<?php

/**
 * Date              Developer                     Modification
 * 23-8-2023            Divya                           kiosk ticket has been added to pie chart
 *  * 29-9-2023         sathiya                         customer login chart created
 *  20-10-2023    Divya                                con changed to rcon
 * 30-10-2023    Divya                                free ticket and wallet ticket has been closed

 */




include '../../include/shi-config.php';


include '../../include/functions.php';
include '../../include/payment-config.php';
include '../../include/Crypto.php';



$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";



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
        $tableNames = ['ticket', 'aticket', 'fticket', 'mticket', 'wticket', 'cticket', 'cpticket', 'bpticket', 'kticket'];
        $total = 0;

        foreach ($tableNames as $tableName) {
            $query = "SELECT COUNT(id) AS total FROM $tableName WHERE draw_id = '$drawid' AND deletes = '1'";
            $result = mysqli_query($this->con, $query);
            $row = mysqli_fetch_assoc($result);
            $total += (int)$row['total'];
        }

        return $total;
    }




    function getTicketBaseSales($drawid)
    {
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




        if ($drawid != '') {
            $drawcon = "`draw_id` = '$drawid' AND";
        } else {
            $drawcon = "";
        }

        $output = '';
        $total_Aed_Array = [];

        /////////// Online Ticket ///////////////

        if ($roll_id == 1) {
            $oticket = mysqli_query($this->con, "SELECT sum(net_total) as 'total' FROM ticket INNER JOIN invoice ON invoice.id = ticket.invoice_no WHERE ticket.draw_id = '$drawid' AND invoice.response != 'wallet' AND ticket.deletes = '0'");
            $row1 = mysqli_fetch_array($oticket);

            $data[] = ['Online (' . intval($row1['total']) . ')' => intval($row1['total'])];




            // $wticket = mysqli_query($this->con, "SELECT SUM(net_total) as 'total' FROM `wticket` WHERE deletes = 0 AND draw_id = '$drawid'");
            // $row2 = mysqli_fetch_array($wticket);
            // $data[] = ['Wallet (' . intval($row2['total']) . ')' => intval($row2['total'])];
        }

        //////////// Agent Ticket //////////////

        if ($roll_id == 1) {
            $aticket = select_query_sum($this->con, "aticket", "net_total", "$drawcon $qcon `deletes`='0' order by `id` DESC", "", "");
            $data[] = ['Agent (' . intval($aticket) . ')' => intval($aticket)];
        }


        ///// Cash Ticket /////

        if ($roll_id == 1) {
            $cpticket = select_query_sum($this->con, "cpticket", "net_total", "$drawcon  `deletes`='0' order by `id` DESC", "", "");
            $data[] = ['Cash (' . intval($cpticket) . ')' => intval($cpticket)];
        }

        ///// Bonus Ticket /////

        if ($roll_id == 1) {

            $bpticket = select_query_sum($this->con, "bpticket", "net_total", "$drawcon  `deletes`='0' order by `id` DESC", "", "");
            $data[] = ['Bonus (' . intval($bpticket)  . ')' => intval($bpticket)];
        }

        ///// Coupon Ticket /////

        if ($roll_id == 1) {
            $cticket = select_query_sum($this->con, "cticket", "net_total", "$drawcon  `deletes`='0' order by `id` DESC", "", "");
            $data[] = ['Coupon (' . intval($cticket) . ')' => intval($cticket)];
        }

        ///// Free Ticket //////

        // if ($roll_id == 1) {
        //     $fticket = select_query_sum($this->con, "fticket", "net_total", "$drawcon $qcon `deletes`='0' order by `id` DESC", "", "");
        //     $data[] = ['Free (' . intval($fticket) . ')' => intval($fticket)];
        // }


        // kiosk ticket 
        if ($roll_id == 1) {
            $kticket = select_query_sum($this->con, "kticket", "net_total", "$drawcon  `deletes`='0' order by `id` DESC", "", "");
            $data[] = ['Kiosk (' . intval($kticket) . ')' => intval($kticket)];
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
            $dateArray[] =  date($format, mktime(0, 0, 0, $m, ($de - $i), $y));
        }
        return array_reverse($dateArray);
    }
}

$overall = new overall($con, $dubaidate_time);

if ($method == "ticketsaleschart") {
    $result = [];

    $draw = select_query($rcon, "draw", "", "`deletes`='0' and `status`='Active' and `ticket_start_datetime`< '$dubaidate_time' and `ticket_end_datetime`>'$dubaidate_time'   order by `id` ASC", "", "");
    $result['result'] = $overall->getTicketBaseSales($draw['result'][0]['id']);

    if ($_SESSION['memid'] != '') {
        $result['type'] = 1;
        // $result['output'] = $drawout . $output;
    } else {
        $result['type'] = 0;
        $result['result'] = '<div class="alert alert-danger" role="alert">Could not get data!</div>';
    }

    echo json_encode($result);
} else  if ($method == "getErrorCount") {
    $result = [];

    $result['type'] = 1;
    // $time0 = strtotime($dubaidate_time);
    $fd = date('Y-m-d H:i:s', strtotime('-1 day', strtotime($dubaidate_time)));
    $td = date('Y-m-d H:i:s', strtotime('-10 minutes', strtotime($dubaidate_time)));


    // Network
    $Network = select_query($rcon, "payment_history", "", "`gateway` = 'network' AND (`pay_re_status` = 'CAPTURED' OR `pay_re_status` IS null) AND  `cron_check_status` = '0'  AND  `category` = 'PRODUCT' AND  `reference` != '' AND `status` = '0'  AND `createdon` BETWEEN '$fd' AND '$td'  ORDER BY `id` ASC", "", "");
    $result['result']['network'] =  $Network['nr'];

    // CCAvenue 
    $CCAvenue = select_query($rcon, "payment_history", "", "`gateway` = 'ccavenue' AND `status` = '0'  AND  `reference` != ''  AND  `cron_check_status` = '0' AND `pay_re_status` IN ('Success', 'Successful', 'Shipped') AND  `category` = 'PRODUCT' AND `createdon` BETWEEN '$fd' AND '$td'   ORDER BY `id` ASC", "", "");
    $result['result']['ccavenue'] =  $CCAvenue['nr'];



    // SMS 
    $smslog = select_query($rcon, "smslog", "", "`smssendstatus` = '0' AND `mobile` != '' AND `details` != '' ORDER BY `id` ASC", "", "");
    $result['result']['smslog'] =  $smslog['nr'];

    // Deleted Ticket
    $result['result']['deletedTicket'] =    $overall->getDeletedTicket($draw_id);

    // CC Missing Tickcet
    $ccorder_lookup = select_query($rcon, "ccorder_lookup", "", "`order_no` NOT IN (SELECT `transaction_id` FROM `ticket` WHERE `deletes` = '0' AND `draw_id` = '$draw_id') AND `ticket` = 'NO' AND order_status = 'Shipped' ORDER BY `id` DESC", "", "");
    $result['result']['ccMissingT'] =  intval($ccorder_lookup['nr']);



    // Last Draw Email
    $draw_comp = select_query($rcon, "draw", "", "`status` ='Completed'  AND `deletes` ='0' ORDER BY `id` DESC LIMIT  1", "", "");
    $completed_draw = $draw_comp['result']['0']['id'];
    $email_sentwin = select_query($rcon, "winnerlist", "", "`emaillog` = '1' AND `draw_id` ='$completed_draw' ORDER BY `winnerlist`.`draw_id` DESC", "", "");
    // Last Draw SMS
    $sms_sentwin = select_query($rcon, "winnerlist", "", "`smslog` = '1' AND `draw_id` ='$completed_draw' ORDER BY `winnerlist`.`draw_id` DESC", "", "");




    $result['result']['lastDrawemail'] =  intval($email_sentwin['nr']);

    $result['result']['lastDrawsms'] =  intval($sms_sentwin['nr']);


    $deleted_req_count = select_query($rcon, "user_delete_request", "", "`status` = '0'", "", "");
    $result['result']['deleted_req_count'] =  intval($deleted_req_count['nr']);

    echo json_encode($result);
} else if ($method == "getDrawTotal") {
    $result = [];

    $current = select_query($rcon, "draw", "id,name", "`deletes`='0' and `status`='Active' and `ticket_start_datetime`< '$dubaidate_time' and `ticket_end_datetime` > '$dubaidate_time'   order by `id` ASC LIMIT 1", "", "");
    $result['currentDraw'] = $overall->getTicketBaseSales($current['result'][0]['id']);
    $result['currentDrawName'] = $current['result'][0]['name'];



    $drawlast = select_query($rcon, "draw", "id,name", "`status` LIKE 'Completed' AND `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
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

    // $last7 = $overall->getLastNDays(7);
    $result['type'] = 1;

    $data = [];
    // foreach ($overall->getLastNDays(7) as $key) {
    //     $pre = date('M d', strtotime($key));
    // $data['days'][] = $pre;


    $signInUp = mysqli_query($con, "SELECT 
                                        DATE_FORMAT(`day`, '%Y-%m-%d') AS `day`,
                                        SUM(CASE WHEN `activity_type` = 'signUPcount' THEN `count` ELSE 0 END) AS `signUPcount`,
                                        SUM(CASE WHEN `activity_type` = 'signINcount' THEN `count` ELSE 0 END) AS `signINcount`
                                    FROM (
                                        SELECT 
                                            DATE(`created_at`) AS `day`,
                                            'signUPcount' AS `activity_type`,
                                            COUNT(`id`) AS `count`
                                        FROM user_register
                                        WHERE 
                                            `roll_id` = '0' AND 
                                            `deletes` = '0' AND 
                                            `status` = '0' AND 
                                            `created_at` BETWEEN CURDATE() - INTERVAL 6 DAY AND CURDATE() - INTERVAL 1 SECOND
                                        GROUP BY DATE(`created_at`)
                                        
                                        UNION ALL
                                        
                                        SELECT 
                                            DATE(`lastlogin`) AS `day`,
                                            'signINcount' AS `activity_type`,
                                            COUNT(`id`) AS `count`
                                        FROM user_register
                                        WHERE 
                                            `roll_id` = '0' AND 
                                            `deletes` = '0' AND 
                                            `status` = '0' AND 
                                            `lastlogin` BETWEEN CURDATE() - INTERVAL 6 DAY AND CURDATE() - INTERVAL 1 SECOND
                                        GROUP BY DATE(`lastlogin`)
                                        
                                        UNION ALL
                                        
                                        SELECT 
                                            CURDATE() AS `day`,
                                            'signUPcount' AS `activity_type`,
                                            COUNT(`id`) AS `count`
                                        FROM user_register
                                        WHERE 
                                            `roll_id` = '0' AND 
                                            `deletes` = '0' AND 
                                            `status` = '0' AND 
                                            DATE(`created_at`) = CURDATE()
                                        
                                        UNION ALL
                                        
                                        SELECT 
                                            CURDATE() AS `day`,
                                            'signINcount' AS `activity_type`,
                                            COUNT(`id`) AS `count`
                                        FROM user_register
                                        WHERE 
                                            `roll_id` = '0' AND 
                                            `deletes` = '0' AND 
                                            `status` = '0' AND 
                                            DATE(`lastlogin`) = CURDATE()
                                    ) AS subquery
                                    GROUP BY `day`;");


    while ($row = mysqli_fetch_assoc($signInUp)) {

        $data['days'][] = date('M d', strtotime($row['day']));
        $data['Signup'][] = $row['signUPcount'];
        $data['Signin'][] = $row['signINcount'];

    }

    // $row = mysqli_fetch_assoc($signInUp);

    // $signUp = mysqli_query($rcon, "select COUNT(`id`) AS 'count' from user_register where `roll_id` = '0' and `deletes`='0' AND `status` = '0'  AND `created_at` BETWEEN '$key 00:00:00' AND '$key 23:59:59' ORDER BY `id` DESC;");
    // $row = mysqli_fetch_assoc($signUp);

    // $data['Signup'][] = $row['signUPcount'];

    // $signIn = mysqli_query($rcon, "select COUNT(`id`) AS 'count' from user_register where `roll_id` = '0' and `deletes`='0' AND `status` = '0' AND `lastlogin` BETWEEN '$key 00:00:00' AND '$key 23:59:59' ORDER BY `id` DESC;");
    // $row1 = mysqli_fetch_assoc($signIn);

    //     $data['Signin'][]  =   $row1['signINcount'];
    // }


    $result['result'] = $data;

    echo json_encode($result);
} else if ($method == 'lastoneWeekSignUPWithCreatedBy') {
    $result = [];
    $data = [];

    $last7 = $overall->getLastNDays(7);
    $result['type'] = 1;

    foreach ($overall->getLastNDays(5) as $key) {
        $pre = date('M d', strtotime($key));
        $data['days'][] = $pre;

        $signUp = mysqli_query($rcon, "SELECT COUNT(`id`) AS 'count' FROM user_register WHERE `roll_id` = '0' AND `deletes`='0' AND `status` = '0' AND `created_at` BETWEEN '$key 00:00:00' AND '$key 23:59:59' AND `created_by` > 0 ORDER BY `id` DESC;");
        $row = mysqli_fetch_assoc($signUp);
        $data['Signup'][] = $row['count'];

        $kioskSignUps = mysqli_query($rcon, "SELECT COUNT(`id`) AS 'count' FROM user_register WHERE `roll_id` = '0' AND `deletes`='0' AND `status` = '0' AND `created_at` BETWEEN '$key 00:00:00' AND '$key 23:59:59' AND `created_by` IS NULL AND `kiosk_id` IS NOT NULL ORDER BY `id` DESC;");
        $kioskRow = mysqli_fetch_assoc($kioskSignUps);
        $data['KioskSignup'][] = $kioskRow['count'];

        $customerSignUps = mysqli_query($rcon, "SELECT COUNT(`id`) AS 'count' FROM user_register WHERE `roll_id` = '0' AND `deletes`='0' AND `status` = '0' AND `created_at` BETWEEN '$key 00:00:00' AND '$key 23:59:59' AND ((`created_by` IS NULL OR `created_by` = 0) AND `kiosk_id` IS NULL) ORDER BY `id` DESC;");
        $customerRow = mysqli_fetch_assoc($customerSignUps);
        $data['CustomerSignup'][] = $customerRow['count'];

        if ($key == date('Y-m-d')) {
            $currentDaySignUps = mysqli_query($rcon, "SELECT COUNT(`id`) AS 'count' FROM user_register WHERE `roll_id` = '0' AND `deletes`='0' AND `status` = '0' AND `created_at` BETWEEN '$key 00:00:00' AND '$key 23:59:59' AND `created_by` > 0 ORDER BY `id` DESC;");
            $currentDayRow = mysqli_fetch_assoc($currentDaySignUps);
            $data['CurrentDaySignup'] = $currentDayRow['count'];
            break;
        }
    }

    $result['result'] = $data;

    echo json_encode($result);
} else if ($method == "getDrawCCPayment") {
    $result = [];

    $current = select_query($rcon, "draw", "id,name", "`deletes`='0' and `status`='Active' and `ticket_start_datetime`< '$dubaidate_time' and `ticket_end_datetime` > '$dubaidate_time'   order by `id` ASC LIMIT 1", "", "");
    $draw_id = $current['result'][0]['id'];

    $getCCPayment = select_query($rcon, "payment_history", "", "draw_id = '$draw_id' AND gateway='ccavenue'", "", "");
    $result['currentDraw'] = $getCCPayment['nr'];
    $result['currentDrawName'] = $current['result'][0]['name'];



    $drawlast = select_query($rcon, "draw", "id,name", "`status` LIKE 'Completed' AND `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
    $last_draw_id = $drawlast['result'][0]['id'];

    $getPastCCPayment = select_query($rcon, "payment_history", "", "draw_id = '$last_draw_id' AND gateway='ccavenue'", "", "");
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

    $current = select_query($rcon, "draw", "id,name", "`deletes`='0' and `status`='Active' and `ticket_start_datetime`< '$dubaidate_time' and `ticket_end_datetime` > '$dubaidate_time'   order by `id` ASC LIMIT 1", "", "");
    $draw_id = $current['result'][0]['id'];

    $getCCPayment = select_query($rcon, "payment_history", "", "draw_id = '$draw_id' AND gateway='network'", "", "");
    $result['currentDraw'] = $getCCPayment['nr'];
    $result['currentDrawName'] = $current['result'][0]['name'];



    $drawlast = select_query($rcon, "draw", "id,name", "`status` LIKE 'Completed' AND `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
    $last_draw_id = $drawlast['result'][0]['id'];

    $getPastCCPayment = select_query($rcon, "payment_history", "", "draw_id = '$last_draw_id' AND gateway='network'", "", "");
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

    $date = date('Y-m-d');
    if ($_REQUEST['date'] != '') {
        $date = date('Y-m-d', strtotime($_REQUEST['date']));
    }

    $fd = $date . ' 00:00:00';
    $td = $date . ' 23:59:59';

    $response['type'] = '0';

    $ticket = mysqli_query($con, "SELECT COUNT(`id`) AS 'count' FROM `ticket` WHERE `createdon` BETWEEN '$fd' AND '$td' AND `deletes` = '0';");
    $ticket_c = mysqli_fetch_assoc($ticket);
    $response['ticketCount'] = ($ticket_c['count'] > 0) ? $ticket_c['count'] : 0;

    $invoice = mysqli_query($con, "SELECT COUNT(`id`) AS 'count' FROM `ticket` WHERE `invoice_no` != '0' AND `createdon` BETWEEN '$fd' AND '$td' AND `deletes` = '0';");
    $invoice_c = mysqli_fetch_assoc($invoice);
    $response['invoiceCount'] = ($invoice_c['count'] > 0) ? $invoice_c['count'] : 0;

    $payments = mysqli_query($con, "SELECT COUNT(`payment_history`.`id`) AS 'count' FROM `payment_history` INNER JOIN `ticket` ON `ticket`.`transaction_id` = `payment_history`.`transaction_id` WHERE `payment_history`.`pay_re_status` IN ('CAPTURED', 'Shipped', 'Successful', 'Success') AND `payment_history`.`status` = '1' AND `ticket`.`createdon` BETWEEN '$fd' AND '$td' AND `ticket`.`deletes` = '0';");
    $payments_c = mysqli_fetch_assoc($payments);
    $response['paymentsCount'] = ($payments_c['count'] > 0) ? $payments_c['count'] : 0;

    $ticketLines = mysqli_query($con, "SELECT COUNT(`test`.`ticket_id`) as 'count' FROM (SELECT `ticket_id` FROM `ticket_lines` WHERE `deletes` = '0' AND `createdon` BETWEEN '$fd' AND '$td' AND `type` = 'OT' GROUP BY `ticket_id`) AS `test`;");
    $ticketLines_c = mysqli_fetch_assoc($ticketLines);
    $response['ticketLinesCount'] = ($ticketLines_c['count'] > 0) ? $ticketLines_c['count'] : 0;


    // $getPayment = select_query($con, "payment_history", "", "`pay_re_status` IN ('CAPTURED', 'Shipped', 'Successful', 'Success') AND `status` = '1'  AND `createdon` BETWEEN '$fd' AND '$td'  ORDER BY `id` ASC", "", "");

    // if ($getPayment['nr'] > 0) {
    //     $response['TotalOrder'] = $getPayment['nr'];
    //     $response['type'] = '0';
    //     $tableNames = ['ticket', 'aticket', 'fticket', 'mticket', 'wticket', 'cticket', 'cpticket', 'bpticket'];

    //     $ticket = $aticket = $fticket = $mticket = $wticket = $cticket = $cpticket = $bpticket = $invticket = $ainvticket = $finvticket = $minvticket = $winvticket = $cinvticket = $cpinvticket = $bpinvticket = 0;

    //     foreach ($getPayment['result'] as $key => $value) {

    //         $transactionId = $value['transaction_id'];

    //         $otTicket = select_query($con, "ticket", "", "transaction_id = '$transactionId' AND  `deletes`='0'", "", "");
    //         $ticket += $otTicket['nr'];

    //         $aticket_qy = select_query($con, "aticket", "", "transaction_id = '$transactionId' AND  `deletes`='0'", "", "");
    //         $aticket += $aticket_qy['nr'];

    //         $fticket_qy = select_query($con, "fticket", "", "transaction_id = '$transactionId' AND  `deletes`='0'", "", "");
    //         $fticket += $fticket_qy['nr'];

    //         $mticket_qy = select_query($con, "mticket", "", "transaction_id = '$transactionId' AND  `deletes`='0'", "", "");
    //         $mticket += $mticket_qy['nr'];

    //         $wticket_qy = select_query($con, "wticket", "", "transaction_id = '$transactionId' AND  `deletes`='0'", "", "");
    //         $wticket += $wticket_qy['nr'];

    //         $cticket_qy = select_query($con, "cticket", "", "transaction_id = '$transactionId' AND  `deletes`='0'", "", "");
    //         $cticket += $cticket_qy['nr'];

    //         $cpticket_qy = select_query($con, "cpticket", "", "transaction_id = '$transactionId' AND  `deletes`='0'", "", "");
    //         $cpticket += $cpticket_qy['nr'];

    //         $bpticket_qy = select_query($con, "bpticket", "", "transaction_id = '$transactionId' AND  `deletes`='0'", "", "");
    //         $bpticket += $bpticket_qy['nr'];
    //     }
    //     $response['ticket'] = $ticket;
    //     $response['aticket'] = $aticket;
    //     $response['fticket'] = $fticket;
    //     $response['mticket'] = $mticket;
    //     $response['wticket'] = $wticket;
    //     $response['cticket'] = $cticket;
    //     $response['cpticket'] = $cpticket;
    //     $response['bpticket'] = $bpticket;

    //     $response['invticket'] = $ticket;
    //     $response['ainvticket'] = $aticket;
    //     $response['finvticket'] = $fticket;
    //     $response['minvticket'] = $mticket;
    //     $response['winvticket'] = $wticket;
    //     $response['cinvticket'] = $cticket;
    //     $response['cpinvticket'] = $cpticket;
    //     $response['bpinvticket'] = $bpticket;
    // } else {
    //     $response['type'] = '1';
    // }

    echo json_encode($response);
} else if ($method == "ticketAnanDetails") {
    $result = [];
    $draw = BlockSQLInjection($_POST["draw"]);
    // $draw = $_POST['draw'];

    if ($draw == '') {
        $current = select_query($rcon, "draw", "id,name", "`deletes`='0' and `status`='Active' and `ticket_start_datetime`< '$dubaidate_time' and `ticket_end_datetime` > '$dubaidate_time'   order by `id` ASC LIMIT 1", "", "");
        $draw = $current['result'][0]['id'];
    }


    $query1 = "SELECT COUNT(`id`) AS 'ticketCount' ,SUM(net_total) AS 'ticketTotal', SUM(amt) AS 'lineWiseTotal' FROM (
                SELECT * from
                (SELECT `id`, `net_total`  FROM `ticket` WHERE `draw_id` = $draw AND `deletes` = '0') AS `t`
                INNER JOIN (SELECT ticket_lines.ticket_id, SUM(product.rate) AS 'amt'  FROM `ticket_lines`
                INNER JOIN product ON product.id = ticket_lines.product_id
                WHERE `ticket_lines`.`draw_id` = $draw AND `ticket_lines`.`deletes` = '0' AND `ticket_lines`.`type` = 'OT' GROUP BY `ticket_id`) as `s` on s.ticket_id = t.id
                ) AS v WHERE v.net_total = v.amt;";


    // var_dump($query1);die;

    $rqueryRun = mysqli_query($rcon, $query1);
    $row1 = mysqli_fetch_assoc($rqueryRun);


    $query2 = "SELECT COUNT(`id`) AS 'invoiceCount' FROM `ticket` WHERE `draw_id` = $draw AND `deletes` = '0' AND `invoice_no` != 0;";
    $rqueryRun1 = mysqli_query($rcon, $query2);
    $row2 = mysqli_fetch_assoc($rqueryRun1);





    $result['type'] = 0;


    $query5 = mysqli_query($rcon, "SELECT COUNT(`id`) AS 'count', SUM(`net_total`) as 'total' FROM `ticket` WHERE `deletes` = '0' AND `draw_id` = '$draw';");
    $row5 = mysqli_fetch_assoc($query5);


    $result['ticketCount'] = (intval($row5['count']) > 0) ? $row5['count'] : 0;
    $result['ticketTotal'] = (intval($row5['total']) > 0) ? $row5['total'] : 0;

    $qeury4 = mysqli_query($rcon, "SELECT SUM(s.`amt`) as 'totalline' FROM ( SELECT ticket_lines.ticket_id, SUM(product.rate) AS 'amt' FROM `ticket_lines` INNER JOIN product ON product.id = ticket_lines.product_id WHERE `ticket_lines`.`draw_id` = $draw AND `ticket_lines`.`deletes` = '0' AND `ticket_lines`.`type` = 'OT' GROUP BY `ticket_id`) as s;");
    $row4 = mysqli_fetch_assoc($qeury4);


    $result['lineWiseTotal'] = (intval($row4['totalline']) > 0) ? $row4['totalline'] : 0;
    $result['invoiceCount'] = (intval($row2['invoiceCount']) > 0) ? $row2['invoiceCount'] : 0;


    echo json_encode($result);
} else if ($method == 'loginDetails') {
    $result = [];

    $last7 = $overall->getLastNDays(7);
    $result['type'] = 1;

    $data = [];
    foreach ($overall->getLastNDays(7) as $key) {

        $pre = date('M d', strtotime($key));
        $data['days'][] = $pre;
        $type_method = 'email_login_success_';

        $signUp = mysqli_query($lcon, "select COUNT(`id`) AS 'count' from `error_log` where `type` ='$type_method' AND `createdon` BETWEEN '$key 00:00:00' AND '$key 23:59:59' ORDER BY `id` DESC;");

        $row = mysqli_fetch_assoc($signUp);
        $data['Signup'][] = $row['count'];


        $signIn = mysqli_query($lcon, "select COUNT(`id`) AS 'count' from `error_log` where `type` ='mobile_login_success_' AND `createdon` BETWEEN '$key 00:00:00' AND '$key 23:59:59' ORDER BY `id` DESC;");
        $row1 = mysqli_fetch_assoc($signIn);
        $data['Signin'][]  =   $row1['count'];
    }
    $result['result'] = $data;
    echo json_encode($result);
} else if ($method == 'loginotpDetails') {
    $result = [];

    $last7 = $overall->getLastNDays(7);
    $result['type'] = 1;

    $data = [];
    foreach ($overall->getLastNDays(7) as $key) {

        $pre = date('M d', strtotime($key));
        $data['days'][] = $pre;
        $logioEmailotp = mysqli_query($lcon, "select COUNT(`id`) AS 'count' from `error_log` where `type` ='eamil_loginotp_success' AND `createdon` BETWEEN '$key 00:00:00' AND '$key 23:59:59' ORDER BY `id` DESC;");

        $row = mysqli_fetch_assoc($logioEmailotp);
        $data['Signup'][] = $row['count'];


        $loginMobileotp = mysqli_query($lcon, "select COUNT(`id`) AS 'count' from `error_log` where `type` ='mobile_loginotp_success' AND `createdon` BETWEEN '$key 00:00:00' AND '$key 23:59:59' ORDER BY `id` DESC;");
        $row1 = mysqli_fetch_assoc($loginMobileotp);
        $data['Signin'][]  = $row1['count'];
    }
    $result['result'] = $data;
    echo json_encode($result);
}
