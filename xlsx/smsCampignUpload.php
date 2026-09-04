<?php
include '../include/shi-config.php';
include '../include/functions.php';
require_once "Classes/PHPExcel.php";


$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$userid  = $_SESSION['memid'];

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

function generateRandomCode($length = 6)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomCode = '';

    for ($i = 0; $i < $length; $i++) {
        $randomCode .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomCode;
}


if ($method == 'sms_campign_link_generate') {



    $result = [];
    $smsUploadBatch = ($_POST['smsUploadBatch'] != '') ? $_POST['smsUploadBatch'] : '1';
    $smsCamID = $_POST['smsCamID'];
    if ($smsCamID == '' && $smsCamID == null) {
        $result['type'] = '0';
        $result['result'] = 'Kindly select the SMS Campaign';
        goto Vsi;
    }


    if (isset($_FILES['mfile']['name'])) {

        $allowed = array('xlsx');
        $file_name = $_FILES['mfile']['name'];
        $file_type = $_FILES['mfile']['type'];
        $file_size = $_FILES['mfile']['size'];
        $file_tem_loc = $_FILES['mfile']['tmp_name'];

        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $name = uniqid(15) . time();
        $file_store = "";
        $path = "";
        $userid = $newid;
        if (!in_array($ext, $allowed)) {
            $result['type'] = '0';
            $result['result'] = 'format not supported';
            goto Vsi;
        } else {
            mkdir("upload/" . $userid, 0755);
            mkdir("upload/" . $userid . "/network", 0755);
            $path = "upload/" . $userid . "/network";
            $file_store = $path . '/' . $name . '.' . $ext;
        }

        $fileNEW = 'ajax/' . $path . '/' . $name . '.' . $ext;

        if (move_uploaded_file($file_tem_loc, $file_store)) {


            $xl_file_path = $file_store;

            $reader = PHPExcel_IOFactory::createReaderForFile($xl_file_path);
            $excelObj = $reader->load($xl_file_path);
            $worksheet = $excelObj->getSheet(0);
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();

            if ($highestRow > 100000) {
                $highestRow = "100000";
            } else {
                $highestRow = $highestRow;
            }

            for ($row = 2; $row <= $highestRow; $row++) {
                $mobileNo = $worksheet->getCell('A' . $row)->getValue();
                if ($mobileNo != "" && $mobileNo != "0") {
                    generateFVI:
                    $randomCode = $smsUploadBatch . generateRandomCode(6);
                    $campignList = select_query($con, "sms_campaign_list", "id", "`link_url` = '$randomCode' ORDER BY `id` DESC limit 1", "", "");
                    if ($campignList['nr'] > 0) {
                        goto generateFVI;
                    }


                    $campignMobile = select_query($con, "sms_campaign_list", "id", "`mobile` = '$mobileNo' AND `campaign_id` = '$smsCamID' ORDER BY `id` DESC limit 1", "", "");
                    if ($campignMobile['nr'] < 1) {
                        $look_arr = [
                            'mobile' => $mobileNo,
                            'campaign_id' => $smsCamID,
                            'link_url' => $randomCode,
                            'user_click' => '0',
                            'click_count' => '0',
                            'createdon' => $dubaidate_time,
                            'batch' => $smsUploadBatch
                        ];
                        $draw_ins = insert($con, "sms_campaign_list", "", $look_arr, "", "", "");
                    }
                }
            }
            // $del =  unlink($adminurl . $fileNEW);
            $result['type'] = '1';
            $result['result'] = 'Uploaded Successfully';
            goto Vsi;
        } else {
            $result['type'] = '0';
            $result['result'] = 'Not upload';
            goto Vsi;
        }
    } else {
        $result['type'] = '0';
        $result['result'] = 'Not Get File';
        goto Vsi;
    }

    Vsi:
    echo json_encode($result);
}
