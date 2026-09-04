<?php



include '../../include/shi-config.php';

include '../../include/functions.php';

include '../../xlsx/Classes/PHPExcel.php';


$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";

$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";

$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";

if ($type == 'agent') {

    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($_REQUEST[role]) AND" : "";
} else {

    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$_REQUEST[role]' AND" : "";
}



$headers = apache_request_headers();

//print_r($headers);

$result = array();

$post_csrf = $headers['X-Csrf-Token'] ?? '';






if ($method == "generatedcopoun") {

    $result = [];
    $coupon = BlockSQLInjection($_POST["ccode"]);
    $productid = BlockSQLInjection($_POST["camt"]);
    // $coupon = $_POST['ccode'];

    // $productid = $_POST['camt'];



    if ($_SESSION['memid'] == '') {

        $result["type"] = "0";

        $result["result"] = "Kindly Login First & Try Again";

        goto resultSI;
    }



    if ($_POST['cname'] == '') {

        $result["type"] = "0";

        $result["result"] = "Kindly Enter the Coupon Name";

        goto resultSI;
    }



    if ($coupon == '') {

        $result["type"] = "0";

        $result["result"] = "Kindly Enter the Coupon Code";

        goto resultSI;
    }



    if ($_POST['cusedby'] == '') {

        $result["type"] = "0";

        $result["result"] = "Kindly Select the Coupon Used By";

        goto resultSI;
    }



    if ($_POST['climit'] == '') {

        $result["type"] = "0";

        $result["result"] = "Kindly Enter the Coupon Limitations";

        goto resultSI;
    }



    if ($productid == '') {

        $result["type"] = "0";

        $result["result"] = "Kindly Select the Coupon Value";

        goto resultSI;
    }



    if ($_POST['formdate'] == '' || $_POST['todate'] == '') {

        $result["type"] = "0";

        $result["result"] = "Kindly Select the Coupon Start & End Date Time";

        goto resultSI;
    }



    if (strtotime($_POST['formdate']) > strtotime($_POST['todate'])) {

        $result["type"] = "0";

        $result["result"] = "End Date is Greater Then for Start Date";

        goto resultSI;
    }



    $couponcode = select_query($con, "couponcode", "", "`c_code`='$coupon'", "", "");

    if ($couponcode['nr'] > 0) {

        $result["type"] = "0";

        $result["result"] = "Coupon Code Already Used";

        goto resultSI;
    }



    $gift_amt = select_top_name($con, "product", "rate", "`id` = '$productid'", "rate", "");

    if (intval($gift_amt) < 1) {

        $result["type"] = "0";

        $result["result"] = "Minimum Amount 1 AED";

        goto resultSI;
    }
    $c_name = BlockSQLInjection($_POST['cname']);
    $c_limit = BlockSQLInjection($_POST['climit']);
    $c_used_by = BlockSQLInjection($_POST['cusedby']);
    $product_id = BlockSQLInjection($_POST['camt']);
    $c_started_at = BlockSQLInjection($_POST['formdate']);
    $c_ended_at = BlockSQLInjection($_POST['todate']);


    $coupon_arr = [
        'c_name' => $c_name,
        'c_limit' => $c_limit,
        'c_used_by' => $c_used_by,
        'productid' => $product_id,
        'c_started_at' => $c_started_at,
        'c_ended_at' => $c_ended_at,
        // 'c_name' => $_POST['cname'],

        // 'c_limit' => $_POST['climit'],

        // 'c_used_by' => $_POST['cusedby'],

        // 'productid' => $_POST['camt'],

        // 'c_started_at' => $_POST['formdate'],

        // 'c_ended_at' => $_POST['todate'],

        'c_code' => $coupon,

        'createdon' => $dubaidate_time,

        'updatedon' => $dubaidate_time,

        'c_couponvalue' => intval($gift_amt),

        'c_createdby' => $_SESSION['memid'],

        'c_type' => 'Multiple'

    ];



    $coupin_ins = insert($con, "couponcode", "", $coupon_arr, "", "", "");

    if ($coupin_ins['id'] != '') {

        $result["type"] = "1";

        $result["result"] = 'Coupon Created Successfully';

        goto resultSI;
    } else {

        $result["type"] = "0";

        $result["result"] = 'Coupon Creation Failed';

        goto resultSI;
    }



    resultSI:
    echo json_encode($result);
} else if ($method == 'coupon_report') {
    $formdate = BlockSQLInjection($_POST["agdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);
    $coupontype = BlockSQLInjection($_POST["coupontype"]);

    // $formdate = $_POST['agdate'];
    // $todate = $_POST['todate'];
    // $coupontype = $_POST['coupontype'];

    if ($_SESSION['memid'] == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly Login First & Try Again";
        goto resultJ00;
    }
    $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC ", "roll_id", "");
    if ($formdate != '' && $todate != '') {
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "AND `createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59'";
    }

    $c_createdfor = '';

    if (isset($coupontype) && $coupontype == 'affiliate') {
        $c_createdfor = "AND c_createdfor IS NOT NULL";
    } else   if ($roll_id != 1) {
        $c_createdfor = "AND `c_createdfor` = '$_SESSION[memid]'";
    } else {
        $c_createdfor = "AND c_createdfor IS NULL";
    }


    $coupon = select_query($con, "couponcode", "", "`c_type` = 'Multiple' AND `deletes` = '0' $contype  $c_createdfor", "", "");
    if ($coupon['nr'] > 0) {
        foreach ($coupon['result'] as $key => $value) {
            $name = select_top_name($con, "user_register", "name", "`id`='$value[c_createdby]' and `deletes`='0'  order by `id` DESC ", "name", "");
            $action = '';

            if ($value['c_limit'] == $value['c_use_count']) {
                $status = 'Completed';
            } else if ($value['c_limit'] != $value['c_use_count'] && $value['c_limit'] > $value['c_use_count']) {
                $status = 'Active';
                if (strtotime($dubaidate_time) < strtotime($value['c_ended_at'])) {
                    if (in_array($roll_id, [1])) {

                        if ($value['status'] == 0) {
                            $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-ban" style="color: red;" onclick="couponDeactive(' . "'$value[id]'" . ')">&nbsp;Deactive</span></a>';
                        }
                        if (!isset($coupontype) && $coupontype = 'affiliate') {
                            $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-pencil-square-o" style="color: #4f5e3a;" onclick="editCoupon(' . "'$value[id]'" . ')">&nbsp;Edit</span></a>';
                        }
                    }
                }
            }


            if ($value['status'] == 2) {
                $status = 'Deactivated';
                if (strtotime($dubaidate_time) < strtotime($value['c_ended_at'])) {
                    if (in_array($roll_id, [1])) {
                        $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-toggle-on" style="color: darkblue;" onclick="couponActive(' . "'$value[id]'" . ')">&nbsp;Active</span></a>';
                    }
                }
            }

            $tic = '<a class="btn text-danger btn-sm" style="cursor: pointer;color: blue !important;"  onclick="viewCouponHistory(' . "'$value[id]'" . "," . "$value[c_use_count]" . ')">' . $value['c_use_count'] . '</a>';
            $result[] = ['ticketCount' =>  $tic, 'value' => $value['c_couponvalue'], 'name' => $value['c_name'], 'code' => $value['c_code'], 'usedby' => $value['c_used_by'], 'startdate' => date("d-M-Y g:i a", strtotime($value['c_started_at'])), 'limit' => $value['c_limit'], 'generateby' => $name, 'enddate' => date("d-M-Y g:i a", strtotime($value['c_ended_at'])), 'status' => $status, 'created_at' => date("d-M-Y g:i a", strtotime($value['createdon'])), 'action' => $action];
        }
    }


    resultJ00:
    echo json_encode($result);
}

else if ($method == 'get_new_coupon') {

    $result = [];



    reGenerate:

    $coupon = randomString(10);

    $couponcode = select_query($con, "couponcode", "", "`c_code`='$coupon'", "", "");

    if ($couponcode['nr'] > 0) {

        goto reGenerate;
    }



    $result["type"] = "1";

    $result["result"] = $coupon;

    echo json_encode($result);
} else if ($method == 'deactivate_coupon') {

    $result = [];

    $couponid = $_POST['couponid'];

    $reason = $_POST['reason'];

    if ($couponid == '') {

        $result["type"] = "0";

        $result["result"] = "Coupon ID Missing. Kindly Refresh and Try Again!";

        goto resultSAI;
    }



    if ($reason == '') {

        $result["type"] = "0";

        $result["result"] = "Kinldy Enter the Reason";

        goto resultSAI;
    }



    $inv_arr = ["status" => '2', "reason" => $reason, 'updatedon' => $dubaidate_time];

    $Inv_update = update($con, "couponcode", "`id` = '$couponid' and `status` = '0' and `deletes`='0'", $inv_arr, "", "", "", "");

    $errors = $Inv_update['errors'];

    if ($errors != "") {

        $result["type"] = "0";

        // $result["result"] = $errors;

        $result["result"] = "Deactivation Failed";

        goto resultSAI;
    } else {

        $result["type"] = "1";

        $result["result"] = "Deactivated Successfully";

        goto resultSAI;
    }



    resultSAI:

    echo json_encode($result);
} else if ($method == 'edit_get_details') {

    $result = [];

    $coupon = $_POST['id'];



    if ($_SESSION['memid'] == '') {

        $result["type"] = "0";

        $result["result"] = "Kindly Login First & Try Again";

        goto resultJ;
    }



    if ($coupon == '') {

        $result["type"] = "0";

        $result["result"] = "Kindly Login First & Try Again";

        goto resultJ;
    }



    $couponcode = select_query($con, "couponcode", "", "`id`='$coupon' ORDER BY `id` DESC LIMIT 1", "", "");

    if ($couponcode['nr'] > 0) {

        if ($couponcode['result'][0]['status'] == '2') {

            $result["type"] = "0";

            $result["result"] = "Coupon Deactivated So Could not be Edit!";

            goto resultJ;
        }



        if ($couponcode['result'][0]['status'] == '1') {

            $result["type"] = "0";

            $result["result"] = "Coupon Completed So Could not be Edit!";

            goto resultJ;
        }



        if ($couponcode['result'][0]['status'] == '0') {



            $result["type"] = "1";

            $result["coupon"] = $coupon;

            $result["c_limit"] = $couponcode['result'][0]['c_limit'];

            $result["c_code"] = $couponcode['result'][0]['c_code'];

            $result["c_used_by"] = $couponcode['result'][0]['c_used_by'];

            goto resultJ;
        }
    } else {

        $result["type"] = "0";

        $result["result"] = "Coupon Not Found!";

        goto resultJ;
    }



    resultJ:

    echo json_encode($result);
} else  if ($method == 'saveChanges') {

    $result = [];

    $couponid = $_POST['id'];

    if ($_SESSION['memid'] == '') {

        $result["type"] = "0";

        $result["result"] = "Kindly Login First & Try Again";

        goto resultSHI;
    }

    if ($couponid == '') {

        $result["type"] = "0";

        $result["result"] = "Coupon ID Missing. Kindly Refresh and Try Again!";

        goto resultSHI;
    }



    if ($_POST['cusedby'] == '') {

        $result["type"] = "0";

        $result["result"] = "Kindly Select the Coupon Used By";

        goto resultSHI;
    }



    if ($_POST['climit'] == '') {

        $result["type"] = "0";

        $result["result"] = "Kindly Enter the Coupon Limitations";

        goto resultSHI;
    }



    $inv_arr = ["c_used_by" => $_POST['cusedby'], "c_limit" => $_POST['climit'], 'updatedon' => $dubaidate_time];

    $Inv_update = update($con, "couponcode", "`id` = '$couponid' and `deletes`='0'", $inv_arr, "", "", "", "");

    $errors = $Inv_update['errors'];

    if ($errors != "") {

        $result["type"] = "0";

        // $result["result"] = $errors;

        $result["result"] = "Update Failed";

        goto resultSHI;
    } else {

        $result["type"] = "1";

        $result["result"] = "Updated Successfully";

        goto resultSHI;
    }



    resultSHI:

    echo json_encode($result);
} else if ($method == 'activate_coupon') {

    $result = [];

    $couponid = $_POST['couponid'];

    $reason = $_POST['reason'];

    if ($couponid == '') {

        $result["type"] = "0";

        $result["result"] = "Coupon ID Missing. Kindly Refresh and Try Again!";

        goto resultSBI;
    }



    if ($reason == '') {

        $result["type"] = "0";

        $result["result"] = "Kinldy Enter the Reason";

        goto resultSBI;
    }



    $inv_arr = ["status" => '0', "reason" => $reason, 'updatedon' => $dubaidate_time];

    $Inv_update = update($con, "couponcode", "`id` = '$couponid' and `status` = '2' and `deletes`='0'", $inv_arr, "", "", "", "");

    $errors = $Inv_update['errors'];

    if ($errors != "") {

        $result["type"] = "0";

        // $result["result"] = $errors;

        $result["result"] = "Activation Failed";

        goto resultSBI;
    } else {

        $result["type"] = "1";

        $result["result"] = "Activated Successfully";

        goto resultSBI;
    }



    resultSBI:

    echo json_encode($result);
} else if ($method == "list_couponticket") {



    // $result = [];
    $contype = '';
    $type = 'CT';
    $da = '';
    $draw_Con = '';

    $now = date('Y-m-d');
    $formdate = BlockSQLInjection($_POST["formdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);
    $searchTxt = BlockSQLInjectionforagent($_REQUEST["search_id"]);
    // $formdate = $_POST['formdate'];

    // $todate = $_POST['todate'];

    if ($formdate != '' && $todate != '') {

        $da = 'DESC';

        $fd = date("Y-m-d", strtotime($formdate));

        $td = date("Y-m-d", strtotime($todate));

        $contype1 = "AND cp.createdon BETWEEN '$fd 00:00:00' AND '$td 23:59:59' ";
        // $contype1 = "AND cticket.purchase_datetime BETWEEN '$fd 00:00:00' AND '$td 23:59:59' ";
    }
    else {

       
        
    }
    
    if ($searchTxt != '') {
        
        // $contype1 = '';
            $draw_Con = "AND (ur.name LIKE '%$searchTxt%' OR ur.email LIKE '%$searchTxt%' OR ur.mobile LIKE '%$searchTxt%' OR cc.c_code LIKE '%$searchTxt%') ";
    }

 
    if($searchTxt == '' && $formdate == '' && $todate == ''){
         $da = 'DESC';
        $contype1 = "AND cp.createdon LIKE '%$now%'";
        
    }


         

// var_dump("SELECT cp.*, ur.name, ur.lname, ur.mobile, ur.email, cc.c_code, cc.c_name FROM `couponcode_history` cp JOIN `user_register` ur ON cp.userid = ur.id JOIN `couponcode` cc ON cc.id = cp.couponcodeID WHERE cp.deletes='0' $contype1 $draw_Con;");die;

$selectQuery = "SELECT cp.*, ur.name, ur.lname, ur.mobile, ur.email, cc.c_code, cc.c_name FROM `couponcode_history` cp JOIN `user_register` ur ON cp.userid = ur.id JOIN `couponcode` cc ON cc.id = cp.couponcodeID WHERE cp.deletes='0' $contype1 $draw_Con;";


  $runQuery = mysqli_query($con, $selectQuery);
  
//   var_dump($runQuery);die;
        
  
    if ($runQuery && mysqli_num_rows($runQuery) > 0) {
          $result = [];
        
         while ($row = mysqli_fetch_assoc($runQuery)) {
             
            
                $ticket_ID = $row['ticket_id'];
                $coupon_ID = $row['couponCodeID'];
                // $coupon_ID = $row['couponCodeID'];
                $TicketNO = select_top_name($con, "ndticket", "ticketNo", "`id`='$ticket_ID' and `deletes`='0'", "ticketNo", "");
                $referenceID = select_top_name($con, "ndticket", "referenceID", "`id`='$ticket_ID' and `deletes`='0'", "referenceID", "");
                // $c_name = select_top_name($con, "couponcode", "c_name", "`id`='$coupon_ID' and `deletes`='0'", "c_name", "");
               
               
                 
   
                $result[] = [
                    "firstname" => $row['name'] .' '. $row['lname'],
                    "lastname" => $row['lname'],
                    "id" => $row['id'],
                    "mobile" => $row['mobile'],
                    "emailid" => $row['email'],
                    "createdon" => $row['createdon'],
                    "netTotal" => $row['amt'],
                    "TicketNO" => $TicketNO,
                    "referenceID" => $referenceID,
                    "coupon_code" => $row['c_code'],
                    "coupon_name" => $row['c_name'],
                    
                 
                   
                ];
                    }
        
        
        
    }

// var_dump($result);die;
    echo json_encode($result);
} else if ($method == "delete_ct_ticket") {



    try {

        $result = [];

        $transid = $_POST['transid'];

        $message = $_POST['message'];

        if ($transid != '') {

            if ($message != '') {

                $checkTicket = select_query($con, "cticket", "", "`transaction_id` = '$transid' AND `deletes`='0' ORDER BY `id` DESC", "", "");

                if ($checkTicket['nr'] > 0) {

                    $ticketID = $checkTicket['result'][0]['id'];

                    $ticketArr = ["deletes" => "1", "delete_reason" => $message];

                    $ticketUpdate = update($con, "cticket", "`id` = '$ticketID' AND `transaction_id` = '$transid' AND `deletes`='0' ORDER BY `id` DESC", $ticketArr, "", "", "", "");

                    $errors = $ticketUpdate['errors'];

                    if ($errors != "") {

                        $result["type"] = "0";

                        $result["result"] = $errors;
                    } else {

                        $pointTransArr = ["deletes" => "1"];

                        $ticketLinesUpdate = update($con, "ticket_lines", "`ticket_id` = '$ticketID' AND `type` = 'CT' AND `deletes`='0' ORDER BY `id` DESC", $pointTransArr, "", "", "", "");

                        $errors = $ticketLinesUpdate['errors'];

                        if ($errors != "") {

                            $result["type"] = "0";

                            $result["result"] = $errors;
                        } else {

                            $pointTransactionArr = ["deletes" => "1"];

                            $pointTransactionUpdate = update($con, "points_transaction", "`invoice_id` = '$ticketID' AND `type` = 'COUPON' AND `deletes`='0' ORDER BY `id` DESC", $pointTransactionArr, "", "", "", "");

                            $errors = $pointTransactionUpdate['errors'];

                            if ($errors != "") {

                                $result["type"] = "0";

                                $result["result"] = $errors;
                            } else {

                                $result["type"] = "1";

                                $result["result"] = "Ticket Deleted.";

                                goto Retrun;
                            }
                        }
                    }
                } else {

                    $result["type"] = "0";

                    $result["result"] = "Ticket Not Found.";

                    goto Retrun;
                }
            } else {

                $result["type"] = "0";

                $result["result"] = "Please Enter The Reason!";

                goto Retrun;
            }
        } else {

            $result["type"] = "0";

            $result["result"] = "Transaction ID Missing";

            goto Retrun;
        }

        Retrun:

        echo json_encode($result);
    } catch (Exception $e) {

        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];

        $cron_testarr = array("user_id" => $_SESSION['cusid'], "reason" => json_encode($error), "filename" => 'free_services.php', "draw_id" => '0', "creadedon" => $dubaidate_time);

        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == "generateaffiliatecopoun") {

    $result = [];
    $cname = BlockSQLInjection($_POST["cname"]);
    $totalcoupon = BlockSQLInjection($_POST["totalcoupon"]);
    $productid = BlockSQLInjection($_POST["camt"]);
    $user = BlockSQLInjection($_POST["user_id"]);
    $used_BY = $_POST["used_BY"];
    // $cname = $_POST['cname'];
    // $totalcoupon = $_POST['totalcoupon'];
    // $productid = $_POST['camt'];
    // $user = $_POST['user_id'];


    if ($_SESSION['memid'] == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly Login First & Try Again";
        goto resultCP;
    }



    if ($cname == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly Enter the Coupon Name";
        goto resultCP;
    }

    // if ($user == '') {
    //     $result["type"] = "0";
    //     $result["result"] = "Please Select User";
    //     goto resultCP;
    // }
    
    if ($used_BY == '') {
        $result["type"] = "0";
        $result["result"] = "Please Select Usef BY";
        goto resultCP;
    }
    

    if ($totalcoupon == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly Enter the Number of coupon";
        goto resultCP;
    }



    if ($totalcoupon > 100) {
        $result["type"] = "0";
        $result["result"] = "Maximum 100 coupon at once.";
        goto resultCP;
    }



    if ($productid == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly Select the Coupon Value";
        goto resultCP;
    }



    if ($_POST['formdate'] == '' || $_POST['todate'] == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly Select the Coupon Start & End Date Time";
        goto resultCP;
    }



    if (strtotime($_POST['formdate']) > strtotime($_POST['todate'])) {
        $result["type"] = "0";
        $result["result"] = "End Date is Greater Then for Start Date";
        goto resultCP;
    }




    $gift_amt = select_top_name($con, "product", "rate", "`id` = '$productid'", "rate", "");

    if (intval($gift_amt) < 1) {
        $result["type"] = "0";
        $result["result"] = "Minimum Amount 1 AED";
        goto resultCP;
    }

// var_dump('helow');die;


    $err = 0;

    for ($i = 1; $i <= $totalcoupon; $i++) {

        reGenerateCoupon:
        $coupon = randomString(10);
        
        // var_dump($coupon);die;

        $couponcode = select_query($con, "couponcode", "", "`c_code`='$coupon'", "", "");

        if ($couponcode['nr'] > 0) {
            goto reGenerateCoupon;
        }
        // var_dump($used_BY);die;

        $coupon_arr = [
            'c_name' => $cname,
            'c_limit' => "1",
            'c_used_by' => $used_BY,
            'productid' => $productid,
            'c_started_at' => $_POST['formdate'],
            'c_ended_at' => $_POST['todate'],
            'c_code' => $coupon,
            'createdon' => $dubaidate_time,
            'updatedon' => $dubaidate_time,
            'c_couponvalue' => intval($gift_amt),
            'c_createdby' => $_SESSION['memid'],
            'c_type' => 'Multiple',
            'user_id' => '0',
            // 'c_createdfor' => $user,
            'c_createdfor' => $_SESSION['memid'],
        ];

        $coupin_ins = insert($con, "couponcode", "", $coupon_arr, "", "", "");
        
        // var_dump($coupin_ins);die;

        if ($coupin_ins['id'] == '') {
            $err++;
        }
    }

    if ($err == 0) {
        $result["type"] = "1";
        $result["result"] = 'Coupon Created Successfully';
        goto resultCP;
    } else {
        $result["type"] = "0";
        $result["result"] = 'Coupon Creation Failed';
        goto resultCP;
    }

    resultCP:
    echo json_encode($result);
} elseif ($method == 'downloadaffiliatecopoun') {

    $user_id = $_REQUEST['user_id'];
    $date = $_REQUEST['date'];
    $user_name = select_top_name($con, "user_register", "name", "`id` = '$user_id'", "name", "");

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $filename = $user_name . "-Coupon-Code-" . date('ymdhis') . '.xlsx';

    $conditions = '';


    if (isset($date)) {
        $fd = date("Y-m-d", strtotime($date));
        $td = date("Y-m-d", strtotime($date));
        $contype = "AND `createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59'";
    }
    $getData = select_query($con, "couponcode", "", "`c_createdfor`='$user_id' '$conditions'", "", "");



    if ($getData['nr'] > 0) {
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'SR NO');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'COUPON CODE');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'LIMIT');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'COUPON STARTDATE');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'COUPON VALIDUPTO');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'TICKET USED');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'CREATED DATE');
        $col = 2;
        $srNo = 1;
        foreach ($getData['result'] as $key => $value) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $col, $srNo++);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $col, $value['c_code']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $col, $value['c_limit']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $col, $value['c_started_at']);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $col, $value['c_ended_at']);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $col, $value['c_use_count']);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $col, $value['createdon']);
            $col++;
        }

        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"');

        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_start();
        $objWriter->save("php://output");
        $xlsData = ob_get_contents();
        ob_end_clean();

        $response =  array(
            'op' => 'ok',
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData),
            'filename' => $filename
        );

        echo json_encode($response);
    }

} elseif ($method == 'list_coupon_history') {
    
    // var_dump('king');die;

    // $result = [];
    $contype = '';
    $type = 'CT';
    $da = '';
    $draw_Con = '';
    $draw1='';

    $now = date('Y-m-d');
    $formdate = BlockSQLInjection($_POST["formdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);
    $searchTxt = BlockSQLInjectionforagent($_REQUEST["search_id"]);
    // $formdate = $_POST['formdate'];

    // $todate = $_POST['todate'];
    // var_dump($formdate,$todate);die;

    if ($formdate != '' && $todate != '') {
        
        

        $da = 'DESC';

        $fd = date("Y-m-d", strtotime($formdate));

        $td = date("Y-m-d", strtotime($todate));

        $contype1 = "AND createdon BETWEEN '$fd 00:00:00' AND '$td 23:59:59' ";
        
        
        $draw1="HAVING 
            MIN(createdon) BETWEEN '$fd 00:00:00' AND '$td 23:59:59'";
    } 
    // else {

    //     $da = 'DESC';
    //     $contype1 = "AND createdon LIKE '%$now%'";
    //      $draw1=" HAVING
    //         createdon = '$contype1'";
        
    // }
    
    if ($searchTxt != '') {
        
        $contype1 = '';
            $draw_Con = "AND (c_name LIKE '%$searchTxt%') ";
            
           $draw1=" HAVING
            c_name = '$searchTxt'";

    }

 
    // var_dump($draw1);die;


//  var_dump("SELECT c_name, MIN(c_started_at) AS c_started_at, MIN(c_ended_at) AS c_ended_at, productid, MIN(createdon) AS first_createdon, COUNT(*) AS count, SUM(c_use_count) AS user FROM couponcode GROUP BY c_name, productid $draw1;");die;        



// $selectQuery = "SELECT c_name, MIN(c_started_at) AS c_started_at, MIN(c_ended_at) AS c_ended_at, productid, MIN(createdon) AS first_createdon, COUNT(*) AS count, SUM(c_use_count) AS user FROM couponcode WHERE deletes = 0 $draw_Con $contype1 GROUP BY c_name, productid;";
$selectQuery = "SELECT c_name, MIN(c_started_at) AS c_started_at, MIN(c_ended_at) AS c_ended_at, productid, MIN(createdon) AS first_createdon, COUNT(*) AS count, SUM(c_use_count) AS user FROM couponcode GROUP BY c_name, productid $draw1;";


  $runQuery = mysqli_query($con, $selectQuery);
  

        // var_dump($runQuery);die;
  
    if ($runQuery && mysqli_num_rows($runQuery) > 0) {
          $result = [];
        
         while ($row = mysqli_fetch_assoc($runQuery)) {
             
            
                $count = $row['count'];
                $user = $row['user'];
                $not_used = $count - $user;
                
             
               
               
                 
   
                $result[] = [
                    "c_name" => $row['c_name'],
                    "c_started_at" => $row['c_started_at'],
                    "c_ended_at" => $row['c_ended_at'],
                    "productid" => $row['productid'],
                    "createdon" => $row['first_createdon'],
                    "count" => $row['count'],
                    "user" => $row['user'],
                
                    "not_used" => $not_used,
                    
                    
                 
                   
                ];
                    }
        
        
        
    }

// var_dump($result);die;
    echo json_encode($result);
}

function randomString($length = 10)

{

    // Set the chars

    $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';



    // Count the total chars

    $totalChars = strlen($chars);



    // Get the total repeat

    $totalRepeat = ceil($length / $totalChars);



    // Repeat the string

    $repeatString = str_repeat($chars, $totalRepeat);



    // Shuffle the string result

    $shuffleString = str_shuffle($repeatString);



    // get the result random string

    return substr($shuffleString, 1, $length);
}
