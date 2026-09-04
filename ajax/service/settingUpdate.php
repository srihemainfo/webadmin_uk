<?php


include '../../include/shi-config.php';
include '../../include/functions.php';

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

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

// class myClass
// {
//     public $con;
//     function __construct($con)
//     {
//         $this->con = $con;
//     }

//     function get_cus_site_total($checkout_response)
//     {
//         $post = json_decode($checkout_response, true);

//         if ($post['final_amount'] != null && $post['final_amount'] != '') {
//             $finaltotal = $post['final_amount'];
//         } else {
//             if ($post['item1'] > 0) {
//                 $tentotal = $post['item1'] * 10;
//             } else {
//                 $tentotal = "0";
//             }

//             if ($post['item2'] > 0) {
//                 $twentytotal = $post['item2'] * 20;
//             } else {
//                 $twentytotal = "0";
//             }

//             if ($post['item3'] > 0) {
//                 $thirtytotal = $post['item3'] * 50;
//             } else {
//                 $thirtytotal = "0";
//             }

//             if ($post['item4'] > 0) {
//                 $fourtytotal = $post['item4'] * 100;
//             } else {
//                 $fourtytotal = "0";
//             }

//             $finaltotal = $tentotal + $twentytotal + $thirtytotal + $fourtytotal;
//         }



//         return $finaltotal;
//     }

//     function get_age_site_total($checkout_response)
//     {
//         $post = json_decode($checkout_response, true);
//         $totalAmount = '';
//         $count = intval($post['count']) + 1;
//         if ($count <= 13) {
//             for ($i = 1; $i <= $count; $i++) {
//                 $no = '';
//                 if ($i <= 9) {
//                     $no = '0' . $i;
//                 } else if ($i <= 12) {
//                     $no = $i;
//                 } else {
//                 }
//                 $pname = "productid" . $i;
//                 $proid = $post[$pname];
//                 $result['pid'] = $proid;
//                 $lucky = "my3number" . $i;
//                 $p = select_query($this->con, "product", "", "`id`='$proid' and `deletes`='0' ", "", "");
//                 if ($p['nr'] > 0) {
//                     $totalAmount += floatval($p['result'][0]['rate']);
//                     // $m3n = $post[$lucky];

//                 }
//             }
//         }
//         return  $totalAmount;
//     }

//     function get_age_lines($checkout_response)
//     {
//         $text = '';
//         $post = json_decode($checkout_response, true);
//         $count = intval($post['count']) + 1;
//         if ($count <= 13) {
//             for ($i = 1; $i <= $count; $i++) {
//                 $no = '';
//                 if ($i <= 9) {
//                     $no = '0' . $i;
//                 } else if ($i <= 12) {
//                     $no = $i;
//                 } else {
//                 }
//                 $pname = "productid" . $i;
//                 $proid = $post[$pname];
//                 $result['pid'] = $proid;
//                 $lucky = "my3number" . $i;
//                 $p = select_query($this->con, "product", "", "`id`='$proid' and `deletes`='0' ", "", "");
//                 if ($p['nr'] > 0) {
//                     $m3n = $post[$lucky];
//                     $text .= $m3n . ' (' . floatval($p['result'][0]['rate']) . '), ';
//                 }
//             }
//         }
//         return  rtrim(trim($text), ',');
//     }

//     function get_cus_lines($checkout_response)
//     {
//         $text = '';
//         $post = json_decode($checkout_response, true);
//         $txtAdmin = '';
//         if (count($post['lineData']) > 0) {

//             $pid = $post['product_id'];
//             $product = select_query($this->con, "product", "", "`id`='$pid'  ORDER BY `id` DESC LIMIT 1", "", "");


//             for ($i = 0; $i < count($post['lineData']); $i++) {
//                 $key = 'line_no_' . ($i + 1);
//                 $text .= $post['lineData'][$i][$key] . ' (' . floatval($product['result'][0]['rate']) . '), ';
//             }

//             $txtAdmin = rtrim(trim($text), ',');
//         } else {
//             $pt = "4";
//             $ot = 1;
//             $k = "";
//             for ($x = 1; $x <= $pt; $x++) {

//                 $tval = "item" . $x;

//                 if ($post[$tval] > 0) {

//                     $k++;

//                     $product = select_query($this->con, "product", "", "`id`='$x'  ", "", "");

//                     foreach ($product['result'] as $key => $productinfolist) {
//                     }

//                     if ($x == "1") {

//                         $rowvalue = "a";
//                     } else if ($x == "2") {

//                         $rowvalue = "b";
//                     } else if ($x == "3") {

//                         $rowvalue = "c";
//                     } else if ($x == "4") {

//                         $rowvalue = "d";
//                     } else {

//                         $rowvalue = "";
//                     }



//                     $numlines = $post[$tval];

//                     for ($g = 1; $g <= $numlines; $g++) {

//                         $checkline = "itemid_row" . $rowvalue . "_" . $g;
//                         if ($post[$checkline] != "") {
//                             $my3number = $post[$checkline];
//                             $text .= $my3number . ' (' . floatval($product['result'][0]['rate']) . '), ';
//                             $ot++;
//                         }
//                     }
//                 }
//             }

//             $txtAdmin = rtrim(trim($text), ',');
//         }




//         return  $txtAdmin;
//     }
// }

// $my = new myClass($con);



if ($method == 'updateSettings') {
    $result = [];

    // if ($_POST['type'] == '') {
    //     $result['type'] = '0';
    //     $result['result'] = 'Type Missing';
    //     goto resultFIS12;
    // }

    // if ($_POST['type'] == 'Email') {
    //     $consition = 'EMAIL';
    //     $id = '1';
    // }
    // if ($_POST['type'] == 'Mobile') {
    //     $consition = 'SMS';
    //     $id = '2';
    // }

    if ($_POST['customerSettings'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Value Missing';
        goto resultFIS1261;
    }

    // $set_notify = select_query($con, "settings", "", "`id` = '$id' AND `type` = 'Block_My3Number' AND `notify_to` = '$consition' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
    // // var_dump($set_notify);die;
    // if ($set_notify['nr'] > 0) {

    //     if ($set_notify['result'][0]['notify'] != '') {
    //         $Email_Arr = json_decode($set_notify['result'][0]['notify']);

    //         if (($key = array_search($_POST['item'], $Email_Arr)) !== false) {
    //             unset($Email_Arr[$key]);
    //         }
    //     }

    $draw_arr = ["customerSiteNotfication" => json_encode(json_decode($_POST['customerSettings']))];


    // var_dump($draw_arr);
    // die;

    $Inv_update = update($con, "settings", "`id` = '1' ORDER BY `id` DESC LIMIT 1", $draw_arr, "", "", "", "");
    $errors = $Inv_update['errors'];
    if ($errors != "") {

        $result["type"] = "0";
        // $result["result"] = $errors;
        $result['type'] = '0';
        $result['result'] = 'Update Failed!';
        goto resultFIS1261;
    } else {
        $result['type'] = '1';
        $result['result'] = 'Updated Successfully';
        goto resultFIS1261;
    }
    // } else {
    //     $result['type'] = '0';
    //     $result['result'] = 'Track Not Found!';
    //     goto resultFIS12;
    // }







    resultFIS1261:
    echo json_encode($result);
} else if ($method == 'delete_notify') {
    $result = [];

    if ($_POST['type'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Type Missing';
        goto resultFIS12;
    }

    if ($_POST['type'] == 'Email') {
        $consition = 'EMAIL';
        $id = '1';
    }
    if ($_POST['type'] == 'Mobile') {
        $consition = 'SMS';
        $id = '2';
    }

    if ($_POST['item'] == '') {
        $result['type'] = '0';
        $result['result'] = 'Value Missing';
        goto resultFIS12;
    }

    $set_notify = select_query($con, "set_notify", "", "`id` = '$id' AND `type` = 'Block_My3Number' AND `notify_to` = '$consition' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
    // var_dump($set_notify);die;
    if ($set_notify['nr'] > 0) {

        if ($set_notify['result'][0]['notify'] != '') {
            $Email_Arr = json_decode($set_notify['result'][0]['notify']);

            if (($key = array_search($_POST['item'], $Email_Arr)) !== false) {
                unset($Email_Arr[$key]);
            }
        }

        $draw_arr = ["notify" => json_encode(array_values($Email_Arr))];
        $Inv_update = setupdate($con, "set_notify", "`id` = '$id' AND `type` = 'Block_My3Number' AND `notify_to` = '$consition' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", $draw_arr, "", "", "", "");
        $errors = $Inv_update['errors'];
        if ($errors != "") {

            $result["type"] = "0";
            // $result["result"] = $errors;
            $result['type'] = '0';
            $result['result'] = 'Update Failed!';
            goto resultFIS12;
        } else {
            $result['type'] = '1';
            $result['result'] = array_values($Email_Arr);
            goto resultFIS12;
        }
    } else {
        $result['type'] = '0';
        $result['result'] = 'Track Not Found!';
        goto resultFIS12;
    }







    resultFIS12:
    echo json_encode($result);
}
// function winnerTemplate($baseurl, $prize, $amount, $my3number, $drawname, $drawdate, $strname, $name)
// {

//     $output = '';

//     // $output .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

//     // <html xmlns="http://www.w3.org/1999/xhtml">

//     // <head>

//     //  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

//     //  <meta http-equiv="X-UA-Compatible" content="IE=edge" />

//     //  <meta name="viewport" content="width=device-width, initial-scale=1.0">

//     //  <title>Ticket Purchase OTP   Template</title>

//     //  <script type="text/javascript" src="https://gc.kis.v2.scr.kaspersky-labs.com/FD126C42-EBFA-4E12-B309-BB3FDD723AC1/main.js?attr=joANRz1QAreAWrKv7NqvwqdmY-WaS_NcrGhRXWzdh4taBt-YrMDr8zsAW5exQY9iQc4imXcH0KdDKH5EUPE8APuGF8NlSBE5r7WonXIaiWvbVBqDQFNY_EGym43v5Tju" charset="UTF-8"></script><style type="text/css">



//     //    @import url("https://fonts.googleapis.com/css2?family=Barlow+Condensed&display=swap");

//     //    body {

//     //      margin: 0;

//     //    }

//     //    .wrapper {



//     //      background:#CCC;



//     //      }

//     //    .main {



//     //      background:#FFF;

//     //      max-width:600px;



//     //      }



//     //    table {

//     //      border-spacing: 0;

//     //    }

//     //    td {

//     //      padding: 3px;

//     //    }

//     //    img {

//     //      border: 0;

//     //    }

//     //    .column-one {



//     //      text-align:center;

//     //      margin:0 auto;

//     //      }

//     //    .column-one .column {



//     //      width:100%;

//     //        margin:0 auto;



//     //      }
//     //      .im {
//     //         color: #29377d;
//     //     }

//     //  </style>

//     //  </head>

//     //  <body>


//     //    <center class="wrapper">


//     //      <table class="main" width="100%">

//     //          <!-- BORDER -->

//     //          <tr><td class="column-one" style="background: #29377d; height:50px;">


//     //          </td></tr>

//     //                  <tr><td class="column-one" style="background: radial-gradient(circle,#fcef48 0%,#fdd206 100%); height:11px;">

//     //          </td></tr>

//     //          <tr><td class="column-one" >

//     //          <table class="column"> <tr>
//     //            <td valign="top" style="padding: 16px 0 0px 0;">

//     //          <center>

//     //            <img src="https://www.littledraw.com/assets/images/mailtemplate/logo1.png" style="border: 0px;"  >

//     //          </center>

//     //            </td></tr></table>



//     //          </td></tr>

//     //          <!-- LOGO  -->

//     //                  <tr>

//     //                    <td class="column-one" >

//     //          <table align="center" class="column"> <tr><td valign="top" >
//     //           <tr>
//     //             <td valign="top"> <img style="border: 0px;margin: -80px 0 0px 10px;" src="https://www.littledraw.com/assets/images/mailtemplate/winner_man.png" >  </td>
//     //             <td><h3 class="demoname"style="color: #29377d;  font-family: Arial Narrow;font-style: italic;font-size: 28px; margin: 0px; text-align: center;font-weight: 500;"> Congratulations!
//     //             </h3>
//     //             <br>
//     //             <h3 class="demoname"style="color: #be1e2d;  font-family: Arial Narrow;font-style: italic;font-size: 28px; margin: 0px; text-align: center;font-weight: 600;">Hi,' . $name . '
//     //             </h3>
//     //             <h3 class="demoname" style="color: #29377d;font-family: Arial Narrow;font-style: italic;font-size: 25px;margin: 0;text-align: center;font-weight: 600;">You have won with
//     //             </h3>
//     //             <h3 class="demoname"style="color: #29377d;  font-family: Arial Narrow;font-style: italic;font-size: 28px; margin: 0px; text-align: center;font-weight: 700;">National Draw
//     //             </h3>
//     //             <h3 style="color: #ffffff;font-size: 22px;margin: auto;padding: 8px 13px 10px 14px;background: #be1e2d;line-height: 1;border-radius: 10px;width: 219px;/* text-align: center; */">
//     //               <p  style="color: #ffffff; text-decoration-line: none;font-style: italic;font-family: Arial Narrow;font-size: 24px;margin: 0;">' . $prize . ' Prize of AED' . $amount . '/-</p>
//     //             </h3>
//     //             <p style="color: #29377d;font-size:147%;text-align: center;font-style: italic;font-family: Arial Narrow;line-height: 30px;margin: 8px 0;"><span style="
//     //               font-weight: 600;font-size: 25px; ">' . $drawname . ' <br> on ' . $drawdate . ' </span><br><span style="font-weight: 600;">by matching the ' . $strname . '</span></p>
//     //             <h3 style="color: #be1e2d;font-size: 22px;margin: auto;/* padding: 8px 0px 10px 14px; */background: #fff;line-height: 1;border-radius: 10px;width: 85px;padding: 5px 0 5px 0;border: 1px solid #be1e2d;/* text-align: center; */">
//     //             <p  style="color: #be1e2d;text-decoration-line: none;font-style: italic;font-family: Arial Narrow;font-size: 31px;margin:0;">' . $my3number . '</p>
//     //             </h3>
//     //             <p style="color: #29377d;font-size:147% !important;text-align: center;font-style: italic;font-family: Arial Narrow;line-height:30px;margin: 0;">Do not miss to participating in<br>our Just3 Draws to increase your<br>chances of winning in our Super Raffle<br>Draw and Grand Raffle Draw.<br><span style="
//     //               font-weight: 800;">T &amp; C applies.</span></p>
//     //           </td>
//     //           </tr>


//     //            </td></tr></table>
//     //        </td></tr>

//     //  <tr>

//     //                    <td class="column-one" >

//     //          <table align="center" class="column"> <tr>

//     //            <td valign="top" >


//     //               <table style="margin: auto; color: #000000; font-size: medium; background-color: #fbfbfb;  border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">

//     //      </table>
//     //      <br>



//     //      <table style="margin: auto; color: #000000;  font-size: medium; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">

//     //        <tbody>


//     //            <tr>

//     //            <td class="gmail-line" style="box-sizing: border-box; width: 8px;padding: 0;">

//     //              <img  style="width:500px !important;" src="https://www.littledraw.com/assets/images/mailtemplate/final_img.png">

//     //            </td>

//     //          </tr>

//     //        </tbody>

//     //      </table>



//     //             <p style="color: #29377d !important;font-size: 15px !important;margin: 0px !important;text-align: center !important;font-weight: 500 !important;font-style: italic !important;font-family: Arial Narrow !important;margin: 8px 0px 0px 0px !important;">Note: This is a system auto generated email. Please do not reply to this mail.<br>

//     //             For Clarification


//     //                    <br>

//     //             Call 04 33 98880 Whatsapp +971 56 199 1271

//     //             <br>

//     //             or email support@littledraw.com</p>
//     //            </td></tr></table>

//     //          </td></tr>

//     //      </table> <!-- End Main Class -->



//     //    </center> <!-- End Wrapper -->



//     //  </body>

//     // </html>';





//     $output .= '
//     <!DOCTYPE html
//        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
//     <html xmlns="http://www.w3.org/1999/xhtml">
//        <head>
//           <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
//           <meta http-equiv="X-UA-Compatible" content="IE=edge" />
//           <meta name="viewport" content="width=device-width, initial-scale=1.0">
//           <title>Winner Email</title>
//           <style type="text/css">
//              @import url("https://fonts.googleapis.com/css2?family=Barlow+Condensed&display=swap");
//              @import url("https://fonts.cdnfonts.com/css/verdana");
//              body {
//              margin: 0;
//              }
//              .wrapper {
//              background: #CCC;
//              }
//              .main {
//              background: #FFF;
//              max-width: 600px;
//              }
//              table {
//              border-spacing: 0;
//              }
//              td {
//              padding: 3px;
//              }
//              img {
//              border: 0;
//              }
//              .column-one {
//              text-align: center;
//              margin: 0 auto;
//              }
//              .column-one .column {
//              width: 100%;
//              margin: 0 auto;
//              }
//              .im {
//              color: #01104e;
//              }
//              .column-one h3 {
//              color: #01104e;
//              font-family: Verdana, sans-serif !important;
//              font-size: 28px;
//              font-weight: 600;
//              margin: 14px 0 0 0;
//              }
//              .column-one p {
//              color: #01104e;
//              font-family: Verdana, sans-serif !important;
//              font-size: 19px;
//              font-weight: 500;
//              margin: 4px 0;
//              }
//              td.column-two p {
//              text-align: justify;
//              color: #01104e;
//              font-family: Verdana, sans-serif !important;
//              font-size: 17px;
//              font-weight: 500 !important;
//              padding: 0 10%;
//              margin: auto;
//              }
//              .c-f {
//              padding: 8px 0;
//              }
//              ul li {
//              display: inline;
//              border: 2px solid #be1e2d;
//              color: #be1e2d;
//              border-radius: 50px;
//              padding: 3px 9px;
//              font-size: 24px;
//              font-weight: 600;
//              margin: 0 1px;
//              }
//           </style>
//        </head>
//        <body>
//           <center class="wrapper">
//              <table class="main" width="100%">
//                 <!-- BORDER -->
               
//                 <tr>
//                    <td class="column-one">
//                       <table class="column">
//                          <tr>
//                             <td valign="top" style="padding: 0;">
//                                <center>
//                                   <br>
//                                   <img src="' . constant('assetURL') . 'littledraw/1/littledrawLogo.png" style="border: 0px;" width="35%">
//                                </center>
//                             </td>
//                          </tr>
//                          <tr>
//                             <td valign="top" style="padding: 0;">
//                                <center>
//                                   <br>
//                                   <img src="' . constant('assetURL') . 'littledraw/1/WinnerEmailTemplate.png" style="border: 0px;" width="84%">
//                                   <br>
//                                </center>
//                             </td>
//                          </tr>
//                       </table>
//                    </td>
//                 </tr>
//                 <!-- LOGO  -->
//                 <tr>
//                    <td class="column-one c-f">
//                     <br>
//                     <p style="font-weight: 600!important;">Hi, ' . $name . '</p>
//                     <p style="font-size: 14px; font-weight: 400!important; margin: 16px 0; ">Congratulations on winning the ' . $prize . ' Prize of AED ' . number_format($amount) . ' in the
//                        <br>
//                        Just3 ' . $drawname . ' held on ' . date("jS F Y", strtotime($drawdate)) . '.
//                     </p>
//                     <p style="font-size: 15px; font-weight: 400!important; color:#BE1E2D;" >Your winning combination</p>
//                    </td>
//                 </tr>
//                 <tr>
//                    <td>
//                       <ul
//                          style="color: #01104e;font-family: Verdana, sans-serif !important;font-size: 15px;font-weight: 500; list-style: none; text-align: center; padding: 0; margin:0 ; line-height: 1.5;">
//                          <li style="color: #004f9d;border-color: #004f9d;">' . $my3number[0] . '</li>
//                          <li>' . $my3number[1] . '</li>
//                          <li style="color: #009444;border-color: #009444;">' . $my3number[2] . '</li>
                        
//                       </ul>
//                    </td>
//                 </tr>
//                 <tr>
//                    <td class="column-one ">
//                       <p
//                          style="font-size:14px; font-weight: 400!important; font-family: Verdana, sans-serif !important; margin:14px 0;">
//                          You have up to 60 days to claim your winnings
//                       </p>
//                    </td>
//                 </tr>
//                 <tr>
//                     <td style="border-radius: 4px 4px 0px 0px;color: #111111;padding:14px 10px;" align="center" valign="top"
//                        bgcolor="#ffffff">
//                        <a href="' . $baseurl . '"
//                           style="color: #01104e;margin: 0px;padding: 9px; border:1px solid #01104e; width: fit-content;font-size: 16px;border-radius: 5px;font-family: Verdana, sans-serif !important; font-weight: 600; text-decoration: none;">CLAIM 
//                        NOW</a>
//                     </td>
//                  </tr>
//                 <tr>
//                    <td class="column-one">
//                       <img style="margin-top: 19px;" src="' . constant('assetURL') . 'littledraw/1/EmailTemplateFooter.png" width="84%">
//                    </td>
//                 </tr>
//                 <tr>
//                    <td>
//                       <p
//                          style="color: #01104e !important;font-size: 11px !important;margin: 7px 0px !important;text-align: center !important;font-weight: 500 !important;font-family: Verdana, sans-serif !important;">
//                          Note: This is a system auto-generated email. Please do not reply to this mail.
//                       </p>
//                    </td>
//                 </tr>
//                 <tr>
//                    <td class="column-one" style="background: #e6e6e6; height:15px;">
//                    </td>
//                 </tr>
//              </table>
//              <!-- End Main Class -->
//           </center>
//           <!-- End Wrapper -->
//        </body>
//     </html>';


//     return $output;
// }
