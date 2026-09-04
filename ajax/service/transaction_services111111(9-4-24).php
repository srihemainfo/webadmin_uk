<?php
include '../../include/shi-config.php';
include '../../include/functions.php';
include '../../include/Crypto.php';

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";
$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";
$role = $_REQUEST['role'] ?? '';

if ($type == 'agent') {
    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($role) AND" : "";
} else {
    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$role' AND" : "";
}

// Initialize other necessary variables and headers
$headers = apache_request_headers();
$result = array();
$post_csrf = $headers['X-Csrf-Token'] ?? '';

if ($method == "total_earnings") {
    $result = [];
    $earnings = select_top_name($con, "user_register", "t_earning", "`id`='$_SESSION[memid]' and `dectletes`='0'", "t_earning", "");
    if ($earnings != '') {
        $result['type'] = 1;
        $result['result'] = floatval($earnings);
    } else {
        $result['type'] = 1;
        $result['result'] = 'Could not get amount!';
    }
    echo json_encode($result);
} elseif ($method == "collect_earning") {
    $result = [];
    $roll_id = select_top_name($rcon, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC ", "roll_id", "");
    $n_roll_id = intval($roll_id) + 1;
    $now = date("Y-m-d H:i:s");
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

    $output = '';
    $total_Aed_Array = [];
    
    /////////// Total Ticket ///////////////
    if ($roll_id == 1) {
        $oticket = mysqli_query($con, "SELECT SUM(I.netTotal) AS totalAmount FROM `invoice` AS I JOIN ndticket ON ndticket.id = I.ticketId WHERE I.ticketId = ndticket.id AND I.deletes ='0'AND DATE(I.createdon) = CURDATE()");
        $row1 = mysqli_fetch_array($oticket);
        $totalAmount = $row1['totalAmount'];

        $productAmtArray = []; // This array will store product amount (AED value) and its total sum

        $sql = mysqli_query($con, "SELECT P.id, ROUND(P.rate) AS 'productAmt', IFNULL(ROUND(I.totalAmt), 0) AS 'totalAmt'
            FROM `product` AS P
            LEFT JOIN (
                SELECT I.product_id, SUM(I.netTotal) AS 'totalAmt'
                FROM `invoice` AS I
                WHERE I.deletes = '0'
                AND DATE(I.createdon) = CURDATE()
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

        $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3" style="display: inline-block;"><div class="card overflow-hidden"><div class="card-body"><div class="d-flex"><div class="mt-2"><h6 class="">Total Ticket</h6><h2 class="mb-0 number-font" id="totalonline"  style="text-align: left;">AED ';
     
         $output .= array_sum($productAmtArray);
        $output .= '</h2>';

        foreach ($productAmtArray as $productAmt => $totalAmt) {
            $output .= '<p class="aed-agent"  style="text-align: left;"> AED ' . $productAmt . ' - ' . $totalAmt . ' </p>';
        }

        $output .= '</div></div></div></div></div>';
    }

    /////////// Online Ticket ///////////////
    if ($roll_id == 1) {
        $oticket = mysqli_query($con, "SELECT SUM(I.netTotal) AS totalAmount FROM `invoice` AS I JOIN ndticket ON ndticket.id = I.ticketId WHERE I.ticketId = ndticket.id AND I.agent_id='0'AND I.deletes ='0' AND DATE(I.createdon)");
        $row1 = mysqli_fetch_array($oticket);
        $totalAmount = $row1['totalAmount'];

        $productAmtArray = []; // This array will store product amount (AED value) and its total sum

        $sql = mysqli_query($con, "SELECT P.id, ROUND(P.rate) AS 'productAmt', IFNULL(ROUND(I.totalAmt), 0) AS 'totalAmt'
            FROM `product` AS P
            LEFT JOIN (
                SELECT I.product_id, SUM(I.netTotal) AS 'totalAmt'
                FROM `invoice` AS I
                WHERE I.deletes = '0' AND I.agent_id='0'AND DATE(I.createdon) = CURDATE()
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

        $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3" style="display: inline-block;"><div class="card overflow-hidden"><div class="card-body"><div class="d-flex"><div class="mt-2"><h6 class="">Online Ticket</h6><h2 class="mb-0 number-font" id="totalonline"  style="text-align: left;">AED ';
        $output .= array_sum($productAmtArray); // Display total amount for Online Ticket section
        $output .= '</h2>';

        foreach ($productAmtArray as $productAmt => $totalAmt) {
            $output .= '<p class="aed-agent"  style="text-align: left;">AED ' . $productAmt . ' - ' . $totalAmt . ' </p>';
        }

        $output .= '</div></div></div></div></div>';
    }

    //////////// Agent Ticket //////////////
    $productAmtArray = []; // Reset the array for Agent Ticket section

    $oticket = mysqli_query($con, "SELECT SUM(I.netTotal) AS totalAmount FROM `invoice` AS I JOIN ndticket ON ndticket.id = I.ticketId WHERE I.agent_id != '0' AND I.ticketId = ndticket.id AND I.deletes ='0';");
    $row1 = mysqli_fetch_array($oticket);
    $totalAmount = $row1['totalAmount'];

    $sql = mysqli_query($con, "SELECT P.id, ROUND(P.rate) AS 'productAmt', IFNULL(ROUND(I.totalAmt),0) AS 'totalAmt'  FROM `product` AS P LEFT JOIN ( SELECT I.product_id, SUM(I.netTotal) AS 'totalAmt' FROM `invoice` AS I WHERE I.deletes = '0' AND I.agent_id !='0' GROUP BY I.product_id ) AS I ON P.rate = I.product_id WHERE P.deletes = '0';");

    if (mysqli_num_rows($sql) > 0) {
        while ($row = mysqli_fetch_assoc($sql)) {
            $productAmt = round($row['productAmt']); // Extract AED value
            $totalAmt = $row['totalAmt'];

            // Check if productAmt already exists in the array. If so, skip adding it again.
            if (!isset($productAmtArray[$productAmt])) {
                $productAmtArray[$productAmt] = $totalAmt;
            }
        }
    }

    $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3"><div class="card overflow-hidden"><div class="card-body"><div class="d-flex"><div class="mt-2"><h6 class="">Agent Ticket</h6><h2 class="mb-0 number-font" id="totalagent"  style="text-align: left;">AED ';
    $output .= array_sum($productAmtArray); // Display total amount for Agent Ticket section
    $output .= '</h2>';

    foreach ($productAmtArray as $productAmt => $totalAmt) {
        $output .= '<p class="aed-agent"  style="text-align: left;">AED ' . $productAmt . ' - ' . $totalAmt . ' </p>';
    }

    $output .= '</div></div></div></div></div>';

    ///// Coupon Ticket /////
    $oticket = mysqli_query($con, "SELECT SUM(I.netTotal) AS totalAmount FROM `invoice` AS I JOIN ndticket ON ndticket.id = I.ticketId WHERE I.agent_id != '0' AND I.ticketId = ndticket.id;");
    $row1 = mysqli_fetch_array($oticket);
    $totalAmount = $row1['totalAmount'];
    
    $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3"><div class="card overflow-hidden"><div class="card-body"><div class="d-flex"><div class="mt-2"><h6 class="">Coupon Ticket</h6><h2 class="mb-0 number-font" id="totalonline"  style="text-align: left;">AED ' . intval($totalAmount) . '</h2>'; // This line displays total amount
    
    $sql = mysqli_query($con, "SELECT P.id, ROUND(P.rate) AS 'productAmt',IFNULL(ROUND(I.totalAmt),0) AS 'totalAmt'  FROM `product` AS P LEFT JOIN ( SELECT I.product_id, SUM(I.netTotal) AS 'totalAmt' FROM `invoice` AS I WHERE I.deletes = '0' AND I.agent_id !='0' GROUP BY I.product_id ) AS I ON P.rate = I.product_id WHERE P.deletes = '0';");
    
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
    ////////////////// Deleted Ticket Count ///////////////////
    $oticket = mysqli_query($con, "SELECT COUNT(*) AS deletes_count FROM `ndticket` WHERE deletes = '1';");
    $row1 = mysqli_fetch_array($oticket);
    $totalAmount = $row1['deletes_count'];
    $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3"><div class="card overflow-hidden"><div class="card-body"><div class="d-flex"><div class="mt-2"><h6 class="">Deleted Account Request</h6><h2 class="mb-0 number-font" id="totalonline">' . intval($totalAmount) . '</h2>';
    $output .= '</div></div></div></div></div>';
    
     //////////////////Whatsapp message Count ///////////////////
    $whatsapp_send = mysqli_query($con, "SELECT COUNT(*) AS whatsapp_send
        FROM `smslog`
        WHERE gateway = 'doubleTick' AND DATE(datetime) = CURDATE()");
    $row1 = mysqli_fetch_array($whatsapp_send);
    $whatsapp_count = $row1['whatsapp_send'];
    $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3"><div class="card overflow-hidden"><div class="card-body"><div class="d-flex"><div class="mt-2"><h6 class="">Whatsapp message Count</h6><h2 class="mb-0 number-font" id="totalonline">' . intval($whatsapp_count) . '</h2>';
    $output .= '</div></div></div></div></div>';
    
    //////////////////SMS message Count ///////////////////
    $sms_send = mysqli_query($con, "SELECT COUNT(*) AS sms_send
        FROM `smslog`
        WHERE gateway = 'brandmaster' AND DATE(datetime) = CURDATE()");
    $row1 = mysqli_fetch_array($sms_send);
    $sms_count = $row1['sms_send'];
    $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3"><div class="card overflow-hidden"><div class="card-body"><div class="d-flex"><div class="mt-2"><h6 class="">SMS message Count</h6><h2 class="mb-0 number-font" id="totalonline">' . intval($sms_count) . '</h2>';
    $output .= '</div></div></div></div></div>';
    
    
    //////////////////Send Email  Count ///////////////////
    $email_send = mysqli_query($con, "SELECT COUNT(*) AS email_send
        FROM `emaillog`
        WHERE DATE(datetime) = CURDATE();");
    $row1 = mysqli_fetch_array($email_send);
    $sms_count = $row1['email_send'];
    $output .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xl-3"><div class="card overflow-hidden"><div class="card-body"><div class="d-flex"><div class="mt-2"><h6 class="">Send Email Count</h6><h2 class="mb-0 number-font" id="totalonline">' . intval($sms_count) . '</h2>';
    $output .= '</div></div></div></div></div>';
    
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