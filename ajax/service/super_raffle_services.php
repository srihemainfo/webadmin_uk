<?php

/**

 * Date             Developer        Modification

 * 20-07-2023       Surya         Raffle Draw Winner User Selection & Preview
 * 21-09-2023       Surya         Draw insert updated in pre_draw


 * */

 function encodeValue($value) {
    return base64_encode($value);
}

 

include '../../include/shi-config.php';

include '../../include/functions.php';

include '../../include/payment-config.php';

include '../../include/Crypto.php';





$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";

$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";

$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";

$result = array();

if ($method == "check_winner_list1") {


    $rafflePrizeTwo = strtoupper($_REQUEST['rafflePrizeTwo']);


    $drawid = $_REQUEST['drawid'];

    // var_dump($drawid);die;


    if ($rafflePrizeTwo == ' ') {

        $result["type"] = "0";
        $result["result"] = "this empty";
        $result['input'] = 'rafflePrizeTwo';
    } else {

        $Ticket_lines = select_query($rcon, "superraffledraw", "", "`draw_no`='$draw_new_id' and `deletes`='0'", "", "");

        if ($Ticket_lines['nr'] > 0) {

            foreach ($Ticket_lines['result'] as $key => $value) {

                $start = $value['ticket_start_datetime'];

                $end = $value['ticket_end_datetime'];

                if ($start != '' && $end != '') {
                    $formattedStart = date('Y-m-d H:i:s', strtotime($start));
                    $formattedEnd = date('Y-m-d H:i:s', strtotime($end));

                    // Create the date filter
                    $datefilter1 = "`createdon` BETWEEN '$formattedStart' AND '$formattedEnd' AND";
                } else {

                    $datefilter1 = "";
                }
            }
        }




        // $contype1 = "`raffle_id` LIKE '" . $rafflePrizeTwo . "%' AND  " . $datefilter1 . " AND ";


        $getSecondRaffleData = select_query($con, "ticket_lines", "", " $datefilter1 `deletes`='0'  ORDER BY `id` DESC LIMIT 1", "", "");

        if ($getSecondRaffleData['nr'] == 0 && $rafflePrizeTwo != "") {

            $result["type"] = "0";

            $result["result"] = "Incorrect Raffle ID";

            $result['input'] = 'rafflePrizeTwo';
        } else {

            $result["type"] = "1";

            $result["id"] = "0";

            $result["result"] = "Success!";
        }
    }

    echo json_encode($result);
}if ($method == "check_winner_list") {
    $rafflePrizeOne = strtoupper($_REQUEST['rafflePrizeOne']);
    $drawid = $_REQUEST['drawid'];
    $rafflePrizeOne = trim($rafflePrizeOne);
 
    if (empty($rafflePrizeOne)) {
        $result["type"] = "0";
        $result["result"] = "Raffle ID cannot be empty";
        $result['input'] = 'rafflePrizeOne';
    } else {
        $validTypes = array('O', 'A', 'C', 'B', 'T', 'P','K');
        $inputType = strtoupper(substr($rafflePrizeOne, 0, 1));
       
        if (!in_array($inputType, $validTypes)) {
            $result["type"] = "0";
            $result["result"] = "Incorrect Raffle ID";
            $result['input'] = 'rafflePrizeOne';
        }else {
            $typeResult = select_query($con, "ticket_lines", "", "`type` LIKE '$inputType%' ORDER BY `id` DESC LIMIT 1", "", "");
 
            if (empty($typeResult)) {
                $result["type"] = "0";
                $result["result"] = "Incorrect Raffle ID";
                $result['input'] = 'rafflePrizeOne';
            } else {
                $Ticket_lines = select_query($con, "ticket_lines", "", "`raffle_id` LIKE '$rafflePrizeOne%'  ORDER BY `id` DESC LIMIT 1", "", "");
 
                if ($Ticket_lines['nr'] > 0) {
                    foreach ($Ticket_lines['result'] as $key => $value) {
                        $start = $value['createdon'];
                        if ($start) {
                            $formattedStart = date('Y-m-d H:i:s', strtotime($start));
                            $datefilter1 = "`ticket_start_datetime` <= '$start' AND ticket_end_datetime > '$start' AND status = 'Active'";
                        } else {
                            $datefilter1 = "";
                        }
                    }
                }
 
                $consolidated_Report = select_query($con, "superraffledraw", "", "$datefilter1 ORDER BY `id` DESC LIMIT 1", "", "");
                $drawid =  $consolidated_Report['result'][0]['id'];
//  var_dump($consolidated_Report);die;
                // $contype = "`raffle_id` LIKE '$rafflePrizeOne%' AND `createdon` = '$start' ";
                // $getFirstRaffleData = select_query($con, "ticket_lines", "", "$contype AND `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
 
                if ($consolidated_Report['nr'] == 0 ) {
                    $result["type"] = "0";
                    $result["result"] = "Incorrect Raffle ID";
                    $result['input'] = 'rafflePrizeOne';
                } else {
                    $result["type"] = "1";
                    $result["id"] = "0";
                    $result["result"] = "Success!";
                }
            }
        }
    }
    echo json_encode($result);
} else if ($method == "winner_list") {

    $rafflePrizeOne = $_REQUEST['rafflePrizeOne'];

    $drawid = $_REQUEST['drawid'];

    $Ticket_lines = select_query($con, "ticket_lines", "", "`raffle_id` = '$rafflePrizeOne' ORDER BY `id` DESC LIMIT 1", "", "");
    if ($Ticket_lines['nr'] > 0) {

        foreach ($Ticket_lines['result'] as $key => $value) {

            $start = $value['createdon'];
            if ($start) {
                $formattedStart = date('Y-m-d H:i:s', strtotime($start));

                // Create the date filter
                $datefilter1 = "`ticket_start_datetime` <= ' $start' AND ticket_end_datetime > ' $start'  ";
                // ticket_start_datetime <= ' $start' AND ticket_end_datetime > ' $start' AND

            } else {

                $datefilter1 = "";
            }
        }
    }
    $consolidated_Report = select_query($con, "superraffledraw", "", " $datefilter1 ORDER BY `id` DESC LIMIT 1", "", "");

    //$consolidated_Report = select_query($con, "superraffledraw", "", "`draw_no` = '$drawid' ORDER BY `id` DESC LIMIT 1", "", "");



    $drawid =  $consolidated_Report['result'][0]['id'];
    $draw_no =  $consolidated_Report['result'][0]['draw_no'];

    if ($rafflePrizeOne == '') {

        $result["type"] = "0";

        $result["result"] = "Please Enter Raffle Id";

        $result['input'] = 'rafflePrizeOne';
    } else {
        $contype = "`raffle_id` LIKE '" . $rafflePrizeOne . "%' AND `createdon` = '" . $start . "'";

        $getFirstRaffleData = select_query($con, "ticket_lines", "", "$contype AND `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");

        if ($consolidated_Report['nr'] == 0) {

            $result["type"] = "0";

            $result["result"] = "Incorrect Raffle ID";

            $result['input'] = 'rafflePrizeOne';
        }
        if ($consolidated_Report['nr'] == 0 && $rafflePrizeOne != "") {

            $result["type"] = "0";

            $result["result"] = "Incorrect Raffle ID";

            $result['input'] = 'rafflePrizeOne';
        } else {

            $qry = select_query($con, "pre_draw", "", "`draw_no` = '$drawid' and `deletes`='0' ORDER BY `id` DESC LIMIT 1;", "", "");
            if ($qry['nr'] < 1) {
                $ins = mysqli_query($con, "INSERT INTO pre_draw (SELECT * FROM `draw` WHERE `draw_no` = $draw_no);");
            }

            $draw_arr = array("raffleprizefirst" => $rafflePrizeOne);

            $result["type"] = "1";

            $result["id"] = $drawid;

            $result["rafflePrizeOne"] = $rafflePrizeOne;

            $result["result"] = "Success!";
        }
    }

    echo json_encode($result);
} else if ($method  === 'getTabel_raffleWinner_table') {
    $result = [];

    $drawID= $_REQUEST['id'];

    $rafflePrizeOne = $_REQUEST['rafflePrizeOne'];
    $id = $_REQUEST['id'];
    if ($rafflePrizeOne) {
        $superraffle = select_query($con, "superraffledraw", "", "`id`= '$id' AND `deletes` = '0' ", "", "");
        if (!empty($superraffle['result'])) {
            $result_datetime = $superraffle['result'][0]['result_datetime'];
            $draw_no = $superraffle['result'][0]['draw_no'];
            $permitno = $superraffle['result'][0]['permitno'];
            $prize = $superraffle['result'][0]['wonprize'];
        }
        $Ticket_lines = select_query($con, "ticket_lines", "", "`raffle_id`= '$rafflePrizeOne' AND `deletes` = '0' ", "", "");
        if (!empty($Ticket_lines['result'])) {
            $userid = $Ticket_lines['result'][0]['user_id'];
            $productid = $Ticket_lines['result'][0]['product_id'];
            $type = $Ticket_lines['result'][0]['type'];
        }

        $user_register = select_query($con, "user_register", "", "`id`= '$userid' AND `deletes` = '0' ", "", "");

        if (!empty($user_register['result'])) {
            $name = $user_register['result'][0]['name'];
            $lname = $user_register['result'][0]['lname'];
            $email = $user_register['result'][0]['email'];
            $mobile = $user_register['result'][0]['mobile'];
        }

        $product = select_query($con, "product", "", "`id`= '$productid' AND `deletes` = '0' ", "", "");
        if (!empty($product['result'])) {
            $productname = $product['result'][0]['name'];
            $rate = $product['result'][0]['rate'];
            $superraffleprize = $product['result'][0]['super_raffle_prize'];
        }
        $dataHtml = '<table style="text-align: center;" class="table-dark table-striped" id="rebreport">
        <thead>
            <tr>
                <th rowspan="3">Product Category Number</th>
                <th colspan="1">Prize</th>
                <th colspan="1">Total</th>
            </tr>
            
        </thead>
        <tbody>';

        $product = select_query($con, "product", "", "`deletes`='0'", "", "");

        // $drawID =328;

        // var_dump($drawID);die;

        if ($product['nr'] > 0) {
            foreach ($product['result'] as $key => $value) {
                $rowCount = 0;
                $rowAmt = 0;
                $title = 'Category AED ' . (int) $value['rate'];

                $dataHtml .= '<tr>';
                $dataHtml .= '<tr><td><a href="javascript:void(0);" onclick="getWinnerList(' . (int) $value['id'] . ', ' . $drawID . ', ' . "'$rafflePrizeOne'" . ', ' . "'$title'" . ', ' . "'$baseurl'" . ' )">Category AED ' . (int) $value['rate'] . '</a></td>';

                // var_dump('<a href="javascript:void(0);" onclick="getWinnerList(' . (int) $value['id'] . ', ' . $drawID . ', ' . "'$title'" . ', ' . "'$baseurl'" . ' )">Category AED ' . (int) $value['rate'] . '</a>');die;

                if ($productid == $value['id']) {
                    $dataHtml .= '<td>' . number_format($superraffleprize, 2) . '</td>';
                    $dataHtml .= '<td>' . number_format($superraffleprize, 2) . '</td>';
                } else {
                    $dataHtml .= '<td>-</td>';
                    $dataHtml .= '<td>-</td>';
                }

                $dataHtml .= '</tr>';
            }
        }

        $dataHtml .= '<tr rowspan="3"><td>Total</td>';
        $dataHtml .= '<td>' . number_format($superraffleprize, 2) . '</td>';
        $dataHtml .= '<td rowspan="2">' . number_format($superraffleprize, 2) . '</td></tr></tbody></table>';

        $result['type'] = '1';
        $result['result'] = $dataHtml;
        $encodedDrawId = encodeValue($rafflePrizeOne);
        // var_dump($rafflePrizeOne, $id);die;
        $result['ded'] = ' <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Report for DED
                              </button>
                              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                              <li><a href=" ' . 'rafflededreport_new1.php?drawid=' .  $encodedDrawId . '&id= ' . $id . '&proid=0&reportType=pdf" target="_blank" class="dropdown-item reportdwn" data-report="0" data-type="pdf" href="#">Pdf</a></li>
                              

                               
                                </ul>
                            </div>';


        $result['ldfullre'] = ' <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Report for LD
                              </button>
                              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                               <li><a href=" ' . $baseurl . 'rafflededreport.php?drawid=' . $winnerid . '&proid=G&reportType=excel&pred=yes" target="_blank" class="dropdown-item reportdwn" data-report="0" data-type="excel" href="#">Excel</a></li>

                                <li><a href=" ' . $baseurl . 'rafflerbddedreport.php?drawid=' . $drawID . '&proid=G" target="_blank" class="dropdown-item reportdwn" data-report="0" data-type="pdf" href="#">Pdf</a></li>
                              </ul>
                            </div>';
        $draw_arr = array("raffleprizefirst" => $first, "raffleprizesecond" => $second, "raffleprizethird" => $third);


        $qry = select_query($con, "pre_draw", "", "`draw_no` = '$drawid' and `deletes`='0' ", "", "");
    } else {
        $result["type"] = "0";
        $result["result"] = 'Draw ID not received!';
    }

    echo json_encode($result);
} else if ($method == 'dedUpdate') {
    $result = [];
    $drawid = $_POST['drawid'];
    $permit = $_POST['permit'];
    if ($drawid != '') {
        if ($permit != '') {
            $draw_arr = ['permitno' => $permit];
            $Inv_update = update($con, "superraffledraw ", "`id` = '$drawid'", $draw_arr, "", "", "", "");
            $errors = $Inv_update['errors'];
            if ($errors != "") {
                $result["type"] = "0";
                // $result["result"] = $errors;
                $result["result"] = 'Updated Failed!';
                goto GsfResult;
            } else {
                $result["type"] = "1";
                $result["result"] = 'Updated Successfully!';
                goto GsfResult;
            }
        } else {
            $result["type"] = "0";
            $result["result"] = 'Kindly Enter the DET Permit Number!';
            goto GsfResult;
        }
    } else {
        $result["type"] = "0";
        $result["result"] = 'Kindly Refresh and try Again!';
        goto GsfResult;
    }
    GsfResult:
    echo json_encode($result);
}
