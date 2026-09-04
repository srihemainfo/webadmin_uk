<?php
include '../../include/shi-config.php';
include '../../include/functions.php';
if (isset($con)) {
    mysqli_set_charset($con, "utf8mb4");
}
$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";
$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";
$role = $_REQUEST['role'] ?? ''; 

// Enable all error reporting
// error_reporting(E_ALL); 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Display errors in the browser
// ini_set('display_errors', 1);

// Optional: Show startup errors as well
// ini_set('display_startup_errors', 1);

if ($type == 'agent') {
    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($role) AND" : "";
} else {
    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$role' AND" : "";
}

$headers = apache_request_headers();
$result = [];

$post_csrf = $headers['X-Csrf-Token'] ?? '';


if ($method === 'create_index') {
    $category_id = $_POST['category_id'] ?? '';
    $status = $_POST['status'] ?? '';

    // Added bl.scheduled_at into SELECT query
    $c_query = "
        SELECT 
            'content_cat' AS row_type,
            bl.id,
            bl.category_id,
            bl.blog_title,
            bl.slug,
            bl.published_date,
            bl.faq,
            bl.thumbnail,
            bl.hero_image,
            bl.status,
            bl.scheduled_at,
            ca.cat_name
        FROM blogs_content bl
        LEFT JOIN categories ca ON ca.id = bl.category_id
        WHERE bl.deleted_at = '0'
    ";

    if (!empty($category_id)) {
        $category_id = mysqli_real_escape_string($con, $category_id);
        $c_query .= " AND bl.category_id = '$category_id'";
    }

    if ($status !== '') {
        $status = mysqli_real_escape_string($con, $status);
        $c_query .= " AND bl.status = '$status'";
    }

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

// NEW METHOD ADDED: schedule_blog
else if ($method === 'schedule_blog') {
    $blog_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $scheduled_at = isset($_POST['scheduled_at']) ? $_POST['scheduled_at'] : '';

    if ($blog_id === 0 || empty($scheduled_at)) {
        echo json_encode([
            "type"   => 0,
            "result" => "Invalid ID or Schedule Date"
        ]);
        exit;
    }

    // Prepare update query
    $stmt = $con->prepare("UPDATE blogs_content SET scheduled_at = ? WHERE id = ?");
    if($stmt) {
        $stmt->bind_param("si", $scheduled_at, $blog_id);
        
        if ($stmt->execute()) {
            echo json_encode([
                "type"   => 1,
                "result" => "Scheduled successfully"
            ]);
        } else {
            echo json_encode([
                "type"   => 0,
                "result" => "Failed to update schedule"
            ]);
        }
        $stmt->close();
    } else {
        echo json_encode([
            "type"   => 0,
            "result" => "Query Failed: " . $con->error
        ]);
    }
    exit;
}

else if ($method === 'delete_category') {
    
     $blog_id = $_POST['id'];
    //  var_dump($blog_id);die;
     
      if ($blog_id === 0) {
        echo json_encode([
            "type"   => 0,
            "result" => "Invalid input"
        ]);
        exit;
    }

    $c_query = "
         UPDATE blogs_content SET deleted_at = '1'
        WHERE id = $blog_id
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

else if ($method === 'update_status') {
    
    $category_id = $_POST['id'];
    $status = $_POST['status'] == 1 ? 1 : 0;
    // var_dump($status);die;
     
      if ($category_id === 0) {
        echo json_encode([
            "type"   => 0,
            "result" => "Invalid input"
        ]);
        exit;
    }

    $c_query = "
        UPDATE blogs_content SET  
           status = '$status'
        WHERE id = $category_id
    ";
    // var_dump($c_query);die;
    
    
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

else if ($method === 'auto_interlink') {
    set_time_limit(0);
    ignore_user_abort(true);

    header('Content-Type: application/json');
    
    $blogId = intval($_POST['id']);
    $openAiKey = '';
    
    $query = mysqli_query($con, "SELECT content FROM blogs_content WHERE id = $blogId LIMIT 1");
    $blog = mysqli_fetch_assoc($query);
    
    if (!$blog) {
        echo json_encode(['type' => 0, 'message' => 'Blog not found.']);
        exit;
    }
    
    $rawContent = $blog['content'];

    $linksQuery = mysqli_query($con, "SELECT b.slug, b.blog_title, c.cat_url FROM blogs_content b JOIN categories c ON b.category_id = c.id WHERE b.status = 1 AND (b.deleted_at = '0' OR b.deleted_at IS NULL) AND b.id != $blogId LIMIT 15");
    $internalLinksData = "";
    while ($link = mysqli_fetch_assoc($linksQuery)) {
        $url = "https://www.goride.run" . rtrim($link['cat_url'], '/') . "/" . ltrim($link['slug'], '/');
        $internalLinksData .= "- URL: {$url} (Context: {$link['blog_title']})\n";
    }

    if (empty(trim($internalLinksData))) {
        echo json_encode(['type' => 0, 'message' => 'No active published blogs found to link to.']);
        exit;
    }

    $systemPrompt = "You are an expert SEO developer. Your EXCLUSIVE task is to inject internal links into the provided raw HTML blog content while perfectly maintaining the continuity and flow of the existing paragraphs.

AVAILABLE LINKS:
{$internalLinksData}

MANDATORY RULES:
1. DO NOT inject raw blog titles disruptively. Maintain the exact original narrative and continuity of the article.
2. Find highly relevant words natively existing in the text (e.g., 'driver app', 'taxi business', 'ride-hailing') and convert them into anchor texts for the provided URLs. You may make very minor, natural sentence tweaks if absolutely necessary to fit a relevant keyword smoothly.
3. Distribute the links evenly throughout the ENTIRE article from start to finish. Ensure the full length of the article is processed and returned. Do not stop after the first few paragraphs.
4. Format EVERY injected link EXACTLY with this inline CSS: <a href='EXACT_URL_FROM_LIST' style='color: #007bff; text-decoration: underline; font-weight: 600;'>relevant phrase</a>
5. Use only the exact URLs provided in the list.
6. Return ONLY the complete raw HTML string. DO NOT wrap the output in markdown blocks. Return the entire processed content without truncating it.";

    $postData = [
        "model" => "gpt-4o",
        "messages" => [
            ["role" => "system", "content" => $systemPrompt],
            ["role" => "user", "content" => $rawContent]
        ],
        "temperature" => 0.4,
        "max_tokens" => 4096
    ];

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $openAiKey
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 600); 

    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo json_encode(['type' => 0, 'message' => 'cURL Error: ' . curl_error($ch)]);
        curl_close($ch);
        exit;
    }
    
    curl_close($ch);
    $responseData = json_decode($response, true);

    if (isset($responseData['choices'][0]['message']['content'])) {
        $updatedContent = $responseData['choices'][0]['message']['content'];
        
        $updatedContent = preg_replace('/^```html\s*/i', '', $updatedContent);
        $updatedContent = preg_replace('/^```\s*/i', '', $updatedContent);
        $updatedContent = preg_replace('/\s*```$/i', '', $updatedContent);
        $updatedContent = trim($updatedContent);
        
        $stmt = $con->prepare("UPDATE blogs_content SET content = ? WHERE id = ?");
        $stmt->bind_param("si", $updatedContent, $blogId);
        
        if ($stmt->execute()) {
            echo json_encode(['type' => 1, 'message' => 'Smart interlinks injected successfully!']);
        } else {
            echo json_encode(['type' => 0, 'message' => 'Failed to save updated content to database.']);
        }
    } else {
        $errorMsg = isset($responseData['error']['message']) ? $responseData['error']['message'] : 'OpenAI API failed to process the request.';
        echo json_encode(['type' => 0, 'message' => $errorMsg]);
    }
    exit; 
}

else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
  

    $action            = $_POST['action'] ?? '';
    $method            = $_POST['method'] ?? '';
    $category_id       = $_POST['category_id'] ?? '';
    $published_date    = $_POST['published_date'] ?? '';
    $sub_title         = $_POST['sub_title'] ?? '';
    $blog_title        = $_POST['blog_title'] ?? '';
    $slug              = $_POST['slug'] ?? '';
    $blog_id              = $_POST['blog_id'] ?? '';
    $read_minutes      = $_POST['read_minutes'] ?? '';
    $seo_title         = $_POST['seo_title'] ?? '';
    $descripe         = $_POST['descripe'] ?? '';
    $meta_description  = $_POST['meta_description'] ?? '';
    $meta_keywords     = $_POST['meta_keywords'] ?? '';
    $content           = $_POST['content'] ?? '';
    // $label_h        = $_POST['label_h'] ?? '';
    // $content_p        = $_POST['content_p'] ?? '';
    $content        = $_POST['content'] ?? '';
    $faq_answer        = $_POST['faq_answer'] ?? '';
    $faq_question       = $_POST['faq_question'] ?? '';
    // var_dump($_POST);die;
    

    $getImg = $con->query("SELECT hero_image, thumbnail FROM blogs_content WHERE id = '$blog_id'");
    $rowImg = $getImg->fetch_assoc();
    
    
    

    /* ================= HERO IMAGE UPLOAD ================= */

   // HERO IMAGE
    if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === UPLOAD_ERR_OK) {
    
        // $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/blog/';
        // $uploadPath = 'assets/images/blog/';
    
        // if (!is_dir($uploadDir)) {
        //     mkdir($uploadDir, 0755, true);
        // }
    
        // $ext = pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION);
        // $heroName = uniqid('hero_') . '.' . $ext;
    
        // if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $uploadDir . $heroName)) {
        //     $hero_image = $uploadPath . $heroName;
        // }
        
        $ch = curl_init();
    
        $postData = [
            'image' => new CURLFile(
                $_FILES['hero_image']['tmp_name'],
                $_FILES['hero_image']['type'],
                $_FILES['hero_image']['name']
            ),
            'name'     => ''.$slug,
            'img_type' => 'blog'
        ];
    
    
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://mobapi.goride.run/api/s3-upload-image',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => [
    'Accept: application/json',
    'Authorization: Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()'
],
            CURLOPT_TIMEOUT => 30
        ]);
    
    
        $response = curl_exec($ch);
    
        if ($response == false) {
            echo json_encode([
                'status' => false,
                'message' => curl_error($ch)
            ]);
            curl_close($ch);
            exit;
        }
    
        curl_close($ch);
    
        $uploadRes = json_decode($response, true);
    
        if (!$uploadRes || !$uploadRes['status']) {
            echo json_encode([
                'status' => false,
                'message' => 'Image upload failed',
                'raw_response' => $response  
            ]);
            exit;
        }
    
        $hero_image = $uploadRes['data']['url'];
    }else{
        
        $hero_image = $rowImg['hero_image'];
    }

 
    
    // THUMBNAIL
    if (isset($_FILES['thumb_nail']) && $_FILES['thumb_nail']['error'] === UPLOAD_ERR_OK) {
    
        // $uploadDir  = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/thumbnail/';
        // $uploadPath = 'assets/images/thumbnail/';
    
        // if (!is_dir($uploadDir)) {
        //     mkdir($uploadDir, 0755, true);
        // }
    
        // $ext = pathinfo($_FILES['thumb_nail']['name'], PATHINFO_EXTENSION);
        // $thumbName = uniqid('thumb_') . '.' . $ext;
    
        // if (move_uploaded_file($_FILES['thumb_nail']['tmp_name'], $uploadDir . $thumbName)) {
        //     $thumbnail = $uploadPath . $thumbName;
        // }
        
        $ch = curl_init();
    
        $postData = [
            'image' => new CURLFile(
                $_FILES['thumb_nail']['tmp_name'],
                $_FILES['thumb_nail']['type'],
                $_FILES['thumb_nail']['name']
            ),
            'name'     => ' '.$slug,
            'img_type' => 'blog'
        ];
    
    
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://mobapi.goride.run/api/s3-upload-image',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => [
    'Accept: application/json',
    'Authorization: Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()'
],
            CURLOPT_TIMEOUT => 30
        ]);
    
    
        $response = curl_exec($ch);
    
        if ($response === false) {
            echo json_encode([
                'status' => false,
                'message' => curl_error($ch)
            ]);
            curl_close($ch);
            exit;
        }
    
        curl_close($ch);
    
        $uploadRes = json_decode($response, true);
    
        if (!$uploadRes || !$uploadRes['status']) {
            echo json_encode([
                'status' => false,
                'message' => 'Image upload failed',
                'raw_response' => $response  
            ]);
            exit;
        }
    
        $thumbnail = $uploadRes['data']['url'];
    }else{
        $thumbnail  = $rowImg['thumbnail'];
    }

   
   $faqData = [];

    if (!empty($_POST['faq_question']) && !empty($_POST['faq_answer'])) {
        foreach ($_POST['faq_question'] as $index => $question) {
            $faqData[] = [
                'question' => trim($question),
                'answer'   => trim($_POST['faq_answer'][$index] ?? '')
            ];
        }
    }else{
        $faqData = '[]';
    }
    
    
    $faqJson = !empty($faqData) ? json_encode($faqData, JSON_UNESCAPED_UNICODE) : '[]';
    // var_dump($faqJson);die;
    

    $labelData = [];

    if ($action === 'edit_blog' && $method === 'edit_content') {

    // 1. Use ? placeholders instead of direct variables
    $updateQuery = "
        UPDATE blogs_content SET
            category_id      = ?,
            published_date   = ?,
            sub_title        = ?,
            blog_title       = ?,
            slug             = ?,
            read_minutes     = ?,
            content          = ?,
            seo_title        = ?,
            meta_description = ?,
            meta_keywords    = ?,
            faq              = ?,
            thumbnail        = ?,
            description      = ?,
            hero_image       = ?
        WHERE id = ?
    ";

    // 2. Prepare the statement
    $stmt = $con->prepare($updateQuery);

    if ($stmt) {
        // 3. Bind the parameters 
        // "ssssssssssssssi" means 14 strings and 1 integer (the blog_id).
        $stmt->bind_param(
            "ssssssssssssssi",
            $category_id,
            $published_date,
            $sub_title,
            $blog_title,
            $slug,
            $read_minutes,
            $content,
            $seo_title,
            $meta_description,
            $meta_keywords,
            $faqJson,
            $thumbnail,
            $descripe,
            $hero_image,
            $blog_id
        );

        // 4. Execute the query
        if ($stmt->execute()) {
            echo json_encode([
                'type' => 1,
                'result' => 'Content updated successfully'
            ]);
        } else {
            echo json_encode([
                'type' => 0,
                'result' => 'Update failed: ' . $stmt->error
            ]);
        }
        
        $stmt->close();
        exit;
        
    } else {
        // If the preparation fails, catch it here cleanly instead of crashing
        echo json_encode([
            'type' => 0,
            'result' => 'Query preparation failed: ' . $con->error
        ]);
        exit;
    }
}
}

resutGJHIP:
echo json_encode($result);
?>