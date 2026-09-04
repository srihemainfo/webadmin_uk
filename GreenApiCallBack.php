<?php
include 'include/shi-config.php';
include 'include/functions.php';

// Takes raw data from the request
$json = file_get_contents('php://input');
// Converts it into a PHP object
$data = json_decode($json, true);

// Log
error_log_new($con, '', 'GreenApi_call_back', '', '', '', 'GreenApi Call Back', json_encode($data), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);

$idMessage = $data['idMessage'];
$typeWebhook = $data['typeWebhook'];

if (isset($typeWebhook) && $typeWebhook  != '' && $typeWebhook == 'outgoingMessageStatus' && $idMessage != '') {
    $smsCheck = select_query($con, "smslog", "", "`gateway` = 'greenapi' AND `reference_id` LIKE '$idMessage' ORDER BY id DESC LIMIT 1", "", "");
    if ($smsCheck['nr'] > 0) {
        $id = $smsCheck['result'][0]['id'];
        $status = $data['status'];
        $timestamp = date("Y-m-d ") . ' ' . date("H:i:s", strtotime($data['timestamp']));

        $updateArr = [
            'SentDate' => date("Y-m-d H:i:s"),
            'smsdetails' => json_encode($data),
            'smsstatus' => $status
        ];

        $smsUpdate = update($con, "smslog", "`id` = '$id' ORDER BY id DESC LIMIT 1", $updateArr, "", "", "", "");
        $errors = $smsUpdate['errors'];
        if ($errors != "") {
            $result["type"] = "0";
            $result["result"] = $errors;
            // Log
            error_log_new($con, '', 'GreenApi_call_back_update_failed', $id, '', '', 'GreenApi Call Back', json_encode($result), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);
            goto Resutlt;
        } else {
            $result["type"] = "1";
            $result["result"] = 'SUCCESS';

            // Log
            error_log_new($con, '', 'GreenApi_call_back_update_Success', $id, '', '', 'GreenApi Call Back', json_encode($result), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);
            goto Resutlt;
        }

        Resutlt:
        echo json_encode($result);
    } else {
        // Log
error_log_new($con, '', 'GreenApi_call_back_sms_Track_not_found', '', '', '', 'GreenApi Call Back', json_encode($data), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);

    }
}