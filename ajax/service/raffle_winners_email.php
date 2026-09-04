<?php

/**
 * 
 *      Date            Developer     Changes
 *      21-06-2023      Prashant      Raffle Draw Announment
 * */
include '../../include/shi-config.php';
include '../../include/functions.php';

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

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

class myClass
{
    public $con;
    function __construct($con)
    {
        $this->con = $con;
    }

    function get_cus_site_total($checkout_response)
    {
        $post = json_decode($checkout_response, true);

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

        return $finaltotal;
    }

    function get_age_site_total($checkout_response)
    {
        $post = json_decode($checkout_response, true);
        $totalAmount = '';
        $count = intval($post['count']) + 1;
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
                $proid = $post[$pname];
                $result['pid'] = $proid;
                $lucky = "my3number" . $i;
                $p = select_query($this->con, "product", "", "`id`='$proid' and `deletes`='0' ", "", "");
                if ($p['nr'] > 0) {
                    $totalAmount += floatval($p['result'][0]['rate']);
                    // $m3n = $post[$lucky];

                }
            }
        }
        return  $totalAmount;
    }

    function get_age_lines($checkout_response)
    {
        $text = '';
        $post = json_decode($checkout_response, true);
        $count = intval($post['count']) + 1;
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
                $proid = $post[$pname];
                $result['pid'] = $proid;
                $lucky = "my3number" . $i;
                $p = select_query($this->con, "product", "", "`id`='$proid' and `deletes`='0' ", "", "");
                if ($p['nr'] > 0) {
                    $m3n = $post[$lucky];
                    $text .= $m3n . ' (' . floatval($p['result'][0]['rate']) . '), ';
                }
            }
        }
        return  rtrim(trim($text), ',');
    }

    function get_cus_lines($checkout_response)
    {
        $text = '';
        $post = json_decode($checkout_response, true);
        $pt = "4";

        $ot = 1;

        $k = "";

        for ($x = 1; $x <= $pt; $x++) {

            $tval = "item" . $x;

            if ($post[$tval] > 0) {

                $k++;

                $product = select_query($this->con, "product", "", "`id`='$x'  ", "", "");

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
                        $my3number = $post[$checkline];
                        $text .= $my3number . ' (' . floatval($product['result'][0]['rate']) . '), ';
                        $ot++;
                    }
                }
            }
        }


        return  rtrim(trim($text), ',');
    }
}

$my = new myClass($con);


if ($method == "list_draw_winner" || $method == "announce") {

    $type = $method;

    $result = [];

    $announce = [];

    $winnerid = $_POST['winnerid'];

    $draw = select_query($con, "draw", "", "`id`= '$winnerid' AND `deletes` = '0' ", "", "");

    $draw_no = str_pad($draw['result'][0]['draw_no'], 2, "0", STR_PAD_LEFT);

    $emailarr = [];

    $first = $draw['result'][0]['raffleprizefirst'];
    $second = $draw['result'][0]['raffleprizesecond'];
    $third = $draw['result'][0]['raffleprizethird'];

    $value_arr = array($draw['result'][0]['third_one'], $draw['result'][0]['third_two'], $draw['result'][0]['third_three'], $draw['result'][0]['third_four']);

    /*foreach ($value_arr as $value) {

        if ($value != '-') {

            $third[] = $value;
        }
    }*/

    $drawname = $draw['result'][0]['name'];

    $draw_id = $draw['result'][0]['id'];

    $drawdate = date("F d, Y", strtotime($draw['result'][0]['result_datetime']));

    if ($first != '' && $first != '-') {

        $lines1 = select_query($con, "ticket_lines", "", "`draw_id` = '$winnerid' AND `raffle_id` = '$first' AND `deletes` = '0'", "", "");

        if ($lines1['nr'] > 0) {

            for ($i = 0; $i < $lines1['nr']; $i++) {

                $id_i = $lines1['result'][$i]['id'];

                $uid = $lines1['result'][$i]['user_id'];

                $proid = $lines1['result'][$i]['product_id'];

                $user = select_query($con, "user_register", "", "`id`= '$uid' AND `deletes` = '0' ", "", "");

                $product = select_query($con, "product", "", "`id`= '$proid' AND `deletes`='0' ", "", "");

                $prize_amount = select_top_name($con, "product", "prize_one", "`id`= '$proid' AND `deletes`='0'", "prize_one", "");

                // $messages1 = 'WAWW!!! Congratulation! You have won the 1st Prize of AED ' . $prize_amount . ' for Just3 RaffleDraw in ' . $drawname . ' on ' . $drawdate . '  by matching Raffle ID ' . $lines1[result][$i][raffle_id] . '.Good Luck!!!';
                $messages1 = 'Wow! Congratulations on your incredible win! Your Raffle ID ' . $lines1["result"][$i]["raffle_id"] . ' has been selected as the first prize winner, earning you AED ' . $prize_amount . ' in the Just3 Raffle Draw for the ' . $drawname . ' held on ' . $drawdate . '. Good Luck!';

                $email_check = select_query($con, "winnerlist", "", "`ticket_lines_id`= '$id_i' AND `my3number` is null  AND  `draw_id` = '$draw_id'", "", "");

                if ($email_check['nr'] > 0) {

                    $ok = 1;

                    if ($email_check['result'][0]['emaillog'] == '1') {

                        $email_scr = 'Success';
                    } else {

                        $email_scr = '<a class="btn text-danger btn-sm" data-bs-target="#sendemailwinner" data-bs-toggle="modal" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" onclick="sendmailwinner(' . $id_i . ', ' . $draw_id . ')">Send Email</span></a>';
                    }
                } else {

                    $ok = 0;
                }

                $result[] = array("id" => $uid, "name" => $user['result'][0]['name'], "mobile" => $user['result'][0]['mobile'], "product" => "AED " . $product['result'][0]['rate'], "prize" => "1<sup>st</sup>Prize", "amt" => "AED " . $prize_amount, "message" => '<textarea id="w3review" name="w3review" rows="4" cols="50" readonly>' . $messages1 . '</textarea>', "emailme" => $email_scr);

                if ($type == 'announce') {

                    $email = $user['result'][0]['email'];

                    $mobile1 = $user['result'][0]['mobile'];

                    $subject = 'Congrats! YOU HAVE WON - nationaldraw';

                    $name_i = $user['result'][0]['name'];

                    $messages = winnerTemplate($baseurl, '1<sup>st</sup>', $prize_amount, $first, $drawname, $drawdate, 'Straight number', $name_i);

                    if ($ok == 0) {

                        $ins = "INSERT INTO `winnerlist` (`id`, `userid`, `name`, `email`, `mobile`, `ticket_lines_id`, `raffle_id`, `prize`, `message` , `prize_amt`, `draw_id`, `smslog`,`emaillog`, `p_id`) VALUES (NULL, '$uid', '$name_i', '$email', '$mobile1', '$id_i', '$first', '1', '$messages1', '$prize_amount', '$draw_id', '0', '0', '$proid');";

                        $run_new = mysqli_query($con, $ins);

                        $last = mysqli_query($con, "SELECT * FROM `winnerlist` ORDER by id DESC LIMIT 1");

                        if (mysqli_num_rows($last) > 0) {

                            $row = $last->fetch_assoc();

                            $last_id = $row['id'];
                        }

                        $announce[] = array("userid" => $uid, "name" => $user['result'][0]['name'], "email" => $user['result'][0]['email'], "mobile" => $user['result'][0]['mobile'], "subject" => $subject, "messages" => $messages, "smsmessages" => $messages1, "winnerlist" => $last_id);
                    }
                }
            }
        }
    }

    if ($second != '' && $second != '-') {

        $lines2 = select_query($con, "ticket_lines", "", "`draw_id` = '$winnerid' AND `raffle_id` = '$second' AND `deletes` = '0'", "", "");

        if ($lines2['nr'] > 0) {

            for ($i = 0; $i < $lines2['nr']; $i++) {

                $id_i = $lines2['result'][$i]['id'];

                $uid = $lines2['result'][$i]['user_id'];

                $proid = $lines2['result'][$i]['product_id'];

                $user = select_query($con, "user_register", "", "`id`= '$uid' AND `deletes` = '0' ", "", "");

                $product = select_query($con, "product", "", "`id`= '$proid' AND `deletes`='0' ", "", "");

                $prize_amount = select_top_name($con, "product", "prize_two", "`id`= '$proid' AND `deletes`='0'", "prize_two", "");

                // $messages1 = 'WAWW!!! Congratulation! You have won the 2nd Prize of AED ' . $prize_amount . ' for Just3 RaffleDraw in ' . $drawname . ' on ' . $drawdate . '  by matching Raffle ID ' . $lines2[result][$i][raffle_id] . '.Good Luck!!!';
                $messages1 = 'Wow! Congratulations on your incredible win! Your Raffle ID ' . $lines2["result"][$i]["raffle_id"] . ' has been selected as the second prize winner, earning you AED ' . $prize_amount . ' in the Just3 Raffle Draw for the ' . $drawname . ' held on ' . $drawdate . '. Good Luck!';

                $email_check = select_query($con, "winnerlist", "", "`ticket_lines_id`= '$id_i' AND `my3number` is null  AND  `draw_id` = '$draw_id'", "", "");

                if ($email_check['nr'] > 0) {

                    $ok = 1;

                    if ($email_check['result'][0]['emaillog'] == '1') {

                        $email_scr = 'Success';
                    } else {

                        $email_scr = '<a class="btn text-danger btn-sm" data-bs-target="#sendemailwinner" data-bs-toggle="modal" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" onclick="sendmailwinner(' . $id_i . ', ' . $draw_id . ')">Send Email</span></a>';
                    }
                } else {

                    $ok = 0;
                }

                $result[] = array("id" => $uid, "name" => $user['result'][0]['name'], "mobile" => $user['result'][0]['mobile'], "product" => "AED " . $product['result'][0]['rate'], "prize" => "2<sup>nd</sup>Prize", "amt" => "AED " . $product['result'][0]['prize_two'], "message" => '<textarea id="w3review" name="w3review" rows="4" cols="50" readonly>' . $messages1 . '</textarea>', "emailme" => $email_scr);

                if ($type == "announce") {

                    $email = $user['result'][0]['email'];

                    $mobile2 = $user['result'][0]['mobile'];

                    $subject = 'Congrats! YOU HAVE WON - nationaldraw';

                    $name_i = $user['result'][0]['name'];

                    $messages = winnerTemplate($baseurl, '2<sup>nd</sup>', $prize_amount, $second, $drawname, $drawdate, 'Reverse number', $name_i);

                    if ($ok == 0) {

                        $ins = "INSERT INTO `winnerlist` (`id`, `userid`, `name`, `email`, `mobile`, `ticket_lines_id`, `raffle_id`, `prize`, `message`, `prize_amt`, `draw_id`, `smslog`,`emaillog`, `p_id`) VALUES (NULL, '$uid', '$name_i', '$email', '$mobile2', '$id_i', '$second', '2', '$messages1', '$prize_amount', '$draw_id', '0', '0', '$proid');";

                        $run_new = mysqli_query($con, $ins);

                        $last = mysqli_query($con, "SELECT * FROM `winnerlist` ORDER by id DESC LIMIT 1");

                        if (mysqli_num_rows($last) > 0) {

                            $row = $last->fetch_assoc();

                            $last_id = $row['id'];
                        }

                        $announce[] = array("userid" => $uid, "name" => $user['result'][0]['name'], "email" => $user['result'][0]['email'], "mobile" => $user['result'][0]['mobile'], "subject" => $subject, "messages" => $messages, "smsmessages" => $messages1, "winnerlist" => $last_id);
                    }
                }
            }
        }
    }

    if (count($third) > 0) {

        // $arr = implode(',', $third);

        $lines3 = select_query($con, "ticket_lines", "", "`draw_id` = '$winnerid' AND `raffle_id` = '$third' AND `deletes` = '0'", "", "");

        if ($lines3['nr'] > 0) {

            for ($i = 0; $i < $lines3['nr']; $i++) {

                $id_i = $lines3['result'][$i]['id'];

                $uid = $lines3['result'][$i]['user_id'];

                $proid = $lines3['result'][$i]['product_id'];

                $number_3 = $lines3['result'][$i]['raffle_id'];

                $user = select_query($con, "user_register", "", "`id`= '$uid' AND `deletes` = '0' ", "", "");

                $product = select_query($con, "product", "", "`id`= '$proid' AND `deletes`='0' ", "", "");

                $prize_amount = select_top_name($con, "product", "prize_three", "`id`= '$proid' AND `deletes`='0'", "prize_three", "");

                /*$messages1 = 'WAWW!!! Congratulation! you have won the 3rd Prize of AED ' . $prize_amount . ' for Just3 RaffleDraw in ' . $drawname . ' on ' . $drawdate . '  matching Raffle number ' . $lines3[result][$i][raffle_id] . '.Good Luck!!!';*/
                $messages1 = 'Wow! Congratulations on your incredible win! Your Raffle ID ' . $lines3["result"][$i]["raffle_id"] . ' has been selected as the third prize winner, earning you AED ' . $prize_amount . ' in the Just3 Raffle Draw for the ' . $drawname . ' held on ' . $drawdate . '. Good Luck!';

                $email_check = select_query($con, "winnerlist", "", "`ticket_lines_id`= '$id_i' AND `my3number` is null  AND  `draw_id` = '$draw_id'", "", "");

                if ($email_check['nr'] > 0) {

                    $ok = 1;

                    if ($email_check['result'][0]['emaillog'] == '1') {

                        $email_scr = 'Success';
                    } else {

                        $email_scr = '<a class="btn text-danger btn-sm" data-bs-target="#sendemailwinner" data-bs-toggle="modal" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" onclick="sendmailwinner(' . $id_i . ', ' . $draw_id . ')">Send Email</span></a>';
                    }
                } else {

                    $ok = 0;
                }

                $result[] = array("id" => $uid, "name" => $user['result'][0]['name'], "mobile" => $user['result'][0]['mobile'], "product" => "AED " . $product['result'][0]['rate'], "prize" => "3<sup>rd</sup>Prize", "amt" => "AED " . $product['result'][0]['prize_three'], "message" => '<textarea id="w3review" name="w3review" rows="4" cols="50" readonly>' . $messages1 . '</textarea>', "emailme" => $email_scr);

                if ($type == "announce") {

                    $email = $user['result'][0]['email'];

                    $mobile3 = $user['result'][0]['mobile'];

                    $subject = 'Congrats! YOU HAVE WON - nationaldraw';

                    $name_i = $user['result'][0]['name'];

                    $messages = winnerTemplate($baseurl, '3<sup>rd</sup>', $prize_amount, $number_3, $drawname, $drawdate, 'Mix number', $name_i);

                    if ($ok == 0) {

                        $ins = "INSERT INTO `winnerlist` (`id`, `userid`, `name`, `email`, `mobile`, `ticket_lines_id`, `raffle_id`, `prize`, `message`, `prize_amt`,  `draw_id`, `smslog`,`emaillog`, `p_id`) VALUES (NULL, '$uid', '$name_i', '$email', '$mobile3', '$id_i', '$number_3', '3', '$messages1', '$prize_amount', '$draw_id', '0', '0', '$proid');";

                        $run_new = mysqli_query($con, $ins);

                        $last = mysqli_query($con, "SELECT * FROM `winnerlist` ORDER by id DESC LIMIT 1");

                        if (mysqli_num_rows($last) > 0) {

                            $row = $last->fetch_assoc();

                            $last_id = $row['id'];
                        }

                        $announce[] = array("userid" => $uid, "name" => $user['result'][0]['name'], "email" => $user['result'][0]['email'], "mobile" => $user['result'][0]['mobile'], "subject" => $subject, "messages" => $messages, "smsmessages" => $messages1, "winnerlist" => $last_id);
                    }
                }
            }
        }
    }

    if ($type == "list_draw_winner") {

        goto Vi;
    }

    if ($type == "announce") {

        $draw_arr = array("raffle_status" => 'Completed');

        $Inv_update = update($con, "draw", "`id` = '$winnerid' and `deletes`='0'", $draw_arr, "", "", "", "");

        $errors = $Inv_update['errors'];

        if ($errors != "") {

            $result["type"] = "0";

            $result["result"] = $errors;
        } else {

            $winnerlistdrawid = $winnerid;

            $sms_winner_list = select_query($con, "winnerlist", "", "`draw_id`= '$winnerlistdrawid' and `smslog` = '0' AND `raffle_id` IS NOT NULL", "", "");

            if ($sms_winner_list['nr'] > 0) {

                foreach ($sms_winner_list['result'] as $key => $value) {

                    $listid = $value['id'];

                    $draw_id = $value['draw_id'];

                    $email = $value['email'];

                    $number_3 = $value['raffle_id'];

                    $prize_amount = $value['prize_amt'];

                    $price = $value['prize'];

                    $user_id = $value['userid'];

                    $winnerlist = $value['id'];

                    $draw = select_query($con, "draw", "", "`id`= '$draw_id' AND `deletes` = '0' ", "", "");

                    $drawname = $draw['result'][0]['name'];

                    $drawdate = date("d M Y", strtotime($draw['result'][0]['result_datetime']));

                    $shi_data = select_query($con, "user_register", "", "`id`='$user_id' and `deletes`='0' ", "", "");

                    if ($shi_data['nr'] > 0) {

                        $name = $shi_data['result'][0]['name'];

                        $email = $shi_data['result'][0]['email'];

                        $mobile = $shi_data['result'][0]['mobile'];
                    }

                    $smsmessages = $value['message'];

                    if (substr($mobile, 0, 3) == "971") {

                        $messages1 = $smsmessages;

                        $templateid = "";

                        sendsms($con, $mobile, $messages1, $templateid);

                        $draw_arr = array("smslog" => '1');

                        $Inv_update = update($con, "winnerlist", "`id` = '$listid'", $draw_arr, "", "", "", "");

                        $errors = $Inv_update['errors'];

                        if ($errors != "") {

                            $result["type"] = "0";

                            $result["result"] = $errors;
                        } else {

                            $result["type"] = "1";

                            $result["result"] = 'SMS Send Successfully!';
                        }
                    } else {

                        $result["type"] = "0";

                        $result["result"] = 'Could not send sms!';
                    }
                    if ($price == 1) {

                        $str_content = 'Straight number';
                        $sup = '<sup>st</sup>';
                    } else if ($price == 2) {

                        $str_content = 'Reverse number';
                        $sup = '<sup>nd</sup>';
                    } else {

                        $str_content = 'Mix number';
                        $sup = '<sup>rd</sup>';
                    }
                    $messages = winnerTemplate($baseurl, $price . $sup, $prize_amount, $number_3, $drawname, $drawdate, $str_content, $name);
                    $user_ip = getUserIP();

                    $subject = 'Congrats! YOU HAVE WON - nationaldraw';

                    $emailchack = explode('@', $email);

                    if (strtolower($emailchack[1]) != "nationaldraw.ae") {


                        $insertlog = mysqli_query($con, "INSERT INTO `emaillog` (`details`,`subject`,`email`,`ip`,`datetime`,`status`) VALUES ('$messages','$subject','$email','$user_ip','$dubaidate_time','0')");

                        if ($insertlog) {

                            $draw_arr = array("emaillog" => '1');

                            $Inv_update = update($con, "winnerlist", "`id` = '$listid'", $draw_arr, "", "", "", "");
                            $errors = $Inv_update['errors'];

                            if ($errors != "") {

                                $result["type"] = "0";

                                $result["result"] = $errors;
                            } else {

                                $result["type"] = "1";

                                $result["result"] = 'Success';
                            }
                        } else {

                            $result["type"] = "0";

                            $result["result"] = 'Email not send!';
                        }
                    } else {

                        $result["type"] = "0";

                        $result["result"] = 'Could not send email!';
                    }
                }
            } else {

                $result["type"] = "0";

                $result["result"] = 'Winner Not Sound!';
            }

            // Winning Commission Add Function

            $winner_list = select_query($con, "winnerlist", "", "`draw_id`= '$winnerid'", "", "");

            if ($winner_list['nr'] > 0) {

                foreach ($winner_list['result'] as $key => $value) {

                    $ticket_lines_id = $value['ticket_lines_id'];

                    $prize_amt = $value['prize_amt'];

                    $my3number = $value['raffle_id'];

                    $ticketlineslist = select_query($con, "ticket_lines", "", "`id` = '$ticket_lines_id' AND `draw_id` = '$winnerid' AND `my3number` = '$my3number' AND `deletes` = '0'", "", "");

                    if ($ticketlineslist['nr'] > 0) {

                        foreach ($ticketlineslist['result'] as $key => $value) {

                            $type = $value['type'];

                            if ($type == 'AT') {

                                $agent_id = $value['agent_id'];

                                $invoice_id = $value['invoice_no'];

                                // Collect Agent Details

                                $agent_data = select_query($con, "user_register", "", "`id` = '$agent_id' AND `deletes`='0'", "", "");

                                if ($agent_data['nr'] > 0) {

                                    $t_earning = floatval($agent_data['result'][0]['t_earning']);

                                    $roll_id_new = $agent_data['result'][0]['roll_id'];

                                    $ag_id = $agent_data['result'][0]['id'];

                                    $a_com = select_query($con, "winning_commission", "", "`roll_id` = '$roll_id_new' AND `deletes`='0'", "", "");

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

                                                $level_3_amt = $prize_amt * $value / 100;

                                                $amt_3 = floatval($t_earning_new) + $level_3_amt;

                                                $t_earning_arr = array("t_earning" => $amt_3);

                                                $earn_update = update($con, "user_register", "`id` = '$ag_id' and `deletes`='0'", $t_earning_arr, "", "", "", "");

                                                $errors = $earn_update['errors'];

                                                if ($errors != "") {

                                                    $result["type"] = "0";

                                                    $result["result"] = $errors;
                                                } else {

                                                    $Arrde = array("type" => "earn", "order_type" => "winning", "amount" => $level_3_amt, "to_id" => $ag_id, "to_opening" => $t_earning_new, "to_closing" => $amt_3, "invoice_id" => $invoice_id, "createdon" => $dubaidate_time);

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
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $result["type"] = "1";

            $result["result"] = "Success!";

            $result["announce"] = $announce;

            $result['url'] = $adminurl . 'rafflewinners/list/' . $winnerid;

            goto Vi;
        }
    }

    Vi:

    echo json_encode($result);
} else if ($method == "list_draw_winner_final") {

    $result = [];

    $announce = [];

    $winnerid = $_POST['winnerid'];

    $draw = select_query($con, "draw", "", "`id`= '$winnerid'  AND `deletes` = '0' ", "", "");

    $draw_no = str_pad($draw['result'][0]['draw_no'], 2, "0", STR_PAD_LEFT);

    $winnerList = select_query($con, "winnerlist", "", "`draw_id`= '$winnerid' AND `raffle_id` is not null", "", "");

    if ($winnerList['nr'] > 0) {

        for ($i = 0; $i < $winnerList['nr']; $i++) {

            $id = $winnerList['result'][$i]['id'];

            $uid = $winnerList['result'][$i]['userid'];

            $prize = $winnerList['result'][$i]['prize'];

            $messages1 = $winnerList['result'][$i]['message'];

            $p_id = $winnerList['result'][$i]['p_id'];

            $prize_amt = $winnerList['result'][$i]['prize_amt'];

            $user = select_query($con, "user_register", "", "`id`= '$uid' AND `deletes` = '0' ", "", "");

            $Product_amt = select_top_name($con, "product", "rate", "`id`= '$p_id' AND `deletes`='0'", "rate", "");

            $amt_add_shi = $winnerList['result'][$i]['amt_add_shi'];

            $ticket_lines_id = $winnerList['result'][$i]['ticket_lines_id'];

            if ($user['nr'] > 0) {

                // $user_id_new = $user[result][0]['id'];

                $name = $user['result'][0]['name'];

                $email = $user['result'][0]['email'];

                $mobile = $user['result'][0]['mobile'];

                $t_amt = floatval($user['result'][0]['t_earning']);
            }

            if ($amt_add_shi == 0) {

                $total_amount = $t_amt + floatval($prize_amt);

                $invoice_id = select_top_name($con, "ticket_lines", "invoice_no", "`id`= '$ticket_lines_id' AND `deletes`='0'", "invoice_no", "");

                $earning_transaction_arr = array("invoice_id" => $invoice_id, "order_type" => 'winning', "type" => 'earn', "to_closing" => $total_amount, "amount" => $prize_amt, "to_opening" => $t_amt, "to_id" => $uid, "deletes" => '0', "createdon" => $dubaidate_time);

                $earning_transaction_ins = insert($con, "earning_transaction", "", $earning_transaction_arr, "", "", "");

                $total_arr = array("t_earning" => $total_amount);

                $Inv_update = update($con, "user_register", "`id`= '$uid' AND `deletes` = '0'", $total_arr, "", "", "", "");

                $errors = $Inv_update['errors'];

                if ($errors != "") {

                    $result["result"] = $errors;
                } else {

                    $amt_arr = array("amt_add_shi" => '1');

                    $winer = update($con, "winnerlist", "`id` = '$id'", $amt_arr, "", "", "", "");

                    $errors = $winer['errors'];

                    if ($errors != "") {

                        $result["result"] = $errors;
                    } else {
                    }
                }
            }

            if ($winnerList['result'][$i]['emaillog'] == '1') {

                $email_scr = 'Success';
            } else {

                $emailchack = explode('@', $email);

                if (strtolower($emailchack[1]) != "nationaldraw.ae" && strtolower($emailchack[1]) != "") {

                    $email_scr = '<a class="btn text-danger btn-sm" data-bs-target="#sendemailwinner" data-bs-toggle="modal" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" onclick="sendmailwinner(' . $id . ')">Send Email</span></a>';
                } else {

                    $email_scr = '<textarea  readonly>Could Not Send Email it Contains "National Draw" Tag</textarea>';
                }
            }

            if ($winnerList['result'][$i]['smslog'] == '1') {

                $sms_scr = 'Success';
            } else {

                if (substr($mobile, 0, 3) == "971") {

                    $sms_scr = '<a class="btn text-danger btn-sm" data-bs-target="#sendsmswinner" data-bs-toggle="modal" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" onclick="sendsmswinner(' . $id . ')">Send SMS</span></a>';
                } else {

                    $sms_scr = '<textarea  readonly>Could Not Send SMS as it is Not  971 Number.</textarea>';
                }
            }

            if ($prize == 1) {

                $newprize = $prize . "<sup>st</sup>Prize";
            } else if ($prize == 2) {

                $newprize = $prize . "<sup>nd</sup>Prize";
            } else {

                $newprize = $prize . "<sup>rd</sup>Prize";
            }

            $result[] = array("id_i" => $id, "id" => $uid, "name" => $user['result'][0]['name'], "mobile" => $user['result'][0]['mobile'], "product" => "AED " . $Product_amt, "prize" => $newprize, "amt" => '<span style="display: none;">' . $prize_amt . '</span>AED ' . $prize_amt, "message" => '<textarea id="w3review" name="w3review" rows="3" cols="40" readonly>' . $messages1 . '</textarea>', "emailme" => $email_scr, "smsme" => $sms_scr);
        }
    }

    echo json_encode($result);
} else if ($method == "send_email_winner") {

    $winnerlistid = $_POST['winnerlistid'];

    $email_check = select_query($con, "winnerlist", "", "`id`= '$winnerlistid'", "", "");

    if ($email_check['nr'] > 0) {

        $draw_id = $email_check['result'][0]['draw_id'];

        $email = $email_check['result'][0]['email'];

        $number_3 = $email_check['result'][0]['raffle_id'];

        $prize_amount = $email_check['result'][0]['prize_amt'];

        $price = $email_check['result'][0]['prize'];

        $user_id = $email_check['result'][0]['userid'];

        $winnerlist = $email_check['result'][0]['id'];

        $draw = select_query($con, "draw", "", "`id`= '$draw_id' AND `deletes` = '0' ", "", "");

        $drawname = $draw['result'][0]['name'];

        // $draw_id = $draw[result][0][id];

        $drawdate = date("d M Y", strtotime($draw['result'][0]['result_datetime']));

        if ($price == 1) {

            $str_content = 'Straight number';
            $sup = '<sup>st</sup>';
        } else if ($price == 2) {

            $str_content = 'Reverse number';
            $sup = '<sup>nd</sup>';
        } else {

            $str_content = 'Mix number';
            $sup = '<sup>rd</sup>';
        }

        $shi_data = select_query($con, "user_register", "", "`id`='$user_id' and `deletes`='0' ", "", "");

        if ($shi_data['nr'] > 0) {

            $name = $shi_data['result'][0]['name'];

            $email = $shi_data['result'][0]['email'];

            $mobile = $shi_data['result'][0]['mobile'];
        }

        $messages = winnerTemplate($baseurl, $price . $sup, $prize_amount, $number_3, $drawname, $drawdate, $str_content, $name);

        $subject = 'Congrats! YOU HAVE WON - nationaldraw';

        $emailchack = explode('@', $email);

        if (strtolower($emailchack[1]) != "nationaldraw.ae") {

            $sendmail = sendemail($con, $email, $subject, $messages, 'results');

            if ($sendmail) {

                $draw_arr = array("emaillog" => '1');

                $Inv_update = update($con, "winnerlist", "`id` = '$winnerlistid'", $draw_arr, "", "", "", "");

                $errors = $Inv_update['errors'];

                if ($errors != "") {

                    $result["type"] = "0";

                    $result["result"] = $errors;
                } else {

                    $result["type"] = "1";

                    $result["result"] = 'Success';
                }
            } else {

                $result["type"] = "0";

                $result["result"] = 'Email not send!';
            }
        } else {

            $result["type"] = "0";

            $result["result"] = 'Could not send email!';
        }
    } else {

        $result["type"] = "0";

        $result["result"] = 'Winner Not Found!';
    }

    echo json_encode($result);
} else if ($method == "send_sms_winner") {

    $winnerlistid = $_POST['winnerlistid'];

    $email_check = select_query($con, "winnerlist", "", "`id`= '$winnerlistid'", "", "");

    if ($email_check['nr'] > 0) {

        $draw_id = $email_check['result'][0]['draw_id'];

        $email = $email_check['result'][0]['email'];

        $number_3 = $email_check['result'][0]['my3number'];

        $prize_amount = $email_check['result'][0]['prize_amt'];

        $price = $email_check['result'][0]['prize'];

        $user_id = $email_check['result'][0]['userid'];

        $winnerlist = $email_check['result'][0]['id'];

        $draw = select_query($con, "draw", "", "`id`= '$draw_id' AND `deletes` = '0' ", "", "");

        $drawname = $draw['result'][0]['name'];

        $drawdate = date("d M Y", strtotime($draw['result'][0]['result_datetime']));

        $shi_data = select_query($con, "user_register", "", "`id`='$user_id' and `deletes`='0' ", "", "");

        if ($shi_data['nr'] > 0) {

            $name = $shi_data['result'][0]['name'];

            $email = $shi_data['result'][0]['email'];

            $mobile = $shi_data['result'][0]['mobile'];
        }

        $smsmessages = $email_check['result'][0]['message'];

        if (substr($mobile, 0, 3) == "971") {

            $messages1 = $smsmessages;

            $templateid = "";

            sendsms($con, $mobile, $messages1, $templateid);

            $draw_arr = array("smslog" => '1');

            $Inv_update = update($con, "winnerlist", "`id` = '$winnerlistid'", $draw_arr, "", "", "", "");

            $errors = $Inv_update['errors'];

            if ($errors != "") {

                $result["type"] = "0";

                $result["result"] = $errors;
            } else {

                $result["type"] = "1";

                $result["result"] = 'SMS Send Successfully!';
            }
        } else {

            $result["type"] = "0";

            $result["result"] = 'Could not send sms!';
        }
    } else {

        $result["type"] = "0";

        $result["result"] = 'Winner Not Sound!';
    }

    echo json_encode($result);
} else if ($method == "send_email") {

    $result = [];

    $user_id = $_POST['userid'];

    $shi_data = select_query($con, "user_register", "", "`id`='$user_id' and `deletes`='0' ", "", "");

    if ($shi_data['nr'] > 0) {

        $name = $shi_data['result'][0]['name'];

        $email = $shi_data['result'][0]['email'];

        $mobile = $shi_data['result'][0]['mobile'];
    }

    // $email = $_POST['email'];

    // $mobile = $_POST['mobile'];

    $subject = $_POST['subject'];

    $messages = $_POST['messages'];

    $smsmessages = $_POST['smsmessages'];

    $winnerlist = $_POST['winnerlist'];

    $emailchack = explode('@', $email);

    if (strtolower($emailchack[1]) != "nationaldraw.ae") {

        $sendmail = sendemail($con, $email, $subject, $messages, 'results');

        $draw_arr = array("emaillog" => '1');

        $Inv_update = update($con, "winnerlist", "`id` = '$winnerlist'", $draw_arr, "", "", "", "");

        $errors = $Inv_update['errors'];

        if ($errors != "") {

            $result["type"] = "0";

            $result["result"] = $errors;
        } else {
        }
    } else {

        $sendmail = true;

        $draw_arr = array("emaillog" => '0');

        $Inv_update = update($con, "winnerlist", "`id` = '$winnerlist'", $draw_arr, "", "", "", "");

        $errors = $Inv_update['errors'];

        if ($errors != "") {

            $result["type"] = "0";

            $result["result"] = $errors;
        } else {
        }
    }

    if ($sendmail) {

        if (substr($mobile, 0, 3) == "971") {

            $messages1 = $smsmessages;

            $templateid = "";

            sendsms($con, $mobile, $messages1, $templateid);

            $draw_arr = array("smslog" => '1');

            $Inv_update = update($con, "winnerlist", "`id` = '$winnerlist'", $draw_arr, "", "", "", "");

            $errors = $Inv_update['errors'];

            if ($errors != "") {

                $result["type"] = "0";

                $result["result"] = $errors;
            } else {
            }
        } else {

            $draw_arr = array("smslog" => '0');

            $Inv_update = update($con, "winnerlist", "`id` = '$winnerlist'", $draw_arr, "", "", "", "");

            $errors = $Inv_update['errors'];

            if ($errors != "") {

                $result["type"] = "0";

                $result["result"] = $errors;
            } else {
            }
        }

        $result["type"] = "1";

        $result["result"] = "Success";
    } else {

        $result["type"] = "0";

        $result["result"] = "Email not send!";
    }

    echo json_encode($result);
} else if ($method == "getTabel") {
    $drawID = $_POST['drawID'];
    $result = [];
    if ($drawID != '') {
        $draw = select_query($con, "draw", "", "`id`= '$drawID' AND `deletes` = '0' ", "", "");
        $first = $draw['result'][0]['raffleprizefirst'];
        $second = $draw['result'][0]['raffleprizesecond'];
        $third_one = $draw['result'][0]['raffleprizethird'];
        $third_two = $draw['result'][0]['third_two'];
        $third_three = $draw['result'][0]['third_three'];
        $third_four = $draw['result'][0]['third_four'];
        $sbCount = [];
        $tCount = 0;
        $tPrize = 0;
        $rtCount = 0;
        $rtTotal = 0;
        $dataHtml = '<table style="text-align: center;" class=" table-dark table-striped" id="rebreport">
                            <thead>
                                <tr>
                                    <th rowspan="2">Raffle Number</th>
                                    <th colspan="">1<sup>st</sup> Prize</th>
                                    <th colspan="">2<sup>nd</sup> Prize</th>
                                    <th colspan="">3<sup>rd</sup> Prize</th>
                                    <th colspan="2">Total</th>
                                </tr>
                                <tr>
                                    <th colspan="">' . $first . '</th>
                                    <th colspan="">' . $second . '</th>
                                    <th colspan="">' . $third_one . '</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>';

        $product = select_query($con, "product", "", "`deletes`='0' ", "", "");
        if ($product['nr'] > 0) {
            foreach ($product['result'] as $key => $value) {
                $rowCount = 0;
                $rowAmt = 0;
                $title = 'Category AED ' . (int) $value['rate'];
                $dataHtml .= '<tr><td><a href="javascript:void(0);" onclick="getWinnerList(' . (int) $value['id'] . ', ' . $drawID . ', ' . "'$title'" . ', ' . "'$baseurl'" . ' )">Category AED ' . (int) $value['rate'] . '</a></td>';

                $sbCount[0]['count'] += 0;
                $sbCount[0]['total'] += 0;
                if ($first != '' && $first != '-') {
                    $lines1 = mysqli_query($con, "select COUNT(id) AS `total` from ticket_lines where `product_id` = '$value[id]' AND `draw_id` = '$drawID' AND `raffle_id` = '$first' AND `deletes` = '0'");

                    $row = mysqli_fetch_assoc($lines1);

                    if ($row['total'] > 0) {
                        $rowCount += $row['total'];
                        $rowAmt += $row['total'] * $value['prize_one'];
                        $sbCount[0]['count'] += $row['total'];
                        $sbCount[0]['total'] += $row['total'] * $value['prize_one'];
                        $dataHtml .= '<td>' . number_format($row['total'] * $value['prize_one'], 2) . '</td>';
                    } else {

                        $dataHtml .= '<td>-</td>';
                    }
                } else {
                    $dataHtml .= '<td>-</td>';
                }

                $sbCount[1]['count'] += 0;
                $sbCount[1]['total'] += 0;
                if ($second != '' && $second != '-') {
                    $lines2 = mysqli_query($con, "select COUNT(id) AS `total` from ticket_lines where `product_id` = '$value[id]' AND `draw_id` = '$drawID' AND `raffle_id` = '$second' AND `deletes` = '0'");
                    $row = mysqli_fetch_assoc($lines2);
                    if ($row['total'] > 0) {
                        $rowCount += $row['total'];
                        $rowAmt += $row['total'] * $value['prize_two'];
                        $sbCount[1]['count'] += $row['total'];
                        $sbCount[1]['total'] += $row['total'] * $value['prize_two'];
                        $dataHtml .= '<td>' . number_format($row['total'] * $value['prize_two'], 2) . '</td>';
                    } else {
                        $dataHtml .= '<td>-</td>';
                    }
                } else {
                    $dataHtml .= '<td>-</td>';
                }

                $sbCount[2]['count'] += 0;
                $sbCount[2]['total'] += 0;
                if ($third_one != '' && $third_one != '-') {
                    $lines3 = mysqli_query($con, "select COUNT(id) AS `total` from ticket_lines where `product_id` = '$value[id]' AND `draw_id` = '$drawID' AND `raffle_id` = '$third_one' AND `deletes` = '0'");
                    $row = mysqli_fetch_assoc($lines3);
                    if ($row['total'] > 0) {
                        $rowCount += $row['total'];
                        $rowAmt += $row['total'] * $value['prize_three'];
                        $sbCount[2]['count'] += $row['total'];
                        $sbCount[2]['total'] += $row['total'] * $value['prize_three'];
                        $dataHtml .= '<td>' . number_format($row['total'] * $value['prize_three'], 2) . '</td>';
                    } else {
                        $dataHtml .= '<td>-</td>';
                    }
                } else {
                    $dataHtml .= '<td>-</td>';
                }

                /*$sbCount[3]['count'] += 0;
                $sbCount[3]['total'] += 0;
                if ($third_two != '' && $third_two != '-') {
                    $lines4 = mysqli_query($con, "select COUNT(id) AS `total` from ticket_lines where `product_id` = '$value[id]' AND `draw_id` = '$drawID' AND `raffle_id` = '$third_two' AND `deletes` = '0'");
                    $row = mysqli_fetch_assoc($lines4);
                    if ($row['total'] > 0) {
                        $rowCount += $row['total'];
                        $rowAmt += $row['total'] * $value['prize_three'];
                        $sbCount[3]['count'] += $row['total'];
                        $sbCount[3]['total'] += $row['total'] * $value['prize_three'];
                        $dataHtml .= '<td>' . $row['total'] . '</td><td>' . number_format($row['total'] * $value['prize_three'], 2) . '</td>';
                    } else {
                        $dataHtml .= '<td>-</td><td>-</td>';
                    }
                } else {
                    $dataHtml .= '<td>-</td><td>-</td>';
                }

                $sbCount[4]['count'] += 0;
                $sbCount[4]['total'] += 0;
                if ($third_three != '' && $third_three != '-') {
                    $lines5 = mysqli_query($con, "select COUNT(id) AS `total` from ticket_lines where `product_id` = '$value[id]' AND `draw_id` = '$drawID' AND `raffle_id` = '$third_three' AND `deletes` = '0'");
                    $row = mysqli_fetch_assoc($lines5);
                    if ($row['total'] > 0) {
                        $rowCount += $row['total'];
                        $rowAmt += $row['total'] * $value['prize_three'];
                        $sbCount[4]['count'] += $row['total'];
                        $sbCount[4]['total'] += $row['total'] * $value['prize_three'];
                        $dataHtml .= '<td>' . $row['total'] . '</td><td>' . number_format($row['total'] * $value['prize_three'], 2) . '</td>';
                    } else {
                        $dataHtml .= '<td>-</td><td>-</td>';
                    }
                } else {
                    $dataHtml .= '<td>-</td><td>-</td>';
                }

                $sbCount[5]['count'] += 0;
                $sbCount[5]['total'] += 0;
                if ($third_four != '' && $third_four != '-') {
                    $lines6 = mysqli_query($con, "select COUNT(id) AS `total` from ticket_lines where `product_id` = '$value[id]' AND `draw_id` = '$drawID' AND `raffle_id` = '$third_four' AND `deletes` = '0'");
                    $row = mysqli_fetch_assoc($lines6);
                    if ($row['total'] > 0) {
                        $rowCount += $row['total'];
                        $rowAmt += $row['total'] * $value['prize_three'];
                        $sbCount[5]['count'] += $row['total'];
                        $sbCount[5]['total'] += $row['total'] * $value['prize_three'];
                        $dataHtml .= '<td>' . $row['total'] . '</td><td>' . number_format($row['total'] * $value['prize_three'], 2) . '</td>';
                    } else {
                        $dataHtml .= '<td>-</td><td>-</td>';
                    }
                } else {
                    $dataHtml .= '<td>-</td><td>-</td>';
                }*/

                $rtCount += $rowCount;
                $rtTotal += $rowAmt;
                $dataHtml .= '<td>' . number_format($rowAmt, 2) . '</td>';
                $dataHtml .= '</tr>';
            }
        }

        $dataHtml .= '<tr><td>Total</td>';

        for ($i = 0; $i < count($sbCount); $i++) {
            $tCount += $sbCount[$i]['count'];
            $tPrize += $sbCount[$i]['total'];
            $dataHtml .= '<td>' . number_format($sbCount[$i]['total'], 2) . '</td>';
        }
        $title = "RBD Preliminary Winner";
        $dataHtml .= '<td rowspan="2">' . number_format($rtTotal, 2) . '</td></tr>
                               
                            </tbody>



                        </table>
                    ';

        $result['type'] = '1';
        $result['result'] = $dataHtml;
        // $result['ded'] = '<a href=" ' . $baseurl . 'rafflerbddedreport.php?drawid=' . $drawID . '&proid=0" class="btn btn-info" target="_blank" rel="noopener noreferrer" download>DET Report</a>';
        $result['ded'] = ' <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Report for DED
                              </button>
                              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                               <li><a href="rafflededreport_new.php?drawid=' . $drawID . '&proid=0&reportType=excel&pre=yes&prizeINC=yes" target="_blank" class="dropdown-item reportdwn" data-report="0" data-type="excel" href="#">Excel</a></li>
                                <li><a href="rafflerbddedreport.php?drawid=' . $drawID . '&proid=0" target="_blank" class="dropdown-item reportdwn" data-report="0" data-type="pdf" href="#">Pdf</a></li>
                              </ul>
                            </div>';

        //  <li><a href=" ' . $baseurl . 'rafflededreport.php?drawid=' . $drawID . '&proid=0&reportType=excel" target="_blank" class="dropdown-item reportdwn" data-report="0" data-type="excel" href="#">Excel</a></li>
        $result['ldfullre'] = ' <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Report for LD
                              </button>
                              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                               <li><a href="rafflededreport.php?drawid=' . $drawID . '&proid=G&reportType=excel&pre=yes" target="_blank" class="dropdown-item reportdwn" data-report="0" data-type="excel" href="#">Excel</a></li>
                                <li><a href="rafflerbddedreport.php?drawid=' . $drawID . '&proid=G" target="_blank" class="dropdown-item reportdwn" data-report="0" data-type="pdf" href="#">Pdf</a></li>
                              </ul>
                            </div>';

        //  <li><a href=" ' . $baseurl . 'rafflededreport.php?drawid=' . $drawID . '&proid=G&reportType=excel" target="_blank" class="dropdown-item reportdwn" data-report="0" data-type="excel" href="#">Excel</a></li>


        // $result['ldfullre'] = '<a href=" ' . $baseurl . 'rafflerbddedreport.php?drawid=' . $drawID . '&proid=G" class="btn btn-info" target="_blank" rel="noopener noreferrer" download>LD Full Report</a>';
    } else {
        $result['type'] = '0';
        $result['result'] = 'Draw ID Could Not Be Get!';
    }
    echo json_encode($result);
} else if ($method == "list_draw_winner_new") {

    $result = [];

    $winnerid = $_POST['drawid'];

    $draw = select_query($con, "draw", "", "`id`= '$winnerid' AND `deletes` = '0' ", "", "");

    $draw_no = str_pad($draw['result'][0]['draw_no'], 2, "0", STR_PAD_LEFT);

    $pid = $_POST['pid'];

    $first = $draw['result'][0]['raffleprizefirst'];
    $second = $draw['result'][0]['raffleprizesecond'];
    $third = $draw['result'][0]['raffleprizethird'];
    // foreach ($value_arr as $value) {
    //     if ($value != '-') {
    //         $third[] = $value;
    //     }
    // }

    $drawname = $draw['result'][0]['name'];

    $draw_id = $draw['result'][0]['id'];

    $drawdate = date("d M Y", strtotime($draw['result'][0]['result_datetime']));
    $conP = "";
    if ($pid != 0) {
        $conP = "AND `product_id` = '$pid'";
    }

    if ($first != '' && $first != '-') {

        $lines1 = select_query($con, "ticket_lines", "", "`draw_id` = '$winnerid' AND `raffle_id` = '$first' AND `deletes` = '0' $conP", "", "");

        if ($lines1['nr'] > 0) {
            for ($i = 0; $i < $lines1['nr']; $i++) {

                $id_i = $lines1['result'][$i]['id'];
                $uid = $lines1['result'][$i]['user_id'];
                $proid = $lines1['result'][$i]['product_id'];

                $ticketID = $lines1['result'][$i]['type'] . $lines1['result'][$i]['ticket_id'];

                $user = select_query($con, "user_register", "", "`id`= '$uid' AND `deletes` = '0' ", "", "");
                $product = select_query($con, "product", "", "`id`= '$proid' AND `deletes`='0' ", "", "");
                $prize_amount = select_top_name($con, "product", "prize_one", "`id`= '$proid' AND `deletes`='0'", "prize_one", "");

                $result[] = array("empty" => '', "date" => date("d-M-Y g:i a", strtotime($lines1['result'][$i]['createdon'])), "raffle" => $lines1['result'][$i]['raffle_id'], "my3num" => $lines1['result'][$i]['my3number'], "email" => $user['result'][0]['email'], "ticketid" => $ticketID, "id" => $uid, "name" => $user['result'][0]['name'], "mobile" => $user['result'][0]['mobile'], "product" => (int) $product['result'][0]['rate'], "prize" => "1<sup>st</sup>Prize", "amt" => number_format($prize_amount));
            }
        }
    }

    if ($second != '' && $second != '-') {

        $lines2 = select_query($con, "ticket_lines", "", "`draw_id` = '$winnerid' AND `raffle_id` = '$second' AND `deletes` = '0' $conP", "", "");

        if ($lines2['nr'] > 0) {

            for ($i = 0; $i < $lines2['nr']; $i++) {

                $id_i = $lines2['result'][$i]['id'];

                $uid = $lines2['result'][$i]['user_id'];

                $proid = $lines2['result'][$i]['product_id'];

                $ticketID = $lines2['result'][$i]['type'] . $lines2['result'][$i]['ticket_id'];

                $user = select_query($con, "user_register", "", "`id`= '$uid' AND `deletes` = '0' ", "", "");

                $product = select_query($con, "product", "", "`id`= '$proid' AND `deletes`='0' ", "", "");

                $prize_amount = select_top_name($con, "product", "prize_two", "`id`= '$proid' AND `deletes`='0'", "prize_two", "");

                $result[] = array("empty" => '', "date" => date("d-M-Y g:i a", strtotime($lines2['result'][$i]['createdon'])), "raffle" => $lines2['result'][$i]['raffle_id'], "my3num" => $lines2['result'][$i]['my3number'], "email" => $user['result'][0]['email'], "ticketid" => $ticketID, "id" => $uid, "name" => $user['result'][0]['name'], "mobile" => $user['result'][0]['mobile'], "product" => (int) $product['result'][0]['rate'], "prize" => "2<sup>nd</sup>Prize", "amt" => number_format($prize_amount));
            }
        }
    }

    if ($third != '' && $third != '-') {

        // $arr = implode(',', $third);

        $lines3 = select_query($con, "ticket_lines", "", "`draw_id` = '$winnerid' AND `raffle_id` = '$third' AND `deletes` = '0' $conP", "", "");

        if ($lines3['nr'] > 0) {

            for ($i = 0; $i < $lines3['nr']; $i++) {

                $id_i = $lines3['result'][$i]['id'];

                $uid = $lines3['result'][$i]['user_id'];

                $proid = $lines3['result'][$i]['product_id'];

                $number_3 = $lines3['result'][$i]['my3number'];
                $ticketID = $lines3['result'][$i]['type'] . $lines3['result'][$i]['ticket_id'];
                $user = select_query($con, "user_register", "", "`id`= '$uid' AND `deletes` = '0' ", "", "");

                $product = select_query($con, "product", "", "`id`= '$proid' AND `deletes`='0' ", "", "");

                $prize_amount = select_top_name($con, "product", "prize_three", "`id`= '$proid' AND `deletes`='0'", "prize_three", "");

                $result[] = array("empty" => '', "date" => date("d-M-Y g:i a", strtotime($lines3['result'][$i]['createdon'])), "raffle" => $lines3['result'][$i]['raffle_id'], "my3num" => $lines3['result'][$i]['my3number'], "email" => $user['result'][0]['email'], "ticketid" => $ticketID, "id" => $uid, "name" => $user['result'][0]['name'], "mobile" => $user['result'][0]['mobile'], "product" => (int) $product['result'][0]['rate'], "prize" => "3<sup>rd</sup>Prize", "amt" => number_format($prize_amount));
            }
        }
    }

    echo json_encode($result);
} else if ($method == "changeSmsGateWay") {
    $gateway = $_POST['gateway'];
    $site = $_POST['site'];
    $result = [];
    if ($_SESSION['memid'] != '') {
        if ($gateway != '') {
            if ($site != '') {
                $sms_arr = [];
                if ($site == 'admin') {
                    $site = 'adminsite';
                    $sms_arr = ["gateway" => $gateway, "site" => $site, "changedby" => $_SESSION['memid'], "deletes" => '0', "createdon" => $dubaidate_time, "deletedon" => $dubaidate_time];
                }

                if ($site == 'customer') {
                    $site = 'customersite';
                    $sms_arr = ["gateway" => $gateway, "site" => $site, "changedby" => $_SESSION['memid'], "deletes" => '0', "createdon" => $dubaidate_time, "deletedon" => $dubaidate_time];
                }

                if ($site != '') {
                    $delete_LOG = mysqli_query($con, "UPDATE sms_switch SET `deletes` = '1', `deletedon` = '$dubaidate_time' WHERE `site` = '$site'");
                    if ($delete_LOG) {
                        $sms_switch = insert($con, "sms_switch", "", $sms_arr, "", "", "");
                        if ($sms_switch['id'] != "") {
                            $result["type"] = "1";
                            $result["result"] = 'SMS Gateway Changed!';
                            goto GfResult;
                        } else {
                            $result["type"] = "0";
                            $result["result"] = 'SMS Gateway Could not be changed!';
                            goto GfResult;
                        }
                    } else {
                        $result["type"] = "0";
                        $result["result"] = 'SMS Gateway Could not be changed!';
                        goto GfResult;
                    }
                }
            } else {
                $result['type'] = '0';
                $result['result'] = 'Kindly Select SMS Gateway!';
                goto GfResult;
            }
        } else {
            $result['type'] = '0';
            $result['result'] = 'Kindly Select SMS Gateway!';
            goto GfResult;
        }
    } else {
        $result['type'] = '0';
        $result['result'] = 'Login First and Try Again!';
        goto GfResult;
    }

    GfResult:
    echo json_encode($result);
} else if ($method == 'dedUpdate') {
    $result = [];
    $drawid = $_POST['drawid'];
    $permit = $_POST['permit'];
    if ($drawid != '') {
        if ($permit != '') {
            $draw_arr = ['permitno' => $permit];
            $Inv_update = update($con, "draw", "`id` = '$drawid'", $draw_arr, "", "", "", "");
            $errors = $Inv_update['errors'];
            if ($errors != "") {
                $result["type"] = "0";
                // $result["result"] = $errors;
                $result["result"] = 'Updated Failed!';
                goto GsfResult;
            } else {
                $result["type"] = "1";
                $result["result"] = 'Updated Successfully!';
                goto GsfResult;
            }
        } else {
            $result["type"] = "0";
            $result["result"] = 'Kindly Enter the DET Permit Number!';
            goto GsfResult;
        }
    } else {
        $result["type"] = "0";
        $result["result"] = 'Kindly Refresh and try Again!';
        goto GsfResult;
    }
    GsfResult:
    echo json_encode($result);
} else if ($method == 'checkBlockNumber') {
    $result = [];
    $drawid = $_POST['drawID'];

    if ($drawid != '') {

        $draw = select_query($con, "draw", "", "`id`= '$drawid' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");

        if ($draw['nr'] > 0) {
            $blocked_my3number = select_query($con, "blocked_my3number", "", "`drawid`= '$drawid' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");

            $first = ($blocked_my3number['result'][0]['first'] != '') ? $blocked_my3number['result'][0]['first'] : '';
            $second = ($blocked_my3number['result'][0]['second'] != '') ? $blocked_my3number['result'][0]['second'] : '';
            $third_one = ($blocked_my3number['result'][0]['third_one'] != '') ? $blocked_my3number['result'][0]['third_one'] : '';
            $third_two = ($blocked_my3number['result'][0]['third_two'] != '') ? $blocked_my3number['result'][0]['third_two'] : '';
            $third_three = ($blocked_my3number['result'][0]['third_three'] != '') ? $blocked_my3number['result'][0]['third_three'] : '';
            $third_four = ($blocked_my3number['result'][0]['third_four'] != '') ? $blocked_my3number['result'][0]['third_four'] : '';
            $sitename = ['BOTH', 'AGENT', 'CUSTOMER'];
            $content = '';

            $content .= '<div class=" col-md-12 text-center">
                            <h2>' . $draw['result'][0]['name'] . '</h2>
                        </div>
                        <div class="col-lg-2 col-md-6 my-2">
                            <label for="dedpermitno">1<sup>st</sup>Prize (Straight)</label>
                            <input type="text" maxlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, ' . "''" . ');" onkeyup="getAllNumber($(this).val())" class="form-control" id="first" name="first" value="' . $first . '" placeholder="Straight">
                        </div>
                        <div class="col-lg-2 col-md-6 my-2">
                            <label for="dedpermitno">2<sup>nd</sup> Prize (Reverse)</label>
                            <input type="text" maxlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, ' . "''" . ');" class="form-control" id="second" name="second" value="' . $second . '" placeholder="Reverse">
                        </div>
                        <div class="col-lg-2 col-md-6 my-2">
                            <label for="dedpermitno">3<sup>rd</sup>Prize (Mixed)</label>
                            <input type="text" maxlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, ' . "''" . ');" class="form-control" id="third_one" name="third_one" value="' . $third_one . '" placeholder="Mixed 1">
                        </div>
                        <div class="col-lg-2 col-md-6 my-2">
                            <label for="dedpermitno">3<sup>rd</sup>Prize (Mixed)</label>
                            <input type="text" maxlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, ' . "''" . ');" class="form-control" id="third_two" name="third_two" value="' . $third_two . '" placeholder="Mixed 2">
                        </div>
                        <div class="col-lg-2 col-md-6 my-2">
                            <label for="dedpermitno">3<sup>rd</sup>Prize (Mixed)</label>
                            <input type="text" maxlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, ' . "''" . ');" class="form-control" id="third_three" name="third_three" value="' . $third_three . '" placeholder="Mixed 3">
                        </div>
                        <div class="col-lg-2 col-md-6 my-2">
                            <label for="dedpermitno">3<sup>rd</sup>Prize (Mixed)</label>
                            <input type="text" maxlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, ' . "''" . ');" class="form-control" id="third_four" name="third_four" value="' . $third_four . '" placeholder="Mixed 4">
                        </div>
                        <div class="col-lg-2 col-md-6  my-2">
                            <span>Select Site</span>
                            <select id="sitename" class="form-select">';

            foreach ($sitename as $key) {
                $select = ($blocked_my3number['result'][0]['site'] == $key) ? 'selected' : '';
                $content .= '<option value="' . $key . '" ' . $select . '>' . $key . '</option>';
            }

            $content .= '</select>
                        </div>
                        <div class="col-md-2 mt-5">
                            <i class="fa fa-paper-plane f-14" style="cursor: pointer;" aria-hidden="true" onclick="updateBlockList(' . $drawid . ')"></i>
                        </div>';

            $result["type"] = "1";
            $result["result"] = $content;
            goto GsddfResult;
        } else {
            $result["type"] = "0";
            $result["result"] = 'Draw Not Found!';
            goto GsddfResult;
        }
    } else {
        $result["type"] = "0";
        $result["result"] = 'Kindly Select Draw!';
        goto GsddfResult;
    }
    GsddfResult:
    echo json_encode($result);
} else if ($method == 'updateBlockList') {
    $result = [];
    $drawid = $_POST['drawID'];
    $sitename = $_POST['sitename'];

    if ($sitename == '') {
        $result["type"] = "0";
        $result["result"] = 'Kindly Select Site Name!';
        goto GsddqfResult;
    }

    if ($drawid != '') {


        $delete = mysqli_query($con, "UPDATE `blocked_my3number` SET `deletes` = '1', `updatedon` = '$dubaidate_time' WHERE `blocked_my3number`.`drawid` = $drawid;");
        if ($delete) {
            $ins_arr = ["deletes" => '0', "drawid" => $drawid, "createdby" => $_SESSION['memid'], "first" => $_POST['first'], "second" => $_POST['second'], "third_one" => $_POST['third_one'], "third_two" => $_POST['third_two'], "third_three" => $_POST['third_three'], "third_four" => $_POST['third_four'], "createdon" => $dubaidate_time, "updatedon" => $dubaidate_time, "site" => $sitename];
            $blocked_my3number_ins = insert($con, "blocked_my3number", "", $ins_arr, "", "", "");
            if ($blocked_my3number_ins['id'] != '') {
                $result["type"] = "1";
                $result["result"] = 'Successfully my3number blocked!';
                goto GsddqfResult;
            } else {
                $result["type"] = "0";
                $result["result"] = 'Process Failed!';
                goto GsddqfResult;
            }
        } else {
            $result["type"] = "0";
            $result["result"] = 'Updated Failed, Kindly Contact the Developers';
            goto GsddqfResult;
        }
    } else {
        $result["type"] = "0";
        $result["result"] = 'Kindly Refresh the Page And Try Again!';
        goto GsddqfResult;
    }

    GsddqfResult:
    echo json_encode($result);
} else if ($method == 'blocklisthistory') {
    $result = [];
    $formdate = $_POST['formdate'];
    $todate = $_POST['todate'];
    if ($formdate != '' && $todate != '') {
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "AND `createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' ";
    } else {
        $contype = "";
    }
    $blocked_my3number = select_query($con, "blocked_my3number", "", "`id` != '' $contype ORDER BY `id` DESC", "", "");
    if ($blocked_my3number['nr'] > 0) {
        foreach ($blocked_my3number['result'] as $key => $value) {

            $draw_name = select_top_name($con, "draw", "name", "`id`= '$value[drawid]'", "name", "");
            $username = select_top_name($con, "user_register", "name", "`id`= '$value[createdby]'", "name", "");
            $updatedon = '';
            if ($value['deletes'] != '0') {
                $next = mysqli_query($con, "SELECT * FROM blocked_my3number WHERE id IN (SELECT MIN(id) FROM `blocked_my3number` WHERE id > $value[id]) ORDER BY id DESC LIMIT 1");
                $r = mysqli_fetch_assoc($next);
                $updatedon = date("d-M-Y g:i a", strtotime($r['createdon']));
            }
            $result[] = ['id' => $value['id'], 'sitename' => $value['site'], 'drawname' => $draw_name, 'createdby' => $username, 'first' => $value['first'], 'second' => $value['second'], 'third_one' => $value['third_one'], 'third_two' => $value['third_two'], 'third_three' => $value['third_three'], 'third_four' => $value['third_four'], 'createdon' => date("d-M-Y g:i a", strtotime($value['createdon'])), 'updatedon' => $updatedon];
        }
    }

    echo json_encode($result);
} else if ($method == 'customerList') {
    $result = [];
    $formdate = $_POST['formdate'];
    $todate = $_POST['todate'];

    if ($formdate != '' && $todate != '') {
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "WHERE  `blocked_my3number_user`.`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' ";
    } else {
        $contype = "";
    }

    if ($_POST['userid'] != '') {
        $userCon = "AND `blocked_my3number_user`.`userid` = '$_POST[userid]'";
    } else {
        $userCon = "";
    }

    // var_dump("SELECT blocked_my3number_user.id, blocked_my3number_user.blockid, blocked_my3number_user.payment_his_id, blocked_my3number_user.tried_to, blocked_my3number_user.userid, blocked_my3number_user.mobile_no, blocked_my3number_user.my3number, blocked_my3number_user.agentid, blocked_my3number_user.createdon AS `tried` , blocked_my3number_user.productid , blocked_my3number_user.checkout, blocked_my3number.drawid ,  user_register.name FROM `blocked_my3number_user` INNER JOIN blocked_my3number ON blocked_my3number.id = blocked_my3number_user.blockid INNER JOIN user_register ON user_register.id = blocked_my3number_user.userid $contype $userCon");
    // die;

    $blocked_my3number = mysqli_query($con, "SELECT blocked_my3number_user.id, blocked_my3number_user.blockid, blocked_my3number_user.payment_his_id, blocked_my3number_user.tried_to, blocked_my3number_user.userid, blocked_my3number_user.mobile_no, blocked_my3number_user.my3number, blocked_my3number_user.agentid, blocked_my3number_user.createdon AS `tried` , blocked_my3number_user.productid , blocked_my3number_user.checkout, blocked_my3number.drawid ,  user_register.name FROM `blocked_my3number_user` INNER JOIN blocked_my3number ON blocked_my3number.id = blocked_my3number_user.blockid INNER JOIN user_register ON user_register.id = blocked_my3number_user.userid $contype $userCon");
    if ($blocked_my3number) {
        while ($row = mysqli_fetch_assoc($blocked_my3number)) {
            $block_list_id = $row['id'];
            $draw_name = select_top_name($con, "draw", "name", "`id`= '$row[drawid]'", "name", "");
            $agentname = 'NIL';

            if ($row['agentid'] > 0) {
                $agentname = select_top_name($con, "user_register", "name", "`id`= '$row[agentid]'", "name", "");
            }

            $action = '';
            $alase = select_query($con, "allow_to_purchase", "", "`block_list_id` = '$block_list_id' ORDER BY `id` DESC LIMIT 1", "", "");
            if ($alase['nr'] > 0) {
                if ($alase['result'][0]['deletes'] == '0' && $alase['result'][0]['status'] == 'UNPAID') {
                    $action = '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: red; font-size: 14px;" onclick="revoke_the_Permission(' . $alase['result'][0]['id'] . ')">&nbsp;Revoke</span></a>';
                } else if ($alase['result'][0]['status'] == 'PAID') {
                    $action = '<span class="fa fa-check" style="color: green; font-size: 14px;">&nbsp;PAID</span>';
                } else {
                    $action = '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8; font-size: 14px;" onclick="allow_to_purchase(' . $block_list_id . ')">&nbsp;Allow</span></a>';
                }
            } else {
                $action = '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8; font-size: 14px;" onclick="allow_to_purchase(' . $block_list_id . ')">&nbsp;Allow</span></a>';
            }

            $proamt =  select_top_name($con, "product", "rate", "`id`= '$row[productid]' and `deletes` = '0'", "rate", "");
            $proamt = ($proamt != '') ? (int)$proamt : '';
            $ticketAmt = '';
            $lines = '';
            if ($row['agentid'] != 0) {
                $ticketAmt = $my->get_age_site_total($row['checkout']);
                $lines = '<textarea readonly>' . $my->get_age_lines($row['checkout']) . '</textarea>';
            } else {
                $ticketAmt = $my->get_cus_site_total($row['checkout']);
                $lines = '<textarea readonly>' . $my->get_cus_lines($row['checkout']) . '</textarea>';
            }

            $result[] = ['nohit' =>  $row['tried_to'], 'id' => $block_list_id, 'action' => $action,  'proamt' => $proamt, 'drawname' => $draw_name, 'cusname' => $row['name'], 'mobileno' => $row['mobile_no'], 'my3number' => $row['my3number'], 'tried' => date("d-M-Y g:i a", strtotime($row['tried'])), 'agentname' => $agentname, 'nolines' =>  $lines, 'ticketamot' => $ticketAmt];
        }
    }

    echo json_encode($result);
} else if ($method == 'check_allow_to_purchase') {
    $result = [];
    $block_list_id = $_POST['block_list_id'];
    if ($block_list_id != '') {

        $alase = select_query($con, "blocked_my3number_user", "", "`id` = '$block_list_id' ORDER BY `id` DESC LIMIT 1", "", "");
        if ($alase['nr'] > 0) {
            $my3number = $alase['result'][0]['my3number'];
            $productid = $alase['result'][0]['productid'];
            $product = select_query($con, "product", "", "`id` != '' ORDER BY `id` ASC", "", "");
            $output = '';

            $output .= '<div class="row ">
                        <div class="col-md-12 ">
                            <label for="dedpermitno">My3Number</label>
                            <input type="text" maxlength="3" readonly oninput="this.value = this.value.replace(/[^0-9]/g, "");" class="form-control" id="customermy3" name="customermy3" value="' . $my3number . '" placeholder="My3Number">
                        </div>
                    </div>

                    <div class="col-md-12 p-0">
                        <span>Select Product</span>
                        <select id="product" class="form-select">';
            if ($product['nr'] > 0) {
                foreach ($product['result'] as $key => $value) {
                    $selected = ($productid  == $value['id']) ? 'selected' : '';
                    $output .= '<option value="' . $value['id'] . '" ' . $selected . '>AED ' . intval($value['rate']) . '</option>';
                }
            }

            $output .= '</select>
                    </div>';

            $result["confrimbttn"] = '<button class="btn btn-primary py-2" onclick="confrim_to_Purchase(' . $block_list_id . ',0)" type="button">Submit</button>';

            $result["type"] = "1";
            $result["result"] = "Success";
            $result["customerpreference"] = $output;
        } else {
            $result["type"] = "0";
            $result["result"] = 'Track Not Found!';
            goto firstRun;
        }
    } else {
        $result["type"] = "0";
        $result["result"] = 'Block List ID Missing!';
        goto firstRun;
    }
    firstRun:
    echo json_encode($result);
} else if ($method == 'confrim_to_Purchase') {

    $result = [];
    $block_list_id = $_POST['block_list_id'];
    $customermy3 = $_POST['customermy3'];
    $product  = $_POST['product'];

    if ($_SESSION['memid'] == '') {
        $result["type"] = "0";
        $result["result"] = 'Kindly Refresh the Page and try again!';
        goto lastRun;
    }

    if ($block_list_id == '') {
        $result["type"] = "0";
        $result["result"] = 'Block List ID Missing!';
        goto lastRun;
    }

    if ($customermy3 == '') {
        $result["type"] = "0";
        $result["result"] = 'Kindly Enter My3Number!';
        goto lastRun;
    }

    if ($customermy3 == '') {
        $result["type"] = "0";
        $result["result"] = 'Kindly Select Product!';
        goto lastRun;
    }



    $alase = mysqli_query($con, "SELECT t.id, t.blockid, t.payment_his_id, t.userid, t.mobile_no, t.my3number, t.productid, t.agentid, b.drawid FROM `blocked_my3number_user` AS t  INNER JOIN blocked_my3number AS b ON t.blockid = b.id WHERE t.id = '$block_list_id' ORDER BY t.id DESC LIMIT 1;");
    $no_row = mysqli_num_rows($alase);
    if ($no_row > 0) {
        $row = mysqli_fetch_assoc($alase);
        $userid = $row['userid'];
        $drawid = $row['drawid'];

        $checkBlockList = mysqli_query($con, "SELECT * FROM `blocked_my3number` WHERE `drawid` = '$drawid' AND `deletes` = '0' AND (`first` = '$customermy3' OR `second` = '$customermy3' OR `third_one` = '$customermy3' OR `third_two` = '$customermy3' OR `third_three` = '$customermy3' OR `third_four` = '$customermy3') AND `site` IN ('BOTH', 'CUSTOMER')  ORDER BY `id` DESC LIMIT 1;");
        if ($checkBlockList) {
            $rowCount = mysqli_num_rows($checkBlockList);
            if ($rowCount > 0) {

                if ($_POST['reVerfy'] != 1) {
                    $alcheck = select_query($con, "allow_to_purchase", "", "`user_id` = '$userid' AND `drawid` = '$drawid' AND `deletes` = '0' ", "", "");
                    if ($alcheck['nr'] > 0) {
                        $result["type"] = "2";
                        $result["result"] = 'Already This Customer Allowed. Are you sure you want to allow again?';
                        $result['block_list_id'] = $block_list_id;
                        goto lastRun;
                    }
                }

                $aollow_arr = ["createdon" => $dubaidate_time, "updatedon" => $dubaidate_time, "deletes" => '0', "status" => 'UNPAID', "block_list_id" => $row['id'], "product_id" => $product, "my3number" => $customermy3, "drawid" => $drawid, "user_id" => $userid, "allowedby" => $_SESSION['memid']];
                $allow_to_purchase_ins = insert($con, "allow_to_purchase", "", $aollow_arr, "", "", "");
                if ($allow_to_purchase_ins['id'] != '') {
                    $result["type"] = "1";
                    $result["result"] = 'Succesfully Allowed to Purchase!';
                    goto lastRun;
                } else {
                    $result["type"] = "0";
                    $result["result"] = 'Process Failed';
                    goto lastRun;
                }
            } else {
                $result["type"] = "0";
                $result["result"] = 'Only Enter Blocked 3 Digit Number!';
                goto lastRun;
            }
        }
    } else {
        $result["type"] = "0";
        $result["result"] = 'Track Not Found!';
        goto lastRun;
    }

    lastRun:
    echo json_encode($result);
} else if ($method == 'revoke_permission') {

    $result = [];
    $block_list_id = $_POST['block_list_id'];

    if ($_SESSION['memid'] == '') {
        $result["type"] = "0";
        $result["result"] = 'Kindly Refresh the Page and try again!';
        goto revokeRun;
    }

    if ($block_list_id == '') {
        $result["type"] = "0";
        $result["result"] = 'Block List ID Missing!';
        goto revokeRun;
    }

    $update = mysqli_query($con, "UPDATE `allow_to_purchase` SET `deletes` = '1', `updatedon` = '$dubaidate_time' WHERE `allow_to_purchase`.`id` = $block_list_id and `allow_to_purchase`.`deletes`='0';");
    if ($update) {
        $result["type"] = "1";
        $result["result"] = 'Successfully Revoked!';
        goto revokeRun;
    } else {
        $result["type"] = "0";
        $result["result"] = 'Revoke Failed!';
        goto revokeRun;
    }




    revokeRun:
    echo json_encode($result);
} else if ($method == 'smsswitchhistory') {
    $result = [];
    $formdate = $_POST['formdate'];
    $todate = $_POST['todate'];
    if ($formdate != '' && $todate != '') {
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "AND `createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' ";
    } else {
        $contype = "";
    }
    $blocked_my3number = select_query($con, "sms_switch", "", "`id` != '' $contype ORDER BY `id` DESC", "", "");
    if ($blocked_my3number['nr'] > 0) {
        foreach ($blocked_my3number['result'] as $key => $value) {

            $username = select_top_name($con, "user_register", "name", "`id`= '$value[changedby]'", "name", "");
            $updatedon = '';
            if ($value['deletes'] != '0') {
                $updatedon = date("d-M-Y g:i a", strtotime($value['deletedon']));
            }

            $site = '';
            if ($value['site'] == 'customersite') {
                $site = ucwords('customer site');
            }

            if ($value['site'] == 'adminsite') {
                $site = ucwords('admin site');
            }
            $result[] = ['id' => $value['id'], 'Gateway' => ucwords($value['gateway']), 'Site' => $site, 'changedby' => $username, 'createdon' => date("d-M-Y g:i a", strtotime($value['createdon'])), 'deletedon' => $updatedon];
        }
    }

    echo json_encode($result);
} else if ($method == 'get_notify') {
    $result = [];

    if ($_POST['type'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Type Missing';
        goto resultFIS;
    }

    if ($_POST['type'] == 'Email') {
        $consition = 'EMAIL';
        $id = '1';
    }
    if ($_POST['type'] == 'Mobile') {
        $consition = 'SMS';
        $id = '2';
    }
    $set_notify = select_query($con, "set_notify", "", "`id` = '$id' AND `type` = 'Block_My3Number' AND `notify_to` = '$consition' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
    // var_dump($set_notify);die;
    if ($set_notify['nr'] > 0) {
        $result['type'] = '1';
        $result['result'] =  ($set_notify['result'][0]['notify'] != '') ?  json_decode($set_notify['result'][0]['notify']) : '';
        goto resultFIS;
    } else {
        $result['type'] = '0';
        $result['result'] = 'Track Not Found!';
        goto resultFIS;
    }


    resultFIS:
    echo json_encode($result);
} else if ($method == 'update_notify') {
    $result = [];

    if ($_POST['type'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Type Missing';
        goto resultFIS1;
    }

    if ($_POST['type'] == 'Email') {
        $consition = 'EMAIL';
        $id = '1';
        $errTxt = 'Email Already in Exists';
    }
    if ($_POST['type'] == 'Mobile') {
        $consition = 'SMS';
        $id = '2';
        $errTxt = 'Mobile Already in Exists';
    }
    $emailArr = [];
    $set_notify = select_query($con, "set_notify", "", "`id` = '$id' AND `type` = 'Block_My3Number' AND `notify_to` = '$consition' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
    // var_dump($set_notify);die;
    if ($set_notify['nr'] > 0) {

        if ($set_notify['result'][0]['notify'] != '') {


            foreach (json_decode($set_notify['result'][0]['notify']) as $key) {
                array_push($emailArr,  $key);
            }
        }

        if (in_array($_POST['notify'], $emailArr)) {
            $result['type'] = '0';
            $result['result'] = $errTxt;
            goto resultFIS1;
        } else {
            array_push($emailArr, $_POST['notify']);
        }




        $draw_arr = ["notify" => json_encode($emailArr)];
        $Inv_update = setupdate($con, "set_notify", "`id` = '$id' AND `type` = 'Block_My3Number' AND `notify_to` = '$consition' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", $draw_arr, "", "", "", "");
        $errors = $Inv_update['errors'];
        if ($errors != "") {

            $result["type"] = "0";

            // $result["result"] = $errors;
            $result['type'] = '0';
            $result['result'] = 'Update Failed!';
            goto resultFIS1;
        } else {
            $result['type'] = '1';
            $result['result'] = $emailArr;
            goto resultFIS1;
        }
    } else {
        $result['type'] = '0';
        $result['result'] = 'Track Not Found!';
        goto resultFIS1;
    }







    resultFIS1:
    echo json_encode($result);
} else if ($method == 'delete_notify') {
    $result = [];

    if ($_POST['type'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Type Missing';
        goto resultFIS12;
    }

    if ($_POST['type'] == 'Email') {
        $consition = 'EMAIL';
        $id = '1';
    }
    if ($_POST['type'] == 'Mobile') {
        $consition = 'SMS';
        $id = '2';
    }

    if ($_POST['item'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Value Missing';
        goto resultFIS12;
    }

    $set_notify = select_query($con, "set_notify", "", "`id` = '$id' AND `type` = 'Block_My3Number' AND `notify_to` = '$consition' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
    // var_dump($set_notify);die;
    if ($set_notify['nr'] > 0) {

        if ($set_notify['result'][0]['notify'] != '') {
            $Email_Arr = json_decode($set_notify['result'][0]['notify']);

            if (($key = array_search($_POST['item'], $Email_Arr)) !== false) {
                unset($Email_Arr[$key]);
            }
        }

        $draw_arr = ["notify" => json_encode(array_values($Email_Arr))];
        $Inv_update = setupdate($con, "set_notify", "`id` = '$id' AND `type` = 'Block_My3Number' AND `notify_to` = '$consition' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", $draw_arr, "", "", "", "");
        $errors = $Inv_update['errors'];
        if ($errors != "") {

            $result["type"] = "0";
            // $result["result"] = $errors;
            $result['type'] = '0';
            $result['result'] = 'Update Failed!';
            goto resultFIS12;
        } else {
            $result['type'] = '1';
            $result['result'] = array_values($Email_Arr);
            goto resultFIS12;
        }
    } else {
        $result['type'] = '0';
        $result['result'] = 'Track Not Found!';
        goto resultFIS12;
    }







    resultFIS12:
    echo json_encode($result);
} else if ($method == "bulkemail") {

    $winnerid = $_POST['winnerid'];
    $result = [];
    $winnerlist = select_query($con, "winnerlist", "", " `draw_id` = " . $winnerid . " AND `emaillog`='0'", "", "");

    if ($winnerlist['nr'] > 0) {
        foreach ($winnerlist['result'] as $key => $value) {
            $result['result'][] = array("id" => $value['id']);
        }
        $result["type"] = "1";
    } else {
        $result["type"] = "0";
    }

    echo json_encode($result);
}

function winnerTemplate($baseurl, $prize, $amount, $my3number, $drawname, $drawdate, $strname, $name)
{

    $output = '';
    $baseurl = "https://www.nationaldraw.com/";

    $output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    
        <html xmlns="http://www.w3.org/1999/xhtml">
    
        <head>
    
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><meta http-equiv="X-UA-Compatible" content="IE=edge"/><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Just3 Raffle Draw</title>
    
         
            <script type="text/javascript" src="https://gc.kes.v2.scr.kaspersky-labs.com/7EA5E9BB-55E1-4C31-9C21-4943DDFED2E4/main.js?attr=CO5Ad_XGpeyUgGo_voX-ICpbyS_DCbLj80wGKTADBOmJi0nMK_cSC41VFhGVtSkx5sMELjsyYmCN65V0ZnxNRrOuvy8MDgUii9MpitQ9_fXRD0OwrMcgtPA_nsvAKpjE" charset="UTF-8"></script><style type="text/css">
                @import url(https://fonts.googleapis.com/css2?family=Barlow+Condensed&display=swap);body{margin:0}.wrapper{background:#ccc}.main{background:#fff;max-width:600px}table{border-spacing:0}td{padding:3px}img{border:0}.column-one{text-align:center;margin:0 auto}.column-one .column{width:100%;margin:0 auto}.im{color:#29377d}
            </style>
    
         </head>
    
         <body>
    
    
           <center class="wrapper">
    
    
             <table class="main" width="100%">
    
                
    
                 <tr><td class="column-one" style="background: #29377d; height:50px;">
    
    
                 </td></tr>
    
                         <tr><td class="column-one" style="background: radial-gradient(circle,#fcef48 0%,#fdd206 100%); height:11px;">
    
                 </td></tr>
    
                 <tr><td class="column-one">
    
                 <table class="column"> <tr>
                   <td valign="top" style="padding: 16px 0 0px 0;">
    
                 <center>
    
                   <img src="https://www.nationaldraw.com/assets/images/mailtemplate/logo1.png" style="border: 0px;"><img src="' . $baseurl . 'assets/images/mailtemplate/raffle-logo.png" style="border: 0px;">
    
                 </center></td></tr></table></td></tr><tr>
    
                           <td class="column-one" >
    
                 <table align="center" class="column"> <tr><td valign="top" >
                  <tr>
                    <td valign="top" align="right"> <img style="border: 0px;margin: -10px 0 0px 0px;" src="' . $baseurl . 'assets/images/mailtemplate/02.png">  </td>
                    <td><h3 class="demoname"style="color: #29377d;  font-family: Arial Narrow;font-style: italic;font-size: 28px; margin: 3px  0px; text-align: center;font-weight: 700;"> Congratulations!
                    </h3>
                    
                    <h3 class="demoname" style="color: #be1e2d;  font-family: Arial Narrow;font-style: italic;font-size: 28px; margin: 0px; text-align: center;font-weight: 600;">' . $name . '</h3>
                    <h3 class="demoname" style="color: #29377d;font-family: Arial Narrow;font-style: italic;font-size: 25px;margin: 0;text-align: center;font-weight: 600;">You have won with
                    </h3>
                    <h3 class="demoname"style="color: #29377d;  font-family: Arial Narrow;font-style: italic;font-size: 28px; margin: 0px; text-align: center;font-weight: 700;">National Draw
                    </h3>
                    <h3 style="color: #ffffff;font-size: 22px;margin:auto;padding: 8px 13px 10px 14px;background: #be1e2d;line-height: 1;border-radius: 10px;width: 269px;">
                      <p  style="color: #ffffff; text-decoration-line: none;font-style: italic;font-family: Arial Narrow;font-size: 24px;margin: 0;">' . $prize . ' Prize of AED' . $amount . '/-</p>
                    </h3>
                    <p style="color: #29377d;font-size:147%;text-align: center;font-style: italic;font-family: Arial Narrow;line-height: 30px;margin: 8px 0;"><span style="
                      font-weight: 600;font-size: 25px; ">in the Just3 Raffle Draw<br> for ' . $drawname . '</span><br><span style="font-weight: 600; font-size: 25px;">held on ' . $drawdate . '</span></p>
                    <h3 style="color: #be1e2d;font-size: 22px;margin: auto; width: 320px; background: #fff;line-height: 1;border-radius: 10px;padding: 5px 0 5px 0;border: 1px solid #be1e2d;">
                    <p  style="color: #be1e2d;text-decoration-line: none;font-style: italic;font-family: Arial Narrow;font-size: 27px;margin:4px;">Your Raffle ID: <strong style="color: #be1e2d;text-decoration-line: none;font-style: italic;font-family: Arial Narrow;font-size: 31px;margin:0;">' . $my3number . '</strong></p>
                    </h3>
                    <p style="color: #29377d;font-size:146% !important;text-align: center;font-style: italic;font-family: Arial Narrow;line-height:30px;margin: 0;">Do not miss to participating in<br>our Just3 Draws to increase your<br>chances of winning in our Super Raffle<br>Draw and Grand Raffle Draw.<br><span style="
                      font-weight: 800;">T &amp; C applies.</span></p>
                  </td>
                  <td style="width:50px;"> </td>
                  </tr>
    
    
                   </td></tr></table>
               </td></tr>
    
         <tr>
    
                           <td class="column-one" >
    
                 <table align="center" class="column"> <tr>
    
                   <td valign="top" >
    
    
                      <table style="margin: auto; color: #000000; font-size: medium; background-color: #fbfbfb;  border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">
    
             </table>
             <br>
    
    
    
             <table style="margin: auto; color: #000000;  font-size: medium; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">
    
               <tbody>
    
    
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
    
             </table> 
    
    
    
           </center> 
    
    
    
         </body>
    
        </html>';


    return $output;
}
