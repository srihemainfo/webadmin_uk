<?php
include '../include/shi-config.php';
include '../include/functions.php';
require_once "Classes/PHPExcel.php";


$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$newid  = $_SESSION['memid'];


function unique_multi_array($array, $key)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
            $i++;
        }
    }
    return $temp_array;
}


if ($method == "move_file") {

    $result = [];



    if (isset($_FILES['mfile']['name'])) {

        $allowed = array('xlsx');

        $file_name = $_FILES['mfile']['name'];
        $file_type = $_FILES['mfile']['type'];
        $file_size = $_FILES['mfile']['size'];
        $file_tem_loc = $_FILES['mfile']['tmp_name'];

        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $name = "";

        if ("jpg" == $ext) {
            $name .= str_replace(".jpg", "", $file_name) . $newid . date("Ymdhms");
        } else if ("jpeg" == $ext) {
            $name .= str_replace(".jpeg", "", $file_name) . $newid . date("Ymdhms");
        } else if ("tif" == $ext) {
            $name .= str_replace(".tif", "", $file_name) . $newid . date("Ymdhms");
        } else if ("tiff" == $ext) {
            $name .= str_replace(".tiff", "", $file_name) . $newid . date("Ymdhms");
        } else if ("png" == $ext) {
            $name .= str_replace(".png", "", $file_name) . $newid . date("Ymdhms");
        } else if ("xlsx" == $ext) {
            $name .= str_replace(".xlsx", "", $file_name) . $newid . date("Ymdhms");
        }

        $file_store = "";
        $path = "";
        $userid = $newid;
        if (!in_array($ext, $allowed)) {
            $result['type'] = '0';
            $result['result'] = 'format not supported';
            goto Vi;
        } else {

            mkdir("upload/" . $userid, 0755);
            mkdir("upload/" . $userid . "/bluk", 0755);

            $path = "upload/" . $userid . "/bluk";

            $file_store = $path . '/' . $name . '.' . $ext;
        }

        $fileNEW = 'ajax/' . $path . '/' . $name . '.' . $ext;

        if (move_uploaded_file($file_tem_loc, $file_store)) {
            $result['type'] = '1';
            $result['result'] = 'success';

            $xl_file_path = $file_store;

            $reader = PHPExcel_IOFactory::createReaderForFile($xl_file_path);
            $excelObj = $reader->load($xl_file_path);
            $worksheet = $excelObj->getSheet(0);
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();

            $output = '';
            $drawname  = '';
            $user = array();
            $i = 0;

            $roll_id = "SELECT * FROM `user_register` WHERE `id`='$newid' AND `status` = '0' AND `deletes` = '0'";
            $run = mysqli_query($con,  $roll_id);
            if (mysqli_num_rows($run) > 0) {
                $row = $run->fetch_assoc();
                if ($row[roll_id] == 1) {
                    $roll_id = 0;
                }
            }
            // err check
            $ticket_err = false;
            $date_err = false;
            $mobile_err = false;
            $My3no_err = [];
            $product_err = [];




            $smscontent = '';
            for ($row = 2; $row <= $highestRow; $row++) {
                // $result['s'][] =  $row;
                $tid = $worksheet->getCell('A' . $row)->getValue();
                $mobile = $worksheet->getCell('C' . $row)->getValue();
                $name = $worksheet->getCell('B' . $row)->getValue();
                $email = $worksheet->getCell('D' . $row)->getValue();
                $purdata = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('H' . $row)->getValue()));


                if ($tid != '') {
                    if (strlen($mobile) > 8) {

                        if (strpos($mobile, ' ') !== false) {
                            $result['type'] = '0';
                            $result['result'] = 'Please Remove the space ' . $mobile;
                            goto Vi;
                        } else {


                            $user[$i][id] = '';
                            $user[$i][ticketid] = $tid;
                            $user[$i][mobile] = $mobile;
                            $user[$i][purdata] = $purdata;
                            $smscontent .=  'Thank you for your purchase and donation. Ticket ID #MT' . $tid . '.';
                            $sql = "SELECT * FROM `user_register` WHERE `mobile` = '$mobile' AND `status` = '0' AND `deletes` = '0'";
                            // $result['s'][]  =  $sql;
                            $existcheck = mysqli_query($con, $sql);
                            if (mysqli_num_rows($existcheck) > 0) {
                                $user_row = $existcheck->fetch_assoc();
                                $user[$i][id] = $user_row[id];
                            } else {

                                $user_name = 'Customer';

                                $sql1 = "INSERT INTO `user_register` (`user`, `roll_id`, `name`, `mobile`, `email`, `passport`, `passport_expiry`, `deletes`, `status`, `created_at` , `otp`, `pass`, `created_by`) VALUES ('$user_name', '$roll_id', ' $name', '$mobile', '$email', '', '', '0', '0', '$dubaidate_time', '', '', '$_SESSION[memid]');";
                                $run1 = mysqli_query($con, $sql1);
                                if ($run1) {
                                    $sql2 = "SELECT * FROM `user_register` WHERE `mobile` = '$mobile' AND `status` = 0 AND `deletes` = 0";
                                    $existcheck1 = mysqli_query($con, $sql2);
                                    if (mysqli_num_rows($existcheck1) > 0) {
                                        $row_new = $existcheck1->fetch_assoc();
                                        $user[$i][id] = $row_new[id];
                                    }
                                }
                            }

                            $draw_new_id = $_POST['draw_new_id'];

                            $draw = select_query($con, "draw", "", "`id`='$draw_new_id' and `deletes`='0' ", "", "");
                            if ($draw['nr'] > 0) {
                                $user[$i]['drawid'] = $draw['result'][0]['id'];
                                $drawname = $draw['result'][0]['name'];
                                $draw_status = $draw['result'][0]['status'];
                            }

                            // $highestRow1 = $worksheet->getHighestRow();
                            $tt = 0;
                            for ($row1 = 2; $row1 <= $highestRow; $row1++) {

                                if ($tid == $worksheet->getCell('A' . $row1)->getValue()) {
                                    $My3Numbers = $worksheet->getCell('E' . $row1)->getValue();
                                    $raffle =  $worksheet->getCell('F' . $row1)->getValue();
                                    $product = $worksheet->getCell('G' . $row1)->getValue();

                                    $user[$i]['data']['my3number' . $tt] =  $My3Numbers;
                                    $user[$i]['data']['raffle' . $tt] = $raffle;
                                    $amt = number_format((float) $product, 2, '.', '');
                                    $product_id = select_top_name($con, "product", "id", "`rate`='$amt' and `deletes`='0'  order by `id` DESC ", "id", "");
                                    $user[$i]['data']['productid' . $tt] =   $product_id;
                                    $user[$i]['data']['ticketid' . $tt] =   str_pad($tt + 1, 2, "0", STR_PAD_LEFT);
                                    $user[$i]['linescount'] = $tt;

                                    $tt++;
                                }
                            }

                            $i++;
                        }
                    } else {
                        $result['type'] = '0';
                        $result['result'] = 'Please Check the all mobile numbers';
                        goto Vi;
                    }
                }
            }

            $user_arr = unique_multi_array($user, 'ticketid');

            for ($g = 0; $g <= count($user_arr); $g++) {
                if ($user_arr[$g]['id'] != '') {


                    $t_id = $user_arr[$g]['ticketid'];
                    $draw_id = $user_arr[$g]['drawid'];
                    $ticket = select_query($con, "mticket", "", "(`ticket_no`='MT-$t_id' OR `ticket_no`='MT$t_id') and `deletes`='0' ", "", "");
                    if ($ticket['nr'] > 0) {
                        $ticket_err = true;
                    } else {
                        $ticket_err = false;
                    }

                    if ($user_arr[$g]['purdata'] == '1900-01-01') {
                        $date_err = true;
                    } else {
                        $date_err = false;
                    }

                    for ($s = 0; $s <= intval($user_arr[$g]['linescount']); $s++) {
                        if (strlen($user_arr[$g][data]['my3number' . $s]) == 3) {
                            if ($user_arr[$g][data]['my3number' . $s] != '') {
                                array_push($My3no_err, true);
                            } else {
                                array_push($My3no_err, false);
                            }
                        } else {
                            array_push($My3no_err, false);
                        }

                        if ($user_arr[$g][data]['productid' . $s] != '') {
                            array_push($product_err, true);
                        } else {
                            array_push($product_err, false);
                        }
                    }

                    if (in_array(false, $My3no_err)) {
                        $m3_t = false;
                    } else {
                        $m3_t = true;
                    }

                    if (in_array(false, $product_err)) {
                        $pro_t = false;
                    } else {
                        $pro_t = true;
                    }



                    $output  .= '<table width="100%" border="0">
                        <tr>';
                    if ($ticket_err == true) {
                        $output  .=  '<td width="20%" ><strong>Ticket :</strong> </strong> <strong style="background:red;">' . $t_id  . '</strong></td>';
                    } else {
                        $output  .=  '<td width="20%" ><strong>Ticket :' . $t_id  . '</td>';
                    }



                    if ($user_arr[$g]['purdata'] != '1900-01-01') {
                        $output  .= '<td width="20%" ><strong>Purchase Date : </strong>' . $user_arr[$g][purdata] . '</td>';
                    } else {
                        $output  .= '<td width="20%" ><strong>Purchase Date : </strong><span style="background:red;">' . $user_arr[$g][purdata] . '</span></td>';
                    }
                    $output  .= ' <td width="40%" align="left"> 
                    <strong>Draw Name :</strong>' . $drawname . '<strong> </td>
					  <td width="20%" align="left">';

                   
                    if ($ticket_err == false && $date_err == false && $m3_t == true && $pro_t == true) {
                        if($draw_status == 'Active') {
                        $output  .= '<input name="checkbox" type="checkbox" id="checkbox' . $t_id . '" checked/><label for="checkbox"> Send SMS / Email</label>';
                    }
                        $user_arr[$g]['inform'] = 1;
                    } else {
                        $user_arr[$g]['inform'] = 0;
                    }

                


                    $output  .= '</td>
  
                        </tr>
                         <tr>
                          <td colspan="4"><table width="100%" border="1" align="left" cellpadding="5" cellspacing="0" style="margin:5px !important;" >
                       <thead class="thead-bg">
                    <tr>
                      <th scope="col" style="padding:5px !important;">PRODUCTS</th>
                     
                      <th scope="col">MY3NUMBERS</th>
                      <th scope="col">RAFFLE ID
                      </th>
                    </tr>
                  </thead>
                       <tbody>';
                    $message = "";

                    for ($s = 0; $s <= intval($user_arr[$g]['linescount']); $s++) {

                        //$message.= $user_arr[$g][data]['productid' . $s]
                        $p_id = $user_arr[$g][data]['productid' . $s];
                        $productrate = select_top_name($con, "product", "rate", "`id`='$p_id' and `deletes`='0'  order by `id` DESC ", "rate", "");

                        $output  .= '<tr>';
                        if ($user_arr[$g][data]['productid' . $s] != '') {
                            $output  .= '<td>AED ' . $productrate . '</td>';
                        } else {
                            $output  .= '<td><span style="background:red;">AED ' . $productrate . '</span></td>';
                        }





                        if (strlen($user_arr[$g]['data']['my3number' . $s]) <= 3) {
                            if ($user_arr[$g]['data']['my3number' . $s] != '') {
                                if (strlen($user_arr[$g]['data']['my3number' . $s]) == 3) {
                                    $output  .= '<td>' . $user_arr[$g]['data']['my3number' . $s] . '</td>';
                                } else {
                                    $output  .= '<td><span style="background:red;">' . $user_arr[$g]['data']['my3number' . $s] . '</span></td>';
                                }
                            } else {
                                $output  .= '<td><span style="background:red;">Empty</span></td>';
                            }
                        }
                       



                        $output  .= '<td>
                          ' . $user_arr[$g]['data']['raffle' . $s] . '
                    </p>
                        </td>
                      </tr>
					  ';
                    }

                    $output  .= ' 
					
					
					</tbody>
                      </table></td>
                    </tr>
                    
                 
                             <tr><td colspan="4">  </td></tr>
                       </table>';
                }
            }

            // <tr>
            // <td colspan="4"><strong>SMS Content :  </strong> Thank you for your purchase and donations. Ticket ID #' . $user_arr[$g][ticketid] . '. CAT-AED 10. 443, 331. for a Total Amounts of AED ' . $amt . ' for more info. (https://tinyurl.com/2oehrmd9),. TC apply.</td>
            // </tr>


            $output  .= '<div id="submetbtn"><button class="btn btn-primary" onclick="bulk_insert()">Submit</button><div>';
            $result['myobj'] = json_encode($user_arr);
            $result['output'] = $output;
        } else {
            $result['type'] = '0';
            $result['result'] = 'Not upload';
            goto Vi;
        }
    } else {
        $result['type'] = '0';
        $result['result'] = 'Not Get File';
        goto Vi;
    }


    Vi:
    echo json_encode($result);
} else if ($method == "file_download"){
    $result = [];
$tablename = $_POST['tablename'];
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $filename = $tablename.date('ymdhis').'.xlsx';
    


    $contype = '';
    $da = '';
    $now = date('Y-m-d');
    $formdate = $_POST['formdate'];
    $todate = $_POST['todate'];
    if ($formdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y/m/d", strtotime($formdate));
        $td = date("Y/m/d", strtotime($todate));
        $contype = "`purchase_datetime` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $da = 'DESC';
        $contype = "`purchase_datetime` LIKE '%$now%' AND";
    }
    if ($_SESSION[memid] != 1) {
        $agent = "`agent_id` = '$_SESSION[memid]' AND";
    } else {
        $agent = "";
    }

    $sql = "SELECT * FROM `$tablename` WHERE $contype $agent `deletes` = '0' ORDER BY createdon $da";
    $run = mysqli_query($con, $sql);
    if (mysqli_num_rows($run) > 0) {
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'USER ID');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'NAME');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'MOBILE');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'TICKET NO');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'TOTAL LINES');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'PURCHASE DATE');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'TOTAL AMOUNT');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'TRANSACTION ID');

      
        $col = 2;
        while ($row = mysqli_fetch_array($run)) {
            $sql2 = "SELECT * FROM `user_register` WHERE `id` = '$row[user_id]' AND `deletes`='0'";
            $run2 = mysqli_query($con, $sql2);
            if (mysqli_num_rows($run2)) {
                $row2 = $run2->fetch_assoc();
                $result['type'] = 1;
               
               
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$col, $row['id']);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$col, $row['user_id']);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$col, $row2['name']);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$col, $row2['mobile']);
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$col, $row['ticket_no']);
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$col, $row['total_lines']);
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$col, date("d-M-Y g:i a", strtotime($row['purchase_datetime'])));
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$col, $row['total_amount']);
                $objPHPExcel->getActiveSheet()->setCellValue('I'.$col, $row['transaction_id']);

                // $result['result'][] = array("id" => str_pad($row['id'], 7, "0", STR_PAD_LEFT), "userid" => $row['user_id'], "name" => $row2['name'], "mobile" => $row2['mobile'], "ticket_no" => $row['ticket_no'], "total_lines" => $row['total_lines'], "date" => date("d-M-Y g:i a", strtotime($row['purchase_datetime'])), "total_amt" => $row['total_amount'], "transaction_id" => $row['transaction_id']);
            $col++;
            }
        }


        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $loc ='upload/generate/';  
        $objWriter->save($loc.$filename);


        $result['url'] = $adminurl. 'xlsx/' . $loc . $filename;
        $result['filename'] =  $filename;
    } else {
        $result['type'] = 0;
        // $result['result'][]  = '';
        // $result['result'] = 'No Datas Found!';
    }
    // $result['q'] = $sql;
    echo json_encode($result);
 }