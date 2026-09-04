<?php



include '../../include/shi-config.php';
include '../../include/functions.php';

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


if ($method == "add_new_email") {

    $result = [];

    if ($_SESSION['memid'] == '') {
        $result["type"] = "0";
        $result["result"] = 'Kindly Refresh the Page and try again!';
        goto Vi;
    }

    if ($_POST['host'] == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly Enter the Host!";
        goto Vi;
    }
    if ($_POST['smtpauth'] == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly select SMTP Auth!";
        goto Vi;
    }
    if ($_POST['username'] == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly Enter the Username!";
        goto Vi;
    }
    if ($_POST['password'] == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly Enter the Password!";
        goto Vi;
    }
    if ($_POST['smtpsecure'] == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly select SMTP Secure!";
        goto Vi;
    }
    if ($_POST['port'] == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly Select the Port!";
        goto Vi;
    }
    if ($_POST['fromemail'] == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly Enter the From Email!";
        goto Vi;
    }
    if ($_POST['fromname'] == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly Enter the From Name!";
        goto Vi;
    }
    if ($_POST['replyto'] == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly Enter the From Email!";
        goto Vi;
    }


    $email_config = select_query($con, "email_config", "", "`Username`= '$_POST[username]' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");

$host = BlockSQLInjection($_POST['host']);
$smtpauth = BlockSQLInjection($_POST['smtpauth']);
$username = BlockSQLInjection($_POST['username']);
$password = BlockSQLInjection($_POST['password']);
$smtpsecure = BlockSQLInjection($_POST['smtpsecure']);
$port = BlockSQLInjection($_POST['port']);
$setFrom = BlockSQLInjection($_POST['fromemail']);
$fromname = BlockSQLInjection($_POST['fromname']);
$addReplyTo = BlockSQLInjection($_POST['replyto']);
$char_set = BlockSQLInjection($_POST['cahrset']);
$encoding = BlockSQLInjection($_POST['encoding']);

    $n_arr = [
         'Host' => $host,
    'SMTPAuth' => $smtpauth,
    'Username' => $username,
    'Password' => $password,
    'SMTPSecure' => $smtpsecure,
    'Port' => $port,
    'setFrom' => $setFrom,
    'fromname' => $fromname,
    'AddReplyTo' => $addReplyTo,
    'char_set' => $char_set,
    'Encoding' => $encoding,
        'status' => '1',
        'deletes' => '0',
        'createdon' => $dubaidate_time
    ];

    if ($_POST['id'] == 0) {

        if ($email_config['nr'] > 0) {
            $result["type"] = "0";
            $result["result"] = "Username has been exists!";
            goto Vi;
        }

        $email_config_ins = insert($con, "email_config", "", $n_arr, "", "", "");
        if ($email_config_ins['id'] != '') {
            $ins_arr = [
                'id' => NULL,
                'emailconfig_id' => $email_config_ins['id'],
                'userid' =>  $_SESSION['memid'],
                'reason' => "New Email Added",
                'createdon' =>   $dubaidate_time,
                'type' =>    'Success'
            ];
            $emailswitch_log = insert($con, "emailswitch_log", "", $ins_arr, "", "", "");
            $result["type"] = "1";
            $result["result"] = "Success!";
            goto Vi;
        } else {
            $result["type"] = "0";
            $result["result"] = "Email Couldn`t be added!";
            goto Vi;
        }
    } else {
        $email_config = update($con, "email_config", "`id` = '$_POST[id]' and `deletes`='0'", $n_arr, "", "", "", "");
        $errors = $email_config['errors'];
        if ($errors != "") {
            $result["type"] = "0";
            $result["result"] = 'Update Failed!';
            // $result["result"] = $errors;
            goto Vi;
        } else {

            $ins_arr = [
                'id' => NULL,
                'emailconfig_id' => $_POST['id'],
                'userid' =>  $_SESSION['memid'],
                'reason' => "Email Config Updated",
                'createdon' =>   $dubaidate_time,
                'type' =>    'Updated Successfully'
            ];
            $emailswitch_log = insert($con, "emailswitch_log", "", $ins_arr, "", "", "");


            $result["type"] = "1";
            $result["result"] = 'Updated Successfully';
            goto Vi;
        }
    }




    Vi:
    echo json_encode($result);
} else if ($method == 'emailswitchhistory') {

    $result = [];
        $status = BlockSQLInjection($_POST["status"]);

    // $status = $_POST['status'];
    if ($status != '') {
        $stat_con = "AND `status` = '$status'";
    }

    $email_config = select_query($con, "email_config", "", "`deletes` = '0' $stat_con ORDER BY `id` DESC", "", "");

    if ($email_config['nr'] > 0) {
        foreach ($email_config['result'] as $key => $value) {
            $action = '';

            $amtArr = ['all', 'welcome', 'otp', 'tickets', 'followup', 'results'];
            $dropown = '<select class="form-select" onchange="changeMap($(this).val(), ' . $value['id'] . ')" aria-label="Default select example">';
            foreach ($amtArr  as $key) {
                $ccc = ($key == $value['emailkey']) ? 'selected' : '';
                $dropown .= '<option value="' . $key . '"  ' . $ccc . '>' . ucwords($key) . '</option>';
            }
            $dropown .= '</select>';
            $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer; font-size: 16px;"><span class="fa fa-paper-plane" style="color: #51b512;" onclick="testEmail(' . $value['id'] . ')">&nbsp;Test Mail</span></a>&nbsp;';
            $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer; font-size: 16px;"><span class="fa fa-pencil-square-o" style="color: #001e1e;" onclick="EditEmail(' . $value['id'] . ',' . "'$value[Host]'" . ',' . "'$value[SMTPAuth]'" . ',' . "'$value[Username]'" . ',' . "'$value[Password]'" . ',' . "'$value[SMTPSecure]'" . ',' . "'$value[Port]'" . ',' . "'$value[setFrom]'" . ',' . "'$value[fromname]'" . ',' . "'$value[AddReplyTo]'" . ',' . "'$value[char_set]'" . ',' . "'$value[Encoding]'" . ')">&nbsp;Edit</span></a>&nbsp;';
            if ($value['status'] == 0) {
                $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer; font-size: 16px;"><span class="fa fa-ban" style="color: red;" onclick="emailDeactive(' . $value['id'] . ')">&nbsp;Deactive</span></a>';
            } else {
                $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer; font-size: 16px;"><span class="fa fa-toggle-on" style="color: darkblue;" onclick="emailActive(' . $value['id'] . ')">&nbsp;Active</span></a>';
            }

            $result[] = [
                'id' => $value['id'],
                'host' => $value['Host'],
                'smtpauth' => ($value['SMTPAuth'] == 1) ? true : false,
                'username' => $value['Username'],
                'password' => '<input class="form-control" type="text" value="' . $value['Password'] . '" readonly="">',
                'smtpsecure' => strtoupper($value['SMTPSecure']),
                'port' => $value['Port'],
                'fromemail' => $value['setFrom'],
                'fromname' =>  $value['fromname'],
                'replyto' => $value['AddReplyTo'],
                'charset' => $value['char_set'],
                'encode' => $value['Encoding'],
                'status' => ($value['status'] == 0) ? 'Active' : 'Inactive',
                'dropown' => $dropown,
                'action' => $action
            ];
        }
    }


    echo json_encode($result);
} else if ($method == 'deactivate_email') {
    $result = [];
    if ($_SESSION['memid'] == '') {
        $result["type"] = "0";
        $result["result"] = 'Kindly Refresh the Page and try again!';
        goto resultGI;
    }
    if ($_POST['emailID'] == '') {
        $result["type"] = "0";
        $result["result"] = 'Email ID Missing. Kindly Refresh and try again!';
        goto resultGI;
    }
    if ($_POST['reason'] == '') {
        $result["type"] = "0";
        $result["result"] = 'Kindly enter the reason!';
        goto resultGI;
    }

    $update_arr = ['status' => '1', 'reason' => $_POST['reason']];
    $email_config = update($con, "email_config", "`id` = '$_POST[emailID]' and `status` = '0' and `deletes`='0'", $update_arr, "", "", "", "");
    $errors = $email_config['errors'];
    if ($errors != "") {




        $result["type"] = "0";
        $result["result"] = 'Deactivation Failed!';
        // $result["result"] = $errors;
        goto resultGI;
    } else {

        $mail_arr = [
            'id' => NULL,
            'emailconfig_id' => $_POST['emailID'],
            'userid' =>  $_SESSION['memid'],
            'reason' => $_POST['reason'],
            'createdon' =>   $dubaidate_time,
            'type' =>    'Deactivated Successfully'
        ];
        $emailswitch_log = insert($con, "emailswitch_log", "", $mail_arr, "", "", "");

        $result["type"] = "1";
        $result["result"] = 'Deactivated Successfully';
        goto resultGI;
    }

    resultGI:
    echo json_encode($result);
} else if ($method == 'activate_email') {
    $result = [];
    if ($_SESSION['memid'] == '') {
        $result["type"] = "0";
        $result["result"] = 'Kindly Refresh the Page and try again!';
        goto resultGIR;
    }
    if ($_POST['emailID'] == '') {
        $result["type"] = "0";
        $result["result"] = 'Email ID Missing. Kindly Refresh and try again!';
        goto resultGIR;
    }
    if ($_POST['reason'] == '') {
        $result["type"] = "0";
        $result["result"] = 'Kindly enter the reason!';
        goto resultGIR;
    }

    $update_arr = ['status' => '0', 'reason' => $_POST['reason']];
    $email_config = update($con, "email_config", "`id` = '$_POST[emailID]' and `status` = '1' and `deletes`='0'", $update_arr, "", "", "", "");
    $errors = $email_config['errors'];
    if ($errors != "") {
        $result["type"] = "0";
        $result["result"] = 'Activation Failed!';
        // $result["result"] = $errors;
        goto resultGIR;
    } else {
        $mail_arr = [
            'id' => NULL,
            'emailconfig_id' => $_POST['emailID'],
            'userid' =>  $_SESSION['memid'],
            'reason' => $_POST['reason'],
            'createdon' =>   $dubaidate_time,
            'type' =>    'Activated Successfully'
        ];
        $emailswitch_log = insert($con, "emailswitch_log", "", $mail_arr, "", "", "");
        $result["type"] = "1";
        $result["result"] = 'Activated Successfully';
        goto resultGIR;
    }

    resultGIR:
    echo json_encode($result);
} else if ($method == 'sentTestMail') {
    $result = [];
    if ($_SESSION['memid'] == '') {
        $result["type"] = "0";
        $result["result"] = 'Kindly Refresh the Page and try again!';
        goto resultGJ;
    }

    if ($_POST['id'] == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly Refresh the Page and try again!";
        goto resultGJ;
    }

    if ($_POST['toemail'] == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly Enter the To Email!";
        goto resultGJ;
    }

    $email_config = select_query($con, "email_config", "", "`id` = '$_POST[id]' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
    if ($email_config['nr'] < 1) {
        $result["type"] = "0";
        $result["result"] = "From Email Not Found!";
        goto resultGJ;
    }

    $email = $_POST['toemail'];
    $subject = 'Test Email';
    $messages = '<h1 style="color: lime;">Test Success</h1>';
    $user_ip = getUserIP();
    $datetime = date("Y-m-d H:i:s");
    $messages1 = mysqli_real_escape_string($con, $messages);
    $insertlog = mysqli_query($con, "INSERT INTO `emaillog` (`details`,`subject`,`email`,`ip`,`datetime`,`status`) VALUES ('$messages1','$subject','$email','$user_ip','$dubaidate_time','1')");
    $insert_id = $con->insert_id;
    require "PHPMailer-master/src/Exception.php";
    require "PHPMailer-master/src/PHPMailer.php";
    require "PHPMailer-master/src/SMTP.php";
    $mail = new PHPMailer\PHPMailer\PHPMailer;
    $mail->isSMTP();

    if ($email_config['result'][0]['Host'] != '') {
        $mail->Host = $email_config['result'][0]['Host'];
    }

    if ($email_config['result'][0]['SMTPAuth'] != '') {
        $smtpAu = boolval($email_config['result'][0]['SMTPAuth']) ? true : false;
        $mail->SMTPAuth = $smtpAu;
    }

    if ($email_config['result'][0]['Username'] != '') {
        $mail->Username = $email_config['result'][0]['Username'];
    }

    if ($email_config['result'][0]['Password'] != '') {
        $mail->Password = $email_config['result'][0]['Password'];
    }

    if ($email_config['result'][0]['SMTPSecure'] != '') {
        $mail->SMTPSecure = $email_config['result'][0]['SMTPSecure'];
    }

    if ($email_config['result'][0]['Port'] != '') {
        $mail->Port = $email_config['result'][0]['Port'];
    }

    if ($email_config['result'][0]['setFrom'] != '') {
        $mail->setFrom($email_config['result'][0]['setFrom'], $email_config['result'][0]['fromname']);
    }

    if ($email_config['result'][0]['AddReplyTo'] != '') {
        $mail->AddReplyTo($email_config['result'][0]['AddReplyTo'], $email_config['result'][0]['fromname']);
    }

    if ($email_config['result'][0]['char_set'] != '') {
        $mail->CharSet = $email_config['result'][0]['char_set'];
    }

    if ($email_config['result'][0]['Encoding'] != '') {
        $mail->Encoding = $email_config['result'][0]['Encoding'];
    }


    $mail->addAddress($email);
    $mail->Subject = $subject;
    $mail->isHTML(true);
    $mail->Body = $messages;


    if (!$mail->send()) {
        $er = json_encode($mail->ErrorInfo);
        $update = mysqli_query($con, "UPDATE `emaillog` SET `sendstatus` = 'FAILED', `error_info` = '$er', `fromemail` = '$fromMail' WHERE `emaillog`.`id` = $insert_id;");
        $result["type"] = "0";
        $result["result"] = "FAILED";
        goto resultGJ;
    } else {
        $update = mysqli_query($con, "UPDATE `emaillog` SET `sendstatus` = 'SUCCESS', `fromemail` = '$fromMail' WHERE `emaillog`.`id` = $insert_id;");
        $result["type"] = "1";
        $result["result"] = "SUCCESS";
        goto resultGJ;
    }

    resultGJ:
    echo json_encode($result);
} else if ($method == 'updateEmail') {
    $result = [];
    if ($_POST['selected'] == '') {
        $result["type"] = "0";
        $result["result"] = 'Selected value missing. kindly Refresh and Try again!';
        goto resultGJD;
    }

    if ($_POST['id'] == '') {
        $result["type"] = "0";
        $result["result"] = 'Selected value missing. kindly Refresh and Try again!';
        goto resultGJD;
    }

    $email_config = select_query($con, "email_config", "", "`id` != '$_POST[id]' AND `emailkey` = 'all' AND `deletes` = '0' AND `status` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
    if ($email_config['nr'] < 1) {
        $result["type"] = "0";
        $result["result"] = 'Kindly set one email to All!';
        goto resultGJD;
    }


    $update_arr = ['emailkey' => $_POST['selected']];
    $email_config = update($con, "email_config", "`id` = '$_POST[id]' and `deletes`='0'", $update_arr, "", "", "", "");
    $errors = $email_config['errors'];
    if ($errors != "") {
        $result["type"] = "0";
        $result["result"] = 'Update Failed!';
        // $result["result"] = $errors;
        goto resultGJD;
    } else {

        $ins_arr = [
            'id' => NULL,
            'emailconfig_id' => $_POST['id'],
            'userid' =>  $_SESSION['memid'],
            'reason' => "Assigned To: " . $_POST['selected'],
            'createdon' =>   $dubaidate_time,
            'type' =>    'Updated Successfully'
        ];
        $emailswitch_log = insert($con, "emailswitch_log", "", $ins_arr, "", "", "");


        $result["type"] = "1";
        $result["result"] = 'Updated Successfully';
        goto resultGJD;
    }

    resultGJD:
    echo json_encode($result);
} else if ($method == 'list_email_config_log') {
    $result = [];
$formdate = BlockSQLInjection($_POST["formdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);

    // $formdate = $_POST['formdate'];
    // $todate = $_POST['todate'];
    if ($formdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "AND createdon BETWEEN '$fd 00:00:00' AND '$td 23:59:59' ";
    }

    $email_config = select_query($con, "emailswitch_log", "", "`id` != '0' $contype ORDER BY `id` DESC", "", "");
    if ($email_config['nr'] > 0) {
        foreach ($email_config['result'] as $key => $value) {
            $name = select_top_name($con, "user_register", "name", "`id`='$value[userid]' and `deletes`='0'", "name", "");
            $email = select_top_name($con, "email_config", "setFrom", "`id`='$value[emailconfig_id]' and `deletes`='0'", "setFrom", "");
            $result[] = [
                'sno' => $value['id'],
                'reason' => $value['reason'],
                'message' => $value['type'],
                'createdon' =>  date("d-M-Y g:i a", strtotime($value['createdon'])),
                'email' => $email,
                'name' => $name
            ];
        }
    }

    echo json_encode($result);
}
