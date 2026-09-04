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

// FACEBOOK GRAPH API DIRECT CURL FUNCTION (Replaces Green API)
function sendMetaWhatsAppTemplate($con, $mobile_number, $template_name, $parameters) {
    // Hardcoded credentials from .env
    $fb_version = "v24.0";
    $phone_number_id = "852631444607712";
    $token = "EAAd9FiMviuoBQs0J2gbLe3kT9ZBs1Nwfq2LsMjZBysnrf6CQKZAEo3qlx527uGr7SpxgUKSqUXrT6lduVZC1N5oaijxtCfQA61Ayv7vrSqxrRRYBBgjXDIvZCHhPhwXMkEf1vrH96QAYt74Aea6aZA1POCKy18jFajheTgQUS2yRaPZAWeUVsSd6pgnvVNuEjdAEwZDZD";
    
    $url = "https://graph.facebook.com/" . $fb_version . "/" . $phone_number_id . "/messages";
    
    $clean_mobile = preg_replace('/[^0-9]/', '', $mobile_number);
    $clean_mobile = ltrim($clean_mobile, '0');
    if (strlen($clean_mobile) == 10) {
        $clean_mobile = "91" . $clean_mobile;
    }

    $templateQuery = mysqli_query($con, "SELECT * FROM wamail_templates WHERE name = '$template_name' LIMIT 1");
    $template = $templateQuery ? mysqli_fetch_assoc($templateQuery) : null;

    $bodyParameters = [];
    foreach ($parameters as $param) {
        if ($param !== null && $param !== '') {
            $bodyParameters[] = [
                "type" => "text",
                "text" => (string) $param
            ];
        }
    }

    $components = [];

    if ($template && !empty($template['header_image'])) {
        $components[] = [
            "type" => "header",
            "parameters" => [
                [
                    "type" => "image",
                    "image" => [
                        "link" => $template['header_image'] 
                    ]
                ]
            ]
        ];
    }

    if (!empty($bodyParameters)) {
        $components[] = [
            "type" => "body",
            "parameters" => $bodyParameters
        ];
    }

    if ($template && !empty($template['variables_json'])) {
        $buttonsConfig = json_decode($template['variables_json'], true);
        if (!empty($buttonsConfig['buttons'])) {
            foreach ($buttonsConfig['buttons'] as $index => $btn) {
                if ($btn['type'] === 'COPY_CODE') {
                    $otpCode = $parameters[0] ?? '123456';
                    $components[] = [
                        "type" => "button",
                        "sub_type" => "url",
                        "index" => (string)$index,
                        "parameters" => [
                            [
                                "type" => "text",
                                "text" => (string)$otpCode
                            ]
                        ]
                    ];
                }
                if ($btn['type'] === 'URL' && strpos($btn['url'] ?? '', '{{1}}') !== false) {
                    $dynamicUrlVal = $parameters[0] ?? '';
                    $components[] = [
                        "type" => "button",
                        "sub_type" => "url",
                        "index" => (string)$index,
                        "parameters" => [
                            [
                                "type" => "text",
                                "text" => (string)$dynamicUrlVal
                            ]
                        ]
                    ];
                }
            }
        }
    }

    $templatePayload = [
        "name" => $template_name,
        "language" => [
            "code" => "en_US"
        ]
    ];

    if (!empty($components)) {
        $templatePayload["components"] = $components;
    }

    $payload = [
        "messaging_product" => "whatsapp",
        "to" => $clean_mobile,
        "type" => "template",
        "template" => $templatePayload
    ];

    $reqTime = date('Y-m-d H:i:s');

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $resTime = date('Y-m-d H:i:s');
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    $isSuccess = ($http_code == 200);
    $body = json_decode($response, true);
    $messageId = $body['messages'][0]['id'] ?? '';

    // Insert into smslog exactly as sampleMess does
    $esc_subject = mysqli_real_escape_string($con, $template_name);
    $esc_details = mysqli_real_escape_string($con, json_encode($parameters));
    $esc_token_res = mysqli_real_escape_string($con, $response);
    $esc_payload = mysqli_real_escape_string($con, json_encode($payload));
    $status_str = $isSuccess ? 'sent' : 'failed';
    $sms_status_str = $isSuccess ? 'Sent' : 'Failed';
    $sms_send_status = $isSuccess ? '1' : '0';
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';

    mysqli_query($con, "INSERT INTO smslog (`gateway`, `subject`, `details`, `mobile`, `ip`, `datetime`, `token_response`, `status`, `reference_id`, `site`, `REQ_Time`, `RES_Time`, `smsdetails`, `smsstatus`, `smssendstatus`, `response`, `isResend`) VALUES ('fbWhatsapp', '$esc_subject', '$esc_details', '$clean_mobile', '$ip', NOW(), '$esc_token_res', '$status_str', '$messageId', 'CUSTOMER', '$reqTime', '$resTime', '$esc_payload', '$sms_status_str', '$sms_send_status', '$esc_token_res', 'NO')");

    return [
        'success' => $isSuccess,
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

// Helper to get and decode vehicle_details
function getVehicleDetails($con, $user_id) {
    $q = mysqli_query($con, "SELECT vehicle_details FROM customer_register WHERE id = '$user_id'");
    $row = mysqli_fetch_assoc($q);
    $vd = [];
    if ($row && !empty($row['vehicle_details']) && $row['vehicle_details'] != 'null') {
        $vd = json_decode($row['vehicle_details'], true);
        if (!is_array($vd)) $vd = [];
    }
    return $vd;
}

function buildKycWhereClause($con, $searchTxt, $dateRange) {
    $where = "c.deletes = '0'"; 
    if (!empty($searchTxt)) {
        $searchTxt = mysqli_real_escape_string($con, $searchTxt);
        $where .= " AND (c.name LIKE '%$searchTxt%' OR c.mobile LIKE '%$searchTxt%' OR c.email LIKE '%$searchTxt%')";
    }
    if (!empty($dateRange)) {
        $dates = explode(' - ', $dateRange);
        if (count($dates) == 2) {
            $start = DateTime::createFromFormat('d/m/Y', trim($dates[0]))->format('Y-m-d 00:00:00');
            $end = DateTime::createFromFormat('d/m/Y', trim($dates[1]))->format('Y-m-d 23:59:59');
            $where .= " AND (k.created_at BETWEEN '$start' AND '$end' OR c.created_at BETWEEN '$start' AND '$end')";
        }
    }
    return $where;
}

// ------------------------------------------------------------------
// 1. FETCH KYC DATA (SEPARATED FOR MAXIMUM SPEED)
// ------------------------------------------------------------------

if ($method == 'fetch_selfie_data') {
    $searchTxt = $_POST['searchTxt'] ?? '';
    $dateRange = $_POST['dateRange'] ?? '';
    $where = buildKycWhereClause($con, $searchTxt, $dateRange);
    $where .= " AND (k.user_id IS NOT NULL OR c.doc_verify > 0)";

    $query = "SELECT c.id as user_id, c.name, c.mobile, c.created_at, k.selfie_status, k.selfie_reason, k.s_lat, k.s_lang, k.created_at as kyc_created_at 
              FROM customer_register c 
              LEFT JOIN kyc_carpool k ON c.id = k.user_id 
              WHERE $where ORDER BY c.id DESC LIMIT 1000";
              
    $result = mysqli_query($con, $query);
    $data = [];
    if ($result) {
        while($row = mysqli_fetch_assoc($result)) { $data[] = $row; }
        sendJsonResponse(['result' => $data, 'type' => 1]);
    } else { sendJsonResponse(['result' => 'Query failed', 'type' => 0]); }
}

else if ($method == 'fetch_dl_data') {
    $searchTxt = $_POST['searchTxt'] ?? '';
    $dateRange = $_POST['dateRange'] ?? '';
    $where = buildKycWhereClause($con, $searchTxt, $dateRange);
    $where .= " AND (o_dl.user_id IS NOT NULL OR (k.dl_status IS NOT NULL AND k.dl_status != '') OR c.vehicle_details LIKE '%dl_status%')";

    $query = "SELECT c.id as user_id, c.name, c.mobile, c.created_at, c.vehicle_details, k.s_lat, k.s_lang, k.dl_status as kyc_dl_status, o_dl.doc_no as dl_no, o_dl.status as dl_status, o_dl.req_response as dl_response, o_dl.created_at as dl_created_at 
              FROM customer_register c 
              LEFT JOIN kyc_carpool k ON c.id = k.user_id 
              LEFT JOIN (SELECT o1.* FROM ocr_request o1 INNER JOIN (SELECT user_id, MAX(id) as latest_id FROM ocr_request WHERE global_type = 'customer' AND doc_type IN ('DRIVING_LICENSE', 'DL') GROUP BY user_id) o2 ON o1.id = o2.latest_id) o_dl ON c.id = o_dl.user_id 
              WHERE $where ORDER BY c.id DESC LIMIT 1000";
              
    $result = mysqli_query($con, $query);
    $data = [];
    $seen = [];
    if ($result) {
        while($row = mysqli_fetch_assoc($result)) {
            if (!isset($seen[$row['user_id']])) {
                if(!empty($row['vehicle_details']) && $row['vehicle_details'] != 'null') {
                    $vd = json_decode($row['vehicle_details'], true);
                    if(is_array($vd) && isset($vd['dl_status'])) $row['dl_status'] = $vd['dl_status'];
                }
                if (empty($row['dl_status']) && !empty($row['kyc_dl_status'])) $row['dl_status'] = $row['kyc_dl_status'];
                if (!empty($row['dl_response'])) {
                    $dl_res = json_decode($row['dl_response'], true);
                    if (is_array($dl_res) && (isset($dl_res['code']) || !isset($dl_res['id_no']))) $row['dl_status'] = 'rejected';
                }
                $data[] = $row;
                $seen[$row['user_id']] = true;
            }
        }
        sendJsonResponse(['result' => $data, 'type' => 1]);
    } else { sendJsonResponse(['result' => 'Query failed', 'type' => 0]); }
}

else if ($method == 'fetch_vehicle_data') {
    $searchTxt = $_POST['searchTxt'] ?? '';
    $dateRange = $_POST['dateRange'] ?? '';
    $where = buildKycWhereClause($con, $searchTxt, $dateRange);
    
    // Only fetch users who have actually submitted vehicle details
    $where .= " AND (c.vehicle_details IS NOT NULL AND c.vehicle_details != 'null' AND c.vehicle_details != '')";

    // Replaced SQL check with deeper SELECT to evaluate OCR status dynamically in the PHP loop below
    $query = "SELECT c.id as user_id, c.name, c.mobile, c.created_at, c.vehicle_details, k.s_lat, k.s_lang, k.dl_status as kyc_dl_status, o_dl.status as ocr_status, o_dl.req_response as dl_response 
              FROM customer_register c 
              LEFT JOIN kyc_carpool k ON c.id = k.user_id 
              LEFT JOIN (
                  SELECT o1.user_id, o1.req_response, o1.status 
                  FROM ocr_request o1 
                  INNER JOIN (
                      SELECT user_id, MAX(id) as latest_id 
                      FROM ocr_request 
                      WHERE global_type = 'customer' AND doc_type IN ('DRIVING_LICENSE', 'DL') 
                      GROUP BY user_id
                  ) o2 ON o1.id = o2.latest_id
              ) o_dl ON c.id = o_dl.user_id 
              WHERE $where ORDER BY c.id DESC LIMIT 1000";
              
    $result = mysqli_query($con, $query);
    $data = [];
    $seen = [];
    
    if ($result) {
        while($row = mysqli_fetch_assoc($result)) {
            if (!isset($seen[$row['user_id']])) {
                
                $current_dl_status = '';

                // 1. Check DL Status from vehicle_details JSON
                if(!empty($row['vehicle_details']) && $row['vehicle_details'] != 'null') {
                    $vd = json_decode($row['vehicle_details'], true);
                    if(is_array($vd) && isset($vd['dl_status'])) {
                        $current_dl_status = strtolower($vd['dl_status']);
                    }
                }

                // 2. Check DL Status from kyc_carpool table
                if (empty($current_dl_status) && !empty($row['kyc_dl_status'])) {
                    $current_dl_status = strtolower($row['kyc_dl_status']);
                }

                // 3. Check explicit OCR Request status
                if (!empty($row['ocr_status'])) {
                    $current_dl_status = strtolower($row['ocr_status']);
                }

                // 4. Catch dynamic API errors (This is what caused Sumanth and Faiyaz to show up previously)
                if (!empty($row['dl_response'])) {
                    $dl_res = json_decode($row['dl_response'], true);
                    if (is_array($dl_res) && (isset($dl_res['code']) || !isset($dl_res['id_no']))) {
                        $current_dl_status = 'rejected';
                    }
                }

                // --- CRITICAL BLOCKER ---
                // If after all checks, their DL is NOT approved, skip them entirely.
                if ($current_dl_status !== 'approved') {
                    $seen[$row['user_id']] = true;
                    continue; // Skip adding to data array
                }
                // ------------------------

                if(!empty($row['vehicle_details']) && $row['vehicle_details'] != 'null') {
                    $vd = json_decode($row['vehicle_details'], true);
                    if(is_array($vd)) {
                        if(isset($vd['car_type_status'])) {
                            $row['car_type_status'] = $vd['car_type_status'];
                        }
                        if(isset($vd['choosed_vehicle'])) {
                            $row['choosed_vehicle'] = $vd['choosed_vehicle'];
                        }
                    }
                }
                
                $data[] = $row;
                $seen[$row['user_id']] = true;
            }
        }
        sendJsonResponse(['result' => $data, 'type' => 1]);
    } else { 
        sendJsonResponse(['result' => 'Query failed', 'type' => 0]); 
    }
}

// ------------------------------------------------------------------
// 2. FETCH DOCUMENT IMAGE (ON-DEMAND API)
// ------------------------------------------------------------------
else if ($method == 'fetch_document_image') {
    $user_id = mysqli_real_escape_string($con, $_POST['id']);
    $tab = $_POST['tab'] ?? 'selfie';
    $url = '';
    
    if ($tab == 'selfie') {
        $q = mysqli_query($con, "SELECT selfie_url FROM kyc_carpool WHERE user_id = '$user_id'");
        if ($row = mysqli_fetch_assoc($q)) {
            $url = $row['selfie_url'];
        }
    }
    
    if (empty($url) || $url == 'null') {
        sendJsonResponse(['status' => false, 'url' => '']);
    } else {
        sendJsonResponse(['status' => true, 'url' => $url]);
    }
}

// ------------------------------------------------------------------
// 3. VERIFY SELFIE
// ------------------------------------------------------------------
else if ($method == 'verify_selfie') {
    $user_id = mysqli_real_escape_string($con, $_POST['id']);
    $action = $_POST['action']; 
    $reason = isset($_POST['reason']) ? mysqli_real_escape_string($con, $_POST['reason']) : '';

    $doc_val = ($action == 'approve') ? 1 : 0;
    $s_stat_str = ($action == 'approve') ? 'approved' : 'rejected';
    $s_reason_sql = ($action == 'approve') ? "NULL" : "'$reason'";

    mysqli_begin_transaction($con);
    try {
        mysqli_query($con, "UPDATE customer_register SET doc_verify = $doc_val WHERE id = '$user_id'");
        mysqli_query($con, "UPDATE kyc_carpool SET selfie_status = '$s_stat_str', selfie_reason = $s_reason_sql WHERE user_id = '$user_id'");
        mysqli_commit($con);
        
        $whatsapp_status_msg = "";
        $fetch_user = mysqli_query($con, "SELECT name, mobile FROM customer_register WHERE id = '$user_id'");
        if ($userRow = mysqli_fetch_assoc($fetch_user)) {
            $name = $userRow['name'] ? strtoupper($userRow['name']) : 'User';
            $mobile = $userRow['mobile'];
            
            $templateName = ($action == 'approve') ? 'cp_selfie_approved' : 'cp_selfie_reject';
            $parameters = ($action == 'approve') ? [$name] : [$name, ucfirst($reason)];
            
            $api_result = sendMetaWhatsAppTemplate($con, $mobile, $templateName, $parameters);
            $whatsapp_status_msg = $api_result['success'] ? " (WhatsApp Sent)" : " (WhatsApp Failed)";
        }
        sendJsonResponse(['result' => "Selfie KYC updated successfully.$whatsapp_status_msg", 'type' => 1]);
    } catch (Exception $e) {
        mysqli_rollback($con);
        sendJsonResponse(['result' => 'Update failed.', 'type' => 0]);
    }
}

// ------------------------------------------------------------------
// 4. VERIFY DL
// ------------------------------------------------------------------
else if ($method == 'verify_dl') {
    $user_id = mysqli_real_escape_string($con, $_POST['id']);
    $action = $_POST['action']; 
    $reason = isset($_POST['reason']) ? mysqli_real_escape_string($con, $_POST['reason']) : '';

    $dl_stat_str = ($action == 'approve') ? 'approved' : 'rejected';
    
    $vd = getVehicleDetails($con, $user_id);
    $vd['dl_status'] = $dl_stat_str;
    $vd['dl_reason'] = ($action == 'approve') ? null : $reason;
    $new_vd_json = mysqli_real_escape_string($con, json_encode($vd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    $reason_sql = ($action == 'approve') ? "NULL" : "'$reason'";

    mysqli_begin_transaction($con);
    try {
        mysqli_query($con, "UPDATE customer_register SET vehicle_details = '$new_vd_json' WHERE id = '$user_id'");
        mysqli_query($con, "UPDATE kyc_carpool SET dl_status = '$dl_stat_str', dl_reason = $reason_sql WHERE user_id = '$user_id'");
        mysqli_query($con, "UPDATE ocr_request SET status = '$dl_stat_str' WHERE user_id = '$user_id' AND doc_type IN ('DRIVING_LICENSE', 'DL')");
        mysqli_commit($con);
        
        $whatsapp_status_msg = "";
        $fetch_user = mysqli_query($con, "SELECT name, mobile FROM customer_register WHERE id = '$user_id'");
        if ($userRow = mysqli_fetch_assoc($fetch_user)) {
            $name = $userRow['name'] ? strtoupper($userRow['name']) : 'User';
            $mobile = $userRow['mobile'];
            
            $templateName = ($action == 'approve') ? 'cp_dl_approved' : 'cp_dl_reject';
            $parameters = ($action == 'approve') ? [$name] : [$name, ucfirst($reason)];
            
            $api_result = sendMetaWhatsAppTemplate($con, $mobile, $templateName, $parameters);
            $whatsapp_status_msg = $api_result['success'] ? " (WhatsApp Sent)" : " (WhatsApp Failed)";
        }
        sendJsonResponse(['result' => "DL Verification updated successfully.$whatsapp_status_msg", 'type' => 1]);
    } catch (Exception $e) {
        mysqli_rollback($con);
        sendJsonResponse(['result' => 'Update failed.', 'type' => 0]);
    }
}

// ------------------------------------------------------------------
// 5. VERIFY VEHICLE
// ------------------------------------------------------------------
else if ($method == 'verify_vehicle') {
    $user_id = mysqli_real_escape_string($con, $_POST['id']);
    $action = $_POST['action']; 
    $vehicle_type = isset($_POST['vehicle_type']) ? mysqli_real_escape_string($con, $_POST['vehicle_type']) : '';
    $reason = isset($_POST['reason']) ? mysqli_real_escape_string($con, $_POST['reason']) : '';

    $vd = getVehicleDetails($con, $user_id);

    $dlCheckQuery = mysqli_query($con, "SELECT dl_status FROM kyc_carpool WHERE user_id = '$user_id'");
    $dlCheckRow = mysqli_fetch_assoc($dlCheckQuery);
    $kyc_dl_status = $dlCheckRow['dl_status'] ?? '';
    
    $json_dl_status = $vd['dl_status'] ?? '';
    
    $current_dl_status = !empty($kyc_dl_status) ? $kyc_dl_status : $json_dl_status;

    if (strtolower($current_dl_status) !== 'approved') {
        sendJsonResponse(['result' => 'Vehicle verification blocked: Driving License (DL) must be approved first.', 'type' => 0]);
    }

    $veh_stat_str = ($action == 'approve') ? 'approved' : 'rejected';
    $veh_verify_val = ($action == 'approve') ? 2 : 0;
    
    if ($action == 'approve' && !empty($vehicle_type)) { $vd['choosed_vehicle'] = $vehicle_type; }
    $vd['car_type_status'] = $veh_stat_str;
    $vd['car_type_reason'] = ($action == 'approve') ? null : $reason;
    if ($action == 'approve') {
        $vd['admin_verify'] = true;
        $vd['vehicle_review_message'] = "Vehicle Verification Approved";
    }

    $new_vd_json = mysqli_real_escape_string($con, json_encode($vd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

    mysqli_begin_transaction($con);
    try {
        mysqli_query($con, "UPDATE customer_register SET vehicle_verify = $veh_verify_val, vehicle_details = '$new_vd_json' WHERE id = '$user_id'");
        mysqli_commit($con);
        
        $whatsapp_status_msg = "";
        $fetch_user = mysqli_query($con, "SELECT name, mobile FROM customer_register WHERE id = '$user_id'");
        if ($userRow = mysqli_fetch_assoc($fetch_user)) {
            $name = $userRow['name'] ? strtoupper($userRow['name']) : 'User';
            $mobile = $userRow['mobile'];
            
            $templateName = ($action == 'approve') ? 'cp_vehicle_approved' : 'cp_vehicle_reject';
            $parameters = ($action == 'approve') ? [$name, strtoupper($vehicle_type)] : [$name, ucfirst($reason)];
            
            $api_result = sendMetaWhatsAppTemplate($con, $mobile, $templateName, $parameters);
            $whatsapp_status_msg = $api_result['success'] ? " (WhatsApp Sent)" : " (WhatsApp Failed)";
        }
        sendJsonResponse(['result' => "Vehicle Verification updated successfully.$whatsapp_status_msg", 'type' => 1]);
    } catch (Exception $e) {
        mysqli_rollback($con);
        sendJsonResponse(['result' => 'Update failed.', 'type' => 0]);
    }
}
?>