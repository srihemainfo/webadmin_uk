<?php
$pageTitle = "Schedule Report";
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
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
                                                oninput="this.value = this.value.replace(/[^A-Za-z.0-9 ]/g, ''); if (this.value.length > 3) { unsubscribeList(); }"
                                                maxlength="70" />
                                        </div>
                                        <div class="col-sm-12 col-md-3 col-lg-3">
                                            <span>Select Date (Created At)</span>
                                            <input class="form-control" type="text" id="datefilterLogin"
                                                name="datefilterLogin" placeholder="Select Date" value="" readonly />
                                        </div>
                                        <!--<div class="col-sm-12 col-md-3 col-lg-3">-->
                                        <!--    <span>Select filter</span>-->
                                        <!--</div>-->
                                        <div class="col-sm-12 col-md-6 col-lg-3 mt-4">
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
        
                    <div class="tab-menu-heading border-0 p-0 mb-3">
                      <ul class="nav nav-tabs nav-tabs-line" id="userTabs" role="tablist">
                        
                        <li class="nav-item" role="presentation">
                          <button class="nav-link active" id="pending-tab" data-type="pending" data-bs-toggle="tab"
                            type="button" role="tab">
                            <i class="fa-regular fa-clock me-1"></i> All
                          </button>
                        </li>
        
                      </ul>
                    </div>
        
                    <div class="panel-body tabs-menu-body border-0 pt-0">
                      <div class="table-responsive">
                        <div class="row" id="card-container"></div>
        
                        <table class="table table-bordered text-nowrap border-bottom" id="Participation_List"
                                    style="width:100%;">
                          <thead>
                            <tr>
                              <th>created_at</th>
                              <th>name</th>
                              <th>mobile</th>
                              <th>from place</th>
                              <th>to place</th>
                              <th>oneway price</th>
                              <th>roundtrip price</th>
                              <th>details</th>
                              <th>action</th>
                            </tr>
                          </thead>
                        </table>
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

<div class="modal fade" id="detailsModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Checkout Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered" id="detailsTable">
            <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Edit Fare Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form id="editForm" class="container">

          <input type="hidden" id="edit_id">
          
          <div class="row mb-3">
            <label class="col-md-4 col-form-label">Mileage</label>
            <div class="col-md-8">
              <input type="text" id="mileage" class="form-control">
            </div>
          </div>
          
          <div class="row mb-3">
            <label class="col-md-4 col-form-label">Fuel Amount</label>
            <div class="col-md-8">
              <input type="text" id="fuel_amount" class="form-control">
            </div>
          </div>
          
          <div class="row mb-3">
            <label class="col-md-4 col-form-label">Toll Amount</label>
            <div class="col-md-8">
              <input type="text" id="toll_amount" class="form-control">
            </div>
          </div>
          
          <div class="row mb-3">
            <label class="col-md-4 col-form-label">Oneway Driver Amt</label>
            <div class="col-md-8">
              <input type="text" id="oneway_driver_amt" class="form-control">
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-md-4 col-form-label">Return Driver Amt</label>
            <div class="col-md-8">
              <input type="text" id="return_driver_amt" class="form-control">
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-md-4 col-form-label">Oneway Total</label>
            <div class="col-md-8">
              <input type="text" id="oneway_total" class="form-control">
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-md-4 col-form-label">Return Total</label>
            <div class="col-md-8">
              <input type="text" id="return_total" class="form-control">
            </div>
          </div>




        </form>
      </div>

      <div class="modal-footer">
        <button class="btn btn-primary" id="saveEdit">Save</button>
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

    function num(val) {
      return parseFloat(val) || 0;
  }

  function calculateTotals() {

      // Get input values
      let mileage = num(document.getElementById("mileage").value);
      let fuel_amount = num(document.getElementById("fuel_amount").value);
      let toll_amount = num(document.getElementById("toll_amount").value);
      let oneway_driver_amt = num(document.getElementById("oneway_driver_amt").value);
      let return_driver_amt = num(document.getElementById("return_driver_amt").value);

      // Calculate one-way total
      let oneway_total = oneway_driver_amt + toll_amount + fuel_amount;
      document.getElementById("oneway_total").value = oneway_total.toFixed(2);

      // Calculate return total
      let return_total = return_driver_amt + (toll_amount * 2) + (fuel_amount * 2);
      document.getElementById("return_total").value = return_total.toFixed(2);
  }

  // Add event listeners to all related fields
  ["mileage", "fuel_amount", "toll_amount", "oneway_driver_amt", "return_driver_amt"]
      .forEach(id => {
          document.getElementById(id).addEventListener("input", calculateTotals);
      });

</script>
<script>
let mainDomain = window.location.hostname.split('.').slice(-2).join('.');
$(document).on('click', '.attachment-thumb', function() {
    const fullUrl = $(this).data('full');
    $('#previewImage').attr('src', fullUrl);
    $('#imagePreviewModal').modal('show');
});

$(document).on('click', '.view-details', function () {
    let details = $(this).data('details');

    // Ensure it is parsed JSON
    if (typeof details === 'string') {
        details = JSON.parse(details);
    }

    let html = "";
    Object.keys(details).forEach(key => {
        html += `
            <tr>
                <th>${key.replace(/_/g, " ").toUpperCase()}</th>
                <td>${details[key]}</td>
            </tr>
        `;
    });

    $("#detailsTable tbody").html(html);

    // Show modal
    $("#detailsModal").modal('show');
});


$(document).on('change', '.verifyToggle', function() {
    if ($(this).data('txt') == 'dl' || $(this).data('txt') == 'selfie' || $(this).data('txt') == 'gst' || $(
            this).data('txt') == 'name_board' || $(this).data('txt') == 'aadhar' || $(this).data('txt') ==
        'doc_verify' || $(this).data('txt') == 'vehicle' && ($(this).data('status') == '0' || $(this).data(
            'status') == '1') && $(this).data('id') != '') {
        $.ajax({
            url: origin + "/ajax/service/datatable_services.php",
            type: 'POST',
            dataType: "json",
            data: {
                method: "kycFetchDoc",
                txt: $(this).data('txt'),
                id: $(this).data('id'),
                status: $(this).data('status')
            },
            beforeSend: function() {
                $(this).prop('disabled', true);
            },
            complete: function() {
                $(this).prop('disabled', false);
            },
            success: function(response) {
                let data = response.result;
                if (response.type == 1) {
                    toast('success', data);
                } else {
                    toast('error', data);
                }
                $('#vehicleModal').modal('hide');
                $('#confirmBlockModal').modal('hide');
                unsubscribeList()
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
        if ($(this).data('txt') != 'doc_verify') {
            const label = $(this).closest('.toggle-container').find('.verifyLabel');
            if ($(this).is(':checked')) {
                label.text('Verified').removeClass('text-danger').addClass('text-success');
            } else {
                label.text('Not Verified').removeClass('text-success').addClass('text-danger');
            }
        }
    }
});

$('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
    const target = $(e.target).attr('data-bs-target');
    // if (target === '#pendingTab') {
    //   pendingTable.ajax.reload();
    // } else if (target === '#verifiedTab') {
    //   verifiedTable.ajax.reload();
    // }
    unsubscribeList();
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
            maxDate: today,
            opens: 'left',
            startDate: sevenDaysAgo,
            endDate: today,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                    .endOf('month')
                ],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year')
                    .endOf('year')
                ]
            }
        });
        $(selector).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                'DD/MM/YYYY'));
            // $(`#drawID`).val('');
            // $(`#drawID`).val('').selectpicker('refresh');
        });
        $(selector).on('cancel.daterangepicker', function(ev, picker) {
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
        const btn = $('#searchBTN');
        // const searchTxt = $('#searchTxt').val();
        const dateFilter = $('#datefilterLogin').val() ?? null;
        if (dateFilter) {
            startDate = moment($('#datefilterLogin').data('daterangepicker').startDate).format("YYYY-MM-DD");
            endDate = moment($('#datefilterLogin').data('daterangepicker').endDate).format("YYYY-MM-DD");
        }
        
        if ($.fn.DataTable.isDataTable('#Participation_List')) {
            $('#Participation_List').DataTable().clear().destroy();
        }
        
        // Initialize DataTable (hidden table)
        const table = $('#Participation_List').DataTable({
            destroy: true,
            paging: true,
            searching: true,
            info: false,
            order: [],
            ajax: {
                url: origin + "/ajax/service/schedule_job.php",
                method: "POST",
                dataSrc: "result",
                data: {
                    method: 'get_sch_report',
                    dateFilter,
                    startDate,
                    endDate,
                    u_type: $('.nav-link.active').data('type'),
                    searchTxt: $('#searchTxt').val()
                    // kyc_filter: kyc_filter
                },
                beforeSend: function() {
                    btn.html(`<span class="spinner-border spinner-border-sm"></span>&nbsp;Loading...`)
                        .prop('disabled', true);
                },
                complete: function() {
                    btn.html(`Go`)
                        .prop('disabled', false);
                },
            },
            dom: 'Bfrtip',
            buttons: ['pageLength', 'copy', {
                extend: 'excelHtml5',
                title: 'Go Ride Leads'
            }],
            columns: [
                { data: 'created_at' },
                { data: 'name' },
                { data: 'mobile' },
                { data: 'from_place' },
                { data: 'to_place' },
                { 
                    data: 'oneway_price',
                    render: function (data, type, row) {
                        return JSON.parse(data);
                    }
                },
                { 
                    data: 'roundtrip_price',
                    render: function (data, type, row) {
                        return JSON.parse(data);
                    }
                },
        
                {
                    data: 'checkout_data',
                    render: function (data, type, row) {
                        return `
                            <button class="btn btn-info btn-sm view-details" 
                                data-details='${row.checkout_data}'>
                                View Details
                            </button>
                        `;
                    }
                },
        
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <button class="btn btn-primary btn-sm edit-btn" 
                                    data-id="${row.id}" 
                                    data-details='${row.checkout_data}'>
                                Edit
                            </button>
                
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
                                Delete
                            </button>
                        `;
                    }
                }
            ]
        });
        
    } catch (e) {
        console.log(`Error: ${e.message}`);
    }
};

let blockData = {};

$(document).on('click', '.verify-user-btn, .unverify-user-btn', function() {
    blockData = {
        id: $(this).data('id'),
        user_id: $(this).data('user'),
        job_id: $(this).data('job')
    };
    if ($(this).data('id') !== '' && $(this).data('user') !== '') {
        $.ajax({
            url: origin + "/ajax/service/datatable_services.php",
            type: 'POST',
            dataType: "json",
            data: {
                method: "kycUserDetails",
                id: $(this).data('id'),
                user: $(this).data('user')
            },
            beforeSend: function() {
                console.log('Sending request...');
            },
            success: function(response) {
                // console.log('Success:', response);
                $('#confirmBlockModal').modal('show');
                let data = response.result;
                if (response.type == '1') {
                    $('#userName').val(data.name);
                    $('#userMobile').val(data.mobile);
                    $('#selfieStatus').val(data.mobile);
                } else {
                    toast('error', response.result)
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
    if ($(this).hasClass('verify-user-btn')) {} else if ($(this).hasClass('unverify-user-btn')) {
        $('#confirmUnblockModal').modal('show');
    }
});

$(document).on('click', '.edit-btn', function () {

    let recordId = $(this).data('id');
    let details = $(this).data('details');

    if (typeof details === "string") {
        details = JSON.parse(details);
    }
    
    $("#edit_id").val(recordId);
    $("#oneway_driver_amt").val(details.oneway_driver_amt);
    $("#return_driver_amt").val(details.return_driver_amt);
    $("#oneway_total").val(details.oneway_total);
    $("#return_total").val(details.return_total);

    if (details["fuel_fare_o/r"]) {
        let fuel = details["fuel_fare_o/r"].split("/")[0].trim();
        $("#fuel_amount").val(fuel);
    }

    if (details["toll_fare_o/r"]) {
        let toll = details["toll_fare_o/r"].split("/")[0].trim();
        $("#toll_amount").val(toll);
    }

    if (details["fuel_type_/_mileage"]) {
        let mileage = details["fuel_type_/_mileage"].split("/")[1].trim();
        $("#mileage").val(mileage);
    }

    $("#editModal").modal("show");
});

$(document).on('click', '.delete-btn', function () {
    
     let recordId = $(this).data('id');

    Swal.fire({
        title: "Are you sure?",
        text: "This record will be deleted permanently.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it",
        cancelButtonText: "Cancel",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {

            let payload = {
                method: "delete_sch_report",
                id: recordId
            };

            $.ajax({
                url: origin + "/ajax/service/schedule_job.php",
                type: "POST",
                data: payload,
                dataType: "json",
                success: function (response) {
                    $("#editModal").modal("hide");

                    toast('success', response.message)

                    unsubscribeList();
                },
                error: function (xhr) {
                    toast('error', 'Something went wrong');
                    console.error("Deletion Failed:", xhr.responseText);
                }
            });

        }
    });
});

$("#saveEdit").click(function () {

    let payload = {
        method: "update_sch_report",
        id: $("#edit_id").val(),
        oneway_driver_amt: $("#oneway_driver_amt").val(),
        return_driver_amt: $("#return_driver_amt").val(),
        oneway_total: $("#oneway_total").val(),
        return_total: $("#return_total").val(),
        fuel_amount: $("#fuel_amount").val(),
        toll_amount: $("#toll_amount").val(),
        mileage: $("#mileage").val()
    };

    $.ajax({
        url: origin + "/ajax/service/schedule_job.php",   // ← Change to your URL
        type: "POST",
        data: payload,
        success: function (response) {
            // Hide modal
            $("#editModal").modal("hide");
            
            unsubscribeList();
            // Optional notification
            console.log("Update Success:", response);
        },
        error: function (xhr) {
            console.error("Update Failed:", xhr.responseText);
        }
    });

});



var url01 = origin + "/ajax/service/datatable_services.php";
$(function() {
    
    try {
        createDatePicker('datefilterLogin');
        unsubscribeList()
        $('#datefilterLogin').trigger('cancel.daterangepicker');
        
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