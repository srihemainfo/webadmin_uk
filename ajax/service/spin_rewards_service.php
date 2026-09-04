<?php
include '../../include/shi-config.php';

if ($con->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed.']));
}

$method = $_POST['method'] ?? '';

function generateValidatedJSON($types, $values, $limits) {
    if (count($types) !== 12 || count($limits) !== 12) {
        return false;
    }

    $rewards_data_array = [];
    for ($i = 0; $i < 12; $i++) {
        $type = $types[$i];
        $limit = $limits[$i];
        $val = null;
        
        if (!preg_match('/^\d+$/', $limit)) {
            return false;
        }

        if ($type === 'cashback') {
            $val = $values[$i];
            if (!preg_match('/^\d{1,4}$/', $val)) {
                return false; 
            }
            $val = (int)$val;
        }
        
        $rewards_data_array[] = [
            'slot' => $i + 1,
            'type' => $type,
            'value' => $val,
            'limit' => (int)$limit
        ];
    }
    return json_encode($rewards_data_array);
}

switch ($method) {
    case 'fetch_rewards':
        $data = [];
        $query = "SELECT id, reward_date, rewards_data, status FROM spin_wheel_rewards ORDER BY reward_date DESC";
        $result = $con->query($query);
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        echo json_encode(['data' => $data]);
        break;

    case 'add_rewards':
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';
        $types = $_POST['reward_type'] ?? [];
        $values = $_POST['reward_value'] ?? [];
        $limits = $_POST['reward_limit'] ?? [];

        if (empty($start_date)) {
            echo json_encode(['status' => 'error', 'message' => 'Start date is required.']);
            exit;
        }

        if (empty($end_date)) {
            $end_date = $start_date;
        }

        if (strtotime($start_date) > strtotime($end_date)) {
            echo json_encode(['status' => 'error', 'message' => 'End Date cannot be earlier than Start Date.']);
            exit;
        }

        if (strtotime($start_date) < strtotime(date('Y-m-d'))) {
            echo json_encode(['status' => 'error', 'message' => 'Cannot add rewards for a past date.']);
            exit;
        }

        $json_data = generateValidatedJSON($types, $values, $limits);
        if (!$json_data) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data. Check that amounts are max 4 digits and all Number of People limits are filled with numbers.']);
            exit;
        }

        $status = 'active'; 

        // Pre-check for existing active configurations in the selected date range
        $existing_dates = [];
        $current_date = strtotime($start_date);
        $end_time = strtotime($end_date);

        $checkStmt = $con->prepare("SELECT id FROM spin_wheel_rewards WHERE reward_date = ? AND status = 'active'");
        
        while ($current_date <= $end_time) {
            $date_str = date('Y-m-d', $current_date);
            $checkStmt->bind_param("s", $date_str);
            $checkStmt->execute();
            $checkStmt->store_result();
            if ($checkStmt->num_rows > 0) {
                $existing_dates[] = $date_str;
            }
            $current_date = strtotime('+1 day', $current_date);
        }
        $checkStmt->close();

        if (!empty($existing_dates)) {
            echo json_encode(['status' => 'error', 'message' => 'Active rewards already exist for: ' . implode(', ', $existing_dates) . '. Please select empty dates.']);
            exit;
        }

        // Insert configurations for each day in the range
        $insertStmt = $con->prepare("INSERT INTO spin_wheel_rewards (reward_date, rewards_data, status) VALUES (?, ?, ?)");
        $current_date = strtotime($start_date);
        $inserted_count = 0;

        while ($current_date <= $end_time) {
            $date_str = date('Y-m-d', $current_date);
            $insertStmt->bind_param("sss", $date_str, $json_data, $status);
            if ($insertStmt->execute()) {
                $inserted_count++;
            }
            $current_date = strtotime('+1 day', $current_date);
        }
        $insertStmt->close();

        echo json_encode(['status' => 'success', 'message' => "Rewards created successfully for $inserted_count day(s)."]);
        break;

    case 'edit_rewards':
        $id = (int)($_POST['reward_id'] ?? 0);
        $types = $_POST['reward_type'] ?? [];
        $values = $_POST['reward_value'] ?? [];
        $limits = $_POST['reward_limit'] ?? [];

        if ($id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid Record ID.']);
            exit;
        }

        $json_data = generateValidatedJSON($types, $values, $limits);
        if (!$json_data) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data. Check that amounts are max 4 digits and all Number of People limits are filled with numbers.']);
            exit;
        }

        $updateStmt = $con->prepare("UPDATE spin_wheel_rewards SET rewards_data = ? WHERE id = ?");
        $updateStmt->bind_param("si", $json_data, $id);
        
        if ($updateStmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Rewards configuration updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $updateStmt->error]);
        }
        $updateStmt->close();
        break;

    case 'update_status':
        $id = (int)$_POST['id'];
        $status = $_POST['status'];

        if ($status === 'active') {
            $dateStmt = $con->prepare("SELECT reward_date FROM spin_wheel_rewards WHERE id = ?");
            $dateStmt->bind_param("i", $id);
            $dateStmt->execute();
            $dateStmt->bind_result($reward_date);
            $dateStmt->fetch();
            $dateStmt->close();

            if ($reward_date) {
                $checkActiveStmt = $con->prepare("SELECT id FROM spin_wheel_rewards WHERE reward_date = ? AND status = 'active' AND id != ?");
                $checkActiveStmt->bind_param("si", $reward_date, $id);
                $checkActiveStmt->execute();
                $checkActiveStmt->store_result();
                
                if ($checkActiveStmt->num_rows > 0) {
                    echo json_encode(['status' => 'error', 'message' => 'An active reward configuration already exists for this date.']);
                    $checkActiveStmt->close();
                    exit;
                }
                $checkActiveStmt->close();
            }
        }

        $updateStmt = $con->prepare("UPDATE spin_wheel_rewards SET status = ? WHERE id = ?");
        $updateStmt->bind_param("si", $status, $id);
        
        if ($updateStmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update status.']);
        }
        $updateStmt->close();
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid method.']);
        break;
}

$con->close();
?>