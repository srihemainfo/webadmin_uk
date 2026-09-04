<?php

include '../../include/shi-config.php';
include '../../include/functions.php';
include '../../include/Crypto.php';
error_reporting(E_ALL); 
ini_set('display_errors', 1);
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
$result = array();
$post_csrf = $headers['X-Csrf-Token'] ?? '';

function range_between_datetime_full($start, $end, $step = '+1 day')
{
    $startDate = DateTime::createFromFormat('Y-m-d h:i A', $start);
    $endDate   = DateTime::createFromFormat('Y-m-d h:i A', $end);

    $dates = [];

    while ($startDate <= $endDate) {
        $dates[] = $startDate->format('Y-m-d h:i A');
        $startDate->modify($step);
    }

    return $dates;
}


if ($method == 'get_users') {
    
    $sear = BlockSQLInjection($_POST['search']);

    $get_user = "SELECT user_register.id, user_register.name, user_register.mobile, kyc_details.id as kyc_id FROM user_register LEFT JOIN kyc_details ON kyc_details.user_id = user_register.id
                 WHERE (user_register.name LIKE '%$sear%' OR user_register.mobile LIKE '%$sear%') AND user_register.status = '0' AND user_register.deletes = '0'";

    $result = mysqli_query($con, $get_user);

    if (!$result) {
        $response = [
            "status" => false,
            "message" => "Database Error: " . mysqli_error($con)
        ];
    } else {

        $users = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }

        if (count($users) > 0) {
            $response = [
                "status" => true,
                "message" => "Users fetched successfully",
                "data" => $users
            ];
        } else {
            $response = [
                "status" => false,
                "message" => "No users found"
            ];
        }
    }

    echo json_encode($response);
    exit;
}

if ($method == 'get_user_by_mobile') {

    $mob = BlockSQLInjection($_POST['mobile']);

    $get_user = "
        SELECT 
            user_register.id,
            user_register.name,
            user_register.mobile,
            kyc_details.id AS kyc_id
        FROM user_register
        LEFT JOIN kyc_details 
            ON kyc_details.user_id = user_register.id
        WHERE user_register.mobile = '$mob'
        AND user_register.status = '0'
        AND user_register.deletes = '0'
        LIMIT 1
    ";

    $result = mysqli_query($con, $get_user);

    if ($result && mysqli_num_rows($result) > 0) {

        $user = mysqli_fetch_assoc($result);

        echo json_encode([
            "status" => 1,
            "data" => $user
        ]);
    } else {

        echo json_encode([
            "status" => 0,
            "message" => "User not found"
        ]);
    }

    exit;
}

if ($method == 'get_users_details') {
    
    $sear = BlockSQLInjection($_POST['search']);

    $get_user = "
        SELECT 
            ur.id AS u_id,
            ur.name,
            ur.mobile,
            JSON_UNQUOTE(JSON_EXTRACT(ur.vehicle_details, '$.rc_number')) AS rc_number,
            JSON_UNQUOTE(JSON_EXTRACT(ur.vehicle_details, '$.rc_expiry_date')) AS rc_expiry_date,
            JSON_UNQUOTE(JSON_EXTRACT(ur.vehicle_details, '$.vehicle_questions.fuel_type')) AS fuel_type,
            kd.*
        FROM user_register ur
        LEFT JOIN kyc_details kd ON kd.user_id = ur.id
        WHERE ( ur.id = '$sear' OR ur.mobile = '$sear' )
          AND ur.status = '0'
          AND ur.deletes = '0'
    ";
    
    $result = mysqli_query($con, $get_user);

    if (!$result) {
        $response = [
            "status" => false,
            "message" => "Database Error: " . mysqli_error($con)
        ];
    } else {

        $users = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }

        if (count($users) > 0) {
            $response = [
                "status" => true,
                "message" => "Users fetched successfully",
                "data" => $users[0]
            ];
        } else {
            $response = [
                "status" => false,
                "message" => "No users found"
            ];
        }
    }

    echo json_encode($response);
    exit;
}

if ($method == 'submit_schedule') {

    $user_id = BlockSQLInjection($_POST['user_id']);
    $schedules_data = $_POST['schedules'];
    
    $isUpdate = false;
    
    if ($user_id != '' && $schedules_data != '') {

        foreach ($schedules_data as $row) {

            $from_place  = BlockSQLInjection($row['from']);
            $to_place    = BlockSQLInjection($row['to']);
            $oneway_total  = BlockSQLInjection($row['oneway_total']);
            $return_total  = BlockSQLInjection($row['return_total']);

            // get between dates
            $dates_between = range_between_datetime_full($row['from_date'], $row['to_date']);
            // var_dump($dates_between);die;

            // Build dates_price array
            $dates_price = [];
            foreach ($dates_between as $d) {
                $dates_price[$d] = [
                    "oneway" => $oneway_total,
                    "return" => $return_total
                ];
            }

            // JSON encode data
            $dates_price_json = json_encode($dates_price, JSON_UNESCAPED_UNICODE);
            $checkout_json    = json_encode($row, JSON_UNESCAPED_UNICODE);

            // Check if record exists
            $check_sql = "
                SELECT id 
                FROM schedule_dates 
                WHERE from_place = '$from_place' 
                  AND to_place = '$to_place' 
                  AND user_id = '$user_id' 
                  AND deletes = 0
                LIMIT 1
            ";
            $check_res = mysqli_query($con, $check_sql);

            if (mysqli_num_rows($check_res) > 0) {
                // UPDATE
                $update_sql = "
                    UPDATE schedule_dates SET 
                        dates_price   = '$dates_price_json',
                        checkout_data = '$checkout_json'
                    WHERE from_place = '$from_place'
                      AND to_place = '$to_place'
                      AND user_id = '$user_id'
                      AND deletes = 0
                ";
                mysqli_query($con, $update_sql);
                $isUpdate = true;
            } else {
                // INSERT
                $insert_sql = "
                    INSERT INTO schedule_dates 
                        (from_place, to_place, user_id, dates_price, checkout_data) 
                    VALUES 
                        ('$from_place', '$to_place', '$user_id', '$dates_price_json', '$checkout_json')
                ";
                mysqli_query($con, $insert_sql);
                $isUpdate = true;
            }
        }
        
        if($isUpdate){
            $response = [
                "status" => true,
                "message" => "Jobs scheduled successfully",
                "data" => []
            ];
        }else{
            $response = [
                "status" => false,
                "message" => "Jobs scheduled failed",
                "data" => []
            ];
        }
        
        echo json_encode($response);
        exit;
    }else{
        $response = [
            "status" => false,
            "message" => "Invalid data",
            "data" => []
        ];
        
        echo json_encode($response);
        exit;
    }
}

if ($method == 'send_whatsapp_document') {

    $user_id = intval($_POST['user_id'] ?? 0);
    $mobile  = preg_replace('/\D/', '', $_POST['mobile'] ?? '');
    $file    = $_FILES['list_pdf'] ?? null;
    $user_name = '';

    if ($user_id <= 0 || $mobile == '' || !$file || $file['error'] !== 0) {
        echo json_encode(["status" => "error", "msg" => "Invalid request"]);
        exit;
    }else{
        $check_user_sql = "
            SELECT id, name, mobile, email 
            FROM user_register 
            WHERE id = '$user_id' 
              AND deletes = '0' 
              AND roll_id = '0'
            LIMIT 1
        ";

        $check_res = mysqli_query($con, $check_user_sql);

        if (!$check_res || mysqli_num_rows($check_res) === 0) {
            echo json_encode(["status" => "error", "msg" => "User not found"]);
            exit;
        }

        $user_row  = mysqli_fetch_assoc($check_res);
        $user_name = $user_row['name'];
    }

    // Correct MIME validation
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);

    if ($mime !== "application/pdf") {
        echo json_encode(["status" => "error", "msg" => "Only PDF allowed"]);
        exit;
    }

    // Move file
    $folder = __DIR__ . "/../../assets/schedule_list/";
    if (!is_dir($folder)) mkdir($folder, 0777, true);

    $safeName = "wa_" . time() . "_" . basename($file['name']);
    $path = $folder . $safeName;
    move_uploaded_file($file['tmp_name'], $path);

    // Base64 encode file correctly
    $base_convert = base64_encode(file_get_contents($path));

    // WhatsApp API payload
    $body = [
        "chatId" => $mobile.'@c.us',
        "contentType" => "MessageMedia",
        "content" => [
            "mimetype" => "application/pdf",
            "data" => $base_convert,
            "filename" => $safeName
        ]
    ];
    
    
    $sess_ins = WHATSAPP_SESSION_ID_1;
    
    // var_dump($body);die;

    // Send request
    $headers = [
        "Content-Type: application/json",
        "x-api-key: " . WHATSAPP_API_KEY_1
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, WHATSAPP_API_URL_1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body, JSON_UNESCAPED_UNICODE));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $ok = ($code == 200 && stripos($response, 'success') !== false);
    $sss = $ok ? "success" : "failed";
    
    $log_sql = "
        INSERT INTO whatsapp_bulk_message
            (`details`, `name`, `from_whatsapp`, `to_whatsapp`, `status`, `whatapp_campain`, `created_at`, `updated_at`)
        VALUES
            ('PDF FILE', 
             '" . mysqli_real_escape_string($con, $user_name) . "',
             '$sess_ins',
             '$mobile',
             '$sss',
             '',
             NOW(),
             NOW()
            )
    ";

    mysqli_query($con, $log_sql);
    
    // var_dump($response);die;

    echo json_encode([
        "status" => $ok ? "success" : "failed",
        "response" => $response
    ]);
    exit;
}

if ($method == 'get_sch_report') {
    $response = [];
    $startDate  = $_POST['startDate'] ?? '';
    $endDate    = $_POST['endDate'] ?? '';
    $dateFilter = $_POST['dateFilter'] ?? '';
    $searchTxt  = $_POST['searchTxt'] ?? '';

    $filterParts = '';

    if (!empty($searchTxt)) {
        $searchTxt = mysqli_real_escape_string($con, trim($searchTxt));
        $filterParts .= " AND (ur.name LIKE '%$searchTxt%' OR ur.mobile LIKE '%$searchTxt%') ";
    }

    if (!empty($dateFilter) && !empty($startDate) && !empty($endDate)) {
        $filterParts .= " AND sd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ";
    }

    $s_query = "
        SELECT 
            sd.id,
            ur.name, 
            ur.mobile, 
            sd.from_place, 
            sd.to_place,
            sd.created_at,
            sd.dates_price
        FROM schedule_dates AS sd 
        JOIN user_register AS ur ON ur.id = sd.user_id 
        WHERE ur.deletes = '0' 
        AND sd.deletes = 0 $filterParts
        ORDER BY sd.id DESC
    ";

    $query_run = mysqli_query($con, $s_query);
    $response['result'] = [];

    if ($query_run) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            
            // Format dates_price into a flat structure {"2026-03-30 17:30:00": 1500} for the frontend
            $dates_price = json_decode($row['dates_price'], true);
            $flat_prices = [];
            
            if (is_array($dates_price)) {
                foreach ($dates_price as $date => $prices) {
                    if (is_array($prices)) {
                        $flat_prices[$date] = $prices['oneway'] ?? ($prices['return'] ?? 0);
                    } else {
                        $flat_prices[$date] = $prices;
                    }
                }
            }
            
            $row['dates_price_parsed'] = json_encode($flat_prices);
            unset($row['dates_price']); // Remove raw complex json
            
            $response['result'][] = $row;
        }
        $response['status'] = 'success';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'SQL Error: ' . mysqli_error($con);
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

if ($method == 'update_sch_report') {
    header('Content-Type: application/json');
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $routes = json_decode($_POST['routes'] ?? '[]', true);

    if (empty($routes)) {
        // If all rows inside the edit modal were deleted and saved, delete the main row.
        mysqli_query($con, "UPDATE schedule_dates SET deletes = 1 WHERE id = '$id'");
        echo json_encode(['status' => true, 'message' => 'Record deleted as all dates were removed.']);
        exit;
    }

    $from_place = mysqli_real_escape_string($con, $routes[0]['from']);
    $to_place = mysqli_real_escape_string($con, $routes[0]['to']);
    
    // Construct the simple, flat JSON structure directly: {"2026-04-03 14:10:00": 1500}
    $dates_price = [];
    foreach ($routes as $route) {
        $dt = date('Y-m-d H:i:s', strtotime($route['datetime']));
        $price = (int)$route['price'];
        $dates_price[$dt] = $price; 
    }
    
    $dates_price_json = mysqli_real_escape_string($con, json_encode($dates_price));

    $u_query = "UPDATE schedule_dates SET from_place = '$from_place', to_place = '$to_place', dates_price = '$dates_price_json' WHERE id = '$id'";
    
    if (mysqli_query($con, $u_query)) {
        echo json_encode(['status' => true, 'message' => 'Schedule updated successfully.']);
    } else {
        echo json_encode(['status' => false, 'message' => 'Failed to update record.']);
    }
    exit;
}

if ($method === 'delete_sch_report') {
    $e_id = isset($_POST['id']) ? trim($_POST['id']) : '';
    if (!empty($e_id)) {
        $stmt = $con->prepare("UPDATE schedule_dates SET deletes = 1 WHERE id = ?");
        $stmt->bind_param("i", $e_id);
        if ($stmt->execute()) {
            $response = ['status' => true, 'message' => 'Record deleted successfully.'];
        } else {
            $response = ['status' => false, 'message' => 'Database error.'];
        }
        $stmt->close();
    } else {
        $response = ['status' => false, 'message' => 'Invalid ID.'];
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}



?>