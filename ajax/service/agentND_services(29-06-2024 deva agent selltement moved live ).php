<?php


include '../../include/shi-config.php';

include '../../include/functions.php';

// include '../../include/payment-config.php';
// var_dump('deva11222233333');die;

// error_reporting(E_ALL);
// ini_set('display_errors', 1);



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






if ($method == "ListofAgent") {
    
    // var_dump('welcom');die;
     $searchTxt = BlockSQLInjectionforagent($_REQUEST["searchTxt"]);
     $status_agent = BlockSQLInjectionforagent($_REQUEST["status"]);
     $formdate = BlockSQLInjectionforagent($_REQUEST["formdate"]);
     $todate = BlockSQLInjectionforagent($_REQUEST["todate"]);
    $result = [];
    // var_dump($searchTxt,$status_agent,$formdate,$todate);die;
    
     $draw_Con = ''; // Initialize the variable
    
    
    $contype ='';
    
     if ($formdate != '' && $todate != '') {

        $da = 'DESC';

        $fd = date("Y-m-d", strtotime($formdate));

        $td = date("Y-m-d", strtotime($todate));

        // $contype = "`purchaseDatetime` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
        $contype = "`created_at` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {

        $da = 'DESC';

        // $contype = "`purchaseDatetime` LIKE '%$now%' AND";
        $contype = "`created_at` LIKE '%$now%' AND";
    }
     if ($searchTxt != '') {
         $contype = '';
         $draw_Con = "(name LIKE '%$searchTxt%' OR email LIKE '%$searchTxt%' OR mobile LIKE '%$searchTxt%') AND ";
            
            
     }


 

    
    $user = select_query($con, "user_register", "", " $contype $draw_Con `roll_id` = '3' AND `deletes`='0'", "", "");


    if ($user['nr'] > 0) {
         foreach ($user['result'] as $key => $value) {
             
             $Agent_ID = $value['id'];
             
             $query = "SELECT sum(total) AS total_topup  FROM `wallet_history` WHERE `userid` = $Agent_ID AND `reward_type` = 'AGENTTOPUP'";
                $data = mysqli_query($con, $query);
                $total_topup = mysqli_fetch_assoc($data)['total_topup'];
                
                $query1 = "SELECT sum(total) AS purchase  FROM `wallet_history` WHERE `userid` = $Agent_ID AND (`reward_type` = 'PURCHASE' or `reward_type` = 'RENEWAL')";
                 $data1 = mysqli_query($con, $query1);
                $purchase = mysqli_fetch_assoc($data1)['purchase'];

             
            //  var_dump($value['id']);die;
             $result[] = array(
            "id" => $value['id'],
            "name" => $value['name'].' '. $value['lname'],
            "mobile" => $value['mobile'],
            "password" => $value['password'],
            "email" => $value['email'],
            "created_at" => $value['created_at'],
            "topup" => $total_topup,
            "purchase" => $purchase,
            "points" => $value['walletBalance'],
            
            
        );
                }
        
        // $result = mysqli_fetch_all($user, MYSQLI_ASSOC);

        // $result["type"] = "1";

        // $result["result"] = "Success!";
    } else {

        $result["type"] = "0";

        $result["result"] = "User not found!";
    }


    // ghjOP:
    
    // var_dump($result);die;
    echo json_encode($result);

    

    
}else if ($method == "Agent_customer") {
    
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
else if ($method == "Agent_customer1") {
    
    // var_dump('welcom');die;
     $searchTxt = BlockSQLInjectionforagent($_REQUEST["searchTxt"]);
     $userId = BlockSQLInjectionforagent($_REQUEST["userId"]);
     $formdate = BlockSQLInjectionforagent($_REQUEST["formdate"]);
     $todate = BlockSQLInjectionforagent($_REQUEST["todate"]);
    $result = [];
    // var_dump($searchTxt,$userId,$formdate,$todate);die;
    
    $contype ='';
    
     if ($formdate != '' && $todate != '') {

        $da = 'DESC';

        $fd = date("Y-m-d", strtotime($formdate));

        $td = date("Y-m-d", strtotime($todate));

        // $contype = "`purchaseDatetime` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
        $contype = "`created_at` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {

        $da = 'DESC';

        // $contype = "`purchaseDatetime` LIKE '%$now%' AND";
        $contype = "`created_at` LIKE '%$now%' AND";
    }


 

    
    $user = select_query($con, "user_register", "", " $contype `agentIds` = $userId AND `roll_id` = '0' AND `deletes`='0'", "", "");
    // var_dump($user);die;


    if ($user['nr'] > 0) {
         foreach ($user['result'] as $key => $value) {
             $result[] = array(
            "id" => $value['id'],
            "name" => $value['name'].' '. $value['lname'],
            "mobile" => $value['mobile'],
            "password" => $value['password'],
            "email" => $value['email'],
            "created_at" => $value['created_at'],
            "points" => "0",
            );}
    } else {

        $result["type"] = "0";

        $result["result"] = "User not found!";
    }


    // ghjOP:
    echo json_encode($result);

    
}
else if ($method == "list_product") {

    // $result = [];

    $contype = '';

    $type = 'OT';

    $da = '';
    $userType = '';

    $now = date('Y-m-d');
    $userType1 = $_POST['userType'];
    
    // var_dump($userType);die;
    if($userType1 == 'customer'){
        
        $userType="nd.`agentId`= 0 AND";
        
    } else if($userType1 == 'agent'){
         $userType="nd.`agentId`!= 0 AND";
    }else{
        $userType = '';
    }
    $formdate = BlockSQLInjectionforagent($_POST["agdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    // $formdate = $_POST['formdate'];

    // $todate = $_POST['todate'];
    
    // var_dump($formdate,$todate);die;

    if ($formdate != '' && $todate != '') {

        $da = 'DESC';

        $fd = date("Y-m-d", strtotime($formdate));

        $td = date("Y-m-d", strtotime($todate));

        // $contype = "`purchaseDatetime` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
        $contype = "nd.`purchaseDatetime` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {

        $da = 'DESC';

        // $contype = "`purchaseDatetime` LIKE '%$now%' AND";
        $contype = "nd.`purchaseDatetime` LIKE '%$now%' AND";
    }
    
    
    // if($userType1 == 'agent'){
        
       
    
    // $NDticketquery = mysqli_query($con, "SELECT 
    // nd.*,
    // ur.name,
    // ur.lname,
    // ur.mobile,
    // ur.email,
    // ur2.name AS agentName
    // FROM `ndticket` nd 
    // JOIN `user_register` ur ON nd.userId = ur.id 
    // JOIN `user_register` ur2 ON nd.agentId = ur2.id 
    // WHERE " . $contype . " nd.agentId != 0 AND nd.id != '' AND nd.deletes != '1'");
    
    // } else{
        
      
        $NDticketquery = mysqli_query($con, "SELECT nd.*, ur.name, ur.lname, ur.mobile, ur.email, CONCAT(ur2.name, ' ', ur2.lname) AS agentName,
            (SELECT product_id FROM invoice WHERE invoice.ticketId = nd.id LIMIT 1) as Product,
            (SELECT rate FROM product WHERE product.id = Product LIMIT 1) as Product_rate
            FROM `ndticket` nd
            JOIN `user_register` ur ON nd.userId = ur.id 
           
            LEFT JOIN `user_register` ur2 ON nd.agentId = ur2.id OR (nd.agentId = '' AND ur2.id = '')
            WHERE $contype $userType nd.id != '' AND nd.deletes != '1'");
         
           
            
        
          
       
    // }
    if (mysqli_num_rows($NDticketquery) > 0) {
        $result = mysqli_fetch_all($NDticketquery, MYSQLI_ASSOC);
    }



    echo json_encode($result);

    
}