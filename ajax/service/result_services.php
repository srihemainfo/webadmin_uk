<?php
include '../../include/shi-config.php';
include '../../include/functions.php';

if (isset($_POST['request_type']) && !empty($_POST['request_type'])) {
    echo json_encode("Success message from server.......");
}



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



if ($method == "winner_list") {
    $result = [];
    $formdate = $_POST['formdate'];
    if ($formdate != '') {

        $fd = date("Y-m-d", strtotime($formdate));

        $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$fd 23:59:59' ";
    }



    $winner_list = select_query($con, "winner_past_lsit", "", "`deletes`='0' AND   $contype", "", "");
    if ($winner_list['nr'] > 0) {
        foreach ($winner_list[result] as $key => $value) {
            // $invoiceid = $value['invoice_id'];
            // $transid =  select_top_name($con, "aticket", "transaction_id", "`invoice_no`='$invoiceid' and `deletes`='0'", "transaction_id", "");
            // $agentid =  select_top_name($con, "aticket", "agent_id", "`invoice_no`='$invoiceid' and `deletes`='0'", "agent_id", "");
            // $agent_name =  select_top_name($con, "user_register", "name", "`id`='$agentid' and `deletes`='0'", "name", "");
            // $ticketamount =  select_top_name($con, "aticket", "net_total", "`invoice_no`='$invoiceid' and `deletes`='0'", "net_total", "");
            $url = $adminurl . $value['image_url'];
            $result['result'][] = array("id" => $value['id'], "date" =>  str_pad($value['createdon'], 7, "0", STR_PAD_LEFT), "drawid" => str_pad($value['draw_id'], 7, "0", STR_PAD_LEFT), "url" => '<a class="btn text-danger btn-sm"><span style="cursor: pointer;color: #136e1a !important;" class="fe fe-eye"" onclick="preview(' . "'$url'" . ')">Preview</span></a>', "name" => $value['name'], "country" => $value['country'], "my3number" => $value['my3_numbers'], "match" => $value['matched_order'], "participated" => $value['participated_categeroy'], "prize" => $value['won_prize']);
        }
        $result['type'] = 1;
    } else {
        $result['type'] = 0;
    }

    echo json_encode($result);
} else

if ($method == "result_uplaod1") {


    $result = [];
    $formdate = $_POST['id'];
    $output = '';

    $winner_upadte = select_query($con, "winner_past_lsit", "", "`deletes`='0'  AND  `id`= $formdate", "", "");
    if ($winner_upadte['nr'] > 0) {
        $draw_id = $winner_upadte['result'][0]['draw_id'];
        $product_categeroy = $winner_upadte['result'][0]['participated_categeroy'];
        $match_order = $winner_upadte['result'][0]['matched_order'];
        $output .= '	<form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" value="' . $formdate . '" name="result_up">
        <fieldset>
            <div class="row">
                <div class="row">
                    <div class="col-6 mb-3">
                        <span>Select Draw</span>&nbsp;<span style="color: red;">*</span>
                        <select id="draw_new_id" name="draw_new_id" class="form-select">
                            <option value="">Select Draw</option>';

        $draw = select_query($con, "draw", "", "`status` != 'Pending' and `deletes`='0' ", "", "");

        if ($draw['nr'] > 0) {
            foreach ($draw['result'] as $key => $value) {
                $sss = "";
                if ($value['id'] ==  $draw_id) {
                    $sss = "selected";
                }
                $output .= '<option value="' . $value['id'] . ' " ' . $sss . '>' . $value['name'] . '</option>';
            }
        }

        $output .= '</select>

                    </div>

                    <div class="col-6 mb-3">
                    
                        <span>Image:</span>&nbsp;<span style="color: red;">*(size : 1020 × 680 px)</span>


                        <input type="file" class="form-control" name="photos"required >

                    </div>
                </div>


                <div class="row">
                    <div class="col-6 mb-3">
                        <span>Name:</span>&nbsp;<span style="color: red;">*</span>

                        <input type="text" class="form-control" name="customername" value="' . $winner_upadte[result][0][name] . '" required>



                    </div>

                    <div class="col-6 mb-3">
                        <span>Country:</span>&nbsp;<span style="color: red;">*</span>


                        <input type="text" class="form-control" name="Country" value="' . $winner_upadte[result][0][country] . '" required>



                    </div>
                </div>


                <div class="row">
                    <div class="col-6 mb-3">
                        <span>My3 Numbers:</span>&nbsp;<span style="color: red;">*</span>

                        <input type="text" class="form-control" name="My3number" value="' . $winner_upadte[result][0][my3_numbers] . '" required>





                    </div>

                    <div class="col-6 mb-3">
                        <span>Matched Order:</span>&nbsp;<span style="color: red;">*</span>
                        <select id="Matched" name="Matched" class="form-select" value="' . $winner_upadte[result][0][matched_order] . '" required>
                            <option value="">Select Prize</option>';

        for ($i = 1; $i <= 3; $i++) {

            $sss = "";
            if ($i ==  $match_order) {
                $sss = "selected";
            }
            $output .=    '<option value="' . $i . '"  ' . $sss . '>' . $i . '<span>st</span> Prize</option>
                            ';
        }

        $output .= '</select>




                    </div>
                </div>


                <div class="row">
                    <div class="col-6 mb-3">
                        <span>Participated Category:</span>&nbsp;<span style="color: red;">*</span>
                        <select id="Participated" name="Participated" class="form-select">
                            <option value="">Select Category</option>';

        $productlist = select_query($con, "product", "", "`deletes`='0' ", "", "");
        if ($productlist['nr'] > 0) {
            foreach ($productlist['result'] as $key => $value) {

                $sss = "";
                if ($value['id'] ==   $product_categeroy) {
                    $sss = "selected";
                }

                $output .= '<option value=" ' . $value['id'] . '" ' . $sss . '>' . $value['rate'] . '</option>';
            }
        }

        $output .= '</select>





                    </div>

                    <div class="col-6 mb-3">
                        <span> Prize:</span>&nbsp;<span style="color: red;">*</span>
                        <input type="text" class="form-control" name="Prize" value="' . $winner_upadte[result][0][won_prize] . '" required>

                        



                    </div>
                </div>















                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-info" name="resuld_uplaod" style="width:20% ;" value="Update"> 
            </div>








            </div>


        </fieldset>

    </form>';



        $result['result'] = $output;
        $result['type'] = 1;
    } else {
        $result['type'] = 0;
    }
    echo json_encode($result);
} else if ($method == "delete_result_now") {

    $drawid = $_POST['id'];
    $result = [];
    $draw_arr = array("deletes" => '1');
    $Inv_update = update($con, "winner_past_lsit", "`id` = '$drawid' and `deletes`='0'", $draw_arr, "", "", "", "");
    $errors = $Inv_update['errors'];
    if ($errors != "") {
        $result["type"] = "0";
        $result["result"] = $errors;
    } else {
        $result["type"] = "1";
        $result["result"] = "Deleted Successfully!";
    }

    echo json_encode($result);
} elseif ($method == "result_uplaod2") {


    $result = [];
    $formdate = $_POST['id'];
    $output = '';
    $drawName = isset($_POST['drawName']) ? $_POST['drawName'] : '';
    $drawTable = ($drawName == 'GRAND') ? 'raffledraw' : 'winnerlist';

    $winner_upadte = select_query($con, $drawTable, "", "`id`= $formdate", "", "");
    if ($winner_upadte['nr'] > 0) {
        $draw_id = $winner_upadte['result'][0]['draw_id'];
        $product_categeroy = $winner_upadte['result'][0]['participated_categeroy'];
        $match_order = $winner_upadte['result'][0]['matched_order'];
        $winnerid = $winner_upadte['result'][0]['id'];


        $output .= '<fieldset>
            <input type="hidden" value="' . $formdate . '" name="result_up">
                <div class="row">
                    <div class="row">
                        
    
                        <div class="col-12 mb-6">
                        
                            <span>Image:</span>&nbsp;<span style="color: red;">(Size : 180 × 200 px)</span>
    
    
                            <input type="file" class="form-control" id="photos" onchange="preview(event)" required>
                            <div class="text-center mt-3">
                            <img id="frame" src="" width="150px" height="150px" style="display:none;">
                            </div>
                        </div>
                    </div>


                    <div class="col-12 mb-6">
                    <span>Name:</span>&nbsp;<span style="color: red;">(70 Characters are only allowed)</span>
    


                    <input maxlength="70" type="text" class="form-control"  id="custname" value="' . $winner_upadte['result'][0][($drawName == 'GRAND') ? 'winner_name' : 'name'] . '" required>



                </div>
                    
    
                        <div class="col-12 mb-6">
                            <span>Nationality:</span>&nbsp;
    
    
                            <input type="text" class="form-control" id="Country" required>
    
    
    
                        </div>

                        <div class="col-12 mb-6">
                            <span>Residing Country :</span>&nbsp;
    
    
                            <input type="text" class="form-control" id="residingCountry" required>
    
    
    
                        </div>
                    </div>
    
    
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-info" onclick="move_new_file(' . "'$winnerid'" . ', ' . "'$drawName'" . ')" style="width:20% ;" value="Upload"> 
                </div>
    
    
    
    
    
    
    
    
                </div>
    
    
            </fieldset>';



        $result['result'] = $output;
        $result['type'] = 1;
    } else {
        $result['type'] = 0;
    }
    echo json_encode($result);
} else if ($method == "move_file") {

    $result = [];
    $newid =  $_POST['id'];

    $loginID = $_SESSION['memid'];
    if ($loginID == '' ||  $loginID == null ||  $loginID == 'null') {
        $result['type'] = '0';
        $result['result'] = 'Login Reuired';
        goto resultGVI;
    }
    $drawName = isset($_POST['drawName']) ? $_POST['drawName'] : '';
    $drawTable = ($drawName == 'GRAND') ? 'raffledraw' : 'winnerlist';
    $wd_arr = [];

    if ($_POST['Country'] != '') {
        $wd_arr['country'] = $_POST['Country'];
    }

    if ($_POST['residingCountry'] != '') {
        $wd_arr['residingcountry'] = $_POST['residingCountry'];
    }

    if ($_POST['custname'] != '') {
        $fname = ($drawName == 'GRAND') ? 'winner_name' : 'name';
        $wd_arr[$fname] = $_POST['custname'];
    }

    if (isset($_FILES['mfile']['name'])) {


        $allowed = array('jpg', 'jpeg', 'tif', 'tiff', 'png', 'webp');

        $file_name = $_FILES['mfile']['name'];
        $file_type = $_FILES['mfile']['type'];
        $file_size = $_FILES['mfile']['size'];
        $file_temp_loc = $_FILES['mfile']['tmp_name'];

        if ($file_name == '') {
            $result['type'] = '0';
            $result['result'] = 'Kindly choose the File!';
            goto resultGVI;
        }



        $ext = pathinfo($file_name, PATHINFO_EXTENSION);


        $file_store = "";
        $path = "";
        $userid = $newid;
        if (!in_array($ext, $allowed)) {
            $result['type'] = '0';
            $result['result'] = 'format not supported!';
            goto resultGVI;
        }


        $filePath = new CURLFile($file_temp_loc, $file_type, $file_name);
        $postData = array('image' => $filePath, 'id' => $loginID);

        $fileUpload = json_decode(json_decode(fileMoveS3($postData), true), true);

        if ($fileUpload['status'] = 'success') {

            $wd_arr["image_url"] = $fileUpload['data']['digitalURL'];
            // if ($_POST['Country'] != ' ') {
            //     $wd_arr['country'] = $_POST['Country'];
            //     $wd_arr['residingcountry'] = $_POST['residingCountry'];
            //     $wd_arr['name'] = $_POST['custname'];
            // }


            $wd_update = setupdate($con, $drawTable, "`id` = '$newid'", $wd_arr, "", "", "", "");

            $result['type'] = '1';
            $result['result'] = 'Image With Details Updated Successfully';
        } else {
            $result['type'] = '0';
            $result['result'] = 'Image upload Failed!';
        }
    } else {




        $wd_update = setupdate($con, $drawTable, "`id` = '$newid'", $wd_arr, "", "", "", "");
        $errors = $wd_update['errors'];
        if ($errors != "") {
            $result["type"] = 0;
            $result["result"] = $errors;
        } else {
            $result['type'] = '1';
            $result['result'] = 'Updated Successfully';
        }
    }

    resultGVI:
    echo json_encode($result);
} else if ($method == "delete_image_now") {

    $newid =  $_POST['id'];
    $result = [];

    $drawName = isset($_POST['drawName']) ? $_POST['drawName'] : '';
    $drawTable = 'winnerlist';

    $winner_img = array("image_url" => '');
    $Inv_update = update($con, $drawTable, "`id` = '$newid'", $winner_img, "", "", "", "");
    $errors = $Inv_update['errors'];
    if ($errors != "") {
        $result["type"] = "0";
        $result["result"] = $errors;
    } else {
        $result["type"] = "1";
        $result["result"] = "Image Deleted Successfully!";
    }

    echo json_encode($result);
} else if ($method == "result_uplaod3") {

// var_dump('hwlloe');die;
    $result = [];
    $formdate = $_POST['id'];
    $output = '';
    $drawName = isset($_POST['drawName']) ? $_POST['drawName'] : '';
    // $drawTable = ($drawName == 'SUPER') ? 'superraffledraw' : 'winnerlist';
    $drawTable = 'winnerlist';

    

    $winner_upadte = select_query($con, $drawTable, "", "`id`= $formdate", "", "");
    if ($winner_upadte['nr'] > 0) {
        $draw_id = $winner_upadte['result'][0]['draw_id'];
        $product_categeroy = $winner_upadte['result'][0]['participated_categeroy'];
        $match_order = $winner_upadte['result'][0]['matched_order'];
        $winnerid = $winner_upadte['result'][0]['id'];
        
        
        
        $output .= '<fieldset>
            <input type="hidden" value="' . $formdate . '" name="result_up">
            <div class="row">
                <div class="row">
                    <div class="col-12 mb-6">
                        <span>Image:</span>&nbsp;<span style="color: red;">(Size : 180 × 200 px)</span>
                        <input type="file" class="form-control" id="photos" onchange="preview(event)" required>
                        <div class="text-center mt-3">';
                        if($winner_upadte['result'][0]['image_url'] != ''){
                            $output .= '<img id="frame" src="' .constant('assetURL'). $winner_upadte['result'][0]['image_url'] . '" width="150px" height="150px">';
                        } else {
                            $output .= '<img id="frame" src="' . $winner_upadte['result'][0]['image_url'] . '" width="150px" height="150px" style="display:none;">';
                        }
                        $output .= '</div>
                    </div>
                </div>
                <div class="col-12 mb-6">
                    <span>Name:</span>&nbsp;<span style="color: red;">(70 Characters are only allowed)</span>
                    <input maxlength="70" type="text" class="form-control"  id="custname" value="' . $winner_upadte['result'][0]['fullName'] . '" required>
                </div>
                <div class="col-12 mb-6">
                    <span>Nationality:</span>&nbsp;
                    <select class="form-control" id="Country" name="Country">
                        <option value="">Select Nationality</option>';
                        $countries = mysqli_query($con, "SELECT * FROM countries");
                        while ($row = mysqli_fetch_assoc($countries)) {
                            $output .= '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                        }
        $output .= '</select>
                </div>
                <div class="col-12 mb-6">
                    <span>Residing Country :</span>&nbsp;
                   
                    <select class="form-control" id="residingCountry" name="residingCountry">
                        <option value="">Select Country</option>';
                        $countries = mysqli_query($con, "SELECT * FROM countries");
                        while ($row = mysqli_fetch_assoc($countries)) {
                            $output .= '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                        }
        $output .= '</select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger" onclick="deleteinfo(\'' . $winnerid . '\', \'' . $drawName . '\')">Delete</button>
                <input type="submit" class="btn btn-info" onclick="move_new_file1(\'' . $winnerid . '\', \'' . $drawName . '\')" style="width:20% ;" value="Upload"> 
            </div>
        </div>
        </fieldset>';


// var_dump($output);die;

        $result['result'] = $output;
        $result['type'] = 1;
    } else {
        $result['type'] = 0;
    }
    echo json_encode($result);
} else if ($method == "move_file1") {

    $result = [];
    $newid =  $_POST['id'];
    
    

    $loginID = $_SESSION['memid'];
    if ($loginID == '' ||  $loginID == null ||  $loginID == 'null') {
        $result['type'] = '0';
        $result['result'] = 'Login Reuired';
        goto resultGVI1;
    }
    $drawName = isset($_POST['drawName']) ? $_POST['drawName'] : '';
    $drawTable =  'winnerlist';
    $wd_arr = [];

    if ($_POST['Country'] != '') {
        $wd_arr['country'] = $_POST['Country'];
    }

    if ($_POST['residingCountry'] != '') {
        $wd_arr['residingcountry'] = $_POST['residingCountry'];
    }
    
    if ($_POST['custname'] == '') {
            $result['type'] = '0';
            $result['result'] = 'Kindly Give the Winner Name!';
            goto resultGVI1;
        }

    if ($_POST['custname'] != '') {
        $fname ='fullName';
        
        $wd_arr[$fname] = $_POST['custname'];
    }
    
    // var_dump($_POST['Country'],$_POST['residingCountry'],$_POST['custname']);die;
    
   

    if (isset($_FILES['mfile']['name'])) {


        $allowed = array('jpg', 'jpeg', 'tif', 'tiff', 'png', 'webp');

        $file_name = $_FILES['mfile']['name'];
        $file_type = $_FILES['mfile']['type'];
        $file_size = $_FILES['mfile']['size'];
        $file_temp_loc = $_FILES['mfile']['tmp_name'];

        if ($file_name == '') {
            $result['type'] = '0';
            $result['result'] = 'Kindly choose the File!';
            goto resultGVI1;
        }



        $ext = pathinfo($file_name, PATHINFO_EXTENSION);


        $file_store = "";
        $path = "";
        $userid = $newid;
        if (!in_array($ext, $allowed)) {
            $result['type'] = '0';
            $result['result'] = 'format not supported!';
            goto resultGVI1;
        }


        $filePath = new CURLFile($file_temp_loc, $file_type, $file_name);
        $postData = array('image' => $filePath, 'id' => $loginID);
        
        // var_dump($postData);die;

        $fileUpload = json_decode(json_decode(fileMoveS3($postData), true), true);

        // var_dump($fileUpload);die;

        if ($fileUpload['status'] = 'success') {
            
            $imag12 = $fileUpload['data']['digitalURL'];
            
            
            $w= constant('assetURL'). $imag12;
            // var_dump($w);die;
            // assetURL
            

            $wd_arr["image_url"] = $imag12;
            // if ($_POST['Country'] != ' ') {
            //     $wd_arr['country'] = $_POST['Country'];
            //     $wd_arr['residingcountry'] = $_POST['residingCountry'];
            //     $wd_arr['name'] = $_POST['custname'];
            // }


            $wd_update = setupdate($con, $drawTable, "`id` = '$newid'", $wd_arr, "", "", "", "");

            $result['type'] = '1';
            $result['result'] = 'Image With Details Updated Successfully';
            goto resultGVI1;
        } else {
            $result['type'] = '0';
            $result['result'] = 'Image upload Failed!';
            goto resultGVI1;
        }
    } else {

 



        $wd_update = setupdate($con, $drawTable, "`id` = '$newid'", $wd_arr, "", "", "", "");
       
     
        $errors = $wd_update['errors'];
        if ($errors != "") {
            $result["type"] = 0;
            $result["result"] = $errors;
            goto resultGVI1;
        } else {
            $result['type'] = '1';
            $result['result'] = 'Updated Successfully';
        }
    }

    resultGVI1:
        // var_dump($result);die;
    echo json_encode($result);
}
