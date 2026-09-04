<?php


include '../../include/shi-config.php';

include '../../include/functions.php';

// include '../../include/payment-config.php';
// var_dump('deva11222233333');die;

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






if ($method == "getuserdata") {

    $result = [];


    if ($draw_id == '') {
        $result["type"] = "0";

        $result["result"] = 'Active Draws Not Found. Kindly Try After Some Time!';
        goto ghjOP;
    }


    $mobile = $_POST['mobile'];

    $user = select_query($con, "user_register", "", "`mobile` = '$mobile' AND `roll_id` = '0' AND `deletes`='0'", "", "");

    if ($user['nr'] > 0) {



        $drawid = '';



        $date = date("Y-m-d H:i:s");

        // $draw_id = select_top_name($con, "draw", "id", "`deletes`='0' and `status`='Active' and `ticket_start_datetime`<'$date' and `ticket_end_datetime`>'$date'   order by `id` ASC ", "id", "");



        // $draw = select_query($con, "draw", "", "`deletes`='0' and `status`='Active' and `ticket_start_datetime`<'$date' and `ticket_end_datetime`>'$date'   order by `id` ASC", "", "");

        // if ($draw['nr'] > 0) {

        //     $drawid = $draw['result'][0]['id'];

        //     $drawname = $draw['result'][0]['name'];
        // }



        $outputscreen = '';

        $outputscreen .= '<div class="row"><div class="col-6"><h6><strong>Draw Name: </strong></h6><p>' . $drawname . '</p>';

        $outputscreen .= '<input class="form-control mt-2 form-control-sm tno" id="drawid" type="hidden" value="' . $draw_id . '" name="drawid" readonly></div>';

        $outputscreen .= '<div class="col-6"><h6><strong>Name: </strong></h6><p>' . $user['result'][0]['name'] . '</p></div></div>';







        $output = '';

        $output .= '<div class="col-md-3 p-0 d-flex" style="padding:0px; margin:0;" id="">';

        $output .= '<button class="btn-style1" onclick="addnewlines($(' . "'#totallines'" . ').val(), ' . "'sub'" . ')">-</button>';

        $output .= '<input class="form-control" type="text" id="totallines" value="0" readOnly>';

        $output .= '<button class="btn-style1" onclick="addnewlines($(' . "'#totallines'" . ').val(), ' . "'add'" . ')">+</button>';

        $output .= '</div>';





        $result['outputscreen'] = $outputscreen;

        $result['output'] = $output;

        $result["type"] = "1";

        $result["result"] = "Success!";
    } else {

        $result["type"] = "0";

        $result["result"] = "User not found!";
    }


    ghjOP:
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

        // $ticket_no = str_replace("MT-", "", $offline[result][0]['ticket_no']);

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

                        $sendmail = sendemail($con, $email, $subject, $messages);
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

    $ticket = select_query($con, "aticket", "", "(`ticket_no`='AT-$_POST[ticketid]' OR `ticket_no`='AT$_POST[ticketid]') and `deletes`='0' ", "", "");



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



        if ($_SESSION['memid'] != '') {

            $verfiyTL = [];
            $_SESSION['blid'] = '';
            $chcount = 0;
            // Log

            error_log_new($con, getUserIP(), 'agent_ticket_start', '', '', $_POST['myUser'], 'Start Time :' . $dubaidate_time, json_encode($_POST), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);

            // $drawid = $_POST['drawid'];
            $drawid =  $draw_id;
            if ($drawid == '') {
                $result["type"] = "0";
                $result["result"] = "Active Draws Not Found. Kindly Try After Some time!";
                goto result;
            }

            $myuserno = $_POST['myUser'];

            $reconfirmS = $_POST['reconfirmS'];

            $count = intval($_POST['count']);

            $omatype = 'AT';

            if ($count <= 13) {

                for ($i = 1; $i <= $count; $i++) {

                    $pn = "productid" . $i;

                    $id3 = "my3number" . $i;

                    if ($_POST[$pn] != '' && $_POST[$id3] != '') {

                        $user_register = select_query($con, "user_register", "", "`mobile` = '$myuserno' AND `deletes`='0' AND `roll_id` ='0' ORDER BY `id` DESC LIMIT 1", "", "");

                        if ($user_register['nr'] > 0) {

                            $userid = $user_register['result'][0]['id'];

                            $my3number = $_POST[$id3];

                            $productID = $_POST[$pn];

                            $checkBlockList = mysqli_query($con, "SELECT * FROM `blocked_my3number` WHERE `drawid` = '$drawid' AND `deletes` = '0' AND (`first` = '$my3number' OR `second` = '$my3number' OR `third_one` = '$my3number' OR `third_two` = '$my3number' OR `third_three` = '$my3number' OR `third_four` = '$my3number') AND `site` IN ('BOTH', 'AGENT') ORDER BY `id` DESC LIMIT 1;");

                            if ($checkBlockList) {

                                $rowCount = mysqli_num_rows($checkBlockList);

                                if ($rowCount > 0) {

                                    $mobile_no = $user_register['result'][0]['mobile'];
                                    $row = mysqli_fetch_assoc($checkBlockList);

                                    $allow_to_purchase = select_query($con, "allow_to_purchase", "", "`drawid` = '$drawid' AND `my3number` = '$my3number' AND `product_id` = '$productID' AND `deletes` = '0' AND `status` = 'UNPAID' AND `user_id` = '$userid' ORDER BY `id` DESC LIMIT 1", "", "");

                                    if ($allow_to_purchase['nr'] > 0) {
                                        if ($_SESSION['blid'] == '') {
                                            $_SESSION['blid'] = $allow_to_purchase['result'][0]['id'];
                                        }
                                        $chcount++;
                                    } else {

                                        $cartdata = json_encode($_POST);
                                        $get_block_user = select_query($con, "blocked_my3number_user", "", "`checkout` LIKE '$cartdata' AND `userid` = '$userid' AND `drawid` = '$drawid' AND `productid` = '$productID' ORDER BY `id` DESC LIMIT 1", "", "");

                                        if ($get_block_user['nr'] < 1) {
                                            $blockArr = ["userid" => $userid, "productid" => $productID, "agentid" => $_SESSION['memid'], "my3number" => $my3number, "blockid" =>   $row['id'], "payment_his_id" => '0', "mobile_no" => $mobile_no, "createdon" => $dubaidate_time, "checkout" => $cartdata, "drawid" => $drawid, "tried_to" => 1];
                                            $blocked_my3number_user = insert($con, "blocked_my3number_user", "", $blockArr, "", "", "");
                                            $idn = $blocked_my3number_user['id'];
                                        } else {
                                            $idn =  $get_block_user['result'][0]['id'];
                                            $tried_to = $get_block_user['result'][0]['tried_to'] + 1;
                                            mysqli_query($con, "UPDATE `blocked_my3number_user` SET `tried_to` = '$tried_to' WHERE `blocked_my3number_user`.`id` = $idn;");
                                        }

                                        $get_block_user_es = select_query($con, "blocked_my3number_user", "", "`userid` = '$userid' AND `drawid` = '$drawid' AND `productid` = '$productID' AND `notify_status` = '1' ORDER BY `id` DESC LIMIT 1", "", "");
                                        if ($get_block_user_es['nr'] < 1) {
                                            if ($idn != '') {
                                                $set_notify = select_query($con, "set_notify", "", "`id` IN ('1', '2') AND `type` = 'Block_My3Number' AND `deletes` = '0' ORDER BY `id` ASC", "", "");
                                                $cus_name = ucwords(strtolower(select_top_name($con, "user_register", "name", "`id`='$userid' and `deletes`='0'", "name", "")));
                                                $tURL = get_tiny_url($adminurl . '/blockmy3numbers/list/' . $userid);
                                                $cat_txt = '';
                                                if ($row['first'] == $my3number) {
                                                    $cat_txt = 'Straight';
                                                } else if ($row['second'] == $my3number) {
                                                    $cat_txt = 'Reverse';
                                                } else if ($row['third_one'] == $my3number || $row['third_two'] == $my3number || $row['third_three'] == $my3number || $row['third_four'] == $my3number) {
                                                    $cat_txt = 'Mix';
                                                }

                                                if ($set_notify['nr'] > 0) {
                                                    foreach ($set_notify['result'] as $key => $value) {



                                                        if ($value['notify_to'] == 'EMAIL') {
                                                            $emailArr = json_decode($value['notify']);
                                                            foreach ($emailArr as $email) {
                                                                $subject = 'Tried to Purchase the Blocked My 3 Number';
                                                                $messages = '<div
                                                    style="font-family: Helvetica, Arial, sans-serif;min-width: 100%;overflow: auto;line-height: 2;">
                                                    <div style="margin: 50px auto; width: 70%; padding: 20px 0">
                                                      <div style="border-bottom: 1px solid #eee">
                                                        <ahref=""style="font-size: 1.4em;color: #00466a;text-decoration: none;font-weight: 600;">NATIONAL DRAW</a>
                                                      </div>
                                                      <p style="font-size: 1.1em">Hi Team,</p>
                                                      <p>
                                                      The following Customer - ' . $cus_name . ' has tried to purchase the blocked ' . $cat_txt . '
                                                      My3Number ' . $my3number . '. Please use the below link to review the
                                                      Customers activity.
                                                      </p>
                    
                    
                                                      <h2    style="
                                                      background: #00466a;
                                                      margin: 0 auto;
                                                      width: max-content;
                                                      padding: 0 10px;
                                                      color: #fff;
                                                      border-radius: 4px;"> <a
                                                       href="' . $tURL . '"
                                                    style="color: white;
                                                    font-size: 14px;
                                                    text-decoration: none;
                                                    cursor: pointer;"
                                                       >View Details</a></h2>
                                                      <p style="font-size: 0.9em">Regards,<br />National Draw</p>
                                                      <hr style="border: none; border-top: 1px solid #eee" />
                                                      <div
                                                        style="
                                                          float: right;
                                                          padding: 8px 0;
                                                          color: #aaa;
                                                          font-size: 0.8em;
                                                          line-height: 1;
                                                          font-weight: 300;
                                                        "
                                                      ></div>
                                                    </div>
                                                  </div>
                                                  ';
                                                                if ($email != "") {
                                                                    $emailchack = explode('@', $email);
                                                                    if (strtolower($emailchack[1]) != "nationaldraw.ae") {
                                                                        $email_json = array(
                                                                            "senderName" => "Draw",
                                                                            "method" => "sendmail",
                                                                            "emailid" => $email,
                                                                            "subject" => $subject,
                                                                            "emailtemplated" => $messages,
                                                                            "log" => "1"
                                                                        );
                                                                        $email_sent = invokeApiRequest('POST', $adminurl . 'api/invokeemail.php', '', json_encode($email_json));
                                                                    }
                                                                }
                                                            }
                                                        }


                                                        if ($value['notify_to'] == 'SMS') {
                                                            $mobileArr = json_decode($value['notify']);
                                                            foreach ($mobileArr as $mobile) {
                                                                if (substr($mobile, 0, 3) == "971") {
                                                                    $messages = 'The following Customer - ' . $cus_name . ' has tried to purchase the blocked ' . $cat_txt . ' My3Number ' . $my3number . '. Please use the below link to review the Customers activity. ( ' . $tURL . ' )';
                                                                    sendsms($con, $mobile, $messages, "");
                                                                }
                                                            }
                                                        }
                                                    }

                                                    mysqli_query($con, "UPDATE `blocked_my3number_user` SET `notify_status` = '1' WHERE `blocked_my3number_user`.`id` = $idn;");
                                                }
                                            }
                                        }

                                        goto failed;






                                        // $blockArr = array("userid" => $userid, "agentid" => $_SESSION['memid'], "productid" => $productID, "my3number" => $my3number, "blockid" => $row['id'], "payment_his_id" => '0', "mobile_no" => $mobile_no, "createdon" => $dubaidate_time, "checkout" => json_encode($_POST));
                                        // $blocked_my3number_user = insert($con, "blocked_my3number_user", "", $blockArr, "", "", "");
                                        // goto failed;
                                    }
                                    if ($chcount == 1) {
                                        goto success;
                                    }
                                    failed:
                                    $result["type"] = "0";
                                    $result["result"] = "Sorry, the server is busy. Please try again later! Error 502";
                                    goto result;
                                    success:
                                }
                            }

                            $ticket_lines = select_query($con, "ticket_lines", "", "`my3number` = '$my3number'  AND `product_id` = '$productID' AND `agent_id` = '$_SESSION[memid]' AND `user_id` = '$userid' AND `draw_id` = '$drawid' AND `deletes`='0' AND `type` ='AT' ORDER BY `id` DESC LIMIT 1", "", "");

                            if ($ticket_lines['nr'] > 0) {

                                $verfiyTL[] = 1;
                            } else {

                                $verfiyTL[] = 0;
                            }
                        } else {

                            $result["type"] = "0";

                            $result["result"] = "User Not Found!";

                            goto result;
                        }
                    } else {

                        $result["type"] = "0";

                        $result["result"] = "Please fill all fields!";

                        $result["s"] = $pn;

                        goto result;
                    }
                }
            }



            $tArrayTotal = array_sum($verfiyTL);

            if ($count === $tArrayTotal) {

                if (!$reconfirmS) {

                    $result["type"] = "2";

                    $result["result"] = "Are Your Sure To create Same Ticket Again.";

                    goto result;
                }
            }



            $count = $count + 1;



            unid:

            $uniqid = 'AT' . uniqid(15);

            $achekc = select_query($con, "aticket", "", "`transaction_id`='$uniqid'", "", "");

            if ($achekc['nr'] > 0) {

                goto unid;
            }



            if ($_SESSION['memid'] == '') {

                $result["type"] = "0";

                $result["result"] = "Kindly Login and try to Create Ticket";

                goto result;
            }



            if ($myuserno == 0 || $myuserno == '0' || strlen($myuserno) < 9) {

                $result["type"] = "0";

                $result["result"] = "Kindly Enter Valid Mobile Number";

                goto result;
            }



            $userCheck = select_query($con, "user_register", "", "`mobile` = '$myuserno' AND `deletes`='0' AND `roll_id` ='0' ORDER BY `id` DESC LIMIT 1", "", "");

            if ($userCheck['nr'] < 1) {

                $result["type"] = "0";

                $result["result"] = "User Not Found!";

                goto result;
            }








            if ($drawid == '') {

                $result["type"] = "0";

                $result["result"] = "Draw Details Didn`t Receive. kindly Refresh the page and try again.";

                goto result;
            }



            $user_register = select_query($con, "user_register", "", "`mobile` = '$myuserno' AND `deletes`='0' AND `roll_id` ='0' ORDER BY `id` DESC LIMIT 1", "", "");

            if ($user_register['nr'] > 0) {

                $userid = $user_register['result'][0]['id'];

                $username = $user_register['result'][0]['name'];

                $mobile = $user_register['result'][0]['mobile'];

                $email = $user_register['result'][0]['email'];

                $address = $user_register['result'][0]['address'];

                $city = $user_register['result'][0]['city'];

                $nationality = $user_register['result'][0]['nationality'];



                $aticketArr = array("payment_transaction_id" => $uniqid, "transaction_id" => $uniqid, "deletes" => '0', "purchase_datetime" => $dubaidate_time, "createdon" => $dubaidate_time);

                $aticket = insert($con, "aticket", "", $aticketArr, "", "", "");



                if ($aticket['id'] != '') {

                    $ticketnumber = $aticket['id'];



                    $ticket = select_query($con, "aticket", "", "`ticket_no`='AT$ticketnumber' and `deletes`='0' ", "", "");

                    if ($ticket['nr'] == 0) {



                        $invoice_Arr = ["ticket_id" => $ticketnumber, "deletes" => "0", "emailid" => $email, "firstname" => $username, "createdon" => $dubaidate_time, "type" => $omatype, "address" => $con->real_escape_string($address), "city" => $con->real_escape_string($city), "country" => $con->real_escape_string($nationality)];

                        $getinvoice = insert($con, "invoice", "", $invoice_Arr, "", "", "");



                        if ($getinvoice['id'] != '') {



                            $getin = $getinvoice['id'];



                            $totalAmount = 0;

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

                                    $p = select_query($con, "product", "", "`id`='$proid' and `deletes`='0' ", "", "");



                                    if ($p['nr'] > 0) {

                                        $totalAmount += floatval($p['result'][0]['rate']);

                                        $m3n = $_POST[$lucky];

                                        $ticket_lines_arr = array("user_id" => $userid, "ticket_id" => $ticketnumber, "draw_id" => $drawid, "agent_id" => $_SESSION['memid'], "product_id" => $_POST[$pname], "orders" => $ticketnumber, "my3number" => $m3n, "raffle_id" => $rafflevalue, "invoice_no" => $getin, "type" => $omatype, "deletes" => '0', "createdon" => $dubaidate_time);

                                        $a_ticket_lines_insert = insert($con, "ticket_lines", "", $ticket_lines_arr, "", "", "");
                                    }
                                }
                            }



                            $count = $count - 1;



                            $agent_id = select_top_name($con, "user_register", "id", "`id`='$_SESSION[memid]' and `deletes`='0'", "id", "");

                            $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'", "roll_id", "");



                            $onepercen = ($totalAmount / 105);

                            $total_amount = number_format(($onepercen * 100), 2);

                            $tax_value = number_format(($totalAmount - $total_amount), 2);



                            $atic_arr = array(

                                "draw_id" => $drawid,

                                "agent_id" => $agent_id,

                                "user_id" => $userid,

                                "ticket_no" => $omatype . $ticketnumber,

                                "invoice_no" => $getin,

                                "sale_from" => $roll_id,

                                "total_amount" => $total_amount,

                                "tax_percentage" => "5.00",

                                "tax_value" => $tax_value,

                                "net_total" => $totalAmount,

                                "status" => '1',

                                "payment_by" => '1',

                                "total_lines" => $count,



                            );

                            $aticket_update = update($con, "aticket", "`id` = '$ticketnumber' and `deletes`='0'", $atic_arr, "", "", "", "");

                            $errors = $aticket_update['errors'];

                            if ($errors != "") {

                                $result["type"] = "0";

                                $result["result"] = $errors;

                                // Log

                                error_log_new($con, getUserIP(), 'agent_ticket_update_failed', '', '', $_POST['myUser'], 'Ticket Creation Field', json_encode($errors), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);
                            } else {



                                $result["id"] = str_pad($ticketnumber, 7, "0", STR_PAD_LEFT);



                                $aticket_new_id = $ticketnumber;



                                $subject = "Purchase Confirmation";



                                //new agent purchase mail template intergaration start 30-12-2022//

                                $messages =



                                    ' <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



                    <html xmlns="http://www.w3.org/1999/xhtml">



                    <head>



                     <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



                     <meta http-equiv="X-UA-Compatible" content="IE=edge" />



                     <meta name="viewport" content="width=device-width, initial-scale=1.0">



                     <title>Ticket Purchase OTP Mail Template</title>



                     <script type="text/javascript" src="https://gc.kis.v2.scr.kaspersky-labs.com/FD126C42-EBFA-4E12-B309-BB3FDD723AC1/main.js?attr=jHKHuNoFmYLbllpFdvwRKptFQ5foKxuLGDumnBZDk9tRvvK2zyU0wWgGmT2tncRiw5FWmFI9p72A0xhiHg-Xy7pckbfbKf_m2cnULrkkWSA" charset="UTF-8"></script><style type="text/css">







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



                               <img src="' . $adminurl . 'assets/images/mailtemplate/logo1.png" style="border: 0px;"  >



                             </center>



                               </td></tr></table>







                             </td></tr>



                             <!-- LOGO  -->



                                     <tr>



                                       <td class="column-one" >



                             <table align="center" class="column"> <tr><td valign="top" >



                      <div style="margin:0 auto;  max-width:500px; display:block;">



                              <div style="width:110px; float:left; ">      <img style="border: 0px;" src="' . $adminurl . 'assets/images/mailtemplate/char21.png" ></div>





                              <div  style="">



                     <h3 class="demoname"style="color: #29377d;  font-family: Arial Narrow;font-style: italic;font-size: 28px; margin: 0px; text-align: center;font-weight: 500;"> Hi, ' . $username . '



                                           <br>



                                         </h3>



                                         <p style="color: #29377d;   font-family: Arial Narrow;font-style: italic; font-size:165%;  margin: 13px 8px 13px 8px; text-align: center;">Thank you for your purchase <br>

                                        and donations </p>



                                         <h3 style="color: #29377d; font-family: Arial Narrow;  font-style: italic;font-size: 195%; margin: 0px; text-align: center;">Ticket ID # ' . $omatype . '' . $ticketnumber . '



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



                                         <span><img align="center" src="' . $adminurl . 'assets/images/mailtemplate/three.png"></span>Numbers </strong></th>



                                       <th style="padding: 12px 5px;color: #354169;font-style: italic;font-family: Arial Narrow;font-size:18px; width:25%" align="center" bgcolor="#d0dbe7"><strong>Raffle ID</strong></th>



                                     </tr>



                                     <tr>';



                                $query = select_query($con, "ticket_lines", "", "`ticket_id`='$aticket_new_id' and  `type` = '$omatype' and `ticket_id`!='' and `deletes`='0' group by `product_id` order by `product_id` ASC  ", "", "");



                                foreach ($query['result'] as $key => $valuelist) {

                                    $p_id = $valuelist['product_id'];

                                    $t_id = $valuelist['orders'];

                                    $product = select_query($con, "product", "", "`id`='$valuelist[product_id]'  ", "", "");



                                    foreach ($product['result'] as $key => $productinfo) {
                                    }

                                    $pcountlist = select_query_count($con, "ticket_lines", "id", " `ticket_id`='$aticket_new_id' and `product_id`='$valuelist[product_id]' and `type` = '$omatype' and `deletes`='0' and `orders`='$valuelist[orders]'", "", "");



                                    $mynumber = select_query($con, "ticket_lines", "", " `ticket_id`='$aticket_new_id' and `product_id`='$p_id' and `type` = '$omatype' and `orders`='$t_id' and `deletes`='0'", "", "");



                                    $messages .= '<tr>

                                           <td style=" padding: 12px 5px;color: #354169;font-style: italic;font-family: Arial Narrow;font-size:19px;" align="center" bgcolor="#ffffff"><strong style="font-weight: 800;">AED ' . number_format((float) $productinfo['rate'], 2, '.', '') . '</strong></td>';

                                    $messages .= '<td style=" padding: 12px 5px;color: #354169;font-style: italic;font-family: Arial Narrow;font-size:19px;" align="center" bgcolor="#ffffff"><strong style="font-weight: 800;">' . $mynumber['nr'] . '</strong></td>';



                                    $messages .= '<td style=" padding: 12px 5px;color: #354169;font-style: italic;font-family: Arial Narrow;font-size:19px;" align="center" bgcolor="#ffffff"><strong style="font-weight: 800;">';



                                    foreach ($mynumber['result'] as $key => $mynumber1) {

                                        $messages .= $mynumber1['my3number'] . "<br>";
                                    }



                                    $messages .= '</strong></td>';



                                    $messages .= '<td style=" padding: 12px 5px;color: #354169;font-style: italic;font-family: Arial Narrow;font-size:19px;" align="center" bgcolor="#ffffff"><strong style="font-weight: 800;">';



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



                        <table style="margin: auto; color: #000000;  font-size: medium; background-color: #fbfbfb;  border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">



                          <tbody>



                            <tr>



                              <td style="padding: 0px 10px 0px 0px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px; width:175px;" align="center" valign="top" bgcolor="#ffffff">



                                <h3 style="color: #ffffff;  font-size: 22px; margin: 0px; padding: 8px 13px 10px 14px; background: #29377d;; line-height: 1; border-radius: 5px;">



                                  <a href="' . $baseurl . 'ticket-view/' . $uniqid . '" style="color: #ffffff; text-decoration-line: none;font-style: italic;font-family: Arial Narrow;">View Ticket</a>



                                </h3>



                              </td>



                              <td style="padding: 0px 0px 0px 30px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px;width:175px;" align="center" valign="top" bgcolor="#ffffff">



                                <h3 style="color: #ffffff; font-size: 22px; margin: 0px;  padding: 8px 13px 10px 14px; background: #ffffff;color: #29377d;;  line-height: 1; border-radius: 5px;border: 1px solid;">



                                  <a href="' . $baseurl . 'invoice/' . $uniqid . '" style="color: #29377d; text-decoration-line: none;font-style: italic;font-family: Arial Narrow;">View Invoice</a>



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



                                 <img  style="width:500px !important;" src="' . $adminurl . 'assets/images/mailtemplate/final_img.png">



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



                         </table>







                       </center>







                     </body>



                    </html>';



                                $user_ip = getUserIP();

                                //new agent purchase mail template intergaration end//



                                if ($email != "") {

                                    $emailchack = explode('@', $email);

                                    if (strtolower($emailchack[1]) != "nationaldraw.ae") {

                                        // $emailsend = sendemail($con, $email, $subject, $messages);



                                        $messages2 = mysqli_real_escape_string($con, $messages);

                                        $insertlog = mysqli_query($con, "INSERT INTO `emaillog` (`details`,`subject`,`email`,`ip`,`datetime`,`status`) VALUES ('$messages2','$subject','$email','$user_ip','$dubaidate_time','0')");
                                    }
                                }



                                if (substr($mobile, 0, 3) == "971") {

                                    // $messages1 = 'Thank you for your purchase and donations. Ticket ID #' . $omatype . '' . $ticketnumber . '.';

                                    $messages1 = 'Ticket ID #' . $omatype . '' . $ticketnumber . '.';

                                    $query = select_query($con, "ticket_lines", "", "`ticket_id`='$aticket_new_id' and `type` = '$omatype' and `ticket_id`!='' group by `product_id` order by `product_id` ASC  ", "", "");



                                    foreach ($query['result'] as $key => $valuelist) {

                                        $p_id = $valuelist['product_id'];

                                        $t_id = $valuelist['orders'];

                                        $product = select_query($con, "product", "", "`id`='$valuelist[product_id]'  ", "", "");

                                        $messages1 .= 'CAT-AED ' . round($product['result'][0]['rate']) . '. ';



                                        $rcount = count($mynumber['result']);



                                        $io = 1;

                                        $mynumber = select_query($con, "ticket_lines", "", "`ticket_id`='$aticket_new_id' and `product_id`='$p_id' and `type` = '$omatype' and `orders`='$t_id'", "", "");



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



                                    $messages1 .= 'for a Total Amounts of AED ' . number_format((float) $totalAmount, 2, '.', '') . ' for more info. ( ' . $printurl . ' ),. TC apply.';

                                    $templateid = "";

                                    // sendsms($con, $mobile, $messages1, $templateid);



                                    // Create SMS Log

                                    $log = mysqli_query($con, "INSERT INTO `smslog` (`gateway`, `details`,`mobile`,`ip`,`datetime`,`status`, `smssendstatus`) VALUES ('', '$messages1','$mobile','$user_ip','$dubaidate_time','', '0')");
                                }



                                $t = get_tiny_url($baseurl . 'ticket-view/' . $uniqid);

                                $in = get_tiny_url($baseurl . 'invoice/' . $uniqid);



                                $action = '';

                                $action .= '<div class="g-2">';

                                $action .= '<a target="_blank" href="' . $in . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span class="fa fa-file-text-o fs-14"></span></a>';

                                $action .= '<a target="_blank" href="' . $t . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span class="fa fa-files-o fs-14"></span></a>';

                                $action .= '</div>';



                                // Agent Commission

                                if ($totalAmount != '') {

                                    $ag_data = select_query($con, "user_register", "", "`id` = '$agent_id' AND `deletes`='0'", "", "");

                                    if ($ag_data['nr'] > 0) {

                                        $ag_id = $ag_data['result'][0]['id'];



                                        $t_point = floatval($ag_data['result'][0]['t_point']);

                                        $UT_point = $t_point - $totalAmount;



                                        $point_arr = array("t_point" => $UT_point);

                                        $point_update = update($con, "user_register", "`id` = '$ag_id' and `deletes`='0'", $point_arr, "", "", "", "");

                                        $errors = $point_update['errors'];

                                        if ($errors != "") {

                                            $result["type"] = "0";

                                            $result["result"] = $errors;
                                        } else {

                                            $nowpoints = select_top_name($con, "ldbank", "points", "`deletes`='0' and `id`='9999999'  ", "points", "");

                                            $balancepoint = $nowpoints + $totalAmount;



                                            $ld_arr = array("points" => $balancepoint);

                                            $ld_update = update($con, "ldbank", "`deletes`='0' and `id`='9999999'", $ld_arr, "", "", "", "");

                                            $errors = $ld_update['errors'];

                                            if ($errors != "") {

                                                $result["type"] = "0";

                                                $result["result"] = $errors;
                                            } else {

                                                $Arrd = array("from_id" => "$ag_id", "type" => "sales", "points" => $totalAmount, "from_opening" => $t_point, "from_closing" => $UT_point, "to_id" => "9999999", "to_opening" => $nowpoints, "to_closing" => $balancepoint, "invoice_id" => $getin, "createdon" => $dubaidate_time);

                                                $point_trans = insert($con, "points_transaction", "", $Arrd, "", "", "");



                                                $agent_data = select_query($con, "user_register", "", "`id` = '$agent_id' AND `deletes`='0'", "", "");

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

                                                            $deletes = select_top_name($con, "user_register", "deletes", "`id` = '$ag_id'", "deletes", "");

                                                            if (intval($deletes) == 0) {

                                                                $level_3_amt = $totalAmount * $value / 100;

                                                                $amt_3 = floatval($t_earning_new) + $level_3_amt;

                                                                $t_earning_arr = array("t_earning" => $amt_3);

                                                                $earn_update = update($con, "user_register", "`id` = '$ag_id' and `deletes`='0'", $t_earning_arr, "", "", "", "");

                                                                $errors = $earn_update['errors'];

                                                                if ($errors != "") {

                                                                    $result["type"] = "0";

                                                                    $result["result"] = $errors;
                                                                } else {

                                                                    $Arrde = array("type" => "earn", "order_type" => "sales", "amount" => $level_3_amt, "to_id" => $ag_id, "to_opening" => $t_earning_new, "to_closing" => $amt_3, "invoice_id" => $getin, "createdon" => $dubaidate_time);

                                                                    $erarn_trans = insert($con, "earning_transaction", "", $Arrde, "", "", "");

                                                                    $created_by = select_top_name($con, "user_register", "created_by", "`id` = '$ag_id' AND `deletes`='0'", "created_by", "");

                                                                    if ($created_by != '') {

                                                                        $ag_id = $created_by;
                                                                    }
                                                                }
                                                            } else {

                                                                $created_by = select_top_name($con, "user_register", "created_by", "`id` = '$ag_id'", "created_by", "");

                                                                if ($created_by != '') {

                                                                    $ag_id = $created_by;
                                                                }
                                                            }
                                                        }

                                                        $result["type"] = "1";

                                                        $result["result"] = "The Created Agent Ticket has been Sent Successfully.";
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }



                                // LOG

                                error_log_new($con, getUserIP(), 'agent_ticket_creation_success', '', '', $_POST['myUser'], 'Ticket Creation Finished', json_encode($_POST), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);

                                $blid = $_SESSION['blid'];

                                if ($blid != '') {
                                    $paynid = $ticketnumber;
                                    $update_s = mysqli_query($con, "UPDATE `allow_to_purchase` SET `status` = 'PAID', `newpaymentid` = '$paynid'  WHERE `allow_to_purchase`.`id` = $blid  AND `allow_to_purchase`.`deletes` = '0' AND `allow_to_purchase`.`status` = 'UNPAID';");
                                    unset($_SESSION['blid']);
                                }

                                // $result["type"] = "1";

                                // $result["result"] = "The Created Agent Ticket has been Sent Successfully.";



                                $result["amount"] = $totalAmount;

                                $result["mobile"] = $mobile;

                                $result["qty"] = $count;

                                $result["ticketno"] = $ticketnumber;

                                $result["userid"] = $userid;

                                $result["name"] = $username;

                                $result["action"] = $action;
                            }
                        } else {

                            $result["type"] = "0";

                            $result["result"] = "Invoice Generation Field. Please Contact to Admin.";

                            goto result;
                        }

                        // }

                    } else {

                        $result["type"] = "0";

                        $result["result"] = "Ticket Found!";

                        goto result;
                    }
                } else {

                    // Log

                    error_log_new($con, getUserIP(), 'agent_ticket_failed', '', '', $_POST['myUser'], 'Ticket Creation Field', json_encode($con), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);



                    $result["type"] = "0";

                    $result["result"] = "Ticket Creation Field";

                    goto result;
                }
            } else {

                $result["type"] = "0";

                $result["result"] = "User Not Found!";

                goto result;
            }
        } else {

            // Log

            error_log_new($con, getUserIP(), 'session_not_found', '', '', '', 'Start Time :' . $dubaidate_time, json_encode($_POST), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);



            $result["type"] = "0";

            $result["result"] = "Kindly Refresh the Page Try Again";

            goto result;
        }



        result:

        echo json_encode($result);
    } catch (Exception $e) {

        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];

        // Log

        error_log_new($con, getUserIP(), 'agent_ticket_catch_error', '', '', $_POST['myUser'], 'Catch Time: ' . $dubaidate_time, json_encode($error), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);
    }
} else if ($method == 'previewmticket') {

    $result = [];

    $omatype = 'AT';
    $totalAmount = 0;
    $count = intval($_POST['count']);



    $balance_point = select_top_name($con, "user_register", "t_point", "`id`='$_SESSION[memid]' and `deletes`='0'", "t_point", "");

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
        $lname = $row['lname'];
        $full_name = $username . ' ' . $lname;
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

    $output .= $full_name;

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

                                                                                                                        AED ' . number_format($hprize1, 2) . '

                                                                                                                    </td>

                                                                                                                    <td style="text-align: center; width: 33%;">

                                                                                                                        AED ' . number_format($hprize2, 2) . '

                                                                                                                    </td>

                                                                                                                    <td style="text-align: center; width: 33%;">

                                                                                                                        AED ' . number_format($hprize3, 2) . '

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

                $output .= 'AED' . number_format((float) $row['rate'], 2, '.', '');

                $output .= '</td>';

                $procount = 0;

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

                        <td colspan="4" style="text-align: center;"><br>' . (($draw_id != '') ? 'Just3 Draw Date: ' . date("d M Y", strtotime($drawresultdate1))  : '') . '<br> ' . (($ssdraw_id != '') ? 'Super Raffle Draw Date: ' . date("d M Y", strtotime($sdrawresultdate1))  : (($raffle_status != 'Pending') ? 'Just3 Raffle Draw Date: ' . date("d M Y", strtotime($drawresultdate1)) : ''))  . '<br>' . (!checkGrandRaffleEligible($draw_no) ?  ('Grand Raffle Draw Date: ' . raffleDrawDate($con, $dubaidate_time, 'd M Y')) : '') . '</td>

                    </tr>



    ';



    $output .= '<tr>

                                <td colspan="4" style="text-align: center;">

                                    All Other Terms and Conditions Apply

                                </td>

                            </tr>

                        </tbody>

                        </table>';



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

                    <p style="color: #29377d !important;font-size: 15px !important;margin: 0px !important;text-align: center !important;font-weight: 500 !important;font-style: italic !important;font-family: Arial Narrow !important;margin: 8px 0px 0px 0px !important;">Note: This is a system auto generated email. Please do not reply to this mail.<br>
                    
                    For Clarification
                    
                     
                    
                           <br>
                    
                    Call 04 33 98880 Whatsapp +971 56 199 1271
                    
                    <br>
                    
                    or email support@nationaldraw.com</p>

          </div>

           </div>

           </div>';

            // $email = 'prakashsp8421@gmail.com';

            if ($email != "") {



                $emailchack = explode('@', $email);

                if (strtolower($emailchack[1]) != "nationaldraw.ae") {

                    $sendemail = sendemail($con, $email, $subject, $messages);
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

    $omatype = 'AT';



    $ticketnumber = select_top_name($con, "aticket", "ticket_no", "`transaction_id`='$transid' and `deletes`='0'", "ticket_no", "");

    $aticket_new_id = select_top_name($con, "aticket", "id", "`transaction_id`='$transid' and `deletes`='0'", "id", "");

    $user_id = select_top_name($con, "aticket", "user_id", "`transaction_id`='$transid' and `deletes`='0'", "user_id", "");

    $mobile = select_top_name($con, "user_register", "mobile", "`id`='$user_id' and `deletes`='0'", "mobile", "");

    $totalAmount = select_top_name($con, "aticket", "net_total", "`transaction_id`='$transid' and `deletes`='0'", "net_total", "");

    if (substr($mobile, 0, 3) == "971") {

        $messages1 = 'Ticket ID #' . $ticketnumber . '. ';



        $query = select_query($con, "ticket_lines", "", "`ticket_id`='$aticket_new_id' and `type` = '$omatype' and `ticket_id`!='' group by `product_id` order by `product_id` ASC  ", "", "");



        foreach ($query['result'] as $key => $valuelist) {

            $p_id = $valuelist['product_id'];

            $t_id = $valuelist['orders'];

            $product = select_query($con, "product", "", "`id`='$valuelist[product_id]'  ", "", "");

            $messages1 .= 'CAT-AED ' . round($product['result'][0]['rate']) . '. ';



            $io = 1;

            $mynumber = select_query($con, "ticket_lines", "", "`ticket_id`='$aticket_new_id' and `product_id`='$p_id' and `type` = '$omatype' and `orders`='$t_id'", "", "");

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
    
    // var_dump('welcome');die;

    $result = [];

    $userid = $_REQUEST['userid'];

    $reason = $_REQUEST['reason'];
    
    // var_dump($userid,$reason);die;


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

                // $point_trans = insert($con, "points_transaction", "", $Arrd, "", "", "");



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

                        // $point_update3 = update($con, "point_request", "`request_id` = '$pointrequestid' and `deletes`='0'", $point_arr3, "", "", "", "");

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



    $offline = select_query($con, "aticket", "", "`transaction_id`='$transid' and `deletes`='0' ORDER BY `id` DESC", "", "");

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

        $mticket_update = update($con, "aticket", "`id` = '$mticket_id'  and `deletes`='0'", $mticket_arr, "", "", "", "");

        $errors = $mticket_update['errors'];

        if ($errors != "") {

            $result["type"] = "0";

            $result["result"] = $errors;
        } else {

            $Ticket_arr = array("deletes" => '1');

            $ticket_lines_update = update($con, "ticket_lines", "`ticket_id` = '$mticket_id' and `type` = 'AT'  and `deletes`='0'", $Ticket_arr, "", "", "", "");

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



                        $sendmail = sendemail($con, $email, $subject, $messages);
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
}
