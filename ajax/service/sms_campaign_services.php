<?php



// require '../../vendor/autoload.php';
include '../../include/shi-config.php';
include '../../include/functions.php';

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";
$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";



$result = array();


if ($method == "campaign_create") {
    $result = [];
    $name = $_POST['name'];
    $message = $_POST['message'];
    $url = $_POST['url'];

    $wallet_logarr = [
        "campaign_name" => $name,
        "message" => $message,
        "utm_url" => $url
    ];

    $capID = $_POST['id'];
    if ($capID == 0) {
        $wallet_logarr['createdon']  = $dubaidate_time;
        $wallet_logarr['deletes']  = '0';
        $wallet_log_ins = insert($con, "sms_campaign", "", $wallet_logarr, "", "", "");
        $errors = $wallet_log_ins['errors'];
        if ($errors != "") {
            $result["type"] = "1";
            $result["result"] = "The SMS campaign creation process has failed.";
            goto resultNIF;
        } else {
            $result["type"] = "0";
            $result["result"] = "SMS Campaign created successfully.";
            goto resultNIF;
        }
    } else {

        $ticket_lines_update = update($con, "sms_campaign", "`id` = '$capID' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", $wallet_logarr, "", "", "", "");
        $errors = $ticket_lines_update['errors'];
        if ($errors != "") {
            // $result["type"] = "0";
            // $result["result"] = $errors;
            $result["type"] = "0";
            $result["result"] = "SMS Campaign Updated process has failed.";
            goto resultNIF;
        } else {
            $result["type"] = "0";
            $result["result"] = "SMS Campaign Updated successfully.";
            goto resultNIF;
        }
    }



    resultNIF:
    echo json_encode($result);
} else if ($method == 'campaign_delete') {
    $result = [];
    $capID = $_POST['id'];

    if ($capID == '') {
        $result["type"] = "0";
        $result["result"] = 'Kindly Refresh the screen and Try again!';
        goto resultNIF34;
    }

    $wallet_logarr['deletes']  = '1';
    $ticket_lines_update = update($con, "sms_campaign", "`id` = '$capID' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", $wallet_logarr, "", "", "", "");
    $errors = $ticket_lines_update['errors'];
    if ($errors != "") {
        // $result["type"] = "0";
        // $result["result"] = $errors;
        $result["type"] = "0";
        $result["result"] = "SMS Campaign delete process has failed.";
        goto resultNIF34;
    } else {
        $result["type"] = "0";
        $result["result"] = "SMS Campaign deleted successfully.";
        goto resultNIF34;
    }

    resultNIF34:
    echo json_encode($result);
} else if ($method == 'campReport') {
    $result = [];
    $campID = $_POST['campID'];
    $statusget = $_POST['statusget'];
    $mobile = $_POST['mobile'];
    if ($campID == '') {
        $result["type"] = "0";
        $result["result"] = [];
        goto resultNIF1;
    }

    $que = "SELECT mobile, link_url, user_click, click_count, message FROM `sms_campaign_list` AS sl LEFT JOIN `sms_campaign` AS s ON s.id = sl.campaign_id WHERE sl.`campaign_id` = '$campID' AND sl.`deletes` = '0'";

    $que .= ($statusget == 'click') ? ' AND sl.user_click != 0' : ' AND sl.user_click = 0';

    $que .= ($mobile != '' && $mobile != null) ? " AND sl.`mobile` = '$mobile'" : '';

    $query = mysqli_query($con, $que);
    if (mysqli_num_rows($query) > 0) {
        $result["type"] = "0";
        $result["result"] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        goto resultNIF1;
    } else {
        $result["type"] = "0";
        $result["result"] = [];
        goto resultNIF1;
    }

    resultNIF1:
    echo json_encode($result);
} else if ($method == 'campList') {
    $result = [];


    $query = mysqli_query($con, "SELECT id, campaign_name, message, utm_url, createdon FROM `sms_campaign` WHERE `deletes` = '0' ORDER BY `id` DESC;");
    if (mysqli_num_rows($query) > 0) {
        $result["type"] = "0";
        $result["result"] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        goto resultNIF1;
    } else {
        $result["type"] = "0";
        $result["result"] = [];
        goto resultNIF1;
    }

    resultNIF123:
    echo json_encode($result);
} else if ($method == 'campBathList') {
    $result = [];
    $smscampID = $_POST['smscampID'];
    $batch = $_POST['smsDownloadBatch'];

    $smsDownloadBatch = ($batch != '') ? " AND s.`batch` = '$batch'"  : '';
    // $smsDownloadBatch = ($batch != '') ? $batch : ' ';




    //var_dump($smscampID);
    $query = mysqli_query($con, "SELECT s.batch, COUNT(s.id) AS 'count', SUM(s.user_click) AS 'totalClick', sl.country, sl.createdon FROM `sms_campaign_list` AS s LEFT JOIN sms_campaign AS sl ON sl.id = s.campaign_id WHERE s.`campaign_id` = $smscampID AND s.`deletes` = '0' $smsDownloadBatch GROUP BY s.batch;");

    $data = mysqli_fetch_all($query, MYSQLI_ASSOC);

    // var_dump("SELECT batch, COUNT(id) AS 'count', SUM(user_click) AS 'totalClick', country FROM `sms_campaign_list` WHERE `campaign_id` = $smscampID AND `deletes` = '0' $smsDownloadBatch GROUP BY batch;");
    // die;
    $allResult = [];

    foreach ($data as $row) {
        $BATCH = $row['batch'];

        $countryCode = ($row['country'] != null && $row['country'] != '91') ? 'mobile' : "CONCAT('91', mobile)";

        $dataww = date('Y-m-d', strtotime($row['createdon']));
        $dateTime = ($dataww != '' && $dataww != null) ? "AND createdon > '$dataww 00:00:00'" : '';



        // var_dump("SELECT COUNT(id) AS 'count' FROM `user_register` WHERE mobile IN (SELECT $countryCode FROM sms_campaign_list WHERE `campaign_id` = '$smscampID' AND `batch` = '$BATCH' AND `user_click` = '1' AND `deletes` = '0') ");
        // die;




        $query1 =  mysqli_query($con, "SELECT COUNT(id) AS 'count' FROM `user_register` WHERE mobile IN (SELECT $countryCode FROM sms_campaign_list WHERE `campaign_id` = '$smscampID' AND `batch` = '$BATCH' AND `user_click` = '1') AND `deletes` = '0' AND `roll_id` = '0' AND `status` = '0'");

        $frow = mysqli_fetch_assoc($query1);

        $query2 = mysqli_query($con, "SELECT COUNT(ticket_id) AS count FROM (SELECT ticket_id FROM `ticket_lines` WHERE  deletes = '0' AND `type` = 'OT' $dateTime AND user_id IN (SELECT id FROM `user_register` WHERE mobile IN (SELECT $countryCode FROM `sms_campaign_list` WHERE campaign_id = '$smscampID' AND batch = '$BATCH' AND user_click = '1' AND deletes = '0')  AND `deletes` = '0' AND `roll_id` = '0' AND `status` = '0') GROUP BY ticket_id) AS g; ");

        $frowjs = mysqli_fetch_assoc($query2);


        $allResult[] = [
            'batch' => $row['batch'],
            'count' => $row['count'],
            'totalClick' => $row['totalClick'],
            'userCount' => $frow['count'],
            'ticket_count' =>  $frowjs['count'],
            // 'Q' => "SELECT COUNT(ticket_id) AS count FROM (SELECT ticket_id FROM `ticket_lines` WHERE  deletes = '0' AND `type` = 'OT' $dateTime AND user_id IN (SELECT id FROM `user_register` WHERE mobile IN (SELECT $countryCode FROM `sms_campaign_list` WHERE campaign_id = '$smscampID' AND batch = '$BATCH' AND user_click = '1' AND deletes = '0')  AND `deletes` = '0' AND `roll_id` = '0' AND `status` = '0') GROUP BY ticket_id) AS g; "
        ];
        // var_dump($allResult);
        // die;

    }

    // var_dump($allResult);
    // die;
    if (count($allResult) > 0) {
        $result["type"] = "0";
        $result["result"] = $allResult;
    } else {
        $result["type"] = "0";
        $result["result"] = [];
    }

    echo json_encode($result);
} else if ($method == 'noofregister') {

    $smscampID = $_POST['smscampID'];
    $batch = $_POST['smsDownloadBatch'];

    $result = [];
    $smsDownloadBatch = ($batch != '') ? " AND `batch` = '$batch'"  : '';
   

    $camp = mysqli_query($con, "SELECT * FROM `sms_campaign` WHERE `id` = '$smscampID' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1;");
    if (mysqli_num_rows($camp) > 0) {
        $rowMy = mysqli_fetch_assoc($camp);



        $countryCode = ($rowMy['country'] != null && $rowMy['country'] != '91') ? 'mobile' : "CONCAT('91', mobile)";



        $newQuery = "SELECT * FROM `user_register` WHERE mobile IN (SELECT $countryCode FROM sms_campaign_list WHERE `campaign_id` = '$smscampID'  $smsDownloadBatch AND `user_click` = '1' AND `deletes` = '0')  AND `deletes` = '0' AND `roll_id` = '0' AND `status` = '0'";

        $queryResult = mysqli_query($rcon, $newQuery);

        if ($queryResult) {
            // Check if there are rows returned
            if (mysqli_num_rows($queryResult) > 0) {
                while ($row = mysqli_fetch_assoc($queryResult)) {

                    $result[] = array(
                        "name" => $row['name'] . ' ' . $row['lname'],
                        "mobile" => $row['mobile'],
                        "t_point" => $row['t_point'],
                        "email" => $row['email'],
                        "createdat" => $row['created_at'],
                        // "action" => $action  // Include or exclude depending on your use
                    );
                }
                // var_dump($result);die;
            }
        }
    }




    echo json_encode(['result' => $result]);
} else if ($method == 'noofticket') {

    $smscampID = $_POST['smscampID'];
    $batch = $_POST['smsDownloadBatch'];

    $smsDownloadBatch = ($batch != '') ? " AND `batch` = '$batch'"  : '';

    $camp = mysqli_query($con, "SELECT * FROM `sms_campaign` WHERE `id` = '$smscampID' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1;");
    if (mysqli_num_rows($camp) > 0) {
        $rowMy = mysqli_fetch_assoc($camp);

        $countryCode = ($rowMy['country'] != null && $rowMy['country'] != '91') ? 'mobile' : "CONCAT('91', mobile)";

        $dataww = date('Y-m-d', strtotime($rowMy['createdon']));
        $dateTime = ($dataww != '' && $dataww != null) ? "AND createdon > '$dataww 00:00:00'" : '';

        // var_dump("SELECT * FROM ticket LEFT JOIN user_register ON user_register.id = ticket.user_id WHERE ticket.id IN (SELECT ticket_id FROM (SELECT ticket_id FROM `ticket_lines` WHERE  deletes = '0' $dateTime AND `type` = 'OT' AND user_id IN (SELECT id FROM `user_register` WHERE mobile IN (SELECT $countryCode FROM `sms_campaign_list` WHERE campaign_id = '$smscampID' $smsDownloadBatch AND deletes = '0')) GROUP BY ticket_id) AS g);");
        // die;


        $newQuery  = "SELECT * FROM ticket LEFT JOIN user_register ON user_register.id = ticket.user_id WHERE ticket.id IN (SELECT ticket_id FROM (SELECT ticket_id FROM `ticket_lines` WHERE  deletes = '0' $dateTime AND `type` = 'OT' AND user_id IN (SELECT id FROM `user_register` WHERE mobile IN (SELECT $countryCode FROM `sms_campaign_list` WHERE campaign_id = '$smscampID' $smsDownloadBatch AND deletes = '0' AND `user_click` = '1')) GROUP BY ticket_id) AS g);";
       
        // var_dump($newQuery);
        // die;

        $result = [];
        // Assuming $rcon is your database connection

        // Execute the query and fetch the results
        $queryResult = mysqli_query($rcon, $newQuery);

        if ($queryResult) {
            // Check if there are rows returned
            if (mysqli_num_rows($queryResult) > 0) {
                while ($row = mysqli_fetch_assoc($queryResult)) {

                    $result[] = array(
                        "ticket_no" => $row['ticket_no'],
                        "name" => $row['name'] . ' ' . $row['lname'],
                        "email" => $row['email'],
                        "draw_id" => $row['draw_id'],
                        "mobile" => $row['mobile'],
                        "purchase_datetime" => $row['purchase_datetime'],
                        "transaction_id" => $row['transaction_id'],
                        "t_point" => $row['t_point'],
                        // "createdat" => $row['created_at'],
                        // "action" => $action  // Include or exclude depending on your use
                    );
                }
                // var_dump($result);
                // die;
            }
        }
    }
    echo json_encode(['result' => $result]);
}
