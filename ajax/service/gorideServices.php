<?php

include '../../include/shi-config.php';

include '../../include/functions.php';

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";

$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";

$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";

$role = $_REQUEST['role'] ?? "";

if ($type == 'agent') {

    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($role) AND" : "";
} else {

    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$role' AND" : "";
}

$headers = apache_request_headers();

//print_r($headers);

$result = array();

$post_csrf = $headers['X-Csrf-Token'] ?? '';

if ($method == "country_access") {

    if ($_SESSION['memid'] != 1) {
        
        $condition = "`created_by` = '$_SESSION[memid]' AND";
    } else {

        $condition = "";
    }

    $roll_id = select_top_name($rcon, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC ", "roll_id", "");

    $user_register = select_query($rcon, "user_register", "", "$condition  $role `deletes`='0'", "", "");

    $result = [];

    if ($user_register['nr'] > 0) {

        $countries = select_query($lcon, "countries", "id,
                    active_status,
                    name,
                    phonecode AS dailcode,
                    currency_symbol AS currency_symbol,
                    currency AS currency,
                    iso2 AS country_code,
                    JSON_UNQUOTE(JSON_EXTRACT(timezones, '$[0].zoneName')) AS time_zone", "", "", "");
        

        if($countries['nr'] > 0){
            $result = $countries['result'];
        }

    }
    goto resultSAI;
    
}elseif ($method == "update_access") {

    if ($_SESSION['memid'] != 1) {
        
        $condition = "`created_by` = '$_SESSION[memid]' AND";
    } else {

        $condition = "";
    }
    
    $c_id = BlockSQLInjectionforagent($_POST["c_id"]);
    
    $c_status = BlockSQLInjectionforagent($_POST["c_status"]);
    

    $roll_id = select_top_name($rcon, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC ", "roll_id", "");

    $user_register = select_query($rcon, "user_register", "", "$condition  $role `deletes`='0'", "", "");

    $result = [];

    if ($user_register['nr'] > 0) {

        $countries = select_query($lcon, "countries", "", "`id` = $c_id", "", "");
        

        if($countries['nr'] > 0){
            
            $inv_arr = ["active_status" => $c_status, 'updatedon' => $dubaidate_time];

            $Inv_update = update($lcon, "countries", "`id` = '$c_id'", $inv_arr, "", "", "", "");
        
            $errors = $Inv_update['errors'];
        
            if ($errors != "") {
        
                $result["type"] = "0";
        
                $result["result"] = "Status Updation Failed";
        
                goto resultSAI;
            } else {
        
                $result["type"] = "1";
        
                $result["result"] = "Status Updated Successfully";
        
                goto resultSAI;
            }
        }else{
            $result["type"] = "0";
        
            $result["result"] = "Country not found.";
    
            goto resultSAI;
        }

    }
}

resultSAI:
echo json_encode($result);

function generateNumericOTP($n)



{



    $generator = "1357902468";



    $result = "";



    for ($i = 1; $i <= $n; $i++) {



        $result .= substr($generator, (rand() % (strlen($generator))), 1);
    }



    return $result;
}
