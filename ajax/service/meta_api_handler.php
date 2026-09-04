<?php
include '../../include/shi-config.php';
include '../../include/functions.php';

if (isset($_POST['method']) && $_POST['method'] === 'publish_ig_post') {
    header('Content-Type: application/json');

    $accessToken = "YOUR_SYSTEM_USER_ACCESS_TOKEN_HERE"; 
    $igUserId = "YOUR_INSTAGRAM_BUSINESS_ACCOUNT_ID_HERE";
    $baseDomain = "https://www.yourdomain.com"; 

    $message = $_POST['message'] ?? '';

    if (!isset($_FILES['media_file']) || $_FILES['media_file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => false, 'message' => 'Image upload failed on the server.']);
        exit;
    }

    $uploadDir = '../../uploads/instagram/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileTmpPath = $_FILES['media_file']['tmp_name'];
    $fileName = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", basename($_FILES['media_file']['name']));
    $destPath = $uploadDir . $fileName;

    if (!move_uploaded_file($fileTmpPath, $destPath)) {
        echo json_encode(['status' => false, 'message' => 'Failed to save image to server.']);
        exit;
    }

    $publicImageUrl = rtrim($baseDomain, '/') . "/uploads/instagram/" . $fileName;

    $containerUrl = "https://graph.facebook.com/v19.0/{$igUserId}/media";
    $containerParams = [
        'image_url' => $publicImageUrl,
        'caption' => $message,
        'access_token' => $accessToken
    ];

    $ch1 = curl_init();
    curl_setopt($ch1, CURLOPT_URL, $containerUrl);
    curl_setopt($ch1, CURLOPT_POST, true);
    curl_setopt($ch1, CURLOPT_POSTFIELDS, http_build_query($containerParams));
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
    $response1 = curl_exec($ch1);
    curl_close($ch1);

    $result1 = json_decode($response1, true);

    if (isset($result1['error'])) {
        echo json_encode([
            'status' => false, 
            'message' => 'Failed to create Instagram media container.', 
            'error_details' => $result1['error']
        ]);
        exit;
    }

    $publishUrl = "https://graph.facebook.com/v19.0/{$igUserId}/media_publish";
    $publishParams = [
        'creation_id' => $result1['id'],
        'access_token' => $accessToken
    ];

    $ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, $publishUrl);
    curl_setopt($ch2, CURLOPT_POST, true);
    curl_setopt($ch2, CURLOPT_POSTFIELDS, http_build_query($publishParams));
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    $response2 = curl_exec($ch2);
    curl_close($ch2);

    $result2 = json_decode($response2, true);

    if (isset($result2['error'])) {
        echo json_encode([
            'status' => false, 
            'message' => 'Failed to publish the post to Instagram.', 
            'error_details' => $result2['error']
        ]);
        exit;
    }

    echo json_encode([
        'status' => true, 
        'message' => 'Post published successfully!',
        'ig_post_id' => $result2['id']
    ]);
    exit;
}
?>