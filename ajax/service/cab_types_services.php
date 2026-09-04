<?php
include '../../include/shi-config.php';
include '../../include/functions.php';
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
header('Content-Type: application/json');

if (isset($_POST['method'])) {
    
    // --- ADD NEW CAB TYPE ---
    if ($_POST['method'] === 'add_cab_type') {
        $cabName = mysqli_real_escape_string($con, trim($_POST['cab_name']));
        $seatCapacity = mysqli_real_escape_string($con, trim($_POST['seat_capacity']));
        
        if (empty($cabName) || empty($seatCapacity)) {
            echo json_encode(['status' => false, 'message' => 'Cab name and seat capacity are required.']);
            exit;
        }

        // Check if cab type already exists
        $checkQuery = mysqli_query($con, "SELECT id FROM `cab_types` WHERE `name` = '$cabName'");
        if (mysqli_num_rows($checkQuery) > 0) {
            echo json_encode(['status' => false, 'message' => 'Cab type already exists.']);
            exit;
        }

        // Insert into database
        $insertQuery = "INSERT INTO `cab_types` (`name`, `seat_capacity`) VALUES ('$cabName', '$seatCapacity')";
        if (mysqli_query($con, $insertQuery)) {
            echo json_encode(['status' => true, 'message' => 'Cab type added successfully.']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Database error. Failed to add cab type.']);
        }
        exit;
    }

    // --- EDIT CAB TYPE ---
    if ($_POST['method'] === 'edit_cab_type') {
        $cabId = mysqli_real_escape_string($con, trim($_POST['cab_id']));
        $cabName = mysqli_real_escape_string($con, trim($_POST['cab_name']));
        $seatCapacity = mysqli_real_escape_string($con, trim($_POST['seat_capacity']));
        
        if (empty($cabId) || empty($cabName) || empty($seatCapacity)) {
            echo json_encode(['status' => false, 'message' => 'All fields are required.']);
            exit;
        }

        // Check if cab type name already exists for ANOTHER record
        $checkQuery = mysqli_query($con, "SELECT id FROM `cab_types` WHERE `name` = '$cabName' AND id != '$cabId'");
        if (mysqli_num_rows($checkQuery) > 0) {
            echo json_encode(['status' => false, 'message' => 'Cab type name already exists.']);
            exit;
        }

        // Update database
        $updateQuery = "UPDATE `cab_types` SET `name` = '$cabName', `seat_capacity` = '$seatCapacity' WHERE `id` = '$cabId'";
        if (mysqli_query($con, $updateQuery)) {
            echo json_encode(['status' => true, 'message' => 'Cab type updated successfully.']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Database error. Failed to update cab type.']);
        }
        exit;
    }

    // --- GET ALL CAB TYPES ---
    if ($_POST['method'] === 'get_cab_types') {
        $search = isset($_POST['search']) ? mysqli_real_escape_string($con, trim($_POST['search'])) : '';
        
        $whereClause = "";
        if (!empty($search)) {
            $whereClause = " WHERE `name` LIKE '%$search%' ";
        }

        $query = mysqli_query($con, "SELECT id, name, seat_capacity FROM `cab_types` $whereClause ORDER BY id DESC");
        
        $data = [];
        $sno = 1;
        
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = [
                'sno' => $sno++,
                'id' => $row['id'],
                'name' => $row['name'],
                'seat_capacity' => $row['seat_capacity']
            ];
        }
        
        echo json_encode(['status' => true, 'data' => $data]);
        exit;
    }
}
?>