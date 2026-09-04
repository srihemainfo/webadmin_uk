<?php
error_reporting(E_ALL);
ini_set('display_errors', 0); 

include '../../include/shi-config.php';
include '../../include/functions.php';       

header('Content-Type: application/json');

$method = $_REQUEST["method"] ?? "";

// Global Helper to convert spaces to hyphens safely
if (!function_exists('dbFormatName')) {
    function dbFormatName($str) {
        $str = trim($str ?? '');
        return preg_replace('/\s+/', '-', $str);
    }
}

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

else if($method == "fetch_districts") {
    $state = mysqli_real_escape_string($con, $_POST['state'] ?? '');
    $query = "SELECT id, district_name FROM trip_locations WHERE state = '$state' AND main = 0 AND deletes = 0 ORDER BY district_name ASC";
    $result = mysqli_query($con, $query);
    $data = array();
    if($result) {
        while($row = mysqli_fetch_assoc($result)) { $data[] = $row; }
        echo json_encode(["status" => "success", "data" => $data]);
    }
    exit;
}

else if($method == "save_location") {
    $state = mysqli_real_escape_string($con, $_POST['state'] ?? '');
    $is_new = ($_POST['is_new'] ?? '') === 'true' || ($_POST['is_new'] ?? '') === '1'; 
    $district_raw = $_POST['district_val'] ?? '';
    
    if ($is_new) {
        $district_raw = dbFormatName($district_raw);
    }
    $district_val = mysqli_real_escape_string($con, $district_raw);
    
    $entry_type = mysqli_real_escape_string($con, $_POST['entry_type'] ?? ''); 
    $spot_name_raw = $_POST['spot_name'] ?? '';
    $aliases_raw = $_POST['aliases'] ?? '';

    $alias_arr = [];
    if (!empty(trim($aliases_raw))) {
        $parts = explode(',', $aliases_raw);
        foreach($parts as $part) {
            $trimmed = dbFormatName($part);
            if (!empty($trimmed)) {
                $alias_arr[] = $trimmed;
            }
        }
    }
    
    $alias_arr = array_unique($alias_arr); 
    // Secure JSON encoding
    $aliases_json = empty($alias_arr) ? "'[]'" : "'" . mysqli_real_escape_string($con, json_encode(array_values($alias_arr))) . "'";
    $district_id = 0;

    if ($is_new) {
        $check_dist = mysqli_query($con, "SELECT id FROM trip_locations WHERE district_name = '$district_val' AND state = '$state' AND main = 0 AND deletes = 0");
        if (mysqli_num_rows($check_dist) > 0) {
            echo json_encode(["status" => "error", "message" => "Duplicate entry: The district '$district_val' already exists!"]);
            exit;
        }

        $q = "INSERT INTO trip_locations (main, district_name, state, alias_names) VALUES (0, '$district_val', '$state', '[]')";
        if(mysqli_query($con, $q)) {
            $district_id = mysqli_insert_id($con);
        } else {
            echo json_encode(["status" => "error", "message" => mysqli_error($con)]);
            exit;
        }
    } else {
        $district_id = (int) $district_val;
    }

    if ($entry_type == 'spot' || $is_new) {
        if (!empty(trim($spot_name_raw))) {
            $spot_parts = explode(',', $spot_name_raw);
            $added_spots = 0;
            $skipped_spots = 0;
            $last_skipped = '';

            $spot_input_arr = [];
            foreach($spot_parts as $sp) {
                $trimmed_spot = dbFormatName($sp);
                if (!empty($trimmed_spot) && !in_array(strtolower($trimmed_spot), array_map('strtolower', $spot_input_arr))) {
                    $spot_input_arr[] = $trimmed_spot;
                }
            }

            foreach($spot_input_arr as $s_name) {
                $s_name_esc = mysqli_real_escape_string($con, $s_name);
                
                $check_spot = mysqli_query($con, "SELECT id FROM trip_locations WHERE district_name = '$s_name_esc' AND main = $district_id AND deletes = 0");
                if (mysqli_num_rows($check_spot) > 0) {
                    $skipped_spots++;
                    $last_skipped = $s_name;
                    continue; 
                }

                $sq = "INSERT INTO trip_locations (main, district_name, state, alias_names) 
                       VALUES ($district_id, '$s_name_esc', '$state', $aliases_json)";
                
                if(mysqli_query($con, $sq)) {
                    $added_spots++;
                } else {
                    echo json_encode(["status" => "error", "message" => mysqli_error($con)]);
                    exit;
                }
            }

            if ($added_spots > 0) {
                $msg = "Location(s) Saved Successfully!";
                if ($skipped_spots > 0) $msg .= " ($skipped_spots duplicate spots skipped).";
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
    
    else if ($entry_type == 'district_alias') {
        if (!empty($alias_arr)) {
            $q_chk = "SELECT alias_names FROM trip_locations WHERE id = $district_id LIMIT 1";
            $res = mysqli_query($con, $q_chk);
            if ($row = mysqli_fetch_assoc($res)) {
                $curr = json_decode($row['alias_names'], true);
                if(!is_array($curr)) $curr = [];
                
                $merged = array_unique(array_merge($curr, $alias_arr));
                $json_merged = "'" . mysqli_real_escape_string($con, json_encode(array_values($merged))) . "'";
                
                mysqli_query($con, "UPDATE trip_locations SET alias_names = $json_merged WHERE id = $district_id");
            }
        }
        echo json_encode(["status" => "success", "message" => "District Aliases Updated!"]);
        exit;
    }
}

else if($method == "update_location") {
    $id = (int) ($_POST['id'] ?? 0);
    $clean_name = dbFormatName($_POST['name'] ?? '');
    $name = mysqli_real_escape_string($con, $clean_name);
    $aliases_raw = $_POST['aliases'] ?? '';

    $info_q = mysqli_query($con, "SELECT district_name, main, state FROM trip_locations WHERE id = $id");
    if($info = mysqli_fetch_assoc($info_q)) {
        $old_name = trim($info['district_name']);
        $main = $info['main'];
        $state = $info['state'];
        
        $dup_chk = mysqli_query($con, "SELECT id FROM trip_locations WHERE district_name = '$name' AND main = $main AND state = '$state' AND id != $id AND deletes = 0");
        if(mysqli_num_rows($dup_chk) > 0) {
            echo json_encode(["status" => "error", "message" => "Duplicate entry: The name '$name' is already taken here!"]);
            exit;
        }

        $alias_arr = [];
        if (!empty(trim($aliases_raw))) {
            $parts = explode(',', $aliases_raw);
            foreach($parts as $part) {
                $trimmed = dbFormatName($part);
                if (!empty($trimmed)) {
                    $alias_arr[] = $trimmed;
                }
            }
        }
        $aliases_json = empty($alias_arr) ? "'[]'" : "'" . mysqli_real_escape_string($con, json_encode(array_values(array_unique($alias_arr)))) . "'";

        $up_q = "UPDATE trip_locations SET district_name = '$name', alias_names = $aliases_json WHERE id = $id";
        if(mysqli_query($con, $up_q)) {
            
            if (strtolower($old_name) != strtolower(trim($name))) {
                $old_name_esc = mysqli_real_escape_string($con, $old_name);
                $new_name_clean = trim($name);
                
                $display_new_title = str_replace('-', ' ', $name);
                
                $q1 = mysqli_query($con, "SELECT id, to_place FROM dynamic_pages_local WHERE name = '$old_name_esc'");
                while($r = mysqli_fetch_assoc($q1)) {
                    $r_id = $r['id'];
                    $to_place_clean = trim($r['to_place']);
                    $to_place_display = str_replace('-', ' ', $to_place_clean);
                    
                    $n_slug_raw = strtolower($new_name_clean . '-to-' . $to_place_clean . '-drop-taxi');
                    $n_slug = trim(preg_replace('/[^a-z0-9]+/', '-', $n_slug_raw), '-');
                    $n_title = ucwords(strtolower($display_new_title)) . ' to ' . ucwords(strtolower($to_place_display)) . ' Drop Taxi';
                    
                    mysqli_query($con, "UPDATE dynamic_pages_local SET name = '$name', slug = '$n_slug', title = '$n_title' WHERE id = $r_id");
                }

                $q2 = mysqli_query($con, "SELECT id, name as from_loc FROM dynamic_pages_local WHERE to_place = '$old_name_esc'");
                while($r = mysqli_fetch_assoc($q2)) {
                    $r_id = $r['id'];
                    $from_loc_clean = trim($r['from_loc']);
                    $from_loc_display = str_replace('-', ' ', $from_loc_clean);
                    
                    $n_slug_raw = strtolower($from_loc_clean . '-to-' . $new_name_clean . '-drop-taxi');
                    $n_slug = trim(preg_replace('/[^a-z0-9]+/', '-', $n_slug_raw), '-');
                    $n_title = ucwords(strtolower($from_loc_display)) . ' to ' . ucwords(strtolower($display_new_title)) . ' Drop Taxi';
                    
                    mysqli_query($con, "UPDATE dynamic_pages_local SET to_place = '$name', slug = '$n_slug', title = '$n_title' WHERE id = $r_id");
                }
            }

            echo json_encode(["status" => "success", "message" => "Location updated successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => mysqli_error($con)]);
        }
    }
    exit;
}

// UPDATE WIKI DETAILS
else if($method == "update_wiki_details") {
    $id = (int)($_POST['id'] ?? 0);
    $wikiDes = mysqli_real_escape_string($con, $_POST['wikiDes'] ?? '');
    $wikiDesHtml = mysqli_real_escape_string($con, $_POST['wikiDesHtml'] ?? '');

    $q = mysqli_query($con, "SELECT district_name FROM trip_locations WHERE id = $id");
    if($row = mysqli_fetch_assoc($q)) {
        $loc_name = mysqli_real_escape_string($con, trim($row['district_name']));
        
        mysqli_query($con, "UPDATE trip_locations SET wikiDes = '$wikiDes', wikiDesHtml = '$wikiDesHtml' WHERE id = $id");
        
        // Update combinations where this is the destination
        mysqli_query($con, "UPDATE dynamic_pages_local SET to_wikiDes = '$wikiDes', to_wikiDesHtml = '$wikiDesHtml' WHERE to_place = '$loc_name'");
        
        echo json_encode(["status" => "success", "message" => "Wiki details updated successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Location not found!"]);
    }
    exit;
}

else if($method == "delete_location") {
    $id = (int) ($_POST['id'] ?? 0);
    
    $names_to_delete = [];
    
    $q_name = mysqli_query($con, "SELECT district_name, main FROM trip_locations WHERE id = $id");
    if ($r_name = mysqli_fetch_assoc($q_name)) {
        $names_to_delete[] = mysqli_real_escape_string($con, trim($r_name['district_name']));
        
        if ($r_name['main'] == 0) {
            $q_children = mysqli_query($con, "SELECT district_name FROM trip_locations WHERE main = $id");
            while ($child = mysqli_fetch_assoc($q_children)) {
                $names_to_delete[] = mysqli_real_escape_string($con, trim($child['district_name']));
            }
        }
    }

    foreach ($names_to_delete as $d_name) {
        mysqli_query($con, "UPDATE dynamic_pages_local SET deletes = '1' WHERE name = '$d_name' OR to_place = '$d_name'");
    }

    mysqli_query($con, "UPDATE trip_locations SET deletes = 1 WHERE id = $id");
    mysqli_query($con, "UPDATE trip_locations SET deletes = 1 WHERE main = $id");
    
    echo json_encode(["status" => "success", "message" => "Location and related combinations soft-deleted!"]);
    exit;
}

else if ($method == "view_combinations") {
    $id = (int)($_POST['id'] ?? 0);
    $q = mysqli_query($con, "SELECT district_name FROM trip_locations WHERE id = $id");
    $loc_name = mysqli_real_escape_string($con, trim(mysqli_fetch_assoc($q)['district_name']));

    $combos = [];
    $res = mysqli_query($con, "SELECT name as from_loc, to_place as to_loc FROM dynamic_pages_local WHERE name = '$loc_name' AND deletes = '0' ORDER BY id DESC");
    while($r = mysqli_fetch_assoc($res)) {
        $combos[] = $r;
    }
    echo json_encode(["status" => "success", "data" => $combos]);
    exit;
}

// 7. ULTRA FAST Combination Generator
else if ($method == "generate_blank_combinations") {
    $loc_id = (int)($_POST['id'] ?? 0);
    
    $loc_q = mysqli_query($con, "SELECT district_name, main FROM trip_locations WHERE id = $loc_id");
    $loc_data = mysqli_fetch_assoc($loc_q);
    $source_name = trim($loc_data['district_name']);
    $source_main = (int)$loc_data['main'];

    $others_q = mysqli_query($con, "
        SELECT t.district_name
        FROM trip_locations t
        WHERE t.deletes = 0 AND t.id != $loc_id
        AND (t.main != $loc_id AND $source_main != t.id)
        AND (t.main != $source_main OR t.main = 0 OR $source_main = 0)
    ");

    $generated = 0;
    $source_esc = mysqli_real_escape_string($con, $source_name);
    $source_display = str_replace('-', ' ', $source_name);

    while($dest = mysqli_fetch_assoc($others_q)) {
        $dest_name = trim($dest['district_name']);
        $dest_esc = mysqli_real_escape_string($con, $dest_name);

        $chk = mysqli_query($con, "SELECT id FROM dynamic_pages_local WHERE name = '$source_esc' AND to_place = '$dest_esc'");
        
        if(mysqli_num_rows($chk) == 0) {
            
            // STRICT CLEAN SLUGS
            $slug_raw = strtolower($source_name . '-to-' . $dest_name . '-drop-taxi');
            $slug = trim(preg_replace('/[^a-z0-9]+/', '-', $slug_raw), '-');
            
            $dest_display = str_replace('-', ' ', $dest_name);
            $title = ucwords(strtolower($source_display)) . ' to ' . ucwords(strtolower($dest_display)) . ' Drop Taxi';

            $q = "INSERT INTO dynamic_pages_local 
                  (name, to_place, kms, country_code, slug, title, status, deletes) 
                  VALUES ('$source_esc', '$dest_esc', '0', 'IN', '$slug', '$title', 'active', '0')";
            
            if (mysqli_query($con, $q)) {
                $generated++;
            }
        }
    }
    
    echo json_encode(["status" => "success", "message" => "$generated combinations generated instantly!"]);
    exit;
}

// Highly Optimized Table Fetcher (Removed Heavy COALESCE Blocks)
else if($method == "fetch_all_locations") {
    $query = "
        SELECT 
            t1.id, 
            t1.state, 
            t1.district_name as location_name, 
            t1.main, 
            t1.alias_names,
            t1.wikiDes,
            t1.wikiDesHtml,
            t2.district_name as parent_name,
            (SELECT COUNT(id) FROM dynamic_pages_local dpl WHERE dpl.name = t1.district_name AND dpl.deletes = '0') as combo_count,
            (SELECT COUNT(t3.id) FROM trip_locations t3 
             WHERE t3.deletes = 0 
               AND t3.id != t1.id 
               AND (t3.main != t1.id AND t1.main != t3.id) 
               AND (t3.main != t1.main OR t3.main = 0 OR t1.main = 0)
            ) as total_combos
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
    echo json_encode(["data" => $data]);
    exit;
}

else {
    echo json_encode(["status" => false, "message" => "Invalid method!"]);
    exit;
}
?>