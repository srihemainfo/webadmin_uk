<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referral Report</title>
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <!-- DataTables Buttons CSS -->
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Daterangepicker CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        body {
            background-color: #f4f6f9;
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
        .table th {
            text-transform: uppercase;
            font-size: 12px;
            color: #6c757d;
            font-weight: 700;
            background-color: #f8f9fa !important;
            letter-spacing: 0.5px;
        }
        .table td {
            vertical-align: middle;
            font-size: 14px;
        }
        .card {
            border: 0;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
            border-radius: 8px;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #edf2f9;
            padding: 1rem 1.25rem;
        }
        div.dataTables_wrapper div.dataTables_filter input {
            border-radius: 4px;
            border: 1px solid #ced4da;
            padding: 4px 8px;
        }
        div.dataTables_wrapper div.dataTables_filter input:focus {
            box-shadow: none;
            border-color: #86b7fe;
        }
        .side-menu__item{
            text-decoration:none!important;
        }
        #date_filter {
            cursor: pointer;
            background-color: #fff;
        }
        
        /* --- ISOLATED DATATABLES HEADER CSS --- */
        /* Forces the label and select to sit perfectly inline */
        #referralTable_wrapper .dataTables_length label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 0;
        }
        #referralTable_wrapper .dataTables_length select {
            border-radius: 4px;
            padding: 4px 8px;
            margin: 0;
        }
        /* Minimizes button text and padding without affecting sidebar */
        #referralTable_wrapper .dt-buttons .btn {
            font-size: 11px !important; 
            padding: 4px 8px !important;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            
            <div class="page-header mt-4 mb-4">
                <h1 class="page-title d-flex align-items-center mb-0" style="font-size: 1.5rem; font-weight: 600;">
                    <a href="javascript:void(0)" class="back-arrow-btn text-decoration-none">
                        <i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i>
                    </a>
                    Referral Report
                </h1>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-bottom-0">
                            <h3 class="card-title fw-bold text-dark m-0">Detailed Referral Records</h3>
                        </div>
                        <div class="card-body pt-3">
                            
                            <!-- Filter Section -->
                            <div class="row mb-4 align-items-end">
                                <div class="col-md-4 col-lg-3">
                                    <label class="form-label fw-bold text-muted small">Select Date</label>
                                    <input type="text" id="date_filter" class="form-control" placeholder="Select Date Range" readonly>
                                </div>
                                <div class="col-md-4 col-lg-3">
                                    <button type="button" id="resetBtn" class="btn btn-light border px-4">
                                        <i class="fa fa-refresh me-1"></i> Reset
                                    </button>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom table-hover w-100" id="referralTable">
                                    <thead>
                                        <tr>
                                            <th width="5%">S.No</th>
                                            <th width="15%">Date & Time</th>
                                            <th width="10%">Referral Code</th>
                                            <th width="15%">Referrer Details</th>
                                            <th width="15%">Referred User</th>
                                            <th width="10%">Referrer Earned</th>
                                            <th width="10%">Refered User Earned</th>
                                            <th width="5%" class="text-center">Status</th>
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

<!-- Core JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

<!-- Moment.js and Daterangepicker Plugins -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    $(document).ready(function() {
        
        let startDateValue = '';
        let endDateValue = '';

        // Initialize Daterangepicker
        $('#date_filter').daterangepicker({
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
            locale: {
                cancelLabel: 'Clear',
                format: 'MM/DD/YYYY'
            }
        });

        // Apply Date Range Event
        $('#date_filter').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            startDateValue = picker.startDate.format('YYYY-MM-DD');
            endDateValue = picker.endDate.format('YYYY-MM-DD');
            table.draw();
        });

        // Cancel/Clear Date Range Event
        $('#date_filter').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            startDateValue = '';
            endDateValue = '';
            table.draw();
        });

        // Initialize Server-Side DataTables
        var table = $('#referralTable').DataTable({
            "processing": true,
            "serverSide": true,
            "destroy": true,
            // CHANGED DOM: Grouped 'l' (length) and 'B' (buttons) together in a flex container on the left
            "dom": "<'row mb-3 align-items-center'<'col-sm-12 col-md-8 d-flex align-items-center gap-3'l B><'col-sm-12 col-md-4'f>>" +
                   "<'row'<'col-sm-12'tr>>" +
                   "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "buttons": [
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-success btn-sm',
                    text: '<i class="fa fa-file-excel"></i> Excel'
                },
                {
                    extend: 'copyHtml5',
                    className: 'btn btn-primary btn-sm',
                    text: '<i class="fa fa-copy"></i> Copy Numbers',
                    header: false,
                    title: '', // PREVENTS THE "REFERRAL REPORT" TITLE FROM COPYING
                    exportOptions: {
                        columns: [ 4 ], // Exports ONLY the 'Referred User' column (Index 4)
                        format: {
                            body: function ( data, row, column, node ) {
                                // 1. Strip HTML tags, replace with space
                                let text = data.replace(/<[^>]*>?/gm, ' ');
                                // 2. Extract sequences of 10 or more digits (standard phone numbers)
                                let phoneMatch = text.match(/\d{10,15}/);
                                if (phoneMatch) {
                                    return phoneMatch[0];
                                }
                                // 3. Fallback: just strip all non-numeric characters
                                return text.replace(/\D/g, '');
                            }
                        }
                    },
                    customize: function(data) {
                        let rows = data.split('\n');
                        let result = rows.map(function(row) {
                            return row.trim();
                        }).filter(function(row) {
                            return row !== '';
                        });
                        return result.join(',');
                    }
                }
            ],
            "ajax": {
                "url": "/ajax/service/referral_report_services.php",
                "type": "POST",
                "data": function (d) {
                    d.method = "fetch_referral_report";
                    d.start_date = startDateValue;
                    d.end_date = endDateValue;
                }
            },
            "order": [],
            "pageLength": 25,
            "lengthMenu": [10, 25, 50, 100],
            "columnDefs": [
                { "orderable": false, "targets": [0, 2, 3, 4] }
            ],
            "language": {
                "search": "Search Code/Name/Mobile:",
                "processing": '<i class="fa fa-spinner fa-spin fa-2x fa-fw text-primary"></i><span class="sr-only">Loading...</span>'
            }
        });

        // Trigger reset manual button
        $('#resetBtn').on('click', function() {
            $('#date_filter').val('');
            startDateValue = '';
            endDateValue = '';
            table.draw();
        });
    });
</script>

</body>
</html>