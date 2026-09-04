<?php
include '../../include/shi-config.php';
include '../../include/functions.php';
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
header('Content-Type: application/json');

if (isset($_POST['method'])) {
    
    // --- ADD NEW DISTRICT ---
    if ($_POST['method'] === 'add_district') {
        $districtName = mysqli_real_escape_string($con, trim($_POST['district_name']));
        
        if (empty($districtName)) {
            echo json_encode(['status' => false, 'message' => 'District name is required.']);
            exit;
        }

        // Check if district already exists
        $checkQuery = mysqli_query($con, "SELECT id FROM `districts` WHERE `district_name` = '$districtName' AND `deletes` = 0");
        if (mysqli_num_rows($checkQuery) > 0) {
            echo json_encode(['status' => false, 'message' => 'District already exists.']);
            exit;
        }

        // Insert into database (state defaults to 'Tamil Nadu' automatically per your DB schema)
        $insertQuery = "INSERT INTO `districts` (`district_name`) VALUES ('$districtName')";
        if (mysqli_query($con, $insertQuery)) {
            echo json_encode(['status' => true, 'message' => 'District added successfully.']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Database error. Failed to add district.']);
        }
        exit;
    }

    // --- GET ALL DISTRICTS ---
    if ($_POST['method'] === 'get_districts') {
        $query = mysqli_query($con, "SELECT id, district_name FROM `districts` WHERE `deletes` = 0 ORDER BY id DESC");
        
        $data = [];
        $sno = 1;
        
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = [
                'sno' => $sno++,
                'id' => $row['id'],
                'name' => $row['district_name']
            ];
        }
        
        echo json_encode(['status' => true, 'data' => $data]);
        exit;
    }
}
?>