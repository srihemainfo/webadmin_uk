<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Completed Jobs Report</title>
    <!-- Core Dependencies -->
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- Standard DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables & Moment JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <!-- DataTables Excel/Copy Export JS -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
</head>

<style>
    body { background-color: #f4f7fc; }
    .back-arrow-btn i {
        background: #ffffff; font-size: 16px; padding: 2px 3px;
        border-radius: 50px; border: 2px solid #6c6e70; color: #6c6e70;
        margin-right: 15px; width: 24px; height: 24px;
        display: inline-flex; align-items: center; justify-content: center;
        transition: 0.3s;
    }
    .back-arrow-btn i:hover { background: #6c6e70; color: #fff; }
    .table-container {
        border: 1px solid #e0e0e0; padding: 20px;
        background: #fff; border-radius: 8px; 
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    
    .dt-buttons { margin-bottom: 15px; }
    .table thead th { font-size: 13px; font-weight: 600; color: #495057; background-color: #f8f9fa; text-transform: uppercase; white-space: nowrap; }
    .table tbody td { font-size: 14px; color: #333; vertical-align: middle; }
    .detail-text { font-size: 12px; color: #6c757d; display: block; margin-top: 3px; }
    .amount { font-weight: 600; }
    .text-success { color: #198754 !important; }
    .text-primary { color: #0d6efd !important; }
    .text-danger { color: #dc3545 !important; }
    .text-warning-custom { color: #d97706 !important; } /* Distinct color for tax */
    
    .dataTables_filter { float: right; margin-bottom: 15px; }
</style>

<body>
<div class="main-content app-content mt-4">
    <div class="side-app">
        <div class="main-container container-fluid">
            <div class="page-header align-items-center mb-4 d-flex justify-content-between">
                <h1 class="page-title m-0 d-flex align-items-center fs-4">
                    <a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)"></i></a> 
                    Completed Jobs Financial Report
                </h1>
            </div>

            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="reportsTable" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Job No</th>
                                <th>Customer Details</th>
                                <th>Driver Details</th>
                                <th>Customer Paid</th>
                                <th>Commission</th>
                                <th>Tax</th>
                                <th>Driver Earned</th>
                                <th>Status</th>
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
    $.fn.dataTable.ext.errMode = 'none';
    var reportApiUrl = window.location.origin + "/ajax/service/completed_jobs_service.php"; 

    $(document).ready(function() { 
        var reportTable = $("#reportsTable").on('error.dt', function(e, settings, techNote, message) {
            console.error('DataTables Error: ', message);
            alert("Failed to load data. Please check the console or ensure the backend service path is correct.");
        }).DataTable({
            destroy: true, 
            autoWidth: false, 
            order: [[0, 'desc']], 
            
            dom: 'Bfrtip',
            lengthMenu: [
                [10, 25, 50, -1],
                ['10 rows', '25 rows', '50 rows', 'Show all']
            ],
            
            buttons: [
                'pageLength', 
                'copy',       
                {
                    extend: 'excelHtml5',
                    text: 'Excel', 
                    exportOptions: {
                        // Added index 8 to include the new Tax column in Excel
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
                        format: {
                            body: function (data, row, column, node) {
                                return data.replace(/<[^>]*>?/gm, ' ').replace(/&nbsp;/g, ' ').trim();
                            }
                        }
                    }
                }
            ],
            
            language: { emptyTable: "No completed jobs found." },
            ajax: { 
                url: reportApiUrl, 
                type: "POST", 
                dataSrc: "data", 
                data: { method: 'fetch_completed_reports' } 
            },
            columns: [
                { 
                    data: 'pickup_date', 
                    render: function(data, type, row) {
                        // FIX: Give DataTables the raw YYYY-MM-DD string for chronological sorting
                        if (type === 'sort' || type === 'type') {
                            return data; 
                        }
                        // Keep the nice format for display
                        return data ? moment(data).format('DD MMM YYYY, hh:mm A') : '-';
                    }
                },
                { 
                    data: 'job_no',
                    render: function(data) {
                        return '<strong>' + data + '</strong>';
                    }
                },
                { 
                    data: 'customer_name', 
                    render: function(data, type, row) {
                        let name = data ? data : 'Unknown Customer';
                        let mobile = row.customer_mobile ? row.customer_mobile : 'No Number';
                        return `<span>${name}</span><span class="detail-text"><i class="fa fa-phone"></i> ${mobile}</span>`;
                    }
                },
                { 
                    data: 'driver_name', 
                    render: function(data, type, row) {
                        if (!data) return '<span class="text-muted">Unassigned</span>';
                        let mobile = row.driver_mobile ? row.driver_mobile : 'No Number';
                        return `<span>${data}</span><span class="detail-text"><i class="fa fa-phone"></i> ${mobile}</span>`;
                    }
                },
                { 
                    data: 'customer_paid', 
                    className: "text-end",
                    render: function(data) {
                        return `<span class="amount text-primary">${parseFloat(data).toFixed(2)}</span>`;
                    }
                },
                { 
                    data: 'commission', 
                    className: "text-end",
                    render: function(data) {
                        return `<span class="amount text-danger">${parseFloat(data).toFixed(2)}</span>`;
                    }
                },
                { 
                    data: 'tax', 
                    className: "text-end",
                    render: function(data) {
                        return `<span class="amount text-warning-custom">${parseFloat(data).toFixed(2)}</span>`;
                    }
                },
                { 
                    data: 'driver_earned', 
                    className: "text-end",
                    render: function(data) {
                        return `<span class="amount text-success">${parseFloat(data).toFixed(2)}</span>`;
                    }
                },
                { 
                    data: 'job_status', 
                    className: "text-center",
                    render: function(data) {
                        return `<span class="badge bg-success">COMPLETED</span>`;
                    }
                }
            ]
        });
    });
</script>
</body>
</html>