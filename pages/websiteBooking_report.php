<?php
$pageTitle = "Website Booking";

?> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>  
    
    .card-header.d-lg-flex.d-block.justify-content-between {
        border-bottom: none;
    }
    .cursor-pointer {
        cursor: pointer;
        font-size: 1.2rem; /* Adjust size as needed */
    }

    input,
    select {
        border: 1px solid #CCC;
        /* width: 250px; */
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
        /* Change to the desired color */
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
        /* opacity: 1; */
    }

    input,
    select {
        border: 1px solid #CCC;
        /* width: 250px; */
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
        /* color: #495057; */
        background-color: #ffffff !important;
    }

    .dropdown.bootstrap-select {
        display: block !important;
        width: 100% !important;
    }
    
div:where(.swal2-container) {
    z-index: 9999 !important;
}

div:where(.swal2-container) h2:where(.swal2-title) {
    color: rgb(85 85 85) !important;
}
    
</style>
<script>
    window.onload = function () {
        var page_origin = window.location.origin;
        let anchor = document.getElementById("anchor");
        anchor.href = page_origin;
    }
</script>
<!--app-content open-->
<div class="main-content app-content mt-0">
    <div class="side-app">
        <input type="hidden" id="tabID" value="agents">
        <!-- CONTAINER -->
        <div class="main-container container-fluid mt-5 p-0">
            <!-- PAGE-HEADER -->
            <div class="page-header pt-5">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left"
                            onclick="history.go(-1)" aria-hidden="true"></i></a><?= $pageTitle; ?></h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= $pageTitle; ?></li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->
            <!-- ROW-1 -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                    <div class="card ">
                        <div class="card-body">
                            <div class="d-flex1">
                                <div class="mt-2">
                                    <div class="row">

                                        <div class="col-sm-12 col-md-3 col-lg-3">
                                            <span>Search (Name / Mobile / Email)</span>

                                            <input class="form-control" type="text" id="searchTxt" name="searchTxt"
                                                placeholder="Name / Mobile / Email" value=""
                                                oninput="this.value = this.value.replace(/[^A-Za-z. ]/g, ''); if (this.value.length > 3) { unsubscribeList(); }"
                                                maxlength="70" />

                                        </div>


                                        <div class="col-sm-12 col-md-3 col-lg-3">
                                            <span>Select Date (Created At)</span>

                                            <input class="form-control" type="text" id="datefilterLogin"
                                                name="datefilterLogin" placeholder="Select Date" value="" readonly onchange="unsubscribeList();" />
                                        </div>
                                        
                                        <div class="col-sm-12 col-md-3 col-lg-3">
                                            <span>Job Status</span>
                                            <select class="form-control" id="job_status" name="job_status" onchange="unsubscribeList();">
                                                <option value="">-- Select Status --</option>
                                                <option value="created">Created</option>
                                                <option value="cancelled">Cancelled</option>
                                            </select>
                                        </div>


                                        <div class="col-sm-12 col-md-3 col-lg-3">
                                            <button type="button" class="btn btn-primary" id="searchBTN"
                                                onclick="unsubscribeList();">GO</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4" id="searcherr">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-4">
                        <div class="grid-margin">
                            <div class="panel panel-primary">
                                <div class="tab-menu-heading border-0 p-0">
                                    <div class="tabs-menu1">
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body border-0 pt-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab1">
                                            <div class="card-header d-lg-flex d-block justify-content-between">
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered text-nowrap border-bottom"
                                                    id="Participation_List" style="width:100%;">
                                                    <thead>
                                                        <tr>
                                                            <th class="column_sort sorting sorting_asc">Acion</th>
                                                            <th class="column_sort sorting sorting_asc">create at</th>
                                                            <th class="column_sort sorting sorting_asc">Job No</th>
                                                            <th class="column_sort sorting sorting_asc">Job Type</th>
                                                            <th class="column_sort sorting sorting_asc">Job Status</th>
                                                            <th class="column_sort sorting sorting_asc">Name</th>
                                                            <th class="column_sort sorting sorting_asc">Mobile</th>
                                                            <th class="column_sort sorting sorting_asc">Fare (₹)</th>
                                                            <th class="column_sort sorting sorting_asc">From</th>
                                                            <th class="column_sort sorting sorting_asc">To</th>
                                                            <th class="column_sort sorting sorting_asc">PickUp Date</th>
                                                            <th class="column_sort sorting sorting_asc">Dropoff Date</th>
                                                            <th class="column_sort sorting sorting_asc">Last Comment</th>
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
            </div>
        </div>
        <!-- ROW-4 END -->
    </div>
    <!-- CONTAINER END -->
</div>

<div class="modal fade" id="comment_form">
   <div class="modal-dialog modal-lg">
      <div class="modal-content modal-content-demo">
         <div id="otperror"></div>
         <div class="modal-header">
            <h6 class="modal-title">Booking Details</h6>
            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="container">
               <form action="#" class="comment_form">
                  <div class="form-group">
                     <div class="model-text">
                        <!--<h3 class="text-center"><b id="title"></b>Lead Details</h3>-->
                        <input type="hidden" id="lead_id" class="form-control" value=""/>
                        <!--<p class="text-center">Enter the code we just send on your <b id="title"></b> <b id="mno"></b></p>-->
                        
                        <!--<br>-->
                     </div>
                     <div class="row ">
                        <p class="fw-bold">Comment Logs</p>
                        <table class="table table-hover" >
                            <thead>
                                <tr>
                                    <th scope="col">SNO</th>
                                    <th scope="col">Contact Person</th>
                                    <th scope="col">Customer Comment</th>
                                    <th scope="col">Next FollowUp</th>
                                    <th scope="col">Date</th>
                                </tr>
                            </thead>
                            <tbody id="l_tbl">
                            </tbody>
                        </table>
                     </div>
                     <div class="row mt-3">
                        <p class="fw-bold">Add Comment</p>
                        <!-- Column 1: Select -->
                        <div class="col-3">
                            <label for="selectOption">Customer Comment</label>
                            <select id="com_comment" class="form-control">
                                <option value="">Choose</option>
                                <option value="Interested">Interested</option>
                                <option value="Not Interested">Not Interested</option>
                                <option value="Call Later">Call Later</option>
                            </select>
                        </div>
                    
                        <!-- Column 2: Date -->
                        <div class="col-3">
                            <label for="dateInput">Next FollowUp</label>
                            <input type="date" id="com_dateInput" class="form-control" />
                        </div>
                    
                        <!-- Column 3: Textarea -->
                        <div class="col-6">
                            <label for="message">Message</label>
                            <textarea id="com_message" class="form-control" rows="3" placeholder="Enter message"></textarea>
                        </div>
                    
                    </div>
                     <div class="row justify-content-center mt-5">
                        <!-- Column: Centered Button -->
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" id="comment_submit">Submit</button>
                        </div>
                    </div>


                  </div>
               </form>
               <br>
            </div>
         </div>
         <div class="modal-footer" id="otpbtn">
            <!-- <button class="btn ripple btn-success" onclick="saveformNew()" type="button">Submit</button>
               <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button> -->
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="whatsapp_form">
   <div class="modal-dialog modal-md">
      <div class="modal-content modal-content-demo">
         <div id="otperror"></div>
         <div class="modal-header">
            <h6 class="modal-title">Send Whatsapp Message</h6>
            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="container">
               <form action="#" class="comment_form">
                  <div class="form-group">
                     <div class="model-text d-none">
                        <input type="hidden" id="what_id" class="form-control" value=""/>
                        <input type="hidden" id="leads_id" class="form-control" value=""/>
                        <input type="hidden" id="lead_phone" class="form-control" value=""/>
                        <br>
                     </div>
                    
                    <div class="form-group">
                        <label class="form-label">Template</label>
                        <div class="input-group d-flex">
                            <select id="waTemplateID" class="selectpicker form-control" data-live-search="true" data-width="grow">
                                <option value="" selected="">Select Template</option>
                                <?php
                                $utm_campaign = mysqli_query($con, "SELECT id, name, body FROM `wamail_templates` WHERE is_active = 1 ORDER BY id;");
                                if (mysqli_num_rows($utm_campaign) > 0) {
                                    while ($row = mysqli_fetch_assoc($utm_campaign)) {
                                        $bodyText = htmlspecialchars($row['body']);
                                ?>
                                    <option value="<?= $row['id']; ?>" data-body="<?= $bodyText; ?>">
                                        <?= htmlspecialchars($row['name']); ?>
                                    </option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                            
                            <div class="input-group-append">
                                <button class="btn btn-success" type="button" id="sendWhatsAppBtn" style="border-radius: 0 4px 4px 0;"
                                    onclick="window.open('/wa-template/', '_blank');">
                                    <i class="fab fa-whatsapp"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <style>
                        /* This fixes a common bug where selectpicker collapses to 0px inside input-groups */
                        .bootstrap-select.form-control {
                            border: none;
                        }
                        .input-group > .bootstrap-select.form-control {
                            flex: 1 1 auto;
                            width: 1% !important;
                        }
                    </style>
                    
                    <div class="whPreview" style="margin-top: 15px;">
                        <label class="form-label">Preview & Edit Content</label>
                        <textarea id="templatePreview" class="form-control" rows="5" placeholder="Select a template to preview..."></textarea>
                    </div>
                    
                     <div class="row justify-content-center mt-5">
                        <!-- Column: Centered Button -->
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" id="whatsapp_submit">Send</button>
                        </div>
                    </div>
                    
                    <div class="whLogContainer" style="margin-top: 25px;">
                        <label class="form-label font-weight-bold"> WhatsApp History</label>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead class="thead-dark">
                                    <tr>
                                        <!--<th style="width: 50px;">#</th>-->
                                        <th style="width: 180px;">Date & Time</th>
                                        <th>Message Sent</th>
                                    </tr>
                                </thead>
                                <tbody id="wh_log_tbl_body">
                                    <tr><td colspan="3" class="text-center">Loading logs...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                  </div>
               </form>
               <br>
            </div>
         </div>
         <div class="modal-footer" id="otpbtn">
            <!-- <button class="btn ripple btn-success" onclick="saveformNew()" type="button">Submit</button>
               <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button> -->
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="jobPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-briefcase me-2"></i> Job Preview
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">X</button>
            </div>

            <div class="modal-body">
                <div class="row g-4">

                    <!-- Customer Info -->
                    <div class="col-md-6">
                        <div class="border p-3 h-100">
                            <h6 class="text-primary mb-3">Customer Details</h6>
                            <p><strong>Name:</strong> <span id="pv_name"></span></p>
                            <p><strong>Email:</strong> <span id="pv_email"></span></p>
                            <p><strong>Mobile:</strong> <span id="pv_mobile"></span></p>
                        </div>
                    </div>

                    <!-- Job Info -->
                    <div class="col-md-6">
                        <div class="border p-3 h-100">
                            <h6 class="text-primary mb-3">Job Details</h6>
                            <p><strong>Job No:</strong> <span id="pv_job_no"></span></p>
                            <input type="hidden" id="pv_job_id" value="">
                            <p><strong>Job Type:</strong> <span id="pv_job_type"></span></p>
                            <p><strong>Car Type:</strong> <span id="pv_car_type"></span></p>
                        </div>
                    </div>

                    <!-- Route -->
                    <div class="col-md-12">
                        <div class="border p-3">
                            <h6 class="text-primary mb-3">Route Details</h6>
                            <p><strong>From:</strong> <span id="pv_from"></span></p>
                            <p><strong>To:</strong> <span id="pv_to"></span></p>
                            <p><strong>Distance:</strong> <span id="pv_dis"></span> kms</p>
                            <p><strong>Duration:</strong> <span id="pv_dur"></span></p>
                            <p class="d-none"><strong>Pickup Address:</strong> <span id="pv_pickup_address"></span></p>
                            <p class="d-none"><strong>Drop Address:</strong> <span id="pv_drop_address"></span></p>
                        </div>
                    </div>

                    <!-- Schedule -->
                    <div class="col-md-6">
                        <div class="border p-3 h-100">
                            <h6 class="text-primary mb-3">Schedule</h6>
                            <p><strong>Pickup:</strong> <span id="pv_pickup_time"></span></p>
                            <!--<p><strong>Drop Day:</strong> <span id="pv_drop_date"></span></p>-->
                        </div>
                    </div>

                    <!-- Fare -->
                    <div class="col-md-6">
                        <div class="border p-3 h-100">
                            <h6 class="text-primary mb-3">Fare Breakdown</h6>
                            <p><strong>Base Fare:</strong> ₹<span id="pv_base_fare"></span></p>
                            <p><strong>Toll:</strong> ₹<span id="pv_toll_fare"></span></p>
                            <h5 class="text-success">
                                Total: ₹<span id="pv_total_fare"></span>
                            </h5>
                        </div>
                    </div>

                </div>
            </div>
            
            <div id="otpSection" class="border p-3 mt-3 d-none">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-shield-alt me-1"></i> OTP Verification
                </h6>
            
                <div class="row align-items-center g-2">
                    <div class="col-md-6">
                        <input type="text"
                               id="otpInput"
                               class="form-control"
                               maxlength="6"
                               placeholder="Enter 6-digit OTP">
                    </div>
            
                    <div class="col-md-6">
                        <button class="btn btn-success w-100" id="verifyOtpBtn">
                            Verify OTP
                        </button>
                    </div>
                </div>
            
            </div>
            <small class="text-muted d-block mt-2" id="otpMsg"></small>


            <div class="modal-footer justify-content-between">
                <div>
                    <button class="btn btn-outline-primary" id="sendOtpBtn">
                        <i class="fas fa-key me-1"></i> Send OTP
                    </button>
                    <button class="btn btn-outline-secondary" id="forceRegBtn">
                        <i class="fas fa-bolt me-1"></i> Force Register
                    </button>
                </div>
                <div>
                    <button class="btn btn-warning" id="commentBtn">
                        <i class="fas fa-comment me-1"></i> Make Comment
                    </button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="jobPreviewEditModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-briefcase me-2"></i> Job Preview
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">X</button>
            </div>

            <div class="modal-body">
                <div class="row g-4">

                    <!-- Customer Info -->
                    <div class="col-md-6">
                        <div class="border p-3 h-100">
                            <h6 class="text-primary mb-3">Customer Details</h6>
                    
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control mb-2" id="ed_name">
                    
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control mb-2" id="ed_email">
                    
                            <label class="form-label">Mobile</label>
                            <input type="text" class="form-control" id="ed_mobile">
                        </div>
                    </div>


                    <!-- Job Info -->
                    <div class="col-md-6">
                        <div class="border p-3 h-100">
                            <h6 class="text-primary mb-3">Job Details</h6>
                    
                            <label class="form-label">Job No</label>
                            <input type="text" class="form-control mb-2" id="ed_job_no" disabled>
                    
                            <input type="hidden" id="ed_job_id">
                    
                            <label class="form-label">Job Type</label>
                            <input type="text" class="form-control" id="ed_job_type" disabled>
                            <!--<select class="form-control mb-2" id="ed_job_type" readonly>-->
                            <!--    <option value="oneway">One Way</option>-->
                            <!--    <option value="roundtrip">Round Trip</option>-->
                            <!--</select>-->
                    
                            <label class="form-label">Car Type</label>
                            <input type="text" class="form-control" id="ed_car_type" disabled>
                        </div>
                    </div>


                    <!-- Route -->
                    <div class="col-md-12">
                        <div class="border p-3">
                            <h6 class="text-primary mb-3">Route Details</h6>
                    
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">From</label>
                                    <input type="text" class="form-control" id="ed_from" disabled>
                                </div>
                    
                                <div class="col-md-6">
                                    <label class="form-label">To</label>
                                    <input type="text" class="form-control" id="ed_to" disabled>
                                </div>
                    
                                <div class="col-md-3">
                                    <label class="form-label">Distance (kms)</label>
                                    <input type="number" class="form-control" id="ed_dis">
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label">Duration</label>
                                    <input type="text" class="form-control" id="ed_dur">
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label">Passengers</label>
                                    <input type="number"
                                           class="form-control"
                                           id="ed_passenger_count"
                                           min="1"
                                           placeholder="No. of passengers">
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label">Luggage</label>
                                    <input type="number"
                                           class="form-control"
                                           id="ed_luggage_count"
                                           min="0"
                                           placeholder="No. of luggage">
                                </div>

                    
                                <div class="col-md-6 d-none">
                                    <label class="form-label">Pickup Address</label>
                                    <select id="ed_pickup_address" class="form-control select2"></select>
                                </div>
                    
                                <div class="col-md-6 d-none">
                                    <label class="form-label">Drop Address</label>
                                    <select id="ed_drop_address" class="form-control select2"></select>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Schedule -->
                    <div class="col-md-6">
                        <div class="border p-3 h-100">
                            <h6 class="text-primary mb-3">Schedule</h6>
                    
                            <label class="form-label">Pickup Date & Time</label>
                            <input type="datetime-local" class="form-control mb-2" id="ed_pickup_time">
                    
                            <!--<label class="form-label">Drop Day</label>-->
                            <!--<input type="number" class="form-control" id="ed_drop_date">-->
                        </div>
                    </div>


                    <!-- Fare -->
                    <div class="col-md-6">
                        <div class="border p-3 h-100">
                            <h6 class="text-primary mb-3">Fare Breakdown</h6>
                    
                            <label class="form-label">Base Fare</label>
                            <input type="number" class="form-control mb-2 fare-input" id="ed_base_fare">
                    
                            <label class="form-label">Toll Fare</label>
                            <input type="number" class="form-control mb-2 fare-input" id="ed_toll_fare">
                    
                            <h5 class="text-success">
                                Total: ₹<span id="ed_total_fare">0</span>
                            </h5>
                        </div>
                    </div>


                </div>
            </div>
            
            <div class="modal-footer justify-content-end">
                
                <div>
                    <button class="btn btn-warning" id="editBtn">
                        <i class="fas fa-comment me-1"></i> Edit Changes
                    </button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>


<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
  const quill = new Quill('#whatsapp_message', {
    theme: 'snow'
  });
</script>
<script>
    const API_DOMAIN_2 = "<?= API_DOMAIN_2 ?>";
</script>

<script>
$(document).ready(function () {
    
    $('.fare-input').on('input', calculateFare);
    
    $('.select2').select2({
        placeholder: 'Select address',
        dropdownParent: $('#jobPreviewEditModal'),
        ajax: {
            url: '/api/places/search',
            dataType: 'json',
            delay: 250,
            headers: {
                'Authorization': 'Bearer <?= $_SESSION["token"] ?? "" ?>'
            },
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return {
                    results: data.map(item => ({
                        id: item.address,
                        text: item.address
                    }))
                };
            }
        }
    });
    
    let isSubmitting = false;
    
    $('#editBtn').on('click', function () {
        
        if (isSubmitting) return;

        isSubmitting = true;
        
        const btn = $(this);
        btn.prop('disabled', true);
        btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Updating...');

        const payload = {
            job_id: $('#ed_job_id').val(),
            job_no: $('#ed_job_no').val(),
        
            name: $('#ed_name').val(),
            email: $('#ed_email').val(),
            mobile: $('#ed_mobile').val(),
        
            job_type: $('#ed_job_type').val(),
            car_type: $('#ed_car_type').val(),
        
            // from_place: $('#ed_from').val(),
            // to_place: $('#ed_to').val(),
            distance: $('#ed_dis').val(),
            duration: $('#ed_dur').val(),
        
            pickup_address: $('#ed_pickup_address').val(),
            drop_address: $('#ed_drop_address').val(),
        
            passenger_count: $('#ed_passenger_count').val(),
            luggage_count: $('#ed_luggage_count').val(),
        
            pickup_date: $('#ed_pickup_time').val(),
            // drop_day: $('#ed_drop_date').val(),
        
            base_fare: $('#ed_base_fare').val(),
            toll_fare: $('#ed_toll_fare').val(),
            total_fare: $('#ed_total_fare').text()
        };
    
        $.ajax({
            url: API_DOMAIN_2 + 'admin-job-edit',
            method: 'POST',
            contentType: 'application/json',
            headers: {
                'Authorization': 'Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()'
            },
            data: JSON.stringify(payload),
            success(res) {
                toast('success', 'Job updated successfully');
                $('#jobPreviewEditModal').modal('hide');
                unsubscribeList();
            },
            error(xhr) {
                toast('error', xhr.responseJSON?.message || 'Update failed');
            },
            complete() {
                
                isSubmitting = false;
                btn.prop('disabled', false);
                btn.html('<i class="fas fa-save me-1"></i> Edit Changes');
            }
        });
    });

    
});
</script>

<script>
    let mainDomain = window.location.hostname.split('.').slice(-2).join('.');
    
    $('#waTemplateID').on('change', function() {
        
        var selectedBody = $(this).find(':selected').data('body');
        
        if (selectedBody) {
            $('#templatePreview').val(selectedBody);
        } else {
            $('#templatePreview').val('');
        }
    });

    const createDatePicker = (id) => {
        try {
            const selector = `#${id}`;
            const today = moment();
            const sevenDaysAgo = moment().subtract(0, 'days');
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
                // maxSpan: {
                //     days: 30
                // },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
                }
            });
            $(selector).on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                // $(`#drawID`).val('');
                // $(`#drawID`).val('').selectpicker('refresh');
            });
            $(selector).on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
        } catch (e) {
            console.log(`Error: ${e.message}`);
        }
    }
    
    const unsubscribeList = () => {
        try {
            let startDate = null;
            let endDate = null;
            var btn = $(`#searchBTN`);
            var dateFilter = $('#datefilterLogin').val() ?? null;
            if (dateFilter != '' && dateFilter != null && dateFilter != undefined) {
                startDate = moment($('#datefilterLogin').data('daterangepicker').startDate).format("YYYY-MM-DD");
                endDate = moment($('#datefilterLogin').data('daterangepicker').endDate).format("YYYY-MM-DD");
            }
            var table = $('#Participation_List').DataTable({
                destroy: true,
                pageLength: 10,
                order: [],
                paging: true,
                searching: true,
                info: true,
                ajax: {
                    url: origin + "/ajax/service/leadService.php",
                    method: "POST",
                    dataSrc: "result",
                    data: {
                        method: 'websiteBookingList',
                        dateFilter: dateFilter,
                        startDate: startDate,
                        endDate: endDate,
                        searchTxt: $(`#searchTxt`).val(),
                        job_status: $(`#job_status`).val(),
                        // statusVal: $(`#statusVal`).val(),
                        // statusPlan: $(`#statusPlan`).val(),
                        // utm_source: $(`#utm_campaign_Source`).val(),
                        // utm_campaign: $(`#utm_campaign`).val(),
                        // registered_status: $(`#registered_status`).val()
                        
                        // currencyID: $(`#currencyID`).val()
                    },
                    beforeSend: function () {
                        btn.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Loading...`).prop('disabled', true);
                    },
                },
                
                dom: 'Bfrtip',
                buttons: [
                    'pageLength',
                    'copy',
                    {
                        extend: 'excelHtml5',
                        title: 'Go Ride Leads'
                    },
                ],
                columns: [
                    
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                    
                            return `
                                <i class="fas fa-comment-alt text-primary fa-2x me-2 cursor-pointer"
                                   title="Add Comment"
                                   onclick="comment_form('${row.id}', this)"></i>
                    
                                <i class="fab fa-whatsapp text-success fa-2x me-2 cursor-pointer"
                                   title="Send WhatsApp"
                                   onclick="openWhatsApp('${row.id}', this, '${row.mobile}')"></i>
                    
                                <i class="fas fa-eye text-secondary fa-2x me-2 cursor-pointer"
                                   title="Job Preview"
                                   onclick='setPreview(${JSON.stringify(row)})'></i>
                                   
                                <i class="fas fa-edit text-dark fa-2x me-2 cursor-pointer"
                                   title="Edit"
                                   onclick='setPreviewEdit(${JSON.stringify(row)})'></i>
                                   
                                <i class="fas fa-times text-danger fa-2x me-2 cursor-pointer"
                                   title="Cancel Job"
                                   onclick="setCancel('${row.job_no}', this)">
                                </i>

                    
                                ${
                                    row.user_id != 0
                                    ? `<i class="fas fa-gavel text-warning fa-2x cursor-pointer"
                                         title="Open Bidding Panel"
                                         onclick="openBidding(${row.id})"></i>`
                                    : ``
                                }
                            `;
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return data?.created_at || '';
                        }
                    },
                    
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return data?.job_no || '';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return data?.job_type || '';
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                    
                            return data?.job_status || '';
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                    
                            return data?.name || '';
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                    
                            return data?.mobile || '';
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                    
                            return data?.fare || '';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return data?.from_place || '';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return data?.to_place || '';
                        }
                    },
                    
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                    
                            return data.pickup_date??'NA';
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return data.dropoff_date??'NA';
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                    
                            return data?.comments_status || '';
                        }
                    }
                    
                ],
                initComplete: function (settings, json) {
                    btn.html(`GO`).prop('disabled', false);
                },
                
            });
        } catch (e) {
            console.log(`Error: ${e.message}`);
        }
    }
    
    function openBidding(jobId) {
        window.open('/job-bidding?job_id=' + jobId, '_blank');
    }


    
    function formatDateTime12Hr(dateStr) {
        if (!dateStr) return '-';
        const d = new Date(dateStr);
        return d.toLocaleString('en-IN', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }
    
    // $('.select2').select2({
    //     placeholder: 'Select address',
    //     dropdownParent: $('#jobPreviewEditModal'),
    //     ajax: {
    //         url: '/api/places/search',
    //         dataType: 'json',
    //         delay: 250,
    //         headers: {
    //             'Authorization': 'Bearer <?= $_SESSION["token"] ?? "" ?>'
    //         },
    //         data: params => ({ q: params.term }),
    //         processResults: data => ({
    //             results: data.map(item => ({
    //                 id: item.address,
    //                 text: item.address
    //             }))
    //         })
    //     }
    // });
    
    function setPreview(data) {
    
        // Customer
        $('#pv_name').text(data.name ?? '-');
        $('#pv_email').text(data.email ?? '-');
        $('#pv_mobile').text(data.mobile ?? '-');
    
        // Job
        $('#pv_job_no').text(data.job_no ?? '-');
        $('#pv_job_id').val(data.id ?? '-');
        $('#pv_job_type').text(data.job_type ?? '-');
        $('#pv_car_type').text(data.car_type ?? '-');
    
        // Route
        $('#pv_from').text(data.from_place ?? '-');
        $('#pv_to').text(data.to_place ?? '-');
        $('#pv_dis').text(data.distance ?? '-');
        $('#pv_dur').text(data.day ?? '-');
        $('#pv_pickup_address').text(data.pick_address ?? '-');
        $('#pv_drop_address').text(data.drop_address ?? '-');
    
        // Schedule
        $('#pv_pickup_time').text(formatDateTime12Hr(data.pickup_date));
        // $('#pv_drop_date').text(data.job_type == 'roundtrip' ? data.day : 'N/A');
    
        // Fare
        $('#pv_base_fare').text(data.base_fare ?? '0');
        $('#pv_toll_fare').text(data.toll_fare ?? '0');
        $('#pv_total_fare').text(data.fare ?? '0');
    
        // Button actions
        $('#sendOtpBtn').off().on('click', function () {
            sendOtp(data.id, data.mobile);
        });
        
        $('#forceRegBtn').off().on('click', function () {
            forceReg(data.id, data.mobile);
        });
    
        $('#commentBtn').off().on('click', function () {
            comment_form(data.id);
        });
    
        $('#jobPreviewModal').modal('show');
    }
    
    function setPreviewEdit(data) {
        
        let us_de = JSON.parse(data.user_details);
    
        $('#ed_job_id').val(data.id);
        $('#ed_job_no').val(data.job_no);
    
        $('#ed_name').val(data.name);
        $('#ed_email').val(data.email);
        $('#ed_mobile').val(data.mobile);
    
        $('#ed_job_type').val(data.job_type);
        $('#ed_car_type').val(data.car_type);
    
        $('#ed_from').val(data.from_place);
        $('#ed_to').val(data.to_place);
        $('#ed_dis').val(data.distance);
        $('#ed_dur').val(data.day);
    
        setSelect2Value('#ed_pickup_address', data.pick_address);
        setSelect2Value('#ed_drop_address', data.drop_address);
    
        $('#ed_pickup_time').val(formatForInput(data.pickup_date));
        // $('#ed_drop_date').val(data.day);
    
        $('#ed_base_fare').val(data.base_fare);
        $('#ed_toll_fare').val(data.toll_fare);
        $('#ed_passenger_count').val(us_de.pass_count ?? 1);
        $('#ed_luggage_count').val(us_de.lugg_count ?? 0);
        calculateFare();
    
        $('#jobPreviewEditModal').modal('show');
    }
    
    function formatForInput(dateStr) {
        if (!dateStr) return '';
    
        const d = new Date(dateStr);
    
        const pad = n => n.toString().padStart(2, '0');
    
        return (
            d.getFullYear() + '-' +
            pad(d.getMonth() + 1) + '-' +
            pad(d.getDate()) + 'T' +
            pad(d.getHours()) + ':' +
            pad(d.getMinutes())
        );
    }


    function calculateFare() {
        const base = Number($('#ed_base_fare').val()) || 0;
        const toll = Number($('#ed_toll_fare').val()) || 0;
        $('#ed_total_fare').text(base + toll);
    }


    
    function setSelect2Value(selector, value) {
        if (!value) return;
    
        const option = new Option(value, value, true, true);
        $(selector).append(option).trigger('change');
    }

    function sendOtp(jobId, mobile) {
    
        $('#sendOtpBtn')
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
            success: function (res) {
    
                if (res.status == 'success') {
                    $('#otpSection').removeClass('d-none');
                    $('#otpMsg').text('OTP sent successfully').addClass('text-success');
                    
                    window.enc = res.data.enc;
                    
                    toast('success', res.message);
    
                } else {
                    $('#otpMsg').text(res.message || 'Failed to send OTP')
                                .addClass('text-danger');
                }
            },
            error: function () {
                toast('error', res.message);
                // alert('OTP service unavailable');
            },
            complete: function () {
                $('#sendOtpBtn')
                    .prop('disabled', false)
                    .html('<i class="fas fa-key me-1"></i> Send OTP');
            }
        });
    }
    
    $('#verifyOtpBtn').on('click', function () {

        const otp = $('#otpInput').val().trim();
    
        if (!otp || otp.length !== 6) {
            $('#otpMsg').text('Please enter a valid 6-digit OTP')
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
                name: $('#pv_name').text(),
                email: $('#pv_email').text(),
                mobile: $('#pv_mobile').text(),
                platform_type: 'android',
                fcm_token: null
            },
            success: function (res) {
    
                if (res.status == 'success') {
    
                    $('#otpMsg').text('OTP verified successfully')
                                .removeClass('text-danger')
                                .addClass('text-success');
    
                    $('#otpInput, #verifyOtpBtn').prop('disabled', true);
    
                    window.userToken = res.token;
                    
                    var jobNo = $('#pv_job_no').text().trim();
                    var jobId = $('#pv_job_id').val();
                    var userId = res.data.user_id;
                    
                    Swal.fire({
                        title: 'Post Job Confirmation',
                        text: `Are you sure you want to post this job (${jobNo})?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Post Job',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            postJob(userId, jobId);
                        }
                    });

    
                } else {
                    $('#otpMsg').text(res.message || 'Invalid OTP')
                                .addClass('text-danger');
                }
            },
            error: function () {
                $('#otpMsg').text('Verification failed')
                            .addClass('text-danger');
            }
        });
    });
    
    function forceReg(jobId, mobile) {
    
        const $btn = $('#forceRegBtn');
    
        // Reset message
        // $msg.text('').removeClass('text-danger');
    
        $btn.prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin me-1"></i> Registering...');
    
        $.ajax({
            url: API_DOMAIN_2 + 'v1-cus/force-register-web',
            type: 'POST',
            dataType: 'json',
            timeout: 15000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                sid: jobId,
                mobile: '91' + mobile,
                platform_type: 'android',
                fcm_token: null
            }
        })
        .done(function (res) {
    
            if (res.status === 'success') {
    
                if (res.token) {
                    window.userToken = res.token;
                }
    
                const jobNo  = $('#pv_job_no').text().trim();
                const currentJobId = $('#pv_job_id').val();
                const userId = res.data.user_id;
    
                Swal.fire({
                    title: 'Post Job Confirmation',
                    text: `Are you sure you want to post this job (${jobNo})?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Post Job',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        postJob(userId, currentJobId);
                    }
                });
    
            } else {
    
                // $msg.text(res.message || 'Verification failed')
                //     .addClass('text-danger');
            }
        })
        .fail(function (xhr) {
    
            let errorMsg = 'Server error. Please try again.';
    
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
    
            // $msg.text(errorMsg)
            //     .addClass('text-danger');
        })
        .always(function () {
    
            // Re-enable button safely
            $btn.prop('disabled', false)
                .html('Force Register');
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
            beforeSend: function () {
                Swal.fire({
                    title: 'Posting Job...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function (res) {
    
                if (res.status === true) {
    
                    Swal.fire({
                        icon: 'success',
                        title: 'Job Posted',
                        text: `Job ${res.data} posted successfully`
                    });
                    
                    location.reload();
                    // optional redirect
                    // window.location.href = '/my-jobs';
    
                } else {
    
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: res.message || 'Job posting failed'
                    });
                }
            },
            error: function (xhr) {
    
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Server error'
                });
            }
        });
    }


    
    function userProfileUpdate(token, name, email, mobile) {
    
        $.ajax({
            url: API_DOMAIN_2 + 'v1-cus/user-profile-update',
            type: 'POST',
            dataType: 'json',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            data: {
                c_name: name,
                c_email: email,
                c_mobile: mobile,
                c_gender: 'MALE',
                whatsapp_update: 'yes'
            },
            success: function (res) {
    
                if (res.status === true) {
                    toast('success', 'Profile updated successfully');
                } else {
                    toast('error', res.message || 'Profile update failed');
                }
            },
            error: function () {
                toast('error', 'Profile update failed');
            }
        });
    }



    
    function comment_form(id, btn){
        $('#com_comment').val('');
        $('#com_message').val('');
        $('#com_dateInput').val('');
        $('#lead_id').val(id);
        if(id != ''){
            $(btn).prop('disabled', true);
              
            $.ajax({
                url: origin + "/ajax/service/leadService.php",
                method: "POST",
                dataType: "json",
                data: {
                    method: 'wb_lead_details',
                    id: id,
                },
                success: function(response) {
                    if (response.type === 1 && Array.isArray(response.result)) {
                        let rows = '';
                        response.result.forEach((item, index) => {
                            rows += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.person}</td>
                                    <td>${item.comment}<br><small>${item.message}</small></td>
                                    <td>${item.next_follow}</td>
                                    <td>${item.timestamp}</td>
                                </tr>
                            `;
                        });
                        $('#l_tbl').html(rows);
                    } else {
                        $('#l_tbl').html('<tr><td colspan="5" class="text-center">No data found</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    
                },
                complete: function() {
                    $(btn).prop('disabled', false);
                }
            });
        }
        $('#comment_form').modal('show');
    }
    
    function setCancel(job_no, btn){
    
        if(!job_no) return;
    
        Swal.fire({
            title: 'Are you sure?',
            html: `You are about to cancel this job.<br><br>
                   <strong>Job No: ${job_no}</strong>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Cancel Job',
            cancelButtonText: 'No, Keep It',
            reverseButtons: true
        }).then((result) => {
    
            if (result.isConfirmed) {
    
                $(btn).prop('disabled', true);
    
                $.ajax({
                    url: origin + "/ajax/service/Websitebook_service.php",
                    method: "POST",
                    dataType: "json",
                    data: {
                        method: 'cancel_job',
                        job_no: job_no,
                    },
                    success: function(response) {
                        if(response.type){
                            Swal.fire(
                                'Cancelled!',
                                response.result,
                                'success'
                            );
                        } else {
                            Swal.fire(
                                'Failed!',
                                response.result,
                                'error'
                            );
                        }
                        unsubscribeList();
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Something went wrong while cancelling Job No: ' + job_no,
                            'error'
                        );
                    },
                    complete: function() {
                        $(btn).prop('disabled', false);
                    }
                });
    
            }
    
        });
    }

    
    function wh_log_form(id, btn) {
        
        if (id != '') {
            $(btn).prop('disabled', true);
    
            $.ajax({
                url: origin + "/ajax/service/leadService.php",
                method: "POST",
                dataType: "json",
                data: {
                    method: 'webBook_log_details',
                    id: id,
                },
                success: function(response) {
                    let rows = '';
                    
                    if (response.type === 1 && Array.isArray(response.result) && response.result.length > 0) {
                        response.result.forEach((item, index) => {
                            rows += `
                                <tr>
                                    <td>${item.timestamp}</td>
                                    <td>${item.message}</td>
                                </tr>
                            `;
                        });
                    } else {
                        rows = '<tr><td colspan="3" class="text-center">No WhatsApp logs found.</td></tr>';
                    }
                    $('#wh_log_tbl_body').html(rows);
                },
                error: function(xhr, status, error) {
                    console.error("Log Fetch Error:", error);
                    $('#wh_log_tbl_body').html('<tr><td colspan="3" class="text-danger text-center">Error loading logs.</td></tr>');
                },
                complete: function() {
                    $(btn).prop('disabled', false);
                }
            });
        }
        // $('#comment_form').modal('show');
    }
    
    function openWhatsApp(id, btn, ph){
        // quill.setText("");
        wh_log_form(id, btn)
        ph = '91' + ph.replace(/\D/g, '');
        $('#what_id').val(id);
        $('#lead_phone').val(ph);
        $('#whatsapp_form').modal('show');
    }
    
    function sendWhatsApp(btn){
        
        let what_id = $('#what_id').val();
        let ph = $('#lead_phone').val();
        let template_id = $('#waTemplateID').val();
        let message = $('#templatePreview').val();
        
        if(what_id != '' && ph != '' && template_id != ''){
            $(btn).html('Sending...').prop('disabled', true);
            let ins = "<?= WHATSAPP_API_INS ?>";
            let api_key = "<?= WHATSAPP_API_KEY ?>";
            let whats_url = "<?= WHATSAPP_SERVER ?>";
    
            // First fetch template body from backend
            $.ajax({
                url: origin + "/ajax/service/salse_marketing_services.php",
                method: "POST",
                data: { method: 'get_single_template_webBook',id: template_id, mess: message, a_id: what_id },
                success: function(res){
                    try {
                        let data = JSON.parse(res);
                        if(data.success){
                            // let message = data.data.body;
                            // console.log(message);
                            // return message;
                            $.ajax({
                                url: `${whats_url}client/sendMessage/${ins}`,
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "x-api-key": `${api_key}`
                                },
                                data: JSON.stringify({
                                    chatId: `${ph}@c.us`,
                                    contentType: "string",
                                    content: message,
                                }),
                                success: function (response) {
                                    if(response.success){
                                        toast('success', response.message);
                                    }else{
                                        toast('error', response.message);
                                    }
                                },
                                error: function (xhr) {
                                    toast('error', "Something went wrong!");
                                },
                                complete: function() {
                                    $(btn).html('Send').prop('disabled', false);
                                }
                            });
                        } else {
                            toast('error', data.message);
                            $(btn).html('Send').prop('disabled', false);
                        }
                    } catch (e) {
                        toast('error', "Invalid response");
                        $(btn).html('Send').prop('disabled', false);
                    }
                },
                error: function(){
                    toast('error', "Unable to fetch template body");
                    $(btn).html('Send').prop('disabled', false);
                }
            });
        } else {
            toast('error', "Please select a template");
        }
        $('#whatsapp_form').modal('hide');
    }
        
        
    const comment_submit = (btn) => {
        const com_comment = $('#com_comment').val();
        const com_message = $('#com_message').val();
        const com_dateInput = $('#com_dateInput').val();
        const lead_id = $('#lead_id').val();
        
        $(btn).html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Loading...`)
              .prop('disabled', true);
              
        if(com_comment == '' || com_message == '' || com_dateInput == '' || lead_id == ''){
            toast('error', 'Fill all inputs');
            $(btn).html('Submit').prop('disabled', false);
            return false;
        }
        
        $.ajax({
            url: origin + "/ajax/service/leadService.php",
            method: "POST",
            // contentType: "application/json",
            dataType: "json",
            data: {
                method: 'webBook_lead_comment',
                com_comment: com_comment,
                com_message: com_message,
                com_dateInput: com_dateInput,
                lead_id: lead_id
            },
            success: function(response) {
                // console.log(response.result)
                // response = JSON.parse(response);co
                toast('success', response.result);
                $('#comment_form').modal('hide');
                unsubscribeList()
            },
            error: function(xhr, status, error) {
                // response = JSON.parse(response);
                toast('success', response.result);
                $('#comment_form').modal('hide');
                unsubscribeList()
                // Optional: show error message
            },
            complete: function() {
                $(btn).html('Submit').prop('disabled', false);
            }
        });
    };

    
    const drawIDchange = () => {
        try {
            $('#datefilterLogin').trigger('cancel.daterangepicker');
        } catch (e) {
            console.log(`Error: ${e.message}`);
        }
    }
    var url01 = origin + "/ajax/service/datatable_services.php";
    $(function () {
        
        $('#comment_submit').on('click', function () {
            comment_submit(this);
        });
        $('#whatsapp_submit').on('click', function () {
            sendWhatsApp(this);
        });
        
         $(document).on('change', '.toggle-profile', function () {
            let checkbox = $(this);
            let isChecked = checkbox.is(':checked');
            let slider = checkbox.siblings('span');
            let innerKnob = slider.find('.slider-inner');
        
            slider.css('background-color', isChecked ? '#49117cc9' : '#ccc');
            innerKnob.css('transform', isChecked ? 'translateX(22px)' : 'translateX(0)');
        
            let crmId = checkbox.data('id');
            let userId = checkbox.data('userid');
            let status = isChecked ? 1 : 0;
            $.ajax({
                url: url01,
                method: 'POST',
                data: {
                    method: 'change_sub_status',
                    crmId: crmId,
                    userId: userId,
                    status: status
                },
                success: function (data) {
                    var response = JSON.parse(data);
                    if (response.type == 1) {
                        toast('success', response.result);
                    } else {
                        toast('error', response.result);
        
                        checkbox.prop('checked', !isChecked);
                        slider.css('background-color', !isChecked ? '#49117cc9' : '#ccc');
                        innerKnob.css('transform', !isChecked ? 'translateX(22px)' : 'translateX(0)');
                    }
                },
                 error: function () {
                    toast('error', 'Something went wrong');
        
                    checkbox.prop('checked', !isChecked);
                    slider.css('background-color', !isChecked ? '#49117cc9' : '#ccc');
                    innerKnob.css('transform', !isChecked ? 'translateX(22px)' : 'translateX(0)');
                }
            });
        });
        
        try {
            createDatePicker('datefilterLogin');

            $('#datefilterLogin').trigger('cancel.daterangepicker');
            unsubscribeList();
        } catch (e) {
            console.log(`Error: ${e.message}`);
        }
    });
    
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
    
</script>