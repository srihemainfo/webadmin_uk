
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
} 
else if ($method == 'customer_bookings_chart') {
    $startDate = isset($_POST['startDate']) ? trim(BlockSQLInjectionforagent($_POST['startDate'])) : '';
    $endDate = isset($_POST['endDate']) ? trim(BlockSQLInjectionforagent($_POST['endDate'])) : '';
    $start = date('Y-m-d 00:00:00', strtotime($startDate));
    $end = date('Y-m-d 23:59:59', strtotime($endDate));
    $currentDateTime = date('Y-m-d H:i:s');
    // Query filters out GRP- jobs and strictly checks pickup_date
    $sql = "SELECT DATE(pickup_date) as p_date, job_status, pickup_date 
            FROM cus_job_temp 
            WHERE deletes = '0' 
            AND job_no NOT LIKE 'GRP-%' 
            AND pickup_date BETWEEN '$start' AND '$end'";
    $res = mysqli_query($con, $sql);
    // Prepare zero-filled dates to prevent missing bars on the graph
    $dates = [];
    $period = new DatePeriod(new DateTime($start), new DateInterval('P1D'), new DateTime(date('Y-m-d', strtotime($end . ' +1 day'))));
    foreach ($period as $dt) {
        $dates[$dt->format("Y-m-d")] = [
            'unassigned' => 0,
            'assigned' => 0,
            'completed' => 0,
            'cancelled' => 0,
            'expired' => 0
        ];
    }
    if ($res && mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $d = $row['p_date'];
            if (!isset($dates[$d])) continue;
            $status = strtolower($row['job_status']);
            $isExpired = (strtotime($row['pickup_date']) < strtotime($currentDateTime));
            if ($status === 'cancelled') {
                $dates[$d]['cancelled']++;
            } else if ($status === 'completed') {
                $dates[$d]['completed']++;
            } else if ($status === 'accept' || $status === 'accepted') {
                // If it's accepted but the pickup time has passed, treat it as completed based on system logic
                if ($isExpired) {
                    $dates[$d]['completed']++;
                } else {
                    $dates[$d]['assigned']++;
                }
            } else {
                // If not accepted, completed, or cancelled, it is either unassigned or expired
                if ($isExpired) {
                    $dates[$d]['expired']++;
                } else {
                    $dates[$d]['unassigned']++;
                }
            }
        }
    }
    echo json_encode([
        'type' => 1,
        'dates' => array_keys($dates),
        'unassigned' => array_column($dates, 'unassigned'),
        'assigned' => array_column($dates, 'assigned'),
        'completed' => array_column($dates, 'completed'),
        'cancelled' => array_column($dates, 'cancelled'),
        'expired' => array_column($dates, 'expired')
    ]);
    exit;
}
// elseif ($method == "pending_payment_dashboard_counts") {
    
//     // Using the exact Cron Recovery LIFO concept to find total platform debt
//     $query = "SELECT 
//                 w.id AS ID, 
//                 w.userid,
//                 w.opening_balance, 
//                 w.total AS deducted_amount, 
//                 w.closeing_balance, 
//                 (SELECT closeing_balance FROM walletBalance_history 
//                  WHERE userid = w.userid 
//                  ORDER BY id DESC LIMIT 1) AS live_balance
//               FROM walletBalance_history w 
//               WHERE w.ip = 'CRON' AND w.transaction_type = 'DEBIT' AND w.closeing_balance < 0
//               ORDER BY w.id DESC";

//     $result = mysqli_query($con, $query);
    
//     $total_pending_amount = 0;
//     $total_pending_jobs = 0;
//     $user_unpaid_debt = array();

//     if ($result) {
//         while ($row = mysqli_fetch_assoc($result)) {
//             $uid = $row['userid'];
//             $live_bal = (float)$row['live_balance'];
            
//             // Initialize the user's total live debt
//             if (!isset($user_unpaid_debt[$uid])) {
//                 $user_unpaid_debt[$uid] = ($live_bal < 0) ? abs($live_bal) : 0;
//             }

//             $opening = (float)$row['opening_balance'];
//             $deducted = (float)$row['deducted_amount'];
//             $closing = (float)$row['closeing_balance'];

//             // Calculate EXACT debt generated by this job
//             $actual_debt = ($opening > 0) ? abs($closing) : $deducted;

//             // Allocate Live Debt
//             if ($user_unpaid_debt[$uid] >= $actual_debt) {
//                 $to_pay = $actual_debt;
//                 $user_unpaid_debt[$uid] -= $actual_debt; 
//             } elseif ($user_unpaid_debt[$uid] > 0) {
//                 $to_pay = $user_unpaid_debt[$uid];
//                 $user_unpaid_debt[$uid] = 0; 
//             } else {
//                 $to_pay = 0;
//             }

//             // Aggregate only the jobs that still have a pending amount
//             if ($to_pay > 0) {
//                 $total_pending_amount += $to_pay;
//                 $total_pending_jobs++;
//             }
//         }
//     }

//     // Clear buffer and return exact JSON structure expected by your frontend script
//     if (ob_get_length()) ob_clean();
//     header('Content-Type: application/json');
//     echo json_encode([
//         "type" => 1,
//         "total_amount" => $total_pending_amount,
//         "total_count" => $total_pending_jobs
//     ]);
//     exit;
// }
elseif ($method == "pending_payment_dashboard_counts") {
    
    // Updated Query: Removed 'AND w.closeing_balance < 0' so it fully matches the report query
    $query = "SELECT 
                w.id AS ID, 
                w.userid,
                w.opening_balance, 
                w.total AS deducted_amount, 
                w.closeing_balance, 
                (SELECT closeing_balance FROM walletBalance_history 
                 WHERE userid = w.userid 
                 ORDER BY id DESC LIMIT 1) AS live_balance,
                (SELECT COUNT(id) FROM walletBalance_history r 
                 WHERE r.userid = w.userid 
                 AND r.transaction_type = 'REFUND' 
                 AND r.reference_id = w.reference_id 
                 AND r.reference_table = w.reference_table) AS refund_count
              FROM walletBalance_history w 
              WHERE w.ip = 'CRON' AND w.transaction_type = 'DEBIT'
              ORDER BY w.id DESC";

    $result = mysqli_query($con, $query);
    
    $rows = [];
    $userIds = [];
    
    // 1. Store results and collect user IDs first
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
            $userIds[] = $row['userid'];
        }
    }

    // 2. Fetch ONLY Valid Credits (WD% prefix and NOT Purchase)
    $user_credits = [];
    if (!empty($userIds)) {
        $uid_str = implode(',', array_unique($userIds));
        $credit_query = "SELECT w.userid, w.total 
                         FROM walletBalance_history w
                         INNER JOIN payment_history p ON w.reference_table = 'payment_history' AND w.reference_id = p.id
                         WHERE w.transaction_type = 'CREDIT' AND w.userid IN ($uid_str) 
                         AND p.transaction_id LIKE 'WD%'
                         AND p.category != 'Purchase'
                         ORDER BY w.id DESC"; 
                         
        $credit_res = mysqli_query($con, $credit_query);
        while ($c_row = mysqli_fetch_assoc($credit_res)) {
            $user_credits[$c_row['userid']][] = [
                'amount' => (float)$c_row['total']
            ];
        }
    }
    
    $total_pending_amount = 0;
    $total_pending_jobs = 0;
    $user_unpaid_debt = array();

    // 3. Calculate exact pending amounts mimicking the main report math
    foreach ($rows as $row) {
        $uid = $row['userid'];
        $live_bal = (float)$row['live_balance'];
        
        $is_refunded = ((int)$row['refund_count']) > 0;
        
        if (!isset($user_unpaid_debt[$uid])) {
            $user_unpaid_debt[$uid] = ($live_bal < 0) ? abs($live_bal) : 0;
        }

        $opening = (float)$row['opening_balance'];
        $deducted = (float)$row['deducted_amount'];
        $closing = (float)$row['closeing_balance'];

        // Guard added: Actual debt is only calculated if closing balance is negative
        $actual_debt = ($closing < 0) ? (($opening > 0) ? abs($closing) : $deducted) : 0;

        if ($is_refunded) {
            $to_pay = 0;
            $paid = 0;
        } else {
            // Normal Logic: Allocate Live Debt
            if ($user_unpaid_debt[$uid] >= $actual_debt) {
                $to_pay = $actual_debt;
                $paid = 0;
                $user_unpaid_debt[$uid] -= $actual_debt; 
            } elseif ($user_unpaid_debt[$uid] > 0) {
                $to_pay = $user_unpaid_debt[$uid];
                $paid = $actual_debt - $to_pay;
                $user_unpaid_debt[$uid] = 0; 
            } else {
                $to_pay = 0;
                $paid = $actual_debt; 
            }

            // NEW FIX: Ensure the amount 'paid' was covered by a VALID WD% credit
            $amount_to_split = round($paid, 2);
            if (isset($user_credits[$uid]) && $amount_to_split > 0) {
                foreach ($user_credits[$uid] as &$credit) {
                    if ($amount_to_split <= 0) break;
                    if ($credit['amount'] <= 0) continue;
                    
                    if ($credit['amount'] <= $amount_to_split) {
                        $amount_to_split = round($amount_to_split - $credit['amount'], 2);
                        $credit['amount'] = 0; 
                    } else {
                        $credit['amount'] = round($credit['amount'] - $amount_to_split, 2); 
                        $amount_to_split = 0;
                    }
                }
            }

            // If invalid credits (like purchases) caused a gap, push the leftover back to unpaid debt
            if ($amount_to_split > 0) {
                $to_pay += $amount_to_split;
                $paid -= $amount_to_split; // Though not used here, kept for logic consistency
            }
        }

        // Aggregate only the jobs that truly have a pending amount
        if ($to_pay > 0) {
            $total_pending_amount += $to_pay;
            $total_pending_jobs++;
        }
    }

    // Clear buffer and return exact JSON structure expected by your frontend script
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');
    echo json_encode([
        "type" => 1,
        "total_amount" => $total_pending_amount,
        "total_count" => $total_pending_jobs
    ]);
    exit;
}
else if ($method == 'carpool_kyc_summary') {
    $startDate = isset($_POST['start_date']) ? mysqli_real_escape_string($con, $_POST['start_date']) : date('Y-m-d');
    $endDate = isset($_POST['end_date']) ? mysqli_real_escape_string($con, $_POST['end_date']) : date('Y-m-d');

    $startDateTime = $startDate . ' 00:00:00';
    $endDateTime = $endDate . ' 23:59:59';

    // 🚀 ADAPTED: Removed o_rc. Added c.vehicle_details and updated o_dl to match fetch_vehicle_data logic
    $query = "SELECT c.id, c.doc_verify, c.vehicle_details, k.selfie_status, 
                     o_dl.status as dl_status 
              FROM customer_register c
              LEFT JOIN kyc_carpool k ON c.id = k.user_id
              LEFT JOIN ocr_request o_dl ON c.id = o_dl.user_id AND o_dl.global_type = 'customer' AND o_dl.doc_type IN ('DRIVING_LICENSE', 'DL')
              WHERE c.deletes = '0' 
              AND (
                  k.user_id IS NOT NULL 
                  OR (c.vehicle_details IS NOT NULL AND c.vehicle_details != 'null' AND c.vehicle_details != '') 
                  OR o_dl.user_id IS NOT NULL
              )
              AND (
                  k.created_at BETWEEN '$startDateTime' AND '$endDateTime' 
                  OR c.created_at BETWEEN '$startDateTime' AND '$endDateTime' 
                  OR o_dl.created_at BETWEEN '$startDateTime' AND '$endDateTime'
              )
              GROUP BY c.id"; 

    $res = mysqli_query($con, $query);
    
    // Arrays to hold individual counts
    $selfie = ['total' => 0, 'verified' => 0, 'pending' => 0, 'rejected' => 0];
    $dl = ['total' => 0, 'verified' => 0, 'pending' => 0, 'rejected' => 0];
    $vehicle = ['total' => 0, 'verified' => 0, 'pending' => 0, 'rejected' => 0];

    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            // 1. Selfie Logic
            if (!empty($row['selfie_status'])) {
                $selfie['total']++;
                $s_stat = strtolower($row['selfie_status']);
                if ($s_stat == 'verified' || $s_stat == 'approved' || $row['doc_verify'] == 1) {
                    $selfie['verified']++;
                } else if ($s_stat == 'rejected') {
                    $selfie['rejected']++;
                } else {
                    $selfie['pending']++;
                }
            }

            // 2. DL Logic
            if (!empty($row['dl_status'])) {
                $dl['total']++;
                $d_stat = strtolower($row['dl_status']);
                if ($d_stat == 'verified' || $d_stat == 'approved') {
                    $dl['verified']++;
                } else if ($d_stat == 'rejected') {
                    $dl['rejected']++;
                } else {
                    $dl['pending']++;
                }
            }

            // 3. Vehicle (RC) Logic based on JSON vehicle_details
            if (!empty($row['vehicle_details']) && $row['vehicle_details'] != 'null') {
                $vd = json_decode($row['vehicle_details'], true);
                
                if (is_array($vd)) {
                    $vehicle['total']++;
                    
                    // Prioritize 'car_type_status' as seen in your fetch_vehicle_data method
                    $v_stat = 'pending'; // Default fallback
                    if (isset($vd['car_type_status'])) {
                        $v_stat = strtolower($vd['car_type_status']);
                    } else if (isset($vd['status'])) {
                        // Fallback just in case it's saved as 'status'
                        $v_stat = strtolower($vd['status']);
                    }

                    if ($v_stat == 'verified' || $v_stat == 'approved') {
                        $vehicle['verified']++;
                    } else if ($v_stat == 'rejected') {
                        $vehicle['rejected']++;
                    } else {
                        $vehicle['pending']++;
                    }
                }
            }
        }
    }

    echo json_encode([
        'type' => 1,
        'data' => [
            'selfie' => $selfie,
            'dl' => $dl,
            'vehicle' => $vehicle
        ]
    ]);
    exit;
}
else if ($method == 'carpool_bookings_chart') {
    header('Content-Type: application/json; charset=utf-8');
    $startDate = isset($_POST['startDate']) ? trim(BlockSQLInjectionforagent($_POST['startDate'])) : '';
    $endDate   = isset($_POST['endDate']) ? trim(BlockSQLInjectionforagent($_POST['endDate'])) : '';
    $start = date('Y-m-d 00:00:00', strtotime($startDate));
    $end   = date('Y-m-d 23:59:59', strtotime($endDate));
    // Prepare zero-filled dates to prevent missing bars on the graph
    $dates = [];
    $period = new DatePeriod(new DateTime($start), new DateInterval('P1D'), new DateTime(date('Y-m-d', strtotime($end . ' +1 day'))));
    foreach ($period as $dt) {
        $dates[$dt->format("Y-m-d")] = [
            'public' => 0,
            'private' => 0
        ];
    }
    // Query specifically for carpool global_type based on pickup_date
    $sql = "SELECT DATE(pickup_date) as p_date, confirm_status 
            FROM cus_job_temp 
            WHERE deletes = '0' 
            AND global_type = 'carpool' 
            AND pickup_date BETWEEN '$start' AND '$end'";
    $res = mysqli_query($con, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $d = $row['p_date'];
            if (!isset($dates[$d])) continue;
            // Following your reference logic: confirm_status == 0 is private, otherwise public
            if ((int)$row['confirm_status'] === 0) {
                $dates[$d]['private']++;
            } else {
                $dates[$d]['public']++;
            }
        }
    }
    echo json_encode([
        'type'    => 1,
        'dates'   => array_keys($dates),
        'public'  => array_column($dates, 'public'),
        'private' => array_column($dates, 'private')
    ]);
    exit;
}
elseif ($_POST['method'] == 'customer_signup_chart') {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // 1. Generate an array of ALL dates in the selected range, defaulting to 0
    $period = new DatePeriod(
         new DateTime($startDate),
         new DateInterval('P1D'),
         (new DateTime($endDate))->modify('+1 day')
    );

    $results = [];
    foreach ($period as $dt) {
        $dateStr = $dt->format('Y-m-d');
        $results[$dateStr] = 0; // Default count to 0
    }

    // 2. Fetch actual signup data from the database
    $query = "SELECT DATE(created_at) as date_val, COUNT(id) as count 
              FROM customer_register 
              WHERE deletes = 0 AND (user = 'Customer' OR roll_id = 4) 
              AND DATE(created_at) BETWEEN '$startDate' AND '$endDate' 
              GROUP BY DATE(created_at) 
              ORDER BY DATE(created_at) ASC";
              
    $result = mysqli_query($con, $query);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $date_val = $row['date_val'];
            // Overwrite the 0 with the actual count if it exists
            if (isset($results[$date_val])) {
                $results[$date_val] = (int)$row['count'];
            }
        }
    }
    
    // 3. Format the final array for Chart.js
    $dates = [];
    $counts = [];
    
    foreach ($results as $date => $count) {
        $dates[] = date('d M', strtotime($date));
        $counts[] = $count;
    }
    
    echo json_encode([
        'type' => 1,
        'dates' => $dates,
        'counts' => $counts
    ]);
    exit;
}

// elseif ($method == 'job_details_summary') {
    
//     // 1. Get dates from payload
//     $startDate = $_POST['start_date'] ?? $_POST['startDate'] ?? date('Y-m-d');
//     $endDate   = $_POST['end_date'] ?? $_POST['endDate'] ?? date('Y-m-d');
    
//     // 2. Identify Job Type based on the frontend tab (Default to GRD/Driver)
//     $jobType   = $_POST['job_type'] ?? 'GRD'; 
//     $jobPrefix = ($jobType === 'GRC') ? 'GRC-%' : 'GRD-%';

//     // Append exact time boundaries for the date range
//     $start = $startDate . ' 00:00:00';
//     $end   = $endDate . ' 23:59:59';

//     // Get current date and time for Expired vs Unassigned logic
//     $now = date('Y-m-d H:i:s');

//     // -------------------------------------------------------------------------
//     // 1. ASSIGNED JOBS
//     // -------------------------------------------------------------------------
//     $q_assigned = "
//         SELECT COUNT(o.id) as c 
//         FROM `open_jobs` o 
//         INNER JOIN `cus_job_temp` c ON o.job_no = c.job_no 
//         WHERE o.deletes = '0' AND c.deletes = '0' 
//         AND o.job_status IN ('accept', 'accepted') 
//         AND c.job_status IN ('accept', 'accepted') 
//         AND c.job_no LIKE '$jobPrefix'
//         AND o.created_at BETWEEN '$start' AND '$end'
//     ";
//     $assigned_jobs = (int)(mysqli_fetch_assoc(mysqli_query($con, $q_assigned))['c'] ?? 0);

//     // -------------------------------------------------------------------------
//     // 2. UNASSIGNED JOBS (App/Manual Jobs Only)
//     // -------------------------------------------------------------------------
//     $q_unassigned = "
//         SELECT COUNT(id) as c 
//         FROM `cus_job_temp` 
//         WHERE deletes = '0' 
//         AND user_id != '0' 
//         AND job_status NOT IN ('accept', 'accepted', 'completed', 'cancelled') 
//         AND pickup_date >= '$now' 
//         AND job_no LIKE '$jobPrefix'
//         AND created_at BETWEEN '$start' AND '$end'
//     ";
//     $unassigned_jobs = (int)(mysqli_fetch_assoc(mysqli_query($con, $q_unassigned))['c'] ?? 0);

//     // -------------------------------------------------------------------------
//     // 3. CANCELLED JOBS
//     // -------------------------------------------------------------------------
//     $q_cancelled = "
//         SELECT COUNT(id) as c 
//         FROM `cus_job_temp` 
//         WHERE job_status = 'cancelled' 
//         AND job_no LIKE '$jobPrefix'
//         AND created_at BETWEEN '$start' AND '$end'
//     ";
//     $cancelled_jobs = (int)(mysqli_fetch_assoc(mysqli_query($con, $q_cancelled))['c'] ?? 0);

//     // -------------------------------------------------------------------------
//     // 4. EXPIRED JOBS 
//     // -------------------------------------------------------------------------
//     $q_expired = "
//         SELECT COUNT(id) as c 
//         FROM `cus_job_temp` 
//         WHERE global_type = 'customer' 
//         AND job_status = 'created' 
//         AND pickup_date < '$now' 
//         AND job_no LIKE '$jobPrefix'
//         AND pickup_date BETWEEN '$start' AND '$end'
//     ";
//     $expired_jobs = (int)(mysqli_fetch_assoc(mysqli_query($con, $q_expired))['c'] ?? 0);

//     // -------------------------------------------------------------------------
//     // 5. WEBSITE JOBS (Active Website Jobs Only)
//     // -------------------------------------------------------------------------
//     $q_website = "
//         SELECT COUNT(id) as c 
//         FROM `cus_job_temp` 
//         WHERE user_id = '0' 
//         AND deletes = '0'
//         AND job_status NOT IN ('accept', 'accepted', 'completed', 'cancelled')
//         AND pickup_date >= '$now'
//         AND job_no LIKE '$jobPrefix'
//         AND created_at BETWEEN '$start' AND '$end'
//     ";
//     $website_jobs = (int)(mysqli_fetch_assoc(mysqli_query($con, $q_website))['c'] ?? 0);

//     // -------------------------------------------------------------------------
//     // 6. TOTAL JOBS (Mathematical Sum)
//     // -------------------------------------------------------------------------
//     $total_jobs = $assigned_jobs + $unassigned_jobs + $cancelled_jobs + $expired_jobs + $website_jobs;

//     // Return the perfectly mapped data
//     echo json_encode([
//         'type' => 1,
//         'data' => [
//             'total_jobs'      => $total_jobs,
//             'assigned_jobs'   => $assigned_jobs,
//             'cancelled_jobs'  => $cancelled_jobs,
//             'expired_jobs'    => $expired_jobs,
//             'unassigned_jobs' => $unassigned_jobs,
//             'website_jobs'    => $website_jobs
//         ]
//     ]);
//     exit;
// }
elseif ($method == 'job_details_summary') {
    
    // Ensure accurate current time matching Laravel Carbon
    date_default_timezone_set('Asia/Kolkata');
    $now = date('Y-m-d H:i:s');

    // 1. Get dates from payload
    $startDate = $_POST['start_date'] ?? $_POST['startDate'] ?? date('Y-m-d');
    $endDate   = $_POST['end_date'] ?? $_POST['endDate'] ?? date('Y-m-d');
    
    // Append exact time boundaries
    $start = $startDate . ' 00:00:00';
    $end   = $endDate . ' 23:59:59';

    // 2. Identify Job Type (GRD naturally excludes GRP- without needing NOT LIKE)
    $jobType   = $_POST['job_type'] ?? 'GRD'; 
    $jobPrefix = ($jobType === 'GRC') ? 'GRC-%' : 'GRD-%';

    // BASE CONDITION: Applied to all queries to strictly use pickup_date
    $baseWhere = "WHERE deletes = '0' AND job_no LIKE '$jobPrefix' AND pickup_date BETWEEN '$start' AND '$end'";

    // -------------------------------------------------------------------------
    // 1. ASSIGNED JOBS (Future & Current Active jobs)
    // Based on jobList_New logic: IN ('accept', 'accepted', 'started') and pickup >= now
    // -------------------------------------------------------------------------
    $q_assigned = "
        SELECT COUNT(id) as c FROM `cus_job_temp` 
        $baseWhere 
        AND job_status IN ('accept', 'accepted', 'started') 
        AND pickup_date >= '$now'
    ";
    $assigned_jobs = (int)(mysqli_fetch_assoc(mysqli_query($con, $q_assigned))['c'] ?? 0);

    // -------------------------------------------------------------------------
    // 2. COMPLETED JOBS (NEW)
    // Based on jobList_New logic: Marked completed, OR was assigned/started but pickup_date passed
    // -------------------------------------------------------------------------
    $q_completed = "
        SELECT COUNT(id) as c FROM `cus_job_temp` 
        $baseWhere 
        AND (
            job_status = 'completed' OR 
            (job_status IN ('accept', 'accepted', 'started') AND pickup_date < '$now')
        )
    ";
    $completed_jobs = (int)(mysqli_fetch_assoc(mysqli_query($con, $q_completed))['c'] ?? 0);

    // -------------------------------------------------------------------------
    // 3. EXPIRED JOBS 
    // Based on getExpiredJobs: Not active/done and pickup_date has passed
    // -------------------------------------------------------------------------
    $q_expired = "
        SELECT COUNT(id) as c FROM `cus_job_temp` 
        $baseWhere 
        AND job_status NOT IN ('accept', 'accepted', 'started', 'completed', 'cancelled') 
        AND pickup_date < '$now'
    ";
    $expired_jobs = (int)(mysqli_fetch_assoc(mysqli_query($con, $q_expired))['c'] ?? 0);

    // -------------------------------------------------------------------------
    // 4. UNASSIGNED JOBS (App/Manual Jobs in the Future)
    // Based on adminJobList: Not assigned/expired/cancelled, user_id != '0'
    // -------------------------------------------------------------------------
    $q_unassigned = "
        SELECT COUNT(id) as c FROM `cus_job_temp` 
        $baseWhere 
        AND job_status NOT IN ('accept', 'accepted', 'started', 'completed', 'cancelled') 
        AND pickup_date >= '$now' 
        AND user_id != '0'
    ";
    $unassigned_jobs = (int)(mysqli_fetch_assoc(mysqli_query($con, $q_unassigned))['c'] ?? 0);

    // -------------------------------------------------------------------------
    // 5. WEBSITE JOBS (Active Website Jobs in the Future)
    // Based on adminJobList fix: same as unassigned but user_id = '0'
    // -------------------------------------------------------------------------
    $q_website = "
        SELECT COUNT(id) as c FROM `cus_job_temp` 
        $baseWhere 
        AND job_status NOT IN ('accept', 'accepted', 'started', 'completed', 'cancelled') 
        AND pickup_date >= '$now' 
        AND user_id = '0'
    ";
    $website_jobs = (int)(mysqli_fetch_assoc(mysqli_query($con, $q_website))['c'] ?? 0);

    // -------------------------------------------------------------------------
    // 6. CANCELLED JOBS
    // -------------------------------------------------------------------------
    $q_cancelled = "
        SELECT COUNT(id) as c FROM `cus_job_temp` 
        $baseWhere 
        AND job_status = 'cancelled'
    ";
    $cancelled_jobs = (int)(mysqli_fetch_assoc(mysqli_query($con, $q_cancelled))['c'] ?? 0);

    // -------------------------------------------------------------------------
    // 7. TOTAL JOBS (Mathematical Sum)
    // -------------------------------------------------------------------------
    $total_jobs = $assigned_jobs + $completed_jobs + $unassigned_jobs + $website_jobs + $expired_jobs + $cancelled_jobs;

    // Return mapped data
    echo json_encode([
        'type' => 1,
        'data' => [
            'total_jobs'      => $total_jobs,
            'assigned_jobs'   => $assigned_jobs,
            'completed_jobs'  => $completed_jobs,
            'unassigned_jobs' => $unassigned_jobs,
            'website_jobs'    => $website_jobs,
            'expired_jobs'    => $expired_jobs,
            'cancelled_jobs'  => $cancelled_jobs
        ]
    ]);
    exit;
}
elseif ($method == 'app_jobs_chart') {
    
    // 1. Get dates and type from payload
    $startDate = $_POST['startDate'] ?? date('Y-m-d');
    $endDate   = $_POST['endDate'] ?? date('Y-m-d');
    $jobType   = $_POST['jobType'] ?? 'GRD'; // 'GRD' or 'GRC'

    // Append exact time boundaries for the date range
    $start = $startDate . ' 00:00:00';
    $end   = $endDate . ' 23:59:59';
    $currentDateTime = date('Y-m-d H:i:s');

    // Set the prefix based on the requested App
    $prefix = ($jobType === 'GRD') ? 'GRD-%' : 'GRC-%';

    // 2. Fill missing days with 0 (so the chart doesn't break on empty days)
    $period = new DatePeriod(
         new DateTime($startDate),
         new DateInterval('P1D'),
         (new DateTime($endDate))->modify('+1 day')
    );

    $results = [];
    foreach ($period as $dt) {
        $dateStr = $dt->format('Y-m-d');
        $results[$dateStr] = [
            'posted'    => 0,
            'accepted'  => 0,
            'completed' => 0,
            'cancelled' => 0
        ];
    }

    // 3. MySQL Query matching the `jobList_New` logic strictly
    // - Posted: Any status other than cancelled, but hasn't been accepted yet
    // - Accepted: Status is accept/accepted AND pickup_date is in the future
    // - Completed: Status is accept/accepted AND pickup_date is in the past
    // - Cancelled: Status is cancelled
    
    $query = "
        SELECT DATE(created_at) as date_val,
               
               -- POSTED: It is created/pending/new
               SUM(CASE WHEN job_status IN ('created', 'pending', 'new') 
                        THEN 1 ELSE 0 END) as posted_count,
               
               -- ACCEPTED: It is accepted, but pickup time hasn't passed yet
               SUM(CASE WHEN job_status IN ('accept', 'accepted') AND pickup_date >= '$currentDateTime' 
                        THEN 1 ELSE 0 END) as accepted_count,
               
               -- COMPLETED: It was accepted, and the pickup time has passed
               SUM(CASE WHEN job_status IN ('accept', 'accepted') AND pickup_date < '$currentDateTime' 
                        THEN 1 ELSE 0 END) as completed_count,
               
               -- CANCELLED: Explicitly cancelled
               SUM(CASE WHEN job_status = 'cancelled' 
                        THEN 1 ELSE 0 END) as cancelled_count
                        
        FROM `cus_job_temp`
        WHERE job_no LIKE '$prefix'
        AND deletes = '0'
        AND created_at BETWEEN '$start' AND '$end'
        GROUP BY DATE(created_at)
        ORDER BY DATE(created_at) ASC
    ";

    $result_query = mysqli_query($con, $query);

    if ($result_query) {
        while ($row = mysqli_fetch_assoc($result_query)) {
            $date_val = $row['date_val'];
            // Insert counts into the corresponding date bucket
            if (isset($results[$date_val])) {
                $results[$date_val]['posted']    = (int)$row['posted_count'];
                $results[$date_val]['accepted']  = (int)$row['accepted_count'];
                $results[$date_val]['completed'] = (int)$row['completed_count'];
                $results[$date_val]['cancelled'] = (int)$row['cancelled_count'];
            }
        }
    }

    // 4. Format arrays specifically for Chart.js to map properly
    $dates = [];
    $posted = [];
    $accepted = [];
    $completed = [];
    $cancelled = [];

    foreach ($results as $date => $counts) {
        $dates[] = date('d M', strtotime($date));
        $posted[] = $counts['posted'];
        $accepted[] = $counts['accepted'];
        $completed[] = $counts['completed'];
        $cancelled[] = $counts['cancelled'];
    }

    // Return the JSON Payload
    echo json_encode([
        'type'      => 1,
        'dates'     => $dates,
        'posted'    => $posted,
        'accepted'  => $accepted,
        'completed' => $completed,
        'cancelled' => $cancelled
    ]);
    exit;
}


else if ($method == "hour_chart") {
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
// 21-05-2026
// else if ($method == 'jobList_New') {
//     header('Content-Type: application/json; charset=utf-8');
    
//     $result = [];
//     $status = isset($_POST['jobStatus']) ? trim(BlockSQLInjectionforagent($_POST['jobStatus'])) : '';
//     $startDate = isset($_POST['startDate']) ? trim(BlockSQLInjectionforagent($_POST['startDate'])) : '';
//     $endDate = isset($_POST['endDate']) ? trim(BlockSQLInjectionforagent($_POST['endDate'])) : '';
//     $filterType = isset($_POST['filterType']) ? trim(BlockSQLInjectionforagent($_POST['filterType'])) : '';

//     $dateCol = ($filterType == 'pickup') ? 'o.pickup_date' : 'o.created_at';
//     $currentDateTime = date('Y-m-d H:i:s');

//     $jobsMap = []; 

//     // ---------------------------------------------------------
//     // 1. FETCH NORMAL JOBS
//     // ---------------------------------------------------------
//     $sql = "SELECT o.*, d.name as driver_name, c.id as c_id, c.job_status as c_status, c.global_type as c_global_type, 
//             c.user_details as c_user_details, c.base_fare as c_base_fare, c.toll_fare as c_toll_fare, 
//             c.preview_hash, c.duration as c_duration, c.distance as c_distance, cf.rating as fb_rating, 
//             cf.review as fb_review, c.payment_status as c_payment_status, jc.created_at as c_cancelled_at 
//             FROM `open_jobs` o
//             LEFT JOIN `user_register` d ON o.assigned_to = d.id
//             INNER JOIN `cus_job_temp` c ON o.job_no = c.job_no
//             LEFT JOIN `customer_feedback` cf ON c.id = cf.job_id
//             LEFT JOIN `job_cancellations` jc ON c.id = jc.job_id
//             WHERE o.deletes = '0' AND c.deletes = '0'";

//     if ($status == 'completed') {
//         $sql .= " AND ( ((o.job_status = 'accept' OR o.job_status = 'accepted' OR o.job_status = 'started') AND o.pickup_date < '$currentDateTime') OR o.job_status = 'completed' OR c.job_status = 'completed' )";
//     } else {
//         $sql .= " AND o.pickup_date >= '$currentDateTime'";
//         if ($status == 'accepted') {
//             $sql .= " AND (o.job_status = 'accept' OR o.job_status = 'accepted' OR o.job_status = 'started') 
//                       AND (c.job_status = 'accept' OR c.job_status = 'accepted' OR c.job_status = 'started')";
//         } 
//         elseif ($status == 'not_complete') {
//             $sql .= " AND o.job_status != 'accept' AND o.job_status != 'accepted' AND o.job_status != 'started' AND o.job_status != 'cancelled'";
//         } 
//         elseif ($status != '') {
//             $sql .= " AND o.job_status = '$status'";
//         }
//     }

//     if (!empty($startDate) && !empty($endDate)) {
//         $start = date('Y-m-d 00:00:00', strtotime($startDate));
//         $end = date('Y-m-d 23:59:59', strtotime($endDate));
//         $sql .= " AND $dateCol BETWEEN '$start' AND '$end'";
//     }

//     $sql .= " ORDER BY o.pickup_date ASC";
//     $res = mysqli_query($con, $sql);

//     if ($res && mysqli_num_rows($res) > 0) {
//         while ($row = mysqli_fetch_assoc($res)) {
//             $jobsMap[$row['job_no']] = $row; 
//         }
//     }

//     // ---------------------------------------------------------
//     // 2. FETCH SCHEDULED JOBS
//     // ---------------------------------------------------------
//     if (in_array($status, ['accepted', 'not_complete', 'completed', ''])) {
//         $sch_sql = "SELECT c.*, c.id as c_id, d.name as driver_name, c.job_status as c_status, c.global_type as c_global_type, 
//                 c.user_details as c_user_details, c.base_fare as c_base_fare, c.toll_fare as c_toll_fare, 
//                 c.preview_hash, c.duration as c_duration, c.distance as c_distance, cf.rating as fb_rating, 
//                 cf.review as fb_review, c.payment_status as c_payment_status, jc.created_at as c_cancelled_at 
//                 FROM `cus_job_temp` c
//                 LEFT JOIN `user_register` d ON c.assigned_to = d.id
//                 LEFT JOIN `customer_feedback` cf ON c.id = cf.job_id
//                 LEFT JOIN `job_cancellations` jc ON c.id = jc.job_id
//                 WHERE c.deletes = '0' AND c.global_type = 'schedule' AND c.payment_status = 'paid'";

//         if ($status == 'completed') {
//             $sch_sql .= " AND ( ((c.job_status = 'accept' OR c.job_status = 'accepted' OR c.job_status = 'started') AND c.pickup_date < '$currentDateTime') OR c.job_status = 'completed' )";
//         } else {
//             $todayStart = date('Y-m-d 00:00:00');
//             if ($status == 'accepted') {
//                 $sch_sql .= " AND (c.job_status = 'accept' OR c.job_status = 'accepted' OR c.job_status = 'started') AND c.pickup_date >= '$todayStart'";
//             } 
//             elseif ($status == 'not_complete') {
//                 $sch_sql .= " AND c.job_status != 'accept' AND c.job_status != 'accepted' AND c.job_status != 'started' AND c.job_status != 'cancelled' AND c.pickup_date >= '$todayStart'";
//             }
//         }

//         if (!empty($startDate) && !empty($endDate)) {
//             $start = date('Y-m-d 00:00:00', strtotime($startDate));
//             $end = date('Y-m-d 23:59:59', strtotime($endDate));
//             $sch_dateCol = ($filterType == 'pickup') ? 'c.pickup_date' : 'c.created_at';
//             $sch_sql .= " AND $sch_dateCol BETWEEN '$start' AND '$end'";
//         }

//         $sch_res = mysqli_query($con, $sch_sql);
//         if ($sch_res && mysqli_num_rows($sch_res) > 0) {
//             while ($row = mysqli_fetch_assoc($sch_res)) {
//                 if (!isset($jobsMap[$row['job_no']])) {
//                     $jobsMap[$row['job_no']] = $row;
//                 }
//             }
//         }
//     }

//     // ---------------------------------------------------------
//     // 3. PRE-FETCH BULK DATA (Eliminating N+1 Queries)
//     // ---------------------------------------------------------
//     $customer_ids_to_fetch = [];
//     $driver_ids_to_fetch = [];
//     $push_jobs_to_fetch = [];

//     foreach ($jobsMap as $job_no => $row) {
//         $uid = $row['user_id'] ?? 0;
        
//         if (isset($row['c_global_type']) && $row['c_global_type'] === 'schedule') {
//             $g_type = 'schedule';
//         } else if (empty($uid) || $uid == 0 || !empty($row['c_user_details'])) {
//             $g_type = 'website';
//         } else if (strpos($job_no, 'GRC') === 0) {
//             $g_type = 'customer';
//         } else if (strpos($job_no, 'GRD') === 0) {
//             $g_type = 'driver'; 
//         } else {
//             $g_type = 'website';
//         }
//         $jobsMap[$job_no]['calc_global_type'] = $g_type;

//         if (!empty($uid) && $uid != 0 && $g_type !== 'website') {
//             if ($g_type === 'driver') $driver_ids_to_fetch[$uid] = $uid;
//             else $customer_ids_to_fetch[$uid] = $uid;
//         }

//         $currentStatus = strtolower($row['job_status'] ?? '');
//         if (($currentStatus === 'accept' || $currentStatus === 'accepted' || $currentStatus === 'started' || $currentStatus === 'completed') && !empty($row['c_id'])) {
//             $push_jobs_to_fetch[$row['c_id']] = $row['c_id'];
//         }
//     }

//     $customer_data = [];
//     if (!empty($customer_ids_to_fetch)) {
//         $ids = implode(',', $customer_ids_to_fetch);
//         $q = mysqli_query($con, "SELECT id, name, mobile FROM customer_register WHERE id IN ($ids)");
//         if ($q) while($r = mysqli_fetch_assoc($q)) $customer_data[$r['id']] = $r;
//     }

//     $driver_data = [];
//     if (!empty($driver_ids_to_fetch)) {
//         $ids = implode(',', $driver_ids_to_fetch);
//         $q = mysqli_query($con, "SELECT id, name, mobile FROM user_register WHERE id IN ($ids)");
//         if ($q) while($r = mysqli_fetch_assoc($q)) $driver_data[$r['id']] = $r;
//     }

//     $push_data = [];
//     if (!empty($push_jobs_to_fetch)) {
//         $chunks = array_chunk($push_jobs_to_fetch, 50);
//         foreach ($chunks as $chunk) {
//             $likeConditions = [];
//             foreach ($chunk as $jid) {
//                 $likeConditions[] = "req_json LIKE '%\"job_id\": $jid%' OR req_json LIKE '%\"job_id\":$jid%'";
//             }
//             $whereClause = implode(' OR ', $likeConditions);
//             $q = mysqli_query($con, "SELECT req_json, res_json FROM push_notifications WHERE ($whereClause) ORDER BY id ASC");
//             if ($q) {
//                 while($r = mysqli_fetch_assoc($q)) {
//                     foreach ($chunk as $jid) {
//                         if (strpos($r['req_json'], '"job_id": '.$jid) !== false || strpos($r['req_json'], '"job_id":'.$jid) !== false) {
//                             $resData = !empty($r['res_json']) ? json_decode($r['res_json'], true) : [];
//                             if (isset($resData['success_count'])) {
//                                 $push_data[$jid] = (int)$resData['success_count'];
//                             }
//                         }
//                     }
//                 }
//             }
//         }
//     }

//     // ---------------------------------------------------------
//     // 4. FINAL PROCESS AND FORMAT
//     // ---------------------------------------------------------
//     $finalJobs = [];
//     foreach ($jobsMap as $job_no => $row) {
        
//         $fareBreakdown = [];
//         if (!empty($row['fare_breakdown']) && is_string($row['fare_breakdown'])) {
//             $fareData = json_decode($row['fare_breakdown'], true);
//             if (json_last_error() === JSON_ERROR_NONE && is_array($fareData)) {
//                 $fareBreakdown = $fareData;
//                 $row = array_merge($row, $fareData);
//             }
//         }

//         $baseFare   = (float) (array_key_exists('base_fare', $fareBreakdown) ? $fareBreakdown['base_fare'] : ($row['base_fare'] ?? $row['c_base_fare'] ?? 0));
//         $tollFare   = (float) (array_key_exists('toll_fare', $fareBreakdown) ? $fareBreakdown['toll_fare'] : ($row['toll_fare'] ?? $row['c_toll_fare'] ?? 0));
//         $tax        = (float) (array_key_exists('tax_fare', $fareBreakdown) ? $fareBreakdown['tax_fare'] : (array_key_exists('tax', $fareBreakdown) ? $fareBreakdown['tax'] : ($row['tax'] ?? $row['c_tax'] ?? 0)));
//         $commission = (float) (array_key_exists('com', $fareBreakdown) ? $fareBreakdown['com'] : ($row['com'] ?? 0));
//         $discount   = (float) (array_key_exists('discount', $fareBreakdown) ? $fareBreakdown['discount'] : ($row['discount'] ?? 0));
//         $isDiscount = array_key_exists('isDiscount', $fareBreakdown) ? $fareBreakdown['isDiscount'] : ($row['isDiscount'] ?? '');

//         $jobStatus = strtolower($row['job_status'] ?? '');
        
//         if($jobStatus == 'created' || $jobStatus == 'bidding' || $jobStatus == 'schedule'){
//             $totalFare = (float) (array_key_exists('total_fare', $fareBreakdown) ? $fareBreakdown['total_fare'] : ($row['fare'] ?? 0));
//         } else {
//             $totalFare  = (float) ($row['fare'] ?? 0);
//         }

//         $b_amt = 0;
//         $paid_on = 0;
//         $paid_wallet = 0;
//         $pay_amt = (float) ($row['pay_amt'] ?? 0);
//         $deductAmt = $row['deductAmt'] ?? null;
//         $gateway = $row['gateway'] ?? '';

//         if(array_key_exists('total_fare', $fareBreakdown) && (float)$fareBreakdown['total_fare'] == $pay_amt && $deductAmt == null){
//             $b_amt = 0;
//         }else if($deductAmt != 0 && array_key_exists('pay_to_driver', $fareBreakdown)){
//             $b_amt = (float) $fareBreakdown['pay_to_driver'];
//         }else{
//             $b_amt = $baseFare + $tollFare;
//         }
        
//         $paid_on = (float) ($row['fare'] ?? 0);
//         $credit_bonus = $isDiscount == 'yes' ? $discount : 0;
        
//         if($gateway && $gateway == 'wallet'){
//             $paid_on = 0;
//             $paid_wallet = $pay_amt;
//         }else if($gateway && $gateway != 'wallet'){
//             $paid_on = $pay_amt;
//             $paid_wallet = (float) ($row['wallet_amt'] ?? 0);
//         }
        
//         if(($row['payment_status'] ?? $row['c_payment_status'] ?? 'pending') == 'pending' && $deductAmt == null){
//             $paid_on = 0;
//             $paid_wallet = 0;
//             $b_amt = 0;
//         }
        
//         if($jobStatus == 'created' || $jobStatus == 'bidding'){
//             $b_amt = 0;
//             $paid_on = 0;
//             $paid_wallet = 0;
//         }

//         $row['actual_base']  = $baseFare + $commission;
//         $row['base_fare']    = ($isDiscount == 'yes' && $discount > 0) ? ($baseFare + $commission) - $discount : ($baseFare + $commission);
//         $row['govt_levy']    = $tollFare;
//         $row['tax']          = $tax;
//         $row['com']          = $commission;
//         $row['discount']     = $discount;
//         $row['total_fare']   = $totalFare;
//         $row['fare']         = $totalFare; 
//         $row['isDiscount']   = $isDiscount;
//         $row['paid_amt']     = $paid_on;
//         $row['wallet_amt']   = $paid_wallet;
//         $row['credit_bonus'] = $credit_bonus;
//         $row['balance_amt']  = $b_amt;

//         if (isset($row['c_global_type']) && $row['c_global_type'] === 'schedule') {
//             $row['base_fare'] = null;
//             $row['toll_fare'] = null;
//             $row['tax']       = null;
//         }

//         $bidsDetails = !empty($row['bids_details']) ? json_decode($row['bids_details'], true) : [];
//         $row['fare_breakdown'] = $fareBreakdown;
//         $row['add_fare_details'] = !empty($row['add_fare_details']) ? json_decode($row['add_fare_details'], true) : null;
//         $row['liked_users'] = !empty($row['liked_users']) ? json_decode($row['liked_users'], true) : [];
//         $row['feedback_rating'] = $row['fb_rating'] ?? null;
//         $row['feedback_review'] = $row['fb_review'] ?? null;
//         $row['payment_status'] = $row['c_payment_status'] ?? 'pending';

//         $assignedTo = $row['assigned_to'] ?? 0;
//         if (!empty($assignedTo) && $assignedTo > 0) {
//             if (!isset($bidsDetails[$assignedTo])) {
//                 $bidsDetails[$assignedTo] = [];
//             }
//             $bidsDetails[$assignedTo]['status'] = 'accept';
//             $bidsDetails[$assignedTo]['amount'] = isset($bidsDetails[$assignedTo]['amount']) ? $bidsDetails[$assignedTo]['amount'] : $row['fare'];
//             $bidsDetails[$assignedTo]['b_name'] = !empty($row['driver_name']) ? $row['driver_name'] : 'Unknown Driver';
//         }
//         $row['bids_details'] = (object)$bidsDetails;

//         $uid = $row['user_id'] ?? 0;
//         $poster_name = 'Customer';
//         $mobile = '';
        
//         $row['global_type'] = $row['calc_global_type']; 
//         $row['user_details'] = null;

//         if ($row['global_type'] === 'website') {
//             if (!empty($row['c_user_details'])) {
//                 $uDetails = json_decode($row['c_user_details'], true);
//                 $row['user_details'] = (object)$uDetails;
//                 $poster_name = isset($uDetails['name']) ? $uDetails['name'] : 'Website Customer';
//                 $mobile = isset($uDetails['mobile']) ? $uDetails['mobile'] : '';
//             } else {
//                 $poster_name = 'Website Customer';
//             }
//         } else {
//             if (!empty($uid) && $uid != 0) {
//                 if ($row['global_type'] === 'driver' && isset($driver_data[$uid])) {
//                     $poster_name = $driver_data[$uid]['name'];
//                     $mobile = $driver_data[$uid]['mobile'];
//                 } elseif (isset($customer_data[$uid])) {
//                     $poster_name = $customer_data[$uid]['name'];
//                     $mobile = $customer_data[$uid]['mobile'];
//                 }
//             }
//         }
        
//         $row['name'] = $poster_name;
//         $row['poster_name'] = $poster_name;
//         if (empty($row['mobile'])) $row['mobile'] = $mobile;
        
//         $row['duration'] = !empty($row['duration']) ? $row['duration'] : ($row['c_duration'] ?? '');
//         $row['distance'] = !empty($row['distance']) ? $row['distance'] : ($row['c_distance'] ?? '');

//         // 🚀 Normalize the cancelled_at value properly
//         $row['cancelled_at'] = !empty($row['cancelled_at']) ? $row['cancelled_at'] : ($row['c_cancelled_at'] ?? null);

//         // 🚀 Remove temporary fields
//         unset($row['c_status'], $row['c_global_type'], $row['c_user_details'], $row['c_base_fare'], $row['c_toll_fare'], $row['c_duration'], $row['c_distance'], $row['fb_rating'], $row['fb_review'], $row['c_payment_status'], $row['calc_global_type'], $row['c_cancelled_at']);

//         $row['count'] = 0;
//         if (!empty($row['c_id']) && isset($push_data[$row['c_id']])) {
//             $row['count'] = $push_data[$row['c_id']];
//         }

//         $finalJobs[] = $row;
//     }

//     echo json_encode([
//         'type' => 1,
//         'result' => array_values($finalJobs) 
//     ]);
//     exit;
// }
else if ($method == 'jobList_New') {
    header('Content-Type: application/json; charset=utf-8');
    
    $result = [];
    $status = isset($_POST['jobStatus']) ? trim(BlockSQLInjectionforagent($_POST['jobStatus'])) : '';
    $startDate = isset($_POST['startDate']) ? trim(BlockSQLInjectionforagent($_POST['startDate'])) : '';
    $endDate = isset($_POST['endDate']) ? trim(BlockSQLInjectionforagent($_POST['endDate'])) : '';
    $filterType = isset($_POST['filterType']) ? trim(BlockSQLInjectionforagent($_POST['filterType'])) : '';

    $dateCol = ($filterType == 'pickup') ? 'o.pickup_date' : 'o.created_at';
    $currentDateTime = date('Y-m-d H:i:s');

    $jobsMap = []; 

    // ---------------------------------------------------------
    // 1. FETCH NORMAL JOBS
    // ---------------------------------------------------------
    $sql = "SELECT o.*, d.name as driver_name, c.id as c_id, c.job_status as c_status, c.global_type as c_global_type, 
            c.user_details as c_user_details, c.base_fare as c_base_fare, c.toll_fare as c_toll_fare, 
            c.preview_hash, c.duration as c_duration, c.distance as c_distance, cf.rating as fb_rating, 
            cf.review as fb_review, c.payment_status as c_payment_status, jc.created_at as c_cancelled_at,
            c.otpVerify as c_otpVerify
            FROM `open_jobs` o
            LEFT JOIN `user_register` d ON o.assigned_to = d.id
            INNER JOIN `cus_job_temp` c ON o.job_no = c.job_no
            LEFT JOIN `customer_feedback` cf ON c.id = cf.job_id
            LEFT JOIN `job_cancellations` jc ON c.id = jc.job_id
            WHERE o.deletes = '0' AND c.deletes = '0'";

    if ($status == 'completed') {
        $sql .= " AND ( ((o.job_status = 'accept' OR o.job_status = 'accepted' OR o.job_status = 'started') AND o.pickup_date < '$currentDateTime') OR o.job_status = 'completed' OR c.job_status = 'completed' )";
    } else {
        $sql .= " AND o.pickup_date >= '$currentDateTime'";
        if ($status == 'accepted') {
            $sql .= " AND (o.job_status = 'accept' OR o.job_status = 'accepted' OR o.job_status = 'started') 
                      AND (c.job_status = 'accept' OR c.job_status = 'accepted' OR c.job_status = 'started')";
        } 
        elseif ($status == 'not_complete') {
            $sql .= " AND o.job_status != 'accept' AND o.job_status != 'accepted' AND o.job_status != 'started' AND o.job_status != 'cancelled'";
        } 
        elseif ($status != '') {
            $sql .= " AND o.job_status = '$status'";
        }
    }

    if (!empty($startDate) && !empty($endDate)) {
        $start = date('Y-m-d 00:00:00', strtotime($startDate));
        $end = date('Y-m-d 23:59:59', strtotime($endDate));
        $sql .= " AND $dateCol BETWEEN '$start' AND '$end'";
    }

    $sql .= " ORDER BY o.pickup_date ASC";
    $res = mysqli_query($con, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $jobsMap[$row['job_no']] = $row; 
        }
    }

    // ---------------------------------------------------------
    // 2. FETCH SCHEDULED JOBS
    // ---------------------------------------------------------
    if (in_array($status, ['accepted', 'not_complete', 'completed', ''])) {
        $sch_sql = "SELECT c.*, c.id as c_id, d.name as driver_name, c.job_status as c_status, c.global_type as c_global_type, 
                c.user_details as c_user_details, c.base_fare as c_base_fare, c.toll_fare as c_toll_fare, 
                c.preview_hash, c.duration as c_duration, c.distance as c_distance, cf.rating as fb_rating, 
                cf.review as fb_review, c.payment_status as c_payment_status, jc.created_at as c_cancelled_at,
                c.otpVerify as c_otpVerify
                FROM `cus_job_temp` c
                LEFT JOIN `user_register` d ON c.assigned_to = d.id
                LEFT JOIN `customer_feedback` cf ON c.id = cf.job_id
                LEFT JOIN `job_cancellations` jc ON c.id = jc.job_id
                WHERE c.deletes = '0' AND c.global_type = 'schedule' AND c.payment_status = 'paid'";

        if ($status == 'completed') {
            $sch_sql .= " AND ( ((c.job_status = 'accept' OR c.job_status = 'accepted' OR c.job_status = 'started') AND c.pickup_date < '$currentDateTime') OR c.job_status = 'completed' )";
        } else {
            $todayStart = date('Y-m-d 00:00:00');
            if ($status == 'accepted') {
                $sch_sql .= " AND (c.job_status = 'accept' OR c.job_status = 'accepted' OR c.job_status = 'started') AND c.pickup_date >= '$todayStart'";
            } 
            elseif ($status == 'not_complete') {
                $sch_sql .= " AND c.job_status != 'accept' AND c.job_status != 'accepted' AND c.job_status != 'started' AND c.job_status != 'cancelled' AND c.pickup_date >= '$todayStart'";
            }
        }

        if (!empty($startDate) && !empty($endDate)) {
            $start = date('Y-m-d 00:00:00', strtotime($startDate));
            $end = date('Y-m-d 23:59:59', strtotime($endDate));
            $sch_dateCol = ($filterType == 'pickup') ? 'c.pickup_date' : 'c.created_at';
            $sch_sql .= " AND $sch_dateCol BETWEEN '$start' AND '$end'";
        }

        $sch_res = mysqli_query($con, $sch_sql);
        if ($sch_res && mysqli_num_rows($sch_res) > 0) {
            while ($row = mysqli_fetch_assoc($sch_res)) {
                if (!isset($jobsMap[$row['job_no']])) {
                    $jobsMap[$row['job_no']] = $row;
                }
            }
        }
    }

    // ---------------------------------------------------------
    // 3. PRE-FETCH BULK DATA (Eliminating N+1 Queries)
    // ---------------------------------------------------------
    $customer_ids_to_fetch = [];
    $driver_ids_to_fetch = [];
    $push_jobs_to_fetch = [];

    foreach ($jobsMap as $job_no => $row) {
        $uid = $row['user_id'] ?? 0;
        
        if (isset($row['c_global_type']) && $row['c_global_type'] === 'schedule') {
            $g_type = 'schedule';
        } else if (empty($uid) || $uid == 0 || !empty($row['c_user_details'])) {
            $g_type = 'website';
        } else if (strpos($job_no, 'GRC') === 0) {
            $g_type = 'customer';
        } else if (strpos($job_no, 'GRD') === 0) {
            $g_type = 'driver'; 
        } else {
            $g_type = 'website';
        }
        $jobsMap[$job_no]['calc_global_type'] = $g_type;

        if (!empty($uid) && $uid != 0 && $g_type !== 'website') {
            if ($g_type === 'driver') $driver_ids_to_fetch[$uid] = $uid;
            else $customer_ids_to_fetch[$uid] = $uid;
        }

        $currentStatus = strtolower($row['job_status'] ?? '');
        if (($currentStatus === 'accept' || $currentStatus === 'accepted' || $currentStatus === 'started' || $currentStatus === 'completed') && !empty($row['c_id'])) {
            $push_jobs_to_fetch[$row['c_id']] = $row['c_id'];
        }
    }

    $customer_data = [];
    if (!empty($customer_ids_to_fetch)) {
        $ids = implode(',', $customer_ids_to_fetch);
        $q = mysqli_query($con, "SELECT id, name, mobile FROM customer_register WHERE id IN ($ids)");
        if ($q) while($r = mysqli_fetch_assoc($q)) $customer_data[$r['id']] = $r;
    }

    $driver_data = [];
    if (!empty($driver_ids_to_fetch)) {
        $ids = implode(',', $driver_ids_to_fetch);
        $q = mysqli_query($con, "SELECT id, name, mobile FROM user_register WHERE id IN ($ids)");
        if ($q) while($r = mysqli_fetch_assoc($q)) $driver_data[$r['id']] = $r;
    }

    $push_data = [];
    if (!empty($push_jobs_to_fetch)) {
        $chunks = array_chunk($push_jobs_to_fetch, 50);
        foreach ($chunks as $chunk) {
            $likeConditions = [];
            foreach ($chunk as $jid) {
                $likeConditions[] = "req_json LIKE '%\"job_id\": $jid%' OR req_json LIKE '%\"job_id\":$jid%'";
            }
            $whereClause = implode(' OR ', $likeConditions);
            $q = mysqli_query($con, "SELECT req_json, res_json FROM push_notifications WHERE ($whereClause) ORDER BY id ASC");
            if ($q) {
                while($r = mysqli_fetch_assoc($q)) {
                    foreach ($chunk as $jid) {
                        if (strpos($r['req_json'], '"job_id": '.$jid) !== false || strpos($r['req_json'], '"job_id":'.$jid) !== false) {
                            $resData = !empty($r['res_json']) ? json_decode($r['res_json'], true) : [];
                            if (isset($resData['success_count'])) {
                                $push_data[$jid] = (int)$resData['success_count'];
                            }
                        }
                    }
                }
            }
        }
    }

    // ---------------------------------------------------------
    // 4. FINAL PROCESS AND FORMAT
    // ---------------------------------------------------------
    $finalJobs = [];
    foreach ($jobsMap as $job_no => $row) {

        $fareBreakdown = [];
        if (!empty($row['fare_breakdown']) && is_string($row['fare_breakdown'])) {
            $fareData = json_decode($row['fare_breakdown'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($fareData)) {
                $fareBreakdown = $fareData;
                $row = array_merge($row, $fareData);
            }
        }

        $baseFare   = (float) (array_key_exists('base_fare', $fareBreakdown) ? $fareBreakdown['base_fare'] : ($row['base_fare'] ?? $row['c_base_fare'] ?? 0));
        $tollFare   = (float) (array_key_exists('toll_fare', $fareBreakdown) ? $fareBreakdown['toll_fare'] : ($row['toll_fare'] ?? $row['c_toll_fare'] ?? 0));
        $tax        = (float) (array_key_exists('tax_fare', $fareBreakdown) ? $fareBreakdown['tax_fare'] : (array_key_exists('tax', $fareBreakdown) ? $fareBreakdown['tax'] : ($row['tax'] ?? $row['c_tax'] ?? 0)));
        $commission = (float) (array_key_exists('com', $fareBreakdown) ? $fareBreakdown['com'] : ($row['com'] ?? 0));
        $discount   = (float) (array_key_exists('discount', $fareBreakdown) ? $fareBreakdown['discount'] : ($row['discount'] ?? 0));
        $isDiscount = array_key_exists('isDiscount', $fareBreakdown) ? $fareBreakdown['isDiscount'] : ($row['isDiscount'] ?? '');

        $jobStatus = strtolower($row['job_status'] ?? '');

        if($jobStatus == 'created' || $jobStatus == 'bidding' || $jobStatus == 'schedule'){
            $totalFare = (float) (array_key_exists('total_fare', $fareBreakdown) ? $fareBreakdown['total_fare'] : ($row['fare'] ?? 0));
        } else {
            $totalFare  = (float) ($row['fare'] ?? 0);
        }

        $b_amt = 0;
        $paid_on = 0;
        $paid_wallet = 0;
        $pay_amt = (float) ($row['pay_amt'] ?? 0);
        $deductAmt = $row['deductAmt'] ?? null;
        $gateway = $row['gateway'] ?? '';

        if(array_key_exists('total_fare', $fareBreakdown) && (float)$fareBreakdown['total_fare'] == $pay_amt && $deductAmt == null){
            $b_amt = 0;
        }else if($deductAmt != 0 && array_key_exists('pay_to_driver', $fareBreakdown)){
            $b_amt = (float) $fareBreakdown['pay_to_driver'];
        }else{
            $b_amt = $baseFare + $tollFare;
        }

        $paid_on = (float) ($row['fare'] ?? 0);
        $credit_bonus = $isDiscount == 'yes' ? $discount : 0;

        if($gateway && $gateway == 'wallet'){
            $paid_on = 0;
            $paid_wallet = $pay_amt;
        }else if($gateway && $gateway != 'wallet'){
            $paid_on = $pay_amt;
            $paid_wallet = (float) ($row['wallet_amt'] ?? 0);
        }

        if(($row['payment_status'] ?? $row['c_payment_status'] ?? 'pending') == 'pending' && $deductAmt == null){
            $paid_on = 0;
            $paid_wallet = 0;
            $b_amt = 0;
        }

        if($jobStatus == 'created' || $jobStatus == 'bidding'){
            $b_amt = 0;
            $paid_on = 0;
            $paid_wallet = 0;
        }

        $row['actual_base']  = $baseFare + $commission;
        $row['base_fare']    = ($isDiscount == 'yes' && $discount > 0) ? ($baseFare + $commission) - $discount : ($baseFare + $commission);
        $row['govt_levy']    = $tollFare;
        $row['tax']          = $tax;
        $row['com']          = $commission;
        $row['discount']     = $discount;
        $row['total_fare']   = $totalFare;
        $row['fare']         = $totalFare;
        $row['isDiscount']   = $isDiscount;
        $row['paid_amt']     = $paid_on;
        $row['wallet_amt']   = $paid_wallet;
        $row['credit_bonus'] = $credit_bonus;
        $row['balance_amt']  = $b_amt;

        if (isset($row['c_global_type']) && $row['c_global_type'] === 'schedule') {
            $row['base_fare'] = null;
            $row['toll_fare'] = null;
            $row['tax']       = null;
        }

        $bidsDetails = !empty($row['bids_details']) ? json_decode($row['bids_details'], true) : [];
        $row['fare_breakdown'] = $fareBreakdown;
        $row['add_fare_details'] = !empty($row['add_fare_details']) ? json_decode($row['add_fare_details'], true) : null;
        $row['liked_users'] = !empty($row['liked_users']) ? json_decode($row['liked_users'], true) : [];
        $row['feedback_rating'] = $row['fb_rating'] ?? null;
        $row['feedback_review'] = $row['fb_review'] ?? null;
        $row['payment_status'] = $row['c_payment_status'] ?? 'pending';
        $row['otpVerify'] = $row['c_otpVerify'] ?? 0;

        $assignedTo = $row['assigned_to'] ?? 0;
        if (!empty($assignedTo) && $assignedTo > 0) {
            if (!isset($bidsDetails[$assignedTo])) {
                $bidsDetails[$assignedTo] = [];
            }
            $bidsDetails[$assignedTo]['status'] = 'accept';
            $bidsDetails[$assignedTo]['amount'] = isset($bidsDetails[$assignedTo]['amount']) ? $bidsDetails[$assignedTo]['amount'] : $row['fare'];
            $bidsDetails[$assignedTo]['b_name'] = !empty($row['driver_name']) ? $row['driver_name'] : 'Unknown Driver';
        }
        $row['bids_details'] = (object)$bidsDetails;

        $uid = $row['user_id'] ?? 0;
        $poster_name = 'Customer';
        $mobile = '';

        $row['global_type'] = $row['calc_global_type'];
        $row['user_details'] = null;

        if ($row['global_type'] === 'website') {
            if (!empty($row['c_user_details'])) {
                $uDetails = json_decode($row['c_user_details'], true);
                $row['user_details'] = (object)$uDetails;
                $poster_name = isset($uDetails['name']) ? $uDetails['name'] : 'Website Customer';
                $mobile = isset($uDetails['mobile']) ? $uDetails['mobile'] : '';
            } else {
                $poster_name = 'Website Customer';
            }
        } else {
            if (!empty($uid) && $uid != 0) {
                if ($row['global_type'] === 'driver' && isset($driver_data[$uid])) {
                    $poster_name = $driver_data[$uid]['name'];
                    $mobile = $driver_data[$uid]['mobile'];
                } elseif (isset($customer_data[$uid])) {
                    $poster_name = $customer_data[$uid]['name'];
                    $mobile = $customer_data[$uid]['mobile'];
                }
            }
        }

        $row['name'] = $poster_name;
        $row['poster_name'] = $poster_name;
        if (empty($row['mobile'])) $row['mobile'] = $mobile;

        $row['duration'] = !empty($row['duration']) ? $row['duration'] : ($row['c_duration'] ?? '');
        $row['distance'] = !empty($row['distance']) ? $row['distance'] : ($row['c_distance'] ?? '');

        $row['cancelled_at'] = !empty($row['cancelled_at']) ? $row['cancelled_at'] : ($row['c_cancelled_at'] ?? null);

        unset(
            $row['c_status'],
            $row['c_global_type'],
            $row['c_user_details'],
            $row['c_base_fare'],
            $row['c_toll_fare'],
            $row['c_duration'],
            $row['c_distance'],
            $row['fb_rating'],
            $row['fb_review'],
            $row['c_payment_status'],
            $row['calc_global_type'],
            $row['c_cancelled_at'],
            $row['c_otpVerify']
        );

        $row['count'] = 0;
        if (!empty($row['c_id']) && isset($push_data[$row['c_id']])) {
            $row['count'] = $push_data[$row['c_id']];
        }

        $finalJobs[] = $row;
    }

    echo json_encode([
        'type' => 1,
        'result' => array_values($finalJobs)
    ]);
    exit;
}
else if ($method == 'carpoolJobList') {
    header('Content-Type: application/json; charset=utf-8');
    
    // Set Timezone to ensure accurate time comparison
    date_default_timezone_set('Asia/Kolkata');
    $currentDateTime = date('Y-m-d H:i:s');
    
    // Filters
    $search = isset($_POST['search']) ? mysqli_real_escape_string($con, $_POST['search']) : '';
    $startDate = isset($_POST['startDate']) ? mysqli_real_escape_string($con, $_POST['startDate']) : '';
    $endDate = isset($_POST['endDate']) ? mysqli_real_escape_string($con, $_POST['endDate']) : '';
    $filterType = isset($_POST['filterType']) ? mysqli_real_escape_string($con, $_POST['filterType']) : 'created_at';
    $tabType = isset($_POST['tabType']) ? mysqli_real_escape_string($con, $_POST['tabType']) : 'current';

    $dateCol = ($filterType == 'pickup') ? 'c.pickup_date' : 'c.created_at';

    // Base query setup
    $sql = "SELECT c.*, d.name as driver_name, d.mobile as driver_mobile
            FROM `cus_job_temp` c
            LEFT JOIN `customer_register` d ON c.assigned_to = d.id OR c.user_id = d.id
            WHERE c.global_type = 'carpool' AND c.deletes = '0'";

    // --- FIX: Use PHP $currentDateTime instead of MySQL NOW() ---
    if ($tabType == 'cancelled') {
        // Cancelled: Job status is cancelled
        $sql .= " AND c.job_status = 'cancelled'";
    } else if ($tabType == 'completed') {
        // Completed: Not cancelled, Pickup Date exceeded current time, AND seats are filled
        $sql .= " AND c.job_status != 'cancelled' AND c.pickup_date < '$currentDateTime' AND c.filled_seat > 0";
    } else if ($tabType == 'expired') {
        // Expired: Not cancelled, Pickup Date exceeded current time, AND NO seats filled
        $sql .= " AND c.job_status != 'cancelled' AND c.pickup_date < '$currentDateTime' AND (c.filled_seat = 0 OR c.filled_seat IS NULL OR c.filled_seat = '')";
    } else { 
        // Current: Not cancelled AND Pickup Date has not exceeded current time
        $sql .= " AND c.job_status != 'cancelled' AND c.pickup_date >= '$currentDateTime'";
    }
    // -------------------------------------------------------------

    // Apply Search
    if (!empty($search)) {
        $sql .= " AND (c.job_no LIKE '%$search%' OR d.name LIKE '%$search%' OR d.mobile LIKE '%$search%' OR c.from_place LIKE '%$search%' OR c.to_place LIKE '%$search%')";
    }

    // Apply Date Range
    if (!empty($startDate) && !empty($endDate)) {
        $start = date('Y-m-d 00:00:00', strtotime($startDate));
        $end = date('Y-m-d 23:59:59', strtotime($endDate));
        $sql .= " AND $dateCol BETWEEN '$start' AND '$end'";
    }

    $sql .= " ORDER BY c.id DESC"; // Newest first
    $res = mysqli_query($con, $sql);
    
    $publicPools = [];
    $privatePools = [];
    $jobIds = [];
    $jobsDict = [];

    if ($res && mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $row['invitations'] = []; 
            $row['per_seat_fare'] = $row['fare']; // Force fare to represent per-seat cost
            $row['filled_seats'] = isset($row['filled_seat']) && $row['filled_seat'] != '' ? (int)$row['filled_seat'] : 0;
            $row['total_seats'] = isset($row['pass_count']) && $row['pass_count'] != '' ? (int)$row['pass_count'] : 4;
            
            $jobsDict[$row['id']] = $row;
            $jobIds[] = $row['id'];
        }
    }

    // Fetch Invitations mapping from customer_register
    if (count($jobIds) > 0) {
        $jobIdsStr = implode(',', $jobIds);
        $invSql = "SELECT i.*, 
                          cr1.name as inviter_name, cr1.mobile as inviter_mobile, 
                          cr2.name as invitee_name, cr2.mobile as invitee_mobile 
                   FROM invitations i
                   LEFT JOIN customer_register cr1 ON i.inviter_id = cr1.id
                   LEFT JOIN customer_register cr2 ON i.invitee_user_id = cr2.id
                   WHERE i.job_id IN ($jobIdsStr) ORDER BY i.created_at DESC";
                   
        $invRes = mysqli_query($con, $invSql);
        if ($invRes && mysqli_num_rows($invRes) > 0) {
            while ($inv = mysqli_fetch_assoc($invRes)) {
                $jid = $inv['job_id'];
                if(isset($jobsDict[$jid])) {
                    $jobsDict[$jid]['invitations'][] = $inv;
                }
            }
        }
    }

    // Sort into Public/Private
    foreach ($jobsDict as $job) {
        if ((int)$job['confirm_status'] === 0) {
            $privatePools[] = $job;
        } else {
            $publicPools[] = $job;
        }
    }

    echo json_encode([
        'type' => 1,
        'status' => true,
        'data' => [
            'public' => $publicPools,
            'private' => $privatePools
        ]
    ]);
    exit;
}
else if ($method === 'get_last_5_schedules') {
    $driver_id = intval($_POST['driver_id'] ?? 0);
    if ($driver_id <= 0) {
        echo json_encode(['status' => false, 'message' => 'Invalid Driver ID']);
        exit;
    }

    // Fetch the last 5 entries from the schedule_dates table
    $sql = "SELECT from_place, to_place, dates_price 
            FROM schedule_dates 
            WHERE user_id = '$driver_id' AND deletes = 0 
            ORDER BY id DESC LIMIT 5";
            
    $res = mysqli_query($con, $sql);
    $data = [];
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            // Parse the JSON dates_price field to extract the price
            $datesPrice = json_decode($row['dates_price'], true);
            $price = 0;
            if (is_array($datesPrice)) {
                // Get the first price value found in the JSON object
                $price = reset($datesPrice); 
            }
            
            $data[] = [
                'from_place' => $row['from_place'],
                'to_place'   => $row['to_place'],
                'price'      => $price
            ];
        }
    }
    
    echo json_encode(['status' => true, 'data' => $data]);
    exit;
}

else if ($method === 'auto_save_bulk_schedule') {
    $driver_id = intval($_POST['driver_id'] ?? 0);
    $routes = json_decode(stripslashes($_POST['routes']), true);
    
    if ($driver_id <= 0 || !is_array($routes)) {
        echo json_encode(["status" => false, "message" => "Invalid data provided"]);
        exit;
    }

    $now = date('Y-m-d H:i:s');
    $successCount = 0;

    foreach ($routes as $route) {
        $from = mysqli_real_escape_string($con, $route['from']);
        $to = mysqli_real_escape_string($con, $route['to']);
        $dates_price = mysqli_real_escape_string($con, json_encode($route['dates']));
        
        // Insert into schedule_dates table
        $sql = "INSERT INTO schedule_dates (user_id, from_place, to_place, dates_price, created_at, deletes) 
                VALUES ('$driver_id', '$from', '$to', '$dates_price', '$now', 0)";
        
        if (mysqli_query($con, $sql)) {
            $successCount++;
        }
    }

    echo json_encode(["status" => true, "message" => "$successCount schedules generated successfully"]);
    exit;
}
















else if($method == "ad_lead_content"){
    $ad_content = $_POST['template'];
    // var_dump($ad_content);die;

      try {

        $historySql = mysqli_query($con, "
            SELECT
                COUNT(gl.id) AS lead_count,

                COUNT(ur.id) AS register_count,
        
                COUNT(CASE 
                    WHEN ur.doc_verify = 1 THEN 1 
                END) AS doc_verified_count,
        
                COUNT(CASE 
                    WHEN ur.vehicle_verify = 2 THEN 1 
                END) AS vehicle_verified_count,
        
                COUNT(CASE 
                    WHEN kd.type = 'Driver' AND ur.doc_verify = 1 AND ur.vehicle_verify = 2 THEN 1 
                END) AS driver_count,
        
                COUNT(CASE 
                    WHEN kd.type = 'Owner' AND ur.doc_verify = 1 THEN 1 
                END) AS owner_count
        
            FROM goride_ad_leads gl
        
            LEFT JOIN user_register ur 
                ON ur.mobile = gl.phone
                AND ur.deletes = 0
        
            LEFT JOIN kyc_details kd 
                ON kd.user_id = ur.id
        
            WHERE gl.campaign_name = '$ad_content'
        ");

        $template_content = mysqli_fetch_assoc($historySql);

        $result['type'] = 1;
        $result['result'] = $template_content;
        


    } catch (Exception $e) {

        $result['type'] = 0;
        $result['result'] = $e->getMessage();
    }

     echo json_encode($result);

}else if($method === "credits_count"){

        $url = "http://smsresell.cwd.co.in/api/checkbalance.php?user=goride&pass=Goride@321";
        $urls = "http://promodnd.cwd.co.in/api/creditapi?key=5cadcbbd9c385e6a16526b8226abff1e&route=2";

        $response = file_get_contents($url);
        $result = file_get_contents($urls);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $response,
            'result' => $result
        ]);
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

    $mobileUsers = [];
    foreach ($dates as $date) {
        $mobileUsers[$date] = 0;
    }

    $mobileQuery = "
    WITH RECURSIVE date_range AS (
        SELECT DATE('$startDate') AS date
        UNION ALL
        SELECT DATE_ADD(date, INTERVAL 1 DAY)
        FROM date_range
        WHERE date < DATE('$endDate')
    )
    SELECT 
        dr.date,
        COALESCE(COUNT(ur.id), 0) AS count
    FROM 
        date_range dr
    LEFT JOIN user_register ur 
        ON DATE(ur.created_at) = dr.date
        AND ur.deviceType IN ('MOBILE', 'TABLET')
        AND ur.fcm_token IS NOT NULL
        AND ur.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'
    GROUP BY 
        dr.date
    ORDER BY 
        dr.date
    ";
    

    $mobileResult = mysqli_query($con, $mobileQuery);

    while ($row = mysqli_fetch_assoc($mobileResult)) {
        $date = $row['date'];
        $mobileUsers[$date] = $row['count'];
        $totalCount += (int)$row['count'];
    }


    $desktopUsers = [];
    foreach ($dates as $date) {
        $desktopUsers[$date] = 0;
    }
    
    $desktopQuery = "
    WITH RECURSIVE date_range AS (
        SELECT DATE('$startDate') AS date
        UNION ALL
        SELECT DATE_ADD(date, INTERVAL 1 DAY)
        FROM date_range
        WHERE date < DATE('$endDate')
    )
    SELECT 
        dr.date,
        COALESCE(COUNT(ur.id), 0) AS count
    FROM 
        date_range dr
    LEFT JOIN user_register ur 
        ON DATE(ur.created_at) = dr.date
        AND ur.deviceType = 'DESKTOP'
        AND ur.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'
    GROUP BY 
        dr.date
    ORDER BY 
        dr.date
    ";
    

    $desktopResult = mysqli_query($con, $desktopQuery);

    while ($row = mysqli_fetch_assoc($desktopResult)) {
        $date = $row['date'];
        $desktopUsers[$date] = $row['count'];
        $totalCount += (int)$row['count'];
    }

    
    $result['leads'] = $leads;
    $result['mobile'] = $mobileUsers;
    $result['desktop'] = $desktopUsers;
    $result['dates'] = $dates;
    $result['signups'] = $signups;
    $result['logins'] = $logins;
    $result['loginsOTP'] = $loginsOTP;
    $result['total'] = $totalCount;
    resutGJHIP:
    echo json_encode($result);
}

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
    $whereClause[] = "kd.deletes = '0' ";
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
    $whereClause[] = "kd.deletes = '0' ";
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
              AND kd.type = 'Driver'
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
              AND kd.type = 'Driver'
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
              AND kd.type = 'Driver'
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
    // $whereClause[] = "op.deletes = '0'";
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

else if ($method === 'ownerReport_List') {

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
    $whereClause[] = "kd.deletes = '0'  ";
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
              AND kd.type = 'Owner'
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
              AND kd.type = 'Owner'
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
              AND kd.type = 'Owner'
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
} else if($method === 'cab_count'){

    // Fetch all cab types dynamically and count matches inside the JSON column
    $c_query = "
        SELECT 
            c.name AS cab_name, 
            COUNT(u.id) AS user_count
        FROM cab_types c
        LEFT JOIN user_register u 
            ON JSON_UNQUOTE(JSON_EXTRACT(u.vehicle_details, '$.type')) = c.name 
            AND u.deletes = '0'
        GROUP BY c.id, c.name
        ORDER BY c.id ASC
    ";

    $result_data = mysqli_query($con, $c_query);

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
    exit;

}else if($method === 'seat_count'){

  $c_query = "

    (
        SELECT 
            'seat_count' AS type,
            COALESCE(
                JSON_UNQUOTE(JSON_EXTRACT(oc.req_response, '$.vehicle_details.seat_capacity')),
                oc.seater
            ) AS seat_capacity,
            COUNT(DISTINCT oc.user_id) AS user_count
        FROM ocr_request oc
        JOIN user_register ur 
            ON ur.id = oc.user_id
        AND ur.vehicle_verify = 2
        AND ur.vehicle_details IS NOT NULL
        WHERE oc.doc_type = 'RC'
        AND oc.req_response IS NOT NULL
        AND ur.deletes = '0'
        GROUP BY seat_capacity

    )
    

  ";
    $result_data = mysqli_query($con, $c_query);

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
else if($method === 'sts_count'){

  $c_query = "

    (
        SELECT 
            'selfie_count' AS type,
            COUNT(*) AS total
        FROM kyc_details kd
        JOIN user_register cu ON cu.id = kd.user_id
        WHERE kd.o_status = '0'
          AND kd.deletes = '0'
          AND cu.dialCode = '91'
          AND cu.deletes = '0'

    )
    UNION ALL
    (
        SELECT 
            'aadhar_count' AS type,
            COUNT(*) AS total
        FROM kyc_details kd
         JOIN user_register cu ON cu.id = kd.user_id
        WHERE kd.o_status = '1'
          AND kd.deletes = '0'
          AND cu.dialCode = '91'
          AND cu.deletes = '0'
    )
    UNION ALL
    (
      SELECT 
            'dl_count' AS type,
            COUNT(*) AS total
        FROM kyc_details kd
         JOIN user_register cu ON cu.id = kd.user_id
        WHERE kd.o_status = '2'
          AND kd.type IN ('Driver') 
          AND kd.deletes = '0'
          AND cu.dialCode = '91'
          AND cu.deletes = '0'
    )
    UNION ALL
    (
         SELECT 
            'gst_count' AS type,
            COUNT(*) AS total
        FROM kyc_details kd
        JOIN user_register cu ON cu.id = kd.user_id
        WHERE kd.o_status = '2'
          AND kd.type IN ('Owner') 
          AND kd.deletes = '0'
          AND cu.dialCode = '91'
          AND cu.deletes = '0'
    )
    UNION ALL
    (
        SELECT 
            'updated_count' AS type,
            COUNT(DISTINCT ur.id) AS total
        FROM kyc_details kd
        JOIN user_register ur 
            ON ur.id = kd.user_id
        AND ur.vehicle_verify = 2
        AND ur.vehicle_details IS NOT NULL
        AND kd.cab_type IS NOT NULL
        AND ur.deletes = '0'
    )
    UNION ALL
    (
        SELECT 
            'updated_count' AS type,
            COUNT(DISTINCT ur.id) AS total
        FROM kyc_details kd
        JOIN user_register ur 
            ON ur.id = kd.user_id
        AND ur.vehicle_verify = 2
        AND ur.vehicle_details IS NOT NULL
        AND kd.cab_type IS  NULL
        AND ur.deletes = '0'
    )
    
    
  ";
    $result_data = mysqli_query($con, $c_query);

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
            WHERE cu.vehicle_verify > 0
              AND LOWER(vehicle_details->>'$.vehicle_review_message')
                   LIKE '%wait for admin approval%'
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
              AND cu.dialCode = '91'
              AND cu.deletes = '0'
              AND kd.deletes = '0'
        )
        UNION ALL
        (
            SELECT 
                'error_log' AS type,
                COUNT(*) AS total
            FROM api_error_log err
            WHERE err.created_at BETWEEN CURDATE() AND NOW()
            AND err.deletes = '0' AND err.status = 0
            AND err.url NOT LIKE 'https://www.goride.run/api/user'
            AND err.url NOT LIKE 'https://www.goride.run/api/getChat-con'

        )
        UNION ALL
        (
            SELECT 
                'v_driver' AS type,
                COUNT(*) AS total
            FROM user_register ur JOIN kyc_details kd ON kd.user_id = ur.id
            WHERE ur.deletes = '0' AND ur.doc_verify = 1 AND ur.vehicle_verify = 2 AND kd.type = 'Driver' AND kd.o_status = 3 AND ur.vehicle_details IS NOT NULL

        )
        
        UNION ALL
        (
            SELECT 
                'v_owner' AS type,
                COUNT(*) AS total
            FROM user_register ur JOIN kyc_details kd ON kd.user_id = ur.id
            WHERE ur.deletes = '0' AND ur.doc_verify = 1 AND kd.type = 'Owner' AND kd.o_status = 3

        )
        
        UNION ALL
        (
            SELECT 
                'k_v_driver' AS type,
                COUNT(*) AS total
            FROM user_register ur JOIN kyc_details kd ON kd.user_id = ur.id
            WHERE ur.deletes = '0' AND ur.doc_verify = 1 AND ur.vehicle_verify != 2 AND kd.type = 'Driver' AND kd.o_status = 3

        )
        
        UNION ALL
        (
            SELECT 
                'v_v_driver' AS type,
                COUNT(*) AS total
            FROM user_register ur JOIN kyc_details kd ON kd.user_id = ur.id
            WHERE ur.deletes = '0' AND ur.vehicle_verify = 2 AND kd.type = 'Driver'

        )
        
        UNION ALL
        (
            SELECT 
                'sms_count' AS type,
                COUNT(*) AS total
            FROM smslog sms
            WHERE sms.datetime BETWEEN CURDATE() AND NOW()
            AND sms.gateway = 'smsportal' 
        )
        
         UNION ALL
        (
           SELECT 
                'shi_count' AS type,
                COUNT(*) AS total
            FROM smslog sms
            WHERE sms.datetime BETWEEN CURDATE() AND NOW()
            AND sms.gateway = 'shiwhatsapp' 

        )
        UNION ALL
        (
           SELECT 
                'wh_count' AS type,
                COUNT(*) AS total
            FROM whatsapp_bulk_message wh
            WHERE wh.created_at BETWEEN CURDATE() AND NOW()
            AND wh.status = 'success' 

        )
        
        
        UNION ALL
        (
            SELECT 
                'consent_count' AS type,
                COUNT(ud.id) AS total
            FROM user_register cu
            JOIN kyc_details kd 
                ON cu.id = kd.user_id
            LEFT JOIN users_docs ud 
                ON cu.id = ud.user_id
            WHERE cu.deletes = '0'
            AND kd.deletes = '0'

        ) 
        
        UNION ALL
        (
             
             SELECT 
                'reject_count' AS type,
                COUNT(cu.id) AS total
            FROM user_register cu
            LEFT JOIN kyc_details kd 
                ON cu.id = kd.user_id
            WHERE cu.vehicle_verify > 0
            AND LOWER(vehicle_details->>'$.vehicle_review_message')
                  NOT LIKE '%vehicle details verified%'
              AND LOWER(vehicle_details->>'$.vehicle_review_message')
                  NOT LIKE '%wait for admin approval%'
            AND cu.deletes = '0'
            AND kd.deletes = '0'
        
        )
        
        UNION ALL
        (
             
            SELECT 
                'notverifi_count' AS type,
                COUNT(cu.id) AS total
            FROM user_register cu
            LEFT JOIN kyc_details kd 
                ON cu.id = kd.user_id
            WHERE cu.vehicle_verify > 0
              AND LOWER(vehicle_details->>'$.vehicle_review_message')
                   LIKE '%wait for admin approval%'
            AND cu.deletes = '0'
            AND kd.deletes = '0'
        
        )
        
        UNION ALL
        (
        SELECT
            'kyc_missing_count' AS type,
            COUNT(*) AS total
        FROM user_register ur
        WHERE ur.deletes = '0'
        AND ur.dialCode = '91'
          AND NOT EXISTS (
              SELECT 1
              FROM kyc_details kd
              WHERE kd.user_id = ur.id
                AND kd.deletes = '0'
          )
        )
       
        UNION ALL
        (
            SELECT 
                'lead_web' AS type,
                COUNT(*) AS total
            FROM cus_job_temp cs
            WHERE cs.deletes = '0' AND cs.user_id = '0' AND cs.user_details IS NOT NULL AND cs.job_status IN ('created', 'bidding')

        )
        
        UNION ALL
        (
            SELECT 
                'post_web' AS type,
                COUNT(*) AS total
            FROM cus_job_temp cs
            WHERE cs.deletes = '0' AND cs.user_id != 0 AND cs.user_details IS NOT NULL AND cs.job_status IN ('created', 'bidding')

        )
         
    ";

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