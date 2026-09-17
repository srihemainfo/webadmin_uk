<?php
include '../../include/shi-config.php';
include '../../include/functions.php';

header('Content-Type: application/json');

// DataTable Parameters
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;
$searchValue = isset($_POST['search']['value']) ? mysqli_real_escape_string($con, $_POST['search']['value']) : '';

$startDate = mysqli_real_escape_string($con, $_POST['startDate'] ?? '');
$endDate = mysqli_real_escape_string($con, $_POST['endDate'] ?? '');

// Base Condition (Excluding null/empty places)
$whereClause = " WHERE l.action IN ('location_search', 'location_search_cached') AND l.from_place IS NOT NULL AND l.from_place != '' ";

// Date Filter
if (!empty($startDate) && !empty($endDate)) {
    $whereClause .= " AND l.created_at >= '{$startDate} 00:00:00' AND l.created_at <= '{$endDate} 23:59:59' ";
}

// 1. Get Total Records (without search filter)
$totalQuery = "SELECT COUNT(l.id) as total FROM `user_activity_log` l $whereClause";
$totalResult = mysqli_query($con, $totalQuery);
$recordsTotal = mysqli_fetch_assoc($totalResult)['total'] ?? 0;

// Apply Search Filter from DataTable (if user pressed Enter)
$searchCondition = "";
if (!empty($searchValue)) {
    $searchCondition = " AND (c.name LIKE '%$searchValue%' OR c.mobile LIKE '%$searchValue%' OR l.from_place LIKE '%$searchValue%' OR l.to_place LIKE '%$searchValue%' OR l.from_city LIKE '%$searchValue%' OR l.to_city LIKE '%$searchValue%') ";
}

// 2. Get Filtered Records Count
$filteredQuery = "SELECT COUNT(l.id) as total FROM `user_activity_log` l LEFT JOIN `customer_register` c ON l.user_id = c.id $whereClause $searchCondition";
$filteredResult = mysqli_query($con, $filteredQuery);
$recordsFiltered = mysqli_fetch_assoc($filteredResult)['total'] ?? 0;

// 3. Final Data Query with Limit/Offset (Ordered by created_at DESC)
$query = "
    SELECT 
        c.name as customer_name,
        c.mobile as customer_mobile,
        l.from_place,
        l.to_place,
        l.from_city,
        l.to_city,
        l.created_at
    FROM `user_activity_log` l
    LEFT JOIN `customer_register` c ON l.user_id = c.id
    $whereClause $searchCondition
    ORDER BY l.created_at DESC
    LIMIT $start, $length
";

$result = mysqli_query($con, $query);
$data = [];

if ($result) {
    $sno = $start + 1; // Accurate serial number calculation based on page offset
    while ($row = mysqli_fetch_assoc($result)) {
        $fromCityBadge = !empty($row['from_city']) 
            ? '<span class="badge bg-primary text-white px-2 py-1">' . htmlspecialchars($row['from_city']) . '</span>' 
            : '<span class="text-muted">-</span>';
        $toCityBadge = !empty($row['to_city']) 
            ? '<span class="badge bg-success text-white px-2 py-1">' . htmlspecialchars($row['to_city']) . '</span>' 
            : '<span class="text-muted">-</span>';

        $data[] = [
            "sno" => $sno++,
            "name" => '<span class="fw-semibold text-dark">' . (!empty($row['customer_name']) ? htmlspecialchars($row['customer_name']) : '<span class="text-muted">Guest / Unknown</span>') . '</span>',
            "mobile" => !empty($row['customer_mobile']) ? htmlspecialchars($row['customer_mobile']) : '-',
            "from_city" => $fromCityBadge,
            "from_loc" => '<small class="text-muted">' . (!empty($row['from_place']) ? htmlspecialchars($row['from_place']) : '-') . '</small>',
            "to_city" => $toCityBadge,
            "to_loc" => '<small class="text-muted">' . (!empty($row['to_place']) ? htmlspecialchars($row['to_place']) : '-') . '</small>',
            "date" => '<span class="text-nowrap">' . date("d M Y, h:i:s A", strtotime($row['created_at'])) . '</span>'
        ];
    }
}

// Return formatted JSON required by Server-Side DataTables
echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $recordsTotal,
    "recordsFiltered" => $recordsFiltered,
    "data" => $data
]);
exit;
?>