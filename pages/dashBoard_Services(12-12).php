<?php
include '../../include/shi-config.php';
include '../../include/functions.php';
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

// var_dump($dubaidate_time);die;

if ($method == "collect_earning") {



    $result = [];
    $roll_id = select_top_name($rcon, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC ", "roll_id", "");
    $n_roll_id = intval($roll_id) + 1;
    // $now = date("Y-m-d H:i:s");
    $user = select_query($rcon, "user_register", "", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC", "", "");
    $totalagent = select_query($rcon, "user_register", "", "`created_by`='$_SESSION[memid]' and `roll_id` = '$n_roll_id' and `deletes`='0'  order by `id` DESC", "", "");
    $product = select_query($rcon, "product", "", "`deletes`='0'", "", "");
    $aticket = 0;
    $mticket = 0;
    $oticket = 0;
    $total_amt = '';
    $qcon = ($_SESSION['memid'] != 1) ? "`agent_id` = '$_SESSION[memid]' AND" : "`agent_id` != '' AND";
    $drawid = $draw_id;
    $createdon = $dcreatedon;
    $drawcon = ($drawid != '') ? "`draw_id` = '$drawid' AND" : "";
    $createdon = ($createdon != '') ? "`createdon` = '$drawid' AND" : "";

    $currentDate = date('Y-m-d', strtotime($dubaidate_time));

    // var_dump($currentDate);
    // die;

    $output = '';
    $total_Aed_Array = [];

    /////////// Total Ticket ///////////////
    if ($roll_id == 1 or $roll_id == 4) {

        $oticket = mysqli_query($con, "SELECT SUM(I.grandtotal) AS totalAmount FROM `invoice` AS I JOIN ndticket ON ndticket.id = I.ticketId WHERE I.ticketId = ndticket.id AND I.deletes ='0'AND DATE(I.createdon) = '$currentDate';");



        $row1 = mysqli_fetch_array($oticket);
        $totalAmount = $row1['totalAmount'];

        $productAmtArray = []; // This array will store product amount (AED value) and its total sum

        $sql = mysqli_query($con, "SELECT P.id, ROUND(P.rate) AS 'productAmt', IFNULL(ROUND(I.totalAmt), 0) AS 'totalAmt'
            FROM `product` AS P
            LEFT JOIN (
                SELECT I.product_id, SUM(I.grandtotal) AS 'totalAmt'
                FROM `invoice` AS I
                WHERE I.deletes = '0'
                AND DATE(I.createdon) = '$currentDate'
                GROUP BY I.product_id
            ) AS I ON P.id = I.product_id
            WHERE P.deletes = '0'");

        if (mysqli_num_rows($sql) > 0) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $productAmt = round($row['productAmt']);  // Extract AED value
                $totalAmt = $row['totalAmt'];

                // Check if productAmt already exists in the array, if so, add current totalAmt to it
                if (isset($productAmtArray[$productAmt])) {
                    $productAmtArray[$productAmt] += $totalAmt;
                } else {
                    $productAmtArray[$productAmt] = $totalAmt;
                }
            }
        }

        $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3" style="display: inline-block;"><div class="card overflow-hidden"><div class="card-body"><div class="d-flex"><div class="mt-2"><h6 class="">Total Amount</h6><h2 class="mb-0 number-font" id="totalonline"  style="text-align: left;">AED ';

        // $output .= array_sum($productAmtArray);
        $output .= $totalAmount;
        $output .= '</h2>';

        foreach ($productAmtArray as $productAmt => $totalAmt) {
            $output .= '<p class="aed-agent"  style="text-align: left;">AED' . $productAmt . ' - ' . $totalAmt . ' </p>';
        }

        $output .= '</div></div></div></div></div>';
    }

    /////////// Online Ticket ///////////////
    if ($roll_id == 1 or $roll_id == 4) {
        // Calculate totals for online tickets
        // $oticket = mysqli_query($con, "SELECT SUM(I.netTotal) AS totalAmount FROM `invoice` AS I JOIN ndticket ON ndticket.id = I.ticketId WHERE I.ticketId = ndticket.id AND I.agent_id='0'AND I.deletes ='0' AND DATE(I.createdon)");

        $oticket = mysqli_query($con, "SELECT SUM(I.grandtotal) AS totalAmount FROM `invoice` AS I JOIN ndticket ON ndticket.id = I.ticketId WHERE I.ticketId = ndticket.id AND I.agent_id='0'AND I.deletes ='0' AND DATE(I.createdon) = '$currentDate'");
        $row1 = mysqli_fetch_array($oticket);
        $totalAmount = $row1['totalAmount'];

        $productAmtArray = []; // This array will store product amount (AED value) and its total sum

        $sql = mysqli_query($con, "SELECT P.id, ROUND(P.rate) AS 'productAmt', IFNULL(ROUND(I.totalAmt), 0) AS 'totalAmt'
        FROM `product` AS P
        LEFT JOIN (
            SELECT I.product_id, SUM(I.grandtotal) AS 'totalAmt'
            FROM `invoice` AS I
            WHERE I.deletes = '0' AND I.agent_id='0'AND DATE(I.createdon) = '$currentDate' AND I.renewalStatus = 'new'
            GROUP BY I.product_id
        ) AS I ON P.id = I.product_id
        WHERE P.deletes = '0';");

        if (mysqli_num_rows($sql) > 0) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $productAmt = round($row['productAmt']);  // Extract AED value
                $totalAmt = $row['totalAmt'];

                // Check if productAmt already exists in the array, if so, add current totalAmt to it
                if (isset($productAmtArray[$productAmt])) {
                    $productAmtArray[$productAmt] += $totalAmt;
                } else {
                    $productAmtArray[$productAmt] = $totalAmt;
                }
            }
        }

        // Display Online Ticket section
        $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3" style="display: inline-block;"><div class="card overflow-hidden"><div class="card-body"><div class="d-flex"><div class="mt-2"><h6 class="">Online Ticket</h6><h2 class="mb-0 number-font" id="totalonline"  style="text-align: left;">AED ';
        $output .= array_sum($productAmtArray); // Display total amount for Online Ticket section
        $output .= '</h2>';

        foreach ($productAmtArray as $productAmt => $totalAmt) {
            $output .= '<p class="aed-agent"  style="text-align: left;">AED ' . $productAmt . ' - ' . $totalAmt . ' </p>';
        }

        $output .= '</div></div></div></div></div>';

        // Calculate totals for renewals
        $renewalAmtArray = []; // This array will store renewal amount (AED value) and its total sum

        $renewalSql = mysqli_query($con, "SELECT P.id, ROUND(P.rate) AS 'productAmt', IFNULL(ROUND(I.totalAmt), 0) AS 'totalAmt'
        FROM `product` AS P
        LEFT JOIN (
            SELECT I.product_id, SUM(I.grandtotal) AS 'totalAmt'
            FROM `invoice` AS I
            WHERE I.deletes = '0' AND DATE(I.createdon) = '$currentDate' AND I.renewalStatus = 'renewal'
            GROUP BY I.product_id
        ) AS I ON P.id = I.product_id
        WHERE P.deletes = '0';");

        if (mysqli_num_rows($renewalSql) > 0) {
            while ($renewalRow = mysqli_fetch_assoc($renewalSql)) {
                $productAmt = round($renewalRow['productAmt']);  // Extract AED value
                $totalAmt = $renewalRow['totalAmt'];

                // Check if productAmt already exists in the array, if so, add current totalAmt to it
                if (isset($renewalAmtArray[$productAmt])) {
                    $renewalAmtArray[$productAmt] += $totalAmt;
                } else {
                    $renewalAmtArray[$productAmt] = $totalAmt;
                }
            }
        }

        // Display Renewal section
        $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3" style="display: inline-block;"><div class="card overflow-hidden"><div class="card-body"><div class="d-flex"><div class="mt-2"><h6 class="">Renewals</h6><h2 class="mb-0 number-font" id="totalRenewal"  style="text-align: left;">AED ';
        $output .= array_sum($renewalAmtArray); // Display total amount for Renewal section
        $output .= '</h2>';

        foreach ($renewalAmtArray as $productAmt => $totalAmt) {
            $output .= '<p class="aed-agent"  style="text-align: left;">AED ' . $productAmt . ' - ' . $totalAmt . ' </p>';
        }

        $output .= '</div></div></div></div></div>';
    }





    ///// Agent Ticket /////
    // $oticket = mysqli_query($con, "SELECT SUM(I.netTotal) AS totalAmount FROM `invoice` AS I JOIN ndticket ON ndticket.id = I.ticketId WHERE I.agent_id != '0' AND I.ticketId = ndticket.id;");
    $oticket = mysqli_query($con, "SELECT SUM(I.grandtotal) AS totalAmount FROM `invoice` AS I JOIN ndticket ON ndticket.id = I.ticketId WHERE I.agent_id != '0' AND DATE(I.createdon) = CURDATE() AND I.renewalStatus = 'new' AND  I.ticketId = ndticket.id;");
    $row1 = mysqli_fetch_array($oticket);
    $totalAmount = $row1['totalAmount'];

    $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3"><div class="card overflow-hidden"><div class="card-body"><div class="d-flex"><div class="mt-2"><h6 class="">Agent Ticket</h6><h2 class="mb-0 number-font" id="totalonline"  style="text-align: left;">AED ' . intval($totalAmount) . '</h2>'; // This line displays total amount

    $sql = mysqli_query($con, "SELECT P.id, ROUND(P.rate) AS 'productAmt', IFNULL(ROUND(I.totalAmt), 0) AS 'totalAmt'
        FROM `product` AS P
        LEFT JOIN (
            SELECT I.product_id, SUM(I.grandtotal) AS 'totalAmt'
            FROM `invoice` AS I
            WHERE I.deletes = '0' AND I.agent_id !='0' AND DATE(I.createdon) = '$currentDate' AND I.renewalStatus = 'new'
            GROUP BY I.product_id
        ) AS I ON P.id = I.product_id
        WHERE P.deletes = '0';");

    if (mysqli_num_rows($sql) > 0) {
        $productAmtArray = []; // Initialize an array to store unique product amounts
        while ($row = mysqli_fetch_assoc($sql)) {
            $productAmt = intval($row['productAmt']);
            if (!isset($productAmtArray[$productAmt])) {
                $output .= '<p class="aed-agent"  style="text-align: left;">AED ' . $productAmt . ' - ' . $row['totalAmt'] . ' </p>';
                $productAmtArray[$productAmt] = true; // Mark product amount as displayed
                $total_Aed_Array['AED' . intval($row['productAmt'])] = ($total_Aed_Array['AED' . intval($row['productAmt'])] ?? 0) + $row['totalAmt'];
            }
        }
    }

    $output .= '</div></div></div></div></div>';
    
    ///// Wallte amount/////
    // $oticket = mysqli_query($con, "SELECT SUM(I.netTotal) AS totalAmount FROM `invoice` AS I JOIN ndticket ON ndticket.id = I.ticketId WHERE I.agent_id != '0' AND I.ticketId = ndticket.id;");
    $oticket = mysqli_query($con, "SELECT SUM(I.grandtotal) AS totalAmount FROM `invoice` AS I JOIN ndticket ON ndticket.id = I.ticketId WHERE I.agent_id = '0' AND DATE(I.createdon) = CURDATE() AND I.renewalStatus = 'new' AND I.paymentType = 'Wallet' AND I.ticketId = ndticket.id;");
    $row1 = mysqli_fetch_array($oticket);
    $totalAmount = $row1['totalAmount'];

    $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3"><div class="card overflow-hidden"><div class="card-body"><div class="d-flex"><div class="mt-2"><h6 class="">Wallet Ticket</h6><h2 class="mb-0 number-font" id="totalonline"  style="text-align: left;">AED ' . intval($totalAmount) . '</h2>'; // This line displays total amount

    $sql = mysqli_query($con, "SELECT P.id, ROUND(P.rate) AS 'productAmt', IFNULL(ROUND(I.totalAmt), 0) AS 'totalAmt'
        FROM `product` AS P
        LEFT JOIN (
            SELECT I.product_id, SUM(I.grandtotal) AS 'totalAmt'
            FROM `invoice` AS I
            WHERE I.deletes = '0' AND I.agent_id ='0' AND DATE(I.createdon) = '$currentDate' AND I.renewalStatus = 'new' AND I.paymentType = 'Wallet'
            GROUP BY I.product_id
        ) AS I ON P.id = I.product_id
        WHERE P.deletes = '0';");
        
       
       
    if (mysqli_num_rows($sql) > 0) {
        $productAmtArray = []; // Initialize an array to store unique product amounts
        while ($row = mysqli_fetch_assoc($sql)) {
            $productAmt = intval($row['productAmt']);
            if (!isset($productAmtArray[$productAmt])) {
                $output .= '<p class="aed-agent"  style="text-align: left;">AED ' . $productAmt . ' - ' . $row['totalAmt'] . ' </p>';
                $productAmtArray[$productAmt] = true; // Mark product amount as displayed
                $total_Aed_Array['AED' . intval($row['productAmt'])] = ($total_Aed_Array['AED' . intval($row['productAmt'])] ?? 0) + $row['totalAmt'];
            }
        }
    }

    $output .= '</div></div></div></div></div>';
    //////////////////The Pickup the Store ///////////////////
     $picuptostore = mysqli_query($con, "SELECT COUNT(id) as count,  IFNULL(SUM(shipamount), 0) as amount  FROM invoice WHERE DATE(createdon) = '$currentDate' AND deliveryType = 'deliveryToMe' AND delivery_status != ''AND renewalStatus = 'DELIVERYTOCUSTOMER';");
    
    $row1 = mysqli_fetch_array($picuptostore);
    $picup_count = $row1['count'];
    $picup_amount = $row1['amount'];
    $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-9"> <div class="row"> <div class="col-lg-6 col-md-6 col-sm-12 col-xl-4"><div class="card overflow-hidden"><div class="card-body"><div class="d-flex"><div class="mt-2"><h6 class="">Today Delivery Schedule</h6><h5 class="mb-0 number-font" id="totalonline">Count - ' . intval($picup_count) . '<br> AED&nbsp;&nbsp;&nbsp;&nbsp;- '.intval($picup_amount).' </h5>';
    $output .= '</div></div></div></div></div>';
    
    //////////////////The Pickup the Store ///////////////////
    $picuptostore = mysqli_query($con, "SELECT COUNT(id) as count, IFNULL(SUM(shipamount), 0) as amount FROM invoice WHERE DATE(createdon) = '$currentDate' AND deliveryType = 'pickUpToStore' AND delivery_status != '' AND renewalStatus = 'PICKUPTOSTORE';");
    // var_dump("SELECT COUNT(id) as count, SUM(shipamount) as amount FROM invoice WHERE DATE(createdon) = '$currentDate' AND deliveryType = 'pickUpToStore' AND delivery_status != '' AND renewalStatus = 'PICKUPTOSTORE';");die;
    $row1 = mysqli_fetch_array($picuptostore);
    $picup_count = $row1['count'];
    $picup_amount = $row1['amount'];
    $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-4"><div class="card overflow-hidden"><div class="card-body"><div class="d-flex"><div class="mt-2"><h6 class="">Today Pick UP Schedule</h6><h5 class="mb-0 number-font" id="totalonline">Count - ' . intval($picup_count) . '<br> AED&nbsp;&nbsp;&nbsp;&nbsp;- '.intval($picup_amount).' </h5>';
    $output .= '</div></div></div></div></div>';

    //////////////////Whatsapp message Count ///////////////////
    $whatsapp_send = mysqli_query($con, "SELECT COUNT(*) AS whatsapp_send
        FROM `smslog`
        WHERE (gateway = 'doubleTick' OR gateway = 'greenapi') AND DATE(datetime) = '$currentDate'");
    $row1 = mysqli_fetch_array($whatsapp_send);
    $whatsapp_count = $row1['whatsapp_send'];
    $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-4"><div class="card overflow-hidden"><div class="card-body"><div class="d-flex"><div class="mt-2"><h6 class="">Whatsapp message Count</h6><h2 class="mb-0 number-font" id="totalonline">' . intval($whatsapp_count) . '</h2>';
    $output .= '</div></div></div></div></div>';

    //////////////////SMS message Count ///////////////////
    $sms_send = mysqli_query($con, "SELECT COUNT(*) AS sms_send
        FROM `smslog`
        WHERE gateway = 'brandmaster' AND DATE(datetime) = '$currentDate'");
    $row1 = mysqli_fetch_array($sms_send);
    $sms_count = $row1['sms_send'];
    $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-4"><div class="card overflow-hidden"><div class="card-body"><div class="d-flex"><div class="mt-2"><h6 class="">SMS message Count</h6><h2 class="mb-0 number-font" id="totalonline">' . intval($sms_count) . '</h2>';
    $output .= '</div></div></div></div></div>';


    //////////////////Send Email  Count ///////////////////
    $email_send = mysqli_query($con, "SELECT COUNT(*) AS email_send
        FROM `emaillog`
        WHERE DATE(datetime) = '$currentDate';");
    $row1 = mysqli_fetch_array($email_send);
    $sms_count = $row1['email_send'];
    $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-4"><div class="card overflow-hidden"><div class="card-body"><div class="d-flex"><div class="mt-2"><h6 class="">Send Email Count</h6><h2 class="mb-0 number-font" id="totalonline">' . intval($sms_count) . '</h2>';
    $output .= '</div></div></div></div></div>';
    
     //////////////////WhatsApp Download  Count ///////////////////
    // $email_send = mysqli_query($con, "SELECT COUNT(*) as email_count FROM `smslog` WHERE token_response LIKE '%\"templateName\":\"nd_prize_details_v4\"%'");
    $email_send = mysqli_query($con, "SELECT COUNT(*) as email_count FROM `smslog` WHERE token_response LIKE '%\"templateName\":\"nd_prize_details_v4\"%' or token_response LIKE '%\"fileName\":\"National Draw - Details.pdf\"%'");
    $row1 = mysqli_fetch_array($email_send);
    $sms_count = $row1['email_count'];
    $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-4"><div class="card overflow-hidden"><div class="card-body"><div class="d-flex"><div class="mt-2"><h6 class="">ND Price Detail Download Count</h6><h2 class="mb-0 number-font" id="totalonline">' . intval($sms_count) . '</h2>';
    $output .= '</div></div></div></div></div>';
    
     
     ////////////////// Deleted Ticket Count ///////////////////
    // $oticket = mysqli_query($con, "SELECT COUNT(*) AS deletes_count FROM `ndticket` WHERE deletes = '1';");
    // $row1 = mysqli_fetch_array($oticket);
    // $totalAmount = $row1['deletes_count'];
    // $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-4"><div class="card overflow-hidden"><div class="card-body"><div class="d-flex"><div class="mt-2"><h6 class="">Deleted Account Request</h6><h2 class="mb-0 number-font" id="totalonline">' . intval($totalAmount) . '</h2>';
    // $output .= '</div></div></div></div></div></div>';
    
    



    // =======================================================







    $drawout = '';
    if ($_SESSION['memid'] != '') {
        $result['type'] = 1;
        $result['output'] = $drawout . $output;
    } else {
        $result['type'] = 0;
        $result['result'] = '<div class="alert alert-danger" role="alert">Could not get data!</div>';
    }



    echo json_encode($result);
} else if ($method == "hour_chart") {
    // var_dump('wlcome');die;
    $selectedDate = $_POST['selectedDate'];
    $domainName = $_POST['domainName'];

    $hours = [];
    $hourlySales = [];
    // $oticket = mysqli_query($con, "SELECT COALESCE(SUM(I.grandtotal), 0) AS totalAmount 
    //                             FROM `invoice` AS I 
    //                             JOIN ndticket ON ndticket.id = I.ticketId 
    //                             WHERE I.deletes = '0' 
    //                             AND DATE(I.createdon) = '$selectedDate'");

    // $row1 = mysqli_fetch_array($oticket);


    $totalAmount = 0;

    // var_dump($totalAmount);die;


    if ($domainName != '') {


        $query = "SELECT 
        hours.hour,
        COALESCE(SUM(i.grandtotal), 0) AS total_amount
        FROM (
        SELECT 0 AS hour UNION ALL
        SELECT 1 UNION ALL
        SELECT 2 UNION ALL
        SELECT 3 UNION ALL
        SELECT 4 UNION ALL
        SELECT 5 UNION ALL
        SELECT 6 UNION ALL
        SELECT 7 UNION ALL
        SELECT 8 UNION ALL
        SELECT 9 UNION ALL
        SELECT 10 UNION ALL
        SELECT 11 UNION ALL
        SELECT 12 UNION ALL
        SELECT 13 UNION ALL
        SELECT 14 UNION ALL
        SELECT 15 UNION ALL
        SELECT 16 UNION ALL
        SELECT 17 UNION ALL
        SELECT 18 UNION ALL
        SELECT 19 UNION ALL
        SELECT 20 UNION ALL
        SELECT 21 UNION ALL
        SELECT 22 UNION ALL
        SELECT 23
        ) AS hours
        LEFT JOIN (SELECT HOUR(g.createdon) AS hour, SUM(g.grandtotal) AS grandtotal FROM (SELECT p.id, p.transaction_id, p.createdon, p.grandtotal FROM `payment_history_log` as pl 
        LEFT JOIN payment_history as p ON pl.payment_history_id = p.id where  pl.`nenc_response` LIKE '%$domainName%' AND DATE(p.createdon) = '$selectedDate' AND p.gateway = 'ccavenue' AND p.paymentStatus IN ('CAPTURED', 'Success', 'Shipped'))  as g GROUP BY HOUR(g.createdon)
        ) AS i ON hours.hour = i.hour
        GROUP BY hours.hour;";
    } else {

        $query = "SELECT 
                hours.hour,
                COALESCE(SUM(i.grandtotal), 0) AS total_amount
              FROM (
                SELECT 0 AS hour UNION ALL
                SELECT 1 UNION ALL
                SELECT 2 UNION ALL
                SELECT 3 UNION ALL
                SELECT 4 UNION ALL
                SELECT 5 UNION ALL
                SELECT 6 UNION ALL
                SELECT 7 UNION ALL
                SELECT 8 UNION ALL
                SELECT 9 UNION ALL
                SELECT 10 UNION ALL
                SELECT 11 UNION ALL
                SELECT 12 UNION ALL
                SELECT 13 UNION ALL
                SELECT 14 UNION ALL
                SELECT 15 UNION ALL
                SELECT 16 UNION ALL
                SELECT 17 UNION ALL
                SELECT 18 UNION ALL
                SELECT 19 UNION ALL
                SELECT 20 UNION ALL
                SELECT 21 UNION ALL
                SELECT 22 UNION ALL
                SELECT 23
              ) AS hours
              LEFT JOIN (
                SELECT HOUR(createdon) AS hour, SUM(grandtotal) AS grandtotal
                FROM invoice
                WHERE DATE(createdon) = '$selectedDate'
                GROUP BY HOUR(createdon)
              ) AS i ON hours.hour = i.hour
              GROUP BY hours.hour;";
    }

    // var_dump($query);
    // die;


    $result = mysqli_query($con, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $hours[] = $row['hour'];
        $hourlySales[] = $row['total_amount'];
        $totalAmount += floatval($row['total_amount']);
    }

    $response = [
        'hours' => $hours,
        'hourlySales' => $hourlySales,
        'totalAED' => $totalAmount
    ];

    // Return the data in JSON format
    // var_dump($response);die;
    echo json_encode($response);
}
else if ($method === 'sign_up_chart') {
    $results = [];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    if (!isset($startDate) || $startDate == '' || $startDate == null || !isset($endDate) || $endDate == '' || $endDate == null) {
        $result['type'] = 0;
        $result['result'] = 'The Date has been missing!';
        goto resutGJHIP;
    }
    $utm_campaign = $_POST['utm_campaign'];
    $utm_campaign_source = $_POST['utm_campaign_source'];
    $result['type'] = 1;
    $totalCount = 0;
    $dates = [];
    $signups = [];
    $logins = [];
    $loginsOTP = [];
    $startTimestamp = strtotime($startDate);
    $endTimestamp = strtotime($endDate);
    for ($currentTimestamp = $startTimestamp; $currentTimestamp <= $endTimestamp; $currentTimestamp += 86400) { // 86400 seconds in a day
        $date = date('Y-m-d', $currentTimestamp);
        $dates[] = $date;
        $signups[$date] = 0;
        $logins[$date] = 0;
        $loginsOTP[$date] = 0;
    }
    $loginWithPassword = "WITH RECURSIVE date_range AS (
    SELECT DATE('$startDate') AS date
    UNION ALL
    SELECT DATE_ADD(date, INTERVAL 1 DAY)
    FROM date_range
    WHERE date < DATE('$endDate')
)
SELECT 
    dr.date,
    COALESCE(COUNT(DISTINCT sub.userid), 0) AS count
FROM 
    date_range dr
LEFT JOIN (
    SELECT 
        userid,
        COUNT(*) AS count,
        DATE(createdon) AS date
    FROM 
        login_logs
    WHERE 
        method = 'loginWithPassword' 
        AND createdon BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'
" . ($utm_campaign != '' && isset($utm_campaign) ? " AND utm_campaign =  '$utm_campaign' " : '') . "
" . ($utm_campaign_source != '' && isset($utm_campaign_source) ? " AND utm_source =  '$utm_campaign_source' " : '') . "
    GROUP BY 
        userid, DATE(createdon)
) AS sub ON dr.date = sub.date
GROUP BY 
    dr.date
ORDER BY 
    dr.date;";
    // var_dump( $loginWithPassword);die;
    $resultRun = mysqli_query($con, $loginWithPassword);
    while ($row = mysqli_fetch_assoc($resultRun)) {
        $date = $row['date'];
        $logins[$date] = $row['count'];
        $totalCount += (int) $row['count'];
    }
    $loginQueryOtp = "WITH RECURSIVE date_range AS (
        SELECT DATE('$startDate') AS date
        UNION ALL
        SELECT DATE_ADD(date, INTERVAL 1 DAY)
        FROM date_range
        WHERE date < DATE('$endDate')
    )
    SELECT 
        dr.date,
        COALESCE(COUNT(DISTINCT sub.userid), 0) AS count
    FROM 
        date_range dr
    LEFT JOIN (
        SELECT 
            userid,
            COUNT(*) AS count,
            DATE(createdon) AS date
        FROM 
            login_logs
        WHERE 
            method = 'loginOTPverify' 
            AND createdon BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'
            " . ($utm_campaign != '' && isset($utm_campaign) ? " AND utm_campaign =  '$utm_campaign' " : '') . "
" . ($utm_campaign_source != '' && isset($utm_campaign_source) ? " AND utm_source =  '$utm_campaign_source' " : '') . "
        GROUP BY 
            userid, DATE(createdon)
    ) AS sub ON dr.date = sub.date
    GROUP BY 
        dr.date
    ORDER BY 
        dr.date;";
    $loginResultOTP = mysqli_query($con, $loginQueryOtp);
    while ($row = mysqli_fetch_assoc($loginResultOTP)) {
        $date = $row['date'];
        $loginsOTP[$date] = $row['count'];
        $totalCount += (int) $row['count'];
    }
    $signupQuery = "WITH RECURSIVE date_range AS (
        SELECT DATE('$startDate') AS date
        UNION ALL
        SELECT DATE_ADD(date, INTERVAL 1 DAY)
        FROM date_range
        WHERE date < DATE('$endDate')
    )
    SELECT 
        dr.date,
        COALESCE(COUNT(DISTINCT sub.userid), 0) AS count
    FROM 
        date_range dr
    LEFT JOIN (
        SELECT 
            userid,
            COUNT(*) AS count,
            DATE(createdon) AS date
        FROM 
            login_logs
        WHERE 
            method = 'userRegister' 
            AND createdon BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'
            " . ($utm_campaign != '' && isset($utm_campaign) ? " AND utm_campaign =  '$utm_campaign' " : '') . "
" . ($utm_campaign_source != '' && isset($utm_campaign_source) ? " AND utm_source =  '$utm_campaign_source' " : '') . "
        GROUP BY 
            userid, DATE(createdon)
    ) AS sub ON dr.date = sub.date
    GROUP BY 
        dr.date
    ORDER BY 
        dr.date;";
    $signupResult = mysqli_query($con, $signupQuery);
    while ($row = mysqli_fetch_assoc($signupResult)) {
        $date = $row['date'];
        $signups[$date] = $row['count'];
        $totalCount += (int) $row['count'];
    }
    
    $leads = [];
    foreach ($dates as $date) {
        $leads[$date] = 0;
    }
    
    $leadQuery = "WITH RECURSIVE date_range AS (
        SELECT DATE('$startDate') AS date
        UNION ALL
        SELECT DATE_ADD(date, INTERVAL 1 DAY)
        FROM date_range
        WHERE date < DATE('$endDate')
    )
    SELECT 
        dr.date,
        COALESCE(COUNT(sub.id), 0) AS count
    FROM 
        date_range dr
    LEFT JOIN (
        SELECT 
            id,
            DATE(created_at) AS date
        FROM 
            goride_ad_leads
        WHERE 
            created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'
    ) AS sub ON dr.date = sub.date
    GROUP BY 
        dr.date
    ORDER BY 
        dr.date;";
    
    $leadResult = mysqli_query($con, $leadQuery);
    while ($row = mysqli_fetch_assoc($leadResult)) {
        $date = $row['date'];
        $leads[$date] = $row['count'];
        $totalCount += (int) $row['count'];
    }
    
    $result['leads'] = $leads;
    $result['dates'] = $dates;
    $result['signups'] = $signups;
    $result['logins'] = $logins;
    $result['loginsOTP'] = $loginsOTP;
    $result['total'] = $totalCount;
    resutGJHIP:
    echo json_encode($result);
}

// else if ($method === 'verifyReport_List') {
    
//     $results = [];
//     $startDate = $_POST['startDate'];
//     $endDate = $_POST['endDate'];
//     if (!isset($startDate) || $startDate == '' || $startDate == null || !isset($endDate) || $endDate == '' || $endDate == null) {
//         $result['type'] = 0;
//         $result['result'] = 'The Date has been missing!';
//         goto resutGJHIP;
//     }
    
    
//     $whereClause = [];
//     $whereClause[] = "cu.deletes = '0'";
//     $whereClause[] = "kd.deletes = '0'";
//     $whereClause[] = "DATE(kd.updated_at) BETWEEN '$startDate' AND '$endDate'";
    
//     $filter = implode(' AND ', $whereClause);
    
//     $query = "
//         SELECT 
//             DATE(kd.updated_at) AS report_date,
    
//             SUM(
//                 CASE 
//                     WHEN cu.doc_verify = '1'
//                          AND (kd.updated_by IS NULL AND kd.reject_reason IS NULL)
//                     THEN 1 ELSE 0 
//                 END
//             ) AS kyc_completed,
    
//             SUM(
//                 CASE 
//                     WHEN 
//                         (JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.admin_verify')) = 'true'
//                          AND JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.vehicle_review_message')) LIKE '%Vehicle details verified%')
//                     THEN 1 ELSE 0
//                 END
//             ) AS vehicle_completed,
    
//             SUM(
//                 CASE 
//                     WHEN (
//                         (cu.doc_verify = '1' AND cu.vehicle_verify = '2' AND kd.type = 'Driver'
//                          AND JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.admin_verify')) = 'true'
//                          AND JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.vehicle_review_message')) LIKE '%Vehicle details verified%')
//                         OR
//                         (cu.doc_verify = '1' AND kd.o_proof_status = 'approved' AND kd.type = 'Owner')
//                     )
//                     THEN 1 ELSE 0
//                 END
//             ) AS both_completed
    
//         FROM kyc_details kd
//         JOIN user_register cu ON kd.user_id = cu.id
//         WHERE $filter
//         GROUP BY DATE(kd.updated_at)
//         ORDER BY DATE(kd.updated_at)
//     ";
    
//     $result = mysqli_query($con, $query);

//     if (!$result) {
//         die("Query Error: " . mysqli_error($con));
//     }
    
    
//     $dates = [];
//     $kyc = [];
//     $vehicle = [];
//     $both = [];
    
//     while ($row = mysqli_fetch_assoc($result)) {
//         $dates[] = $row['report_date'];
//         $kyc[] = (int)$row['kyc_completed'];
//         $vehicle[] = (int)$row['vehicle_completed'];
//         $both[] = (int)$row['both_completed'];
//     }
    
//     mysqli_free_result($result);
//     mysqli_close($con);
    
//     $result = [
//         'type' => 1,
//         'dates' => $dates,
//         'kyc' => $kyc,       // KYC
//         'vehicle' => $vehicle,  // Vehicle
//         'both' => $both,     // Both
//     ];
    
//     var_dump($result);die;
//     echo json_encode($result);
// }

else if ($method === 'vehicleReport_List') {

    $startDate = $_POST['startDate'] ?? null;
    $endDate   = $_POST['endDate'] ?? null;

    if (empty($startDate) || empty($endDate)) {
        echo json_encode([
            'type' => 0,
            'result' => 'The Date has been missing!'
        ]);
        exit;
    }

    $whereClause = [];
    $whereClause[] = "cu.deletes = '0'";
    $whereClause[] = "kd.deletes = '0'";
    $whereClause[] = "DATE(kd.updated_at) BETWEEN '$startDate' AND '$endDate'";

    $filter = implode(' AND ', $whereClause);

    $query = "
        SELECT 
            report_date,
            SUM(vehicle_reject) AS vehicle_reject,
            SUM(vehicle_created) AS vehicle_created,
            SUM(vehicle_completed) AS vehicle_completed
        FROM (
         
            SELECT 
                DATE(ocr.created_at) AS report_date,
                COUNT(*) AS vehicle_created,
                0 AS vehicle_reject,
                0 AS vehicle_completed
            FROM ocr_request ocr
            JOIN user_register cu ON ocr.user_id = cu.id
            WHERE cu.deletes = '0'
              AND ocr.doc_type = 'RC'
              AND ocr.status = 'ACTIVE'
              AND cu.vehicle_details IS NOT NULL
              AND DATE(ocr.created_at) BETWEEN '$startDate' AND '$endDate'
            GROUP BY DATE(ocr.created_at)

            UNION ALL

            SELECT 
                DATE(cu.v_updated_at) AS report_date,
                0 AS vehicle_created,
                0 AS vehicle_reject,
                COUNT(*) AS vehicle_completed
            FROM user_register cu
            WHERE cu.deletes = '0'
              AND cu.v_updated_at IS NOT NULL
              AND JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.admin_verify')) = 'true'
              AND JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.vehicle_review_message')) LIKE '%Vehicle details verified%'
              AND DATE(cu.v_updated_at) BETWEEN '$startDate' AND '$endDate'
            GROUP BY DATE(cu.v_updated_at)
            
            UNION ALL

            SELECT 
                DATE(cu.v_updated_at) AS report_date,
                0 AS vehicle_created,
                COUNT(*) AS vehicle_reject,
                0 AS vehicle_completed
            FROM user_register cu
            WHERE cu.deletes = '0'
              AND cu.v_updated_at IS NOT NULL
              AND JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.admin_verify')) = 'false'
              AND JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.vehicle_review_message')) NOT LIKE '%Vehicle details verified%'
              AND JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.vehicle_review_message')) NOT LIKE '%wait for admin approval%'
              AND DATE(cu.v_updated_at) BETWEEN '$startDate' AND '$endDate'
            GROUP BY DATE(cu.v_updated_at)

            
        ) AS combined
        GROUP BY report_date
        ORDER BY report_date;
    ";


    $result = mysqli_query($con, $query);

    if (!$result) {
        echo json_encode([
            'type' => 0,
            'result' => 'Query Error: ' . mysqli_error($con)
        ]);
        exit;
    }

    $dates = [];
    $kyc_created = [];
    $vehicle_reject = [];
    $vehicle_created = [];
    $vehicle_completed = [];
    $both = [];
    $tot = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $dates[]   = $row['report_date'];
        // $kyc_created[]     = (int)$row['kyc_created'];
        $vehicle_reject[]     = (int)$row['vehicle_reject'];
        $vehicle_created[] = (int)$row['vehicle_created'];
        $vehicle_completed[] = (int)$row['vehicle_completed'];
        // $both[]    = (int)$row['kyc_completed'] + (int)$row['vehicle_completed'];
        $tot = '<strong>' . $row['report_date'].': </strong>' . (int)$row['vehicle_created'];
    }

    mysqli_free_result($result);
    mysqli_close($con);

    echo json_encode([
        'type' => 1,
        'dates' => $dates,
        'vehicle_reject' => $vehicle_reject,
        'vehicle_created' => $vehicle_created,
        'vehicle_completed' => $vehicle_completed,
        // 'both' => $both,
        'total' => $tot
    ], JSON_NUMERIC_CHECK);
    exit;
}

else if ($method === 'verifyReport_List') {

    $startDate = $_POST['startDate'] ?? null;
    $endDate   = $_POST['endDate'] ?? null;

    if (empty($startDate) || empty($endDate)) {
        echo json_encode([
            'type' => 0,
            'result' => 'The Date has been missing!'
        ]);
        exit;
    }

    $whereClause = [];
    $whereClause[] = "cu.deletes = '0'";
    $whereClause[] = "kd.deletes = '0'";
    $whereClause[] = "DATE(kd.updated_at) BETWEEN '$startDate' AND '$endDate'";

    $filter = implode(' AND ', $whereClause);

    $query = "
        SELECT 
            report_date,
            SUM(kyc_created) AS kyc_created,
            SUM(kyc_completed) AS kyc_completed,
            SUM(kyc_reject) AS kyc_reject
        FROM (
         
            SELECT 
                DATE(kd.update_date) AS report_date,
                0 AS kyc_created,
                COUNT(*) AS kyc_completed,
                0 AS kyc_reject
                
            FROM kyc_details kd
            JOIN user_register cu ON kd.user_id = cu.id
            WHERE cu.deletes = '0'
              AND kd.deletes = 0
              AND cu.doc_verify = '1' AND kd.o_status = 3
              AND DATE(kd.update_date) BETWEEN '$startDate' AND '$endDate'
            GROUP BY DATE(kd.update_date)
            
            UNION ALL
            
            SELECT 
                DATE(kd.created_at) AS report_date,
                COUNT(*) AS kyc_created,
                0 AS kyc_completed,
                0 AS kyc_reject
            FROM kyc_details kd
            JOIN user_register cu ON kd.user_id = cu.id
            WHERE cu.deletes = '0' 
              AND kd.deletes = 0
              AND DATE(kd.created_at) BETWEEN '$startDate' AND '$endDate'
            GROUP BY DATE(kd.created_at)
            
            UNION ALL
            
            SELECT 
                DATE(kd.update_date) AS report_date,
                0 AS kyc_created,
                0 AS kyc_completed,
                COUNT(*) AS kyc_reject
                
            FROM kyc_details kd
            JOIN user_register cu ON kd.user_id = cu.id
            WHERE cu.deletes = '0' 
              AND kd.deletes = 0
              AND kd.reject_reason IS NOT NULL
              AND kd.updated_by IS NOT NULL
              AND cu.doc_verify = '0'
              AND DATE(kd.update_date) BETWEEN '$startDate' AND '$endDate'
            GROUP BY DATE(kd.update_date)
            
        ) AS combined
        GROUP BY report_date
        ORDER BY report_date;
    ";


    $result = mysqli_query($con, $query);

    if (!$result) {
        echo json_encode([
            'type' => 0,
            'result' => 'Query Error: ' . mysqli_error($con)
        ]);
        exit;
    }

    $dates = [];
    $kyc_created = [];
    $kyc_completed = [];
    $kyc_reject = [];
    // $vehicle_completed = [];
    $both = [];
    $tot = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $dates[]   = $row['report_date'];
        $kyc_created[]     = (int)$row['kyc_created'];
        $kyc_completed[]     = (int)$row['kyc_completed'];
        $kyc_reject[] = (int)$row['kyc_reject'];
        // $vehicle_completed[] = (int)$row['vehicle_completed'];
        // $both[]    = (int)$row['kyc_completed'] + (int)$row['vehicle_completed'];
        $tot = '<strong>' . $row['report_date'].': </strong>' . (int)$row['kyc_created'];
    }

    mysqli_free_result($result);
    mysqli_close($con);

    echo json_encode([
        'type' => 1,
        'dates' => $dates,
        'kyc_created' => $kyc_created,
        'kyc_completed' => $kyc_completed,
        'kyc_reject' => $kyc_reject,
        // 'vehicle_completed' => $vehicle_completed,
        // 'both' => $both,
        'total' => $tot,
    ], JSON_NUMERIC_CHECK);
    exit;
}

// else if ($method === 'vehicleReport_List') {

//     $startDate = $_POST['startDate'] ?? null;
//     $endDate   = $_POST['endDate'] ?? null;

//     if (empty($startDate) || empty($endDate)) {
//         echo json_encode([
//             'type' => 0,
//             'result' => 'The Date has been missing!'
//         ]);
//         exit;
//     }

//     $whereClause = [];
//     $whereClause[] = "cu.deletes = '0'";
//     $whereClause[] = "kd.deletes = '0'";
//     $whereClause[] = "DATE(kd.updated_at) BETWEEN '$startDate' AND '$endDate'";

//     $filter = implode(' AND ', $whereClause);

//     $query = "
//         SELECT 
//             report_date,
//             SUM(kyc_created) AS kyc_created,
//             SUM(kyc_completed) AS kyc_completed,
//             SUM(kyc_reject) AS kyc_reject
//         FROM (
         
//             SELECT 
//                 DATE(kd.update_date) AS report_date,
//                 0 AS kyc_created,
//                 COUNT(*) AS kyc_completed,
//                 0 AS kyc_reject
                
//             FROM kyc_details kd
//             JOIN user_register cu ON kd.user_id = cu.id
//             WHERE cu.deletes = '0' 
//               AND kd.deletes = 0
//               AND cu.doc_verify = '1'
//               AND DATE(kd.update_date) BETWEEN '$startDate' AND '$endDate'
//             GROUP BY DATE(kd.update_date)
            
//             UNION ALL
            
//             SELECT 
//                 DATE(kd.created_at) AS report_date,
//                 COUNT(*) AS kyc_created,
//                 0 AS kyc_completed,
//                 0 AS kyc_reject
//             FROM kyc_details kd
//             JOIN user_register cu ON kd.user_id = cu.id
//             WHERE cu.deletes = '0' 
//               AND kd.deletes = 0
//               AND DATE(kd.created_at) BETWEEN '$startDate' AND '$endDate'
//             GROUP BY DATE(kd.created_at)
            
//             UNION ALL
            
//             SELECT 
//                 DATE(kd.update_date) AS report_date,
//                 0 AS kyc_created,
//                 0 AS kyc_completed,
//                 COUNT(*) AS kyc_reject
                
//             FROM kyc_details kd
//             JOIN user_register cu ON kd.user_id = cu.id
//             WHERE cu.deletes = '0' 
//               AND kd.deletes = 0
//               AND kd.reject_reason IS NOT NULL
//               AND kd.updated_by IS NOT NULL
//               AND cu.doc_verify = '0'
//               AND DATE(kd.update_date) BETWEEN '$startDate' AND '$endDate'
//             GROUP BY DATE(kd.update_date)
            
//         ) AS combined
//         GROUP BY report_date
//         ORDER BY report_date;
//     ";


//     $result = mysqli_query($con, $query);

//     if (!$result) {
//         echo json_encode([
//             'type' => 0,
//             'result' => 'Query Error: ' . mysqli_error($con)
//         ]);
//         exit;
//     }

//     $dates = [];
//     $kyc_created = [];
//     $kyc_completed = [];
//     $kyc_reject = [];
//     // $vehicle_completed = [];
//     $both = [];
//     $tot = 0;
//     while ($row = mysqli_fetch_assoc($result)) {
//         $dates[]   = $row['report_date'];
//         $kyc_created[]     = (int)$row['kyc_created'];
//         $kyc_completed[]     = (int)$row['kyc_completed'];
//         $kyc_reject[] = (int)$row['kyc_reject'];
//         // $vehicle_completed[] = (int)$row['vehicle_completed'];
//         // $both[]    = (int)$row['kyc_completed'] + (int)$row['vehicle_completed'];
//         $tot = '<strong>' . $row['report_date'].': </strong>' . (int)$row['kyc_created'];
//     }

//     mysqli_free_result($result);
//     mysqli_close($con);

//     echo json_encode([
//         'type' => 1,
//         'dates' => $dates,
//         'kyc_created' => $kyc_created,
//         'kyc_completed' => $kyc_completed,
//         'kyc_reject' => $kyc_reject,
//         // 'vehicle_completed' => $vehicle_completed,
//         // 'both' => $both,
//         'total' => $tot,
//     ], JSON_NUMERIC_CHECK);
//     exit;
// }

else if ($method === 'driverReport_List') {

    $startDate = $_POST['startDate'] ?? null;
    $endDate   = $_POST['endDate'] ?? null;

    if (empty($startDate) || empty($endDate)) {
        echo json_encode([
            'type' => 0,
            'result' => 'The Date has been missing!'
        ]);
        exit;
    }

    $whereClause = [];
    $whereClause[] = "kd.deletes = '0'";
    $whereClause[] = "cu.deletes = '0' AND cu.status = '0' AND cu.roll_id = '0'";
    $whereClause[] = "DATE(kd.created_at) BETWEEN '$startDate' AND '$endDate'";
    
    $filter = implode(' AND ', $whereClause);
    
    $query = "
        SELECT 
            DATE(kd.created_at) AS report_date,
            SUM(CASE WHEN kd.type = 'Driver' THEN 1 ELSE 0 END) AS driver,
            SUM(CASE WHEN kd.type = 'Owner' THEN 1 ELSE 0 END) AS owner
        FROM kyc_details kd
         JOIN user_register cu ON kd.user_id = cu.id
        WHERE $filter
        GROUP BY DATE(kd.created_at)
        ORDER BY DATE(kd.created_at);
    ";



    $result = mysqli_query($con, $query);

    if (!$result) {
        echo json_encode([
            'type' => 0,
            'result' => 'Query Error: ' . mysqli_error($con)
        ]);
        exit;
    }

    $dates = [];
    $owner = [];
    $driver = [];
    $both = [];
    $txt = '';
    $d_c = 0;
    $o_c = 0;
    // $tot = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $dates[]   = $row['report_date'];
        $owner[]     = (int)$row['owner'];
        $driver[]     = (int)$row['driver'];
        $both[]    = (int)$row['owner'] + (int)$row['driver'];
        $d_c += (int)$row['driver'];
        $o_c += (int)$row['owner'];
    }

    mysqli_free_result($result);
    mysqli_close($con);

    echo json_encode([
        'type' => 1,
        'dates' => $dates,
        'driver' => $driver,
        'owner' => $owner,
        'both' => $both,
        'd_c' => $d_c,
        'o_c' => $o_c
    ], JSON_NUMERIC_CHECK);
    exit;
}

else if ($method === 'jobsReport_List') {

    $startDate = $_POST['startDate'] ?? null;
    $endDate   = $_POST['endDate'] ?? null;

    if (empty($startDate) || empty($endDate)) {
        echo json_encode([
            'type' => 0,
            'result' => 'The Date has been missing!'
        ]);
        exit;
    }

    $whereClause = [];
    $whereClause[] = "op.deletes = '0'";
    // $whereClause[] = "cu.deletes = '0' AND cu.status = '0' AND cu.roll_id = '0'";
    $whereClause[] = "DATE(op.created_at) BETWEEN '$startDate' AND '$endDate'";
    
    $filter = implode(' AND ', $whereClause);
    
    $query = "
        SELECT 
            DATE(op.created_at) AS report_date,
            COUNT(*) AS jobs
        FROM open_jobs op
        WHERE $filter
        GROUP BY DATE(op.created_at)
        ORDER BY DATE(op.created_at);
    ";



    $result = mysqli_query($con, $query);

    if (!$result) {
        echo json_encode([
            'type' => 0,
            'result' => 'Query Error: ' . mysqli_error($con)
        ]);
        exit;
    }

    $dates = [];
    $jobs = [];
    $tot = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $dates[]   = $row['report_date'];
        $jobs[]     = (int)$row['jobs'];
        $tot   += (int)$row['jobs'];
        // $d_c += (int)$row['driver'];
        // $o_c += (int)$row['owner'];
    }

    mysqli_free_result($result);
    mysqli_close($con);

    echo json_encode([
        'type' => 1,
        'dates' => $dates,
        'jobs' => $jobs,
        'tot' => $tot
    ], JSON_NUMERIC_CHECK);
    exit;
}

else if ($method === 'user_pending_counts') {

    $s_query = "
        (
            SELECT 
                'kyc_count' AS type,
                COUNT(*) AS total
            FROM kyc_details kd
            JOIN user_register cu ON cu.id = kd.user_id
            WHERE cu.doc_verify = '0'
              AND kd.o_status = '3'
              AND cu.deletes = '0'
              AND kd.deletes = '0'
        )
        

        UNION ALL
        
        (
            SELECT 
                'vehicle_count' AS type,
                COUNT(*) AS total
            FROM kyc_details kd
            JOIN user_register cu ON cu.id = kd.user_id
            WHERE cu.vehicle_verify NOT IN ('0', '2')
              AND JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.vehicle_review_message')) = 'wait for admin approval'
              AND cu.deletes = '0'
              AND kd.deletes = '0'
        )
    
        UNION ALL
    
        (
            SELECT 
                'expiry_count' AS type,
                COUNT(*) AS total
            FROM open_jobs oj
            WHERE oj.job_status IN ('created','bidding')
              AND oj.pickup_date < NOW()
              AND oj.deletes = '0'
        )
        
    
        UNION ALL
    
        (
            SELECT 
                'cancelled_count' AS type,
                COUNT(*) AS total
            FROM open_jobs oj
            WHERE oj.job_status = 'cancelled'
              AND oj.deletes = '0'
        )
        
        UNION ALL
    
        (
            SELECT 
                'n_complete_count' AS type,
                COUNT(*) AS total
            FROM open_jobs oj
            WHERE oj.job_status IN ('created','bidding')
              AND oj.pickup_date > NOW()
              AND oj.deletes = '0'
        )
        
        UNION ALL

        (
            SELECT 
                'kyc_n_count' AS type,
                COUNT(*) AS total
            FROM kyc_details kd
            JOIN user_register cu ON cu.id = kd.user_id
            WHERE cu.doc_verify = '0'
              AND kd.o_status < '3'
              AND cu.deletes = '0'
              AND kd.deletes = '0'
        )

           
    ";
   
//   UNION ALL

//         (
//             SELECT 
//                 'kyc_n_count' AS type,
//                 COUNT(*) AS total
//             FROM  user_register cu
//             LEFT JOIN kyc_details kd ON cu.id = kd.user_id
//             WHERE (cu.doc_verify = '0' AND cu.deletes = '0') AND ((kd.o_status < '3' AND kd.deletes = '0') OR (kd.id IS NULL) )
//         )


    $result_data = mysqli_query($con, $s_query);

    $result = ["type" => "0"];
    $data = [];

    if ($result_data && mysqli_num_rows($result_data) > 0) {

        while ($row = mysqli_fetch_assoc($result_data)) {
            $data[] = $row;
        }

        $result["type"] = "1";
        $result["result"] = $data;

    } else {
        $result["result"] = [];
    }

    echo json_encode($result);
}


function identify($apikey, $idUrl)
{
    $idHead = array("Authorization: Basic " . $apikey, "Content-Type: application/vnd.ni-identity.v1+json");
    $idPost = "";
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
