<?php


include '../../include/shi-config.php';

include '../../include/functions.php';

// include '../../include/payment-config.php';
// var_dump('deva11222233333');die;

// error_reporting(E_ALL);
// ini_set('display_errors', 1);


$memid = $_SESSION['memid'];


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

//print_r($headers);

$result = array();

$post_csrf = $headers['X-Csrf-Token'] ?? '';



// var_dump('hin');die;


if ($method == "Daily_Report_customer") {

    
    $result = [];
    $contype = '';
    $conition = '';
    $da = '';
    $now = date('Y-m-d');

// var_dump('test');die;
    $formdate = BlockSQLInjectionforagent($_POST["formdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    $tablename = BlockSQLInjectionforagent($_POST["tablename"]);
    $Customer = BlockSQLInjectionforagent($_POST["Customer"]);
    $payment_category = BlockSQLInjectionforagent($_POST["payment_category"]);

    $ticket_like = "";   
    $payment_type = "";
    $contype = '';


    if ($formdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        
        // $contype = " AND p.`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
        $contype = " AND p.`createdon` BETWEEN '$formdate' AND '$todate' ";
    } else {
        $contype = '';
    }

   

    if($Customer == 'non_uae_cus'){
        $which_cus = "AND u.mobile NOT LIKE '971%' ";
    } else{
        $which_cus = "AND u.mobile LIKE '971%' ";
    }
    // var_dump($payment_category);die;
    

    if($payment_category == 'logged_in' && $Customer !=''){


        if ($formdate != '' && $todate != '') {
            $da = 'DESC';
            $fd = date("Y-m-d", strtotime($formdate));
            $td = date("Y-m-d", strtotime($todate));
            
            // $contype = " AND p.`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
            $contype = " AND u.`lastlogin` BETWEEN '$formdate' AND '$todate' ";
            $contype1 = " AND n.`updatedon` BETWEEN '$formdate' AND '$todate' ";
        } else {
            $contype = '';
        }

        // var_dump("SELECT 
        //     u.id AS 'CustomerID', 
        //     u.name AS 'FirstName', 
        //     u.lname AS 'LastName', 
        //     u.email AS 'Email', 
        //     u.mobile AS 'Mobile',
        //     'NA' AS max_finaltotal
        // FROM 
        //     `user_register` u
        // WHERE 
        //     u.deletes = '0' 
        //     AND u.roll_id = '0' 
        //     AND u.status = '0' 
        //     $which_cus
        //     AND u.id NOT IN (
        //         SELECT 
        //             n.userId 
        //         FROM 
        //             `ndticket` n 
        //         WHERE 
        //             n.deletes = '0'
        //             $contype1
        //         GROUP BY 
        //             n.userId
        //     )
        //     $contype");die;

        $select_query = mysqli_query($con, "SELECT 
            u.id AS 'CustomerID', 
            u.name AS 'FirstName', 
            u.lname AS 'LastName', 
            u.email AS 'Email', 
            u.mobile AS 'Mobile',
            'NA' AS max_finaltotal
        FROM 
            `user_register` u
        WHERE 
            u.deletes = '0' 
            AND u.roll_id = '0' 
            AND u.status = '0' 
            $which_cus
            AND u.id NOT IN (
                SELECT 
                    n.userId 
                FROM 
                    `ndticket` n 
                WHERE 
                    n.deletes = '0'
                    $contype1
                GROUP BY 
                    n.userId
            )
            $contype
        ");

    } else if($payment_category != '' && $payment_category != 'logged_in') {

        $ticletReferemID = "";


        if($payment_category =='NotInitiated'){ 
            // $payment_type =" AND p.paymentStatus IS NULL";
            $payment_type =" AND p.paymentStatus ='NULL'";
            $ticket_like = "AND p.user_id NOT IN (
                    SELECT n.userId 
                    FROM `ndticket` n 
                    WHERE n.deletes = '0' 
                    GROUP BY n.userId
                )";
         }
         
        else if($payment_category =='Aborted'){ 
            $payment_type =" AND p.paymentStatus ='Aborted'";
            $ticket_like = "AND p.user_id NOT IN (
                SELECT p.user_id 
                FROM payment_history p 
                WHERE p.paymentStatus = 'Success' 
                $contype
                GROUP BY p.user_id
            )";
        }
        else if($payment_category =='Awaited'){ $payment_type =" AND p.paymentStatus ='Awaited'";}
        else if($payment_category =='Failure'){ 
            $payment_type =" AND p.paymentStatus ='Failure'";
            $ticket_like = "AND p.user_id NOT IN (
                SELECT p.user_id 
                FROM payment_history p 
                WHERE p.paymentStatus = 'Success' 
                $contype
                GROUP BY p.user_id
            )";
        }
        else if($payment_category =='CAPTURED'){ $payment_type =" AND p.paymentStatus ='CAPTURED'";}
        else if($payment_category =='Initiated'){ 
            $payment_type =" AND p.paymentStatus ='Initiated'";
            $ticket_like = "AND p.user_id NOT IN (
                SELECT p.user_id 
                FROM payment_history p 
                WHERE p.paymentStatus = 'Success' 
                $contype
                GROUP BY p.user_id
            )";
        }
        else if($payment_category =='Invalid'){ 
            $payment_type =" AND p.paymentStatus ='Invalid'";
            $ticket_like = "AND p.user_id NOT IN (
                SELECT p.user_id 
                FROM payment_history p 
                WHERE p.paymentStatus = 'Success' 
                $contype
                GROUP BY p.user_id
            )";
        }
        else if($payment_category =='Shipped'){ $payment_type =" AND p.paymentStatus ='Shipped'";}
        else if($payment_category =='Success'){ 
            $payment_type =" AND p.paymentStatus ='Success' AND p.ticketReferenceID !=''";
            $ticletReferemID = "AND p.ticketReferenceID !=''";
        }
        else {$payment_type = "";}

        // var_dump("SELECT 
        //     u.id AS 'CustomerID', 
        //     u.name AS 'FirstName', 
        //     u.lname AS 'LastName', 
        //     u.email AS 'Email', 
        //     u.mobile AS 'Mobile',
        //     MAX(p.finaltotal) AS max_finaltotal,
        //     p.paymentStatus AS PymentStatus
        // FROM 
        //     payment_history p
        // JOIN 
        //     `user_register` u ON u.id = p.user_id
        // WHERE 
        //     u.roll_id = '0' 
        //     $payment_type 
        //     AND p.agent_id = '0'
        //     $ticket_like            
        //     AND u.deletes = '0' 
        //     AND u.roll_id = '0' 
        //     AND u.status = '0' 
        //     $which_cus
        //     $contype
        // GROUP BY 
        //     u.id, u.name, u.lname, u.email, u.mobile
        // HAVING 
        //     MAX(p.finaltotal) > 0;");die;


        $select_query = mysqli_query($con, "SELECT 
            u.id AS 'CustomerID', 
            u.name AS 'FirstName', 
            u.lname AS 'LastName', 
            u.email AS 'Email', 
            u.mobile AS 'Mobile',
            MAX(p.finaltotal) AS max_finaltotal,
            p.paymentStatus AS PymentStatus
        FROM 
            payment_history p
        JOIN 
            `user_register` u ON u.id = p.user_id
        WHERE 
            u.roll_id = '0' 
            $payment_type 
            AND p.agent_id = '0'
            $ticket_like            
            AND u.deletes = '0' 
            AND u.roll_id = '0' 
            AND u.status = '0' 
            $which_cus
            $contype
        GROUP BY 
            u.id, u.name, u.lname, u.email, u.mobile
        HAVING 
            MAX(p.finaltotal) > 0;
        ");

        


    }

    if (mysqli_num_rows($select_query) > 0) {
        $result = ['type' => 1, 'result' => mysqli_fetch_all($select_query, MYSQLI_ASSOC)];
    } 
    else {
        $result = ['type' => 0, 'result' => []];
    }
    
    
    echo json_encode($result);
} else if ($method == "Agent_customer") {

    // var_dump('deva losu');die;

    $searchTxt = BlockSQLInjectionforagent($_REQUEST["searchTxt"]);
    $userId = BlockSQLInjectionforagent($_REQUEST["userId"]);
    $formdate = BlockSQLInjectionforagent($_REQUEST["formdate"]);
    $todate = BlockSQLInjectionforagent($_REQUEST["todate"]);

    $result = [];

    $contype = '';
    $now = date("Y-m-d");

    if ($formdate != '' && $todate != '') {
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "`created_at` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $contype = "`created_at` LIKE '%$now%' AND";
    }

    // $user = select_query($con, "user_register", "", " $contype `roll_id` = '' AND `deletes`='0'", "", "");
    $user = select_query($con, "user_register", "", "`agentIds` IS NOT NULL AND `roll_id` = '0' AND `deletes`='0'", "", "");

    $result = [];



    if ($user['nr'] > 0) {
        foreach ($user['result'] as $key => $value) {

            $agentIds = json_decode($value['agentIds']);

            // if (in_array($userId, $agentIds)) {
            if (is_array($agentIds) && in_array($userId, $agentIds)) {
                $result[] = array(
                    "id" => $value['id'],
                    "name" => $value['name'] . ' ' . $value['lname'],
                    "agentIds" => $value['agentIds'],
                    "mobile" => $value['mobile'],
                    "password" => $value['password'],
                    "email" => $value['email'],
                    "created_at" => $value['created_at'],
                    "points" => "0",
                );
            }
        }
    }

    echo json_encode($result);
} 