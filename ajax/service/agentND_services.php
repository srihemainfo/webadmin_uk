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


if ($method == "ListofAgent") {


    // var_dump('welcom');die;
    $searchTxt = BlockSQLInjectionforagent($_REQUEST["searchTxt"]);
    $status_agent = BlockSQLInjectionforagent($_REQUEST["status"]);
    $formdate = BlockSQLInjectionforagent($_REQUEST["formdate"]);
    $todate = BlockSQLInjectionforagent($_REQUEST["todate"]);
    $searchTxt = BlockSQLInjectionforagent($_REQUEST["searchTxt"]);
    $status_agent = BlockSQLInjectionforagent($_REQUEST["status"]);
    $formdate = BlockSQLInjectionforagent($_REQUEST["formdate"]);
    $todate = BlockSQLInjectionforagent($_REQUEST["todate"]);
    $result = [];
    // var_dump($searchTxt,$status_agent,$formdate,$todate);die;

    $draw_Con = ''; // Initialize the variable


    $contype = '';

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
                "name" => $value['name'] . ' ' . $value['lname'],
                "mobile" => $value['mobile'],
                "password" => $value['password'],
                "email" => $value['email'],
                "created_at" => $value['created_at'],
                "topup" => $total_topup,
                "purchase" => $purchase,
                "commision_point" => $commision_point,
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
} else if ($method == "Agent_customer1") {

    // var_dump('welcom');die;
    $searchTxt = BlockSQLInjectionforagent($_REQUEST["searchTxt"]);
    $userId = BlockSQLInjectionforagent($_REQUEST["userId"]);
    $formdate = BlockSQLInjectionforagent($_REQUEST["formdate"]);
    $todate = BlockSQLInjectionforagent($_REQUEST["todate"]);
    $result = [];
    // var_dump($searchTxt,$userId,$formdate,$todate);die;

    $contype = '';

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
                "name" => $value['name'] . ' ' . $value['lname'],
                "mobile" => $value['mobile'],
                "password" => $value['password'],
                "email" => $value['email'],
                "created_at" => $value['created_at'],
                "points" => "0",
            );
        }
    } else {

        $result["type"] = "0";

        $result["result"] = "User not found!";
    }


    // ghjOP:
    echo json_encode($result);
} else if ($method == "list_product") {

    // $result = [];

    $contype = '';

    $type = 'OT';

    $da = '';
    $userType = '';

    $now = date('Y-m-d');
    $userType1 = $_POST['userType'];

    // var_dump($userType);die;
    if ($userType1 == 'customer') {

        $userType = "nd.`agentId`= 0 AND";
    } else if ($userType1 == 'agent') {
        $userType = "nd.`agentId`!= 0 AND";
    } else {
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
} else if ($method == "Gold_Request") {

    //  var_dump('hellow');die;
    // $result = [];

    $contype = '';


    $draw_Con = '';

    $now = date('Y-m-d');
    $userType1 = $_POST['userType'];

    // var_dump($userType1);die;

    $selectedProductId = $_POST['selectedProductId'];




    $formdate = BlockSQLInjectionforagent($_POST["agdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    $searchTxt = BlockSQLInjectionforagent($_REQUEST["search_id"]);
    // $formdate = $_POST['formdate'];

    // $todate = $_POST['todate'];

    // var_dump($productRate,$userType1);die;

    if ($formdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));

        $contype = "`generate_date` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $contype = '';
    }



    if ($searchTxt != '') {
        $draw_Con = "(uname LIKE '%$searchTxt%' OR umobile LIKE '%$searchTxt%' OR uemail LIKE '%$searchTxt%') AND ";
    }
    // if ($searchTxt == ''  && $formdate == '' && $todate == '') {


    //     $contype = "G.`createdon` LIKE '%$now%' AND";
    // }




    $AgentSettlement = mysqli_query($con, "SELECT * FROM `agent_settlement` WHERE  $contype $draw_Con deletes = '0';");



    //   var_dump($GoldRequest);die;

    if (mysqli_num_rows($AgentSettlement) > 0) {
        while ($row = mysqli_fetch_assoc($AgentSettlement)) {

            $processedBy = $row['processedBy'];

            if ($processedBy == 0) {
                $processedBy_name = ""; // Assign a default name or any other default action
                $update_time = '';
            } else {
                $processedBy_name = select_top_name($con, "user_register", "name", "`id`=$processedBy", "name", "");
                $update_time = $row['updatedon'];
            }





            $result[] = [
                "updatedon" => $update_time,
                "createdon" => $row['createdon'],
                "uname" => $row['uname'],
                "id" => $row['id'],
                "umobile" => $row['umobile'],
                "uemail" => $row['uemail'],
                "totalSales" => $row['totalSales'],
                "commissionPercentage" => $row['commissionPercentage'],
                "totalEarnings" => $row['totalEarnings'],
                "generate_date" => $row['generate_date'],
                "status" => $row['status'],
                "method_type" => $row['method_type'],
                "processedBy_name" => $processedBy_name,

            ];
        }
    }


    // var_dump($result);die;
    echo json_encode($result);
} else if ($method == "method_type") {


    $result = [];

    $contype = '';


    $draw_Con = '';

    $now = date('Y-m-d');
    $rowId = $_POST['rowId'];
    $selectedValue = $_POST['selectedValue'];
    $delete_reason = $_POST['delete_reason'];

    // var_dump("UPDATE agent_settlement SET method_type = '$selectedValue',reason ='$delete_reason' WHERE id = $rowId");die;



    $updateQuery1 = "UPDATE agent_settlement SET method_type = '$selectedValue',reason ='$delete_reason' WHERE id = $rowId";
    if (mysqli_query($con, $updateQuery1)) {
        echo json_encode(array("type" => 1, "status" => "success", "message" => "Method type updated successfully"));
    } else {
        echo json_encode(array("type" => 0, "status" => "error", "message" => "Error updating method type"));
    }
} else if ($method == "submitFunction") {


    $result = [];
    $contype = '';
    $draw_Con = '';
    $now = date('Y-m-d');
    $rowId = $_POST['rowId'];
    $method_type = $_POST['mode'];
    $reasontext = $_POST['reasontext'];


    $currentMonthText = strtoupper(date('F'));
    
    // var_dump($current_month);die;
    
    
    
    
     
     $Agent_settled = select_query($con, "agent_settlement", "", "`id` = $rowId and `deletes`='0'", "", "");

    // var_dump($Agent_settled);die;

    if ($Agent_settled['nr'] > 0) {


        $userid = $Agent_settled['result'][0]['userid'];
        $uname = $Agent_settled['result'][0]['uname'];
        $umobile = $Agent_settled['result'][0]['umobile'];
        $uemail = $Agent_settled['result'][0]['uemail'];
        $month = $Agent_settled['result'][0]['month'];
        $totalSales = $Agent_settled['result'][0]['totalSales'];
        $commissionPercentage = $Agent_settled['result'][0]['commissionPercentage'];
        $totalEarnings = $Agent_settled['result'][0]['totalEarnings'];
        $settle_id = $Agent_settled['result'][0]['id'];
        // $settle_id = $Agent_settled['result'][0]['id'];

        


        $open_walletBalance = select_top_name($con, "user_register", "walletBalance", "`id`=$userid", "walletBalance", "");



        $closeing_walletBalance = $open_walletBalance + $totalEarnings;
    }

    
$currentMonthText1 = date('F', mktime(0, 0, 0, $month, 1));

// var_dump($month);

// var_dump($currentMonthText1);die;


    $balance_point = select_top_name($con, "ndbank", "points", "`deletes`='0'", "points", "");

    if (intval($balance_point) > 0) {
        if (intval($balance_point) >= intval($totalEarnings)) {
        } else {

            $result['message'] = '<div class="alert alert-danger">Your Point Balance is Low, Refill your Points Before Starting Transaction.</div>';
            $result['type'] = 0;

            goto GHi1;
        }
    }

    $nd_to_balance = $balance_point;

    $UT_point = intval($nd_to_balance) - intval($totalEarnings);



    if ($method_type == "" or $method_type == "NULL") {

        $result['message'] = '<div class="alert alert-danger">Please Select the Method Type.</div>';
        $result['type'] = 0;

        goto GHi1;
        // echo json_encode(array("type" => 0, "status" => "error", "message" => "Please Select the Method Type"));

    } else {
        // var_dump('jjj');die;
        if ($method_type == 'Paid Cash') {



            $update = "UPDATE agent_settlement SET processedBy = $memid,status ='1',method_type ='$method_type',reason = '$reasontext', updatedon ='$dubaidate_time' WHERE id = $rowId";


            if (mysqli_query($con, $update)) {

                $email = $uemail;
                $subject = 'Agent Commission:'.$currentMonthText1. '  '.$currentYear.' Commission Points Settement';


                $messages = '
    <!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Agent Cash Settlement</title>
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
            color: #01104e;
            }
            .column-one h3 {
            color: #01104e;
            font-family: Verdana, sans-serif !important;
            font-size: 28px;
            font-weight: 600;
            margin: 14px 0 0 0;
            }
            .column-one p {
            color: #01104e;
            font-family: Verdana, sans-serif !important;
            font-size: 19px;
            font-weight: 500;
            margin: 4px 0;
            }
        </style>
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
                                <img src="https://nationalasset.blr1.digitaloceanspaces.com/nationaldraw/1/ndLogo.png" style="border: 0px;"
                                    width="58%">
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
                    <p style="font-weight: 600!important; margin-top:18px;">Hi, '.$uname.'</p>
                    
                    
                    </td>
                </tr>
                <tr>
                    <td valign="top" style="padding: 0;">
                    <center>
                        <br>
                        <img src="https://nationalasset.blr1.digitaloceanspaces.com/nationaldraw/1/paymentchaspay.png" style="border-radius: 19px;" width="27%">
                        <br>
                    </center>
                    </td>
                </tr>
                <!-- <tr>
                    <td class="column-one c-f">
                    
                    <p style="font-size:16px;font-weight: !important;font-family: Verdana, sans-serif !important;margin: 3px auto;padding: 0 4%;border-radius: 8px;">
                        <!-- Agent Cash Settlement 
                        <br>
                        Your June 2024 commission points have been successfully settled as cash.
                    </p>
                    </td>
                </tr>
                <tr> -->
                    <td>
                        <br>
                        <table style="margin: auto;border-collapse: collapse;border: 1px solid #088b42;width:90%;max-width:480px;" border="1" cellspacing="2" cellpadding="0">
                            <tbody>
                            <tr>		
                                
                            
                                <th style="padding: 12px 0px;color: #ffffff;font-family: Verdana, sans-serif !important;font-size:17px;background: #01104e;" align="center" bgcolor="#d0dbe7"><strong style="font-weight: 500;">Sale Amount</strong></th>
                                <th style="padding: 12px 0px;color: #ffffff;font-family: Verdana, sans-serif !important;font-size:17px;background: #01104e;" align="center" bgcolor="#d0dbe7"><strong style="font-weight: 500;">Commission<br>(Percentage)</strong></th>
                                <th style="padding: 12px 0px;color: #ffffff;font-family: Verdana, sans-serif !important;font-size:17px;background: #01104e;" align="center" bgcolor="#d0dbe7"><strong style="font-weight: 500;">Commission Amount </strong></th>

                                
                            </tr>
                            <tr>
                            
                                <td style=" padding: 12px 0px;color: #01104e;font-family: Verdana, sans-serif !important;font-size:17px;" align="center" bgcolor="#ffffff"><strong style="font-weight: 500;">AED '.$totalSales.'  </strong></td>
                                <td style=" padding: 12px 0px;color: #01104e;font-family: Verdana, sans-serif !important;font-size:17px;" align="center" bgcolor="#ffffff"><strong style="font-weight: 500;">'.$commissionPercentage.'% </strong></td>
                                <td style=" padding: 12px 0px;color: #01104e;font-family: Verdana, sans-serif !important;font-size:17px;" align="center" bgcolor="#ffffff"><strong style="font-weight: 500;">AED '.$totalEarnings.'</strong></td>

                            
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                
        
                <tr>
                    <td class="column-one c-f">
                    
                    <p style="font-size:16px;font-weight: !important;font-family: Verdana, sans-serif !important;margin: 3px auto;padding: 0 6%;border-radius: 8px;">
                        <!-- Agent Cash Settlement -->
                        <br>
                        Your '.$currentMonthText1.' '.$currentYear.' commission points have been successfully settled as cash.
                    </p>
                    <br>
                    </td>
                </tr>
                <tr>
            
                <tr>
                    <td class="column-one">
                    <img style="width: !important;margin-top: 10px;" src="https://nationalasset.blr1.digitaloceanspaces.com/nationaldraw/1/ndFooter.png" width="84%">
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

    $messages2 = mysqli_real_escape_string($con, $messages);

    $insertlog = mysqli_query($con, "INSERT INTO `emaillog` (`details`,`subject`,`email`,`ip`,`datetime`,`status`) VALUES ('$messages2','$subject','$email','','$dubaidate_time','0')");
                                        
                                        if ($insertlog) {
                                            $id12 = mysqli_insert_id($con);
                                         
                                            //   var_dump($id12); die;
                                            
                                            if($id12 !=''){

                                                $result['message'] = 'Agent cash payment settlement successfully';
                                                $result['type'] = 1;
                                                goto GHi1;
                                                // echo json_encode(array("type" => 1, "status" => "success", "message" => "Agent settlement updated successfully"));
                                                

                                                
                                            } else {

                                                $result['message'] = 'Could Not Send Email';
                                                $result['type'] = 0;
                                                goto GHi1;
                                                // echo 'Could Not Send Email';
                                            }
                                            
                                            
                                        } else {
                                            // Handle the error if the query failed
                                            echo "Error: " . mysqli_error($con);
                                        }

                
            } else {

                $result['message'] = 'Error updating method type.';
                $result['type'] = 0;
                goto GHi1;
                // echo json_encode(array("type" => 0, "status" => "error", "message" => "Error updating method type"));
            }
        }
        if ($method_type == 'Point Transaction') {


            $Arrd1 = array("userid" => "$userid", "uname" => "$uname", "umobile" => "$umobile", "uemail" => "$uemail", "opening_balance" => "$open_walletBalance", "total" => $totalEarnings, "closeing_balance" => $closeing_walletBalance, "point_type" => 'WALLET', "transaction_type" => 'CREDIT', "reward_type" => $currentMonthText . 'SETTLEMENT', "reference_id" => $settle_id, "reference_table" => 'agent_settlement');
            $Arrd2 = array("userid" => "$userid", "uname" => "$uname", "umobile" => "$umobile", "uemail" => "$uemail", "opening_balance" => "$open_walletBalance", "total" => $totalEarnings, "closeing_balance" => $closeing_walletBalance, "ND_Bank" => $nd_to_balance, "point_type" => 'WALLET', "transaction_type" => 'DEBIT', "reward_type" => $currentMonthText . 'SETTLEMENT', "reference_id" => $settle_id, "reference_table" => 'agent_settlement');
            // var_dump($Arrd1);die;

            $Wallet_History = insert($con, "wallet_history", "", $Arrd1, "", "", "");
            $point_trans = insert($con, "ndbank_history", "", $Arrd2, "", "", "");

            $id12 = $point_trans["id"];


            $point_arr1 = array("points" => $UT_point);
            $point_update1 = update($con, "ndbank", "`deletes`='0'", $point_arr1, "", "", "", "");
            $errors = $point_update1['errors'];
            if ($errors != "") {
                $result["type"] = "0";
                $result["result"] = $errors;
                goto GHi1;
            } else {
                $point_arr2 = array("t_point" => $closeing_walletBalance, "walletBalance" => $closeing_walletBalance);
                $point_update2 = update($con, "user_register", "`id` = '$userid' and `deletes`='0'", $point_arr2, "", "", "", "");
                $errors = $point_update2['errors'];
                if ($errors != "") {
                    $result["type"] = "0";
                    $result["result"] = $errors;
                } else {

                    $update = "UPDATE agent_settlement SET processedBy = $memid,status ='1', method_type ='$method_type',reason = '$reasontext',updatedon ='$dubaidate_time',reference_id = $id12 ,reference_table ='ndbank_history' WHERE id = $rowId";
                    // var_dump($update);die;

                    if (mysqli_query($con, $update)) {

                        $email = $uemail;
                        $subject = 'Agent Commission:'.$currentMonthText1. '  '.$currentYear.' Commission Points Settement';
        
        
                        $messages = '
            
<!DOCTYPE html
   PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>point transaction</title>
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
         color: #01104e;
         }
         .column-one h3 {
         color: #01104e;
         font-family: Verdana, sans-serif !important;
         font-size: 28px;
         font-weight: 600;
         margin: 14px 0 0 0;
         }
         .column-one p {
         color: #01104e;
         font-family: Verdana, sans-serif !important;
         font-size: 19px;
         font-weight: 500;
         margin: 4px 0;
         }
      </style>
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
                              <img src="https://nationalasset.blr1.digitaloceanspaces.com/nationaldraw/1/ndLogo.png" style="border: 0px;"
                                 width="58%">
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
                  <p style="font-weight: 600!important; margin-top:18px;">Hi, '.$uname.'</p>
                 
                 
                </td>
              </tr>
            <tr>
                <td valign="top" style="padding: 0;">
                   <center>
                      <br>
                      <img src="https://nationalasset.blr1.digitaloceanspaces.com/nationaldraw/1/pointtransaction.png" style="border-radius: 19px;" width="25%">
                      <br>
                   </center>
                </td>
             </tr>
            <!-- <tr>
                <td class="column-one c-f">
                 <br>
                  <p style="font-size:16px;font-weight: 00!important;font-family: Verdana, sans-serif !important;margin: 3px auto;padding: 0 4%;border-radius: 8px;">
                    <!-- Agent Point Transaction 
                   Your June 2024 commission points have been successfully transferred to your agent wallet.
                  </p>
                </td>
              </tr> -->
              <tr>
                <td><br>
                    <table style="margin: auto;border-collapse: collapse;border: 1px solid #088b42;width:90%;max-width:480px;" border="1" cellspacing="2" cellpadding="0">
                        <tbody>
                          <tr>		
                            
                          
                            <th style="padding: 12px 0px;color: #ffffff;font-family: Verdana, sans-serif !important;font-size:17px;background: #01104e;" align="center" bgcolor="#d0dbe7"><strong style="font-weight: 500;">Sale Amount</strong></th>
                            <th style="padding: 12px 0px;color: #ffffff;font-family: Verdana, sans-serif !important;font-size:17px;background: #01104e;" align="center" bgcolor="#d0dbe7"><strong style="font-weight: 500;">Commission<br>(Percentage)</strong></th>
                            <th style="padding: 12px 0px;color: #ffffff;font-family: Verdana, sans-serif !important;font-size:17px;background: #01104e;" align="center" bgcolor="#d0dbe7"><strong style="font-weight: 500;">Commission Amount </strong></th>

                            
                          </tr>
                          <tr>
                          
                            <td style=" padding: 12px 0px;color: #01104e;font-family: Verdana, sans-serif !important;font-size:17px;" align="center" bgcolor="#ffffff"><strong style="font-weight: 500;">AED '.$totalSales.'                           </strong></td>
                            <td style=" padding: 12px 0px;color: #01104e;font-family: Verdana, sans-serif !important;font-size:17px;" align="center" bgcolor="#ffffff"><strong style="font-weight: 500;">'.$commissionPercentage.'% </strong></td>
                            <td style=" padding: 12px 0px;color: #01104e;font-family: Verdana, sans-serif !important;font-size:17px;" align="center" bgcolor="#ffffff"><strong style="font-weight: 500;">AED '.$totalEarnings.' </strong></td>

                          
                        </tr>
                        </tbody>
                      </table>
                </td>
              </tr>
            
       
              <tr>
                <td class="column-one c-f">
                 <br>
                  <p style="font-size:16px;font-weight: 00!important;font-family: Verdana, sans-serif !important;margin: 3px auto;padding: 0 4%;border-radius: 8px;">
                    <!-- Agent Point Transaction -->
                   Your '.$currentMonthText1.' '.$currentYear.' commission points have been successfully transferred to your agent wallet.
                  </p>
                  <br>
                </td>
              </tr>
         
            <tr>
                <td class="column-one">
                   <img style="width: !important;margin-top: 10px;" src="https://nationalasset.blr1.digitaloceanspaces.com/nationaldraw/1/ndFooter.png" width="84%">
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
        
            $messages2 = mysqli_real_escape_string($con, $messages);
        
            $insertlog = mysqli_query($con, "INSERT INTO `emaillog` (`details`,`subject`,`email`,`ip`,`datetime`,`status`) VALUES ('$messages2','$subject','$email','','$dubaidate_time','0')");
                                                
                                                if ($insertlog) {
                                                    $id12 = mysqli_insert_id($con);
                                                 
                                                    //   var_dump($id12); die;
                                                    
                                                    if($id12 !=''){
        
                                                        $result['message'] = 'Agent point transaction settlement successfully';
                                                        $result['type'] = 1;
                                                        goto GHi1;
                                                        // echo json_encode(array("type" => 1, "status" => "success", "message" => "Agent settlement updated successfully"));
                                                        
        
                                                        
                                                    } else {
        
                                                        $result['message'] = 'Could Not Send Email';
                                                        $result['type'] = 0;
                                                        goto GHi1;
                                                        // echo 'Could Not Send Email';
                                                    }
                                                    
                                                    
                                                } else {
                                                    // Handle the error if the query failed
                                                    echo "Error: " . mysqli_error($con);
                                                }
        
                        
                    } else {
        
                        $result['message'] = 'Error updating method type.';
                        $result['type'] = 0;
                        goto GHi1;
                        // echo json_encode(array("type" => 0, "status" => "error", "message" => "Error updating method type"));
                    }
                }
            }
        }
        // var_dump($method_type);die;



    }


    GHi1:
    echo json_encode($result);
} else if ($method == 'withdraw_transfer_verify') {

    // var_dump('jesdjfhaesjk');die;

    $result = [];
    $request = $_POST['request'];

    // var_dump($request);die;
    $wd_data = select_query($con, "agent_settlement", "", "`id`='$request' and `status`='0' and `deletes`='0'", "", "");
    if ($wd_data['nr'] > 0) {

        $uname = $wd_data['result'][0]['uname'];
        $umobile = $wd_data['result'][0]['umobile'];
        $uemail = $wd_data['result'][0]['uemail'];
        $totalSales = $wd_data['result'][0]['totalSales'];
        $commissionPercentage = $wd_data['result'][0]['commissionPercentage'];
        $totalEarnings = $wd_data['result'][0]['totalEarnings'];


        $cus_prefer = $wd_data['result'][0]['cus_prefer'];
        $from_name1 = select_top_name($con, "user_register", "name", "`id`='$from_id' and `deletes`='0'", "name", "");

        $output = '';

        $output .= '<form class="login100-form validate-form">
                        <div class="row" style="justify-content: center;">
                                <div class="row mb-3">
                                         <div class="col-6">
                                                     <p class="mdtext">Agent Name </p>
                                            </div>
                                            <div class="col-6">
                                                        <p>' . $uname . '</p>
                                            </div>
                                </div>
                                <div class="row mb-3">
                                         <div class="col-6">
                                                     <p class="mdtext">Mobile Number</p>
                                            </div>
                                            <div class="col-6">
                                                        <p> ' . $umobile . '</p>
                                            </div>
                                </div>
                                <div class="row mb-3">
                                         <div class="col-6">
                                                     <p class="mdtext">Email ID</p>
                                            </div>
                                            <div class="col-6">
                                                        <p> ' . $uemail . '</p>
                                            </div>
                                </div>
                                 <div class="row mb-3">
                                         <div class="col-6">
                                                     <p class="mdtext">Agent total sales</p>
                                            </div>
                                            <div class="col-6">
                                                        <p>' . $totalSales . '</p>
                                            </div>
                                </div>
                                 <div class="row mb-3">
                                         <div class="col-6">
                                                     <p class="mdtext">Commission %</p>
                                            </div>
                                            <div class="col-6">
                                                        <p> ' . $commissionPercentage . '%</p>
                                            </div>
                                </div>
                                 <div class="row mb-3">
                                         <div class="col-6">
                                                     <p class="mdtext">Commission AED</p>
                                            </div>
                                            <div class="col-6">
                                                        <p>' . $totalEarnings . ' AED</p>
                                            </div>
                                </div>
                                
                                 <div class="row mb-3">
                                             <div class="col-6">
                                         <p class="mdtext">Transaction Mode</p>
                                        </div>
                                        <div class="col-6">
                                        <select class="form-select" id="transactionmode">
                                            <option value="">Select Mode</option>
                                            <option value="Paid Cash">Paid Cash</option>
                                            <option value="Point Transaction">Point Transaction</option>
                                        </select>
                                        </div>
                                </div>
                                <div class="row mb-3">
                                    <div id="erterNre1"></div>
                                    <textarea id="reasontext" placeholder="Reason"></textarea>
                                </div>
                                </form>';





        $withdraw = '<button class="btn btn-primary" onclick="transferwdamount(' . "'$request'" . ')">Transfer</button>';
        $result['type'] = '1';
        $result['result'] = $output;
        $result['withdraw'] = $withdraw;
    } else {

        $result['type'] = '0';

        $result['result'] = '<div class="alert alert-danger" role="alert">Request Not Found!</div>';
    }



    echo json_encode($result);
}
