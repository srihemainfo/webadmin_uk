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

    if ($roll_id != 1 && $roll_id != 2 && $roll_id != 6 && $roll_id != 11 && $roll_id != 8 && $_SESSION['memid'] != '8236') {
        $condition = "`created_by` = '$memid' AND";
    } else {
        $condition = "";
    }
    $datefill = BlockSQLInjectionforagent($_POST["datefill"]);
    $datefrom = BlockSQLInjectionforagent($_POST["datefrom"]);
    // $datefill = $_POST['datefill'];
    // $datefrom = $_POST['datefrom'];

    $now = date('Y-m-d');

    if ($datefill == 'Invalid date') {

        // var_dump('helskdf');die;
        $datefill = '';
    }
    if ($datefrom == 'Invalid date') {

        // var_dump('helskdf');die;
        $datefrom = '';
    }
    // var_dump($datefill,$datefrom);die;

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
    } else if ($datefrom != '' && $datefill != '') {

        $contype = "`created_at` BETWEEN '$datefrom 00:00:00' AND '$datefill 23:59:59' AND";
    } else {
        $fieldcon = "";
        $contype = "`created_at` LIKE '%$now%' AND";
    }
    // $deletedstatus = BlockSQLInjectionforagent($_POST["deletedstatus"]);

    $deletedstatus = $_POST['deletedstatus'];
    if ($deletedstatus != '') {
        $deletecon = "`deletes`='$deletedstatus'";
    } else {
        $deletecon = "`deletes`='Deleted'";
    }

    $user_register = select_query($rcon, "user_register", "", "$condition $role $contype $fieldcon $deletecon order by `id` DESC", "", "");

    if ($user_register['nr'] > 0) {

        foreach ($user_register['result'] as $key => $value) {
            $FRONT_img = select_query($rcon, "user_images", "", "`user_id`='$value[id]' and `img_url` != '' and `type` = 'FRONT' and `status`='0' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
            $BACK_img = select_query($rcon, "user_images", "", "`user_id`='$value[id]' and `img_url` != '' and `type` = 'BACK' and `status`='0' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
            $deletes = $value['deletes'];

            // var_dump($roll_id);die;


            $action = '';
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
                } else  if ($value['roll_id'] == 3) {


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
                } else  if ($value['roll_id'] == 4) {


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
} else if ($method == "ND_invoice") {
    // //  var_dump('hellow');die;
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

    $formdate = BlockSQLInjectionforagent($_POST["agdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    $searchTxt = BlockSQLInjectionforagent($_REQUEST["search_id"]);
    // $formdate = $_POST['formdate'];

    // $todate = $_POST['todate'];



    if ($formdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));

        // $contype = "`collectRequestedDate` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
        $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {

        $da = 'DESC';
        $contype = "";
    }



    if ($userType1 == 'deliveryToMe') {

        $userType = "`deliveryType`= 'deliveryToMe' AND";
    } else if ($userType1 == 'pickUpToStore') {

        $userType = "`deliveryType`= 'pickUpToStore' AND";
    } else {
        $userType = '';
    }

    if ($searchTxt != '') {

        $contype = "";

        // $draw_Con = "(secertCode LIKE '$searchTxt') AND ";
        $draw_Con = "(secertCode LIKE '%" . $searchTxt . "%') AND ";
    }
    if ($searchTxt == '' && $userType1 == '' && $formdate == '' && $todate == '') {
        $da = 'DESC';
        $contype = "`createdon` LIKE '%$now%' AND";
    }



    $NDticketquery = mysqli_query($con, "SELECT * FROM `shipping_request` WHERE $userType $contype $draw_Con `deletes` = '0'  ORDER BY `id` DESC;");

    $selectQuery = "SELECT * FROM `shipping_request` WHERE $userType $contype $draw_Con `deletes` = '0'  ORDER BY `id` DESC;";
    //   var_dump("SELECT * FROM `shipping_request` WHERE $userType $contype $draw_Con `deletes` = '0'  ORDER BY `id` DESC;");die;

    $runQuery = mysqli_query($con, $selectQuery);


    if ($runQuery && mysqli_num_rows($runQuery) > 0) {
        $result = [];

        while ($row = mysqli_fetch_assoc($runQuery)) {

            $userID = $row['userID'];
            $pay_id = $row['pickup_invoice_id'];


            $name = select_top_name($con, "user_register", "name", "`id`='$userID' and `deletes`='0'", "name", "");
            $lname = select_top_name($con, "user_register", "lname", "`id`='$userID' and `deletes`='0'", "lname", "");

            $fullname = $name . ' ' . $lname;
            $mobile = select_top_name($con, "user_register", "mobile", "`id`='$userID' and `deletes`='0'", "mobile", "");
            $email = select_top_name($con, "user_register", "email", "`id`='$userID' and `deletes`='0'", "email", "");
            $payment_trans_id = select_top_name($con, "invoice", "payment_transaction_id", "`id`='$pay_id' and `deletes`='0'", "payment_transaction_id", "");

            // var_dump($payment_trans_id);die;


            $result[] = [
                "collectRequestedDate" => $row['collectRequestedDate'],
                "product_cost" => $row['product_cost'],
                "id" => $row['id'],
                "fullname" => $fullname,
                "mobile" => $mobile,
                "email" => $email,
                "reason" => $row['reason'],
                "packing_cost" => $row['packing_cost'],
                "handling_cost" => $row['handling_cost'],
                "delivery_cost" => $row['delivery_cost'],
                "subgrand_total" => $row['subgrand_total'],
                "paidSoFor" => $row['paidSoFor'],
                "grand_total" => $row['grand_total'],
                "deliveryType" => $row['deliveryType'],
                "delivery_status" => $row['delivery_status'],
                "cart" => $row['shippingAddress'],
                "ticketReferenceID" => $row['ticketReferenceID'],
                "payment_transaction_id" => $payment_trans_id,

            ];
        }


        // $result = mysqli_fetch_all($NDticketquery, MYSQLI_ASSOC);
    }


    // var_dump($result);die;
    echo json_encode($result);
} else if ($method == "update_delivery_status") {



    $rowId = BlockSQLInjectionforagent($_POST["id"]);
    $newStatus = BlockSQLInjectionforagent($_POST["status"]);

    // $Tracking_URL = $_POST['Tracking_URL'];
    $delete_re = $_POST['Tracking_URL'];




    $invoice_id = select_top_name($con, "shipping_request", "pickup_invoice_id", "`id`='$rowId' and `deletes`='0'", "pickup_invoice_id", "");
    $deliveryType = select_top_name($con, "shipping_request", "deliveryType", "`id`='$rowId' and `deletes`='0'", "deliveryType", "");
    $delivery_status = select_top_name($con, "shipping_request", "delivery_status", "`id`='$rowId' and `deletes`='0'", "delivery_status", "");

    // if($deliveryType =='pickUpToStore' && $delivery_status == 'requested'){


    //     var_dump('kinh');die;
    // }

    $updateQuery = "UPDATE shipping_request SET delivery_status = '$newStatus',orderClosedBy = '$memid',reason = '$delete_re', updatedon = '$dubaidate_time' WHERE id = '$rowId'";


    $updateQuery1 = "UPDATE invoice SET delivery_status = '$newStatus', updatedon = '$dubaidate_time' WHERE id = '$invoice_id'";
    if (mysqli_query($con, $updateQuery1)) {

        if (mysqli_query($con, $updateQuery)) {
            echo json_encode(array("type" => 1, "status" => "success", "message" => "Delivery status updated successfully"));
        } else {
            echo json_encode(array("type" => 0, "status" => "error", "message" => "Error updating delivery status"));
        }
    }
} else if ($method == "Search_Secret_code") {

    // var_dump('welcome');die;



    $rowId = BlockSQLInjectionforagent($_POST["id"]);
    $newStatus = BlockSQLInjectionforagent($_POST["status"]);
    $secretCode = BlockSQLInjectionforagent($_POST["secretCode"]);

    // var_dump($rowId,$newStatus,$secretCode);die;
    $selectQuery = "SELECT * FROM `shipping_request` WHERE `id`=$rowId AND `secertCode`= '$secretCode' AND  `deletes` = '0'  ORDER BY `id` DESC;";
    // var_dump("SELECT * FROM `shipping_request` WHERE `id`=$rowId AND `secertCode`='$secretCode' AND  `deletes` = '0'  ORDER BY `id` DESC;");die;
    $runQuery = mysqli_query($con, $selectQuery);


    if ($runQuery && mysqli_num_rows($runQuery) > 0) {
        echo json_encode(array("type" => 1, "status" => "success", "message" => "The Secret Code match successfully"));
    } else {
        echo json_encode(array("type" => 0, "status" => "error", "message" => "Error the secret code"));
    }
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
    $request_type = BlockSQLInjectionforagent($_REQUEST["request_type"]);

    // $formdate = $_POST['formdate'];

    // $todate = $_POST['todate'];

    // var_dump($productRate,$userType1);die;

    if ($formdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));

        $contype = "G.`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $contype = '';
    }

    if($request_type != ''){
        $request_status ="G.delivery_status ='$request_type' AND";
    }



    if ($searchTxt != '') {
        $draw_Con = "(ur.name LIKE '%$searchTxt%' OR ur.email LIKE '%$searchTxt%' OR ur.mobile LIKE '%$searchTxt%') AND ";
    }
    if ($searchTxt == ''  && $formdate == '' && $todate == '' && $request_type == '') {


        $contype = "G.`createdon` LIKE '%$now%' AND";
    }


    //   var_dump("SELECT  G.id, G.grams, G.reason, G.delivery_status, G.ticketReferenceID, G.goldValue, G.orderClosedBy, G.createdon, G.updatedon, ur.name, ur.lname, ur.mobile, ur.email
    //     FROM `gold_request` G
    //     JOIN `user_register` ur ON G.userID = ur.id
    //     WHERE $contype $draw_Con $request_status G.deletes = '0';

    //     ");die;

    $GoldRequest = mysqli_query($con, "SELECT  G.id, G.grams, G.reason, G.delivery_status, G.ticketReferenceID, G.goldValue, G.orderClosedBy, G.createdon, G.updatedon, ur.name, ur.lname, ur.mobile, ur.email
        FROM `gold_request` G
        JOIN `user_register` ur ON G.userID = ur.id
        WHERE $contype $draw_Con $request_status G.deletes = '0';
        ");



    //   var_dump($GoldRequest);die;

    if (mysqli_num_rows($GoldRequest) > 0) {
        while ($row = mysqli_fetch_assoc($GoldRequest)) {

            $order_Closed_by = $row['orderClosedBy'];

            if ($order_Closed_by !== null or $order_Closed_by !== 0) {
                $order_closed_By_name = select_top_name($con, "user_register", "name", "`id`=$order_Closed_by", "name", "");
            } else {
                $deleted_By_name = "No specific user";
            }

            $result[] = [
                "reason" => $row['reason'],
                "firstname" => $row['name'],
                "id" => $row['id'],
                "lastname" => $row['lname'],
                "mobile" => $row['mobile'],
                "emailid" => $row['email'],
                "createdon" => $row['createdon'],
                "goldValue" => $row['goldValue'],
                "delivery_statu" => $row['delivery_status'],
                "grams" => $row['grams'],
                //  "deleted_at" => $row['deleted_at'] ? $row['deleted_at'] : "Not deleted",
                "ticketReferenceID" => $row['ticketReferenceID'],
                "order_closed_By_name" => $order_closed_By_name,
                // "monthlyactiveCount" => $monthlyactiveCount,
            ];
        }
    }


    // var_dump($result);die;
    echo json_encode($result);
} else if ($method === "Gold_delivery_status") {

    $result = [];

    $rowId = BlockSQLInjectionforagent($_POST["id"]);
    $newStatus = BlockSQLInjectionforagent($_POST["status"]);
    $reason = $_POST['reason'] ?? '';


    if ($rowId == '' || $rowId == null) {
        $result["type"] = "0";
        $result["result"] =  "Provide Valid IDs!";
        goto resultFGVI;
    }

    if ($newStatus == '' || $newStatus == null) {
        $result["type"] = "0";
        $result["result"] =  "Provide Valid Status!";
        goto resultFGVI;
    }


    if ($newStatus === 'cancelled' && ($reason == '' || $reason == null)) {
        $result["type"] = "0";
        $result["result"] =  "Provide Valid Reason!";
        goto resultFGVI;
    }

    $gold_requestCheck = select_query($con, "gold_request", "", "`id` = '$rowId' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
    if ($gold_requestCheck['nr'] < 1) {
        $result["type"] = "0";
        $result["result"] =  "Gold Request Track Not Found!";
        goto resultFGVI;
    }

    $winnerID = $gold_requestCheck['result'][0]['winnerlistId'];

    // var_dump($winnerID);
    // die;

    $shi_arr = [
        'delivery_status' => $newStatus,
        'updatedon' => $dubaidate_time,
        'orderClosedBy' => $_SESSION['memid']
    ];

    if ($newStatus === 'cancelled') {
        $shi_arr['reason'] = $reason;
    }


    // var_dump($shi_arr);
    // die;


    $user_register_update = update($con, "gold_request", "`id` = '$rowId' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", $shi_arr, "", "", "", "");
    $errors = $user_register_update['errors'];
    if ($errors != "") {
        $result["type"] = "0";
        $result["result"] =  "Error updating delivery status";
        goto resultFGVI;
    } else {

        if ($newStatus === 'cancelled' && $winnerID != null) {

            // $nrar = ['requestType' => 'NULL', 'requestTime' => 'NULL'];
            $winnerUpdate = mysqli_query($con, "UPDATE `winnerlist` SET `requestType` = NULL, `requestTime` = NULL WHERE `winnerlist`.`id` = $winnerID ORDER BY `id` DESC LIMIT 1;");
            // $winnerUpdate = setupdate($con, "winnerlist", "`id` = '$winnerID' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", $nrar, "", "", "", "");
            // $errors = $winnerUpdate['errors'];
            // var_dump($errors);die;
            if ($winnerUpdate) {
                $result["type"] = "1";
                $result["result"] =  "The delivery status has been successfully updated";
                goto resultFGVI;
            } else {
                $result["type"] = "0";
                $result["result"] =  "Error updating delivery status";
                goto resultFGVI;
            }
        }

        $result["type"] = "1";
        $result["result"] =  "The delivery status has been successfully updated";
        goto resultFGVI;
    }


    resultFGVI:
    echo json_encode($result);
}
