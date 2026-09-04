<?php


include '../../include/shi-config.php';
include '../../include/functions.php';

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$memid = $_SESSION['memid'];

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";

$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";

$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";

$roleID = $_REQUEST['role'] ?? '';

// var_dump($method);die;

$headers = apache_request_headers();



if ($method == "delete_NDticket") {

    $result = [];

    $transid = $_POST['transid'];

    $message = $_POST['message'];

    // var_dump($transid,$message);die;


    $inv_arr = array("deletes" => '1', "deleteReason" => $message);
    $ND_update = update($con, "ndticket", "`id` = '$transid'", $inv_arr, "", "", "", "");
    $result = array();

    if ($ND_update) {
        $result["result"] = " Ticket has been Deleted Successfully";
    } else {
        $result["result"] = "Failed to delete ticket";
    }







    echo json_encode($result);
} 

else if($method == "sendemailtopurchase"){
    
    // var_dump('hellow welcom');die;
    $result = [];
    // $result = array();
   

    if ($_POST['toemail'] == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly Enter the To Email!";
        goto resultGJ1;
    }
    
    if ($_POST['transid'] == '') {
        $result["type"] = "0";
        $result["result"] = "Miss the Ticket ID!";
        goto resultGJ1;
    }
    
  
    
   
    
     $email_config1 = select_query($con, "email_config", "", "`deletes` ='0' ORDER BY `id` DESC", "", "");
     
    //  var_dump($email_config1['nr'] > 0);die;
      if ($email_config1['nr'] > 0) {
        foreach ($email_config1['result'] as $key => $value) {
            
            $Id12 = $value['id'];
            
            
        }
          
      }
    

    // $email_config = select_query($con, "email_config", "", "`id` = '$_POST[id]' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
    $email_config = select_query($con, "email_config", "", "`id` = '$Id12' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
    if ($email_config['nr'] < 1) {
        $result["type"] = "0";
        $result["result"] = "From Email Not Found!";
        goto resultGJ1;
    }

    $email = $_POST['toemail'];
    $ticket_id = $_POST['transid'];
    //  var_dump('test1234');die;
    
    $ticket = select_query($con, "ndticket", "", "`id` = '$ticket_id' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
    
    
    if ($ticket['nr'] > 0) {
        foreach ($ticket['result'] as $key => $value) {
            
           $userId = $value['userId']; // Store the user ID first

            $name = select_top_name($con, "user_register", "name", "`id`='$userId' and `deletes`='0'", "name", "");
            $lname = select_top_name($con, "user_register", "lname", "`id`='$userId' and `deletes`='0'", "lname", "");
            
            $customer = $name .' '.$lname;
            
            $Id = $value['id'];
            $ticketNo = $value['ticketNo'];
            // $raffleIds = $value['raffleIds'];
            $netTotal = $value['netTotal'];
            $startDate = new DateTime($value['startDate']); // Convert start date to DateTime object
            $endDate = new DateTime($value['endDate']); // Convert end date to DateTime object

            $interval = $startDate->diff($endDate);
            $totalDays = $interval->days;
            
            $createdon = $value['createdon'];
            
            $dateTime = new DateTime($createdon);
            $time = $dateTime->format('H:i:s');
            
         
            // $date = $dateTime->format('Y-m-d');
            $date = $dateTime->format('j F, Y');
            $referenceID = $value['referenceID'];
            
            $raffleIdsArray = json_decode($value['raffleIds']); // Decode the JSON string into an array

            $raffleIds = implode("\n", $raffleIdsArray);
            
            
        }
          
      }
      
   
    // var_dump($raffleIds);die;

    // $email = '$email;
    $subject = 'Purchase Confirmation ';
    // $messages = '<h1 style="color: lime;">Test Success for National Draw</h1>';
    
    $messages = '<!DOCTYPE html
   PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title> Order Confirmation</title>
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
      </style>
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
                              <img src="https://nationalasset.blr1.digitaloceanspaces.com/nationaldraw/1/ndLogo.png" style="border: 0px;"
                                 width="50%">
                           </center>
                        </td>
                     </tr>
                     <tr>
                        <td valign="top" style="padding: 0;">
                           <center>
                              <br>
                              <img src="https://nationalasset.blr1.digitaloceanspaces.com/nationaldraw/1/ndThanks.png" style="border-radius: 19px;" width="70%">
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
                  <p style="font-weight: 500!important;">Hi,'.$customer .'</p>
                  <p style="font-size: 16px; font-weight: 400!important;">Thank you for your purchase<br>
                    and donation.
                  </p>
                  <p style="font-size:21px;font-weight: 600!important;font-family: Verdana, sans-serif !important;margin:14px 0;border: 2px dotted green;width: fit-content;margin: 15px auto;padding: 6px;border-radius: 8px;">
                    Ticket ID #'.$ticketNo .'
                  </p>
                </td>
              </tr>
              <tr>
                <td>
                    <table style="margin: auto;border-collapse: collapse;border: 1px solid #088b42;width:90%;max-width:480px;" border="1" cellspacing="2" cellpadding="0">
                        <tbody>
                          <tr>
                            
                            <th style="padding: 12px 0px;color: #ffffff;font-family: Verdana, sans-serif !important;font-size:17px;width: 13%;background: #171f4f;" align="center" bgcolor="#d0dbe7"><strong style="font-weight: 500;">Valid Up To</strong></th>
                            <th style="padding: 12px 0px;color: #ffffff;font-family: Verdana, sans-serif !important;font-size:17px;width:12%;background: #01104e;" align="center" bgcolor="#d0dbe7"><strong style="font-weight: 500;">Raffle ID</strong></th>
                            
                          </tr>
                          <tr>
                            <td style=" padding: 12px 0px;color: #01104e;font-family: Verdana, sans-serif !important;font-size:17px;" align="center" bgcolor="#ffffff"><strong style="font-weight: 500;">  '.  $totalDays .' Days<br>
                                <small>'.$date. '</small></strong></td>
                            <td style=" padding: 12px 0px;color: #01104e;font-family: Verdana, sans-serif !important;font-size:17px;" align="center" bgcolor="#ffffff"><strong style="font-weight: 500;">'.  $raffleIds .' </strong></td>
                          </tr>
                        </tbody>
                      </table>
                </td>
              </tr>
              <tr>
                <td style="color: #111111; padding: 20px 14px; " align="center" valign="top" bgcolor="#ffffff">
                  <h3 style="color: #01104e;  font-size: 20px; margin: 0px;font-family: Verdana, sans-serif !important;">
                    Total Amount:
                    <span class="gmail-otp-bg" style="color: #088b42;font-family: Verdana, sans-serif !important;">AED
                      '.$netTotal.'</span>
                    <br>
                  </h3>
                </td>
              </tr>
        <tr>
                <td>
                  <table style="margin: auto; color:;  font-size: medium; background-color: ;  border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td style="padding: 0px 0px 0px 2px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px;width:40%;" align="center" valign="top" bgcolor="#ffffff">
                          <h3 style="color: #ffffff; font-size: 17px; margin: 0px;  padding: 8px 5px 10px 5px; background: #ffffff;color: #01104e;;  line-height: 1; border-radius: 5px;border: 2px solid #088b42;">
                            <a href="'.$baseurl.'ticket-view/'.$referenceID.'" style="text-transform: uppercase; color: #01104e; text-decoration-line: none; font-family: Poppins, sans-serif !important; cursor: pointer;" contenteditable="false">View Ticket</a>
                          </h3>
                        </td>
                       
                      </tr>
                      <br>
                    </tbody>
                  </table>
                  <br>
                </td>
              </tr>
            <tr>
               <td>
                  <ul
                     style="color: #01104e;font-family: Verdana, sans-serif !important;font-size: 15px;font-weight: 500; list-style: none; text-align: center; padding: 0; margin:0 ; line-height: 1.5;">
                     <li>• Thrill Draw win up to 24 Grams of Gold</li>
                     <li>• Booster Draw win up to 100 Grams of Gold </li>
                     <li>• Bumper Draw win up to 1000 Grams of  Gold</li>
                  </ul>
               </td>
            </tr>
         
            <tr>
                <td class="column-one">
                   <img style="width: !important;margin-top: 10px;" src="https://nationalasset.blr1.digitaloceanspaces.com/nationaldraw/1/ndFooter.png" width="84%">
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

//  <td style="padding: 0px 0px 0px 30px; border-radius: 4px 4px 0px 0px; font-size: 24px; line-height: 24px;width:44%;" align="center" valign="top" bgcolor="#ffffff">
//                           <h3 style="color: #ffffff; font-size: 17px; margin: 0px;  padding: 8px 5px 10px 5px; background: #ffffff;color: #01104e;;  line-height: 1; border-radius: 5px;border: 2px solid #088b42;">
//                             <a href="https://www.test.nationaldrawuae.com/invoice/'.$referenceID.'" style="text-transform: uppercase; color: #01104e; text-decoration-line: none; font-family: Poppins, sans-serif !important; cursor: pointer;" contenteditable="false">View Invoice</a>
//                           </h3>
//                         </td>
    $user_ip = getUserIP();
    $datetime = date("Y-m-d H:i:s");
    $messages1 = mysqli_real_escape_string($con, $messages);
    // var_dump($messages1,$subject,$email,$user_ip,$dubaidate_time);die;
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
        goto resultGJ1;
    } else {
        $update = mysqli_query($con, "UPDATE `emaillog` SET `sendstatus` = 'SUCCESS', `fromemail` = '$fromMail' WHERE `emaillog`.`id` = $insert_id;");
        $result["type"] = "1";
        $result["result"] = "SUCCESS";
        goto resultGJ1;
    }
    
    resultGJ1:
    echo json_encode($result);


    
}else if($method == "sendsmstopurchase"){
    
     $result = [];
    
    $mobile=$_POST['mobile']; 
    
    if ($_POST['mobile'] == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly Enter the Mobile No!";
        goto resultGJ2;
    }
    
        if (substr(strval($mobile), 0, 3) == "971") {
            
            
            $query = "SELECT * FROM sms_switch WHERE site = 'customersite' AND deletes = '0'";
            $result = mysqli_query($con, $query);
            
            $messages='testing National Draw';
            
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);

                $query = "INSERT INTO smslog (gateway, mobile, details, ip, datetime, status, site, REQ_Time, RES_Time, smssendstatus, response, token_response, subject, reference_id, smsdetails, smsstatus) VALUES ('', '$mobile', '$messages', '', '".date("Y-m-d H:i:s")."', '', 'CUSTOMER', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."', '1', '', '', '', '', '', '')";
                mysqli_query($con, $query);
                $smslogID = mysqli_insert_id($con);
                // var_dump('hellow');die;

                if ($row['gateway'] == 'brandmaster') {
                   
                    $auth_token = 'Basic bmF0aW9uYWxkcmF3OkFudU9YZ05C';
                    $headers = [
                        'Content-Type: application/json',
                        "Authorization: $auth_token"
                    ];
                     
                    $body = [
                        'source' => "NATL DRAW",
                        'destination' => [
                            strval($mobile)
                        ],
                        'text' => $messages
                    ];
                    $url = 'https://portal.smshub.live/API/SendBulkSMS';
                    $options = [
                        'http' => [
                            'header' => implode("\r\n", $headers),
                            'method' => 'POST',
                            'content' => json_encode($body)
                        ]
                    ];
                    $context = stream_context_create($options);
                    
                    // var_dump($context);die;
                    $response = file_get_contents($url, false, $context);
                    // var_dump($response);die;
                    $result = json_decode($response);

                    $query = "UPDATE smslog SET token_response = '" . json_encode($body) . "', status = '" . json_encode($result[0]) . "', reference_id = '" . ($result[0]->Id ?? '') . "', RES_Time = '" . date("Y-m-d H:i:s") . "', gateway = 'brandmaster', smsstatus = '" . ($result[0]->Description ?? '') . "' WHERE id = $smslogID";
                    mysqli_query($con, $query);

                    if ($result[0]->Description == 'Success') {
                        
                         $result["type"] = "1";
                         $result["result"] = "SUCCESS";
                         goto resultGJ2;
                        // return true;
                    } else {
                        $result["type"] = "0";
                        $result["result"] = "FAILED ";
                        goto resultGJ2;
                    }
                } else {
                   
                    $result["type"] = "0";
                    $result["result"] = "FAILED : the SMS gateway Error ";
                    goto resultGJ2;
                }
            } else {
                $result["type"] = "0";
                $result["result"] = "FAILED : the SMS gateway Error ";
                goto resultGJ2;
            }
        } else {
          $result["type"] = "0";
          $result["result"] = "Kindly Enter the 971  Mobile No Only ";
          goto resultGJ2;
        }
    
   

    
    
    // var_dump($result);die;
    
    resultGJ2:
    echo json_encode($result);
   

}




