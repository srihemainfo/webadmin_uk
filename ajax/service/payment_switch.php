<?php
/**
 *      Date            Developer_name      Modifications
 *      20-05-2023      Prashant            Payment Enable/Disable Options
 * */
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


if ($method == "changePaymentGateWay") {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $result = [];
    if ($_SESSION['memid'] != '') {
        if ($id != '') {
            if ($status != '') {
                
                $update_arr = ["status" => $status, "changedby" => $_SESSION['memid'], "updatedon" => $dubaidate_time];
                
                // $payment_switch = setupdate($con, "payment_switch", "", $update_arr, "", "", "");
                $payment_switch = setupdate($con, "payment_switch", "`id`='$id'", $update_arr, "", "", "");
                
                if ($payment_switch['errors'] == "") {
                    
                    //$insert_gateway_log = ["gateway_id" => $id, "changed_by" => $_SESSION['memid'], "changed_status" => $status, "createdon" => $dubaidate_time];
                    //$invoice = insert($con, "paymentswitch_log", "", $insert_gateway_log, "", "", "");
                    
                    //echo "INSERT INTO `paymentswitch_log`(`gateway_id`, `changed_by`, `changed_status`, `createdon`) VALUES ('".$id."','".$_SESSION['memid']."','".$status."','".$dubaidate_time."')";
                    mysqli_query($con,"INSERT INTO `paymentswitch_log`(`gateway_id`, `changed_by`, `changed_status`, `createdon`) VALUES ('".$id."','".$_SESSION['memid']."','".$status."','".$dubaidate_time."')");
                    
                    $result["type"] = "1";
                    $result["result"] = 'Payment Gateway Changed!';
                    goto GfResult;
                } else {
                    $result["type"] = "0";
                    $result["result"] = 'Payment Gateway Could not be changed!';
                    goto GfResult;
                }
            } else {
                $result['type'] = '0';
                $result['result'] = 'Kindly Select Payment Gateway!';
                goto GfResult;
            }
        } else {
            $result['type'] = '0';
            $result['result'] = 'Kindly Select Payment Gateway!';
            goto GfResult;
        }
    } else {
        $result['type'] = '0';
        $result['result'] = 'Login First and Try Again!';
        goto GfResult;
    }

    GfResult:
    echo json_encode($result);
}  else if ($method == 'paymentswitchhistory') {
    $result = [];
    $formdate = BlockSQLInjection($_POST["formdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);
    // $formdate = $_POST['formdate'];
    // $todate = $_POST['todate'];
    if ($formdate != '' && $todate != '') {
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "AND `createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' ";
    } else {
        $contype = "";
    }
    $getPaymentLog = select_query($con, "paymentswitch_log", "", "`id` != '' $contype ORDER BY `id` DESC", "", "");
    if ($getPaymentLog['nr'] > 0) {
        foreach ($getPaymentLog['result'] as $key => $value) {

            $username = select_top_name($con, "user_register", "name", "`id`= '$value[changed_by]'", "name", "");
            $gatewayname = select_top_name($con, "payment_switch", "gateway", "`id`= '$value[gateway_id]'", "gateway", "");
            
            if($value['changed_status'] == '0'){
                $status = "Enabled";
            } else {
                $status = "Disabled";
            }
            $result[] = ['srno' => ++$key,'gateway' => $gatewayname,'changedby' => $username,'status' => $status,'createdon' => date("d-M-Y g:i a", strtotime($value['createdon']))];
        }
    }

    echo json_encode($result);
}

