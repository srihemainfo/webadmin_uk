<?php

//   Date               Developer name       Description
//   1-6-2023           Prakash              This page contains affiliate page backend functions


include '../../include/shi-config.php';
include '../../include/functions.php';
include '../../include/payment-config.php';



$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";

$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";

$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";

if ($type == 'agent') {

    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($_REQUEST[role]) AND" : "";
} else {

    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$_REQUEST[role]' AND" : "";
}



$headers = apache_request_headers();

//print_r($headers);

$result = array();

$post_csrf = $headers['X-Csrf-Token'] ?? '';



if ($method == "deleted_affiliate") {

    $result = [];
$userId = BlockSQLInjection($_POST["userid"]);
$reason = BlockSQLInjection($_POST["reason"]);
    // $userId = $_REQUEST['userid'];

    // $reason = $_REQUEST['reason'];

    if ($_SESSION['memid'] == '') {
        $result["type"] = "0";
        $result["result"] = "Login Required!";
        goto fgIV;
    }


    if ($userId == '') {
        $result["type"] = "0";
        $result["result"] = "User Id Not Received.";
        goto fgIV;
    }

    if ($reason == '') {
        $result["type"] = "0";
        $result["result"] = "Please Fill The Reason.";
        goto fgIV;
    }


    $user_register = select_query($con, "user_register", "", "`id` = '$userId'  AND `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");


    if ($user_register['nr'] > 0) {

        $shi_arr = ["deletes" => '1'];

        // User profile activity log Start 
        $get_user_data = select_query($con, "user_register", "", "`id`='$userId' and `deletes`='0'", "", "");
        if ($get_user_data['nr'] > 0) {
            $result_arr = array_intersect_key($get_user_data['result'][0], $shi_arr);
            $log_arr = array_diff($result_arr, $shi_arr);
            $user_profile_log_arr = [
                'user_id' =>  $userId,
                'changed_by' => $_SESSION['memid'],
                'changed_data' => json_encode($log_arr),
                'updated_datetime' => $dubaidate_time,
                'ip' => getUserIP()
            ];
        }
        // User profile activity log End 

        $user_register_update = update($con, "user_register", "`id` = '$userId' and `deletes`='0' and `status` = '0'", $shi_arr, "", "", "", "");
        $errors = $user_register_update['errors'];

        if ($errors != "") {
            $result["type"] = "0";
            $result["result"] = $errors;
        } else {
            $suspended_historyArr = ["userid" => $userId, "reason" => $reason, "submittedby" => $_SESSION['memid'], "createdon" => $dubaidate_time];
            $suspended_history = insert($con, "deleted_log", "", $suspended_historyArr, "", "", "");

            // User profile activity log
            $user_profile_log_ins = insert($con, "user_profile_activity_log", "", $user_profile_log_arr, "", "", "");


            $result["type"] = "1";
            $result["result"] = "Account Deleted.";
            goto fgIV;
        }
    } else {
        $result["type"] = "0";
        $result["result"] = "User Not Found!";
        goto fgIV;
    }

    fgIV:
    echo json_encode($result);
} else if ($method == "suspended_affiliate") {

    $result = [];
    // $userId = $_REQUEST['userid'];
    // $reason = $_REQUEST['reason'];
$userId = BlockSQLInjection($_POST["userid"]);
$reason = BlockSQLInjection($_POST["reason"]);

    if ($_SESSION['memid'] == '') {
        $result["type"] = "0";
        $result["result"] = "Login Required!";
        goto fgIV123;
    }

    if ($userId == '') {
        $result["type"] = "0";
        $result["result"] = "User Id Not Received.";
        goto fgIV123;
    }

    if ($reason == '') {
        $result["type"] = "0";
        $result["result"] = "Please Fill The Reason.";
        goto fgIV123;
    }






    $user_register = select_query($con, "user_register", "", "`id` = '$userId'  AND `status`='0' AND `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
    if ($user_register['nr'] > 0) {


        $shi_arr = ["status" => '1'];

        // User profile activity log Start 
        $get_user_data = select_query($con, "user_register", "", "`id`='$userId' and `deletes`='0'", "", "");
        if ($get_user_data['nr'] > 0) {
            $result_arr = array_intersect_key($get_user_data['result'][0], $shi_arr);
            $log_arr = array_diff($result_arr, $shi_arr);
            $user_profile_log_arr = [
                'user_id' =>  $userId,
                'changed_by' => $_SESSION['memid'],
                'changed_data' => json_encode($log_arr),
                'updated_datetime' => $dubaidate_time,
                'ip' => getUserIP()
            ];
        }
        // User profile activity log End 

        $user_register_update = update($con, "user_register", "`id` = '$userId'  AND `deletes`='0' and `status` = '0'", $shi_arr, "", "", "", "");
        $errors = $user_register_update['errors'];
        if ($errors != "") {
            $result["type"] = "0";
            $result["result"] = $errors;
        } else {



            $suspended_historyArr = ["userid" => $userId, "reason" => $reason, "submittedby" => $_SESSION['memid'], "type" => 'suspended', "createdon" => $dubaidate_time];
            $suspended_history = insert($con, "suspended_log", "", $suspended_historyArr, "", "", "");

            // User profile activity log
            $user_profile_log_ins = insert($con, "user_profile_activity_log", "", $user_profile_log_arr, "", "", "");



            $result["type"] = "1";
            $result["result"] = "Account Suspended.";
            goto fgIV123;
        }
    } else {
        $result["type"] = "0";
        $result["result"] = "User Not Found!";
        goto fgIV123;
    }

    fgIV123:
    echo json_encode($result);
} else if ($method == "unsuspendagent_affiliate") {

    $result = [];
    $userId = BlockSQLInjection($_POST["userid"]);
$reason = BlockSQLInjection($_POST["reason"]);
    // $userId = $_REQUEST['userid'];
    // $reason = $_REQUEST['reason'];

    if ($_SESSION['memid'] == '') {
        $result["type"] = "0";
        $result["result"] = "Login Required!";
        goto fgIV1203;
    }

    if ($userId == '') {
        $result["type"] = "0";
        $result["result"] = "User Id Not Received.";
        goto fgIV1203;
    }

    if ($reason == '') {
        $result["type"] = "0";
        $result["result"] = "Please Fill The Reason.";
        goto fgIV1203;
    }






    $user_register = select_query($con, "user_register", "", "`id` = '$userId'  AND `status`='1' AND `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
    if ($user_register['nr'] > 0) {


        $shi_arr = ["status" => '0'];

        // User profile activity log Start 
        $get_user_data = select_query($con, "user_register", "", "`id`='$userId' and `deletes`='0'", "", "");
        if ($get_user_data['nr'] > 0) {
            $result_arr = array_intersect_key($get_user_data['result'][0], $shi_arr);
            $log_arr = array_diff($result_arr, $shi_arr);
            $user_profile_log_arr = [
                'user_id' =>  $userId,
                'changed_by' => $_SESSION['memid'],
                'changed_data' => json_encode($log_arr),
                'updated_datetime' => $dubaidate_time,
                'ip' => getUserIP()
            ];
        }
        // User profile activity log End 

        $user_register_update = update($con, "user_register", "`id` = '$userId'  AND `deletes`='0' and `status` = '1'", $shi_arr, "", "", "", "");
        $errors = $user_register_update['errors'];
        if ($errors != "") {
            $result["type"] = "0";
            $result["result"] = $errors;
        } else {



            $suspended_historyArr = ["userid" => $userId, "reason" => $reason, "submittedby" => $_SESSION['memid'], "type" => 'suspended', "createdon" => $dubaidate_time];
            $suspended_history = insert($con, "suspended_log", "", $suspended_historyArr, "", "", "");

            // User profile activity log
            $user_profile_log_ins = insert($con, "user_profile_activity_log", "", $user_profile_log_arr, "", "", "");



            $result["type"] = "1";
            $result["result"] = "Account Suspended.";
            goto fgIV1203;
        }
    } else {
        $result["type"] = "0";
        $result["result"] = "User Not Found!";
        goto fgIV1203;
    }

    fgIV1203:
    echo json_encode($result);
}
