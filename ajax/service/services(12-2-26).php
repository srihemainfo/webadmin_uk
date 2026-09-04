<?php




include '../../include/shi-config.php';



include '../../include/functions.php';



// error_reporting(E_ALL);
// ini_set('display_errors', 1);



$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";



$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";



$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";

$role = $_REQUEST['role'] ?? "";

if ($type == 'agent') {



    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($role) AND" : "";
} else {



    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$role' AND" : "";
}







$headers = apache_request_headers();



//print_r($headers);



$result = array();



$post_csrf = $headers['X-Csrf-Token'] ?? '';










if ($method == "list_agent") {



    if ($_SESSION['memid'] != 1) {



        $condition = "`created_by` = '$_SESSION[memid]' AND";
    } else {



        $condition = "";
    }











    $roll_id = select_top_name($rcon, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC ", "roll_id", "");











    $user_register = select_query($rcon, "user_register", "", "$condition  $role `deletes`='0'", "", "");



    if ($user_register['nr'] > 0) {



        foreach ($user_register['result'] as $key => $value) {











            if ($roll_id == 1) {



                if ($value['roll_id'] == 0) {



                    $action = '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye"></span></a><a class="btn text-danger btn-sm" data-bs-target="#deleteinfo" data-bs-toggle="modal" data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2 fs-14" onclick=deletelist(' . $value['id'] . ')></span></a>';
                } else if ($value['roll_id'] == 6) {



                    $action = '<a href="' . $adminurl . 'profile/edit/permission/' . $value['id'] . '" style="cursor: pointer;"><span class="fe fe-edit fs-14"></span></a>';



                    $action .= '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye"></span></a>';



                    $action .= '<a class="btn text-danger btn-sm" data-bs-target="#deleteinfo" data-bs-toggle="modal" data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2 fs-14" onclick=deletelist(' . $value['id'] . ')></span></a>';
                } else {



                    $action = '<a href="' . $adminurl . 'profile/edit/permission/' . $value['id'] . '" style="cursor: pointer;"><span class="fe fe-edit fs-14"></span></a>&nbsp;&nbsp;<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye"></span></a>&nbsp;<a class="btn text-danger btn-sm" data-bs-target="#deleteinfo" data-bs-toggle="modal" data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2 fs-14" onclick=deletelist(' . $value['id'] . ')></span></a>';
                }
            } else {



                if ($value['roll_id'] == 0) {



                    $action = '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye"></span></a>';
                } else if ($value['roll_id'] == 6) {



                    $action = '';
                } else {



                    $action = '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye"></span></a>';
                }
            }











            $result[] = array("action" => $action, "id" => $value['id'], "nid" => str_pad($value['id'], 7, "0", STR_PAD_LEFT), "rollType" => $value['user'], "name" => $value['name'], "t_point" => $value['t_point'], "mobile" => $value['mobile'], "passport" => $value['passport'], "email" => $value['email']);
        }
    }











    echo json_encode($result);
} else if ($method == "createlines") {



    $result = [];



    $count = intval($_POST['count']);



    if ($_POST['key'] == 'sub') {



        $count = $count - 1;
    } else {



        $count = $count + 1;
    }







    $outputscreen = '';



    if ($count <= 12) {



        for ($i = 1; $i <= $count; $i++) {



            $no = '';



            if ($i <= 9) {



                $no = '0' . $i;
            } else if ($i <= 12) {



                $no = $i;
            } else {
            }







            $outputscreen .= '<div class="row">';



            $outputscreen .= '<div class="col-2" style="padding:0px; margin:0;">';



            $outputscreen .= '<select name="productid' . $i . '">';



            $outputscreen .= '<option value="">Select product</option>';



            $pq = "SELECT * FROM `product` WHERE `deletes` = '0'";



            $product = mysqli_query($con, $pq);



            $r = 1;



            if (mysqli_num_rows($product) > 0) {







                while ($row = mysqli_fetch_array($product)) {



                    $name = "productid" . $i;



                    $yesorno = '';



                    if ($_POST[$name] == $row['id']) {



                        $yesorno = 'selected';
                    }



                    $outputscreen .= '<option value="' . $row['id'] . '" ' . $yesorno . '>AED ' . round($row['rate']) . '</option>';



                    $r++;
                }
            }







            $outputscreen .= '</select>';



            $outputscreen .= '</div>';



            $outputscreen .= '<div class="col-2" style="margin: 0px -10px 0 -10px;">';



            $outputscreen .= '<input class="form-control mt-2 form-control-sm raffleTS" type="number" min="100" max="999" value="' . $_POST['ticketnumber'] . '" readOnly>';



            $outputscreen .= '</div>';



            $outputscreen .= '<div class="col-2" style="padding:0px; margin:0;">';



            $outputscreen .= '<input class="form-control mt-2 form-control-sm raffle_idTicket" type="text" value="' . $no . '" name="ticketid' . $i . '"  onkeyup="keynumcheck(this.value, this.id)" maxlength="2" size="2" readOnly>';



            $outputscreen .= '</div>';



            $outputscreen .= '<div class="col-2" style="padding:0px; margin:0;">';



            $outputscreen .= '<input class="form-control mt-2 form-control-sm" type="text" name="my3number' . $i . '" value="' . $_POST["my3number" . $i] . '" oninput="this.value = this.value.replace(/[^0-9]/g, ' . "''" . ');" maxlength="3" size="3">';



            $outputscreen .= '</div>';



            $outputscreen .= '</div>';
        }







        if ($count >= 0) {







            if ($_POST['key'] == 'sub') {



                $result['totallines'] = $count;



                $result['outputscreen'] = $outputscreen;
            } else {



                $result['totallines'] = $count;



                $result['outputscreen'] = $outputscreen;
            }



            $result["type"] = "1";



            $result["result"] = "Success!";
        } else {



            $result["type"] = "0";



            $result["result"] = "Failed!";
        }
    }



    echo json_encode($result);
} else if ($method == 'previewmticket') {



    $result = [];



    $omatype = 'MT';



    $count = intval($_POST['count']);







    if ($count <= 13) {



        for ($i = 1; $i <= $count; $i++) {



            $pn = "productid" . $i;



            $id3 = "my3number" . $i;



            if ($_POST[$pn] != '' && $_POST[$id3] != '') {



                if (strlen($_POST[$id3]) == 3) {
                } else {



                    $result["type"] = "0";



                    $result["result"] = "Please Enter 3 Number!";



                    $result["s"] = $pn;



                    goto re;
                }
            } else {



                $result["type"] = "0";



                $result["result"] = "Please fill all fields!";



                $result["s"] = $pn;



                goto re;
            }
        }
    }







    $myuserno = $_POST['myUser'];



    $totalAmount = '';



    $ticketnumber = $_POST['ticketnumber'];



    $drawid = $_POST['drawid'];



    $drawdate = select_top_name($con, "draw", "result_datetime", "`id`='$drawid' and `deletes`='0' and `status`='Active'  order by `id` DESC ", "result_datetime", "");



    $drawdate = date("d M Y", strtotime($drawdate));



    $drawname = select_top_name($con, "draw", "name", "`id`='$drawid' and `deletes`='0' and `status`='Active'  order by `id` DESC ", "name", "");



    $sql = "SELECT * FROM `user_register` WHERE  `mobile` = '$myuserno' AND `deletes`='0'";



    $run = mysqli_query($con, $sql);



    if (mysqli_num_rows($run) > 0) {



        $row = mysqli_fetch_array($run);



        $userid = $row['id'];



        $username = $row['name'];



        $mobile = $row['mobile'];



        $email = $row['email'];
    } else {



        $result["type"] = "0";



        $result["result"] = "User Not Found!";
    }



    $output = '';



    $output .= '



                            <table style="width: 100%;" cellpadding="5">



                            <tbody>



                               <tr class="table-wrapper">



  <td colspan="4" style="text-align: center; "><span class="Ticket" style="font-weight:bold; font-size:16px; "><br>Ticket Details<br></b><br></td>



                              </tr>















                               <tr>



                                  <th colspan="2" style="text-align: center;">CUSTOMER NAME</th>



                                  <th colspan="2" style="text-align: center;">MOBILE NO. <br></th>



                             </tr>







                             <tr>



                                  <td colspan="2" style="text-align: center;">';



    $output .= $username;



    $output .= '</td><td colspan="2" style="text-align: center;">';



    $output .= $mobile;



    $output .= '</td>



                         </tr>



                         <tr style="    border-bottom: 1px dashed #a9a9a9;">







</tr>







                         <tr>



                                   <td colspan="4" style="text-align: center;font-weight:bold;"><br><h4>';



    $output .= $drawname;



    $output .= '</h4></td>



                              </tr>







                                 <tr style="    border-bottom: 1px dashed #a9a9a9;">







</tr>



                         <tr>



                         <th colspan="2" style="text-align: center;">GRAND PRIZE UPTO</th>



                         <th colspan="2" style="text-align: center;">ISSUED  ON</th>



                         </tr>



                         <tr>



                         <td colspan="2" style="text-align: center;">



                         AED 1,000,000



                        </td>



                        <td colspan="2" style="text-align: center;">';



    $output .= date('d M Y');



    $output .= '</td>



                        </tr>



                           <tr style="    border-bottom: 1px dashed #a9a9a9;">







</tr>











   <tr>



        <td colspan="4" style="text-align: center;">



          



      



<table style="text-align: center; width:100%">







    <tr>



        <th colspan="4" style="text-align: center;">



            <br>Just3 Draw Prizes<br>



        </th>



    </tr>







    <tr >



        <th style="text-align: center; width: 33%;">



            1ST PRIZE 



            



        </th>



        <th style="text-align: center; width: 33%;">



            2ND PRIZE 



        </th>



        <th style="text-align: center; width: 33%;">



            3RD PRIZE



        </th>



    </tr>



    <tr>



    <th style="text-align: center; width: 33%;">



        



         UPTO



    </th>



    <th style="text-align: center; width: 33%;">



        UPTO



    </th>



    <th style="text-align: center; width: 33%;">



        UPTO



    </th>



</tr>



    <tr>



        <td style="text-align: center; width: 33%;">



            AED 25,000.00



        </td>



        <td style="text-align: center; width: 33%;">



            AED 2,500.00



        </td>



        <td style="text-align: center; width: 33%;">



            AED 250.00



        </td>



    </tr>	</table>



	  </td>



    </tr>











	



       <tr style="border-bottom: 1px dashed #a9a9a9;">







</tr>











    <tr>



        <th>



            <br> TOTAL LINES



        </th>



    </tr>



    <tr>



        <td>';



    $output .= $count;



    $output .= '</td>



    </tr>



       <tr style="    border-bottom: 1px dashed #a9a9a9;">







</tr>















    <tr>



        <th>



            Products



        </th>



        <th>



            Lines



        </th>



        <th>



            My<span font-size: 22px;



      font-family: "Montserrat", sans-serif !important; >3</span>                                      Numbers



        </th>



        <th>



            Raffle ID



        </th>



    </tr>';







    $arr = array();



    if ($count <= 13) {



        for ($l = 1; $l <= $count; $l++) {



            $pname1 = "productid" . $l;



            $proid1 = $_POST[$pname1];







            array_push($arr, $proid1);
        }
    }







    // $result['arr'] = $arr;







    $sql = 'SELECT * FROM `product` WHERE `deletes` = 0;';



    $run = mysqli_query($con, $sql);



    if (mysqli_num_rows($run) > 0) {



        while ($row = mysqli_fetch_array($run)) {







            if (in_array($row['id'], $arr)) {



                $output .= '<tr><td>';



                $output .= 'AED' . number_format((float) $row['rate'], 2, '.', '');



                $output .= '</td>';



                $procount = '';



                $lucystring = '';



                $rafflestring = '';







                if ($count <= 13) {



                    for ($i = 1; $i <= $count; $i++) {



                        $no = '';



                        if ($i <= 9) {



                            $no = '0' . $i;
                        } else if ($i <= 12) {



                            $no = $i;
                        } else {
                        }



                        $pname = "productid" . $i;



                        $proid = $_POST[$pname];



                        $result['pid'] = $proid;



                        $lucky = "my3number" . $i;



                        $rafflevalue = $omatype . $ticketnumber . $no;



                        if ($row['id'] == $proid) {



                            $procount += 1;



                            $lucystring .= $_POST[$lucky] . '<br>';



                            $rafflestring .= $rafflevalue . '<br>';
                        }
                    }
                }







                $output .= '<td>';



                $output .= $procount;



                $output .= '</td><td>';



                $output .= $lucystring;



                $output .= '</td><td>';



                $output .= $rafflestring;



                $output .= '</td></tr>';
            }
        }
    }







    $output .= '



    <tr style="border-bottom: 1px dashed #a9a9a9; padding-top:12px;"></tr>



    <tr>



        <td colspan="4" style="text-align: center;"><br><br><br>Draw Date: ' . $drawdate . '</td>



    </tr>



    <tr>



        <td colspan="4" style="text-align: center;">Ticket ID: #' . $omatype . '' . $ticketnumber . '</td>



    </tr>



    <tr>



        <td colspan="4" style="text-align: center;">



            All Other Terms and Conditions Apply



        </td>



    </tr>



</tbody>



</table>



';







    // <tr>



    // <td colspan="4" style="text-align: center;"><svg id="barcode"></svg></td>



    // </tr>







    $result["ticketid"] = $ticketnumber;



    $result["output"] = $output;



    $result["type"] = "1";



    $result["result"] = "Success!";



    re:



    echo json_encode($result);
} else if ($method == "blukregisteroffline") {



    $result = [];



    $count = intval($_POST['count']);



    $omatype = 'MT';



    $dubaidate_time = $_POST['purdata'];



    $inform = $_REQUEST['inform'];



    if ($inform == 1) {



        if ($dubaidate_time != '1900-01-01') {















            if ($count <= 13) {



                for ($i = 1; $i <= $count; $i++) {



                    $pn = "productid" . $i;



                    $id3 = "my3number" . $i;



                    if ($_POST[$pn] != '' && $_POST[$id3] != '') {
                    } else {



                        $result["type"] = "0";



                        $result["result"] = "Please fill all fields!";



                        $result["s"] = $pn;



                        goto Ji;
                    }
                }
            }







            $count = $count + 1;







            unqid:



            $uniqid = uniqid(15);



            $mcheck = select_query($con, "mticket", "", "`transaction_id`='$uniqid' and `deletes`='0' ", "", "");



            $ochekc = select_query($con, "ticket", "", "`transaction_id`='$uniqid' and `deletes`='0' ", "", "");



            $achekc = select_query($con, "aticket", "", "`transaction_id`='$uniqid' and `deletes`='0' ", "", "");



            if ($mcheck['nr'] > 0 || $ochekc['nr'] > 0 || $achekc['nr'] > 0) {



                goto unqid;
            }







            $drawid = $_POST['drawid'];



            $draw_details = select_query($con, "draw", "", "`id`='$drawid' and `deletes`='0' ", "", "");



            if ($draw_details['nr'] > 0) {



                $draw_status = $draw_details['result'][0]['status'];
            }



            $myuserno = $_POST['myUser'];



            $mobile = '';



            $username = '';



            $ticketnumber = $_POST['ticketnumber'];



            $userid = '';



            $email = '';



            $ticket = select_query($con, "mticket", "", "(`ticket_no`='MT-$ticketnumber' OR `ticket_no`='MT$ticketnumber') and `deletes`='0' ", "", "");



            if ($ticket['nr'] == 0) {



                $sql0 = "INSERT INTO `invoice` (`id`, `ticket_id`, `deletes`, `createdon`, `type`) VALUES (NULL, '$ticketnumber', '0', '$dubaidate_time', 'MT');";



                $run = mysqli_query($con, $sql0);



                if ($run) {



                    $invoice = "SELECT * FROM `invoice` WHERE `ticket_id` = '$ticketnumber' ORDER BY createdon DESC LIMIT 1";



                    $getinvoice = mysqli_query($con, $invoice);



                    if ($getinvoice) {



                        $getin = $getinvoice->fetch_assoc();



                        // $last_id = $con->insert_id;



                        $sql = "SELECT * FROM `user_register` WHERE  `mobile` = '$myuserno' AND `deletes`='0'";



                        $run = mysqli_query($con, $sql);



                        if (mysqli_num_rows($run) > 0) {



                            $row = mysqli_fetch_array($run);



                            $userid = $row['id'];



                            $username = $row['name'];



                            $mobile = $row['mobile'];



                            $email = $row['email'];
                        } else {



                            $result["type"] = "0";



                            $result["result"] = "User Not Found!";
                        }







                        $totalAmount = '';



                        if ($count <= 13) {



                            for ($i = 1; $i <= $count; $i++) {



                                $no = '';



                                if ($i <= 9) {



                                    $no = '0' . $i;
                                } else if ($i <= 12) {



                                    $no = $i;
                                } else {
                                }



                                $pname = "productid" . $i;



                                $proid = $_POST[$pname];



                                $result['pid'] = $proid;



                                $lucky = "my3number" . $i;



                                $rafflevalue = $omatype . $ticketnumber . $no;



                                $p = select_query($con, "product", "", "`id`='$proid' and `deletes`='0' ", "", "");







                                if ($p['nr'] > 0) {



                                    $totalAmount += floatval($p['result'][0]['rate']);



                                    $m3n = $_POST[$lucky];



                                    $sql2 = "INSERT INTO `ticket_lines` (`id`, `user_id`, `ticket_id`, `draw_id`, `agent_id`, `product_id`, `orders`, `my3number`, `raffle_id`, `invoice_no`, `deletes`, `createdon`, `type`) VALUES (NULL, '$userid', '$ticketnumber', '$drawid', '$_SESSION[memid]', '$_POST[$pname]', '$ticketnumber', '$m3n', '$rafflevalue', '$getin[id]', '0', '$dubaidate_time', '$omatype');";



                                    // $sql2 = "INSERT INTO `ticket_lines` (`id`, `user_id`, `ticket_id`, `draw_id`, `agent_id`, `product_id`, `orders`, `my3number`, `raffle_id`, `invoice_no`, `deletes`, `createdon`, `type`) VALUES (NULL, '$userid', '$ticketnumber', '$drawid', '$_SESSION[memid]', '$_POST[$pname]', '$ticketnumber', '$_POST[$lucky]', '$rafflevalue', '$getin[id]', '0', '$dubaidate_time', '$omatype');";



                                    $run2 = mysqli_query($con, $sql2);
                                }
                            }
                        }







                        $count = $count - 1;







                        $sql = "INSERT INTO `mticket` (`id`, `draw_id`, `agent_id`, `user_id`, `ticket_no`, `invoice_no`, `sale_from`, `purchase_datetime`, `total_amount`, `tax_percentage`, `tax_value`, `net_total`, `status`, `payment_by`, `total_lines`, `transaction_id`, `payment_transaction_id`, `deletes`, `createdon`) VALUES



									 (NULL, '$drawid ', '$_SESSION[memid]', '$userid', 'MT$ticketnumber', '$getin[id]', '$_SESSION[roll_id]', '$dubaidate_time', '$totalAmount', '5.00', '0', '$totalAmount', '1', '1', '$count', '$uniqid', '$uniqid', '0', '$dubaidate_time');";







                        $run5 = mysqli_query($con, $sql);



                        if ($run5) {



                            $sql44 = "SELECT * FROM `mticket` WHERE `invoice_no` = '$getin[id]' AND `user_id` = '$userid' AND `deletes`='0' ORDER BY `id` DESC LIMIT 1";







                            $run22 = mysqli_query($con, $sql44);



                            if (mysqli_num_rows($run22)) {



                                $row8 = $run22->fetch_assoc();



                                $result["id"] = str_pad($row8['id'], 7, "0", STR_PAD_LEFT);







                                $update = "UPDATE `ticket_lines` SET `ticket_id` = '$row8[id]' WHERE `invoice_no` = '$getin[id]' AND `user_id` = '$userid' AND `deletes`='0';";



                                $runnew = mysqli_query($con, $update);







                                $update3 = "UPDATE `invoice` SET `ticket_id` = '$row8[id]' WHERE `ticket_id`='$ticketnumber';";



                                $runnew = mysqli_query($con, $update3);







                                $mticket_new_id = $row8['id'];
                            }







                            // $email = "developer@cwd.co.in";



                            $subject = "Purchase Confirmation";



                            $messages = '<!DOCTYPE html>



                    <html>







                    <head>



                        <title></title>



                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



                        <meta name="viewport" content="width=device-width, initial-scale=1">



                        <meta http-equiv="X-UA-Compatible" content="IE=edge" />



                        <style type="text/css">



                            body,



                            table,



                            td,



                            a {



                                -webkit-text-size-adjust: 100%;



                                -ms-text-size-adjust: 100%;



                            }







                            table,



                            td {



                                mso-table-lspace: 0pt;



                                mso-table-rspace: 0pt;



                            }







                            img {



                                -ms-interpolation-mode: bicubic;



                            }







                            .logo-img {



                                width: 153px;



                                background: #fff;



                                position: relative;



                                top: 70px;



                                border-radius: 76px;



                                height: 153px;



                            }







                            .logo-img img {



                                background: #ffff;



                                top: 0;



                                position: relative;



                                border-radius: 100px;



                                width: 150px;



                                height: 150px;



                                padding: 0;



                                margin: auto;



                            }



                            /* RESET STYLES */







                            img {



                                border: 0;



                                height: auto;



                                line-height: 100%;



                                outline: none;



                                text-decoration: none;



                            }







                            table {



                                border-collapse: collapse !important;



                            }







                            body {



                                height: 100% !important;



                                margin: 0 !important;



                                padding: 0 !important;



                                width: 100% !important;



                            }



                            /* iOS BLUE LINKS */







                            a[x-apple-data-detectors] {



                                color: inherit !important;



                                text-decoration: none !important;



                                font-size: inherit !important;



                                font-family: inherit !important;



                                font-weight: inherit !important;



                                line-height: inherit !important;



                            }



                            /* MOBILE STYLES */







                            @media screen and (max-width:600px) {



                                h1 {



                                    font-size: 32px !important;



                                    line-height: 32px !important;



                                }



                            }



                            /* ANDROID CENTER FIX */







                            div[style*="margin: 16px 0;"] {



                                margin: 0 !important;



                            }



                        </style>



                    </head>







                    <body style="background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;">



                        <div class="container">



                            <!-- HIDDEN PREHEADER TEXT -->







                            <table border="0" cellpadding="0" cellspacing="0" width="100%">



                                <!-- LOGO -->



                                <tr>



                                    <td bgcolor="#6b286f" align="center" style="background-image: -webkit-linear-gradient(124deg, #2e76a1 0%, #002387 100%);">



                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">';







                            $messages .= '<tr>



                                                <td align="center" valign="top"> </td>



                                            </tr>



                                        </table>



                                    </td>



                                </tr>



                                <tr>



                                    <td align="center" style="padding: 0px 10px 0px 10px;">



                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">



                                            <tr>



                                                <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: Lato, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 400; line-height: 48px;">



                                                <div style="font-family: Helvetica,Arial,sans-serif;min-width:100%;overflow:auto;line-height:2">



                                                <div >



                                               <div style="border-bottom:1px solid #eee">



                                                 <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">NATIONAL DRAW</a>



                                               </div>



                                               <p style="font-size:1.1em">Hi ' . $username . ',</p>



                                               <p style="font-size:1.1em">Thank you for your purchase and donations</p>



                                                </td>



                                            </tr>



                                        </table>



                                    </td>



                                </tr>



                                <tr>



                                    <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">



                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">



                                            <tr>



                                                <td bgcolor="#f4f4f4" align="center">



                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">



                                                        <tr>



                                                            <td bgcolor="#ffffff" align="center" valign="top" style="padding: 12px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: Lato, Helvetica, Arial, sans-serif; font-size: 24px; font-weight: 400;">







                                                                <h2 style="font-size: 24px; font-weight: 600; ">



                                                                    Ticket ID # ' . $omatype . '' . $ticketnumber . '<br>



                                                                </h2>



                                                            </td>



                                                        </tr>



                                                    </table>



                                                </td>



                                            </tr>



                                            <!-- COPY -->



                                            <!-- COPY -->







                                            <tr>



                                                <td bgcolor="#ffffff" align="left">



                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">



                                                        <tbody>



                                                            <tr>



                                                                <td bgcolor="#ffffff" align="center" style="padding:12px">



                                                                    <table border="1" cellspacing="2" cellpadding="0">



                                                                        <tbody>



                                                                            <tr>



                                                                                <th bgcolor="#ffffff" align="center" style="padding:12px">



                                                                                      Products



                                                                                </th>



                                                                                <th bgcolor="#ffffff" align="center" style="padding:12px">



                                                                                Lines



                                                                                </th>



                                                                                <th bgcolor="#ffffff" align="center" style="padding:12px">



                                          My<span font-size: 22px;



      font-family: "Montserrat", sans-serif !important; >3</span>                                      Numbers



                                                                                </th>



                                                                                <th bgcolor="#ffffff" align="center" style="padding:12px">



                                                                                Raffle ID



                                                                                </th>



                                                                            </tr>';







                            $query = select_query($con, "ticket_lines", "", "`ticket_id`='$mticket_new_id' and `type` = '$omatype' and `ticket_id`!='' AND `deletes`='0' group by `product_id` order by `product_id` ASC  ", "", "");







                            foreach ($query['result'] as $key => $valuelist) {



                                $p_id = $valuelist['product_id'];



                                $t_id = $valuelist['orders'];



                                $product = select_query($con, "product", "", "`id`='$valuelist[product_id]'  ", "", "");







                                foreach ($product['result'] as $key => $productinfo) {
                                }



                                $pcountlist = select_query_count($con, "ticket_lines", "id", "`ticket_id`='$mticket_new_id' and `product_id`='$valuelist[product_id]' and `type` = '$omatype' and `orders`='$valuelist[orders]' AND `deletes`='0' ", "", "");







                                $mynumber = select_query($con, "ticket_lines", "", "`ticket_id`='$mticket_new_id' and `product_id`='$p_id' and `type` = '$omatype' and `orders`='$t_id' AND `deletes`='0'", "", "");







                                $messages .= '<tr>



                          <td bgcolor="#ffffff" align="center" style="padding:12px">AED ' . number_format((float) $productinfo['rate'], 2, '.', '') . '</td>';



                                $messages .= '<td bgcolor="#ffffff" align="center" style="padding:12px">' . $mynumber['nr'] . '</td>';







                                $messages .= '<td bgcolor="#ffffff" align="center" style="padding:12px">';







                                foreach ($mynumber['result'] as $key => $mynumber1) {



                                    $messages .= $mynumber1['my3number'] . "<br>";
                                }







                                $messages .= '</td>';







                                $messages .= '<td bgcolor="#ffffff" align="center" style="padding:12px">';







                                foreach ($mynumber['result'] as $key => $mynumber1) {



                                    $messages .= $mynumber1['raffle_id'] . "<br>";
                                }







                                $messages .= '</td>';







                                $messages .= '</tr>';
                            }







                            $messages .= '</tbody>



                                                                    </table>



                                                                </td>



                                                            </tr>



                                                        </tbody>



                                                    </table>



                                                </td>



                                            </tr>



                                            <tr>



                                                <td bgcolor="#ffffff" align="center" valign="top" style="padding: 12px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: Lato, Helvetica, Arial, sans-serif; font-size: 24px; font-weight: 400; line-height: 48px;">







                                                    <h2 style="font-size: 24px; font-weight: 600; ">



                                                        Total Amount:AED ' . number_format((float) $totalAmount, 2, '.', '') . '<br>



                                                    </h2>



                                                </td>



                                            </tr>



                                            <tr>



                                                <td bgcolor="#ffffff" align="left">



                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">



                                                        <tr>



                                                            <td bgcolor="#ffffff" align="center" style="padding:12px">



                                                                <table border="0" cellspacing="0" cellpadding="0">



                                                                    <tr>







                                                                        <td align="center" style="border-radius: 3px; background-image: -webkit-linear-gradient(-45deg, #0e336d 0%, #ce2629 100%); " bgcolor="#6b286f">



                                                                            <a href="' . $baseurl . 'ticket-view/' . $uniqid . '" target="_blank" style="font-size: 20px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; text-decoration: none; padding: 15px 25px; border-radius: 2px; display: inline-block;">View Ticket



                                                            </a></td>



                                                                        <td>&nbsp;</td>



                                                                        <td align="center" style="border-radius: 3px; background-image: -webkit-linear-gradient(-45deg, #0e336d 0%, #ce2629 100%); " bgcolor="#6b286f">



                                                                            <a href="' . $baseurl . 'invoice/' . $uniqid . '" target="_blank" style="font-size: 20px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; text-decoration: none; padding: 15px 25px; border-radius: 2px; display: inline-block;">



                                          View Invoice



                                        </a></td>



                                                                    </tr>



                                                                </table>



                                                            </td>



                                                        </tr>



                                                    </table>



                                                </td>



                                            </tr>







                                        </table>



                                    </td>



                                </tr>







                                <tr>



                                    <td bgcolor="#f4f4f4" align="center" style="padding: 30px 10px 0px 10px;">



                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">



                                            <tr>



                                                <td bgcolor="#e4dcf1" align="center" style="padding: 30px 30px 30px 30px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: Lato, Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">







                                                    <p style="margin: 0;" target="_blank" style="color: #6b286f;">Good Luck!!! For future draws, national Steps Big Dreams.</p>



                                                </td>



                                            </tr>



                                        </table>



                                    </td>



                                </tr>



                                <tr>



                                    <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">



                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">



                                            <tr>



                                                <td bgcolor="#f4f4f4" align="left" style="padding: 0px 30px 30px 30px; color: #666666; font-family: Lato, Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px;"> <br>



                                                   

                                                    <p style="color: #29377d !important;font-size: 15px !important;margin: 0px !important;text-align: center !important;font-weight: 500 !important;font-style: italic !important;font-family: Arial Narrow !important;margin: 8px 0px 0px 0px !important;">Note: This is a system auto generated email. Please do not reply to this mail.<br>
                                                    
                                                    For Clarification
                                                    
                                                    
                                                           <br>
                                                    
                                                    Call 04 33 98880 Whatsapp +971 56 199 1271
                                                    
                                                    <br>
                                                    
                                                    or email support@nationaldraw.com</p>

                                                </td>



                                            </tr>



                                        </table>



                                    </td>



                                </tr>



                            </table>



                        </div>



                    </body>







                    </html>';







                            if ($draw_status == 'Active') {



                                $emailchack =  explode('@', $email);



                                if (strtolower($emailchack[1]) != "nationaldraw.ae") {



                                    $user_ip = getUserIP();



                                    $insertlog = mysqli_query($con, "INSERT INTO `emaillog` (`details`,`subject`,`email`,`ip`,`datetime`,`status`) VALUES ('$messages','$subject','$email','$user_ip','$dubaidate_time','0')");



                                    // echo 'Message has been sent';



                                    // $emailsend = sendemail($con, $email, $subject, $messages);



                                    $emailsend = true;
                                } else {



                                    $emailsend = true;
                                }
                            } else {



                                $emailsend = true;
                            }











                            if ($emailsend) {



                                if ($draw_status == 'Active') {







                                    if (substr($mobile, 0, 3) == "971") {



                                        $messages1 = 'Thank you for your purchase and donations. Ticket ID #' . $omatype . '' . $ticketnumber . '.';







                                        $query = select_query($con, "ticket_lines", "", "`ticket_id`='$mticket_new_id' and `type` = '$omatype' and `ticket_id`!='' AND `deletes`='0' group by `product_id` order by `product_id` ASC  ", "", "");







                                        foreach ($query['result'] as $key => $valuelist) {



                                            $p_id = $valuelist['product_id'];



                                            $t_id = $valuelist['orders'];



                                            $product = select_query($con, "product", "", "`id`='$valuelist[product_id]'  ", "", "");



                                            $messages1 .= 'CAT-AED ' . round($product['result'][0]['rate']) . '. ';



                                            // $messages1 .= 'CAT-AED ' . number_format((float) $product['result'][0]['rate'], 2, '.', '') . ' ';



                                            $rcount = count($mynumber['result']);



                                            // $result["rcount"] = $rcount;



                                            $io = 1;



                                            $mynumber = select_query($con, "ticket_lines", "", "`ticket_id`='$mticket_new_id' and `product_id`='$p_id' and `type` = '$omatype' and `orders`='$t_id' AND `deletes`='0'", "", "");



                                            // $messages1 .= 'Selected No: ';



                                            foreach ($mynumber['result'] as $key => $mynumber1) {



                                                $messages1 .= $mynumber1['my3number'];



                                                if ($io == $rcount) {



                                                    $messages1 .= '. ';
                                                } else {



                                                    $messages1 .= ', ';
                                                }



                                                $io++;
                                            }
                                        }







                                        $printurlf = $baseurl . 'ticket-view/' . $uniqid;



                                        $printurl = get_tiny_url($printurlf);







                                        $messages1 .= 'for a Total Amounts of AED ' . number_format((float) $totalAmount, 2, '.', '') . ' for more info. (' . $printurl . '),. TC apply.';



                                        $templateid = "";



                                        sendsms($con, $mobile, $messages1, $templateid);
                                    }
                                }











                                $t = get_tiny_url($baseurl . 'ticket-view/' . $uniqid);



                                $in = get_tiny_url($baseurl . 'invoice/' . $uniqid);







                                $action = '';



                                $action .= '<div class="g-2">';



                                $action .= '<a target="_blank" href="' . $in . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span class="fa fa-file-text-o fs-14"></span></a>';



                                $action .= '<a target="_blank" href="' . $t . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span class="fa fa-files-o fs-14"></span></a>';



                                $action .= '</div>';







                                $result["type"] = "1";



                                $result["result"] = "The Created Manual Ticket has been Sent Successfully.";



                                $result["amount"] = $totalAmount;



                                $result["mobile"] = $mobile;



                                $result["qty"] = $count;



                                $result["ticketno"] = $ticketnumber;



                                $result["userid"] = $userid;



                                $result["name"] = $username;



                                $result["action"] = $action;
                            } else {



                                $result["type"] = "0";



                                $result["result"] = $emailsend;



                                goto Ji;
                            }
                        } else {



                            $result["type"] = "0";



                            $result["result"] = "failed";
                        }
                    }
                }
            } else {



                $result["type"] = "0";



                $result["result"] = "Ticket Found!";
            }
        } else {



            $result["type"] = "0";



            $result["result"] = "Date Format Not Supported!";



            goto Ji;
        }
    } else {



        $result["type"] = "0";



        $result["result"] = "Inform Error";



        goto Ji;
    }







    Ji:



    echo json_encode($result);
} else if ($method == "registeroffline") {



    $result = [];



    $count = intval($_POST['count']);



    $omatype = 'MT';



    if ($count <= 13) {



        for ($i = 1; $i <= $count; $i++) {



            $pn = "productid" . $i;



            $id3 = "my3number" . $i;



            if ($_POST[$pn] != '' && $_POST[$id3] != '') {
            } else {



                $result["type"] = "0";



                $result["result"] = "Please fill all fields!";



                $result["s"] = $pn;



                goto result;
            }
        }
    }







    $count = $count + 1;







    unid:



    $uniqid = uniqid(15);



    $mcheck = select_query($con, "mticket", "", "`transaction_id`='$uniqid' and `deletes`='0' ", "", "");



    $ochekc = select_query($con, "ticket", "", "`transaction_id`='$uniqid' and `deletes`='0' ", "", "");



    $achekc = select_query($con, "aticket", "", "`transaction_id`='$uniqid' and `deletes`='0' ", "", "");



    if ($mcheck['nr'] > 0 || $ochekc['nr'] > 0 || $achekc['nr'] > 0) {



        goto unid;
    }







    $drawid = $_POST['drawid'];



    $myuserno = $_POST['myUser'];



    $mobile = '';



    $username = '';



    $ticketnumber = $_POST['ticketnumber'];



    $userid = '';



    $email = '';



    // $ticket = select_query($con, "invoice", "", "`ticket_id`='$ticketnumber' and `deletes`='0' ", "", "");



    $ticket = select_query($con, "mticket", "", "(`ticket_no`='MT-$ticketnumber' OR `ticket_no`='MT$ticketnumber') and `deletes`='0' ", "", "");



    if ($ticket['nr'] == 0) {



        $sql0 = "INSERT INTO `invoice` (`id`, `ticket_id`, `deletes`, `createdon`, `type`) VALUES (NULL, '$ticketnumber', '0', '$dubaidate_time', 'MT');";



        $run = mysqli_query($con, $sql0);



        if ($run) {



            $invoice = "SELECT * FROM `invoice` WHERE `ticket_id` = '$ticketnumber' ORDER BY createdon DESC LIMIT 1";



            $getinvoice = mysqli_query($con, $invoice);



            if ($getinvoice) {



                $getin = $getinvoice->fetch_assoc();



                // $last_id = $con->insert_id;



                $sql = "SELECT * FROM `user_register` WHERE  `mobile` = '$myuserno' AND `deletes`='0'";



                $run = mysqli_query($con, $sql);



                if (mysqli_num_rows($run) > 0) {



                    $row = mysqli_fetch_array($run);



                    $userid = $row['id'];



                    $username = $row['name'];



                    $mobile = $row['mobile'];



                    $email = $row['email'];
                } else {



                    $result["type"] = "0";



                    $result["result"] = "User Not Found!";
                }







                $totalAmount = '';



                if ($count <= 13) {



                    for ($i = 1; $i <= $count; $i++) {



                        $no = '';



                        if ($i <= 9) {



                            $no = '0' . $i;
                        } else if ($i <= 12) {



                            $no = $i;
                        } else {
                        }



                        $pname = "productid" . $i;



                        $proid = $_POST[$pname];



                        $result['pid'] = $proid;



                        $lucky = "my3number" . $i;



                        $rafflevalue = $omatype . $ticketnumber . $no;



                        $p = select_query($con, "product", "", "`id`='$proid' and `deletes`='0' ", "", "");







                        if ($p['nr'] > 0) {



                            $totalAmount += floatval($p['result'][0]['rate']);



                            $m3n = $_POST[$lucky];



                            $sql2 = "INSERT INTO `ticket_lines` (`id`, `user_id`, `ticket_id`, `draw_id`, `agent_id`, `product_id`, `orders`, `my3number`, `raffle_id`, `invoice_no`, `deletes`, `createdon`, `type`) VALUES (NULL, '$userid', '$ticketnumber', '$drawid', '$_SESSION[memid]', '$_POST[$pname]', '$ticketnumber', '$m3n', '$rafflevalue', '$getin[id]', '0', '$dubaidate_time', '$omatype');";







                            $run2 = mysqli_query($con, $sql2);
                        }
                    }
                }







                $count = $count - 1;







                $sql = "INSERT INTO `mticket` (`id`, `draw_id`, `agent_id`, `user_id`, `ticket_no`, `invoice_no`, `sale_from`, `purchase_datetime`, `total_amount`, `tax_percentage`, `tax_value`, `net_total`, `status`, `payment_by`, `total_lines`, `transaction_id`, `payment_transaction_id`, `deletes`, `createdon`) VALUES



									 (NULL, '$drawid ', '$_SESSION[memid]', '$userid', 'MT$ticketnumber', '$getin[id]', '$_SESSION[roll_id]', '$dubaidate_time', '$totalAmount', '5.00', '0', '$totalAmount', '1', '1', '$count', '$uniqid', '$uniqid', '0', '$dubaidate_time');";







                $run5 = mysqli_query($con, $sql);



                if ($run5) {



                    $sql44 = "SELECT * FROM `mticket` WHERE `invoice_no` = '$getin[id]' AND `user_id` = '$userid' AND `deletes`='0' ORDER BY `id` DESC LIMIT 1";







                    $run22 = mysqli_query($con, $sql44);



                    if (mysqli_num_rows($run22)) {



                        $row8 = $run22->fetch_assoc();



                        $result["id"] = str_pad($row8['id'], 7, "0", STR_PAD_LEFT);







                        $update = "UPDATE `ticket_lines` SET `ticket_id` = '$row8[id]' WHERE `invoice_no` = '$getin[id]' AND `user_id` = '$userid' AND `deletes`='0';";



                        $runnew = mysqli_query($con, $update);







                        $update3 = "UPDATE `invoice` SET `ticket_id` = '$row8[id]' WHERE `ticket_id`='$ticketnumber';";



                        $runnew = mysqli_query($con, $update3);







                        $mticket_new_id = $row8['id'];
                    }









                    $subject = "Purchase Confirmation";



                    $messages = '<!DOCTYPE html>



                    <html>







                    <head>



                        <title></title>



                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



                        <meta name="viewport" content="width=device-width, initial-scale=1">



                        <meta http-equiv="X-UA-Compatible" content="IE=edge" />



                        <style type="text/css">



                            body,



                            table,



                            td,



                            a {



                                -webkit-text-size-adjust: 100%;



                                -ms-text-size-adjust: 100%;



                            }







                            table,



                            td {



                                mso-table-lspace: 0pt;



                                mso-table-rspace: 0pt;



                            }







                            img {



                                -ms-interpolation-mode: bicubic;



                            }







                            .logo-img {



                                width: 153px;



                                background: #fff;



                                position: relative;



                                top: 70px;



                                border-radius: 76px;



                                height: 153px;



                            }







                            .logo-img img {



                                background: #ffff;



                                top: 0;



                                position: relative;



                                border-radius: 100px;



                                width: 150px;



                                height: 150px;



                                padding: 0;



                                margin: auto;



                            }



                            /* RESET STYLES */







                            img {



                                border: 0;



                                height: auto;



                                line-height: 100%;



                                outline: none;



                                text-decoration: none;



                            }







                            table {



                                border-collapse: collapse !important;



                            }







                            body {



                                height: 100% !important;



                                margin: 0 !important;



                                padding: 0 !important;



                                width: 100% !important;



                            }



                            /* iOS BLUE LINKS */







                            a[x-apple-data-detectors] {



                                color: inherit !important;



                                text-decoration: none !important;



                                font-size: inherit !important;



                                font-family: inherit !important;



                                font-weight: inherit !important;



                                line-height: inherit !important;



                            }



                            /* MOBILE STYLES */







                            @media screen and (max-width:600px) {



                                h1 {



                                    font-size: 32px !important;



                                    line-height: 32px !important;



                                }



                            }



                            /* ANDROID CENTER FIX */







                            div[style*="margin: 16px 0;"] {



                                margin: 0 !important;



                            }



                        </style>



                    </head>







                    <body style="background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;">



                        <div class="container">



                            <!-- HIDDEN PREHEADER TEXT -->







                            <table border="0" cellpadding="0" cellspacing="0" width="100%">



                                <!-- LOGO -->



                                <tr>



                                    <td bgcolor="#6b286f" align="center" style="background-image: -webkit-linear-gradient(124deg, #2e76a1 0%, #002387 100%);">



                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">';







                    $messages .= '<tr>



                                                <td align="center" valign="top"> </td>



                                            </tr>



                                        </table>



                                    </td>



                                </tr>



                                <tr>



                                    <td align="center" style="padding: 0px 10px 0px 10px;">



                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">



                                            <tr>



                                                <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: Lato, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 400; line-height: 48px;">



                                                <div style="font-family: Helvetica,Arial,sans-serif;min-width:100%;overflow:auto;line-height:2">



                                                <div >



                                               <div style="border-bottom:1px solid #eee">



                                                 <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">NATIONAL DRAW</a>



                                               </div>



                                               <p style="font-size:1.1em">Hi ' . $username . ',</p>



                                               <p style="font-size:1.1em">Thank you for your purchase and donations</p>



                                                </td>



                                            </tr>



                                        </table>



                                    </td>



                                </tr>



                                <tr>



                                    <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">



                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">



                                            <tr>



                                                <td bgcolor="#f4f4f4" align="center">



                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">



                                                        <tr>



                                                            <td bgcolor="#ffffff" align="center" valign="top" style="padding: 12px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: Lato, Helvetica, Arial, sans-serif; font-size: 24px; font-weight: 400;">







                                                                <h2 style="font-size: 24px; font-weight: 600; ">



                                                                    Ticket ID # ' . $omatype . '' . $ticketnumber . '<br>



                                                                </h2>



                                                            </td>



                                                        </tr>



                                                    </table>



                                                </td>



                                            </tr>



                                            <!-- COPY -->



                                            <!-- COPY -->







                                            <tr>



                                                <td bgcolor="#ffffff" align="left">



                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">



                                                        <tbody>



                                                            <tr>



                                                                <td bgcolor="#ffffff" align="center" style="padding:12px">



                                                                    <table border="1" cellspacing="2" cellpadding="0">



                                                                        <tbody>



                                                                            <tr>



                                                                                <th bgcolor="#ffffff" align="center" style="padding:12px">



                                                                                      Products



                                                                                </th>



                                                                                <th bgcolor="#ffffff" align="center" style="padding:12px">



                                                                                Lines



                                                                                </th>



                                                                                <th bgcolor="#ffffff" align="center" style="padding:12px">



                                                                                                    My<span font-size: 22px;



                                                                font-family: "Montserrat", sans-serif !important; >3</span>                                      Numbers



                                                                                                                                            </th>



                                                                                <th bgcolor="#ffffff" align="center" style="padding:12px">



                                                                                Raffle ID



                                                                                </th>



                                                                            </tr>';







                    $query = select_query($con, "ticket_lines", "", "`ticket_id`='$mticket_new_id' and  `type` = '$omatype' and `ticket_id`!='' AND `deletes`='0' group by `product_id` order by `product_id` ASC  ", "", "");







                    foreach ($query['result'] as $key => $valuelist) {



                        $p_id = $valuelist['product_id'];



                        $t_id = $valuelist['orders'];



                        $product = select_query($con, "product", "", "`id`='$valuelist[product_id]'  ", "", "");







                        foreach ($product['result'] as $key => $productinfo) {
                        }



                        $pcountlist = select_query_count($con, "ticket_lines", "id", "`ticket_id`='$mticket_new_id' and `product_id`='$valuelist[product_id]' and `type` = '$omatype' and `orders`='$valuelist[orders]' AND `deletes`='0'", "", "");







                        $mynumber = select_query($con, "ticket_lines", "", "`ticket_id`='$mticket_new_id' and `product_id`='$p_id' and `type` = '$omatype' and `orders`='$t_id' AND `deletes`='0'", "", "");







                        $messages .= '<tr>



                          <td bgcolor="#ffffff" align="center" style="padding:12px">AED ' . number_format((float) $productinfo['rate'], 2, '.', '') . '</td>';



                        $messages .= '<td bgcolor="#ffffff" align="center" style="padding:12px">' . $mynumber['nr'] . '</td>';







                        $messages .= '<td bgcolor="#ffffff" align="center" style="padding:12px">';







                        foreach ($mynumber['result'] as $key => $mynumber1) {



                            $messages .= $mynumber1['my3number'] . "<br>";
                        }







                        $messages .= '</td>';







                        $messages .= '<td bgcolor="#ffffff" align="center" style="padding:12px">';







                        foreach ($mynumber['result'] as $key => $mynumber1) {



                            $messages .= $mynumber1['raffle_id'] . "<br>";
                        }







                        $messages .= '</td>';







                        $messages .= '</tr>';
                    }







                    $messages .= '</tbody>



                                                                    </table>



                                                                </td>



                                                            </tr>



                                                        </tbody>



                                                    </table>



                                                </td>



                                            </tr>



                                            <tr>



                                                <td bgcolor="#ffffff" align="center" valign="top" style="padding: 12px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: Lato, Helvetica, Arial, sans-serif; font-size: 24px; font-weight: 400; line-height: 48px;">







                                                    <h2 style="font-size: 24px; font-weight: 600; ">



                                                        Total Amount:AED ' . number_format((float) $totalAmount, 2, '.', '') . '<br>



                                                    </h2>



                                                </td>



                                            </tr>



                                            <tr>



                                                <td bgcolor="#ffffff" align="left">



                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">



                                                        <tr>



                                                            <td bgcolor="#ffffff" align="center" style="padding:12px">



                                                                <table border="0" cellspacing="0" cellpadding="0">



                                                                    <tr>







                                                                        <td align="center" style="border-radius: 3px; background-image: -webkit-linear-gradient(-45deg, #0e336d 0%, #ce2629 100%); " bgcolor="#6b286f">



                                                                            <a href="' . $baseurl . 'ticket-view/' . $uniqid . '" target="_blank" style="font-size: 20px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; text-decoration: none; padding: 15px 25px; border-radius: 2px; display: inline-block;">View Ticket



                                                            </a></td>



                                                                        <td>&nbsp;</td>



                                                                        <td align="center" style="border-radius: 3px; background-image: -webkit-linear-gradient(-45deg, #0e336d 0%, #ce2629 100%); " bgcolor="#6b286f">



                                                                            <a href="' . $baseurl . 'invoice/' . $uniqid . '" target="_blank" style="font-size: 20px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; text-decoration: none; padding: 15px 25px; border-radius: 2px; display: inline-block;">



                                          View Invoice



                                        </a></td>



                                                                    </tr>



                                                                </table>



                                                            </td>



                                                        </tr>



                                                    </table>



                                                </td>



                                            </tr>







                                        </table>



                                    </td>



                                </tr>







                                <tr>



                                    <td bgcolor="#f4f4f4" align="center" style="padding: 30px 10px 0px 10px;">



                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">



                                            <tr>



                                                <td bgcolor="#e4dcf1" align="center" style="padding: 30px 30px 30px 30px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: Lato, Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">







                                                    <p style="margin: 0;" target="_blank" style="color: #6b286f;">Good Luck!!! For future draws, national Steps Big Dreams.</p>



                                                </td>



                                            </tr>



                                        </table>



                                    </td>



                                </tr>



                                <tr>



                                    <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">



                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">



                                            <tr>



                                                <td bgcolor="#f4f4f4" align="left" style="padding: 0px 30px 30px 30px; color: #666666; font-family: Lato, Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 18px;"> <br>



                                                    

                                                      <p style="color: #29377d !important;font-size: 15px !important;margin: 0px !important;text-align: center !important;font-weight: 500 !important;font-style: italic !important;font-family: Arial Narrow !important;margin: 8px 0px 0px 0px !important;">Note: This is a system auto generated email. Please do not reply to this mail.<br>
                                                      
                                                      For Clarification
                                                      
                                                      
                                                             <br>
                                                      
                                                      Call 04 33 98880 Whatsapp +971 56 199 1271
                                                      
                                                      <br>
                                                      
                                                      or email support@nationaldraw.com</p>

                                                </td>



                                            </tr>



                                        </table>



                                    </td>



                                </tr>



                            </table>



                        </div>



                    </body>







                    </html>';





                    $emailchack =  explode('@', $email);

                    if (strtolower($emailchack[1]) != "nationaldraw.ae") {

                        $emailsend = sendemail($con, $email, $subject, $messages, 'tickets');
                    } else {

                        $emailsend = true;
                    }









                    if ($emailsend) {



                        if (substr($mobile, 0, 3) == "971") {



                            $messages1 = 'Thank you for your purchase and donations. Ticket ID #' . $omatype . '' . $ticketnumber . '.';







                            $query = select_query($con, "ticket_lines", "", "`ticket_id`='$mticket_new_id' and `type` = '$omatype' and `ticket_id`!='' AND `deletes`='0' group by `product_id` order by `product_id` ASC  ", "", "");







                            foreach ($query['result'] as $key => $valuelist) {



                                $p_id = $valuelist['product_id'];



                                $t_id = $valuelist['orders'];



                                $product = select_query($con, "product", "", "`id`='$valuelist[product_id]'  ", "", "");



                                $messages1 .= 'CAT-AED ' . round($product['result'][0]['rate']) . '. ';



                                // $messages1 .= 'CAT-AED ' . number_format((float) $product['result'][0]['rate'], 2, '.', '') . ' ';



                                $rcount = count($mynumber['result']);



                                // $result["rcount"] = $rcount;



                                $io = 1;



                                $mynumber = select_query($con, "ticket_lines", "", "`ticket_id`='$mticket_new_id' and `product_id`='$p_id' and `type` = '$omatype' and `orders`='$t_id' AND `deletes`='0'", "", "");



                                // $messages1 .= 'Selected No: ';



                                foreach ($mynumber['result'] as $key => $mynumber1) {



                                    $messages1 .= $mynumber1['my3number'];



                                    if ($io == $rcount) {



                                        $messages1 .= '. ';
                                    } else {



                                        $messages1 .= ', ';
                                    }



                                    $io++;
                                }
                            }







                            $printurlf = $baseurl . 'ticket-view/' . $uniqid;



                            $printurl = get_tiny_url($printurlf);







                            $messages1 .= 'for a Total Amounts of AED ' . number_format((float) $totalAmount, 2, '.', '') . ' for more info. (' . $printurl . '),. TC apply.';



                            $templateid = "";



                            sendsms($con, $mobile, $messages1, $templateid);
                        }







                        $t = get_tiny_url($baseurl . 'ticket-view/' . $uniqid);



                        $in = get_tiny_url($baseurl . 'invoice/' . $uniqid);







                        $action = '';



                        $action .= '<div class="g-2">';



                        $action .= '<a target="_blank" href="' . $in . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span class="fa fa-file-text-o fs-14"></span></a>';



                        $action .= '<a target="_blank" href="' . $t . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span class="fa fa-files-o fs-14"></span></a>';



                        $action .= '</div>';







                        $result["type"] = "1";



                        $result["result"] = "The Created Manual Ticket has been Sent Successfully.";



                        $result["amount"] = $totalAmount;



                        $result["mobile"] = $mobile;



                        $result["qty"] = $count;



                        $result["ticketno"] = $ticketnumber;



                        $result["userid"] = $userid;



                        $result["name"] = $username;



                        $result["action"] = $action;
                    } else {



                        $result["type"] = "0";



                        $result["result"] = $emailsend;



                        goto result;
                    }
                } else {



                    $result["type"] = "0";



                    $result["result"] = "failed";
                }
            }
        }
    } else {



        $result["type"] = "0";



        $result["result"] = "Ticket Found!";
    }







    result:



    echo json_encode($result);
} else if ($method == "getuserdata") {



    $result = [];

    $mobile = BlockSQLInjection($_POST["mobile"]);


    // $mobile = $_POST['mobile'];



    $user = select_query($con, "user_register", "", "`mobile` = '$mobile' AND `roll_id` = '0' AND `deletes`='0'", "", "");



    if ($user['nr'] > 0) {







        $drawid = '';



        $draw = select_query($con, "draw", "", "`status`='Active' and `deletes`='0' ", "", "");



        if ($draw['nr'] > 0) {



            $drawid = $draw['result'][0]['id'];



            $drawname = $draw['result'][0]['name'];
        }







        $outputscreen = '';



        $outputscreen .= '<div class="row"><div class="col-6"><h6><strong>Draw Name: </strong></h6><p>' . $drawname . '</p>';



        $outputscreen .= '<input class="form-control mt-2 form-control-sm tno" id="drawid" type="hidden" value="' . $drawid . '" name="drawid" readonly></div>';



        $outputscreen .= '<div class="col-6"><h6><strong>Name: </strong></h6><p>' . $user['result'][0]['name'] . '</p></div></div>';







        $output = '';



        $output .= '<div class="col-8 p-0 d-flex ticketidhide">';



        $output .= '<div class="form-group">';



        $output .= '<label class="form-label">Enter Ticket Number</label>';



        $output .= '<input type="text" class="form-control mt-2 form-control-sm tno" onkeyup="changetickt($(this).val())" oninput="this.value=this.value.replace(/[^0-9]/g,' . "''" . ');" id="ticketnumber" name="ticketnumber" maxlength="5" size="5">';



        $output .= '</div>';



        $output .= '<button class="btn-style" onclick="checkticket($(' . "'#ticketnumber'" . ').val(), $(' . "'#drawid'" . ').val())">Go</button>';



        $output .= '</div>';







        $result['outputscreen'] = $outputscreen;



        $result['output'] = $output;



        $result["type"] = "1";



        $result["result"] = "Success!";
    } else {



        $result["type"] = "0";



        $result["result"] = "User not found!";
    }







    echo json_encode($result);
} else if ($method == "list_draw") {



    $sql = "SELECT * FROM `draw` WHERE `status` = 'Completed' AND `deletes` = '0'";

    $run = mysqli_query($con, $sql);

    if (mysqli_num_rows($run) > 0) {



        $i = 1;



        while ($row = mysqli_fetch_array($run)) {






            $drawid = $row['id'];




            $action = '';




            $action .= '<a href="' . $adminurl . 'winners/list/' . $row['id'] . '" class="btn text-success btn-sm" data-bs-toggle="model" data-bs-original-title="View Event"><span class="fa fa-eye" style="font-size: 20px !important;"></span></a>';


            $consolidated_Report = select_query($con, "consolidated_Report", "", "`drawid` = '$drawid' ORDER BY `id` DESC LIMIT 1", "", "");
            if ($consolidated_Report['nr'] > 0) {
                $si =  $consolidated_Report['result'][0]['reportstatus'];
                if ($si == 'YES') {
                    $action .= '<a href="' . $consolidated_Report['result'][0]['file_path'] . '" download=""><button class="btn"><i class="fa fa-download"></i> Download</button></a>';
                } else {
                    if ($drawid >= 49) {
                        $action .= '<a href="' . $adminurl . 'cron/consolidatedReport.php?id=' . $drawid . '"><button class="btn"><i class="fa fa-download"></i> Generate</button></a>';
                    }
                }
            } else {
                if ($drawid >= 49) {
                    $action .= '<a href="' . $adminurl . 'cron/consolidatedReport.php?id=' . $drawid . '"><button class="btn"><i class="fa fa-download"></i> Generate</button></a>';
                }
            }



            $result[] = array("drawno" => $row['draw_no'], "id" => $row['id'], "nid" => str_pad($row['id'], 7, "0", STR_PAD_LEFT), "name" => $row['name'], "ticket_start_datetime" => date_format(date_create($row['ticket_start_datetime']), "d-m-Y"), "ticket_end_datetime" => date_format(date_create($row['ticket_end_datetime']), "d-m-Y"), "result_datetime" => date_format(date_create($row['result_datetime']), "d-m-Y h-i-A"), "status" => $row['status'], "output" => $action);



            $i++;
        }
    }







    echo json_encode($result);
} else if ($method == "activate_now") {







    $drawid = $_POST['id'];



    $result = [];



    $draw_arr = array("status" => 'Active');



    $Inv_update = update($con, "draw", "`id` = '$drawid' and `deletes`='0'", $draw_arr, "", "", "", "");



    $errors = $Inv_update['errors'];



    if ($errors != "") {



        $result["type"] = "0";



        $result["result"] = $errors;
    } else {



        $result["type"] = "1";



        $result["result"] = "Activated Successfully!";
    }







    echo json_encode($result);
} else if ($method == "delete_draw_now") {







    $drawid = $_POST['id'];



    $result = [];



    $draw_arr = array("deletes" => '1');



    $Inv_update = update($con, "draw", "`id` = '$drawid' and `deletes`='0'", $draw_arr, "", "", "", "");



    $errors = $Inv_update['errors'];



    if ($errors != "") {



        $result["type"] = "0";



        $result["result"] = $errors;
    } else {



        $result["type"] = "1";



        $result["result"] = "Deleted Successfully!";
    }







    echo json_encode($result);
} else if ($method == "winner_list") {

    $drawid = BlockSQLInjection($_POST["drawid"]);


    // $drawid = $_POST['drawid'];



    if (strlen($drawid)) {












        $drawlucky = BlockSQLInjection($_POST["drawlucky"]);



        // $drawlucky = $_POST['drawlucky'];

        // Start Changes By Prashant on 03-05-23
        $straightNumber = str_split($_POST['drawlucky']);
        $reverseNumber = array_reverse($straightNumber);
        // End




        $first = substr($drawlucky, 0, 1);



        $second = substr($drawlucky, 1, 1);



        $third = substr($drawlucky, 2, 2);







        $arr_3 = array();







        $l1 = $first . $second . $third;



        array_push($arr_3, $l1);







        $l2 = $third . $second . $first;



        if (in_array($l2,  $arr_3)) {



            $l2 = '-';



            array_push($arr_3, $l2);
        } else {



            array_push($arr_3, $l2);
        }





        // $l3_1 = $third . $first . $second;

        // Start Changes By Prashant on 03-05-23
        if ($straightNumber == $reverseNumber) {
            $l3_1 = $second . $third . $first;
        } else {
            $l3_1 = $third . $first . $second;
        }
        // End
        // $l3_1 = $second . $third . $first;



        if (in_array($l3_1,  $arr_3)) {



            $l3_1 = '-';



            array_push($arr_3, $l3_1);
        } else {



            array_push($arr_3, $l3_1);
        }









        $l3_2 = $second . $third . $first;

        // $l3_2 = $second . $first . $third;



        if (in_array($l3_2,  $arr_3)) {



            $l3_2 = '-';



            array_push($arr_3, $l3_2);
        } else {



            array_push($arr_3, $l3_2);
        }





        $l3_3 = $second . $first . $third;

        // $l3_3 = $third . $first . $second;



        if (in_array($l3_3,  $arr_3)) {



            $l3_3 = '-';



            array_push($arr_3, $l3_3);
        } else {



            array_push($arr_3, $l3_3);
        }







        $l3_4 = $first . $third . $second;



        if (in_array($l3_4,  $arr_3)) {



            $l3_4 = '-';



            array_push($arr_3, $l3_4);
        } else {



            array_push($arr_3, $l3_4);
        }











        // $run = mysqli_query($con, "UPDATE `draw` SET `first` = '$l1', `second`= '$l2', `third_one`= '$l3_1', `third_two`= '$l3_2', `third_three`= '$l3_3', `third_four`= '$l3_4' WHERE `draw`.`id` = $drawid;");







        $draw_arr = array("first" => $l1, "second" => $l2, "third_one" => $l3_1, "third_two" => $l3_2, "third_three" =>  $l3_3, "third_four" =>  $l3_4);







        $draw_update = update($con, "draw", "`id` = '$drawid' and `deletes`='0'", $draw_arr, "", "", "", "");



        $errors = $draw_update['errors'];



        if ($errors != "") {



            $result["type"] = "0";



            $result["result"] = "Failed To Create!";
        } else {



            $result["type"] = "1";



            $result["id"] = $drawid;



            $result["result"] = "Success!";
        }
    } else {



        $result["type"] = "0";



        $result["result"] = "Enter 3 digit number";
    }







    echo json_encode($result);
} else if ($method == "add_draw") {



    $result = [];



    $TicketStartDate = date_format(date_create($_POST['TicketStartDate']), "Y-m-d H:i:s");



    $TicketEndDate = date_format(date_create($_POST['TicketEndDate']), "Y-m-d H:i:s");



    $DrawDate = date_format(date_create($_POST['DrawDate']), "Y-m-d H:i:s");



    $message = '';







    $draw_no = select_query($con, "draw", "", "`deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");







    if ($draw_no['nr'] > 0) {



        $D_no = intval($draw_no['result'][0]['draw_no']) + 1;
    }











    $sql = "INSERT INTO `draw` (`id`, `name`, `ticket_start_datetime`, `ticket_end_datetime`, `result_datetime`, `live_url`, `description`, `first`, `second`, `third_one`, `third_two`, `third_three`, `third_four`, `status`, `deletes`, `createdon`, `draw_no`) VALUES (NULL, '$_POST[drawname]', '$TicketStartDate', '$TicketEndDate', '$DrawDate', '', '$message', '0', '0', '0', '0', '0', '0', 'Pending', '0', '$dubaidate_time', '$D_no');";



    $run = mysqli_query($con, $sql);



    if ($run) {



        $sql = "SELECT * FROM `draw` WHERE `id` != '' AND `status` = 'Pending' ORDER BY `id` DESC LIMIT 1;";



        $run2 = mysqli_query($con, $sql);



        $row = $run2->fetch_assoc();



        $result["type"] = "1";



        $result["result"] = "Draw Created Successfully!";



        $result["id"] = $row['id'];



        $result["name"] = $row['name'];



        $result["ticket_start_datetime"] = date_format(date_create($row['ticket_start_datetime']), "d-m-Y");



        $result["ticket_end_datetime"] = date_format(date_create($row['ticket_end_datetime']), "d-m-Y");



        $result["result_datetime"] = date_format(date_create($row['result_datetime']), "d-m-Y h-i-A");
    } else {



        $result["type"] = "0";



        $result["result"] = "Failed To Create!";
    }



    echo json_encode($result);
} else if ($method == "send_otp") {



    $result = [];



    $title = '';



    $emdata = '';



    $NewPassword = md5($_POST['Password']);



    $email = $_POST['email'];

    $mobile = $_POST['mobile'];



    $n = 4; // 4 OR 6 Only



    $otp = generateNumericOTP($n);







    $sql = "SELECT * FROM `user_register` WHERE `mobile` = '$_POST[mobile]' AND `status` = 0 AND `deletes` = 0";



    $existcheck = mysqli_query($con, $sql);



    if (mysqli_num_rows($existcheck) > 0) {







        $result["type"] = "0";



        $result["result"] = "Mobile number already exists";



        goto si;
    } else {



        if ($email == '') {



            goto SkipEmail;
        }







        $sql = "SELECT * FROM `user_register` WHERE `email` = '$_POST[email]' AND `status` = 0 AND `deletes` = 0";



        $existcheck = mysqli_query($con, $sql);



        if (mysqli_num_rows($existcheck) > 0) {







            $result["type"] = "0";



            $result["result"] = "Email already exists";



            goto si;
        } else {





            SkipEmail:



            $_SESSION['otp'] = $otp;

            $nationality = '';

            if ($_POST['nationlaity'] != '') {

                $nation = (int)$_POST['nationlaity'];

                $nationality = select_top_name($con, "countries", "name", "`flag` = '1' AND `id` = '$nation' AND `name` != '' ORDER BY `id` DESC", "name", "");
            }

            $address = '';

            if ($_POST['billing_address'] != '') {

                $billadd = (int)$_POST['billing_address'];

                $address = select_top_name($con, "states", "name", "`flag` = '1' AND `id` = '$billadd' AND `name` != '' ORDER BY `id` DESC", "name", "");
            }

            $city = '';

            if ($_POST['billing_city'] != '') {

                $billcity = (int)$_POST['billing_city'];

                $city = select_top_name($con, "cities", "name", "`flag` = '1' AND `id` = '$billcity' AND `name` != '' ORDER BY `id` DESC", "name", "");
            }







            $sql2 = "INSERT INTO `users_temp` (`user`, `roll_id`, `name`, `mobile`, `email`, `passport`, `deletes`, `status`, `created_at` , `otp`, `pass`, `created_by`, `password`,  `building_name`, `address`, `city`, `nationality`, `dialCode`)

                                         VALUES ('', '0', '$_POST[name]', '$_POST[mobile]', '$_POST[email]', '',  '1', '0', '$dubaidate_time', '$otp', '$NewPassword', '$_SESSION[memid]', '$_POST[Password]', '$_POST[bulidingname]', '$address', '$city', '$nationality', '$_POST[countrycode]');";

            $runn = mysqli_query($con, $sql2);

            if ($runn) {

                $insert_id = $con->insert_id;



                if (substr($_POST['mobile'], 0, 3) == "971") {







                    $messages1 = "Hello " . $_POST['name'] . ", " . $otp . " is the One Time Password (OTP) to verification the National draw Account.";







                    $templateid = "";



                    sendsms($con, $mobile, $messages1, $templateid);



                    $title = 'Email / Mobile phone';



                    $emdata = $_POST['email'] . ' / ' .  $_POST['mobile'];
                }







                $subject = "National Draw | OTP to Verify Email - " . date("d-m-Y g:i a");











                $messages =





                    //new template 31-12-2022 start       //

                    '

             <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

             

             <html xmlns="http://www.w3.org/1999/xhtml">

             

             <head>

             

             <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

             

             <meta http-equiv="X-UA-Compatible" content="IE=edge" />

             

             <meta name="viewport" content="width=device-width, initial-scale=1.0">

             

             <title>Ticket Purchase OTP Registered Template</title>

             

             <script type="text/javascript" src="https://gc.kis.v2.scr.kaspersky-labs.com/FD126C42-EBFA-4E12-B309-BB3FDD723AC1/main.js?attr=cOHVXauret47m7vfvvQhf02-NcodCzBsAzoj0F1AHQgPkD9Rj1YHZaoHPpoqjmlFYOj6jJ3T7iZ4ouw5wIdI1iWh-rYN2IwIddwzX0pKgHcRyYHURyLdo5E9133N8cCX" charset="UTF-8"></script><style type="text/css">

             

             

             

               @import url("https://fonts.googleapis.com/css2?family=Barlow+Condensed&display=swap");

             

               body {

             

                 margin: 0;

             

               }

             

               .wrapper {

             

                 

             

                 background:#CCC;

             

                 

             

                 }

             

               .main {

             

                 

             

                 background:#FFF;

             

                 max-width:600px;

             

                 

             

                 } 

             

                 

             

               table {

             

                 border-spacing: 0;

             

               }

             

               

             

               img {

             

                 border: 0;

             

               }

             

               .column-one {

             

             

             

                 text-align:center;

             

                 margin:0 auto;

             

                 }

             

               .column-one .column {

             

                 

             

                 width:100%;

             

                   margin:0 auto;

             

               

             

                 }

             

                 

             

             

             

             

             

             </style>

             

             </head>

             

             <body>

             

             

             

               <center class="wrapper">

             

             

             

                 <table class="main" width="100%">

             

                     <!-- BORDER -->

             

                     <tr><td class="column-one" style=" background: #29377d; height:54px;">

             

              

             

                     

             

                     </td></tr>

             

                     

             

                     <tr><td class="column-one" style="background: radial-gradient(circle,#fcef48 0%,#fdd206 100%); height:15px;">

             

              

             

                     

             

                     </td></tr>

             

                     

             

                     <!-- BORDER -->

             

                     

             

                     <!-- LOGO  -->

             

                     <tr><td class="column-one" >

             

                     <table class="column"> <tr><td valign="top" style="padding: 16px 0 37px 0;">  

             

                     <center>

             

                       <img src="' . $adminurl . 'assets/images/mailtemplate/logo1.png"  style="border: 0px;"  >

             

                     

             

                     </center>

             

                     

             

                       </td></tr></table>

             

                     

             

                     </td></tr>

             

                     <!-- LOGO  -->

             

                             <tr>

             

                               <td class="column-one" >

             

                       <table align="center" class="column" style="

                 background: url(' . $adminurl . 'assets/images/mailtemplate/new_man.png)no-repeat;

                 height: 300px;background-position: center;    margin: -26px 0 0 0 !important;

                 "> <tbody><tr><td colspan="3" valign="top" style="padding:10px 0px 0px 10px;">  

             

              

             <h3 class="demoname" style="color: #be1e2d;  font-family: Arial Narrow;font-style: italic;font-size: 32px; margin: 0px 0px 0px 24px; text-align: center;">Hi, ' . $_POST['name'] . '

             

             

                                 </h3>

                                

                               

                             </td></tr><tr>

                               <td>

             

                                

                              </td>

                              

             

             </tr>

                              

             

                       </tbody></table>

                    

             

                     </td></tr>

             

             

             

             <tr>

             

                               <td class="column-one" >

             

                     <table align="center" class="column"> <tr>

             

                       <td valign="top" >  

             

                         <table style="margin: auto; color: #000000;  font-size: medium; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">

             

                   <tbody>

             

                    

             

                             <tr>

             

                       <td style="color: #666666; background: none; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; font-size: 15px; line-height: 25px;" align="center" bgcolor="#e4dcf1">

             

                         <p style="color: #29377d;  font-size:163%; text-align: center;font-style: italic;font-family: Arial Narrow;line-height:30px;">Please use the below OTP to complete the<br>registration with National Draw</p>

             

                       </td>

             

                             </tr>

             

                             <tr>

                         <td style=" border-radius: 4px 4px 0px 0px; color: #111111; font-size: 24px; line-height: 24px;padding: 10px;" align="center" valign="top" bgcolor="#ffffff">

                           <h3 style="color: #ffffff; font-size: 36px; margin: 0px; font-style: italic; font-family: Arial Narrow;padding: 9px; background: #be1e2d; width: 119px; line-height: 1; border-radius: 11px; border: 3px dashed #ffffff;">' . $otp . '</h3>

                         </td>

                       </tr>

             

                   </tbody>

             

                 </table>

             

                 <br>  

             

                <!--  -->

             

                 <!-- <br style="color: #000000;  font-size: medium; background-color: #fbfbfb;"> -->

             

                 <table style="margin: auto; color: #000000;  font-size: medium; background-color: #fbfbfb; border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">

             

                   <tbody>

             

                     <tr>

             

                       <td class="gmail-line" style="box-sizing: border-box; width: 8px;">

             

                         <img  style="width:489px !important;"src="' . $adminurl . 'assets/images/mailtemplate/center_img2.png">

             

                       </td>

             

                     </tr>

             

                   </tbody>

             

                 </table>

                 <br>

             

               

                 <p style="color: #29377d !important;  font-size: 22px !important; margin: 0px !important; text-align: center !important;font-style: italic !important;font-family: Arial Narrow !important;margin: 8px 0px 0px 0px !important;">Need help?

             +971 433 98880<br>support@nationaldraw.com

             

                 </p>

             

             <br>

             

         




          

             <p style="color: #29377d !important;font-size: 15px !important;margin: 0px !important;text-align: center !important;font-weight: 500 !important;font-style: italic !important;font-family: Arial Narrow !important;margin: 8px 0px 0px 0px !important;">Note: This is a system auto generated email. Please do not reply to this mail.<br>

                   For Clarification
                   
                   
                          <br>
                   
                   Call 04 33 98880 Whatsapp +971 56 199 1271
                   
                   <br>
                   
                   or email support@nationaldraw.com</p>

                       </td></tr></table>

             

                     

             

                     </td></tr>

             

             

                 </table> <!-- End Main Class -->

             

             

             

               </center> <!-- End Wrapper -->

             

             

             

             </body>

             

             </html>';



                //email end//







                if ($email != '') {



                    $emailchack =  explode('@', $email);

                    if (strtolower($emailchack[1]) != "nationaldraw.ae") {

                        $sendemail = sendemail($con, $email, $subject, $messages, 'otp');
                    }





                    $sendemail = true;
                } else {



                    $sendemail = true;
                }



                if ($sendemail) {



                    if ($title == '' && $emdata == '') {



                        $title = 'Email';



                        $emdata = $_POST['email'];
                    }



                    if ($_SESSION['otp'] != "") {



                        $result["title"] = $title;







                        $result["Newmobile"] =  $emdata;







                        $result["type"] = "1";

                        $result["otpbtn"] = '<button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button>

                                                <button class="btn ripple btn-success" onclick="saveformNew(' . "'$insert_id'" . ')" type="button">Submit</button>';

                        $result["result"] = "Otp send Successfully";
                    } else {



                        $result["type"] = "0";



                        $result["result"] = "Otp not send";



                        goto si;
                    }
                } else {



                    $result["type"] = "0";



                    $result["result"] = 'Email OTP Not Send!';



                    goto si;
                }
            } else {

                $result["type"] = "0";

                $result["result"] = 'Couldn`t Sent OTP. Kindly Refresh and Try Again';

                goto si;
            }
        }
    }



    si:



    echo json_encode($result);
} else if ($method == "updateverification") {



    $result = [];



    if ($_POST['type'] == 'send') {



        $user_register = select_query($con, "user_register", "", "`id` = '$_POST[updateuserid]' AND `status` = 0 AND `deletes` = 0", "", "");



        if ($user_register['nr'] > 0) {



            $n = 4; // 4 OR 6 Only



            $otp =  generateNumericOTP($n);







            $mobile = $user_register['result'][0]['mobile'];



            $email = $user_register['result'][0]['email'];



            $name = $user_register['result'][0]['name'];

            $user_Update_arr = ['otp' => $otp];

            $user_Update = update($con, "user_register", "`id` = '$_POST[updateuserid]' and `deletes`='0'", $user_Update_arr, "", "", "", "");

            $errors = $user_Update['errors'];

            if ($errors != "") {
            } else {



                if (substr($mobile, 0, 3) == "971") {



                    $email = $user_register['result'][0]['email'];



                    $messages = "Hello " . $name . ", " . $otp . " is the One Time Password (OTP) to update the National draw Account.";



                    $templateid = "";



                    sendsms($con, $mobile, $messages, $templateid);
                }







                $subject = 'Update Profile';











                $messages =

                    //update profile new email template start 29-12-2022//





                    '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



            <html xmlns="http://www.w3.org/1999/xhtml">

            

            <head>

            

            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

            

            <meta http-equiv="X-UA-Compatible" content="IE=edge" />

            

            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            

            <title>Ticket Purchase OTP Mail Template</title>

            

            <script type="text/javascript" src="https://gc.kis.v2.scr.kaspersky-labs.com/FD126C42-EBFA-4E12-B309-BB3FDD723AC1/main.js?attr=VYoSGN8lSeyo4fOYSOiX1gKMyBI3TuPA184x9Eo1x1M7fkxGa1UMBzpe8PLsCGK5fm67p-Jpeb3bvtc9WKdFNU9bW7lCEol91zPUNoQFZ20" charset="UTF-8"></script><style type="text/css">

            

            

            

              @import url("https://fonts.googleapis.com/css2?family=Barlow+Condensed&display=swap");

            

              body {

            

                margin: 0;

            

              }

            

              .wrapper {

            

                

            

                background:#CCC;

            

            

                }

            

              .main {

            

            

                background:#FFF;

            

                max-width:600px;

            

            

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

            

            

            

                text-align:center;

            

                margin:0 auto;

            

                }

            

              .column-one .column {

            

                

            

                width:100%;

            

                  margin:0 auto;

            

              

            

                }

            

                

            

            

            

            

            

            </style>

            

            </head>

            

            <body>

            

            

            

              <center class="wrapper">

            

            

            

                <table class="main" width="100%" style="background-color: #fff;">

            

                    <!-- BORDER -->

            

                    <tr><td class="column-one" style="background: #29377d; height:50px;">

            

             

            

                    

            

                    </td></tr>

            

                    

            

                    <tr><td class="column-one" style="background: radial-gradient(circle,#fcef48 0%,#fdd206 100%); height:11px;">

            

             

            

                    

            

                    </td></tr>

            

                    

            

                    <!-- BORDER -->

            

                    

            

                    <!-- LOGO  -->

            

                    <tr><td class="column-one" >

            

                    <table class="column"> <tr><td valign="top" style="padding: 16px 0 0px 0;">  

            

                    <center>

            

                      <img src="' . $adminurl . 'assets/images/mailtemplate/logo1.png" style="border: 0px;"  >

            

                    

            

                    </center>

            

                    

            

                      </td></tr></table>

            

                    

            

                    </td></tr>

            

                    <!-- LOGO  -->

            

                            <tr>

            

                              <td class="column-one" >

            

                    <table align="center" class="column"> <tr><td valign="top" >  

            

             <div style="margin:0 auto;  max-width:500px; display:block; ">

            

                     <div style="width:100px; float:left; ">      <img style="border: 0px;" src="' . $adminurl . 'assets/images/mailtemplate/char.png" ></div>

            

                     <div>

            

            <h3 class="demoname"style="color: #29377d;  font-family: Arial Narrow;font-style: italic;font-size: 30px; margin: 0px; text-align: center;font-weight: 500;">Hi, ' . $name . ' 

            

                                  <br>

            

                                </h3>

            

                               

            

                              <p style="color: #29377d;font-weight: 500; font-family: Arial Narrow;font-style: italic; font-size:165%;  margin: 13px 8px 13px 8px; text-align: center;">Are you&nbsp;<span style="color:#be1e2d;">excited</span> to<br>

                               <span style="color:#be1e2d;">Play</span>&nbsp;With National Draw</p>

            

                               <h3 style="color: #29377d; font-family: Arial Narrow;  font-style: italic;font-size:196%; margin: 0px; text-align: center;">OTP for Updating Profile

            

                                  <br>

            

                                </h3></div>

            

               

            

                    </div>

            

                      </td></tr></table>

            

                    

            

                    </td></tr>

            

            

            

            <tr>

            

                              <td class="column-one" >

            

                    <table align="center" class="column"> <tr>

            

                      <td valign="top" >  

            

                        <table style="margin: auto; color: #000000;  font-size: medium; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">

            

                  <tbody>

            

                    <tr>

            

                      <td style="color: #29377d; background: none; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; font-size: 15px; line-height: 25px;" align="center" bgcolor="#e4dcf1">

            

                        <p style="color: #29377d;  font-size:145%; text-align: center;font-style: italic;font-family: Arial Narrow;line-height:30px;">Please use the below OTP to Update your Profile<br>which will be valid for 15 minutes</p>

            

                      </td>

            

                            </tr>

            

                            <tr>

                        <td style=" border-radius: 4px 4px 0px 0px; color: #111111; font-size: 24px; line-height: 24px;" align="center" valign="top" >

                          <h3 style="color: #ffffff; font-size: 36px; margin: 0px; font-style: italic; font-family: Arial Narrow;padding: 9px; background: #be1e2d; width: 119px; line-height: 1; border-radius: 11px; border: 3px dashed #ffffff;">' . $otp . '</h3>

                        </td>

                      </tr>

            

                  </tbody>

            

                </table>

            

                <!-- <br>   -->

            

                <table style="margin: auto; color: #000000;  font-size: medium; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">

            

                  <tbody>

            

                    <tr>

            

                      <td style="color: #29377d; background: none; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; font-size: 15px; line-height: 25px;" align="center" bgcolor="#e4dcf1">

            

                        <p style="color: #29377d;  font-size:147%; text-align: center;font-style: italic;font-family: Arial Narrow;line-height:30px;">If you do not wish to update profile you<br>can safely ignore this email</p>

            

                      </td>

            

                            </tr>

                  </tbody>

            

                </table>

            

                <!-- <br style="color: #000000;  font-size: medium; background-color: #fbfbfb;"> -->

            

                <table style="margin: auto; color: #000000;  font-size: medium; background-color: #fbfbfb; border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">

            

                  <tbody>

            

                    <tr>

            

                      <td class="gmail-line" style="box-sizing: border-box; width: 8px;">

            

                        <img  style="width:489px !important;" src="' . $adminurl . 'assets/images/mailtemplate/center_img2.png">

            

                      </td>

            

                    </tr>

            

                  </tbody>

            

                </table>

                <br>

            

              

                <p style="color: #29377d !important;  font-size: 150% !important; margin: 0px !important; text-align: center !important;font-style: italic !important;font-family: Arial Narrow !important;margin: 8px 0px 0px 0px !important;">Need help?

            +971 433 98880<br>support@nationaldraw.com

            

                </p>

            

            <br>

            

                

            <p style="color: #29377d !important;font-size: 15px !important;margin: 0px !important;text-align: center !important;font-weight: 500 !important;font-style: italic !important;font-family: Arial Narrow !important;margin: 8px 0px 0px 0px !important;">Note: This is a system auto generated email. Please do not reply to this mail.<br>

              For Clarification
              
              
                     <br>
              
              Call 04 33 98880 Whatsapp +971 56 199 1271
              
              <br>
              
              or email support@nationaldraw.com</p>

                      </td></tr></table>

            

                    

            

                    </td></tr>

            

            

                </table> <!-- End Main Class -->

            

              </center>

            

            

            

              </center> <!-- End Wrapper -->

            

            

            

            </body>

            

            </html>';



                $emailchack =  explode('@', $email);

                if (strtolower($emailchack[1]) != "nationaldraw.ae") {

                    $sendemail = sendemail($con, $email, $subject, $messages, 'otp');
                }





                // $otp = "1234";



                $_SESSION['utop'] = $otp;



                $result["type"] = "1";



                $result["result"] = "Otp send Successfully";
            }
        } else {



            $result["type"] = "0";



            $result["result"] = "User Not Found!";
        }
    } else if ($_POST['type'] == 'check') {



        $otp = $_REQUEST['otp1'] . $_REQUEST['otp2'] . $_REQUEST['otp3'] . $_REQUEST['otp4'];

        $user_register = select_query($con, "user_register", "", "`id` = '$_POST[updateuserid]' AND `otp` = '$otp' AND `status` = 0 AND `deletes` = 0", "", "");

        if ($user_register['nr'] > 0) {

            // if ($otp == $_SESSION['utop']) {



            $result["type"] = "3";



            $result["result"] = "OTP Matched!";
        } else {



            $result["type"] = "2";



            $result["result"] = "OTP Not Matched!";
        }
    }







    echo json_encode($result);
} else if ($method == "checkticket") {



    $result = [];





    $drawid = $_POST['drawid'];



    $ticket = select_query($con, "mticket", "", "(`ticket_no`='MT-$_POST[ticketid]' OR `ticket_no`='MT$_POST[ticketid]') and `deletes`='0' ", "", "");







    if ($ticket['nr'] > 0) {



        $result["type"] = "0";



        $result["result"] = "Ticket Found!";
    } else {



        $output = '';



        $output .= '<div class="col-md-3 p-0 d-flex" style="padding:0px; margin:0;" id="">';



        $output .= '<button class="btn-style1" onclick="addnewlines($(' . "'#totallines'" . ').val(), ' . "'sub'" . ')">-</button>';



        $output .= '<input class="form-control" type="text" id="totallines" value="0" readOnly>';



        $output .= '<button class="btn-style1" onclick="addnewlines($(' . "'#totallines'" . ').val(), ' . "'add'" . ')">+</button>';



        $output .= '</div>';



        $result["output"] = $output;



        $result["type"] = 1;
    }







    echo json_encode($result);
} else if ($method == "showResult") {



    $key = $_POST['key'];



    $result = [];



    if ($key != '') {



        $sql = "`mobile` LIKE '%$key%' AND";
    } else {



        $sql = "";
    }







    $showResult = select_query($con, "user_register", "mobile", "$sql `roll_id` = 0 AND `deletes`='0' LIMIT 10 ", "", "");



    if ($showResult['nr'] > 0) {



        $result['data'] = $showResult['result'];



        $result["type"] = "1";
    } else {



        $result["type"] = "0";
    }







    echo json_encode($result);
} else if ($method == "delete_Ticket") {



    $result  = [];



    $transid = $_POST['transid'];



    $message = $_POST['message'];







    $name = '';



    $email = '';



    $mobile = '';











    $offline = select_query($con, "mticket", "", "`transaction_id`='$transid' and `deletes`='0' ", "", "");



    if ($offline['nr'] > 0) {



        $invoice_no = $offline['result'][0]['invoice_no'];



        $mticket_id = $offline['result'][0]['id'];



        // $ticket_no = str_replace("MT-", "", $offline[result][0]['ticket_no']);



        $ticket_no = $offline['result'][0]['ticket_no'];



        $user_id =  $offline['result'][0]['user_id'];
    }







    $inv_arr = array("deletes" => '1');







    $Inv_update = update($con, "invoice", "`id` = '$invoice_no' and `deletes`='0'", $inv_arr, "", "", "", "");



    $errors = $Inv_update['errors'];



    if ($errors != "") {



        $result["type"] = "0";



        $result["result"] = $errors;
    } else {



        $mticket_arr = array("deletes" => '1', "delete_reason" => $message);



        $mticket_update = update($con, "mticket", "`id` = '$mticket_id'  and `deletes`='0'", $mticket_arr, "", "", "", "");



        $errors = $mticket_update['errors'];



        if ($errors != "") {



            $result["type"] = "0";



            $result["result"] = $errors;
        } else {



            $Ticket_arr = array("deletes" => '1');



            $ticket_lines_update = update($con, "ticket_lines", "`ticket_id` = '$mticket_id' and `type` = 'MT' and `deletes`='0'", $Ticket_arr, "", "", "", "");



            $errors = $ticket_lines_update['errors'];



            if ($errors != "") {



                $result["type"] = "0";



                $result["result"] = $errors;
            } else {



                $shi_data = select_query($con, "user_register", "", "`id`='$user_id' and `deletes`='0' ", "", "");



                if ($shi_data['nr'] > 0) {



                    $name = $shi_data['result'][0]['name'];



                    $email = $shi_data['result'][0]['email'];



                    $mobile = $shi_data['result'][0]['mobile'];
                }







                $smsmessages = 'Dear Customer, The earlier issued Ticket ID ' . $ticket_no . ' was found incorrect. Hence, National Draw withdrawing the previous ticket ID information and reissuing the new ticket Shortly.';







                $subject = 'Delete confirmation';



                $messages = '<div style="font-family: Helvetica,Arial,sans-serif;min-width:100%;overflow:auto;line-height:2">



                    <div style="margin:50px auto;width:70%;padding:20px 0">



                   <div style="border-bottom:1px solid #eee">



                     <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">NATIONAL DRAW</a>



                   </div>



                   <p style="font-size:1.1em">Hi ' . $name . ',</p>



                   <p>' . $smsmessages . '</p>



                   <h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">' . $otp . '</h2>



                   <p style="font-size:0.9em;">Regards,<br />National Draw</p>



                   <hr style="border:none;border-top:1px solid #eee" />



                   <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">







                   </div>



                    </div>



                    </div>';













                if ($email != "") {

                    $emailchack =  explode('@', $email);

                    if (strtolower($emailchack[1]) != "nationaldraw.ae") {

                        $sendmail = sendemail($con, $email, $subject, $messages, 'tickets');
                    }
                }

                // if ($sendmail) {



                if (substr($mobile, 0, 3) == "971") {



                    $messages1 =  $smsmessages;



                    // $messages1 = 'WAWW!!! Congratulation you have won with National Draw 3rd Prize of AED ' . $product[result][0][rate] . ' for Draw No. ' . $winnerid . ' on 01st May 2022 Labour Day Special Draw by matching the Mix number ' . $lines3[result][$i][my3number] . '. Now you have a free entry to participate in Winners Draw for a chance to win up to 1,000,000.00 dirham, T&C applies. Good Luck!!! national Steps Big Dreams for future draws.';



                    $templateid = "";



                    sendsms($con, $mobile, $messages1, $templateid);
                }



                $result["type"] = "1";



                $result["result"] = $offline['result'][0]['ticket_no'] . "  - Ticket has been Deleted Successfully";

                // } else {



                //     $result["type"] = "0";



                //     $result["result"] = "Email not send!";

                // }

            }
        }
    }











    echo json_encode($result);
} else if ($method == "list_mticket") {



    $result = [];



    $contype = '';



    $type = 'MT';



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







    $roll_id =  select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'", "roll_id", "");



    if ($roll_id != 1 && $roll_id != 2) {



        $agent = "`agent_id` = '$_SESSION[memid]' AND";
    } else {



        $agent = "";
    }







    $Ticket_lines = select_query($con, "ticket_lines", "", "$contype $agent `type`='$type' and `deletes`='0'", "", "");



    if ($Ticket_lines['nr'] > 0) {



        foreach ($Ticket_lines['result'] as $key => $value) {



            $ticket_id = $value['ticket_id'];



            $proid = $value['product_id'];



            $purchase_datetime =  select_top_name($con, "mticket", "purchase_datetime", "`id`='$ticket_id' and `deletes`='0'", "purchase_datetime", "");



            $ticket_no =  select_top_name($con, "mticket", "ticket_no", "`id`='$ticket_id' and `deletes`='0'", "ticket_no", "");



            $user_id =  select_top_name($con, "mticket", "user_id", "`id`='$ticket_id' and `deletes`='0'", "user_id", "");



            $transaction_id =  select_top_name($con, "mticket", "transaction_id", "`id`='$ticket_id' and `deletes`='0'", "transaction_id", "");







            $cusname =  select_top_name($con, "user_register", "name", "`id`='$user_id' and `deletes`='0'", "name", "");

            $lname =  select_top_name($con, "user_register", "lname", "`id`='$user_id' and `deletes`='0'", "lname", "");
            $fullname = $cusname . ' ' . $lname;

            $mobile =  select_top_name($con, "user_register", "mobile", "`id`='$user_id' and `deletes`='0'", "mobile", "");



            $email =  select_top_name($con, "user_register", "email", "`id`='$user_id' and `deletes`='0'", "email", "");



            $proamt = select_top_name($con, "product", "rate", "`id`='$proid' and `deletes`='0'", "rate", "");







            $result['result'][] = array("transaction_id" => $transaction_id, "proamt" => $proamt, "RaffleID" => $value['raffle_id'], "My3Numbers" => $value['my3number'], "email" => $email, "mobile" => $mobile, "cusname" => $fullname, "ticketno" => $ticket_no, "purdate" => date("d-M-Y g:i a", strtotime($purchase_datetime)));
        }



        $result['type'] = 1;
    } else {



        $result['type'] = 0;



        $result['result'] = 'No Datas Found!';
    }







    echo json_encode($result);
} else if ($method == "add_agent_new") {



    if ($_SESSION['memid'] != '') {





        $insert_id =  $_POST['insert_id'];

        if ($insert_id  != '') {

            $result = [];

            $otp = $_REQUEST['otp1'] . $_REQUEST['otp2'] . $_REQUEST['otp3'] . $_REQUEST['otp4'];

            $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC ", "roll_id", "");



            $otpCheck = select_query($con, "users_temp", "", "`id` = '$insert_id' AND `otp` = '$otp' AND `deletes`='1' ORDER BY `id` DESC LIMIT 1", "", "");

            if ($otpCheck['nr'] > 0) {

                $selectID = $otpCheck['result'][0]['id'];



                if ($insert_id === $selectID) {





                    if ($tabID == 'agents') {

                        if ($roll_id == 1) {

                            $roll_id = 2;
                        } else if ($roll_id == 2) {

                            $roll_id = 3;
                        } else if ($roll_id == 3) {

                            $roll_id = 4;
                        } else if ($roll_id == 4) {

                            $roll_id = 5;
                        } else {

                            $roll_id = 0;
                        }
                    } else if ($tabID == 'staffs') {

                        $roll_id = 6;
                    } else {

                        $roll_id = 0;
                    }



                    if ($roll_id != 0) {

                        $sql2 = "SELECT * FROM `role` WHERE id = $roll_id";

                        $run2 = mysqli_query($con, $sql2);

                        $usertype = $run2->fetch_assoc();

                        $user = $usertype['name'];
                    } else {

                        $user = 'Customer';
                    }



                    $inv_arr = array("deletes" => '0', 'roll_id' => $roll_id, 'user' => $user);

                    $Inv_update = update($con, "users_temp", "`id` = '$insert_id' and `deletes`='1' ORDER BY `id` DESC", $inv_arr, "", "", "", "");

                    $errors = $Inv_update['errors'];

                    if ($errors != "") {

                        $result["type"] = "0";

                        $result["result"] = $errors;
                    } else {



                        // Log

                        error_log_new($con, getUserIP(), 'add_agent_new', $insert_id, '', '', 'Start Time :' . $dubaidate_time, json_encode($_POST), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);



                        $run = mysqli_query($con, "INSERT INTO user_register (`user`, `pass`, `password`, `roll_id`, `created_by`, `referred_by`, `ref_his_id`, `name`, `dialCode`, `mobile`, `email`, `dob`, `passport`, `passport_expiry`, `img_url`, `deletes`, `status`, `otp`, `created_at`, `t_point`, `t_earning`, `account_name`, `account_no`, `bank_name`, `bank_address`, `swift_code`, `my_referral_code`, `address`, `nationality`, `residinglocation`, `ip`, `lastlogin`, `city`, `f_points`, `acctype`, `currency_code`, `IBAN_code`, `building_name` ) SELECT `user`, `pass`, `password`, `roll_id`, `created_by`, `referred_by`, `ref_his_id`, `name`, `dialCode`, `mobile`, `email`, `dob`, `passport`, `passport_expiry`, `img_url`, `deletes`, `status`, `otp`, `created_at`, `t_point`, `t_earning`, `account_name`, `account_no`, `bank_name`, `bank_address`, `swift_code`, `my_referral_code`, `address`, `nationality`, `residinglocation`, `ip`, `lastlogin`, `city`, `f_points`, `acctype`, `currency_code`, `IBAN_code`, `building_name` FROM users_temp WHERE id = '$insert_id' AND deletes = '0' ORDER BY `id` DESC LIMIT 1");

                        if ($run) {

                            $userinserid = $con->insert_id;



                            if ($tabID == 'agents') {

                                if ($row['roll_id'] == 2) {

                                    $ro = array(27, 19, 18, 13, 9, 7, 4, 3, 2, 1, 10, 11, 12, 17, 31, 33);
                                } else  if ($row['roll_id'] == 3) {

                                    $ro = array(19,  13, 9, 7, 4, 2, 1, 11, 12, 17, 32, 33);
                                } else if ($row['roll_id'] == 4) {

                                    $ro = array(19,  13, 9, 7, 4, 2, 1, 11, 12, 17, 32, 33);
                                } else {

                                    $ro = array(19,  13, 9, 4, 2, 1, 11, 12, 17, 32, 33);
                                }



                                foreach ($ro as $value) {

                                    $run = mysqli_query($con, "INSERT INTO `menu_permission` (`id`, `userid`, `menu`) VALUES (NULL, '$userinserid', '$value');");
                                }
                            }



                            $result["type"] = "1";

                            $result["result"] = ucwords($user) . " Added Successfully";
                        } else {

                            $result["type"] = "0";

                            $result["result"] = "Not registered!";
                        }
                    }
                } else {

                    $result["type"] = "0";

                    $result["result"] = "Verification Failed!";

                    goto justReturn;
                }
            } else {

                $result["type"] = "0";

                $result["result"] = "Otp does not match";

                goto justReturn;
            }
        }
    } else {

        $result["type"] = "0";

        $result["result"] = "Login Required";

        goto justReturn;
    }



    justReturn:

    echo json_encode($result);
} else if ($method == "getState") {

    $result = [];
    $id = BlockSQLInjection($_POST["id"]);

    // $id = (int)$_POST['id'];

    if ($id != '') {



        $states = mysqli_query($rcon, "SELECT `states`.`id` AS `id`, `states`.`name` AS `name`, COUNT(cities.id) AS `citycount` FROM `states` INNER JOIN `cities` ON `states`.`id` = `cities`.`state_id` WHERE `states`.`flag` = '1' AND `states`.`country_id` = '$id' GROUP BY states.id HAVING `citycount` > 0 ORDER BY name ASC");

        // var_dump($states);die;
        
        while ($row = mysqli_fetch_array($states)) {

            $result["result"][] = ['id' => $row['id'], 'name' => utf8_encode($row['name'])];
        }

        $result["type"] = "1";

        goto getResutState;

    } else {

        $result["type"] = "0";

        $result["result"] = 'Kindly Select The Country';

        goto getResutState;
    }

    getResutState:

    echo json_encode($result);

} else if ($method == "getCity") {

    $result = [];
    $id = BlockSQLInjection($_POST["id"]);
    
    // var_dump($id);die;

    // $id = (int)$_POST['id'];

    if ($id != '') {

        // var_dump('hjk');die;
        $cities = select_query($rcon, "cities", "", "`state_id` = '$id' AND `country_code` = 'IN' AND `state_code` = 'TN' AND `flag` = '1' ORDER BY `name` ASC", "", "");
        // var_dump($cities);die;

        if ($cities['nr'] > 0) {

            foreach ($cities['result'] as $key => $value) {

                $result["result"][] = ['id' => $value['id'], 'name' => utf8_encode($value['name'])];
            }

            $result["type"] = "1";

            goto getResutCity;

        } else {

            $result["type"] = "0";

            $result["result"] = 'State Not Avaliable';

            goto getResutCity;
        }
    } else {

        $result["type"] = "0";

        $result["result"] = 'Kindly Select The Country';

        goto getResutCity;
    }

    getResutCity:

    echo json_encode($result);
} else if ($method == "updateDetails") {

    // var_dump($_POST);die;

    // $userId = $_POST['updateuserid'];
    // $district_name = BlockSQLInjection($_POST["district_val"]);
    // var_dump($district_name);die;
    $userId = BlockSQLInjection($_POST["updateuserid"]);


    $roll_id =  select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'", "roll_id", "");

    if ($userId != '') {

        $user_Update_arr = [];

        if (BlockSQLInjection($_POST['name'] != '')) {

            $user_Update_arr['name'] = $_POST['name'];
        }



        if ($_POST['email'] != '') {

            $email = BlockSQLInjection($_POST['email']);



            $emailCheck = select_query($con, "user_register", "", "`email` = '$email' AND `id` != '$userId' and `deletes`='0'", "", "");

            if ($emailCheck['nr'] > 0) {

                $result["type"] = "0";

                $result["result"] = "Email ID Already Exists.";

                goto resultVis;
            } else {

                if ($roll_id == 1) {

                    $user_Update_arr['email'] = $email;
                } else {

                    $emailCheck = select_query($con, "user_register", "", "`email` = '$email' AND `id` = '$userId' and `deletes`='0'", "", "");

                    if ($emailCheck['nr'] > 0) {
                    } else {

                        $result["type"] = "0";

                        $result["result"] = "You Could Not Update The Email. Please Contact Admin";

                        goto resultVis;
                    }
                }
            }
        }



        if ($_POST['mobile'] != '') {


            $mobile = BlockSQLInjection($_POST['mobile']);

            // $mobile = $_POST['mobile'];

            $mobileCheck = select_query($con, "user_register", "", "`mobile` = '$mobile' AND `id` != '$userId' and `deletes`='0'", "", "");

            if ($mobileCheck['nr'] > 0) {

                $result["type"] = "0";

                $result["result"] = "Mobile Number Already Exists.";

                goto resultVis;
            } else {

                if ($roll_id == 1) {

                    $user_Update_arr['mobile'] = $mobile;
                } else {

                    $mobileCheck = select_query($con, "user_register", "", "`mobile` = '$mobile' AND `id` = '$userId' and `deletes`='0'", "", "");

                    if ($mobileCheck['nr'] > 0) {
                    } else {

                        $result["type"] = "0";

                        $result["result"] = "You Could Not Update The Moblie No. Please Contact Admin";

                        goto resultVis;
                    }
                }
            }
        }



        if ($_POST['bulidingname'] != '') {

            $user_Update_arr['building_name'] = $_POST['bulidingname'];
        }



        if ($_POST['nationlaity'] != '') {
            $nation = BlockSQLInjection($_POST['nationlaity']);

            // $nation = (int)$_POST['nationlaity'];
            $inContry = select_top_name($con, "countries", "name", "`flag` = '1' AND `id` = '$nation' AND `name` != '' ORDER BY `id` DESC", "name", "");
            $user_Update_arr["nationality"] = $con->real_escape_string($inContry);
        }

        // if ($_POST['billing_city'] != '') {

        //     $billcity = BlockSQLInjection($_POST['billing_city']);
        //     $billaddress = BlockSQLInjection($_POST['billing_address']);
        //     // var_dump($billaddress);die;

        //     // $billcity = (int)$_POST['billing_city'];
        //     $district_name = mysqli_query($con, "
        //      UPDATE user_register 
        //      SET city = '$billcity' ,
        //       state = '$billaddress'
        //      WHERE deletes = '0'");

        // }


        if ($_POST['billing_address'] != '') {
            $billadd = BlockSQLInjection($_POST['billing_address']);

            // $billadd = (int)$_POST['billing_address'];
            $inState = select_top_name($con, "states", "name", "`flag` = '1' AND `id` = '$billadd' AND `name` != '' ORDER BY `id` DESC", "name", "");


            $user_Update_arr["state"] = $con->real_escape_string($inState);
        }
        
        if ($_POST['address_us'] != '') {
            $address_us = BlockSQLInjection($_POST['address_us']);
            
            $user_Update_arr["address"] = $con->real_escape_string($address_us);
        }


        if (!empty($_POST['company_name'])) {
            $user_Update_arr['company_name'] = BlockSQLInjection($_POST['company_name']);
        }

        if (!empty($_POST['upi_id'])) {
            $user_Update_arr['upiID'] = BlockSQLInjection($_POST['upi_id']);
        }
        
        
        if (!empty($_POST['acctype'])) {
            $user_Update_arr['acctype'] = BlockSQLInjection($_POST['acctype']);
        }

        if (!empty($_POST['account_name'])) {
            $user_Update_arr['account_name'] = BlockSQLInjection($_POST['account_name']);
        }

        if (!empty($_POST['bank_name'])) {
            $user_Update_arr['bank_name'] = BlockSQLInjection($_POST['bank_name']);
        }

        if (!empty($_POST['ibancode'])) {
            $user_Update_arr['IBAN_code'] = BlockSQLInjection($_POST['ibancode']);
        }

        if (!empty($_POST['swift_code'])) {
            $user_Update_arr['swift_code'] = BlockSQLInjection($_POST['swift_code']);
        }

        if (!empty($_POST['passport_expiry'])) {
            $user_Update_arr['dob'] = date("Y-m-d", strtotime(BlockSQLInjection($_POST['passport_expiry'])));
        }

        if (!empty($_POST['currency'])) {
            $user_Update_arr['currency_code'] = BlockSQLInjection($_POST['currency']);
        }

        if (!empty($_POST['exchangeid'])) {
            $user_Update_arr['exchangeid'] = BlockSQLInjection($_POST['exchangeid']);
        }

        if (!empty($_POST['whatsAppNo'])) {
            $user_Update_arr['whatsAppNo'] = BlockSQLInjection($_POST['whatsAppNo']);
        }

        // var_dump($user_Update_arr, $userId);
        // die;

        // User profile activity log Start at 26-05-23
        $get_user_data = select_query($con, "user_register", "", "`id`='$userId' and `deletes`='0'", "", "");
        if ($get_user_data['nr'] > 0) {
            $result_arr = array_intersect_key($get_user_data['result'][0], $user_Update_arr);
            $log_arr = array_diff($result_arr, $user_Update_arr);

            $user_profile_log_arr['user_id'] = $userId;
            $user_profile_log_arr['changed_by'] = $_SESSION['userinfo']['id'];
            $user_profile_log_arr['changed_data'] = json_encode($log_arr);
            $user_profile_log_arr['updated_datetime'] = $dubaidate_time;
            $user_profile_log_arr['ip'] = getUserIP();
        }
        // User profile activity log End at 26-05-23

        $user_Update = update($con, "user_register", "`id` = '$userId' and `deletes`='0'", $user_Update_arr, "", "", "", "");

        $errors = $user_Update['errors'];

        if ($errors != "") {

            $result["type"] = "0";

            $result["result"] = "Update Failed";

            goto resultVis;
        } else {

            // User profile activity log
            $user_profile_log_ins = insert($con, "user_profile_activity_log", "", $user_profile_log_arr, "", "", "");

            $result["type"] = "1";

            $result["result"] = "Updated Successfully";

            goto resultVis;
        }
    } else {

        $result["type"] = "0";

        $result["result"] = "User ID Not Found";

        goto resultVis;
    }







    resultVis:

    echo json_encode($result);
} else if ($method == "add_agent") {







    $tablename = "user_register";



    $result = [];







    $passport_expiry = $_REQUEST['passport_expiry'];







    $checkexpiry = strtotime($passport_expiry);



    $timenow = time();











    $editid = isset($_REQUEST['edit_id']) ? $_REQUEST['edit_id'] : "0";



    if ($editid != "0" && $editid != "") {



        $update = update($con, $tablename, "id='$editid'", "", "", "", "", "");



        $errors = $update['errors'];



        if ($errors != "") {



            $result["type"] = "0";



            $result["result"] = $errors;
        } else {



            $result["type"] = "2";



            $result["result"] = "Updated Successfully";
        }
    } else {



        $email = $_REQUEST['email'];



        $mobile = $_REQUEST['mobile'];



        $passport = $_REQUEST['passport'];











        $ins = insert($con, $tablename, "", "", "", "", "");



        $errors = $ins['errors'];



        if ($errors != "") {



            $result["type"] = "0";



            $result["result"] = $errors;
        } else {



            $result["type"] = "1";



            $result["result"] = "Inserted Successfully";
        }
    }









    echo json_encode($result);
} else if ($method == "bulkemail") {



    $winnerid = $_POST['winnerid'];



    $result = [];







    $winnerlist = select_query($con, "winnerlist", "", "`emaillog`='0'", "", "");



    if ($winnerlist['nr'] > 0) {



        foreach ($winnerlist['result'] as $key => $value) {



            $result['result'][] = array("id" => $value['id']);
        }



        $result["type"] = "1";
    } else {



        $result["type"] = "0";
    }















    echo json_encode($result);
} else if ($method == "deleteagent") {
    $tablename = "user_register";
    $result = [];

    $editid = isset($_REQUEST['deleteid']) ? $_REQUEST['deleteid'] : "0";

    if ($editid != "0" && $editid != "") {
        $arr = array("deletes" => "1");

        $update = update($con, $tablename, "id='$editid'", $arr, "", "", "", "");

        if ($update) {
            
            $apiUrl = 'https://www.goride.net.in/api/adminUserCancelJobs';
            $apiHeaders = [
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
            ];
            $postData = [
                'user_id' => $editid
            ];

            $apiResponse = requestAPI('POST', $apiUrl, $apiHeaders, $postData);
            $apiDecoded = json_decode($apiResponse, true);

            if (isset($apiDecoded['status']) && $apiDecoded['status'] === true) {
                $result["type"] = "3";
                $result["result"] = "Deleted Successfully and jobs cancelled.";
            } else {
                $result["type"] = "3";
                $result["result"] = "Deleted Successfully, but failed to cancel jobs.";
                $result["api_error"] = $apiDecoded['message'] ?? 'Unknown API error';
            }
        }
    }

    echo json_encode($result);
}











function generateNumericOTP($n)



{



    $generator = "1357902468";



    $result = "";



    for ($i = 1; $i <= $n; $i++) {



        $result .= substr($generator, (rand() % (strlen($generator))), 1);
    }



    return $result;
}
