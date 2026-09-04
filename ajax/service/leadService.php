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


if ($type == 'agent') {
    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($role) AND" : "";
} else {
    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$role' AND" : "";
}

$headers = apache_request_headers();
$result = [];

$post_csrf = $headers['X-Csrf-Token'] ?? '';


if ($method === "agencyLeadList") {
    try {
        $pattern = '/^\d{4}-\d{2}-\d{2}$/';
    
        $startDate = $_POST['startDate'] ?? '';
        $endDate = $_POST['endDate'] ?? '';
        $dateFilter = $_POST['dateFilter'] ?? '';
        $searchTxt = $_POST['searchTxt'] ?? '';
        $utm_campaign = $_POST['utm_campaign'] ?? '';
        $registered_status = $_POST['registered_status'] ?? '';
    
        $filterParts = [];
        $result = [];
    
        // Search filter
        if (!empty($searchTxt)) {
            $filterParts[] = "(gl.full_name LIKE '%$searchTxt%' OR gl.email LIKE '%$searchTxt%' OR gl.phone LIKE '%$searchTxt%')";
        }
    
        // Date range filter
        if (!empty($dateFilter)) {
            if (!preg_match($pattern, $startDate) || !preg_match($pattern, $endDate)) {
                $result['type'] = 0;
                $result['result'] = [];
                $result['data'] = [];
                goto resutGJHIP;
            }
    
            if (empty($startDate) || empty($endDate)) {
                $result['type'] = 0;
                $result['result'] = [];
                $result['data'] = [];
                goto resutGJHIP;
            }
    
            $filterParts[] = "gl.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
        } else {
            $todayStart = date('Y-m-d') . ' 00:00:00';
            $todayEnd = date('Y-m-d') . ' 23:59:59';
    
            $filterParts[] = "gl.created_at BETWEEN '$todayStart' AND '$todayEnd'";
        }
    
        // utm_campaign filter
        if (!empty($utm_campaign)) {
            $filterParts[] = "gl.campaign_name LIKE '%$utm_campaign%'";
        }
        
        if ($registered_status !== '') {
            if ($registered_status == 1) {
                $filterParts[] = "ur.mobile IS NOT NULL";
            } elseif ($registered_status == 0) {
                $filterParts[] = "ur.mobile IS NULL";
            }
        }
    
        // Combine WHERE clause
        $whereClause = '';
        if (!empty($filterParts)) {
            $whereClause = 'WHERE ' . implode(' AND ', $filterParts);
        }
    
        // Final query with LEFT JOIN and CASE for register_status
        $selectQuery = "
            SELECT gl.*, 
                   CASE
                       WHEN EXISTS (
                           SELECT 1
                           FROM user_register ur
                           WHERE ur.mobile = gl.phone
                       ) THEN 1
                       ELSE 0
                   END AS register_status
            FROM goride_agency_ad_leads gl
            $whereClause
            ORDER BY gl.created_at DESC
        ";
        
        // var_dump($selectQuery);die;
    
        // Execute query
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
elseif ($method === "websiteBookingList") {
    try {
        $pattern = '/^\d{4}-\d{2}-\d{2}$/';
    
        $startDate = $_POST['startDate'] ?? '';
        $endDate = $_POST['endDate'] ?? '';
        $dateFilter = $_POST['dateFilter'] ?? '';
        $searchTxt = $_POST['searchTxt'] ?? '';
        $job_status = $_POST['job_status'] ?? '';
        $filterType = $_POST['filterType'] ?? '';
    
        $filterParts = [];
        $result = [];
        
        $col_jb = $filterType == 'pickup'
            ? 'pickup_date'
            : 'created_at';
        
        if (!empty($startDate) && !empty($endDate)) {
        
            if (!preg_match($pattern, $startDate) || !preg_match($pattern, $endDate)) {
                $result['type'] = 0;
                $result['result'] = [];
                $result['data'] = [];
                goto resutGJHIP;
            }
        
            if (empty($startDate) || empty($endDate)) {
                $result['type'] = 0;
                $result['result'] = [];
                $result['data'] = [];
                goto resutGJHIP;
            }
        
            $filterParts[] = "ct.$col_jb BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
        
        } else {
        
            $todayStart = date('Y-m-d') . ' 00:00:00';
            $todayEnd   = date('Y-m-d') . ' 23:59:59';
        
            $filterParts[] = "ct.$col_jb BETWEEN '$todayStart' AND '$todayEnd'";
        }
        
        if (!empty($job_status)) {
            $filterParts[] = "ct.job_status LIKE '%$job_status%'";
        }else{
            $filterParts[] = "ct.job_status LIKE 'created' ";
        }
        
        $filterParts[] = "ct.user_id = '0' AND ct.global_type = 'customer'";
        $filterParts[] = "(ct.job_status != 'created' OR ct.pickup_date >= NOW())";
        $whereClause = '';
        if (!empty($filterParts)) {
            $whereClause = 'WHERE ' . implode(' AND ', $filterParts);
        }
    
        $selectQuery = "
            SELECT ct.*,
            ct.user_details->>'$.name'   AS name,
            ct.user_details->>'$.email'  AS email,
            ct.user_details->>'$.cab_type'  AS car_type,
            ct.user_details->>'$.mobile' AS mobile
            FROM cus_job_temp ct
            $whereClause
            ORDER BY ct.created_at DESC
        ";
        
        // var_dump($selectQuery);die;
        
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

else if ($method === "wh_log_details") {
    $result = ['type' => 0, 'result' => '', 'data' => []];

    try {
        $lead_id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        if ($lead_id > 0) {
            // 1. Use Prepared Statements for Security
            $stmt = mysqli_prepare($con, "SELECT whatsapp_log FROM goride_agency_ad_leads WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $lead_id);
            mysqli_stmt_execute($stmt);
            $s_result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($s_result)) {
                $raw_log = $row['whatsapp_log'];
                
                // 2. Decode and validate JSON
                $comments = json_decode($raw_log, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($comments) && !empty($comments)) {
                    $datas = [];
                    foreach ($comments as $comment) {
                        // 3. Map keys and handle missing data gracefully
                        $datas[] = [
                            'message'   => $comment['message'] ?? '',
                            'timestamp' => $comment['date'] ?? ''
                        ];
                    }
                    
                    // Reverse to show latest logs first (optional)
                    $result['type'] = 1;
                    $result['result'] = array_reverse($datas); 
                } else {
                    $result['result'] = 'No logs available or invalid format.';
                }
            } else {
                $result['result'] = 'Lead ID not found in database.';
            }
            mysqli_stmt_close($stmt);
        } else {
            $result['result'] = 'Invalid Lead ID provided.';
        }

    } catch (Exception $e) {
        $result['result'] = 'Server Error: ' . $e->getMessage();
    }

    // Return the response as JSON
    echo json_encode($result);
    exit;
} else if ($method === "webBook_log_details") {
    $result = ['type' => 0, 'result' => '', 'data' => []];

    try {
        $lead_id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        if ($lead_id > 0) {
            // 1. Use Prepared Statements for Security
            $stmt = mysqli_prepare($con, "SELECT whatsapp_log FROM cus_job_temp WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $lead_id);
            mysqli_stmt_execute($stmt);
            $s_result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($s_result)) {
                $raw_log = $row['whatsapp_log'];
                
                // 2. Decode and validate JSON
                $comments = json_decode($raw_log, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($comments) && !empty($comments)) {
                    $datas = [];
                    foreach ($comments as $comment) {
                        // 3. Map keys and handle missing data gracefully
                        $datas[] = [
                            'message'   => $comment['message'] ?? '',
                            'timestamp' => $comment['date'] ?? ''
                        ];
                    }
                    
                    // Reverse to show latest logs first (optional)
                    $result['type'] = 1;
                    $result['result'] = array_reverse($datas); 
                } else {
                    $result['result'] = 'No logs available or invalid format.';
                }
            } else {
                $result['result'] = 'Lead ID not found in database.';
            }
            mysqli_stmt_close($stmt);
        } else {
            $result['result'] = 'Invalid Lead ID provided.';
        }

    } catch (Exception $e) {
        $result['result'] = 'Server Error: ' . $e->getMessage();
    }

    // Return the response as JSON
    echo json_encode($result);
    exit;
} else if ($method === "lead_details") {
    try {
        $pattern = '/^\d{4}-\d{2}-\d{2}$/';

        $lead_id = $_POST['id'] ?? '';
        
        $filterParts = [];
        $result = [];
        if($lead_id != ''){
            // if ($com_comment != '' || $com_message != '' || $com_dateInput != '') {

                // $s_lead = select_query($con, "goride_agency_ad_leads", "", "`id` = '$lead_id' ORDER BY `id` ASC LIMIT 1", "", "");
                
                $selectQuery = "SELECT * FROM goride_agency_ad_leads gl WHERE id = '$lead_id'";
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
} else if ($method === "wb_lead_details") {
    try {
        $pattern = '/^\d{4}-\d{2}-\d{2}$/';

        $lead_id = $_POST['id'] ?? '';
        
        $filterParts = [];
        $result = [];
        if($lead_id != ''){
            // if ($com_comment != '' || $com_message != '' || $com_dateInput != '') {

                // $s_lead = select_query($con, "goride_agency_ad_leads", "", "`id` = '$lead_id' ORDER BY `id` ASC LIMIT 1", "", "");
                
                $selectQuery = "SELECT * FROM cus_job_temp gl WHERE id = '$lead_id'";
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
} else if ($method === "lead_comment") {
    try {
        $pattern = '/^\d{4}-\d{2}-\d{2}$/';

        $lead_id = $_POST['lead_id'] ?? '';
        $com_comment = $_POST['com_comment'] ?? '';
        $com_dateInput = $_POST['com_dateInput'] ?? '';
        $com_message = $_POST['com_message'] ?? '';
        
        $filterParts = [];
        $result = [];
        if($lead_id != ''){
            if ($com_comment != '' || $com_message != '' || $com_dateInput != '') {

                $s_lead = select_query($con, "goride_agency_ad_leads", "", "`id` = '$lead_id' ORDER BY `id` ASC LIMIT 1", "", "");
            
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
            
                $upd_qry = "UPDATE goride_agency_ad_leads SET comments = '$json_data', comments_status = '$com_comment' WHERE id = '$lead_id'";
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
} else if ($method === "webBook_lead_comment") {
    try {
        $pattern = '/^\d{4}-\d{2}-\d{2}$/';

        $lead_id = $_POST['lead_id'] ?? '';
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

resutGJHIP:
echo json_encode($result);