<?php
include '../include/shi-config.php';
include '../include/functions.php';
require_once "Classes/PHPExcel.php";


$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$userid  = $_SESSION['memid'];


if ($method == 'kiosk_upload') {
    if ($userid == '' || $userid == null || $userid == 'null') {
        $result['type'] = '0';
        $result['result'] = 'Login Required!';
        goto Vsi;
    }
    $result = [];
    if (isset($_FILES['kioskFile']['name'])) {

        $allowed = array('xlsx');
        $file_name = $_FILES['kioskFile']['name'];
        $file_type = $_FILES['kioskFile']['type'];
        $file_size = $_FILES['kioskFile']['size'];
        $file_tem_loc = $_FILES['kioskFile']['tmp_name'];

        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $name = uniqid(15) . time();
        $file_store = "";
        $path = "";
        // $userid = $newid;
        if (!in_array($ext, $allowed)) {
            $result['type'] = '0';
            $result['result'] = 'format not supported';
            goto Vsi;
        } else {

            mkdir("upload/" . $userid, 0755);
            mkdir("upload/" . $userid . "/Kiosk", 0755);
            $path = "upload/" . $userid . "/Kiosk";
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

            for ($row = 2; $row <= $highestRow; $row++) {

                $kioskID = $worksheet->getCell('A' . $row)->getValue();

                if ($kioskID != '' && $kioskID != null && $kioskID != 'null') {



                    $kiosk_machines = select_query($con, "kiosk_machines", "", "`kiosk_id` LIKE '$kioskID' AND `ownedby` = '$userid' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
                    if ($kiosk_machines['nr'] > 0) {
                        $kid = $kiosk_machines['result'][0]['id'];
                        if ($kid != '') {

                            $kiosk_arr = [
                                'location' => $worksheet->getCell('B' . $row)->getValue(),
                                'area' => $worksheet->getCell('C' . $row)->getValue(),
                                'country' => $worksheet->getCell('D' . $row)->getValue()
                            ];

                            $Inv_update = update($con, "kiosk_machines", "`id` = '$kid' AND `deletes` = '0' AND `ownedby` = '$userid' ORDER BY `id` DESC LIMIT 1", $kiosk_arr, "", "", "", "");
                            $errors = $Inv_update['errors'];
                            if ($errors != "") {

                                // Log
                                error_log_new($con, getUserIP(), 'Kiosk_Bulk_Update_Failed', $kid, '', '', 'The Kiosk bluk update has been failed', json_encode($errors), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);

                                // $result["type"] = "0";
                                // $result["result"] = $errors;
                                // $result["result"] = "Update Failed. Kindly Contact to Admin Team!";
                                // goto gviRetrunMM;
                            } else {

                                // Log
                                error_log_new($con, getUserIP(), 'Kiosk_Bulk_Update_Success', $kid, '', '', 'The Kiosk bluk update has been Success', json_encode($errors), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);

                                // $result['type'] = 1;
                                // $result['result'] = 'The Kiosk machine details successfully Updated!';
                                // goto gviRetrunMM;
                            }
                        }
                    } else {
                        $kiosk_machines = select_query($con, "kiosk_machines", "", "`kiosk_id` LIKE '$kioskID' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
                        if ($kiosk_machines['nr'] < 1) {


                            $kiosk_arr = [
                                'ownedby' => $userid,
                                'kiosk_id' => $kioskID,
                                'location' => $worksheet->getCell('B' . $row)->getValue(),
                                'is_active' => '0',
                                'deletes' => '0',
                                'createdon' => $dubaidate_time,
                                'country' => $worksheet->getCell('D' . $row)->getValue(),
                                'area' => $worksheet->getCell('C' . $row)->getValue()
                            ];

                            $kiosk_machines_ins = insert($con, "kiosk_machines", "", $kiosk_arr, "", "", "");
                            if ($kiosk_machines_ins['id'] != '') {
                                // Log
                                error_log_new($con, getUserIP(), 'Kiosk_Bulk_Insert_Success', $kid, '', '', 'The Kiosk bluk Insert has been Success', json_encode($kiosk_machines_ins), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);

                                // $result['type'] = 1;
                                // $result['result'] = 'The Kiosk machine added successfully!';
                                // goto gviRetrun;
                            } else {
                                // Log
                                error_log_new($con, getUserIP(), 'Kiosk_Bulk_Insert_Failed', $kid, '', '', 'The Kiosk bluk Insert has been Failed', json_encode($kiosk_machines_ins), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);

                                // $result['type'] = 0;
                                // $result['result'] = 'The process failed! Try again!';
                                // goto gviRetrun;
                            }
                        }
                    }
                }
            }

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
