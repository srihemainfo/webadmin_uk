<?php

/**
 * Date         Developer_name      Modifications
 * 23-1-2023    subin               changed to decending order in Customer Ticket Line Wise Reports table  (line 104).
 * 25-1-2023    Prakash             Invoice icon disabled by wallet ticket.
 * 13-03-2023   sathiya             wallet ticket invoice added.
 * 23-08-2023   Divya               kiosk ticket shown in customer page. 
 */




include '../../include/shi-config.php';





include '../../include/functions.php';



// error_reporting(E_ALL);
// ini_set('display_errors', 1);


$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";



$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";



$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";



if ($type == 'agent') {

    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($_REQUEST[role]) AND" : "";
} else {

    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$_REQUEST[role]' AND" : "";
}



$headers = apache_request_headers();




$result = array();



$post_csrf = $headers['X-Csrf-Token'] ?? '';


function sortByDate($a, $b)

{

    $dateA = strtotime($a['date']);

    $dateB = strtotime($b['date']);



    if ($dateA == $dateB) {

        return 0;
    }

    return ($dateA < $dateB) ? -1 : 1;
}



if ($method == "Customer_Ticket1") {

    $result = [];
    $type_Con = '';
    $draw_Con = '';

    $user_id = $_REQUEST['user_id'];
    $formdate = $_POST['formdate'];
    $todate = $_POST['todate'];
    $Ticket_lines = select_query($con, 'ticket_lines', "", "`user_id` = '$user_id' AND `deletes`='0' AND `createdon` BETWEEN '$formdate' AND '$todate'", "", "");
    if ($Ticket_lines['nr'] > 0) {

        foreach ($Ticket_lines['result'] as $key => $value) {
            $pro_id = $value['product_id'];
            $proamt =  select_top_name($con, 'product', "rate", "`id`='$pro_id' and `deletes`='0'", "rate", "");
            $draw_id = $value['draw_id'];
            $draw_name = select_top_name($con, "draw", "name", "`id`='$draw_id' and `deletes`='0'  ORDER BY `id` DESC", "name", "");
            $action = '';
            $action .= '<div class="g-2">';
            $ticket_id = $value['ticket_id'];
            if ($value['type'] == 'OT') {

                $transaction_id = select_top_name($con, 'ticket', "transaction_id", "`id`='$ticket_id' and `deletes`='0'", "transaction_id", "");

                $ticket_no = select_top_name($con, 'ticket', "ticket_no", "`id`='$ticket_id' and `deletes`='0'", "ticket_no", "");

                $purchase_datetime =  select_top_name($con, 'ticket', "purchase_datetime", "`id`='$ticket_id' and `deletes`='0'", "purchase_datetime", "");

                $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' . $transaction_id . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span class="fa fa-file-text-o fs-14"></span></a>';
            } else  if ($value['type'] == 'WT') {

                $transaction_id = select_top_name($con, 'wticket', "transaction_id", "`id`='$ticket_id' and `deletes`='0'", "transaction_id", "");

                $ticket_no = select_top_name($con, 'wticket', "ticket_no", "`id`='$ticket_id' and `deletes`='0'", "ticket_no", "");

                $invoice_no = select_top_name($con, 'wticket', "invoice_no", "`id`='$ticket_id' and `deletes`='0'", "invoice_no", "");

                $purchase_datetime =  select_top_name($con, 'wticket', "purchase_datetime", "`id`='$ticket_id' and `deletes`='0'", "purchase_datetime", "");

                if ($invoice_no['result'][0]['invoice_no'] != '' && $invoice_no['result'][0]['invoice_no'] != 0) {

                    $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' . $transaction_id . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span style="font-size: 18px;" class="fa fa-file-text-o"></span></a>';
                }
            } else  if ($value['type'] == 'AT') {

                $transaction_id = select_top_name($con, 'aticket', "transaction_id", "`id`='$ticket_id' and `deletes`='0'", "transaction_id", "");

                $ticket_no = select_top_name($con, 'aticket', "ticket_no", "`id`='$ticket_id' and `deletes`='0'", "ticket_no", "");

                $purchase_datetime =  select_top_name($con, 'aticket', "purchase_datetime", "`id`='$ticket_id' and `deletes`='0'", "purchase_datetime", "");

                $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' . $transaction_id . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span class="fa fa-file-text-o fs-14"></span></a>';
            } else  if ($value['type'] == 'MT') {

                $transaction_id = select_top_name($con, 'mticket', "transaction_id", "`id`='$ticket_id' and `deletes`='0'", "transaction_id", "");

                $ticket_no = select_top_name($con, 'mticket', "ticket_no", "`id`='$ticket_id' and `deletes`='0'", "ticket_no", "");

                $purchase_datetime =  select_top_name($con, 'mticket', "purchase_datetime", "`id`='$ticket_id' and `deletes`='0'", "purchase_datetime", "");

                $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' . $transaction_id . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span class="fa fa-file-text-o fs-14"></span></a>';
            } else  if ($value['type'] == 'FT') {

                $transaction_id = select_top_name($con, 'fticket', "transaction_id", "`id`='$ticket_id' and `deletes`='0'", "transaction_id", "");

                $ticket_no = select_top_name($con, 'fticket', "ticket_no", "`id`='$ticket_id' and `deletes`='0'", "ticket_no", "");

                $purchase_datetime =  select_top_name($con, 'fticket', "purchase_datetime", "`id`='$ticket_id' and `deletes`='0'", "purchase_datetime", "");
            } else  if ($value['type'] == 'CT') {

                $cticket = select_query($con, 'cticket', "", "`id`='$ticket_id' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");

                if ($cticket['nr'] > 0) {

                    $transaction_id = $cticket['result'][0]['transaction_id'];

                    $ticket_no = $cticket['result'][0]['ticket_no'];

                    $purchase_datetime = $cticket['result'][0]['purchase_datetime'];
                }
            } else if ($value['type'] == 'BP') {

                $bpticket = select_query($con, 'bpticket', "", "`id`='$ticket_id' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");

                if ($bpticket['nr'] > 0) {

                    $transaction_id = $bpticket['result'][0]['transaction_id'];

                    $ticket_no = $bpticket['result'][0]['ticket_no'];

                    $purchase_datetime = $bpticket['result'][0]['purchase_datetime'];
                }
            } else if ($value['type'] == 'CP') {

                $cpticket = select_query($con, 'cpticket', "", "`id`='$ticket_id' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");

                if ($cpticket['nr'] > 0) {

                    $transaction_id = $cpticket['result'][0]['transaction_id'];

                    $ticket_no = $cpticket['result'][0]['ticket_no'];

                    $purchase_datetime = $cpticket['result'][0]['purchase_datetime'];

                    $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' . $transaction_id . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span class="fa fa-file-text-o fs-14"></span></a>';
                }
            } else if ($value['type'] == 'KT') {

                $kticket = select_query($con, 'kticket', "", "`id`='$ticket_id' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");

                if ($kticket['nr'] > 0) {

                    $transaction_id = $kticket['result'][0]['transaction_id'];

                    $ticket_no = $kticket['result'][0]['ticket_no'];

                    $purchase_datetime = $kticket['result'][0]['purchase_datetime'];

                    $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' . $transaction_id . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span class="fa fa-file-text-o fs-14"></span></a>';
                }
            }



            $my3number = $value['my3number'];

            $raffle_id = $value['raffle_id'];









            $action .= '<a target="_blank" href="' . $baseurl . 'ticket-view/' . $transaction_id . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span class="fa fa-files-o fs-14"></span></a>';

            $action .= '</div>';





            $result[] = array("drawname" => $draw_name, "action" => $action, "ticketno" => $ticket_no, "my3number" => $my3number, "raffleid" => $raffle_id, "proamt" => $proamt, "purdate" => date("d-M-Y g:i a", strtotime($purchase_datetime)));
        }
    }





    echo json_encode($result);

    
} else if ($method == "Customer_Ticket") {
    
   

    $result = [];
    $type_Con = '';
    $draw_Con = '';

    $user_id = $_REQUEST['user_id'];
    $formdate = $_POST['formdate'];
    $todate = $_POST['todate'];
    
    
     
    // $Ticket_lines = select_query($con, 'ticket_lines', "", "`user_id` = '$user_id' AND `deletes`='0' AND `createdon` BETWEEN '$formdate' AND '$todate'", "", "");
    $Ticket_lines = select_query($con, 'ndticket', "", "`userId` = '$user_id' AND `deletes`='0' AND `createdon` BETWEEN '$formdate' AND '$todate'", "", "");
    if ($Ticket_lines['nr'] > 0) {

        foreach ($Ticket_lines['result'] as $key => $value) {
            
            $ticketid = $value['ticketNo'];
            $raffleIds = $value['raffleIds'];
            $purchasedate = $value['purchaseDatetime'];
            
            $pro_id =  select_top_name($con, 'invoice', "product_id", "`user_id`='$user_id' and `deletes`='0'", "product_id", "1");
            
            $pro_rate =  select_top_name($con, 'product', "rate", "`id`='$pro_id' and `deletes`='0'", "rate", "");
            
            $endDate = $value['endDate'];
            
           
            
            
            
            
            $action = '';
            $action .= '<div class="g-2">';
           
            









            $action .= '<a target="_blank" href="' . $baseurl . 'ticket-view/' . $transaction_id . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span class="fa fa-files-o fs-14"></span></a>';

            $action .= '</div>';





            $result[] = array("ticketid" => $ticketid, "action" => $action, "raffleid" => $raffleIds, "proamt" => $pro_rate, "purdate" => date("d-M-Y g:i a", strtotime($purchasedate)), "endDate" => date("d-M-Y g:i a", strtotime($endDate)));
            
        }
    }



// var_dump($result);die;

    echo json_encode($result);
} 


function getTotalAmount($con, $tableName, $userid)
{
    $query = "SELECT SUM(amount) AS totalAmount FROM $tableName WHERE from_id = $userid AND status = '1' ORDER BY id ASC";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return (int)$row['totalAmount'];
}
