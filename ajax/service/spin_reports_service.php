<?php
include '../../include/shi-config.php';

if ($con->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed.']));
}

$method = $_POST['method'] ?? '';

switch ($method) {
    case 'fetch_report':
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';

        // Base query joining daily_spin_tracker with user_register
        $query = "SELECT dst.id, dst.spin_date, dst.spin_at, dst.slot, dst.slot_data, ur.name, ur.mobile 
                  FROM daily_spin_tracker dst 
                  LEFT JOIN user_register ur ON dst.user_id = ur.id 
                  WHERE 1=1";
        
        $params = [];
        $types = "";

        // Apply Date Filters if provided
        if (!empty($start_date) && !empty($end_date)) {
            $query .= " AND dst.spin_date >= ? AND dst.spin_date <= ?";
            $params[] = $start_date;
            $params[] = $end_date;
            $types .= "ss";
        } else if (!empty($start_date)) {
            $query .= " AND dst.spin_date >= ?";
            $params[] = $start_date;
            $types .= "s";
        } else if (!empty($end_date)) {
            $query .= " AND dst.spin_date <= ?";
            $params[] = $end_date;
            $types .= "s";
        }

        // Order by latest spin first
        $query .= " ORDER BY dst.spin_at DESC";

        $stmt = $con->prepare($query);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        echo json_encode(['data' => $data]);
        $stmt->close();
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid method.']);
        break;
}

$con->close();
?>