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



if ($method == "faq_list") {
    $result = [];
    $faq = select_query($con, "faq", "", "`deletes` = '0' ORDER BY `orderno` ASC", "", "");
    if ($faq['nr'] > 0) {
        foreach ($faq['result'] as $key => $value) {

            $result[] = [
                'id' => $value['id'],
                'order' => $value['orderno'],
                'question' => $value['ques'],
                'answer' => $value['ans'],
            ];
        }
    }
    echo json_encode($result);
} else if ($method == "faqinsert") {
    $result = [];

    $faq_arr = [
        'orderno' => $_POST['orderno'],
        'ques' => $_POST['question'],
        'ans' => $_POST['answers'],
        'createdon' => $dubaidate_time,
        'updatedon' => $dubaidate_time
    ];
    $faquser_ins = insert($con, "faq", "", $faq_arr, "", "", "");
    if ($faquser_ins['id'] != '') {
        $result["type"] = "1";
        $result["result"] = 'FAQ Added Successfully';
        goto resultSI;
    } else {
        $result["type"] = "0";
        $result["result"] = 'FAQ Failed';
        goto resultSI;
    }

    resultSI:
    echo json_encode($result);
} else if ($method == "delete_result_now") {

    $drawid = $_POST['id'];
    $result = [];
    $draw_arr = array("deletes" => '1');
    $Inv_update = update($con, "faq", "`id` = '$drawid' and `deletes`='0'", $draw_arr, "", "", "", "");
    $errors = $Inv_update['errors'];
    if ($errors != "") {
        $result["type"] = "0";
        $result["result"] = $errors;
    } else {

        $faq = select_query($con, "faq", "", "`deletes` = '0' ORDER BY `orderno` ASC", "", "");

        if ($faq['nr'] > 0) {
            $orderNo = 1;
            foreach ($faq['result'] as $key => $value) {
                $order_arr['orderno'] = $orderNo;
                $id = $value['id'];
                setupdate($con, "faq", "`id` = '$id' and `deletes`='0'", $order_arr, "", "", "", "");
                $orderNo++;
            }
        }
        $result["type"] = "1";
        $result["result"] = "Deleted Successfully!";
    }

    echo json_encode($result);
} else if ($method == "faqupdate1") {
    $result = [];

    $getFaqData = select_query($con, "faq", "", "id=$_POST[id]", "", "");
    $oldOrderNo = $getFaqData['result'][0]['orderno'];
    $orderNo = BlockSQLInjection($_POST["orderno"]);

    // $orderNo = $_POST['orderno'];

    $faqId = select_top_name($con, "faq", "id", "`orderno`='$orderNo' ", "id", "");


    $faq_arrup = [
        'orderno' => $_POST['orderno'],
        'ques' => $_POST['question'],
        'ans' => $_POST['answers'],
        'updatedon' => $dubaidate_time
    ];
    $faquser_ins = update($con, "faq", "`id` = '$_POST[id]' and `deletes`='0'", $faq_arrup, "", "", "", "");
    if ($faquser_ins) {
        $order_arr['orderno'] = $oldOrderNo;
        $update_order_no = setupdate($con, "faq", "`id` = '$faqId' and `deletes`='0'", $order_arr, "", "", "", "");
        $result["type"] = "1";
        $result["result"] = 'FAQ Updated Successfully';
        goto resultSI1;
    } else {
        $result["type"] = "0";
        $result["result"] = 'FAQ Updated Failed';
        goto resultSI1;
    }

    resultSI1:
    echo json_encode($result);
} elseif ($method == 'update_orderno') {

    $orderNo = 1;
    $position = BlockSQLInjection($_POST["position"]);

    // $position = $_POST['position'];

    foreach ($position as $k => $v) {
        // echo $v.'<br>';
        $order_arr['orderno'] = $orderNo;
        update($con, "faq", "`id` = '$v' and `deletes`='0'", $order_arr, "", "", "", "");
        $orderNo++;
    }
}
