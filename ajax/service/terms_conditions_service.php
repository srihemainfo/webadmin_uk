<?php
// Date        Developer     Changes



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

$result = array();


if ($method == "terms_list") {
    $result = [];
    $faq = select_query($con, "termsconditions", "", "`deletes` = '0' ORDER BY `orderno` ASC", "", "");
    if ($faq['nr'] > 0) {
        foreach ($faq['result'] as $key => $value) {
            $terms = strip_tags($value['name']);

            if (strlen($terms) > 500) {

                // truncate string
                $termsCut = substr($terms, 0, 500);
                $endPoint = strrpos($termsCut, ' ');

                //if the string doesn't contain any space then it will cut without word basis.
                $terms = $endPoint ? substr($termsCut, 0, $endPoint) : substr($termsCut, 0);
                $terms .= '.....';
            }
            $result[] = [
                'id' => $value['id'],
                'order' => $value['orderno'],
                'terms' => $terms
            ];
        }
    }
    echo json_encode($result);
} else if ($method == "terminsert") {
    $result = [];

    $term_arr = [
        'orderno' => $_POST['orderno'],
        'name' => $_POST['terms'],
        'createdon' => $dubaidate_time,
        'updatedon' => $dubaidate_time
    ];
    $faquser_ins = insert($con, "termsconditions", "", $term_arr, "", "", "");
    if ($faquser_ins['id'] != '') {
        $result["type"] = "1";
        $result["result"] = 'Data Added Successfully';
        goto resultSI;
    } else {
        $result["type"] = "0";
        $result["result"] = 'Data Failed';
        goto resultSI;
    }

    resultSI:
    echo json_encode($result);
} else if ($method == "delete_result_now") {

    $id = $_POST['id'];
    $result = [];
    $data_arr = array("deletes" => '1');
    $Inv_update = update($con, "termsconditions", "`id` = '$id' and `deletes`='0'", $data_arr, "", "", "", "");
    $errors = $Inv_update['errors'];
    if ($errors != "") {
        $result["type"] = "0";
        $result["result"] = $errors;
    } else {

        $termsconditions = select_query($con, "termsconditions", "", "`deletes` = '0' ORDER BY `orderno` ASC", "", "");

        if ($termsconditions['nr'] > 0) {
            $orderNo = 1;
            foreach ($termsconditions['result'] as $key => $value) {
                $order_arr['orderno'] = $orderNo;
                $id = $value['id'];
                setupdate($con, "termsconditions", "`id` = '$id' and `deletes`='0'", $order_arr, "", "", "", "");
                $orderNo++;
            }
        }

        $result["type"] = "1";
        $result["result"] = "Deleted Successfully!";
    }

    echo json_encode($result);
} else if ($method == "termupdate") {
    $result = [];

    $getFaqData = select_query($con, "termsconditions", "", "id=$_POST[id]", "", "");
    $oldOrderNo = $getFaqData['result'][0]['orderno'];
    $orderNo = BlockSQLInjection($_POST["orderno"]);

    // $orderNo = $_POST['orderno'];

    $faqId = select_top_name($con, "termsconditions", "id", "`orderno`='$orderNo' ", "id", "");


    $faq_arrup = [
        'orderno' => $_POST['orderno'],
        'name' => $_POST['terms'],
        'updatedon' => $dubaidate_time
    ];
    $faquser_ins = update($con, "termsconditions", "`id` = '$_POST[id]' and `deletes`='0'", $faq_arrup, "", "", "", "");
    if ($faquser_ins) {
        $order_arr['orderno'] = $oldOrderNo;
        $update_order_no = setupdate($con, "termsconditions", "`id` = '$faqId' and `deletes`='0'", $order_arr, "", "", "", "");
        $result["type"] = "1";
        $result["result"] = 'Data Updated Successfully';
        goto resultSI1;
    } else {
        $result["type"] = "0";
        $result["result"] = 'Data Updated Failed';
        goto resultSI1;
    }

    resultSI1:
    echo json_encode($result);
} elseif ($method == 'update_orderno') {

    $orderNo = 1;
    $position = $_POST['position'];
    // $position = BlockSQLInjection($_POST["position"]);

    foreach ($position as $k => $v) {
        // echo $v.'<br>';
        $order_arr['orderno'] = $orderNo;
        update($con, "termsconditions", "`id` = '$v' and `deletes`='0'", $order_arr, "", "", "", "");
        $orderNo++;
    }
}
