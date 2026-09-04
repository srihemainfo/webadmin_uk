<?php
// ajax/service/push_report_api.php
header('Content-Type: application/json; charset=utf-8');
ob_start();

include '../../include/shi-config.php';
include '../../include/functions.php';
include '../../include/Crypto.php';

// Enable mysqli exception mode so try/catch blocks work properly
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// IMPORTANT: Force the database connection to support 4-byte emojis
mysqli_set_charset($con, 'utf8mb4');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['method'])) {
    
    $method = $_POST['method'];

    if ($method === 'get_automation_report') {
        try {
            // Get date filters if they exist
            $fromDate = isset($_POST['from_date']) && !empty($_POST['from_date']) ? $_POST['from_date'] : null;
            $toDate = isset($_POST['to_date']) && !empty($_POST['to_date']) ? $_POST['to_date'] : null;

            // Base Query
            $sql = "
                SELECT 
                    r.id AS rule_id,
                    r.event AS event_name,
                    r.title,
                    r.message,
                    r.redirect,
                    COUNT(l.id) AS total_count,
                    SUM(CASE WHEN l.status = 'sent' THEN 1 ELSE 0 END) AS success_count,
                    SUM(CASE WHEN l.status != 'sent' THEN 1 ELSE 0 END) AS failure_count
                FROM push_automation_rules r
                LEFT JOIN push_notification_logs l ON r.id = l.rule_id 
            ";

            $params = [];
            $types = "";

            // Apply date filters dynamically to the LEFT JOIN condition
            if ($fromDate && $toDate) {
                $sql .= " AND DATE(l.sent_at) BETWEEN ? AND ? ";
                $params[] = $fromDate;
                $params[] = $toDate;
                $types .= "ss";
            } elseif ($fromDate) {
                $sql .= " AND DATE(l.sent_at) >= ? ";
                $params[] = $fromDate;
                $types .= "s";
            } elseif ($toDate) {
                $sql .= " AND DATE(l.sent_at) <= ? ";
                $params[] = $toDate;
                $types .= "s";
            }

            $sql .= " GROUP BY r.id ORDER BY r.id ASC";

            $stmt = $con->prepare($sql);
            
            // Bind parameters dynamically if filters are applied
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            
            $result = $stmt->get_result();
            $results = $result->fetch_all(MYSQLI_ASSOC);

            echo json_encode([
                'status' => true,
                'data' => $results,
                'message' => 'Report fetched successfully'
            ], JSON_UNESCAPED_UNICODE);
            exit;

        } catch (Exception $e) { 
            echo json_encode([
                'status' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
            exit;
        }
    }
    
    echo json_encode(['status' => false, 'message' => 'Invalid method']);
    exit;
}
?>