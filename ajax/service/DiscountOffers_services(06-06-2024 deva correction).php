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




if ($method == "ND_invoice") {
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
    
    
    
    if($userType1 == 'deliveryToMe'){
        
        $userType="`deliveryType`= 'deliveryToMe' AND";
        
    } else if($userType1 == 'pickUpToStore'){
        
         $userType="`deliveryType`= 'pickUpToStore' AND";
    }else{
        $userType = '';
    }
    
     if ($searchTxt != '') {
        
        $contype = "";
        
        // $draw_Con = "(secertCode LIKE '$searchTxt') AND ";
        $draw_Con = "(secertCode LIKE '%" . $searchTxt . "%') AND ";
            
            
        }
        if($searchTxt == '' && $userType1 =='' && $formdate == '' && $todate == ''){
              $da = 'DESC';
        $contype = "`createdon` LIKE '%$now%' AND";
            
        }
    
      
       
        $NDticketquery = mysqli_query($con, "SELECT * FROM `shipping_request` WHERE $userType $contype $draw_Con `deletes` = '0'  ORDER BY `id` DESC;");
        
          $selectQuery = "SELECT * FROM `shipping_request` WHERE $userType $contype $draw_Con `deletes` = '0'  ORDER BY `id` DESC;";
       
          
          $runQuery = mysqli_query($con, $selectQuery);
        
  
    if ($runQuery && mysqli_num_rows($runQuery) > 0) {
          $result = [];
        
         while ($row = mysqli_fetch_assoc($runQuery)) {
             
             $userID = $row['userID'];
             $pay_id = $row['pickup_invoice_id'];
                        
                
                $name = select_top_name($con, "user_register", "name", "`id`='$userID' and `deletes`='0'", "name", "");
                $lname = select_top_name($con, "user_register", "lname", "`id`='$userID' and `deletes`='0'", "lname", "");
               
                $fullname = $name .' '. $lname;
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

    

} else if ($method == "Discount_Offers") {
    
    // var_dump('think');die;
     $contype = "";
     $Discout_type = '';

    $formdate = BlockSQLInjectionforagent($_POST["formdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    $select_dis_type = BlockSQLInjectionforagent($_POST["select_dis_type"]);
    
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
    
    if($select_dis_type != ''){
        $Discout_type = "`type`='$select_dis_type' AND";
        
    }
    
    // $Tracking_URL = $_POST['Tracking_URL'];
     $selectQuery = "SELECT * FROM `discount_periods` WHERE $contype $Discout_type `deletes` = '0'  ORDER BY `id` DESC;";
    //   var_dump($selectQuery);die;
          
    $runQuery = mysqli_query($con, $selectQuery);
        
  
    if ($runQuery && mysqli_num_rows($runQuery) > 0) {
          $result = [];
        
         while ($row = mysqli_fetch_assoc($runQuery)) {
             
             $userid = $row['userid'];
             $product_id = $row['product_id'];
                        
                
                $name = select_top_name($con, "user_register", "name", "`id`='$userid' and `deletes`='0'", "name", "");
                $lname = select_top_name($con, "user_register", "lname", "`id`='$userid' and `deletes`='0'", "lname", "");
                $fullname = $name .' '.$lname;
                $mobile = select_top_name($con, "user_register", "mobile", "`id`='$userid' and `deletes`='0'", "mobile", "");
                $email = select_top_name($con, "user_register", "email", "`id`='$userid' and `deletes`='0'", "email", "");
                
                $rate = select_top_name($con, "product", "rate", "`id`='$product_id' and `deletes`='0'", "rate", "");
                
                $end_date = $row['end_date'];
                $end_date_status = strtotime($end_date) >= strtotime(date('Y-m-d')) ? 'Active' : 'Inactive';
                
                
            
                 
   
                $result[] = [
                    "id" => $row['id'],
                    "userid" => $row['userid'],
                    "couponCode" => $row['couponCode'],
                    "type" => $row['type'],
                    // "type" => $row['type'],
                    "start_date" => $row['start_date'],
                    "end_date" => $row['end_date'],
                    "discount_name" => $row['discount_name'],
                    "fullname" => $fullname,
                    "mobile" => $mobile,
                    "email" => $email,
                    
                     "end_date_status" => $end_date_status,
                    
                    "product_id" => $row['product_id'],
                    "usedBy" => $row['usedBy'],
                    "reachedLimit" => $row['reachedLimit'],
                    "maxPurchaseLimit" => $row['maxPurchaseLimit'],
                    "rate" => $rate,
                    "discount_amount" => $row['discount_amount'],
                    "createdon" => $row['createdon'],
                    "deletes" => $row['deletes'],
                   
                   
                ];
                    }
        
        
        
    }



    echo json_encode($result);
    


} else if ($method == "update_Delete_status") {
    
     $rowId = BlockSQLInjectionforagent($_POST["id"]);
    
//   var_dump($rowId);die;

$updateQuery = "UPDATE discount_periods  SET deletes = '1', updatedon = '$dubaidate_time' WHERE id = '$rowId'";



  if (mysqli_query($con, $updateQuery)) {
        echo json_encode(array("type" => 1,"status" => "success", "message" => "Deleted successfully"));
    } else {
        echo json_encode(array("type" => 0,"status" => "error", "message" => "Error updating delivery status"));
    }



} else if ($method == "New_Discount_offers") {
    
     $startdate = BlockSQLInjectionforagent($_POST["startdate"]);
     $enddate = BlockSQLInjectionforagent($_POST["enddate"]);
     $product = BlockSQLInjectionforagent($_POST["product"]);
     $DiscountAED = BlockSQLInjectionforagent($_POST["DiscountAED"]);
     $DisName = BlockSQLInjectionforagent($_POST["DisName"]);
     $Dis_type = BlockSQLInjectionforagent($_POST["Dis_type"]);
     $Discount_code = BlockSQLInjectionforagent($_POST["Discount_code"]);
     $used_BY = BlockSQLInjectionforagent($_POST["used_BY"]);
     $couponLimit = BlockSQLInjectionforagent($_POST["couponLimit"]);
     
    //  var_dump($startdate,$enddate,$product,$DiscountAED,$DisName,$Dis_type,$Discount_code,$used_BY,$couponLimit);die;
    $type12 ='';
     
     if($Dis_type !='coupon' ){
         $Discount_code ='';
     }
      if($Dis_type =='coupon' ){
         $type12 = '';
        //  $Discount_code ='';
     }else{
         
         $type12 = "type != 'coupon' AND";
     }
     
     $product1 ="SELECT * FROM product WHERE id = '$product' AND  deletes = '0'";
    
     $productResult = mysqli_query($con, $product1);
     if (mysqli_num_rows($productResult) > 0) {
         
          while ($row = mysqli_fetch_assoc($productResult)) {
           $product_amount = intval($row['rate']);

             
          }
         
     }
     
     
    //  var_dump($Discount_code);die;
     
     $couponcode = select_query($con, "discount_periods", "", "`couponCode`='$Discount_code'", "", "");
    
    if($Dis_type =='coupon' && $couponcode['nr'] > 0){
        
        echo json_encode(array("type" => 0,"status" => "error", "message" => "The Coupon code already exists"));

      
    }else{
    
    // var_dump($DiscountAED,$product_amount,intval($DiscountAED) < intval($product_amount) ,intval($DiscountAED) == intval($product_amount) );die;
      
         if(intval($DiscountAED) >= intval($product_amount)  ){
            
            
            
             echo json_encode(array("type" => 0,"status" => "error", "message" => "Invalid Discount Amount!..."));
             
         } else{
            
             
             $test = "SELECT * FROM discount_periods WHERE product_id = '$product'  AND  deletes = '0'";
             
            
             $testResult = mysqli_query($con, $test);
             if (mysqli_num_rows($testResult) > 0) {
                
                 
                //   $checkQuery = "SELECT * FROM discount_periods WHERE product_id = '$product' AND((start_date < '$startdate' AND end_date < '$startdate') AND (start_date < '$enddate' AND end_date < '$enddate')) AND  deletes = '0' AND type !='coupon' LIMIT 1";
                if($Dis_type == 'general'){
                    
                    // var_dump('general');die;
                    
                    $checkQuery = "(SELECT * FROM `discount_periods` WHERE deletes = '0' AND type != 'coupon' AND product_id = '$product' AND start_date <= '$startdate' AND end_date >= '$startdate' LIMIT 1)
                    UNION ALL
                    (SELECT * FROM `discount_periods` WHERE deletes = '0' AND type != 'coupon' AND product_id = '$product' AND start_date <= '$enddate' AND end_date >= '$enddate' LIMIT 1)";
                      
                      
                      
                    // var_dump($checkQuery);die;
            
                       $checkResult = mysqli_query($con, $checkQuery);
                       if (mysqli_num_rows($checkResult) > 0) {
                           
                        //   var_dump('test1111');die;
                          
                           echo json_encode(array("type" => 0,"status" => "error", "message" => "The Discount of Product already exists"));
                 
                            
                            
                            
                        
                         } else{
                            //  var_dump('test222222');die;
                             
                              $insertQuery = "INSERT INTO discount_periods (userid,type,couponCode,usedBy,maxPurchaseLimit,start_date, end_date,discount_name, product_id, discount_amount, deletes, createdon, updatedon) 
                                    VALUES ($memid,'$Dis_type', UPPER('$Discount_code'),'$used_BY','$couponLimit','$startdate', '$enddate','$DisName', '$product', '$DiscountAED', '0', '$dubaidate_time', '$dubaidate_time')";
                    
                              if (mysqli_query($con, $insertQuery)) {
                                    echo json_encode(array("type" => 1,"status" => "success", "message" => "New Discount Created successfully"));
                                } else {
                                    echo json_encode(array("type" => 0,"status" => "error", "message" => "Error updating delivery status"));
                                }
                             
                         }
                    
                } else if($Dis_type == 'coupon'){
                    // var_dump('coupon');die;
                    
                    $insertQuery = "INSERT INTO discount_periods (userid,type,couponCode,usedBy,maxPurchaseLimit,start_date, end_date,discount_name, product_id, discount_amount, deletes, createdon, updatedon) 
                                    VALUES ($memid,'$Dis_type', UPPER('$Discount_code'),'$used_BY','$couponLimit','$startdate', '$enddate','$DisName', '$product', '$DiscountAED', '0', '$dubaidate_time', '$dubaidate_time')";
                    
                              if (mysqli_query($con, $insertQuery)) {
                                    echo json_encode(array("type" => 1,"status" => "success", "message" => "New Discount Created successfully"));
                                } else {
                                    echo json_encode(array("type" => 0,"status" => "error", "message" => "Error updating delivery status"));
                                }
                    
                }
                
                   
                   
                 
             }else{
                 
                 
                     
                     $insertQuery = "INSERT INTO discount_periods (userid,type,couponCode,usedBy,maxPurchaseLimit,start_date, end_date,discount_name, product_id, discount_amount, deletes, createdon, updatedon) 
                                VALUES ($memid,'$Dis_type', UPPER('$Discount_code'),'$used_BY','$couponLimit','$startdate', '$enddate','$DisName', '$product', '$DiscountAED', '0', '$dubaidate_time', '$dubaidate_time')";
                
                          if (mysqli_query($con, $insertQuery)) {
                                echo json_encode(array("type" => 1,"status" => "success", "message" => "New Discount Created successfully"));
                            } else {
                                echo json_encode(array("type" => 0,"status" => "error", "message" => "Error updating delivery status"));
                            }
                 
                
                 
             }
         
       
         
         
         
         }
    }
     
     
    



} else if ($method == 'get_new_coupon') {

    $result = [];
    
    // var_dump('frbs');die;



    reGenerate:
      

    $coupon = randomString(10);
    
    //   var_dump('thing11');die;

    $couponcode = select_query($con, "discount_periods", "", "`couponCode`='$coupon'", "", "");
    
    

    if ($couponcode['nr'] > 0) {

        goto reGenerate;
    }



    $result["type"] = "1";

    $result["result"] = $coupon;

    echo json_encode($result);

} elseif ($method == 'list_Discount_history') {
    
    // var_dump('king');die;

    // $result = [];
    $contype = '';
    $type = 'CT';
    $da = '';
    $draw_Con = '';
    $creatdate12='';
    $draw1 = '';
    $draw2 = '';
    $draw3 = '';
    $contype22 = '';

    $now = date('Y-m-d');
    // $formdate = BlockSQLInjection($_POST["formdate"]);
    // $todate = BlockSQLInjection($_POST["todate"]);
    $searchTxt = BlockSQLInjectionforagent($_REQUEST["search_id"]);
    $formdate = $_POST['formdate'];

    $todate = $_POST['todate'];
    // var_dump($formdate,$todate);die;

    if ($formdate != '' && $todate != '' && $searchTxt =='') {
        
       

        $da = 'DESC';

        $fd = date("Y-m-d", strtotime($formdate));

        $td = date("Y-m-d", strtotime($todate));

        $contype1 = "AND createdon BETWEEN '$formdate' AND '$todate' ";
        //  var_dump($contype1);die;
        
        $creatdate12="HAVING 
            MIN(createdon) BETWEEN '$formdate' AND '$todate' ";
    } 
   
    
    if ($searchTxt != '' && $formdate =='' && $todate =='') {
        
       
            
           $draw2=" HAVING
            c_name = '$searchTxt'";

    }
    
     if ($searchTxt != '' && $formdate !='' && $todate !='') {
        
        // $contype1 = '';
            // $draw_Con =  "WHERE c_name = '$searchTxt'";
            $draw_Con= "WHERE c_name = '$searchTxt' 
    AND createdon BETWEEN '$formdate' AND '$todate'" ;
            
        //   $draw3=" MIN(createdon) BETWEEN '2024-05-22 00:00:00' AND '2024-05-22 23:59:59'";

    }
    
    // if($formdate == '' && $todate == '' && $searchTxt == ''){
    //     $contype22 ="HAVING 
    // MIN(createdon) BETWEEN CURDATE() AND CURDATE() + INTERVAL 1 DAY";
    // }

 
// var_dump($creatdate12);die;


$selectQuery = "SELECT * FROM `discount_periods` WHERE deletes = '0';";


  $runQuery = mysqli_query($con, $selectQuery);
  

        // var_dump($runQuery);die;
  
    if ($runQuery && mysqli_num_rows($runQuery) > 0) {
          $result = [];
        
         while ($row = mysqli_fetch_assoc($runQuery)) {
             
             
             
                $id = $row['id'];
                $maxPurchaseLimit = $row['maxPurchaseLimit'];
                $reachedLimit = $row['reachedLimit'];
                $not_used = $maxPurchaseLimit - $reachedLimit;
                
                
                $product_id = $row['product_id'];
                $rate = select_top_name($con, "product", "rate", "`id`='$product_id' and `deletes`='0'", "rate", "");
                
             
               
               
                 
   
                $result[] = [
                    "id" => $row['id'],
                    "type" => $row['type'],
                    "discount_name" => $row['discount_name'],
                    "couponCode" => $row['couponCode'],
                    "userid" => $row['userid'],
                    "start_date" => $row['start_date'],
                    "end_date" => $row['end_date'],
                    "rate" => $rate,
                    "discount_amount" => $row['discount_amount'],
                    "maxPurchaseLimit" => $row['maxPurchaseLimit'],
                    "reachedLimit" => $row['reachedLimit'],
                    "createdon" => $row['createdon'],
                    
                
                    "not_used" => $not_used,
                    
                    
                 
                   
                ];
                    }
        
        
        
    }

// var_dump($result);die;
    echo json_encode($result);

} else if ($method == "list_Discountticket") {



    // $result = [];
    $contype = '';
    $type = 'CT';
    $da = '';
    $draw_Con = '';
    $Coupon_name1 = '';
   

    $now = date('Y-m-d');
    // $formdate = BlockSQLInjection($_POST["formdate"]);
    // $todate = BlockSQLInjection($_POST["todate"]);
    $searchTxt = BlockSQLInjectionforagent($_REQUEST["search_id"]);
    $Discout_id = BlockSQLInjectionforagent($_REQUEST["Discout_id"]);
    $formdate = $_POST['formdate'];

    $todate = $_POST['todate'];
    
    // $Coupon_name = $_POST['Coupon_nam'];
    
    // var_dump($Coupon_name);die;
    
    
    // var_dump($formdate,$todate);die;

    // if ($formdate != '' && $todate != '') {

    //     $da = 'DESC';

    //   $fd = date_format(date_create_from_format('Y-m-d H:i:s', $formdate), 'Y-m-d');
    // $td = date_format(date_create_from_format('Y-m-d H:i:s', $todate), 'Y-m-d');

   

    //     // $contype1 = "AND cp.createdon BETWEEN '$fd 00:00:00' AND '$td 23:59:59' ";
    //     $contype1 = "AND cp.createdon BETWEEN '$formdate' AND '$todate' ";
    // }
    // else {

       
        
    // }
    
    // if ($searchTxt != '') {
        
    //     // $contype1 = '';
    //         $draw_Con = "AND (ur.name LIKE '%$searchTxt%' OR ur.email LIKE '%$searchTxt%' OR ur.mobile LIKE '%$searchTxt%' OR cc.c_code LIKE '%$searchTxt%') ";
    // }
    
    if ($Discout_id != '') {
        
     
            $Coupon_name1 = "discountID ='$Discout_id' AND  ";
    }

 
    // if($searchTxt == '' && $formdate == '' && $todate == '' && $Coupon_name == ''){
    //      $da = 'DESC';
    //     $contype1 = "AND cp.createdon LIKE '%$now%'";
        
    // }



// var_dump("SELECT cp.*, ur.name, ur.lname, ur.mobile, ur.email, cc.c_code, cc.c_name FROM `couponcode_history` cp JOIN `user_register` ur ON cp.userid = ur.id JOIN `couponcode` cc ON cc.id = cp.couponcodeID WHERE cp.deletes='0' $contype1 $draw_Con $Coupon_name1;");die;

$selectQuery = "SELECT id,firstname,lastname,mobile,emailid,createdon,product_id,grandtotal,renewalStatus,discountID,ticketId,ticketReferenceID FROM `invoice` WHERE $Coupon_name1 deletes ='0' ";


  $runQuery = mysqli_query($con, $selectQuery);
  
    if ($runQuery && mysqli_num_rows($runQuery) > 0) {
          $result = [];
         while ($row = mysqli_fetch_assoc($runQuery)) {
             
            
                $ticket_ID = $row['ticketId'];
                $discountID = $row['discountID'];
                $product_id = $row['product_id'];
                $TicketNO = select_top_name($con, "ndticket", "ticketNo", "`id`='$ticket_ID' and `deletes`='0'", "ticketNo", "");
                // $referenceID = select_top_name($con, "ndticket", "ticketNo", "`id`='$ticket_ID' and `deletes`='0'", "ticketNo", "");
                $discount_name = select_top_name($con, "discount_periods", "discount_name", "`id`='$discountID' and `deletes`='0'", "discount_name", "");
                $couponCode = select_top_name($con, "discount_periods", "couponCode", "`id`='$discountID' and `deletes`='0'", "couponCode", "");
                $discount_amount = select_top_name($con, "discount_periods", "discount_amount", "`id`='$discountID' and `deletes`='0'", "discount_amount", "");
                $rate = select_top_name($con, "product", "rate", "`id`='$product_id' and `deletes`='0'", "rate", "");
               
   
                $result[] = [
                    "firstname" => $row['firstname'] .' '. $row['lastname'],
                    "lastname" => $row['lastname'],
                    "id" => $row['id'],
                    "mobile" => $row['mobile'],
                    "emailid" => $row['emailid'],
                    "createdon" => $row['createdon'],
                    "discount_name" => $discount_name,
                    "couponCode" => $couponCode,
                    "discount_amount" => $discount_amount,
                    "product_id" => $rate,
                    "grandtotal" => $row['grandtotal'],
                    
                    "TicketNO" => $TicketNO,
                    // "referenceID" => $referenceID,
                    "ticketReferenceID" => $row['ticketReferenceID'],
                    // "coupon_name" => $row['c_name'],
                    
                 
                   
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


