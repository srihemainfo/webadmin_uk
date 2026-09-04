<?php
// 09-10-23     sathiya    fuctionality changes in weekly sales 
// 10-10-23     sathiya    fuctionality changes in ticket wise 



include '../../include/shi-config.php';

include '../../include/functions.php';

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

//print_r($headers);

$result = array();

$post_csrf = $headers['X-Csrf-Token'] ?? '';


if ($method == "draw_earn_graph") {

    $result = [];

    $now = date("Y-m-d H:i:s");

    $draw = select_query($con, "draw", "", "`deletes`='0' and `status`='Active' and `ticket_start_datetime`<'$now' and `ticket_end_datetime`>'$now'   order by `id` ASC", "", "");

    $drawid = $draw['result'][0]['id'];

    $oticket = mysqli_query($con, "SELECT sum(net_total) FROM ticket INNER JOIN invoice ON invoice.id = ticket.invoice_no WHERE ticket.draw_id = '$drawid' AND invoice.response != 'wallet' AND ticket.deletes = '0'");

    $row1 = mysqli_fetch_array($oticket);

    $oticket = $row1['sum(net_total)'];

    $wticket = mysqli_query($con, "SELECT sum(net_total) FROM ticket INNER JOIN invoice ON invoice.id = ticket.invoice_no WHERE ticket.draw_id = '$drawid' AND invoice.response = 'wallet' AND ticket.deletes = '0'");

    $row2 = mysqli_fetch_array($wticket);

    $wticket = $row2['sum(net_total)'];

    $aticket = select_query_sum($con, "aticket", "net_total", "`draw_id` = '$drawid' AND `deletes`='0' order by `id` DESC", "", "");

    $mticket = select_query_sum($con, "mticket", "net_total", "`draw_id` = '$drawid' AND  `deletes`='0' order by `id` DESC", "", "");

    $result["type"] = "1";

    $result["result"] = [

        ['value' => $oticket, 'name' => 'Online Ticket'],

        ['value' => $wticket, 'name' => 'Wallet Ticket'],

        ['value' => $aticket, 'name' => 'Agent Ticket'],

        ['value' => $mticket, 'name' => 'Manual Ticket'],

    ];

    echo json_encode($result);
} else if ($method == 'product_graph') {

    $result = [];

    $perDayTotal = 0;

    $now = date("Y-m-d H:i:s");

    $draw = select_query($con, "draw", "", "`deletes`='0' and `status`='Active' and `ticket_start_datetime`<'$now' and `ticket_end_datetime`>'$now'   order by `id` ASC", "", "");

    $drawid = $draw['result'][0]['id'];

    $product = select_query($con, "product", "", "`deletes`='0'", "", "");

    if ($product['nr'] > 0) {

        $today = strtotime($dubaidate_time);

        for ($i = 7; $i >= 0; $i--) {

            if ($i != 0) {

                $onebefore = date('Y-m-d', strtotime('-' . $i . ' day', $today));
            } else {

                $onebefore = date('Y-m-d', strtotime($dubaidate_time));
            }

            foreach ($product['result'] as $key => $value) {

                $product1 = select_query($con, "ticket_lines", "", "`product_id` = '$value[id]' and `draw_id` = '$drawid' and `deletes`='0' and  `createdon` BETWEEN '$onebefore 00:00:00' AND '$onebefore 23:59:59'", "", "");
                $result["result"]['day7']['AED' . intval($value['rate'])][] = intval($product1['nr']) * intval($value['rate']);

                $perDayTotal += (intval($product1['nr']) * intval($value['rate']));
            }

            $result["result"]['date'][] = $onebefore . ' (' . $perDayTotal . ')';

            $perDayTotal = 0;
        }
    }

    $result["type"] = "1";

    echo json_encode($result);
} else if ($method == 'product_graph1') {

    $result = [];

    $perDayTotal = 0;



    $product = select_query($con, "product", "", "`deletes`='0'", "", "");

    if ($product['nr'] > 0) {
        // $dubaidate_time = $draw['result'][0]['ticket_start_datetime'];
        // $dubaidate_time = date('Y-m-d', strtotime('-7 day', strtotime($dubaidate_time)));
        $today = strtotime($dubaidate_time);
        // $today = date('Y-m-d', strtotime('-7 day', $today));
        //  $today = strtotime($today);

        for ($i = 7; $i >= 0; $i--) {

            if ($i != 0) {

                $onebefore = date('Y-m-d', strtotime('-' . $i . ' day', $today));
            } else {

                $onebefore = date('Y-m-d', strtotime($dubaidate_time));
            }

            foreach ($product['result'] as $key => $value) {

                // $product1 = select_query($con, "ticket_lines", "", "`product_id` = '$value[id]' and `draw_id` = '$drawid' and `deletes`='0' and  `createdon` BETWEEN '$onebefore 00:00:00' AND '$onebefore 23:59:59'", "", "");
                $product1 = select_query($con, "ticket_lines", "", "`product_id` = '$value[id]'  and `deletes`='0' and  `createdon` BETWEEN '$onebefore 00:00:00' AND '$onebefore 23:59:59'", "", "");
                $result["result"]['day7']['AED' . intval($value['rate'])][] = intval($product1['nr']) * intval($value['rate']);

                $perDayTotal += (intval($product1['nr']) * intval($value['rate']));
            }

            $result["result"]['date'][] = $onebefore . ' (' . $perDayTotal . ')';

            $perDayTotal = 0;
        }
    }



    $result["type"] = "1";

    echo json_encode($result);
} else if ($method == 'ticket_graph') {
    $result = [];

    $perDayTotal = 0;

    $now = date("Y-m-d H:i:s");

    $draw = select_query($con, "draw", "", "`deletes`='0' and `status`='Active' and `ticket_start_datetime`<'$now' and `ticket_end_datetime`>'$now'   order by `id` ASC", "", "");

    $drawid = $draw['result'][0]['id'];

    $product = select_query($con, "product", "", "`deletes`='0'", "", "");

    if ($product['nr'] > 0) {

        $today = strtotime($dubaidate_time);

        for ($i = 6; $i >= 0; $i--) {

            if ($i != 0) {
                $onebefore = date('Y-m-d', strtotime('-' . $i . ' day', $today));
            } else {
                $onebefore = date('Y-m-d', strtotime($dubaidate_time));
            }
            $result["result"]['date'][] = $onebefore;

            $contype = "`createdon` BETWEEN '$onebefore 00:00:00' AND '$onebefore 23:59:59' AND";

            $total_ticket = [];

            $oticket = mysqli_query($con, "SELECT sum(net_total) FROM ticket INNER JOIN invoice ON invoice.id = ticket.invoice_no WHERE ticket.purchase_datetime  BETWEEN '$onebefore 00:00:00' AND '$onebefore 23:59:59' AND invoice.response != 'wallet' AND ticket.deletes = '0'");
            $row1 = mysqli_fetch_array($oticket);
            $oticket = $row1['sum(net_total)'];
            $result["result"]['day7']['ticket'][] = $oticket;

            $aticket = select_query_sum($con, "aticket", "net_total", " $contype `deletes`='0' order by `id` DESC", "", "");
            $result["result"]['day7']['aticket'][] = $aticket;

            $cpticket = select_query_sum($con, "cpticket", "net_total", " $contype `deletes`='0' order by `id` DESC", "", "");
            $result["result"]['day7']['cpticket'][] = $cpticket;

            $bpticket = select_query_sum($con, "bpticket", "net_total", " $contype `deletes`='0' order by `id` DESC", "", "");
            $result["result"]['day7']['bpticket'][] = $bpticket;

            $cticket = select_query_sum($con, "cticket", "net_total", " $contype `deletes`='0' order by `id` DESC", "", "");
            $result["result"]['day7']['cticket'][] = $cticket;


        
            $kticket = select_query_sum($con, "kticket", "net_total", " $contype `deletes`='0' order by `id` DESC", "", "");
            $result["result"]['day7']['kticket'][] = $kticket;

            $perDayTotal = 0;
        }
    }

    $result["type"] = "1";

    echo json_encode($result);
} else if ($method == 'loadDetailsWeeklyData') {
    $result = [];

    $firstWeekStart = $_POST['firstWeekStart'];
    $firstWeekEnd = $_POST['firstWeekEnd'];
    $nextWeekStart = $_POST['nextWeekStart'];
    $nextWeekEnd = $_POST['nextWeekEnd'];


    if ($firstWeekStart == '' || $firstWeekEnd == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly select the date";
        goto resultVIG;
    }

    if ($nextWeekStart == '' || $nextWeekEnd == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly select the date";
        goto resultVIG;
    }
    $product = [];
    $selectFirstWeek = mysqli_query($con, "SELECT tl.product_id as proid, CAST(p.rate AS SIGNED) AS rate , SUM(p.rate) AS 'totalAmount' FROM `ticket_lines` AS tl
    LEFT JOIN product AS p ON p.id = tl.product_id
    WHERE tl.deletes = '0' AND tl.createdon BETWEEN '$firstWeekStart' AND '$firstWeekEnd' GROUP BY tl.product_id  
    ORDER BY `tl`.`product_id` ASC");
    // $firstWeekData = mysqli_fetch_all($selectFirstWeek, MYSQLI_ASSOC);
    $firstWeekData = [];
    if (mysqli_num_rows($selectFirstWeek) > 0) {
        while ($row = mysqli_fetch_assoc($selectFirstWeek)) {
            $firstWeekData['AED ' . $row['rate']] = intval($row['totalAmount']);

            array_push($product, 'AED ' . $row['rate']);
        }
    }


    $selectNextWeek = mysqli_query($con, "SELECT tl.product_id as proid, CAST(p.rate AS SIGNED) AS rate , SUM(p.rate) AS 'totalAmount' FROM `ticket_lines` AS tl
    LEFT JOIN product AS p ON p.id = tl.product_id
    WHERE tl.deletes = '0' AND tl.createdon BETWEEN '$nextWeekStart' AND '$nextWeekEnd' GROUP BY tl.product_id  
    ORDER BY `tl`.`product_id` ASC");
    // $firstWeekData = mysqli_fetch_all($selectFirstWeek, MYSQLI_ASSOC);
    $nextWeekData = [];
    if (mysqli_num_rows($selectNextWeek) > 0) {
        while ($row = mysqli_fetch_assoc($selectNextWeek)) {
            $nextWeekData['AED ' . $row['rate']] = intval($row['totalAmount']);

            array_push($product, 'AED ' . $row['rate']);
        }
    }


    $result["type"] = "1";
    $result["result"] = 'Report Successfully Collected';
    $result['firstWeekData'] = $firstWeekData;
    $result['nextWeekData'] = $nextWeekData;
    $result['productList'] = array_unique($product);
    goto resultVIG;





    resultVIG:
    echo json_encode($result);
}
