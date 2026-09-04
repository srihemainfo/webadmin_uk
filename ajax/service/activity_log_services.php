<?php
include '../../include/shi-config.php';
include '../../include/functions.php';

// 1. MAIN AGGREGATED REPORT API (NOW SUPPORTS BOTH FROM AND TO)
if (isset($_POST['method']) && $_POST['method'] === 'get_location_ad_reports') {
    header('Content-Type: application/json');
    
    $draw = intval($_POST['draw']);
    $start = intval($_POST['start']);
    $length = intval($_POST['length']);
    $searchValue = mysqli_real_escape_string($con, $_POST['search']['value'] ?? '');
    
    $startDate = mysqli_real_escape_string($con, $_POST['startDate'] ?? '');
    $endDate = mysqli_real_escape_string($con, $_POST['endDate'] ?? '');

    // Extract both FROM and TO place_ids using UNION ALL
    $baseQuery = "
        SELECT 'From' as search_type, JSON_UNQUOTE(JSON_EXTRACT(meta, '$.from_place_id')) as place_id, id as log_id, created_at 
        FROM `user_activity_log` 
        WHERE meta IS NOT NULL AND JSON_UNQUOTE(JSON_EXTRACT(meta, '$.from_place_id')) IS NOT NULL AND JSON_UNQUOTE(JSON_EXTRACT(meta, '$.from_place_id')) != 'null'
        UNION ALL
        SELECT 'To' as search_type, JSON_UNQUOTE(JSON_EXTRACT(meta, '$.to_place_id')) as place_id, id as log_id, created_at 
        FROM `user_activity_log` 
        WHERE meta IS NOT NULL AND JSON_UNQUOTE(JSON_EXTRACT(meta, '$.to_place_id')) IS NOT NULL AND JSON_UNQUOTE(JSON_EXTRACT(meta, '$.to_place_id')) != 'null'
    ";

    $whereClause = " WHERE 1=1 ";

    if (!empty($startDate) && !empty($endDate)) {
        $whereClause .= " AND base.created_at >= '{$startDate} 00:00:00' AND base.created_at <= '{$endDate} 23:59:59' ";
    }

    if (!empty($searchValue)) {
        $whereClause .= " AND (
            o.name LIKE '%{$searchValue}%' OR 
            o.state LIKE '%{$searchValue}%' OR 
            o.country LIKE '%{$searchValue}%' OR
            base.search_type LIKE '%{$searchValue}%'
        ) ";
    }

    $joinQueryWithSearch = "
        FROM ($baseQuery) base 
        INNER JOIN `outstation_locations` o ON base.place_id = o.place_id
        $whereClause
    ";

    // Count Queries
    $totalQuery = mysqli_query($con, "SELECT COUNT(DISTINCT CONCAT(base.place_id, base.search_type)) AS total FROM ($baseQuery) base INNER JOIN `outstation_locations` o ON base.place_id = o.place_id");
    $totalRecords = $totalQuery ? mysqli_fetch_assoc($totalQuery)['total'] : 0;

    $filteredQuery = mysqli_query($con, "SELECT COUNT(DISTINCT CONCAT(o.place_id, base.search_type)) AS filtered_total $joinQueryWithSearch");
    $totalFiltered = $filteredQuery ? mysqli_fetch_assoc($filteredQuery)['filtered_total'] : 0;

    // Grouping Data
    $dataQuery = "
        SELECT 
            o.place_id,
            o.name AS district, 
            o.state, 
            o.country, 
            base.search_type,
            COUNT(base.log_id) AS search_count
        $joinQueryWithSearch 
        GROUP BY o.place_id, o.name, o.state, o.country, base.search_type 
        ORDER BY search_count DESC 
        LIMIT $start, $length
    ";
    
    $result = mysqli_query($con, $dataQuery);
    
    $data = [];
    $sno = $start + 1;
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Visual badges for From and To
            $typeBadge = ($row['search_type'] === 'From') 
                ? '<span class="badge bg-primary px-3 py-2">FROM</span>' 
                : '<span class="badge bg-warning text-dark px-3 py-2">TO</span>';

            // Pass both placeid AND searchtype to the button
            $btn = '<button class="btn btn-sm btn-secondary view-details-btn shadow-sm" data-placeid="'.htmlspecialchars($row['place_id']).'" data-searchtype="'.htmlspecialchars($row['search_type']).'"><i class="fa fa-eye"></i> View Customers</button>';

            $data[] = [
                "sno" => $sno++,
                "district" => !empty($row['district']) ? htmlspecialchars($row['district']) : '-',
                "state" => !empty($row['state']) ? htmlspecialchars($row['state']) : '-',
                "country" => !empty($row['country']) ? htmlspecialchars($row['country']) : 'India',
                "search_type" => $typeBadge,
                "search_count" => '<span class="badge bg-success" style="font-size:14px;">' . $row['search_count'] . ' Searches</span>',
                "action" => $btn
            ];
        }
    }

    echo json_encode([
        "draw" => $draw,
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => $totalFiltered,
        "data" => $data
    ]);
    exit;
}

// 2. DETAILED CUSTOMER LOG API
if (isset($_POST['method']) && $_POST['method'] === 'get_ad_report_details') {
    header('Content-Type: application/json');
    
    $place_id = mysqli_real_escape_string($con, $_POST['place_id']);
    $search_type = mysqli_real_escape_string($con, $_POST['search_type']); // "From" or "To"
    
    $startDate = mysqli_real_escape_string($con, $_POST['startDate'] ?? '');
    $endDate = mysqli_real_escape_string($con, $_POST['endDate'] ?? '');

    // Conditionally check either from_place_id or to_place_id based on the button clicked
    if ($search_type === 'From') {
        $whereClause = " WHERE JSON_UNQUOTE(JSON_EXTRACT(l.meta, '$.from_place_id')) = '$place_id' ";
    } else {
        $whereClause = " WHERE JSON_UNQUOTE(JSON_EXTRACT(l.meta, '$.to_place_id')) = '$place_id' ";
    }

    if (!empty($startDate) && !empty($endDate)) {
        $whereClause .= " AND l.created_at >= '{$startDate} 00:00:00' AND l.created_at <= '{$endDate} 23:59:59' ";
    }

    $query = "
        SELECT 
            c.name as customer_name,
            c.mobile as customer_mobile,
            JSON_UNQUOTE(JSON_EXTRACT(l.meta, '$.from_name')) AS from_loc,
            JSON_UNQUOTE(JSON_EXTRACT(l.meta, '$.to_name')) AS to_loc,
            l.created_at
        FROM `user_activity_log` l
        LEFT JOIN `customer_register` c ON l.user_id = c.id
        $whereClause
        ORDER BY l.id DESC
        LIMIT 500
    ";

    $result = mysqli_query($con, $query);
    $data = [];
    if ($result) {
        $sno = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = [
                "sno" => $sno++,
                "name" => $row['customer_name'] ? $row['customer_name'] : '<span class="text-muted">Guest / Unknown</span>',
                "mobile" => $row['customer_mobile'] ? $row['customer_mobile'] : '-',
                "from_loc" => $row['from_loc'] ? $row['from_loc'] : '-',
                "to_loc" => $row['to_loc'] ? $row['to_loc'] : '-',
                "date" => date("d M Y, h:i A", strtotime($row['created_at']))
            ];
        }
    }

    echo json_encode(['status' => true, 'data' => $data]);
    exit;
}
?>