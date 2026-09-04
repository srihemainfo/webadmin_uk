<?php
include 'include/shi-config.php';
include 'include/functions.php';
// echo 'hello';
 $aticket = select_query($con, "aticket", "", "`purchase_datetime` BETWEEN '2023-01-20 00:00:00' AND '2023-01-21 23:59:59' AND `deletes` = '0' AND `draw_id` != 0", "", "");
if($aticket['nr'] > 0){
  foreach($aticket['result'] as $key => $value){
     $getin = $value['invoice_no'];
     $totalAmount = $value['net_total'];
     $agent_id = $value['agent_id'];
     $earning_transaction  = select_query($con, "earning_transaction", "", "`invoice_id` LIKE '$getin'", "", "");
     if($earning_transaction['nr'] < 1){
         if ($totalAmount != '') {
                                $ag_data = select_query($con, "user_register", "", "`id` = '$agent_id' AND `deletes`='0'", "", "");
                                if ($ag_data['nr'] > 0) {
                                    $ag_id = $ag_data['result'][0]['id'];

                                    $t_point = floatval($ag_data['result'][0]['t_point']);
                                    $UT_point =  $t_point - $totalAmount;

                                    $point_arr = array("t_point" => $UT_point);
                                    $point_update = update($con, "user_register", "`id` = '$ag_id' and `deletes`='0'", $point_arr, "", "", "", "");
                                    $errors = $point_update['errors'];
                                    if ($errors != "") {
                                        $result["type"] = "0";
                                        $result["result"] = $errors;
                                    } else {
                                        $nowpoints = select_top_name($con, "ldbank", "points", "`deletes`='0' and `id`='9999999'  ", "points", "");
                                        $balancepoint = $nowpoints + $totalAmount;

                                        $ld_arr = array("points" => $balancepoint);
                                        $ld_update = update($con, "ldbank", "`deletes`='0' and `id`='9999999'", $ld_arr, "", "", "", "");
                                        $errors = $ld_update['errors'];
                                        if ($errors != "") {
                                            $result["type"] = "0";
                                            $result["result"] = $errors;
                                        } else {
                                            $Arrd = array("from_id" => "$ag_id", "type" => "sales", "points" => $totalAmount, "from_opening" => $t_point, "from_closing" => $UT_point, "to_id" => "9999999", "to_opening" => $nowpoints, "to_closing" => $balancepoint, "invoice_id" => $getin, "createdon" => $dubaidate_time);
                                            $point_trans = insert($con, "points_transaction", "", $Arrd, "", "", "");


                                            $agent_data = select_query($con, "user_register", "", "`id` = '$agent_id' AND `deletes`='0'", "", "");
                                            if ($agent_data['nr'] > 0) {
                                                $t_earning = floatval($agent_data['result'][0]['t_earning']);
                                                $roll_id_new = $agent_data['result'][0]['roll_id'];


                                                $a_com = select_query($con, "sales_commission", "", "`start_amt` <= '$t_earning' AND `end_amt` >= ' $t_earning' AND `roll` = '$roll_id_new' AND `deletes`='0'", "", "");
                                                if ($a_com['nr'] > 0) {

                                                    $level_1 = floatval($a_com['result'][0]['level_1']);
                                                    $level_2 = floatval($a_com['result'][0]['level_2']);
                                                    $level_3 = floatval($a_com['result'][0]['level_3']);
                                                    $level_per = array();

                                                    if ($level_3 != 0) {
                                                        array_push($level_per, $level_3);
                                                    }
                                                    if ($level_2 != 0) {
                                                        array_push($level_per, $level_2);
                                                    }
                                                    if ($level_1 != 0) {
                                                        array_push($level_per, $level_1);
                                                    }

                                                    foreach ($level_per as $value) {
                                                        $t_earning_new = select_top_name($con, "user_register", "t_earning", "`id` = '$ag_id'", "t_earning", "");
                                                        $deletes = select_top_name($con, "user_register", "deletes", "`id` = '$ag_id'", "deletes", "");
                                                        if (intval($deletes) == 0) {
                                                            $level_3_amt = $totalAmount * $value / 100;
                                                            $amt_3 = floatval($t_earning_new) + $level_3_amt;
                                                            $t_earning_arr = array("t_earning" => $amt_3);
                                                            $earn_update = update($con, "user_register", "`id` = '$ag_id' and `deletes`='0'", $t_earning_arr, "", "", "", "");
                                                            $errors = $earn_update['errors'];
                                                            if ($errors != "") {
                                                                $result["type"] = "0";
                                                                $result["result"] = $errors;
                                                            } else {
                                                                $Arrde = array("type" => "earn", "order_type" => "sales", "amount" => $level_3_amt, "to_id" => $ag_id, "to_opening" => $t_earning_new, "to_closing" => $amt_3, "invoice_id" => $getin, "createdon" => $dubaidate_time);
                                                                $erarn_trans = insert($con, "earning_transaction", "", $Arrde, "", "", "");
                                                                $created_by  = select_top_name($con, "user_register", "created_by", "`id` = '$ag_id' AND `deletes`='0'", "created_by", "");
                                                                if ($created_by != '') {
                                                                    $ag_id = $created_by;
                                                                }
                                                            }
                                                        } else {
                                                            $created_by  = select_top_name($con, "user_register", "created_by", "`id` = '$ag_id'", "created_by", "");
                                                            if ($created_by != '') {
                                                                $ag_id = $created_by;
                                                            }
                                                        }
                                                    }
                                                    $result["type"] = "1";
                                                    $result["result"] = "The Created Agent Ticket has been Sent Successfully.";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
         echo $value['invoice_no']. '</br>';
     }
      
  }  
}
die;

