<?php





include '../../include/shi-config.php';
include '../../include/functions.php';

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";
$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";

$role = $_REQUEST['role'] ?? "";

if ($type == 'agent') {
    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($role) AND" : "";
} else {
    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$role' AND" : "";
}



$headers = apache_request_headers();
$result = array();
$post_csrf = $headers['X-Csrf-Token'] ?? '';






if ($method == "getBonusReport") {

    $result = [];
    $formdate = BlockSQLInjectionforreportrange($_POST["formdate"]);
    $todate = BlockSQLInjectionforreportrange($_POST["todate"]);
    // $formdate = $_POST['formdate'];

    // $todate = $_POST['todate'];

    if ($formdate != '' && $todate != '') {

        $datefilter = "c.`createdon` BETWEEN '$formdate' AND '$todate' AND";
    } else {

        $datefilter = "";
    }

    $reCon = '';
    $reward = BlockSQLInjectionforreportrange($_POST["reward"]);

    // $reward = $_POST['reward'];

    if ($reward != '') {
        $reCon = "c.`reward_type` LIKE '$reward' AND";
    }



    // $cb_transactions = select_query($con, "cb_transactions", "", "$datefilter $reCon `point_type` = 'BONUS' AND `status` = '0' AND `deletes`= '0'", "", "");

    $cb_transactions = mysqli_query($con, "select c.*, u.name, u.lname from cb_transactions as c
    LEFT JOIN user_register AS u ON u.id = c.userid
    where $datefilter $reCon c.`point_type` = 'BONUS' AND c.`status` = '0' AND c.`deletes`= '0';");

    if (mysqli_num_rows($cb_transactions) > 0) {



        // foreach ($cb_transactions['result'] as $key => $value) {
        while ($value = mysqli_fetch_assoc($cb_transactions)) {

            $result[] = [

                'id' =>  $value['id'],

                'cardno' => $value['card_no'],

                'name'  => $value['name'] . ' ' . (isset($value['lname']) ? $value['lname'] : ''),

                'opbal'  => $value['opening_balance'],

                'total' => $value['total'],

                'clobal' => $value['closeing_balance'],

                'mobile' => $value['umobile'],

                'email' => $value['uemail'],

                'transtype'   => $value['transaction_type'],

                'rewardtype'  => $value['reward_type'],

                'ip'  => $value['ip'],

                'date' => $value['createdon'],



            ];
        }
    }







    echo json_encode($result);
} else if ($method == "getCashReport") {

    $result = [];
    $formdate = BlockSQLInjectionforreportrange($_POST["formdate"]);
    $todate = BlockSQLInjectionforreportrange($_POST["todate"]);
    // $formdate = $_POST['formdate'];

    // $todate = $_POST['todate'];

    if ($formdate != '' && $todate != '') {
        $datefilter = "`createdon` BETWEEN '$formdate' AND '$todate' AND";
    } else {
        $datefilter = "";
    }

    $reCon = '';
    $reward = BlockSQLInjectionforreportrange($_POST["reward"]);

    // $reward = $_POST['reward'];

    if ($reward != '') {
        $reCon = "`reward_type` LIKE '$reward' AND";
    }

    $cb_transactions = select_query($con, "cb_transactions", "", "$datefilter $reCon  `point_type` = 'CASH' AND `status` = '0' AND `deletes`= '0'", "", "");

    if ($cb_transactions['nr'] > 0) {



        foreach ($cb_transactions['result'] as $key => $value) {

            $result[] = [

                'id' => $value['id'],

                'name'  => $value['uname'],

                'opbal'  => $value['opening_balance'],

                'total' => $value['total'],

                'clobal' => $value['closeing_balance'],

                'mobile' => $value['umobile'],

                'email' => $value['uemail'],

                'transtype'   => $value['transaction_type'],

                'rewardtype'  => $value['reward_type'],

                'ip'  => $value['ip'],

                'date' => $value['createdon'],

            ];
        }
    }



    echo json_encode($result);
} else if ($method == "list_bonus_ticket") {



    $result = [];



    $contype = '';



    $type = 'BP';

    $da = '';

    $now = date('Y-m-d');
    $formdate = BlockSQLInjection($_POST["formdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);

    // $formdate = $_POST['formdate'];

    // $todate = $_POST['todate'];
    $fieldname = $_POST['fieldname'];

    if ($formdate != '' && $todate != '') {



        $fd = date("Y-m-d", strtotime($formdate));

        $td = date("Y-m-d", strtotime($todate));

        $contype1 = "AND bpticket.purchase_datetime BETWEEN '$fd 00:00:00' AND '$td 23:59:59' ";
    } else {



        $contype1 = "AND bpticket.purchase_datetime LIKE '%$now%'";
    }

    if ($fieldname != '' && $fieldname != undefined) {
        $contype1 = "AND (bpticket.`ticket_no` ='" . $fieldname . "' OR bpticket.`ticket_no` LIKE '%" . $fieldname . "%' OR bpticket.`ticket_no` LIKE '%" . $fieldname . "%' OR bpticket.`ticket_no` LIKE '%" . $fieldname . "%') ";
    }





    $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'", "roll_id", "");







    $wtticket = mysqli_query($con, "SELECT bpticket.id AS 'ticketid', user_register.name AS 'name', user_register.lname AS 'lname',  user_register.mobile AS 'mobile', user_register.email AS 'email', ticket_lines.my3number AS 'my3number', ticket_lines.raffle_id AS 'raffleid', product.rate AS 'productamt', bpticket.invoice_no, bpticket.purchase_datetime AS 'purchasedatetime', bpticket.transaction_id AS 'transactionid' , bpticket.ticket_no AS 'ticket_no' FROM ticket_lines INNER JOIN `bpticket` ON bpticket.id = ticket_lines.ticket_id INNER JOIN user_register ON bpticket.user_id = user_register.id INNER JOIN product ON ticket_lines.product_id = product.id WHERE bpticket.deletes = 0 AND ticket_lines.type = '$type'  $contype1");

    while ($value = mysqli_fetch_assoc($wtticket)) {

        $transaction_id = $value['transactionid'];

        $action = '';

        $action .= '<div class="g-2">';

        if ($value['invoice_no'] != '' && $value['invoice_no'] != 0) {

            // $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' . $transaction_id . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span style="font-size: 18px;" class="fa fa-file-text-o"></span></a>';

        }



        $action .= '<a target="_blank" href="' . $baseurl . 'ticket-view/' . $transaction_id . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span style="font-size: 18px;" class="fa fa-files-o">&nbsp;Ticket</span></a>';

        if ($roll_id == 1) {

            // $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size: 18px;" class="fa fa-paper-plane" onclick="sendemailtopurchase(' . "'$transaction_id'" . ', ' . "'WT'" . ', ' . "'wticket'" . ')">&nbsp;Email</span></a>';

            // $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8; font-size: 18px;" onclick="sendsmstopurchase(' . "'$transaction_id'" . ', ' . "'WT'" . ', ' . "'wticket'" . ')">&nbsp;SMS</span></a>';

            $action .= '<button onclick="deletebpticket(' . "'$transaction_id'" . ')" class="deleteBTN"><span class="fe fe-trash-2 fs-14"></span></button>';
        }



        $action .= '</div>';

        $r = mysqli_query($con, "SELECT couponcode.c_code FROM `couponcode_history` INNER JOIN couponcode ON couponcode.id = couponcode_history.couponcodeID WHERE couponcode_history.deletes = '0' AND couponcode_history.ticket_id = '$value[ticketid]'");

        $ro = mysqli_fetch_assoc($r);



        $result[] = ["codeC" => $ro['c_code'], "action" => $action, "transaction_id" => $value['transactionid'], "proamt" => $value['productamt'], "RaffleID" => $value['raffleid'], "My3Numbers" => $value['my3number'], "email" => $value['email'], "mobile" => $value['mobile'], "cusname" => $value['name'] . ' ' . $value['lname'], "ticketno" => $value['ticket_no'], "purdate" => '<span style="display:none;">' . strtotime($value['purchasedatetime']) . '</span>' . date("d-M-Y g:i a", strtotime($value['purchasedatetime'])), 'date' => $value['purchasedatetime']];
    }





    echo json_encode($result);
} else if ($method == "list_cash_ticket") {

    $userid = $_SESSION['memid'];

    $result = [];



    $contype = $contype2 = '';



    $type = 'CP';

    $da = '';

    $now = date('Y-m-d');
    $formdate = BlockSQLInjection($_POST["formdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);
    // $formdate = $_POST['formdate'];

    // $todate = $_POST['todate'];
    $fieldname = $_POST['fieldname'];
    if ($formdate != '' && $todate != '') {



        $fd = date("Y-m-d", strtotime($formdate));

        $td = date("Y-m-d", strtotime($todate));
        $contype1 = "AND cpticket.createdon BETWEEN '$fd 00:00:00' AND '$td 23:59:59' ";
        // $contype1 = "AND cpticket.purchase_datetime BETWEEN '$fd 00:00:00' AND '$td 23:59:59' ";
    } else {


        $contype1 = "AND cpticket.createdon LIKE '%$now%'";
        // $contype1 = "AND cpticket.purchase_datetime LIKE '%$now%'";
    }

    if ($fieldname != '' && $fieldname != undefined) {
        $contype1 = "AND (cpticket.`ticket_no` ='" . $fieldname . "' OR cpticket.`ticket_no` LIKE '%" . $fieldname . "%' OR cpticket.`ticket_no` LIKE '%" . $fieldname . "%' OR cpticket.`ticket_no` LIKE '%" . $fieldname . "%') ";
    }






    $roll_id = select_top_name($rcon, "user_register", "roll_id", "`id`='$userid' and `deletes`='0'", "roll_id", "");





    $wtticket = mysqli_query($rcon, "SELECT cpticket.id AS 'ticketid', user_register.name AS 'name',user_register.lname AS 'lname', user_register.mobile AS 'mobile', user_register.email AS 'email', ticket_lines.my3number AS 'my3number', ticket_lines.raffle_id AS 'raffleid', product.rate AS 'productamt', cpticket.invoice_no, cpticket.purchase_datetime AS 'purchasedatetime', cpticket.transaction_id AS 'transactionid' , cpticket.ticket_no AS 'ticket_no' FROM ticket_lines INNER JOIN `cpticket` ON cpticket.id = ticket_lines.ticket_id INNER JOIN user_register ON cpticket.user_id = user_register.id INNER JOIN product ON ticket_lines.product_id = product.id WHERE cpticket.deletes = 0 AND ticket_lines.type = '$type'  $contype1 $contype2");

    while ($value = mysqli_fetch_assoc($wtticket)) {

        $transaction_id = $value['transactionid'];

        $action = '';

        $action .= '<div class="g-2">';

        if ($value['invoice_no'] != '' && $value['invoice_no'] != 0) {

            $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' . $transaction_id . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span style="font-size: 18px;" class="fa fa-file-text-o">&nbsp;Invoice</span></a>';
        }



        $action .= '<a target="_blank" href="' . $baseurl . 'ticket-view/' . $transaction_id . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span style="font-size: 18px;" class="fa fa-files-o">&nbsp;Ticket</span></a>';

        if ($roll_id == 1) {

            // $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size: 18px;" class="fa fa-paper-plane" onclick="sendemailtopurchase(' . "'$transaction_id'" . ', ' . "'WT'" . ', ' . "'wticket'" . ')">&nbsp;Email</span></a>';

            // $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8; font-size: 18px;" onclick="sendsmstopurchase(' . "'$transaction_id'" . ', ' . "'WT'" . ', ' . "'wticket'" . ')">&nbsp;SMS</span></a>';

            $action .= '<button onclick="deletecpticket(' . "'$transaction_id'" . ')" class="deleteBTN"><span class="fe fe-trash-2 fs-14"></span></button>';
        }



        $action .= '</div>';

        $r = mysqli_query($rcon, "SELECT couponcode.c_code FROM `couponcode_history` INNER JOIN couponcode ON couponcode.id = couponcode_history.couponcodeID WHERE couponcode_history.deletes = '0' AND couponcode_history.ticket_id = '$value[ticketid]'");

        $ro = mysqli_fetch_assoc($r);



        $result[] = array("codeC" => $ro['c_code'], "action" => $action, "transaction_id" => $value['transactionid'], "proamt" => $value['productamt'], "RaffleID" => $value['raffleid'], "My3Numbers" => $value['my3number'], "email" => $value['email'], "mobile" => $value['mobile'], "cusname" => $value['name'] . ' ' . $value['lname'], "ticketno" => $value['ticket_no'], "purdate" => '<span style="display:none;">' . strtotime($value['purchasedatetime']) . '</span>' . date("d-M-Y g:i a", strtotime($value['purchasedatetime'])));
    }





    echo json_encode($result);
} else if ($method == "deletedp_Ticket") {

    $result = [];



    $transid = $_POST['transid'];

    $message = $_POST['message'];



    if ($_SESSION['memid'] == '') {

        $result["type"] = "0";

        $result["result"] = "Kindly Login First & Try Again";

        goto fiVP;
    }



    if ($transid == '') {

        $result["type"] = "0";

        $result["result"] = "Kindly refresh the page and try again";

        goto fiVP;
    }



    if ($message == '') {

        $result["type"] = "0";

        $result["result"] = "Kinldy fill Deleted Reason";

        goto fiVP;
    }







    $bpticket = select_query($con, "bpticket", "", "`transaction_id`='$transid' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");



    if ($bpticket['nr'] > 0) {

        $invoice_no = $bpticket['result'][0]['invoice_no'];

        $mticket_id = $bpticket['result'][0]['id'];

        $ticket_no = $bpticket['result'][0]['ticket_no'];

        $user_id = $bpticket['result'][0]['user_id'];

        $net_total = (int)$bpticket['result'][0]['net_total'];

        $ticket_no = $bpticket['result'][0]['ticket_no'];
    } else {

        $result["type"] = "0";

        $result["result"] = "Ticket Not Found!";

        goto fiVP;
    }



    $shi_data = select_query($con, "user_register", "", "`id`='$user_id' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");

    if ($shi_data['nr'] > 0) {

        $user_id = $shi_data['result'][0]['id'];

        $name = $shi_data['result'][0]['name'];

        $email = $shi_data['result'][0]['email'];

        $mobile = $shi_data['result'][0]['mobile'];

        $bonus_points = $shi_data['result'][0]['bonus_points'];
    } else {

        $result["type"] = "0";

        $result["result"] = "User Not Found!";

        goto fiVP;
    }





    $mticket_arr = ["deletes" => '1', "delete_reason" => $message];



    $mticket_update = update($con, "bpticket", "`id` = '$mticket_id'  and `deletes`='0'", $mticket_arr, "", "", "", "");



    $errors = $mticket_update['errors'];



    if ($errors != "") {



        $result["type"] = "0";



        $result["result"] = $errors;
    } else {



        $Ticket_arr = ["deletes" => '1'];



        $ticket_lines_update = update($con, "ticket_lines", "`ticket_id` = '$mticket_id' and `type` = 'BP'  and `deletes`='0'", $Ticket_arr, "", "", "", "");



        $errors = $ticket_lines_update['errors'];



        if ($errors != "") {

            $result["type"] = "0";

            $result["result"] = $errors;
        } else {

            $topoints = $net_total + $bonus_points;

            $upArff = ["bonus_points" => $topoints];

            $ins_updathh = setupdate($con, "user_register", "`id`='$user_id'", $upArff, "", "", "");











            $Arr1 = [

                'userid' => $user_id,

                'uname' => $name,

                'umobile' => $mobile,

                'uemail' => $email,

                'opening_balance' => $bonus_points,

                'total' => $net_total,

                'closeing_balance' => $topoints,

                'point_type' => 'BONUS',

                'transaction_type' => 'CREDIT',

                'reward_type' => 'TICKET DELETED',

                'card_no' => '',

                'reference_id' => $mticket_id,

                'reference_table' => 'bpticket',

                'ip' => getUserIP(),

                'device' => '',

                'deletes' => '0',

                'status' => '0',

                'createdon' => $dubaidate_time,

                'updatedon' => $dubaidate_time

            ];

            $cb_trans_ins = insert($con, "cb_transactions", "", $Arr1, "", "", "");





            if ($cb_trans_ins['id'] != '') {





                $smsmessages = 'Dear Customer, The earlier issued Ticket ID ' . $ticket_no . ' was found incorrect. Hence, National Draw withdrawing the previous ticket ID information and reissuing the new ticket Shortly.';





                $subject = 'Delete confirmation';



                $messages = '<div style="font-family: Helvetica,Arial,sans-serif;min-width:100%;overflow:auto;line-height:2">



                    <div style="margin:50px auto;width:70%;padding:20px 0">



                   <div style="border-bottom:1px solid #eee">



                     <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">NATIONAL DRAW</a>



                   </div>



                   <p style="font-size:1.1em">Hi ' . $name . ',</p>



                   <p>' . $smsmessages . '</p>



                   <h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">' . $otp . '</h2>



                   <p style="font-size:0.9em;">Regards,<br />National Draw</p>



                   <hr style="border:none;border-top:1px solid #eee" />



                   <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">







                   </div>



                    </div>



                    </div>';



                if ($email != "") {

                    $emailchack = explode('@', $email);

                    if (strtolower($emailchack[1]) != "nationaldraw.ae") {

                        $sendmail = sendemail($con, $email, $subject, $messages, 'tickets');
                    }
                }







                if (substr($mobile, 0, 3) == "971") {

                    $templateid = "";

                    sendsms($con, $mobile, $smsmessages, $templateid);
                }



                $result["type"] = "1";



                $result["result"] = $ticket_no . "  - Ticket has been Deleted Successfully";
            }
        }
    }













    fiVP:

    echo json_encode($result);
} else if ($method == "deletecp_Ticket") {

    $result = [];



    $transid = $_POST['transid'];

    $message = $_POST['message'];



    if ($_SESSION['memid'] == '') {

        $result["type"] = "0";

        $result["result"] = "Kindly Login First & Try Again";

        goto fiVP12;
    }



    if ($transid == '') {

        $result["type"] = "0";

        $result["result"] = "Kindly refresh the page and try again";

        goto fiVP12;
    }



    if ($message == '') {

        $result["type"] = "0";

        $result["result"] = "Kinldy fill Deleted Reason";

        goto fiVP12;
    }







    $bpticket = select_query($con, "cpticket", "", "`transaction_id`='$transid' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");



    if ($bpticket['nr'] > 0) {

        $invoice_no = $bpticket['result'][0]['invoice_no'];

        $mticket_id = $bpticket['result'][0]['id'];

        $ticket_no = $bpticket['result'][0]['ticket_no'];

        $user_id = $bpticket['result'][0]['user_id'];

        $net_total = (int)$bpticket['result'][0]['net_total'];

        $ticket_no = $bpticket['result'][0]['ticket_no'];
    } else {

        $result["type"] = "0";

        $result["result"] = "Ticket Not Found!";

        goto fiVP12;
    }



    $shi_data = select_query($con, "user_register", "", "`id`='$user_id' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");

    if ($shi_data['nr'] > 0) {

        $user_id = $shi_data['result'][0]['id'];

        $name = $shi_data['result'][0]['name'];

        $email = $shi_data['result'][0]['email'];

        $mobile = $shi_data['result'][0]['mobile'];

        $cash_points = $shi_data['result'][0]['cash_points'];
    } else {

        $result["type"] = "0";

        $result["result"] = "User Not Found!";

        goto fiVP12;
    }





    $mticket_arr = ["deletes" => '1', "delete_reason" => $message];



    $mticket_update = update($con, "cpticket", "`id` = '$mticket_id'  and `deletes`='0'", $mticket_arr, "", "", "", "");



    $errors = $mticket_update['errors'];



    if ($errors != "") {



        $result["type"] = "0";



        $result["result"] = $errors;
    } else {



        $Ticket_arr = ["deletes" => '1'];

        $ticket_lines_update = update($con, "ticket_lines", "`ticket_id` = '$mticket_id' and `type` = 'CP'  and `deletes`='0'", $Ticket_arr, "", "", "", "");

        $errors = $ticket_lines_update['errors'];

        if ($errors != "") {

            $result["type"] = "0";

            $result["result"] = $errors;
        } else {









            $Ticket_arr1 = ["deletes" => '1'];

            $invoice_del = update($con, "invoice", "`id` = '$invoice_no' and `ticket_id` = '$mticket_id' and `type` = 'CP'  and `deletes`='0'", $Ticket_arr1, "", "", "", "");

            $errors = $invoice_del['errors'];

            if ($errors != "") {

                $result["type"] = "0";

                $result["result"] = $errors;
            } else {



                $topoints = $net_total + $cash_points;

                $upArff = ["cash_points" => $topoints];

                $ins_updathh = setupdate($con, "user_register", "`id`='$user_id'", $upArff, "", "", "");



                $Arr1 = [

                    'userid' => $user_id,

                    'uname' => $name,

                    'umobile' => $mobile,

                    'uemail' => $email,

                    'opening_balance' => $cash_points,

                    'total' => $net_total,

                    'closeing_balance' => $topoints,

                    'point_type' => 'CASH',

                    'transaction_type' => 'CREDIT',

                    'reward_type' => 'TICKET DELETED',

                    'card_no' => '',

                    'reference_id' => $mticket_id,

                    'reference_table' => 'cpticket',

                    'ip' => getUserIP(),

                    'device' => '',

                    'deletes' => '0',

                    'status' => '0',

                    'createdon' => $dubaidate_time,

                    'updatedon' => $dubaidate_time

                ];

                $cb_trans_ins = insert($con, "cb_transactions", "", $Arr1, "", "", "");



                if ($cb_trans_ins['id'] != '') {





                    $smsmessages = 'Dear Customer, The earlier issued Ticket ID ' . $ticket_no . ' was found incorrect. Hence, National Draw withdrawing the previous ticket ID information and reissuing the new ticket Shortly.';





                    $subject = 'Delete confirmation';



                    $messages = '<div style="font-family: Helvetica,Arial,sans-serif;min-width:100%;overflow:auto;line-height:2">



                    <div style="margin:50px auto;width:70%;padding:20px 0">



                   <div style="border-bottom:1px solid #eee">



                     <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">NATIONAL DRAW</a>



                   </div>



                   <p style="font-size:1.1em">Hi ' . $name . ',</p>



                   <p>' . $smsmessages . '</p>



                   <h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">' . $otp . '</h2>



                   <p style="font-size:0.9em;">Regards,<br />National Draw</p>



                   <hr style="border:none;border-top:1px solid #eee" />



                   <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">







                   </div>



                    </div>



                    </div>';



                    if ($email != "") {

                        $emailchack = explode('@', $email);

                        if (strtolower($emailchack[1]) != "nationaldraw.ae") {

                            $sendmail = sendemail($con, $email, $subject, $messages, 'tickets');
                        }
                    }







                    if (substr($mobile, 0, 3) == "971") {

                        $templateid = "";

                        sendsms($con, $mobile, $smsmessages, $templateid);
                    }



                    $result["type"] = "1";



                    $result["result"] = $ticket_no . "  - Ticket has been Deleted Successfully";
                }
            }
        }
    }


    fiVP12:

    echo json_encode($result);
}
