<?php

include '../../include/shi-config.php';
include '../../include/functions.php';
include '../../include/payment-config.php';
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





if ($method == "total_earnings") {



    $result = [];



    $earnings = select_top_name($con, "user_register", "t_earning", "`id`='$_SESSION[memid]' and `deletes`='0'", "t_earning", "");



    if ($earnings != '') {



        $result['type'] = 1;



        $result['result'] = floatval($earnings);
    } else {



        $result['type'] = 1;



        $result['result'] = 'Could not get amount!';
    }



    echo json_encode($result);
} else if ($method == "list_earnings") {



    $result = [];



    $contype = '';



    $da = '';



    $now = date('Y-m-d');


    $formdate = BlockSQLInjectionforagent($_POST["formdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    // $formdate = $_POST['formdate'];



    // $todate = $_POST['todate'];



    if ($formdate != '' && $todate != '') {



        $da = 'DESC';



        $fd = date("Y/m/d", strtotime($formdate));



        $td = date("Y/m/d", strtotime($todate));



        $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {



        $da = 'DESC';



        $contype = "`createdon` LIKE '%$now%' AND";
    }



    if ($_SESSION['memid'] != 1) {



        $agent = "`to_id` = '$_SESSION[memid]' AND";
    } else {



        $agent = "";
    }



    $earn_Trans = select_query($con, "earning_transaction", "", "$agent $contype `deletes`='0' ORDER BY `id` DESC", "", "");



    if ($earn_Trans['nr'] > 0) {



        foreach ($earn_Trans['result'] as $key => $value) {



            $userid = $value['to_id'];



            $rollid = select_top_name($con, "user_register", "roll_id", "`id`='$userid' and `deletes`='0'", "roll_id", "");



            if (intval($rollid) != 0) {



                $invoiceid = $value['invoice_id'];



                $transid = select_top_name($con, "aticket", "transaction_id", "`invoice_no`='$invoiceid' and `deletes`='0'", "transaction_id", "");



                $agentid = select_top_name($con, "aticket", "agent_id", "`invoice_no`='$invoiceid' and `deletes`='0'", "agent_id", "");



                $agent_name = select_top_name($con, "user_register", "name", "`id`='$agentid' and `deletes`='0'", "name", "");



                $ticketamount = select_top_name($con, "aticket", "net_total", "`invoice_no`='$invoiceid' and `deletes`='0'", "net_total", "");



                $result[] = array("id" => str_pad($value['id'], 7, "0", STR_PAD_LEFT), "ticketamount" => $ticketamount, "date" => date("d-M-Y g:i a", strtotime($value['createdon'])), "amt" => $value['amount'], "type" => ucwords($value['type']), "transid" => $transid, "status" => 'Success', "sellername" => ucwords($agent_name), "ordertype" => ucwords($value['order_type']));
            }
        }



        // $result['type'] = 1;



    } else {



        // $result['type'] = 0;



    }



    echo json_encode($result);
} else if ($method == "total_points") {



    $result = [];



    $points = select_top_name($con, "user_register", "t_point", "`id`='$_SESSION[memid]' and `deletes`='0'", "t_point", "");



    if ($points != '') {



        $result['type'] = 1;



        $result['result'] = floatval($points);
    } else {



        $result['type'] = 1;



        $result['result'] = 'Could not get amount!';
    }



    echo json_encode($result);
}


/// 15-7-2023     
else if ($method == "list_points_trans") {



    $result = [];



    $contype = '';



    $da = '';



    $now = date('Y-m-d');


    $formdate = BlockSQLInjectionforagent($_POST["formdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    $tablename = BlockSQLInjectionforagent($_POST["tablename"]);
    // $formdate = $_POST['formdate'];



    // $todate = $_POST['todate'];



    // $tablename = $_POST['tablename'];



    if ($formdate != '' && $todate != '') {



        $da = 'DESC';



        $fd = date("Y-m-d", strtotime($formdate));



        $td = date("Y-m-d", strtotime($todate));



        $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {



        $da = 'DESC';



        $contype = "`createdon` LIKE '%$now%' AND";
    }



    if ($tablename == 'pointtable') {



        $userid = $_SESSION['memid'];



        $conition = "(`from_id` = '$userid' OR `to_id` = '$userid') AND";



        $point_Trans = select_query($rcon, "points_transaction", "", "$conition $contype `deletes`='0'", "", "");



        if ($point_Trans['nr'] > 0) {



            foreach ($point_Trans['result'] as $key => $value) {



                $trans_id = '';



                $cusid = '';



                $cusname = '';



                $point_s = '';



                $ordertype = '';



                $formname = select_top_name($rcon, "user_register", "name", "`id`='$value[from_id]' and `deletes`='0'", "name", "");
                $formlname = select_top_name($rcon, "user_register", "lname", "`id`='$value[from_id]' and `deletes`='0'", "lname", "");



                $toname = select_top_name($rcon, "user_register", "name", "`id`='$value[to_id]' and `deletes`='0'", "name", "");
                $tolname = select_top_name($rcon, "user_register", "lname", "`id`='$value[to_id]' and `deletes`='0'", "lname", "");



                $inv_id = $value['invoice_id'];



                if ($value['type'] == 'request') {



                    $trans_id = select_top_name($rcon, "point_request", "request_id", "`id`='$inv_id' and `deletes`='0'", "request_id", "");



                    $cusname = select_top_name($rcon, "user_register", "name", "`id`='$value[from_id]' and `deletes`='0'", "name", "");
                    $cuslname = select_top_name($rcon, "user_register", "lname", "`id`='$value[from_id]' and `deletes`='0'", "lname", "");
                    $cusfullname = $cusname . ' ' . $cuslname;
                } else {



                    $trans_id = select_top_name($rcon, "aticket", "transaction_id", "`invoice_no`='$inv_id' and `deletes`='0'", "transaction_id", "");



                    $cusid = select_top_name($rcon, "aticket", "user_id", "`invoice_no`='$inv_id' and `deletes`='0'", "user_id", "");



                    $cusname = select_top_name($rcon, "user_register", "name", "`id`='$cusid' and `deletes`='0'", "name", "");
                    $cuslname = select_top_name($rcon, "user_register", "lname", "`id`='$cusid' and `deletes`='0'", "lname", "");
                    $cusfullname = $cusname . ' ' . $cuslname;
                }



                if ($value['from_id'] == $_SESSION['memid']) {



                    $ordertype = '<p style="color: red;">Debit</p>';



                    $point_s = '<p style="color: red;">' . $value['points'] . '</p>';
                } else {



                    $ordertype = '<p style="color: green;">Credit</p>';



                    $point_s = '<p style="color: green;">' . $value['points'] . '</p>';
                }



                $result['result'][] = array("ordertype" => $ordertype, "toname" => $toname . ' ' . $tolname, "cusname" => $cusname . ' ' . $cuslname, "status" => 'Success', "transid" => $trans_id, "paymenttype" => ucwords($value['type']), "points" => $point_s, "formname" => $formname . ' ' . $formlname, "id" => str_pad($value['id'], 7, "0", STR_PAD_LEFT), "date" => date("d-M-Y g:i a", strtotime($value['createdon'])));
            }



            $result['type'] = 1;
        } else {



            $result['type'] = 0;
        }
    } else if ($tablename == 'requesttable') {



        if ($_SESSION['memid'] != '') {



            $agent = "(`from_id` = '$_SESSION[memid]' OR `to_id` = '$_SESSION[memid]') AND";
        }



        $point_req = select_query($con, "point_request", "", "$agent `transaction_status` = '0' and `deletes`='0' ORDER BY `id` DESC", "", "");



        if ($point_req['nr'] > 0) {



            foreach ($point_req['result'] as $key => $value) {



                $to_id = $value['to_id'];



                $from_id = $value['from_id'];



                $action = '';



                if ($from_id == $_SESSION['memid']) {



                    $action = '-';



                    $leader_name = select_top_name($con, "user_register", "name", "`id`='$to_id' and `deletes`='0'", "name", "");
                    $leader_lname = select_top_name($con, "user_register", "lname", "`id`='$to_id' and `deletes`='0'", "lname", "");
                } else {



                    $leader_name = select_top_name($con, "user_register", "name", "`id`='$from_id' and `deletes`='0'", "name", "");
                    $leader_lname = select_top_name($con, "user_register", "lname", "`id`='$from_id' and `deletes`='0'", "lname", "");



                    $action = '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-money" onclick="pointTransfer(' . "'$value[request_id]'" . ')">&nbsp;Transfer</span></a>&nbsp;';

                    if ($value['transaction_status'] ==  '0') {
                        $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-ban" onclick="pointReject(' . "'$value[request_id]'" . ')">&nbsp;Reject</span></a>';
                    }
                }



                $result['result'][] = array("requestid" => $value['request_id'], "id" => str_pad($value['id'], 7, "0", STR_PAD_LEFT), "name" => $leader_name . ' ' . $leader_lname, "date" => date("d-M-Y g:i a", strtotime($value['createdon'])), "points" => $value['points'], "status" => "Pending", "action" => $action);
            }



            $result['type'] = 1;
        } else {



            $result['type'] = 0;
        }
    } else {



        if ($_SESSION['memid'] != '') {



            $agent = "`from_id` = '$_SESSION[memid]' AND";
        }



        $point_req = select_query($con, "point_request", "", "$contype $agent `transaction_status` = '2' and `deletes`='0' ORDER BY `id` DESC", "", "");



        if ($point_req['nr'] > 0) {



            foreach ($point_req['result'] as $key => $value) {



                $to_id = $value['to_id'];



                $from_id = $value['from_id'];



                $action = '';



                if ($from_id == $_SESSION['memid']) {



                    $action = '-';



                    $leader_name = select_top_name($con, "user_register", "name", "`id`='$to_id' and `deletes`='0'", "name", "");
                } else {



                    $leader_name = select_top_name($con, "user_register", "name", "`id`='$from_id' and `deletes`='0'", "name", "");



                    // $action = '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-money" onclick="pointTransfer(' . "'$value[request_id]'" . ')"> Transfer</span></a>';
                }



                $result['result'][] = ["requestid" => $value['request_id'], 'reason' => $value['reason'], "id" => str_pad($value['id'], 7, "0", STR_PAD_LEFT), "name" => $leader_name, "date" => date("d-M-Y g:i a", strtotime($value['createdon'])), "points" => $value['points'], "status" => "Rejected", "action" => $action];
            }



            $result['type'] = 1;
        } else {



            $result['type'] = 0;
        }
    }


    echo json_encode($result);
} else if ($method == "rejectToPoint") {
    $result = [];
    $rejectReason = BlockSQLInjection($_REQUEST["topoint"]);
    $pointrequestid = BlockSQLInjection($_REQUEST["pointrequestid"]);
    // $rejectReason = $_REQUEST['topoint'];
    // $pointrequestid = $_REQUEST['pointrequestid'];


    if ($pointrequestid == '') {
        $result['type'] = '0';
        $result['result'] = 'Request ID not Found!';
        goto FGvi;
    }

    if ($rejectReason == '') {
        $result['type'] = '0';
        $result['result'] = 'Kindly Enter the Reason!';
        goto FGvi;
    }




    $point_req = select_query($con, "point_request", "", "`request_id` = '$pointrequestid' AND `transaction_status` = '0' AND `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");

    if ($point_req['nr'] > 0) {
        $id = $point_req['result'][0]['id'];


        $point_arr3 = ["transaction_status" => '2', 'reason' => $rejectReason];
        $point_update3 = update($con, "point_request", "`id` = '$id' and `deletes`='0'", $point_arr3, "", "", "", "");
        $errors = $point_update3['errors'];
        if ($errors != "") {
            $result["type"] = "0";
            $result["result"] = $errors;
        } else {

            $result['type'] = '1';
            $result['result'] = 'Request Rejected Successfully!';
            goto FGvi;
        }
    } else {
        $result['type'] = '0';
        $result['result'] = 'Request ID not Found!';
        goto FGvi;
    }


    FGvi:
    echo json_encode($result);
}

/////////  NEW /////////////////
else if ($method == "collect_earning") {


    $result = [];







    $roll_id = select_top_name($rcon, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC ", "roll_id", "");


    $n_roll_id = intval($roll_id) + 1;


    $now = date("Y-m-d H:i:s");

    // $draw = select_query($rcon, "draw", "", "`deletes`='0' and `status`='Active' and `ticket_start_datetime`<'$now' and `ticket_end_datetime`>'$now'   order by `id` ASC", "", "");

    $user = select_query($rcon, "user_register", "", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC", "", "");

    $totalagent = select_query($rcon, "user_register", "", "`created_by`='$_SESSION[memid]' and `roll_id` = '$n_roll_id' and `deletes`='0'  order by `id` DESC", "", "");

    $product = select_query($rcon, "product", "", "`deletes`='0'", "", "");

    $aticket = 0;
    $mticket = 0;
    $oticket = 0;
    $total_amt = '';
    $qcon = '';

    if ($roll_id != 1) {
        $qcon = "`agent_id` = '$_SESSION[memid]' AND";
    } else {
        $qcon = "`agent_id` != '' AND";
    }


    $drawid = $draw_id;
    $createdon = $dcreatedon;

    if ($drawid != '') {
        $drawcon = "`draw_id` = '$drawid' AND";
    } else {
        $drawcon = "";
    }



    if ($createdon != '') {
        $createdon = "`createdon` = '$drawid' AND";
    } else {
        $createdon = "";
    }

    $output = '';
    $total_Aed_Array = [];




    /////////// Online Ticket ///////////////

    if ($roll_id == 1) {

        $oticket = mysqli_query($con, "SELECT sum(net_total) FROM ticket INNER JOIN invoice ON invoice.id = ticket.invoice_no WHERE ticket.draw_id = '$drawid' AND invoice.response != 'wallet' AND ticket.deletes = '0'");
        $row1 = mysqli_fetch_array($oticket);
        $oticket = $row1['sum(net_total)'];

        $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="mt-2">
                                        <h6 class="">Online Ticket</h6>
                                        <h2 class="mb-0 number-font" id="totalonline">AED ' . intval($oticket) . '</h2>';

        $sql = mysqli_query($con, "SELECT pt.id, ROUND(pt.rate) AS 'productAmt', IFNULL(ROUND(t.totalAmt),0) AS 'totalAmt' FROM `product` AS pt 
               LEFT JOIN (SELECT t.product_id, SUM(p.rate) AS 'totalAmt' FROM `ticket_lines` AS t
               INNER JOIN `invoice` AS i ON i.id = t.invoice_no 
               LEFT JOIN product AS p ON p.id = t.product_id
               WHERE t.type = 'OT'  and t.draw_id = '$drawid' and t.deletes='0' and i.response != 'wallet' AND p.deletes = '0' GROUP BY t.product_id) AS t ON t.product_id = pt.id
               WHERE pt.deletes = '0';");

        if (mysqli_num_rows($sql) > 0) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $output .= '<p class="aed-agent">AED  ' . intval($row['productAmt']) . ' - ' . $row['totalAmt'] . ' </p>';
                // $total_Aed_Array['AED' . intval($row['productAmt'])] += $row['totalAmt'];
                $total_Aed_Array['AED' . intval($row['productAmt'])] = ($total_Aed_Array['AED' . intval($row['productAmt'])] ?? 0) + $row['totalAmt'];
            }
        }

        $output .= '</div>
                </div>
            </div>
        </div>
     </div>';

        $wticket = mysqli_query($con, "SELECT SUM(net_total) FROM `wticket` WHERE deletes = 0 AND draw_id = '$drawid'");
        $row2 = mysqli_fetch_array($wticket);
        $wticket = $row2['SUM(net_total)'];

        $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="mt-2">
                            <h6 class="">Wallet Ticket</h6>
                            <h2 class="mb-0 number-font" id="totalonline">AED ' . intval($wticket) . '</h2>';


        $sql = mysqli_query($con, "SELECT pt.id, ROUND(pt.rate) AS 'productAmt', IFNULL(ROUND(t.totalAmt),0) AS 'totalAmt' FROM `product` AS pt 
        LEFT JOIN (SELECT t.product_id, SUM(p.rate) AS 'totalAmt' FROM `ticket_lines` AS t
        LEFT JOIN product AS p ON p.id = t.product_id
        WHERE t.type = 'WT'  and t.draw_id = '$drawid' and t.deletes='0' AND p.deletes = '0' GROUP BY t.product_id) AS t ON t.product_id = pt.id
        WHERE pt.deletes = '0';");

        if (mysqli_num_rows($sql) > 0) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $output .= '<p class="aed-agent">AED  ' . intval($row['productAmt']) . ' - ' . $row['totalAmt'] . ' </p>';
                $total_Aed_Array['AED' . intval($row['productAmt'])] += $row['totalAmt'];
            }
        }

        $output .= '</div>
                    </div>
                </div>
            </div>
        </div>';



        //////////// Agent Ticket //////////////

        $aticket = select_query_sum($con, "aticket", "net_total", "$drawcon $qcon `deletes`='0' order by `id` DESC", "", "");


        $output .= ' <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6 class="">Agent Ticket</h6>
                        <h2 class="mb-0 number-font" id="totalagent">AED ' . floatval($aticket) . '</h2>';

        $sql = mysqli_query($con, "SELECT pt.id, ROUND(pt.rate) AS 'productAmt', IFNULL(ROUND(t.totalAmt),0) AS 'totalAmt' FROM `product` AS pt 
        LEFT JOIN (SELECT t.product_id, SUM(p.rate) AS 'totalAmt' FROM `ticket_lines` AS t
        LEFT JOIN product AS p ON p.id = t.product_id
        WHERE t.type = 'AT'  and t.draw_id = '$drawid' and t.deletes='0' AND p.deletes = '0' GROUP BY t.product_id) AS t ON t.product_id = pt.id
        WHERE pt.deletes = '0';");

        if (mysqli_num_rows($sql) > 0) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $output .= '<p class="aed-agent">AED  ' . intval($row['productAmt']) . ' - ' . $row['totalAmt'] . ' </p>';
                $total_Aed_Array['AED' . intval($row['productAmt'])] += $row['totalAmt'];
            }
        }

        $output .= '</div>
                </div>
            </div>
        </div>
        </div>';


        ///// Cash Ticket /////


        $cpticket = select_query_sum($con, "cpticket", "net_total", "$drawcon  `deletes`='0' order by `id` DESC", "", "");

        $output .= ' <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6 class="">Cash Ticket</h6>
                        <h2 class="mb-0 number-font" id="totalfree">AED ' . floatval($cpticket) . '</h2>';


        $sql = mysqli_query($con, "SELECT pt.id, ROUND(pt.rate) AS 'productAmt', IFNULL(ROUND(t.totalAmt),0) AS 'totalAmt' FROM `product` AS pt 
        LEFT JOIN (SELECT t.product_id, SUM(p.rate) AS 'totalAmt' FROM `ticket_lines` AS t
        LEFT JOIN product AS p ON p.id = t.product_id
        WHERE t.type = 'CP'  and t.draw_id = '$drawid' and t.deletes='0' AND p.deletes = '0' GROUP BY t.product_id) AS t ON t.product_id = pt.id
        WHERE pt.deletes = '0';");

        if (mysqli_num_rows($sql) > 0) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $output .= '<p class="aed-agent">AED  ' . intval($row['productAmt']) . ' - ' . $row['totalAmt'] . ' </p>';
                $total_Aed_Array['AED' . intval($row['productAmt'])] += $row['totalAmt'];
            }
        }


        $output .= '</div>
                </div>
            </div>
        </div>
        </div>';


        ///// Bonus Ticket /////


        $bpticket = select_query_sum($con, "bpticket", "net_total", "$drawcon  `deletes`='0' order by `id` DESC", "", "");


        $output .= ' <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6 class="">Bonus Ticket</h6>
                        <h2 class="mb-0 number-font" id="totalfree">AED ' . floatval($bpticket) . '</h2>';


        $sql = mysqli_query($con, "SELECT pt.id, ROUND(pt.rate) AS 'productAmt', IFNULL(ROUND(t.totalAmt),0) AS 'totalAmt' FROM `product` AS pt 
        LEFT JOIN (SELECT t.product_id, SUM(p.rate) AS 'totalAmt' FROM `ticket_lines` AS t
        LEFT JOIN product AS p ON p.id = t.product_id
        WHERE t.type = 'BP'  and t.draw_id = '$drawid' and t.deletes='0' AND p.deletes = '0' GROUP BY t.product_id) AS t ON t.product_id = pt.id
        WHERE pt.deletes = '0';");

        if (mysqli_num_rows($sql) > 0) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $output .= '<p class="aed-agent">AED  ' . intval($row['productAmt']) . ' - ' . $row['totalAmt'] . ' </p>';
                $total_Aed_Array['AED' . intval($row['productAmt'])] += $row['totalAmt'];
            }
        }



        $output .= '</div>
                </div>
            </div>
        </div>
        </div>';


        ///// Kiosk Ticket /////









        $kticket = select_query_sum($con, "kticket", "net_total", "$drawcon  `deletes`='0' order by `id` DESC", "", "");


        $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6 class="">Kiosk Ticket</h6>
                        <h2 class="mb-0 number-font" id="totalfree">AED ' . floatval($kticket) . '</h2>';

        $sql = mysqli_query($con, "SELECT pt.id, ROUND(pt.rate) AS 'productAmt', IFNULL(ROUND(t.totalAmt),0) AS 'totalAmt' FROM `product` AS pt 
        LEFT JOIN (SELECT t.product_id, SUM(p.rate) AS 'totalAmt' FROM `ticket_lines` AS t
        LEFT JOIN product AS p ON p.id = t.product_id
        WHERE t.type = 'KT'  and t.draw_id = '$drawid' and t.deletes='0' AND p.deletes = '0' GROUP BY t.product_id) AS t ON t.product_id = pt.id
        WHERE pt.deletes = '0';");

        if (mysqli_num_rows($sql) > 0) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $output .= '<p class="aed-agent">AED  ' . intval($row['productAmt']) . ' - ' . $row['totalAmt'] . ' </p>';
                $total_Aed_Array['AED' . intval($row['productAmt'])] += $row['totalAmt'];
            }
        }


        $output .= '</div>
                </div>
            </div>
        </div>
        </div>';


        ///// Coupon Ticket /////



        $cticket = select_query_sum($con, "cticket", "net_total", "$drawcon  `deletes`='0' order by `id` DESC", "", "");

        $output .= ' <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6 class="">Coupon Ticket</h6>
                        <h2 class="mb-0 number-font" id="totalfree">AED ' . floatval($cticket) . '</h2>';



        $sql = mysqli_query($con, "SELECT pt.id, ROUND(pt.rate) AS 'productAmt', IFNULL(ROUND(t.totalAmt),0) AS 'totalAmt' FROM `product` AS pt 
        LEFT JOIN (SELECT t.product_id, SUM(p.rate) AS 'totalAmt' FROM `ticket_lines` AS t
        LEFT JOIN product AS p ON p.id = t.product_id
        WHERE t.type = 'CT'  and t.draw_id = '$drawid' and t.deletes='0' AND p.deletes = '0' GROUP BY t.product_id) AS t ON t.product_id = pt.id
        WHERE pt.deletes = '0';");

        if (mysqli_num_rows($sql) > 0) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $output .= '<p class="aed-agent">AED  ' . intval($row['productAmt']) . ' - ' . $row['totalAmt'] . ' </p>';
                $total_Aed_Array['AED' . intval($row['productAmt'])] += $row['totalAmt'];
            }
        }


        $output .= '</div>
                </div>
            </div>
        </div>
        </div>';





        ///// Free Ticket //////



        $fticket = select_query_sum($con, "fticket", "net_total", "$drawcon $qcon `deletes`='0' order by `id` DESC", "", "");

        $output .= ' <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6 class="">Free Ticket</h6>
                        <h2 class="mb-0 number-font" id="totalfree">AED ' . floatval($fticket) . '</h2>';


        $sql = mysqli_query($con, "SELECT pt.id, ROUND(pt.rate) AS 'productAmt', IFNULL(ROUND(t.totalAmt),0) AS 'totalAmt' FROM `product` AS pt 
        LEFT JOIN (SELECT t.product_id, SUM(p.rate) AS 'totalAmt' FROM `ticket_lines` AS t
        LEFT JOIN product AS p ON p.id = t.product_id
        WHERE t.type = 'FT'  and t.draw_id = '$drawid' and t.deletes='0' AND p.deletes = '0' GROUP BY t.product_id) AS t ON t.product_id = pt.id
        WHERE pt.deletes = '0';");

        if (mysqli_num_rows($sql) > 0) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $output .= '<p class="aed-agent">AED  ' . intval($row['productAmt']) . ' - ' . $row['totalAmt'] . ' </p>';
                $total_Aed_Array['AED' . intval($row['productAmt'])] += $row['totalAmt'];
            }
        }

        $output .= '</div>
                </div>
            </div>
        </div>
        </div>';


        ////////////////// Deleted Ticket Count ///////////////////

        $total = 0;

        $dticket = mysqli_query($con, "SELECT SUM(total) AS total
                                    FROM (
                                        SELECT COUNT(id) AS total FROM `ticket` WHERE `draw_id` = '$drawid' AND `deletes` = '1'
                                        UNION ALL
                                        SELECT COUNT(id) AS total FROM `aticket` WHERE `draw_id` = '$drawid' AND `deletes` = '1'
                                        UNION ALL
                                        SELECT COUNT(id) AS `total` FROM `fticket` WHERE `draw_id` = '$drawid' AND `deletes` = '1'
                                        UNION ALL
                                        SELECT COUNT(id) AS `total` FROM `mticket` WHERE `draw_id` = '$drawid' AND `deletes` = '1'
                                        UNION ALL
                                        SELECT COUNT(id) AS `total` FROM `wticket` WHERE `draw_id` = '$drawid' AND `deletes` = '1'
                                        UNION ALL
                                        SELECT COUNT(id) AS `total` FROM `cticket` WHERE `draw_id` = '$drawid' AND `deletes` = '1'
                                        UNION ALL
                                        SELECT COUNT(id) AS `total` FROM `kticket` WHERE `draw_id` = '$drawid' AND `deletes` = '1'
                                        UNION ALL
                                        SELECT COUNT(id) AS `total` FROM `cpticket` WHERE `draw_id` = '$drawid' AND `deletes` = '1'
                                        UNION ALL
                                        SELECT COUNT(id) AS `total` FROM `bpticket` WHERE `draw_id` = '$drawid' AND `deletes` = '1'
                                    ) AS combined_counts;");
        $row = mysqli_fetch_assoc($dticket);
        $total += (int)$row['total'];




        $output .= ' <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6 class="">Deleted Ticket Count</h6>
                        <h2 class="mb-0 number-font" id="totalfree">' . $total . '</h2>
                    </div>
                </div>
            </div>
        </div>
        </div>';



        /////////////// CCAvenue Missing Tickets ////////////////
        // $ccorder_lookup = select_query($con, "ccorder_lookup", "", "`order_no` NOT IN (SELECT `transaction_id` FROM `ticket` WHERE `deletes` = '0' AND `draw_id` = '$drawid') AND `ticket` = 'NO' AND order_status = 'Shipped' ORDER BY `id` DESC", "", "");

        $ccorder_lookup = mysqli_query($con, "SELECT COUNT(id) AS 'totalCount' FROM ccorder_lookup WHERE `order_no` NOT IN (SELECT `transaction_id` FROM `ticket` WHERE `deletes` = '0' AND `draw_id` = '$drawid') AND `ticket` = 'NO' AND order_status = 'Shipped' ORDER BY `id` DESC;");

        // if (mysqli_num_rows($ccorder_lookup) > 0) {

        $ccorderLookUp = mysqli_fetch_all($ccorder_lookup, MYSQLI_ASSOC);

        $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6 class="">CCAvenue Missing Ticket</h6>
                        <h2 class="mb-0 number-font" id="totalfree">No <strong onclick="getUnTicket()" style="cursor: pointer;">' . intval($ccorderLookUp[0]['totalCount'] ?? 0) . '</strong></h2></div>
                </div>
            </div>
        </div>
        </div>';
        // }

        ///////////// Past Draw Winners Email Count ///////////////



        $sqlEmailSMS = mysqli_query($con, "SELECT g.*, v.smsCount FROM (SELECT COALESCE(wl.draw_id, d.max_draw_id) AS draw_id, COALESCE(COUNT(wl.id), 0) AS 'emailCount'
        FROM (
            SELECT MAX(id) AS max_draw_id
            FROM `draw` 
            WHERE `status` ='Completed' 
            AND `deletes` ='0'
        ) d
        LEFT JOIN winnerlist wl ON wl.`draw_id` = d.max_draw_id AND wl.`emaillog` = '1' AND wl.raffle_id IS NULL 
        GROUP BY COALESCE(wl.draw_id, d.max_draw_id)
        ORDER BY draw_id DESC) AS g 
        LEFT JOIN (SELECT COALESCE(wl.draw_id, d.max_draw_id) AS draw_id, COALESCE(COUNT(wl.id), 0) AS 'smsCount'
        FROM (
            SELECT MAX(id) AS max_draw_id
            FROM `draw` 
            WHERE `status` ='Completed' 
            AND `deletes` ='0'
        ) d
        LEFT JOIN winnerlist wl ON wl.`draw_id` = d.max_draw_id AND wl.`smslog` = '1' AND wl.raffle_id IS NULL 
        GROUP BY COALESCE(wl.draw_id, d.max_draw_id)
        ORDER BY draw_id DESC) AS v ON v.draw_id = g.draw_id;");

        // if (mysqli_num_rows($sqlEmailSMS) >0 ) {

        $smsEmailCount = mysqli_fetch_all($sqlEmailSMS, MYSQLI_ASSOC);

        $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-2">
                                    <h6 class="">Past Draw Winners Sent Email Count</h6>
                                    <h2 class="mb-0 number-font" id="totalfree">Total Email ' . intval($smsEmailCount[0]['emailCount']) . '</h2>';

        $output .= '<p>Just3 Ball - ' . $smsEmailCount[0]['emailCount'] . ' </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';



        ///////////// Past Draw Winners SMS Count ///////////////






        $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-2">
                                    <h6 class="">Past Draw Winners Sent SMS Count</h6>
                                    <h2 class="mb-0 number-font" id="totalfree">Total SMS ' . intval($smsEmailCount[0]['smsCount']) . '</h2>';

        $output .= '<p>Just3 Ball - ' . $smsEmailCount[0]['smsCount'] . ' </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
        // }
    }

    // newsignupkiosk

    if ($roll_id == 9) {

        $kiosknewsign = select_query_sum($con, "kticket", "user_id", "$drawcon  `deletes`='0' order by `id` DESC", "", "");

        $output .= ' <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6 class="">Kiosk Signup Ticket</h6>
                        <h2 class="mb-0 number-font" id="totalfree">No <strong onclick="getUnTicket()" style="cursor: pointer;">' . intval($kiosknewsign['nr']) . '</strong></h2></div>
                </div>
            </div>
        </div>
        </div>';
    }

    ///// Kiosk Ticket /////

    if ($roll_id == 9) {

        $kuticket = select_query_sum($con, "kticket", "net_total", "$drawcon  `deletes`='0' order by `id` DESC", "", "");

        $output .= ' <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6 class="">Kiosk Ticket</h6>
                        <h2 class="mb-0 number-font" id="totalfree">AED ' . floatval($kuticket) . '</h2>';

        $sql = mysqli_query($con, "SELECT pt.id, ROUND(pt.rate) AS 'productAmt', IFNULL(ROUND(t.totalAmt),0) AS 'totalAmt' FROM `product` AS pt 
        LEFT JOIN (SELECT t.product_id, SUM(p.rate) AS 'totalAmt' FROM `ticket_lines` AS t
        LEFT JOIN product AS p ON p.id = t.product_id
        WHERE t.type = 'KT'  and t.draw_id = '$drawid' and t.deletes='0' AND p.deletes = '0' GROUP BY t.product_id) AS t ON t.product_id = pt.id
        WHERE pt.deletes = '0';");

        if (mysqli_num_rows($sql) > 0) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $output .= '<p class="aed-agent">AED  ' . intval($row['productAmt']) . ' - ' . $row['totalAmt'] . ' </p>';
                $total_Aed_Array['AED' . intval($row['productAmt'])] += $row['totalAmt'];
            }
        }


        $output .= '</div>
                </div>
            </div>
        </div>
        </div>';
    }

    ///// Kiosk Ticket for overall count in kioskpage /////

    if ($roll_id == 9) {
        $overallkioskticket = select_query_sum($con, "kticket", "net_total", " `deletes`='0' order by `id` DESC", "", "");

        $output .= ' <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2">
                        <h6 class="">Overall Sales Count Kiosk</h6>
                        <h2 class="mb-0 number-font" id="totalfree">AED ' . floatval($overallkioskticket) . '</h2>';


        $sql = mysqli_query($con, "SELECT pt.id, ROUND(pt.rate) AS 'productAmt', IFNULL(ROUND(t.totalAmt),0) AS 'totalAmt' FROM `product` AS pt 
        LEFT JOIN (SELECT t.product_id, SUM(p.rate) AS 'totalAmt' FROM `ticket_lines` AS t
        LEFT JOIN product AS p ON p.id = t.product_id
        WHERE t.type = 'KT' and t.deletes='0' AND p.deletes = '0' GROUP BY t.product_id) AS t ON t.product_id = pt.id
        WHERE pt.deletes = '0';");

        if (mysqli_num_rows($sql) > 0) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $output .= '<p class="aed-agent">AED  ' . intval($row['productAmt']) . ' - ' . $row['totalAmt'] . ' </p>';
                $total_Aed_Array['AED' . intval($row['productAmt'])] += $row['totalAmt'];
            }
        }
        $output .= '</div>
                </div>
            </div>
        </div>
        </div>';
    }







    if ($roll_id == 11) {

        $output .= '<div style="text-align: center;">
                <br><br><br><br><br><br><br><br><br><br>
                <h15 style="font-weight: bold; font-size: 68px; color: #3498db; text-transform: uppercase; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">Welcome !!! </h15>
        </div>';
    }


    if (!in_array($roll_id, [1, 2, 7, 9, 11])) {
        $aticket = select_query_sum($con, "aticket", "net_total", "$drawcon $qcon `deletes`='0' order by `id` DESC", "", "");

        $output .= ' <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex"><div class="mt-2">
                        <h6 class="">Balance Points</h6>
                        <h2 class="mb-0 number-font" id="totalagent">' . $user['result'][0]['t_point'] . '</h2>
                    </div>
                </div></div> </div> </div>';

        $earnings = select_top_name($con, "user_register", "t_earning", "`id`='$_SESSION[memid]' and `deletes`='0'", "t_earning", "");

        $output .= ' <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                    <div class="d-flex">
                            <div class="mt-2">
                                <h6 class="">Total Earnings</h6>
                                <h2 class="mb-0 number-font" id="totalagent">' . $earnings . '</h2>
                            </div>
                        </div>
                    </div>
                </div>
                </div>';
    }



    $drawout = '';

    if (!in_array($roll_id, [7, 9, 11])) {

        // $total_amt = floatval($mticket) + floatval($oticket) + floatval($apticket) + floatval($aticket) + floatval($wticket) + floatval($cticket) + floatval($cpticket) + floatval($kticket) + floatval($bpticket);
        $total_amt = floatval($mticket ?? 0) + floatval($oticket ?? 0) + floatval($apticket ?? 0) + floatval($aticket ?? 0) + floatval($wticket ?? 0) + floatval($cticket ?? 0) + floatval($cpticket ?? 0) + floatval($kticket ?? 0) + floatval($bpticket ?? 0);


        $drawout .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="mt-2">
                                <h6 class="" id="drawname">' . $drawname . '</h6>
                                <h2 class="mb-0 number-font" id="total_amt">AED ' . $total_amt . '</h2>';

        if ($roll_id == 1) {
            if ($product['nr'] > 0) {
                foreach ($product['result'] as $key => $value) {
                    $drawout .= '<p>AED  ' . intval($value['rate']) . ' - ' . $total_Aed_Array['AED' . intval($value['rate'])] . ' </p>';
                }
            }
        }

        $drawout .= '</div>
                        </div>
                    </div>
                </div>
            </div>';
    }







    if (in_array($roll_id, [7])) {

        $total_Coupon = select_query($con, "couponcode", "", "`c_createdfor` = '$_SESSION[memid]' AND `deletes` = '0'", "", "");
        $used_Coupon = select_query($con, "couponcode", "", "`c_createdfor` = '$_SESSION[memid]' AND `c_limit` = `c_use_count`  AND `deletes` = '0'", "", "");
        $not_used_Coupon = select_query($con, "couponcode", "", "`c_createdfor` = '$_SESSION[memid]' AND `c_limit` != `c_use_count` AND `c_limit` > `c_use_count`  AND `deletes` = '0'", "", "");


        $output .= ' <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mt-2"><h6 class="">Total Coupons</h6><h2 class="mb-0 number-font" id="totalfree">No <strong style="cursor: pointer;">' . intval($total_Coupon['nr']) . '</strong></h2></div></div>
                </div></div></div>';


        $output .= ' <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="mt-2"><h6 class="">Used Coupons</h6><h2 class="mb-0 number-font" id="totalfree">No <strong style="cursor: pointer;">' . intval($used_Coupon['nr']) . '</strong></h2></div></div>
                        </div></div></div>';


        $output .= ' <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="mt-2"><h6 class="">Unused Coupons</h6><h2 class="mb-0 number-font" id="totalfree">No <strong style="cursor: pointer;">' . intval($not_used_Coupon['nr']) . '</strong></h2></div></div>
                                </div></div></div>';
    }


    if (in_array($roll_id, [9])) {
        $currentUser = $_SESSION['memid'];
        $query = "SELECT  sum(kt.net_total) AS 'total' FROM `kticket` AS kt
                INNER JOIN user_register u ON u.id = kt.user_id
                INNER JOIN (SELECT ticket_id, GROUP_CONCAT(`my3number` SEPARATOR  ', ') AS 'my3number', GROUP_CONCAT(`raffle_id` SEPARATOR  ', ') AS 'raffle_id' FROM `ticket_lines`
                WHERE `deletes` = '0' AND `type` = 'KT' GROUP BY ticket_id) AS tl
                ON tl.ticket_id = kt.id
                INNER JOIN draw d ON d.id = kt.draw_id
                WHERE kt.`deletes` = '0' AND kt.`kiosk_id` in (SELECT `kiosk_id` FROM `kiosk_machines` WHERE `ownedby` = '$currentUser') AND
                kt.`draw_id` = '$drawid' AND kt.`id` != '0';";

        $kticket = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($kticket);

        $total_amt = (int)$row['total'];

        $drawout .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="mt-2">
                                <h6 class="" id="drawname">' . $drawname . '</h6>
                                <h2 class="mb-0 number-font" id="total_amt">AED ' . $total_amt . '</h2>';



        $drawout .= '</div>
                        </div>
                    </div>
                </div>
            </div>';
    }



    if ($_SESSION['memid'] != '') {
        $result['type'] = 1;
        $result['output'] = $drawout . $output;
    } else {
        $result['type'] = 0;
        $result['result'] = '<div class="alert alert-danger" role="alert">Could not get data!</div>';
    }

    echo json_encode($result);
} else if ($method == 'collect_trans_earnings') {



    $result = [];



    $total_amt = "";



    if ($_SESSION['memid'] != 1) {



        $agent = "`agent_id` = '$_SESSION[memid]' AND";
    } else {



        $agent = "";
    }



    $points = select_top_name($con, "user_register", "t_point", "`id`='$_SESSION[memid]' and `deletes`='0'", "t_point", "");



    $earnings = select_top_name($con, "user_register", "t_earning", "`id`='$_SESSION[memid]' and `deletes`='0'", "t_earning", "");



    $aticket_Trans = select_query($con, "aticket", "", "$agent `deletes`='0' ORDER BY `id` DESC", "1", "");



    if ($aticket_Trans['nr'] > 0) {



        foreach ($aticket_Trans['result'] as $key => $value) {



            $inv = $value['invoice_no'];



            $earn_Trans = select_query($con, "earning_transaction", "", "`invoice_id` = '$inv' AND `deletes`='0' ORDER BY `id` DESC", "1", "");



            if ($earn_Trans['nr'] > 0) {



                $total_amt += floatval($earn_Trans['result'][0]['amount']);
            }
        }



        $result['myearnings'] = $earnings;



        $result['mypoint'] = $points;



        $result['total_amt'] = $total_amt;



        $result['type'] = 1;
    } else {



        $result['type'] = 0;



        $result['result'] = 'Could not get data!';
    }



    echo json_encode($result);
} else if ($method == "onllin_earnings") {



    $result = [];



    $contype = '';



    $type = 'OT';



    $da = '';



    $now = date('Y-m-d');

    $formdate = BlockSQLInjectionforagent($_POST["formdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);





    if ($formdate != '' && $todate != '') {



        $da = 'DESC';



        $fd = date("Y-m-d", strtotime($formdate));



        $td = date("Y-m-d", strtotime($todate));



        $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {



        $da = 'DESC';



        $contype = "`createdon` LIKE '%$now%' AND";
    }



    if ($_SESSION['memid'] != 1) {



        $agent = "`agent_id` = '$_SESSION[memid]' AND";
    } else {



        $agent = "";
    }



    $Ticket_lines = select_query($con, "ticket_lines", "", "$contype `type`='$type' and `deletes`='0'", "", "");



    if ($Ticket_lines['nr'] > 0) {



        foreach ($Ticket_lines['result'] as $key => $value) {



            $ticket_id = $value['ticket_id'];



            $proid = $value['product_id'];



            $purchase_datetime = select_top_name($con, "ticket", "purchase_datetime", "`id`='$ticket_id' and `deletes`='0'", "purchase_datetime", "");



            $ticket_no = select_top_name($con, "ticket", "ticket_no", "`id`='$ticket_id' and `deletes`='0'", "ticket_no", "");



            $user_id = select_top_name($con, "ticket", "user_id", "`id`='$ticket_id' and `deletes`='0'", "user_id", "");



            $cusname = select_top_name($con, "user_register", "name", "`id`='$user_id' and `deletes`='0'", "name", "");



            $mobile = select_top_name($con, "user_register", "mobile", "`id`='$user_id' and `deletes`='0'", "mobile", "");



            $email = select_top_name($con, "user_register", "email", "`id`='$user_id' and `deletes`='0'", "email", "");



            $proamt = select_top_name($con, "product", "rate", "`id`='$proid' and `deletes`='0'", "rate", "");



            $result['result'][] = array("proamt" => $proamt, "RaffleID" => $value['raffle_id'], "My3Numbers" => $value['my3number'], "email" => $email, "mobile" => $mobile, "cusname" => $cusname, "ticketno" => $ticket_no, "purdate" => date("d-M-Y g:i a", strtotime($purchase_datetime)));
        }



        $result['type'] = 1;
    } else {



        $result['type'] = 0;



        $result['result'] = 'No Datas Found!';
    }



    echo json_encode($result);
} else if ($method == "payment_history") {



    $result = [];

    $contype = '';

    $type = 'OT';

    $da = '';

    $gt = '';

    $now = date('Y-m-d');

    $formdate = $_POST['formdate'];

    $todate = $_POST['todate'];

    if ($formdate != '' && $todate != '') {

        $da = 'DESC';

        $fd = date("Y-m-d", strtotime($formdate));

        $td = date("Y-m-d", strtotime($todate));

        $contype = "`payment_history`.`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' order by `payment_history`.`id` DESC";

        $gt = "REQUIRED GENERATE ( $fd TO $td )";
    } else {

        $da = 'DESC';

        $contype = "`payment_history`.`createdon` LIKE '%$now%'  order by `payment_history`.`id` DESC ";

        $gt = "REQUIRED GENERATE ( $now )";
    }



    if ($_SESSION['memid'] != 1) {

        $agent = "`agent_id` = '$_SESSION[memid]' AND";
    } else {

        $agent = "";
    }



    $i = 0;

    $payment_history = mysqli_query($con, "SELECT `payment_history`.`ip`, `payment_history`.`status`,  `user_register`.`name`, `user_register`.`lastlogin`,`user_register`.`created_at`, `payment_history`.`transaction_id`, `user_register`.`mobile`, `user_register`.`nationality`, `user_register`.`residinglocation`, `user_register`.`email`,  `payment_history`.`response`, `payment_history`.`gateway`, `payment_history`.`createdon`,  `payment_history`.`pay_re_status` FROM `payment_history`  INNER JOIN user_register ON payment_history.user_id = user_register.id WHERE `payment_history`.`pay_re_status` != 'WALLET' AND $contype");

    while ($row = mysqli_fetch_assoc($payment_history)) {

        $gateway = '';

        $transaction_id = $row['transaction_id'];

        $data = json_decode($row['response'], true);

        $ticket_Num = '';

        if ($row['gateway'] == '' ||  $row['gateway'] == 'network') {

            $total = intval($data['amount']['value']) / 100;

            $gateway = '<span style="color: red; font-weight: bolder;">Network</span>';
        } else {

            $total = $data['amount'];

            $gateway = '<span style="color: blue; font-weight: bolder;">CCAvenue</span>';
        }



        $action = '';

        if ($row['status'] != 0) {

            $action .= '<div class="g-2">';

            $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' . $transaction_id . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span class="fa fa-file-text-o fs-14"></span></a>';

            $action .= '<a target="_blank" href="' . $baseurl . 'ticket-view/' . $transaction_id . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span class="fa fa-files-o fs-14"></span></a>';

            $action .= '</div>';



            $ticket_D = mysqli_query($con, "SELECT `ticket_no` FROM `ticket` WHERE `transaction_id` LIKE '$transaction_id' and `deletes`='0' ORDER BY `id` DESC LIMIT 1");

            $ticketno = mysqli_fetch_array($ticket_D);

            $ticket_Num = $ticketno['ticket_no'];
        } else {

            if ($row['gateway'] == '' ||  $row['gateway'] == 'network') {

                $action .= '<div class="g-2">';

                $action .= '<a target="_blank"  class="btn text-primary btn-sm" data-bs-toggle="tooltip" onclick="requery(' . "'$transaction_id'" . ')" data-bs-original-title="View Receipt"><span class="fa fa-check-circle-o"> Run</span></a>';

                $action .= '</div>';

                // if ($ticket_Num == '') {

                //     $ticket_D1 = mysqli_query($con, "SELECT `event` FROM `orders` WHERE `event` = 'CAPTURED' and `transaction_id`='$transaction_id' ORDER BY `id` DESC LIMIT 1");

                //     $ticketno1 = mysqli_fetch_array($ticket_D1);

                //     $ticket_Num1 = $ticketno1['event'];

                //     if ($ticket_Num != '' && $ticket_Num1 == 'CAPTURED') {

                //         $value['pay_re_status'] = 'generate';

                //         $i++;

                //     }

                // }

            } else {
            }
        }



        $result[] = array("gateway" => $gateway, "action" => $action, "amt" => $total, "email" => $row['email'], "mobile" => $row['mobile'], "transid" => $transaction_id, "ticketno" => $ticket_Num, "stauts" => $row['pay_re_status'], "date" => date("d-M-Y g:i a", strtotime($row['createdon'])));
    }





    echo json_encode($result);
} else if ($method == "get_Generate_count") {



    $result = [];



    $contype = '';



    $type = 'OT';



    $da = '';



    $gt = '';



    $now = date('Y-m-d');

    $formdate = $_POST['formdate'];

    $todate = $_POST['todate'];

    if ($formdate != '' && $todate != '') {

        $da = 'DESC';

        $fd = date("Y-m-d", strtotime($formdate));

        $td = date("Y-m-d", strtotime($todate));

        $contype = "`payment_history`.`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' order by `payment_history`.`id` DESC";

        $gt = "REQUIRED GENERATE ( $fd TO $td )";
    } else {

        $da = 'DESC';

        $contype = "`payment_history`.`createdon` LIKE '%$now%'  order by `payment_history`.`id` DESC ";

        $gt = "REQUIRED GENERATE ( $now )";
    }





    if ($_SESSION['memid'] != 1) {



        $agent = "`agent_id` = '$_SESSION[memid]' AND";
    } else {



        $agent = "";
    }

    $i = 0;

    $payment_history = mysqli_query($con, "SELECT `payment_history`.`ip`, `payment_history`.`status`,  `user_register`.`name`, `user_register`.`lastlogin`,`user_register`.`created_at`, `payment_history`.`transaction_id`, `user_register`.`mobile`, `user_register`.`nationality`, `user_register`.`residinglocation`, `user_register`.`email`,  `payment_history`.`response`, `payment_history`.`gateway`, `payment_history`.`createdon`,  `payment_history`.`pay_re_status` FROM `payment_history`  INNER JOIN user_register ON payment_history.user_id = user_register.id WHERE `payment_history`.`pay_re_status` != 'WALLET' AND $contype");

    while ($row = mysqli_fetch_assoc($payment_history)) {

        $transaction_id = $row['transaction_id'];

        if ($row['status'] != 0) {
        } else {

            if ($row['gateway'] == '' ||  $row['gateway'] == 'network') {

                $ticket_D = mysqli_query($con, "SELECT `ticket_no` FROM `ticket` WHERE `transaction_id` LIKE '$transaction_id' and `deletes`='0' ORDER BY `id` DESC LIMIT 1");

                $ticketno = mysqli_fetch_array($ticket_D);

                $ticket_Num = $ticketno['ticket_no'];

                if ($ticket_Num == '') {

                    if ($row['status'] != 0) {

                        $ticket_D = mysqli_query($con, "SELECT `event` FROM `orders` WHERE `event` = 'CAPTURED' and `transaction_id`='$transaction_id' ORDER BY `id` DESC LIMIT 1");

                        $ticketno = mysqli_fetch_array($ticket_D);

                        $ticket_Num = $ticketno['event'];

                        if ($ticket_Num != '' && $ticket_Num == 'CAPTURED') {

                            // $value['pay_re_status'] = 'generate';

                            $i++;
                        }
                    }
                }
            }
        }
    }



    $result['generate'] = $gt . ' - ' . $i;

    $result['type'] = 1;







    echo json_encode($result);
} else if ($method == "getleader_data") {



    $result = [];



    if ($_SESSION['memid'] != 1) {



        $userid = $_SESSION['memid'];
    }



    $created_by = select_top_name($con, "user_register", "created_by", "`id`='$userid' and `deletes`='0'", "created_by", "");



    $leader = select_query($con, "user_register", "", "`id`='$created_by' and `deletes`='0'", "", "");



    if ($leader['nr'] > 0) {



        $reoutput = ' <div class="row">







        <div class="col-sm-12">



    <form class="login100-form validate-form">







        <div class="wrap-input100 validate-input input-group">



            <a href="javascript:void(0)" class="input-group-text bg-white text-muted">



                <i class="side-menu__icon fa fa-money"></i>



            </a>



            <input class="input100 border-start-0 ms-0 form-control" id="totpoint" oninput="this.value = this.value.replace(/[^0-9]/g, ' . "''" . ');" type="text" placeholder="Enter Points">



        </div>



        </div>



        <div class="row">



    <div class="col-12">



        <div class="form-label">Leader Name : <strong id="leanername">' . $leader['result'][0]['name'] . ' ' . $leader['result'][0]['lname'] . '</strong></div>



        <div class="form-label">ID : <strong id="leanerid">' . $leader['result'][0]['id'] . '</strong></div>



    </div>



        </div>



    </form>



    </div>';



        $result['pointbtn'] = '<button class="btn btn-secondary" onclick="pointrequest()">Request</button>';



        $result['reoutput'] = $reoutput;



        $result['type'] = 1;
    } else {



        $result['type'] = 0;



        $result['result'] = 'Could not get details!';
    }



    echo json_encode($result);
} else if ($method == 'point_request') {



    $result = [];



    $point = $_POST['point'];



    if ($_SESSION['memid'] != 1) {



        $userid = $_SESSION['memid'];
    }



    PE:



    $request_id = 'PR' . uniqid(15) . date('his');



    $req_check = select_query($con, "point_request", "", "`request_id`='$request_id' and `deletes`='0'", "", "");



    if ($req_check['nr'] > 0) {



        goto PE;
    }



    if ($point != '') {



        $created_by = select_top_name($con, "user_register", "created_by", "`id`='$userid' and `deletes`='0'", "created_by", "");



        $leader = select_query($con, "user_register", "", "`id`='$created_by' and `deletes`='0'", "", "");



        if ($leader['nr'] > 0) {



            $leaderid = $leader['result'][0]['id'];



            $point_arr = array("from_id" => $userid, "request_id" => $request_id, "to_id" => $leaderid, "points" => $point, "not_type" => '0', "transaction_status" => '0', "deletes" => '0', "createdon" => $dubaidate_time);



            $point_ins = insert($con, "point_request", "", $point_arr, "", "", "");



            if ($point_ins['id'] != '') {



                $result['type'] = 1;



                $result['result'] = 'Request Sent';
            }
        } else {



            $result['type'] = 0;



            $result['result'] = 'Leader Not Found!';



            goto Fi;
        }
    } else {



        $result['type'] = 1;



        $result['result'] = 'Please Enter Points!';



        goto Fi;
    }



    Fi:



    echo json_encode($result);
} else if ($method == 'pointTransferDetails') {



    $result = [];



    $pointrequestid = $_POST['pointrequestid'];



    if ($pointrequestid != '') {



        if ($_SESSION['memid'] != '') {



            $userid = $_SESSION['memid'];
        }



        $balance_point = select_top_name($con, "user_register", "t_point", "`id`='$userid' and `deletes`='0'", "t_point", "");



        if (intval($balance_point) > 0) {



            $point_req = select_query($con, "point_request", "", "`to_id` = '$userid' and `request_id` = '$pointrequestid' and `transaction_status` = '0' and `deletes`='0' ORDER BY `id` DESC", "", "");



            if ($point_req['nr'] > 0) {



                $to_id = $point_req['result'][0]['from_id'];



                $req_name = select_top_name($con, "user_register", "name", "`id`='$to_id' and `deletes`='0'", "name", "");
            }



            if ($req_name != '' && $balance_point != '') {



                $output = '';



                $output .= '   <div class="row">



                <div class="col-12">



                    <div class="form-label">Account Balance : <strong>' . $balance_point . '</strong></div>



                    <div class="form-label">To Name : <strong>' . $req_name . '</strong></div>



                </div>



            </div>';



                $output .= '<div class="col-sm-12">



                <form class="login100-form validate-form">



                    <div class="wrap-input100 validate-input input-group">



                        <a href="javascript:void(0)" class="input-group-text bg-white text-muted">



                            <i class="side-menu__icon fa fa-money"></i>



                        </a>



                        <input class="input100 border-start-0 ms-0 form-control" id="topoint" type="text" placeholder="Enter Points">



                    </div>



            </div>';



                $btn = '<button class="btn btn-secondary" onclick="transfertopoint(' . "'$pointrequestid'" . ')">Transfer</button>';



                $result['btn'] = $btn;



                $result['result'] = $output;



                $result['type'] = 1;
            } else {



                $result['result'] = '<div class="alert alert-danger">Couldn\'t get details!</div>';



                $result['type'] = 0;



                goto Gi;
            }
        } else {



            $result['result'] = '<div class="alert alert-danger">Your account balance is 0</div>';



            $result['type'] = 0;



            goto Gi;
        }
    } else {



        $result['result'] = '<div class="alert alert-danger">Couldn\'t not find Request!</div>';



        $result['type'] = 0;



        goto Gi;
    }



    Gi:



    echo json_encode($result);
} else if ($method == 'transfertopoint') {



    $result = [];



    $pointrequestid = $_POST['pointrequestid'];



    $topoint = $_POST['topoint'];



    if ($pointrequestid != '') {



        if ($topoint != '') {



            if ($_SESSION['memid'] != '') {



                $userid = $_SESSION['memid'];
            }



            $balance_point = select_top_name($con, "user_register", "t_point", "`id`='$userid' and `deletes`='0'", "t_point", "");



            if (intval($balance_point) > 0) {



                if (intval($balance_point) > intval($topoint)) {
                } else {



                    $btn = '<button class="btn btn-secondary" onclick="transfertopoint(' . "'$pointrequestid'" . ')">Transfer</button>';



                    $result['tbtn'] = $btn;



                    $result['result'] = '<div class="alert alert-danger">Your Point Balance is Low, Refill your Points Before Starting Transaction.</div>';



                    $result['type'] = 0;



                    goto GHi;
                }



                $point_req = select_query($con, "point_request", "", "`request_id` = '$pointrequestid' and `transaction_status` = '0' and `deletes`='0' ORDER BY `id` DESC", "", "");



                if ($point_req['nr'] > 0) {



                    $to_id = $point_req['result'][0]['to_id'];



                    $PT_id = $point_req['result'][0]['id'];



                    $req_name = select_top_name($con, "user_register", "name", "`id`='$to_id' and `deletes`='0'", "name", "");



                    $t_point = select_top_name($con, "user_register", "t_point", "`id`='$to_id' and `deletes`='0'", "t_point", "");



                    $UT_point = intval($t_point) - intval($topoint);



                    $form_id = $point_req['result'][0]['from_id'];



                    $from_t_point = select_top_name($con, "user_register", "t_point", "`id`='$form_id' and `deletes`='0'", "t_point", "");



                    $balancepoint = intval($from_t_point) + intval($topoint);



                    $Arrd = array("from_id" => "$to_id", "type" => "request", "points" => $topoint, "from_opening" => $t_point, "from_closing" => $UT_point, "to_id" => $form_id, "to_opening" => $from_t_point, "to_closing" => $balancepoint, "invoice_id" => $PT_id, "createdon" => $dubaidate_time);



                    $point_trans = insert($con, "points_transaction", "", $Arrd, "", "", "");



                    $point_arr1 = array("t_point" => $UT_point);



                    $point_update1 = update($con, "user_register", "`id` = '$to_id' and `deletes`='0'", $point_arr1, "", "", "", "");



                    $errors = $point_update1['errors'];



                    if ($errors != "") {



                        $result["type"] = "0";



                        $result["result"] = $errors;
                    } else {



                        $point_arr2 = array("t_point" => $balancepoint);



                        $point_update2 = update($con, "user_register", "`id` = '$form_id' and `deletes`='0'", $point_arr2, "", "", "", "");



                        $errors = $point_update2['errors'];



                        if ($errors != "") {



                            $result["type"] = "0";



                            $result["result"] = $errors;
                        } else {



                            $point_arr3 = array("transaction_status" => '1');



                            $point_update3 = update($con, "point_request", "`request_id` = '$pointrequestid' and `deletes`='0'", $point_arr3, "", "", "", "");



                            $errors = $point_update3['errors'];



                            if ($errors != "") {



                                $result["type"] = "0";



                                $result["result"] = $errors;
                            } else {



                                $result['result'] = '<div class="alert alert-success">Success</div>';



                                $result['type'] = 1;
                            }
                        }
                    }
                }
            } else {



                $result['result'] = '<div class="alert alert-danger">Your account balance is 0</div>';



                $result['type'] = 0;



                goto GHi;
            }
        } else {



            $result['result'] = '<div class="alert alert-danger">Enter the point.</div>';



            $result['type'] = 0;



            goto GHi;
        }
    } else {



        $result['result'] = '<div class="alert alert-danger">Couldn\'t not find Request!</div>';



        $result['type'] = 0;



        goto GHi;
    }



    GHi:



    echo json_encode($result);
} else if ($method == 'getadmin_data') {



    $result = [];



    $my_t_earn = select_top_name($con, "user_register", "t_earning", "`id`='$_SESSION[memid]' and `deletes`='0'", "t_earning", "");



    if (floatval($my_t_earn) > 0) {



        $admin = select_query($con, "user_register", "", "`id`='1' and `deletes`='0'", "", "");



        if ($admin['nr'] > 0) {



            $output = '';



            $output .= ' <form class="login100-form validate-form">



            <div class="wrap-input100 validate-input input-group">



                <p>My Balance: </p><span>AED ' . $my_t_earn . '</span>



            </div>



            <div class="wrap-input100 validate-input input-group">



                <p>Request To: </p><span>' . $admin['result'][0]['name'] . '</span>



            </div>



            <div class="wrap-input100 validate-input input-group">



                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">



                    <i class="side-menu__icon fa fa-money"></i>



                </a>



                <input class="input100 border-start-0 ms-0 form-control" id="withdrawamt" oninput="this.value = this.value.replace(/[^0-9]/g, ' . "''" . ');" type="text" placeholder="Enter Amount">



            </div>







        </form>';



            $withdraw = '<button class="btn btn-secondary" onclick="reqwithdraw()">Request</button>';



            $result['withdraw'] = $withdraw;



            $result['result'] = $output;



            $result['type'] = '1';
        } else {



            $result['type'] = '0';



            $result['result'] = 'Admin Not Found!';
        }
    } else {



        $result['type'] = '0';



        $result['result'] = '<div class="alert alert-danger" role="alert">Your account balance is zero(0)</div>';
    }



    echo json_encode($result);
} else if ($method == 'req_with_draw') {



    $result = [];



    $amount = $_POST['amount'];



    RE:



    $request_id = 'WR' . uniqid(15) . date('his');



    $req_check = select_query($con, "withdraw_request", "", "`request_id`='$request_id' and `deletes`='0'", "", "");



    if ($req_check['nr'] > 0) {



        goto RE;
    }



    $my_t_earn = select_top_name($con, "user_register", "t_earning", "`id`='$_SESSION[memid]' and `deletes`='0'", "t_earning", "");



    if (floatval($my_t_earn) > floatval($amount)) {



        $withdraw_arr = array("request_id" => $request_id, "from_id" => $_SESSION['memid'], "to_id" => '1', "amount" => $amount, "status" => '0', "deletes" => '0', "createdon" => $dubaidate_time);



        $with_draw_ins = insert($con, "withdraw_request", "", $withdraw_arr, "", "", "");



        $wd_id = select_top_name($con, "withdraw_request", "id", "`request_id`='$request_id' and `deletes`='0'", "id", "");



        if ($wd_id != '') {



            $result['requestid'] = $wd_id;



            $result['type'] = '1';



            $result['result'] = '<div class="alert alert-success" role="alert">Request has been sent</div>';
        }
    } else {



        $result['type'] = '0';



        $result['result'] = '<div class="alert alert-danger" role="alert">Your account balance is Low</div>';
    }



    echo json_encode($result);
} else if ($method == 'list_with_draw_request') {

    $result = [];
    $contype = '';

    $da = '';
    $type = '';
    $now = date('Y-m-d');
    $formdate = BlockSQLInjectionforagent($_POST["formdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);

    // $formdate = $_POST['formdate'];
    // $todate = $_POST['todate'];

    $status = BlockSQLInjectionforagent($_POST["status"]);
    $trans_mode = BlockSQLInjectionforagent($_POST["trans_mode"]);

    // $status = $_POST['status'];
    // $trans_mode = $_POST['trans_mode'];

    if ($status == 'all') {
        $statuscon = "`status` != '' AND";
        
    } else {



        $statuscon = "`status` = '$status' AND";
    }



    if ($trans_mode == 'all') {



        $trans_mode_con = "";
    } else {

        $trans_mode_con = "`trans_mode` = '$trans_mode' AND";
    }

    if ($formdate != '') {

        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($formdate));
        $td = ($todate != '') ? date("Y-m-d", strtotime($todate)) : $now;

        $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {

        $da = 'DESC';
        // $contype = "`createdon` LIKE '%$now%' AND";
        $contype = "";
    }
    $permission = array(3, 6, 5);

    $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'", "roll_id", "");

    if (in_array($roll_id, $permission)) {
        $agent = "`from_id` = '$_SESSION[memid]' AND";
    } else {
        $agent = "";
    }
    $with_his = select_query($con, "withdraw_request", "", " $trans_mode_con $agent $statuscon $contype  `deletes`='0' ORDER BY `id` DESC", "", "");



    if ($with_his['nr'] > 0) {

        foreach ($with_his['result'] as $key => $value) {
            
            $from_name1 = select_top_name($con, "user_register", "name", "`id`='$value[requested_by]' and `deletes`='0'", "name", "");
            $from_name2 = select_top_name($con, "user_register", "lname", "`id`='$value[requested_by]' and `deletes`='0'", "lname", "");
            $from_name= $from_name1 .' '.$from_name2;
            
            // $to_name = select_top_name($con, "user_register", "name", "`id`='$value[to_id]' and `deletes`='0'", "name", "");
            
            $mobile = select_top_name($con, "user_register", "mobile", "`id`='$value[requested_by]' and `deletes`='0'", "mobile", "");
            
            $address = select_top_name($con, "user_register", "address", "`id`='$value[requested_by]' and `deletes`='0'", "address", "");
            $city = select_top_name($con, "user_register", "city", "`id`='$value[requested_by]' and `deletes`='0'", "city", "");
            // var_dump('hellow');die;
            $t_earning = select_top_name($con, "user_register", "walletBalance", "`id`='$value[requested_by]' and `deletes`='0'", "walletBalance", "");
            $idProFront = select_top_name($con, "user_register", "idProFront", "`id`='$value[requested_by]' and `deletes`='0'", "idProFront", "");
            $idProBack = select_top_name($con, "user_register", "idProBack", "`id`='$value[requested_by]' and `deletes`='0'", "idProBack", "");
            
            $From_roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$value[requested_by]' and `deletes`='0'", "roll_id", "");
            
            if ($From_roll_id != 0) {
                $type = select_top_name($con, "role", "name", "`id`='$From_roll_id'", "name", "");
                
            } else {
                
                $type = 'customer';
            }

            $request_id = $value['request_id'];
            $attachmentimg = $value['attachment_url'];
            
            // var_dump($request_id,$attachmentimg);die;

            $path = (strpos($attachmentimg, "nationaldraw") === 0) ?  constant('assetURL')  . $attachmentimg : $baseurl . $attachmentimg;
            
            $path1 =  constant('assetURL')  . $idProFront ;
            $path2 =  constant('assetURL')  . $idProBack ;
            
            // var_dump($path1,$path2);die;

            $status = '';
            $action = '';
            if ($value['status'] == 0) {

                if ($roll_id == 1 || $roll_id == 2 || $roll_id == 4) {

                    $action .= '<a class="btn text-danger btn-sm transfer" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-money" onclick="withdrawtransfer(' . "'$request_id'" . ')"> Transfer</span></a>';

                    $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-money" onclick="withdrawDecline(' . "'$request_id'" . ')"> Reject</span></a>';
                    
                    $action .= '<a class="btn text-danger btn-sm transfer" style="cursor: pointer;" data-bs-original-title="Delete">
<span class="fa fa-picture-o" style="color: #a4d47a;" onclick="user_id_image(' . "'$request_id', '$path1', '$path2'" . ')"> Id Proof</span></a>';
                    // $action .= '<a class="btn text-danger btn-sm" data-toggle="tooltip" title="Declaration!" target="_blank"  href="acknowledgement_receipt.php?id=' . $value['id'] . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-wpforms"></span></a>';
                } else {

                    $action .= '';
                }
            }

            $status = '';

            if ($value['status'] == 1) {

                $status = 'Success';
            } else if ($value['status'] == 2) {
                $status = 'Reject';
            } else if ($value['status'] == 0) {

                $status = 'Process';
            }

            if ($value['status'] == 1 || $value['status'] == 2) {

                $action .= '<a class="btn text-danger btn-sm transfer" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-edit" style="color: blue;" onclick="editWithDrawDetails(' . "'$request_id'" . ')"> Edit</span></a>';

                $action .= '<a class="btn text-danger btn-sm transfer" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-eye" style="color: #ff0000;" onclick="Bankdeatils(' . "'$request_id'" . ')"> Bank Details</span></a>';
                
                //  $action .= '<a class="btn text-danger btn-sm transfer" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-picture-o" style="color: #a4d47a;" onclick="user_id_image(' . "'$request_id'" . ')"> User Id </span></a>';
                $action .= '<a class="btn text-danger btn-sm transfer" style="cursor: pointer;" data-bs-original-title="Delete">
<span class="fa fa-picture-o" style="color: #a4d47a;" onclick="user_id_image(' . "'$request_id', '$path1', '$path2'" . ')"> Id Proof </span></a>';

               


                if ($attachmentimg != '') {

                    $action .= ' <a href="' . $path . '"  target="blank"><button class="btn"><i  style="color: lime;" class="fa fa-eye"></i> View</button></a>';
                }
            }
// var_dump($path);die;
            $result[] = array("wbalance" => $t_earning, "trans_mode" => $value['trans_mode'],"Request_Type" => $value['cus_prefer'], "trans_id" => $value['transaction_id'], "trans_date" => $value['trans_date'], "type" => ucwords($type), "fromname" => ucwords($from_name), "id" => str_pad($value['id'], 7, "0", STR_PAD_LEFT), "date" => date("d-M-Y g:i a", strtotime($value['createdon'])), "amt" => $value['amount'], "nation" => $value['nationality'], "bank" => $value['bank_name'], "acount_num" => $value['acc_no'], "iban" => $value['iban_code'], "swift" => $value['swiftcode'], "location" => $value['residinglocation'], "request_id" => $request_id, "toname" => ucwords($to_name), "status" => $status, "mobile" => $mobile, "address" => $address, "region" => $city, "action" => $action);
        }

        // $result['type'] = 1;
    } else {



        // $result['type'] = 0;



    }



    echo json_encode($result);
} else if ($method == 'withdraw_transfer_verify') {



    $result = [];
    $request = $_POST['request'];
    $wd_data = select_query($con, "withdraw_request", "", "`request_id`='$request' and `status`='0' and `deletes`='0'", "", "");
    if ($wd_data['nr'] > 0) {

        $from_id = $wd_data['result'][0]['requested_by'];

        $cus_prefer = $wd_data['result'][0]['cus_prefer'];
        $from_name1 = select_top_name($con, "user_register", "name", "`id`='$from_id' and `deletes`='0'", "name", "");
        $from_name2 = select_top_name($con, "user_register", "lname", "`id`='$from_id' and `deletes`='0'", "lname", "");
        $from_name = $from_name1 .' '.$from_name2;
        $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$from_id' and `deletes`='0'", "roll_id", "");
        $mobile = select_top_name($con, "user_register", "mobile", "`id`='$from_id' and `deletes`='0'", "mobile", "");
        $email = select_top_name($con, "user_register", "email", "`id`='$from_id' and `deletes`='0'", "email", "");
        $output = '';

        $output .= '<form class="login100-form validate-form">
                        <div class="row" style="justify-content: center;">
                                <div class="row mb-3">
                                        <div class="col-4">
                                                <p class="mdtext">Transfer To </p>
                                        </div>
                                        <div class="col-2">
                                        <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" data-bs-placement="bottom" data-html="true"  title="' . $mobile . ' / ' . $email . '"><span style="cursor: pointer;color: #136e1a !important;" class="fa fa-exchange""></span></a>
                                        </div>
                                        <div class="col-6">
                                                <p>' . $from_name . '</p>
                                        </div>
                                </div>
                                <div class="row mb-3">
                                            <div class="col-6">
                                                     <p class="mdtext">Amount </p>
                                            </div>
                                            <div class="col-6">
                                                        <p>AED ' . $wd_data['result'][0]['amount'] . '</p>
                                            </div>
                                </div>';

        if ($roll_id == 0) {
            if ($cus_prefer == 'bank') {
                $output .= '
                <div class="row mb-3" style="background-color: #f8f8f8;">
                <div class="row mb-3">
                <div class="col-12">
                         <p class="mdtext" style="color: firebrick;">Bank Details</p>
                </div>
                </div>
                <div class="row mb-3">
                <div class="col-6">
                         <p class="mdtext">Bank Name </p>
                </div>
                <div class="col-6">
                            <p>' . $wd_data['result'][0]['bank_name'] . '</p>
                </div>
                </div>
                <div class="row mb-3">
                <div class="col-6">
                         <p class="mdtext">Branch Name </p>
                </div>
                <div class="col-6">
                            <p>' . $wd_data['result'][0]['branch_name'] . '</p>
                </div>
                </div>
                <div class="row mb-3">
                <div class="col-6">
                         <p class="mdtext">Branch Code </p>
                </div>
                <div class="col-6">
                            <p>' . $wd_data['result'][0]['branch_code'] . '</p>
                </div>
                </div>
                <div class="row mb-3">
                <div class="col-6">
                         <p class="mdtext">Account Holder Name </p>
                </div>
                <div class="col-6">
                            <p>' . $wd_data['result'][0]['achname'] . '</p>
                </div>
                </div>
                <div class="row mb-3">
                <div class="col-6">
                         <p class="mdtext">Account Type </p>
                </div>
                <div class="col-6">
                            <p>' . $wd_data['result'][0]['acctype'] . '</p>
                </div>
                </div>
                <div class="row mb-3">
                <div class="col-6">
                         <p class="mdtext">Account Number </p>
                </div>
                <div class="col-6">
                            <p>' . $wd_data['result'][0]['acc_no'] . '</p>
                </div>
                </div>
                <div class="row mb-3">
                <div class="col-6">
                         <p class="mdtext">IBAN Code </p>
                </div>
                <div class="col-6">
                            <p>' . $wd_data['result'][0]['iban_code'] . '</p>
                </div>
                </div>
                <div class="row mb-3">
                <div class="col-6">
                         <p class="mdtext">SWIFT Code </p>
                </div>
                <div class="col-6">
                            <p>' . $wd_data['result'][0]['swiftcode'] . '</p>
                </div>
                </div>
                <div class="row mb-3">
                <div class="col-6">
                         <p class="mdtext">Currency </p>
                </div>
                <div class="col-6">
                            <p>' . $wd_data['result'][0]['currencyccode'] . '</p>
                </div>
                </div>
                </div>';
                
            } else if ($cus_prefer == 'exchange') {



                $output .= '
                <div class="row mb-3" style="background-color: #f8f8f8;">
                <div class="row mb-3">
                <div class="col-12">
                         <p class="mdtext" style="color: firebrick;">Exchange</p>
                </div>

                </div>

                <div class="row mb-3">
                <div class="col-6">
                         <p class="mdtext">Exchange ID </p>
                </div>
                <div class="col-6">
                            <p>' . $wd_data['result'][0]['exchangeid'] . '</p>
                </div>
                </div>
                </div>';
            }
        }



        $output .= '<div class="row mb-3">
                                <div class="col-6">
                                         <p class="mdtext">Transaction Mode</p>
                                </div>
                                <div class="col-6">
                                <select class="form-select" id="transactionmode">
                                <option value="">Select Mode</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank">Bank</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Exchange">Exchange</option>
                                </select>
                                </div>
                                </div>

                                <div class="row mb-3">
                                        <div class="col-6">
                                                <p class="mdtext">Transaction ID</p>
                                        </div>
                                        <div class="col-6">
                                        <input class="input101 input100 border-start-0 ms-0 form-control" id="withdrawid" type="text" placeholder="Enter Transaction Id">
                                        </div>
                                </div>
                                <div class="row mb-3">
                                <div class="col-6">
                                        <p class="mdtext">Transaction Date</p>
                                </div>
                                <div class="col-6">
                                <input class="input101 input100 border-start-0 ms-0 form-control" id="withdrawdate" type="date" placeholder="Enter Transaction Id">
                                </div>
                        </div>
                      <div class="row mb-3">
                        <div class="col-6">
                                <p class="mdtext">Transaction File</p>
                        </div>
                        <div class="col-6 text-center p-0">
                        <input class=" ms-0 form-control" type = "file" id="withdrawfile" name="withdrawfile" onchange="preview()" accept=".png, .jpg, .webp ,.pdf,.capture=camera" / >
                        <span style="font-size: 12px;">Upload File size less than 2MB</span><br>
                        <span style="font-size: 12px;">Upload File Format (jpg,png,webp,pdf)</span>
                        </div>
                </div>
                        <div class="row mb-3">
                        <textarea id="reasontext" placeholder="Reason"></textarea>
                        </div>
                        </div>
        </form>';
        $withdraw = '<button class="btn btn-primary" onclick="transferwdamount(' . "'$request'" . ')">Transfer</button>';
        $result['type'] = '1';
        $result['result'] = $output;
        $result['withdraw'] = $withdraw;
    } else {

        $result['type'] = '0';
        
        $result['result'] = '<div class="alert alert-danger" role="alert">Request Not Found!</div>';
    }



    echo json_encode($result);
} else if ($method == 'transfer_wd') {

    $result = [];

    $request = $_POST['request'];

    $mode = $_POST['mode'];

    $transid = $_POST['transid'];

    $date = $_POST['date'];

    $reasontext = $_POST['reasontext'];

    $loginID = $_SESSION['memid'];
    if ($loginID == '' ||  $loginID == null ||  $loginID == 'null') {
        $result['type'] = '0';
        $result['result'] = 'Login Reuired';
        goto FGHIcvjsk;
    }

// var_dump($request,$mode,$transid,$date,$date,$loginID);die;
    $wd_data = select_query($con, "withdraw_request", "", "`request_id`='$request' and `status`='0' and `deletes`='0'", "", "");
   
    $user_id = $wd_data['result'][0]['from_id'];
    $allowed = array('jpg', 'png', 'webp', 'pdf');

    $file_name = $_FILES['filesent']['name'];
    $file_type = $_FILES['filesent']['type'];
    $file_size = $_FILES['filesent']['size'];
    $file_temp_loc = $_FILES['filesent']['tmp_name'];
    
    //  var_dump($file_name);die;
    if ($file_name != '') {
        if (($file_size / 1024) < 2048) {
            $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $name = "";

            $name = md5(uniqid());

            $file_store = "";
            $path = "";
            if (!in_array($ext, $allowed)) {
                $withdraw = '<button class="btn btn-primary" onclick="transferwdamount(' . "'$request'" . ')">Transfer</button>';
                $result['type'] = '0';
                $result['result'] = 'File format not supported';
                $result['withdraw'] = $withdraw;
                goto FGHIcvjsk;
            }
           

            $fileNEW = '';
            // move_uploaded_file($file_tem_loc, $file_store);
            $filePath = new CURLFile($file_temp_loc, $file_type, $file_name);
            $postData = array('image' => $filePath, 'id' => $loginID);

            $fileUpload = json_decode(json_decode(fileMoveS3($postData), true), true);

            if ($fileUpload['status'] = 'success') {
                $fileNEW =  $fileUpload['data']['digitalURL'];
            } else {
                $result['type'] = '0';
                $result['result'] = 'File Upload Failed';
                // $result['withdraw'] = $withdraw;
                goto FGHIcvjsk;
            }


//  var_dump($request,$mode,$transid,$date,$fileNEW,$reasontext);die;

            if ($request != '' && $mode != '' && $transid != '' && $date != '' && $fileNEW != '' && $reasontext != '') {
                
                

                $wd_data = select_query($con, "withdraw_request", "", "`request_id`='$request' and `status`='0' and `deletes`='0'", "", "");

                if ($wd_data['nr'] > 0) {

                    $from_id = $wd_data['result'][0]['requested_by'];

                    // $from_earn = select_top_name($con, "user_register", "walletBalance", "`id`='$from_id' and `deletes`='0'", "walletBalance", "");
                    $req_walletBalance = select_top_name($con, "user_register", "walletBalance", "`id`='$from_id' and `deletes`='0'", "walletBalance", "");
// var_dump('kumar');die;

                    // $amount = $wd_data['result'][0]['amount'];
                    
                    // $req_name = select_top_name($con, "user_register", "name", "`id`='$from_id' and `deletes`='0'", "name", "");
                    // $req_lname = select_top_name($con, "user_register", "lname", "`id`='$from_id' and `deletes`='0'", "lname", "");
                    // $req_fullname = $req_name .' '.$req_lname;
                    // $req_mobile = select_top_name($con, "user_register", "mobile", "`id`='$from_id' and `deletes`='0'", "mobile", "");
                    // $req_email = select_top_name($con, "user_register", "email", "`id`='$from_id' and `deletes`='0'", "email", "");



                    $wd_arr = array("submited_by" => $_SESSION['memid'], "reasontext" => $reasontext, "trans_mode" => $mode, "attachment_url" => $fileNEW, "transaction_id" => $transid, "trans_date" => $date, "status" => '1');
                    
                    // $Arrd2 = array("userid" => "$from_id", "uname" => "$req_fullname","umobile" => "$req_mobile","uemail" => "$req_email","opening_balance" => "$req_walletBalance", "total" => $topoint, "closeing_balance" => $walletBalance, "point_type" => 'WALLET', "transaction_type" => 'CREDIT', "reward_type" => 'BANKWITHDRAWAL',"reference_id" => $PT_id,"reference_table" => 'point_request',"createdon" => $dubaidate_time,"updatedon" => $dubaidate_time);

                    $wd_update = update($con, "withdraw_request", "`request_id` = '$request' and `deletes`='0'", $wd_arr, "", "", "", "");
                    
                    // $Wallet_History = insert($con, "wallet_history", "", $Arrd2, "", "", "");

                    $errors = $wd_update['errors'];

                    if ($errors != "") {

                        $result["type"] = "0";

                        $result["result"] = $errors;
                    } else {

                        $result['type'] = '1';

                        $result['result'] = 'Transfer Amount Successfully';
                    }
                } else {

                    $result['type'] = '0';

                    $result['result'] = 'Request Not Found!';

                    // $result[result] = '<div class="alert alert-danger" role="alert">Request Not Found!</div>';

                }
            } else {
                $result['type'] = '0';

                $result['result'] = 'Please Fill All Fields.';
            }
        } else {

            $result['type'] = '0';

            $result['result'] = 'Please Upload file less then 2MB';

            // $result[result] = '<div class="alert alert-danger" role="alert">Please Fill All Fields.</div>';

        }
    } else {
        $withdraw = '<button class="btn btn-primary" onclick="transferwdamount(' . "'$request'" . ')">Transfer</button>';
        $result['type'] = '0';

        $result['result'] = 'Please select upload file ';
        $result['withdraw'] = $withdraw;
    }

    FGHIcvjsk:
    echo json_encode($result);
} else if ($method == "ldbank_balance") {



    $result = [];



    $bank = '9999999';



    $points = select_top_name($con, "ldbank", "points", "`id`='$bank' and `deletes`='0'", "points", "");



    if ($points != '') {



        $result['type'] = 1;



        $result['result'] = intval($points);
    } else {



        $result['type'] = 0;



        $result['result'] = 'Could not get amount!';
    }



    echo json_encode($result);
} else if ($method == "transfer_point_to_admin") {



    $result = [];

    $totalpoint = BlockSQLInjection($_POST["totalpoint"]);






    $userid = $_SESSION['memid'];



    $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$userid' and `deletes`='0'", "roll_id", "");



    if ($roll_id == 1) {



        if ($totalpoint != '') {



            $bank = '9999999';



            $points = select_top_name($con, "ldbank", "points", "`id`='$bank' and `deletes`='0'", "points", "");



            $t_point = select_top_name($con, "user_register", "t_point", "`id`='$userid' and `deletes`='0'", "t_point", "");



            $UT_point = intval($points) - intval($totalpoint);



            $form_id = $point_req['result'][0]['from_id'];



            $balancepoint = intval($t_point) + intval($totalpoint);



            $Arrd = array("from_id" => "$bank", "type" => "generate", "points" => $totalpoint, "from_opening" => $points, "from_closing" => $UT_point, "to_id" => $userid, "to_opening" => $t_point, "to_closing" => $balancepoint, "invoice_id" => '', "createdon" => $dubaidate_time);



            $point_trans = insert($con, "points_transaction", "", $Arrd, "", "", "");



            $point_arr1 = array("t_point" => $balancepoint);



            $point_update1 = update($con, "user_register", "`id` = '$userid' and `deletes`='0'", $point_arr1, "", "", "", "");



            $errors = $point_update1['errors'];



            if ($errors != "") {



                $result["type"] = "0";



                $result["result"] = $errors;
            } else {



                $point_arr2 = array("points" => $UT_point);



                $point_update2 = update($con, "ldbank", "`id` = '$bank' and `deletes`='0'", $point_arr2, "", "", "", "");



                $errors = $point_update2['errors'];



                if ($errors != "") {



                    $result["type"] = "0";



                    $result["result"] = $errors;
                } else {



                    $result['type'] = 1;



                    $result['result'] = '<div class="alert alert-success" role="alert">Points has been Generated Successfully.</div>';
                }
            }
        } else {



            $result['type'] = 0;



            $result['result'] = '<div class="alert alert-warning" role="alert">Please Fill the Points.</div>';
        }
    } else {



        $result['type'] = 0;



        $result['result'] = '<div class="alert alert-warning" role="alert">Your not a Admin.</div>';
    }



    echo json_encode($result);
} else if ($method == "list_ld_points") {



    $bank = '9999999';



    $result = [];



    $contype = '';



    $da = '';



    $now = date('Y-m-d');

    $formdate = BlockSQLInjectionforagent($_POST["formdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    $tablename = BlockSQLInjectionforagent($_POST["tablename"]);




    if ($formdate != '' && $todate != '') {



        $da = 'DESC';



        $fd = date("Y/m/d", strtotime($formdate));



        $td = date("Y/m/d", strtotime($todate));



        $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {



        $da = 'DESC';



        $contype = "`createdon` LIKE '%$now%' AND";
    }



    $userid = $_SESSION['memid'];



    $conition = "`from_id` = '$bank' AND";



    $point_Trans = select_query($con, "points_transaction", "", "$conition $contype `type` = 'generate' and `deletes`='0'", "", "");



    if ($point_Trans['nr'] > 0) {



        foreach ($point_Trans['result'] as $key => $value) {



            $trans_id = '';



            $point_s = '';



            $ordertype = '';



            $toname = select_top_name($con, "user_register", "name", "`id`='$value[to_id]' and `deletes`='0'", "name", "");



            if ($value['from_id'] == $bank) {



                $ordertype = '<p style="color: red;">Debit</p>';



                $point_s = '<p style="color: red;">' . $value['points'] . '</p>';
            } else {



                $ordertype = '<p style="color: green;">Credit</p>';



                $point_s = '<p style="color: green;">' . $value['points'] . '</p>';
            }



            $result['result'][] = array("ordertype" => $ordertype, "toname" => $toname, "cusname" => $toname, "status" => 'Success', "transid" => $trans_id, "paymenttype" => ucwords($value['type']), "points" => $point_s, "id" => str_pad($value[id], 7, "0", STR_PAD_LEFT), "date" => date("d-M-Y g:i a", strtotime($value['createdon'])));
        }



        $result['type'] = 1;
    } else {



        $result['type'] = 0;
    }



    echo json_encode($result);
} else if ($method == 'Check_Payemnt_Status') {



    $result = [];



    $transid = $_POST['transid'];



    $oticket = select_query($con, "ticket", "", "`transaction_id` = '$transid' and `deletes`='0' order by `id` DESC", "", "");



    if ($oticket['nr'] > 0) {



        $result['type'] = 0;



        $result['result'] = 'Ticket Already Generated.';



        goto Renew;
    } else {



        // $payment_history = select_query($con, "payment_history", "", "`transaction_id` = '$transid' AND `pay_re_status` != ''", "", "");



        $payment_history = select_query($con, "payment_history", "", "`transaction_id` = '$transid' AND `status` = '0' ORDER BY `id` DESC", "", "");



        if ($payment_history['nr'] > 0) {



            $response = json_decode($payment_history['result'][0]['response'], true);



            $orderReference = $payment_history['result'][0]['reference'];



            if ($orderReference == '') {



                $result['type'] = 0;



                $result['result'] = 'Reference ID Not Found';



                goto Renew;
            }










            $idData = identify($apikey, $access_token_url);

            // var_dump($idData);die;

            if (isset($idData->access_token)) {



                $token = $idData->access_token;



                $payData = callback($token, $check_order_status_url, $orderReference);



                $merchantOrderReference = $payData->merchantOrderReference;



                $state = $payData->_embedded->payment[0]->state;
            }



            if ($merchantOrderReference == $transid && $state == 'CAPTURED') {



                $output = '<button type="button" onclick = "otpreview(' . "'$transid'" . ')" class="btn btn-primary">Preview Ticket</button>';



                $result['output'] = $output;



                $result['type'] = 1;



                $result['result'] = $state;
            } else {



                $result['type'] = 0;



                $result['result'] = $state;
            }
        }
    }



    Renew:



    echo json_encode($result);
} else if ($method == 'otpreview') {



    $result = [];



    $transid = $_POST['transid'];



    $oticket = select_query($con, "ticket", "", "`transaction_id` = '$transid' and `deletes`='0' order by `id` DESC", "", "");



    if ($oticket['nr'] > 0) {



        $result['type'] = 0;



        $result['result'] = 'Ticket Already Generated.';



        goto Renew3;
    } else {



        $payment_history = select_query($con, "payment_history", "", "`transaction_id` = '$transid' AND `status` = '0' ORDER BY `id` DESC", "", "");



        if ($payment_history['nr'] > 0) {



            $response = json_decode($payment_history['result'][0]['response'], true);



            $user_id = $payment_history['result'][0]['user_id'];



            $checkout_response = json_decode($payment_history['result'][0]['checkout_response'], true);



            $orderReference = $payment_history['result'][0]['reference'];






            $idData = identify($apikey, $access_token_url);



            if (isset($idData->access_token)) {



                $token = $idData->access_token;



                $payData = callback($token, $check_order_status_url, $orderReference);



                $merchantOrderReference = $payData->merchantOrderReference;



                $state = $payData->_embedded->payment[0]->state;
            }



            if ($merchantOrderReference == $transid && $state == 'CAPTURED') {



                $username = select_top_name($con, "user_register", "name", "`id`='$user_id' and `deletes`='0'", "name", "");



                $mobile = select_top_name($con, "user_register", "mobile", "`id`='$user_id' and `deletes`='0'", "mobile", "");



                $date = date("Y-m-d H:i:s");



                $draw = select_query($con, "draw", "", "`deletes`='0' and `status`='Active' and `ticket_start_datetime`<'$date' and `ticket_end_datetime`>'$date'   order by `id` ASC", "", "");



                if ($draw['nr'] > 0) {



                    $drawid = $draw['result'][0]['id'];



                    $drawname = $draw['result'][0]['name'];



                    $drawdate = date("d M Y", strtotime($draw['result'][0]['result_datetime']));
                }



                $count = intval($checkout_response['item1'] ?? 0) + intval($checkout_response['item2'] ?? 0) + intval($checkout_response['item3'] ?? 0) + intval($checkout_response['item4'] ?? 0);



                $output = '';



                $output .= '



                                        <table style="width: 100%;" cellpadding="5">



                                        <tbody>



                                           <tr class="table-wrapper">



              <td colspan="4" style="text-align: center; "><span class="Ticket" style="font-weight:bold; font-size:16px; "><br>Ticket Details<br></b><br></td>



                                          </tr>















                                           <tr>



                                              <th colspan="2" style="text-align: center;">CUSTOMER NAME</th>



                                              <th colspan="2" style="text-align: center;">MOBILE NO. <br></th>



                                         </tr>







                                         <tr>



                                              <td colspan="2" style="text-align: center;">';



                $output .= $username;



                $output .= '</td><td colspan="2" style="text-align: center;">';



                $output .= $mobile;



                $output .= '</td>



                                     </tr>



                                     <tr style="    border-bottom: 1px dashed #a9a9a9;">







            </tr>







                                     <tr>



                                               <td colspan="4" style="text-align: center;font-weight:bold;"><br><h4>';



                $output .= $drawname;



                $output .= '</h4></td>



                                          </tr>







                                             <tr style="    border-bottom: 1px dashed #a9a9a9;">







            </tr>



                                     <tr>



                                     <th colspan="2" style="text-align: center;">GRAND PRIZE UPTO</th>



                                     <th colspan="2" style="text-align: center;">ISSUED  ON</th>



                                     </tr>



                                     <tr>



                                     <td colspan="2" style="text-align: center;">



                                     AED 1,000,000



                                    </td>



                                    <td colspan="2" style="text-align: center;">';



                $output .= date('d M Y');



                $output .= '</td>



                                    </tr>



                                       <tr style="    border-bottom: 1px dashed #a9a9a9;">







            </tr>











               <tr>



                    <td colspan="4" style="text-align: center;">











            <table style="text-align: center; width:100%">







                <tr>



                    <th colspan="4" style="text-align: center;">



                        <br>Just3 Draw Prizes<br>



                    </th>



                </tr>







                <tr >



                    <th style="text-align: center; width: 33%;">



                        1ST PRIZE







                    </th>



                    <th style="text-align: center; width: 33%;">



                        2ND PRIZE



                    </th>



                    <th style="text-align: center; width: 33%;">



                        3RD PRIZE



                    </th>



                </tr>



                <tr>



                <th style="text-align: center; width: 33%;">







                     UPTO



                </th>



                <th style="text-align: center; width: 33%;">



                    UPTO



                </th>



                <th style="text-align: center; width: 33%;">



                    UPTO



                </th>



            </tr>



                <tr>



                    <td style="text-align: center; width: 33%;">



                        AED 25,000.00



                    </td>



                    <td style="text-align: center; width: 33%;">



                        AED 2,500.00



                    </td>



                    <td style="text-align: center; width: 33%;">



                        AED 250.00



                    </td>



                </tr>	</table>



                  </td>



                </tr>















                   <tr style="border-bottom: 1px dashed #a9a9a9;">







            </tr>











                <tr>



                    <th>



                        <br> TOTAL LINES



                    </th>



                </tr>



                <tr>



                    <td>';



                $output .= $count;



                $output .= '</td>



                </tr>



                   <tr style="border-bottom: 1px dashed #a9a9a9;">







            </tr>



                <tr>



                    <th>



                        Products



                    </th>



                    <th>



                        Lines



                    </th>



                    <th>



                        My<span font-size: 22px;



                  font-family: "Montserrat", sans-serif !important; >3</span>                                      Numbers



                    </th>';



                $output .= '</tr>';



                $pt = "4";



                for ($x = 1; $x <= $pt; $x++) {



                    $tval = "item" . $x;



                    $numlines = intval($checkout_response[$tval]);



                    if ($numlines > 0) {



                        $product = select_query($con, "product", "", "`id`='$x'  ", "", "");



                        $output .= '<tr><td>AED ' . number_format((float) $product['result'][0]['rate'], 2, '.', '') . '</td>';



                        $output .= '<td>' . $numlines . '</td><td>';



                        if ($x == "1") {



                            $rowvalue = "a";
                        } else if ($x == "2") {



                            $rowvalue = "b";
                        } else if ($x == "3") {



                            $rowvalue = "c";
                        } else if ($x == "4") {



                            $rowvalue = "d";
                        } else {



                            $rowvalue = "";
                        }



                        for ($g = 1; $g <= $numlines; $g++) {



                            $checkline = "itemid_row" . $rowvalue . "_" . $g;



                            if ($checkout_response[$checkline] != "") {



                                $my3number = $checkout_response[$checkline];



                                $output .= $my3number . '<br>';
                            }
                        }



                        $output .= '</td>';



                        $output .= '</tr><tr style="border-bottom: 1px dashed #1860a4;"></tr>';
                    }
                }



                $output .= '



                <tr style="border-bottom: 1px dashed #a9a9a9; padding-top:12px;"></tr>



                <tr>



                <td colspan="4" style="text-align: center;"><br>Just3 Draw Date: ' . $drawdate . '<br>Raffle Draw Date: ' . raffleDrawDate($con, $dubaidate_time, 'd M Y') . '</td>



            </tr><tr>



                    <td colspan="4" style="text-align: center;">



                        All Other Terms and Conditions Apply



                    </td>



                </tr>



            </tbody>



            </table>



            ';



                $result["output"] = $output;



                $result['otreigiterbtn'] = ' <button class="btn btn-success my-1" type="button" id="buybtn" onclick="otregisteroffline(' . "'$transid'" . ',' . "'$drawid'" . ')">Confirm</button>';



                $result['type'] = 1;



                $result['result'] = $checkout_response;
            } else {



                $result['type'] = 0;



                $result['result'] = $state;
            }
        }
    }



    Renew3:



    echo json_encode($result);
} else if ($method == 'otregisteroffline') {



    $result = [];



    $transid = $_POST['transid'];



    $drawid = $_POST['drawid'];



    $oticket = select_query($con, "ticket", "", "`transaction_id` = '$transid' and `deletes`='0' order by `id` DESC", "", "");



    if ($oticket['nr'] > 0) {



        $result['type'] = 0;



        $result['result'] = 'Ticket Already Generated.';



        $result['otreigiterbtn'] = ' <button class="btn btn-success my-1" type="button" id="buybtn" onclick="otregisteroffline(' . "'$transid'" . ',' . "'$drawid'" . ')">Confirm</button>';



        goto Renew2;
    } else {



        $payment_history = select_query($con, "payment_history", "", "`transaction_id` = '$transid' AND `status` = '0' ORDER BY `id` DESC", "", "");





        if ($payment_history['nr'] > 0) {



            $response = json_decode($payment_history['result'][0]['response'], true);



            $user_id = $payment_history['result'][0]['user_id'];



            $checkout_response = json_decode($payment_history['result'][0]['checkout_response'], true);



            $orderReference = $payment_history['result'][0]['reference'];






            $idData = identify($apikey, $access_token_url);



            if (isset($idData->access_token)) {



                $token = $idData->access_token;



                $payData = callback($token, $check_order_status_url, $orderReference);



                $merchantOrderReference = $payData->merchantOrderReference;



                $state = $payData->_embedded->payment[0]->state;
            }



            if ($merchantOrderReference == $transid && $state == 'CAPTURED') {



                $name = select_top_name($con, "user_register", "name", "`id`='$user_id' and `id`!='' and `deletes`='0' and `status`='0' and `roll_id`='0' order by `id` DESC ", "name", "");



                $mobile = select_top_name($con, "user_register", "mobile", "`id`='$user_id' and `id`!='' and `deletes`='0' and `status`='0' and `roll_id`='0' order by `id` DESC ", "mobile", "");



                $email = select_top_name($con, "user_register", "email", "`id`='$user_id' and `id`!='' and `deletes`='0' and `status`='0' and `roll_id`='0' order by `id` DESC ", "email", "");



                $date = date("Y-m-d H:i:s");



                $draw = select_query($con, "draw", "", "`id` = '$drawid' AND `deletes`='0' order by `id` ASC", "", "");



                if ($draw['nr'] > 0) {



                    $draw_id = $draw['result'][0]['id'];



                    $drawname = $draw['result'][0]['name'];



                    $drawdate = date("d M Y", strtotime($draw['result'][0]['result_datetime']));
                }



                $count = intval($checkout_response['item1'] ?? 0) + intval($checkout_response['item2'] ?? 0) + intval($checkout_response['item3'] ?? 0) + intval($checkout_response['item4'] ?? 0);



                $transaction_id = $transid;



                $post = $checkout_response;



                if ($post['item1'] > 0) {



                    $tentotal = $post['item1'] * 10;
                } else {



                    $tentotal = "0";
                }



                if ($post['item2'] > 0) {



                    $twentytotal = $post['item2'] * 20;
                } else {



                    $twentytotal = "0";
                }



                if ($post['item3'] > 0) {



                    $thirtytotal = $post['item3'] * 50;
                } else {



                    $thirtytotal = "0";
                }



                if ($post['item4'] > 0) {



                    $fourtytotal = $post['item4'] * 100;
                } else {



                    $fourtytotal = "0";
                }



                $finaltotal = $tentotal + $twentytotal + $thirtytotal + $fourtytotal;



                $totline = $post['item1'] + $post['item2'] + $post['item3'] + $post['item4'];



                $onepercen = ($finaltotal / 105);



                $total_amount = number_format(($onepercen * 100), 2);



                $tax_value = number_format(($finaltotal - $total_amount), 2);



                $datetime = date("Y-m-d H:i:s");



                $Arr = array(

                    "draw_id" => $draw_id,

                    "user_id" => $user_id,

                    "total_lines" => $totline,

                    "sale_from" => 1,

                    "purchase_datetime" => $datetime,

                    "net_total" => $finaltotal,

                    "tax_percentage" => 5,

                    "tax_value" => $tax_value,

                    "total_amount" => $total_amount,

                    "payment_by" => "Card",

                    "transaction_id" => $transaction_id,



                );



                $ticket = insert($con, "ticket", "", $Arr, "", "", "");



                $ottype = "OT";



                $ticketnumber = $ticket['id'];



                $ticketid = "OT" . sprintf("%04d", $ticket['id']);



                $bfirstname = $payData->billingAddress->firstName;



                $blastname = $payData->billingAddress->lastName;



                $bemailid = $email;



                $baddress = $payData->billingAddress->address1;



                $bcity = $payData->billingAddress->city;



                $bcountry = $payData->billingAddress->countryCode;



                $bresponse = json_encode($payData);



                $Arr1 = array("ticket_id" => $ticket['id'], "type" => "OT", "firstname" => $bfirstname, "lastname" => $blastname, "emailid" => $bemailid, "address" => $baddress, "city" => $bcity, "country" => $bcountry, "response" => $bresponse);



                $invoice = insert($con, "invoice", "", $Arr1, "", "", "");



                $invoiceid = $invoice['id'];



                ///////////////////////////////  POINT TRANSACTION ////////////////////////



                $nowpoints = select_top_name($con, "ldbank", "points", "`deletes`='0' and `id`='9999999'  ", "points", "");



                $balancepoint = $nowpoints - $finaltotal;



                $topoints = select_top_name($con, "user_register", "t_point", "`deletes`='0' and `status`='0'  and `id`!='' and `id`='$user_id'  ", "t_point", "");

                // NEW //
                $t_earning = select_top_name($con, "user_register", "t_earning", "`deletes`='0' and `status`='0'  and `id`!='' and `id`='$user_id'  ", "t_earning", "");
                $topoints = $topoints + $t_earning;


                $tobalancepoint = $topoints + $finaltotal;



                $Arrd = array("from_id" => "9999999", "type" => "credit", "points" => $finaltotal, "from_opening" => $nowpoints, "from_closing" => $balancepoint, "to_id" => $user_id, "to_opening" => $topoints, "to_closing" => $tobalancepoint, "invoice_id" => $invoiceid, "createdon" => $datetime);



                $invoice = insert($con, "points_transaction", "", $Arrd, "", "", "");



                $Arrd = array("from_id" => "$user_id", "type" => "order", "points" => $finaltotal, "from_opening" => $tobalancepoint, "from_closing" => $topoints, "to_id" => "9999999", "to_opening" => $balancepoint, "to_closing" => $nowpoints, "invoice_id" => $ticket['id'], "createdon" => $datetime);



                $invoice = insert($con, "points_transaction", "", $Arrd, "", "", "");



                ///////////////////////////////  POINT TRANSACTION ////////////////////////



                $Arr2 = array("ticket_no" => $ticketid, "invoice_no" => $invoiceid);



                $ins_update = setupdate($con, "ticket", "`id`='$ticket[id]'", $Arr2, "", "", "");



                $printurlf = "https://www.littledraw.com/ticket-view/" . $transaction_id;



                $printinvoice = "https://www.littledraw.com/invoice/" . $transaction_id;



                $printurl = get_tiny_url($printurlf);



                $invoiceurl = get_tiny_url($printinvoice);



                $pt = "4";



                $ot = 1;



                $k = "";



                for ($x = 1; $x <= $pt; $x++) {



                    $tval = "item" . $x;



                    if ($post[$tval] > 0) {



                        $k++;



                        $product = select_query($con, "product", "", "`id`='$x'  ", "", "");



                        foreach ($product['result'] as $key => $productinfolist) {
                        }



                        if ($x == "1") {



                            $rowvalue = "a";
                        } else if ($x == "2") {



                            $rowvalue = "b";
                        } else if ($x == "3") {



                            $rowvalue = "c";
                        } else if ($x == "4") {



                            $rowvalue = "d";
                        } else {



                            $rowvalue = "";
                        }



                        $numlines = $post[$tval];



                        for ($g = 1; $g <= $numlines; $g++) {



                            $checkline = "itemid_row" . $rowvalue . "_" . $g;



                            if ($post[$checkline] != "") {



                                $my3number = "";



                                $linedate = "";



                                $my3number = $post[$checkline];



                                $raffle_id = 'OT' . $ticketnumber . str_pad($ot, 2, "0", STR_PAD_LEFT);



                                $linedate = array("my3number" => $my3number, "user_id" => $user_id, "ticket_id" => $ticket['id'], "draw_id" => $draw_id, "agent_id" => 0, "product_id" => $x, "orders" => $ticketid, "orders" => $ticket['id'], "raffle_id" => $raffle_id, "type" => "OT", "invoice_no" => $invoiceid);



                                $lines = insert($con, "ticket_lines", "", $linedate, "", "", "");



                                $ot++;
                            }
                        }
                    }
                }



                if (substr($mobile, 0, 3) == "971") {



                    $messages1 = 'Ticket ID #' . $ticketid . '.';



                    $query = select_query($con, "ticket_lines", "", "`ticket_id`='$ticketnumber' and `type` = '$ottype' and `ticket_id`!='' and `deletes` = '0' group by `product_id` order by `product_id` ASC  ", "", "");



                    foreach ($query['result'] as $key => $valuelist) {



                        $p_id = $valuelist['product_id'];



                        $t_id = $valuelist['orders'];



                        $product = select_query($con, "product", "", "`id`='$valuelist[product_id]'  ", "", "");



                        $messages1 .= 'CAT-AED ' . round($product['result'][0]['rate']) . '. ';



                        $rcount = count($mynumber['result']);



                        $io = 1;



                        $mynumber = select_query($con, "ticket_lines", "", "`ticket_id`='$ticketnumber' and `product_id`='$p_id' and `type` = '$ottype' and `deletes` = '0'", "", "");



                        foreach ($mynumber['result'] as $key => $mynumber1) {



                            $messages1 .= $mynumber1['my3number'];



                            if ($io == $rcount) {



                                $messages1 .= '. ';
                            } else {



                                $messages1 .= ', ';
                            }



                            $io++;
                        }
                    }



                    $messages1 .= 'for an Amounts of AED ' . number_format((float) $finaltotal, 2, '.', '') . ' for more info. (' . $printurl . '),. TC apply. Good Luck!!!';



                    $templateid = "";



                    sendsms($con, $mobile, $messages1, $templateid);
                }



                $subject = "Purchase Confirmation";





                $messages = '<!DOCTYPE html>



                <html lang="en">



                <head>



                  <meta charset="UTF-8">



                  <meta http-equiv="X-UA-Compatible" content="IE=edge">



                  <meta name="viewport" content="width=device-width, initial-scale=1.0">







                </head>



                <body>



                  <div>



                    <div dir="ltr">



                      <table class="gmail-body-bg" style="margin: auto; min-height: 100px; color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb; border-collapse: collapse; background-image: -webkit-linear-gradient(124deg, #2e76a1 0%, #002387 100%);" border="0" width="100%" cellspacing="0" cellpadding="0">



                        <tbody>



                          <tr>



                            <td style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-style: italic;" align="center" bgcolor="">



                              <table style="margin: auto; max-width: 500px; border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0">



                                <tbody>



                                  <tr>



                                    <td style="font-family: Roboto Condensed,sans-serif;" align="center" valign="top">&nbsp;</td>



                                  </tr>



                                </tbody>



                              </table>



                            </td>



                          </tr>



                        </tbody>



                      </table>



                      <table style="margin: auto; color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb; max-width: 500px; border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0">



                        <tbody>



                          <tr>



                            <td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic; padding: 9px 20px 20px; background: radial-gradient(circle,#fcef48 0%,#fdd206 100%); font-size: 48px; line-height: 24px;" align="center" valign="top" bgcolor="#ffffff">&nbsp;</td>



                          </tr>



                        </tbody>



                      </table>



                      <table style="margin: auto; color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb; max-width: 500px; border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0">



                        <tbody>



                          <tr>



                            <td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic; padding: 20px; font-size: 48px; line-height: 24px;" align="center" valign="top" bgcolor="#ffffff">



                              <img style="border: 0px; height: auto; line-height: 48px; outline: none; display: block;" src="' . $baseurl . 'assets/images/mail/logo.png" width="200" height="120">



                            </td>



                          </tr>



                        </tbody>



                      </table>



                      <table style="margin: auto; color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb; max-width: 500px; border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0">



                        <tbody>



                          <tr>



                            <td style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-style: italic;" align="center" bgcolor="#f4f4f4">



                              <table style="margin: auto; max-width: 500px; border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0">



                                <tbody>



                                  <tr><!--<td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; padding: 0px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px;" align="center" valign="top" bgcolor="#ffffff">--> <!--  <h3 style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-size: 23px; margin: 0px;">Hi, ' . $username . ' --> <!--    <br>--> <!--  </h3>--> <!--</td>--></tr>



                                </tbody>



                              </table>



                            </td>



                          </tr>



                          <tr>



                            <td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic; padding: 6px 12px 7px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px;" align="center" valign="top" bgcolor="#ffffff">



                              <table>



                                <tbody>



                                  <tr>



                                    <td>



                                      <img style="border: 0px; height: auto; line-height: 24px; outline: none; left: 38px; bottom: -36px;" src="' . $baseurl . 'assets/images/mail/heloboy.png">



                                    </td>



                                    <td>



                                      <h3 style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-size: 23px; margin: 0px; text-align: center;">Hi, ' . $name . '



                                        <br>



                                      </h3>



                                      <br>



                                      <p style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-size: 18px; font-weight: bold; margin: 0px; text-align: center;">Thank you for your purchase



                                        <br>and donations



                                      </p>



                                      <br>



                                      <h3 style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-size: 23px; margin: 0px; text-align: center;">Ticket ID #' . $ottype . '' . $ticketnumber . '



                                        <br>



                                      </h3>



                                    </td>



                                  </tr>



                                </tbody>



                              </table>



                            </td>



                          </tr>



                          <tr><!--<td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic; padding: 9px 12px 7px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px;" align="center" valign="top" bgcolor="#ffffff">--> <!--  <h3 style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-size: 23px; margin: 0px;">Ticket ID #OT1209 --> <!--    <br>--> <!--  </h3>--> <!--</td>--></tr>



                        </tbody>



                      </table>



                      <table style="margin: auto; color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb; max-width: 500px; border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0">



                        <tbody>



                          <tr>



                            <td style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-style: italic; padding: 12px;" align="center" bgcolor="#ffffff">



                              <table style="margin: auto; border-collapse: collapse;" border="1" cellspacing="2" cellpadding="0">



                                <tbody>



                                  <tr>



                                    <th style="padding: 12px;" align="center" bgcolor="#ffffff">Product</th>



                                    <th style="padding: 12px;" align="center" bgcolor="#ffffff">Lines</th>



                                    <th style="padding: 12px;" align="center" bgcolor="#ffffff">My



                                      <span class="gmail-my-number" style="color: #be1e2d; font-size: 23px;">3</span>Numbers



                                    </th>



                                    <th style="padding: 12px;" align="center" bgcolor="#ffffff">Raffle ID</th>



                                  </tr>';



                $query = select_query($con, "ticket_lines", "", "`ticket_id`='$ticketnumber'  and `type` = '$ottype' and `ticket_id`!='' and `deletes` = '0' group by `product_id` order by `product_id` ASC  ", "", "");



                foreach ($query['result'] as $key => $valuelist) {



                    $p_id = $valuelist['product_id'];



                    $t_id = $valuelist['orders'];



                    $product = select_query($con, "product", "", "`id`='$valuelist[product_id]'  ", "", "");



                    foreach ($product['result'] as $key => $productinfo) {
                    }



                    $pcountlist = select_query_count($con, "ticket_lines", "id", "`product_id`='$valuelist[product_id]' and  `type` = '$ottype' and  `orders`='$valuelist[orders]' and `deletes` = '0'", "", "");



                    $mynumber = select_query($con, "ticket_lines", "", "`ticket_id`='$ticketnumber' and `product_id`='$p_id' and  `type` = '$ottype' and `deletes` = '0'", "", "");



                    $messages .= '<tr>



                <td style="font-family: Roboto Condensed,sans-serif; padding: 12px;" align="center" bgcolor="#ffffff">AED ' . number_format((float) $productinfo['rate'], 2, '.', '') . '</td>';



                    $messages .= '<td style="font-family: Roboto Condensed,sans-serif; padding: 12px;" align="center" bgcolor="#ffffff">' . $mynumber['nr'] . '</td>';



                    $messages .= '<td style="font-family: Roboto Condensed,sans-serif; padding: 12px;" align="center" bgcolor="#ffffff">';



                    foreach ($mynumber['result'] as $key => $mynumber1) {



                        $messages .= $mynumber1['my3number'] . "<br>";
                    }



                    $messages .= '</td>';



                    $messages .= '<td style="font-family: Roboto Condensed,sans-serif; padding: 12px;" align="center" bgcolor="#ffffff">';



                    foreach ($mynumber['result'] as $key => $mynumber1) {



                        $messages .= $mynumber1['raffle_id'] . "<br>";
                    }



                    $messages .= '</td>';



                    $messages .= '</tr>';
                }



                // $messages .= '<tr>



                //                     <td style="font-family: Roboto Condensed,sans-serif; padding: 12px;" align="center" bgcolor="#ffffff">AED 10.00</td>



                //                     <td style="font-family: Roboto Condensed,sans-serif; padding: 12px;" align="center" bgcolor="#ffffff">1</td>



                //                     <td style="font-family: Roboto Condensed,sans-serif; padding: 12px;" align="center" bgcolor="#ffffff">274</td>



                //                     <td style="font-family: Roboto Condensed,sans-serif; padding: 12px;" align="center" bgcolor="#ffffff">OT120406</td>



                //                   </tr>';



                $messages .= ' </tbody>



                              </table>



                            </td>



                          </tr>



                        </tbody>



                      </table>



                      <table style="margin: auto; color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb; max-width: 500px; border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0">



                        <tbody>



                          <tr>



                            <td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic; padding: 9px 12px 7px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px;" align="center" valign="top" bgcolor="#ffffff">



                              <h3 style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-size: 23px; margin: 0px;">Total Amount:



                                <span class="gmail-otp-bg" style="color: #be1e2d;">&nbsp;AED ' . number_format((float) $finaltotal, 2, '.', '') . '</span>



                                <br>



                              </h3>



                            </td>



                          </tr>



                        </tbody>



                      </table>



                      <table style="margin: auto; color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb; max-width: 500px; border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0">



                        <tbody>



                          <tr>



                            <td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic; padding: 9px 12px 21px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px;" align="center" valign="top" bgcolor="#ffffff">



                              <h3 style="color: #ffffff; font-family: Roboto Condensed,sans-serif; font-size: 19px; margin: 0px; padding: 9px; background: #052c8a; width: 108.453px; line-height: 1; border-radius: 11px;">



                                <a href="' . $baseurl . 'ticket-view/' . $transaction_id . '" style="color: #ffffff; text-decoration-line: none;">View Ticket</a>



                              </h3>



                            </td>



                            <td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic; padding: 9px 12px 21px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px;" align="center" valign="top" bgcolor="#ffffff">



                              <h3 style="color: #ffffff; font-family: Roboto Condensed,sans-serif; font-size: 19px; margin: 0px; padding: 9px; background: #2b8db9; width: 117.547px; line-height: 1; border-radius: 11px;">



                                <a href="' . $baseurl . 'invoice/' . $transaction_id . '" style="color: #ffffff; text-decoration-line: none;">View Invoice</a>



                              </h3>



                            </td>



                          </tr>



                        </tbody>



                      </table>



                      <br style="color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb;">



                      <table style="margin: auto; color: #000000; font-family: Times New Roman; font-size: medium; max-width: 500px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0">



                        <tbody>



                          <tr>



                            <td style="color: #666666; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic;  background: none; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; padding: 12px 9px 12px 13px; font-size: 15px; line-height: 25px;" align="center" bgcolor="#e4dcf1">



                              <p style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-size: 18px; margin: 0px; text-align: left;">Watch Just3 ' . (isset($drawfreq) && $drawfreq === 4 ? 'Daily Draw results <br> every Monday to Friday ' : 'Tri-Daily Draw results <br> every (Monday,Wednesday,Friday) ') . constant("resultTIME") . 'UAE TIME ' . ((isset($ssdraw_id) && $ssdraw_id != '' && isset($sdrawresultdate1)) ? ', Super Raffle Draw ' . date("dS F Y", strtotime($sdrawresultdate1)) . ' '  : ' ') . (!checkGrandRaffleEligible($draw_no) ?  ('& <br>Grand Raffle Draw result on ' . raffleDrawDate($con, $dubaidate_time, 'dS F Y')) : '') . '</p>



                            </td>



                            <td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic; padding: 9px 13px 9px 18px; font-size: 48px; font-weight: bold; line-height: 24px; display: flex; margin-top: 25px;" align="center" valign="" bgcolor="#ffffff">



                              <img style="border: 0px; height: auto; line-height: 48px; outline: none;" src="' . $baseurl . 'assets/images/mail/fb.png">



                              <img style="border: 0px; height: auto; line-height: 48px; outline: none;" src="' . $baseurl . 'assets/images/mail/youtube.png">



                            </td>



                          </tr>



                        </tbody>



                      </table>



                      <br style="color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb;">



                      <table style="margin: auto; color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb; max-width: 500px; border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0">



                        <tbody>



                          <tr>



                            <td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic; padding: 9px 6px 9px 18px; font-size: 48px; font-weight: bold; line-height: 24px; background: radial-gradient(circle,#fcef48 0%,#fdd206 100%);" align="center" valign="" bgcolor="#ffffff">



                              <h3 style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-size: 19px; margin: 0px; text-align: left;">



                                <span class="gmail-otp-bg" style="color: #be1e2d;">Good Luck!!!</span>&nbsp;for your



                                <br>Future Bi-Weekly Draws



                              </h3>



                            </td>



                            <td class="gmail-line" style="box-sizing: border-box; width: 8px;">&nbsp;</td>



                            <td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic; padding: 9px 11px 9px 22px; font-size: 48px; font-weight: bold; line-height: 24px; background: radial-gradient(circle,#fcef48 0%,#fdd206 100%);" align="center" valign="" bgcolor="#ffffff">



                              <h3 style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-size: 19px; margin: 0px; text-align: left;">



                                <img style="border: 0px; height: auto; line-height: 19px; outline: none;" src="' . $baseurl . 'assets/images/mail/arrow.png">Change



                                <br>your life



                              </h3>



                            </td>



                          </tr>



                        </tbody>



                      </table>



                      <br style="color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb;">



                      <br style="color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb;">



                     

                     <p style="color: #29377d !important;font-size: 15px !important;margin: 0px !important;text-align: center !important;font-weight: 500 !important;font-style: italic !important;font-family: Arial Narrow !important;margin: 8px 0px 0px 0px !important;">Note: This is a system auto generated email. Please do not reply to this mail.<br>
                     
                     For Clarification
                     
                     
                            <br>
                     
                     Call 04 33 98880 Whatsapp +971 56 199 1271
                     
                     <br>
                     
                     or email support@littledraw.com</p>

                    </div>



                  </div>







                </body>



                </html>';



                $emailchack = explode('@', $email);



                if (strtolower($emailchack[1]) != "littledraw.ae") {



                    $emailsend = sendemail($con, $email, $subject, $messages, 'tickets');
                } else {
                }



                $draw_arr = array("response" => json_encode($payData), "pay_re_status" => $state, "status" => '1');



                $Inv_update = update($con, "payment_history", "`transaction_id` = '$transaction_id' and `status` = '0'", $draw_arr, "", "", "", "");



                $errors = $Inv_update['errors'];



                if ($errors != "") {



                    $result["type"] = "0";



                    $result["result"] = $errors;
                } else {



                    $order_arr = array("ticket_id" => $ticketnumber);



                    $Inv_update = update($con, "orders", "`transaction_id` = '$transid'", $order_arr, "", "", "", "");



                    $errors = $Inv_update['errors'];



                    if ($errors != "") {



                        $result["type"] = "0";



                        $result["result"] = $errors;
                    } else {
                    }



                    $result['type'] = 1;



                    $result['result'] = 'Ticket Genereted.';
                }
            } else {



                $result['type'] = 0;



                $result['result'] = $state;



                $result['otreigiterbtn'] = ' <button class="btn btn-success my-1" type="button" id="buybtn" onclick="otregisteroffline(' . "'$transid'" . ',' . "'$drawid'" . ')">Confirm</button>';
            }
        }
    }



    Renew2:



    echo json_encode($result);
} else if ($method == 'withdrawDecline') {



    $result = [];



    $request = $_REQUEST['request'];



    if ($request != '') {



        $result['rejectbtn'] = '<button class="btn btn-primary" onclick="reject_request(' . "'$request'" . ')">Reject</button>';



        $result['result'] = '<textarea id="rejectreasontext" placeholder="Reason"></textarea>';



        $result['type'] = 1;
    } else {



        $result['result'] = 'Request ID Not Found!';



        $result['type'] = 0;
    }



    echo json_encode($result);
} else if ($method == 'withdrawDeclineReject') {

    $result = [];
    $request = $_REQUEST['request'];
    $reason = $_REQUEST['reason'];
    if ($request != '' && $reason != '') {
        $point_req = select_query($con, "withdraw_request", "", "`request_id` = '$request' and `deletes` = '0' ORDER BY `id` DESC", "", "");
         if ($point_req['nr'] > 0) {

                    $to_id = $point_req['result'][0]['form_id'];
                    $PT_id = $point_req['result'][0]['id'];
                    $cus_prefer = $point_req['result'][0]['cus_prefer'];
         }
         
        //  var_dump($cus_prefer);die;
         
         
        
        $wd_arr = array("submited_by" => $_SESSION['memid'], "reasontext" => $reason, "trans_date" => $dubaidate_time, "status" => '2');
        $wd_update = update($con, "withdraw_request", "`request_id` = '$request' and `deletes`='0'", $wd_arr, "", "", "", "");
        
        $errors = $wd_update['errors'];
        if ($errors != "") {

            $result['rejectbtn'] = '<button class="btn btn-primary" onclick="reject_request(' . "'$request'" . ')">Reject</button>';
            $result["type"] = "0";
            $result["result"] = $errors;
        } else {
            $withdraw_data = select_query($con, "withdraw_request", "", "`request_id` = '$request' AND `deletes` = '0' ORDER BY `id` DESC", "", "");
            if ($withdraw_data['nr'] >= 0) {
                $user_id = $withdraw_data['result'][0]['requested_by'];
                $amt = $withdraw_data['result'][0]['amount'];
                $Wid = $withdraw_data['result'][0]['id'];
                $open_amt = select_top_name($con, "user_register", "walletBalance", "`id`='$user_id' and `deletes`='0'", "walletBalance", "");
                $total_amt = intval($open_amt) + intval($amt);
                $arr = array("walletBalance" => $total_amt);
                
                // ===========================================================================================================================
                
                
                
                    $req_name = select_top_name($con, "user_register", "name", "`id`='$user_id' and `deletes`='0'", "name", "");
                    $req_lname = select_top_name($con, "user_register", "lname", "`id`='$user_id' and `deletes`='0'", "lname", "");
                    $req_fullname = $req_name .' '.$req_lname;
                    $req_mobile = select_top_name($con, "user_register", "mobile", "`id`='$user_id' and `deletes`='0'", "mobile", "");
                    $req_email = select_top_name($con, "user_register", "email", "`id`='$user_id' and `deletes`='0'", "email", "");

                
                // ===========================================================================================================================
                $update = update($con, "user_register", "`id` = '$user_id' and `deletes`='0'", $arr, "", "", "", "");
                $errors = $update['errors'];
                if ($errors != "") {
                    $result["type"] = "0";
                    $result["result"] = $errors;
                } else {

                    $Arrde = array("type" => "withdraw", "order_type" => "credit", "amount" => $amt, "to_id" => $user_id, "to_opening" => $open_amt, "to_closing" => $total_amt, "invoice_id" => $Wid, "createdon" => $dubaidate_time);
                    
                    if($cus_prefer == 'bank'){
                        
                        $Arrd2 = array("userid" => "$user_id", "uname" => "$req_fullname","umobile" => "$req_mobile","uemail" => "$req_email","opening_balance" => "$open_amt", "total" => $amt, "closeing_balance" => $total_amt, "point_type" => 'WALLET', "transaction_type" => 'CREDIT', "reward_type" => 'RE_BANKWITHDRAWAL',"reference_id" => $PT_id,"reference_table" => 'withdraw_request',"createdon" => $dubaidate_time,"updatedon" => $dubaidate_time);
                        $Wallet_History = insert($con, "wallet_history", "", $Arrd2, "", "", "");
                        
                    }
                    
                    if($cus_prefer == 'exchange'){
                        
                        $Arrd2 = array("userid" => "$user_id", "uname" => "$req_fullname","umobile" => "$req_mobile","uemail" => "$req_email","opening_balance" => "$open_amt", "total" => $amt, "closeing_balance" => $total_amt, "point_type" => 'WALLET', "transaction_type" => 'CREDIT', "reward_type" => 'RE_EXCHANGEWITHDRAWAL',"reference_id" => $PT_id,"reference_table" => 'withdraw_request',"createdon" => $dubaidate_time,"updatedon" => $dubaidate_time);
                        $Wallet_History = insert($con, "wallet_history", "", $Arrd2, "", "", "");
                        
                    }
                    
                    // $Arrd2 = array("userid" => "$user_id", "uname" => "$req_fullname","umobile" => "$req_mobile","uemail" => "$req_email","opening_balance" => "$open_amt", "total" => $amt, "closeing_balance" => $total_amt, "point_type" => 'WALLET', "transaction_type" => 'CREDIT', "reward_type" => 'RE_BANKWITHDRAWAL',"reference_id" => $PT_id,"reference_table" => 'withdraw_request',"createdon" => $dubaidate_time,"updatedon" => $dubaidate_time);
                    // $Wallet_History = insert($con, "wallet_history", "", $Arrd2, "", "", "");
                    
                    $erarn_trans = insert($con, "earning_transaction", "", $Arrde, "", "", "");
                    if ($erarn_trans['id'] != '') {
                        $result['type'] = '1';
                        $result['result'] = 'Rejected Successfully';
                    }
                }
            }
        }
    } else {

        $result['result'] = 'Request ID Not Found!';
        $result['type'] = 0;
    }

    echo json_encode($result);
    
    
} else if ($method == "list_history") {



    // $result = [];



    $contype = '';



    $da = '';



    $now = date('Y-m-d');



    $userid = $_POST['userid'];



    $formdate = $_POST['formdate'];



    $todate = $_POST['todate'];



    if ($formdate != '' && $todate != '') {



        $da = 'DESC';



        $fd = date("Y/m/d", strtotime($formdate));



        $td = date("Y/m/d", strtotime($todate));



        $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {



        $da = 'DESC';



        $contype = "`createdon` LIKE '%$now%' AND";
    }



    if ($userid != '') {



        $agent = "`to_id` = '$userid' AND";
    } else {



        $agent = "";
    }



    $earn_Trans = select_query($con, "earning_transaction", "", "$agent $contype `deletes`='0' ORDER BY `id` DESC", "", "");



    if ($earn_Trans['nr'] > 0) {



        foreach ($earn_Trans['result'] as $key => $value) {



            $userid = $value['to_id'];



            $rollid = select_top_name($con, "user_register", "roll_id", "`id`='$userid' and `deletes`='0'", "roll_id", "");



            if (intval($rollid) != 0) {



                $invoiceid = $value['invoice_id'];



                $transid = select_top_name($con, "aticket", "transaction_id", "`invoice_no`='$invoiceid' and `deletes`='0'", "transaction_id", "");



                $agentid = select_top_name($con, "aticket", "agent_id", "`invoice_no`='$invoiceid' and `deletes`='0'", "agent_id", "");



                $agent_name = select_top_name($con, "user_register", "name", "`id`='$agentid' and `deletes`='0'", "name", "");

                $agent_lname = select_top_name($con, "user_register", "lname", "`id`='$agentid' and `deletes`='0'", "lname", "");




                $ticketamount = select_top_name($con, "aticket", "net_total", "`invoice_no`='$invoiceid' and `deletes`='0'", "net_total", "");



                $action = '';



                $action .= '<div class="g-2">';



                $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' . $transid . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span class="fa fa-file-text-o fs-14"></span></a>';



                $action .= '<a target="_blank" href="' . $baseurl . 'ticket-view/' . $transid . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span class="fa fa-files-o fs-14"></span></a>';



                $action .= '</div>';



                $result[] = array("action" => $action, "id" => str_pad($value['id'], 7, "0", STR_PAD_LEFT), "ticketamount" => $ticketamount, "date" => date("d-M-Y g:i a", strtotime($value['createdon'])), "amt" => $value['amount'], "type" => ucwords($value['type']), "transid" => $transid, "status" => 'Success', "sellername" => ucwords($agent_name) . ' ' . ucwords($agent_lname), "ordertype" => ucwords($value['order_type']));
            }
        }



        // $result['type'] = 1;



    } else {



        // $result['type'] = 0;



    }



    echo json_encode($result);
} else if ($method == "searchagent") {



    $result = [];



    $contype = '';



    $da = '';



    $now = date('Y-m-d');

    $mobile = BlockSQLInjection($_POST["mobile"]);


    // $mobile = $_POST['mobile'];



    $userid = select_top_name($con, "user_register", "id", "`mobile`='$mobile' and `roll_id` != '0' and `roll_id` != '1' and `roll_id` != '2'  and `deletes`='0'", "id", "");



    if ($userid != '') {



        $result['result'] = $userid;



        $result['type'] = 1;
    } else {



        $result['type'] = 0;



        $result['result'] = 'User Not Found!';
    }



    echo json_encode($result);
} else if ($method == "update_withdraw") {



    $result = [];



    $request = $_POST['request'];



    if ($request != '') {



        $withdraw_data = select_query($con, "withdraw_request", "", "`request_id` = '$request' AND `deletes` = '0' ORDER BY `id` DESC", "", "");



        if ($withdraw_data['nr'] >= 0) {



            $date = $withdraw_data['result'][0]['trans_date'];



            $reason = $withdraw_data['result'][0]['reasontext'];



            $output = '';



            $output .= '<div class="row">



            <div class="col-12">



                <span>Transaction Date</span>&nbsp;<span style="color:red;">*</span>



                <input type="date" id="transdate" value="' . $date . '">



            </div>



            <div class="col-12">



                <span>Reason</span>&nbsp;<span style="color:red;">*</span>



                <textarea id="transreason"></textarea>



            </div>



        </div>';



            $result['savechange'] = '  <button type="button" class="btn btn-primary" onclick="update_withdraw(' . "'$request'" . ')">Save changes</button>';



            $result['output'] = $output;



            $result['reason'] = $reason;



            $result['type'] = 1;
        } else {



            $result['type'] = 0;



            $result['result'] = 'Track not Found.';
        }
    } else {



        $result['type'] = 0;



        $result['result'] = 'Request id not found!';
    }



    echo json_encode($result);
} else if ($method == 'save_withdraw') {



    $result = [];



    $request = $_REQUEST['request'];



    $reasontxt = $_REQUEST['reasontxt'];



    $transdate = $_REQUEST['transdate'];



    if ($request != '' && $reasontxt != '' && $transdate != '') {



        $wd_arr = array("updated_by" => $_SESSION['memid'], "reasontext" => $reasontxt, "trans_date" => $transdate);



        $wd_update = update($con, "withdraw_request", "`request_id` = '$request' and `deletes`='0'", $wd_arr, "", "", "", "");



        $errors = $wd_update['errors'];



        if ($errors != "") {



            $result["type"] = 0;



            $result["result"] = $errors;
        } else {



            $result['type'] = '1';



            $result['result'] = 'Updated Successfully';
        }
    } else {



        $result['result'] = 'Please Fill All Fields.';



        $result['type'] = 0;
    }



    echo json_encode($result);
} else if ($method == 'Bank_deatils') {



    $result = [];



    $request = $_REQUEST['request'];



    $wd_data = select_query($con, "withdraw_request", "", "`request_id`='$request' and `deletes`='0'", "", "");



    if ($wd_data['nr'] > 0) {



        $from_id = $wd_data['result'][0]['requested_by'];



        $cus_prefer = $wd_data['result'][0]['cus_prefer'];



        $from_name1 = select_top_name($con, "user_register", "name", "`id`='$from_id' and `deletes`='0'", "name", "");
        
        $from_name2 = select_top_name($con, "user_register", "lname", "`id`='$from_id' and `deletes`='0'", "lname", "");

        $from_name = $from_name1 . ' '.$from_name2;

        $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$from_id' and `deletes`='0'", "roll_id", "");



        $mobile = select_top_name($con, "user_register", "mobile", "`id`='$from_id' and `deletes`='0'", "mobile", "");



        $email = select_top_name($con, "user_register", "email", "`id`='$from_id' and `deletes`='0'", "email", "");



        $output = '';



        $output .= '<div class="row" style="justify-content: center;">



                                <div class="row mb-3">



                                        <div class="col-4">



                                                <p class="mdtext">Transfer To </p>



                                        </div>



                                        <div class="col-2">



                                        <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" data-bs-placement="bottom" data-html="true"  title="' . $mobile . ' / ' . $email . '"><span style="cursor: pointer;color: #136e1a !important;" class="fa fa-exchange""></span></a>



                                        </div>



                                        <div class="col-6">



                                                <p>' . $from_name . '</p>



                                        </div>



                                </div>



                                <div class="row mb-3">



                                            <div class="col-6">



                                                     <p class="mdtext">Amount </p>



                                            </div>



                                            <div class="col-6">



                                                        <p>AED ' . $wd_data['result'][0]['amount'] . '</p>



                                            </div>



                                </div>';



        if ($roll_id == 0) {



            if ($cus_prefer == 'bank') {



                $output .= '



                <div class="row mb-3" style="background-color: #f8f8f8;">



                <div class="row mb-3">



                <div class="col-12">



                         <p class="mdtext" style="color: firebrick;">Bank Details</p>



                </div>



                </div>







                <div class="row mb-3">



                <div class="col-6">



                         <p class="mdtext">Bank Name </p>



                </div>



                <div class="col-6">



                            <p>' . $wd_data['result'][0]['bank_name'] . '</p>



                </div>



                </div>

                <div class="row mb-3">



                <div class="col-6">



                         <p class="mdtext">Branch Name </p>



                </div>



                <div class="col-6">



                            <p>' . $wd_data['result'][0]['branch_name'] . '</p>



                </div>



                </div>

                <div class="row mb-3">



                <div class="col-6">



                         <p class="mdtext">Branch Code </p>



                </div>



                <div class="col-6">



                            <p>' . $wd_data['result'][0]['branch_code'] . '</p>



                </div>



                </div>





                <div class="row mb-3">



                <div class="col-6">



                         <p class="mdtext">Account Holder Name </p>



                </div>



                <div class="col-6">



                            <p>' . $wd_data['result'][0]['achname'] . '</p>



                </div>



                </div>







                <div class="row mb-3">



                <div class="col-6">



                         <p class="mdtext">Account Type </p>



                </div>



                <div class="col-6">



                            <p>' . $wd_data['result'][0]['acctype'] . '</p>



                </div>



                </div>







                <div class="row mb-3">



                <div class="col-6">



                         <p class="mdtext">Account Number </p>



                </div>



                <div class="col-6">



                            <p>' . $wd_data['result'][0]['acc_no'] . '</p>



                </div>



                </div>







                <div class="row mb-3">



                <div class="col-6">



                         <p class="mdtext">IBAN Code </p>



                </div>



                <div class="col-6">



                            <p>' . $wd_data['result'][0]['iban_code'] . '</p>



                </div>



                </div>







                <div class="row mb-3">



                <div class="col-6">



                         <p class="mdtext">SWIFT Code </p>



                </div>



                <div class="col-6">



                            <p>' . $wd_data['result'][0]['swiftcode'] . '</p>



                </div>



                </div>











                <div class="row mb-3">



                <div class="col-6">



                         <p class="mdtext">Currency </p>



                </div>



                <div class="col-6">



                            <p>' . $wd_data['result'][0]['currencyccode'] . '</p>



                </div>



                </div>



                </div>';
            } else if ($cus_prefer == 'exchange') {



                $output .= '



                <div class="row mb-3" style="background-color: #f8f8f8;">



                <div class="row mb-3">



                <div class="col-12">



                         <p class="mdtext" style="color: firebrick;">Exchange</p>



                </div>



                </div>







                <div class="row mb-3">



                <div class="col-6">



                         <p class="mdtext">Exchange ID </p>



                </div>



                <div class="col-6">



                            <p>' . $wd_data['result'][0]['exchangeid'] . '</p>



                </div>



                </div>







                </div>';
            }
        }



        $output .= ' </div>';



        $result['type'] = '1';



        $result['result'] = $output;
    } else {



        $result['type'] = '0';



        $result['result'] = 'Request Not Found!';
    }



    echo json_encode($result);
} else if ($method == "payment_status_history") {
    $result = [];

    if (empty($_SESSION['memid'])) {
        echo json_encode($result);
        return;
    }
    $formdate = $_POST["formdate"];
    $todate = $_POST["todate"];
    $paymentStatus = $_POST["paymentStatus"];
    $is_special_link = $_POST["is_special_link"];
    $draw_new_id = BlockSQLInjectionforreportrange($_POST["draw_new_id"]);
    $gatewayname = BlockSQLInjectionforreportrange($_POST["gatewayname"]);
    $ccAvenue = BlockSQLInjectionforreportrange($_POST["ccAvenue"]);



    $drawCon = '';
    $timeCon = '';
    $paymentStatusCon = '';
    $gatewayCon = '';
    $ccAvenueCon = '';
    $is_special_linkCon = '';
    if ($ccAvenue == 'notGenerated') {
        $ccAvenueCon = "AND p.transaction_id IN (SELECT order_no FROM `ccorder_lookup` WHERE `ticket` = 'NO' AND order_status = 'Shipped' ORDER BY `id` DESC)";
    } else {
        if (!empty($draw_new_id)) {
            $drawCon = "AND p.draw_id = '$draw_new_id'";
        }

        if (!empty($formdate) && !empty($todate)) {
            $timeCon = "AND p.createdon BETWEEN '$formdate 00:00:00' AND '$todate 23:59:59'";
        }

        if (!empty($paymentStatus)) {
            if ($paymentStatus == 'NULL') {
                $paymentStatusCon = "AND p.pay_re_status IS NULL";
            } else {
                $paymentStatusCon = "AND p.pay_re_status LIKE '$paymentStatus'";
            }
        }

        if (!empty($gatewayname)) {
            $gatewayCon = "AND p.gateway = '$gatewayname'";
        }

        if(!empty($is_special_link)){
            $is_special_linkCon = "AND p.is_special_link = '$is_special_link'";
        }
    }

    $query = "SELECT p.id, p.ip, p.transaction_id, p.response, p.gateway, p.createdon, p.pay_re_status, p.draw_id, p.user_id, u.name, u.lastlogin, u.created_at, u.mobile, u.nationality, u.residinglocation, u.email,
        t.ticket_no, d.name AS drawname
        FROM payment_history p
        LEFT JOIN user_register u ON p.user_id = u.id
        LEFT JOIN ticket t ON t.transaction_id = p.transaction_id AND t.deletes = '0'
        LEFT JOIN draw d ON d.id = p.draw_id AND d.deletes = '0'
        WHERE p.id != '' AND p.gateway IN ('network', 'ccavenue')  AND p.category = 'PRODUCT'
        $ccAvenueCon $timeCon $drawCon $gatewayCon $paymentStatusCon $is_special_linkCon";

    // var_dump($query);die;

    $payment_history = mysqli_query($rcon, $query);

    while ($row = mysqli_fetch_assoc($payment_history)) {
        $transaction_id = $row['transaction_id'];
        $action = '';

        $total = '';
        $reason = '';
        $cardinfo = '';
        $bankinfo = '';

        $ticketno = $row['ticket_no'];

        $result[] = [
            'drawname' => $row['drawname'],
            'ticketno' => $ticketno,
            'action' => $action,
            "creadedon" => $row['created_at'],
            "lostlogin" => $row['lastlogin'],
            "gateway" => $row['gateway'],
            "ip" => $row['ip'],
            "livein" => $row['residinglocation'],
            "nation" => $row['nationality'],
            "mobile" => $row['mobile'],
            "amt" => $total,
            "email" => $row['email'],
            "reason" => $reason,
            "cardinfo" => $cardinfo,
            "bankinfo" => $bankinfo,
            "transid" => $row['transaction_id'],
            "name" => $row['name'],
            "stauts" => $row['pay_re_status'],
            "date" => $row['createdon'],
            "baseurl" => $baseurl,
            "gresponse" => json_decode($row['response'], true)
        ];
    }

    echo json_encode($result);
} else if ($method == "customer_login_histroy") {



    $formdate = $_POST['formdate'];
    $todate = $_POST['todate'];
    $cpname = $_REQUEST['cpname'];

    $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC ", "roll_id", "");



    if ($_SESSION['memid'] == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly Login First & Try Again";
        goto resultJ00;
    }




    if ($formdate != '' && $todate != '') {
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "AND `lastlogin` BETWEEN '$fd 00:00:00' AND '$td 23:59:59'";
    }
    $c_createdfor = '';
    if ($cpname != '') {
        $cpname = "AND `nationality` = '$cpname'";
    } else {
        $cpname = '';
    }

    $user_register = select_query($con, "user_register", "", "`roll_id`='0' AND `deletes` = '0' $contype $c_createdfor $cpname order by `id` DESC ", "", "");

    foreach ($user_register['result'] as $key => $value) {


        $result[] = [
            "nation" => $value['nationality'], "mobile" => $value['mobile'],   "email" => $value['email'], "livein" => $value['id'], "creadedon" => $value['created_at'],
            "lostlogin" => $value['lastlogin'], "name" => $value['name']
        ];
    }

    resultJ00:
    echo json_encode($result);
} else if ($method == "carton_status_history") {


    $result = [];




    if ($_SESSION['memid'] == '') {
        goto gh89;
    }

    $type = 'OT';

    $formdate = BlockSQLInjectionforreportrange($_POST["formdate"]);
    $todate = BlockSQLInjectionforreportrange($_POST["todate"]);
    $paymentStatus = BlockSQLInjection($_POST["paymentStatus"]);

    $draw_new_id = $_POST['draw_new_id'];
    $gatewayname = $_POST['gatewayname'];
    $ccAvenue = $_POST['ccAvenue'];


    $drawname = '';
    $timeCon = '';
    $paymentStatusCon = '';
    $drawCon = '';
    $gatewayCon = '';
    $ccAvenueCon = '';

    if ($ccAvenue == 'notGenerated') {



        $ccAvenueCon = 'AND p.transaction_id IN (SELECT order_no FROM `ccorder_lookup` WHERE `ticket` = "NO" AND order_status = "Shipped" ORDER BY `id` DESC)';
    } else {

        if ($draw_new_id != '') {
            $drawCon = "AND p.draw_id = '$draw_new_id'";
            $drawname = select_top_name($con, "draw", "name", "`id`='$draw_new_id' and `deletes`='0'", "name", "");
        }

        if ($formdate != '' && $todate != '') {
            $timeCon = "AND p.createdon BETWEEN '$formdate' AND '$todate'";
        }

        if ($paymentStatus != '') {
            $paymentStatusCon = "AND p.pay_re_status LIKE '$paymentStatus'";
        }

        if ($gatewayname != '') {
            $gatewayCon = "AND p.gateway = '$gatewayname'";
        }
    }


    $payment_history = mysqli_query($con, "SELECT p.ip,  p.transaction_id,  p.response, p.gateway, p.createdon, p.pay_re_status, p.draw_id, p.user_id, u.name, u.lastlogin, u.created_at, u.mobile, u.nationality, u.residinglocation, u.email FROM payment_history p INNER JOIN user_register u ON p.user_id = u.id WHERE p.id != '' AND p.pay_re_status NOT IN ('COUPON', 'WALLET', '') AND p.reference != '' AND p.gateway != '' AND p.category = 'CARTON' $ccAvenueCon $timeCon $drawCon $gatewayCon $paymentStatusCon");

    while ($row = mysqli_fetch_assoc($payment_history)) {



        $transaction_id = $row['transaction_id'];
        $action = '';
        $data = json_decode($row['response'], true);
        $gateway = '';
        if ($row['gateway'] == '' ||  $row['gateway'] == 'network') {
            $total = intval($data['amount']['value']) / 100;
            $gateway = '<span style="color: red; font-weight: bolder;">Network</span>';
            $reason = $data['_embedded']['payment'][0]["authResponse"]["resultMessage"];
            $cardinfo = $data['_embedded']['payment'][0]["paymentMethod"]["pan"];
            $bankinfo = $data['_embedded']['payment'][0]["paymentMethod"]["issuingOrg"];
        } else {
            $total = ($data['order_amt'] == '' || $data['order_amt'] == null) ? $data['amount'] : $data['order_amt'];
            $gateway = '<span style="color: blue; font-weight: bolder;">CCAvenue</span>';
        }

        $ticketno = select_query($con, "carton_order", "", "`transaction_id`='$transaction_id' AND `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
        // var_dump($ticketno);die;
        if ($ticketno['nr'] > 0) {
            // $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' . $transaction_id . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span style="font-size: 20px !important;" class="fa fa-file-text-o"></span></a>';
            // $action .= '<a target="_blank" href="' . $baseurl . 'ticket-view/' . $transaction_id . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span style="font-size: 20px !important;" class="fa fa-files-o"></span></a>';
        } else {
            if ($row['gateway'] == '' ||  $row['gateway'] == 'network') {
                $action = '<a class="btn text-danger btn-sm" href="' . $adminurl . 'cron/cartonRun.php?transid=' . $transaction_id . '" target="_blank" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-check-circle" style="font-size: 20px !important; color: red;">&nbsp;Run</span></a>';
                // $action .= '<a target="_blank"  class="btn text-primary btn-sm" data-bs-toggle="tooltip" onclick="requery(' . "'$transaction_id'" . ')" data-bs-original-title="View Receipt"><span class="fa fa-check-circle-o" style="font-size: 20px !important; color: red;">&nbsp;Run</span></a>';
            } else {
                $action = '<a class="btn text-danger btn-sm" href="' . $adminurl . 'cron/cartonRun.php?transid=' . $transaction_id . '" target="_blank" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-check-circle" style="font-size: 20px !important; color: red;">&nbsp;Run</span></a>';
            }
        }



        // $carton_order = select_query($con, "carton_order", "", "`transaction_id` = '$transaction_id' and `deletes`='0' order by `id` DESC LIMIT 1", "", "");
        // if ($carton_order['nr'] > 0) {
        //     $total_point = $carton_order['result'][0]['total_point'];
        //     $total_cash = $carton_order['result'][0]['total_cash'];
        //     $total_bonus = $carton_order['result'][0]['total_bonus'];
        //     $cartons =  $carton_order['result'][0]['c_count'];
        //     $bottle =  $carton_order['result'][0]['bottle'];
        // }
        $query = 'SELECT * FROM (SELECT t.carton_order_id , t.carton_ids, co.c_count, co.bottle, co.total_point, co.total_cash, co.total_bonus, co.transaction_id , co.createdon FROM ( SELECT v.carton_order_id, GROUP_CONCAT(v.cname SEPARATOR ", ") AS "carton_ids" FROM (SELECT cd.carton_order_id , CONCAT(c.tier_level, " (", c.cartons, ")") AS "cname" FROM `carton_details` cd INNER JOIN carton c ON cd.carton_id = c.id WHERE cd.deletes = "0" ORDER BY cd.carton_id ASC) AS v GROUP BY v.carton_order_id) AS t INNER JOIN carton_order co ON t.carton_order_id = co.id WHERE co.deletes = "0") AS s WHERE s.transaction_id = ' . "'$transaction_id'" . ' ORDER BY s.carton_order_id DESC LIMIT 1';
        // var_dump($query);
        // die;

        $carton_order = mysqli_query($con, $query);
        if (mysqli_num_rows($carton_order) > 0) {
            $row1 = mysqli_fetch_assoc($carton_order);
            $total_point = $row1['total_point'];
            $total_cash = $row1['total_cash'];
            $total_bonus = $row1['total_bonus'];
            $cartons =  $row1['c_count'];
            $bottle =  $row1['bottle'];
            $carton_ids = $row1['carton_ids'];
        }


        // if ($drawname == '') {
        //     $drawname = select_top_name($con, "draw", "name", "`id`='$row[draw_id]' and `deletes`='0'", "name", "");
        // }


        $result[] = [
            'total_point'  => $total_point,
            'total_bonus'  => $total_bonus,
            'total_cash' => $total_cash,
            'drawname' => $drawname,
            'ticketno' =>  $ticketno,
            'action' =>  $action,
            "creadedon" =>  $row['created_at'],
            "lostlogin" =>  date("d-M-Y g:i a", strtotime($row['lastlogin'])),
            "gateway" => $gateway,
            "ip" => $row['ip'],
            "livein" => $row['residinglocation'],
            "nation" => $row['nationality'],
            "mobile" => $row['mobile'],
            "amt" => (int)$total,
            "email" => $row['email'],
            "reason" => $reason,
            "cardinfo" => $cardinfo,
            "bankinfo" => $bankinfo,
            "transid" => $row['transaction_id'],
            "name" => $row['name'],
            "stauts" => $row['pay_re_status'],
            "date" => date("d-M-Y g:i a", strtotime($row['createdon'])),
            "cartons" => $cartons,
            "bottle" => $bottle,
            "carton_ids" =>  $carton_ids
        ];
    }

    gh89:
    echo json_encode($result);
} else if ($method == 'get_draw_date') {
    $result = [];
    $drawid = $_POST['drawid'];
    if ($drawid == '') {
        $result['type'] = '0';
        $result['result'] = 'Kindly Select the Draw';
        goto resultsGVI;
    }


    $draw = select_query($rcon, "draw", "", "`id` = '$drawid' AND `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
    if ($draw['nr'] < 1) {
        $result['type'] = '0';
        $result['result'] = 'Draw not found!';
        goto resultsGVI;
    }
    $result['type'] = '1';
    $result['start'] = date('Y-m-d', strtotime($draw['result'][0]['ticket_start_datetime']));
    $result['end'] =  date('Y-m-d', strtotime($draw['result'][0]['ticket_end_datetime']));

    resultsGVI:
    echo json_encode($result);
} else if ($method == 'Network_history') {
    $result = [];
    $formdate = BlockSQLInjection($_POST["formdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);
    // $formdate = $_POST['formdate'];
    // $todate = $_POST['todate'];
    if ($formdate != '' && $todate != '') {
        $timeCon = "AND `Date_Time` BETWEEN '$formdate' AND '$todate'";
    }

    $network = select_query($con, "network_order_lookup", "", "`id` != '' $timeCon ORDER BY `id` DESC", "", "");

    if ($network['nr'] > 0) {
        foreach ($network['result'] as $key => $value) {
            $result[] = [
                'transid' => $value['Merchant_Defined_Order_Number'],
                'status' => $value['Order_Status'],
                'paystatus' => $value['Payment_Status'],
                'date' => date("d-M-Y g:i a", strtotime($value['Date_Time'])),
                'amt' => $value['Total'],
                'email' => $value['Card_Holder_Email'],
                'capdate' => date("d-M-Y g:i a", strtotime($value['Captured_Date']))
            ];
        }
    }


    echo json_encode($result);
} else if ($method == 'Check_Payemnt_Status_CC') {

    $result = [];
    $transaction_id = $_POST['transid'];

    $oticket = select_query($con, "ticket", "", "`transaction_id` = '$transaction_id' and `deletes`='0' order by `id` DESC", "", "");

    if ($oticket['nr'] > 0) {
        $result['type'] = 0;
        $result['result'] = 'Ticket Already Generated.';
    } else {
        $payment_history = select_query($con, "payment_history", "", "`transaction_id` = '$transaction_id' AND `gateway` = 'ccavenue' AND  `reference` != '' ORDER BY `id` DESC LIMIT 1", "", "");

        if ($payment_history['nr'] > 0) {

            $id = $payment_history1['result'][0]['id'];

            $ccRequest = [
                "order_no" => $transaction_id,
            ];


            $encrypted_data = encrypt(json_encode($ccRequest), $working_key);

            $ccURL = 'https://login.ccavenue.ae/apis/servlet/DoWebTrans?enc_request=' . $encrypted_data . '&access_code=' . $access_code . '&command=orderStatusTracker&request_type=JSON&response_type=JSON&version=1.1';
            $responseData = run_Api('POST', $ccURL);
            parse_str($responseData, $jsonData);

            $status = (int) $jsonData['status'];

            if ($status === 0) {

                $encResponse = trim($jsonData['enc_response']);
                $rcvdString = decrypt($encResponse, $working_key);
                $ccResponse = json_decode($rcvdString, true);
                $order_status = $ccResponse['order_status'];

                if ($order_status != '') {
                    if ($order_status == 'Successful' || $order_status == 'Shipped') {
                        $oticket = select_query($con, "ticket", "", "`transaction_id` = '$transaction_id' and `deletes`='0' order by `id` DESC", "", "");
                        if ($oticket['nr'] > 0) {
                            $result['type'] = 0;
                            $result['result'] = 'Ticket Already Generated.';
                        } else {
                            $result['type'] = 1;
                            $result['result'] = $order_status;
                            $result['output'] = '<button type="button" onclick = "ccotpreview(' . "'$transaction_id'" . ')" class="btn btn-primary">Preview Ticket</button>';
                        }
                    } else {
                        $result['type'] = 0;
                        $result['result'] = 'Order Status Not Found.';
                    }
                } else {
                    $result['type'] = 0;
                    $result['result'] = 'Order Status Not Found.';
                }
            } else {
                $result['type'] = 0;
                $result['result'] = 'Something Went Wrong!!.';
            }
        } else {
            $result['type'] = 0;
            $result['result'] = 'Something Went Wrong!!.';
        }
    }

    echo json_encode($result);
} else if ($method == 'ccotpreview') {



    $result = [];
    $transaction_id = $_POST['transid'];

    $oticket = select_query($con, "ticket", "", "`transaction_id` = '$transaction_id' and `deletes`='0' order by `id` DESC", "", "");

    if ($oticket['nr'] > 0) {
        $result['type'] = 0;
        $result['result'] = 'Ticket Already Generated.';
    } else {
        $payment_history = select_query($con, "payment_history", "", "`transaction_id` = '$transaction_id' AND `status` = '0' ORDER BY `id` DESC", "", "");

        if ($payment_history['nr'] > 0) {

            $ccRequest = [
                "order_no" => $transaction_id,
            ];
            $encrypted_data = encrypt(json_encode($ccRequest), $working_key);

            $ccURL = 'https://login.ccavenue.ae/apis/servlet/DoWebTrans?enc_request=' . $encrypted_data . '&access_code=' . $access_code . '&command=orderStatusTracker&request_type=JSON&response_type=JSON&version=1.1';
            $responseData = run_Api('POST', $ccURL);
            parse_str($responseData, $jsonData);

            $status = (int) $jsonData['status'];

            if ($status === 0) {
                $encResponse = trim($jsonData['enc_response']);
                $rcvdString = decrypt($encResponse, $working_key);
                $ccResponse = json_decode($rcvdString, true);
                $order_status = $ccResponse['order_status'];

                $response = json_decode($payment_history['result'][0]['response'], true);
                $user_id = $payment_history['result'][0]['user_id'];
                $checkout_response = json_decode($payment_history['result'][0]['checkout_response'], true);

                if ($order_status != '') {
                    if ($order_status == 'Successful' || $order_status == 'Shipped') {
                        $oticket = select_query($con, "ticket", "", "`transaction_id` = '$transaction_id' and `deletes`='0' order by `id` DESC", "", "");
                        if ($oticket['nr'] > 0) {
                            $result['type'] = 0;
                            $result['result'] = 'Already Ticket Generated';
                        } else {
                            $username = select_top_name($con, "user_register", "name", "`id`='$user_id' and `deletes`='0'", "name", "");
                            $mobile = select_top_name($con, "user_register", "mobile", "`id`='$user_id' and `deletes`='0'", "mobile", "");
                            $date = date("Y-m-d H:i:s");

                            $draw = select_query($con, "draw", "", "`deletes`='0' and `status`='Active' and `ticket_start_datetime`<'$date' and `ticket_end_datetime`>'$date'   order by `id` ASC", "", "");

                            if ($draw['nr'] > 0) {
                                $drawid = $draw['result'][0]['id'];
                                $drawname = $draw['result'][0]['name'];
                                $drawdate = date("d M Y", strtotime($draw['result'][0]['result_datetime']));
                            }

                            $count = intval($checkout_response['item1']) + intval($checkout_response['item2']) + intval($checkout_response['item3']) + intval($checkout_response['item4']);

                            $output = '';
                            $output .= '
                                                        <table style="width: 100%;" cellpadding="5">
                                                        <tbody>
                                                           <tr class="table-wrapper">
                                                                <td colspan="4" style="text-align: center; "><span class="Ticket" style="font-weight:bold; font-size:16px; "><br>Ticket Details<br></b><br></td>
                                                          </tr>
                                                           <tr>
                                                              <th colspan="2" style="text-align: center;">CUSTOMER NAME</th>
                                                              <th colspan="2" style="text-align: center;">MOBILE NO. <br></th>
                                                         </tr>
                                                         <tr>
                                                              <td colspan="2" style="text-align: center;">';
                            $output .= $username;
                            $output .= '</td><td colspan="2" style="text-align: center;">';
                            $output .= $mobile;
                            $output .= '</td>
                                                     </tr>
                                                     <tr style="    border-bottom: 1px dashed #a9a9a9;">
                            </tr>
                                                     <tr>
                                                               <td colspan="4" style="text-align: center;font-weight:bold;"><br><h4>';
                            $output .= $drawname;
                            $output .= '</h4></td>
                                                          </tr>
                                                             <tr style="    border-bottom: 1px dashed #a9a9a9;">
                            </tr>
                                                 <tr>
                                                     <th colspan="2" style="text-align: center;">GRAND PRIZE UPTO</th>
                                                     <th colspan="2" style="text-align: center;">ISSUED  ON</th>
                                                     </tr>
                                                     <tr>
                                                 <td colspan="2" style="text-align: center;">
                                                     AED 1,000,000
                                                    </td>
                                                <td colspan="2" style="text-align: center;">';
                            $output .= date('d M Y');
                            $output .= '</td>
                                                    </tr>
                                                       <tr style="    border-bottom: 1px dashed #a9a9a9;">
                        </tr>
                               <tr>
                                    <td colspan="4" style="text-align: center;">
                            <table style="text-align: center; width:100%">
                                <tr>
                                    <th colspan="4" style="text-align: center;">
                                    <br>Rolling Ball Draw Prizes<br>
                                    </th>
                                </tr>
                                <tr >
                                    <th style="text-align: center; width: 33%;">
                                        1ST PRIZE
                                    </th>
                                    <th style="text-align: center; width: 33%;">
                                        2ND PRIZE
                                    </th>
                
                                    <th style="text-align: center; width: 33%;">
                                        3RD PRIZE
                                    </th>
                                </tr>
                                <tr>
                                <th style="text-align: center; width: 33%;">
                                     UPTO
                                </th>
                                <th style="text-align: center; width: 33%;">
                                    UPTO
                                </th>
                                <th style="text-align: center; width: 33%;">
                                    UPTO
                                </th>
                            </tr>
                                <tr>
                                    <td style="text-align: center; width: 33%;">
                                        AED 25,000.00
                                    </td>
                                    <td style="text-align: center; width: 33%;">
                                        AED 2,500.00
                                    </td>
                                    <td style="text-align: center; width: 33%;">
                                        AED 250.00
                                    </td>
                                </tr> </table>
                                  </td>
                                </tr>
                                   <tr style="border-bottom: 1px dashed #a9a9a9;">
                            </tr>
                                <tr>
                                    <th>
                                        <br> TOTAL LINES
                                    </th>
                                </tr>
                                <tr>
                                    <td>';
                            $output .= $count;

                            $output .= '</td>
                
                                </tr>
                                   <tr style="border-bottom: 1px dashed #a9a9a9;">
                            </tr>
                                <tr>
                                    <th>
                                        Products
                                    </th>
                
                                    <th>
                                        Lines
                                    </th>
                                    <th>
                                        My<span font-size: 22px;
                                  font-family: "Montserrat", sans-serif !important; >3</span>                                      Numbers
                                    </th>';
                            $output .= '</tr>';
                            $pt = "4";


                            for ($x = 1; $x <= $pt; $x++) {
                                $tval = "item" . $x;
                                $numlines = intval($checkout_response[$tval]);


                                if ($numlines > 0) {

                                    $product = select_query($con, "product", "", "`id`='$x'  ", "", "");
                                    $output .= '<tr><td>AED ' . number_format((float) $product['result'][0]['rate'], 2, '.', '') . '</td>';
                                    $output .= '<td>' . $numlines . '</td><td>';

                                    if ($x == "1") {
                                        $rowvalue = "a";
                                    } else if ($x == "2") {
                                        $rowvalue = "b";
                                    } else if ($x == "3") {
                                        $rowvalue = "c";
                                    } else if ($x == "4") {
                                        $rowvalue = "d";
                                    } else {
                                        $rowvalue = "";
                                    }
                                    for ($g = 1; $g <= $numlines; $g++) {
                                        $checkline = "itemid_row" . $rowvalue . "_" . $g;
                                        if ($checkout_response[$checkline] != "") {
                                            $my3number = $checkout_response[$checkline];
                                            $output .= $my3number . '<br>';
                                        }
                                    }
                                    $output .= '</td>';
                                    $output .= '</tr><tr style="border-bottom: 1px dashed #1860a4;"></tr>';
                                }
                            }

                            $output .= '
                                <tr style="border-bottom: 1px dashed #a9a9a9; padding-top:12px;"></tr>
                            <tr>
                                <td colspan="4" style="text-align: center;"><br>Rolling Ball Draw Date: ' . $drawdate . '<br>Raffle Draw Date: ' . raffleDrawDate($con, $dubaidate_time, 'd M Y') . '</td>
                            </tr>
                                <tr>
                                    <td colspan="4" style="text-align: center;">
                                        All Other Terms and Conditions Apply
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                            ';

                            $result["output"] = $output;
                            $result['otreigiterbtn'] = ' <button class="btn btn-success my-1" type="button" id="buybtn" onclick="ccotregisteroffline(' . "'$transaction_id'" . ',' . "'$drawid'" . ')">Confirm</button>';
                            $result['type'] = 1;
                            $result['result'] = $checkout_response;
                        }
                    } else {
                        $result['type'] = 0;
                        $result['result'] = 'Order Status Not Found';
                    }
                } else {
                    $result['type'] = 0;
                    $result['result'] = 'Order Status Not Found';
                }
            } else {
                $result['type'] = 0;
                $result['result'] = $status;
            }
        }
    }

    echo json_encode($result);
} else if ($method == 'ccotregisteroffline') {

    $transaction_id = $_POST['transid'];
    $draw_id = $_POST['drawid'];

    $payment_history = select_query($con, "payment_history", "", "`transaction_id` = '$transaction_id' and `status` = '0'", "", "");

    if ($payment_history['nr'] > 0) {
        if ($draw_id != '' && $draw_id != 0) {
            $oticket = select_query($con, "ticket", "", "`transaction_id` = '$transaction_id' and `deletes`='0' order by `id` DESC", "", "");

            if ($oticket['nr'] < 1) {

                $ccRequest = [
                    "order_no" => $transaction_id,
                ];

                $encrypted_data = encrypt(json_encode($ccRequest), $working_key);

                $ccURL = 'https://login.ccavenue.ae/apis/servlet/DoWebTrans?enc_request=' . $encrypted_data . '&access_code=' . $access_code . '&command=orderStatusTracker&request_type=JSON&response_type=JSON&version=1.1';
                $responseData = run_Api('POST', $ccURL);
                parse_str($responseData, $jsonData);

                $encResponse = trim($jsonData['enc_response']);
                $rcvdString = decrypt($encResponse, $working_key);
                $payData = json_decode($rcvdString, true);
                $state = $ccResponse['order_status'];

                $response = json_decode($payment_history['result'][0]['response'], true);
                $user_id = $payment_history['result'][0]['user_id'];
                $checkout_response = json_decode($payment_history['result'][0]['checkout_response'], true);

                $orderReference = $payment_history['result'][0]['reference'];
                $transaction_id = $payment_history['result'][0]['transaction_id'];

                $name = select_top_name($con, "user_register", "name", "`id`='$user_id' and `id`!='' and `deletes`='0' and `status`='0' and `roll_id`='0' order by `id` DESC ", "name", "");
                $mobile = select_top_name($con, "user_register", "mobile", "`id`='$user_id' and `id`!='' and `deletes`='0' and `status`='0' and `roll_id`='0' order by `id` DESC ", "mobile", "");
                $email = select_top_name($con, "user_register", "email", "`id`='$user_id' and `id`!='' and `deletes`='0' and `status`='0' and `roll_id`='0' order by `id` DESC ", "email", "");
                // get user address
                $address = select_top_name($con, "user_register", "address", "`id`='$user_id' and `id`!='' and `deletes`='0' and `status`='0' and `roll_id`='0' order by `id` DESC ", "address", "");
                $date = date("Y-m-d H:i:s");

                $count = intval($checkout_response['item1']) + intval($checkout_response['item2']) + intval($checkout_response['item3']) + intval($checkout_response['item4']);

                $post = $checkout_response;

                if ($post['item1'] > 0) {

                    $tentotal = $post['item1'] * 10;
                } else {

                    $tentotal = "0";
                }

                if ($post['item2'] > 0) {

                    $twentytotal = $post['item2'] * 20;
                } else {

                    $twentytotal = "0";
                }

                if ($post['item3'] > 0) {

                    $thirtytotal = $post['item3'] * 50;
                } else {

                    $thirtytotal = "0";
                }

                if ($post['item4'] > 0) {

                    $fourtytotal = $post['item4'] * 100;
                } else {

                    $fourtytotal = "0";
                }

                $finaltotal = $tentotal + $twentytotal + $thirtytotal + $fourtytotal;

                $totline = $post['item1'] + $post['item2'] + $post['item3'] + $post['item4'];

                $onepercen = ($finaltotal / 105);

                $total_amount = number_format(($onepercen * 100), 2);

                $tax_value = number_format(($finaltotal - $total_amount), 2);

                $datetime = date("Y-m-d H:i:s");

                $Arr = array(
                    "draw_id" => $draw_id,
                    "user_id" => $user_id,
                    "total_lines" => $totline,
                    "sale_from" => 1,
                    "purchase_datetime" => $datetime,
                    "net_total" => $finaltotal,
                    "tax_percentage" => 5,
                    "	tax_value" => $tax_value,
                    "total_amount" => $total_amount,
                    "payment_by" => "Card",
                    "transaction_id" => $transaction_id,

                );

                $ticket = insert($con, "ticket", "", $Arr, "", "", "");
                $ottype = "OT";

                $ticketnumber = $ticket['id'];
                $ticketid = "OT" . sprintf("%04d", $ticket['id']);
                $bresponse = json_encode($payData);
                $Arr1 = array("ticket_id" => $ticket['id'], "type" => "OT", "firstname" => $name, "lastname" => '', "emailid" => $email, "address" => $address, "city" => '', "country" => '', "response" => $bresponse);
                $invoice = insert($con, "invoice", "", $Arr1, "", "", "");

                $invoiceid = $invoice['id'];

                ///////////////////////////////  POINT TRANSACTION ////////////////////////

                $nowpoints = select_top_name($con, "ldbank", "points", "`deletes`='0' and `id`='9999999'  ", "points", "");
                $balancepoint = $nowpoints - $finaltotal;
                $topoints = select_top_name($con, "user_register", "t_point", "`deletes`='0' and `status`='0'  and `id`!='' and `id`='$user_id'  ", "t_point", "");

                // NEW //
                $t_earning = select_top_name($con, "user_register", "t_earning", "`deletes`='0' and `status`='0'  and `id`!='' and `id`='$user_id'  ", "t_earning", "");
                $topoints = $topoints + $t_earning;

                $tobalancepoint = $topoints + $finaltotal;

                $Arrd = array("from_id" => "9999999", "type" => "credit", "points" => $finaltotal, "from_opening" => $nowpoints, "from_closing" => $balancepoint, "to_id" => $user_id, "to_opening" => $topoints, "to_closing" => $tobalancepoint, "invoice_id" => $invoiceid, "createdon" => $datetime);
                $invoice = insert($con, "points_transaction", "", $Arrd, "", "", "");
                $Arrd = array("from_id" => "$user_id", "type" => "order", "points" => $finaltotal, "from_opening" => $tobalancepoint, "from_closing" => $topoints, "to_id" => "9999999", "to_opening" => $balancepoint, "to_closing" => $nowpoints, "invoice_id" => $ticket['id'], "createdon" => $datetime);
                $invoice = insert($con, "points_transaction", "", $Arrd, "", "", "");

                $Arr2 = array("ticket_no" => $ticketid, "invoice_no" => $invoiceid);

                $ins_update = setupdate($con, "ticket", "`id`='$ticket[id]'", $Arr2, "", "", "");
                $pt = "4";
                $ot = 1;
                $k = "";

                for ($x = 1; $x <= $pt; $x++) {
                    $tval = "item" . $x;
                    if ($post[$tval] > 0) {

                        $k++;

                        $product = select_query($con, "product", "", "`id`='$x'  ", "", "");

                        foreach ($product['result'] as $key => $productinfolist) {
                        }

                        if ($x == "1") {

                            $rowvalue = "a";
                        } else if ($x == "2") {

                            $rowvalue = "b";
                        } else if ($x == "3") {

                            $rowvalue = "c";
                        } else if ($x == "4") {

                            $rowvalue = "d";
                        } else {

                            $rowvalue = "";
                        }

                        $numlines = $post[$tval];

                        for ($g = 1; $g <= $numlines; $g++) {

                            $checkline = "itemid_row" . $rowvalue . "_" . $g;

                            if ($post[$checkline] != "") {

                                $my3number = "";

                                $linedate = "";

                                $my3number = $post[$checkline];

                                $raffle_id = 'OT' . $ticketnumber . str_pad($ot, 2, "0", STR_PAD_LEFT);

                                $linedate = array("my3number" => $my3number, "user_id" => $user_id, "ticket_id" => $ticket['id'], "draw_id" => $draw_id, "agent_id" => 0, "product_id" => $x, "orders" => $ticketid, "orders" => $ticket['id'], "raffle_id" => $raffle_id, "type" => "OT", "invoice_no" => $invoiceid);

                                $lines = insert($con, "ticket_lines", "", $linedate, "", "", "");

                                $ot++;
                            }
                        }
                    }
                }

                $printurlf = $baseurl . "ticket-view/" . $transaction_id;
                $printinvoice = $baseurl . "invoice/" . $transaction_id;
                $printurl = get_tiny_url($printurlf);
                $invoiceurl = get_tiny_url($printinvoice);

                if (substr($mobile, 0, 3) == "971") {

                    $messages1 = 'Ticket ID #' . $ticketid . '.';
                    $query = select_query($con, "ticket_lines", "", "`ticket_id`='$ticketnumber' and `type` = '$ottype' and `ticket_id`!='' and `deletes` = '0' group by `product_id` order by `product_id` ASC  ", "", "");

                    foreach ($query['result'] as $key => $valuelist) {
                        $p_id = $valuelist['product_id'];
                        $t_id = $valuelist['orders'];

                        $product = select_query($con, "product", "", "`id`='$valuelist[product_id]'  ", "", "");

                        $messages1 .= 'CAT-AED ' . round($product['result'][0]['rate']) . '. ';


                        $io = 1;
                        $mynumber = select_query($con, "ticket_lines", "", "`ticket_id`='$ticketnumber' and `product_id`='$p_id' and `type` = '$ottype' and `deletes` = '0'", "", "");
                        $rcount = count($mynumber['result']);
                        foreach ($mynumber['result'] as $key => $mynumber1) {
                            $messages1 .= $mynumber1['my3number'];

                            if ($io == $rcount) {
                                $messages1 .= '. ';
                            } else {
                                $messages1 .= ', ';
                            }

                            $io++;
                        }
                    }

                    $messages1 .= 'for an Amounts of AED ' . number_format((float) $finaltotal, 2, '.', '') . ' for more info. ( ' . $printurl . ' ),. TC apply. Good Luck!!!';

                    $templateid = "";
                    $log = mysqli_query($con, "INSERT INTO `smslog` (`gateway`, `details`,`mobile`,`ip`,`datetime`,`status`, `smssendstatus`) VALUES ('', '$messages1','$mobile','','$dubaidate_time','', '0')");
                    sendsms($con, $mobile, $messages1, $templateid);
                }

                $subject = "Purchase Confirmation";

                $messages = '<!DOCTYPE html>

            <html lang="en">

            <head>

              <meta charset="UTF-8">

              <meta http-equiv="X-UA-Compatible" content="IE=edge">

              <meta name="viewport" content="width=device-width, initial-scale=1.0">



            </head>

            <body>

              <div>

                <div dir="ltr">

                  <table class="gmail-body-bg" style="margin: auto; min-height: 100px; color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb; border-collapse: collapse; background-image: -webkit-linear-gradient(124deg, #2e76a1 0%, #002387 100%);" border="0" width="100%" cellspacing="0" cellpadding="0">

                    <tbody>

                      <tr>

                        <td style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-style: italic;" align="center" bgcolor="">

                          <table style="margin: auto; max-width: 500px; border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0">

                            <tbody>

                              <tr>

                                <td style="font-family: Roboto Condensed,sans-serif;" align="center" valign="top">&nbsp;</td>

                              </tr>

                            </tbody>

                          </table>

                        </td>

                      </tr>

                    </tbody>

                  </table>

                  <table style="margin: auto; color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb; max-width: 500px; border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0">

                    <tbody>

                      <tr>

                        <td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic; padding: 9px 20px 20px; background: radial-gradient(circle,#fcef48 0%,#fdd206 100%); font-size: 48px; line-height: 24px;" align="center" valign="top" bgcolor="#ffffff">&nbsp;</td>

                      </tr>

                    </tbody>

                  </table>

                  <table style="margin: auto; color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb; max-width: 500px; border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0">

                    <tbody>

                      <tr>

                        <td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic; padding: 20px; font-size: 48px; line-height: 24px;" align="center" valign="top" bgcolor="#ffffff">

                          <img style="border: 0px; height: auto; line-height: 48px; outline: none; display: block;" src="' . $baseurl . 'assets/images/mail/logo.png" width="200" height="120">

                        </td>

                      </tr>

                    </tbody>

                  </table>

                  <table style="margin: auto; color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb; max-width: 500px; border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0">

                    <tbody>

                      <tr>

                        <td style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-style: italic;" align="center" bgcolor="#f4f4f4">

                          <table style="margin: auto; max-width: 500px; border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0">

                            <tbody>

                              <tr><!--<td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; padding: 0px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px;" align="center" valign="top" bgcolor="#ffffff">--> <!--  <h3 style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-size: 23px; margin: 0px;">Hi, ' . $username . ' --> <!--    <br>--> <!--  </h3>--> <!--</td>--></tr>

                            </tbody>

                          </table>

                        </td>

                      </tr>

                      <tr>

                        <td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic; padding: 6px 12px 7px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px;" align="center" valign="top" bgcolor="#ffffff">

                          <table>

                            <tbody>

                              <tr>

                                <td>

                                  <img style="border: 0px; height: auto; line-height: 24px; outline: none; left: 38px; bottom: -36px;" src="' . $baseurl . 'assets/images/mail/heloboy.png">

                                </td>

                                <td>

                                  <h3 style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-size: 23px; margin: 0px; text-align: center;">Hi, ' . $name . '

                                    <br>

                                  </h3>

                                  <br>

                                  <p style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-size: 18px; font-weight: bold; margin: 0px; text-align: center;">Thank you for your purchase

                                    <br>and donations

                                  </p>

                                  <br>

                                  <h3 style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-size: 23px; margin: 0px; text-align: center;">Ticket ID #' . $ottype . '' . $ticketnumber . '

                                    <br>

                                  </h3>

                                </td>

                              </tr>

                            </tbody>

                          </table>

                        </td>

                      </tr>

                      <tr><!--<td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic; padding: 9px 12px 7px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px;" align="center" valign="top" bgcolor="#ffffff">--> <!--  <h3 style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-size: 23px; margin: 0px;">Ticket ID #OT1209 --> <!--    <br>--> <!--  </h3>--> <!--</td>--></tr>

                    </tbody>

                  </table>

                  <table style="margin: auto; color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb; max-width: 500px; border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0">

                    <tbody>

                      <tr>

                        <td style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-style: italic; padding: 12px;" align="center" bgcolor="#ffffff">

                          <table style="margin: auto; border-collapse: collapse;" border="1" cellspacing="2" cellpadding="0">

                            <tbody>

                              <tr>

                                <th style="padding: 12px;" align="center" bgcolor="#ffffff">Product</th>

                                <th style="padding: 12px;" align="center" bgcolor="#ffffff">Lines</th>

                                <th style="padding: 12px;" align="center" bgcolor="#ffffff">My

                                  <span class="gmail-my-number" style="color: #be1e2d; font-size: 23px;">3</span>Numbers

                                </th>

                                <th style="padding: 12px;" align="center" bgcolor="#ffffff">Raffle ID</th>

                              </tr>';

                $query = select_query($con, "ticket_lines", "", "`ticket_id`='$ticketnumber'  and `type` = '$ottype' and `ticket_id`!='' and `deletes` = '0' group by `product_id` order by `product_id` ASC  ", "", "");

                foreach ($query['result'] as $key => $valuelist) {

                    $p_id = $valuelist['product_id'];

                    $t_id = $valuelist['orders'];

                    $product = select_query($con, "product", "", "`id`='$valuelist[product_id]'  ", "", "");

                    foreach ($product['result'] as $key => $productinfo) {
                    }

                    $pcountlist = select_query_count($con, "ticket_lines", "id", "`product_id`='$valuelist[product_id]' and  `type` = '$ottype' and  `orders`='$valuelist[orders]' and `deletes` = '0'", "", "");

                    $mynumber = select_query($con, "ticket_lines", "", "`ticket_id`='$ticketnumber' and `product_id`='$p_id' and  `type` = '$ottype' and `deletes` = '0'", "", "");

                    $messages .= '<tr>

            <td style="font-family: Roboto Condensed,sans-serif; padding: 12px;" align="center" bgcolor="#ffffff">AED ' . number_format((float) $productinfo['rate'], 2, '.', '') . '</td>';

                    $messages .= '<td style="font-family: Roboto Condensed,sans-serif; padding: 12px;" align="center" bgcolor="#ffffff">' . $mynumber['nr'] . '</td>';

                    $messages .= '<td style="font-family: Roboto Condensed,sans-serif; padding: 12px;" align="center" bgcolor="#ffffff">';

                    foreach ($mynumber['result'] as $key => $mynumber1) {

                        $messages .= $mynumber1['my3number'] . "<br>";
                    }

                    $messages .= '</td>';

                    $messages .= '<td style="font-family: Roboto Condensed,sans-serif; padding: 12px;" align="center" bgcolor="#ffffff">';

                    foreach ($mynumber['result'] as $key => $mynumber1) {

                        $messages .= $mynumber1['raffle_id'] . "<br>";
                    }

                    $messages .= '</td>';

                    $messages .= '</tr>';
                }

                $messages .= ' </tbody>

                          </table>

                        </td>

                      </tr>

                    </tbody>

                  </table>

                  <table style="margin: auto; color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb; max-width: 500px; border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0">

                    <tbody>

                      <tr>

                        <td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic; padding: 9px 12px 7px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px;" align="center" valign="top" bgcolor="#ffffff">

                          <h3 style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-size: 23px; margin: 0px;">Total Amount:

                            <span class="gmail-otp-bg" style="color: #be1e2d;">&nbsp;AED ' . number_format((float) $finaltotal, 2, '.', '') . '</span>

                            <br>

                          </h3>

                        </td>

                      </tr>

                    </tbody>

                  </table>

                  <table style="margin: auto; color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb; max-width: 500px; border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0">

                    <tbody>

                      <tr>

                        <td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic; padding: 9px 12px 21px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px;" align="center" valign="top" bgcolor="#ffffff">

                          <h3 style="color: #ffffff; font-family: Roboto Condensed,sans-serif; font-size: 19px; margin: 0px; padding: 9px; background: #052c8a; width: 108.453px; line-height: 1; border-radius: 11px;">

                            <a href="' . $baseurl . 'ticket-view/' . $transaction_id . '" style="color: #ffffff; text-decoration-line: none;">View Ticket</a>

                          </h3>

                        </td>

                        <td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic; padding: 9px 12px 21px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px;" align="center" valign="top" bgcolor="#ffffff">

                          <h3 style="color: #ffffff; font-family: Roboto Condensed,sans-serif; font-size: 19px; margin: 0px; padding: 9px; background: #2b8db9; width: 117.547px; line-height: 1; border-radius: 11px;">

                            <a href="' . $baseurl . 'invoice/' . $transaction_id . '" style="color: #ffffff; text-decoration-line: none;">View Invoice</a>

                          </h3>

                        </td>

                      </tr>

                    </tbody>

                  </table>

                  <br style="color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb;">

                  <table style="margin: auto; color: #000000; font-family: Times New Roman; font-size: medium; max-width: 500px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0">

                    <tbody>

                      <tr>

                        <td style="color: #666666; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic;  background: none; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; padding: 12px 9px 12px 13px; font-size: 15px; line-height: 25px;" align="center" bgcolor="#e4dcf1">
                          <p style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-size: 18px; margin: 0px; text-align: left;">Watch Rolling Ball TRI-DAILY Draw results every Monday ' . constant("resultTIME") . '(UAE Time) ' . (!checkGrandRaffleEligible($draw_no) ?  ('& <br>Grand Raffle Draw result on ' . raffleDrawDate($con, $dubaidate_time, 'dS F Y')) : '') . '</p>
                        </td>

                        <td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic; padding: 9px 13px 9px 18px; font-size: 48px; font-weight: bold; line-height: 24px; display: flex; margin-top: 25px;" align="center" valign="" bgcolor="#ffffff">
                          <img style="border: 0px; height: auto; line-height: 48px; outline: none;" src="' . $baseurl . 'assets/images/mail/fb.png">
                          <img style="border: 0px; height: auto; line-height: 48px; outline: none;" src="' . $baseurl . 'assets/images/mail/youtube.png">
                        </td>
                      </tr>
                    </tbody>
                  </table>

                  <br style="color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb;">

                  <table style="margin: auto; color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb; max-width: 500px; border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic; padding: 9px 6px 9px 18px; font-size: 48px; font-weight: bold; line-height: 24px; background: radial-gradient(circle,#fcef48 0%,#fdd206 100%);" align="center" valign="" bgcolor="#ffffff">
                          <h3 style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-size: 19px; margin: 0px; text-align: left;">
                            <span class="gmail-otp-bg" style="color: #be1e2d;">Good Luck!!!</span>&nbsp;for your
                            <br>TRI-DAILY Draws
                          </h3>
                        </td>

                        <td class="gmail-line" style="box-sizing: border-box; width: 8px;">&nbsp;</td>
                        <td style="color: #111111; font-family: Lato,Helvetica,Arial,sans-serif; font-style: italic; padding: 9px 11px 9px 22px; font-size: 48px; font-weight: bold; line-height: 24px; background: radial-gradient(circle,#fcef48 0%,#fdd206 100%);" align="center" valign="" bgcolor="#ffffff">

                          <h3 style="color: #052c8a; font-family: Roboto Condensed,sans-serif; font-size: 19px; margin: 0px; text-align: left;">
                            <img style="border: 0px; height: auto; line-height: 19px; outline: none;" src="' . $baseurl . 'assets/images/mail/arrow.png">Change
                            <br>your life
                          </h3>
                        </td>
                      </tr>
                    </tbody>
                  </table>

                  <br style="color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb;">
                  <br style="color: #000000; font-family: Times New Roman; font-size: medium; background-color: #fbfbfb;">
           
                </div>
              </div>
            </body>
            </html>';

                $emailchack = explode('@', $email);

                if (strtolower($emailchack[1]) != "littledraw.ae") {
                    $emailsend = sendemail($con, $email, $subject, $messages, '');
                }

                $draw_arr = array("response" => json_encode($payData), "pay_re_status" => $state, "status" => '1', "crontime" => $dubaidate_time);
                $Inv_update = update($con, "payment_history", "`transaction_id` = '$transaction_id' and `status` = '0'", $draw_arr, "", "", "", "");

                $order_arr = array("ticket_id" => $ticketnumber);
                $Inv_update = update($con, "orders", "`transaction_id` = '$transaction_id'", $order_arr, "", "", "", "");

                $result['type'] = 1;
                $result['result'] = 'Ticket genereated';
            } else {
                $result['type'] = 0;
                $result['result'] = 'Ticket already genereated';
            }
        } else {
            $result['type'] = 0;
            $result['result'] = 'Draw id Not found';
        }
    } else {
        $result['type'] = 0;
        $result['result'] = 'Ticket already genereated';
    }
    echo json_encode($result);
}


function identify($apikey, $idUrl)

{



    $idHead = array("Authorization: Basic " . $apikey, "Content-Type: application/vnd.ni-identity.v1+json");



    $idPost = "";



    // $idOutput = invokeCurlRequest("POST", $idUrl, $idHead, $idPost);

    $idOutput = invokeApiRequest("POST", $idUrl, $idHead, $idPost);



    return $idOutput;
}



function callback($token, $url, $orderReference)

{



    $payUrl = $url . $orderReference;



    $payHead = array("Authorization: Bearer " . $token, "Content-Type: application/vnd.ni-payment.v2+json", "Accept: application/vnd.ni-payment.v2+json");



    $payPost = '';




    $payOutput = invokeApiRequest("GET", $payUrl, $payHead, $payPost);



    return $payOutput;
}


function run_Api($method, $url)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
}
