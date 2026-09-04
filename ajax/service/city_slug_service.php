<?php
include '../../include/shi-config.php';
include '../../include/functions.php';
error_reporting(E_ALL);
// ini_set('display_errors', 1);
// header('Content-Type: application/json');

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

// Handle Invalid Action
if (empty($action)) {
    echo json_encode(['status' => false, 'message' => 'No action specified']);
    exit;
}

switch ($action) {
    // --- 1. FETCH ALL SLUGS (READ) ---
    case 'fetch':
        $query = mysqli_query($con, "SELECT * FROM `city_slug` ORDER BY slug ASC");
        $data = [];
        if ($query) {
            while ($row = mysqli_fetch_assoc($query)) {
                $data[] = $row;
            }
        }
        echo json_encode(['data' => $data]);
        break;

    // --- 2. CREATE NEW SLUG ---
    case 'create':
        $slug = mysqli_real_escape_string($con, trim($_POST['slug']));
        
        if (empty($slug)) {
            echo json_encode(['status' => false, 'message' => 'Slug cannot be empty']);
            exit;
        }

        // Check if slug already exists
        $check = mysqli_query($con, "SELECT slug FROM `city_slug` WHERE slug = '$slug'");
        if (mysqli_num_rows($check) > 0) {
            echo json_encode(['status' => false, 'message' => 'This slug already exists!']);
        } else {
            // Insert with status 0 (Active)
            $insert = mysqli_query($con, "INSERT INTO `city_slug` (`slug`, `status`) VALUES ('$slug', 0)");
            if ($insert) {
                echo json_encode(['status' => true, 'message' => 'Slug added successfully']);
            } else {
                echo json_encode(['status' => false, 'message' => 'Database error on insert']);
            }
        }
        break;

    // --- 3. UPDATE EXISTING SLUG ---
    case 'update':
        $old_slug = mysqli_real_escape_string($con, trim($_POST['old_slug']));
        $new_slug = mysqli_real_escape_string($con, trim($_POST['slug']));
        
        if (empty($new_slug)) {
            echo json_encode(['status' => false, 'message' => 'Slug cannot be empty']);
            exit;
        }

        // If changing the name, check if the NEW name already exists
        if ($old_slug !== $new_slug) {
            $check = mysqli_query($con, "SELECT slug FROM `city_slug` WHERE slug = '$new_slug'");
            if (mysqli_num_rows($check) > 0) {
                echo json_encode(['status' => false, 'message' => 'The new slug name already exists!']);
                exit;
            }
        }

        $update = mysqli_query($con, "UPDATE `city_slug` SET `slug` = '$new_slug' WHERE `slug` = '$old_slug'");
        if ($update) {
            echo json_encode(['status' => true, 'message' => 'Slug updated successfully']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Database error on update']);
        }
        break;

    // --- 4. PERMANENT DELETE ---
    case 'delete':
        $slug = mysqli_real_escape_string($con, trim($_POST['slug']));
        $delete = mysqli_query($con, "DELETE FROM `city_slug` WHERE `slug` = '$slug'");
        if ($delete) {
            echo json_encode(['status' => true, 'message' => 'Slug deleted permanently']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Failed to delete slug']);
        }
        break;

    // --- 5. TOGGLE STATUS ---
    case 'toggle_status':
        $slug = mysqli_real_escape_string($con, trim($_POST['slug']));
        $current_status = (int)$_POST['current_status'];
        
        // 0 = Active, 1 = Inactive
        $new_status = ($current_status === 0) ? 1 : 0;
        
        $update = mysqli_query($con, "UPDATE `city_slug` SET `status` = $new_status WHERE `slug` = '$slug'");
        if ($update) {
            $statusText = ($new_status === 0) ? 'Activated' : 'Deactivated';
            echo json_encode(['status' => true, 'message' => "Slug successfully $statusText"]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Failed to update status']);
        }
        break;

    default:
        echo json_encode(['status' => false, 'message' => 'Invalid action']);
        break;
}
?>