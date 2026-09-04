<?php
include '../../include/shi-config.php';
include '../../include/functions.php';

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";
$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";
$role = $_REQUEST['role'] ?? ''; 

// error_reporting(E_ALL); 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
$con->set_charset('utf8mb4');
$memid = $_SESSION['memid'];

if ($type == 'agent') {
    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($role) AND" : "";
} else {
    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$role' AND" : "";
}

$headers = apache_request_headers();
$result = [];

$post_csrf = $headers['X-Csrf-Token'] ?? '';

function getClientIPv4() {
    $headers = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
    foreach ($headers as $key) {
        if (!empty($_SERVER[$key])) {
            $ipList = explode(',', $_SERVER[$key]);
            foreach ($ipList as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    return $ip;
                }
            }
        }
    }
    return '0.0.0.0';
}

function uploadToS3ViaPresigned($apiBase, $file)
{
    // 1. Get presigned URL
    $ch = curl_init($apiBase . 'manual-kyc/presigned-url');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'file_name' => $file['name'],
            'file_type' => $file['type']
        ]),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json']
    ]);

    $res = curl_exec($ch);
    curl_close($ch);

    $presigned = json_decode($res, true);

    if (empty($presigned['status'])) {
        return ['status' => false, 'error' => 'Failed to get presigned URL'];
    }

    // 2. Upload file to S3
    $fileData = file_get_contents($file['tmp_name']);

    $ch = curl_init($presigned['upload_url']);
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: ' . $file['type']],
        CURLOPT_POSTFIELDS => $fileData
    ]);

    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!in_array($httpCode, [200, 201])) {
        return ['status' => false, 'error' => 'S3 upload failed'];
    }

    return [
        'status' => true,
        'file_url' => $presigned['file_url']
    ];
}

function rollbackS3($apiBase, array $urls)
{
    foreach ($urls as $url) {
        if (!$url) continue;

        $ch = curl_init($apiBase . 'manual-kyc/delete-s3-object');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode(['file_url' => $url]),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json']
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
}



$clientIp = getClientIPv4();

if ($method == "send_whats_content") {

    $number  = BlockSQLInjection($_POST['number']);
    $message = $_POST['message'];

    try {

        $sql = "SELECT id, mobile, name, email 
                FROM user_register 
                WHERE mobile = ? AND deletes = '0' AND roll_id = 0
                LIMIT 1";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $number);
        $stmt->execute();
        $resultUser = $stmt->get_result();
        $stmt->close();

        if ($resultUser->num_rows == 0) {
            $result['type'] = 0;
            $result['result'] = "No user found with this number.";
            return;
        }

        $row = $resultUser->fetch_assoc();

        $user_name   = $row['name'];
        $user_mobile = $row['mobile'];

        $ins = "INSERT INTO whatsapp_bulk_message 
                    (details, name, to_whatsapp, status, created_at, updated_at)
                VALUES (?, ?, ?, 'pending', NOW(), NOW())";

        $stmt2 = $con->prepare($ins);
        $stmt2->bind_param("sss", $message, $user_name, $user_mobile);
        $stmt2->execute();
        $stmt2->close();

        // ---- SUCCESS ----
        $result['type'] = 1;
        $result['result'] = "WhatsApp message initiated.";

    } catch (Exception $e) {

        $result['type'] = 0;
        $result['result'] = $e->getMessage();
    }
}

if ($method == "get_kyc_location") {

    $id = ($_POST['id']);

    try {

        $locationSql = mysqli_query($con, "
        SELECT s_lat, s_lang FROM  kyc_details WHERE user_id = '$id';
        ");

        $location_content = mysqli_fetch_assoc($locationSql);

        $result['type'] = 1;
        $result['result'] = $location_content;

    } catch (Exception $e) {

        $result['type'] = 0;
        $result['result'] = $e->getMessage();
    }

}

if ($method == "get_temp_content") {

    $template = ($_POST['template']);
    

    try {

        $historySql = mysqli_query($con, "
        SELECT body FROM  wamail_templates WHERE id = '$template';
        ");

        $template_content = mysqli_fetch_assoc($historySql);

        $result['type'] = 1;
        $result['result'] = $template_content;

    } catch (Exception $e) {

        $result['type'] = 0;
        $result['result'] = $e->getMessage();
    }

}

if ($method == "get_whats_content") {

    $number = BlockSQLInjection($_POST['number']);

    try {

        $historySql = mysqli_query($con, "
        SELECT created_at, to_whatsapp, details, whatapp_campain
        FROM whatsapp_bulk_message
        WHERE to_whatsapp = '$number'
        AND (details LIKE '%KYC Verification%' 
        OR details LIKE '%Vehicle Verification%')
        ORDER BY id DESC
        ");

    
    
        if (!$historySql) {
            throw new Exception("Query failed: " . mysqli_error($con));
        }

        if (mysqli_num_rows($historySql) == 0) {
            $result['type'] = 0;
            $result['result'] = "No user found with this number.";
            return;
        }

        $allRows = [];
        while ($row = mysqli_fetch_assoc($historySql)) {
            $allRows[] = $row;
        }

        $result['type'] = 1;
        $result['result'] = $allRows;

    } catch (Exception $e) {

        $result['type'] = 0;
        $result['result'] = $e->getMessage();
    }

    

}

if ($method == "getFormsList") {

    // Validate input
    if (!isset($_POST['user_id']) || empty($_POST['user_id'])) {
        echo json_encode([
            'type' => 0,
            'result' => 'User ID is required.'
        ]);
        exit;
    }

    $u_id = BlockSQLInjection($_POST['user_id']); // Prevent SQL injection

    try {

        // Prepare SQL query with LEFT JOIN
        $sql = "SELECT ft.id, ft.name, ud.empty_url, ud.upload_url
                FROM form_types ft 
                LEFT JOIN users_docs ud 
                    ON ud.type_id = ft.id 
                    AND ud.user_id = ?
                WHERE ft.deletes = 0";

        $stmt = mysqli_prepare($con, $sql);

        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($con));
        }

        mysqli_stmt_bind_param($stmt, "i", $u_id);
        mysqli_stmt_execute($stmt);

        $result_set = mysqli_stmt_get_result($stmt);

        if (!$result_set) {
            throw new Exception("Query execution failed: " . mysqli_error($con));
        }

        // Check if rows exist
        if (mysqli_num_rows($result_set) == 0) {
            echo json_encode([
                'type' => 0,
                'result' => 'No forms found.'
            ]);
            exit;
        }

        // Fetch all data
        $forms = [];
        while ($row = mysqli_fetch_assoc($result_set)) {
            $forms[] = $row;
        }
        
        $data_u = mysqli_fetch_assoc(mysqli_query($con, 
            "SELECT id, type 
             FROM kyc_details 
             WHERE user_id = $u_id AND deletes = 0 
             LIMIT 1"
        ));
        
        $u_type = $data_u['type'] ?? '';

        // SUCCESS RESPONSE
        echo json_encode([
            'type' => 1,
            'result' => $forms,
            'u_id' => $u_id,
            'auth_id' => $memid,
            'user_role' => $u_type
        ]);
        exit;

    } catch (Exception $e) {

        // ERROR RESPONSE
        echo json_encode([
            'type' => 0,
            'result' => $e->getMessage()
        ]);
        exit;
    }
}

if ($method == "sent_forms") {

    if (empty($_POST['user_id']) || empty($_POST['id'])) {
        echo json_encode(['type' => 0, 'result' => 'User ID and Form ID are required.']);
        exit;
    }

    $u_id = BlockSQLInjection($_POST['user_id']);
    $id   = BlockSQLInjection($_POST['id']);

    try {

        $sql = "SELECT ud.*, ur.mobile, ur.name
                FROM users_docs ud
                JOIN user_register ur ON ur.id = ud.user_id
                WHERE ud.type_id = ? AND ud.user_id = ? AND ud.deletes = 0
                LIMIT 1";

        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $id, $u_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!$data) {
            echo json_encode(['type' => 0, 'result' => 'No form found.']);
            exit;
        }

        $user_name   = $data['name'];
        $user_mobile = $data['mobile'];

        // Pick PDF URL
        $pdfUrl = !empty($data['empty_url']) ? $data['empty_url'] : $data['upload_url'];

        if (empty($pdfUrl)) {
            echo json_encode(['type' => 0, 'result' => 'No PDF available.']);
            exit;
        }

        // --------------------------------------------------
        // 2) Send WhatsApp API
        // --------------------------------------------------
        $payload = [
            "chatId" => $user_mobile . "@c.us",
            "contentType" => "MessageMediaFromURL",
            "content" => $pdfUrl
        ];

        $headers = [
            "Content-Type: application/json",
            "x-api-key: " . WHATSAPP_API_KEY_1
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, WHATSAPP_API_URL_1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Determine success or failure
        $status = 'failed';
        if ($httpCode == 200 && stripos($response, 'success') !== false) {
            $status = 'success';
        }

        $ins = "INSERT INTO whatsapp_bulk_message
                    (details, name, to_whatsapp, status, created_at, updated_at, ip, from_whatsapp, media_url)
                VALUES (?, ?, ?, ?, NOW(), NOW(), ?, ?, ?)";

        $stmt2 = mysqli_prepare($con, $ins);

        $details = "PDF FILE";
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $session_id = WHATSAPP_SESSION_ID_1;

        mysqli_stmt_bind_param(
            $stmt2,
            "sssssss",
            $details,
            $user_name,
            $user_mobile,
            $status,
            $ip,
            $session_id,
            $pdfUrl
        );

        mysqli_stmt_execute($stmt2);
        $insert_id = mysqli_insert_id($con);
        mysqli_stmt_close($stmt2);

        // --------------------------------------------------
        // 4) Final Response
        // --------------------------------------------------
        echo json_encode([
            'type' => ($status == 'success' ? 1 : 0),
            'result' => ($status == 'success' ? 'Pdf sent successfully' : 'Pdf not sent'),
        ]);
        exit;

    } catch (Exception $e) {

        echo json_encode([
            'type' => 0,
            'result' => $e->getMessage()
        ]);
        exit;
    }
}

if ($method == "switch_role") {

    if (empty($_POST['user_id']) || empty($_POST['role'])) {
        echo json_encode(['type' => '0', 'result' => 'User ID and Role are required.']); exit;
    }

    $u_id = intval($_POST['user_id']);
    $role = BlockSQLInjection($_POST['role']);

    try {

        $data = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM kyc_details WHERE user_id=$u_id AND deletes=0 LIMIT 1"));
        if (!$data) { echo json_encode(['type'=>'0','result'=>'User not found']); exit; }

        // Check active accepted job
        $activeQuery = mysqli_query($con, 
            "SELECT COUNT(*) AS cnt FROM open_jobs 
             WHERE JSON_EXTRACT(bids_details, '$.\"$u_id\".status') = 'accept'
             AND pickup_date <= NOW()
             AND (dropoff_date IS NULL OR dropoff_date >= NOW())"
        );
        
        $active = mysqli_fetch_assoc($activeQuery)['cnt'];
        
        if ($active > 0) {
            echo json_encode(['type'=>'0','result'=>'This user has active accepted jobs.']); 
            exit;
        }


        mysqli_query($con, "UPDATE kyc_details SET type='$role' WHERE user_id=$u_id");

        echo json_encode(['type'=>'1','result'=>'Role updated successfully']); exit;

    } catch (Exception $e) {
        echo json_encode(['type'=>'0','result'=>$e->getMessage()]); exit;
    }
}

if ($method === "kyc_manualUpload") {
    
    
    $user_id  = BlockSQLInjection($_POST['user_id'] ?? '');
    $doc_type = BlockSQLInjection($_POST['doc_type'] ?? '');
    $doc_date = $_POST['doc_date'] ??'';
    $doc_no = BlockSQLInjection($_POST['doc_no'] ?? '');
    
    if (empty($user_id) || empty($doc_type)) {
        echo json_encode(['type' => 0, 'result' => 'Missing required fields']);
        exit;
    }

    if (!isset($_FILES['document1']) || $_FILES['document1']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['type' => 0, 'result' => 'Primary document is required']);
        exit;
    }

    $file1 = $_FILES['document1'];

    $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
    $ext1 = strtolower(pathinfo($file1['name'], PATHINFO_EXTENSION));

    if (!in_array($ext1, $allowed) || $file1['size'] > 10 * 1024 * 1024) {
        echo json_encode(['type' => 0, 'result' => 'Invalid primary document']);
        exit;
    }

    $needsSecond = in_array($doc_type, ['aadhaar', 'rc', 'dl', 'nameboard']);
    $file2Url = null;

    if ($needsSecond) {

        if (!isset($_FILES['document2']) || $_FILES['document2']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['type' => 0, 'result' => 'Secondary document is required']);
            exit;
        }

        $file2 = $_FILES['document2'];
        $ext2 = strtolower(pathinfo($file2['name'], PATHINFO_EXTENSION));

        if (!in_array($ext2, $allowed) || $file2['size'] > 10 * 1024 * 1024) {
            echo json_encode(['type' => 0, 'result' => 'Invalid secondary document']);
            exit;
        }
    }

    $apiBase = API_DOMAIN_2;

    $upload1 = uploadToS3ViaPresigned($apiBase, $file1);

    if (!$upload1['status']) {
        echo json_encode(['type' => 0, 'result' => $upload1['error']]);
        exit;
    }

    $file1Url = $upload1['file_url'];

    if ($needsSecond) {
        $upload2 = uploadToS3ViaPresigned($apiBase, $file2);

        if (!$upload2['status']) {
            echo json_encode(['type' => 0, 'result' => $upload2['error']]);
            exit;
        }

        $file2Url = $upload2['file_url'];
    }

    try {

        if ($doc_type == 'selfie') {
    
            $payload = [
                'user_id' => $user_id,
                's_url'   => $file1Url,
                'type'    => 'Driver',
                'change_status' => 1
            ];
    
            $endpoint = 'manual-kyc/selfie-update';
    
        } elseif ($doc_type == 'aadhaar') {
    
            $payload = [
                'user_id' => $user_id,
                'front_image' => $file1Url,
                'back_image'  => $file2Url,
                'type' => 'AADHAAR'
            ];
    
            $endpoint = 'manual-kyc/aadhar-update';
    
        }elseif ($doc_type == 'dl'){
            
            $formats = ['Y-m-d'];
            $date = null;
            
            foreach ($formats as $format) {
                $date = DateTime::createFromFormat($format, $doc_date);
                
                $doc_date = $date ? $date->format('d/m/Y') : '';
            }
            
            $payload = [
                'user_id' => $user_id,
                'dl_no' => $doc_no,
                'exp'  => null,
                'expiry'  => null,
                'dob'  => $doc_date,
                'front_url'  => $file1Url,
                'back_url'  => $file2Url,
                'type' => 'DRIVING_LICENSE'
            ];
            
            
    
            $endpoint = 'manual-kyc/dl-update';
            
        } else {
            throw new Exception('Unsupported document type');
        }
    
        $ch = curl_init($apiBase . $endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json']
        ]);
    
        $res = curl_exec($ch);
        curl_close($ch);
    
        $apiRes = json_decode($res, true);
    
        if (empty($apiRes['status'])) {
            throw new Exception($apiRes['message'] ?? 'KYC update failed');
        }
    
        echo json_encode([
            'type' => 1,
            'result' => 'Document uploaded successfully',
            'files' => [
                'front' => $file1Url,
                'back' => $file2Url
            ]
        ]);
        exit;
    
    } catch (Exception $e) {
    
        rollbackS3($apiBase, [$file1Url, $file2Url]);
    
        echo json_encode([
            'type' => 0,
            'data' => $payload,
            'result' => $e->getMessage()
        ]);
        exit;
    }

}

resutGJHIP:
echo json_encode($result);
