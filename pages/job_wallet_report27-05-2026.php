<style>
    /* Input and Button Defaults */
    input, button, select {
        height: 35px;
        margin: 0;
        padding: 6px 12px;
        border-radius: 4px;
        font-family: inherit;
        font-size: 14px;
        color: inherit;
        border: 1px solid #CCC;
    }

    button.btn-primary {
        color: #FFF;
        background-color: #428BCA;
        border: 1px solid #357EBD;
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
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .table thead th {
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 600;
        color: #555;
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
                            <div class="col-lg-3 col-md-4 mb-3">
                                <span class="form-label mb-1" style="font-size: 13px; font-weight:600;">Select Date <span style="color:red;">*</span></span>
                                <div id="reportrange-cron" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; border-radius: 4px; height:35px; display:flex; align-items:center; justify-content:space-between;">
                                    <div><i class="fa fa-calendar text-muted"></i>&nbsp; <span></span></div>
                                    <i class="fa fa-caret-down text-muted"></i>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-5 mb-3">
                                <span class="form-label mb-1" style="font-size: 13px; font-weight:600;">Search</span>
                                <input type="text" id="searchKeyword" class="form-control" placeholder="Search Mobile, Email, Name, Job No..." style="height: 35px; width: 100%;">
                            </div>
                            
                            <div class="col-lg-2 col-md-3 mb-3">
                                <button type="button" class="btn btn-primary w-100 fw-bold" onclick="viewCronTable()">Search</button>
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
        <div class="modal-content" style="border-radius: 12px;"> 
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="paymentDetailsModalLabel" style="color: #333;">Payment Split Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <div class="table-responsive border" style="border-radius: 8px; overflow: hidden;">
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
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" style="border-radius: 6px;">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    var url = window.location.origin + "/ajax/service/transaction_services.php";

    $(document).ready(function() {
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
                    searchKeyword: searchKeyword
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
                        if (row.is_refunded) {
                            return '<span class="text-muted fw-bold">-</span>';
                        }
                        return data;
                    }
                },
                { 
                    data: 'to_pay', 
                    defaultContent: '-',
                    render: function(data, type, row) {
                        if (row.is_refunded) {
                            return '<span class="text-muted fw-bold">-</span>';
                        }
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
                    render: function(data) { return data ? moment(data).format("DD MMM YYYY hh:mm a") : ''; }
                },
                { 
                    data: null,
                    orderable: false,
                    className: "text-center",
                    render: function(data, type, row) {
                        var detailsStr = encodeURIComponent(JSON.stringify(row.payment_details));
                        var isRef = row.is_refunded ? 1 : 0;
                        
                        return `<button class="btn btn-sm btn-light border text-dark fw-bold shadow-sm" onclick="showPaymentDetails('${detailsStr}', '${row.deducted_amount}', '${row.to_pay}', ${isRef})" title="View Details">
                                    View Details
                                </button>`;
                    }
                }
            ]
        });
    }

    function showPaymentDetails(encodedDetails, deductedAmt, toPay, isRefunded) {
        var details = JSON.parse(decodeURIComponent(encodedDetails));
        var tbody = $('#paymentDetailsBody');
        tbody.empty();

        if (isRefunded) {
            // Dedicated UI for Refunded Jobs
            tbody.append(`
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <i class="fa fa-undo text-primary mb-2" style="font-size: 24px;"></i><br>
                        <h6 class="fw-bold text-primary mb-1">Deduction Fully Refunded</h6>
                        <small class="text-muted">No external wallet payments were consumed for this job.</small>
                    </td>
                </tr>
            `);

            // Detail rows for refund
            details.forEach(function(item, index) {
                var formattedDate = item.date ? moment(item.date).format("DD MMM YYYY hh:mm a") : '-';
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

            // Refund Footer
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
            // Normal Split Logic for Unpaid/Paid Jobs
            if (details.length === 0) {
                tbody.append('<tr><td colspan="5" class="text-center text-muted py-3">No payments recorded for this job.</td></tr>');
            } else {
                var totalAccounted = 0;
                details.forEach(function(item, index) {
                    totalAccounted += parseFloat(item.amount.replace(/,/g, ''));
                    var formattedDate = item.date ? moment(item.date).format("DD MMM YYYY hh:mm a") : '-';
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

    function showPaymentDetails(encodedDetails, deductedAmt, toPay) {
        var details = JSON.parse(decodeURIComponent(encodedDetails));
        var tbody = $('#paymentDetailsBody');
        tbody.empty();

        if (details.length === 0) {
            tbody.append('<tr><td colspan="5" class="text-center text-muted py-3">No payments recorded for this job.</td></tr>');
        } else {
            var totalAccounted = 0;
            
            details.forEach(function(item, index) {
                // Keep track of the running total for the calculation footer
                totalAccounted += parseFloat(item.amount.replace(/,/g, ''));
                var formattedDate = item.date ? moment(item.date).format("DD MMM YYYY hh:mm a") : '-';
                
                // Style the Gateway Badge dynamically 
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

            // The Math Calculation Footer
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

        $('#paymentDetailsModal').modal('show');
    }
</script>