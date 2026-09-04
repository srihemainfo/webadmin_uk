<?php


$DIR = dirname(__DIR__);
set_include_path($DIR);





$pidFilePath = $DIR . '/cron/lockFile/CCAvenueRecheck.pid';



// // Try to open the PID file for writing
$pidFile = fopen($pidFilePath, 'w');

if ($pidFile && flock($pidFile, LOCK_EX | LOCK_NB)) {
  // Lock acquired, write the current process ID to the file
  fwrite($pidFile, getmypid());


  require 'include/shi-config.php';
  require 'include/functions.php';
  require 'include/payment-config.php';
  require 'include/Crypto.php';

  function run_Api($method, $url)

  {

    $curl = curl_init();



    curl_setopt_array($curl, array(

      CURLOPT_URL => $url,

      CURLOPT_RETURNTRANSFER => true,

      CURLOPT_ENCODING => '',

      CURLOPT_MAXREDIRS => 10,

      CURLOPT_TIMEOUT => 0,

      CURLOPT_FOLLOWLOCATION => true,

      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

      CURLOPT_CUSTOMREQUEST => $method,

    ));



    $response = curl_exec($curl);



    curl_close($curl);

    return $response;
  }



  function generate_Unique_Id($length_of_string)

  {

    return substr(sha1(time()), 0, $length_of_string);
  }



  function getUserIPCron()

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


  $ipORuid = getUserIPCron() == '' ? generate_Unique_Id(20) : getUserIPCron();


  $time0 = strtotime($dubaidate_time);

  $fd = date('Y-m-d H:i:s', strtotime('-1 day', $time0));

  $td = date('Y-m-d H:i:s', strtotime('-10 minutes', $time0));


  $onebefore = date('Y-m-d H:i:s', strtotime('-7 day', $time0));



  echo '<strong>Start Time: </strong>' . $fd . '<br>' . '<strong>End Time: </strong>' . $td . '<br><br>';


  $payment_historydd = select_query($con, "payment_history", "", "`ticketReferenceID` IS null AND `gateway` = 'ccavenue' AND `status` = '0' AND  `cron_check_status` = '0' AND `createdon` BETWEEN '$fd' AND '$td'  ORDER BY `id` ASC", "", "");

  echo "<br><br><strong>CC AVENUE COUNT : </strong>" . $payment_historydd['nr'];

  echo "<br>";



  $payment_history1 = select_query($con, "payment_history", "", "`ticketReferenceID` IS null AND `gateway` = 'ccavenue' AND `status` = '0' AND  `cron_check_status` = '0' AND `createdon` BETWEEN '$fd' AND '$td'  ORDER BY `id` ASC LIMIT 7", "", "");

  if ($payment_history1['nr'] > 0) {

    foreach ($payment_history1['result'] as $key => $value) {

      $user_id = $value['user_id'];

      $transaction_id = $value['transaction_id'];

      echo '<strong>CCAvenue ID: </strong>' . $transaction_id . '<br>';



      // Log

      error_log_new($con, $ipORuid, 'ccAvenue_cron_start', '', '', '', $transaction_id, json_encode($_POST), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);

      $id = $value['id'];

      $ccRequest = [

        "order_no" => $transaction_id,

      ];



      $encrypted_data = encrypt(json_encode($ccRequest), $working_key); // Method for encrypting the data.



      $ccURL = 'https://login.ccavenue.ae/apis/servlet/DoWebTrans?enc_request=' . $encrypted_data . '&access_code=' . $access_code . '&command=orderStatusTracker&request_type=JSON&response_type=JSON&version=1.1';

      $responseData = run_Api('POST', $ccURL);

      parse_str($responseData, $jsonData);


      $status = (int) $jsonData['status'];



      if ($status === 0) {

        $encResponse = trim($jsonData['enc_response']);

        $rcvdString = decrypt($encResponse, $working_key);

        $ccResponse = json_decode($rcvdString, true);

        $order_status = $ccResponse['order_status'];


        if ($order_status != '') {

          if ($order_status == 'Successful' || $order_status == 'Shipped') {



            $oticket = select_query($con, "ndticket", "", "JSON_CONTAINS(transactionIds, '\"$transaction_id\"', '$') and `deletes`='0' order by `id` DESC", "", "");



            if ($oticket['nr'] > 0) {

              $draw_arr = array("cron_check_status" => '1', 'crontime' => $dubaidate_time);

              $Inv_update = update($con, "payment_history", "`transaction_id` = '$transaction_id'", $draw_arr, "", "", "", "");

              $errors = $Inv_update['errors'];

              if ($errors != "") {

                $result["type"] = "0";

                $result["result"] = $errors;
              } else {

                // Log

                error_log_new($con, $ipORuid, 'Ticket_Already_Generated.', '', '', '', $order_status . 'Ticket ID: ' . $oticket['result'][0]['id'], json_encode($_POST), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);
              }
            }
            // else {

            // Log

            error_log_new($con, $ipORuid, 'cc_Ticket_generation_started', '', '', '', $order_status . 'Ticket ID: ' . $oticket['result'][0]['id'], json_encode($_POST), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);

            $draw_arr = array("paymentStatus" => $order_status);

            $Inv_update = update($con, "payment_history", "`transaction_id` = '$transaction_id' AND `status` = '0'", $draw_arr, "", "", "", "");




            if ($Inv_update && $value['category'] == 'PRODUCT') {

              $getDeleteUser = select_query($con, "user_register", "", "`id` = '$user_id' AND `roll_id` = '0' AND `status` = '0' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
              if ($getDeleteUser['nr'] > 0) {
                $mobileNo = $getDeleteUser['result'][0]['mobile'];
                $password = $getDeleteUser['result'][0]['password'];

                $getCountryAPI = invokeApiRequest('POST', API_DOMAIN . 'loginWithPassword', [
                  'Accept: application/json',
                  'Content-Type: application/json'
                ], json_encode([
                  'mobile' => $mobileNo,
                  'password' =>  $password
                ]));
                $getCountryData = json_decode($getCountryAPI, true);

                if ($getCountryData['status'] === 'success') {
                  $token = $getCountryData['token'];

                  if ($token != '' && $token != null && isset($token)) {


                    $generateTicketAPI = invokeApiRequest('POST', API_DOMAIN . 'onlineTicketGeneration', [
                      'Accept: application/json',
                      'Content-Type: application/json',
                      'Authorization: Bearer ' . $token
                    ], json_encode([
                      'transaction_id' => $transaction_id,
                      // 'password' =>  $password
                    ]));
                    $generateTicket = json_decode($generateTicketAPI, true);


                    if ($generateTicket['status'] === 'success') {

                      $draw_arr = array("cron_check_status" => '2');

                      $Inv_update = update($con, "payment_history", "`transaction_id` = '$transaction_id' AND `status` = '1' ", $draw_arr, "", "", "", "");


                      if ($Inv_update) {
                        echo 'Ticket Generated successfully!<br>';
                      }
                    }
                  }
                }
              }
            }
          } else {

            $draw_arr = array("cron_check_status" => '1', "paymentStatus" => $order_status,  'crontime' => $dubaidate_time);

            $Inv_update = update($con, "payment_history", "`transaction_id` = '$transaction_id' AND `status` = '0'", $draw_arr, "", "", "", "");

            $errors = $Inv_update['errors'];

            if ($errors != "") {

              $result["type"] = "0";

              $result["result"] = $errors;
            } else {



              // Log

              error_log_new($con, $ipORuid, 'CCAvenue_check', '', '', '', $state . ' ID: ' . $id, json_encode($_POST), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);
            }
          }
        } else {

          $draw_arr = array("cron_check_status" => '1', 'crontime' => $dubaidate_time);

          $Inv_update = update($con, "payment_history", "`transaction_id` = '$transaction_id'", $draw_arr, "", "", "", "");

          $errors = $Inv_update['errors'];

          if ($errors != "") {

            $result["type"] = "0";

            $result["result"] = $errors;
          } else {

            // Log

            error_log_new($con, $ipORuid, 'CCAvenue_check', '', '', '', $state . ' ID: ' . $id, json_encode($_POST), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);
          }
        }
      }
    }
  } else {

    // Log

    error_log_new($con, $ipORuid, 'ccAvenue_cron_not_found', '', '', '', 'Data Not Found', json_encode($_POST), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);
  }



  // Log

  error_log_new($con, $ipORuid, 'Cron_1_End', '', '', '', 'Start Time ' . $fd . ' End Time ' . $td . ' Seven Day Before ' . $onebefore, 'Cron is working Fine', __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);


  mysqli_close($con);
  mysqli_close($bcon);
  mysqli_close($rcon);


  //   //   // Release the lock and close the PID file
  flock($pidFile, LOCK_UN);
  fclose($pidFile);
} else {
  // Another instance is running or unable to obtain the lock
  echo "Cron job is already running. Exiting.";
}
