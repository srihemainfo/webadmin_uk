<?php
include '../../include/shi-config.php';
include '../../include/functions.php';

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";
$type = isset($_REQUEST["type"]) ? $_REQUEST['type'] : "";
$tabID = isset($_REQUEST["tabID"]) ? $_REQUEST['tabID'] : "";
$role = $_REQUEST['role'] ?? ''; 

// Enable all error reporting
// error_reporting(E_ALL); 
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Display errors in the browser
// ini_set('display_errors', 1);

// Optional: Show startup errors as well
// ini_set('display_startup_errors', 1);

if ($type == 'agent') {
    $role = isset($_REQUEST["role"]) ? "`roll_id` IN ($role) AND" : "";
} else {
    $role = isset($_REQUEST["role"]) ? "`roll_id` = '$role' AND" : "";
}

$headers = apache_request_headers();
$result = [];

$post_csrf = $headers['X-Csrf-Token'] ?? '';

// if ($method === "userReport") {

//     try {
//         $startDate  = $_POST['startDate'] ?? '';
//         $endDate    = $_POST['endDate'] ?? '';
//         $dateFilter = $_POST['dateFilter'] ?? '';
//         $searchTxt = $_POST['searchTxt'] ?? '';
//         $search_filter = $_POST['search_filter'] ?? '';
//         $rmFilter = $_POST['rmFilter'] ?? '';
//         $agentsFilter = $_POST['agentsFilter'] ?? '';

//         $conditions = [];
//         $result = [
//             'type'   => 0,
//             'result' => []
//         ];
        
//         if (!empty($searchTxt)) {
//             $searchTxt = $con->real_escape_string($searchTxt);
//             $conditions[] = "(ur.name LIKE '%$searchTxt%' 
//                             OR ur.email LIKE '%$searchTxt%' 
//                             OR ur.mobile LIKE '%$searchTxt%')";
//         }
        
//         // Use !== '' instead of !empty() to avoid skipping '0'
//         if ($search_filter !== '' && $search_filter !== 'all') {
//             $search_filter = $con->real_escape_string($search_filter);
//             $conditions[] = "kyc.type = '$search_filter'";
//         }
        
//         // FIX 1: Use !== '' so that rmFilter == '0' doesn't get ignored by empty()
//         if ($rmFilter !== '' && $rmFilter !== 'all') {
//             if ($rmFilter == '1') {
//                 // Enabled and Assigned
//                 // FIX 2: Ensure it explicitly ignores empty strings and 0s
//                 $conditions[] = "(ur.rm_status = '1' AND ur.agent_id IS NOT NULL AND ur.agent_id != '0' AND ur.agent_id != '')";
    
//             } elseif ($rmFilter == '0') {
//                 // Disabled
//                 $conditions[] = "ur.rm_status = '0'";
    
//             } elseif ($rmFilter == '2') {
//                 // Enabled but Unassigned
//                 // FIX 3: Catch NULLs, 0s, and empty strings
//                 $conditions[] = "(ur.rm_status = '1' AND (ur.agent_id IS NULL OR ur.agent_id = '0' OR ur.agent_id = ''))";
//             }
//         }
        
//         if ($agentsFilter !== '' && $agentsFilter !== 'all') {
//             $agentsFilter = $con->real_escape_string($agentsFilter);
//             $conditions[] = "ur.agent_id = '$agentsFilter'";
//         }

//         // if (!empty($dateFilter) && !empty($startDate) && !empty($endDate)) {
//         //     $conditions[] = "
//         //         ca.created_at 
//         //         BETWEEN '$startDate 00:00:00' 
//         //         AND '$endDate 23:59:59'
//         //     ";
//         // }
        
//         // existing conditions
//         $conditions[] = "ur.deletes = '0'";
//         $conditions[] = "kyc.type IN ('Driver', 'Owner')";
        
//         $whereClause = '';
//         if (!empty($conditions)) {
//             $whereClause = 'WHERE ' . implode(' AND ', $conditions);
//         }
        
//         $selectQuery = "
//             SELECT 
//                 ur.id,
//                 kyc.user_id,
//                 ur.name,
//                 ur.agent_id,
//                 kyc.type,
//                 ur.mobile,
//                 ur.email,
//                 ur.rm_status,
//                 ur.created_at,
        
//                 IF(
//                     agent.id IS NOT NULL, 
//                     CONCAT(agent.name, ' ', agent.lname), 
//                     '-'
//                 ) AS matched_name
        
//             FROM user_register ur
        
//             LEFT JOIN kyc_details kyc
//                 ON ur.id = kyc.user_id
        
//             LEFT JOIN user_register agent
//                 ON ur.agent_id = agent.id
//                 AND agent.roll_id = 3
//                 AND agent.deletes = '0'
        
//             $whereClause
//         ";

//         $get_data = $con->query($selectQuery);

//         if ($get_data && $get_data->num_rows > 0) {
//             $result['type'] = 1;
//             while ($row = $get_data->fetch_assoc()) {
//                 $result['result'][] = $row;
//             }
//         }

//     } catch (Exception $e) {
//         $result['type'] = 0;
//         $result['result'] = $e->getMessage();
//     }

//     echo json_encode($result);
//     exit;
// }
if ($method === "userReport") {

    try {
        $startDate  = $_POST['startDate'] ?? '';
        $endDate    = $_POST['endDate'] ?? '';
        $dateFilter = $_POST['dateFilter'] ?? '';
        $searchTxt = $_POST['searchTxt'] ?? '';
        $search_filter = $_POST['search_filter'] ?? '';
        $rmFilter = $_POST['rmFilter'] ?? '';
        $agentsFilter = $_POST['agentsFilter'] ?? '';

        $conditions = [];
        $result = [
            'type'   => 0,
            'result' => []
        ];
        
        if (!empty($searchTxt)) {
            $searchTxt = $con->real_escape_string($searchTxt);
            $conditions[] = "(ur.name LIKE '%$searchTxt%' 
                            OR ur.email LIKE '%$searchTxt%' 
                            OR ur.mobile LIKE '%$searchTxt%')";
        }
        
        // Use !== '' instead of !empty() to avoid skipping '0'
        if ($search_filter !== '' && $search_filter !== 'all') {
            $search_filter = $con->real_escape_string($search_filter);
            $conditions[] = "kyc.type = '$search_filter'";
        }
        
        // FIX 1: Use !== '' so that rmFilter == '0' doesn't get ignored by empty()
        if ($rmFilter !== '' && $rmFilter !== 'all') {
            if ($rmFilter == '1') {
                // Enabled and Assigned
                // FIX 2: Ensure it explicitly ignores empty strings and 0s
                $conditions[] = "(ur.rm_status = '1' AND ur.agent_id IS NOT NULL AND ur.agent_id != '0' AND ur.agent_id != '')";
    
            } elseif ($rmFilter == '0') {
                // Disabled
                $conditions[] = "ur.rm_status = '0'";
    
            } elseif ($rmFilter == '2') {
                // Enabled but Unassigned
                // FIX 3: Catch NULLs, 0s, and empty strings
                $conditions[] = "(ur.rm_status = '1' AND (ur.agent_id IS NULL OR ur.agent_id = '0' OR ur.agent_id = ''))";
            }
        }
        
        if ($agentsFilter !== '' && $agentsFilter !== 'all') {
            $agentsFilter = $con->real_escape_string($agentsFilter);
            $conditions[] = "ur.agent_id = '$agentsFilter'";
        }

        // if (!empty($dateFilter) && !empty($startDate) && !empty($endDate)) {
        //     $conditions[] = "
        //         ca.created_at 
        //         BETWEEN '$startDate 00:00:00' 
        //         AND '$endDate 23:59:59'
        //     ";
        // }
        
        // existing conditions
        $conditions[] = "ur.deletes = '0'";
        $conditions[] = "kyc.type IN ('Driver', 'Owner')";
        
        $whereClause = '';
        if (!empty($conditions)) {
            $whereClause = 'WHERE ' . implode(' AND ', $conditions);
        }
        
        $selectQuery = "
            SELECT 
                ur.id,
                kyc.user_id,
                ur.name,
                ur.agent_id,
                kyc.type,
                ur.mobile,
                ur.email,
                ur.rm_status,
                ur.created_at,
        
                IF(
                    agent.id IS NOT NULL, 
                    CONCAT(agent.name, ' ', agent.lname), 
                    '-'
                ) AS matched_name
        
            FROM user_register ur
        
            LEFT JOIN kyc_details kyc
                ON ur.id = kyc.user_id
        
            LEFT JOIN user_register agent
                ON ur.agent_id = agent.id
                AND agent.roll_id = 3
                AND agent.deletes = '0'
        
            $whereClause
        ";

        $get_data = $con->query($selectQuery);

        if ($get_data && $get_data->num_rows > 0) {
            $result['type'] = 1;
            while ($row = $get_data->fetch_assoc()) {
                $result['result'][] = $row;
            }
        }

    } catch (Exception $e) {
        $result['type'] = 0;
        $result['result'] = $e->getMessage();
    }

    echo json_encode($result);
    exit;
}

if(isset($_POST['action']) && $_POST['action'] == 'delete'){

    $id = intval($_POST['id']);

    $query = mysqli_query($con, "
        UPDATE user_register 
        SET rm_status = 0 , agent_id = 0
        WHERE id = '$id'
    ");

    if($query){
        echo json_encode([
            "status" => true,
            "message" => "Status updated successfully"
        ]);
    }else{
        echo json_encode([
            "status" => false,
            "message" => "Update failed"
        ]);
    }

    exit; // VERY IMPORTANT
}

else if ($method == "assignAgent") {
    
    $userId = BlockSQLInjection($_POST["userId"] ?? '');
    $agentId = BlockSQLInjection($_POST["agentId"] ?? '');
    $assignedBy = $_SESSION['memid'] ?? 0;

    if ($userId == '') {
        echo json_encode(["type" => "0", "result" => "User ID Not Found"]);
        exit;
    }

    // Determine RM status: 1 if an agent is selected, 0 if it's cleared out
    $rm_status = (!empty($agentId) && $agentId != '0') ? 1 : 0;

    $con->begin_transaction();
    try {
        // 1. Inactivate existing agent mapping for this driver/owner
        $checkQuery = "SELECT id FROM agent_driver_map WHERE driver_id = ? AND status = 'active' AND deletes = 0 LIMIT 1";
        $stmt = $con->prepare($checkQuery);
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result_k = $stmt->get_result();

        if ($result_k->num_rows > 0) {
            $updateQuery = "UPDATE agent_driver_map SET status = 'inactive', updated_at = NOW() WHERE driver_id = ? AND status = 'active' AND deletes = 0";
            $updateStmt = $con->prepare($updateQuery);
            $updateStmt->bind_param("s", $userId);
            $updateStmt->execute();
        }

        // 2. Insert new agent mapping ONLY if an agent was actually selected
        if ($rm_status == 1) {
            $insertQuery = "INSERT INTO agent_driver_map (agent_id, driver_id, assigned_by, status, deletes, created_at, updated_at) VALUES (?, ?, ?, 'active', 0, NOW(), NOW())";
            $insertStmt = $con->prepare($insertQuery);
            $insertStmt->bind_param("sss", $agentId, $userId, $assignedBy);
            
            if (!$insertStmt->execute()) {
                throw new Exception("Agent map insert failed");
            }
        }

        // 3. Update the user_register table
        $updateUserQuery = "UPDATE user_register SET agent_id = ?, rm_status = ? WHERE id = ? AND deletes = '0'";
        $updateUserStmt = $con->prepare($updateUserQuery);
        $updateUserStmt->bind_param("sss", $agentId, $rm_status, $userId);
        
        if (!$updateUserStmt->execute()) {
            throw new Exception("User register update failed");
        }

        $con->commit();
        
        // --- NEW: DYNAMIC SUCCESS MESSAGE ---
        if ($rm_status == 1) {
            $successMessage = "Agent Assigned Successfully";
        } else {
            $successMessage = "Agent Removed Successfully";
        }

        echo json_encode(["type" => "1", "result" => $successMessage]);
        
    } catch (Exception $e) {
        $con->rollback();
        echo json_encode(["type" => "0", "result" => "Agent Assignment failed: " . $e->getMessage()]);
    }
    exit;
}

















// if ($method === "user_report") {

//     try {

//         $startDate       = $_POST['startDate'] ?? '';
//         $endDate         = $_POST['endDate'] ?? '';
//         $dateFilter      = $_POST['dateFilter'] ?? '';
        
//         // var_dump($stateFilter);die;

//         $conditions = [];
//         $result = [
//             'type'   => 0,
//             'result' => []
//         ];


//         $conditions[] = "user_register.deletes = '0'";


//         // if (!empty($dateFilter) && !empty($startDate) && !empty($endDate)) {
//         //     $conditions[] = "kyc_details.created_at 
//         //                      BETWEEN '$startDate 00:00:00' 
//         //                      AND '$endDate 23:59:59'";
//         // }


//         // if ($verifyType === 'pending') {

//         //     $conditions[] = "user_register.vehicle_verify = '2'";
//         //     $conditions[] = "kyc_details.cab_type IS NULL";
            
//         //     if (!empty($districtFilter)) {
//         //         $conditions[] = "user_register.districts_id = '$districtFilter'";
//         //     }
            
//         //      if (!empty($stateFilter)) {
//         //         $conditions[] = "user_register.state_id = '$stateFilter'";
//         //     }

//         // } 
//         // else if ($verifyType === 'verified') {

//         //     $conditions[] = "user_register.vehicle_verify = '2'";
//         //     $conditions[] = "kyc_details.cab_type IS NOT NULL";

//         //     if (!empty($cabTypeFilter)) {
//         //         $conditions[] = "kyc_details.cab_type = '$cabTypeFilter'";
//         //     }

//         //     if (!empty($fueltypeFilter)) {
//         //         $conditions[] = "user_register.fuel_type = '$fueltypeFilter'";
//         //     }
            
//         //     if (!empty($districtFilter)) {
//         //         $conditions[] = "user_register.districts_id = '$districtFilter'";
//         //     }
            
//         //      if (!empty($stateFilter)) {
//         //         $conditions[] = "user_register.state_id = '$stateFilter'";
//         //     }
//         // }


//         $whereClause = '';
//         if (!empty($conditions)) {
//             $whereClause = 'WHERE ' . implode(' AND ', $conditions);
//         }

  
//         $selectQuery = "
//           SELECT 
//                 user_register.id,
//                 user_register.name,
//                 kyc_details.type,
//                 user_register.email,
//                  user_register.created_at
//             FROM user_register
            
//             LEFT JOIN kyc_details 
//                 ON user_register.id = kyc_details.user_id
//              WHERE user_register.deletes = '0'
            
//                     ";

       

//         $get_data = $con->query($selectQuery);

//         if ($get_data && $get_data->num_rows > 0) {
//             $result['type'] = 1;
//             while ($row = $get_data->fetch_assoc()) {
//                 $result['result'][] = $row;
//             }
//         }

//     } catch (Exception $e) {
//         $result['type'] = 0;
//         $result['result'] = $e->getMessage();
//     }

//     echo json_encode($result);
//     exit;
// }


else {
    $result['type'] = 0;
    $result['result'] = 'The Method Not Found!';
    goto resutGJHIP;
}

resutGJHIP:
echo json_encode($result);