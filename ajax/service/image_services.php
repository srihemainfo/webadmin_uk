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




if ($method == "image_list") {
    $result = [];
    $formdate = $_POST['formdate'];
    if ($formdate != '') {

        $fd = date("Y-m-d", strtotime($formdate));

        $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$fd 23:59:59' ";
    }

    $image_list = select_query($con, "past_result", "", "`deletes`='0' AND  $contype", "", "");
    if ($image_list['nr'] > 0) {
        foreach ($image_list['result'] as $key => $value) {
            $url = $baseurl . "assets/pastdrawresult/" . $value['image_url'];
            $result['result'][] = array("id" => $value['id'], "date" =>  str_pad($value['createdon'], 7, "0", STR_PAD_LEFT), "drawid" => str_pad($value['draw_id'], 7, "0", STR_PAD_LEFT), "url" => '<a class="btn text-danger btn-sm"><span style="cursor: pointer;color: #136e1a !important;" class="fe fe-eye"" onclick="preview(' . "'$url'" . ')">Preview</span></a>', "product" => $value['product_id'],);
        }
        $result['type'] = 1;
    } else {
        $result['type'] = 0;
    }

    echo json_encode($result);
} else if ($method == "delete_pastresult_now") {

    $drawid = $_POST['id'];
    $result = [];
    $draw_arr = array("deletes" => '1 ');
    $Inv_update = update($con, "past_result", "`id` = '$drawid' and `deletes`='0'", $draw_arr, "", "", "", "");
    $errors = $Inv_update['errors'];
    if ($errors != "") {
        $result["type"] = "0";
        $result["result"] = $errors;
    } else {
        $result["type"] = "1";
        $result["result"] = "Deleted Successfully!";
    }

    echo json_encode($result);
} else if ($method == "homeupimage_list") {
    $result = [];
    $formdate = $_POST['formdate'];
    if ($formdate != '') {

        $fd = date("Y-m-d", strtotime($formdate));

        $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$fd 23:59:59' ";
    }

    $image_list = select_query($con, "homeview_images", "", "`deletes`='0' AND  $contype", "", "");
    if ($image_list['nr'] > 0) {
        foreach ($image_list['result'] as $key => $value) {
            $url = $baseurl . $value['image_url'];
            $result['result'][] = array("id" => $value['id'], "drawid" => $value['draw_id'], "date" =>  str_pad($value['createdon'], 7, "0", STR_PAD_LEFT),  "url" => '<a class="btn text-danger btn-sm"><span style="cursor: pointer;color: #136e1a !important;" class="fe fe-eye"" onclick="preview(' . "'$url'" . ')">Preview</span></a>', "product" => $value['product_id']);
        }
        $result['type'] = 1;
    } else {
        $result['type'] = 0;
    }

    echo json_encode($result);
} else if ($method == "delete_homeimage_now") {

    $drawid = $_POST['id'];
    $result = [];
    $draw_arr = array("deletes" => '1');
    $Inv_update = update($con, "homeview_images", "`id` = '$drawid' and `deletes`='0'", $draw_arr, "", "", "", "");
    $errors = $Inv_update['errors'];
    if ($errors != "") {
        $result["type"] = "0";
        $result["result"] = $errors;
    } else {
        $result["type"] = "1";
        $result["result"] = "Deleted Successfully!";
    }

    echo json_encode($result);
}
