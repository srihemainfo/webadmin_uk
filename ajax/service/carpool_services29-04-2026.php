<?php
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

// ------------------------------------------------------------------
// 1. FETCH CUSTOMER KYC DATA 
// ------------------------------------------------------------------
if ($method == 'fetch_customer_ocr') {
    $searchTxt = $_POST['searchTxt'] ?? '';
    $dateRange = $_POST['dateRange'] ?? '';
    $filter = $_POST['filter'] ?? 'all';

    $where = "c.deletes = '0' AND (k.user_id IS NOT NULL OR o_rc.user_id IS NOT NULL OR o_dl.user_id IS NOT NULL)"; 

    if (!empty($searchTxt)) {
        $searchTxt = mysqli_real_escape_string($con, $searchTxt);
        $where .= " AND (c.name LIKE '%$searchTxt%' OR c.mobile LIKE '%$searchTxt%' OR c.email LIKE '%$searchTxt%')";
    }

    if (!empty($dateRange)) {
        $dates = explode(' - ', $dateRange);
        if (count($dates) == 2) {
            $start = DateTime::createFromFormat('d/m/Y', trim($dates[0]))->format('Y-m-d 00:00:00');
            $end = DateTime::createFromFormat('d/m/Y', trim($dates[1]))->format('Y-m-d 23:59:59');
            $where .= " AND (k.created_at BETWEEN '$start' AND '$end' 
                         OR o_rc.created_at BETWEEN '$start' AND '$end' 
                         OR o_dl.created_at BETWEEN '$start' AND '$end')";
        }
    }

    if ($filter != 'all') {
        switch ($filter) {
            case 'kyc_pending': 
                $where .= " AND (c.doc_verify = 0 OR c.doc_verify IS NULL)"; 
                break;
            case 'kyc_verified': 
                $where .= " AND c.doc_verify = 1"; 
                break;
            case 'vehicle_pending': 
                $where .= " AND (c.vehicle_verify = 0 OR c.vehicle_verify = 1 OR c.vehicle_verify IS NULL)"; 
                break;
            case 'vehicle_verified': 
                $where .= " AND c.vehicle_verify = 2"; 
                break;
            case 'both_verified': 
                $where .= " AND c.doc_verify = 1 AND c.vehicle_verify = 2"; 
                break;
            case 'both_pending': 
                $where .= " AND (c.doc_verify = 0 OR c.doc_verify IS NULL) AND (c.vehicle_verify = 0 OR c.vehicle_verify = 1 OR c.vehicle_verify IS NULL)"; 
                break; 
        }
    }

    // Pull kyc_dl_status to act as a fallback AND fetch o_dl.req_response
    // NEW: Added k.created_at as kyc_created_at, o_rc.created_at as rc_created_at, o_dl.created_at as dl_created_at
    $query = "
        SELECT 
            c.id as user_id, c.name, c.mobile, c.email, c.doc_verify, c.vehicle_verify, c.created_at, c.vehicle_details,
            k.selfie_url, k.selfie_status, k.selfie_reason, k.proof_type, k.proof_status, k.proof_reason, k.front_image as aadhar_front, k.back_image as aadhar_back,
            k.dl_status as kyc_dl_status, k.created_at as kyc_created_at,
            o_rc.front as rc_front, o_rc.back as rc_back, o_rc.doc_no as rc_no, o_rc.id as rc_req_id, o_rc.status as rc_status, o_rc.created_at as rc_created_at,
            o_dl.front as dl_front, o_dl.back as dl_back, o_dl.doc_no as dl_no, o_dl.id as dl_req_id, o_dl.status as dl_status, o_dl.req_response as dl_response, o_dl.created_at as dl_created_at
        FROM customer_register c
        LEFT JOIN kyc_carpool k ON c.id = k.user_id
        LEFT JOIN ocr_request o_rc ON c.id = o_rc.user_id AND o_rc.global_type = 'customer' AND o_rc.doc_type = 'RC'
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
                        if(isset($vd['rc_status'])) $row['rc_status'] = $vd['rc_status'];
                    }
                }
                
                if (empty($row['dl_status']) && !empty($row['kyc_dl_status'])) {
                    $row['dl_status'] = $row['kyc_dl_status'];
                }

                $data[] = $row;
                $seenUsers[$row['user_id']] = true;
            }
        }
        echo json_encode(['result' => $data, 'type' => 1]);
    } else {
        echo json_encode(['result' => 'Database query failed', 'type' => 0]);
    }
    exit;
}

// ------------------------------------------------------------------
// 2. VERIFY USER DOCUMENTS (AADHAR & SELFIE ONLY)
// ------------------------------------------------------------------
else if ($method == 'verify_customer_doc') {
    $user_id = mysqli_real_escape_string($con, $_POST['id']);
    $action = $_POST['action'] ?? ''; // 'approve' or 'reject'
    $docs = $_POST['docs'] ?? []; 
    $send_whatsapp = $_POST['send_wa'] ?? 'no';
    
    $query = mysqli_query($con, "SELECT selfie_status, proof_status, selfie_reason, proof_reason FROM kyc_carpool WHERE user_id = '$user_id'");
    $curr = mysqli_fetch_assoc($query);

    $selfie_status = (in_array($curr['selfie_status'], ['approved', '1'])) ? 1 : 0;
    $aadhar_status = (in_array($curr['proof_status'], ['approved', '1'])) ? 1 : 0;
    $selfie_reason = $curr['selfie_reason'];
    $aadhar_reason = $curr['proof_reason'];

    if ($action == 'approve') {
        if (in_array('selfie', $docs)) { $selfie_status = 1; }
        if (in_array('aadhar', $docs)) { $aadhar_status = 1; }
    } else if ($action == 'reject') {
        if (isset($docs['selfie'])) { $selfie_status = 0; $selfie_reason = mysqli_real_escape_string($con, $docs['selfie']); }
        if (isset($docs['aadhar'])) { $aadhar_status = 0; $aadhar_reason = mysqli_real_escape_string($con, $docs['aadhar']); }
    }

    if ($selfie_status === 1) { $selfie_reason = null; }
    if ($aadhar_status === 1) { $aadhar_reason = null; }

    $doc_val = ($selfie_status === 1 && $aadhar_status === 1) ? 1 : 0;
    $s_stat_str = ($selfie_status === 1) ? 'approved' : 'rejected';
    $a_stat_str = ($aadhar_status === 1) ? 'approved' : 'rejected';
    
    $kyc_s_reason_sql = empty($selfie_reason) ? "NULL" : "'" . mysqli_real_escape_string($con, $selfie_reason) . "'";
    $kyc_a_reason_sql = empty($aadhar_reason) ? "NULL" : "'" . mysqli_real_escape_string($con, $aadhar_reason) . "'";

    mysqli_begin_transaction($con);
    try {
        mysqli_query($con, "UPDATE customer_register SET doc_verify = $doc_val WHERE id = '$user_id'");
        
        mysqli_query($con, "UPDATE kyc_carpool SET 
            selfie_status = '$s_stat_str', 
            selfie_reason = $kyc_s_reason_sql, 
            proof_status = '$a_stat_str', 
            proof_reason = $kyc_a_reason_sql 
            WHERE user_id = '$user_id'");
            
        mysqli_commit($con);
        
        $whatsapp_status_msg = "";
        if ($send_whatsapp == 'yes') {
            $fetch_user = mysqli_query($con, "SELECT name, mobile FROM customer_register WHERE id = '$user_id'");
            if ($userRow = mysqli_fetch_assoc($fetch_user)) {
                $name = $userRow['name'] ? strtoupper($userRow['name']) : 'User';
                $mobile = $userRow['mobile'];
                
                if ($doc_val === 1) {
                    $what_mess = "Hello *" . $name . "*,\n\nYour KYC verification is completed Successfully. ✅\n\nNeed help? Feel free to reach out.\n\nRegards, \n*GoRide Run Pvt. Ltd.*";
                } else {
                    $what_mess = "Hello *" . $name . "*,\n\nAction required on your KYC documents. ⚠️\n\n*Remarks:*\n";
                    if($selfie_status === 0 && !empty($selfie_reason)) $what_mess .= "- Selfie: " . ucfirst($selfie_reason) . "\n";
                    if($aadhar_status === 0 && !empty($aadhar_reason)) $what_mess .= "- Aadhaar: " . ucfirst($aadhar_reason) . "\n";
                    $what_mess .= "\nPlease re-upload the requested documents.\n\nRegards, \n*GoRide Run Pvt. Ltd.*";
                }
                
                $api_result = sendGreenApiWhatsApp($mobile, $what_mess);
                $esc_msg = mysqli_real_escape_string($con, $what_mess);
                $db_status = $api_result['success'] ? 'success' : 'failed';
                $log_data = mysqli_real_escape_string($con, $api_result['response']);
                mysqli_query($con, "INSERT INTO whatsapp_bulk_message (`details`, `name`, `from_whatsapp`, `to_whatsapp`, `status`, `log`, `created_at`, `updated_at`) VALUES ('$esc_msg', '$name', '7103510606', '$mobile', '$db_status', '$log_data', NOW(), NOW())");
                $whatsapp_status_msg = $api_result['success'] ? " (WhatsApp Sent)" : " (WhatsApp Failed)";
            }
        }
        echo json_encode(['result' => "User documents updated successfully.$whatsapp_status_msg", 'type' => 1]);
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo json_encode(['result' => 'User KYC Update failed.', 'type' => 0]);
    }
    exit;
}

// ------------------------------------------------------------------
// 3. VERIFY VEHICLE DOCUMENTS (RC, DL, VEHICLE IMAGES)
// ------------------------------------------------------------------
else if ($method == 'verify_customer_vehicle') {
    $user_id = mysqli_real_escape_string($con, $_POST['id']);
    $action = $_POST['action'] ?? '';
    $docs = $_POST['docs'] ?? [];
    $send_whatsapp = $_POST['send_wa'] ?? 'no';
    
    $fetch_user = mysqli_query($con, "
        SELECT c.vehicle_details, 
               (SELECT status FROM ocr_request WHERE user_id = c.id AND doc_type = 'DRIVING_LICENSE' LIMIT 1) as dl_stat,
               (SELECT status FROM ocr_request WHERE user_id = c.id AND doc_type = 'RC' LIMIT 1) as rc_stat,
               (SELECT dl_status FROM kyc_carpool WHERE user_id = c.id LIMIT 1) as kyc_dl_stat
        FROM customer_register c WHERE c.id = '$user_id'
    ");
    $userRow = mysqli_fetch_assoc($fetch_user);

    $vd = [];
    if(!empty($userRow['vehicle_details']) && $userRow['vehicle_details'] != 'null') {
        $vd = json_decode($userRow['vehicle_details'], true);
        if (!is_array($vd)) { $vd = []; }
    }
    
    $dl_stat_str = $vd['dl_status'] ?? $userRow['kyc_dl_stat'] ?? $userRow['dl_stat'] ?? 'pending';
    $rc_stat_str = $vd['rc_status'] ?? $userRow['rc_stat'] ?? 'pending';
    
    $dl_reason = $vd['dl_reason'] ?? '';
    $rc_reason = $vd['rc_reason'] ?? '';
    $veh_reason = $vd['veh_reason'] ?? '';
    $veh_status_flag = (isset($vd['admin_verify']) && $vd['admin_verify'] == false && stripos($vd['vehicle_review_message'] ?? '', 'reject') !== false) ? 0 : 1;

    // Apply Actions
    if ($action == 'approve') {
        if (in_array('dl', $docs)) { $dl_stat_str = 'approved'; }
        if (in_array('rc', $docs)) { $rc_stat_str = 'approved'; }
        if (in_array('veh', $docs)) { $veh_status_flag = 1; }
    } else if ($action == 'reject') {
        if (isset($docs['dl'])) { $dl_stat_str = 'rejected'; $dl_reason = mysqli_real_escape_string($con, $docs['dl']); }
        if (isset($docs['rc'])) { $rc_stat_str = 'rejected'; $rc_reason = mysqli_real_escape_string($con, $docs['rc']); }
        if (isset($docs['veh'])) { $veh_status_flag = 0; $veh_reason = mysqli_real_escape_string($con, $docs['veh']); }
    }

    if ($dl_stat_str === 'approved') { $dl_reason = null; }
    if ($rc_stat_str === 'approved') { $rc_reason = null; }
    if ($veh_status_flag === 1) { $veh_reason = null; }

    $veh_val = ($dl_stat_str === 'approved' && $rc_stat_str === 'approved' && $veh_status_flag === 1) ? 2 : 1;
    
    mysqli_begin_transaction($con);
    try {
        mysqli_query($con, "UPDATE customer_register SET vehicle_verify = $veh_val WHERE id = '$user_id'");
        
        mysqli_query($con, "UPDATE ocr_request SET status = '$dl_stat_str' WHERE user_id = '$user_id' AND doc_type IN ('DRIVING_LICENSE', 'DL')");
        mysqli_query($con, "UPDATE ocr_request SET status = '$rc_stat_str' WHERE user_id = '$user_id' AND doc_type = 'RC'");
        
        $kyc_dl_reason_sql = empty($dl_reason) ? "NULL" : "'" . mysqli_real_escape_string($con, $dl_reason) . "'";
        mysqli_query($con, "UPDATE kyc_carpool SET dl_status = '$dl_stat_str', dl_reason = $kyc_dl_reason_sql WHERE user_id = '$user_id'");

        $vd['dl_status'] = $dl_stat_str;
        $vd['rc_status'] = $rc_stat_str;
        $vd['dl_reason'] = $dl_reason ?: null;
        $vd['rc_reason'] = $rc_reason ?: null;
        $vd['veh_reason'] = $veh_reason ?: null;
        $vd['veh_status'] = ($veh_status_flag === 1) ? 'approved' : 'rejected';
        
        if ($veh_val === 2) {
            $vd['admin_verify'] = true;
            $vd['vehicle_review_message'] = "Vehicle Verification Approved";
        } else {
            $vd['admin_verify'] = false;
            $vd['vehicle_review_message'] = "Vehicle Verification Rejected. Please check your documents.";
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

                if ($veh_val === 2) {
                    $what_mess = "Hello *" . $name . "*,\n\nYour vehicle has been successfully Verified. 🚗✅\n\nPlease contact us if you have any questions.\n\nRegards,\n*GoRide Run Pvt. Ltd.*";
                } else {
                   $what_mess = "Hello *" . $name . "*,\n\nAction required on your Vehicle Verification. ⚠️\n\n*Remarks:*\n";
                   if($dl_stat_str === 'rejected' && !empty($dl_reason)) $what_mess .= "- Driving License: " . ucfirst($dl_reason) . "\n";
                   if($rc_stat_str === 'rejected' && !empty($rc_reason)) $what_mess .= "- RC Document: " . ucfirst($rc_reason) . "\n";
                   if($veh_status_flag === 0 && !empty($veh_reason)) $what_mess .= "- Vehicle Photos: " . ucfirst($veh_reason) . "\n";
                   $what_mess .= "\nPlease re-upload the requested documents.\n\nRegards,\n*GoRide Run Pvt. Ltd.*";
                }
                
                $api_result = sendGreenApiWhatsApp($mobile, $what_mess);
                $esc_msg = mysqli_real_escape_string($con, $what_mess);
                $db_status = $api_result['success'] ? 'success' : 'failed';
                $log_data = mysqli_real_escape_string($con, $api_result['response']);
                mysqli_query($con, "INSERT INTO whatsapp_bulk_message (`details`, `name`, `from_whatsapp`, `to_whatsapp`, `status`, `log`, `created_at`, `updated_at`) VALUES ('$esc_msg', '$name', '7103510606', '$mobile', '$db_status', '$log_data', NOW(), NOW())");
                $whatsapp_status_msg = $api_result['success'] ? " (WhatsApp Sent)" : " (WhatsApp Failed)";
            }
        }

        echo json_encode(['result' => "Vehicle documents updated successfully.$whatsapp_status_msg", 'type' => 1]);
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo json_encode(['result' => 'Vehicle Update failed.', 'type' => 0]);
    }
    exit;
}
?>