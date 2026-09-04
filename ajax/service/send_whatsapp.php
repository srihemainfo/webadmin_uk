<?php
include '../../include/shi-config.php';
include '../../include/functions.php';

define('ADMIN_ACCESS_TOKEN', 'asdfghjklpoiuytrewqzxcvbnm!@$%^&*()');

header('Content-Type: application/json');

$result = [];

if(isset($_POST['whatsapp_number']) && isset($_POST['whatsapp_message']))
{
    $number  = trim($_POST['whatsapp_number']);
    $message = trim($_POST['whatsapp_message']);

    if($number == "" || $message == "")
    {
        $result['status'] = false;
        $result['message'] = "Number or message empty";
        echo json_encode($result);
        exit;
    }

    // API URL
    $url = "https://www.goride.run/api/whatsapp/message-send";

    // Payload
    $payload = [
        "mobile"  => $number,
        "message" => $message
    ];

    // CURL
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . ADMIN_ACCESS_TOKEN,
            "Content-Type: application/json"
        ]
    ]);

    $response = curl_exec($ch);

    if(curl_errno($ch))
    {
        $result['status'] = false;
        $result['message'] = curl_error($ch);
        echo json_encode($result);
        curl_close($ch);
        exit;
    }

    curl_close($ch);

    $api_response = json_decode($response, true);

    if(isset($api_response['status']) && $api_response['status'] == true)
    {
        $result['status'] = true;
        $result['message'] = "Message Sent Successfully";
    }
    else
    {
        $result['status'] = false;
        $result['message'] = $api_response['message'] ?? "API Failed";
    }

}
else
{
    $result['status'] = false;
    $result['message'] = "Invalid Request";
}

echo json_encode($result);
exit;
?>