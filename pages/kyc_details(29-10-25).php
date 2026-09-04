<?php
$pageTitle = "KYC Detail";
// error_reporting(E_ALL);
// ini_set('display_errors', 1);


?> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>

    .user-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s;
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
    .user-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
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
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
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

.text-primary-bs {
    color: #007bff !important; /* Primary color */
}

.text-success-bs {
    color: #28a745 !important; /* Success color */
}

.text-warning-bs {
    color: #ffc107 !important; /* Warning color */
}

.text-danger-bs {
    color: #dc3545 !important; /* Danger color */
}

.verify-sticker {
    display: inline-block;
    font-weight: 600;
    font-size: 1rem;
    padding: 8px 20px;
    border-radius: 50px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
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
    box-shadow: 0 3px 6px rgba(0,0,0,0.2);
    transition: all 0.4s ease;
  }

  input:checked + .slider1 {
    background: linear-gradient(145deg, #1dd1a1, #10ac84);
    box-shadow: inset 2px 2px 5px #0e8c69, inset -2px -2px 5px #22e4ba;
  }

  input:checked + .slider1::before {
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
  input:checked + .slider1::before {
    box-shadow: 0 0 10px rgba(16,172,132,0.7);
  }
  
    .vehicle-card {
      transition: all 0.3s ease;
    }
    .vehicle-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .vehicle-details h5 {
      font-size: 1.1rem;
    }
    .vehicle-images img, .remaining-images img {
      transition: all 0.3s ease;
    }
    .vehicle-images img:hover, .remaining-images img:hover {
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
        top: -10px;
        right: -10px;
        width: 15px;
        height: 15px;
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
                                                name="datefilterLogin" placeholder="Select Date" value="" readonly />
                                        </div>

                                        



                                        <div class="col-sm-12 col-md-6 col-lg-4 mt-4">
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
                                                <div class="row" id="card-container"></div>

                                                <!-- Keep table hidden for DataTable logic -->
                                                <table id="Participation_List" class="d-none">
                                                    <thead>
                                                        <tr>
                                                            <th>created_at</th>
                                                            <th>name</th>
                                                            <th>mobile</th>
                                                            <th>type</th>
                                                            <th>selfie_url</th>
                                                            <th>selfie_status</th>
                                                            <th>proof_type</th>
                                                            <th>front_image</th>
                                                            <th>proof_status</th>
                                                            <th>dl_no</th>
                                                            <th>dl_status</th>
                                                            <th>doc_verify</th>
                                                            <th>id</th>
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
            <h6 class="modal-title">Lead Details</h6>
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

<div class="modal fade" id="vehicleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-0 text-dark">
        <h5 class="modal-title fw-bold">Vehicle Details</h5>
        <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close">X</button>
      </div>
      <div class="modal-body bg-light" id="vehicleData">
        <!-- Dynamic data will appear here -->
      </div>
    </div>
  </div>
</div>


<!-- Bootstrap Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="imagePreviewLabel">Image Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
      </div>

      <!-- Body -->
      <div class="modal-body d-flex justify-content-center align-items-center">
        <img id="previewImage" src="" alt="Preview" class="img-fluid shadow" style="max-height:80vh; object-fit:contain;">
      </div>

    </div>
  </div>
</div>


<div class="modal fade" id="confirmBlockModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">

      <div class="modal-header text-dark">
        <h5 class="modal-title">KYC Details</h5>
        <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close">X</button>
      </div>

      <div class="modal-body">
          <div class="mb-3">
            <label for="userName" class="info-label">Name:</label>
            <input type="text" id="userName" class="info-input" placeholder="Enter Name" value="John Doe">
          </div>
        
          <div class="mb-3">
            <label for="userMobile" class="info-label">Mobile:</label>
            <input type="text" id="userMobile" class="info-input" placeholder="Enter Mobile" value="9876543210">
          </div>
        
          <hr>
        
          <div class="toggle-row">
            <span class="toggle-label">Selfie Status</span>
            <label class="toggle-switch">
              <input type="checkbox" id="selfieStatus">
              <span class="toggle-slider"></span>
            </label>
          </div>
        
          <div class="toggle-row">
            <span class="toggle-label">Aadhar Status</span>
            <label class="toggle-switch">
              <input type="checkbox" id="aadharStatus">
              <span class="toggle-slider"></span>
            </label>
          </div>
        
          <div class="toggle-row mb-3">
            <span class="toggle-label">DL Status</span>
            <label class="toggle-switch">
              <input type="checkbox" id="dlStatus">
              <span class="toggle-slider"></span>
            </label>
          </div>
        
          <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-success w-50 me-2" id="verifyBtn">
              <i class="bi bi-check-circle me-1"></i> Verify
            </button>
            <button type="button" class="btn btn-danger w-50" id="notVerifyBtn">
              <i class="bi bi-x-circle me-1"></i> Not Verify
            </button>
          </div>
        </div>

      <!--<div class="modal-footer">-->
      <!--  <button type="button" id="confirmBlockBtn" class="btn btn-danger">Yes, Block</button>-->
      <!--  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>-->
      <!--</div>-->

    </div>
  </div>
</div>

<div class="modal fade" id="confirmUnblockModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">

      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Confirm Unblock</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <p class="mb-0">Are you sure you want to <strong>unblock</strong> this user? They will regain full access to their account immediately.</p>
      </div>

      <div class="modal-footer">
        <button type="button" id="confirmUnblockBtn" class="btn btn-success">Yes, Unblock</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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

// <script>
//     tinymce.init({
//         selector: '#whatsapp_message',
//         menubar: false,
//         plugins: 'lists link',
//         toolbar: 'undo redo | bold italic underline | bullist numlist | link',
//         height: 200
//     });
// </script>
<script>
    let mainDomain = window.location.hostname.split('.').slice(-2).join('.');
    
    $(document).on('click', '.attachment-thumb', function () {
        const fullUrl = $(this).data('full');
        $('#previewImage').attr('src', fullUrl);
        $('#imagePreviewModal').modal('show');
    });
    
    $(document).on('change', '.verifyToggle', function() {
        
        if($(this).data('txt') == 'dl' || $(this).data('txt') == 'selfie' || $(this).data('txt') == 'gst' || $(this).data('txt') == 'name_board' || $(this).data('txt') == 'aadhar' || $(this).data('txt') == 'doc_verify' || $(this).data('txt') == 'vehicle' && ($(this).data('status') == '0' || $(this).data('status') == '1') && $(this).data('id') != ''){
            
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
                    if(response.type == 1){
                        toast('success', data);
                    }else{
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
            
            if($(this).data('txt') != 'doc_verify'){
                const label = $(this).closest('.toggle-container').find('.verifyLabel');
                
                if ($(this).is(':checked')) {
                    label.text('Verified').removeClass('text-danger').addClass('text-success');
                } else {
                    label.text('Not Verified').removeClass('text-success').addClass('text-danger');
                }
            }
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
    // const unsubscribeList = () => {
    //     try {
    //         let startDate = null;
    //         let endDate = null;
    //         var btn = $(`#searchBTN`);
    //         var dateFilter = $('#datefilterLogin').val() ?? null;
    //         if (dateFilter != '' && dateFilter != null && dateFilter != undefined) {
    //             startDate = moment($('#datefilterLogin').data('daterangepicker').startDate).format("YYYY-MM-DD");
    //             endDate = moment($('#datefilterLogin').data('daterangepicker').endDate).format("YYYY-MM-DD");
    //         }
    //         var table = $('#Participation_List').DataTable({
    //             destroy: true,
    //             pageLength: 10,
    //             order: [],
    //             paging: true,
    //             searching: true,
    //             info: true,
    //             ajax: {
    //                 url: origin + "/ajax/service/datatable_services.php",
    //                 method: "POST",
    //                 dataSrc: "result",
    //                 data: {
    //                     method: 'kycDetails',
    //                     dateFilter: dateFilter,
    //                     startDate: startDate,
    //                     endDate: endDate,
    //                     searchTxt: $('#searchTxt').val()
    //                 },
    //                 beforeSend: function () {
    //                     btn.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Loading...`).prop('disabled', true);
    //                 },
    //             },
    //             dom: 'Bfrtip',
    //             buttons: [
    //                 'pageLength',
    //                 'copy',
    //                 {
    //                     extend: 'excelHtml5',
    //                     title: 'Go Ride Leads'
    //                 },
    //             ],
    //             columns: [
    //                 {
    //                     data: null,
    //                     render: function (data) {
    //                         if (!data?.created_at) return '';
                    
    //                         // Convert MySQL datetime (YYYY-MM-DD HH:MM:SS) to JS Date
    //                         const dateObj = new Date(data.created_at.replace(' ', 'T'));
                    
    //                         const datePart = dateObj.toLocaleDateString('en-US', {
    //                             day: '2-digit',
    //                             month: 'short',
    //                             year: 'numeric'
    //                         });
                    
    //                         const timePart = dateObj.toLocaleTimeString('en-US', {
    //                             hour: 'numeric',
    //                             minute: '2-digit',
    //                             hour12: true
    //                         });
                    
    //                         return `${datePart}, ${timePart}`; 
    //                         // Example: "18 Sep 2025, 5:30 PM"
    //                     }
    //                 },
    //                 {
    //                     data: null,
    //                     render: function (data) {
    //                         return data?.name || '';
    //                     }
    //                 },
    //                 {
    //                     data: null,
    //                     render: function (data) {
    //                         return data?.mobile || '';
    //                     }
    //                 },
    //                 {
    //                     data: null,
    //                     render: function (data) {
    //                         return data?.type || '';
    //                     }
    //                 },
    //                 {
    //                     data: null,
    //                     render: function (data) {
    //                         let html = '';
                            
    //                         if(data?.selfie_url){
                                
    //                             let selfie = `
    //                                 <img src="${data?.selfie_url}" 
    //                                      data-full="${data?.selfie_url}" 
    //                                      class="attachment-thumb"
    //                                      style="width:70px; height:50px; object-fit:cover; margin:4px; border-radius:8px; cursor:pointer; transition:transform .2s;"
    //                                      onmouseover="this.style.transform='scale(1.05)'"
    //                                      onmouseout="this.style.transform='scale(1)'">
    //                             `;
                
    //                             html = `
    //                                 <div style="display:flex; flex-direction:row; gap:5px;">
    //                                     <div style="display:flex; gap:5px;">${selfie}</div>
    //                                 </div>
    //                             `;
                                
    //                         }
                            
    //                         return html;
    //                     }
    //                 },
    //                 {
    //                     data: null,
    //                     render: function (data) {
    //                         return data?.selfie_status || '';
    //                     }
    //                 },
    //                 {
    //                     data: null,
    //                     render: function (data) {
    //                         return data?.proof_type || '';
    //                     }
    //                 },
    //                 {
    //                     data: null,
    //                     render: function (data) {
                            
    //                         let html = '';
                    
    //                         if(data?.front_image && data?.back_image){
                                
    //                             let img1 = `
    //                                 <img src="${data?.front_image}" 
    //                                      data-full="${data?.front_image}" 
    //                                      class="attachment-thumb"
    //                                      style="width:70px; height:50px; object-fit:cover; margin:4px; border-radius:8px; cursor:pointer; transition:transform .2s;"
    //                                      onmouseover="this.style.transform='scale(1.05)'"
    //                                      onmouseout="this.style.transform='scale(1)'">
    //                             `;
    //                             let img2 = `
    //                                 <img src="${data?.back_image}" 
    //                                      data-full="${data?.back_image}" 
    //                                      class="attachment-thumb"
    //                                      style="width:70px; height:50px; object-fit:cover; margin:4px; border-radius:8px; cursor:pointer; transition:transform .2s;"
    //                                      onmouseover="this.style.transform='scale(1.05)'"
    //                                      onmouseout="this.style.transform='scale(1)'">
    //                             `;
                                
    //                             html = `
    //                                 <div style="display:flex; flex-direction:row; gap:5px;">
    //                                     <div style="display:flex; gap:5px;">${img1}</div>
    //                                     <div style="display:flex; gap:5px;">${img2}</div>
    //                                 </div>
    //                             `;
    //                         }


        
                    
    //                         return html;
    //                     }
    //                 },
    //                 {
    //                     data: null,
    //                     render: function (data) {
    //                         return data?.proof_status || '';
    //                     }
    //                 },
    //                 {
    //                     data: null,
    //                     render: function (data) {
    //                         return data?.dl_no || '';
    //                     }
    //                 },
    //                 {
    //                     data: null,
    //                     render: function (data) {
    //                         let html = '';
                    
    //                         // try {
    //                         //     let attach = JSON.parse(data?.attachments || '[]');
                    
    //                         //     if (Array.isArray(attach) && attach.length > 0) {
    //                         //         let div1 = '';
    //                         //         let div2 = '';
                    
    //                         //         attach.forEach((url, index) => {
    //                         //             const imgTag = `
    //                         //                 <img src="${url}" 
    //                         //                      data-full="${url}" 
    //                         //                      class="attachment-thumb"
    //                         //                      style="width:70px; height:70px; object-fit:cover; margin:4px; border-radius:8px; cursor:pointer; transition:transform .2s;"
    //                         //                      onmouseover="this.style.transform='scale(1.05)'"
    //                         //                      onmouseout="this.style.transform='scale(1)'">
    //                         //             `;


                    
    //                         //             if (index % 2 === 0) {
    //                         //                 div1 += imgTag;
    //                         //             } else {
    //                         //                 div2 += imgTag;
    //                         //             }
    //                         //         });
                    
    //                         //         html = `
    //                         //             <div style="display:flex; flex-direction:row; gap:5px;">
    //                         //                 <div style="display:flex; gap:5px;">${div1}</div>
    //                         //                 <div style="display:flex; gap:5px;">${div2}</div>
    //                         //             </div>
    //                         //         `;
    //                         //     } else {
    //                         //         html = `<span>No Attachments</span>`;
    //                         //     }
    //                         // } catch (err) {
    //                         //     html = `<span>Error loading</span>`;
    //                         // }
                    
    //                         return data?.dl_status || '';
    //                     }
    //                 },
    //                 {
    //                     data: null,
    //                     render: function (data) {
    //                         return data.doc_verify == 0 ? 'Not Verified' : 'Verified';
    //                     }
    //                 },
    //                 {
    //                     data: null,
    //                     render: function (data) {
                            
    //                         return `
    //                             <button class="btn btn-sm btn-danger verify-user-btn" title="Block"
    //                                 data-id="${data.id}"
    //                                 data-user="${data.user_id}" >
    //                                 <i class="fa-solid fa-user-lock"></i>
    //                             </button>
    //                         `;
    //                     }
    //                 }

    //             ],
    //             initComplete: function () {
    //                 btn.html(`GO`).prop('disabled', false);
    //             }
    //         });

    //     } catch (e) {
    //         console.log(`Error: ${e.message}`);
    //     }
    // }
    
    
    const unsubscribeList = () => {
        try {
            let startDate = null;
            let endDate = null;
            const btn = $('#searchBTN');
            const dateFilter = $('#datefilterLogin').val() ?? null;
    
            if (dateFilter) {
                startDate = moment($('#datefilterLogin').data('daterangepicker').startDate).format("YYYY-MM-DD");
                endDate = moment($('#datefilterLogin').data('daterangepicker').endDate).format("YYYY-MM-DD");
            }
    
            // Initialize DataTable (hidden table)
            const table = $('#Participation_List').DataTable({
                destroy: true,
                paging: true,
                searching: true,
                info: false,
                order: [],
                ajax: {
                    url: origin + "/ajax/service/datatable_services.php",
                    method: "POST",
                    dataSrc: "result",
                    data: {
                        method: 'kycDetails',
                        dateFilter,
                        startDate,
                        endDate,
                        searchTxt: $('#searchTxt').val()
                    },
                    beforeSend: function () {
                        btn.html(`<span class="spinner-border spinner-border-sm"></span>&nbsp;Loading...`)
                            .prop('disabled', true);
                    },
                    complete: function() {
                        btn.html(`Go`)
                            .prop('disabled', false);
                    },
                },
                dom: 'Bfrtip',
                buttons: ['pageLength', 'copy', { extend: 'excelHtml5', title: 'Go Ride Leads' }],
                columns: [
                    { data: 'created_at' },
                    { data: 'name' },
                    { data: 'mobile' },
                    { data: 'type' },
                    { data: 'selfie_url' },
                    { data: 'selfie_status' },
                    { data: 'proof_type' },
                    { data: 'front_image' },
                    { data: 'proof_status' },
                    { data: 'dl_no' },
                    { data: 'dl_status' },
                    { data: 'doc_verify' },
                    { data: 'id' }
                ],
                
                drawCallback: function (settings) {
                    const data = this.api().rows({ page: 'current' }).data();
                    const container = $('#card-container');
                    container.empty();
                
                    if (!data.length) {
                        container.html('<p class="text-center text-muted">No records found.</p>');
                        return;
                    }
                
                    data.each(function (item) {
                        const verifiedBadge = item.doc_verify == 1 
                            ? `<span class="badge bg-success">Verified</span>` 
                            : `<span class="badge bg-secondary">Not Verified</span>`;
                        
                        const created = new Date(item.created_at).toLocaleString('en-US', {
                            day: '2-digit', month: 'short', year: 'numeric',
                            hour: '2-digit', minute: '2-digit', hour12: true
                        });
                
                        const profileImg = item.selfie_url || 'https://via.placeholder.com/150?text=No+Image';
                
                        const card = `
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="user-card rounded-4 p-3 bg-white h-100 d-flex flex-column">
                
                                <!-- Header -->
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="fw-bold mb-1 text-truncate" title="${item.name || 'No Name'}">
                                            ${item.name || 'No Name'}
                                        </h6>
                                        <small class="text-muted">${item.type || 'Free Package'}</small>
                                    </div>
                                    <div class="d-flex gap-4">
                                        <div class="text-end">
                                            ${verifiedBadge}
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input verifyToggle" type="checkbox" role="switch" ${item.doc_verify == 1 ? 'checked' : ''} data-txt="doc_verify" data-id="${item.user_id}" data-status="${item.doc_verify == 1 ? '0' : '1'}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <!-- Profile -->
                                    <div class="text-center mb-3 col-5">
                                        <img src="${profileImg}" class="shadow-sm border border-light" 
                                             style="width:90%;height:120px;object-fit:cover;">
                                    </div>
                    
                                    <!-- Details -->
                                    <div class="mb-3 text-start col-7">
                                        <p class="mb-1"><i class="fa-solid fa-id-card text-primary me-2"></i> ${item.name || 'N/A'}</p>
                                        <p class="mb-1"><i class="fa-solid fa-phone text-success me-2"></i> ${item.mobile || '-'}</p>
                                        <p class="mb-1"><i class="fa-solid fa-sun text-warning me-2"></i> ${item.exp??0} Exp.</p>
                                        <p class="mb-0 text-muted"><i class="fa-solid fa-calendar-days me-2"></i><small>KYC created: ${created}</small></p>
                                    </div>
                                </div>
                
                                <!-- Actions -->
                                <div class="mt-auto pt-3 border-top d-flex justify-content-around">
                                    <!-- Aadhar -->
                                    <div class="action-icon text-center position-relative" title="Aadhar" onclick="getDoc('${item.user_id}', '${item.id}', 'AADHAR', '${item.proof_status}')">
                                        <i class="text-primary-bs fa-solid fa-id-card fs-5"></i>
                                        ${
                                            (item.proof_status == 'approved' || item.proof_status == 'approval_pending') ? '<img src="https://cdn-icons-png.flaticon.com/64/18295/18295118.png" alt="Preview" class="img-fluid doc-verified" style="max-height:80vh; object-fit:contain;">' : '<img src="https://cdn-icons-png.flaticon.com/64/10015/10015336.png" alt="Preview" class="img-fluid doc-verified" style="max-height:80vh; object-fit:contain;">'
                                        }
                                        
                                        <div class="text-primary-bs small">Aadhar</div>
                                    </div>
                                
                                    <!-- Selfie -->
                                    <div class="action-icon text-center position-relative" title="Selfie" onclick="getDoc('${item.selfie_url}', '${item.id}', 'SELFIE', '${item.selfie_status}')">
                                        <i class="text-success-bs fa-solid fa-camera fs-5"></i>
                                        ${
                                            (item.selfie_status == 'approved' || item.selfie_status == 'approval_pending') ? '<img src="https://cdn-icons-png.flaticon.com/64/18295/18295118.png" alt="Preview" class="img-fluid doc-verified" style="max-height:80vh; object-fit:contain;">' : '<img src="https://cdn-icons-png.flaticon.com/64/10015/10015336.png" alt="Preview" class="img-fluid doc-verified" style="max-height:80vh; object-fit:contain;">'
                                        }
                                        <div class="small text-success-bs">Selfie</div>
                                    </div>
                                
                                    <!-- Driving Licence -->
                                    <div class="action-icon text-center position-relative" title="Driving Licence" onclick="getDoc('${item.user_id}', '${item.id}', 'DRIVING_LICENSE', null)">
                                        <i class="text-warning-bs fa-solid fa-id-badge fs-5"></i>
                                        ${
                                            (item.dl_status == 'approved' || item.dl_status == 'approval_pending') ? '<img src="https://cdn-icons-png.flaticon.com/64/18295/18295118.png" alt="Preview" class="img-fluid doc-verified" style="max-height:80vh; object-fit:contain;">' : '<img src="https://cdn-icons-png.flaticon.com/64/10015/10015336.png" alt="Preview" class="img-fluid doc-verified" style="max-height:80vh; object-fit:contain;">'
                                        }
                                        <div class="small text-warning-bs">DL</div>
                                    </div>
                                
                                    <!-- Vehicles -->
                                    <div class="action-icon text-center" title="Vehicles" onclick="getDoc('${item.user_id}', '${item.id}', 'VEHICLE', null)">
                                        <i class="text-danger-bs fa-solid fa-car fs-5"></i>
                                        <div class="small text-danger-bs">Vehicle</div>
                                    </div>
                                    
                                    <!-- Name Board -->
                                    <div class="action-icon text-center" title="Name Board" onclick="getDoc('${item.user_id}', '${item.id}', '${item.o_proof_type == 'name_board' ? 'NAME_BOARD' : 'GST'}')">
                                      
                                        <i class="text-success-bs fa-solid fa-sign-hanging fs-5"></i>
                                      <div class="small fw-semibold text-success-bs">Board/GST</div>
                                    </div>

                                </div>

                
                            </div>
                        </div>
                        `;
                
                        container.append(card);
                    });
                
                    btn.html(`GO`).prop('disabled', false);
                }
                



            });
    
        } catch (e) {
            console.log(`Error: ${e.message}`);
        }
    };

    
    
    let blockData = {}; // to hold row data temporarily

    // When user clicks "Block" in table
    $(document).on('click', '.verify-user-btn, .unverify-user-btn', function () {
        
        
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
                    if(response.type == '1'){
                        $('#userName').val(data.name);
                        $('#userMobile').val(data.mobile);
                        $('#selfieStatus').val(data.mobile);
                    }else{
                        toast('error', response.result)
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

    
        if ($(this).hasClass('verify-user-btn')) {
            
        } else if ($(this).hasClass('unverify-user-btn')) {
            $('#confirmUnblockModal').modal('show');
        }
    });
    
    function getDoc(user_id, id, txt, status){
        
        if (user_id != '' && id != '' && (txt != '' && txt != 'SELFIE') ) {
            $.ajax({
                url: origin + "/ajax/service/datatable_services.php",
                type: 'POST',
                dataType: "json",
                data: {
                    method: "kycUserDetails",
                    id: id,
                    txt: txt,
                    user_id: user_id
                },
                beforeSend: function() {
                    $(this).prop('disabled', true);
                },
                complete: function() {
                    $(this).prop('disabled', false);
                },
                success: function(response) {
                    // console.log('Success:', response);
                    // $('#confirmBlockModal').modal('show');
                    let data = response.result;
                    if(response.type == 1){
                        
                        if(txt == 'DRIVING_LICENSE'){
                            
                            let html = '<div class="container-fluid">';
                            // Correct the typo
                            let details = data.req_response ? JSON.parse(data.req_response): null;
                            
                            if(details){
                                
                                
                                html += `
                                    <div class="row mb-2">
                                        <h4 class="col-12 text-start fw-bold pe-2">Driving Licence Details :</h4>
                                    </div>
                                    </hr>
                                `;
                                        
                                for (let key in details) {
                                    if (details.hasOwnProperty(key)) {
                                        html += `
                                            <div class="row mb-2">
                                                <div class="col-5 text-start fw-bold pe-2">${key}</div>
                                                <div class="col-1 text-center">:</div>
                                                <div class="col-6 text-end">${details[key]}</div>
                                            </div>
                                        `;
                                    }
                                }
                                let isVerify = false;
                                
                                if (data.status && (data.dl_status == 'approved' || data.dl_status == 'Verified')) {
                                    isVerify = true;
                                    
                                    $('#confirmBlockModal .modal-title').html('Kyc Details <span class="badge bg-success">Verified</span>');
                                } else {
                                    $('#confirmBlockModal .modal-title').html('Kyc Details <span class="badge bg-secondary">Not Verified</span>');
                                    
                                }
                                
                                html += `
                                
                                    <div class="modal-body text-center py-4">
                                        <label class="fw-bold fs-6 mb-3 d-block">Current Status:</label>
                                    
                                        <div class="toggle-container d-inline-flex align-items-center">
                                          <span class="status-text me-3 ${isVerify? 'text-success' : 'text-danger'} fw-semibold verifyLabel">${isVerify? 'Verified' : 'Not Verified'}</span>
                                          <label class="premium-toggle">
                                            <input type="checkbox" class="verifyToggle" ${isVerify? 'checked' : ''} data-txt="dl" data-id="${id}" data-status="${isVerify? '0' : '1'}">
                                            <span class="slider1"></span>
                                          </label>
                                        </div>
                                    </div>
                                `;
                                
                                    
                            }else{
                                toast('error', 'DL Not Uploaded');
                            }
                            
                            html += '</div>';
                        
                            // Inject into modal body
                            $('#confirmBlockModal .modal-body').html(html);
                            
                            $('#confirmBlockModal').modal('show');
                        }else if(txt == 'VEHICLE'){
                            showVehicleModal(data);
                        }else if(txt == 'NAME_BOARD' || txt == 'GST'){
                            
                            if(data.o_proof_type == 'name_board'){
                                
                                let html = '<div class="container-fluid">';
            
                                html += `
                                    <div class="row mb-2">
                                        <h4 class="col-12 text-start fw-bold pe-2">Name Board :</h4>
                                    </div>
                                    </hr>
                                `;
                                
                                html += `
                                    <div class="row mb-2">
                                        <img src="${data.o_proof}" alt="Preview" class="img-fluid shadow" style="max-height:80vh; object-fit:contain;">
                                    </div>
                                `;
                                let isVerify = false;
                                
                                if (data.o_proof_status && (data.o_proof_status == 'approved' || data.o_proof_status == 'Verified')) {
                                   isVerify = true; 
                                    $('#confirmBlockModal .modal-title').html('Kyc Details <span class="badge bg-success">Verified</span>');
                                } else {
                                    $('#confirmBlockModal .modal-title').html('Kyc Details <span class="badge bg-secondary">Not Verified</span>');
                                    
                                }
                                
                                html += `
                                    <div class="modal-body text-center py-4">
                                        <label class="fw-bold fs-6 mb-3 d-block">Current Status:</label>
                                    
                                        <div class="toggle-container d-inline-flex align-items-center">
                                          <span class="status-text me-3 ${isVerify? 'text-success' : 'text-danger'} fw-semibold verifyLabel">${isVerify? 'Verified' : 'Not Verified'}</span>
                                          <label class="premium-toggle">
                                            <input type="checkbox" class="verifyToggle" ${isVerify? 'checked' : ''} data-txt="name_board" data-id="${id}" data-status="${isVerify? '0' : '1'}">
                                            <span class="slider1"></span>
                                          </label>
                                        </div>
                                    </div>
                                `;
                                
                                $('#confirmBlockModal .modal-body').html(html);
                                $('#confirmBlockModal').modal('show');
                                
                            }else if(data.o_proof_type == 'gst' && (data.gst_details == null || data.gst_details == '')){
                                
                                let html = `
                                    <div class="container-fluid" id="gst_search">
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <input type="text" class="form-control" id="searchInput" placeholder="Enter GST Number" value="${data.o_proof_no}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <button class="btn btn-primary" id="searchBtn" onclick="checkGST(${id})">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="gst_details">
                                        
                                    </div>
                                `;
                                
                                $('#confirmBlockModal .modal-body').html(html);
                                $('#confirmBlockModal .modal-title').html('Search'); // optional, change modal title
                                $('#confirmBlockModal').modal('show');
                                
                            }else if(data.o_proof_type == 'gst' && data.gst_details){
                                
                                let html = '<div class="container-fluid">';
                            // Correct the typo
                            let details = data.gst_details ? JSON.parse(data.gst_details): null;
                            
                            if(details){
                                
                                
                                html += `
                                    <div class="row mb-2">
                                        <h4 class="col-12 text-start fw-bold pe-2">GST Details :</h4>
                                    </div>
                                    </hr>
                                `;
                                        
                                for (let key in details) {
                                    if (details.hasOwnProperty(key)) {
                                        html += `
                                            <div class="row mb-2">
                                                <div class="col-5 text-start fw-bold pe-2">${key}</div>
                                                <div class="col-1 text-center">:</div>
                                                <div class="col-6 text-end">${details[key]}</div>
                                            </div>
                                        `;
                                    }
                                }
                                let isVerify = false;
                                
                                if (data.status && (data.o_proof_status == 'approved' || data.o_proof_status == 'Verified')) {
                                    isVerify = true;
                                    
                                    $('#confirmBlockModal .modal-title').html('Kyc Details <span class="badge bg-success">Verified</span>');
                                } else {
                                    $('#confirmBlockModal .modal-title').html('Kyc Details <span class="badge bg-secondary">Not Verified</span>');
                                    
                                }
                                
                                html += `
                                
                                    <div class="modal-body text-center py-4">
                                        <label class="fw-bold fs-6 mb-3 d-block">Current Status:</label>
                                    
                                        <div class="toggle-container d-inline-flex align-items-center">
                                          <span class="status-text me-3 ${isVerify? 'text-success' : 'text-danger'} fw-semibold verifyLabel">${isVerify? 'Verified' : 'Not Verified'}</span>
                                          <label class="premium-toggle">
                                            <input type="checkbox" class="verifyToggle" ${isVerify? 'checked' : ''} data-txt="gst" data-id="${id}" data-status="${isVerify? '0' : '1'}">
                                            <span class="slider1"></span>
                                          </label>
                                        </div>
                                    </div>
                                `;
                                
                                    
                            }else{
                                toast('error', 'GST Not Uploaded');
                            }
                            
                            html += '</div>';
                        
                            // Inject into modal body
                            $('#confirmBlockModal .modal-body').html(html);
                            
                            $('#confirmBlockModal').modal('show');
                            }
                        }else if(txt == 'AADHAR'){
                            
                            let html = '<div class="container-fluid">';
            
                            html += `
                                <div class="row mb-2">
                                    <h4 class="col-12 text-start fw-bold pe-2">Aadhar Image :</h4>
                                </div>
                                </hr>
                            `;
                            
                            let isVerify = false;
                            
                            if (data.proof_status && (data.proof_status == 'approved' || data.proof_status == 'Verified')) {
                               isVerify = true; 
                                $('#confirmBlockModal .modal-title').html('Kyc Details <span class="badge bg-success">Verified</span>');
                            } else {
                                $('#confirmBlockModal .modal-title').html('Kyc Details <span class="badge bg-secondary">Not Verified</span>');
                                
                            }
                            
                            if(data.proof_type != 'AADHAR_DIGILOCKER'){
                                html += `
                                    <div class="row mb-2 d-flex gap-3">
                                        <img src="${data.front_image}" alt="Preview" class="img-fluid shadow" style="max-height:80vh; object-fit:contain;">
                                        <img src="${data.back_image}" alt="Preview" class="img-fluid shadow" style="max-height:80vh; object-fit:contain;">
                                    </div>
                                `;
                                
                                html += `
                                                    
                                    <div class="modal-body text-center py-4">
                                        <label class="fw-bold fs-6 mb-3 d-block">Current Status:</label>
                                    
                                        <div class="toggle-container d-inline-flex align-items-center">
                                          <span class="status-text me-3 ${isVerify? 'text-success' : 'text-danger'} fw-semibold verifyLabel">${isVerify? 'Verified' : 'Not Verified'}</span>
                                          <label class="premium-toggle">
                                            <input type="checkbox" class="verifyToggle" ${isVerify? 'checked' : ''} data-txt="aadhar" data-id="${id}" data-status="${isVerify? '0' : '1'}">
                                            <span class="slider1"></span>
                                          </label>
                                        </div>
                                    </div>
                                `;
                                
                                $('#confirmBlockModal .modal-body').html(html);
                                $('#confirmBlockModal').modal('show');
                                  
                            }else{
                                html += `
                                    <div class="row mb-2 d-flex gap-3">
                                        <img src="https://console.goride.run/assets/images/digiverify.png" alt="Preview" class="img-fluid shadow" style="max-height:80vh; object-fit:contain;">
                                    </div>
                                `;
                            }
                            
                            $('#confirmBlockModal .modal-body').html(html);
                            
                            $('#confirmBlockModal').modal('show');
                            
                        }
                        
                    }else{
                        toast('error', response.result);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }else if(user_id != '' && id != '' && (txt != '' && txt == 'SELFIE') ){
            
            let html = '<div class="container-fluid">';
            
            html += `
                <div class="row mb-2">
                    <h4 class="col-12 text-start fw-bold pe-2">Selfie Image :</h4>
                </div>
                </hr>
            `;
            
            html += `
                <div class="row mb-2">
                    <img src="${user_id}" alt="Preview" class="img-fluid shadow" style="max-height:80vh; object-fit:contain;">
                </div>
            `;
            let isVerify = false;
            
            if (status && (status == 'approved' || status == 'Verified')) {
               isVerify = true; 
                $('#confirmBlockModal .modal-title').html('Kyc Details <span class="badge bg-success">Verified</span>');
            } else {
                $('#confirmBlockModal .modal-title').html('Kyc Details <span class="badge bg-secondary">Not Verified</span>');
                
            }
            
            html += `
                                
                <div class="modal-body text-center py-4">
                    <label class="fw-bold fs-6 mb-3 d-block">Current Status:</label>
                
                    <div class="toggle-container d-inline-flex align-items-center">
                      <span class="status-text me-3 ${isVerify? 'text-success' : 'text-danger'} fw-semibold verifyLabel">${isVerify? 'Verified' : 'Not Verified'}</span>
                      <label class="premium-toggle">
                        <input type="checkbox" class="verifyToggle" ${isVerify? 'checked' : ''} data-txt="selfie" data-id="${id}" data-status="${isVerify? '0' : '1'}">
                        <span class="slider1"></span>
                      </label>
                    </div>
                </div>
            `;
            
            $('#confirmBlockModal .modal-body').html(html);
            $('#confirmBlockModal').modal('show');
        }
    }
    
    function checkGST(id){
        if(id != ''){
            
            let $btn = $('#searchBtn');
            
            $.ajax({
                url: origin + "/ajax/service/GSTBotService.php",
                type: 'POST',
                dataType: "json",
                data: {
                    method: "gst_details",
                    id: id
                },
                beforeSend: function() {
                    // Disable button and show loading text
                    $btn.prop('disabled', true).html('Searching...');
                },
                complete: function() {
                    // Re-enable button and restore text
                    $btn.prop('disabled', false).html('Search');
                },
                success: function(response) {
                    let data = response.result;
                    let data2 = response.data;
                    let html = '';
                    
                    if (response.type == 1) {
                    
                        // Use data directly, no JSON.parse
                        let details = data || null;
                    
                        if (details) {
                    
                            html += `
                                <div class="row mb-2">
                                    <h4 class="col-12 text-start fw-bold pe-2">GST Details :</h4>
                                </div>
                                <hr>
                            `;
                    
                            for (let key in details) {
                                if (details.hasOwnProperty(key)) {
                                    html += `
                                        <div class="row mb-2">
                                            <div class="col-5 text-start fw-bold pe-2">${key}</div>
                                            <div class="col-1 text-center">:</div>
                                            <div class="col-6 text-end">${details[key]}</div>
                                        </div>
                                    `;
                                }
                            }
                    
                            // Status toggle logic (optional)
                            let isVerify = false;
                            if (data2.status && (data2.status === 'approved' || data2.status === 'Verified')) {
                                isVerify = true;
                                $('#confirmBlockModal .modal-title').html('Kyc Details <span class="badge bg-success">Verified</span>');
                            } else {
                                $('#confirmBlockModal .modal-title').html('Kyc Details <span class="badge bg-secondary">Not Verified</span>');
                            }
                    
                            html += `
                                <div class="modal-body text-center py-4">
                                    <label class="fw-bold fs-6 mb-3 d-block">Current Status:</label>
                                    <div class="toggle-container d-inline-flex align-items-center">
                                      <span class="status-text me-3 ${isVerify ? 'text-success' : 'text-danger'} fw-semibold verifyLabel">
                                        ${isVerify ? 'Verified' : 'Not Verified'}
                                      </span>
                                      <label class="premium-toggle">
                                        <input type="checkbox" class="verifyToggle" ${isVerify ? 'checked' : ''} data-txt="gst" data-id="${id}" data-status="${isVerify ? '0' : '1'}">
                                        <span class="slider1"></span>
                                      </label>
                                    </div>
                                </div>
                            `;
                        }
                    
                    } else {
                        // Handle other types
                    }
                    
                    $('#gst_search').remove();
                    $('#gst_details').html(html);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
            
        }
    }
    
    // function showVehicleModal(data) {
    //       let html = '';
    //       let vehicle_details = data.vehicle_details ? JSON.parse(data.vehicle_details, true):[];
          
    //       vehicle_details.forEach((item, index) => {
    //         html += `
    //           <div class="vehicle-card bg-white rounded-4 shadow-sm mb-4 overflow-hidden">
    //             <div class="row g-0 align-items-center">
    //               <div class="col-md-5 text-center bg-light p-3">
    //                 <div class="car-gallery d-flex flex-wrap justify-content-center gap-2">
    //                   ${item.car.map(url => `
    //                     <div class="img-wrap border rounded-3 p-1 bg-white">
    //                       <img src="${url}" class="img-fluid rounded-3 car-img" style="max-width: 150px; height: auto;">
    //                     </div>
    //                   `).join('')}
    //                 </div>
    //               </div>
    //               <div class="col-md-7 p-4">
    //                 <h5 class="fw-bold text-dark mb-3">Vehicle Seater: 
    //                   <span class="badge bg-primary bg-gradient px-3 py-2 fs-6">${item.type}</span>
    //                 </h5>
    //                 <div class="details-list">
    //                   <p class="mb-2"><strong>RC Document:</strong> ${item.rc}
                        
    //                   </p>
                      
    //                 </div>
    //               </div>
    //             </div>
    //           </div>
    //         `;
    //       });
          
    //     //   <p class="mb-2"><strong>FC Document:</strong> 
    //     //     <a href="${item.fc}" target="_blank" class="text-decoration-none text-primary fw-semibold">View</a>
    //     //   </p>
    //     //   <p class="mb-0"><strong>Insurance:</strong> 
    //     //     <a href="${item.insurence}" target="_blank" class="text-decoration-none text-primary fw-semibold">View</a>
    //     //   </p>
        
    //       $('#confirmBlockModal .modal-body').html(html);
    //     //   $('#vehicleModal').modal('show');
    //     }
    function showVehicleModal(data) {
      let html = '';
      
      let vehicle_details = data.vehicle_details ? JSON.parse(data.vehicle_details, true):[];
      
      vehicle_details.forEach((item, index) => {
        let images = item.car;
        let firstTwo = images.slice(0, 2);
        let remaining = images.slice(2);
        
        html += `
          <div class="vehicle-card bg-white rounded-4 shadow-sm mb-4 p-3">
            <div class="d-flex align-items-start gap-3">
              <div class="vehicle-images d-flex flex-column gap-2">
                ${firstTwo.map(url => `<img src="${url}" class="rounded-3 img-fluid" style="width:120px;height:auto;">`).join('')}
              </div>
              <div class="vehicle-details flex-grow-1">
                <h5 class="fw-bold mb-2">Seater: <span class="badge bg-secondary">${item.type}</span></h5>
                <p class="mb-2">RC No: ${item.rc}</p>
                <div class="remaining-images d-flex gap-2 mt-2 flex-wrap" style="display:none;">
                  ${remaining.map(url => `<img src="${url}" class="rounded-3 img-fluid" style="width:100px;height:auto;">`).join('')}
                </div>
                
              </div>
            </div>
          </div>
        `;
      });
      
      html += `
                                
        <div class="modal-body text-center py-4">
            <label class="fw-bold fs-6 mb-3 d-block">Current Status:</label>
        
            <div class="toggle-container d-inline-flex align-items-center">
              <span class="status-text me-3 ${data.vehicle_verify == 1 ? 'text-success' : 'text-danger'} fw-semibold verifyLabel">${data.vehicle_verify == 1 ? 'Verified' : 'Not Verified'}</span>
              <label class="premium-toggle">
                <input type="checkbox" class="verifyToggle" ${data.vehicle_verify == 1 ? 'checked' : ''} data-txt="vehicle" data-id="${data.id}" data-status="${data.vehicle_verify == 1 ? '0' : '1'}">
                <span class="slider1"></span>
              </label>
            </div>
        </div>
    `;
      
        $('#vehicleData').html(html);

      // Show modal
      $('#vehicleModal').modal('show');
    
      // View More button toggle
    //   $('.view-more-btn').on('click', function(){
    //     $(this).siblings('.remaining-images').slideToggle();
    //     $(this).text($(this).text() === 'View More' ? 'View Less' : 'View More');
    //   });
    }
    
    // When user confirms in modal
    $('#confirmBlockBtn').on('click', function () {
        let $btn = $(this);
        // $('#confirmBlockModal').modal('hide');
    
        $.ajax({
            url: origin + "/ajax/service/datatable_services.php",
            method: "POST",
            dataType: "json",
            data: {
                method: "reportBlock",
                id: blockData.id,
                status: 'block',
                user_id: blockData.user_id
            },
            beforeSend: function () {
                $btn.prop('disabled', true).html('Processing...'); // disable + change text
            },
            success: function (res) {
                if (res.type == '1') {
                    toast('success', response.message);
                    location.reload();
                    // $('#Participation_List').DataTable().ajax.reload(null, false);
                } else {
                    toast('error', response.message);
                }
            },
            error: function () {
                toast('error', "⚠️ Something went wrong. Try again.");
            },
            complete: function () {
                $btn.prop('disabled', false).html('Yes, Block'); // re-enable + reset text
            }
        });
    });
    
    $('#confirmUnblockBtn').on('click', function () {
        let $btn = $(this);
        // $('#confirmBlockModal').modal('hide');
    
        $.ajax({
            url: origin + "/ajax/service/datatable_services.php",
            method: "POST",
            dataType: "json",
            data: {
                method: "reportBlock",
                id: blockData.id,
                status: 'unblock',
                user_id: blockData.user_id
            },
            beforeSend: function () {
                $btn.prop('disabled', true).html('Processing...'); // disable + change text
            },
            success: function (res) {
                if (res.type == '1') {
                    toast('success', response.result);
                    location.reload();
                    // $('#Participation_List').DataTable().ajax.reload(null, false);
                } else {
                    toast('error', response.result);
                }
            },
            error: function () {
                toast('error', "⚠️ Something went wrong. Try again.");
            },
            complete: function () {
                $btn.prop('disabled', false).html('Yes, Unblock'); // re-enable + reset text
            }
        });
    });
    

    function comment_form(id, btn){
        $('#com_comment').val('');
        $('#com_message').val('');
        $('#com_dateInput').val('');
        $('#lead_id').val(id);
        if(id != ''){
            $(btn).prop('disabled', true);
              
            $.ajax({
                url: origin + "/ajax/service/topSpendersServices.php",
                method: "POST",
                // contentType: "application/json",
                dataType: "json",
                data: {
                    method: 'lead_details',
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
                    // response = JSON.parse(response);
                    // toast('success', response.result);
                    // $('#comment_form').modal('hide');
                    // unsubscribeList()
                    // Optional: show error message
                },
                complete: function() {
                    $(btn).prop('disabled', false);
                }
            });
        }
        $('#comment_form').modal('show');
    }
    
    function openWhatsApp(id, btn, ph){
        // quill.setText("");
        $('#what_id').val(id);
        $('#lead_phone').val(ph);
        $('#whatsapp_form').modal('show');
    }
    
    function sendWhatsApp(btn){
        let what_id = $('#what_id').val();
        let ph = $('#lead_phone').val();
        let template_id = $('#waTemplateID').val();
        
        if(what_id != '' && ph != '' && template_id != ''){
            $(btn).html('Sending...').prop('disabled', true);
            let ins = "<?= WHATSAPP_API_INS ?>";
            let api_key = "<?= WHATSAPP_API_KEY ?>";
            let whats_url = "<?= WHATSAPP_SERVER ?>";
    
            // First fetch template body from backend
            $.ajax({
                url: origin + "/ajax/service/salse_marketing_services.php",
                method: "POST",
                data: { method: 'get_single_template',id: template_id },
                success: function(res){
                    try {
                        let data = JSON.parse(res);
                        if(data.success){
                            let message = data.data.body;
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
        
        $.ajax({
            url: origin + "/ajax/service/topSpendersServices.php",
            method: "POST",
            // contentType: "application/json",
            dataType: "json",
            data: {
                method: 'lead_comment',
                com_comment: com_comment,
                com_message: com_message,
                com_dateInput: com_dateInput,
                lead_id: lead_id
            },
            success: function(response) {
                // console.log(response.result)
                // response = JSON.parse(response);
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