<?php
include '../../include/shi-config.php';
include '../../include/functions.php';
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
header('Content-Type: application/json');

$method = isset($_REQUEST["method"]) ? $_REQUEST["method"] : "";

function appendUserEditLog($con, $user_id, $edited_by, $changes)
{

    if (empty($changes)) return;

    $logQuery = mysqli_query($con, "SELECT edit_log FROM user_register WHERE id = '$user_id'");
    $row = mysqli_fetch_assoc($logQuery);
    $existingLog = [];

    if (!empty($row['edit_log'])) {
        $decoded = json_decode($row['edit_log'], true);
        if (is_array($decoded)) $existingLog = $decoded;
    }

    $existingLog[] = [
        "edited_by" => $edited_by,
        "edited_at" => date("Y-m-d H:i:s"),
        "details"   => $changes
    ];

    $newLogJson = mysqli_real_escape_string($con, json_encode($existingLog));

    mysqli_query($con, "UPDATE user_register SET edit_log = '$newLogJson' WHERE id = '$user_id'");
}

if ($method == "update_driver_profile") {
    // 1. Setup & Auth
    $dubaidate_time = date('d-m-Y h:i:s A'); // Format for edit_log

    $driver_id = BlockSQLInjection($_POST['driver_id'] ?? '');
    $edited_by = $_SESSION['memid'] ?? '0';

    if ($driver_id == '') {
        echo json_encode(["status" => false, "message" => "Driver ID Not Found"]);
        exit;
    }

    // Get the role of the user performing the edit (to restrict email/mobile updates)
    $roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$edited_by' and `deletes`='0'", "roll_id", "");

    // 2. Sanitize Inputs
    $name         = BlockSQLInjection($_POST['name'] ?? '');
    $mobile       = BlockSQLInjection($_POST['mobile'] ?? '');
    $age          = BlockSQLInjection($_POST['age'] ?? '');
    $email        = BlockSQLInjection($_POST['email'] ?? '');
    $state        = BlockSQLInjection($_POST['state'] ?? '');
    $districts_id = BlockSQLInjection($_POST['districts_id'] ?? '');
    $exp          = trim(BlockSQLInjection($_POST['exp'] ?? ''));
    $languages    = BlockSQLInjection($_POST['languages'] ?? ''); // e.g. "Tamil,English"

    // ADDRESS SANITIZATION (Strips enters/newlines so the JS variable doesn't break on frontend)
    $clean_address = '';
    if (!empty($_POST['address'])) {
        $clean_address = str_replace(["\r\n", "\r", "\n", "\t"], " ", $_POST['address']);
        $clean_address = preg_replace('/\s+/', ' ', $clean_address);
    }
    $address = BlockSQLInjection(trim($clean_address));

    // 3. Unique Checks (Email & Mobile)
    if (!empty($email)) {
        $emailCheck = select_query($con, "user_register", "id", "`email` = '$email' AND `id` != '$driver_id' AND `deletes`='0'", "", "");
        if ($emailCheck['nr'] > 0) {
            echo json_encode(["status" => false, "message" => "Email ID already exists!"]);
            exit;
        }
    }

    if (!empty($mobile)) {
        // Checking roll_id = '0' typically isolates driver accounts in your system
        $checkMobile = select_query($con, "user_register", "id", "`mobile` = '$mobile' AND `id` != '$driver_id' AND `deletes`='0' AND roll_id = '0'", "", "");
        if ($checkMobile['nr'] > 0) {
            echo json_encode(["status" => false, "message" => "Mobile number already exists!"]);
            exit;
        }
    }

    // 4. Fetch Old Data
    $get_user_data_log = select_query($con, "user_register", "", "`id`='$driver_id'", "", "");
    $oldData = ($get_user_data_log['nr'] > 0) ? $get_user_data_log['result'][0] : [];

    // Fetch OCR data specifically to track the old Experience value accurately
    $getOCR = select_query($con, "ocr_request", "exp", "user_id = '$driver_id' AND doc_type = 'DRIVING_LICENSE' AND status IS NOT NULL ORDER BY id DESC LIMIT 1", "", "");
    $old_exp = ($getOCR['nr'] > 0) ? $getOCR['result'][0]['exp'] : '';

    // 5. Track Changes (Diffing Engine)
    $changes = [];
    $cleanString = function ($str) {
        if (!is_string($str)) $str = (string)$str;
        $str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');
        return trim(preg_replace('/[\x00-\x1F\x7F]/u', ' ', $str));
    };

    function trackChange(&$changes, $field, $old, $new, $cleanString)
    {
        $cOld = $cleanString($old);
        $cNew = $cleanString($new);
        if ($cOld === '0' || strtolower($cOld) === 'null') $cOld = '';
        if ($cNew === '0' || strtolower($cNew) === 'null') $cNew = '';

        if ($cOld !== $cNew) {
            $changes[$field] = ["old" => $cOld, "new" => $cNew];
        }
    }

    trackChange($changes, 'name', $oldData['name'] ?? '', $name, $cleanString);
    trackChange($changes, 'age', $oldData['age'] ?? '', $age, $cleanString);
    trackChange($changes, 'state', $oldData['state'] ?? '', $state, $cleanString);
    trackChange($changes, 'districts_id', $oldData['districts_id'] ?? '', $districts_id, $cleanString);
    trackChange($changes, 'address', $oldData['address'] ?? '', $address, $cleanString);
    trackChange($changes, 'exp', $old_exp, $exp, $cleanString);

    // Only track mobile/email if the user is authorized to change them
    if ($roll_id == 1) {
        trackChange($changes, 'email', $oldData['email'] ?? '', $email, $cleanString);
        trackChange($changes, 'mobile', $oldData['mobile'] ?? '', $mobile, $cleanString);
    }

    // Handle Vehicle Details JSON (For Languages)
    $vehicle_details = !empty($oldData['vehicle_details']) ? json_decode($oldData['vehicle_details'], true) : [];
    if (!is_array($vehicle_details)) $vehicle_details = [];
    if (!isset($vehicle_details['user_info'])) $vehicle_details['user_info'] = [];

    $oldLanguage = $vehicle_details['user_info']['language'] ?? '';
    trackChange($changes, 'language', $oldLanguage, $languages, $cleanString);

    $vehicle_details['user_info']['language'] = $languages;
    $newVehicleJson = json_encode($vehicle_details, JSON_UNESCAPED_SLASHES);

    // 6. Manage Edit Log
    $current_log_json = $oldData['edit_log'] ?? '[]';
    $current_log_array = json_decode($current_log_json, true);
    if (!is_array($current_log_array)) $current_log_array = [];

    if (!empty($changes)) {
        $current_log_array[] = [
            'edited_by' => $edited_by,
            'edited_at' => $dubaidate_time,
            'details'   => $changes
        ];
    }
    $new_log_json = json_encode($current_log_array);

    // ==========================================
    // ðŸ”¥ FIX: Escaping JSON strings for SQL safely
    // ==========================================
    $safeVehicleJson = mysqli_real_escape_string($con, $newVehicleJson);
    $safeLogJson = mysqli_real_escape_string($con, $new_log_json);

    // 7. Update Arrays & Execute
    $user_Update_arr = [
        'name'            => $name,
        'age'             => $age,
        'state'           => $state,
        'districts_id'    => $districts_id,
        'address'         => $address,
        'vehicle_details' => $safeVehicleJson, // Uses safe variable
        'edit_log'        => $safeLogJson      // Uses safe variable
    ];

    // Only allow Email/Mobile updates if the editor is an Admin (roll_id = 1)
    if ($roll_id == 1) {
        $user_Update_arr['email'] = $email;
        $user_Update_arr['mobile'] = $mobile;
    }

    update($con, "user_register", "`id` = '$driver_id'", $user_Update_arr, "", "", "", "");

    // Update experience in ocr_request
    if ($exp !== '') {
        update($con, "ocr_request", "`user_id` = '$driver_id'", ['exp' => $exp], "", "", "", "");
    }

    echo json_encode(["status" => true, "message" => "Driver profile updated successfully"]);
    exit;
}
// Paste this inside ajax/service/driverServices.php

 elseif ($method == 'get_cab_seat_capacity') {
    $cab_type = isset($_POST['cab_type']) ? mysqli_real_escape_string($con, $_POST['cab_type']) : '';

    if (!empty($cab_type)) {
        // Query to get seat capacity based on cab_types table
        $query = "SELECT seat_capacity FROM cab_types WHERE name = '$cab_type' LIMIT 1";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            echo json_encode([
                'status' => true, 
                'data' => [
                    'seat_capacity' => $row['seat_capacity']
                ]
            ]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Cab type not found']);
        }
    } else {
        echo json_encode(['status' => false, 'message' => 'Invalid cab type provided']);
    }
    exit;
}


elseif ($method === "get_driver_current_location") {
    // Clean output buffer to ensure valid JSON
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');

    $driver_id = mysqli_real_escape_string($con, $_POST['driver_id']);

    // Query the drivers_current_location table, ordering by newest first
    $query = mysqli_query($con, "SELECT lat, lng, updated_at FROM drivers_current_location WHERE user_id = '$driver_id' ORDER BY updated_at DESC LIMIT 1");

    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        // Format the date nicely for the tooltip
        $row['updated_at'] = date('d M Y, h:i A', strtotime($row['updated_at']));

        echo json_encode(['status' => true, 'data' => $row]);
    } else {
        echo json_encode(['status' => false, 'message' => 'No location data found.']);
    }
    exit;
} elseif ($method === "fetch_driver_history") {
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');

    $driver_id = $_POST['driver_id'] ?? '';
    $start_datetime = $_POST['start_datetime'];
    $end_datetime = $_POST['end_datetime'];

    if (empty($start_datetime) || empty($end_datetime)) {
        echo json_encode(['status' => false, 'message' => 'Missing parameters.']);
        exit;
    }

    if (!empty($driver_id)) {
        // Fast single driver query
        $q = "SELECT lat, lng, recorded_at AS updated_at, user_id,
                     DATE_FORMAT(recorded_at, '%d %b %Y, %h:%i:%s %p') AS formatted_time
              FROM drivers_location_logs
              WHERE user_id = ? 
                AND recorded_at BETWEEN ? AND ?
              ORDER BY recorded_at ASC";
        $stmt = mysqli_prepare($con, $q);
        mysqli_stmt_bind_param($stmt, 'iss', $driver_id, $start_datetime, $end_datetime);
    } else {
        // Optimised all-drivers query
        $q = "SELECT l1.user_id, l1.lat, l1.lng, l1.recorded_at AS updated_at,
                     DATE_FORMAT(l1.recorded_at, '%d %b %Y, %h:%i:%s %p') AS formatted_time
              FROM drivers_location_logs l1
              INNER JOIN (
                  SELECT user_id, MAX(recorded_at) AS max_recorded_at
                  FROM drivers_location_logs
                  WHERE recorded_at BETWEEN ? AND ?
                  GROUP BY user_id
              ) l2 ON l1.user_id = l2.user_id AND l1.recorded_at = l2.max_recorded_at";
        $stmt = mysqli_prepare($con, $q);
        mysqli_stmt_bind_param($stmt, 'ss', $start_datetime, $end_datetime);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $historyData = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode(['status' => true, 'type' => !empty($driver_id) ? 'single' : 'all', 'data' => $historyData]);
    } else {
        $msg = !empty($driver_id) ? 'No travel history found.' : 'No drivers active in this timeframe.';
        echo json_encode(['status' => false, 'message' => $msg]);
    }
    exit;
}
if ($method === "get_coords_by_place_id") {
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');

    $from_place_id = mysqli_real_escape_string($con, $_POST['from_place_id']);
    $to_place_id = mysqli_real_escape_string($con, $_POST['to_place_id']);

    $data = ['from_lat' => null, 'from_lng' => null, 'to_lat' => null, 'to_lng' => null];

    // Fetch from outstation_locations table using Place ID
    $query = mysqli_query($con, "SELECT place_id, latitude, longitude FROM outstation_locations WHERE place_id IN ('$from_place_id', '$to_place_id')");

    if ($query) {
        while ($row = mysqli_fetch_assoc($query)) {
            if ($row['place_id'] == $from_place_id) {
                $data['from_lat'] = $row['latitude'];
                $data['from_lng'] = $row['longitude'];
            }
            if ($row['place_id'] == $to_place_id) {
                $data['to_lat'] = $row['latitude'];
                $data['to_lng'] = $row['longitude'];
            }
        }
    }

    if ($data['from_lat'] && $data['to_lat']) {
        echo json_encode(['status' => true, 'data' => $data]);
    } else {
        echo json_encode(['status' => false, 'message' => 'Coordinates not found in database']);
    }
    exit;
} 
elseif ($method === "get_driver_location_logs") {
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');

    $driverId = (int)$_POST['driver_id'];
    $dateFilter = isset($_POST['date']) ? mysqli_real_escape_string($con, $_POST['date']) : date('Y-m-d');

    // Use prepared statements for security
    $query = "SELECT lat, lng, updated_at 
              FROM drivers_current_location 
              WHERE user_id = ? 
              AND DATE(updated_at) = ? 
              ORDER BY updated_at DESC LIMIT 300";

    $stmt = $con->prepare($query);
    $stmt->bind_param("is", $driverId, $dateFilter);
    $stmt->execute();
    $result = $stmt->get_result();

    $logs = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Format for DataTables sorting and display
            $row['formatted_time'] = date('h:i A', strtotime($row['updated_at']));
            $row['raw_timestamp'] = strtotime($row['updated_at']);
            $logs[] = $row;
        }
    }

    if (empty($logs)) {
        echo json_encode(['status' => false, 'message' => 'No tracking data found for this date.']);
    } else {
        echo json_encode(['status' => true, 'data' => $logs]);
    }
    $stmt->close();
    exit;} 
elseif ($method === "fetch_edit_logs") {
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');
    $userid = mysqli_real_escape_string($con, $_POST['user_id']);
    
    $district_map = [];
    $dist_query = mysqli_query($con, "SELECT id, district_name FROM districts");
    while ($d = mysqli_fetch_assoc($dist_query)) {
        $district_map[$d['id']] = $d['district_name'];
    }
    
    $agent_map = [];
    $agent_query = mysqli_query($con, "SELECT id, name, lname FROM user_register WHERE roll_id = 3");
    if ($agent_query) {
        while ($a = mysqli_fetch_assoc($agent_query)) {
            $agent_map[$a['id']] = trim($a['name'] . ' ' . $a['lname']);
        }
    }
    
    $edit_log_data = [];
    $log_query = mysqli_query($con, "SELECT edit_log FROM user_register WHERE id = '$userid'");
    if ($log_row = mysqli_fetch_assoc($log_query)) {
        $edit_log_json = $log_row['edit_log'] ?? '';
        if (!empty($edit_log_json)) {
            $edit_log_data = json_decode($edit_log_json, true);
            if (!is_array($edit_log_data)) $edit_log_data = [];
        }
    }
    
    $edit_log_data = array_reverse($edit_log_data);
    $editor_ids = [];
    foreach ($edit_log_data as $log) {
        if (!empty($log['edited_by'])) $editor_ids[] = (int)$log['edited_by'];
    }
    
    $editor_ids = array_unique($editor_ids);
    $editor_names = [];
    if (!empty($editor_ids)) {
        $ids_str = implode(',', $editor_ids);
        $name_query = mysqli_query($con, "SELECT id, name FROM user_register WHERE id IN ($ids_str)");
        while ($name_row = mysqli_fetch_assoc($name_query)) {
            $editor_names[$name_row['id']] = $name_row['name'];
        }
    }
    
    if (!function_exists('getFriendlyName')) {
        function getFriendlyName($key)
        {
            $mapping = [
                'name' => 'Full Name',
                'mobile' => 'Mobile Number',
                'email' => 'Email Address',
                'exp' => 'Driver Experience',
                'billing_address' => 'State',
                'districts_id' => 'District',
                'address_us' => 'Address',
                'language' => 'Language',
                'user_role' => 'User Role',
                'li_upto' => 'License Expiry',
                'dl_expiry' => 'License Expiry',
                'license_type' => 'License Type',
                'cab_type' => 'Vehicle Model',
                'fuel_types' => 'Fuel Type',
                'seat' => 'Seat Capacity',
                'Luggage' => 'Luggage Capacity',
                'insurance_upto' => 'Insurance Expiry',
                'rc_upto' => 'RC Expiry',
                'puc_upto' => 'PUC Expiry',
                'price_per_km' => 'Price/KM',
                'extra_price_per_km' => 'Extra Price/KM',
                'price_per_hour' => 'Price/Hour',
                'price_per_day' => 'Price/Day',
                'upi_id' => 'UPI ID',
                'reviews' => 'Feedback Review',
                'per_km' => 'Price Per KM',
                'company_name' => 'Company Name',
                'fuel' => 'Fuel',
                'state' => 'State',
                'per_hour' => 'Extra Price Per Hour',
                'upiID' => 'UPI ID',
                'address' => 'Address',
                'per_day' => 'Per Day',
                'extra_per_km' => 'Extra Price Per KM',
                'remarks' => 'Remarks',
                'car_colour' => 'Car Color',
                'agent_id' => 'Agent',
                'maker_model' => 'Maker Model',
                'vehicle_details.rc_details.response.vehicle_details.fit_up_to' => 'RC Expiry Date',
                'vehicle_details.rc_details.response.vehicle_details.seat_capacity' => 'Seat Capacity',
                'vehicle_details.rc_details.response.permit_details.pucc_upto' => 'PUC Expiry Date',
                'vehicle_details.rc_details.response.finance_details.insurance_upto' => 'Insurance Expiry',
                'vehicle_details.rc_details.response.vehicle_details.fuel_type' => 'Fuel Type',
                'vehicle_details.rc_details.response.vehicle_details.body_type' => 'Vehicle Body Type',
                'vehicle_details.rc_details.response.vehicle_details.maker_model' => 'Maker Model',
                'vehicle_details.rc_details.response.vehicle_details.color' => 'Car Colour',
                'vehicle_details.user_info.luggage' => 'Luggage Capacity',
                'vehicle_details.user_info.language' => 'Language'
            ];
            return $mapping[$key] ?? $key;
        }
    }

    if (!function_exists('formatLogDate')) {
        function formatLogDate($val)
        {
            if (is_string($val) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $val)) {
                return date('d-m-Y', strtotime($val));
            }
            return $val;
        }
    }

    if (!function_exists('get_recursive_diff_html')) {
        function get_recursive_diff_html($old, $new, $parentKey)
        {
            $html = '';
            $keys = array_unique(array_merge(array_keys($old ?? []), array_keys($new ?? [])));
            foreach ($keys as $key) {
                $val1 = $old[$key] ?? null;
                $val2 = $new[$key] ?? null;
                if (is_array($val1) && is_array($val2)) {
                    $html .= get_recursive_diff_html($val1, $val2, $parentKey . '.' . $key);
                } elseif ($val1 != $val2) {
                    $fullKey = $parentKey . '.' . $key;
                    if ($fullKey === 'vehicle_details.type') {
                        continue;
                    }
                    $name = getFriendlyName($fullKey);
                    $v1 = is_array($val1) ? json_encode($val1) : htmlspecialchars(formatLogDate($val1 ?? ''));
                    $v2 = is_array($val2) ? json_encode($val2) : htmlspecialchars(formatLogDate($val2 ?? ''));
                    $v1 = ($v1 === '') ? 'N/A' : $v1;
                    $v2 = ($v2 === '') ? 'N/A' : $v2;
                    $html .= "<tr><td>{$name}</td><td>{$v1}</td><td>{$v2}</td></tr>";
                }
            }
            return $html;
        }
    }

    $output = '';
    if (empty($edit_log_data)) {
        $output = '<p class="text-center text-muted">No logs found.</p>';
    } else {
        foreach ($edit_log_data as $log) {
            $editor_id = $log['edited_by'] ?? null;
            $editor_name = $editor_names[$editor_id] ?? 'Unknown (ID: ' . $editor_id . ')';
            $date = $log['edited_at'] ?? 'N/A';
            $output .= '<div class="log-entry mb-4 p-3 border">';
            $output .= "<div><strong>Edited By: {$editor_name} - {$date}</strong></div>";
            $output .= '<table class="table table-sm table-bordered mt-2" style="font-size:13px"><thead><tr style="background:#f9f9f9"><th>FIELD</th><th>OLD VALUE</th><th>NEW VALUE</th></tr></thead><tbody>';
            if (!empty($log['details'])) {
                foreach ($log['details'] as $field => $change) {
                    if ($change['old'] == $change['new']) continue;
                    if ($field === 'vehicle_details' && is_array($change['old']) && is_array($change['new'])) {
                        $output .= get_recursive_diff_html($change['old'], $change['new'], 'vehicle_details');
                    } else {
                        $displayName = getFriendlyName($field);
                        if ($field === 'districts_id') {
                            $oldVal = $district_map[$change['old']] ?? $change['old'];
                            $newVal = $district_map[$change['new']] ?? $change['new'];
                            $oldVal = ($oldVal === '') ? 'N/A' : $oldVal;
                            $newVal = ($newVal === '') ? 'N/A' : $newVal;
                        } elseif ($field === 'agent_id') {
                            $oldVal = empty($change['old']) ? 'Unassigned' : ($agent_map[$change['old']] ?? 'Agent ID: ' . $change['old']);
                            $newVal = empty($change['new']) ? 'Unassigned' : ($agent_map[$change['new']] ?? 'Agent ID: ' . $change['new']);
                        } else {
                            $oldVal = is_array($change['old']) ? json_encode($change['old']) : htmlspecialchars(formatLogDate($change['old'] ?? ''));
                            $newVal = is_array($change['new']) ? json_encode($change['new']) : htmlspecialchars(formatLogDate($change['new'] ?? ''));
                            $oldVal = ($oldVal === '') ? 'N/A' : $oldVal;
                            $newVal = ($newVal === '') ? 'N/A' : $newVal;
                        }
                        $output .= "<tr><td>{$displayName}</td><td>{$oldVal}</td><td>{$newVal}</td></tr>";
                    }
                }
            }
            $output .= '</tbody></table></div>';
        }
    }
    echo json_encode(['html' => $output]);
    exit;
}
elseif($method=== "fetch_job_logs") {
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');
    $jobId = (int)$_POST['job_id'];
    $logs = [];
    
    $query = mysqli_query($con, "
        SELECT l.*, u.name as editor_name 
        FROM job_edit_logs l
        LEFT JOIN user_register u ON l.edited_by = u.id
        WHERE l.job_id = $jobId
        ORDER BY l.created_at DESC
    ");
    
    if ($query) {
        while ($row = mysqli_fetch_assoc($query)) {
            $row['formatted_date'] = date('d M Y - h:i A', strtotime($row['created_at']));
            $row['edit_details'] = json_decode($row['edit_details'], true);
            $logs[] = $row;
        }
    }
    echo json_encode(['status' => true, 'data' => $logs]);
    exit;
}















else if ($method === 'get_last_5_schedules') {
    $driver_id = intval($_POST['driver_id'] ?? 0);
    if ($driver_id <= 0) {
        echo json_encode(['status' => false, 'message' => 'Invalid Driver ID']);
        exit;
    }

    $sql = "SELECT from_place, to_place, dates_price 
            FROM schedule_dates 
            WHERE user_id = '$driver_id' AND deletes = 0 
            ORDER BY id DESC LIMIT 15";

    $res = mysqli_query($con, $sql);
    $data = [];

    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $datesPrice = json_decode($row['dates_price'], true);
            if (is_array($datesPrice)) {
                // Extract individual dates from the JSON object
                foreach ($datesPrice as $date => $price) {
                    $data[] = [
                        'from_place' => $row['from_place'],
                        'to_place'   => $row['to_place'],
                        'price'      => $price,
                        'original_date' => $date
                    ];
                }
            }
        }
    }

    // Sort by newest date to get the true "Last 5"
    usort($data, function ($a, $b) {
        return strtotime($b['original_date']) - strtotime($a['original_date']);
    });

    $data = array_slice($data, 0, 5);

    echo json_encode(['status' => true, 'data' => $data]);
    exit;
} else if ($method === "save_driver_schedule" || $method === "auto_save_bulk_schedule") {
    $driver_id = intval($_POST['driver_id'] ?? 0);

    // Safely Decode JSON
    $routes_raw = $_POST['routes'] ?? '[]';
    $routes = json_decode(stripslashes($routes_raw), true);
    if (!is_array($routes)) $routes = [];

    if ($driver_id <= 0) {
        echo json_encode(["status" => false, "message" => "Invalid Driver"]);
        exit;
    }

    $now = date('Y-m-d H:i:s');
    $successCount = 0;

    // Only delete existing schedules if saving from the main EDIT panel.
    if ($method === "save_driver_schedule") {
        mysqli_query($con, "DELETE FROM schedule_dates WHERE user_id = '$driver_id'");
    }

    if (count($routes) > 0) {
        foreach ($routes as $route) {
            
            $raw_from = trim($route['from'] ?? '');
            $raw_to = trim($route['to'] ?? '');

            // 🔥 FIX: If $raw_from is an ID (numeric), fetch district_name from DB
            if (is_numeric($raw_from)) {
                $query_from = mysqli_query($con, "SELECT district_name FROM districts WHERE id = " . intval($raw_from));
                if ($query_from && mysqli_num_rows($query_from) > 0) {
                    $row = mysqli_fetch_assoc($query_from);
                    $raw_from = $row['district_name'];
                }
            }

            // 🔥 FIX: If $raw_to is an ID (numeric), fetch district_name from DB
            if (is_numeric($raw_to)) {
                $query_to = mysqli_query($con, "SELECT district_name FROM districts WHERE id = " . intval($raw_to));
                if ($query_to && mysqli_num_rows($query_to) > 0) {
                    $row = mysqli_fetch_assoc($query_to);
                    $raw_to = $row['district_name'];
                }
            }

            // Now escape the actual names (e.g. "Chennai", "Coimbatore")
            $from = mysqli_real_escape_string($con, $raw_from);
            $to = mysqli_real_escape_string($con, $raw_to);
            $dates = $route['dates'] ?? [];

            // Skip empty rows
            if (empty($dates)) continue;

            $dates_price = mysqli_real_escape_string($con, json_encode($dates));

            $sql = "INSERT INTO schedule_dates (user_id, from_place, to_place, dates_price, created_at, deletes) 
                    VALUES ('$driver_id', '$from', '$to', '$dates_price', '$now', 0)";

            if (mysqli_query($con, $sql)) {
                $successCount++;
            }
        }
    }

    echo json_encode(["status" => true, "message" => "Schedules saved successfully"]);
    exit;
}else if ($method === "update_driver_remarks") {

    $driver_id = intval($_POST['driver_id'] ?? 0);
    $edited_by = intval($_SESSION['memid'] ?? 0);

    if ($driver_id <= 0) {
        echo json_encode(["status" => false, "message" => "Invalid Driver ID"]);
        exit;
    }

    $oldQuery = mysqli_query($con, "
        SELECT remarks, reviews 
        FROM user_register 
        WHERE id='$driver_id'
        LIMIT 1
    ");
    $oldData = mysqli_fetch_assoc($oldQuery);

    $remarks = mysqli_real_escape_string($con, $_POST['remarks'] ?? '');
    $review  = mysqli_real_escape_string($con, $_POST['review'] ?? '');

    $changes = [];

    if ($oldData['remarks'] != $remarks) {
        $changes['remarks'] = [
            "old" => $oldData['remarks'],
            "new" => $remarks
        ];
    }

    if ($oldData['reviews'] != $review) {
        $changes['reviews'] = [
            "old" => $oldData['reviews'],
            "new" => $review
        ];
    }

    mysqli_query($con, "
        UPDATE user_register 
        SET remarks='$remarks', reviews='$review'
        WHERE id='$driver_id'
    ");

    appendUserEditLog($con, $driver_id, $edited_by, $changes);

    echo json_encode(["status" => true, "message" => "Remarks updated"]);
    exit;
} else if ($method === "update_driver_fare") {

    $driver_id = intval($_POST['driver_id'] ?? 0);
    $edited_by = intval($_SESSION['memid'] ?? 0);

    if ($driver_id <= 0) {
        echo json_encode(["status" => false, "message" => "Invalid Driver ID"]);
        exit;
    }

    $oldQuery = mysqli_query($con, "
        SELECT per_km, extra_per_km, per_hour, per_day
        FROM user_register
        WHERE id = '$driver_id'
        LIMIT 1
    ");
    $oldData = mysqli_fetch_assoc($oldQuery);

    $per_km        = floatval($_POST['per_km'] ?? 0);
    $extra_per_km  = floatval($_POST['extra_per_km'] ?? 0);
    $per_hour      = floatval($_POST['per_hour'] ?? 0);
    $per_day       = floatval($_POST['per_day'] ?? 0);

    $changes = [];

    function trackFare(&$changes, $field, $old, $new)
    {
        if ((string)$old !== (string)$new) {
            $changes[$field] = [
                "old" => $old,
                "new" => $new
            ];
        }
    }

    trackFare($changes, 'per_km', $oldData['per_km'], $per_km);
    trackFare($changes, 'extra_per_km', $oldData['extra_per_km'], $extra_per_km);
    trackFare($changes, 'per_hour', $oldData['per_hour'], $per_hour);
    trackFare($changes, 'per_day', $oldData['per_day'], $per_day);

    mysqli_query($con, "
        UPDATE user_register 
        SET per_km='$per_km',
            extra_per_km='$extra_per_km',
            per_hour='$per_hour',
            per_day='$per_day'
        WHERE id='$driver_id'
    ");

    appendUserEditLog($con, $driver_id, $edited_by, $changes);

    echo json_encode(["status" => true, "message" => "Fare updated successfully"]);
    exit;
} else if ($method === "update_driver_payment") {

    $driver_id = intval($_POST['driver_id'] ?? 0);
    $edited_by = intval($_SESSION['memid'] ?? 0);

    if ($driver_id <= 0) {
        echo json_encode(["status" => false, "message" => "Invalid Driver ID"]);
        exit;
    }

    $oldQuery = mysqli_query($con, "
        SELECT upiID 
        FROM user_register
        WHERE id='$driver_id'
        LIMIT 1
    ");
    $oldData = mysqli_fetch_assoc($oldQuery);

    $upiID = mysqli_real_escape_string($con, $_POST['upiID'] ?? '');

    $changes = [];

    if ($oldData['upiID'] !== $upiID) {
        $changes['upiID'] = [
            "old" => $oldData['upiID'],
            "new" => $upiID
        ];
    }

    mysqli_query($con, "
        UPDATE user_register 
        SET upiID='$upiID'
        WHERE id='$driver_id'
    ");

    appendUserEditLog($con, $driver_id, $edited_by, $changes);

    echo json_encode(["status" => true, "message" => "Payment updated successfully"]);
    exit;
} else if ($method === "get_driver_schedule") {
    $driver_id = intval($_POST['driver_id'] ?? 0);

    if ($driver_id <= 0) {
        echo json_encode(["status" => false, "message" => "Invalid Driver ID"]);
        exit;
    }

    // Fetch schedules for the driver where it's not deleted
    $query = mysqli_query($con, "SELECT from_place, to_place, dates_price FROM schedule_dates WHERE user_id = '$driver_id' AND deletes = 0");

    $formattedSchedules = [];
    $today = strtotime(date('Y-m-d')); // Get today's timestamp to filter out past jobs

    while ($row = mysqli_fetch_assoc($query)) {
        $datesPrice = json_decode($row['dates_price'], true);

        if (is_array($datesPrice)) {
            foreach ($datesPrice as $date => $price) {
                // Only show today's and future schedules
                if (strtotime($date) >= $today) {
                    $formattedSchedules[] = [
                        'from'  => $row['from_place'],
                        'to'    => $row['to_place'],
                        'date'  => $date,
                        'price' => $price
                    ];
                }
            }
        }
    }

    // Sort the schedules by date (closest date first)
    usort($formattedSchedules, function ($a, $b) {
        return strtotime($a['date']) - strtotime($b['date']);
    });

    echo json_encode([
        "status" => true,
        "data" => $formattedSchedules,
        "message" => "Schedules fetched successfully"
    ]);
    exit;
} else if ($method === "update_driver_vehicle") {

    $driver_id = intval($_POST['driver_id'] ?? 0);
    $edited_by = intval($_SESSION['memid'] ?? 0);

    if ($driver_id <= 0) {
        echo json_encode(["status" => false, "message" => "Invalid Driver ID"]);
        exit;
    }

    // Fetch existing JSON safely
    $query = mysqli_query($con, "SELECT vehicle_details FROM user_register WHERE id = '$driver_id' LIMIT 1");
    $row = mysqli_fetch_assoc($query);

    $vehicle_details = json_decode($row['vehicle_details'] ?? '{}', true);
    if (!is_array($vehicle_details)) {
        $vehicle_details = [];
    }
    $oldVehicle = $vehicle_details;

    // --- 1. SAFELY INITIALIZE NESTED ARRAYS ---
    if (!isset($vehicle_details['rc_details'])) $vehicle_details['rc_details'] = [];
    if (!isset($vehicle_details['rc_details']['response'])) $vehicle_details['rc_details']['response'] = [];
    if (!isset($vehicle_details['rc_details']['response']['vehicle_details'])) $vehicle_details['rc_details']['response']['vehicle_details'] = [];
    if (!isset($vehicle_details['rc_details']['response']['finance_details'])) $vehicle_details['rc_details']['response']['finance_details'] = [];
    if (!isset($vehicle_details['rc_details']['response']['permit_details'])) $vehicle_details['rc_details']['response']['permit_details'] = [];
    if (!isset($vehicle_details['user_info'])) $vehicle_details['user_info'] = [];
    if (!isset($vehicle_details['insurance_details'])) $vehicle_details['insurance_details'] = [];
    if (!isset($vehicle_details['vehicle'])) $vehicle_details['vehicle'] = [];

    // --- 2. MAP PAYLOAD TO JSON EXACTLY ---
    $vehicle_details['rc_details']['response']['vehicle_details']['body_type'] = $_POST['vehicle_type'] ?? null;
    $vehicle_details['type'] = $_POST['vehicle_type'] ?? null;
    $vehicle_details['rc_details']['response']['vehicle_details']['maker_model'] = $_POST['maker_model'] ?? null;

    // JS sends "fuel"
    $fuel_val = $_POST['fuel'] ?? null;
    $vehicle_details['rc_details']['response']['vehicle_details']['fuel_type'] = $fuel_val;

    // JS sends "seating" and "luggage"
    $seating_val = intval($_POST['seating'] ?? 0);
    $vehicle_details['rc_details']['response']['vehicle_details']['seat_capacity'] = $seating_val;
    $vehicle_details['user_info']['luggage'] = $_POST['luggage'] ?? null;

    // JS sends "rc_expiry" and "insurance_expiry"
    if (!empty($_POST['rc_expiry'])) {
        $vehicle_details['rc_expiry_date'] = $_POST['rc_expiry'];
        $vehicle_details['rc_details']['response']['vehicle_details']['fit_up_to'] = $_POST['rc_expiry'];
    }
    if (!empty($_POST['insurance_expiry'])) {
        $vehicle_details['insurance_details']['insurance_exp_date'] = $_POST['insurance_expiry'];
        $vehicle_details['rc_details']['response']['finance_details']['insurance_upto'] = $_POST['insurance_expiry'];
    }

    // --- 3. 6 IMAGE URLS UPDATE ---
    $imgFields = ['front_view_image_url', 'boot_image_url', 'extra_image_1_url', 'car_top_view_image_url', 'interior_front_image_url', 'special_features_image_url'];
    foreach ($imgFields as $field) {
        if (!empty($_POST[$field])) {
            $vehicle_details['vehicle'][$field] = $_POST[$field];
        }
    }

    $changes = [];
    if ($oldVehicle != $vehicle_details) {
        $changes['vehicle_details'] = ["old" => $oldVehicle, "new" => $vehicle_details];
    }

    $newJson = mysqli_real_escape_string($con, json_encode($vehicle_details, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    $fuel_sql = mysqli_real_escape_string($con, $fuel_val);

    // --- 4. UPDATE DB ---
    $sql = "UPDATE user_register 
            SET vehicle_details = '$newJson',
                seaters = '$seating_val',
                fuel_type = '$fuel_sql'
            WHERE id = '$driver_id'";

    if (!mysqli_query($con, $sql)) {
        echo json_encode(["status" => false, "message" => "Database Error: " . mysqli_error($con)]);
        exit;
    }

    if (function_exists('appendUserEditLog')) {
        appendUserEditLog($con, $driver_id, $edited_by, $changes);
    }

    echo json_encode(["status" => true, "message" => "Vehicle updated successfully"]);
    exit;
}
else if ($method === "update_driver_license") {

    $driver_id = intval($_POST['driver_id'] ?? 0);
    $edited_by = intval($_SESSION['memid'] ?? 0);

    if ($driver_id <= 0) {
        echo json_encode(["status" => false, "message" => "Invalid Driver ID"]);
        exit;
    }

    $dl_no = mysqli_real_escape_string($con, trim($_POST['dl_no'] ?? ''));
    $new_expiry = mysqli_real_escape_string($con, trim($_POST['dl_expiry'] ?? ''));

    if (empty($dl_no) || empty($new_expiry)) {
        echo json_encode(["status" => false, "message" => "License number and expiry date are required"]);
        exit;
    }

    $kycQuery = mysqli_query($con, "
        SELECT id, dl_no, dl_expiry 
        FROM kyc_details 
        WHERE user_id = '$driver_id'
        ORDER BY id DESC
        LIMIT 1
    ");

    $kyc = mysqli_fetch_assoc($kycQuery);

    if (!$kyc) {
        echo json_encode(["status" => false, "message" => "KYC record not found"]);
        exit;
    }

    $changes = [];
    if ($kyc['dl_no'] !== $dl_no) {
        $changes['dl_no'] = ["old" => $kyc['dl_no'], "new" => $dl_no];
    }
    if ($kyc['dl_expiry'] !== $new_expiry) {
        $changes['dl_expiry'] = ["old" => $kyc['dl_expiry'], "new" => $new_expiry];
    }

    // Update both dl_no and dl_expiry
    mysqli_query($con, "
        UPDATE kyc_details
        SET dl_no = '$dl_no', 
            dl_expiry = '$new_expiry'
        WHERE id = '{$kyc['id']}'
    ");

    if (function_exists('appendUserEditLog')) {
        appendUserEditLog($con, $driver_id, $edited_by, $changes);
    }

    echo json_encode([
        "status" => true,
        "message" => "License details updated successfully"
    ]);
    exit;
} elseif ($method === "fetch_group_drivers_filtered") {
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');

    $search = isset($_POST['search']) ? mysqli_real_escape_string($con, trim($_POST['search'])) : '';
    $cab_type = isset($_POST['cab_type']) ? mysqli_real_escape_string($con, trim($_POST['cab_type'])) : '';
    $start_date = isset($_POST['start_date']) ? mysqli_real_escape_string($con, trim($_POST['start_date'])) : '';
    $end_date = isset($_POST['end_date']) ? mysqli_real_escape_string($con, trim($_POST['end_date'])) : '';

    $min_lat = (isset($_POST['min_lat']) && $_POST['min_lat'] !== '') ? (float)$_POST['min_lat'] : null;
    $max_lat = (isset($_POST['max_lat']) && $_POST['max_lat'] !== '') ? (float)$_POST['max_lat'] : null;
    $min_lng = (isset($_POST['min_lng']) && $_POST['min_lng'] !== '') ? (float)$_POST['min_lng'] : null;
    $max_lng = (isset($_POST['max_lng']) && $_POST['max_lng'] !== '') ? (float)$_POST['max_lng'] : null;

    $query = "SELECT ur.*, kd.dl_no, kd.dl_expiry, kd.selfie_url, kd.front_image, kd.back_image, kd.proof_type, kd.proof_status, 
                     drl.lat as s_lat, drl.lng as s_lang, drl.updated_at as dcl_updated_at 
              FROM user_register ur 
              INNER JOIN kyc_details kd ON kd.user_id = ur.id 
              LEFT JOIN drivers_current_location drl ON drl.user_id = ur.id 
              WHERE ur.deletes = '0' 
              AND ur.doc_verify = '1' 
              AND ur.vehicle_verify = '2' 
              AND kd.type = 'Driver' 
              AND kd.o_status = '3' 
              AND ur.vehicle_details IS NOT NULL 
              AND ur.vehicle_details != ''";

    if (!empty($search)) {
        $query .= " AND (ur.name LIKE '%$search%' OR ur.mobile LIKE '%$search%')";
    }

    if (!empty($cab_type)) {
        $clean_cab = str_replace(' ', '', strtolower($cab_type));
        $query .= " AND (REPLACE(LOWER(JSON_UNQUOTE(JSON_EXTRACT(ur.vehicle_details, '$.type'))), ' ', '') LIKE '%$clean_cab%' OR REPLACE(LOWER(CAST(ur.vehicle_details AS CHAR)), ' ', '') LIKE '%$clean_cab%')";
    }

    if (!empty($start_date) && !empty($end_date)) {
        $start = (strlen($start_date) == 10) ? $start_date . ' 00:00:00' : $start_date;
        $end = (strlen($end_date) == 10) ? $end_date . ' 23:59:59' : $end_date;
        $query .= " AND drl.updated_at >= '$start' AND drl.updated_at <= '$end'";
    }

    if ($min_lat !== null && $max_lat !== null && $min_lng !== null && $max_lng !== null) {
        $query .= " AND (drl.lat BETWEEN $min_lat AND $max_lat) AND (drl.lng BETWEEN $min_lng AND $max_lng)";
    }

    $query .= " ORDER BY ur.id DESC";

    $result = mysqli_query($con, $query);
    $data = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    if (count($data) > 0) {
        echo json_encode(['status' => true, 'data' => $data]);
    } else {
        echo json_encode(['status' => false, 'message' => 'No drivers match your filters.', 'data' => []]);
    }
    exit;
}
elseif ($method === "fetch_job_push_status") {
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');

    $job_id = isset($_POST['job_id']) ? mysqli_real_escape_string($con, trim($_POST['job_id'])) : '';
    $job_no = isset($_POST['job_no']) ? mysqli_real_escape_string($con, trim($_POST['job_no'])) : '';
    $is_assigned_job = isset($_POST['is_assigned_job']) && $_POST['is_assigned_job'] === 'true';

    if (empty($job_id)) {
        echo json_encode(['status' => false, 'message' => 'Job ID is required']);
        exit;
    }

    // FIX: If it's an assigned job (from open_jobs), grab the original ID from cus_job_temp using job_no
    if ($is_assigned_job && !empty($job_no)) {
        $lookup_query = "SELECT id FROM cus_job_temp WHERE job_no = '$job_no' LIMIT 1";
        $lookup_res = mysqli_query($con, $lookup_query);
        if ($lookup_res && mysqli_num_rows($lookup_res) > 0) {
            $lookup_row = mysqli_fetch_assoc($lookup_res);
            $job_id = $lookup_row['id']; // Override the ID for the push_notifications lookup
        }
    }

    // Extract job_id from req_json to find the matching notification
    $query = "SELECT id, title, body, res_json, created_at FROM push_notifications 
              WHERE JSON_UNQUOTE(JSON_EXTRACT(req_json, '$.job_id')) = '$job_id' 
              ORDER BY id DESC LIMIT 1";
              
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $res_json = json_decode($row['res_json'], true);
        
        if ($res_json) {
            echo json_encode([
                'status' => true,
                'notification_id' => $row['id'],
                'title' => $row['title'],
                'body' => $row['body'],
                'data' => $res_json,
                'sent_at' => date('d M Y, h:i A', strtotime($row['created_at']))
            ]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Push response data is empty.']);
        }
    } else {
        echo json_encode(['status' => false, 'message' => 'No push notification data found for this job.']);
    }
    exit;
}

else if ($method === "get_driver_details") {
    $driver_id = (int) $_POST['driver_id'];

    if (!$driver_id) {
        echo json_encode(['status' => false, 'message' => 'Invalid driver id']);
        exit;
    }
    $query = "SELECT 
            ur.id as user_id,
            ur.profile_img_url,
            ur.address,
            ur.name,
            ur.mobile,
            ur.profile_percentage,
            ur.age,
            ur.email,
            ur.state,
            ur.city,
            ur.dob,
            ur.districts_id,
            dist.district_name,
            kd.exp,
            kd.dl_no,
            kd.dl_expiry,
            kd.selfie_url,
            kd.front_image as aadhar_image_front,
            kd.back_image as aadhar_image_back,
            kd.proof_type,
            kd.proof_status,
            ur.vehicle_details,
            ur.per_km,
            ur.per_hour,
            ur.per_day,
            ur.extra_per_km,
            ur.upiID,
            ur.reviews,
            ur.remarks,
            ocr.front as license_front_image,
            ocr.back as license_back_image,
            COALESCE(NULLIF(JSON_UNQUOTE(JSON_EXTRACT(ocr.req_response, '$.NT')), ''), JSON_UNQUOTE(JSON_EXTRACT(ocr.req_response, '$.TR'))) as license_type
        FROM user_register as ur
        LEFT JOIN (
            SELECT exp, dl_no, dl_expiry, selfie_url, front_image, back_image, proof_type, proof_status, user_id 
            FROM kyc_details 
            WHERE user_id = $driver_id AND deletes = 0 
            ORDER BY id DESC LIMIT 1
        ) as kd ON kd.user_id = ur.id
        LEFT JOIN (
            SELECT front, back, req_response, user_id 
            FROM ocr_request 
            WHERE user_id = $driver_id AND doc_type = 'DRIVING_LICENSE' 
            ORDER BY id DESC LIMIT 1
        ) as ocr ON ocr.user_id = ur.id
        LEFT JOIN districts as dist ON ur.districts_id = dist.id
        WHERE ur.id = $driver_id";

    $result = mysqli_query($con, $query);

    if (!$result || mysqli_num_rows($result) === 0) {
        echo json_encode(['status' => false, 'message' => 'Driver not found']);
        exit;
    }

    $driver = mysqli_fetch_assoc($result);

    // 2. Decode vehicle_details safely
    $driver['vehicle_details'] = !empty($driver['vehicle_details'])
        ? json_decode($driver['vehicle_details'], true)
        : [];

    // 3. Ensure the proof_type is actually Aadhaar (fallback if the row is something else)
    $validAadhaarTypes = ['AADHAAR', 'AADHAR_DIGILOCKER'];
    if (!in_array($driver['proof_type'], $validAadhaarTypes)) {
        $driver['aadhar_image_front'] = null;
        $driver['aadhar_image_back']  = null;
        $driver['proof_type']         = null;
        $driver['proof_status']       = null;
    }

    // 4. Set doc_verify based on Aadhaar approval
    $driver['doc_verify'] = ($driver['proof_status'] === 'approved') ? 1 : 0;

    // Send exactly the same JSON structure expected by the frontend
    echo json_encode([
        'status' => true,
        'data' => ['driver' => $driver]
    ]);
    exit;
} else if ($method === "update_vehicle_documents") {

    $driver_id = intval($_POST['driver_id'] ?? 0);
    $edited_by = intval($_SESSION['memid'] ?? 0);

    if ($driver_id <= 0) {
        echo json_encode(["status" => false, "message" => "Invalid Driver ID"]);
        exit;
    }

    $query = mysqli_query($con, "
        SELECT vehicle_details 
        FROM user_register 
        WHERE id = '$driver_id'
        LIMIT 1
    ");

    $row = mysqli_fetch_assoc($query);

    $vehicle_details = json_decode($row['vehicle_details'], true);
    if (!is_array($vehicle_details)) {
        $vehicle_details = [];
    }

    $oldVehicle = $vehicle_details;

    // Use isset() so that if JS sends an empty string (""), it successfully overwrites and clears the DB
    if (isset($_POST['rc_number'])) {
        $vehicle_details['rc_number'] = $_POST['rc_number'];
    }

    if (isset($_POST['rc_expiry'])) {
        $vehicle_details['rc_expiry_date'] = $_POST['rc_expiry'];
    }

    if (isset($_POST['rc_front'])) {
        $vehicle_details['rc_front_image_url'] = $_POST['rc_front'];
    }

    if (isset($_POST['rc_back'])) {
        $vehicle_details['rc_back_image_url'] = $_POST['rc_back'];
    }

    if (isset($_POST['rc_details_full']) && !empty($_POST['rc_details_full'])) {
        $decodedRc = json_decode($_POST['rc_details_full'], true);
        if (is_array($decodedRc)) {
            $vehicle_details['rc_details'] = $decodedRc;
        }
    }

    if (!isset($vehicle_details['puc_details'])) {
        $vehicle_details['puc_details'] = [];
    }

    // FIXED: Now properly captures empty strings to erase PUC
    if (isset($_POST['puc_image'])) {
        $vehicle_details['puc_details']['puc_image_url'] = $_POST['puc_image'];
    }

    // FIXED: Now properly captures empty strings to erase PUC expiry
    if (isset($_POST['puc_expiry'])) {
        $vehicle_details['puc_details']['puc_exp_date'] = $_POST['puc_expiry'];
    }

    if (!isset($vehicle_details['insurance_details'])) {
        $vehicle_details['insurance_details'] = [];
    }

    // Ensure Insurance updates correctly if cleared too
    if (isset($_POST['insurance_image'])) {
        $vehicle_details['insurance_details']['insurance_image_url'] = $_POST['insurance_image'];
    }

    if (isset($_POST['insurance_expiry'])) {
        $vehicle_details['insurance_details']['insurance_exp_date'] = $_POST['insurance_expiry'];
    }

    $changes = [];

    if ($oldVehicle != $vehicle_details) {
        $changes['vehicle_details'] = [
            "old" => $oldVehicle,
            "new" => $vehicle_details
        ];
    }

    $newJson = mysqli_real_escape_string(
        $con,
        json_encode($vehicle_details, JSON_UNESCAPED_SLASHES)
    );

    mysqli_query($con, "
        UPDATE user_register
        SET vehicle_details = '$newJson'
        WHERE id = '$driver_id'
    ");

    appendUserEditLog($con, $driver_id, $edited_by, $changes);

    echo json_encode([
        "status" => true,
        "message" => "Vehicle documents updated successfully"
    ]);
    exit;
} else {
    echo json_encode(["status" => false, "message" => "Invalid method!"]);
    exit;
}
