<?php
// error_reporting(E_ALL); 
// ini_set('display_errors', 1);
?>
<style>
    .whatsapp-icon {
        font-size: 22px;
    }
    .phone-icon {
        font-size: 15px;
    }
    .chart-container {
        position: relative;
        height: 200px;
        overflow: hidden
    }
    .aed-agent {
        text-align: center
    }
    .card {
        margin-bottom: 20px;
    }
    #collectdiv .col-xl-3 .card {
        height: 90%;
    }
    .form-control:disabled,
    .form-control[readonly] {
        background-color: rgb(255 255 255 / 10%);
        */
    }
    input,
    select {
        border: 1px solid #CCC;
    }
    .nav.product-sale {
        position: unset;
        top: -3rem;
        right: 5px;
        margin: 12px 0;
    }
    input,
    button {
        height: 35px;
        margin: 0;
        padding: 6px 12px;
        border-radius: 2px;
        font-family: inherit;
        font-size: 100%;
        color: inherit;
    }
    .swal-modal {
        border: 3px solid white;
        color: #fff;
    }
    .swal-button {
        background-color: #07f3a2 !important;
    }
    .swal-text {
        font-weight: 600 !important;
    }
    .back-arrow-btn i {
        background: #ffffff;
        font-size: 16px;
        padding: 2px 3px;
        border-radius: 50px;
        border: 2px solid #6c6e70;
        color: #6c6e70;
        margin-right: 15px;
        width: 24px;
        height: 24px;
    }
    .required-indicator {
        color: red;
    }
    .dt-column-order {
        display: none;
    }
    table.dataTable th.dt-type-numeric,
    table.dataTable th.dt-type-date,
    table.dataTable td.dt-type-numeric,
    table.dataTable td.dt-type-date {
        text-align: left;
    }
    .form-control:disabled,
    .form-control[readonly] {
        background-color: rgb(255 255 255 / 10%);
    }
    input,
    select {
        border: 1px solid #CCC;
    }
    .text {
        float: unset !important;
    }
    .dropdown-item {
        display: block;
        width: 100%;
        padding: 0.25rem 1rem;
        clear: both;
        font-weight: 400;
        color: #121212 !important;
        text-align: inherit;
        text-decoration: none;
        white-space: nowrap;
        background-color: white;
        border: 0;
    }
    input,
    button {
        height: 35px;
        margin: 0;
        padding: 6px 12px;
        border-radius: 2px;
        font-family: inherit;
        font-size: 100%;
        color: inherit;
    }
    .back-arrow-btn i {
        background: #ffffff;
        font-size: 16px;
        padding: 2px 3px;
        border-radius: 50px;
        border: 2px solid #6c6e70;
        color: #6c6e70;
        margin-right: 15px;
        width: 24px;
        height: 24px;
    }
    .btn-light {
        background-color: #ffffff !important;
    }
    .dropdown.bootstrap-select {
        display: block !important;
        width: 100% !important;
    }
    .job-card-premium {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #eef0f4;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.04);
        padding: 16px 18px;
        transition: all 0.25s ease;
    }
    .job-card-premium:hover {
        box-shadow: 0 10px 26px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }
    .job-main {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .job-left {
        max-width: 75%;
    }
    .job-code {
        font-weight: 700;
        font-size: 15px;
        color: #1f3c88;
        margin-bottom: 4px;
    }
    .job-route {
        font-size: 13px;
        color: #4b5563;
        margin-bottom: 6px;
    }
    .job-route i {
        color: #0d6efd;
        margin-right: 4px;
    }
    .job-route .arrow {
        margin: 0 6px;
        font-weight: 600;
    }
    .job-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        font-size: 12px;
        color: #6b7280;
    }
    .job-meta i {
        margin-right: 4px;
    }
    .job-right {
        text-align: right;
        min-width: 110px;
    }
    .bid-badge {
        font-size: 12px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-block;
    }
    .bid-badge.has-bids {
        background: #e7f1ff;
        color: #0d6efd;
    }
    .bid-badge.has-amount {
        background: #ffe7e7;
        color: #e92646;
    }
    .bid-badge.no-bids {
        background: #f1f3f5;
        color: #6c757d;
    }
    .job-bids {
        margin-top: 14px;
        border-top: 1px dashed #e5e7eb;
        padding-top: 10px;
    }
    .job-list-scroll {
        max-height: 250px;
        overflow-y: auto;
        padding-right: 6px;
    }
    .job-list-scroll {
        scroll-behavior: smooth;
    }
    .job-list-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .job-list-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .job-list-scroll::-webkit-scrollbar-thumb {
        background-color: #cfd6e1;
        border-radius: 10px;
    }
    .job-list-scroll::-webkit-scrollbar-thumb:hover {
        background-color: #adb5bd;
    }
    .job-list-scroll {
        scrollbar-width: thin;
        scrollbar-color: #cfd6e1 transparent;
    }
    .bid-list-scroll {
        max-height: 220px;
        overflow-y: auto;
        padding-right: 6px;
    }
    .bid-list-scroll {
        scroll-behavior: smooth;
    }
    .bid-list-scroll::-webkit-scrollbar {
        width: 5px;
    }
    .bid-list-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .bid-list-scroll::-webkit-scrollbar-thumb {
        background-color: #cfd6e1;
        border-radius: 10px;
    }
    .bid-list-scroll::-webkit-scrollbar-thumb:hover {
        background-color: #adb5bd;
    }
    .bid-list-scroll {
        scrollbar-width: thin;
        scrollbar-color: #cfd6e1 transparent;
    }
</style>
<style>
    ::-webkit-scrollbar {
        width: 6px;
    }
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .iti {
        width: 100%;
    }
    .iti input {
        width: 100% !important;
    }
    
    .nav-tabs .nav-link {
        color: #4b5563;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        margin-right: 5px;
        border-radius: 8px 8px 0 0;
    }
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        background-color: #fff;
        border-bottom-color: transparent;
    }
</style>
<?php
$role_id = $_SESSION['userinfo']['roll_id'];
$u_id = $_SESSION['memid'];
?>
<div class="main-content app-content mt-0">
    <div class="side-app">
        <input type="hidden" id="tabID" value="agents">
        <div class="main-container container-fluid">
            <?php if ($role_id != 8) { ?>
                <div class="page-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <h1 class="page-title mb-0">Dashboard</h1>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-1">
                        <div class="d-flex gap-2">
                            <div class="card shadow-sm border-start border-3 border-primary" style="width:130px;">
                                <div class="card-body p-2 text-center">
                                    <small class="" style="color:black;">SMS</small><br>
                                    <span class="fw-bold text-primary" id="sms_count">0</span>
                                </div>
                            </div>
                            <div class="card shadow-sm border-start border-3 border-primary" style="width:130px;">
                                <div class="card-body p-2 text-center">
                                    <small class="" style="color:black;">Whatsapp</small><br>
                                    <span class="fw-bold text-primary" id="total_wh_count">0</span>
                                </div>
                            </div>
                            <div class="card shadow-sm border-start border-3 border-primary" style="width:130px;">
                                <div class="card-body p-2 text-center">
                                    <small class="text-muted">Promo DND</small><br>
                                    <span class="fw-bold text-primary" id="promo_count">0</span>
                                </div>
                            </div>
                            <div class="card shadow-sm border-start border-3 border-primary" style="width:130px;">
                                <div class="card-body p-2 text-center">
                                    <small class="text-muted">SMS Resell</small><br>
                                    <span class="fw-bold text-primary" id="sms_resell">0</span>
                                </div>
                            </div>
                        </div>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </div>
                </div>
                
                <style>
    /* Custom CSS to further enhance size and appearance */
    #dashboardTabs .nav-link {
        font-size: 17px !important;
        transition: all 0.3s ease;
        border: 1px solid #dee2e6;
        margin-right: 10px;
        border-radius: 8px;
        padding: 20px 30px;
    }
    #dashboardTabs .nav-link:hover{
        background:##696c85;
    }

    #dashboardTabs .nav-link i {
        font-size: 19px;
    }

    #dashboardTabs .nav-link.active {
        background-color:#696c85;
        color: white !important;
    }
</style>

<ul class="nav nav-tabs mt-3 mb-4 border-bottom-0" id="dashboardTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold  shadow-sm fs-5" id="driver-tab" data-bs-toggle="tab" data-bs-target="#driver-pane" type="button" role="tab" aria-controls="driver-pane" aria-selected="true">
            <i class="fas fa-car-side me-4"></i> Driver & Agency
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold  shadow-sm fs-5" id="customer-tab" data-bs-toggle="tab" data-bs-target="#customer-pane" type="button" role="tab" aria-controls="customer-pane" aria-selected="false">
             <i class="fas fa-users me-4"></i> Customer & Leads
        </button>
    </li>
</ul>
            <?php } ?>
        </div>

        <?php if ($role_id != 8) { ?>
        <div class="tab-content" id="dashboardTabsContent">
            <div class="tab-pane fade show active" id="driver-pane" role="tabpanel" aria-labelledby="driver-tab">
                <div class="row g-3 <?php echo ($role_id == 8) ? 'mt-5' : ''; ?>">
                    <style>
                        .job-card-clickable {
                            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
                            cursor: pointer;
                        }
                        .job-card-clickable:hover {
                            transform: translateY(-3px);
                            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
                        }
                        .job-stat-item {
                            font-size: 13px;
                            color: #6c757d;
                            white-space: nowrap;
                        }
                        .job-stat-val {
                            font-size: 16px;
                            font-weight: 900;
                            /* Extra bold/solid */
                            color: #212529;
                            margin-left: 4px;
                        }
                    </style>
                    <style>
                        /* Add a subtle hover effect so users know the stats are clickable */
                        .job-stat-link {
                            cursor: pointer;
                            transition: all 0.2s ease;
                            padding: 2px 4px;
                            border-radius: 4px;
                        }
                        .job-stat-link:hover {
                            background-color: #f8f9fa;
                            transform: translateY(-1px);
                        }
                    </style>
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-start border-4 h-100" style="border-color: #20c997;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0 cursor-pointer" style="color: #20c997; font-size: 15px;" onclick="window.location.href='https://console.goride.run/agencydashboard/'">
                                        <i class="bi bi-briefcase-fill me-1"></i> Job Details
                                    </h6>
                                    <div id="job_summary_daterange"
                                        style="cursor:pointer; background: #fff; border: 1px solid #e0e0e0; padding: 3px 8px; border-radius: 4px; font-size: 12px; color: #555;"
                                        onclick="event.stopPropagation();">
                                        <i class="bi bi-calendar3"></i>&nbsp;
                                        <span>Today</span>&nbsp;
                                        <i class="bi bi-caret-down-fill" style="font-size: 10px;"></i>
                                    </div>
                                </div>
                                <div class="mb-3 mt-2 d-flex align-items-center cursor-pointer" onclick="window.location.href='https://console.goride.run/agencydashboard/'">
                                    <span class="text-muted fw-semibold me-2" style="font-size: 14px;">Total Jobs :- </span>
                                    <span id="jd_total" class="text-dark" style="font-size: 26px; font-weight: 900; line-height: 1;">0</span>
                                </div>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="job-stat-item job-stat-link" style="min-width: 28%;" onclick="window.location.href='https://console.goride.run/agencydashboard/#unassigned'">
                                        Unassigned:<span id="jd_unassigned" class="job-stat-val text-primary fw-bold ms-1">0</span>
                                    </div>
                                    <div class="job-stat-item job-stat-link" style="min-width: 25%;" onclick="window.location.href='https://console.goride.run/agencydashboard/#assigned'">
                                        Assigned:<span id="jd_assigned" class="job-stat-val text-success fw-bold ms-1">0</span>
                                    </div>
                                    <div class="job-stat-item job-stat-link" style="min-width: 25%;" onclick="window.location.href='https://console.goride.run/agencydashboard/#websiteBookings'">
                                        Website:<span id="jd_website" class="job-stat-val text-warning fw-bold ms-1">0</span>
                                    </div>
                                    <div class="job-stat-item job-stat-link" style="min-width: 28%;" onclick="window.location.href='https://console.goride.run/agencydashboard/#cancelledJobs'">
                                        Cancelled:<span id="jd_cancelled" class="job-stat-val text-danger fw-bold ms-1">0</span>
                                    </div>
                                    <div class="job-stat-item job-stat-link" style="min-width: 25%;" onclick="window.location.href='https://console.goride.run/agencydashboard/#expiredJobs'">
                                        Expired:<span id="jd_expired" class="job-stat-val text-secondary fw-bold ms-1">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-start border-4 border-success">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-success fw-bold mb-1">KYC Pending</h6>
                                    <a href="kyc-details/"
                                        onclick="setCookie('isPending', true); setCookie('isVehicle', ''); setCookie('isNotComp', '');"
                                        id="kyc_p_count"
                                        class="fw-semibold mb-0"
                                        target="_blank" style="font-size: 25px">0</a>
                                </div>
                                <i class="bi bi-person-x fs-1 text-success"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-start border-4 border-info ">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class=" fw-bold mb-1" style="color:#5965f9;">KYC Not Completed</h6>
                                    <a href="kyc-details/"
                                        onclick="setCookie('isPending', ''); setCookie('isVehicle', ''); setCookie('isNotComp', true);  "
                                        id="kyc_nc_count"
                                        class="fw-semibold mb-0"
                                        target="_blank" style="font-size: 25px">0</a>
                                    <div class="d-flex gap-4 text-muted">
                                        <div>
                                            Selfie
                                            <span id="o_selfie" class="fw-bold text-dark">0</span>
                                        </div>
                                        <div>
                                            Aadhar
                                            <span id="o_aadhar" class="fw-bold text-dark">0</span>
                                        </div>
                                        <div>
                                            DL
                                            <span id="o_dl" class="fw-bold text-dark">0</span>
                                        </div>
                                        <div>
                                            GST
                                            <span id="o_gst" class="fw-bold text-dark">0</span>
                                        </div>
                                        <div>
                                            Others
                                            <span id="kyc_missing_count" class="fw-bold text-dark">0</span>
                                        </div>
                                    </div>
                                </div>
                                <i class="bi bi-person-dash fs-1" style="color:#5965f9;"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-start border-4 border-warning"
                            style="cursor: pointer; transition: all 0.3s ease;"
                            onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';"
                            onclick="window.location.href='https://console.goride.run/paymentstatushistory/'">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div class="w-100">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="fw-bold mb-0" style="color:#ff9800;">Payment History</h6>
                                        <div id="payment_daterange"
                                            style="cursor:pointer; background: #fff; border: 1px solid #e0e0e0; padding: 2px 6px; border-radius: 4px; font-size: 11px; color: #555; position: relative; z-index: 10;"
                                            onclick="event.stopPropagation();">
                                            <i class="bi bi-calendar3"></i>&nbsp;
                                            <span>Today</span>&nbsp;
                                            <i class="bi bi-caret-down-fill" style="font-size: 9px;"></i>
                                        </div>
                                    </div>
                                    <span id="pay_total_amount" class="fw-semibold d-block text-dark" style="font-size: 25px; line-height: 1.2;">
                                        ₹ 0.00
                                    </span>
                                    <div class="d-flex gap-3 text-muted mt-2" style="font-size: 13px;">
                                        <div>
                                            Wallet
                                            <span id="pay_wallet" class="fw-bold text-success">₹ 0.00</span>
                                        </div>
                                        <div>
                                            Purchase
                                            <span id="pay_purchase" class="fw-bold text-info">₹ 0.00</span>
                                        </div>
                                    </div>
                                </div>
                                <i class="bi bi-receipt fs-1 ms-2" style="color:#ff9800; opacity: 0.7;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-start border-4 border-secondary">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-secondary fw-bold mb-1">Vehicle Pending</h6>
                                    <a href="kyc-details/"
                                        onclick="setCookie('isPending', ''); setCookie('isVehicle', true); setCookie('isNotComp', '');"
                                        id="vehicle_p_count"
                                        class="fw-semibold d-block text-secondary"
                                        target="_blank"
                                        style="font-size: 25px; line-height: 1;">
                                        0
                                    </a>
                                    <div class="mt-2"></div>
                                    <div class="d-flex gap-4 text-muted">
                                        <div>
                                            Consent
                                            <span id="cons_count" class="fw-bold text-dark">0</span>
                                        </div>
                                        <div>
                                            Rejected
                                            <span id="sts_reject" class="fw-bold text-dark">0</span>
                                        </div>
                                    </div>
                                </div>
                                <i class="bi bi-truck fs-1 text-secondary"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-start border-4 border-warning">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-warning fw-bold mb-1">Available Jobs</h6>
                                    <a href="/joblist"
                                        onclick="setCookie('isNoComplete', true); setCookie('isExpired', ''); setCookie('isCancelled', '');"
                                        id="not_complete_count"
                                        class="fw-semibold mb-0"
                                        target="_blank" style="font-size: 25px">0</a>
                                </div>
                                <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-start border-4 border-danger">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-1" style="color:#d9462c;">Error Logs</h6>
                                    <a href="/errorlog"
                                        onclick="setCookie('isNoComplete', true); setCookie('isExpired', ''); setCookie('isCancelled', '');"
                                        id="error_logs_count"
                                        class="fw-semibold mb-0"
                                        target="_blank" style="font-size: 25px">0</a>
                                </div>
                                <i class="bi bi-exclamation-triangle fs-1" style="color:#d9462c;"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12 col-lg-4">
                        <div class="card shadow-sm border-start border-4 border-success">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-semibold mb-2 text-success">
                                        Verified Users
                                    </h6>
                                    <div class="d-flex gap-4 text-muted">
                                        <div>
                                            Driver:
                                            <span id="v_driver" class="fw-bold text-dark"></span>
                                        </div>
                                        <div>
                                            Owner:
                                            <span id="v_owner" class="fw-bold text-dark"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-success">
                                    <i class="bi bi-patch-check-fill fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-6">
                        <div class="card shadow-sm border-start border-4 border-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="fw-semibold text-success mb-0">
                                        User Verification Status
                                    </h6>
                                    <i class="bi bi-shield-check fs-3 text-success"></i>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="fw-semibold text-dark text-start mb-2">Drivers</div>
                                        <div class="d-flex justify-content-between text-muted">
                                            <div>
                                                KYC<br>
                                                <span id="d_kyc" class="fw-bold text-dark"></span>
                                            </div>
                                            <div>
                                                Vehicle<br>
                                                <span id="d_veh" class="fw-bold text-dark"></span>
                                            </div>
                                            <div>
                                                Both<br>
                                                <span id="d_both" class="fw-bold text-dark"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fw-semibold text-dark mb-2 text-center">Owners</div>
                                        <div class="text-muted text-center">
                                            KYC<br>
                                            <span id="o_kyc" class="fw-bold text-dark"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-6">
                        <div class="card shadow-sm border-start border-4 border-danger">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-semibold text-danger mb-0">
                                        Current Jobs
                                    </h6>
                                    <i class="bi bi-person-lines-fill fs-3 text-danger"></i>
                                </div>
                                <div class="job-list-scroll" id="jobsContainer">
                                    </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 d-none">
                        <div class="card shadow-sm border-start border-4 border-danger">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-danger fw-bold mb-1">Expired Jobs</h6>
                                    <a href="/joblist"
                                        onclick="setCookie('isNotComplete', ''); setCookie('isExpired', true); setCookie('isCancelled', '');"
                                        id="expiry_count"
                                        class="fw-semibold mb-0"
                                        target="_blank" style="font-size: 25px">0</a>
                                </div>
                                <i class="bi bi-clock-history fs-1 text-danger"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 d-none">
                        <div class="card shadow-sm border-start border-4 border-dark">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-dark fw-bold mb-1">Cancelled Jobs</h6>
                                    <a href="/joblist"
                                        onclick="setCookie('isNotComplete', ''); setCookie('isExpired', ''); setCookie('isCancelled', true);"
                                        id="cancelled_count"
                                        class="fw-semibold mb-0"
                                        target="_blank" style="font-size: 25px">0</a>
                                </div>
                                <i class="bi bi-x-octagon fs-1 text-dark"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Weekly KYC Verify Report - Driver</h4>
                                <div>
                                    </div>
                            </div>
                            <div class="card-body">
                                <div class="col-6 mb-2">
                                    <span>Select Date</span><span style="color:red;">*</span>
                                    <input class="form-control" type="text" id="datefilterVerify" name="datefilterVerify" readonly />
                                </div>
                                <div class="chart-container" style="position: relative; height: 200px;">
                                    <canvas id="saleschart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Weekly Vehicle Verify Report</h4>
                                <div>
                                    </div>
                            </div>
                            <div class="card-body">
                                <div class="col-6 mb-2">
                                    <span>Select Date</span><span style="color:red;">*</span>
                                    <input class="form-control" type="text" id="datefilterVehicle" name="datefilterVehicle" readonly />
                                </div>
                                <div class="chart-container" style="position: relative; height: 200px;">
                                    <canvas id="vehiclechart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Weekly Driver & Owner Report</h4>
                                <div>
                                    <span id="do_c"></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="col-6 mb-2">
                                    <span>Select Date</span><span style="color:red;">*</span>
                                    <input class="form-control" type="text" id="datefilterDriver" name="datefilterDriver" readonly />
                                </div>
                                <div class="chart-container" style="position: relative; height: 200px;">
                                    <canvas id="driverchart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Weekly Jobs Report</h4>
                                <div>
                                    <span id="j_c"></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="col-6 mb-2">
                                    <span>Select Date</span><span style="color:red;">*</span>
                                    <input class="form-control" type="text" id="datefilterJobs" name="datefilterJobs" readonly />
                                </div>
                                <div class="chart-container" style="position: relative; height: 200px;">
                                    <canvas id="jobschart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Driver Posted Jobs</h4>
                            </div>
                            <div class="card-body">
                                <div class="col-6 mb-2">
                                    <span>Select Date</span><span style="color:red;">*</span>
                                    <input class="form-control" type="text" id="datefilterDriverJobs" readonly />
                                </div>
                                <div class="chart-container" style="position: relative; height: 200px;">
                                    <canvas id="driverAppJobsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Customer Posted Jobs</h4>
                            </div>
                            <div class="card-body">
                                <div class="col-6 mb-2">
                                    <span>Select Date</span><span style="color:red;">*</span>
                                    <input class="form-control" type="text" id="datefilterCustomerJobs" readonly />
                                </div>
                                <div class="chart-container" style="position: relative; height: 200px;">
                                    <canvas id="customerAppJobsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Weekly KYC Verify Report - Owner</h4>
                                <div>
                                    </div>
                            </div>
                            <div class="card-body">
                                <div class="col-6 mb-2">
                                    <span>Select Date</span><span style="color:red;">*</span>
                                    <input class="form-control" type="text" id="datefilterOwner" name="datefilterOwner" readonly />
                                </div>
                                <div class="chart-container" style="position: relative; height: 200px;">
                                    <canvas id="ownerchart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mt-3">
                        <div class="card shadow-sm border-start border-4 border-primary">
                            <div class="card-body">
                                <div>
                                    <h6 class="fw-semibold text-primary mb-0">
                                        Our Cabs Count
                                        <div class="d-flex justify-content-end align-items-center gap-4 m-4">
                                            <div class="d-flex align-items-center gap-1">
                                                <span class="fw-bold text-dark">Updated</span>
                                                <span id="updated_count" class="text-muted">0</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-1">
                                                <span class="fw-bold text-dark">None</span>
                                                <span id="none_updated" class="text-muted">0</span>
                                            </div>
                                        </div>
                                    </h6>
                                </div>
                                <div class="row text-center">
                                    <div class="d-flex text-muted gap-4 fw-bold" id="cabCountContainer">
                                        <div>
                                            <span id="mini_count" class="fw-bold text-dark">Go Mini</span><br>
                                            <span id="mini_val" class="fw-bold text-dark">0</span>
                                        </div>
                                        <div>
                                            <span id="sedan_count" class="fw-bold text-dark">Go Sedan</span><br>
                                            <span id="sedan_val" class="fw-bold text-dark">0</span>
                                        </div>
                                        <div>
                                            <span id="prime_count" class="fw-bold text-dark">Go SUV</span><br>
                                            <span id="prime_val" class="fw-bold text-dark">0</span>
                                        </div>
                                        <div>
                                            <span id="primesuv_count" class="fw-bold text-dark">Go SUV Plus</span><br>
                                            <span id="primesuv_val" class="fw-bold text-dark">0</span>
                                        </div>
                                        <div>
                                            <span id="primepls_count" class="fw-bold text-dark">Go Executive</span><br>
                                            <span id="primepls_val" class="fw-bold text-dark">0</span>
                                        </div>
                                        <div>
                                            <span id="xl_count" class="fw-bold text-dark">Go Tourister</span><br>
                                            <span id="xl_val" class="fw-bold text-dark">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <div class="card shadow-sm border-start border-4 border-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-semibold mb-0" style="color:brown;">
                                        Seats Count
                                    </h6>
                                </div>
                                <div class="row text-center">
                                    <div class="d-flex justify-content-around align-items-center text-mute" id="seatCountContainer">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-12 d-flex align-items-center justify-content-between">
                                        <h4 class="card-title">Sign-up & Sign-in (7 Days)</h4>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <span>Select Date</span><span style="color:red;">*</span>
                                        <input class="form-control" type="text" id="datefilterLogin" name="datefilterLogin"
                                            value="" readonly />
                                    </div>
                                    <div class="col-3 mb-2">
                                        <span>Source</span>
                                        <select id="utm_campaign_source_login" onchange="loginChart()" class="form-select">
                                            <option value="" selected="">All</option>
                                            <?php
                                            $utm_campaign = mysqli_query($con, "SELECT utm_source FROM `login_logs` WHERE utm_source IS NOT null AND utm_source != '' GROUP BY utm_source;");
                                            if (mysqli_num_rows($utm_campaign) > 0) {
                                                while ($row = mysqli_fetch_assoc($utm_campaign)) {
                                            ?>
                                                    <option value="<?= $row['utm_source']; ?>"><?= $row['utm_source']; ?>
                                                    </option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-3 mb-2">
                                        <span>UTM</span>
                                        <select id="utm_campaign_login" onchange="loginChart()" class="form-select">
                                            <option value="" selected="">All</option>
                                            <?php
                                            $utm_campaign = mysqli_query($con, "SELECT utm_campaign FROM `login_logs` WHERE utm_campaign IS NOT null AND utm_campaign != '' GROUP BY utm_campaign;");
                                            if (mysqli_num_rows($utm_campaign) > 0) {
                                                while ($row = mysqli_fetch_assoc($utm_campaign)) {
                                            ?>
                                                    <option value="<?= $row['utm_campaign']; ?>"><?= $row['utm_campaign']; ?>
                                                    </option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="signupLoginChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="tab-pane fade" id="customer-pane" role="tabpanel" aria-labelledby="customer-tab">
    <div class="row g-3">
        
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-start border-4 h-100" style="border-color: #20c997;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0 cursor-pointer" style="color: #20c997; font-size: 15px;" onclick="window.location.href='https://console.goride.run/agencydashboard/'">
                            <i class="bi bi-briefcase-fill me-1"></i> Job Details
                        </h6>
                        <div id="job_summary_daterange_cus"
                            style="cursor:pointer; background: #fff; border: 1px solid #e0e0e0; padding: 3px 8px; border-radius: 4px; font-size: 12px; color: #555;"
                            onclick="event.stopPropagation();">
                            <i class="bi bi-calendar3"></i>&nbsp;
                            <span>Today</span>&nbsp;
                            <i class="bi bi-caret-down-fill" style="font-size: 10px;"></i>
                        </div>
                    </div>
                    <div class="mb-3 mt-2 d-flex align-items-center cursor-pointer" onclick="window.location.href='https://console.goride.run/agencydashboard/'">
                        <span class="text-muted fw-semibold me-2" style="font-size: 14px;">Total Jobs :- </span>
                        <span id="jd_total_cus" class="text-dark" style="font-size: 26px; font-weight: 900; line-height: 1;">0</span>
                    </div>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="job-stat-item job-stat-link" style="min-width: 28%;" onclick="window.location.href='https://console.goride.run/agencydashboard/#unassigned'">
                            Unassigned:<span id="jd_unassigned_cus" class="job-stat-val text-primary fw-bold ms-1">0</span>
                        </div>
                        <div class="job-stat-item job-stat-link" style="min-width: 25%;" onclick="window.location.href='https://console.goride.run/agencydashboard/#assigned'">
                            Assigned:<span id="jd_assigned_cus" class="job-stat-val text-success fw-bold ms-1">0</span>
                        </div>
                        <div class="job-stat-item job-stat-link" style="min-width: 25%;" onclick="window.location.href='https://console.goride.run/agencydashboard/#websiteBookings'">
                            Website:<span id="jd_website_cus" class="job-stat-val text-warning fw-bold ms-1">0</span>
                        </div>
                        <div class="job-stat-item job-stat-link" style="min-width: 28%;" onclick="window.location.href='https://console.goride.run/agencydashboard/#cancelledJobs'">
                            Cancelled:<span id="jd_cancelled_cus" class="job-stat-val text-danger fw-bold ms-1">0</span>
                        </div>
                        <div class="job-stat-item job-stat-link" style="min-width: 25%;" onclick="window.location.href='https://console.goride.run/agencydashboard/#expiredJobs'">
                            Expired:<span id="jd_expired_cus" class="job-stat-val text-secondary fw-bold ms-1">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-6 d-none">
            <div class="card shadow-sm border-start border-4 border-success">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-semibold mb-2 text-success">
                            Website Booking
                        </h6>
                        <div class="d-flex gap-4 text-muted">
                            <div>
                                Lead:
                                <span id="l_web" class="fw-bold text-dark">0</span>
                            </div>
                            <div>
                                Posted Jobs:
                                <span id="post_web" class="fw-bold text-dark">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-patch-check-fill fs-1"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-6">
            <div class="card shadow-sm border-start border-4 border-secondary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-semibold text-secondary mb-0">
                            Leads Status
                        </h6>
                        <i class="bi bi-diagram-3 fs-3 text-secondary"></i>
                    </div>
                    <div class="row align-items-center g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold mb-1">
                                Campaign Name <span class="text-danger">*</span>
                            </label>
                            <select id="utm_campaign" class="form-select" style="width: 50%;">
                                <option value="">Select Campaign Name</option>
                                <?php
                                $utm_campaign = mysqli_query(
                                    $con,
                                    "SELECT campaign_name 
                                     FROM goride_ad_leads 
                                     WHERE campaign_name IS NOT NULL 
                                     GROUP BY campaign_name"
                                );
                                while ($row = mysqli_fetch_assoc($utm_campaign)) {
                                ?>
                                    <option value="<?= htmlspecialchars($row['campaign_name']); ?>">
                                        <?= htmlspecialchars($row['campaign_name']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="fw-semibold mb-1">Lead</div>
                            <span id="ad_lead" class="fw-bold"></span>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="fw-semibold mb-1">Register</div>
                            <span id="ad_reg" class="fw-bold"></span>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="fw-semibold mb-1">KYC</div>
                            <span id="ad_kyc" class="fw-bold"></span>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="fw-semibold mb-1">Vehicle</div>
                            <span id="ad_veh" class="fw-bold"></span>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="fw-semibold mb-1">Driver</div>
                            <span id="ad_driver" class="fw-bold"></span>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="fw-semibold mb-1">Owner</div>
                            <span id="ad_owner" class="fw-bold"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12 d-flex align-items-center justify-content-between">
                            <h4 class="card-title">Customer App Date-wise Signups</h4>
                        </div>
                        <div class="col-12 mb-2">
                            <span>Select Date</span><span style="color:red;">*</span>
                            <input class="form-control" type="text" id="datefilterCustomerSignup" name="datefilterCustomerSignup" value="" readonly />
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="customerSignupChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div> </div>  <?php } ?>

        <div class="modal fade" id="takeActionModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Take Action</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="job_id">
                        <input type="hidden" id="user_id">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="job_action" value="confirm" id="confirmJob">
                            <label class="form-check-label" for="confirmJob">
                                Confirm the Job
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="job_action" value="delete" id="deleteJob">
                            <label class="form-check-label" for="deleteJob">
                                Delete the Job
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="submitJobAction">
                            Submit
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($role_id == 8) { ?>
            <div class="row g-3 mt-5">
                <div class="col-md-4 col-lg-3">
                    <div class="card shadow-sm border-start border-4 border-primary">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-primary fw-bold mb-1">Total Companies Created</h6>
                                <a href="#"
                                    id="total_comp_count"
                                    class="fw-semibold mb-0 text-decoration-none text-dark"
                                    style="font-size: 25px">0</a>
                            </div>
                            <i class="bi bi-building fs-1 text-primary-emphasis opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="card shadow-sm border-start border-4 border-info">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-info fw-bold mb-1">Total Companies (Month)</h6>
                                <a href="#"
                                    id="month_comp_count"
                                    class="fw-semibold mb-0 text-decoration-none text-dark"
                                    style="font-size: 25px">0</a>
                            </div>
                            <i class="bi bi-calendar-check fs-1 text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="card shadow-sm border-start border-4 border-primary">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-primary fw-bold mb-1">Total Drivers Created</h6>
                                <a href="#"
                                    id="total_driver_count"
                                    class="fw-semibold mb-0 text-decoration-none text-dark"
                                    style="font-size: 25px">0</a>
                            </div>
                            <i class="bi bi-building fs-1 text-primary-emphasis opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="card shadow-sm border-start border-4 border-info">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-info fw-bold mb-1">Total Drivers (Month)</h6>
                                <a href="#"
                                    id="month_driver_count"
                                    class="fw-semibold mb-0 text-decoration-none text-dark"
                                    style="font-size: 25px">0</a>
                            </div>
                            <i class="bi bi-calendar-check fs-1 text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 d-none">
                    <div class="card shadow-sm border-start border-4 border-success">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-success fw-bold mb-1">Total Vehicle Created</h6>
                                <a href="#"
                                    id="total_veh_count"
                                    class="fw-semibold mb-0 text-decoration-none text-dark"
                                    style="font-size: 25px">0</a>
                            </div>
                            <i class="bi bi-truck fs-1 text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 d-none">
                    <div class="card shadow-sm border-start border-4 border-warning">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-warning-emphasis fw-bold mb-1">Total Vehicle (Month)</h6>
                                <a href="#"
                                    id="month_veh_count"
                                    class="fw-semibold mb-0 text-decoration-none text-dark"
                                    style="font-size: 25px">0</a>
                            </div>
                            <i class="bi bi-speedometer2 fs-1 text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-3 mt-4">
                <div class="d-flex justify-content-end mt-4 mb-3">
                    <button class="btn btn-primary add-com-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#createOwnerModal">
                        Add Driver / Company
                    </button>
                </div>
                <div class="col-lg-3 col-md-6 d-flex flex-column">
                    <h5 class="mb-3">
                        <i class="bi bi-building text-primary me-2"></i>Company Details
                    </h5>
                    <div id="companyListWrapper" class="flex-grow-1" style="max-height: 520px; overflow-y: auto; padding-right: 5px;">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 d-flex flex-column">
                    <h5 class="mb-3">
                        <i class="bi bi-building text-primary me-2"></i>Driver Details
                    </h5>
                    <div id="driversListWrapper" class="flex-grow-1" style="max-height: 520px; overflow-y: auto; padding-right: 5px;">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 d-none flex-column">
                    <h6 class="mb-3 d-flex align-items-center fw-bold">
                        <i class="bi bi-truck text-primary me-2"></i> Vehicles under Company
                    </h6>
                    <div class="flex-grow-1" style="max-height: 520px; overflow-y: auto; padding-right: 5px;">
                        <div class="card shadow-sm border-0 mb-2 p-3">
                            <div class="d-flex justify-content-between">
                                <div class="bg-primary-subtle rounded p-2 d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="bi bi-truck text-primary"></i>
                                </div>
                                <small class="text-muted fw-bold">ID: VEH-001</small>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 9px;">Model</small>
                                    <p class="mb-2 fw-bold" style="font-size: 14px;">Tata Ace Gold</p>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 9px;">Type</small>
                                    <p class="mb-0" style="font-size: 13px;">Mini Truck</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 9px;">Registration</small>
                                    <p class="mb-2 fw-bold" style="font-size: 14px;">MH-12-AB-1234</p>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 9px;">Capacity</small>
                                    <p class="mb-0" style="font-size: 13px;">1.5 Tons</p>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-sm border-0 mb-2 p-3">
                            <div class="d-flex justify-content-between">
                                <div class="bg-primary-subtle rounded p-2 d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="bi bi-truck text-primary"></i>
                                </div>
                                <small class="text-muted fw-bold">ID: VEH-002</small>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 9px;">Model</small>
                                    <p class="mb-2 fw-bold" style="font-size: 14px;">Mahindra Supro</p>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 9px;">Type</small>
                                    <p class="mb-0" style="font-size: 13px;">Van</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 9px;">Registration</small>
                                    <p class="mb-2 fw-bold" style="font-size: 14px;">MH-12-CD-5678</p>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 9px;">Capacity</small>
                                    <p class="mb-0" style="font-size: 13px;">1.0 Tons</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-12 d-none flex-column">
                    <h6 class="mb-3 d-flex align-items-center fw-bold">
                        <i class="bi bi-person-badge text-primary me-2"></i> Driver Details for Vehicle 1
                    </h6>
                    <div class="card shadow-sm border-0 flex-grow-1 overflow-hidden">
                        <div class="bg-primary p-3 text-white d-flex align-items-center">
                            <div class="bg-white-50 rounded p-2 me-3" style="background: rgba(255,255,255,0.2);">
                                <i class="bi bi-person-square fs-2"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">Vikram Singh</h5>
                                <small class="opacity-75">Professional Driver</small>
                                <div class="mt-1">
                                    <span class="badge bg-white text-primary fw-bold me-1" style="font-size: 9px;">DL VERIFIED</span>
                                    <span class="badge bg-white text-primary fw-bold" style="font-size: 9px;">KYC DONE</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 9px;">License Number</small>
                                    <p class="fw-bold mb-0" style="font-size: 13px;">DL-1420230005678</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 9px;">License Expiry</small>
                                    <p class="fw-bold mb-0" style="font-size: 13px;">12 Oct 2030</p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 9px;">Primary Contact</small>
                                    <p class="fw-bold mb-0" style="font-size: 13px;">+91 77665 54433</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 9px;">Residential Address</small>
                                    <p class="text-muted mb-0" style="font-size: 11px; line-height: 1.2;">Flat 402, Lotus Greens, Sector 15, Pune, Maharashtra - 411001</p>
                                </div>
                            </div>
                            <hr class="text-muted opacity-25">
                            <small class="text-muted text-uppercase fw-bold d-block mb-2" style="font-size: 9px;">Verification Documents</small>
                            <div class="d-flex gap-2">
                                <div class="border p-2 d-flex align-items-center flex-fill" style="background: #f8f9fa;">
                                    <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                    <span style="font-size: 12px;">Aadhar Card</span>
                                </div>
                                <div class="border p-2 d-flex align-items-center flex-fill" style="background: #f8f9fa;">
                                    <i class="bi bi-card-list text-primary me-2"></i>
                                    <span style="font-size: 12px;">Driving License</span>
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
<div class="modal fade" id="createOwnerModal" tabindex="-1" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content shadow border-0">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-person-plus me-2 text-primary"></i>
                    Register Driver/Company
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">x</button>
            </div>
            <div class="modal-body pt-2">
                <div id="registrationFields">
                    <div class="mb-3 com_fields">
                        <label class="form-label small fw-semibold">Company Name</label>
                        <input type="text" id="reg_company" class="form-control form-control-sm"
                            placeholder="Enter Company Name" oninput="this.value=this.value.replace(/[^A-Za-z0-9 ]/g,'').slice(0,75)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Name</label>
                        <input type="text" id="reg_name" class="form-control form-control-sm"
                            placeholder="Enter Name" oninput="this.value=this.value.replace(/[^A-Za-z ]/g,'').slice(0,75)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Mobile</label>
                        <input type="tel" id="reg_mobile"
                            class="form-control form-control-sm w-100" inputmode="numeric"
                            placeholder="Enter Mobile" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email</label>
                        <input type="email" id="reg_email"
                            class="form-control form-control-sm"
                            placeholder="Enter Email" oninput="this.value=this.value.replace(/\s/g,'').slice(0,75)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Role Type</label>
                        <select id="reg_role" class="form-select form-select-sm">
                            <option value="Owner">Owner</option>
                            <option value="Driver">Driver</option>
                        </select>
                    </div>
                    <button type="button"
                        class="btn btn-primary w-100 fw-semibold"
                        id="btnSendOTP">
                        <i class="bi bi-shield-lock me-1"></i>
                        Register & Send OTP
                    </button>
                </div>
                <div id="otpSection" class="mt-4 pt-3 border-top" style="display: none;">
                    <label class="form-label small fw-semibold">Enter OTP</label>
                    <div class="input-group input-group-sm">
                        <input type="text"
                            id="otp_code"
                            class="form-control"
                            placeholder="6-digit code"
                            oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)">
                        <button class="btn btn-success fw-semibold"
                            type="button"
                            id="btnVerifyOTP">
                            Verify
                        </button>
                    </div>
                    <div class="mt-2 small">
                        <button type="button"
                            id="btnResendOTP"
                            class="btn btn-link p-0 text-decoration-none"
                            disabled>
                            Resend OTP
                        </button>
                        <span id="otpTimer" class="text-muted ms-2">(30s)</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button"
                    class="btn btn-light border"
                    data-bs-dismiss="modal">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0"></script>
<?php if ($role_id == 8): ?>
    <script>
        const API_URL = "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>";
        const ADMIN_ACCESS_TOKEN = "<?= rtrim(ADMIN_ACCESS_TOKEN, '/') ?>";
        const u_id = "<?= rtrim($u_id, '/') ?>";
    </script>
    <script src="/js/fieldScript.js?v=<?php echo time(); ?>"></script>
<?php else: ?>
    <script>
        var myChartHourlySales;
        var paymentDrawWise;
        var paymentDayWise;
        var ownerDayWise;
        var vehicleDayWise;
        var loginChartWise;
        var tapAndWinChart;
        var customerSignupChartInst;
        var driverChart;
        var jobsChart;
        var driverAppJobsChartInst;
        var customerAppJobsChartInst;
        $(document).on('click', '.take-action', function() {
            $('#job_id').val($(this).data('id'));
            $('#user_id').val($(this).data('user-id'));
            $('input[name="job_action"]').prop('checked', false);
            $('#takeActionModal').modal('show');
        });
        $('#submitJobAction').on('click', function() {
            const $btn = $(this);
            let jobId = $('#job_id').val();
            let userId = $('#user_id').val();
            let action = $('input[name="job_action"]:checked').val();
            if (!action) {
                alert('Please select an action');
                return;
            }
            $btn.prop('disabled', true).text('Processing...');
            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/job/action",
                type: 'POST',
                data: {
                    job_id: jobId,
                    user_id: userId,
                    action: action
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#takeActionModal').modal('hide');
                },
                error: function() {
                    alert('Something went wrong');
                },
                complete: function() {
                    $btn.prop('disabled', false).text('Submit');
                }
            });
        });
        // // console.log(origin, 'hiii');
        var url = origin + "/ajax/service/dashBoard_Services.php";
        var jobUrl = origin + "/ajax/service/jobServices.php";
        var url1 = "http://smsresell.cwd.co.in/api/checkbalance.php?user=goride&pass=Goride@321";
        
        const tapAndWinFlow = () => {
            try {
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        selectedDate: $('#datefilterTap').val(),
                        method: 'TapAndWinBooked',
                        ad_template: 'template'
                    },
                    success: function(data) {
                        var response = JSON.parse(data);
                        if (response != "") {
                            if (response.type == 1) {
                                $(`#tapWinErr`).text(``);
                                if (tapAndWinChart) {
                                    tapAndWinChart.destroy();
                                }
                                var dataSets = Object.keys(response.dataSet);
                                let DataN = [];
                                dataSets.forEach(s => {
                                    const dataArray = Object.entries(response.dataSet[s]).map(([key, value]) => ({
                                        [key]: value
                                    }));
                                    DataN[s] = [];
                                    response.prizeList.forEach(l => {
                                        const entry = dataArray.find(item => Object.keys(item)[0] === l.toString());
                                        DataN[s].push(entry ? entry[Object.keys(entry)[0]] : '0');
                                    });
                                });
                                const availableColors = [
                                    '#57ab68', '#6ab477', '#7bbc86', '#8cc495', '#9dcda3', '#add5b2', '#bedec2', '#cee6d1', '#deeee0', '#eff7ef', '#57ab68', '#4b9459', '#3e7e4b', '#33683e', '#275430', '#1c4024', '#122d18', '#081b0c', '#030b04', '#000100'
                                ];
                                var dataSET = [];
                                let i = 1;
                                for (const [index, value] of Object.entries(DataN)) {
                                    const dataEntry = {
                                        label: 'Batch ' + i,
                                        data: value, // Convert to numbers
                                        borderWidth: 1,
                                        borderColor: availableColors.slice(0, response.prizeList.length),
                                        backgroundColor: availableColors.slice(0, response.prizeList.length)
                                    };
                                    // Push the dataEntry into the dataSET array
                                    dataSET.push(dataEntry);
                                    i++;
                                }
                                // console.log(dataSET);
                                if (dataSET.length > 0) {
                                    const ctx = document.getElementById('tapAndWinFlowG').getContext('2d');
                                    tapAndWinChart = new Chart(ctx, {
                                        type: 'doughnut',
                                        data: {
                                            labels: response.Labels,
                                            datasets: dataSET
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            plugins: {
                                                legend: {
                                                    display: true,
                                                    position: 'top'
                                                },
                                                tooltip: {
                                                    callbacks: {
                                                        label: function(tooltipItem) {
                                                            const datasetLabel = tooltipItem.dataset.label || '';
                                                            const dataPoint = tooltipItem.raw;
                                                            return `${datasetLabel}: ${dataPoint}`;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    });
                                } else {
                                    $(`#tapWinErr`).text(`No Prizes Claimed!`);
                                }
                            } else {
                                showToast(`error`, response.result, 5000);
                                $(`#tapWinErr`).text(`No Prizes Claimed!`);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching hourly sales data:', error);
                    }
                });
            } catch (e) {
                // console.log(`Error: ${e.message}`);
            }
        }
        const tapAndWinFlowNew = () => {
            try {
                $(`#tapWinErr`).html('');
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        selectedDate: $('#datefilterTap').val(),
                        method: 'TapAndWinBooked123',
                    },
                    success: function(data) {
                        var response = JSON.parse(data);
                        if (response != "") {
                            if (response.type == 1) {
                                if (tapAndWinChart) {
                                    tapAndWinChart.destroy();
                                }
                                if (response.Labels.length > 0 && response.totalClaim.length > 0) {
                                    const ctx = document.getElementById('tapAndWinFlowG').getContext('2d');
                                    tapAndWinChart = new Chart(ctx, {
                                        type: 'doughnut',
                                        data: {
                                            labels: response.Labels,
                                            datasets: [{
                                                label: 'Tap & Win Prize - Direct',
                                                data: response.totalClaim,
                                                backgroundColor: [
                                                    'rgb(255, 99, 132)', // Red
                                                    'rgb(54, 162, 235)', // Blue
                                                    'rgb(255, 205, 86)', // Yellow
                                                    'rgb(75, 192, 192)', // Teal
                                                    'rgb(153, 102, 255)', // Purple
                                                    'rgb(255, 159, 64)', // Orange
                                                    'rgb(255, 99, 71)', // Tomato
                                                    'rgb(124, 252, 0)', // Lawn Green
                                                    'rgb(30, 144, 255)', // Dodger Blue
                                                    'rgb(238, 130, 238)', // Violet
                                                    'rgb(255, 215, 0)', // Gold
                                                    'rgb(205, 92, 92)', // Indian Red
                                                    'rgb(0, 255, 127)', // Medium Spring Green
                                                    'rgb(100, 149, 237)', // Cornflower Blue
                                                    'rgb(240, 230, 140)', // Khaki
                                                    'rgb(147, 112, 219)', // Medium Purple
                                                    'rgb(255, 105, 180)', // Hot Pink
                                                    'rgb(255, 228, 196)', // Bisque
                                                    'rgb(0, 206, 209)', // Dark Turquoise
                                                    'rgb(135, 206, 235)' // Sky Blue
                                                ],
                                                hoverOffset: 4
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            plugins: {
                                                legend: {
                                                    display: true,
                                                    position: 'top'
                                                },
                                            }
                                        }
                                    });
                                } else {
                                    // showToast(`error`, response.result, 5000);
                                    $(`#tapWinErr`).html('<div class="alert alert-warning" role="alert">No Prizes Claimed!</div>');
                                }
                            } else {
                                showToast(`error`, response.result, 5000);
                                // $(`#tapWinErr`).text(`No Prizes Claimed!`);
                                $(`#tapWinErr`).html('<div class="alert alert-warning" role="alert">No Prizes Claimed!</div>');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching hourly sales data:', error);
                    }
                });
            } catch (e) {
                // console.log(`Error: ${e.message}`);
            }
        }
        $("#utm_campaign").on("change", function() {
            var template = $('#utm_campaign').val();
            $.ajax({
                url: origin + "/ajax/service/dashBoard_Services.php",
                type: "POST",
                data: {
                    method: 'ad_lead_content',
                    template: template
                },
                dataType: "json",
                success: function(res) {
                    // $('#ad_kyc').val(res.result.);
                    // // console.log(res);
                    if (res.type == 1) {
                        $('#ad_kyc').text(res.result.doc_verified_count);
                        $('#ad_reg').text(res.result.register_count);
                        $('#ad_lead').text(res.result.lead_count);
                        $('#ad_veh').text(res.result.vehicle_verified_count);
                        $('#ad_driver').text(res.result.driver_count);
                        $('#ad_owner').text(res.result.owner_count);
                    } else {
                    }
                },
                complete: function() {
                    $("#sendWhatsapp").prop("disabled", false).html(
                        '<i class="bi bi-send-fill"></i> Send Message');
                    // // // console.log("Request completed.");
                }
            });
        });
        const customerSignupFlow = () => {
            try {
                var startDate = moment($('#datefilterCustomerSignup').data('daterangepicker').startDate).format("YYYY-MM-DD");
                var endDate = moment($('#datefilterCustomerSignup').data('daterangepicker').endDate).format("YYYY-MM-DD");

                if (!startDate || !endDate) {
                    showToast(`error`, 'Kindly select the date range filter', 5000);
                    return false;
                }

                var formdata = [
                    { name: 'method', value: "customer_signup_chart" },
                    { name: 'startDate', value: startDate },
                    { name: 'endDate', value: endDate }
                ];

                var onsuccess = function(data) {
                    try {
                        var response = JSON.parse(data);
                        if (response.type == 1) {
                            if (customerSignupChartInst) {
                                customerSignupChartInst.destroy();
                            }
                            var ctx = document.getElementById('customerSignupChart').getContext('2d');
                            customerSignupChartInst = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: response.dates,
                                    datasets: [{
                                        label: 'Customer Signups',
                                        data: response.counts,
                                        backgroundColor: '#1abc9c',
                                        borderColor: '#16a085',
                                        borderWidth: 1,
                                        borderRadius: 4
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { display: true }
                                    },
                                    scales: {
                                        x: { grid: { display: false } },
                                        y: { beginAtZero: true }
                                    }
                                }
                            });
                        }
                    } catch (err) {
                        console.error("Chart render error:", err);
                    }
                };

                do_ajax_call(formdata, onsuccess, origin + "/ajax/service/dashBoard_Services.php");
            } catch (e) {}
        };
        const loginChart = () => {
            var formdata = [];
            var startDate = moment($('#datefilterLogin').data('daterangepicker').startDate).format("YYYY-MM-DD");
            var endDate = moment($('#datefilterLogin').data('daterangepicker').endDate).format("YYYY-MM-DD");
            var utm_campaign = $(`#utm_campaign_login`).val();
            var utm_campaign_source = $(`#utm_campaign_source_login`).val();
            if (startDate == '' || startDate == null || startDate == undefined || endDate == '' || endDate == null || endDate == undefined) {
                showToast(`error`, 'Kindly select the data Range filter', 5000);
                return false;
            }
            formdata.push({
                    name: 'method',
                    value: "sign_up_chart"
                }, {
                    name: 'startDate',
                    value: startDate
                }, {
                    name: 'endDate',
                    value: endDate
                }, {
                    name: 'utm_campaign',
                    value: utm_campaign
                },
                {
                    name: 'utm_campaign_source',
                    value: utm_campaign_source
                }
            );
            var post_data = formdata;
            var onsuccess = function(data) {
                var response = JSON.parse(data);
                if (response != "") {
                    if (response.type == 1) {
                        if (loginChartWise) {
                            loginChartWise.destroy();
                        }
                        var ctx = document.getElementById('signupLoginChart').getContext('2d');
                        loginChartWise = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: response.dates,
                                datasets: [{
                                        label: 'Mobile',
                                        data: response.mobile,
                                        backgroundColor: '#F7418F',
                                        borderColor: '#F7418F',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Desktop',
                                        data: response.desktop,
                                        backgroundColor: '#1e4d79ff',
                                        borderColor: '#1e4d79ff',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Leads',
                                        data: response.leads,
                                        backgroundColor: '#41C9E2',
                                        borderColor: '#41C9E2',
                                        borderWidth: 1
                                    }
                                    // ,
                                    // {
                                    //     label: 'Sign-in (With OTP)',
                                    //     data: response.loginsOTP,
                                    //     backgroundColor: '#42e351',
                                    //     borderColor: '#42e351',
                                    //     borderWidth: 1
                                    // }
                                ]
                            },
                            options: {
                                plugins: {
                                    legend: {
                                        display: true
                                    }
                                },
                                scales: {
                                    x: {
                                        offset: true,
                                        grid: {
                                            display: false
                                        }
                                    },
                                    y: {
                                        display: true
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Gradient Donut with custom Start-angle'
                                },
                                responsive: true,
                                maintainAspectRatio: false,
                            }
                        });
                        // document.getElementById('collectdiv').innerHTML = response.output;
                        // $(`#signTotlaCount`).text(`Total: ${response.total}`);
                    } else {
                        showToast(`error`, response.result, 5000);
                        // document.getElementById('collectdiv').innerHTML = response.result;
                    }
                }
            }
            do_ajax_call(post_data, onsuccess, origin + "/ajax/service/dashBoard_Services.php");
        }
        function fetchHourlySales(element) {
            const check_utm = $(element).attr('id');
            // // console.log(check_utm);
            if ($('#fromdatefilter').val() == '') {
                $('#fromdatefilter').val(moment().format('MM/DD/YYYY'));
            }
            if ($('#todatefilter').val() == '') {
                $('#todatefilter').val(moment().subtract(1, 'days').format('MM/DD/YYYY'));
            }
            // if(check_utm != 'utm_campaign_source'){
            //      changeUTM(check_utm);
            // }
            // changeUTM('no')
            try {
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        fromDate: $('#fromdatefilter').val(),
                        toDate: $('#todatefilter').val(),
                        method: 'hour_chart',
                        payment_type: $('#payment_type').val(),
                        domainName: $(`#domainName`).val(),
                        utm_campaign: $(`#utm_campaign`).val(),
                        utm_campaign_source: $(`#utm_campaign_source`).val()
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        let old_utm_source = $('#utm_campaign_source').val();
                        let old_utm_campaign = $('#utm_campaign').val();
                        // console.log(old_utm_campaign);
                        if (check_utm != 'utm_campaign_source') {
                            var utm_filter_source = $('#utm_campaign_source').empty();
                            var utm_filter_campaign = $('#utm_campaign').empty();
                            // $(`#utm_campaign_source`).val('').selectpicker('refresh');
                            // $(`#utm_campaign`).val('').selectpicker('refresh');
                            if (data.result_source.length > 0) {
                                $.each(data.result_source, function(index, value) {
                                    utm_filter_campaign.append(`<option value="${value.utm_c}" data-camp="${value.utm_s}">${value.utm_c}</option>`);
                                    utm_filter_source.append(`<option value="${value.utm_s}" data-source="${value.utm_c}">${value.utm_s}</option>`);
                                });
                            }
                            utm_filter_source.prepend(`<option value="" selected>All</option>`);
                            utm_filter_campaign.prepend(`<option value="" selected>All</option>`);
                        }
                        // if (data.result_source.length > 0) {
                        //      $.each(data.result_source, function(index, value) {
                        //          utm_filter_source.append(`<option value="${value.utm_s}" data-camp="${value.utm_c}">${value.utm_s}</option>`);
                        //          utm_filter_campaign.append(`<option value="${value.utm_c}">${value.utm_c}</option>`);
                        //      });
                        // }
                        // utm_filter_source.prepend(`<option value="" selected>All</option>`);
                        // utm_filter_campaign.prepend(`<option value="" selected>All</option>`);
                        if (check_utm == 'fromdatefilter' || check_utm == 'todatefilter') {
                            $(`#utm_campaign_source`).val('').selectpicker('refresh');
                            $(`#utm_campaign`).val('').selectpicker('refresh');
                        } else {
                            // // console.log(utm_filter_campaign);
                            $(`#utm_campaign_source`).val(old_utm_source).selectpicker('refresh');
                            $(`#utm_campaign`).val(old_utm_campaign).selectpicker('refresh');
                        }
                        if (check_utm == 'utm_campaign') {
                            changeUTM(check_utm, old_utm_source, old_utm_campaign);
                        }
                        // if (check_utm == 'utm_campaign_source') {
                        //      utm_filter_source.prepend(`<option value="" selected>All</option>`);
                        //      utm_filter_campaign.prepend(`<option value="" selected>All</option>`);
                        // } else {
                        //      utm_filter_source.prepend(`<option value="">All</option>`);
                        //      utm_filter_campaign.prepend(`<option value="">All</option>`);
                        // }
                        updateChart(data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching hourly sales data:', error);
                    }
                });
            } catch (e) {
                // console.log(`Error: ${e.message}`);ad_lead_content
            }
        }
        // const fetchHourlySales = () => {
        // }
        const creditsCounts = () => {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "text",
                data: {
                    method: 'credits_count'
                },
                success: function(res) {
                    if (typeof res === 'string') {
                        res = JSON.parse(res);
                    }
                    let promo = {};
                    if (res.result) {
                        promo = (typeof res.result === 'string') ?
                            JSON.parse(res.result) :
                            res.result;
                    }
                    $('#sms_resell').text(res.data);
                    $('#promo_count').text(promo.Credits);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching pending counts:", error);
                }
            });
        };
        const cabCounts = () => {
            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: {
                    method: 'cab_count'
                },
                success: function(res) {
                    if (res.type == '1') {
                        let html = "";
                        res.result.forEach(item => {
                            if (item.type === "cab_count" && item.seat_capacity === 'Mini') {
                                $('#mini_val').text(item.user_count);
                            } else if (item.type === "cab_count" && item.seat_capacity === 'Prime_Sedan') {
                                $('#sedan_val').text(item.user_count);
                            } else if (item.type === "cab_count" && item.seat_capacity === 'Prime_SUV') {
                                $('#prime_val').text(item.user_count);
                            } else if (item.type === "cab_count" && item.seat_capacity === 'Prime_SUV⁺') {
                                $('#primesuv_val').text(item.user_count);
                            } else if (item.type === "cab_count" && item.seat_capacity === 'Prime_Plus') {
                                $('#primepls_val').text(item.user_count);
                            } else if (item.type === "cab_count" && item.seat_capacity === 'XL_Intercity') {
                                $('#xl_val').text(item.user_count);
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching pending counts:", error);
                }
            });
        };
        const seatCounts = () => {
            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: {
                    method: 'seat_count'
                },
                success: function(res) {
                    if (res.type == '1') {
                        let html = "";
                        res.result.forEach(item => {
                            if (item.type === "seat_count") {
                                html += `
                                <div>
                                    <span class="fw-bold text-dark">${item.seat_capacity} Seater</span><br>
                                    <span class="text-muted">${item.user_count}</span>
                                </div>
                            `;
                            }
                        });
                        $("#seatCountContainer").html(html);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching pending counts:", error);
                }
            });
        };
        const uPendingCounts = () => {
            $.ajax({
                url: url, // <-- change this to your URL
                type: "POST",
                data: {
                    method: "user_pending_counts"
                },
                dataType: "json",
                success: function(res) {
                    // console.log(res);
                    if (res.type == '1') {
                        $("#kyc_p_count").text(res.result[0].total ?? 0);
                        $("#vehicle_p_count").text(res.result[1].total ?? 0);
                        $("#error_logs_count").text(res.result[6].total ?? 0);
                        $("#expiry_count").text(res.result[2].total ?? 0);
                        $("#cancelled_count").text(res.result[3].total ?? 0);
                        $("#not_complete_count").text(res.result[4].total ?? 0);
                        $("#cons_count").text(res.result[14].total ?? 0);
                        $("#sts_reject").text(res.result[15].total ?? 0);
                        // $("#sts_notverifi").text(res.result[16].total ?? 0);
                        $("#v_driver").text(res.result[7].total ?? 0);
                        $("#v_owner").text(res.result[8].total ?? 0);
                        $("#d_both").text(res.result[7].total ?? 0);
                        $("#o_kyc").text(res.result[8].total ?? 0);
                        $("#d_kyc").text(res.result[9].total ?? 0);
                        $("#d_veh").text(res.result[10].total ?? 0);
                        $("#sms_count").text(res.result[11].total ?? 0);
                        //  $("#value_count").text(res.result[16].total ?? 0);
                        const non_kyc_count = res.result[5].total ?? 0;
                        const non_kyc_count_wo_kyc = res.result[17].total ?? 0;
                        const shiCount = res.result[12].total ?? 0;
                        const whCount = res.result[13].total ?? 0;
                        $('#kyc_nc_count').text(parseInt(non_kyc_count) + parseInt(non_kyc_count_wo_kyc));
                        $("#kyc_missing_count").text(res.result[17].total ?? 0);
                        $("#l_web").text(res.result[18].total ?? 0);
                        $("#post_web").text(res.result[19].total ?? 0);
                        // $('#cab_count').text(res.result[15].total ?? 0);
                        $("#total_wh_count").text(parseInt(shiCount) + parseInt(whCount));
                    } else {
                        $("#kyc_p_count").text(0);
                        $("#kyc_nc_count").text(0);
                        $("#vehicle_p_count").text(0);
                        $("#error_logs_count").text(0);
                        $("#expiry_count").text(0);
                        $("#not_complete_count").text(0);
                        $("#cancelled_count").text(0);
                        $("#cons_count").text(0);
                        $("#sts_reject").text(0);
                        // $("#sts_notverifi").text(0);
                        $("#v_owner").text(0);
                        $("#v_driver").text(0);
                        $("#d_both").text(0);
                        $("#o_kyc").text(0);
                        $("#d_kyc").text(0);
                        $("#d_veh").text(0);
                        $("#sms_count").text(0);
                        $("#shi_count").text(0);
                        $("#wh_count").text(0);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching pending counts:", error);
                }
            });
        };
        // const paymentCounts = () => {
        //      $.ajax({
        //          url: origin + "/ajax/service/transaction_services.php",
        //          type: "POST",
        //          dataType: "json",
        //          data: {
        //              method: 'payment_dashboard_counts'
        //          },
        //          success: function(res) {
        //              if (res.type == 1) {
        //                  // Helper function to format amount with Rupee symbol and 2 decimal places
        //                  const formatAmt = (amt) => '₹ ' + parseFloat(amt || 0).toFixed(2);
        //                  // Bind the formatted amounts to the HTML elements
        //                  $("#pay_total_amount").text(formatAmt(res.total_received));
        //                  $("#pay_wallet").text(formatAmt(res.total_wallet));
        //                  $("#pay_purchase").text(formatAmt(res.total_purchase));
        //              }
        //          },
        //          error: function(xhr, status, error) {
        //              console.error("Error fetching payment counts:", error);
        //          }
        //      });
        // };
        const stsCounts = () => {
            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: {
                    method: 'sts_count'
                },
                success: function(res) {
                    if (res.type == '1') {
                        $("#o_selfie").text(res.result[0].total ?? 0);
                        $("#o_aadhar").text(res.result[1].total ?? 0);
                        $("#o_dl").text(res.result[2].total ?? 0);
                        $("#o_gst").text(res.result[3].total ?? 0);
                        $("#updated_count").text(res.result[4].total ?? 0);
                        $("#none_updated").text(res.result[5].total ?? 0);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching pending counts:", error);
                }
            });
        };
        function updateChart(data) {
            var labelsWithTime = data.hours.map(function(label) {
                var hour = parseInt(label);
                if (hour === 0) {
                    return "12 am";
                } else if (hour < 12) {
                    return hour + " am";
                } else if (hour === 12) {
                    return "12 pm";
                } else {
                    return (hour - 12) + " pm";
                }
            });
            // Format the dates
            let formattedFromDate = data.fromDate;
            let formattedToDate = data.toDate;
            myChartHourlySales.data.labels = labelsWithTime;
            myChartHourlySales.data.datasets[0].label = formattedFromDate; // Update label for the first dataset
            myChartHourlySales.data.datasets[1].label = formattedToDate
            // Update the datasets with hourly sales data for both Date 1 and Date 2
            myChartHourlySales.data.datasets[0].data = data.hourlySales1; // Data for Date 1
            myChartHourlySales.data.datasets[1].data = data.hourlySales2; // Data for Date 2
            // Update the chart with the new data
            myChartHourlySales.update();
            // // console.log(data.toTotalAED);
            // Update the total sales for both dates in the card titles
            if (data.fromTotalAED != 0 || data.toTotalAED != 0) {
                $('.card-title1').html(formattedFromDate + ': <span class="fw-bold">' + 'AED ' + data.fromTotalAED + '</span>'); // Total for Date 1
                $('.card-title2').html(formattedToDate + ': <span class="fw-bold">' + 'AED ' + data.toTotalAED + ' </span>');
                $('.card-title3').html(''); // Total for Date 1
                $('.card-title4').html('');
            } else {
                $('.card-title1').html(formattedFromDate + ': <span class="fw-bold">' + 'AED ' + data.fromTotalAED + '</span> / ' + '<span class="fw-bold text-danger">' + 'AED ' + data.fromMissingAED + '</span>'); // Total for Date 1
                $('.card-title2').html(formattedToDate + ': <span class="fw-bold">' + 'AED ' + data.toTotalAED + ' </span> / ' + '<span class="fw-bold text-danger">' + 'AED ' + data.toMissingAED + ' </span>');
                $('.card-title3').html('<span class="fw-bold"> User Count : </span>' + data.user_count1);
                $('.card-title4').html('<span class="fw-bold"> User Count : </span>' + data.user_count2);
            }
        }
        const saleschart = () => {
            try {
                var startDate = moment($('#datefilterVerify').data('daterangepicker').startDate).format("YYYY-MM-DD");
                var endDate = moment($('#datefilterVerify').data('daterangepicker').endDate).format("YYYY-MM-DD");
                var formdata = [{
                        name: 'method',
                        value: "verifyReport_List"
                    },
                    {
                        name: 'startDate',
                        value: startDate
                    },
                    {
                        name: 'endDate',
                        value: endDate
                    }
                ];
                var onsuccess = function(data) {
                    try {
                        var response = JSON.parse(data);
                        if (response.type == 1) {
                            if (paymentDayWise) paymentDayWise.destroy();
                            // ✅ Prepare chart data safely
                            let labels = response.dates || [];
                            let kycCreated = (response.kyc_created || []).map(x => Number(x) || 0);
                            let kycCompleted = (response.kyc_completed || []).map(x => Number(x) || 0);
                            let kycReject = (response.kyc_reject || []).map(x => Number(x) || 0);
                            //   let vehicleCompleted = (response.vehicle_completed || []).map(x => Number(x) || 0);
                            //   let both = (response.both || []).map(x => Number(x) || 0);
                            let total = (response.total || 'N/A');
                            //   $('#tot_v').html('New K&V ' + total);
                            // $('#tot_v').html(
                            //   `New KYC <a href="https://console.goride.run/kyc-details" target="_blank" style="color: #007bff; text-decoration: none;">
                            //      ${total}
                            //   </a>`
                            // );
                            const maxVal = Math.max(...kycCreated, ...kycCompleted, ...kycReject);
                            const suggestedMax = maxVal > 0 ? undefined : 1;
                            // 🎨 Elegant color palette
                            const colors = {
                                kycCreated: 'rgba(26, 188, 156, 0.85)', // teal
                                kycCompleted: 'rgba(46, 204, 113, 0.85)', // green
                                kycReject: 'rgba(52, 152, 219, 0.85)', // blue
                                // vehicleCompleted: 'rgba(241, 196, 15, 0.85)',// yellow
                                // both: 'rgba(155, 89, 182, 0.85)'             // purple
                            };
                            const borderColors = {
                                kycCreated: 'rgba(22, 160, 133, 1)',
                                kycCompleted: 'rgba(39, 174, 96, 1)',
                                kycReject: 'rgba(41, 128, 185, 1)',
                                // vehicleCompleted: 'rgba(243, 156, 18, 1)',
                                // both: 'rgba(142, 68, 173, 1)'
                            };
                            var ctx = document.getElementById('saleschart').getContext('2d');
                            paymentDayWise = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                            label: 'KYC Created',
                                            data: kycCreated,
                                            backgroundColor: colors.kycCreated,
                                            borderColor: borderColors.kycCreated,
                                            borderWidth: 1,
                                            borderRadius: 0
                                        },
                                        {
                                            label: 'KYC Reject',
                                            data: kycReject,
                                            backgroundColor: colors.kycReject,
                                            borderColor: borderColors.kycReject,
                                            borderWidth: 1,
                                            borderRadius: 0
                                        },
                                        {
                                            label: 'KYC Verified',
                                            data: kycCompleted,
                                            backgroundColor: colors.kycCompleted,
                                            borderColor: borderColors.kycCompleted,
                                            borderWidth: 1,
                                            borderRadius: 0
                                        }
                                    ]
                                },
                                options: {
                                    plugins: {
                                        legend: {
                                            display: true,
                                            labels: {
                                                font: {
                                                    size: 13,
                                                    family: "'Poppins', sans-serif"
                                                },
                                                color: '#333'
                                            }
                                        },
                                        tooltip: {
                                            backgroundColor: 'rgba(0, 0, 0, 0.75)',
                                            titleColor: '#fff',
                                            bodyColor: '#fff',
                                            borderColor: '#ddd',
                                            borderWidth: 1,
                                            padding: 10,
                                            titleFont: {
                                                weight: 'bold',
                                                size: 13
                                            },
                                            bodyFont: {
                                                size: 12
                                            }
                                        }
                                    },
                                    scales: {
                                        x: {
                                            grid: {
                                                display: false
                                            },
                                            ticks: {
                                                color: '#444',
                                                font: {
                                                    size: 12
                                                }
                                            }
                                        },
                                        y: {
                                            beginAtZero: true,
                                            suggestedMax: suggestedMax,
                                            grid: {
                                                color: 'rgba(200,200,200,0.3)'
                                            },
                                            ticks: {
                                                color: '#444',
                                                font: {
                                                    size: 12
                                                }
                                            }
                                        }
                                    },
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    animation: {
                                        duration: 1000,
                                        easing: 'easeOutQuart'
                                    }
                                }
                            });
                        } else {
                            // console.log(response.result || 'No data');
                        }
                    } catch (err) {
                        console.error("Chart render error:", err);
                    }
                };
                do_ajax_call(formdata, onsuccess, origin + "/ajax/service/dashBoard_Services.php");
            } catch (e) {
                // console.log(`Error: ${e.message}`);
            }
        };
        const vehiclechart = () => {
            try {
                var startDate = moment($('#datefilterVehicle').data('daterangepicker').startDate).format("YYYY-MM-DD");
                var endDate = moment($('#datefilterVehicle').data('daterangepicker').endDate).format("YYYY-MM-DD");
                var formdata = [{
                        name: 'method',
                        value: "vehicleReport_List"
                    },
                    {
                        name: 'startDate',
                        value: startDate
                    },
                    {
                        name: 'endDate',
                        value: endDate
                    }
                ];
                var onsuccess = function(data) {
                    try {
                        var response = JSON.parse(data);
                        if (response.type == 1) {
                            if (vehicleDayWise) vehicleDayWise.destroy();
                            let labels = response.dates || [];
                            let vehicleCreated = (response.vehicle_created || []).map(x => Number(x) || 0);
                            let vehicleReject = (response.vehicle_reject || []).map(x => Number(x) || 0);
                            let vehicleCompleted = (response.vehicle_completed || []).map(x => Number(x) || 0);
                            let total = (response.total || 'N/A');
                            // $('#tot_veh').html(
                            //   `New Vehicle <a href="https://console.goride.run/kyc-details" target="_blank" style="color: #007bff; text-decoration: none;">
                            //      ${total}
                            //   </a>`
                            // );
                            const maxVal = Math.max(...vehicleCreated, ...vehicleCompleted, ...vehicleReject);
                            const suggestedMax = maxVal > 0 ? undefined : 1;
                            // 🎨 Elegant color palette
                            const colors = {
                                vehicleCreated: 'rgba(26, 188, 156, 0.85)', // teal
                                vehicleCompleted: 'rgba(46, 204, 113, 0.85)', // green
                                vehicleReject: 'rgba(52, 152, 219, 0.85)', // blue
                                // vehicleCompleted: 'rgba(241, 196, 15, 0.85)',// yellow
                                // both: 'rgba(155, 89, 182, 0.85)'             // purple
                            };
                            const borderColors = {
                                vehicleCreated: 'rgba(22, 160, 133, 1)',
                                vehicleCompleted: 'rgba(39, 174, 96, 1)',
                                vehicleReject: 'rgba(41, 128, 185, 1)',
                                // vehicleCompleted: 'rgba(243, 156, 18, 1)',
                                // both: 'rgba(142, 68, 173, 1)'
                            };
                            var ctx = document.getElementById('vehiclechart').getContext('2d');
                            vehicleDayWise = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                            label: 'Vehicle Created',
                                            data: vehicleCreated,
                                            backgroundColor: colors.vehicleCreated,
                                            borderColor: borderColors.vehicleCreated,
                                            borderWidth: 1,
                                            borderRadius: 0
                                        },
                                        {
                                            label: 'Vehicle Reject',
                                            data: vehicleReject,
                                            backgroundColor: colors.vehicleReject,
                                            borderColor: borderColors.vehicleReject,
                                            borderWidth: 1,
                                            borderRadius: 0
                                        },
                                        {
                                            label: 'Vehicle Completed',
                                            data: vehicleCompleted,
                                            backgroundColor: colors.vehicleCompleted,
                                            borderColor: borderColors.vehicleCompleted,
                                            borderWidth: 1,
                                            borderRadius: 0
                                        }
                                    ]
                                },
                                options: {
                                    plugins: {
                                        legend: {
                                            display: true,
                                            labels: {
                                                font: {
                                                    size: 13,
                                                    family: "'Poppins', sans-serif"
                                                },
                                                color: '#333'
                                            }
                                        },
                                        tooltip: {
                                            backgroundColor: 'rgba(0, 0, 0, 0.75)',
                                            titleColor: '#fff',
                                            bodyColor: '#fff',
                                            borderColor: '#ddd',
                                            borderWidth: 1,
                                            padding: 10,
                                            titleFont: {
                                                weight: 'bold',
                                                size: 13
                                            },
                                            bodyFont: {
                                                size: 12
                                            }
                                        }
                                    },
                                    scales: {
                                        x: {
                                            grid: {
                                                display: false
                                            },
                                            ticks: {
                                                color: '#444',
                                                font: {
                                                    size: 12
                                                }
                                            }
                                        },
                                        y: {
                                            beginAtZero: true,
                                            suggestedMax: suggestedMax,
                                            grid: {
                                                color: 'rgba(200,200,200,0.3)'
                                            },
                                            ticks: {
                                                color: '#444',
                                                font: {
                                                    size: 12
                                                }
                                            }
                                        }
                                    },
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    animation: {
                                        duration: 1000,
                                        easing: 'easeOutQuart'
                                    }
                                }
                            });
                        } else {
                            // console.log(response.result || 'No data');
                        }
                    } catch (err) {
                        console.error("Chart render error:", err);
                    }
                };
                do_ajax_call(formdata, onsuccess, origin + "/ajax/service/dashBoard_Services.php");
            } catch (e) {
                // console.log(`Error: ${e.message}`);
            }
        };
        const driverchart = () => {
            try {
                var startDate = moment($('#datefilterDriver').data('daterangepicker').startDate).format("YYYY-MM-DD");
                var endDate = moment($('#datefilterDriver').data('daterangepicker').endDate).format("YYYY-MM-DD");
                var formdata = [{
                        name: 'method',
                        value: "driverReport_List"
                    },
                    {
                        name: 'startDate',
                        value: startDate
                    },
                    {
                        name: 'endDate',
                        value: endDate
                    }
                ];
                var onsuccess = function(data) {
                    try {
                        var response = JSON.parse(data);
                        if (response.type == 1) {
                            if (driverChart) driverChart.destroy();
                            let labels = response.dates || [];
                            let owner = (response.owner || []).map(x => Number(x) || 0);
                            let driver = (response.driver || []).map(x => Number(x) || 0);
                            let both = (response.both || []).map(x => Number(x) || 0);
                            let d_c = (response.d_c || '');
                            let o_c = (response.o_c || '');
                            $('#do_c').html('<strong>Driver: </strong>' + d_c + ' ' + '<strong>Owner: </strong>' + o_c);
                            const maxVal = Math.max(...owner, ...driver, ...both);
                            const suggestedMax = maxVal > 0 ? undefined : 1;
                            const colors = {
                                both: '#53629E',
                                owner: '#87BAC3',
                                driver: '#84AE92'
                            };
                            const borderColors = {
                                both: '#53629E',
                                owner: '#87BAC3',
                                driver: '#84AE92'
                            };
                            // const colors = {
                            //      both: '#8A784E',
                            //      owner: '#AEC8A4',
                            //      driver:  '#E7EFC7'
                            // };
                            // const borderColors = {
                            //      both: '#8A784E',
                            //      owner: '#AEC8A4',
                            //      driver:  '#E7EFC7'
                            // };
                            var ctx = document.getElementById('driverchart').getContext('2d');
                            driverChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                            label: 'Overall',
                                            data: both,
                                            backgroundColor: colors.both,
                                            borderColor: borderColors.both,
                                            borderWidth: 1,
                                            borderRadius: 0
                                        },
                                        {
                                            label: 'Owner',
                                            data: owner,
                                            backgroundColor: colors.owner,
                                            borderColor: borderColors.owner,
                                            borderWidth: 1,
                                            borderRadius: 0
                                        },
                                        {
                                            label: 'Driver',
                                            data: driver,
                                            backgroundColor: colors.driver,
                                            borderColor: borderColors.driver,
                                            borderWidth: 1,
                                            borderRadius: 0
                                        },
                                    ]
                                },
                                options: {
                                    plugins: {
                                        legend: {
                                            display: true,
                                            labels: {
                                                font: {
                                                    size: 13,
                                                    family: "'Poppins', sans-serif"
                                                },
                                                color: '#333'
                                            }
                                        },
                                        tooltip: {
                                            backgroundColor: 'rgba(0, 0, 0, 0.75)',
                                            titleColor: '#fff',
                                            bodyColor: '#fff',
                                            borderColor: '#ddd',
                                            borderWidth: 1,
                                            padding: 10,
                                            titleFont: {
                                                weight: 'bold',
                                                size: 13
                                            },
                                            bodyFont: {
                                                size: 12
                                            }
                                        }
                                    },
                                    scales: {
                                        x: {
                                            grid: {
                                                display: false
                                            },
                                            ticks: {
                                                color: '#444',
                                                font: {
                                                    size: 12
                                                }
                                            }
                                        },
                                        y: {
                                            beginAtZero: true,
                                            suggestedMax: suggestedMax,
                                            grid: {
                                                color: 'rgba(200,200,200,0.3)'
                                            },
                                            ticks: {
                                                color: '#444',
                                                font: {
                                                    size: 12
                                                }
                                            }
                                        }
                                    },
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    animation: {
                                        duration: 1000,
                                        easing: 'easeOutQuart'
                                    }
                                }
                            });
                        } else {
                            // console.log(response.result || 'No data');
                        }
                    } catch (err) {
                        console.error("Chart render error:", err);
                    }
                };
                do_ajax_call(formdata, onsuccess, origin + "/ajax/service/dashBoard_Services.php");
            } catch (e) {
                // console.log(`Error: ${e.message}`);
            }
        };
        const loadAppJobsChart = (type) => {
            try {
                var dateInputId = type === 'GRD' ? '#datefilterDriverJobs' : '#datefilterCustomerJobs';
                var canvasId = type === 'GRD' ? 'driverAppJobsChart' : 'customerAppJobsChart';

                var startDate = moment($(dateInputId).data('daterangepicker').startDate).format("YYYY-MM-DD");
                var endDate = moment($(dateInputId).data('daterangepicker').endDate).format("YYYY-MM-DD");

                if (!startDate || !endDate) return false;

                var formdata = [
                    { name: 'method', value: "app_jobs_chart" },
                    { name: 'startDate', value: startDate },
                    { name: 'endDate', value: endDate },
                    { name: 'jobType', value: type }
                ];

                var onsuccess = function(data) {
                    try {
                        var response = JSON.parse(data);
                        if (response.type == 1) {
                            if (type === 'GRD' && driverAppJobsChartInst) driverAppJobsChartInst.destroy();
                            if (type === 'GRC' && customerAppJobsChartInst) customerAppJobsChartInst.destroy();

                            var ctx = document.getElementById(canvasId).getContext('2d');
                            var newChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: response.dates,
                                    datasets: [
                                        { label: 'Posted', data: response.posted, backgroundColor: '#3498db' },
                                        { label: 'Accepted', data: response.accepted, backgroundColor: '#f1c40f' },
                                        { label: 'Completed', data: response.completed, backgroundColor: '#2ecc71' },
                                        { label: 'Cancelled', data: response.cancelled, backgroundColor: '#e74c3c' }
                                    ]
                                },
                                options: {
                                    responsive: true, maintainAspectRatio: false,
                                    plugins: { legend: { display: true } },
                                    scales: {
                                        x: { grid: { display: false } },
                                        y: { beginAtZero: true }
                                    }
                                }
                            });

                            if (type === 'GRD') driverAppJobsChartInst = newChart;
                            if (type === 'GRC') customerAppJobsChartInst = newChart;
                        }
                    } catch (err) { console.error("Chart render error:", err); }
                };
                do_ajax_call(formdata, onsuccess, origin + "/ajax/service/dashBoard_Services.php");
            } catch (e) {}
        };
        const jobschart = () => {
            try {
                var startDate = moment($('#datefilterJobs').data('daterangepicker').startDate).format("YYYY-MM-DD");
                var endDate = moment($('#datefilterJobs').data('daterangepicker').endDate).format("YYYY-MM-DD");
                var formdata = [{
                        name: 'method',
                        value: "jobsReport_List"
                    },
                    {
                        name: 'startDate',
                        value: startDate
                    },
                    {
                        name: 'endDate',
                        value: endDate
                    }
                ];
                var onsuccess = function(data) {
                    try {
                        var response = JSON.parse(data);
                        if (response.type == 1) {
                            if (jobsChart) jobsChart.destroy();
                            let labels = response.dates || [];
                            let jobs = (response.jobs || []).map(x => Number(x) || 0);
                            //   let driver = (response.driver || []).map(x => Number(x) || 0);
                            //   let both = (response.both || []).map(x => Number(x) || 0);
                            //   let d_c = (response.d_c || '');
                            let j_c = (response.tot || 0);
                            $('#j_c').html('<strong>Total Jobs: </strong>' + j_c);
                            const maxVal = Math.max(...jobs);
                            const suggestedMax = maxVal > 0 ? undefined : 1;
                            const colors = {
                                jobs: '#FFC107',
                                // owner: '#87BAC3',
                                // driver:  '#84AE92'
                            };
                            const borderColors = {
                                jobs: '#FFC107',
                                // owner: '#87BAC3',
                                // driver:  '#84AE92'
                            };
                            // const colors = {
                            //      both: '#8A784E',
                            //      owner: '#AEC8A4',
                            //      driver:  '#E7EFC7'
                            // };
                            // const borderColors = {
                            //      both: '#8A784E',
                            //      owner: '#AEC8A4',
                            //      driver:  '#E7EFC7'
                            // };
                            var ctx = document.getElementById('jobschart').getContext('2d');
                            jobsChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                            label: 'Jobs',
                                            data: jobs,
                                            backgroundColor: colors.jobs,
                                            borderColor: borderColors.jobs,
                                            borderWidth: 1,
                                            borderRadius: 0
                                        },
                                        // {
                                        //    label: 'Owner',
                                        //    data: owner,
                                        //    backgroundColor: colors.owner,
                                        //    borderColor: borderColors.owner,
                                        //    borderWidth: 1,
                                        //    borderRadius: 0
                                        // },
                                        // {
                                        //    label: 'Driver',
                                        //    data: driver,
                                        //    backgroundColor: colors.driver,
                                        //    borderColor: borderColors.driver,
                                        //    borderWidth: 1,
                                        //    borderRadius: 0
                                        // },
                                    ]
                                },
                                options: {
                                    plugins: {
                                        legend: {
                                            display: true,
                                            labels: {
                                                font: {
                                                    size: 13,
                                                    family: "'Poppins', sans-serif"
                                                },
                                                color: '#333'
                                            }
                                        },
                                        tooltip: {
                                            backgroundColor: 'rgba(0, 0, 0, 0.75)',
                                            titleColor: '#fff',
                                            bodyColor: '#fff',
                                            borderColor: '#ddd',
                                            borderWidth: 1,
                                            padding: 10,
                                            titleFont: {
                                                weight: 'bold',
                                                size: 13
                                            },
                                            bodyFont: {
                                                size: 12
                                            }
                                        }
                                    },
                                    scales: {
                                        x: {
                                            grid: {
                                                display: false
                                            },
                                            ticks: {
                                                color: '#444',
                                                font: {
                                                    size: 12
                                                }
                                            }
                                        },
                                        y: {
                                            beginAtZero: true,
                                            suggestedMax: suggestedMax,
                                            grid: {
                                                color: 'rgba(200,200,200,0.3)'
                                            },
                                            ticks: {
                                                color: '#444',
                                                font: {
                                                    size: 12
                                                }
                                            }
                                        }
                                    },
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    animation: {
                                        duration: 1000,
                                        easing: 'easeOutQuart'
                                    }
                                }
                            });
                        } else {
                            // console.log(response.result || 'No data');
                        }
                    } catch (err) {
                        console.error("Chart render error:", err);
                    }
                };
                do_ajax_call(formdata, onsuccess, origin + "/ajax/service/dashBoard_Services.php");
            } catch (e) {
                // console.log(`Error: ${e.message}`);
            }
        };
        const ownerchart = () => {
            try {
                var startDate = moment($('#datefilterOwner').data('daterangepicker').startDate).format("YYYY-MM-DD");
                var endDate = moment($('#datefilterOwner').data('daterangepicker').endDate).format("YYYY-MM-DD");
                var formdata = [{
                        name: 'method',
                        value: "ownerReport_List"
                    },
                    {
                        name: 'startDate',
                        value: startDate
                    },
                    {
                        name: 'endDate',
                        value: endDate
                    }
                ];
                // // console.log(formdata, 'hiiiiiiiiiiiiiii')
                var onsuccess = function(data) {
                    try {
                        var response = JSON.parse(data);
                        if (response.type == 1) {
                            if (ownerDayWise) ownerDayWise.destroy();
                            // ✅ Prepare chart data safely
                            let labels = response.dates || [];
                            let kycCreated = (response.kyc_created || []).map(x => Number(x) || 0);
                            let kycCompleted = (response.kyc_completed || []).map(x => Number(x) || 0);
                            let kycReject = (response.kyc_reject || []).map(x => Number(x) || 0);
                            //   let vehicleCompleted = (response.vehicle_completed || []).map(x => Number(x) || 0);
                            //   let both = (response.both || []).map(x => Number(x) || 0);
                            let total = (response.total || 'N/A');
                            //   $('#tot_v').html('New K&V ' + total);
                            // $('#tot_v').html(
                            //   `New KYC <a href="https://console.goride.run/kyc-details" target="_blank" style="color: #007bff; text-decoration: none;">
                            //      ${total}
                            //   </a>`
                            // );
                            const maxVal = Math.max(...kycCreated, ...kycCompleted, ...kycReject);
                            const suggestedMax = maxVal > 0 ? undefined : 1;
                            // 🎨 Elegant color palette
                            const colors = {
                                kycCreated: 'rgba(26, 188, 156, 0.85)', // teal
                                kycCompleted: 'rgba(46, 204, 113, 0.85)', // green
                                kycReject: 'rgba(52, 152, 219, 0.85)', // blue
                                // vehicleCompleted: 'rgba(241, 196, 15, 0.85)',// yellow
                                // both: 'rgba(155, 89, 182, 0.85)'             // purple
                            };
                            const borderColors = {
                                kycCreated: 'rgba(22, 160, 133, 1)',
                                kycCompleted: 'rgba(39, 174, 96, 1)',
                                kycReject: 'rgba(41, 128, 185, 1)',
                                // vehicleCompleted: 'rgba(243, 156, 18, 1)',
                                // both: 'rgba(142, 68, 173, 1)'
                            };
                            var ctx = document.getElementById('ownerchart').getContext('2d');
                            ownerDayWise = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                            label: 'KYC Created',
                                            data: kycCreated,
                                            backgroundColor: colors.kycCreated,
                                            borderColor: borderColors.kycCreated,
                                            borderWidth: 1,
                                            borderRadius: 0
                                        },
                                        {
                                            label: 'KYC Reject',
                                            data: kycReject,
                                            backgroundColor: colors.kycReject,
                                            borderColor: borderColors.kycReject,
                                            borderWidth: 1,
                                            borderRadius: 0
                                        },
                                        {
                                            label: 'KYC Verified',
                                            data: kycCompleted,
                                            backgroundColor: colors.kycCompleted,
                                            borderColor: borderColors.kycCompleted,
                                            borderWidth: 1,
                                            borderRadius: 0
                                        }
                                    ]
                                },
                                options: {
                                    plugins: {
                                        legend: {
                                            display: true,
                                            labels: {
                                                font: {
                                                    size: 13,
                                                    family: "'Poppins', sans-serif"
                                                },
                                                color: '#333'
                                            }
                                        },
                                        tooltip: {
                                            backgroundColor: 'rgba(0, 0, 0, 0.75)',
                                            titleColor: '#fff',
                                            bodyColor: '#fff',
                                            borderColor: '#ddd',
                                            borderWidth: 1,
                                            padding: 10,
                                            titleFont: {
                                                weight: 'bold',
                                                size: 13
                                            },
                                            bodyFont: {
                                                size: 12
                                            }
                                        }
                                    },
                                    scales: {
                                        x: {
                                            grid: {
                                                display: false
                                            },
                                            ticks: {
                                                color: '#444',
                                                font: {
                                                    size: 12
                                                }
                                            }
                                        },
                                        y: {
                                            beginAtZero: true,
                                            suggestedMax: suggestedMax,
                                            grid: {
                                                color: 'rgba(200,200,200,0.3)'
                                            },
                                            ticks: {
                                                color: '#444',
                                                font: {
                                                    size: 12
                                                }
                                            }
                                        }
                                    },
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    animation: {
                                        duration: 1000,
                                        easing: 'easeOutQuart'
                                    }
                                }
                            });
                        } else {
                            // console.log(response.result || 'No data');
                        }
                    } catch (err) {
                        console.error("Chart render error:", err);
                    }
                };
                do_ajax_call(formdata, onsuccess, origin + "/ajax/service/dashBoard_Services.php");
            } catch (e) {
                // console.log(`Error: ${e.message}`);
            }
        }
        const createChart = () => {
            try {
                var ctxHourlySales = document.getElementById('salescharthour').getContext('2d');
                myChartHourlySales = new Chart(ctxHourlySales, {
                    type: 'line',
                    data: {
                        labels: [], // Labels (hours of the day)
                        datasets: [{
                            label: 'Sales Date 1', // Label for the first date's sales
                            data: [], // Data for the first date
                            backgroundColor: '#002d72', // Color for the first line
                            borderColor: '#002d72', // Border color for the first line
                            borderWidth: 1,
                            pointRadius: 0.5,
                            fill: false, // Don't fill the area under the line
                        }, {
                            label: 'Sales Date 2', // Label for the second date's sales
                            data: [], // Data for the second date
                            backgroundColor: '#d50032', // Color for the second line
                            borderColor: '#d50032', // Border color for the second line
                            borderWidth: 1,
                            pointRadius: 0.5,
                            fill: false, // Don't fill the area under the line
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                            },
                        },
                        interaction: {
                            intersect: false,
                        },
                        scales: {
                            x: {
                                display: true,
                                title: {
                                    display: true
                                }
                            },
                            y: {
                                display: true,
                                title: {
                                    display: true
                                },
                                ticks: {
                                    beginAtZero: true, // Ensures the y-axis starts at 0
                                    stepSize: 500, // Increment the y-axis by 500
                                }
                            }
                        },
                        maintainAspectRatio: false,
                        title: {
                            display: true,
                            text: 'Hourly Sales Comparison (Two Dates)' // Updated title for comparison chart
                        }
                    },
                    // height: 400
                });
            } catch (e) {
                // console.log(`Error: ${e.message}`);
            }
        }
        function collect_earning() {
            var formdata = [];
            formdata.push({
                name: 'method',
                value: "collect_earning"
            });
            var post_data = formdata;
            var onsuccess = function(data) {
                var response = JSON.parse(data); // parse the response from the server
                var result_sales = response.result_sales; // parse the response from the server
                if (response != "") {
                    if (response.type == 1) {
                        let total_salesAmt = 0;
                        let card_total = 0;
                        let wallet_total = 0;
                        let coupon_total = 0;
                        let discount_total = 0;
                        let partial_total = 0;
                        let totaleasy = 0;
                        let totalfun = 0;
                        let totalsmart = 0;
                        let totalsuper = 0;
                        let totalsuper2 = 0;
                        let allPartial = 0;
                        // Ensure we're iterating over the parsed JSON response (assuming it's an array)
                        $.each(result_sales, function(index, item) { // Assuming response.data contains the array of items
                            // Process Card payments
                            if (item.paymentType == 'Card') {
                                if (item.rate == '5.00') {
                                    $('#OTAmt4').text(parseInt(item.total_net_value));
                                    totalfun += parseInt(item.total_net_value);
                                    card_total += parseInt(item.total_net_value);
                                }
                                if (item.rate == '15.00') {
                                    $('#OTAmt1').text(parseInt(item.total_net_value));
                                    totaleasy += parseInt(item.total_net_value);
                                    card_total += parseInt(item.total_net_value);
                                }
                                if (item.rate == '35.00') {
                                    $('#OTAmt3').text(parseInt(item.total_net_value));
                                    totalsmart += parseInt(item.total_net_value);
                                    card_total += parseInt(item.total_net_value);
                                }
                                if (item.rate == '75.00') {
                                    $('#OTAmt2').text(parseInt(item.total_net_value));
                                    totalsuper += parseInt(item.total_net_value);
                                    card_total += parseInt(item.total_net_value);
                                }
                                if (item.rate == '100.00') {
                                    $('#OTAmt5').text(parseInt(item.total_net_value));
                                    totalsuper2 += parseInt(item.total_net_value);
                                    card_total += parseInt(item.total_net_value);
                                }
                                $('#OTtotalAmt').html(card_total);
                                // total_salesAmt += card_total;
                            }
                            // Process Wallet payments
                            if (item.paymentType == 'Wallet') {
                                if (item.rate == '5.00') {
                                    $('#WAAmt4').text(parseInt(item.total_net_value));
                                    totalfun += parseInt(item.total_net_value);
                                    wallet_total += parseInt(item.total_net_value);
                                }
                                if (item.rate == '15.00') {
                                    $('#WAAmt1').text(parseInt(item.total_net_value));
                                    totaleasy += parseInt(item.total_net_value);
                                    wallet_total += parseInt(item.total_net_value);
                                }
                                if (item.rate == '35.00') {
                                    $('#WAAmt3').text(parseInt(item.total_net_value));
                                    totalsmart += parseInt(item.total_net_value);
                                    wallet_total += parseInt(item.total_net_value);
                                }
                                if (item.rate == '75.00') {
                                    $('#WAAmt2').text(parseInt(item.total_net_value));
                                    totalsuper += parseInt(item.total_net_value);
                                    wallet_total += parseInt(item.total_net_value);
                                }
                                if (item.rate == '100.00') {
                                    $('#WAAmt5').text(parseInt(item.total_net_value));
                                    totalsuper2 += parseInt(item.total_net_value);
                                    wallet_total += parseInt(item.total_net_value);
                                }
                                $('#WAtotalAmt').html(wallet_total);
                                // total_salesAmt += wallet_total;
                            }
                            // Process Coupon payments
                            if (item.paymentType == 'Coupon') {
                                if (item.rate == '5.00') {
                                    $('#CPAmt4').text(parseInt(item.total_net_value));
                                    totalfun += parseInt(item.total_net_value);
                                    coupon_total += parseInt(item.total_net_value);
                                }
                                if (item.rate == '15.00') {
                                    $('#CPAmt1').text(parseInt(item.total_net_value));
                                    totaleasy += parseInt(item.total_net_value);
                                    coupon_total += parseInt(item.total_net_value);
                                }
                                if (item.rate == '35.00') {
                                    $('#CPAmt3').text(parseInt(item.total_net_value));
                                    totalsmart += parseInt(item.total_net_value);
                                    coupon_total += parseInt(item.total_net_value);
                                }
                                if (item.rate == '75.00') {
                                    $('#CPAmt2').text(parseInt(item.total_net_value));
                                    totalsuper += parseInt(item.total_net_value);
                                    coupon_total += parseInt(item.total_net_value);
                                }
                                if (item.rate == '100.00') {
                                    $('#CPAmt5').text(parseInt(item.total_net_value));
                                    totalsuper2 += parseInt(item.total_net_value);
                                    coupon_total += parseInt(item.total_net_value);
                                }
                                $('#CPtotalAmt').html(coupon_total);
                                // total_salesAmt += coupon_total;
                            }
                            // Process Partial payments
                            if (item.paymentType == 'Partial') {
                                if (item.total_net_value) {
                                    $('#OPCardAmt1').text(parseInt(item.total_net_value));
                                    card_total += parseInt(item.total_net_value);
                                    $('#OTtotalAmt').html(card_total);
                                }
                                if (item.total_wallet_value) {
                                    wallet_total += parseInt(item.total_wallet_value);
                                    $('#OPWalletAmt1').text(parseInt(item.total_wallet_value));
                                }
                                partial_total += parseInt(item.total_wallet_value) + parseInt(item.total_net_value);
                                $('#OPtotalAmt').html(partial_total);
                                $('#WAtotalAmt').html(wallet_total);
                                // total_salesAmt += partial_total;
                            }
                            if (item.paymentType == 'Discount Coupon') {
                                // $('#DCQTY1').text(item.no_of_dc_tickets);
                                // $('#DCProAmt1').text('5,15, 35, 75');
                                discount_total += parseFloat(item.total_discount_c_amt) + parseFloat(item.total_discount_amt);
                                $('#DCAmt1').html('<span class="fw-bold">' + 'Card : ' + '</span>' + item.total_discount_c_amt + '<br><span class="fw-bold">' + ' Discount : ' + '</span>' + item.total_discount_amt);
                                $('#DCtotalAmt').text(parseFloat(item.total_discount_c_amt) + parseFloat(item.total_discount_amt));
                            }
                            total_salesAmt = coupon_total + wallet_total + card_total + discount_total;
                            if (!isNaN(partial_total)) {
                                $('#salesPartialAmt').text(partial_total)
                            }
                            if (!isNaN(total_salesAmt)) {
                                $('#salesTotalAmt').text(total_salesAmt)
                            }
                            $('#totalAmt1').text(totaleasy);
                            $('#disCountAmt').text(discount_total);
                            $('#totalAmt4').text(totalfun);
                            $('#totalAmt3').text(totalsmart);
                            $('#totalAmt2').text(totalsuper);
                            $('#totalAmt5').text(totalsuper2);
                            $('#dash_draw').text('Draw No #' + item.draw_no);
                        });
                        // Update the page with the final output (assuming 'output' is a key in response)
                        document.getElementById('mess_count').innerHTML = response.output;
                    }
                } else {
                    // Handle empty response or error
                    document.getElementById('mess_count').appendChild(response.result);
                }
            };
            // Make the AJAX call
            do_ajax_call(post_data, onsuccess, origin + "/ajax/service/dashBoard_Services.php");
        }
        const paymenttype = () => {
            try {
                let draw_range = '';
                if ($('#draw_range').val() != '') {
                    draw_range = $('#draw_range').val();
                }
                var formdata = [];
                formdata.push({
                    name: 'method',
                    value: "getPaymentHistoryList"
                });
                formdata.push({
                    name: 'draw_range',
                    value: draw_range
                });
                var post_data = formdata;
                var onsuccess = function(data) {
                    var response = JSON.parse(data);
                    if (response != "") {
                        if (response.type == 1) {
                            if (paymentDrawWise) {
                                paymentDrawWise.destroy();
                            }
                            var ctx = document.getElementById('paymenttype').getContext('2d');
                            paymentDrawWise = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: response.labels,
                                    datasets: [
                                        {
                                            label: 'Overall',
                                            data: response.payoverallg,
                                            backgroundColor: '#20c997',
                                            borderColor: '#20c997',
                                            borderWidth: 1
                                        },
                                        {
                                            label: 'Online',
                                            data: response.payamountg,
                                            backgroundColor: '#002d72',
                                            borderColor: '#002d72',
                                            borderWidth: 1
                                        },
                                        {
                                            label: 'Wallet',
                                            data: response.walletSales,
                                            backgroundColor: '#d50032',
                                            borderColor: '#d50032',
                                            borderWidth: 1
                                        },
                                        {
                                            label: 'Coupon',
                                            data: response.couponList,
                                            backgroundColor: '#28a745',
                                            borderColor: '#28a745',
                                            borderWidth: 1
                                        },
                                        {
                                            label: 'Wallet Deposit',
                                            data: response.walletDeposit,
                                            backgroundColor: '#efcc18',
                                            borderColor: '#efcc18',
                                            borderWidth: 1
                                        }
                                        // ,
                                        // {
                                        //      label: 'Partial Wallet',
                                        //      data: response.partial_walletg,
                                        //      backgroundColor: '#28a745',
                                        //      borderColor: '#28a745',
                                        //      borderWidth: 1
                                        // }
                                    ]
                                },
                                options: {
                                    plugins: {
                                        legend: {
                                            display: true
                                        }
                                    },
                                    scales: {
                                        x: {
                                            offset: true,
                                            grid: {
                                                display: false
                                            }
                                        },
                                        y: {
                                            display: true
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: 'Gradient Donut with custom Start-angle'
                                    },
                                    responsive: true,
                                    maintainAspectRatio: false,
                                }
                            });
                        } else {
                            // document.getElementById('collectdiv').innerHTML = response.result;
                        }
                    }
                }
                do_ajax_call(post_data, onsuccess, origin + "/ajax/service/dashBoard_Services.php");
            } catch (e) {
                // console.log(`Error: ${e.message}`);
            }
        }
        const createDatePicker = (id) => {
            const selector = `#${id}`; // Create the selector with the provided ID
            // Set the start and end dates
            const today = moment();
            const sevenDaysAgo = moment().subtract(7, 'days');
            $(selector).daterangepicker({
                autoUpdateInput: true,
                locale: {
                    cancelLabel: 'Clear'
                },
                // minDate: sevenDaysAgo, 
                maxDate: today,
                opens: 'left',
                startDate: sevenDaysAgo,
                endDate: today,
                maxSpan: {
                    days: 7
                },
            });
            $(selector).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                $(`#payment_type`).val('').selectpicker('refresh');
                // $(`#utm_campaign_source`).val('').selectpicker('refresh');
                // $(`#utm_campaign`).val('').selectpicker('refresh');
                // loginChart();
            });
            $(selector).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        }
        const singleDatePicker = (id) => {
            const selector = `#${id}`; // Create the selector with the provided ID
            // Set the start and end dates
            const today = moment();
            // $(selector).val(today.format('DD/MM/YYYY'));
            $(selector).daterangepicker({
                locale: {
                    cancelLabel: 'Clear' // Change the cancel button label to 'Clear'
                },
                autoApply: true,
                singleDatePicker: true, // Enable single date picker
                maxDate: today, // Set max date to today
                startDate: today, // Set the default start date to today
                opens: 'center', // Set the calendar to open in the center
                autoUpdateInput: false // Set the calendar to open on the left
            });
            $(selector).on('apply.daterangepicker', function(ev, picker) {
                // Format and display the selected date
                $(this).val(picker.startDate.format('MM/DD/YYYY')); // Use only startDate for single date
                // loginChart(); (Uncomment if needed for your chart)
                $(this).trigger('change');
            });
            $(selector).on('cancel.daterangepicker', function(ev, picker) {
                // Clear the input field when the user cancels
                $(this).val('');
                $(this).trigger('change');
            });
        }
        const loadUTM = () => {
            var formdata = [];
            var startDate = moment($('#datefilterLogin').data('daterangepicker').startDate).format("YYYY-MM-DD");
            var endDate = moment($('#datefilterLogin').data('daterangepicker').endDate).format("YYYY-MM-DD");
            // var utm_campaign = $(`#utm_campaign_login`).val();
            // var utm_campaign_source = $(`#utm_campaign_source_login`).val();
            if (startDate == '' || startDate == null || startDate == undefined || endDate == '' || endDate == null || endDate == undefined) {
                showToast(`error`, 'Kindly select the data Range filter', 5000);
                return false;
            }
            formdata.push({
                    name: 'method',
                    value: "loginUTM_LIST"
                }, {
                    name: 'startDate',
                    value: startDate
                }, {
                    name: 'endDate',
                    value: endDate
                },
                // {
                //      name: 'utm_campaign',
                //      value: utm_campaign
                // },
                // {
                //      name: 'utm_campaign_source',
                //      value: utm_campaign_source
                // }
            );
            var post_data = formdata;
            var onsuccess = function(data) {
                var response = JSON.parse(data);
                if (response != "") {
                    $(`#utm_campaign_source_login,#utm_campaign_login`).empty();
                    $(`#utm_campaign_source_login,#utm_campaign_login`).append(`<option value="" selected="">All</option>`);
                    if (response != "") {
                        if (response.type == 1) {
                            response.utm_source.forEach(e => {
                                $(`#utm_campaign_source_login`).append(`<option value="${e.utm_source}">${e.utm_source}</option>`);
                            });
                            response.utm_campaign.forEach(e => {
                                $(`#utm_campaign_login`).append(`<option value="${e.utm_campaign}">${e.utm_campaign}</option>`);
                            });
                        } else {
                            showToast('error', response.result, 5000);
                        }
                    }
                }
            }
            do_ajax_call(post_data, onsuccess, origin + "/ajax/service/dashBoard_Services.php");
        }
        function changeUTM(text, source, campaign) {
            let source_val = $('#utm_campaign').find('option:selected').data('camp');
            let source_data = $('#utm_campaign').val();
            // $(`#utm_campaign`).val('').selectpicker('refresh');
            $('#utm_campaign_source option').each(function() {
                // // console.log($(this).val() == source_val, source_val, $(this));
                let optionValue = $(this).data('source'); // Get the value of the option
                // Check if the option is empty (for "All") or matches the selected source_val
                if (optionValue == '' || optionValue == source_data) {
                    $(this).show(); // Reset display property to default (visible)
                } else {
                    $(this).hide(); // Hide the option
                }
            });
            $(`#utm_campaign_source`).val('').selectpicker('refresh');
            // $(`#utm_campaign`).val(source).selectpicker('refresh');
            // if (text == 'utm_campaign_source') {
            // } else {
            //      $(`#utm_campaign`).selectpicker('refresh');
            // }
        }
        $(function() {
            createDatePicker('datefilterLogin');
            createDatePicker('datefilterVerify');
            createDatePicker('datefilterVehicle');
            createDatePicker('datefilterDriver');
            createDatePicker('datefilterOwner');
            createDatePicker('datefilterJobs');
            createDatePicker('datefilterDriverJobs');
            createDatePicker('datefilterCustomerJobs');
            $(`#datefilterDriverJobs`).on('apply.daterangepicker', function(ev, picker) { loadAppJobsChart('GRD'); });
            $(`#datefilterCustomerJobs`).on('apply.daterangepicker', function(ev, picker) { loadAppJobsChart('GRC'); });
            singleDatePicker('fromdatefilter');
            createDatePicker('datefilterCustomerSignup');
            $(`#datefilterCustomerSignup`).on('apply.daterangepicker', function(ev, picker) {
                customerSignupFlow();
            });
            singleDatePicker('todatefilter');
            uPendingCounts();
            creditsCounts();
            cabCounts();
            seatCounts();
            paymentCounts();
            initPaymentDatePicker();
            stsCounts();
            loadJobs();
            // collect_earning();
            // paymenttype();
            // createChart();
            ownerchart();
            saleschart();
            vehiclechart()
            driverchart();
            jobschart();
            loadAppJobsChart('GRD');
            loadAppJobsChart('GRC');
            let today_dete = new Date().toISOString().split('T')[0];
            $('#dateSale').val(today_dete);
            // fetchHourlySales(this);
            loginChart();
            customerSignupFlow();
            // tapAndWinFlowNew();
            <?php if ($roll_id == 1) { ?>
                setInterval(function() {
                    // collect_earning();
                    // paymenttype();
                    // saleschart();
                    loginChart();
                    // tapAndWinFlowNew();
                }, runtime);
            <?php } ?>
            $(`#datefilter`).on('change', function() {
                try {
                    const selectedDates = $(this).val();
                    if (selectedDates == '' || selectedDates == undefined || selectedDates == null) {
                        showToast('error', 'The selected date has been missing! Pls refresh and try again!', 5000);
                        return false;
                    }
                    var formdata = [];
                    formdata.push({
                        name: 'method',
                        value: "getDropDownList"
                    }, {
                        name: 'date',
                        value: selectedDates
                        });
                    var post_data = formdata;
                    var onsuccess = function(data) {
                        var response = JSON.parse(data);
                        $(`#utm_campaign_source,#utm_campaign`).empty();
                        $(`#utm_campaign_source,#utm_campaign`).append(`<option value="" selected="">All</option>`);
                        if (response != "") {
                            if (response.type == 1) {
                                response.utm_source.forEach(e => {
                                    $(`#utm_campaign_source`).append(`<option value="${e.utm_source}">${e.utm_source}</option>`);
                                });
                                response.utm_campaign.forEach(e => {
                                    $(`#utm_campaign`).append(`<option value="${e.utm_campaign}">${e.utm_campaign}</option>`);
                                });
                            } else {
                                showToast('error', response.result, 5000);
                            }
                        }
                    }
                    do_ajax_call(post_data, onsuccess, origin + "/ajax/service/dashBoard_Services.php");
                } catch (e) {
                    // console.log(`Error: ${e.message}`);
                }
            });
            let today = new Date().toISOString().split('T')[0];
            $('#datefilter').val(today).trigger('change');
            $(`#datefilterLogin`).on('apply.daterangepicker', function(ev, picker) {
                // $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                loadUTM();
                loginChart();
            });
            $(`#datefilterVerify`).on('apply.daterangepicker', function(ev, picker) {
                // $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                // loadUTM();
                saleschart();
            });
            $(`#datefilterDriver`).on('apply.daterangepicker', function(ev, picker) {
                // $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                // loadUTM();
                driverchart();
            });
            $(`#datefilterOwner`).on('apply.daterangepicker', function(ev, picker) {
                // $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                // loadUTM();
                ownerchart();
            });
            $(`#datefilterJobs`).on('apply.daterangepicker', function(ev, picker) {
                // $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                // loadUTM();
                jobschart();
            });
            createDatePricker('dateSale');
            // getData();
        });
        function createDatePricker(id) {
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
                // minDate: moment('2023-02-01').toDate(),
                // minYear: moment().format("YYYY"),
                // maxYear: moment().add(1, 'years').format("YYYY"),
                // maxDate: moment().add(1, 'days').toDate(),
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
        function initPaymentDatePicker() {
            var start = moment();
            var end = moment();
            function cb(start, end) {
                // Update the text inside the date picker trigger
                var dateText = (start.format('YYYY-MM-DD') === end.format('YYYY-MM-DD')) ?
                    start.format('MMM D') :
                    start.format('MMM D') + ' - ' + end.format('MMM D');
                $('#payment_daterange span').html(dateText);
                // Fetch new data based on selected dates
                paymentCounts(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
            }
            $('#payment_daterange').daterangepicker({
                startDate: start,
                endDate: end,
                opens: 'left', // Ensures the calendar doesn't flow off-screen
                // Removed maxSpan, minDate, and maxDate to remove custom picker restrictions
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
                }
            }, cb);
            // Initial trigger to load today's data on page load
            cb(start, end);
        }
        function paymentCounts(startDate, endDate) {
            $.ajax({
                url: origin + "/ajax/service/transaction_services.php",
                type: "POST",
                dataType: "json",
                data: {
                    method: 'payment_dashboard_counts',
                    start_date: startDate,
                    end_date: endDate
                },
                success: function(res) {
                    if (res.type == 1) {
                        // Helper function to format amount with Rupee symbol and 2 decimal places
                        const formatAmt = (amt) => '₹ ' + parseFloat(amt || 0).toFixed(2);
                        // Bind the formatted amounts to the HTML elements
                        $("#pay_total_amount").text(formatAmt(res.total_received));
                        $("#pay_wallet").text(formatAmt(res.total_wallet));
                        $("#pay_purchase").text(formatAmt(res.total_purchase));
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching payment counts:", error);
                }
            });
        }
        function getData() {
            var newdate = $('#dateSale').val();
            var draw = $('#draw_id').val();
            if (newdate != '') {
                var formdate = moment($('#dateSale').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
                var todate = moment($('#dateSale').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");
            } else {
                var formdate = '';
                var todate = '';
            }
            const btn = $('#getURLData');
            var url = origin + "/ajax/service/dashBoard_Services.php";
            // // console.log(date, draw);
            // if (draw != '' && date == '') {
            //     $('#dateSale').prop('disabled', true);
            //     $('#draw_id').prop('disabled', false);
            // }
            // if (draw == '' && date != '') {
            //     $('#draw_id').prop('disabled', true);
            //     $('#dateSale').prop('disabled', false);
            // }
            $('#OTAmt').text(0);
            $('#WAAmt').text(0);
            // if ((draw != '' || date != '') || (draw == '' && date == '')) {
            if ((formdate != '' && todate != '')) {
                $.ajax({
                    url: url, // URL of the PHP file that returns the data
                    method: 'POST',
                    data: {
                        method: 'sales_report',
                        fromDate: formdate,
                        toDate: todate,
                        // draw: draw,
                    },
                    beforeSend: function() {
                        // Button Loading
                        btn.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="sr-only">Loading...</span>`).prop('disabled', true);
                    },
                    success: function(response) {
                        let data = JSON.parse(response);
                        btn.html(`GO`).prop('disabled', false);
                        let coupon_total = 0;
                        let payment_total = 0;
                        let partial_total = 0;
                        $.each(data, function(index, item) {
                            // // console.log(`From: ${moment(formdate).format('DD MMMM YYYY')} To: ${moment(todate).format('DD MMMM YYYY')}`);
                            let card_total = item.total_c_value ?? 0;
                            let wallet_total = item.total_w_value ?? 0;
                            $('#OTAmt').text(card_total);
                            $('#WAAmt').text(wallet_total);
                            // if (date != '' && draw == '') {
                            //     $('#OTAmt').text(card_total);
                            //     $('#WAAmt').text(wallet_total);
                            // } else if (date == '' && draw != '') {
                            //     $('#OTAmt').text(card_total);
                            //     $('#WAAmt').text(0);
                            // }
                        });
                        $('#draw_id').prop('disabled', false);
                        $('#dateSale').prop('disabled', false);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        btn.html(`GO`).prop('disabled', false);
                    }
                });
            }
        }
        function resetInp(inp) {
            if (inp == 'date') {
                $('#draw_id').val('');
            }
            if (inp == 'draw') {
                $('#dateSale').val('');
            }
        }
        function loadJobs() {
            const container = $('#jobsContainer');
            container.html(`
            <div class="text-center text-muted py-4">
                <div class="spinner-border spinner-border-sm"></div>
                Loading jobs...
            </div>
        `);
            const now = new Date();
            const yearStartDate = `${now.getFullYear()}-01-01`;
            const yearEndDate = `${now.getFullYear()}-12-31`;
            $.ajax({
                url: jobUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    startDate: yearStartDate,
                    endDate: yearEndDate,
                    jobStatus: 'not_complete',
                    method: 'jobList'
                },
                success: function(response) {
                    if (!response.result || response.result.length === 0) {
                        container.html(`
                        <div class="text-center text-muted py-4">
                            No active jobs found
                        </div>
                    `);
                        return;
                    }
                    let html = '';
                    response.result.forEach(job => {
                        html += renderJobCard(job);
                    });
                    container.html(html);
                },
                error: function() {
                    container.html(`
                    <div class="text-center text-danger py-4">
                        Failed to load jobs
                    </div>
                `);
                }
            });
        }
        function renderJobCard(job) {
            // console.log(job.bids_details);
            return `
            <div class="job-card-premium mb-3">
                <div class="job-main">
                    <div class="job-left">
                        <div class="job-code">${job.job_no} <span class="bid-badge has-bids ms-3">${job.job_type == 'oneway' ? 'One Way' : 'Round Trip'}</span>
                         <span class="fw-semibold ms-3" style="font-size:13px;">
                         <a href="https://console.goride.run/kyc-verify/verify/${job.user_id}/${job.kd_id}"
                            class="text-decoration"
                            target="_blank"
                            rel="noopener noreferrer">
                            ${job.name}
                            </a>
                         </span>
                        </div>
                        <div class="job-route">
                            <i class="bi bi-geo-alt-fill"></i>
                            ${job.from_place}
                            <span class="arrow">→</span>
                            ${job.to_place}
                        </div>
                        <div class="job-meta">
                            <span>
                                <i class="bi bi-calendar-event"></i>
                                Pickup: ${job.pickup_date}
                            </span>
                            <span>
                                <i class="bi bi-people-fill"></i>
                                ${job.pass_count} Pax
                            </span>
                            <span>
                                <i class="bi bi-clock-history"></i>
                                ${job.duration}
                            </span>
                        </div>
                    </div>
                    <div class="job-right">
                        <div class="bid-badge has-amount">
                                ₹ ${job.fare}
                        </div>
                        <div class="bid-badge ${job.bid_count > 0 ? 'has-bids' : 'no-bids'}">
                            ${job.bid_count} Bids
                        </div>
                        <button class="btn btn-sm btn-outline-primary mt-2"
                                onclick="toggleBids(${job.id})">
                                View Bids
                            </button></br>
                      ${
                        job.job_status == 'created' && job.confirm_status == 0
                        ? `<div>
                              <i class="fas fa-check-circle text-success fa-1x me-2 take-action"
                                 style="cursor:pointer;"
                                 data-id="${job.id}"
                                 data-user-id="${job.user_id}"
                                 data-from-place="${job.from_place}"
                                 title="Take Action"></i>
                           </div>`
                        : ''
                    }
                          <ul style="list-style:none; padding:0; margin-top:15px;
                                display:flex; flex-direction:row;
                                gap:12px; justify-content:flex-end; align-items:center;">
                        <li style="display:flex; align-items:center;">
                            <a href="https://wa.me/${job.mobile}"
                            target="_blank"
                            rel="noopener noreferrer"
                            title="Chat on WhatsApp"
                            style="width:32px; height:32px;
                                    display:flex; align-items:center; justify-content:center;
                                    border-radius:50%;
                                    background:#eafaf1;
                                    color:#25D366;
                                    text-decoration:none;">
                                <i class="fa-brands fa-whatsapp" style="font-size:20px;"></i>
                            </a>
                        </li>
                            <li style="display:flex; align-items:center;">
                                <a href="tel:${job.mobile}"
                                title="Call"
                                style="width:32px; height:32px;
                                        display:flex; align-items:center; justify-content:center;
                                        border-radius:50%;
                                        background:#eef4ff;
                                        color:#0d6efd;
                                        text-decoration:none;">
                                    <i class="fa-solid fa-phone" style="font-size:15px;"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="job-bids" id="bids-${job.id}" style="display:none;"></div>
        </div>
        `;
        }
        $(document).on("click", ".cancel-job", function() {
            let jobId = $(this).data("id");
            let userid = $(this).data("userid");
            // console.log(jobId);
            // console.log(userid);
            if (!jobId) return;
            if (!confirm("Are you sure you want to cancel this job?")) return;
            $.ajax({
                url: origin + "/ajax/service/jobServices.php",
                method: "POST",
                dataType: "json",
                data: {
                    method: "cancelJob",
                    job_id: jobId,
                    user_id: userid
                },
                success: function(res) {
                    if (res.type === 1) {
                        toast('success', res.msg);
                    } else {
                        toast('error', res.msg);
                    }
                },
                error: function() {
                    toast('error', 'Something went wrong while cancelling job.');
                }
            });
        });
        $(document).on("click", ".delete-job", function() {
            let jobId = $(this).data("id");
            if (!jobId) return;
            if (!confirm("Are you sure you want to delete this job?")) return;
            $.ajax({
                url: origin + "/ajax/service/jobServices.php",
                method: "POST",
                dataType: "json",
                data: {
                    method: "deleteJob",
                    job_id: jobId
                },
                success: function(res) {
                    if (res.type === 1) {
                        toast('success', res.msg);
                    } else {
                        toast('error', res.msg);
                    }
                },
                error: function() {
                    toast('error', 'Something went wrong while cancelling job.');
                }
            });
        });
        function toggleBids(jobId) {
            const el = $('#bids-' + jobId);
            // Toggle close
            if (el.is(':visible')) {
                el.slideUp();
                return;
            }
            // Loader
            el.html(`
            <div class="text-muted small py-2">
                <div class="spinner-border spinner-border-sm me-1"></div>
                Loading bids...
            </div>
        `).slideDown();
            $.ajax({
                url: jobUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    method: 'bid_details',
                    job_id: jobId
                },
                success: function(response) {
                    if (!response.result || Object.keys(response.result).length === 0) {
                        el.html('<div class="text-muted small">No bids yet</div>');
                        return;
                    }
                    let html = `
                    <div class="bid-list-scroll">
                `;
                    Object.entries(response.result).forEach(([driverId, bid]) => {
                        html += `
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <div class="fw-semibold">${bid.name}</div>
                                <div class="small text-muted">
                                    <i class="bi bi-telephone-fill me-1"></i>
                                    ${bid.mobile ?? 'N/A'}
                                </div>
                                <div class="small text-muted text-capitalize">
                                    Status: ${bid.status}
                                </div>
                            </div>
                            <div class="fw-bold text-success">
                                ₹${Number(bid.amount).toLocaleString()}
                            </div>
                        </div>
                    `;
                    });
                    html += `</div>`;
                    el.html(html);
                },
                error: function() {
                    el.html(`
                    <div class="text-danger small">
                        Failed to load bid details
                    </div>
                `);
                }
            });
        }
        // 1. Initialize the Date Pickers specifically for the Job Details Cards
        function initJobDetailsDatePicker() {
            var start = moment();
            var end = moment();
            var rangesObj = {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            };

            // Driver Tab Setup
            function cbDriver(start, end) {
                var dateText = (start.format('YYYY-MM-DD') === end.format('YYYY-MM-DD')) ? start.format('MMM D') : start.format('MMM D') + ' - ' + end.format('MMM D');
                $('#job_summary_daterange span').html(dateText);
                fetchJobDetailsSummary(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'), 'GRD', '');
            }
            $('#job_summary_daterange').daterangepicker({ startDate: start, endDate: end, opens: 'left', ranges: rangesObj }, cbDriver);
            cbDriver(start, end);

            // Customer Tab Setup
            function cbCustomer(start, end) {
                var dateText = (start.format('YYYY-MM-DD') === end.format('YYYY-MM-DD')) ? start.format('MMM D') : start.format('MMM D') + ' - ' + end.format('MMM D');
                $('#job_summary_daterange_cus span').html(dateText);
                fetchJobDetailsSummary(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'), 'GRC', '_cus');
            }
            $('#job_summary_daterange_cus').daterangepicker({ startDate: start, endDate: end, opens: 'left', ranges: rangesObj }, cbCustomer);
            cbCustomer(start, end);
        }

        // 2. The AJAX call to get the counts
        function fetchJobDetailsSummary(startDate, endDate, jobType, suffix) {
            $.ajax({
                url: origin + "/ajax/service/dashBoard_Services.php",
                type: "POST",
                dataType: "json",
                data: {
                    method: 'job_details_summary',
                    start_date: startDate,
                    end_date: endDate,
                    job_type: jobType
                },
                success: function(res) {
                    if (res.type == 1) {
                        $('#jd_total' + suffix).text(res.data.total_jobs);
                        $('#jd_unassigned' + suffix).text(res.data.unassigned_jobs);
                        $('#jd_assigned' + suffix).text(res.data.assigned_jobs);
                        $('#jd_website' + suffix).text(res.data.website_jobs);
                        $('#jd_cancelled' + suffix).text(res.data.cancelled_jobs);
                        $('#jd_expired' + suffix).text(res.data.expired_jobs);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching Job Details Summary:", error);
                }
            });
        }
        
        $(function() {
            // Trigger the independent initializations
            initJobDetailsDatePicker();
        });
    </script>
<?php endif; ?>