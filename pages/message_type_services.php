<?php
include '../../include/shi-config.php';
include '../../include/functions.php';
include '../../include/Crypto.php';
// error_reporting(E_ALL); 
// ini_set('display_errors', 1);

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
$result = array();
$post_csrf = $headers['X-Csrf-Token'] ?? '';


if ($method == 'whatsapp_message') {

    $status = BlockSQLInjection($_POST['status']);
    

    if($status == 1){

    $sql = "UPDATE settings SET mess_type = 'sms' ";
    $result = mysqli_query($con, $sql);

        $response = [
                "type"   => '1',
                "result" => "success" . mysqli_error($con)
            ];

    }

    echo json_encode($response);
    exit;
}

if ($method == 'sms_message') {

    $status = BlockSQLInjection($_POST['status']);
    var_dump($status);die;
    

    if($status == 1){

    $sql = "UPDATE settings SET mess_type = 'sms' ";
    $result = mysqli_query($con, $sql);

        $response = [
                "type"   => '1',
                "result" => "success" . mysqli_error($con)
            ];

    }

    echo json_encode($response);
    exit;
}


if ($method == 'fetch_status') {

    $search = BlockSQLInjection($_POST['search']);

    $sql = "SELECT mess_type FROM settings LIMIT 1";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        $response = [
            "type"   => '0',
            "result" => "Database Error: " . mysqli_error($con)
        ];
    } else {

        if ($row = mysqli_fetch_assoc($result)) {
            $response = [
                "type"   => '1',
                "result" => $row['mess_type']
            ];
        } else {
            $response = [
                "type"   => '0',
                "result" => null
            ];
        }
    }

    echo json_encode($response);
    exit;
}


