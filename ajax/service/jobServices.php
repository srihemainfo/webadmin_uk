<?php
include '../../include/shi-config.php';
include '../../include/functions.php';

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
$result = [];

$post_csrf = $headers['X-Csrf-Token'] ?? '';


if ($method == "jobList") {
    try {
        $pattern = '/^\d{4}-\d{2}-\d{2}$/';
    
        $startDate = ($_POST['startDate']) ?? '';
        $endDate = ($_POST['endDate']) ?? '';
        $dateFilter = ($_POST['dateFilter']) ?? '';
        $jobType = BlockSQLInjection($_POST['jobType']) ?? '';
        $jobStatus = BlockSQLInjection($_POST['jobStatus']) ?? '';
        $job_no = $_POST['job_no'] ?? '';
    
        $filterParts = [];
        $result = [];
        // var_dump($jobStatus);die;
        if (!empty($dateFilter)) {
            // if (!preg_match($pattern, $startDate) || !preg_match($pattern, $endDate)) {
            //     $result['type'] = 0;
            //     $result['result'] = [];
            //     $result['data'] = [];
            //     goto resutGJHIP;
            // }
    
            if (!empty($startDate) && !empty($endDate)) {
                $filterParts[] = "open_jobs.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
            }
        }
        
       
    
        if (!empty($jobType)) {
            $jobType = $con->real_escape_string($jobType);
            $filterParts[] = "open_jobs.job_type LIKE '%$jobType%'";
        }
        
        if (!empty($job_no)) {
            $job_no = $con->real_escape_string($job_no);
            $filterParts[] = "open_jobs.job_no = '$job_no'";
        }
        
        if (!empty($jobStatus)) {
            $jobStatus = $con->real_escape_string($jobStatus);

            if($jobStatus == 'not_complete'){
                
                $filterParts[] = "open_jobs.job_status IN ('created','bidding') AND open_jobs.pickup_date > NOW() AND open_jobs.deletes = '0'";
            }
            if($jobStatus == 'bidding'){
                
                $filterParts[] = "open_jobs.job_status IN ('bidding') AND open_jobs.pickup_date > NOW() AND open_jobs.deletes = '0'";
            }
            if($jobStatus == 'accepted'){
                
                $filterParts[] = "open_jobs.job_status IN ('accept') AND open_jobs.deletes = '0'";
            }
            if($jobStatus == 'cancelled'){
                
                $filterParts[] = "open_jobs.job_status IN ('cancelled') AND open_jobs.deletes = '0'";
            }
            if($jobStatus == 'expired'){
                
                $filterParts[] = "open_jobs.job_status IN ('created','bidding') AND open_jobs.pickup_date < NOW() AND open_jobs.deletes = '0'";
            }
             if($jobStatus == 'deleted'){
                
                $filterParts[] = "open_jobs.deletes = '1'";
                // var_dump($filterParts);die;
            }
                        
            
        }else{

            // $filterParts[] = "open_jobs.deletes = '0'";
        }
    
    
        $whereClause = '';
        if (!empty($filterParts)) {
            $whereClause = 'WHERE ' . implode(' AND ', $filterParts);
        }
        // var_dump($filterParts);die;
    
        $selectQuery = "
            SELECT open_jobs.*, user_register.name, user_register.mobile,
            kyc_details.type, kyc_details.id AS kd_id
            FROM open_jobs 
            LEFT JOIN user_register ON open_jobs.user_id = user_register.id 
            LEFT JOIN kyc_details ON user_register.id = kyc_details.user_id
            $whereClause 
            ORDER BY open_jobs.created_at DESC
        ";
        
    
        $get_data = $con->query($selectQuery);
        //  var_dump($selectQuery);die;
    
        if ($get_data && $get_data->num_rows > 0) {
            $result['type'] = 1;
    
            while ($row = $get_data->fetch_assoc()) {
                
                $bids = json_decode($row['bids_details'], true);
    
                $row['bid_count'] = is_array($bids) ? count($bids) : 0;
    
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
} else if ($method == "cancelJob") {
    
    $job_id = $_POST['job_id'] ?? '';
    $user_id = $_POST['user_id'] ?? '';
    $job_no = $_POST['job_no'] ?? '';
    $jobtype = $_POST['jobtype'] ?? '';
    $response = ["type" => 0, "msg" => "Invalid request"];
    
    if (!empty($job_id) && !empty($user_id)) {
        $apiURL = API_DOMAIN_2."admin-cancel-job";
        $headers = [
            'Content-Type: application/x-www-form-urlencoded'
        ];
        
        $postData = [
            'job_id' => $job_id,
            'user_id' => $user_id,
            'job_type' => $jobtype,
            'job_no' => $job_no,
            'auth_key' => 'ASDFGHJKLqwertyuiopMNBVCXZ!@#$%^&*()0987612345'
        ];
    
        $apiResponse = requestAPI('POST', $apiURL, $headers, $postData);
        $decoded = json_decode($apiResponse, true);
    
        if (!empty($decoded) && isset($decoded['status']) && $decoded['status'] == true) {
            $response = ["type" => 1, "msg" => "Job cancelled successfully"];
        } else {
            $response = ["type" => 0, "msg" => $decoded['message'] ?? "Failed to cancel job"];
        }
    }
    
    echo json_encode($response);
    exit;
} else if ($method == "deleteJob") {
    
    $job_id = $_POST['job_id'] ?? '';
    $user_id = $_POST['user_id'] ?? '';
    $job_no = $_POST['job_no'] ?? '';
    $response = ["type" => 0, "msg" => "Invalid request"];
    
    if (!empty($job_id) && !empty($user_id)) {
        $apiURL = API_DOMAIN_2."admin-delete-job";
        $headers = [
            'Content-Type: application/x-www-form-urlencoded'
        ];
        
        $postData = [
            'job_id' => $job_id,
            'user_id' => $user_id,
            'job_no' => $job_no,
            'auth_key' => 'ASDFGHJKLqwertyuiopMNBVCXZ!@#$%^&*()0987612345'
        ];
    
        $apiResponse = requestAPI('POST', $apiURL, $headers, $postData);
        $decoded = json_decode($apiResponse, true);
    
        if (!empty($decoded) && isset($decoded['status']) && $decoded['status'] == true) {
            $response = ["type" => 1, "msg" => "Job deleted successfully"];
        } else {
            $response = ["type" => 0, "msg" => $decoded['message'] ?? "Failed to delete job"];
        }
    }

    echo json_encode($response);
    exit;
} else if ($method == "bid_details") {

    $response = ["type" => 0, "result" => []];

    $job_id = isset($_POST['job_id']) ? (int)$_POST['job_id'] : 0;
    if ($job_id == 0) {
        echo json_encode($response);
        exit;
    }

    // 1. Fetch bids_details safely
    $sql = "SELECT user_id, bids_details FROM open_jobs WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $job_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result || mysqli_num_rows($result) == 0) {
        echo json_encode($response);
        exit;
    }

    $row = mysqli_fetch_assoc($result);
    $bids_details = json_decode($row['bids_details'], true);

    if (!is_array($bids_details) || empty($bids_details)) {
        echo json_encode($response);
        exit;
    }

    // 2. Collect bidder IDs
    $user_ids = array_map('intval', array_keys($bids_details));

    // 3. Fetch bidder details in ONE query
    $placeholders = implode(',', array_fill(0, count($user_ids), '?'));
    $types = str_repeat('i', count($user_ids));

    $user_sql = "SELECT user_register.id, user_register.name, user_register.mobile, kyc_details.id AS kd_id
                 FROM user_register LEFT JOIN kyc_details ON user_register.id = kyc_details.user_id  WHERE user_register.id IN ($placeholders)
                  ";
    $user_stmt = mysqli_prepare($con, $user_sql);
    mysqli_stmt_bind_param($user_stmt, $types, ...$user_ids);
    mysqli_stmt_execute($user_stmt);
    $user_result = mysqli_stmt_get_result($user_stmt);

    $users = [];
    while ($user = mysqli_fetch_assoc($user_result)) {
        $users[$user['id']] = [
            'id'     => $user['id'],
            'name'   => $user['name'],
            'mobile' => $user['mobile'],
            'kd_id'  => $user['kd_id'],
        ];
    }

    // 4. Merge bidder info into bids_details
    foreach ($bids_details as $uid => &$bid) {
        $bid['kd_id'] = $users[$uid]['kd_id'] ?? 'N/A';
        $bid['user_id'] = $users[$uid]['id'] ?? 'N/A';
        $bid['name']   = $users[$uid]['name']   ?? 'Unknown';
        $bid['mobile'] = $users[$uid]['mobile'] ?? 'N/A';
    }
    unset($bid); // important

    $response = [
        "type"   => 1,
        "result" => $bids_details
    ];

    echo json_encode($response);
    exit;
} else if ($method == "lead_comment") {
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

                $s_lead = select_query($con, "goride_ad_leads", "", "`id` = '$lead_id' ORDER BY `id` ASC LIMIT 1", "", "");
            
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
            
                $upd_qry = "UPDATE goride_ad_leads SET comments = '$json_data', comments_status = '$com_comment' WHERE id = '$lead_id'";
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
}else if ($method == "lead_details") {
    try {
        $pattern = '/^\d{4}-\d{2}-\d{2}$/';

        $lead_id = $_POST['id'] ?? '';
        
        $filterParts = [];
        $result = [];
        if($lead_id != ''){
            // if ($com_comment != '' || $com_message != '' || $com_dateInput != '') {

                // $s_lead = select_query($con, "goride_ad_leads", "", "`id` = '$lead_id' ORDER BY `id` ASC LIMIT 1", "", "");
                
                $selectQuery = "SELECT * FROM goride_ad_leads gl WHERE id = '$lead_id'";
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
else {
    $result['type'] = 0;
    $result['result'] = 'The Method Not Found!';
    goto resutGJHIP;
}

resutGJHIP:
echo json_encode($result);