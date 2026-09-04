<?php

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

$result = array();
// $post_csrf = $headers['X-Csrf-Token'] ?? '';



function generateNumericOTP($n)

{

    $generator = "1357902468";

    $result = "";

    for ($i = 1; $i <= $n; $i++) {

        $result .= substr($generator, (rand() % (strlen($generator))), 1);
    }

    return $result;
}

if ($method == "send_otp") {

    $result = [];
    $title = '';
    $emdata = '';
    $email = BlockSQLInjection($_POST["email"]);
    $mobile = BlockSQLInjection($_POST["mobile"]);
    $lname  = $_POST['lname'];

    $NewPassword = md5($_POST['Password']);


    if ($lname == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly enter last name";
        goto si;
    }


    if ($mobile == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly enter Mobile No";
        goto si;
    }

    if ($email == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly enter Email ID";
        goto si;
    }

    $n = 4; // 4 OR 6 Only

    $otp = generateNumericOTP($n);
    $sql = "SELECT * FROM `user_register` WHERE `mobile` = '$_POST[mobile]' AND `status` = 0 AND `deletes` = 0";
    $existcheck = mysqli_query($con, $sql);
    if (mysqli_num_rows($existcheck) > 0) {
        $result["type"] = "0";
        $result["result"] = "Mobile number already exists";
        goto si;
    } else {
        if ($email == '') {
            goto SkipEmail;
        }


        $existcheck = mysqli_query($con, "SELECT * FROM `user_register` WHERE `email` = '$_POST[email]' AND `status` = 0 AND `deletes` = 0");
        if (mysqli_num_rows($existcheck) > 0) {
            $result["type"] = "0";
            $result["result"] = "Email already exists";
            goto si;
        } else {
            SkipEmail:
            $_SESSION['otp'] = $otp;
            $nationality = '';
            if ($_POST['nationlaity'] != '') {
                $nation = BlockSQLInjection($_POST["nationlaity"]);


                $inNation = select_top_name($con, "countries", "name", "`flag` = '1' AND `id` = '$nation' AND `name` != '' ORDER BY `id` DESC", "name", "");
                $nationality = $con->real_escape_string($inNation);
            }
            $address = '';
            if ($_POST['billing_address'] != '') {
                $billadd = BlockSQLInjection($_POST["billing_address"]);


                $inState =   select_top_name($con, "states", "name", "`flag` = '1' AND `id` = '$billadd' AND `name` != '' ORDER BY `id` DESC", "name", "");
                $address =  $con->real_escape_string($inState);
            }
            $city = '';
            if ($_POST['billing_city'] != '') {
                $billcity = BlockSQLInjection($_POST["billing_city"]);


                $inCity =  select_top_name($con, "cities", "name", "`flag` = '1' AND `id` = '$billcity' AND `name` != '' ORDER BY `id` DESC", "name", "");
                $city =  $con->real_escape_string($inCity);
            }

            $sql2 = "INSERT INTO `users_temp` (`user`, `roll_id`, `name`, `mobile`, `email`, `passport`, `deletes`, `status`, `created_at` , `otp`, `pass`, `created_by`, `password`,  `building_name`, `address`, `city`, `nationality`, `dialCode`, `lname`)
                    VALUES ('', '0', '$_POST[name]', '$_POST[mobile]', '$_POST[email]', '',  '1', '0', '$dubaidate_time', '$otp', '$NewPassword', '$_SESSION[memid]', '$_POST[Password]', '$_POST[bulidingname]', '$address', '$city', '$nationality', '$_POST[countrycode]', '$lname');";

            $runn = mysqli_query($con, $sql2);
            if ($runn) {
                $insert_id = $con->insert_id;

                if (substr($_POST['mobile'], 0, 3) == "971") {
                    $messages1 = "Hello " . $_POST['name'] . ", " . $otp . " is the One Time Password (OTP) to verification the National draw Account.";

                    sendsms($con, $mobile, $messages1, "");

                    $title = 'Email / Mobile phone';
                    $emdata = $_POST['email'] . ' / ' .  $_POST['mobile'];
                }



                $subject = "National Draw | OTP to Verify Email - " . date("d-m-Y g:i a");

              


                $messages = '<!DOCTYPE html
                PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
             <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                   <meta name="viewport" content="width=device-width, initial-scale=1.0">
                   <title>Registration-OTP</title>
                   <style type="text/css">
                      @import url("https://fonts.googleapis.com/css2?family=Barlow+Condensed&display=swap");
                      @import url("https://fonts.cdnfonts.com/css/verdana");
                      body {
                      margin: 0;
                      }
                      .wrapper {
                      background: #CCC;
                      }
                      .main {
                      background: #FFF;
                      max-width: 600px;
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
                      text-align: center;
                      margin: 0 auto;
                      }
                      .column-one .column {
                      width: 100%;
                      margin: 0 auto;
                      }
                      .im {
                      color: #01104e;
                      }
                      .column-one h3 {
                      color: #01104e;
                      font-family: Verdana, sans-serif !important;
                      font-size: 28px;
                      font-weight: 600;
                      margin: 14px 0 0 0;
                      }
                      .column-one p {
                      color: #01104e;
                      font-family: Verdana, sans-serif !important;
                      font-size: 19px;
                      font-weight: 500;
                      margin: 4px 0;
                      }
                      td.column-two p {
                      text-align: justify;
                      color: #01104e;
                      font-family: Verdana, sans-serif !important;
                      font-size: 17px;
                      font-weight: 500 !important;
                      padding: 0 10%;
                      margin: auto;
                      }
                      .c-f {
                      padding: 8px 0;
                      }
                      ul li {
                      display: inline;
                      border: 2px solid #be1e2d;
                      color: #be1e2d;
                      border-radius: 6px;
                      padding: 4px 10px;
                      font-size: 24px;
                      font-weight: 600;
                      margin: 0 1px;
                      }
                   </style>
                </head>
                <body>
                   <center class="wrapper">
                      <table class="main" width="100%">
                         <!-- BORDER -->
                         <!-- <tr>
                            <td class="column-one f" style="background: #01104e; height:50px;">
                            </td>
                            </tr> -->
                         <tr>
                            <td class="column-one">
                               <table class="column">
                                  <tr>
                                     <td valign="top" style="padding: 0;">
                                        <center>
                                           <br>
                                           <img src="' . constant('assetURL') . 'nationaldraw/1/nationaldrawLogo.png"
                                              style="border: 0px;" width="35%">
                                        </center>
                                     </td>
                                  </tr>
                                  <tr>
                                     <td valign="top" style="padding: 0;">
                                        <center>
                                           <br>
                                           <img src="' . constant('assetURL') . 'nationaldraw/1/RegistrationOTPTemplate.png" style="border: 0px;" width="84%">
                                           <br>
                                        </center>
                                     </td>
                                  </tr>
                               </table>
                            </td>
                         </tr>
                         <!-- LOGO  -->
                         <tr>
                            <td class="column-one c-f">
                               <p
                                  style="font-size:15px; font-weight: 500!important; font-family: Verdana, sans-serif !important; margin:14px 0;">
                                  Please complete your registration
                                  <br>using the OTP below.
                               </p>
                            </td>
                         </tr>
                         <tr>
                            <td>
                               <ul
                                  style="color: #01104e;font-family: Verdana, sans-serif !important;font-size: 15px;font-weight: 500; list-style: none; text-align: center; padding: 0; margin:0 ; line-height: 1.5;">
                                  <li>' . $otp[0] . '</li>
                                  <li>' . $otp[1] . '</li>
                                  <li>' . $otp[2] . '</li>
                                  <li>' . $otp[3] . '</li>
                                 <!-- <li>0</li>
                                  <li>8</li> -->
                               </ul>
                            </td>
                         </tr>
                         <tr>
                            <td class="column-one ">
                               <p
                                  style="font-size:13px; font-weight: 500!important; font-family: Verdana, sans-serif !important; margin:14px 0;">
                                  Note: Your OTP will be valid for only 05 minutes.
                               </p>
                            </td>
                         </tr>
                         <tr>
                            <td class="column-one">
                               <img style="margin-top: 10px;" src="' . constant('assetURL') . 'nationaldraw/1/EmailTemplateFooter.png" width="84%">
                            </td>
                         </tr>
                         <tr>
                            <td>
                               <p
                                  style="color: #01104e !important;font-size: 11px !important;margin: 7px 0px !important;text-align: center !important;font-weight: 500 !important;font-family: Verdana, sans-serif !important;">
                                  Note: This is a system auto-generated email. Please do not reply to this mail.
                               </p>
                            </td>
                         </tr>
                         <tr>
                            <td class="column-one" style="background: #e6e6e6; height:15px;">
                            </td>
                         </tr>
                      </table>
                      <!-- End Main Class -->
                   </center>
                   <!-- End Wrapper -->
                </body>
             </html>';



                if ($email != '') {

                    $emailchack =  explode('@', $email);
                    if (strtolower($emailchack[1]) != "nationaldraw.ae") {
                        $sendemail = sendemail($con, $email, $subject, $messages, 'otp');
                    }


                    $sendemail = true;
                } else {

                    $sendemail = true;
                }

                if ($sendemail) {

                    if ($title == '' && $emdata == '') {

                        $title = 'Email';

                        $emdata = $_POST['email'];
                    }

                    if ($_SESSION['otp'] != "") {

                        $result["title"] = $title;



                        $result["Newmobile"] =  $emdata;



                        $result["type"] = "1";
                        $result["otpbtn"] = '<button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button>
                                                <button class="btn ripple btn-success" onclick="saveformNew(' . "'$insert_id'" . ')" type="button">Submit</button>';
                        $result["result"] = "Otp send Successfully";
                    } else {

                        $result["type"] = "0";

                        $result["result"] = "Otp not send";

                        goto si;
                    }
                } else {

                    $result["type"] = "0";

                    $result["result"] = 'Email OTP Not Send!';

                    goto si;
                }
            } else {
                $result["type"] = "0";
                $result["result"] = 'Couldn`t Sent OTP. Kindly Refresh and Try Again';
                goto si;
            }
        }
    }

    si:
    echo json_encode($result);
} else if ($method == "add_agent_new") {

    if ($_SESSION['memid'] != '') {

        $insert_id = BlockSQLInjection($_POST["insert_id"]);
        // $insert_id =  $_POST['insert_id'];
        if ($insert_id  != '') {
            $result = [];
            $otp = BlockSQLInjection($_REQUEST["otp1"]) . BlockSQLInjection($_REQUEST["otp2"]) . BlockSQLInjection($_REQUEST["otp3"]) . BlockSQLInjection($_REQUEST["otp4"]);

            // $otp = $_REQUEST['otp1'] . $_REQUEST['otp2'] . $_REQUEST['otp3'] . $_REQUEST['otp4'];
            $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC ", "roll_id", "");

            $otpCheck = select_query($con, "users_temp", "", "`id` = '$insert_id' AND `otp` = '$otp' AND `deletes`='1' ORDER BY `id` DESC LIMIT 1", "", "");
            if ($otpCheck['nr'] > 0) {
                $selectID = $otpCheck['result'][0]['id'];

                if ($insert_id === $selectID) {


                    if ($tabID == 'agents') {
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
                    } else if ($tabID == 'affiliate') {
                        $roll_id = 7;
                    } else if ($tabID == 'kioskcreater') {
                        $roll_id = 9;
                    } else if ($tabID == 'support') {
                        $roll_id = 11;
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

                    $inv_arr = ["deletes" => '0', 'roll_id' => $roll_id, 'user' => $user];
                    $Inv_update = update($con, "users_temp", "`id` = '$insert_id' and `deletes`='1' ORDER BY `id` DESC", $inv_arr, "", "", "", "");
                    $errors = $Inv_update['errors'];
                    if ($errors != "") {
                        $result["type"] = "0";
                        $result["result"] = $errors;
                    } else {

                        // Log
                        error_log_new($con, getUserIP(), 'add_agent_new', $insert_id, '', '', 'Start Time :' . $dubaidate_time, json_encode($_POST), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);

                        $run = mysqli_query($con, "INSERT INTO user_register (`user`, `pass`, `password`, `roll_id`, `created_by`,  `name`, `dialCode`, `mobile`, `email`, `dob`, `passport`, `passport_expiry`, `img_url`, `deletes`, `status`, `otp`, `created_at`, `t_point`,  `account_name`, `account_no`, `bank_name`, `bank_address`, `swift_code`, `my_referral_code`, `address`, `nationality`, `residinglocation`, `ip`, `lastlogin`, `city`, `f_points`, `acctype`, `currency_code`, `IBAN_code`, `building_name`, `lname`) SELECT `user`, `pass`, `password`, `roll_id`, `created_by`,  `name`, `dialCode`, `mobile`, `email`, `dob`, `passport`, `passport_expiry`, `img_url`, `deletes`, `status`, `otp`, `created_at`, `t_point`,  `account_name`, `account_no`, `bank_name`, `bank_address`, `swift_code`, `my_referral_code`, `address`, `nationality`, `residinglocation`, `ip`, `lastlogin`, `city`, `f_points`, `acctype`, `currency_code`, `IBAN_code`, `building_name`, `lname` FROM users_temp WHERE id = '$insert_id' AND deletes = '0' ORDER BY `id` DESC LIMIT 1");
                        if ($run) {
                            $userinserid = $con->insert_id;

                            $user_data =   select_query($con, "user_register", "", "`id` = '$userinserid' AND `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                            if ($user_data['nr'] > 0) {
                                $user_roll_id = $user_data['result'][0]['roll_id'];

                                $ro = [];
                                if ($tabID == 'agents') {
                                    if ($user_roll_id == 2) {
                                        $ro = [27, 19, 18, 13, 9, 7, 4, 3, 2, 1, 10, 11, 12, 17, 31, 33];
                                    } else  if ($user_roll_id == 3) {
                                        $ro = [19,  13, 9, 7, 4, 2, 1, 11, 12, 17, 32, 33];
                                    } else if ($user_roll_id == 4) {
                                        $ro = [19,  13, 9, 7, 4, 2, 1, 11, 12, 17, 32, 33];
                                    } else if ($user_roll_id == 5) {
                                        $ro = [19,  13, 9, 4, 2, 1, 11, 12, 17, 32, 33];
                                    }
                                } else if ($tabID == 'staffs') {
                                    $ro = [81, 67, 66, 64, 61, 54, 47, 45, 44, 43, 37, 36, 35, 34, 33, 32, 27, 25, 24, 22, 19, 18, 17, 12, 11, 10, 9, 7, 4, 3, 2, 1, 89];
                                } else if ($tabID == 'affiliate') {
                                    $ro = [1, 2, 9, 34, 106, 93];
                                } else if ($tabID == 'kioskcreater') {
                                    $ro = [1, 9, 5, 34, 135, 133, 134];
                                } else if ($tabID == 'support') {
                                    $ro = [9, 144, 19, 34, 1, 145, 146, 147, 148, 149, 150, 151, 152];
                                }


                                if (count($ro) > 0) {
                                    foreach ($ro as $value) {
                                        $run = mysqli_query($con, "INSERT INTO `menu_permission` (`id`, `userid`, `menu`) VALUES (NULL, '$userinserid', '$value');");
                                    }
                                }

                                $delete = mysqli_query($con, "DELETE FROM `users_temp` WHERE `users_temp`.`id` = $insert_id;");

                                $result["type"] = "1";
                                $result["result"] = ucwords($user) . " Added Successfully";
                            }
                        } else {
                            $result["type"] = "0";
                            $result["result"] = "Not registered!";
                        }
                    }
                } else {
                    $result["type"] = "0";
                    $result["result"] = "Verification Failed!";
                    goto justReturn;
                }
            } else {
                $result["type"] = "0";
                $result["result"] = "Otp does not match";
                goto justReturn;
            }
        }
    } else {
        $result["type"] = "0";
        $result["result"] = "Login Required";
        goto justReturn;
    }

    justReturn:
    echo json_encode($result);
}
