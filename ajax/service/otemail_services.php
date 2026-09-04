<?php

// Date        Developer     Changes

// 24-1-2023   Prakash       Email validation Updated
// 27-04-2023  sathiya       reversal ticket function worked

include '../../include/shi-config.php';
include '../../include/functions.php';
include '../../include/payment-config.php';

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";

$type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : "";

$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST["tabID"] : "";

if ($type == 'agent') {

    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($_REQUEST[role]) AND" : "";
} else {

    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$_REQUEST[role]' AND" : "";
}

$headers = apache_request_headers();

//print_r($headers);

$result = array();

$post_csrf = $headers['X-Csrf-Token'] ?? '';

// if(isset($_COOKIE["cookie_csrf_token"]) && $_COOKIE["cookie_csrf_token"]!=""){

//         $_SESSION['csrf_token']=$_COOKIE["cookie_csrf_token"];

// }

//echo "bbbbbbb".$_SESSION["csrf_token"];

//echo "aaaaaaa".$post_csrf;

//exit;

// if (!isset($post_csrf) || !isset($_SESSION["csrf_token"]) || $_SESSION["csrf_token"] == "" || $post_csrf == "") {

//     unset($_SESSION["csrf_token"]);

//     session_destroy();

//     divert($adminurl . 'login1');

// }

// if ($post_csrf != $_SESSION["csrf_token"]) {

//     unset($_SESSION["csrf_token"]);

//     unset($_COOKIE['csrf_token']);

//     session_destroy();

//     divert($adminurl . 'logout.php');

// }

// exit;

// echo $method;

// exit;

if ($method == "deleteo_Ticket") {

    $result = [];

    $transid = $_POST['transid'];

    $message = $_POST['message'];

    $name = '';

    $email = '';

    $mobile = '';

    $offline = select_query($con, "ticket", "", "`transaction_id`='$transid' and `deletes`='0' ", "", "");

    if ($offline['nr'] > 0) {

        $invoice_no = $offline['result'][0]['invoice_no'];

        $mticket_id = $offline['result'][0]['id'];

        // $ticket_no = str_replace("OT", "", $offline[result][0]['ticket_no']);

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

        $mticket_update = update($con, "ticket", "`id` = '$mticket_id'  and `deletes`='0'", $mticket_arr, "", "", "", "");

        $errors = $mticket_update['errors'];

        if ($errors != "") {

            $result["type"] = "0";

            $result["result"] = $errors;
        } else {

            $Ticket_arr = array("deletes" => '1');

            $ticket_lines_update = update($con, "ticket_lines", "`ticket_id` = '$mticket_id' and `type` = 'OT'  and `deletes`='0'", $Ticket_arr, "", "", "", "");

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

                // $smsmessages = 'Dear Customer, The earlier issued Ticket ID ' . $ticket_no . '  has been deleted due to double ticket generation. For any queries contact 04 33 98880';

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

                // if ($sendmail) {

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
} else if ($method == "sendemailtopurchase") {

    $transid = $_REQUEST['transid'];

    $ottype = $_REQUEST['ottype'];

    $tablename = $_REQUEST['tablename'];

    $result = sendpurchaseemail($con, $transid, $ottype, $tablename, $baseurl, $adminurl,  $draw_no, $dubaidate_time);

    echo json_encode($result);
} else if ($method == "sendsmstopurchase") {

    $transid = $_REQUEST['transid'];

    $omatype = $_REQUEST['ottype'];

    $tablename = $_REQUEST['tablename'];

    $result = sendsmstopurchase($con, $omatype, $tablename, $transid, $baseurl);

    echo json_encode($result);
} else if ($method == "bothemailsms") {

    $transid = $_REQUEST['transid'];

    $omatype = $_REQUEST['ottype'];

    $tablename = $_REQUEST['tablename'];

    $result[] = sendpurchaseemail($con, $transid, $ottype, $tablename, $baseurl, $adminurl,  $draw_no, $dubaidate_time);

    $result[] = sendsmstopurchase($con, $omatype, $tablename, $transid, $baseurl);

    echo json_encode($result);
} else if ($method == "delete_wt_Ticket") {

    $result = [];
    $transid = $_POST['transactionid'];
    $message = $_POST['reason'];
    $type = $_POST['type'];
    $name = '';
    $email = '';
    $mobile = '';

    if ($type == 'WT') {

        $wticket = select_query($con, "wticket", "", "`transaction_id`='$transid' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
        if ($wticket['nr'] > 0) {
            $invoice_no = $wticket['result'][0]['invoice_no'];
            $mticket_id = $wticket['result'][0]['id'];
            $ticket_no = $wticket['result'][0]['ticket_no'];
            $user_id = $wticket['result'][0]['user_id'];
            $mticket_arr = array("deletes" => '1', "delete_reason" => $message);
            $mticket_update = update($con, "wticket", "`id` = '$mticket_id'  and `deletes`='0'", $mticket_arr, "", "", "", "");
            $errors = $mticket_update['errors'];
            if ($errors != "") {
                $result["type"] = "0";
                $result["result"] = $errors;
            } else {
                $payment_check = select_query($con, "payment_history", "", "`transaction_id`='$transid' and `status`='1' ORDER BY `id` DESC LIMIT 1", "", "");
                $wallet_amt = $payment_check['result'][0]['finaltotal'];
                $user_wallet = select_query($con, "user_register", "", "`id`='$user_id' and `deletes`='0' ", "", "");
                $current_wallet = $user_wallet['result'][0]['t_earning'];
                $totalamt = $wallet_amt + $current_wallet;
                $wallet_update_arr = array("t_earning" => $totalamt);
                $wallet_total_update = update($con, "user_register", "`id` = '$user_id' AND `deletes` = '0'", $wallet_update_arr, "", "", "", "");
                $errors = $wallet_total_update['errors'];

                if ($errors != "") {
                    $result["type"] = "0";
                    $result["result"] = $errors;
                } else {
                    $wallet_logarr = array("transaction_id" => $transid, "amount" => $wallet_amt, "user_id" => $user_id, "ticket_id" => $mticket_id, "transaction_type" => 'Credit', "type" => 'deleted', "deletes" => '0', "createdon" => $dubaidate_time);
                    $wallet_log_ins = insert($con, "wticket_deletelog", "", $wallet_logarr, "", "", "");
                    $errors = $wallet_log_ins['errors'];
                    if ($errors != "") {
                        $result["type"] = "0";
                        $result["result"] = $errors;
                    } else {
                        $ticket_line_arr = array("deletes" => '1');
                        $ticket_lines_update = update($con, "ticket_lines", "`ticket_id` = '$mticket_id' and `type` = 'WT' and `deletes`='0'", $ticket_line_arr, "", "", "", "");
                        $errors = $ticket_lines_update['errors'];
                        if ($errors != "") {
                            $result["type"] = "0";
                            $result["result"] = $errors;
                        } else {
                            $inv_arr = array("deletes" => '1');
                            $Inv_update = update($con, "invoice", "`id` = '$invoice_no' and `deletes`='0'", $inv_arr, "", "", "", "");
                            $errors = $Inv_update['errors'];
                            if ($errors != '') {
                                $result["type"] = "0";
                                $result["result"] = $errors;
                            } else {
                                $pointTransactionArr = ["deletes" => "1"];
                                $pointTransactionUpdate = update($con, "points_transaction", "`invoice_id` = '$mticket_id' AND `type` = 'wallet' ORDER BY `id` DESC", $pointTransactionArr, "", "", "", "");
                                $errors = $pointTransactionUpdate['errors'];
                                if ($errors != '') {
                                    $result["type"] = "0";
                                    $result["result"] = $errors;
                                } else {
                                    $payment_history_arr = array("status" => '0');
                                    $payment_history_update = update($con, "payment_history", "`transaction_id` = '$transid' AND `status` = '1'", $payment_history_arr, "", "", "", "");
                                    $errors = $payment_history_update['errors'];
                                    if ($errors != "") {
                                        $result["type"] = "0";
                                        $result["result"] = $errors;
                                    } else {

                                        $result["type"] = "1";
                                        $result["result"] = "Ticket Deleted Successfully. Ticket ID: " . $ticket_no;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $result["type"] = "0";
            $result["result"] = "Ticket Not Found!";
            goto Firesult;
        }
    } else if ($type == 'OT') {
        $wticket = select_query($con, "ticket", "", "`transaction_id`='$transid' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
        if ($wticket['nr'] > 0) {
            $invoice_no = $wticket['result'][0]['invoice_no'];
            $mticket_id = $wticket['result'][0]['id'];
            $ticket_no = $wticket['result'][0]['ticket_no'];
            $user_id = $wticket['result'][0]['user_id'];
            $mticket_arr = array("deletes" => '1', "delete_reason" => $message);
            $mticket_update = update($con, "ticket", "`id` = '$mticket_id'  and `deletes`='0'", $mticket_arr, "", "", "", "");
            $errors = $mticket_update['errors'];
            if ($errors != "") {
                $result["type"] = "0";
                $result["result"] = $errors;
            } else {
                $payment_check = select_query($con, "payment_history", "", "`transaction_id`='$transid' and `status`='1' ORDER BY `id` DESC LIMIT 1", "", "");
                $wallet_amt = $payment_check['result'][0]['finaltotal'];
                $user_wallet = select_query($con, "user_register", "", "`id`='$user_id' and `deletes`='0' ", "", "");
                $current_wallet = $user_wallet['result'][0]['t_earning'];
                $totalamt = $wallet_amt + $current_wallet;
                $wallet_update_arr = array("t_earning" => $totalamt);
                $wallet_total_update = update($con, "user_register", "`id` = '$user_id' AND `deletes` = '0'", $wallet_update_arr, "", "", "", "");
                $errors = $wallet_total_update['errors'];

                if ($errors != "") {
                    $result["type"] = "0";
                    $result["result"] = $errors;
                } else {
                    $wallet_logarr = array("transaction_id" => $transid, "amount" => $wallet_amt, "user_id" => $user_id, "ticket_id" => $mticket_id, "transaction_type" => 'Credit', "type" => 'deleted', "deletes" => '0', "createdon" => $dubaidate_time);
                    $wallet_log_ins = insert($con, "wticket_deletelog", "", $wallet_logarr, "", "", "");
                    $errors = $wallet_log_ins['errors'];
                    if ($errors != "") {
                        $result["type"] = "0";
                        $result["result"] = $errors;
                    } else {
                        $ticket_line_arr = array("deletes" => '1');
                        $ticket_lines_update = update($con, "ticket_lines", "`ticket_id` = '$mticket_id' and `type` = 'OT' and `deletes`='0'", $ticket_line_arr, "", "", "", "");
                        $errors = $ticket_lines_update['errors'];
                        if ($errors != "") {
                            $result["type"] = "0";
                            $result["result"] = $errors;
                        } else {
                            $inv_arr = array("deletes" => '1');
                            $Inv_update = update($con, "invoice", "`id` = '$invoice_no' and `deletes`='0'", $inv_arr, "", "", "", "");
                            $errors = $Inv_update['errors'];
                            if ($errors != '') {
                                $result["type"] = "0";
                                $result["result"] = $errors;
                            } else {

                                $pointTransactionArr = ["deletes" => "1"];
                                $pointTransactionUpdate = update($con, "points_transaction", "`invoice_id` = '$mticket_id' AND `type` = 'wallet' ORDER BY `id` DESC", $pointTransactionArr, "", "", "", "");
                                $errors = $pointTransactionUpdate['errors'];
                                if ($errors != '') {
                                    $result["type"] = "0";
                                    $result["result"] = $errors;
                                } else {
                                    $payment_history_arr = array("status" => '0');
                                    $payment_history_update = update($con, "payment_history", "`transaction_id` = '$transid' AND `status` = '1'", $payment_history_arr, "", "", "", "");
                                    $errors = $payment_history_update['errors'];
                                    if ($errors != "") {
                                        $result["type"] = "0";
                                        $result["result"] = $errors;
                                    } else {

                                        $result["type"] = "1";
                                        $result["result"] = "Ticket Deleted Successfully. Ticket ID: " . $ticket_no;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $result["type"] = "0";
            $result["result"] = "Ticket Not Found!";
            goto Firesult;
        }
    } else {
        $result["type"] = "0";
        $result["result"] = "Please Refresh the Page And Try Again.";
        goto Firesult;
    }
    Firesult:
    echo json_encode($result);
} else if ($method == "unDeleteo_Ticket") {

    $result = [];

    $transid = $_POST['transid'];

    $message = $_POST['message'];
    $type = $_POST['type'];
    $name = '';

    $email = '';

    $mobile = '';

    if ($type == 'OT') {

        $offline = select_query($con, "ticket", "", "`transaction_id`='$transid' ", "", "");

        if ($offline['nr'] > 0) {

            $invoice_no = $offline['result'][0]['invoice_no'];

            $mticket_id = $offline['result'][0]['id'];


            $ticket_no = $offline['result'][0]['ticket_no'];

            $user_id = $offline['result'][0]['user_id'];
        }

        $inv_arr = array("deletes" => '0');

        $Inv_update = update($con, "invoice", "`id` = '$invoice_no' ", $inv_arr, "", "", "", "");

        $errors = $Inv_update['errors'];

        if ($errors != "") {

            $result["type"] = "0";

            $result["result"] = $errors;
        } else {

            $mticket_arr = array("deletes" => '0', "delete_reason" => $message);

            $mticket_update = update($con, "ticket", "`id` = '$mticket_id'", $mticket_arr, "", "", "", "");

            $errors = $mticket_update['errors'];

            if ($errors != "") {

                $result["type"] = "0";

                $result["result"] = $errors;
            } else {

                $Ticket_arr = array("deletes" => '0');

                $ticket_lines_update = update($con, "ticket_lines", "`ticket_id` = '$mticket_id' and `type` = 'OT'", $Ticket_arr, "", "", "", "");

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



                    $result["type"] = "1";

                    $result["result"] = $offline['result'][0]['ticket_no'] . "  - Ticket has been recovered Successfully";
                    // } else {

                    //   $result["type"] = "0";

                    //   $result["result"] = "Email not send!";
                    // }
                }
            }
        }
    } else if ($type == 'MT') {
    } else if ($type == 'FT') {
        $checkTicket = select_query($con, "fticket", "", "`transaction_id` = '$transid' ORDER BY `id` DESC", "", "");
        if ($checkTicket['nr'] > 0) {
            $ticketID = $checkTicket['result'][0]['id'];
            $ticketArr = ["deletes" => "0", "delete_reason" => $message];
            $ticketUpdate = update($con, "fticket", "`id` = '$ticketID' AND `transaction_id` = '$transid' ORDER BY `id` DESC", $ticketArr, "", "", "", "");
            $errors = $ticketUpdate['errors'];
            if ($errors != "") {
                $result["type"] = "0";
                $result["result"] = $errors;
            } else {
                $pointTransArr = ["deletes" => "0"];
                $ticketLinesUpdate = update($con, "ticket_lines", "`ticket_id` = '$ticketID' AND `type` = 'FT'  ORDER BY `id` DESC", $pointTransArr, "", "", "", "");
                $errors = $ticketLinesUpdate['errors'];
                if ($errors != "") {
                    $result["type"] = "0";
                    $result["result"] = $errors;
                } else {
                    $pointTransactionArr = ["deletes" => "0"];
                    $pointTransactionUpdate = update($con, "points_transaction", "`invoice_id` = '$ticketID' AND `type` = 'FREE' ORDER BY `id` DESC", $pointTransactionArr, "", "", "", "");
                    $errors = $pointTransactionUpdate['errors'];
                    $result["type"] = "1";
                    $result["result"] = $offline['result'][0]['ticket_no'] . "  - Ticket has been recovered Successfully";
                }
            }
        } else {
            $result["type"] = "0";
            $result["result"] = "Ticket Not Found.";
        }
    } else if ($type == 'AT') {
        $result = [];
        $transid = $_POST['transid'];
        $message = $_POST['message'];

        $name = '';
        $email = '';
        $mobile = '';

        $offline = select_query($con, "aticket", "", "`transaction_id`='$transid' ORDER BY `id` DESC", "", "");
        if ($offline['nr'] > 0) {
            $invoice_no = $offline['result'][0]['invoice_no'];
            $ticket_id = $offline['result'][0]['id'];

            // $ticket_no = str_replace("OT", "", $offline[result][0]['ticket_no']);
            $ticket_no = $offline['result'][0]['ticket_no'];
            $user_id = $offline['result'][0]['user_id'];
            $agent_id = $offline['result'][0]['agent_id'];
            $totalAmount = $offline['result'][0]['net_total'];
            $ag_id = $offline['result'][0]['agent_id'];
        }

        $inv_arr = array("deletes" => '0');

        $Inv_update = update($con, "invoice", "`id` = '$invoice_no'", $inv_arr, "", "", "", "");
        $errors = $Inv_update['errors'];
        if ($errors != "") {
            $result["type"] = "0";
            $result["result"] = $errors;
        } else {
            $aticket_arr = array("deletes" => '0', "delete_reason" => $message);
            $aticket_update = update($con, "aticket", "`id` = '$ticket_id' ", $aticket_arr, "", "", "", "");
            $errors = $mticket_update['errors'];
            if ($errors != "") {
                $result["type"] = "0";
                $result["result"] = $errors;
            } else {
                $Ticket_arr = array("deletes" => '0');
                $ticket_lines_update = update($con, "ticket_lines", "`ticket_id` = '$ticket_id' and `type` = 'AT' ", $Ticket_arr, "", "", "", "");
                $errors = $ticket_lines_update['errors'];
                if ($errors != "") {
                    $result["type"] = "0";
                    $result["result"] = $errors;
                } else {
                    $result["type"] = "1";

                    $result["result"] = $offline['result'][0]['ticket_no'] . "  - Ticket has been recovered Successfully";
                }
            }
        }
    } else if ($type == 'CT') {
        $checkTicket = select_query($con, "cticket", "", "`transaction_id` = '$transid' ORDER BY `id` DESC", "", "");
        if ($checkTicket['nr'] > 0) {
            $ticketID = $checkTicket['result'][0]['id'];
            $ticketArr = ["deletes" => "0", "delete_reason" => $message];
            $ticketUpdate = update($con, "cticket", "`id` = '$ticketID' AND `transaction_id` = '$transid' ORDER BY `id` DESC", $ticketArr, "", "", "", "");
            $errors = $ticketUpdate['errors'];
            if ($errors != "") {
                $result["type"] = "0";
                $result["result"] = $errors;
            } else {
                $pointTransArr = ["deletes" => "0"];
                $ticketLinesUpdate = update($con, "ticket_lines", "`ticket_id` = '$ticketID' AND `type` = 'CT'  ORDER BY `id` DESC", $pointTransArr, "", "", "", "");
                $errors = $ticketLinesUpdate['errors'];
                if ($errors != "") {
                    $result["type"] = "0";
                    $result["result"] = $errors;
                } else {
                    $pointTransactionArr = ["deletes" => "0"];
                    $pointTransactionUpdate = update($con, "points_transaction", "`invoice_id` = '$ticketID' AND `type` = 'COUPON' ORDER BY `id` DESC", $pointTransactionArr, "", "", "", "");
                    $errors = $pointTransactionUpdate['errors'];

                    $result["type"] = "1";

                    $result["result"] = $offline['result'][0]['ticket_no'] . "  - Ticket has been recovered Successfully";
                }
            }
        } else {
            $result["type"] = "0";
            $result["result"] = "Ticket Not Found.";
        }
    } else if ($type == 'WT' || $type == 'oldwallet') {

        $wticket = select_query($con, "wticket", "", "`transaction_id`='$transid' ORDER BY `id` DESC ", "", "");
        if ($wticket['nr'] > 0) {
            $invoice_no = $wticket['result'][0]['invoice_no'];
            $mticket_id = $wticket['result'][0]['id'];
            $ticket_no = $wticket['result'][0]['ticket_no'];
            $user_id = $wticket['result'][0]['user_id'];
            $mticket_arr = array("deletes" => '0', "delete_reason" => $message);
            $mticket_update = update($con, "wticket", "`id` = '$mticket_id' AND `transaction_id` = '$transid' ORDER BY `id` DESC ", $mticket_arr, "", "", "", "");
            $errors = $mticket_update['errors'];
            if ($errors != "") {
                $result["type"] = "0";
                $result["result"] = $errors;
            } else {
                $payment_check = select_query($con, "payment_history", "", "`transaction_id`='$transid' ORDER BY `id` DESC ", "", "");
                $wallet_amt = $payment_check['result'][0]['finaltotal'];
                $user_wallet = select_query($con, "user_register", "", "`id`='$user_id' and `deletes`='0' ", "", "");
                $current_wallet = $user_wallet['result'][0]['t_earning'];
                if ($current_wallet == '0') {
                    $result["type"] = "0";
                    $result["result"] = "Wallet Amount Not Found";
                } else {
                    $totalamt = $current_wallet - $wallet_amt;
                    $wallet_update_arr = array("t_earning" => $totalamt);
                    $wallet_total_update = update($con, "user_register", "`id` = '$user_id' AND `deletes` = '0'", $wallet_update_arr, "", "", "", "");
                    $errors = $wallet_total_update['errors'];

                    if ($errors != "") {
                        $result["type"] = "0";
                        $result["result"] = $errors;
                    } else {
                        $wallet_logarr = array("transaction_id" => $transid, "amount" => $wallet_amt, "user_id" => $user_id, "ticket_id" => $mticket_id, "transaction_type" => 'Debit', "type" => 'deleted', "deletes" => '0', "createdon" => $dubaidate_time);
                        $wallet_log_ins = insert($con, "wticket_deletelog", "", $wallet_logarr, "", "", "");
                        $errors = $wallet_log_ins['errors'];
                        if ($errors != "") {
                            $result["type"] = "0";
                            $result["result"] = $errors;
                        } else {
                            $ticket_line_arr = array("deletes" => '0');
                            $ticket_lines_update = update($con, "ticket_lines", "`ticket_id` = '$mticket_id' and `type` = 'WT' ORDER BY `id` DESC ", $ticket_line_arr, "", "", "", "");
                            $errors = $ticket_lines_update['errors'];
                            if ($errors != "") {
                                $result["type"] = "0";
                                $result["result"] = $errors;
                            } else {
                                $inv_arr = array("deletes" => '0');
                                $Inv_update = update($con, "invoice", "`id` = '$invoice_no' ", $inv_arr, "", "", "", "");
                                $errors = $Inv_update['errors'];
                                if ($errors != '') {
                                    $result["type"] = "0";
                                    $result["result"] = $errors;
                                } else {
                                    $pointTransactionArr = ["deletes" => "0"];
                                    $pointTransactionUpdate = update($con, "points_transaction", "`invoice_id` = '$mticket_id' AND `type` = 'wallet' ORDER BY `id` DESC", $pointTransactionArr, "", "", "", "");
                                    $errors = $pointTransactionUpdate['errors'];
                                    if ($errors != '') {
                                        $result["type"] = "0";
                                        $result["result"] = $errors;
                                    } else {
                                        $payment_history_arr = array("status" => '1');
                                        $payment_history_update = update($con, "payment_history", "`transaction_id` = '$transid'", $payment_history_arr, "", "", "", "");
                                        $errors = $payment_history_update['errors'];
                                        if ($errors != "") {
                                            $result["type"] = "0";
                                            $result["result"] = $errors;
                                        } else {

                                            $result["type"] = "1";
                                            $result["result"] = "Ticket Deleted Successfully. Ticket ID: " . $ticket_no;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $wticket = select_query($con, "ticket", "", "`transaction_id`='$transid' ORDER BY `id` DESC ", "", "");
            if ($wticket['nr'] > 0) {
                $invoice_no = $wticket['result'][0]['invoice_no'];
                $mticket_id = $wticket['result'][0]['id'];
                $ticket_no = $wticket['result'][0]['ticket_no'];
                $user_id = $wticket['result'][0]['user_id'];
                $mticket_arr = array("deletes" => '0', "delete_reason" => $message);
                $mticket_update = update($con, "ticket", "`id` = '$mticket_id' AND `transaction_id` = '$transid' ORDER BY `id` DESC ", $mticket_arr, "", "", "", "");
                $errors = $mticket_update['errors'];
                if ($errors != "") {
                    $result["type"] = "0";
                    $result["result"] = $errors;
                } else {
                    $payment_check = select_query($con, "payment_history", "", "`transaction_id`='$transid' ORDER BY `id` DESC ", "", "");
                    $wallet_amt = $payment_check['result'][0]['finaltotal'];
                    $user_wallet = select_query($con, "user_register", "", "`id`='$user_id' and `deletes`='0' ", "", "");
                    $current_wallet = $user_wallet['result'][0]['t_earning'];
                    if ($current_wallet == '0') {
                        $result["type"] = "0";
                        $result["result"] = "Wallet Amount Not Found";
                    } else {
                        $totalamt = $current_wallet - $wallet_amt;
                        $wallet_update_arr = array("t_earning" => $totalamt);
                        $wallet_total_update = update($con, "user_register", "`id` = '$user_id' AND `deletes` = '0'", $wallet_update_arr, "", "", "", "");
                        $errors = $wallet_total_update['errors'];
                        if ($errors != "") {
                            $result["type"] = "0";
                            $result["result"] = $errors;
                        } else {
                            $ticket_line_arr = array("deletes" => '0');
                            $ticket_lines_update = update($con, "ticket_lines", "`ticket_id` = '$mticket_id' and `type` = 'WT' ORDER BY `id` DESC ", $ticket_line_arr, "", "", "", "");
                            $errors = $ticket_lines_update['errors'];

                            if ($errors != "") {
                                $result["type"] = "0";
                                $result["result"] = $errors;
                            } else {
                                $wallet_logarr = array("transaction_id" => $transid, "amount" => $wallet_amt, "user_id" => $user_id, "ticket_id" => $mticket_id, "transaction_type" => 'Debit', "type" => 'deleted', "deletes" => '0', "createdon" => $dubaidate_time);
                                $wallet_log_ins = insert($con, "wticket_deletelog", "", $wallet_logarr, "", "", "");
                                $errors = $wallet_log_ins['errors'];
                                if ($errors != "") {
                                    $result["type"] = "0";
                                    $result["result"] = $errors;
                                } else {
                                    $inv_arr = array("deletes" => '0');
                                    $Inv_update = update($con, "invoice", "`id` = '$invoice_no' ", $inv_arr, "", "", "", "");
                                    $errors = $Inv_update['errors'];
                                    if ($errors != '') {
                                        $result["type"] = "0";
                                        $result["result"] = $errors;
                                    } else {
                                        $pointTransactionArr = ["deletes" => "0"];
                                        $pointTransactionUpdate = update($con, "points_transaction", "`invoice_id` = '$mticket_id' AND `type` = 'wallet' ORDER BY `id` DESC", $pointTransactionArr, "", "", "", "");
                                        $errors = $pointTransactionUpdate['errors'];
                                        if ($errors != '') {
                                            $result["type"] = "0";
                                            $result["result"] = $errors;
                                        } else {
                                            $payment_history_arr = array("status" => '1');
                                            $payment_history_update = update($con, "payment_history", "`transaction_id` = '$transid'", $payment_history_arr, "", "", "", "");
                                            $errors = $payment_history_update['errors'];
                                            if ($errors != "") {
                                                $result["type"] = "0";
                                                $result["result"] = $errors;
                                            } else {

                                                $result["type"] = "1";
                                                $result["result"] = "Ticket Deleted Successfully. Ticket ID: " . $ticket_no;
                                            }
                                        }
                                    }
                                }
                                $result["type"] = "1";
                                $result["result"] = "Ticket Deleted Successfully. Ticket ID: " . $ticket_no;
                            }
                        }
                    }
                }
            }
        }
    } else if ($type == 'BP') {


        $bpticket = select_query($con, "bpticket", "", "`transaction_id`='$transid' and `deletes`='1' ORDER BY `id` DESC LIMIT 1", "", "");

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
            $bonus_points = (int)$shi_data['result'][0]['bonus_points'];
        } else {
            $result["type"] = "0";
            $result["result"] = "User Not Found!";
            goto fiVP;
        }

        if ($bonus_points >= $net_total) {







            $mticket_arr = ["deletes" => '0', "delete_reason" => $message];

            $mticket_update = update($con, "bpticket", "`id` = '$mticket_id'  and `deletes`='1'", $mticket_arr, "", "", "", "");

            $errors = $mticket_update['errors'];

            if ($errors != "") {

                $result["type"] = "0";

                $result["result"] = $errors;
            } else {

                $Ticket_arr = ["deletes" => '0'];

                $ticket_lines_update = update($con, "ticket_lines", "`ticket_id` = '$mticket_id' and `type` = 'BP'  and `deletes`='1'", $Ticket_arr, "", "", "", "");

                $errors = $ticket_lines_update['errors'];

                if ($errors != "") {
                    $result["type"] = "0";
                    $result["result"] = $errors;
                } else {
                    $topoints = $bonus_points - $net_total;
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
                        'transaction_type' => 'DEBIT',
                        'reward_type' => 'TICKET REVOKED',
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


                      
                        $result["type"] = "1";

                        $result["result"] = $ticket_no . "  - Ticket has been Revoked Successfully";
                    }
                }
            }
        } else {
            $result["type"] = "0";
            $result["result"] = "Customer Bonus Point is very low!";
            goto fiVP;
        }
    } else if ($type == 'CP') {

        $bpticket = select_query($con, "cpticket", "", "`transaction_id`='$transid' and `deletes`='1' ORDER BY `id` DESC LIMIT 1", "", "");

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
            $cash_points = (int)$shi_data['result'][0]['cash_points'];
        } else {
            $result["type"] = "0";
            $result["result"] = "User Not Found!";
            goto fiVP;
        }

        if ($cash_points >= $net_total) {
            $mticket_arr = ["deletes" => '0', "delete_reason" => $message];

            $mticket_update = update($con, "cpticket", "`id` = '$mticket_id'  and `deletes`='1'", $mticket_arr, "", "", "", "");

            $errors = $mticket_update['errors'];

            if ($errors != "") {

                $result["type"] = "0";

                $result["result"] = $errors;
            } else {

                $Ticket_arr = ["deletes" => '0'];
                $ticket_lines_update = update($con, "ticket_lines", "`ticket_id` = '$mticket_id' and `type` = 'CP'  and `deletes`='1'", $Ticket_arr, "", "", "", "");
                $errors = $ticket_lines_update['errors'];
                if ($errors != "") {
                    $result["type"] = "0";
                    $result["result"] = $errors;
                } else {




                    $Ticket_arr1 = ["deletes" => '0'];
                    $invoice_del = update($con, "invoice", "`id` = '$invoice_no' and `ticket_id` = '$mticket_id' and `type` = 'CP'  and `deletes`='1'", $Ticket_arr1, "", "", "", "");
                    $errors = $invoice_del['errors'];
                    if ($errors != "") {
                        $result["type"] = "0";
                        $result["result"] = $errors;
                    } else {

                        $topoints = $cash_points - $net_total;
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
                            'transaction_type' => 'DEBIT',
                            'reward_type' => 'TICKET REVOKED',
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



                            $result["type"] = "1";

                            $result["result"] = $ticket_no . "  - Ticket has been Revoked Successfully";
                        }
                    }
                }
            }
        } else {
            $result["type"] = "0";
            $result["result"] = "Customer Cash Point is very low!";
            goto fiVP;
        }
    }
    fiVP:
    echo json_encode($result);
}

function sendpurchaseemail($con, $transid, $ottype, $tablename, $baseurl, $adminurl, $draw_no, $dubaidate_time)
{

    $result = [];

    $ticketnumber = select_top_name($con, $tablename, "id", "`transaction_id`='$transid' and `deletes`='0'", "id", "");

    $user_id = select_top_name($con, $tablename, "user_id", "`transaction_id`='$transid' and `deletes`='0'", "user_id", "");

    $email = select_top_name($con, "user_register", "email", "`id`='$user_id' and `deletes`='0'", "email", "");

    $name = select_top_name($con, "user_register", "name", "`id`='$user_id' and `deletes`='0'", "name", "");

    $totalAmount = select_top_name($con, $tablename, "net_total", "`transaction_id`='$transid' and `deletes`='0'", "net_total", "");

    $invoice_no = select_top_name($con, $tablename, "invoice_no", "`transaction_id`='$transid' and `deletes`='0'", "invoice_no", "");

    $emailchack = explode('@', $email);

    if (strtolower($emailchack[1]) != "nationaldraw.ae") {

        $subject = "Purchase Confirmation";

        $messages =

            '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

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



                                                                                                      <tr>

                                                                        <td class="column-one" >

                                                              <table align="center" class="column"> <tr><td valign="top" >

                                                        <div style="margin:0 auto;  max-width:500px; display:block;">

                                                                <div style="width:110px; float:left; ">      <img style="border: 0px;" src="' . $adminurl . 'assets/images/mailtemplate/char21.png" ></div>


                                                                <div  style="">

                                                      <h3 class="demoname"style="color: #29377d;  font-family: Arial Narrow;font-style: italic;font-size: 28px; margin: 0px; text-align: center;font-weight: 500;"> Hi,' . $name . '

                                                                            <br>

                                                                          </h3>

                                                                          <p style="color: #29377d;   font-family: Arial Narrow;font-style: italic; font-size:165%;  margin: 13px 8px 13px 8px; text-align: center;">Thank you for your purchase <br>
                                                                          and donations </p>

                                                                          <h3 style="color: #29377d; font-family: Arial Narrow;  font-style: italic;font-size: 195%; margin: 0px; text-align: center;">Ticket ID #' . $ottype . '' . $ticketnumber . '

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

                                                                              <tr>';

        $messages .= '<td style="padding: 0px 10px 0px 0px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px; width:175px;" align="center" valign="top" bgcolor="#ffffff">

                                                                                  <h3 style="color: #ffffff;  font-size: 22px; margin: 0px; padding: 8px 13px 10px 14px; background: #29377d;; line-height: 1; border-radius: 5px;">

                                                                                    <a href="' . $baseurl . 'ticket-view/' . $transid . '" style="color: #ffffff; text-decoration-line: none;font-style: italic;font-family: Arial Narrow;">View Ticket</a>

                                                                                  </h3>

                                                                                </td>';

        if ($invoice_no != '' && $invoice_no != 0) {

            $messages .= '<td style="padding: 0px 0px 0px 30px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px;width:175px;" align="center" valign="top" bgcolor="#ffffff">

                                                                                  <h3 style="color: #ffffff; font-size: 22px; margin: 0px;  padding: 8px 13px 10px 14px; background: #ffffff;color: #29377d;;  line-height: 1; border-radius: 5px;border: 1px solid;">

                                                                                    <a href="' . $baseurl . 'invoice/' . $transid . '" style="color: #29377d; text-decoration-line: none;font-style: italic;font-family: Arial Narrow;">View Invoice</a>

                                                                                  </h3>

                                                                                </td>';
        }

        $messages .= '</tr>

                                                                            </tbody>

                                                                          </table>

                                                                          <table style="margin: auto; color: #000000;  font-size: medium; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">

                                                                            <tbody>

                                                                              <tr>

                                                                                <td style="color: #666666; background: none; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; font-size: 15px; line-height: 25px;" align="center" bgcolor="#e4dcf1">

                                                                                  <p style="color: #29377d;  font-size:147%; text-align: center;font-style: italic;font-family: Arial Narrow;line-height:30px;">Watch Just3 ' . (isset($drawfreq) && $drawfreq === 4 ? 'Daily Draw results <br> every Monday to Friday ' : 'Tri-Daily Draw results <br> every (Monday,Wednesday,Friday) ') . constant("resultTIME") . 'UAE TIME ' . ((isset($ssdraw_id) && $ssdraw_id != '' && isset($sdrawresultdate1)) ? ', Super Raffle Draw ' . date("dS F Y", strtotime($sdrawresultdate1)) . ' '  : ' ') . (!checkGrandRaffleEligible($draw_no) ?  ('& <br>Grand Raffle Draw result on ' . raffleDrawDate($con, $dubaidate_time, 'dS F Y')) : '') . '</p>

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

                                                                          </table> <!-- End Main Class -->



                                                                        </center> <!-- End Wrapper -->



                                                                      </body>

                                                                      </html>';

        $emailsend = sendemail($con, $email, $subject, $messages, 'tickets');

        if ($emailsend == true) {

            $result["type"] = "1";

            $result["result"] = "Email Send Successfully";
        } else {

            $result["type"] = "0";

            $result["result"] = "could not send Email.";
        }
    } else {

        $result["type"] = "0";

        $result["result"] = "could not send Email.";
    }

    return $result;
}

function sendsmstopurchase($con, $omatype, $tablename, $transid, $baseurl)
{

    $result = [];

    $ticketnumber = select_top_name($con, $tablename, "ticket_no", "`transaction_id`='$transid' and `deletes`='0'", "ticket_no", "");

    $aticket_new_id = select_top_name($con, $tablename, "id", "`transaction_id`='$transid' and `deletes`='0'", "id", "");

    $user_id = select_top_name($con, $tablename, "user_id", "`transaction_id`='$transid' and `deletes`='0'", "user_id", "");

    $mobile = select_top_name($con, "user_register", "mobile", "`id`='$user_id' and `deletes`='0'", "mobile", "");

    $totalAmount = select_top_name($con, $tablename, "net_total", "`transaction_id`='$transid' and `deletes`='0'", "net_total", "");

    if (substr($mobile, 0, 3) == "971") {

        $messages1 = 'Ticket ID #' . $ticketnumber . '. ';

        $query = select_query($con, "ticket_lines", "", "`ticket_id`='$aticket_new_id' and `type` = '$omatype' and `ticket_id`!='' AND `deletes` = '0' group by `product_id` order by `product_id` ASC  ", "", "");

        foreach ($query['result'] as $key => $valuelist) {

            $p_id = $valuelist['product_id'];

            $t_id = $valuelist['orders'];

            $product = select_query($con, "product", "", "`id`='$valuelist[product_id]'  ", "", "");

            $messages1 .= 'CAT-AED ' . round($product['result'][0]['rate']) . '. ';

            $io = 1;

            $mynumber = select_query($con, "ticket_lines", "", "`ticket_id`='$aticket_new_id' and `product_id`='$p_id' and `type` = '$omatype' and `orders`='$t_id' AND `deletes` = '0'", "", "");

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

        $printurlf = $baseurl . 'ticket-view/' . $transid;

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

    return $result;
}
