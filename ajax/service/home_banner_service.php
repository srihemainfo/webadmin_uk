<?php


include '../../include/shi-config.php';
include '../../include/functions.php';

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
// echo ($method);
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



if ($method == "home_banner") {
    $result = [];
    // $formdate = $_POST['formdate'];
    // $todate = $_POST['todate'];
    // $contestDate = $_POST['contestDate'];
    $banner = $_POST['banner'];


    if ($banner != '') {
        $sqlGet = mysqli_query($con, "SELECT * FROM `home_banner` WHERE  `device_type` = '$banner' AND `deletes` = '0'  ORDER BY order_by ASC;");

        if (mysqli_num_rows($sqlGet) > 0) {
            $result = mysqli_fetch_all($sqlGet, MYSQLI_ASSOC);
        }
    }

    echo json_encode($result);
} elseif ($method == 'statusbutton') {

    $result = [];
    $id = $_POST['id'];
    $status = $_POST['status'];




    $updateQuery = "UPDATE `home_banner` SET `status` = '" . (($status == 'Active') ? 'Inactive' : 'Active') . "' WHERE `id` = '$id'";


    $runQuery = mysqli_query($con, $updateQuery);

    if ($runQuery) {
        // echo "Status updated successfully";
        $result["type"] = 1;
        $result["result"] = "Status updated successfully.";
    } else {
        // echo "Error updating status: " . mysqli_error($your_db_connection_variable);
        $result["type"] = 0;
        $result["result"] = "Status updated process failed.";
    }



    echo json_encode($result);
}

// Assuming you have the database connection established=============================

elseif ($method == 'seachbutton') {
    $id = $_POST['id'];
    $myorder_by_value = $_POST['ordeer_by'];

    // Fetch the current ordeer_by value from the 


    $sql = "SELECT * FROM `home_banner` WHERE `ordeer_by` = '$_POST[ordeer_by]' AND `deletes` = 0";
    $existcheck = mysqli_query($con, $sql);
    if (mysqli_num_rows($existcheck) > 0) {
        $result["type"] = "0";
        $result["result"] = "$_POST[ordeer_by]this number already exists";
        goto si;
    } else {


        $getOrdeerByQuery = "SELECT ordeer_by FROM home_banner WHERE ordeer_by != $myorder_by_value";
        $getOrdeerByResult = mysqli_query($rcon, $getOrdeerByQuery);



        if ($getOrdeerByResult) {
            if (mysqli_num_rows($getOrdeerByResult) > 0) {

                $updateQuery = "UPDATE `home_banner` SET `ordeer_by` = '$myorder_by_value' WHERE `id` = '$id'";
                $result1 = mysqli_query($rcon, $updateQuery);
                if ($result1) {

                    $result["type"] = "1";
                    $result["result"] = "Status updated successfully";
                    goto si;

                    // echo "Status updated successfully";
                } else {
                    echo "Error updating status: " . mysqli_error($rcon);
                }
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error fetching current Order By value: ' . mysqli_error($rcon)]);
        }
    }
    si:
    echo json_encode($result);
} elseif ($method == 'deleteinfo') {
    $result = [];
    $id = $_POST['id'];
    $urllevel = $_POST['urllevel'];


    // $sql = "UPDATE `home_banner` SET `deletes` = '1' WHERE `home_banner`.`id` = $id;";
    $existcheck = mysqli_query($con, "UPDATE `home_banner` SET `deletes` = '1' WHERE `home_banner`.`id` = $id;");
    if ($existcheck) {
        $result = [
            'type' => 1,
            'result' => 'Image deleted successfully!'
        ];
    } else {
        $result = [
            'type' => 0,
            'result' => 'Deleted process failed!'
        ];
        // echo "Error updating status: " . mysqli_error($rcon);
    }


    // var_dump($id,$urllevel);die;
    echo json_encode($result);
} elseif ($method == 'updateOrder') {
    $result = [];
    $id = $_POST['id'];
    $order = $_POST['order'];
    $deviceType = $_POST['deviceType'];


    $existcheck = mysqli_query($con, "UPDATE `home_banner` SET `order_by` = '$order' WHERE `id` = $id AND `deletes` = '0' AND `device_type` = '$deviceType';");
    if ($existcheck) {
        $result = [
            'type' => 1,
            'result' => 'The Order updated successfully!'
        ];
    } else {
        $result = [
            'type' => 0,
            'result' => 'The Order update process failed!'
        ];
    }


    // var_dump($id,$urllevel);die;
    echo json_encode($result);
} else if ($method == 'changeTimeBannar') {
    $result = [];

    $id = $_POST['id'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];


    if ($id == '' || $id == null) {
        $result = [
            'type' => 0,
            'result' => 'The ID has been missing!'
        ];
        goto resultFVI123;
    }

    $updateCon = '';

    // $startTime = date("YYYY-MM-DD HH:mm:ss", strtotime($startTime));

    if (isset($startTime) && $startTime != '') {
        $updateCon .= "`start_date` = '$startTime' ";
    }

    if (isset($endTime) && $endTime != '') {
        $updateCon .= "`end_date` = '$endTime' ";
    }

    if ($updateCon == '' || $updateCon == null) {
        $result = [
            'type' => 0,
            'result' => 'The Date & Time update process failed!'
        ];
        goto resultFVI123;
    }

    // var_dump("UPDATE `home_banner` SET $updateCon WHERE `id` = $id AND `deletes` = '0';");
    // die;

    $existcheck = mysqli_query($con, "UPDATE `home_banner` SET $updateCon WHERE `id` = $id AND `deletes` = '0';");
    if ($existcheck) {
        $result = [
            'type' => 1,
            'result' => 'The Date & Time updated successfully!'
        ];
    } else {
        $result = [
            'type' => 0,
            'result' => 'The Date & Time update process failed!'
        ];
    }


    resultFVI123:
    echo json_encode($result);
}
