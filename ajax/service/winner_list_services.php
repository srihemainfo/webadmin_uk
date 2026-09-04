<?php

include '../../include/shi-config.php';

include '../../include/functions.php';

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";

$type = isset($_REQUEST["type"]) ? $_REQUEST[type] : "";

$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST[tabID] : "";

if ($type == 'agent') {

    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($_REQUEST[role]) AND" : "";
} else {

    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$_REQUEST[role]' AND" : "";
}

$headers = apache_request_headers();

//print_r($headers);

$result = array();

$post_csrf = $headers['X-Csrf-Token'] ?? '';


if ($method == "winner_list_services") {

    $result = [];
    $data = [];
    $groupBYId = mysqli_query($con, "SELECT `userid`, SUM(prize_amt) AS 'prize' FROM `winnerlist` WHERE userid != '' GROUP BY userid;");
    while ($row = mysqli_fetch_assoc($groupBYId)) {
        $userid = $row['userid'];
        $name = select_top_name($con, "user_register", "name", "`id`='$userid'", "name", "");

        $data[] = ['userid' =>   $userid, 'name' =>  $name];
        $credit_Total = '';
        $winnerlist = mysqli_query($con, "SELECT winnerlist.id AS 'id', user_register.id AS 'userid', user_register.name AS 'name', user_register.email AS 'email', user_register.mobile AS 'mobile', winnerlist.prize AS 'prize', winnerlist.prize_amt AS 'prizeamt', draw.id AS 'drawid', draw.draw_no AS 'drawno', draw.name AS 'drawname',  draw.result_datetime AS 'resultdate', ticket_lines.id AS 'ticketlinseis', ticket_lines.invoice_no AS 'invoiceid' FROM winnerlist INNER JOIN user_register ON user_register.id = winnerlist.userid INNER JOIN draw ON draw.id = winnerlist.draw_id INNER JOIN ticket_lines ON ticket_lines.id = winnerlist.ticket_lines_id WHERE winnerlist.userid LIKE '$userid' ORDER BY id ASC;");
        while ($row1 = mysqli_fetch_assoc($winnerlist)) {
            $credit_Total += (int)$row1['prizeamt'];
            $data[]  =   ['credit' => (int)$row1['prizeamt'], 'text' => 'Prize ' . $row1['drawname'], 'date' => date('d-m-Y g:i a', strtotime($row1['resultdate']))];
        }

        $debit_Total = '';
        $withdraw = mysqli_query($con, "SELECT *  FROM `withdraw_request` WHERE `from_id` = " . $row['userid'] . " AND `status` = '1' ORDER BY `id` ASC");
        while ($wrow = mysqli_fetch_assoc($withdraw)) {
            $debit_Total += (int)$wrow['amount'];
            $data[]  =   ['debit' => (int)$wrow['amount'], 'text' => 'withdraw', 'date' => date('d-m-Y', strtotime($wrow['trans_date']))];
        }


        $ticket = mysqli_query($con, "SELECT ticket.id AS 'ticketid' , ticket.net_total AS 'amount', ticket.purchase_datetime AS 'purchasetime', ticket.ticket_no AS 'ticketno' FROM `ticket` INNER JOIN invoice ON invoice.id = ticket.invoice_no WHERE ticket.user_id = " . $row['userid'] . " AND ticket.deletes = 0 AND invoice.response = 'wallet';");
        while ($trow = mysqli_fetch_assoc($ticket)) {
            $debit_Total += (int)$trow['amount'];
            $data[] = ['debit' => (int)$trow['amount'], 'text' => 'Ticket Purchase', 'date' => date('d-m-Y g:i a', strtotime($trow['purchasetime']))];
        }

        $t_earning = select_top_name($con, "user_register", "t_earning", "`id`='$userid'", "t_earning", "");
        $data[] = ['debit' => $debit_Total, 'credit' => $credit_Total, 'wallet' => (int)$t_earning];
    }


    $result['type'] = '1';
    $result['result'] = $data;
    echo json_encode($result);
}
