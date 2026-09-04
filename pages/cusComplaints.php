<?php
$pageTitle = "Customer Complaints";
// error_reporting(E_ALL);
// ini_set('display_errors', 1);


?> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
                                                <table class="table table-bordered text-nowrap border-bottom"
                                                    id="Participation_List" style="width:100%;">
                                                    <thead>
                                                        <tr>
                                                            <th class="column_sort sorting sorting_asc">created at</th>
                                                            <th class="column_sort sorting sorting_asc">Reporter</th>
                                                            <th class="column_sort sorting sorting_asc">Reported</th>
                                                            <th class="column_sort sorting sorting_asc">Reason</th>
                                                            <th class="column_sort sorting sorting_asc">Message</th>
                                                            <th class="column_sort sorting sorting_asc">Attachment</th>
                                                            <th class="column_sort sorting sorting_asc">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                    <!-- <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </tfoot> -->
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

      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Confirm Block</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <p class="mb-0">Are you sure you want to block this user? They will not be able to access their account for the next 7 days.</p>
      </div>

      <div class="modal-footer">
        <button type="button" id="confirmBlockBtn" class="btn btn-danger">Yes, Block</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>

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
                    url: origin + "/ajax/service/datatable_services.php",
                    method: "POST",
                    dataSrc: "result",
                    data: {
                        method: 'customerComplaint',
                        dateFilter: dateFilter,
                        startDate: startDate,
                        endDate: endDate,
                        searchTxt: $('#searchTxt').val()
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
                        render: function (data) {
                            if (!data?.created_at) return '';
                    
                            // Convert MySQL datetime (YYYY-MM-DD HH:MM:SS) to JS Date
                            const dateObj = new Date(data.created_at.replace(' ', 'T'));
                    
                            const datePart = dateObj.toLocaleDateString('en-US', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric'
                            });
                    
                            const timePart = dateObj.toLocaleTimeString('en-US', {
                                hour: 'numeric',
                                minute: '2-digit',
                                hour12: true
                            });
                    
                            return `${datePart}, ${timePart}`; 
                            // Example: "18 Sep 2025, 5:30 PM"
                        }
                    },
                    {
                        data: null,
                        render: function (data) {
                            return data?.reporter_name || '';
                        }
                    },
                    {
                        data: null,
                        render: function (data) {
                            return data?.reported_name || '';
                        }
                    },
                    {
                        data: null,
                        render: function (data) {
                            return data?.reason || '';
                        }
                    },
                    {
                        data: null,
                        render: function (data) {
                            return data?.message || '';
                        }
                    },
                    {
                        data: null,
                        render: function (data) {
                            let html = '';
                    
                            try {
                                let attach = JSON.parse(data?.attachments || '[]');
                    
                                if (Array.isArray(attach) && attach.length > 0) {
                                    let div1 = '';
                                    let div2 = '';
                    
                                    attach.forEach((url, index) => {
                                        const imgTag = `
                                            <img src="${url}" 
                                                 data-full="${url}" 
                                                 class="attachment-thumb"
                                                 style="width:70px; height:70px; object-fit:cover; margin:4px; border-radius:8px; cursor:pointer; transition:transform .2s;"
                                                 onmouseover="this.style.transform='scale(1.05)'"
                                                 onmouseout="this.style.transform='scale(1)'">
                                        `;


                    
                                        if (index % 2 === 0) {
                                            div1 += imgTag;
                                        } else {
                                            div2 += imgTag;
                                        }
                                    });
                    
                                    html = `
                                        <div style="display:flex; flex-direction:row; gap:5px;">
                                            <div style="display:flex; gap:5px;">${div1}</div>
                                            <div style="display:flex; gap:5px;">${div2}</div>
                                        </div>
                                    `;
                                } else {
                                    html = `<span>No Attachments</span>`;
                                }
                            } catch (err) {
                                html = `<span>Error loading</span>`;
                            }
                    
                            return html;
                        }
                    },
                    {
                        data: null,
                        render: function (data) {
                            if(data.status == 'pending'){
                                return `
                                    <button class="btn btn-sm btn-danger block-user-btn" title="Block"
                                        data-id="${data.id}"
                                        data-user="${data.user_id}"
                                        data-job="35">
                                        <i class="fa-solid fa-user-lock"></i>
                                    </button>
                                `;
                                
                            }else if(data.status == 'blocked'){
                                
                                return `
                                    <button class="btn btn-sm btn-success unblock-user-btn" title="UnBlock"
                                        data-id="${data.id}"
                                        data-user="${data.user_id}"
                                        data-job="35">
                                        <i class="fa-solid fa-user-check"></i>
                                    </button>
                                `;
                            }else{
                                return `
                                    <button class="btn btn-sm btn-dark user-btn"
                                        Un Blocked
                                    </button>
                                `; 
                            }
                        }
                    }

                ],
                initComplete: function () {
                    btn.html(`GO`).prop('disabled', false);
                }
            });

        } catch (e) {
            console.log(`Error: ${e.message}`);
        }
    }
    
    
    let blockData = {}; // to hold row data temporarily

    // When user clicks "Block" in table
    $(document).on('click', '.block-user-btn, .unblock-user-btn', function () {
        
        blockData = {
            id: $(this).data('id'),
            user_id: $(this).data('user'),
            job_id: $(this).data('job')
        };
    
        if ($(this).hasClass('block-user-btn')) {
            $('#confirmBlockModal').modal('show');
        } else if ($(this).hasClass('unblock-user-btn')) {
            $('#confirmUnblockModal').modal('show');
        }
    });

    
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