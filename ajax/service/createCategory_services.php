<?php
include '../../include/shi-config.php';
include '../../include/functions.php';

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";
$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";
$role = $_REQUEST['role'] ?? ''; 

if ($type == 'agent') {
    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($role) AND" : "";
} else {
    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$role' AND" : "";
}

$headers = apache_request_headers();
$result = [];

if ($method == "create_category") {

    try {

        // Escape inputs to prevent SQL Injection
        $cat_name = mysqli_real_escape_string($con, trim($_POST['cat_name'] ?? ''));
        $cat_url  = mysqli_real_escape_string($con, trim($_POST['cat_url'] ?? ''));
        
        // Catch SEO Fields
        $seo_title       = mysqli_real_escape_string($con, trim($_POST['seo_title'] ?? ''));
        $seo_description = mysqli_real_escape_string($con, trim($_POST['seo_description'] ?? ''));
        $meta_keywords   = mysqli_real_escape_string($con, trim($_POST['meta_keywords'] ?? ''));

       if ($cat_name === '' || $cat_url === '') {
            echo json_encode([
                'type'   => 0,
                'result' => 'Invalid input'
            ]);
            exit;
        } else {
            
            // Updated INSERT query with SEO fields
            $insertQuery = "
                INSERT INTO categories (cat_name, cat_url, seo_title, seo_description, meta_keywords) 
                VALUES ('$cat_name', '$cat_url', '$seo_title', '$seo_description', '$meta_keywords')
            ";

            if ($con->query($insertQuery)) {
                echo json_encode([
                    'type'   => 1,
                    'result' => 'Category created successfully'
                ]);
            } else {
                echo json_encode([
                    'type'   => 0,
                    'result' => 'Insert failed'
                ]);
            }
        }

    } catch (Exception $e) {

        echo json_encode([
            'type'   => 0,
            'result' => $e->getMessage()
        ]);
    }

    exit;
}
else if ($method === 'create_index') {

    $c_query = "
        SELECT 
            'create_cat' AS row_type,
            id,
            cat_name AS category_name,
            cat_url AS category_url,
            cat_schema,
            seo_title,
            seo_description,
            meta_keywords,
            status
        FROM categories WHERE deleted_at = 0
    ";

    $result_data = mysqli_query($con, $c_query);

    $response = [
        "type"   => 0,
        "result" => []
    ];

    if ($result_data && mysqli_num_rows($result_data) > 0) {

        while ($row = mysqli_fetch_assoc($result_data)) {
            $response["result"][] = $row;
        }

        $response["type"] = 1;
    }

    echo json_encode($response);
    exit; 
}
else if ($method === 'update_category') {
    
    // Escape inputs
    $category_name = mysqli_real_escape_string($con, trim($_POST['cat_name'] ?? ''));
    $category_url  = mysqli_real_escape_string($con, trim($_POST['cat_url'] ?? ''));
    $category_id   = (int)$_POST['id'];

    // Catch SEO Fields for Update
    $seo_title       = mysqli_real_escape_string($con, trim($_POST['seo_title'] ?? ''));
    $seo_description = mysqli_real_escape_string($con, trim($_POST['seo_description'] ?? ''));
    $meta_keywords   = mysqli_real_escape_string($con, trim($_POST['meta_keywords'] ?? ''));
     
    if ($category_name === '' || $category_url === '' || $category_id === 0) {
        echo json_encode([
            "type"   => 0,
            "result" => "Invalid input"
        ]);
        exit;
    }

    // Updated UPDATE query with SEO fields
    $c_query = "
        UPDATE categories SET  
            cat_name = '$category_name', 
            cat_url = '$category_url',
            seo_title = '$seo_title',
            seo_description = '$seo_description',
            meta_keywords = '$meta_keywords'
        WHERE id = $category_id
    ";
    
    $result_data = mysqli_query($con, $c_query);

    $response = [
        "type"   => 0,
        "result" => []
    ];

    if ($result_data && mysqli_affected_rows($con) > 0) {
        $response["type"]   = 1;
        $response["result"] = "Category updated successfully";
    } else {
        $response["type"]   = 0;
        $response["result"] = "No changes made or update failed";
    }
    
    echo json_encode($response);
    exit;
}
else if ($method === 'delete_category') {
    
     $category_id = (int)$_POST['id'];
     
     if ($category_id === 0) {
        echo json_encode([
            "type"   => 0,
            "result" => "Invalid input"
        ]);
        exit;
    }

    $c_query = "
        UPDATE categories SET  
            deleted_at = '1'
        WHERE id = $category_id
    ";
    
    $result_data = mysqli_query($con, $c_query);

    $response = [
        "type"   => 0,
        "result" => []
    ];

    if ($result_data && mysqli_affected_rows($con) > 0) {
        $response["type"]   = 1;
        $response["result"] = "Deleted successfully";
    } else {
        $response["type"]   = 0;
        $response["result"] = "Deleted failed";
    }
    
    echo json_encode($response);
    exit;
}
else if ($method === 'update-status') {
    
    $category_id = (int)$_POST['id'];
    $status = $_POST['status'] == 1 ? 1 : 0;
     
    if ($category_id === 0) {
        echo json_encode([
            "type"   => 0,
            "result" => "Invalid input"
        ]);
        exit;
    }

    $c_query = "
        UPDATE categories SET  
           status = '$status'
        WHERE id = $category_id
    ";
    
    $result_data = mysqli_query($con, $c_query);

    $response = [
        "type"   => 0,
        "result" => []
    ];

    if ($result_data && mysqli_affected_rows($con) > 0) {
        $response["type"]   = 1;
        $response["result"] = "Status updated successfully";
    } else {
        $response["type"]   = 0;
        $response["result"] = "Status update failed";
    }
    
    echo json_encode($response);
    exit;
}
?> 