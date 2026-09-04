
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
                    Firebase Signup Report
                </h1>
            </div>

            <div class="card filter-card shadow-sm">
                <div class="card-body py-3">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Select Date Range</label>
                            <div class="input-group">
                                <input type="text" id="date_range" class="form-control bg-white" placeholder="Select Date" readonly>
                                <button id="btnClear" class="btn btn-light border" type="button" title="Clear Date" style="height: 28px; width: 28px; padding: 0; margin-left: 10px; background: #f1f1f1; border-color: #d6d6d6; display: flex; align-items: center; justify-content: center; margin-top:4px;">
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
                                    <thead class="bg-light">
                                        <tr>
                                            <th>UTM CAMPAIGN</th>
                                            <th>UTM SOURCE</th>
                                            <th>UTM MEDIUM</th>
                                            <th class="text-center text-primary">TOTAL CUSTOMERS ACQUIRED</th>
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
    // Update this to wherever you pasted the PHP block
    const API_URL = 'ajax/service/datatable_services.php'; 

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
        $('#date_range').daterangepicker({
            autoUpdateInput: false,
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
            locale: { format: 'DD/MM/YYYY', cancelLabel: 'Clear' }
        });

        // Ensure the input starts empty so the user knows it's the full report initially
        $('#date_range').val('');

        // Load the full report initially with empty dates
        loadReport('', '');

        $('#date_range').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            let backendFromDate = picker.startDate.format('YYYY-MM-DD');
            let backendToDate = picker.endDate.format('YYYY-MM-DD');
            loadReport(backendFromDate, backendToDate);
        });

        $('#btnClear').on('click', function() {
            $('#date_range').val('');
            loadReport('', ''); 
        });
    });

    function loadReport(fromDate = '', toDate = '') {
        if ($.fn.DataTable.isDataTable('#reportTable')) {
            $('#reportTable').DataTable().clear().destroy();
        }
        $('#reportBody').html('<tr><td colspan="4" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading report...</td></tr>');

        $.ajax({
            url: API_URL,
            type: 'POST',
            dataType: 'json',
            data: { 
                method: 'utm_customer_report',
                from_date: fromDate,
                to_date: toDate
            },
            success: function(response) {
                if (response.status === true) {
                    let html = '';
                    
                    response.data.forEach((row) => {
                        let campaign = escapeHtml(row.utm_campaign);
                        let source = escapeHtml(row.utm_source);
                        let medium = escapeHtml(row.utm_medium);
                        let total = row.total_customers;

                        let campaignBadge = campaign === '(Direct / None)' ? `<span class="badge bg-light text-muted">${campaign}</span>` : `<span class="font-weight-bold text-dark">${campaign}</span>`;

                        html += `<tr>
                            <td>${campaignBadge}</td>
                            <td>${source}</td>
                            <td>${medium}</td>
                            <td class="text-center font-weight-bold text-primary" style="font-size:16px;">${total}</td>
                        </tr>`;
                    });

                    $('#reportBody').html(html);
                    $('#reportTable').DataTable({
                        "order": [[ 3, "desc" ]], // Updated to 3 because we removed the state column
                        "pageLength": 25,
                        "destroy": true
                    });
                } else {
                    $('#reportBody').html(`<tr><td colspan="4" class="text-center text-muted">${response.message || 'No data found.'}</td></tr>`);
                    toast('info', 'No report data found.');
                }
            },
            error: function() {
                $('#reportBody').html('<tr><td colspan="4" class="text-center text-danger">Failed to load data.</td></tr>');
                toast('error', 'Failed to fetch data from API.');
            }
        });
    }
</script>