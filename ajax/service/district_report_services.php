<?php
include '../../include/shi-config.php';
include '../../include/functions.php';

// 1. DISTRICT LOGS API (Main Table)
if (isset($_POST['method']) && $_POST['method'] === 'get_district_reports') {
    header('Content-Type: application/json');
    
    $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
    $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
    $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
    $searchValue = isset($_POST['search']['value']) ? mysqli_real_escape_string($con, $_POST['search']['value']) : '';
    
    $startDate = mysqli_real_escape_string($con, $_POST['startDate'] ?? '');
    $endDate = mysqli_real_escape_string($con, $_POST['endDate'] ?? '');
    $searchType = isset($_POST['searchType']) ? mysqli_real_escape_string($con, $_POST['searchType']) : 'From';

    $dateFilter = "";
    if (!empty($startDate) && !empty($endDate)) {
        $dateFilter = " AND created_at >= '{$startDate} 00:00:00' AND created_at <= '{$endDate} 23:59:59' ";
    }

    if ($searchType === 'From') {
        $baseQuery = "
            SELECT 
                COALESCE(NULLIF(TRIM(from_city), ''), NULLIF(TRIM(from_district), '')) AS district_name, 
                COUNT(id) AS search_count
            FROM `user_activity_log` 
            WHERE (
                (from_city IS NOT NULL AND TRIM(from_city) != '' AND LOWER(from_city) != 'null')
                OR (from_district IS NOT NULL AND TRIM(from_district) != '' AND LOWER(from_district) != 'null')
            )
            $dateFilter
            GROUP BY district_name
        ";
    } else {
        $baseQuery = "
            SELECT 
                COALESCE(NULLIF(TRIM(to_city), ''), NULLIF(TRIM(to_district), '')) AS district_name, 
                COUNT(id) AS search_count
            FROM `user_activity_log` 
            WHERE (
                (to_city IS NOT NULL AND TRIM(to_city) != '' AND LOWER(to_city) != 'null')
                OR (to_district IS NOT NULL AND TRIM(to_district) != '' AND LOWER(to_district) != 'null')
            )
            $dateFilter
            GROUP BY district_name
        ";
    }

    $totalQuery = "SELECT COUNT(*) as total FROM ($baseQuery) as t";
    $totalResult = mysqli_query($con, $totalQuery);
    $recordsTotal = mysqli_fetch_assoc($totalResult)['total'] ?? 0;

    $searchFilterQuery = "";
    if (!empty($searchValue)) {
        $searchFilterQuery = " WHERE base.district_name LIKE '%$searchValue%' ";
    }

    $filteredQuery = "SELECT COUNT(*) as total FROM ($baseQuery) base $searchFilterQuery";
    $filteredResult = mysqli_query($con, $filteredQuery);
    $recordsFiltered = mysqli_fetch_assoc($filteredResult)['total'] ?? 0;

    $dataQuery = "
        SELECT 
            base.district_name,
            base.search_count
        FROM ($baseQuery) base 
        $searchFilterQuery
        ORDER BY base.search_count DESC 
        LIMIT $start, $length
    ";
    
    $result = mysqli_query($con, $dataQuery);
    $data = [];
    $sno = $start + 1; 
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $districtNameEscaped = htmlspecialchars($row['district_name']);
            
            // Action button logic added
            $btn = '<button class="btn btn-sm btn-secondary view-details-btn shadow-sm" data-district="'.$districtNameEscaped.'" data-searchtype="'.htmlspecialchars($searchType).'"><i class="fa fa-eye"></i> View Customers</button>';

            $data[] = [
                "sno" => $sno++,
                "district_name" => $districtNameEscaped,
                "search_count" => '<span class="badge bg-success" style="font-size:14px;">' . $row['search_count'] . ' Searches</span>',
                "action" => $btn
            ];
        }
    }

    echo json_encode([
        "draw" => $draw,
        "recordsTotal" => $recordsTotal,
        "recordsFiltered" => $recordsFiltered,
        "data" => $data
    ]);
    exit;
}

// 2. DETAILED CUSTOMER LOG API (Modal Table with Date DESC sorting)
if (isset($_POST['method']) && $_POST['method'] === 'get_district_report_details') {
    header('Content-Type: application/json');
    
    $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
    $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
    $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
    $searchValue = isset($_POST['search']['value']) ? mysqli_real_escape_string($con, $_POST['search']['value']) : '';

    $district_name = mysqli_real_escape_string($con, $_POST['district_name']); 
    $search_type = mysqli_real_escape_string($con, $_POST['search_type']); 
    $startDate = mysqli_real_escape_string($con, $_POST['startDate'] ?? '');
    $endDate = mysqli_real_escape_string($con, $_POST['endDate'] ?? '');

    $whereClause = ($search_type === 'From') 
        ? " WHERE (l.from_city = '$district_name' OR l.from_district = '$district_name') " 
        : " WHERE (l.to_city = '$district_name' OR l.to_district = '$district_name') ";

    if (!empty($startDate) && !empty($endDate)) {
        $whereClause .= " AND l.created_at >= '{$startDate} 00:00:00' AND l.created_at <= '{$endDate} 23:59:59' ";
    }

    $totalQuery = "SELECT COUNT(l.id) as total FROM `user_activity_log` l $whereClause";
    $totalResult = mysqli_query($con, $totalQuery);
    $recordsTotal = mysqli_fetch_assoc($totalResult)['total'] ?? 0;

    $searchCondition = "";
    if (!empty($searchValue)) {
        $searchCondition = " AND (c.name LIKE '%$searchValue%' OR c.mobile LIKE '%$searchValue%' OR l.from_place LIKE '%$searchValue%' OR l.to_place LIKE '%$searchValue%') ";
    }

    $filteredQuery = "SELECT COUNT(l.id) as total FROM `user_activity_log` l LEFT JOIN `customer_register` c ON l.user_id = c.id $whereClause $searchCondition";
    $filteredResult = mysqli_query($con, $filteredQuery);
    $recordsFiltered = mysqli_fetch_assoc($filteredResult)['total'] ?? 0;

    // ORDER BY l.created_at DESC (Latest comes first)
    $query = "
        SELECT 
            c.name as customer_name,
            c.mobile as customer_mobile,
            l.from_place as from_loc,
            l.to_place as to_loc,
            l.created_at
        FROM `user_activity_log` l
        LEFT JOIN `customer_register` c ON l.user_id = c.id
        $whereClause $searchCondition
        ORDER BY l.created_at DESC
        LIMIT $start, $length
    ";

    $result = mysqli_query($con, $query);
    $data = [];
    $sno = $start + 1; 
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = [
                "sno" => $sno++,
                "name" => '<span class="fw-semibold text-dark">' . ($row['customer_name'] ? htmlspecialchars($row['customer_name']) : '<span class="text-muted">Guest / Unknown</span>') . '</span>',
                "mobile" => $row['customer_mobile'] ? htmlspecialchars($row['customer_mobile']) : '-',
                "from_loc" => '<small class="text-muted">' . ($row['from_loc'] ? htmlspecialchars($row['from_loc']) : '-') . '</small>',
                "to_loc" => '<small class="text-muted">' . ($row['to_loc'] ? htmlspecialchars($row['to_loc']) : '-') . '</small>',
                "date" => '<span class="text-nowrap">' . date("d M Y, h:i:s A", strtotime($row['created_at'])) . '</span>'
            ];
        }
    }

    echo json_encode([
        "draw" => $draw,
        "recordsTotal" => $recordsTotal,
        "recordsFiltered" => $recordsFiltered,
        "data" => $data
    ]);
    exit;
}
?>