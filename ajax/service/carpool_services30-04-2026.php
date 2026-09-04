<?php
// Start output buffering to prevent whitespace corruption
ob_start();

include '../../include/shi-config.php';
include '../../include/functions.php';
include '../../include/Crypto.php';

// IMPORTANT: Force the database connection to support 4-byte emojis
mysqli_set_charset($con, 'utf8mb4');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$method = $_POST['method'] ?? '';

// GREEN API DIRECT CURL FUNCTION
function sendGreenApiWhatsApp($mobile_number, $message_text) {
    $instanceId = "7103510606";
    $token = "ff4b612bf49b4572934c5aac05fce3f6d38adf0d49714c40a5";
    $apiUrl = "https://7103.api.greenapi.com/waInstance$instanceId/sendMessage/$token";
    
    $clean_mobile = preg_replace('/[^0-9]/', '', $mobile_number);
    $clean_mobile = ltrim($clean_mobile, '0');
    if (strlen($clean_mobile) == 10) {
        $clean_mobile = "91" . $clean_mobile;
    }

    $payload = [
        "chatId" => $clean_mobile . "@c.us",
        "message" => $message_text
    ];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    return [
        'success' => ($http_code == 200),
        'response' => $response ? $response : $error
    ]; 
}

// Helper to safely output JSON and exit
function sendJsonResponse($data) {
    ob_clean(); // Clear any hidden HTML/whitespace that breaks JS
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// ------------------------------------------------------------------
// 1. FETCH CUSTOMER KYC DATA 
// ------------------------------------------------------------------
if ($method == 'fetch_customer_ocr') {
    $searchTxt = $_POST['searchTxt'] ?? '';
    $dateRange = $_POST['dateRange'] ?? '';
    $filter = $_POST['filter'] ?? 'all';

    $where = "c.deletes = '0' AND (k.user_id IS NOT NULL OR o_dl.user_id IS NOT NULL)"; 

    if (!empty($searchTxt)) {
        $searchTxt = mysqli_real_escape_string($con, $searchTxt);
        $where .= " AND (c.name LIKE '%$searchTxt%' OR c.mobile LIKE '%$searchTxt%' OR c.email LIKE '%$searchTxt%')";
    }

    if (!empty($dateRange)) {
        $dates = explode(' - ', $dateRange);
        if (count($dates) == 2) {
            $start = DateTime::createFromFormat('d/m/Y', trim($dates[0]))->format('Y-m-d 00:00:00');
            $end = DateTime::createFromFormat('d/m/Y', trim($dates[1]))->format('Y-m-d 23:59:59');
            $where .= " AND (k.created_at BETWEEN '$start' AND '$end' OR o_dl.created_at BETWEEN '$start' AND '$end')";
        }
    }

    if ($filter != 'all') {
        switch ($filter) {
            case 'selfie_pending': 
                $where .= " AND (k.selfie_status IS NULL OR k.selfie_status = 'pending' OR k.selfie_status = '')"; break;
            case 'selfie_verified': 
                $where .= " AND k.selfie_status IN ('approved', '1')"; break;
            case 'selfie_rejected': 
                $where .= " AND k.selfie_status IN ('rejected', 'failed', '0')"; break;
            case 'dl_pending': 
                $where .= " AND (c.vehicle_details NOT LIKE '%\"dl_status\":\"approved\"%' AND c.vehicle_details NOT LIKE '%\"dl_status\":\"rejected\"%' AND (o_dl.status IS NULL OR o_dl.status = 'pending') AND (k.dl_status IS NULL OR k.dl_status = 'pending'))"; break;
            case 'dl_verified': 
                $where .= " AND (c.vehicle_details LIKE '%\"dl_status\":\"approved\"%' OR o_dl.status IN ('approved', '1') OR k.dl_status IN ('approved', '1'))"; break;
            case 'dl_rejected': 
                $where .= " AND (c.vehicle_details LIKE '%\"dl_status\":\"rejected\"%' OR o_dl.status IN ('rejected', 'failed', '0') OR k.dl_status IN ('rejected', 'failed', '0'))"; break;
            case 'veh_type_pending': 
                $where .= " AND (c.vehicle_details NOT LIKE '%\"car_type_status\":\"approved\"%' AND c.vehicle_details NOT LIKE '%\"car_type_status\":\"rejected\"%')"; break;
            case 'veh_type_verified': 
                $where .= " AND c.vehicle_details LIKE '%\"car_type_status\":\"approved\"%'"; break;
            case 'veh_type_rejected': 
                $where .= " AND c.vehicle_details LIKE '%\"car_type_status\":\"rejected\"%'"; break;
        }
    }

    $query = "
        SELECT 
            c.id as user_id, c.name, c.mobile, c.email, c.doc_verify, c.vehicle_verify, c.created_at, c.vehicle_details,
            k.selfie_url, k.selfie_status, k.selfie_reason, k.s_lat, k.s_lang,
            k.dl_status as kyc_dl_status, k.created_at as kyc_created_at,
            o_dl.doc_no as dl_no, o_dl.id as dl_req_id, o_dl.status as dl_status, o_dl.req_response as dl_response, o_dl.created_at as dl_created_at
        FROM customer_register c
        LEFT JOIN kyc_carpool k ON c.id = k.user_id
        LEFT JOIN ocr_request o_dl ON c.id = o_dl.user_id AND o_dl.global_type = 'customer' AND o_dl.doc_type = 'DRIVING_LICENSE'
        WHERE $where
        ORDER BY c.id DESC
    ";
    
    $result = mysqli_query($con, $query);
    $data = [];
    $seenUsers = [];
    
    if ($result) {
        while($row = mysqli_fetch_assoc($result)) {
            if (!isset($seenUsers[$row['user_id']])) {
                
                if(!empty($row['vehicle_details']) && $row['vehicle_details'] != 'null') {
                    $vd = json_decode($row['vehicle_details'], true);
                    if(is_array($vd)) {
                        if(isset($vd['dl_status'])) $row['dl_status'] = $vd['dl_status'];
                        if(isset($vd['car_type_status'])) $row['car_type_status'] = $vd['car_type_status'];
                    }
                }
                
                if (empty($row['dl_status']) && !empty($row['kyc_dl_status'])) {
                    $row['dl_status'] = $row['kyc_dl_status'];
                }

                // --- NEW LOGIC: VALIDATE OCR RESPONSE ---
                // Even if the status says approved, verify the JSON response is actually valid DL data.
                if (!empty($row['dl_response'])) {
                    $dl_res_decoded = json_decode($row['dl_response'], true);
                    if (is_array($dl_res_decoded)) {
                        // If response contains an error code (NOT_FOUND, etc) or lacks the required 'id_no' field
                        if (isset($dl_res_decoded['code']) || !isset($dl_res_decoded['id_no'])) {
                            $row['dl_status'] = 'rejected';
                        }
                    }
                }
                // ----------------------------------------

                $data[] = $row;
                $seenUsers[$row['user_id']] = true;
            }
        }
        sendJsonResponse(['result' => $data, 'type' => 1]);
    } else {
        sendJsonResponse(['result' => 'Database query failed', 'type' => 0]);
    }
}

// ------------------------------------------------------------------
// 2. UNIFIED VERIFY KYC (SELFIE, DL, VEHICLE TYPE)
// ------------------------------------------------------------------
else if ($method == 'verify_customer_kyc') {
    $user_id = mysqli_real_escape_string($con, $_POST['id']);
    $action = $_POST['action'] ?? ''; 
    $docs = $_POST['docs'] ?? []; 
    $send_whatsapp = $_POST['send_wa'] ?? 'no';
    
    $fetch_user = mysqli_query($con, "
        SELECT c.vehicle_details, 
               (SELECT selfie_status FROM kyc_carpool WHERE user_id = c.id LIMIT 1) as selfie_status,
               (SELECT selfie_reason FROM kyc_carpool WHERE user_id = c.id LIMIT 1) as selfie_reason,
               (SELECT status FROM ocr_request WHERE user_id = c.id AND doc_type = 'DRIVING_LICENSE' LIMIT 1) as dl_stat,
               (SELECT dl_status FROM kyc_carpool WHERE user_id = c.id LIMIT 1) as kyc_dl_stat
        FROM customer_register c WHERE c.id = '$user_id'
    ");
    $userRow = mysqli_fetch_assoc($fetch_user);

    $selfie_status = (in_array($userRow['selfie_status'], ['approved', '1'])) ? 1 : 0;
    $selfie_reason = $userRow['selfie_reason'];

    $vd = [];
    if(!empty($userRow['vehicle_details']) && $userRow['vehicle_details'] != 'null') {
        $vd = json_decode($userRow['vehicle_details'], true);
        if (!is_array($vd)) { $vd = []; }
    }
    
    $dl_stat_str = $vd['dl_status'] ?? $userRow['kyc_dl_stat'] ?? $userRow['dl_stat'] ?? 'pending';
    $vehicle_type_stat_str = $vd['car_type_status'] ?? 'pending';
    
    $dl_reason = $vd['dl_reason'] ?? '';
    $vehicle_type_reason = $vd['car_type_reason'] ?? '';

    if ($action == 'approve') {
        if (in_array('selfie', $docs)) { $selfie_status = 1; }
        if (in_array('dl', $docs)) { $dl_stat_str = 'approved'; }
        if (in_array('car_type', $docs)) { $vehicle_type_stat_str = 'approved'; }
    } else if ($action == 'reject') {
        if (isset($docs['selfie'])) { $selfie_status = 0; $selfie_reason = mysqli_real_escape_string($con, $docs['selfie']); }
        if (isset($docs['dl'])) { $dl_stat_str = 'rejected'; $dl_reason = mysqli_real_escape_string($con, $docs['dl']); }
        if (isset($docs['car_type'])) { $vehicle_type_stat_str = 'rejected'; $vehicle_type_reason = mysqli_real_escape_string($con, $docs['car_type']); }
    }

    if ($selfie_status === 1) { $selfie_reason = null; }
    if ($dl_stat_str === 'approved') { $dl_reason = null; }
    if ($vehicle_type_stat_str === 'approved') { $vehicle_type_reason = null; }

    $doc_val = ($selfie_status === 1) ? 1 : 0;
    $veh_val = ($dl_stat_str === 'approved' && $vehicle_type_stat_str === 'approved') ? 2 : 1;
    $s_stat_str = ($selfie_status === 1) ? 'approved' : 'rejected';

    mysqli_begin_transaction($con);
    try {
        mysqli_query($con, "UPDATE customer_register SET doc_verify = $doc_val, vehicle_verify = $veh_val WHERE id = '$user_id'");
        
        $kyc_s_reason_sql = empty($selfie_reason) ? "NULL" : "'" . mysqli_real_escape_string($con, $selfie_reason) . "'";
        $kyc_dl_reason_sql = empty($dl_reason) ? "NULL" : "'" . mysqli_real_escape_string($con, $dl_reason) . "'";
        mysqli_query($con, "UPDATE kyc_carpool SET selfie_status = '$s_stat_str', selfie_reason = $kyc_s_reason_sql, dl_status = '$dl_stat_str', dl_reason = $kyc_dl_reason_sql WHERE user_id = '$user_id'");
        
        mysqli_query($con, "UPDATE ocr_request SET status = '$dl_stat_str' WHERE user_id = '$user_id' AND doc_type IN ('DRIVING_LICENSE', 'DL')");

        $vd['dl_status'] = $dl_stat_str;
        $vd['dl_reason'] = $dl_reason ?: null;
        $vd['car_type_status'] = $vehicle_type_stat_str;
        $vd['car_type_reason'] = $vehicle_type_reason ?: null;
        
        if ($veh_val === 2) {
            $vd['admin_verify'] = true;
            $vd['vehicle_review_message'] = "Vehicle Verification Approved";
        } else {
            $vd['admin_verify'] = false;
            $vd['vehicle_review_message'] = "Vehicle Verification Rejected. Please check your details.";
        }

        $new_vd_json = mysqli_real_escape_string($con, json_encode($vd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        mysqli_query($con, "UPDATE customer_register SET vehicle_details = '$new_vd_json' WHERE id = '$user_id'");

        mysqli_commit($con);
        
        $whatsapp_status_msg = "";
        if ($send_whatsapp == 'yes') {
            $fetch_user = mysqli_query($con, "SELECT name, mobile FROM customer_register WHERE id = '$user_id'");
            if ($userRow = mysqli_fetch_assoc($fetch_user)) {
                $name = $userRow['name'] ? strtoupper($userRow['name']) : 'User';
                $mobile = $userRow['mobile'];

                $what_mess = "Hello *" . $name . "*,\n\n";
                if ($veh_val === 2 && $doc_val === 1) {
                    $what_mess .= "Your KYC and Vehicle details have been successfully Verified. ✅\n\nPlease contact us if you have any questions.\n\nRegards,\n*GoRide Run Pvt. Ltd.*";
                } else {
                   $what_mess .= "Action required on your Verification. ⚠️\n\n*Remarks:*\n";
                   if($selfie_status === 0 && !empty($selfie_reason)) $what_mess .= "- Selfie: " . ucfirst($selfie_reason) . "\n";
                   if($dl_stat_str === 'rejected' && !empty($dl_reason)) $what_mess .= "- Driving License: " . ucfirst($dl_reason) . "\n";
                   if($vehicle_type_stat_str === 'rejected' && !empty($vehicle_type_reason)) $what_mess .= "- Vehicle Type: " . ucfirst($vehicle_type_reason) . "\n";
                   $what_mess .= "\nPlease fix the requested details.\n\nRegards,\n*GoRide Run Pvt. Ltd.*";
                }
                
                $api_result = sendGreenApiWhatsApp($mobile, $what_mess);
                $esc_msg = mysqli_real_escape_string($con, $what_mess);
                $db_status = $api_result['success'] ? 'success' : 'failed';
                $log_data = mysqli_real_escape_string($con, $api_result['response']);
                mysqli_query($con, "INSERT INTO whatsapp_bulk_message (`details`, `name`, `from_whatsapp`, `to_whatsapp`, `status`, `log`, `created_at`, `updated_at`) VALUES ('$esc_msg', '$name', '7103510606', '$mobile', '$db_status', '$log_data', NOW(), NOW())");
                $whatsapp_status_msg = $api_result['success'] ? " (WhatsApp Sent)" : " (WhatsApp Failed)";
            }
        }

        sendJsonResponse(['result' => "KYC updated successfully.$whatsapp_status_msg", 'type' => 1]);
    } catch (Exception $e) {
        mysqli_rollback($con);
        sendJsonResponse(['result' => 'KYC Update failed.', 'type' => 0]);
    }
}
?>