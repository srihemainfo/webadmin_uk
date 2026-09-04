<?php


include '../../include/shi-config.php';
include '../../include/functions.php';

mysqli_set_charset($con, "utf8mb4");

// Optional but recommended:
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$memid = $_SESSION['memid'];

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";


$result = array();


if ($method == "get_notification") {
    
    $sql_unread = "SELECT COUNT(*) AS total FROM notifications WHERE read_at IS NULL";
    $result_unread = mysqli_query($con, $sql_unread);
    
    $row_unread = mysqli_fetch_assoc($result_unread);
    $unread = $row_unread['total'];
    
    // 2. Get notifications: unread first, then newest first
    $sql_list = "
        SELECT *
        FROM notifications
        WHERE type = 'job.lead'
        ORDER BY 
            (read_at IS NOT NULL),
            created_at DESC
        LIMIT 20
    ";
    
    $result_list = mysqli_query($con, $sql_list);
    
    $list = [];
    while ($row = mysqli_fetch_assoc($result_list)) {
        $list[] = $row;
    }
    
    // 3. Output JSON
    header('Content-Type: application/json');
    echo json_encode([
        "unread" => (int)$unread,
        "list"   => $list
    ]);
}

if ($method == "mark_read") {
    
    $id = $_POST['id'] ?? null;

    if (!$id) {
        // If ID is missing, return a bad request status
        http_response_code(400);
        die("Error: Invalid ID provided.");
    }
    
    // --- Use Prepared Statements for Security ---
    $sql_update = "UPDATE notifications SET read_at = NOW() WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql_update);
    
    if ($stmt) {
        // Bind the ID parameter (i = integer)
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            echo "OK";
        } else {
            // Execution failed
            http_response_code(500);
            error_log("MySQLi execution failed: " . mysqli_stmt_error($stmt));
            die("Error processing request.");
        }
    
        mysqli_stmt_close($stmt);
    } else {
        // Statement preparation failed
        http_response_code(500);
        error_log("MySQLi preparation failed: " . mysqli_error($con));
        die("Error preparing statement.");
    }
}
