<?php
include '../../include/shi-config.php';
include '../../include/functions.php';

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";
$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";
$role = $_REQUEST['role'] ?? ''; 

// Enable all error reporting
// error_reporting(E_ALL); 
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

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


if ($method == "cabList") 

    {

    try {
        $pattern = '/^\d{4}-\d{2}-\d{2}$/';
    
        $startDate = ($_POST['startDate']) ?? '';
        $endDate = ($_POST['endDate']) ?? '';
        $dateFilter = ($_POST['dateFilter']) ?? '';
    
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
        $whereClause = '';
        if (!empty($filterParts)) {
            $whereClause = 'WHERE ' . implode(' AND ', $filterParts);
        }
        // var_dump($filterParts);die;
    
        $selectQuery = "
            SELECT user_register.id, user_register.name, JSON_UNQUOTE(JSON_EXTRACT(user_register.vehicle_details,'$.vehicle.front_view_image_url'))
             AS car_image,
            JSON_UNQUOTE(JSON_EXTRACT(user_register.vehicle_details,'$.rc_details.response.vehicle_details')) AS car_details,
             JSON_UNQUOTE(JSON_EXTRACT(user_register.vehicle_details,'$.rc_details.response.vehicle_details.rc_number')) AS car_no
            FROM user_register 
            WHERE
            user_register.vehicle_verify = '2'
            AND user_register.deletes = '0';
        ";
        
    
        $get_data = $con->query($selectQuery);
        //  var_dump($selectQuery);die;
    
        if ($get_data && $get_data->num_rows > 0) {
            $result['type'] = 1;
    
            while ($row = $get_data->fetch_assoc()) {
                
                // $bids = json_decode($row['bids_details'], true);
    
                // $row['bid_count'] = is_array($bids) ? count($bids) : 0;
    
                $result['result'][] = $row;
                // var_dump($result['result']);die;
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
else if ($method == "car_types") 

    {

    try {
        
        $car_id   = $_POST['id'] ?? '';
        $car_type = $_POST['car_type'] ?? '';
        
        $result = [];
        
        if ($car_id == '' || $car_type == '') {
            echo json_encode([
                'type' => 0,
                'result' => 'Invalid input'
            ]);
            exit;
        }

        $selectQuery = "
            SELECT vehicle_type
            FROM kyc_details 
            WHERE  user_id = '$car_id'
            LIMIT 1
        ";
        // var_dump($selectQuery);die;
        
        $get_data = $con->query($selectQuery);
        
        if ($get_data && $get_data->num_rows > 0) {
        
            $updateQuery = "
                UPDATE kyc_details 
                SET vehicle_type = '$car_type'
                WHERE user_id = '$car_id'
            ";
        
            if ($con->query($updateQuery)) {
                $result['type'] = 1;
                $result['result'] = 'Car type updated successfully';
            } else {
                $result['type'] = 0;
                $result['result'] = 'Update failed';
            }
        
        } else {
        
             $result['type'] = 0;
             $result['result'] = 'No records Found!';
        }
        
        echo json_encode($result);
        
    
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
