<?php

/**
 * Date              Developer                     Modification
 */




include '../../include/shi-config.php';



include '../../include/functions.php';
include '../../include/payment-config.php';
include '../../include/Crypto.php';



$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
//$drawid=isset($_REQUEST["draw_new_id"]) ?$_REQUEST["draw_new_id"] : "";


class overall
{
    public $con;
    public $dubaidate_time;
    function __construct($con, $dubaidate_time)
    {
        $this->con = $con;
        $this->dubaidate_time = $dubaidate_time;
    }


    function getDeletedTicket($drawid)
    {
        //$tableNames = ['ticket', 'aticket', 'fticket', 'mticket', 'wticket', 'cticket', 'cpticket', 'bpticket','ticket_lines'];
        $tableNames = ['ticket_lines'];
        $total = 0;

        foreach ($tableNames as $tableName) {
            $query = "SELECT COUNT(id) AS total FROM $tableName WHERE draw_id = '$drawid'AND createdon = '$formdate' AND deletes = '1'";
            $result = mysqli_query($this->con, $query);
            $row = mysqli_fetch_assoc($result);
            $total += (int)$row['total'];
        }

        return $total;
    }




    function getTicketBaseSales($drawid,$fromdate,$todate)
    {
        // echo $drawid;
        // echo $fromdate;
        // echo $todate;
        // exit;
        
        $data = [];
        $roll_id = select_top_name($this->con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC ", "roll_id", "");
        $n_roll_id = intval($roll_id) + 1;


        $user = select_query($this->con, "user_register", "", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC", "", "");

        $totalagent = select_query($this->con, "user_register", "", "`created_by`='$_SESSION[memid]' and `roll_id` = '$n_roll_id' and `deletes`='0'  order by `id` DESC", "", "");

        $product = select_query($this->con, "product", "", "`deletes`='0'", "", "");


        $aticket = 0;
        $mticket = 0;
        $oticket = 0;


        $total_amt = '';
        $qcon = '';



        if ($_SESSION['memid'] != 1) {
            $qcon = "`agent_id` = '$_SESSION[memid]' AND";
        } else {
            $qcon = "`agent_id` != '' AND";
        }

        //   $drawid = $draw['result'][0]['id'];
        
      $date_from =date("d-m-y",strtotime($formdate));
            
        if ($drawid != '') {
            
            $drawcon = "`draw_id` = '$drawid' AND";
            
        
        } else if($fromdate!="" && $todate!=""){
            
            $drawcon = "(`createdon` BETWEEN '$fromdate 00:00:00' AND '$todate 23:59:59')  AND";
            
            
        }
        else {
            $drawcon = "";
        }
        
        
        /*if ($drawid =='1') {
            $drawcon = "`draw_id` = '$drawid' AND";
        } else if($drawid =='2'){
            $drawcon = "`draw_id` = '$drawid' AND";
        }else if ($drawid =='3')
        {
            $drawcon = "`draw_id` = '$drawid' AND";
        }
        else{
            $drawcon = "";
        }*/

        $output = '';
        $total_Aed_Array = [];

        /////////// Online Ticket ///////////////
    

        if ($roll_id == 1) {
            //  $oticket = mysqli_query($this->con, "SELECT sum(net_total) as 'total' FROM ticket INNER JOIN invoice ON invoice.id = ticket.invoice_no WHERE ticket.draw_id = '$drawid' AND invoice.response != 'wallet' AND ticket.deletes = '0'");
            // $row1 = mysqli_fetch_array($oticket);

            // $data[] = ['Online (' . intval($row1['total']) . ')' => intval($row1['total'])];

            // $wticket = mysqli_query($this->con, "SELECT SUM(net_total) as 'total' FROM `wticket` WHERE deletes = 0 AND draw_id = '$drawid'");
            //$row2 = mysqli_fetch_array($wticket);
            // $data[] = ['Wallet (' . intval($row2['total']) . ')' => intval($row2['total'])];
          
           
        
           $req_fdate=$_REQUEST['formdate'];
            $date_froms =date("Y-m-d",strtotime($req_fdate));
            
            //   print_r($date_from);exit();
            
            $oticket = mysqli_query($this->con, "SELECT COUNT(product_id) as 'total1' 
            FROM ticket_lines WHERE $drawcon  product_id = '1' 
            AND ticket_lines.deletes = '0'");
            $row1 = mysqli_fetch_array($oticket);
            
            // echo "SELECT COUNT(product_id) as 'total1' 
            // FROM ticket_lines WHERE $drawcon  product_id = '1' 
            // AND ticket_lines.deletes = '0'";
            
            // exit;
            

            $data[] = ['AED 10 (' . intval($row1['total1']) . ')' => intval($row1['total1'])];
        }

        if ($roll_id == 1) {
            $aed20 = mysqli_query($this->con, "SELECT COUNT(product_id) as 'total0' FROM ticket_lines  WHERE $drawcon  product_id = '2' AND ticket_lines.deletes = '0'");
            $row1eqwreq = mysqli_fetch_array($aed20);

            $data[] = ['AED 20 (' . intval($row1eqwreq['total0']) . ')' => intval($row1eqwreq['total0'])];
        }

        if ($roll_id == 1) {
            $aed50 = mysqli_query($this->con, "SELECT COUNT(product_id) as 'total3' FROM ticket_lines  WHERE $drawcon  product_id = '3' AND ticket_lines.deletes = '0'");
            $row1 = mysqli_fetch_array($aed50);

            $data[] = ['AED 50 (' . intval($row1['total3']) . ')' => intval($row1['total3'])];
        }

        if ($roll_id == 1) {
            $aed100 = mysqli_query($this->con, "SELECT COUNT(product_id) as 'total4' FROM ticket_lines  WHERE $drawcon  product_id = '4' AND ticket_lines.deletes = '0'");
            $row1 = mysqli_fetch_array($aed100);

            $data[] = ['AED 100 (' . intval($row1['total4']) . ')' => intval($row1['total4'])];
        }



    

        return $data;
    }



    function getLastNDays($days, $format = 'Y-m-d')
    {
        $m = date("m");
        $de = date("d");
        $y = date("Y");
        $dateArray = array();
        for ($i = 0; $i <= $days - 1; $i++) {
            $dateArray[] =  date($format, mktime(0, 0, 0, ($de - $i),$m,  $y));
        }
        return array_reverse($dateArray);
    }
}

$overall = new overall($con, $dubaidate_time);

if ($method == "ticketsaleschart") {
    $result = [];
    $formdate = BlockSQLInjection($_POST["formdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);
//  $formdate = $_REQUEST['formdate'];
//  $todate = $_REQUEST['todate'];
 
    $draw = select_query($con, "draw", "", "`deletes`='0' and `status`='Active' and `ticket_start_datetime`< '$dubaidate_time' and `ticket_end_datetime`>'$dubaidate_time'   order by `id` ASC", "", "");

    $drawid = $draw['result'][0]['id'];
    
    // print_r($_REQUEST);exit();
    
 if ($_REQUEST['draw_new_id'] != '') { 
    $drawid = BlockSQLInjectionforagent($_REQUEST['draw_new_id']);   
    $formdate = 0;   
    $todate = 0; 
} else { 
    if ($_REQUEST['formdate'] != '') {  
        $formdate = BlockSQLInjectionforagent($_REQUEST['formdate']); 
    } else { 
        $formdate = 0; 
    }
    if ($_REQUEST['todate'] != '') {  
        $todate = BlockSQLInjectionforagent($_REQUEST['todate']); 
    } else { 
        $todate = 0; 
    }
    
    $drawid = 0; 
}
         
        
   
//          $formdate = $_REQUEST['formdate'];
//  $todate = $_REQUEST['todate'];
//   }
    
    
    // if ($_REQUEST['formdate'] != '') {
    //     $drawid12 = $_REQUEST['formdate'];
        
    // }
    //  if ($_REQUEST['todate'] != '') {
    //     $drawid23 = $_REQUEST['todate'];
        
    // }

    $result['result'] = $overall->getTicketBaseSales($drawid,$formdate,$todate);

    if ($_SESSION['memid'] != '') {
        $result['type'] = 1;
        // $result['output'] = $drawout . $output;
    } else {
        $result['type'] = 0;
        $result['result'] = '<div class="alert alert-danger" role="alert">Could not get data!</div>';
    }

    echo json_encode($result);
}





else  if ($method == "getErrorCount") {
    $result = [];

    $result['type'] = 1;
    // $time0 = strtotime($dubaidate_time);
    $fd = date('Y-m-d H:i:s', strtotime('-1 day', strtotime($dubaidate_time)));
    $td = date('Y-m-d H:i:s', strtotime('-10 minutes', strtotime($dubaidate_time)));


    // Network
    $Network = select_query($con, "payment_history", "", "`gateway` = 'network' AND (`pay_re_status` = 'CAPTURED' OR `pay_re_status` IS null) AND  `cron_check_status` = '0'  AND  `reference` != '' AND `status` = '0'  AND `createdon` BETWEEN '$fd' AND '$td'  ORDER BY `id` ASC", "", "");
    $result['result']['network'] =  $Network['nr'];

    // CCAvenue 
    $CCAvenue = select_query($con, "payment_history", "", "`gateway` = 'ccavenue' AND `status` = '0'  AND  `reference` != ''  AND  `cron_check_status` = '0' AND `createdon` BETWEEN '$fd' AND '$td'  ORDER BY `id` ASC", "", "");
    $result['result']['ccavenue'] =  $CCAvenue['nr'];



    // SMS 
    $smslog = select_query($con, "smslog", "", "`smssendstatus` = '0' AND `mobile` != '' AND `details` != '' ORDER BY `id` ASC", "", "");
    $result['result']['smslog'] =  $smslog['nr'];

    // Deleted Ticket
    $result['result']['deletedTicket'] =    $overall->getDeletedTicket($draw_id);

    // CC Missing Tickcet
    $ccorder_lookup = select_query($con, "ccorder_lookup", "", "`order_no` NOT IN (SELECT `transaction_id` FROM `ticket` WHERE `deletes` = '0' AND `draw_id` = '$draw_id') AND `ticket` = 'NO' AND order_status = 'Shipped' ORDER BY `id` DESC", "", "");
    $result['result']['ccMissingT'] =  intval($ccorder_lookup['nr']);



    // Last Draw Email
    $draw_comp = select_query($con, "draw", "", "`status` ='Completed'  AND `deletes` ='0' ORDER BY `id` DESC LIMIT  1", "", "");
    $completed_draw = $draw_comp['result']['0']['id'];
    $email_sentwin = select_query($con, "winnerlist", "", "`emaillog` = '1' AND `draw_id` ='$completed_draw' ORDER BY `winnerlist`.`draw_id` DESC", "", "");
    // Last Draw SMS
    $sms_sentwin = select_query($con, "winnerlist", "", "`smslog` = '1' AND `draw_id` ='$completed_draw' ORDER BY `winnerlist`.`draw_id` DESC", "", "");




    $result['result']['lastDrawemail'] =  intval($email_sentwin['nr']);

    $result['result']['lastDrawsms'] =  intval($sms_sentwin['nr']);


    $deleted_req_count = select_query($con, "user_delete_request", "", "`status` = '0'", "", "");
    $result['result']['deleted_req_count'] =  intval($deleted_req_count['nr']);

    echo json_encode($result);
} else if ($method == "getDrawTotal") {
    $result = [];

    $current = select_query($con, "draw", "id,name", "`deletes`='0' and `status`='Active' and `ticket_start_datetime`< '$dubaidate_time' and `ticket_end_datetime` > '$dubaidate_time'   order by `id` ASC LIMIT 1", "", "");
    $result['currentDraw'] = $overall->getTicketBaseSales($current['result'][0]['id']);
    $result['currentDrawName'] = $current['result'][0]['name'];



    $drawlast = select_query($con, "draw", "id,name", "`status` LIKE 'Completed' AND `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
    $result['pastDraw'] = $overall->getTicketBaseSales($drawlast['result'][0]['id']);
    $result['pastDrawName'] = $drawlast['result'][0]['name'];




    if ($_SESSION['memid'] != '') {
        $result['type'] = 1;
    } else {
        $result['type'] = 0;
        $result['result'] = '<div class="alert alert-danger" role="alert">Could not get data!</div>';
    }

    echo json_encode($result);
} else if ($method == 'lastoneWeekSignUP') {
    $result = [];

    $last7 = $overall->getLastNDays(7);
    $result['type'] = 1;

    $data = [];
    foreach ($overall->getLastNDays(7) as $key) {
        $pre = date('M d', strtotime($key));
        $data['days'][] = $pre;
        $signUp = mysqli_query($con, "select COUNT(`id`) AS 'count' from user_register where `roll_id` = '0' and `deletes`='0' AND `status` = '0'  AND `created_at` BETWEEN '$key 00:00:00' AND '$key 23:59:59' ORDER BY `id` DESC;");
        $row = mysqli_fetch_assoc($signUp);

        $data['Signup'][] = $row['count'];


        $signIn = mysqli_query($con, "select COUNT(`id`) AS 'count' from user_register where `roll_id` = '0' and `deletes`='0' AND `status` = '0' AND `lastlogin` BETWEEN '$key 00:00:00' AND '$key 23:59:59' ORDER BY `id` DESC;");
        $row1 = mysqli_fetch_assoc($signIn);
        $data['Signin'][]  =   $row1['count'];
    }
    $result['result'] = $data;



    echo json_encode($result);
} else if ($method == "getDrawCCPayment") {
    $result = [];

    $current = select_query($con, "draw", "id,name", "`deletes`='0' and `status`='Active' and `ticket_start_datetime`< '$dubaidate_time' and `ticket_end_datetime` > '$dubaidate_time'   order by `id` ASC LIMIT 1", "", "");
    $draw_id = $current['result'][0]['id'];

    $getCCPayment = select_query($con, "payment_history", "", "draw_id = '$draw_id' AND gateway='ccavenue'", "", "");
    $result['currentDraw'] = $getCCPayment['nr'];
    $result['currentDrawName'] = $current['result'][0]['name'];



    $drawlast = select_query($con, "draw", "id,name", "`status` LIKE 'Completed' AND `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
    $last_draw_id = $drawlast['result'][0]['id'];

    $getPastCCPayment = select_query($con, "payment_history", "", "draw_id = '$last_draw_id' AND gateway='ccavenue'", "", "");
    $result['pastDraw'] = $getPastCCPayment['nr'];
    $result['pastDrawName'] = $drawlast['result'][0]['name'];




    if ($_SESSION['memid'] != '') {
        $result['type'] = 1;
    } else {
        $result['type'] = 0;
        $result['result'] = '<div class="alert alert-danger" role="alert">Could not get data!</div>';
    }

    echo json_encode($result);
} else if ($method == "getDrawNetPayment") {
    $result = [];

    $current = select_query($con, "draw", "id,name", "`deletes`='0' and `status`='Active' and `ticket_start_datetime`< '$dubaidate_time' and `ticket_end_datetime` > '$dubaidate_time'   order by `id` ASC LIMIT 1", "", "");
    $draw_id = $current['result'][0]['id'];

    $getCCPayment = select_query($con, "payment_history", "", "draw_id = '$draw_id' AND gateway='network'", "", "");
    $result['currentDraw'] = $getCCPayment['nr'];
    $result['currentDrawName'] = $current['result'][0]['name'];



    $drawlast = select_query($con, "draw", "id,name", "`status` LIKE 'Completed' AND `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
    $last_draw_id = $drawlast['result'][0]['id'];

    $getPastCCPayment = select_query($con, "payment_history", "", "draw_id = '$last_draw_id' AND gateway='network'", "", "");
    $result['pastDraw'] = $getPastCCPayment['nr'];
    $result['pastDrawName'] = $drawlast['result'][0]['name'];




    if ($_SESSION['memid'] != '') {
        $result['type'] = 1;
    } else {
        $result['type'] = 0;
        $result['result'] = '<div class="alert alert-danger" role="alert">Could not get data!</div>';
    }

    echo json_encode($result);
} else if ($method == "ticketanalyzechart") {
    $result = [];

    $fd = date('Y-m-d') . ' 00:00:00';
    $td = date('Y-m-d') . ' 23:59:59';


    $getPayment = select_query($con, "payment_history", "", "`pay_re_status` = 'CAPTURED' AND `status` = '1'  AND `createdon` BETWEEN '$fd' AND '$td'  ORDER BY `id` ASC", "", "");

    if ($getPayment['nr'] > 0) {
        $response['TotalOrder'] = $getPayment['nr'];
        $response['type'] = '0';
        $tableNames = ['ticket', 'aticket', 'fticket', 'mticket', 'wticket', 'cticket', 'cpticket', 'bpticket'];

        $ticket = $aticket = $fticket = $mticket = $wticket = $cticket = $cpticket = $bpticket = $invticket = $ainvticket = $finvticket = $minvticket = $winvticket = $cinvticket = $cpinvticket = $bpinvticket = 0;

        foreach ($getPayment['result'] as $key => $value) {

            $transactionId = $value['transaction_id'];

            $otTicket = select_query($con, "ticket", "", "transaction_id = '$transactionId' AND  `deletes`='0'", "", "");
            $ticket += $otTicket['nr'];

            $aticket_qy = select_query($con, "aticket", "", "transaction_id = '$transactionId' AND  `deletes`='0'", "", "");
            $aticket += $aticket_qy['nr'];

            $fticket_qy = select_query($con, "fticket", "", "transaction_id = '$transactionId' AND  `deletes`='0'", "", "");
            $fticket += $fticket_qy['nr'];

            $mticket_qy = select_query($con, "mticket", "", "transaction_id = '$transactionId' AND  `deletes`='0'", "", "");
            $mticket += $mticket_qy['nr'];

            $wticket_qy = select_query($con, "wticket", "", "transaction_id = '$transactionId' AND  `deletes`='0'", "", "");
            $wticket += $wticket_qy['nr'];

            $cticket_qy = select_query($con, "cticket", "", "transaction_id = '$transactionId' AND  `deletes`='0'", "", "");
            $cticket += $cticket_qy['nr'];

            $cpticket_qy = select_query($con, "cpticket", "", "transaction_id = '$transactionId' AND  `deletes`='0'", "", "");
            $cpticket += $cpticket_qy['nr'];

            $bpticket_qy = select_query($con, "bpticket", "", "transaction_id = '$transactionId' AND  `deletes`='0'", "", "");
            $bpticket += $bpticket_qy['nr'];
        }
        $response['ticket'] = $ticket;
        $response['aticket'] = $aticket;
        $response['fticket'] = $fticket;
        $response['mticket'] = $mticket;
        $response['wticket'] = $wticket;
        $response['cticket'] = $cticket;
        $response['cpticket'] = $cpticket;
        $response['bpticket'] = $bpticket;

        $response['invticket'] = $ticket;
        $response['ainvticket'] = $aticket;
        $response['finvticket'] = $fticket;
        $response['minvticket'] = $mticket;
        $response['winvticket'] = $wticket;
        $response['cinvticket'] = $cticket;
        $response['cpinvticket'] = $cpticket;
        $response['bpinvticket'] = $bpticket;
    } else {
        $response['type'] = '1';
    }

    echo json_encode($response);
}
