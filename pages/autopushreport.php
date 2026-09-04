<style>
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
    #reportTable td {
        vertical-align: middle;
        font-size: 14px;
    }
    .filter-card {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        margin-bottom: 20px;
    }
    /* Fix for input group icon */
    .input-group-text {
        cursor: pointer;
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            
            <div class="page-header">
                <h1 class="page-title">
                    <a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>
                    Push Automation Report
                </h1>
            </div>

            <div class="card filter-card shadow-sm">
                <div class="card-body py-3">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Select Date</label>
                            <div class="input-group">
                                <input type="text" id="date_range" class="form-control bg-white" placeholder="DD/MM/YYYY - DD/MM/YYYY" readonly>
                                <button id="btnClear"
        class="btn btn-light border"
        type="button"
        title="Clear Date"
        style="
            height: 28px;
            width: 28px;
            padding: 0;
            margin-left: 10px;
            background: #f1f1f1;
            border-color: #d6d6d6;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top:4px;
        ">
    <i class="fa fa-times text-danger" style="font-size: 12px;"></i>
</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="reportTable" class="table table-bordered table-hover text-nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Event Name</th>
                                            <th>Title</th>
                                            <th>Message Body</th>
                                            <th>Redirect</th>
                                            <th class="text-center text-primary">Total Triggered</th>
                                            <th class="text-center text-success">Success</th>
                                            <th class="text-center text-danger">Failed</th>
                                        </tr>
                                    </thead>
                                    <tbody id="reportBody">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    const API_URL = 'ajax/service/push_report_api.php'; 

    function toast(icon, message) {
        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 4000 }).fire({ icon: icon, title: message });
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    $(document).ready(function() {
        // 1. Initialize Date Range Picker
        $('#date_range').daterangepicker({
            startDate: moment(), // Set default start date internally
            endDate: moment(),   // Set default end date internally
            autoUpdateInput: false, // Prevents auto-filling the input field before selection
            opens: 'right',
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
            },
            locale: {
                format: 'DD/MM/YYYY',
                cancelLabel: 'Clear'
            }
        });

        // 2. Explicitly set the input field to today's date by default
        $('#date_range').val(moment().format('DD/MM/YYYY') + ' - ' + moment().format('DD/MM/YYYY'));

        // 3. Load default report on page load filtered to TODAY
        let todayBackend = moment().format('YYYY-MM-DD');
        loadReport(todayBackend, todayBackend);

        // 4. Auto-trigger search when user selects a date range
        $('#date_range').on('apply.daterangepicker', function(ev, picker) {
            // Update the UI input field to show the selected range
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));

            // Format dates for the backend (MySQL prefers YYYY-MM-DD)
            let backendFromDate = picker.startDate.format('YYYY-MM-DD');
            let backendToDate = picker.endDate.format('YYYY-MM-DD');
            
            // Trigger the report load immediately
            loadReport(backendFromDate, backendToDate);
        });

        // 5. Clear button logic
        $('#btnClear').on('click', function() {
            $('#date_range').val(''); // Clear the input field
            loadReport('', ''); // Reload report without date filters
        });
    });

    // The loadReport function remains largely the same
    function loadReport(fromDate = '', toDate = '') {
        if ($.fn.DataTable.isDataTable('#reportTable')) {
            $('#reportTable').DataTable().clear().destroy();
        }
        $('#reportBody').html('<tr><td colspan="7" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading report...</td></tr>');

        $.ajax({
            url: API_URL,
            type: 'POST',
            dataType: 'json',
            data: { 
                method: 'get_automation_report',
                from_date: fromDate,
                to_date: toDate
            },
            success: function(response) {
                if (response.status === true) {
                    let html = '';
                    
                    response.data.forEach((row) => {
                        let eventName = escapeHtml(row.event_name);
                        let title = escapeHtml(row.title);
                        let message = escapeHtml(row.message);
                        let redirect = escapeHtml(row.redirect);
                        let totalCount = row.total_count || 0;
                        let successCount = row.success_count || 0;
                        let failureCount = row.failure_count || 0;

                        html += `<tr>
                            <td class="font-weight-bold">${eventName}</td>
                            <td>${title}</td>
                            <td style="white-space: normal; min-width: 250px;">${message}</td>
                            <td><span class="badge bg-light text-dark border">${redirect}</span></td>
                            <td class="text-center font-weight-bold text-primary">${totalCount}</td>
                            <td class="text-center font-weight-bold text-success">${successCount}</td>
                            <td class="text-center font-weight-bold text-danger">${failureCount}</td>
                        </tr>`;
                    });

                    $('#reportBody').html(html);
                    $('#reportTable').DataTable({
                        "order": [[ 4, "desc" ]], 
                        "pageLength": 25,
                        "destroy": true
                    });
                } else {
                    $('#reportBody').html(`<tr><td colspan="7" class="text-center text-muted">${response.message || 'No data found.'}</td></tr>`);
                    toast('info', 'No report data found.');
                }
            },
            error: function() {
                $('#reportBody').html('<tr><td colspan="7" class="text-center text-danger">Failed to load data.</td></tr>');
                toast('error', 'Failed to fetch data from API.');
            }
        });
    }
</script>