<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
if (isset($_POST['method']) && $_POST['method'] === 'fetch_edit_logs') {
    // Clean output buffer
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');
    $userid = $_POST['user_id'];
    // 1. Fetch District Names
    $district_map = [];
    $dist_query = mysqli_query($con, "SELECT id, district_name FROM districts");
    while ($d = mysqli_fetch_assoc($dist_query)) {
        $district_map[$d['id']] = $d['district_name'];
    }
    // NEW: Fetch Agent Names to map agent_id to actual names
    $agent_map = [];
    $agent_query = mysqli_query($con, "SELECT id, name, lname FROM user_register WHERE roll_id = 3");
    if ($agent_query) {
        while ($a = mysqli_fetch_assoc($agent_query)) {
            $agent_map[$a['id']] = trim($a['name'] . ' ' . $a['lname']);
        }
    }
    // 2. Fetch Logs
    $edit_log_data = [];
    $log_query = mysqli_query($con, "SELECT edit_log FROM user_register WHERE id = '$userid'");
    if ($log_row = mysqli_fetch_assoc($log_query)) {
        $edit_log_json = $log_row['edit_log'] ?? '';
        if (!empty($edit_log_json)) {
            $edit_log_data = json_decode($edit_log_json, true);
            if (!is_array($edit_log_data)) $edit_log_data = [];
        }
    }
    // Sort: Newest First
    $edit_log_data = array_reverse($edit_log_data);
    // 3. Fetch Editor Names
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
    // Helper: Friendly Names
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
            'car_colour' => 'Car Color',
            'agent_id' => 'Agent',
            'maker_model' => 'Maker Model', // NEW: Added Agent ID mapping
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
    // --- NEW HELPER: FORMAT DATE (Y-m-d to d-m-Y) ---
    function formatLogDate($val)
    {
        if (is_string($val) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $val)) {
            return date('d-m-Y', strtotime($val));
        }
        return $val;
    }
    // Helper: Recursive Diff HTML
    // Helper: Recursive Diff HTML
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
                // ✨ ADDED FIX: Skip vehicle_details.type so it doesn't show in the edit log
                if ($fullKey === 'vehicle_details.type') {
                    continue;
                }
                $name = getFriendlyName($fullKey);
                $v1 = is_array($val1) ? json_encode($val1) : htmlspecialchars(formatLogDate($val1 ?? ''));
                $v2 = is_array($val2) ? json_encode($val2) : htmlspecialchars(formatLogDate($val2 ?? ''));
                // replace empty strings with "N/A"
                $v1 = ($v1 === '') ? 'N/A' : $v1;
                $v2 = ($v2 === '') ? 'N/A' : $v2;
                $html .= "<tr><td>{$name}</td><td>{$v1}</td><td>{$v2}</td></tr>";
            }
        }
        return $html;
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
                        // Map District ID to Name
                        if ($field === 'districts_id') {
                            $oldVal = $district_map[$change['old']] ?? $change['old'];
                            $newVal = $district_map[$change['new']] ?? $change['new'];
                            // <-- ADD THIS: replace empty strings with "N/A"
                            $oldVal = ($oldVal === '') ? 'N/A' : $oldVal;
                            $newVal = ($newVal === '') ? 'N/A' : $newVal;
                        }
                        // NEW: Map Agent ID to Name
                        elseif ($field === 'agent_id') {
                            $oldVal = empty($change['old']) ? 'Unassigned' : ($agent_map[$change['old']] ?? 'Agent ID: ' . $change['old']);
                            $newVal = empty($change['new']) ? 'Unassigned' : ($agent_map[$change['new']] ?? 'Agent ID: ' . $change['new']);
                        } else {
                            $oldVal = is_array($change['old']) ? json_encode($change['old']) : htmlspecialchars(formatLogDate($change['old'] ?? ''));
                            $newVal = is_array($change['new']) ? json_encode($change['new']) : htmlspecialchars(formatLogDate($change['new'] ?? ''));
                            // <-- ADD THIS: replace empty strings with "N/A"
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
if (isset($_REQUEST['menuper'])) {
    mysqli_query($con, "Delete  from `menu_permission` where `userid`='$subid4'");
    $menuval = $_REQUEST['other'];
    $menuid = $_REQUEST['otherid'];
    $menu = mysqli_query($con, "SELECT * FROM  `orm_menu`");
    $val = mysqli_num_rows($menu);
    for ($i = 1; $i <= 300; $i++) {
        if ($menuval[$i] != "") {
            mysqli_query($con, "INSERT INTO `menu_permission`(`userid`,`menu`) VALUES ('$subid4','$menuid[$i]')");
        }
    }
}
?>
<?php
// Add this function near the top of the PHP block, after the opening <?php
function mapFuelType($fuel)
{
    if (empty($fuel)) return '';
    $fuel = strtoupper(trim($fuel));
    // Replace spaces, plus signs, ampersands with a slash
    $fuel = preg_replace('/[\s+&]/', '/', $fuel);
    // If it contains both PETROL and CNG, return the exact option value
    if (strpos($fuel, 'PETROL') !== false && strpos($fuel, 'CNG') !== false) {
        return 'PETROL/CNG';
    }
    // Check for individual fuel types
    if (strpos($fuel, 'PETROL') !== false) return 'PETROL';
    if (strpos($fuel, 'DIESEL') !== false) return 'DIESEL';
    if (strpos($fuel, 'CNG') !== false) return 'CNG';
    // Fallback: return original (uppercased) – may still not match, but at least consistent
    return $fuel;
}
// =======================================================================
// 2. THE PAGE LOAD / VIEW LOGIC
// =======================================================================
if ($subid3 != 'permission') {
    if ($subid3 != '') {
        $userid = $subid3;
    } else {
        $userid = $_SESSION['memid'];
    }
    // 3. Prepare data for Page Load
    // $sql = "SELECT ur.*, kd.type, kd.cab_type, kd.dl_expiry, 
    // JSON_UNQUOTE(JSON_EXTRACT(oc.req_response, '$.date_of_expiry')) AS raw_expiry_date,
    // JSON_UNQUOTE(JSON_EXTRACT(oc.req_response, '$.TR')) AS tr_value,
    // JSON_UNQUOTE(JSON_EXTRACT(oc.req_response, '$.NT')) AS nt_value,
    // oc.exp
    // FROM `user_register` ur 
    // LEFT JOIN kyc_details kd ON kd.user_id = ur.id 
    // LEFT JOIN ocr_request oc ON oc.user_id = ur.id AND oc.doc_type = 'DRIVING_LICENSE' AND oc.status IS NOT NULL 
    // WHERE ur.`id` = '$userid' 
    // ORDER BY oc.id DESC";
  $sql = "SELECT ur.*, kd.type, 
    CASE 
        WHEN JSON_UNQUOTE(JSON_EXTRACT(ur.vehicle_details, '$.type')) REGEXP '^[0-9]+$' 
        THEN JSON_UNQUOTE(JSON_EXTRACT(ur.vehicle_details, '$.rc_details.response.vehicle_details.body_type'))
        ELSE JSON_UNQUOTE(JSON_EXTRACT(ur.vehicle_details, '$.type')) 
    END AS cab_type, 
    JSON_UNQUOTE(JSON_EXTRACT(ur.vehicle_details, '$.rc_details.response.vehicle_details.fuel_type')) AS fuel_type, 
    kd.dl_expiry, 
    JSON_UNQUOTE(JSON_EXTRACT(oc.req_response, '$.date_of_expiry')) AS raw_expiry_date,
    JSON_UNQUOTE(JSON_EXTRACT(oc.req_response, '$.TR')) AS tr_value,
    JSON_UNQUOTE(JSON_EXTRACT(oc.req_response, '$.NT')) AS nt_value,
    oc.exp
    FROM `user_register` ur 
    LEFT JOIN kyc_details kd ON kd.user_id = ur.id 
    LEFT JOIN ocr_request oc ON oc.user_id = ur.id AND oc.doc_type = 'DRIVING_LICENSE' AND oc.status IS NOT NULL 
    WHERE ur.`id` = '$userid' 
    ORDER BY oc.id DESC";
    $run = mysqli_query($con, $sql);
    $row = [];
    if (mysqli_num_rows($run) > 0) {
        $row = $run->fetch_assoc();
    }
    $userType = $row['type'] ?? '';
    $pricePerKm = $row['per_km'] ?? '';
    $pricePerHour = $row['per_hour'] ?? '';
    $pricePerDay = $row['per_day'] ?? '';
    $extrapricePerKm = $row['extra_per_km'] ?? '';
    // --- FIX: ROBUST DATE PARSING FOR DISPLAY ---
    $value = '';
    if (!empty($row['raw_expiry_date'])) {
        $ts = strtotime($row['raw_expiry_date']);
        if ($ts !== false && $ts > 0) {
            $value = date('Y-m-d', $ts);
        }
    }
    $distrcit = $row['districts_id'] ?? '';
    $review = $row['reviews'] ?? '';
    $remarks = $row['remarks'] ?? '';
    $ntValue = $row['nt_value'] ?? '';
    $trValue = $row['tr_value'] ?? '';
    $livalue = !empty($ntValue) ? $ntValue : $trValue;
    $livalue = strtoupper(trim($livalue));
    $livalue = str_replace(' ', '-', $livalue);
    $vehicleDetails = json_decode($row['vehicle_details'] ?? '{}', true);
    if (!is_array($vehicleDetails)) {
        $vehicleDetails = [];
    }
    // --- FIXED: Parse Vehicle Dates safely matching the Agency Dashboard JSON structure ---
    // 1. RC Expiry
    $RcUpto = !empty($row['rc_upto'])
        ? $row['rc_upto']
        : ($vehicleDetails['rc_expiry_date'] ?? $vehicleDetails['rc_details']['response']['vehicle_details']['fit_up_to'] ?? '');
    if ($RcUpto && strtotime($RcUpto) > 0) $RcUpto = date('Y-m-d', strtotime($RcUpto));
    // 2. PUC Expiry
    $pucUpto = !empty($row['puc_upto'])
        ? $row['puc_upto']
        : ($vehicleDetails['puc_details']['puc_exp_date'] ?? $vehicleDetails['rc_details']['response']['permit_details']['pucc_upto'] ?? '');
    if ($pucUpto && strtotime($pucUpto) > 0) $pucUpto = date('Y-m-d', strtotime($pucUpto));
    // 3. Insurance Expiry
    $insuranceUpto = !empty($row['insurance_upto'])
        ? $row['insurance_upto']
        : ($vehicleDetails['insurance_details']['insurance_exp_date'] ?? $vehicleDetails['rc_details']['response']['finance_details']['insurance_upto'] ?? '');
    if ($insuranceUpto && strtotime($insuranceUpto) > 0) $insuranceUpto = date('Y-m-d', strtotime($insuranceUpto));
    $SeatCapacity = !empty($row['seat']) ? $row['seat'] : ($vehicleDetails['rc_details']['response']['vehicle_details']['seat_capacity'] ?? '');
    $rawFuel = !empty($row['fuel_types']) ? $row['fuel_types'] : ($vehicleDetails['rc_details']['response']['vehicle_details']['fuel_type'] ?? '');
    $fuelType = mapFuelType($rawFuel);
    // 1. Fetch from root JSON "type" first (e.g. "Go 7Seater")
    // 2. Fallback to SQL 'cab_type'
    // 3. Fallback to RC body_type
    $jsonType = $vehicleDetails['type'] ?? '';

// If jsonType is NOT empty AND is NOT a pure number (e.g. "Go 4Seater"), use it.
// Otherwise (if it's "4", "6", etc., or empty), fallback to body_type, then to row['cab_type'].
$cabType = (!empty($jsonType) && !preg_match('/^[0-9]+$/', $jsonType))
    ? $jsonType
    : ($vehicleDetails['rc_details']['response']['vehicle_details']['body_type'] 
        ?? (!empty($row['cab_type']) && !preg_match('/^[0-9]+$/', $row['cab_type']) 
            ? $row['cab_type'] 
            : ''));
    // Prioritize Maker Model and Car Colour from user_register DB overrides
    $makerModel = !empty($row['maker_model']) ? $row['maker_model'] : ($vehicleDetails['rc_details']['response']['vehicle_details']['maker_model'] ?? '');
    $carColour = !empty($row['car_colour']) ? $row['car_colour'] : ($vehicleDetails['rc_details']['response']['vehicle_details']['color'] ?? '');
    $userInfo = $vehicleDetails['user_info'] ?? [];
    $langSource = !empty($row['language']) ? $row['language'] : ($userInfo['language'] ?? '');
    $selectedLanguages = !empty($langSource) ? explode(',', $langSource) : [];
    $luggageValue = !empty($row['Luggage']) ? $row['Luggage'] : ($userInfo['luggage'] ?? '');
    $roll_id =  select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'", "roll_id", "");
    $acc_type = ['savings', 'current'];
    $bank_list = [
        'First Abu Dhabi Bank (FAB)',
        'Emirates NBD',
        'Abu Dhabi Commercial Bank',
        'Dubai Islamic Bank',
        'MashreqBank',
        'Abu Dhabi Islamic Bank (ADIB)',
        'HSBC Bank Middle East - UAE Operations',
        'Union National Bank',
        'Commercial Bank of Dubai (CBD)',
        'Emirates Islamic Bank',
        'National Bank of Ras Al Khaimah (RAKBANK)',
        'Al Hilal Bank',
        'Noor Bank',
        'Sharjah Islamic Bank',
        'National Bank of Fujairah',
        'Others'
    ];
    $bank_check = '';
    if ($row['bank_name'] != '') {
        if (in_array($row['bank_name'], $bank_list)) {
            $bank_check = '1';
        } else {
            $bank_check = '0';
        }
    }
?>
    <style>
        .bootstrap-select>.dropdown-toggle {
            background: #fff
        }
        .brround {
            height: 80px;
            width: 81px;
        }
        .bootstrap-select .dropdown-menu li a,
        .dropdown-item.active {
            padding: 3px 0 24px 22px !important
        }
        .inner.show {
            min-height: 135px !important;
            max-height: 134px !important
        }
        .dropdown-menu.show {
            width: 100%
        }
        .bootstrap-select .dropdown-toggle .filter-option {
            height: auto
        }
        .back-arrow-btn i {
            background: #fff;
            font-size: 16px;
            padding: 2px 3px;
            border-radius: 50px;
            border: 2px solid #6c6e70;
            color: #6c6e70;
            margin-right: 15px;
            width: 24px;
            height: 24px
        }
        textarea {
            height: 100px;
            padding: 12px 20px;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 4px;
            background-color: #f8f8f8;
            font-size: 16px;
            resize: none;
            text-align: center;
        }
    </style>
    <div class="main-content mt-0 edit-profile1 app-content">
        <div class="side-app">
            <div class="main-container container-fluid">
                <div class="page-header">
                    <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Edit Profile</h1>
                    <div>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Pages</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                                <div class="card-title">My Profile</div>
                            </div>
                            <div class="card-body">
                                <div class="text-center chat-image mb-5">
                                    <div class="avatar avatar-xxl chat-profile mb-3 brround">
                                        <?php if ($row['img_url'] != '') { ?>
                                            <a class="" href="javascript:void(0);">
                                                <img alt="avatar" src="https://nationalasset.blr1.digitaloceanspaces.com/<?php echo $row['img_url']; ?>" class="brround">
                                            </a>
                                        <?php } else { ?>
                                            <a class="" href="javascript:void(0);">
                                                <img alt="avatar" src="assets/images/users/2.jpg" class="brround">
                                            </a>
                                        <?php } ?>
                                    </div>
                                    <div class="main-chat-msg-name">
                                        <?php if ($roll_id  != 9) { ?>
                                            <h5 class="mb-1 text-dark fw-semibold">Go Ride</h5>
                                            </a>
                                        <?php } ?>
                                        <?php if ($roll_id  == 9) { ?>
                                            <p class="mt-0 mb-0 pt-0 fs-15">User-
                                                <span class="font-weight-500"><?= $row['name']; ?></span>
                                            </p>
                                        <?php } ?>
                                        <p class="text-muted mt-0 mb-0 pt-0 fs-13">ID - <?= $row['id']; ?></p>
                                        <?php if ($roll_id  != 9) { ?>
                                            <p class="mt-0 mb-0 pt-0 fs-15">Last Login -
                                                <span class="font-weight-500"><?= $row['lastlogin']; ?></span>
                                            </p>
                                        <?php } ?>
                                        <?php if ($roll_id  == 9) { ?>
                                            <p class="mt-0 mb-0 pt-0 fs-15">Created On -
                                                <span class="font-weight-500"><?= $row['created_at']; ?></span>
                                            </p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <ul class="list-group no-margin">
                                <li class="list-group-item d-flex ps-3">
                                    <div class="social social-profile-buttons me-2">
                                        <a class="social-icon text-primary" href=""><i class="fe fe-mail"></i></a>
                                    </div>
                                    <a href="javascript:void(0)" class="my-auto"><?= $row['email']; ?></a>
                                </li>
                                <li class="list-group-item d-flex ps-3">
                                    <div class="social social-profile-buttons me-2">
                                        <a class="social-icon text-primary" href=""><i class="fe fe-phone"></i></a>
                                    </div>
                                    <a href="javascript:void(0)" class="my-auto"><?= $row['mobile']; ?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <form id="editprofileform">
                            <input type="hidden" id="updateuserid" name="updateuserid" value="<?= $userid; ?>">
                            <input type="hidden" id="typeOTP" name="typeOTP" value="<?php if ($subid3 != '') echo 'send'; ?>">
                            <div class="card ">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3 class="card-title mb-0">Edit Profile</h3>
                                    <div class="d-flex align-items-center">
                                        <?php
                                        $profilePercent = (int)($row['profile_percentage'] ?? 0);
                                        $badgeClass = 'bg-danger';
                                        if ($profilePercent >= 75) $badgeClass = 'bg-success';
                                        elseif ($profilePercent >= 40) $badgeClass = 'bg-warning text-dark';
                                        ?>
                                        <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-2 me-4" id="profileScoreBadge" style="font-size: 13px;">
                                            <i class="fa fa-check-circle me-1"></i><?= $profilePercent ?>% Complete
                                        </span>
                                        <i class="fa-solid fa-message cursor-pointer me-4" style="color: #6c757d; font-size: 1.4rem;" onclick="openRemarksModal('<?= $userid ?>', '<?= addslashes($row['name'] ?? 'User') ?>')" title="Add/View Remarks"></i>
                                        <button type="button" class="btn btn-info btn-sm" onclick="loadEditLogs()">
                                            <i class="fa fa-history"></i> Edit Logs
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-12 mt-4">
                                                <h3 class="card-title">User Details</h5>
                                                    <hr>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Full Name <span class="text-danger">*</span></label>
                                                    <input type="text" value="<?= htmlspecialchars($row['name'] ?? ''); ?>" name="name" class="form-control" placeholder="Enter Full Name" required maxlength="50" minlength="2" pattern="^[A-Za-z. ]{2,50}$" oninput="validateName(this)">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Mobile Number <span class="text-danger">*</span></label>
                                                    <?php
                                                    $fullMobile = $row['mobile'] ?? '';
                                                    $displayMobile = $fullMobile;
                                                    // UPDATED LOGIC: If the number is 10 digits, ADD '91' for display
                                                    if (!empty($fullMobile) && strlen($fullMobile) === 10) {
                                                        $displayMobile = '91' . $fullMobile;
                                                    }
                                                    ?>
                                                    <input
                                                        type="tel"
                                                        value="<?= htmlspecialchars($displayMobile) ?>"
                                                        name="mobile"
                                                        class="form-control"
                                                        placeholder="Enter Mobile Number"
                                                        required
                                                        maxlength="12"
                                                        minlength="12"
                                                        pattern="^91[0-9]{10}$"
                                                        title="Enter Valid Mobile Number"
                                                        oninput="validateMobile(this)"
                                                        onchange="if(this.value.length === 10) this.value = '91' + this.value;"
                                                        data-fullmobile="<?= htmlspecialchars($fullMobile) ?>"
                                                        <?= ($roll_id != 1) ? 'readonly' : ''; ?>>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Email ID <span class="text-danger">*</span></label>
                                                    <input type="email" value="<?= htmlspecialchars($row['email'] ?? ''); ?>" name="email" class="form-control" placeholder="Enter Email Address" required maxlength="75" pattern="^[^\s@]+@[^\s@]+\.[^\s@]{2,}$" title="Enter valid email address without spaces (max 75 characters)" oninput="validateEmail(this)" <?= ($roll_id != 1) ? 'readonly' : ''; ?>>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>State <span class="text-danger">*</span></label>
                                                    <select class="form-control form-select" name="billing_address" required title="Please select a state">
                                                        <option value="">Select State</option>
                                                        <?php
                                                        $query = mysqli_query($con, "SELECT id, name FROM states WHERE country_code = 'IN'");
                                                        while ($rows = mysqli_fetch_assoc($query)) {
                                                            $selected = ($rows['name'] == $row['state']) ? 'selected' : '';
                                                            echo "<option value='" . htmlspecialchars($rows['name']) . "' $selected>" . htmlspecialchars($rows['name']) . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>District <span class="text-danger">*</span></label>
                                                    <select class="form-control form-select" name="districts_id" required title="Please select district">
                                                        <option value="">Select District</option>
                                                        <?php
                                                        $query = mysqli_query($con, "SELECT id, district_name FROM districts");
                                                        while ($district = mysqli_fetch_assoc($query)) {
                                                            $selected = ($district['id'] == $row['districts_id']) ? 'selected' : '';
                                                            echo '<option value="' . htmlspecialchars($district['id']) . '" ' . $selected . '>' . htmlspecialchars($district['district_name']) . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Address <span class="text-danger">*</span></label>
                                                    <input type="text" value="<?= htmlspecialchars($row['address'] ?? ''); ?>" name="address_us" class="form-control" placeholder="Enter Address" required maxlength="200" title="Enter valid address">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Language <span class="text-danger"></span></label>
                                                    <select name="language[]" class="selectpicker form-control" multiple required data-live-search="true" data-actions-box="true" title="Select Languages">
                                                        <?php
                                                        $languages = ["Tamil", "English", "Hindi", "Telugu", "Malayalam", "Kannada", "Marathi"];
                                                        foreach ($languages as $lang) {
                                                            $selected = in_array($lang, $selectedLanguages ?? []) ? 'selected' : '';
                                                            echo "<option value='" . htmlspecialchars($lang) . "' $selected>" . htmlspecialchars($lang) . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>User Role Type <span class="text-danger">*</span></label>
                                                    <select name="user_role" class="form-control form-select" required title="Select user role">
                                                        <option value="">Select Role</option>
                                                        <option value="Driver" <?= ($userType === 'Driver') ? 'selected' : '' ?>>Driver</option>
                                                        <option value="Owner" <?= ($userType === 'Owner') ? 'selected' : '' ?>>Owner</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>License Expiry <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" name="li_upto" value="<?= htmlspecialchars($value) ?>" required min="<?= date('Y-m-d') ?>" title="Select valid expiry date">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>License Type <span class="text-danger">*</span></label>
                                                    <select class="form-control form-select" id="license_type" name="license_type" required title="Select license type">
                                                        <option value="">Select Type</option>
                                                        <option value="LMV" <?= ($livalue === 'LMV') ? 'selected' : '' ?>>LMV</option>
                                                        <option value="LMV-TR" <?= ($livalue === 'LMV-TR') ? 'selected' : '' ?>>LMV-TR</option>
                                                        <option value="MCWG" <?= ($livalue === 'MCWG') ? 'selected' : '' ?>>MCWG</option>
                                                        <option value="OTHERS" <?= ($livalue === 'OTHERS') ? 'selected' : '' ?>>Others</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <h3 class="card-title">Vehicle Details</h5>
                                                    <hr>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Vehicle Type <span class="text-danger">*</span></label>
                                                    <select class="form-control form-select" id="cab_types" name="cab_type" required title="Please select vehicle type">
                                                        <option value="">Select Model</option>
                                                        <?php
                                                        $cab_query = mysqli_query($con, "SELECT id, name FROM cab_types ORDER BY name ASC");
                                                        if ($cab_query) {
                                                            while ($cab = mysqli_fetch_assoc($cab_query)) {
                                                                $cabName = htmlspecialchars($cab['name']);
                                                                // Match the saved cabType to select the correct dropdown option
                                                                $selected = ($cabType === $cabName || ($cabType === 'Mini' && $cabName === 'Go Mini') || ($cabType === '7Seater' && $cabName === 'Go 7Seater') || ($cabType === '12Seater' && $cabName === 'Go 12Seater')) ? 'selected' : '';
                                                                
                                                                echo "<option value='{$cabName}' {$selected}>{$cabName}</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Fuel Type <span class="text-danger">*</span></label>
                                                    <select class="form-control form-select" id="fuel_types" name="fuel_types" required title="Please select fuel type">
                                                        <option value="">Select Type</option>
                                                        <option value="PETROL" <?= ($fuelType === 'PETROL') ? 'selected' : '' ?>>PETROL</option>
                                                        <option value="DIESEL" <?= ($fuelType === 'DIESEL') ? 'selected' : '' ?>>DIESEL</option>
                                                        <option value="CNG" <?= ($fuelType === 'CNG') ? 'selected' : '' ?>>CNG</option>
                                                        <option value="PETROL/CNG" <?= ($fuelType === 'PETROL/CNG') ? 'selected' : '' ?>>PETROL/CNG</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Driver Experience (Years) <span class="text-danger">*</span></label>
                                                    <input type="text" value="<?= htmlspecialchars($row['exp'] ?? ''); ?>" name="exp" class="form-control" placeholder="Experience" required maxlength="2" minlength="1" pattern="^[0-9]{1,2}$" title="Driver experience must be 1 to 2 digits (max 99 years)" oninput="validateExperience(this)">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Seat Capacity <span class="text-danger">*</span></label>
                                                    <input type="text" id="seat_capacity" value="<?= htmlspecialchars($SeatCapacity ?? '') ?>" name="seat" class="form-control" placeholder="Seat Capacity" required maxlength="2" minlength="1" pattern="^[0-9]{1,2}$" title="Seat capacity must be 1 to 2 digits (max 99)" oninput="validateSeat(this)" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Luggage Capacity <span class="text-danger">*</span></label>
                                                    <input type="text" name="Luggage" class="form-control" placeholder="Luggage Capacity" value="<?= htmlspecialchars($luggageValue ?? '') ?>" required maxlength="2" minlength="1" pattern="^[0-9]{1,2}$" title="Luggage capacity must be 1 to 2 digits (max 99)" oninput="validateLuggage(this)">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Vehicle Insurance Expiry <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" name="insurance_upto" value="<?= htmlspecialchars($insuranceUpto ?? '') ?>" required min="<?= date('Y-m-d') ?>" title="Select valid insurance expiry date">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>RC Expiry/Fitness Expiry <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" name="rc_upto" value="<?= htmlspecialchars($RcUpto ?? '') ?>" required min="<?= date('Y-m-d') ?>" title="Select valid RC expiry date">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>PUC Expiry <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" name="puc_upto" value="<?= htmlspecialchars($pucUpto ?? '') ?>" required min="<?= date('Y-m-d') ?>" title="Select valid PUC expiry date">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Maker Model</label>
                                                    <input type="text" value="<?= htmlspecialchars($makerModel ?? '') ?>" name="maker_model" class="form-control" placeholder="Enter Maker Model">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Car Colour</label>
                                                    <input type="text" value="<?= htmlspecialchars($carColour ?? '') ?>" name="car_colour" class="form-control" placeholder="Enter Car Colour">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mt-4">
                                            <h5 class="card-title">Fare Details</h5>
                                            <hr>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Price Per KM</label>
                                                <input type="text" name="price_per_km" class="form-control" value="<?= htmlspecialchars($pricePerKm ?? '') ?>" placeholder="Enter price per KM" maxlength="6" pattern="^\d+(\.\d{1,2})?$" title="Enter valid price (example: 10 or 10.50)" oninput="validateDecimal(this)">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Extra Price Per KM</label>
                                                <input type="text" name="extra_price_per_km" class="form-control" value="<?= htmlspecialchars($extrapricePerKm ?? '') ?>" placeholder="Enter extra price per KM" maxlength="6" pattern="^\d+(\.\d{1,2})?$" title="Enter valid price (example: 10 or 10.50)" oninput="validateDecimal(this)">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Extra Price Per Hour</label>
                                                <input type="text" name="price_per_hour" class="form-control" value="<?= htmlspecialchars($pricePerHour ?? '') ?>" placeholder="Enter price per Hour" maxlength="6" pattern="^\d+(\.\d{1,2})?$" title="Enter valid price (example: 100 or 100.50)" oninput="validateDecimal(this)">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Extra Price Per Day</label>
                                                <input type="text" name="price_per_day" class="form-control" value="<?= htmlspecialchars($pricePerDay ?? '') ?>" placeholder="Enter price per Day" maxlength="6" pattern="^\d+(\.\d{1,2})?$" title="Enter valid price (example: 500 or 500.50)" oninput="validateDecimal(this)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-3">
                                            <h5 class="card-title">Payment Details</h5>
                                            <hr>
                                            <div class="form-group">
                                                <label for="exampleUpi">UPI ID</label>
                                                <input type="text" value="<?= htmlspecialchars($row['upiID'] ?? '') ?>" name="upi_id" class="form-control" id="exampleUpi" placeholder="Enter UPI ID (example@upi)" maxlength="50" pattern="^[A-Za-z0-9._-]+@[A-Za-z]+$" title="Enter valid UPI ID (example@upi). No spaces allowed." oninput="validateUpi(this)">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <h5 class="card-title">Feedback</h5>
                                            <hr>
                                            <div class="form-group">
                                                <label for="reviews">Reviews</label>
                                                <select class="form-control form-select" id="reviews" name="reviews">
                                                    <option value="">Select</option>
                                                    <option value="Good" <?= ($review === 'Good') ? 'selected' : '' ?>>Good</option>
                                                    <option value="Bad" <?= ($review === 'Bad') ? 'selected' : '' ?>>Bad</option>
                                                    <option value="Excellent" <?= ($review === 'Excellent') ? 'selected' : '' ?>>Excellent</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <h5 class="card-title">Remarks</h5>
                                            <hr>
                                            <div class="form-group">
                                                <label for="remarks">Remarks</label>
                                                <input type="text" value="<?= htmlspecialchars($remarks ?? '') ?>" name="remarks" class="form-control" id="remarks" placeholder="Enter Remarks">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <h5 class="card-title">Assigned To</h5>
                                            <hr>
                                            <div class="form-group">
                                                <label for="agent_id">Agents List</label>
                                                <select class="form-control form-select" id="agent_id" name="agent_id">
                                                    <option value="">Select Agent</option>
                                                    <?php
                                                    // Assuming $assigned_agent_id holds the ID of the agent currently saved to this record
                                                    $assigned_agent_id = $row['agent_id'] ?? null;
                                                    $query = mysqli_query($con, "SELECT id, name, lname FROM user_register WHERE roll_id = 3 AND deletes = '0' ");
                                                    if ($query) {
                                                        while ($agentsList = mysqli_fetch_assoc($query)) {
                                                            // Compare the loop's ID with the previously saved agent ID
                                                            $selected = ($agentsList['id'] == $assigned_agent_id) ? 'selected' : '';
                                                            echo '<option value="' . htmlspecialchars($agentsList['id']) . '" ' . $selected . '>'
                                                                . htmlspecialchars($agentsList['name']) . ' ' . htmlspecialchars($agentsList['lname'])
                                                                . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <div id="editmessage2"></div>
                                    <button type="button" id="updateBtn" onclick="updateDetails()" class="btn btn-success bg-success-gradient my-1">Update</button>
                                    <?php if ($subid3 != '' && $roll_id != 1 && $roll_id != 6) { ?>
                                    <?php   } else { ?>
                                    <?php  } ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="otp">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content modal-content-demo">
                <div id="otperror2"></div>
                <div class="modal-header">
                    <h6 class="modal-title">OTP</h6>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form action="#" class="otp_form" id="profile_form">
                            <div class="form-group">
                                <div class="model-text">
                                    <h3 class="text-center">User verification
                                    </h3>
                                    <p class="text-center">Enter the code we just send on your <?= $row['email']; ?> / <?= $row['mobile']; ?> </p>
                                    <br>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" onkeyup="onKeyUpEvent(1, event)" onfocus="onFocusEvent(1)" class="form-control" id="otp1" name="otp1">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" onkeyup="onKeyUpEvent(2, event)" onfocus="onFocusEvent(2)" class="form-control" id="otp2" name="otp2">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" onkeyup="onKeyUpEvent(3, event)" onfocus="onFocusEvent(3)" class="form-control" id="otp3" name="otp3">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" maxlength="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" onkeyup="onKeyUpEvent(4, event)" onfocus="onFocusEvent(4)" class="form-control" id="otp4" name="otp4">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-success" onclick="updateverification()" type="button">Submit</button>
                    <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editLogModal" tabindex="-1" aria-labelledby="editLogModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLogModalLabel">Edit Logs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="edit_log_content">
                        <p class="text-center text-muted">Loading logs...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="remarksModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Remarks for <span id="rmk_user_name" class="text-primary"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="rmk_user_id">
                    <input type="hidden" id="rmk_mem_id" value="<?= $_SESSION['memid'] ?? '' ?>">
                    <p class="fw-bold mb-2">Previous Remarks</p>
                    <div class="table-responsive mb-4" style="max-height: 250px; overflow-y: auto;">
                        <table class="table table-hover table-bordered table-sm text-start" style="text-align: left !important;">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th scope="col" width="10%">SNO</th>
                                    <th scope="col" width="30%">CONTACT PERSON</th>
                                    <th scope="col" width="40%">REMARKS</th>
                                    <th scope="col" width="20%">DATE & TIME</th>
                                </tr>
                            </thead>
                            <tbody id="remarks_table_body">
                            </tbody>
                        </table>
                    </div>
                    <p class="fw-bold mt-4 mb-2">Add Remark</p>
                    <div class="row border p-3 rounded bg-light" style="margin: 0;">
                        <div class="col-12 p-0">
                            <textarea id="new_remark_text" class="form-control" rows="3" placeholder="Type your remarks here..." style="text-align: left !important; resize: vertical; border: 1px solid #ccc;"></textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-primary px-4" id="btn_save_remark" onclick="saveRemark()">Submit Remark</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        input,
        select {
            border: 1px solid #ccc
        }
        button,
        input {
            height: 35px;
            margin: 0;
            padding: 6px 12px;
            border-radius: 2px;
            font-family: inherit;
            font-size: 100%;
            color: inherit
        }
        .min-height-profile-dtls {
            height: 455px !important;
            overflow-x: scroll !important
        }
        .edit-profile1.app-content {
            min-height: calc(92vh - 50px)
        }
        .jexcel>tbody>tr>td.readonly {
            color: #000 !important
        }
        .nav.product-sale {
            position: unset;
            top: -3rem;
            right: 5px;
            margin: 12px 0;
        }
        table.table.table-bordered {
            text-align: center;
        }
        div#shtext {
            text-align: center;
            cursor: pointer;
        }
        #spreedcontent {
            text-align: center;
            margin-top: 10px;
        }
        .show-more {
            text-align: center;
            cursor: pointer;
        }
    </style>
    <?php
    if ($subid3 != '') {
        $userid = $subid3;
    } else {
        $userid = $_SESSION['memid'];
    }
    $roll_id = select_top_name($con, 'user_register', "roll_id", "`id`='$_SESSION[memid]'", "roll_id", "");
    if ($roll_id == 1 || $roll_id == 2 || $roll_id == 6  && ($row['id'] != $_SESSION['memid'])  &&  $row['roll_id']  != 3 && $row['roll_id']  != 4  &&  $row['roll_id']  != 5) {
        // Fetch edit logs for this user
        // var_dump($row['company_name']);die;
    ?>
        <script>
        
            
            var spreadsheet;
            var new_spreadsheet;
            var n_spreadsheet;
            document.getElementById('user_role').addEventListener('change', function() {
                var type = $('#user_role').val();
                // console.log(type);
                const companyDiv = document.getElementById('exampleCompany');
                const compLabel = document.getElementById('com_label');
                if (type == 'owner') {
                    companyDiv.style.display = 'block';
                    compLabel.style.display = 'block';
                } else {
                    companyDiv.style.display = 'none';
                    compLabel.style.display = 'none';
                }
            });
            $(function() {
                $('#spreedcontent').hide();
                $('#new_spreedcontent').hide();
                $('#n_spreedcontent').hide();
                // createDatePricket('ticketctime');
                createDatePricket('transhctime');
                createDatePricket('withhctime');
                createDatePricket('cahsbonustime');
                // Customer_Ticket();
                transaction();
                new_balance_customer_sheet();
                n_balance_customer_sheet();
            });
            function validateName(input) {
                input.value = input.value.replace(/[^A-Za-z. ]/g, '');
                // Remove multiple spaces
                input.value = input.value.replace(/\s{2,}/g, ' ');
                // Prevent starting with space
                input.value = input.value.replace(/^\s+/, '');
                // Enforce max length 50 manually
                if (input.value.length > 50) {
                    input.value = input.value.substring(0, 50);
                }
            }
            function validateUpi(input) {
                // Allow letters, numbers, dot, underscore, hyphen, @
                input.value = input.value.replace(/[^A-Za-z0-9@._-]/g, '');
                // Remove spaces explicitly
                input.value = input.value.replace(/\s/g, '');
            }
            function validateLuggage(input) {
                // Allow only numbers
                input.value = input.value.replace(/[^0-9]/g, '');
                // Enforce max length = 2
                if (input.value.length > 2) {
                    input.value = input.value.substring(0, 2);
                }
                // Prevent invalid values like 00
                if (input.value.length > 1 && input.value.startsWith('0')) {
                    input.value = input.value.replace(/^0+/, '');
                }
            }
            function validateExperience(input) {
                // Allow only numbers
                input.value = input.value.replace(/[^0-9]/g, '');
                // Enforce max length = 2
                if (input.value.length > 2) {
                    input.value = input.value.substring(0, 2);
                }
                // Prevent invalid values like 00
                if (input.value.length > 1 && input.value.startsWith('0')) {
                    input.value = input.value.replace(/^0+/, '');
                }
            }
            function validateSeat(input) {
                // Allow only numbers
                input.value = input.value.replace(/[^0-9]/g, '');
                // Enforce max length = 2
                if (input.value.length > 2) {
                    input.value = input.value.substring(0, 2);
                }
                // Prevent zero or empty invalid values like 00
                if (input.value.length === 2 && input.value.startsWith('0')) {
                    input.value = input.value.replace(/^0+/, '');
                }
            }
            function validateEmail(input) {
                input.value = input.value.replace(/\s/g, '');
                // Enforce max length 75
                if (input.value.length > 75) {
                    input.value = input.value.substring(0, 75);
                }
            }
            document.getElementById("profile_form").addEventListener("submit", function(e) {
                let form = this;
                if (!form.checkValidity()) {
                    e.preventDefault(); // stop submit
                    form.reportValidity(); // show validation messages
                    return false;
                }
            });
            function createDatePricket(id) {
                var start = moment();
                var end = moment();
                function cb(start, end) {
                    $('#' + id + ' span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));
                }
                $('#' + id).daterangepicker({
                    startDate: start.set({
                        hour: 0,
                        minute: 0,
                        second: 0,
                        millisecond: 0
                    }),
                    endDate: end.set({
                        hour: 23,
                        minute: 59,
                        second: 59,
                        millisecond: 59
                    }),
                    timePicker: true,
                    timePicker24Hour: true,
                    timePickerSeconds: true,
                    maxSpan: {
                        days: 365
                    },
                    autoUpdateInput: true,
                    // minYear: moment().format("YYYY"),
                    // maxYear: moment().add(1, 'years').format("YYYY"),
                    maxDate: moment().add(1, 'days').toDate(),
                    ranges: {
                        'Today': [moment().set({
                            hour: 0,
                            minute: 0,
                            second: 0,
                            millisecond: 0
                        }), moment().set({
                            hour: 23,
                            minute: 59,
                            second: 59,
                            millisecond: 59
                        })],
                        'Yesterday': [moment().subtract(1, 'days').set({
                            hour: 0,
                            minute: 0,
                            second: 0,
                            millisecond: 0
                        }), moment().subtract(1, 'days').set({
                            hour: 23,
                            minute: 59,
                            second: 59,
                            millisecond: 59
                        })],
                        'Last 7 Days': [moment().subtract(6, 'days').set({
                            hour: 0,
                            minute: 0,
                            second: 0,
                            millisecond: 0
                        }), moment().set({
                            hour: 23,
                            minute: 59,
                            second: 59,
                            millisecond: 59
                        })],
                        'Last 30 Days': [moment().subtract(29, 'days').set({
                            hour: 0,
                            minute: 0,
                            second: 0,
                            millisecond: 0
                        }), moment().set({
                            hour: 23,
                            minute: 59,
                            second: 59,
                            millisecond: 59
                        })],
                        'This Month': [moment().startOf('month').set({
                            hour: 0,
                            minute: 0,
                            second: 0,
                            millisecond: 0
                        }), moment().endOf('month').set({
                            hour: 23,
                            minute: 59,
                            second: 59,
                            millisecond: 59
                        })],
                        'Last Month': [moment().subtract(1, 'month').startOf('month').set({
                            hour: 0,
                            minute: 0,
                            second: 0,
                            millisecond: 0
                        }), moment().subtract(1, 'month').endOf('month').set({
                            hour: 23,
                            minute: 59,
                            second: 59,
                            millisecond: 59
                        })],
                        'This Year': [moment().startOf('year').set({
                            hour: 0,
                            minute: 0,
                            second: 0,
                            millisecond: 0
                        }), moment().endOf('year').set({
                            hour: 23,
                            minute: 59,
                            second: 59,
                            millisecond: 59
                        })],
                        'Last Year': [moment().subtract(1, 'year').startOf('year').set({
                            hour: 0,
                            minute: 0,
                            second: 0,
                            millisecond: 0
                        }), moment().subtract(1, 'year').endOf('year').set({
                            hour: 23,
                            minute: 59,
                            second: 59,
                            millisecond: 59
                        })]
                    }
                }, cb);
                cb(start, end);
            }
            const Customer_Ticket = () => {
                try {
                    let updateuserid = $('#updateuserid').val();
                    // var formdate = moment($('#ticketctime').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
                    // var todate = moment($('#ticketctime').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");
                    if (updateuserid == '') {
                        toast('error', 'User ID Missing!');
                        return false;
                    }
                    // if (formdate == '' || todate == '') {
                    //    toast('error', 'Kindly select the date');
                    //    return false;
                    // }
                    var table = $("#customer_ticket").DataTable({
                        destroy: true,
                        pageLength: 10,
                        order: [
                            [6, 'desc']
                        ],
                        columnDefs: [{
                            type: 'date',
                            targets: [6]
                        }],
                        paging: true,
                        searching: true,
                        info: true,
                        ajax: {
                            url: window.location.origin + "/ajax/service/customer_ticket_services.php",
                            method: "POST",
                            dataSrc: "",
                            data: {
                                method: 'Customer_Ticket',
                                user_id: updateuserid,
                                // formdate: formdate,
                                // todate: todate
                            }
                        },
                        dom: 'Bfrtip',
                        buttons: [
                            'pageLength',
                            'copy',
                            {
                                extend: 'csvHtml5',
                                title: 'Customer Ticket Reports - ( ' + updateuserid + ' )'
                            },
                            {
                                extend: 'excelHtml5',
                                title: 'Customer Ticket Reports - ( ' + updateuserid + ' )'
                            },
                            {
                                extend: 'pdfHtml5',
                                orientation: 'landscape',
                                pageSize: 'LEGAL',
                                title: 'Customer Ticket Reports - ( ' + updateuserid + ' )'
                            }, 'print',
                        ],
                        columns: [{
                                targets: 0, // Target the first column
                                render: function(data, type, row, meta) {
                                    return meta.row + 1; // Add 1 to start from 1 instead of 0
                                }
                            },
                            {
                                data: null,
                                render: function(data, type, row, meta) {
                                    return data.ticketNo; // Add 1 to start from 1 instead of 0
                                }
                            },
                            {
                                data: null,
                                render: function(data, type, row, meta) {
                                    const raffleids = JSON.parse(data.raffleIds);
                                    return `<textarea readonly="">${raffleids.join(", ")}</textarea>`;
                                }
                            },
                            {
                                data: null,
                                render: function(data, type, row, meta) {
                                    return data.netTotal;
                                }
                            },
                            {
                                data: null,
                                render: function(data, type, row, meta) {
                                    return data.shipamount;
                                }
                            },
                            {
                                data: null,
                                render: function(data, type, row, meta) {
                                    return data.grandtotal;
                                }
                            },
                            {
                                data: null,
                                render: function(data, type, row, meta) {
                                    return moment(data.purchaseDatetime).format("DD MMM YYYY hh:mm A");
                                }
                            },
                            {
                                data: null,
                                render: function(data, type, row, meta) {
                                    return moment(data.endDate).format("DD MMM YYYY");
                                }
                            },
                            {
                                data: null,
                                render: function(data, type, row, meta) {
                                    var invoiceLink = data.invoiceNo ? `<a target="_blank" href="<?= $baseurl; ?>invoice/${data.referenceID}" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span style="font-size: 18px;color: #40411f !important;font-weight: 500;" class="fa fa-file-text-o">&nbsp;Invoice</span></a>` : '';
                                    return `<a target="_blank" href="<?= $baseurl; ?>ticket-view/${data.referenceID}" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span style="font-size: 18px;color: green !important;font-weight: 500;" class="fa fa-files-o">&nbsp;Ticket</span></a>&nbsp;
                     ${invoiceLink}
                            `;
                                }
                            },
                            //    {
                            //       data: "raffleid"
                            //    },
                            //       {
                            //      data: null,
                            //      render: function(data, type, row, meta) {
                            //          // Check if the data is already an array (no need to parse)
                            //          if (Array.isArray(data.raffleid)) {
                            //              return formatMatrix(data.raffleid);
                            //          }
                            //          try {
                            //              // Attempt to parse the JSON data
                            //              var parsedData = JSON.parse(data.raffleid);
                            //              if (Array.isArray(parsedData)) {
                            //                  return formatMatrix(parsedData);
                            //              } else {
                            //                  // Handle unexpected JSON format
                            //                  console.error("Unexpected JSON format:", parsedData);
                            //                  return "Error: Invalid data format";
                            //              }
                            //          } catch (error) {
                            //              // Handle JSON parsing error
                            //              console.error("Error parsing JSON:", error);
                            //              return "Error: Invalid JSON";
                            //          }
                            //      }
                            //  },
                            // {
                            //    data: "proamt"
                            // },
                            // {
                            //    data: "purdate"
                            // },
                            // {
                            //    data: "endDate"
                            // }
                        ],
                        //    footerCallback: function(row, data, start, end, display) {
                        //       var api = this.api();
                        //       var intVal = function(i) {
                        //          return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                        //       };
                        //       total = api
                        //          .column(4)
                        //          .data()
                        //          .reduce(function(a, b) {
                        //             return intVal(a) + intVal(b);
                        //          }, 0);
                        //       pageTotal = api
                        //          .column(4, {
                        //             page: 'current'
                        //          })
                        //          .data()
                        //          .reduce(function(a, b) {
                        //             return intVal(a) + intVal(b);
                        //          }, 0);
                        //       $(api.column(4).footer()).html('' + pageTotal + ' ( ' + total + ' total)');
                        //    },
                    });
                } catch (e) {
                    console.log('Error: ' + e);
                }
            }
            function transaction() {
                var table = $('#transaction').DataTable();
                table.destroy();
                let updateuserid = $('#updateuserid').val();
                var formdate = moment($('#transhctime').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
                var todate = moment($('#transhctime').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");
                if (updateuserid == '') {
                    toast('error', 'User ID Missing!');
                    return false;
                }
                if (formdate == '' || todate == '') {
                    toast('error', 'Kindly select the date');
                    return false;
                }
                table = $("#transaction").DataTable({
                    pageLength: 10,
                    order: [
                        [
                            0, 'desc'
                        ]
                    ],
                    columnDefs: [{
                        type: 'date',
                        targets: [1]
                    }],
                    paging: true,
                    searching: true,
                    info: true,
                    ajax: {
                        url: window.location.origin + "/ajax/service/customer_ticket_services.php",
                        method: "POST",
                        dataSrc: "",
                        data: {
                            method: 'transaction_table',
                            user_id: updateuserid,
                            formdate: formdate,
                            todate: todate
                        }
                    },
                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength',
                        'copy',
                        {
                            extend: 'excelHtml5',
                            title: 'Customer Transaction - ( ' + updateuserid + ' )'
                        },
                    ],
                    columns: [{
                            data: "date"
                        },
                        {
                            data: "purchaseType",
                            render: function(data, type, row) {
                                if (row.purchaseType) {
                                    return row.purchaseType;
                                } else {
                                    return 'NA';
                                }
                            }
                        },
                        {
                            data: "planType",
                            render: function(data, type, row) {
                                if (row.planType) {
                                    return row.planType;
                                } else {
                                    return 'NA';
                                }
                            }
                        },
                        {
                            data: "gateway",
                            render: function(data, type, row) {
                                if (row.gateway) {
                                    return row.gateway;
                                } else {
                                    return 'NA';
                                }
                            }
                        },
                        {
                            data: "paymentStatus",
                            render: function(data, type, row) {
                                if (row.paymentStatus == "Paid" || row.paymentStatus == "SUCCESS") {
                                    return 'SUCCESS';
                                } else if (row.paymentStatus == "Pending" || row.paymentStatus == "Failed") {
                                    return 'FAILED';
                                } else {
                                    return 'NA';
                                }
                            }
                        },
                        {
                            data: "ticketReferenceID",
                            render: function(data, type, row) {
                                if (row.ticketReferenceID !== "") {
                                    return row.ticketReferenceID;
                                } else {
                                    return 'NA';
                                }
                            }
                        },
                        {
                            data: "Amount"
                        }
                    ],
                    footerCallback: function(row, data, start, end, display) {
                        var api = this.api();
                        var intVal = function(i) {
                            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ?
                                i : 0;
                        };
                        total = api
                            .column(5)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        pageTotal = api
                            .column(5, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        $(api.column(5).footer()).html('' + pageTotal + ' ( ' + total + ' total)');
                    },
                });
            }
            function formatMatrix(raffleIds) {
                var result = '';
                for (var i = 0; i < raffleIds.length; i += 4) {
                    for (var j = i; j < i + 4 && j < raffleIds.length; j++) {
                        result += raffleIds[j];
                        if (j < raffleIds.length - 1) {
                            result += ', ';
                        } else {
                            // result += ' end';
                        }
                    }
                    result += '<br>';
                }
                return result;
            }
        </script>
    <?php
    }
    ?>
    <script>
        var state = <?= json_encode(strtolower($row['address'])); ?>;
        var city = <?= json_encode(strtolower($row['city'])); ?>;
        $(function() {
            $('#userup').hide();
            if ($('#nationlaity').val() != '') {
                getState($('#nationlaity').val());
            }
            bank_check(<?= $bank_check; ?>);
            function autoUpdateSeatCapacity() {
                var cabType = $('#cab_types').val();
                var seatInput = $('#seat_capacity');

                if (seatInput.length === 0) return;

                if (!cabType) {
                    seatInput.val('');
                    seatInput.removeClass('is-invalid');
                    return;
                }

                // Fetch dynamic seat capacity via AJAX
                $.ajax({
                    url: window.location.origin + "/ajax/service/driverServices.php",
                    type: "POST",
                    dataType: "json",
                    data: {
                        method: 'get_cab_seat_capacity',
                        cab_type: cabType
                    },
                    beforeSend: function() {
                        seatInput.val('...'); // Show loading state
                    },
                    success: function(res) {
                        if (res.status && res.data && res.data.seat_capacity) {
                            seatInput.val(res.data.seat_capacity);
                        } else {
                            seatInput.val('');
                        }
                        seatInput.removeClass('is-invalid');
                    },
                    error: function() {
                        console.error("Failed to fetch seat capacity.");
                        seatInput.val('');
                    }
                });
            }

            // Initialize TomSelect to fix the dropdown UI
            if (typeof TomSelect !== 'undefined' && !window.cabTypesTomSelect && $('#cab_types').length) {
                window.cabTypesTomSelect = new TomSelect("#cab_types", {
                    create: false,
                    placeholder: "Select Model",
                    onChange: function() {
                        autoUpdateSeatCapacity();
                    }
                });
            }

            $('#cab_types').on('change', autoUpdateSeatCapacity);

        
           $('#cab_types').on('change click', autoUpdateSeatCapacity);

            
        });
        function updateDetails() {
            // Custom validation – no browser popups
            if (!validateForm()) {
                return false;
            }
            $('#updateBtn').prop('disabled', true).text('Updating...');
            var formdata = $('#editprofileform').serializeArray();
            // Handle mobile number: ensure it has '91' prefix when not readonly
            formdata.forEach(function(field) {
                if (field.name === 'mobile') {
                    var mobileInput = document.querySelector('input[name="mobile"]');
                    var fullMobile = mobileInput.getAttribute('data-fullmobile');
                    if (mobileInput.hasAttribute('readonly') && fullMobile) {
                        field.value = fullMobile; // use stored full number
                    } else {
                        let cleanNumber = field.value.trim().replace(/\D/g, '');
                        if (cleanNumber.length === 10) {
                            field.value = '91' + cleanNumber; // add country code
                        }
                    }
                }
            });
            var fuel_types = $('#fuel_types').val();
            formdata.push({
                name: 'method',
                value: "updateDetails"
            });
            formdata.push({
                name: 'fuel',
                value: fuel_types
            });
            var post_data = formdata;
            var onsuccess = function(data) {
                var response = JSON.parse(data);
                if (response != "") {
                    if (response.type == 1) {
                        toast('success', response.result);
                        // ✨ NEW: Trigger Score Calculation After Update ✨
                        let driverIdToUpdate = $('#updateuserid').val();
                        // Temporarily show updating status
                        $('#profileScoreBadge').html(`<i class="fa fa-spinner fa-spin me-1"></i>Updating...`);
                        // 1. Tell Laravel to calculate the new score
                        $.ajax({
                            url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-driver-profile-update",
                            type: "POST",
                            dataType: "json",
                            headers: {
                                "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                            },
                            data: {
                                driver_id: driverIdToUpdate
                            },
                            complete: function() {
                                // 2. ONLY AFTER calculation is done, fetch the fresh percentage directly from DB
                                $.ajax({
                                    url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-get-driver-details",
                                    type: "POST",
                                    dataType: "json",
                                    headers: {
                                        "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                                    },
                                    data: {
                                        driver_id: driverIdToUpdate
                                    },
                                    success: function(res) {
                                        if (res.status && res.data && res.data.driver) {
                                            let percent = parseInt(res.data.driver.profile_percentage) || 0;
                                            percent = Math.max(0, Math.min(100, percent));
                                            let badgeClass = percent < 40 ? 'bg-danger text-white' : (percent < 75 ? 'bg-warning text-dark' : 'bg-success text-white');
                                            $('#profileScoreBadge')
                                                .removeClass('bg-success bg-warning text-dark bg-danger text-white')
                                                .addClass(badgeClass)
                                                .html(`<i class="fa fa-check-circle me-1"></i>${percent}% Complete`);
                                        } else {
                                            $('#profileScoreBadge').html(`<i class="fa fa-info-circle me-1"></i>Refresh Needed`);
                                        }
                                    },
                                    error: function() {
                                        $('#profileScoreBadge').html(`<i class="fa fa-exclamation-circle me-1"></i>Error`);
                                    }
                                });
                            }
                        });
                        // ✨ END NEW CALCULATION LOGIC ✨
                    } else {
                        toast('error', response.result);
                    }
                }
                $('#updateBtn').prop('disabled', false).text('Update');
            };
            do_ajax_call(post_data, onsuccess);
        }
        // Helper: map field names to user‑friendly labels
        function getFieldLabel(fieldName) {
            const labels = {
                'name': 'Full Name',
                'mobile': 'Mobile Number',
                'email': 'Email Address',
                'billing_address': 'State',
                'districts_id': 'District',
                'address_us': 'Address',
                'language[]': 'Language',
                'user_role': 'User Role',
                'li_upto': 'License Expiry',
                'license_type': 'License Type',
                'cab_type': 'Vehicle Type',
                'fuel_types': 'Fuel Type',
                'exp': 'Driver Experience',
                'seat': 'Seat Capacity',
                'Luggage': 'Luggage Capacity',
                'insurance_upto': 'Vehicle Insurance Expiry',
                'rc_upto': 'RC Expiry',
                'puc_upto': 'PUC Expiry',
                'price_per_km': 'Price Per KM',
                'extra_price_per_km': 'Extra Price Per KM',
                'price_per_hour': 'Extra Price Per Hour',
                'price_per_day': 'Extra Price Per Day',
                'upi_id': 'UPI ID',
                'reviews': 'Reviews',
                'remarks': 'Remarks',
                'agent_id': 'Assigned Agent',
                'maker_model': 'Maker Model',
                'car_colour': 'Car Colour'
            };
            return labels[fieldName] || fieldName;
        }
        // Main validation function
        // Clear all previous highlights
        function clearHighlights() {
            $('.is-invalid').removeClass('is-invalid');
        }
        // Attach event listeners to remove highlight on input/change
        function attachHighlightReset() {
            // For text inputs, selects, textareas
            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
            });
            // For selectpicker (Bootstrap select) we need to listen to change on the original select
            $('.selectpicker').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
                $(this).removeClass('is-invalid');
            });
        }
        // Main validation function
        function validateForm() {
            // Clear previous highlights first
            clearHighlights();
            // Collect all required fields and their current values
            const fields = [{
                    name: 'name',
                    required: true,
                    pattern: /^[A-Za-z. ]{2,50}$/,
                    message: 'Full Name must contain only letters, dots and spaces (2-50 characters).'
                },
                {
                    name: 'mobile',
                    required: true,
                    pattern: /^(91)?[0-9]{10}$/,
                    message: 'Mobile Number must be a valid 10-digit number (with or without 91 prefix).'
                },
                {
                    name: 'email',
                    required: true,
                    pattern: /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/,
                    message: 'Enter a valid email address (max 75 characters, no spaces).'
                },
                {
                    name: 'billing_address',
                    required: true,
                    pattern: /.+/,
                    message: 'Please select a State.'
                },
                {
                    name: 'districts_id',
                    required: true,
                    pattern: /.+/,
                    message: 'Please select a District.'
                },
                {
                    name: 'address_us',
                    required: true,
                    pattern: /^.{1,200}$/,
                    message: 'Address is required (max 200 characters).'
                },
                {
                    name: 'language[]',
                    required: true,
                    pattern: /.+/,
                    message: 'Please select at least one Language.',
                    isSelectMultiple: true
                },
                {
                    name: 'user_role',
                    required: true,
                    pattern: /.+/,
                    message: 'Please select a User Role.'
                },
                {
                    name: 'li_upto',
                    required: true,
                    pattern: /^\d{4}-\d{2}-\d{2}$/,
                    message: 'License Expiry date is required.'
                },
                {
                    name: 'license_type',
                    required: true,
                    pattern: /.+/,
                    message: 'Please select a License Type.'
                },
                {
                    name: 'cab_type',
                    required: true,
                    pattern: /.+/,
                    message: 'Please select a Vehicle Type.'
                },
                {
                    name: 'fuel_types',
                    required: true,
                    pattern: /.+/,
                    message: 'Please select a Fuel Type.'
                },
                {
                    name: 'exp',
                    required: true,
                    pattern: /^[0-9]{1,2}$/,
                    message: 'Driver Experience must be a number between 1 and 99.'
                },
                {
                    name: 'seat',
                    required: true,
                    pattern: /^[0-9]{1,2}$/,
                    message: 'Seat Capacity must be a number between 1 and 99.'
                },
                {
                    name: 'Luggage',
                    required: true,
                    pattern: /^[0-9]{1,2}$/,
                    message: 'Luggage Capacity must be a number between 1 and 99.'
                },
                {
                    name: 'insurance_upto',
                    required: true,
                    pattern: /^\d{4}-\d{2}-\d{2}$/,
                    message: 'Insurance Expiry date is required.'
                },
                {
                    name: 'rc_upto',
                    required: true,
                    pattern: /^\d{4}-\d{2}-\d{2}$/,
                    message: 'RC Expiry date is required.'
                },
                {
                    name: 'puc_upto',
                    required: false,
                    pattern: /^\d{4}-\d{2}-\d{2}$/,
                    message: 'PUC Expiry date is required.'
                },
                // Fare fields are optional, but if present should match pattern
                {
                    name: 'price_per_km',
                    required: false,
                    pattern: /^\d+(\.\d{1,2})?$/,
                    message: 'Price Per KM must be a number (e.g., 10 or 10.50).'
                },
                {
                    name: 'extra_price_per_km',
                    required: false,
                    pattern: /^\d+(\.\d{1,2})?$/,
                    message: 'Extra Price Per KM must be a number (e.g., 10 or 10.50).'
                },
                {
                    name: 'price_per_hour',
                    required: false,
                    pattern: /^\d+(\.\d{1,2})?$/,
                    message: 'Extra Price Per Hour must be a number (e.g., 100 or 100.50).'
                },
                {
                    name: 'price_per_day',
                    required: false,
                    pattern: /^\d+(\.\d{1,2})?$/,
                    message: 'Extra Price Per Day must be a number (e.g., 500 or 500.50).'
                },
                {
                    name: 'upi_id',
                    required: false,
                    pattern: /^[A-Za-z0-9._-]+@[A-Za-z]+$/,
                    message: 'Enter a valid UPI ID (e.g., name@bank).'
                },
                {
                    name: 'reviews',
                    required: false,
                    pattern: /.*/
                },
                {
                    name: 'remarks',
                    required: false,
                    pattern: /.*/
                },
                {
                    name: 'agent_id',
                    required: false,
                    pattern: /.*/
                },
                {
                    name: 'maker_model',
                    required: false,
                    pattern: /.*/
                },
                {
                    name: 'car_colour',
                    required: false,
                    pattern: /.*/
                }
            ];
            // Check each field
            for (let field of fields) {
                let $element = $(`[name="${field.name}"]`);
                if ($element.length === 0) continue;
                let value;
                if (field.isSelectMultiple) {
                    // For multi-select, get selected values
                    value = $element.val() || [];
                    if (field.required && value.length === 0) {
                        $element.addClass('is-invalid');
                        toast('error', field.message);
                        $element.focus();
                        return false;
                    }
                } else {
                    value = $element.val() || '';
                    if (typeof value === 'string') value = value.trim();
                    // Required check
                    if (field.required && value === '') {
                        $element.addClass('is-invalid');
                        toast('error', getFieldLabel(field.name) + ' is required.');
                        $element.focus();
                        return false;
                    }
                    // Pattern check (if pattern exists and field has a value or is required)
                    if (field.pattern && value !== '') {
                        // For mobile, we allow both 10-digit and 12-digit with '91'
                        if (field.name === 'mobile') {
                            let digits = value.replace(/\D/g, '');
                            if (!(digits.length === 10 || (digits.length === 12 && digits.startsWith('91')))) {
                                $element.addClass('is-invalid');
                                toast('error', field.message);
                                $element.focus();
                                return false;
                            }
                        } else if (!field.pattern.test(value)) {
                            $element.addClass('is-invalid');
                            toast('error', field.message || getFieldLabel(field.name) + ' is invalid.');
                            $element.focus();
                            return false;
                        }
                    }
                }
            }
            // Additional date validations (dates should not be in the past)
            const today = new Date().toISOString().split('T')[0];
            const dateFields = ['li_upto', 'insurance_upto', 'rc_upto', 'puc_upto'];
            for (let name of dateFields) {
                let $el = $(`[name="${name}"]`);
                if ($el.length && $el.val()) {
                    if ($el.val() < today) {
                        $el.addClass('is-invalid');
                        toast('error', getFieldLabel(name) + ' cannot be in the past.');
                        $el.focus();
                        return false;
                    }
                }
            }
            return true;
        }
        // Function to fetch edit logs dynamically via AJAX
        function loadEditLogs() {
            let updateuserid = $('#updateuserid').val();
            // Show loading state
            $('#edit_log_content').html('<p class="text-center text-muted"><i class="fa fa-spinner fa-spin"></i> Loading logs...</p>');
            $('#editLogModal').modal('show'); // Open modal
            $.ajax({
                url: window.location.href, // Post to current file to trigger the PHP block at top
                type: 'POST',
                dataType: 'json',
                data: {
                    method: 'fetch_edit_logs',
                    user_id: updateuserid
                },
                success: function(response) {
                    $('#edit_log_content').html(response.html);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Log error for debugging
                    $('#edit_log_content').html('<p class="text-center text-danger">Failed to load logs. See console.</p>');
                }
            });
        }
        function updateverification() {
            let mobile = $('#exampleInputnumber').val();
            let updateuserid = $('#updateuserid').val();
            let typeOTP = $('#typeOTP').val();
            let formdata = [];
            if (typeOTP == 'send') {
                formdata.push({
                    name: 'mobile',
                    value: mobile
                });
                formdata.push({
                    name: 'updateuserid',
                    value: updateuserid
                });
            } else if (typeOTP == 'check') {
                formdata = $('.otp_form').serializeArray();
                formdata.push({
                    name: 'updateuserid',
                    value: updateuserid
                });
            }
            formdata.push({
                name: 'method',
                value: "updateverification"
            });
            formdata.push({
                name: 'type',
                value: typeOTP
            });
            var post_data = formdata;
            var onsuccess = function(data) {
                var response = JSON.parse(data);
                if (response != "") {
                    if (response.type == 1) {
                        document.getElementById('typeOTP').value = 'check';
                        document.getElementById('otperror2').innerHTML = '<div class="alert alert-success" role="alert">' + response.result + '</div>';
                        $('#otp').modal('show');
                    } else if (response.type == 0 || response.type == 2) {
                        document.getElementById('otperror2').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';
                        $('#otp').modal('show');
                    } else {
                        document.getElementById('typeOTP').value = 'send';
                        $('#newupotp').hide();
                        $('#userup').show();
                        document.getElementById('otperror2').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';
                        $('#otp').modal('hide');
                    }
                }
            }
            do_ajax_call(post_data, onsuccess);
        }
        function jump(field, autoMove) {
            if (field.value.length >= field.maxLength) {
                document.getElementById(autoMove).focus();
            }
        }
        function getCodeBoxElement(index) {
            return document.getElementById('otp' + index);
        }
        function onKeyUpEvent(index, event) {
            const eventCode = event.which || event.keyCode;
            if (getCodeBoxElement(index).value.length === 1) {
                if (index !== 4) {
                    getCodeBoxElement(index + 1).focus();
                } else {
                    getCodeBoxElement(index).blur();
                    // Submit code
                    console.log('submit code ');
                }
            }
            if (eventCode === 8 && index !== 1) {
                getCodeBoxElement(index - 1).focus();
            }
        }
        function onFocusEvent(index) {
            for (item = 1; item < index; item++) {
                if (window.CP.shouldStopExecution(0)) break;
                const currentElement = getCodeBoxElement(item);
                if (!currentElement.value) {
                    currentElement.focus();
                    break;
                }
            }
            window.CP.exitedLoop(0);
        }
        function toast(icon, message) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
            Toast.fire({
                icon: icon,
                title: message
            });
        }
        function getState(id) {
            if (id != '') {
                var formdata = [];
                formdata.push({
                    name: 'method',
                    value: "getState"
                });
                formdata.push({
                    name: 'id',
                    value: id
                });
                var post_data = formdata;
                var onsuccess = function(data) {
                    var response = JSON.parse(data);
                    if (response != "") {
                        if (response.type == 1) {
                            $('#billing_address').empty();
                            let len = response.result.length;
                            $('#billing_address').append(`<option value="">Select State / Emirates</option>`);
                            for (let i = 0; i < len; i++) {
                                optionText = response.result[i]['name'];
                                optionValue = response.result[i]['id'];
                                let svalue = (state.toLowerCase() == optionText.toLowerCase()) ? 'selected' : '';
                                $('#billing_address').append(`<option value="${optionValue}" ${svalue}>${optionText}</option>`);
                            }
                            getCity($('#billing_address').val());
                        } else {
                            // toast('error', response.result);
                        }
                        $('#billing_address').selectpicker('refresh');
                    }
                }
                do_ajax_call(post_data, onsuccess);
            } else {
                toast('error', 'Kindly Select Country')
            }
        }
        function getCity(id) {
            if (id != '') {
                var formdata = [];
                formdata.push({
                    name: 'method',
                    value: "getCity"
                });
                formdata.push({
                    name: 'id',
                    value: id
                });
                var post_data = formdata;
                var onsuccess = function(data) {
                    var response = JSON.parse(data);
                    if (response != "") {
                        if (response.type == 1) {
                            $('#billing_city').empty();
                            let len = response.result.length;
                            $('#billing_city').append(`<option value="">Select Area / District</option>`);
                            for (let i = 0; i < len; i++) {
                                optionText = response.result[i]['name'];
                                optionValue = response.result[i]['id'];
                                let svalue = (city.toLowerCase() == optionText.toLowerCase()) ? 'selected' : '';
                                $('#billing_city').append(`<option value="${optionText}" ${svalue}>${optionText}</option>`);
                                // $('#billing_city').val(res.result.name);
                            }
                        } else {
                            // toast('error', response.result);
                        }
                        $('#billing_city').selectpicker('refresh');
                    }
                }
                do_ajax_call(post_data, onsuccess);
            } else {
                // toast('error', 'Kindly Select State')
            }
        }
        $("#bank_name1").change(function() {
            var bank_name = $(this).val();
            $('#bank_name').prop('type', 'hidden');
            if (bank_name == '') {} else if (bank_name == 'Others') {
                $('#bank_name').prop('type', 'text');
            } else {
                document.getElementById('bank_name').value = bank_name;
            }
        });
        function bank_check(bank_check) {
            if (bank_check == '0') {
                $('#bank_name1').val('Others');
                $('#bank_name').prop('type', 'text');
            }
        }
        function age_validate() {
            $("#expirydateerror").html("");
            let chooshedYear = parseInt(moment($("#passport_expiry").val(), "YYYY").format('Y'));
            if (chooshedYear < 1900) {
                toast('error', 'Invalid Year!');
                return false;
            } else if (chooshedYear > 2200) {
                toast('error', 'Invalid Year!');
                return false;
            } else {
                if (calAge('passport_expiry') < 18) {
                    toast('error', 'You age under 18 not qualified to participate to try your luck.');
                    return false;
                }
                return true;
            }
        }
        function calAge(id) {
            let date1 = new Date(document.getElementById(id).value);
            let date2 = new Date();
            let yearsDiff = date2.getFullYear() - date1.getFullYear();
            return parseInt(yearsDiff);
        }
        function toast(icon, message) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
            Toast.fire({
                icon: icon,
                title: message
            })
        }
        // Function to open the modal and fetch existing remarks
        function openRemarksModal(user_id, user_name) {
            $('#rmk_user_id').val(user_id);
            $('#rmk_user_name').text(user_name);
            $('#new_remark_text').val(''); // Clear textbox
            $('#remarks_table_body').html('<tr><td colspan="4" class="text-center"><span class="spinner-border spinner-border-sm"></span> Loading...</td></tr>');
            $('#remarksModal').modal('show');
            fetchRemarks(user_id);
        }
        // Function to fetch remarks from the database
        function fetchRemarks(user_id) {
            $.ajax({
                url: window.location.origin + "/ajax/service/datatable_services.php",
                method: "POST",
                dataType: "json",
                data: {
                    method: 'get_user_remarks',
                    user_id: user_id
                },
                success: function(response) {
                    let tbody = '';
                    if (response.type == 1 && response.data && response.data.length > 0) {
                        response.data.forEach((item, index) => {
                            tbody += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.contacted_person}</td>
                                    <td>${item.remarks}</td>
                                    <td>${item.created_at}</td>
                                </tr>
                            `;
                        });
                    } else {
                        tbody = '<tr><td colspan="4" class="text-center text-muted">No remarks found.</td></tr>';
                    }
                    $('#remarks_table_body').html(tbody);
                },
                error: function() {
                    $('#remarks_table_body').html('<tr><td colspan="4" class="text-center text-danger">Failed to load remarks.</td></tr>');
                }
            });
        }
        function saveRemark() {
            let user_id = $('#rmk_user_id').val();
            // FIX: Inject the PHP session ID directly into the JS variable.
            // If the session is somehow missing, it safely falls back to '1'.
            let memid = "<?= $_SESSION['memid'] ?? '1' ?>";
            let remarks = $('#new_remark_text').val();
            let btn = $('#btn_save_remark');
            if (remarks.trim() === '') {
                toast('error', 'Please enter a remark.');
                return;
            }
            btn.html('<span class="spinner-border spinner-border-sm"></span> Saving...').prop('disabled', true);
            $.ajax({
                url: window.location.origin + "/ajax/service/datatable_services.php",
                method: "POST",
                dataType: "json",
                data: {
                    method: 'save_user_remark',
                    user_id: user_id,
                    memid: memid, // This will now successfully send the exact ID
                    remarks: remarks
                },
                success: function(response) {
                    if (response.type == 1) {
                        toast('success', 'Remark added successfully!');
                        $('#new_remark_text').val(''); // Clear the textarea
                        fetchRemarks(user_id); // Refresh the table list dynamically
                    } else {
                        toast('error', response.message || 'Failed to add remark.');
                    }
                },
                error: function() {
                    toast('error', 'Something went wrong while saving.');
                },
                complete: function() {
                    btn.html('Submit Remark').prop('disabled', false);
                }
            });
        }
    </script>
<?php
} else {
    if ($subid4 != '') {
        $userid = $subid4;
    }
    $sql = "SELECT * FROM `user_register` WHERE `id` = '$userid'";
    $run = mysqli_query($con, $sql);
    if (mysqli_num_rows($run) > 0) {
        $row = $run->fetch_assoc();
    }
?>
    <style>
        .is-invalid {
            border: 2px solid #dc3545 !important;
            background-color: #fff8f8;
        }
        .is-invalid:focus {
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
            border-color: #dc3545;
        }
        .blue-button {
            color: #FFF;
            background-color: #428BCA;
            border: 1px solid #357EBD;
        }
    </style>
    <div class="main-content app-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">
                <div class="page-header">
                    <h1 class="page-title">Menu Permissions</h1>
                    <div>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Pages</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">My Profile</div>
                            </div>
                            <div class="card-body">
                                <div class="text-center chat-image mb-5">
                                    <div class="avatar avatar-xxl chat-profile mb-3 brround">
                                        <a class="" href="agentview"><img alt="avatar" src="assets/images/users/2.jpg" class="brround"></a>
                                    </div>
                                    <div class="main-chat-msg-name">
                                        <h5 class="mb-1 text-dark fw-semibold">Goride</h5>
                                        </a>
                                        <p class="text-muted mt-0 mb-0 pt-0 fs-13">ID - <?= $row['id']; ?></p>
                                    </div>
                                </div>
                                <ul class="list-group no-margin">
                                    <li class="list-group-item d-flex ps-3">
                                        <div class="social social-profile-buttons me-2">
                                            <a class="social-icon text-primary" href=""><i class="fe fe-mail"></i></a>
                                        </div>
                                        <a href="javascript:void(0)" class="my-auto"><?= $row['email']; ?></a>
                                    </li>
                                    <li class="list-group-item d-flex ps-3">
                                        <div class="social social-profile-buttons me-2">
                                            <a class="social-icon text-primary" href=""><i class="fa fa-address-card-o"></i></a>
                                        </div>
                                        <a href="javascript:void(0)" class="my-auto"><?= $row['passport']; ?></a>
                                    </li>
                                    <li class="list-group-item d-flex ps-3">
                                        <div class="social social-profile-buttons me-2">
                                            <a class="social-icon text-primary" href=""><i class="fe fe-phone"></i></a>
                                        </div>
                                        <a href="javascript:void(0)" class="my-auto"><?= $row['mobile']; ?></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <?php
                        if ($subid3 == 'permission') {
                        ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Menu Permission</div>
                                        </div>
                                        <div class="card-body">
                                            <form action="" method="post">
                                                <?php
                                                $menupermission = mysqli_query($con, "SELECT * FROM  `orm_menu`  where `main`='0' AND `deletes`='0' ORDER BY `id` ASC");
                                                while ($menupermission1 = mysqli_fetch_array($menupermission)) {
                                                    $userper = mysqli_query($con, "select * FROM  `menu_permission` where `userid`='$subid4' and `menu`='$menupermission1[id]' ");
                                                    $row = mysqli_fetch_array($userper);
                                                    $numrow = mysqli_num_rows($userper);
                                                    $row['id'];
                                                ?>
                                                    <div class="form-group">
                                                        <label class="custom-switch form-switch mb-0">
                                                            <input type="checkbox" name="other[<?php echo $menupermission1['id']; ?>]" id="other<?php echo $menupermission1['id']; ?>" value="<?php echo  $menupermission1['name']; ?>" <?php if ($row) { ?> checked="checked" <?php } ?> class="custom-switch-input">
                                                            <input type="hidden" name="otherid[<?php echo $menupermission1['id']; ?>]" id="otherid<?php echo $menupermission1['id']; ?>" style="width:25px;" value="<?php echo  $menupermission1['id']; ?>">
                                                            <span class="custom-switch-indicator"></span>
                                                            <span class="custom-switch-description"><?php echo $menupermission1['name']; ?></span>
                                                        </label>
                                                        <div class="row">
                                                            <?php
                                                            $submenupermission = mysqli_query($con, "SELECT * FROM  `orm_menu`  where `main`='$menupermission1[id]' ORDER BY `order` ASC;");
                                                            while ($submenupermission1 = mysqli_fetch_array($submenupermission)) {
                                                                $subper = mysqli_query($con, "select * FROM  `menu_permission` where `userid`='$subid4' and `menu`='$submenupermission1[id]'");
                                                                $row1 = mysqli_fetch_array($subper);
                                                            ?>
                                                                <div class="col-md-1"></div>
                                                                <div class="col-md-8">
                                                                    <label class="custom-switch form-switch mb-0">
                                                                        <input type="checkbox" name="other[<?php echo $submenupermission1['id']; ?>]" id="other<?php echo $submenupermission1['id']; ?>" value="<?php echo  $submenupermission1['id']; ?>" <?php if ($row1) { ?> checked <?php } ?> class="custom-switch-input">
                                                                        <input type="hidden" name="otherid[<?php echo $submenupermission1['id']; ?>]" id="otherid<?php echo $submenupermission1['id']; ?>" style="width:25px;" value="<?php echo  $submenupermission1['id']; ?>">
                                                                        <span class="custom-switch-indicator"></span>
                                                                        <span class="custom-switch-description"><?php echo $submenupermission1['name']; ?></span>
                                                                    </label>
                                                                </div>
                                                                <div class="row">
                                                                    <?php
                                                                    $seconmenupermission = mysqli_query($con, "SELECT * FROM  `orm_menu`  where `main`='$submenupermission1[id]' ORDER BY `order` ASC;");
                                                                    while ($seconmenupermission1 = mysqli_fetch_array($seconmenupermission)) {
                                                                        $secondper = mysqli_query($con, "select * FROM  `menu_permission` where `userid`='$subid4' and `menu`='$seconmenupermission1[id]'");
                                                                        $secondper1 = mysqli_fetch_array($secondper);
                                                                    ?>
                                                                        <div class="col-md-2"></div>
                                                                        <div class="col-md-8">
                                                                            <label class="custom-switch form-switch mb-0">
                                                                                <input type="checkbox" name="other[<?php echo $seconmenupermission1['id']; ?>]" id="other<?php echo $seconmenupermission1['id']; ?>" value="<?php echo  $seconmenupermission1['id']; ?>" <?php if ($secondper1) { ?> checked <?php } ?> class="custom-switch-input">
                                                                                <input type="hidden" name="otherid[<?php echo $seconmenupermission1['id']; ?>]" id="otherid<?php echo $submenupermission1['id']; ?>" style="width:25px;" value="<?php echo  $seconmenupermission1['id']; ?>">
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo $seconmenupermission1['name']; ?></span>
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-md-2"></div>
                                                                    <?php } ?>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div class="form-group">
                                                    <input type="submit" name="menuper" id="menuper" value="Submit" style="margin-left: 0px;" class="btn blue-button">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        } ?>
                    </div>
                <?php } ?>
                <?php
                if ($row['state'] !== "") { ?>
                    <script>
                        $(document).ready(function() {
                            getCity(<?= $row['state']; ?>);
                            attachHighlightReset();
                        })
                    </script>
                <?php
                }
                ?>