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


if ($method === "topSpendersList") {
    try {

        $pattern = '/^\d{4}-\d{2}-\d{2}$/';

        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $dateFilter = $_POST['dateFilter'];
        // $drawID = $_POST['drawID'];
        $currencyID = $_POST['currencyID'];

        $valFilter = '';
        $payFliter = '';
        $invFliter = '';
        
        $currencyFLT = '';
        
        if (isset($currencyID) && $currencyID != '') {
            $currencyFLT = " AND s.currency = '$currencyID' ";
        }
        
        
        if (isset($dateFilter) && $dateFilter != '') {
            
            if (!preg_match($pattern, $startDate) || !preg_match($pattern, $endDate)) {
                $result['type'] = 0;
                $result['result'] = 'Please provide valid start and end dates';
                $result['data'] = [];
            }
            
            if (!isset($startDate) || $startDate == '' || $startDate == null || !isset($endDate) || $endDate == '' || $endDate == null) {
                $result['type'] = 0;
                $result['result'] = 'Please provide valid start and end dates';
                $result['data'] = [];
                goto resutGJHIP;
            }
            
            
            
            $payFliter = " AND lp.createdon BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ";
            
            $invFliter = " AND s.createdon BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ";
            
            $valFilter = " AND createdon BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ";
            
            // $valFilter = " AND createdon BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ";
        }
        
        
        // if (isset($drawID) && $drawID != '' && $drawID != null) {
            
        //     $draw = select_query($con, "ll_draw", "", "`deletes` = '0' AND `id` = '$drawID' ORDER BY `result_datetime` ASC LIMIT 1", "", "");
        //     if ($draw['nr'] < 1) {
            //         $result['type'] = 0;
            //         $result['result'] = 'Active Draw Not Found!';
            //         $result['data'] = [];
            //         goto resutGJHIP;
            //     }
            
            
            
            //     $startDate = $draw['result'][0]['ticket_start_datetime'];
            
            //     $endDate = $draw['result'][0]['ticket_end_datetime'];
            
            
            
            //     $payFliter = " AND lp.createdon BETWEEN '$startDate' AND '$endDate' ";
            
            //     $invFliter = " AND i.createdon BETWEEN '$startDate' AND '$endDate' ";
            
            //     $valFilter = " AND createdon BETWEEN '$startDate' AND '$endDate' ";
            // }
            


            
            


            
        $selectQuery = "SELECT s.* , u.name, u.email, u.mobile FROM `subscriptions` s LEFT JOIN user_register u ON s.user_id = u.id WHERE  s.id != '' $invFliter $currencyFLT ORDER BY `id` DESC;";

        
        
        
        $query = mysqli_query($con, $selectQuery);
        // var_dump($selectQuery);
        // die;
        if ($query && mysqli_num_rows($query) > 0) {
            $result['type'] = 1;
            $result['result'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        } else {
            $result['type'] = 0;
            $result['result'] = [];
            goto resutGJHIP;
        }
    } catch (Exception $e) {
        $result['type'] = 0;
        $result['result'] = $e->getMessage();
    }
} else if ($method === "CRMLsitReprot") {
    try {
        $pattern = '/^\d{4}-\d{2}-\d{2}$/';

        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $dateFilter = $_POST['dateFilter'];
        // $drawID = $_POST['drawID'];
        // $currencyID = $_POST['currencyID'];
        $searchTxt = $_POST['searchTxt'];
        $statusVal = $_POST['statusVal'];
        $statusPlan = $_POST['statusPlan'];
        $utm_source = $_POST['utm_source'];
        $utm_campaign = $_POST['utm_campaign'];


        $valFilter = '';
        $payFliter = '';
        $invFliter = '';
        $seachFLT = '';
        $statusValFLT = '';
        $planFLT = '';
        $planSource = '';
        $planCampaign = '';


        if (isset($searchTxt) && $searchTxt != '') {
            $seachFLT = " AND ( c.subDomainName LIKE '%$searchTxt%' OR u.name LIKE '%$searchTxt%' OR u.email LIKE '%$searchTxt%' OR u.mobile LIKE '%$searchTxt%' ) ";
        }

        if (isset($statusVal) && $statusVal != '') {
            $statusValFLT = " AND s.sub_status LIKE '$statusVal' ";
        }

        if (isset($statusPlan) && $statusPlan != '') {
            $planFLT = " AND s.productName LIKE '$statusPlan' ";
        }
        
        if (isset($utm_source) && $utm_source != '') {
            $planSource = " AND s.utm_source LIKE '$utm_source' ";
        }
        if (isset($utm_campaign) && $utm_campaign != '') {
            $planCampaign = " AND s.utm_source LIKE '$utm_campaign' ";
        }



        if (isset($dateFilter) && $dateFilter != '') {

            if (!preg_match($pattern, $startDate) || !preg_match($pattern, $endDate)) {
                $result['type'] = 0;
                $result['result'] = 'Please provide valid start and end dates';
                $result['data'] = [];
            }

            if (!isset($startDate) || $startDate == '' || $startDate == null || !isset($endDate) || $endDate == '' || $endDate == null) {
                $result['type'] = 0;
                $result['result'] = 'Please provide valid start and end dates';
                $result['data'] = [];
                goto resutGJHIP;
            }

            $invFliter = " AND c.createdon BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ";
        }

        $selectQuery = "SELECT 
                        c.*, s.expiryDate, 
                        s.planType, s.checkout_response,
                        s.sub_status AS subStatus, u.name, 
                        u.email, u.mobile, s.productName, 
                        s.subscription_id AS subScriptionID 
                        FROM crm AS c 
                        JOIN 
                        (SELECT *, JSON_UNQUOTE(JSON_EXTRACT(checkout_response, '$.productDetails.name')) AS productName FROM `subscriptions` 
                        ORDER BY `id` DESC) AS s ON s.id = c.subscription_id 
                        JOIN user_register u ON u.id = s.user_id 
                        WHERE c.deletes = '0' $seachFLT $invFliter $planFLT $statusValFLT $planSource $planCampaign ORDER BY createdon DESC;";
        
        $domainname = "SELECT fullDomain FROM crm WHERE deletes = '0' AND fullDomain IS NOT NULL";
        $query1 = mysqli_query($con, $domainname);
        
        $fullDomain = [];  
        if ($query1 && mysqli_num_rows($query1) > 0) {
            while ($row = mysqli_fetch_assoc($query1)) {
                $fullDomain[] = $row['fullDomain'];
            }
        }
        
        
        if ($fullDomain) {

            // var_dump($fullDomain);die;
            $url = 'https://apidemo.goride.run/shi-v1/partnerlist';
        
            $token = 'your-secret-token-here';
          
            $headers = [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ];
            
            $postData = json_encode($fullDomain);
        
            $response = invokeCurlRequest2('POST', $url, $headers, $postData);
        
            
            // var_dump($fullDomain);die;
            // var_dump($response);
        }

        $apiData = json_decode($response, true);
        $apiDataMapped = $apiData; 
        // var_dump($apiDataMapped);

            $query = mysqli_query($con, $selectQuery);
            if ($query && mysqli_num_rows($query) > 0) {
                $result['type'] = 1;
                $result['result'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
                $result['api_response'] = $apiDataMapped;

                // var_dump($apiDataMapped); 
                // Now, map the api_response to the result based on fullDomain
                foreach ($result['result'] as $key => $item) {
                    // Map fullDomain to the api response data
                    if($item['fullDomain']){
                        
                        $result['result'][$key]['emailsetting_count']  =  $apiDataMapped['data'][$item['fullDomain']]['emailsetting_count'];
                        $result['result'][$key]['gentral_setting_count']  =  $apiDataMapped['data'][$item['fullDomain']]['gentral_setting_count'];
                        $result['result'][$key]['bookingsetting_count']  =  $apiDataMapped['data'][$item['fullDomain']]['bookingsetting_count'];
                        $result['result'][$key]['vehicle_count']  =  $apiDataMapped['data'][$item['fullDomain']]['vehicle_count'];
                        //   var_dump($item['vehicle_count']);die;
                    }
                }
            } else {
                $result['type'] = 0;
                $result['result'] = [];
                goto resutGJHIP;
            }
        
        // var_dump($fullDomain); 
        // $result['api_response'] = $response;

        // $query = mysqli_query($con, $selectQuery);
        // if ($query && mysqli_num_rows($query) > 0) {
        //     $result['type'] = 1;
        //     $result['result'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        // } else {
        //     $result['type'] = 0;
        //     $result['result'] = [];
        //     goto resutGJHIP;
        // }
    } catch (Exception $e) {
        $result['type'] = 0;
        $result['result'] = $e->getMessage();
    }
} else if ($method === "LeadsReport") {
    
    try {
        $pattern = '/^\d{4}-\d{2}-\d{2}$/';

        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $dateFilter = $_POST['dateFilter'];
        // $drawID = $_POST['drawID'];
        // $currencyID = $_POST['currencyID'];
        $searchTxt = $_POST['searchTxt'];
        $statusVal = $_POST['statusVal'];
        $statusPlan = $_POST['statusPlan'];
        $utm_source = $_POST['utm_source'];
        $utm_campaign = $_POST['utm_campaign'];


        $valFilter = '';
        $payFliter = '';
        $invFliter = '';
        $seachFLT = '';
        $statusValFLT = '';
        $planFLT = '';
        $planSource = '';
        $planCampaign = '';


        if (isset($searchTxt) && $searchTxt != '') {
            $seachFLT = " AND ( c.subDomainName LIKE '%$searchTxt%' OR u.name LIKE '%$searchTxt%' OR u.email LIKE '%$searchTxt%' OR u.mobile LIKE '%$searchTxt%' ) ";
        }

        if (isset($statusVal) && $statusVal != '') {
            $statusValFLT = " AND s.sub_status LIKE '$statusVal' ";
        }

        if (isset($statusPlan) && $statusPlan != '') {
            $planFLT = " AND s.productName LIKE '$statusPlan' ";
        }
        
        if (isset($utm_source) && $utm_source != '') {
            $planSource = " AND s.utm_source LIKE '$utm_source' ";
        }
        if (isset($utm_campaign) && $utm_campaign != '') {
            $planCampaign = " AND s.utm_source LIKE '$utm_campaign' ";
        }



        if (isset($dateFilter) && $dateFilter != '') {

            if (!preg_match($pattern, $startDate) || !preg_match($pattern, $endDate)) {
                $result['type'] = 0;
                $result['result'] = 'Please provide valid start and end dates';
                $result['data'] = [];
            }

            if (!isset($startDate) || $startDate == '' || $startDate == null || !isset($endDate) || $endDate == '' || $endDate == null) {
                $result['type'] = 0;
                $result['result'] = 'Please provide valid start and end dates';
                $result['data'] = [];
                goto resutGJHIP;
            }

            $invFliter = " AND c.createdon BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ";
        }

        $selectQuery = "SELECT 
                        c.*, s.expiryDate, 
                        s.planType, s.checkout_response,
                        s.sub_status AS subStatus, u.name, 
                        u.email, u.mobile, s.productName, 
                        s.subscription_id AS subScriptionID 
                        FROM crm AS c 
                        JOIN 
                        (SELECT *, JSON_UNQUOTE(JSON_EXTRACT(checkout_response, '$.productDetails.name')) AS productName FROM `subscriptions` 
                        ORDER BY `id` DESC) AS s ON s.id = c.subscription_id 
                        JOIN user_register u ON u.id = s.user_id 
                        WHERE c.deletes = '0' $seachFLT $invFliter $planFLT $statusValFLT $planSource $planCampaign;";
        
        $domainname = "SELECT fullDomain FROM crm WHERE deletes = '0' AND fullDomain IS NOT NULL";
        $query1 = mysqli_query($con, $domainname);
        
        $fullDomain = [];  
        if ($query1 && mysqli_num_rows($query1) > 0) {
            while ($row = mysqli_fetch_assoc($query1)) {
                $fullDomain[] = $row['fullDomain'];
            }
        }
        
        
        if ($fullDomain) {

            // var_dump($fullDomain);die;
            $url = 'https://apidemo.goride.run/shi-v1/partnerlist';
        
            $token = 'your-secret-token-here';
          
            $headers = [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ];
            
            $postData = json_encode($fullDomain);
        
            $response = invokeCurlRequest2('POST', $url, $headers, $postData);
        
            
            // var_dump($fullDomain);die;
            // var_dump($response);
        }

        $apiData = json_decode($response, true);
        $apiDataMapped = $apiData; 
        // var_dump($apiDataMapped);

            $query = mysqli_query($con, $selectQuery);
            if ($query && mysqli_num_rows($query) > 0) {
                $result['type'] = 1;
                $result['result'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
                $result['api_response'] = $apiDataMapped;

                // var_dump($apiDataMapped); 
                // Now, map the api_response to the result based on fullDomain
                foreach ($result['result'] as $key => $item) {
                    // Map fullDomain to the api response data
                    if($item['fullDomain']){
                        
                        $result['result'][$key]['emailsetting_count']  =  $apiDataMapped['data'][$item['fullDomain']]['emailsetting_count'];
                        $result['result'][$key]['gentral_setting_count']  =  $apiDataMapped['data'][$item['fullDomain']]['gentral_setting_count'];
                        $result['result'][$key]['bookingsetting_count']  =  $apiDataMapped['data'][$item['fullDomain']]['bookingsetting_count'];
                        $result['result'][$key]['vehicle_count']  =  $apiDataMapped['data'][$item['fullDomain']]['vehicle_count'];
                        //   var_dump($item['vehicle_count']);die;
                    }
                }
            } else {
                $result['type'] = 0;
                $result['result'] = [];
                goto resutGJHIP;
            }
        
        // var_dump($fullDomain); 
        // $result['api_response'] = $response;

        // $query = mysqli_query($con, $selectQuery);
        // if ($query && mysqli_num_rows($query) > 0) {
        //     $result['type'] = 1;
        //     $result['result'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        // } else {
        //     $result['type'] = 0;
        //     $result['result'] = [];
        //     goto resutGJHIP;
        // }
    } catch (Exception $e) {
        $result['type'] = 0;
        $result['result'] = $e->getMessage();
    }
} else if ($method === "GoRideLeadsReprot") {
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
            FROM goride_ad_leads gl
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
}else if ($method === "lead_comment") {
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
}else if ($method === "lead_details") {
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


// else if ($method === "walletEOD") {
//     try {

//         $pattern = '/^\d{4}-\d{2}-\d{2}$/';

//         $startDate = $_POST['startDate'];
//         $endDate = $_POST['endDate'];
//         $dateFilter = $_POST['dateFilter'];
//         $drawID = $_POST['drawID'];

//         $payFliter = '';
//         $invFliter = '';

//         if (isset($dateFilter) && $dateFilter != '') {

//             if (!preg_match($pattern, $startDate) || !preg_match($pattern, $endDate)) {
//                 $result['type'] = 0;
//                 $result['result'] = 'Please provide valid start and end dates';
//                 $result['data'] = [];
//             }

//             if (!isset($startDate) || $startDate == '' || $startDate == null || !isset($endDate) || $endDate == '' || $endDate == null) {
//                 $result['type'] = 0;
//                 $result['result'] = 'Please provide valid start and end dates';
//                 $result['data'] = [];
//                 goto resutGJHIP;
//             }



//             $payFliter = " AND startTime BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ";

//             // $invFliter = " AND i.createdon BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ";

//         }


//         // if (isset($drawID) && $drawID != '' && $drawID != null) {

//         //     $draw = select_query($con, "ll_draw", "", "`deletes` = '0' AND `id` = '$drawID' ORDER BY `result_datetime` ASC LIMIT 1", "", "");
//         //     if ($draw['nr'] < 1) {
//         //         $result['type'] = 0;
//         //         $result['result'] = 'Active Draw Not Found!';
//         //         $result['data'] = [];
//         //         goto resutGJHIP;
//         //     }



//         //     $startDate = $draw['result'][0]['ticket_start_datetime'];

//         //     $endDate = $draw['result'][0]['ticket_end_datetime'];
//         //     $payFliter = " AND lp.createdon BETWEEN '$startDate' AND '$endDate' ";

//         //     $invFliter = " AND i.createdon BETWEEN '$startDate' AND '$endDate' ";

//         // }


//         $selectQuery = "SELECT * FROM `wallet_eod` WHERE reportStatus = 'YES' AND deletes = '0'  $payFliter;";


//         // var_dump($selectQuery);
//         // die;

//         $query = mysqli_query($con, $selectQuery);
//         if ($query && mysqli_num_rows($query) > 0) {
//             $result['type'] = 1;
//             $result['result'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
//         } else {
//             $result['type'] = 0;
//             $result['result'] = [];
//             goto resutGJHIP;
//         }
//     } catch (Exception $e) {
//         $result['type'] = 0;
//         $result['result'] = $e->getMessage();
//     }
// } else if ($method === "walletEODDAY") {
//     try {

//         $pattern = '/^\d{4}-\d{2}-\d{2}$/';

//         $startDate = $_POST['startDate'];
//         $endDate = $_POST['endDate'];
//         $dateFilter = $_POST['dateFilter'];
//         $drawID = $_POST['drawID'];

//         $payFliter = '';
//         $invFliter = '';

//         if (isset($dateFilter) && $dateFilter != '') {

//             if (!preg_match($pattern, $startDate) || !preg_match($pattern, $endDate)) {
//                 $result['type'] = 0;
//                 $result['result'] = 'Please provide valid start and end dates';
//                 $result['data'] = [];
//             }

//             if (!isset($startDate) || $startDate == '' || $startDate == null || !isset($endDate) || $endDate == '' || $endDate == null) {
//                 $result['type'] = 0;
//                 $result['result'] = 'Please provide valid start and end dates';
//                 $result['data'] = [];
//                 goto resutGJHIP;
//             }



//             $payFliter = " AND startTime BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ";

//             // $invFliter = " AND i.createdon BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ";

//         }


//         // if (isset($drawID) && $drawID != '' && $drawID != null) {

//         //     $draw = select_query($con, "ll_draw", "", "`deletes` = '0' AND `id` = '$drawID' ORDER BY `result_datetime` ASC LIMIT 1", "", "");
//         //     if ($draw['nr'] < 1) {
//         //         $result['type'] = 0;
//         //         $result['result'] = 'Active Draw Not Found!';
//         //         $result['data'] = [];
//         //         goto resutGJHIP;
//         //     }



//         //     $startDate = $draw['result'][0]['ticket_start_datetime'];

//         //     $endDate = $draw['result'][0]['ticket_end_datetime'];
//         //     $payFliter = " AND lp.createdon BETWEEN '$startDate' AND '$endDate' ";

//         //     $invFliter = " AND i.createdon BETWEEN '$startDate' AND '$endDate' ";

//         // }


//         $selectQuery = "SELECT * FROM `wallet_eod_day` WHERE reportStatus = 'YES' AND deletes = '0'  $payFliter;";


//         // var_dump($selectQuery);
//         // die;

//         $query = mysqli_query($con, $selectQuery);
//         if ($query && mysqli_num_rows($query) > 0) {
//             $result['type'] = 1;
//             $result['result'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
//         } else {
//             $result['type'] = 0;
//             $result['result'] = [];
//             goto resutGJHIP;
//         }
//     } catch (Exception $e) {
//         $result['type'] = 0;
//         $result['result'] = $e->getMessage();
//     }
// } 
else {
    $result['type'] = 0;
    $result['result'] = 'The Method Not Found!';
    goto resutGJHIP;
}

resutGJHIP:
echo json_encode($result);
