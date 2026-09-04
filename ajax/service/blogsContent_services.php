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


// error_reporting(E_ALL); 
// error_reporting(E_ALL);


// ini_set('display_errors', 1);


// ini_set('display_startup_errors', 1);

if ($type == 'agent') {
    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($role) AND" : "";
} else {
    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$role' AND" : "";
}

$headers = apache_request_headers();
$result = [];

$post_csrf = $headers['X-Csrf-Token'] ?? '';


// blogsContent_services.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action            = $_POST['action'] ?? '';
    $method            = $_POST['method'] ?? '';
    $category_id       = $_POST['category_id'] ?? '';
    $published_date    = $_POST['published_date'] ?? '';
    $sub_title    = $_POST['sub_title'] ?? '';
    $blog_title        = $_POST['blog_title'] ?? '';
    $slug              = $_POST['slug'] ?? '';
    $read_minutes      = $_POST['read_minutes'] ?? '';
    $seo_title         = $_POST['seo_title'] ?? '';
    $meta_description  = $_POST['meta_description'] ?? '';
    $meta_keywords     = $_POST['meta_keywords'] ?? '';
    // $label_h        = $_POST['label_h'] ?? '';
    // $content_p        = $_POST['content_p'] ?? '';
    // $label_h        = $_POST['label_h'] ?? '';
    $content        = $_POST['content'] ?? '';
    $faq_answer        = $_POST['faq_answer'] ?? '';
    $faq_question       = $_POST['faq_question'] ?? '';
    $description       = $_POST['descripe'] ?? '';
    // var_dump($_POST);die;

    $thumbnail  = '';
    $hero_image = '';

    /* ================= HERO IMAGE UPLOAD ================= */

    if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {

        // server path
        // $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/blog/';

        // // DB path
        // $uploadPath = 'assets/images/blog/';

        // if (!is_dir($uploadDir)) {
        //     mkdir($uploadDir, 0755, true);
        // }

        // $ext = pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION);
        // $heroName = uniqid('hero_') . '.' . $ext;

        // if (move_uploaded_file(
        //     $_FILES['hero_image']['tmp_name'],
        //     $uploadDir . $heroName
        // )) {
        //     // store relative path in DB
        //     $hero_image = $uploadPath . $heroName;
        // }
        
        $ch = curl_init();
    
        $postData = [
            'image' => new CURLFile(
                $_FILES['hero_image']['tmp_name'],
                $_FILES['hero_image']['type'],
                $_FILES['hero_image']['name']
            ),
            'name'     => 'blog-'.$slug.'-goride',
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
    }
    
 
    
    if (isset($_FILES['thumb_nail']) && $_FILES['thumb_nail']['error'] === UPLOAD_ERR_OK) {

        // $uploadDir  = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/thumbnail/';
        // $uploadPath = 'assets/images/thumbnail/';
    
        // if (!is_dir($uploadDir)) {
        //     mkdir($uploadDir, 0755, true);
        // }
    
        // $ext = pathinfo($_FILES['thumb_nail']['name'], PATHINFO_EXTENSION);
        // $heroName = uniqid('thumb_', true) . '.' . $ext;
    
        // if (move_uploaded_file(
        //     $_FILES['thumb_nail']['tmp_name'],
        //     $uploadDir . $heroName
        // )) {
        //     $thumbnail = $uploadPath . $heroName; // store in DB
        // }
        
        $ch = curl_init();
    
        $postData = [
            'image' => new CURLFile(
                $_FILES['thumb_nail']['tmp_name'],
                $_FILES['thumb_nail']['type'],
                $_FILES['thumb_nail']['name']
            ),
            'name'     => 'blog-thumb'.$slug.'-goride', 
            'img_type' => 'blog',
            'auth_key' => 'asdfghjklpoiuytrewqzxcvbnm!@$%^&*()'
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
        
   }
   
   $faqData = [];

    if (!empty($_POST['faq_question']) && !empty($_POST['faq_answer'])) {
        foreach ($_POST['faq_question'] as $index => $question) {
            $faqData[] = [
                'question' => trim($question),
                'answer'   => trim($_POST['faq_answer'][$index] ?? '')
            ];
        }
    }
    
    $faqJson = !empty($faqData) ? json_encode($faqData, JSON_UNESCAPED_UNICODE) : null;
    
      $labelData = [];

    // if (!empty($_POST['label_h']) && !empty($_POST['content_p'])) {
    //     foreach ($_POST['label_h'] as $index => $question) {
    //         $labelData[] = [
    //             'question' => trim($question),
    //             'answer'   => trim($_POST['content_p'][$index] ?? '')
    //         ];
    //     }
    // }
    
    $labelJson = !empty($labelData) ? json_encode($labelData, JSON_UNESCAPED_UNICODE) : null;

    if ($action === 'create_blog' && $method === 'blogs_content') {
        $checkQuery = "SELECT id FROM blogs_content WHERE slug = '$slug'";
       
        $checkResult = $con->query($checkQuery);

        if ($checkResult && $checkResult->num_rows > 0) {

            echo json_encode([
                'type' => 0,
                'result' => 'Category URL already exists'
            ]);
            exit;

        } else {

            $stmt = $con->prepare("
                INSERT INTO blogs_content (
                    category_id,
                    published_date,
                    blog_title,
                    sub_title,
                    slug,
                    read_minutes,
                    thumbnail,
                    hero_image,
                    content,
                    seo_title,
                    meta_description,
                    meta_keywords,
                    description,
                    faq
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->bind_param(
                "issssissssssss",
                $category_id,
                $published_date,
                $blog_title,
                $sub_title,
                $slug,
                $read_minutes,
                $thumbnail,
                $hero_image,
                $content,
                $seo_title,
                $meta_description,
                $meta_keywords,
                $description,
                $faqJson
            );

            if ($stmt->execute()) {
                $sitemap_needed = isset($_POST['sitemap_needed']) && ($_POST['sitemap_needed'] == '1' || $_POST['sitemap_needed'] === 'on') ? 1 : 0;

                $catUrl = '';
                if (!empty($category_id)) {
                    $catQ = mysqli_query($con, "SELECT cat_url FROM categories WHERE id = " . intval($category_id));
                    if ($catQ && $catRow = mysqli_fetch_assoc($catQ)) {
                        $catUrl = $catRow['cat_url'];
                    }
                }

                require_once __DIR__ . '/sitemapservice.php';
                $sitemapService = new SitemapService();

                try {
                    if ($sitemap_needed == 1) {
                        $sitemapService->addBlog($slug, $catUrl, date('Y-m-d'));
                    } else {
                        $sitemapService->removeBlog($slug, $catUrl);
                    }
                } catch (Exception $e) {
                    error_log('Sitemap error in create blog: ' . $e->getMessage());
                }

                echo json_encode([
                    'type' => 1,
                    'result' => 'Content created successfully'
                ]);
                
                exit;
            } else {
                echo json_encode([
                    'type' => 0,
                    'result' => 'Insert failed: ' . $stmt->error
                ]);
                
                exit;
            }

        }
    }
}

// else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
//     // var_dump('hhjjg');die;

//     $action            = $_POST['action'] ?? '';
//     $method            = $_POST['method'] ?? '';
//     $category_id       = $_POST['category_id'] ?? '';
//     $published_date    = $_POST['published_date'] ?? '';
//     $blog_title        = $_POST['blog_title'] ?? '';
//     $slug              = $_POST['slug'] ?? '';
//     $blog              = $_POST['blog_id'] ?? '';
    
//     $read_minutes      = $_POST['read_minutes'] ?? '';
//     $seo_title         = $_POST['seo_title'] ?? '';
//     $meta_description  = $_POST['meta_description'] ?? '';
//     $meta_keywords     = $_POST['meta_keywords'] ?? '';
//     $content           = $_POST['content'] ?? '';
//     $faq_answer        = $_POST['faq_answer'] ?? '';
//     $faq_question       = $_POST['faq_question'] ?? '';
//     // var_dump($faq_question);die;

//     $thumbnail  = '';
//     $hero_image = '';

//     /* ================= HERO IMAGE UPLOAD ================= */

//     if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === 0) {

//         // server path
//         $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/blog/';

//         // DB path
//         $uploadPath = 'assets/images/blog/';

//         if (!is_dir($uploadDir)) {
//             mkdir($uploadDir, 0755, true);
//         }

//         $ext = pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION);
//         $heroName = uniqid('hero_') . '.' . $ext;

//         if (move_uploaded_file(
//             $_FILES['hero_image']['tmp_name'],
//             $uploadDir . $heroName
//         )) {
//             // store relative path in DB
//             $hero_image = $uploadPath . $heroName;
//         }
//     }
    
 
    
//     if (isset($_FILES['thumb_nail']) && $_FILES['thumb_nail']['error'] === UPLOAD_ERR_OK) {

//         $uploadDir  = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/thumbnail/';
//         $uploadPath = 'assets/images/thumbnail/';
    
//         if (!is_dir($uploadDir)) {
//             mkdir($uploadDir, 0755, true);
//         }
    
//         $ext = pathinfo($_FILES['thumb_nail']['name'], PATHINFO_EXTENSION);
//         $heroName = uniqid('thumb_', true) . '.' . $ext;
    
//         if (move_uploaded_file(
//             $_FILES['thumb_nail']['tmp_name'],
//             $uploadDir . $heroName
//         )) {
//             $thumbnail = $uploadPath . $heroName; // store in DB
//         }
//   }
   
//   $faqData = [];

//     if (!empty($_POST['faq_question']) && !empty($_POST['faq_answer'])) {
//         foreach ($_POST['faq_question'] as $index => $question) {
//             $faqData[] = [
//                 'question' => trim($question),
//                 'answer'   => trim($_POST['faq_answer'][$index] ?? '')
//             ];
//         }
//     }
    
//     $faqJson = !empty($faqData) ? json_encode($faqData, JSON_UNESCAPED_UNICODE) : null;
// // var_dump($faqJson);die;
//     /* ===================================================== */

//     if ($action === 'edit_blog' && $method === 'edit_content') {

//         // if ($category_id === '' || $blog_title === '') {
//         //     echo json_encode([
//         //         'type' => 0,
//         //         'result' => 'Invalid input'
//         //     ]);
//         //     exit;
//         // }

//         $checkQuery = "SELECT id FROM blogs_content WHERE category_id = '$category_id'";
//         $checkResult = $con->query($checkQuery);

//         // if ($checkResult && $checkResult->num_rows > 0) {

//         //     echo json_encode([
//         //         'type' => 0,
//         //         'result' => 'Category URL already exists'
//         //     ]);
//         //     exit;

//         // } else {

//         //     $insertQuery = "
//         //         INSERT INTO blogs_content (
//         //             category_id,
//         //             published_date,
//         //             blog_title,
//         //             slug,
//         //             read_minutes,
//         //             thumbnail,
//         //             hero_image,
//         //             content,
//         //             seo_title,
//         //             meta_description,
//         //             meta_keywords,
//         //             faq
//         //         ) VALUES (
//         //             '$category_id',
//         //             '$published_date',
//         //             '$blog_title',
//         //             '$slug',
//         //             '$read_minutes',
//         //             '$thumbnail',
//         //             '$hero_image',
//         //             '$content',
//         //             '$seo_title',
//         //             '$meta_description',
//         //             '$meta_keywords',
//         //             '$faqJson'
//         //         )
//         //     ";

//         //     if ($con->query($insertQuery)) {
//         //         echo json_encode([
//         //             'type' => 1,
//         //             'result' => 'Content created successfully'
//         //         ]);
//         //     } else {
//         //         echo json_encode([
//         //             'type' => 0,
//         //             'result' => 'Insert failed: ' . $con->error
//         //         ]);
//         //     }
//         // }
//     }
// }

// else if ($method === 'create_index') {

//     $c_query = "
//         SELECT 
//             'create_cat' AS row_type,
//             id,
//             cat_name AS category_name,
//             cat_url AS category_url
//         FROM categories WHERE deleted_at = 0
//     ";

//     $result_data = mysqli_query($con, $c_query);

//     $response = [
//         "type"   => 0,
//         "result" => []
//     ];

//     if ($result_data && mysqli_num_rows($result_data) > 0) {

//         while ($row = mysqli_fetch_assoc($result_data)) {
//             $response["result"][] = $row;
//         }

//         $response["type"] = 1;
//     }

//     echo json_encode($response);
//     exit; 
// }
// else if ($method === 'update_category') {
    
//      $category_name = trim($_POST['cat_name'] ?? '');
//      $category_url = trim($_POST['cat_url'] ?? '');
//      $category_id = $_POST['id'];
     
//       if ($category_name === '' || $category_url === '' || $category_id === 0) {
//         echo json_encode([
//             "type"   => 0,
//             "result" => "Invalid input"
//         ]);
//         exit;
//     }

//     $c_query = "
//         UPDATE categories SET  
//             cat_name = '$category_name', cat_url = '$category_url'
//         WHERE id = $category_id
//     ";
    
    
//     $result_data = mysqli_query($con, $c_query);

//     $response = [
//         "type"   => 0,
//         "result" => []
//     ];

//     if ($result_data && mysqli_affected_rows($con) > 0) {
//         $response["type"]   = 1;
//         $response["result"] = "Category updated successfully";
//     } else {
//         $response["type"]   = 0;
//         $response["result"] = "No changes made or update failed";
//     }
    
//     echo json_encode($response);
//     exit;
// }
// else if ($method === 'delete_category') {
    
//      $category_id = $_POST['id'];
     
//       if ($category_name === '' || $category_url === '' || $category_id === 0) {
//         echo json_encode([
//             "type"   => 0,
//             "result" => "Invalid input"
//         ]);
//         exit;
//     }

//     $c_query = "
//         UPDATE categories SET  
//             deleted_at = '1'
//         WHERE id = $category_id
//     ";
    
    
//     $result_data = mysqli_query($con, $c_query);

//     $response = [
//         "type"   => 0,
//         "result" => []
//     ];

//     if ($result_data && mysqli_affected_rows($con) > 0) {
//         $response["type"]   = 1;
//         $response["result"] = "Deleted successfully";
//     } else {
//         $response["type"]   = 0;
//         $response["result"] = "Deleted failed";
//     }
    
//     echo json_encode($response);
//     exit;
// }
resutGJHIP:
echo json_encode($result);