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

if ($method == 'error_report') {

    $result = [];
    $now = date('Y-m-d');
    $agdate = BlockSQLInjection($_POST["agdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);

    if ($agdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($agdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "`created_at` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $da = 'DESC';
        $contype = "`created_at` LIKE '%$now%' AND";
    }

    $Emaillog = select_query($rcon, "api_error_log", "", " $contype `id`!= '' AND status = '0' AND (url != 'https://www.goride.run/api/user' AND url NOT LIKE '%/api/getChat%') ", "", "");

    if ($Emaillog['nr'] > 0) {

        foreach ($Emaillog['result'] as $key => $value) {

            $result[] = $value;
        }
    }

    echo json_encode($result);
}


if ($method == 'status_change') {

    $result = [];
    $id = $_POST['id'];
  
    $sql = "UPDATE api_error_log SET status = 1 WHERE id = $id";

    $query = mysqli_query($rcon,$sql);

    if ($query) {
        $result['status'] = 'success';
        $result['message'] = 'Status updated successfully';
    } else {
        $result['status'] = 'error';
        $result['message'] = 'Failed to update status';
    }

    echo json_encode($result);
    exit;

   
}

