<?php



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


if ($method == "fm_contest_info") {
    $result = [];

    $sql = mysqli_query($con, "SELECT f.id, f.reg_date, f.pre_winner_id, f.straight_no, f.comb_no, f.winner_id, f.winning_my3number, f.prize_amt, f.createdon, u.name, u.email, u.mobile FROM `fm_info` AS f LEFT JOIN user_register AS u on u.id = f.winner_id WHERE f.deletes = '0' ORDER BY f.reg_date ASC;");
    if (mysqli_num_rows($sql) > 0) {
        $result['type'] = 1;
        $result['result'] = mysqli_fetch_all($sql, MYSQLI_ASSOC);
    } else {
        $result['type'] = 0;
        $result['result'] = [];
    }


    echo json_encode($result);
} else if ($method == 'changeMy3Number') {
    $result = [];
    $my3number = $_POST['my3number'];
    $id = $_POST['id'];
    $winningNumber = $_POST['winningNumber'];

    if ($id == '' || $id == 'null') {
        $result['type'] = 0;
        $result['result'] = 'The ID Missing. Kindly refresh and Try Again!';
        goto resultGVI;
    }

    if ($my3number == '' || $my3number == 'null') {
        $result['type'] = 0;
        $result['result'] = 'Kindly Enter your My3Number!';
        goto resultGVI;
    }
    if ($winningNumber == '' || $winningNumber == 'null') {
        $result['type'] = 0;
        $result['result'] = 'Kindly Enter your winning My3Number!';
        goto resultGVI;
    }

    $first = substr($my3number, 0, 1);
    $second = substr($my3number, 1, 1);
    $third = substr($my3number, 2, 2);

    $arr_3 = [];
    // $l1 = $first . $second . $third;
    // array_push($arr_3, $l1);
    $l2 = $third . $second . $first;
    if (in_array($l2,  $arr_3)) {
        // $l2 = 'XXX';
        // array_push($arr_3, $l2);
    } else {
        array_push($arr_3, $l2);
    }

    $l3_1 = $third . $first . $second;
    if (in_array($l3_1,  $arr_3)) {
        // $l3_1 = 'XXX';
        // array_push($arr_3, $l3_1);
    } else {
        array_push($arr_3, $l3_1);
    }

    $l3_2 = $second . $third . $first;
    if (in_array($l3_2,  $arr_3)) {
        // $l3_2 = 'XXX';
        // array_push($arr_3, $l3_2);
    } else {
        array_push($arr_3, $l3_2);
    }

    $l3_3 = $second . $first . $third;
    if (in_array($l3_3,  $arr_3)) {
        // $l3_3 = 'XXX';
        // array_push($arr_3, $l3_3);
    } else {
        array_push($arr_3, $l3_3);
    }

    $l3_4 = $first . $third . $second;
    if (in_array($l3_4,  $arr_3)) {
        // $l3_4 = 'XXX';
        // array_push($arr_3, $l3_4);
    } else {
        array_push($arr_3, $l3_4);
    }
    // var_dump($arr_3);
    // die;
    if (!in_array($winningNumber, $arr_3)) {
        $result['type'] = 0;
        $result['result'] = 'The winning My3Number does not match!';
        goto resultGVI;
    }

    $combo = implode(',', $arr_3);

    $update_arr = ['comb_no' => $combo, 'straight_no' => $my3number, 'winning_my3number' => $winningNumber];
    $email_config = update($con, "fm_info", "`id` = '$id' and `deletes`='0'", $update_arr, "", "", "", "");
    $errors = $email_config['errors'];
    if ($errors != "") {
        $result["type"] = "0";
        $result["result"] = 'Update Failed!';
        goto resultGVI;
    } else {
        $result["type"] = "1";
        $result["result"] = 'Updated Successfully';
        goto resultGVI;
    }

    resultGVI:
    echo json_encode($result);
} else if ($method == "fm_participation_list") {
    $result = [];
    $contestDate = $_POST['contestDate'];

    if ($contestDate == '' || $contestDate == 'null') {
        $result['type'] = 0;
        $result['result'] = 'Kindly Select contest Date!';
        goto FVIresult;
    }

    $getComboQuery = mysqli_query($con, "SELECT CONCAT('\'', REPLACE(comb_no, ',', '\', \''), '\'') AS 'combo', winning_my3number
        FROM `fm_info`
        WHERE `reg_date` = '$contestDate' AND `deletes` = '0' ORDER BY `id` ASC LIMIT 1;");

    if (mysqli_num_rows($getComboQuery) > 0) {
        $row = mysqli_fetch_assoc($getComboQuery);

        $combo = $row['combo'];
        $winning_my3number = $row['winning_my3number'];

        if ($combo == '' || $combo == 'null') {
            $result['type'] = 0;
            $result['result'] = 'The Combination My3Number Missing!';
            goto FVIresult;
        }

        $newQuery = "SELECT f.id, f.user_id, f.my3number, f.reg_date, f.createdon, u.name, u.lname, u.email, u.mobile, fm.winner_id, fm.prize_amt FROM `fm_register` AS f 
       LEFT JOIN user_register AS u ON f.user_id = u.id
       LEFT JOIN fm_info AS fm ON fm.reg_date = f.reg_date
       WHERE f.`reg_date` = '$contestDate' AND f.`deletes` = '0'";

        // var_dump($_POST['winnersOnly']);

        if ($_POST['winnersOnly'] == 'true') {
            $newQuery .= " AND f.`my3number` IN (
                $combo
            )";
        } else {
            $newQuery .= " AND f.`my3number` = '$winning_my3number'";
        }

        $newQuery .= " ORDER BY f.`id` ASC";

        // var_dump($newQuery);
        // die;

        $getPart = mysqli_query($con, $newQuery);

        if (mysqli_num_rows($getPart) > 0) {
            $result['type'] = '1';
            $result['result'] = mysqli_fetch_all($getPart, MYSQLI_ASSOC);
            goto FVIresult;
        } else {
            $result['type'] = '0';
            $result['result'] = [];
            goto FVIresult;
        }
    } else {
        $result['type'] = '0';
        $result['result'] = [];
        goto FVIresult;
    }




    FVIresult:
    echo json_encode($result);
} else if ($method == 'getWinnerData') {

    $result = [];
    $contestDate = $_POST['winnerlistData'];

    if ($contestDate == '' || $contestDate == 'null') {
        $result['type'] = 0;
        $result['result'] = 'Participant Date is missing. Kindly refresh and try again!';
        goto resultGHIJ;
    }

    $getComboQuery = mysqli_query($con, "SELECT CONCAT('\'', REPLACE(comb_no, ',', '\', \''), '\'') AS 'combo', winning_my3number
    FROM `fm_info`
    WHERE `reg_date` = '$contestDate' AND `deletes` = '0' ORDER BY `id` ASC LIMIT 1;");

    if (mysqli_num_rows($getComboQuery) > 0) {
        $row = mysqli_fetch_assoc($getComboQuery);

        $combo = $row['combo'];
        $winning_my3number = $row['winning_my3number'];

        if ($combo == '' || $combo == 'null') {
            $result['type'] = 0;
            $result['result'] = 'The Combination My3Number Missing!';
            goto resultGHIJ;
        }

        $newQuery = "SELECT f.id, f.user_id, f.my3number, f.reg_date, f.createdon, u.name, u.lname, u.email, u.mobile, fm.winner_id, fm.prize_amt FROM `fm_register` AS f 
   LEFT JOIN user_register AS u ON f.user_id = u.id
   LEFT JOIN fm_info AS fm ON fm.reg_date = f.reg_date
   WHERE f.`reg_date` = '$contestDate' AND f.`deletes` = '0'";

        // var_dump($_POST['winnersOnly']);

        // if ($_POST['winnersOnly'] == 'true') {
        //     $newQuery .= " AND f.`my3number` IN (
        //     $combo
        // )";
        // } else {
        $newQuery .= " AND f.`my3number` = '$winning_my3number'";
        // }

        $newQuery .= " ORDER BY RAND() LIMIT 1;";

        // var_dump($newQuery);
        // die;

        $getPart = mysqli_query($con, $newQuery);

        if (mysqli_num_rows($getPart) > 0) {
            // $result['type'] = '1';
            $partIDAll = mysqli_fetch_assoc($getPart);
            // goto resultGHIJ;
            $partID = $partIDAll['id'];
            if ($partID == '' || $partID == 'null') {
                $result['type'] = 0;
                $result['result'] = 'Participant id is missing. Kindly refresh and try again!';
                goto resultGHIJ;
            }

            $getPart = mysqli_query($con, "SELECT f.id, f.user_id, f.my3number, f.reg_date, f.createdon, u.name, u.lname, u.email, u.mobile, fm.winner_id, fm.prize_amt FROM `fm_register` AS f 
            LEFT JOIN user_register AS u ON f.user_id = u.id
            LEFT JOIN fm_info AS fm ON fm.reg_date = f.reg_date
            WHERE f.`id` = '$partID' AND f.`deletes` = '0' ORDER BY f.`id` ASC LIMIT 1;");

            if (mysqli_num_rows($getPart) > 0) {

                $winnerData = mysqli_fetch_assoc($getPart);
                if ($winnerData['winner_id'] > 0) {
                    $result['type'] = 0;
                    $result['result'] = 'The Winner Already Announced!';
                    goto resultGHIJ;
                }

                $result['type'] = '1';
                $result['result'] = 'Winner Details Collected!';
                $result['winnerData'] = $winnerData;
                goto resultGHIJ;
            } else {
                $result['type'] = '0';
                $result['result'] = 'The Participant not found!';
                goto resultGHIJ;
            }
        } else {
            $result['type'] = '0';
            $result['result'] = 'The Participant not found!';
            goto resultGHIJ;
        }
    } else {
        $result['type'] = '0';
        $result['result'] = 'The winning nunber didn`t get!';
        goto resultGHIJ;
    }

    resultGHIJ:
    echo json_encode($result);
} else if ($method == 'announceFMWinner') {

    $result = [];
    $partID = $_POST['partID'];

    if ($partID == '' || $partID == 'null') {
        $result['type'] = 0;
        $result['result'] = 'Participant ID is missing. Kindly refresh and try again!';
        goto resultGHIJ1;
    }

    $getPart = mysqli_query($con, "SELECT f.id, f.user_id, f.my3number, f.reg_date, f.createdon, u.name, u.lname, u.email, u.mobile, u.bonus_points, fm.winner_id, fm.prize_amt, fm.id AS 'fminfoid' FROM `fm_register` AS f 
    LEFT JOIN user_register AS u ON f.user_id = u.id
    LEFT JOIN fm_info AS fm ON fm.reg_date = f.reg_date
    WHERE f.`id` = '$partID' AND f.`deletes` = '0' ORDER BY f.`id` ASC LIMIT 1;");

    if (mysqli_num_rows($getPart) > 0) {

        $winnerData = mysqli_fetch_assoc($getPart);
        $prize_amt = (int)$winnerData['prize_amt'];
        $user_id = $winnerData['user_id'];
        $bonus_points = $winnerData['bonus_points'];
        $selectedMy3number = $winnerData['my3number'];
        $email = $winnerData['email'];
        $fminfoid =  $winnerData['fminfoid'];
        $fullName = $winnerData['name'] . ' ' . (isset($winnerData['lname']) ? $winnerData['lname'] : '');

        if ($winnerData['winner_id'] > 0) {
            $result['type'] = 0;
            $result['result'] = 'The Winner Already Announced!';
            goto resultGHIJ1;
        }

        if ($prize_amt < 1) {
            $result['type'] = 0;
            $result['result'] = 'The Prize Amount Required More Than 1 AED!';
            goto resultGHIJ1;
        }
        

        $fmUpdateArr = [
            'pre_winner_id' => $user_id,
            // 'winning_my3number' => $selectedMy3number
        ];
        $fmUpdate = update($con, "fm_info", "`id`='$fminfoid'  and `deletes`='0'", $fmUpdateArr, "", "", "", "");
        $errors = $fmUpdate['errors'];
        if ($errors != "") {
            $result["type"] = "0";
            $result["result"] = $errors;
        } else {


           

            $result['type'] = '1';
            $result['result'] = 'The winner has been successfully selected!';
            // $result['winnerData'] = $winnerData;
            goto resultGHIJ1;
        }
       
    } else {
        $result['type'] = '0';
        $result['result'] = [];
        goto resultGHIJ1;
    }





    resultGHIJ1:
    echo json_encode($result);
} else if ($method == "fm_winner_list") {
    $result = [];
    $contestDate = $_POST['contestDate'];

    if ($contestDate == '' || $contestDate == 'null') {
        $result['type'] = 0;
        $result['result'] = 'Kindly Select contest Date!';
        goto FVIresultA;
    }

    $getComboQuery = mysqli_query($con, "SELECT winning_my3number, winner_id
        FROM `fm_info`
        WHERE `reg_date` = '$contestDate' AND `deletes` = '0' AND winner_id != 0 ORDER BY `id` ASC LIMIT 1;");

    if (mysqli_num_rows($getComboQuery) > 0) {
        $row = mysqli_fetch_assoc($getComboQuery);

        $combo = $row['winning_my3number'];
        $uwinner_id = $row['winner_id'];

        if ($combo == '' || $combo == 'null') {
            $result['type'] = 0;
            $result['result'] = [];
            goto FVIresultA;
        }

        $getPart = mysqli_query($con, "SELECT f.id, f.user_id, f.my3number, f.reg_date, f.createdon, u.name, u.lname, u.email, u.mobile, fm.winner_id, fm.prize_amt FROM `fm_register` AS f 
        LEFT JOIN user_register AS u ON f.user_id = u.id
        LEFT JOIN fm_info AS fm ON fm.reg_date = f.reg_date
        WHERE f.`my3number` IN (
            $combo
        ) AND f.`reg_date` = '$contestDate' AND f.`deletes` = '0' AND fm.winner_id != 0  AND f.user_id = '$uwinner_id' ORDER BY f.`id` ASC;");

        if (mysqli_num_rows($getPart) > 0) {
            $result['type'] = '1';
            $result['result'] = mysqli_fetch_all($getPart, MYSQLI_ASSOC);
            goto FVIresultA;
        } else {
            $result['type'] = '0';
            $result['result'] = [];
            goto FVIresultA;
        }

    
    } else {
        $result['type'] = '0';
        $result['result'] = [];
        goto FVIresultA;
    }




    FVIresultA:
    echo json_encode($result);
}
