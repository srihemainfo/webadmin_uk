<?php
include '../../include/shi-config.php';
include '../../include/functions.php';
mysqli_set_charset($con, "utf8mb4");
// Optional but recommended:
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
$memid = $_SESSION['memid'];
$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";
$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";
$roleID = $_REQUEST['role'] ?? '';
if ($type == 'users') {
    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($roleID) AND" : "";
} else {
    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$roleID' AND" : "";
}
$headers = apache_request_headers();
$result = array();
$post_csrf = $headers['X-Csrf-Token'] ?? '';

if ($method == "list_agent") {
    // Fetches the logged-in admin's role
    $roll_id = select_top_name(
        $rcon,
        "user_register",
        "roll_id",
        "`id`='$memid' and `deletes`='0' order by `id` DESC",
        "roll_id",
        ""
    );

    // Array to safely hold all our WHERE conditions
    $query_parts = [];

    if ($roll_id != 1 && $roll_id != 2 && $roll_id != 4 && $roll_id != 11 && $roll_id != 8 && $_SESSION['memid'] != '8236') {
        $query_parts[] = "`created_by` = '$memid'";
    }

    $datefill = isset($_POST["datefill"]) ? mysqli_real_escape_string($con, trim($_POST["datefill"])) : '';
    $datefrom = isset($_POST["datefrom"]) ? mysqli_real_escape_string($con, trim($_POST["datefrom"])) : '';
    $now = date('Y-m-d');

    if ($datefrom != '' && $datefill != '') {
        $query_parts[] = "`created_at` BETWEEN '$datefrom' AND '$datefill'";
    }

    // 1. FIX: Full Name Search including First Name + Last Name
    $fieldname = isset($_POST["fieldname"]) ? mysqli_real_escape_string($con, trim($_POST["fieldname"])) : '';
    if ($fieldname != '') {
        $query_parts[] = "(`email` LIKE '%$fieldname%' OR `name` LIKE '%$fieldname%' OR `lname` LIKE '%$fieldname%' OR CONCAT(`name`, ' ', `lname`) LIKE '%$fieldname%' OR `mobile` LIKE '%$fieldname%')";
    }

    // 2. FIX: Default to '0' instead of 'Deleted' so the table actually loads data
    $deletedstatus = isset($_POST['deletedstatus']) ? mysqli_real_escape_string($con, trim($_POST['deletedstatus'])) : '';
    if ($deletedstatus !== '') {
        $query_parts[] = "`deletes`='$deletedstatus'";
    } else {
        $query_parts[] = "`deletes`='0'";
    }

    $type = $_POST['type'] ?? '';

    // 🚀 DYNAMIC TABLE SELECTION
    $tableName = "user_register"; // Default table for Agents, Staffs, Field Officers, etc.

    if ($type == 'users') {
        $query_parts[] = "(`roll_id` IN (5, 6, 7, 0))";
        $tableName = "customer_register"; // Query NEW table ONLY for users/customers
    } else if ($type == 'staffs') {
        $query_parts[] = "(`roll_id` IN (4, 12))";
    }

    if ($datefrom == '' && $datefill == '' && $fieldname == '' && $deletedstatus == '' && $type != 'agent' && $type != 'staffs') {
        $query_parts[] = "`created_at` LIKE '%$now%'";
    }

    // Safely apply global variables if they exist
    global $role, $whereClause;
    if (!empty($role)) {
        // Strip trailing AND if it was hardcoded in the old logic
        $clean_role = trim(preg_replace('/AND\s*$/i', '', trim($role)));
        if (!empty($clean_role)) $query_parts[] = $clean_role;
    }

    // 3. FIX: Safely build the final SQL query without breaking syntax
    $final_where = implode(" AND ", $query_parts);
    if (!empty($whereClause)) {
        $final_where .= " " . $whereClause;
    }

    // Fetch data using the dynamically set $tableName
    $user_register = select_query($rcon, $tableName, "", "$final_where ORDER BY `id` DESC", "", "");

    $result = [];

    if (isset($user_register['nr']) && $user_register['nr'] > 0) {
        foreach ($user_register['result'] as $key => $value) {
            $FRONT_img = select_query($rcon, "user_images", "", "`user_id`='{$value['id']}' and `img_url` != '' and `type` = 'FRONT' and `status`='0' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
            $BACK_img = select_query($rcon, "user_images", "", "`user_id`='{$value['id']}' and `img_url` != '' and `type` = 'BACK' and `status`='0' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
            $deletes = $value['deletes'];
            $action = '';

            $ow_count = select_query($rcon, "agent_owner_map", "COUNT(*) as o_count", "`agent_id`='{$value['id']}' and `status`=0 and `deletes`=0", "", "");

            if ($roll_id == 1) {
                if (in_array($value['roll_id'], [5, 6, 7, 8, 0])) {
                    // Users / Customers (Permission Removed Here)
                    // $action .= '<a href="' . $adminurl . 'profile/edit/permission/' . $value['id'] . '" style="cursor: pointer;"><span class="fe fe-edit" style="color: #576025db;font-size: 16px;font-weight: bold;">&nbsp;Permission</span></a>&nbsp;&nbsp;';
                    $action .= '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye" style="color: #49117cc9;font-size: 16px;font-weight: bold;">&nbsp;Profile</span></a>&nbsp;&nbsp;';
                    if (intval($deletes) != 1) {
                        $action .= '<a class="btn text-danger btn-sm" data-bs-target="#deleteinfo" data-bs-toggle="modal" data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2" style="color: #ff0000;font-size: 16px;font-weight: bold;" onclick=deletelist(' . $value['id'] . ')></span></a>&nbsp;&nbsp;';
                    }
                } else 
                
                if ($value['roll_id'] == 3) {
                    // Agents
                    $action .= '<a href="' . $adminurl . 'profile/edit/permission/' . $value['id'] . '" style="cursor: pointer;"><span class="fe fe-edit" style="color: #576025db;font-size: 16px;font-weight: bold;">&nbsp;Permission</span></a>&nbsp;&nbsp;';
                    $action .= '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye" style="color: #49117cc9;font-size: 16px;font-weight: bold;">&nbsp;Profile</span></a>&nbsp;&nbsp;';
                    if ($value['status'] != 1) {
                        $action .= '<a onclick="suspendagent(' . "'{$value['id']}'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #3c9306;font-size: 16px;font-weight: bold;">&nbsp;Active</span></a>&nbsp;&nbsp;';
                    } else {
                        $action .= '<a onclick="unsuspendagent(' . "'{$value['id']}'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #ff7c0b;font-size: 16px;font-weight: bold;">&nbsp;Inactive</span></a>&nbsp;&nbsp;';
                    }
                    if (intval($deletes) != 1) {
                        $action .= '<a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2" style="color: #ff0000;font-size: 16px;font-weight: bold;" onclick=agentdelete(' . $value['id'] . ')></span></a>';
                    }
                } else if ($value['roll_id'] == 4) {
                    // Staffs
                    $action .= '<a href="' . $adminurl . 'profile/edit/permission/' . $value['id'] . '" style="cursor: pointer;"><span class="fe fe-edit" style="color: #576025db;font-size: 16px;font-weight: bold;">&nbsp;Permission</span></a>&nbsp;&nbsp;';
                    $action .= '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye" style="color: #49117cc9;font-size: 16px;font-weight: bold;">&nbsp;Profile</span></a>&nbsp;&nbsp;';
                    if ($value['status'] != 1) {
                        $action .= '<a onclick="suspendagent(' . "'{$value['id']}'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #3c9306;font-size: 16px;font-weight: bold;">&nbsp;Active</span></a>&nbsp;&nbsp;';
                    } else {
                        $action .= '<a onclick="unsuspendagent(' . "'{$value['id']}'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #ff7c0b;font-size: 16px;font-weight: bold;">&nbsp;Inactive</span></a>&nbsp;&nbsp;';
                    }
                    if (intval($deletes) != 1) {
                        $action .= '<a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2" style="color: #ff0000;font-size: 16px;font-weight: bold;" onclick=agentdelete(' . $value['id'] . ')></span></a>';
                    }
                }
            } else {
                if ($value['roll_id'] == 0) {
                    $action = '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye" style="color: #49117cc9;font-size: 16px;font-weight: bold;">&nbsp;Profile</span></a>&nbsp;&nbsp;';
                    if ($value['img_url'] != '' || (isset($FRONT_img['result'][0]['img_url']) && $FRONT_img['result'][0]['img_url'] != '') || (isset($BACK_img['result'][0]['img_url']) && $BACK_img['result'][0]['img_url'] != '')) {
                        $action .= '<a class="btn text-danger btn-sm"   style="cursor: pointer;" onclick=previewp(' . $value['id'] . ') ><span class="fa fa-eye" ></span>&nbsp;Preview</a>&nbsp;&nbsp;';
                    }
                } else if (in_array($value['roll_id'], [6, 7, 9, 11])) {
                    $action = '';
                } else if (in_array($value['roll_id'], [3, 4, 5])) {
                    $action = '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye" style="color: #49117cc9;font-size: 16px;font-weight: bold;">&nbsp;Profile</span></a>&nbsp;&nbsp;';
                    if ($roll_id == 2) {
                        if ($value['status'] != 1) {
                            $action .= '<a onclick="suspendagent(' . "'{$value['id']}'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #3c9306;font-size: 16px;font-weight: bold;">&nbsp;Active</span></a>&nbsp;&nbsp;';
                        } else {
                            $action .= '<a onclick="unsuspendagent(' . "'{$value['id']}'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #ff7c0b;font-size: 16px;font-weight: bold;">&nbsp;Inactive</span></a>&nbsp;&nbsp;';
                        }
                    }
                }
            }

            $o_count = 0;
            if ($value['roll_id'] == 8 && isset($ow_count['result'][0]['o_count'])) {
                $o_count = $ow_count['result'][0]['o_count'];
            }

            $result[] = [
                "buildinglocation" => utf8_encode($value['address'] ?? ''),
                "o_count" => $o_count,
                "Country" => utf8_encode($value['nationality'] ?? ''),
                "City" => utf8_encode($value['city'] ?? ''),
                "residinglocation" => $value['residinglocation'] ?? '',
                "createdat" => $value['created_at'],
                "action" => $action,
                "id" => $value['id'],
                "nid" => str_pad($value['id'], 7, "0", STR_PAD_LEFT),
                "rollType" => $value['user'] ?? '',
                "deviceType" => $value["deviceType"] ?? '',
                "name" => trim(($value['name'] ?? '') . ' ' . ($value['lname'] ?? '')),
                "t_point" => $value['t_point'] ?? 0,
                "mobile" => $value['mobile'] ?? '',
                "email" => $value['email'] ?? '',
                "walletBalance" => $value['walletBalance'] ?? '0.00', // Added
                "cash_points" => $value['cash_points'] ?? '0',     // Added
                "dob" => $value['dob'] ?? '',
                "password" => $value['password'] ?? ''
            ];
        }
    }

    // Force JSON output
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($result, JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}
// ---------------------------------------------------------
// GET USER REMARKS
// ---------------------------------------------------------
// ---------------------------------------------------------
// GET USER REMARKS
// ---------------------------------------------------------
elseif ($method == "get_user_remarks") {
    try {
        $user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($con, trim($_POST['user_id'])) : '';
        
        $result = [];
        $result["type"] = 0;
        $result["data"] = [];

        if (empty($user_id)) {
            $result["message"] = "User ID is missing";
            goto result_get_remarks;
        }

        // JOIN user_remarks with user_register to get the actual name instead of the ID
        $query = "SELECT r.remarks, r.created_at, 
                         CONCAT(u.name, ' ', IFNULL(u.lname, '')) AS contacted_person_name 
                  FROM user_remarks r 
                  LEFT JOIN user_register u ON r.contacted_person = u.id 
                  WHERE r.user_id = '$user_id' 
                  ORDER BY r.created_at DESC";
                  
        $result_data = mysqli_query($con, $query);

        if ($result_data && mysqli_num_rows($result_data) > 0) {
            while ($row = mysqli_fetch_assoc($result_data)) {
                // Format the date to look clean for the frontend
                $row['created_at'] = date('d-m-Y H:i:s', strtotime($row['created_at']));
                
                // Assign the fetched name. If not found, fallback to 'Admin'
                $row['contacted_person'] = !empty(trim($row['contacted_person_name'])) ? trim($row['contacted_person_name']) : 'Admin';
                
                $result["data"][] = $row;
            }
            $result["type"] = 1;
        } else {
            $result["type"] = 1; // Set to 1 so the frontend doesn't throw an error, just shows empty
            $result["message"] = "No remarks found.";
        }

    } catch (\Exception $e) {
        $result["type"] = 0;
        $result["message"] = $e->getMessage();
    }

    result_get_remarks:
    ob_clean(); // This cleans any hidden PHP warnings so the JSON doesn't break!
    echo json_encode($result);
    exit;

// ---------------------------------------------------------
// SAVE USER REMARK
// ---------------------------------------------------------
} elseif ($method == "save_user_remark") {
    try {
        // Ensure session is started to fetch logged-in user ID
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($con, trim($_POST['user_id'])) : '';
        $remarks = isset($_POST['remarks']) ? mysqli_real_escape_string($con, trim($_POST['remarks'])) : '';
        
        // FIX 1 & 2: Grab memid from the AJAX POST first, then fallback to the correct $_SESSION['memid']
        $post_memid = isset($_POST['memid']) ? trim($_POST['memid']) : '';
        $session_memid = isset($_SESSION['memid']) ? $_SESSION['memid'] : '1';
        
        // Decide which one to use (prioritize the one sent via AJAX)
        $logged_in_id = !empty($post_memid) ? $post_memid : $session_memid;
        $logged_in_id = mysqli_real_escape_string($con, $logged_in_id);

        $result = [];
        $result["type"] = 0;

        if (empty($user_id) || empty($remarks)) {
            $result["message"] = "Remark text cannot be empty.";
            goto result_save_remark;
        }

        // Insert the numeric ID 
        $query = "INSERT INTO user_remarks (user_id, contacted_person, remarks) VALUES ('$user_id', '$logged_in_id', '$remarks')";
        
        if (mysqli_query($con, $query)) {
            $result["type"] = 1;
            $result["message"] = "Remark added successfully!";
        } else {
            $result["type"] = 0;
            $result["message"] = "Database error: " . mysqli_error($con);
        }

    } catch (\Exception $e) {
        $result["type"] = 0;
        $result["message"] = $e->getMessage();
    }

    result_save_remark:
    ob_clean(); // This cleans any hidden PHP warnings so the JSON doesn't break!
    echo json_encode($result);
    exit;
} elseif ($method == "list_agent_copy") {
    $roll_id = select_top_name($rcon, "user_register", "roll_id", "`id`='$memid' and `deletes`='0'  order by `id` DESC ", "roll_id", "");
    // var_dump($roll_id);die;
    if ($roll_id != 1 && $roll_id != 2 && $roll_id != 4 && $roll_id != 11 && $roll_id != 8 && $_SESSION['memid'] != '8236') {
        $condition = "`created_by` = '$memid' AND";
    } else {
        $condition = "";
    }
    $datefill = BlockSQLInjectionforagent($_POST["datefill"]);
    $datefrom = BlockSQLInjectionforagent($_POST["datefrom"]);
    $now = date('Y-m-d');
    if ($datefrom != '' && $datefill != '') {
        $contype = "`created_at` BETWEEN '$datefrom 00:00:00' AND '$datefill 23:59:59' AND";
    } else {
        // $contype = "`created_at` LIKE '%$now%' AND";
        // $contype = '';
    }
    // var_dump($contype);die;
    $fieldname = BlockSQLInjectionforagent($_POST["fieldname"]);
    if ($fieldname != '') {
        // $contype = '';
        $fieldcon = "(`email` LIKE '%" . $fieldname . "%' OR `name` LIKE '%" . $fieldname . "%' OR `mobile` LIKE '%" . $fieldname . "%' )  AND ";
    } else {
        $fieldcon = "";
    }
    // $deletedstatus = BlockSQLInjectionforagent($_POST["deletedstatus"]);
    $deletedstatus = $_POST['deletedstatus'];
    if ($deletedstatus != '') {
        $deletecon = "`deletes`='$deletedstatus'";
    } else {
        $deletecon = "`deletes`='Deleted'";
    }
    $type = $_POST['type'];
    if ($type == 'agent') {
        $roll_id1 = "`roll_id`=3 AND ";
    } else if ($type == 'staffs') {
        $roll_id1 = "(`roll_id`= 4 OR `roll_id` = 12) AND ";
    }
    if ($datefrom == '' && $datefill == '' && $fieldname == '' && $deletedstatus == '' && $type != 'agent' && $type != 'staffs') {
        $contype = "`created_at` LIKE '%$now%' AND";
    }
    $user_register = select_query($rcon, "user_register", "", "$condition $role $contype $fieldcon $roll_id1 $deletecon $whereClause order by `id` DESC", "", "");
    if ($user_register['nr'] > 0) {
        foreach ($user_register['result'] as $key => $value) {
            $FRONT_img = select_query($rcon, "user_images", "", "`user_id`='$value[id]' and `img_url` != '' and `type` = 'FRONT' and `status`='0' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
            $BACK_img = select_query($rcon, "user_images", "", "`user_id`='$value[id]' and `img_url` != '' and `type` = 'BACK' and `status`='0' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
            $deletes = $value['deletes'];
            $action = '';
            // var_dump($roll_id);die;  
            if ($roll_id == 1) {
                if ($value['roll_id'] == 0) {
                    $action = '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye" style="color: #49117cc9;font-size: 16px;font-weight: bold;">&nbsp;Profile</span></a>&nbsp;&nbsp;';
                    if ($value['img_url'] != '' || $FRONT_img['result'][0]['img_url'] != '' || $BACK_img['result'][0]['img_url'] != '') {
                        $action .= '<a class="btn text-danger btn-sm"   style="cursor: pointer;" onclick=previewp(' . $value['id'] . ') ><span class="fa fa-eye" ></span>&nbsp;Profile Image</a>&nbsp;&nbsp;';
                    }
                    if (intval($deletes) != 1) {
                        $action .= '<a class="btn text-danger btn-sm" data-bs-target="#deleteinfo" data-bs-toggle="modal" data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2" style="color: #ff0000;font-size: 16px;font-weight: bold;" onclick=deletelist(' . $value['id'] . ')></span></a>&nbsp;&nbsp;';
                    }
                } else if (in_array($value['roll_id'], [6, 7, 9, 11])) {
                    $action = '<a href="' . $adminurl . 'profile/edit/permission/' . $value['id'] . '" style="cursor: pointer;"><span class="fe fe-edit" style="color: #576025db; font-size: 16px;font-weight: bold;">&nbsp;Permission</span></a>&nbsp;&nbsp;';
                    $action .= '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye" style="color: #49117cc9;font-size: 16px;font-weight: bold;">&nbsp;Profile</span></a>&nbsp;&nbsp;';
                    if ($value['roll_id'] == 7) {
                        if ($value['status'] != 1) {
                            $action .= '<a onclick="suspendaffiliate(' . "'$value[id]'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #3c9306;font-size: 16px;font-weight: bold;">&nbsp;Active</span></a>&nbsp;&nbsp;';
                        } else {
                            $action .= '<a onclick="unsuspendaffiliate(' . "'$value[id]'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #ff7c0b;font-size: 16px;font-weight: bold;">&nbsp;Inactive</span></a>&nbsp;&nbsp;';
                        }
                        if (intval($deletes) != 1) {
                            $action .= '<a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2" style="color: #ff0000;font-size: 16px;font-weight: bold;" onclick=affiliatedelete(' . $value['id'] . ')></span></a>';
                        }
                    }
                    if ($value['roll_id'] == 6) {
                        if (intval($deletes) != 1) {
                            $action .= '<a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2" style="color: #ff0000;font-size: 16px;font-weight: bold;" onclick=affiliatedelete(' . $value['id'] . ')></span></a>';
                        }
                    }
                } else if ($value['roll_id'] == 3) {
                    // $action .= '<a href="' . $adminurl . 'profile/edit/permission/' . $value['id'] . '" style="cursor: pointer;"><span class="fe fe-edit" style="color: #576025db;font-size: 16px;font-weight: bold;">&nbsp;Permission</span></a>&nbsp;&nbsp;';
                    $action .= '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye" style="color: #49117cc9;font-size: 16px;font-weight: bold;">&nbsp;Profile</span></a>&nbsp;&nbsp;';
                    if ($value['status'] != 1) {
                        $action .= '<a onclick="suspendagent(' . "'$value[id]'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #3c9306;font-size: 16px;font-weight: bold;">&nbsp;Active</span></a>&nbsp;&nbsp;';
                    } else {
                        $action .= '<a onclick="unsuspendagent(' . "'$value[id]'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #ff7c0b;font-size: 16px;font-weight: bold;">&nbsp;Inactive</span></a>&nbsp;&nbsp;';
                    }
                    if (intval($deletes) != 1) {
                        $action .= '<a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2" style="color: #ff0000;font-size: 16px;font-weight: bold;" onclick=agentdelete(' . $value['id'] . ')></span></a>';
                    }
                } else if ($value['roll_id'] == 4) {
                    $action .= '<a href="' . $adminurl . 'profile/edit/permission/' . $value['id'] . '" style="cursor: pointer;"><span class="fe fe-edit" style="color: #576025db;font-size: 16px;font-weight: bold;">&nbsp;Permission</span></a>&nbsp;&nbsp;';
                    $action .= '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye" style="color: #49117cc9;font-size: 16px;font-weight: bold;">&nbsp;Profile</span></a>&nbsp;&nbsp;';
                    if ($value['status'] != 1) {
                        $action .= '<a onclick="suspendagent(' . "'$value[id]'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #3c9306;font-size: 16px;font-weight: bold;">&nbsp;Active</span></a>&nbsp;&nbsp;';
                    } else {
                        $action .= '<a onclick="unsuspendagent(' . "'$value[id]'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #ff7c0b;font-size: 16px;font-weight: bold;">&nbsp;Inactive</span></a>&nbsp;&nbsp;';
                    }
                    if (intval($deletes) != 1) {
                        $action .= '<a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2" style="color: #ff0000;font-size: 16px;font-weight: bold;" onclick=agentdelete(' . $value['id'] . ')></span></a>';
                    }
                }
            } else {
                if ($value['roll_id'] == 0) {
                    $action = '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye" style="color: #49117cc9;font-size: 16px;font-weight: bold;">&nbsp;Profile</span></a>&nbsp;&nbsp;';
                    // $action = '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye"></span></a>';
                    if ($value['img_url'] != '' || $FRONT_img['result'][0]['img_url'] != '' || $BACK_img['result'][0]['img_url'] != '') {
                        // $action .= '<a class="btn text-danger btn-sm"   style="cursor: pointer;" onclick=previewp(' . $value['id'] . ') ><span class="fa fa-eye" ></span>&nbsp;Preview</a>';
                        $action .= '<a class="btn text-danger btn-sm"   style="cursor: pointer;" onclick=previewp(' . $value['id'] . ') ><span class="fa fa-eye" ></span>&nbsp;Preview</a>&nbsp;&nbsp;';
                    }
                } else if (in_array($value['roll_id'], [6, 7, 9, 11])) {
                    $action = '';
                } else if (in_array($value['roll_id'], [3, 4, 5])) {
                    // $action = '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye"></span></a>';
                    $action = '<a href="' . $adminurl . 'profile/edit/' . $value['id'] . '" style="cursor: pointer;"><span class="fa fa-eye" style="color: #49117cc9;font-size: 16px;font-weight: bold;">&nbsp;Profile</span></a>&nbsp;&nbsp;';
                    if ($roll_id == 2) {
                        if ($value['status'] != 1) {
                            $action .= '<a onclick="suspendagent(' . "'$value[id]'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #3c9306;font-size: 16px;font-weight: bold;">&nbsp;Active</span></a>&nbsp;&nbsp;';
                            // $action .= '&nbsp;<a onclick="suspendagent(' . "'$value[id]'" . ')" style="cursor: pointer; color: #ff7c0b;"><span class="fa fa-lightbulb-o">Active</span></a>';
                        } else {
                            $action .= '<a onclick="unsuspendagent(' . "'$value[id]'" . ')" style="cursor: pointer;"><span class="fa fa-lightbulb-o" style="color: #ff7c0b;font-size: 16px;font-weight: bold;">&nbsp;Inactive</span></a>&nbsp;&nbsp;';
                            // $action .= '&nbsp;<a onclick="unsuspendagent(' . "'$value[id]'" . ')" style="cursor: pointer; color: #020202;"><span class="fa fa-lightbulb-o">Inactive</span></a>';
                        }
                    }
                }
            }
            // echo '<pre>';
            // var_dump($value);die;
            // echo '</pre>';
            // $result[] = ["buildinglocation" => utf8_encode($value['address']), "Country" => utf8_encode($value['nationality']), "City" => utf8_encode($value['city']), "residinglocation" => $value['residinglocation'], "createdat" => $value['created_at'], "action" => $action, "id" => $value['id'], "nid" => str_pad($value['id'], 7, "0", STR_PAD_LEFT), "rollType" => $value['user'], "name" => $value['name'] . ' ' . $value['lname'], "t_point" => $value['t_point'], "walletBalance" => $value['walletBalance'], "mobile" => $value['mobile'], "passport" => utf8_encode($value['passport']), "email" => $value['email'], "dob" => $value['dob'], "password" => $value['password']];
            $result[] = ["buildinglocation" => utf8_encode($value['address']), "Country" => utf8_encode($value['nationality']), "City" => utf8_encode($value['city']), "residinglocation" => $value['residinglocation'], "createdat" => $value['created_at'], "action" => $action, "id" => $value['id'], "nid" => str_pad($value['id'], 7, "0", STR_PAD_LEFT), "rollType" => $value['user'], "deviceType" => $value["deviceType"], "name" => $value['name'] . ' ' . $value['lname'], "t_point" => $value['t_point'], "mobile" => $value['mobile'], "email" => $value['email'], "dob" => $value['dob'], "password" => $value['password']];
        }
    }
    echo json_encode($result);
} elseif ($method == "list_agent2") {
        try {
            // Get POST parameters
            $startDate   = isset($_POST['datefrom']) ? trim($_POST['datefrom']) : '';
            $endDate     = isset($_POST['datefill']) ? trim($_POST['datefill']) : '';
            $searchTxt   = isset($_POST['fieldname']) ? trim($_POST['fieldname']) : '';
            $u_type      = isset($_POST['kyc_filter']) ? trim($_POST['kyc_filter']) : '';
            $deletedstatus = isset($_POST['deletedstatus']) ? trim($_POST['deletedstatus']) : '';
            $user_type   = isset($_POST['user_type']) ? trim($_POST['user_type']) : '';

            // Escape all inputs to prevent SQL injection
            $startDate   = mysqli_real_escape_string($con, $startDate);
            $endDate     = mysqli_real_escape_string($con, $endDate);
            $searchTxt   = mysqli_real_escape_string($con, $searchTxt);
            $u_type      = mysqli_real_escape_string($con, $u_type);
            $deletedstatus = mysqli_real_escape_string($con, $deletedstatus);
            $user_type   = mysqli_real_escape_string($con, $user_type);

            $filterParts = [];
            $result = [];

            // 1. Search by mobile number (if provided)
            if (!empty($searchTxt)) {
                $filterParts[] = "(cu.mobile LIKE '%$searchTxt%')";
            }

            // 2. Driver/Owner filter (if provided)
            if (!empty($user_type)) {
                $filterParts[] = "kd.type = '$user_type'";
            }

            // 3. Date range filter – only if BOTH dates are provided
            if (!empty($startDate) && !empty($endDate)) {
                $filterParts[] = "(cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59')";
            }

            // 4. KYC/vehicle status filter (u_type)
            if ($u_type == 'all') {
                $filterParts[] = "cu.roll_id = '0' AND cu.deletes = '$deletedstatus'";
            } elseif ($u_type == 'k_n_c') {
                $filterParts[] = "
                    cu.doc_verify = '0'
                    AND (kd.id IS NULL OR kd.o_status < 3)
                    AND cu.deletes = '$deletedstatus'
                ";
            } elseif ($u_type == 'k_n_v') {
                $filterParts[] = "
                    cu.doc_verify = '0'
                    AND kd.updated_by IS NULL
                    AND kd.reject_reason IS NULL
                    AND kd.id IS NOT NULL
                    AND cu.deletes = '$deletedstatus'
                ";
            } elseif ($u_type == 'k_r') {
                $filterParts[] = "
                    cu.doc_verify = '0'
                    AND kd.updated_by IS NOT NULL
                    AND kd.reject_reason IS NOT NULL
                    AND kd.id IS NOT NULL
                    AND cu.deletes = '$deletedstatus'
                ";
            } elseif ($u_type == 'k_v') {
                $filterParts[] = "
                    (
                        (cu.doc_verify = '1' AND cu.vehicle_verify = '2' AND kd.type = 'Driver'
                         AND JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.admin_verify')) = 'true'
                         AND JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.vehicle_review_message')) LIKE '%Vehicle details verified%')
                        OR
                        (cu.doc_verify = '1' AND kd.o_proof_status = 'approved' AND kd.type = 'Owner')
                    )
                    AND cu.deletes = '$deletedstatus'
                ";
            } elseif ($u_type == 'v_n_c') {
                $filterParts[] = "
                    cu.vehicle_verify = '0'
                    AND cu.vehicle_details IS NULL
                    AND cu.deletes = '$deletedstatus'
                ";
            } elseif ($u_type == 'v_n_v') {
                $filterParts[] = "
                    cu.vehicle_verify != '2'
                    AND cu.vehicle_details IS NOT NULL
                    AND cu.deletes = '$deletedstatus'
                ";
            } elseif ($u_type == 'v_r') {
                $filterParts[] = "
                    JSON_EXTRACT(cu.vehicle_details, '$.admin_verify') = false
                    AND (
                        JSON_EXTRACT(cu.vehicle_details, '$.vehicle_review_message') IS NULL
                        OR JSON_EXTRACT(cu.vehicle_details, '$.vehicle_review_message') NOT LIKE '%wait for admin approval%'
                    )
                    AND cu.deletes = '$deletedstatus'
                ";
            } elseif ($u_type == 'v_v') {
                $filterParts[] = "
                    cu.vehicle_verify = '2'
                    AND kd.type = 'Driver'
                    AND JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.admin_verify')) = 'true'
                    AND JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.vehicle_review_message')) LIKE '%Vehicle details verified%'
                    AND cu.deletes = '$deletedstatus'
                ";
            }

            // Build WHERE clause
            $whereClause = '';
            if (!empty($filterParts)) {
                $whereClause = 'WHERE ' . implode(' AND ', $filterParts);
            }

            // Final query with ORDER BY at the end
            // Added kd.id as kyc_id right here:
            $s_query = "
                SELECT 
                    cu.id as nid,
                    cu.name,
                    cu.lname,
                    cu.mobile,
                    cu.email,
                    cu.doc_verify,
                    cu.vehicle_verify,
                    cu.vehicle_details,
                    cu.created_at as createdat,
                    cu.walletBalance,
                    cu.winningBalance,
                    kd.type as type,
                    kd.id as kyc_id,
                    kd.*
                FROM user_register cu
                LEFT JOIN kyc_details kd 
                    ON kd.user_id = cu.id AND kd.deletes = 0
                $whereClause
                ORDER BY kd.updated_at DESC, cu.v_updated_at DESC
            ";

            $result_data = mysqli_query($con, $s_query);
            if ($result_data && mysqli_num_rows($result_data) > 0) {
                while ($row = mysqli_fetch_assoc($result_data)) {
                    $result['result'][] = $row;
                }
                $result['type'] = '1';
            } else {
                $result['type'] = '0';
                $result['result'] = [];
            }
        } catch (\Exception $e) {
            $result['type'] = '0';
            $result['result'] = [];
        }

        resutGJHIPPPP:
        echo json_encode($result);
    }else if ($method == "list_oticket") {
    // $result = [];
    $contype = '';
    $type = 'OT';
    $da = '';
    $userType = '';
    $now = date('Y-m-d');
    $userType1 = $_POST['userType'];
    // var_dump($userType);die;
    if ($userType1 == 'customer') {
        $userType = "`agentId`= 0 AND";
    } else if ($userType1 == 'agent') {
        $userType = "`agentId`!= 0 AND";
    } else {
        $userType = '';
    }
    $formdate = BlockSQLInjectionforagent($_POST["agdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    // $formdate = $_POST['formdate'];
    // $todate = $_POST['todate'];
    // var_dump($formdate,$todate);die;
    if ($formdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        // $contype = "`purchaseDatetime` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
        $contype = "`updatedon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $da = 'DESC';
        $contype = "`updatedon` LIKE '%$now%' AND";
    }
    // $NDticketquery = mysqli_query($con, "SELECT nd.*, ur.name, ur.lname, ur.mobile, ur.email, CONCAT(ur2.name, ' ', ur2.lname) AS agentName,
    //     (SELECT product_id FROM invoice WHERE invoice.ticketId = nd.id LIMIT 1) as Product,
    //     (SELECT rate FROM product WHERE product.id = Product LIMIT 1) as Product_rate
    //     FROM `ndticket` nd
    //     JOIN `user_register` ur ON nd.userId = ur.id 
    //     LEFT JOIN `user_register` ur2 ON nd.agentId = ur2.id OR (nd.agentId = '' AND ur2.id = '')
    //     WHERE $contype $userType nd.id != '' AND nd.deletes != '1'");
    // $NDticketquery = mysqli_query($con, "SELECT 
    //     nd.id, nd.referenceID,nd.agentId, nd.grandtotal,nd.invoiceNo, nd.ticketNo, nd.raffleIds, 
    //     nd.purchaseDatetime, nd.startDate, nd.endDate, 
    //     ur.name, ur.lname, ur.mobile, ur.email, 
    //     CONCAT(ur2.name, ' ', ur2.lname) AS agentName, 
    //     invoice.product_id AS Product, 
    //     product.rate AS Product_rate 
    // FROM 
    //     `ndticket` nd 
    //     JOIN `user_register` ur ON nd.userId = ur.id 
    //     LEFT JOIN `user_register` ur2 ON nd.agentId = ur2.id OR (nd.agentId = '' AND ur2.id = '') 
    //     LEFT JOIN `invoice` ON invoice.ticketId = nd.id
    //     LEFT JOIN `product` ON product.id = invoice.product_id
    // WHERE 
    //     $contype $userType nd.id != '' 
    //     AND nd.deletes != '1';");
    // $NDticketquery = mysqli_query($con, "SELECT id,referenceID,agentId,userId,ticketNo,purchaseDatetime,discount,totalAmt,grandtotal,startDate,endDate,createdon FROM `ndticket` WHERE deletes = '0';");
    $selectQuery = "SELECT id,referenceID,agentId,userId,ticketNo,invoiceNo,raffleIds,purchaseDatetime,discount,totalAmt,grandtotal,startDate,endDate,createdon FROM `ndticket` WHERE $contype $userType deletes = '0';";
    $runQuery = mysqli_query($con, $selectQuery);
    if ($runQuery && mysqli_num_rows($runQuery) > 0) {
        $result = [];
        while ($row = mysqli_fetch_assoc($runQuery)) {
            $userID = $row['userId'];
            $agentId = $row['agentId'];
            $name = select_top_name($con, "user_register", "name", "`id`='$userID' and `deletes`='0'", "name", "");
            $lname = select_top_name($con, "user_register", "lname", "`id`='$userID' and `deletes`='0'", "lname", "");
            $fullname = $name . ' ' . $lname;
            $agent_name = select_top_name($con, "user_register", "name", "`id`='$agentId' and `deletes`='0'", "name", "");
            $agent_lname = select_top_name($con, "user_register", "lname", "`id`='$agentId' and `deletes`='0'", "lname", "");
            $agent_fullname = $agent_name . ' ' . $agent_lname;
            $mobile = select_top_name($con, "user_register", "mobile", "`id`='$userID' and `deletes`='0'", "mobile", "");
            $email = select_top_name($con, "user_register", "email", "`id`='$userID' and `deletes`='0'", "email", "");
            // var_dump($payment_trans_id);die;
            $result[] = [
                "id" => $row['id'],
                "fullname" => $fullname,
                "mobile" => $mobile,
                "email" => $email,
                "agentName" => $agent_fullname,
                "agentId" => $row['agentId'],
                "ticketNo" => $row['ticketNo'],
                "invoiceNo" => $row['invoiceNo'],
                "referenceID" => $row['referenceID'],
                "raffleIds" => $row['raffleIds'],
                "grandtotal" => $row['grandtotal'],
                "discount" => $row['discount'],
                "purchaseDatetime" => $row['purchaseDatetime'],
                "startDate" => $row['startDate'],
                "endDate" => $row['endDate'],
            ];
        }
    }
    echo json_encode($result);
    // }
    // if (mysqli_num_rows($NDticketquery) > 0) {
    //     $result = mysqli_fetch_all($NDticketquery, MYSQLI_ASSOC);
    // }
    // echo json_encode($result);
} else if ($method == "Ticket_History") {
    // $result = [];
    $contype = '';
    $da = '';
    $now = date('Y-m-d');
    $ticketID = $_POST['ticketID'];
    // var_dump($ticketID);die;
    if ($userType1 == 'customer') {
        $userType = '0';
    } else {
    }
    $formdate = BlockSQLInjectionforagent($_POST["formdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    // $formdate = $_POST['formdate'];
    // $todate = $_POST['todate'];
    // var_dump($formdate,$todate);die;
    if ($formdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "T.`purchaseDatetime` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $da = 'DESC';
        // $contype = "T.`purchaseDatetime` LIKE '%$now%' AND";
        $contype = " ";
    }
    //   var_dump('hiiiiii');die;
    // $NDticketquery = mysqli_query($con, "SELECT I.netTotal,I.createdon, I.renewalStatus,I.startDate,I.endDate, T.ticketNo, T.raffleIds
    //     FROM ndticket AS T
    //     JOIN `invoice` AS I ON I.ticketId = T.id
    //     WHERE T.id = $ticketID");
    $NDticketquery = mysqli_query($con, "SELECT I.id,I.netTotal,I.cart, I.createdon,I.ticketReferenceID,I.payment_transaction_id,T.referenceID, I.renewalStatus, I.startDate, I.endDate, I.deliveryType, I.delivery_status, T.ticketNo, T.raffleIds, CASE WHEN I.netTotal <= 7 THEN 'New Purchased' WHEN I.netTotal >= 10 AND I.netTotal <= 300 THEN 'Monthly' ELSE 'Yearly' END AS netTotalStatus FROM ndticket AS T JOIN `invoice` AS I ON I.ticketId = T.id WHERE $contype T.id = $ticketID");
    if (mysqli_num_rows($NDticketquery) > 0) {
        $result = mysqli_fetch_all($NDticketquery, MYSQLI_ASSOC);
    }
    // var_dump($result);die;
    echo json_encode($result);
} else if ($method == "update_delivery_status") {
    // var_dump('kinmg');die;
    $rowId = BlockSQLInjectionforagent($_POST["id"]);
    $newStatus = BlockSQLInjectionforagent($_POST["status"]);
    // $formdate = $_POST['formdate'];
    // $todate = $_POST['todate'];
    // var_dump($newStatus);die;
    //   $updatedon = date('Y-m-d H:i:s');
    $updateQuery = "UPDATE invoice SET delivery_status = '$newStatus', updatedon = '$dubaidate_time' WHERE id = '$rowId'";
    if (mysqli_query($con, $updateQuery)) {
        echo json_encode(array("type" => 1, "status" => "success", "message" => "Delivery status updated successfully"));
    } else {
        echo json_encode(array("type" => 0, "status" => "error", "message" => "Error updating delivery status"));
    }
} else if ($method == "ND_invoice") {
    //  var_dump('hellow');die;
    // $result = [];
    $contype = '';
    $type = 'OT';
    $da = '';
    $userType1 = '';
    $userType = '';
    $draw_Con = '';
    $now = date('Y-m-d');
    $userType1 = $_POST['userType'];
    // var_dump($userType1);die;
    $selectedProductId = $_POST['selectedProductId'];
    if (isset($_POST["selectedProductId"]) && $_POST["selectedProductId"] != '') {
        $productRate = "nd.`product_id` = '" . $_POST["selectedProductId"] . "' AND";
    }
    // var_dump($productRate);die;
    $formdate = BlockSQLInjectionforagent($_POST["agdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    $searchTxt = BlockSQLInjectionforagent($_REQUEST["search_id"]);
    // $formdate = $_POST['formdate'];
    // $todate = $_POST['todate'];
    // var_dump($productRate,$userType1);die;
    if ($formdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "nd.`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $contype = '';
    }
    if ($userType1 == 'New') {
        $userType = "nd.`renewalStatus`= 'NEW' AND";
    } else if ($userType1 == 'Renewal') {
        $userType = "nd.`renewalStatus`= 'RENEWAL' AND";
    } else if ($userType1 == 'Wallet') {
        $userType = "nd.`paymentType`= 'Wallet' AND";
    } else if ($userType1 == 'Agent') {
        $userType = "nd.`agent_id`!= '' AND";
    } else if ($userType1 == 'Discount_code') {
        $userType = "d.`type` IN ('coupon', 'general') AND";
    } else if ($userType1 == 'flatAgent') {
        $userType = "d.`type` IN ('flatAgent') AND";
    } else {
        $userType = '';
    }
    if ($searchTxt != '') {
        $draw_Con = "(nd.firstname LIKE '%$searchTxt%' OR nd.emailid LIKE '%$searchTxt%' OR nd.mobile LIKE '%$searchTxt%') AND ";
    }
    if ($searchTxt == '' && $userType1 == '' && $formdate == '' && $todate == '') {
        $da = 'DESC';
        $contype = "nd.`createdon` LIKE '%$now%' AND";
    }
    // var_dump("SELECT 
    //             nd.*, 
    //             CONCAT(ur2.name, ' ', ur2.lname) AS agentName, 
    //             p.rate AS Product_rate
    //         FROM 
    //             `invoice` nd
    //         JOIN 
    //             `user_register` ur ON nd.user_id = ur.id
    //         LEFT JOIN 
    //             `user_register` ur2 ON nd.agent_id = ur2.id
    //         LEFT JOIN 
    //             `product` p ON nd.product_id = p.id
    //         WHERE 
    //              $contype $userType $draw_Con $productRate
    //              nd.id != '' 
    //             AND nd.deletes != '1'
    //         ORDER BY 
    //             nd.id DESC;
    //         ");die;
    $NDticketquery = mysqli_query($con, "SELECT 
                nd.*, 
                CONCAT(ur2.name, ' ', ur2.lname) AS agentName, 
                p.rate AS Product_rate,
                d.type as discountType
            FROM 
                `invoice` nd
            JOIN 
                `user_register` ur ON nd.user_id = ur.id
            LEFT JOIN 
                `user_register` ur2 ON nd.agent_id = ur2.id
            LEFT JOIN 
                `product` p ON nd.product_id = p.id
            LEFT JOIN discount_periods d ON d.id = nd.discountID
            WHERE 
                 $contype $userType $draw_Con $productRate
                 nd.id != '' 
                AND nd.deletes != '1'
            ORDER BY 
                nd.id DESC;
            ");
    if (mysqli_num_rows($NDticketquery) > 0) {
        $result = mysqli_fetch_all($NDticketquery, MYSQLI_ASSOC);
    }
    echo json_encode($result);
} else if ($method == "Deleted_Request") {
    //  var_dump('hellow');die;
    // $result = [];
    $contype = '';
    $draw_Con = '';
    $user_Status = '';
    $now = date('Y-m-d');
    // $userType1 = $_POST['userType'];
    // var_dump($userType1);die;
    $selectedProductId = $_POST['selectedProductId'];
    $formdate = BlockSQLInjectionforagent($_POST["agdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    $searchTxt = BlockSQLInjectionforagent($_REQUEST["search_id"]);
    $userType = BlockSQLInjectionforagent($_REQUEST["userType"]);
    // var_dump($userType);die;
    // $formdate = $_POST['formdate'];
    // $todate = $_POST['todate'];
    // var_dump($productRate,$userType1);die;
    if ($formdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "d.`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $contype = '';
    }
    if ($searchTxt != '') {
        $draw_Con = "(ur.name LIKE '%$searchTxt%' OR ur.email LIKE '%$searchTxt%' OR ur.mobile LIKE '%$searchTxt%') AND ";
    }
    if ($userType != '') {
        $user_Status = "d.status= '$userType' AND";
    }
    // if($searchTxt == '' && $userType1 =='' && $formdate == '' && $todate == ''){
    //      $da = 'DESC';
    //     $contype = "d.`createdon` LIKE '%$now%' AND";
    // }
    $DeletedRequest = mysqli_query($con, "SELECT d.account_expire_date,d.deleted_by, d.deleted_at, d.status, d.createdon, ur.name, ur.lname, ur.mobile, ur.email
        FROM `user_delete_request` d
        JOIN `user_register` ur ON d.requestID = ur.id
        WHERE $contype $draw_Con $user_Status ur.id != '';
        ");
    if (mysqli_num_rows($DeletedRequest) > 0) {
        while ($row = mysqli_fetch_assoc($DeletedRequest)) {
            $del_by = $row['deleted_by'];
            if ($del_by !== null) {
                $deleted_By_name = select_top_name($con, "user_register", "name", "`id`=$del_by", "name", "");
            } else {
                $deleted_By_name = "No specific user";
            }
            $result[] = [
                "firstname" => $row['name'],
                "lastname" => $row['lname'],
                "mobile" => $row['mobile'],
                "emailid" => $row['email'],
                "createdon" => $row['createdon'],
                "status" => $row['status'],
                "deleted_at" => $row['deleted_at'] ? $row['deleted_at'] : "Not deleted",
                "account_expire_date" => $row['account_expire_date'],
                "Deleted_BY_name" => $deleted_By_name,
                // "monthlyactiveCount" => $monthlyactiveCount,
            ];
        }
    }
    // var_dump($result);die;
    echo json_encode($result);
} else if ($method == "WalletPurchase") {
    //  var_dump('hellow');die;
    // $result = [];
    $contype = '';
    $type = 'OT';
    $da = '';
    $userType1 = '';
    $userType = '';
    $draw_Con = '';
    $now = date('Y-m-d');
    $userType1 = $_POST['userType'];
    // var_dump($userType1);die;
    $selectedProductId = $_POST['selectedProductId'];
    if (isset($_POST["selectedProductId"]) && $_POST["selectedProductId"] != '') {
        $productRate = "nd.`product_id` = '" . $_POST["selectedProductId"] . "' AND";
    }
    // var_dump($productRate);die;
    $formdate = BlockSQLInjectionforagent($_POST["agdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    $searchTxt = BlockSQLInjectionforagent($_REQUEST["search_id"]);
    // $formdate = $_POST['formdate'];
    // $todate = $_POST['todate'];
    // var_dump($formdate,$todate);die;
    if ($formdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "nd.`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $contype = '';
    }
    if ($userType1 == 'New') {
        $userType = "nd.`renewalStatus`= 'NEW' AND";
    } else if ($userType1 == 'Renewal') {
        $userType = "nd.`renewalStatus`= 'RENEWAL' AND";
    } else if ($userType1 == 'PICKUPTOSTORE') {
        $userType = "nd.`renewalStatus`= 'PICKUPTOSTORE' AND";
    } else if ($userType1 == 'DELIVERYTOCUSTOMER') {
        $userType = "nd.`renewalStatus`= 'DELIVERYTOCUSTOMER' AND";
    } else {
        $userType = '';
    }
    if ($searchTxt != '') {
        $draw_Con = "(nd.firstname LIKE '%$searchTxt%' OR nd.emailid LIKE '%$searchTxt%' OR nd.mobile LIKE '%$searchTxt%') AND ";
    }
    if ($searchTxt == '' && $userType1 == '' && $formdate == '' && $todate == '') {
        $da = 'DESC';
        $contype = "nd.`createdon` LIKE '%$now%' AND";
    }
    $NDticketquery = mysqli_query($con, "SELECT nd.*,            
            (SELECT rate FROM product WHERE product.id = product_id LIMIT 1) as Product_rate
            FROM `invoice` nd
            JOIN `user_register` ur ON nd.user_id = ur.id            
            WHERE $contype $userType $draw_Con $productRate nd.id != '' AND nd.paymentType = 'Wallet' AND nd.agent_id = '0' AND nd.deletes != '1' ORDER BY nd.id DESC;");
    //   LEFT JOIN `user_register` ur2 ON nd.agent_id = ur2.id OR (nd.agent_id = '' AND ur2.id = ''), CONCAT(ur2.name, ' ', ur2.lname) AS agentName
    if (mysqli_num_rows($NDticketquery) > 0) {
        while ($row = mysqli_fetch_assoc($NDticketquery)) {
            $invoice_id = $row['id'];
            $ticketId = $row['ticketId'];
            $payment_id = select_top_name($con, "payment_history", "id", "`invoice_no`=$invoice_id ", "id", "");
            $ticketNo = select_top_name($con, "ndticket", "ticketNo", "`id`=$ticketId ", "ticketNo", "");
            $wallet_opening_balance = select_top_name($con, "wallet_history", "opening_balance", "`reference_id`=$payment_id ", "opening_balance", "");
            $wallet_total = select_top_name($con, "wallet_history", "total", "`reference_id`=$payment_id ", "total", "");
            $wallet_closeing_balance = select_top_name($con, "wallet_history", "closeing_balance", "`reference_id`=$payment_id ", "closeing_balance", "");
            // var_dump($wallet_closeing_balance);die;
            $result[] = [
                "payment_transaction_id" => $row['payment_transaction_id'],
                "firstname" => $row['firstname'],
                "lastname" => $row['lastname'],
                "resultdate" => $row['resultDate'],
                "mobile" => $row['mobile'],
                "emailid" => $row['emailid'],
                "createdon" => $row['createdon'],
                "Product_rate" => $row['Product_rate'],
                "grandtotal" => $row['grandtotal'],
                "quantity" => $row['quantity'],
                "paymentType" => $row['paymentType'],
                "renewalStatus" => $row['renewalStatus'],
                "id" => $row['id'],
                "ticketReferenceID" => $row['ticketReferenceID'],
                "id" => $row['id'],
                "ticketNo" => $ticketNo,
                "cart" => $row['cart'],
                "wallet_opening_balance" => $wallet_opening_balance,
                "wallet_total" => $wallet_total,
                "wallet_closeing_balance" => $wallet_closeing_balance,
                // "monthlyactiveCount" => $monthlyactiveCount,
            ];
        }
        // $result = mysqli_fetch_all($NDticketquery, MYSQLI_ASSOC);
    }
    // var_dump($result);die;
    echo json_encode($result);
} else if ($method == "Agent_Ticket_list") {
    // $result = [];
    $contype = '';
    $type = 'OT';
    $da = '';
    $now = date('Y-m-d');
    $userType1 = $_POST['userType'];
    // var_dump($userType);die;
    if ($userType1 == 'customer') {
        $userType = '0';
    } else {
    }
    $formdate = BlockSQLInjectionforagent($_POST["agdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    // $formdate = $_POST['formdate'];
    // $todate = $_POST['todate'];
    // var_dump($formdate,$todate);die;
    if ($formdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        // $contype = "`purchaseDatetime` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
        $contype = "nd.`purchaseDatetime` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $da = 'DESC';
        // $contype = "`purchaseDatetime` LIKE '%$now%' AND";
        $contype = "nd.`purchaseDatetime` LIKE '%$now%' AND";
    }
    $AgentID = BlockSQLInjectionforagent($_POST["userId"]);
    if ($userType1 == 'agent') {
        $NDticketquery = mysqli_query($con, "SELECT nd.*, ur.name, ur.lname, ur.mobile, ur.email,ur2.name AS agentName FROM `ndticket` nd JOIN `user_register` ur ON nd.userId = ur.id JOIN `user_register` ur2 ON nd.agentId = ur2.id WHERE " . $contype . " nd.agentId != 0 AND nd.id != '' AND nd.deletes != '1'");
    } else {
        $NDticketquery = mysqli_query($con, "SELECT 
            nd.*, 
            ur.name, 
            ur.lname, 
            ur.mobile, 
            ur.email, 
            (SELECT product_id FROM invoice WHERE invoice.ticketId = nd.id LIMIT 1) as Product, 
            (SELECT rate FROM product WHERE product.id = Product LIMIT 1) as Product_rate, 
            '' as agentName 
        FROM 
            `ndticket` nd 
        JOIN 
            `user_register` ur ON nd.userId = ur.id 
        WHERE 
            $contype
            nd.agentId = $AgentID 
            AND nd.id != '' 
            AND nd.deletes != '1'");
    }
    if (mysqli_num_rows($NDticketquery) > 0) {
        $result = mysqli_fetch_all($NDticketquery, MYSQLI_ASSOC);
    }
    echo json_encode($result);
} else if ($method == "archived_ticket") {
    $formdate = BlockSQLInjectionforagent($_POST["fromdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    // $formdate = $_POST['fromdate'];
    // $todate = $_POST['todate'];
    // var_dump($formdate,$todate);die;
    if ($formdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        // $contype = "`purchaseDatetime` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
        $contype = "nd.`endDate` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $da = 'DESC';
        // $contype = "`purchaseDatetime` LIKE '%$now%' AND";
        $contype = "nd.`endDate` LIKE '%$now%' AND";
    }
    // var_dump("SELECT nd.*, ur.name, ur.lname, ur.mobile, ur.email, ur2.name AS agentName
    // FROM `ndticket` nd
    // JOIN `user_register` ur ON nd.userId = ur.id
    // JOIN `user_register` ur2 ON nd.agentId = ur2.id
    // WHERE  " . $contype . " nd.deletes != '1'");die;
    $NDticketquery = mysqli_query($con, "SELECT nd.*, ur.name, ur.lname, ur.mobile, ur.email, ur2.name AS agentName
    FROM `ndticket` nd
    JOIN `user_register` ur ON nd.userId = ur.id
    JOIN `user_register` ur2 ON nd.agentId = ur2.id
    WHERE  " . $contype . " nd.deletes != '1'");
    // if($userType1 == 'agent'){
    // $NDticketquery = mysqli_query($con, "SELECT nd.*, ur.name, ur.lname, ur.mobile, ur.email,ur2.name AS agentName FROM `ndticket` nd JOIN `user_register` ur ON nd.userId = ur.id JOIN `user_register` ur2 ON nd.agentId = ur2.id WHERE " . $contype . " nd.agentId != 0 AND nd.id != '' AND nd.deletes != '1'");
    // } else{
    //     $NDticketquery = mysqli_query($con, "SELECT nd.*, ur.name, ur.lname, ur.mobile, ur.email, '' as agentName FROM `ndticket` nd JOIN `user_register` ur ON nd.userId = ur.id WHERE " . $contype . " nd.agentId = 0 AND nd.id != '' AND nd.deletes != '1' LIMIT 0, 25");
    // }
    if (mysqli_num_rows($NDticketquery) > 0) {
        $result = mysqli_fetch_all($NDticketquery, MYSQLI_ASSOC);
    }
    // var_dump($result);die;
    echo json_encode($result);
} else if ($method == "delete_NDticket") {
    $result = [];
    $transid = $_POST['transid'];
    $message = $_POST['message'];
    // Update ndticket table
    $ndticket_update = update($con, "ndticket", "`id` = '$transid'", ["deletes" => '1', "deleteReason" => $message], "", "", "", "");
    // Update invoice table
    $invoice_update = update($con, "invoice", "`ticketId` = '$transid'", ["deletes" => '1'], "", "", "", "");
    // $ndticket_update &&
    if ($invoice_update) {
        $result["result"] = "Ticket has been Deleted Successfully";
        $result["type"] = '1';
    } else {
        $result["result"] = "Failed to delete ticket";
        $result["type"] = '0';
    }
    // var_dump($result);die;
    echo json_encode($result);
}
// else if ($method == "delete_NDticket") {
//     $result = [];
//     $transid = $_POST['transid'];
//     $message = $_POST['message'];
//     // var_dump($transid,$message);die;
//       $inv_arr = array("deletes" => '1', "deleteReason" => $message);
//       $ND_update = update($con, "ndticket", "`id` = '$transid'", $inv_arr, "", "", "", "");
//       $invoce_update = update($con, "invoice", "`ticketId` = '$transid'", "`deletes` = '1'", "", "", "", "");
//       var_dump($invoce_update);die;
//       $result = array();
//         if ($ND_update) {
//             $result["result"] = " Ticket has been Deleted Successfully";
//             $result["type"]='1';
//         } else {
//             $result["result"] = "Failed to delete ticket";
//         }
//     echo json_encode($result);
// }
else if ($method == "sendemailtopurchase") {
    // var_dump('kkimhg');die;
    $transid = $_REQUEST['transid'];
    $ottype = $_REQUEST['ottype'];
    $tablename = $_REQUEST['tablename'];
    $result = sendpurchaseemail($con, $transid, $ottype, $tablename, $baseurl, $adminurl, $draw_no, $dubaidate_time);
    echo json_encode($result);
} else if ($method == "list_oticket_Ticket_History") {
    // $result = [];
    $ticket_id_H = $_POST['id'];
    $NDticketquery = mysqli_query($con, "SELECT * FROM `invoice` WHERE ticketId = '$ticket_id_H';");
    if (mysqli_num_rows($NDticketquery) > 0) {
        $result = mysqli_fetch_all($NDticketquery, MYSQLI_ASSOC);
    }
    // var_dump($result);die;
    echo json_encode($result);
} else if ($method == "list_aticket") {
    // $result = [];
    $contype = '';
    $type = 'AT';
    $da = '';
    $now = date('Y-m-d');
    $formdate = BlockSQLInjectionforagent($_POST["formdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    // $formdate = $_POST['formdate'];
    // $todate = $_POST['todate'];
    if ($formdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $da = 'DESC';
        $contype = "`createdon` LIKE '%$now%' AND";
    }
    $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$memid' and `deletes`='0'", "roll_id", "");
    if ($_SESSION['memid'] == '86921') {
        $agent = "(`agent_id` IN (SELECT `id`  FROM `user_register` WHERE `created_by` = $memid AND `roll_id` != '0' AND `deletes` = '0' AND `status` = '0') OR `agent_id` = '$memid') AND";
    } else if ($roll_id != 1 && $roll_id != 2 && $_SESSION['memid'] != '8236' && $roll_id != 6 && $roll_id != 8 && $roll_id != 11) {
        $agent = "`agent_id` = '$memid' AND";
    } else {
        $agent = "";
    }
    $Ticket_lines = select_query($rcon, "ticket_lines", "", "$contype $agent `type`='$type' and `deletes`='0'", "", "");
    if ($Ticket_lines['nr'] > 0) {
        foreach ($Ticket_lines['result'] as $key => $value) {
            $ticket_id = $value['ticket_id'];
            $proid = $value['product_id'];
            $purchase_datetime = select_top_name($con, "aticket", "purchase_datetime", "`id`='$ticket_id' and `deletes`='0'", "purchase_datetime", "");
            $ticket_no = select_top_name($con, "aticket", "ticket_no", "`id`='$ticket_id' and `deletes`='0'", "ticket_no", "");
            $user_id = select_top_name($con, "aticket", "user_id", "`id`='$ticket_id' and `deletes`='0'", "user_id", "");
            $transaction_id = select_top_name($con, "aticket", "transaction_id", "`id`='$ticket_id' and `deletes`='0'", "transaction_id", "");
            $cusname = select_top_name($con, "user_register", "name", "`id`='$user_id'", "name", "");
            $lname = select_top_name($con, "user_register", "lname", "`id`='$user_id'", "lname", "");
            $mobile = select_top_name($con, "user_register", "mobile", "`id`='$user_id'", "mobile", "");
            $email = select_top_name($con, "user_register", "email", "`id`='$user_id'", "email", "");
            $proamt = select_top_name($con, "product", "rate", "`id`='$proid' and `deletes`='0'", "rate", "");
            $full_cusname = $cusname . ' ' . $lname;
            $action = '';
            $action .= '<div class="g-2">';
            $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' . $transaction_id . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span class="fa fa-file-text-o" style="font-size: 18px;"></span></a>';
            $action .= '<a target="_blank" href="' . $baseurl . 'ticket-view/' . $transaction_id . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span class="fa fa-files-o" style="font-size: 18px;"></span></a>';
            $action .= '<a target="_blank" href="https://api.whatsapp.com/send?phone=' . $mobile . '&text=' . $baseurl . 'ticket-view/' . $transaction_id . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span class="fa fa-whatsapp" style="font-size: 18px;"></span></a>';
            if ($_SESSION['memid'] == 1 || $roll_id != 11) {
                $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="font-size: 18px;" onclick="sendemailtopurchase(' . "'$transaction_id'" . ')">Send Email</span></a>';
                $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane"  style="color: #1a73e8;font-size: 18px;" onclick="sendsmstopurchase(' . "'$transaction_id'" . ')">Send Sms</span></a>';
                $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8;font-size: 18px;" onclick="bothemailsms(' . "'$transaction_id'" . ')">Both</span></a>';
                if ($roll_id == 1) {
                    $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-trash" style="color: red;font-size: 18px;" onclick="deleteagticket(' . "'$transaction_id'" . ')"></span></a>';
                }
            }
            $action .= '</div>';
            $agent_id = select_top_name($con, "aticket", "agent_id", "`id`='$ticket_id' and `deletes`='0'", "agent_id", "");
            $agent_name = select_top_name($con, "user_register", "name", "`id`='$agent_id'", "name", "");
            $agent_lname = select_top_name($con, "user_register", "lname", "`id`='$agent_id'", "lname", "");
            $full_name = $agent_name . ' ' . $agent_lname;
            $result[] = array("agentname" => $full_name, "action" => $action, "transaction_id" => $transaction_id, "proamt" => $proamt, "RaffleID" => $value['raffle_id'], "My3Numbers" => $value['my3number'], "email" => $email, "mobile" => $mobile, "cusname" => $full_cusname, "ticketno" => $ticket_no, "purdate" => date("d-M-Y g:i a", strtotime($purchase_datetime)));
        }
    }
    echo json_encode($result);
} else if ($method == "list_fticket") {
    // $result = [];
    $contype = '';
    $type = 'FT';
    $da = '';
    $now = date('Y-m-d');
    $formdate = BlockSQLInjectionforagent($_POST["formdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    // $formdate = $_POST['formdate'];
    // $todate = $_POST['todate'];
    if ($formdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $da = 'DESC';
        $contype = "`createdon` LIKE '%$now%' AND";
    }
    // if ($memid != 1) {
    //     $agent = "`agent_id` = '$memid' AND";
    // } else {
    //     $agent = "";
    // }
    $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$memid' and `deletes`='0'", "roll_id", "");
    if ($roll_id != 1 && $roll_id != 2) {
        $agent = "`agent_id` = '$memid' AND";
    } else {
        $agent = "";
    }
    $Ticket_lines = select_query($con, "ticket_lines", "", "$contype $agent `type`='$type' and `deletes`='0'", "", "");
    if ($Ticket_lines['nr'] > 0) {
        foreach ($Ticket_lines['result'] as $key => $value) {
            $ticket_id = $value['ticket_id'];
            $proid = $value['product_id'];
            $purchase_datetime = select_top_name($con, "fticket", "purchase_datetime", "`id`='$ticket_id' and `deletes`='0'", "purchase_datetime", "");
            $ticket_no = select_top_name($con, "fticket", "ticket_no", "`id`='$ticket_id' and `deletes`='0'", "ticket_no", "");
            $user_id = select_top_name($con, "fticket", "user_id", "`id`='$ticket_id' and `deletes`='0'", "user_id", "");
            $transaction_id = select_top_name($con, "fticket", "transaction_id", "`id`='$ticket_id' and `deletes`='0'", "transaction_id", "");
            $cusname = select_top_name($con, "user_register", "name", "`id`='$user_id' and `deletes`='0'", "name", "");
            $lname = select_top_name($con, "user_register", "lname", "`id`='$user_id' and `deletes`='0'", "lname", "");
            $fullname = $cusname . ' ' . $lname;
            $mobile = select_top_name($con, "user_register", "mobile", "`id`='$user_id' and `deletes`='0'", "mobile", "");
            $email = select_top_name($con, "user_register", "email", "`id`='$user_id' and `deletes`='0'", "email", "");
            $proamt = select_top_name($con, "product", "rate", "`id`='$proid' and `deletes`='0'", "rate", "");
            $action = '';
            $action .= '<div class="g-2">';
            // $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' . $transaction_id . '"  class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span style="font-size: 20px !important;" class="fa fa-file-text-o fs-14"></span></a>';
            $action .= '<a target="_blank" href="' . $baseurl . 'ticket-view/' . $transaction_id . '"  class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span style="font-size: 20px !important;" class="fa fa-files-o fs-14"></span></a>';
            if ($_SESSION['memid'] == 1) {
                // $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" onclick="sendemailtopurchase(' . "'$transaction_id'" . ')">Send Email</span></a>';
                // $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8;" onclick="sendsmstopurchase(' . "'$transaction_id'" . ')">Send Sms</span></a>';
                // $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8;" onclick="bothemailsms(' . "'$transaction_id'" . ')">Both</span></a>';
                $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-trash" style="color: red; font-size: 20px !important;" onclick="deletefticket(' . "'$transaction_id'" . ')"></span></a>';
            }
            $action .= '</div>';
            $agent_id = select_top_name($con, "fticket", "agent_id", "`id`='$ticket_id' and `deletes`='0'", "agent_id", "");
            $agent_name = select_top_name($con, "user_register", "name", "`id`='$agent_id' and `deletes`='0'", "name", "");
            $result[] = array("agentname" => $agent_name, "action" => $action, "transaction_id" => $transaction_id, "proamt" => $proamt, "RaffleID" => $value['raffle_id'], "My3Numbers" => $value['my3number'], "email" => $email, "mobile" => $mobile, "cusname" => $fullname, "ticketno" => $ticket_no, "purdate" => date("d-M-Y g:i a", strtotime($purchase_datetime)));
        }
    } else {
    }
    echo json_encode($result);
} elseif ($method == 'sms_report') {
    $result = [];
    $now = date('Y-m-d');
    $agdate = BlockSQLInjection($_POST["agdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);
    // $agdate = $_POST['agdate'];
    // $todate = $_POST['todate'];
    if ($agdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($agdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "`datetime` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $da = 'DESC';
        $contype = "`datetime` LIKE '%$now%' AND";
    }
    // var_dump($contype);die;
    $gatcon = '';
    $gatewayname = BlockSQLInjection($_POST["gatewayname"]);
    $statusget = BlockSQLInjection($_POST["statusget"]);
    // $gatewayname = $_POST['gatewayname'];
    // $statusget = $_POST['statusget'];
    // var_dump($gatewayname);die;
    if ($gatewayname != '') {
        // if ($gatewayname == 'expresso') {
        //     $gatcon = "`gateway` LIKE '$gatewayname' AND ";
        // } 
        // else if ($gatewayname == 'dataslice') {
        //     $gatcon = "`gateway` LIKE '$gatewayname' AND ";
        // } 
        // else 
        if ($gatewayname == 'cequens' || $gatewayname == 'dataslice' || $gatewayname == 'expresso' || $gatewayname == 'precise' || $gatewayname == 'firebase' || $gatewayname == 'twilio' || $gatewayname == 'brandmaster') {
            if ($statusget == 'Unchecked') {
                $gatcon = "`gateway` LIKE '$gatewayname' AND `smsstatus` = '' AND";
            } else if ($statusget == 'All') {
                $gatcon = "`gateway` LIKE '$gatewayname' AND";
            } else {
                $gatcon = "`gateway` LIKE '$gatewayname' AND `smsstatus` LIKE '$statusget' AND";
            }
        }
    }
    // var_dump("SELECT * FROM `smslog` WHERE $gatcon $contype `id`!= '' AND  `gateway` IN ('cequens', 'dataslice', 'expresso', 'precise', 'firebase', 'twilio')");die;
    $SMSquery = mysqli_query($con, "SELECT * FROM `smslog` WHERE $gatcon $contype `id`!= '' AND  `gateway` IN ('cequens', 'dataslice', 'expresso', 'precise', 'firebase', 'twilio','brandmaster')");
    if (mysqli_num_rows($SMSquery) > 0) {
        $result = mysqli_fetch_all($SMSquery, MYSQLI_ASSOC);
    }
    echo json_encode($result);
} elseif ($method == 'WhatsAppsent_report') {

    // 1. Get POST variables securely
    $agdate = $_POST['agdate'] ?? '';
    $todate = $_POST['todate'] ?? '';
    $statusget = $_POST['statusget'] ?? 'All';
    $gatewayname = $_POST['gatewayname'] ?? '';

    $data = [];

    // 2. Base Query: Only fetch WhatsApp gateways (like 'fbWhatsapp' or 'shiwhatsapp')
    $query = "SELECT * FROM `smslog` WHERE `gateway` LIKE '%whatsapp%'";

    // 3. Apply Date Filter
    if (!empty($agdate) && !empty($todate)) {
        // Formats the dates securely for MySQL comparison
        $agdate_formatted = date('Y-m-d', strtotime($agdate));
        $todate_formatted = date('Y-m-d', strtotime($todate));

        $query .= " AND DATE(`datetime`) >= '$agdate_formatted' AND DATE(`datetime`) <= '$todate_formatted'";
    }

    // 4. Apply Status Filter
    if ($statusget != 'All') {
        if (strtolower($statusget) == 'unchecked') {
            // Unchecked usually means empty or null in the database
            $query .= " AND (`smsstatus` = '' OR `smsstatus` IS NULL)";
        } else {
            $query .= " AND `smsstatus` = '" . mysqli_real_escape_string($con, $statusget) . "'";
        }
    }

    // 5. Apply Gateway Filter (If you have multiple gateways)
    if (!empty($gatewayname) && $gatewayname != 'All') {
        $query .= " AND `gateway` = '" . mysqli_real_escape_string($con, $gatewayname) . "'";
    }

    // 6. Order by newest first (Explicitly ordering by datetime DESC)
    $query .= " ORDER BY `datetime` DESC, `id` DESC";

    // 7. Execute Query
    $res = mysqli_query($con, $query);

    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
    }

    // 8. Return JSON to DataTables
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
} elseif ($method == 'WhatsApp_report1') {
    $result = [];
    $now = date('Y-m-d');
    
    $agdate = BlockSQLInjection($_POST["agdate"] ?? '');
    $todate = BlockSQLInjection($_POST["todate"] ?? '');
    $gatewayname = BlockSQLInjection($_POST["gatewayname"] ?? '');
    $statusget = BlockSQLInjection($_POST["statusget"] ?? '');

    // 1. Build Date Condition (Default to today if empty)
    if ($agdate != '' && $todate != '') {
        $fd = date("Y-m-d", strtotime($agdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "`datetime` BETWEEN '$fd 00:00:00' AND '$td 23:59:59'";
    } else {
        $contype = "`datetime` LIKE '%$now%'";
    }

    // 2. Build Gateway Condition (Dynamic - no hardcoded names needed)
    $gatcon = "";
    if ($gatewayname != '' && strtolower($gatewayname) != 'all') {
        // Ensure it matches the DB casing. The DB screenshot shows lowercase 'greenwhatsapp'
        $gatcon = " AND `gateway` = '$gatewayname'"; 
    }

    // 3. Build Status Condition
    $statcon = "";
    if ($statusget != '' && strtolower($statusget) != 'all') {
        if (strtolower($statusget) == 'unchecked') {
            $statcon = " AND (`smsstatus` = '' OR `smsstatus` IS NULL)";
        } else {
            $statcon = " AND `smsstatus` = '$statusget'";
        }
    }

    // 4. Execute the Clean Query
    // Removed the restrictive IN() clause so ALL gateways work based on the dropdown
    $sql = "SELECT * FROM `smslog` WHERE $contype $gatcon $statcon ORDER BY `id` DESC";
    
    $SMSquery = mysqli_query($con, $sql);
    
    if ($SMSquery && mysqli_num_rows($SMSquery) > 0) {
        $result = mysqli_fetch_all($SMSquery, MYSQLI_ASSOC);
    }
    
    echo json_encode($result);
    exit;
}elseif ($method == 'Customer_Applink_Report') {

    $result = [];

    $agdate = $_POST["agdate"];
    $todate = $_POST["todate"];
    $statusget = BlockSQLInjection($_POST["statusget"]);
    
    $conditions = [];
    
    if ($agdate != '' && $todate != '') {

        $conditions[] = "ws.created_at BETWEEN '$agdate 00:00:00' AND '$todate 23:59:59'";
    }
    
    if ($statusget != '' && $statusget != 'All') {
    
        $conditions[] = "ws.status = '$statusget'";
    }
    
    $where = "";
    
    if (!empty($conditions)) {
        $where = "WHERE " . implode(" AND ", $conditions);
    }
    
    $query = "
    
        SELECT
        ws.id,
        ws.mobile,
        ws.body,
        ws.temp_name,
        ws.created_at,
        ws.status
        
        FROM wb_template_sent AS ws
        
        $where
        
        ORDER BY ws.id DESC
    
    ";
    
    // var_dump($query);die;
    
    $SMSquery = mysqli_query($con, $query);
    
    if (mysqli_num_rows($SMSquery) > 0) {
        $result = mysqli_fetch_all($SMSquery, MYSQLI_ASSOC);
    }
    
    echo json_encode($result);

} elseif ($method == 'push_report') {
    $result = [];
    $now = date('Y-m-d');
    $agdate = BlockSQLInjection($_POST["agdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);
    $status = BlockSQLInjection($_POST["status"]);
    if ($agdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($agdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "push_notifications.`created_at` BETWEEN '$fd 00:00:00' AND '$td 23:59:59'";
    } else {
        $da = 'DESC';
        $contype = "push_notifications.`created_at` LIKE '%$now%'";
    }
    if ($status != '') {
        $contype .= " AND push_notifications.status=$status";
    }
    // Updated SQL to include res_json AND fetch sender_name from user_register
    $sql = "
    SELECT 
        push_notifications.id,
        push_notifications.sent_by,
        push_notifications.title,
        push_notifications.body,
        push_notifications.status,
        push_notifications.created_at,
        push_notifications.req_json,
        push_notifications.res_json,
        COALESCE(user.name, customer.name) AS name,
        sender.name AS sender_name
    FROM push_notifications
    LEFT JOIN user_register AS user ON user.id = push_notifications.user_id
    LEFT JOIN customer_register AS customer ON customer.id = push_notifications.user_id
    LEFT JOIN user_register AS sender ON sender.id = push_notifications.sent_by
    WHERE $contype 
    ORDER BY push_notifications.id DESC
    ";
    $pushnotifi = mysqli_query($rcon, $sql);
    if ($pushnotifi && mysqli_num_rows($pushnotifi) > 0) {
        while ($value = mysqli_fetch_assoc($pushnotifi)) {
            $result[] = [
                "id" => $value['id'],
                "name" => $value['name'],          // Recipient name (if single user)
                "sent_by" => $value['sent_by'],       // Fallback ID
                "sender_name" => $value['sender_name'],   // Fetched Admin name who sent it
                "title" => $value['title'],
                "body" => htmlspecialchars($value['body']), // Passed cleanly to frontend JS
                "status" => $value['status'],        // Passed as int so JS badges work perfectly
                "created_at" => $value['created_at'],
                "req_json" => $value['req_json'],
                "res_json" => $value['res_json']
            ];
        }
    }
    echo json_encode($result);
} elseif ($method == 'get_rms') {
    $data = [];
    // Fetch Agents/RMs based on your user_register table structure
    $query = "SELECT id, name, lname FROM user_register WHERE user = 'Agent' AND roll_id = 3 ORDER BY name ASC";
    $result = mysqli_query($rcon, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $fullName = trim($row['name'] . ' ' . $row['lname']);
            $data[] = [
                "id" => $row['id'],
                "name" => $fullName != '' ? $fullName : 'Agent ID: ' . $row['id']
            ];
        }
    }
    echo json_encode($data);
    exit;
} elseif ($method == 'get_rm_drivers') {
    $rm_id = mysqli_real_escape_string($rcon, $_POST['rm_id']);
    $data = [];
    /* * IMPORTANT: How are your drivers linked to the RM?
     * Based on your screenshot, you might use 'agentIds' or 'created_by' in user_register.
     * I am using 'agentIds' here, but change it to 'created_by' if that's what your system uses.
     * (I also see an 'agent_driver_map' table in your sidebar. If you use that table instead, 
     * you will need to do a JOIN query here).
     */
    $query = "SELECT id, name, lname FROM user_register WHERE user = 'Customer' AND agent_id = '$rm_id' ORDER BY name ASC";
    $result = mysqli_query($rcon, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $fullName = trim($row['name'] . ' ' . $row['lname']);
            $data[] = [
                "id" => $row['id'],
                "name" => $fullName != '' ? $fullName : 'Driver ID: ' . $row['id']
            ];
        }
    }
    echo json_encode($data);
    exit;
} elseif ($method == 'email_report') {
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
    $Emaillog = select_query($rcon, "emaillog", "sendstatus,fromemail,email,ip,subject,datetime,id", " $contype `id`!= '' ", "", "");
    if ($Emaillog['nr'] > 0) {
        foreach ($Emaillog['result'] as $key => $value) {
            $result[] = ["id" => $value['id'], "sendstatus" => $value['sendstatus'], "fromemail" => $value['fromemail'], "email" => $value['email'], "ip" => $value['ip'], "subject" => '<textarea readonly>' . $value['subject'] . '</textarea>', "datetime" => $value['datetime']];
        }
    }
    echo json_encode($result);
} elseif ($method == 'suspended_report') {
    $result = [];
    $now = date('Y-m-d');
    $agdate = $_POST['agdate'];
    $todate = $_POST['todate'];
    if ($agdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($agdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $da = 'DESC';
        $contype = "`createdon` LIKE '%$now%' AND";
    }
    $Suspended = select_query($con, "suspended_log", "", " $contype  `id`!= '' ", "", "");
    if ($Suspended['nr'] > 0) {
        foreach ($Suspended['result'] as $key => $value) {
            $result[] = array("userid" => $value['userid'], "type" => $value['type'], "reason" => '<textarea readonly>' . $value['reason'] . '</textarea>', "createdon" => $value['createdon']);
        }
    } else {
    }
    echo json_encode($result);
} elseif ($method == 'bankchange_report') {
    $result = [];
    $now = date('Y-m-d');
    $agdate = BlockSQLInjectionforreportrange($_POST["agdate"]);
    $todate = BlockSQLInjectionforreportrange($_POST["todate"]);
    if ($agdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($agdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' AND";
    } else {
        $da = 'DESC';
        $contype = "`createdon` LIKE '%$now%' AND";
    }
    $bank_change = select_query($con, "bank_change_log", "", " $contype `id`!= '' ", "", "");
    if ($bank_change['nr'] > 0) {
        foreach ($bank_change['result'] as $key => $value) {
            $details = json_decode($value['request'], true);
            $result[] = array("userid" => $value['userid'], "updatetype" => $value['type'], "bankname" => ($details['bank_name'] == '') ? $details['bankname'] : $details['bank_name'], "account_type" => ($details['acctype'] == '') ? $details['account_name'] : $details['acctype'], "accountno" => ($details['accountno'] == '') ? $details['account_no'] : $details['accountno'], "iban" => ($details['ibancode'] == '') ? $details['iban_code'] : $details['ibancode'], "swift" => ($details['swiftcode'] == '') ? $details['swift_code'] : $details['swiftcode'], "currency" => ($details['currencyccode'] == '') ? ['currency_code'] : $details['currencyccode'], "passport" => ($details['emirites_passport'] == '') ? $details['passport'] : $details['emirites_passport'], "datetime" => $value['createdon']);
        }
    } else {
    }
    echo json_encode($result);
}
///////// NEW //////////
else if ($method == 'monthly_report') {
    try {
        $result = [];
        $fromdate = BlockSQLInjection($_POST["fromdate"]);
        $todate = BlockSQLInjection($_POST["todate"]);
        $fromdate = date("Y-m-d", strtotime($fromdate));
        $todate = date("Y-m-d", strtotime($todate));
        if ($fromdate != '' && $todate != '') {
            $contype = "`createdon` BETWEEN '$fromdate 00:00:00' AND '$todate 23:59:59' AND";
            $total_ticket = [];
            $ticket = select_query($rcon, "ticket", "`id`, `user_id`, `ticket_no`, `net_total`, `createdon`", "$contype `deletes`='0' ORDER BY `id` DESC", "", "");
            if ($ticket['nr'] > 0) {
                foreach ($ticket['result'] as $key => $value) {
                    // $user_register = select_query($con, "user_register", "", "`id`='$value[user_id]' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                    $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' ORDER BY `id` DESC LIMIT 1", "", "");
                    if ($user_register['nr'] > 0) {
                        $result[] = ['agentname' => '', 'ticketno' => $value['ticket_no'], 'cusname' => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], 'mobile' => $user_register['result'][0]['mobile'], 'email' => $user_register['result'][0]['email'], 'proamt' => $value['net_total'], 'purdate' => date('d-m-Y g:i a', strtotime($value['createdon'])), 'date' => $value['createdon']];
                    }
                }
            }
            $aticket = select_query($rcon, "aticket", "`id`, `user_id`, `ticket_no`, `net_total`, `createdon`, `agent_id`", "$contype `deletes`='0' ORDER BY `id` DESC", "", "");
            if ($aticket['nr'] > 0) {
                foreach ($aticket['result'] as $key => $value) {
                    $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                    if ($user_register['nr'] > 0) {
                        $agent_id = $value['agent_id'];
                        $agentName = select_top_name($rcon, "user_register", "name", "`id`='$agent_id'", "name", "");
                        $agentlName = select_top_name($rcon, "user_register", "lname", "`id`='$agent_id'", "lname", "");
                        $agentfulname = $agentName . ' ' . $agentlName;
                        $result[] = ['agentname' => $agentfulname, 'ticketno' => $value['ticket_no'], 'cusname' => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], 'mobile' => $user_register['result'][0]['mobile'], 'email' => $user_register['result'][0]['email'], 'proamt' => $value['net_total'], 'purdate' => date('d-m-Y g:i a', strtotime($value['createdon'])), 'date' => $value['createdon']];
                    }
                }
            }
            $mticket = select_query($rcon, "mticket", "`id`, `user_id`, `ticket_no`, `net_total`, `createdon`", "$contype `deletes`='0' ORDER BY `id` DESC", "", "");
            if ($mticket['nr'] > 0) {
                foreach ($mticket['result'] as $key => $value) {
                    $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                    if ($user_register['nr'] > 0) {
                        $result[] = ['agentname' => '', 'ticketno' => $value['ticket_no'], 'cusname' => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], 'mobile' => $user_register['result'][0]['mobile'], 'email' => $user_register['result'][0]['email'], 'proamt' => $value['net_total'], 'purdate' => date('d-m-Y g:i a', strtotime($value['createdon'])), 'date' => $value['createdon']];
                    }
                }
            }
            $wticket = select_query($rcon, "wticket", "`id`, `user_id`, `ticket_no`, `net_total`, `createdon`", "$contype `deletes`='0' ORDER BY `id` DESC", "", "");
            if ($wticket['nr'] > 0) {
                foreach ($wticket['result'] as $key => $value) {
                    $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                    if ($user_register['nr'] > 0) {
                        $result[] = ['agentname' => '', 'ticketno' => $value['ticket_no'], 'cusname' => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], 'mobile' => $user_register['result'][0]['mobile'], 'email' => $user_register['result'][0]['email'], 'proamt' => $value['net_total'], 'purdate' => date('d-m-Y g:i a', strtotime($value['createdon'])), 'date' => $value['createdon']];
                    }
                }
            }
            $cpticket = select_query($rcon, "cpticket", "`id`, `user_id`, `ticket_no`, `net_total`, `createdon`", "$contype `deletes`='0' ORDER BY `id` DESC", "", "");
            if ($cpticket['nr'] > 0) {
                foreach ($cpticket['result'] as $key => $value) {
                    $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                    if ($user_register['nr'] > 0) {
                        $result[] = ['agentname' => '', 'ticketno' => $value['ticket_no'], 'cusname' => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], 'mobile' => $user_register['result'][0]['mobile'], 'email' => $user_register['result'][0]['email'], 'proamt' => $value['net_total'], 'purdate' => date('d-m-Y g:i a', strtotime($value['createdon'])), 'date' => $value['createdon']];
                    }
                }
            }
            $kticket = select_query($rcon, "kticket", "`id`, `user_id`, `ticket_no`, `net_total`, `createdon`", "$contype `deletes`='0' ORDER BY `id` DESC", "", "");
            if ($kticket['nr'] > 0) {
                foreach ($kticket['result'] as $key => $value) {
                    $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                    if ($user_register['nr'] > 0) {
                        $result[] = ['agentname' => '', 'ticketno' => $value['ticket_no'], 'cusname' => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], 'mobile' => $user_register['result'][0]['mobile'], 'email' => $user_register['result'][0]['email'], 'proamt' => $value['net_total'], 'purdate' => date('d-m-Y g:i a', strtotime($value['createdon'])), 'date' => $value['createdon']];
                    }
                }
            }
            $bpticket = select_query($rcon, "bpticket", "`id`, `user_id`, `ticket_no`, `net_total`, `createdon`", "$contype `deletes`='0' ORDER BY `id` DESC", "", "");
            if ($bpticket['nr'] > 0) {
                foreach ($bpticket['result'] as $key => $value) {
                    $user_register = select_query($rcon, "user_register", "", "`id`='$value[user_id]' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
                    if ($user_register['nr'] > 0) {
                        $result[] = ['agentname' => '', 'ticketno' => $value['ticket_no'], 'cusname' => $user_register['result'][0]['name'] . ' ' . $user_register['result'][0]['lname'], 'mobile' => $user_register['result'][0]['mobile'], 'email' => $user_register['result'][0]['email'], 'proamt' => $value['net_total'], 'purdate' => date('d-m-Y g:i a', strtotime($value['createdon'])), 'date' => $value['createdon']];
                    }
                }
            }
        }
        echo json_encode($result);
    } catch (Exception $e) {
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'datatable_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == 'show_image') {
    try {
        $id = $_POST['id'];
        if ($id != '') {
            $result = [];
            $img_url = select_top_name($con, "user_register", "img_url", "`id`='$id' and `deletes`='0'  order by `id` DESC ", "img_url", "");
            if ($img_url != '') {
                $result['img'][] = (strpos($img_url, "littledraw") === 0) ? $baseurl . $img_url : constant('assetURL') . $img_url;
            }
            $FRONT_img = select_query($con, "user_images", "", "`user_id`='$id' and `img_url` != '' and `type` = 'FRONT' and `status`='0' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
            if ($FRONT_img['result'][0]['img_url'] != '') {
                $result['img'][] = (strpos($img_url, "littledraw") === 0) ? $baseurl . $FRONT_img['result'][0]['img_url'] : constant('assetURL') . $FRONT_img['result'][0]['img_url'];
            }
            $BACK_img = select_query($con, "user_images", "", "`user_id`='$id' and `img_url` != '' and `type` = 'BACK' and `status`='0' and `deletes`='0' ORDER BY `id` DESC LIMIT 1", "", "");
            if ($BACK_img['result'][0]['img_url'] != '') {
                $result['img'][] = (strpos($img_url, "littledraw") === 0) ? $baseurl . $BACK_img['result'][0]['img_url'] : constant('assetURL') . $BACK_img['result'][0]['img_url'];
            }
            $i = 1;
            $output = '';
            foreach ($result['img'] as $value) {
                // if (strpos($value, "littledraw") !== 0) {
                //     // echo '"littledraw" is present in the string.';
                //     $value = constant('assetURL') . $value;
                // }
                // var_dump($value);
                // die;
                $output .= '<div><img src="' . $value . '" style="width: 636px;height: 300px;" alt=""></div>';
                $i++;
            }
            $result['type'] = 1;
            $result['result'] = $output;
            echo json_encode($result);
        }
    } catch (Exception $e) {
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'datatable_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == 'digital_market') {
    try {
        // $formdate = $_POST['formdate'];
        // $todate = $_POST['todate'];
        $formdate = BlockSQLInjection($_POST["formdate"]);
        $todate = BlockSQLInjection($_POST["todate"]);
        if ($formdate != '' && $todate != '') {
            $fd = date("Y-m-d", strtotime($formdate));
            $td = date("Y-m-d", strtotime($todate));
            $dateCon = "AND `createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59'";
        }
        // echo "SELECT subid1, COUNT(subid1) as 'totalview'  FROM digital_market WHERE ip IN (SELECT `ip` FROM `digital_market` WHERE `utm_source` = 'metroads' AND `deletes` = '0'  $dateCon GROUP BY ip)  AND `subid1` != '' AND  `deletes` = '0' and (`subid1` like '%metroads%' || `subid1` like '%success%'  || `subid1` like '%play%'   || `subid1` like '%login%'   || `subid1` like '%cart%'   || `subid1` like '%billing%'
        // || `subid1` like '%dashboard%' || `subid1` like '%failed%' || `subid1` like '%thanks%'  )  $dateCon GROUP BY subid1 order by totalview desc";
        // die;
        $sql = mysqli_query(
            $con,
            "SELECT subid1, COUNT(subid1) as 'totalview'  FROM digital_market WHERE ip IN (SELECT `ip` FROM `digital_market` WHERE `utm_source` = 'metroads' AND `deletes` = '0'  $dateCon GROUP BY ip)  AND `subid1` != '' AND  `deletes` = '0' and (`subid1` like '%metroads%' || `subid1` like '%success%'  || `subid1` like '%play%'   || `subid1` like '%login%'   || `subid1` like '%cart%'   || `subid1` like '%billing%'
        || `subid1` like '%dashboard%' || `subid1` like '%failed%' || `subid1` like '%thanks%'  )  $dateCon GROUP BY subid1 order by totalview desc"
        );
        while ($row = mysqli_fetch_assoc($sql)) {
            $subid = $row['subid1'];
            if ($subid == "?utm_source=metroads&utm_medium=banner&utm_campaign=metro_ads") {
                $subid = "Metro landing";
            }
            if ($subid == "index.php?makepayment=success") {
                $subid = "Success";
            } else {
                $subid = $subid;
            }
            $result[] = ["pagename" => $subid, "totalviews" => $row['totalview']];
            $subid = "";
        }
        echo json_encode($result);
    } catch (Exception $e) {
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'datatable_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == 'overallreport') {
    try {
        $fromdate = BlockSQLInjectionforreportrange($_POST["fromdate"]);
        $todate = BlockSQLInjectionforreportrange($_POST["todate"]);
        // $formdate = $_POST['formdate'];
        // $todate = $_POST['todate'];
        if ($formdate != '' && $todate != '') {
            $fd = date("Y-m-d", strtotime($formdate));
            $td = date("Y-m-d", strtotime($todate));
            $dateCon = "AND `createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59'";
        }
        $source = BlockSQLInjectionforreportrange($_POST["source"]);
        // $source = $_POST['source'];
        if ($source == 'organic') {
            $sourceCon = "`utm_source` = '' AND";
        } else if ($source == 'All') {
            $sourceCon = "";
        } else {
            $sourceCon = "`utm_source` = '$source' AND";
        }
        if ($source == 'organic') {
            $subiCon = " AND (`subid1` like '%%' || `subid1` like '%success%' || `subid1` like '%play%' || `subid1` like '%login%' || `subid1` like '%cart%' || `subid1` like '%billing%' || `subid1` like '%dashboard%' || `subid1` like '%failed%' || `subid1` like '%thanks%' ) AND (`subid1` NOT like '%gclid%' && `subid1` NOT like '%fbclid%' && `subid1` NOT like '%utm_source%' && `subid1` NOT like '%wbraid%' && `subid1` NOT like 'index.php%' )";
        } else if ($source == 'All') {
            $subiCon = "";
        } else if ($source == "Google") {
            $subiCon = " AND (`subid1` like '%%' || `subid1` like '%success%' || `subid1` like '%play%' || `subid1` like '%login%' || `subid1` like '%cart%' || `subid1` like '%billing%' || `subid1` like '%dashboard%' || `subid1` like '%failed%' || `subid1` like '%thanks%' ) AND (`subid1` NOT like '%fbclid%' && `subid1` NOT like '%gclid%' && `subid1` NOT like '%wbraid%')";
        } else {
            $subiCon = "AND `subid1` != '' AND (`subid1` like '%%' || `subid1` like '%success%' || `subid1` like '%play%' || `subid1` like '%login%' || `subid1` like '%cart%' || `subid1` like '%billing%' || `subid1` like '%dashboard%'  || `subid1` like '%failed%' || `subid1` like '%thanks%' ) ";
        }
        // echo "SELECT subid1, COUNT(subid1) as 'totalview' FROM digital_market WHERE ip IN (SELECT `ip` FROM `digital_market` WHERE $sourceCon `deletes` = '0' $dateCon GROUP BY `ip` ORDER BY `id` DESC) AND `deletes` = '0' $subiCon $dateCon GROUP BY subid1 order by totalview desc;";
        // die;
        $sql = mysqli_query(
            $rcon,
            "SELECT subid1, COUNT(subid1) as 'totalview' FROM digital_market WHERE ip IN (SELECT `ip` FROM `digital_market` WHERE $sourceCon `deletes` = '0' $dateCon GROUP BY `ip` ORDER BY `id` DESC) AND `deletes` = '0' $subiCon $dateCon GROUP BY subid1 order by totalview desc;"
        );
        while ($row = mysqli_fetch_assoc($sql)) {
            $subid = $row['subid1'];
            $utm_source = $source;
            if ($utm_source == "" && $subid == "") {
                $utm_source = "Organic";
                $subid = 'Home';
            } else
                if ($utm_source == "") {
                $utm_source = "Organic";
                $subid = $row['subid1'];
            } else
                    if ($subid == "?utm_source=metroads&utm_medium=banner&utm_campaign=metro_ads") {
                $subid = "Metro landing";
            } else if ($subid == "index.php?makepayment=success") {
                $subid = "Success";
            } else if ($subid == "") {
                $subid = "Home";
            } else {
                $subid = $subid;
            }
            $subid = $utm_source . ": " . $subid;
            $result[] = ["pagename" => $subid, "totalviews" => $row['totalview']];
            $subid = "";
        }
        if ($source == 'All') {
            $subid = $utm_source . ": " . 'Overall';
            $digital_market = mysqli_query($con, "SELECT `ip` FROM `digital_market` WHERE `deletes` = '0' $dateCon GROUP BY `ip`;");
            $result[] = ["pagename" => $subid, "totalviews" => mysqli_num_rows($digital_market)];
        }
        echo json_encode($result);
    } catch (Exception $e) {
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'datatable_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == 'searchCoupon') {
    try {
        $result = [];
        $coupon_type = $_POST['coupon_type'];
        if ($coupon_type != '') {
            $coupon_con = "`coupontype` = '$coupon_type' AND";
        } else {
            $coupon_con = "";
        }
        $coupon_status = (int) $_POST['coupon_status'];
        if ($coupon_status == 1) {
            $status_con = "`used_by` = '0' AND `status` = '0' AND `ticket_id` = '0' AND";
        } else if ($coupon_status == 2) {
            $status_con = "`used_by` != '0' AND `status` = '0' AND `ticket_id` = '0' AND";
        } else if ($coupon_status == 2) {
            $status_con = "`used_by` != '0' AND `status` != '0' AND `ticket_id` != '0' AND";
        } else {
            $status_con = "";
        }
        $fromdate = date("Y-m-d", strtotime($_POST['formdate']));
        $todate = date("Y-m-d", strtotime($_POST['todate']));
        if ($fromdate != '' && $todate != '') {
            $dateFilter = "`createdon` BETWEEN '$fromdate 00:00:00' AND '$todate 23:59:59' AND";
        }
        $couponcode = select_query($con, "couponcode", "", "$coupon_con $status_con $dateFilter `deletes`='0' ORDER BY `id` DESC", "", "");
        if ($couponcode['nr'] > 0) {
            foreach ($couponcode['result'] as $key => $value) {
                $status = "";
                $action = "";
                if ((int) $value['used_by'] == 0 && (int) $value['status'] == 0 && (int) $value['ticket_id'] == 0) {
                    $status = strtoupper('generated');
                } else if ((int) $value['used_by'] != 0 && (int) $value['status'] == 0 && (int) $value['ticket_id'] == 0) {
                    $status = strtoupper('assigned');
                } else if ((int) $value['used_by'] != 0 && (int) $value['status'] != 0 && (int) $value['ticket_id'] != 0) {
                    $status = strtoupper('used');
                    $transaction_id = select_top_name($con, "cticket", "transaction_id", "`id`=" . $value['ticket_id'] . " and `deletes`='0'  order by `id` DESC ", "transaction_id", "");
                    if ($transaction_id != '') {
                        $action = '<a target="_blank" href="' . $baseurl . 'ticket-view/' . $transaction_id . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span class="fa fa-files-o fs-14"></span></a>';
                    }
                }
                $result[] = ["couponcode" => $value['couponcode'], "coupontype" => $value['coupontype'], "status" => $status, "expiredon" => date('d-m-Y g:i a', strtotime($value['expiredon'])), "createdon" => date('d-m-Y g:i a', strtotime($value['createdon'])), "action" => $action];
            }
        }
        echo json_encode($result);
    } catch (Exception $e) {
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'datatable_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == 'searchReferral') {
    try {
        $result = [];
        $fromdate = date("Y-m-d", strtotime($_POST['formdate']));
        $todate = date("Y-m-d", strtotime($_POST['todate']));
        if ($fromdate != '' && $todate != '') {
            $dateFilter = "`createdon` BETWEEN '$fromdate 00:00:00' AND '$todate 23:59:59' AND";
        }
        $referral_status = (int) $_POST['referral_status'];
        if ($referral_status == 1) {
            $status_con = "`status` = '1' AND";
        } else if ($referral_status == 2) {
            $status_con = "`status` = '0' AND";
        } else {
            $status_con = "";
        }
        $referralcode = select_query($con, "referral_history", "", " $dateFilter $status_con `deletes`='0' ORDER BY `id` DESC", "", "");
        if ($referralcode['nr'] > 0) {
            foreach ($referralcode['result'] as $key => $value) {
                $usercount = 0;
                if ($value['to_user_ids'] != '') {
                    $totalUsers = explode(',', $value['to_user_ids']);
                    $usercount = count($totalUsers);
                }
                if ((int) $value['status'] == 1) {
                    $status = 'finished';
                } else if ((int) $value['status'] == '0') {
                    $status = 'pending';
                } else {
                    $status = '';
                }
                $ticketid = select_top_name($con, "ticket", "ticket_no", "`referral_id`='" . $value['id'] . "' and `deletes`='0'  order by `id` DESC ", "ticket_no", "");
                $user_register = select_query($con, "user_register", "`id`, `email`, `name`, `mobile`", "`id`='" . $value['from_user_id'] . "' and `deletes`='0'  order by `id` DESC ", "", "");
                if ($user_register['nr'] > 0) {
                    $name = $user_register['result'][0]['name'];
                    $email = $user_register['result'][0]['email'];
                    $mobile = $user_register['result'][0]['mobile'];
                }
                $result[] = ["ticketid" => $ticketid, "referralcode" => $value['refferral_code'], "name" => $name, "email" => $email, "mobile" => $mobile, "usercount" => $usercount, "targetamt" => $value['target_amt'], "acamt" => $value['achieved_amt'], "status" => strtoupper($status)];
            }
        }
        echo json_encode($result);
    } catch (Exception $e) {
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'datatable_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == "list_Wallticket") {
    // $result = [];
    $contype = '';
    $type = 'OT';
    $da = '';
    $now = date('Y-m-d');
    $formdate = BlockSQLInjectionforagent($_POST["formdate"]);
    $todate = BlockSQLInjectionforagent($_POST["todate"]);
    // $formdate = $_POST['formdate'];
    // $todate = $_POST['todate'];
    if ($formdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "ticket.purchase_datetime BETWEEN '$fd 00:00:00' AND '$td 23:59:59' ";
        $contype1 = "wticket.purchase_datetime BETWEEN '$fd 00:00:00' AND '$td 23:59:59' ";
    } else {
        $da = 'DESC';
        $contype = "ticket.purchase_datetime LIKE '%$now%'";
        $contype1 = "wticket.purchase_datetime LIKE '%$now%'";
    }
    $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$memid' and `deletes`='0'", "roll_id", "");
    if ($roll_id != 1 && $roll_id != 2 && $roll_id != 6 && $roll_id != 8) {
        $agent = "`agent_id` = '$memid' AND";
    } else {
        $agent = "";
    }
    // var_dump("SELECT ticket.id AS 'ticketid', user_register.name AS 'name', user_register.mobile AS 'mobile', user_register.email AS 'email', ticket_lines.my3number AS 'my3number', ticket_lines.raffle_id AS 'raffleid', product.rate AS 'productamt', ticket.purchase_datetime AS 'purchasedatetime', ticket.transaction_id AS 'transactionid' ,ticket.ticket_no AS 'ticket_no' FROM ticket_lines INNER JOIN `ticket` ON ticket.id = ticket_lines.ticket_id INNER JOIN invoice ON ticket.invoice_no = invoice.id INNER JOIN user_register ON ticket.user_id = user_register.id INNER JOIN product ON ticket_lines.product_id = product.id WHERE ticket.deletes = '0' AND invoice.response = 'wallet' AND ticket_lines.type = 'OT' AND $contype");
    // die;
    $wticket = mysqli_query($con, "SELECT ticket.id AS 'ticketid', user_register.name AS 'name',user_register.lname AS 'lname', user_register.mobile AS 'mobile', user_register.email AS 'email', ticket_lines.my3number AS 'my3number', ticket_lines.raffle_id AS 'raffleid', product.rate AS 'productamt', ticket.purchase_datetime AS 'purchasedatetime', ticket.transaction_id AS 'transactionid' ,ticket.ticket_no AS 'ticket_no' FROM ticket_lines INNER JOIN `ticket` ON ticket.id = ticket_lines.ticket_id INNER JOIN invoice ON ticket.invoice_no = invoice.id INNER JOIN user_register ON ticket.user_id = user_register.id INNER JOIN product ON ticket_lines.product_id = product.id WHERE ticket.deletes = '0' AND invoice.response = 'wallet' AND ticket_lines.type = 'OT' AND $contype");
    while ($value = mysqli_fetch_assoc($wticket)) {
        $transaction_id = $value['transactionid'];
        $action = '';
        $action .= '<div class="g-2">';
        $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' . $transaction_id . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span style="font-size: 18px;" class="fa fa-file-text-o"></span></a>';
        $action .= '<a target="_blank" href="' . $baseurl . 'ticket-view/' . $transaction_id . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span style="font-size: 18px;" class="fa fa-files-o"></span></a>';
        if ($_SESSION['memid'] == 1) {
            $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size: 18px;" class="fa fa-paper-plane" onclick="sendemailtopurchase(' . "'$transaction_id'" . ', ' . "'OT'" . ', ' . "'ticket'" . ')">&nbsp;Email</span></a>';
            $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8; font-size: 18px;" onclick="sendsmstopurchase(' . "'$transaction_id'" . ', ' . "'OT'" . ', ' . "'ticket'" . ')">&nbsp;SMS</span></a>';
            // $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" onclick="sendemailtopurchase(' . "'$transaction_id'" . ')">Send Email</span></a>';
            // $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8;" onclick="sendsmstopurchase(' . "'$transaction_id'" . ')">Send Sms</span></a>';
            // $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8;" onclick="bothemailsms(' . "'$transaction_id'" . ')">Both</span></a>';
            // $action .= '<button value="' . $transaction_id . '" onclick="deleteoticket($(this).val())" class="btn btn-danger edit"><span class="fe fe-trash-2 fs-14 sssss"></span></button>';
        }
        $action .= '</div>';
        $result[] = array("action" => $action, "transaction_id" => $value['transactionid'], "proamt" => $value['productamt'], "RaffleID" => $value['raffleid'], "My3Numbers" => $value['my3number'], "email" => $value['email'], "mobile" => $value['mobile'], "cusname" => $value['name'], "lname" => $value['lname'], "ticketno" => $value['ticket_no'], "purdate" => '<span style="display:none;">' . strtotime($value['purchasedatetime']) . '</span>' . date("d-M-Y g:i a", strtotime($value['purchasedatetime'])));
    }
    $wtticket = mysqli_query($con, "SELECT wticket.id AS 'ticketid',user_register.name AS 'name', user_register.mobile AS 'mobile', user_register.email AS 'email', ticket_lines.my3number AS 'my3number', ticket_lines.raffle_id AS 'raffleid', product.rate AS 'productamt',wticket.invoice_no, wticket.purchase_datetime AS 'purchasedatetime', wticket.transaction_id AS 'transactionid' , wticket.ticket_no AS 'ticket_no' FROM ticket_lines INNER JOIN `wticket` ON wticket.id = ticket_lines.ticket_id INNER JOIN user_register ON wticket.user_id = user_register.id INNER JOIN product ON ticket_lines.product_id = product.id WHERE wticket.deletes = 0 AND ticket_lines.type = 'WT' AND $contype1");
    while ($value = mysqli_fetch_assoc($wtticket)) {
        $transaction_id = $value['transactionid'];
        $action = '';
        $action .= '<div class="g-2">';
        if ($value['invoice_no'] != '' && $value['invoice_no'] != 0) {
            $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' . $transaction_id . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span style="font-size: 18px;" class="fa fa-file-text-o"></span></a>';
        }
        $action .= '<a target="_blank" href="' . $baseurl . 'ticket-view/' . $transaction_id . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span style="font-size: 18px;" class="fa fa-files-o">&nbsp;Ticket</span></a>';
        if ($_SESSION['memid'] == 1) {
            $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span style="font-size: 18px;" class="fa fa-paper-plane" onclick="sendemailtopurchase(' . "'$transaction_id'" . ', ' . "'WT'" . ', ' . "'wticket'" . ')">&nbsp;Email</span></a>';
            $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8; font-size: 18px;" onclick="sendsmstopurchase(' . "'$transaction_id'" . ', ' . "'WT'" . ', ' . "'wticket'" . ')">&nbsp;SMS</span></a>';
            // $action .= '<a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-paper-plane" style="color: #1a73e8;" onclick="bothemailsms(' . "'$transaction_id'" . ')">Both</span></a>';
            // $action .= '<a onclick="deletewticket(' . "'$transaction_id'" . ')" style="cursor: pointer; color: ##fb0909;"><span class="fe fe-trash-2 fs-14 sssss"></span></a>';
            // $action .= '<button value="' . $transaction_id . '" onclick="deletewticket($(this).val())" class="btn btn-danger edit"><span class="fe fe-trash-2 fs-14 sssss"></span></button>';
        }
        $action .= '</div>';
        $result[] = array("action" => $action, "transaction_id" => $value['transactionid'], "proamt" => $value['productamt'], "RaffleID" => $value['raffleid'], "My3Numbers" => $value['my3number'], "email" => $value['email'], "mobile" => $value['mobile'], "cusname" => $value['name'] . ' ' . $value['lname'], "ticketno" => $value['ticket_no'], "purdate" => '<span style="display:none;">' . strtotime($value['purchasedatetime']) . '</span>' . date("d-M-Y g:i a", strtotime($value['purchasedatetime'])));
    }
    echo json_encode($result);
} else if ($method == "raffledraw") {
    try {
        $result = [];
        $raffle_draw = mysqli_query($con, "SELECT raffle_draw.id AS 'raffledrawid', raffle_draw_name, draw_date, raffle_id, draw_id, draw_name, product_id, ticket_lines_id, user_id, username, email, mobile, status, raffle_draw.deletes AS 'deletes', updatedon, raffle_draw.createdon AS 'createdon', product.name AS 'productname', product.raffle_prize AS 'raffle_prize' FROM `raffle_draw` INNER JOIN product ON raffle_draw.product_id = product.id ORDER BY raffle_draw.id ASC");
        while ($row = mysqli_fetch_assoc($raffle_draw)) {
            $result[] = [
                "sno" => $row['raffledrawid'],
                "raffleid" => $row['raffle_id'],
                "drawname" => $row['draw_name'],
                "name" => ucwords(strtolower($row['username'])),
                "email" => $row['email'],
                "mobile" => $row['mobile'],
                "productname" => $row['productname'],
                "prizeamt" => number_format(intval($row['raffle_prize'])),
                "drawdate" => date('d-m-Y', strtotime($row['draw_date'])),
            ];
        }
        echo json_encode($result);
    } catch (Exception $e) {
        $error = ['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        $cron_testarr = array("user_id" => $_SESSION['memid'], "reason" => json_encode($error), "filename" => 'datatable_services.php', "draw_id" => '', "creadedon" => $dubaidate_time);
        $cron_test = insert($con, "cron_test", "", $cron_testarr, "", "", "");
    }
} else if ($method == "payment_status_history") {
    $result = [];
    $contype = '';
    $type = 'OT';
    $da = '';
    $gt = '';
    $now = date('Y-m-d');
    $formdate = BlockSQLInjection($_POST["formdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);
    // $formdate = $_POST['formdate'];
    // $todate = $_POST['todate'];
    if ($formdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($formdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "`payby_link`.`createdon` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' order by `payby_link`.`id` DESC";
    } else {
        $da = 'DESC';
        $contype = "`payby_link`.`createdon` LIKE '%$now%'  order by `payby_link`.`id` DESC";
    }
    $paymentStatus = BlockSQLInjectionforreportrange($_POST["paymentStatus"]);
    // $paymentStatus = $_POST['paymentStatus'];
    if ($paymentStatus == 'initiated') {
        $paymentCon = "`payby_link`.`email_sms_status` = '1' AND `payby_link`. `status` ='Initiated' AND";
    } else if ($paymentStatus == 'completed') {
        $paymentCon = "`payby_link`.`email_sms_status` = '1' AND `payby_link`. `status` ='Paid' AND";
    }
    $payment_history = mysqli_query($con, "SELECT `payby_link`.`is_cron`,`payby_link`.`user_id`,`payby_link`.`status`,`payby_link`.`payment_id`, `payby_link`.`Newpayment_id`, `payby_link`.`createdon`, `user_register`.`name`, `payby_link`.`mobile_num`,`payby_link`.`email_id`, `payby_link`.`type`,  `payby_link`.`expired` FROM `payby_link`  INNER JOIN user_register ON payby_link.user_id = user_register.id WHERE  $paymentCon $contype ");
    while ($row = mysqli_fetch_assoc($payment_history)) {
        $payment_id = $row['payment_id'];
        $Newpayment_id = $row['Newpayment_id'];
        $payment_customer1 = select_query($con, "payment_history", "", " `id`= '$payment_id' ", "", "");
        $payment_customer2 = select_query($con, "payment_history", "", " `id`= '$row[Newpayment_id]' ", "", "");
        $old_transid = $payment_customer1['result'][0]['transaction_id'];
        $new_transid = $payment_customer2['result'][0]['transaction_id'];
        $purchase_amt = $payment_customer2['result'][0]['finaltotal'];
        // $action = '';
        // $action .= '<a target="_blank" href="' . $baseurl . 'invoice/' .  $new_transid . '" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span class="fa fa-file-text-o fs-14"></span></a>';
        // $action .= '<a target="_blank" href="' . $baseurl . 'ticket-view/' .  $new_transid . '" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span class="fa fa-files-o fs-14"></span></a>';
        if ($row['is_cron'] == '0') {
            $isCron = 'No';
        } else {
            $isCron = 'Yes';
        }
        $result[] = array(
            "is_cron" => $isCron,
            "linkstatus" => $row['status'],
            "type" => $row['type'],
            "newtransaction" => $new_transid,
            "mobile" => $row['mobile_num'],
            "oldtransaction" => $old_transid,
            "email" => $row['email_id'],
            "userid" => $row['user_id'],
            "name" => $row['name'],
            "amount" => $purchase_amt,
            "action" => $action,
            "date" => date("d-M-Y g:i a", strtotime($row['createdon'])),
        );
    }
    echo json_encode($result);
} elseif ($method == 'userprofile_report') {
    $result = [];
    $now = date('Y-m-d');
    $agdate = BlockSQLInjection($_POST["agdate"]);
    $todate = BlockSQLInjection($_POST["todate"]);
    if ($agdate != '' && $todate != '') {
        $da = 'DESC';
        $fd = date("Y-m-d", strtotime($agdate));
        $td = date("Y-m-d", strtotime($todate));
        $contype = "user_profile_activity_log.`updated_datetime` BETWEEN '$fd 00:00:00' AND '$td 23:59:59' ORDER BY user_profile_activity_log.id DESC";
    } else {
        $da = 'DESC';
        $contype = "user_profile_activity_log.`updated_datetime` LIKE '%$now%' ORDER BY user_profile_activity_log.id DESC";
    }
    $emaillog = mysqli_query($con, "SELECT user_profile_activity_log.*,user_register.email as userEmail,changedBy.email as changedByEmail FROM `user_profile_activity_log` JOIN user_register ON user_register.id = user_profile_activity_log.user_id JOIN user_register changedBy ON changedBy.id = user_profile_activity_log.changed_by where " . $contype);
    if (mysqli_num_rows($emaillog) > 0) {
        $i = 1;
        while ($row = mysqli_fetch_assoc($emaillog)) {
            if (count(json_decode($row['changed_data'], true)) > 0) {
                $result[] = ["userId" => $i++, "userEmail" => $row['userEmail'], "changedBy" => $row['changedByEmail'], "changedData" => $row['changed_data'], "ip" => $row['ip'], "datetime" => $row['updated_datetime']];
            }
        }
    }
    echo json_encode($result);
} elseif ($method == 'change_sub_status') {
    try {
        $crm_id = BlockSQLInjection($_POST["crmId"]);
        $userId = BlockSQLInjection($_POST["userId"]);
        $status = BlockSQLInjection($_POST["status"]);
        $access_update = update($con, "crm", "`id` = '$crm_id' AND `deletes` = '0' AND `userID` = '$userId'", ["manual_sub_access" => $status], "", "", "", "");
        // $ndticket_update &&
        if ($access_update) {
            $result["result"] = "Access Updated Successfully";
            $result["type"] = '1';
        } else {
            $result["result"] = "Access Updation Failed";
            $result["type"] = '0';
        }
    } catch (\Exception $e) {
        $result["result"] = "Access Updation Failed";
        $result["type"] = '1'; // You had '0' on failure earlier, but generally, 1 = error
    }
    echo json_encode($result);
} elseif ($method == 'customerComplaint') {
    try {
        $startDate = $_POST['startDate'] ?? '';
        $endDate = $_POST['endDate'] ?? '';
        $dateFilter = $_POST['dateFilter'] ?? '';
        $searchTxt = $_POST['searchTxt'] ?? '';
        $filterParts = [];
        $result = [];
        // Search filter
        if (!empty($searchTxt)) {
            $filterParts[] = "(reported.name LIKE '%$searchTxt%' 
                            OR reported.email LIKE '%$searchTxt%' 
                            OR reported.mobile LIKE '%$searchTxt%' 
                            OR reporter.name LIKE '%$searchTxt%' 
                            OR reporter.email LIKE '%$searchTxt%' 
                            OR reporter.mobile LIKE '%$searchTxt%')";
        }
        // Date range filter
        if (!empty($dateFilter)) {
            if (empty($startDate) || empty($endDate)) {
                $result['type'] = 0;
                $result['result'] = [];
                $result['data'] = [];
                goto resutGJHIPQ;
            }
            $filterParts[] = "cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
        } else {
            $todayStart = date('Y-m-d') . ' 00:00:00';
            $todayEnd = date('Y-m-d') . ' 23:59:59';
            $filterParts[] = "cu.created_at BETWEEN '$todayStart' AND '$todayEnd'";
        }
        // Build WHERE clause
        $whereClause = '';
        if (!empty($filterParts)) {
            $whereClause = 'WHERE ' . implode(' AND ', $filterParts);
        }
        $s_query = "
            SELECT 
                cu.id,
                cu.user_id,
                cu.reporter_id,
                reported.name AS reported_name,
                reporter.name AS reporter_name,
                cu.reason,
                cu.status,
                cu.message,
                cu.created_at,
                cu.attachments
            FROM complain_user cu
            JOIN user_register reported ON cu.user_id = reported.id
            JOIN user_register reporter ON cu.reporter_id = reporter.id
            $whereClause
        ";
        $result_data = mysqli_query($con, $s_query);
        $result = [];
        $result["type"] = '0';
        if (mysqli_num_rows($result_data) > 0) {
            while ($row = mysqli_fetch_assoc($result_data)) {
                $result['result'][] = $row;
            }
            $result["type"] = '1';
            goto resutGJHIPQ;
        } else {
            $result['result'] = [];
        }
    } catch (\Exception $e) {
        $result["result"] = $e->getMessage();
        $result["type"] = '0';
    }
    resutGJHIPQ:
    echo json_encode($result);
} elseif ($method == 'kycDetails') {
    try {
        $startDate = $_POST['startDate'] ?? '';
        $endDate = $_POST['endDate'] ?? '';
        $dateFilter = $_POST['dateFilter'] ?? '';
        $searchTxt = $_POST['searchTxt'] ?? '';
        $u_type = $_POST['u_type'] ?? '';
        $own_type = $_POST['search_filter'] ?? '';
        $filterParts = [];
        $result = [];
        // Search filter
        if (!empty($searchTxt)) {
            $filterParts[] = "(cu.name LIKE '%$searchTxt%' 
                            OR cu.email LIKE '%$searchTxt%' 
                            OR cu.mobile LIKE '%$searchTxt%' 
                            OR cu.name LIKE '%$searchTxt%' 
                            OR cu.email LIKE '%$searchTxt%' 
                            OR cu.mobile LIKE '%$searchTxt%')";
        }
        // Date range filter
        if (!empty($dateFilter)) {
            if (empty($startDate) || empty($endDate)) {
                $result['type'] = 0;
                $result['result'] = [];
                $result['data'] = [];
                goto resutGJHIP;
            }
            $filterParts[] = "kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
        } else {
            $todayStart = date('Y-m-d') . ' 00:00:00';
            $todayEnd = date('Y-m-d') . ' 23:59:59';
            $filterParts[] = "kd.created_at BETWEEN '$todayStart' AND '$todayEnd'";
        }
        // var_dump($filterParts);die;
        if ($u_type == 'pending') {
            // $filterParts[] = "((cu.doc_verify = '0' AND cu.vehicle_verify = '0' AND kd.type = 'Driver') OR (cu.doc_verify = '0' AND kd.type = 'Owner'))";
        } elseif ($u_type == 'kyc_pending') {
            $filterParts[] = "cu.doc_verify = '0' AND kd.o_status == '3'";
        } elseif ($u_type == 'vehicle_pending') {
            $filterParts[] = "(cu.vehicle_verify = '1' AND 
                JSON_EXTRACT(cu.vehicle_details, '$.admin_verify') = false
                AND (
                    JSON_EXTRACT(cu.vehicle_details, '$.vehicle_review_message') IS NULL 
                    OR JSON_EXTRACT(cu.vehicle_details, '$.vehicle_review_message') LIKE '%wait for admin approval%'
                ))";
        } elseif ($u_type == 'verified') {
            $filterParts[] = "
                ((
                    (cu.doc_verify = '1' AND cu.vehicle_verify = '2' AND kd.type = 'Driver')
                    AND JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.admin_verify')) = 'true'
                    AND JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.vehicle_review_message')) LIKE '%Vehicle details verified%'
                )OR (cu.doc_verify = '1' AND kd.o_proof_status = 'approved' AND kd.type = 'Owner'))
            ";
        } elseif ($u_type == 'rejected_kyc') {
            $filterParts[] = "cu.doc_verify = '0' AND kd.updated_by IS NOT NULL AND kd.reject_reason IS NOT NULL";
        } elseif ($u_type == 'rejected_vehicle') {
            $filterParts[] = "
                (JSON_EXTRACT(cu.vehicle_details, '$.admin_verify') = false
                AND (
                    JSON_EXTRACT(cu.vehicle_details, '$.vehicle_review_message') IS NULL 
                    OR JSON_EXTRACT(cu.vehicle_details, '$.vehicle_review_message') NOT LIKE '%wait for admin approval%'
                ))
            ";
        }
        // Build WHERE clause
        $whereClause = '';
        if (!empty($filterParts)) {
            $whereClause = 'WHERE ' . implode(' AND ', $filterParts);
        }
        $s_query = "
            SELECT 
                cu.name,
                cu.mobile,
                cu.doc_verify,
                cu.vehicle_verify,
                kd.*
            FROM kyc_details kd
            JOIN user_register cu ON kd.user_id = cu.id
            $whereClause AND cu.deletes = '0' AND kd.deletes = 0
        ";
        // var_dump($s_query);die;
        $result_data = mysqli_query($con, $s_query);
        $result = [];
        $result["type"] = '0';
        if (mysqli_num_rows($result_data) > 0) {
            while ($row = mysqli_fetch_assoc($result_data)) {
                $result['result'][] = $row;
            }
            $result["type"] = '1';
            goto resutGJHIP;
        } else {
            $result["type"] = '0';
            $result['result'] = [];
        }
    } catch (\Exception $e) {
        $result["result"] = [];
        $result["type"] = '0';
    }
    resutGJHIP:
    echo json_encode($result);
} elseif ($method == 'kycDetails2') {
    try {
        $startDate = $_POST['startDate'] ?? '';
        $endDate = $_POST['endDate'] ?? '';
        $dateFilter = $_POST['dateFilter'] ?? '';
        $searchTxt = $_POST['searchTxt'] ?? '';
        $u_type = $_POST['kyc_filter'] ?? '';
        $own_type = $_POST['search_filter'] ?? '';
        $filterParts = [];
        $result = [];
        // Search filter
        if (!empty($searchTxt)) {
            $filterParts[] = "(cu.name LIKE '%$searchTxt%' 
                            OR cu.email LIKE '%$searchTxt%' 
                            OR cu.mobile LIKE '%$searchTxt%' 
                            OR cu.name LIKE '%$searchTxt%' 
                            OR cu.email LIKE '%$searchTxt%' 
                            OR cu.mobile LIKE '%$searchTxt%')";
        }
        // Date range filter
        if ($u_type == 'all') {
            if (!empty($dateFilter)) {
                if (empty($startDate) || empty($endDate)) {
                    $result['type'] = 0;
                    $result['result'] = [];
                    $result['data'] = [];
                    goto resutGJHIPPP;
                }
                if ($own_type != 'all') {
                    $filterParts[] = "((kd.type = '$own_type') AND ((kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59')))";
                } else {
                    $filterParts[] = "((kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') )";
                }
            } else {
                // var_dump("dfghj");die;
                $todayStart = date('Y-m-d') . ' 00:00:00';
                $todayEnd = date('Y-m-d') . ' 23:59:59';
                if ($own_type != 'all') {
                    $filterParts[] = "((kd.type = '$own_type') AND ((kd.created_at BETWEEN '$todayStart' AND '$todayEnd') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'))) ";
                } else {
                    $filterParts[] = "((kd.created_at BETWEEN '$todayStart' AND '$todayEnd') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59')) ";
                }
            }
            $filterParts[] = "cu.roll_id = '0' AND cu.deletes = '0' ORDER BY kd.updated_at DESC";
        } elseif ($u_type == 'k_n_c') {
            if (!empty($dateFilter)) {
                if (empty($startDate) || empty($endDate)) {
                    $result['type'] = 0;
                    $result['result'] = [];
                    $result['data'] = [];
                    goto resutGJHIPPP;
                }
                if ($own_type != 'all') {
                    $filterParts[] = "((kd.type = '$own_type') AND ((kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') ))";
                } else {
                    $filterParts[] = "((kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') )";
                }
            } else {
                $todayStart = date('Y-m-d') . ' 00:00:00';
                $todayEnd = date('Y-m-d') . ' 23:59:59';
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND ((kd.created_at BETWEEN '$todayStart' AND '$todayEnd') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'))) ";
                } else {
                    $filterParts[] = "((kd.created_at BETWEEN '$todayStart' AND '$todayEnd') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59')) ";
                }
            }
            $filterParts[] = "
            (
                cu.doc_verify = '0'
                AND
                (
                    kd.id IS NOT NULL
                    AND kd.o_status < 3
                )
            ) AND cu.deletes = '0' ORDER BY kd.updated_at DESC
            ";
        } elseif ($u_type == 'k_n_v') {
            if (!empty($dateFilter)) {
                if (empty($startDate) || empty($endDate)) {
                    $result['type'] = 0;
                    $result['result'] = [];
                    $result['data'] = [];
                    goto resutGJHIPPP;
                }
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND ((kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59')) )";
                } else {
                    $filterParts[] = "((kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') )";
                }
            } else {
                $todayStart = date('Y-m-d') . ' 00:00:00';
                $todayEnd = date('Y-m-d') . ' 23:59:59';
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND (kd.created_at BETWEEN '$todayStart' AND '$todayEnd')) ";
                } else {
                    $filterParts[] = "((kd.created_at BETWEEN '$todayStart' AND '$todayEnd')) ";
                }
            }
            $filterParts[] = "(cu.doc_verify = '0' AND kd.o_status = '3' AND cu.deletes = '0')";
        } elseif ($u_type == 'k_r') {
            if (!empty($dateFilter)) {
                if (empty($startDate) || empty($endDate)) {
                    $result['type'] = 0;
                    $result['result'] = [];
                    $result['data'] = [];
                    goto resutGJHIPPP;
                }
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND ((kd.update_date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' AND kd.update_date IS NULL)))";
                } else {
                    $filterParts[] = "((kd.update_date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' AND kd.update_date IS NULL))";
                }
            } else {
                $todayStart = date('Y-m-d') . ' 00:00:00';
                $todayEnd = date('Y-m-d') . ' 23:59:59';
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND ((kd.update_date BETWEEN '$todayStart' AND '$todayEnd') OR (kd.created_at BETWEEN '$todayStart' AND '$todayEnd' AND kd.update_date IS NULL))) ";
                } else {
                    $filterParts[] = "((kd.update_date BETWEEN '$todayStart' AND '$todayEnd') OR (kd.created_at BETWEEN '$todayStart' AND '$todayEnd' AND kd.update_date IS NULL)) ";
                }
            }
            $filterParts[] = "cu.doc_verify = '0' AND kd.updated_by IS NOT NULL AND kd.reject_reason IS NOT NULL AND kd.id IS NOT NULL AND cu.deletes = '0' ORDER BY kd.updated_at DESC";
        } elseif ($u_type == 'k_v') {
            if (!empty($dateFilter)) {
                if (empty($startDate) || empty($endDate)) {
                    $result['type'] = 0;
                    $result['result'] = [];
                    $result['data'] = [];
                    goto resutGJHIPPP;
                }
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND ((kd.update_date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' AND kd.update_date IS NULL)))";
                } else {
                    $filterParts[] = "((kd.update_date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' AND kd.update_date IS NULL))";
                }
            } else {
                $todayStart = date('Y-m-d') . ' 00:00:00';
                $todayEnd = date('Y-m-d') . ' 23:59:59';
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND ((kd.update_date BETWEEN '$todayStart' AND '$todayEnd') OR (kd.created_at BETWEEN '$todayStart' AND '$todayEnd' AND kd.update_date IS NULL)))";
                } else {
                    $filterParts[] = "((kd.update_date BETWEEN '$todayStart' AND '$todayEnd') OR (kd.created_at BETWEEN '$todayStart' AND '$todayEnd' AND kd.update_date IS NULL))";
                }
            }
            $filterParts[] = "
                    cu.doc_verify = '1' AND cu.deletes = '0' AND kd.o_status = 3 ORDER BY kd.updated_at DESC
            ";
        } elseif ($u_type == 'v_n_c') {
            if (!empty($dateFilter)) {
                if (empty($startDate) || empty($endDate)) {
                    $result['type'] = 0;
                    $result['result'] = [];
                    $result['data'] = [];
                    goto resutGJHIPPP;
                }
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND ((kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59')) )";
                } else {
                    $filterParts[] = "((kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') )";
                }
            } else {
                $todayStart = date('Y-m-d') . ' 00:00:00';
                $todayEnd = date('Y-m-d') . ' 23:59:59';
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND ((kd.created_at BETWEEN '$todayStart' AND '$todayEnd') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'))) ";
                } else {
                    $filterParts[] = "((kd.created_at BETWEEN '$todayStart' AND '$todayEnd') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59')) ";
                }
            }
            $filterParts[] = "
            (
                cu.vehicle_verify = '0'
                AND
                cu.vehicle_details IS NULL
            ) AND cu.deletes = '0' ORDER BY cu.v_updated_at DESC
            ";
        } elseif ($u_type == 'v_n_v_n') {
            if (!empty($dateFilter)) {
                if (empty($startDate) || empty($endDate)) {
                    $result['type'] = 0;
                    $result['result'] = [];
                    $result['data'] = [];
                    goto resutGJHIPPP;
                }
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND ((kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59')) )";
                } else {
                    $filterParts[] = "((kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') )";
                }
            } else {
                $todayStart = date('Y-m-d') . ' 00:00:00';
                $todayEnd = date('Y-m-d') . ' 23:59:59';
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND ((kd.created_at BETWEEN '$todayStart' AND '$todayEnd') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'))) ";
                } else {
                    $filterParts[] = "((kd.created_at BETWEEN '$todayStart' AND '$todayEnd') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59')) ";
                }
            }
            $filterParts[] = "
            (
                cu.vehicle_verify > 0
              AND LOWER(vehicle_details->>'$.vehicle_review_message')
                   LIKE '%wait for admin approval%'
  AND cu.vehicle_details->>'$.vehicle.boot_image_url' IS NOT NULL
    AND cu.vehicle_details->>'$.vehicle.boot_image_url' != ''
    AND cu.vehicle_details->>'$.vehicle.boot_image_url' <> ''
            ) AND cu.deletes = '0' ORDER BY cu.v_updated_at DESC
            ";
        } elseif ($u_type == 'na_p') {
            if (!empty($dateFilter)) {
                if (empty($startDate) || empty($endDate)) {
                    $result['type'] = 0;
                    $result['result'] = [];
                    $result['data'] = [];
                    goto resutGJHIPPP;
                }
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND ((kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59')) )";
                } else {
                    $filterParts[] = "((kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') )";
                }
            } else {
                $todayStart = date('Y-m-d') . ' 00:00:00';
                $todayEnd = date('Y-m-d') . ' 23:59:59';
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND ((kd.created_at BETWEEN '$todayStart' AND '$todayEnd') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'))) ";
                } else {
                    $filterParts[] = "((kd.created_at BETWEEN '$todayStart' AND '$todayEnd') OR (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59')) ";
                }
            }
            $filterParts[] = "
                cu.doc_verify = '0' AND kd.type = 'Owner' AND kd.id IS NOT NULL AND o_proof_type = 'name_board' AND o_proof_status = 'Inreview' AND cu.deletes = '0' ORDER BY kd.update_date DESC
            ";
        } elseif ($u_type == 'na_v') {
            if (!empty($dateFilter)) {
                if (empty($startDate) || empty($endDate)) {
                    $result['type'] = 0;
                    $result['result'] = [];
                    $result['data'] = [];
                    goto resutGJHIPPP;
                }
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND ((kd.update_date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' AND kd.update_date IS NULL)))";
                } else {
                    $filterParts[] = "((kd.update_date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' AND kd.update_date IS NULL))";
                }
            } else {
                $todayStart = date('Y-m-d') . ' 00:00:00';
                $todayEnd = date('Y-m-d') . ' 23:59:59';
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND (kd.update_date BETWEEN '$todayStart' AND '$todayEnd')) ";
                } else {
                    $filterParts[] = "((kd.update_date BETWEEN '$todayStart' AND '$todayEnd')) ";
                }
            }
            $filterParts[] = "
                kd.type = 'Owner' AND kd.id IS NOT NULL AND o_proof_type = 'name_board' AND o_proof_status = 'approved' AND cu.deletes = '0' ORDER BY kd.update_date DESC
            ";
        } elseif ($u_type == 'na_r') {
            if (!empty($dateFilter)) {
                if (empty($startDate) || empty($endDate)) {
                    $result['type'] = 0;
                    $result['result'] = [];
                    $result['data'] = [];
                    goto resutGJHIPPP;
                }
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND ((kd.update_date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' AND kd.update_date IS NULL)))";
                } else {
                    $filterParts[] = "((kd.update_date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' AND kd.update_date IS NULL))";
                }
            } else {
                $todayStart = date('Y-m-d') . ' 00:00:00';
                $todayEnd = date('Y-m-d') . ' 23:59:59';
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND ((kd.update_date BETWEEN '$todayStart' AND '$todayEnd') OR (kd.created_at BETWEEN '$todayStart' AND '$todayEnd' AND kd.update_date IS NULL))) ";
                } else {
                    $filterParts[] = "((kd.update_date BETWEEN '$todayStart' AND '$todayEnd') OR (kd.created_at BETWEEN '$todayStart' AND '$todayEnd' AND kd.update_date IS NULL)) ";
                }
            }
            $filterParts[] = "
                cu.doc_verify = '0' ADN kd.type = 'Owner' AND kd.id IS NOT NULL AND o_proof_type = 'name_board' AND o_proof_status = 'rejected' AND cu.deletes = '0' ORDER BY kd.update_date DESC
            ";
        } elseif ($u_type == 'v_n_v') {
            if (!empty($dateFilter)) {
                if (empty($startDate) || empty($endDate)) {
                    $result['type'] = 0;
                    $result['result'] = [];
                    $result['data'] = [];
                    goto resutGJHIPPP;
                }
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type ='$own_type' AND (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') )";
                } else {
                    $filterParts[] = "((cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') )";
                }
            } else {
                $todayStart = date('Y-m-d') . ' 00:00:00';
                $todayEnd = date('Y-m-d') . ' 23:59:59';
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND (cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59')) ";
                } else {
                    $filterParts[] = "((cu.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59')) ";
                }
            }
            $filterParts[] = "
            (
                cu.vehicle_verify = 1
                AND JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.vehicle_review_message')) = 'Wait for admin approval' AND
                cu.vehicle_details IS NOT NULL
            ) AND cu.deletes = '0' ORDER BY cu.v_updated_at DESC
            ";
        } elseif ($u_type == 'v_r') {
            if (!empty($dateFilter)) {
                if (empty($startDate) || empty($endDate)) {
                    $result['type'] = 0;
                    $result['result'] = [];
                    $result['data'] = [];
                    goto resutGJHIPPP;
                }
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND (cu.v_updated_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'))";
                } else {
                    $filterParts[] = "((cu.v_updated_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'))";
                }
            } else {
                $todayStart = date('Y-m-d') . ' 00:00:00';
                $todayEnd = date('Y-m-d') . ' 23:59:59';
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND (cu.v_updated_at BETWEEN '$todayStart' AND '$todayEnd')) ";
                } else {
                    $filterParts[] = "((cu.v_updated_at BETWEEN '$todayStart' AND '$todayEnd')) ";
                }
            }
            $filterParts[] = "
                (JSON_EXTRACT(cu.vehicle_details, '$.admin_verify') = false
                AND (
                    JSON_EXTRACT(cu.vehicle_details, '$.vehicle_review_message') IS NULL 
                    OR JSON_EXTRACT(cu.vehicle_details, '$.vehicle_review_message') NOT LIKE '%wait for admin approval%'
                )) AND cu.deletes = '0' ORDER BY kd.updated_at DESC
            ";
        } elseif ($u_type == 'v_v') {
            if (!empty($dateFilter)) {
                if (empty($startDate) || empty($endDate)) {
                    $result['type'] = 0;
                    $result['result'] = [];
                    $result['data'] = [];
                    goto resutGJHIPPP;
                }
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND (cu.v_updated_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'))";
                } else {
                    $filterParts[] = "((cu.v_updated_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'))";
                }
            } else {
                $todayStart = date('Y-m-d') . ' 00:00:00';
                $todayEnd = date('Y-m-d') . ' 23:59:59';
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND (cu.v_updated_at BETWEEN '$todayStart' AND '$todayEnd')) ";
                } else {
                    $filterParts[] = "((cu.v_updated_at BETWEEN '$todayStart' AND '$todayEnd')) ";
                }
            }
            $filterParts[] = "
                (
                    (
                        (cu.vehicle_verify = '2' AND kd.type = 'Driver') AND
                        JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.admin_verify')) = 'true' AND
                        JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.vehicle_review_message')) LIKE '%Vehicle details verified%'
                    )
                ) AND cu.deletes = '0' ORDER BY kd.updated_at DESC
            ";
        } elseif ($u_type == 'both_i') {
            if (!empty($dateFilter)) {
                if (empty($startDate) || empty($endDate)) {
                    $result['type'] = 0;
                    $result['result'] = [];
                    $result['data'] = [];
                    goto resutGJHIPPP;
                }
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND ((kd.update_date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (cu.v_updated_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' AND kd.update_date IS NULL)))";
                } else {
                    $filterParts[] = "((kd.update_date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (cu.v_updated_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' AND kd.update_date IS NULL))";
                }
            } else {
                $todayStart = date('Y-m-d') . ' 00:00:00';
                $todayEnd = date('Y-m-d') . ' 23:59:59';
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND ((kd.update_date BETWEEN '$todayStart' AND '$todayEnd') OR (cu.v_updated_at BETWEEN '$todayStart' AND '$todayEnd') OR (kd.created_at BETWEEN '$todayStart' AND '$todayEnd' AND kd.update_date IS NULL)) )";
                } else {
                    $filterParts[] = "((kd.update_date BETWEEN '$todayStart' AND '$todayEnd') OR (cu.v_updated_at BETWEEN '$todayStart' AND '$todayEnd') OR (kd.created_at BETWEEN '$todayStart' AND '$todayEnd' AND kd.update_date IS NULL)) ";
                }
            }
            $filterParts[] = "
                (
                    (
                        (cu.doc_verify = '1' AND cu.vehicle_verify = '2' AND kd.type = 'Driver') AND
                        JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.admin_verify')) = 'true' AND
                        JSON_UNQUOTE(JSON_EXTRACT(cu.vehicle_details, '$.vehicle_review_message')) LIKE '%Vehicle details verified%'
                    )
                ) AND cu.deletes = '0' ORDER BY kd.updated_at DESC
            ";
        } elseif ($u_type == 'both_c') {
            if (!empty($dateFilter)) {
                if (empty($startDate) || empty($endDate)) {
                    $result['type'] = 0;
                    $result['result'] = [];
                    $result['data'] = [];
                    goto resutGJHIPPP;
                }
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND ((kd.update_date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' AND kd.update_date IS NULL)))";
                } else {
                    $filterParts[] = "((kd.update_date BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59') OR (kd.created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' AND kd.update_date IS NULL))";
                }
            } else {
                $todayStart = date('Y-m-d') . ' 00:00:00';
                $todayEnd = date('Y-m-d') . ' 23:59:59';
                if ($own_type != 'all') {
                    $filterParts[] = "(kd.type = '$own_type' AND ((kd.update_date BETWEEN '$todayStart' AND '$todayEnd') OR (kd.created_at BETWEEN '$todayStart' AND '$todayEnd' AND kd.update_date IS NULL))) ";
                } else {
                    $filterParts[] = "((kd.update_date BETWEEN '$todayStart' AND '$todayEnd') OR (kd.created_at BETWEEN '$todayStart' AND '$todayEnd' AND kd.update_date IS NULL)) ";
                }
            }
            $filterParts[] = "
                (
                    cu.doc_verify = '1' AND kd.o_proof_status = 'approved' AND kd.type = 'Owner'
                ) AND cu.deletes = '0' ORDER BY kd.updated_at DESC
            ";
        }
        // Build WHERE clause
        $whereClause = '';
        if (!empty($filterParts)) {
            $whereClause = 'WHERE ' . implode(' AND ', $filterParts);
        }
        $s_query = "
            SELECT 
                cu.name,
                cu.mobile,
                cu.doc_verify,
                cu.vehicle_verify,
                kd.*
            FROM user_register cu
            LEFT JOIN kyc_details kd 
                ON kd.user_id = cu.id AND kd.deletes = 0
            $whereClause
        ";
        // var_dump($s_query);
        //die;
        $result_data = mysqli_query($con, $s_query);
        $result = [];
        $result["type"] = '0';
        if (mysqli_num_rows($result_data) > 0) {
            while ($row = mysqli_fetch_assoc($result_data)) {
                $result['result'][] = $row;
            }
            $result["type"] = '1';
            goto resutGJHIPPP;
        } else {
            $result["type"] = '0';
            $result['result'] = [];
        }
    } catch (\Exception $e) {
        $result["result"] = [];
        $result["type"] = '0';
    }
    resutGJHIPPP:
    echo json_encode($result);
} elseif ($method == 'reportBlock') {
    try {
        $user_id = $_POST['user_id'] ?? '';
        $id = $_POST['id'] ?? '';
        $st = $_POST['status'] ?? '';
        if (empty($id) || empty($user_id)) {
            $result["type"] = '0';
            $result["result"] = "Invalid request parameters";
            echo json_encode($result);
            exit;
        }
        if ($st != 'block' && $st != 'unblock') {
            $result["type"] = '0';
            $result["result"] = "Invalid request parameters";
            echo json_encode($result);
            exit;
        }
        // Get complaint details (use intval to avoid SQL injection)
        // $id = intval($id);
        $get_sql = "SELECT * FROM complain_user WHERE id = $id AND (status = 'pending' OR status = 'blocked') LIMIT 1";
        $get_res = mysqli_query($con, $get_sql);
        $complain = mysqli_fetch_assoc($get_res);
        // var_dump($complain);die;
        if ($complain) {
            // Call external API
            $apiURL = "https://www.goride.net.in/api/admin-block-user";
            $headers = [
                'Content-Type: application/x-www-form-urlencoded'
            ];
            $postData = [
                'id' => $id,
                'status' => $st,
                'user_id' => $user_id,
                'auth_key' => 'ASDFGHJKLqwertyuiopMNBVCXZ!@#$%^&*()0987612345'
            ];
            // var_dump($postData);die;
            $apiResponse = requestAPI('POST', $apiURL, $headers, $postData);
            $decoded = json_decode($apiResponse, true);
            if (!empty($decoded) && isset($decoded['status']) && $decoded['status'] == true) {
                $result["type"] = '1';
                $result["result"] = $decoded['message'] ?? 'User blocked successfully';
            } else {
                $result["type"] = '0';
                $result["result"] = $decoded['message'];
            }
        } else {
            $result["type"] = '0';
            $result["result"] = "Complaint not found or already processed";
        }
    } catch (Exception $e) {
        $result["type"] = '0';
        $result["result"] = $e->getMessage();
    }
    echo json_encode($result);
} elseif ($method == 'kycUserDetails') {
    try {
        $user_id = $_POST['user_id'] ?? '';
        $id = $_POST['id'] ?? '';
        $txt = $_POST['txt'] ?? '';
        if (empty($id) || empty($user_id)) {
            $result["type"] = '0';
            $result["result"] = "Invalid request parameters";
            echo json_encode($result);
            exit;
        }
        // if($st != 'block' && $st != 'unblock'){
        //     $result["type"] = '0';
        //     $result["result"] = "Invalid request parameters";
        //     echo json_encode($result);
        //     exit;
        // }
        if ($txt == 'DRIVING_LICENSE' || $txt == 'NAME_BOARD' || $txt == 'GST') {
            if ($txt == 'NAME_BOARD' || $txt == 'GST') {
                $get_sql = "SELECT id, type, user_id FROM kyc_details WHERE id = ? ORDER BY id DESC LIMIT 1";
                $stmt2 = $con->prepare($get_sql);
                $stmt2->bind_param("i", $id);
                $stmt2->execute();
                $res = $stmt2->get_result();
                $data = $res->fetch_assoc();
                if ($data) {
                    if ($data['type'] == 'Driver') {
                        $result["type"] = '0';
                        $result["result"] = 'Driver Role Not Suitable';
                        echo json_encode($result);
                        exit;
                    }
                }
                $get_sql = "
                    SELECT 
                        cu.name,
                        cu.mobile,
                        cu.doc_verify,
                        cu.company_name,
                        kd.*
                    FROM kyc_details kd
                    JOIN user_register cu ON kd.user_id = cu.id
                    LEFT JOIN ocr_request cr ON cr.user_id = cu.id
                    WHERE kd.id = $id 
                      AND kd.user_id = $user_id 
                      AND cu.deletes = '0'
                    ORDER BY cr.id DESC
                    LIMIT 1
                ";
            } elseif ($txt == 'DRIVING_LICENSE') {
                $get_sql = "SELECT id, type, user_id FROM kyc_details WHERE id = ? ORDER BY id DESC LIMIT 1";
                $stmt3 = $con->prepare($get_sql);
                $stmt3->bind_param("i", $id);
                $stmt3->execute();
                $res = $stmt3->get_result();
                $data = $res->fetch_assoc();
                if ($data) {
                    if ($data['type'] == 'Owner') {
                        $result["type"] = '0';
                        $result["result"] = 'Owner Role Not Suitable';
                        echo json_encode($result);
                        exit;
                    }
                }
                $get_sql = "
                    SELECT 
                        cu.name,
                        cu.mobile,
                        cu.doc_verify,
                        cr.doc_type,
                        cr.doc_no,
                        cr.doc_expiry,
                        cr.exp,
                        cr.status,
                        cr.request_id,
                        cr.req_response,
                        cr.front,
                        cr.back,
                        kd.*
                    FROM kyc_details kd
                    JOIN user_register cu ON kd.user_id = cu.id
                    LEFT JOIN ocr_request cr ON cr.user_id = cu.id
                    WHERE kd.id = $id 
                      AND kd.user_id = $user_id 
                      AND cu.deletes = '0'
                      AND cr.doc_type = 'DRIVING_LICENSE'
                    ORDER BY cr.id DESC
                    LIMIT 1
                ";
            }
        } elseif ($txt == 'VEHICLE') {
            $get_sql = "
                SELECT 
                    *
                FROM user_register cu
                WHERE cu.id = $user_id AND deletes = '0'
                ORDER BY cu.id DESC
                LIMIT 1
            ";
        } elseif ($txt == 'USERVERIFY' || $txt == 'AADHAR') {
            $get_sql = "
                SELECT 
                    cu.name,
                    cu.mobile,
                    cu.doc_verify,
                    cu.address,
                    kd.*
                FROM kyc_details kd
                JOIN user_register cu ON kd.user_id = cu.id
                WHERE kd.id = $id 
                  AND kd.user_id = $user_id 
                  AND cu.deletes = '0'
                ORDER BY kd.id DESC
                LIMIT 1
            ";
        } elseif ($txt == 'VEHICLEVERIFY') {
            $get_sql = "
                SELECT 
                    cu.id AS user_id,
                    cu.name,
                    cu.mobile,
                    cu.vehicle_details
                FROM user_register cu
                WHERE cu.id = $user_id
                  AND cu.deletes = '0'
                LIMIT 1
            ";
            $result = mysqli_query($con, $get_sql);
            $row = mysqli_fetch_assoc($result);
            if ($row && $row['vehicle_details']) {
                $vehicleData = json_decode($row['vehicle_details'], true);
                $rcDetails = $vehicleData['rc_details']['response']['vehicle_details'] ?? [];
                $finance = $vehicleData['rc_details']['response']['finance_details'] ?? [];
                $owner = $vehicleData['rc_details']['response']['user_details'] ?? [];
                $permit = $vehicleData['rc_details']['response']['permit_details'] ?? [];
                $puc = $vehicleData['puc_details'] ?? [];
                $insurance = $vehicleData['insurance_details'] ?? [];
                $fc = $vehicleData['fc_details'] ?? [];
                $response = [
                    "status" => "success",
                    "data" => [
                        "vehicle" => [
                            "owner_name" => $owner['owner_name'] ?? '',
                            "rc_number" => $rcDetails['rc_number'] ?? '',
                            "maker_model" => $rcDetails['maker_model'] ?? '',
                            "maker_description" => $rcDetails['maker_description'] ?? '',
                            "fuel_type" => $rcDetails['fuel_type'] ?? '',
                            "registration_date" => $rcDetails['registration_date'] ?? '',
                            "fit_up_to" => $rcDetails['fit_up_to'] ?? '',
                            "color" => $rcDetails['color'] ?? '',
                            "seat_capacity" => $rcDetails['seat_capacity'] ?? '',
                            "vehicle_engine_number" => $rcDetails['vehicle_engine_number'] ?? '',
                            "vehicle_chassis_number" => $rcDetails['vehicle_chassis_number'] ?? '',
                            "registered_at" => $rcDetails['registered_at'] ?? '',
                            "ownership_type" => $vehicleData['vehicle_ownership'] ?? '',
                            "review_message" => $vehicleData['vehicle_review_message'] ?? ''
                        ],
                        "puc" => [
                            "puc_status" => $puc['puc_status'] ?? '',
                            "puc_exp_date" => $puc['puc_exp_date'] ?? '',
                            "puc_image_url" => $puc['puc_image_url'] ?? '',
                            "puc_admin_status" => $puc['puc_admin_status'] ?? ''
                        ],
                        "insurance" => [
                            "insurance_status" => $insurance['insurance_status'] ?? '',
                            "insurance_exp_date" => $insurance['insurance_exp_date'] ?? '',
                            "insurance_image_url" => $insurance['insurance_image_url'] ?? '',
                            "insurance_admin_status" => $insurance['insurance_admin_status'] ?? ''
                        ],
                        "fc" => [
                            "fc_status" => $fc['fc_status'] ?? '',
                            "fc_exp_date" => $fc['fc_exp_date'] ?? '',
                            "fc_image_url" => $fc['fc_image_url'] ?? '',
                            "fc_admin_status" => $fc['fc_admin_status'] ?? ''
                        ]
                    ]
                ];
                echo json_encode($response);
            } else {
                echo json_encode(["status" => "error", "message" => "No vehicle details found"]);
            }
            exit;
        }
        $get_res = mysqli_query($con, $get_sql);
        $complain = mysqli_fetch_assoc($get_res);
        // var_dump($get_sql);die;
        if ($complain) {
            $result["type"] = '1';
            $result["result"] = $complain;
        } else {
            $result["type"] = '0';
            $result["result"] = "KYC not found or already processed";
        }
    } catch (Exception $e) {
        $result["type"] = '0';
        $result["result"] = $e->getMessage();
    }
    echo json_encode($result);
} elseif ($method == 'kycFetchDoc') {
    try {
        $id = $_POST['id'] ?? '';
        $txt = $_POST['txt'] ?? '';
        $status = $_POST['status'] ?? '';
        $result = [];
        // Validate params
        if (empty($id) || empty($txt)) {
            $result["type"] = '0';
            $result["result"] = "Invalid request parameters";
            echo json_encode($result);
            exit;
        }
        if ($txt == 'dl' || $txt == 'selfie') {
            $col = ($txt == 'dl') ? 'dl_status' : 'selfie_status';
            $title = ($txt == 'dl') ? 'Driving Licence' : 'Selfie';
            $get_sql = "SELECT id, $col AS status_field, user_id FROM kyc_details WHERE id = ? ORDER BY id DESC LIMIT 1";
            $stmt = $con->prepare($get_sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $data = $res->fetch_assoc();
            if ($data) {
                $new_status = ($status == 1) ? 'approved' : 'rejected';
                $r_status = ($status == 1) ? 'Verified' : 'Not Verified';
                $u_status = ($status == 1) ? 1 : 0;
                $update_sql = "UPDATE kyc_details SET $col = ? WHERE id = ?";
                $update_stmt = $con->prepare($update_sql);
                $update_stmt->bind_param("si", $new_status, $data['id']);
                $update_stmt->execute();
                $result["type"] = '1';
                $result["result"] = $title . " " . $r_status;
            } else {
                $result["type"] = '0';
                $result["result"] = "Record Not Found";
            }
            $stmt->close();
        } elseif ($txt == 'doc_verify') {
            $col = 'doc_verify';
            $title = ($txt == 'doc_verify') ? 'Document ' : 'Document ';
            $get_sql = "SELECT id, $col FROM user_register WHERE id = ? ORDER BY id DESC LIMIT 1";
            $stmt = $con->prepare($get_sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $data = $res->fetch_assoc();
            if ($data) {
                $get_sql2 = "SELECT * FROM kyc_details WHERE user_id = ? AND  (
                                (type = 'driver' AND dl_status = 'approved')
                                OR
                                (type != 'driver' AND o_proof_status = 'approved')
                            ) AND (proof_status = 'approved' OR proof_status = 'approval_pending') AND selfie_status = 'approved' ORDER BY id DESC LIMIT 1";
                $stmt2 = $con->prepare($get_sql2);
                $stmt2->bind_param("i", $id);
                $stmt2->execute();
                $res2 = $stmt2->get_result();
                $data2 = $res2->fetch_assoc();
                if (!$data2) {
                    $result["type"] = '0';
                    $result["result"] = "Kyc Pending.";
                    goto POIUYTRGH;
                }
                $new_status = ($status == 1) ? '1' : '0';
                $r_status = ($status == 1) ? 'Verified' : 'Not Verified';
                $u_status = ($status == 1) ? 1 : 0;
                $update_sql = "UPDATE user_register SET $col = ? WHERE id = ?";
                $update_stmt = $con->prepare($update_sql);
                $update_stmt->bind_param("si", $new_status, $data['id']);
                $update_stmt->execute();
                $result["type"] = '1';
                $result["result"] = $title . " " . $r_status;
            } else {
                $result["type"] = '0';
                $result["result"] = "Record Not Found or Kyc Pending";
            }
            $stmt->close();
        } elseif ($txt == 'vehicle') {
            // Determine column names dynamically
            $col = 'vehicle_verify';
            $title = ($txt == 'vehicle') ? 'Vehicle ' : 'Vehicle ';
            // Fetch latest record
            $get_sql = "SELECT id, $col FROM user_register WHERE id = ? ORDER BY id DESC LIMIT 1";
            $stmt = $con->prepare($get_sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $data = $res->fetch_assoc();
            if ($data) {
                $get_sql2 = "SELECT * FROM user_register WHERE id = ? AND vehicle_verify IS NOT NULL ORDER BY id DESC LIMIT 1";
                $stmt2 = $con->prepare($get_sql2);
                $stmt2->bind_param("i", $id);
                $stmt2->execute();
                $res2 = $stmt2->get_result();
                $data2 = $res2->fetch_assoc();
                if (!$data2) {
                    $result["type"] = '0';
                    $result["result"] = "Vehicles Not Available.";
                    goto POIUYTRGH;
                }
                // Determine new status
                $new_status = ($status == 1) ? '1' : '0';
                $r_status = ($status == 1) ? 'Verified' : 'Not Verified';
                $u_status = ($status == 1) ? 1 : 0;
                // Update kyc_details
                $update_sql = "UPDATE user_register SET $col = ? WHERE id = ?";
                $update_stmt = $con->prepare($update_sql);
                $update_stmt->bind_param("si", $new_status, $data['id']);
                $update_stmt->execute();
                $result["type"] = '1';
                $result["result"] = $title . " " . $r_status;
            } else {
                $result["type"] = '0';
                $result["result"] = "Record Not Found or vehicles pending not available";
            }
            $stmt->close();
        } elseif ($txt == 'gst' || $txt == 'name_board') {
            // Determine column names dynamically
            $col = 'o_proof_status';
            $title = $txt == 'gst' ? 'GST' : 'Name Board';
            // Fetch latest record
            $get_sql = "SELECT id, $col AS status_field, user_id FROM kyc_details WHERE id = ? ORDER BY id DESC LIMIT 1";
            $stmt = $con->prepare($get_sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $data = $res->fetch_assoc();
            if ($data) {
                // Determine new status
                $new_status = ($status == 1) ? 'approved' : 'rejected';
                $r_status = ($status == 1) ? 'Verified' : 'Not Verified';
                $u_status = ($status == 1) ? 1 : 0;
                // Update kyc_details
                $update_sql = "UPDATE kyc_details SET $col = ? WHERE id = ?";
                $update_stmt = $con->prepare($update_sql);
                $update_stmt->bind_param("si", $new_status, $data['id']);
                $update_stmt->execute();
                // Optional: Update user_register (uncomment if needed)
                /*
                $user_update = "UPDATE user_register SET doc_verify = ? WHERE id = ?";
                $user_stmt = $con->prepare($user_update);
                $user_stmt->bind_param("ii", $u_status, $data['user_id']);
                $user_stmt->execute();
                $user_stmt->close();
                */
                $result["type"] = '1';
                $result["result"] = $title . " " . $r_status;
            } else {
                $result["type"] = '0';
                $result["result"] = "Record Not Found";
            }
            $stmt->close();
        } else {
            $result["type"] = '0';
            $result["result"] = "Invalid verification type";
        }
    } catch (Exception $e) {
        $result["type"] = '0';
        $result["result"] = $e->getMessage();
    }
    POIUYTRGH:
    echo json_encode($result);
} elseif ($method == 'kycApprove') {
    try {
        $id = $_POST['kyc_id'] ?? '';
        $selfie_status = $_POST['selfie_status'] ?? '';
        $aadhar_status = $_POST['aadhar_status'] ?? '';
        $dl_status = $_POST['dl_status'] ?? '';
        $gst_status = $_POST['gst_status'] ?? '';
        $send_whatsapp = $_POST['send_whatsapp'] ?? '';
        $result = [];
        $up_status = 3;
        $fetch_kyc = select_query($rcon, "kyc_details", "", "id = '$id' AND `deletes`='0'", "", "");
        if ($fetch_kyc['nr'] > 0) {
            $k_type = $fetch_kyc['result'][0]['type'];
            $user_id = $fetch_kyc['result'][0]['user_id'];
            $o_proof_type = $fetch_kyc['result'][0]['o_proof_type'];
            $fetch_user = select_query($rcon, "settings", "template_type", "", "", "");
            $template_type = $fetch_user['result'][0]['template_type'];
            if ($k_type == 'Driver') {
                $up_status = $dl_status != 'approved' ? 2 : $up_status;
                $up_status = $aadhar_status != 'approved' ? 1 : $up_status;
                $up_status = $selfie_status != 'approved' ? 0 : $up_status;
                $update_kyc = "
                    UPDATE kyc_details 
                    SET 
                        `selfie_status` = '$selfie_status',
                        `proof_status`  = '$aadhar_status',
                        `dl_status`     = '$dl_status',
                        `updated_by` = '$memid',
                        `update_date` = now(),
                        `o_status` = '$up_status'
                    WHERE id = '$id' AND `deletes` = '0'
                ";
                mysqli_query($rcon, $update_kyc);
                $fetch_user = select_query($rcon, "user_register", "", "id = '$user_id' AND `deletes`='0'", "", "");
                $name = $fetch_user['result'][0]['name'];
                $mobile = $fetch_user['result'][0]['mobile'];
                if ($selfie_status == 'approved' && $aadhar_status == 'approved' && $dl_status == 'approved') {
                    $update_user = "
                        UPDATE user_register 
                        SET `doc_verify` = '1' 
                        WHERE id = '$user_id' AND `deletes` = '0'
                    ";
                    $dl_status = $aadhar_status = $selfie_status = 'Verified';
                    $what_mess = '🎉 *KYC Verification Completed!* 🎉
                    
Hi ' . $name . ', your KYC is now fully verified.
✅ Selfie verified
✅ Aadhar verified
✅ DL verified
Your GoRide account is active for *posting jobs* and ready to use.

Next, *add your vehicle details* to start placing bids and ride with GoRide.

🚖 *GoRide — Drive Safe. Earn Smart. Ride Proud.*';
                    if ($send_whatsapp == 'yes') {
                        if ($template_type == 'whatsapp') {
                            $what_mess = mysqli_real_escape_string($con, $what_mess);
                            $name = mysqli_real_escape_string($con, $name);
                            $mobile = mysqli_real_escape_string($con, $mobile);
                            $ins_whatsapp = "
                                INSERT INTO whatsapp_bulk_message (`details`, `name`, `to_whatsapp`, `status`, `created_at`, `updated_at`)
                                VALUES ('$what_mess', '$name', '$mobile', 'pending', NOW(), NOW())
                            ";
                            mysqli_query($con, $ins_whatsapp);
                            $apiUrl = API_DOMAIN_2 . 'send-push-notify';
                            $headers = [
                                'Content-Type: application/json'
                            ];
                            $message_content = "Hi $name, your GoRide profile is approved. Add vehicle details to continue.";
                            $payload = [
                                'method' => $template_type,
                                'user_id' => $user_id,
                                'name' => $name,
                                'mobile' => $mobile,
                                'title' => 'KYC Verified',
                                'action' => 'kyc_verified',
                                'message' => $message_content
                            ];
                            $response = requestAPI(
                                'POST',
                                $apiUrl,
                                $headers,
                                json_encode($payload)
                            );
                            $apiResponse = json_decode($response, true);
                        } else {
                            $apiUrl = API_DOMAIN_2 . 'send-template';
                            $headers = [
                                'Content-Type: application/json'
                            ];
                            $message_content = "KYC Verified. Hi $name, your GoRide profile is approved. Add vehicle details to continue.";
                            $payload = [
                                'method' => $template_type,
                                'user_id' => $user_id,
                                'name' => $name,
                                'mobile' => $mobile,
                                'title' => 'KYC Verified',
                                'action' => 'kyc_verified',
                                'message' => $message_content
                            ];
                            $response = requestAPI(
                                'POST',
                                $apiUrl,
                                $headers,
                                json_encode($payload)
                            );
                            $apiResponse = json_decode($response, true);
                            // var_dump($apiResponse);die;
                            // if (isset($apiResponse['error'])) {
                            //     $result = [
                            //         'type'   => '0',
                            //         'result' => $apiResponse['error']
                            //     ];
                            // } else {
                            //     $result = [
                            //         'type'   => '1',
                            //         'result' => $apiResponse
                            //     ];
                            // }
                        }
                    }
                    mysqli_query($rcon, $update_user);
                } else {
                    $what_mess = '⚠️ *KYC Verification Pending!* ⚠️
Hi ' . $name . ', your KYC is pending.';
                    if ($selfie_status != 'approved') {
                        $what_mess .= "\n\n❌ *Selfie*: Pending";
                    } else {
                        $what_mess .= "\n\n✅ Selfie verified";
                    }
                    if ($aadhar_status != 'approved') {
                        $what_mess .= "\n\n❌ *Aadhar*: Pending";
                    } else {
                        $what_mess .= "\n\n✅ Aadhar verified";
                    }
                    if ($dl_status != 'approved') {
                        $what_mess .= "\n\n❌ *DL*: Pending";
                    } else {
                        $what_mess .= "\n\n✅ DL verified";
                    }
                    $what_mess .= "\n\nKindly Upload your pending documents
🚖 *GoRide — Drive Safe. Earn Smart. Ride Proud.*";
                    if ($send_whatsapp == 'yes') {
                        // $what_mess = mysqli_real_escape_string($con, $what_mess;
                        $name = mysqli_real_escape_string($con, $name);
                        $mobile = mysqli_real_escape_string($con, $mobile);
                        $ins_whatsapp = "
                            INSERT INTO whatsapp_bulk_message (`details`, `name`, `to_whatsapp`, `status`, `created_at`, `updated_at`)
                            VALUES ('$what_mess', '$name', '$mobile', 'pending', NOW(), NOW())
                        ";
                        mysqli_query($con, $ins_whatsapp);
                        $apiUrl = API_DOMAIN_2 . 'send-push-notify';
                        $headers = [
                            'Content-Type: application/json'
                        ];
                        $message_content = "Hi $name, your GoRide profile is pending. Please complete the KYC process.";
                        $payload = [
                            'method' => $template_type,
                            'user_id' => $user_id,
                            'name' => $name,
                            'mobile' => $mobile,
                            'title' => 'KYC Pending',
                            'action' => 'kyc_pending',
                            'message' => $message_content
                        ];
                        $response = requestAPI(
                            'POST',
                            $apiUrl,
                            $headers,
                            json_encode($payload)
                        );
                        $apiResponse = json_decode($response, true);
                    }
                    // mysqli_query($rcon, $update_user);
                }
                $result["type"] = '1';
                $result["result"] = "Driver Kyc Verification updated";
            } elseif ($k_type == 'Owner') {
                $up_status = $gst_status != 'approved' ? 2 : $up_status;
                $up_status = $aadhar_status != 'approved' ? 1 : $up_status;
                $up_status = $selfie_status != 'approved' ? 0 : $up_status;
                $update_kyc = "
                    UPDATE kyc_details 
                    SET 
                        `selfie_status` = '$selfie_status',
                        `proof_status`  = '$aadhar_status',
                        `o_proof_status` = '$gst_status',
                        `updated_by` = '$memid',
                        `update_date` = now(),
                        `o_status` = '$up_status'
                    WHERE id = '$id' AND `deletes` = '0'
                ";
                mysqli_query($rcon, $update_kyc);
                $fetch_user = select_query($rcon, "user_register", "", "id = '$user_id' AND `deletes`='0'", "", "");
                $name = $fetch_user['result'][0]['name'];
                $mobile = $fetch_user['result'][0]['mobile'];
                if ($selfie_status == 'approved' && $aadhar_status == 'approved' && $gst_status == 'approved') {
                    $update_user = "
                        UPDATE user_register 
                        SET `doc_verify` = '1' 
                        WHERE id = '$user_id' AND `deletes` = '0'
                    ";
                    if ($o_proof_type == 'gst') {
                        $gst_name = 'GST';
                    } else {
                        $gst_name = 'Name Board';
                    }
                    $what_mess = '🎉 *KYC Verification Completed!* 🎉
                    
Hi ' . $name . ', your KYC is now fully verified.
✅ Selfie verified
✅ Aadhar verified
✅ ' . $gst_name . ' verified

Your GoRide account is active for *posting jobs* and ready to use.

Next, *set up your CRM* to manage your vehicles and bookings efficiently.

🚖 *GoRide — Drive Safe. Earn Smart. Ride Proud.*';
                    mysqli_query($rcon, $update_user);
                    if ($send_whatsapp == 'yes') {
                        if ($template_type == 'whatsapp') {
                            $what_mess = mysqli_real_escape_string($con, $what_mess);
                            $name = mysqli_real_escape_string($con, $name);
                            $mobile = mysqli_real_escape_string($con, $mobile);
                            $ins_whatsapp = "
                                INSERT INTO whatsapp_bulk_message (`details`, `name`, `to_whatsapp`, `status`, `created_at`, `updated_at`)
                                VALUES ('$what_mess', '$name', '$mobile', 'pending', NOW(), NOW())
                            ";
                            mysqli_query($con, $ins_whatsapp);
                            $apiUrl = API_DOMAIN_2 . 'send-push-notify';
                            $headers = [
                                'Content-Type: application/json'
                            ];
                            $message_content = "Hi $name, your GoRide profile is approved. Add vehicle details to continue.";
                            $payload = [
                                'method' => $template_type,
                                'user_id' => $user_id,
                                'name' => $name,
                                'mobile' => $mobile,
                                'title' => 'KYC Verified',
                                'action' => 'kyc_verified',
                                'message' => $message_content
                            ];
                            $response = requestAPI(
                                'POST',
                                $apiUrl,
                                $headers,
                                json_encode($payload)
                            );
                            $apiResponse = json_decode($response, true);
                        } else {
                            $apiUrl = API_DOMAIN_2 . 'send-template';
                            $headers = [
                                'Content-Type: application/json'
                            ];
                            $message_content = "Hi $name, your GoRide profile is approved. Add vehicle details to continue.";
                            $payload = [
                                'method' => $template_type,
                                'user_id' => $user_id,
                                'name' => $name,
                                'mobile' => $mobile,
                                'title' => 'KYC Verified',
                                'action' => 'kyc_verified',
                                'message' => $message_content
                            ];
                            $response = requestAPI(
                                'POST',
                                $apiUrl,
                                $headers,
                                json_encode($payload)
                            );
                            $apiResponse = json_decode($response, true);
                        }
                    }
                } else {
                    if ($o_proof_type == 'gst') {
                        $gst_name = 'GST';
                    } else {
                        $gst_name = 'Name Board';
                    }
                    $what_mess = '⚠️ *KYC Verification Pending!* ⚠️
Hi ' . $name . ', your KYC is pending.';
                    if ($selfie_status != 'approved') {
                        $what_mess .= "\n❌ *Selfie*: Pending";
                    } else {
                        $what_mess .= "\n✅ Selfie verified";
                    }
                    if ($aadhar_status != 'approved') {
                        $what_mess .= "\n❌ *Aadhar*: Pending";
                    } else {
                        $what_mess .= "\n✅ Aadhar verified";
                    }
                    if ($gst_status != 'approved') {
                        $what_mess .= "\n❌ *$gst_name*: Pending";
                    } else {
                        $what_mess .= "\n✅ $gst_name verified";
                    }
                    $what_mess .= "\nYour GoRide account is active for *posting jobs* and ready to use.
Next, *add your vehicle details* to start placing bids and ride with GoRide.
🚖 *GoRide — Drive Safe. Earn Smart. Ride Proud.*";
                    if ($send_whatsapp == 'yes') {
                        // $what_mess = mysqli_real_escape_string($con, $what_mess;
                        $name = mysqli_real_escape_string($con, $name);
                        $mobile = mysqli_real_escape_string($con, $mobile);
                        $ins_whatsapp = "
                            INSERT INTO whatsapp_bulk_message (`details`, `name`, `to_whatsapp`, `status`, `created_at`, `updated_at`)
                            VALUES ('$what_mess', '$name', '$mobile', 'pending', NOW(), NOW())
                        ";
                        mysqli_query($con, $ins_whatsapp);
                        $apiUrl = API_DOMAIN_2 . 'send-push-notify';
                        $headers = [
                            'Content-Type: application/json'
                        ];
                        $message_content = "Hi $name, your GoRide profile is pending. Please complete the KYC process.";
                        $payload = [
                            'method' => $template_type,
                            'user_id' => $user_id,
                            'name' => $name,
                            'mobile' => $mobile,
                            'title' => 'KYC Pending',
                            'action' => 'kyc_pending',
                            'message' => $message_content
                        ];
                        $response = requestAPI(
                            'POST',
                            $apiUrl,
                            $headers,
                            json_encode($payload)
                        );
                        $apiResponse = json_decode($response, true);
                    }
                    // mysqli_query($rcon, $update_user);
                }
                $result["type"] = '1';
                $result["result"] = "Owner Kyc Verification updated";
            }
            mysqli_query(
                $con,
                "UPDATE notifications SET read_at = NOW() 
                 WHERE actor_id = $user_id AND type = 'kyc.updated'"
            );
        } else {
            $result["type"] = '0';
            $result["result"] = "Record not found";
        }
    } catch (Exception $e) {
        $result["type"] = '0';
        $result["result"] = $e->getMessage();
    }
    // POIUYTRGHR:
    echo json_encode($result);
} elseif ($method == 'kycReject') {
    try {
        $id = $_POST['kyc_id'] ?? '';
        $selfie_status = $_POST['selfie_status'] ?? '';
        $aadhar_status = $_POST['aadhar_status'] ?? '';
        $dl_status = $_POST['dl_status'] ?? '';
        $gst_status = $_POST['gst_status'] ?? '';
        $send_whatsapp = $_POST['send_whatsapp'] ?? '';
        $remark = $_POST['remark'] ?? '';
        $result = [];
        $fetch_kyc = select_query($rcon, "kyc_details", "", "id = '$id' AND `deletes`='0'", "", "");
        if ($fetch_kyc['nr'] > 0) {
            $k_type = $fetch_kyc['result'][0]['type'];
            $user_id = $fetch_kyc['result'][0]['user_id'];
            $o_proof_type = $fetch_kyc['result'][0]['o_proof_type'];
            $o_proof_type = $fetch_kyc['result'][0]['o_proof_type'];
            $s_image = null;
            $a_image = null;
            $dl_image = null;
            if ($k_type == 'Driver') {
                // Get KYC details
                $get_kyc_sql = "
                    SELECT selfie_url, front_image, o_proof, user_id
                    FROM kyc_details
                    WHERE id = '$id' AND deletes = '0'
                ";
                $get_kyc_res = mysqli_query($con, $get_kyc_sql);
                $get_kyc = mysqli_fetch_assoc($get_kyc_res);
                $user_id = $get_kyc['user_id'];
                // Get OCR details
                $get_ocr_sql = "
                    SELECT front, doc_type
                    FROM ocr_request
                    WHERE user_id = '$user_id'
                    AND doc_type = 'DRIVING_LICENSE'
                    ORDER BY id DESC
                    LIMIT 1
                ";
                $get_ocr_res = mysqli_query($con, $get_ocr_sql);
                $get_ocr = mysqli_fetch_assoc($get_ocr_res);
                $ocr_doc = $get_ocr['doc_type'] ?? null;
                if ($dl_status == 'rejected') {
                    $update_kyc = "
                        UPDATE kyc_details 
                        SET 
                            `dl_status`     = '$dl_status',
                            `reject_reason` = '$remark',
                            `updated_by` = '$memid',
                            `update_date` = now(),
                            `o_status` = 2
                        WHERE id = '$id' AND `deletes` = '0'
                    ";
                    mysqli_query($con, $update_kyc);
                    $dl_image = $get_kyc['o_proof'];
                }
                if ($aadhar_status == 'rejected') {
                    $update_kyc = "
                        UPDATE kyc_details 
                        SET 
                            `proof_status`  = '$aadhar_status',
                            `reject_reason` = '$remark',
                            `updated_by` = '$memid',
                            `update_date` = now(),
                            `o_status` = 1
                        WHERE id = '$id' AND `deletes` = '0'
                    ";
                    mysqli_query($con, $update_kyc);
                    $a_image = $get_kyc['front_image'];
                }
                if ($selfie_status == 'rejected') {
                    $update_kyc = "
                        UPDATE kyc_details 
                        SET 
                            `selfie_status` = '$selfie_status',
                            `reject_reason` = '$remark',
                            `updated_by` = '$memid',
                            `update_date` = now(),
                            `o_status` = 0
                        WHERE id = '$id' AND `deletes` = '0'
                    ";
                    mysqli_query($con, $update_kyc);
                    $s_image = $get_kyc['selfie_url'];
                }
                $fetch_user = select_query($rcon, "user_register", "", "id = '$user_id' AND `deletes`='0'", "", "");
                $name = $fetch_user['result'][0]['name'];
                $mobile = $fetch_user['result'][0]['mobile'];
                $update_user = "
                        UPDATE user_register 
                        SET `doc_verify` = '0' 
                        WHERE id = '$user_id' AND `deletes` = '0'
                    ";
                $what_mess = '⚠️ *KYC Verification Declined* ⚠️
                
Hi ' . $name . ', we found some issues with your submitted documents.

*Reason*: ' . ucfirst($remark) . '

🚖 *GoRide — Drive Smart. Stay Verified. Earn Confidently.*';
                if ($send_whatsapp == 'yes') {
                    $what_mess = mysqli_real_escape_string($con, $what_mess);
                    $name = mysqli_real_escape_string($con, $name);
                    $mobile = mysqli_real_escape_string($con, $mobile);
                    if ($s_image) {
                        $ins_whatsapp = "
                                INSERT INTO whatsapp_bulk_message (`images`, `name`, `to_whatsapp`, `status`, `created_at`, `updated_at`)
                                VALUES ('$s_image', '$name', '$mobile', 'pending', NOW(), NOW())
                            ";
                        mysqli_query($con, $ins_whatsapp);
                    }
                    if ($a_image) {
                        $ins_whatsapp = "
                                INSERT INTO whatsapp_bulk_message (`images`, `name`, `to_whatsapp`, `status`, `created_at`, `updated_at`)
                                VALUES ('$a_image', '$name', '$mobile', 'pending', NOW(), NOW())
                            ";
                        mysqli_query($con, $ins_whatsapp);
                    }
                    if ($dl_image) {
                        $ins_whatsapp = "
                                INSERT INTO whatsapp_bulk_message (`images`, `name`, `to_whatsapp`, `status`, `created_at`, `updated_at`)
                                VALUES ('$dl_image', '$name', '$mobile', 'pending', NOW(), NOW())
                            ";
                        mysqli_query($con, $ins_whatsapp);
                    }
                    $ins_whatsapp = "
                            INSERT INTO whatsapp_bulk_message (`details`, `name`, `to_whatsapp`, `status`, `created_at`, `updated_at`)
                            VALUES ('$what_mess', '$name', '$mobile', 'pending', NOW(), NOW())
                        ";
                    mysqli_query($con, $ins_whatsapp);
                }
                mysqli_query($rcon, $update_user);
                $result["type"] = '1';
                $result["result"] = "Driver Kyc Verification updated";
            } elseif ($k_type == 'Owner') {
                if ($gst_status == 'rejected') {
                    $update_kyc = "
                        UPDATE kyc_details 
                        SET 
                            `o_proof_status`     = '$gst_status',
                            `reject_reason` = '$remark',
                            `updated_by` = '$memid',
                            `update_date` = now(),
                            `o_status` = 2
                        WHERE id = '$id' AND `deletes` = '0'
                    ";
                    mysqli_query($con, $update_kyc);
                }
                if ($aadhar_status == 'rejected') {
                    $update_kyc = "
                        UPDATE kyc_details 
                        SET 
                            `proof_status`  = '$aadhar_status',
                            `reject_reason` = '$remark',
                            `updated_by` = '$memid',
                            `update_date` = now(),
                            `o_status` = 1
                        WHERE id = '$id' AND `deletes` = '0'
                    ";
                    mysqli_query($con, $update_kyc);
                }
                if ($selfie_status == 'rejected') {
                    $update_kyc = "
                        UPDATE kyc_details 
                        SET 
                            `selfie_status` = '$selfie_status',
                            `reject_reason` = '$remark',
                            `updated_by` = '$memid',
                            `update_date` = now(),
                            `o_status` = 0
                        WHERE id = '$id' AND `deletes` = '0'
                    ";
                    mysqli_query($con, $update_kyc);
                }
                if ($o_proof_type == 'gst') {
                    $gst_name = 'GST';
                } else {
                    $gst_name = 'Name Board';
                }
                $fetch_user = select_query($rcon, "user_register", "", "id = '$user_id' AND `deletes`='0'", "", "");
                $name = $fetch_user['result'][0]['name'];
                $mobile = $fetch_user['result'][0]['mobile'];
                $update_user = "
                        UPDATE user_register 
                        SET `doc_verify` = '0' 
                        WHERE id = '$user_id' AND `deletes` = '0'
                    ";
                $what_mess = '⚠️ *KYC Verification Declined* ⚠️
Hi ' . $name . ', we found some issues with your submitted documents.  
Please open the GoRide app and re-upload the required details for verification.
';
                if ($selfie_status == 'rejected') {
                    $what_mess .= "\n❌ *Selfie*: Rejected";
                }
                if ($aadhar_status == 'rejected') {
                    $what_mess .= "\n❌ *Aadhar*: Rejected";
                }
                if ($gst_status == 'rejected') {
                    $what_mess .= "\n❌ *$gst_name*: Rejected";
                }
                $what_mess .= '
*Reason*: ' . ucfirst($remark) . '

Your GoRide account remains limited until verification is completed.  
Open the app to update your documents and complete your KYC.

🚖 *GoRide — Drive Smart. Stay Verified. Earn Confidently.*';
                if ($send_whatsapp == 'yes') {
                    $what_mess = mysqli_real_escape_string($con, $what_mess);
                    $name = mysqli_real_escape_string($con, $name);
                    $mobile = mysqli_real_escape_string($con, $mobile);
                    $ins_whatsapp = "
                            INSERT INTO whatsapp_bulk_message (`details`, `name`, `to_whatsapp`, `status`, `created_at`, `updated_at`)
                            VALUES ('$what_mess', '$name', '$mobile', 'pending', NOW(), NOW())
                        ";
                    mysqli_query($con, $ins_whatsapp);
                }
                mysqli_query($rcon, $update_user);
                $result["type"] = '1';
                $result["result"] = "Driver Kyc Verification updated";
            }
            mysqli_query(
                $con,
                "UPDATE notifications SET read_at = NOW() 
                 WHERE actor_id = $user_id AND type = 'kyc.updated'"
            );
        } else {
            $result["type"] = '0';
            $result["result"] = "Record not found";
        }
    } catch (Exception $e) {
        $result["type"] = '0';
        $result["result"] = $e->getMessage();
    }
    // POIUYTRGHR:
    echo json_encode($result);
} elseif ($method == 'vehicleApprove') {

    try {

        $id = $_POST['user_id'] ?? '';

        $send_whatsapp = $_POST['isWhatsapp'] ?? '';

        $fields = [

            'back_view_admin_status',

            'boot_admin_status',

            'car_top_view_admin_status',

            'extra_image_1_admin_status',

            'extra_image_2_admin_status',

            'front_view_admin_status',

            'interior_front_admin_status',

            'interior_rear_admin_status',

            'side_view_admin_status',

            'special_features_admin_status',

            'fc_admin_status',

            'insurance_admin_status',

            'puc_admin_status',

            'rc_front_admin_status',

            'rc_back_admin_status'

        ];

        $statuses = [];

        foreach ($fields as $field) {

            $statuses[$field] = $_POST[$field] ?? '';
        }

        $allApproved = !in_array('', $statuses, true) && count(array_unique($statuses)) === 1 && current($statuses) === 'approved';

        if (!$allApproved) {

            $result["type"] = '0';

            $result["result"] = "All details should be checked for vehicle approval";

            goto LKJHTYUI;
        }

        $result = [];

        $top_sts = 'approved';

        $re_mess = 'Vehicle details verified';

        $admin_verify = true;



        $fetch_user = select_query($rcon, "user_register", "", "id = '$id' AND `deletes`='0'", "", "");

        if ($fetch_user['nr'] > 0) {

            $tableData = $fetch_user['result'][0]['vehicle_details'] ? json_decode($fetch_user['result'][0]['vehicle_details'], true) : null;

            $name = $fetch_user['result'][0]['name'];

            $mobile = $fetch_user['result'][0]['mobile'];

            if ($tableData) {

                foreach ($statuses as $key => $value) {

                    if (isset($tableData['vehicle'][$key])) {

                        $tableData['vehicle'][$key] = $value;
                    } elseif (isset($tableData['fc_details'][$key])) {

                        $tableData['fc_details'][$key] = $value;
                    } elseif (isset($tableData['insurance_details'][$key])) {

                        $tableData['insurance_details'][$key] = $value;
                    } elseif (isset($tableData['puc_details'][$key])) {

                        $tableData['puc_details'][$key] = $value;
                    } else {

                        $tableData[$key] = $value;
                    }
                }

                $extraKeys = ['vehicle_review_message', 'admin_verify'];

                foreach ($extraKeys as $extraKey) {

                    if ($extraKey == 'admin_verify') {

                        $tableData[$extraKey] = $admin_verify;
                    }

                    if ($extraKey == 'vehicle_review_message') {

                        $tableData[$extraKey] = $re_mess;
                    }
                }



                // FIX: Encode with unescaped unicode/slashes to prevent formatting issues

                $updatedJson = json_encode($tableData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);



                // FIX: Escape the JSON string before putting it in the SQL query

                $escapedJson = mysqli_real_escape_string($rcon, $updatedJson);



                $update_kyc = "

                    UPDATE user_register 

                    SET 

                        `vehicle_details` = '$escapedJson',

                        `v_updated_at` = now(),

                        `vehicle_verify`  = '2'

                    WHERE id = '$id' AND `deletes` = '0'

                ";

                mysqli_query($rcon, $update_kyc);



                $result["type"] = '1';

                $result["result"] = $re_mess;



                // $what_mess = '*Vehicle Verification Completed!*

                // Hi ' . $name . ' , your vehicle has been successfully verified by the GoRide team. 🎉
                
                // Your vehicle is now approved and active for rides.  
                
                // You can start accepting bookings, placing bids, and driving with confidence. 🚘💼  
                
                // Thank you for keeping your documents up to date and maintaining GoRide’s quality standards. 🙌  
                
                // Watch this video for more information about Goride Jobs👇
                
                // https://youtu.be/_KNRIsVe-Yw?si=wd4eh7P0JzE-V5tn
                
                // 🚖 *GoRide — Drive Safe. Earn Smart. Ride Proud.*';

                $what_mess = "Vehicle Verification Completed!\n\nHi " . $name . " , your vehicle has been successfully verified by the GoRide team. 🎉\n\nYour vehicle is now approved and active for rides.  \nYou can start accepting bookings, placing bids, and driving with confidence. 🚘💼\n\nThank you for keeping your documents up to date and maintaining GoRide’s quality standards. 🙌\n\nWatch this video for more information about Goride Jobs👇\nhttps://youtu.be/_KNRIsVe-Yw?si=wd4eh7P0JzE-V5tn\n\n🚖 GoRide — Drive Safe. Earn Smart. Ride Proud.";

                if ($send_whatsapp == 'yes') {

                    $what_mess = mysqli_real_escape_string($con, $what_mess);

                    $name = mysqli_real_escape_string($con, $name);

                    $mobile = mysqli_real_escape_string($con, $mobile);

                    $ins_whatsapp = "

                            INSERT INTO whatsapp_bulk_message (`details`, `name`, `to_whatsapp`, `status`, `created_at`, `updated_at`)

                            VALUES ('$what_mess', '$name', '$mobile', 'pending', NOW(), NOW())

                        ";

                    mysqli_query($con, $ins_whatsapp);
                }

                goto LKJHTYUI;
            }
        } else {

            $result["type"] = '0';

            $result["result"] = "Record not found";
        }
    } catch (Exception $e) {

        $result["type"] = '0';

        $result["result"] = $e->getMessage();
    }

    LKJHTYUI:

    echo json_encode($result);
} elseif ($method == 'vehicleReject') {
    try {
        $id = $_POST['user_id'] ?? '';
        $send_whatsapp = $_POST['isWhatsapp'] ?? '';
        $fields = [
            'back_view_admin_status',
            'boot_admin_status',
            'car_top_view_admin_status',
            'extra_image_1_admin_status',
            'extra_image_2_admin_status',
            'front_view_admin_status',
            'interior_front_admin_status',
            'interior_rear_admin_status',
            'side_view_admin_status',
            'special_features_admin_status',
            'fc_admin_status',
            'insurance_admin_status',
            'puc_admin_status',
            'rc_front_admin_status',
            'rc_back_admin_status'
        ];
        $statuses = [];
        foreach ($fields as $field) {
            if ($_POST[$field] == 'rejected') {
                $statuses[$field] = $_POST[$field] ?? '';
            }
        }
        $anyRejected = in_array('rejected', $statuses, true);
        if (!$anyRejected) {
            $result["type"] = '0';
            $result["result"] = "None of them should be rejected";
            goto LKJHTYUII;
        }
        if ($_POST['remark'] == '' || $_POST['remark'] == null) {
            $result["type"] = '0';
            $result["result"] = "Remark field required";
            goto LKJHTYUII;
        }
        $result = [];
        $top_sts = 'rejected';
        $re_mess = $_POST['remark'];
        $admin_verify = false;
        // $up_status = 3;
        $fetch_user = select_query($rcon, "user_register", "", "id = '$id' AND `deletes`='0'", "", "");
        if ($fetch_user['nr'] > 0) {
            $tableData = $fetch_user['result'][0]['vehicle_details'] ? json_decode($fetch_user['result'][0]['vehicle_details'], true) : null;
            $name = $fetch_user['result'][0]['name'];
            $mobile = $fetch_user['result'][0]['mobile'];
            if ($tableData) {
                foreach ($statuses as $key => $value) {
                    if (isset($tableData['vehicle'][$key])) {
                        $tableData['vehicle'][$key] = $value;
                    } elseif (isset($tableData['fc_details'][$key])) {
                        $tableData['fc_details'][$key] = $value;
                    } elseif (isset($tableData['insurance_details'][$key])) {
                        $tableData['insurance_details'][$key] = $value;
                    } elseif (isset($tableData['puc_details'][$key])) {
                        $tableData['puc_details'][$key] = $value;
                    } else {
                        $tableData[$key] = $value;
                    }
                }
                $extraKeys = ['vehicle_review_message', 'admin_verify'];
                foreach ($extraKeys as $extraKey) {
                    if ($extraKey == 'admin_verify') {
                        $tableData[$extraKey] = $admin_verify;
                    }
                    if ($extraKey == 'vehicle_review_message') {
                        $tableData[$extraKey] = $re_mess;
                    }
                }
                $updatedJson = json_encode($tableData, JSON_PRETTY_PRINT);
                $update_kyc = "
                    UPDATE user_register 
                    SET 
                        `vehicle_details` = '$updatedJson',
                        `v_updated_at` = now(),
                        `vehicle_verify`  = '1'
                    WHERE id = '$id' AND `deletes` = '0'
                ";
                mysqli_query($rcon, $update_kyc);
                $result["type"] = '1';
                $result["result"] = 'Rejection process completed.';
                // var_dump($updatedJson);die;
                $what_mess = '⚠️Vehicle Verification Declined ⚠️
                
Hi *' . $name . '*, we’ve reviewed your vehicle details, and unfortunately, your verification could not be completed. 

Some of your documents or photos need correction or re-upload.  
Please review them and resubmit for approval. 🧾📸

*Reason:* ' . $re_mess . '
Once you update the required details, our team will recheck and verify your vehicle. 🔄 

🚖 *GoRide — Drive Safe. Earn Smart. Ride Proud.*';
                if ($send_whatsapp == 'yes') {
                    $what_mess = mysqli_real_escape_string($con, $what_mess);
                    $name = mysqli_real_escape_string($con, $name);
                    $mobile = mysqli_real_escape_string($con, $mobile);
                    $ins_whatsapp = "
                            INSERT INTO whatsapp_bulk_message (`details`, `name`, `to_whatsapp`, `status`, `created_at`, `updated_at`)
                            VALUES ('$what_mess', '$name', '$mobile', 'pending', NOW(), NOW())
                        ";
                    mysqli_query($con, $ins_whatsapp);
                }
                goto LKJHTYUII;
            }
        } else {
            $result["type"] = '0';
            $result["result"] = "Record not found";
        }
    } catch (Exception $e) {
        $result["type"] = '0';
        $result["result"] = $e->getMessage();
    }
    LKJHTYUII:
    echo json_encode($result);
}
