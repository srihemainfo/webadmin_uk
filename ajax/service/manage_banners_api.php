<?php
include '../../include/shi-config.php';
// include '../../include/functions.php'; // Uncomment if needed

header('Content-Type: application/json');

if (isset($_POST['method'])) {
    
    // ==========================================
    // 0. CHECK ORDER NUM (Scoped by Display State)
    // ==========================================
    if ($_POST['method'] === 'check_order_num') {
        $id        = isset($_POST['id']) ? intval($_POST['id']) : -1;
        $order_num = isset($_POST['order_num']) ? intval($_POST['order_num']) : 0;
        $display_state = isset($_POST['display_state']) ? mysqli_real_escape_string($con, $_POST['display_state']) : '';

        // Safely check state bounds (Handling 'All states' vs 'Specific State')
        if (empty($display_state)) {
            $stateCondition = "(`display_state` = '' OR `display_state` IS NULL)";
            $stateNameForMsg = "All States";
        } else {
            $stateCondition = "`display_state` = '$display_state'";
            $stateNameForMsg = $display_state;
        }

        $checkOrderQuery = "SELECT id FROM `banner` WHERE `order_num` = $order_num AND $stateCondition";
        
        if ($id > 0) {
            $checkOrderQuery .= " AND id != $id";
        }
        
        $checkResult = mysqli_query($con, $checkOrderQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            echo json_encode(['status' => false, 'message' => "Order number $order_num is already in use for $stateNameForMsg."]);
        } else {
            echo json_encode(['status' => true, 'message' => "Order number is valid."]);
        }
        exit;
    }

    // ==========================================
    // 1. GET ALL BANNERS (WITH S3 IMAGE URL)
    // ==========================================
    if ($_POST['method'] === 'get_banners') {
        $query = "
            SELECT b.*, s.s3_url AS image_url 
            FROM `banner` b 
            LEFT JOIN `s3_images` s ON b.image_id = s.id 
            ORDER BY b.`display_state` ASC, b.`order_num` ASC
        ";
        
        $result = mysqli_query($con, $query);
        
        $data = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        echo json_encode(['status' => true, 'data' => $data]);
        exit;
    }

    // ==========================================
    // 2. SAVE BANNER (INSERT / UPDATE)
    // ==========================================
    if ($_POST['method'] === 'save_banner') {
        $id            = isset($_POST['id']) ? intval($_POST['id']) : -1;
        $title         = mysqli_real_escape_string($con, $_POST['title']);
        $subtitle      = mysqli_real_escape_string($con, $_POST['subtitle']);
        $route         = mysqli_real_escape_string($con, $_POST['route']);
        $order_num     = intval($_POST['order_num']);
        $is_active     = isset($_POST['is_active']) && $_POST['is_active'] === 'true' ? 1 : 0;
        $image_id      = isset($_POST['image_id']) ? intval($_POST['image_id']) : 0;
        $display_state = mysqli_real_escape_string($con, $_POST['display_state'] ?? '');

        // Re-validate duplicate check safely scoped by state
        if (empty($display_state)) {
            $stateCondition = "(`display_state` = '' OR `display_state` IS NULL)";
            $stateNameForMsg = "All States";
        } else {
            $stateCondition = "`display_state` = '$display_state'";
            $stateNameForMsg = $display_state;
        }

        $checkOrderQuery = "SELECT id FROM `banner` WHERE `order_num` = $order_num AND $stateCondition";
        if ($id > 0) {
            $checkOrderQuery .= " AND id != $id";
        }
        
        $checkResult = mysqli_query($con, $checkOrderQuery);
        if (mysqli_num_rows($checkResult) > 0) {
            echo json_encode(['status' => false, 'message' => "Order number $order_num is already in use for $stateNameForMsg."]);
            exit;
        }

        // Processing Database logic based on New or Existing Banner
        if ($id > 0) {
            // UPDATE EXISTING
            $updateQuery = "UPDATE `banner` SET
                `title` = '$title',
                `subtitle` = '$subtitle',
                `route` = '$route',
                `order_num` = $order_num,
                `is_active` = $is_active,
                `display_state` = '$display_state'";
                
            // Only update image_id if a new image was uploaded and we received a valid ID
            if ($image_id > 0) {
                $updateQuery .= ", `image_id` = $image_id";
            }
            
            $updateQuery .= " WHERE `id` = $id";

            if (mysqli_query($con, $updateQuery)) {
                echo json_encode(['status' => true, 'message' => 'Banner updated successfully.']);
            } else {
                echo json_encode(['status' => false, 'message' => 'Database update failed: ' . mysqli_error($con)]);
            }
        } else {
            // INSERT NEW
            $insertQuery = "INSERT INTO `banner` 
            (`title`, `subtitle`, `route`, `order_num`, `is_active`, `image_id`, `display_state`, `created_at`) 
            VALUES (
                '$title', '$subtitle', '$route', $order_num, $is_active, $image_id, '$display_state', NOW()
            )";
            
            if (mysqli_query($con, $insertQuery)) {
                echo json_encode(['status' => true, 'message' => 'Banner added successfully.']);
            } else {
                echo json_encode(['status' => false, 'message' => 'Database insert failed: ' . mysqli_error($con)]);
            }
        }
        exit;
    }

    // ==========================================
    // 3. DELETE BANNER
    // ==========================================
    if ($_POST['method'] === 'delete_banner') {
        $id = intval($_POST['id']);
        $deleteQuery = "DELETE FROM `banner` WHERE `id` = $id";
        
        if (mysqli_query($con, $deleteQuery)) {
            echo json_encode(['status' => true, 'message' => 'Banner deleted successfully.']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Failed to delete banner.']);
        }
        exit;
    }
    
    // ==========================================
    // 4. GET STATES
    // ==========================================
    if ($_POST['method'] === 'get_states') {
        $states = [];
        $qry = mysqli_query($con,"
            SELECT DISTINCT current_state
            FROM customer_register
            WHERE current_state IS NOT NULL
            AND current_state <> ''
            ORDER BY current_state ASC
        ");

        while($row = mysqli_fetch_assoc($qry)){
            $states[] = $row['current_state'];
        }

        echo json_encode([
            'status' => true,
            'data' => $states
        ]);
        exit;
    }
}
?>