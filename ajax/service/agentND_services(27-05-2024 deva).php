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
    
    $contype ='';
    $draw_Con = '';
    
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
     
    //  var_dump($contype,$draw_Con);die;


 

    
    $user = select_query($con, "user_register", "", " $contype $draw_Con `roll_id` = '3' AND `deletes`='0'", "", "");


    if ($user['nr'] > 0) {
         foreach ($user['result'] as $key => $value) {
             $result[] = array(
            "id" => $value['id'],
            "name" => $value['name'].' '. $value['lname'],
            "mobile" => $value['mobile'],
            "password" => $value['password'],
            "email" => $value['email'],
            "created_at" => $value['created_at'],
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