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

    $formdate = BlockSQLInjectionforagent($_POST["formdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    
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
    
    // $Tracking_URL = $_POST['Tracking_URL'];
     $selectQuery = "SELECT * FROM `discount_periods` WHERE $contype `deletes` = '0'  ORDER BY `id` DESC;";
       
          
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
                    "start_date" => $row['start_date'],
                    "end_date" => $row['end_date'],
                    "discount_name" => $row['discount_name'],
                    "fullname" => $fullname,
                    "mobile" => $mobile,
                    "email" => $email,
                    
                     "end_date_status" => $end_date_status,
                    
                    "product_id" => $row['product_id'],
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
     
     
     
     
     
     $product1 ="SELECT * FROM product WHERE id = '$product' AND  deletes = '0'";
    
     $productResult = mysqli_query($con, $product1);
     if (mysqli_num_rows($productResult) > 0) {
         
          while ($row = mysqli_fetch_assoc($productResult)) {
           $product_amount = intval($row['rate']);

             
          }
         
     }
    
      
     if(intval($DiscountAED) > intval($product_amount)){
        
        
         echo json_encode(array("type" => 0,"status" => "error", "message" => "Invalid Discount Amount!..."));
         
     } else{
         
         
         $test = "SELECT * FROM discount_periods WHERE product_id = '$product'  AND  deletes = '0'";
         
        //  var_dump($test);die;
         $testResult = mysqli_query($con, $test);
         if (mysqli_num_rows($testResult) > 0) {
            //  var_dump('king');die;
             
              $checkQuery = "SELECT * FROM discount_periods WHERE product_id = '$product' AND((start_date < '$startdate' AND end_date < '$startdate') AND (start_date < '$enddate' AND end_date < '$enddate')) AND  deletes = '0'";
            //   var_dump($checkQuery);die;
    
               $checkResult = mysqli_query($con, $checkQuery);
               if (mysqli_num_rows($checkResult) > 0) {
                   
                //   var_dump('hdevas');die;
         
                      $insertQuery = "INSERT INTO discount_periods (userid,start_date, end_date,discount_name, product_id, discount_amount, deletes, createdon, updatedon) 
                            VALUES ($memid,'$startdate', '$enddate','$DisName', '$product', '$DiscountAED', '0', '$dubaidate_time', '$dubaidate_time')";
            
                      if (mysqli_query($con, $insertQuery)) {
                            echo json_encode(array("type" => 1,"status" => "success", "message" => "New Discount Created successfully"));
                        } else {
                            echo json_encode(array("type" => 0,"status" => "error", "message" => "Error updating delivery status"));
                        }
                
                 } else{
                     
                     echo json_encode(array("type" => 0,"status" => "error", "message" => "The Discount of Product already exists"));
                     
              
                     
                 }
               
               
             
         }else{
            //  var_dump('hellwo');die;
              $insertQuery = "INSERT INTO discount_periods (userid,start_date, end_date,discount_name, product_id, discount_amount, deletes, createdon, updatedon) 
                            VALUES ($memid,'$startdate', '$enddate','$DisName', '$product', '$DiscountAED', '0', '$dubaidate_time', '$dubaidate_time')";
            
                      if (mysqli_query($con, $insertQuery)) {
                            echo json_encode(array("type" => 1,"status" => "success", "message" => "New Discount Created successfully"));
                        } else {
                            echo json_encode(array("type" => 0,"status" => "error", "message" => "Error updating delivery status"));
                        }
         }
     
   
     
     
     
     }
     
     
    


}


