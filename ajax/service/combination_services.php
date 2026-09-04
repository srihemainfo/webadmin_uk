<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

include '../../include/shi-config.php';
include '../../include/functions.php';

header('Content-Type: application/json');

$method = $_REQUEST["method"] ?? "";

if ($method == "get_all_locations") {
    $query = "SELECT id, district_name, main FROM trip_locations WHERE deletes = 0 ORDER BY district_name ASC";
    $result = mysqli_query($con, $query);
    $data = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) { $data[] = $row; }
        echo json_encode(["status" => "success", "data" => $data]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($con)]);
    }
    exit;
}

else if ($method == "get_pending_destinations") {
    $from_id = (int)($_POST['from_id'] ?? 0);

    $info_q = mysqli_query($con, "SELECT district_name, main, state FROM trip_locations WHERE id = $from_id");
    $from_row = mysqli_fetch_assoc($info_q);

    $from_name = mysqli_real_escape_string($con, trim($from_row['district_name']));
    $from_state = mysqli_real_escape_string($con, trim($from_row['state']));
    $from_main = (int)$from_row['main'];

    // Ultra Fast Query: No maps, no distance logic. Just exact pairs.
    $query = "
        SELECT 
            '$from_name' as from_name, '$from_state' as from_state,
            t2.id as to_id, t2.district_name as to_name, t2.state as to_state, t2.main as to_main, 
            dpl.id as existing_id,
            IF(dpl.id IS NOT NULL, 1, 0) as is_existing
        FROM trip_locations t2
        LEFT JOIN dynamic_pages_local dpl ON LOWER(TRIM(dpl.name)) = LOWER(TRIM('$from_name')) 
             AND LOWER(TRIM(dpl.to_place)) = LOWER(TRIM(t2.district_name)) AND dpl.deletes = '0'
        WHERE t2.deletes = 0 AND t2.id != $from_id
          AND (t2.main != $from_id AND $from_main != t2.id) 
          AND (t2.main != $from_main OR t2.main = 0 OR $from_main = 0) 
        ORDER BY t2.district_name ASC
    ";

    $result = mysqli_query($con, $query);
    $data = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) { $data[] = $row; }
        echo json_encode(["status" => "success", "data" => $data]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($con)]);
    }
    exit;
}

// 3. Save Combinations (Instant, No Math, No Fare Overwrites)
else if ($method == "save_combination") {
    $from_loc_raw = trim($_POST['from_loc'] ?? '');
    $to_loc_raw = trim($_POST['to_loc'] ?? '');
    
    $from_loc = mysqli_real_escape_string($con, $from_loc_raw);
    $to_loc = mysqli_real_escape_string($con, $to_loc_raw);

    // Check if it exists exactly
    $dup_check = mysqli_query($con, "SELECT id FROM dynamic_pages_local WHERE LOWER(TRIM(name)) = LOWER(TRIM('$from_loc_raw')) AND LOWER(TRIM(to_place)) = LOWER(TRIM('$to_loc_raw'))");
    
    if (mysqli_num_rows($dup_check) > 0) {
        $row = mysqli_fetch_assoc($dup_check);
        $update_id = $row['id'];
        
        // CRITICAL FIX: Only reactivate. DO NOT overwrite kms or fare.
        $q = "UPDATE dynamic_pages_local SET deletes = '0' WHERE id = $update_id";
        mysqli_query($con, $q);
    } else {
        // Clean Slug Generation - Strict spaces-to-hyphens conversion
        $slug_raw = strtolower($from_loc_raw . '-to-' . $to_loc_raw . '-drop-taxi');
        $slug = trim(preg_replace('/[^a-z0-9]+/', '-', $slug_raw), '-');
        
        // Display Title (Keeps spaces naturally)
        $title_raw = ucwords(strtolower($from_loc_raw)) . ' to ' . ucwords(strtolower($to_loc_raw)) . ' Drop Taxi';
        $title = mysqli_real_escape_string($con, $title_raw);

        // Insert new record entirely blank for speeds. Fares will be NULL automatically if omitted.
        $q = "INSERT INTO dynamic_pages_local 
              (name, to_place, kms, country_code, slug, title, status, deletes) 
              VALUES ('$from_loc', '$to_loc', '0', 'IN', '$slug', '$title', 'active', '0')";
        mysqli_query($con, $q);
    }

    echo json_encode(["status" => "success"]);
    exit;
}

// 4. Delete Combination ONLY (Does not touch Locations table)
else if ($method == "delete_combination") {
    $id = (int)($_POST['id'] ?? 0);

    $delete_q = "UPDATE dynamic_pages_local SET deletes = '1' WHERE id = $id";
                 
    if (mysqli_query($con, $delete_q)) {
        echo json_encode(["status" => "success", "message" => "Combination deleted successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($con)]);
    }
    exit;
}

// 5. Fetch for DataTables
else if ($method == "fetch_all_combinations") {
    $query = "SELECT id, name as from_loc, to_place as to_loc FROM dynamic_pages_local WHERE deletes = '0' ORDER BY id DESC";
    $result = mysqli_query($con, $query);
    $data = array();
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) { $data[] = $row; }
    }
    
    if (ob_get_length()) ob_clean();
    echo json_encode(["data" => $data]);
    exit;
}

// Fallback Invalid Method
else {
    echo json_encode(["status" => false, "message" => "Invalid method!"]);
    exit;
}
?>