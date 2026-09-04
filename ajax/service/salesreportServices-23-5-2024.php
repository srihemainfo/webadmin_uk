<?php
include '../../include/shi-config.php';
include '../../include/functions.php';

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

try {

    // function getWinnerInfo($con, $winnerRaffleID, $prize, $saleDate, $ticketCon, $todayGoldPrize)
    // {
    //     $raffleCheckQuery = "SELECT n.*, u.id AS 'userID', u.email, u.name, u.lname, u.mobile FROM ndticket n LEFT JOIN user_register u ON u.id = n.userId WHERE JSON_CONTAINS(n.raffleIds, '\"$winnerRaffleID\"') AND n.deletes = '0' AND STR_TO_DATE(n.endDate, '%Y-%m-%d') >= '" . $saleDate . "' AND n.userId != 0 AND n.oldTicketID = 0  $ticketCon ORDER BY n.`id` DESC LIMIT 1;";
    //     $runQuery = mysqli_query($con, $raffleCheckQuery);
    //     if ($runQuery && mysqli_num_rows($runQuery) > 0) {
    //         $row = mysqli_fetch_assoc($runQuery);
    //         return [
    //             "userID" =>  $row['userID'],
    //             "Name" =>  $row['name'] . ' ' . ($row['lname'] ?? ''),
    //             "Email" => $row['email'],
    //             "Mobile" => $row['mobile'],
    //             "RaffleID" => $winnerRaffleID,
    //             "prize" => $prize,
    //             'ticketReferenceID' => $row['referenceID'],
    //             'ticketID' => $row['id'],
    //             "prizeAmt" => $prize * $todayGoldPrize,
    //         ];
    //     }
    //     return null;
    // }

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

    if ($method == "seles_report") {

        // $resultDate = $_POST['resultDate'];

        // $getAll = $_POST['getAll'] ?? false;

        $selectQuery = "SELECT * FROM `draw` AS d WHERE d.`deletes` = '0' ORDER BY d.`id` ASC;";

        // $dateTime = date('Y-m-d', strtotime($resultDate));

        // if (!$getAll) {
        //     if ($resultDate != '' && date('Y-m-d', strtotime($resultDate)) === $resultDate) {
        //         $selectQuery .= " AND d.`resultDate` = '$resultDate'";
        //     } else {
        //         $selectQuery .= " AND d.`resultDate` >= '" . date('Y-m-d', strtotime('-3 day')) . "'";
        //     }
        // }
        // var_dump($selectQuery);
        // die;

        $runQuery = mysqli_query($con, $selectQuery);

        if ($runQuery && mysqli_num_rows($runQuery) > 0) {

            $result = [
                "type" => "1",
                "result" => [],
            ];

            while ($row = mysqli_fetch_assoc($runQuery)) {

                $gold_price = 0;

                if ($row['dailyThirllStatus'] != 'Pending') {
                    $gold_price += $row['dailyThirllPrice'];
                }

                if ($row['weeklyBoosterStatus'] != 'Pending') {
                    $gold_price +=  $row['weeklyBoosterPrice'];
                }

                if ($row['monthlyBumperStatus'] != 'Pending') {
                    $gold_price +=  $row['monthlyBumperPrice'];
                }


                // if ($row['dailyThirllStatus'] != 'Pending' && $row['weeklyBoosterStatus'] != 'Pending' && $row['monthlyBumperStatus'] != 'Pending') {

                //     $gold_price = $row['dailyThirllPrice'] + $row['weeklyBoosterPrice'] + $row['monthlyBumperPrice'];
                // } else if ($row['dailyThirllStatus'] != 'Pending' && $row['weeklyBoosterStatus'] != 'Pending') {

                //     $gold_price = $row['dailyThirllPrice'] + $row['weeklyBoosterPrice'];
                // } else if ($row['dailyThirllStatus'] != 'Pending' && $row['monthlyBumperStatus'] != 'Pending') {

                //     $gold_price = $row['dailyThirllPrice'] + $row['monthlyBumperPrice'];
                // } else {

                //     $gold_price = $row['dailyThirllPrice'];
                // }
                // var_dump($gold_price);die;

                $gold_rate = $gold_price * 300;
                $tableName = "eligible_raffles_" . $row['id'];

                // var_dump("SHOW TABLES LIKE eligible_raffles_" . $row['id']);
                // die;
                // $dailyactiveQuery = "SELECT COUNT(id) AS id_count FROM ndticket WHERE endDate >= '{$row['resultDate']}'";
                $tableCheckQuery = mysqli_query($con, "SHOW TABLES LIKE '$tableName'");


                if ($tableCheckQuery && mysqli_num_rows($tableCheckQuery) > 0) {


                    // var_dump("SELECT 'ticketReferenceID' FROM '$tableName' WHERE `is_thrill` = 'YES' GROUP BY `ticketReferenceID`;");die;

                    $dailyactiveQuery = mysqli_query($con, "SELECT `ticketReferenceID` FROM `$tableName` WHERE `is_thrill` = 'YES' GROUP BY `ticketReferenceID`;");
                }
                // var_dump(mysqli_num_rows($dailyactiveQuery));
                // die;
                // if (mysqli_num_rows($dailyactiveQuery) < 1) {
                else {

                    $dailyactiveQuery = mysqli_query($con, "SELECT  g.ticketNo AS 'ticketNo',
                                       COUNT(g.ticketNo) AS 'totalTickets' FROM (SELECT 
                                        referenceID AS ticketReferenceID,
                                        userId AS userid,
                                        ticketNo,
                                        endDate,
                                        JSON_UNQUOTE(JSON_EXTRACT(raffleIds, CONCAT('$[', numbers.n, ']'))) AS raffleid,
                                        is_thrill,
                                        is_weekly,
                                        is_bumper
                                    FROM 
                                        ndticket
                                    JOIN
                                        (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL
                                        SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL
                                        SELECT 10 UNION ALL SELECT 11) AS numbers
                                    ON
                                        JSON_VALID(raffleIds) = 1
                                    WHERE 
                                        userId != 0 
                                        AND purchaseDatetime < '{$row['resultDate']}' 
                                        AND STR_TO_DATE(endDate, '%Y-%m-%d') >= '{$row['resultDate']}' 
                                        AND deletes = '0'
                                        AND JSON_UNQUOTE(JSON_EXTRACT(raffleIds, CONCAT('$[', numbers.n, ']'))) IS NOT NULL   
                                    ORDER BY id DESC) AS g WHERE g.is_thrill = 'YES' GROUP BY g.ticketNo;");
                }

                $dailyactiveCount = mysqli_num_rows($dailyactiveQuery);










                $vaqlue = $dailyactiveCount - $gold_rate;

                //  var_dump($dailyactiveCount);die;


                // $weeklyactiveQuery = "SELECT COUNT(id) AS id_count FROM ndticket WHERE endDate >= '{$row['resultDate']}' and grandtotal >= 7";
                // $weeklyactiveResult = mysqli_query($con, $weeklyactiveQuery);
                // $weeklyactiveCount = mysqli_fetch_assoc($weeklyactiveResult)['id_count'];

                // $monthlyactiveQuery = "SELECT COUNT(id) AS id_count FROM ndticket WHERE endDate >= '{$row['resultDate']}' and grandtotal >= 30";
                // $monthlyactiveResult = mysqli_query($con, $monthlyactiveQuery);
                // $monthlyactiveCount = mysqli_fetch_assoc($monthlyactiveResult)['id_count'];

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

                    "dailyGold" => $gold_price
                    // "weeklyBoosterStatus" => $row['weeklyBoosterStatus'],
                    // "weeklyBoosterName" => $row['weeklyBoosterName'],
                    // "weeklyDrawNo" => $row['weeklyDrawNo'],
                    // "weeklyactiveCount" => $weeklyactiveCount,
                    // "monthlyBumperStatus" => $row['monthlyBumperStatus'],
                    // "monthlyBumperName" => $row['monthlyBumperName'],
                    // "bumperDrawNo" => $row['bumperDrawNo'],
                    // "monthlyactiveCount" => $monthlyactiveCount,
                ];
            }
            goto resultFI;
        }

        $result['type'] = '0';
        $result['result'] = [];
        goto resultFI;
    } else {
        $result['type'] = '0';
        $result['result'] = 'Method Not Found!';
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
