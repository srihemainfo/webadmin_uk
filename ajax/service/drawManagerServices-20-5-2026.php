<?php
include '../../include/shi-config.php';
include '../../include/functions.php';

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

try {

    function getWinnerInfo($con, $winnerRaffleID, $prize, $saleDate, $ticketCon, $todayGoldPrize)
    {
        $raffleCheckQuery = "SELECT n.*, u.id AS 'userID', u.email, u.name, u.lname, u.mobile FROM ndticket n LEFT JOIN user_register u ON u.id = n.userId WHERE JSON_CONTAINS(n.raffleIds, '\"$winnerRaffleID\"') AND n.deletes = '0' AND STR_TO_DATE(n.endDate, '%Y-%m-%d') >= '" . $saleDate . "' AND n.userId != 0 AND n.oldTicketID = 0  $ticketCon ORDER BY n.`id` DESC LIMIT 1;";
        $runQuery = mysqli_query($con, $raffleCheckQuery);
        if ($runQuery && mysqli_num_rows($runQuery) > 0) {
            $row = mysqli_fetch_assoc($runQuery);
            return [
                "userID" =>  $row['userID'],
                "Name" =>  $row['name'] . ' ' . ($row['lname'] ?? ''),
                "Email" => $row['email'],
                "Mobile" => $row['mobile'],
                "RaffleID" => $winnerRaffleID,
                "prize" => $prize,
                'ticketReferenceID' => $row['referenceID'],
                'ticketID' => $row['id'],
                "prizeAmt" => $prize * $todayGoldPrize,
            ];
        }
        return null;
    }

    $method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
    $headers = apache_request_headers();
    $result = [];

    // Log
    error_log_new($con, getUserIP(), 'processStart', $_SESSION['memid'], '', '', 'The Request recored', json_encode($_REQUEST), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);


    // Auth Check 
    $currentUserID = $_SESSION['memid'] ?? '';
    if ($currentUserID == '') {
        $result['type'] = '0';
        $result['result'] = 'Login Required';
        goto resultFI;
    }

    // Get All Draw List
    if ($method == "getAllDraw") {

        $resultDate = $_POST['resultDate'];

        $getAll = $_POST['getAll'] ?? false;

        $selectQuery = "SELECT * FROM `draw` AS d WHERE d.`deletes` = '0'";

        $dateTime = DateTime::createFromFormat('Y-m-d', $resultDate);


        if (!$getAll) {
            if ($resultDate != '' && $dateTime->format('Y-m-d') === $resultDate) {
                $selectQuery .= " AND d.`resultDate` = '$resultDate'";
            } else {
                $selectQuery .= " AND d.`resultDate` >= '" . date('Y-m-d', strtotime('-3 day')) . "'";
            }
        }
        // var_dump($selectQuery);
        // die;

        $runQuery = mysqli_query($con, $selectQuery);

        if ($runQuery && mysqli_num_rows($runQuery) > 0) {
            $result['type'] = '1';
            $result['result'] = mysqli_fetch_all($runQuery, MYSQLI_ASSOC);
            goto resultFI;
        }

        $result['type'] = '0';
        $result['result'] = [];
        goto resultFI;
    } else if ($method == "WinnerUpdate") {
        $noRaffleID = $_REQUEST['noRaffleID'];
        $drawKeyWord = $_REQUEST['drawKeyWord'];
        $drawID = $_REQUEST['drawID'];

        if ($drawID == '' || $drawID == null) {
            $result['type'] = '0';
            $result['result'] = 'The Draw ID Missing!';
            goto resultFI;
        }

        if ($drawKeyWord == '' || $drawKeyWord == null) {
            $result['type'] = '0';
            $result['result'] = 'Method Not Found!';
            goto resultFI;
        }


        if ($noRaffleID == '' || $noRaffleID == null || $noRaffleID < 1) {
            $result['type'] = '0';
            $result['result'] = 'Raffle No Missing';
            goto resultFI;
        }

        $checkDraw = select_query($con, "draw", "", "`id` = $drawID AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
        if ($checkDraw['nr'] < 1) {
            $result['type'] = '0';
            $result['result'] = 'The Active Draw Not Found!';
            goto resultFI;
        }

        $saleDate = date('Y-m-d', strtotime($checkDraw['result'][0]['saleDate'])) ?? '';
        $dailyThirllStatus =  $checkDraw['result'][0]['dailyThirllStatus'] ?? '';
        $monthlyBumperStatus =  $checkDraw['result'][0]['monthlyBumperStatus'] ?? '';
        $weeklyBoosterStatus = $checkDraw['result'][0]['weeklyBoosterStatus']  ?? '';

        $todayGoldPrize =  $checkDraw['result'][0]['todayGoldPrize'] ?? 0;

        if ($todayGoldPrize < 1) {
            $result['type'] = '0';
            $result['result'] = 'Please provide an update on the gold rate in AED. Please try again!';
            goto resultFI;
        }


        $tableName = "eligible_raffles_" . $drawID;

        // SQL query to check if the table exists
        $sql = "SHOW TABLES LIKE '$tableName'";
        $checkTable = $con->query($sql);

        // If the table doesn't exist, create it
        if ($checkTable->num_rows == 0) {
            $result['type'] = '0';
            $result['result'] = 'Eligible raffle list not found. Please contact the developers for assistance.';
            goto resultFI;
        }


        if ($drawKeyWord === 'dailyThrill' || $drawKeyWord === 'monthlyBumber' || $drawKeyWord === 'weeklyBooster') {

            $winnerRaffleID = $_REQUEST['winnerRaffleID' . $noRaffleID];

            if ($winnerRaffleID == '' || $winnerRaffleID == null || !isset($winnerRaffleID)) {
                $result['type'] = '0';
                $result['result'] = '';
                // $result['errorRaffleID'][] = 'winnerRaffleID' . $noRaffleID;
                $result['errorRaffleID'][] = [
                    'id' => 'winnerRaffleID' . $noRaffleID,
                    'error' => 'Raffle No Missing!'
                ];
                goto resultFI;
            }


            if (($dailyThirllStatus === 'Active' && $drawKeyWord === 'dailyThrill') || ($monthlyBumperStatus === 'Active' && $drawKeyWord === 'monthlyBumber') || ($weeklyBoosterStatus === 'Active' && $drawKeyWord === 'weeklyBooster')) {

                $ticketCon = '';
                $checkQueryR = '';
                if ($drawKeyWord === 'dailyThrill') {
                    $ticketCon = "AND `is_thrill` = 'YES'";
                    // Check Existing 
                    $checkQueryR = "SELECT * FROM `draw` WHERE `id` = $drawID AND (`winweeklyBoosterIds` LIKE '$winnerRaffleID' OR `winBumperRaffleIds` LIKE '$winnerRaffleID') AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1;";
                } else if ($drawKeyWord === 'monthlyBumber') {
                    $ticketCon = "AND `is_bumper` = 'YES'";
                    // Check Existing 
                    $checkQueryR = "SELECT * FROM `draw` WHERE `id` = $drawID AND (`winweeklyBoosterIds` LIKE '$winnerRaffleID' OR `winThirllRaffleIds` LIKE '$winnerRaffleID') AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1;";
                } else if ($drawKeyWord === 'weeklyBooster') {
                    $ticketCon = "AND `is_weekly` = 'YES'";
                    // Check Existing 
                    $checkQueryR = "SELECT * FROM `draw` WHERE `id` = $drawID AND (`winBumperRaffleIds` LIKE '$winnerRaffleID' OR `winThirllRaffleIds` LIKE '$winnerRaffleID') AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1;";
                }


                if ($checkQueryR == '') {
                    $result['type'] = '0';
                    $result['result'] = 'The Draw Not Found!';
                    goto resultFI;
                }


                $elibibleCheck = select_query($con, $tableName, "", "`raffleid` LIKE '$winnerRaffleID' $ticketCon ORDER BY `id` DESC LIMIT 1", "", "");

                // var_dump($elibibleCheck);
                // die;

                if ($elibibleCheck['nr'] < 1) {
                    $result['type'] = '0';
                    $result['result'] = 'The Raffle ID not eligible for this draw!';
                    goto resultFI;
                }








                // var_dump($checkQueryR);
                // die;
                $runQuery1 = mysqli_query($con, $checkQueryR);
                if ($runQuery1) {
                    if (mysqli_num_rows($runQuery1) < 1) {

                        // $raffleCheckQuery = "SELECT * FROM ndticket WHERE JSON_CONTAINS(raffleIds, '\"$winnerRaffleID\"') AND deletes = '0' AND sid = SUBSTRING('" . $winnerRaffleID . "', 3, LENGTH('" . $winnerRaffleID . "') - 4) AND STR_TO_DATE(endDate, '%Y-%m-%d') >= '" . $saleDate . "' AND userId != 0 AND oldTicketID = 0  ORDER BY `id` DESC LIMIT 1;";
                        $raffleCheckQuery = "SELECT * FROM ndticket WHERE JSON_CONTAINS(raffleIds, '\"$winnerRaffleID\"') AND deletes = '0' AND STR_TO_DATE(endDate, '%Y-%m-%d') >= '" . $saleDate . "' AND userId != 0 AND oldTicketID = 0  $ticketCon ORDER BY `id` DESC LIMIT 1;";

                        // var_dump($raffleCheckQuery);
                        // die;

                        $runQuery = mysqli_query($con, $raffleCheckQuery);
                        if ($runQuery) {
                            if (mysqli_num_rows($runQuery) > 0) {
                                $row = mysqli_fetch_assoc($runQuery);
                                if ($row) {

                                    $whereCon = '';
                                    $updateArr = [];
                                    if ($drawKeyWord === 'dailyThrill') {
                                        $updateArr['winThirllRaffleIds'] = $winnerRaffleID;
                                        $whereCon .= "AND `dailyThirllStatus` = 'Active'";
                                    } else if ($drawKeyWord === 'monthlyBumber') {
                                        $updateArr['winBumperRaffleIds'] = $winnerRaffleID;
                                        $whereCon .= "AND `monthlyBumperStatus` = 'Active'";
                                    } else if ($drawKeyWord === 'weeklyBooster') {
                                        $updateArr['winweeklyBoosterIds'] = $winnerRaffleID;
                                        $whereCon .= "AND `weeklyBoosterStatus` = 'Active'";
                                    }

                                    if ($whereCon == '') {
                                        $result['type'] = '0';
                                        $result['result'] = 'The Draw Not Found!';
                                        goto resultFI;
                                    }

                                    $updateDraw = update($con, "draw", "`id` = $drawID AND `deletes` = '0' $whereCon ORDER BY `id` DESC LIMIT 1", $updateArr, "", "", "", "");

                                    // var_dump($updateDraw);
                                    // die;

                                    $errors = $updateDraw['errors'];
                                    if ($errors != "") {
                                        $result["type"] = "0";
                                        $result["result"] = "The Winner Updated Process failed!";
                                        goto resultFI;
                                    } else {
                                        $result['type'] = '1';
                                        $result['result'] = 'The Winner updated successfully!';
                                        goto resultFI;
                                    }
                                } else {
                                    $result['type'] = '0';
                                    // $result['result'] = 'The Raffle ID Not Found. Please Provide Valid Raffle ID!';
                                    // $result['errorRaffleID'][] = 'winnerRaffleID' . $noRaffleID;
                                    $result['errorRaffleID'][] = [
                                        'id' => 'winnerRaffleID' . $noRaffleID,
                                        'error' => 'The Raffle ID Not Found. Please Provide Valid Raffle ID!'
                                    ];
                                    goto resultFI;
                                }
                            } else {
                                $result['type'] = '0';
                                $result['result'] = '';
                                // $result['errorRaffleID'][] = 'winnerRaffleID' . $noRaffleID;
                                $result['errorRaffleID'][] = [
                                    'id' => 'winnerRaffleID' . $noRaffleID,
                                    'error' => 'The Raffle ID Not Found. Please Provide Valid Raffle ID!'
                                ];
                                goto resultFI;
                            }
                        } else {
                            $result['type'] = '0';
                            $result['result'] = 'The Recheck Query Failed!';
                            goto resultFI;
                        }
                    } else {
                        $result['type'] = '0';
                        $result['result'] = '';
                        // $result['errorRaffleID'][] = 'winnerRaffleID' . $noRaffleID;
                        $result['errorRaffleID'][] = [
                            'id' => 'winnerRaffleID' . $noRaffleID,
                            'error' => 'The Raffle ID has already been set for today\'s winners.!'
                        ];
                        goto resultFI;
                    }
                } else {
                    $result['type'] = '0';
                    $result['result'] = 'The Recheck Query Failed!';
                    goto resultFI;
                }
            } else {
                $result['type'] = '0';
                $result['result'] = 'The Active Draw Not Found!';
                goto resultFI;
            }
        } else {
            $result['type'] = '0';
            $result['result'] = 'Method Not Found!';
            goto resultFI;
        }
    } else if ($method == "WinnerPreview" || $method == 'WinnerList') {
        $drawID = $_REQUEST['drawID'];



        if ($drawID == '' || $drawID == null) {
            $result['type'] = '0';
            $result['result'] = 'The Draw ID Missing!';
            goto resultFI;
        }


        $checkDraw = select_query($con, "draw", "", "`id` = $drawID AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
        if ($checkDraw['nr'] < 1) {
            $result['type'] = '0';
            $result['result'] = 'The Active Draw Not Found!';
            goto resultFI;
        }

        $ticketCon = '';

        $winThirllRaffleIds =  $checkDraw['result'][0]['winThirllRaffleIds'] ?? '';
        // $winConRaffleIds =  json_decode($checkDraw['result'][0]['winConRaffleIds'], true) ?? '';
        $winweeklyBoosterIds =  $checkDraw['result'][0]['winweeklyBoosterIds'] ?? '';
        $winBumperRaffleIds = $checkDraw['result'][0]['winBumperRaffleIds']  ?? '';
        $saleDate = date('Y-m-d', strtotime($checkDraw['result'][0]['saleDate'])) ?? '';

        // Prize
        $dailyThirllPrice = (int) $checkDraw['result'][0]['dailyThirllPrice'] ?? 0;
        $weeklyBoosterPrice =  (int) $checkDraw['result'][0]['weeklyBoosterPrice'] ?? 0;
        $monthlyBumperPrice = (int) $checkDraw['result'][0]['monthlyBumperPrice']  ?? 0;

        $todayGoldPrize =  $checkDraw['result'][0]['todayGoldPrize'] ?? 0;

        if ($todayGoldPrize < 1) {
            $result['type'] = '0';
            $result['result'] = 'Please provide an update on the gold rate in AED. Please try again!';
            goto resultFI;
        }


        $winnerData = [];

        if ($method == 'WinnerList') {
            $result['type'] = '1';
            $result['result'] = 'The Winner details collected successfully!';
            $result['dailyThirllStatus'] = $checkDraw['result'][0]['dailyThirllStatus'] ?? '';
            $result['weeklyBoosterStatus'] = $checkDraw['result'][0]['weeklyBoosterStatus'] ?? '';
            $result['monthlyBumperStatus'] = $checkDraw['result'][0]['monthlyBumperStatus'] ?? '';
            $result['winnerData'] = json_decode($checkDraw['result'][0]['previewData'], true) ?? '';
            goto resultFI;
        }


        if ($winThirllRaffleIds != '' && $winThirllRaffleIds != 'NULL' && $dailyThirllPrice > 0) {
            $ticketCon = "AND n.`is_thrill` = 'YES'";
            $getWinnerDetails = getWinnerInfo($con, $winThirllRaffleIds, $dailyThirllPrice, $saleDate, $ticketCon, $todayGoldPrize);
            if ($getWinnerDetails != null && count($getWinnerDetails) > 0) {
                $winnerData['winThirllRaffleIds'] = $getWinnerDetails;
            }
        }

        // var_dump($getWinnerDetails);die;
        // var_dump($winBumperRaffleIds);
        // die;

        if ($winBumperRaffleIds != '' && $winBumperRaffleIds != 'NULL' && $monthlyBumperPrice > 0) {
            $ticketCon = "AND n.`is_bumper` = 'YES'";
            $getWinnerDetails = getWinnerInfo($con, $winBumperRaffleIds, $monthlyBumperPrice, $saleDate, $ticketCon, $todayGoldPrize);

            if ($getWinnerDetails != null && count($getWinnerDetails) > 0) {
                $winnerData['winBumperRaffleIds'] = $getWinnerDetails;
            }
        }


        if ($winweeklyBoosterIds != '' && $winweeklyBoosterIds != 'NULL' && $weeklyBoosterPrice > 0) {
            $ticketCon = "AND n.`is_weekly` = 'YES'";
            // foreach ($winConRaffleIds as $key => $value) {
            $getWinnerDetails = getWinnerInfo($con, $winweeklyBoosterIds, $weeklyBoosterPrice, $saleDate, $ticketCon, $todayGoldPrize);
            if ($getWinnerDetails != null && count($getWinnerDetails) > 0) {
                $winnerData['winweeklyBoosterIds'] = $getWinnerDetails;
            }
            // }
        }

        if (count($winnerData) < 1) {
            $result['type'] = '0';
            $result['result'] = 'The Winners Not Found!';
            goto resultFI;
        }

        $winnerData['todayGoldPrize'] = $checkDraw['result'][0]['todayGoldPrize'];
        // dd($winnerData);
        $updateArr = [
            'previewData' => json_encode($winnerData)
        ];

        $updateDraw = update($con, "draw", "`id` = $drawID AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", $updateArr, "", "", "", "");
        $errors = $updateDraw['errors'];
        if ($errors != "") {
            $result["type"] = "0";
            $result["result"] = "The Winner Preview Process failed!";
            goto resultFI;
        } else {
            $result['type'] = '1';
            $result['result'] = 'The Winner details collected successfully!';
            $result['dailyThirllStatus'] = $checkDraw['result'][0]['dailyThirllStatus'] ?? '';
            $result['weeklyBoosterStatus'] = $checkDraw['result'][0]['weeklyBoosterStatus'] ?? '';
            $result['monthlyBumperStatus'] = $checkDraw['result'][0]['monthlyBumperStatus'] ?? '';
            $result['winnerData'] = $winnerData;
            goto resultFI;
        }
        // var_dump(json_encode($winnerData));
        // die;
    } else if ($method == "winnerAnnonuce") {
        $drawID = $_REQUEST['drawID'];

        if ($drawID == '' || $drawID == null) {
            $result['type'] = '0';
            $result['result'] = 'The Draw ID Missing!';
            goto resultFI;
        }


        $checkDraw = select_query($con, "draw", "", "`id` = $drawID AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
        if ($checkDraw['nr'] < 1) {
            $result['type'] = '0';
            $result['result'] = 'The Active Draw Not Found!';
            goto resultFI;
        }

        $winThirllRaffleIds =  $checkDraw['result'][0]['winThirllRaffleIds'] ?? '';
        $winConRaffleIds =  json_decode($checkDraw['result'][0]['winConRaffleIds'], true) ?? '';
        $winBumperRaffleIds = $checkDraw['result'][0]['winBumperRaffleIds']  ?? '';
        $saleDate = date('Y-m-d', strtotime($checkDraw['result'][0]['saleDate'])) ?? '';

        // Prize
        $dailyThirllPrice =  $checkDraw['result'][0]['dailyThirllPrice'];
        $weeklyBoosterPrice = $checkDraw['result'][0]['weeklyBoosterPrice'];
        $monthlyBumperPrice = $checkDraw['result'][0]['monthlyBumperPrice'];


        $previewData = json_decode($checkDraw['result'][0]['previewData'], true) ?? '';

        $dailyThirllStatus = $checkDraw['result'][0]['dailyThirllStatus'] ?? '';
        $weeklyBoosterStatus = $checkDraw['result'][0]['weeklyBoosterStatus'] ?? '';
        $monthlyBumperStatus = $checkDraw['result'][0]['monthlyBumperStatus'] ?? '';

        // var_dump($previewData['winThirllRaffleIds']);
        // die;

        $conRaflleCon = '';
        if ($previewData['winThirllRaffleIds'] != null && $previewData['winThirllRaffleIds']['RaffleID'] != '' && $previewData['winThirllRaffleIds']['RaffleID'] != null) {
            $conRaflleCon .= "AND `winThirllRaffleIds` = '" . ($previewData['winThirllRaffleIds']['RaffleID'] != '' ? $previewData['winThirllRaffleIds']['RaffleID'] : null) . "' ";
        }

        if ($previewData['winweeklyBoosterIds'] != null && $previewData['winweeklyBoosterIds']['RaffleID'] != '' && $previewData['winweeklyBoosterIds']['RaffleID'] != null) {
            $conRaflleCon .= "AND `winweeklyBoosterIds` = '" . ($previewData['winweeklyBoosterIds']['RaffleID'] != '' ? $previewData['winweeklyBoosterIds']['RaffleID'] : null) . "' ";
        }

        if ($previewData['winBumperRaffleIds'] != null && $previewData['winBumperRaffleIds']['RaffleID'] != '' && $previewData['winBumperRaffleIds']['RaffleID'] != null) {
            $conRaflleCon .= "AND `winBumperRaffleIds` = '" . ($previewData['winBumperRaffleIds']['RaffleID'] != '' ? $previewData['winBumperRaffleIds']['RaffleID'] : null) . "' ";
        }

        $checkRaffleID = select_query($con, "draw", "", "`id` = $drawID AND `deletes` = '0' $conRaflleCon ORDER BY `id` DESC LIMIT 1", "", "");



        if ($checkDraw['result'][0]['todayGoldPrize'] !== $previewData['todayGoldPrize']) {
            $result['type'] = '0';
            $result['result'] = 'Today Gold rate does not match! Refresh and Try Again!';
            goto resultFI;
        }
        // var_dump($checkRaffleID);
        // die;

        if ($checkRaffleID['nr'] < 1) {
            $result['type'] = '0';
            $result['result'] = 'The Raffle IDs does not match!';
            goto resultFI;
        }


        // var_dump($previewData);
        // die;

        $drawUpdateArr = [];

        if ($dailyThirllStatus === 'Active') {

            $winnerDetails = $previewData['winThirllRaffleIds'];
            if ($winnerDetails['RaffleID'] != '') {
                $checkWinnerlist = select_query($con, "winnerlist", "", "`userid` = '" . $winnerDetails['userID'] . "' AND `draw_id` = $drawID AND `winRaffleId` = '" . $winnerDetails['RaffleID'] . "' AND `ticketReferenceID` = '" . $winnerDetails['ticketReferenceID'] . "' AND `ticketID` = '" . $winnerDetails['ticketID'] . "' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
                if ($checkWinnerlist['nr'] < 1) {


                    $smsContent = 'WAWW!!! Congratulation! You have won the of AED ' . $winnerDetails['prizeAmt'] . ' for ' . ($checkDraw['result'][0]['dailyThrillName'] . ' Draw #' . str_pad($checkDraw['result'][0]['dailyDrawNo'], 3, '0', STR_PAD_LEFT)) . ' on ' . date("dS M Y", strtotime($checkDraw['result'][0]['resultDate'])) . '. Good Luck!!!';


                    $emailContent = '<!DOCTYPE html
                        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml">
                    
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title> Winner-email</title>
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
                                color: #171f4f;
                            }
                    
                            .column-one h3 {
                                color: #171f4f;
                                font-family: Verdana, sans-serif !important;
                                font-size: 28px;
                                font-weight: 600;
                                margin: 14px 0 0 0;
                            }
                    
                            .column-one p {
                                color: #171f4f;
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
                                                        <img src="' . constant('assetURL') . 'nationaldraw/1/ndLogo.png" style="border: 0px; margin:10px 0;  " width="50%">
                                                    </center>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top" style="padding: 0;">
                                                    <center>
                                                    
                                                        <img src="' . constant('assetURL') . 'nationaldraw/1/winnerImages.png" style="border-radius: 19px;" width="77%">
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
                                     <br>
                                     <p style="font-weight: 600!important;">Hi ' . ucwords(strtolower($winnerDetails['Name'])) . ',</p>
                                     <p style="font-size: 15px; font-weight: 400!important; margin: 16px 0; ">Congratulations on winning a prize of  ' . strval($winnerDetails['prize']) . ' Grams of Gold in <br>the ' . ($checkDraw['result'][0]['dailyThrillName'] . ' Draw #' . str_pad($checkDraw['result'][0]['dailyDrawNo'], 3, '0', STR_PAD_LEFT)) . ' held on ' . date("dS M Y", strtotime($checkDraw['result'][0]['resultDate'])) . '.
                                     </p>
                                     <p style="font-size: 16px; font-weight: 600!important; color:#088b42;">Your Winning Raffle ID</p>
                                     
                                    </td>
                                 </tr>
                                <tr>
                                    <td class="column-one ">
                                        <p style="font-size: 18px;font-weight: 500!important;font-family: Verdana, sans-serif !important;margin:4px 0;/* border: 2px dotted green; */width: fit-content;margin: 5px auto;padding: 6px;border-radius: 5px;background: #171f4f;color: #fff;">
                                             #' . $winnerDetails['RaffleID'] . '
                                          </p>
                                    </td>
                                </tr>                             
                                <tr>
                                    <td class="column-one">
                                        <br>
                                        <img style="width: !important;margin-top: 10px;" src="' . constant('assetURL') . 'nationaldraw/1/ndFooter.png" width="84%">
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
                    $emailSubject = 'Congrats! YOU HAVE WON - NationalDraw';

                    if (substr($winnerDetails['Mobile'], 0, 3) == "971") {
                        $smslog = mysqli_query($con, "INSERT INTO `smslog` (`gateway`, `details`,`mobile`,`ip`,`datetime`,`status`, `smssendstatus`) VALUES ('', '$smsContent','" . $winnerDetails['Mobile'] . "','" . getUserIP() . "','$dubaidate_time','', '0');");
                    }

                    if ($winnerDetails['Email'] != '') {
                        $emailchack = explode('@', $winnerDetails['Email']);
                        if (strtolower($emailchack[1]) != "nationaldrawuae.com") {
                            $messages2 = mysqli_real_escape_string($con, $emailContent);
                            $emaillog = mysqli_query($con, "INSERT INTO `emaillog` (`details`,`subject`,`email`,`ip`,`datetime`,`status`) VALUES ('$messages2','$emailSubject','" . $winnerDetails['Email'] . "','" . getUserIP() . "','$dubaidate_time','0');");
                        }
                    }



                    $winnerArr = [
                        'draw_id' => $drawID,
                        'userid' => $winnerDetails['userID'],
                        'drawType' => 'dailyThrill',
                        'winningDrawName' => $checkDraw['result'][0]['dailyThrillName'] . ' #' . str_pad($checkDraw['result'][0]['dailyDrawNo'], 3, '0', STR_PAD_LEFT),
                        'winRaffleId' => $winnerDetails['RaffleID'],
                        'fullName' => $winnerDetails['Name'],
                        'email' =>  $winnerDetails['Email'],
                        'mobile' =>  $winnerDetails['Mobile'],
                        'ticketID' => $winnerDetails['ticketID'],
                        'ticketReferenceID' => $winnerDetails['ticketReferenceID'],
                        'prize' => $winnerDetails['prize'],
                        'prize_amt' => $winnerDetails['prizeAmt'],
                        'smsContent' => $smsContent,
                        'emailSubject' => $emailSubject,
                        'emailContent' => mysqli_real_escape_string($con, $emailContent),
                        'smslog' => (isset($smslog) && $smslog) ? '1' : '0',
                        'emaillog' => (isset($emaillog) && $emaillog) ? '1' : '0',
                        'createdon' => $dubaidate_time,
                        'deletes' => '0'
                    ];
                    $winnerlistIns = insert($con, "winnerlist", "", $winnerArr, "", "", "");
                    if ($winnerlistIns['id'] != '') {

                        if ($winnerDetails['prize'] == 2 && $winnerDetails['Mobile'] != '') {

                            $sendWhatsAppMsg = invokeApiRequest('POST', API_DOMAIN . 'dtSendTemplate', [
                                'Accept: application/json',
                                'Content-Type: application/json',
                                // 'Authorization: Bearer ' . $_COOKIE['sessionToken']
                            ], json_encode(
                                [
                                    "senderName" => "DRAW",
                                    "mobileNo" => $winnerDetails['Mobile'],
                                    "templateName" => "winner_whatsapp_template_2g_v6_final",
                                    "language" => "en",
                                    "templateBodyParam" => [
                                        $winnerDetails['Name'],
                                        strval($winnerDetails['prize']),
                                        str_pad($checkDraw['result'][0]['dailyDrawNo'], 3, '0', STR_PAD_LEFT)
                                    ]
                                ]
                            ));
                            // var_dump($sendWhatsAppMsg);
                            // die;
                        }

                        $drawUpdateArr['dailyThirllStatus'] = 'Completed';
                    } else {
                        $result["type"] = "0";
                        $result["result"] = "The Winner Announcement Process failed!";
                        goto resultFI;
                    }
                }
            }
        }

        if ($monthlyBumperStatus === 'Active') {
            $winnerDetails = $previewData['winBumperRaffleIds'];
            if ($winnerDetails['RaffleID'] != '') {


                $checkWinnerlist = select_query($con, "winnerlist", "", "`userid` = '" . $winnerDetails['userID'] . "' AND `draw_id` = $drawID AND `winRaffleId` = '" . $winnerDetails['RaffleID'] . "' AND `ticketReferenceID` = '" . $winnerDetails['ticketReferenceID'] . "' AND `ticketID` = '" . $winnerDetails['ticketID'] . "' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
                // var_dump($checkWinnerlist);die;
                if ($checkWinnerlist['nr'] < 1) {

                    $smsContent = 'WAWW!!! Congratulation! You have won the of AED ' . $winnerDetails['prizeAmt'] . ' for ' . ($checkDraw['result'][0]['monthlyBumperName'] . ' Draw #' . str_pad(($checkDraw['result'][0]['bumperDrawNo'] ?? 0), 3, '0', STR_PAD_LEFT)) . ' on ' . date("dS M Y", strtotime($checkDraw['result'][0]['resultDate'])) . '. Good Luck!!!';

                    $emailContent = '<!DOCTYPE html
                    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title> Winner-email</title>
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
                            color: #171f4f;
                        }
                
                        .column-one h3 {
                            color: #171f4f;
                            font-family: Verdana, sans-serif !important;
                            font-size: 28px;
                            font-weight: 600;
                            margin: 14px 0 0 0;
                        }
                
                        .column-one p {
                            color: #171f4f;
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
                                                    <img src="' . constant('assetURL') . 'nationaldraw/1/ndLogo.png" style="border: 0px; margin:10px 0;  " width="50%">
                                                </center>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" style="padding: 0;">
                                                <center>
                                                
                                                    <img src="' . constant('assetURL') . 'nationaldraw/1/winnerImages.png" style="border-radius: 19px;" width="77%">
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
                                 <br>
                                 <p style="font-weight: 600!important;">Hi ' . ucwords(strtolower($winnerDetails['Name'])) . ',</p>
                                 <p style="font-size: 15px; font-weight: 400!important; margin: 16px 0; ">Congratulations on winning a prize of  ' . strval($winnerDetails['prize']) . ' Grams of Gold in <br>the ' . ($checkDraw['result'][0]['monthlyBumperName'] . ' Draw #' . str_pad(($checkDraw['result'][0]['bumperDrawNo'] ?? 0), 3, '0', STR_PAD_LEFT)) . ' held on ' . date("dS M Y", strtotime($checkDraw['result'][0]['resultDate'])) . '.
                                 </p>
                                 <p style="font-size: 16px; font-weight: 600!important; color:#088b42;">Your Winning Raffle ID</p>
                                 
                                </td>
                             </tr>
                            <tr>
                                <td class="column-one ">
                                    <p style="font-size: 18px;font-weight: 500!important;font-family: Verdana, sans-serif !important;margin:4px 0;/* border: 2px dotted green; */width: fit-content;margin: 5px auto;padding: 6px;border-radius: 5px;background: #171f4f;color: #fff;">
                                         #' . $winnerDetails['RaffleID'] . '
                                      </p>
                                </td>
                            </tr>                             
                            <tr>
                                <td class="column-one">
                                    <br>
                                    <img style="width: !important;margin-top: 10px;" src="' . constant('assetURL') . 'nationaldraw/1/ndFooter.png" width="84%">
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
                    $emailSubject = 'Congrats! YOU HAVE WON - NationalDraw';

                    if (substr($winnerDetails['Mobile'], 0, 3) == "971") {
                        $smslog = mysqli_query($con, "INSERT INTO `smslog` (`gateway`, `details`,`mobile`,`ip`,`datetime`,`status`, `smssendstatus`) VALUES ('', '$smsContent','" . $winnerDetails['Mobile'] . "','" . getUserIP() . "','$dubaidate_time','', '0');");
                    }

                    if ($winnerDetails['Email'] != '') {
                        $emailchack = explode('@', $winnerDetails['Email']);
                        if (strtolower($emailchack[1]) != "nationaldrawuae.com") {
                            $messages2 = mysqli_real_escape_string($con, $emailContent);
                            $emaillog = mysqli_query($con, "INSERT INTO `emaillog` (`details`,`subject`,`email`,`ip`,`datetime`,`status`) VALUES ('$messages2','$emailSubject','" . $winnerDetails['Email'] . "','" . getUserIP() . "','$dubaidate_time','0');");
                        }
                    }

                    $winnerArr = [
                        'draw_id' => $drawID,
                        'userid' => $winnerDetails['userID'],
                        'drawType' => 'monthlyBumper',
                        'winningDrawName' => $checkDraw['result'][0]['monthlyBumperName'] . ' #' . str_pad(($checkDraw['result'][0]['bumperDrawNo'] ?? 0), 3, '0', STR_PAD_LEFT),
                        'winRaffleId' => $winnerDetails['RaffleID'],
                        'fullName' => $winnerDetails['Name'],
                        'email' =>  $winnerDetails['Email'],
                        'mobile' =>  $winnerDetails['Mobile'],
                        'ticketID' => $winnerDetails['ticketID'],
                        'ticketReferenceID' => $winnerDetails['ticketReferenceID'],
                        'prize' => $winnerDetails['prize'],
                        'prize_amt' => $winnerDetails['prizeAmt'],
                        'smsContent' => $smsContent,
                        'emailSubject' => $emailSubject,
                        'emailContent' => mysqli_real_escape_string($con, $emailContent),
                        'smslog' => (isset($smslog) && $smslog) ? '1' : '0',
                        'emaillog' => (isset($emaillog) && $emaillog) ? '1' : '0',
                        'createdon' => $dubaidate_time,
                        'deletes' => '0'
                    ];
                    $winnerlistIns = insert($con, "winnerlist", "", $winnerArr, "", "", "");
                    if ($winnerlistIns['id'] != '') {
                        $drawUpdateArr['monthlyBumperStatus'] = 'Completed';
                    } else {
                        $result["type"] = "0";
                        $result["result"] = "The Winner Announcement Process failed!";
                        goto resultFI;
                    }
                }
            }
        }

        if ($weeklyBoosterStatus === 'Active') {

            $winnerDetails = $previewData['winweeklyBoosterIds'];
            // foreach ($winConRaffleIds as $key => $winnerDetails) {
            // $winnerDetails = $previewData['winBumperRaffleIds'];
            if ($winnerDetails['RaffleID'] != '') {
                $checkWinnerlist = select_query($con, "winnerlist", "", "`userid` = '" . $winnerDetails['userID'] . "' AND `draw_id` = $drawID AND `winRaffleId` = '" . $winnerDetails['RaffleID'] . "' AND `ticketReferenceID` = '" . $winnerDetails['ticketReferenceID'] . "' AND `ticketID` = '" . $winnerDetails['ticketID'] . "' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
                if ($checkWinnerlist['nr'] < 1) {

                    $smsContent = 'WAWW!!! Congratulation! You have won the of AED ' . $winnerDetails['prizeAmt'] . ' for ' . ($checkDraw['result'][0]['weeklyBoosterName'] . ' Draw #' . str_pad(($checkDraw['result'][0]['weeklyDrawNo'] ?? 0), 3, '0', STR_PAD_LEFT)) . ' on ' . date("dS M Y", strtotime($checkDraw['result'][0]['resultDate'])) . '. Good Luck!!!';


                    $emailContent = '<!DOCTYPE html
                    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title> Winner-email</title>
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
                            color: #171f4f;
                        }
                
                        .column-one h3 {
                            color: #171f4f;
                            font-family: Verdana, sans-serif !important;
                            font-size: 28px;
                            font-weight: 600;
                            margin: 14px 0 0 0;
                        }
                
                        .column-one p {
                            color: #171f4f;
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
                                                    <img src="' . constant('assetURL') . 'nationaldraw/1/ndLogo.png" style="border: 0px; margin:10px 0;  " width="50%">
                                                </center>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top" style="padding: 0;">
                                                <center>
                                                
                                                    <img src="' . constant('assetURL') . 'nationaldraw/1/winnerImages.png" style="border-radius: 19px;" width="77%">
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
                                 <br>
                                 <p style="font-weight: 600!important;">Hi ' . ucwords(strtolower($winnerDetails['Name'])) . ',</p>
                                 <p style="font-size: 15px; font-weight: 400!important; margin: 16px 0; ">Congratulations on winning a prize of  ' . strval($winnerDetails['prize']) . ' Grams of Gold in <br>the ' . ($checkDraw['result'][0]['weeklyBoosterName'] . ' Draw #' . str_pad(($checkDraw['result'][0]['weeklyDrawNo'] ?? 0), 3, '0', STR_PAD_LEFT)) . ' held on ' . date("dS M Y", strtotime($checkDraw['result'][0]['resultDate'])) . '.
                                 </p>
                                 <p style="font-size: 16px; font-weight: 600!important; color:#088b42;">Your Winning Raffle ID</p>
                                 
                                </td>
                             </tr>
                            <tr>
                                <td class="column-one ">
                                    <p style="font-size: 18px;font-weight: 500!important;font-family: Verdana, sans-serif !important;margin:4px 0;/* border: 2px dotted green; */width: fit-content;margin: 5px auto;padding: 6px;border-radius: 5px;background: #171f4f;color: #fff;">
                                         #' . $winnerDetails['RaffleID'] . '
                                      </p>
                                </td>
                            </tr>                             
                            <tr>
                                <td class="column-one">
                                    <br>
                                    <img style="width: !important;margin-top: 10px;" src="' . constant('assetURL') . 'nationaldraw/1/ndFooter.png" width="84%">
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
                    $emailSubject = 'Congrats! YOU HAVE WON - NationalDraw';


                    if (substr($winnerDetails['Mobile'], 0, 3) == "971") {
                        $smslog = mysqli_query($con, "INSERT INTO `smslog` (`gateway`, `details`,`mobile`,`ip`,`datetime`,`status`, `smssendstatus`) VALUES ('', '$smsContent','" . $winnerDetails['Mobile'] . "','" . getUserIP() . "','$dubaidate_time','', '0');");
                    }

                    if ($winnerDetails['Email'] != '') {
                        $emailchack = explode('@', $winnerDetails['Email']);
                        if (strtolower($emailchack[1]) != "nationaldrawuae.com") {
                            $messages2 = mysqli_real_escape_string($con, $emailContent);
                            $emaillog = mysqli_query($con, "INSERT INTO `emaillog` (`details`,`subject`,`email`,`ip`,`datetime`,`status`) VALUES ('$messages2','$emailSubject','" . $winnerDetails['Email'] . "','" . getUserIP() . "','$dubaidate_time','0');");
                        }
                    }

                    $winnerArr = [
                        'draw_id' => $drawID,
                        'userid' => $winnerDetails['userID'],
                        'drawType' => 'weeklyBooster',
                        'winningDrawName' => $checkDraw['result'][0]['weeklyBoosterName'] . ' #' . str_pad(($checkDraw['result'][0]['weeklyDrawNo'] ?? 0), 3, '0', STR_PAD_LEFT),
                        'winRaffleId' => $winnerDetails['RaffleID'],
                        'fullName' => $winnerDetails['Name'],
                        'email' =>  $winnerDetails['Email'],
                        'mobile' =>  $winnerDetails['Mobile'],
                        'ticketID' => $winnerDetails['ticketID'],
                        'ticketReferenceID' => $winnerDetails['ticketReferenceID'],
                        'prize' => $winnerDetails['prize'],
                        'prize_amt' => $winnerDetails['prizeAmt'],
                        'smsContent' => $smsContent,
                        'emailSubject' => $emailSubject,
                        'emailContent' => mysqli_real_escape_string($con, $emailContent),
                        'smslog' => (isset($smslog) && $smslog) ? '1' : '0',
                        'emaillog' => (isset($emaillog) && $emaillog) ? '1' : '0',
                        'createdon' => $dubaidate_time,
                        'deletes' => '0'
                    ];
                    $winnerlistIns = insert($con, "winnerlist", "", $winnerArr, "", "", "");
                    if ($winnerlistIns['id'] != '') {
                        $drawUpdateArr['weeklyBoosterStatus'] = 'Completed';
                    } else {
                        $result["type"] = "0";
                        $result["result"] = "The Winner Announcement Process failed!";
                        goto resultFI;
                    }
                }
            }
            // }
        }

        if (count($drawUpdateArr) < 1) {
            $result["type"] = "0";
            $result["result"] = "The Winner Announcement Process failed!";
            goto resultFI;
        }

        $updateDraw = update($con, "draw", "`id` = $drawID AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", $drawUpdateArr, "", "", "", "");
        $errors = $updateDraw['errors'];

        if ($errors != "") {
            $result["type"] = "0";
            $result["result"] = "The Winner Announcement Process failed!";
            goto resultFI;
        } else {
            $result['type'] = '1';
            $result['result'] = 'The Winner Announced successfully!';
            // $result['dailyThirllStatus'] = $checkDraw['result'][0]['dailyThirllStatus'] ?? '';
            // $result['weeklyBoosterStatus'] = $checkDraw['result'][0]['weeklyBoosterStatus'] ?? '';
            // $result['monthlyBumperStatus'] = $checkDraw['result'][0]['monthlyBumperStatus'] ?? '';
            // $result['winnerData'] = $winnerData;
            goto resultFI;
        }
    } else if ($method == 'resendWinner') {
        $drawID = $_REQUEST['drawID'];
        $RaffleID = $_REQUEST['RaffleID'];
        $ticketReferenceID = $_REQUEST['ticketReferenceID'];
        $ticketID = $_REQUEST['ticketID'];

        if ($drawID == '' || $drawID == null) {
            $result['type'] = '0';
            $result['result'] = 'The Draw ID Missing! Kindly Refresh and Try Again!';
            goto resultFI;
        }

        if ($RaffleID == '' || $RaffleID == null) {
            $result['type'] = '0';
            $result['result'] = 'The Raffle ID Missing! Kindly Refresh and Try Again!';
            goto resultFI;
        }

        if ($ticketReferenceID == '' || $ticketReferenceID == null) {
            $result['type'] = '0';
            $result['result'] = 'The Ticket Reference ID Missing! Kindly Refresh and Try Again!';
            goto resultFI;
        }

        if ($ticketID == '' || $ticketID == null) {
            $result['type'] = '0';
            $result['result'] = 'The Ticket ID Missing! Kindly Refresh and Try Again!';
            goto resultFI;
        }

        $checkWinnerlist = select_query($con, "winnerlist", "", "`draw_id` = $drawID AND `ticketReferenceID` LIKE '$ticketReferenceID' AND `ticketID` = $ticketID AND `winRaffleId` LIKE '$RaffleID' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
        if ($checkWinnerlist['nr'] < 1) {
            $result['type'] = '0';
            $result['result'] = 'The Track Not Found!';
            goto resultFI;
        }

        $emaillog = false;
        $smslog = false;

        if (substr($checkWinnerlist['result'][0]['mobile'], 0, 3) === 971) {
            $smslog = mysqli_query($con, "INSERT INTO `smslog` (`gateway`, `details`,`mobile`,`ip`,`datetime`,`status`, `smssendstatus`) VALUES ('', '" . $checkWinnerlist['result'][0]['smsContent'] . "','" . $checkWinnerlist['result'][0]['mobile'] . "','" . getUserIP() . "','$dubaidate_time','', '0');");
        }

        $emailchack = explode('@', $checkWinnerlist['result'][0]['email']);
        if (strtolower($emailchack[1]) != "nationaldrawuae.com") {
            $messages2 = mysqli_real_escape_string($con, $checkWinnerlist['result'][0]['emailContent']);
            $emaillog = mysqli_query($con, "INSERT INTO `emaillog` (`details`,`subject`,`email`,`ip`,`datetime`,`status`) VALUES ('$messages2','" . $checkWinnerlist['result'][0]['emailSubject'] . "','" . $checkWinnerlist['result'][0]['email'] . "','" . getUserIP() . "','$dubaidate_time','0');");
        }

        // var_dump((substr($checkWinnerlist['result'][0]['mobile'], 0, 3) === 971));
        // die;

        if ($smslog || $emaillog) {
            $result['type'] = '1';
            $result['result'] = 'The Email & SMS send successfully!';
            goto resultFI;
        } else {
            $result['type'] = '0';
            $result['result'] = 'The Email & SMS send process failed!';
            goto resultFI;
        }
    } else if ($method == 'updateGlodRate') {


        $drawID = $_REQUEST['drawID'];
        $todayGoldPrize = $_REQUEST['todayGoldPrize'];
        if ($drawID == '' || $drawID == null) {
            $result['type'] = '0';
            $result['result'] = 'The Draw ID Missing!';
            goto resultFI;
        }


        $checkDraw = select_query($con, "draw", "", "`id` = $drawID AND `deletes` = '0' AND `dailyThirllStatus` = 'Active' ORDER BY `id` DESC LIMIT 1", "", "");
        if ($checkDraw['nr'] < 1) {
            $result['type'] = '0';
            $result['result'] = 'The Active Draw Not Found!';
            goto resultFI;
        }

        if ($todayGoldPrize < 1) {
            $result['type'] = '0';
            $result['result'] = 'Please provide an update on the gold rate in AED. Please try again!';
            goto resultFI;
        }

        $updateArr = [
            'todayGoldPrize' =>  $todayGoldPrize
        ];

        $updateDraw = update($con, "draw", "`id` = $drawID AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", $updateArr, "", "", "", "");
        $errors = $updateDraw['errors'];
        if ($errors != "") {
            $result["type"] = "0";
            $result["result"] = "The Gold Rate Updated Process failed!";
            goto resultFI;
        } else {
            $result['type'] = '1';
            $result['result'] = 'The Gold Rate updated successfully!';
            goto resultFI;
        }
    } else if ($method == "seles_report") {

        $resultDate = $_POST['resultDate'];

        $getAll = $_POST['getAll'] ?? false;

        $selectQuery = "SELECT * FROM `draw` AS d WHERE d.`deletes` = '0'";

        $dateTime = DateTime::createFromFormat('Y-m-d', $resultDate);

        // var_dump($dateTime);die;


        if (!$getAll) {
            if ($resultDate != '' && $dateTime->format('Y-m-d') === $resultDate) {
                $selectQuery .= " AND d.`resultDate` = '$resultDate'";
            } else {
                $selectQuery .= " AND d.`resultDate` >= '" . date('Y-m-d', strtotime('-3 day')) . "'";
            }
        }
        // var_dump($selectQuery);
        // die;

        $runQuery = mysqli_query($con, $selectQuery);

        if ($runQuery && mysqli_num_rows($runQuery) > 0) {

            $result = [
                "type" => "1",
                "result" => [],
            ];

            while ($row = mysqli_fetch_assoc($runQuery)) {

                $gold_price = '';

                if ($row['dailyThirllStatus'] != 'Pending' && $row['weeklyBoosterStatus'] != 'Pending' && $row['monthlyBumperStatus'] != 'Pending') {

                    $gold_price = $row['dailyThirllPrice'] + $row['weeklyBoosterPrice'] + $row['monthlyBumperPrice'];
                } else if ($row['dailyThirllStatus'] != 'Pending' && $row['weeklyBoosterStatus'] != 'Pending') {

                    $gold_price = $row['dailyThirllPrice'] + $row['weeklyBoosterPrice'];
                } else if ($row['dailyThirllStatus'] != 'Pending' && $row['monthlyBumperStatus'] != 'Pending') {

                    $gold_price = $row['dailyThirllPrice'] + $row['monthlyBumperPrice'];
                } else {

                    $gold_price = $row['dailyThirllPrice'];
                }
                // var_dump($gold_price);die;

                $gold_rate = $gold_price * 300;

                // var_dump($row['resultDate'],$row['dailyThirllPrice']);die;

                $dailyactiveQuery = "SELECT COUNT(id) AS id_count FROM ndticket WHERE endDate >= '{$row['resultDate']}'";
                $dailyactiveResult = mysqli_query($con, $dailyactiveQuery);
                $dailyactiveCount = mysqli_fetch_assoc($dailyactiveResult)['id_count'];


                $vaqlue = $dailyactiveCount - $gold_rate;

                //  var_dump($vaqlue);die;


                $weeklyactiveQuery = "SELECT COUNT(id) AS id_count FROM ndticket WHERE endDate >= '{$row['resultDate']}' and grandtotal >= 7";
                $weeklyactiveResult = mysqli_query($con, $weeklyactiveQuery);
                $weeklyactiveCount = mysqli_fetch_assoc($weeklyactiveResult)['id_count'];

                $monthlyactiveQuery = "SELECT COUNT(id) AS id_count FROM ndticket WHERE endDate >= '{$row['resultDate']}' and grandtotal >= 30";
                $monthlyactiveResult = mysqli_query($con, $monthlyactiveQuery);
                $monthlyactiveCount = mysqli_fetch_assoc($monthlyactiveResult)['id_count'];

                $result['result'][] = [
                    "resultDate" => $row['resultDate'],
                    "Total_price" => $gold_price,
                    "Total_rete" => $gold_rate,
                    "value" => $vaqlue,
                    "dailyThirllStatus" => $row['dailyThirllStatus'],
                    "dailyThrillName" => $row['dailyThrillName'],
                    "resultdate" => $row['resultDate'],
                    "dailyactiveCount" => $dailyactiveCount,
                    "dailyDrawNo" => $row['dailyDrawNo'],
                    "weeklyBoosterStatus" => $row['weeklyBoosterStatus'],
                    "weeklyBoosterName" => $row['weeklyBoosterName'],
                    "weeklyDrawNo" => $row['weeklyDrawNo'],
                    "weeklyactiveCount" => $weeklyactiveCount,
                    "monthlyBumperStatus" => $row['monthlyBumperStatus'],
                    "monthlyBumperName" => $row['monthlyBumperName'],
                    "bumperDrawNo" => $row['bumperDrawNo'],
                    "monthlyactiveCount" => $monthlyactiveCount,
                ];
            }
            goto resultFI;
        }

        $result['type'] = '0';
        $result['result'] = [];
        goto resultFI;
    }

    // Return The Response
    resultFI:
    echo json_encode($result);
} catch (Exception $e) {
    $result = [
        'type' => '0',
        'result' => 'Process Failed!',
        'error' => ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]
    ];

    // Log
    error_log_new($con, getUserIP(), 'processFailed', $_SESSION['memid'], '', '', 'Catch Error', json_encode($result), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);

    echo json_encode($result);
}
