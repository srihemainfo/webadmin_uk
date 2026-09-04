<?php
error_reporting(E_ALL);
ini_set('display_errors', 0); 

include '../../include/shi-config.php';
include '../../include/functions.php';
         

if(isset($_POST['method'])) {
    $method = $_POST['method'];

    // 1. Fetch States
    if($method == "fetch_states") {
        $query = "SELECT id, name FROM states WHERE country_code = 'IN' AND flag = 1 ORDER BY name ASC";
        $result = mysqli_query($con, $query);
        $data = array();
        if($result) {
            while($row = mysqli_fetch_assoc($result)) { $data[] = $row; }
            echo json_encode(["status" => "success", "data" => $data]);
        }
        exit;
    }

    // 2. Fetch Main Districts
    if($method == "fetch_districts") {
        $state = mysqli_real_escape_string($con, $_POST['state']);
        $query = "SELECT id, district_name FROM trip_locations WHERE state = '$state' AND main = 0 AND deletes = 0 ORDER BY district_name ASC";
        $result = mysqli_query($con, $query);
        $data = array();
        if($result) {
            while($row = mysqli_fetch_assoc($result)) { $data[] = $row; }
            echo json_encode(["status" => "success", "data" => $data]);
        }
        exit;
    }

    // 3. Save Location
    if($method == "save_location") {
        $state = mysqli_real_escape_string($con, $_POST['state']);
        $is_new = $_POST['is_new'] === 'true'; 
        $district_val = mysqli_real_escape_string($con, $_POST['district_val']);
        $entry_type = mysqli_real_escape_string($con, $_POST['entry_type']); 
        $spot_name_raw = isset($_POST['spot_name']) ? $_POST['spot_name'] : '';
        $aliases_raw = isset($_POST['aliases']) ? $_POST['aliases'] : '';

        // Process Aliases array - VALIDATION COMPLETELY REMOVED
        $alias_arr = [];
        if (!empty($aliases_raw)) {
            $parts = explode(',', $aliases_raw);
            foreach($parts as $part) {
                $trimmed = trim($part);
                if (!empty($trimmed)) {
                    $alias_arr[] = $trimmed;
                }
            }
        }
        
        // Silently remove identical duplicates so the database stays clean, no errors thrown!
        $alias_arr = array_unique($alias_arr); 
        $aliases_json = empty($alias_arr) ? "NULL" : "'" . mysqli_real_escape_string($con, json_encode(array_values($alias_arr))) . "'";
        $district_id = 0;

        // --- ADDING A NEW DISTRICT ---
        if ($is_new) {
            $check_dist = mysqli_query($con, "SELECT id FROM trip_locations WHERE district_name = '$district_val' AND state = '$state' AND main = 0 AND deletes = 0");
            if (mysqli_num_rows($check_dist) > 0) {
                echo json_encode(["status" => "error", "message" => "Duplicate entry: The district '$district_val' already exists!"]);
                exit;
            }

            $q = "INSERT INTO trip_locations (main, district_name, state) VALUES (0, '$district_val', '$state')";
            if(mysqli_query($con, $q)) {
                $district_id = mysqli_insert_id($con);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to create new district."]);
                exit;
            }
        } else {
            $district_id = (int) $district_val;
        }

        // --- PROCESSING CHILD SPOTS ---
        if ($entry_type == 'spot' || $is_new) {
            if (!empty($spot_name_raw)) {
                $spot_parts = explode(',', $spot_name_raw);
                $added_spots = 0;
                $skipped_spots = 0;
                $last_skipped = '';

                // Strip out duplicates silently without yelling at user
                $spot_input_arr = [];
                foreach($spot_parts as $sp) {
                    $trimmed_spot = trim($sp);
                    if (!empty($trimmed_spot) && !in_array(strtolower($trimmed_spot), array_map('strtolower', $spot_input_arr))) {
                        $spot_input_arr[] = $trimmed_spot;
                    }
                }

                foreach($spot_input_arr as $s_name) {
                    $s_name_esc = mysqli_real_escape_string($con, $s_name);
                    
                    // DB Duplicate Check for the spot name itself
                    $check_spot = mysqli_query($con, "SELECT id FROM trip_locations WHERE district_name = '$s_name_esc' AND main = $district_id AND deletes = 0");
                    if (mysqli_num_rows($check_spot) > 0) {
                        $skipped_spots++;
                        $last_skipped = $s_name;
                        continue; 
                    }

                    $sq = "INSERT INTO trip_locations (main, district_name, state, alias_names) 
                           VALUES ($district_id, '$s_name_esc', '$state', $aliases_json)";
                    if(mysqli_query($con, $sq)) $added_spots++;
                }

                if ($added_spots > 0) {
                    $msg = "Location(s) Saved Successfully!";
                    if ($skipped_spots > 0) $msg .= " ($skipped_spots duplicate spots were silently skipped).";
                    echo json_encode(["status" => "success", "message" => $msg]);
                } else if ($skipped_spots > 0) {
                    echo json_encode(["status" => "error", "message" => "Duplicate entry: The spot '$last_skipped' already exists in this district!"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "No valid spots were provided."]);
                }
            } else if ($is_new) {
                echo json_encode(["status" => "success", "message" => "New District created successfully."]);
            }
            exit;
        } 
        
        // --- ADDING ALIASES TO EXISTING DISTRICT ---
        else if ($entry_type == 'district_alias') {
            if (!empty($alias_arr)) {
                $q_chk = "SELECT alias_names FROM trip_locations WHERE id = $district_id LIMIT 1";
                $res = mysqli_query($con, $q_chk);
                if ($row = mysqli_fetch_assoc($res)) {
                    $curr = json_decode($row['alias_names'], true);
                    if(!is_array($curr)) $curr = [];
                    
                    // NO VALIDATION - Just blindly merge the arrays and silently drop exact matches
                    $merged = array_unique(array_merge($curr, $alias_arr));
                    $json_merged = "'" . mysqli_real_escape_string($con, json_encode(array_values($merged))) . "'";
                    
                    mysqli_query($con, "UPDATE trip_locations SET alias_names = $json_merged WHERE id = $district_id");
                }
            }
            echo json_encode(["status" => "success", "message" => "District Aliases Updated!"]);
            exit;
        }
    }

    // 4. Update Existing Location (Modal - Edit)
    if($method == "update_location") {
        $id = (int) $_POST['id'];
        $name = mysqli_real_escape_string($con, trim($_POST['name']));
        $aliases_raw = isset($_POST['aliases']) ? $_POST['aliases'] : '';

        // Duplicate Check: Ensure the new name doesn't conflict with siblings
        $info_q = mysqli_query($con, "SELECT main, state FROM trip_locations WHERE id = $id");
        if($info = mysqli_fetch_assoc($info_q)) {
            $main = $info['main'];
            $state = $info['state'];
            
            $dup_chk = mysqli_query($con, "SELECT id FROM trip_locations WHERE district_name = '$name' AND main = $main AND state = '$state' AND id != $id AND deletes = 0");
            if(mysqli_num_rows($dup_chk) > 0) {
                echo json_encode(["status" => "error", "message" => "Duplicate entry: The name '$name' is already taken here!"]);
                exit;
            }

            // Process Aliases & prevent duplicate typing in the Edit Box WITHOUT throwing an error
            $alias_arr = [];
            if (!empty($aliases_raw)) {
                $parts = explode(',', $aliases_raw);
                foreach($parts as $part) {
                    $trimmed = trim($part);
                    if (!empty($trimmed)) {
                        $alias_arr[] = $trimmed;
                    }
                }
            }

            $aliases_json = empty($alias_arr) ? "NULL" : "'" . mysqli_real_escape_string($con, json_encode(array_values(array_unique($alias_arr)))) . "'";

            $up_q = "UPDATE trip_locations SET district_name = '$name', alias_names = $aliases_json WHERE id = $id";
            if(mysqli_query($con, $up_q)) {
                echo json_encode(["status" => "success", "message" => "Location updated successfully!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to update location."]);
            }
        }
        exit;
    }

    // 5. Delete Location (Soft Delete)
    if($method == "delete_location") {
        $id = (int) $_POST['id'];
        
        mysqli_query($con, "UPDATE trip_locations SET deletes = 1 WHERE id = $id");
        mysqli_query($con, "UPDATE trip_locations SET deletes = 1 WHERE main = $id");
        
        echo json_encode(["status" => "success", "message" => "Location deleted successfully!"]);
        exit;
    }

    // 6. Fetch All Data for DataTables
    if($method == "fetch_all_locations") {
        $query = "
            SELECT 
                t1.id, 
                t1.state, 
                t1.district_name as location_name, 
                t1.main, 
                t1.alias_names,
                t2.district_name as parent_name
            FROM trip_locations t1
            LEFT JOIN trip_locations t2 ON t1.main = t2.id
            WHERE t1.deletes = 0
            ORDER BY t1.id DESC
        ";
        
        $result = mysqli_query($con, $query);
        $data = array();
        
        if($result) {
            while($row = mysqli_fetch_assoc($result)) { 
                $data[] = $row; 
            }
        }

        if (ob_get_length()) ob_clean(); 
        header('Content-Type: application/json');
        
        echo json_encode(["data" => $data]);
        exit;
    }
}
?>