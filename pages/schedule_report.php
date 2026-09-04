<?php
$pageTitle = "Schedule Report";
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    /* SOLID, NO ROUNDED CORNERS, NO BADGES */
    .card, .btn, .form-control, .modal-content, .nav-tabs .nav-link, .input-group-text {
        border-radius: 0 !important;
    }

    .form-control, select {
        border: 1px solid #ced4da;
        box-shadow: none !important;
    }

    .form-control:focus {
        border-color: #0d6efd;
    }

    /* Table Styling */
    .table-custom-border th {
        font-size: 13px;
        font-weight: 700;
        color: #495057;
        text-transform: uppercase;
        vertical-align: middle;
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    .table-custom-border td {
        font-size: 14px;
        color: #212529;
        vertical-align: middle;
    }

    /* Solid Button Colors based on screenshots */
    .btn-edit { background-color: #0dcaf0; color: #fff; border: none; font-weight: 500; }
    .btn-edit:hover { background-color: #0bacce; color: #fff; }
    
    .btn-delete { background-color: #dc3545; color: #fff; border: none; font-weight: 500; }
    .btn-delete:hover { background-color: #bb2d3b; color: #fff; }

    .btn-cancel { background-color: #0dcaf0; color: #fff; border: none; font-weight: 600; padding: 8px 20px; }
    .btn-cancel:hover { background-color: #0bacce; color: #fff; }

    .btn-save { background-color: #20c997; color: #fff; border: none; font-weight: 600; padding: 8px 20px; }
    .btn-save:hover { background-color: #1ba87e; color: #fff; }

    /* SweetAlert Override */
    div:where(.swal2-container) {
        z-index: 1060000 !important;
    }
</style>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <input type="hidden" id="tabID" value="agents">
        <div class="main-container container-fluid mt-5 p-0">
            
            <div class="page-header pt-5">
                <h1 class="page-title">
                    <a href="javascript:void(0)" class="btn btn-light btn-sm border me-2" onclick="history.go(-1)">
                        <i class="fa fa-chevron-left"></i>
                    </a>
                    <?= $pageTitle; ?>
                </h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= $pageTitle; ?></li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-end">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <label class="form-label text-muted small fw-bold">Search (Name / Mobile)</label>
                                    <input class="form-control" type="text" id="searchTxt" placeholder="Enter Name or Mobile..." oninput="this.value = this.value.replace(/[^A-Za-z0-9 ]/g, ''); if(this.value.length > 2) { unsubscribeList(); }" maxlength="50" />
                                </div>
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <label class="form-label text-muted small fw-bold">Select Date (Created At)</label>
                                    <input class="form-control" type="text" id="datefilterLogin" placeholder="Select Date" readonly />
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-edit w-100" id="searchBTN" onclick="unsubscribeList();">Go</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row row-sm mt-3">
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-custom-border text-nowrap w-100" id="Participation_List">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>From Place</th>
                                            <th>To Place</th>
                                            <th>Scheduled Dates | Prices</th>
                                            <th class="text-center">Action</th>
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

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-4 bg-white">
                <form id="editForm">
                    <input type="hidden" id="edit_id">
                    <div class="table-responsive border">
                        <table class="table table-bordered mb-0" id="editScheduleTable">
                            <thead class="table-light text-uppercase text-muted" style="font-size: 12px; font-weight: 700;">
                                <tr>
                                    <th width="20%">From Place</th>
                                    <th width="20%">To Place</th>
                                    <th width="25%">New Pickup Date & Time</th>
                                    <th width="20%">Price (₹)</th>
                                    <th width="15%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 bg-light p-3">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-save" id="saveEdit"><i class="fa fa-save me-1"></i> Save Schedules</button>
            </div>
        </div>
    </div>
</div>

<script>
window.onload = function() {
    let anchor = document.getElementById("anchor");
    if (anchor) anchor.href = window.location.origin;
}

// DataTables Initialization
const unsubscribeList = () => {
    try {
        let startDate = null;
        let endDate = null;
        const btn = $('#searchBTN');
        const dateFilter = $('#datefilterLogin').val();
        
        if (dateFilter) {
            startDate = moment($('#datefilterLogin').data('daterangepicker').startDate).format("YYYY-MM-DD");
            endDate = moment($('#datefilterLogin').data('daterangepicker').endDate).format("YYYY-MM-DD");
        }
        
        if ($.fn.DataTable.isDataTable('#Participation_List')) {
            $('#Participation_List').DataTable().clear().destroy();
        }
        
        $('#Participation_List').DataTable({
            destroy: true,
            paging: true,
            searching: false, // Handled via API
            info: true,
            order: [], 
            ajax: {
                url: window.location.origin + "/ajax/service/schedule_job.php",
                method: "POST",
                dataSrc: "result",
                data: {
                    method: 'get_sch_report',
                    dateFilter: dateFilter ? 1 : '',
                    startDate: startDate,
                    endDate: endDate,
                    searchTxt: $('#searchTxt').val().trim()
                },
                beforeSend: function() {
                    btn.html(`<i class="fa fa-spinner fa-spin"></i>`).prop('disabled', true);
                },
                complete: function() {
                    btn.html(`Go`).prop('disabled', false);
                },
            },
            columns: [
                { data: 'name', defaultContent: '-' },
                { data: 'mobile', defaultContent: '-' },
                { data: 'from_place', defaultContent: '-' },
                { data: 'to_place', defaultContent: '-' },
                { 
                    data: 'dates_price_parsed', 
                    render: function (data, type, row) {
                        if (!data) return '-';
                        try {
                            let parsed = typeof data === 'string' ? JSON.parse(data) : data;
                            if (typeof parsed === 'string') parsed = JSON.parse(parsed);

                            let html = '<ul class="list-unstyled mb-0" style="margin: 0; padding: 0; font-size: 14px;">';
                            for (let [dt, price] of Object.entries(parsed)) {
                                let formattedDate = moment(dt).format('DD-MM-YYYY hh:mm A');
                                // Using normal text without badges/colors as requested
                                html += `<li class="mb-1">${formattedDate} - ₹${price}</li>`;
                            }
                            html += '</ul>';
                            return html;
                        } catch (e) {
                            return 'Invalid Format';
                        }
                    }
                },
                {
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function (data, type, row) {
                        let safeData = row.dates_price_parsed ? encodeURIComponent(typeof row.dates_price_parsed === 'string' ? row.dates_price_parsed : JSON.stringify(row.dates_price_parsed)) : '';
                        return `
                            <div class="d-flex gap-2 justify-content-center">
                                <button class="btn btn-edit btn-sm edit-btn px-3" 
                                        data-id="${row.id}" 
                                        data-from="${row.from_place}"
                                        data-to="${row.to_place}"
                                        data-prices="${safeData}">
                                    Edit
                                </button>
                                <button class="btn btn-delete btn-sm delete-btn px-3" data-id="${row.id}">
                                    Delete
                                </button>
                            </div>
                        `;
                    }
                }
            ]
        });
    } catch (e) {
        console.log(`Error: ${e.message}`);
    }
};

const createDatePicker = (id) => {
    try {
        const selector = `#${id}`;
        $(selector).daterangepicker({
            autoUpdateInput: false,
            locale: { cancelLabel: 'Clear', format: 'DD/MM/YYYY' },
            maxDate: moment(),
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')]
            }
        });
        $(selector).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            unsubscribeList();
        });
        $(selector).on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            unsubscribeList();
        });
    } catch (e) {}
}

// OPEN EDIT MODAL
$(document).on('click', '.edit-btn', function () {
    let recordId = $(this).data('id');
    let fromPlace = $(this).data('from');
    let toPlace = $(this).data('to');
    let encodedPrices = $(this).data('prices');
    
    $("#edit_id").val(recordId);
    let tbody = $("#editScheduleTable tbody");
    tbody.empty();

    let prices = {};
    if (encodedPrices) {
        try {
            let decoded = decodeURIComponent(encodedPrices);
            prices = JSON.parse(decoded);
            if (typeof prices === "string") prices = JSON.parse(prices);
        } catch (e) {}
    }

    if (Object.keys(prices).length > 0) {
        for (let [dt, price] of Object.entries(prices)) {
            let dateVal = dt.replace(' ', 'T').substring(0, 16);
            let row = `
                <tr>
                    <td><input type="text" class="form-control edit-from fw-bold text-dark" value="${fromPlace}"></td>
                    <td><input type="text" class="form-control edit-to fw-bold text-dark" value="${toPlace}"></td>
                    <td><input type="datetime-local" class="form-control edit-date text-dark" value="${dateVal}"></td>
                    <td><input type="number" class="form-control edit-price fw-bold text-success" value="${price}" min="1"></td>
                    <td class="text-center align-middle"><button type="button" class="btn btn-delete btn-sm remove-row-btn py-1 px-3"><i class="fa fa-trash"></i></button></td>
                </tr>
            `;
            tbody.append(row);
        }
    }

    $("#editModal").modal("show");
});

// REMOVE ROW DYNAMICALLY
$(document).on('click', '.remove-row-btn', function() {
    $(this).closest('tr').remove();
});

// DELETE ENTIRE RECORD
$(document).on('click', '.delete-btn', function () {
     let recordId = $(this).data('id');
    Swal.fire({
        title: "Are you sure?",
        text: "This record will be deleted permanently.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete",
        cancelButtonText: "Cancel",
        confirmButtonColor: '#dc3545',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: window.location.origin + "/ajax/service/schedule_job.php",
                type: "POST",
                data: { method: "delete_sch_report", id: recordId },
                dataType: "json",
                success: function (response) {
                    toast('success', response.message);
                    unsubscribeList();
                },
                error: function () {
                    toast('error', 'Something went wrong');
                }
            });
        }
    });
});

// SAVE EDITS
$("#saveEdit").click(function (e) {
    e.preventDefault();
    let id = $("#edit_id").val();
    let routes = [];
    let isValid = true;

    $("#editScheduleTable tbody tr").each(function() {
        let from = $(this).find('.edit-from').val().trim();
        let to = $(this).find('.edit-to').val().trim();
        let dt = $(this).find('.edit-date').val();
        let price = $(this).find('.edit-price').val();

        if (!from || !to || !dt || !price) {
            isValid = false;
        } else {
            routes.push({
                from: from,
                to: to,
                datetime: dt.replace('T', ' ') + ':00',
                price: price
            });
        }
    });

    if (!isValid) {
        toast('error', 'Please fill all fields in the rows.');
        return;
    }

    let btn = $(this);
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

    $.ajax({
        url: window.location.origin + "/ajax/service/schedule_job.php",   
        type: "POST",
        data: { method: "update_sch_report", id: id, routes: JSON.stringify(routes) },
        dataType: "json",
        success: function (response) {
            $("#editModal").modal("hide");
            toast('success', response.message);
            unsubscribeList();
        },
        error: function () {
            toast('error', 'Update Failed');
        },
        complete: function() {
            btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> Save Schedules');
        }
    });
});

$(function() {
    createDatePicker('datefilterLogin');
    unsubscribeList();
});

function toast(icon, message) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    })
    Toast.fire({ icon: icon, title: message })
}
</script>



