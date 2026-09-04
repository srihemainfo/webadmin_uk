<?php
$pageTitle = "Schedule Driver";
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>

.loading-input {
    background: #f2f2f2;
    color: #999;
}


.user-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}

.user-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}

.user-card i {
    cursor: pointer;
    transition: color 0.2s;
}

.user-card i:hover {
    color: #000;
}

#card-container .card {
    transition: all 0.2s ease-in-out;
}

#card-container .card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

.info-input {
    border: none;
    border-bottom: 2px solid #e0e0e0;
    background: transparent;
    font-size: 16px;
    font-weight: 500;
    padding: 4px 0;
    width: 100%;
    outline: none;
    color: #333;
    transition: all 0.3s ease;
}

.info-input:focus {
    border-bottom: 2px solid #0072ff;
}

.info-input::placeholder {
    color: #aaa;
}

.info-label {
    font-weight: 600;
    color: #444;
}

.card-header.d-lg-flex.d-block.justify-content-between {
    border-bottom: none;
}

.cursor-pointer {
    cursor: pointer;
    font-size: 1.2rem;
    /* Adjust size as needed */
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

.text-primary-bs {
    color: #007bff !important;
    /* Primary color */
}

.text-success-bs {
    color: #28a745 !important;
    /* Success color */
}

.text-warning-bs {
    color: #ffc107 !important;
    /* Warning color */
}

.text-danger-bs {
    color: #dc3545 !important;
    /* Danger color */
}

.verify-sticker {
    display: inline-block;
    font-weight: 600;
    font-size: 1rem;
    padding: 8px 20px;
    border-radius: 50px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    color: #fff;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.verify-sticker.verified {
    background: linear-gradient(135deg, #4caf50, #2e7d32);
}

.verify-sticker.invalid {
    background: linear-gradient(135deg, #e53935, #b71c1c);
}

.verify-sticker i {
    font-size: 1.1rem;
    vertical-align: middle;
}

.premium-toggle {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 32px;
}

.premium-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider1 {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background: linear-gradient(145deg, #e0e0e0, #f9f9f9);
    border-radius: 50px;
    transition: all 0.4s ease;
    box-shadow: inset 2px 2px 5px #d1d1d1, inset -2px -2px 5px #fff;
}

.slider1::before {
    content: "";
    position: absolute;
    height: 24px;
    width: 24px;
    left: 4px;
    bottom: 4px;
    background: white;
    border-radius: 50%;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
    transition: all 0.4s ease;
}

input:checked+.slider1 {
    background: linear-gradient(145deg, #1dd1a1, #10ac84);
    box-shadow: inset 2px 2px 5px #0e8c69, inset -2px -2px 5px #22e4ba;
}

input:checked+.slider1::before {
    transform: translateX(28px);
    background: #fff;
}

.status-text {
    font-size: 16px;
    min-width: 110px;
    text-align: right;
    transition: color 0.3s ease;
}

/* Optional: glow effect when verified */
input:checked+.slider1::before {
    box-shadow: 0 0 10px rgba(16, 172, 132, 0.7);
}

.vehicle-card {
    transition: all 0.3s ease;
}

.vehicle-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.vehicle-details h5 {
    font-size: 1.1rem;
}

.vehicle-images img,
.remaining-images img {
    transition: all 0.3s ease;
}

.vehicle-images img:hover,
.remaining-images img:hover {
    transform: scale(1.05);
    cursor: pointer;
}

.remaining-images {
    border-top: 1px dashed #ddd;
    padding-top: 8px;
}

.view-more-btn {
    font-size: 0.85rem;
    font-weight: 500;
}

.doc-verified {
    position: absolute;
    top: -9px;
    right: -13px;
    width: 15px;
    height: 15px;
}

    /* PETROL - Red */
    .fuel-petrol {
        color: #dc3545 !important;
        border: 2px solid #dc3545 !important;
        border-radius: 8px;
    }
    .fuel-petrol:focus {
        box-shadow: 0 0 5px rgba(220,53,69,0.6) !important;
        border-color: #dc3545 !important;
    }

    /* DIESEL - Secondary (Gray) */
    .fuel-diesel {
        color: #6c757d !important;
        border: 2px solid #6c757d !important;
        border-radius: 8px;
    }
    .fuel-diesel:focus {
        box-shadow: 0 0 5px rgba(108,117,125,0.6) !important;
        border-color: #6c757d !important;
    }

    /* CNG - Green */
    .fuel-cng {
        color: #198754 !important;
        border: 2px solid #198754 !important;
        border-radius: 8px;
    }
    .fuel-cng:focus {
        box-shadow: 0 0 5px rgba(25,135,84,0.6) !important;
        border-color: #198754 !important;
    }
    
    .card-custom {
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: none;
    }
    .select2-container {
       width: 250px !important; 
    }
    .form-control, .select2-container--default .select2-selection--single {
        height: 44px !important;
        padding: 8px 12px !important;
        border-radius: 8px !important;
    }
    .select2-selection__rendered {
        padding-left: 12px !important;
    }
    .select2-selection__arrow {
        height: 44px !important;
    }
    
    .drop-area {
        cursor: pointer;
        background: #f8f9fa;
      }
      .drop-area.dragover {
        background: #d1e7dd;
        border-color: #198754;
      }
</style>
<script>
window.onload = function() {
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
            <div class="page-header">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left"
                            onclick="history.go(-1)" aria-hidden="true"></i></a><?= $pageTitle; ?></h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= $pageTitle; ?></li>
                    </ol>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-6 d-flex align-items-start justify-content-start gap-3">
                    <div>
                        <label class="form-label text-danger">Select user</label>
                        <!--<input type="number" class="form-control fuel-petrol" id="petrol" placeholder="0.00" value="101">-->
                        <select id="users_id" class="form-control location-select mb-3">
                        </select>
                    </div>
                </div>
                <div class="col-6 d-flex align-items-end justify-content-end gap-3">
                    <div>
                        <label class="form-label text-danger">Petrol (per ltr)</label>
                        <input type="number" class="form-control fuel-petrol" id="petrol" placeholder="0.00" value="101">
                    </div>
                    <div>
                        <label class="form-label text-secondary">Diesel (per ltr)</label>
                        <input type="number" class="form-control fuel-diesel" id="diesel" placeholder="0.00" value="94">
                    </div>
                    <div>
                        <label class="form-label text-success">CNG (per ltr)</label>
                        <input type="number" class="form-control fuel-cng" id="cng" placeholder="0.00" value="91">
                    </div>
                </div>

            </div>
            <div class="row g-4">
                <!-- Left Section -->
                <div class="col-9">
                    <div class="card card-custom">
                        <div class="card-body">
            
                            <!-- Row 1 -->
                            <div class="row mb-4">
                                
                                <!-- From / To -->
                                <div class="col-4">
                                    <label class="form-label">From</label>
                                    <select id="from_place" class="form-control location-select mb-3" data-field="from_place"></select>
            
                                    <label class="form-label">To</label>
                                    <select id="to_place" class="form-control location-select" data-field="to_place"></select>
                                </div>
            
                                <div class="col-2">
                                    <label class="form-label">Distance (Km)</label>
                                    <input type="text" class="form-control mb-3" id="distance" placeholder="Distance" data-field="distance">
            
                                    <label class="form-label">Duration</label>
                                    <input type="text" class="form-control" id="duration" placeholder="Duration" data-field="duration">
                                </div>
            
                                <!-- Mileage / Toll / Fuel -->
                                <div class="col-6">
                                    <div class="row g-3">
                                        <div class="col-4">
                                            <label class="form-label">Mileage (per ltr)</label>
                                            <input type="number" class="form-control" id="mileage" placeholder="0.00" onkeyup="calFuel()" data-field="mileage">
                                        </div>
                                        
                                        <div class="col-4">
                                            <label class="form-label fuel_type">Fuel (Oneway) (₹)</label>
                                            <input type="number" class="form-control" id="fuel_fare" placeholder="0.00" onkeyup="calFuel()" data-field="fuel_fare">
                                            <input type="hidden" class="form-control" id="fuel_type" value="">
                                        </div>
                                        
                                        <div class="col-4">
                                            <label class="form-label fuel_type">Fuel (Return) (₹)</label>
                                            <input type="number" class="form-control" id="fuel_ret_fare" placeholder="0.00" onkeyup="calFuel()" data-field="fuel_ret_fare">
                                            <input type="hidden" class="form-control" id="fuel_ret_type" value="">
                                        </div>
                                        
            
                                        <div class="col-12" style="margin-top: 2px;">
                                            <label class="form-label">Toll Fare (₹)</label>
                                            <div class="d-flex gap-2">
                                                <input type="number" class="form-control" id="toll_fare" placeholder="0.00" onkeyup="calFuel()" data-field="toll_fare">
                                                <!--<button type="button" class="btn btn-primary" onclick="getTollPrice(this)">Fetch</button>-->
                                            </div>
                                        </div>
            
                                    </div>
                                </div>
            
                            </div>
            
                            <!-- Row 2 -->
                            <div class="row mb-4">
            
                                <!-- Date Range -->
                                <!--<div class="col-4">-->
                                    <!--<label class="form-label">Date Range</label>-->
                                    <!--<div class="d-flex gap-3">-->
                                        <input class="form-control d-none" type="text" id="date_from" name="date_from" placeholder="Select Date" value="" data-field="date_from" readonly />
                                        <!--<input class="form-control" type="text" id="date_to" name="date_to" placeholder="Select Date" value="" readonly />-->
                                    <!--</div>-->
                                <!--</div>-->
            
                                <!-- Our Price / Driver Price -->
                                <div class="col-12">
                                    <div class="row g-3 justify-content-start">
                                        <div class="col-2">
                                            <label class="form-label">Our Price (₹)</label>
                                            <input type="number" class="form-control" id="our_price" placeholder="0.00" onkeyup="calFuel()">
                                        </div>
            
                                        <div class="col-2">
                                            <label class="form-label">Oneway Amt (₹)</label>
                                            <input type="number" class="form-control" id="driver_one_tot_price" placeholder="0.00" onkeyup="calFuel()" data-field="driver_one_tot_price">
                                        </div>
                                        
                                        <div class="col-2">
                                            <label class="form-label">Return Amt (₹)</label>
                                            <input type="number" class="form-control" id="driver_ret_tot_price" placeholder="0.00" onkeyup="calFuel()" data-field="driver_ret_tot_price">
                                        </div>
                                        
                                        <div class="col-2">
                                            <label class="form-label text-success">Oneway Total (₹)</label>
                                            <input type="number" class="form-control" id="driver_one_price" placeholder="0.00" data-field="driver_one_price">
                                        </div>
                                        
                                        <div class="col-2">
                                            <label class="form-label text-danger">Return Total (₹)</label>
                                            <input type="number" class="form-control" id="driver_ret_price" placeholder="0.00" data-field="driver_ret_price">
                                            <!--<input type="number" class="form-control" id="driver_price" placeholder="0.00" data-field="driver_price">-->
                                        </div>
                                    </div>
                                </div>
            
                            </div>
            
                            <!-- Submit Row -->
                            <div class="row">
                                <div class="col-12 text-end">
                                    <!--<button class="btn btn-success">Preview in Excel</button>-->
                                    <!--<button class="btn btn-success" onclick="submit_schedule()" id="submit_btn">Submit Schedule</button>-->
                                    <button class="btn btn-primary" id="add_tbl" onclick="add_list()">+</button>
                                </div>
                            </div>
            
                        </div>
                    </div>
                </div>
            
                <!-- Right Table -->
                <div class="col-3">
                    <div class="card card-custom p-0" id="user_info_box">
                        <div class="p-3">
                            <h5 class="mb-3">User Details</h5>
                
                            <p><strong>Name:</strong> <span id="u_name">XXXXXXXXX</span></p>
                            <p><strong>Mobile:</strong> <span id="u_mobile">91XXXXXXXXX</span></p>
                            <p><strong>Fuel Type:</strong> <span id="u_fuel">XXXXXXXXX</span></p>
                
                            <hr>
                
                            <h6>Vehicle Details</h6>
                            <p><strong>RC Number:</strong> <span id="u_rc">TNXXXXXXXXX</span></p>
                            <p><strong>RC Expiry:</strong> <span id="u_rc_exp">XXXX-XX-XX</span></p>
                        </div>
                
                        <!-- WhatsApp Button -->
                        <div class="p-3 border-top text-center">
                            <button class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2"
                                data-bs-toggle="modal" data-bs-target="#whatsappModal">
                                <i class="bi bi-whatsapp" style="font-size: 1.3rem;"></i>
                                Send WhatsApp
                            </button>
                        </div>

                    </div>
                </div>


            </div>
            
            <div class="row">
                <!-- Left Section -->
                <div class="col-12">
                    <div class="card card-custom">
                        <div class="card-body">
                            <h4 class="mb-4">Price Plan List</h4>
                            
                            <div class="table-responsive">
                                <div class="mb-3 text-end">
                                    <button class="btn btn-success" onclick="exportExcel()">Export Excel</button>
                                    <button class="btn btn-danger" onclick="exportPDF()">Export PDF</button>
                                </div>

                                <!--<table class="table table-hover table-striped">-->
                                <!--    <thead class="">-->
                                <!--        <tr>-->
                                <!--            <th>From</th>-->
                                <!--            <th>To</th>-->
                                <!--            <th>From Date</th>-->
                                <!--            <th>To Date</th>-->
                                <!--            <th>Distance</th>-->
                                <!--            <th>Duration</th>-->
                                <!--            <th>Fuel Type</th>-->
                                <!--            <th>Mileage</th>-->
                                <!--            <th>Fuel Fare</th>-->
                                <!--            <th>Toll Fare</th>-->
                                <!--            <th>Your Price</th>-->
                                <!--            <th>Action</th>-->
                                <!--        </tr>-->
                                <!--    </thead>-->
                            
                                <!--    <tbody id="list_tbl">-->
                                        
                                <!--    </tbody>-->
                                <!--</table>-->
                                <table class="table table-hover table-striped" id="schedule_list">
                                    <thead>
                                
                                        <!-- Extra info row INSIDE the table -->
                                        <tr class="table-info">
                                            <th colspan="12">
                                                <strong>Name:</strong> <span id="t_name"></span> &nbsp;&nbsp;
                                                <!--<strong>Mobile:</strong> <span id="t_mobile"></span> &nbsp;&nbsp;-->
                                                <strong>Vehicle Number:</strong> <span id="t_vehicle"></span>
                                            </th>
                                        </tr>
                                
                                        <tr id="list_tbl_th">
                                            <th>From</th>
                                            <th>To</th>
                                            <th style="display: none;">From Date</th>
                                            <th style="display: none;">To Date</th>
                                            <th>Distance</th>
                                            <th>Duration</th>
                                            <th>Fuel Type / Mileage</th>
                                            <th>Fuel Fare O/R</th>
                                            <th>Toll Fare O/R</th>
                                            <th>Oneway Driver Amt</th>
                                            <th>Return Driver Amt</th>
                                            <th>Oneway Total</th>
                                            <th>Return Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                
                                    <tbody id="list_tbl"></tbody>
                                </table>

                            </div>

                        </div>
                    </div>
                </div>
                
                <div class="col-12 text-center mb-5">
                    <button class="btn btn-success" onclick="submit_schedule()" id="submit_btn">Submit Schedule</button>
                </div>
            </div>

        </div>
        
    </div>
</div>

<!-- WhatsApp Modal -->
<div class="modal fade" id="whatsappModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Send Document via WhatsApp</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- Drag & Drop Upload -->
        <div id="drop-area" class="drop-area text-center p-4 border border-2">
          <i class="bi bi-cloud-arrow-up" style="font-size: 3rem;"></i>
          <p class="mt-2">Drag & drop your document here<br>or click to upload</p>
          <input type="file" id="docInput" hidden>
        </div>

        <div id="fileName" class="mt-3 text-center text-success fw-bold d-none"></div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-success" onclick="sendWhatsAppDocument()">Send</button>
      </div>

    </div>
  </div>
</div>

<!-- Leave Page Confirmation Modal -->
<div class="modal fade" id="leaveModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Unsaved Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p>You have unsaved data. Are you sure you want to leave this page?</p>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-danger" onclick="forceLeave()">Leave</button>
      </div>

    </div>
  </div>
</div>


<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>


<script>

function getCCookie(name) {
    const cookies = document.cookie.split(';');

    for (let cookie of cookies) {
        cookie = cookie.trim();

        if (cookie.startsWith(name + '=')) {
            return decodeURIComponent(cookie.substring(name.length + 1));
        }
    }
    return null; // cookie not found
}

function deleteCCookie(name, path = "/", domain) {
    let cookie = `${name}=; Max-Age=0; path=${path};`;

    if (domain) {
        cookie += ` domain=${domain};`;
    }

    document.cookie = cookie;
}

var url01 = origin + "/ajax/service/schedule_job.php";


let warnBeforeLeave = false;  // TRUE = block page refresh/close

var schedule_d = getCCookie('schedule_d_mobile') ?? '';
if (schedule_d) {
    $('#users_id').html(`
        <option value="${schedule_d}" selected>
            ${schedule_d}
        </option>
    `);
    get_userDetails();
    deleteCCookie('schedule_d_mobile');
}

// Enable protection when a row is added
function enableLeaveProtection() {
    warnBeforeLeave = true;
}

// Disable protection when user confirms to leave
function disableLeaveProtection() {
    warnBeforeLeave = false;
}

// Detect unsaved rows in table
function hasUnsavedRows() {
    return $("#list_tbl tr").length > 0;  // at least one row
}

window.addEventListener("beforeunload", function (e) {
    if (warnBeforeLeave && hasUnsavedRows()) {
        e.preventDefault();
        // openLeaveModal();
        e.returnValue = "";
    }
});

function openLeaveModal() {
    if (hasUnsavedRows()) {
        new bootstrap.Modal(document.getElementById("leaveModal")).show();
    } else {
        // safe to leave immediately
        window.location.href = "/home";   // <-- your redirect path
    }
}

function forceLeave() {
    disableLeaveProtection();   // disable warnings
    location.reload();          // or window.close(), or redirect
}


function removeRow(el) {
    $(el).closest("tr").remove();
    computeTotals();

    // If no rows left → disable protection
    if (!hasUnsavedRows()) {
        disableLeaveProtection();
    }
}

const createDatePicker = (id) => {
    try {
        const selector = `#${id}`;
        const today = moment();
        const start = moment().subtract(0, 'days'); // same as your original

        $(selector).daterangepicker({
            autoUpdateInput: true,
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY hh:mm A' // time format applied
            },

            maxDate: today,
            opens: 'left',

            // Enable time picker
            timePicker: true,
            timePicker24Hour: false,      // 12-hour format
            timePickerSeconds: false,     // disable seconds
            timePickerIncrement: 1,       // minute interval

            startDate: start,
            endDate: today,

            ranges: {
                'Today': [moment().startOf('day'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
            }
        });

        // When user selects date range
        $(selector).on('apply.daterangepicker', function (ev, picker) {
            $(this).val(
                picker.startDate.format('DD/MM/YYYY hh:mm A') +
                ' - ' +
                picker.endDate.format('DD/MM/YYYY hh:mm A')
            );
        });

        // When user clears the input
        $(selector).on('cancel.daterangepicker', function () {
            $(this).val('');
        });

    } catch (e) {
        console.log(`Error: ${e.message}`);
    }
};

function exportExcel() {
    var table = document.getElementById("list_tbl");
    var wb = XLSX.utils.table_to_book(table, { sheet: "Schedule List" });
    XLSX.writeFile(wb, "Schedule List.xlsx");
}

function exportPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'pt', 'a4');

    doc.text("Schedule List", 40, 30);

    doc.autoTable({
        html: '#schedule_list',
        startY: 50,
        styles: { fontSize: 8 }
    });

    doc.save("Schedule List.pdf");
}

let selectedFile = null;

document.getElementById("drop-area").addEventListener("click", () => {
    document.getElementById("docInput").click();
});

// When selecting via input
document.getElementById("docInput").addEventListener("change", function() {
    selectedFile = this.files[0];
    showFileName();
});

// Drag events
const dropArea = document.getElementById("drop-area");

dropArea.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropArea.classList.add("dragover");
});

dropArea.addEventListener("dragleave", () => {
    dropArea.classList.remove("dragover");
});

dropArea.addEventListener("drop", (e) => {
    e.preventDefault();
    dropArea.classList.remove("dragover");

    selectedFile = e.dataTransfer.files[0];
    showFileName();
});

// Show file name
function showFileName() {
    if (selectedFile) {
        document.getElementById("fileName").classList.remove("d-none");
        document.getElementById("fileName").innerText = "Selected: " + selectedFile.name;
    }
}

// Send button
function sendWhatsAppDocument() {
    if (!selectedFile) {
        alert("Please upload a document before sending.");
        return;
    }

    // Extract user info from card
    // const mobile = document.getElementById("u_mobile").innerText.replace(/\D/g, "");
    const mobile = '919585769163';
    const name = 'Elavarasan';
    // const name = document.getElementById("u_name").innerText;

    // You cannot attach a file directly to WhatsApp API via URL.
    // You need to upload the document to your server and generate a file link.

    // Example message:
    const message = `Hello ${name}, your requested document is attached.`;

    alert("NOTE: WhatsApp attachment requires a file URL.\n\nUpload file to your server, then send the link.");

    // For now, open WhatsApp with message only:
    window.open(`https://wa.me/${mobile}?text=${encodeURIComponent(message)}`, "_blank");
}


$(function() {
    
    
    function initLocationSelect(selector) {
        $(selector).select2({
            placeholder: "Search Location...",
            minimumInputLength: 2,
            width: "100%",
            ajax: {
                url: "https://www.goride.run/api/get-location",
                type: "POST",
                delay: 250,
                data: function (params) {
                    return {
                        auth_token: "ASDFGHJKLqwertyuiopMNBVCXZ!@#$%^&*()0987612345",
                        search: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.data.map(function(item) {
                            return {
                                id: item.name,
                                text: item.name
                            };
                        })
                    };
                },
                error: function(xhr){
                    console.log("Error:", xhr.responseText);
                }
            }
        });
    }
    
    function usersSelect(selector) {
        $(selector).select2({
            placeholder: "Choose user...",
            minimumInputLength: 2,
            width: "100%",
            ajax: {
                url: url01,
                type: "POST",
                data: function (params) {
                    return {
                        method: "get_users",
                        search: params.term
                    };
                },
                processResults: function (data) {
                    // console.log(data)
                    data = JSON.parse(data);
                    return {
                        results: data.data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.name + ' - ' + item.mobile
                            };
                        })
                    };
                },
                error: function(xhr){
                    console.log("Error:", xhr.responseText);
                }
            }
        });
    }
    
    createDatePicker('date_from');
    // createDatePicker('date_to');

    // Initialize both dropdowns
    initLocationSelect("#from_place");
    initLocationSelect("#to_place");
    usersSelect("#users_id");
});

// if (schedule_d) {

//     $.ajax({
//         url: url01,
//         type: "POST",
//         data: {
//             method: "get_user_by_mobile",
//             mobile: schedule_d
//         },
//         success: function (response) {

//             response = JSON.parse(response);

//             if (response.status === 1) {
//                 const user = response.data;

//                 // Create new option
//                 const option = new Option(
//                     user.name + ' - ' + user.mobile,
//                     user.id,
//                     true,
//                     true
//                 );

//                 $(selector).append(option).trigger('change');
//             }
//         }
//     });
// }


const drawIDchange = () => {
    try {
        $('#date_from').trigger('cancel.daterangepicker');
        // $('#date_to').trigger('cancel.daterangepicker');
    } catch (e) {
        console.log(`Error: ${e.message}`);
    }
}


$('#date_from').trigger('cancel.daterangepicker');
// $('#date_to').trigger('cancel.daterangepicker');


$('#from_place, #to_place').on('change', function () {
    if($("#from_place").val() && $("#to_place").val()) {
        fetchDistance();
        fetchToll();
    }
});

$('#users_id').on('change', function () {
    if($("#users_id").val() != '') {
        get_userDetails();
    }
});

function get_userDetails() {
    
        let u_id = $('#users_id').val();
    
        if (u_id !== '') {
    
            $.ajax({
                url: url01,
                type: "POST",
                data: {
                    method: "get_users_details",
                    search: u_id
                },
                success: function (data) {
                    data = JSON.parse(data);
                    console.log(data);
                    if (data.status) {
                
                        let u = data.data;
                        $('#fuel_type').val(u.fuel_type);
                        $('.fuel_type').html('Fuel Fare (₹) (' + u.fuel_type + ')');
                        let html = `
                            <div class="p-3">
                                <h5 class="mb-3">User Details</h5>
                
                                <p><strong>Name:</strong> ${u.name}</p>
                                <p><strong>Mobile:</strong> ${u.mobile}</p>
                                <p><strong>Fuel Type:</strong> ${u.fuel_type}</p>
                
                                <hr>
                
                                <h6>Vehicle Details</h6>
                                <p><strong>RC Number:</strong> ${u.rc_number}</p>
                                <p><strong>RC Expiry:</strong> ${u.rc_expiry_date}</p>
                            </div>
                            
                            <div class="p-3 border-top text-center">
                                <button class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2"
                                    data-bs-toggle="modal" data-bs-target="#whatsappModal" data-user_id="${u_id}" data-mobile="${u.mobile}">
                                    <i class="bi bi-whatsapp" style="font-size: 1.3rem;"></i>
                                    Send WhatsApp
                                </button>
                            </div>
                        `;
                
                        // Append to the right card
                        $("#user_info_box").html(html);
                        
                        $("#t_name").text(u.name);
                        // $("#t_mobile").text(u.mobile);
                        $("#t_vehicle").text(u.rc_number);
                    }
                    // handle response here
                },
                error: function (xhr) {
                    console.log("Error:", xhr.responseText);
                }
            });
    
        }
    }

$(document).on('select2:open', function () {
    $('.select2-search select2-search--dropdown .select2-search__field').focus();
});

let wa = { user_id: "", mobile: "", file: null };

document.getElementById('whatsappModal').addEventListener('show.bs.modal', e => {
    let b = e.relatedTarget;
    wa.user_id = b.dataset.user_id;
    wa.mobile  = b.dataset.mobile;
});

let drop = document.getElementById("drop-area");
let input = document.getElementById("docInput");
let fname = document.getElementById("fileName");

drop.onclick = () => input.click();
input.onchange = e => showFile(e.target.files[0]);

["dragover","drop"].forEach(ev => drop.addEventListener(ev, e=>{
    e.preventDefault();
    if(ev==="drop") showFile(e.dataTransfer.files[0]);
}));

function showFile(file) {
    wa.file = file;
    fname.textContent = file.name;
    fname.classList.remove("d-none");
}

function sendWhatsAppDocument() {

    if(!wa.file) return alert("Upload a file.");

    let fd = new FormData();
    fd.append("method", "send_whatsapp_document");
    fd.append("user_id", wa.user_id);
    fd.append("mobile", wa.mobile);
    fd.append("list_pdf", wa.file);

    $.ajax({
        url: url01,
        type: "POST",
        data: fd,
        processData: false,
        contentType: false,
        beforeSend: () => $(".btn-success").prop("disabled", true),
        complete: () => $(".btn-success").prop("disabled", false),
        success: r => { alert("Sent!"); $("#whatsappModal").modal("hide"); },
        error: x => alert("Error sending.")
    });
}



function fetchDistance() {
    $.ajax({
        url: "https://www.goride.run/api/get-distance",
        type: "POST",
        dataType: "json",
        data: {
            auth_token: "ASDFGHJKLqwertyuiopMNBVCXZ!@#$%^&*()0987612345",
            from: $("#from_place").find("option:selected").text(),
            to: $("#to_place").find("option:selected").text(),
            way_type: "oneway"
        },
        success: function (response) {
            console.log(response);

            if(response.status) {
                $("#distance").val(response.data.distance);
                $("#duration").val(response.data.duration);
                $("#our_price").val(response.data.fare);
            }
        }
    });
}

function fetchToll() {
    $.ajax({
        url: origin + "/ajax/service/tollBOTService.php",
        type: "POST",
        dataType: "json",
        data: {
            method: "toll_details",
            authKey: "ASDFGHJKLqwertyuiopMNBVCXZ!@#$%^&*()0987612345",
            from_place: $("#from_place").find("option:selected").text(),
            to_place: $("#to_place").find("option:selected").text()
        },
        beforeSend: function () {
            $("#toll_fare")
                .val("Loading...")
                .prop("disabled", true)
                .addClass("loading-input");
        },
        success: function (response) {
            console.log(response);

            if (response.type == 1) {
                $("#toll_fare").val(response.result.toll);
            }else{
                $("#toll_fare").val(0);
            }
        },
        complete: function () {
            // Remove loading effect
            $("#toll_fare")
                .prop("disabled", false)
                .removeClass("loading-input");
        },
    });
}

// function getTollPrice(btn) {

//     let $btn = $(btn); // convert to jQuery object

//     $btn.text("Getting price...");
//     $btn.prop("disabled", true); // disable button

//     // Simulate toll price fetch
//     setTimeout(() => {
//         $('#toll_fare').val(200);

//         $btn.text("Fetch Toll");
//         $btn.prop("disabled", false);
//         $btn.hide();
//     }, 1000);
    
//     if($('#toll_fare').val() != ''){
//         $btn.show();
//     }
    
//     calFuel()
// }

function calFuel() {

    let mileage = parseFloat($('#mileage').val()) || 0;
    let distance = parseFloat($('#distance').val()) || 0;
    let petrolRate = parseFloat($('#petrol').val()) || 0;
    let dieselRate = parseFloat($('#diesel').val()) || 0;
    let cngRate = parseFloat($('#cng').val()) || 0;
    let our_price = parseFloat($('#our_price').val()) || 0;
    let toll_fare = parseFloat($('#toll_fare').val()) || 0;
    // let driver_price = parseFloat($('#driver_price').val()) || 0;
    let driver_one_tot_price = parseFloat($('#driver_one_tot_price').val()) || 0;
    let driver_ret_tot_price = parseFloat($('#driver_ret_tot_price').val()) || 0;

    if (mileage <= 0 || distance <= 0 || petrolRate <= 0 || dieselRate <= 0 || cngRate <= 0) {
        $('#fuel_fare').val(0);
        $('#fuel_ret_fare').val(0);
        return;
    }
    
    
    if($('#fuel_type').val() == 'Diesel'){
        var fuelCost = parseFloat(distance / mileage).toFixed(5) * dieselRate;
        console.log(parseFloat(distance / mileage).toFixed(5), dieselRate, fuelCost)
        
    }else if($('#fuel_type').val() == 'Petrol'){
        var fuelCost = parseFloat(distance / mileage).toFixed(5) * petrolRate;
    }else if($('#fuel_type').val() == 'CNG'){
        var fuelCost = parseFloat(distance / mileage).toFixed(5) * cngRate;
    }
    
    let tot_one = 0;
    let tot_ret = 0;
    
    if(driver_one_tot_price != 0 && driver_one_tot_price != ''){
        tot_one = driver_one_tot_price + fuelCost + toll_fare;
    }else{
        // tot = our_price + fuelCost + toll_fare;
        tot_one = 0;
    }
    
    if(driver_ret_tot_price != 0 && driver_ret_tot_price != ''){
        tot_ret = driver_ret_tot_price + (fuelCost * 2) + (toll_fare * 2);
    }else{
        // tot = our_price + fuelCost + toll_fare;
        tot_ret = 0;
    }
    
    fuelCost = parseFloat(fuelCost).toFixed(2);
    tot_one = tot_one.toFixed(2);
    tot_ret = tot_ret.toFixed(2);
    
    // $('#approx_amt').html('Total Amt is ₹' + tot);
    
    $('#fuel_fare').val(fuelCost);
    $('#fuel_ret_fare').val(fuelCost * 2);
    $('#driver_one_price').val(tot_one);
    $('#driver_ret_price').val(tot_ret);
}

function removeRow(btn) {
    $(btn).closest("tr").remove();
}

function clear_inputs() {
    $('[data-field]').not('[data-field="date_from"]').val('').removeClass('is-invalid');
    $('#from_place, #to_place')
        .val(null)
        .trigger('change')
        .removeClass('is-valid is-invalid');

}

function safeVal(el) {
    let v = $(el).val();
    return (typeof v === "string") ? v.trim() : "";
}

function add_list() {

    const fields = {};
    let isValid = true;

    // Collect all inputs with data-field attribute
    $("[data-field]").each(function () {
        const key = $(this).data("field");
        const val = safeVal(this);

        if (val === "") {
            $(this).addClass("is-invalid").removeClass("is-valid");
            isValid = false;
        } else {
            $(this).addClass("is-valid").removeClass("is-invalid");
        }
        fields[key] = $("<div>").text(val).html();
    });

    if (!isValid) {
        toast('error', "Please fill all required fields.");
        return false;
    }
    
    const dateFilter = $('#date_from').val() ?? null;
    let startDate = '';
    let endDate = '';
    
    if (dateFilter) {
        const dr = $('#date_from').data('daterangepicker');
    
        startDate = dr.startDate.format("YYYY-MM-DD hh:mm A"); 
        endDate   = dr.endDate.format("YYYY-MM-DD hh:mm A");
        // startDate = dr.startDate.format("YYYY-MM-DD HH:mm:ss"); 
        // endDate   = dr.endDate.format("YYYY-MM-DD HH:mm:ss");
    }


    let row = `
        <tr>
            <td>${fields.from_place}</td>
            <td>${fields.to_place}</td>
            <td style="display: none;">${startDate}</td>
            <td style="display: none;">${endDate}</td>
            <td>${fields.distance}</td>
            <td>${fields.duration}</td>
            <td>${$('#fuel_type').val()} / ${fields.mileage}</td>
            <td>${fields.fuel_fare} / ${fields.fuel_ret_fare}</td>
            <td>${fields.toll_fare} / ${fields.toll_fare * 2}</td>
            <td>${fields.driver_one_tot_price}</td>
            <td>${fields.driver_ret_tot_price}</td>
            <td>${fields.driver_one_price}</td>
            <td>${fields.driver_ret_price}</td>
            <td>
                <button class="btn btn-danger btn-sm" onclick="removeRow(this)">
                    Remove
                </button>
            </td>
        </tr>
    `;

    $("#list_tbl").append(row);
    enableLeaveProtection();

    clear_inputs();
}

function submit_schedule(){
    
    let headers = [];
    $("#list_tbl_th th").each(function () {
         let header = $(this).text().trim();

        if (header !== "Action") {
            let formatted = header.toLowerCase().replace(/\s+/g, "_");
            headers.push(formatted);
        }
    });
    // headers.push('from_date');
    // headers.push('to_date');
    
    let j_data = [];
    const dateFilters = $('#date_from').val() ?? null;
    let startDates = '';
    let endDates = '';
    
    if (dateFilters) {
        const dr = $('#date_from').data('daterangepicker');
    
        startDates = dr.startDate.format("YYYY-MM-DD hh:mm A"); 
        endDates   = dr.endDate.format("YYYY-MM-DD hh:mm A");
        // startDate = dr.startDate.format("YYYY-MM-DD HH:mm:ss"); 
        // endDate   = dr.endDate.format("YYYY-MM-DD HH:mm:ss");
    }
    
    $("#list_tbl tr").each(function () {
        let row = {};
        let tds = $(this).find("td");
    
        if (tds.length > 0) { // skip header row
            tds.each(function (i) {
                if (headers[i]) {  // ensures Action column is ignored
                    row[headers[i]] = $(this).text().trim();
                }
            });
            // row[headers[i]] = $(this).text().trim();
            j_data.push(row);
        }
    });
    
    console.log(j_data);
    
    if (!Array.isArray(j_data) || j_data.length === 0) {
        toast('error', "Please fill the schedule table before submitting.");
        return;
    }
    let btn = $("#submit_btn");
    
    if($('#users_id').val() == ''){
        toast('error', "Please select user.");
        return;
    }
    
    $.ajax({
        url: url01,
        type: "POST",
        data: {
            method: "submit_schedule",
            user_id: $('#users_id').val(),
            schedules: j_data
        },
         beforeSend: function () {
            btn.prop("disabled", true).text("Submitting...");
        },
        success: function (data) {
            data = JSON.parse(data);
            
            if(data.status){
                toast('success', data.message);
                $('#list_tbl').empty();
                location.reload();
            }else{
                toast('error', data.message);
                
            }
        },
         complete: function () { 
            // always runs after success or error
            btn.prop("disabled", false).text("Submit");
        },
        error: function (xhr) {
            console.log("Error:", xhr.responseText);
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

</script>