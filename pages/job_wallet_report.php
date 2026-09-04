

<style>
    /* Input and Button Defaults */
    input, button, select, textarea {
        margin: 0;
        font-family: inherit;
        font-size: 14px;
        color: inherit;
    }
    
    input.form-control, select.form-control {
        height: 35px;
        padding: 6px 12px;
        border-radius: 4px;
        border: 1px solid #CCC;
    }

    button.btn-primary {
        color: #FFF;
        background-color: #428BCA;
        border: 1px solid #357EBD;
        height: 35px;
        border-radius: 4px;
    }
    
    button.btn-primary:hover {
        background-color: #3276b1;
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
        text-align: center;
        line-height: 18px;
    }
    
    /* Solid UI Container */
    .tab-content {
        border: 1px solid #ddd;
        padding: 25px 20px;
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .table thead th {
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 600;
        color: #555;
    }

    /* Modal Professional Solid Styling */
    .modal-content.solid-corners {
        border-radius: 4px !important;
        border: 1px solid #ccc;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .modal-header.solid-header {
        border-radius: 4px 4px 0 0 !important;
    }
</style>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <br>
            <div class="page-header align-items-center mb-4">
                <h1 class="page-title m-0">
                    <a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)"></i></a> 
                    Job Wallet History Report
                </h1>
                <input type="hidden" id="rmk_user_id">
<input type="hidden" id="rmk_mem_id" value="<?= $_SESSION['memid'] ?? '' ?>">
                <div class="ms-auto">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cron Recovery</li>
                    </ol>
                </div>
            </div>

            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="tab-content" id="reportTabsContent">
                        
                        <div class="row w-100 align-items-end mb-4">
                            <div class="col-lg-3 col-md-3 mb-3">
                                <span class="form-label mb-1" style="font-size: 13px; font-weight:600;">Select Date <span style="color:red;">*</span></span>
                                <div id="reportrange-cron" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; border-radius: 4px; height:35px; display:flex; align-items:center; justify-content:space-between;">
                                    <div><i class="fa fa-calendar text-muted"></i>&nbsp; <span></span></div>
                                    <i class="fa fa-caret-down text-muted"></i>
                                </div>
                            </div>
                            
                            <div class="col-lg-2 col-md-2 mb-3">
                                <span class="form-label mb-1" style="font-size: 13px; font-weight:600;">Status</span>
                                <select id="statusFilter" class="form-control" style="width: 100%;">
                                    <option value="">All Statuses</option>
                                    <option value="Unpaid">Unpaid</option>
                                    <option value="Partially Paid">Partially Paid</option>
                                    <option value="Refunded">Refunded</option>
                                    <option value="Cleared">Cleared</option>
                                </select>
                            </div>

                            <div class="col-lg-4 col-md-4 mb-3">
                                <span class="form-label mb-1" style="font-size: 13px; font-weight:600;">Search</span>
                                <input type="text" id="searchKeyword" class="form-control" placeholder="Search Mobile, Email, Name, Job No..." style="height: 35px; width: 100%;">
                            </div>
                            
                            <div class="col-lg-2 col-md-2 mb-3">
                                <button type="button" class="btn btn-primary w-100 fw-bold d-flex align-items-center justify-content-center" onclick="viewCronTable()">Search</button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped border-bottom" id="cronDeductionTable" style="width:100%;">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Job No</th>
                                        <th>Opening Bal (₹)</th>
                                        <th>Deducted (₹)</th>
                                        <th>Closing Bal (₹)</th>
                                        <th>Paid After (₹)</th>
                                        <th>To Pay (₹)</th>
                                        <th>Status</th>
                                        <th>Deduction Date</th>
                                        <th>Action</th> 
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-labelledby="paymentDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> 
        <div class="modal-content solid-corners"> 
            <div class="modal-header border-bottom-0 bg-light solid-header">
                <h5 class="modal-title fw-bold" id="paymentDetailsModalLabel" style="color: #333;">Payment Split Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <div class="table-responsive border" style="border-radius: 4px; overflow: hidden;">
                    <table class="table table-bordered table-striped mb-0" style="color: #444;">
                        <thead class="bg-light">
                            <tr>
                                <th style="font-size: 13px; font-weight: 600;">#</th>
                                <th style="font-size: 13px; font-weight: 600;">Transaction ID</th>
                                <th style="font-size: 13px; font-weight: 600;">Gateway</th>
                                <th style="font-size: 13px; font-weight: 600;">Paid Amount (₹)</th>
                                <th style="font-size: 13px; font-weight: 600;">Date & Timing</th>
                            </tr>
                        </thead>
                        <tbody id="paymentDetailsBody" style="font-size: 14px;">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-primary d-flex align-items-center justify-content-center" data-bs-dismiss="modal" style="border-radius: 4px;">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="refundModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content solid-corners">
            <div class="modal-header bg-danger text-white border-bottom-0 solid-header">
                <h5 class="modal-title fw-bold m-0"><i class="fa fa-exclamation-triangle me-2"></i> Caution: Refund Deduction</h5>
            </div>
            <div class="modal-body pt-4">
                <p class="mb-4 text-center" style="font-size: 15px; color:#444;">
                    Are you sure you want to refund the deduction for Job: <strong id="refundJobNoDisplay" class="text-danger"></strong>?
                </p>
                <div class="form-group text-start m-0">
                    <label class="fw-bold mb-2" style="color:#333;">Reason for Refund <span class="text-danger">*</span></label>
                    <textarea id="refundReasonInput" maxlength="150" class="form-control" rows="3" style="border-radius: 4px; resize: none;" placeholder="Enter a detailed reason (max 150 chars)..."></textarea>
                    <small class="text-muted mt-1 d-block" style="font-size: 12px;">Only alphanumeric characters, spaces, and commas are allowed.</small>
                </div>
                <input type="hidden" id="refundJobNo">
                <input type="hidden" id="refundUserId"> 
            </div>
            <div class="modal-footer bg-light border-top d-flex justify-content-end" style="border-radius: 0 0 4px 4px;">
                <button type="button" class="btn btn-outline-secondary px-4 me-2" data-bs-dismiss="modal" style="border-radius: 4px;">Cancel</button>
                <button type="button" class="btn btn-danger px-4 d-flex align-items-center justify-content-center fw-bold" onclick="submitManualRefund()" style="border-radius: 4px;">Process Refund</button>
            </div>
        </div>
    </div>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 99999;">
    <div id="refundSuccessToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fa fa-check-circle me-2"></i> Refund processed successfully!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    
    <div id="permissionDeniedToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fa fa-times-circle me-2"></i> You Are Not Permitted to Refund
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>

    <div id="validationErrorToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="validationErrorText">
                <i class="fa fa-exclamation-circle me-2"></i> Invalid input.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
    var url = window.location.origin + "/ajax/service/transaction_services.php";
    
    // NOTE: Replace this with the logged-in user's actual ID from your session context
    var loggedInMemId = parseInt($('#rmk_mem_id').val(), 10) || 0;

    $(document).ready(function() {
        console.log("Logged In Member ID:", loggedInMemId);
        createDatePricket('reportrange-cron');
        viewCronTable();
    });

    function createDatePricket(id) {
        var start = moment();
        var end = moment();

        function cb(start, end) {
            $('#' + id + ' span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));
        }

        $('#' + id).daterangepicker({
        startDate: start.set({ hour: 0, minute: 0, second: 0, millisecond: 0 }),
        endDate: end.set({ hour: 23, minute: 59, second: 59, millisecond: 59 }),
        timePicker: true,
        timePicker24Hour: true,
        timePickerSeconds: true,
        autoUpdateInput: true,
        maxDate: moment().add(1, 'days').toDate(),
        ranges: {
            'Today': [moment().set({hour: 0, minute: 0, second: 0, millisecond: 0}), moment().set({hour: 23, minute: 59, second: 59, millisecond: 59})],
            'Last 7 Days': [moment().subtract(6, 'days').set({hour: 0, minute: 0, second: 0, millisecond: 0}), moment().set({hour: 23, minute: 59, second: 59, millisecond: 59})],
            'This Month': [moment().startOf('month').set({hour: 0, minute: 0, second: 0, millisecond: 0}), moment().endOf('month').set({hour: 23, minute: 59, second: 59, millisecond: 59})],
            'Last Month': [moment().subtract(1, 'month').startOf('month').set({hour: 0, minute: 0, second: 0, millisecond: 0}), moment().subtract(1, 'month').endOf('month').set({hour: 23, minute: 59, second: 59, millisecond: 59})],
            'All Time': [moment(0).set({hour: 0, minute: 0, second: 0, millisecond: 0}), moment().set({hour: 23, minute: 59, second: 59, millisecond: 59})]
        }
    }, cb);
        cb(start, end);
    }

    function viewCronTable() {
        var formdate = moment($('#reportrange-cron').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
        var todate = moment($('#reportrange-cron').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");
        var searchKeyword = $('#searchKeyword').val(); 
        var statusFilter = $('#statusFilter').val();

        $("#cronDeductionTable").DataTable({
            destroy: true,
            autoWidth: false, 
            pageLength: 10,
            order: [[0, 'desc']],
            language: {
                emptyTable: "No data available for the selected filters",
                zeroRecords: "No data available for the selected filters"
            },
            ajax: {
                url: url,
                method: "POST",
                dataSrc: "",
                data: {
                    method: 'cron_deduction_report',
                    formdate: formdate,
                    todate: todate,
                    searchKeyword: searchKeyword,
                    statusFilter: statusFilter
                }
            },
            dom: 'Bfrtip',
            buttons: ['pageLength', 'copy', { extend: 'excelHtml5', title: 'Cron Deduction Report' }],
            columns: [
                { data: 'ID', defaultContent: '' },
                { data: 'name', defaultContent: '-' },
                { data: 'mobile', defaultContent: '-' },
                { data: 'job_no', defaultContent: '-' },
                { data: 'opening_balance', defaultContent: '0.00' },
                { 
                    data: 'deducted_amount', 
                    defaultContent: '0.00',
                    render: function(data) { return '- ' + data; }
                },
                { data: 'closing_balance', defaultContent: '0.00' },
                { 
                    data: 'amount_paid', 
                    defaultContent: '-',
                    render: function(data, type, row) {
                        if (row.is_refunded) return '<span class="text-muted fw-bold">-</span>';
                        return data;
                    }
                },
                { 
                    data: 'to_pay', 
                    defaultContent: '-',
                    render: function(data, type, row) {
                        if (row.is_refunded) return '<span class="text-muted fw-bold">-</span>';
                        return data;
                    }
                },
                { 
                    data: 'paymentStatus', 
                    defaultContent: '-',
                    render: function(data) {
                        if(data === 'Cleared') return '<span class="text-success fw-bold">' + data + '</span>';
                        if(data === 'Refunded') return '<span class="text-primary fw-bold" style="color: #0d6efd !important;">' + data + '</span>';
                        if(data === 'Partially Paid') return '<span class="text-warning fw-bold" style="color: #f39c12 !important;">' + data + '</span>';
                        return '<span class="text-danger fw-bold">' + data + '</span>';
                    }
                },
                {
                    data: 'date',
                    render: function(data) { return data ? moment.utc(data).local().format("DD MMM YYYY hh:mm A") : ''; }
                },
                { 
                    data: null,
                    orderable: false,
                    className: "text-center",
                    render: function(data, type, row) {
                        var detailsArray = row.payment_details ? row.payment_details : [];
                        var detailsStr = encodeURIComponent(JSON.stringify(detailsArray));
                        var isRef = row.is_refunded ? 1 : 0;
                        
                        // Action buttons placed side-by-side using Flexbox
                        var html = '<div class="d-flex align-items-center justify-content-center gap-2">';
                        
                        // View Details Button
                        html += `<button class="btn btn-sm btn-light border text-dark fw-bold shadow-sm" style="border-radius: 4px;" onclick="showPaymentDetails('${detailsStr}', '${row.deducted_amount}', '${row.to_pay}', ${isRef})" title="View Details">View Details</button>`;
                        
                        // Show Refund Action if status is Unpaid or Partially Paid AND it is NOT a Carpool Job
                        if((row.paymentStatus === 'Unpaid' || row.paymentStatus === 'Partially Paid') && !row.is_carpool) {
                            html += `<button class="btn btn-sm btn-danger text-white fw-bold shadow-sm" style="border-radius: 4px;" onclick="openRefundModal('${row.job_no}', '${row.userid}')">Refund</button>`;
                        } else if (row.is_carpool) {
                            // Show standard badge for Carpool since refunds aren't allowed
                            html += `<span class="badge bg-info text-white shadow-sm" style="border-radius: 4px; padding: 6px 10px;">Carpool</span>`;
                        }
                        
                        html += '</div>';
                        return html;
                    }
                }
            ]
        });
    }

    function openRefundModal(jobNo, userId) {
        // ONLY allow to open the modal if loggedInMemId is 1
        if (loggedInMemId === 1) {
            $('#refundJobNoDisplay').text(jobNo);
            $('#refundJobNo').val(jobNo);
            $('#refundUserId').val(userId);
            $('#refundReasonInput').val(''); 
            $('#refundModal').modal('show');
        } else {
            // Show the permission denied toast
            var toastEl = document.getElementById('permissionDeniedToast');
            var toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    }

    function showErrorToast(message) {
        $('#validationErrorText').html('<i class="fa fa-exclamation-circle me-2"></i> ' + message);
        var toastEl = document.getElementById('validationErrorToast');
        var toast = new bootstrap.Toast(toastEl);
        toast.show();
    }

    function submitManualRefund() {
        var jobNo = $('#refundJobNo').val();
        var reason = $('#refundReasonInput').val().trim();
        
        // 1. Validation for empty reason
        if(reason === '') {
            showErrorToast("Please provide a reason for the refund.");
            $('#refundReasonInput').focus();
            return;
        }

        // 2. Validation for character limit (already enforced by maxlength, but good as fallback)
        if(reason.length > 150) {
            showErrorToast("Reason cannot exceed 50 characters.");
            $('#refundReasonInput').focus();
            return;
        }

        // 3. Validation for special characters (only alphanumeric, spaces, commas, and dots allowed)
        var regex = /^[a-zA-Z0-9 ,.]*$/;
        if(!regex.test(reason)) {
            showErrorToast("Special characters are not allowed. Only text, numbers, spaces, commas, and dots are accepted.");
            $('#refundReasonInput').focus();
            return;
        }

        $.ajax({
            url: url,
            method: 'POST',
            dataType: 'json',
            data: {
                method: 'process_manual_refund',
                job_no: jobNo,
                reason: reason,
                mem_id: loggedInMemId
            },
            success: function(response) {
                if(response.status) {
                    $('#refundModal').modal('hide');
                    viewCronTable(); 
                    
                    // Show success toast
                    var toastEl = document.getElementById('refundSuccessToast');
                    var toast = new bootstrap.Toast(toastEl);
                    toast.show();
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function() {
                alert("Something went wrong processing the refund.");
            }
        });
    }

    function showPaymentDetails(encodedDetails, deductedAmt, toPay, isRefunded) {
        var details = JSON.parse(decodeURIComponent(encodedDetails));
        var tbody = $('#paymentDetailsBody');
        tbody.empty();

        if (isRefunded) {
            var refundedBy = details[0] && details[0].refunded_by ? details[0].refunded_by : '-';
            var refundReason = details[0] && details[0].reason ? details[0].reason : 'Due to Job Cancelled';

            tbody.append(`
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <i class="fa fa-undo text-primary mb-2" style="font-size: 24px;"></i><br>
                        <h6 class="fw-bold text-primary mb-1">Deduction Fully Refunded</h6>
                        <small class="text-muted d-block mt-2"><strong>Refunded By:</strong> ${refundedBy}</small>
                        <small class="text-muted d-block"><strong>Reason:</strong> ${refundReason}</small>
                    </td>
                </tr>
            `);

            details.forEach(function(item, index) {
                // Fixed: Removed .utc().local() to prevent timezone shifting
                var formattedDate = item.date ? moment(item.date).format("DD MMM YYYY hh:mm A") : '-';
                tbody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.transaction_id}</td>
                        <td><span class="badge bg-primary">${item.gateway}</span></td>
                        <td class="fw-bold text-primary">+ ${item.amount}</td>
                        <td>${formattedDate}</td>
                    </tr>
                `);
            });

            tbody.append(`
                <tr class="bg-light">
                    <td colspan="3" class="text-end fw-bold text-dark" style="font-size: 13px;">Original Deduction:</td>
                    <td class="fw-bold text-dark" style="font-size: 13px;">${deductedAmt}</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end fw-bold" style="font-size: 13px;">Status:</td>
                    <td class="fw-bold text-primary" style="font-size: 13px;">Refunded to Wallet</td>
                    <td></td>
                </tr>
            `);
        } else {
            if (details.length === 0) {
                tbody.append('<tr><td colspan="5" class="text-center text-muted py-3">No payments recorded for this job.</td></tr>');
            } else {
                var totalAccounted = 0;
                details.forEach(function(item, index) {
                    totalAccounted += parseFloat(item.amount.replace(/,/g, ''));
                    // Fixed: Removed .utc().local() to prevent timezone shifting
                    var formattedDate = item.date ? moment(item.date).format("DD MMM YYYY hh:mm A") : '-';
                    var badgeClass = item.gateway === 'OPENING BALANCE' ? 'bg-primary' : 'bg-secondary';

                    tbody.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.transaction_id}</td>
                            <td><span class="badge ${badgeClass}">${item.gateway}</span></td>
                            <td class="fw-bold">+ ${item.amount}</td>
                            <td>${formattedDate}</td>
                        </tr>
                    `);
                });

                tbody.append(`
                    <tr>
                        <td colspan="3" class="text-end fw-bold" style="font-size: 13px;">Total Accounted:</td>
                        <td class="fw-bold text-success" style="font-size: 13px;">+ ${totalAccounted.toFixed(2)}</td>
                        <td></td>
                    </tr>
                    <tr class="bg-light">
                        <td colspan="3" class="text-end fw-bold text-dark" style="font-size: 13px;">Original Deduction:</td>
                        <td class="fw-bold text-dark" style="font-size: 13px;">${deductedAmt}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end fw-bold" style="font-size: 13px;">Still To Pay:</td>
                        <td class="fw-bold text-danger" style="font-size: 13px;">${toPay}</td>
                        <td></td>
                    </tr>
                `);
            }
        }
        $('#paymentDetailsModal').modal('show');
    }
</script>