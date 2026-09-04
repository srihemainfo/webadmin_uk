<?php
// Ensure absolutely NO spaces or empty lines exist above the <?php tag
error_reporting(0);
ini_set('display_errors', 0);

include '../../include/shi-config.php';
include '../../include/functions.php';

if (!isset($_POST['method'])) {
    echo json_encode(['status' => false, 'message' => 'No method specified.']);
    exit;
}

// =========================================================
// GLOBAL EXTRACTION: Get Target IDs for ALL requests
// =========================================================
$pushQuery = mysqli_query($con, "SELECT res_json FROM `push_notifications` WHERE id = 22676");
if (!$pushQuery) {
    $pushQuery = mysqli_query($con, "SELECT res_json FROM `push_notificationss` WHERE id = 22676");
}

$targetIds = [];
if ($pushQuery && $pushRow = mysqli_fetch_assoc($pushQuery)) {
    if (!empty($pushRow['res_json'])) {
        $resJson = json_decode($pushRow['res_json'], true);
        if (isset($resJson['not_delivered']) && is_array($resJson['not_delivered'])) {
            foreach ($resJson['not_delivered'] as $userTarget) {
                if (isset($userTarget['id'])) {
                    // FIX: Extract only numbers from strings like "Customer-2"
                    $rawId = $userTarget['id'];
                    $numericId = preg_replace('/[^0-9]/', '', $rawId); 
                    
                    if (!empty($numericId)) {
                        $targetIds[] = intval($numericId);
                    }
                }
            }
        }
    }
}

// If IDs exist, remove duplicates and join them. If not, use '0' to ensure queries return empty gracefully.
if (!empty($targetIds)) {
    $uniqueTargetIds = array_unique($targetIds);
    $idList = implode(',', $uniqueTargetIds);
} else {
    $idList = '0';
}

// ---------------------------------------------------------
// 1. Get Top Location Widgets (RESTRICTED TO TARGET IDs)
// ---------------------------------------------------------
if ($_POST['method'] === 'get_top_counts') {
    header('Content-Type: application/json');
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 12;
    
    $query = "SELECT current_state, current_district, COUNT(id) as total_users 
              FROM `customer_register` 
              WHERE id IN ($idList)
              AND current_state IS NOT NULL AND current_state != '' 
              AND current_district IS NOT NULL AND current_district != ''
              GROUP BY current_state, current_district 
              ORDER BY total_users DESC 
              LIMIT $offset, $limit";
              
    $result = mysqli_query($con, $query);
    $data = [];
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = [
                'state' => htmlspecialchars($row['current_state']),
                'district' => htmlspecialchars($row['current_district']),
                'total' => $row['total_users']
            ];
        }
    }
    echo json_encode(['status' => true, 'data' => $data]);
    exit;
}

// ---------------------------------------------------------
// 2. Get Filter Options (RESTRICTED TO TARGET IDs)
// ---------------------------------------------------------
if ($_POST['method'] === 'get_filter_options') {
    header('Content-Type: application/json');
    $states = [];
    $districts = [];
    
    $s_query = mysqli_query($con, "SELECT DISTINCT current_state FROM `customer_register` WHERE id IN ($idList) AND current_state IS NOT NULL AND current_state != '' ORDER BY current_state ASC");
    while($row = mysqli_fetch_assoc($s_query)) { $states[] = htmlspecialchars($row['current_state']); }
    
    $d_query = mysqli_query($con, "SELECT DISTINCT current_district FROM `customer_register` WHERE id IN ($idList) AND current_district IS NOT NULL AND current_district != '' ORDER BY current_district ASC");
    while($row = mysqli_fetch_assoc($d_query)) { $districts[] = htmlspecialchars($row['current_district']); }
    
    echo json_encode(['status' => true, 'states' => $states, 'districts' => $districts]);
    exit;
}

// ---------------------------------------------------------
// 3. Main DataTables (RESTRICTED TO TARGET IDs)
// ---------------------------------------------------------
if ($_POST['method'] === 'get_targeted_customers') {
    header('Content-Type: application/json');
    
    $draw = intval($_POST['draw']);
    $start = intval($_POST['start']);
    $length = intval($_POST['length']);
    $searchValue = mysqli_real_escape_string($con, $_POST['search']['value'] ?? '');

    $filterState = mysqli_real_escape_string($con, $_POST['state'] ?? '');
    $filterDistrict = mysqli_real_escape_string($con, $_POST['district'] ?? '');
    $locStatus = mysqli_real_escape_string($con, $_POST['loc_status'] ?? 'ALL');
    
    $startDate = mysqli_real_escape_string($con, $_POST['startDate'] ?? '');
    $endDate = mysqli_real_escape_string($con, $_POST['endDate'] ?? '');
    
    $baseSelect = "SELECT c.id, c.name, c.mobile, c.current_state, c.current_district, c.current_address, c.whatsapp_update,
                  (SELECT COUNT(id) FROM `user_activity_log` u WHERE u.user_id = c.id) as log_count 
                  FROM `customer_register` c";

    // RESTRICT Query strictly to the targeted failure IDs
    $whereClause = " WHERE c.id IN ($idList) ";

    // Conditionally Append State & District filters ONLY if they are selected
    if (!empty($filterState)) {
        $whereClause .= " AND c.current_state = '$filterState' ";
    }
    if (!empty($filterDistrict)) {
        $whereClause .= " AND c.current_district = '$filterDistrict' "; 
    }

    // Location Status Filter
    if ($locStatus === 'HAS_STATE') {
        $whereClause .= " AND c.current_state IS NOT NULL AND c.current_state != '' ";
    } else if ($locStatus === 'NO_STATE') {
        $whereClause .= " AND (c.current_state IS NULL OR c.current_state = '') ";
    }

    // Date Filters
    if (!empty($startDate) && !empty($endDate)) { 
        $whereClause .= " AND DATE(c.created_at) BETWEEN '$startDate' AND '$endDate' "; 
    }

    // Global Search
    if (!empty($searchValue)) {
        $whereClause .= " AND (
            c.name LIKE '%{$searchValue}%' OR 
            c.mobile LIKE '%{$searchValue}%'
        ) ";
    }

    $orderColumnMap = [
        0 => 'c.id',
        1 => 'c.name',
        2 => 'c.mobile',
        3 => 'c.current_state',
        4 => 'c.current_district',
        5 => 'log_count'
    ];
    
    $orderColIndex = $_POST['order'][0]['column'] ?? 5;
    $orderDir = $_POST['order'][0]['dir'] ?? 'DESC';
    $orderCol = $orderColumnMap[$orderColIndex] ?? 'log_count';
    $orderBy = " ORDER BY $orderCol $orderDir ";

    $totalRecords = count($targetIds);

    $filteredQuery = mysqli_query($con, "SELECT COUNT(c.id) as filtered_total FROM `customer_register` c $whereClause");
    $totalFiltered = $filteredQuery ? mysqli_fetch_assoc($filteredQuery)['filtered_total'] : 0;

    $dataQuery = $baseSelect . $whereClause . $orderBy . " LIMIT $start, $length";
    $result = mysqli_query($con, $dataQuery);
    
    $data = [];
    $sno = $start + 1;
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $stateUI = !empty($row['current_state']) ? htmlspecialchars($row['current_state']) : '<span class="text-muted fst-italic">Not Updated</span>';
            $districtUI = !empty($row['current_district']) ? htmlspecialchars($row['current_district']) : '<span class="text-muted fst-italic">Not Updated</span>';
            
            $btn = '<button class="btn-action view-activity-btn" data-userid="'.intval($row['id']).'" data-username="'.htmlspecialchars($row['name']).'">
                        <i class="fa fa-list-alt"></i> View Activity
                    </button>';

            $data[] = [
                "sno" => $sno++,
                "name" => !empty($row['name']) ? htmlspecialchars($row['name']) : 'Guest / Unknown',
                "mobile" => htmlspecialchars($row['mobile']) . $whatsappBadge,
                "state" => $stateUI,
                "district" => $districtUI,
                "log_count" => '<span class="badge" style="background:#475569;">' . intval($row['log_count']) . ' Logs</span>',
                "action" => $btn
            ];
        }
    }

    echo json_encode([
        "draw" => $draw,
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => empty($totalFiltered) ? 0 : (int) $totalFiltered,
        "data" => $data
    ]);
    exit;
}

// ---------------------------------------------------------
// 4. Detailed Customer Activity Logs
// ---------------------------------------------------------
if ($_POST['method'] === 'get_customer_activity') {
    header('Content-Type: application/json');
    
    $user_id = intval($_POST['user_id']);
    
    $query = "SELECT module, action, meta, ip_address, created_at 
              FROM `user_activity_log` 
              WHERE user_id = $user_id 
              ORDER BY id DESC LIMIT 200"; 
              
    $result = mysqli_query($con, $query);
    $data = [];
    
    if ($result) {
        $sno = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $fromLoc = '-';
            $toLoc = '-';
            if (!empty($row['meta'])) {
                $metaArr = json_decode($row['meta'], true);
                if (is_array($metaArr)) {
                    $fromLoc = isset($metaArr['from_name']) ? htmlspecialchars($metaArr['from_name']) : '';
                    $toLoc = isset($metaArr['to_name']) ? htmlspecialchars($metaArr['to_name']) : '';
                }
            }

            $data[] = [
                "sno" => $sno++,
                "module" => !empty($row['module']) ? htmlspecialchars($row['module']) : 'System',
                "action" => !empty($row['action']) ? htmlspecialchars(str_replace('_', ' ', strtoupper($row['action']))) : '-',
                "from_loc" => $fromLoc,
                "to_loc" => $toLoc,
                "ip" => htmlspecialchars($row['ip_address']),
                "date" => date("d M Y, h:i A", strtotime($row['created_at']))
            ];
        }
    }

    echo json_encode(['status' => true, 'data' => $data]);
    exit;
}