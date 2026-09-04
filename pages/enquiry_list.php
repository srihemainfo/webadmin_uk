<?php
$pageTitle = "Enquiry List";
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

.clickable-bid-cell {
    cursor: pointer;
    color: #3200fa;
    font-weight: 700;
    text-align: center;
}

.clickable-place {
    cursor: pointer;
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
                                <div class="row mt-1">

                                    <!-- Date -->
                                    <div class="col-md-2">
                                        <span>Date</span>
                                        <input class="form-control" type="text" id="datefilterLogin"
                                               name="datefilterLogin" placeholder="Select Date" value="" readonly />
                                    </div>
                                
                                    <div class="col-md-2">
                                        <span>Form type</span>
                                        <select id="enType" class="form-control">
                                            <option value="">All Types</option>
                                            <option value="agent_enquiry">Agency Form</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <span>Form Status</span>
                                        <select id="enStatus" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="0">No reply</option>
                                            <option value="1">Replied</option>
                                        </select>
                                    </div>
                                
                                    <!-- Search Button -->
                                    <div class="col-md-2">
                                        <span class="d-block">&nbsp;</span>
                                        <button class="btn btn-primary" onclick="enquiryList()">Search</button>
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
                                                            <th class="column_sort sorting sorting_asc">Created</th>
                                                            <th class="column_sort sorting sorting_asc">Type</th>
                                                            <th class="column_sort sorting sorting_asc">Name</th>
                                                            <th class="column_sort sorting sorting_asc">Email</th>
                                                            <th class="column_sort sorting sorting_asc">Mobile</th>
                                                            <th class="column_sort sorting sorting_asc">message</th>
                                                            <th class="column_sort sorting sorting_asc">Request Form</th>
                                                            <th class="column_sort sorting sorting_asc">Reply Message</th>
                                                            <th class="column_sort sorting sorting_asc">Action</th>
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

<div class="modal fade" id="bid_modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content modal-content-demo">
         <div class="modal-header">
            <h6 class="modal-title">Bid Details</h6>
            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>SNO</th>
                        <th>Bidder ID</th>
                        <th>Amount</th>
                        <th>Remark</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="bid_tbl">
                    <tr>
                        <td colspan="5" class="text-center">No Bids Found</td>
                    </tr>
                </tbody>
            </table>
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

    let origin = window.location.origin;

    let mainDomain = window.location.hostname.split('.').slice(-2).join('.');

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
    
    const enquiryList = () => {
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
                    url: origin + "/ajax/service/enquiryService.php",
                    method: "POST",
                    dataSrc: "result",
                    data: function (d) {
                        const datePicker = $('#datefilterLogin').data('daterangepicker');
                        const hasDate = $('#datefilterLogin').val()?.trim() !== '';
                    
                        d.method = "enquiryList";
                        d.enType = $("#enType").val();
                        d.enStatus = $("#enStatus").val();
                        d.dateFilter = hasDate ? 1 : '';
                    
                        if (hasDate && datePicker) {
                            d.startDate = datePicker.startDate.format("YYYY-MM-DD");
                            d.endDate = datePicker.endDate.format("YYYY-MM-DD");
                        }
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
                            return data?.created_at || ''
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return data?.type || '';
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
                        render: function(data, type, row, meta) {
                            return data?.email || '';
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
                            return data?.message || '';
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return data?.req_json || '';
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return data?.reply_mess || '';
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return '';
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
    
    $("#btnSearch").on("click", function () {
        $("#Participation_List").DataTable().ajax.reload();
    });
    
    $("#searchTxt").on("keypress", function (e) {
        if (e.which === 13) {
            $("#Participation_List").DataTable().ajax.reload();
        }
    });
    
    $(document).on('click', '.clickable-bid-cell', function () {
        const id = $(this).data('id');
        showBidDetails(id, this);
    });
    
    $(document).on("click", ".cancel-job", function () {
        let jobId = $(this).data("id");
        let userid = $(this).data("userid");
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
            success: function (res) {
                if (res.type === 1) {
                    toast('success', res.msg);
                    $("#Participation_List").DataTable().ajax.reload(null, false);
                } else {
                    toast('error', res.msg);
                }
            },
            error: function () {
                toast('error', 'Something went wrong while cancelling job.');
            }
        });
    });
    
    $(document).on("click", ".delete-job", function () {
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
            success: function (res) {
                if (res.type === 1) {
                    toast('success', res.msg);
                    $("#Participation_List").DataTable().ajax.reload(null, false);
                } else {
                    toast('error', res.msg);
                }
            },
            error: function () {
                toast('error', 'Something went wrong while cancelling job.');
            }
        });
    });

    function showBidDetails(jobId, btn) {
        $(btn).prop('disabled', true);
    
        $.ajax({
            url: origin + "/ajax/service/jobServices.php",
            method: "POST",
            dataType: "json",
            data: {
                method: "bid_details",
                job_id: jobId
            },
            success: function(response) {
                let rows = "";
                if (response.type === 1 && response.result) {
                    let index = 1;
                    for (const [bidderId, bid] of Object.entries(response.result)) {
                        rows += `
                            <tr>
                                <td>${index++}</td>
                                <td>
                                  <a href="https://console.goride.run/kyc-verify/verify/${bid.user_id}/${bid.kd_id}"
                                    target="_blank" class="text-decoration-none text-primary">
                                    ${bid.name}
                                </td>
                                <td>${bid.amount}</td>
                                <td>${bid.remark ? bid.remark : "-"}</td>
                                <td>${bid.status}</td>
                            </tr>
                        `;
                    }
                } else {
                    rows = `<tr><td colspan="5" class="text-center">No Bids Found</td></tr>`;
                }
                $("#bid_tbl").html(rows);
                $("#bid_modal").modal("show");
            },
            error: function(xhr) {
                $("#bid_tbl").html(`<tr><td colspan="5" class="text-center">Error loading bids</td></tr>`);
                $("#bid_modal").modal("show");
            },
            complete: function() {
                $(btn).prop('disabled', false);
            }
        });
    }
    
    $(function () {
        try {
            createDatePicker('datefilterLogin');
            $('#datefilterLogin').trigger('cancel.daterangepicker');
            
            var isNotComplete = getCookie("isNoComplete");
            var isExpired = getCookie("isExpired");
            var isCancelled = getCookie("isCancelled");
    
            if (isNotComplete == "true") {
        
                const start = moment().startOf('year');
                const end = moment().endOf('year');
        
                $("#datefilterLogin").data('daterangepicker').setStartDate(start);
                $("#datefilterLogin").data('daterangepicker').setEndDate(end);
        
                $("#datefilterLogin").val(
                    start.format('DD/MM/YYYY') + " - " + end.format('DD/MM/YYYY')
                );
        
                $("#jobStatus").prop("selectedIndex", 1).trigger("change");
        
            }
            
            if (isExpired == "true") {
        
                const start = moment().startOf('year');
                const end = moment().endOf('year');
        
                $("#datefilterLogin").data('daterangepicker').setStartDate(start);
                $("#datefilterLogin").data('daterangepicker').setEndDate(end);
        
                $("#datefilterLogin").val(
                    start.format('DD/MM/YYYY') + " - " + end.format('DD/MM/YYYY')
                );
        
                $("#jobStatus").prop("selectedIndex", 4).trigger("change");
        
            }
            
            if (isCancelled == "true") {
        
                const start = moment().startOf('year');
                const end = moment().endOf('year');
        
                $("#datefilterLogin").data('daterangepicker').setStartDate(start);
                $("#datefilterLogin").data('daterangepicker').setEndDate(end);
        
                $("#datefilterLogin").val(
                    start.format('DD/MM/YYYY') + " - " + end.format('DD/MM/YYYY')
                );
        
                $("#jobStatus").prop("selectedIndex", 5).trigger("change");
        
            }
            
            
            enquiryList();
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