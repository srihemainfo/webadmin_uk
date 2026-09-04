<?php




include '../../include/shi-config.php';

include '../../include/functions.php';

include '../../include/payment-config.php';

include '../../include/Crypto.php';





$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";

$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";

$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";

$result = array();



if ($method == "searchTicket") {

    $response = array();



    $contype = "`raffle_id` LIKE '%" . $data . "%' AND `draw_id` = " . $_REQUEST['draw_id'] . " AND ";

    $da = '';



    $getData = select_query($con, "ticket_lines", "", " $contype `deletes`='0'  ORDER BY id", "", "");



    if ($getData['nr'] > 0) {

        foreach ($getData['result'] as $key => $value) {

            $response[] = array("value" => $value['raffle_id'], "label" => $value['raffle_id'], "id" => $value['id']);
        }
    }

    if (count($response) == 0) {

        $response[] = array("value" => "", "label" => "No Ticket Found");
    }



    echo json_encode($response);
} elseif ($method == "ticketLineDetails") {

    $ticket_line_id = $_REQUEST['ticket_line_id'];



    $selectQ = "SELECT ticket_lines.*,user_register.name,user_register.mobile,user_register.email,product.rate  FROM `ticket_lines` JOIN user_register ON user_register.id = ticket_lines.user_id JOIN product ON product.id = ticket_lines.product_id WHERE ticket_lines.id = '" . $ticket_line_id . "' AND ticket_lines.deletes='0' ";



    $resQuery = mysqli_query($con, $selectQ);

    $NumRows = mysqli_num_rows($resQuery);



    if ($NumRows > 0) {

        $fetchArray = mysqli_fetch_array($resQuery);

        // print_r($fetchArray);



        $result = ["cusname" => $fetchArray['name'], "mobile" => $fetchArray['mobile'], "email" => $fetchArray['email'], "proamt" => $fetchArray['rate'], "RaffleID" => $fetchArray['raffle_id'], "purdate" => date("d-M-Y g:i a", strtotime($fetchArray['createdon']))];
    }

    echo json_encode($result);
} elseif ($method == "winner_list") {


    $rafflePrizeOne = BlockSQLInjection($_REQUEST["rafflePrizeOne"]);
    $rafflePrizeTwo = BlockSQLInjection($_REQUEST["rafflePrizeTwo"]);

    $rafflePrizeThree = BlockSQLInjection($_REQUEST["rafflePrizeThree"]);


    $drawid = BlockSQLInjection($_REQUEST["drawid"]);
    $RejectReason = BlockSQLInjection($_POST["deletemessage"]);

    // $drawid = $_REQUEST['drawid'];


    $currentUser = $_SESSION['memid'];
    // $RejectReason = $_POST['deletemessage'];

    if ($currentUser == '' || $currentUser == null || $currentUser == 'null') {
        $result["type"] = "0";
        $result["result"] = "Login Required!";
        goto returnReturn;
    }

    if ($RejectReason == '') {
        $result['type'] = '0';
        $result['result'] = 'Kindly enter the reject reason!';
        goto returnReturn;
    }


    if ($rafflePrizeOne == '') {

        $result["type"] = "0";

        $result["result"] = "Please Enter Raffle Id";

        $result['input'] = 'rafflePrizeOne';
    } elseif ($rafflePrizeTwo == '') {

        $result["type"] = "0";

        $result["result"] = "Please Enter Raffle Id";

        $result['input'] = 'rafflePrizeTwo';
    } elseif ($rafflePrizeThree == '') {

        $result["type"] = "0";

        $result["result"] = "Please Enter Raffle Id";

        $result['input'] = 'rafflePrizeThree';
    } elseif ($rafflePrizeOne == $rafflePrizeTwo) {

        $result["type"] = "0";

        $result["result"] = "RaffleId 1 & 2 are same";

        $result['input'] = 'rafflePrizeTwo';
    } elseif ($rafflePrizeTwo == $rafflePrizeThree) {

        $result["type"] = "0";

        $result["result"] = "RaffleId 2 & 3 are same";

        $result['input'] = 'rafflePrizeThree';
    } elseif ($rafflePrizeOne == $rafflePrizeThree) {

        $result["type"] = "0";

        $result["result"] = "RaffleId 1 & 3 are same";

        $result['input'] = 'rafflePrizeThree';
    } else {



        $contype = "`raffle_id` = '" . $rafflePrizeOne . "' AND `draw_id` = " . $drawid . " AND ";

        $getFirstRaffleData = select_query($con, "ticket_lines", "", " $contype `deletes`='0'  ORDER BY id", "", "");



        $contype1 = "`raffle_id` = '" . $rafflePrizeTwo . "' AND `draw_id` = " . $drawid . " AND ";

        $getSecondRaffleData = select_query($con, "ticket_lines", "", " $contype1 `deletes`='0'  ORDER BY id", "", "");



        $contype2 = "`raffle_id` = '" . $rafflePrizeThree . "' AND `draw_id` = " . $drawid . " AND ";

        $getThirdRaffleData = select_query($con, "ticket_lines", "", " $contype2 `deletes`='0'  ORDER BY id", "", "");



        if ($getFirstRaffleData['nr'] == 0) {

            $result["type"] = "0";

            $result["result"] = "Incorrect Raffle ID";

            $result['input'] = 'rafflePrizeOne';
        } elseif ($getSecondRaffleData['nr'] == 0) {

            $result["type"] = "0";

            $result["result"] = "Incorrect Raffle ID";

            $result['input'] = 'rafflePrizeTwo';
        } elseif ($getThirdRaffleData['nr'] == 0) {

            $result["type"] = "0";

            $result["result"] = "Incorrect Raffle ID";

            $result['input'] = 'rafflePrizeThree';
        } else {



            $draw_arr = array("raffleprizefirst" => $rafflePrizeOne, "raffleprizesecond" => $rafflePrizeTwo, "raffleprizethird" => $rafflePrizeThree, 'just3_raffle_review' => 'Submitted');



            $draw_update = update($con, "draw", "`id` = '$drawid' and `deletes`='0'", $draw_arr, "", "", "", "");



            // Log
            $ins_arr = ["messaged_by" => $currentUser, 'drawgroup' => 'JUST3RAFFLE', 'draw_status' => 'Submitted', 'message' => $RejectReason, 'drawid' => $drawid, 'ip' => getUserIP(), 'createdon' => $dubaidate_time];
            $draw_reviewer_log_ins = insert($con, "draw_reviewer_log", "", $ins_arr, "", "", "");


            $result["type"] = "1";

            $result["id"] = $drawid;

            $result["result"] = "Success!";

            $result["reload"] = "1";
        }
    }

    returnReturn:
    echo json_encode($result);
} elseif ($method == "check_winner_list") {


    $rafflePrizeOne = strtoupper(BlockSQLInjection($_REQUEST['rafflePrizeOne']));
    $rafflePrizeTwo = strtoupper(BlockSQLInjection($_REQUEST['rafflePrizeTwo']));
    $rafflePrizeThree = strtoupper(BlockSQLInjection($_REQUEST['rafflePrizeThree']));
    $drawid = BlockSQLInjection($_REQUEST['drawid']);






    if ($rafflePrizeOne == $rafflePrizeTwo && ($rafflePrizeOne != "" && $rafflePrizeTwo != "")) {

        $result["type"] = "0";
        $result["result"] = "RaffleId 1 & 2 are same";
        $result['input'] = 'rafflePrizeTwo';
    } elseif ($rafflePrizeTwo == $rafflePrizeThree && ($rafflePrizeTwo != "" && $rafflePrizeThree != "")) {

        $result["type"] = "0";

        $result["result"] = "RaffleId 2 & 3 are same";
        $result['input'] = 'rafflePrizeThree';
    } elseif ($rafflePrizeOne == $rafflePrizeThree && ($rafflePrizeOne != "" && $rafflePrizeThree != "")) {

        $result["type"] = "0";

        $result["result"] = "RaffleId 1 & 3 are same";
        $result['input'] = 'rafflePrizeThree';
    } else {



        $contype = "`raffle_id` LIKE '" . $rafflePrizeOne . "%' AND `draw_id` = " . $drawid . " AND ";
        $getFirstRaffleData = select_query($con, "ticket_lines", "", " $contype `deletes`='0'  ORDER BY `id` DESC LIMIT 1", "", "");

        $contype1 = "`raffle_id` LIKE '" . $rafflePrizeTwo . "%' AND `draw_id` = " . $drawid . " AND ";
        $getSecondRaffleData = select_query($con, "ticket_lines", "", " $contype1 `deletes`='0'  ORDER BY `id` DESC LIMIT 1", "", "");

        $contype2 = "`raffle_id` LIKE '" . $rafflePrizeThree . "%' AND `draw_id` = " . $drawid . " AND ";
        $getThirdRaffleData = select_query($con, "ticket_lines", "", " $contype2 `deletes`='0'  ORDER BY `id` DESC LIMIT 1", "", "");



        if ($getFirstRaffleData['nr'] == 0 && $rafflePrizeOne != "") {

            $result["type"] = "0";

            $result["result"] = "Incorrect Raffle ID";

            $result['input'] = 'rafflePrizeOne';
        } elseif ($getSecondRaffleData['nr'] == 0 && $rafflePrizeTwo != "") {

            $result["type"] = "0";

            $result["result"] = "Incorrect Raffle ID";

            $result['input'] = 'rafflePrizeTwo';
        } elseif ($getThirdRaffleData['nr'] == 0 && $rafflePrizeThree != "") {

            $result["type"] = "0";

            $result["result"] = "Incorrect Raffle ID";

            $result['input'] = 'rafflePrizeThree';
        } else {

            $result["type"] = "1";

            $result["id"] = "0";

            $result["result"] = "Success!";
        }
    }

    echo json_encode($result);
} else if ($method == "list_draw") {





    $sql = "SELECT * FROM `draw` WHERE `raffle_status` = 'Completed' AND `deletes` = '0'";

    $run = mysqli_query($con, $sql);

    if (mysqli_num_rows($run) > 0) {



        $i = 1;



        while ($row = mysqli_fetch_array($run)) {



            $drawid = $row['id'];



            $action = '';



            $action .= '<a href="' . $adminurl . 'rafflewinners/list/' . $row['id'] . '" class="btn text-success btn-sm" data-bs-toggle="model" data-bs-original-title="View Event"><span class="fa fa-eye" style="font-size: 20px !important;"></span></a>';








            $result[] = array("drawno" => $row['draw_no'], "id" => $row['id'], "nid" => str_pad($row['id'], 7, "0", STR_PAD_LEFT), "name" => $row['name'], "ticket_start_datetime" => date_format(date_create($row['ticket_start_datetime']), "d-m-Y"), "ticket_end_datetime" => date_format(date_create($row['ticket_end_datetime']), "d-m-Y"), "result_datetime" => date_format(date_create($row['result_datetime']), "d-m-Y h-i-A"), "status" => $row['status'], "output" => $action);



            $i++;
        }
    }

    echo json_encode($result);
} else if ($method == 'notificationHistory') {
    $result = [];
    $currentUser = $_SESSION['memid'];
    $drawid = BlockSQLInjection($_POST["drawid"]);

    // $drawID = $_POST['drawID'];
    if ($currentUser == '' || $currentUser == null || $currentUser == 'null') {
        $result["type"] = "0";
        $result["result"] = "Login Required!";
        goto Resuten;
    }


    if ($drawID == '' || $drawID == null || $drawID == 'null') {
        $result["type"] = "0";
        $result["result"] = "The Draw ID has been missing!";
        goto Resuten;
    }



    $query = "SELECT d.id, d.ip, d.drawid, d.message, d.messaged_by, d.drawgroup, d.draw_status, d.createdon, u.name FROM `draw_reviewer_log` d
    INNER JOIN user_register u ON u.id = d.messaged_by 
    WHERE drawid = $drawID AND `drawgroup` = 'JUST3RAFFLE'
    ORDER BY `d`.`createdon` DESC;";
    $runQuery = mysqli_query($con, $query);
    $data = mysqli_fetch_all($runQuery, MYSQLI_ASSOC);

    $result["type"] = "1";
    $result["result"] =  $data;


    Resuten:
    echo json_encode($result);
}
