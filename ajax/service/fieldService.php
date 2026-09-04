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
$user_id = $_SESSION['memid'];

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

// if ($method == "getCustomerCounts") {

//     $result = [];

//     $agentId = $user_id;
//     $currentMonthStart = date('Y-m-01 00:00:00');
//     $now = date('Y-m-d H:i:s');

//     // -------- TOTAL COMPANIES --------
//     $sqlTotalComp = "
//         SELECT COUNT(DISTINCT aom.owner_id) AS total
//         FROM agent_owner_map aom
//         JOIN kyc_details kd kd.user_id = aom.owner_id 
//         WHERE aom.agent_id = '$agentId' AND kd.type = 'Owner'
//     ";
//     $res1 = mysqli_query($con, $sqlTotalComp);
//     $row1 = mysqli_fetch_assoc($res1);
//     $result['total_companies'] = (int)$row1['total'];
    
//     $sqlTotalComp = "
//         SELECT COUNT(DISTINCT aom.owner_id) AS total
//         FROM agent_owner_map aom
//         JOIN kyc_details kd kd.user_id = aom.owner_id 
//         WHERE aom.agent_id = '$agentId' AND kd.type = 'Driver'
//     ";
//     $res1 = mysqli_query($con, $sqlTotalComp);
//     $row1 = mysqli_fetch_assoc($res1);
//     $result['total_drivers'] = (int)$row1['total'];

//     // -------- MONTH COMPANIES --------
//     $sqlMonthComp = "
//         SELECT COUNT(DISTINCT aom.owner_id) AS total
//         FROM agent_owner_map aom
//         JOIN kyc_details kd kd.user_id = aom.owner_id 
//         WHERE aom.agent_id = '$agentId' AND kd.type = 'Owner' 
//         AND aom.created_at BETWEEN '$currentMonthStart' AND '$now'
//     ";
//     $res2 = mysqli_query($con, $sqlMonthComp);
//     $row2 = mysqli_fetch_assoc($res2);
//     $result['month_companies'] = (int)$row2['total'];
    
//     $sqlMonthComp = "
//         SELECT COUNT(DISTINCT aom.owner_id) AS total
//         FROM agent_owner_map aom
//         JOIN kyc_details kd kd.user_id = aom.owner_id 
//         WHERE aom.agent_id = '$agentId' AND kd.type = 'Driver'
//         AND aom.created_at BETWEEN '$currentMonthStart' AND '$now'
//     ";
//     $res2 = mysqli_query($con, $sqlMonthComp);
//     $row2 = mysqli_fetch_assoc($res2);
//     $result['month_companies'] = (int)$row2['total'];

//     // -------- TOTAL VEHICLES --------
//     $sqlTotalVeh = "
//         SELECT COUNT(v.id) AS total
//         FROM vehicle_master v
//         INNER JOIN agent_owner_map aom ON aom.owner_id = v.user_id
//         WHERE aom.agent_id = '$agentId'
//     ";
//     $res3 = mysqli_query($con, $sqlTotalVeh);
//     $row3 = mysqli_fetch_assoc($res3);
//     $result['total_vehicles'] = (int)$row3['total'];

//     // -------- MONTH VEHICLES --------
//     $sqlMonthVeh = "
//         SELECT COUNT(v.id) AS total
//         FROM vehicle_master v
//         INNER JOIN agent_owner_map aom ON aom.owner_id = v.user_id
//         WHERE aom.agent_id = '$agentId'
//         AND v.created_at BETWEEN '$currentMonthStart' AND '$now'
//     ";
//     $res4 = mysqli_query($con, $sqlMonthVeh);
//     $row4 = mysqli_fetch_assoc($res4);
//     $result['month_vehicles'] = (int)$row4['total'];

//     echo json_encode([
//         'status' => true,
//         'data' => $result
//     ]);
//     exit;
// }

if ($method == "getCustomerCounts") {

    $result = [];

    $agentId = $user_id;
    $currentMonthStart = date('Y-m-01 00:00:00');
    $now = date('Y-m-d H:i:s');

    // -------- TOTAL COMPANIES --------
    $sqlTotalComp = "
        SELECT COUNT(DISTINCT aom.owner_id) AS total
        FROM agent_owner_map aom
        JOIN kyc_details kd ON kd.user_id = aom.owner_id 
        WHERE aom.agent_id = '$agentId' 
        AND kd.type = 'Owner'
    ";
    $res1 = mysqli_query($con, $sqlTotalComp);
    $row1 = mysqli_fetch_assoc($res1);
    $result['total_companies'] = (int)$row1['total'];

    // -------- TOTAL DRIVERS --------
    $sqlTotalDriver = "
        SELECT COUNT(DISTINCT aom.owner_id) AS total
        FROM agent_owner_map aom
        JOIN kyc_details kd ON kd.user_id = aom.owner_id 
        WHERE aom.agent_id = '$agentId' 
        AND kd.type = 'Driver'
    ";
    $res2 = mysqli_query($con, $sqlTotalDriver);
    $row2 = mysqli_fetch_assoc($res2);
    $result['total_drivers'] = (int)$row2['total'];

    // -------- MONTH COMPANIES --------
    $sqlMonthComp = "
        SELECT COUNT(DISTINCT aom.owner_id) AS total
        FROM agent_owner_map aom
        JOIN kyc_details kd ON kd.user_id = aom.owner_id 
        WHERE aom.agent_id = '$agentId' 
        AND kd.type = 'Owner'
        AND aom.created_at BETWEEN '$currentMonthStart' AND '$now'
    ";
    $res3 = mysqli_query($con, $sqlMonthComp);
    $row3 = mysqli_fetch_assoc($res3);
    $result['month_companies'] = (int)$row3['total'];

    // -------- MONTH DRIVERS --------
    $sqlMonthDriver = "
        SELECT COUNT(DISTINCT aom.owner_id) AS total
        FROM agent_owner_map aom
        JOIN kyc_details kd ON kd.user_id = aom.owner_id 
        WHERE aom.agent_id = '$agentId' 
        AND kd.type = 'Driver'
        AND aom.created_at BETWEEN '$currentMonthStart' AND '$now'
    ";
    $res4 = mysqli_query($con, $sqlMonthDriver);
    $row4 = mysqli_fetch_assoc($res4);
    $result['month_drivers'] = (int)$row4['total'];

    // -------- TOTAL VEHICLES --------
    $sqlTotalVeh = "
        SELECT COUNT(v.id) AS total
        FROM vehicle_master v
        INNER JOIN agent_owner_map aom ON aom.owner_id = v.user_id
        WHERE aom.agent_id = '$agentId'
    ";
    $res5 = mysqli_query($con, $sqlTotalVeh);
    $row5 = mysqli_fetch_assoc($res5);
    $result['total_vehicles'] = (int)$row5['total'];

    // -------- MONTH VEHICLES --------
    $sqlMonthVeh = "
        SELECT COUNT(v.id) AS total
        FROM vehicle_master v
        INNER JOIN agent_owner_map aom ON aom.owner_id = v.user_id
        WHERE aom.agent_id = '$agentId'
        AND v.created_at BETWEEN '$currentMonthStart' AND '$now'
    ";
    $res6 = mysqli_query($con, $sqlMonthVeh);
    $row6 = mysqli_fetch_assoc($res6);
    $result['month_vehicles'] = (int)$row6['total'];

    echo json_encode([
        'status' => true,
        'data' => $result
    ]);
    exit;
}

elseif ($method == "getAgentCompanies") {

    $agentId = $user_id;
    $result = [];

    $sql = "
        SELECT 
            ur.id,
            ur.name,
            ur.mobile,
            ur.company_name,
            ur.doc_verify,
            kd.type,
            ur.vehicle_verify
        FROM user_register ur
        INNER JOIN agent_owner_map aom 
            ON aom.owner_id = ur.id
        INNER JOIN kyc_details kd 
            ON kd.user_id = ur.id
        WHERE aom.agent_id = '$agentId'
        AND ur.deletes = '0'
        AND ur.status = '0'
        AND kd.type = 'Owner'
        GROUP BY ur.id
        ORDER BY ur.id DESC
    ";

    $query = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_assoc($query)) {
        $result[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'mobile' => $row['mobile'],
            'company_name' => $row['company_name'],
            'doc_verify' => $row['doc_verify'],
            'type' => $row['type'],
            'vehicle_verify' => $row['vehicle_verify']
        ];
    }

    echo json_encode([
        'status' => true,
        'data' => $result
    ]);
    exit;
}

elseif ($method == "getAgentDrivers") {

    $agentId = $user_id;
    $result = [];

    $sql = "
        SELECT 
            ur.id,
            ur.name,
            ur.mobile,
            ur.company_name,
            ur.doc_verify,
            kd.type,
            ur.vehicle_verify
        FROM user_register ur
        INNER JOIN agent_owner_map aom 
            ON aom.owner_id = ur.id
        INNER JOIN kyc_details kd 
            ON kd.user_id = ur.id
        WHERE aom.agent_id = '$agentId'
        AND ur.deletes = '0'
        AND ur.status = '0'
        AND kd.type = 'Driver'
        GROUP BY ur.id
        ORDER BY ur.id DESC
    ";

    $query = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_assoc($query)) {
        $result[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'mobile' => $row['mobile'],
            'company_name' => $row['company_name'],
            'doc_verify' => $row['doc_verify'],
            'type' => $row['type'],
            'vehicle_verify' => $row['vehicle_verify']
        ];
    }

    echo json_encode([
        'status' => true,
        'data' => $result
    ]);
    exit;
}

?>