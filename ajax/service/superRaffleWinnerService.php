<?php

/**
 *   Date            Developer           Modification
 * 
 */


include '../../include/shi-config.php';
include '../../include/functions.php';

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";
$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";

$result = array();

$ticketTable = [
    'MT' => 'mticket',
    'OT' => 'ticket',
    'AT' => 'aticket',
    'FT' => 'fticket',
    'WT' => 'wticket',
    'CT' => 'cticket',
    'CP' => 'cpticket',
    'BP' => 'bpticket',
    'KT' => 'kticket'
];

if ($method == 'superRaffleService') {
    $result = [];

    $query = mysqli_query($con, "SELECT s.*, u.name AS uname, u.lname, u.email, u.mobile, p.rate 
                                  FROM superraffledraw AS s 
                                  LEFT JOIN user_register AS u ON u.id = s.win_userid 
                                  LEFT JOIN product AS p ON p.id = s.product_id 
                                  WHERE s.deletes = '0' 
                                  ORDER BY s.id ASC");

    if ($query) {
        if (mysqli_num_rows($query) > 0) {
            $result["type"] = "0";
            $result["result"] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        } else {
            $result["type"] = "0";
            $result["result"] = [];
        }
    } else {
        $result["type"] = "1";
        $result["message"] = mysqli_error($con);
    }

    echo json_encode($result);
} else if ($method == 'updateGrandRaffleStrategy') {
    $result = [];
    $capID = $_POST['raffleID'];
    $stratgy = $_POST['stratgy'];

    if ($capID == '' || $stratgy == '') {
        $result["type"] = "1";
        $result["result"] = 'Kindly Refresh the screen and Try again!';
        goto resultNIF34;
    }

    $wallet_logarr['execution_strategy']  = $stratgy;
    $ticket_lines_update = update($con, "superraffledraw", "`id` = '$capID' AND `status` = 'Active' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", $wallet_logarr, "", "", "", "");
    $errors = $ticket_lines_update['errors'];
    if ($errors != "") {
        $result["type"] = "1";
        $result["result"] = "Execution strategy update process has failed.";
        goto resultNIF34;
    } else {

        $draw = select_query($con, "superraffledraw", "", "`status` = 'Active' AND `id` = '$capID' AND `deletes` = '0' ORDER BY `result_datetime` ASC LIMIT 1", "", "");
        if ($draw['nr'] > 0) {
            $startTime = $draw['result'][0]['ticket_start_datetime'];
            $endTime = $draw['result'][0]['ticket_end_datetime'];
            $executionStrategy = $draw['result'][0]['execution_strategy'];


            if ($executionStrategy === 'MANUAL') {


                $wallet_logarr  = ['product_id' => null, "win_ticket_line_id" => 0, "win_raffle_no" =>  '', 'win_userid' => 0, 'wonprize' => 0];
                $ticket_lines_update = update($con, "superraffledraw", "`id` = '$capID' AND `status` = 'Active' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", $wallet_logarr, "", "", "", "");
                $errors = $ticket_lines_update['errors'];
                if ($errors != "") {
                    $result["type"] = "1";
                    $result["result"] = "Execution strategy update process has failed.";
                    goto resultNIF34;
                } else {
                    $result["type"] = "0";
                    $result["result"] = "Execution strategy has been updated, and the winner has been reset successfully.";
                    goto resultNIF34;
                }
            } else {

                $selectCat = "SELECT tl.product_id AS 'productid', SUM(p.rate) FROM `ticket_lines` AS tl LEFT JOIN product AS p ON p.id = tl.product_id WHERE tl.createdon BETWEEN '$startTime' AND '$endTime' AND tl.deletes = '0' GROUP BY tl.product_id ORDER BY `SUM(p.rate)` DESC LIMIT 1;";
                $catRun = mysqli_query($con, $selectCat);
                $getProData = mysqli_fetch_all($catRun, MYSQLI_ASSOC);

                // var_dump();
                // die;

                if ($catRun && mysqli_num_rows($catRun) > 0 && isset($getProData[0]['productid']) && intval($getProData[0]['productid']) > 0) {



                    $winningProduct = $getProData[0]['productid']; // Product ID




                    $getWinnersList = mysqli_query($con, "SELECT
                g.user_id,
                SUM(g.rate) AS `total`
                FROM (
                SELECT
                    t.user_id,
                    p.rate
                FROM
                    `ticket_lines` AS t
                LEFT JOIN `product` AS p ON p.id = t.product_id
                LEFT JOIN user_register AS u ON u.id = t.user_id
                WHERE
                    t.deletes = '0' AND u.deletes = '0' AND u.status = '0' AND u.roll_id = '0'
                    AND t.createdon BETWEEN '$startTime' AND '$endTime'
                ) AS g
                GROUP BY
                g.user_id
                ORDER BY `total` DESC LIMIT 10;");

                    if ($getWinnersList) {
                        while ($row = mysqli_fetch_assoc($getWinnersList)) {
                            $userid = $row['user_id'];

                            $raffleCheck = select_query($con, "superraffledraw", "", "id = '$capID' AND status = 'Active' ORDER BY `id` DESC LIMIT 1;", "", "");

                            if ($raffleCheck['nr'] > 0) {
                                if ($userid != '' && $userid != null) {
                                    $getRaffleID = mysqli_query($con, "SELECT * FROM `ticket_lines` WHERE `user_id` = '$userid' AND `deletes` = '0' AND `product_id` = '$winningProduct' AND `createdon` BETWEEN '$startTime' AND '$endTime' ORDER BY RAND() LIMIT 1;");
                                    if ($getRaffleID) {
                                        $getWinDetails = mysqli_fetch_assoc($getRaffleID);

                                        $TicketTableName = $ticketTable[$getWinDetails['type']];
                                        $ticket_id = $getWinDetails['ticket_id'];

                                        $checkTicket = select_query($con, $TicketTableName, "", "`id` = $ticket_id AND deletes = '0' ORDER BY `id` DESC LIMIT 1", "", "");
                                        if ($checkTicket['nr'] < 1) {
                                            $result["type"] = "1";
                                            $result["result"] = "Could you please try again? It seems that the selected ticket was not found.";
                                            $result['TicketID'] = $ticket_id;
                                            goto resultNIF34;
                                        }


                                        $gift_amt = select_query($con, "product", "", "`id` = '$winningProduct' ORDER BY `id` DESC LIMIT 1", "", "");
                                        if ($gift_amt['nr'] > 0) {
                                            $raffle_prize = (int)$gift_amt['result'][0]['super_raffle_prize'];
                                            if ($raffle_prize > 0) {
                                                $winRaffle = $getWinDetails['raffle_id'];


                                                if ($winRaffle != '') {
                                                    $inv_arr = ['product_id' => $gift_amt['result'][0]['id'], "win_ticket_line_id" => $getWinDetails['id'], "win_raffle_no" =>  $winRaffle, 'win_userid' => $userid, 'wonprize' => $raffle_prize];
                                                    $Inv_update = update($con, "superraffledraw", "`id` = '$capID' AND status = 'Active' ORDER BY `id` DESC LIMIT 1", $inv_arr, "", "", "", "");
                                                    $errors = $Inv_update['errors'];
                                                    if ($errors != "") {
                                                        // echo 'Update Process Failed';
                                                        $result["type"] = "1";
                                                        $result["result"] = "The winner selection process has failed.";
                                                        goto resultNIF34;
                                                    } else {
                                                        //   $winnerID = $userid;
                                                        //   goto resultGO;
                                                        $result["type"] = "0";
                                                        $result["result"] = "Execution strategy and new winner have also been updated.";
                                                        goto resultNIF34;
                                                    }
                                                }
                                            } else {
                                                $result["type"] = "1";
                                                $result["result"] = 'The prize amount needs to be more than 1 AED!';
                                                goto resultNIF34;
                                            }
                                        } else {
                                            $result["type"] = "1";
                                            $result["result"] = "The Product Not Found!";
                                            goto resultNIF34;
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $result["type"] = "1";
                    $result["result"] = "The category selection process has failed!";
                    goto resultNIF34;
                }
            }


            // else {
            //     $Ticket_lines = select_query($con, "superraffledraw", "", "`id` = '$capID' ORDER BY `id` DESC LIMIT 1", "", "");
            //     if ($Ticket_lines['nr'] > 0) {
            //         foreach ($Ticket_lines['result'] as $key => $value) {
            //             $start = $value['ticket_start_datetime'];
            //             $end = $value['ticket_end_datetime'];
            //             if ($start && $end) {
            //                 $formattedStart = date('Y-m-d H:i:s', strtotime($start));
            //                 $formattedEnd = date('Y-m-d H:i:s', strtotime($end));
            //                 // Create the date filter
            //                 // $datefilter1 = "`createdon` >= '$formattedStart' AND `createdon` <= '$formattedStart'";
            //             } else {
            //             }
            //             $datefilter1 = "";
            //         }
            //     }
            // }

            // $consolidated_Report = select_query($con, "ticket_lines", "", $datefilter1 . " ORDER BY `id` DESC LIMIT 1", "", "");

            // $winningProduct = $consolidated_Report['result'][0]['product_id']; // Product ID
            // $getWinnersList = mysqli_query($con, "SELECT g.user_id, g.product_id, SUM(g.rate) AS `total` FROM ( SELECT t.user_id, p.rate, t.product_id FROM `ticket_lines` AS t LEFT JOIN `product` AS p ON p.id = t.product_id LEFT JOIN user_register AS u ON u.id = t.user_id WHERE t.deletes = '0' AND u.deletes = '0' AND u.status = '0' AND u.roll_id = '0' AND t.createdon BETWEEN '$formattedStart' AND '$formattedEnd' ) AS g GROUP BY g.user_id, g.product_id ORDER BY `total` DESC LIMIT 1");
            // if ($getWinnersList) {
            //     while ($row = mysqli_fetch_assoc($getWinnersList)) {
            //         $userid = $row['user_id'];
            //         $productid = $row['product_id'];
            //         $raffleCheck = select_query($con, "superraffledraw", "", "id = '$capID' AND status = 'Active' ORDER BY `id` DESC LIMIT 1;", "", "");

            //         $userregister = select_query($con, "user_register", "", "id = '$userid' AND deletes = '0' ORDER BY `id` DESC LIMIT 1;", "", "");
            //         if ($raffleCheck['nr'] > 0) {
            //             if ($userid != '' && $userid != null) {
            //                 $getRaffleID = mysqli_query($con, "SELECT * FROM `ticket_lines` WHERE `user_id` = '$userid' AND `deletes` = '0' AND `product_id` = '$productid' AND `createdon` BETWEEN '$formattedStart' AND '$formattedEnd' ORDER BY RAND() LIMIT 1;");
            //                 if ($getRaffleID) {
            //                     $getWinDetails = mysqli_fetch_assoc($getRaffleID);

            //                     $TicketTableName = $ticketTable[$getWinDetails['type']];
            //                     $ticket_id = $getWinDetails['ticket_id'];

            //                     $checkTicket = select_query($con, $TicketTableName, "", "`id` = $ticket_id AND deletes = '0' ORDER BY `id` DESC LIMIT 1", "", "");
            //                     if ($checkTicket['nr'] < 1) {
            //                         $result["type"] = "1";
            //                         $result["result"] = "Could you please try again? It seems that the selected ticket was not found.";
            //                         $result['TicketID'] = $ticket_id;
            //                         goto resultNIF34;
            //                     }


            //                     $gift_amt = select_query($con, "product", "", "`id` = '$productid' ORDER BY `id` DESC LIMIT 1", "", "");
            //                     if ($gift_amt['nr'] > 0) {
            //                         $raffle_prize = (int)$gift_amt['result'][0]['super_raffle_prize'];
            //                         if ($raffle_prize > 0) {
            //                             $winRaffle = $getWinDetails['raffle_id'];


            //                             if ($winRaffle != '') {
            //                                 $inv_arr = ['product_id' => $gift_amt['result'][0]['id'], "win_ticket_line_id" => $getWinDetails['id'], "win_raffle_no" =>  $winRaffle, 'win_userid' => $userid, 'wonprize' => $raffle_prize];
            //                                 $Inv_update = update($con, "superraffledraw", "`id` = '$capID' AND status = 'Active' ORDER BY `id` DESC LIMIT 1", $inv_arr, "", "", "", "");
            //                                 $errors = $Inv_update['errors'];
            //                                 if ($errors != "") {
            //                                     // echo 'Update Process Failed';
            //                                     $result["type"] = "1";
            //                                     $result["result"] = "The winner selection process has failed.";
            //                                     goto resultNIF34;
            //                                 } else {
            //                                     //   $winnerID = $userid;
            //                                     //   goto resultGO;
            //                                     $result["type"] = "0";
            //                                     $result["result"] = "Execution strategy and new winner have also been updated.";
            //                                     goto resultNIF34;
            //                                 }
            //                             }
            //                         } else {
            //                             $result["type"] = "1";
            //                             $result["result"] = 'The prize amount needs to be more than 1 AED!';
            //                             goto resultNIF34;
            //                         }
            //                     } else {
            //                         $result["type"] = "1";
            //                         $result["result"] = "The Product Not Found!";
            //                         goto resultNIF34;
            //                     }
            //                 }
            //             }
            //         }
            //     }
            // }



        } else {
            $result["type"] = "1";
            $result["result"] = "Active Draw Not Found!";
            goto resultNIF34;
        }

        $result["type"] = "0";
        $result["result"] = "Execution strategy updated successfully.";
        goto resultNIF34;
    }

    resultNIF34:
    echo json_encode($result);
} else if ($method == 'GrandRaffleAnnounce') {
    $result = [];

    $raffleDrawID = $_POST['raffleDrawID'];
    $winnerID = $_POST['winnerID'];

    if ($raffleDrawID == '' || $raffleDrawID == '') {
        $result["type"] = "1";
        $result["result"] = 'The Draw ID Missing!';
        goto resultNIF340;
    }

    if ($winnerID == '' || $winnerID == '') {
        $result["type"] = "1";
        $result["result"] = 'The Winner ID Missing!';
        goto resultNIF340;
    }

    $draw = select_query($con, "superraffledraw", "", "`status` = 'Active' AND `id` = '$raffleDrawID' AND `win_userid` = '$winnerID' AND `deletes` = '0' ORDER BY `result_datetime` ASC LIMIT 1", "", "");
    if ($draw['nr'] > 0) {

        $prizeAmt = (int)$draw['result'][0]['wonprize'];
        $ticketLineID = $draw['result'][0]['win_ticket_line_id'];
        $raffleNo =  $draw['result'][0]['win_raffle_no'];
        $raffleRusltTime = $draw['result'][0]['result_datetime'];

        if ($ticketLineID == '' && $ticketLineID == null) {
            $result["type"] = "1";
            $result["result"] = 'The Ticket ID Missing!';
            goto resultNIF340;
        }


        if ($raffleNo == '' && $raffleNo == null) {
            $result["type"] = "1";
            $result["result"] = 'The Ticket Raffle No Missing!';
            goto resultNIF340;
        }

        $collectDrawData = mysqli_query($con, "SELECT d.draw_no, d.result_datetime, p.rate FROM `ticket_lines` AS t LEFT JOIN draw AS d ON d.id = t.draw_id LEFT JOIN product AS p ON p.id = t.product_id WHERE t.id = '$ticketLineID' AND t.deletes = '0' AND t.raffle_id = '$raffleNo';");
        if ($collectDrawData && mysqli_num_rows($collectDrawData) > 0) {
            $drow = mysqli_fetch_assoc($collectDrawData);

            $drawNo =  $drow['draw_no'];
            $drawDate = $drow['result_datetime'];
            $productRate = $drow['rate'];

            // var_dump($productRate);
            // die;

            if ($drawNo == '' || $drawDate == '' ||  $productRate == '') {
                $result["type"] = "1";
                $result["result"] = 'The Draw details missing!';
                goto resultNIF340;
            }


            if ($prizeAmt < 1) {
                $result["type"] = "1";
                $result["result"] = 'The prize amount needs to be more than 1 AED!';
                goto resultNIF340;
            }

            $userData = select_query($con, "user_register", "", "`id`='$winnerID' AND `deletes` = '0' AND `roll_id` = '0' AND `status` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
            if ($userData['nr'] > 0) {

                $name = $userData['result'][0]['name'] . ' ' . ($userData['result'][0]['lname'] ?? '');
                $email = $userData['result'][0]['email'];
                $mobile = $userData['result'][0]['mobile'];
                $t_earning = (int)$userData['result'][0]['t_earning'];

                $totalBalance = $prizeAmt + $t_earning;



                $userArr = [
                    't_earning' => $totalBalance
                ];
                $userUpdate = update($con, "user_register", "`id`='$winnerID' AND `deletes` = '0' AND `roll_id` = '0' AND `status` = '0' ORDER BY `id` DESC LIMIT 1", $userArr, "", "", "", "");
                $errors = $userUpdate['errors'];
                if ($errors != "") {
                    // $result["type"] = "0";
                    // $result["result"] = $errors;
                    $result["type"] = "1";
                    $result["result"] = 'The process of transferring the winning amount to the wallet has failed.';
                    goto resultNIF340;
                } else {

                    $fmUpdateArr = [
                        'status' => 'Completed',
                        'winner_name' => $name,
                        'email' =>  $email,
                        'mobile' =>  $mobile,
                        // 'image_url' =>  'assets/images/Male156520230622020600.jpg'
                    ];

                    $fmUpdate = update($con, "superraffledraw", "`status` = 'Active' AND `id` = '$raffleDrawID' AND `win_userid` = '$winnerID' AND `deletes` = '0' ORDER BY `result_datetime` ASC LIMIT 1", $fmUpdateArr, "", "", "", "");
                    $errors = $fmUpdate['errors'];
                    if ($errors != "") {
                        $result["type"] = "0";
                        $result["result"] = $errors;
                    } else {

                        $prizeIMG = [
                            'AED10' => constant('assetURL') . 'nationaldraw/1/superAed10.jpg',
                            'AED20' => constant('assetURL') . 'nationaldraw/1/superAed20.jpg',
                            'AED50' => constant('assetURL') . 'nationaldraw/1/superAed50.jpg',
                            'AED100' => constant('assetURL') . 'nationaldraw/1/superAed100.jpg'
                        ];


                        // Email Template
                        $emailTemplate = '
                        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml">
                           <head>
                              <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                              <meta http-equiv="X-UA-Compatible" content="IE=edge">
                              <meta name="viewport" content="width=device-width, initial-scale=1.0">
                              <title>3 Day Super Sale Campaign -Template</title>
                              <style type="text/css"> 
                                 @import url("https://fonts.googleapis.com/css2?family=Barlow+Condensed&display=swap");
                                 body {
                                 margin: 0;
                                 }
                                 .wrapper {
                                 background:#CCC;
                                 }
                                 .main {
                                 background:#FFF;
                                 max-width:600px;
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
                                 text-align:center;
                                 margin:0 auto;
                                 }
                                 .column-one .column {
                                 width:100%;
                                 margin:0 auto;
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
                                 margin: 4px 0 ;
                                 }
                                 td.column-two p {
                                 text-align: justify;
                                 color: #01104e;
                                 font-family: Verdana, sans-serif !important;
                                 font-size: 17px;
                                 font-weight: 500!important;
                                 padding: 0 10%;
                                 margin: auto;
                                 }
                                 .c-f{
                                 padding: 8px 0;
                                 }
                                 a.PARTICIPATE {
                                 text-align: center;
                                 padding: 7px 18px;
                                 color: #fff;
                                 font-family: Roboto, sans-serif!important;
                                 font-size: 19px;
                                 font-weight: 700!important;
                                 border-radius: 4px;
                                 margin: auto;
                                 background: #01104e;
                                 text-decoration: auto;
                                 }
                              </style>
                           </head>
                           <body contenteditable="false">
                              <center class="wrapper">
                                 <table class="main" width="100%">
                                    <!-- BORDER -->
                                    <!-- <tr>
                                       <td class="column-one f" style="background: #01104e; height:50px;">
                                       </td>
                                       </tr> -->
                                    <tbody>
                                       <tr>
                                          <td class="column-one">
                                             <table class="column">
                                                <tbody>
                                                   <tr>
                                                      <td valign="top" style="padding: 0;">
                                                         <center>
                                                            <br>
                                                            <img src="' . constant('assetURL') . 'nationaldraw/1/nationaldrawLogo.png" style="border: 0px;" width="35%">
                                                         </center>
                                                      </td>
                                                   </tr>
                                                </tbody>
                                             </table>
                                          </td>
                                       </tr>
                                       <!-- LOGO  -->
                                       <tr>
                                          <td class="column-one c-f">
                                             <p>Hi, <strong style="font-weight: 500!important;">' . ucwords(strtolower($name)) . '</strong></p>
                                             <p>Congratulations!</p>
                                          </td>
                                       </tr>
                                       <tr class="column-one">
                                          <td>
                                            <img src="' . $prizeIMG['AED' . intval($productRate)] . '" width="85%" alt="">
                                            
                                        </td>
                                       </tr>
                                       <tr>
                                          <td class="column-two">
                                      <br>
                                             <p style="text-align: center; font-size: 16px; margin-bottom: 10px;">Congratulations on this amazing achievement!  </p>
                                          </td>
                                       </tr>
                                       <tr>
                                        <td class="column-two">
                                    
                                           <p style="text-align: center; font-size: 17px; margin-bottom: 33px; font-weight: 600!important; letter-spacing: 1px;">Keep participating &<br>
                                            Keep Winning! </p>
                                        </td>
                                     </tr>
                                     
                                      
                                       <tr style="text-align:center;">
                                          <td class="column-one">
                                             <img style="width:500px !important;margin-top: 10px;" src="' . constant('assetURL') . 'nationaldraw/1/EmailTemplateFooter.png">
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <p style="color: #01104e !important;font-size: 11px !important;margin:0 0 7px 0px !important;text-align: center !important;font-weight: 600 !important;font-family: Verdana, sans-serif !important;">Note: This is a system auto-generated email. Please do not reply to this mail.
                                             </p>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td class="column-one" style="background: #e6e6e6; height:15px;">
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                                 <!-- End Main Class -->
                              </center>
                              <!-- End Wrapper -->
                              
                        </html>';

                        $subject = "Congratulations! You Are the Winner of the Super Raffle " . date('Y', strtotime($raffleRusltTime));

                        $emailchack = explode('@', $email);
                        if (strtolower($emailchack[1]) != "nationaldraw.ae") {

                            $user_ip = getUserIP();
                            $messages2 = mysqli_real_escape_string($con, $emailTemplate);

                            $insertlog = mysqli_query($con, "INSERT INTO `emaillog` (`details`,`subject`,`email`,`ip`,`datetime`,`status`) VALUES ('$messages2','$subject','$email','$user_ip','$dubaidate_time','0')");
                        }

                        if (substr($mobile, 0, 3) == "971") {
                            // SMS Content
                            $messages1 = 'Congrats! You have won AED ' . number_format($prizeAmt) . ' in our Super Raffle Draw ' . date('M-Y', strtotime($raffleRusltTime)) . ', the Raffle ID ' . $raffleNo . ', participated category of AED ' . intval($productRate) . ' dated ' . date('d.m.Y', strtotime($drawDate)) . ' and Draw No:' . $drawNo . '. Good Luck!!! For future draws, national Steps Big Dreams.';
                            $log = mysqli_query($con, "INSERT INTO `smslog` (`gateway`, `details`,`mobile`,`ip`,`datetime`,`status`, `smssendstatus`) VALUES ('', '$messages1','$mobile','','$dubaidate_time','', '0')");
                        }


                        $result["type"] = "0";
                        $result["result"] = "The Super Raffle Winner Announcement Is Complete!";
                        goto resultNIF340;
                    }

                    // var_dump($t_earning);
                    // die;
                }
            } else {
                $result["type"] = "1";
                $result["result"] = "The Details not found!";
                goto resultNIF340;
            }
        } else {

            $result["type"] = "1";
            $result["result"] = "The Draw Details Not Found!";
            goto resultNIF340;
        }
    } else {
        $result["type"] = "1";
        $result["result"] = "Active Draw Not Found!";
        goto resultNIF340;
    }


    resultNIF340:
    echo json_encode($result);
} else if ($method == 'raffledrawPreWinners') {
    $result = [];

    $capID = '2';

    $draw = select_query($con, "superraffledraw", "", "`status` = 'Active' AND `id` = '$capID' AND `deletes` = '0' ORDER BY `result_datetime` ASC LIMIT 1", "", "");
    if ($draw['nr'] > 0) {
        $startTime = $draw['result'][0]['ticket_start_datetime'];
        $endTime = $draw['result'][0]['ticket_end_datetime'];

        $getWinnersList = mysqli_query($con, "SELECT g.user_id, SUM(g.rate) AS `total`, g.name, g.lname, g.mobile, g.email FROM ( SELECT t.user_id, p.rate, u.name, u.lname, u.mobile, u.email FROM `ticket_lines` AS t LEFT JOIN `product` AS p ON p.id = t.product_id LEFT JOIN user_register AS u ON u.id = t.user_id WHERE t.deletes = '0' AND u.deletes = '0' AND u.status = '0' AND u.roll_id = '0' AND t.createdon BETWEEN '$startTime' AND '$endTime' ) AS g GROUP BY g.user_id ORDER BY `total` DESC LIMIT 100;");
        $data = mysqli_fetch_all($getWinnersList, MYSQLI_ASSOC);
        if (mysqli_num_rows($getWinnersList) > 0) {

            // SELECT g.* FROM (SELECT t.user_id, t.product_id, COUNT(t.product_id) * p.rate AS 'productRate', p.rate FROM `ticket_lines` AS t LEFT JOIN product AS p ON p.id = t.product_id WHERE t.deletes = '0' GROUP BY t.user_id, t.product_id ORDER BY `productRate` DESC) AS g WHERE g.user_id = 132065;
            $i = 0;
            foreach ($data as $key => $value) {

                $userID = $value['user_id'];

                $productCat = mysqli_query($con, "SELECT g.* FROM (SELECT t.user_id, t.product_id, COUNT(t.product_id) * p.rate AS 'productRate', p.rate FROM `ticket_lines` AS t LEFT JOIN product AS p ON p.id = t.product_id WHERE t.deletes = '0' GROUP BY t.user_id, t.product_id ORDER BY `productRate` DESC) AS g WHERE g.user_id = $userID;");
                while ($row = mysqli_fetch_assoc($productCat)) {
                    $data[$i]['AED' . intval($row['rate'])] = intval($row['productRate']);
                }

                $i++;
                // var_dump($data);
                // die;
            }

            $result["type"] = "0";
            $result['result'] = $data;
            goto resultNIF34012;
        } else {
            $result["type"] = "0";
            $result['result'] = [];
            goto resultNIF34012;
        }
    }

    resultNIF34012:
    echo json_encode($result);
} elseif ($method == "check_winner_list1") {



    $rafflePrizeOne = strtoupper($_REQUEST['rafflePrizeTwo']);
    $drawid = $_REQUEST['drawid'];
    $rafflePrizeOne = trim($rafflePrizeOne);

    if (empty($rafflePrizeOne)) {
        $result["type"] = "0";
        $result["result"] = "Raffle ID cannot be empty";
        $result['input'] = 'rafflePrizeTwo';
    } else {
        $validTypes = array('O', 'A', 'C', 'B', 'T', 'P', 'K');
        $inputType = strtoupper(substr($rafflePrizeOne, 0, 1));

        if (!in_array($inputType, $validTypes)) {
            $result["type"] = "0";
            $result["result"] = "Incorrect Raffle ID";
            $result['input'] = 'rafflePrizeTwo';
        } else {
            $typeResult = select_query($con, "ticket_lines", "", "`type` LIKE '$inputType%' ORDER BY `id` DESC LIMIT 1", "", "");

            if (empty($typeResult)) {
                $result["type"] = "0";
                $result["result"] = "Incorrect Raffle ID";
                $result['input'] = 'rafflePrizeTwo';
            } else {
                $Ticket_lines = select_query($con, "ticket_lines", "", "`raffle_id` LIKE '$rafflePrizeOne%'  ORDER BY `id` DESC LIMIT 1", "", "");

                if ($Ticket_lines['nr'] > 0) {
                    foreach ($Ticket_lines['result'] as $key => $value) {
                        $start = $value['createdon'];
                        if ($start) {
                            $formattedStart = date('Y-m-d H:i:s', strtotime($start));
                            $datefilter1 = "`ticket_start_datetime` <= '$start' AND ticket_end_datetime > '$start' ";
                        } else {
                            $datefilter1 = "";
                        }
                    }
                }

                $consolidated_Report = select_query($con, "superraffledraw", "", "$datefilter1 ORDER BY `id` DESC LIMIT 1", "", "");
                $drawid =  $consolidated_Report['result'][0]['id'];

                $contype = "`raffle_id` LIKE '$rafflePrizeOne%' AND `createdon` = '$start'";
                $getFirstRaffleData = select_query($con, "ticket_lines", "", "$contype AND `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");

                if ($getFirstRaffleData['nr'] == 0 && $rafflePrizeOne != "") {
                    $result["type"] = "0";
                    $result["result"] = "Incorrect Raffle ID";
                    $result['input'] = 'rafflePrizeTwo';
                } else {
                    $result["type"] = "1";
                    $result["id"] = "0";
                    $result["result"] = "Success!";
                }
            }
        }
    }
    echo json_encode($result);
} elseif ($method == "winner_list1") {
    $rafflePrizeTwo = $_REQUEST['rafflePrizeTwo'];
    $drawid = $_REQUEST['drawid'];


    if ($rafflePrizeTwo == '') {
        $result["type"] = "0";
        $result["result"] = "Please Enter Raffle Id";
        $result['input'] = 'rafflePrizeTwo';
    } else {
        $Ticket_lines = select_query($con, "ticket_lines", "", "`raffle_id` = '$rafflePrizeTwo' ORDER BY `id` DESC LIMIT 1", "", "");

        if ($Ticket_lines['nr'] == 0) {


            $result["type"] = "0";
            $result["result"] = "Incorrect Raffle ID";
            $result['input'] = 'rafflePrizeTwo';
        } else {

            if ($Ticket_lines['nr'] > 0) {

                // var_dump($Ticket_lines);die;
                foreach ($Ticket_lines['result'] as $key => $value) {

                    $start = $value['createdon'];

                    if ($start) {
                        $formattedStart = date('Y-m-d H:i:s', strtotime($start));

                        // Create the date filter
                        $datefilter1 = "`ticket_start_datetime` <= ' $start' AND ticket_end_datetime > ' $start' ";
                        // ticket_start_datetime <= ' $start' AND ticket_end_datetime > ' $start' AND

                    } else {

                        $datefilter1 = "";
                    }
                }
            }


            // var_dump("`ticket_start_datetime` <= ' $start' AND ticket_end_datetime > ' $start' ");die;
            $consolidated_Report = select_query($con, "superraffledraw", "", "`draw_no`='$drawid' AND $datefilter1 ORDER BY `id` DESC LIMIT 1", "", "");

            // var_dump($consolidated_Report);die;
            if ($consolidated_Report['nr'] == 0) {
                $result["type"] = "0";
                $result["result"] = "Incorrect Raffle ID";
                $result['input'] = 'rafflePrizeTwo';
            } else {


                $cusname_id = select_top_name($rcon, "ticket_lines", "user_id", "`raffle_id`='$rafflePrizeTwo'", "user_id", "");

                $product_id = select_top_name($rcon, "ticket_lines", "product_id", "`raffle_id`='$rafflePrizeTwo'", "product_id", "");

                $id = select_top_name($rcon, "ticket_lines", "id", "`raffle_id`='$rafflePrizeTwo'", "id", "");

                $prize = select_top_name($rcon, "product", "super_raffle_prize", "`id`='$product_id'", "super_raffle_prize", "");

                $cusname = select_top_name($rcon, "user_register", "name", "`id`='$cusname_id'", "name", "");
                $cuslname = select_top_name($rcon, "user_register", "lname", "`id`='$cusname_id'", "lname", "");
                $email  = select_top_name($rcon, "user_register", "email", "`id`='$cusname_id'", "email", "");
                $mobile = select_top_name($rcon, "user_register", "mobile", "`id`='$cusname_id'", "mobile", "");

                $draw_arr = array("win_raffle_no" => $rafflePrizeTwo);


                $draw_update = update($con, "superraffledraw", "`id` = '$drawid' and `deletes`='0'", ["win_raffle_no" => $rafflePrizeTwo,  "win_userid" => $cusname_id, "win_ticket_line_id" => $id, "wonprize" => $prize, "product_id" => $product_id], "", "", "", "");
                //$draw_update = update($con, "superraffledraw", "`id` = '$drawid' and `deletes`='0'", ["win_raffle_no" => $rafflePrizeTwo, "winner_name" => $cusname . ' ' . $cuslname, "email" => $email, "mobile" => $mobile, "win_userid" => $cusname_id,"win_ticket_line_id" => $id, "wonprize" => $prize, "product_id" => $product_id], "", "", "", "");



                $result["type"] = "1";

                $result["id"] = $drawid;

                $result["result"] = "Success!";
            }
        }
        // var_dump($consolidated_Report);die;
    }

    echo json_encode($result);
}
