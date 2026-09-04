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
                       WHEN ur.mobile IS NOT NULL THEN 1
                       ELSE 0 
                   END AS register_status
            FROM goride_agency_ad_leads gl
            LEFT JOIN user_register ur ON gl.phone = ur.mobile
            $whereClause
            ORDER BY gl.created_at DESC
        ";
    
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
}else if ($method === "lead_details") {
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
}

resutGJHIP:
echo json_encode($result);
