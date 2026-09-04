<head>
    <!-- Dependencies -->
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- Daterangepicker CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Moment.js and Daterangepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
</head>

<style>
    .back-arrow-btn i {
        background: #ffffff; font-size: 16px; padding: 2px 3px;
        border-radius: 50px; border: 2px solid #6c6e70; color: #6c6e70;
        margin-right: 15px; width: 24px; height: 24px;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .filter-container {
        border: 1px solid #e0e0e0; padding: 20px;
        background: #f8f9fa; border-radius: 4px; 
        margin-bottom: 20px;
    }
    .table-container {
        border: 1px solid #e0e0e0; padding: 20px;
        background: #fff; border-radius: 4px; 
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .table thead th { font-size: 14px; font-weight: 600; color: #333; background-color: #f1f1f1; text-transform: uppercase; }
    .table tbody td { font-size: 14px; color: #333; vertical-align: middle; }
    .array-separator { margin: 8px 0; border-top: 1px solid #dee2e6; }
</style>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <br>
            <div class="page-header align-items-center mb-4 d-flex justify-content-between">
                <h1 class="page-title m-0 d-flex align-items-center">
                    <a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)"></i></a> 
                    User Spin Rewards Report
                </h1>
            </div>

            <!-- Date Filter Section -->
            <div class="filter-container">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Select Date (Spin Date)</label>
                        <input type="text" id="date_range_picker" class="form-control" placeholder="Select Date" readonly style="background-color: #fff; cursor: pointer;">
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="reportsTable" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Spin Date & Time</th>
                                <th>User Name</th>
                                <th>Mobile Number</th>
                                <th class="text-center">No. of Spins</th>
                                <th>Slot No.</th>
                                <th>Reward Type</th>
                                <th>Reward Value</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
    var reportApiUrl = window.location.origin + "/ajax/service/spin_reports_service.php"; 
    var reportTable;
    var currentStartDate = '';
    var currentEndDate = '';

    $(document).ready(function() { 
        // Initialize Daterangepicker
        $('#date_range_picker').daterangepicker({
            autoUpdateInput: false,
            opens: 'right',
            showDropdowns: true,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
            },
            locale: {
                format: 'YYYY-MM-DD',
                cancelLabel: 'Clear'
            }
        });

        // Apply Button Click Event
        $('#date_range_picker').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            currentStartDate = picker.startDate.format('YYYY-MM-DD');
            currentEndDate = picker.endDate.format('YYYY-MM-DD');
            loadReportTable();
        });

        // Clear/Cancel Button Click Event
        $('#date_range_picker').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            currentStartDate = '';
            currentEndDate = '';
            loadReportTable();
        });

        // Load table initially with no filters
        loadReportTable(); 
    });

    function loadReportTable() {
        reportTable = $("#reportsTable").DataTable({
            destroy: true, 
            autoWidth: false, 
            pageLength: 25, 
            order: [[0, 'desc']], 
            language: { emptyTable: "No spin records found for the selected dates." },
            ajax: { 
                url: reportApiUrl, 
                type: "POST", 
                dataSrc: "data", 
                data: { 
                    method: 'fetch_report',
                    start_date: currentStartDate,
                    end_date: currentEndDate
                } 
            },
            columns: [
                { 
                    data: 'spin_at', 
                    width: "15%",
                    render: function(data) {
                        return data ? moment(data).format('YYYY-MM-DD hh:mm:ss A') : '-';
                    }
                },
                { 
                    data: 'name', 
                    width: "15%",
                    defaultContent: "Unknown User"
                },
                { 
                    data: 'mobile', 
                    width: "12%",
                    defaultContent: "-"
                },
                { 
                    data: 'slot_data', 
                    width: "8%",
                    className: "text-center align-middle",
                    render: function(data) {
                        try {
                            let parsedData = JSON.parse(data);
                            let sdArray = Array.isArray(parsedData) ? parsedData : [parsedData];
                            return `<span class="badge bg-primary rounded-pill px-3 py-2 fs-6">${sdArray.length}</span>`;
                        } catch(e) {
                            return '0';
                        }
                    }
                },
                { 
                    data: 'slot_data', 
                    width: "10%",
                    render: function(data) {
                        try {
                            let parsedData = JSON.parse(data);
                            let sdArray = Array.isArray(parsedData) ? parsedData : [parsedData];
                            
                            return sdArray.map(sd => {
                                return sd.slot ? sd.slot : '-';
                            }).join('<hr class="array-separator">');
                        } catch(e) {
                            return '-';
                        }
                    }
                },
                { 
                    data: 'slot_data', 
                    width: "20%",
                    render: function(data) {
                        try {
                            let parsedData = JSON.parse(data);
                            let sdArray = Array.isArray(parsedData) ? parsedData : [parsedData];
                            
                            return sdArray.map(sd => {
                                if (sd.type) {
                                    let typeText = sd.type.replace('_', ' ');
                                    return typeText.charAt(0).toUpperCase() + typeText.slice(1);
                                }
                                return '-';
                            }).join('<hr class="array-separator">');
                        } catch(e) {
                            return '-';
                        }
                    }
                },
                { 
                    data: 'slot_data', 
                    width: "15%",
                    render: function(data) {
                        try {
                            let parsedData = JSON.parse(data);
                            let sdArray = Array.isArray(parsedData) ? parsedData : [parsedData];
                            
                            return sdArray.map(sd => {
                                return (sd.value !== null && sd.value !== '') ? sd.value : '-';
                            }).join('<hr class="array-separator">');
                        } catch(e) {
                            return '-';
                        }
                    }
                }
            ]
        });
    }
</script>