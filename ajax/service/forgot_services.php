<?php

include '../../include/shi-config.php';

include '../../include/functions.php';

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";

if ($method == 'forgototp') {
  try {
    $key = $_POST['key'];

    $value = '';

    $mobile = $_POST['mobile'];

    $email = $_POST['email'];

    $n = 4; // 4 OR 6 Only

    $otp = generateNumericOTP($n);

    if ($key == 'email') {

      $value = $email;
    } else if ($key == 'mobile') {

      $value = $mobile;
    }

    if ($mobile != '' || $email != '') {

      if ($mobile != '') {

        $contype = "`mobile`='$mobile'";

        $_SESSION['mobile'] = $mobile;
      }

      if ($email != '') {

        $contype = "`email`='$email'";

        $_SESSION['email'] = $email;
      }

      $usercheck = select_query($con, "user_register", "", "$contype and `deletes`='0' ", "", "");

      if ($usercheck['nr'] > 0) {

        $name = $usercheck['result'][0]['name'];

        if (substr($mobile, 0, 3) == "971") {

          $email = $usercheck['result'][0]['email'];

          $messages = "Hello " . $name . ", " . $otp . " is the One Time Password (OTP) to Forgot Password the National draw Account.";



          $templateid = "";

          sendsms($con, $mobile, $messages, $templateid);
        }

        $subject = 'Forgot password';
        $messages = '<!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">

        <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Registration-Welcome</title>
    <style type="text/css">
        @import url("https://fonts.googleapis.com/css2?family=Barlow+Condensed&display=swap");
      

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
        ul li {
    display: inline;
    border: 2px solid #171f4f;
    color: #171f4f;
    border-radius: 24%;
    padding: 4px 10px;
    font-size: 24px;
    font-weight: 600;
    margin: 0 1px;
}  </style>
</head>

<body>
    <center class="wrapper">
        <table class="main" width="100%">
            <!-- BORDER -->
            <tr>
                <td style="background-color: #171f4f; height: 45px;"></td>
            </tr>
            <tr>
                <td class="column-one" style="background: #088b42;height:10px;">
                </td>
            </tr>
            <!-- <tr>
               <td style="background-color: #339a46; height: 45px;"></td>
               </tr> -->
            <tr>
                <td class="column-one">
                    <table class="column">
                        <tr>
                            <td valign="top" style="padding: 0;">
                                <center>
                                    <br>
                                  <img src="'. constant('assetURL') . 'nationaldraw/1/logo123.png" style="border: 0px; margin:10px 0;  " width="50%">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" style="padding: 0;">
                                <center>
                                    <!-- <br> -->
                                    <img src="'. constant('assetURL') . 'nationaldraw/1/otp-verify-123.png" style="border-radius: 19px;" width="43%">
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
               
               <!-- <p style="font-size: 14px; font-weight: 400!important; font-family: Verdana, sans-serif !important; margin-top: 9px;">Are you excited to Play with Little Draw</p> -->
                  <p style="font-size:17px; font-weight: 400!important; font-family: Verdana, sans-serif !important; margin:11px 0;">
                     Forgot your Password?
                  </p>
                  <p style="font-size: 14px; font-weight: 400!important; font-family: Verdana, sans-serif !important; ">
                    Please use the below OTP to reset your password<br>which will be valid for 15 minutes
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

                    
                    </ul>
                </td>
            </tr>
            <tr>
               <td class="column-one ">
                  <p style="font-size:14px; font-weight: 400!important; font-family: Verdana, sans-serif !important; margin:10px 0;">
                     If you have not forgotten your password,<br>you can safely ignore this email
                  </p>
               </td>
            </tr>
            <!-- <tr>
                <td class="column-one ">
                    <p
                        style="font-size:13px; font-weight: 500!important; font-family: Verdana, sans-serif !important; margin:14px 0;">
                        Note: Your OTP will be valid for only XXX minutes.
                    </p>
                </td>
            </tr> -->
            <tr>
                <td class="column-one">
                    <br>
                     <img style="width: !important;margin-top: 10px;" src="'. constant('assetURL') . 'nationaldraw/1/logo-adress123.png" width="84%">
                </td>
            </tr>
            <tr>
                <td>
                    <p
                        style="color: #171f4f !important;font-size: 11px !important;margin: 7px 0px !important;text-align: center !important;font-weight: 500 !important;font-family: Verdana, sans-serif !important;">
                        Note: This is a system auto-generated email. Please do not reply to this mail.
                    </p>
                </td>
            </tr>
            <tr>
                <td class="column-one" style="background: #171f4f; height:10px;">
                </td>
            </tr>
        </table>
        <!-- End Main Class -->
    </center>
    <!-- End Wrapper -->
</body>

</html>';



        //end//
        $sendemail = false;
        if ($email != "") {
          $emailchack =  explode('@', $email);
          if (strtolower($emailchack[1]) != "littledraw.ae") {
            $sendemail = sendemail($con, $email, $subject, $messages, 'otp');
          }
        }
        if ($sendemail) {

          $_SESSION['otp'] = $otp;

          $output = '';

          $output .= '<div class="col-sm-12 mt-2 bgWhite">';

          $output .= '<div class="title-otp">';

          $output .= 'ENTER OTP';

          $output .= '</div>';

          $output .= '<form action="" class="formwrapper">';

          $output .= '<input class="otp" type="text box" id="otp1" onkeyup="onKeyUpEvent(1, event)" onfocus="onFocusEvent(1)" maxlength=1>';

          $output .= '<input class="otp" type="text box" id="otp2" onkeyup="onKeyUpEvent(2, event)" onfocus="onFocusEvent(2)" maxlength=1>';

          $output .= '<input class="otp" type="tex box" id="otp3" onkeyup="onKeyUpEvent(3, event)" onfocus="onFocusEvent(3)" maxlength=1>';

          $output .= '<input class="otp" type="text box" id="otp4" onkeyup="onKeyUpEvent(4, event)" onfocus="onFocusEvent(4)" maxlength=1>';

          //$output .= '<hr class="mt-4">';

          $output .= '<div class="col-md-12 text-center">';

          $output .= '<button type="button" onclick="verify()" class="btn btn-danger sendotp">Verify OTP</button>';

          $output .= '<button type="button" onclick="resend(' . "'$key'" . ', ' . "'$value'" . ')" class="btn btn-danger sendotp">Resend OTP</button>';

          $output .= '</div>';

          $output .= '</form>';

          $output .= '</div>';

          $result["output"] = $output;

          $result["type"] = "1";

          $result["result"] = "Success!";
        } else {

          $result["type"] = "0";

          $result["result"] = "otp not send";
        }
      } else {

        $result["type"] = $mobile;

        $result["type"] = "0";

        $result["result"] = "user not found";
      }
    }

    echo json_encode($result);
  } catch (Exception $e) {
    $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
    $cron_testarr = array("user_id" => '0', "reason" => json_encode($error), "filename" => 'forgot_services.php', "draw_id" => '0', "creadedon" => $dubaidate_time);
    $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
  }
} else if ($method == 'verify') {

  $otp = $_POST['otp'];

  if ($_SESSION['mobile'] != '' || $_SESSION['email'] != '') {

    if ($_SESSION['otp'] != '') {

      if ($_SESSION['otp'] == $otp) {

        $output = '';

        $output .= '<div class="form-group">';

        $output .= '<label for="pwd">New Password:</label>';

        $output .= '<input type="password" id="newpass" class="form-control" placeholder="Enter New Password" id="pwd">';

        $output .= '</div>';

        $output .= '<div class="form-group">';

        $output .= '<label for="pwd">Confirm Password:</label>';

        $output .= '<input type="password" id="conpass" class="form-control" placeholder="Enter Confirm Password" id="pwd">';

        $output .= '</div>';

        $output .= '<div class="col-md-12 text-center mt-3">';

        $output .= '<button type="button" onclick="updatePassword($(' . "'#newpass'" . ').val(),$(' . "'#conpass'" . ').val() )" class="btn btn-danger sendotp">Change Password</button>';

        $output .= '</div>';

        $result["output"] = $output;

        $result["type"] = "1";

        $result["result"] = "otp matched!";
      } else {

        $result["type"] = "0";

        $result["result"] = "otp does not match";
      }
    }
  }

  echo json_encode($result);
} else if ($method == 'updatePassword') {

  $newpass = $_POST['newpass'];

  $conpass = $_POST['conpass'];

  if ($newpass != '' || $conpass != '') {

    if ($newpass == $conpass) {

      if ($_SESSION['mobile'] != '' || $_SESSION['email'] != '') {

        if ($_SESSION['mobile'] != '') {

          $contype = "`mobile`='$_SESSION[mobile]'";
        }

        if ($_SESSION['email'] != '') {

          $contype = "`email`='$_SESSION[email]'";
        }

        $pass = md5($newpass);

        $update = "update user_register set `pass` = '$pass' where $contype and `deletes`='0'";

        $run = mysqli_query($con, $update);

        if ($run) {

          unset($_SESSION['otp']);

          unset($_SESSION['mobile']);

          unset($_SESSION['email']);

          $result["type"] = "1";

          $result["result"] = "Updated Successfully";
        } else {

          $result["type"] = "0";

          $result["result"] = $errors;
        }
      } else {

        $result["type"] = "0";

        $result["result"] = "otp does not match";
      }
    } else {

      $result["type"] = "0";

      $result["result"] = "password does not match";
    }
  } else {

    $result["type"] = "0";

    $result["result"] = "fill the fields";
  }

  echo json_encode($result);
}



else if ($method == 'update_password') {

  if ($_POST['npass'] != '') {

    if ($_POST['cpass'] != '') {

      if ($_POST['npass'] == $_POST['cpass']) {

        $uid = $_POST['uid'];

        $arr = array("pass" => md5($_POST['npass']));

        $update = update($con, "user_register", "`id` = '$uid' and `deletes`='0'", $arr, "", "", "", "");

        $errors = $update['errors'];

        if ($errors != "") {

          $result["type"] = "0";

          $result["result"] = $errors;
        } else {

          $result["type"] = "1";

          $result["result"] = "Updated Successfully";
        }
      } else {

        $result["type"] = "0";

        $result["result"] = "New password does not match with confirm password ";
      }
    } else {

      $result["type"] = "0";

      $result["result"] = "Enter confirm password";
    }
  } else {

    $result["type"] = "0";

    $result["result"] = "Enter new password";
  }

  echo json_encode($result);
}

function generateNumericOTP($n)
{

  $generator = "1357902468";

  $result = "";

  for ($i = 1; $i <= $n; $i++) {

    $result .= substr($generator, (rand() % (strlen($generator))), 1);
  }

  return $result;
}
