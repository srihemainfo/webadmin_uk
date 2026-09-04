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



        $selectQuery = "SELECT * FROM `draw` AS d WHERE d.`deletes` = '0' ORDER BY d.`id` ASC;";


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




                $gold_rate = $gold_price * 300;
                $tableName = "eligible_raffles_" . $row['id'];


                $tableCheckQuery = mysqli_query($con, "SHOW TABLES LIKE '$tableName'");


                if ($tableCheckQuery && mysqli_num_rows($tableCheckQuery) > 0) {




                    $dailyactiveQuery = mysqli_query($con, "SELECT `ticketReferenceID` FROM `$tableName` WHERE `is_thrill` = 'YES' GROUP BY `ticketReferenceID`;");
                } else {

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

                ];
            }
            goto resultFI;
        }

        $result['type'] = '0';
        $result['result'] = [];
        goto resultFI;
    } else   if ($method == "winner_list_services") {
        $result = [];
        $data = [];


        $setConfig = mysqli_query($con, "SET SESSION group_concat_max_len = 1000000");

        if (!$setConfig) {
            // die("Error setting group_concat_max_len: " . mysqli_error($con));

            $result['type'] = '1';
            $result['result'] = "Error setting group_concat_max_len: " . mysqli_error($con);
            goto resultFI;
        }

        // $getDraw = mysqli_query($con, "SELECT * FROM `draw` AS d WHERE d.`deletes` = '0' ORDER BY d.`id` ASC;");

        // $getAllDraw = mysqli_fetch_all($getDraw, MYSQLI_ASSOC);

        $selectQuery = "SELECT * FROM `draw` AS d WHERE d.`deletes` = '0' ORDER BY d.`id` ASC;";


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




                $gold_rate = $gold_price * 300;
                $tableName = "eligible_raffles_" . $row['id'];


                $tableCheckQuery = mysqli_query($con, "SHOW TABLES LIKE '$tableName'");


                if ($tableCheckQuery && mysqli_num_rows($tableCheckQuery) > 0 && $row['dailyThirllStatus'] === 'Completed') {


                    $gold_rate = $gold_price * $row['todayGoldPrize'];

                    // $dailyactiveQuery = mysqli_query($con, "SELECT `ticketReferenceID` FROM `$tableName` WHERE `is_thrill` = 'YES' GROUP BY `ticketReferenceID`;");


                    //     var_dump("SELECT h.ticketReferenceID FROM (SELECT f.ticketReferenceID FROM (SELECT 
                    //     referenceID AS ticketReferenceID,
                    //     userId AS userid,
                    //     ticketNo,
                    //     endDate,
                    //     JSON_UNQUOTE(JSON_EXTRACT(raffleIds, CONCAT('$[', numbers.n, ']'))) AS raffleid,
                    //     is_thrill,
                    //     is_weekly,
                    //     is_bumper
                    // FROM 
                    //     ndticket
                    // JOIN
                    //     (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL
                    //     SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL
                    //     SELECT 10 UNION ALL SELECT 11) AS numbers
                    // ON
                    //     JSON_VALID(raffleIds) = 1
                    // WHERE 
                    //     userId != 0 
                    //     AND purchaseDatetime < '{$row['resultDate']}'
                    //     AND STR_TO_DATE(endDate, '%Y-%m-%d') >= '{$row['resultDate']}' 
                    //     AND deletes = '0'
                    //     AND JSON_UNQUOTE(JSON_EXTRACT(raffleIds, CONCAT('$[', numbers.n, ']'))) IS NOT NULL   
                    // ORDER BY id DESC) AS f GROUP BY f.ticketReferenceID

                    // UNION

                    // SELECT ticketReferenceID FROM `invoice` WHERE deletes = '0' AND renewalStatus LIKE 'RENEWAL'  AND STR_TO_DATE(endDate, '%Y-%m-%d') >= '{$row['resultDate']}'  GROUP BY ticketReferenceID)  as h GROUP BY h.ticketReferenceID;");die;

                    $dailyactiveQuery = mysqli_query($con, "SELECT h.ticketReferenceID FROM (SELECT f.ticketReferenceID FROM (SELECT 
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
                ORDER BY id DESC) AS f GROUP BY f.ticketReferenceID

                UNION

                SELECT ticketReferenceID FROM `invoice` WHERE deletes = '0' AND renewalStatus LIKE 'RENEWAL'  AND STR_TO_DATE(endDate, '%Y-%m-%d') >= '{$row['resultDate']}'  GROUP BY ticketReferenceID)  as h GROUP BY h.ticketReferenceID;");
                } else {

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



                $result['data'][] = [
                    "resultDate" => $row['resultDate'],
                    // "totalGoldPrize" => $gold_price,
                    "totalGoldPrize" => $gold_rate,


                    "totalPL" => $vaqlue,


                    "dailyThirllStatus" => $row['dailyThirllStatus'],

                    "dailyThrillName" => $row['dailyThrillName'],
                    // "resultdate" => $row['resultDate'],
                    "saleCount" => $dailyactiveCount,
                    "dailyDrawNo" => $row['dailyDrawNo'],

                    "totalGold" => $gold_price

                ];
            }

            $result['type'] = '1';
            $result['result'] = 'Data Collected Successuffly';
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
