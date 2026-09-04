
<?php

include '../../include/shi-config.php';

include '../../include/functions.php';

$method=$_REQUEST['method'];


$user_id=$_REQUEST['user_id'];

if($method =='transaction_table'){
    $result = [];
    $query = select_query($con, "points_transaction", "", "(`from_id`='$user_id' || `to_id`='$user_id') and `from_id`!='' and `deletes`='0' ORDER BY `id` DESC", "", "");
    foreach ($query['result'] as $key => $transinfo) {
        $response4 = select_query($con, "invoice", "", "`id`='$transinfo[invoice_id]' and `deletes`='0'", "", "");

        $response5 = select_query($con, "ticket", "", "`invoice_no`='$transinfo[invoice_id]' and `deletes`='0'", "", "");

         $response6 = select_query($con, "wallet_payment_history", "", "`id` = '$transinfo[invoice_id]' and `deletes`='0'", "", "");

        
        $id=$transinfo['id'];
        // $time= date("d-M-Y g:i a", strtotime($transinfo[createdon]));
        $time=select_top_name($con, "points_transaction", "createdon", "`deletes`='0' and `id`='$id'ORDER BY `id` DESC ", "createdon", "");
        $type=strtoupper($transinfo['type']);

        $ticket_no = select_top_name($con, "ticket", "ticket_no", "`deletes`='0' and `id`='$transinfo[invoice_id]' ", "ticket_no", "");
        if ($transinfo['type'] == "order") {

            $transaction_id = select_top_name($con, "ticket", "transaction_id", "`deletes`='0' and `id`='$transinfo[invoice_id]' ", "transaction_id", "");

            $description= "Ticket ID: <a href='" . $baseurl . "ticket-view/" . $transaction_id . "' target='_blank'>#" . $ticket_no . "</a>";
          } else if ($transinfo['type'] == "credit") {
            
            $transaction_id = select_top_name($con, "ticket", "transaction_id", "`deletes`='0' and `invoice_no`='$transinfo[invoice_id]' ", "transaction_id", "");

            $response = select_top_name($con, "invoice", "response", "`deletes`='0' and `id`='$transinfo[invoice_id]' ", "response", "");

            $data = json_decode($response, true);

            $cardinfo = $data[_embedded][payment][0]['paymentMethod'][pan];

            $OrderReference = $data[_embedded][payment][0]['merchantOrderReference'];

            if ($response != '') {

              if ($response == "wallet") {

                $description= "Wallet Purchase,  Invoice No. <a href='" . $baseurl . "invoice/" . $transaction_id . "' target='_blank'>#" . $transinfo['invoice_id'] . "</a>";
              } else {

                $description="Reference ID: " . $OrderReference . " Card: " . $cardinfo . ".  Invoice No. <a href='" . $baseurl . "invoice/" . $transaction_id . "' target='_blank'>#" . $transinfo['invoice_id'] . "</a>";
              }
            } else {

              $wtransactionid = $response6[result][0][wtransactionid];

              $description='Transaction Id: ' . $wtransactionid;
            }
          }

          if ($transinfo['type'] == "order") {
           $Amount= "<span style='color:red; font-weight:600;  font-size:14px !important;'>-AED $transinfo[points] </span>";
           
         
         }else if ($transinfo['type'] == "credit") {
            $Amount= "<span style='color:#32a332; font-weight:600;  font-size:14px !important;'>AED
            $transinfo[points]
          </span>"; 
        } else if ($transinfo['type'] == "FREE") {
            $Amount= "<span style='color:#32a332; font-weight:600;  font-size:14px !important;'>AED
            $transinfo[points]
          </span>";
         }

         if ($transinfo['type'] == "order") {

            $balence= $transinfo['from_closing'];
          } else if ($transinfo['type'] == "credit") {

            $balence= $transinfo['to_closing'];
          } else if ($transinfo['type'] == "FREE") {
            $balence= $transinfo['to_closing'];
          }




     $result[] = array("ID" =>$id , "date" =>$time, "Type" => $type, "Description" => $description,"Amount" =>  $Amount, "TotalBalance" => $balence);
    }
   

    echo json_encode($result);


    
}




?>