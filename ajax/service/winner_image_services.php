<?php
include '../../include/shi-config.php';
include '../../include/functions.php';

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// var_dump('kimnh');die;
$method = $_REQUEST['method'];
$drawID = $_REQUEST['drawId'];
// var_dump($method,$drawID);die;


if ($method == "site_banner") {

  
  if (isset($_FILES['file']['name'])) {
      
      var_dump('');die;
       
     


        $allowed = array('jpg', 'jpeg', 'tif', 'tiff', 'png', 'webp');

        $file_name = $_FILES['file']['name'];
        $file_type = $_FILES['file']['type'];
        $file_size = $_FILES['file']['size'];
        $file_temp_loc = $_FILES['file']['tmp_name'];
        
        var_dump();die;

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

        // $fileUpload = json_decode(json_decode(fileMoveS3($postData), true), true);

        // var_dump($fileUpload);die;

        if ($fileUpload['status'] = 'success') {

            $wd_arr["image_url"] = $fileUpload['data']['digitalURL'];
            // if ($_POST['Country'] != ' ') {
            //     $wd_arr['country'] = $_POST['Country'];
            //     $wd_arr['residingcountry'] = $_POST['residingCountry'];
            //     $wd_arr['name'] = $_POST['custname'];
            // }


            // $wd_update = setupdate($con, $drawTable, "`id` = '$newid'", $wd_arr, "", "", "", "");

            $result['type'] = '1';
            $result['result'] = 'Image With Details Updated Successfully';
        } else {
            $result['type'] = '0';
            $result['result'] = 'Image upload Failed!';
        }
    } 
   
   

    
}





