<?php

use LDAP\Result;

include '../../include/shi-config.php';
include '../../include/functions.php';
include '../../include/payment-config.php';

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$type = isset($_REQUEST["type"]) ? $_REQUEST[type] : "";
$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST[tabID] : "";
if ($type == 'agent') {
    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($_REQUEST[role]) AND" : "";
} else {
    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$_REQUEST[role]' AND" : "";
}

$headers = apache_request_headers();
//print_r($headers);
$result = array();
$post_csrf = $headers['X-Csrf-Token'] ?? '';



if ($method == "payment_log") {
    $result = [];
    $contype = '';
    $type = 'OT';
    $da = '';
    $now = date('Y-m-d');
    $formdate = $_POST['formdate'];
    $todate = $_POST['todate'];
    if ($formdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $da = 'DESC';
        $contype = "`createdon` LIKE '%$now%' AND ";
    }
    if ($_SESSION['memid'] != 1) {
        $agent = "`agent_id` = '$_SESSION[memid]' AND";
    } else {
        $agent = "";
    }

    $Ticket_lines = select_query($con, "orders", "", "`amount`!='0.00' and $contype `id` != ''", "", "");
    // $Ticket_lines = select_query($con, "payment_history", "", "$contype `pay_re_status` != ''", "", "");
    if ($Ticket_lines['nr'] > 0) {
        foreach ($Ticket_lines['result'] as $key => $value) {
            $transaction_id = $value['transaction_id'];

            $data = json_decode($value['response'], true);
            $total = $value['amount'] ;

           // $net_total =  select_top_name($con, "ticket", "net_total", "`transaction_id`='$transaction_id' and `deletes`='0'", "net_total", "");
		   
		 
      $ticket_no =  select_top_name($con, "ticket", "ticket_no", "`transaction_id`='$transaction_id' and `deletes`='0'", "ticket_no", "");
	  
	    if($value[event]=="CAPTURED" && $ticket_no==""){
			$ticket_no = "Generate";
			} else  if($value[event]=="CAPTURED"){
						$ticket_no = $ticket_no; 
			}  else { $ticket_no = "";}
	  
          $user_id =  select_top_name($con, "payment_history", "user_id", "`transaction_id`='$transaction_id' ", "user_id", "");
            $mobile =  select_top_name($con, "user_register", "mobile", "`id`='$user_id' and `deletes`='0'", "mobile", "");
            $email = $value[email];
            $action = '';

      


            $result['result'][] = array("action" => $action, "amt" => $total, "email" => $email, "mobile" => $mobile, "transid" => $transaction_id, "ticketno" => $ticket_no,  "stauts" => $value[event],   "date" => date("d-M-Y g:i a", strtotime($value[createdon])));
        }
        $result['type'] = 1;
    } else {
        $result['type'] = 0;
        $result['result'] = 'No Datas Found!';
    }



    echo json_encode($result);
} 


function identify($apikey, $idUrl)
{
    $idHead = array("Authorization: Basic " . $apikey, "Content-Type: application/vnd.ni-identity.v1+json");
    $idPost   = "";
    $idOutput = invokeCurlRequest("POST", $idUrl, $idHead, $idPost);
    return $idOutput;
}


function callback($token, $url, $orderReference)
{
    $payUrl = $url . $orderReference;
    $payHead = array("Authorization: Bearer " . $token, "Content-Type: application/vnd.ni-payment.v2+json", "Accept: application/vnd.ni-payment.v2+json");
    $payPost = '';

    $payOutput = invokeCurlRequest("GET", $payUrl, $payHead, $payPost, true);
    return $payOutput;
}
