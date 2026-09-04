<?php

include '../../include/shi-config.php';

include '../../include/functions.php';

$method=$_REQUEST['method'];


$user_id=$_REQUEST['user_id'];

if($method =='withdrawal_table'){
    $result = [];
    $contype = '';

    $da = '';

    $type = '';

    $now = date('Y-m-d');


    $with_his = select_query($con, "withdraw_request", "", "`from_id`=$user_id AND `deletes`='0' ORDER BY `createdon` DESC ", "", "");

    if ($with_his['nr'] > 0) {

        foreach ($with_his['result'] as $key => $value) {

            $from_name = select_top_name($con, "user_register", "name", "`id`='$value[from_id]' and `deletes`='0'", "name", "");

            $to_name = select_top_name($con, "user_register", "name", "`id`='$value[to_id]' and `deletes`='0'", "name", "");

            $t_earning = select_top_name($con, "user_register", "t_earning", "`id`='$value[from_id]' and `deletes`='0'", "t_earning", "");

            $From_roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$value[from_id]' and `deletes`='0'", "roll_id", "");

            if ($From_roll_id != 0) {

                $type = select_top_name($con, "role", "name", "`id`='$From_roll_id'", "name", "");
            } else {

                $type = 'customer';
            }

            $request_id = $value['request_id'];

            $status = '';

            $action = '';

            if ($value[status] == 0) {

                if ($roll_id == 1 || $roll_id == 2) {

                    $action .= '<a class="btn text-success btn-sm transfer" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-money" onclick="withdrawtransfer(' . "'$request_id'" . ')"> Transfer</span></a>';

                    $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-money" onclick="withdrawDecline(' . "'$request_id'" . ')"> Reject</span></a>';
                } else {

                    $action .= '';
                }
            }

            $status = '';

            if ($value[status] == 1) {

                $status = 'Success';
            } else if ($value[status] == 2) {

                $status = 'Reject';
            } else if ($value[status] == 0) {

                $status = 'Process';
            }

            if ($value[status] == 1 || $value[status] == 2) {

                $action .= '<a class="btn text-danger btn-sm transfer" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-edit" style="color: blue;" onclick="editWithDrawDetails(' . "'$request_id'" . ')"> Edit</span></a>';

                $action .= '<a class="btn text-danger btn-sm transfer" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-eye" style="color: #ff0000;" onclick="Bankdeatils(' . "'$request_id'" . ')"> Bank Details</span></a>';
            }

            $result[] = array("wbalance" => $t_earning, "trans_mode" => $value[trans_mode], "trans_id" => $value[transaction_id], "trans_date" => $value[trans_date], "type" => ucwords($type), "fromname" => ucwords($from_name), "id" => str_pad($value['id'], 7, "0", STR_PAD_LEFT), "Date" => date("d-M-Y g:i a", strtotime($value['createdon'])), "Amount" => $value['amount'], "RequestId" => $request_id, "To" => ucwords($to_name), "Status" => $status, "action" => $action);

            // $result['result'][] = array("type" => ucwords($type), "fromname" => ucwords($from_name), "id" => str_pad($value['id'], 7, "0", STR_PAD_LEFT), "date" => date("d-M-Y g:i a", strtotime($value['createdon'])), "amt" => $value['amount'], "request_id" => $request_id, "toname" => ucwords($to_name), "status" => ($value[status] == 0) ? 'Pending' : 'Success', "action" => $action);

        }

  

    } else {


    }



}


echo json_encode($result);

?>