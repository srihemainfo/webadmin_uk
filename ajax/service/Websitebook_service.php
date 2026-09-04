<?php
include '../../include/shi-config.php';
include '../../include/functions.php';

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";
$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";
$role = $_REQUEST['role'] ?? ''; 
// Enable all error reporting
// error_reporting(E_ALL); 

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


if ($method === "websitebooklist") {
    try {
        $pattern = '/^\d{4}-\d{2}-\d{2}$/';
    
        $startDate = $_POST['startDate'] ?? '';
        $endDate = $_POST['endDate'] ?? '';
        $dateFilter = $_POST['dateFilter'] ?? '';
        $searchTxt = $_POST['searchTxt'] ?? '';
        $job_status = $_POST['job_status'] ?? '';
    
        $filterParts = [];
        $result = [];
        
        if (!empty($job_status)) {
            $filterParts[] = "cus.job_staus LIKE '%$job_status%'";
        }else{
            
            $filterParts[] = "cus.job_staus LIKE 'created' ";
        }
        
        $filterParts[] = "cus.user_id = '0' AND cus.global_type = 'customer' ";
        
        $whereClause = '';
        if (!empty($filterParts)) {
            $whereClause = 'WHERE ' . implode(' AND ', $filterParts);
        }
        
        
        $selectQuery = "
            SELECT *
            FROM cus_job_temp cus $whereClause
            ORDER BY cus.created_at DESC
        ";
        
        $get_data = $con->query($selectQuery);
    
        if ($get_data && $get_data->num_rows > 0) {
            $result['type'] = 1;
    
            while ($row = $get_data->fetch_assoc()) {
                $result['result'][] = $row;
            }
        } else {
            $result['type'] = 0;
            $result['result'] = [];
            $result['data'] = [];
        }
    
    } catch (Exception $e) {
        $result['type'] = 0;
        $result['result'] = $e->getMessage();
    }

}
// else if ($method === "get_cancelled_jobs") {
//     try {
//         $startDate = $_POST['startDate'] ?? '';
//         $endDate = $_POST['endDate'] ?? '';
//         $filterType = $_POST['filterType'] ?? '';

//         $filterParts = ["c.job_status = 'cancelled'"];

//         if (!empty($startDate) && !empty($endDate)) {
//             $col = ($filterType === 'pickup') ? 'pickup_date' : 'created_at';
//             $filterParts[] = "c.$col BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
//         }

//         $whereClause = 'WHERE ' . implode(' AND ', $filterParts);
        
//         $selectQuery = "SELECT c.*, 
//                         u.name as admin_name, 
//                         u.lname as admin_lname, 
//                         u.mobile as admin_mobile,
//                         cust.name as cust_name,
//                         cust.mobile as cust_mobile,
//                         jc.reason AS cancel_reason,
//                         jc.cancelled_by,
//                         jc.created_at AS cancelled_at,
//                         uc.name AS cancelled_by_name,
//                         jcd.doc_url AS cancel_doc
//                         FROM cus_job_temp c 
//                         LEFT JOIN user_register u ON c.user_id = u.id AND c.global_type != 'customer' AND c.global_type != 'schedule'
//                         LEFT JOIN customer_register cust ON c.user_id = cust.id AND (c.global_type = 'customer' OR c.global_type = 'schedule')
//                         LEFT JOIN job_cancellations jc ON c.id = jc.job_id
//                         LEFT JOIN user_register uc ON jc.cancelled_by = uc.id
//                         LEFT JOIN job_cancellation_docs jcd ON jc.id = jcd.cancellation_id
//                         $whereClause ORDER BY c.id DESC";
                        
//         $get_data = $con->query($selectQuery);

//         $result['type'] = 1;
//         $result['result'] = [];

//         if ($get_data && $get_data->num_rows > 0) {
            
//             $jobs = [];
//             $jobIds = [];

//             // 1. Fetch all rows and collect Job IDs
//             while ($row = $get_data->fetch_assoc()) {
//                 $jobs[] = $row;
//                 $jobId = (int)($row['id'] ?? 0);
//                 if ($jobId > 0) {
//                     $jobIds[] = $jobId;
//                 }
//             }

//             // 2. Fetch Push Notifications in ONE Query using JSON extraction (MySQL 5.7+)
//             // This prevents the N+1 query problem and removes the slow LIKE '%...%' scans
//             $pushCounts = [];
//             if (!empty($jobIds)) {
//                 $jobIdString = implode(',', $jobIds);
                
//                 // We extract the job_id from the JSON string directly in SQL and group by it.
//                 // Note: Make sure req_json format is strict JSON. 
//                 // Alternatively, if you don't have JSON_EXTRACT, you can fetch the recent push logs and process in PHP.
//                 $pushQuery = "
//                     SELECT 
//                         JSON_EXTRACT(req_json, '$.job_id') as p_job_id, 
//                         res_json 
//                     FROM push_notifications 
//                     WHERE JSON_EXTRACT(req_json, '$.job_id') IN ($jobIdString) 
//                     ORDER BY id ASC"; 
//                     // Ordering ASC so later records overwrite earlier ones in PHP loop, simulating 'DESC LIMIT 1'

//                 $push_q = mysqli_query($con, $pushQuery);
                
//                 if ($push_q && mysqli_num_rows($push_q) > 0) {
//                     while ($p_row = mysqli_fetch_assoc($push_q)) {
//                         $p_job_id = str_replace('"', '', $p_row['p_job_id']); // JSON_EXTRACT returns strings with quotes
                        
//                         if (!empty($p_row['res_json'])) {
//                             $resData = json_decode($p_row['res_json'], true);
//                             if (isset($resData['success_count'])) {
//                                 $pushCounts[$p_job_id] = (int)$resData['success_count'];
//                             }
//                         }
//                     }
//                 }
//             }

//             // 3. Process data and attach push counts
//             foreach ($jobs as $row) {
                
//                 // HANDLE FARE BREAKDOWN (Base & Toll)
//                 $fareDecoded = !empty($row['fare_breakdown']) ? json_decode($row['fare_breakdown'], true) : [];
//                 $baseVal = !empty($row['base_fare']) ? $row['base_fare'] : ($fareDecoded['base_fare'] ?? 0);
//                 $tollVal = !empty($row['toll_fare']) ? $row['toll_fare'] : ($fareDecoded['toll_fare'] ?? 0);

//                 $row['base_fare'] = ($baseVal > 0) ? $baseVal : "Included";
//                 $row['toll_fare'] = ($tollVal > 0) ? $tollVal : "Included";
//                 $row['fare_breakdown'] = $fareDecoded;

//                 $uDetails = [];
//                 if (!empty($row['user_details']) && is_string($row['user_details'])) {
//                     $uDetails = json_decode($row['user_details'], true) ?: [];
//                 }
//                 $jsonName = $uDetails['name'] ?? '';
//                 $jsonMobile = $uDetails['mobile'] ?? '';

//                 // NAME & MOBILE FALLBACK LOGIC
//                 $adminFullName = !empty($row['admin_name']) ? trim($row['admin_name'] . ' ' . ($row['admin_lname'] ?? '')) : '';
//                 $displayName = !empty($row['cust_name']) ? $row['cust_name'] : 
//                               (!empty($adminFullName) ? $adminFullName : 
//                               (!empty($jsonName) ? $jsonName : 'Customer'));

//                 $rawMobile = !empty($row['cust_mobile']) ? $row['cust_mobile'] : 
//                             (!empty($row['admin_mobile']) ? $row['admin_mobile'] : 
//                             (!empty($jsonMobile) ? $jsonMobile : ($row['mobile'] ?? '')));

//                 $row['mobile'] = preg_replace('/[^0-9]/', '', $rawMobile);
//                 $row['name'] = $displayName;
//                 $row['poster_name'] = $displayName;

//                 if ($row['global_type'] === 'schedule') {
//                     $row['base_fare'] = null;
//                     $row['toll_fare'] = null;
//                     if (!empty($fareDecoded['total_fare'])) {
//                         $row['fare'] = $fareDecoded['total_fare'];
//                     }
//                 }

//                 // SET CANCEL DEFAULTS
//                 $row['cancel_reason'] = !empty($row['cancel_reason']) ? $row['cancel_reason'] : 'No specific reason provided.';
//                 $row['cancelled_at']  = !empty($row['cancelled_at']) ? date('d M Y - h:i A', strtotime($row['cancelled_at'])) : 'Unknown Time';
//                 $row['cancel_doc']    = !empty($row['cancel_doc']) ? $row['cancel_doc'] : null;

//                 // Cancelled By Assignment
//                 if (!empty($row['cancelled_by_name'])) {
//                     $row['cancelled_by_display'] = trim($row['cancelled_by_name']);
//                 } elseif (!empty($row['cancelled_by']) && !is_numeric($row['cancelled_by'])) {
//                     $row['cancelled_by_display'] = trim($row['cancelled_by']);
//                 } else {
//                     $row['cancelled_by_display'] = 'User';
//                 }

//                 // ATTACH PUSH NOTIFICATION COUNT FROM CACHE
//                 $searchJobId = (int)($row['id'] ?? 0);
//                 $row['count'] = $pushCounts[$searchJobId] ?? 0;

//                 $result['result'][] = $row;
//             }
//         }
//         goto resutGJHIP;
//     } catch (Exception $e) {
//         $result['type'] = 0;
//         $result['result'] = $e->getMessage();
//         goto resutGJHIP;
//     }
// }
else if ($method === "get_cancelled_jobs") {
    try {
        $startDate = $_POST['startDate'] ?? '';
        $endDate = $_POST['endDate'] ?? '';
        $filterType = $_POST['filterType'] ?? '';

        // $filterParts = ["c.job_status = 'cancelled'"];
        $filterParts = [
    "c.job_status = 'cancelled'",
    "c.job_no NOT LIKE 'GRP-%'"
];
        if (!empty($startDate) && !empty($endDate)) {
            $col = ($filterType === 'pickup') ? 'pickup_date' : 'created_at';
            $filterParts[] = "c.$col BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
        }

        $whereClause = 'WHERE ' . implode(' AND ', $filterParts);
        
        $selectQuery = "SELECT c.*, 
                        u.name as admin_name, 
                        u.lname as admin_lname, 
                        u.mobile as admin_mobile,
                        u.created_at as admin_created_at,
                        cust.name as cust_name,
                        cust.mobile as cust_mobile,
                        cust.created_at as cust_created_at,
                        jc.reason AS cancel_reason,
                        jc.cancelled_by,
                        jc.created_at AS cancelled_at,
                        uc.name AS cancelled_by_name,
                        jcd.doc_url AS cancel_doc
                        FROM cus_job_temp c 
                        LEFT JOIN user_register u ON c.user_id = u.id AND c.global_type != 'customer' AND c.global_type != 'schedule'
                        LEFT JOIN customer_register cust ON c.user_id = cust.id AND (c.global_type = 'customer' OR c.global_type = 'schedule')
                        LEFT JOIN job_cancellations jc ON c.id = jc.job_id
                        LEFT JOIN user_register uc ON jc.cancelled_by = uc.id
                        LEFT JOIN job_cancellation_docs jcd ON jc.id = jcd.cancellation_id
                        $whereClause ORDER BY c.id DESC";
                        
        $get_data = $con->query($selectQuery);

        $result['type'] = 1;
        $result['result'] = [];

        if ($get_data && $get_data->num_rows > 0) {
            
            $jobs = [];
            $jobIds = [];

            // 1. Fetch all rows and collect Job IDs
            while ($row = $get_data->fetch_assoc()) {
                $jobs[] = $row;
                $jobId = (int)($row['id'] ?? 0);
                if ($jobId > 0) {
                    $jobIds[] = $jobId;
                }
            }

            // 2. Fetch Push Notifications in ONE Query using JSON extraction (MySQL 5.7+)
            // This prevents the N+1 query problem and removes the slow LIKE '%...%' scans
            $pushCounts = [];
            if (!empty($jobIds)) {
                $jobIdString = implode(',', $jobIds);
                
                // We extract the job_id from the JSON string directly in SQL and group by it.
                // Note: Make sure req_json format is strict JSON. 
                // Alternatively, if you don't have JSON_EXTRACT, you can fetch the recent push logs and process in PHP.
                $pushQuery = "
                    SELECT 
                        JSON_EXTRACT(req_json, '$.job_id') as p_job_id, 
                        res_json 
                    FROM push_notifications 
                    WHERE JSON_EXTRACT(req_json, '$.job_id') IN ($jobIdString) 
                    ORDER BY id ASC"; 
                    // Ordering ASC so later records overwrite earlier ones in PHP loop, simulating 'DESC LIMIT 1'

                $push_q = mysqli_query($con, $pushQuery);
                
                if ($push_q && mysqli_num_rows($push_q) > 0) {
                    while ($p_row = mysqli_fetch_assoc($push_q)) {
                        $p_job_id = str_replace('"', '', $p_row['p_job_id']); // JSON_EXTRACT returns strings with quotes
                        
                        if (!empty($p_row['res_json'])) {
                            $resData = json_decode($p_row['res_json'], true);
                            if (isset($resData['success_count'])) {
                                $pushCounts[$p_job_id] = (int)$resData['success_count'];
                            }
                        }
                    }
                }
            }

            // 3. Process data and attach push counts
            foreach ($jobs as $row) {
                
                // HANDLE FARE BREAKDOWN (Base & Toll)
                $fareDecoded = !empty($row['fare_breakdown']) ? json_decode($row['fare_breakdown'], true) : [];
                $baseVal = !empty($row['base_fare']) ? $row['base_fare'] : ($fareDecoded['base_fare'] ?? 0);
                $tollVal = !empty($row['toll_fare']) ? $row['toll_fare'] : ($fareDecoded['toll_fare'] ?? 0);

                $row['base_fare'] = ($baseVal > 0) ? $baseVal : "Included";
                $row['toll_fare'] = ($tollVal > 0) ? $tollVal : "Included";
                $row['fare_breakdown'] = $fareDecoded;

                $uDetails = [];
                if (!empty($row['user_details']) && is_string($row['user_details'])) {
                    $uDetails = json_decode($row['user_details'], true) ?: [];
                }
                $jsonName = $uDetails['name'] ?? '';
                $jsonMobile = $uDetails['mobile'] ?? '';

                // NAME & MOBILE FALLBACK LOGIC
                $adminFullName = !empty($row['admin_name']) ? trim($row['admin_name'] . ' ' . ($row['admin_lname'] ?? '')) : '';
                $displayName = !empty($row['cust_name']) ? $row['cust_name'] : 
                              (!empty($adminFullName) ? $adminFullName : 
                              (!empty($jsonName) ? $jsonName : 'Customer'));

                $rawMobile = !empty($row['cust_mobile']) ? $row['cust_mobile'] : 
                            (!empty($row['admin_mobile']) ? $row['admin_mobile'] : 
                            (!empty($jsonMobile) ? $jsonMobile : ($row['mobile'] ?? '')));

                $row['mobile'] = preg_replace('/[^0-9]/', '', $rawMobile);
                $row['name'] = $displayName;
                $row['poster_name'] = $displayName;

                // CREATED AT FALLBACK LOGIC
                $row['cust_created_at'] = !empty($row['cust_created_at']) ? $row['cust_created_at'] : 'N/A';
                $row['admin_created_at'] = !empty($row['admin_created_at']) ? $row['admin_created_at'] : 'N/A';
                $row['user_created_at'] = ($row['cust_created_at'] !== 'N/A') ? $row['cust_created_at'] : 
                                         (($row['admin_created_at'] !== 'N/A') ? $row['admin_created_at'] : 'N/A');

                if ($row['global_type'] === 'schedule') {
                    $row['base_fare'] = null;
                    $row['toll_fare'] = null;
                    if (!empty($fareDecoded['total_fare'])) {
                        $row['fare'] = $fareDecoded['total_fare'];
                    }
                }

                // SET CANCEL DEFAULTS
                $row['cancel_reason'] = !empty($row['cancel_reason']) ? $row['cancel_reason'] : 'No specific reason provided.';
                $row['cancelled_at']  = !empty($row['cancelled_at']) ? date('d M Y - h:i A', strtotime($row['cancelled_at'])) : 'Unknown Time';
                $row['cancel_doc']    = !empty($row['cancel_doc']) ? $row['cancel_doc'] : null;

                // Cancelled By Assignment
                if (!empty($row['cancelled_by_name'])) {
                    $row['cancelled_by_display'] = trim($row['cancelled_by_name']);
                } elseif (!empty($row['cancelled_by']) && !is_numeric($row['cancelled_by'])) {
                    $row['cancelled_by_display'] = trim($row['cancelled_by']);
                } else {
                    $row['cancelled_by_display'] = 'User';
                }

                // ATTACH PUSH NOTIFICATION COUNT FROM CACHE
                $searchJobId = (int)($row['id'] ?? 0);
                $row['count'] = $pushCounts[$searchJobId] ?? 0;

                $result['result'][] = $row;
            }
        }
        goto resutGJHIP;
    } catch (Exception $e) {
        $result['type'] = 0;
        $result['result'] = $e->getMessage();
        goto resutGJHIP;
    }
}


else if ($method === "lead_details") {
    try {
        $pattern = '/^\d{4}-\d{2}-\d{2}$/';

        $lead_id = $_POST['id'] ?? '';
        // var_dump($lead_id);die;
        
        $filterParts = [];
        $result = [];
        if($lead_id != ''){
            // if ($com_comment != '' || $com_message != '' || $com_dateInput != '') {

                // $s_lead = select_query($con, "goride_agency_ad_leads", "", "`id` = '$lead_id' ORDER BY `id` ASC LIMIT 1", "", "");
                
                $selectQuery = "SELECT * FROM cus_job_temp cus WHERE id = '$lead_id'";
                $s_result = mysqli_query($con, $selectQuery);
                
                if ($s_result && mysqli_num_rows($s_result) > 0) {
                    $row = mysqli_fetch_assoc($s_result);
                
                    // Decode the JSON comment field
                    $comments = json_decode($row['comments'], true);
                    $datas = [];
                    // var_dump($comments);dide;
                    if (is_array($comments) && count($comments) > 0) {
                        foreach ($comments as $comment) {
                            $personId = $comment['person'] ?? 0;
                    
                            // Fetch user once per person (optional optimization)
                            $s_lead = select_query($con, "user_register", "", "`id` = '$personId' LIMIT 1", "", "");
                    
                            if ($s_lead['nr'] < 1) {
                                $userName = 'Unknown';
                            } else {
                                $userName = trim($s_lead['result'][0]['name'] . ' ' . $s_lead['result'][0]['lname']);
                            }
                    
                            $datas[] = [
                                'person' => $userName,
                                'comment' => $comment['comment'] ?? '',
                                'message' => $comment['message'] ?? '',
                                'next_follow' => $comment['next_follow'] ?? '',
                                'timestamp' => $comment['timestamp'] ?? ''
                            ];
                        }
                    } else {
                        $result['type'] = 0;
                        $result['result'] = 'No comments found.';
                        $result['data'] = [];
                        goto resutGJHIP;
                    }

                    
                    $result['type'] = 1;
                    $result['result'] = $datas;
                    $result['data'] = [];
                    
                    goto resutGJHIP;
                } else {
                    echo "No lead found.";
                }
            // }

        }else{
            $result['type'] = 0;
            $result['result'] = 'Lead ID Not Found';
            $result['data'] = [];
        }
        
        // var_dump($result);die;
    } catch (Exception $e) {
        $result['type'] = 0;
        $result['result'] = $e->getMessage();
    }
}
// else if ($method === "wh_log_details") {
//     $result = ['type' => 0, 'result' => '', 'data' => []];

//     try {
//         $lead_id = isset($_POST['id']) ? intval($_POST['id']) : 0;

//         if ($lead_id > 0) {
//             // 1. Use Prepared Statements for Security
//             $stmt = mysqli_prepare($con, "SELECT whatsapp_log FROM goride_agency_ad_leads WHERE id = ?");
//             mysqli_stmt_bind_param($stmt, "i", $lead_id);
//             mysqli_stmt_execute($stmt);
//             $s_result = mysqli_stmt_get_result($stmt);

//             if ($row = mysqli_fetch_assoc($s_result)) {
//                 $raw_log = $row['whatsapp_log'];
                
//                 // 2. Decode and validate JSON
//                 $comments = json_decode($raw_log, true);

//                 if (json_last_error() === JSON_ERROR_NONE && is_array($comments) && !empty($comments)) {
//                     $datas = [];
//                     foreach ($comments as $comment) {
//                         // 3. Map keys and handle missing data gracefully
//                         $datas[] = [
//                             'message'   => $comment['message'] ?? '',
//                             'timestamp' => $comment['date'] ?? ''
//                         ];
//                     }
                    
//                     // Reverse to show latest logs first (optional)
//                     $result['type'] = 1;
//                     $result['result'] = array_reverse($datas); 
//                 } else {
//                     $result['result'] = 'No logs available or invalid format.';
//                 }
//             } else {
//                 $result['result'] = 'Lead ID not found in database.';
//             }
//             mysqli_stmt_close($stmt);
//         } else {
//             $result['result'] = 'Invalid Lead ID provided.';
//         }

//     } catch (Exception $e) {
//         $result['result'] = 'Server Error: ' . $e->getMessage();
//     }

//     // Return the response as JSON
//     echo json_encode($result);
//     exit;
// }

else if ($method === "lead_comment") {
    try {
        $pattern = '/^\d{4}-\d{2}-\d{2}$/';

        $lead_id = $_POST['lead_id'] ?? '';
        // var_dump($lead_id);die;
        $com_comment = $_POST['com_comment'] ?? '';
        $com_dateInput = $_POST['com_dateInput'] ?? '';
        $com_message = $_POST['com_message'] ?? '';
        
        $filterParts = [];
        $result = [];
        if($lead_id != ''){
            if ($com_comment != '' || $com_message != '' || $com_dateInput != '') {

                $s_lead = select_query($con, "cus_job_temp", "", "`id` = '$lead_id' ORDER BY `id` ASC LIMIT 1", "", "");
            
                if ($s_lead['nr'] < 1) {
                    $result['type'] = 0;
                    $result['result'] = 'Lead Not Found!';
                    $result['data'] = [];
                    goto resutGJHIP;
                }
            
                $new_comment = [
                    'person'      => $_SESSION['memid'],
                    'comment'     => $com_comment ?? '',
                    'next_follow' => $com_dateInput ?? '',
                    'message'     => $com_message ?? '',
                    'timestamp'   => date("Y-m-d H:i:s")
                ];
            
                if (!empty($s_lead['result'][0]['comments'])) {
                    $load_data = json_decode($s_lead['result'][0]['comments'], true);
                    if (!is_array($load_data)) $load_data = [];
                    $load_data[] = $new_comment;
                } else {
                    $load_data = [$new_comment];
                }
            
                $json_data = mysqli_real_escape_string($con, json_encode($load_data, JSON_UNESCAPED_UNICODE));
            
                $upd_qry = "UPDATE cus_job_temp SET comments = '$json_data', comments_status = '$com_comment' WHERE id = '$lead_id'";
                // var_dump("UPDATE cus_job_temp SET comments = '$json_data', comments_status = '$com_comment' WHERE id = '$lead_id'");die;
                $update_result = mysqli_query($con, $upd_qry);
            
                if ($update_result) {
                    $result['type'] = 1;
                    $result['result'] = 'Comment added successfully.';
                    $result['data'] = '';
                    goto resutGJHIP;
                } else {
                    $result['type'] = 0;
                    $result['result'] = 'Failed to update comment.';
                    $result['data'] = '';
                    goto resutGJHIP;
                }
            }

        }else{
            $result['type'] = 0;
            $result['result'] = 'Lead ID Not Found';
            $result['data'] = [];
        }
        
        // var_dump($result);die;
    } catch (Exception $e) {
        $result['type'] = 0;
        $result['result'] = $e->getMessage();
    }
}

else if ($method == "cancel_job") {
    try {

        $job_no = $_POST['job_no'] ?? '';

        if (empty($job_no)) {
            $result = [
                'type'   => 0,
                'result' => 'Job ID Not Found',
                'data'   => []
            ];
            echo json_encode($result);
            exit;
        }

        // Check if job exists
        $stmt = $con->prepare("SELECT id FROM cus_job_temp WHERE job_no = ? ORDER BY id ASC LIMIT 1");
        $stmt->bind_param("s", $job_no);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows < 1) {
            $result = [
                'type'   => 0,
                'result' => 'Job Not Found!',
                'data'   => []
            ];
            echo json_encode($result);
            exit;
        }

        $row = $res->fetch_assoc();
        $lead_id = $row['id'];
        // var_dump($lead_id);die;
        // Update job status
        $update_stmt = $con->prepare("UPDATE cus_job_temp SET job_status = 'cancelled' WHERE id = ?");
        $update_stmt->bind_param("i", $lead_id);
        $update_stmt->execute();

        if ($update_stmt->affected_rows > 0) {
            $result = [
                'type'   => 1,
                'result' => 'Job cancelled successfully.',
                'data'   => ''
            ];
        } else {
            $result = [
                'type'   => 0,
                'result' => 'Job already cancelled.',
                'data'   => ''
            ];
        }

    } catch (Exception $e) {
        $result = [
            'type'   => 0,
            'result' => $e->getMessage(),
            'data'   => []
        ];
    }

    echo json_encode($result);
    exit;
}


resutGJHIP:
echo json_encode($result);