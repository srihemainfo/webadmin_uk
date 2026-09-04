<?php
include '../include/shi-config.php';
include '../include/functions.php';
require_once "Classes/PHPExcel.php";


$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$userid  = $_SESSION['memid'];



function getTicketStatus($con, $transaction_id, $dubaidate_time)
{
    $ticketStatus = 'NO';
    $to = date("Y-m-d H:i:s",   strtotime('-7 day', strtotime($dubaidate_time)));
    $ticketno = select_top_name($con, "ticket", "ticket_no", "`transaction_id`='$transaction_id' AND `createdon` >= '$to' AND `deletes`='0'", "ticket_no", "");
    if ($ticketno != '') {
        $ticketStatus = 'YES';
    }
    return $ticketStatus;
}
if ($method == 'network_report') {

    $result = [];
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

            for ($row = 2; $row <= $highestRow; $row++) {


                if ($worksheet->getCell('D' . $row)->getValue() == 'SUCCESS') {
                    $transaction_id = $worksheet->getCell('B' . $row)->getValue();
                    if ($transaction_id != '') {

                        $lookup = select_query($con, "network_order_lookup", "", "`Merchant_Defined_Order_Number`='$transaction_id' ORDER BY `id` DESC LIMIT 1", "", "");
                        if ($lookup['nr'] > 0) {
                            $ticket = $lookup['result'][0]['ticket'];
                            $lookupID = $lookup['result'][0]['id'];
                            if ($ticket == 'NO') {
                                if (getTicketStatus($con, $transaction_id, $dubaidate_time) == 'YES') {
                                    $query =   mysqli_query($con, "UPDATE `network_order_lookup` SET `ticket` = 'YES' WHERE `network_order_lookup`.`id` = $lookupID;");
                                }
                            }
                        } else {



                            $look_arr = [
                                'System_Generated_Order' => $worksheet->getCell('A' . $row)->getValue(),
                                'Merchant_Defined_Order_Number' => $worksheet->getCell('B' . $row)->getValue(),
                                'Order_Status' => $worksheet->getCell('C' . $row)->getValue(),
                                'Payment_Status' => $worksheet->getCell('D' . $row)->getValue(),
                                'Decline_Code' => $con->real_escape_string($worksheet->getCell('E' . $row)->getValue()),
                                'Decline_Reason' => $worksheet->getCell('F' . $row)->getValue(),
                                'Date_Time' => date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $worksheet->getCell('G' . $row)->getValue() . ' ' . $worksheet->getCell('H' . $row)->getValue()))),
                                'Total' => $worksheet->getCell('I' . $row)->getValue(),
                                'Currency' => $worksheet->getCell('J' . $row)->getValue(),
                                'Channel' => $worksheet->getCell('K' . $row)->getValue(),
                                'Outlet'  => $worksheet->getCell('L' . $row)->getValue(),
                                'Origin_user_email' => $worksheet->getCell('M' . $row)->getValue(),
                                'Card_Holder_Email' => $worksheet->getCell('N' . $row)->getValue(),
                                'Cardholder_Currency' => $worksheet->getCell('O' . $row)->getValue(),
                                'Cardholder_Amount' => $worksheet->getCell('P' . $row)->getValue(),
                                'Outlet_Reference_ID' => $worksheet->getCell('Q' . $row)->getValue(),
                                'Merchant_ID' => $worksheet->getCell('R' . $row)->getValue(),
                                'Account_ID' => $worksheet->getCell('S' . $row)->getValue(),
                                'Gateway_Risk' => $worksheet->getCell('T' . $row)->getValue(),
                                'Gateway_Risk_Violations' => $worksheet->getCell('U' . $row)->getValue(),
                                'Authentication_Response' => $worksheet->getCell('V' . $row)->getValue(),
                                'Three_DS_Version' => $worksheet->getCell('W' . $row)->getValue(),
                                'Post_Auth_Fraud_Check' => $worksheet->getCell('X' . $row)->getValue(),
                                'Authorisation_Data' => $worksheet->getCell('Y' . $row)->getValue(),
                                'RRN' => $worksheet->getCell('Z' . $row)->getValue(),
                                'Authorised_Amount' => $worksheet->getCell('AA' . $row)->getValue(),
                                'Captured_Amount' => $worksheet->getCell('AB' . $row)->getValue(),
                                'Captured_Date' => date('Y-m-d', strtotime(str_replace('/', '-', $worksheet->getCell('AC' . $row)->getValue()))),
                                'Refund_Amount' => $worksheet->getCell('AD' . $row)->getValue(),
                                'Refund_Date' =>  date('Y-m-d', strtotime(str_replace('/', '-', $worksheet->getCell('AE' . $row)->getValue()))),
                                'Billing_Address' => $con->real_escape_string($worksheet->getCell('AF' . $row)->getValue()),
                                'Shipping_Address' => $con->real_escape_string($worksheet->getCell('AG' . $row)->getValue()),
                                'Cardholder_Name_o' => $worksheet->getCell('AH' . $row)->getValue(),
                                'Card_Number_o' => $worksheet->getCell('AI' . $row)->getValue(),
                                'Payment_Method_o' => $worksheet->getCell('AJ' . $row)->getValue(),
                                'Payment_Type_o' => $worksheet->getCell('AK' . $row)->getValue(),
                                'Card_Scheme_o' => $worksheet->getCell('AL' . $row)->getValue(),
                                'Expiry_o' => $worksheet->getCell('AM' . $row)->getValue(),
                                'ECI_Value_o' => $worksheet->getCell('AN' . $row)->getValue(),
                                'County_of_Issue_o' => $con->real_escape_string($worksheet->getCell('AO' . $row)->getValue()),
                                'Issuing_Organization_o' => $con->real_escape_string($worksheet->getCell('AP' . $row)->getValue()),
                                'Issuing_Organization_phone_number_o' => $worksheet->getCell('AQ' . $row)->getValue(),
                                'Issuing_Organization_Website_o' => $worksheet->getCell('AR' . $row)->getValue(),
                                'Type_of_Card_o' => $worksheet->getCell('AS' . $row)->getValue(),
                                'Category_Of_Card_o' => $worksheet->getCell('AT' . $row)->getValue(),
                                'Cardholder_Name_t' => $worksheet->getCell('AU' . $row)->getValue(),
                                'Card_Number_t' => $worksheet->getCell('AV' . $row)->getValue(),
                                'Payment_Method_t' => $worksheet->getCell('AW' . $row)->getValue(),
                                'Payment_Type_t' => $worksheet->getCell('AX' . $row)->getValue(),
                                'Card_Scheme_t' => $worksheet->getCell('AY' . $row)->getValue(),
                                'Expiry_t' => $worksheet->getCell('AZ' . $row)->getValue(),
                                'ECI_Value_t' => $worksheet->getCell('BA' . $row)->getValue(),
                                'County_of_Issue_t' => $worksheet->getCell('BB' . $row)->getValue(),
                                'Issuing_Organization_t' => $worksheet->getCell('BC' . $row)->getValue(),
                                'Issuing_Organization_phone_number_t' => $worksheet->getCell('BD' . $row)->getValue(),
                                'Issuing_Organization_Website_t' => $worksheet->getCell('BE' . $row)->getValue(),
                                'Type_of_Card_t' => $worksheet->getCell('BF' . $row)->getValue(),
                                'Category_Of_Card_t' => $worksheet->getCell('BG' . $row)->getValue(),
                                'ticket' => getTicketStatus($con, $transaction_id, $dubaidate_time),
                                'createdon' => $dubaidate_time
                            ];


                            $draw_ins = insert($con, "network_order_lookup", "", $look_arr, "", "", "");
                        }
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
