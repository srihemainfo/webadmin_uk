<?php
include '../../include/shi-config.php';
include '../../include/functions.php';

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";
$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";
$role = $_REQUEST['role'] ?? ''; 

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_errors', 1);

if ($type == 'agent') {
    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($role) AND" : "";
} else {
    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$role' AND" : "";
}

$headers = apache_request_headers();
$result = [];

$post_csrf = $headers['X-Csrf-Token'] ?? '';


if ($method === "cabList") {

    try {

        $startDate       = $_POST['startDate'] ?? '';
        $endDate         = $_POST['endDate'] ?? '';
        $dateFilter      = $_POST['dateFilter'] ?? '';
        $verifyType      = $_POST['verifyType'] ?? '';
        $cabTypeFilter   = $_POST['cabtypeFilter'] ?? '';
        $fueltypeFilter  = $_POST['fueltypeFilter'] ?? '';
        $districtFilter  = $_POST['districtFilter'] ?? '';
        // var_dump($districtFilter);die;

        $conditions = [];
        $result = [
            'type'   => 0,
            'result' => []
        ];


        $conditions[] = "user_register.deletes = '0'";


        if (!empty($dateFilter) && !empty($startDate) && !empty($endDate)) {
            $conditions[] = "kyc_details.created_at 
                             BETWEEN '$startDate 00:00:00' 
                             AND '$endDate 23:59:59'";
        }


        if ($verifyType === 'pending') {

            $conditions[] = "user_register.vehicle_verify = '2'";
            $conditions[] = "kyc_details.cab_type IS NULL";

        } 
        else if ($verifyType === 'verified') {

            $conditions[] = "user_register.vehicle_verify = '2'";
            $conditions[] = "kyc_details.cab_type IS NOT NULL";

            if (!empty($cabTypeFilter)) {
                if($cabTypeFilter == 'not'){
                    
                $conditions[] = "kyc_details.cab_type IS NULL";
                }else{
                    
                $conditions[] = "kyc_details.cab_type = '$cabTypeFilter'";
                }
            }

            if (!empty($fueltypeFilter)) {
                if($fueltypeFilter == 'not'){
                    $conditions[] = "user_register.fuel_type IS NULL";
                }else{
                    $conditions[] = "user_register.fuel_type = '$fueltypeFilter'";
                }
            }
            
            if (!empty($districtFilter)) {
                
                if($districtFilter == 'not'){
                    $conditions[] = "user_register.districts_id IS NULL";
                }else{
                    $conditions[] = "user_register.districts_id = '$districtFilter'";
                }
                
            }
        }


        $whereClause = '';
        if (!empty($conditions)) {
            $whereClause = 'WHERE ' . implode(' AND ', $conditions);
        }

  
        $selectQuery = "
           SELECT 
                user_register.id,
                user_register.name,
                user_register.fuel_type,
                user_register.vehicle_verify,
                user_register.address,
                
            
                user_register.districts_id,
                districts.district_name,   
            
                kyc_details.id AS kd_id,
                kyc_details.cab_type,
                kyc_details.created_at,
            
                JSON_UNQUOTE(
                    JSON_EXTRACT(user_register.vehicle_details,'$.vehicle.front_view_image_url')
                ) AS car_image,
            
                JSON_UNQUOTE(
                    JSON_EXTRACT(user_register.vehicle_details,'$.rc_details.response.vehicle_details')
                ) AS car_details,
            
                JSON_UNQUOTE(
                    JSON_EXTRACT(user_register.vehicle_details,'$.rc_details.response.vehicle_details.rc_number')
                ) AS car_no
            
            FROM user_register
            
            LEFT JOIN kyc_details 
                ON user_register.id = kyc_details.user_id
            
            LEFT JOIN districts                  
                ON districts.id = user_register.districts_id
            
            $whereClause;
                    ";

       

        $get_data = $con->query($selectQuery);

        if ($get_data && $get_data->num_rows > 0) {
            $result['type'] = 1;
            while ($row = $get_data->fetch_assoc()) {
                $result['result'][] = $row;
            }
        }

    } catch (Exception $e) {
        $result['type'] = 0;
        $result['result'] = $e->getMessage();
    }

    echo json_encode($result);
    exit;
}
else if ($method == "images_car") {

    try {
        
        $user_id   = $_POST['user_id'] ?? '';
        $offset = $_POST['offset'] ?? '';
        // var_dump($offset);die;
        
        $keys = [
        0 => '$.vehicle.front_view_image_url',
        1 => '$.vehicle.back_view_image_url',
        2 => '$.vehicle.side_view_image_url',
        3 => '$.vehicle.special_features_image_url',
        4 => '$.vehicle.extra_image_1_url'
        ];
        
        if (!isset($keys[$offset])) {
            echo json_encode(["status" => "end"]);
            exit;
        }
        
        $jsonPath = $keys[$offset];
        
    
        $sql = "
            SELECT 
                JSON_UNQUOTE(
                    JSON_EXTRACT(vehicle_details, '$jsonPath')
                ) AS image
            FROM user_register JOIN kyc_details ON user_register.id = kyc_details.user_id 
            WHERE user_register.id = $user_id
            AND user_register.deletes = '0'
            ";
    
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $image = $row['image'] ?? null;
        
    if ($image !== null && $image !== '' && $image !== 'null') {
        echo json_encode([
            "status" => "success",
            "image"  => $image
        ]);
        
    } else {
        echo json_encode([
            "status" => "end"
        ]);
    }

    exit;
        
    } catch (Exception $e) {
        $result['type'] = 0;
        $result['result'] = $e->getMessage();
    }
}
else if ($method == "car_types") {

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
            SELECT cab_type
            FROM kyc_details 
            WHERE  user_id = '$car_id'
            LIMIT 1
        ";
        // var_dump($selectQuery);die;
        
        $get_data = $con->query($selectQuery);
        
        if ($get_data && $get_data->num_rows > 0) {
        
            $updateQuery = "
                UPDATE kyc_details 
                SET cab_type = '$car_type'
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
else if ($method == "tank_types") {

    try {
        
        $ur_id   = $_POST['id'] ?? '';
        $tank_type = $_POST['tank_type'] ?? '';
        // var_dump($tank_type);die;
        
        $result = [];
        
        if ($ur_id == '' || $tank_type == '') {
            echo json_encode([
                'type' => 0,
                'result' => 'Invalid input'
            ]);
            exit;
        }

        $selectQuery = "
            SELECT fuel_type
             FROM user_register 
            WHERE id = '$ur_id'
            LIMIT 1
        ";
        // var_dump($selectQuery);die;
        
        $get_data = $con->query($selectQuery);
        
        if ($get_data && $get_data->num_rows > 0) {
        
            $updateQuery = "
              UPDATE user_register
                SET fuel_type = '$tank_type'
                WHERE id = '$ur_id'
            ";
            // var_dump($updateQuery);die;
        
            if ($con->query($updateQuery)) {
                $result['type'] = 1;
                $result['result'] = 'updated successfully';
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
else if ($method == "district_list") {

    try {
        
        $ur_id   = $_POST['id'] ?? '';
        $districts = $_POST['districts'] ?? '';
        // var_dump($districts);die;
        
        $result = [];
        
        if ($ur_id == '' || $districts == '') {
            echo json_encode([
                'type' => 0,
                'result' => 'Invalid input'
            ]);
            exit;
        }

        $selectQuery = "
            SELECT districts_id
             FROM user_register 
            WHERE id = '$ur_id'
            LIMIT 1
        ";
        // var_dump($selectQuery);die;
        
        $get_data = $con->query($selectQuery);
        
        if ($get_data && $get_data->num_rows > 0) {
        
            $updateQuery = "
             UPDATE user_register
                SET districts_id = '$districts'
                WHERE id = '$ur_id'
            ";
            // var_dump(" UPDATE user_register
            //     SET districts_id = '$districts'
            //     WHERE id = '$ur_id'");die;
        
            if ($con->query($updateQuery)) {
                $result['type'] = 1;
                $result['result'] = 'updated successfully';
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