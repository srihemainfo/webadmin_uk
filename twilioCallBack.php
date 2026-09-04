<?php
include 'include/shi-config.php';
include 'include/functions.php';

// Takes raw data from the request
// $json = file_get_contents('php://input');
// Converts it into a PHP object
// $_POST = json_decode($json, true);

// Log
// error_log_new($con, getUserIP(), 'twilio_call_back', '', '', '', 'twilio Call Back', json_encode($_POST), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);


// Log
// error_log_new($con, getUserIP(), 'twilio_call_back_Get', '', '', '', 'twilio Call Back', json_encode($_GET), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);


// Log
error_log_new($con, getUserIP(), 'twilio_call_back', '', '', '', 'twilio Call Back', json_encode($_POST), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);


$idMessage = $_POST['SmsSid'];

// var_dump($idMessage);die;

if ($idMessage != '') {
    $smsCheck = select_query($con, "smslog", "", "`gateway` = 'twilio' AND `reference_id` LIKE '$idMessage' ORDER BY id DESC LIMIT 1", "", "");
    if ($smsCheck['nr'] > 0) {
        $id = $smsCheck['result'][0]['id'];
        $status = $_POST['SmsStatus'];
        // $timestamp = date("Y-m-d H:i:s", strtotime($_POST['RawDlrDoneDate']));

        $updateArr = [
            'SentDate' => date("Y-m-d H:i:s"),
            'smsdetails' => json_encode($_POST),
            'smsstatus' => $status
        ];

        $smsUpdate = update($con, "smslog", "`id` = '$id' ORDER BY id DESC LIMIT 1", $updateArr, "", "", "", "");
        $errors = $smsUpdate['errors'];
        if ($errors != "") {
            $result["type"] = "0";
            $result["result"] = $errors;
            // Log
            error_log_new($con, '', 'Twilio_call_back_update_failed', $id, '', '', 'Twilio Call Back', json_encode($result), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);
            goto Resutlt;
        } else {
            $result["type"] = "1";
            $result["result"] = 'SUCCESS';

            // Log
            error_log_new($con, '', 'Twilio_call_back_update_Success', $id, '', '', 'Twilio Call Back', json_encode($result), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);
            goto Resutlt;
        }
    } else {

        $result["type"] = "0";
        $result["result"] = 'Message Not Found';
        // Log
        error_log_new($con, '', 'Twilio_call_back_sms_Track_not_found', '', '', '', 'Twilio Call Back', json_encode($_POST), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);
        goto Resutlt;
    }
} else {
    $result["type"] = "0";
    $result["result"] = 'id Missing';
    // Log
    error_log_new($con, '', 'Twilio_call_back_update_failed', $id, '', '', 'Twilio Call Back', json_encode($result), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);
    goto Resutlt;
}

Resutlt:
echo json_encode($result);
// Log
// error_log_new($con, getUserIP(), 'twilio_call_back_Request', '', '', '', 'twilio Call Back', json_encode($_REQUEST), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);
