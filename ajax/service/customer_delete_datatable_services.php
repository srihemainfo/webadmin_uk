<?php
// Date        Developer     Changes
//  20-10-2023    Divya       con changed to rcon


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



$post_csrf = $headers['X-Csrf-Token'] ?? '';


if ($method == "list_agent") {
    $result = [];
    $user_register = select_query($rcon, "user_delete_request", "", "", "", "");

    if ($user_register['nr'] > 0) {
        foreach ($user_register['result'] as $key => $value) {
            $email = select_top_name($rcon, "user_register", "email", "`id`='$value[user_id]'", "email", "", "");
            $name = select_top_name($rcon, "user_register", "name", "`id`='$value[user_id]'", "name", "", "");

            $expdate = $value['account_expire_date'];
            $reactdate = $value['reactivated_at'];

      
            if ($value['status'] == 0) {
                $status = "deleted";
                $reactdate = "";
            } else {
                $status = "reactivated";
                $expdate = "";
            }

            $result[] = [
                "srno" => ++$key,
                "cname" => $name,
                "cemail" => $email,
                "status" => $status,
                "expdatetime" => $expdate,
                "reactdate" => $reactdate,
                "deldatetime" => $value['deleted_at']
            ];
        }
    }
    echo json_encode($result);
}
