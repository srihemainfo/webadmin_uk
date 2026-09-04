<?php
// include('include/shi-config.php');
// include('include/functions.php');

// $ticket = select_query($con, "ticket", "id, transaction_id, ticket_no, draw_id", "transaction_id IN (SELECT `transaction_id` FROM `payment_history` WHERE `draw_id` = '0' AND `pay_re_status` NOT IN ('COUPON', 'WALLET', 'CASH', 'BONUS', '') AND `gateway` != '') ORDER BY `id` ASC LIMIT 50", "", "");

// if ($ticket['nr'] > 0) {
//   foreach ($ticket['result'] as $key => $value) {
//     $transid = $value['transaction_id'];
//     $draw_id = $value['draw_id'];
//     echo 'Transaction ID: ' . $transid . '<br>';


//     $payment_history = select_query($con, "payment_history", "id", "`transaction_id` = '$transid' and `draw_id` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
//     if ($payment_history['nr'] > 0) {

//       // var_dump($draw_id);die;


//       $draw_arr = ["draw_id" => $draw_id];

//       $Inv_update = update($con, "payment_history", "`transaction_id` = '$transid'", $draw_arr, "", "", "", "");

//       $errors = $Inv_update['errors'];

//       if ($errors != "") {

//         $result["type"] = "0";

//         $result["result"] = $errors;
//       } else {

//         echo 'Draw ID: ' . $draw_id . ' Updated<br>';
//       }
//     }
//   }
// }


function get_tiny_url($url)
{

    $ch = curl_init();

    $timeout = 5;

    curl_setopt($ch, CURLOPT_URL, 'http://tinyurl.com/api-create.php?url=' . $url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    $data = curl_exec($ch);

    curl_close($ch);

    return $data;
}


var_dump(get_tiny_url('https://www.cequens.com/products/sms-api'));
die;


// Test
