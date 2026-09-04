<?php









include '../../include/shi-config.php';



include '../../include/functions.php';







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








if ($method == "list_agent") {



    if ($_SESSION['memid'] != 1) {



        $condition = "`created_by` = '$_SESSION[memid]' AND";
    } else {



        $condition = "";
    }











    $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC ", "roll_id", "");











    $user_register = select_query($con, "user_register", "", "$condition  $role `deletes`='0'", "", "");



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



    $mobile = $_POST['mobile'];



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
} 
else if ($method == "list_draw") {



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
} 
else if ($method == "activate_now") {







    $drawid = $_POST['id'];



    $result = [];



    $draw_arr = array("status" => 'Active');



    $Inv_update = update($con, "pre_draw", "`id` = '$drawid' and `deletes`='0'", $draw_arr, "", "", "", "");



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



    $Inv_update = update($con, "pre_draw", "`id` = '$drawid' and `deletes`='0'", $draw_arr, "", "", "", "");



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



    $drawid = $_POST['drawid'];

  $draw_no = $_POST['draw_no'];

    if (strlen($drawid)) {















        $drawlucky = $_POST['drawlucky'];

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

//  $result[] = array("drawno" => $row['draw_no'], "id" => $row['id'], "nid" => str_pad($row['id'], 7, "0", STR_PAD_LEFT), "name" => $row['name'], "ticket_start_datetime" => date_format(date_create($row['ticket_start_datetime']), "d-m-Y"), "ticket_end_datetime" => date_format(date_create($row['ticket_end_datetime']), "d-m-Y"), "result_datetime" => date_format(date_create($row['result_datetime']), "d-m-Y h-i-A"), "status" => $row['status'], "output" => $action);


//   $drawin= array("draw_no"=>str_pad(['id'], 7, "0", STR_PAD_LEFT),"drawfreq"=>'3',"name"=>'Tri daily draw');
//   $res=insert($con, "pre_draw", "" ,$drawin, "" ,"","");

    //   $datas="SELECT id FROM pre_draw Order BY id DESC";
    //   $res=mysqli_query($con, $sql);
      
    //   print_r($res);exit();
    
    
       $qry=select_query($con, "pre_draw", "", "`draw_no` = '$drawid' and `deletes`='0' ","","");
    
       
       if($qry['nr']>0){

        $draw_update = update($con, "pre_draw", "`draw_no` = '$drawid' and `deletes`='0'", $draw_arr, "", "", "", "");
        
       } else {
           
          $drawqry=select_query($con, "draw", "", "`draw_no` = '$drawid' and `deletes`='0' ","","");
          
            foreach ($drawqry['result'] as $key => $value) { }
            
           $draw_no =  $value['draw_no'];
             $drawfreq =  $value['drawfreq'];
               $name =  $value['name'];
                 $ticket_start_datetime =  $value['ticket_start_datetime'];
                   $ticket_end_datetime =  $value['ticket_end_datetime'];
                     $result_datetime =  $value['result_datetime'];
                   
            
            
            
             $draw_arr_int = array("draw_no"=>$draw_no,"drawfreq"=>$drawfreq,"name"=>$name,"ticket_start_datetime"=>$ticket_start_datetime,
             "ticket_end_datetime"=>$ticket_end_datetime,"result_datetime"=>$result_datetime);   
           
          $res=insert($con, "pre_draw", "" ,$draw_arr_int, "" ,"",""); 
           
       }
        
         

        
                        // $sql4 = "INSERT INTO `pre_draw` (`draw_no`, `drawfreq`,'`name`,`ticket_start_datetime`,`ticket_end_datetime`,
                        // `result_datetime`,`live_url`,`description`,`first`,`second`,`third_one`,`third_two`,`third_three`,`third_four`,`status`,
                        // '`sid`,`permitno`,`hprize1`,`hprize2`,`hprize3`,`prizeRatio`,`popUpHtml`,`deletes`,
                        // `raffleprizethird`,`raffleprizesecond`,'raffleprizefirst','raffle_status') VALUES (NULL, NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,
                        // NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);";

      
//   $qry=select_query($con, "pre_draw", "", order by DESC);
//   print_r()

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
