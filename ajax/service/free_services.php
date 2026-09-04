<?php



include '../../include/shi-config.php';

include '../../include/functions.php';

include '../../include/payment-config.php';

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";

$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";

$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";

if ($type == 'agent') {

    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($_REQUEST[role]) AND" : "";
} else {

    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$_REQUEST[role]' AND" : "";
}



$headers = apache_request_headers();

//print_r($headers);

$result = array();

$post_csrf = $headers['X-Csrf-Token'] ?? '';






if ($method == "getuserdata") {

    $result = [];
    $mobile = BlockSQLInjection($_POST["mobile"]);

    // $mobile = $_POST['mobile'];

    $user = select_query($con, "user_register", "", "`mobile` = '$mobile' AND `roll_id` = '0' AND `deletes`='0'", "", "");

    if ($user['nr'] > 0) {



        $drawid = '';



        $date = date("Y-m-d H:i:s");

   



        $draw = select_query($con, "draw", "", "`deletes`='0' and `status`='Active' and `ticket_start_datetime`<'$date' and `ticket_end_datetime`>'$date'   order by `id` ASC", "", "");

        if ($draw['nr'] > 0) {

            $drawid = $draw['result'][0]['id'];

            $drawname = $draw['result'][0]['name'];
        }



        $outputscreen = '';

        $outputscreen .= '<div class="row"><div class="col-6"><h6><strong>Draw Name: </strong></h6><p>' . $drawname . '</p>';

        $outputscreen .= '<input class="form-control mt-2 form-control-sm tno" id="drawid" type="hidden" value="' . $drawid . '" name="drawid" readonly></div>';

        $outputscreen .= '<div class="col-6"><h6><strong>Name: </strong></h6><p>' . $user['result'][0]['name'] . '</p></div></div>';



       





        $output = '';

        $output .= '<div class="col-md-3 p-0 d-flex" style="padding:0px; margin:0;" id="">';

        $output .= '<button class="btn-style1" onclick="addnewlines($(' . "'#totallines'" . ').val(), ' . "'sub'" . ')">-</button>';

        $output .= '<input class="form-control" type="text" id="totallines" value="0" readOnly>';

        $output .= '<button class="btn-style1" onclick="addnewlines($(' . "'#totallines'" . ').val(), ' . "'add'" . ')">+</button>';

        $output .= '</div>';

        // $result["output"] = $output;

        // $result["type"] = 1;





        $result['outputscreen'] = $outputscreen;

        $result['output'] = $output;

        $result["type"] = "1";

        $result["result"] = "Success!";
    } else {

        $result["type"] = "0";

        $result["result"] = "User not found!";
    }



    echo json_encode($result);
} else if ($method == "delete_Ticket") {

    $result = [];

    $transid = $_POST['transid'];

    $message = $_POST['message'];



    $name = '';

    $email = '';

    $mobile = '';





    $offline = select_query($con, "mticket", "", "`transaction_id`='$transid' and `deletes`='0' ", "", "");

    if ($offline['nr'] > 0) {

        $invoice_no = $offline['result'][0]['invoice_no'];

        $mticket_id = $offline['result'][0]['id'];

        

        $ticket_no = $offline['result'][0]['ticket_no'];

        $user_id = $offline['result'][0]['user_id'];
    }



    $inv_arr = array("deletes" => '1');



    $Inv_update = update($con, "invoice", "`id` = '$invoice_no' and `deletes`='0'", $inv_arr, "", "", "", "");

    $errors = $Inv_update['errors'];

    if ($errors != "") {

        $result["type"] = "0";

        $result["result"] = $errors;
    } else {

        $mticket_arr = array("deletes" => '1', "delete_reason" => $message);

        $mticket_update = update($con, "mticket", "`id` = '$mticket_id'  and `deletes`='0'", $mticket_arr, "", "", "", "");

        $errors = $mticket_update['errors'];

        if ($errors != "") {

            $result["type"] = "0";

            $result["result"] = $errors;
        } else {

            $Ticket_arr = array("deletes" => '1');

            $ticket_lines_update = update($con, "ticket_lines", "`ticket_no` = '$mticket_id' and `type` = 'MT' and `deletes`='0'", $Ticket_arr, "", "", "", "");

            $errors = $ticket_lines_update['errors'];

            if ($errors != "") {

                $result["type"] = "0";

                $result["result"] = $errors;
            } else {

                $shi_data = select_query($con, "user_register", "", "`id`='$user_id' and `deletes`='0' ", "", "");

                if ($shi_data['nr'] > 0) {

                    $name = $shi_data['result'][0]['name'];

                    $email = $shi_data['result'][0]['email'];

                    $mobile = $shi_data['result'][0]['mobile'];
                }



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

                //   $sendmail = sendemail($con, $email, $subject, $messages);

                if ($sendmail) {

                    if (substr($mobile, 0, 3) == "971") {

                        $messages1 = $smsmessages;

                        // $messages1 = 'WAWW!!! Congratulation you have won with National Draw 3rd Prize of AED ' . $product[result][0][rate] . ' for Draw No. ' . $winnerid . ' on 01st May 2022 Labour Day Special Draw by matching the Mix number ' . $lines3[result][$i][my3number] . '. Now you have a free entry to participate in Winners Draw for a chance to win up to 1,000,000.00 dirham, T&C applies. Good Luck!!! national Steps Big Dreams for future draws.';

                        $templateid = "";

                        sendsms($con, $mobile, $messages1, $templateid);
                    }

                    $result["type"] = "1";

                    $result["result"] = $offline['result'][0]['ticket_no'] . "  - Ticket has been Deleted Successfully";
                } else {

                    $result["type"] = "0";

                    $result["result"] = "Email not send!";
                }
            }
        }
    }





    echo json_encode($result);
} else if ($method == "showResult") {

    $key = $_POST['key'];

    $result = [];

    if ($key != '') {

        $sql = "SELECT * FROM `user_register` WHERE  `mobile` LIKE '%$key%' AND `roll_id` = 0 AND `deletes`='0'";
    } else {

        $sql = "SELECT * FROM `user_register` WHERE  `roll_id` = 0 AND `deletes`='0'";
    }



    $run = mysqli_query($con, $sql);

    if (mysqli_num_rows($run) > 0) {

        while ($row = mysqli_fetch_array($run)) {

            $result['data'][] = array("id" => $row['id'], "rollType" => $row['user'], "name" => $row['name'], "t_point" => $row['t_point'], "mobile" => $row['mobile'], "passport" => $row['passport'], "email" => $row['email']);
        }

        $result["type"] = "1";
    } else {

        $result["type"] = "0";

        $result["result"] = "User Not Found!";
    }



    echo json_encode($result);
} else if ($method == "checkticket") {

    $result = [];

    // $ticket = select_query($con, "invoice", "", "`ticket_id`='$_POST[ticketid]' and `deletes`='0' ", "", "");

    $drawid = $_POST['drawid'];

    $ticket = select_query($con, "fticket", "", "(`ticket_no`='FT-$_POST[ticketid]' OR `ticket_no`='FT$_POST[ticketid]') and `deletes`='0' ", "", "");



    if ($ticket['nr'] > 0) {

        $result["type"] = "0";

        $result["result"] = "Ticket Found!";
    } else {

        $output = '';

        $output .= '<div class="col-md-3 p-0 d-flex" style="padding:0px; margin:0;" id="">';

        $output .= '<button class="btn-style1" onclick="addnewlines($(' . "'#totallines'" . ').val(), ' . "'sub'" . ')">-</button>';

        $output .= '<input class="form-control" type="text" id="totallines" value="0" readOnly>';

        $output .= '<button class="btn-style1" onclick="addnewlines($(' . "'#totallines'" . ').val(), ' . "'add'" . ')">+</button>';

        $output .= '</div>';

        $result["output"] = $output;

        $result["type"] = 1;
    }



    echo json_encode($result);
} else if ($method == "createlines") {

    $result = [];

    $count = intval($_POST['count']);

    if ($_POST['key'] == 'sub') {

        $count = $count - 1;
    } else {

        $count = $count + 1;
    }



    $outputscreen = '';

    if ($count <= 12) {

        for ($i = 1; $i <= $count; $i++) {

            $no = '';

            if ($i <= 9) {

                $no = '0' . $i;
            } else if ($i <= 12) {

                $no = $i;
            } else {
            }



            $outputscreen .= '<div class="row">';

            $outputscreen .= '<div class="col-2" style="padding:0px; margin:0;">';

            $outputscreen .= '<select name="productid' . $i . '">';

            $outputscreen .= '<option value="">Select product</option>';

            $pq = "SELECT * FROM `product` WHERE `deletes` = '0'";

            $product = mysqli_query($con, $pq);

            $r = 1;

            if (mysqli_num_rows($product) > 0) {



                while ($row = mysqli_fetch_array($product)) {

                    $name = "productid" . $i;

                    $yesorno = '';

                    if ($_POST[$name] == $row['id']) {

                        $yesorno = 'selected';
                    }

                    $outputscreen .= '<option value="' . $row['id'] . '" ' . $yesorno . '>AED ' . round($row['rate']) . '</option>';

                    $r++;
                }
            }



            $outputscreen .= '</select>';

            $outputscreen .= '</div>';

            // $outputscreen .= '<div class="col-2" style="margin: 0px -10px 0 -10px;">';

            // $outputscreen .= '<input class="form-control mt-2 form-control-sm raffleTS" type="number" min="100" max="999" value="' . $_POST[ticketnumber] . '" readOnly>';

            // $outputscreen .= '</div>';

            // $outputscreen .= '<div class="col-2" style="padding:0px; margin:0;">';

            // $outputscreen .= '<input class="form-control mt-2 form-control-sm raffle_idTicket" type="text" value="' . $no . '" name="ticketid' . $i . '"  onkeyup="keynumcheck(this.value, this.id)" maxlength="2" size="2" readOnly>';

            // $outputscreen .= '</div>';

            $randid = isset($_POST["my3number" . $i]) ? $_POST["my3number" . $i] : rand(100, 999);

            $outputscreen .= '<div class="col-2" style="padding:0px; margin:0;">';

            $outputscreen .= '<input class="form-control mt-2 form-control-sm" type="text" name="my3number' . $i . '" value="' . $randid . '" oninput="this.value = this.value.replace(/[^0-9]/g, ' . "''" . ');" maxlength="3" size="3">';

            $outputscreen .= '</div>';

            $outputscreen .= '</div>';
        }



        if ($count >= 0) {



            if ($_POST['key'] == 'sub') {

                $result['totallines'] = $count;

                $result['outputscreen'] = $outputscreen;
            } else {

                $result['totallines'] = $count;

                $result['outputscreen'] = $outputscreen;
            }

            $result["type"] = "1";

            $result["result"] = "Success!";
        } else {

            $result["type"] = "0";

            $result["result"] = "Failed!";
        }
    }

    echo json_encode($result);
} else if ($method == "registeronline") {

    try {

        $result = [];
        $count = intval($_POST['count']);
        $omaftype = 'FT';
        if ($count <= 13) {
            for ($i = 1; $i <= $count; $i++) {
                $pn = "productid" . $i;
                $id3 = "my3number" . $i;
                if ($_POST[$pn] != '' && $_POST[$id3] != '') {
                } else {
                    $result["type"] = "0";
                    $result["result"] = "Please fill all fields!";
                    $result["s"] = $pn;
                    goto result;
                }
            }
        }
        $count = $count + 1;

        unid:
        $uniqid = 'FT' . uniqid(15) . time();
        $mcheck = select_query($con, "mticket", "", "`transaction_id`='$uniqid' and `deletes`='0' ", "", "");
        $ochekc = select_query($con, "ticket", "", "`transaction_id`='$uniqid' and `deletes`='0' ", "", "");
        $achekc = select_query($con, "aticket", "", "`transaction_id`='$uniqid' and `deletes`='0' ", "", "");
        $fcheck = select_query($con, "fticket", "", "`transaction_id`='$uniqid' and `deletes`='0' ", "", "");
        if ($mcheck['nr'] > 0 || $ochekc['nr'] > 0 || $achekc['nr'] > 0 || $fcheck['nr'] > 0) {
            goto unid;
        }

        $drawid = $_POST['drawid'];
        $myuserno = $_POST['myUser'];
        $mobile = '';
        $username = '';

        $fticketArr = array("payment_transaction_id" => '', 'createdon' => $dubaidate_time, "transaction_id" => $uniqid, "deletes" => '0');
        $fticket = insert($con, "fticket", "", $fticketArr, "", "", "");
        if ($fticket['id'] != '') {
            $ticketnumber = $fticket['id'];

            $userid = '';
            $email = '';
            $ticket_no = $omaftype . $ticketnumber;
            $ticket = select_query($con, "fticket", "", "`ticket_no`= '$ticket_no' AND `deletes`='0' ", "", "");
            if ($ticket['nr'] == 0) {

                $user_register = select_query($con, "user_register", "", "`mobile` = '$myuserno' AND `roll_id` = '0' AND `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                if ($user_register['nr'] > 0) {
                    $userid = $user_register['result'][0]['id'];
                    $username = $user_register['result'][0]['name'];
                    $mobile = $user_register['result'][0]['mobile'];
                    $email = $user_register['result'][0]['email'];
                    $topoints = floatval($user_register['result'][0]['t_point']);

                    $totalAmount = '';

                    if ($count <= 13) {
                        for ($i = 1; $i <= $count; $i++) {
                            $no = '';
                            if ($i <= 9) {
                                $no = '0' . $i;
                            } else if ($i <= 12) {
                                $no = $i;
                            } else {
                            }

                            $pname = "productid" . $i;
                            $proid = $_POST[$pname];
                            $result['pid'] = $proid;
                            $lucky = "my3number" . $i;
                            $rafflevalue = $omaftype . $ticketnumber . $no;
                            $p = select_query($con, "product", "", "`id`='$proid' and `deletes`='0' ", "", "");
                            if ($p['nr'] > 0) {
                                $totalAmount += floatval($p['result'][0]['rate']);
                                $m3n = $_POST[$lucky];
                                $ticket_lines_arr = array("user_id" => $userid, "ticket_id" => $ticketnumber, "draw_id" => $drawid, "agent_id" => $_SESSION['memid'], "product_id" => $_POST[$pname], "orders" => $ticketnumber, "my3number" => $m3n, "raffle_id" => $rafflevalue, "invoice_no" => '', "type" => $omaftype, "deletes" => '0', "createdon" => $dubaidate_time);
                                $a_ticket_lines_insert = insert($con, "ticket_lines", "", $ticket_lines_arr, "", "", "");
                            }
                        }
                    }

                    $count = $count - 1;

                    $onepercen = ($totalAmount / 105);
                    $total_amount = number_format(($onepercen * 100), 2);
                    $tax_value = number_format(($totalAmount - $total_amount), 2);

                    $atic_arr = array(
                        "draw_id" => $drawid,
                        "agent_id" => $_SESSION['memid'],
                        "user_id" => $userid,
                        "ticket_no" => $ticket_no,
                        "invoice_no" => '',
                        "sale_from" => '1',
                        "purchase_datetime" => $dubaidate_time,
                        "total_amount" => $total_amount,
                        "tax_percentage" => "5.00",
                        "tax_value" => $tax_value,
                        "net_total" => $totalAmount,
                        "status" => '1',
                        "payment_by" => 'FREE',
                        "total_lines" => $count,
                        "updatedon" => $dubaidate_time
                    );
                    $fticket_update = update($con, "fticket", "`id` = '$ticketnumber' and `deletes`='0'", $atic_arr, "", "", "", "");
                    $errors = $fticket_update['errors'];
                    if ($errors != "") {
                        $result["type"] = "0";
                        $result["result"] = $errors;
                    } else {


                        $search_fticket = select_query($con, "fticket", "", "`id` = '$ticketnumber' AND `user_id` = '$userid' AND `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                        if ($search_fticket['nr'] > 0) {


                            $subject = "HERE IS YOUR FREE TICKET FROM NATIONAL DRAW" . date("d-m-Y g:i a");

                            $messages .=

                                '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
 
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 
 <meta http-equiv="X-UA-Compatible" content="IE=edge" />
 
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
 <title>Ticket Purchase OTP Mail Template</title>
 
 <script type="text/javascript" src="/86AAF7CD-A199-463A-BEEF-AEE7400B3F0D/main.js?attr=H0u125qMtPBOo8Zu5cYgaDL8xBMB-H_QRrcacqYBON_K51cUrJqsV54pRrMA9nXfbYvRby1IxrV2GlR-QG-iyMQT_9R4-aKvYQx24XIAAJAiIimRqf2U6kJui3vvrWX0" charset="UTF-8"></script><script type="text/javascript" src="https://gc.kis.v2.scr.kaspersky-labs.com/FD126C42-EBFA-4E12-B309-BB3FDD723AC1/main.js?attr=CS1LTlXA9qKY8L_S-EwN_w7tacsjFaWiDZhCZ8lHZBOVuU9OmuU124knWyo3Z1vMxSeSqP6ii_iPK-_qmIGUS5DmeBMDgGhGGvlPQe2UDkRQIvbcaGbrwEMYmxXNxsnK" charset="UTF-8"></script><style type="text/css">
 
 
 
   @import url("https://fonts.googleapis.com/css2?family=Barlow+Condensed&display=swap");
 
   body {
 
     margin: 0;
 
   }
 
   .wrapper {
 
     
 
     background:#CCC;
 
     
 
     }
 
   .main {
 
     
 
     background:#FFF;
 
     max-width:600px;
 
     
 
     } 
 
     
 
   table {
 
     border-spacing: 0;
 
   }
 
   td {
 
     padding: 3px;
 
   }
 
   img {
 
     border: 0;
 
   }
 
   .column-one {
 
 
 
     text-align:center;
 
     margin:0 auto;
 
     }
 
   .column-one .column {
 
     
 
     width:100%;
 
       margin:0 auto;
 
   
 
     }
 
 
 </style>
 
 </head>
 
 <body>
 
 
   <center class="wrapper">
 
 
     <table class="main" width="100%">
 
         <!-- BORDER -->
 
         <tr><td class="column-one" style="background: #29377d; height:50px;">
         
 
         </td></tr>
 
                 <tr><td class="column-one" style="background: radial-gradient(circle,#fcef48 0%,#fdd206 100%); height:11px;">        
 
         </td></tr>        
 
         <tr><td class="column-one" >
 
         <table class="column"> <tr>
           <td valign="top" style="padding: 16px 0 0px 0;">  
 
         <center>
 
           <img src="' . $baseurl . 'assets/images/mailtemplate/logo1.png" style="border: 0px;"  >
         
         </center>
 
           </td></tr></table>
 
         
 
         </td></tr>
 
         <!-- LOGO  -->
 
                 <tr>
 
                   <td class="column-one" >
 
         <table align="center" class="column"> <tr><td valign="top" >  
 
  <div style="margin:0 auto;  max-width:500px; display:block;">
 
          <div style="width:110px; float:left; ">      <img style="border: 0px;" src="' . $baseurl . 'assets/images/mailtemplate/mantoy1.png" ></div>
 
 
          <div  style="">
 
 <h3 class="demoname"style="color: #29377d;  font-family: Arial Narrow;font-style: italic;font-size: 28px; margin: 0px; text-align: center;font-weight: 500;"> ' . $username . ' 
 
                       <br>
 
                     </h3>
 
                    
 
                     <strong><p style="color: #29377d;   font-family: Arial Narrow;font-style: italic; font-size:165%;  margin: 13px 8px 13px 8px; text-align: center;">Your <span style="color: #be1e2d;">Free Ticket</span> Details <br>
 
                     are Below</p>
                   </strong>
                     <h3 style="color: #29377d; font-family: Arial Narrow;  font-style: italic;font-size: 195%; margin: 0px; text-align: center;">Ticket ID #' . $omaftype . '' . $ticketnumber . '
 
                       <br>
 
                     </h3></div>
 
    
 
         </div>
 
           </td></tr></table>
 
         
 
         </td></tr>
 
 
 
 <tr>
 
                   <td class="column-one" >
 
         <table align="center" class="column"> <tr>
 
           <td valign="top" >  
 
  <table style="margin: auto; border-collapse: collapse;border: 1px; width:95%; max-width:500px;" border="1" cellspacing="2" cellpadding="0">
 
               <tbody>
 
                 <tr>
 
                   <th style="padding: 12px 5px;color: #354169;font-style: italic;font-family: Arial Narrow;font-size:18px; width:28%" align="center" bgcolor="#d0dbe7"><strong>Products</strong></th>
 
                   <th style="padding: 12px 5px;color: #354169;font-style: italic;font-family: Arial Narrow;font-size:18px; width:12%" align="center" bgcolor="#d0dbe7"><strong>Lines</strong></th>
 
                   <th style="padding: 12px 5px;color: #354169;font-size:20px;font-family: Arial Narrow; width:35%" align="center" bgcolor="#d0dbe7"><strong>My 
 
                     <span><img align="center" src="' . $baseurl . 'assets/images/mailtemplate/three.png"></span>Numbers </strong></th>
 
                   <th style="padding: 12px 5px;color: #354169;font-style: italic;font-family: Arial Narrow;font-size:18px; width:25%" align="center" bgcolor="#d0dbe7"><strong>Raffle ID</strong></th>
 
                 </tr>
 
                 <tr>';

                            $query = select_query($con, "ticket_lines", "", "`ticket_id`='$ticketnumber' and  `type` = '$omaftype' and `ticket_id`!='' and `deletes`='0' group by `product_id` order by `product_id` ASC  ", "", "");
                            foreach ($query['result'] as $key => $valuelist) {
                                $p_id = $valuelist['product_id'];
                                $t_id = $valuelist['orders'];
                                $product = select_query($con, "product", "", "`id`='$valuelist[product_id]'  ", "", "");
                                foreach ($product['result'] as $key => $productinfo) {
                                }
                                $pcountlist = select_query_count($con, "ticket_lines", "id", " `ticket_id`='$ticketnumber' and `product_id`='$valuelist[product_id]' and `type` = '$omaftype' and `deletes`='0' and `orders`='$valuelist[orders]'", "", "");
                                $mynumber = select_query($con, "ticket_lines", "", " `ticket_id`='$ticketnumber' and `product_id`='$p_id' and `type` = '$omaftype' and `orders`='$t_id' and `deletes`='0'", "", "");
                                $messages .= '<tr>
                                                         <td style=" padding: 12px 5px;color: #354169;font-style: italic;font-family: Arial Narrow;font-size:19px;" align="center" bgcolor="#ffffff"><strong style="font-weight: 800;">AED ' . number_format((float) $productinfo['rate'], 2, '.', '') . '</strong></td>';
                                $messages .= '<td style=" padding: 12px 5px;color: #354169;font-style: italic;font-family: Arial Narrow;font-size:19px;" align="center" bgcolor="#ffffff"><strong style="font-weight: 800;">' . $mynumber['nr'] . '</strong></td>';
                                $messages .= '<td style=" padding: 12px 5px;color: #354169;font-style: italic;font-family: Arial Narrow;font-size:19px;" align="center" bgcolor="#ffffff"><strong style="font-weight: 800;">';
                                foreach ($mynumber['result'] as $key => $mynumber1) {
                                    $messages .= $mynumber1['my3number'] . "<br>";
                                }
                                $messages .= '</strong></td>';
                                $messages .= '<td sstyle=" padding: 12px 5px;color: #354169;font-style: italic;font-family: Arial Narrow;font-size:19px;" align="center" bgcolor="#ffffff"><strong style="font-weight: 800;">';
                                foreach ($mynumber['result'] as $key => $mynumber1) {
                                    $messages .= $mynumber1['raffle_id'] . "<br>";
                                }
                                $messages .= '</strong></td>';
                                $messages .= '</tr>';
                            }

                            $messages .= '</tbody>
 
             </table>
             <br>
 
             
 
              <table style="margin: auto; color: #000000; font-size: medium; background-color: #fbfbfb;  border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">
 
       <tbody>
 
         <tr>
 
           <td style="color: #111111; padding: 15px 14px 23px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px;" align="center" valign="top" bgcolor="#ffffff">
 
             <h3 style="color: #29377d;  font-size: 30px; margin: 0px;font-style: italic;font-family: Arial Narrow;">Total Amount: 
 
               <span class="gmail-otp-bg" style="color: #be1e2d;font-style: italic;font-family: Arial Narrow;">AED ' . number_format((float) $totalAmount, 2, '.', '') . '</span> 
 
               <br>
 
             </h3>
 
           </td>
 
         </tr>
 
       </tbody>
 
     </table>
     <br>
 
     <table style="margin: auto; color: #000000;  font-size: medium; background-color: #fbfbfb;  border-collapse: collapse;" border="0"  cellspacing="0" cellpadding="0">
 
       <tbody>
 
         <tr>
 
           <td style="padding: 0px 10px 0px 0px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px; width:175px;" align="center" valign="top" bgcolor="#ffffff">
 
             <h3 style="color: #ffffff;  font-size: 22px; margin: 0px; padding: 8px 13px 10px 14px; background: #29377d; line-height: 1; border-radius: 5px;">
 
               <a href="' . $baseurl . 'ticket-view/' . $uniqid . '" style="color: #ffffff; text-decoration-line: none;font-style: italic;font-family: Arial Narrow;">View Ticket</a>
 
             </h3>
 
           </td>
 
 
         </tr>
 
       </tbody>
 
     </table>
 
     <table style="margin: auto; color: #000000;  font-size: medium; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">
 
       <tbody>
 
         <tr>
 
           <td style="color: #666666; background: none; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; font-size: 15px; line-height: 25px;" align="center" bgcolor="#e4dcf1">
 
            <p style="color: #29377d;  font-size:147%; text-align: center;font-style: italic;font-family: Arial Narrow;line-height:30px;">Watch Just3 ' . (isset($drawfreq) && $drawfreq === 4 ? 'Daily Draw results <br> every Monday to Friday ' : 'Tri-Daily Draw results <br> every (Monday,Wednesday,Friday) ') . constant("resultTIME") . 'UAE TIME ' . ((isset($ssdraw_id) && $ssdraw_id != '' && isset($sdrawresultdate1)) ? ', Super Raffle Draw on ' . date("dS F Y", strtotime($sdrawresultdate1)) . ' '  : ' ') . (!checkGrandRaffleEligible($draw_no) ?  ('& <br>Grand Raffle Draw result on ' . raffleDrawDate($con, $dubaidate_time, 'dS F Y')) : '') . '</p>
 
           </td>
       
         </tr>
 
        
           <tr>
 
           <td class="gmail-line" style="box-sizing: border-box; width: 8px;padding: 0;">
 
             <img  style="width:500px !important;" src="' . $baseurl . 'assets/images/mailtemplate/final_img.png">
 
           </td>
 
         </tr>
 
       </tbody>
 
     </table> 
    
   
    
 <p style="color: #29377d !important;font-size: 15px !important;margin: 0px !important;text-align: center !important;font-weight: 500 !important;font-style: italic !important;font-family: Arial Narrow !important;margin: 8px 0px 0px 0px !important;">Note: This is a system auto generated email. Please do not reply to this mail.<br>

For Clarification


       <br>

Call 04 33 98880 Whatsapp +971 56 199 1271

<br>

or email support@nationaldraw.com</p>
           </td></tr></table>
 
         </td></tr>
 
     </table> <!-- End Main Class -->
 
 
 
   </center> <!-- End Wrapper -->
 
 
 
 </body>
 

</html>';







                            //     '<!DOCTYPE html
                            //     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

                            // <html xmlns="http://www.w3.org/1999/xhtml">

                            // <head>

                            //     <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

                            //     <meta http-equiv="X-UA-Compatible" content="IE=edge" />

                            //     <meta name="viewport" content="width=device-width, initial-scale=1.0">

                            //     <title>Ticket Purchase OTP Mail Template</title>

                            //     <style type="text/css">
                            //         @import url("https://fonts.googleapis.com/css2?family=Barlow+Condensed&display=swap");

                            //         body {

                            //             margin: 0;

                            //         }

                            //         .wrapper {



                            //             background: #CCC;



                            //         }

                            //         .main {



                            //             background: #FFF;

                            //             max-width: 600px;



                            //         }



                            //         table {

                            //             border-spacing: 0;

                            //         }

                            //         td {

                            //             padding: 3px;

                            //         }

                            //         img {

                            //             border: 0;

                            //         }

                            //         .column-one {



                            //             text-align: center;

                            //             margin: 0 auto;

                            //         }

                            //         .column-one .column {



                            //             width: 100%;

                            //             margin: 0 auto;



                            //         }
                            //     </style>

                            // </head>

                            // <body>



                            //     <center class="wrapper">



                            //         <table class="main" width="100%">


                            //             <tr>
                            //                 <td class="column-one"
                            //                     style="background-image: -webkit-linear-gradient(124deg, #2e76a1 0%, #002387 100%); height:50px;">





                            //                 </td>
                            //             </tr>



                            //             <tr>
                            //                 <td class="column-one"
                            //                     style="background: radial-gradient(circle,#fcef48 0%,#fdd206 100%); height:11px;">





                            //                 </td>
                            //             </tr>





                            //             <tr>
                            //                 <td class="column-one">

                            //                     <table class="column">
                            //                         <tr>
                            //                             <td valign="top">

                            //                                 <center>

                            //                                     <img src="' . $baseurl . 'assets/images/mailnew/logo.png"
                            //                                         style="border: 0px;">



                            //                                 </center>



                            //                             </td>
                            //                         </tr>
                            //                     </table>



                            //                 </td>
                            //             </tr>



                            //             <tr>

                            //                 <td class="column-one">

                            //                     <table align="center" class="column">
                            //                         <tr>
                            //                             <td valign="top">

                            //                                 <div style="margin:0 auto;  max-width:500px; display:block; ">

                            //                                     <div style="width:90px; float:left; "> <img style="border: 0px;"
                            //                                             src="' . $baseurl . 'assets/images/mailnew/mantoy.png"></div>





                            //                                     <div style=""><br />

                            //                                         <h3 class="demoname"
                            //                                             style="color: #052c8a;  font-family: Arial Narrow;font-style: italic;font-size: 38px; margin: 0px; text-align: center;">
                            //                                           ' . $username . '

                            //                                             <br>

                            //                                         </h3>



                            //                                         <strong>
                            //                                             <p
                            //                                                 style="color: #052c8a;   font-family: Arial Narrow;font-style: italic; font-size:179%;  margin: 0px 8px 0px 8px; text-align: center;">
                            //                                                 Your <span style="color: #be1e2d;">Free Ticket</span> Details <br>

                            //                                                 are Below</p>
                            //                                         </strong>
                            //                                         <h3
                            //                                             style="color: #052c8a; font-family: Arial Narrow;  font-style: italic;font-size: 214%; margin: 0px; text-align: center;">
                            //                                             Ticket ID #' . $omaftype . '' . $ticketnumber . '

                            //                                             <br>

                            //                                         </h3>
                            //                                     </div>



                            //                                 </div>

                            //                             </td>
                            //                         </tr>
                            //                     </table>



                            //                 </td>
                            //             </tr>



                            //             <tr>

                            //                 <td class="column-one">

                            //                     <table align="center" class="column">
                            //                         <tr>

                            //                             <td valign="top">

                            //                                 <table
                            //                                     style="margin: auto; border-collapse: collapse;border: 1px; width:95%; max-width:500px;"
                            //                                     border="1" cellspacing="2" cellpadding="0">

                            //                                     <tbody>

                            //                                         <tr>

                            //                                             <th style="padding: 12px 5px;color: #354169;font-style: italic;font-family: Arial Narrow;font-size:18px; width:28%"
                            //                                                 align="center" bgcolor="#cedce9"><strong>Products</strong></th>

                            //                                             <th style="padding: 12px 5px;color: #354169;font-style: italic;font-family: Arial Narrow;font-size:18px; width:12%"
                            //                                                 align="center" bgcolor="#cedce9"><strong>Lines</strong></th>

                            //                                             <th style="padding: 12px 5px;color: #354169;font-size:20px;font-family: Arial Narrow; width:35%"
                            //                                                 align="center" bgcolor="#cedce9"><strong>My

                            //                                                     <span><img align="center"
                            //                                                             src="' . $baseurl . 'assets/images/mailnew/three.png"></span>&nbsp;Numbers
                            //                                                 </strong></th>

                            //                                             <th style="padding: 12px 5px;color: #354169;font-style: italic;font-family: Arial Narrow;font-size:18px; width:25%"
                            //                                                 align="center" bgcolor="#cedce9"><strong>Raffle ID</strong></th>

                            //                                         </tr>';


                            //     $query = select_query($con, "ticket_lines", "", "`ticket_id`='$ticketnumber' and  `type` = '$omaftype' and `ticket_id`!='' and `deletes`='0' group by `product_id` order by `product_id` ASC  ", "", "");
                            //     foreach ($query['result'] as $key => $valuelist) {
                            //         $p_id = $valuelist['product_id'];
                            //         $t_id = $valuelist['orders'];
                            //         $product = select_query($con, "product", "", "`id`='$valuelist[product_id]'  ", "", "");
                            //         foreach ($product['result'] as $key => $productinfo) {
                            //         }
                            //         $pcountlist = select_query_count($con, "ticket_lines", "id", " `ticket_id`='$ticketnumber' and `product_id`='$valuelist[product_id]' and `type` = '$omaftype' and `deletes`='0' and `orders`='$valuelist[orders]'", "", "");
                            //         $mynumber = select_query($con, "ticket_lines", "", " `ticket_id`='$ticketnumber' and `product_id`='$p_id' and `type` = '$omaftype' and `orders`='$t_id' and `deletes`='0'", "", "");
                            //         $messages .= '<tr>
                            //                                         <td style="padding: 12px 5px;color: #354169;font-style: italic;font-family: Arial Narrow;font-size:19px;" align="center" bgcolor="#ffffff"><strong style="font-weight: 800;">AED ' . number_format((float) $productinfo['rate'], 2, '.', '') . '</strong></td>';
                            //         $messages .= '<td style="padding: 12px 5px;color: #354169;font-style: italic;font-family: Arial Narrow;font-size:19px;" align="center" bgcolor="#ffffff"><strong style="font-weight: 800;">' . $mynumber['nr'] . '</strong></td>';
                            //         $messages .= '<td style="padding: 12px 5px;color: #354169;font-style: italic;font-family: Arial Narrow;font-size:19px;" align="center" bgcolor="#ffffff"><strong style="font-weight: 800;">';
                            //         foreach ($mynumber['result'] as $key => $mynumber1) {
                            //             $messages .= $mynumber1['my3number'] . "<br>";
                            //         }
                            //         $messages .= '</strong></td>';
                            //         $messages .= '<td style="padding: 12px 5px;color: #354169;font-style: italic;font-family: Arial Narrow;font-size:19px;" align="center" bgcolor="#ffffff"><strong style="font-weight: 800;">';
                            //         foreach ($mynumber['result'] as $key => $mynumber1) {
                            //             $messages .= $mynumber1['raffle_id'] . "<br>";
                            //         }
                            //         $messages .= '</strong></td>';
                            //         $messages .= '</tr>';
                            //     }

                            //     $messages .= '</tbody>

                            //                                 </table>
                            //                                 <br>



                            //                                 <table
                            //                                     style="margin: auto; color: #000000; font-size: medium; background-color: #fbfbfb;  border-collapse: collapse;"
                            //                                     border="0" cellspacing="0" cellpadding="0">

                            //                                     <tbody>

                            //                                         <tr>

                            //                                             <td style="color: #111111; padding: 15px 14px 23px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px;"
                            //                                                 align="center" valign="top" bgcolor="#ffffff">

                            //                                                 <h3
                            //                                                     style="color: #052c8a;  font-size: 35px; margin: 0px;font-style: italic;font-family: Arial Narrow;">
                            //                                                     Total Amount:

                            //                                                     <span class="gmail-otp-bg"
                            //                                                         style="color: #be1e2d;font-style: italic;font-family: Arial Narrow;">AED
                            //                                                         ' . number_format((float) $totalAmount, 2, '.', '') . '</span>

                            //                                                     <br>

                            //                                                 </h3>

                            //                                             </td>

                            //                                         </tr>

                            //                                     </tbody>

                            //                                 </table>
                            //                                 <br>

                            //                                 <table
                            //                                     style="margin: auto; color: #000000;  font-size: medium; background-color: #fbfbfb;  border-collapse: collapse;"
                            //                                     border="0" cellspacing="0" cellpadding="0">

                            //                                     <tbody>

                            //                                         <tr>

                            //                                             <td style="padding: 0px 10px 0px 0px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px; width:175px;"
                            //                                                 align="center" valign="top" bgcolor="#ffffff">

                            //                                                 <h3
                            //                                                     style="color: #ffffff;  font-size: 22px; margin: 0px; padding: 8px 13px 10px 14px; background: #052c8a; line-height: 1; border-radius: 5px;">

                            //                                                     <a href="' . $baseurl . 'ticket-view/' . $uniqid . '"
                            //                                                         style="color: #ffffff; text-decoration-line: none;font-style: italic;font-family: Arial Narrow;">View
                            //                                                         Ticket</a>

                            //                                                 </h3>

                            //                                             </td>



                            //                                         </tr>

                            //                                     </tbody>

                            //                                 </table>

                            //                                 <table
                            //                                     style="margin: auto; color: #000000;  font-size: medium; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; border-collapse: collapse;"
                            //                                     border="0" cellspacing="0" cellpadding="0">

                            //                                     <tbody>

                            //                                         <tr>

                            //                                             <td style="color: #666666; background: none; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; line-height: 25px;"
                            //                                                 align="center" bgcolor="#e4dcf1">

                            //                                                 <p
                            //                                                     style="color: #052c8a;  font-size:147%; text-align: center;font-style: italic;font-family: Arial Narrow;line-height:30px;">
                            //                                                     Watch Rolling Ball Weekly Draw results<br> every Monday '. constant("resultTIME") .'(UAE Time) & <br>Grand Raffle Draw result on ' . raffleDrawDate($con, $dubaidate_time, 'd.m.Y') . '</p>

                            //                                             </td>



                            //                                         </tr>

                            //                                         <tr>

                            //                                             <td>

                            //                                                 <p
                            //                                                     style="color: #052c8a;  font-size: 22px; margin: 0px; text-align: center;font-weight: 600;font-style: italic;font-family: Arial Narrow;">
                            //                                                     <strong>See us live on<span> <img align="center"
                            //                                                                 style="border: 0px; height: auto; line-height: 48px; outline: none;"
                            //                                                                 src="' . $baseurl . 'assets/images/mailnew/fb.png">&nbsp;

                            //                                                             <img align="center"
                            //                                                                 style="border: 0px; height: auto; line-height: 48px; outline: none;"
                            //                                                                 src="' . $baseurl . 'assets/images/mailnew/youtube.png"></span></strong>
                            //                                                 </p>

                            //                                             </td>



                            //                                         </tr>

                            //                                     </tbody>

                            //                                 </table>

                            //                                 <br style="color: #000000;  font-size: medium; background-color: #fbfbfb;">

                            //                                 <table
                            //                                     style="margin: auto; color: #000000;  font-size: medium; background-color: #fbfbfb; border-collapse: collapse;"
                            //                                     border="0" cellspacing="0" cellpadding="0">

                            //                                     <tbody>

                            //                                         <tr>

                            //                                             <td class="gmail-line" style="box-sizing: border-box; width: 8px;">

                            //                                                 <img style="width:489px !important;"
                            //                                                     src="' . $baseurl . 'assets/images/mailnew/center_img2.png">

                            //                                             </td>

                            //                                         </tr>

                            //                                     </tbody>

                            //                                 </table>


                            

                            //                             </td>
                            //                         </tr>
                            //                     </table>



                            //                 </td>
                            //             </tr>
                            //         </table>
                            //     </center>
                            // </body>

                            // </html>';




                            if ($email != "") {
                                $emailchack = explode('@', $email);
                                if (strtolower($emailchack[1]) != "nationaldraw.ae") {
                                    $emailsend = sendemail($con, $email, $subject, $messages, 'tickets');
                                }
                            }

                            if (substr($mobile, 0, 3) == "971") {
                                $messages1 = 'Ticket ID #' . $omaftype . '' . $ticketnumber . '.';
                                $query = select_query($con, "ticket_lines", "", "`ticket_id`='$ticketnumber' and `type` = '$omaftype' and `ticket_id`!='' group by `product_id` order by `product_id` ASC  ", "", "");

                                foreach ($query['result'] as $key => $valuelist) {
                                    $p_id = $valuelist['product_id'];
                                    $t_id = $valuelist['orders'];
                                    $product = select_query($con, "product", "", "`id`='$valuelist[product_id]'  ", "", "");
                                    $messages1 .= 'CAT-AED ' . round($product['result'][0]['rate']) . '. ';
                                    $rcount = count($mynumber['result']);
                                    $io = 1;
                                    $mynumber = select_query($con, "ticket_lines", "", "`ticket_id`='$ticketnumber' and `product_id`='$p_id' and `type` = '$omaftype' and `orders`='$t_id'", "", "");
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

                                $printurlf = $baseurl . 'ticket-view/' . $uniqid;
                                $printurl = get_tiny_url($printurlf);
                                $messages1 .= 'for a Total Amounts of AED ' . number_format((float) $totalAmount, 2, '.', '') . ' Check your free Ticket here. ( ' . $printurl . ' ),. TC apply.';
                                $templateid = "";
                                sendsms($con, $mobile, $messages1, $templateid);
                            }

                            $t = get_tiny_url($baseurl . 'ticket-view/' . $uniqid);
                            $in = get_tiny_url($baseurl . 'invoice/' . $uniqid);

                            $nowpoints = select_top_name($con, "ldbank", "points", "`deletes`='0' and `id`='9999999'", "points", "");
                            $balancepoint = intval($nowpoints) - intval($totalAmount);

                            //$tobalancepoint = $topoints + $totalAmount;
                            $tobalancepoint = "0";

                            $Arrd = array("from_id" => "9999999", "type" => "FREE", "points" => $totalAmount, "from_opening" => $nowpoints, "from_closing" => $balancepoint, "to_id" => $userid, "to_opening" => $topoints, "to_closing" => $tobalancepoint, "invoice_id" => $ticketnumber, "createdon" => $dubaidate_time);
                            $invoice = insert($con, "points_transaction", "", $Arrd, "", "", "");
                            if ($invoice['id'] != '') {
                                $ld_arr = array("points" => $balancepoint);
                                $amt_update = update($con, "ldbank", "`id`='9999999' AND `deletes`='0'", $ld_arr, "", "", "", "");
                                $errors = $amt_update['errors'];
                                if ($errors != "") {
                                    $result["type"] = "0";
                                    $result["result"] = $errors;
                                } else {
                                    $result["type"] = "1";
                                    $result["result"] = "Ticket Generated!";
                                }
                            }
                        }
                    }
                } else {
                    $result["type"] = "0";
                    $result["result"] = "Ticket Found!";
                }
            } else {
                $result["type"] = "0";
                $result["result"] = "User Not Found!";
            }
            result:
            echo json_encode($result);
        }
    } catch (Exception $e) {
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'free_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == 'previewmticket') {

    $result = [];

    $omatype = 'FT';

    $totalAmount = '';

    $count = intval($_POST['count']);



    $balance_point = select_top_name($con, "user_register", "f_points", "`id`='$_SESSION[memid]' and `deletes`='0'", "f_points", "");

    if (intval($balance_point) > 0) {



        if ($count <= 13) {

            for ($i = 1; $i <= $count; $i++) {

                $no = '';

                if ($i <= 9) {

                    $no = '0' . $i;
                } else if ($i <= 12) {

                    $no = $i;
                } else {
                }

                $pname = "productid" . $i;

                $proid = $_POST[$pname];

                $p = select_query($con, "product", "", "`id`='$proid' and `deletes`='0' ", "", "");

                if ($p['nr'] > 0) {

                    $totalAmount += floatval($p['result'][0]['rate']);
                }
            }
        }



        if (intval($balance_point) >= intval($totalAmount)) {
        } else {

            $result["type"] = "0";

            $result["result"] = "Your Point Balance is Low, Refill your Points Before Starting Transaction.";

            goto re;
        }
    } else {

        $result["type"] = "0";

        $result["result"] = "Your Point Balance is Nil, Refill your Points Before Starting Transaction.";

        goto re;
    }







    if ($count <= 13) {

        for ($i = 1; $i <= $count; $i++) {

            $pn = "productid" . $i;

            $id3 = "my3number" . $i;

            if ($_POST[$pn] != '' && $_POST[$id3] != '') {

                if (strlen($_POST[$id3]) == 3) {
                } else {

                    $result["type"] = "0";

                    $result["result"] = "Please Enter 3 Number!";

                    $result["s"] = $pn;

                    goto re;
                }
            } else {

                $result["type"] = "0";

                $result["result"] = "Please fill all fields!";

                $result["s"] = $pn;

                goto re;
            }
        }
    }



    $myuserno = $_POST['myUser'];

    $totalAmount = '';

    $ticketnumber = $_POST['ticketnumber'];

    $drawid = $_POST['drawid'];

    $drawdate = select_top_name($con, "draw", "result_datetime", "`id`='$drawid' and `deletes`='0' and `status`='Active'  order by `id` DESC ", "result_datetime", "");

    $drawdate = date("d M Y", strtotime($drawdate));

    $drawname = select_top_name($con, "draw", "name", "`id`='$drawid' and `deletes`='0' and `status`='Active'  order by `id` DESC ", "name", "");

    $sql = "SELECT * FROM `user_register` WHERE  `mobile` = '$myuserno' AND `deletes`='0'";

    $run = mysqli_query($con, $sql);

    if (mysqli_num_rows($run) > 0) {

        $row = mysqli_fetch_array($run);

        $userid = $row['id'];

        $username = $row['name'];

        $mobile = $row['mobile'];

        $email = $row['email'];
    } else {

        $result["type"] = "0";

        $result["result"] = "User Not Found!";
    }

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

            AED 30,000.00

        </td>

        <td style="text-align: center; width: 33%;">

            AED 3,000.00

        </td>

        <td style="text-align: center; width: 33%;">

            AED 1,000.00

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

       <tr style="    border-bottom: 1px dashed #a9a9a9;">



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



    $arr = array();

    if ($count <= 13) {

        for ($l = 1; $l <= $count; $l++) {

            $pname1 = "productid" . $l;

            $proid1 = $_POST[$pname1];



            array_push($arr, $proid1);
        }
    }







    $sql = 'SELECT * FROM `product` WHERE `deletes` = 0;';

    $run = mysqli_query($con, $sql);

    if (mysqli_num_rows($run) > 0) {

        while ($row = mysqli_fetch_array($run)) {



            if (in_array($row['id'], $arr)) {

                $output .= '<tr><td>';

                $output .= 'AED ' . number_format((float) $row['rate'], 2, '.', '');

                $output .= '</td>';

                $procount = '';

                $lucystring = '';

                $rafflestring = '';



                if ($count <= 13) {

                    for ($i = 1; $i <= $count; $i++) {

                        $no = '';

                        if ($i <= 9) {

                            $no = '0' . $i;
                        } else if ($i <= 12) {

                            $no = $i;
                        } else {
                        }

                        $pname = "productid" . $i;

                        $proid = $_POST[$pname];

                        $result['pid'] = $proid;

                        $lucky = "my3number" . $i;

                        $rafflevalue = $omatype . $ticketnumber . $no;

                        if ($row['id'] == $proid) {

                            $procount += 1;

                            $lucystring .= $_POST[$lucky] . '<br>';

                            $rafflestring .= $rafflevalue . '<br>';
                        }
                    }
                }



                $output .= '<td>';

                $output .= $procount;

                $output .= '</td><td>';

                $output .= $lucystring;

                $output .= '</td>';

                $output .= '</tr>';
            }
        }
    }



    $output .= '

    <tr style="border-bottom: 1px dashed #a9a9a9; padding-top:12px;"></tr>

    <tr>

    <td colspan="4" style="text-align: center;"><br>Just3 Draw Date: ' . $drawdate . '<br>Raffle Draw Date: ' . raffleDrawDate($con, $dubaidate_time, 'd M Y') . '</td>

</tr>

    

    ';



    $output .= '<tr>

        <td colspan="4" style="text-align: center;">

            All Other Terms and Conditions Apply

        </td>

    </tr>

</tbody>

</table>

';









    $result["ticketid"] = $ticketnumber;

    $result["output"] = $output;

    $result["type"] = "1";

    $result["result"] = "Success!";

    re:

    echo json_encode($result);
} else if ($method == "madd_agent_new") {

    $result = [];

    $sql = "SELECT * FROM `user_register` WHERE `mobile` = '$_POST[mobile]' AND `status` = 0 AND `deletes` = 0";

    $existcheck = mysqli_query($con, $sql);

    if (mysqli_num_rows($existcheck) > 0) {



        $result["type"] = "0";

        $result["result"] = "Mobile number already exists";

        // echo json_encode($result);

        goto Ri;
    } else {



        $sql = "SELECT * FROM `user_register` WHERE `email` = '$_POST[email]' AND `status` = 0 AND `deletes` = 0";

        $existcheck = mysqli_query($con, $sql);

        if (mysqli_num_rows($existcheck) > 0) {



            $result["type"] = "0";

            $result["result"] = "Email already exists";

            goto Ri;
        } else {



            // $result = [];

            // echo json_encode("s");

            // $otp = $_REQUEST['otp1'] . $_REQUEST['otp2'] . $_REQUEST['otp3'] . $_REQUEST['otp4'];



            $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC ", "roll_id", "");



            // if ($otp == $_SESSION['otp']) {



            if ($tabID == 'agents') {

                // if ($_SESSION['roll_id'] == 1) {

                if ($roll_id == 1) {

                    $roll_id = 2;
                } else if ($roll_id == 2) {

                    $roll_id = 3;
                } else if ($roll_id == 3) {

                    $roll_id = 4;
                } else if ($roll_id == 4) {

                    $roll_id = 5;
                } else {

                    $roll_id = 0;
                }
            } else if ($tabID == 'staffs') {

                $roll_id = 6;
            } else {

                $roll_id = 0;
            }



            if ($roll_id != 0) {

                $sql2 = "SELECT * FROM `role` WHERE id = $roll_id";

                $run2 = mysqli_query($con, $sql2);

                $usertype = $run2->fetch_assoc();

                $user = $usertype['name'];
            } else {

                $user = 'Customer';
            }







            $pass = md5($_POST['Password']);

            if ($user == 'Customer') {

                $pass = null;
            }



            $sql = "INSERT INTO `user_register` (`user`, `roll_id`, `name`, `mobile`, `email`, `passport`, `passport_expiry`, `deletes`, `status`, `created_at` , `otp`, `pass`, `created_by`) VALUES ('$user', '$roll_id', '$_POST[name]', '$_POST[mobile]', '$_POST[email]', '$_POST[passport]', '$_POST[passport_expiry]', '0', '0', '$dubaidate_time', '', '$pass', '$_SESSION[memid]');";

            $result['query'] = $sql;

            $run = mysqli_query($con, $sql);

            if ($run) {

                $sql = "SELECT * FROM `user_register` WHERE `mobile` = '$_SESSION[Newmobile]' AND `email` = '$_SESSION[Newemail]' AND `status` = 0 AND `deletes` = 0";

                $existcheck = mysqli_query($con, $sql);

                if ($existcheck) {

                    $row = $existcheck->fetch_assoc();

                    $result = array("type" => "1", "result" => "Inserted Successfully", "id" => $row['id'], "rollType" => $row['user'], "name" => $row['name'], "t_point" => $row['t_point'], "mobile" => $row['mobile'], "passport" => $row['passport'], "email" => $row['email']);

                    $result["type"] = "1";

                    $result["result"] = "Customer Added Successfully";
                }
            } else {

                $result["type"] = "0";

                $result["result"] = "Not registered!";

                goto Ri;
            }

            // } else {

            //     $result["type"] = "0";

            //     $result["result"] = "Otp does not match";

            // }

        }

        goto Ri;
    }

    Ri:

    echo json_encode($result);
} else if ($method == "send_otp") {

    $result = [];

    $title = '';

    $emdata = '';

    $_SESSION['Newname'] = $_REQUEST['name'];

    $_SESSION['Newemail'] = $_REQUEST['email'];

    $_SESSION['Newmobile'] = $_REQUEST['mobile'];

    $_SESSION['Newpassport'] = $_REQUEST['passport'];

    $_SESSION['Newpassport_expiry'] = $_REQUEST['passport_expiry'];

    $_SESSION['NewPassword'] = md5($_REQUEST['Password']);

    $email = $_REQUEST['email'];

    $mobile = $_REQUEST['mobile'];

    $n = 4; // 4 OR 6 Only

    $otp = generateNumericOTP($n);

    // $otp = "1234";

    $sql = "SELECT * FROM `user_register` WHERE `mobile` = '$_SESSION[Newmobile]' AND `status` = 0 AND `deletes` = 0";

    $existcheck = mysqli_query($con, $sql);

    if (mysqli_num_rows($existcheck) > 0) {

        // $result = [];

        $result["type"] = "0";

        $result["result"] = "Mobile number already exists";

        goto si;
    } else {

        $sql = "SELECT * FROM `user_register` WHERE `email` = '$_SESSION[Newemail]' AND `status` = 0 AND `deletes` = 0";

        $existcheck = mysqli_query($con, $sql);

        if (mysqli_num_rows($existcheck) > 0) {

            // $result = [];

            $result["type"] = "0";

            $result["result"] = "Email already exists";

            goto si;
        } else {



            $_SESSION['otp'] = $otp;

            // otp email

            if (substr($_SESSION['Newmobile'], 0, 3) == "971") {

                // $email =  $_SESSION['Newemail'];

                // $mobile = substr($_SESSION['Newmobile'], 0, 3);

                $messages1 = "Hello " . $_SESSION['Newname'] . ", " . $otp . " is the One Time Password (OTP) to verification the National draw Account.";

                // $messages = "Congratulation!!! You have successfully created National Draw account, Good luck to national steps big dreams. Now you can buy and donate to participate into National Draw";

                $templateid = "";

                sendsms($con, $mobile, $messages1, $templateid);

                $title = 'Email / Mobile phone';

                $emdata = $_SESSION['Newemail'] . ' / ' . $_SESSION['Newmobile'];
            }



            $subject = 'Verification';

            // $messages = 'OTP:' . $otp;



            $messages = '<div style="font-family: Helvetica,Arial,sans-serif;min-width:100%;overflow:auto;line-height:2">

           <div style="margin:50px auto;width:70%;padding:20px 0">

          <div style="border-bottom:1px solid #eee">

            <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">NATIONAL DRAW</a>

          </div>

          <p style="font-size:1.1em">Hi ' . $_SESSION['Newname'] . ',</p>

          <p>Thank you for choosing National Draw. Use the following OTP to complete your Sign Up procedures. OTP is valid for 15 minutes</p>

          <h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">' . $otp . '</h2>

          <p style="font-size:0.9em;">Regards,<br />National Draw</p>

          <hr style="border:none;border-top:1px solid #eee" />

          <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">



          </div>

           </div>

           </div>';

            // $email = 'prakashsp8421@gmail.com';

            if ($email != "") {



                $emailchack = explode('@', $email);

                if (strtolower($emailchack[1]) != "nationaldraw.ae") {

                    $sendemail = sendemail($con, $email, $subject, $messages, 'otp');
                }
            }

            //  $sendemail = sendemail($con, $email, $subject, $messages);



            if ($sendemail) {

                if ($title == '' && $emdata == '') {

                    $title = 'Email';

                    $emdata = $_SESSION['Newemail'];
                }

                if ($_SESSION['otp'] != "") {

                    $result["title"] = $title;



                    $result["Newmobile"] = $emdata;

                    // $result["Newemail"] = $_SESSION['Newemail'];

                    $result["type"] = "1";

                    $result["result"] = "Otp send Successfully";
                } else {

                    $result["type"] = "0";

                    $result["result"] = "Otp not send";

                    goto si;
                }
            }
        }
    }

    si:

    echo json_encode($result);
} else if ($method == "sendsmstopurchase") {

    $result = [];

    $transid = $_REQUEST['transid'];

    $omatype = 'FT';



    $ticketnumber = select_top_name($con, "fticket", "ticket_no", "`transaction_id`='$transid' and `deletes`='0'", "ticket_no", "");

    $fticket_new_id = select_top_name($con, "fticket", "id", "`transaction_id`='$transid' and `deletes`='0'", "id", "");

    $user_id = select_top_name($con, "fticket", "user_id", "`transaction_id`='$transid' and `deletes`='0'", "user_id", "");

    $mobile = select_top_name($con, "user_register", "mobile", "`id`='$user_id' and `deletes`='0'", "mobile", "");

    $totalAmount = select_top_name($con, "fticket", "net_total", "`transaction_id`='$transid' and `deletes`='0'", "net_total", "");

    if (substr($mobile, 0, 3) == "971") {

        $messages1 = 'Ticket ID #' . $ticketnumber . '. ';







        $query = select_query($con, "ticket_lines", "", "`ticket_id`='$fticket_new_id' and `type` = '$omatype' and `ticket_id`!='' group by `product_id` order by `product_id` ASC  ", "", "");



        foreach ($query['result'] as $key => $valuelist) {

            $p_id = $valuelist['product_id'];

            $t_id = $valuelist['orders'];

            $product = select_query($con, "product", "", "`id`='$valuelist[product_id]'  ", "", "");

            $messages1 .= 'CAT-AED ' . round($product['result'][0]['rate']) . '. ';







            $io = 1;

            $mynumber = select_query($con, "ticket_lines", "", "`ticket_id`='$fticket_new_id' and `product_id`='$p_id' and `type` = '$omatype' and `orders`='$t_id'", "", "");

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



        $printurlf = $baseurl . 'ticket-view/' . $uniqid;

        $printurl = get_tiny_url($printurlf);



        $messages1 .= 'for a Total Amounts of AED ' . number_format((float) $totalAmount, 2, '.', '') . ' for more info. (' . $printurl . '),. TC apply.';

        $templateid = "";

        sendsms($con, $mobile, $messages1, $templateid);

        $result["type"] = "1";

        $result["result"] = "SMS Send Successfully";
    } else {

        $result["type"] = "0";

        $result["result"] = "could not send sms";
    }

    echo json_encode($result);
} else if ($method == "suspended_agent") {

    $result = [];

    $userid = $_REQUEST['userid'];

    $reason = $_REQUEST['reason'];

    if ($userid != '') {

        if ($reason != '') {

            $shi_arr = array("status" => '1');

            $user_register_update = update($con, "user_register", "`id` = '$userid' and `deletes`='0' and `status` = '0'", $shi_arr, "", "", "", "");

            $errors = $user_register_update['errors'];

            if ($errors != "") {

                $result["type"] = "0";

                $result["result"] = $errors;
            } else {

                $suspended_historyArr = array("userid" => $userid, "reason" => $reason, "submittedby" => $_SESSION['memid'], "type" => 'suspended', "createdon" => $dubaidate_time);

                $suspended_history = insert($con, "suspended_log", "", $suspended_historyArr, "", "", "");

                $result["type"] = "1";

                $result["result"] = "Account Suspended.";
            }
        } else {

            $result["type"] = "0";

            $result["result"] = "Please Fill The Reason.";
        }
    } else {

        $result["type"] = "0";

        $result["result"] = "User Id Not Received.";
    }

    echo json_encode($result);
} else if ($method == "unsuspendagent_agent") {

    $result = [];

    $userid = $_REQUEST['userid'];

    $reason = $_REQUEST['reason'];

    if ($userid != '') {

        if ($reason != '') {

            $shi_arr = array("status" => '0');

            $user_register_update = update($con, "user_register", "`id` = '$userid' and `deletes`='0' and `status` = '1'", $shi_arr, "", "", "", "");

            $errors = $user_register_update['errors'];

            if ($errors != "") {

                $result["type"] = "0";

                $result["result"] = $errors;
            } else {

                $suspended_historyArr = array("userid" => $userid, "reason" => $reason, "submittedby" => $_SESSION['memid'], "type" => 'unsuspended', "createdon" => $dubaidate_time);

                $suspended_history = insert($con, "suspended_log", "", $suspended_historyArr, "", "", "");

                $result["type"] = "1";

                $result["result"] = "Account Unsuspended.";
            }
        } else {

            $result["type"] = "0";

            $result["result"] = "Please Fill The Reason.";
        }
    } else {

        $result["type"] = "0";

        $result["result"] = "User Id Not Received.";
    }

    echo json_encode($result);
} else if ($method == "deleted_agent") {

    $result = [];

    $userid = $_REQUEST['userid'];

    $reason = $_REQUEST['reason'];

    if ($userid != '') {

        if ($reason != '') {



            $user_register = select_query($con, "user_register", "", "`id` = '$userid'  AND `deletes`='0' ORDER BY `id` DESC", "", "");

            if ($user_register['nr'] > 0) {

                // $t_point = $user_register[result][0][t_point];

                $created_by = $user_register['result'][0]['created_by'];





                $to_id = $user_register['result'][0]['id'];



                $req_name = $user_register['result'][0]['name'];

                $t_point = $user_register['result'][0]['t_point'];

                $UT_point = intval($t_point) - intval($t_point);

                $form_id = $user_register['result'][0]['created_by'];

                $from_t_point = select_top_name($con, "user_register", "t_point", "`id`='$form_id' and `deletes`='0'", "t_point", "");

                $balancepoint = intval($from_t_point) + intval($t_point);



                $Arrd = array("from_id" => "$to_id", "type" => "delete", "points" => $t_point, "from_opening" => $t_point, "from_closing" => $UT_point, "to_id" => $form_id, "to_opening" => $from_t_point, "to_closing" => $balancepoint, "invoice_id" => '', "createdon" => $dubaidate_time);

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



                            $shi_arr = array("deletes" => '1');

                            $user_register_update = update($con, "user_register", "`id` = '$userid' and `deletes`='0' and `status` = '0'", $shi_arr, "", "", "", "");

                            $errors = $user_register_update['errors'];

                            if ($errors != "") {

                                $result["type"] = "0";

                                $result["result"] = $errors;
                            } else {

                                $suspended_historyArr = array("userid" => $userid, "reason" => $reason, "submittedby" => $_SESSION['memid'], "createdon" => $dubaidate_time);

                                $suspended_history = insert($con, "deleted_log", "", $suspended_historyArr, "", "", "");

                                $result["type"] = "1";

                                $result["result"] = "Account Deleted.";
                            }
                        }
                    }
                }
            } else {

                $result["type"] = "0";

                $result["result"] = "User Not Found!";
            }
        } else {

            $result["type"] = "0";

            $result["result"] = "Please Fill The Reason.";
        }
    } else {

        $result["type"] = "0";

        $result["result"] = "User Id Not Received.";
    }

    echo json_encode($result);
} else if ($method == "delete_agent_ticket") {

    $result = [];

    $transid = $_POST['transid'];

    $message = $_POST['message'];



    $name = '';

    $email = '';

    $mobile = '';





    $offline = select_query($con, "fticket", "", "`transaction_id`='$transid' and `deletes`='0' ORDER BY `id` DESC", "", "");

    if ($offline['nr'] > 0) {

        $invoice_no = $offline['result'][0]['invoice_no'];

        $mticket_id = $offline['result'][0]['id'];

        // $ticket_no = str_replace("OT", "", $offline[result][0]['ticket_no']);

        $ticket_no = $offline['result'][0]['ticket_no'];

        $user_id = $offline['result'][0]['user_id'];

        $agent_id = $offline['result'][0]['agent_id'];

        $totalAmount = $offline['result'][0]['net_total'];

        $ag_id = $offline['result'][0]['agent_id'];
    }



    $inv_arr = array("deletes" => '1');



    $Inv_update = update($con, "invoice", "`id` = '$invoice_no' and `deletes`='0'", $inv_arr, "", "", "", "");

    $errors = $Inv_update['errors'];

    if ($errors != "") {

        $result["type"] = "0";

        $result["result"] = $errors;
    } else {

        $mticket_arr = array("deletes" => '1', "delete_reason" => $message);

        $mticket_update = update($con, "fticket", "`id` = '$mticket_id'  and `deletes`='0'", $mticket_arr, "", "", "", "");

        $errors = $mticket_update['errors'];

        if ($errors != "") {

            $result["type"] = "0";

            $result["result"] = $errors;
        } else {

            $Ticket_arr = array("deletes" => '1');

            $ticket_lines_update = update($con, "ticket_lines", "`ticket_id` = '$mticket_id' and `type` = 'FT'  and `deletes`='0'", $Ticket_arr, "", "", "", "");

            $errors = $ticket_lines_update['errors'];

            if ($errors != "") {

                $result["type"] = "0";

                $result["result"] = $errors;
            } else {

                $shi_data = select_query($con, "user_register", "", "`id`='$user_id' and `deletes`='0' ", "", "");

                if ($shi_data['nr'] > 0) {

                    $name = $shi_data['result'][0]['name'];

                    $email = $shi_data['result'][0]['email'];

                    $mobile = $shi_data['result'][0]['mobile'];
                }









                $agent_data = select_query($con, "user_register", "", "`id` = '$agent_id' and `roll_id` != '0' and `roll_id` != '1' and `roll_id` != '6' ORDER BY `id` DESC", "", "");

                if ($agent_data['nr'] > 0) {

                    $t_earning = floatval($agent_data['result'][0]['t_earning']);

                    $roll_id_new = $agent_data['result'][0]['roll_id'];





                    $a_com = select_query($con, "sales_commission", "", "`start_amt` <= '$t_earning' AND `end_amt` >= ' $t_earning' AND `roll` = '$roll_id_new' AND `deletes`='0'", "", "");

                    if ($a_com['nr'] > 0) {



                        $level_1 = floatval($a_com['result'][0]['level_1']);

                        $level_2 = floatval($a_com['result'][0]['level_2']);

                        $level_3 = floatval($a_com['result'][0]['level_3']);

                        $level_per = array();



                        if ($level_3 != 0) {

                            array_push($level_per, $level_3);
                        }

                        if ($level_2 != 0) {

                            array_push($level_per, $level_2);
                        }

                        if ($level_1 != 0) {

                            array_push($level_per, $level_1);
                        }



                        foreach ($level_per as $value) {

                            $t_earning_new = select_top_name($con, "user_register", "t_earning", "`id` = '$ag_id'", "t_earning", "");



                            $level_3_amt = $totalAmount * $value / 100;

                            $amt_3 = floatval($t_earning_new) - $level_3_amt;

                            $t_earning_arr = array("t_earning" => $amt_3);

                            $earn_update = update($con, "user_register", "`id` = '$ag_id'  and `roll_id` != '0' and `roll_id` != '1' and `roll_id` != '6' ORDER BY `id` DESC", $t_earning_arr, "", "", "", "");

                            $errors = $earn_update['errors'];

                            if ($errors != "") {

                                $result["type"] = "0";

                                $result["result"] = $errors;
                            } else {

                                $Arrde = array("type" => "debit", "order_type" => "sales", "amount" => $level_3_amt, "to_id" => $ag_id, "to_opening" => $t_earning_new, "to_closing" => $amt_3, "invoice_id" => $invoice_no, "createdon" => $dubaidate_time);

                                $erarn_trans = insert($con, "earning_transaction", "", $Arrde, "", "", "");

                                $created_by = select_top_name($con, "user_register", "created_by", "`id` = '$ag_id'  and `roll_id` != '0' and `roll_id` != '1' and `roll_id` != '6' ORDER BY `id` DESC", "created_by", "");

                                if ($created_by != '') {

                                    $ag_id = $created_by;
                                }
                            }
                        }
                    }
                }



                $smsmessages = 'Dear Customer, The earlier issued Ticket ID ' . $ticket_no . ' has been deleted due to non payment. For any queries contact 04 33 98880';



                $subject = 'Delete confirmation';

                $messages = '<div style="font-family: Helvetica,Arial,sans-serif;min-width:100%;overflow:auto;line-height:2">

                    <div style="margin:50px auto;width:70%;padding:20px 0">

                   <div style="border-bottom:1px solid #eee">

                     <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">NATIONAL DRAW</a>

                   </div>

                   <p style="font-size:1.1em">Hi ' . $name . ',</p>

                   <p>' . $smsmessages . '</p>

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

                    $messages1 = $smsmessages;

                    // $messages1 = 'WAWW!!! Congratulation you have won with National Draw 3rd Prize of AED ' . $product[result][0][rate] . ' for Draw No. ' . $winnerid . ' on 01st May 2022 Labour Day Special Draw by matching the Mix number ' . $lines3[result][$i][my3number] . '. Now you have a free entry to participate in Winners Draw for a chance to win up to 1,000,000.00 dirham, T&C applies. Good Luck!!! national Steps Big Dreams for future draws.';

                    $templateid = "";

                    sendsms($con, $mobile, $messages1, $templateid);
                }

                $result["type"] = "1";

                $result["result"] = $offline['result'][0]['ticket_no'] . "  - Ticket has been Deleted Successfully";
            }
        }
    }





    echo json_encode($result);
} else if ($method == "delete_free_ticket") {
    try {
        $result = [];
        $transid = $_POST['transid'];
        $message = $_POST['message'];
        if ($transid != '') {
            if ($message != '') {
                $checkTicket = select_query($con, "fticket", "", "`transaction_id` = '$transid' AND `deletes`='0' ORDER BY `id` DESC", "", "");
                if ($checkTicket['nr'] > 0) {
                    $ticketID = $checkTicket['result'][0]['id'];
                    $ticketArr = ["deletes" => "1", "delete_reason" => $message];
                    $ticketUpdate = update($con, "fticket", "`id` = '$ticketID' AND `transaction_id` = '$transid' AND `deletes`='0' ORDER BY `id` DESC", $ticketArr, "", "", "", "");
                    $errors = $ticketUpdate['errors'];
                    if ($errors != "") {
                        $result["type"] = "0";
                        $result["result"] = $errors;
                    } else {
                        $pointTransArr = ["deletes" => "1"];
                        $ticketLinesUpdate = update($con, "ticket_lines", "`ticket_id` = '$ticketID' AND `type` = 'FT' AND `deletes`='0' ORDER BY `id` DESC", $pointTransArr, "", "", "", "");
                        $errors = $ticketLinesUpdate['errors'];
                        if ($errors != "") {
                            $result["type"] = "0";
                            $result["result"] = $errors;
                        } else {
                            $pointTransactionArr = ["deletes" => "1"];
                            $pointTransactionUpdate = update($con, "points_transaction", "`invoice_id` = '$ticketID' AND `type` = 'FREE' AND `deletes`='0' ORDER BY `id` DESC", $pointTransactionArr, "", "", "", "");
                            $errors = $pointTransactionUpdate['errors'];
                            if ($errors != "") {
                                $result["type"] = "0";
                                $result["result"] = $errors;
                            } else {
                                $result["type"] = "1";
                                $result["result"] = "Ticket Not Found.";
                                goto Retrun;
                            }
                        }
                    }
                } else {
                    $result["type"] = "0";
                    $result["result"] = "Ticket Not Found.";
                    goto Retrun;
                }
            } else {
                $result["type"] = "0";
                $result["result"] = "Please Enter The Reason!";
                goto Retrun;
            }
        } else {
            $result["type"] = "0";
            $result["result"] = "Transaction ID Missing";
            goto Retrun;
        }
        Retrun:
        echo json_encode($result);
    } catch (Exception $e) {
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => $_SESSION['cusid'], "reason" => json_encode($error), "filename" => 'free_services.php', "draw_id" => '0', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
}
