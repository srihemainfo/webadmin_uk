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
    // --- NEW HELPER: FORMAT DATE (Y-m-d to d-m-Y) ---
    function formatLogDate($val)
    {
        if (is_string($val) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $val)) {
            return date('d-m-Y', strtotime($val));
        }
        return $val;
    }
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
function mapFuelType($fuel)
{
    if (empty($fuel)) return '';
    $fuel = strtoupper(trim($fuel));
    $fuel = preg_replace('/[\s+&]/', '/', $fuel);
    if (strpos($fuel, 'PETROL') !== false && strpos($fuel, 'CNG') !== false) {
        return 'PETROL/CNG';
    }
    if (strpos($fuel, 'PETROL') !== false) return 'PETROL';
    if (strpos($fuel, 'DIESEL') !== false) return 'DIESEL';
    if (strpos($fuel, 'CNG') !== false) return 'CNG';
    return $fuel;
}

if ($subid3 != 'permission') {
    if ($subid3 != '') {
        $userid = $subid3;
    } else {
        $userid = $_SESSION['memid'];
    }
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

    $RcUpto = !empty($row['rc_upto']) ? $row['rc_upto'] : ($vehicleDetails['rc_expiry_date'] ?? $vehicleDetails['rc_details']['response']['vehicle_details']['fit_up_to'] ?? '');
    if ($RcUpto && strtotime($RcUpto) > 0) $RcUpto = date('Y-m-d', strtotime($RcUpto));

    $pucUpto = !empty($row['puc_upto']) ? $row['puc_upto'] : ($vehicleDetails['puc_details']['puc_exp_date'] ?? $vehicleDetails['rc_details']['response']['permit_details']['pucc_upto'] ?? '');
    if ($pucUpto && strtotime($pucUpto) > 0) $pucUpto = date('Y-m-d', strtotime($pucUpto));

    $insuranceUpto = !empty($row['insurance_upto']) ? $row['insurance_upto'] : ($vehicleDetails['insurance_details']['insurance_exp_date'] ?? $vehicleDetails['rc_details']['response']['finance_details']['insurance_upto'] ?? '');
    if ($insuranceUpto && strtotime($insuranceUpto) > 0) $insuranceUpto = date('Y-m-d', strtotime($insuranceUpto));
    
    $SeatCapacity = !empty($row['seat']) ? $row['seat'] : ($vehicleDetails['rc_details']['response']['vehicle_details']['seat_capacity'] ?? '');
    $rawFuel = !empty($row['fuel_types']) ? $row['fuel_types'] : ($vehicleDetails['rc_details']['response']['vehicle_details']['fuel_type'] ?? '');
    $fuelType = mapFuelType($rawFuel);
    $jsonType = $vehicleDetails['type'] ?? '';
    
    $cabType = (!empty($jsonType) && !preg_match('/^[0-9]+$/', $jsonType))
        ? $jsonType
        : ($vehicleDetails['rc_details']['response']['vehicle_details']['body_type']
            ?? (!empty($row['cab_type']) && !preg_match('/^[0-9]+$/', $row['cab_type'])
                ? $row['cab_type']
                : ''));

    $makerModel = !empty($row['maker_model']) ? $row['maker_model'] : ($vehicleDetails['rc_details']['response']['vehicle_details']['maker_model'] ?? '');
    $carColour = !empty($row['car_colour']) ? $row['car_colour'] : ($vehicleDetails['rc_details']['response']['vehicle_details']['color'] ?? '');
    $userInfo = $vehicleDetails['user_info'] ?? [];
    $langSource = !empty($row['language']) ? $row['language'] : ($userInfo['language'] ?? '');
    $selectedLanguages = !empty($langSource) ? explode(',', $langSource) : [];
    $luggageValue = !empty($row['Luggage']) ? $row['Luggage'] : ($userInfo['luggage'] ?? '');
    $roll_id =  select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'", "roll_id", "");
    $acc_type = ['savings', 'current'];
    $bank_list = [
        'First Abu Dhabi Bank (FAB)', 'Emirates NBD', 'Abu Dhabi Commercial Bank', 'Dubai Islamic Bank',
        'MashreqBank', 'Abu Dhabi Islamic Bank (ADIB)', 'HSBC Bank Middle East - UAE Operations',
        'Union National Bank', 'Commercial Bank of Dubai (CBD)', 'Emirates Islamic Bank',
        'National Bank of Ras Al Khaimah (RAKBANK)', 'Al Hilal Bank', 'Noor Bank', 'Sharjah Islamic Bank',
        'National Bank of Fujairah', 'Others'
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
    /* Fix hover effect while changing tabs */
.nav-tabs .nav-link {
    transition: background-color 0.2s ease, border-color 0.2s ease;
}

.nav-tabs .nav-link:hover:not(.active) {
    background-color: #f8f9fa;
    border-color: #dee2e6 #dee2e6 #fff;
}

.nav-tabs .nav-link.active {
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
    color: #000 !important;
}

.nav-tabs .nav-link.active:hover {
    background-color: #fff;
    cursor: default;
}

.nav-tabs .nav-link:focus {
    outline: none;
}
        .bootstrap-select>.dropdown-toggle { background: #fff }
        .brround { height: 80px; width: 81px; }
        .bootstrap-select .dropdown-menu li a, .dropdown-item.active { padding: 3px 0 24px 22px !important }
        .inner.show { min-height: 135px !important; max-height: 134px !important }
        .dropdown-menu.show { width: 100% }
        .bootstrap-select .dropdown-toggle .filter-option { height: auto }
        .back-arrow-btn i { background: #fff; font-size: 16px; padding: 2px 3px; border-radius: 50px; border: 2px solid #6c6e70; color: #6c6e70; margin-right: 15px; width: 24px; height: 24px }
        textarea { height: 100px; padding: 12px 20px; box-sizing: border-box; border: 2px solid #ccc; border-radius: 4px; background-color: #f8f8f8; font-size: 16px; resize: none; text-align: center; }
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
                                                    $assigned_agent_id = $row['agent_id'] ?? null;
                                                    $query = mysqli_query($con, "SELECT id, name, lname FROM user_register WHERE roll_id = 3 AND deletes = '0' ");
                                                    if ($query) {
                                                        while ($agentsList = mysqli_fetch_assoc($query)) {
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
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <?php if ($roll_id == 1 || $roll_id == 2 || $roll_id == 6 && ($row['id'] != $_SESSION['memid']) && $row['roll_id'] != 3 && $row['roll_id'] != 4 && $row['roll_id'] != 5) { ?>
                <div class="row mt-4">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body pt-4">
                                <div class="grid-margin">
                                    <ul class="nav nav-tabs mb-4" id="walletTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link active text-dark fw-bold" id="tab2-tab" data-bs-toggle="tab" href="#tab2" role="tab" onclick="transaction()">Transaction History</a>
                                        </li>
                                        <li class="nav-item" role="presentation">
    <a class="nav-link text-dark fw-bold" id="wallet-tab" data-bs-toggle="tab" href="#walletTabPane" role="tab" onclick="loadWalletHistory()">Wallet</a>
</li>
                                        <!--<li class="nav-item" role="presentation">-->
                                        <!--    <a class="nav-link text-dark fw-bold" id="tab5-tab" data-bs-toggle="tab" href="#tab5" role="tab" onclick="balance_summary()">Balance Sheet</a>-->
                                        <!--</li>-->
                                        <!--<li class="nav-item" role="presentation">-->
                                        <!--    <a class="nav-link text-dark fw-bold" id="tab6-tab" data-bs-toggle="tab" href="#tab6" role="tab" onclick="new_balance_summary()">New Balance Sheet</a>-->
                                        <!--</li>-->
                                        <!--<li class="nav-item" role="presentation">-->
                                        <!--    <a class="nav-link text-dark fw-bold" id="tab7-tab" data-bs-toggle="tab" href="#tab7" role="tab" onclick="n_balance_summary()">CB Balance Sheet</a>-->
                                        <!--</li>-->
                                    </ul>

                                    <div class="tab-content border-0 pt-0" id="walletTabsContent">
                                        <div class="tab-pane fade show active" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                                            <div class="row mb-3 align-items-center">
                                                <div class="col-lg-4 col-md-6 mb-2">
                                                    <div id="transhctime" class="form-control d-flex justify-content-between align-items-center" style="background: #fff; cursor: pointer;">
    <div><i class="fa fa-calendar me-2"></i><span class="selected-date"></span></div>
    <i class="fa fa-caret-down"></i>
</div>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <button class="btn btn-primary px-4" onclick="transaction()">Go</button>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="transaction" class="table table-bordered text-nowrap border-bottom w-100">
                                                    <thead class="bg-light">
                                                        <tr>
                                                            <th class="wd-15p border-bottom-0">Date &amp; Time</th>
                                                            <th class="wd-15p border-bottom-0">Purchase Type</th>
                                                            <th class="wd-15p border-bottom-0">Payment Gateway</th>
                                                            <th class="wd-15p border-bottom-0">Payment Status</th>
                                                            <th class="wd-15p border-bottom-0">Transaction ID</th>
                                                            <th class="wd-15p border-bottom-0">Amount (Rs)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                    <tfoot></tfoot>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="tab5" role="tabpanel" aria-labelledby="tab5-tab">
                                            <div class="table-responsive">
                                                <div class="col-12">
                                                    <h4 style="text-align: center;" class="mb-3">Balance Sheet Abstract</h4>
                                                    <table class="table table-bordered">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th scope="col">Summary</th>
                                                                <th scope="col">Winning</th>
                                                                <th scope="col">Add Credit</th>
                                                                <th scope="col">Cash</th>
                                                                <th scope="col">Bonus</th>
                                                                <th scope="col">Withdraw</th>
                                                                <th scope="col">Wallet Ticket</th>
                                                                <th scope="col">Cash Ticket</th>
                                                                <th scope="col">Bonus Ticket</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><strong>Sub Total</strong></td>
                                                                <td id="winning_amt" style="color: green; font-weight: bolder;"></td>
                                                                <td id="addcredit_amt" style="color: green; font-weight: bolder;"></td>
                                                                <td id="cash_amt" style="color: blue; font-weight: bolder;"></td>
                                                                <td id="bonus_amt" style="color: blue; font-weight: bolder;"></td>
                                                                <td id="withdraw_amt" style="color: red; font-weight: bolder;"></td>
                                                                <td id="wt_amt" style="color: red; font-weight: bolder;"></td>
                                                                <td id="cahst_amt" style="color: red; font-weight: bolder;"></td>
                                                                <td id="bonust_amt" style="color: red; font-weight: bolder;"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Balance</strong></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td colspan="2" id="bal_cp" style="font-weight: bolder;"></td>
                                                                <td colspan="4" id="spent_balance" style="font-weight: bolder;"></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Total</strong></td>
                                                                <td colspan="2" id="wa_total" style="font-weight: bolder;"></td>
                                                                <td colspan="2" id="cb_total" style="font-weight: bolder;"></td>
                                                                <td colspan="4" id="spent_total" style="font-weight: bolder;"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="tab6" role="tabpanel" aria-labelledby="tab6-tab">
                                            <div class="table-responsive">
                                                <div class="col-12">
                                                    <h4 style="text-align: center;" class="mb-3">New Balance Sheet Abstract</h4>
                                                    <table class="table table-bordered">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th scope="col">Summary</th>
                                                                <th scope="col">Winning Balance</th>
                                                                <th scope="col">Add Credit</th>
                                                                <th scope="col" colspan="3" class="text-center">Total Points</th>
                                                                <th scope="col">Withdrawal</th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col"></th>
                                                                <th scope="col"></th>
                                                                <th scope="col"></th>
                                                                <th scope="col">Cash</th>
                                                                <th scope="col">Bonus</th>
                                                                <th scope="col">Wallet</th>
                                                                <th scope="col"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><strong>Cumulative</strong></td>
                                                                <td id="winning_amt_new" style="font-weight: bolder;"></td>
                                                                <td id="addcredit_amt_new" style="font-weight: bolder;"></td>
                                                                <td id="cash_amt_new" style="font-weight: bolder;"></td>
                                                                <td id="bonus_amt_new" style="font-weight: bolder;"></td>
                                                                <td id="withdraw_amt_new" style="font-weight: bolder;"></td>
                                                                <td id="wt_amt_new" style="font-weight: bolder;"></td>
                                                            </tr>
                                                            <tr style="background-color: #f8acac;">
                                                                <td><strong>Used/Withdrawal</strong></td>
                                                                <td id="withDraw_data" style="font-weight: bolder;"></td>
                                                                <td id="addc_data" style="font-weight: bolder;"></td>
                                                                <td id="cash_data_r" style="font-weight: bolder;"></td>
                                                                <td id="bonus_data_r" style="font-weight: bolder;"></td>
                                                                <td id="withdraw_data_r" style="font-weight: bolder;"></td>
                                                                <td id="wallet_data_r" style="font-weight: bolder;"></td>
                                                            </tr>
                                                            <tr style="background-color: #add3ac;">
                                                                <td><strong>Balance</strong></td>
                                                                <td id="withDraw_data_f" style="font-weight: bolder;"></td>
                                                                <td id="addc_data_f" style="font-weight: bolder;"></td>
                                                                <td id="cash_data_f" style="font-weight: bolder;"></td>
                                                                <td id="bonus_data_f" style="font-weight: bolder;"></td>
                                                                <td id="withdraw_data_f" style="font-weight: bolder;"></td>
                                                                <td id="wallet_data_f" style="font-weight: bolder;"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="tab7" role="tabpanel" aria-labelledby="tab7-tab">
                                            <div class="table-responsive">
                                                <div class="col-12">
                                                    <h4 style="text-align: center;" class="mb-3">CB Balance Sheet Abstract</h4>
                                                    <table class="table table-bordered">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th scope="col" class="align-middle">Summary</th>
                                                                <th scope="col" colspan="2" class="text-center">Ticket</th>
                                                                <th scope="col" colspan="2" class="text-center">Balance</th>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col"></th>
                                                                <th scope="col">BP</th>
                                                                <th scope="col">CP</th>
                                                                <th scope="col">BP</th>
                                                                <th scope="col">CP</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr style="background-color: #fff;">
                                                                <td><strong>Bonus Total</strong></td>
                                                                <td id="cash_data_r1" style="color: #f10000;font-weight: bolder;"></td>
                                                                <td id="bonus_data_r1" style="color: #f10000;font-weight: bolder;"></td>
                                                                <td id="withdraw_data_r1" style="color: green;font-weight: bolder;"></td>
                                                                <td id="wallet_data_r1" style="color: green;font-weight: bolder;"></td>
                                                            </tr>
                                                            <tr style="background-color: #fff;">
                                                                <td><strong>Cash Total</strong></td>
                                                                <td id="cash_data_f1" style="color: #f10000;font-weight: bolder;"></td>
                                                                <td id="bonus_data_f1" style="color: #f10000;font-weight: bolder;"></td>
                                                                <td id="withdraw_data_f1" style="color: green;font-weight: bolder;"></td>
                                                                <td id="wallet_data_f1" style="color: green;font-weight: bolder;"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane fade" id="walletTabPane" role="tabpanel" aria-labelledby="wallet-tab">
    <div class="row mb-3 align-items-center">
        <div class="col-lg-4 col-md-6 mb-2">
            <div id="wallethctime" class="form-control d-flex justify-content-between align-items-center" style="background: #fff; cursor: pointer;">
                <div><i class="fa fa-calendar me-2"></i><span class="selected-date"></span></div>
                <i class="fa fa-caret-down"></i>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <button class="btn btn-primary px-4" onclick="loadWalletHistory()">Go</button>
        </div>
    </div>
    <div class="table-responsive">
        <table id="walletHistoryTable" class="table table-bordered text-nowrap border-bottom w-100">
    <thead class="bg-light">
        <tr>
            <th class="wd-15p border-bottom-0">Date & Time</th>
            <th class="wd-10p border-bottom-0">Transaction Type</th>
            <th class="wd-10p border-bottom-0">Point Type</th>
            <th class="wd-10p border-bottom-0">Reward Type</th>
            <th class="wd-10p border-bottom-0">Status</th>
            <th class="wd-15p border-bottom-0">Job No</th> <th class="wd-10p border-bottom-0">Opening Bal (Rs)</th>
            <th class="wd-10p border-bottom-0">Amount (Rs)</th>
            <th class="wd-10p border-bottom-0">Closing Bal (Rs)</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
    </div>
</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
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
    
    <?php if ($roll_id == 1 || $roll_id == 2 || $roll_id == 6 && ($row['id'] != $_SESSION['memid']) && $row['roll_id'] != 3 && $row['roll_id'] != 4 && $row['roll_id'] != 5) { ?>
        <script>
    // Safety Definitions to prevent JS errors if the AJAX functions are loaded later
    function balance_summary() { console.log("Balance summary tab clicked"); }
    function new_balance_summary() { console.log("New balance summary tab clicked"); }
    function n_balance_summary() { console.log("CB balance summary tab clicked"); }
    function new_balance_customer_sheet() { console.log("Loading new balance sheet"); }
    function n_balance_customer_sheet() { console.log("Loading cb balance sheet"); }
    function show_more() {}
    function new_show_more() {}
    function n_show_more() {}

    var spreadsheet;
    var new_spreadsheet;
    var n_spreadsheet;
    
    // FIX: Add safety check before adding event listener
    var userRoleElement = document.getElementById('user_role');
    if (userRoleElement) {
        userRoleElement.addEventListener('change', function() {
            var type = $('#user_role').val();
            const companyDiv = document.getElementById('exampleCompany');
            const compLabel = document.getElementById('com_label');
            if (type == 'owner') {
                if (companyDiv) companyDiv.style.display = 'block';
                if (compLabel) compLabel.style.display = 'block';
            } else {
                if (companyDiv) companyDiv.style.display = 'none';
                if (compLabel) compLabel.style.display = 'none';
            }
        });
    }
    
    $(function() {
        $('#spreedcontent').hide();
        $('#new_spreedcontent').hide();
        $('#n_spreedcontent').hide();
        createDatePricket('transhctime');
        createDatePricket('wallethctime');
        transaction();
        new_balance_customer_sheet();
        n_balance_customer_sheet();
    });
    
    function validateName(input) {
        input.value = input.value.replace(/[^A-Za-z. ]/g, '');
        input.value = input.value.replace(/\s{2,}/g, ' ');
        input.value = input.value.replace(/^\s+/, '');
        if (input.value.length > 50) {
            input.value = input.value.substring(0, 50);
        }
    }
    function validateUpi(input) {
        input.value = input.value.replace(/[^A-Za-z0-9@._-]/g, '');
        input.value = input.value.replace(/\s/g, '');
    }
    function validateLuggage(input) {
        input.value = input.value.replace(/[^0-9]/g, '');
        if (input.value.length > 2) {
            input.value = input.value.substring(0, 2);
        }
        if (input.value.length > 1 && input.value.startsWith('0')) {
            input.value = input.value.replace(/^0+/, '');
        }
    }
    function validateExperience(input) {
        input.value = input.value.replace(/[^0-9]/g, '');
        if (input.value.length > 2) {
            input.value = input.value.substring(0, 2);
        }
        if (input.value.length > 1 && input.value.startsWith('0')) {
            input.value = input.value.replace(/^0+/, '');
        }
    }
    function validateSeat(input) {
        input.value = input.value.replace(/[^0-9]/g, '');
        if (input.value.length > 2) {
            input.value = input.value.substring(0, 2);
        }
        if (input.value.length === 2 && input.value.startsWith('0')) {
            input.value = input.value.replace(/^0+/, '');
        }
    }
    function validateEmail(input) {
        input.value = input.value.replace(/\s/g, '');
        if (input.value.length > 75) {
            input.value = input.value.substring(0, 75);
        }
    }
    
    if (document.getElementById("profile_form")) {
        document.getElementById("profile_form").addEventListener("submit", function(e) {
            let form = this;
            if (!form.checkValidity()) {
                e.preventDefault();
                form.reportValidity();
                return false;
            }
        });
    }
    
    // FIX: Simplified Date Picker Initialization
    function createDatePricket(id) {
        var $el = $('#' + id);
        if ($el.length === 0) return;
        
        var start = moment().startOf('month');
        var end = moment().endOf('month');
        
        function cb(start, end) {
            // Write the dates directly into the div, preserving the calendar icon
            $el.html('<i class="fa fa-calendar me-2"></i>' + start.format('MMM Do YYYY') + ' - ' + end.format('MMM Do YYYY') + '<i class="fa fa-caret-down ms-auto"></i>');
        }
        
        $el.daterangepicker({
            startDate: start,
            endDate: end,
            timePicker: false, // Changed to false for simpler UI, switch to true if time is needed
            maxSpan: { days: 365 },
            autoUpdateInput: true,
            maxDate: moment().add(1, 'days').toDate(),
            ranges: {
                'Today': [moment().startOf('day'), moment().endOf('day')],
                'Yesterday': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
                'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
                'Last 30 Days': [moment().subtract(29, 'days').startOf('day'), moment().endOf('day')],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);
        
        cb(start, end);
    }
    
    function transaction() {
    if ($('#transaction').length === 0) return;
    
    let updateuserid = $('#updateuserid').val();
    let formdate = '';
    let todate = '';
    
    if ($('#transhctime').length > 0) {
        let drp = $('#transhctime').data('daterangepicker');
        if (drp && drp.startDate) {
            formdate = drp.startDate.format("YYYY-MM-DD HH:mm:ss");
            todate = drp.endDate.format("YYYY-MM-DD HH:mm:ss");
        } else {
            formdate = moment().startOf('month').format("YYYY-MM-DD HH:mm:ss");
            todate = moment().endOf('month').format("YYYY-MM-DD HH:mm:ss");
        }
    }
    
    if (updateuserid == '') {
        toast('error', 'User ID Missing!');
        return false;
    }
    
    var table = $('#transaction').DataTable();
    table.destroy();
    
    // Added Category Header
    $('#transaction thead').html(`
        <tr class="bg-light">
            <th class="wd-15p border-bottom-0">Date & Time</th>
            <th class="wd-15p border-bottom-0">Purchase Type</th>
            <th class="wd-15p border-bottom-0">Category</th>
            <th class="wd-15p border-bottom-0">Payment Gateway</th>
            <th class="wd-15p border-bottom-0">Status</th>
            <th class="wd-15p border-bottom-0">Job No</th>
            <th class="wd-15p border-bottom-0">Transaction ID</th>
            <th class="wd-15p border-bottom-0">Amount (Rs)</th>
        </tr>
    `);
    
    table = $("#transaction").DataTable({
        pageLength: 10,
        order: [ [ 0, 'desc' ] ],
        columnDefs: [{ type: 'date', targets: [0] }],
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
            { extend: 'excelHtml5', title: 'Customer Transaction - ( ' + updateuserid + ' )' },
        ],
        columns: [
            { 
                data: "date" 
            },
            { 
                data: "purchaseType",
                render: function(data) { return data ? data : 'NA'; }
            },
            { 
                data: "category", // <-- ADDED CATEGORY DATA BINDING
                render: function(data) { return data ? data : '-'; }
            },
            { 
                data: "gateway",
                render: function(data) { return data ? data : 'NA'; }
            },
            { 
                data: "paymentStatus",
                render: function(data) {
                    let status = (data || '').toUpperCase();
                    if (status === "SUCCESS" || status === "PAID") {
                        return '<span class="badge bg-success text-white px-2 py-1">SUCCESS</span>';
                    } else if (status === "PENDING") {
                        return '<span class="badge bg-warning text-dark px-2 py-1">PENDING</span>';
                    } else {
                        return '<span class="badge bg-danger text-white px-2 py-1">' + (status || 'FAILED') + '</span>';
                    }
                }
            },
            { 
                data: "job_no" 
            },
            { 
                data: "ticketReferenceID",
                render: function(data) { return data ? data : 'NA'; }
            },
            { 
                data: "Amount",
                render: function(data) { return `<span class="fw-bold text-dark">Rs ${parseFloat(data || 0).toFixed(2)}</span>`; }
            }
        ]
    });
}
function loadWalletHistory() {
    if ($('#walletHistoryTable').length === 0) return;
    
    let updateuserid = $('#updateuserid').val();
    let formdate = '';
    let todate = '';
    
    if ($('#wallethctime').length > 0) {
        let drp = $('#wallethctime').data('daterangepicker');
        if (drp && drp.startDate) {
            formdate = drp.startDate.format("YYYY-MM-DD HH:mm:ss");
            todate = drp.endDate.format("YYYY-MM-DD HH:mm:ss");
        } else {
            formdate = moment().startOf('month').format("YYYY-MM-DD HH:mm:ss");
            todate = moment().endOf('month').format("YYYY-MM-DD HH:mm:ss");
        }
    }
    
    if (updateuserid == '') {
        toast('error', 'User ID Missing!');
        return false;
    }
    
    var table = $('#walletHistoryTable').DataTable();
    table.destroy();
    
    table = $("#walletHistoryTable").DataTable({
        pageLength: 10,
        order: [ [ 0, 'desc' ] ],
        columnDefs: [{ type: 'date', targets: [0] }],
        paging: true,
        searching: true,
        info: true,
        ajax: {
            url: window.location.origin + "/ajax/service/customer_ticket_services.php",
            method: "POST",
            dataSrc: "",
            data: {
                method: 'wallet_history_table',
                user_id: updateuserid,
                formdate: formdate,
                todate: todate
            }
        },
        dom: 'Bfrtip',
        buttons: [
            'pageLength',
            'copy',
            { extend: 'excelHtml5', title: 'Wallet Balance History - ( ' + updateuserid + ' )' },
        ],
        columns: [
            { data: "date" },
            { 
                data: "transaction_type",
                render: function(data) { return data ? `<span class="fw-bold">${data}</span>` : '-'; }
            },
            { data: "point_type", render: function(data) { return data ? data : '-'; } },
            { data: "reward_type", render: function(data) { return data ? data : '-'; } },
            { 
                data: "paymentStatus",
                render: function(data) {
                    let status = (data || '').toUpperCase();
                    
                    // NEW: If status is just a hyphen (CRON job), return it plainly
                    if (status === "-") {
                        return '<span class="text-muted fw-bold">-</span>';
                    }
                    
                    if (status === "SUCCESS" || status === "PAID") {
                        return '<span class="badge bg-success text-white px-2 py-1">SUCCESS</span>';
                    } else if (status === "PENDING") {
                        return '<span class="badge bg-warning text-dark px-2 py-1">PENDING</span>';
                    } else {
                        return '<span class="badge bg-danger text-white px-2 py-1">' + (status || 'FAILED') + '</span>';
                    }
                }
            },
            { 
                data: "job_no",
                render: function(data) { return data ? data : '-'; } 
            },
            { 
                data: "opening_balance",
                render: function(data) { return `<span class="text-muted">Rs ${parseFloat(data || 0).toFixed(2)}</span>`; }
            },
            { 
                data: "total",
                render: function(data) { return `<span class="fw-bold text-dark">Rs ${parseFloat(data || 0).toFixed(2)}</span>`; }
            },
            { 
                data: "closeing_balance",
                render: function(data) { return `<span class="text-primary fw-bold">Rs ${parseFloat(data || 0).toFixed(2)}</span>`; }
            }
        ]
    });
}
</script>
    <?php
    }
    ?>
    <script>
        var state = <?= json_encode(strtolower($row['address'] ?? '')); ?>;
        var city = <?= json_encode(strtolower($row['city'] ?? '')); ?>;
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
            if (!validateForm()) {
                return false;
            }
            $('#updateBtn').prop('disabled', true).text('Updating...');
            var formdata = $('#editprofileform').serializeArray();
            formdata.forEach(function(field) {
                if (field.name === 'mobile') {
                    var mobileInput = document.querySelector('input[name="mobile"]');
                    var fullMobile = mobileInput.getAttribute('data-fullmobile');
                    if (mobileInput.hasAttribute('readonly') && fullMobile) {
                        field.value = fullMobile; 
                    } else {
                        let cleanNumber = field.value.trim().replace(/\D/g, '');
                        if (cleanNumber.length === 10) {
                            field.value = '91' + cleanNumber; 
                        }
                    }
                }
            });
            var fuel_types = $('#fuel_types').val();
            formdata.push({ name: 'method', value: "updateDetails" });
            formdata.push({ name: 'fuel', value: fuel_types });
            var post_data = formdata;
            var onsuccess = function(data) {
                var response = JSON.parse(data);
                if (response != "") {
                    if (response.type == 1) {
                        toast('success', response.result);
                        let driverIdToUpdate = $('#updateuserid').val();
                        $('#profileScoreBadge').html(`<i class="fa fa-spinner fa-spin me-1"></i>Updating...`);
                        $.ajax({
                            url: "<?= rtrim(TEST_API_DOMAIN_2 ?? '', '/') ?>/admin-driver-profile-update",
                            type: "POST",
                            dataType: "json",
                            headers: { "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()" },
                            data: { driver_id: driverIdToUpdate },
                            complete: function() {
                                $.ajax({
                                    url: "<?= rtrim(TEST_API_DOMAIN_2 ?? '', '/') ?>/admin-get-driver-details",
                                    type: "POST",
                                    dataType: "json",
                                    headers: { "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()" },
                                    data: { driver_id: driverIdToUpdate },
                                    success: function(res) {
                                        if (res.status && res.data && res.data.driver) {
                                            let percent = parseInt(res.data.driver.profile_percentage) || 0;
                                            percent = Math.max(0, Math.min(100, percent));
                                            let badgeClass = percent < 40 ? 'bg-danger text-white' : (percent < 75 ? 'bg-warning text-dark' : 'bg-success text-white');
                                            $('#profileScoreBadge').removeClass('bg-success bg-warning text-dark bg-danger text-white').addClass(badgeClass).html(`<i class="fa fa-check-circle me-1"></i>${percent}% Complete`);
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
                    } else {
                        toast('error', response.result);
                    }
                }
                $('#updateBtn').prop('disabled', false).text('Update');
            };
            do_ajax_call(post_data, onsuccess);
        }
        
        function getFieldLabel(fieldName) {
            const labels = {
                'name': 'Full Name', 'mobile': 'Mobile Number', 'email': 'Email Address', 'billing_address': 'State',
                'districts_id': 'District', 'address_us': 'Address', 'language[]': 'Language', 'user_role': 'User Role',
                'li_upto': 'License Expiry', 'license_type': 'License Type', 'cab_type': 'Vehicle Type', 'fuel_types': 'Fuel Type',
                'exp': 'Driver Experience', 'seat': 'Seat Capacity', 'Luggage': 'Luggage Capacity', 'insurance_upto': 'Vehicle Insurance Expiry',
                'rc_upto': 'RC Expiry', 'puc_upto': 'PUC Expiry', 'price_per_km': 'Price Per KM', 'extra_price_per_km': 'Extra Price Per KM',
                'price_per_hour': 'Extra Price Per Hour', 'price_per_day': 'Extra Price Per Day', 'upi_id': 'UPI ID', 'reviews': 'Reviews',
                'remarks': 'Remarks', 'agent_id': 'Assigned Agent', 'maker_model': 'Maker Model', 'car_colour': 'Car Colour'
            };
            return labels[fieldName] || fieldName;
        }
        
        function clearHighlights() { $('.is-invalid').removeClass('is-invalid'); }
        
        function attachHighlightReset() {
            $('input, select, textarea').on('input change', function() { $(this).removeClass('is-invalid'); });
            $('.selectpicker').on('changed.bs.select', function() { $(this).removeClass('is-invalid'); });
        }
        
        function validateForm() {
            clearHighlights();
            const fields = [
                { name: 'name', required: true, pattern: /^[A-Za-z. ]{2,50}$/, message: 'Full Name must contain only letters, dots and spaces (2-50 characters).' },
                { name: 'mobile', required: true, pattern: /^(91)?[0-9]{10}$/, message: 'Mobile Number must be a valid 10-digit number (with or without 91 prefix).' },
                { name: 'email', required: true, pattern: /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/, message: 'Enter a valid email address (max 75 characters, no spaces).' },
                { name: 'billing_address', required: true, pattern: /.+/, message: 'Please select a State.' },
                { name: 'districts_id', required: true, pattern: /.+/, message: 'Please select a District.' },
                { name: 'address_us', required: true, pattern: /^.{1,200}$/, message: 'Address is required (max 200 characters).' },
                { name: 'language[]', required: true, pattern: /.+/, message: 'Please select at least one Language.', isSelectMultiple: true },
                { name: 'user_role', required: true, pattern: /.+/, message: 'Please select a User Role.' },
                { name: 'li_upto', required: true, pattern: /^\d{4}-\d{2}-\d{2}$/, message: 'License Expiry date is required.' },
                { name: 'license_type', required: true, pattern: /.+/, message: 'Please select a License Type.' },
                { name: 'cab_type', required: true, pattern: /.+/, message: 'Please select a Vehicle Type.' },
                { name: 'fuel_types', required: true, pattern: /.+/, message: 'Please select a Fuel Type.' },
                { name: 'exp', required: true, pattern: /^[0-9]{1,2}$/, message: 'Driver Experience must be a number between 1 and 99.' },
                { name: 'seat', required: true, pattern: /^[0-9]{1,2}$/, message: 'Seat Capacity must be a number between 1 and 99.' },
                { name: 'Luggage', required: true, pattern: /^[0-9]{1,2}$/, message: 'Luggage Capacity must be a number between 1 and 99.' },
                { name: 'insurance_upto', required: true, pattern: /^\d{4}-\d{2}-\d{2}$/, message: 'Insurance Expiry date is required.' },
                { name: 'rc_upto', required: true, pattern: /^\d{4}-\d{2}-\d{2}$/, message: 'RC Expiry date is required.' },
                { name: 'puc_upto', required: false, pattern: /^\d{4}-\d{2}-\d{2}$/, message: 'PUC Expiry date is required.' },
                { name: 'price_per_km', required: false, pattern: /^\d+(\.\d{1,2})?$/, message: 'Price Per KM must be a number (e.g., 10 or 10.50).' },
                { name: 'extra_price_per_km', required: false, pattern: /^\d+(\.\d{1,2})?$/, message: 'Extra Price Per KM must be a number (e.g., 10 or 10.50).' },
                { name: 'price_per_hour', required: false, pattern: /^\d+(\.\d{1,2})?$/, message: 'Extra Price Per Hour must be a number (e.g., 100 or 100.50).' },
                { name: 'price_per_day', required: false, pattern: /^\d+(\.\d{1,2})?$/, message: 'Extra Price Per Day must be a number (e.g., 500 or 500.50).' },
                { name: 'upi_id', required: false, pattern: /^[A-Za-z0-9._-]+@[A-Za-z]+$/, message: 'Enter a valid UPI ID (e.g., name@bank).' }
            ];
            for (let field of fields) {
                let $element = $(`[name="${field.name}"]`);
                if ($element.length === 0) continue;
                let value;
                if (field.isSelectMultiple) {
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
                    if (field.required && value === '') {
                        $element.addClass('is-invalid');
                        toast('error', getFieldLabel(field.name) + ' is required.');
                        $element.focus();
                        return false;
                    }
                    if (field.pattern && value !== '') {
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
        
        function loadEditLogs() {
            let updateuserid = $('#updateuserid').val();
            $('#edit_log_content').html('<p class="text-center text-muted"><i class="fa fa-spinner fa-spin"></i> Loading logs...</p>');
            $('#editLogModal').modal('show');
            $.ajax({
                url: window.location.href,
                type: 'POST',
                dataType: 'json',
                data: { method: 'fetch_edit_logs', user_id: updateuserid },
                success: function(response) { $('#edit_log_content').html(response.html); },
                error: function(xhr, status, error) { $('#edit_log_content').html('<p class="text-center text-danger">Failed to load logs. See console.</p>'); }
            });
        }
        
        function updateverification() {
            let mobile = $('#exampleInputnumber').val();
            let updateuserid = $('#updateuserid').val();
            let typeOTP = $('#typeOTP').val();
            let formdata = [];
            if (typeOTP == 'send') {
                formdata.push({ name: 'mobile', value: mobile });
                formdata.push({ name: 'updateuserid', value: updateuserid });
            } else if (typeOTP == 'check') {
                formdata = $('.otp_form').serializeArray();
                formdata.push({ name: 'updateuserid', value: updateuserid });
            }
            formdata.push({ name: 'method', value: "updateverification" });
            formdata.push({ name: 'type', value: typeOTP });
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
        
        function getCodeBoxElement(index) { return document.getElementById('otp' + index); }
        
        function onKeyUpEvent(index, event) {
            const eventCode = event.which || event.keyCode;
            if (getCodeBoxElement(index).value.length === 1) {
                if (index !== 4) {
                    getCodeBoxElement(index + 1).focus();
                } else {
                    getCodeBoxElement(index).blur();
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
            Toast.fire({ icon: icon, title: message });
        }
        
        function openRemarksModal(user_id, user_name) {
            $('#rmk_user_id').val(user_id);
            $('#rmk_user_name').text(user_name);
            $('#new_remark_text').val('');
            $('#remarks_table_body').html('<tr><td colspan="4" class="text-center"><span class="spinner-border spinner-border-sm"></span> Loading...</td></tr>');
            $('#remarksModal').modal('show');
            fetchRemarks(user_id);
        }
        
        function fetchRemarks(user_id) {
            $.ajax({
                url: window.location.origin + "/ajax/service/datatable_services.php",
                method: "POST",
                dataType: "json",
                data: { method: 'get_user_remarks', user_id: user_id },
                success: function(response) {
                    let tbody = '';
                    if (response.type == 1 && response.data && response.data.length > 0) {
                        response.data.forEach((item, index) => {
                            tbody += `<tr><td>${index + 1}</td><td>${item.contacted_person}</td><td>${item.remarks}</td><td>${item.created_at}</td></tr>`;
                        });
                    } else {
                        tbody = '<tr><td colspan="4" class="text-center text-muted">No remarks found.</td></tr>';
                    }
                    $('#remarks_table_body').html(tbody);
                },
                error: function() { $('#remarks_table_body').html('<tr><td colspan="4" class="text-center text-danger">Failed to load remarks.</td></tr>'); }
            });
        }
        
        function saveRemark() {
            let user_id = $('#rmk_user_id').val();
            let memid = "<?= $_SESSION['memid'] ?? '1' ?>";
            let remarks = $('#new_remark_text').val();
            let btn = $('#btn_save_remark');
            if (remarks.trim() === '') { toast('error', 'Please enter a remark.'); return; }
            btn.html('<span class="spinner-border spinner-border-sm"></span> Saving...').prop('disabled', true);
            $.ajax({
                url: window.location.origin + "/ajax/service/datatable_services.php",
                method: "POST",
                dataType: "json",
                data: { method: 'save_user_remark', user_id: user_id, memid: memid, remarks: remarks },
                success: function(response) {
                    if (response.type == 1) {
                        toast('success', 'Remark added successfully!');
                        $('#new_remark_text').val('');
                        fetchRemarks(user_id);
                    } else {
                        toast('error', response.message || 'Failed to add remark.');
                    }
                },
                error: function() { toast('error', 'Something went wrong while saving.'); },
                complete: function() { btn.html('Submit Remark').prop('disabled', false); }
            });
        }
        
        function getState(id) {
            if (id != '') {
                var formdata = [];
                formdata.push({ name: 'method', value: "getState" });
                formdata.push({ name: 'id', value: id });
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
                        }
                        $('#billing_address').selectpicker('refresh');
                    }
                }
                do_ajax_call(post_data, onsuccess);
            }
        }
        
        function getCity(id) {
            if (id != '') {
                var formdata = [];
                formdata.push({ name: 'method', value: "getCity" });
                formdata.push({ name: 'id', value: id });
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
                            }
                        }
                        $('#billing_city').selectpicker('refresh');
                    }
                }
                do_ajax_call(post_data, onsuccess);
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
    </script>
<?php
}


if (isset($_POST['menuper'])) {
    $target_userid = $subid4; 

    // 1. Remove all existing permissions for this user to ensure a clean slate
    $delete_query = "DELETE FROM `menu_permission` WHERE `userid` = '$target_userid'";
    mysqli_query($con, $delete_query);

    // 2. Loop through the submitted checkboxes and insert the new permissions
    if (!empty($_POST['other'])) {
        foreach ($_POST['other'] as $selected_menu_id) {
            $safe_menu_id = mysqli_real_escape_string($con, $selected_menu_id);
            $insert_query = "INSERT INTO `menu_permission` (`userid`, `menu`) VALUES ('$target_userid', '$safe_menu_id')";
            mysqli_query($con, $insert_query);
        }
    }
    // Redirect cleanly to avoid blank screen and prevent form resubmission popups
    echo "<script>alert('Permissions Updated Successfully!'); window.location.href = window.location.href;</script>";
    exit; 
}

// Removed the 'else {' block so the page HTML always loads normally
if ($subid4 != '') {
    $userid = $subid4;
}
$sql = "SELECT * FROM `user_register` WHERE `id` = '$userid'";
    $run = mysqli_query($con, $sql);
    if (mysqli_num_rows($run) > 0) {
        $row = $run->fetch_assoc(); // $row holds user data
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
                                                    // Changed $row to $perm_row to avoid overwriting user details
                                                    $userper = mysqli_query($con, "SELECT * FROM `menu_permission` WHERE `userid`='$subid4' AND `menu`='{$menupermission1['id']}'");
                                                    $perm_row = mysqli_fetch_array($userper);
                                                ?>
                                                    <div class="form-group">
                                                        <label class="custom-switch form-switch mb-0">
                                                            <!-- FIXED: value is now ID, not Name -->
                                                            <input type="checkbox" name="other[<?php echo $menupermission1['id']; ?>]" id="other<?php echo $menupermission1['id']; ?>" value="<?php echo $menupermission1['id']; ?>" <?php if ($perm_row) { ?> checked="checked" <?php } ?> class="custom-switch-input">
                                                            <span class="custom-switch-indicator"></span>
                                                            <span class="custom-switch-description"><?php echo $menupermission1['name']; ?></span>
                                                        </label>
                                                        <div class="row">
                                                            <?php
                                                            $submenupermission = mysqli_query($con, "SELECT * FROM `orm_menu` WHERE `main`='{$menupermission1['id']}' ORDER BY `order` ASC");
                                                            while ($submenupermission1 = mysqli_fetch_array($submenupermission)) {
                                                                $subper = mysqli_query($con, "SELECT * FROM `menu_permission` WHERE `userid`='$subid4' AND `menu`='{$submenupermission1['id']}'");
                                                                $sub_row = mysqli_fetch_array($subper);
                                                            ?>
                                                                <div class="col-md-1"></div>
                                                                <div class="col-md-8">
                                                                    <label class="custom-switch form-switch mb-0">
                                                                        <input type="checkbox" name="other[<?php echo $submenupermission1['id']; ?>]" id="other<?php echo $submenupermission1['id']; ?>" value="<?php echo $submenupermission1['id']; ?>" <?php if ($sub_row) { ?> checked="checked" <?php } ?> class="custom-switch-input">
                                                                        <span class="custom-switch-indicator"></span>
                                                                        <span class="custom-switch-description"><?php echo $submenupermission1['name']; ?></span>
                                                                    </label>
                                                                </div>
                                                                <div class="row">
                                                                    <?php
                                                                    $seconmenupermission = mysqli_query($con, "SELECT * FROM `orm_menu` WHERE `main`='{$submenupermission1['id']}' ORDER BY `order` ASC");
                                                                    while ($seconmenupermission1 = mysqli_fetch_array($seconmenupermission)) {
                                                                        $secondper = mysqli_query($con, "SELECT * FROM `menu_permission` WHERE `userid`='$subid4' AND `menu`='{$seconmenupermission1['id']}'");
                                                                        $sec_row = mysqli_fetch_array($secondper);
                                                                    ?>
                                                                        <div class="col-md-2"></div>
                                                                        <div class="col-md-8">
                                                                            <label class="custom-switch form-switch mb-0">
                                                                                <input type="checkbox" name="other[<?php echo $seconmenupermission1['id']; ?>]" id="other<?php echo $seconmenupermission1['id']; ?>" value="<?php echo $seconmenupermission1['id']; ?>" <?php if ($sec_row) { ?> checked="checked" <?php } ?> class="custom-switch-input">
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
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
if (isset($row['state']) && $row['state'] !== "") { ?>
    <script>
        $(document).ready(function() {
            getCity("<?= htmlspecialchars($row['state']); ?>");
            attachHighlightReset(); 
        });
    </script>
<?php
}
?>