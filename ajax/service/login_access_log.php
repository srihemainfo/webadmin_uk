<?php

//  20-10-2023    Divya       con changed to rcon


include '../../include/shi-config.php';



include '../../include/functions.php';



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





if ($method == 'access_log') {

    $result = [];
    $now = date('Y-m-d');
    $agdate = $_POST['agdate'];
    $todate = $_POST['todate'];
    if ($agdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($agdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $da = 'DESC';
        $contype = "`createdon` LIKE '%$now%' AND";
    }

    $error_log_ip = mysqli_query($lcon, "SELECT `ip` FROM error_log WHERE $contype `type` = 'auth_failed_1'  GROUP BY `ip`;");
    $i = 1;
    while ($row = mysqli_fetch_assoc($error_log_ip)) {
        $ip = $row['ip'];
        $get_ip_details = select_query($lcon, "error_log", "", "`ip` = '$ip' AND `type` = 'auth_try' ORDER BY `id` ASC LIMIT 1", "", "");
        if ($get_ip_details['nr'] > 0) {
            $request = json_decode($get_ip_details['result'][0]['request'], true);
            $userID = ($get_ip_details['result'][0]['email'] != '') ? $get_ip_details['result'][0]['email'] : $get_ip_details['result'][0]['mobile'];
            $system_try = '';
            $forgot_check = '';
            $forgot_reason = '';
            $forgot_time = '';
            $auth_success = '';
            $auth_time = '';
            $status = 'open';

            $auth_failed_1 = select_query($lcon, "error_log", "", "`ip` = '$ip' AND `type` = 'auth_failed_1' ORDER BY `id` DESC LIMIT 1", "", "");
            if ($auth_failed_1['nr'] > 0) {
                $system_try = $auth_failed_1['result']['0']['message'];
            }

            $auth_user_found = select_query($lcon, "error_log", "", "`ip` = '$ip' AND `type` = 'auth_user_found' ORDER BY `id` DESC LIMIT 1", "", "");
            if ($auth_user_found['nr'] > 0) {
                $system_try = $auth_user_found['result']['0']['message'];
            }

            $auth_password_failed = select_query($lcon, "error_log", "", "`ip` = '$ip' AND `type` = 'auth_password_failed' ORDER BY `id` DESC LIMIT 1", "", "");
            if ($auth_password_failed['nr'] > 0) {
                $system_try = $auth_password_failed['result']['0']['message'];
            }

            $forgot_password_start  = select_query($lcon, "error_log", "", "`ip` = '$ip' AND `type` = 'forgot_password_start' ORDER BY `id` DESC LIMIT 1", "", "");
            if ($forgot_password_start['nr'] > 0) {
                $forgot_check = $forgot_password_start['result']['0']['message'];
                $forgot_time = date('d-m-y g:i a', strtotime($forgot_password_start['result'][0]['createdon']));
            }

            $forgot_password_user_not_found = select_query($lcon, "error_log", "", "`ip` = '$ip' AND `type` = 'forgot_password_user_not_found' ORDER BY `id` DESC LIMIT 1", "", "");
            if ($forgot_password_user_not_found['nr'] > 0) {
                $forgot_reason = $forgot_password_user_not_found['result']['0']['message'];
            }

            $forgot_password_success  = select_query($lcon, "error_log", "", "`ip` = '$ip' AND `type` = 'forgot_password_success' ORDER BY `id` DESC LIMIT 1", "", "");
            if ($forgot_password_success['nr'] > 0) {
                $forgot_reason = $forgot_password_success['result']['0']['message'];
            }

            $auth_success_1  = select_query($lcon, "error_log", "", "`ip` = '$ip' AND `type` = 'auth_success_1' ORDER BY `id` DESC LIMIT 1", "", "");
            if ($auth_success_1['nr'] > 0) {
                $auth_time  = date('d-m-y g:i a', strtotime($auth_success_1['result'][0]['createdon']));
                $auth_success = $auth_success_1['result']['0']['message'];
                $status = 'closed';
            }

            $result[] = [
                "sno" => $i,
                "execution_time" => '<span style="display: none;">' . strtotime($get_ip_details['result'][0]['createdon']) . '</span>' . date('d-m-y g:i a', strtotime($get_ip_details['result'][0]['createdon'])), "ip" =>  $ip, "type" => $request['verifymethod'], "userdetails" => $userID, "reason" => $get_ip_details['result'][0]['message'],
                "system_try" =>  $system_try, "forgot_check" => $forgot_check,
                "forgot_reason" =>  $forgot_reason, "forgot_time" =>  $forgot_time, "auth_success" => $auth_success, "auth_time" => $auth_time, "status" => strtoupper($status)
            ];
        }
        $i++;
    }

    echo json_encode($result);
}
