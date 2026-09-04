<?php
include '../../include/shi-config.php';
include '../../include/functions.php';

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";
$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";
$role = $_REQUEST['role'] ?? '';

if ($type == 'agent') {
    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($role) AND" : "";
} else {
    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$role' AND" : "";
}

$headers = apache_request_headers();
$result = [];

$post_csrf = $headers['X-Csrf-Token'] ?? '';


if ($method === "topSpendersList") {
    try {

        $pattern = '/^\d{4}-\d{2}-\d{2}$/';

        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $dateFilter = $_POST['dateFilter'];
        $drawID = $_POST['drawID'];
        $currencyID = $_POST['currencyID'];

        $valFilter = '';
        $payFliter = '';
        $invFliter = '';

        $currencyFLT = '';

        if (isset($currencyID) && $currencyID != '') {
            $currencyFLT = " AND s.currency = '$currencyID' ";
        }


        if (isset($dateFilter) && $dateFilter != '') {

            if (!preg_match($pattern, $startDate) || !preg_match($pattern, $endDate)) {
                $result['type'] = 0;
                $result['result'] = 'Please provide valid start and end dates';
                $result['data'] = [];
            }

            if (!isset($startDate) || $startDate == '' || $startDate == null || !isset($endDate) || $endDate == '' || $endDate == null) {
                $result['type'] = 0;
                $result['result'] = 'Please provide valid start and end dates';
                $result['data'] = [];
                goto resutGJHIP;
            }



            $payFliter = " AND lp.createdon BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ";

            $invFliter = " AND s.createdon BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ";

            $valFilter = " AND createdon BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ";

            // $valFilter = " AND createdon BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ";
        }


        // if (isset($drawID) && $drawID != '' && $drawID != null) {

        //     $draw = select_query($con, "ll_draw", "", "`deletes` = '0' AND `id` = '$drawID' ORDER BY `result_datetime` ASC LIMIT 1", "", "");
        //     if ($draw['nr'] < 1) {
        //         $result['type'] = 0;
        //         $result['result'] = 'Active Draw Not Found!';
        //         $result['data'] = [];
        //         goto resutGJHIP;
        //     }



        //     $startDate = $draw['result'][0]['ticket_start_datetime'];

        //     $endDate = $draw['result'][0]['ticket_end_datetime'];



        //     $payFliter = " AND lp.createdon BETWEEN '$startDate' AND '$endDate' ";

        //     $invFliter = " AND i.createdon BETWEEN '$startDate' AND '$endDate' ";

        //     $valFilter = " AND createdon BETWEEN '$startDate' AND '$endDate' ";
        // }








        //         $selectQuery = "SELECT 
//     g.user_id 'CustomerID', 
//     CONCAT(u.name, ' ', u.lname) AS 'Name', 
//     u.mobile 'Mobile', 
//     u.email 'Email', 
//     g.grandtotal 'TotalSpending', 
//     COALESCE(w.prize, 0) AS  'TotalWinning',
//     COALESCE(tc.tapPrize, 0) AS 'TotalTapWinning',
//    COALESCE(w.prize, 0) + COALESCE(tc.tapPrize, 0) AS 'TotalWinningCombined'

        // FROM 
//     (SELECT user_id, SUM(grandtotal) AS grandtotal
// FROM (
//     SELECT lp.user_id, SUM(lp.grandtotal) AS grandtotal
//     FROM ll_payment_history lp
//     WHERE lp.gateway = 'myfatoorah' AND lp.paymentStatus = 'Paid' AND lp.category = 'WalletDeposit'  $payFliter 
//     GROUP BY lp.user_id

        //     UNION ALL

        //     SELECT i.user_id, SUM(i.grandtotal) AS grandtotal
//     FROM ll_invoice i
//     WHERE i.deletes = '0' AND i.paymentType = 'Card'  $invFliter
//     GROUP BY i.user_id

        //  UNION ALL

        // SELECT userid, SUM(COALESCE(total, 0)) AS walletTopUp FROM `ll_walletBalance_history` WHERE `transaction_type` = 'CREDIT' AND `point_type` = 'WALLET' AND `reward_type` IN ('PUR_CANCEL') AND `deletes` = '0' AND `status` = '0' $valFilter GROUP BY userid

        // ) AS combined
// GROUP BY user_id
// ORDER BY grandtotal DESC) AS g 
// LEFT JOIN 
//     (SELECT 
//         w.userid, 
//         SUM(w.prize_amt) AS prize 
//      FROM 
//         ll_winnerlist w 
//      WHERE 
//         w.deletes = '0' 
//      GROUP BY 
//         w.userid 
//      ORDER BY 
//         prize DESC) AS w 
// ON 
//     w.userid = g.user_id 
// LEFT JOIN 
//     (SELECT 
//         tc.userID, 
//         SUM(tc.tapWinPrizeAmt) AS tapPrize 
//      FROM 
//         tapWin_chances tc 
//      WHERE 
//         tc.deletes = '0' AND tc.claimStatus = 'YES'
//      GROUP BY 
//         tc.userID) AS tc 
// ON 
//     tc.userID = g.user_id 
// LEFT JOIN 
//     user_register u ON u.id = g.user_id 
// ORDER BY `TotalSpending` DESC;";


        $selectQuery = "SELECT s.* , u.name, u.email, u.mobile FROM `subscriptions` s LEFT JOIN user_register u ON s.user_id = s.id WHERE  s.id != '' $invFliter $currencyFLT ORDER BY `id` DESC;";





        $query = mysqli_query($con, $selectQuery);
        if ($query && mysqli_num_rows($query) > 0) {
            $result['type'] = 1;
            $result['result'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        } else {
            $result['type'] = 0;
            $result['result'] = [];
            goto resutGJHIP;
        }
    } catch (Exception $e) {
        $result['type'] = 0;
        $result['result'] = $e->getMessage();
    }
} else if ($method === "walletEOD") {
    try {

        $pattern = '/^\d{4}-\d{2}-\d{2}$/';

        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $dateFilter = $_POST['dateFilter'];
        $drawID = $_POST['drawID'];

        $payFliter = '';
        $invFliter = '';

        if (isset($dateFilter) && $dateFilter != '') {

            if (!preg_match($pattern, $startDate) || !preg_match($pattern, $endDate)) {
                $result['type'] = 0;
                $result['result'] = 'Please provide valid start and end dates';
                $result['data'] = [];
            }

            if (!isset($startDate) || $startDate == '' || $startDate == null || !isset($endDate) || $endDate == '' || $endDate == null) {
                $result['type'] = 0;
                $result['result'] = 'Please provide valid start and end dates';
                $result['data'] = [];
                goto resutGJHIP;
            }



            $payFliter = " AND startTime BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ";

            // $invFliter = " AND i.createdon BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ";

        }


        // if (isset($drawID) && $drawID != '' && $drawID != null) {

        //     $draw = select_query($con, "ll_draw", "", "`deletes` = '0' AND `id` = '$drawID' ORDER BY `result_datetime` ASC LIMIT 1", "", "");
        //     if ($draw['nr'] < 1) {
        //         $result['type'] = 0;
        //         $result['result'] = 'Active Draw Not Found!';
        //         $result['data'] = [];
        //         goto resutGJHIP;
        //     }



        //     $startDate = $draw['result'][0]['ticket_start_datetime'];

        //     $endDate = $draw['result'][0]['ticket_end_datetime'];
        //     $payFliter = " AND lp.createdon BETWEEN '$startDate' AND '$endDate' ";

        //     $invFliter = " AND i.createdon BETWEEN '$startDate' AND '$endDate' ";

        // }


        $selectQuery = "SELECT * FROM `wallet_eod` WHERE reportStatus = 'YES' AND deletes = '0'  $payFliter;";


        // var_dump($selectQuery);
        // die;

        $query = mysqli_query($con, $selectQuery);
        if ($query && mysqli_num_rows($query) > 0) {
            $result['type'] = 1;
            $result['result'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        } else {
            $result['type'] = 0;
            $result['result'] = [];
            goto resutGJHIP;
        }
    } catch (Exception $e) {
        $result['type'] = 0;
        $result['result'] = $e->getMessage();
    }
} else if ($method === "walletEODDAY") {
    try {

        $pattern = '/^\d{4}-\d{2}-\d{2}$/';

        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $dateFilter = $_POST['dateFilter'];
        $drawID = $_POST['drawID'];

        $payFliter = '';
        $invFliter = '';

        if (isset($dateFilter) && $dateFilter != '') {

            if (!preg_match($pattern, $startDate) || !preg_match($pattern, $endDate)) {
                $result['type'] = 0;
                $result['result'] = 'Please provide valid start and end dates';
                $result['data'] = [];
            }

            if (!isset($startDate) || $startDate == '' || $startDate == null || !isset($endDate) || $endDate == '' || $endDate == null) {
                $result['type'] = 0;
                $result['result'] = 'Please provide valid start and end dates';
                $result['data'] = [];
                goto resutGJHIP;
            }



            $payFliter = " AND startTime BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ";

            // $invFliter = " AND i.createdon BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ";

        }


        // if (isset($drawID) && $drawID != '' && $drawID != null) {

        //     $draw = select_query($con, "ll_draw", "", "`deletes` = '0' AND `id` = '$drawID' ORDER BY `result_datetime` ASC LIMIT 1", "", "");
        //     if ($draw['nr'] < 1) {
        //         $result['type'] = 0;
        //         $result['result'] = 'Active Draw Not Found!';
        //         $result['data'] = [];
        //         goto resutGJHIP;
        //     }



        //     $startDate = $draw['result'][0]['ticket_start_datetime'];

        //     $endDate = $draw['result'][0]['ticket_end_datetime'];
        //     $payFliter = " AND lp.createdon BETWEEN '$startDate' AND '$endDate' ";

        //     $invFliter = " AND i.createdon BETWEEN '$startDate' AND '$endDate' ";

        // }


        $selectQuery = "SELECT * FROM `wallet_eod_day` WHERE reportStatus = 'YES' AND deletes = '0'  $payFliter;";


        // var_dump($selectQuery);
        // die;

        $query = mysqli_query($con, $selectQuery);
        if ($query && mysqli_num_rows($query) > 0) {
            $result['type'] = 1;
            $result['result'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        } else {
            $result['type'] = 0;
            $result['result'] = [];
            goto resutGJHIP;
        }
    } catch (Exception $e) {
        $result['type'] = 0;
        $result['result'] = $e->getMessage();
    }
} else {
    $result['type'] = 0;
    $result['result'] = 'The Method Not Found!';
    goto resutGJHIP;
}

resutGJHIP:
echo json_encode($result);
