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

$role_id = $_SESSION['userinfo']['roll_id'];

$headers = apache_request_headers();
$result = array();

function generateNumericOTP($n)
{
    $generator = "1357902468";
    $result = "";
    for ($i = 1; $i <= $n; $i++) {
        $result .= substr($generator, (rand() % (strlen($generator))), 1);
    }
    return $result;
}

if ($method == "send_otp") {

    $result = [];
    $title = '';
    $emdata = '';
    $email = BlockSQLInjection($_POST["email"]);
    $mobile = BlockSQLInjection($_POST["mobile"]);
    $lname  = $_POST['lname'];
    $NewPassword = md5($_POST['Password']);

    // Capture permissions from frontend (comma-separated string of menu IDs)
    $permissions = isset($_POST['permissions']) ? BlockSQLInjection($_POST['permissions']) : '';

    if ($lname == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly enter last name";
        goto si;
    }

    if ($mobile == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly enter Mobile No";
        goto si;
    }

    if ($email == '') {
        $result["type"] = "0";
        $result["result"] = "Kindly enter Email ID";
        goto si;
    }

    $n = 4; // 4 OR 6 Only
    $otp = generateNumericOTP($n);

    $sql = "SELECT * FROM `user_register` WHERE `mobile` = '$_POST[mobile]' AND `status` = 0 AND `deletes` = 0";
    $existcheck = mysqli_query($con, $sql);
    if (mysqli_num_rows($existcheck) > 0) {
        $result["type"] = "0";
        $result["result"] = "Mobile number already exists";
        goto si;
    } else {
        if ($email == '') {
            goto SkipEmail;
        }

        $existcheck = mysqli_query($con, "SELECT * FROM `user_register` WHERE `email` = '$_POST[email]' AND `status` = 0 AND `deletes` = 0");
        if (mysqli_num_rows($existcheck) > 0) {
            $result["type"] = "0";
            $result["result"] = "Email already exists";
            goto si;
        } else {
            SkipEmail:
            $_SESSION['otp'] = $otp;
            $nationality = '';
            if ($_POST['nationlaity'] != '') {
                $nation = BlockSQLInjection($_POST["nationlaity"]);
                $inNation = select_top_name($con, "countries", "name", "`flag` = '1' AND `id` = '$nation' AND `name` != '' ORDER BY `id` DESC", "name", "");
                $nationality = $con->real_escape_string($inNation);
            }
            
            $residinglocation = '';
            if ($_POST['residinglocation'] != '') {
                $nation1 = BlockSQLInjection($_POST["residinglocation"]);
                $inNation1 = select_top_name($con, "countries", "name", "`flag` = '1' AND `id` = '$nation1' AND `name` != '' ORDER BY `id` DESC", "name", "");
                $residinglocation = $con->real_escape_string($inNation1);
            }

            $address = '';
            if ($_POST['billing_address'] != '') {
                $billadd = BlockSQLInjection($_POST["billing_address"]);
                $inState = select_top_name($con, "states", "name", "`flag` = '1' AND `id` = '$billadd' AND `name` != '' ORDER BY `id` DESC", "name", "");
                $address = $con->real_escape_string($inState);
            }

            $city = '';
            if ($_POST['billing_city'] != '') {
                $billcity = BlockSQLInjection($_POST["billing_city"]);
                $inCity = select_top_name($con, "cities", "name", "`flag` = '1' AND `id` = '$billcity' AND `name` != '' ORDER BY `id` DESC", "name", "");
                $city = $con->real_escape_string($inCity);
            }

            // Insert into users_temp including permissions
            $sql2 = "INSERT INTO `users_temp` (`user`, `roll_id`, `name`, `mobile`, `email`, `passport`, `deletes`, `status`, `created_at` , `otp`, `pass`, `created_by`, `password`,  `building_name`, `address`, `city`, `nationality`, `dialCode`, `lname`, `residinglocation`, `dob`, `permissions`)
                    VALUES ('', '0', '$_POST[name]', '$_POST[mobile]', '$_POST[email]', '',  '1', '0', '$dubaidate_time', '$otp', '$NewPassword', '$_SESSION[memid]', '$_POST[Password]', '$_POST[bulidingname]', '$address', '$city', '$nationality', '$_POST[countrycode]', '$lname', '$residinglocation', '$_POST[dob]', '$permissions');";

            $runn = mysqli_query($con, $sql2);
            if ($runn) {
                $insert_id = $con->insert_id;

                if (substr($_POST['mobile'], 0, 3) == "971") {
                    $messages1 = "Hello " . $_POST['name'] . ", " . $otp . " is the One Time Password (OTP) to verification the National draw Account.";
                    sendsms($con, $mobile, $messages1, "");
                    $title = 'Email / Mobile phone';
                    $emdata = $_POST['email'] . ' / ' .  $_POST['mobile'];
                }

                $subject = "Goride | OTP to Verify Email - " . date("d-m-Y g:i a");
                $messages = 'Your GoRide Verification Code is ' . $otp[0] . $otp[1] . $otp[2] . $otp[3] . '. Please don\'t share with anyone.';

                if ($email != '') {
                    $emailchack = explode('@', $email);
                    if (strtolower($emailchack[1]) != "littledraw.ae") {
                        $sendemail = sendemail($con, $email, $subject, $messages, 'otp');
                    }
                    $sendemail = true;
                } else {
                    $sendemail = true;
                }

                if ($sendemail) {
                    if ($title == '' && $emdata == '') {
                        $title = 'Email';
                        $emdata = $_POST['email'];
                    }

                    if ($_SESSION['otp'] != "") {
                        $result["title"] = $title;
                        $result["Newmobile"] = $emdata;
                        $result["type"] = "1";
                        $result["otpbtn"] = '<button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button>
                                                <button class="btn ripple btn-success" id="otpmodal1" onclick="saveformNew(' . "'$insert_id'" . ')" type="button">Submit</button>';
                        $result["result"] = "Otp send Successfully";
                    } else {
                        $result["type"] = "0";
                        $result["result"] = "Otp not send";
                        goto si;
                    }
                } else {
                    $result["type"] = "0";
                    $result["result"] = 'Email OTP Not Send!';
                    goto si;
                }
            } else {
                $result["type"] = "0";
                $result["result"] = 'Couldn`t Sent OTP. Kindly Refresh and Try Again';
                goto si;
            }
        }
    }

    si:
    echo json_encode($result);

} else if ($method == "add_agent_new") {

    if ($_SESSION['memid'] != '') {

        $insert_id = BlockSQLInjection($_POST["insert_id"]);
        if ($insert_id != '') {
            $result = [];
            $otp = BlockSQLInjection($_REQUEST["otp1"]) . BlockSQLInjection($_REQUEST["otp2"]) . BlockSQLInjection($_REQUEST["otp3"]) . BlockSQLInjection($_REQUEST["otp4"]);

            $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC ", "roll_id", "");

            $otpCheck = select_query($con, "users_temp", "", "`id` = '$insert_id' AND `otp` = '$otp' AND `deletes`='1' ORDER BY `id` DESC LIMIT 1", "", "");
            if ($otpCheck['nr'] > 0) {
                $selectID = $otpCheck['result'][0]['id'];

                if ($insert_id === $selectID) {

                    // Determine new user's roll_id based on tab
                    if ($tabID == 'agents') {
                        $roll_id = 3;
                    } else if ($tabID == 'staffs') {
                        $roll_id = 4;
                    } else if ($tabID == 'affiliate') {
                        $roll_id = 7;
                    } else if ($tabID == 'kioskcreater') {
                        $roll_id = 9;
                    } else if ($tabID == 'support') {
                        $roll_id = 11;
                    } else {
                        $roll_id = 0;
                    }

                    if ($roll_id != 0) {
                        $sql2 = "SELECT * FROM `role` WHERE id = $roll_id";
                        $run2 = mysqli_query($con, $sql2);
                        $usertype = $run2->fetch_assoc();
                        $user = $usertype['name'];
                    } else {
                        $user = 'Customer';
                    }

                    // Update users_temp with final roll_id and user type
                    $inv_arr = ["deletes" => '0', 'roll_id' => $roll_id, 'user' => $user];
                    $Inv_update = update($con, "users_temp", "`id` = '$insert_id' and `deletes`='1' ORDER BY `id` DESC", $inv_arr, "", "", "", "");
                    $errors = $Inv_update['errors'];
                    if ($errors != "") {
                        $result["type"] = "0";
                        $result["result"] = $errors;
                    } else {

                        // Log
                        error_log_new($con, getUserIP(), 'add_agent_new', $insert_id, '', '', 'Start Time :' . $dubaidate_time, json_encode($_POST), __DIR__, basename(__FILE__), __LINE__, $dubaidate_time);

                        // Insert into user_register from users_temp
                        $run = mysqli_query($con, "INSERT INTO user_register (`user`, `pass`, `password`, `roll_id`, `created_by`,  `name`, `dialCode`, `mobile`, `email`, `dob`, `passport`, `passport_expiry`, `img_url`, `status`, `otp`, `created_at`, `t_point`,  `account_name`, `account_no`, `bank_name`, `bank_address`, `swift_code`, `my_referral_code`, `address`, `nationality`, `residinglocation`, `ip`, `lastlogin`, `city`, `f_points`, `acctype`, `currency_code`, `IBAN_code`, `building_name`, `lname`) SELECT `user`, `pass`, `password`, `roll_id`, `created_by`,  `name`, `dialCode`, `mobile`, `email`, `dob`, `passport`, `passport_expiry`, `img_url`, `status`, `otp`, `created_at`, `t_point`,  `account_name`, `account_no`, `bank_name`, `bank_address`, `swift_code`, `my_referral_code`, `address`, `nationality`, `residinglocation`, `ip`, `lastlogin`, `city`, `f_points`, `acctype`, `currency_code`, `IBAN_code`, `building_name`, `lname` FROM users_temp WHERE id = '$insert_id' AND deletes = '0' ORDER BY `id` DESC LIMIT 1");
                        if ($run) {
                            $userinserid = $con->insert_id;

                            // Retrieve the stored permissions from users_temp
                            $temp_data = select_query($con, "users_temp", "permissions", "`id` = '$insert_id'", "", "");
                            $permissions_str = ($temp_data['nr'] > 0) ? $temp_data['result'][0]['permissions'] : '';

                            // Determine which permissions to assign
                            $ro = [];
                            if (!empty($permissions_str)) {
                                // Use permissions sent from frontend
                                $ro = explode(',', $permissions_str);
                            } else {
                                // Fallback to hardcoded defaults
                                $user_data = select_query($con, "user_register", "", "`id` = '$userinserid' AND `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                                if ($user_data['nr'] > 0) {
                                    $user_roll_id = $user_data['result'][0]['roll_id'];

                                    if ($tabID == 'agents') {
                                        if ($user_roll_id == 2) {
                                            $ro = [27, 19, 18, 13, 9, 7, 4, 3, 2, 1, 10, 11, 12, 17, 31, 33];
                                        } else if ($user_roll_id == 3) {
                                            $ro = [19, 13, 9, 7, 4, 2, 1, 11, 12, 17, 32, 33];
                                        } else if ($user_roll_id == 4) {
                                            $ro = [19, 13, 9, 7, 4, 2, 1, 11, 12, 17, 32, 33];
                                        } else if ($user_roll_id == 5) {
                                            $ro = [19, 13, 9, 4, 2, 1, 11, 12, 17, 32, 33];
                                        }
                                    } else if ($tabID == 'staffs') {
                                        $ro = [81, 67, 66, 64, 61, 54, 47, 45, 44, 43, 37, 36, 35, 34, 33, 32, 27, 25, 24, 22, 19, 18, 17, 12, 11, 10, 9, 7, 4, 3, 2, 1, 89];
                                    } else if ($tabID == 'affiliate') {
                                        $ro = [1, 2, 9, 34, 106, 93];
                                    } else if ($tabID == 'kioskcreater') {
                                        $ro = [1, 9, 5, 34, 135, 133, 134];
                                    } else if ($tabID == 'support') {
                                        $ro = [9, 144, 19, 34, 1, 145, 146, 147, 148, 149, 150, 151, 152];
                                    }
                                }
                            }

                            // Insert permissions
                            if (count($ro) > 0) {
                                foreach ($ro as $value) {
                                    $value = intval($value);
                                    if ($value > 0) {
                                        mysqli_query($con, "INSERT INTO `menu_permission` (`id`, `userid`, `menu`) VALUES (NULL, '$userinserid', '$value');");
                                    }
                                }
                            }

                            // Delete the temporary record
                            mysqli_query($con, "DELETE FROM `users_temp` WHERE `users_temp`.`id` = $insert_id;");

                            $result["type"] = "1";
                            $result["result"] = ucwords($user) . " Added Successfully";
                        } else {
                            $result["type"] = "0";
                            $result["result"] = "Not registered!";
                        }
                    }
                } else {
                    $result["type"] = "0";
                    $result["result"] = "Verification Failed!";
                    goto justReturn;
                }
            } else {
                $result["type"] = "0";
                $result["result"] = "Otp does not match";
                goto justReturn;
            }
        }
    } else {
        $result["type"] = "0";
        $result["result"] = "Login Required";
        goto justReturn;
    }

    justReturn:
    echo json_encode($result);

} else if ($method == "add_user_direct") {

    if ($role_id != '') {
        $result = [];

        $name       = BlockSQLInjection($_POST["name"]);
        $lname      = BlockSQLInjection($_POST["lname"]);
        $mobile = "+91" . $_POST['mobile'];
        $email      = BlockSQLInjection($_POST["email"]);
        $password = mysqli_real_escape_string($con, $_POST["password"]);
        $roll_id = BlockSQLInjection($_POST["role"]);
        $state = BlockSQLInjection($_POST["state_name"]);
        $district = BlockSQLInjection($_POST["district_name"]);

        $created_by = $_SESSION['memid'];
        $created_at = date("Y-m-d H:i:s");
        $deletes    = '0';
        $status = 0; // Added missing variable

        if ($role_id != 1) {
            $result["type"] = "0";
            $result["result"] = "Only Role ID 1 is allowed to insert.";
            echo json_encode($result);
            exit;
        }

        $roleData = select_query($con, "role", "", "`id`='$roll_id'", "", "");
        $userType = ($roleData['nr'] > 0) ? $roleData['result'][0]['name'] : "User";

        $checkDuplicate = mysqli_query($con, "
            SELECT `user`, `mobile` 
            FROM user_register 
            WHERE (`user` = '$userType' AND `mobile` = '$mobile') 
            AND `deletes` = '0'
        ");

        if (mysqli_num_rows($checkDuplicate) > 0) {
            $row = mysqli_fetch_assoc($checkDuplicate);
            if ($row['user'] == $userType) {
                $result["type"] = "0";
                $result["result"] = "User already exists!";
            } else if ($row['mobile'] == $mobile) {
                $result["type"] = "0";
                $result["result"] = "Mobile already exists!";
            }
            echo json_encode($result);
            exit;
        }

        $hashedPassword = md5($password);

        $insert = mysqli_query($con, "INSERT INTO user_register 
            (`user`, `pass`, `password`, `roll_id`, `created_by`, `name`, `mobile`, `email`, `status`, `state`,`districts_id` , `deletes`, `created_at`)
            VALUES
            ('$userType', '$hashedPassword', '$password', '$roll_id', '$created_by', '$name', '$mobile', '$email', '$status', '$state', '$district', '$deletes', '$created_at')
        ");

        if ($insert) {
            $userInsertId = $con->insert_id;

            // Default permissions for direct add (no dynamic selection here)
            $ro = [];
            if ($tabID == 'agents') {
                $ro = [19, 13, 9, 7, 4, 2, 1];
            } else if ($tabID == 'staffs') {
                $ro = [81, 67, 66, 64, 61, 54, 47];
            } else if ($tabID == 'affiliate') {
                $ro = [1, 2, 9, 34];
            } else if ($tabID == 'kioskcreater') {
                $ro = [1, 9, 5];
            } else if ($tabID == 'support') {
                $ro = [9, 144, 19, 34, 1];
            }

            foreach ($ro as $menu) {
                mysqli_query($con, "INSERT INTO menu_permission (`userid`, `menu`) VALUES ('$userInsertId', '$menu')");
            }

            $result["type"] = "1";
            $result["result"] = ucwords($userType) . " Added Successfully";
        } else {
            $result["type"] = "0";
            $result["result"] = "Insert Failed!";
        }
    } else {
        $result["type"] = "0";
        $result["result"] = "Login Required";
    }

    echo json_encode($result);
}
?>