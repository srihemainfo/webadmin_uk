<?php


include '../../include/shi-config.php';
include '../../include/functions.php';

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$memid = $_SESSION['memid'];

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";

$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";

$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";

$roleID = $_REQUEST['role'] ?? '';
$role = isset($_REQUEST["role"]) ? "`roll_id` = '$roleID' AND" : "";
$headers = apache_request_headers();



$result = array();

$post_csrf = $headers['X-Csrf-Token'] ?? '';




 if ($method == "list_oticket") {


    $result = [];


    // $formdate = BlockSQLInjectionforagent($_POST["agdate"]);
    // $todate = BlockSQLInjectionforagent($_POST["todate"]);



    $NDwinnerquery = mysqli_query($con, "SELECT * FROM `winnerlist` where `deletes` = '0' ORDER BY  id ASC");

    if (mysqli_num_rows($NDwinnerquery) > 0) {
        $result = mysqli_fetch_all($NDwinnerquery,MYSQLI_ASSOC);
    }


    echo json_encode($result); 
} else if ($method == "delete_NDticket") {

    $result = [];

    $transid = $_POST['transid'];

    $message = $_POST['message'];

    // var_dump($transid,$message);die;


    $inv_arr = array("deletes" => '1', "deleteReason" => $message);
    $ND_update = update($con, "ndticket", "`id` = '$transid'", $inv_arr, "", "", "", "");
    $result = array();

    if ($ND_update) {
        $result["result"] = " Ticket has been Deleted Successfully";
    } else {
        $result["result"] = "Failed to delete ticket";
    }







    echo json_encode($result);
} 
