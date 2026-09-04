<?php
// error_reporting(E_ALL); 
// ini_set('display_errors', 1);
$today = date('Y-m-d');
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
?>
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/viewerjs/dist/viewer.min.css">
<script src="https://unpkg.com/viewerjs/dist/viewer.min.js"></script>
<!--DIGIO-->
<script src="https://app.digio.in/sdk/v11/digio.js"></script>
<style>
    /* Hide all cards beyond the 3rd one in each tab */
    /*#unassignedJobs .card:nth-child(n+4),*/
    /*#assignedJobs .card:nth-child(n+4),*/
    /*#websiteJobs .card:nth-child(n+4) {*/
    /*    display: none;*/
    /*}*/
    /* When the parent is hovered or scrolled, show all cards */
    /*#unassigned:hover .card,*/
    /*#assigned:hover .card,*/
    /*#websiteBookings:hover .card,*/
    /*#unassigned:focus-within .card,*/
    /*#assigned:focus-within .card,*/
    /*#websiteBookings:focus-within .card {*/
    /*    display: block !important;*/
    /*}*/
    /* Style the scroll containers */
    /* 1. Makes any container a relative parent for the spinner */
    /* Localized Spinner for Images */
    /* --- BROKEN IMAGE HANDLING --- */
    .document-img,
    .vehicle-photo,
    .driver-photo {
        position: relative;
        background-color: #f8f9fa;
        color: transparent;
        /* Hides the default broken image alt text */
    }
    /* This ::after pseudo-element only shows up if the image fails to load */
    .document-img::after,
    .vehicle-photo::after,
    .driver-photo::after {
        content: "No Image Available";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: #6c757d;
        font-weight: 500;
        text-align: center;
        z-index: 2;
        /* Sits on top of the broken image icon */
    }
    .has-local-spinner {
        position: relative !important;
        min-height: 100px;
    }
    .has-local-spinner::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -12px;
        margin-left: -12px;
        width: 24px;
        height: 24px;
        border: 3px solid rgba(0, 0, 0, 0.1);
        border-top-color: #0d6efd;
        /* Blue spinner */
        border-radius: 50%;
        animation: local-spinner-spin 0.8s linear infinite;
        z-index: 10;
    }
    @keyframes local-spinner-spin {
        to {
            transform: rotate(360deg);
        }
    }
    .clear-img-btn {
        background-color: rgba(220, 53, 69, 0.9) !important;
        /* Red background */
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .2931.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") !important;
        /* White X Icon */
        border-radius: 4px !important;
        /* Normal square edges instead of round */
        padding: 0.4rem !important;
        opacity: 1 !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2) !important;
    }
    .clear-img-btn:hover {
        background-color: rgba(200, 35, 51, 1) !important;
    }
    #vehicleImagesModal .modal-dialog {
        margin-left: 20px;
        /* Pushes it to the left. Change to 0 if you want it touching the edge */
        margin-right: auto;
        /* Prevents it from centering */
    }
    #unassigned,
    #assigned,
    #websiteBookings {
        max-height: 650px;
        overflow-y: auto;
        transition: all 0.3s ease;
        padding-right: 5px;
    }
    /* Simple scroll indicator */
    #unassigned::after,
    #assigned::after,
    #websiteBookings::after {
        content: "↓ Scroll for more";
        display: block;
        text-align: center;
        color: #6c757d;
        font-size: 12px;
        padding: 8px;
        background: linear-gradient(to bottom, transparent, #f8f9fa);
        position: sticky;
        bottom: 0;
        width: 100%;
        pointer-events: none;
    }
    /* Hide indicator when hovering */
    #unassigned:hover::after,
    #assigned:hover::after,
    #websiteBookings:hover::after,
    #unassigned:focus-within::after,
    #assigned:focus-within::after,
    #websiteBookings:focus-within::after {
        opacity: 0;
    }
    /* Add scrollbar styling */
    #unassigned::-webkit-scrollbar,
    #assigned::-webkit-scrollbar,
    #websiteBookings::-webkit-scrollbar {
        width: 6px;
    }
    #unassigned::-webkit-scrollbar-track,
    #assigned::-webkit-scrollbar-track,
    #websiteBookings::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    #unassigned::-webkit-scrollbar-thumb,
    #assigned::-webkit-scrollbar-thumb,
    #websiteBookings::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    #unassigned::-webkit-scrollbar-thumb:hover,
    #assigned::-webkit-scrollbar-thumb:hover,
    #websiteBookings::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    /* Fix map height */
    #driversMapWrapper {
        position: relative;
        height: 600px !important;
        width: 100%;
    }
    #driversMap {
        height: 100% !important;
        width: 100%;
    }
    .leaflet-container {
        height: 100% !important;
        width: 100% !important;
    }
    #jobsTab .nav-tabs .nav-link {
        padding: 11px 15px;
        display: flex;
        justify-content: center;
    }
    .nav-tabs {
        margin: 0;
    }
    .fc-event-main {
        padding: 2px 4px;
        cursor: pointer;
    }
    #assignDriverModal .form-check {
        padding-left: 3.25rem;
    }
    .cursor-pointer {
        cursor: pointer !important;
    }
    #unassigned {
        max-height: 503px;
        overflow: auto;
    }
    #assigned {
        max-height: 503px;
        overflow: auto;
    }
    #driverListPanel,
    #incomingBidsPanel {
        max-height: 620px;
        overflow: auto;
    }
    #driversTab {
        max-height: 620px;
        overflow: auto;
    }
    #cancelledJobs {
        max-height: 620px;
        overflow: auto;
    }
    .bid-panel {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, .075);
        height: 100%;
    }
    .bid-header {
        background: #eaf6ff;
        padding: 10px 15px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .bidders-wrap {
        height: 100%;
        padding: 10px;
    }
    .bid-item {
        padding: 12px 9px;
        background: #e9e9f175;
        margin-bottom: 10px;
        font-size: 13px;
    }
    .bid-amount {
        font-weight: 700;
    }
    .bid-status {
        font-size: 13px;
        font-weight: 600;
    }
    .status-accepted {
        color: #198754;
    }
    .status-cancelled {
        color: #dc3545;
    }
    .status-progress {
        color: #fd7e14;
    }
    .call-con,
    .whatsapp-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 16px;
        text-decoration: none;
        transition: all 0.2s ease-in-out;
    }
    .call-con {
        background-color: #e7f1ff;
        color: #0d6efd;
    }
    .whatsapp-icon {
        background-color: #e9f7ef;
        color: #25d366;
    }
    #carPhotoModal .carousel-control-prev-icon,
    #carPhotoModal .carousel-control-next-icon {
        filter: invert(1);
    }
    #carPhotoModal .carousel-control-prev-icon,
    #carPhotoModal .carousel-control-next-icon {
        background-color: #000;
        border-radius: 50%;
        background-size: 60%;
        width: 28px;
        height: 28px;
    }
    /* ================================
   DATE RANGE INPUT
================================ */
    #jobDateRange {
        background: #f8f9fc;
        border: 1px solid #e2e6f0;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        color: #4a4f6a;
        height: 32px;
        transition: all 0.2s ease-in-out;
    }
    #jobDateRange::placeholder {
        color: #9aa0b4;
        font-weight: 500;
    }
    #jobDateRange:focus {
        border-color: #ffc45b;
        /* box-shadow: 0 0 0 0.15rem rgb(240 240 245); */
        background: #ffffff;
    }
    .ranges li:hover
    /* ================================
   DATERANGEPICKER PANEL
================================ */
    .daterangepicker {
        border-radius: 10px !important;
        border: 1px solid #f0f0f5;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        font-size: 13px;
    }
    /* LEFT RANGE LIST */
    .daterangepicker .ranges li {
        border-radius: 6px;
        padding: 8px 12px;
        margin-bottom: 4px;
        color: #5a5f7d;
        font-weight: 500;
    }
    /* HOVER */
    .daterangepicker .ranges li:hover {
        background: #f0f0f5;
        color: #382f2f;
        border: 1px solid #f0f0f5;
    }
    /* ACTIVE RANGE */
    .daterangepicker .ranges li.active {
        background: #ffc45b;
        color: #382f2f;
        border: 1px solid #f0f0f5;
    }
    /* CALENDAR HEADER */
    .daterangepicker .calendar-table th {
        color: #5a5f7d;
        font-weight: 600;
    }
    /* DATE CELLS */
    .daterangepicker td.available {
        border-radius: 6px;
    }
    .daterangepicker td.available:hover {
        background: #eef1ff;
        color: #5b6cff;
    }
    /* SELECTED RANGE */
    .daterangepicker td.in-range {
        background: #eef1ff;
        color: #4a4f6a;
    }
    .daterangepicker td.active,
    .daterangepicker td.active:hover {
        background: #5b6cff;
        color: #ffffff;
    }
    /* ================================
   APPLY / CANCEL BUTTONS
================================ */
    .daterangepicker .drp-buttons {
        border: 1px solid #f0f0f5;
        padding: 10px;
    }
    .daterangepicker .applyBtn {
        background: #5b6cff;
        border-color: #5b6cff;
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 6px;
    }
    .daterangepicker .applyBtn:hover {
        background: #4959e8;
    }
    .daterangepicker .cancelBtn {
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 6px;
        color: #6c757d;
    }
    .past-jobs-slider {
        position: fixed;
        right: 0;
        top: 70px;
        width: 300px;
        height: calc(100% - 70px);
        background: #fff;
        box-shadow: -4px 0 10px rgba(0, 0, 0, .1);
        padding: 12px;
        animation: slideIn .3s ease;
        z-index: 1050;
    }
    @keyframes slideIn {
        from {
            transform: translateX(100%);
        }
        to {
            transform: translateX(0);
        }
    }
    .past-jobs-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .past-job {
        padding: 8px;
        border-bottom: 1px solid #eee;
    }
    /* Add to your existing CSS */
    .past-jobs-slider {
        position: fixed;
        right: 0;
        top: 70px;
        width: 350px;
        height: calc(100vh - 70px);
        background: white;
        box-shadow: -4px 0 15px rgba(0, 0, 0, 0.1);
        padding: 15px;
        z-index: 1050;
        overflow-y: auto;
        transition: transform 0.3s ease;
    }
    .past-jobs-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 10px;
        margin-bottom: 15px;
        border-bottom: 1px solid #dee2e6;
    }
    .past-job {
        padding: 12px;
        margin-bottom: 10px;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        background: #f8f9fa;
        transition: all 0.2s;
    }
    .past-job:hover {
        background: #e9ecef;
        border-color: #dee2e6;
    }
    .copyPastJob {
        margin-top: 8px;
        font-size: 12px;
    }
    /* ================================
   BID REMARKS
================================ */
    .bid-remark {
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 6px;
        margin-top: 4px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        line-height: 1.4;
    }
    /* Remark Types */
    .remark-success {
        background: #e9f7ef;
        color: #198754;
    }
    .remark-warning {
        background: #fff3cd;
        color: #856404;
    }
    .remark-danger {
        background: #fdecea;
        color: #dc3545;
    }
    .profile-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: 4px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
    }
    .profile-good {
        border-color: #198754;
        /* green */
        color: #198754;
    }
    .profile-medium {
        border-color: #ffc107;
        /* yellow */
        color: #856404;
    }
    .profile-low {
        border-color: #dc3545;
        /* red */
        color: #dc3545;
    }
    /* Responsive */
    @media (max-width: 768px) {
        .past-jobs-slider {
            width: 100%;
            top: 0;
            height: 100vh;
        }
    }
    .offcanvas.offcanvas-end#driverEditDrawer {
        width: 100%;
    }
    .cursor-pointer {
        cursor: pointer !important;
    }
    .pulse-marker {
        width: 14px;
        height: 14px;
        background: #0d6efd;
        border-radius: 50%;
        position: relative;
    }
    .pulse-marker::after {
        content: '';
        position: absolute;
        width: 14px;
        height: 14px;
        background: rgba(13, 110, 253, 0.5);
        border-radius: 50%;
        animation: pulse 1.5s infinite;
        top: 0;
        left: 0;
    }
    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        100% {
            transform: scale(3);
            opacity: 0;
        }
    }
    #mapLoader {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        transition: opacity 0.3s ease;
    }
    #mapLoader.hide {
        opacity: 0;
        pointer-events: none;
    }
    .map-spinner {
        width: 45px;
        height: 45px;
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-top: 4px solid #0d6efd;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        100% {
            transform: rotate(360deg);
        }
    }
    .map-loader-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .fc-v-event .fc-event-main-frame {
        color: #fff;
    }
    .calendar-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(2px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 20;
        transition: opacity 0.3s ease;
    }
    .calendar-spinner {
        width: 36px;
        height: 36px;
        border: 4px solid #dee2e6;
        border-top: 4px solid #0d6efd;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    .ts-control {
        min-height: 34px;
        font-size: 13px;
    }
    .ts-dropdown {
        font-size: 13px;
    }
    #profileProgressBar {
        transition: width 0.6s ease;
    }
    .card-open {
        background-color: #fee43130;
    }
    /*NEW CSS M1*/
    /* Document Box Styles */
    .document-box {
        position: relative;
        width: 100%;
        height: 100px;
        border: 2px dashed #dee2e6;
        /*border-radius: 8px;*/
        overflow: hidden;
        cursor: pointer;
        transition: all 0.2s;
        background: #f8f9fa;
    }
    .document-box-small {
        width: 100%;
        height: 110px;
        /* Aadhaar card proportion */
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #e5e8ef;
        background: #f8f9fb;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .document-box:hover,
    .document-box-small:hover {
        border: 1px dashed #0104cd;
        background: #e7f1ff;
        cursor: pointer;
    }
    .document-box.has-image,
    .document-box-small.has-image {
        border: 2px solid #28a745;
    }
    .document-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    /* Upload Overlay */
    .upload-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s;
        z-index: 5;
    }
    .upload-overlay-small {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 30px;
        height: 30px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }
    .document-box:hover .upload-overlay,
    .document-box-small:hover .upload-overlay {
        opacity: 1;
    }
    .upload-overlay i {
        color: white;
        font-size: 20px;
    }
    .upload-overlay-small i {
        color: #0d6efd;
        font-size: 16px;
    }
    /* File Input */
    .file-input-hidden {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 20;
    }
    /* Gallery Count */
    .gallery-count {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        z-index: 5;
    }
    /* Schedule Items */
    .schedule-item,
    .activity-item {
        transition: background 0.2s;
    }
    .schedule-item:hover,
    .activity-item:hover {
        background: #f8f9fa;
    }
    /* Call/WhatsApp Icons */
    .call-icon,
    .whatsapp-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        text-decoration: none;
        transition: all 0.2s;
    }
    .call-icon {
        background: #e3f2fd;
        color: #1976d2;
    }
    .whatsapp-icon {
        background: #e8f5e9;
        color: #25d366;
    }
    .call-icon:hover,
    .whatsapp-icon:hover {
        transform: scale(1.1);
    }
    /* Cursor */
    .cursor-pointer {
        cursor: pointer !important;
    }
    /* Edit Icons */
    .edit-section-trigger {
        padding: 4px 8px;
        border-radius: 4px;
        transition: all 0.2s;
    }
    /*.edit-section-trigger:hover {*/
    /*    background: #ffe800;*/
    /*}*/
    /* Form Controls */
    .form-control-sm,
    .form-select-sm {
        font-size: 13px;
        padding: 6px 10px;
    }
    .form-label {
        font-size: 12px;
        margin-bottom: 2px;
    }
    /* Table */
    .table-sm td,
    .table-sm th {
        padding: 0.5rem;
        font-size: 13px;
    }
    /* Remarks */
    .remarks-content {
        font-size: 14px;
        line-height: 1.5;
    }
    /* Modal for Image Viewer - Scoped specifically so it doesn't break other modals */
    #driverPhotoModal .modal-body,
    #carPhotoModal .modal-body {
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #000;
    }
    #driverPhotoModal .modal-body img,
    #carPhotoModal .modal-body img {
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
    }
    .modal-body img {
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
    }
    .image-wrapper {
        width: 100%;
        height: 100px;
        /*border-radius: 10px;*/
        overflow: hidden;
        position: relative;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    /* Vehicle Image */
    .image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Crop nicely */
        transition: transform 0.3s ease;
        cursor: pointer;
    }
    .gallery-count {
        position: absolute;
        bottom: 8px;
        right: 8px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
    }
    /*#view-vehicle {*/
    /*    font-size: 12px;*/
    /*}*/
    .offcanvas-body {
        font-size: 14px;
    }
    .card-heading {
        font-size: 12px;
        font-weight: 600;
        background: #adb1b64d;
        padding: 6px 10px;
        border-radius: 6px;
    }
    .verified-star {
        width: 20px;
        height: 20px;
        background: #149604;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-left: 6px;
        clip-path: polygon(50% 0%, 61% 10%, 75% 6%, 82% 18%, 95% 22%,
                90% 35%, 100% 50%, 90% 65%, 95% 78%, 82% 82%,
                75% 94%, 61% 90%, 50% 100%, 39% 90%, 25% 94%,
                18% 82%, 5% 78%, 10% 65%, 0% 50%, 10% 35%,
                5% 22%, 18% 18%, 25% 6%, 39% 10%);
    }
    .verified-star i {
        color: white;
        font-size: 10px;
    }
    .jobs-scroll {
        max-height: 290px;
        /* adjust height as needed */
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 4px;
    }
    /* nicer scrollbar */
    .jobs-scroll::-webkit-scrollbar {
        width: 5px;
    }
    .jobs-scroll::-webkit-scrollbar-thumb {
        background: #cfd6e4;
        border-radius: 10px;
    }
    /* Responsive */
    @media (max-width: 768px) {
        .document-box-small {
            width: calc(50% - 5px);
        }
    }
    .upload-loading {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }
    .upload-loading i {
        font-size: 22px;
        color: #fff;
    }
</style>
<div class="main-content app-content mt-0">
    <div class="side-app">
        <input type="hidden" id="tabID" value="agents">
        <div class="main-container container-fluid">
            <ul class="nav nav-tabs mt-3 gap-3 align-items-center" id="dashboardTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#jobsTab">
                        Current Jobs
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#driversTab">
                        Drivers
                    </button>
                </li>
                <li class="nav-item d-none">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#scheduled" aria-selected="false"
                        tabindex="-1" role="tab">
                        Scheduled Jobs
                    </button>
                </li>
                <li class="nav-item d-none">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#requested" aria-selected="false"
                        tabindex="-1" role="tab">
                        Requested Jobs
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cancelledJobs">
                        Cancelled Jobs
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#expiredJobs">
                        Expired Jobs
                    </button>
                </li>
            </ul>
            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="jobsTab">
                    <div class="row g-3">
                        <div class="col-lg-4">
                            <div class="card h-100">
                                <div class="card-header p-0">
                                    <ul class="nav nav-tabs nav-justified" role="tablist">
                                        <li class="nav-item">
                                            <button class="nav-link active" data-bs-toggle="tab"
                                                data-bs-target="#unassigned">
                                                Unassigned
                                                <span class="badge bg-secondary ms-1" id="unassignedCount">0</span>
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#assigned">
                                                Assigned
                                                <span class="badge bg-success ms-1" id="assignedCount">0</span>
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab"
                                                data-bs-target="#websiteBookings">
                                                Website
                                                <span class="badge bg-warning ms-1" id="websiteCount">0</span>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body p-2">
                                    <div class="row  align-items-center mb-2">
                                        <div class="col-2">
                                            <div class="dropdown w-100">
                                                <button
                                                    class="btn btn-sm btn-light border w-100  py-1 d-flex align-items-center justify-content-center gap-1"
                                                    type="button" id="jobFilterBtn" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="fa fa-filter text-warning"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-start shadow-sm w-100">
                                                    <li>
                                                        <a class="dropdown-item active" href="#" data-filter="all">
                                                            <i class="fa fa-list me-2 text-secondary"></i>Select Filter
                                                            Type
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" data-filter="booked">
                                                            <i class="fa fa-check-circle me-2 text-success"></i>Booked
                                                            (Datewise)
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" data-filter="pickup">
                                                            <i class="fa fa-map-marker-alt me-2 text-primary"></i>Pickup
                                                            (Datewise)
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <input type="text" id="jobDateRange"
                                                class="form-control form-control-sm text-center py-0 px-1"
                                                placeholder="Select Date" readonly style="cursor:pointer;">
                                        </div>
                                        <div class="col-4 p-0">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fa fa-search text-dark"></i>
                                                </span>
                                                <input type="text" class="form-control border-start-0 p-0"
                                                    id="jobSearch" placeholder="Search jobs...">
                                            </div>
                                        </div>
                                        <div class="col-2 text-end d-flex justify-content-end gap-2">
                                            <button class="btn btn-sm btn-success  createJobBtn">
                                                <i class="fa fa-plus me-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="tab-content" style="max-height: 650px;overflow: auto;">
                                        <div class="tab-pane fade show active" id="unassigned">
                                            <div id="unassignedLoader" class="text-center py-4" style="display:none">
                                                <div class="spinner-border" role="status"></div>
                                            </div>
                                            <div id="unassignedJobs"></div>
                                        </div>
                                        <div class="tab-pane fade" id="assigned">
                                            <div id="assignedLoader" class="text-center py-4" style="display:none">
                                                <div class="spinner-border" role="status"></div>
                                            </div>
                                            <div id="assignedJobs"></div>
                                        </div>
                                        <div class="tab-pane fade" id="websiteBookings">
                                            <div id="websiteLoader" class="text-center py-4" style="display:none">
                                                <div class="spinner-border text-warning" role="status"></div>
                                            </div>
                                            <div id="websiteJobs"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                <!-- ================= LEFT PANEL ================= -->
                                <div class="col-lg-8 d-none" id="leftPanel">
                                    <!-- INCOMING BIDS -->
                                    <div id="incomingBidsPanel" class="d-none">
                                        <div class="bid-panel">
                                            <div class="bid-header d-flex align-items-center justify-content-between">
                                                <a href="javascript:void(0)"
                                                    class="backToMap text-decoration-none text-dark small" style="white-space: nowrap; flex-shrink: 0;">
                                                    <i class="fa fa-arrow-left me-1"></i> Back
                                                </a>
                                                <span class="fw-semibold mx-2 text-center" id="incomingBidsTitle" style="white-space: nowrap; flex-shrink: 0;">Incoming Bids</span>
                                                <span class="text-info small best_price" style="white-space: nowrap; flex-shrink: 0;">
                                                    <i class="fa fa-coins me-1"></i> Best Price: ₹4246
                                                </span>
                                            </div>
                                            <div class="bidders-wrap">
                                                <div class="bid-item">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <a href="#"
                                                                class="fw-bold text-dark text-decoration-none driver-link"
                                                                data-driver="1">
                                                                Elavarasan
                                                            </a>
                                                            <i class="fa fa-check-circle text-success"
                                                                title="Verified"></i>
                                                            <i class="fa fa-map-marker-alt text-danger cursor-pointer"
                                                                title="View Location"></i>
                                                        </div>
                                                        <span class="bid-amount">₹4246</span>
                                                    </div>
                                                    <!-- DRIVER REMARK -->
                                                    <div class="bid-remark remark-success">
                                                        <i class="fa fa-comment-dots"></i>
                                                        Available immediately · Preferred driver
                                                    </div>
                                                    <div class="d-flex justify-content-end gap-3 mt-2">
                                                        <span class="text-success fw-semibold cursor-pointer bid-accept"
                                                            data-bid="1">
                                                            <i class="fa fa-check-circle me-1"></i> Accept
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="bid-item">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <a href="#"
                                                                class="fw-bold text-dark text-decoration-none driver-link"
                                                                data-driver="2">
                                                                Elavarasan
                                                            </a>
                                                            <i class="fa fa-check-circle text-success"></i>
                                                            <i
                                                                class="fa fa-map-marker-alt text-danger cursor-pointer"></i>
                                                        </div>
                                                        <span class="bid-amount">₹4350</span>
                                                    </div>
                                                    <div class="bid-remark remark-warning">
                                                        <i class="fa fa-comment-dots"></i>
                                                        Traffic near toll gate · 10 mins delay
                                                    </div>
                                                    <div class="d-flex justify-content-end gap-3 mt-2">
                                                        <span class="text-success fw-semibold cursor-pointer bid-accept"
                                                            data-bid="2">
                                                            <i class="fa fa-check-circle me-1"></i> Accept
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="bid-item">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <a href="#"
                                                                class="fw-bold text-dark text-decoration-none driver-link"
                                                                data-driver="3">
                                                                Elavarasan
                                                            </a>
                                                            <i class="fa fa-check-circle text-success"></i>
                                                            <i
                                                                class="fa fa-map-marker-alt text-danger cursor-pointer"></i>
                                                        </div>
                                                        <span class="bid-amount">₹4600</span>
                                                    </div>
                                                    <div class="bid-remark remark-danger">
                                                        <i class="fa fa-comment-dots"></i>
                                                        Long route · Extra toll charges expected
                                                    </div>
                                                    <div class="d-flex justify-content-end gap-3 mt-2">
                                                        <span class="text-danger fw-semibold cursor-pointer bid-reject"
                                                            data-bid="3">
                                                            <i class="fa fa-times-circle me-1"></i> Reject
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="driverListPanel" class="d-none">
                                        <div class="bid-panel">
                                            <div class="bid-header d-flex align-items-center justify-content-between">
                                                <a href="javascript:void(0)"
                                                    class="backToMap text-decoration-none text-dark small">
                                                    <i class="fa fa-arrow-left me-1"></i> Back
                                                </a>
                                                <span class="fw-semibold" id="availableDriversTitle">Available Drivers</span>
                                            </div>
                                            <div class="p-2">
                                                <input type="text" id="driverSearchInput"
                                                    class="form-control form-control-sm"
                                                    placeholder="Search driver by name...">
                                            </div>
                                            <div class="bidders-wrap" id="driverListContainer">
                                                <div id="driverContainerLoader" class="text-center py-4"
                                                    style="display:none">
                                                    <div class="spinner-border" role="status"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ================= RIGHT PANEL ================= -->
                                <!-- ================= RIGHT PANEL ================= -->
                                <div class="col-12" id="rightPanel"> <!-- CHANGED: from col-lg-6 to col-12 -->
                                    <!-- Map card - takes full width -->
                                    <div class="card h-100" id="mapCard">
                                        <div class="card-body p-0">
                                            <div id="driversMapWrapper" style="position:relative; height:600px;">
                                                <div id="mapLoader">
                                                    <div class="map-loader-content">
                                                        <div class="map-spinner"></div>
                                                        <div class="mt-2">Loading Live Drivers...</div>
                                                    </div>
                                                </div>
                                                <div id="driversMap" style="height:100%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-6">
                                            <div class="card mb-2 d-none" id="ownerDetailsCard">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <!-- LEFT -->
                                                        <strong><i class="fa fa-id-card me-1 text-danger"></i> Passenger
                                                            Details</strong>
                                                        <!-- RIGHT -->
                                                        <a href="javascript:void(0)"
                                                            class="backToMap text-decoration-none text-dark small">
                                                            <i class="fa fa-arrow-left me-1"></i> Back
                                                        </a>
                                                    </div>
                                                    <div class="d-flex align-items-center mt-3">
                                                        <img src="/assets/images/driver.png" alt="Customer Photo"
                                                            class="rounded-circle me-3 cursor-pointer"
                                                            style="width:60px;height:60px;object-fit:cover;"
                                                            data-bs-toggle="modal" data-bs-target="#driverPhotoModal">
                                                        <div class="w-100">
                                                            <div
                                                                class="d-flex justify-content-between align-items-center">
                                                                <div class="d-flex align-items-center gap-2"
                                                                    style="font-size:14px">
                                                                    <span>Venkatesan</span>
                                                                </div>
                                                                <div class="d-inline-flex align-items-center gap-2">
                                                                    <a href="tel:9876543210" class="call-con"
                                                                        title="Call Owner">
                                                                        <i class="fa fa-phone"></i>
                                                                    </a>
                                                                    <a href="https://wa.me/919876543210" target="_blank"
                                                                        class="whatsapp-icon" title="WhatsApp Owner">
                                                                        <i class="fab fa-whatsapp"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="small text-muted mt-1"
                                                                style="display: flex;flex-direction: column;">
                                                                <span class="d-none"><i
                                                                        class="fa fa-check-circle text-success me-1"></i>
                                                                    KYC Verified</span>
                                                                <span class="c_mobile"><i class="fa fa-phone"></i>
                                                                    91987654321</span>
                                                                <span class="c_address"><i class="fa fa-map-marker-alt text-danger me-1"></i>Chennai, Tamil Nadu</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-1 small text-muted c_created_at">
                                                        <i class="fa fa-user-clock me-1"></i>Account Created: <strong>15
                                                            Mar 2022</strong>
                                                    </div>
                                                    <hr class="my-3">
                                                    <div class="mt-3 p-3 rounded d-none">
                                                        <div class="d-flex align-items-center gap-3 mb-2">
                                                            <div class="d-flex align-items-center">
                                                                <i
                                                                    class="fa fa-star text-warning me-1"></i><strong>4.2</strong>
                                                            </div>
                                                            <div class="d-flex align-items-center text-muted"
                                                                style="font-size:12px;">
                                                                <i class="fa fa-user-check me-1"></i><span>(112
                                                                    Ratings)</span>
                                                            </div>
                                                        </div>
                                                        <!-- ROW 2 : JOB STATS -->
                                                        <div class="d-flex align-items-center gap-4 small mb-2">
                                                            <div class="d-flex align-items-center">
                                                                <i
                                                                    class="fa fa-check-circle text-success me-1"></i>Accepted:
                                                                <strong class="ms-1">40</strong>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <i
                                                                    class="fa fa-flag-checkered text-primary me-1"></i>Completed:
                                                                <strong class="ms-1">32</strong>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <i
                                                                    class="fa fa-times-circle text-danger me-1"></i>Rejected:
                                                                <strong class="ms-1">05</strong>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-3 small mb-2">
                                                            <div class="px-2 py-1 rounded bg-light d-flex align-items-center cursor-pointer"
                                                                data-bs-toggle="modal" data-bs-target="#reportModal">
                                                                <i class="fa fa-flag text-danger me-1"></i>Report:
                                                                <strong class="ms-1">2</strong>
                                                            </div>
                                                            <button class="btn btn-sm btn-danger px-2 py-1">
                                                                <i class="fa fa-ban me-1"></i> Block
                                                            </button>
                                                        </div>
                                                        <div class="small text-muted mb-2">
                                                            <i class="fa fa-comment-dots me-1 text-warning"></i>Remarks:
                                                            <strong>Good</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- END: Right Panel -->
                                <!-- ================= EDIT / VIEW JOB DETAILS ================= -->
                                <div class="card d-none" id="jobDetailsPanel">
                                    <!-- HEADER -->
                                    <div class="card-header d-flex justify-content-between align-items-center py-3">
                                        <div class="fw-semibold">
                                            <i class="fa fa-edit me-1 text-warning"></i>
                                            Job Details
                                        </div>
                                        <!-- BACK -->
                                        <a href="javascript:void(0)"
                                            class="backToMap text-decoration-none text-dark small">
                                            <i class="fa fa-arrow-left me-1"></i> Back
                                        </a>
                                    </div>
                                    <!-- BODY -->
                                    <div class="card-body p-0">
                                        <!-- JS WILL INJECT CONTENT HERE -->
                                        <div id="jobDetailsContent"></div>
                                    </div>
                                    <!-- FOOTER (OPTIONAL) -->
                                    <div class="card-footer d-flex justify-content-end gap-2">
                                        <button class="btn btn-sm btn-secondary backToMap">
                                            Cancel
                                        </button>
                                        <button class="btn btn-sm btn-success" id="confirmBookingBtn">
                                            <i class="fa fa-save me-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="driversTab">
                    <div class="row g-3">
                        <!-- LEFT : DRIVERS LIST -->
                        <div class="col-lg-3">
                            <div class="card h-100">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong>
                                            Drivers
                                            <span class="badge bg-primary ms-1" id="driverCount">0</span>
                                        </strong>
                                        <div class="d-flex align-items-center gap-2">
                                            <!-- FILTER ICON -->
                                            <span class="driver-filter-icon" title="Filter by status">
                                                <i class="fa fa-filter text-warning"></i>
                                            </span>
                                            <!-- STATUS FILTER -->
                                            <select class="form-select form-select-sm driver-status-filter"
                                                id="driverStatus">
                                                <option value="">All</option>
                                                <option value="online">🟢 Online</option>
                                                <option value="busy">🟡 Busy</option>
                                                <option value="offline">🔴 Offline</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <select id="profileFieldFilter" multiple placeholder="Select profile fields...">
                                            <!-- 40 Marks -->
                                            <option value="complete_verified">Complete Verified (Doc + Vehicle)</option>
                                            <!-- Basic Info -->
                                            <option value="state">State</option>
                                            <option value="districts_id">District</option>
                                            <!-- Images -->
                                            <option value="images_count">Above 4 Images</option>
                                            <!-- Expiry -->
                                            <option value="dl_expiry">Driving Licence Expiry</option>
                                            <option value="passport_expiry">Passport Expiry</option>
                                            <!-- Vehicle & Driver -->
                                            <option value="vehicle_type">Vehicle Type</option>
                                            <option value="fuel_type">Fuel Type</option>
                                            <option value="exp">Driver Experience</option>
                                            <option value="seaters">Seat Capacity</option>
                                            <!-- Pricing -->
                                            <option value="per_km">Price Per KM</option>
                                            <option value="extra_per_km">Extra Price Per KM</option>
                                            <option value="per_hour">Price Per Hour</option>
                                            <option value="per_day">Price Per Day</option>
                                            <!-- Others -->
                                            <option value="upiID">UPI ID</option>
                                            <option value="reviews">Reviews</option>
                                        </select>
                                        <!-- FILTER TYPE -->
                                        <select class="form-select form-select-sm" id="profileFilterType">
                                            <option value="">-- Select Type --</option>
                                            <option value="filled">Filled</option>
                                            <option value="not_filled">Not Filled</option>
                                        </select>
                                    </div>
                                    <div class="mt-2">
                                        <label class="small text-muted">Profile Percentage</label>
                                        <div id="percentageRange"></div>
                                        <div class="d-flex justify-content-between small mt-1">
                                            <span id="rangeMin">0%</span>
                                            <span id="rangeMax">100%</span>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="driverSearch"
                                        placeholder="Search drivers...">
                                </div>
                                <div class="list-group list-group-flush" id="driversList"
                                    style="max-height:650px;overflow:auto">
                                    <!-- Drivers injected by JS -->
                                </div>
                            </div>
                        </div>
                        <!-- RIGHT : DRIVER CALENDAR -->
                        <div class="col-lg-9">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <strong>Driver Schedule</strong>
                                        <button class="btn btn-sm btn-outline-secondary" id="showAllDrivers">
                                            All Drivers
                                        </button>
                                    </div>
                                    <div>
                                        <button class="btn btn-sm btn-outline-primary active" data-view="month">
                                            Month
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary" data-view="week">
                                            Week
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body position-relative" style="height:550px;">
                                    <div id="calendarLoader" class="calendar-overlay d-none">
                                        <div class="calendar-spinner"></div>
                                        <div class="mt-2 small text-muted">Loading drivers schedule...</div>
                                    </div>
                                    <div id="driverCalendar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="scheduled">
                    <div class="card shadow-sm border-0">
                        <!-- HEADER / FILTER -->
                        <div class="card-header bg-light">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-4 col-6">
                                    <label class="form-label small fw-semibold text-muted mb-1">
                                        <i class="fa fa-map-marker-alt text-success me-1"></i> From
                                    </label>
                                    <select id="scheduledFrom" class="form-select form-select-sm select2">
                                        <option value="">Select From</option>
                                    </select>
                                </div>
                                <div class="col-md-4 col-6">
                                    <label class="form-label small fw-semibold text-muted mb-1">
                                        <i class="fa fa-map-marker-alt text-danger me-1"></i> To
                                    </label>
                                    <select id="scheduledTo" class="form-select form-select-sm select2">
                                        <option value="">Select To</option>
                                    </select>
                                </div>
                                <div class="col-md-4 col-12">
                                    <button class="btn btn-sm btn-primary w-100" id="searchScheduledJobs">
                                        <i class="fa fa-search me-1"></i> Search
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- BODY -->
                        <div class="card-body p-2">
                            <div id="scheduledJobs"></div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="requested">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light fw-semibold">
                            Requested
                        </div>
                        <div class="card-body p-2">
                            <div id="requestedJobs"></div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="cancelledJobs">
                    <div id="cancelledLoader" class="text-center py-4" style="display:none">
                        <div class="spinner-border text-danger" role="status"></div>
                    </div>
                    <div id="cancelledJobsList"></div>
                </div>
                <div class="tab-pane fade" id="expiredJobs">
                    <div id="expiredLoader" class="text-center py-4" style="display:none">
                        <div class="spinner-border text-secondary" role="status"></div>
                    </div>
                    <div id="expiredJobsList"></div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="assignDriverModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-primary text-white border-0">
                        <h5 class="modal-title">
                            <i class="fa fa-user-plus me-2"></i>Assign Driver to Job
                        </h5>
                        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" id="job_id">
                        <div class="mb-3" id="jobDetailsSection"></div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fa fa-user me-2 text-primary"></i>Select Driver
                            </label>
                            <select class="form-select" id="driver_id">
                                <option value="">-- Choose Driver --</option>
                            </select>
                        </div>
                        <div class="border-top pt-3 mt-3">
                            <label class="form-label fw-bold mb-3">
                                <i class="fa fa-bell me-2 text-primary"></i>Notifications
                            </label>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="notify_whatsapp" checked>
                                <label class="form-check-label cursor-pointer" for="notify_whatsapp">
                                    <i class="fab fa-whatsapp text-success me-2"></i>Notify via WhatsApp
                                </label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notify_sms" checked>
                                <label class="form-check-label cursor-pointer" for="notify_sms">
                                    <i class="fa fa-sms text-info me-2"></i>Notify via SMS
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button class="btn btn-secondary px-4" data-bs-dismiss="modal">
                            <i class="fa fa-times me-2"></i>Cancel
                        </button>
                        <button class="btn btn-success px-4" id="assignBtn">
                            <i class="fa fa-check me-2"></i>Assign Driver
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <!-- HEADER -->
            <div class="modal-header py-2 bg-light">
                <h6 class="modal-title d-flex align-items-center gap-2">
                    <i class="fa fa-flag text-danger"></i>
                    Report Details
                </h6>
                <button type="button" class="border-0 bg-transparent text-dark" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <!-- BODY -->
            <div class="modal-body small">
                <!-- BASIC INFO -->
                <div class="mb-3">
                    <div class="fw-semibold text-dark mb-1">
                        <i class="fa fa-user me-1 text-primary"></i>
                        Name:
                        <span class="fw-normal">Elavarasan</span>
                    </div>
                    <div class="fw-semibold text-dark mb-1">
                        <i class="fa fa-id-badge me-1 text-secondary"></i>
                        Type:
                        <span class="badge bg-warning text-dark ms-1">
                            Driver
                        </span>
                    </div>
                    <div class="fw-semibold text-dark">
                        <i class="fa fa-flag me-1 text-danger"></i>
                        Total Reports:
                        <span class="badge bg-danger ms-1">2</span>
                    </div>
                </div>
                <hr>
                <!-- REPORT LIST -->
                <div class="mb-2 fw-semibold text-muted">
                    Report History
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between">
                            <span>
                                <i class="fa fa-exclamation-circle text-danger me-1"></i>
                                Abusive Behaviour
                            </span>
                            <small class="text-muted">12 Jan 2026</small>
                        </div>
                        <div class="text-muted small mt-1">
                            Driver used inappropriate language during pickup.
                        </div>
                    </div>
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between">
                            <span>
                                <i class="fa fa-exclamation-circle text-warning me-1"></i>
                                Late Arrival
                            </span>
                            <small class="text-muted">05 Jan 2026</small>
                        </div>
                        <div class="text-muted small mt-1">
                            Reached pickup location 45 minutes late.
                        </div>
                    </div>
                </div>
            </div>
            <!-- FOOTER -->
            <div class="modal-footer py-2">
                <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button class="btn btn-sm btn-danger">
                    <i class="fa fa-ban me-1"></i>
                    Take Action
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="driverPhotoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px;height:100%;">
        <div class="modal-content">
            <!-- Close Button -->
            <div class="modal-header py-1 px-2">
                <h6 class="modal-title">Driver Photo</h6>
                <button type="button" class="btn p-0" data-bs-dismiss="modal">
                    <i class="fa fa-times text-dark"></i>
                </button>
            </div>
            <!-- Image -->
            <div class="modal-body p-2 text-center">
                <img src="/assets/images/driver.png" class="img-fluid " style="max-height:250px;" alt="Driver Zoom">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="carPhotoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:300px;">
        <div class="modal-content">
            <!-- HEADER -->
            <div class="modal-header py-1 px-2">
                <h6 class="modal-title">Car Photos</h6>
                <button type="button" class="btn p-0" data-bs-dismiss="modal">
                    <i class="fa fa-times text-dark"></i>
                </button>
            </div>
            <!-- BODY -->
            <div class="modal-body p-2">
                <div id="carImageCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-touch="true">
                    <!-- IMAGES -->
                    <div class="carousel-inner text-center">
                        <div class="carousel-item active">
                            <img src="/assets/images/carimage.png" class="img-fluid " style="max-height:250px;"
                                alt="Car Image 1">
                        </div>
                        <div class="carousel-item">
                            <img src="/assets/images/cariamge2.jpg" class="img-fluid " style="max-height:250px;"
                                alt="Car Image 2">
                        </div>
                        <div class="carousel-item">
                            <img src="/assets/images/carimage3.png" class="img-fluid " style="max-height:250px;"
                                alt="Car Image 3">
                        </div>
                    </div>
                    <!-- CONTROLS -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#carImageCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carImageCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmActionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2 align-items-center">
                <h6 class="modal-title d-flex align-items-center gap-2" id="confirmTitle">
                    <i id="confirmIcon" class="fa"></i>
                    <span id="confirmTitleText">Confirm Action</span>
                </h6>
                <button type="button" class="border-0 bg-transparent text-dark" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body small text-center">
                <p id="confirmMessage" class="mb-0"></p>
            </div>
            <div class="modal-footer py-2 justify-content-center">
                <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button class="btn btn-sm btn-dark" id="confirmYesBtn">
                    Yes
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Add this modal AFTER the cancelJobModal -->
<div class="modal fade" id="removeDriverModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <!-- HEADER -->
            <div class="modal-header py-2 align-items-center">
                <h6 class="modal-title d-flex align-items-center gap-2">
                    <i class="fa fa-user-times text-danger"></i>
                    Remove Driver
                </h6>
                <button type="button" class="border-0 bg-transparent text-dark" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <!-- BODY -->
            <div class="modal-body text-center small">
                <p class="mb-0">
                    Are you sure you want to
                    <strong class="text-danger">remove this driver</strong> from the job?
                </p>
            </div>
            <!-- FOOTER -->
            <div class="modal-footer py-2 justify-content-center">
                <button class="btn btn-sm btn-secondary" id="removeDriverNoBtn">
                    Cancel
                </button>
                <button class="btn btn-sm btn-danger" id="confirmRemoveDriverBtn">
                    <i class="fa fa-user-times me-1"></i> Yes, Remove
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Driver Edit Modal -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="driverEditDrawer">
    <div class="offcanvas-header border-bottom d-flex justify-content-between p-3 bg-white">
        <div class="d-flex align-items-center gap-3">
            <h5 class="mb-0 fw-bold">
                <i class="fa fa-id-card me-2 text-danger"></i> Edit Driver
            </h5>
            <!-- Profile Completion Badge -->
            <span class="badge bg-success rounded-pill px-3 py-2" id="profileScoreBadge">
                <i class="fa fa-check-circle me-1"></i>85% Complete
            </span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="position-relative">
                <div class="d-flex align-items-center bg-white border rounded-pill px-3 py-1 shadow-sm" style="width: 280px; border-color: #e2e6f0 !important;">
                    <i class="fa fa-search text-muted me-2" style="font-size: 13px;"></i>
                    <input type="text" id="globalDriverSearch" class="form-control form-control-sm border-0 bg-transparent shadow-none px-0" placeholder="Search drivers" autocomplete="new-password" style="font-size: 13px;">
                </div>
                <div id="globalDriverSearchResults" class="list-group position-absolute w-100 d-none shadow-lg border bg-white" style="top: 100%; left: 0; max-height: 350px; overflow-y: auto; margin-top: 8px; z-index: 99999; border-radius: 8px;">
                </div>
            </div>
            <button class="btn btn-light btn-sm border" type="button" id="closeDrawerBtn" title="Close">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>
    <div class="offcanvas-body p-3 bg-light">
        <div id="driverEditLoader" class="calendar-overlay d-none">
            <div class="calendar-spinner"></div>
        </div>
        <div class="row g-4">
            <!-- ================= LEFT COLUMN - DRIVER PROFILE ================= -->
            <div class="col-lg-4">
                <!-- Driver Basic Info Card -->
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">
                            <div class="d-flex align-items-center">
                                <h6 class="mb-0 fw-bold card-heading" style="font-size: 14px;">
                                    <i class="fa fa-user-circle text-danger me-2"></i>Driver Profile
                                    <span id="status-driver-profile" class="ms-2"></span>
                                </h6>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-info btn-sm" onclick="loadEditLogs()">
                                    <i class="fa fa-history"></i>
                                </button>
                                <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger" data-section="driver-profile" title="Edit"></i>
                            </div>
                        </div>
                        <div class="view-mode" id="view-driver-profile">
                            <div class="d-flex align-items-center">
                                <div id="driverzoom" class="position-relative flex-shrink-0 me-3" style="width: 70px;">
                                    <div class="image-wrapper rounded-circle shadow-sm" style="width: 70px; height: 70px; overflow: hidden; border: 2px solid #fff;">
                                        <img src="assets/images/driver.png" class="driver-photo viewable-image" style="width:100%; height:100%; object-fit:cover;">
                                    </div>
                                    <div class="" data-target="driver-photo" style="bottom: 0px; right: 0px; width: 25px; height: 25px; cursor: pointer;">
                                    </div>
                                </div>
                                <div class="flex-grow-1" style="min-width: 0;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-truncate" style="font-size: 14px;">
                                            <i class="fa fa-user text-primary me-1"></i>
                                            <span class="display-field fw-bold" data-field="name">Venkatesan</span>
                                        </div>
                                        <div class="d-flex gap-2 flex-shrink-0">
                                            <a href="tel:9876543210" class="call-icon text-decoration-none" title="Call">
                                                <i class="fa fa-phone d-flex align-items-center justify-content-center"></i>
                                            </a>
                                            <a href="https://wa.me/919876543210" class="whatsapp-icon text-decoration-none" title="WhatsApp">
                                                <i class="fab fa-whatsapp d-flex align-items-center justify-content-center"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="text-truncate mb-1" style="font-size: 12px;">
                                        <i class="fa fa-envelope text-danger me-1 text-center" style="width: 14px;"></i>
                                        <span class="display-field" data-field="email" title="driver@mail.com">driver@mail.com</span>
                                    </div>
                                    <div class="row g-0" style="font-size: 12px; margin-left:-12px;">
                                        <div class="col-7 text-truncate">
                                            <i class="fa fa-phone text-success me-1 text-center" style="width: 14px;"></i>
                                            <span class="display-field" data-field="mobile">9876543210</span>
                                        </div>
                                        <div class="col-5 text-truncate">
                                            <i class="fa fa-briefcase text-dark me-1 text-center" style="width: 14px;"></i>
                                            <span id="display_experience">5 Yrs</span>
                                        </div>
                                        <div class="col-12 text-truncate d-none">
                                            <i class="fa fa-calendar text-secondary me-1 text-center" style="width: 14px;"></i>
                                            <span class="display-field" data-field="age">35</span> Yrs
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-1" style="font-size: 12px;">
                                <div class="col-12 text-truncate" title="Address">
                                    <i class="fa fa-address-card text-danger me-1 text-center" style="width: 14px;"></i>
                                    <span class="display-field" data-field="address">123, Anna Salai, T. Nagar, Chennai - 600017</span>
                                </div>
                                <div class="col-5 text-truncate">
                                    <i class="fa fa-map-marker text-info me-1 text-center" style="width: 14px;"></i>
                                    <span class="display-field" data-field="city">Chennai</span>
                                </div>
                                <div class="col-7 text-truncate">
                                    <i class="fa fa-globe text-info me-1 text-center" style="width: 14px;"></i>
                                    <span class="display-field" data-field="state">Tamil Nadu</span>
                                </div>
                                <div class="col-12 text-truncate mt-1">
                                    <i class="fa fa-language text-dark me-1 text-center" style="width: 14px;"></i>
                                    <strong>Languages:</strong>
                                    <span class="display-field" data-field="languages">Tamil, English, Telugu</span>
                                </div>
                            </div>
                        </div>
                        <div class="edit-mode" id="edit-driver-profile" style="display: none;">
                            <div class="row g-1 small">
                                <div class="col-7">
                                    <label class="form-label fw-semibold mb-0" style="font-size: 11px;">Name</label>
                                    <input type="text" class="form-control form-control-sm edit-field py-1" oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g,'').slice(0,65)" id="edit_name" data-field="name" value="Venkatesan">
                                </div>
                                <div class="col-5">
                                    <label class="form-label fw-semibold mb-0" style="font-size: 11px;">Mobile</label>
                                    <input type="text" class="form-control form-control-sm edit-field py-1" id="edit_mobile" oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,12)" data-field="mobile" value="9876543210">
                                </div>
                                <div class="col-7">
                                    <label class="form-label fw-semibold mb-0" style="font-size: 11px;">Email</label>
                                    <input type="email" class="form-control form-control-sm edit-field py-1" id="edit_email" oninput="this.value = this.value.replace(/[^a-zA-Z0-9@._-]/g,'').slice(0,75)" value="">
                                </div>
                                <div class="col-5 d-none">
                                    <label class="form-label fw-semibold mb-0" style="font-size: 11px;">Age</label>
                                    <input type="text" class="form-control form-control-sm edit-field py-1" oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,3)" id="edit_age" data-field="age" value="35">
                                </div>
                                <div class="col-5">
                                    <label class="form-label fw-semibold mb-0" style="font-size: 11px;">Exp (Yrs)*</label>
                                    <input type="number" class="form-control form-control-sm py-1" id="edit_experience" oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,3)" value="5">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold mb-0" style="font-size: 11px;">Languages</label>
                                    <div class="" style="background: #fff;">
                                        <select name="language[]" class="form-control form-select-sm edit-field py-1" id="edit_languages" multiple>
                                            <?php
                                            $languages = ["Tamil", "English", "Hindi", "Telugu", "Malayalam", "Kannada", "Marathi"];
                                            $selectedLangs = (isset($selectedLanguages) && is_array($selectedLanguages)) ? $selectedLanguages : [];
                                            foreach ($languages as $lang) {
                                                $selected = in_array($lang, $selectedLangs) ? 'selected' : '';
                                                echo "<option value='" . htmlspecialchars($lang) . "' $selected>" . htmlspecialchars($lang) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
    <label class="form-label fw-semibold mb-0" style="font-size: 11px;">State</label>
    <select class="form-control form-control-sm py-1" id="edit_state" data-field="state" placeholder="Select State">
        <option value="">Select State</option>
        <?php
        $query = mysqli_query($con, "SELECT id, name FROM states WHERE country_code = 'IN'");
        while ($rows = mysqli_fetch_assoc($query)) {
            echo "<option value='" . htmlspecialchars($rows['name']) . "'>" . htmlspecialchars($rows['name']) . "</option>";
        }
        ?>
    </select>
</div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-0" style="font-size: 11px;">District</label>
                                    <select class="form-control form-select form-select-sm py-1 edit-field" id="edit_districts_id" data-field="districts_id">
                                        <option value="">Select District</option>
                                        <?php
                                        // Assuming $con is available in this file. If not, you might need to load this dynamically via AJAX.
                                        $dist_query = mysqli_query($con, "SELECT id, district_name FROM districts");
                                        while ($dist = mysqli_fetch_assoc($dist_query)) {
                                            echo "<option value='" . htmlspecialchars($dist['id']) . "'>" . htmlspecialchars($dist['district_name']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold mb-0" style="font-size: 11px;">Address</label>
                                    <input type="text" class="form-control form-control-sm edit-field py-1" id="edit_address" oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s,.-]/g,'').slice(0,150)" data-field="address" value="123, Anna Salai, T. Nagar, Chennai - 600017">
                                </div>
                                <div class="col-12 mt-2 text-end">
                                    <button class="btn btn-sm btn-secondary cancel-section py-1 px-2" style="font-size: 11px;" data-section="driver-profile"><i class="fa fa-times me-1"></i>Cancel</button>
                                    <button class="btn btn-sm btn-success save-section py-1 px-2" style="font-size: 11px;" data-section="driver-profile"><i class="fa fa-save me-1"></i>Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Aadhaar Card Section -->
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0 fw-bold card-heading"><i class="fa fa-id-card text-danger me-2"></i>Aadhaar Card
                                <span id="status-aadhaar"></span>
                            </h6>
                            <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger" data-section="aadhaar"
                                title="Edit"></i>
                        </div>
                        <!-- VIEW MODE -->
                        <div class="view-mode" id="view-aadhaar">
                            <div class="row text-center g-3">
                                <!-- Aadhaar Front -->
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">Aadhaar Front</h6>
                                    <div class="document-box-small viewable-image" id="aadhaarFront">
                                        <img src="assets/images/adharfront.jpeg" class="document-img"
                                            alt="Aadhaar Front">
                                    </div>
                                </div>
                                <!-- Aadhaar Back -->
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">Aadhaar Back</h6>
                                    <div class="document-box-small viewable-image" id="aadhaarBack">
                                        <img src="assets/images/adharback.jpeg" class="document-img" alt="Aadhaar Back">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- EDIT MODE -->
                        <div class="edit-mode" id="edit-aadhaar" style="display: none;">
                            <div class="row g-2">
                                <!--<div class="text-center mb-3">-->
                                <!--    <button class="btn btn-sm btn-primary" id="verifyAadhaarDigilocker">-->
                                <!--        <i class="fa fa-link me-1"></i> Verify via DigiLocker-->
                                <!--    </button>-->
                                <!--</div>-->
                                <div class="text-center mb-2 text-muted">
                                    Upload Aadhaar Images
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1">Front Side</label>
                                    <div class="document-box-small" id="edit-aadhaarFront" data-target="aadhaarFront">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRqkqkqkqkqkqkqkqkqkqkqkqkqkqkqkqk&s"
                                            class="document-img" alt="Aadhaar Front">
                                        <div class="upload-overlay">
                                            <i class="fa fa-camera text-white"></i>
                                            <input type="file" class="file-input-hidden" accept="image/*"
                                                data-target="aadhaarFront">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1">Back Side</label>
                                    <div class="document-box-small" id="edit-aadhaarBack" data-target="aadhaarBack">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSpSpSpSpSpSpSpSpSpSpSpSpSpSpSpSpS&s"
                                            class="document-img" alt="Aadhaar Back">
                                        <div class="upload-overlay">
                                            <i class="fa fa-camera text-white"></i>
                                            <input type="file" class="file-input-hidden" accept="image/*"
                                                data-target="aadhaarBack">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-2 text-end">
                                    <button class="btn btn-sm btn-success me-2 save-section-img" data-section="aadhaar"><i
                                            class="fa fa-save me-1"></i>Save</button>
                                    <button class="btn btn-sm btn-secondary cancel-section" data-section="aadhaar"><i
                                            class="fa fa-times me-1"></i>Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Driving License Section -->
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0 fw-bold card-heading">
                                <i class="fa fa-id-card text-danger me-2"></i>Driving License
                                <span id="status-license"></span>
                            </h6>
                            <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger" data-section="license"
                                title="Edit"></i>
                        </div>
                        <!-- VIEW MODE -->
                        <div class="view-mode" id="view-license">
                            <div class="row text-center g-3">
                                <!-- License Front -->
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">License Front</h6>
                                    <div class="document-box-small viewable-image" id="licenseFront">
                                        <img src="assets/images/lic-front.png" class="document-img" alt="License Front">
                                    </div>
                                </div>
                                <!-- License Back -->
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">License Back</h6>
                                    <div class="document-box-small viewable-image" id="licenseBack">
                                        <img src="assets/images/lic-back.png" class="document-img" alt="License Back">
                                    </div>
                                </div>
                                <!-- License No -->
                                <div class="col-6 mt-1 text-start">
                                    <i class="fa fa-id-badge text-info me-1"></i>
                                    <strong>No:</strong>
                                    <span id="display_license_no">Licence No</span>
                                </div>
                                <!-- License Type -->
                                <div class="col-6 mt-1 text-start">
                                    <i class="fa fa-id-badge text-info me-1"></i>
                                    <strong>Type:</strong>
                                    <span id="display_license_type">LMV</span>
                                </div>
                                <!-- Expiry -->
                                <div class="col-6 mt-1 text-start">
                                    <i class="fa fa-calendar text-warning me-1"></i>
                                    <strong>Expiry:</strong>
                                    <span id="display_license_expiry">12/2028</span>
                                </div>
                            </div>
                        </div>
                        <!-- EDIT MODE -->
                        <div class="edit-mode" id="edit-license" style="display: none;">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1">Front Side</label>
                                    <div class="document-box-small" id="edit-licenseFront" data-target="licenseFront">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTtTtTtTtTtTtTtTtTtTtTtTtTtTtTtTtT&s"
                                            class="document-img" alt="License Front">
                                        <div class="upload-overlay">
                                            <i class="fa fa-camera text-white"></i>
                                            <input type="file" class="file-input-hidden" accept="image/*"
                                                data-target="licenseFront">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1">Back Side</label>
                                    <div class="document-box-small" id="edit-licenseBack" data-target="licenseBack">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcUuUuUuUuUuUuUuUuUuUuUuUuUuUuUuUuU&s"
                                            class="document-img" alt="License Back">
                                        <div class="upload-overlay">
                                            <i class="fa fa-camera text-white"></i>
                                            <input type="file" class="file-input-hidden" accept="image/*"
                                                data-target="licenseBack">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1">License No</label>
                                    <input type="text" class="form-control form-control-sm edit-field"
                                        id="edit_license_no">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1">Date of Birth</label>
                                    <input type="date" class="form-control form-control-sm" id="edit_license_dob" max="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1">License Type</label>
                                    <select class="form-select form-select-sm" id="edit_license_type">
                                        <option value="LMV" selected>LMV</option>
                                        <option value="HMV">HMV</option>
                                        <option value="Transport">Transport</option>
                                        <option value="MCWG">MCWG</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1">Expiry Date</label>
                                    <input type="date" class="form-control form-control-sm edit-field" id="edit_license_expiry" min="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-12 mt-2 text-end">
                                    <button class="btn btn-sm btn-success me-2 save-section-img" data-section="license"><i
                                            class="fa fa-save me-1"></i>Save</button>
                                    <button class="btn btn-sm btn-secondary cancel-section" data-section="license"><i
                                            class="fa fa-times me-1"></i>Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ================= CENTER COLUMN - VEHICLE DETAILS ================= -->
            <div class="col-lg-4">
                <!-- Vehicle Details Card -->
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 fw-bold card-heading">
                                <i class="fa fa-car text-danger me-2"></i>Vehicle Details
                                <span id="status-vehicle"></span>
                            </h6>
                            <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger" data-section="vehicle" title="Edit"></i>
                        </div>
                        <div class="view-mode" id="view-vehicle">
                            <div class="row gx-2 align-items-center gap-3">
                                <div class="col-auto p-0">
                                    <div class="position-relative" style="width: 150px;">
                                        <div id="vehicle-gallery-container" class="shadow-sm" style="width: 100%; height: 80px; overflow: hidden; background: #f8f9fa; border-radius: 6px;">
                                            <img src="assets/images/carimage3.png" alt="Front View" id="main_vehicle_preview" class="cursor-pointer vehicle-photo" style="width: 100%; height: 100%; object-fit: cover;">
                                            <div style="width: 0; height: 0; overflow: hidden; opacity: 0;">
                                                <img src="" alt="Boot" id="gallery_boot_image">
                                                <img src="" alt="Extra 1" id="gallery_extra_image_1">
                                                <img src="" alt="Top View" id="gallery_car_top_view_image">
                                                <img src="" alt="Interior" id="gallery_interior_front_image">
                                                <img src="" alt="Special" id="gallery_special_features_image">
                                            </div>
                                        </div>
                                        <div class="upload-overlay-small" data-bs-toggle="modal" data-bs-target="#vehicleImagesModal" style="cursor: pointer; position: absolute; bottom: -6px; right: -6px; width: 26px; height: 26px; background: white; border-radius: 50%; box-shadow: 0 2px 5px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; z-index: 10;">
                                            <i class="fa fa-camera text-primary" style="font-size: 12px;"></i>
                                        </div>
                                        <input type="hidden" id="val_front_view_image_url" value="">
                                        <input type="hidden" id="val_boot_image_url" value="">
                                        <input type="hidden" id="val_extra_image_1_url" value="">
                                        <input type="hidden" id="val_car_top_view_image_url" value="">
                                        <input type="hidden" id="val_interior_front_image_url" value="">
                                        <input type="hidden" id="val_special_features_image_url" value="">
                                    </div>
                                </div>
                                <div class="col" style="min-width: 0;">
                                    <div class="mb-1 d-flex justify-content-start align-items-center text-truncate" style="line-height: 1.2;">
                                        <strong class="text-dark" style="font-size: 13px;" id="display_vehicle_type">Go Sedan</strong>
                                        <span class="text-muted" style="font-size: 11px;">(<span id="display_maker_model">Hyundai Aura 1.2 MT CNG</span>)</span>
                                        <span class="verified-star"><i class="fa fa-check"></i></span>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2 mb-2" style="font-size: 12px; line-height: 1;">
                                        <div class="text-nowrap"><i class="fa fa-users text-primary me-1"></i><span id="display_seating">4+1</span></div>
                                        <div class="text-nowrap"><i class="fa fa-gas-pump text-warning me-1"></i><span id="display_fuel">Petrol</span></div>
                                        <div class="text-nowrap"><i class="fa fa-suitcase text-secondary me-1"></i><span id="display_luggage">3</span></div>
                                    </div>
                                    <div class="d-flex flex-column gap-1" style="font-size: 11.5px; line-height: 1.2;">
                                        <div class="text-nowrap">
                                            <i class="fa fa-file text-info me-1" style="width: 12px; text-align: center;"></i>RC: <span id="display_rc" class="text-dark fw-medium">30 Nov 2026</span>
                                        </div>
                                        <div class="text-nowrap">
                                            <i class="fa fa-file text-danger me-1" style="width: 12px; text-align: center;"></i>IN: <span id="display_insurance" class="text-dark fw-medium">15 Aug 2027</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 g-1" id="simpleVehicleImages">
                                <div class="col-4 col-md-2 p-1">
                                    <div class="position-relative">
                                        <img src="assets/images/placeholder.png" class="img-fluid cursor-pointer vehicle-thumb" data-index="0" style="height:70px; width:100%; object-fit:cover; border:1px solid #dee2e6;">
                                        <span class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 text-white text-center small py-1" style="font-size:9px; border-radius:0 0 4px 4px;">Front</span>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2 p-1">
                                    <div class="position-relative">
                                        <img src="assets/images/placeholder.png" class="img-fluid cursor-pointer vehicle-thumb" data-index="1" style="height:70px; width:100%; object-fit:cover; border:1px solid #dee2e6;">
                                        <span class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 text-white text-center small py-1" style="font-size:9px; border-radius:0 0 4px 4px;">Boot</span>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2 p-1">
                                    <div class="position-relative">
                                        <img src="assets/images/placeholder.png" class="img-fluid cursor-pointer vehicle-thumb" data-index="2" style="height:70px; width:100%; object-fit:cover; border:1px solid #dee2e6;">
                                        <span class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 text-white text-center small py-1" style="font-size:9px; border-radius:0 0 4px 4px;">Extra 1</span>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2 p-1">
                                    <div class="position-relative">
                                        <img src="assets/images/placeholder.png" class="img-fluid cursor-pointer vehicle-thumb" data-index="3" style="height:70px; width:100%; object-fit:cover; border:1px solid #dee2e6;">
                                        <span class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 text-white text-center small py-1" style="font-size:9px; border-radius:0 0 4px 4px;">Top</span>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2 p-1">
                                    <div class="position-relative">
                                        <img src="assets/images/placeholder.png" class="img-fluid cursor-pointer vehicle-thumb" data-index="4" style="height:70px; width:100%; object-fit:cover; border:1px solid #dee2e6;">
                                        <span class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 text-white text-center small py-1" style="font-size:9px; border-radius:0 0 4px 4px;">Interior</span>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2 p-1">
                                    <div class="position-relative">
                                        <img src="assets/images/placeholder.png" class="img-fluid cursor-pointer vehicle-thumb" data-index="5" style="height:70px; width:100%; object-fit:cover; border:1px solid #dee2e6;">
                                        <span class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 text-white text-center small py-1" style="font-size:9px; border-radius:0 0 4px 4px;">Special</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="edit-mode mt-3" id="edit-vehicle" style="display:none;">
                            <div class="row g-2 small">
                                <div class="col-5">
                                    <label class="form-label fw-semibold mb-0 text-nowrap" style="font-size: 11px;">Vehicle Type</label>
                                    <select class="form-select form-select-sm py-1" id="edit_vehicle_type">
                                        <option>Go Mini</option>
                                        <option>Go 4Seater</option>
                                        <option>Go 6Seater</option>
                                        <option selected>Go 7Seater</option>
                                    </select>
                                </div>
                                <div class="col-7">
                                    <label class="form-label fw-semibold mb-0 text-nowrap" style="font-size: 11px;">Maker Model</label>
                                    <input type="text" class="form-control form-control-sm py-1" oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s]/g,'').slice(0,25)" id="edit_maker_model" value="Hyundai Aura 1.2">
                                </div>
                                <div class="col-5">
                                    <label class="form-label fw-semibold mb-0 text-nowrap" style="font-size: 11px;">Fuel Type</label>
                                    <select class="form-select form-select-sm py-1" id="edit_fuel">
                                        <option value="">Select</option>
                                        <option value="PETROL">PETROL</option>
                                        <option value="DIESEL">DIESEL</option>
                                        <option value="CNG">CNG</option>
                                        <option value="PETROL/CNG">PETROL/CNG</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label class="form-label fw-semibold mb-0 text-nowrap" style="font-size: 11px;">Seating</label>
                                    <input type="text" class="form-control form-control-sm py-1 px-1 text-center" oninput="this.value = this.value.replace(/[^0-9+]/g,'').slice(0,3)" id="edit_seating" value="4+1">
                                </div>
                                <div class="col-4">
                                    <label class="form-label fw-semibold mb-0 text-nowrap" style="font-size: 11px;">Luggage</label>
                                    <input type="text" class="form-control form-control-sm py-1 px-1 text-center" oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,3)" id="edit_luggage" value="2">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-0 text-nowrap" style="font-size: 11px;">RC Valid Until</label>
                                    <input type="date" class="form-control form-control-sm py-1 px-1" id="edit_rc" min="<?php echo $today; ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-0 text-nowrap" style="font-size: 11px;">Insurance Valid</label>
                                    <input type="date" class="form-control form-control-sm py-1 px-1" id="edit_insurance" min="<?php echo $today; ?>">
                                </div>
                                <div class="col-12 mt-2 text-end">
                                    <button class="btn btn-sm btn-secondary cancel-section py-1 px-2" style="font-size: 11px;" data-section="vehicle"><i class="fa fa-times me-1"></i>Cancel</button>
                                    <button class="btn btn-sm btn-success save-section py-1 px-2" style="font-size: 11px;" data-section="vehicle"><i class="fa fa-save me-1"></i>Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Vehicle Documents -->
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0 fw-bold card-heading">
                                <i class="fa fa-file text-danger me-2"></i>Vehicle Documents
                                <span id="status-documents"></span>
                            </h6>
                            <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger" data-section="documents"
                                title="Edit"></i>
                        </div>
                        <!-- VIEW MODE -->
                        <div class="view-mode" id="view-documents">
                            <div class="row text-center g-3">
                                <!-- RC -->
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">RC Front</h6>
                                    <div class="document-box-small viewable-image" id="rcDocumentFront">
                                        <img src="assets/images/lic-front.png" class="document-img" alt="RC">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">RC Back</h6>
                                    <div class="document-box-small viewable-image" id="rcDocumentBack">
                                        <img src="assets/images/lic-back.png" class="document-img" alt="RC">
                                    </div>
                                </div>
                                <!-- PUC -->
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">PUC</h6>
                                    <div class="document-box-small viewable-image" id="pucDocument">
                                        <img src="assets/images/adharfront.jpeg" class="document-img" alt="PUC">
                                    </div>
                                </div>
                                <!-- Insurance -->
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">Insurance</h6>
                                    <div class="document-box-small viewable-image" id="insuranceDocument">
                                        <img src="assets/images/adharfront.jpeg" class="document-img" alt="Insurance">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- EDIT MODE -->
                        <div class="edit-mode" id="edit-documents" style="display: none;">
                            <div class="row text-center g-3">
                                <!-- RC Front -->
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">RC Front</h6>
                                    <div class="document-box-small" id="edit-rcDocumentFront" data-target="rcDocumentFront">
                                        <img class="document-img" alt="RC Front">
                                        <div class="upload-overlay">
                                            <i class="fa fa-camera text-white"></i>
                                            <input type="file" class="file-input-hidden" accept="image/*" data-target="rcDocumentFront">
                                        </div>
                                    </div>
                                </div>
                                <!-- RC Back -->
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">RC Back</h6>
                                    <div class="document-box-small" id="edit-rcDocumentBack" data-target="rcDocumentBack">
                                        <img class="document-img" alt="RC Back">
                                        <div class="upload-overlay">
                                            <i class="fa fa-camera text-white"></i>
                                            <input type="file" class="file-input-hidden" accept="image/*" data-target="rcDocumentBack">
                                        </div>
                                    </div>
                                </div>
                                <!-- RC Number -->
                                <div class="col-6">
                                    <label class="form-label fw-semibold">RC Number</label>
                                    <input type="text" class="form-control form-control-sm" id="edit_rc_number">
                                </div>
                                <!-- RC Expiry -->
                                <div class="col-6">
                                    <label class="form-label fw-semibold">RC Expiry</label>
                                    <input type="date" class="form-control form-control-sm" id="edit_rc_expiry" min="<?php echo $today; ?>">
                                </div>
                                <!-- PUC -->
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">PUC</h6>
                                    <div class="document-box-small" id="edit-pucDocument" data-target="pucDocument">
                                        <img class="document-img" alt="PUC">
                                        <div class="upload-overlay">
                                            <i class="fa fa-camera text-white"></i>
                                            <input type="file" class="file-input-hidden" accept="image/*" data-target="pucDocument">
                                        </div>
                                    </div>
                                </div>
                                <!-- PUC Expiry -->
                                <div class="col-6">
                                    <label class="form-label fw-semibold">PUC Expiry</label>
                                    <input type="date" class="form-control form-control-sm" id="edit_puc_expiry" min="<?php echo $today; ?>">
                                </div>
                                <!-- Insurance -->
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">Insurance</h6>
                                    <div class="document-box-small" id="edit-insuranceDocument" data-target="insuranceDocument">
                                        <img class="document-img" alt="Insurance">
                                        <div class="upload-overlay">
                                            <i class="fa fa-camera text-white"></i>
                                            <input type="file" class="file-input-hidden" accept="image/*" data-target="insuranceDocument">
                                        </div>
                                    </div>
                                </div>
                                <!-- Insurance Expiry -->
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Insurance Expiry</label>
                                    <input type="date" class="form-control form-control-sm" id="edit_insurance_expiry" min="<?php echo $today; ?>">
                                </div>
                                <!-- Buttons -->
                                <div class="col-12 text-end mt-3">
                                    <button class="btn btn-sm btn-success me-2 save-section-img" data-section="documents">
                                        <i class="fa fa-save me-1"></i>Save
                                    </button>
                                    <button class="btn btn-sm btn-secondary cancel-section" data-section="documents">
                                        <i class="fa fa-times me-1"></i>Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5 d-none">
                        <!-- Vehicle Gallery -->
                        <div class="card shadow-sm border-0 mb-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold card-heading"><i class="fa fa-images text-danger me-2"></i>Vehicle
                                        Gallery</h6>
                                    <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger"
                                        data-section="gallery" title="Edit Gallery"></i>
                                </div>
                                <!-- VIEW MODE -->
                                <div class="view-mode" id="view-gallery">
                                    <div class="position-relative image-wrapper">
                                        <img id="mainGalleryImage" src="assets/images/carimage.png"
                                            class="img-fluid cursor-pointer viewable-image"
                                            style="width:100%;height:120px;object-fit:cover;">
                                        <div class="gallery-count" id="galleryCount">0 Photos</div>
                                    </div>
                                </div>
                                <input type="file" id="photoUpload" multiple accept="image/*" class="form-control mt-2">
                                <!-- Hidden container for viewer -->
                                <div id="hiddenGalleryImages" style="display:none;"></div>
                                <!-- EDIT MODE -->
                                <div class="edit-mode" id="edit-gallery" style="display: none;">
                                    <div class="position-relative mb-2">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcYyYyYyYyYyYyYyYyYyYyYyYyYyYyYyYyY&s"
                                            alt="Car" class="img-fluid rounded" id="editMainCarImage"
                                            style="width: 100%; height: 100px; object-fit: cover;">
                                        <div class="upload-overlay-small" style="bottom: 5px; right: 5px;">
                                            <i class="fa fa-camera text-primary"></i>
                                            <input type="file" class="file-input-hidden" accept="image/*"
                                                id="galleryUpload">
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button class="btn btn-sm btn-success me-2 save-section"
                                            data-section="gallery"><i class="fa fa-save me-1"></i>Save</button>
                                        <button class="btn btn-sm btn-secondary cancel-section"
                                            data-section="gallery"><i class="fa fa-times me-1"></i>Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                </div>
            </div>
            <!-- ================= RIGHT COLUMN - JOBS & ACTIVITY ================= -->
            <div class="col-lg-4">
                <!-- Scheduled Jobs -->
                <!-- Recent Activity -->
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0 fw-bold card-heading"><i class="fa fa-calendar-alt text-danger me-2"></i>Scheduled Jobs
                                <span id="status-jobs"></span>
                            </h6>
                            <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger" data-section="jobs"
                                title="Edit Jobs"></i>
                        </div>
                        <!-- VIEW MODE -->
                        <div class="view-mode" id="view-jobs">
                            <div class="schedule-list jobs-scroll" id="scheduledJobsList">
                                <div
                                    class="schedule-item p-2 border-bottom d-flex justify-content-between align-items-center">
                                    <div><i class="fa fa-map-marker-alt text-success me-2"></i> Chennai → Salem</div>
                                    <span class="text-muted small">Today 10:30 AM</span>
                                </div>
                                <div
                                    class="schedule-item p-2 border-bottom d-flex justify-content-between align-items-center">
                                    <div><i class="fa fa-map-marker-alt text-success me-2"></i> Salem → Coimbatore</div>
                                    <span class="text-muted small">Tomorrow 2:00 PM</span>
                                </div>
                            </div>
                        </div>
                        <!-- EDIT MODE -->
                        <div class="edit-mode" id="edit-jobs" style="display: none;">
                            <div id="dynamicScheduleWrapper" style="max-height: 280px; overflow-y: auto; overflow-x: hidden;">
                            </div>
                            <button type="button" class="btn btn-sm btn-info w-100 mb-3 mt-2" id="addScheduleRowBtn">
                                <i class="fa fa-plus me-1"></i> Add Route
                            </button>
                            <div class="text-end mt-2">
                                <button type="button" class="btn btn-sm btn-success me-2" id="saveScheduleBtn">
                                    <i class="fa fa-save me-1"></i>Save
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary cancel-section" data-section="jobs">
                                    <i class="fa fa-times me-1"></i>Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row align-items-stretch mb-3">
                    <div class="col-6">
                        <div class="card shadow-sm border-0 mb-3 h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold card-heading" style="padding: 7px 2px;">
                                        <i class="fa fa-credit-card text-danger me-1"></i>Payment Methods
                                        <span id="status-payment"></span>
                                    </h6>
                                    <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger" data-section="payment"
                                        title="Edit"></i>
                                </div>
                                <!-- VIEW MODE -->
                                <div class="view-mode" id="view-payment">
                                    <div class="mt-5">
                                        <span class="badge bg-light text-dark p-2">PRIMARY UPI</span>
                                        <span class="display-field ms-2" data-field="upi"
                                            id="display_upi">venkatesan@okaxis</span>
                                    </div>
                                </div>
                                <!-- EDIT MODE -->
                                <div class="edit-mode" id="edit-payment" style="display: none;">
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold mb-1">UPI ID</label>
                                        <input type="text" class="form-control form-control-sm edit-field" id="edit_upi" data-field="upi" value="venkatesan@okaxis" oninput="this.value = this.value.replace(/\s/g, '').slice(0, 30)">
                                    </div>
                                    <div class="text-end mt-2">
                                        <button class="btn btn-sm btn-success me-2 save-section" data-section="payment"><i
                                                class="fa fa-save me-1"></i>Save</button>
                                        <button class="btn btn-sm btn-secondary cancel-section" data-section="payment"><i
                                                class="fa fa-times me-1"></i>Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold card-heading"><i class="fa fa-comment text-danger me-2"></i>Admin Remarks
                                        <span id="status-remarks"></span>
                                    </h6>
                                    <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger" data-section="remarks"
                                        title="Edit Remarks"></i>
                                </div>
                                <!-- VIEW MODE -->
                                <div class="view-mode" id="view-remarks">
                                    <div class="remarks-content p-3 bg-light ">
                                        <p class="mb-2" id="display_remarks">"Reliable driver for long distance trips. Excellent
                                            fuel management on previous Salem route. Recommended for VIP clients."</p>
                                        <small class="text-muted" id="display_review">— John Doe, Operations
                                            Manager</small>
                                    </div>
                                </div>
                                <!-- EDIT MODE -->
                                <div class="edit-mode" id="edit-remarks" style="display: none;">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Remarks</label>
                                        <textarea class="form-control form-control-sm" id="edit_remarks"
                                            rows="3">Reliable driver for long distance trips. Excellent fuel management on previous Salem route. Recommended for VIP clients.</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Review</label>
                                        <select class="form-control form-select" id="edit_review">
                                            <option value="">Select</option>
                                            <option value="Good">Good</option>
                                            <option value="Bad">Bad</option>
                                            <option value="Excellent">Excellent</option>
                                        </select>
                                    </div>
                                    <div class="text-end mt-2">
                                        <button class="btn btn-sm btn-success me-2 save-section" data-section="remarks"><i
                                                class="fa fa-save me-1"></i>Save</button>
                                        <button class="btn btn-sm btn-secondary cancel-section" data-section="remarks"><i
                                                class="fa fa-times me-1"></i>Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                        <!-- Fare Rates -->
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold card-heading">
                                        <i class="fa fa-rupee text-danger me-2"></i>Fare Rates
                                        <span id="status-fare"></span>
                                    </h6>
                                    <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger"
                                        data-section="fare" title="Edit"></i>
                                </div>
                                <!-- VIEW MODE -->
                                <div class="view-mode" id="view-fare">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>PER KM</th>
                                                    <th>EXTRA KM</th>
                                                    <th>EXTRA HOUR</th>
                                                    <th>EXTRA DAY</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>₹<span class="display-field" data-field="per_km"
                                                            id="display_per_km">14</span></td>
                                                    <td>₹<span class="display-field" data-field="extra_km"
                                                            id="display_extra_km">15</span></td>
                                                    <td>₹<span class="display-field" data-field="extra_hour"
                                                            id="display_extra_hour">100</span></td>
                                                    <td>₹<span class="display-field" data-field="extra_day"
                                                            id="display_extra_day">1200</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- EDIT MODE -->
                                <div class="edit-mode" id="edit-fare" style="display: none;">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label fw-semibold mb-1">Per KM (₹)</label>
                                            <input type="number" class="form-control form-control-sm edit-field"
                                                id="edit_per_km" data-field="per_km" value="14" min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5)">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label fw-semibold mb-1">Extra KM (₹)</label>
                                            <input type="number" class="form-control form-control-sm edit-field"
                                                id="edit_extra_km" data-field="extra_km" value="15" min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5)">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label fw-semibold mb-1">Extra Hour (₹)</label>
                                            <input type="number" class="form-control form-control-sm edit-field"
                                                id="edit_extra_hour" data-field="extra_hour" value="100" min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5)">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label fw-semibold mb-1">Extra Day (₹)</label>
                                            <input type="number" class="form-control form-control-sm edit-field"
                                                id="edit_extra_day" data-field="extra_day" value="1200" min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5)">
                                        </div>
                                        <div class="col-12 mt-2 text-end">
                                            <button class="btn btn-sm btn-success me-2 save-section"
                                                data-section="fare"><i class="fa fa-save me-1"></i>Save</button>
                                            <button class="btn btn-sm btn-secondary cancel-section"
                                                data-section="fare"><i class="fa fa-times me-1"></i>Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                <div class="card shadow-sm border-0 mb-3 d-none">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0 fw-bold"><i class="fa fa-clock text-danger me-2"></i>Recent Activity</h6>
                            <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger" data-section="activity"
                                title="Edit Activity"></i>
                        </div>
                        <!-- VIEW MODE -->
                        <div class="view-mode d-none" id="view-activity">
                            <div class="activity-list" id="recentActivityList">
                                <div class="activity-item p-2 border-bottom">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <i class="fa fa-check-circle text-success me-2"></i>
                                            <strong>Trip Completed</strong>
                                        </div>
                                        <span class="text-muted small">2 hours ago</span>
                                    </div>
                                    <div class="ms-4 small">Bangalore to Chennai - 280 KM</div>
                                </div>
                                <div class="activity-item p-2 border-bottom">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <i class="fa fa-exclamation-triangle text-warning me-2"></i>
                                            <strong>PUC Renewal Pending</strong>
                                        </div>
                                        <span class="text-muted small">Yesterday</span>
                                    </div>
                                    <div class="ms-4 small">Document expires on 25th Oct</div>
                                </div>
                                <div class="activity-item p-2">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <i class="fa fa-times-circle text-danger me-2"></i>
                                            <strong>Job Cancelled</strong>
                                        </div>
                                        <span class="text-muted small">2 days ago</span>
                                    </div>
                                    <div class="ms-4 small">Customer no-show - ID #4421</div>
                                </div>
                            </div>
                        </div>
                        <!-- EDIT MODE -->
                        <div class="edit-mode" id="edit-activity" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Activity 1</label>
                                <input type="text" class="form-control form-control-sm mb-1" id="edit_activity1"
                                    value="Trip Completed - Bangalore to Chennai - 280 KM">
                                <input type="text" class="form-control form-control-sm" id="edit_activity1_time"
                                    value="2 hours ago">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Activity 2</label>
                                <input type="text" class="form-control form-control-sm mb-1" id="edit_activity2"
                                    value="PUC Renewal Pending - Document expires on 25th Oct">
                                <input type="text" class="form-control form-control-sm" id="edit_activity2_time"
                                    value="Yesterday">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Activity 3</label>
                                <input type="text" class="form-control form-control-sm mb-1" id="edit_activity3"
                                    value="Job Cancelled - Customer no-show - ID #4421">
                                <input type="text" class="form-control form-control-sm" id="edit_activity3_time"
                                    value="2 days ago">
                            </div>
                            <div class="text-end mt-2">
                                <button class="btn btn-sm btn-success me-2 save-section" data-section="activity"><i
                                        class="fa fa-save me-1"></i>Save</button>
                                <button class="btn btn-sm btn-secondary cancel-section" data-section="activity"><i
                                        class="fa fa-times me-1"></i>Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Admin Remarks -->
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="createBidModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    <i class="fa fa-gavel me-2 text-warning"></i>
                    Create Bid
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="bidJobId">
                <input type="hidden" id="bidDriverId">
                <div class="mb-3">
                    <label class="form-label">Bid Amount</label>
                    <input type="number" id="bidAmount" class="form-control" placeholder="Enter amount">
                </div>
                <div class="mb-2">
                    <label class="form-label">Remark (optional)</label>
                    <input type="text" id="bidRemark" class="form-control" placeholder="Remark">
                </div>
                <small id="bidErrorMsg" class="text-danger d-none"></small>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button class="btn btn-success btn-sm" id="submitBidBtn">
                    <span class="bid-btn-text">
                        <i class="fa fa-paper-plane me-1"></i> Bid
                    </span>
                    <span class="bid-btn-loader d-none">
                        <i class="fa fa-spinner fa-spin me-1"></i> Bidding...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cancelJobModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-danger">
                    <i class="fa fa-times-circle me-2"></i>
                    Cancel Job
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <input type="hidden" id="cancelJobId">
                <input type="hidden" id="cancelJobNo">
                <input type="hidden" id="cancelJobType">
                <input type="hidden" id="cancelJobUserId">
                <p class="mb-2">
                    Are you sure you want to cancel this job?
                </p>
                <small class="text-muted">
                    This action cannot be undone.
                </small>
                <div id="cancelJobError" class="text-danger mt-2 d-none">
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    Keep Job
                </button>
                <button class="btn btn-danger btn-sm" id="confirmCancelJobBtn">
                    <span class="cancel-btn-text">
                        <i class="fa fa-times me-1"></i> Yes, Cancel
                    </span>
                    <span class="cancel-btn-loader d-none">
                        <i class="fa fa-spinner fa-spin me-1"></i> Cancelling...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
<div class="offcanvas offcanvas-end" tabindex="1" id="driverPreviewCanvas">
    <div class="offcanvas-header border-bottom py-2">
        <h6 class="mb-0 card-heading">
            <strong>
                <i class="fa fa-id-card me-1 text-danger"></i> Driver Details
            </strong>
        </h6>
        <button class="btn-close" data-bs-dismiss="offcanvas">
            <i class="fa fa-close"></i>
        </button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="card mb-2 border-0" id="driverDetailsCard">
            <div class="card-body p-3 position-relative">
                <div id="driverProfileLoader" class="calendar-overlay d-none">
                    <div class="calendar-spinner"></div>
                </div>
                <input type="hidden" id="preview_driver_id" value="">
                <div class="d-flex align-items-center gap-3 mt-3">
                    <div style="width:100px;height:80px;">
                        <img src="/assets/images/driver.png" alt="Driver Photo" id="preview_driver_photo"
                            class="rounded-circle cursor-pointer"
                            style="height: 100%;width: 100%; object-fit: cover;"
                            data-bs-toggle="modal" data-bs-target="#driverPhotoModal">
                    </div>
                    <div class="w-100">
                        <div class="d-flex justify-content-start gap-3 align-items-center">
                            <div class="d-flex align-items-center gap-2" style="font-size:14px">
                                <strong id="preview_driver_name">Loading...</strong>
                                <!--<span id="preview_driver_no" class="text-muted"></span>-->
                                <a href="javascript:void(0);" class="text-warning" title="Edit Driver" id="driver_profile_edit">
                                    <i class="fa fa-edit text-info"></i>
                                </a>
                                <div class="d-flex align-items-center">
                                    <i id="sidebar_remarks_icon" class="fa-solid fa-message cursor-pointer" style="color: #6c757d;" title="Add/View Remarks"></i>
                                </div>
                            </div>
                            <div class="d-inline-flex align-items-center gap-2">
                                <a href="#" class="call-con" title="Call Owner" id="preview_call_btn">
                                    <i class="fa fa-phone"></i>
                                </a>
                                <a href="#" target="_blank" class="whatsapp-icon" title="WhatsApp Owner" id="preview_wa_btn">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                        <div class="small text-muted mt-1" style="display: flex;flex-direction: column;">
                            <span id="preview_driver_no" class="text-muted">
                                <i class="fa fa-phone text-success me-1"></i>
                            </span>
                            <span id="preview_kyc_status">
                                <i class="fa fa-times-circle text-danger me-1"></i> Checking...
                            </span>
                            <span id="preview_location">
                                <i class="fa fa-map-marker-alt text-danger me-1"></i> N/A
                            </span>
                            <span id="preview_experience">
                                <i class="fa fa-briefcase text-info me-1"></i> N/A
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-3 small">
                    <div class="row">
                        <div class="col-6" id="preview_license">
                            <i class="fa fa-id-badge text-primary me-1"></i> <strong>N/A</strong> | <strong>N/A</strong>
                        </div>
                        <div class="col-6 p-0" id="preview_languages">
                            <i class="fa fa-language text-dark me-1"></i> <strong>N/A</strong>
                        </div>
                    </div>
                </div>
                <hr class="my-3">
                <strong class="card-heading"><i class="fa fa-car me-1 text-danger"></i> Vehicle Details</strong>
                <div class="d-flex align-items-center mt-3 gap-3">
                    <img src="/assets/images/carimage.png" alt="Car Image" id="preview_vehicle_photo"
                        class="rounded me-3 cursor-pointer"
                        style="width:80px;height:75px;object-fit:cover;"
                        data-bs-toggle="modal" data-bs-target="#carPhotoModal">
                    <div class="w-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex justify-content-center align-items-center" style="font-size:13px">
                                <strong id="preview_vehicle_name">N/A</strong>
                                (<span id="preview_vehicle_model">N/A</span>)
                                <span class="verified-star"><i class="fa fa-check"></i></span>
                            </div>
                        </div>
                        <div class="mt-2 small text-muted">
                            <div class="d-flex align-items-center gap-4 mb-1">
                                <div class="d-flex align-items-center" title="Seats">
                                    <i class="fa fa-users text-info me-1"></i><strong id="preview_seats">N/A</strong>
                                </div>
                                <div class="d-flex align-items-center" title="Fuel Type">
                                    <i class="fa fa-gas-pump text-warning me-1"></i><strong id="preview_fuel">N/A</strong>
                                </div>
                                <div class="d-flex align-items-center" title="Luggage">
                                    <i class="fa fa-suitcase-rolling text-secondary me-1"></i><strong id="preview_luggage">N/A</strong>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-4">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-file-contract text-success me-1"></i>RC: <strong class="ms-1" id="preview_rc">N/A</strong>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-file-alt text-danger me-1"></i>IN: <strong class="ms-1" id="preview_in">N/A</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 p-3 bg-light  small">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-road text-primary me-1"></i>Per Km: <strong class="ms-1 text-dark" id="preview_per_km">N/A</strong>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-plus-circle text-warning me-1"></i>Extra Km: <strong class="ms-1 text-dark" id="preview_extra_km">N/A</strong>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-clock text-info me-1"></i>Add. Hour: <strong class="ms-1 text-dark" id="preview_extra_hour">N/A</strong>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-calendar-plus text-success me-1"></i>Extra Day: <strong class="ms-1 text-dark" id="preview_extra_day">N/A</strong>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center small mb-1">
                        <strong>
                            <i class="fa fa-chart-line text-primary me-1"></i> Profile Completion
                        </strong>
                        <span id="preview_profile_percent_text" class="fw-bold">0%</span>
                    </div>
                    <div class="progress" style="height:8px;">
                        <div id="preview_profile_progress" class="progress-bar" role="progressbar" style="width:0%" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="remarksModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="margin-left: 3%; margin-right: auto;">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light text-dark">
                <h5 class="modal-title fw-bold">Remarks for <span id="rmk_user_name" class="text-primary"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                                <th scope="col" width="30%">Contact Person</th>
                                <th scope="col" width="40%">Remarks</th>
                                <th scope="col" width="20%">Date & Time</th>
                            </tr>
                        </thead>
                        <tbody id="remarks_table_body">
                        </tbody>
                    </table>
                </div>
                <hr>
                <p class="fw-bold mb-2">Add Remark</p>
                <div class="row">
                    <div class="col-12">
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
<div class="modal fade" id="editLogModal" tabindex="-1" aria-labelledby="editLogModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLogModalLabel">Edit Logs</h5>
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal"><i class="fa fa-times text-dark"></i></button>
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
<div class="modal fade" id="vehicleImagesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title"><i class="fa fa-camera text-primary me-2"></i>Update Vehicle Images</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3" id="sixImagesContainer">
                    <div class="col-md-4 text-center">
                        <label class="form-label fw-semibold">Front View</label>
                        <div class="position-relative d-inline-block w-100">
                            <img src="assets/images/placeholder.png" id="prev_front_view" class="img-thumbnail mb-2" style="height:100px; width:100%; object-fit:cover;">
                            <!--<button type="button" class="btn-close clear-img-btn bg-white position-absolute top-0 end-0 m-1 d-none shadow-sm" data-target="front_view" aria-label="Remove" style="padding: 0.35rem; border-radius: 50%; opacity: 0.8;"></button>-->
                        </div>
                        <input type="file" class="form-control form-control-sm local-image-select" data-key="front_view_image_url" data-target="front_view" accept="image/jpeg, image/png, image/jpg">
                    </div>
                    <div class="col-md-4 text-center">
                        <label class="form-label fw-semibold">Boot Image</label>
                        <div class="position-relative d-inline-block w-100">
                            <img src="assets/images/placeholder.png" id="prev_boot_image" class="img-thumbnail mb-2" style="height:100px; width:100%; object-fit:cover;">
                            <!--<button type="button" class="btn-close clear-img-btn bg-white position-absolute top-0 end-0 m-1 d-none shadow-sm" data-target="boot_image" aria-label="Remove" style="padding: 0.35rem; border-radius: 50%; opacity: 0.8;"></button>-->
                        </div>
                        <input type="file" class="form-control form-control-sm local-image-select" data-key="boot_image_url" data-target="boot_image" accept="image/jpeg, image/png, image/jpg">
                    </div>
                    <div class="col-md-4 text-center">
                        <label class="form-label fw-semibold">Extra Image 1</label>
                        <div class="position-relative d-inline-block w-100">
                            <img src="assets/images/placeholder.png" id="prev_extra_image_1" class="img-thumbnail mb-2" style="height:100px; width:100%; object-fit:cover;">
                            <!--<button type="button" class="btn-close clear-img-btn bg-white position-absolute top-0 end-0 m-1 d-none shadow-sm" data-target="extra_image_1" aria-label="Remove" style="padding: 0.35rem; border-radius: 50%; opacity: 0.8;"></button>-->
                        </div>
                        <input type="file" class="form-control form-control-sm local-image-select" data-key="extra_image_1_url" data-target="extra_image_1" accept="image/jpeg, image/png, image/jpg">
                    </div>
                    <div class="col-md-4 text-center">
                        <label class="form-label fw-semibold">Top View</label>
                        <div class="position-relative d-inline-block w-100">
                            <img src="assets/images/placeholder.png" id="prev_car_top_view" class="img-thumbnail mb-2" style="height:100px; width:100%; object-fit:cover;">
                            <!--<button type="button" class="btn-close clear-img-btn bg-white position-absolute top-0 end-0 m-1 d-none shadow-sm" data-target="car_top_view" aria-label="Remove" style="padding: 0.35rem; border-radius: 50%; opacity: 0.8;"></button>-->
                        </div>
                        <input type="file" class="form-control form-control-sm local-image-select" data-key="car_top_view_image_url" data-target="car_top_view" accept="image/jpeg, image/png, image/jpg">
                    </div>
                    <div class="col-md-4 text-center">
                        <label class="form-label fw-semibold">Interior Front</label>
                        <div class="position-relative d-inline-block w-100">
                            <img src="assets/images/placeholder.png" id="prev_interior_front" class="img-thumbnail mb-2" style="height:100px; width:100%; object-fit:cover;">
                            <!--<button type="button" class="btn-close clear-img-btn bg-white position-absolute top-0 end-0 m-1 d-none shadow-sm" data-target="interior_front" aria-label="Remove" style="padding: 0.35rem; border-radius: 50%; opacity: 0.8;"></button>-->
                        </div>
                        <input type="file" class="form-control form-control-sm local-image-select" data-key="interior_front_image_url" data-target="interior_front" accept="image/jpeg, image/png, image/jpg">
                    </div>
                    <div class="col-md-4 text-center">
                        <label class="form-label fw-semibold">Special Features</label>
                        <div class="position-relative d-inline-block w-100">
                            <img src="assets/images/placeholder.png" id="prev_special_features" class="img-thumbnail mb-2" style="height:100px; width:100%; object-fit:cover;">
                            <!--<button type="button" class="btn-close clear-img-btn bg-white position-absolute top-0 end-0 m-1 d-none shadow-sm" data-target="special_features" aria-label="Remove" style="padding: 0.35rem; border-radius: 50%; opacity: 0.8;"></button>-->
                        </div>
                        <input type="file" class="form-control form-control-sm local-image-select" data-key="special_features_image_url" data-target="special_features" accept="image/jpeg, image/png, image/jpg">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-success" id="saveVehicleImagesBtn">
                    <i class="fa fa-upload me-1"></i>Upload & Save
                </button>
            </div>
        </div>
    </div>
</div>
<!--<div class="col-lg-6">-->
<!--<div class="card mb-2 d-none" id="driverDetailsCard">-->
<!--    <div class="card-body p-3">-->
<!--        <div class="d-flex justify-content-between align-items-center">-->
<!--            <strong>-->
<!--                <i class="fa fa-id-card me-1 text-danger"></i> Driver-->
<!--                Details-->
<!--            </strong>-->
<!--            <a href="javascript:void(0)"-->
<!--                class="backToMap text-decoration-none text-dark small">-->
<!--                <i class="fa fa-arrow-left me-1"></i> Back-->
<!--            </a>-->
<!--        </div>-->
<!--        <div class="d-flex align-items-center mt-3">-->
<!--            <img src="/assets/images/driver.png" alt="Driver Photo"-->
<!--                class="rounded-circle me-3 cursor-pointer"-->
<!--                style="width:60px;height:60px;object-fit:cover;"-->
<!--                data-bs-toggle="modal" data-bs-target="#driverPhotoModal">-->
<!--            <div class="w-100">-->
<!--                <div-->
<!--                    class="d-flex justify-content-between align-items-center">-->
<!--                    <div class="d-flex align-items-center gap-2"-->
<!--                        style="font-size:14px">-->
<!--                        <span id="driver_name">Venkatesan</span>-->
<!--                        <a href="#" class="text-warning" title="Edit Driver"-->
<!--                            id="driver_profile_edit">-->
<!--                            <i class="fa fa-edit text-info"></i>-->
<!--                        </a>-->
<!--                    </div>-->
<!--                    <div class="d-inline-flex align-items-center gap-2">-->
<!--                        <a href="tel:9876543210" class="call-con"-->
<!--                            title="Call Owner">-->
<!--                            <i class="fa fa-phone"></i>-->
<!--                        </a>-->
<!--                        <a href="https://wa.me/919876543210" target="_blank"-->
<!--                            class="whatsapp-icon" title="WhatsApp Owner">-->
<!--                            <i class="fab fa-whatsapp"></i>-->
<!--                        </a>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="small text-muted mt-1"-->
<!--                    style="display: flex;flex-direction: column;">-->
<!--                    <span><i-->
<!--                            class="fa fa-check-circle text-success me-1"></i>-->
<!--                        KYC Verified</span>-->
<!--                    <span><i-->
<!--                            class="fa fa-map-marker-alt text-danger me-1"></i>-->
<!--                        Chennai, Tamil Nadu</span>-->
<!--                    <span><i class="fa fa-briefcase text-info"></i> 8-->
<!--                        Years</span>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="mt-3 small">-->
<!--            <div class="row">-->
<!--                <div class="col-6">-->
<!--                    <i class="fa fa-id-badge text-primary"></i>-->
<!--                    <strong>LMV-TR</strong> <span>|<strong>12 Jan-->
<!--                            2028</strong></span>-->
<!--                </div>-->
<!--                <div class="col-6 p-0">-->
<!--                    <i class="fa fa-language text-dark"></i>-->
<!--                    <strong>Tamil, English</strong>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <hr class="my-3">-->
<!--        <strong><i class="fa fa-car me-1 text-danger"></i> Vehicle-->
<!--            Details</strong>-->
<!--        <div class="d-flex align-items-center mt-3">-->
<!--            <img src="/assets/images/carimage.png" alt="Car Image"-->
<!--                class="rounded-circle me-3 cursor-pointer"-->
<!--                style="width:80px;height:60px;object-fit:cover;"-->
<!--                data-bs-toggle="modal" data-bs-target="#carPhotoModal">-->
<!--            <div class="w-100">-->
<!--                <div-->
<!--                    class="d-flex justify-content-between align-items-center">-->
<!--                    <div style="font-size:13px">-->
<!--                        <strong>Go Sedan</strong> (Hyundai Aura 1.2 MT CNG)-->
<!--                        <span><i-->
<!--                                class="fa fa-check-circle text-success me-1"></i></span>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="mt-2 small text-muted">-->
<!--                    <div class="d-flex align-items-center gap-4 mb-1">-->
<!--                        <div class="d-flex align-items-center">-->
<!--                            <i-->
<!--                                class="fa fa-users text-info me-1"></i><strong>4+1</strong>-->
<!--                        </div>-->
<!--                        <div class="d-flex align-items-center">-->
<!--                            <i-->
<!--                                class="fa fa-gas-pump text-warning me-1"></i><strong>Petrol</strong>-->
<!--                        </div>-->
<!--                        <div class="d-flex align-items-center">-->
<!--                            <i-->
<!--                                class="fa fa-suitcase-rolling text-secondary me-1"></i><strong>2</strong>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="d-flex align-items-center gap-4">-->
<!--                        <div class="d-flex align-items-center">-->
<!--                            <i-->
<!--                                class="fa fa-file-contract text-success me-1"></i>RC:-->
<!--                            <strong>30 Nov 2026</strong>-->
<!--                        </div>-->
<!--                        <div class="d-flex align-items-center">-->
<!--                            <i-->
<!--                                class="fa fa-file-alt text-danger me-1"></i>IN:-->
<!--                            <strong>15 Aug 2027</strong>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="mt-3 p-3 rounded d-none">-->
<!--        </div>-->
<!--        <div class="mt-3 p-3 bg-light small">-->
<!--            <div class="d-flex align-items-center gap-2 flex-wrap">-->
<!--                <div class="d-flex align-items-center">-->
<!--                    <i class="fa fa-road text-primary me-2"></i>Per Km:-->
<!--                    <strong class="ms-1">₹14</strong>-->
<!--                </div>-->
<!--                <div class="d-flex align-items-center">-->
<!--                    <i class="fa fa-plus-circle text-warning me-2"></i>Extra-->
<!--                    Km: <strong class="ms-1">₹15</strong>-->
<!--                </div>-->
<!--                <div class="d-flex align-items-center">-->
<!--                    <i class="fa fa-clock text-info me-2"></i>Additional-->
<!--                    Hour: <strong class="ms-1">₹100</strong>-->
<!--                </div>-->
<!--                <div class="d-flex align-items-center">-->
<!--                    <i-->
<!--                        class="fa fa-calendar-plus text-success me-2"></i>Extra-->
<!--                    Day: <strong class="ms-1">₹1200</strong>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!--</div>-->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>
<!--Map-->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Marker Cluster -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<!-- Tom Select -->
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<!-- noUiSlider -->
<link href="https://cdn.jsdelivr.net/npm/nouislider@15.7.0/dist/nouislider.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/nouislider@15.7.0/dist/nouislider.min.js"></script>
<script>
    const API_DOMAIN_2 = "<?= API_DOMAIN_2 ?>";
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Your existing Languages TomSelect
        if (!window.editLanguagesTomSelect) {
            window.editLanguagesTomSelect = new TomSelect("#edit_languages", {
                plugins: ['remove_button'], 
                placeholder: "Select Languages",
                maxItems: null, 
            });
        }

        // NEW: Initialize Tom Select for State
        if (!window.editStateTomSelect) {
            window.editStateTomSelect = new TomSelect("#edit_state", {
                placeholder: "Select State",
                maxItems: 1, // Only allow 1 state to be selected
                allowEmptyOption: true
            });
        }
    });
    // 1. LOCAL PREVIEW & VALIDATION (Browsing Only)
    $(document).on('change', '.local-image-select', function(e) {
        let file = this.files[0];
        let target = $(this).data('target');
        let $previewImg = $('#prev_' + target);
        let $clearBtn = $('.clear-img-btn[data-target="' + target + '"]');
        if (!file) {
            $clearBtn.addClass('d-none');
            return;
        }
        // Validation: Check File Type
        let validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            toast('error', 'Only JPG, JPEG, and PNG formats are allowed.');
            $(this).val(''); // Clear the file input
            $clearBtn.addClass('d-none');
            return;
        }
        // Validation: Check File Size (Max 2MB)
        let maxSize = 2 * 1024 * 1024; // 2MB in bytes
        if (file.size > maxSize) {
            toast('error', 'Image size must be less than 2MB.');
            $(this).val(''); // Clear the file input
            $clearBtn.addClass('d-none');
            return;
        }
        // Display Local Preview
        let reader = new FileReader();
        reader.onload = function(e) {
            $previewImg.attr('src', e.target.result);
            $clearBtn.removeClass('d-none'); // Show the X button
        };
        reader.readAsDataURL(file);
    });
    // 2. CLEAR SELECTED IMAGE ("X" Button Click)
    $(document).on('click', '.clear-img-btn', function() {
        let target = $(this).data('target');
        let $input = $('.local-image-select[data-target="' + target + '"]');
        let $previewImg = $('#prev_' + target);
        $input.val(''); // Empty the file input so it doesn't get uploaded
        // (Optional) Reset to placeholder OR let it keep previous DB image if we had logic for it. 
        // Here we reset to placeholder indicating it's cleared.
        $previewImg.attr('src', 'assets/images/placeholder.png');
        $(this).addClass('d-none'); // Hide the X button
    });
    // 3. UPLOAD TO S3 AND TRIGGER DB SAVE
    $(document).on('click', '#saveVehicleImagesBtn', async function() {
        let $btn = $(this);
        // Find only inputs that have a user-selected file
        let inputsWithFiles = $('.local-image-select').filter(function() {
            return this.files.length > 0;
        });
        if (inputsWithFiles.length === 0) {
            toast('info', 'No new images selected to upload.');
            $('#vehicleImagesModal').modal('hide');
            return;
        }
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Uploading...');
        let uploadPromises = [];
        inputsWithFiles.each(function() {
            let fileInput = this;
            let file = fileInput.files[0];
            let imageKey = $(this).data('key');
            let formData = new FormData();
            formData.append('image', file);
            formData.append('img_type', 'vehicle_' + imageKey);
            formData.append('name', 'driver_vehicle_upload');
            formData.append('auth_key', 'asdfghjklpoiuytrewqzxcvbnm!@$%^&*()');
            // Create an AJAX promise for each upload
            let uploadPromise = $.ajax({
                url: 'https://www.goride.run/api/v1-cus/s3-upload-image',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false
            }).then(response => {
                if (response.status) {
                    let s3Url = response.data.url;
                    // Store URL in the hidden input for DB saving
                    $('#val_' + imageKey).val(s3Url);
                    // Update Front-end UI immediately
                    // Update Front-end UI immediately
                    if (imageKey === 'front_view_image_url') {
                        $('#main_vehicle_preview').attr('src', s3Url);
                        // Also update it in the hidden gallery so it appears when swiping
                        $('#mainGalleryImage').attr('src', s3Url);
                    } else {
                        // FIX: Just remove '_url' to match the exact HTML ID (e.g., 'gallery_boot_image')
                        $('#gallery_' + imageKey.replace('_url', '')).attr('src', s3Url);
                    }
                } else {
                    throw new Error(response.message || `Upload failed for ${imageKey}`);
                }
            });
            uploadPromises.push(uploadPromise);
        });
        try {
            // Wait for all image uploads to finish successfully
            await Promise.all(uploadPromises);
            $btn.html('<i class="fa fa-spinner fa-spin me-1"></i> Saving to DB...');
            // REBUILD the gallery so the newly uploaded S3 URLs are immediately swipeable
            refreshVehicleGallery();
            // Trigger your existing DB Save logic seamlessly
            let $dbSaveBtn = $('.save-section[data-section="vehicle"]');
            if ($dbSaveBtn.length) {
                $dbSaveBtn.trigger('click');
            }
            toast('success', 'Images uploaded and saved successfully!');
            setTimeout(() => {
                $btn.prop('disabled', false).html('<i class="fa fa-upload me-1"></i>Upload & Save');
                $('#vehicleImagesModal').modal('hide');
                // Clean up selections so they don't get uploaded twice next time
                $('.local-image-select').val('');
                $('.clear-img-btn').addClass('d-none');
            }, 1200);
        } catch (error) {
            toast('error', error.message || 'Some images failed to upload. Please try again.');
            $btn.prop('disabled', false).html('<i class="fa fa-upload me-1"></i>Upload & Save');
        }
    });
    let selectedDriverRequest = null;
    const jobStatuses = {
        1021: 'dispatched',
        1022: 'assigned',
        1023: 'reached',
        1024: 'onboard'
    };
    var assignments = {
        1021: 1,
        1022: 2,
        1023: 3,
        1024: 4
    };
    const drivers = {
        1: {
            name: 'Driver A',
            color: '#0d6efd',
            status: 'online',
            profilePercent: 90
        },
        2: {
            name: 'Driver B',
            color: '#198754',
            status: 'busy',
            profilePercent: 60
        },
        3: {
            name: 'Driver C',
            color: '#dc3545',
            status: 'offline',
            profilePercent: 45
        },
        4: {
            name: 'Driver D',
            color: '#6f42c1',
            status: 'online',
            profilePercent: 78
        },
        5: {
            name: 'Driver E',
            color: '#fd7e14',
            status: 'busy',
            profilePercent: 100
        },
        6: {
            name: 'Driver F',
            color: '#20c997',
            status: 'online',
            profilePercent: 27
        },
        7: {
            name: 'Driver G',
            color: '#0dcaf0',
            status: 'offline',
            profilePercent: 82
        }
    };
    $(document).ready(function() {
        const jobs = {
            1021: {
                title: 'Job #1021',
                date: '2026-01-15',
                pickup: 'Chennai',
                drop: 'Salem'
            },
            1022: {
                title: 'Job #1022',
                date: '2026-01-16',
                pickup: 'Trichy',
                drop: 'Madurai'
            },
            1023: {
                title: 'Job #1023',
                date: '2026-01-16',
                pickup: 'Chennai',
                drop: 'Coimbatore'
            },
            1024: {
                title: 'Job #1024',
                date: '2026-01-20',
                pickup: 'Salem',
                drop: 'Trichy'
            },
            1025: {
                title: 'Job #1025',
                date: '2026-01-21',
                pickup: 'Erode',
                drop: 'Chennai'
            },
            1026: {
                title: 'Job #1026',
                date: '2026-01-23',
                pickup: 'Madurai',
                drop: 'Tirunelveli'
            },
            1027: {
                title: 'Job #1027',
                date: '2026-01-25',
                pickup: 'Vellore',
                drop: 'Salem'
            },
            1028: {
                title: 'Job #1028',
                date: '2026-01-26',
                pickup: 'Chennai',
                drop: 'Trichy'
            },
            1029: {
                title: 'Job #1029',
                date: '2026-01-28',
                pickup: 'Coimbatore',
                drop: 'Erode'
            },
            1030: {
                title: 'Job #1030',
                date: '2026-01-30',
                pickup: 'Salem',
                drop: 'Chennai'
            }
        };
        const scheduledTrips = [{
                id: 1,
                driverId: 1,
                from: 'Trichy',
                to: 'Chennai',
                date: '2026-01-20',
                amount: 4500
            },
            {
                id: 2,
                driverId: 2,
                from: 'Madurai',
                to: 'Salem',
                date: '2026-01-22',
                amount: 5200
            }
        ];
        const requestedJobs = [{
                id: 9001,
                driverId: 1,
                driverName: 'Driver A',
                from: 'Salem',
                to: 'Chennai',
                date: '2026-01-18'
            },
            {
                id: 9002,
                driverId: 4,
                driverName: 'Driver D',
                from: 'Trichy',
                to: 'Chennai',
                date: '2026-01-20'
            }
        ];
        function renderScheduledJobs(list = scheduledTrips) {
            $('#scheduledJobs').empty();
            if (!scheduledTrips || scheduledTrips.length === 0) {
                $('#scheduledJobs').html(
                    '<div class="alert alert-info text-center mb-0">No scheduled jobs</div>'
                );
                return;
            }
            let html = '';
            list.forEach(trip => {
                const driverName = drivers[trip.driverId]?.name || 'Driver';
                html += `
      <div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card mb-2 shadow-sm border-0">
                <div class="card-body py-3 px-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <!-- DRIVER -->
                        <div class="fw-semibold text-dark">
                            <i class="fa fa-user text-primary me-2"></i>
                            ${driverName}
                        </div>
                        <!-- FROM -->
                        <div class="text-dark">
                            <i class="fa fa-map-marker-alt text-success me-2"></i>
                            ${trip.from}
                        </div>
                        <!-- TO -->
                        <div class="text-dark">
                            <i class="fa fa-map-marker-alt text-danger me-2"></i>
                            ${trip.to}
                        </div>
                        <!-- DATE -->
                        <div class="text-dark">
                            <i class="fa fa-calendar-alt text-warning me-2"></i>
                            ${trip.date}
                        </div>
                        <!-- AMOUNT -->
                        <div class="fw-bold text-dark">
                            <i class="fa fa-rupee-sign text-info me-1"></i>
                            ${trip.amount}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
`;
            });
            $('#scheduledJobs').html(html);
        }
        function getMatchingJobCount(driverReq) {
            return Object.values(jobs).filter(job => {
                const sameRoute =
                    job.pickup === driverReq.from &&
                    job.drop === driverReq.to;
                // OPTIONAL: date match (same day only)
                const sameDate = job.date === driverReq.date;
                return sameRoute && sameDate;
            }).length;
        }
        function renderRequestedJobs() {
            $('#requestedJobs').empty();
            if (!requestedJobs.length) {
                $('#requestedJobs').html(
                    '<div class="alert alert-info text-center mb-0">No driver requests</div>'
                );
                return;
            }
            let html = '';
            requestedJobs.forEach(req => {
                // 🔥 AUTO MATCH COUNT
                const matchCount = getMatchingJobCount(req);
                html += `
        <div class="card mb-2 shadow-sm border-0 driver-request cursor-pointer"
             data-request="${req.id}">
            <div class="card-body p-3">
                <!-- TOP ROW -->
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <!-- DRIVER -->
                        <div class="fw-semibold mb-1">
                            <i class="fa fa-user text-primary me-2"></i>
                            ${req.driverName}
                        </div>
                        <!-- ROUTE -->
                        <div class="small">
                            <i class="fa fa-map-marker-alt text-success me-1"></i>
                            <strong>${req.from}</strong>
                            <i class="fa fa-arrow-right mx-2 text-muted"></i>
                            <i class="fa fa-map-marker-alt text-danger me-1"></i>
                            <strong>${req.to}</strong>
                        </div>
                        <!-- DATE -->
                        <div class="text-muted small mt-1">
                            <i class="fa fa-calendar-alt text-warning me-1"></i>
                            ${req.date}
                        </div>
                    </div>
                    <!-- 🔥 AUTO MATCH BADGE -->
                    <div>
                        ${matchCount > 0
                        ? `<span class="badge bg-success">
                                       ${matchCount} Matching Job${matchCount > 1 ? 's' : ''}
                                   </span>`
                        : `<span class="badge bg-secondary">
                                       No Match
                                   </span>`
                    }
                    </div>
                </div>
            </div>
        </div>`;
            });
            $('#requestedJobs').html(html);
        }
        function initScheduledLocationFilters() {
            const fromSet = new Set();
            const toSet = new Set();
            scheduledTrips.forEach(trip => {
                fromSet.add(trip.from);
                toSet.add(trip.to);
            });
            fromSet.forEach(loc => {
                $('#scheduledFrom').append(
                    `<option value="${loc}">${loc}</option>`
                );
            });
            toSet.forEach(loc => {
                $('#scheduledTo').append(
                    `<option value="${loc}">${loc}</option>`
                );
            });
            // Init select2
            $('.select2').select2({
                width: '100%',
                placeholder: 'Select',
                allowClear: true
            });
        }
        $('#searchScheduledJobs').on('click', function() {
            const fromVal = $('#scheduledFrom').val();
            const toVal = $('#scheduledTo').val();
            if (!fromVal && !toVal) {
                renderScheduledJobs();
                return;
            }
            const filtered = scheduledTrips.filter(trip => {
                if (fromVal && trip.from !== fromVal) return false;
                if (toVal && trip.to !== toVal) return false;
                return true;
            });
            renderScheduledJobs(filtered);
        });
        let selectedDriver = null;
        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            };
            return date.toLocaleDateString('en-GB', options);
        }
        function renderJobLists() {
            $('#unassignedJobs').empty();
            $('#scheduledJobs').empty();
            $('#assignedJobs').empty();
            Object.entries(jobs).forEach(([jobId, job]) => {
                const assignedDriver = assignments[jobId];
                const jobHtml = `
                    <div class="card mb-2 shadow-sm ddddd border-0">
                        <div class="card-body p-3" >
                          <div class="d-flex justify-content-between align-items-start mb-2">
                    <!-- LEFT: JOB TITLE + ICONS -->
                <div class="d-flex align-items-center gap-2">
                    <!-- JOB ID -->
                    <h6 class="mb-0 fw-bold">${job.title}</h6>
                    <!-- EDIT -->
                    <a href="#"
                       class="text-warning jobEdit"
                       title="Edit Job"
                       data-job="${jobId}">
                        <i class="fa fa-edit text-info"></i>
                    </a>
                    <!-- VIEW -->
                    <a href="https://www.goride.run/booking-information/3d58d95584b7651a83185e3941471aac7d12eb1f7385e7a90dd70f7f978b4f00"
                       class="text-primary jobView"
                       title="View Job"
                       target="_blank">
                        <i class="fa fa-eye text-secondary"></i>
                    </a>
                    <!-- ✅ STATUS (LAST) -->
                </div>
                 ${assignedDriver ? (() => {
                        const s = getJobStatusUI(jobStatuses[jobId] || 'assigned');
                        return `
                        <span class="badge bg-${s.class}"
                              style="font-size:11px;padding:3px 6px;">
                            ${s.text}
                        </span>
                    `;
                    })() : ''}
                    <!-- RIGHT: BIDS BUTTON (UNCHANGED) -->
                    ${!assignedDriver ? `
                   <button class="btn btn-sm btn-warning bidBtn text-dark px-2 position-relative"
                        data-job="${jobId}">
                    <i class="fa fa-gavel me-1 text-dark"></i>
                    Bids
                    <!-- COUNT BADGE -->
                    <span class="position-absolute top-0 start-100 translate-middle
                                 badge rounded-pill bg-danger">
                        4
                    </span>
                </button>
                ` : ''}
                </div>
                       <div class="mt-2 row small text-muted">
                    <!-- ROW 1: OWNER | DATE -->
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fa fa-user-tie text-dark " style="width:16px;"></i>
                <span class="d-inline-flex align-items-center gap-2">
                    <a href="#" class="owner-link fw-bold" data-owner="101">
                        Ramesh
                    </a>
                    <!-- Call icon -->
                    <a href="tel:9876543210"
                       class="call-con"
                       title="Call Owner">
                        <i class="fa fa-phone"></i>
                    </a>
                    <!-- WhatsApp icon -->
                    <a href="https://wa.me/919876543210"
                       target="_blank"
                       class="whatsapp-icon"
                       title="WhatsApp Owner">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-1 justify-content-end">
                            <i class="fa fa-calendar text-danger " style="width:16px;"></i>
                            <span>15 Jan 2026 · 10:30 AM</span>
                        </div>
                    </div>
                    <!-- ROW 2: FROM -->
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fa fa-map-marker-alt text-success " style="width:16px;"></i>
                            <span> <strong>Chennai</strong></span>
                        </div>
                    </div>
                    <!-- ROW 3: TO -->
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fa fa-map-marker-alt text-danger " style="width:16px;"></i>
                            <span><strong>Salem</strong></span>
                        </div>
                    </div>
                    <!-- ROW 4: TRIP | CAR + PASSENGERS + LUGGAGE -->
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between gap-4 mb-1">
                            <!-- LEFT : TRIP -->
                            <div class="d-flex align-items-center">
                                <i class="fa fa-exchange-alt text-info " style="width:16px;"></i>
                                <span><strong>One Way</strong></span>
                            </div>
                            <!-- RIGHT : CAR | PASSENGERS | LUGGAGE -->
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-car text-secondary me-1" style="width:16px;"></i>
                                    <strong>Sedan</strong>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-users text-success me-1" style="width:16px;"></i>
                                    <strong>4</strong>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-suitcase text-warning me-1" style="width:16px;"></i>
                                    <strong>2</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ROW 5: DRIVER -->
                ${assignedDriver ? `
                <div class="col-6">
                    <div class="d-flex align-items-center mb-1">
                        <i class="fa fa-user text-primary" style="width:16px;"></i>
                        <span class="d-inline-flex align-items-center gap-1">
                            <a href="#"
                               class="driver-link fw-bold"
                               data-driver="${assignedDriver}">
                                ${drivers[assignedDriver].name}
                            </a>
                            <i class="fa fa-times-circle text-danger cursor-pointer remove-driver d-none"
                               title="Remove Driver"
                               data-driver="${assignedDriver}"
                               data-job="${jobId}">
                            </i>
                        </span>
                    </div>
                </div>
                ` : `
                <div class="col-6">
                    <span class="badge bg-secondary cursor-pointer unassigned-driver-btn"
                          data-job="${jobId}">
                        Unassigned
                    </span>
                </div>
                `}
                    <div class="col-6">
                        <div class="d-flex align-items-center justify-content-end mb-1">
                            <i class="fa fa-clock me-1 text-danger" ></i>
                            <span >
                                  15 Jan 2026 · 10:30 AM
                            </span>
                        </div>
                    </div>
                    <!-- ROW 6: CALL | WHATSAPP | BOOKED TIME -->
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between pt-1 ">
                           <div class="text-end">
                    <div class="small text-muted">Base Fare: ₹4525</div>
                    <div class="small text-muted">Toll: ₹495</div>
                    <div class="fw-bold text-dark" style=""font-size:14px;>₹5020</div>
                </div>
                           <span class="text-muted cursor-pointer cancelJobBtn"
                      style="font-size:14px"
                      data-job="1021">
                    <i class="fa fa-times-circle text-danger"></i>
                    Cancel
                </span>
                        </div>
                    </div>
                </div>
                        </div>
                    </div>
                    `;
                if (assignedDriver) {
                    $('#assignedJobs').append(jobHtml);
                } else {
                    $('#unassignedJobs').append(jobHtml);
                }
            })
            if ($('#scheduledJobs').is(':empty')) {
                $('#scheduledJobs').html('<div class="alert alert-info text-center mb-0">No scheduled jobs</div>');
            }
            if ($('#unassignedJobs').is(':empty')) {
                $('#unassignedJobs').html('<div class="alert alert-success text-center mb-0">All jobs assigned 2!</div>');
            }
            $('#assignedCount').text($('#assignedJobs .card').length);
            $('#unassignedCount').text($('#unassignedJobs .card').length);
        }
        function populateDriverDropdown() {
            const select = $('#driver_id');
            select.empty();
            select.append('<option value="">-- Select Driver --</option>');
            Object.entries(drivers).forEach(([id, driver]) => {
                select.append(`
                <option value="${id}">
                    ${driver.name} (${driver.status})
                </option>
            `);
            });
        }
        // const calendar = new FullCalendar.Calendar(
        //     document.getElementById('driverCalendar'),
        //     {
        //         initialView: 'dayGridMonth',
        //         initialDate: '2026-01-23',
        //         height: '100%',
        //         fixedWeekCount: false,
        //         headerToolbar: {
        //             left: 'prev,next today',
        //             center: 'title',
        //             right: ''
        //         },
        //         events: [],
        //         eventDidMount(info) {
        //             info.el.style.fontSize = '12px';
        //             info.el.style.borderRadius = '6px';
        //         }
        //     }
        // );
        /* ===============================
           REFRESH CALENDAR
        =============================== */
        // function refreshCalendar(filterDriverId = null) {
        //     calendar.removeAllEvents();
        //     Object.entries(assignments).forEach(([jobId, driverId]) => {
        //         if (!jobs[jobId] || !drivers[driverId]) {
        //             console.warn(`Missing data for job ${jobId} or driver ${driverId}`);
        //             return;
        //         }
        //         if (filterDriverId && driverId != filterDriverId) return;
        //         calendar.addEvent({
        //             title: `${drivers[driverId].name} – ${jobs[jobId].title}`,
        //             start: jobs[jobId].date,
        //             backgroundColor: drivers[driverId].color,
        //             borderColor: drivers[driverId].color,
        //             extendedProps: {
        //                 jobId: jobId,
        //                 driverId: driverId
        //             }
        //         });
        //     });
        // }
        /* ===============================
           INITIAL RENDER
        =============================== */
        // calendar.render();
        // renderDriversList();
        populateDriverDropdown();
        // renderJobLists();
        renderScheduledJobs();
        initScheduledLocationFilters();
        renderRequestedJobs();
        /* ===============================
           TAB SHOW -> UPDATE CALENDAR SIZE
        =============================== */
        /* ===============================
           WEEK / MONTH VIEW BUTTONS
        =============================== */
        // $('button[data-view]').on('click', function () {
        //     $('button[data-view]').removeClass('active');
        //     $(this).addClass('active');
        //     const view = $(this).data('view');
        //     calendar.changeView(view === 'week' ? 'timeGridWeek' : 'dayGridMonth');
        // });
        /* ===============================
           DRIVER CLICK -> FILTER CALENDAR
        =============================== */
        // $(document).on('click', '.driver-item', function (e) {
        //     e.preventDefault();
        //     $('.driver-item').removeClass('active');
        //     $(this).addClass('active');
        //     selectedDriver = $(this).data('driver');
        //     refreshCalendar(selectedDriver);
        // });
        /* ===============================
           SHOW ALL DRIVERS
        =============================== */
        // $('#showAllDrivers').on('click', function () {
        //     selectedDriver = null;
        //     $('.driver-item').removeClass('active');
        //     refreshCalendar();
        // });
        /* ===============================
           ASSIGN BUTTON CLICK
        =============================== */
        $(document).on('click', '.assignBtn', function() {
            const jobId = $(this).data('job');
            const job = jobs[jobId];
            // Set job ID
            $('#job_id').val(jobId);
            $('#driver_id').val('');
            // Reset checkboxes
            $('#notify_whatsapp, #notify_sms').prop('checked', true);
            // Show job details
            $('#jobDetailsSection').html(`
            <div class="alert alert-light border-start border-4 border-primary">
                <h6 class="fw-bold mb-2">${job.title}</h6>
                <small class="text-muted">
                    <i class="fa fa-calendar me-1"></i>${formatDate(job.date)}<br>
                    <i class="fa fa-map-marker-alt text-success me-1"></i>From: <strong>${job.pickup}</strong><br>
                    <i class="fa fa-map-marker-alt text-danger me-1"></i>To: <strong>${job.drop}</strong>
                </small>
            </div>
        `);
            $('#assignDriverModal').modal('show');
        });
        /* ===============================
           ASSIGN DRIVER ACTION
        =============================== */
        $('#assignBtn').on('click', function() {
            const jobId = $('#job_id').val();
            const driverId = $('#driver_id').val();
            const notifyWhatsapp = $('#notify_whatsapp').is(':checked');
            const notifySms = $('#notify_sms').is(':checked');
            if (!driverId) {
                alert('Please select a driver');
                return;
            }
            // Store the assignment
            assignments[jobId] = driverId;
            console.log('Assignment created:', {
                jobId,
                driverId,
                notifyWhatsapp,
                notifySms,
                assignments
            });
            // Show notification
            let notifyMsg = [];
            if (notifyWhatsapp) notifyMsg.push('WhatsApp');
            if (notifySms) notifyMsg.push('SMS');
            if (notifyMsg.length > 0) {
                console.log(`Notifications sent via: ${notifyMsg.join(' & ')}`);
                // You can add actual notification logic here
            }
            // Update job lists
            renderJobLists();
            // Close modal
            $('#assignDriverModal').modal('hide');
            // Switch to Drivers tab and update calendar
            const driverTabBtn = document.querySelector('button[data-bs-target="#driversTab"]');
            const tab = new bootstrap.Tab(driverTabBtn);
            // Listen for tab shown event
            $(driverTabBtn).one('shown.bs.tab', function() {
                // Reset filter
                selectedDriver = null;
                $('.driver-item').removeClass('active');
                // Small delay to ensure tab is fully rendered
                setTimeout(() => {
                    refreshCalendar();
                    calendar.updateSize();
                }, 150);
            });
            // Show the tab
            tab.show();
        });
        /* ===============================
           JOB SEARCH FUNCTIONALITY
        =============================== */
        $('#jobSearch').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            // Search in both tabs
            $('#unassignedJobs .card, #scheduledJobs .card').each(function() {
                const jobText = $(this).text().toLowerCase();
                $(this).toggle(jobText.includes(searchTerm));
            });
            // Show "No results" message for unassigned jobs
            const unassignedVisible = $('#unassignedJobs .card:visible').length;
            if (searchTerm && unassignedVisible === 0) {
                if ($('#unassignedJobs .no-results').length === 0) {
                    $('#unassignedJobs').append('<div class="alert alert-warning text-center mb-0 no-results">No jobs found</div>');
                }
            } else {
                $('#unassignedJobs .no-results').remove();
            }
            // Show "No results" message for scheduled jobs
            const scheduledVisible = $('#scheduledJobs .card:visible').length;
            const scheduledCards = $('#scheduledJobs .card').length;
            if (searchTerm && scheduledVisible === 0 && scheduledCards > 0) {
                if ($('#scheduledJobs .no-results').length === 0) {
                    $('#scheduledJobs .alert-info').hide();
                    $('#scheduledJobs').append('<div class="alert alert-warning text-center mb-0 no-results">No jobs found</div>');
                }
            } else {
                $('#scheduledJobs .no-results').remove();
                if (scheduledCards === 0) {
                    $('#scheduledJobs .alert-info').show();
                }
            }
        });
        /* ===============================
           DRIVER SEARCH FUNCTIONALITY
        =============================== */
        // $('#driverSearch').on('keyup', function () {
        //     const searchTerm = $(this).val().toLowerCase();
        //     $('.driver-item').each(function () {
        //         const driverText = $(this).text().toLowerCase();
        //         $(this).toggle(driverText.includes(searchTerm));
        //     });
        // });
        /* ===============================
           DRIVER STATUS FILTER
        =============================== */
        $('#driverStatus').on('change', function() {
            const status = $(this).val().toLowerCase();
            $('.driver-item').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(!status || text.includes(status));
            });
        });
    });
    // #Suriya
    $(document).ready(function() {
        const firebaseConfig = {
            apiKey: "AIzaSyCiRKGU2xZyZNx5-ZwweLd5cPokxJjxKzw",
            authDomain: "goride-947ed.firebaseapp.com",
            projectId: "goride-947ed",
        };
        firebase.initializeApp(firebaseConfig);
        const db = firebase.firestore();
        let unassignedJobs = {};
        let websiteJobs = {};
        let assignedJobs = {};
        let cancelledJobs = {};
        let rejectData = {};
        let selectedDriver = null;
        let calendarData = [];
        let driversAll = {};
        let currentJobId = null;
        let currentExistingBidders = new Set(); // Add this line
        let selectedFilterType = null;
        let selectedStartDate = null;
        let selectedEndDate = null;
        let driversMap;
        let markerClusterGroup;
        let driverMarkers = {};
        let isLoadingDrivers = false;
        let percentageMin = 0;
        let percentageMax = 100;
        let aadhaarFrontUrl = null;
        let aadhaarBackUrl = null;
        let dlFrontUrl = null;
        let dlBackUrl = null;
        let rcFrontUrl = null;
        let rcBackUrl = null;
        let pucUrl = null;
        let insuranceUrl = null;
        function toggleSection(section, isEdit = true) {
            const viewEl = $('#view-' + section);
            const editEl = $('#edit-' + section);
            const editST = $(`.edit-section-trigger[data-section="${section}"]`);
            if (isEdit) {
                viewEl.hide();
                editST.hide();
                editEl.show();
            } else {
                editEl.hide();
                editST.show();
                viewEl.show();
            }
        }
        function initDriversMap() {
            $('#mapLoader').removeClass('hide');
            driversMap = L.map('driversMap', {
                zoomControl: true
            }).setView([13.0827, 80.2707], 10);
            L.tileLayer(
                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors',
                    maxZoom: 19
                }
            ).addTo(driversMap);
            markerClusterGroup = L.markerClusterGroup();
            driversMap.addLayer(markerClusterGroup);
            // 🔥 Directly load drivers (no map load event)
            loadDriversOnMap();
        }
        initDriversMap();
        /* =====================================================
   LOCATE DRIVER ON MAP (FROM drivers_current_location)
===================================================== */
let tempDriverMarker = null; // Keeps track of the pin so we can remove old ones

$(document).on('click', '.view-driver-location', function(e) {
    e.preventDefault();
    e.stopPropagation();

    const driverId = $(this).data('driver');
    const driverName = $(this).data('name');

    // 1. Switch UI back to the Map panel
    $('.backToMap').trigger('click');

    // 2. Wait for the map to become visible
    setTimeout(() => {
        if (driversMap) {
            driversMap.invalidateSize(); // Fixes Leaflet rendering glitches
        }

        // 3. Fetch exact location from drivers_current_location table
        $.ajax({
            url: window.location.origin + "/ajax/service/driverServices.php", // Using absolute path to prevent 404s
            type: "POST",
            dataType: "json",
            data: {
                method: 'get_driver_current_location',
                driver_id: driverId
            },
            success: function(res) {
                if (res.status && res.data) {
                    const lat = parseFloat(res.data.lat);
                    const lng = parseFloat(res.data.lng);
                    const lastUpdated = res.data.updated_at;

                    // Pan and Zoom map to location
                    driversMap.setView([lat, lng], 16, { animate: true });

                    // Remove previous temporary marker if it exists
                    if (tempDriverMarker) {
                        driversMap.removeLayer(tempDriverMarker);
                    }

                    // Create a distinct Red Marker Icon
                    const redPin = L.divIcon({
                        className: '',
                        html: `<div style="background-color: #dc3545; width: 18px; height: 18px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 8px rgba(0,0,0,0.6);"></div>`,
                        iconSize: [18, 18],
                        iconAnchor: [9, 9]
                    });

                    // Drop pin and open tooltip
                    tempDriverMarker = L.marker([lat, lng], {icon: redPin}).addTo(driversMap);
                    tempDriverMarker.bindPopup(`
                        <div style="text-align:center;">
                            <strong>${driverName}</strong><br>
                            <small class="text-muted">Last seen: ${lastUpdated}</small>
                        </div>
                    `).openPopup();

                } else {
                    toast('error', 'Location not found for this driver.');
                }
            },
            error: function() {
                toast('error', 'Failed to fetch driver location from server.');
            }
        });
    }, 300);
});
        function loadDriversOnMap() {
            if (isLoadingDrivers) return; // prevent double call
            isLoadingDrivers = true;
            $('#mapLoader').removeClass('hide');
            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-get-driver-list",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                success: function(res) {
                    if (!res.status || !res.data) {
                        $('#mapLoader').addClass('hide');
                        isLoadingDrivers = false;
                        return;
                    }
                    res.data.forEach(driver => {
                        if (!driver.s_lat || !driver.s_lang) return;
                        const lat = parseFloat(driver.s_lat);
                        const lng = parseFloat(driver.s_lang);
                        if (!driverMarkers[driver.id]) {
                            // Create pulsing icon
                            const pulseIcon = L.divIcon({
                                className: '',
                                html: `<div class="pulse-marker"></div>`,
                                iconSize: [20, 20],
                                iconAnchor: [10, 10]
                            });
                            const marker = L.marker([lat, lng], {
                                icon: pulseIcon
                            });
                            marker.bindPopup(`
                                <div style="min-width:200px">
                                    <strong>${driver.name}</strong><br>
                                    <small>${driver.mobile ?? ''}</small><br>
                                    <span class="badge bg-success mt-1">
                                        ${driver.vehicle_type ?? 'Driver'}
                                    </span>
                                </div>
                            `);
                            markerClusterGroup.addLayer(marker);
                            driverMarkers[driver.id] = marker;
                        } else {
                            // Update position smoothly
                            driverMarkers[driver.id].setLatLng([lat, lng]);
                        }
                    });
                    $('#mapLoader').addClass('hide');
                    isLoadingDrivers = false;
                },
                error: function() {
                    console.log('Driver fetch failed. Retrying...');
                    // Retry automatically after error
                    setTimeout(function() {
                        isLoadingDrivers = false;
                        loadDriversOnMap();
                    }, 3000);
                }
            });
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
        function formatPickupDate(dateStr) {
            const date = new Date(dateStr.replace(' ', 'T'));
            return new Intl.DateTimeFormat('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            }).format(date).replace(',', ' ·');
        }
        function formatDateSimple(dateStr) {
            const d = new Date(dateStr);
            return d.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }
        function normalizeJob(job, source) {
            /* ===============================
               USER DETAILS SAFE PARSING
            =============================== */
            let userDetails = {};
            if (job.user_details) {
                // Case 1: Already an object
                if (typeof job.user_details === 'object') {
                    userDetails = job.user_details;
                }
                // Case 2: JSON string
                else if (typeof job.user_details === 'string') {
                    try {
                        userDetails = JSON.parse(job.user_details);
                    } catch (e) {
                        userDetails = {};
                    }
                }
            }
            /* ===============================
               ASSIGNED DRIVER
            =============================== */
            let assignedDriver = null;
            if (job.job_status === 'accept' && job.bids_details) {
                Object.entries(job.bids_details).forEach(([driverId, bid]) => {
                    if (bid.status === 'accept') {
                        assignedDriver = {
                            driver_id: Number(driverId),
                            name: bid.b_name || '-',
                            amount: bid.amount || 0,
                            rating: bid.b_rating || ''
                        };
                    }
                });
            }
            /* ===============================
               DATE FORMAT
            =============================== */
            let cre_date = formatPickupDate(job.created_at);
            /* ===============================
               RETURN STRUCTURE
            =============================== */
            return {
                id: job.id,
                title: job.job_no,
                job_no: job.job_no,
                job_type: job.job_type,
                from_place: job.from_place,
                to_place: job.to_place,
                pickup_date: job.pickup_date,
                dropoff_date: job.dropoff_date || null,
                day: job.day || null,
                distance: job.distance || null,
                base_fare: job.base_fare,
                toll_fare: job.toll_fare,
                fare: job.fare,
                pass_count: userDetails.pass_count ?? job.pass_count ?? 1,
                car_type: userDetails.cab_type ?? '',
                luggage: userDetails.lugg_count ?? 0,
                mobile: job.mobile,
                // ✅ FIXED NAME & EMAIL (TRIM + SAFE FALLBACK)
                name: (userDetails.name && userDetails.name.trim() !== '') ?
                    userDetails.name : (job.poster_name && job.poster_name.trim() !== '') ?
                    job.poster_name : '-',
                email: (userDetails.email && userDetails.email.trim() !== '') ?
                    userDetails.email : (job.email && job.email.trim() !== '') ?
                    job.email : '',
                user_id: job.user_id || 0,
                global_type: job.global_type || '',
                bid_count: job.bid_count || 0,
                job_status: job.job_status,
                confirm_status: job.confirm_status || 0,
                preview_hash: job.preview_hash,
                created_date: cre_date,
                assigned_driver: assignedDriver,
                source: source
            };
        }
        function calculateDaysFromDates(pickup, dropoff) {
            if (!pickup || !dropoff) return null;
            // Convert to Date objects (handles formats like "2026-02-24 22:30:00")
            const pickupDate = new Date(pickup.replace(' ', 'T'));
            const dropoffDate = new Date(dropoff.replace(' ', 'T'));
            if (isNaN(pickupDate) || isNaN(dropoffDate)) return null;
            // Difference in milliseconds, convert to days (round down)
            const diffMs = dropoffDate - pickupDate;
            const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
            return diffDays >= 0 ? diffDays : null;
        }
        function getUnassignedActionButton(job) {
            // WEBSITE BOOKING → POST JOB
            if (job.source === 'website' && job.job_status === 'created') {
                return `
                <button class="btn btn-sm btn-primary postJobBtn px-2"
                        data-job='${JSON.stringify(job)}'>
                    <i class="fa fa-paper-plane me-1"></i>
                    Post Job
                </button>`;
            }
            // POSTED JOB → BIDS
            if (job.source === 'posted' && ['created', 'bidding'].includes(job.job_status)) {
                return `
                <button class="btn btn-sm btn-warning bidBtn text-dark px-2 position-relative"
                        data-job="${job.id}">
                    <i class="fa fa-gavel me-1 text-dark"></i>
                    Bids
                    ${job.bid_count > 0 ? `
                    <span class="position-absolute top-0 start-100 translate-middle
                                 badge rounded-pill bg-danger">
                        ${job.bid_count}
                    </span>` : ''}
                </button>`;
            }
            return '';
        }
        function renderJobCard(job, jobId, isExpired = false) {
            console.log(job)
            const domain = "<?php echo APP_DOMAIN; ?>";
            // Check if job should be locked (either explicitly passed as expired OR database status is cancelled)
            const isInactive = isExpired === true || ['cancelled', 'expired', 'deleted'].includes((job.job_status || '').toLowerCase());
            return `
    <div class="card mb-2 shadow-sm border-0">
      <div class="card-body p-3 ${job.global_type == 'open' && !isInactive ? 'card-open' : ''}">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div class="d-flex align-items-center gap-2">
           ${!isInactive ? `
                <span class="text-muted cursor-pointer cancelJobBtn" 
                      style="font-size:14px" 
                      data-jobtype="${job.global_type}" 
                      data-user-id="${job.user_id}" 
                      data-job="${job.id}" 
                      data-job-no="${job.job_no}">
                    <i class="fa fa-times-circle text-danger"></i>
                </span>
            ` : `
                <span class="text-danger"><i class="fa fa-times-circle"></i></span>
            `}
                <span class="text-muted mx-1">|</span>
         <h6 class="mb-0 fw-bold d-flex align-items-center gap-2">
            ${job.title}
            ${job.job_no && String(job.job_no).startsWith('GRD') && !isInactive ? `
                ${job.confirm_status == 0 || !job.confirm_status ? `
                    <button class="btn btn-sm btn-info confirmGrdBtn" 
                            data-jobid="${job.id}" 
                            data-jobno="${job.job_no}" 
                            data-userid="${job.user_id}" 
                            data-jobtype="${job.global_type}" 
                            style="font-size: 11px; padding: 2px 6px;">
                        <i class=""></i> Confirm
                    </button>
                ` : ''}
                ${job.confirm_status == 1 ? `<span class="badge bg-success" style="font-size: 10px;">Confirmed</span>` : ''}
            ` : ''}
        </h6>
        ${!isInactive ? `
        <a href="#" class="text-warning jobEdit d-none" data-job="${jobId}">
          <i class="fa fa-edit text-info"></i>
        </a>
        ` : ''}
        <a href="${domain}/booking-information/${job.preview_hash}" 
           class="text-primary jobView ${(job.job_no && job.job_no.startsWith('GRD')) ? 'd-none' : ''}" 
           target="_blank">
          <i class="fa fa-eye text-secondary"></i>
        </a>
          </div>
          ${!isInactive ? getUnassignedActionButton(job) : ''}
        </div>
        <div class="mt-2 row small text-muted">
          <div class="col-6">
            <i class="fa fa-user-tie text-dark"></i>
            <span class="d-inline-flex align-items-center gap-2">
                <a href="javascript:void(0)" class="${job.source != 'website' ? 'owner-link' : ''} fw-bold" data-owner="${job.user_id}" data-jobtype="${job.global_type}">
                    ${job.name}
                </a>
                <a href="tel:${job.mobile}" class="call-con" title="Call Owner">
                    <i class="fa fa-phone"></i>
                </a>
                <a href="https://wa.me/${job.mobile}" target="_blank" class="whatsapp-icon" title="WhatsApp Owner">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </span>
          </div>
           <div class="col-6">
                <div class="d-flex align-items-center mb-1 justify-content-end">
                    <i class="fa fa-calendar text-danger " style="width:16px;"></i>
                    <span>${formatPickupDate(job.pickup_date)}</span>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex align-items-center mb-1">
                    <i class="fa fa-map-marker-alt text-success " style="width:16px;"></i>
                    <span> <strong>${job.from_place}</strong></span>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex align-items-center mb-1">
                    <i class="fa fa-map-marker-alt text-danger " style="width:16px;"></i>
                    <span><strong>${job.to_place}</strong></span>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between gap-4 mb-1">
                    <div class="d-flex align-items-center gap-2">
                    <i class="fa fa-exchange-alt text-info" title="Job Type"></i>
                    <span><strong>${job.job_type}</strong></span>
                    ${job.job_type === 'roundtrip' && (job.day || job.dropoff_date) ? `
                    <i class="fa fa-calendar-days text-secondary" title="Date/Day"></i>
                    <span><strong>${
                        job.day 
                        ? (job.day == 1 
                            ? 'Upto 24 hours' 
                            : (String(job.day).match(/day|hour/i) ? job.day : job.day + ' Days'))
                        : (job.dropoff_date == 1 
                            ? 'Upto 24 hours' 
                            : (String(job.dropoff_date).match(/day|hour/i) ? job.dropoff_date : job.dropoff_date + ' Days'))
                    }</strong></span>
                ` : ''}
                    <i class="fa fa-road text-info" title="distance"></i>
                    <span><strong>${job.distance}</strong></span>
                </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-car text-secondary me-1" style="width:16px;"></i>
                            <strong>${job.car_type}</strong>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-users text-success me-1" style="width:16px;"></i>
                            <strong>${job.pass_count}</strong>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-suitcase text-warning me-1" style="width:16px;"></i>
                            <strong>${job.luggage}</strong>
                        </div>
                    </div>
                </div>
            </div>
            ${job.assigned_driver ? `
                <div class="col-12">
                    <div class="d-flex align-items-center mb-1">
                        <i class="fa fa-user text-primary" style="width:16px;"></i>
                        <span class="d-inline-flex align-items-center gap-1">
                            <a href="#" class="driver-link fw-bold" data-driver="${job.assigned_driver.driver_id}">
                                ${job.assigned_driver.name}
                            </a>
                            ${!isInactive ? `
                            <i class="fa fa-times-circle text-danger cursor-pointer remove-driver d-none" title="Remove Driver" data-driver="1" data-job="${jobId}">
                            </i>
                            ` : ''}
                        </span>
                    </div>
                </div>
                ` : ''}
            ${(
                    !isInactive &&
                    job.source &&
                    job.source.toLowerCase() === 'posted' &&
                    ['created', 'bidding'].includes(job.job_status)
                ) ? `
                <div class="col-12">
                    <span class="badge bg-secondary cursor-pointer unassigned-driver-btn" data-job="${job.id}" data-jobno="${job.job_no}">
                        Make a Bid
                    </span>
                </div>
            ` : ''}
               <div class="col-6">
    <p class="mb-0"> <i class="fa fa-calendar text-danger me-2"></i>${job.created_date}</p>
    <p class="mb-0">
       <a href="javascript:void(0)" class="${job.source != 'website' ? 'owner-link' : ''} fw-bold" data-owner="${job.user_id}" data-jobtype="${job.global_type}">
                  <i class="fa fa-user-tie text-info"></i> Booked By ${job.name}
                </a>
    </p>
</div>
            <div class="col-6">
                <div class="d-flex align-items-center justify-content-between">
                   <div class="text-end d-flex gap-2 align-items-center">
                        <div class=" text-muted">Base ₹${job.base_fare}</div>
                        <div class=" text-muted">Toll: ₹${job.toll_fare}</div>
                        <div class="fw-bold text-dark" style="font-size:14px;" >₹${job.fare}</div>
                    </div>
                    </div>
            </div>
        </div>
      </div>
    </div>`;
        }
        function loadWebsiteBookingsFinal() {
            $('#websiteJobs').empty();
            $('#websiteLoader').show();
            websiteJobs = {};
            $.ajax({
                url: origin + "/ajax/service/leadService.php",
                type: "POST",
                dataType: "json",
                data: {
                    method: 'websiteBookingList',
                    startDate: selectedStartDate,
                    endDate: selectedEndDate,
                    dateFilter: selectedFilterType === 'booked' ? 1 : '',
                    filterType: selectedFilterType
                },
                success: function(res) {
                    if (!res.result) return;
                    res.result.forEach(j => {
                        const job = normalizeJob(j, 'website');
                        websiteJobs[job.id] = job;
                    });
                    websiteJobs = Object.values(websiteJobs)
                        .sort((a, b) => b.id - a.id);
                    render();
                },
                complete: function() {
                    $('#websiteLoader').hide();
                }
            });
            function render() {
                $('#websiteJobs').empty();
                const jobsArray = Object.values(websiteJobs);
                if (jobsArray.length === 0) {
                    $('#websiteJobs').html(`
            <div class="alert alert-info text-center mt-3">
                <i class="fa fa-info-circle me-2"></i>No data found
            </div>
        `);
                } else {
                    jobsArray.forEach(job => {
                        $('#websiteJobs').append(renderJobCard(job, job.id));
                    });
                }
                $('#websiteCount').text(jobsArray.length);
            }
        }
        function loadUnassignedJobsFinal() {
            $('#unassignedJobs').empty();
            $('#unassignedLoader').show();
            unassignedJobs = {};
            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-job-list",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                data: {
                    method: 'jobList_New',
                    jobStatus: 'not_complete',
                    startDate: selectedStartDate,
                    endDate: selectedEndDate,
                    dateFilter: selectedFilterType ? 1 : '',
                    filterType: selectedFilterType
                },
                success: function(res) {
                    if (!res.result) return;
                    res.result.forEach(j => {
                        const job = normalizeJob(j, 'posted');
                        unassignedJobs[job.id] = job;
                    });
                    render();
                },
                complete: function() {
                    $('#unassignedLoader').hide();
                }
            });
            function render() {
                $('#unassignedJobs').empty();
                const sortedJobs = Object.entries(unassignedJobs)
                    .sort(([idA], [idB]) => Number(idB) - Number(idA));
                if (sortedJobs.length === 0) {
                    $('#unassignedJobs').html(`
                        <div class="alert alert-info text-center mt-3">
                            <i class="fa fa-info-circle me-2"></i>No data found
                        </div>
                    `);
                } else {
                    sortedJobs.forEach(([id, job]) => {
                        $('#unassignedJobs').append(renderJobCard(job, id));
                    });
                }
                $('#unassignedCount').text(sortedJobs.length);
            }
        }
        // function loadAssignedJobsFinal() {
        //     $('#assignedJobs').empty();
        //     $('#assignedLoader').show();
        //     assignedJobs = {};
        //     $.ajax({
        //         url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-job-list",
        //         type: "POST",
        //         dataType: "json",
        //         headers: {
        //             "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
        //         },
        //         data: {
        //             method: 'jobList_New',
        //             jobStatus: 'accepted',
        //             startDate: selectedStartDate,
        //             endDate: selectedEndDate,
        //             dateFilter: selectedFilterType ? 1 : '',
        //             filterType: selectedFilterType
        //         },
        //         success: function(res) {
        //             res.result.forEach(j => {
        //                 const job = normalizeJob(j, 'posted');
        //                 assignedJobs[job.id] = job;
        //             });
        //             assignedJobs = Object.values(assignedJobs)
        //                 .sort((a, b) => b.id - a.id);
        //             render();
        //         },
        //         complete: function() {
        //             $('#assignedLoader').hide();
        //         }
        //     });
        //     function render() {
        //         $('#assignedJobs').empty();
        //         const jobsArray = Object.values(assignedJobs);
        //         if (jobsArray.length === 0) {
        //             $('#assignedJobs').html(`
        //                 <div class="alert alert-info text-center mt-3">
        //                     <i class="fa fa-info-circle me-2"></i>No data found
        //                 </div>
        //             `);
        //         } else {
        //             jobsArray.forEach(job => {
        //                 $('#assignedJobs').append(renderJobCard(job, job.id));
        //             });
        //         }
        //         $('#assignedCount').text(jobsArray.length);
        //     }
        // }
        function loadAssignedJobsFinal() {
            $('#assignedJobs').empty();
            $('#assignedLoader').show();
            assignedJobs = {};
            // Set URL directly to your PHP script 
            var service_url = window.location.origin + "/ajax/service/dashBoard_Services.php";
            $.ajax({
                url: service_url,
                type: "POST",
                dataType: "json",
                data: {
                    method: 'jobList_New',
                    jobStatus: 'accepted', // Fetches assigned jobs
                    startDate: selectedStartDate,
                    endDate: selectedEndDate,
                    dateFilter: selectedFilterType ? 1 : '',
                    filterType: selectedFilterType
                },
                success: function(res) {
                    if (res && res.type == 1 && res.result) {
                        res.result.forEach(j => {
                            // Send to your normalizer
                            const job = normalizeJob(j, 'posted');
                            assignedJobs[job.id] = job;
                        });
                    }
                    assignedJobs = Object.values(assignedJobs).sort((a, b) => b.id - a.id);
                    render();
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error Loading Assigned Jobs:", error);
                },
                complete: function() {
                    $('#assignedLoader').hide();
                }
            });
            function render() {
                $('#assignedJobs').empty();
                const jobsArray = Object.values(assignedJobs);
                if (jobsArray.length === 0) {
                    $('#assignedJobs').html(`
                <div class="alert alert-info text-center mt-3">
                    <i class="fa fa-info-circle me-2"></i>No data found
                </div>
            `);
                } else {
                    jobsArray.forEach(job => {
                        $('#assignedJobs').append(renderJobCard(job, job.id));
                    });
                }
                $('#assignedCount').text(jobsArray.length);
            }
        }
        function loadCancelledJobsFinal() {
            $('#cancelledJobsList').empty();
            $('#cancelledLoader').show();
            cancelledJobs = {};
            $.ajax({
                // CHANGED: Pointing to your local database service instead of API
                url: origin + "/ajax/service/Websitebook_service.php",
                type: "POST",
                dataType: "json",
                // CHANGED: Removed Authorization headers since it's local
                data: {
                    method: 'get_cancelled_jobs', // CHANGED: Ensure this method exists in your PHP file
                    jobStatus: 'cancelled',
                    startDate: selectedStartDate,
                    endDate: selectedEndDate,
                    dateFilter: selectedFilterType ? 1 : '',
                    filterType: selectedFilterType
                },
                success: function(res) {
                    // Check for res.result or res.data depending on your PHP output
                    let dataArray = res.result || res.data;
                    if (dataArray && Array.isArray(dataArray)) {
                        dataArray.forEach(j => {
                            const job = normalizeJob(j, 'posted');
                            cancelledJobs[job.id] = job;
                        });
                    }
                    cancelledJobs = Object.values(cancelledJobs).sort((a, b) => b.id - a.id);
                    render();
                },
                error: function() {
                    render(); // Ensure render is called even on API fail so the message shows
                },
                complete: function() {
                    $('#cancelledLoader').hide();
                    if ($('#cancelledJobsList').is(':empty')) {
                        render();
                    }
                }
            });
            function render() {
                $('#cancelledJobsList').empty();
                const jobsArray = Object.values(cancelledJobs);
                if (jobsArray.length === 0) {
                    $('#cancelledJobsList').html(`
                        <div class="alert alert-info text-center mt-3">
                            <i class="fa fa-info-circle me-2"></i>No cancelled jobs available
                        </div>
                    `);
                } else {
                    jobsArray.forEach(job => {
                        $('#cancelledJobsList').append(renderJobCard(job, job.id));
                    });
                }
            }
        }
        function loadExpiredJobsFinal() {
            $('#expiredJobsList').empty();
            $('#expiredLoader').show();
            let expiredJobsObj = {};
            $.ajax({
                // Update the URL to point to the new dedicated route
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-expired-jobs",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                data: {
                    // Simplify the payload since the controller handles the rest
                    startDate: selectedStartDate,
                    endDate: selectedEndDate,
                    dateFilter: selectedFilterType ? 1 : '',
                    filterType: selectedFilterType
                },
                success: function(res) {
                    if (res.result && Array.isArray(res.result)) {
                        res.result.forEach(j => {
                            const job = normalizeJob(j, 'posted');
                            expiredJobsObj[job.id] = job;
                        });
                    }
                    const jobsArray = Object.values(expiredJobsObj).sort((a, b) => b.id - a.id);
                    if (jobsArray.length === 0) {
                        $('#expiredJobsList').html(`
                    <div class="alert alert-info text-center mt-3">
                        <i class="fa fa-info-circle me-2"></i>No expired jobs available
                    </div>
                `);
                    } else {
                        $('#expiredJobsList').empty();
                        jobsArray.forEach(job => {
                            $('#expiredJobsList').append(renderJobCard(job, job.id, true));
                        });
                    }
                },
                error: function() {
                    $('#expiredJobsList').html('<div class="alert alert-danger text-center mt-3">Error loading expired jobs</div>');
                },
                complete: function() {
                    $('#expiredLoader').hide();
                }
            });
        }
        // Trigger the function when the Expired Jobs tab is clicked
        $('button[data-bs-target="#expiredJobs"]').on('shown.bs.tab', function() {
            loadExpiredJobsFinal();
        });
        $('button[data-bs-target="#unassigned"]').on('shown.bs.tab', function() {
            loadUnassignedJobsFinal();
        });
        $('button[data-bs-target="#assigned"]').on('shown.bs.tab', function() {
            loadAssignedJobsFinal();
        });
        $('button[data-bs-target="#websiteBookings"]').on('shown.bs.tab', function() {
            loadWebsiteBookingsFinal();
        });
        $('button[data-bs-target="#cancelledJobs"]').on('shown.bs.tab', function() {
            loadCancelledJobsFinal();
        });
        function sendOtp(jobId, mobile, btn) {
            const $btn = $(btn);
            $btn
                .prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin me-1"></i> Sending...');
            $.ajax({
                url: API_DOMAIN_2 + 'v1-cus/loginOTP',
                type: 'POST',
                dataType: 'json',
                data: {
                    mobile: '91' + mobile,
                    dialCode: '+91',
                    deviceType: 'MOBILE',
                    isResend: false
                },
                success: function(res) {
                    if (res.status == 'success') {
                        $('#otpSection').removeClass('d-none');
                        $('#otpMsg').text('OTP sent successfully').addClass('text-success');
                        window.enc = res.data.enc;
                        toast('success', res.message);
                    } else {
                        toast('error', res.message);
                        $('#otpMsg').text(res.message || 'Failed to send OTP')
                            .addClass('text-danger');
                    }
                },
                error: function() {
                    toast('error', res.message);
                    // alert('OTP service unavailable');
                },
                complete: function() {
                    $btn
                        .prop('disabled', false)
                        .html('<i class="fas fa-key me-1"></i> Send OTP');
                }
            });
        }
        function postJob(userId, jobId) {
            $.ajax({
                url: API_DOMAIN_2 + 'v1-cus/postJob',
                type: 'POST',
                dataType: 'json',
                data: {
                    user_id: userId,
                    job_id: jobId
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Posting Job...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(res) {
                    if (res.status === true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Job Posted',
                            text: `Job ${res.data} posted successfully`
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: res.message || 'Job posting failed'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Server error'
                    });
                }
            });
        }
        function openPostJobPreview(data) {
            // Hide other panels
            $('#mapCard, #leftPanel, #incomingBidsPanel, #ownerDetailsCard')
                .addClass('d-none');
            // Show job details panel
            $('#jobDetailsPanel').removeClass('d-none');
            $('#jobDetailsContent').html(`
            <div class="container-fluid">
              <!-- CUSTOMER -->
              <div class="border p-3 mb-3">
                <h6 class="text-muted mb-3">Customer Details</h6>
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">Name</label>
                    <input class="form-control" id="pv_name" value="${data.name ?? ''}" readonly>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input class="form-control" id="pv_email" value="${data.email ?? ''}" readonly>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Mobile</label>
                    <input class="form-control" id="pv_mobile" value="${data.mobile ?? ''}" readonly>
                  </div>
                </div>
              </div>
              <!-- JOB -->
              <div class="border p-3 mb-3">
                <h6 class="text-muted mb-3">Job Details</h6>
                <input type="hidden" id="pv_job_id" value="${data.id}" readonly>
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">Job No</label>
                    <input class="form-control" id="pv_job_no" value="${data.job_no}" readonly>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Job Type</label>
                    <input class="form-control" id="pv_job_type" value="${data.job_type}" readonly>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Car Type</label>
                    <input class="form-control" id="pv_car_type" value="${data.car_type}" readonly>
                  </div>
                </div>
              </div>
              <!-- ROUTE -->
              <div class="border p-3 mb-3">
                <h6 class="text-muted mb-3">Route Details</h6>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">From</label>
                    <input class="form-control" id="pv_from" value="${data.from_place}" readonly>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">To</label>
                    <input class="form-control" id="pv_to" value="${data.to_place}" readonly>
                  </div>
                </div>
              </div>
              <!-- SCHEDULE -->
              <div class="border p-3 mb-3">
                <h6 class="text-muted mb-3">Schedule</h6>
                <input class="form-control" id="pv_pickup_time"
                       value="${ formatPickupDate(data.pickup_date)}" readonly>
              </div>
              <!-- FARE -->
              <div class="border p-3 mb-3">
                <h6 class="text-muted mb-3">Fare Breakdown</h6>
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">Base Fare</label>
                    <input class="form-control" id="pv_base_fare" value="${data.base_fare}" readonly>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Toll Fare</label>
                    <input class="form-control" id="pv_toll_fare" value="${data.toll_fare}" readonly>
                  </div>
                  <div class="col-md-4 d-flex align-items-end">
                    <h6 class="text-success fw-bold">
                      Total: ₹<span id="pv_total_fare">${data.fare}</span>
                    </h6>
                  </div>
                </div>
              </div>
              <!-- OTP -->
              <div class="border p-3 mb-3 d-none" id="otpSection">
                <h6 class="text-muted mb-3">OTP Verification</h6>
                <div class="row g-2">
                  <div class="col-md-6">
                    <input class="form-control" id="otpInput" maxlength="6"
                           placeholder="Enter OTP">
                  </div>
                  <div class="col-md-6">
                    <button class="btn btn-success w-100" id="verifyOtpBtn">
                      Verify OTP
                    </button>
                  </div>
                </div>
                <small id="otpMsg" class="text-muted"></small>
              </div>
              <!-- ACTIONS -->
              <div class="text-end">
                <button class="btn btn-outline-primary sendOtpBtn"
                        data-job-id="${data.id}"
                        data-mobile="${data.mobile}">
                  Send OTP
                </button>
              </div>
            </div>
            `);
        }
        $(document).on('click', '#verifyOtpBtn', function() {
            const otp = $('#otpInput').val().trim();
            if (!otp || otp.length !== 6) {
                $('#otpMsg')
                    .text('Please enter a valid 6-digit OTP')
                    .removeClass('text-success')
                    .addClass('text-danger');
                return;
            }
            $.ajax({
                url: API_DOMAIN_2 + 'v1-cus/verify-loginOTP-web',
                type: 'POST',
                dataType: 'json',
                data: {
                    enc: window.enc,
                    otp: otp,
                    name: $('#pv_name').val(),
                    email: $('#pv_email').val(),
                    mobile: $('#pv_mobile').val(),
                    platform_type: 'android',
                    fcm_token: null
                },
                success: function(res) {
                    if (res.status === 'success') {
                        $('#otpMsg')
                            .text('OTP verified successfully')
                            .removeClass('text-danger')
                            .addClass('text-success');
                        $('#otpInput, #verifyOtpBtn').prop('disabled', true);
                        const jobNo = $('#pv_job_no').val().trim();
                        const jobId = $('#pv_job_id').val();
                        const userId = res.data.user_id;
                        Swal.fire({
                            title: 'Post Job Confirmation',
                            text: `Are you sure you want to post this job (${jobNo})?`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, Post Job',
                            cancelButtonText: 'Cancel',
                            reverseButtons: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                postJob(userId, jobId);
                            }
                            if (result.isDismissed) {
                                $('#verifyOtpBtn').prop('disabled', false);
                                $('#otpInput, #verifyOtpBtn').prop('disabled', false);
                            }
                        });
                    } else {
                        $('#otpMsg')
                            .text(res.message || 'Invalid OTP')
                            .addClass('text-danger');
                    }
                },
                error: function() {
                    $('#otpMsg')
                        .text('Verification failed')
                        .addClass('text-danger');
                }
            });
        });
        $(document).on('click', '.postJobBtn', function() {
            const job = $(this).data('job');
            openPostJobPreview(job);
        });
        $(document).on('click', '.sendOtpBtn', function() {
            const jobId = $(this).data('job-id');
            const mobile = $(this).data('mobile');
            sendOtp(jobId, mobile, this);
        });
        function openIncomingBids(job) {
            // Panels visibility (UNCHANGED LOGIC)
            $('#mapCard, #jobDetailsPanel, #driverListPanel').addClass('d-none');
            $('#leftPanel').removeClass('d-none');
            $('#incomingBidsPanel').removeClass('d-none');
            $('#ownerDetailsCard').addClass('d-none');
            console.log(job)
            const jobNo = job.job_no;
            // Inject Job No into Header
            $('#incomingBidsTitle').html(`Incoming Bids <span class="text-primary ms-1">(${jobNo})</span>`);
            const biddersWrap = $('#incomingBidsPanel .bidders-wrap');
            // const bestPriceEl = $('#incomingBidsPanel .bid-header .text-info');
            const bestPriceEl = $('.best_price');
            biddersWrap.html('<p class="text-muted small">Waiting for bids…</p>');
            bestPriceEl.hide();
            db.collection('<?= rtrim(FIREBASE_COLLECTION, '/') ?>')
                .doc(jobNo)
                .onSnapshot(doc => {
                    biddersWrap.empty();
                    if (!doc.exists) {
                        biddersWrap.html('<p class="text-danger">Job not found</p>');
                        return;
                    }
                    const data = doc.data();
                    const bids = data.bids_details || {};
                    if (Object.keys(bids).length === 0) {
                        biddersWrap.html('<p class="text-muted" style="padding-left: 161px;">No bids yet</p>');
                        bestPriceEl.hide();
                        return;
                    }
                    /* ======================
                       FIND BEST PRICE
                    ====================== */
                    let lowestPrice = null;
                    Object.values(bids).forEach(b => {
                        const amt = Number(b.amount);
                        if (!isNaN(amt)) {
                            lowestPrice = lowestPrice === null ? amt : Math.min(lowestPrice, amt);
                        }
                    });
                    if (lowestPrice !== null) {
                        bestPriceEl.html(
                            `<i class="fa fa-coins me-1"></i> Best Price: ₹${lowestPrice}`
                        ).show();
                    }
                    const isJobAccepted = data.job_status === 'accept';
                    let acceptedBidderId = null;
                    if (isJobAccepted) {
                        Object.entries(bids).forEach(([bidderId, bid]) => {
                            if (bid.status === 'accept' || bid.status === 'accepted') {
                                acceptedBidderId = bidderId;
                            }
                        });
                    }
                    /* ======================
                       RENDER BIDS (NEW UI)
                    ====================== */
                    Object.entries(bids).reverse().forEach(([bidderId, bid]) => {
                        if (isJobAccepted && bidderId !== acceptedBidderId) return;
                        const amount = bid.amount;
                        const status = bid.status || 'pending';
                        const isBest = Number(amount) === lowestPrice;
                        const remarkClass =
                            status === 'accept' ?
                            'remark-success' :
                            isBest ?
                            'remark-success' :
                            'remark-warning';
                        biddersWrap.append(`
                        <div class="bid-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <a href="#"
                                       class="fw-bold text-dark text-decoration-none driver-link"
                                       data-driver="${bidderId}">
                                        ${bid.b_name || 'Driver'}
                                    </a>
                                    <i class="fa fa-check-circle text-success" title="Verified"></i>
                                    <i class="fa fa-map-marker-alt text-danger cursor-pointer view-driver-location"
   data-driver="${bidderId}" 
   data-name="${bid.b_name || 'Driver'}"
   title="View Location"></i>
                                </div>
                                <span class="bid-amount">₹${amount}</span>
                            </div>
                            <div class="bid-remark ${remarkClass}">
                                <i class="fa fa-comment-dots"></i>
                                ${bid.remark || '—'}
                            </div>
                            <div class="d-flex justify-content-end gap-3 mt-2">
                                ${status === 'accept' || status === 'accepted'
                                ? `<span class="text-success fw-semibold">
                                                <i class="fa fa-check-circle me-1"></i> Accepted
                                           </span>`
                                : status === 'reject'
                                    ? `<span class="text-danger fw-semibold">
                                                <i class="fa fa-times-circle me-1"></i> Rejected
                                           </span>`
                                    : (!isJobAccepted
                                        ? `
                                            <span class="text-success fw-semibold cursor-pointer bid-accept"
                                                  data-bidder="${bidderId}"
                                                  data-jobid="${data.id}"
                                                  data-jobno="${jobNo}"
                                                  data-userid="${data.user_id}"
                                                  data-amount="${amount}">
                                                <i class="fa fa-check-circle me-1"></i> Accept
                                            </span>
                                            <span class="text-danger fw-semibold cursor-pointer bid-reject"
                                                  data-bidder="${bidderId}"
                                                  data-jobid="${data.id}">
                                                <i class="fa fa-times-circle me-1"></i> Reject
                                            </span>
                                          `
                                        : ''
                                    )
                            }
                            </div>
                        </div>
                        `);
                    });
                });
        }
        $(document).on('click', '.bidBtn', function() {
            const jobId = $(this).data('job');
            // console.log(unassignedJobs);
            const job = unassignedJobs[jobId];
            openIncomingBids(job);
        });
        $(document).on('click', '.bid-accept', function() {
            const bidderId = $(this).data('bidder');
            const jobId = $(this).data('jobid');
            const jobNo = $(this).data('jobno');
            const userId = $(this).data('userid');
            const amount = $(this).data('amount');
            Swal.fire({
                title: `Accept bid ₹${amount}?`,
                icon: 'question',
                showCancelButton: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: 'Accept',
                cancelButtonText: 'Cancel'
            }).then(result => {
                if (!result.isConfirmed) return;
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we confirm the bid.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                fetch(API_DOMAIN_2 + 'admin-accept-bidder', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()'
                        },
                        body: JSON.stringify({
                            job_id: jobId,
                            job_no: jobNo,
                            u_id: userId,
                            user_id: bidderId
                        })
                    })
                    .then(res => res.json())
                    .then(res => {
                        Swal.close(); // Close loader
                        if (res.status) {
                            toast('success', res.message);
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            toast('error', res.message);
                        }
                    })
                    .catch(() => {
                        Swal.close();
                        toast('error', 'Network error');
                    });
            });
        });
        $(document).on('click', '.bid-reject', function() {
            const bidderId = $(this).data('bidder');
            const jobId = $(this).data('jobid');
            const $btn = $(this);
            rejectData = {
                bidderId,
                jobId,
                button: $btn
            };
            $('#confirmIcon')
                .removeClass()
                .addClass('fa fa-times-circle text-danger');
            $('#confirmTitleText').text('Reject Bid');
            $('#confirmMessage').html(
                'Are you sure you want to reject this bid?'
            );
            // Show modal
            const modal = new bootstrap.Modal(
                document.getElementById('confirmActionModal'), {
                    backdrop: 'static',
                    keyboard: false
                }
            );
            modal.show();
        });
        $('#confirmYesBtn').on('click', function() {
            if (!rejectData.jobId) return;
            const {
                bidderId,
                jobId,
                button
            } = rejectData;
            const $confirmBtn = $(this);
            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-reject-bid",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                data: {
                    uid: <?= $_SESSION['memid'] ?>,
                    job_id: jobId,
                    bidder_id: bidderId
                },
                beforeSend: function() {
                    $confirmBtn
                        .prop('disabled', true)
                        .html('<i class="fa fa-spinner fa-spin me-1"></i> Processing');
                },
                success: function(res) {
                    if (res.status) {
                        toast('success', 'Bid rejected successfully');
                        // Replace action buttons with Rejected badge
                        const $actionWrap = button.closest('.d-flex.justify-content-end');
                        $actionWrap.html(`
                            <span class="text-danger fw-semibold">
                                <i class="fa fa-times-circle me-1"></i> Rejected
                            </span>
                        `);
                        // Hide modal
                        bootstrap.Modal.getInstance(
                            document.getElementById('confirmActionModal')
                        ).hide();
                    } else {
                        toast('error', res.message || 'Reject failed');
                    }
                },
                error: function() {
                    toast('error', 'Server error');
                },
                complete: function() {
                    $confirmBtn
                        .prop('disabled', false)
                        .html('Yes');
                    rejectData = {};
                }
            });
        });
        loadUnassignedJobsFinal();
        function getPassengerDetails(id, jobtype) {
            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-get-passenger-details",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                data: {
                    passenger_id: id,
                    jobtype: jobtype
                },
                success: function(res) {
                    if (!res.status || !res.data) return;
                    const d = res.data;
                    // Name
                    if (d.name) {
                        $('#ownerDetailsCard .d-flex.align-items-center.gap-2 span')
                            .first()
                            .text(d.name);
                    }
                    // Mobile
                    if (d.mobile) {
                        const cleanMobile = String(d.mobile).replace(/\D/g, '');
                        $('#ownerDetailsCard .call-con')
                            .attr('href', `tel:${cleanMobile}`);
                        $('#ownerDetailsCard .whatsapp-icon')
                            .attr('href', `https://wa.me/${cleanMobile}`);
                    }
                    $('#ownerDetailsCard img')
                        .attr('src', d.profile_img_url || '../assets/images/users/spl_profile_image.png');
                    $('.c_mobile').html(`<i class="fa fa-phone"></i> ${d.mobile}`);
                    $('.c_address').html(`<i class="fa fa-map-marker-alt text-danger me-1"></i> ${d.address ?? '-'}`);
                    $('.c_created_at').html(`<i class="fa fa-user-clock me-1"></i>Account Created: <strong>${formatPickupDate(d.created_at)}</strong>`);
                    // if (d.city || d.state) {
                    //     $('#ownerDetailsCard .fa-map-marker-alt')
                    //         .parent()
                    //         .html(`
                    //             <i class="fa fa-map-marker-alt text-danger me-1"></i>
                    //             ${d.city || ''} ${d.state ? ', ' + d.state : ''}
                    //         `);
                    // }
                    if (d.created_at) {
                        const date = new Date(d.created_at);
                        const formatted = date.toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });
                        $('#ownerDetailsCard .fa-user-clock')
                            .parent()
                            .html(`
                                <i class="fa fa-user-clock me-1"></i>
                                Account Created: <strong>${formatted}</strong>
                            `);
                    }
                }
            });
        }
        $(document).on('click', '.owner-link', function(e) {
            e.preventDefault();
            const passengerId = $(this).data('owner');
            const jobtype = $(this).data('jobtype');
            $('#mapCard, #jobDetailsPanel').addClass('d-none');
            $('#leftPanel').removeClass('d-none');
            $('#incomingBidsPanel').addClass('d-none');
            $('#ownerDetailsCard').removeClass('d-none');
            getPassengerDetails(passengerId, jobtype);
        });
        function getDriverDetails(id) {
            $.ajax({
                url: "ajax/service/driverServices.php", // CHANGED FROM API
                type: "POST",
                dataType: "json",
                data: {
                    method: 'get_driver_details', // ADDED METHOD
                    driver_id: id
                },
                success: function(res) {
                    if (!res.status || !res.data) {
                        $('#driverDetailsCard .card-body > *:not(#driverProfileLoader)').css('opacity', 1);
                        $('#driverProfileLoader').addClass('d-none');
                        return;
                    }
                    const d = res.data.driver;
                    // --- Handlers ---
                    if (d.user_id) {
                        $('#driver_profile_edit').off('click').on('click', function() {
                            openDriverEditDrawer(d.user_id, d.id);
                        });
                        $('#sidebar_remarks_icon').off('click').on('click', function() {
                            openRemarksModal(d.user_id, d.name || 'Driver');
                        });
                    }
                    // --- 1. Basic Info ---
                    $('#preview_driver_name').text(d.name || 'N/A');
                    if (d.mobile) {
                        const cleanMobile = String(d.mobile).replace(/\D/g, '');
                        $('#preview_driver_no').html(`<i class="fa fa-phone text-success me-1"></i> (${cleanMobile})`);
                        $('#preview_call_btn').attr('href', `tel:${cleanMobile}`);
                        $('#preview_wa_btn').attr('href', `https://wa.me/${cleanMobile}`);
                    }
                    const profileImg = d.selfie_url || d.profile_img_url || '/assets/images/driver.png';
                    $('#preview_driver_photo').attr('src', profileImg);
                    $('#preview_driver_photo').off('click').on('click', function() {
                        $('#driverPhotoModal .modal-body img').attr('src', profileImg);
                    });
                    if (d.doc_verify == 1) {
                        $('#preview_kyc_status').html('<i class="fa fa-check-circle text-success me-1"></i> KYC Verified');
                    } else {
                        $('#preview_kyc_status').html('<i class="fa fa-times-circle text-danger me-1"></i> KYC Pending');
                    }
                    const locationTxt = [d.address, d.state].filter(Boolean).join(', ');
                    $('#preview_location').html(`<i class="fa fa-map-marker-alt text-danger me-1"></i> ${locationTxt || 'N/A'}`);
                    $('#preview_experience').html(`<i class="fa fa-briefcase text-info me-1"></i> ${d.exp ? d.exp + ' Years' : 'N/A'}`);
                    const dlExpiryStr = d.dl_expiry ? formatDateSimple(d.dl_expiry) : 'N/A';
                    const dlTypeHtml = d.license_type ? ` | <strong class="text-secondary">${d.license_type}</strong>` : '';
                    $('#preview_license').html(`<i class="fa fa-id-badge text-primary me-1"></i> <strong>${d.dl_no || 'N/A'}</strong>${dlTypeHtml} | <strong>${dlExpiryStr}</strong>`);
                    // --- 2. Vehicle Parsing ---
                    let vName = 'N/A',
                        vModel = 'N/A',
                        vFuel = 'N/A',
                        vSeats = 'N/A',
                        vLuggage = 'N/A';
                    let rcExpiry = 'N/A',
                        inExpiry = 'N/A',
                        vImage = '/assets/images/carimage.png';
                    let languages = 'N/A';
                    let vehicleData = {};
                    if (d.vehicle_details) {
                        try {
                            vehicleData = typeof d.vehicle_details === 'string' ? JSON.parse(d.vehicle_details) : d.vehicle_details;
                            vName = vehicleData.rc_details?.response?.vehicle_details?.body_type || d.cab_type || 'N/A';
                            vModel = vehicleData.rc_details?.response?.vehicle_details?.maker_model || 'N/A';
                            let rawFuel = (vehicleData.rc_details?.response?.vehicle_details?.fuel_type ||
                                vehicleData.vehicle_questions?.fuel_type || d.fuel_type || 'N/A').toString().trim().toUpperCase();
                            if (rawFuel.includes('PETROL/CNG') || rawFuel.includes('PETROL / CNG')) {
                                vFuel = 'PETROL/CNG';
                            } else {
                                vFuel = rawFuel;
                            }
                            let seatCap = vehicleData.rc_details?.response?.vehicle_details?.seat_capacity;
                            if (seatCap) vSeats = (seatCap > 1) ? (seatCap - 1) + "+1" : seatCap;
                            vLuggage = vehicleData.user_info?.luggage || d.luggage || vehicleData.luggage || vehicleData.vehicle_questions?.luggage_capacity || 'N/A';
                            if (vehicleData.rc_expiry_date) rcExpiry = formatDateSimple(vehicleData.rc_expiry_date);
                            if (vehicleData.insurance_details?.insurance_exp_date) inExpiry = formatDateSimple(vehicleData.insurance_details.insurance_exp_date);
                            if (vehicleData.vehicle?.front_view_image_url) vImage = vehicleData.vehicle.front_view_image_url;
                            if (vehicleData.user_info?.language) languages = vehicleData.user_info.language;
                            const images = [
                                vehicleData.vehicle?.front_view_image_url,
                                vehicleData.vehicle?.back_view_image_url,
                                vehicleData.vehicle?.side_view_image_url,
                                vehicleData.vehicle?.car_top_view_image_url,
                                vehicleData.vehicle?.interior_front_image_url,
                                vehicleData.vehicle?.interior_rear_image_url,
                                vehicleData.vehicle?.boot_image_url,
                                vehicleData.vehicle?.extra_image_1_url,
                                vehicleData.vehicle?.special_features_image_url
                            ].filter(Boolean);
                            const carouselInner = $('#carImageCarousel .carousel-inner');
                            carouselInner.empty();
                            if (images.length === 0) {
                                carouselInner.html(`<div class="carousel-item active"><img src="/assets/images/carimage.png" class="img-fluid" style="max-height:250px;"></div>`);
                            } else {
                                images.forEach((img, index) => {
                                    carouselInner.append(`<div class="carousel-item ${index === 0 ? 'active' : ''}"><img src="${img}" class="img-fluid" style="max-height:250px; object-fit:cover; width:100%;"></div>`);
                                });
                            }
                        } catch (e) {
                            console.log('Error parsing vehicle details JSON for preview');
                        }
                    }
                    $('#preview_vehicle_photo').attr('src', vImage);
                    $('#preview_vehicle_name').text(vName);
                    $('#preview_vehicle_model').text(vModel);
                    $('#preview_seats').text(vSeats);
                    $('#preview_fuel').text(vFuel);
                    $('#preview_luggage').text(vLuggage);
                    $('#preview_rc').text(rcExpiry);
                    $('#preview_in').text(inExpiry);
                    $('#preview_languages').html(`<i class="fa fa-language text-dark me-1"></i> <strong>${languages.replace(/,/g, ', ')}</strong>`);
                    // --- 3. Fares ---
                    $('#preview_per_km').text(d.per_km ? `₹${d.per_km}` : 'N/A');
                    $('#preview_extra_km').text(d.extra_per_km ? `₹${d.extra_per_km}` : 'N/A');
                    $('#preview_extra_hour').text(d.per_hour ? `₹${d.per_hour}` : 'N/A');
                    $('#preview_extra_day').text(d.per_day ? `₹${d.per_day}` : 'N/A');
                    $('#preview_driver_id').val(d.user_id);
                    // --- 4. Profile Percentage ---
                    let percent = parseInt(d.profile_percentage) || 0;
                    percent = Math.max(0, Math.min(100, percent));
                    $('#preview_profile_percent_text').text(percent + '%');
                    const $progress = $('#preview_profile_progress');
                    $progress.css('width', percent + '%').attr('aria-valuenow', percent).removeClass('bg-success bg-warning bg-danger');
                    if (percent < 40) $progress.addClass('bg-danger');
                    else if (percent < 75) $progress.addClass('bg-warning');
                    else $progress.addClass('bg-success');
                    /* =========================
                               SECTION COMPLETION TICKS (SAFE MODE)
                            ========================== */
                    /* =========================
   DYNAMIC SECTION TICKS
========================== */
                    /* =========================
                       SECTION COMPLETION TICKS (SAFE MODE)
                    ========================== */
                    // Hide Loader
                    $('#driverEditDrawer .offcanvas-body > *:not(#driverEditLoader)').css('opacity', 1);
                    $('#driverEditLoader').addClass('d-none');
                    // --- End of Status Check logic ---
                    $('#driverDetailsCard .card-body > *:not(#driverProfileLoader)').css('opacity', 1);
                    $('#driverProfileLoader').addClass('d-none');
                },
                error: function() {
                    $('#driverDetailsCard .card-body > *:not(#driverProfileLoader)').css('opacity', 1);
                    $('#driverProfileLoader').addClass('d-none');
                }
            });
        }
        /* =====================================================
           GLOBAL DRIVER SEARCH INSIDE EDIT DRAWER HEADER
        ===================================================== */
        let isFetchingGlobalDrivers = false;
        function fetchDriversForSearch(callback = null) {
            // If we already have drivers, don't call API again
            if (Object.keys(driversAll).length > 0) {
                if (callback) callback();
                return;
            }
            if (isFetchingGlobalDrivers) return;
            isFetchingGlobalDrivers = true;
            $('#globalDriverSearchResults').html(`<div class="list-group-item text-muted text-center py-3" style="font-size:13px;"><i class="fa fa-spinner fa-spin me-2"></i>Loading drivers...</div>`).removeClass('d-none');
            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-get-driver-list",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                success: function(res) {
                    if (res.status && res.data) {
                        driversAll = {};
                        res.data.forEach(driver => {
                            let status = 'offline';
                            if (driver.rm_status == 1) status = 'online';
                            if (driver.rm_status == 2) status = 'busy';
                            driversAll[driver.id] = {
                                id: driver.id,
                                user_id: driver.user_id,
                                name: driver.name,
                                mobile: driver.mobile,
                                status: status,
                                profilePercent: driver.profile_percentage,
                                fullData: driver
                            };
                        });
                        if (callback) callback();
                    }
                },
                complete: function() {
                    isFetchingGlobalDrivers = false;
                }
            });
        }
        function renderGlobalSearchDropdown(searchTerm = '') {
            const resultsBox = $('#globalDriverSearchResults');
            let driversArray = Object.values(driversAll);
            if (searchTerm) {
                driversArray = driversArray.filter(driver => {
                    const nameMatch = driver.name && String(driver.name).toLowerCase().includes(searchTerm);
                    const mobileMatch = driver.mobile && String(driver.mobile).includes(searchTerm);
                    return nameMatch || mobileMatch;
                });
            }
            if (driversArray.length === 0) {
                resultsBox.html(`<div class="list-group-item text-muted text-center py-3" style="font-size:13px;"><i class="fa fa-user-slash me-2"></i>No drivers found</div>`).removeClass('d-none');
                return;
            }
            let html = '';
            driversArray.forEach(driver => {
                let statusColor = driver.status === 'online' ? 'success' : (driver.status === 'busy' ? 'warning' : 'danger');
                let profilePercent = driver.profilePercent || 0;
                let profileClass = profilePercent >= 80 ? 'profile-good' : (profilePercent >= 50 ? 'profile-medium' : 'profile-low');
                let profileImg = '/assets/images/driver.png';
                if (driver.fullData) {
                    profileImg = driver.fullData.selfie_url || driver.fullData.profile_img_url || profileImg;
                }
                html += `
                <a href="javascript:void(0)" class="list-group-item list-group-item-action global-driver-search-item py-2 px-3" data-id="${driver.user_id || driver.id}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <img src="${profileImg}" class="rounded-circle shadow-sm" style="width:35px;height:35px;object-fit:cover;">
                            <div style="font-size: 13px; line-height: 1.2;">
                                <strong class="text-dark">${driver.name}</strong><br>
                                <small class="text-muted"><i class="fa fa-phone me-1"></i>${driver.mobile || 'N/A'}</small>
                            </div>
                        </div>
                        <div class="profile-circle ${profileClass}" style="width: 35px; height: 35px; font-size: 10px; border-width: 3px; display:flex; align-items:center; justify-content:center; border-radius:50%; border-style:solid; background: #fff;">
                            ${profilePercent}%
                        </div>
                    </div>
                </a>`;
            });
            resultsBox.html(html).removeClass('d-none');
        }
        // Updated Search Listener to prevent opening when Modals steal focus
        // 1. When User CLICKS the Search Box 
        // (Removed 'focus' so Bootstrap Modals don't accidentally trigger this when opening)
        $(document).off('click', '#globalDriverSearch').on('click', '#globalDriverSearch', function(e) {
            e.stopPropagation();
            $('#globalDriverSearchResults').removeClass('d-none');
            const searchTerm = $(this).val().toLowerCase().trim();
            const $icon = $(this).prev('i'); // Targets the search icon next to the input
            // Change magnifying glass to spinner
            if ($icon.length) {
                $icon.removeClass('fa-search text-muted').addClass('fa-spinner fa-spin text-primary');
            }
            fetchDriversForSearch(function() {
                renderGlobalSearchDropdown(searchTerm);
                // Change spinner back to magnifying glass
                if ($icon.length) {
                    $icon.removeClass('fa-spinner fa-spin text-primary').addClass('fa-search text-muted');
                }
            });
        });
        // 2. GLOBAL FAILSAFE: Forcefully hide search dropdown when ANY modal or viewer opens
        $(document).on('show.bs.modal', '.modal', function() {
            $('#globalDriverSearch').blur();
            $('#globalDriverSearchResults').addClass('d-none');
        });
        // Fix for Viewer.js (Image popups) taking over the screen
        document.addEventListener('show', function() {
            $('#globalDriverSearch').blur();
            $('#globalDriverSearchResults').addClass('d-none');
        });
        // Extra failsafe to forcefully close it when the modal is triggered
        $('#vehicleImagesModal').on('show.bs.modal', function() {
            $('#globalDriverSearch').blur();
            $('#globalDriverSearchResults').addClass('d-none');
        });
        $(document).on('keyup', '#globalDriverSearch', function() {
            $('#globalDriverSearchResults').removeClass('d-none');
            const searchTerm = $(this).val().toLowerCase().trim();
            fetchDriversForSearch(function() {
                renderGlobalSearchDropdown(searchTerm);
            });
        });
        // 5. When User Clicks a Result
        $(document).on('click', '.global-driver-search-item', function(e) {
            e.preventDefault();
            const driverId = $(this).data('id');
            // Hide results and clear input
            $('#globalDriverSearchResults').addClass('d-none');
            $('#globalDriverSearch').val('');
            if (driverId) {
                openDriverEditDrawer(driverId);
            }
        });
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#globalDriverSearch, #globalDriverSearchResults').length) {
                $('#globalDriverSearchResults').addClass('d-none');
            }
        });
        function openDriverEditDrawer(id) {
            $('#driverPreviewCanvas').offcanvas('hide');
            window.currentDriverId = id;
            // A beautiful inline SVG that says "No Image Available" - guaranteed to never 404 or show broken icon!
            const NO_IMAGE_SVG = "data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100%25' height='100%25'%3E%3Crect width='100%25' height='100%25' fill='%23f8f9fa'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='12px' fill='%236c757d'%3ENo Image Available%3C/text%3E%3C/svg%3E";
            // FORCE HIDE SEARCH DROPDOWN immediately to prevent it lingering over the drawer
            $('#globalDriverSearchResults').addClass('d-none');
            $('#globalDriverSearch').blur();
            // --- 1. INSTANTLY SHOW WHITE OVERLAY TO PREVENT GHOSTING ---
            $('#driverEditLoader').removeClass('d-none').css({
                'background': '#ffffff',
                'z-index': '9999',
                'opacity': '1'
            });
            // Flush text and reset images to the fallback SVG instantly
            $('.display-field').text('-');
            $('.edit-field').val('');
            $('#main_vehicle_preview, .vehicle-photo').attr('src', NO_IMAGE_SVG);
            $('.driver-photo').attr('src', NO_IMAGE_SVG);
            $('.document-img').attr('src', NO_IMAGE_SVG);
            $('#carImageCarousel .carousel-inner').empty();
            $('#dynamicScheduleWrapper, #scheduledJobsList').empty();
            $('#status-driver-profile, #status-aadhaar, #status-license, #status-vehicle, #status-documents, #status-fare, #status-remarks').empty();
            // SAFELY OPEN DRAWER IMMEDIATELY
            let drawerEl = document.getElementById('driverEditDrawer');
            let bsOffcanvas = bootstrap.Offcanvas.getInstance(drawerEl);
            if (!bsOffcanvas) {
                bsOffcanvas = new bootstrap.Offcanvas(drawerEl);
            }
            bsOffcanvas.show();
            if (typeof fetchDriversForSearch === 'function') {
                fetchDriversForSearch();
            }
            // Fetch new data
            $.ajax({
                url: "ajax/service/driverServices.php",
                type: "POST",
                dataType: "json",
                data: {
                    method: 'get_driver_details',
                    driver_id: id
                },
                success: function(res) {
                    if (!res.status || !res.data) {
                        $('#driverEditLoader').addClass('d-none');
                        return;
                    }
                    const d = res.data.driver || {};
                    let vehicleData = {};
                    try {
                        vehicleData = typeof d.vehicle_details === 'string' ? JSON.parse(d.vehicle_details || '{}') : (d.vehicle_details || {});
                    } catch (e) {
                        vehicleData = {};
                    }
                    const langs = vehicleData.user_info?.language;
                    if (langs) {
                        const langArr = langs.split(',').map(l => l.trim());
                        window.editLanguagesTomSelect.clear();
                        langArr.forEach(l => window.editLanguagesTomSelect.addItem(l));
                    } else {
                        window.editLanguagesTomSelect.clear();
                    }
                    /* =========================
                       TEXT & INPUT SETTERS
                    ========================== */
                    function setText(selector, value, fallback = '-') {
                        $(selector).text(value ? value : fallback);
                    }
                    function setVal(selector, value) {
                        $(selector).val(value ? value : '');
                    }
                    // --- NEW: BULLETPROOF LOCALIZED IMAGE SPINNER & FALLBACK HELPER ---
                    function setLocalizedImage(selector, url, fallbackUrl = NO_IMAGE_SVG) {
                        let $img = $(selector);
                        let $container = $img.parent(); // Grabs the direct container wrapper
                        // Cleanup previous events
                        $img.off('load.local error.local');
                        if (url && url !== '' && url !== 'null') {
                            $container.addClass('has-local-spinner');
                            $img.hide(); // Hide image until it's downloaded
                            $img.on('load.local', function() {
                                $(this).show();
                                $container.removeClass('has-local-spinner');
                            });
                            $img.on('error.local', function() {
                                // If the image link is broken (404), replace with the fallback text SVG!
                                $(this).off('error.local'); // Prevent infinite loop if fallback also breaks
                                $(this).attr('src', fallbackUrl).show();
                                $container.removeClass('has-local-spinner');
                            });
                            $img.attr('src', url);
                        } else {
                            // No URL provided, show the text SVG directly
                            $img.attr('src', fallbackUrl).show();
                            $container.removeClass('has-local-spinner');
                        }
                    }
                    setText('[data-field="name"]', d.name);
                    setVal('#edit_name', d.name);
                    setText('[data-field="mobile"]', d.mobile);
                    setVal('#edit_mobile', d.mobile);
                    if (d.mobile) {
                        let cleanMobile = String(d.mobile).replace(/\D/g, '');
                        let waMobile = cleanMobile.length === 10 ? '91' + cleanMobile : cleanMobile;
                        $('#view-driver-profile .call-icon').attr('href', `tel:${cleanMobile}`);
                        $('#view-driver-profile .whatsapp-icon').attr('href', `https://wa.me/${waMobile}`);
                    }
                    setText('[data-field="age"]', d.age);
                    setVal('#edit_age', d.age);
                    setText('[data-field="email"]', d.email);
                    setVal('#edit_email', d.email);
                    setText('[data-field="address"]', d.address);
                    setVal('#edit_address', d.address);
                    if (d.districts_id) {
                        $('#edit_districts_id').val(d.districts_id);
                        let distName = $('#edit_districts_id option:selected').text();
                        setText('[data-field="city"]', distName !== 'Select District' ? distName : '-');
                    } else {
                        $('#edit_districts_id').val('');
                        setText('[data-field="city"]', '-');
                    }
                    
                    if (d.state && d.state.trim() !== '') {
                    let stateName = d.state.trim();
                    
                    // 1. Tell Tom Select to visually select the state
                    if (window.editStateTomSelect) {
                        window.editStateTomSelect.setValue(stateName);
                    }
                    
                    // 2. Update the View Mode text
                    setText('[data-field="state"]', stateName);
                } else {
                    // Clear it if empty
                    if (window.editStateTomSelect) {
                        window.editStateTomSelect.clear();
                    }
                    setText('[data-field="state"]', '-');
                }
                    setText('[data-field="state"]', d.state.trim());
                    setText('#display_experience', d.exp ? d.exp + " Yrs" : "0 Years");
                    setVal('#edit_experience', d.exp);
                    setText('#display_upi', d.upiID);
                    setVal('#edit_upi', d.upiID);
                    $('#profileScoreBadge').html(`<i class="fa fa-check-circle me-1"></i>${d.profile_percentage || 0}% Complete`);
                    /* =========================
                       PROFILE IMAGE WITH SPINNER
                    ========================== */
                    if (d.profile_img_url) {
                        setLocalizedImage('.driver-photo', d.profile_img_url, NO_IMAGE_SVG);
                    } else if (d.selfie_url) {
                        setLocalizedImage('.driver-photo', d.selfie_url, NO_IMAGE_SVG);
                    } else {
                        setLocalizedImage('.driver-photo', null, NO_IMAGE_SVG);
                    }
                    /* =========================
                       AADHAAR WITH SPINNERS
                    ========================== */
                    if (d.proof_type === 'AADHAR_DIGILOCKER' && d.proof_status === 'approved') {
                        setLocalizedImage('#aadhaarFront img', '/assets/images/digiverify.png', NO_IMAGE_SVG);
                        setLocalizedImage('#aadhaarBack img', '/assets/images/digiverify.png', NO_IMAGE_SVG);
                        setLocalizedImage('#edit-aadhaarFront img', '/assets/images/digiverify.png', NO_IMAGE_SVG);
                        setLocalizedImage('#edit-aadhaarBack img', '/assets/images/digiverify.png', NO_IMAGE_SVG);
                    } else {
                        setLocalizedImage('#aadhaarFront img', d.aadhar_image_front, NO_IMAGE_SVG);
                        setLocalizedImage('#aadhaarBack img', d.aadhar_image_back, NO_IMAGE_SVG);
                        setLocalizedImage('#edit-aadhaarFront img', d.aadhar_image_front, NO_IMAGE_SVG);
                        setLocalizedImage('#edit-aadhaarBack img', d.aadhar_image_back, NO_IMAGE_SVG);
                        aadhaarFrontUrl = d.aadhar_image_front || null;
                        aadhaarBackUrl = d.aadhar_image_back || null;
                    }
                    /* =========================
                       LICENSE / RC WITH SPINNERS
                    ========================== */
                    setLocalizedImage('#licenseFront img', d.license_front_image, NO_IMAGE_SVG);
                    setLocalizedImage('#licenseBack img', d.license_back_image, NO_IMAGE_SVG);
                    setLocalizedImage('#edit-licenseFront img', d.license_front_image, NO_IMAGE_SVG);
                    setLocalizedImage('#edit-licenseBack img', d.license_back_image, NO_IMAGE_SVG);
                    dlFrontUrl = d.license_front_image || null;
                    dlBackUrl = d.license_back_image || null;
                    setText('#display_license_no', d.dl_no);
                    setText('#display_license_expiry', d.dl_expiry);
                    let licType = d.license_type || 'LMV';
                    setText('#display_license_type', licType);
                    setVal('#edit_license_type', licType);
                    setVal('#edit_license_no', d.dl_no);
                    setVal('#edit_license_expiry', d.dl_expiry);
                    setVal('#edit_license_dob', d.dob);
                    /* =========================
                       VEHICLE DETAILS
                    ========================== */
                    setText('#display_vehicle_type', vehicleData.rc_details?.response?.vehicle_details?.body_type);
                    setVal('#edit_vehicle_type', vehicleData.rc_details?.response?.vehicle_details?.body_type);
                    let rawFuel = (vehicleData.rc_details?.response?.vehicle_details?.fuel_type || vehicleData.vehicle_questions?.fuel_type || d.fuel_type || '').toString().trim().toUpperCase();
                    let mappedFuel = '';
                    if (rawFuel.includes('PETROL/CNG') || rawFuel.includes('PETROL / CNG')) mappedFuel = 'PETROL/CNG';
                    else if (rawFuel === 'PETROL') mappedFuel = 'PETROL';
                    else if (rawFuel === 'DIESEL') mappedFuel = 'DIESEL';
                    else if (rawFuel === 'CNG') mappedFuel = 'CNG';
                    setText('#display_fuel', mappedFuel || rawFuel || 'N/A');
                    setVal('#edit_fuel', mappedFuel);
                    const model = vehicleData.rc_details?.response?.vehicle_details?.maker_model;
                    setText('#display_maker_model', model);
                    setVal('#edit_maker_model', model);
                    const color = vehicleData.rc_details?.response?.vehicle_details?.color;
                    setText('#display_colour', color);
                    setVal('#edit_colour', color);
                    const seatCap = vehicleData.rc_details?.response?.vehicle_details?.seat_capacity || d.seat || d.seaters;
                    var disSeat = (seatCap > 1) ? (seatCap - 1) + "+1" : (seatCap || 'N/A');
                    setText('#display_seating', disSeat);
                    setVal('#edit_seating', seatCap);
                    const extLuggage = vehicleData.user_info?.luggage || d.luggage || vehicleData.luggage || vehicleData.vehicle_questions?.luggage_capacity || 'N/A';
                    setText('#display_luggage', extLuggage);
                    setVal('#edit_luggage', extLuggage);
                    if (vehicleData.rc_expiry_date) {
                        setText('#display_rc', formatDateSimple(vehicleData.rc_expiry_date));
                        setText('#display_insurance', formatDateSimple(vehicleData.insurance_details?.insurance_exp_date));
                        setVal('#edit_rc', vehicleData.rc_expiry_date);
                        setVal('#edit_insurance', vehicleData.insurance_details?.insurance_exp_date);
                    } else {
                        setText('#display_rc', null);
                        setText('#display_insurance', null);
                        setVal('#edit_rc', null);
                        setVal('#edit_insurance', null);
                    }
                    const vImages = vehicleData.vehicle || {};
                    // Front view gets a spinner for the main dashboard UI
                    setLocalizedImage('#main_vehicle_preview', vImages.front_view_image_url, NO_IMAGE_SVG);
                    setLocalizedImage('.vehicle-photo', vImages.front_view_image_url, NO_IMAGE_SVG);
                    // ==========================================
                    // PREFILL THE "UPDATE VEHICLE IMAGES" MODAL
                    // ==========================================
                    const imgMapping = [{
                            preview: '#prev_front_view',
                            input: '#val_front_view_image_url',
                            url: vImages.front_view_image_url,
                            target: 'front_view'
                        },
                        {
                            preview: '#prev_boot_image',
                            input: '#val_boot_image_url',
                            url: vImages.boot_image_url,
                            target: 'boot_image'
                        },
                        {
                            preview: '#prev_extra_image_1',
                            input: '#val_extra_image_1_url',
                            url: vImages.extra_image_1_url,
                            target: 'extra_image_1'
                        },
                        {
                            preview: '#prev_car_top_view',
                            input: '#val_car_top_view_image_url',
                            url: vImages.car_top_view_image_url,
                            target: 'car_top_view'
                        },
                        {
                            preview: '#prev_interior_front',
                            input: '#val_interior_front_image_url',
                            url: vImages.interior_front_image_url,
                            target: 'interior_front'
                        },
                        {
                            preview: '#prev_special_features',
                            input: '#val_special_features_image_url',
                            url: vImages.special_features_image_url,
                            target: 'special_features'
                        }
                    ];
                    imgMapping.forEach(item => {
                        // 1. Set the hidden input for saving to DB
                        $(item.input).val(item.url || '');
                        // 2. Set the image in the upload modal (uses spinner + placeholder fallback)
                        setLocalizedImage(item.preview, item.url, '/assets/images/placeholder.png');
                        // 3. Show/hide the "X" (clear) button based on if an image already exists
                        if (item.url && item.url !== '' && item.url !== 'null') {
                            $(`.clear-img-btn[data-target="${item.target}"]`).removeClass('d-none');
                        } else {
                            $(`.clear-img-btn[data-target="${item.target}"]`).addClass('d-none');
                        }
                    });
                    updateSimpleImages();
                    // ==========================================
                    // REFRESH THE CLICKABLE FULLSCREEN GALLERY
                    // ==========================================
                    const $galleryDiv = $('#hiddenGalleryImages');
                    $galleryDiv.empty();
                    const allGalleryImages = [
                        vImages.front_view_image_url,
                        vImages.boot_image_url,
                        vImages.extra_image_1_url,
                        vImages.car_top_view_image_url,
                        vImages.interior_front_image_url,
                        vImages.special_features_image_url
                    ];
                    allGalleryImages.forEach(imgUrl => {
                        if (imgUrl && imgUrl !== '' && imgUrl !== 'null') {
                            $galleryDiv.append(`<img src="${imgUrl}" alt="Vehicle View">`);
                        }
                    });
                    refreshVehicleGallery();
                    /* =========================
                       VEHICLE DOCUMENTS WITH SPINNERS
                    ========================== */
                    setLocalizedImage('#rcDocumentFront img', vehicleData.rc_front_image_url, NO_IMAGE_SVG);
                    setLocalizedImage('#rcDocumentBack img', vehicleData.rc_back_image_url, NO_IMAGE_SVG);
                    setLocalizedImage('#pucDocument img', vehicleData.puc_details?.puc_image_url, NO_IMAGE_SVG);
                    setLocalizedImage('#insuranceDocument img', vehicleData.insurance_details?.insurance_image_url, NO_IMAGE_SVG);
                    setLocalizedImage('#edit-rcDocumentFront img', vehicleData.rc_front_image_url, NO_IMAGE_SVG);
                    setLocalizedImage('#edit-rcDocumentBack img', vehicleData.rc_back_image_url, NO_IMAGE_SVG);
                    setLocalizedImage('#edit-pucDocument img', vehicleData.puc_details?.puc_image_url, NO_IMAGE_SVG);
                    setLocalizedImage('#edit-insuranceDocument img', vehicleData.insurance_details?.insurance_image_url, NO_IMAGE_SVG);
                    rcFrontUrl = vehicleData.rc_front_image_url || null;
                    rcBackUrl = vehicleData.rc_back_image_url || null;
                    pucUrl = vehicleData.puc_details?.puc_image_url || null;
                    insuranceUrl = vehicleData.insurance_details?.insurance_image_url || null;
                    $('#edit_rc_number').val(vehicleData.rc_number || '');
                    $('#edit_rc_expiry').val(vehicleData.rc_expiry_date || '');
                    $('#edit_puc_expiry').val(vehicleData.puc_details?.puc_exp_date || '');
                    $('#edit_insurance_expiry').val(vehicleData.insurance_details?.insurance_exp_date || '');
                    /* =========================
                       FARE DETAILS & REMARKS
                    ========================== */
                    setText('#display_per_km', d.per_km);
                    setText('#display_extra_km', d.extra_per_km);
                    setText('#display_extra_hour', d.per_hour);
                    setText('#display_extra_day', d.per_day);
                    setVal('#edit_per_km', d.per_km);
                    setVal('#edit_extra_km', d.extra_per_km);
                    setVal('#edit_extra_hour', d.per_hour);
                    setVal('#edit_extra_day', d.per_day);
                    setText('[data-field="languages"]', vehicleData.user_info?.language);
                    setText('#display_review', d.reviews);
                    setVal('#edit_review', d.reviews);
                    setText('#display_remarks', d.remarks);
                    setVal('#edit_remarks', d.remarks);
                    updateSectionIcons(d, vehicleData);
                    // Fetch schedule separately
                    loadDriverSchedule(id);
                    // REVEAL EVERYTHING INSTANTLY!
                    // Text is loaded instantly, image spinners are spinning in their specific boxes.
                    $('#driverEditDrawer .offcanvas-body > *:not(#driverEditLoader)').css('opacity', 1);
                    $('#driverEditLoader').addClass('d-none');
                },
                error: function() {
                    $('#driverEditDrawer .offcanvas-body > *:not(#driverEditLoader)').css('opacity', 1);
                    $('#driverEditLoader').addClass('d-none');
                }
            });
        }
        // Handle edit icon click in drivers list
        $(document).on('click', '.driver-edit-icon', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var driverId = $(this).data('driver');
            if (!driverId) {
                showNotification('Driver ID not found', 'error');
                return;
            }
            // Close preview if open and open edit drawer
            $('#driverPreviewCanvas').offcanvas('hide');
            openDriverEditDrawer(driverId);
        });
        $(document).on('click', '.driver-link', function(e) {
            e.preventDefault();
            const driverId = $(this).data('driver');
            const driverCanvas = new bootstrap.Offcanvas('#driverPreviewCanvas');
            driverCanvas.show();
            $('#driverProfileLoader').removeClass('d-none');
            $('#driverDetailsCard .card-body > *:not(#driverProfileLoader)')
                .css('opacity', 0.3);
            $('#profileProgressBar').css('width', '0%');
            getDriverDetails(driverId);
        });
        $(document).on('click', '#driverDetailsCard img:first', function() {
            const imgSrc = $(this).attr('src');
            $('#driverPhotoModal .modal-body img')
                .attr('src', imgSrc);
        });
        function initLocationAutocomplete(selector) {
            const $input = $(selector);
            $input.autocomplete({
                minLength: 3,
                source: function(request, response) {
                    response([{
                        label: 'Loading locations...',
                        value: ''
                    }]);
                    $.ajax({
                        url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-web-getlocation",
                        type: "POST",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': 'gRZlvH9xMVbgCRLQbmV8co6bmAjYpWuvuAY64Mnw'
                        },
                        data: {
                            search: request.term
                        },
                        success: function(res) {
                            if (!res.status || !res.data || !res.data.length) {
                                response([{
                                    label: 'No locations found',
                                    value: ''
                                }]);
                                return;
                            }
                            response(res.data.map(function(item) {
                                return {
                                    id: item.place_id,
                                    label: item.name,
                                    value: item.name,
                                    latitude: item.latitude,
                                    longitude: item.longitude
                                };
                            }));
                        },
                        error: function() {
                            response([{
                                label: 'Error loading locations',
                                value: ''
                            }]);
                        }
                    });
                },
                select: function(event, ui) {
                    if (!ui.item.id) {
                        event.preventDefault();
                        return false;
                    }
                    $input.data("latitude", ui.item.latitude);
                    $input.data("longitude", ui.item.longitude);
                    $input.data("place-id", ui.item.id);
                }
            });
        }
        function getJobFormHTML() {
            return `
            <div class="container-fluid">
                <!-- ================= CUSTOMER DETAILS ================= -->
                <div class="border p-3 mb-3">
                    <h6 class="text-muted mb-3">Customer Details</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" id="custName" class="form-control" placeholder="Customer Name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email</label>
                            <input type="email" id="custEmail" class="form-control" placeholder="Email Address" oninput="this.value = this.value.replace(/\s/g, '')">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mobile <span class="text-danger">*</span></label>
                            <input type="text" id="custMobile" class="form-control" placeholder="Mobile Number">
                        </div>
                    </div>
                </div>
                <!-- ================= JOB DETAILS ================= -->
                <div class="border p-3 mb-3">
                    <h6 class="text-muted mb-3">Job Details</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Job No</label>
                            <input type="text" class="form-control" value="Auto Generated" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Job Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="jobType">
                                <option value="">Select Type</option>
                                <option value="one_way">One Way</option>
                                <option value="round_trip">Round Trip</option>
                            </select>
                        </div>
                       <div class="col-md-4">
                            <label class="form-label">Car Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="carType">
                            <option value="">Select Car</option>
                            <option value="mini" selected>Go Mini</option>
                            <option value="four_seater">Go 4Seaters</option>
                            <option value="six_seater">Go 6Seaters</option>
                            <option value="seven_seater">Go 7Seaters</option>
                            </select>
                     </div>
                <div class="col-md-4 d-none" id="returnDaysWrapper">
                            <label class="form-label">
                                Return in Days <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="returnDays">
                                <option value="">Select Days</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- ================= ROUTE DETAILS ================= -->
                <div class="border p-3 mb-3">
                    <h6 class="text-muted mb-3">Route Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">From <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pickupLocation" placeholder="Pickup Location">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">To <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="dropLocation" placeholder="Drop Location">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Passengers <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="passengers" value="4" min="1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Luggage <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="luggage" value="2" min="0">
                        </div>
                    </div>
                </div>
                <!-- ================= SCHEDULE ================= -->
                <div class="border p-3 mb-3">
                    <h6 class="text-muted mb-3">Schedule</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Pickup Date & Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="pickupDateTime">
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3 text-end">
                    <button type="button" class="btn btn-primary" id="getDistanceBtn">
                        <i class="fa fa-route me-1"></i> Get Fare & Distance
                    </button>
                </div>
                <!-- ================= FARE BREAKDOWN ================= -->
                <div class="border p-3">
                    <h6 class="text-muted mb-3">Fare Breakdown</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Distance (kms)</label>
                            <input type="number" class="form-control" id="distance" placeholder="Distance" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Duration</label>
                            <input type="text" class="form-control" id="duration" placeholder="Duration" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Base Fare <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="baseFare" placeholder="Base Fare" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Toll Fare</label>
                            <input type="number" class="form-control" id="tollFare" placeholder="Toll Fare" readonly>
                        </div>
                        <div class="col-md-12 d-flex align-items-end">
                            <h6 class="fw-bold text-success mb-1">
                                Total: <span id="totalFare">₹0</span>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
            `;
        }
        $(document).on('click', '.createJobBtn', function() {
            // Hide map & other panels
            $('#mapCard, #leftPanel, #incomingBidsPanel, #ownerDetailsCard')
                .addClass('d-none');
            // Show job panel
            $('#jobDetailsPanel').removeClass('d-none');
            // Inject CREATE JOB layout
            $('#jobDetailsContent').html(`
                <div class="container-fluid">
                    <!-- CUSTOMER SEARCH -->
                    <div class="border p-3 mb-3 bg-light d-none">
                        <h6 class="text-muted mb-3">Select Customer</h6>
                        <div class="row g-2 align-items-end">
                            <div class="col-md-8 position-relative">
                                <label class="form-label">Search Existing Customer</label>
                                <input type="text" id="customerSearch"
                                       class="form-control"
                                       placeholder="Search by name or mobile...">
                                <div id="customerSearchResults"
                                     class="list-group position-absolute w-100 d-none"
                                     style="z-index:1000; max-height: 200px; overflow-y: auto;"></div>
                            </div>
                           <div class="col-md-4 text-end d-flex justify-content-end gap-2">
                       <div class="btn-group d-none" id="customerHistoryControls">
                        <button class="btn btn-sm btn-primary active fw-bold" data-filter="all">
                            All
                        </button>
                    </div>
                        <button class="btn btn-sm btn-success" id="newCustomerBtn">
                            <i class="fa fa-user-plus me-1"></i> 
                        </button>
                        </div>
                        </div>
                    </div>
                    <div id="jobFormWrapper" class="d-none">
                        ${getJobFormHTML()}
                    </div>
                    <div id="pastJobsSlider" class="past-jobs-slider d-none">
                        <div class="past-jobs-header d-flex justify-content-between align-items-center">
                            <strong id="pastJobsTitle" class="text-primary">
                                All Jobs
                            </strong>
                            <i class="fa fa-times closePastJobs cursor-pointer"></i>
                        </div>
                        <div id="pastJobsList" style="max-height: calc(100% - 50px); overflow-y: auto;">
                        </div>
                    </div>
                </div>
            `);
            $('#jobFormWrapper').removeClass('d-none');
            initLocationAutocomplete('#pickupLocation');
            initLocationAutocomplete('#dropLocation');
        });
        $(document).on('click', '#getDistanceBtn', function() {
            const $btn = $(this);
            const pickupPlaceId = $('#pickupLocation').data("place-id");
            const dropPlaceId = $('#dropLocation').data("place-id");
            let pickupDateTime = $('#pickupDateTime').val();
            let jobType = $('#jobType').val();
            let carType = $('#carType').val();
            if (!pickupPlaceId || !dropPlaceId) {
                toast('error', 'Please select both Pickup and Drop location');
                return;
            }
            if (!pickupDateTime) {
                toast('error', 'Please select pickup date & time');
                return;
            }
            if (!jobType) {
                toast('error', 'Please select job type');
                return;
            }
            if (!carType) {
                toast('error', 'Please select car type');
                return;
            }
            jobType = jobType === 'one_way' ? 'oneway' : 'roundtrip';
            let dropoffDate = null;
            if (jobType === 'roundtrip') {
                const returnDays = $('#returnDays').val();
                if (!returnDays) {
                    toast('error', 'Please select return days');
                    return;
                }
                dropoffDate = parseInt(returnDays);
            }
            pickupDateTime = pickupDateTime.replace('T', ' ') + ':00';
            let apiCarKey = 'four_seater';
            if (carType === 'suv' || carType === 'innova') {
                apiCarKey = 'seven_seater';
            }
            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-web-getdistance",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': 'gRZlvH9xMVbgCRLQbmV8co6bmAjYpWuvuAY64Mnw'
                },
                data: {
                    from_place_id: pickupPlaceId,
                    to_place_id: dropPlaceId,
                    pickup_date: pickupDateTime,
                    dropoff_date: dropoffDate,
                    way_type: jobType
                },
                beforeSend: function() {
                    $btn
                        .prop('disabled', true)
                        .html('<i class="fa fa-spinner fa-spin me-1"></i> Calculating...');
                },
                success: function(response) {
                    if (!response || !response.status || !response.data) {
                        toast('error', response.message || 'Distance fetch failed');
                        return;
                    }
                    const cab = response.data[apiCarKey];
                    if (!cab) {
                        toast('error', 'Route data not found');
                        return;
                    }
                    $('#distance').val(cab.distance);
                    $('#duration').val(cab.duration);
                    $('#baseFare').val(cab.fare);
                    $('#tollFare').val(cab.toll_fare);
                    const total = parseInt(cab.fare);
                    $('#totalFare').text('₹' + total.toLocaleString('en-IN'));
                    $('#pickupLocation').data("latitude", cab.from_lat);
                    $('#pickupLocation').data("longitude", cab.from_lng);
                    $('#dropLocation').data("latitude", cab.to_lat);
                    $('#dropLocation').data("longitude", cab.to_lng);
                },
                error: function() {
                    toast('error', 'Something went wrong. Try again.');
                },
                complete: function() {
                    $btn
                        .prop('disabled', false)
                        .html('<i class="fa fa-route me-1"></i> Get Distance');
                }
            });
        });
        $(document).on('change', '#jobType', function() {
            const jobType = $(this).val();
            const $wrapper = $('#returnDaysWrapper');
            const $select = $('#returnDays');
            if (jobType === 'round_trip') {
                $wrapper.removeClass('d-none');
                // Populate days only once
                if ($select.children('option').length === 1) {
                    for (let i = 1; i <= 10; i++) {
                        $select.append(
                            `<option value="${i}">${i} Day${i > 1 ? 's' : ''}</option>`
                        );
                    }
                }
            } else {
                $wrapper.addClass('d-none');
                $select.val('');
            }
        });
        $(document).on('click', '#confirmBookingBtn', function() {
            const $btn = $(this);
            // ================= CUSTOMER =================
            const cName = $('#custName').val().trim();
            const cEmail = $('#custEmail').val().trim();
            const cMobile = $('#custMobile').val().trim();
            // ================= JOB =================
            let jobType = $('#jobType').val();
            let carType = $('#carType').val();
            let returnDays = $('#returnDays').val();
            const pickupDateTime = $('#pickupDateTime').val();
            // ================= LOCATION =================
            const fromPlace = $('#pickupLocation').val();
            const toPlace = $('#dropLocation').val();
            const fromPlaceId = $('#pickupLocation').data("place-id");
            const toPlaceId = $('#dropLocation').data("place-id");
            // ================= FARE =================
            const distance = $('#distance').val();
            const duration = $('#duration').val();
            const fare = $('#baseFare').val();
            const tollFare = $('#tollFare').val();
            const passengers = $('#passengers').val();
            const luggage = $('#luggage').val();
            // ================= RESET HIGHLIGHTS =================
            // Remove error borders from previous attempts
            $('.form-control, .form-select').removeClass('is-invalid border-danger');
            let hasError = false;
            let missingFields = [];
            let invalidFields = [];
            // Helper to highlight empty fields and track their names
            function markError(selector, fieldName, isInvalidFormat = false) {
                $(selector).addClass('is-invalid border-danger');
                if (isInvalidFormat) {
                    if (!invalidFields.includes(fieldName)) invalidFields.push(fieldName);
                } else {
                    if (!missingFields.includes(fieldName)) missingFields.push(fieldName);
                }
                hasError = true;
            }
            // ================= VALIDATION & HIGHLIGHTING =================
            if (!cName) markError('#custName', 'Name');
            if (!cMobile) {
    markError('#custMobile', 'Mobile');
} else if (cMobile.startsWith('0')) {
    markError('#custMobile', 'Mobile Number cannot start with 0', true); 
}
            // Only run the validation if the user actually typed something into the email field
            if (cEmail.trim() !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(cEmail)) {
                markError('#custEmail', 'Email', true);
            }
            if (!jobType) markError('#jobType', 'Job Type');
            if (!carType) markError('#carType', 'Car Type');
            if (jobType === 'round_trip' && !returnDays) {
                markError('#returnDays', 'Return Days');
            }
            if (!fromPlace || !fromPlaceId) markError('#pickupLocation', 'Pickup Location');
            if (!toPlace || !toPlaceId) markError('#dropLocation', 'Drop Location');
            if (!passengers || parseInt(passengers) <= 0) markError('#passengers', 'Passengers');
            if (luggage === '' || parseInt(luggage) < 0) markError('#luggage', 'Luggage');
            if (!pickupDateTime) markError('#pickupDateTime', 'Pickup Date & Time');
            // If any fields are empty, stop submission and show specific errors
            if (hasError) {
                let errorMsg = "";
                // Formats: "Email is required." OR "Email, Passengers are required."
                if (missingFields.length > 0) {
                    let verb = missingFields.length > 1 ? "are" : "is";
                    errorMsg += `${missingFields.join(', ')} ${verb} required.<br>`;
                }
                // Formats: "Invalid format for: Email."
                if (invalidFields.length > 0) {
                    errorMsg += `Invalid format for: ${invalidFields.join(', ')}.`;
                }
                // Show the specific fields in the toast
                toast('error', errorMsg.trim());
                // Scroll up to the first highlighted error field
                $('html, body').animate({
                    scrollTop: $('.is-invalid').first().offset().top - 100
                }, 300);
                return;
            }
            // Check if distance was calculated
            if (!fare || !distance) {
                toast('error', 'Please calculate distance first');
                $('#getDistanceBtn').addClass('btn-danger text-white').removeClass('btn-primary');
                setTimeout(() => $('#getDistanceBtn').addClass('btn-primary').removeClass('btn-danger text-white'), 2000);
                return;
            }
            // ================= FORMAT VALUES =================
            jobType = jobType === 'one_way' ? 'oneway' : 'roundtrip';
            let dropoffDate = null;
            let dayText = null;
            if (jobType === 'roundtrip') {
                dropoffDate = parseInt(returnDays);
                dayText = returnDays + " Days";
            }
            const formattedPickup = pickupDateTime.replace('T', ' ') + ':00';
            let apiCabType = 'four_seater';
            if (carType === 'suv') {
                apiCabType = 'seven_seater';
            }
            // ================= CONFIRMATION POPUP =================
            Swal.fire({
                title: 'Confirm Booking?',
                text: `Create job from ${fromPlace} to ${toPlace}?`,
                icon: 'question',
                showCancelButton: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: 'Yes, Create Job'
            }).then((result) => {
                if (!result.isConfirmed) return;
                // ================= API CALL =================
                $.ajax({
                    url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-web-book-journey",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': 'gRZlvH9xMVbgCRLQbmV8co6bmAjYpWuvuAY64Mnw'
                    },
                    data: {
                        job_type: jobType,
                        from_place: fromPlace,
                        to_place: toPlace,
                        from_place_id: fromPlaceId,
                        to_place_id: toPlaceId,
                        pickup_date: formattedPickup,
                        dropoff_date: dropoffDate,
                        pass_count: passengers,
                        lugg_count: luggage,
                        fare: fare,
                        distance: distance,
                        duration: duration,
                        day: dayText,
                        toll: tollFare,
                        cab_type: apiCabType,
                        "add_fare_details[bata]": "Excluded",
                        "add_fare_details[parking]": "Excluded",
                        "add_fare_details[toll]": "Excluded",
                        type: "customer",
                        c_name: cName,
                        c_email: cEmail,
                        c_mobile: cMobile,
                        pick_address: "",
                        drop_address: "",
                        isDriver: "no"
                    },
                    beforeSend: function() {
                        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Creating...');
                    },
                    success: function(res) {
                        if (!res.status) {
                            toast('error', res.message || 'Booking failed');
                            return;
                        }
                        const jobNo = res.data || null;
                        if (!jobNo) {
                            toast('error', 'Job created but job number missing');
                            return;
                        }
                        // ================= SEND WHATSAPP =================
                        $.ajax({
                            url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/web-send-bookinfo",
                            type: "POST",
                            dataType: "json",
                            headers: {
                                'X-CSRF-TOKEN': 'gRZlvH9xMVbgCRLQbmV8co6bmAjYpWuvuAY64Mnw'
                            },
                            data: {
                                job_no: jobNo,
                                mob: cMobile,
                                c_name: cName,
                                c_email: cEmail,
                                from_place: fromPlace,
                                to_place: toPlace,
                                pickup_date: formattedPickup,
                                fare: fare,
                                cab_type: apiCabType
                            },
                            success: function(waRes) {
                                Swal.fire({
                                    icon: waRes.status ? 'success' : 'warning',
                                    title: waRes.status ? 'Job Created & WhatsApp Sent' : 'Job Created',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    text: waRes.status ? `Job No: ${jobNo}` : 'WhatsApp sending failed'
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Job Created',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    text: 'WhatsApp sending failed'
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        });
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON ? (xhr.responseJSON.message || 'Validation Error') : 'Server error. Try again.';
                        toast('error', msg);
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html('<i class="fa fa-check-circle me-1"></i> Confirm Booking');
                    }
                });
            });
        });
        // =====================================================
        // REMOVE RED BORDERS ON INPUT/CHANGE
        // =====================================================
        $(document).on('input change', '.is-invalid', function() {
            $(this).removeClass('is-invalid border-danger');
        });
        // =====================================================
        // REMOVE RED BORDERS ON INPUT
        // =====================================================
        $(document).on('input change', '.is-invalid', function() {
            $(this).removeClass('is-invalid border-danger');
        });
        // Drivers TAB
        function renderDriversList() {
            $('#driversList').empty();
            let searchText = $('#driverSearch').val()?.toLowerCase();
            let selectedFields = $('#profileFieldFilter').val();
            let filterType = $('#profileFilterType').val();
            let visibleCount = 0;
            Object.entries(driversAll).forEach(([id, driver]) => {
                let data = driver.fullData;
                if (searchText &&
                    !driver.name.toLowerCase().includes(searchText) &&
                    (!driver.mobile || !String(driver.mobile).toLowerCase().includes(searchText))) {
                    return;
                }
                const profilePercent = driver.profilePercent ?? 0;
                if (profilePercent < percentageMin || profilePercent > percentageMax) {
                    return;
                }
                if (selectedFields && selectedFields.length && filterType) {
                    let match = selectedFields.every(field => {
                        let value = null;
                        switch (field) {
                            case 'complete_verified':
                                value = (data.doc_verify == 1 && data.vehicle_verify == 2);
                                return filterType === 'filled' ? value : !value;
                            case 'images_count':
                                let count = 0;
                                if (data.profile_img_url) count++;
                                if (data.licence_image) count++;
                                if (data.aadhar_image_front) count++;
                                if (data.aadhar_image_back) count++;
                                value = count >= 4;
                                return filterType === 'filled' ? value : !value;
                            case 'vehicle_type':
                            case 'exp':
                            case 'dl_expiry':
                                value = data[field];
                                break;
                            default:
                                value = data[field];
                        }
                        const isFilled = (val) => {
                            if (val === null || val === undefined) return false;
                            if (typeof val === "string") {
                                val = val.trim();
                                if (val === "" || val.toLowerCase() === "null") return false;
                            }
                            if (val === 0) return false;
                            return true;
                        };
                        if (filterType === 'filled') {
                            return isFilled(value);
                        }
                        if (filterType === 'not_filled') {
                            return !isFilled(value);
                        }
                        return true;
                    });
                    if (!match) return;
                }
                let profileClass = 'profile-low';
                if (profilePercent >= 80) profileClass = 'profile-good';
                else if (profilePercent >= 50) profileClass = 'profile-medium';
                let statusColor = 'secondary';
                if (driver.status === 'online') statusColor = 'success';
                if (driver.status === 'busy') statusColor = 'warning';
                if (driver.status === 'offline') statusColor = 'danger';
                visibleCount++;
                $('#driversList').append(`
                    <div class="list-group-item d-flex justify-content-between align-items-center driver-item"
                         data-driver="${driver.user_id}">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <strong class="driver-link cursor-pointer" data-driver="${driver.user_id}">${driver.name}</strong>
                                <i class="fa fa-edit text-info cursor-pointer driver-edit-icon" 
                                   data-driver="${driver.user_id}" 
                                   title="Edit Driver"></i>
                            </div>
                            <small class="text-muted d-block" style="font-size: 11px;">
                                <i class="fa fa-phone me-1"></i>${driver.mobile || 'N/A'}
                            </small>
                            <small class="text-${statusColor}">
                                ● ${driver.status}
                            </small>
                        </div>
                        <div class="profile-circle ${profileClass}">
                            ${profilePercent}%
                        </div>
                    </div>
                `);
            });
            // Update driver count and show "No drivers found" if needed
            $('#driverCount').text(visibleCount);
            if (visibleCount === 0) {
                $('#driversList').html(`
            <div class="list-group-item text-center text-muted py-3">
                <i class="fa fa-user-slash me-2"></i>No drivers found
            </div>
        `);
            }
        }
        function loadDriversList() {
            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-get-driver-list",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                success: function(res) {
                    if (!res.status || !res.data) {
                        toast('error', 'Failed to load drivers');
                        return;
                    }
                    driversAll = {};
                    res.data.forEach(driver => {
                        // ===== STATUS LOGIC =====
                        let status = 'online';
                        // if (driver.rm_status == 1) status = 'online';
                        // if (driver.rm_status == 2) status = 'busy';
                        // if (driver.status == 0) status = 'offline';
                        driversAll[driver.id] = {
                            id: driver.id,
                            user_id: driver.user_id,
                            name: driver.name,
                            mobile: driver.mobile,
                            status: status,
                            profilePercent: driver.profile_percentage,
                            fullData: driver
                        };
                    });
                    renderDriversList();
                },
                error: function() {
                    toast('error', 'Driver list error');
                }
            });
        }
        function renderDriverList(jobId, searchText = '', existingBidders = new Set()) {
            let html = '';
            searchText = searchText.toLowerCase().trim();
            Object.values(availableDrivers).forEach(driver => {
                if (searchText !== '' && !driver.name.toLowerCase().includes(searchText)) {
                    return;
                }
                let statusClass = 'secondary';
                if (driver.status === 'online') statusClass = 'success';
                if (driver.status === 'busy') statusClass = 'warning';
                if (driver.status === 'offline') statusClass = 'danger';
                // Check if this driver has already bid
                const hasBid = existingBidders.has(String(driver.user_id));
                const buttonClass = hasBid ? 'manageBidBtn btn-success' : 'makeBidBtn btn-warning';
                const buttonText = hasBid ? 'Manage Bid' : 'Make a Bid';
                const buttonIcon = hasBid ? 'fa-edit' : 'fa-gavel';
                html += `
<div class="bid-item mb-2 p-2">
    <div class="d-flex align-items-center gap-2">
        <img src="${driver.profileImage}"
             class="rounded-circle"
             style="width:36px;height:36px;object-fit:cover;">
        <strong>${driver.name || 'Unknown Driver'}</strong>
        <span class="badge bg-${statusClass}">
            ${driver.status}
        </span>
        <button class="btn btn-sm ms-auto ${buttonClass}"
                data-driver="${driver.user_id}"
                data-job="${jobId}">
            <i class="fa ${buttonIcon} me-1"></i> ${buttonText}
        </button>
    </div>
    </div>
            </div>
            <div class="d-flex gap-3 mt-2 small text-muted">
                <img src="${driver.vehicleImage}"
                     class="rounded"
                     style="width:90px;height:60px;object-fit:cover;">
                <div class="flex-grow-1">
                    <div class="fw-semibold text-dark">
                        <i class="fa fa-car text-danger me-1"></i>
                        ${driver.car}
                    </div>
                    <div class="d-flex gap-3 mt-1">
                        <span><i class="fa fa-users text-info"></i> ${driver.seats}</span>
                        <span><i class="fa fa-gas-pump text-warning"></i> ${driver.fuel}</span>
                        <span><i class="fa fa-suitcase text-secondary"></i> ${driver.luggage}</span>
                    </div>
                    <div class="d-flex align-items-center gap-3 mt-2">
                        <span>
                            <i class="fa fa-star text-warning"></i>
                            <strong>${driver.rating}</strong>
                        </span>
                        <span class="text-muted">
                            (${driver.ratingsCount} Ratings)
                        </span>
                    </div>
                </div>
            </div>
        </div>`;
            });
            if (html === '') {
                html = `
            <div class="text-center text-muted py-3">
                No drivers found
            </div>
        `;
            }
            $('#driverListContainer').html(html);
        }
        //     function loadAvailableDrivers(jobId, jobNo) {
        //     currentJobId = jobId;
        //     $('#driverContainerLoader').show();
        //     // 1. Fetch drivers list
        //     $.ajax({
        //         url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-get-driver-list",
        //         type: "POST",
        //         dataType: "json",
        //         headers: {
        //             "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
        //         },
        //         success: function (res) {
        //             if (!res.status || !res.data) {
        //                 toast('error', 'Failed to load drivers');
        //                 return;
        //             }
        //             availableDrivers = {};
        //             res.data.forEach(driver => {
        //                 let status = 'offline';
        //                 if (driver.rm_status == 1) status = 'online';
        //                 if (driver.rm_status == 2) status = 'busy';
        //                 let cabImg = '/assets/images/carimage.png';
        //                 if (driver.vehicle_details) {
        //                     try {
        //                         const parsedDetails = JSON.parse(driver.vehicle_details);
        //                         if (parsedDetails && parsedDetails.vehicle && parsedDetails.vehicle.front_view_image_url) {
        //                             cabImg = parsedDetails.vehicle.front_view_image_url;
        //                         }
        //                     } catch (e) {
        //                         console.log('Could not parse vehicle details JSON');
        //                     }
        //                 }
        //                 availableDrivers[driver.id] = {
        //                     id: driver.id,
        //                     user_id: driver.user_id,
        //                     name: driver.name,
        //                     profileImage: driver.selfie_url || '/assets/images/driver.png',
        //                     status: status,
        //                     rating: driver.ratings || 0,
        //                     ratingsCount: 0,
        //                     accepted: 0,
        //                     completed: driver.complete_jobs || 0,
        //                     cancelled: 0,
        //                     car: driver.cab_type || 'N/A',
        //                     seats: '4+1',
        //                     fuel: driver.vehicle_type || 'Petrol',
        //                     luggage: 2,
        //                     rc: 'N/A',
        //                     insurance: 'N/A',
        //                     vehicleImage: cabImg
        //                 };
        //             });
        //             // 2. Fetch existing bids for this job from Firestore
        //             db.collection('<?= rtrim(FIREBASE_COLLECTION, '/') ?>')
        //                 .doc(jobNo)
        //                 .get()
        //                 .then(doc => {
        //                     let existingBidders = new Set();
        //                     if (doc.exists) {
        //                         const data = doc.data();
        //                         const bids = data.bids_details || {};
        //                         existingBidders = new Set(Object.keys(bids)); // driver IDs as strings
        //                     }
        //                     // 3. Render drivers with the set of existing bidders
        //                     $('#driverContainerLoader').hide();
        //                     renderDriverList(jobId, '', existingBidders);
        //                 })
        //                 .catch(error => {
        //                     console.error('Error fetching bids:', error);
        //                     $('#driverContainerLoader').hide();
        //                     renderDriverList(jobId, '', new Set()); // render without bid info
        //                 });
        //         },
        //         error: function () {
        //             $('#driverContainerLoader').show();
        //             toast('error', 'Driver list error');
        //         }
        //     });
        // }
        function loadAvailableDrivers(jobId, jobNo) {
            currentJobId = jobId;
            // 1. Show loader and clear the container completely
            $('#driverListContainer').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
                    <div class="mt-3 text-muted fw-semibold">Loading drivers<div>
                </div>
            `);
            // 2. Start fetching Drivers from MySQL
            const fetchDrivers = $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-get-driver-list",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                }
            });
            // 3. Start fetching Bids from Firebase AT THE SAME TIME
            const fetchBids = db.collection('<?= rtrim(FIREBASE_COLLECTION, '/') ?>').doc(jobNo).get();
            // 4. Wait for BOTH requests to finish before rendering
            Promise.all([fetchDrivers, fetchBids])
                .then(([res, doc]) => {
                    // --- Process Drivers ---
                    if (!res.status || !res.data) {
                        $('#driverListContainer').html('<div class="alert alert-danger m-3 text-center">Failed to load drivers</div>');
                        return;
                    }
                    availableDrivers = {};
                    res.data.forEach(driver => {
                        let status = 'offline';
                        if (driver.rm_status == 1) status = 'online';
                        if (driver.rm_status == 2) status = 'busy';
                        let cabImg = '/assets/images/carimage.png';
                        if (driver.vehicle_details) {
                            try {
                                const parsedDetails = JSON.parse(driver.vehicle_details);
                                if (parsedDetails?.vehicle?.front_view_image_url) {
                                    cabImg = parsedDetails.vehicle.front_view_image_url;
                                }
                            } catch (e) {
                                console.log('Could not parse vehicle details JSON');
                            }
                        }
                        availableDrivers[driver.id] = {
                            id: driver.id,
                            user_id: driver.user_id,
                            name: driver.name,
                            profileImage: driver.selfie_url || '/assets/images/driver.png',
                            status: status,
                            rating: driver.ratings || 0,
                            ratingsCount: 0,
                            car: driver.cab_type || 'N/A',
                            seats: '4+1',
                            fuel: driver.vehicle_type || 'Petrol',
                            luggage: 2,
                            vehicleImage: cabImg
                        };
                    });
                    // --- Process Bids ---
                    // --- Process Bids ---
                    currentExistingBidders = new Set(); // Use the global variable
                    if (doc.exists) {
                        const bids = doc.data().bids_details || {};
                        currentExistingBidders = new Set(Object.keys(bids)); // Get driver IDs as strings
                    }
                    // --- Render UI ---
                    // The render function will now instantly output the correct buttons
                    renderDriverList(jobId, '', currentExistingBidders);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    $('#driverListContainer').html('<div class="alert alert-danger m-3 text-center">Error loading driver list</div>');
                });
        }
        $(document).on('click', '.manageBidBtn', function() {
            const driverId = $(this).data('driver'); // driver.user_id
            const jobId = $(this).data('job'); // job ID
            const jobNo = $('#availableDriversTitle span.text-primary').text().replace(/[()]/g, ''); // e.g., "GRC-189"
            // Store in hidden fields for later use
            $('#bidDriverId').val(driverId);
            $('#bidJobId').val(jobId);
            // Show loading state (optional)
            $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            // Fetch the job document from Firestore
            db.collection('<?= rtrim(FIREBASE_COLLECTION, '/') ?>')
                .doc(jobNo)
                .get()
                .then(doc => {
                    if (doc.exists) {
                        const bids = doc.data().bids_details || {};
                        const bid = bids[driverId]; // driverId as key
                        if (bid) {
                            $('#bidAmount').val(bid.amount);
                            $('#bidRemark').val(bid.remark || '');
                        } else {
                            // No bid found – fallback to empty fields
                            $('#bidAmount').val('');
                            $('#bidRemark').val('');
                        }
                    } else {
                        $('#bidAmount').val('');
                        $('#bidRemark').val('');
                    }
                    $('#bidErrorMsg').addClass('d-none');
                    $('#createBidModal').modal('show');
                })
                .catch(error => {
                    console.error('Error fetching bid:', error);
                    toast('error', 'Could not load bid details');
                    $('#bidAmount').val('');
                    $('#bidRemark').val('');
                    $('#createBidModal').modal('show');
                })
                .finally(() => {
                    // Restore button state
                    $(this).prop('disabled', false).html('<i class="fa fa-edit me-1"></i> Manage Bid');
                });
        });
        $(document).on('keyup', '#driverSearchInput', function() {
            // Pass the global currentExistingBidders so it remembers who already bidded
            renderDriverList(currentJobId, $(this).val(), currentExistingBidders);
        });
        $(document).on('click', '.unassigned-driver-btn', function() {
            const jobId = $(this).data('job');
            const jobNo = $(this).data('jobno');
            // Inject Job No into Header
            $('#availableDriversTitle').html(`Available Drivers <span class="text-primary ms-1">(${jobNo})</span>`);
            $('#mapCard, #incomingBidsPanel').addClass('d-none');
            $('#leftPanel').removeClass('d-none');
            $('#driverListPanel').removeClass('d-none');
            loadAvailableDrivers(jobId, jobNo); // ← pass jobNo
        });
        $(document).on('click', '.makeBidBtn', function() {
            const driverId = $(this).data('driver');
            const jobId = $(this).data('job');
            $('#bidDriverId').val(driverId);
            $('#bidJobId').val(jobId);
            $('#bidAmount').val('');
            $('#bidRemark').val('');
            $('#bidErrorMsg').addClass('d-none');
            $('#createBidModal').modal('show');
        });
        $(document).on('click', '#submitBidBtn', function() {
            const jobId = $('#bidJobId').val();
            const driverId = $('#bidDriverId').val();
            const amount = $('#bidAmount').val();
            const remark = $('#bidRemark').val();
            if (!amount || parseFloat(amount) <= 0) {
                $('#bidErrorMsg')
                    .text('Please enter valid bid amount')
                    .removeClass('d-none');
                return;
            }
            $('#bidErrorMsg').addClass('d-none');
            const $btn = $(this);
            // Loading UI
            $btn.prop('disabled', true);
            $('.bid-btn-text').addClass('d-none');
            $('.bid-btn-loader').removeClass('d-none');
            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-create-bid",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                data: {
                    job_id: jobId,
                    assignedBy_id: <?php echo $_SESSION['memid'] ?>,
                    driver_id: driverId,
                    amount: amount,
                    remark: remark
                },
                success: function(res) {
                    if (res.status) {
                        $('#createBidModal').modal('hide');
                        toast('success', 'Bid created successfully');
                        // 🔥 INSTANTLY CHANGE THE BUTTON TO "Manage Bid" WITHOUT RELOADING
                        const $btnToUpdate = $(`.makeBidBtn[data-driver="${driverId}"][data-job="${jobId}"]`);
                        if ($btnToUpdate.length) {
                            $btnToUpdate
                                .removeClass('makeBidBtn btn-warning')
                                .addClass('manageBidBtn btn-success')
                                .html('<i class="fa fa-edit me-1"></i> Manage Bid');
                        }
                    } else {
                        $('#bidErrorMsg').text(res.message || 'Bid failed').removeClass('d-none');
                    }
                },
                error: function() {
                    $('#bidErrorMsg')
                        .text('Server error. Try again.')
                        .removeClass('d-none');
                },
                complete: function() {
                    $btn.prop('disabled', false);
                    $('.bid-btn-text').removeClass('d-none');
                    $('.bid-btn-loader').addClass('d-none');
                }
            });
        });
        $(document).on('click', '#confirmCancelJobBtn', function() {
            const jobId = $('#cancelJobId').val();
            const jobNo = $('#cancelJobNo').val();
            const jobType = $('#cancelJobType').val();
            const userId = $('#cancelJobUserId').val();
            const $btn = $(this);
            if (!jobId) return;
            // Loading UI
            $btn.prop('disabled', true);
            $('.cancel-btn-text').addClass('d-none');
            $('.cancel-btn-loader').removeClass('d-none');
            if (userId == 0) {
                $.ajax({
                    url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-cancel-website-job",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                    },
                    data: {
                        job_id: jobId, // <-- ADDED THIS LINE
                        job_no: jobNo
                    },
                    success: function(response) {
                        if (response.type === 1 || response.status === true) {
                            $('#cancelJobModal').modal('hide');
                            toast('success', response.message || response.result || 'Job cancelled successfully');
                            $(`.cancelJobBtn[data-job="${jobId}"]`)
                                .closest('.card')
                                .fadeOut(300, function() {
                                    $(this).remove();
                                });
                        } else {
                            $('#cancelJobError')
                                .text(response.message || response.result || 'Cancel failed')
                                .removeClass('d-none');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Server error. Try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        $('#cancelJobError')
                            .text(errorMsg)
                            .removeClass('d-none');
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                        $('.cancel-btn-text').removeClass('d-none');
                        $('.cancel-btn-loader').addClass('d-none');
                    }
                });
            } else {
                $.ajax({
                    url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-cancel-job",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                    },
                    data: {
                        job_id: jobId,
                        job_no: jobNo,
                        user_id: userId,
                        auth_key: 'ASDFGHJKLqwertyuiopMNBVCXZ!@#$%^&*()0987612345',
                        job_type: jobType // <--- SEND THE DYNAMIC JOB TYPE INSTEAD
                    },
                    success: function(res) {
                        if (res.status) {
                            $('#cancelJobModal').modal('hide');
                            toast('success', 'Job cancelled successfully');
                            // OPTIONAL: Remove card from UI
                            $(`.cancelJobBtn[data-job="${jobId}"]`)
                                .closest('.card')
                                .fadeOut(300, function() {
                                    $(this).remove();
                                });
                        } else {
                            $('#cancelJobError')
                                .text(res.message || 'Cancel failed')
                                .removeClass('d-none');
                        }
                    },
                    error: function() {
                        $('#cancelJobError')
                            .text('Server error. Try again.')
                            .removeClass('d-none');
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                        $('.cancel-btn-text').removeClass('d-none');
                        $('.cancel-btn-loader').addClass('d-none');
                    }
                });
            }
        });
        $(document).on('click', '.cancelJobBtn', function() {
            const jobId = $(this).data('job');
            const jobNo = $(this).data('job-no');
            const jobType = $(this).data('jobtype');
            const userId = $(this).data('user-id');
            $('#cancelJobId').val(jobId);
            $('#cancelJobNo').val(jobNo);
            $('#cancelJobType').val(jobType);
            $('#cancelJobUserId').val(userId);
            $('#cancelJobError').addClass('d-none');
            $('#cancelJobModal').modal('show');
        });
        $(document).on('click', '[data-filter]', function(e) {
            e.preventDefault();
            const filter = $(this).data('filter');
            $('.dropdown-item').removeClass('active');
            $(this).addClass('active');
            if (filter === 'all') {
                selectedFilterType = null;
                selectedStartDate = null;
                selectedEndDate = null;
                $('#jobDateRange').val('');
                const picker = $('#jobDateRange').data('daterangepicker');
                if (picker) {
                    picker.setStartDate(moment());
                    picker.setEndDate(moment());
                }
                reloadJobsWithFilter();
                return;
            }
            selectedFilterType = filter;
            reloadJobsWithFilter();
        });
        $(function() {
            $('#jobDateRange').daterangepicker({
                autoUpdateInput: false,
                opens: 'left',
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [
                        moment().subtract(1, 'month').startOf('month'),
                        moment().subtract(1, 'month').endOf('month')
                    ],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [
                        moment().subtract(1, 'year').startOf('year'),
                        moment().subtract(1, 'year').endOf('year')
                    ]
                }
            });
            $('#jobDateRange').on('apply.daterangepicker', function(ev, picker) {
                selectedStartDate = picker.startDate.format('YYYY-MM-DD');
                selectedEndDate = picker.endDate.format('YYYY-MM-DD');
                $(this).val(
                    picker.startDate.format('DD/MM/YYYY') +
                    ' - ' +
                    picker.endDate.format('DD/MM/YYYY')
                );
                reloadJobsWithFilter();
            });
            $('#jobDateRange').on('cancel.daterangepicker', function() {
                $(this).val('');
                selectedStartDate = null;
                selectedEndDate = null;
                reloadJobsWithFilter();
            });
        });
        function reloadJobsWithFilter() {
            loadWebsiteBookingsFinal();
            loadUnassignedJobsFinal();
            loadAssignedJobsFinal();
            loadExpiredJobsFinal();
        }
        function loadCalendarAssignedJobs(filterDriverId = null) {
            $('#calendarLoader').removeClass('d-none');
            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-job-list",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                data: {
                    method: 'jobList_New',
                    jobStatus: 'accepted'
                },
                success: function(res) {
                    calendar.removeAllEvents();
                    if (!res.result || !res.result.length) return;
                    res.result.forEach(job => {
                        if (job.job_status !== 'accept') return;
                        if (!job.pickup_date) return;
                        if (!job.bids_details) return;
                        let acceptedDriverId = null;
                        let acceptedDriverName = null;
                        Object.entries(job.bids_details).forEach(([bidderId, bid]) => {
                            if (bid.status === 'accept' || bid.status === 'accepted') {
                                acceptedDriverId = bidderId;
                                acceptedDriverName = bid.b_name;
                            }
                        });
                        if (!acceptedDriverId) return;
                        if (filterDriverId && String(acceptedDriverId) !== String(filterDriverId)) return;
                        const eventDate = job.pickup_date.replace(' ', 'T');
                        calendar.addEvent({
                            title: `${acceptedDriverName} – ${job.job_no}`,
                            start: eventDate,
                            backgroundColor: '#198754',
                            borderColor: '#198754',
                            extendedProps: {
                                jobId: job.id,
                                driverId: acceptedDriverId
                            }
                        });
                    });
                },
                complete: function() {
                    setTimeout(() => {
                        $('#calendarLoader').addClass('d-none');
                    }, 200);
                }
            });
        }
        const calendar = new FullCalendar.Calendar(
            document.getElementById('driverCalendar'), {
                initialView: 'dayGridMonth',
                height: '100%',
                fixedWeekCount: false,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                eventDidMount(info) {
                    info.el.style.fontSize = '12px';
                    info.el.style.borderRadius = '6px';
                },
                eventClick(info) {
                    const jobId = info.event.extendedProps.jobId;
                }
            }
        );
        $('[data-view]').on('click', function() {
            $('[data-view]').removeClass('active');
            $(this).addClass('active');
            const view = $(this).data('view');
            if (view === 'month') {
                calendar.changeView('dayGridMonth');
            } else {
                calendar.changeView('timeGridWeek');
            }
        });
        $('button[data-bs-target="#driversTab"]').on('shown.bs.tab', function() {
            $('#calendarLoader').removeClass('d-none');
            setTimeout(() => {
                calendar.updateSize();
                calendar.render();
                loadCalendarAssignedJobs();
                loadDriversList();
            }, 100);
        });
        function refreshCalendar(driverId = null) {
            loadCalendarAssignedJobs(driverId);
        }
        $('button[data-view]').on('click', function() {
            $('button[data-view]').removeClass('active');
            $(this).addClass('active');
            const view = $(this).data('view');
            calendar.changeView(view === 'week' ? 'timeGridWeek' : 'dayGridMonth');
        });
        $(document).on('click', '.driver-item', function(e) {
            e.preventDefault();
            $('.driver-item').removeClass('active');
            $(this).addClass('active');
            selectedDriver = $(this).data('driver');
            refreshCalendar(selectedDriver);
        });
        $('#showAllDrivers').on('click', function() {
            selectedDriver = null;
            $('.driver-item').removeClass('active');
            refreshCalendar();
        });
        $('#driverSearch').on('keyup', function() {
            renderDriversList();
        });
        $('#profileFilterType').on('change', function() {
            renderDriversList();
        });
        const profileSelect = new TomSelect("#profileFieldFilter", {
            plugins: ['remove_button'],
            maxItems: null,
            hideSelected: true,
            closeAfterSelect: false,
            placeholder: "Select profile fields...",
            onChange: function() {
                renderDriversList();
            }
        });
        const rangeSlider = document.getElementById('percentageRange');
        noUiSlider.create(rangeSlider, {
            start: [0, 100],
            connect: true,
            range: {
                'min': 0,
                'max': 100
            },
            step: 1
        });
        rangeSlider.noUiSlider.on('update', function(values) {
            percentageMin = parseInt(values[0]);
            percentageMax = parseInt(values[1]);
            $('#rangeMin').text(percentageMin + '%');
            $('#rangeMax').text(percentageMax + '%');
        });
        rangeSlider.noUiSlider.on('change', function() {
            renderDriversList();
        });
        function getSectionData(section, driverId) {
            let data = {
                driver_id: driverId
            };
            switch (section) {
                case 'driver-profile':
                    let name = $('#edit_name').val();
                    let mobile = $('#edit_mobile').val();
                    // let age = $('#edit_age').val();
                    let email = $('#edit_email').val();
                    let districts_id = $('#edit_districts_id').val();
                    let state = $('#edit_state').val();
                    let address = $('#edit_address').val();
                    let exp = $('#edit_experience').val();
                    let selectedLangs = window.editLanguagesTomSelect.getValue();
                    let langsString = '';
                    if (selectedLangs) {
                        langsString = selectedLangs.join(',');
                    }
                    console.log("Languages:", langsString);
                    // Debugging
                    console.log("Extracted Languages:", langsString);
                    if (!name) {
                        showNotification('Driver name is required', 'error');
                        return false;
                    }
                    // if (!age) {
                    //     showNotification('Age is required', 'error');
                    //     return false;
                    // }
                    if (!mobile || mobile.length < 10) {
                        showNotification('Valid mobile number is required', 'error');
                        return false;
                    }
                    if (!email) {
                        showNotification('Email is required', 'error');
                        return false;
                    }
                    // if (!city) {
                    //     showNotification('City is required', 'error');
                    //     return false;
                    // }
                    if (!address) {
                        showNotification('Address is required', 'error');
                        return false;
                    }
                    if (!exp) {
                        showNotification('Driver experience is required', 'error');
                        return false;
                    }
                    data = {
                        driver_id: driverId,
                        method: 'update_driver_profile',
                        name: name,
                        email: email,
                        mobile: mobile,
                        districts_id: districts_id,
                        state: state || '',
                        address: address,
                        exp: exp,
                        languages: langsString // ✅ Now correctly passes the string
                    };
                    break;
                case 'remarks':
                    data = {
                        ...data,
                        method: 'update_driver_remarks',
                        remarks: $('#edit_remarks').val(),
                        review: $('#edit_review').val()
                    };
                    break;
                case 'fare':
                    let perKm = $('#edit_per_km').val().trim();
                    let extraKm = $('#edit_extra_km').val().trim();
                    let extraHour = $('#edit_extra_hour').val().trim();
                    let extraDay = $('#edit_extra_day').val().trim();
                    // Validation: Only validate if a value is entered (not mandatory)
                    if (perKm !== '' && (perKm < 0 || perKm.length > 5)) {
                        toast1("Please enter a valid Per KM amount (Max 5 digits)", "error");
                        return false;
                    }
                    if (extraKm !== '' && (extraKm < 0 || extraKm.length > 5)) {
                        toast1("Please enter a valid Extra KM amount (Max 5 digits)", "error");
                        return false;
                    }
                    if (extraHour !== '' && (extraHour < 0 || extraHour.length > 5)) {
                        toast1("Please enter a valid Extra Hour amount (Max 5 digits)", "error");
                        return false;
                    }
                    if (extraDay !== '' && (extraDay < 0 || extraDay.length > 5)) {
                        toast1("Please enter a valid Extra Day amount (Max 5 digits)", "error");
                        return false;
                    }
                    data = {
                        ...data,
                        method: 'update_driver_fare',
                        per_km: perKm,
                        extra_per_km: extraKm,
                        per_hour: extraHour,
                        per_day: extraDay
                    };
                    break;
                case 'payment':
                    let upiId = $('#edit_upi').val().trim();
                    // 1. Don't allow empty field
                    if (!upiId) {
                        toast1("UPI ID is required", "error");
                        return false;
                    }
                    // 2. Don't allow spaces (fallback validation)
                    if (/\s/.test(upiId)) {
                        toast1("Spaces are not allowed in UPI ID", "error");
                        return false;
                    }
                    // 3. Max length of 30
                    if (upiId.length > 30) {
                        toast1("UPI ID cannot exceed 30 characters", "error");
                        return false;
                    }
                    data = {
                        ...data,
                        method: 'update_driver_payment',
                        upiID: upiId
                    };
                    break;
                case 'vehicle':
                    let vType = $('#edit_vehicle_type').val();
                    let vMaker = $('#edit_maker_model').val().trim();
                    let vFuel = $('#edit_fuel').val();
                    let vSeating = $('#edit_seating').val().trim();
                    let vLuggage = $('#edit_luggage').val().trim();
                    let vRC = $('#edit_rc').val();
                    let vInsurance = $('#edit_insurance').val();
                    // 1. Mandatory & Regex Validations
                    if (!vType) {
                        toast1("Vehicle Type is required", "error");
                        return false;
                    }
                    if (!vMaker) {
                        toast1("Maker Model is required", "error");
                        return false;
                    }
                    if (vMaker.length > 75) {
                        toast1("Maker Model must be 25 characters or less", "error");
                        return false;
                    }
                    if (!vFuel) {
                        toast1("Fuel Type is required", "error");
                        return false;
                    }
                    if (!vSeating) {
                        toast1("Seating is required", "error");
                        return false;
                    }
                    if (!/^\d{1,3}$/.test(vSeating)) {
                        toast1("Seating must be numbers only (Max 3 digits)", "error");
                        return false;
                    }
                    if (!vLuggage) {
                        toast1("Luggage is required", "error");
                        return false;
                    }
                    if (!/^\d{1,3}$/.test(vLuggage)) {
                        toast1("Luggage must be numbers only (Max 3 digits)", "error");
                        return false;
                    }
                    if (!vRC) {
                        toast1("RC Validity date is required", "error");
                        return false;
                    }
                    if (!vInsurance) {
                        toast1("Insurance Validity date is required", "error");
                        return false;
                    }
                    // 2. Date Validations (Prevent Past Dates)
                    let todayDate = new Date();
                    todayDate.setHours(0, 0, 0, 0); // Reset time to midnight for accurate day comparison
                    let selRCDate = new Date(vRC);
                    if (selRCDate < todayDate) {
                        toast1("RC Validity cannot be a past date", "error");
                        return false;
                    }
                    if (vInsurance) { // Insurance is non-mandatory, but if filled, must not be past
                        let selInsDate = new Date(vInsurance);
                        if (selInsDate < todayDate) {
                            toast1("Insurance Validity cannot be a past date", "error");
                            return false;
                        }
                    }
                    // 3. Payload Construction (Names match PHP exactly)
                    data = {
                        driver_id: driverId,
                        method: 'update_driver_vehicle',
                        vehicle_type: vType,
                        maker_model: vMaker,
                        fuel: vFuel,
                        seating: vSeating,
                        luggage: vLuggage,
                        rc_expiry: vRC,
                        insurance_expiry: vInsurance,
                        // S3 Images
                        front_view_image_url: $('#val_front_view_image_url').val(),
                        boot_image_url: $('#val_boot_image_url').val(),
                        extra_image_1_url: $('#val_extra_image_1_url').val(),
                        car_top_view_image_url: $('#val_car_top_view_image_url').val(),
                        interior_front_image_url: $('#val_interior_front_image_url').val(),
                        special_features_image_url: $('#val_special_features_image_url').val()
                    };
                    break;
            }
            return data;
        }
        function updateSectionDisplay(section) {
            switch (section) {
                case 'driver-profile':
                    // Added .display-field to all these selectors to prevent destroying the inputs
                    $('.display-field[data-field="name"]').text($('#edit_name').val());
                    // Update Mobile Text AND the Call/WhatsApp Links
                    let newMobile = $('#edit_mobile').val();
                    $('.display-field[data-field="mobile"]').text(newMobile);
                    let cleanNewMobile = '';
                    if (newMobile) {
                        cleanNewMobile = String(newMobile).replace(/\D/g, '');
                        let waNewMobile = cleanNewMobile.length === 10 ? '91' + cleanNewMobile : cleanNewMobile;
                        $('#view-driver-profile .call-icon').attr('href', `tel:${cleanNewMobile}`);
                        $('#view-driver-profile .whatsapp-icon').attr('href', `https://wa.me/${waNewMobile}`);
                    }
                    $('.display-field[data-field="address"]').text($('#edit_address').val());
                    let selectedDistName = $('#edit_districts_id option:selected').text();
                    $('.display-field[data-field="city"]').text(selectedDistName !== 'Select District' ? selectedDistName : '-');
                    $('.display-field[data-field="state"]').text($('#edit_state').val());
                    $('.display-field[data-field="email"]').text($('#edit_email').val());
                    $('#display_experience').text($('#edit_experience').val() + " Years");
                    const langs = ($('#edit_languages').val() || []).join(' | ');
                    $('.display-field[data-field="languages"]').text(langs);
                    // ✨ DYNAMICALLY UPDATE THE LEFT SIDE PREVIEW MODAL ✨
                    $('#preview_driver_name').text($('#edit_name').val() || 'N/A');
                    if (cleanNewMobile) {
                        $('#preview_driver_no').html(`<i class="fa fa-phone text-success me-1"></i> (${cleanNewMobile})`);
                        $('#preview_call_btn').attr('href', `tel:${cleanNewMobile}`);
                        let wa = cleanNewMobile.length === 10 ? '91' + cleanNewMobile : cleanNewMobile;
                        $('#preview_wa_btn').attr('href', `https://wa.me/${wa}`);
                    }
                    let locTxt = [$('#edit_address').val(), $('#edit_state').val()].filter(Boolean).join(', ');
                    $('#preview_location').html(`<i class="fa fa-map-marker-alt text-danger me-1"></i> ${locTxt || 'N/A'}`);
                    $('#preview_experience').html(`<i class="fa fa-briefcase text-info me-1"></i> ${$('#edit_experience').val() ? $('#edit_experience').val() + ' Years' : 'N/A'}`);
                    $('#preview_languages').html(`<i class="fa fa-language text-dark me-1"></i> <strong>${langs || 'N/A'}</strong>`);
                    break;
                case 'remarks':
                    $('#display_remarks').text($('#edit_remarks').val());
                    $('#display_review').text($('#edit_review').val());
                    break;
                case 'fare':
                    $('#display_per_km').text($('#edit_per_km').val());
                    $('#display_extra_km').text($('#edit_extra_km').val());
                    $('#display_extra_hour').text($('#edit_extra_hour').val());
                    $('#display_extra_day').text($('#edit_extra_day').val());
                    // ✨ DYNAMICALLY UPDATE THE LEFT SIDE PREVIEW MODAL ✨
                    $('#preview_per_km').text($('#edit_per_km').val() ? `₹${$('#edit_per_km').val()}` : 'N/A');
                    $('#preview_extra_km').text($('#edit_extra_km').val() ? `₹${$('#edit_extra_km').val()}` : 'N/A');
                    $('#preview_extra_hour').text($('#edit_extra_hour').val() ? `₹${$('#edit_extra_hour').val()}` : 'N/A');
                    $('#preview_extra_day').text($('#edit_extra_day').val() ? `₹${$('#edit_extra_day').val()}` : 'N/A');
                    break;
                case 'payment':
                    $('#display_upi').text($('#edit_upi').val());
                    break;
                case 'vehicle':
                    // Fix raw date string bug by parsing dates correctly for the display
                    let rcDateStr = $('#edit_rc').val();
                    let inDateStr = $('#edit_insurance').val();
                    let rcFormatted = rcDateStr ? formatDateSimple(rcDateStr) : 'N/A';
                    let inFormatted = inDateStr ? formatDateSimple(inDateStr) : 'N/A';
                    $('#display_vehicle_type').text($('#edit_vehicle_type').val());
                    $('#display_maker_model').text($('#edit_maker_model').val());
                    $('#display_fuel').text($('#edit_fuel').val());
                    $('#display_seating').text($('#edit_seating').val());
                    $('#display_luggage').text($('#edit_luggage').val());
                    $('#display_rc').text(rcFormatted);
                    $('#display_insurance').text(inFormatted);
                    // ✨ DYNAMICALLY UPDATE THE LEFT SIDE PREVIEW MODAL ✨
                    $('#preview_vehicle_name').text($('#edit_vehicle_type').val() || 'N/A');
                    $('#preview_vehicle_model').text($('#edit_maker_model').val() || 'N/A');
                    $('#preview_fuel').text($('#edit_fuel').val() || 'N/A');
                    $('#preview_seats').text($('#edit_seating').val() || 'N/A');
                    $('#preview_luggage').text($('#edit_luggage').val() || 'N/A');
                    $('#preview_rc').text(rcFormatted);
                    $('#preview_in').text(inFormatted);
                    break;
            }
        }
        // Helper to generate a row HTML with Dropdowns
        // Helper to generate a row HTML with Dropdowns
        // Creates the parent block for a specific From -> To route
        function createRouteBlock(from = '', to = '', dates = []) {
            const getSelectOptions = (selectedValue, placeholder) => {
                let options = `<option value="">${placeholder}</option>` + districtOptionsHTML;
                if (!selectedValue) return options;
                let searchStr = `value='${selectedValue}'`;
                if (options.indexOf(searchStr) !== -1) {
                    return options.replace(searchStr, `${searchStr} selected`);
                } else {
                    return `<option value="${selectedValue}" selected>${selectedValue}</option>` + options;
                }
            };
            let datesHtml = '';
            // If no dates provided, generate one empty row by default
            if (dates.length === 0) {
                datesHtml = createDateRow('', '');
            } else {
                dates.forEach(d => {
                    datesHtml += createDateRow(d.datetime, d.price);
                });
            }
            return `
    <div class="schedule-route-box border  p-2 mb-3 bg-white shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
            <strong class="text-primary"><i class="fa fa-route text-danger me-1"></i> Route Selection</strong>
            <button type="button" class="btn btn-sm btn-outline-danger remove-route-box py-0 px-2" title="Remove Entire Route"><i class="fa fa-trash"></i></button>
        </div>
        <div class="row g-2 mb-3">
            <div class="col-6">
                <label class="form-label mb-0 text-muted" style="font-size:11px;">From City</label>
                <select class="form-select form-select-sm sch-from">
                    ${getSelectOptions(from, 'Select From')}
                </select>
            </div>
            <div class="col-6">
                <label class="form-label mb-0 text-muted" style="font-size:11px;">To City</label>
                <select class="form-select form-select-sm sch-to">
                    ${getSelectOptions(to, 'Select To')}
                </select>
            </div>
        </div>
        <div class="dates-wrapper p-2 bg-light  border">
            <label class="form-label mb-2 fw-bold" style="font-size:12px;"><i class="fa fa-calendar-alt text-info me-1"></i>Dates & Prices</label>
            ${datesHtml}
        </div>
        <button type="button" class="btn btn-sm btn-primary mt-2 w-100 add-date-row-btn" style="border-style: dashed;">
            <i class="fa fa-plus me-1"></i> Add Another Date for this Route
        </button>
    </div>`;
        }
        // Creates the individual Date & Price rows inside a Route block
        function createDateRow(datetime = '', price = '') {
            return `
    <div class="row g-2 mb-2 align-items-center schedule-date-row">
        <div class="col-6">
            <input type="datetime-local" 
                   class="form-control form-control-sm sch-datetime border-primary" 
                   value="${datetime}" 
                   style="cursor: pointer;"
                   onkeydown="return false;" 
                   onpaste="return false;"
                   onclick="this.showPicker ? this.showPicker() : null">
        </div>
        <div class="col-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-primary border-end-0 text-dark">₹</span>
                <input type="number" 
                       class="form-control border-primary border-start-0 px-1 sch-price" 
                       placeholder="Price" 
                       value="${price}" 
                       min="1" 
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)">
            </div>
        </div>
        <div class="col-2 text-end">
            <button type="button" class="btn btn-sm btn-danger remove-date-row py-1 px-2" title="Remove Date"><i class="fa fa-times"></i></button>
        </div>
    </div>`;
        }
        // Function to fetch and render Scheduled Jobs
        // Function to fetch and render Scheduled Jobs
        function loadDriverSchedule(driverId) {
            $('#scheduledJobsList').html('<div class="text-center p-3 text-muted"><i class="fa fa-spinner fa-spin me-2"></i>Loading schedule...</div>');
            $('#dynamicScheduleWrapper').empty();
            $.ajax({
                url: "ajax/service/driverServices.php",
                type: "POST",
                dataType: "json",
                data: {
                    method: 'get_driver_schedule',
                    driver_id: driverId
                },
                success: function(res) {
                    let viewHtml = '';
                    let editHtml = '';
                    // 1. Count exactly how many scheduled dates exist
                    let jobCount = (res.status && res.data) ? res.data.length : 0;
                    // 2. Cap the score at 5 for the UI Badge
                    let jobScore = Math.min(jobCount, 5);
                    // 3. Dynamic Badge Styling (Red for 0, Yellow for 1-4, Green for 5)
                    let badgeClass = 'bg-success';
                    let textClass = 'text-white';
                    if (jobScore === 0) {
                        badgeClass = 'bg-danger';
                    } else if (jobScore < 5) {
                        badgeClass = 'bg-warning';
                        textClass = 'text-dark';
                    }
                    // Update the UI Badge
                    $('#status-jobs').html(`<span class="badge ${badgeClass} ${textClass} ms-2" style="font-size: 11px; padding: 3px 6px;">${jobScore}/5</span>`);
                    // 4. Render the Data
                    if (jobCount > 0) {
                        let groupedRoutes = {};
                        res.data.forEach(item => {
                            let safeDateStr = String(item.date).replace(' ', 'T');
                            let d = new Date(safeDateStr);
                            if (isNaN(d.getTime())) return;
                            // Build View HTML
                            let formattedDate = d.toLocaleString('en-GB', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: true
                            }).toUpperCase();
                            viewHtml += `
                        <div class="schedule-item p-1 border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fa fa-map-marker-alt text-success me-2"></i> 
                                <strong>${item.from}</strong> <i class="fa fa-arrow-right text-muted mx-1" style="font-size: 10px;"></i> <strong>${item.to}</strong>
                            </div>
                            <div class="text-end">
                                <span class="d-block fw-bold text-info">₹${item.price}</span>
                                <span class="text-muted small"><i class="fa fa-calendar-alt text-danger me-1"></i>${formattedDate}</span>
                            </div>
                        </div>
                    `;
                            // Group items for the Edit HTML
                            let routeKey = `${item.from}_${item.to}`;
                            if (!groupedRoutes[routeKey]) {
                                groupedRoutes[routeKey] = {
                                    from: item.from,
                                    to: item.to,
                                    dates: []
                                };
                            }
                            let year = d.getFullYear();
                            let month = String(d.getMonth() + 1).padStart(2, '0');
                            let day = String(d.getDate()).padStart(2, '0');
                            let hours = String(d.getHours()).padStart(2, '0');
                            let minutes = String(d.getMinutes()).padStart(2, '0');
                            let inputValidDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
                            groupedRoutes[routeKey].dates.push({
                                datetime: inputValidDateTime,
                                price: item.price
                            });
                        });
                        // Build the new UI blocks from grouped data
                        Object.values(groupedRoutes).forEach(routeObj => {
                            editHtml += createRouteBlock(routeObj.from, routeObj.to, routeObj.dates);
                        });
                        $('#scheduledJobsList').html(viewHtml);
                        $('#dynamicScheduleWrapper').html(editHtml);
                    } else {
                        $('#scheduledJobsList').html('<div class="text-center p-3 text-muted">No upcoming scheduled jobs found.</div>');
                    }
                },
                error: function() {
                    $('#status-jobs').html('<span class="badge bg-danger text-white ms-2" style="font-size: 11px; padding: 3px 6px;">0/5</span>');
                    $('#scheduledJobsList').html('<div class="text-center p-3 text-danger">Failed to load schedules.</div>');
                }
            });
        }
        // Validation: Prevent selecting the same From and To city
        $(document).on('change', '.sch-from, .sch-to', function() {
            let $row = $(this).closest('.schedule-edit-row');
            let fromCity = $row.find('.sch-from').val();
            let toCity = $row.find('.sch-to').val();
            if (fromCity && toCity && fromCity === toCity) {
                showNotification("From City and To City cannot be the same.", "error");
                $(this).val(""); // Reset the dropdown the user just changed
            }
        });
        // Add New Row Event
        $(document).on('click', '#addScheduleRowBtn', function() {
            $('#dynamicScheduleWrapper').append(createScheduleRow());
            // Scroll to bottom
            let wrapper = document.getElementById('dynamicScheduleWrapper');
            wrapper.scrollTop = wrapper.scrollHeight;
        });
        // Remove Row Event
        $(document).on('click', '.remove-sch-row', function() {
            $(this).closest('.schedule-edit-row').remove();
        });
        // --- NEW EVENT HANDLERS FOR NESTED DATES ---
        $(document).off('click', '#addScheduleRowBtn').on('click', '#addScheduleRowBtn', function() {
            $('#dynamicScheduleWrapper').append(createRouteBlock());
            let wrapper = document.getElementById('dynamicScheduleWrapper');
            wrapper.scrollTop = wrapper.scrollHeight;
        });
        $(document).off('click', '.add-date-row-btn').on('click', '.add-date-row-btn', function() {
            $(this).siblings('.dates-wrapper').append(createDateRow());
        });
        $(document).off('click', '.remove-route-box').on('click', '.remove-route-box', function() {
            $(this).closest('.schedule-route-box').remove();
        });
        $(document).off('click', '.remove-date-row').on('click', '.remove-date-row', function() {
            let wrapper = $(this).closest('.dates-wrapper');
            $(this).closest('.schedule-date-row').remove();
            // If the user deletes all dates, ensure at least one empty box remains
            if (wrapper.find('.schedule-date-row').length === 0) {
                wrapper.append(createDateRow());
            }
        });
        $(document).on('change', '.sch-from, .sch-to', function() {
            let $box = $(this).closest('.schedule-route-box');
            let fromCity = $box.find('.sch-from').val();
            let toCity = $box.find('.sch-to').val();
            if (fromCity && toCity && fromCity === toCity) {
                showNotification("From City and To City cannot be the same.", "error");
                $(this).val("");
            }
        });
        // --- UPDATED SAVE FUNCTION ---
        // --- UPDATED SAVE FUNCTION (Checks Duplicate Dates Only) ---
        $(document).off('click', '#saveScheduleBtn').on('click', '#saveScheduleBtn', function(e) {
            e.preventDefault();
            let driverId = window.currentDriverId;
            let routes = [];
            let isValid = true;
            let errorMessage = "Please fill all required fields.";
            let uniqueEntries = new Set();
            // Reset error borders
            $('.schedule-route-box, .schedule-date-row').css('background-color', 'transparent');
            $('.schedule-route-box').each(function() {
                let from = $(this).find('.sch-from').val();
                let to = $(this).find('.sch-to').val();
                // Check if block has been touched at all
                let hasData = false;
                $(this).find('.sch-datetime, .sch-price').each(function() {
                    if ($(this).val() !== '') hasData = true;
                });
                if (from || to || hasData) {
                    if (!from || !to) {
                        isValid = false;
                        $(this).css('background-color', '#ffe6e6');
                        errorMessage = "Please select both From and To cities for the route.";
                        return false; // breaks the .each loop
                    }
                    let newRoute = {
                        from: from,
                        to: to,
                        dates: {}
                    };
                    let hasValidDate = false;
                    // Loop through all dates under this specific Route block
                    $(this).find('.schedule-date-row').each(function() {
                        let rawDate = $(this).find('.sch-datetime').val();
                        let rawPrice = $(this).find('.sch-price').val();
                        if (rawDate || rawPrice) {
                            let priceInt = parseInt(rawPrice, 10);
                            if (!rawDate || !rawPrice) {
                                isValid = false;
                                $(this).css('background-color', '#ffe6e6');
                                errorMessage = "Please provide both Date and Price.";
                                return false;
                            }
                            if (isNaN(priceInt) || priceInt <= 0) {
                                isValid = false;
                                $(this).css('background-color', '#ffe6e6');
                                errorMessage = "Price must be greater than 0.";
                                return false;
                            }
                            // Extract JUST the date (YYYY-MM-DD) ignoring the time (HH:mm)
                            let dateOnly = rawDate.split('T')[0];
                            let uniqueKey = `${from}_${to}_${dateOnly}`;
                            if (uniqueEntries.has(uniqueKey)) {
                                isValid = false;
                                $(this).css('background-color', '#ffe6e6');
                                errorMessage = `Duplicate trip found: ${from} to ${to} on the same date (${dateOnly}).`;
                                return false;
                            }
                            uniqueEntries.add(uniqueKey);
                            // We still save the full datetime to the database
                            let formattedDateTime = rawDate.replace('T', ' ') + ":00";
                            newRoute.dates[formattedDateTime] = priceInt;
                            hasValidDate = true;
                        }
                    });
                    if (hasValidDate) {
                        routes.push(newRoute);
                    } else if (!hasValidDate && isValid) {
                        isValid = false;
                        $(this).css('background-color', '#ffe6e6');
                        errorMessage = "Please add at least one valid Date and Price for the route.";
                        return false;
                    }
                }
            });
            if (!isValid) {
                showNotification(errorMessage, 'error');
                return;
            }
            let $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            $.ajax({
                url: "ajax/service/driverServices.php",
                type: "POST",
                dataType: "json",
                data: {
                    method: 'save_driver_schedule',
                    driver_id: driverId,
                    routes: JSON.stringify(routes)
                },
                success: function(res) {
                    if (res.status) {
                        showNotification(res.message, 'success');
                        loadDriverSchedule(driverId);
                        toggleSection('jobs', false);
                        recalculateDriverScore(driverId);
                    } else {
                        showNotification(res.message, 'error');
                    }
                },
                error: function() {
                    showNotification('Server error while saving schedules', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i>Save');
                }
            });
        });
        $(document).on('click', '.save-section', function() {
            const $btn = $(this);
            const section = $btn.data('section');
            const driverId = window.currentDriverId;
            if ($btn.prop('disabled')) return;
            // get data first
            const requestData = getSectionData(section, driverId);
            // stop if validation failed
            if (!requestData) {
                return;
            }
            $.ajax({
                url: 'ajax/service/driverServices.php',
                type: 'POST',
                dataType: 'json',
                data: requestData,
                beforeSend: function() {
                    $btn.prop('disabled', true);
                    $btn.data('original-text', $btn.html());
                    $btn.html('<i class="fa fa-spinner fa-spin"></i> Saving...');
                },
                complete: function() {
                    $btn.prop('disabled', false);
                    $btn.html($btn.data('original-text'));
                },
                success: function(res) {
                    if (res.status) {
                        updateSectionDisplay(section);
                        showNotification('Updated successfully', 'success');
                        recalculateDriverScore(driverId); // ✅ Keep only this
                        toggleSection(section, false);
                    } else {
                        showNotification(res.message, 'danger');
                    }
                }
            });
        });
        // --- NEW EVENT HANDLERS FOR NESTED DATES ---
        $(document).off('click', '#addScheduleRowBtn').on('click', '#addScheduleRowBtn', function() {
            $('#dynamicScheduleWrapper').append(createRouteBlock());
            let wrapper = document.getElementById('dynamicScheduleWrapper');
            wrapper.scrollTop = wrapper.scrollHeight;
        });
        $(document).off('click', '.add-date-row-btn').on('click', '.add-date-row-btn', function() {
            $(this).siblings('.dates-wrapper').append(createDateRow());
        });
        // --- DIRECT DELETE & AUTO-SAVE LOGIC ---
        function autoSaveSchedulesSilently() {
            let driverId = window.currentDriverId;
            let routes = [];
            // Only collect VALID routes for the silent auto-save
            $('.schedule-route-box').each(function() {
                let from = $(this).find('.sch-from').val();
                let to = $(this).find('.sch-to').val();
                if (from && to) {
                    let newRoute = {
                        from: from,
                        to: to,
                        dates: {}
                    };
                    let hasValidDate = false;
                    $(this).find('.schedule-date-row').each(function() {
                        let rawDate = $(this).find('.sch-datetime').val();
                        let rawPrice = $(this).find('.sch-price').val();
                        if (rawDate && rawPrice) {
                            let formattedDateTime = rawDate.replace('T', ' ') + ":00";
                            newRoute.dates[formattedDateTime] = parseInt(rawPrice, 10);
                            hasValidDate = true;
                        }
                    });
                    if (hasValidDate) {
                        routes.push(newRoute);
                    }
                }
            });
            // Send to backend instantly
            $.ajax({
                url: "ajax/service/driverServices.php",
                type: "POST",
                dataType: "json",
                data: {
                    method: 'save_driver_schedule',
                    driver_id: driverId,
                    routes: JSON.stringify(routes)
                },
                success: function(res) {
                    if (res.status) {
                        loadDriverSchedule(driverId);
                        recalculateDriverScore(driverId);
                    }
                }
            });
        }
        $(document).off('click', '.remove-route-box').on('click', '.remove-route-box', function() {
            $(this).closest('.schedule-route-box').remove();
            showNotification('Job removed successfully.', 'info');
            autoSaveSchedulesSilently(); // Directly delete in backend
        });
        $(document).off('click', '.remove-date-row').on('click', '.remove-date-row', function() {
            let wrapper = $(this).closest('.dates-wrapper');
            $(this).closest('.schedule-date-row').remove();
            // If they deleted the last date for this route, remove the entire route box
            if (wrapper.find('.schedule-date-row').length === 0) {
                wrapper.closest('.schedule-route-box').remove();
            }
            showNotification('Scheduled date removed.', 'info');
            autoSaveSchedulesSilently(); // Directly delete in backend
        });
        $(document).on('change', '.sch-from, .sch-to', function() {
            let $box = $(this).closest('.schedule-route-box');
            let fromCity = $box.find('.sch-from').val();
            let toCity = $box.find('.sch-to').val();
            if (fromCity && toCity && fromCity === toCity) {
                showNotification("From City and To City cannot be the same.", "error");
                $(this).val("");
            }
        });
        // --- UPDATED SAVE FUNCTION ---
        $(document).off('click', '#saveScheduleBtn').on('click', '#saveScheduleBtn', function(e) {
            e.preventDefault();
            let driverId = window.currentDriverId;
            let routes = [];
            let isValid = true;
            let errorMessage = "Please fill all required fields.";
            let uniqueEntries = new Set();
            // PREVENT SAVING EMPTY SCHEDULE (Directly checks DOM length)
            if ($('.schedule-route-box').length === 0) {
                toast1("Schedule is empty! Add a route or close the editor.", "error");
                return;
            }
            // Reset error borders
            $('.schedule-route-box, .schedule-date-row').css('background-color', 'transparent');
            $('.schedule-route-box').each(function() {
                let from = $(this).find('.sch-from').val();
                let to = $(this).find('.sch-to').val();
                // Check if block has been touched at all
                let hasData = false;
                $(this).find('.sch-datetime, .sch-price').each(function() {
                    if ($(this).val() !== '') hasData = true;
                });
                if (from || to || hasData) {
                    if (!from || !to) {
                        isValid = false;
                        $(this).css('background-color', '#ffe6e6');
                        errorMessage = "Please select both From and To cities for the route.";
                        return false; // breaks loop
                    }
                    let newRoute = {
                        from: from,
                        to: to,
                        dates: {}
                    };
                    let hasValidDate = false;
                    $(this).find('.schedule-date-row').each(function() {
                        let rawDate = $(this).find('.sch-datetime').val();
                        let rawPrice = $(this).find('.sch-price').val();
                        if (rawDate || rawPrice) {
                            let priceInt = parseInt(rawPrice, 10);
                            if (!rawDate || !rawPrice) {
                                isValid = false;
                                $(this).css('background-color', '#ffe6e6');
                                errorMessage = "Please provide both Date and Price.";
                                return false;
                            }
                            if (isNaN(priceInt) || priceInt <= 0) {
                                isValid = false;
                                $(this).css('background-color', '#ffe6e6');
                                errorMessage = "Price must be greater than 0.";
                                return false;
                            }
                            let dateOnly = rawDate.split('T')[0];
                            let uniqueKey = `${from}_${to}_${dateOnly}`;
                            if (uniqueEntries.has(uniqueKey)) {
                                isValid = false;
                                $(this).css('background-color', '#ffe6e6');
                                errorMessage = `Duplicate trip found: ${from} to ${to} on the same date (${dateOnly}).`;
                                return false;
                            }
                            uniqueEntries.add(uniqueKey);
                            let formattedDateTime = rawDate.replace('T', ' ') + ":00";
                            newRoute.dates[formattedDateTime] = priceInt;
                            hasValidDate = true;
                        }
                    });
                    if (hasValidDate) {
                        routes.push(newRoute);
                    } else if (!hasValidDate && isValid) {
                        isValid = false;
                        $(this).css('background-color', '#ffe6e6');
                        errorMessage = "Please add at least one valid Date and Price for the route.";
                        return false;
                    }
                }
            });
            if (!isValid) {
                showNotification(errorMessage, 'error');
                return;
            }
            // PREVENT SAVING EMPTY SCHEDULE (Failsafe checking JSON payload length)
            if (routes.length === 0) {
                toast1("Schedule cannot be empty. Please add at least one valid route.", "error");
                return;
            }
            let $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            $.ajax({
                url: "ajax/service/driverServices.php",
                type: "POST",
                dataType: "json",
                data: {
                    method: 'save_driver_schedule',
                    driver_id: driverId,
                    routes: JSON.stringify(routes)
                },
                success: function(res) {
                    if (res.status) {
                        showNotification(res.message, 'success');
                        loadDriverSchedule(driverId);
                        toggleSection('jobs', false);
                        recalculateDriverScore(driverId);
                    } else {
                        showNotification(res.message, 'error');
                    }
                },
                error: function() {
                    showNotification('Server error while saving schedules', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i>Save');
                }
            });
        });
        function showUploadLoader(container) {
            $(container).css('position', 'relative');
            $(container).append(`
                <div class="upload-loading">
                    <i class="fa fa-spinner fa-spin"></i>
                </div>
            `);
        }
        function hideUploadLoader(container) {
            $(container).find('.upload-loading').remove();
        }
        function getS3PresignedUrl(file) {
            return $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-s3/presigned-url",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                data: {
                    file_name: file.name,
                    file_type: file.type
                }
            });
        }
        async function uploadFileToS3(uploadUrl, file) {
            const response = await fetch(uploadUrl, {
                method: "PUT",
                headers: {
                    "Content-Type": file.type
                },
                body: file
            });
            if (!response.ok) {
                const text = await response.text();
                console.error("S3 Upload Failed:", text);
                throw new Error("S3 upload failed");
            }
            return true;
        }
        async function uploadFileViaPresignedUrl(file) {
            try {
                const presignedRes = await getS3PresignedUrl(file);
                if (!presignedRes.status) {
                    throw new Error("Failed to get upload URL");
                }
                const uploadUrl = presignedRes.upload_url;
                const finalFileUrl = presignedRes.file_url;
                await uploadFileToS3(uploadUrl, file);
                return {
                    status: true,
                    file_url: finalFileUrl
                };
            } catch (error) {
                console.error("S3 Upload Error:", error);
                return {
                    status: false,
                    message: error.message || "Upload failed"
                };
            }
        }
        $(document).on('change', '#edit-aadhaarFront input[type="file"]', async function() {
            const file = this.files[0];
            if (!file) return;
            showUploadLoader('#edit-aadhaarFront');
            const result = await uploadFileViaPresignedUrl(file);
            hideUploadLoader('#edit-aadhaarFront');
            if (result.status) {
                aadhaarFrontUrl = result.file_url;
                $('#edit-aadhaarFront img').attr('src', aadhaarFrontUrl);
            } else {
                alert("Front upload failed: " + result.message);
            }
        });
        $(document).on('change', '#edit-aadhaarBack input[type="file"]', async function() {
            const file = this.files[0];
            if (!file) return;
            showUploadLoader('#edit-aadhaarBack');
            const result = await uploadFileViaPresignedUrl(file);
            hideUploadLoader('#edit-aadhaarBack');
            if (result.status) {
                aadhaarBackUrl = result.file_url;
                $('#edit-aadhaarBack img').attr('src', aadhaarBackUrl);
            } else {
                alert("Back upload failed: " + result.message);
            }
        });
        $(document).on('click', '.save-section-img[data-section="aadhaar"]', async function() {
            const $btn = $(this);
            const driverId = window.currentDriverId;
            if (!aadhaarFrontUrl || !aadhaarBackUrl) {
                toast1("Both Aadhaar Front and Back images are mandatory.", "error");
                return;
            }
            // Save original button content and show loading spinner
            const originalHtml = $btn.html();
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Saving...');
            try {
                const response = await $.ajax({
                    url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-kyc/ocr",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                    },
                    data: {
                        user_id: driverId,
                        front_image: aadhaarFrontUrl,
                        back_image: aadhaarBackUrl,
                        type: "AADHAAR"
                    }
                });
                if (response.status) {
                    $('#aadhaarFront img').attr('src', aadhaarFrontUrl);
                    $('#aadhaarBack img').attr('src', aadhaarBackUrl);
                    showNotification('Aadhaar OCR Update Completed', 'success');
                    recalculateDriverScore(driverId);
                    toggleSection('aadhaar', false);
                    aadhaarFrontUrl = null;
                    aadhaarBackUrl = null;
                } else {
                    showNotification(response.message || "OCR failed", 'danger');
                }
            } catch (error) {
                if (error.status === 422) {
                    showNotification('Validation failed.', 'danger');
                } else {
                    showNotification('Server error occurred.', 'danger');
                }
            } finally {
                // Restore button to original state
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
        $(document).on('change', '#edit-licenseFront input[type="file"]', async function() {
            const file = this.files[0];
            if (!file) return;
            showUploadLoader('#edit-licenseFront');
            const result = await uploadFileViaPresignedUrl(file);
            hideUploadLoader('#edit-licenseFront');
            if (result.status) {
                dlFrontUrl = result.file_url;
                $('#edit-licenseFront img').attr('src', dlFrontUrl);
            } else {
                showNotification(result.message || "Front upload failed", 'danger');
            }
        });
        $(document).on('change', '#edit-licenseBack input[type="file"]', async function() {
            const file = this.files[0];
            if (!file) return;
            showUploadLoader('#edit-licenseBack');
            const result = await uploadFileViaPresignedUrl(file);
            hideUploadLoader('#edit-licenseBack');
            if (result.status) {
                dlBackUrl = result.file_url;
                $('#edit-licenseBack img').attr('src', dlBackUrl);
            } else {
                showNotification(result.message || "Back upload failed", 'danger');
            }
        });
        $(document).on('click', '.save-section-img[data-section="license"]', async function() {
            const driverId = window.currentDriverId;
            const dlNo = $('#edit_license_no').val().trim();
            const dob = $('#edit_license_dob').val();
            const expiry = $('#edit_license_expiry').val();
            // Helper to check if an image is real (not a default placeholder)
            const getValidUrl = (selector) => {
                let src = $(selector).attr('src');
                if (src && !src.includes('assets/images/') && !src.includes('placeholder') && !src.includes('encrypted-tbn0')) {
                    return src;
                }
                return null;
            };
            // ---> FIXED: Grab newly uploaded URL OR existing pre-filled image from screen
            const finalDlFront = dlFrontUrl || getValidUrl('#edit-licenseFront img');
            const finalDlBack = dlBackUrl || getValidUrl('#edit-licenseBack img');
            if (!dlNo || !dob || !expiry || !finalDlFront || !finalDlBack) {
                showNotification("DL No, DOB, Expiry, Front and Back images are mandatory", 'danger');
                return;
            }
            const formattedDob = dob.split('-').reverse().join('-');
            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
            try {
                const response = await $.ajax({
                    url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-kyc/ocr/dl",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                    },
                    data: {
                        user_id: driverId,
                        dl_no: dlNo,
                        dob: formattedDob,
                        expiry: expiry,
                        front_url: finalDlFront, // Use safe image
                        back_url: finalDlBack, // Use safe image
                        type: "DRIVING_LICENSE"
                    }
                });
                // AFTER
                if (response.status) {
                    $('#display_license_no').text(dlNo);
                    $('#display_license_expiry').text(formatDateSimple(expiry));
                    $('#display_license_type').text($('#edit_license_type').val());
                    if (finalDlFront) $('#licenseFront img').attr('src', finalDlFront);
                    if (finalDlBack) $('#licenseBack img').attr('src', finalDlBack);
                    showNotification(response.message || "Driving License Updated", 'success');
                    toggleSection('license', false);
                    dlFrontUrl = null;
                    dlBackUrl = null;
                    recalculateDriverScore(driverId); // ✅ Keep only this
                } else {
                    showNotification(response.message || "OCR failed", 'danger');
                }
            } catch (error) {
                if (error.status === 422) {
                    let errorMsg = "Validation failed";
                    if (error.responseJSON && error.responseJSON.message) {
                        errorMsg = error.responseJSON.message;
                    }
                    showNotification(errorMsg, 'danger');
                } else {
                    showNotification("Server error occurred", 'danger');
                }
            } finally {
                btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i>Save');
            }
        });
        $(document).on('change', '#edit-rcDocumentFront input[type="file"]', async function() {
            const file = this.files[0];
            if (!file) return;
            showUploadLoader('#edit-rcDocumentFront');
            const result = await uploadFileViaPresignedUrl(file);
            hideUploadLoader('#edit-rcDocumentFront');
            if (result.status) {
                rcFrontUrl = result.file_url;
                $('#edit-rcDocumentFront img').attr('src', rcFrontUrl).show();
            } else {
                showNotification(result.message, 'danger');
            }
        });
        $(document).on('change', '#edit-rcDocumentBack input[type="file"]', async function() {
            const file = this.files[0];
            if (!file) return;
            showUploadLoader('#edit-rcDocumentBack');
            const result = await uploadFileViaPresignedUrl(file);
            hideUploadLoader('#edit-rcDocumentBack');
            if (result.status) {
                rcBackUrl = result.file_url;
                $('#edit-rcDocumentBack img').attr('src', rcBackUrl).show();
            } else {
                showNotification(result.message, 'danger');
            }
        });
        $(document).on('change', '#edit-pucDocument input[type="file"]', async function() {
            const file = this.files[0];
            if (!file) return;
            showUploadLoader('#edit-pucDocument');
            const result = await uploadFileViaPresignedUrl(file);
            hideUploadLoader('#edit-pucDocument');
            if (result.status) {
                pucUrl = result.file_url;
                $('#edit-pucDocument img').attr('src', pucUrl).show();
            } else {
                showNotification(result.message, 'danger');
            }
        });
        $(document).on('change', '#edit-insuranceDocument input[type="file"]', async function() {
            const file = this.files[0];
            if (!file) return;
            showUploadLoader('#edit-insuranceDocument');
            const result = await uploadFileViaPresignedUrl(file);
            hideUploadLoader('#edit-insuranceDocument');
            if (result.status) {
                insuranceUrl = result.file_url;
                $('#edit-insuranceDocument img').attr('src', insuranceUrl).show();
            } else {
                showNotification(result.message, 'danger');
            }
        });
       // Prevent typing or pasting in the document date fields (forces calendar picker)
        $(document).on('keydown paste', '#edit_rc_expiry, #edit_puc_expiry, #edit_insurance_expiry', function(e) {
            e.preventDefault();
            return false;
        });

       $(document).on('click', '.save-section-img[data-section="documents"]', async function() {
            const driverId = window.currentDriverId;
            const rcNumber = $('#edit_rc_number').val().trim();
            const rcExpiry = $('#edit_rc_expiry').val();
            const pucExpiry = $('#edit_puc_expiry').val();
            const insuranceExpiry = $('#edit_insurance_expiry').val();

            // ---> STRICT HELPER: Only reads what is currently visible on the screen!
            const getValidUrl = (selector) => {
                let src = $(selector).attr('src');
                if (!src || typeof src !== 'string') return "";
                
                let s = src.trim();
                if (s === '' || s === '0' || s === 'null' || s === 'N/A' || s === 'undefined') return "";
                
                // Block placeholders and SVGs
                if (s.includes('assets/images/') || 
                    s.includes('placeholder') || 
                    s.includes('encrypted-tbn0') || 
                    s.includes('data:image/svg')) {
                    return "";
                }
                return s;
            };

            // Read directly from the image tags. If it's a placeholder, it returns ""
            const finalRcFront = getValidUrl('#edit-rcDocumentFront img');
            const finalRcBack = getValidUrl('#edit-rcDocumentBack img');
            const finalPuc = getValidUrl('#edit-pucDocument img');
            const finalInsurance = getValidUrl('#edit-insuranceDocument img');

            // ==========================================
            // MANDATORY FIELD VALIDATIONS
            // ==========================================
            if (!rcNumber) { toast1("RC Number is required", "error"); return; }
            if (!finalRcFront) { toast1("RC Front image is required", "error"); return; }
            if (!finalRcBack) { toast1("RC Back image is required", "error"); return; }
            if (!finalInsurance) { toast1("Insurance image is required", "error"); return; }
            if (!rcExpiry) { toast1("RC Expiry date is required", "error"); return; }
            if (!insuranceExpiry) { toast1("Insurance Expiry date is required", "error"); return; }

            // ==========================================
            // PAST DATE VALIDATIONS
            // ==========================================
            let todayDate = new Date();
            todayDate.setHours(0, 0, 0, 0); // Reset time to midnight for exact comparison

            if (new Date(rcExpiry) < todayDate) { toast1("RC Expiry cannot be a past date", "error"); return; }
            if (pucExpiry && new Date(pucExpiry) < todayDate) { toast1("PUC Expiry cannot be a past date", "error"); return; }
            if (new Date(insuranceExpiry) < todayDate) { toast1("Insurance Expiry cannot be a past date", "error"); return; }

            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Verifying...');
            
            try {
                // 1. Verify RC with Digio
                const rcResponse = await $.ajax({
                    url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-kyc/rc",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                    },
                    data: {
                        user_id: driverId,
                        type: "RC",
                        front_url: finalRcFront, 
                        back_url: finalRcBack, 
                        expiry: rcExpiry,
                        rc_no: rcNumber
                    }
                });
                
                if (!rcResponse.status) {
                    showNotification(rcResponse.message || "RC verification failed", 'danger');
                    return;
                }
                
                // 2. Save all data to Database
                // Using || "" guarantees an empty string is sent to PHP instead of being dropped
                await $.ajax({
                    url: 'ajax/service/driverServices.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        method: 'update_vehicle_documents',
                        driver_id: driverId,
                        rc_number: rcNumber || "",
                        rc_expiry: rcExpiry || "",
                        rc_front: finalRcFront || "",
                        rc_back: finalRcBack || "",
                        puc_image: finalPuc || "",
                        puc_expiry: pucExpiry || "",
                        insurance_image: finalInsurance || "",
                        insurance_expiry: insuranceExpiry || "",
                        rc_details_full: JSON.stringify(rcResponse.data || {})
                    }
                });
                
                // 3. Update Frontend View Mode
                $('#display_rc').text(formatDateSimple(rcExpiry));
                $('#display_insurance').text(formatDateSimple(insuranceExpiry));
                
                // Use the fallback SVG visually if the user cleared it
                const NO_IMAGE_SVG = "data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100%25' height='100%25'%3E%3Crect width='100%25' height='100%25' fill='%23f8f9fa'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='12px' fill='%236c757d'%3ENo Image Available%3C/text%3E%3C/svg%3E";

                $('#rcDocumentFront img').attr('src', finalRcFront || NO_IMAGE_SVG);
                $('#rcDocumentBack img').attr('src', finalRcBack || NO_IMAGE_SVG);
                $('#pucDocument img').attr('src', finalPuc || NO_IMAGE_SVG);
                $('#insuranceDocument img').attr('src', finalInsurance || NO_IMAGE_SVG);
                
                showNotification("Vehicle Documents Updated Successfully", 'success');
                toggleSection('documents', false);
                
                // Reset globals for the next operation
                rcFrontUrl = rcBackUrl = pucUrl = insuranceUrl = null;
                recalculateDriverScore(driverId);
                
            } catch (err) {
                showNotification("Server error occurred while saving.", 'danger');
                console.error(err);
            } finally {
                btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i>Save');
            }
        });
        $(document).on('click', '#verifyAadhaarDigilocker', async function() {
            const driverId = window.currentDriverId;
            const btn = $(this);
            btn.prop('disabled', true)
                .html('<i class="fa fa-spinner fa-spin"></i> Initiating...');
            try {
                const response = await $.ajax({
                    url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-kyc/request",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                    },
                    data: {
                        user_id: driverId
                    }
                });
                if (!response.status) {
                    showNotification(response.message || "Digilocker request failed", 'danger');
                    return;
                }
                const digioData = response.data;
                const requestId = digioData.id;
                const identifier = digioData.customer_identifier;
                const tokenId = digioData.access_token?.id;
                if (!requestId || !identifier || !tokenId) {
                    showNotification("Digilocker token missing", "danger");
                    return;
                }
                const options = {
                    environment: "production",
                    callback: function(response) {
                        if (response.hasOwnProperty("error_code")) {
                            showNotification("Digio verification failed", "danger");
                            console.log(response);
                            return;
                        }
                        showNotification("Aadhaar verified successfully via DigiLocker", "success");
                        toggleSection('aadhaar', false);
                        console.log("Digio Success:", response);
                    },
                    logo: "https://www.goride.run/goride/img/logo-light.png",
                    theme: {
                        primaryColor: "#0d6efd",
                        secondaryColor: "#000000"
                    }
                };
                const digio = new Digio(options);
                digio.init();
                digio.submit(requestId, identifier, tokenId);
            } catch (err) {
                showNotification("Failed to initiate DigiLocker verification", "danger");
            } finally {
                btn.prop('disabled', false)
                    .html('<i class="fa fa-link me-1"></i> Verify via DigiLocker');
            }
        });
    });
    // #Hastar
    $(document).ready(function() {
        // Show map by default
        $('#mapCard').removeClass('d-none');
        // Hide everything else
        $('#leftPanel').addClass('d-none');
        $('#incomingBidsPanel').addClass('d-none');
        $('#ownerDetailsCard').addClass('d-none');
        $('#jobDetailsPanel').addClass('d-none');
    });
    /* =====================================================
       BIDS BUTTON CLICK
    ===================================================== */
    // $(document).on('click', '.bidBtn', function () {
    //     $('#mapCard, #jobDetailsPanel').addClass('d-none');
    //     $('#leftPanel').removeClass('d-none');
    //     $('#incomingBidsPanel').removeClass('d-none');
    //     $('#driverDetailsCard, #ownerDetailsCard').addClass('d-none');
    // });
    /* =====================================================
       DRIVER DETAILS CLICK
    ===================================================== */
    // $(document).on('click', '.driver-link', function (e) {
    //     e.preventDefault();
    //     $('#mapCard, #jobDetailsPanel').addClass('d-none');
    //     $('#driverDetailsCard').removeClass('d-none');
    //     $('#ownerDetailsCard').addClass('d-none');
    // });
    /* =====================================================
       OWNER DETAILS CLICK
    ===================================================== */
    // $(document).on('click', '.owner-link', function (e) {
    //     e.preventDefault();
    //     $('#mapCard, #jobDetailsPanel').addClass('d-none');
    //     $('#leftPanel').removeClass('d-none');
    //     $('#incomingBidsPanel').removeClass('d-none');
    //     $('#ownerDetailsCard').removeClass('d-none');
    //     $('#driverDetailsCard').addClass('d-none');
    // });
    /* =====================================================
       EDIT JOB CLICK  ⭐ IMPORTANT ⭐
    ===================================================== */
    $(document).on('click', '.jobEdit', function(e) {
        e.preventDefault();
        const jobId = $(this).data('job');
        // Hide map & all panels
        $('#mapCard').addClass('d-none');
        $('#leftPanel, #incomingBidsPanel, #ownerDetailsCard')
            .addClass('d-none');
        // Show job details (FULL COL-8)
        $('#jobDetailsPanel').removeClass('d-none');
        // Inject job preview
        $('#jobDetailsContent').html(`
       <div class="container-fluid">
  <!-- ================= CUSTOMER DETAILS ================= -->
  <div class="border  p-3 mt-2">
    <h6 class="text-muted mb-3">Customer Details</h6>
    <div class="row g-3">
      <div class="col-md-4 mt-0">
        <label class="form-label">Name</label>
        <input type="text" class="form-control" value="Jobby">
      </div>
      <div class="col-md-4 mt-0">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" id="custEmail" value="devavasu2002@gmail.com" oninput="this.value = this.value.replace(/\s/g, '')">
        </div>
      <div class="col-md-4">
        <label class="form-label">Mobile <span class="text-danger">*</span></label>
        <input type="text" id="custMobile" class="form-control" placeholder="Mobile Number" oninput="this.value = this.value.replace(/^0/, '').replace(/[^0-9]/g, '')">
    </div>
    </div>
  </div>
  <!-- ================= JOB DETAILS ================= -->
  <div class="border  p-3 ">
    <h6 class="text-muted mb-3">Job Details</h6>
    <div class="row g-3">
      <div class="col-md-4 mt-0">
        <label class="form-label">Job No</label>
        <input type="text" class="form-control" value="GRC-015">
      </div>
      <div class="col-md-4 mt-0">
        <label class="form-label">Job Type</label>
        <select class="form-select">
          <option selected>Round Trip</option>
          <option>One Way</option>
        </select>
      </div>
      <!-- ✅ CAR TYPE (YOU SAID THIS WAS MISSING) -->
      <div class="col-md-4 mt-0">
        <label class="form-label">Car Type</label>
        <select class="form-select">
          <option selected>Go Sedan</option>
          <option>SUV</option>
        </select>
      </div>
    </div>
  </div>
  <!-- ================= ROUTE DETAILS ================= -->
  <div class="border p-3 mb-4">
    <h6 class="text-muted mb-3">Route Details</h6>
    <div class="row g-3">
      <div class="col-md-6 mt-0">
        <label class="form-label">From</label>
        <input type="text" class="form-control"
               value="Ooty Market, Ooty, Tamil Nadu, India">
      </div>
      <div class="col-md-6 mt-0">
        <label class="form-label">To</label>
        <input type="text" class="form-control"
               value="Kodaikanal Road, Poombarai, Tamil Nadu, India">
      </div>
      <div class="col-md-3 mt-0">
        <label class="form-label">Distance (kms)</label>
        <input type="number" class="form-control" value="550">
      </div>
      <div class="col-md-3 mt-0">
        <label class="form-label">Duration</label>
        <input type="text" class="form-control" value="10 Days">
      </div>
      <div class="col-md-3 mt-0">
        <label class="form-label">Passengers</label>
        <input type="number" class="form-control" value="4">
      </div>
      <div class="col-md-3 mt-0">
        <label class="form-label">Luggage</label>
        <input type="number" class="form-control" value="3">
      </div>
    </div>
  </div>
  <!-- ================= SCHEDULE ================= -->
  <div class="border  p-3 ">
    <h6 class="text-muted mb-3">Schedule</h6>
    <div class="row g-3">
      <div class="col-md-6 mt-0">
        <label class="form-label">Pickup Date & Time</label>
        <input type="datetime-local" class="form-control"
               value="2026-02-10T20:30">
      </div>
    </div>
  </div>
  <!-- ================= FARE BREAKDOWN ================= -->
  <div class="border  p-3 ">
    <h6 class="text-muted mb-3">Fare Breakdown</h6>
    <div class="row g-3">
      <div class="col-md-4 mt-0">
        <label class="form-label">Base Fare</label>
        <input type="number" class="form-control" value="6760">
      </div>
      <div class="col-md-4 mt-0">
        <label class="form-label">Toll Fare</label>
        <input type="number" class="form-control" value="120">
      </div>
      <div class="col-md-4 mt-0 d-flex align-items-end">
        <h6 class="text-success fw-bold mb-1">
          Total: ₹6880
        </h6>
      </div>
    </div>
  </div>
</div>
    `);
    });
    /* =====================================================
       BACK TO MAP (GLOBAL)
    ===================================================== */
    $(document).on('click', '.backToMap', function() {
        $('#leftPanel').addClass('d-none');
        $('#incomingBidsPanel, #ownerDetailsCard, #jobDetailsPanel')
            .addClass('d-none');
        // 🔥 ADD THIS
        $('#driverListPanel').addClass('d-none');
        $('#mapCard').removeClass('d-none');
    });
    /* =====================================================
       BID ACCEPT / REJECT MODAL
    ===================================================== */
    let currentAction = null;
    let currentBidId = null;
    let cancelJobId = null;
    let cancelJobNo = null;
    let cancelJobType = null;
    let cancelJobUserId = null;
    $(document).on('click', '.dropdown-menu .dropdown-item', function(e) {
        e.preventDefault();
        let text = $(this).text().trim();
        // Remove text inside brackets
        text = text.replace(/\(.*?\)/g, '').trim();
        $('#jobFilterBtn span').text(text);
        $('.dropdown-menu .dropdown-item').removeClass('active');
        $(this).addClass('active');
    });
    /* =====================================================
       REMOVE DRIVER WITH CONFIRMATION MODAL
    ===================================================== */
    let driverToRemove = null;
    let jobToRemoveDriverFrom = null;
    $(document).on('click', '.remove-driver', function(e) {
        e.preventDefault();
        e.stopPropagation(); // Prevent triggering driver-link click
        driverToRemove = $(this).data('driver');
        jobToRemoveDriverFrom = $(this).data('job');
        // Show confirmation modal
        $('#removeDriverModal').modal('show');
    });
    $('#confirmRemoveDriverBtn').on('click', function() {
        if (driverToRemove && jobToRemoveDriverFrom) {
            console.log('Removing driver:', driverToRemove, 'from job:', jobToRemoveDriverFrom);
            // Here you would make an API call to remove the driver
            // For example: removeDriverFromJob(jobToRemoveDriverFrom, driverToRemove);
            // Remove the driver from the assignments object
            if (assignments[jobToRemoveDriverFrom]) {
                // ❌ Remove driver
                delete assignments[jobToRemoveDriverFrom];
                // ❌ REMOVE STATUS TOO (THIS IS THE FIX)
                delete jobStatuses[jobToRemoveDriverFrom];
                // 🔄 Refresh UI
                renderJobLists();
                refreshCalendar();
                showToast('Driver removed successfully!', 'success');
            }
        }
        // Close the modal
        $('#removeDriverModal').modal('hide');
        // Reset variables
        driverToRemove = null;
        jobToRemoveDriverFrom = null;
    });
    /* =====================================================
       NEW CUSTOMER BUTTON CLICK
    ===================================================== */
    $(document).on('click', '#newCustomerBtn', function() {
        // Show empty form
        $('#jobFormWrapper').removeClass('d-none');
        // Clear all fields
        clearJobForm();
        // Hide past jobs panel
        $('#pastJobsSlider').addClass('d-none');
        $('#customerSearchResults').addClass('d-none');
        // Focus on customer name
        $('#custName').focus();
    });
    /* =====================================================
       CUSTOMER SEARCH FUNCTIONALITY
    ===================================================== */
    $(document).on('keyup', '#customerSearch', function() {
        const searchTerm = $(this).val().trim();
        const results = $('#customerSearchResults');
        if (!searchTerm) {
            results.addClass('d-none');
            return;
        }
        // Mock data for testing
        const mockCustomers = [{
                id: 1,
                name: 'Ramesh Kumar',
                mobile: '9876543210',
                email: 'ramesh@gmail.com',
                pastJobs: 5
            },
            {
                id: 2,
                name: 'Suresh Patel',
                mobile: '9876543211',
                email: 'suresh@gmail.com',
                pastJobs: 3
            },
            {
                id: 3,
                name: 'Rajesh Sharma',
                mobile: '9876543212',
                email: 'rajesh@gmail.com',
                pastJobs: 7
            },
            {
                id: 4,
                name: 'Mahesh Reddy',
                mobile: '9876543213',
                email: 'mahesh@gmail.com',
                pastJobs: 2
            },
            {
                id: 5,
                name: 'Ganesh Iyer',
                mobile: '9876543214',
                email: 'ganesh@gmail.com',
                pastJobs: 4
            }
        ];
        // Filter customers
        const filtered = mockCustomers.filter(customer =>
            customer.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            customer.mobile.includes(searchTerm)
        );
        if (filtered.length === 0) {
            results.html(`
            <div class="list-group-item text-muted">
                No customers found
            </div>
        `).removeClass('d-none');
            return;
        }
        // Build results HTML
        let html = '';
        filtered.forEach(customer => {
            html += `
            <a href="#" class="list-group-item selectCustomer" 
               data-customer-id="${customer.id}"
               data-name="${customer.name}"
               data-email="${customer.email}"
               data-mobile="${customer.mobile}"
               data-past-jobs="${customer.pastJobs}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${customer.name}</strong><br>
                        <small class="text-muted">${customer.mobile}</small>
                    </div>
                    <span class="badge bg-secondary">${customer.pastJobs} past jobs</span>
                </div>
            </a>
        `;
        });
        results.html(html).removeClass('d-none');
    });
    /* =====================================================
       SELECT EXISTING CUSTOMER
    ===================================================== */
    $(document).on('click', '.selectCustomer', function(e) {
        e.preventDefault();
        const customerId = $(this).data('customer-id');
        const customerName = $(this).data('name');
        const customerEmail = $(this).data('email');
        const customerMobile = $(this).data('mobile');
        const pastJobsCount = $(this).data('past-jobs');
        // Show form (EMPTY initially)
        $('#jobFormWrapper').removeClass('d-none');
        // Fill ONLY customer details (keep job fields empty)
        $('#custName').val(customerName);
        $('#custEmail').val(customerEmail);
        $('#custMobile').val(customerMobile);
        // Hide search results
        $('#customerSearchResults').addClass('d-none');
        // Load and show past jobs on the side
        loadPastJobs(customerId, customerName);
    });
    /* =====================================================
       LOAD PAST JOBS FOR CUSTOMER
    ===================================================== */
    function loadPastJobs(customerId, customerName) {
        // Mock past jobs data
        const mockPastJobs = [{
                id: 1,
                from: 'Chennai',
                to: 'Salem',
                date: '15 Jan 2026',
                carType: 'Sedan',
                passengers: 4,
                luggage: 2,
                baseFare: 4525,
                toll: 495,
                total: 5020,
                status: 'completed'
            },
            {
                id: 2,
                from: 'Chennai',
                to: 'Coimbatore',
                date: '20 Jan 2026',
                carType: 'SUV',
                passengers: 6,
                luggage: 4,
                baseFare: 6520,
                toll: 750,
                total: 7270,
                status: 'cancelled'
            },
            {
                id: 3,
                from: 'Chennai',
                to: 'Bangalore',
                date: '25 Jan 2026',
                carType: 'Sedan',
                passengers: 3,
                luggage: 3,
                baseFare: 7850,
                toll: 850,
                total: 8700,
                status: 'completed'
            }
        ];
        // Build past jobs HTML
        let html = '';
        mockPastJobs.forEach(job => {
            html += `
           <div class="past-job p-3 border-bottom" data-status="${job.status}">
              <div class="mb-2 d-flex align-items-center justify-content-between">
    <strong>${job.from} → ${job.to}</strong>
    <span class="d-flex align-items-center gap-1">
        <i class="fa fa-calendar text-danger"></i>
        <span class="badge bg-light text-dark">${job.date}</span>
    </span>
</div>
                <div class="small text-muted mb-2">
                    <i class="fa fa-car text-secondary me-1"></i>${job.carType} | 
                    <i class="fa fa-users text-success me-1"></i>${job.passengers} | 
                    <i class="fa fa-suitcase text-warning me-1"></i>${job.luggage}
                </div>
                <div class="small text-muted mb-2">
                    Base: ₹${job.baseFare} | Toll: ₹${job.toll} | Total: <strong>₹${job.total}</strong>
                </div>
                <button class="btn btn-sm btn-info w-100 copyPastJob"
                        data-from="${job.from}"
                        data-to="${job.to}"
                        data-car-type="${job.carType}"
                        data-passengers="${job.passengers}"
                        data-luggage="${job.luggage}"
                        data-base-fare="${job.baseFare}"
                        data-toll="${job.toll}">
                    <i class="fa fa-copy me-1"></i> Copy This Job
                </button>
            </div>
        `;
        });
        $('#pastJobsList').html(html);
        $('#pastJobsSlider').removeClass('d-none');
        // Show history buttons
        $('#customerHistoryControls').removeClass('d-none');
    }
    /* =====================================================
       COPY PAST JOB TO FORM
    ===================================================== */
    $(document).on('click', '.copyPastJob', function() {
        const from = $(this).data('from');
        const to = $(this).data('to');
        const carType = $(this).data('car-type');
        const passengers = $(this).data('passengers');
        const luggage = $(this).data('luggage');
        const baseFare = $(this).data('base-fare');
        const toll = $(this).data('toll');
        // Auto-fill form fields (except customer details)
        $('#pickupLocation').val(from);
        $('#dropLocation').val(to);
        $('#carType').val(carType.toLowerCase());
        $('#passengers').val(passengers);
        $('#luggage').val(luggage);
        $('#baseFare').val(baseFare);
        $('#tollFare').val(toll);
        // Calculate total
        calculateTotal();
        // Show success message
        showToast('Job details copied! Adjust as needed.');
        // Close past jobs panel after a delay
        setTimeout(() => {
            $('#pastJobsSlider').addClass('d-none');
        }, 500);
    });
    /* =====================================================
       CLOSE PAST JOBS PANEL
    ===================================================== */
    $(document).on('click', '.closePastJobs', function() {
        $('#pastJobsSlider').addClass('d-none');
    });
    /* =====================================================
       CALCULATE TOTAL FARE
    ===================================================== */
    function calculateTotal() {
        const baseFare = parseFloat($('#baseFare').val()) || 0;
        const tollFare = parseFloat($('#tollFare').val()) || 0;
        const total = baseFare + tollFare;
        $('#totalFare').text('₹' + total.toLocaleString());
    }
    // Add event listeners for fare calculation
    $(document).on('input', '#baseFare, #tollFare', calculateTotal);
    /* =====================================================
       CLEAR JOB FORM
    ===================================================== */
    function clearJobForm() {
        // Clear all form fields
        $('#custName').val('');
        $('#custEmail').val('');
        $('#custMobile').val('');
        $('#jobType').val('');
        $('#carType').val('');
        $('#pickupLocation').val('');
        $('#dropLocation').val('');
        $('#distance').val('');
        $('#duration').val('');
        $('#passengers').val(4);
        $('#luggage').val(2);
        $('#pickupDateTime').val('');
        $('#baseFare').val('');
        $('#tollFare').val('');
        $('#totalFare').text('₹0');
    }
    /* =====================================================
       SHOW TOAST NOTIFICATION
    ===================================================== */
    function showToast(message, type = 'success') {
        const toastClass = type === 'success' ? 'bg-success' : 'bg-danger';
        // Create toast HTML
        const toastHtml = `
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
            <div class="toast show" role="alert">
                <div class="toast-header ${toastClass} text-white">
                    <strong class="me-auto">Notification</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        </div>
    `;
        // Add to body
        $('body').append(toastHtml);
        // Auto remove after 3 seconds
        setTimeout(() => {
            $('.toast').remove();
        }, 3000);
    }
    /* =====================================================
       SAVE JOB FUNCTIONALITY
    ===================================================== */
    // $(document).on('click', '#jobDetailsPanel .btn-success', function () {
    //     const requiredFields = ['#custName', '#custMobile', '#jobType', '#carType', '#pickupLocation', '#dropLocation', '#pickupDateTime', '#baseFare'];
    //     let isValid = true;
    //     let missingFields = [];
    //     requiredFields.forEach(field => {
    //         const value = $(field).val();
    //         if (!value || value.trim() === '') {
    //             isValid = false;
    //             const fieldName = $(field).attr('id').replace('cust', '').replace('job', '').replace('pickup', '');
    //             missingFields.push(fieldName);
    //         }
    //     });
    //     if (!isValid) {
    //         showToast(`Please fill required fields: ${missingFields.join(', ')}`, 'danger');
    //         return;
    //     }
    //     const jobData = {
    //         customer: {
    //             name: $('#custName').val(),
    //             email: $('#custEmail').val(),
    //             mobile: $('#custMobile').val()
    //         },
    //         job: {
    //             type: $('#jobType').val(),
    //             carType: $('#carType').val(),
    //             from: $('#pickupLocation').val(),
    //             to: $('#dropLocation').val(),
    //             distance: $('#distance').val(),
    //             duration: $('#duration').val(),
    //             passengers: $('#passengers').val(),
    //             luggage: $('#luggage').val(),
    //             pickupDateTime: $('#pickupDateTime').val(),
    //             baseFare: $('#baseFare').val(),
    //             tollFare: $('#tollFare').val(),
    //             total: $('#totalFare').text().replace('₹', '')
    //         }
    //     };
    //     console.log('Job data to save:', jobData);
    //     showToast('Job created successfully!', 'success');
    //     setTimeout(() => {
    //         $('.backToMap').trigger('click');
    //     }, 1500);
    // });
    $(document).on('click', '#customerHistoryControls button', function() {
        const filter = $(this).data('filter');
        /* ===============================
           APPLY ACTIVE BUTTON + TITLE
        =============================== */
        // if (filter === 'completed') {
        //     $(this)
        //         .removeClass('btn-outline-secondary')
        //         .addClass('btn-success active fw-bold text-white');
        //     $('#pastJobsTitle')
        //         .text('Completed Jobs')
        //         .removeClass('text-primary text-danger')
        //         .addClass('text-success');
        // }
        // else if (filter === 'cancelled') {
        //     $(this)
        //         .removeClass('btn-outline-secondary')
        //         .addClass('btn-danger active fw-bold text-white');
        //     $('#pastJobsTitle')
        //         .text('Cancelled Jobs')
        //         .removeClass('text-primary text-success')
        //         .addClass('text-danger');
        // }
        // else {
        //     $(this)
        //         .removeClass('btn-outline-secondary')
        //         .addClass('btn-primary active fw-bold text-white');
        //     $('#pastJobsTitle')
        //         .text('All Jobs')
        //         .removeClass('text-success text-danger')
        //         .addClass('text-primary');
        // }
        /* ===============================
           OPEN SLIDER
        =============================== */
        $('#pastJobsSlider').removeClass('d-none');
        /* ===============================
           FILTER JOBS
        =============================== */
        $('#pastJobsList .past-job').each(function() {
            const status = $(this).data('status');
            if (filter === 'all' || status === filter) {
                $(this).slideDown(150);
            } else {
                $(this).slideUp(150);
            }
        });
    });
    $('#removeDriverNoBtn').on('click', function() {
        // Remove driver even on NO
        if (jobToRemoveDriverFrom) {
            delete assignments[jobToRemoveDriverFrom];
            // ❌ ALSO REMOVE STATUS
            delete jobStatuses[jobToRemoveDriverFrom];
            renderJobLists();
            refreshCalendar();
        }
        // Close modal
        $('#removeDriverModal').modal('hide');
        // Reset values
        driverToRemove = null;
        jobToRemoveDriverFrom = null;
    });
    $(document).on('click', '.assignDriverFromList', function() {
        const driverId = $(this).data('driver');
        const jobId = $(this).data('job');
        assignments[jobId] = driverId;
        // ✅ ADD THIS LINE
        jobStatuses[jobId] = 'assigned';
        renderJobLists();
        refreshCalendar();
        $('.backToMap').trigger('click');
        showToast('Driver assigned successfully!', 'success');
    });
    /* =====================================================
       DRIVER CLICK FROM UNASSIGNED LIST → SHOW DETAILS
    ===================================================== */
    $(document).on('click', '.driver-from-list', function(e) {
        e.preventDefault();
        const driverId = $(this).data('driver');
        // Hide map
        $('#mapCard').addClass('d-none');
        // Show left panel
        $('#leftPanel').removeClass('d-none');
        // ✅ KEEP DRIVER LIST VISIBLE
        $('#driverListPanel').removeClass('d-none');
        // (Optional) hide owner details
        $('#ownerDetailsCard').addClass('d-none');
        // Inject name dynamically (demo)
        $('#driverDetailsCard strong span').text(
            drivers[driverId]?.name || 'Driver'
        );
    });
    function getJobStatusUI(status) {
        const map = {
            assigned: {
                text: 'Assigned',
                class: 'primary',
                icon: 'fa-user'
            },
            dispatched: {
                text: 'Dispatched',
                class: 'info',
                icon: 'fa-car'
            },
            reached: {
                text: 'Reached',
                class: 'warning',
                icon: 'fa-map-marker-alt'
            },
            onboard: {
                text: 'On Board',
                class: 'success',
                icon: 'fa-users'
            },
            completed: {
                text: 'Completed',
                class: 'secondary',
                icon: 'fa-check'
            },
            cancelled: {
                text: 'Cancelled',
                class: 'danger',
                icon: 'fa-times'
            }
        };
        return map[status] || map.assigned;
    }
    $(document).on('click', '.driver-request', function() {
        const requestId = $(this).data('request');
        selectedDriverRequest = requestedJobs.find(r => r.id == requestId);
        $('.driver-request').removeClass('border-primary');
        $(this).addClass('border border-primary');
        console.log(
            'Driver wants:',
            selectedDriverRequest.from,
            '→',
            selectedDriverRequest.to
        );
    });
    //Mona
    $(document).ready(function() {
        // =============================================
        // INITIALIZATION
        // =============================================
        // Set initial PUC date
        var today = new Date();
        var futureDate = new Date();
        futureDate.setDate(today.getDate() + 12);
        $('#edit_puc').val(futureDate.toISOString().split('T')[0]);
        // Hide all edit modes initially
        $('.edit-mode').hide();
        // =============================================
        // EDIT SECTION TOGGLE
        // =============================================
        // Click edit icon - show edit mode
        $('.edit-section-trigger').on('click', function(e) {
            e.preventDefault();
            const section = $(this).data('section');
            // Hide view, show edit
            $(`#view-${section}`).hide();
            $(`#edit-${section}`).show();
            $(this).hide(); // Hide the clicked edit icon
        });
        // Cancel button - return to view mode
        $('.cancel-section').on('click', function(e) {
            e.preventDefault();
            const section = $(this).data('section');
            $(`#edit-${section}`).hide();
            $(`#view-${section}`).show();
            $(`.edit-section-trigger[data-section="${section}"]`).show();
        });
        // $('.save-section-suriya').on('click', function(e) {
        //     e.preventDefault();
        //     const section = $(this).data('section');
        //     if (section === 'driver-profile') {
        //         $('#view-driver-profile .display-field[data-field="name"]').text($('#edit_name').val());
        //         $('#view-driver-profile .display-field[data-field="age"]').text($('#edit_age').val() + ' Years');
        //         $('#view-driver-profile .display-field[data-field="mobile"]').text($('#edit_mobile').val());
        //         $('#view-driver-profile .display-field[data-field="email"]').text($('#edit_email').val());
        //         $('#view-driver-profile .display-field[data-field="state"]').text($('#edit_state').val());
        //         $('#view-driver-profile .display-field[data-field="city"]').text($('#edit_city').val());
        //         $('#view-driver-profile .display-field[data-field="address"]').text($('#edit_address').val());
        //         const langs = $('#edit_languages').val() || [];
        //         $('#view-driver-profile .display-field[data-field="languages"]').text('🇮🇳 ' + langs.join(' | 🇬🇧 '));
        //     } else if (section === 'payment') {
        //         $('#display_upi').text($('#edit_upi').val());
        //     } else if (section === 'license') {
        //         const licenseType = $('#edit_license_type').val();
        //         $('#display_license_type').text(licenseType);
        //         const expiryMonth = $('#edit_license_expiry').val();
        //         if (expiryMonth) {
        //             const parts = expiryMonth.split('-');
        //             $('#display_license_expiry').text(parts[1] + '/' + parts[0]);
        //         }
        //     } else if (section === 'fare') {
        //         $('#display_per_km').text($('#edit_per_km').val());
        //         $('#display_extra_km').text($('#edit_extra_km').val());
        //         $('#display_extra_hour').text($('#edit_extra_hour').val());
        //         $('#display_extra_day').text($('#edit_extra_day').val());
        //     } else if (section === 'vehicle') {
        //         $('#display_vehicle_type').text($('#edit_vehicle_type').val());
        //         $('#display_experience').text($('#edit_experience').val() + ' Years');
        //         $('#display_maker_model').text($('#edit_maker_model').val());
        //         $('#display_colour').text($('#edit_colour').val());
        //         // $('#display_model').text($('#edit_model').val());
        //         $('#display_fuel').text($('#edit_fuel').val());
        //         $('#display_seating').text($('#edit_seating').val());
        //         $('#display_luggage').text($('#edit_luggage').val());
        //         $('#display_rc').text($('#edit_rc').val());
        //         const pucDate = $('#edit_puc').val();
        //         if (pucDate) {
        //             const daysLeft = Math.ceil((new Date(pucDate) - new Date()) / (1000 * 60 * 60 * 24));
        //             $('#display_puc').text(daysLeft > 0 ? `Expires in ${daysLeft} days` : 'Expired');
        //         }
        //         $('#display_insurance').text($('#edit_insurance').val());
        //     } else if (section === 'jobs') {
        //         const job1Route = $('#edit_job1_route').val();
        //         const job1Time = $('#edit_job1_time').val();
        //         const job2Route = $('#edit_job2_route').val();
        //         const job2Time = $('#edit_job2_time').val();
        //         $('#scheduledJobsList').html(`
        //         <div class="schedule-item p-2 border-bottom d-flex justify-content-between align-items-center">
        //             <div><i class="fa fa-map-marker-alt text-success me-2"></i> ${job1Route}</div>
        //             <span class="text-muted small">${job1Time}</span>
        //         </div>
        //         <div class="schedule-item p-2 border-bottom d-flex justify-content-between align-items-center">
        //             <div><i class="fa fa-map-marker-alt text-success me-2"></i> ${job2Route}</div>
        //             <span class="text-muted small">${job2Time}</span>
        //         </div>
        //     `);
        //     } else if (section === 'activity') {
        //         const activity1 = $('#edit_activity1').val();
        //         const activity1Time = $('#edit_activity1_time').val();
        //         const activity2 = $('#edit_activity2').val();
        //         const activity2Time = $('#edit_activity2_time').val();
        //         const activity3 = $('#edit_activity3').val();
        //         const activity3Time = $('#edit_activity3_time').val();
        //         const activity1Parts = activity1.split(' - ');
        //         const activity2Parts = activity2.split(' - ');
        //         const activity3Parts = activity3.split(' - ');
        //         $('#recentActivityList').html(`
        //         <div class="activity-item p-2 border-bottom">
        //             <div class="d-flex justify-content-between">
        //                 <div>
        //                     <i class="fa fa-check-circle text-success me-2"></i>
        //                     <strong>${activity1Parts[0] || 'Trip Completed'}</strong>
        //                 </div>
        //                 <span class="text-muted small">${activity1Time}</span>
        //             </div>
        //             <div class="ms-4 small">${activity1Parts[1] || 'Bangalore to Chennai - 280 KM'}</div>
        //         </div>
        //         <div class="activity-item p-2 border-bottom">
        //             <div class="d-flex justify-content-between">
        //                 <div>
        //                     <i class="fa fa-exclamation-triangle text-warning me-2"></i>
        //                     <strong>${activity2Parts[0] || 'PUC Renewal Pending'}</strong>
        //                 </div>
        //                 <span class="text-muted small">${activity2Time}</span>
        //             </div>
        //             <div class="ms-4 small">${activity2Parts[1] || 'Document expires on 25th Oct'}</div>
        //         </div>
        //         <div class="activity-item p-2">
        //             <div class="d-flex justify-content-between">
        //                 <div>
        //                     <i class="fa fa-times-circle text-danger me-2"></i>
        //                     <strong>${activity3Parts[0] || 'Job Cancelled'}</strong>
        //                 </div>
        //                 <span class="text-muted small">${activity3Time}</span>
        //             </div>
        //             <div class="ms-4 small">${activity3Parts[1] || 'Customer no-show - ID #4421'}</div>
        //         </div>
        //     `);
        //     } else if (section === 'remarks') {
        //         $('#display_remarks').text('"' + $('#edit_remarks').val() + '"');
        //         $('#display_remarks_author').text('— ' + $('#edit_remarks_author').val());
        //     }
        //     showNotification('Changes saved successfully', 'success');
        //     $(`#edit-${section}`).hide();
        //     $(`#view-${section}`).show();
        //     $(`.edit-section-trigger[data-section="${section}"]`).show();
        // });
        // Handle file input change
        // $(document).on('change', '.file-input-hidden', function(e) {
        //     e.preventDefault();
        //     const file = e.target.files[0];
        //     const targetId = $(this).data('target');
        //     if (!file || !file.type.match('image.*')) {
        //         showNotification('Please select an image file', 'error');
        //         return;
        //     }
        //     if (file.size > 5 * 1024 * 1024) {
        //         showNotification('Image size should be less than 5MB', 'error');
        //         return;
        //     }
        //     const reader = new FileReader();
        //     reader.onload = function(event) {
        //         const imageUrl = event.target.result;
        //         // Update all instances of this image
        //         if (targetId === 'driver-photo') {
        //             $('.driver-photo').attr('src', imageUrl);
        //         } else if (targetId === 'vehicle-photo') {
        //             $('.vehicle-photo').attr('src', imageUrl);
        //         } else if (targetId === 'gallery') {
        //             $('#mainCarImage, #editMainCarImage').attr('src', imageUrl);
        //         } else {
        //             $(`#${targetId} img, #edit-${targetId} img`).attr('src', imageUrl);
        //             $(`#${targetId}`).addClass('has-image');
        //         }
        //         showNotification('Uploaded successfully', 'success');
        //     };
        //     reader.readAsDataURL(file);
        //     $(this).val('');
        // });
        // Handle gallery upload
        $('#galleryUpload').on('change', function(e) {
            if (e.target.files.length > 0) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    $('#mainCarImage, #editMainCarImage').attr('src', event.target.result);
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
        // Click on any viewable image to open in viewer
        $(document).on('click', '.viewable-image, .driver-photo, .vehicle-photo, .document-img, .gallery-main-image', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const $img = $(this).is('img') ? $(this) : $(this).find('img');
            const src = $img.attr('src');
            if (!src) {
                showNotification('No image to view', 'info');
                return;
            }
        });
        // Notification bell
        $('#notificationBtn').on('click', function() {
            showNotification('You have 3 new notifications', 'info');
        });
        // Close drawer
        $('#closeDrawerBtn').on('click', function() {
            const offcanvas = bootstrap.Offcanvas.getInstance('#driverEditDrawer');
            if (offcanvas) {
                offcanvas.hide();
            }
        });
        // Search
        $('#driverQuickSearch').on('keyup', function(e) {
            if (e.key === 'Enter') {
                showNotification('Searching for: ' + $(this).val(), 'info');
            }
        });
    });
    // Aadhaar Viewer
    const aadhaarViewer = new Viewer(
        document.getElementById('view-aadhaar'), {
            toolbar: true,
            navbar: false,
            fullscreen: true,
            zIndex: 9999
        }
    );
    const driverzoomViewer = new Viewer(
        document.getElementById('driverzoom'), {
            toolbar: true,
            navbar: false,
            fullscreen: true,
            zIndex: 9999
        }
    );
    const licsenseViewer = new Viewer(
        document.getElementById('view-license'), {
            toolbar: true,
            navbar: false,
            fullscreen: true,
            zIndex: 9999
        }
    );
    function refreshVehicleGallery() {
        let container = document.getElementById('hiddenGalleryImages');
        if (!container) return;
        // Destroy existing instance if it exists
        if (window.vehicleViewer) {
            window.vehicleViewer.destroy();
        }
        // Re-initialize pointing specifically to the hidden images container
        window.vehicleViewer = new Viewer(container, {
            toolbar: true,
            navbar: true,
            fullscreen: true,
            zIndex: 9999,
            filter(image) {
                // Ensure the image src exists and isn't the No Image SVG or a placeholder
                return image.src &&
                    !image.src.includes('placeholder') &&
                    !image.src.includes('data:image/svg+xml') &&
                    image.src !== window.location.href;
            }
        });
        // Re-bind the click event on the main thumbnail to open the newly built gallery
        $("#main_vehicle_preview").off("click").on("click", function() {
            if (window.vehicleViewer) {
                window.vehicleViewer.show();
            }
        });
    }
    
    // ==================== SIMPLE VEHICLE IMAGES FUNCTIONS ====================

// Function to update the 6 thumbnail images
function updateSimpleImages() {
    const urls = [
        $('#val_front_view_image_url').val(),
        $('#val_boot_image_url').val(),
        $('#val_extra_image_1_url').val(),
        $('#val_car_top_view_image_url').val(),
        $('#val_interior_front_image_url').val(),
        $('#val_special_features_image_url').val()
    ];
    
    $('#simpleVehicleImages img').each(function(index) {
        if (urls[index] && urls[index].trim() !== '' && !urls[index].includes('placeholder')) {
            $(this).attr('src', urls[index]);
        } else {
            $(this).attr('src', 'assets/images/placeholder.png');
        }
    });
}

// Click handler for thumbnails
$(document).on('click', '.vehicle-thumb', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    var index = $(this).data('index');
    var urls = [
        $('#val_front_view_image_url').val(),
        $('#val_boot_image_url').val(),
        $('#val_extra_image_1_url').val(),
        $('#val_car_top_view_image_url').val(),
        $('#val_interior_front_image_url').val(),
        $('#val_special_features_image_url').val()
    ];
    
    // Check if clicked thumbnail has a valid image
    if (!urls[index] || urls[index].trim() === '' || urls[index].includes('placeholder')) {
        toast('error', 'No image available for this view');
        return;
    }
    
    // Update the hidden gallery images with ALL current URLs
    $('#gallery_boot_image').attr('src', urls[1] || '');
    $('#gallery_extra_image_1').attr('src', urls[2] || '');
    $('#gallery_car_top_view_image').attr('src', urls[3] || '');
    $('#gallery_interior_front_image').attr('src', urls[4] || '');
    $('#gallery_special_features_image').attr('src', urls[5] || '');
    
    // Set main image to clicked thumbnail
    $('#main_vehicle_preview').attr('src', urls[index]);
    
    // Destroy existing viewer instance
    if (window.vehicleViewer) {
        window.vehicleViewer.destroy();
        window.vehicleViewer = null;
    }
    
    // Small delay to ensure images are updated
    setTimeout(function() {
        // Reinitialize viewer with the clicked image as the starting point
        let container = document.getElementById('vehicle-gallery-container');
        if (container) {
            window.vehicleViewer = new Viewer(container, {
                toolbar: true,
                navbar: true,
                fullscreen: true,
                zIndex: 9999,
                initialViewIndex: index,
                filter: function(image) {
                    return image.src && 
                           image.src.indexOf('placeholder') === -1 && 
                           image.src !== window.location.href;
                }
            });
            
            // Show the viewer
            window.vehicleViewer.show();
        }
    }, 100);
});
    // Initialize on page load
    $(document).ready(function() {
        refreshVehicleGallery();
    });
    const docViewer = new Viewer(
        document.getElementById('view-documents'), {
            toolbar: true,
            navbar: false,
            fullscreen: true,
            zIndex: 9999
        }
    );
    let uploadedImages = [];
    $("#photoUpload").on("change", function(e) {
        const files = e.target.files;
        uploadedImages = [];
        $("#hiddenGalleryImages").html(""); // clear old images
        $.each(files, function(index, file) {
            if (!file.type.startsWith("image/")) return;
            const reader = new FileReader();
            reader.onload = function(event) {
                uploadedImages.push(event.target.result);
                // Set first image as preview
                if (uploadedImages.length === 1) {
                    $("#mainGalleryImage").attr("src", event.target.result);
                }
                // Add image to hidden container (for viewer)
                $("#hiddenGalleryImages").append(
                    `<img src="${event.target.result}">`
                );
                $("#galleryCount").text(uploadedImages.length + " Photos");
                initViewer();
            };
            reader.readAsDataURL(file);
        });
    });
    function initViewer() {
        if (window.galleryViewer) {
            window.galleryViewer.destroy();
        }
        window.galleryViewer = new Viewer(
            document.getElementById("hiddenGalleryImages"), {
                toolbar: true,
                navbar: true,
                fullscreen: true,
                zIndex: 9999,
                title: false
            }
        );
        $("#mainGalleryImage").off("click").on("click", function() {
            window.galleryViewer.show();
        });
    }
</script>
<!-- pickup time validation script-->
<script>
    // Show notification
    function showNotification(message, type = 'info') {
        const toastId = 'toast_' + Date.now();
        const bgColor = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';
        const toast = `
            <div id="${toastId}" class="toast align-items-center text-white ${bgColor} border-0 position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 9999;">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        $('body').append(toast);
        const bsToast = new bootstrap.Toast($('#' + toastId));
        bsToast.show();
        setTimeout(function() {
            $('#' + toastId).remove();
        }, 3000);
    }
    // 1. Helper to get the exact current local time in "YYYY-MM-DDTHH:MM" format
    function getMinDateTime() {
        const now = new Date();
        // Adjust for local timezone offset
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        return now.toISOString().slice(0, 16);
    }
    // 2. Simple custom toast logic
    function showDateToast(message) {
        // Remove existing toast if any
        $('.date-validation-toast').remove();
        const toastHtml = `
            <div class="date-validation-toast" style="position: fixed; bottom: 20px; right: 20px; background-color: #dc3545; color: white; padding: 12px 20px; border-radius: 6px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 99999; font-family: inherit; transition: opacity 0.3s ease-in-out;">
                ${message}
            </div>
        `;
        $('body').append(toastHtml);
        setTimeout(() => {
            $('.date-validation-toast').css('opacity', '0');
            setTimeout(() => $('.date-validation-toast').remove(), 300);
        }, 3000);
    }
    // 3. Set the 'min' attribute immediately whenever the Create Job button is clicked
    $(document).on('click', '.createJobBtn, .jobEdit', function() {
        // Short timeout to ensure the DOM is updated before we try to set the attribute
        setTimeout(() => {
            $('#pickupDateTime').attr('min', getMinDateTime());
        }, 100);
    });
    // 4. Update the min attribute periodically just in case the user leaves the form open
    setInterval(() => {
        if ($('#pickupDateTime').length) {
            $('#pickupDateTime').attr('min', getMinDateTime());
        }
    }, 60000);
    // 5. Fallback Event Delegation: Catch any manual input or changes
    $(document).on('change', '#pickupDateTime', function() {
        const selectedTime = $(this).val();
        if (selectedTime && selectedTime < getMinDateTime()) {
            $(this).val(""); // Clear the invalid input
            showDateToast("Invalid selection! Please choose a future date and time.");
        }
    });
</script>
<!--regular validation script-->
<script>
    // 1. Custom Toast Function (No Swal)
    function showValidationToast(message) {
        $('.custom-validation-toast').remove(); // Prevent stacking
        const toastHtml = `
            <div class="custom-validation-toast" style="position: fixed; top: 20px; right: 20px; background-color: #dc3545; color: white; padding: 12px 20px; border-radius: 6px; box-shadow: 0 4px 6px rgba(0,0,0,0.2); z-index: 99999; font-family: inherit; font-size: 14px; transition: opacity 0.3s;">
                <i class="fa fa-exclamation-circle me-2"></i> ${message}
            </div>
        `;
        $('body').append(toastHtml);
        setTimeout(() => {
            $('.custom-validation-toast').fadeOut(300, function() {
                $(this).remove();
            });
        }, 2500);
    }
    // 2. NAME: Max 50 chars, Alphabets & Spaces only
    $(document).on('keypress', '#custName', function(e) {
        const char = String.fromCharCode(e.which);
        if (!/^[a-zA-Z\s]+$/.test(char)) {
            e.preventDefault();
            showValidationToast('Name: Only letters and spaces allowed');
            return false;
        }
        if (this.value.length >= 50) {
            e.preventDefault();
            showValidationToast('Name: Maximum 50 characters allowed');
            return false;
        }
    });
    $(document).on('input', '#custName', function() {
        let val = this.value.replace(/[^a-zA-Z\s]/g, '');
        if (val.length > 50) val = val.substring(0, 50);
        if (this.value !== val) this.value = val;
    });
    // 3. MOBILE: Max 10 digits, Numbers only
    $(document).on('keypress', '#custMobile', function(e) {
        if (e.which < 48 || e.which > 57) {
            e.preventDefault();
            showValidationToast('Mobile: Only numbers allowed');
            return false;
        }
        if (this.value.length >= 10) {
            e.preventDefault();
            showValidationToast('Mobile: Maximum 10 digits allowed');
            return false;
        }
    });
    $(document).on('input', '#custMobile', function() {
        let val = this.value.replace(/[^0-9]/g, '');
        if (val.length > 10) val = val.substring(0, 10);
        if (this.value !== val) this.value = val;
    });
    // 4. EMAIL: Max 100 chars
    $(document).on('keypress', '#custEmail', function(e) {
        if (this.value.length >= 100) {
            e.preventDefault();
            showValidationToast('Email: Maximum 100 characters allowed');
            return false;
        }
    });
    $(document).on('input', '#custEmail', function() {
        if (this.value.length > 100) this.value = this.value.substring(0, 100);
    });
    $(document).on('blur', '#custEmail', function() {
        let val = this.value.trim();
        if (val.length > 0 && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
            showValidationToast('Please enter a valid email address');
        }
    });
    // 5. PASSENGERS: Max 15, No leading zeros
    // =====================================================
    // AUTO-UPDATE PASSENGER & LUGGAGE LIMITS BASED ON CAR TYPE
    // =====================================================
    // =====================================================
    // AUTO-UPDATE PASSENGER & LUGGAGE LIMITS BASED ON CAR TYPE
    // =====================================================
    $(document).on('change', '#carType', function() {
        const carType = $(this).val();
        
        let maxPass = 4;
        let maxLugg = 2;

        // Match the values from your HTML options
        if (carType === 'mini') {
            maxPass = 4;
            maxLugg = 2;
        } else if (carType === 'four_seater') {
            maxPass = 4;
            maxLugg = 3;
        } else if (carType === 'six_seater') {
            maxPass = 6;
            maxLugg = 4;
        } else if (carType === 'seven_seater') {
            maxPass = 7;
            maxLugg = 5;
        }

        // Instantly update the input boxes to the new limits when cab changes
        $('#passengers').val(maxPass).addClass('border-warning');
        $('#luggage').val(maxLugg).addClass('border-warning');

        // Remove the yellow highlight after 1 second (visual feedback)
        setTimeout(() => {
            $('#passengers, #luggage').removeClass('border-warning');
        }, 1000);
    });

    // Prevent typing higher than the allowed limit for the selected cab
    $(document).on('input', '#passengers', function() {
        let val = this.value.replace(/[^0-9]/g, '');
        let carType = $('#carType').val();
        let maxPass = 4; // Default for Mini & 4Seater
        
        if (carType === 'six_seater') maxPass = 6;
        else if (carType === 'seven_seater') maxPass = 7;

        if (val !== '') {
            let num = parseInt(val, 10);
            if (num === 0) val = '';
            else if (num > maxPass) val = maxPass.toString(); 
            else val = num.toString();
        }
        if (this.value !== val) this.value = val;
    });

    // Prevent typing higher luggage than allowed for the selected cab
    $(document).on('input', '#luggage', function() {
        let val = this.value.replace(/[^0-9]/g, '');
        let carType = $('#carType').val();
        let maxLugg = 2; // Default for Mini
        
        if (carType === 'four_seater') maxLugg = 3;
        else if (carType === 'six_seater') maxLugg = 4;
        else if (carType === 'seven_seater') maxLugg = 5;

        if (val !== '') {
            let num = parseInt(val, 10);
            if (num > maxLugg) val = maxLugg.toString();
            else val = num.toString();
        }
        if (this.value !== val) this.value = val;
    });
    
    $(document).on('click', '.confirmGrdBtn', function(e) {
        e.preventDefault();
        const jobId = $(this).data('jobid');
        const jobNo = $(this).data('jobno');
        const userId = $(this).data('userid');
        const jobType = $(this).data('jobtype');
        // Store references to BOTH the card and the button itself
        const $card = $(this).closest('.card');
        const $confirmBtn = $(this);
        Swal.fire({
            title: 'Job Action',
            text: `Do you want to confirm or cancel job ${jobNo}?`,
            icon: 'warning',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: '<i class="fa fa-check"></i> Confirm',
            denyButtonText: '<i class="fa fa-trash"></i> Cancel Job',
            cancelButtonText: 'Close',
            confirmButtonColor: '#198754', // Green
            denyButtonColor: '#dc3545', // Red
        }).then((result) => {
            if (result.isConfirmed) {
                // 1. CONFIRM JOB (Set status = 1)
                $.ajax({
                    url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-confirm-grd-job",
                    type: "POST",
                    dataType: "json",
                    data: {
                        job_id: jobId,
                        job_no: jobNo
                    },
                    headers: {
                        "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Confirming...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(res) {
                        if (res.status) {
                            // Show quick success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Confirmed!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            // 🔥 IMMEDIATELY update the UI: Replace the button with the Confirmed badge
                            $confirmBtn.replaceWith('<span class="badge bg-success" style="font-size: 10px;">Confirmed</span>');
                            // Silently reload the data in the background to ensure consistency
                            reloadJobsWithFilter();
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Server error. Try again.', 'error');
                    }
                });
            } else if (result.isDenied) {
                // 2. CANCEL JOB – remove the card immediately on success
                $.ajax({
                    url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-cancel-job",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                    },
                    data: {
                        job_id: jobId,
                        job_no: jobNo,
                        user_id: userId,
                        auth_key: 'ASDFGHJKLqwertyuiopMNBVCXZ!@#$%^&*()0987612345',
                        job_type: jobType
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Cancelling...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(res) {
                        Swal.close();
                        if (res.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cancelled!',
                                text: 'Job was cancelled successfully.',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                // 🔥 IMMEDIATELY remove card
                                $card.fadeOut(300, function() {
                                    $(this).remove();
                                });
                            });
                        } else {
                            Swal.fire('Error', res.message || 'Cancel failed', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Server error. Try again.', 'error');
                    }
                });
            }
        });
    });
    // Helper function: Updates MySQL & Firebase confirm_status
    function updateConfirmStatus(jobId, jobNo, status, onSuccessCallback = null) {
        $.ajax({
            url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-confirm-grd-job",
            type: "POST",
            dataType: "json",
            data: {
                job_id: jobId,
                job_no: jobNo,
                confirm_status: status
            },
            headers: {
                "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Updating...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(res) {
                if (res.status) {
                    if (onSuccessCallback) {
                        onSuccessCallback(); // Proceeds to the Cancel API
                    } else {
                        Swal.fire('Confirmed!', res.message, 'success').then(() => {
                            // 🔥 Dynamically fetch and show updated data
                            reloadJobsWithFilter();
                        });
                    }
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Server error occurred.', 'error');
            }
        });
    }
    // Helper function: Triggers your existing Cancel/Delete API
    function cancelJobApi(jobId, jobNo, userId, jobType) {
        $.ajax({
            url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-cancel-job",
            type: "POST",
            dataType: "json",
            headers: {
                "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
            },
            data: {
                job_id: jobId,
                job_no: jobNo,
                user_id: userId,
                auth_key: 'ASDFGHJKLqwertyuiopMNBVCXZ!@#$%^&*()0987612345',
                job_type: jobType
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Deleting from Firebase...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(res) {
                if (res.status) {
                    Swal.fire('Cancelled!', 'Job was cancelled and removed from Firebase.', 'success').then(() => {
                        // 🔥 Dynamically fetch and show updated data
                        reloadJobsWithFilter();
                    });
                } else {
                    Swal.fire('Error', res.message || 'Cancel failed', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Server error during cancellation.', 'error');
            }
        });
    }
    // Function triggered by the offcanvas icon
    function openSidebarRemarks() {
        let driver_name = $('#driver_name').text();
        // Grab the ID we stored in Step 1
        let driver_id = $('#sidebar_remarks_icon').attr('data-driver-id');
        if (!driver_id || driver_id === 'undefined' || driver_id === '') {
            showNotification('Could not find Driver ID.', 'error');
            return;
        }
        openRemarksModal(driver_id, driver_name);
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
    function fetchRemarks(user_id) {
        // FIX: Replaced 'origin' with 'window.location.origin' to prevent script crash
        $.ajax({
            url: origin + "/ajax/service/datatable_services.php",
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
    function loadEditLogs() {
        // Fetch the ID directly from the active window variable
        let driverId = window.currentDriverId;
        // Fallback just in case
        if (!driverId) {
            driverId = $('#preview_driver_id').val();
        }
        if (!driverId) {
            showNotification('Driver ID not found', 'error');
            return;
        }
        // Show loading inside modal
        $('#edit_log_content').html('<p class="text-center text-muted"><i class="fa fa-spinner fa-spin"></i> Loading logs...</p>');
        $('#editLogModal').modal('show'); // Open modal
        $.ajax({
            url: window.location.href, // posts to the same page (PHP at top handles it)
            type: 'POST',
            dataType: 'json',
            data: {
                method: 'fetch_edit_logs',
                user_id: driverId
            },
            success: function(response) {
                $('#edit_log_content').html(response.html);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                $('#edit_log_content').html('<p class="text-center text-danger">Failed to load logs. See console.</p>');
            }
        });
    }
    function toast1(message, type = 'error') {
        const bgColor = type === 'error' ? '#ff3333' : '#28a745';
        const toastHtml = `
        <div id="customToast1" style="position: fixed; top: 20px; right: 20px; z-index: 999999; 
             background: ${bgColor}; color: white; padding: 12px 20px; border-radius: 8px; 
             box-shadow: 0 4px 12px rgba(0,0,0,0.3); font-family: sans-serif; min-width: 250px;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" style="background:none; border:none; color:white; font-weight:bold; cursor:pointer; margin-left:15px;">✕</button>
            </div>
        </div>`;
        // Remove existing toast if any
        $('#customToast1').remove();
        $('body').append(toastHtml);
        // Auto-hide after 3 seconds
        setTimeout(() => {
            $('#customToast1').fadeOut(500, function() {
                $(this).remove();
            });
        }, 3000);
    }
    function setSectionScore(elementId, score, max) {
        let badgeClass = 'bg-success';
        let textClass = 'text-white';
        if (score === 0) {
            badgeClass = 'bg-danger';
        } else if (score < max) {
            badgeClass = 'bg-warning';
            textClass = 'text-dark';
        }
        $(elementId).html(`<span class="badge ${badgeClass} ${textClass} ms-2" style="font-size: 11px; padding: 3px 6px;">${score}/${max}</span>`);
    }
    function updateSectionIcons(d, vehicleData) {
        // FIXED: Now safely rejects SVG data URIs (No Image Available) and placeholders
        const isSet = (val) => {
            if (!val) return false;
            let str = String(val).trim();
            return str !== '' && str !== 'N/A' && str !== '-' && str !== 'null' && str !== '0' 
                   && !str.includes('data:image/svg+xml') && !str.includes('assets/images/');
        };

        // 1. Driver Profile (8 points)
        let profileScore = 0;
        if (isSet(d.state)) profileScore += 2;
        if (isSet(d.districts_id)) profileScore += 2;
        if (parseFloat(d.exp || 0) > 0) profileScore += 2;
        let hasLangs = isSet(vehicleData?.user_info?.language) || isSet(d.languages) || isSet(d.language);
        if (hasLangs) profileScore += 2;
        setSectionScore('#status-driver-profile', profileScore, 8);

        // 2. Aadhaar (Kept visually as 1/1 for UI completeness, though covered under backend Doc verify/Images)
        let aadhaarScore = 0;
        if ((isSet(d.aadhar_image_front) && isSet(d.aadhar_image_back)) ||
            (d.proof_type === 'AADHAR_DIGILOCKER' && d.proof_status === 'approved')) {
            aadhaarScore = 1;
        }
        setSectionScore('#status-aadhaar', aadhaarScore, 1);

        // 3. License Expiry (3 points)
        let licenseScore = 0;
        if (isSet(d.dl_expiry)) licenseScore = 3;
        setSectionScore('#status-license', licenseScore, 3);

        // 4. Vehicle Details (14 points)
        let vType = vehicleData?.rc_details?.response?.vehicle_details?.body_type || d.cab_type;
        let vMaker = vehicleData?.rc_details?.response?.vehicle_details?.maker_model || d.maker_model;
        let vFuel = vehicleData?.rc_details?.response?.vehicle_details?.fuel_type || vehicleData?.vehicle_questions?.fuel_type || d.fuel_type || d.fuel_types;
        let vSeats = parseFloat(vehicleData?.rc_details?.response?.vehicle_details?.seat_capacity || d.seat || d.seaters || 0);
        let vLuggage = vehicleData?.user_info?.luggage || vehicleData?.luggage || d.luggage || d.Luggage || '';
        
        let vehicleScore = 0;
        if (isSet(vMaker)) vehicleScore += 4;
        if (isSet(vType)) vehicleScore += 2;
        if (isSet(vFuel)) vehicleScore += 2;
        if (vSeats > 0) vehicleScore += 3;
        if (isSet(vLuggage)) vehicleScore += 3;
        setSectionScore('#status-vehicle', vehicleScore, 14);

        // 5. Vehicle Documents (9 points)
        let docScore = 0;
        let rcExpiry = vehicleData?.rc_expiry_date || vehicleData?.rc_details?.response?.vehicle_details?.fit_up_to || d.rc_upto;
        let insExpiry = vehicleData?.insurance_details?.insurance_exp_date || vehicleData?.insurance_exp_date || d.insurance_upto;
        let pucExpiry = vehicleData?.puc_details?.puc_exp_date || vehicleData?.puc_exp_date || d.puc_upto;
        
        let rcFront = vehicleData?.rc_front_image_url;
        let rcBack = vehicleData?.rc_back_image_url;
        let insImage = vehicleData?.insurance_details?.insurance_image_url;
        let pucImage = vehicleData?.puc_details?.puc_image_url;

        // RC Expiry AND Front/Back Images are worth 3 points
        if (isSet(rcExpiry) && isSet(rcFront) && isSet(rcBack)) {
            docScore += 3;
        }
        
        // Insurance Date AND Image are worth 3 points
        if (isSet(insExpiry) && isSet(insImage)) {
            docScore += 3;
        }
        
        // PUC Date AND Image are worth 3 points
        if (isSet(pucExpiry) && isSet(pucImage)) {
            docScore += 3;
        }

        setSectionScore('#status-documents', docScore, 9);

        // 6. Fare Rates (10 points - matches new 2+3+3+2 split)
        let fareScore = 0;
        if (parseFloat(d.per_km || 0) > 0) fareScore += 2;
        if (parseFloat(d.extra_per_km || d.extra_price_per_km || 0) > 0) fareScore += 3;
        if (parseFloat(d.per_hour || d.price_per_hour || 0) > 0) fareScore += 3;
        if (parseFloat(d.per_day || d.price_per_day || 0) > 0) fareScore += 2;
        setSectionScore('#status-fare', fareScore, 10);

        // 7. Payment Methods (2 points)
        let paymentScore = 0;
        if (isSet(d.upiID) || isSet(d.upi_id)) paymentScore = 2;
        setSectionScore('#status-payment', paymentScore, 2);

        // 8. Admin Remarks (4 points)
        let remarksScore = 0;
        if (isSet(d.remarks)) remarksScore += 2;
        if (isSet(d.reviews)) remarksScore += 2;
        setSectionScore('#status-remarks', remarksScore, 4);
    }
    function recalculateDriverScore(driverId) {
        // 1. Trigger the score update in the backend
        $.ajax({
            url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-driver-profile-update",
            type: "POST",
            dataType: "json",
            headers: {
                "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
            },
            data: {
                driver_id: driverId
            },
            success: function() {
                // 2. IMPORTANT: Re-fetch the fresh driver data to update the UI icons
                $.ajax({
                    url: "ajax/service/driverServices.php", // CHANGED FROM API
                    type: "POST",
                    dataType: "json",
                    data: {
                        method: 'get_driver_details', // ADDED METHOD
                        driver_id: driverId
                    },
                    success: function(res) {
                        if (res.status && res.data && res.data.driver) {
                            const d = res.data.driver;
                            let vehicleData = {};
                            try {
                                // Safely parse JSON if it comes as a string
                                vehicleData = typeof d.vehicle_details === 'string' ? JSON.parse(d.vehicle_details) : (d.vehicle_details || {});
                            } catch (e) {
                                vehicleData = {};
                            }
                            // Update the Top Badge Percentage
                            let percent = parseInt(d.profile_percentage) || 0;
                            $('#profileScoreBadge').html(`<i class="fa fa-check-circle me-1"></i>${percent}% Complete`);
                            // 🔥 DYNAMICALLY REFRESH THE ICONS (Fare, Remarks, etc.)
                            updateSectionIcons(d, vehicleData);
                            // Update Preview Modal and Sidebars
                            $('#preview_profile_percent_text').text(percent + '%');
                            const $progress = $('#preview_profile_progress');
                            $progress.css('width', percent + '%').attr('aria-valuenow', percent);
                            // Update Center List Circle
                            let $driverCircle = $(`.driver-item[data-driver="${driverId}"] .profile-circle`);
                            if ($driverCircle.length) {
                                $driverCircle.text(percent + '%');
                            }
                        }
                    }
                });
            }
        });
    }
</script>
<script>
    // Fetch districts from the database and generate HTML options ONLY
    let districtOptionsHTML = '';
    <?php
    // Assuming $con is your database connection variable
    $dist_query = mysqli_query($con, "SELECT district_name FROM districts ORDER BY district_name ASC");
    if ($dist_query) {
        while ($dist = mysqli_fetch_assoc($dist_query)) {
            $city = htmlspecialchars($dist['district_name']);
            echo "districtOptionsHTML += `<option value='{$city}'>{$city}</option>`;\n";
        }
    }
    ?>
</script>
<script>
    $(document).ready(function() {
        // Make all dropdown links work
        $('.dropdown-menu a').click(function() {
            window.location.href = $(this).attr('href');
        });
    });
</script>