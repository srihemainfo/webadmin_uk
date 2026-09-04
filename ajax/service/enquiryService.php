<?php


include '../../include/shi-config.php';
include '../../include/functions.php';

mysqli_set_charset($con, "utf8mb4");

// Optional but recommended:
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$memid = $_SESSION['memid'];

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";

$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";

$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";

$roleID = $_REQUEST['role'] ?? '';

if ($type == 'agent') {
    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($roleID) AND" : "";
} else {
    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$roleID' AND" : "";
}

$headers = apache_request_headers();



$result = array();

$post_csrf = $headers['X-Csrf-Token'] ?? '';

if ($method == "enquiryList") {
    try {
        $pattern = '/^\d{4}-\d{2}-\d{2}$/';
    
        $startDate = ($_POST['startDate']) ?? '';
        $endDate = ($_POST['endDate']) ?? '';
        $dateFilter = ($_POST['dateFilter']) ?? '';
        $enType = BlockSQLInjection($_POST['enType']) ?? '';
        $enStatus = BlockSQLInjection($_POST['enStatus']) ?? '';
    
        $filterParts = [];
        $result = [];
        if (!empty($dateFilter)) {
    
            if (!empty($startDate) && !empty($endDate)) {
                $filterParts[] = "created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
            }
        }
        
        if (!empty($enType)) {
            $filterParts[] = "type = '$enType'";
        }
        
        if (!empty($enStatus)) {
            $filterParts[] = "status = '$enStatus'";
        }
        
    
    
        $whereClause = '';
        if (!empty($filterParts)) {
            $whereClause = 'WHERE ' . implode(' AND ', $filterParts);
        }
        // var_dump($filterParts);die;
    
        $selectQuery = "
            SELECT * FROM enquiry_list 
            $whereClause 
            ORDER BY created_at DESC
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
    
    echo json_encode($result);exit;
}
