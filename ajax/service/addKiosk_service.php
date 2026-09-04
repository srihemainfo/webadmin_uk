<?php

/**

 * Date        Developer     Modification

 * 18/8/23      divya      developed all kticket

 */

include '../../include/shi-config.php';

include '../../include/functions.php';

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



if ($method == "addKiosk") {

    $result = [];
    $kioskID = $_POST['kioskID'];
    $location = $_POST['location'];
    $currentUser = $_SESSION['memid'];
    $area = $_POST['area'];
    $country = $_POST['country'];


    if ($currentUser == '') {
        $result['type'] = 0;
        $result['result'] = 'Login Required!';
        goto gviRetrun;
    }
    if ($kioskID == '') {
        $result['type'] = 0;
        $result['result'] = 'Kindly Enter The Kiosk id!';
        goto gviRetrun;
    }
    if ($location == '') {
        $result['type'] = 0;
        $result['result'] = 'Kindly Enter The Kiosk location!';
        goto gviRetrun;
    }
    if ($area == '') {
        $result['type'] = 0;
        $result['result'] = 'Kindly Enter The Kiosk Area!';
        goto gviRetrun;
    }
    if ($country == '') {
        $result['type'] = 0;
        $result['result'] = 'Kindly Enter The Kiosk Country!';
        goto gviRetrun;
    }

    $kiosk_machines = select_query($con, "kiosk_machines", "", "`kiosk_id` LIKE '$kioskID'", "", "");
    if ($kiosk_machines['nr'] > 0) {
        $result['type'] = 0;
        $result['result'] = 'The Kiosk Id has been already exists!';
        goto gviRetrun;
    }

    $kiosk_arr = [
        'ownedby' => $currentUser,
        'kiosk_id' => $kioskID,
        'location' => $location,
        'is_active' => '0',
        'deletes' => '0',
        'createdon' => $dubaidate_time,
        'country' => $country,
        'area' => $area
    ];

    $kiosk_machines_ins = insert($con, "kiosk_machines", "", $kiosk_arr, "", "", "");
    if ($kiosk_machines_ins['id'] != '') {
        $result['type'] = 1;
        $result['result'] = 'The Kiosk machine added successfully!';
        goto gviRetrun;
    } else {
        $result['type'] = 0;
        $result['result'] = 'The process failed! Try again!';
        goto gviRetrun;
    }

    gviRetrun:
    echo json_encode($result);
} else if ($method == "changeStatusKiosk") {

    $result = [];
    $kioskID = $_POST['KioskID'];
    $status = $_POST['status'];
    $currentUser = $_SESSION['memid'];

    if ($currentUser == '') {
        $result['type'] = 0;
        $result['result'] = 'Login Required!';
        goto gviRetrun12d;
    }
    if ($kioskID == '') {
        $result['type'] = 0;
        $result['result'] = 'The Kiosk ID Missing!';
        goto gviRetrun12d;
    }
    if ($status == '') {
        $result['type'] = 0;
        $result['result'] = 'The Status Has been Missing!';
        goto gviRetrun12d;
    }

    $kiosk_machines = select_query($con, "kiosk_machines", "", "`id` = '$kioskID' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", "", "");
    if ($kiosk_machines['nr'] < 1) {
        $result['type'] = 0;
        $result['result'] = 'The Kiosk Id has been missing in our DB!';
        goto gviRetrun12d;
    }

    if ($kiosk_machines['result'][0]['is_active'] == $status) {
        $result['type'] = 0;
        $result['result'] = 'The same status has been selected!';
        goto gviRetrun12d;
    }

    // Log
    error_log_new($con, getUserIP(), 'Kiosk_Status_update', $currentUser, '', '', 'The Kiosk Machine Status Has been updated', json_encode($_REQUEST), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);

    $inv_arr = ["is_active" => $status];
    $Inv_update = update($con, "kiosk_machines", "`id` = '$kioskID' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", $inv_arr, "", "", "", "");

    $errors = $Inv_update['errors'];

    if ($errors != "") {

        $result["type"] = "0";

        // $result["result"] = $errors;

        $result["result"] = "Update Failed. Kindly Contact to Admin Team!";

        goto gviRetrun12d;
    } else {

        $result['type'] = 1;
        $result['result'] = 'The Kiosk machine status updated successfully!';
        goto gviRetrun12d;
    }



    gviRetrun12d:
    echo json_encode($result);
} else if ($method == 'kioskMachineList') {
    $result = [];
    $currentUser = $_SESSION['memid'];

    if ($currentUser == '') {
        goto gviRetrun123;
    }

    $query = "SELECT * FROM `kiosk_machines` WHERE deletes = '0' AND `ownedby` = $currentUser;";
    $getKTTicket = mysqli_query($con, $query);
    $proArr = mysqli_fetch_all($getKTTicket, MYSQLI_ASSOC);
    if (count($proArr) > 0) {
        $result = $proArr;
    }
    gviRetrun123:
    echo json_encode($result);
} else if ($method == "UpdateKiosk") {

    $result = [];
    $id = $_POST['id'];
    $currentUser = $_SESSION['memid'];
    $location = $_POST['location'];
    $area = $_POST['area'];
    $country = $_POST['country'];


    if ($currentUser == '') {
        $result['type'] = 0;
        $result['result'] = 'Login Required!';
        goto gviRetrunMM;
    }
    if ($id == '') {
        $result['type'] = 0;
        $result['result'] = 'Kindly Enter The Kiosk id!';
        goto gviRetrunMM;
    }
    if ($location == '') {
        $result['type'] = 0;
        $result['result'] = 'Kindly Enter The Kiosk location!';
        goto gviRetrunMM;
    }
    if ($area == '') {
        $result['type'] = 0;
        $result['result'] = 'Kindly Enter The Kiosk Area!';
        goto gviRetrunMM;
    }
    if ($country == '') {
        $result['type'] = 0;
        $result['result'] = 'Kindly Enter The Kiosk Country!';
        goto gviRetrunMM;
    }

    $kiosk_machines = select_query($con, "kiosk_machines", "", "`id` LIKE '$id'", "", "");
    if ($kiosk_machines['nr'] < 1) {
        $result['type'] = 0;
        $result['result'] = 'The Kiosk has been missing!';
        goto gviRetrunMM;
    }

    $kiosk_arr = [
        'location' => $location,
        'country' => $country,
        'area' => $area
    ];

    $Inv_update = update($con, "kiosk_machines", "`id` = '$id' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1", $kiosk_arr, "", "", "", "");
    $errors = $Inv_update['errors'];
    if ($errors != "") {
        $result["type"] = "0";
        // $result["result"] = $errors;
        $result["result"] = "Update Failed. Kindly Contact to Admin Team!";
        goto gviRetrunMM;
    } else {
        $result['type'] = 1;
        $result['result'] = 'The Kiosk machine details successfully Updated!';
        goto gviRetrunMM;
    }

    gviRetrunMM:
    echo json_encode($result);
}
