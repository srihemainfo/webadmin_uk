<?php


include '../../include/shi-config.php';
include '../../include/functions.php';

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$memid = $_SESSION['memid'];

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";

$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";

$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";

$roleID = $_REQUEST['role'] ?? '';

if ($type == 'agent') {
    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($roleID) AND" : "";
} else {
    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$roleID' AND" : "";
}

$headers = apache_request_headers();



$result = array();

$post_csrf = $headers['X-Csrf-Token'] ?? '';




if ($method == "list_agent") {





    $roll_id = select_top_name($rcon, "user_register", "roll_id", "`id`='$memid' and `deletes`='0'  order by `id` DESC ", "roll_id", "");
    // var_dump($roll_id);die;

    if ($roll_id != 1 && $roll_id != 2 && $roll_id != 4 && $roll_id != 11 && $roll_id != 8 && $_SESSION['memid'] != '8236') {
        $condition = "`created_by` = '$memid' AND";
    } else {
        $condition = "";
    }
    $datefill = BlockSQLInjectionforagent($_POST["datefill"]);
    $datefrom = BlockSQLInjectionforagent($_POST["datefrom"]);
    // $datefill = $_POST['datefill'];
    // $datefrom = $_POST['datefrom'];
    
    $now = date('Y-m-d');

    if ($datefrom != '' && $datefill != '') {
        $contype = "`created_at` BETWEEN '$datefrom 00:00:00' AND '$datefill 23:59:59' AND";
    } else {
        $contype = "`created_at` LIKE '%$now%' AND";
        // $contype = '';
    }
    
    // var_dump($contype);die;
    $fieldname = BlockSQLInjectionforagent($_POST["fieldname"]);

    // $fieldname = $_POST['fieldname'];

    if ($fieldname != '') {
        $contype = '';
        $fieldcon = "(`email` LIKE '%" . $fieldname . "%' OR `name` LIKE '%" . $fieldname . "%' OR `mobile` LIKE '%" . $fieldname . "%' )  AND ";
    } else {
        $fieldcon = "";
    }
    // $deletedstatus = BlockSQLInjectionforagent($_POST["deletedstatus"]);

    $deletedstatus = $_POST['deletedstatus'];
    if ($deletedstatus != '') {
        $deletecon = "`deletes`='$deletedstatus'";
    } else {
        $deletecon = "`deletes`='Deleted'";
    }
    
      $type = $_POST['type'];
    
    
    
    if($type == 'agent'){
        
        $roll_id1 = "`roll_id`=3 AND ";
        
    } else if($type == 'staffs'){
        $roll_id1 = "`roll_id`= 4 AND ";
    }

    $user_register = select_query($rcon, "user_register", "", "$condition $role $contype $fieldcon $roll_id1 $deletecon order by `id` DESC", "", "");

    if ($user_register['nr'] > 0) {

        foreach ($user_register['result'] as $key => $value) {
            $FRONT_img = select_query($rcon, "user_images", "", "`user_id`='$value[id]' and `img_url` != '' and `type` = 'FRONT' and `status`='0' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
            $BACK_img = select_query($rcon, "user_images", "", "`user_id`='$value[id]' and `img_url` != '' and `type` = 'BACK' and `status`='0' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
            $deletes = $value['deletes'];
            
            // var_dump($roll_id);die;


$action ='';
            if ($roll_id == 1) {

                if ($value['roll_id'] == 0) {

                    $action = '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye" style="color: #49117cc9;font-size: 16px;font-weight: bold;">&nbsp;Profile</span></a>&nbsp;&nbsp;';
                    if ($value['img_url'] != '' || $FRONT_img['result'][0]['img_url'] != '' || $BACK_img['result'][0]['img_url'] != '') {
                        $action .= '<a class="btn text-danger btn-sm"   style="cursor: pointer;" onclick=previewp(' . $value['id'] . ') ><span class="fa fa-eye" ></span>&nbsp;Profile Image</a>&nbsp;&nbsp;';
                    }

                    if (intval($deletes) != 1) {
                        $action .= '<a class="btn text-danger btn-sm" data-bs-target="#deleteinfo" data-bs-toggle="modal" data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2" style="color: #ff0000;font-size: 16px;font-weight: bold;" onclick=deletelist(' . $value['id'] . ')></span></a>&nbsp;&nbsp;';
                    }
                } else if (in_array($value['roll_id'], [6, 7, 9, 11])) {
                    $action = '<a href="' . $adminurl . 'profile/edit/permission/' . $value['id'] . '" style="cursor: pointer;"><span class="fe fe-edit" style="color: #576025db; font-size: 16px;font-weight: bold;">&nbsp;Permission</span></a>&nbsp;&nbsp;';
                    $action .= '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye" style="color: #49117cc9;font-size: 16px;font-weight: bold;">&nbsp;Profile</span></a>&nbsp;&nbsp;';
                    if ($value['roll_id'] == 7) {
                        if ($value['status'] != 1) {
                            $action .= '<a onclick="suspendaffiliate(' . "'$value[id]'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #3c9306;font-size: 16px;font-weight: bold;">&nbsp;Active</span></a>&nbsp;&nbsp;';
                        } else {
                            $action .= '<a onclick="unsuspendaffiliate(' . "'$value[id]'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #ff7c0b;font-size: 16px;font-weight: bold;">&nbsp;Inactive</span></a>&nbsp;&nbsp;';
                        }

                        if (intval($deletes) != 1) {
                            $action .= '<a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2" style="color: #ff0000;font-size: 16px;font-weight: bold;" onclick=affiliatedelete(' . $value['id'] . ')></span></a>';
                        }
                    }
                    if ($value['roll_id'] == 6) {


                        if (intval($deletes) != 1) {
                            $action .= '<a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2" style="color: #ff0000;font-size: 16px;font-weight: bold;" onclick=affiliatedelete(' . $value['id'] . ')></span></a>';
                        }
                    }
                }
                else  if ($value['roll_id'] == 3) {


                    // $action .= '<a href="' . $adminurl . 'profile/edit/permission/' . $value['id'] . '" style="cursor: pointer;"><span class="fe fe-edit" style="color: #576025db;font-size: 16px;font-weight: bold;">&nbsp;Permission</span></a>&nbsp;&nbsp;';
                    $action .= '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye" style="color: #49117cc9;font-size: 16px;font-weight: bold;">&nbsp;Profile</span></a>&nbsp;&nbsp;';
                    if ($value['status'] != 1) {
                        $action .= '<a onclick="suspendagent(' . "'$value[id]'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #3c9306;font-size: 16px;font-weight: bold;">&nbsp;Active</span></a>&nbsp;&nbsp;';
                    } else {
                        $action .= '<a onclick="unsuspendagent(' . "'$value[id]'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #ff7c0b;font-size: 16px;font-weight: bold;">&nbsp;Inactive</span></a>&nbsp;&nbsp;';
                    }

                    if (intval($deletes) != 1) {
                        $action .= '<a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2" style="color: #ff0000;font-size: 16px;font-weight: bold;" onclick=agentdelete(' . $value['id'] . ')></span></a>';
                    }
                }
                 else  if ($value['roll_id'] == 4) {


                    $action .= '<a href="' . $adminurl . 'profile/edit/permission/' . $value['id'] . '" style="cursor: pointer;"><span class="fe fe-edit" style="color: #576025db;font-size: 16px;font-weight: bold;">&nbsp;Permission</span></a>&nbsp;&nbsp;';
                    $action .= '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye" style="color: #49117cc9;font-size: 16px;font-weight: bold;">&nbsp;Profile</span></a>&nbsp;&nbsp;';
                    if ($value['status'] != 1) {
                        $action .= '<a onclick="suspendagent(' . "'$value[id]'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #3c9306;font-size: 16px;font-weight: bold;">&nbsp;Active</span></a>&nbsp;&nbsp;';
                    } else {
                        $action .= '<a onclick="unsuspendagent(' . "'$value[id]'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #ff7c0b;font-size: 16px;font-weight: bold;">&nbsp;Inactive</span></a>&nbsp;&nbsp;';
                    }

                    if (intval($deletes) != 1) {
                        $action .= '<a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2" style="color: #ff0000;font-size: 16px;font-weight: bold;" onclick=agentdelete(' . $value['id'] . ')></span></a>';
                    }
                }
            } else {

                if ($value['roll_id'] == 0) {
                    $action = '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye" style="color: #49117cc9;font-size: 16px;font-weight: bold;">&nbsp;Profile</span></a>&nbsp;&nbsp;';
                    // $action = '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye"></span></a>';
                    if ($value['img_url'] != '' || $FRONT_img['result'][0]['img_url'] != '' || $BACK_img['result'][0]['img_url'] != '') {
                        // $action .= '<a class="btn text-danger btn-sm"   style="cursor: pointer;" onclick=previewp(' . $value['id'] . ') ><span class="fa fa-eye" ></span>&nbsp;Preview</a>';
                        $action .= '<a class="btn text-danger btn-sm"   style="cursor: pointer;" onclick=previewp(' . $value['id'] . ') ><span class="fa fa-eye" ></span>&nbsp;Preview</a>&nbsp;&nbsp;';
                    }
                } else if (in_array($value['roll_id'], [6, 7, 9, 11])) {
                    $action = '';
                } else if (in_array($value['roll_id'], [3, 4, 5])) {
                    // $action = '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye"></span></a>';
                    $action = '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye" style="color: #49117cc9;font-size: 16px;font-weight: bold;">&nbsp;Profile</span></a>&nbsp;&nbsp;';
                    if ($roll_id == 2) {
                        if ($value['status'] != 1) {
                            $action .= '<a onclick="suspendagent(' . "'$value[id]'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #3c9306;font-size: 16px;font-weight: bold;">&nbsp;Active</span></a>&nbsp;&nbsp;';
                            // $action .= '&nbsp;<a onclick="suspendagent(' . "'$value[id]'" . ')" style="cursor: pointer; color: #ff7c0b;"><span class="fa fa-lightbulb-o">Active</span></a>';
                        } else {
                            $action .= '<a onclick="unsuspendagent(' . "'$value[id]'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #ff7c0b;font-size: 16px;font-weight: bold;">&nbsp;Inactive</span></a>&nbsp;&nbsp;';
                            // $action .= '&nbsp;<a onclick="unsuspendagent(' . "'$value[id]'" . ')" style="cursor: pointer; color: #020202;"><span class="fa fa-lightbulb-o">Inactive</span></a>';
                        }
                    }
                }
            }

            $result[] = ["buildinglocation" => utf8_encode($value['address']), "Country" => utf8_encode($value['nationality']), "City" => utf8_encode($value['city']), "residinglocation" => $value['residinglocation'], "createdat" => $value['created_at'], "action" => $action, "id" => $value['id'], "nid" => str_pad($value['id'], 7, "0", STR_PAD_LEFT), "rollType" => $value['user'], "name" => $value['name'] . ' ' . $value['lname'], "t_point" => $value['t_point'], "walletBalance" => $value['walletBalance'], "mobile" => $value['mobile'], "passport" => utf8_encode($value['passport']), "email" => $value['email'], "dob" => $value['dob'], "password" => $value['password']];
        }
    }

    echo json_encode($result);
}
else if ($method == "list_oticket") {

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
        
      
        // $NDticketquery = mysqli_query($con, "SELECT nd.*, ur.name, ur.lname, ur.mobile, ur.email, CONCAT(ur2.name, ' ', ur2.lname) AS agentName,
        //     (SELECT product_id FROM invoice WHERE invoice.ticketId = nd.id LIMIT 1) as Product,
        //     (SELECT rate FROM product WHERE product.id = Product LIMIT 1) as Product_rate
        //     FROM `ndticket` nd
        //     JOIN `user_register` ur ON nd.userId = ur.id 
           
        //     LEFT JOIN `user_register` ur2 ON nd.agentId = ur2.id OR (nd.agentId = '' AND ur2.id = '')
        //     WHERE $contype $userType nd.id != '' AND nd.deletes != '1'");
        $NDticketquery = mysqli_query($con, "SELECT 
            nd.id, nd.referenceID,nd.agentId, nd.grandtotal,nd.invoiceNo, nd.ticketNo, nd.raffleIds, 
            nd.purchaseDatetime, nd.startDate, nd.endDate, 
            ur.name, ur.lname, ur.mobile, ur.email, 
            CONCAT(ur2.name, ' ', ur2.lname) AS agentName, 
            invoice.product_id AS Product, 
            product.rate AS Product_rate 
        FROM 
            `ndticket` nd 
            JOIN `user_register` ur ON nd.userId = ur.id 
            LEFT JOIN `user_register` ur2 ON nd.agentId = ur2.id OR (nd.agentId = '' AND ur2.id = '') 
            LEFT JOIN `invoice` ON invoice.ticketId = nd.id
            LEFT JOIN `product` ON product.id = invoice.product_id
        WHERE 
            $contype $userType nd.id != '' 
            AND nd.deletes != '1';");
         
           
            
        
          
       
    // }
    if (mysqli_num_rows($NDticketquery) > 0) {
        $result = mysqli_fetch_all($NDticketquery, MYSQLI_ASSOC);
    }



    echo json_encode($result);

    
}else if ($method == "Ticket_History") {

      // $result = [];

    $contype = '';

    

    $da = '';

    $now = date('Y-m-d');
    $ticketID = $_POST['ticketID'];
    
    // var_dump($ticketID);die;
    if($userType1 == 'customer'){
        
        $userType='0';
        
    }else{
        
    }
    $formdate = BlockSQLInjectionforagent($_POST["formdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    // $formdate = $_POST['formdate'];

    // $todate = $_POST['todate'];
    
    // var_dump($formdate,$todate);die;

    if ($formdate != '' && $todate != '') {

        $da = 'DESC';

        $fd = date("Y-m-d", strtotime($formdate));

        $td = date("Y-m-d", strtotime($todate));

       
        $contype = "T.`purchaseDatetime` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {

        $da = 'DESC';

        
        // $contype = "T.`purchaseDatetime` LIKE '%$now%' AND";
        $contype = " ";
    }
    
//   var_dump('hiiiiii');die;
       
        
        // $NDticketquery = mysqli_query($con, "SELECT I.netTotal,I.createdon, I.renewalStatus,I.startDate,I.endDate, T.ticketNo, T.raffleIds
        //     FROM ndticket AS T
        //     JOIN `invoice` AS I ON I.ticketId = T.id
        //     WHERE T.id = $ticketID");
        
        $NDticketquery = mysqli_query($con, "SELECT I.id,I.netTotal,I.cart, I.createdon,I.ticketReferenceID,I.payment_transaction_id,T.referenceID, I.renewalStatus, I.startDate, I.endDate, I.deliveryType, I.delivery_status, T.ticketNo, T.raffleIds, CASE WHEN I.netTotal <= 7 THEN 'New Purchased' WHEN I.netTotal >= 10 AND I.netTotal <= 300 THEN 'Monthly' ELSE 'Yearly' END AS netTotalStatus FROM ndticket AS T JOIN `invoice` AS I ON I.ticketId = T.id WHERE $contype T.id = $ticketID");
                
      
    
    if (mysqli_num_rows($NDticketquery) > 0) {
        $result = mysqli_fetch_all($NDticketquery, MYSQLI_ASSOC);
    }
    
    // var_dump($result);die;



    echo json_encode($result);

    
}else if ($method == "update_delivery_status") {
    
    // var_dump('kinmg');die;

    $rowId = BlockSQLInjectionforagent($_POST["id"]);
    $newStatus = BlockSQLInjectionforagent($_POST["status"]);
    // $formdate = $_POST['formdate'];

    // $todate = $_POST['todate'];
    
    // var_dump($newStatus);die;
//   $updatedon = date('Y-m-d H:i:s');

$updateQuery = "UPDATE invoice SET delivery_status = '$newStatus', updatedon = '$dubaidate_time' WHERE id = '$rowId'";

  if (mysqli_query($con, $updateQuery)) {
        echo json_encode(array("type" => 1,"status" => "success", "message" => "Delivery status updated successfully"));
    } else {
        echo json_encode(array("type" => 0,"status" => "error", "message" => "Error updating delivery status"));
    }
        
   

    
}else if ($method == "ND_invoice") {
//  var_dump('hellow');die;
    // $result = [];

    $contype = '';

    $type = 'OT';

    $da = '';
    $userType1 = '';
    $userType = '';
    $draw_Con = '';

    $now = date('Y-m-d');
    $userType1 = $_POST['userType'];
    
    // var_dump($userType1);die;
    
$selectedProductId = $_POST['selectedProductId']; 

if(isset($_POST["selectedProductId"]) && $_POST["selectedProductId"] != ''){
    $productRate = "nd.`product_id` = '". $_POST["selectedProductId"] ."' AND";
}
// var_dump($productRate);die;


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
        
        $contype = "nd.`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $contype = '';
    }
    
    if($userType1 == 'New'){
        
        $userType="nd.`renewalStatus`= 'NEW' AND";
        
    } else if($userType1 == 'Renewal'){
         $userType="nd.`renewalStatus`= 'RENEWAL' AND";
    } else if($userType1 == 'Wallet'){
         $userType="nd.`paymentType`= 'Wallet' AND";
    }else if($userType1 == 'Agent'){
         $userType="nd.`agent_id`!= '' AND"; 
    } 
    else{
        $userType = '';
    }
    
    if ($searchTxt != '') {
            $draw_Con = "(nd.firstname LIKE '%$searchTxt%' OR nd.emailid LIKE '%$searchTxt%' OR nd.mobile LIKE '%$searchTxt%') AND ";
    }
    
    if($searchTxt == '' && $userType1 =='' && $formdate == '' && $todate == ''){
        
         $da = 'DESC';
        $contype = "nd.`createdon` LIKE '%$now%' AND";
    }
    
   
        // var_dump("SELECT nd.*, CONCAT(ur2.name, ' ', ur2.lname) AS agentName,            
        //     (SELECT rate FROM product WHERE product.id = product_id LIMIT 1) as Product_rate
        //     FROM `invoice` nd
        //     JOIN `user_register` ur ON nd.user_id = ur.id            
        //     LEFT JOIN `user_register` ur2 ON nd.agent_id = ur2.id OR (nd.agent_id = '' AND ur2.id = '')
        //     WHERE $contype $userType $draw_Con $productRate nd.id != '' AND nd.deletes != '1' ORDER BY nd.id DESC;");die;
      
        
        $NDticketquery = mysqli_query($con, "SELECT nd.*, CONCAT(ur2.name, ' ', ur2.lname) AS agentName,            
            (SELECT rate FROM product WHERE product.id = product_id LIMIT 1) as Product_rate
            FROM `invoice` nd
            JOIN `user_register` ur ON nd.user_id = ur.id            
            LEFT JOIN `user_register` ur2 ON nd.agent_id = ur2.id OR (nd.agent_id = '' AND ur2.id = '')
            WHERE $contype $userType $draw_Con $productRate nd.id != '' AND nd.deletes != '1' ORDER BY nd.id DESC;");
            
          
        
          

    if (mysqli_num_rows($NDticketquery) > 0) {
        $result = mysqli_fetch_all($NDticketquery, MYSQLI_ASSOC);
    }



    echo json_encode($result);

    
}else if ($method == "Deleted_Request") {
//  var_dump('hellow');die;
    // $result = [];

    $contype = '';

   
    $draw_Con = '';
    $user_Status = '';

    $now = date('Y-m-d');
    // $userType1 = $_POST['userType'];
    
    // var_dump($userType1);die;
    
$selectedProductId = $_POST['selectedProductId']; 




    $formdate = BlockSQLInjectionforagent($_POST["agdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    $searchTxt = BlockSQLInjectionforagent($_REQUEST["search_id"]);
    $userType = BlockSQLInjectionforagent($_REQUEST["userType"]);
    
    // var_dump($userType);die;
    // $formdate = $_POST['formdate'];

    // $todate = $_POST['todate'];
    
    // var_dump($productRate,$userType1);die;
    

    if ($formdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        
        $contype = "d.`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $contype = '';
    }
    
  
    
    if ($searchTxt != '') {
            $draw_Con = "(ur.name LIKE '%$searchTxt%' OR ur.email LIKE '%$searchTxt%' OR ur.mobile LIKE '%$searchTxt%') AND ";
    }
    if($userType != ''){
        $user_Status = "d.status= '$userType' AND";
    }
    // if($searchTxt == '' && $userType1 =='' && $formdate == '' && $todate == ''){
        
    //      $da = 'DESC';
    //     $contype = "d.`createdon` LIKE '%$now%' AND";
    // }
    
   
  
       
      
        
        $DeletedRequest = mysqli_query($con, "SELECT d.account_expire_date,d.deleted_by, d.deleted_at, d.status, d.createdon, ur.name, ur.lname, ur.mobile, ur.email
        FROM `user_delete_request` d
        JOIN `user_register` ur ON d.requestID = ur.id
        WHERE $contype $draw_Con $user_Status ur.id != '';
        ");
            
          
       
          

    if (mysqli_num_rows($DeletedRequest) > 0) {
        while ($row = mysqli_fetch_assoc($DeletedRequest)) {
            
            $del_by = $row['deleted_by'];

            if ($del_by !== null) {
                $deleted_By_name = select_top_name($con, "user_register", "name", "`id`=$del_by", "name", "");
            } else {
                $deleted_By_name = "No specific user";
            }


           
            
            
            
            $result[]= [
                    
                    "firstname" => $row['name'],
                    "lastname" => $row['lname'],
                    "mobile" => $row['mobile'],
                    "emailid" => $row['email'],
                    "createdon" => $row['createdon'],
                    "status" => $row['status'],
                     "deleted_at" => $row['deleted_at'] ? $row['deleted_at'] : "Not deleted",
                    "account_expire_date" => $row['account_expire_date'],
                    "Deleted_BY_name" => $deleted_By_name,
                    // "monthlyactiveCount" => $monthlyactiveCount,
                ];
        }
    }


// var_dump($result);die;
    echo json_encode($result);

    
}else if ($method == "WalletPurchase") {
//  var_dump('hellow');die;
    // $result = [];

    $contype = '';

    $type = 'OT';

    $da = '';
    $userType1 = '';
    $userType = '';
    $draw_Con = '';

    $now = date('Y-m-d');
    $userType1 = $_POST['userType'];
    
    // var_dump($userType1);die;
    
$selectedProductId = $_POST['selectedProductId']; 

if(isset($_POST["selectedProductId"]) && $_POST["selectedProductId"] != ''){
    $productRate = "nd.`product_id` = '". $_POST["selectedProductId"] ."' AND";
}
// var_dump($productRate);die;


    $formdate = BlockSQLInjectionforagent($_POST["agdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    $searchTxt = BlockSQLInjectionforagent($_REQUEST["search_id"]);
    // $formdate = $_POST['formdate'];

    // $todate = $_POST['todate'];
    
    // var_dump($formdate,$todate);die;

    if ($formdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        
        $contype = "nd.`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $contype = '';
    }
    
    if($userType1 == 'New'){
        
        $userType="nd.`renewalStatus`= 'NEW' AND";
        
    } else if($userType1 == 'Renewal'){
         $userType="nd.`renewalStatus`= 'RENEWAL' AND";
    } else if($userType1 == 'PICKUPTOSTORE'){
         $userType="nd.`renewalStatus`= 'PICKUPTOSTORE' AND";
    }else if($userType1 == 'DELIVERYTOCUSTOMER'){
         $userType="nd.`renewalStatus`= 'DELIVERYTOCUSTOMER' AND"; 
    } 
    else{
        $userType = '';
    }
    
    if ($searchTxt != '') {
            $draw_Con = "(nd.firstname LIKE '%$searchTxt%' OR nd.emailid LIKE '%$searchTxt%' OR nd.mobile LIKE '%$searchTxt%') AND ";
    }
    if($searchTxt == '' && $userType1 =='' && $formdate == '' && $todate == ''){
        
         $da = 'DESC';
        $contype = "nd.`createdon` LIKE '%$now%' AND";
    }
    
   
      
      
        
        $NDticketquery = mysqli_query($con, "SELECT nd.*,            
            (SELECT rate FROM product WHERE product.id = product_id LIMIT 1) as Product_rate
            FROM `invoice` nd
            JOIN `user_register` ur ON nd.user_id = ur.id            
            
            WHERE $contype $userType $draw_Con $productRate nd.id != '' AND nd.paymentType = 'Wallet' AND nd.agent_id = '0' AND nd.deletes != '1' ORDER BY nd.id DESC;");
            
        //   LEFT JOIN `user_register` ur2 ON nd.agent_id = ur2.id OR (nd.agent_id = '' AND ur2.id = ''), CONCAT(ur2.name, ' ', ur2.lname) AS agentName
        
          

    if (mysqli_num_rows($NDticketquery) > 0) {
        
        while ($row = mysqli_fetch_assoc($NDticketquery)) {
            
            $invoice_id = $row['id'];
            
            $ticketId = $row['ticketId'];
            
            $payment_id = select_top_name($con, "payment_history", "id", "`invoice_no`=$invoice_id ", "id", "");
            
            $ticketNo = select_top_name($con, "ndticket", "ticketNo", "`id`=$ticketId ", "ticketNo", "");
            
            $wallet_opening_balance = select_top_name($con, "wallet_history", "opening_balance", "`reference_id`=$payment_id ", "opening_balance", "");
            $wallet_total = select_top_name($con, "wallet_history", "total", "`reference_id`=$payment_id ", "total", "");
            $wallet_closeing_balance = select_top_name($con, "wallet_history", "closeing_balance", "`reference_id`=$payment_id ", "closeing_balance", "");
            
            // var_dump($wallet_closeing_balance);die;
            
            
            
            $result[]= [
                    "payment_transaction_id" => $row['payment_transaction_id'],
                    "firstname" => $row['firstname'],
                    "lastname" => $row['lastname'],
                    "resultdate" => $row['resultDate'],
                    "mobile" => $row['mobile'],
                    "emailid" => $row['emailid'],
                    "createdon" => $row['createdon'],
                    "Product_rate" => $row['Product_rate'],
                    "grandtotal" => $row['grandtotal'],
                    "quantity" => $row['quantity'],
                    "paymentType" => $row['paymentType'],
                    "renewalStatus" => $row['renewalStatus'],
                    "id" => $row['id'],
                    "ticketReferenceID" => $row['ticketReferenceID'],
                    "id" => $row['id'],
                    "ticketNo" => $ticketNo,
                    "cart" => $row['cart'],
                    "wallet_opening_balance" => $wallet_opening_balance,
                    "wallet_total" => $wallet_total,
                    "wallet_closeing_balance" => $wallet_closeing_balance,
                    // "monthlyactiveCount" => $monthlyactiveCount,
                ];
        }
        // $result = mysqli_fetch_all($NDticketquery, MYSQLI_ASSOC);
    }

// var_dump($result);die;

    echo json_encode($result);

    
}else if ($method == "Agent_Ticket_list") {

    // $result = [];
    
    

    $contype = '';

    $type = 'OT';

    $da = '';

    $now = date('Y-m-d');
    $userType1 = $_POST['userType'];
    
    // var_dump($userType);die;
    if($userType1 == 'customer'){
        
        $userType='0';
        
    }else{
        
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
    
    $AgentID = BlockSQLInjectionforagent($_POST["userId"]);
    
    if($userType1 == 'agent'){
        
       
    
    $NDticketquery = mysqli_query($con, "SELECT nd.*, ur.name, ur.lname, ur.mobile, ur.email,ur2.name AS agentName FROM `ndticket` nd JOIN `user_register` ur ON nd.userId = ur.id JOIN `user_register` ur2 ON nd.agentId = ur2.id WHERE " . $contype . " nd.agentId != 0 AND nd.id != '' AND nd.deletes != '1'");
    
    } else{
        
       
        
        $NDticketquery = mysqli_query($con, "SELECT 
            nd.*, 
            ur.name, 
            ur.lname, 
            ur.mobile, 
            ur.email, 
            (SELECT product_id FROM invoice WHERE invoice.ticketId = nd.id LIMIT 1) as Product, 
            (SELECT rate FROM product WHERE product.id = Product LIMIT 1) as Product_rate, 
            '' as agentName 
        FROM 
            `ndticket` nd 
        JOIN 
            `user_register` ur ON nd.userId = ur.id 
        WHERE 
            $contype
            nd.agentId = $AgentID 
            AND nd.id != '' 
            AND nd.deletes != '1'");
            
          
       
    }
    if (mysqli_num_rows($NDticketquery) > 0) {
        $result = mysqli_fetch_all($NDticketquery, MYSQLI_ASSOC);
    }



    echo json_encode($result);

    
}else if ($method == "archived_ticket") {

  
    

    $formdate = BlockSQLInjectionforagent($_POST["fromdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    
    
    // $formdate = $_POST['fromdate'];
    // $todate = $_POST['todate'];
    
    // var_dump($formdate,$todate);die;

    if ($formdate != '' && $todate != '') {

        $da = 'DESC';

        $fd = date("Y-m-d", strtotime($formdate));

        $td = date("Y-m-d", strtotime($todate));

        // $contype = "`purchaseDatetime` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
        $contype = "nd.`endDate` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {

        $da = 'DESC';

        // $contype = "`purchaseDatetime` LIKE '%$now%' AND";
        $contype = "nd.`endDate` LIKE '%$now%' AND";
    }
    
   
    // var_dump("SELECT nd.*, ur.name, ur.lname, ur.mobile, ur.email, ur2.name AS agentName
    // FROM `ndticket` nd
    // JOIN `user_register` ur ON nd.userId = ur.id
    // JOIN `user_register` ur2 ON nd.agentId = ur2.id
    // WHERE  " . $contype . " nd.deletes != '1'");die;
    
    $NDticketquery = mysqli_query($con, "SELECT nd.*, ur.name, ur.lname, ur.mobile, ur.email, ur2.name AS agentName
    FROM `ndticket` nd
    JOIN `user_register` ur ON nd.userId = ur.id
    JOIN `user_register` ur2 ON nd.agentId = ur2.id
    WHERE  " . $contype . " nd.deletes != '1'");
        
    
    
    // if($userType1 == 'agent'){
        
      
    
    // $NDticketquery = mysqli_query($con, "SELECT nd.*, ur.name, ur.lname, ur.mobile, ur.email,ur2.name AS agentName FROM `ndticket` nd JOIN `user_register` ur ON nd.userId = ur.id JOIN `user_register` ur2 ON nd.agentId = ur2.id WHERE " . $contype . " nd.agentId != 0 AND nd.id != '' AND nd.deletes != '1'");
    
    // } else{
        
      
        
    //     $NDticketquery = mysqli_query($con, "SELECT nd.*, ur.name, ur.lname, ur.mobile, ur.email, '' as agentName FROM `ndticket` nd JOIN `user_register` ur ON nd.userId = ur.id WHERE " . $contype . " nd.agentId = 0 AND nd.id != '' AND nd.deletes != '1' LIMIT 0, 25");
      
    // }
    
    
    
    if (mysqli_num_rows($NDticketquery) > 0) {
        $result = mysqli_fetch_all($NDticketquery, MYSQLI_ASSOC);
    }
// var_dump($result);die;


    echo json_encode($result);

    
}else if ($method == "delete_NDticket") {
    $result = [];

    $transid = $_POST['transid'];
    $message = $_POST['message'];

    // Update ndticket table
    $ndticket_update = update($con, "ndticket", "`id` = '$transid'", ["deletes" => '1', "deleteReason" => $message], "", "", "", "");

    // Update invoice table
    $invoice_update = update($con, "invoice", "`ticketId` = '$transid'", ["deletes" => '1'], "", "", "", "");
    
    // $ndticket_update &&

    if ( $invoice_update) {
        $result["result"] = "Ticket has been Deleted Successfully";
        $result["type"] = '1';
    } else {
        $result["result"] = "Failed to delete ticket";
        $result["type"] = '0';
    }
    // var_dump($result);die;

    echo json_encode($result);
}

// else if ($method == "delete_NDticket") {

//     $result = [];

//     $transid = $_POST['transid'];

//     $message = $_POST['message'];
    
//     // var_dump($transid,$message);die;
    
   
//       $inv_arr = array("deletes" => '1', "deleteReason" => $message);
//       $ND_update = update($con, "ndticket", "`id` = '$transid'", $inv_arr, "", "", "", "");
   
//       $invoce_update = update($con, "invoice", "`ticketId` = '$transid'", "`deletes` = '1'", "", "", "", "");
//       var_dump($invoce_update);die;
//       $result = array();

//         if ($ND_update) {
//             $result["result"] = " Ticket has been Deleted Successfully";
//             $result["type"]='1';
//         } else {
//             $result["result"] = "Failed to delete ticket";
//         }







//     echo json_encode($result);
// }
else if ($method == "sendemailtopurchase") {
    
    // var_dump('kkimhg');die;

    $transid = $_REQUEST['transid'];

    $ottype = $_REQUEST['ottype'];

    $tablename = $_REQUEST['tablename'];

    $result = sendpurchaseemail($con, $transid, $ottype, $tablename, $baseurl, $adminurl, $draw_no, $dubaidate_time);

    echo json_encode($result);
}
else if ($method == "list_oticket_Ticket_History") {

    // $result = [];
    
    
    $ticket_id_H = $_POST['id'];

    
   $NDticketquery = mysqli_query($con, "SELECT * FROM `invoice` WHERE ticketId = '$ticket_id_H';");
   
   

    if (mysqli_num_rows($NDticketquery) > 0) {
        $result = mysqli_fetch_all($NDticketquery, MYSQLI_ASSOC);
    }
// var_dump($result);die;


    echo json_encode($result);

    
}
else if ($method == "list_aticket") {

    // $result = [];

    $contype = '';

    $type = 'AT';

    $da = '';

    $now = date('Y-m-d');
    $formdate = BlockSQLInjectionforagent($_POST["formdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);

    // $formdate = $_POST['formdate'];

    // $todate = $_POST['todate'];

    if ($formdate != '' && $todate != '') {

        $da = 'DESC';

        $fd = date("Y-m-d", strtotime($formdate));

        $td = date("Y-m-d", strtotime($todate));

        $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {

        $da = 'DESC';

        $contype = "`createdon` LIKE '%$now%' AND";
    }



    $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$memid' and `deletes`='0'", "roll_id", "");
    if ($_SESSION['memid'] == '86921') {
        $agent = "(`agent_id` IN (SELECT `id`  FROM `user_register` WHERE `created_by` = $memid AND `roll_id` != '0' AND `deletes` = '0' AND `status` = '0') OR `agent_id` = '$memid') AND";
    } else  if ($roll_id != 1 && $roll_id != 2 && $_SESSION['memid'] != '8236' && $roll_id != 6 && $roll_id != 8 && $roll_id != 11) {

        $agent = "`agent_id` = '$memid' AND";
    } else {

        $agent = "";
    }

    $Ticket_lines = select_query($rcon, "ticket_lines", "", "$contype $agent `type`='$type' and `deletes`='0'", "", "");

    if ($Ticket_lines['nr'] > 0) {

        foreach ($Ticket_lines['result'] as $key => $value) {

            $ticket_id = $value['ticket_id'];

            $proid = $value['product_id'];

            $purchase_datetime = select_top_name($con, "aticket", "purchase_datetime", "`id`='$ticket_id' and `deletes`='0'", "purchase_datetime", "");

            $ticket_no = select_top_name($con, "aticket", "ticket_no", "`id`='$ticket_id' and `deletes`='0'", "ticket_no", "");

            $user_id = select_top_name($con, "aticket", "user_id", "`id`='$ticket_id' and `deletes`='0'", "user_id", "");

            $transaction_id = select_top_name($con, "aticket", "transaction_id", "`id`='$ticket_id' and `deletes`='0'", "transaction_id", "");



            $cusname = select_top_name($con, "user_register", "name", "`id`='$user_id'", "name", "");

            $lname = select_top_name($con, "user_register", "lname", "`id`='$user_id'", "lname", "");

            $mobile = select_top_name($con, "user_register", "mobile", "`id`='$user_id'", "mobile", "");

            $email = select_top_name($con, "user_register", "email", "`id`='$user_id'", "email", "");

            $proamt = select_top_name($con, "product", "rate", "`id`='$proid' and `deletes`='0'", "rate", "");
            $full_cusname =  $cusname . ' ' .  $lname;

            $action = '';

            $action .= '<div class="g-2">';

            $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' . $transaction_id . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span class="fa fa-file-text-o" style="font-size: 18px;"></span></a>';

            $action .= '<a target="_blank" href="' . $baseurl . 'ticket-view/' . $transaction_id . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span class="fa fa-files-o" style="font-size: 18px;"></span></a>';

            $action .= '<a target="_blank" href="https://api.whatsapp.com/send?phone=' . $mobile . '&text=' . $baseurl . 'ticket-view/' . $transaction_id . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span class="fa fa-whatsapp" style="font-size: 18px;"></span></a>';

            if ($_SESSION['memid'] == 1 || $roll_id != 11) {

                $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="font-size: 18px;" onclick="sendemailtopurchase(' . "'$transaction_id'" . ')">Send Email</span></a>';

                $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane"  style="color: #1a73e8;font-size: 18px;" onclick="sendsmstopurchase(' . "'$transaction_id'" . ')">Send Sms</span></a>';

                $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8;font-size: 18px;" onclick="bothemailsms(' . "'$transaction_id'" . ')">Both</span></a>';
                if ($roll_id == 1) {
                    $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-trash" style="color: red;font-size: 18px;" onclick="deleteagticket(' . "'$transaction_id'" . ')"></span></a>';
                }
            }

            $action .= '</div>';

            $agent_id = select_top_name($con, "aticket", "agent_id", "`id`='$ticket_id' and `deletes`='0'", "agent_id", "");

            $agent_name = select_top_name($con, "user_register", "name", "`id`='$agent_id'", "name", "");

            $agent_lname = select_top_name($con, "user_register", "lname", "`id`='$agent_id'", "lname", "");
            $full_name =  $agent_name . ' ' .  $agent_lname;

            $result[] = array("agentname" => $full_name, "action" => $action, "transaction_id" => $transaction_id, "proamt" => $proamt, "RaffleID" => $value['raffle_id'], "My3Numbers" => $value['my3number'], "email" => $email, "mobile" => $mobile, "cusname" => $full_cusname, "ticketno" => $ticket_no, "purdate" => date("d-M-Y g:i a", strtotime($purchase_datetime)));
        }
    }

    echo json_encode($result);
} else if ($method == "list_fticket") {

    // $result = [];

    $contype = '';

    $type = 'FT';

    $da = '';

    $now = date('Y-m-d');
    $formdate = BlockSQLInjectionforagent($_POST["formdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    // $formdate = $_POST['formdate'];

    // $todate = $_POST['todate'];

    if ($formdate != '' && $todate != '') {

        $da = 'DESC';

        $fd = date("Y-m-d", strtotime($formdate));

        $td = date("Y-m-d", strtotime($todate));

        $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {

        $da = 'DESC';

        $contype = "`createdon` LIKE '%$now%' AND";
    }

    // if ($memid != 1) {

    //     $agent = "`agent_id` = '$memid' AND";

    // } else {

    //     $agent = "";

    // }

    $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$memid' and `deletes`='0'", "roll_id", "");

    if ($roll_id != 1 && $roll_id != 2) {

        $agent = "`agent_id` = '$memid' AND";
    } else {

        $agent = "";
    }

    $Ticket_lines = select_query($con, "ticket_lines", "", "$contype $agent `type`='$type' and `deletes`='0'", "", "");

    if ($Ticket_lines['nr'] > 0) {

        foreach ($Ticket_lines['result'] as $key => $value) {

            $ticket_id = $value['ticket_id'];

            $proid = $value['product_id'];

            $purchase_datetime = select_top_name($con, "fticket", "purchase_datetime", "`id`='$ticket_id' and `deletes`='0'", "purchase_datetime", "");

            $ticket_no = select_top_name($con, "fticket", "ticket_no", "`id`='$ticket_id' and `deletes`='0'", "ticket_no", "");

            $user_id = select_top_name($con, "fticket", "user_id", "`id`='$ticket_id' and `deletes`='0'", "user_id", "");

            $transaction_id = select_top_name($con, "fticket", "transaction_id", "`id`='$ticket_id' and `deletes`='0'", "transaction_id", "");

            $cusname = select_top_name($con, "user_register", "name", "`id`='$user_id' and `deletes`='0'", "name", "");
            $lname = select_top_name($con, "user_register", "lname", "`id`='$user_id' and `deletes`='0'", "lname", "");
            $fullname = $cusname . ' ' . $lname;
            $mobile = select_top_name($con, "user_register", "mobile", "`id`='$user_id' and `deletes`='0'", "mobile", "");

            $email = select_top_name($con, "user_register", "email", "`id`='$user_id' and `deletes`='0'", "email", "");

            $proamt = select_top_name($con, "product", "rate", "`id`='$proid' and `deletes`='0'", "rate", "");

            $action = '';

            $action .= '<div class="g-2">';

            // $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' . $transaction_id . '"  class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span style="font-size: 20px !important;" class="fa fa-file-text-o fs-14"></span></a>';

            $action .= '<a target="_blank" href="' . $baseurl . 'ticket-view/' . $transaction_id . '"  class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span style="font-size: 20px !important;" class="fa fa-files-o fs-14"></span></a>';

            if ($_SESSION['memid'] == 1) {
                // $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" onclick="sendemailtopurchase(' . "'$transaction_id'" . ')">Send Email</span></a>';
                // $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8;" onclick="sendsmstopurchase(' . "'$transaction_id'" . ')">Send Sms</span></a>';
                // $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8;" onclick="bothemailsms(' . "'$transaction_id'" . ')">Both</span></a>';
                $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-trash" style="color: red; font-size: 20px !important;" onclick="deletefticket(' . "'$transaction_id'" . ')"></span></a>';
            }
            $action .= '</div>';
            $agent_id = select_top_name($con, "fticket", "agent_id", "`id`='$ticket_id' and `deletes`='0'", "agent_id", "");
            $agent_name = select_top_name($con, "user_register", "name", "`id`='$agent_id' and `deletes`='0'", "name", "");
            $result[] = array("agentname" => $agent_name, "action" => $action, "transaction_id" => $transaction_id, "proamt" => $proamt, "RaffleID" => $value['raffle_id'], "My3Numbers" => $value['my3number'], "email" => $email, "mobile" => $mobile, "cusname" => $fullname, "ticketno" => $ticket_no, "purdate" => date("d-M-Y g:i a", strtotime($purchase_datetime)));
        }
    } else {
    }

    echo json_encode($result);
} elseif ($method == 'sms_report') {
    
    

    $result = [];

    $now = date('Y-m-d');
    $agdate = BlockSQLInjection($_POST["agdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);
    // $agdate = $_POST['agdate'];

    // $todate = $_POST['todate'];

    if ($agdate != '' && $todate != '') {

        $da = 'DESC';

        $fd = date("Y-m-d", strtotime($agdate));

        $td = date("Y-m-d", strtotime($todate));

        $contype = "`datetime` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {

        $da = 'DESC';

        $contype = "`datetime` LIKE '%$now%' AND";
    }
// var_dump($contype);die;
    $gatcon = '';
    $gatewayname = BlockSQLInjection($_POST["gatewayname"]);
    $statusget = BlockSQLInjection($_POST["statusget"]);
    // $gatewayname = $_POST['gatewayname'];
    // $statusget = $_POST['statusget'];
    
    // var_dump($gatewayname);die;
    if ($gatewayname != '') {

        // if ($gatewayname == 'expresso') {
        //     $gatcon = "`gateway` LIKE '$gatewayname' AND ";
        // } 

        // else if ($gatewayname == 'dataslice') {
        //     $gatcon = "`gateway` LIKE '$gatewayname' AND ";
        // } 

        // else 
        if ($gatewayname == 'cequens' || $gatewayname == 'dataslice' || $gatewayname == 'expresso' || $gatewayname == 'precise' || $gatewayname == 'firebase' || $gatewayname == 'twilio' || $gatewayname == 'brandmaster') {

            if ($statusget == 'Unchecked') {
                $gatcon = "`gateway` LIKE '$gatewayname' AND `smsstatus` = '' AND";
            } else if ($statusget == 'All') {
                $gatcon = "`gateway` LIKE '$gatewayname' AND";
            } else {
                $gatcon = "`gateway` LIKE '$gatewayname' AND `smsstatus` LIKE '$statusget' AND";
            }
        }
    }
    // var_dump("SELECT * FROM `smslog` WHERE $gatcon $contype `id`!= '' AND  `gateway` IN ('cequens', 'dataslice', 'expresso', 'precise', 'firebase', 'twilio')");die;

    $SMSquery = mysqli_query($con, "SELECT * FROM `smslog` WHERE $gatcon $contype `id`!= '' AND  `gateway` IN ('cequens', 'dataslice', 'expresso', 'precise', 'firebase', 'twilio','greenapi','brandmaster')");

    if (mysqli_num_rows($SMSquery) > 0) {
        $result = mysqli_fetch_all($SMSquery, MYSQLI_ASSOC);
    }



    echo json_encode($result);
} elseif ($method == 'WhatsApp_report') {

    $result = [];

    $now = date('Y-m-d');
    $agdate = BlockSQLInjection($_POST["agdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);
    // $agdate = $_POST['agdate'];

    // $todate = $_POST['todate'];

    if ($agdate != '' && $todate != '') {

        $da = 'DESC';

        $fd = date("Y-m-d", strtotime($agdate));

        $td = date("Y-m-d", strtotime($todate));

        $contype = "`datetime` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {

        $da = 'DESC';

        $contype = "`datetime` LIKE '%$now%' AND";
    }

    $gatcon = '';
    $gatewayname = BlockSQLInjection($_POST["gatewayname"]);
    $statusget = BlockSQLInjection($_POST["statusget"]);
    // $gatewayname = $_POST['gatewayname'];
    // $statusget = $_POST['statusget'];
    if ($gatewayname != '') {

        // if ($gatewayname == 'expresso') {
        //     $gatcon = "`gateway` LIKE '$gatewayname' AND ";
        // } 

        // else if ($gatewayname == 'dataslice') {
        //     $gatcon = "`gateway` LIKE '$gatewayname' AND ";
        // } 

        // else 
        if ($gatewayname == 'greenapi' or $gatewayname == 'doubleTick' ) {

            if ($statusget == 'Unchecked') {
                $gatcon = "`gateway` LIKE '$gatewayname' AND `smsstatus` = '' AND";
            } else if ($statusget == 'All') {
                $gatcon = "`gateway` LIKE '$gatewayname' AND";
            } else {
                $gatcon = "`gateway` LIKE '$gatewayname' AND `smsstatus` LIKE '$statusget' AND";
            }
        }
    }

    // $SMSquery = mysqli_query($con, "SELECT * FROM `smslog` WHERE $gatcon $contype `id`!= '' AND  `gateway` IN ('greenapi')");
    // var_dump("SELECT * FROM `smslog` WHERE $gatcon $contype `id` != '' AND  `gateway` IN ('greenapi', 'doubleTick')");die;
    $SMSquery = mysqli_query($con, "SELECT * FROM `smslog` WHERE $gatcon $contype `id` != '' AND  `gateway` IN ('greenapi', 'doubleTick')");


    if (mysqli_num_rows($SMSquery) > 0) {
        $result = mysqli_fetch_all($SMSquery, MYSQLI_ASSOC);
    }



    echo json_encode($result);
} elseif ($method == 'email_report') {

    $result = [];
    $now = date('Y-m-d');
    $agdate = BlockSQLInjection($_POST["agdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);

    if ($agdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($agdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "`datetime` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $da = 'DESC';
        $contype = "`datetime` LIKE '%$now%' AND";
    }

    $Emaillog = select_query($rcon, "emaillog", "sendstatus,fromemail,email,ip,subject,datetime,id", " $contype `id`!= '' ", "", "");

    if ($Emaillog['nr'] > 0) {

        foreach ($Emaillog['result'] as $key => $value) {

            $result[] = ["id" => $value['id'], "sendstatus" => $value['sendstatus'], "fromemail" => $value['fromemail'], "email" => $value['email'], "ip" => $value['ip'], "subject" => '<textarea readonly>' . $value['subject'] . '</textarea>', "datetime" => $value['datetime']];
        }
    }

    echo json_encode($result);
} elseif ($method == 'suspended_report') {

    $result = [];

    $now = date('Y-m-d');

    $agdate = $_POST['agdate'];

    $todate = $_POST['todate'];

    if ($agdate != '' && $todate != '') {

        $da = 'DESC';

        $fd = date("Y-m-d", strtotime($agdate));

        $td = date("Y-m-d", strtotime($todate));

        $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {

        $da = 'DESC';

        $contype = "`createdon` LIKE '%$now%' AND";
    }

    $Suspended = select_query($con, "suspended_log", "", " $contype  `id`!= '' ", "", "");

    if ($Suspended['nr'] > 0) {

        foreach ($Suspended['result'] as $key => $value) {

            $result[] = array("userid" => $value['userid'], "type" => $value['type'], "reason" => '<textarea readonly>' . $value['reason'] . '</textarea>', "createdon" => $value['createdon']);
        }
    } else {
    }

    echo json_encode($result);
} elseif ($method == 'bankchange_report') {

    $result = [];

    $now = date('Y-m-d');
    $agdate = BlockSQLInjectionforreportrange($_POST["agdate"]);
    $todate = BlockSQLInjectionforreportrange($_POST["todate"]);


    if ($agdate != '' && $todate != '') {

        $da = 'DESC';

        $fd = date("Y-m-d", strtotime($agdate));

        $td = date("Y-m-d", strtotime($todate));

        $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {

        $da = 'DESC';

        $contype = "`createdon` LIKE '%$now%' AND";
    }

    $bank_change = select_query($con, "bank_change_log", "", " $contype `id`!= '' ", "", "");

    if ($bank_change['nr'] > 0) {

        foreach ($bank_change['result'] as $key => $value) {

            $details = json_decode($value['request'], true);

            $result[] = array("userid" => $value['userid'], "updatetype" => $value['type'], "bankname" => ($details['bank_name'] == '') ? $details['bankname'] : $details['bank_name'], "account_type" => ($details['acctype'] == '') ? $details['account_name'] : $details['acctype'], "accountno" => ($details['accountno'] == '') ? $details['account_no'] : $details['accountno'], "iban" => ($details['ibancode'] == '') ? $details['iban_code'] : $details['ibancode'], "swift" => ($details['swiftcode'] == '') ? $details['swift_code'] : $details['swiftcode'], "currency" => ($details['currencyccode'] == '') ?  ['currency_code'] : $details['currencyccode'], "passport" => ($details['emirites_passport'] == '') ? $details['passport'] : $details['emirites_passport'], "datetime" => $value['createdon']);
        }
    } else {
    }

    echo json_encode($result);
}

///////// NEW //////////
else if ($method == 'monthly_report') {
    try {
        $result = [];
        $fromdate = BlockSQLInjection($_POST["fromdate"]);
        $todate = BlockSQLInjection($_POST["todate"]);

        $fromdate = date("Y-m-d", strtotime($fromdate));
        $todate = date("Y-m-d", strtotime($todate));

        if ($fromdate != '' && $todate != '') {
            $contype = "`createdon` BETWEEN '$fromdate 00:00:00' AND '$todate 23:59:59' AND";

            $total_ticket = [];

            $ticket = select_query($rcon, "ticket", "`id`, `user_id`, `ticket_no`, `net_total`, `createdon`", "$contype `deletes`='0' ORDER BY `id` DESC", "", "");
            if ($ticket['nr'] > 0) {
                foreach ($ticket['result'] as $key => $value) {
                    // $user_register = select_query($con, "user_register", "", "`id`='$value[user_id]' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                    $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' ORDER BY `id` DESC LIMIT 1", "", "");
                    if ($user_register['nr'] > 0) {
                        $result[] = ['agentname' => '', 'ticketno' => $value['ticket_no'], 'cusname' => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], 'mobile' => $user_register['result'][0]['mobile'], 'email' => $user_register['result'][0]['email'], 'proamt' => $value['net_total'], 'purdate' => date('d-m-Y g:i a', strtotime($value['createdon'])), 'date' => $value['createdon']];
                    }
                }
            }

            $aticket = select_query($rcon, "aticket", "`id`, `user_id`, `ticket_no`, `net_total`, `createdon`, `agent_id`", "$contype `deletes`='0' ORDER BY `id` DESC", "", "");
            if ($aticket['nr'] > 0) {
                foreach ($aticket['result'] as $key => $value) {
                    $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                    if ($user_register['nr'] > 0) {
                        $agent_id = $value['agent_id'];
                        $agentName = select_top_name($rcon, "user_register", "name", "`id`='$agent_id'", "name", "");
                        $agentlName = select_top_name($rcon, "user_register", "lname", "`id`='$agent_id'", "lname", "");
                        $agentfulname = $agentName . ' ' . $agentlName;
                        $result[] = ['agentname' => $agentfulname, 'ticketno' => $value['ticket_no'], 'cusname' => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], 'mobile' => $user_register['result'][0]['mobile'], 'email' => $user_register['result'][0]['email'], 'proamt' => $value['net_total'], 'purdate' => date('d-m-Y g:i a', strtotime($value['createdon'])), 'date' => $value['createdon']];
                    }
                }
            }

            $mticket = select_query($rcon, "mticket", "`id`, `user_id`, `ticket_no`, `net_total`, `createdon`", "$contype `deletes`='0' ORDER BY `id` DESC", "", "");
            if ($mticket['nr'] > 0) {
                foreach ($mticket['result'] as $key => $value) {
                    $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                    if ($user_register['nr'] > 0) {
                        $result[] = ['agentname' => '', 'ticketno' => $value['ticket_no'], 'cusname' => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], 'mobile' => $user_register['result'][0]['mobile'], 'email' => $user_register['result'][0]['email'], 'proamt' => $value['net_total'], 'purdate' => date('d-m-Y g:i a', strtotime($value['createdon'])), 'date' => $value['createdon']];
                    }
                }
            }

            $wticket = select_query($rcon, "wticket", "`id`, `user_id`, `ticket_no`, `net_total`, `createdon`", "$contype `deletes`='0' ORDER BY `id` DESC", "", "");
            if ($wticket['nr'] > 0) {
                foreach ($wticket['result'] as $key => $value) {
                    $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                    if ($user_register['nr'] > 0) {
                        $result[] = ['agentname' => '', 'ticketno' => $value['ticket_no'], 'cusname' => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], 'mobile' => $user_register['result'][0]['mobile'], 'email' => $user_register['result'][0]['email'], 'proamt' => $value['net_total'], 'purdate' => date('d-m-Y g:i a', strtotime($value['createdon'])), 'date' => $value['createdon']];
                    }
                }
            }



            $cpticket = select_query($rcon, "cpticket", "`id`, `user_id`, `ticket_no`, `net_total`, `createdon`", "$contype `deletes`='0' ORDER BY `id` DESC", "", "");
            if ($cpticket['nr'] > 0) {
                foreach ($cpticket['result'] as $key => $value) {
                    $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                    if ($user_register['nr'] > 0) {
                        $result[] = ['agentname' => '', 'ticketno' => $value['ticket_no'], 'cusname' => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], 'mobile' => $user_register['result'][0]['mobile'], 'email' => $user_register['result'][0]['email'], 'proamt' => $value['net_total'], 'purdate' => date('d-m-Y g:i a', strtotime($value['createdon'])), 'date' => $value['createdon']];
                    }
                }
            }
            $kticket = select_query($rcon, "kticket", "`id`, `user_id`, `ticket_no`, `net_total`, `createdon`", "$contype `deletes`='0' ORDER BY `id` DESC", "", "");
            if ($kticket['nr'] > 0) {
                foreach ($kticket['result'] as $key => $value) {
                    $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                    if ($user_register['nr'] > 0) {
                        $result[] = ['agentname' => '', 'ticketno' => $value['ticket_no'], 'cusname' => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], 'mobile' => $user_register['result'][0]['mobile'], 'email' => $user_register['result'][0]['email'], 'proamt' => $value['net_total'], 'purdate' => date('d-m-Y g:i a', strtotime($value['createdon'])), 'date' => $value['createdon']];
                    }
                }
            }


            $bpticket = select_query($rcon, "bpticket", "`id`, `user_id`, `ticket_no`, `net_total`, `createdon`", "$contype `deletes`='0' ORDER BY `id` DESC", "", "");
            if ($bpticket['nr'] > 0) {
                foreach ($bpticket['result'] as $key => $value) {
                    $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                    if ($user_register['nr'] > 0) {
                        $result[] = ['agentname' => '', 'ticketno' => $value['ticket_no'], 'cusname' => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], 'mobile' => $user_register['result'][0]['mobile'], 'email' => $user_register['result'][0]['email'], 'proamt' => $value['net_total'], 'purdate' => date('d-m-Y g:i a', strtotime($value['createdon'])), 'date' => $value['createdon']];
                    }
                }
            }
        }
        echo json_encode($result);
    } catch (Exception $e) {
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'datatable_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == 'show_image') {
    try {
        $id = $_POST['id'];
        if ($id != '') {
            $result = [];
            $img_url = select_top_name($con, "user_register", "img_url", "`id`='$id' and `deletes`='0'  order by `id` DESC ", "img_url", "");

            if ($img_url != '') {
                $result['img'][] = (strpos($img_url, "littledraw") === 0) ? $baseurl . $img_url : constant('assetURL') . $img_url;
            }
            $FRONT_img = select_query($con, "user_images", "", "`user_id`='$id' and `img_url` != '' and `type` = 'FRONT' and `status`='0' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
            if ($FRONT_img['result'][0]['img_url'] != '') {
                $result['img'][] = (strpos($img_url, "littledraw") === 0) ? $baseurl . $FRONT_img['result'][0]['img_url'] : constant('assetURL') . $FRONT_img['result'][0]['img_url'];
            }
            $BACK_img = select_query($con, "user_images", "", "`user_id`='$id' and `img_url` != '' and `type` = 'BACK' and `status`='0' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
            if ($BACK_img['result'][0]['img_url'] != '') {
                $result['img'][] = (strpos($img_url, "littledraw") === 0) ?  $baseurl . $BACK_img['result'][0]['img_url'] : constant('assetURL') . $BACK_img['result'][0]['img_url'];
            }

            $i = 1;
            $output = '';
            foreach ($result['img'] as $value) {


                // if (strpos($value, "littledraw") !== 0) {
                //     // echo '"littledraw" is present in the string.';
                //     $value = constant('assetURL') . $value;
                // }

                // var_dump($value);
                // die;

                $output .= '<div><img src="' . $value . '" style="width: 636px;height: 300px;" alt=""></div>';
                $i++;
            }

            $result['type'] = 1;
            $result['result'] = $output;
            echo json_encode($result);
        }
    } catch (Exception $e) {
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'datatable_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == 'digital_market') {

    try {
        // $formdate = $_POST['formdate'];
        // $todate = $_POST['todate'];
        $formdate = BlockSQLInjection($_POST["formdate"]);
        $todate = BlockSQLInjection($_POST["todate"]);
        if ($formdate != '' && $todate != '') {
            $fd = date("Y-m-d", strtotime($formdate));
            $td = date("Y-m-d", strtotime($todate));
            $dateCon = "AND `createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59'";
        }

        // echo "SELECT subid1, COUNT(subid1) as 'totalview'  FROM digital_market WHERE ip IN (SELECT `ip` FROM `digital_market` WHERE `utm_source` = 'metroads' AND `deletes` = '0'  $dateCon GROUP BY ip)  AND `subid1` != '' AND  `deletes` = '0' and (`subid1` like '%metroads%' || `subid1` like '%success%'  || `subid1` like '%play%'   || `subid1` like '%login%'   || `subid1` like '%cart%'   || `subid1` like '%billing%'
        // || `subid1` like '%dashboard%' || `subid1` like '%failed%' || `subid1` like '%thanks%'  )  $dateCon GROUP BY subid1 order by totalview desc";
        // die;

        $sql = mysqli_query(
            $con,
            "SELECT subid1, COUNT(subid1) as 'totalview'  FROM digital_market WHERE ip IN (SELECT `ip` FROM `digital_market` WHERE `utm_source` = 'metroads' AND `deletes` = '0'  $dateCon GROUP BY ip)  AND `subid1` != '' AND  `deletes` = '0' and (`subid1` like '%metroads%' || `subid1` like '%success%'  || `subid1` like '%play%'   || `subid1` like '%login%'   || `subid1` like '%cart%'   || `subid1` like '%billing%'
        || `subid1` like '%dashboard%' || `subid1` like '%failed%' || `subid1` like '%thanks%'  )  $dateCon GROUP BY subid1 order by totalview desc"
        );
        while ($row = mysqli_fetch_assoc($sql)) {

            $subid = $row['subid1'];
            if ($subid == "?utm_source=metroads&utm_medium=banner&utm_campaign=metro_ads") {

                $subid = "Metro landing";
            }
            if ($subid == "index.php?makepayment=success") {

                $subid = "Success";
            } else {

                $subid = $subid;
            }

            $result[] = ["pagename" => $subid, "totalviews" => $row['totalview']];
            $subid = "";
        }

        echo json_encode($result);
    } catch (Exception $e) {
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'datatable_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == 'overallreport') {
    try {
        $fromdate = BlockSQLInjectionforreportrange($_POST["fromdate"]);
        $todate = BlockSQLInjectionforreportrange($_POST["todate"]);
        // $formdate = $_POST['formdate'];
        // $todate = $_POST['todate'];

        if ($formdate != '' && $todate != '') {
            $fd = date("Y-m-d", strtotime($formdate));
            $td = date("Y-m-d", strtotime($todate));
            $dateCon = "AND `createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59'";
        }
        $source = BlockSQLInjectionforreportrange($_POST["source"]);

        // $source = $_POST['source'];
        if ($source == 'organic') {
            $sourceCon = "`utm_source` = '' AND";
        } else if ($source == 'All') {
            $sourceCon = "";
        } else {
            $sourceCon = "`utm_source` = '$source' AND";
        }

        if ($source == 'organic') {
            $subiCon = " AND (`subid1` like '%%' || `subid1` like '%success%' || `subid1` like '%play%' || `subid1` like '%login%' || `subid1` like '%cart%' || `subid1` like '%billing%' || `subid1` like '%dashboard%' || `subid1` like '%failed%' || `subid1` like '%thanks%' ) AND (`subid1` NOT like '%gclid%' && `subid1` NOT like '%fbclid%' && `subid1` NOT like '%utm_source%' && `subid1` NOT like '%wbraid%' && `subid1` NOT like 'index.php%' )";
        } else if ($source == 'All') {
            $subiCon = "";
        } else if ($source == "Google") {
            $subiCon = " AND (`subid1` like '%%' || `subid1` like '%success%' || `subid1` like '%play%' || `subid1` like '%login%' || `subid1` like '%cart%' || `subid1` like '%billing%' || `subid1` like '%dashboard%' || `subid1` like '%failed%' || `subid1` like '%thanks%' ) AND (`subid1` NOT like '%fbclid%' && `subid1` NOT like '%gclid%' && `subid1` NOT like '%wbraid%')";
        } else {
            $subiCon = "AND `subid1` != '' AND (`subid1` like '%%' || `subid1` like '%success%' || `subid1` like '%play%' || `subid1` like '%login%' || `subid1` like '%cart%' || `subid1` like '%billing%' || `subid1` like '%dashboard%'  || `subid1` like '%failed%' || `subid1` like '%thanks%' ) ";
        }

        // echo "SELECT subid1, COUNT(subid1) as 'totalview' FROM digital_market WHERE ip IN (SELECT `ip` FROM `digital_market` WHERE $sourceCon `deletes` = '0' $dateCon GROUP BY `ip` ORDER BY `id` DESC) AND `deletes` = '0' $subiCon $dateCon GROUP BY subid1 order by totalview desc;";
        // die;
        $sql = mysqli_query(
            $rcon,
            "SELECT subid1, COUNT(subid1) as 'totalview' FROM digital_market WHERE ip IN (SELECT `ip` FROM `digital_market` WHERE $sourceCon `deletes` = '0' $dateCon GROUP BY `ip` ORDER BY `id` DESC) AND `deletes` = '0' $subiCon $dateCon GROUP BY subid1 order by totalview desc;"
        );

        while ($row = mysqli_fetch_assoc($sql)) {

            $subid = $row['subid1'];
            $utm_source = $source;

            if ($utm_source == "" && $subid == "") {

                $utm_source = "Organic";
                $subid = 'Home';
            } else
            if ($utm_source == "") {

                $utm_source = "Organic";
                $subid = $row['subid1'];
            } else
            if ($subid == "?utm_source=metroads&utm_medium=banner&utm_campaign=metro_ads") {

                $subid = "Metro landing";
            } else if ($subid == "index.php?makepayment=success") {

                $subid = "Success";
            } else if ($subid == "") {

                $subid = "Home";
            } else {

                $subid = $subid;
            }

            $subid = $utm_source . ": " . $subid;

            $result[] = ["pagename" => $subid, "totalviews" => $row['totalview']];

            $subid = "";
        }

        if ($source == 'All') {
            $subid = $utm_source . ": " . 'Overall';
            $digital_market = mysqli_query($con, "SELECT `ip` FROM `digital_market` WHERE `deletes` = '0' $dateCon GROUP BY `ip`;");
            $result[] = ["pagename" => $subid, "totalviews" => mysqli_num_rows($digital_market)];
        }

        echo json_encode($result);
    } catch (Exception $e) {
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'datatable_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == 'searchCoupon') {
    try {
        $result = [];
        $coupon_type = $_POST['coupon_type'];
        if ($coupon_type != '') {
            $coupon_con = "`coupontype` = '$coupon_type' AND";
        } else {
            $coupon_con = "";
        }

        $coupon_status = (int) $_POST['coupon_status'];
        if ($coupon_status == 1) {
            $status_con = "`used_by` = '0' AND `status` = '0' AND `ticket_id` = '0' AND";
        } else if ($coupon_status == 2) {
            $status_con = "`used_by` != '0' AND `status` = '0' AND `ticket_id` = '0' AND";
        } else if ($coupon_status == 2) {
            $status_con = "`used_by` != '0' AND `status` != '0' AND `ticket_id` != '0' AND";
        } else {
            $status_con = "";
        }

        $fromdate = date("Y-m-d", strtotime($_POST['formdate']));
        $todate = date("Y-m-d", strtotime($_POST['todate']));

        if ($fromdate != '' && $todate != '') {
            $dateFilter = "`createdon` BETWEEN '$fromdate 00:00:00' AND '$todate 23:59:59' AND";
        }

        $couponcode = select_query($con, "couponcode", "", "$coupon_con $status_con $dateFilter `deletes`='0' ORDER BY `id` DESC", "", "");
        if ($couponcode['nr'] > 0) {
            foreach ($couponcode['result'] as $key => $value) {
                $status = "";
                $action = "";
                if ((int) $value['used_by'] == 0 && (int) $value['status'] == 0 && (int) $value['ticket_id'] == 0) {
                    $status = strtoupper('generated');
                } else if ((int) $value['used_by'] != 0 && (int) $value['status'] == 0 && (int) $value['ticket_id'] == 0) {
                    $status = strtoupper('assigned');
                } else if ((int) $value['used_by'] != 0 && (int) $value['status'] != 0 && (int) $value['ticket_id'] != 0) {
                    $status = strtoupper('used');

                    $transaction_id = select_top_name($con, "cticket", "transaction_id", "`id`=" . $value['ticket_id'] . " and `deletes`='0'  order by `id` DESC ", "transaction_id", "");
                    if ($transaction_id != '') {
                        $action = '<a target="_blank" href="' . $baseurl . 'ticket-view/' . $transaction_id . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span class="fa fa-files-o fs-14"></span></a>';
                    }
                }

                $result[] = ["couponcode" => $value['couponcode'], "coupontype" => $value['coupontype'], "status" => $status, "expiredon" => date('d-m-Y g:i a', strtotime($value['expiredon'])), "createdon" => date('d-m-Y g:i a', strtotime($value['createdon'])), "action" => $action];
            }
        }

        echo json_encode($result);
    } catch (Exception $e) {
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'datatable_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == 'searchReferral') {
    try {
        $result = [];

        $fromdate = date("Y-m-d", strtotime($_POST['formdate']));
        $todate = date("Y-m-d", strtotime($_POST['todate']));

        if ($fromdate != '' && $todate != '') {
            $dateFilter = "`createdon` BETWEEN '$fromdate 00:00:00' AND '$todate 23:59:59' AND";
        }

        $referral_status = (int) $_POST['referral_status'];
        if ($referral_status == 1) {
            $status_con = "`status` = '1' AND";
        } else if ($referral_status == 2) {
            $status_con = "`status` = '0' AND";
        } else {
            $status_con = "";
        }

        $referralcode = select_query($con, "referral_history", "", " $dateFilter $status_con `deletes`='0' ORDER BY `id` DESC", "", "");
        if ($referralcode['nr'] > 0) {
            foreach ($referralcode['result'] as $key => $value) {
                $usercount = 0;
                if ($value['to_user_ids'] != '') {
                    $totalUsers = explode(',', $value['to_user_ids']);
                    $usercount = count($totalUsers);
                }

                if ((int) $value['status'] == 1) {
                    $status = 'finished';
                } else if ((int) $value['status'] == '0') {
                    $status = 'pending';
                } else {
                    $status = '';
                }

                $ticketid = select_top_name($con, "ticket", "ticket_no", "`referral_id`='" . $value['id'] . "' and `deletes`='0'  order by `id` DESC ", "ticket_no", "");

                $user_register = select_query($con, "user_register", "`id`, `email`, `name`, `mobile`", "`id`='" . $value['from_user_id'] . "' and `deletes`='0'  order by `id` DESC ", "", "");
                if ($user_register['nr'] > 0) {
                    $name = $user_register['result'][0]['name'];
                    $email = $user_register['result'][0]['email'];
                    $mobile = $user_register['result'][0]['mobile'];
                }

                $result[] = ["ticketid" => $ticketid, "referralcode" => $value['refferral_code'], "name" => $name, "email" => $email, "mobile" => $mobile, "usercount" => $usercount, "targetamt" => $value['target_amt'], "acamt" => $value['achieved_amt'], "status" => strtoupper($status)];
            }
        }

        echo json_encode($result);
    } catch (Exception $e) {
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'datatable_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == "list_Wallticket") {

    // $result = [];

    $contype = '';

    $type = 'OT';

    $da = '';

    $now = date('Y-m-d');
    $formdate = BlockSQLInjectionforagent($_POST["formdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    // $formdate = $_POST['formdate'];

    // $todate = $_POST['todate'];

    if ($formdate != '' && $todate != '') {

        $da = 'DESC';

        $fd = date("Y-m-d", strtotime($formdate));

        $td = date("Y-m-d", strtotime($todate));

        $contype = "ticket.purchase_datetime BETWEEN '$fd 00:00:00' AND '$td 23:59:59' ";
        $contype1 = "wticket.purchase_datetime BETWEEN '$fd 00:00:00' AND '$td 23:59:59' ";
    } else {

        $da = 'DESC';

        $contype = "ticket.purchase_datetime LIKE '%$now%'";
        $contype1 = "wticket.purchase_datetime LIKE '%$now%'";
    }

    $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$memid' and `deletes`='0'", "roll_id", "");

    if ($roll_id != 1 && $roll_id != 2 && $roll_id != 6 && $roll_id != 8) {
        $agent = "`agent_id` = '$memid' AND";
    } else {
        $agent = "";
    }

    // var_dump("SELECT ticket.id AS 'ticketid', user_register.name AS 'name', user_register.mobile AS 'mobile', user_register.email AS 'email', ticket_lines.my3number AS 'my3number', ticket_lines.raffle_id AS 'raffleid', product.rate AS 'productamt', ticket.purchase_datetime AS 'purchasedatetime', ticket.transaction_id AS 'transactionid' ,ticket.ticket_no AS 'ticket_no' FROM ticket_lines INNER JOIN `ticket` ON ticket.id = ticket_lines.ticket_id INNER JOIN invoice ON ticket.invoice_no = invoice.id INNER JOIN user_register ON ticket.user_id = user_register.id INNER JOIN product ON ticket_lines.product_id = product.id WHERE ticket.deletes = '0' AND invoice.response = 'wallet' AND ticket_lines.type = 'OT' AND $contype");
    // die;

    $wticket = mysqli_query($con, "SELECT ticket.id AS 'ticketid', user_register.name AS 'name',user_register.lname AS 'lname', user_register.mobile AS 'mobile', user_register.email AS 'email', ticket_lines.my3number AS 'my3number', ticket_lines.raffle_id AS 'raffleid', product.rate AS 'productamt', ticket.purchase_datetime AS 'purchasedatetime', ticket.transaction_id AS 'transactionid' ,ticket.ticket_no AS 'ticket_no' FROM ticket_lines INNER JOIN `ticket` ON ticket.id = ticket_lines.ticket_id INNER JOIN invoice ON ticket.invoice_no = invoice.id INNER JOIN user_register ON ticket.user_id = user_register.id INNER JOIN product ON ticket_lines.product_id = product.id WHERE ticket.deletes = '0' AND invoice.response = 'wallet' AND ticket_lines.type = 'OT' AND $contype");
    while ($value = mysqli_fetch_assoc($wticket)) {
        $transaction_id = $value['transactionid'];
        $action = '';
        $action .= '<div class="g-2">';
        $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' . $transaction_id . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span style="font-size: 18px;" class="fa fa-file-text-o"></span></a>';
        $action .= '<a target="_blank" href="' . $baseurl . 'ticket-view/' . $transaction_id . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span style="font-size: 18px;" class="fa fa-files-o"></span></a>';
        if ($_SESSION['memid'] == 1) {
            $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size: 18px;" class="fa fa-paper-plane" onclick="sendemailtopurchase(' . "'$transaction_id'" . ', ' . "'OT'" . ', ' . "'ticket'" . ')">&nbsp;Email</span></a>';
            $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8; font-size: 18px;" onclick="sendsmstopurchase(' . "'$transaction_id'" . ', ' . "'OT'" . ', ' . "'ticket'" . ')">&nbsp;SMS</span></a>';
            // $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" onclick="sendemailtopurchase(' . "'$transaction_id'" . ')">Send Email</span></a>';
            // $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8;" onclick="sendsmstopurchase(' . "'$transaction_id'" . ')">Send Sms</span></a>';
            // $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8;" onclick="bothemailsms(' . "'$transaction_id'" . ')">Both</span></a>';
            // $action .= '<button value="' . $transaction_id . '" onclick="deleteoticket($(this).val())" class="btn btn-danger edit"><span class="fe fe-trash-2 fs-14 sssss"></span></button>';
        }

        $action .= '</div>';
        $result[] = array("action" => $action, "transaction_id" => $value['transactionid'], "proamt" => $value['productamt'], "RaffleID" => $value['raffleid'], "My3Numbers" => $value['my3number'], "email" => $value['email'], "mobile" => $value['mobile'], "cusname" => $value['name'], "lname" => $value['lname'], "ticketno" => $value['ticket_no'], "purdate" => '<span style="display:none;">' . strtotime($value['purchasedatetime']) . '</span>' . date("d-M-Y g:i a", strtotime($value['purchasedatetime'])));
    }

    $wtticket = mysqli_query($con, "SELECT wticket.id AS 'ticketid',user_register.name AS 'name', user_register.mobile AS 'mobile', user_register.email AS 'email', ticket_lines.my3number AS 'my3number', ticket_lines.raffle_id AS 'raffleid', product.rate AS 'productamt',wticket.invoice_no, wticket.purchase_datetime AS 'purchasedatetime', wticket.transaction_id AS 'transactionid' , wticket.ticket_no AS 'ticket_no' FROM ticket_lines INNER JOIN `wticket` ON wticket.id = ticket_lines.ticket_id INNER JOIN user_register ON wticket.user_id = user_register.id INNER JOIN product ON ticket_lines.product_id = product.id WHERE wticket.deletes = 0 AND ticket_lines.type = 'WT' AND $contype1");
    while ($value = mysqli_fetch_assoc($wtticket)) {
        $transaction_id = $value['transactionid'];
        $action = '';
        $action .= '<div class="g-2">';
        if ($value['invoice_no'] != '' && $value['invoice_no'] != 0) {
            $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' . $transaction_id . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span style="font-size: 18px;" class="fa fa-file-text-o"></span></a>';
        }
        $action .= '<a target="_blank" href="' . $baseurl . 'ticket-view/' . $transaction_id . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span style="font-size: 18px;" class="fa fa-files-o">&nbsp;Ticket</span></a>';
        if ($_SESSION['memid'] == 1) {
            $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size: 18px;" class="fa fa-paper-plane" onclick="sendemailtopurchase(' . "'$transaction_id'" . ', ' . "'WT'" . ', ' . "'wticket'" . ')">&nbsp;Email</span></a>';
            $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8; font-size: 18px;" onclick="sendsmstopurchase(' . "'$transaction_id'" . ', ' . "'WT'" . ', ' . "'wticket'" . ')">&nbsp;SMS</span></a>';
            // $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8;" onclick="bothemailsms(' . "'$transaction_id'" . ')">Both</span></a>';
            // $action .= '<a onclick="deletewticket(' . "'$transaction_id'" . ')" style="cursor: pointer; color: ##fb0909;"><span class="fe fe-trash-2 fs-14 sssss"></span></a>';
            // $action .= '<button value="' . $transaction_id . '" onclick="deletewticket($(this).val())" class="btn btn-danger edit"><span class="fe fe-trash-2 fs-14 sssss"></span></button>';
        }

        $action .= '</div>';
        $result[] = array("action" => $action, "transaction_id" => $value['transactionid'], "proamt" => $value['productamt'], "RaffleID" => $value['raffleid'], "My3Numbers" => $value['my3number'], "email" => $value['email'], "mobile" => $value['mobile'], "cusname" => $value['name'] . ' ' . $value['lname'], "ticketno" => $value['ticket_no'], "purdate" => '<span style="display:none;">' . strtotime($value['purchasedatetime']) . '</span>' . date("d-M-Y g:i a", strtotime($value['purchasedatetime'])));
    }


    echo json_encode($result);
} else if ($method == "raffledraw") {
    try {
        $result = [];
        $raffle_draw = mysqli_query($con, "SELECT raffle_draw.id AS 'raffledrawid', raffle_draw_name, draw_date, raffle_id, draw_id, draw_name, product_id, ticket_lines_id, user_id, username, email, mobile, status, raffle_draw.deletes AS 'deletes', updatedon, raffle_draw.createdon AS 'createdon', product.name AS 'productname', product.raffle_prize AS 'raffle_prize' FROM `raffle_draw` INNER JOIN product ON raffle_draw.product_id = product.id ORDER BY raffle_draw.id ASC");
        while ($row = mysqli_fetch_assoc($raffle_draw)) {
            $result[] = [
                "sno" => $row['raffledrawid'],
                "raffleid" => $row['raffle_id'],
                "drawname" => $row['draw_name'],
                "name" => ucwords(strtolower($row['username'])),
                "email" => $row['email'],
                "mobile" => $row['mobile'],
                "productname" => $row['productname'],
                "prizeamt" => number_format(intval($row['raffle_prize'])),
                "drawdate" => date('d-m-Y', strtotime($row['draw_date'])),
            ];
        }

        echo json_encode($result);
    } catch (Exception $e) {
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'datatable_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == "payment_status_history") {

    $result = [];

    $contype = '';

    $type = 'OT';

    $da = '';

    $gt = '';

    $now = date('Y-m-d');
    $formdate = BlockSQLInjection($_POST["formdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);
    // $formdate = $_POST['formdate'];

    // $todate = $_POST['todate'];

    if ($formdate != '' && $todate != '') {

        $da = 'DESC';

        $fd = date("Y-m-d", strtotime($formdate));

        $td = date("Y-m-d", strtotime($todate));

        $contype = "`payby_link`.`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' order by `payby_link`.`id` DESC";
    } else {

        $da = 'DESC';

        $contype = "`payby_link`.`createdon` LIKE '%$now%'  order by `payby_link`.`id` DESC";
    }
    $paymentStatus = BlockSQLInjectionforreportrange($_POST["paymentStatus"]);
    // $paymentStatus = $_POST['paymentStatus'];
    if ($paymentStatus == 'initiated') {
        $paymentCon = "`payby_link`.`email_sms_status` = '1' AND `payby_link`. `status` ='Initiated' AND";
    } else if ($paymentStatus == 'completed') {
        $paymentCon = "`payby_link`.`email_sms_status` = '1' AND `payby_link`. `status` ='Paid' AND";
    }

    $payment_history = mysqli_query($con, "SELECT `payby_link`.`is_cron`,`payby_link`.`user_id`,`payby_link`.`status`,`payby_link`.`payment_id`, `payby_link`.`Newpayment_id`, `payby_link`.`createdon`, `user_register`.`name`, `payby_link`.`mobile_num`,`payby_link`.`email_id`, `payby_link`.`type`,  `payby_link`.`expired` FROM `payby_link`  INNER JOIN user_register ON payby_link.user_id = user_register.id WHERE  $paymentCon $contype ");

    while ($row = mysqli_fetch_assoc($payment_history)) {
        $payment_id = $row['payment_id'];
        $Newpayment_id = $row['Newpayment_id'];
        $payment_customer1 = select_query($con, "payment_history", "", " `id`= '$payment_id' ", "", "");
        $payment_customer2 = select_query($con, "payment_history", "", " `id`= '$row[Newpayment_id]' ", "", "");

        $old_transid = $payment_customer1['result'][0]['transaction_id'];
        $new_transid = $payment_customer2['result'][0]['transaction_id'];
        $purchase_amt = $payment_customer2['result'][0]['finaltotal'];

        // $action = '';
        // $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' .  $new_transid . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span class="fa fa-file-text-o fs-14"></span></a>';

        // $action .= '<a target="_blank" href="' . $baseurl . 'ticket-view/' .  $new_transid . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span class="fa fa-files-o fs-14"></span></a>';
        if ($row['is_cron'] == '0') {
            $isCron = 'No';
        } else {
            $isCron = 'Yes';
        }
        $result[] = array(

            "is_cron" => $isCron, "linkstatus" => $row['status'], "type" => $row['type'], "newtransaction" => $new_transid, "mobile" => $row['mobile_num'], "oldtransaction" => $old_transid, "email" => $row['email_id'], "userid" => $row['user_id'], "name" => $row['name'], "amount" => $purchase_amt, "action" => $action, "date" => date("d-M-Y g:i a", strtotime($row['createdon'])),
        );
    }
    echo json_encode($result);
} elseif ($method == 'userprofile_report') {


    $result = [];
    $now = date('Y-m-d');
    $agdate = BlockSQLInjection($_POST["agdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);

    if ($agdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($agdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "user_profile_activity_log.`updated_datetime` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' ORDER BY user_profile_activity_log.id DESC";
    } else {
        $da = 'DESC';
        $contype = "user_profile_activity_log.`updated_datetime` LIKE '%$now%' ORDER BY user_profile_activity_log.id DESC";
    }

    $emaillog = mysqli_query($con, "SELECT user_profile_activity_log.*,user_register.email as userEmail,changedBy.email as changedByEmail FROM `user_profile_activity_log` JOIN user_register ON user_register.id = user_profile_activity_log.user_id JOIN user_register changedBy ON changedBy.id = user_profile_activity_log.changed_by where " . $contype);

    if (mysqli_num_rows($emaillog) > 0) {
        $i = 1;
        while ($row = mysqli_fetch_assoc($emaillog)) {

            if (count(json_decode($row['changed_data'], true)) > 0) {
                $result[] = ["userId" => $i++, "userEmail" => $row['userEmail'], "changedBy" => $row['changedByEmail'], "changedData" => $row['changed_data'], "ip" => $row['ip'], "datetime" => $row['updated_datetime']];
            }
        }
    }

    echo json_encode($result);
}
