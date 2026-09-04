<?php



include '../../include/shi-config.php';

include '../../include/functions.php';

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

// var_dump($_SESSION);die;

$result = array();

$post_csrf = $headers['X-Csrf-Token'] ?? '';

///////// NEW //////////
if ($method == "searchTicket") {



    $result = [];



    $type_Con = '';



    $draw_Con = '';



    $tablename = '';


    $draw_new_id = BlockSQLInjectionforreportrange($_REQUEST["draw_id"]);
    $ticket_name = BlockSQLInjectionforreportrange($_REQUEST["ticket_name"]);
    // $draw_new_id = $_REQUEST['draw_id'];



    // $ticket_name = $_REQUEST['ticket_name'];



    // $now = date('Y-m-d');


    $agdate = BlockSQLInjectionforagent($_POST["agdate"]);

    // $agdate = $_POST['agdate'];



    if ($agdate != '') {

        $newDD = date("Y-m-d", strtotime($agdate));

        $datefilter = "`createdon` BETWEEN '$newDD 00:00:00' AND '$newDD 23:59:59' AND";
    } else {

        $datefilter = "";
    }





    if ($ticket_name == 'MT') {

        $tablename = 'mticket';
    } else if ($ticket_name == 'AT') {

        $tablename = 'aticket';
    } else if ($ticket_name == 'OT') {

        $tablename = 'ticket';
    } else if ($ticket_name == 'WT') {

        if ($draw_new_id > 33) {

            $ticket_name = 'WT';

            $tablename = 'aticket';
        } else {

            $ticket_name = 'OT';

            $tablename = 'ticket';
        }
    } else if ($ticket_name == 'FT') {

        $tablename = 'fticket';
    } else if ($ticket_name == 'CT') {

        $tablename = 'cticket';
    } else if ($ticket_name == 'BP') {

        $tablename = 'bpticket';
    } else if ($ticket_name == 'CP') {

        $tablename = 'cpticket';
    } else if ($ticket_name == 'KT') {

        $tablename = 'kticket';
    }



    if ($draw_new_id != '') {

        $draw_Con = "`draw_id` = '$draw_new_id' AND";
    }



    if ($ticket_name != '') {

        $type_Con = "`type` = '$ticket_name' AND";
    }



    $Ticket_lines = select_query($rcon, "ticket_lines", "", "$draw_Con $type_Con $datefilter `deletes`='0'", "", "");

    if ($Ticket_lines['nr'] > 0) {

        foreach ($Ticket_lines['result'] as $key => $value) {

            if ($value['invoice_no'] != '') {

                $invoice = select_top_name($rcon, "invoice", "response", "`id`='$value[invoice_no]' and `deletes`='0'", "response", "");
            }



            $ticket_id = $value['ticket_id'];



            $proid = $value['product_id'];



            if ($value['type'] == 'MT') {

                $tablename = 'mticket';
            } else if ($value['type'] == 'AT') {

                $tablename = 'aticket';
            } else if ($value['type'] == 'OT') {

                $tablename = 'ticket';
            } else if ($value['type'] == 'FT') {

                $tablename = 'fticket';
            } else if ($value['type'] == 'WT') {

                $tablename = 'wticket';
            } else if ($value['type'] == 'CT') {

                $tablename = 'cticket';
            } else if ($value['type'] == 'BP') {

                $tablename = 'bpticket';
            } else if ($value['type'] == 'CP') {

                $tablename = 'cpticket';
            } else if ($value['type'] == 'KT') {

                $tablename = 'kticket';
            }





            $Ticket_lines123 = select_query($rcon, $tablename, "createdon,purchase_datetime,ticket_no,user_id,transaction_id", "`id`='$ticket_id' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");

            if ($Ticket_lines123['nr'] > 0) {

                $purchase_datetime = $Ticket_lines123['result'][0]['createdon'];

                $ticket_no = $Ticket_lines123['result'][0]['ticket_no'];

                $user_id = $Ticket_lines123['result'][0]['user_id'];

                $transaction_id = $Ticket_lines123['result'][0]['transaction_id'];
            }



            // $purchase_datetime = select_top_name($con, $tablename, "purchase_datetime", "`id`='$ticket_id' and `deletes`='0'", "purchase_datetime", "");

            // $ticket_no = select_top_name($con, $tablename, "ticket_no", "`id`='$ticket_id' and `deletes`='0'", "ticket_no", "");

            // $user_id = select_top_name($con, $tablename, "user_id", "`id`='$ticket_id' and `deletes`='0'", "user_id", "");

            // $transaction_id = select_top_name($con, $tablename, "transaction_id", "`id`='$ticket_id' and `deletes`='0'", "transaction_id", "");



            $user_register = select_query($rcon, "user_register", "name,mobile,email", "`id`='$user_id' ORDER BY `id` DESC LIMIT 1", "", "");

            if ($user_register['nr'] > 0) {

                $name =  $user_register['result'][0]['name'];
                $lname =  $user_register['result'][0]['lname'];
                $cusname = $name . ' ' . $lname;
                $mobile = $user_register['result'][0]['mobile'];

                $email = $user_register['result'][0]['email'];
            }


            $proamt = select_top_name($rcon, "product", "rate", "`id`='$proid' and `deletes`='0'", "rate", "");



            if ($value['type'] == 'AT') {

                $agent_id = select_top_name($rcon, "aticket", "agent_id", "`id`='$ticket_id' ORDER BY `id` DESC", "agent_id", "");

                $agent_hhname = select_top_name($rcon, "user_register", "name", "`id`='$agent_id' ORDER BY `id` DESC", "name", "");
                $agent_lname = select_top_name($rcon, "user_register", "lname", "`id`='$agent_id' ORDER BY `id` DESC", "lname", "");
                $agent_name = $agent_hhname . ' ' . $agent_lname;
            } else {

                $agent_name = '';
            }



            if ($draw_new_id > 33) {
            } else {

                if ($_REQUEST['ticket_name'] != '') {

                    if (trim($_REQUEST['ticket_name']) == 'WT') {

                        if ($invoice != 'wallet') {

                            goto Result;
                        }
                    } else if (trim($_REQUEST['ticket_name']) == 'OT') {

                        if ($invoice == 'wallet') {

                            goto Result;
                        }
                    }
                }
            }





            $result[] = ["agentname" => $agent_name, "transaction_id" => $transaction_id, "paymentgate" => $paymentgateway, "proamt" => $proamt, "RaffleID" => $value['raffle_id'], "My3Numbers" => $value['my3number'], "email" => $email, "mobile" => $mobile, "cusname" => $cusname, "ticketno" => $ticket_no, "purdate" => date("d-M-Y g:i a", strtotime($purchase_datetime))];

            Result:
        }
    }



    echo json_encode($result);
}else if ($method == "tickReport") {

    $result = [];

    $now = date('Y-m-d');
    var_dump($now = date('Y-m-d'));
    $agdate = BlockSQLInjectionforagent($_POST["agdate"]);

    // $agdate = $_POST['agdate'];

    if ($agdate != '') {
        $newDD = date("Y-m-d", strtotime($agdate));

        $contype = "`createdon` BETWEEN '$newDD 00:00:00' AND '$newDD 23:59:59' AND";
    } else {

        $contype = "`createdon` LIKE '%$now%' AND";
    }

    if ($_SESSION['memid'] != 1) {

        $agent = "`agent_id` = '$_SESSION[memid]' AND";
    } else {

        $agent = "";
    }
    $NDticketquery = mysqli_query($con, "SELECT nd.*, ur.name, ur.lname, ur.mobile, ur.email,ur2.name AS agentName,nd FROM `ndticket` nd JOIN `user_register` ur ON nd.userId = ur.id JOIN `user_register` ur2 ON nd.agentId = ur2.id WHERE " . $contype . " nd.agentId != 0 AND nd.id != '' AND nd.deletes = '0'");
    if (mysqli_num_rows($NDticketquery) > 0) {
        $result = mysqli_fetch_all($NDticketquery, MYSQLI_ASSOC);
    }

    echo json_encode($result);
}else if ($method == "agentEarning") {

    $result = [];

    $now = date('Y-m-d');
    $agdate = BlockSQLInjectionforagent($_POST["agdate"]);

    // $agdate = $_POST['agdate'];

    if ($agdate != '') {

        $newDD = date("Y-m-d", strtotime($agdate));

        $contype = "`createdon` BETWEEN '$newDD 00:00:00' AND '$newDD 23:59:59' AND";
    } else {

        $contype = "`createdon` LIKE '%$now%' AND";
    }

    if ($_SESSION['memid'] != 1) {

        $agent = "`agent_id` = '$_SESSION[memid]' AND";
    } else {

        $agent = "";
    }

    $user_register = select_query($rcon, "user_register", "", "`roll_id` in ('3', '4', '5') and `deletes`='0'", "", "");

    if ($user_register['nr'] > 0) {

        $i = 1;

        foreach ($user_register['result'] as $key => $value) {

            $agent_id = $value['id'];

            $position = select_top_name($rcon, "role", "name", "`id` = '$value[roll_id]'", "name", "");

            $agent_earning = select_query_sum($con, "aticket", "net_total", "$contype `agent_id` = '$agent_id' and `deletes`='0' order by `id` DESC", "", "");

            $aticket = select_query($con, "aticket", "", "$contype `agent_id` = '$agent_id' and `deletes`='0' order by `id` DESC", "", "");



            $action = '<button class="btn btn-primary" onclick="viewagenthistory(' . "'$agent_id'" . ')">History</button>';

            $result[] = array("soldticket" => $aticket['nr'], "agpos" => $position, "agentid" => $agent_id, "sino" => $i, "date" => date("d-M-Y", strtotime($agdate)), "name" => $value['name'] . ' ' . ($value['lname'] ?? ''), "amt" => $agent_earning, "action" => $action);

            $i++;
        }
    } else {
    }



    echo json_encode($result);
} else if ($method == "walletlist") {

    $user_register = select_query($con, "user_register", "", "`t_earning` > '0' AND `deletes`='0'", "", "");

    if ($user_register['nr'] > 0) {

        foreach ($user_register['result'] as $key => $value) {

            $id = $value['id'];

            $action = '';

            if ($value['roll_id'] == 0) {

                $position = 'customer';
            } else {

                $position = select_top_name($con, "role", "name", "`id` = '$value[roll_id]'", "name", "");
            }

            $rollid = select_top_name($con, "user_register", "roll_id", "`id` = '$_SESSION[memid]' and `deletes` = '0'", "roll_id", "");

            if ($rollid == 1 || $rollid == 2) {

                $action .= '<div class="g-2">';

                $action .= '<a target="_blank"  class="btn text-primary btn-sm" data-bs-toggle="tooltip" onclick="settlement(' . "'$id'" . ')" data-bs-original-title="View Receipt"><span class="fa fa-check-circle-o"> Settlement</span></a>';

                $action .= '</div>';
            }

            $result[] = array("position" => ucwords($position), "name" => $value['name'], "walbalance" => $value['t_earning'], "mobile" => $value['mobile'], "email" => $value['email'], "action" => $action);
        }
    } else {
    }

    echo json_encode($result);
} else if ($method == 'withdraw_transfer_verify') {

    $result = [];

    $request = $_POST['request'];

    $user_register = select_query($con, "user_register", "", "`id` = '$request' AND `deletes`='0' ORDER by `id` DESC", "", "");

    if ($user_register['nr'] > 0) {

        $amount = $user_register['result'][0]['t_earning'];

        $id = $user_register['result'][0]['id'];

        $name = $user_register['result'][0]['name'];

        $mobile = $user_register['result'][0]['mobile'];

        $email = $user_register['result'][0]['email'];

        if (floatval($amount) > 0) {

            $output = '';

            $output .= '<form class="login100-form validate-form">

            <div class="row" style="justify-content: center;">

                    <div class="row mb-3">

                            <div class="col-4">

                                    <p class="mdtext">Transfer To </p>

                            </div>

                            <div class="col-2">

                            <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" data-bs-placement="bottom" data-html="true"  title="' . $mobile . ' / ' . $email . '"><span style="cursor: pointer;color: #136e1a !important;" class="fa fa-exchange""></span></a>

                            </div>

                            <div class="col-6">

                                    <p>' . $name . '</p>

                            </div>

                    </div>

                    <div class="row mb-3">

                                <div class="col-6">

                                         <p class="mdtext">Withdraw Amount </p>

                                </div>

                                <div class="col-6">

                                <input type="text" name="withdrawamt" id="withdrawamt" class="form-control bg-wrapper" oninput="this.value = this.value.replace(/[^0-9]/g, ' . '' . ');">

                                </div>

                    </div>';

            $output .= '<div class="row mb-3">

                                <div class="col-6">

                                         <p class="mdtext">Transaction Mode</p>

                                </div>

                                <div class="col-6">

                                <select class="form-select" id="transactionmode">

                                <option value="">Select Mode</option>

                                <option value="Cash">Cash</option>

                                <option value="Bank">Bank</option>

                                <option value="Cheque">Cheque</option>

                                </select>

                                </div>

                                </div>





                                <div class="row mb-3">

                                        <div class="col-6">

                                                <p class="mdtext">Transaction ID</p>

                                        </div>

                                        <div class="col-6">

                                        <input class="input101 input100 border-start-0 ms-0 form-control" id="withdrawid" type="text" placeholder="Enter Transaction Id">

                                        </div>

                                </div>

                                <div class="row mb-3">

                                <div class="col-6">

                                        <p class="mdtext">Transaction Date</p>

                                </div>

                                <div class="col-6">

                                <input class="input101 input100 border-start-0 ms-0 form-control" id="withdrawdate" type="date" placeholder="Enter Transaction Id">

                                </div>

                        </div>



                        <div class="row mb-3">

                        <textarea id="reasontext" placeholder="Reason"></textarea>

                        </div>





                        </div>

        </form>';

            $withdraw = '<button class="btn btn-primary" onclick="transferwdamount(' . "'$id'" . ')">Transfer</button>';

            $result['type'] = '1';

            $result['result'] = $output;

            $result['withdraw'] = $withdraw;
        } else {

            $result['type'] = '0';

            $result['result'] = '<div class="alert alert-danger" role="alert">User balance is low!</div>';
        }
    } else {

        $result['type'] = '0';

        $result['result'] = '<div class="alert alert-danger" role="alert">User Not Found!</div>';
    }

    echo json_encode($result);
} else if ($method == 'transfer_wd') {

    $result = [];

    $request = $_POST['request'];

    $mode = $_POST['mode'];

    $transid = $_POST['transid'];

    $date = $_POST['date'];

    $reasontext = $_POST['reasontext'];

    $withdrawamt = $_POST['withdrawamt'];

    if ($request != '' && $mode != '' && $transid != '' && $date != '' && $reasontext != '' && $withdrawamt != '') {

        $user_register = select_query($con, "user_register", "", "`id` = '$request' AND `deletes`='0' ORDER by `id` DESC", "", "");

        if ($user_register['nr'] > 0) {

            $from_earn = $user_register['result'][0]['t_earning'];

            $from_id = $user_register['result'][0]['id'];

            $name = $user_register['result'][0]['name'];

            $mobile = $user_register['result'][0]['mobile'];

            $email = $user_register['result'][0]['email'];

            if (floatval($from_earn) >= floatval($withdrawamt)) {

                RE:

                $request_id = 'WR' . uniqid(15) . date('his');

                $req_check = select_query($con, "withdraw_request", "", "`request_id`='$request_id' and `deletes`='0'", "", "");

                if ($req_check['nr'] > 0) {

                    goto RE;
                }

                $t_total = floatval($from_earn) - floatval($withdrawamt);

                $point_arr1 = array("t_earning" => $t_total);

                $point_update1 = update($con, "user_register", "`id` = '$from_id' and `deletes`='0'", $point_arr1, "", "", "", "");

                $errors = $point_update1['errors'];

                if ($errors != "") {

                    $result["type"] = "0";

                    $result["result"] = $errors;
                } else {

                    $withdraw_arr = array("submited_by" => $_SESSION['memid'], "reasontext" => $reasontext, "trans_mode" => $mode, "transaction_id" => $transid, "trans_date" => $date, "status" => '1', "request_id" => $request_id, "from_id" => $from_id, "to_id" => '1', "amount" => $withdrawamt, "deletes" => '0', "createdon" => $dubaidate_time);

                    $with_draw_ins = insert($con, "withdraw_request", "", $withdraw_arr, "", "", "");

                    $result['type'] = '1';

                    $result['result'] = '<div class="alert alert-success" role="alert">Transfer Amount Successfully</div>';
                }
            } else {

                $result['type'] = '0';

                $result['result'] = '<div class="alert alert-danger" role="alert">User balance is low!</div>';

                $result['transbtn'] = '<button class="btn btn-primary" onclick="transferwdamount(' . "'$request'" . ')">Transfer</button>';
            }
        } else {

            $result['type'] = '0';

            $result['result'] = '<div class="alert alert-danger" role="alert">User Not Found!</div>';

            $result['transbtn'] = '<button class="btn btn-primary" onclick="transferwdamount(' . "'$request'" . ')">Transfer</button>';
        }
    } else {

        $result['type'] = '0';

        $result['result'] = '<div class="alert alert-danger" role="alert">Please Fill All Fields.</div>';

        $result['transbtn'] = '<button class="btn btn-primary" onclick="transferwdamount(' . "'$request'" . ')">Transfer</button>';
    }

    echo json_encode($result);

} else if ($method == "winnerList") {
    
    // var_dump('welcome1234321');die;

    try {

        $result = [];
        $draw_new_id = BlockSQLInjectionforagent($_REQUEST["draw_id"]);
        $ticket_name = BlockSQLInjectionforagent($_REQUEST["ticket_name"]);
        // $draw_new_id = $_REQUEST['draw_id'];

        // $ticket_name = $_REQUEST['ticket_name'];
        $draw_type = BlockSQLInjectionforagent($_REQUEST["draw_type"]);
        $searchTxt = BlockSQLInjectionforagent($_REQUEST["searchTxt"]);

        // $draw_type = $_REQUEST['draw_type'];
        // $searchTxt = $_REQUEST['searchTxt'];


        $now = date('Y-m-d');

        $agdate = $_POST['agdate'];
        $draw_Con='';

        // if ($agdate != '') {

        //     $newDD = date("Y-m-d", strtotime($agdate));

        //     $draw_Con .= "`createdon` BETWEEN '$newDD 00:00:00' AND '$newDD 23:59:59' AND";
        // } else {

        //     $draw_Con  .= "";
        // }



        if ($draw_new_id != '') {

            $draw_Con .= "`draw_id` = '$draw_new_id' AND ";
        } else {

            $draw_Con .= "";
        }



        // if ($draw_type == 'raffle_draw') {

        //     $draw_Con .= "`raffle_id` IS NOT NUll AND ";
        // } elseif ($draw_type == 'ball_draw') {

        //     $draw_Con .=  "`my3number` IS NOT NUll AND ";
        // }




        if ($searchTxt != '') {
            $draw_Con .=  "(`fullName` LIKE '%$searchTxt%' OR `email` LIKE '%$searchTxt%' OR `mobile` LIKE '%$searchTxt%') AND ";
        }
        
        


        // $winnerlist = select_query($rcon, "winnerlist", "", "$draw_Con  `userid` != '' ORDER BY `id` DESC", "", "");
        // $winnerlist = select_query($rcon, "winnerlist", "", "$draw_Con  `userid` != '' AND `winningDrawName` =='Daily Thrill' ORDER BY `id` DESC", "", "");
        $winnerlist = select_query($rcon, "winnerlist", "", "$draw_Con `userid` != '' AND `drawType` = 'dailyThrill' ORDER BY `id` DESC", "", "");
        
        

        
        // var_dump($winnerlist);die;
        
        
        if ($winnerlist['nr'] > 0) {

            foreach ($winnerlist['result'] as $key => $value) {
                
                $cusname = $value['fullName'];
                $mobile = $value['mobile'];
                $email = $value['email'];
                $soldby = '';
                $winningNumber = $value['winRaffleId'];
                $DrawName = $value['winningDrawName'];
                // $ticket_lines_id = $value['ticket_lines_id'];
                
                //  $action = '<a class="btn text-danger btn-sm" data-bs-target="#uploadimg" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; "class="fa fa-edit edit_product"  data-id="' . $value['id'] . '" onclick="uploadimg(' . $value['id'] . ')"></span></a> <a class="btn text-danger btn-sm" target="_blank"  href="raffleacknowledgement.php?id=' . $value['id'] . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size:24px" class="fa fa-trash" onclick="deleteinfo(' . $value['id'] . ')"></span></a>';
                 
                  $action = ' 
                  <a class="btn text-danger btn-sm" target="_blank"  href="acknowledgement.php?id=' . $value['id'] . '&DrawName=' . $DrawName . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a>
                  ';

                
                $result[] = array("soldby" => $soldby, "name" => $cusname, "email" => $email, "mobile" => $mobile, "id_i" => $value['id'], "RaffleNO" => $winningNumber, "TicketID" => $value['ticketID'], "prize" => $value['prize'], "prizeamt" => $value['prize_amt'], 'action' => $action);
                
            }
            
        }
        



//         if ($winnerlist['nr'] > 0) {

//             foreach ($winnerlist['result'] as $key => $value) {

//                 $user_id = $value['userid'];
                
                

//                 $ticket_lines_id = $value['ticket_lines_id'];
                
                



//                 $cusname = select_top_name($rcon, "user_register", "name", "`id`='$user_id' and `deletes`='0'", "name", "");

//                 $cusname = $value['name'];

//                 $mobile = select_top_name($rcon, "user_register", "mobile", "`id`='$user_id' and `deletes`='0'", "mobile", "");

//                 $email = select_top_name($rcon, "user_register", "email", "`id`='$user_id' and `deletes`='0'", "email", "");

//                 $type = select_top_name($rcon, "ticket_lines", "type", "`id`='$ticket_lines_id' and `deletes`='0'", "type", "");



//                 $soldby = '';


                
//                  $action = '<a class="btn text-danger btn-sm" data-bs-target="#uploadimg" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; "class="fa fa-edit edit_product"  data-id="' . $value['id'] . '" onclick="uploadimg(' . $value['id'] . ')"></span></a> <a class="btn text-danger btn-sm" target="_blank"  href="raffleacknowledgement.php?id=' . $value['id'] . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size:24px" class="fa fa-trash" onclick="deleteinfo(' . $value['id'] . ')"></span></a>';
                 
//                   $action = '<a class="btn text-danger btn-sm" data-bs-target="#uploadimg" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; "class="fa fa-edit edit_product"  data-id="' . $value['id'] . '" onclick="uploadimg(' . $value['id'] . ')"></span></a> <a class="btn text-danger btn-sm" target="_blank"  href="acknowledgement.php?id=' . $value['id'] . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size:24px" class="fa fa-trash" onclick="deleteinfo(' . $value['id'] . ')"></span></a>';




// // var_dump('king');die;



//                 // if ($draw_type == 'raffle_draw') {

//                 //     $action = '<a class="btn text-danger btn-sm" data-bs-target="#uploadimg" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; "class="fa fa-edit edit_product"  data-id="' . $value['id'] . '" onclick="uploadimg(' . $value['id'] . ')"></span></a> <a class="btn text-danger btn-sm" target="_blank"  href="raffleacknowledgement.php?id=' . $value['id'] . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size:24px" class="fa fa-trash" onclick="deleteinfo(' . $value['id'] . ')"></span></a>';
//                 // } else {

//                 //     $action = '<a class="btn text-danger btn-sm" data-bs-target="#uploadimg" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; "class="fa fa-edit edit_product"  data-id="' . $value['id'] . '" onclick="uploadimg(' . $value['id'] . ')"></span></a> <a class="btn text-danger btn-sm" target="_blank"  href="acknowledgement.php?id=' . $value['id'] . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size:24px" class="fa fa-trash" onclick="deleteinfo(' . $value['id'] . ')"></span></a>';
//                 // }

//                 if ($ticket_name != '') {

//                     if ($ticket_name == $type) {



//                         if ($draw_type == 'raffle_draw') {

//                             $winningNumber = strtoupper($value['raffle_id']);
//                         } else {

//                             $winningNumber = $value['my3number'];
//                         }

//                         $result[] = array("soldby" => $soldby, "name" => $cusname, "email" => $email, "mobile" => $mobile, "id_i" => $value['id'], "My3number" => $winningNumber, "prize" => $value['prize'], "prizeamt" => $value['prize_amt'], 'action' => $action);
//                     }
//                 } else {



//                     if ($draw_type == 'raffle_draw') {

//                         $winningNumber = strtoupper($value['raffle_id']);
//                     } else {

//                         $winningNumber = $value['my3number'];
//                     }



//                     $result[] = array("soldby" => $soldby, "name" => $cusname, "email" => $email, "mobile" => $mobile, "id_i" => $value['id'], "My3number" => $winningNumber, "prize" => $value['prize'], "prizeamt" => $value['prize_amt'], 'action' => $action);
//                 }
//             }
//         }



        echo json_encode($result);
    } catch (Exception $e) {

        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];

        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'report_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);

        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == "winnerListss") {
    
    // var_dump('welcome1234321');die;

    try {

        $result = [];
        $draw_new_id = BlockSQLInjectionforagent($_REQUEST["draw_id"]);
        $ticket_name = BlockSQLInjectionforagent($_REQUEST["ticket_name"]);
        // $draw_new_id = $_REQUEST['draw_id'];

        // $ticket_name = $_REQUEST['ticket_name'];
        $draw_type = BlockSQLInjectionforagent($_REQUEST["draw_type"]);
        $searchTxt = BlockSQLInjectionforagent($_REQUEST["searchTxt"]);

        // $draw_type = $_REQUEST['draw_type'];
        // $searchTxt = $_REQUEST['searchTxt'];


        $now = date('Y-m-d');

        $agdate = $_POST['agdate'];
        $draw_Con='';

        // if ($agdate != '') {

        //     $newDD = date("Y-m-d", strtotime($agdate));

        //     $draw_Con .= "`createdon` BETWEEN '$newDD 00:00:00' AND '$newDD 23:59:59' AND";
        // } else {

        //     $draw_Con  .= "";
        // }



        if ($draw_new_id != '') {

            $draw_Con .= "`draw_id` = '$draw_new_id' AND ";
        } else {

            $draw_Con .= "";
        }



        // if ($draw_type == 'raffle_draw') {

        //     $draw_Con .= "`raffle_id` IS NOT NUll AND ";
        // } elseif ($draw_type == 'ball_draw') {

        //     $draw_Con .=  "`my3number` IS NOT NUll AND ";
        // }




        if ($searchTxt != '') {
            $draw_Con .=  "(`fullName` LIKE '%$searchTxt%' OR `email` LIKE '%$searchTxt%' OR `mobile` LIKE '%$searchTxt%') AND ";
        }
        
        


        // $winnerlist = select_query($rcon, "winnerlist", "", "$draw_Con  `userid` != '' ORDER BY `id` DESC", "", "");
        // $winnerlist = select_query($rcon, "winnerlist", "", "$draw_Con  `userid` != '' AND `winningDrawName` =='Daily Thrill' ORDER BY `id` DESC", "", "");
        $winnerlist = select_query($rcon, "winnerlist", "", "$draw_Con `userid` != '' AND `drawType` = 'dailyThrill' ORDER BY `id` DESC", "", "");
        
        

        
        // var_dump($winnerlist);die;
        
        
        if ($winnerlist['nr'] > 0) {

            foreach ($winnerlist['result'] as $key => $value) {
                
                $cusname = $value['fullName'];
                $mobile = $value['mobile'];
                $email = $value['email'];
                $soldby = '';
                $winningNumber = $value['winRaffleId'];
                $DrawName = $value['winningDrawName'];
                // $ticket_lines_id = $value['ticket_lines_id'];
                
                //  $action = '<a class="btn text-danger btn-sm" data-bs-target="#uploadimg" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; "class="fa fa-edit edit_product"  data-id="' . $value['id'] . '" onclick="uploadimg(' . $value['id'] . ')"></span></a> <a class="btn text-danger btn-sm" target="_blank"  href="raffleacknowledgement.php?id=' . $value['id'] . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size:24px" class="fa fa-trash" onclick="deleteinfo(' . $value['id'] . ')"></span></a>';
                 
                  $action = ' 
                  <a class="btn text-danger btn-sm" target="_blank"  href="acknowledgement.php?id=' . $value['id'] . '&DrawName=' . $DrawName . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a>
                  ';

                
                $result[] = array("soldby" => $soldby, "name" => $cusname, "email" => $email, "mobile" => $mobile, "id_i" => $value['id'], "RaffleNO" => $winningNumber, "TicketID" => $value['ticketID'], "prize" => $value['prize'], "prizeamt" => $value['prize_amt'], 'action' => $action);
                
            }
            
        }
        



//         if ($winnerlist['nr'] > 0) {

//             foreach ($winnerlist['result'] as $key => $value) {

//                 $user_id = $value['userid'];
                
                

//                 $ticket_lines_id = $value['ticket_lines_id'];
                
                



//                 $cusname = select_top_name($rcon, "user_register", "name", "`id`='$user_id' and `deletes`='0'", "name", "");

//                 $cusname = $value['name'];

//                 $mobile = select_top_name($rcon, "user_register", "mobile", "`id`='$user_id' and `deletes`='0'", "mobile", "");

//                 $email = select_top_name($rcon, "user_register", "email", "`id`='$user_id' and `deletes`='0'", "email", "");

//                 $type = select_top_name($rcon, "ticket_lines", "type", "`id`='$ticket_lines_id' and `deletes`='0'", "type", "");



//                 $soldby = '';


                
//                  $action = '<a class="btn text-danger btn-sm" data-bs-target="#uploadimg" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; "class="fa fa-edit edit_product"  data-id="' . $value['id'] . '" onclick="uploadimg(' . $value['id'] . ')"></span></a> <a class="btn text-danger btn-sm" target="_blank"  href="raffleacknowledgement.php?id=' . $value['id'] . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size:24px" class="fa fa-trash" onclick="deleteinfo(' . $value['id'] . ')"></span></a>';
                 
//                   $action = '<a class="btn text-danger btn-sm" data-bs-target="#uploadimg" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; "class="fa fa-edit edit_product"  data-id="' . $value['id'] . '" onclick="uploadimg(' . $value['id'] . ')"></span></a> <a class="btn text-danger btn-sm" target="_blank"  href="acknowledgement.php?id=' . $value['id'] . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size:24px" class="fa fa-trash" onclick="deleteinfo(' . $value['id'] . ')"></span></a>';




// // var_dump('king');die;



//                 // if ($draw_type == 'raffle_draw') {

//                 //     $action = '<a class="btn text-danger btn-sm" data-bs-target="#uploadimg" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; "class="fa fa-edit edit_product"  data-id="' . $value['id'] . '" onclick="uploadimg(' . $value['id'] . ')"></span></a> <a class="btn text-danger btn-sm" target="_blank"  href="raffleacknowledgement.php?id=' . $value['id'] . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size:24px" class="fa fa-trash" onclick="deleteinfo(' . $value['id'] . ')"></span></a>';
//                 // } else {

//                 //     $action = '<a class="btn text-danger btn-sm" data-bs-target="#uploadimg" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; "class="fa fa-edit edit_product"  data-id="' . $value['id'] . '" onclick="uploadimg(' . $value['id'] . ')"></span></a> <a class="btn text-danger btn-sm" target="_blank"  href="acknowledgement.php?id=' . $value['id'] . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size:24px" class="fa fa-trash" onclick="deleteinfo(' . $value['id'] . ')"></span></a>';
//                 // }

//                 if ($ticket_name != '') {

//                     if ($ticket_name == $type) {



//                         if ($draw_type == 'raffle_draw') {

//                             $winningNumber = strtoupper($value['raffle_id']);
//                         } else {

//                             $winningNumber = $value['my3number'];
//                         }

//                         $result[] = array("soldby" => $soldby, "name" => $cusname, "email" => $email, "mobile" => $mobile, "id_i" => $value['id'], "My3number" => $winningNumber, "prize" => $value['prize'], "prizeamt" => $value['prize_amt'], 'action' => $action);
//                     }
//                 } else {



//                     if ($draw_type == 'raffle_draw') {

//                         $winningNumber = strtoupper($value['raffle_id']);
//                     } else {

//                         $winningNumber = $value['my3number'];
//                     }



//                     $result[] = array("soldby" => $soldby, "name" => $cusname, "email" => $email, "mobile" => $mobile, "id_i" => $value['id'], "My3number" => $winningNumber, "prize" => $value['prize'], "prizeamt" => $value['prize_amt'], 'action' => $action);
//                 }
//             }
//         }



        echo json_encode($result);
    } catch (Exception $e) {

        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];

        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'report_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);

        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
}else if ($method == "winnerListConsolation") {
    
    // var_dump('welcome');die;

    try {

        $result = [];
        $draw_new_id = BlockSQLInjectionforagent($_REQUEST["draw_id"]);
        // var_dump($draw_new_id);die;
        $ticket_name = BlockSQLInjectionforagent($_REQUEST["ticket_name"]);
        // $draw_new_id = $_REQUEST['draw_id'];

        // $ticket_name = $_REQUEST['ticket_name'];
        $draw_type = BlockSQLInjectionforagent($_REQUEST["draw_type"]);
        $searchTxt = BlockSQLInjectionforagent($_REQUEST["searchTxt"]);

        // $draw_type = $_REQUEST['draw_type'];
        // $searchTxt = $_REQUEST['searchTxt'];


        $now = date('Y-m-d');

        $agdate = $_POST['agdate'];

        // if ($agdate != '') {

        //     $newDD = date("Y-m-d", strtotime($agdate));

        //     $draw_Con .= "`createdon` BETWEEN '$newDD 00:00:00' AND '$newDD 23:59:59' AND";
        // } else {

        //     $draw_Con  .= "";
        // }



        if ($draw_new_id != '') {

            $draw_Con .= "`draw_id` = '$draw_new_id' AND ";
        } else {

            $draw_Con .= "";
        }



        // if ($draw_type == 'raffle_draw') {

        //     $draw_Con .= "`raffle_id` IS NOT NUll AND ";
        // } elseif ($draw_type == 'ball_draw') {

        //     $draw_Con .=  "`my3number` IS NOT NUll AND ";
        // }


// var_dump($searchTxt);die;

        if ($searchTxt != '') {
            $draw_Con .=  "(`fullName` LIKE '%$searchTxt%' OR `email` LIKE '%$searchTxt%' OR `mobile` LIKE '%$searchTxt%') AND ";
            
            
        }



        // $winnerlist = select_query($rcon, "winnerlist", "", "$draw_Con  `userid` != '' ORDER BY `id` DESC", "", "");
        $winnerlist = select_query($rcon, "winnerlist", "", "$draw_Con `userid` != '' AND `drawType` = 'dailyConsolation' ORDER BY `id` DESC", "", "");
        
       
        
        // var_dump($winnerlist);die;
        
        
        if ($winnerlist['nr'] > 0) {

            foreach ($winnerlist['result'] as $key => $value) {
                
                $cusname = $value['fullName'];
                $mobile = $value['mobile'];
                $email = $value['email'];
                $soldby = '';
                $winningNumber = $value['winRaffleId'];
                $DrawName = $value['winningDrawName'];
                // $ticket_lines_id = $value['ticket_lines_id'];
                
                
                 
               
                // $action = '<a class="btn text-danger btn-sm" data-bs-target="#uploadimg" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; "class="fa fa-edit edit_product"  data-id="' . $value['id'] . '" onclick="uploadimg(' . $value['id'] . ')"></span></a> <a class="btn text-danger btn-sm" target="_blank"  href="acknowledgement.php?id=' . $value['id'] . '&DrawName=' . $DrawName . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size:24px" class="fa fa-trash" onclick="deleteinfo(' . $value['id'] . ')"></span></a>';
                $action = ' 
                  <a class="btn text-danger btn-sm" target="_blank"  href="acknowledgement.php?id=' . $value['id'] . '&DrawName=' . $DrawName . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a>
                  ';

                
                $result[] = array("soldby" => $soldby, "name" => $cusname, "email" => $email, "mobile" => $mobile, "id_i" => $value['id'], "RaffleNO" => $winningNumber, "TicketID" => $value['ticketID'], "prize" => $value['prize'], "prizeamt" => $value['prize_amt'], 'action' => $action);
                
            }
            
        }
        



//         if ($winnerlist['nr'] > 0) {

//             foreach ($winnerlist['result'] as $key => $value) {

//                 $user_id = $value['userid'];
                
                

//                 $ticket_lines_id = $value['ticket_lines_id'];
                
                



//                 $cusname = select_top_name($rcon, "user_register", "name", "`id`='$user_id' and `deletes`='0'", "name", "");

//                 $cusname = $value['name'];

//                 $mobile = select_top_name($rcon, "user_register", "mobile", "`id`='$user_id' and `deletes`='0'", "mobile", "");

//                 $email = select_top_name($rcon, "user_register", "email", "`id`='$user_id' and `deletes`='0'", "email", "");

//                 $type = select_top_name($rcon, "ticket_lines", "type", "`id`='$ticket_lines_id' and `deletes`='0'", "type", "");



//                 $soldby = '';


                
//                  $action = '<a class="btn text-danger btn-sm" data-bs-target="#uploadimg" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; "class="fa fa-edit edit_product"  data-id="' . $value['id'] . '" onclick="uploadimg(' . $value['id'] . ')"></span></a> <a class="btn text-danger btn-sm" target="_blank"  href="raffleacknowledgement.php?id=' . $value['id'] . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size:24px" class="fa fa-trash" onclick="deleteinfo(' . $value['id'] . ')"></span></a>';
                 
//                   $action = '<a class="btn text-danger btn-sm" data-bs-target="#uploadimg" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; "class="fa fa-edit edit_product"  data-id="' . $value['id'] . '" onclick="uploadimg(' . $value['id'] . ')"></span></a> <a class="btn text-danger btn-sm" target="_blank"  href="acknowledgement.php?id=' . $value['id'] . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size:24px" class="fa fa-trash" onclick="deleteinfo(' . $value['id'] . ')"></span></a>';




// // var_dump('king');die;



//                 // if ($draw_type == 'raffle_draw') {

//                 //     $action = '<a class="btn text-danger btn-sm" data-bs-target="#uploadimg" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; "class="fa fa-edit edit_product"  data-id="' . $value['id'] . '" onclick="uploadimg(' . $value['id'] . ')"></span></a> <a class="btn text-danger btn-sm" target="_blank"  href="raffleacknowledgement.php?id=' . $value['id'] . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size:24px" class="fa fa-trash" onclick="deleteinfo(' . $value['id'] . ')"></span></a>';
//                 // } else {

//                 //     $action = '<a class="btn text-danger btn-sm" data-bs-target="#uploadimg" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; "class="fa fa-edit edit_product"  data-id="' . $value['id'] . '" onclick="uploadimg(' . $value['id'] . ')"></span></a> <a class="btn text-danger btn-sm" target="_blank"  href="acknowledgement.php?id=' . $value['id'] . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size:24px" class="fa fa-trash" onclick="deleteinfo(' . $value['id'] . ')"></span></a>';
//                 // }

//                 if ($ticket_name != '') {

//                     if ($ticket_name == $type) {



//                         if ($draw_type == 'raffle_draw') {

//                             $winningNumber = strtoupper($value['raffle_id']);
//                         } else {

//                             $winningNumber = $value['my3number'];
//                         }

//                         $result[] = array("soldby" => $soldby, "name" => $cusname, "email" => $email, "mobile" => $mobile, "id_i" => $value['id'], "My3number" => $winningNumber, "prize" => $value['prize'], "prizeamt" => $value['prize_amt'], 'action' => $action);
//                     }
//                 } else {



//                     if ($draw_type == 'raffle_draw') {

//                         $winningNumber = strtoupper($value['raffle_id']);
//                     } else {

//                         $winningNumber = $value['my3number'];
//                     }



//                     $result[] = array("soldby" => $soldby, "name" => $cusname, "email" => $email, "mobile" => $mobile, "id_i" => $value['id'], "My3number" => $winningNumber, "prize" => $value['prize'], "prizeamt" => $value['prize_amt'], 'action' => $action);
//                 }
//             }
//         }



        echo json_encode($result);
    } catch (Exception $e) {

        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];

        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'report_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);

        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == "BumpewinnerList") {
    
    // var_dump('winnerListBumper');die;

    try {

        $result = [];
        $draw_new_id = BlockSQLInjectionforagent($_REQUEST["draw_id"]);
        $ticket_name = BlockSQLInjectionforagent($_REQUEST["ticket_name"]);
        // $draw_new_id = $_REQUEST['draw_id'];

        // $ticket_name = $_REQUEST['ticket_name'];
        $draw_type = BlockSQLInjectionforagent($_REQUEST["draw_type"]);
        $searchTxt = BlockSQLInjectionforagent($_REQUEST["searchTxt"]);

        // $draw_type = $_REQUEST['draw_type'];
        // $searchTxt = $_REQUEST['searchTxt'];


        $now = date('Y-m-d');

        $agdate = $_POST['agdate'];

        // if ($agdate != '') {

        //     $newDD = date("Y-m-d", strtotime($agdate));

        //     $draw_Con .= "`createdon` BETWEEN '$newDD 00:00:00' AND '$newDD 23:59:59' AND";
        // } else {

        //     $draw_Con  .= "";
        // }



        if ($draw_new_id != '') {

            $draw_Con .= "`draw_id` = '$draw_new_id' AND ";
        } else {

            $draw_Con .= "";
        }



        // if ($draw_type == 'raffle_draw') {

        //     $draw_Con .= "`raffle_id` IS NOT NUll AND ";
        // } elseif ($draw_type == 'ball_draw') {

        //     $draw_Con .=  "`my3number` IS NOT NUll AND ";
        // }




        if ($searchTxt != '') {
            $draw_Con .=  "(`name` LIKE '%$searchTxt%' OR `email` LIKE '%$searchTxt%' OR `mobile` LIKE '%$searchTxt%') AND ";
        }



        // $winnerlist = select_query($rcon, "winnerlist", "", "$draw_Con  `userid` != '' ORDER BY `id` DESC", "", "");
        $winnerlist = select_query($rcon, "winnerlist", "", "$draw_Con `userid` != '' AND `drawType` = 'monthlyBumper' ORDER BY `id` DESC", "", "");
        
        // var_dump($winnerlist);die;
        
        
        if ($winnerlist['nr'] > 0) {

            foreach ($winnerlist['result'] as $key => $value) {
                
                $cusname = $value['fullName'];
                $mobile = $value['mobile'];
                $email = $value['email'];
                $soldby = '';
                $winningNumber = $value['winRaffleId'];
                $DrawName = $value['winningDrawName'];
                // $ticket_lines_id = $value['ticket_lines_id'];
                
                // $action = '<a class="btn text-danger btn-sm" data-bs-target="#uploadimg" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; "class="fa fa-edit edit_product"  data-id="' . $value['id'] . '" onclick="uploadimg(' . $value['id'] . ')"></span></a> <a class="btn text-danger btn-sm" target="_blank"  href="acknowledgement.php?id=' . $value['id'] . '&DrawName=' . $DrawName . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size:24px" class="fa fa-trash" onclick="deleteinfo(' . $value['id'] . ')"></span></a>';
                $action = ' 
                  <a class="btn text-danger btn-sm" target="_blank"  href="acknowledgement.php?id=' . $value['id'] . '&DrawName=' . $DrawName . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a>
                  ';

                
                $result[] = array("soldby" => $soldby, "name" => $cusname, "email" => $email, "mobile" => $mobile, "id_i" => $value['id'], "RaffleNO" => $winningNumber, "TicketID" => $value['ticketID'], "prize" => $value['prize'], "prizeamt" => $value['prize_amt'], 'action' => $action);
                
            }
            
        }
        



//         if ($winnerlist['nr'] > 0) {

//             foreach ($winnerlist['result'] as $key => $value) {

//                 $user_id = $value['userid'];
                
                

//                 $ticket_lines_id = $value['ticket_lines_id'];
                
                



//                 $cusname = select_top_name($rcon, "user_register", "name", "`id`='$user_id' and `deletes`='0'", "name", "");

//                 $cusname = $value['name'];

//                 $mobile = select_top_name($rcon, "user_register", "mobile", "`id`='$user_id' and `deletes`='0'", "mobile", "");

//                 $email = select_top_name($rcon, "user_register", "email", "`id`='$user_id' and `deletes`='0'", "email", "");

//                 $type = select_top_name($rcon, "ticket_lines", "type", "`id`='$ticket_lines_id' and `deletes`='0'", "type", "");



//                 $soldby = '';


                
//                  $action = '<a class="btn text-danger btn-sm" data-bs-target="#uploadimg" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; "class="fa fa-edit edit_product"  data-id="' . $value['id'] . '" onclick="uploadimg(' . $value['id'] . ')"></span></a> <a class="btn text-danger btn-sm" target="_blank"  href="raffleacknowledgement.php?id=' . $value['id'] . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size:24px" class="fa fa-trash" onclick="deleteinfo(' . $value['id'] . ')"></span></a>';
                 
//                   $action = '<a class="btn text-danger btn-sm" data-bs-target="#uploadimg" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; "class="fa fa-edit edit_product"  data-id="' . $value['id'] . '" onclick="uploadimg(' . $value['id'] . ')"></span></a> <a class="btn text-danger btn-sm" target="_blank"  href="acknowledgement.php?id=' . $value['id'] . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size:24px" class="fa fa-trash" onclick="deleteinfo(' . $value['id'] . ')"></span></a>';




// // var_dump('king');die;



//                 // if ($draw_type == 'raffle_draw') {

//                 //     $action = '<a class="btn text-danger btn-sm" data-bs-target="#uploadimg" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; "class="fa fa-edit edit_product"  data-id="' . $value['id'] . '" onclick="uploadimg(' . $value['id'] . ')"></span></a> <a class="btn text-danger btn-sm" target="_blank"  href="raffleacknowledgement.php?id=' . $value['id'] . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size:24px" class="fa fa-trash" onclick="deleteinfo(' . $value['id'] . ')"></span></a>';
//                 // } else {

//                 //     $action = '<a class="btn text-danger btn-sm" data-bs-target="#uploadimg" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; "class="fa fa-edit edit_product"  data-id="' . $value['id'] . '" onclick="uploadimg(' . $value['id'] . ')"></span></a> <a class="btn text-danger btn-sm" target="_blank"  href="acknowledgement.php?id=' . $value['id'] . '"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size:24px" class="fa fa-trash" onclick="deleteinfo(' . $value['id'] . ')"></span></a>';
//                 // }

//                 if ($ticket_name != '') {

//                     if ($ticket_name == $type) {



//                         if ($draw_type == 'raffle_draw') {

//                             $winningNumber = strtoupper($value['raffle_id']);
//                         } else {

//                             $winningNumber = $value['my3number'];
//                         }

//                         $result[] = array("soldby" => $soldby, "name" => $cusname, "email" => $email, "mobile" => $mobile, "id_i" => $value['id'], "My3number" => $winningNumber, "prize" => $value['prize'], "prizeamt" => $value['prize_amt'], 'action' => $action);
//                     }
//                 } else {



//                     if ($draw_type == 'raffle_draw') {

//                         $winningNumber = strtoupper($value['raffle_id']);
//                     } else {

//                         $winningNumber = $value['my3number'];
//                     }



//                     $result[] = array("soldby" => $soldby, "name" => $cusname, "email" => $email, "mobile" => $mobile, "id_i" => $value['id'], "My3number" => $winningNumber, "prize" => $value['prize'], "prizeamt" => $value['prize_amt'], 'action' => $action);
//                 }
//             }
//         }



        echo json_encode($result);
    } catch (Exception $e) {

        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];

        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'report_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);

        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
}else if ($method == "dailyticket") {
    try {
        $result = [];
        $draw_new_id = BlockSQLInjectionforagent($_REQUEST["draw_id"]);
        $searchTxt = BlockSQLInjectionforagent($_REQUEST["searchTxt"]);
        
        // Prepare the WHERE clause for the SQL query
        $whereClause = "`userid` != '' AND `drawType` = 'dailyThrill'";
        if (!empty($searchTxt)) {
            $whereClause .= "AND `winRaffleId` LIKE '%$searchTxt%' OR `ticketID` LIKE '%$searchTxt%' OR `mobile` LIKE '%$searchTxt%' OR `fullName` '%$searchTxt%' ";
        }

       // Fetch the winner list data
       $query = "SELECT MAX(id) AS id, MAX(fullName) AS fullName, MAX(mobile) AS mobile, MAX(email) AS email, MAX(winRaffleId) AS winRaffleId, MAX(winningDrawName) AS winningDrawName, ticketID, MAX(prize) AS prize, MAX(prize_amt) AS prize_amt 
       FROM winnerlist 
       WHERE $whereClause 
       GROUP BY ticketID,winRaffleId";
$winnerlist = mysqli_query($con, $query);

// Fetch the count of each ticketID
$winnerlist_counts = [];
$winnerlist_count_query = mysqli_query($con, "SELECT COUNT(ticketID) AS C, ticketID FROM winnerlist WHERE $whereClause GROUP BY ticketID");
while ($row = mysqli_fetch_assoc($winnerlist_count_query)) {
 $winnerlist_counts[$row['ticketID']] = $row['C'];
}

// Fetch the count of each Raffleid
$winnerlist_count = [];
$winnerlist_count_qquery = mysqli_query($con, "SELECT COUNT(winRaffleId) AS C, winRaffleId FROM winnerlist WHERE $whereClause GROUP BY winRaffleId");
while ($row = mysqli_fetch_assoc($winnerlist_count_qquery)) {
 $winnerlist_count[$row['winRaffleId']] = $row['C'];
}

// Process the winner list data
while ($value = mysqli_fetch_assoc($winnerlist)) {
 $cusname = $value['fullName'];
 $mobile = $value['mobile'];
 $email = $value['email'];
 $soldby = '';
 $winningNumber = $value['winRaffleId'];
 $DrawName = $value['winningDrawName'];
 $action = isset($winnerlist_counts[$value['ticketID']]) ? $winnerlist_counts[$value['ticketID']] : 0;
 $actions = isset($winnerlist_count[$value['winRaffleId']]) ? $winnerlist_count[$value['winRaffleId']] : 0;
 $result[] = array(
     "soldby" => $soldby,
     "name" => $cusname,
     "email" => $email,
     "mobile" => $mobile,
     "id_i" => $value['id'],
     "RaffleNO" => $winningNumber,
     "TicketID" => $value['ticketID'],
     "prize" => $value['prize'],
     'action' => $action,
     'raffleCount' => $actions
 );
}

// Output the result as JSON
echo json_encode($result);
    } catch (Exception $e) {
        // Handle exceptions
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'report_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
}

else if ($method == "Consolationticket") {
    try {
        $result = [];
        $draw_new_id = BlockSQLInjectionforagent($_REQUEST["draw_id"]);
        $searchTxt = BlockSQLInjectionforagent($_REQUEST["searchTxt"]);
        
        // Prepare the WHERE clause for the SQL query
        $whereClause = "`userid` != '' AND `drawType` = 'dailyConsolation'";
        if (!empty($searchTxt)) {
            $whereClause .= "AND `winRaffleId` LIKE '%$searchTxt%' OR `ticketID` LIKE '%$searchTxt%' OR `mobile` LIKE '%$searchTxt%' OR `fullName` '%$searchTxt%' ";
        }

       // Fetch the winner list data
       $query = "SELECT MAX(id) AS id, MAX(fullName) AS fullName, MAX(mobile) AS mobile, MAX(email) AS email, MAX(winRaffleId) AS winRaffleId, MAX(winningDrawName) AS winningDrawName, ticketID, MAX(prize) AS prize, MAX(prize_amt) AS prize_amt 
       FROM winnerlist 
       WHERE $whereClause 
       GROUP BY ticketID,winRaffleId";
$winnerlist = mysqli_query($con, $query);

// Fetch the count of each ticketID
$winnerlist_counts = [];
$winnerlist_count_query = mysqli_query($con, "SELECT COUNT(ticketID) AS C, ticketID FROM winnerlist WHERE $whereClause GROUP BY ticketID");
while ($row = mysqli_fetch_assoc($winnerlist_count_query)) {
 $winnerlist_counts[$row['ticketID']] = $row['C'];
}

// Fetch the count of each Raffleid
$winnerlist_count = [];
$winnerlist_count_qquery = mysqli_query($con, "SELECT COUNT(winRaffleId) AS C, winRaffleId FROM winnerlist WHERE $whereClause GROUP BY winRaffleId");
while ($row = mysqli_fetch_assoc($winnerlist_count_qquery)) {
 $winnerlist_count[$row['winRaffleId']] = $row['C'];
}

// Process the winner list data
while ($value = mysqli_fetch_assoc($winnerlist)) {
 $cusname = $value['fullName'];
 $mobile = $value['mobile'];
 $email = $value['email'];
 $soldby = '';
 $winningNumber = $value['winRaffleId'];
 $DrawName = $value['winningDrawName'];
 $action = isset($winnerlist_counts[$value['ticketID']]) ? $winnerlist_counts[$value['ticketID']] : 0;
 $actions = isset($winnerlist_count[$value['winRaffleId']]) ? $winnerlist_count[$value['winRaffleId']] : 0;
 $result[] = array(
     "soldby" => $soldby,
     "name" => $cusname,
     "email" => $email,
     "mobile" => $mobile,
     "id_i" => $value['id'],
     "RaffleNO" => $winningNumber,
     "TicketID" => $value['ticketID'],
     "prize" => $value['prize'],
     'action' => $action,
     'raffleCount' => $actions
 );
}
        // Output the result as JSON
        echo json_encode($result);
    } catch (Exception $e) {
        // Handle exceptions
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'report_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
}

else if ($method == "Bumperticket") {
    try {
        $result = [];
        $draw_new_id = BlockSQLInjectionforagent($_REQUEST["draw_id"]);
        $searchTxt = BlockSQLInjectionforagent($_REQUEST["searchTxt"]);
        
        // Prepare the WHERE clause for the SQL query
        $whereClause = "`userid` != '' AND `drawType` = 'monthlyBumper'";
        if (!empty($searchTxt)) {
           // $whereClause .= "AND `winRaffleId` LIKE '%$searchTxt%' OR `ticketID` LIKE '%$searchTxt%' OR `mobile` LIKE '%$searchTxt%' OR `fullName` '%$searchTxt%' ";
            $whereClause .= "AND `winRaffleId` LIKE '%$searchTxt%'  ";
        }

       // Fetch the winner list data
       $query = "SELECT MAX(id) AS id, MAX(fullName) AS fullName, MAX(mobile) AS mobile, MAX(email) AS email, MAX(winRaffleId) AS winRaffleId, MAX(winningDrawName) AS winningDrawName, ticketID, MAX(prize) AS prize, MAX(prize_amt) AS prize_amt 
       FROM winnerlist 
       WHERE $whereClause 
       GROUP BY ticketID,winRaffleId";
$winnerlist = mysqli_query($con, $query);

// Fetch the count of each ticketID
$winnerlist_counts = [];
$winnerlist_count_query = mysqli_query($con, "SELECT COUNT(ticketID) AS C, ticketID FROM winnerlist WHERE $whereClause GROUP BY ticketID");
while ($row = mysqli_fetch_assoc($winnerlist_count_query)) {
 $winnerlist_counts[$row['ticketID']] = $row['C'];
}

// Fetch the count of each Raffleid
$winnerlist_count = [];
$winnerlist_count_qquery = mysqli_query($con, "SELECT COUNT(winRaffleId) AS C, winRaffleId FROM winnerlist WHERE $whereClause GROUP BY winRaffleId");
while ($row = mysqli_fetch_assoc($winnerlist_count_qquery)) {
 $winnerlist_count[$row['winRaffleId']] = $row['C'];
}

// Process the winner list data
while ($value = mysqli_fetch_assoc($winnerlist)) {
 $cusname = $value['fullName'];
 $mobile = $value['mobile'];
 $email = $value['email'];
 $soldby = '';
 $winningNumber = $value['winRaffleId'];
 $DrawName = $value['winningDrawName'];
 $action = isset($winnerlist_counts[$value['ticketID']]) ? $winnerlist_counts[$value['ticketID']] : 0;
 $actions = isset($winnerlist_count[$value['winRaffleId']]) ? $winnerlist_count[$value['winRaffleId']] : 0;
 $result[] = array(
     "soldby" => $soldby,
     "name" => $cusname,
     "email" => $email,
     "mobile" => $mobile,
     "id_i" => $value['id'],
     "RaffleNO" => $winningNumber,
     "TicketID" => $value['ticketID'],
     "prize" => $value['prize'],
     'action' => $action,
     'raffleCount' => $actions
 );
}
// Output the result as JSON
echo json_encode($result);
    } catch (Exception $e) {
        // Handle exceptions
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'report_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
}

else if ($method == "participation_list") {
    
    // var_dump('welcome1234321');die;

    try {

        $result = [];
        $draw_new_id = BlockSQLInjectionforagent($_REQUEST["draw_id"]);
        $ticket_name = BlockSQLInjectionforagent($_REQUEST["ticket_name"]);
        // $draw_new_id = $_REQUEST['draw_id'];

        // $ticket_name = $_REQUEST['ticket_name'];
        $draw_type = BlockSQLInjectionforagent($_REQUEST["draw_type"]);
        $searchTxt = BlockSQLInjectionforagent($_REQUEST["searchTxt"]);

        // $draw_type = $_REQUEST['draw_type'];
        // $searchTxt = $_REQUEST['searchTxt'];


        $now = date('Y-m-d');

        $agdate = $_POST['agdate'];
        $draw_Con='';

        


        $draw_Con = ''; // Initialize the variable
        if ($searchTxt != '') {
            $draw_Con = "(ur.name LIKE '%$searchTxt%' OR ur.email LIKE '%$searchTxt%' OR ur.mobile LIKE '%$searchTxt%') AND ";
        }
            
            $draw = select_query($con, "draw", "", "`id`='$draw_new_id' ", "", "");
            
            if ($draw['nr'] > 0) {
                foreach ($draw['result'] as $key => $value) {
                    $saleDate = $value['saleDate'];
                }
            
                $query = mysqli_query($con, "SELECT nd.*,nd.ticketNo,nd.id, ur.name, ur.lname, nd.netTotal,nd.raffleIds, ur.mobile, ur.email, nd.purchaseDatetime FROM `ndticket` nd JOIN `user_register` ur ON ur.id = nd.userId WHERE $draw_Con nd.endDate >= '$saleDate' AND ur.deletes != 1");
                
                
                
                if (mysqli_num_rows($query) > 0) {
                    
                    $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
                    
                  
                }
            }

       

        echo json_encode($result);
    } catch (Exception $e) {

        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];

        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'report_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);

        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
}else if ($method == 'customerreport') {

    $draw_new_id = '';

    $tablename = '';

    $draw_not_id = '';
    if (isset($_POST['ticket_name'])) {
        if ($_POST['ticket_name'] == 'MT') {
            $tablename = BlockSQLInjectionforcreate('mticket');
        } else if ($_POST['ticket_name'] == 'AT') {
            $tablename = BlockSQLInjectionforcreate('aticket');
        } else if ($_POST['ticket_name'] == 'OT') {
            $tablename = BlockSQLInjectionforcreate('ticket');
        }
    }
    if (count($_POST['draw_new_id']) > 0) {

        $draw_new_id = "draw_id IN (" . trim(str_replace("", ' ', implode(",", $_POST['draw_new_id']))) . ") AND ";
    }

    if (count($_POST['draw_not_id']) > 0) {

        $draw_not_id = "draw_id IN (" . trim(str_replace("", ' ', implode(",", $_POST['draw_not_id']))) . ")";
    }

    $count = intval(count($_POST['draw_new_id'])) - 1;

    // $sql = 'select user_id from (SELECT user_id, draw_id FROM ' . $tablename . ' WHERE ' . $draw_new_id . ' GROUP BY draw_id, user_id ORDER BY ' . $tablename . '.user_id ASC) AS `dummy` GROUP BY user_id HAVING COUNT(*) > 1';

    $sql = 'select user_id from (select user_id from (SELECT user_id, draw_id FROM ' . $tablename . ' WHERE ' . $draw_new_id . 'deletes = 0 GROUP BY draw_id, user_id ORDER BY ' . $tablename . '.`user_id` ASC) AS `dummy1` GROUP BY user_id HAVING COUNT(*) > ' . $count . ') AS `dummy2` WHERE user_id NOT IN (SELECT user_id FROM ' . $tablename . ' WHERE ' . $draw_not_id . ')';

    // var_dump($con);

    // die;

    $table = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_assoc($table)) {

        $userid = $row['user_id'];

        $user_register = select_query($con, "user_register", "id, name, mobile, email, t_point, t_earning", "`id` = '$userid' AND `roll_id` = '0' and `status` = '0' and `deletes`='0'  ORDER BY `id` DESC LIMIT 1", "", "");

        $result[] = array('id' => $userid, 'cusname' => $user_register['result'][0]['name'], 'mobile' => $user_register['result'][0]['mobile'], 'email' => $user_register['result'][0]['email'], 'point' => $user_register['result'][0]['t_point'], 'earning' => $user_register['result'][0]['t_earning']);
    }

    echo json_encode($result);
} else if ($method == 'get_customer_purchase') {

    $result = [];

    $id = $_REQUEST['userid'];

    $output = '';

    if ($id != '') {

        $output .= '<table cellpadding="5" id="U' . $id . '" cellspacing="0" border="0" style="padding-left:50px;">';

        $output .= ' <thead>

                        <tr>

                          <th class="wd-15p border-bottom-0">Draw Name</th>

                           <th class="wd-15p border-bottom-0">Type</th>

                           <th class="wd-15p border-bottom-0">Ticket No</th>

                        </tr>

                        </thead>';

        $draw = select_query($con, "draw", "", "`status` = 'Completed' and `deletes`='0'  ORDER BY `id` DESC", "", "");

        if ($draw['nr'] > 0) {

            foreach ($draw['result'] as $key => $value) {

                $draw_id = $value['id'];

                $draw_name = $value['name'];

                $Ticket_lines1 = select_query($con, "ticket_lines", "", "`draw_id` = '$draw_id' and  `user_id` = '$id' and  `deletes`='0' ORDER BY `id` DESC", "", "");

                if ($Ticket_lines1['nr'] > 0) {

                    foreach ($Ticket_lines1['result'] as $key => $value) {

                        $ticket_id = $value['ticket_id'];

                        $proid = $value['product_id'];

                        if ($value['type'] == 'MT') {

                            $tablename = 'mticket';
                        } else if ($value['type'] == 'AT') {

                            $tablename = 'aticket';
                        } else if ($value['type'] == 'OT') {

                            $tablename = 'ticket';
                        }

                        $purchase_datetime = select_top_name($con, $tablename, "purchase_datetime", "`id`='$ticket_id' and `deletes`='0'", "purchase_datetime", "");

                        $ticket_no = select_top_name($con, $tablename, "ticket_no", "`id`='$ticket_id' and `deletes`='0'", "ticket_no", "");

                        $user_id = select_top_name($con, $tablename, "user_id", "`id`='$ticket_id' and `deletes`='0'", "user_id", "");

                        $transaction_id = select_top_name($con, $tablename, "transaction_id", "`id`='$ticket_id' and `deletes`='0'", "transaction_id", "");

                        $cusname = select_top_name($con, "user_register", "name", "`id`='$user_id'  and `deletes`='0' ORDER BY `id` DESC", "name", "");

                        $mobile = select_top_name($con, "user_register", "mobile", "`id`='$user_id'  and `deletes`='0' ORDER BY `id` DESC", "mobile", "");

                        $email = select_top_name($con, "user_register", "email", "`id`='$user_id'  and `deletes`='0' ORDER BY `id` DESC", "email", "");

                        $proamt = select_top_name($con, "product", "rate", "`id`='$proid' and `deletes`='0'", "rate", "");

                        if ($value['type'] == 'AT') {

                            $agent_id = select_top_name($con, "aticket", "agent_id", "`id`='$ticket_id' ORDER BY `id` DESC", "agent_id", "");

                            $agent_name = select_top_name($con, "user_register", "name", "`id`='$agent_id' ORDER BY `id` DESC", "name", "");
                        } else {

                            $agent_name = '';
                        }

                        $output .= '<tbody>

                        <tr>



                                          <td>' . $draw_name . '</td>

                                           <td>' . $value['type'] . '</td>

                                           <td>' . $ticket_no . '</td>

                                        </tr>

                        </tbody>';

                        // $result[] = array("agentname" => $agent_name, "transaction_id" => $transaction_id, "proamt" => $proamt, "RaffleID" => $value['raffle_id'], "My3Numbers" => $value['my3number'], "email" => $email, "mobile" => $mobile, "cusname" => $cusname, "ticketno" => $ticket_no, "purdate" => date("d-M-Y g:i a", strtotime($purchase_datetime)));

                    }
                }
            }
        }

        $output .= '</table>';

        $result['type'] = '1';

        $result['output'] = $output;
    } else {

        $result['type'] = '0';

        $result['output'] = 'User id not found.';
    }

    echo json_encode($result);
} else if ($method == 'draw_not_id') {

    $result = [];


    //     $draw = select_query($con, "draw", "", " $draw_new_id `status` != 'Pending' and `deletes`='0' ORDER BY `id` DESC", "", "");
    if (!empty($_POST['draw_new_id'])) {
        $draw_new_id = ' `id` NOT IN (' . BlockSQLInjectionforcreate($_POST['draw_new_id']) . ') AND ';
        $draw = select_query($con, "draw", "", " $draw_new_id `status` != 'Pending' and `deletes`='0' ORDER BY `id` DESC", "", "");
        if ($draw['nr'] > 0) {

            foreach ($draw['result'] as $key => $value) {

                $output[] = array('id' => $value['id'], 'name' => $value['name']);
            }
        }

        $result['type'] = '1';

        $result['result'] = $output;
    } else {

        $result['type'] = '0';

        $result['result'] = '';
    }

    echo json_encode($result);
}

//// New ////
else if ($method == "search_wise_Ticket") {

    try {

        $result = [];



        $type_Con = '';

        $draw_Con = '';

        $tablename = '';
        $draw_new_id = BlockSQLInjectionforreportrange($_REQUEST["draw_id"]);
        $ticket_name = BlockSQLInjectionforreportrange($_REQUEST["ticket_name"]);



        $agdate = BlockSQLInjectionforagent($_REQUEST["agdate"]);



        if ($agdate != '') {

            $newDD = date("Y-m-d", strtotime($agdate));

            $datefilter = "`createdon` BETWEEN '$newDD 00:00:00' AND '$newDD 23:59:59' AND";

            $datefilter_n = "ticket.createdon BETWEEN '$newDD 00:00:00' AND '$newDD 23:59:59' AND";
        } else {

            $datefilter = "";

            $datefilter_n = "";
        }



        if ($draw_new_id != '') {

            $draw_Con = "`draw_id` = '$draw_new_id' AND";

            $draw_Con_n = "ticket.draw_id = '$draw_new_id' AND";
        }



        if ($ticket_name == 'MT') {

            $tablename = 'mticket';
        } else if ($ticket_name == 'AT') {

            $tablename = 'aticket';
        } else if ($ticket_name == 'OT') {

            $tablename = 'ticket';
        } else if ($ticket_name == 'WT') {

            if ($draw_new_id > 33) {

                $tablename = 'wticket';
            } else {

                $tablename = 'ticket';
            }
        } else if ($ticket_name == 'FT') {

            $tablename = 'fticket';
        } else if ($ticket_name == 'CT') {

            $tablename = 'cticket';
        } else if ($ticket_name == 'BP') {

            $tablename = 'bpticket';
        } else if ($ticket_name == 'CP') {

            $tablename = 'cpticket';
        } else if ($ticket_name == 'KT') {

            $tablename = 'kticket';
        }








        if ($ticket_name != '') {

            $type_Con = "`type` = '$ticket_name' AND";
        }



        if ($draw_new_id != '') {



            if (($ticket_name != '' && $ticket_name == 'OT') || $ticket_name == '') {

                $oticket = mysqli_query($rcon, "SELECT * FROM ticket INNER JOIN invoice ON invoice.id = ticket.invoice_no WHERE $draw_Con_n $datefilter_n invoice.response != 'wallet' AND ticket.deletes = '0'");

                while ($row = mysqli_fetch_array($oticket)) {

                    // $user_register = select_query($con, "user_register", "", "`id`='$row[user_id]'  and `deletes`='0' ORDER BY `id` DESC", "", "");

                    $user_register = select_query($rcon, "user_register", "", "`id`='$row[user_id]' ORDER BY `id` DESC", "", "");



                    if ($user_register['nr'] > 0) {

                        $result[] = ["agentname" => '', "proamt" => $row['net_total'], "email" => $user_register['result'][0]['email'], "mobile" => $user_register['result'][0]['mobile'], "cusname" => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], "ticketno" => $row['ticket_no'], "transaction_id" => $row['transaction_id'], "purdate" => date("d-M-Y g:i a", strtotime($row['createdon']))];
                    }
                }
            }



            if (($ticket_name != '' && $ticket_name == 'WT') || $ticket_name == '') {

                if ($draw_new_id > 33) {

                    $wticket = select_query($rcon, "wticket", "", "$draw_Con $datefilter `deletes`='0' order by `id` DESC", "", "");

                    if ($wticket['nr'] > 0) {

                        foreach ($wticket['result'] as $key => $value) {

                            $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' ORDER BY `id` DESC", "", "");

                            if ($user_register['nr'] > 0) {

                                $agent_name = select_top_name($rcon, "user_register", "name", "`id`='$value[agent_id]' ORDER BY `id` DESC", "name", "");

                                $result[] = ["agentname" => $agent_name, "proamt" => $value['net_total'], "email" => $user_register['result'][0]['email'], "mobile" => $user_register['result'][0]['mobile'], "cusname" => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], "ticketno" => $value['ticket_no'], "transaction_id" => $value['transaction_id'], "purdate" => date("d-M-Y g:i a", strtotime($value['createdon']))];
                            }
                        }
                    }
                } else {

                    $wticket = mysqli_query($rcon, "SELECT * FROM ticket INNER JOIN invoice ON invoice.id = ticket.invoice_no WHERE $draw_Con_n  $datefilter_n invoice.response = 'wallet' AND ticket.deletes = '0'");

                    while ($row = mysqli_fetch_array($wticket)) {

                        $user_register = select_query($rcon, "user_register", "", "`id`='$row[user_id]'  ORDER BY `id` DESC", "", "");

                        if ($user_register['nr'] > 0) {

                            $result[] = ["agentname" => '', "proamt" => $row['net_total'], "email" => $user_register['result'][0]['email'], "mobile" => $user_register['result'][0]['mobile'], "cusname" => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], "ticketno" => $row['ticket_no'], "transaction_id" => $row['transaction_id'], "purdate" => date("d-M-Y g:i a", strtotime($row['createdon']))];
                        }
                    }
                }
            }



            if (($ticket_name != '' && $ticket_name == 'AT') || $ticket_name == '') {

                $aticket = select_query($rcon, "aticket", "", "$draw_Con $datefilter `deletes`='0' order by `id` DESC", "", "");

                if ($aticket['nr'] > 0) {

                    foreach ($aticket['result'] as $key => $value) {

                        $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' ORDER BY `id` DESC", "", "");

                        if ($user_register['nr'] > 0) {

                            $agent_fname = select_top_name($con, "user_register", "name", "`id`='$value[agent_id]' ORDER BY `id` DESC", "name", "");
                            $agent_lname = select_top_name($con, "user_register", "lname", "`id`='$value[agent_id]' ORDER BY `id` DESC", "lname", "");
                            $agent_name =   $agent_fname . ' ' . $agent_lname;
                            $result[] = ["agentname" => $agent_name, "proamt" => $value['net_total'], "email" => $user_register['result'][0]['email'], "mobile" => $user_register['result'][0]['mobile'], "cusname" => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], "ticketno" => $value['ticket_no'], "transaction_id" => $value['transaction_id'], "purdate" => date("d-M-Y g:i a", strtotime($value['createdon']))];
                        }
                    }
                }
            }



            if (($ticket_name != '' && $ticket_name == 'MT') || $ticket_name == '') {

                $mticket = select_query($rcon, "mticket", "", "$draw_Con $datefilter`deletes`='0' order by `id` DESC", "", "");

                if ($mticket['nr'] > 0) {

                    foreach ($mticket['result'] as $key => $value) {

                        $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' ORDER BY `id` DESC", "", "");

                        if ($user_register['nr'] > 0) {

                            $result[] = ["agentname" => '', "proamt" => $value['net_total'], "email" => $user_register['result'][0]['email'], "mobile" => $user_register['result'][0]['mobile'], "cusname" => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], "ticketno" => $value['ticket_no'], "transaction_id" => $value['transaction_id'], "purdate" => date("d-M-Y g:i a", strtotime($value['createdon']))];
                        }
                    }
                }
            }



            if (($ticket_name != '' && $ticket_name == 'FT') || $ticket_name == '') {

                $fticket = select_query($rcon, "fticket", "", "$draw_Con $datefilter`deletes`='0' order by `id` DESC", "", "");

                if ($fticket['nr'] > 0) {

                    foreach ($fticket['result'] as $key => $value) {

                        $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' ORDER BY `id` DESC", "", "");

                        if ($user_register['nr'] > 0) {

                            $result[] = ["agentname" => '', "proamt" => $value['net_total'], "email" => $user_register['result'][0]['email'], "mobile" => $user_register['result'][0]['mobile'], "cusname" => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], "ticketno" => $value['ticket_no'], "transaction_id" => $value['transaction_id'], "purdate" => date("d-M-Y g:i a", strtotime($value['createdon']))];
                        }
                    }
                }
            }





            if (($ticket_name != '' && $ticket_name == 'CT') || $ticket_name == '') {

                $fticket = select_query($rcon, "cticket", "", "$draw_Con $datefilter`deletes`='0' order by `id` DESC", "", "");

                if ($fticket['nr'] > 0) {

                    foreach ($fticket['result'] as $key => $value) {

                        $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' ORDER BY `id` DESC", "", "");

                        if ($user_register['nr'] > 0) {

                            $result[] = ["agentname" => '', "proamt" => $value['net_total'], "email" => $user_register['result'][0]['email'], "mobile" => $user_register['result'][0]['mobile'], "cusname" => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], "ticketno" => $value['ticket_no'], "transaction_id" => $value['transaction_id'], "purdate" => date("d-M-Y g:i a", strtotime($value['createdon']))];
                        }
                    }
                }
            }





            if (($ticket_name != '' && $ticket_name == 'BP') || $ticket_name == '') {

                $fticket = select_query($rcon, "bpticket", "", "$draw_Con $datefilter`deletes`='0' order by `id` DESC", "", "");

                if ($fticket['nr'] > 0) {

                    foreach ($fticket['result'] as $key => $value) {

                        $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' ORDER BY `id` DESC", "", "");

                        if ($user_register['nr'] > 0) {

                            $result[] = ["agentname" => '', "proamt" => $value['net_total'], "email" => $user_register['result'][0]['email'], "mobile" => $user_register['result'][0]['mobile'], "cusname" => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], "ticketno" => $value['ticket_no'], "transaction_id" => $value['transaction_id'], "purdate" => date("d-M-Y g:i a", strtotime($value['createdon']))];
                        }
                    }
                }
            }
            if (($ticket_name != '' && $ticket_name == 'KT') || $ticket_name == '') {

                $fticket = select_query($rcon, "kticket", "", "$draw_Con $datefilter`deletes`='0' order by `id` DESC", "", "");

                if ($fticket['nr'] > 0) {

                    foreach ($fticket['result'] as $key => $value) {

                        $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' ORDER BY `id` DESC", "", "");

                        if ($user_register['nr'] > 0) {

                            $result[] = ["agentname" => '', "proamt" => $value['net_total'], "email" => $user_register['result'][0]['email'], "mobile" => $user_register['result'][0]['mobile'], "cusname" => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], "ticketno" => $value['ticket_no'], "transaction_id" => $value['transaction_id'], "purdate" => date("d-M-Y g:i a", strtotime($value['createdon']))];
                        }
                    }
                }
            }




            if (($ticket_name != '' && $ticket_name == 'CP') || $ticket_name == '') {

                $fticket = select_query($rcon, "cpticket", "", "$draw_Con $datefilter`deletes`='0' order by `id` DESC", "", "");

                if ($fticket['nr'] > 0) {

                    foreach ($fticket['result'] as $key => $value) {

                        $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' ORDER BY `id` DESC", "", "");

                        if ($user_register['nr'] > 0) {

                            $result[] = ["agentname" => '', "proamt" => $value['net_total'], "email" => $user_register['result'][0]['email'], "mobile" => $user_register['result'][0]['mobile'], "cusname" => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], "ticketno" => $value['ticket_no'], "transaction_id" => $value['transaction_id'], "purdate" => date("d-M-Y g:i a", strtotime($value['createdon']))];
                        }
                    }
                }
            }
        }



        echo json_encode($result);
    } catch (Exception $e) {

        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];

        $cron_testarr = array("user_id" => '0', "reason" => json_encode($error), "filename" => 'report_services.php', "draw_id" => '0', "creadedon" => $dubaidate_time);

        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == "draw_details") {
    try {
        $result = [];
        $draw_detials = select_query($con, "draw", "", "`deletes`='0' ORDER BY `id` DESC", "", "");
        if ($draw_detials['nr'] > 0) {
            foreach ($draw_detials['result'] as $key => $value) {

                $action = '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-plus" style="color: #1a73e8; font-size: 25px;" onclick="open_summer_note(' . $value['id'] . ')"></span></a>';
                $result[] = ['drawno' => $value['draw_no'], 'draw_name' => $value['name'], 'action' => $action];
            }
        }

        echo json_encode($result);
    } catch (Exception $e) {
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => '0', "reason" => json_encode($error), "filename" => 'report_services.php', "draw_id" => '0', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == "update_desicription") {
    try {
        $result = [];
        $start_time = date("Y-m-d H:i:s", strtotime($_POST['start_time']));
        $end_time = date("Y-m-d H:i:s", strtotime($_POST['end_time']));
        $description = $_POST['description'];
        if ($start_time != '' && $end_time != '' && $description != '') {

            $popupArr = ["deletes" => '0', "status" => '0', "content" => addslashes($description), "start_time" => $start_time, "end_time" => $end_time, "creadedon" => $dubaidate_time];
            $popup_Update = update($con, "popup", "`id` = '1' and `deletes`='0'", $popupArr, "", "", "", "");
            $errors = $popup_Update['errors'];
            if ($errors != "") {
                $result["type"] = "0";
                $result["result"] = "Could Not Update";
            } else {
                $result['type'] = '1';
                $result['result'] = 'Popup Created Successfully';
            }

            // $popup = select_query($con, "popup", "", "`start_time` <=  '$start_time' AND `end_time` >= '$end_time' AND `status` = '0' AND `deletes` = '0'", "", "");
            // if ($popup['nr'] > 0) {
            //     $result['type'] = '0';
            //     $result['result'] = 'Already One Popup Avaliable';
            // } else {
            //     $popup_arr = array("deletes" => '0', "status" => '0', "content" => $description, "start_time" => $start_time, "end_time" => $end_time, "creadedon" => $dubaidate_time);
            //     $popup_ins = insert($con, "popup", "", $popup_arr, "", "", "");
            //     if ($popup_ins['id'] != '') {
            //         $result['type'] = '1';
            //         $result['result'] = 'Popup Created Successfully';
            //     }

            // }
        }
        echo json_encode($result);
    } catch (Exception $e) {
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => '0', "reason" => json_encode($error), "filename" => 'report_services.php', "draw_id" => '0', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == "unselectedmy3numbers") {
    $result = [];
    $draw_id = BlockSQLInjection($_POST["draw_id"]);
    $type = BlockSQLInjection($_POST["type"]);
    // $draw_id  = $_POST['draw_id'];
    // $type =  $_POST['type'];
    $rate = select_top_name($rcon, "product", "rate", "`id` = '4'", "rate", "");
    $prize_one = select_top_name($rcon, "draw", "hprize1", "`id` = '$draw_id'", "hprize1", "");

    if ($rate != '' && $prize_one != '') {
        if ($draw_id != '') {
            $query = mysqli_query($rcon, "SELECT `my3number`,  COUNT(*) AS 'count', SUM(rate) AS 'amt' FROM (SELECT ticket_lines.id AS 'ticketlineid', ticket_lines.my3number, ticket_lines.product_id, product.rate FROM `ticket_lines` INNER JOIN product ON `product`.`id` = `ticket_lines`.`product_id` WHERE ticket_lines.draw_id = '$draw_id' AND product.deletes = '0') AS final GROUP BY `my3number`");
            if ($type == 'selected') {
                while ($row = mysqli_fetch_array($query)) {
                    $nTotal = (int)$row['amt'] * ((int)$prize_one / (int)$rate);
                    $result[] = ['rowlabel' => $row['my3number'], 'count' => $row['count'], 'amt' => $row['amt'], 'topten' => number_format($nTotal)];
                }
            }
            if ($type == 'unselected') {
                $selectedArr = [];
                $unselectedArr = [];
                while ($row = mysqli_fetch_array($query)) {
                    $selectedArr[] = $row['my3number'];
                }

                for ($i = 000; $i <= 999; $i++) {
                    $siS = str_pad($i, 3, "0", STR_PAD_LEFT);
                    if (!in_array($siS, $selectedArr)) {
                        $unselectedArr[] =  $siS;
                    }
                }
                $result['type'] = '1';
                $result['result'][] = $unselectedArr;
                $result['unselectedcount'] = count($unselectedArr);
            }
        }
    }

    echo json_encode($result);
} else if ($method == "agentreport") {

    $result = [];

    $agent = select_query($rcon, "user_register", "", "`user` LIKE '%agent%' AND `deletes`='0' ", "", "");

    if ($agent['nr'] > 0) {

        foreach ($agent['result'] as $key => $value) {

            // agent type
            $agent_id = $value['id'];

            $agentname = select_query($rcon, "user_register", "user", "`id`='$agent_id' and `deletes`='0'", "", "");

            $data = $agentname['result'][0];

            // agent name
            $agentname = select_query($rcon, "user_register", "name", "`id`='$agent_id' and `deletes`='0'", "", "");

            $name = $agentname['result'][0];

            $agentlname = select_query($rcon, "user_register", "lname", "`id`='$agent_id' and `deletes`='0'", "", "");

            $lname = $agentlname['result'][0];
            $agent_full_name = $name . ' ' . $name;

            //agent id
            $agentname = select_query($rcon, "user_register", "id", "`id`='$agent_id' and `deletes`='0'", "", "");

            $id = $agentname['result'][0];


            $pointrequest = select_query_sum($con, "points_transaction", "points", "`to_id` = '$agent_id'", $limitations = '', $print = '');

            //points sales

            $pointssale = select_query_sum($con, "points_transaction", "points", "`from_id` = '$agent_id' AND `type`='sales'", $limitations = '', $print = '');

            //balence

            $balence = select_query($rcon, "user_register", "t_point", "`id`='$agent_id' and `deletes`='0'", "", "");

            $pointbalence = $balence['result'][0];

            // difference

            $difference = $pointrequest - ($pointssale + $pointbalence[0]);

            $result[] = array("type" => $data[0], "agentname" => $name[0] . ' ' . $lname[0], "agentid" => $id[0], "pointrequest" => $pointrequest, "pointsale" => $pointssale, "balance" => $pointbalence[0], "difference" => $difference);
        }
    }

    echo json_encode($result);
}

//// NEW ////
else if ($method == "deledted_search_wise_Ticket") {

    try {

        $result = [];



        $type_Con = '';

        $draw_Con = '';

        $tablename = '';


        $draw_new_id = BlockSQLInjection($_REQUEST["draw_id"]);
        $ticket_name = BlockSQLInjection($_REQUEST["ticket_name"]);
        $search_text = BlockSQLInjection($_REQUEST["search_text"]);





        $now = date('Y-m-d');



        $agdate = $_POST['agdate'];



        if ($agdate != '') {

            $newDD = date("Y-m-d", strtotime($agdate));

            $datefilter = "`createdon` BETWEEN '$newDD 00:00:00' AND '$newDD 23:59:59' AND";

            $datefilter_n = "ticket.createdon BETWEEN '$newDD 00:00:00' AND '$newDD 23:59:59' AND";
        } else {

            $datefilter = "";

            $datefilter_n = "";
        }



        if ($draw_new_id != '') {

            $draw_Con = "`draw_id` = '$draw_new_id' AND";

            $draw_Con_n = "ticket.draw_id = '$draw_new_id' AND";
        }



        if ($ticket_name == 'MT') {

            $tablename = 'mticket';
        } else if ($ticket_name == 'AT') {

            $tablename = 'aticket';
        } else if ($ticket_name == 'OT') {

            $tablename = 'ticket';
        } else if ($ticket_name == 'WT') {

            if ($draw_new_id > 33) {

                $tablename = 'wticket';
            } else {

                $tablename = 'ticket';
            }
        } else if ($ticket_name == 'FT') {

            $tablename = 'fticket';
        } else if ($ticket_name == 'CT') {

            $tablename = 'cticket';
        } else if ($ticket_name == 'CP') {

            $tablename = 'cpticket';
        } else if ($ticket_name == 'BP') {

            $tablename = 'bpticket';
        } else if ($ticket_name == 'KT') {

            $tablename = 'kticket';
        }




        $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'", "roll_id", "");



        if ($ticket_name != '') {

            $type_Con = "`type` = '$ticket_name' AND";
        }







        if (($ticket_name != '' && $ticket_name == 'OT') || $ticket_name == '') {

            $oticket = mysqli_query($con, "SELECT * FROM ticket INNER JOIN invoice ON invoice.id = ticket.invoice_no WHERE $draw_Con_n $datefilter_n invoice.response != 'wallet' AND ticket.deletes = '1'");

            while ($row = mysqli_fetch_array($oticket)) {


                $user_register = select_query($con, "user_register", "", "`id`='$row[user_id]' ORDER BY `id` DESC", "", "");


                if ($user_register['nr'] > 0) {

                    $result[] = ["action" => ($roll_id == 1 || $roll_id == 2) ? '<button value="' . $row['transaction_id'] . '" onclick="unDelete($(this).val(), $(\'#hidden\').val())" class="btn btn-primary edit"><span class="fa fa-reply"><input type="hidden" id="hidden"value="OT"></span></button>' : "-", "deletedreason" => $row['delete_reason'], "agentname" => '', "proamt" => $row['net_total'], "email" => $user_register['result'][0]['email'], "mobile" => $user_register['result'][0]['mobile'], "cusname" => ($user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'] ?? ''), "ticketno" => $row['ticket_no'], "transaction_id" => $row['transaction_id'], "purdate" => date("d-M-Y g:i a", strtotime($row['createdon']))];
                }
            }
        }



        if (($ticket_name != '' && $ticket_name == 'WT') || $ticket_name == '') {



            if ($draw_new_id > 33) {

                $wticket = select_query($con, "wticket", "", "$draw_Con $datefilter `deletes`='1' order by `id` DESC", "", "");

                if ($wticket['nr'] > 0) {

                    foreach ($wticket['result'] as $key => $value) {

                        $user_register = select_query($con, "user_register", "", "`id`='$value[user_id]' and `deletes`='0' ORDER BY `id` DESC", "", "");

                        if ($user_register['nr'] > 0) {

                            $agent_name = select_top_name(
                                $con,
                                "user_register",
                                "name",
                                "`id`='$value[agent_id]' ORDER BY `id` DESC",
                                "name",
                                ""
                            );
                            $agent_lname = select_top_name($con, "user_register", "lname", "`id`='$value[agent_id]' ORDER BY `id` DESC", "lname", "");

                            $result[] = ["action" => ($roll_id == 1 || $roll_id == 2) ? '<button value="' . $value['transaction_id'] . '" onclick="unDelete($(this).val(), $(\'#hidden4\').val())" class="btn btn-primary edit"><span class="fa fa-reply"><input type="hidden" id="hidden4"value="WT"></span></button>' : "-", "deletedreason" => $value['delete_reason'], "agentname" => $agent_name . ' ' . $agent_lname, "proamt" => $value['net_total'], "email" => $user_register['result'][0]['email'], "mobile" => $user_register['result'][0]['mobile'], "cusname" =>  $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], "ticketno" => $value['ticket_no'], "transaction_id" => $value['transaction_id'], "purdate" => date("d-M-Y g:i a", strtotime($value['createdon']))];
                        }
                    }
                }
            } else {

                $wticket = mysqli_query($con, "SELECT * FROM ticket INNER JOIN invoice ON invoice.id = ticket.invoice_no WHERE $draw_Con_n  $datefilter_n invoice.response = 'wallet' AND ticket.deletes = '1'");

                while ($row = mysqli_fetch_array($wticket)) {

                    $user_register = select_query($con, "user_register", "", "`id`='$row[user_id]'  and `deletes`='0' ORDER BY `id` DESC", "", "");

                    if ($user_register['nr'] > 0) {

                        $result[] = ["action" => ($roll_id == 1 || $roll_id == 2) ? '<button value="' . $row['transaction_id'] . '" onclick="unDelete($(this).val(), $(\'#hidden5\').val())" class="btn btn-primary edit"><span class="fa fa-reply"><input type="hidden" id="hidden5"value="oldwallet"></span></button>' : "-", "deletedreason" => $row['delete_reason'], "agentname" => '', "proamt" => $row['net_total'], "email" => $user_register['result'][0]['email'], "mobile" => $user_register['result'][0]['mobile'], "cusname" =>  $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], "ticketno" => $row['ticket_no'], "transaction_id" => $row['transaction_id'], "purdate" => date("d-M-Y g:i a", strtotime($row['createdon']))];
                    }
                }
            }
        }



        if (($ticket_name != '' && $ticket_name == 'AT') || $ticket_name == '') {

            $aticket = select_query($con, "aticket", "", "$draw_Con $datefilter `deletes`='1' order by `id` DESC", "", "");



            if ($aticket['nr'] > 0) {

                foreach ($aticket['result'] as $key => $value) {

                    $user_register = select_query($con, "user_register", "", "`id`='$value[user_id]' and `deletes`='0' ORDER BY `id` DESC", "", "");

                    if ($user_register['nr'] > 0) {

                        $agent_name = select_top_name($con, "user_register", "name", "`id`='$value[agent_id]' ORDER BY `id` DESC", "name", "");
                        $agent_lname = select_top_name($con, "user_register", "lname", "`id`='$value[agent_id]' ORDER BY `id` DESC", "lname", "");

                        $result[] = ["action" => ($roll_id == 1 || $roll_id == 2) ? '<button value="' . $value['transaction_id'] . '" onclick="unDelete($(this).val(), $(\'#hidden1\').val())" class="btn btn-primary edit"><span class="fa fa-reply"><input type="hidden" id="hidden1"value="AT"></span></button>' : "-", "deletedreason" => $value['delete_reason'], "agentname" => $agent_name . ' ' . $agent_lname, "proamt" => $value['net_total'], "email" => $user_register['result'][0]['email'], "mobile" => $user_register['result'][0]['mobile'], "cusname" =>  $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], "ticketno" => $value['ticket_no'], "transaction_id" => $value['transaction_id'], "purdate" => date("d-M-Y g:i a", strtotime($value['createdon']))];
                    }
                }
            }
        }



        if (($ticket_name != '' && $ticket_name == 'MT') || $ticket_name == '') {

            $mticket = select_query($con, "mticket", "", "$draw_Con $datefilter`deletes`='1' order by `id` DESC", "", "");

            if ($mticket['nr'] > 0) {

                foreach ($mticket['result'] as $key => $value) {

                    $user_register = select_query($con, "user_register", "", "`id`='$value[user_id]'  and `deletes`='0' ORDER BY `id` DESC", "", "");

                    if ($user_register['nr'] > 0) {

                        $result[] = ["action" => ($roll_id == 1 || $roll_id == 2) ? '<button value="' . $value['user_id'] . '" onclick="unDelete($(this).val(), $(\'#hidden\').val())" class="btn btn-primary edit"><span class="fa fa-reply"><input type="hidden" id="hidden"value="MT"></span></button>' : "-", "deletedreason" => $value['delete_reason'], "agentname" => '', "proamt" => $value['net_total'], "email" => $user_register['result'][0]['email'], "mobile" => $user_register['result'][0]['mobile'], "cusname" =>  $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], "ticketno" => $value['ticket_no'], "transaction_id" => $value['transaction_id'], "purdate" => date("d-M-Y g:i a", strtotime($value['createdon']))];
                    }
                }
            }
        }
        if (($ticket_name != '' && $ticket_name == 'KT') || $ticket_name == '') {

            $mticket = select_query($con, "kticket", "", "$draw_Con $datefilter`deletes`='1' order by `id` DESC", "", "");

            if ($mticket['nr'] > 0) {

                foreach ($mticket['result'] as $key => $value) {

                    $user_register = select_query($con, "user_register", "", "`id`='$value[user_id]'  and `deletes`='0' ORDER BY `id` DESC", "", "");

                    if ($user_register['nr'] > 0) {

                        $result[] = ["action" => ($roll_id == 1 || $roll_id == 2) ? '<button value="' . $value['user_id'] . '" onclick="unDelete($(this).val(), $(\'#hidden\').val())" class="btn btn-primary edit"><span class="fa fa-reply"><input type="hidden" id="hidden"value="MT"></span></button>' : "-", "deletedreason" => $value['delete_reason'], "agentname" => '', "proamt" => $value['net_total'], "email" => $user_register['result'][0]['email'], "mobile" => $user_register['result'][0]['mobile'], "cusname" =>  $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], "ticketno" => $value['ticket_no'], "transaction_id" => $value['transaction_id'], "purdate" => date("d-M-Y g:i a", strtotime($value['createdon']))];
                    }
                }
            }
        }



        if (($ticket_name != '' && $ticket_name == 'FT') || $ticket_name == '') {

            $fticket = select_query($con, "fticket", "", "$draw_Con $datefilter`deletes`='1' order by `id` DESC", "", "");

            if ($fticket['nr'] > 0) {

                foreach ($fticket['result'] as $key => $value) {

                    $user_register = select_query($con, "user_register", "", "`id`='$value[user_id]'  and `deletes`='0' ORDER BY `id` DESC", "", "");

                    if ($user_register['nr'] > 0) {

                        $result[] = ["action" => ($roll_id == 1 || $roll_id == 2) ? '<button value="' . $value['transaction_id'] . '" onclick="unDelete($(this).val(), $(\'#hidden2\').val())" class="btn btn-primary edit"><span class="fa fa-reply"><input type="hidden" id="hidden2"value="FT"></span></button>' : "-", "deletedreason" => $value['delete_reason'], "agentname" => '', "proamt" => $value['net_total'], "email" => $user_register['result'][0]['email'], "mobile" => $user_register['result'][0]['mobile'], "cusname" => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], "ticketno" => $value['ticket_no'], "transaction_id" => $value['transaction_id'], "purdate" => date("d-M-Y g:i a", strtotime($value['createdon']))];
                    }
                }
            }
        }



        if (($ticket_name != '' && $ticket_name == 'CT') || $ticket_name == '') {

            $fticket = select_query($con, "cticket", "", "$draw_Con $datefilter`deletes`='1' order by `id` DESC", "", "");

            if ($fticket['nr'] > 0) {

                foreach ($fticket['result'] as $key => $value) {

                    $user_register = select_query($con, "user_register", "", "`id`='$value[user_id]'  and `deletes`='0' ORDER BY `id` DESC", "", "");

                    if ($user_register['nr'] > 0) {

                        $result[] = ["action" => ($roll_id == 1 || $roll_id == 2) ? '<button value="' . $value['transaction_id'] . '" onclick="unDelete($(this).val(), $(\'#hidden3\').val())" class="btn btn-primary edit"><span class="fa fa-reply"><input type="hidden" id="hidden3"value="CT"></span></button>' : "-", "deletedreason" => $value['delete_reason'], "agentname" => '', "proamt" => $value['net_total'], "email" => $user_register['result'][0]['email'], "mobile" => $user_register['result'][0]['mobile'], "cusname" => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], "ticketno" => $value['ticket_no'], "transaction_id" => $value['transaction_id'], "purdate" => date("d-M-Y g:i a", strtotime($value['createdon']))];
                    }
                }
            }
        }



        if (($ticket_name != '' && $ticket_name == 'CP') || $ticket_name == '') {

            $fticket = select_query($con, "cpticket", "", "$draw_Con $datefilter`deletes`='1' order by `id` DESC", "", "");

            if ($fticket['nr'] > 0) {

                foreach ($fticket['result'] as $key => $value) {

                    $user_register = select_query($con, "user_register", "", "`id`='$value[user_id]'  and `deletes`='0' ORDER BY `id` DESC", "", "");

                    if ($user_register['nr'] > 0) {

                        $result[] = ["action" => ($roll_id == 1 || $roll_id == 2) ? '<button value="' . $value['transaction_id'] . '" onclick="unDelete($(this).val(), ' . "'CP'" . ')" class="btn btn-primary edit"><span class="fa fa-reply"></span></button>' : "-", "deletedreason" => $value['delete_reason'], "agentname" => '', "proamt" => $value['net_total'], "email" => $user_register['result'][0]['email'], "mobile" => $user_register['result'][0]['mobile'], "cusname" =>  $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], "ticketno" => $value['ticket_no'], "transaction_id" => $value['transaction_id'], "purdate" => date("d-M-Y g:i a", strtotime($value['createdon']))];
                    }
                }
            }
        }



        if (($ticket_name != '' && $ticket_name == 'BP') || $ticket_name == '') {

            $fticket = select_query($con, "bpticket", "", "$draw_Con $datefilter`deletes`='1' order by `id` DESC", "", "");

            if ($fticket['nr'] > 0) {

                foreach ($fticket['result'] as $key => $value) {

                    $user_register = select_query($con, "user_register", "", "`id`='$value[user_id]'  and `deletes`='0' ORDER BY `id` DESC", "", "");

                    if ($user_register['nr'] > 0) {

                        $result[] = ["action" => ($roll_id == 1 || $roll_id == 2) ? '<button value="' . $value['transaction_id'] . '" onclick="unDelete($(this).val(), ' . "'BP'" . ')" class="btn btn-primary edit"><span class="fa fa-reply"></span></button>' : "-", "deletedreason" => $value['delete_reason'], "agentname" => '', "proamt" => $value['net_total'], "email" => $user_register['result'][0]['email'], "mobile" => $user_register['result'][0]['mobile'], "cusname" => $user_register['result'][0]['name'] . ' ' . (['result'][0]['lname'] ?? ''), "ticketno" => $value['ticket_no'], "transaction_id" => $value['transaction_id'], "purdate" => date("d-M-Y g:i a", strtotime($value['createdon']))];
                    }
                }
            }
        }





        echo json_encode($result);
    } catch (Exception $e) {

        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        // echo json_encode($error);
        $cron_testarr = array("user_id" => '0', "reason" => json_encode($error), "filename" => 'report_services.php', "draw_id" => '0', "creadedon" => $dubaidate_time);

        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
}
