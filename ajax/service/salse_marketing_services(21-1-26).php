<?php



// require '../../vendor/autoload.php';
include '../../include/shi-config.php';
include '../../include/functions.php';

header('Content-Type: text/html; charset=utf-8');

// mysqli_set_charset($con, 'utf8mb4');

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";
$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";

$admin_name = select_top_name($con, "user_register", "name", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC ", "name", "");

// var_dump($roll_id);die;

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
} 
else if ($method == 'campBathList1') {
    $result = [];
    $now = date('Y-m-d');
    $agdate = BlockSQLInjection($_POST["agdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);

    if ($agdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($agdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "`datetime` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $da = 'DESC';
        $contype = "`datetime` LIKE '%$now%' AND";
    }

    $Emaillog = select_query($con, "campaign_emaillog", "sendstatus,fromemail,email,ip,subject,datetime,id", " $contype `id`!= '' ", "", "");

    if ($Emaillog['nr'] > 0) {

        foreach ($Emaillog['result'] as $key => $value) {

            $result[] = ["id" => $value['id'], "sendstatus" => $value['sendstatus'], "fromemail" => $value['fromemail'], "email" => $value['email'], "ip" => $value['ip'], "subject" => '<textarea readonly>' . $value['subject'] . '</textarea>', "datetime" => $value['datetime']];
        }
    }

    echo json_encode($result);
}
else if ($method == 'campBathList') {
    $result = array('result' => array());
    $now = date('Y-m-d');
    
    $agdate = !empty($_POST["agdate"]) ? date("Y-m-d", strtotime($_POST["agdate"])) : $now;
    $todate = !empty($_POST["todate"]) ? date("Y-m-d", strtotime($_POST["todate"])) : $now;
    
    $contype = "`datetime` BETWEEN '$agdate 00:00:00' AND '$todate 23:59:59' AND source IS NULL AND";
    
    $Emaillog = select_query($con, "campaign_emaillog", "campign_name,sendstatus,fromemail,email,ip,subject,datetime,id,details", " $contype `id`!= '' ", "", "");
    
    if ($Emaillog['nr'] > 0) {
        foreach ($Emaillog['result'] as $key => $value) {
            $result['result'][] = [
                "id" => $value['id'],
                "campign_name" => $value['campign_name'],
                "sendstatus" => $value['sendstatus'],
                "fromemail" => $value['fromemail'],
                "email" => $value['email'],
                "ip" => $value['ip'],
                "subject" => $value['subject'],
                "datetime" => $value['datetime'],
                "details" => $value['details']
            ];
        }
    }
    // var_dump($result);die;
    echo json_encode($result);
    exit;
}

else if ($method == 'ads_leads_list') {
    $result = [];
    $now = date('Y-m-d');
    $agdate = BlockSQLInjection($_POST["agdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);
    $status = $_POST["status"];

    $contype = '';

    if ($agdate != '' && $todate != '') {
        $fd = date("Y-m-d", strtotime($agdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype .= "al.created_time BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND ";
    } else {
        $contype .= "al.created_time LIKE '%$now%' AND ";
    }

    if ($status != '') {
        $contype .= "al.lead_status = '$status' AND ";
    }


// First get all the leads data
$ADslog = select_query(
    $rcon_live,
    "ads_leads al
     LEFT JOIN userinfo ui
        ON RIGHT(al.phone_number, 10) = RIGHT(ui.mobile2, 10)
     LEFT JOIN deleted_userinfo dui
        ON ui.u_id IS NULL
        AND RIGHT(al.phone_number, 10) = RIGHT(dui.mobile2, 10)",
    "al.id,
     al.created_time,
     al.platform,
     al.what_is_your_gender,
     al.what_is_your_age_group,
     al.looking_for_a_match_for,
     al.email,
     al.full_name,
     al.phone_number AS full_number,
     RIGHT(al.phone_number, 10) AS phone_number,
     al.lead_status,
     al.followup_json,
     al.remarks,
     COALESCE(ui.username, dui.username) AS userinfo_username,
     COALESCE(ui.u_id, dui.u_id) AS u_id,

     CASE WHEN dui.u_id IS NOT NULL THEN 1 ELSE 0 END AS is_deleted_user,

     CASE 
         WHEN COALESCE(ui.status, dui.status) = 'Active' THEN 'Completed'
         WHEN COALESCE(ui.status, dui.status) IS NULL THEN '-'
         ELSE COALESCE(ui.status, dui.status)
     END AS otp_verification,

     CASE 
         WHEN COALESCE(ui.status, dui.status) = 'Active'
              AND COALESCE(ui.age, dui.age) IS NOT NULL
              AND COALESCE(ui.birthday, dui.birthday) IS NOT NULL
             THEN 'Myprofile Completed'
         WHEN COALESCE(ui.status, dui.status) = 'Active'
              AND (COALESCE(ui.age, dui.age) IS NULL OR COALESCE(ui.birthday, dui.birthday) IS NULL)
             THEN 'Myprofile Incomplete'
         ELSE '-'
     END AS profile_completion_status",
    rtrim($contype . " al.id != ''", ' AND '),
    "",
    ""
);

$result = [];
$sno = 1;

if ($ADslog['nr'] > 0) {
    foreach ($ADslog['result'] as $value) {
        $filtered = [];
        foreach ($value as $k => $v) {
            if (!is_int($k)) {
                $filtered[$k] = $v;
            }
        }
            $phonee = $filtered['full_number'];
            $fullPhone = '91' . $filtered['phone_number'];

            $escapedPhone = mysqli_real_escape_string($rcon_live, $fullPhone);
            
            $waQuery = "
                SELECT status 
                FROM whatsapp_bulk_message 
                WHERE to_whatsapp = '$phonee'
                ORDER BY id DESC
                LIMIT 1
            ";
            
            // var_dump($waQuery);die;
            $waResult = mysqli_query($con, $waQuery);
            
            if (!$waResult) {
               
                var_dump("WA Query Error: " . mysqli_error($con));
            } 
            elseif (mysqli_num_rows($waResult) > 0) 
            {
                $waData = mysqli_fetch_assoc($waResult);
                $filtered['status'] = $waData['status'];
            } else {
                $filtered['status'] = null;
            }
            
            // var_dump($filtered['status']);die;

        
        $finalUsername = '-';
        $finalUserId = null;
        $uIdFallback = false;

        // Primary: from joined userinfo or deleted_userinfo
        if (!empty($filtered['userinfo_username'])) {
            $finalUsername = $filtered['userinfo_username'];
            $finalUserId = $filtered['u_id'];
        } else {
            // Fallback: from followup_json
            if (!empty($filtered['followup_json'])) {
                $followups = json_decode($filtered['followup_json'], true);
                if (is_array($followups) && count($followups) > 0) {
                    $lastFollowup = end($followups);
                    if (!empty($lastFollowup['username'])) {
                        $finalUsername = $lastFollowup['username'];

                        // Try fetching u_id based on fallback username
                        $escapedUsername = mysqli_real_escape_string($rcon_live, $finalUsername);
                        $getUserQuery = "SELECT u_id FROM userinfo WHERE username = '$escapedUsername' LIMIT 1";
                        $getUserResult = mysqli_query($rcon_live, $getUserQuery);

                        if ($getUserResult && mysqli_num_rows($getUserResult) > 0) {
                            $userData = mysqli_fetch_assoc($getUserResult);
                            $finalUserId = $userData['u_id'];
                            $uIdFallback = true;
                        }
                    }
                }
            }
        }

        // After you finish deciding $finalUsername, $finalUserId, and $uIdFallback
        $filtered['username'] = $finalUsername;
        $filtered['u_id'] = $finalUserId;
        $filtered['u_id_fallback'] = $uIdFallback ? 1 : 0; // store as 1/0 for JS simplicity
        
        // Make sure is_deleted_user from SQL is kept
        if (!isset($filtered['is_deleted_user'])) {
            $filtered['is_deleted_user'] = 0;
        }
        
        unset($filtered['userinfo_username']); 
        
        $filtered['sno'] = $sno++;
        $result[] = $filtered;
    }
}
    // var_dump($result);die;

echo json_encode($result);
}


else if ($method == 'followup_leads_list') {

    $result = [];
    $now = date('Y-m-d');
    $agdate = BlockSQLInjection($_POST["agdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);
    $status = $_POST["status"];

    $statusFiler = "";

    if ($agdate != '' && $todate != '') {
        $fd = date("Y-m-d", strtotime($agdate));
        $td = date("Y-m-d", strtotime($todate));
        $followDateFilter = "lfu.date BETWEEN '$fd' AND '$td'";
    } else {
        $followDateFilter = "lfu.date = '$now'"; 
    }

    if (!empty($status)) {
        $statusFiler = "AND a.lead_status = '$status'";
    }

    $sql = "
        WITH lead_followups AS (
            SELECT 
                a.id AS lead_id,
                jt.employee,
                jt.status,
                jt.comments,
                jt.date,
                jt.updated_at,
                ROW_NUMBER() OVER (PARTITION BY a.id ORDER BY jt.updated_at DESC) AS rn
            FROM ads_leads a,
            JSON_TABLE(a.followup_json,
                '$[*]' COLUMNS (
                    employee VARCHAR(255) PATH '$.employee',
                    status VARCHAR(255) PATH '$.status',
                    comments TEXT PATH '$.comments',
                    date DATE PATH '$.date',
                    updated_at DATETIME PATH '$.updated_at'
                )
            ) AS jt
        )
        SELECT 
            a.id, a.created_time, a.platform, a.what_is_your_gender,
            a.what_is_your_age_group, a.looking_for_a_match_for,
            a.email, a.full_name, RIGHT(a.phone_number, 10) AS phone_number, a.lead_status, a.followup_json,a.remarks,
            lfu.employee, lfu.status AS followup_status, lfu.comments AS followup_comments,
            lfu.date AS followup_date, lfu.updated_at AS followup_updated_at
        FROM ads_leads a
        JOIN lead_followups lfu ON a.id = lfu.lead_id
        WHERE lfu.rn = 1 AND $followDateFilter $statusFiler
        ORDER BY a.created_time ASC
    ";

    $ADslog = mysqli_query($rcon_live, $sql);
    $sno = 1;

    if ($ADslog && mysqli_num_rows($ADslog) > 0) {
        while ($value = mysqli_fetch_assoc($ADslog)) {
            $value['sno'] = $sno++;
            $result[] = $value;
        }
    }
    // var_dump($result);
    // die; 
    echo json_encode($result);
}

else if ($method == 'get_single_lead') {
    $id = $_POST['id'];

    $check_query = "SELECT * FROM ads_leads WHERE id = '$id' LIMIT 1";
    $check_result = mysqli_query($rcon_live, $check_query);
    $data = mysqli_fetch_assoc($check_result);
    
    // var_dump($data);die;
    
    if ($data) {
       
        $full_phone = preg_replace('/\D/', '', $data['phone_number']); 
        $last10 = substr($full_phone, -10);

        // Step 2: Search userinfo for mobile2 match
        $username = '';
        if (!empty($last10)) {
            $user_query = "SELECT username, mobile2 FROM userinfo WHERE RIGHT(REPLACE(mobile2, ' ', ''), 10) = '$last10' LIMIT 1";
            $user_result = mysqli_query($rcon_live, $user_query);
            $user_row = mysqli_fetch_assoc($user_result);

            if ($user_row && !empty($user_row['mobile2'])) {
                $username = $user_row['username'];
            }
        }

        // Step 3: Add username to response
        $data['matched_username'] = $username;
        
        // var_dump($data['matched_username']);
        // die;

        echo json_encode(["success" => true, "data" => $data]);
    } else {
        echo json_encode(["success" => false]);
    }
    exit;
}

else if ($method == 'update_followup') {
    $id = $_POST['lead_id'];
    $new_status = mysqli_real_escape_string($rcon_live, $_POST['new_status']);
    $comments = mysqli_real_escape_string($rcon_live, $_POST['followup_comments']) ?? '';
    $date = mysqli_real_escape_string($rcon_live, $_POST['followup_date']) ?? '';
    $username = mysqli_real_escape_string($rcon_live, $_POST['followup_username']) ?? '';
    $remarks = mysqli_real_escape_string($rcon_live, $_POST['remarks']) ?? '';
    $employee = $admin_name ?? 'Unknown';
    
    // var_dump($username);die;

    // Fetch current followup JSON
    $json_query = "SELECT followup_json , remarks FROM ads_leads WHERE id = '$id'";
    $json_result = mysqli_query($rcon_live, $json_query);
    $row = mysqli_fetch_assoc($json_result);

    $history = $row && $row['followup_json'] ? json_decode($row['followup_json'], true) : [];
    $history[] = [
        "employee" => $employee,
        "status" => $new_status,
        "comments" => $comments,
        "date" => $date,
        "username" =>$username,
        "updated_at" => date('Y-m-d H:i:s')
    ];

    $updated_json = mysqli_real_escape_string($rcon_live, json_encode($history));

    $update_query = "
        UPDATE ads_leads
        SET followup_json = '$updated_json', lead_status = '$new_status', remarks = '$remarks'
        WHERE id = '$id'
    ";
    
    // var_dump($update_query);die;
    
    $update_result = mysqli_query($rcon_live, $update_query);
    
        //  print_r($update_result);die;


    echo json_encode(["success" => $update_result ? true : false]);
    exit;
} 

if ($method == 'send_whatsapp') {

    $lead_id = mysqli_real_escape_string($con, $_POST['id']);
    $name    = mysqli_real_escape_string($con, $_POST['name']);
    $number  = mysqli_real_escape_string($con, $_POST['number']);
    $whatsapp_temp = $_POST['whatsapp_templates'];
    
    $templateId = intval($whatsapp_temp);
    $templateQuery = "SELECT body, media_url, name FROM wamail_templates WHERE id = $templateId AND is_active = 1 LIMIT 1";
    $templateResult = mysqli_query($con, $templateQuery);
    $templateRow = mysqli_fetch_assoc($templateResult);
    
    if ($templateRow) {
        $real_message = $templateRow['body'];
        $real_url = $templateRow['media_url'];
        $type = $templateRow['name'];
    } else {
        $real_message = '';
        $real_url = '';
        $type = '';
    }
    
    $source  = 'ads_leads';
    
    // var_dump($real_url);die;

    $insert_query = "
        INSERT INTO whatsapp_bulk_message (source, details, media_url, type, name, to_whatsapp, status, created_at)
        VALUES ('$source', '$real_message','$real_url', '$type', '$name', '$number', 'pending', NOW())
    ";

    $result = mysqli_query($con, $insert_query);

    if (!$result) {
        echo json_encode(["success" => false, "error" => mysqli_error($con)]);
    } else {
        echo json_encode(["success" => true]);
    }
    exit;

} else if ($method == 'send_email') {

    $lead_id = mysqli_real_escape_string($con, $_POST['id']);
    $name    = mysqli_real_escape_string($con, $_POST['name']);
    $email   = mysqli_real_escape_string($con, $_POST['email']);
    $email_temp = mysqli_real_escape_string($con, $_POST['email_templates']);

    $source  = 'ads_leads';
    
    $templateId = intval($email_temp);
    $templateQuery = "SELECT body, name, subject FROM wamail_templates WHERE id = $templateId AND is_active = 1 LIMIT 1";
    $templateResult = mysqli_query($con, $templateQuery);
    $templateRow = mysqli_fetch_assoc($templateResult);
    
    if ($templateRow) {
        $real_message = $templateRow['body'];
        $subject = $templateRow['subject'];
        $type = $templateRow['name'];
    } else {
        $real_message = '';
        $subject = '';
        $type = '';
    }
    
    // var_dump($email_temp);die;

    $insert_query = "
        INSERT INTO campaign_emaillog (source, subject, details, email, status)
        VALUES ('$source', '$subject', '$real_message', '$email', 0)
    ";

    $result = mysqli_query($con, $insert_query);

    if (!$result) {
        echo json_encode(["success" => false, "error" => mysqli_error($con)]);
    } else {
        echo json_encode(["success" => true]);
    }
    exit;
    
} 

else if ($method == 'add_whatsapp_template') {
    
    mysqli_set_charset($con, "utf8mb4");

    $name       = mysqli_real_escape_string($con, $_POST['name']);
    $channel    = 'whatsapp';
    $body       = mysqli_real_escape_string($con, $_POST['body']);
    $media_url  = mysqli_real_escape_string($con, $_POST['photobase64']);

    $query = "
        INSERT INTO wamail_templates (name, channel, body, media_url, created_by)
        VALUES ('$name', '$channel', '$body', '$media_url', '$admin_name')
    ";

    $result = mysqli_query($con, $query);

    if (!$result) {
        echo json_encode(["success" => false, "error" => mysqli_error($con)]);
    } else {
        echo json_encode(["success" => true]);
    }
    exit;

}

else if ($method == 'add_whatsapp_template2') {
    
    mysqli_set_charset($con, "utf8mb4");

    $name       = mysqli_real_escape_string($con, $_POST['name']);
    $channel    = 'whatsapp';
    $whatsappCon       = mysqli_real_escape_string($con, $_POST['whatsappCon']);
    $whatsappVar       = mysqli_real_escape_string($con, $_POST['whatsappVar']);
    $body       = mysqli_real_escape_string($con, $_POST['body']);
    $media_url  = mysqli_real_escape_string($con, $_POST['photobase64']);

    $query = "
        INSERT INTO wamail_templates (name, channel, body, media_url, created_by, m_type, var_count)
        VALUES ('$name', '$channel', '$body', '$media_url', '$admin_name', '$whatsappCon', '$whatsappVar')
    ";

    $result = mysqli_query($con, $query);

    if (!$result) {
        echo json_encode(["success" => false, "error" => mysqli_error($con)]);
    } else {
        echo json_encode(["success" => true]);
    }
    exit;

}


else if ($method == 'add_email_template') {

    $name       = mysqli_real_escape_string($con, $_POST['name']);
    $channel    = 'email';
    $subject    = mysqli_real_escape_string($con, $_POST['subject']);
    $body       = mysqli_real_escape_string($con, $_POST['body']);

    $query = "
        INSERT INTO wamail_templates (name, channel, subject, body, created_by)
        VALUES ('$name', '$channel', '$subject', '$body', '$admin_name')
    ";

    $result = mysqli_query($con, $query);

    if (!$result) {
        echo json_encode(["success" => false, "error" => mysqli_error($con)]);
    } else {
        echo json_encode(["success" => true]);
    }
    exit;

}

else if ($method == 'get_all_templates') {
    // $startDate = $_POST['start_date'] ?? null;
    // $endDate = $_POST['end_date'] ?? null;

    $where = "WHERE is_active = 1";

    // if ($startDate && $endDate) {
    //     $where .= " AND DATE(created_at) BETWEEN '$startDate' AND '$endDate'";
    // }

    $query = "SELECT * FROM wamail_templates $where ORDER BY id DESC";
    // var_dump($query);die;
    $result = mysqli_query($con, $query);

    $templates = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Format date for frontend
        if (!empty($row['created_at']) && $row['created_at'] !== '0000-00-00 00:00:00') {
           $row['created_at'] = date('d/m/Y h:i A', strtotime($row['created_at']));
        } else {
            $row['created_at'] = '';
        }
        $templates[] = $row;
    }

    echo json_encode(["data" => $templates]);
    exit;
}

else if ($method == 'get_whatsapp_templates') {
    $query = "SELECT id, name FROM wamail_templates WHERE channel = 'whatsapp' AND is_active = 1 ORDER BY id DESC";
    $result = mysqli_query($con, $query);

    $options = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $options[] = $row;
    }

    echo json_encode(["success" => true, "data" => $options]);
    exit;
}
else if ($method == 'get_email_templates') {
    $query = "SELECT id, name FROM wamail_templates WHERE channel = 'email' AND is_active = 1 ORDER BY id DESC";
    $result = mysqli_query($con, $query);

    $options = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $options[] = $row;
    }

    echo json_encode(["success" => true, "data" => $options]);
    exit;
}
else if ($method == 'get_single_template') {
    $id = intval($_POST['id']);
    $query = "SELECT * FROM wamail_templates WHERE id = $id LIMIT 1";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    echo json_encode(['success' => true, 'data' => $row]);
    exit;
}

else if ($method == 'update_template') {
    $id = intval($_POST['template_id']);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $channel = mysqli_real_escape_string($con, $_POST['channel']);
    $subject = mysqli_real_escape_string($con, $_POST['subject'] ?? '');
    $body = mysqli_real_escape_string($con, $_POST['body']);
    $media_url = mysqli_real_escape_string($con, $_POST['photobase64'] ?? '');

    $query = "
        UPDATE wamail_templates
        SET name = '$name', channel = '$channel', subject = '$subject',
            body = '$body', media_url = '$media_url', updated_at = NOW()
        WHERE id = $id
    ";
    $result = mysqli_query($con, $query);
    echo json_encode(["success" => $result]);
    exit;
}
else if ($method == 'delete_template') {
    $id = intval($_POST['id']);

    $query = "UPDATE wamail_templates SET is_active = 0, updated_at = NOW() WHERE id = $id";
    $result = mysqli_query($con, $query);

    echo json_encode(["success" => $result]);
    exit;
}
