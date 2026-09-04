<?php
//    29-3-2023    Prakash            zepto mail config set
// $sitename = 'demoadmin';
// set_include_path('/home/littledraw/public_html/' . $sitename . '/include/');
$DIR = dirname(__DIR__);
set_include_path($DIR);

require 'include/shi-config.php';

function getUserIP()
{

    // Get real visitor IP behind CloudFlare network

    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {

        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];

        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }

    $client = @$_SERVER['HTTP_CLIENT_IP'];

    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];

    $remote = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {

        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {

        $ip = $forward;
    } else {

        $ip = $remote;
    }

    return $ip;
}

function sendemail($DIR, $con, $email, $subject, $messages,  $log = "1", $frmID = '')
{

    $i = 0;
    // set_include_path('/home/littledraw/public_html/' . $sitename . '/');
    set_include_path($DIR);
    require "PHPMailer-master/src/Exception.php";
    require "PHPMailer-master/src/PHPMailer.php";
    require "PHPMailer-master/src/SMTP.php";
    $mail = new PHPMailer\PHPMailer\PHPMailer;

    $mail->isSMTP();


    recheckMail:
    $fromMail = '';
    if ($frmID != '') {
        $conT = "AND `emailkey` = '$frmID' ORDER BY `id` DESC LIMIT 1";
    } else {
        $conT = "AND `emailkey` = 'all' ORDER BY `id` DESC LIMIT 1";
    }


    $get_Email = mysqli_query($con, "SELECT * FROM `email_config` WHERE `deletes` = '0' AND `status` = '0' $conT;");
    $ncount = mysqli_num_rows($get_Email);
    if ($ncount > 0) {
        $row = mysqli_fetch_assoc($get_Email);
        $fromMail = $row['setFrom'];
        $smtpAu = boolval($row['SMTPAuth']) ? true : false;
        $mail->Host = $row['Host'];
        $mail->SMTPAuth = $smtpAu;
        $mail->Username = $row['Username'];
        $mail->Password = $row['Password'];
        $mail->SMTPSecure = $row['SMTPSecure'];
        $mail->Port = $row['Port'];
        $mail->setFrom($fromMail, $row['fromname']);
        $mail->AddReplyTo($row['AddReplyTo'], 'NATIONAL DRAW');
        if ($row['char_set'] != '') {
            $mail->CharSet = $row['char_set'];
        }

        if ($row['Encoding'] != '') {
            $mail->Encoding = $row['Encoding'];
        }
    } else {
        $frmID = '';
        if ($i == 0) {
            $i++;
            goto recheckMail;
        }
    }

    $mail->addAddress($email);

    $mail->Subject = $subject;
    $mail->isHTML(true);
    $mail->Body = $messages;
    $_SESSION['fromMail'] = $fromMail;
    if (!$mail->send()) {
        $user_ip = getUserIP();
        $datetime = date("Y-m-d H:i:s");
        $messages = mysqli_real_escape_string($con, $messages);
        if ($log === "1") {
            $er = json_encode($mail->ErrorInfo);
            $insertlog = mysqli_query($con, "INSERT INTO `emaillog` (`details`,`subject`,`email`,`ip`,`datetime`,`status`,`sendstatus`,`error_info`,`fromemail`) VALUES ('$messages','$subject','$email','$user_ip','$datetime','1','FAILED','$er','$fromMail');");
        }
        return false;
    } else {
        $user_ip = getUserIP();
        $datetime = date("Y-m-d H:i:s");
        $messages = mysqli_real_escape_string($con, $messages);
        if ($log === "1") {
            $insertlog = mysqli_query($con, "INSERT INTO `emaillog` (`details`,`subject`,`email`,`ip`,`datetime`,`status`,`sendstatus`,`error_info`,`fromemail`) VALUES ('$messages','$subject','$email','$user_ip','$datetime','1','SUCCESS','','$fromMail');");
        }
        return true;
    }
}

$result = [];
// Takes raw data from the request
$json = file_get_contents('php://input');
// Converts it into a PHP object
$data = json_decode($json, true);

$method = isset($data["method"]) ? $data["method"] : "";
if ($method == 'sendmail') {
    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $senderName = trim($data['senderName']);
            $email = trim($data['emailid']);
            $emailtemplated = trim($data['emailtemplated']);
            $subject = trim($data['subject']);
            $log = trim($data['log']);

            if ($senderName == "Draw") {

                if ($email == '') {
                    $result['type'] = 0;
                    $result['result'] = 'The Email ID is Missing. Kindly Send Email ID.';
                    goto Result;
                }

                if ($emailtemplated == '') {
                    $result['type'] = 0;
                    $result['result'] = 'The Email Template is Missing. Kindly Send Email Template.';
                    goto Result;
                }

                if ($subject == '') {
                    $result['type'] = 0;
                    $result['result'] = 'The Email Subject is Missing. Kindly Send Email Subject.';
                    goto Result;
                }

                if ($log == '') {
                    $result['type'] = 0;
                    $result['result'] = 'The Log Status is Missing. Kindly Send Email Subject.';
                    $result['details'] = 'if you send log 1 email log stored to Database. Or You Send 0 Donot stored email log in Database.';
                    goto Result;
                }



                // $pickEmail = '';
                // if ((stripos(strtolower($subject), "link") !== false) || (stripos(strtolower($subject), "payment") !== false)) {
                //     $pickEmail = 'followup';
                // } else if ((stripos(strtolower($subject), "purchase") !== false) || (stripos(strtolower($subject), "purchase confirmation") !== false)) {
                //     $pickEmail = 'tickets';
                // } else if ((stripos(strtolower($subject), "won") !== false) || (stripos(strtolower($subject), "congrats") !== false)) {
                //     $pickEmail = 'results';
                // } else if ((stripos(strtolower($subject), "otp") !== false)) {
                //     $pickEmail = 'otp';
                // } else if ((stripos(strtolower($subject), "Congratulation") !== false)) {
                //     $pickEmail = 'welcome';
                // } else {
                $pickEmail = 'all';
                // }

                $sendMail = sendemail($DIR, $con, $email, $subject, $emailtemplated, $log, $pickEmail);
                $result['fromeamil'] = $_SESSION['fromMail'];
                if ($sendMail) {
                    $result['type'] = 1;
                    $result['result'] = 'SUCCESS';
                    goto Result;
                } else {
                    $result['type'] = 0;
                    $result['result'] = 'FAILED';
                    goto Result;
                }
            } else {
                $result['type'] = 0;
                $result['result'] = 'Kindly USE Correct Sender Name.';
                goto Result;
            }
        } else {
            $result['type'] = 0;
            $result['result'] = $_SERVER['REQUEST_METHOD'] . ' Not Supported.';
            goto Result;
        }

        Result:
        echo json_encode($result);
    } catch (Exception $e) {
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        error_log_new($con, getUserIP(), 'Invoke_Email_API', '', $email, '', 'Email Send API Failed', json_encode($error), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);
    }
}
