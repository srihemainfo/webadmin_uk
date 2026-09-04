<head>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<style>
    .push-card input[type="text"], 
    .push-card input[type="file"],
    .push-card select {
        border: 1px solid #ced4da;
        height: 40px;
        padding: 8px 15px;
        border-radius: 5px;
        font-size: 15px;
    }
    
    .push-card textarea.custom-textarea {
        width: 100%;
        min-height: 40px;
        padding: 10px 15px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        background-color: #fcfcfc;
        font-size: 15px;
        resize: vertical;
    }

    .radio-group-container {
        display: flex;
        gap: 25px;
        align-items: center;
        margin-top: 10px;
        padding: 10px 0;
        flex-wrap: wrap;
    }
    .custom-radio-label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        margin: 0;
    }
    .custom-radio-label input[type="radio"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        margin: 0;
    }

    #push_report_1 td, #push_report_1 th, #push_report_scheduled td, #push_report_scheduled th {
        padding: 16px 12px !important;
        vertical-align: middle;
        font-size: 15px;
    }
    .modal-user-table td, .modal-user-table th {
        font-size: 15px; 
        vertical-align: middle;
    }
    .modal-user-table {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .modal-user-table thead th {
        position: sticky;
        top: 0;
        background-color: #f8f9fa !important;
        z-index: 10;
        border-bottom: 2px solid #dee2e6 !important;
    }
    
    .swal2-container {
        z-index: 999999 !important;
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

    #customTabs .custom-tab-btn, #reportTabs .custom-tab-btn {
        background-color: #f8f9fa !important;
        color: #333 !important;
        border: 1px solid #ccc !important;
        border-radius: 4px;
        transition: none; 
        opacity: 1 !important; 
    }
    #customTabs .custom-tab-btn.active, #reportTabs .custom-tab-btn.active {
        background-color: #007bff !important; 
        color: #ffffff !important; 
        border-color: #007bff !important;
    }
    
    #customTabs .badge-success {
        background-color: #d4edda !important;
        color: #000000 !important;
        border: 1px solid #c3e6cb !important;
        opacity: 1 !important;
    }
    #customTabs .badge-danger {
        background-color: #f8d7da !important;
        color: #000000 !important;
        border: 1px solid #f5c6cb !important;
        opacity: 1 !important;
    }

    .modal-close-btn {
        background: none;
        border: none;
        font-size: 24px;
        font-weight: bold;
        color: #666;
        cursor: pointer;
        padding: 0;
        margin-left: auto;
    }
    .modal-close-btn:hover {
        color: #000;
    }

    .route-autocomplete-wrapper { 
        position: relative; 
    }
    #route-autocomplete-list {
        position: absolute;
        top: 100%;
        left: 0;
        min-width: 300px;
        background: #fff;
        border: 1px solid #ced4da;
        border-radius: 4px;
        max-height: 250px;
        overflow-y: auto;
        z-index: 9999;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        display: none;
        padding: 0;
        margin: 4px 0 0 0;
        list-style: none;
    }
    #route-autocomplete-list li {
        padding: 8px 12px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
        line-height: 1.3;
    }
    #route-autocomplete-list li:hover { background-color: #f8f9fa; }
    #route-autocomplete-list li:last-child { border-bottom: none; }
</style>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            
            <div class="page-header mt-4 mb-4">
                <h1 class="page-title" style="font-size: 24px;">
                    <a href="javascript:history.go(-1)" class="btn btn-light btn-sm mr-3 border rounded-circle shadow-sm" style="width: 35px; height: 35px; padding: 6px;">
                        <i class="fa fa-chevron-left"></i>
                    </a>
                    Push Notification
                </h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Push Notification</li>
                    </ol>
                </div>
            </div>
            
            <div class="card shadow-sm mb-5 push-card">
                <div class="card-body p-4">
                    <div class="row align-items-start">
                        
                        <div class="col-lg-4 mb-4">
                            <label class="form-label text-muted text-uppercase small font-weight-bold">Select Target</label>
                            <div class="radio-group-container">
                                <label class="custom-radio-label"><input type="radio" name="target_user" value="driver" checked> Driver</label>
                                <label class="custom-radio-label"><input type="radio" name="target_user" value="customer"> Customer</label>
                                <label class="custom-radio-label"><input type="radio" name="target_user" value="both" > Both</label>
                                <label class="custom-radio-label"><input type="radio" name="target_user" value="rm" > RM</label>
                                <label class="custom-radio-label"><input type="radio" name="target_user" value="state" > State Wise</label>
                                <label class="custom-radio-label"><input type="radio" name="target_user" value="specific_mobiles" > Specific</label>
                                
                                <button type="button" id="btn_open_rm_modal" class="btn btn-sm btn-info font-weight-bold ml-2 shadow-sm" style="display: none; height: 32px;" onclick="openRmModal()">
                                    <i class="fa fa-users"></i> Select Drivers
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-lg-2 mb-4">
                            <label class="form-label text-muted text-uppercase small font-weight-bold">Title</label>
                            <input type="text" id="push_title" class="form-control" placeholder="Enter Notification Title...">
                        </div>

                        <div class="col-lg-2 mb-4 route-autocomplete-wrapper">
                            <label class="form-label text-muted text-uppercase small font-weight-bold">Route (Optional)</label>
                            <input type="text" id="push_route" class="form-control" placeholder="Search route (e.g., /home)" autocomplete="off">
                            <ul id="route-autocomplete-list"></ul>
                        </div>
                        
                        <div class="col-lg-2 mb-4">
                            <label class="form-label text-muted text-uppercase small font-weight-bold">Image (Optional)</label>
                            <input type="file" id="push_image" class="form-control" accept="image/jpeg, image/png, image/jpg" style="padding-top:5px;">
                        </div>

                        <div class="col-lg-2 mb-4">
                            <label class="form-label text-muted text-uppercase small font-weight-bold">Message Body</label>
                            <textarea id="push_body" class="custom-textarea" placeholder="Type message..."></textarea>
                        </div>
                        
                        <div class="col-lg-12 mb-4" id="state_container" style="display: none; background: #eef2f5; padding: 15px; border-radius: 5px; border: 1px solid #e9ecef;">
                            <div class="row">
                                <div class="col-lg-4">
    <label id="state_label" class="form-label text-muted text-uppercase small font-weight-bold">Select State (Customers)</label>
    <select id="state_dropdown" class="form-control" onchange="fetchStateMobiles()">
        <option value="">-- Select State --</option>
        </select>
</div>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-4" id="specific_mobiles_container" style="display: none; background: #f8f9fa; padding: 15px; border-radius: 5px; border: 1px solid #e9ecef;">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label class="form-label text-muted text-uppercase small font-weight-bold">Target User Type</label>
                                    <select id="specific_mobiles_type" class="form-control">
                                        <option value="customer">Customers</option>
                                        <option value="driver">Drivers</option>
                                    </select>
                                </div>
                                <div class="col-lg-9">
                                    <label class="form-label text-muted text-uppercase small font-weight-bold">
                                        Enter Mobile Numbers (Comma Separated) - <span class="text-danger" style="text-transform: none; font-size: 14px;">Adding 91 is MUST *</span>
                                    </label>
                                    <textarea id="specific_mobiles_input" class="custom-textarea" placeholder="e.g., 919876543210, 918765432109" style="height: 60px;"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 mb-4 ml-auto d-flex align-items-end">
                            <div class="d-flex w-100 gap-2">
                                <button class="btn btn-warning w-50 font-weight-bold shadow-sm" id="schedule_push_btn" onclick="openScheduleModal()" style="height: 42px; font-size: 16px; margin-right: 10px;">
                                    <i class="fa fa-clock-o mr-1"></i> Schedule
                                </button>
                                <button class="btn btn-primary w-50 font-weight-bold shadow-sm" id="send_push_btn" onclick="sendPushNotification()" style="height: 42px; font-size: 16px;">
                                    Send
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card push-card">
                <div class="card-body p-4">
                    <div class="row mb-4 align-items-end justify-content-between">
                        
                        <div class="col-lg-5 mb-3 mb-lg-0">
                            <ul class="nav nav-pills" id="reportTabs" role="tablist" style="gap: 15px;">
                                <li class="nav-item">
                                    <a class="nav-link custom-tab-btn active px-4 py-2 font-weight-bold" 
                                       id="regular-tab" 
                                       data-toggle="pill" 
                                       data-target="#regular-report" 
                                       href="javascript:void(0);" 
                                       role="tab" 
                                       onclick="switchTab('regular')" 
                                       style="cursor: pointer;">
                                        Regular Notifications
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link custom-tab-btn px-4 py-2 font-weight-bold" 
                                       id="scheduled-tab" 
                                       data-toggle="pill" 
                                       data-target="#scheduled-report" 
                                       href="javascript:void(0);" 
                                       role="tab" 
                                       onclick="switchTab('scheduled')" 
                                       style="cursor: pointer;">
                                        Scheduled Notifications
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="col-lg-7 d-flex justify-content-end align-items-end gap-2 flex-wrap">
                            <div class="mr-2" style="width: 250px;">
                                <label class="text-muted font-weight-bold small text-uppercase">Date Range</label>
                                <div id="reportrange213" style="background: #fff; cursor: pointer; padding: 8px 15px; border: 1px solid #ccc; width: 100%; border-radius:5px; display: flex; justify-content: space-between; align-items: center; height: 40px;">
                                    <div>
                                        <i class="fa fa-calendar text-primary"></i>&nbsp;
                                        <span class="font-weight-bold text-dark" style="font-size: 15px;"></span> 
                                    </div>
                                    <i class="fa fa-caret-down text-muted"></i>
                                </div>
                            </div>
                            <div class="mr-2" style="width: 150px;">
                                <label class="text-muted font-weight-bold small text-uppercase">Delivery Status</label>
                                <select name="p_status" id="p_status" class="form-control">
                                    <option value="">All Statuses</option>
                                    <option value="2">In Progress</option>
                                    <option value="1">Delivered</option>
                                    <option value="0">Not Delivered</option>
                                </select>
                            </div>
                            <div>
                                <button type="button" id="push_search" class="btn btn-dark font-weight-bold" onclick="searchPush()" style="height: 40px; font-size: 15px; padding: 0 20px;">Apply Filter</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-content mt-4">
                        <div class="tab-pane fade show active" id="regular-report" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover border text-nowrap w-100" id="push_report_1">
                                    <thead>
                                        <tr>
                                            <th class="py-3">Sent At</th>
                                            <th class="py-3">Sent By</th>
                                            <th class="py-3">Target</th>
                                            <th class="py-3">Image</th>
                                            <th class="py-3">Title</th>
                                            <th class="py-3">Message</th>
                                            <th class="py-3">Route</th>
                                            <th class="py-3 text-center">Status</th>
                                            <th class="py-3 text-center">Actions</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="scheduled-report" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover border text-nowrap w-100" id="push_report_scheduled">
                                    <thead>
                                        <tr>
                                            <th class="py-3">Scheduled For</th>
                                            <th class="py-3">Created At</th>
                                            <th class="py-3">Scheduled By</th>
                                            <th class="py-3">Target</th>
                                            <th class="py-3">Image</th>
                                            <th class="py-3">Title</th>
                                            <th class="py-3">Message</th>
                                            <th class="py-3">Target State</th>
                                            <th class="py-3 text-center">Status</th>
                                            <th class="py-3 text-center">Success</th>
                                            <th class="py-3 text-center">Failed</th>
                                            <th class="py-3 text-center">Actions</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<div class="modal fade" id="viewPushModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border: 1px solid #bbb;">
            <div class="modal-header p-4 d-flex align-items-center" style="border-bottom: 1px solid #eee;">
                <h4 class="modal-title font-weight-bold m-0 w-100 d-flex justify-content-between align-items-center" style="color: #333;">
                    <span><i class="fa fa-list-alt text-primary mr-2"></i> Notification Details</span>
                    <button class="btn btn-sm btn-info font-weight-bold shadow-sm" onclick="copyMobileNumbers()" style="margin-right: 15px;">
                        <i class="fa fa-copy"></i> Copy Numbers
                    </button>
                </h4>
                <button type="button" class="modal-close-btn" onclick="$('#viewPushModal').modal('hide');" aria-label="Close">
                    &times;
                </button>
            </div>
            <div class="modal-body p-4">
                
                <div class="p-3 mb-4" style="border: 1px solid #ccc; border-radius: 4px;">
                    <div id="modal-image-container" class="text-center" style="display:none; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
                        <img id="modal-image" src="" alt="Push Image" style="max-width: 100%; max-height: 200px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    </div>
                    
                    <div class="mb-3">
                        <span style="color: #666; font-weight: bold; font-size: 13px; text-transform: uppercase;">Title:</span><br>
                        <span id="modal-title" style="color: #000; font-size: 16px; font-weight: normal;"></span>
                    </div>
                    <div>
                        <span style="color: #666; font-weight: bold; font-size: 13px; text-transform: uppercase;">Message:</span><br>
                        <span id="modal-body" style="color: #000; font-size: 15px; font-weight: normal; word-wrap: break-word; white-space: normal;"></span>
                    </div>
                </div>

                <ul class="nav nav-pills mb-4" id="customTabs" role="tablist">
                    <li class="nav-item" style="margin-right: 15px;">
                        <a class="nav-link custom-tab-btn active px-4 py-2 font-weight-bold" data-target="#delivered-tab" role="tab" style="cursor: pointer;">
                            Delivered <span id="delivered-count" style="font-size: 15px; margin-left:6px;">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link custom-tab-btn px-4 py-2 font-weight-bold" data-target="#not-delivered-tab" role="tab" style="cursor: pointer;">
                            Not Delivered <span id="not-delivered-count" style="font-size: 15px; margin-left:6px;">0</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content" style="border: 1px solid #ccc; border-radius: 4px;">
                    
                    <div class="tab-pane active p-0" id="delivered-tab" role="tabpanel">
                        <div class="p-3 d-flex justify-content-between align-items-center" style="border-bottom: 1px solid #ccc;">
                            <input type="text" class="form-control w-50" id="filterDelivered" placeholder="Search User Details or Mobile..." style="height:40px; font-size: 15px; border: 1px solid #ccc;">
                            <div class="form-check m-0 d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" id="selectAllDelivered" style="width:18px; height:18px; margin-top:0;">
                                <label class="form-check-label font-weight-bold ml-2" for="selectAllDelivered" style="cursor:pointer; padding-top:2px; font-size: 15px; color: #333;">Select All</label>
                            </div>
                        </div>
                        <div class="modal-user-table table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;" class="text-center">S.No</th>
                                        <th style="width: 80px;" class="text-center">Select</th>
                                        <th>User Details</th>
                                        <th>Mobile No.</th>
                                        <th class="text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="delivered-list"></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane p-0" id="not-delivered-tab" role="tabpanel" style="display: none;">
                        <div class="p-3 d-flex justify-content-between align-items-center" style="border-bottom: 1px solid #ccc;">
                            <input type="text" class="form-control w-50" id="filterNotDelivered" placeholder="Search User Details or Mobile..." style="height:40px; font-size: 15px; border: 1px solid #ccc;">
                            <div class="form-check m-0 d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" id="selectAllNotDelivered" style="width:18px; height:18px; margin-top:0;">
                                <label class="form-check-label font-weight-bold ml-2" for="selectAllNotDelivered" style="cursor:pointer; padding-top:2px; font-size: 15px; color: #333;">Select All</label>
                            </div>
                        </div>
                        <div class="modal-user-table table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;" class="text-center">S.No</th>
                                        <th style="width: 80px;" class="text-center">Select</th>
                                        <th>User Details</th>
                                        <th>Mobile No.</th>
                                        <th class="text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="not-delivered-list"></tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer p-3 d-flex justify-content-end" style="border-top: 1px solid #eee;">
                <button type="button" class="btn btn-warning px-4 py-2 font-weight-bold" id="resendFromModalBtn" style="font-size: 15px;">
                    <i class="fa fa-refresh mr-1"></i> Resend to Selected
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rmSelectionModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border: 1px solid #bbb;">
            <div class="modal-header p-4 d-flex align-items-center" style="border-bottom: 1px solid #eee;">
                <h4 class="modal-title font-weight-bold m-0" style="color: #333;">
                    <i class="fa fa-users text-primary mr-2"></i> Select RM & Drivers
                </h4>
                <button type="button" class="modal-close-btn" onclick="$('#rmSelectionModal').modal('hide');" aria-label="Close">
                    &times;
                </button>
            </div>
            <div class="modal-body p-4">
                
                <div class="mb-4">
                    <label class="text-muted font-weight-bold small text-uppercase">1. Select Regional Manager (RM)</label>
                    <select id="rm_dropdown" class="form-control" onchange="fetchRmDrivers()">
                        <option value="">-- Loading RMs --</option>
                    </select>
                </div>

                <div id="rm_drivers_container" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="text-muted font-weight-bold small text-uppercase m-0">2. Select Drivers</label>
                        <div class="form-check m-0 d-flex align-items-center">
                            <input class="form-check-input" type="checkbox" id="selectAllRmDrivers" style="width:18px; height:18px; margin-top:0;">
                            <label class="form-check-label font-weight-bold ml-2" for="selectAllRmDrivers" style="cursor:pointer; padding-top:2px; font-size: 15px; color: #333;">Select All</label>
                        </div>
                    </div>
                    
                    <input type="text" class="form-control mb-3" id="filterRmDrivers" placeholder="Search Driver Name..." style="height:40px; font-size: 15px; border: 1px solid #ccc;">
                    
                    <div class="modal-user-table table-responsive" style="border: 1px solid #ccc; border-radius: 4px;">
                        <table class="table table-hover mb-0">
                            <thead style="background: #f8f9fa;">
                                <tr>
                                    <th style="width: 60px;" class="text-center">S.No</th>
                                    <th style="width: 80px;" class="text-center">Select</th>
                                    <th>Driver Details</th>
                                </tr>
                            </thead>
                            <tbody id="rm-driver-list">
                                <tr class="empty-list-row"><td colspan="3" class="text-center text-muted font-italic py-4">Please select an RM first.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer p-3 d-flex justify-content-end" style="border-top: 1px solid #eee;">
                <button type="button" class="btn btn-success px-4 py-2 font-weight-bold" onclick="saveRmSelection()" style="font-size: 15px;">
                    <i class="fa fa-check mr-1"></i> Save Selection
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="schedulePushModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border: 1px solid #bbb;">
            <div class="modal-header p-4 d-flex align-items-center" style="border-bottom: 1px solid #eee;">
                <h4 class="modal-title font-weight-bold m-0" style="color: #333;">
                    <i class="fa fa-clock-o text-warning mr-2"></i> Schedule Notification
                </h4>
                <button type="button" class="modal-close-btn" onclick="$('#schedulePushModal').modal('hide');" aria-label="Close" style="background:none; border:none; font-size:1.5rem;">
                    &times;
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label text-muted text-uppercase small font-weight-bold">Select Date</label>
                    <input type="date" class="form-control" id="schedule_push_date" style="height: 45px; font-size: 16px; border: 1px solid #ced4da; cursor: pointer;">
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted text-uppercase small font-weight-bold">Select Time (Hourly Slots Only)</label>
                    <select class="form-control form-select" id="schedule_push_time" style="height: 45px; font-size: 16px; border: 1px solid #ced4da; cursor: pointer;">
                        <option value="00:00:00">12:00 AM</option>
                        <option value="01:00:00">01:00 AM</option>
                        <option value="02:00:00">02:00 AM</option>
                        <option value="03:00:00">03:00 AM</option>
                        <option value="04:00:00">04:00 AM</option>
                        <option value="05:00:00">05:00 AM</option>
                        <option value="06:00:00">06:00 AM</option>
                        <option value="07:00:00">07:00 AM</option>
                        <option value="08:00:00">08:00 AM</option>
                        <option value="09:00:00">09:00 AM</option>
                        <option value="10:00:00">10:00 AM</option>
                        <option value="11:00:00">11:00 AM</option>
                        <option value="12:00:00">12:00 PM (Noon)</option>
                        <option value="13:00:00">01:00 PM</option>
                        <option value="14:00:00">02:00 PM</option>
                        <option value="15:00:00">03:00 PM</option>
                        <option value="16:00:00">04:00 PM</option>
                        <option value="17:00:00">05:00 PM</option>
                        <option value="18:00:00">06:00 PM</option>
                        <option value="19:00:00">07:00 PM</option>
                        <option value="20:00:00">08:00 PM</option>
                        <option value="21:00:00">09:00 PM</option>
                        <option value="22:00:00">10:00 PM</option>
                        <option value="23:00:00">11:00 PM</option>
                    </select>
                </div>
                <div class="alert alert-info m-0" style="font-size: 14px;">
                    <i class="fa fa-info-circle mr-1"></i> Notifications can only be scheduled in 1-hour increments starting from the next available hour.
                </div>
            </div>
            <div class="modal-footer p-3 d-flex justify-content-end" style="border-top: 1px solid #eee;">
                <button type="button" class="btn btn-success px-4 py-2 font-weight-bold" onclick="submitScheduledPush()" style="font-size: 15px;">
                    <i class="fa fa-save mr-1"></i> Save Schedule
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reschedulePushModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border: 1px solid #bbb;">
            <div class="modal-header p-4 d-flex align-items-center" style="border-bottom: 1px solid #eee;">
                <h4 class="modal-title font-weight-bold m-0" style="color: #333;">
                    <i class="fa fa-calendar text-primary mr-2"></i> Reschedule Notification
                </h4>
                <button type="button" class="modal-close-btn" onclick="$('#reschedulePushModal').modal('hide');" aria-label="Close" style="background:none; border:none; font-size:1.5rem;">
                    &times;
                </button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="reschedule_push_id">
                <div class="mb-3">
                    <label class="form-label text-muted text-uppercase small font-weight-bold">Select New Date</label>
                    <input type="date" class="form-control" id="reschedule_push_date" style="height: 45px; font-size: 16px; border: 1px solid #ced4da; cursor: pointer;">
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted text-uppercase small font-weight-bold">Select New Time</label>
                    <select class="form-control form-select" id="reschedule_push_time" style="height: 45px; font-size: 16px; border: 1px solid #ced4da; cursor: pointer;">
                        <option value="00:00:00">12:00 AM</option>
                        <option value="01:00:00">01:00 AM</option>
                        <option value="02:00:00">02:00 AM</option>
                        <option value="03:00:00">03:00 AM</option>
                        <option value="04:00:00">04:00 AM</option>
                        <option value="05:00:00">05:00 AM</option>
                        <option value="06:00:00">06:00 AM</option>
                        <option value="07:00:00">07:00 AM</option>
                        <option value="08:00:00">08:00 AM</option>
                        <option value="09:00:00">09:00 AM</option>
                        <option value="10:00:00">10:00 AM</option>
                        <option value="11:00:00">11:00 AM</option>
                        <option value="12:00:00">12:00 PM (Noon)</option>
                        <option value="13:00:00">01:00 PM</option>
                        <option value="14:00:00">02:00 PM</option>
                        <option value="15:00:00">03:00 PM</option>
                        <option value="16:00:00">04:00 PM</option>
                        <option value="17:00:00">05:00 PM</option>
                        <option value="18:00:00">06:00 PM</option>
                        <option value="19:00:00">07:00 PM</option>
                        <option value="20:00:00">08:00 PM</option>
                        <option value="21:00:00">09:00 PM</option>
                        <option value="22:00:00">10:00 PM</option>
                        <option value="23:00:00">11:00 PM</option>
                    </select>
                </div>
                <div class="alert alert-info m-0" style="font-size: 14px;">
                    <i class="fa fa-info-circle mr-1"></i> Rescheduling is restricted to exact hourly slots.
                </div>
            </div>
            <div class="modal-footer p-3 d-flex justify-content-end" style="border-top: 1px solid #eee;">
                <button type="button" class="btn btn-primary px-4 py-2 font-weight-bold" onclick="submitReschedulePush()" style="font-size: 15px;">
                    <i class="fa fa-save mr-1"></i> Update Schedule
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    var origin = window.location.origin;
    var datatable_url = origin + "/ajax/service/datatable_services.php";
    var api_domain = "<?= rtrim(TEST_API_DOMAIN_2 ?? '', '/') ?>";
    
    var admin_session_id = "<?php @session_start(); echo !empty($_SESSION['memid']) ? $_SESSION['memid'] : 1; ?>";

    var globalPushData = {};
    var currentResendContext = {};
    var globalSelectedRmDrivers = []; 
    var rmListLoaded = false; 
    var autoRefreshTimeout = null; 
    var currentActiveTab = 'regular'; 

    $(function() {
        if ($.fn.select2) {
        $('#state_dropdown').select2({
            width: '100%',
            placeholder: "-- Select State --"
        });
    }
        createDatePicker('reportrange213');
        searchPush();
        // Add this inside your $(function() { ... }) block to load states on initial page load
fetchStates($('#specific_mobiles_type').val());

// The updated change listener
$('#specific_mobiles_type').on('change', function() {
    let userType = $(this).val();
    
    // Update the label dynamically
    let labelTxt = userType === 'driver' ? 'Select State (Drivers)' : 'Select State (Customers)';
    $('#state_label').text(labelTxt);
    
    // Clear current selections
    $('#state_dropdown').html('<option value="">Loading States...</option>');
    $('#specific_mobiles_input').val('');
    
    // Fetch new states via AJAX
    fetchStates(userType);
});

function fetchStates(userType) {
    $.ajax({
        url: datatable_url,
        type: 'POST',
        data: { method: 'get_states', user_type: userType },
        dataType: 'json',
        success: function(res) {
            let options = '<option value="">-- Select State --</option>';
            if(res.status && res.data.length > 0) {
                res.data.forEach(function(state) {
                    options += `<option value="${state}">${state}</option>`;
                });
            }
            // Update the HTML
            $('#state_dropdown').html(options);
            
            // --- NEW CODE: Refresh Select2 after new states are loaded ---
            if ($.fn.select2) {
                $('#state_dropdown').select2({
                    width: '100%',
                    placeholder: "-- Select State --"
                });
            }
        },
        error: function() {
            $('#state_dropdown').html('<option value="">Error loading states</option>');
        }
    });
}
        let initialTarget = $('input[name="target_user"]:checked').val();
        if (initialTarget === 'driver' || initialTarget === 'customer' || initialTarget === 'specific_mobiles' || initialTarget === 'state') {
            $('#schedule_push_btn').show();
        } else {
            $('#schedule_push_btn').hide();
        }

        let today = new Date();
        let currentYear = today.getFullYear();
        let currentMonth = String(today.getMonth() + 1).padStart(2, '0');
        let currentDay = String(today.getDate()).padStart(2, '0');
        let todayString = `${currentYear}-${currentMonth}-${currentDay}`;

        $('#schedule_push_date').attr('min', todayString);
        $('#reschedule_push_date').attr('min', todayString);

        $('#schedulePushModal').on('show.bs.modal', function () {
            $('#schedule_push_date').val(todayString);
            validateTimeOptions('schedule_push_date', 'schedule_push_time');
        });

        $('#reschedulePushModal').on('show.bs.modal', function () {
            validateTimeOptions('reschedule_push_date', 'reschedule_push_time');
        });

        $('#schedule_push_date').on('change', function() {
            validateTimeOptions('schedule_push_date', 'schedule_push_time');
        });

        $('#reschedule_push_date').on('change', function() {
            validateTimeOptions('reschedule_push_date', 'reschedule_push_time');
        });

        var appRoutes = [
            { route: "/home", desc: "Main app shell with bottom bar (Rides, Activity, My Posts, More)" },
            { route: "/bookings", desc: "Book a ride- pick from/to, date, vehicle, create booking" },
            { route: "/booking-history", desc: "Booking list" },
            { route: "/notifications", desc: "Notifications inbox" },
            { route: "/wallet", desc: "Wallet balance, top-up" },
            { route: "/wallet/transactions", desc: "Wallet transaction history" },
            { route: "/help", desc: "Help & support" },
            { route: "/refer-and-earn", desc: "Refer & earn" },
            { route: "/how-it-works", desc: "How GoRide works (Education / onboarding)" },
            { route: "/account-profile", desc: "Account profile (name, photo, etc.)" },
            { route: "/account-settings", desc: "Account settings" },
            { route: "/profile-delete-account", desc: "Delete account" },
            { route: "/saved-places", desc: "Saved places (Home, School, Office, custom)" },
            { route: "/cancellation-policy", desc: "Cancellation policy (HTML content)" },
            { route: "/login", desc: "Login screen" },
            { route: "/no-internet", desc: "No internet screen" },
            { route: "/trip-planner", desc: "AI trip planner" },
            { route: "/trip-details", desc: "Trip details (before confirming driver)" },
            { route: "/select-driver", desc: "Choose driver (bids/offers)" },
            { route: "/fare-details", desc: "Fare breakdown" },
            { route: "/ride-confirmed", desc: "Ride confirmed" },
            { route: "/payment-thanks", desc: "Payment thank you" },
            { route: "/pre-available-drivers", desc: "Pre-available drivers list" },
            { route: "/ride-tracking", desc: "Live ride tracking" },
            { route: "/trip-completed", desc: "Trip completed" },
            { route: "/payment", desc: "Payment screen" },
            { route: "/rating", desc: "Rate your ride" },
            { route: "/vehicle-details", desc: "Vehicle details (booking flow)" },
            { route: "/carpool-ride-type", desc: "First-time carpool intro choose passenger vs host style" },
            { route: "/carpool-space", desc: "Full carpool hub (Explore / Offer / My rides tabs inside hub)" },
            { route: "/carpool-explore-solo", desc: "Find a ride (explore/search) standalone" },
            { route: "/carpool-offer-solo", desc: "Post a ride (host offer form) standalone" },
            { route: "/carpool-search-results", desc: "Carpool search results" },
            { route: "/carpool-job-detail", desc: "Carpool job / ride detail" },
            { route: "/carpool-job-open", desc: "Deep link opener for a shared job link" },
            { route: "/carpool-job-chat", desc: "Chat on a carpool job" },
            { route: "/carpool-driver-job-chat-hub", desc: "Driver chat hub for a job" },
            { route: "/carpool-passenger-job-chat-hub", desc: "Passenger chat hub for a job" },
            { route: "/carpool-profile", desc: "Carpool profile (host/passenger profile in carpool)" },
            { route: "/carpool-invite-friends", desc: "Invite friends to carpool" },
            { route: "/my-posts", desc: "Redirect to my post tab" },
            { route: "/carpool-invitations", desc: "Carpool invitations inbox" },
            { route: "/carpool-host", desc: "Host verification hub (KYC steps overview)" },
            { route: "/carpool-host-selfie", desc: "Host selfie step" },
            { route: "/carpool-host-aadhaar", desc: "Host Aadhaar step" },
            { route: "/carpool-host-dl", desc: "Host driving licence step" },
            { route: "/carpool-host-rc", desc: "Host RC step" },
            { route: "/carpool-host-vehicle-type", desc: "Choose vehicle type" },
            { route: "/carpool-host-vehicle", desc: "Host vehicle RC upload" },
            { route: "/carpool-host-vehicle-detail", desc: "Vehicle details form" },
            { route: "/initial-profile", desc: "Complete profile after signup" },
            { route: "/force-update", desc: "Force app update required" },
            { route: "/setup", desc: "App setup screen" }
        ];

        var routeInput = $('#push_route');
        var routeList = $('#route-autocomplete-list');

        function renderRoutes(filterText = '') {
            routeList.empty();
            var matches = appRoutes.filter(item => 
                item.route.toLowerCase().includes(filterText.toLowerCase()) || 
                item.desc.toLowerCase().includes(filterText.toLowerCase())
            );

            if (matches.length === 0) {
                routeList.append('<li><span class="text-muted small">No matches found</span></li>');
            } else {
                matches.forEach(match => {
                    var li = $('<li></li>');
                    li.html(`<strong>${match.route}</strong><br><small class="text-muted">${match.desc}</small>`);
                    li.on('click', function() {
                        routeInput.val(match.route);
                        routeList.hide();
                    });
                    routeList.append(li);
                });
            }
        }

        routeInput.on('focus', function() {
            renderRoutes(routeInput.val());
            routeList.show();
        });

        routeInput.on('input', function() {
            renderRoutes(routeInput.val());
            routeList.show();
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.route-autocomplete-wrapper').length) {
                routeList.hide();
            }
        });

        $('#customTabs .nav-link').on('click', function(e) {
            e.preventDefault();
            $('#customTabs .nav-link').removeClass('active');
            $(this).addClass('active');
            
            var target = $(this).attr('data-target');
            
            $('#viewPushModal .tab-pane').hide().removeClass('active'); 
            
            $(target).fadeIn(200).addClass('active');
        });

$('input[name="target_user"]').on('change', function() {
        let targetVal = $(this).val();

        if (targetVal === 'rm') {
            $('#btn_open_rm_modal').fadeIn(200);
        } else {
            $('#btn_open_rm_modal').hide();
        }

        if (targetVal === 'state') {
            $('#state_container').fadeIn(200);
        } else {
            $('#state_container').hide();
        }

        if (targetVal === 'specific_mobiles' || targetVal === 'state') {
            $('#specific_mobiles_container').fadeIn(200);
            
            // --- NEW LOGIC: Disable textarea for state-wise, enable for specific ---
            if (targetVal === 'state') {
                $('#specific_mobiles_input').prop('disabled', true).css('background-color', '#e9ecef');
            } else {
                $('#specific_mobiles_input').prop('disabled', false).css('background-color', '#fcfcfc');
            }
            
        } else {
            $('#specific_mobiles_container').hide();
        }

        if (targetVal === 'driver' || targetVal === 'customer' || targetVal === 'specific_mobiles' || targetVal === 'state') {
            $('#schedule_push_btn').show();
        } else {
            $('#schedule_push_btn').hide();
        }
    });
    });

    // BUG FIX: Clears dropdowns when target user type is changed to avoid mismatched query results
    $('#specific_mobiles_type').on('change', function() {
        $('#state_dropdown').val('');
        $('#specific_mobiles_input').val('');
    });

    function fetchStateMobiles() {
        let state = $('#state_dropdown').val();
        let userType = $('#specific_mobiles_type').val(); // Pass specific user type to PHP backend

        if (!state) {
            $('#specific_mobiles_input').val('');
            return;
        }
        
        $('#specific_mobiles_input').val('Fetching numbers...');
        
        $.ajax({
            url: datatable_url,
            type: 'POST',
            data: { 
                method: 'get_state_mobiles', 
                state: state, 
                user_type: userType 
            },
            dataType: 'json',
            success: function(res) {
                if(res.status && res.data.length > 0) {
                    $('#specific_mobiles_input').val(res.data.join(', '));
                    toast('success', 'Fetched ' + res.data.length + ' numbers.');
                } else {
                    $('#specific_mobiles_input').val(''); // Safely clears the box if none exist
                    toast('warning', `No ${userType}s found in this state.`);
                }
            },
            error: function() {
                $('#specific_mobiles_input').val('');
                toast('error', 'Server error fetching mobile numbers.');
            }
        });
    }

    function validateTimeOptions(dateElementId, timeElementId) {
        let selectedDate = $('#' + dateElementId).val();
        let today = new Date();
        
        let currentYear = today.getFullYear();
        let currentMonth = String(today.getMonth() + 1).padStart(2, '0');
        let currentDay = String(today.getDate()).padStart(2, '0');
        let todayString = `${currentYear}-${currentMonth}-${currentDay}`;
        
        let currentHour = today.getHours();

        $('#' + timeElementId + ' option').each(function() {
            let optionHour = parseInt($(this).val().split(':')[0], 10);
            
            if (selectedDate === todayString) {
                if (optionHour <= currentHour) {
                    $(this).hide();
                    $(this).prop('disabled', true);
                } else {
                    $(this).show();
                    $(this).prop('disabled', false);
                }
            } else {
                $(this).show();
                $(this).prop('disabled', false);
            }
        });

        if ($('#' + timeElementId + ' option:selected').is(':disabled') || !$('#' + timeElementId).val()) {
            $('#' + timeElementId).val($('#' + timeElementId + ' option:not(:disabled):first').val());
        }
    }
    
    function switchTab(tabName) {
        currentActiveTab = tabName;
        
        $('#reportTabs .nav-link').removeClass('active');
        $('#' + tabName + '-tab').addClass('active');
        
        $('#regular-report').removeClass('show active');
        $('#scheduled-report').removeClass('show active');
        $('#' + tabName + '-report').addClass('show active');
        
        var statusDropdown = $('#p_status');
        statusDropdown.empty();
        
        if (tabName === 'regular') {
            statusDropdown.append('<option value="">All Statuses</option>');
            statusDropdown.append('<option value="2">In Progress</option>');
            statusDropdown.append('<option value="1">Delivered</option>');
            statusDropdown.append('<option value="0">Not Delivered</option>');
        } else {
            statusDropdown.append('<option value="">All Statuses</option>');
            statusDropdown.append('<option value="0">Pending</option>');
            statusDropdown.append('<option value="1">Processed</option>');
            statusDropdown.append('<option value="2">Success</option>');
            statusDropdown.append('<option value="3">Failed</option>');
        }
        
        searchPush(); 
    }

    function openScheduleModal() {
        var target = $('input[name="target_user"]:checked').val();
        
        if (target !== 'customer' && target !== 'driver' && target !== 'specific_mobiles' && target !== 'state') {
            toast('warning', 'Scheduling is only available for Customers, Drivers, Specific numbers, or States.');
            return;
        }

        var title = $('#push_title').val().trim();
        var body = $('#push_body').val().trim();
        if (title === '') { toast('error', 'Please enter a title before scheduling.'); return; }
        if (body === '') { toast('error', 'Please enter a message body before scheduling.'); return; }

        if (target === 'specific_mobiles' || target === 'state') {
            let mobiles = $('#specific_mobiles_input').val().trim();
            if (mobiles === '') { toast('error', 'Please enter mobile numbers before scheduling.'); return; }
        }

        let today = new Date();
        let currentYear = today.getFullYear();
        let currentMonth = String(today.getMonth() + 1).padStart(2, '0');
        let currentDay = String(today.getDate()).padStart(2, '0');
        let todayString = `${currentYear}-${currentMonth}-${currentDay}`;

        $('#schedule_push_date').val(todayString);
        validateTimeOptions('schedule_push_date', 'schedule_push_time');

        $('#schedulePushModal').modal('show');
    }

    function submitScheduledPush() {
        var date = $('#schedule_push_date').val();
        var time = $('#schedule_push_time').val();
        if(!date || !time) { 
            toast('error', 'Please select a valid date and time.'); 
            return; 
        }
        var datetime = date + ' ' + time;

        var target = $('input[name="target_user"]:checked').val();
        var title = $('#push_title').val().trim();
        var body = $('#push_body').val().trim();
        var route = $('#push_route').val().trim();
        var imageFile = $('#push_image')[0].files[0];

        var formData = new FormData();
        formData.append('method', 'schedule_push'); 
        formData.append('admin_id', admin_session_id);
        formData.append('user_type', target);
        formData.append('title', title);
        formData.append('body', body);
        formData.append('route', route);
        formData.append('scheduled_at', datetime);

        if (target === 'specific_mobiles' || target === 'state') {
            formData.append('mobile_numbers', $('#specific_mobiles_input').val().trim());
            formData.append('specific_mobiles_type', $('#specific_mobiles_type').val());
            if (target === 'state') {
                formData.append('sent_state', $('#state_dropdown').val());
            }
        }

        if (imageFile) {
            formData.append('image', imageFile);
        }

        $('#schedulePushModal').modal('hide');

        Swal.fire({
            title: 'Scheduling...',
            text: 'Saving your scheduled notification.',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: datatable_url, 
            type: "POST",
            dataType: "json",
            processData: false, 
            contentType: false, 
            data: formData,
            success: function(response) {
                if (response.status) {
                    Swal.fire({icon: 'success', title: 'Scheduled!', text: response.message, timer: 3000, showConfirmButton: false});
                    $('#push_title').val(''); $('#push_body').val(''); $('#push_route').val(''); $('#push_image').val(''); $('#specific_mobiles_input').val(''); $('#state_dropdown').val('');
                    if(currentActiveTab === 'scheduled') searchPush();
                } else {
                    Swal.fire('Error', response.message || 'Error scheduling notification.', 'error');
                }
            },
            error: function(xhr) { 
                Swal.fire('Error', 'Server connection error.', 'error'); 
            }
        });
    }

    function openRmModal() {
        $('#rmSelectionModal').modal('show');
        if (rmListLoaded) return; 

        $('#rm_dropdown').html('<option value="">Loading RMs...</option>');
        $('#rm_drivers_container').hide();
        
        $.ajax({
            url: datatable_url,
            type: 'POST',
            data: { method: 'get_rms' }, 
            dataType: 'json',
            success: function(res) {
                let options = '<option value="">-- Select an RM --</option>';
                res.forEach(rm => {
                    options += `<option value="${rm.id}">${rm.name} (ID: ${rm.id})</option>`;
                });
                $('#rm_dropdown').html(options);
                rmListLoaded = true; 
            },
            error: function() {}
        });
    }

    function fetchRmDrivers() {
        let rmId = $('#rm_dropdown').val();
        if (!rmId) {
            $('#rm_drivers_container').hide();
            return;
        }
        
        $('#rm_drivers_container').show();
        $('#rm-driver-list').html('<tr class="empty-list-row"><td colspan="3" class="text-center py-4"><i class="fa fa-spinner fa-spin"></i> Loading Drivers...</td></tr>');
        
        $.ajax({
            url: datatable_url,
            type: 'POST',
            data: { method: 'get_rm_drivers', rm_id: rmId },
            dataType: 'json',
            success: function(res) {
                let html = '';
                if(!res || res.length === 0) {
                    html = '<tr class="empty-list-row"><td colspan="3" class="text-center text-danger font-italic py-4">No drivers found for this RM.</td></tr>';
                } else {
                    res.forEach((driver, idx) => {
                        let isChecked = globalSelectedRmDrivers.includes(parseInt(driver.id)) ? 'checked' : '';
                        html += `
                        <tr>
                            <td class="text-center align-middle font-weight-bold text-muted">${idx + 1}</td>
                            <td class="text-center align-middle">
                                <input class="rm-driver-checkbox" type="checkbox" value="${driver.id}" style="width:18px; height:18px; cursor:pointer;" ${isChecked}>
                            </td>
                            <td class="align-middle font-weight-bold text-dark">${driver.name} (ID: ${driver.id})</td>
                        </tr>`;
                    });
                }
                $('#rm-driver-list').html(html);
                $('#selectAllRmDrivers').prop('checked', false);
            }
        });
    }

    $('#filterRmDrivers').on('keyup', function() {
        let filter = $(this).val().toLowerCase();
        let hasData = $('#rm-driver-list tr:not(.empty-list-row, .no-data-row)').length > 0;
        if (!hasData) return;

        let visibleCount = 0;
        $('#rm-driver-list .no-data-row').remove();

        $('#rm-driver-list tr:not(.empty-list-row, .no-data-row)').each(function() {
            let text = $(this).text().toLowerCase();
            let show = text.indexOf(filter) > -1;
            $(this).toggle(show);
            if (show) visibleCount++;
        });

        if (visibleCount === 0) {
            $('#rm-driver-list').append(`<tr class="no-data-row"><td colspan="3" class="text-center text-danger font-weight-bold py-4">No drivers found matching "${$(this).val()}"</td></tr>`);
        }
    });

    $('#selectAllRmDrivers').on('change', function() {
        $('#rm-driver-list .rm-driver-checkbox:visible').prop('checked', $(this).prop('checked'));
    });

    function saveRmSelection() {
        globalSelectedRmDrivers = [];
        $('.rm-driver-checkbox:checked').each(function() {
            globalSelectedRmDrivers.push(parseInt($(this).val()));
        });
        if (globalSelectedRmDrivers.length === 0) { toast('warning', 'Please select at least one driver.'); return; }
        $('#rmSelectionModal').modal('hide');
        toast('success', globalSelectedRmDrivers.length + ' Driver(s) selected.');
    }

    function sendPushNotification() {
        var target = $('input[name="target_user"]:checked').val();
        var title = $('#push_title').val().trim();
        var body = $('#push_body').val().trim();
        var route = $('#push_route').val().trim(); 
        var imageFile = $('#push_image')[0].files[0];

        if (!target) { toast('error', 'Please select a target user.'); return; }
        if (title === '') { toast('error', 'Please enter a title.'); return; }
        if (body === '') { toast('error', 'Please enter a message body.'); return; }

        var formData = new FormData();
        formData.append('admin_id', admin_session_id);
        formData.append('title', title);
        formData.append('body', body);
        formData.append('route', route); 

        if (imageFile) {
            formData.append('image', imageFile);
        }

        if (target === 'specific_mobiles' || target === 'state') {
            let mobiles = $('#specific_mobiles_input').val().trim();
            if (mobiles === '') { toast('error', 'Please enter at least one mobile number.'); return; }
            
            formData.append('mobile_numbers', mobiles);
            formData.append('user_type', $('#specific_mobiles_type').val());
            if (target === 'state') {
                formData.append('sent_state', $('#state_dropdown').val());
            }

            triggerAjaxSubmit("/admin-send-mobile-push", formData);
        } 
        else if (target === 'rm') {
            if (globalSelectedRmDrivers.length === 0) {
                toast('error', 'Please click "Select Drivers" and choose at least one user.'); return;
            }
            formData.append('rm_id', $('#rm_dropdown').val());
            globalSelectedRmDrivers.forEach((id) => formData.append('user_ids[]', id));
            
            triggerAjaxSubmit("/admin-send-specific-push-rm-drivers", formData);
        }
        else {
            formData.append('target_user', target);
            triggerAjaxSubmit("/admin-send-push", formData);
        }
    }

    function triggerAjaxSubmit(endpoint, formData) {
        Swal.fire({
            title: 'Processing...',
            text: 'Uploading data and sending notifications. Please wait.',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: api_domain + endpoint, 
            type: "POST",
            dataType: "json",
            processData: false, 
            contentType: false, 
            headers: { "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()" },
            data: formData,
            success: function(response) {
                if (response.status) {
                    Swal.fire({icon: 'success', title: 'Success!', text: response.message, timer: 2000, showConfirmButton: false});
                    $('#push_title').val(''); $('#push_body').val(''); $('#push_route').val(''); $('#push_image').val(''); $('#specific_mobiles_input').val('');
                    globalSelectedRmDrivers = []; rmListLoaded = false; searchPush(); 
                } else {
                    Swal.fire('Error', response.message || 'Error sending push notification.', 'error');
                }
            },
            error: function(xhr) { Swal.fire('Error', 'Server error occurred. Check console.', 'error'); }
        });
    }

    function copyMobileNumbers() {
        let activesTab = $('.tab-pane.active').attr('id');
        let mobiles = [];
        
        $('#' + activesTab + ' .mobile-val').each(function() {
            let txt = $(this).text().trim();
            if(txt && txt !== '-' && txt !== 'Loading...' && txt !== 'Not Found') {
                mobiles.push(txt);
            }
        });
        
        if(mobiles.length === 0) { 
            toast('warning', 'No valid mobile numbers to copy!'); 
            return; 
        }

        var textToCopy = mobiles.join(', ');

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(textToCopy).then(() => {
                toast('success', 'Copied ' + mobiles.length + ' mobile numbers!');
            }).catch(err => {
                executeFallbackCopy(textToCopy, mobiles.length);
            });
        } else {
            executeFallbackCopy(textToCopy, mobiles.length);
        }
    }

    function executeFallbackCopy(text, count) {
        var textArea = document.createElement("textarea");
        textArea.value = text;
        
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";
        textArea.style.opacity = "0"; 
        
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            var successful = document.execCommand('copy');
            if(successful) {
                toast('success', 'Copied ' + count + ' mobile numbers!');
            } else {
                toast('error', 'Browser blocked copying.');
            }
        } catch (err) {
            toast('error', 'Failed to copy text.');
        }
        
        document.body.removeChild(textArea);
    }

    function searchPush() {
        clearTimeout(autoRefreshTimeout); 
        
        var $dateInput = $('#reportrange213');
        var formdate = $dateInput.length && $dateInput.data('daterangepicker') ? moment($dateInput.data('daterangepicker').startDate._d).format("MMM Do YY") : moment().format("MMM Do YY");
        var todate = $dateInput.length && $dateInput.data('daterangepicker') ? moment($dateInput.data('daterangepicker').endDate._d).format("MMM Do YY") : moment().format("MMM Do YY");
        var status = $('#p_status').val() || '';

        if (currentActiveTab === 'regular') {
            loadRegularDataTable(formdate, todate, status);
        } else {
            loadScheduledDataTable(formdate, todate, status);
        }
    }

    function loadRegularDataTable(formdate, todate, status) {
        if (!document.getElementById('push_report_1')) return;
        if ($.fn.DataTable.isDataTable('#push_report_1')) {
            $('#push_report_1').DataTable().destroy();
        }
        
        $("#push_report_1").DataTable({
            pageLength: 10,
            order: [[0, 'desc']], 
            ajax: {
                url: datatable_url,
                method: "POST",
                dataSrc: function (json) {
                    globalPushData = {};
                    let hasProcessing = false;
                    
                    json.forEach(function(row) {
                        globalPushData[row.id] = row;
                        if(row.status == 2 || row.status === '2') hasProcessing = true;
                    });

                    clearTimeout(autoRefreshTimeout);
                    if (hasProcessing) {
                        autoRefreshTimeout = setTimeout(function() {
                            if ($.fn.DataTable.isDataTable('#push_report_1') && currentActiveTab === 'regular') {
                                $('#push_report_1').DataTable().ajax.reload(null, false);
                            }
                        }, 3000);
                    }
                    return json;
                },
                data: { method: 'push_report', agdate: formdate, todate: todate, status: status }
            },
            columns: [
                { 
                    data: "created_at",
                    render: function(data) {
                        return data ? `<span class="font-weight-bold text-dark">${moment(data).format('MMM DD, YYYY')}</span><br><small class="">${moment(data).format('hh:mm A')}</small>` : '-';
                    }
                },
                { 
                    data: "sent_by", 
                    render: function(data, type, row) {
                        var displayName = row.sender_name ? row.sender_name : (data == 1 ? 'Super Admin' : 'Admin ID: ' + data);
                        return `<span class="" style="font-size:13px;">${displayName}</span>`;
                    }
                },
                {
                    data: "req_json",
                    defaultContent: "-", 
                    render: function(data) {
                        if (!data) return '-';
                        try {
                            var parsedData = typeof data === 'object' ? data : JSON.parse(data);
                            var targetValue = parsedData.target ? parsedData.target : '-';
                            return `<span style="font-size:15px; text-transform: capitalize;">${targetValue}</span>`;
                        } catch (error) { return '-'; }
                    }
                },
                {
                    data: "image_url",
                    render: function(data) {
                        if(data && data !== '') {
                            return `<img src="/${data}" style="height:40px; border-radius:4px; box-shadow:0 0 2px #ccc;">`;
                        }
                        return `<span class="text-muted"><i class="fa fa-image"></i> N/A</span>`;
                    }
                },
                { 
                    data: "title",
                    render: function(data) { return `<div style="font-size:15px; max-width:200px; white-space:normal; word-wrap:break-word;">${data}</div>`; }
                },
                { 
                    data: "body",
                    render: function(data) { 
                        return `<div style="font-size:15px; max-width:350px; white-space:normal; word-wrap:break-word;">${data.replace(/<[^>]*>/g, '')}</div>`; 
                    }
                },
                {
                    data: "route",
                    render: function(data) {
                        return (data && data !== "") ? `<span style="font-size:14px;  font-weight:600;">${data}</span>` : `<span class="text-muted" style="font-size:14px;">N/A</span>`;
                    }
                },
                { 
                    data: "status",
                    className: "text-center",
                    render: function(data) {
                        if (data == 2 || data === '2') return '<span class="text-warning font-weight-bold" style="font-size:15px;"><i class="fa fa-spinner fa-spin mr-1"></i> In Progress</span>';
                        else if (data == 1 || data === '1' || data === 'Delivered') return '<span class="text-success font-weight-bold" style="font-size:15px;">Delivered</span>';
                        else return '<span class="text-danger font-weight-bold" style="font-size:15px;">Not Delivered</span>';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (row.status == 2 || row.status === '2') return `<button type="button" class="btn btn-sm btn-secondary font-weight-bold shadow-sm px-3 py-2" disabled style="font-size:14px;"><i class="fa fa-hourglass-half mr-1"></i> Wait...</button>`;
                        return `<button type="button" class="btn btn-sm btn-info font-weight-bold shadow-sm px-3 py-2" onclick="viewPushDetails(${row.id})" style="font-size:14px;"><i class="fa fa-list mr-1"></i> Details</button>`;
                    }
                }
            ]
        });
    }

    function loadScheduledDataTable(formdate, todate, status) {
    if (!document.getElementById('push_report_scheduled')) return;
    if ($.fn.DataTable.isDataTable('#push_report_scheduled')) {
        $('#push_report_scheduled').DataTable().destroy();
    }
    
    $("#push_report_scheduled").DataTable({
        pageLength: 10,
        order: [[0, 'desc']], 
        ajax: {
            url: datatable_url,
            method: "POST",
            dataSrc: function (json) {
                json.forEach(function(row) {
                    globalPushData[row.id] = row;
                });
                return json;
            },
            data: { method: 'scheduled_push_report', agdate: formdate, todate: todate, status: status }
        },
        columns: [
            { 
                data: "scheduled_at",
                render: function(data) { return `<span class="font-weight-bold text-primary">${moment(data).format('MMM DD, YYYY')}</span><br><small class="font-weight-bold text-dark">${moment(data).format('hh:mm A')}</small>`; }
            },
            { 
                data: "created_at",
                render: function(data) { return `<span class="text-muted">${moment(data).format('MMM DD, YYYY')}</span><br><small>${moment(data).format('hh:mm A')}</small>`; }
            },
            { 
                data: "scheduled_by_name",
                render: function(data) { return `<span class="font-weight-bold" style="font-size:14px;">${data}</span>`; }
            },
            { data: "user_type", render: function(data) { return `<span class="badge badge-info text-uppercase px-2 py-1" style="color:black;">${data}</span>`; } },
            {
                data: "image_url",
                render: function(data) {
                    if(data && data !== '') return `<img src="/${data}" style="height:40px; border-radius:4px; box-shadow:0 0 2px #ccc;">`;
                    return `<span class="text-muted"><i class="fa fa-image"></i> N/A</span>`;
                }
            },
            { data: "title", render: function(data) { return `<div style="font-size:15px; max-width:200px; white-space:normal;">${data}</div>`; } },
            { data: "body", render: function(data) { return `<div style="font-size:15px; max-width:250px; white-space:normal;">${data.replace(/<[^>]*>/g, '')}</div>`; } },
            { 
  data: "sent_state", 
  render: function(data) { 
    return data ? data : "All"; 
  } 
},
            { 
                data: "status",
                className: "text-center",
                render: function(data) {
                    if (data == 0 || data === '0') return '<span class="text-warning font-weight-bold"><i class="fa fa-clock-o"></i> Pending</span>';
                    else if (data == 1 || data === '1') return '<span class="text-info font-weight-bold"><i class="fa fa-spinner fa-spin"></i> Processed</span>';
                    else if (data == 2 || data === '2') return '<span class="text-success font-weight-bold"><i class="fa fa-check"></i> Success</span>';
                    else if (data == 3 || data === '3') return '<span class="text-danger font-weight-bold"><i class="fa fa-times"></i> Failed</span>';
                    return '<span class="text-muted">Unknown</span>';
                }
            },
            {
                data: "success_count",
                className: "text-center font-weight-bold text-success",
                render: function(data, type, row) {
                    return (row.status == 2 || row.status == 3) ? `<span style="font-size:16px;">${data}</span>` : '-';
                }
            },
            {
                data: "failure_count",
                className: "text-center font-weight-bold text-danger",
                render: function(data, type, row) {
                    return (row.status == 2 || row.status == 3) ? `<span style="font-size:16px;">${data}</span>` : '-';
                }
            },
            {
    data: null,
    orderable: false,
    className: 'text-center',
    render: function(data, type, row) {
        if (row.status == 0 || row.status === '0') {
            return `
                <button type="button" class="btn btn-sm btn-primary font-weight-bold shadow-sm mr-1 mb-1" onclick="openRescheduleModal(${row.id}, '${row.scheduled_at}')" style="width: 100px;">
                    <i class="fa fa-calendar mr-1"></i> Reschedule
                </button>
                <button type="button" class="btn btn-sm btn-danger font-weight-bold shadow-sm mb-1" onclick="deleteScheduledPush(${row.id})" style="width: 100px;">
                    <i class="fa fa-trash mr-1"></i> Delete
                </button>
            `;
        }
        
        if (row.status != 0 && row.status !== '0') {
            let success = parseInt(row.success_count) || 0;
            let failure = parseInt(row.failure_count) || 0;
            let total = success + failure;
            
            let percentage = total > 0 ? ((success / total) * 100).toFixed(1) : 0;
            
            return `<span class="badge badge-pill badge-primary" style="font-size:14px; padding: 6px 12px; color:black;">${percentage}%</span>`;
        }

        return `<span class="text-muted small">-</span>`;
    }
}
        ]
    });
}

    function openRescheduleModal(id, currentDateTime) {
        $('#reschedule_push_id').val(id);
        
        let datePart = currentDateTime.substring(0, 10);
        let timePart = currentDateTime.substring(11, 19);

        $('#reschedule_push_date').val(datePart);
        validateTimeOptions('reschedule_push_date', 'reschedule_push_time');
        
        $('#reschedule_push_time').val(timePart);
        
        $('#reschedulePushModal').modal('show');
    }

    function submitReschedulePush() {
        var id = $('#reschedule_push_id').val();
        var date = $('#reschedule_push_date').val();
        var time = $('#reschedule_push_time').val();

        if(!date || !time) { 
            toast('error', 'Please select a valid date and time.'); return; 
        }
        var datetime = date + ' ' + time;

        Swal.fire({ title: 'Rescheduling...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});

        $.ajax({
            url: datatable_url,
            type: 'POST',
            data: { method: 'reschedule_push', id: id, scheduled_at: datetime },
            dataType: 'json',
            success: function(response) {
                if(response.status) {
                    $('#reschedulePushModal').modal('hide');
                    Swal.fire({icon: 'success', title: 'Rescheduled!', text: response.message, timer: 2000, showConfirmButton: false});
                    searchPush();
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() { Swal.fire('Error', 'Server connection error.', 'error'); }
        });
    }

    function deleteScheduledPush(id) {
        Swal.fire({
            title: 'Delete Scheduled Push?',
            text: "It won't be sent to users once deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});
                $.ajax({
                    url: datatable_url,
                    type: 'POST',
                    data: { method: 'delete_scheduled_push', id: id },
                    dataType: 'json',
                    success: function(response) {
                        if(response.status) {
                            Swal.fire({icon: 'success', title: 'Deleted!', text: response.message, timer: 2000, showConfirmButton: false});
                            searchPush();
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() { Swal.fire('Error', 'Server error.', 'error'); }
                });
            }
        });
    }

    function viewPushDetails(id) {
        var rowData = globalPushData[id];
        if (!rowData) return;

        currentResendContext = { id: id, title: rowData.title, body: rowData.body, route: rowData.route };
        
        if (rowData.image_url && rowData.image_url !== '') {
            $('#modal-image').attr('src', '/' + rowData.image_url);
            $('#modal-image-container').show();
        } else {
            $('#modal-image-container').hide();
        }

        var plainBody = rowData.body.replace(/<[^>]*>/g, '');
        $('#modal-title').text(rowData.title);
        $('#modal-body').text(plainBody);

        var delivered = [];
        var notDelivered = [];
        var targetType = '';
        var allIds = []; 

        try {
            var resJson = typeof rowData.res_json === 'string' ? JSON.parse(rowData.res_json) : rowData.res_json;
            delivered = resJson.delivered || [];
            notDelivered = resJson.not_delivered || [];
            
            var reqJson = typeof rowData.req_json === 'string' ? JSON.parse(rowData.req_json) : rowData.req_json;
            targetType = reqJson.target || '';
            
            delivered.forEach(i => { allIds.push(i.id || i.user_id || i) });
            notDelivered.forEach(i => { allIds.push(i.id || i.user_id || i) });
        } catch (e) {}

        if (currentActiveTab === 'scheduled') {
            try {
                var s_resJson = typeof rowData.res_json === 'string' ? JSON.parse(rowData.res_json) : rowData.res_json;
                delivered = s_resJson.delivered_sample || s_resJson.delivered || [];
                notDelivered = s_resJson.not_delivered_sample || s_resJson.not_delivered || [];
            } catch(e) {}
        }

        $('#delivered-count').text(delivered.length);
        $('#not-delivered-count').text(notDelivered.length);

        renderUserList('delivered', delivered, true);
        renderUserList('not-delivered', notDelivered, false);

        if(allIds.length > 0) {
            $.ajax({
                url: datatable_url,
                type: 'POST',
                data: { method: 'get_mobile_numbers', ids: allIds, target: targetType },
                dataType: 'json',
                success: function(res) {
                    if(res.status && res.data) {
                        $('.mobile-val').each(function() {
                            let userId = $(this).attr('data-id');
                            if(res.data[userId]) {
                                $(this).text(res.data[userId]).removeClass('text-muted').addClass('text-primary font-weight-bold');
                            } else {
                                $(this).text('Not Found');
                            }
                        });
                    }
                }
            });
        }

        $('#filterDelivered, #filterNotDelivered').val('');
        $('#selectAllDelivered, #selectAllNotDelivered').prop('checked', false);
        $('#customTabs .nav-link').removeClass('active');
        $('#customTabs .nav-link').first().addClass('active');
        
        $('#viewPushModal .tab-pane').hide().removeClass('active'); 
        
        $('#delivered-tab').show().addClass('active');
        $('#viewPushModal').modal('show');
    }

    function renderUserList(type, listArray, isDelivered) {
        var tbody = $('#' + type + '-list');
        tbody.empty();
        
        if (!listArray || listArray.length === 0) {
            tbody.append('<tr class="empty-list-row"><td colspan="5" class="text-center text-muted font-italic py-4">No users in this category.</td></tr>');
            return;
        }
        
        listArray.forEach(function(item, index) {
            var sno = index + 1;
            var uid = '';
            var uName = 'Unknown';
            var errorTxt = '';
            
            if (typeof item === 'object' && item !== null) {
                uid = item.user_id || item.id; 
                uName = item.name || 'Unknown';
                if (item.error) errorTxt = item.error;
            } else { uid = item; }

            var errorHtml = errorTxt ? `<br><small class="text-danger"><i class="fa fa-exclamation-triangle"></i> ${errorTxt}</small>` : '';
            var statusHtml = isDelivered ? '<span class="text-success font-weight-bold">Delivered</span>' : '<span class="text-danger font-weight-bold">Not Delivered</span>';
            var checkboxHtml = `<input class="user-checkbox" type="checkbox" value="${uid}" style="width:18px; height:18px; cursor:pointer;">`;
            var displayFormat = `${uName} (ID: ${uid})`;

            var rowHtml = `
                <tr>
                    <td class="text-center align-middle font-weight-bold text-muted">${sno}</td>
                    <td class="text-center align-middle">${checkboxHtml}</td>
                    <td class="align-middle font-weight-bold text-dark">
                        ${displayFormat} ${errorHtml}
                    </td>
                    <td class="align-middle">
                        <span class="mobile-val text-muted" data-id="${uid}">Loading...</span>
                    </td>
                    <td class="text-right align-middle">${statusHtml}</td>
                </tr>
            `;
            tbody.append(rowHtml);
        });
    }

    $('#filterDelivered, #filterNotDelivered').on('keyup', function() {
        var filter = $(this).val().toLowerCase();
        let targetList = $(this).attr('id') === 'filterDelivered' ? '#delivered-list' : '#not-delivered-list';
        let hasData = $(targetList + ' tr:not(.empty-list-row, .no-data-row)').length > 0;
        if (!hasData) return;

        let visibleCount = 0;
        $(targetList + ' .no-data-row').remove();

        $(targetList + ' tr:not(.empty-list-row, .no-data-row)').each(function() {
            var text = $(this).text().toLowerCase();
            let show = text.indexOf(filter) > -1;
            $(this).toggle(show);
            if (show) visibleCount++;
        });

        if (visibleCount === 0) {
            $(targetList).append(`<tr class="no-data-row"><td colspan="5" class="text-center font-weight-bold py-4">No records found matching "${$(this).val()}"</td></tr>`);
        }
    });

    $('#selectAllDelivered, #selectAllNotDelivered').on('change', function() {
        let targetList = $(this).attr('id') === 'selectAllDelivered' ? '#delivered-list' : '#not-delivered-list';
        $(targetList + ' .user-checkbox:visible').prop('checked', $(this).prop('checked'));
    });

    $('#resendFromModalBtn').on('click', function() {
        var selectedUserIds = [];
        $('.modal-user-table .user-checkbox:checked').each(function() {
            var val = $(this).val(); 
            if (!selectedUserIds.includes(val)) selectedUserIds.push(val);
        });

        if (selectedUserIds.length === 0) { toast('warning', 'Select at least one user to resend.'); return; }

        Swal.fire({
            title: 'Resend Push Notification?',
            text: `You are about to resend this notification to ${selectedUserIds.length} user(s).`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Send Now'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: '', text: 'Please wait...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});
                $.ajax({
                    url: api_domain + "/admin-resend-push",
                    type: "POST",
                    dataType: "json",
                    contentType: "application/json",
                    headers: { "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()" },
                    data: JSON.stringify({
                        admin_id: admin_session_id,
                        notification_id: currentResendContext.id,
                        user_ids: selectedUserIds,
                        title: currentResendContext.title,
                        body: currentResendContext.body,
                        route: currentResendContext.route 
                    }),
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({icon: 'success', title: 'Success!', text: response.message, timer: 3000, showConfirmButton: false});
                            $('#viewPushModal').modal('hide');
                            searchPush();
                        } else { Swal.fire('Error', response.message || 'Failed to resend.', 'error'); }
                    },
                    error: function(xhr) { Swal.fire('Error', 'Server connection error. Check console.', 'error'); }
                });
            }
        });
    });

    function toast(icon, message) {
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3500, timerProgressBar: true });
        Toast.fire({ icon: icon, title: message });
    }

    function createDatePicker(id) {
        var start = moment();
        var end = moment();
        if($('#' + id).length) { 
            function cb(start, end) { $('#' + id + ' span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY')); }
            $('#' + id).daterangepicker({
                startDate: start, endDate: end, maxDate: moment().format("MM/DD/YYYY"),
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()]
                }
            }, cb);
            cb(start, end);
        }
    }
</script>