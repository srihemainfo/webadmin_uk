<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"/>

<style>
    .card.border-0.shadow-sm { border-radius: 12px; overflow: hidden; }
    .card-header { border-bottom: 1px solid #edf2f9; border-top-left-radius: 12px !important; border-top-right-radius: 12px !important; }
    .form-control, .form-select { border-radius: 6px; border: 1px solid #ced4da; }
    .table-responsive { padding: 0 15px 15px 15px; }
    table.dataTable { border-collapse: separate !important; border-spacing: 0; border: 1px solid #dee2e6; border-radius: 8px; overflow: hidden; }
    table.dataTable thead th { background-color: #f8f9fa; border-bottom: 1px solid #dee2e6 !important; border-right: 1px solid #dee2e6 !important; color: #495057; font-weight: 600; padding: 12px 10px; }
    table.dataTable tbody td { border-bottom: 1px solid #dee2e6 !important; border-right: 1px solid #dee2e6 !important; padding: 12px 10px; color: #333; vertical-align: middle; }
    table.dataTable thead th:last-child, table.dataTable tbody td:last-child { border-right: none !important; }
    table.dataTable tbody tr:last-child td { border-bottom: none !important; }
    .dataTables_wrapper .dataTables_filter input { border-radius: 6px; border: 1px solid #ced4da; padding: 4px 10px; margin-left: 8px; }
    .dataTables_wrapper .dataTables_length select { border-radius: 6px; border: 1px solid #ced4da; padding: 4px 30px 4px 10px; }
    #detailsTable th, #detailsTable td { font-size: 13px; }
    #detailsTable th { background-color: #f1f5f9; }
</style>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            
            <div class="page-header d-flex justify-content-between align-items-center mt-3 mb-4">
                <h1 class="page-title m-0 fw-bold">District-wise Search Log</h1>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light p-4">
                    <div class="row w-100 align-items-end gap-3">
                        <div class="col-md-3">
                            <label class="mb-2 text-muted" style="font-weight: 600; font-size: 13px;">Search Type</label>
                            <select id="searchTypeFilter" class="form-select bg-white">
                                <option value="From" selected>From District</option>
                                <option value="To">To District</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-2 text-muted" style="font-weight: 600; font-size: 13px;">Filter Searches by Date</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa fa-calendar"></i></span>
                                <input type="text" id="logDateRange" class="form-control border-start-0" placeholder="Select Date Range" readonly style="cursor: pointer; background: #fff;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0 pt-3">
                    <div class="table-responsive">
                        <table id="districtReportTable" class="table table-hover align-middle mb-0" style="width:100%; font-size: 14px;">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 80px;">S.NO</th>
                                    <th id="dynamicDistrictHeader">FROM DISTRICT</th>
                                    <th class="text-center">TOTAL SEARCHES</th>
                                    <th class="text-center">ACTION</th>
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

<!-- Customer Details Modal -->
<div class="modal fade" id="customerDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="modalDynamicTitle">Detailed Customer Search Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle w-100" id="detailsTable">
                        <thead>
                            <tr>
                                <th style="width: 50px;" class="text-center">S.NO</th>
                                <th>CUSTOMER NAME</th>
                                <th>MOBILE</th>
                                <th style="width: 20%;">FROM LOCATION</th>
                                <th style="width: 20%;">TO LOCATION</th>
                                <th>DATE & TIME</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    let startDate = moment().format('YYYY-MM-DD');
    let endDate = moment().format('YYYY-MM-DD');
    let searchType = 'From'; 
    let detailsTableInstance = null; 

    $('#logDateRange').daterangepicker({
        startDate: moment(), endDate: moment(), autoUpdateInput: true, opens: 'right',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')]
        },
        locale: { format: 'DD/MM/YYYY' }
    });

    $('#logDateRange').val(moment().format('DD/MM/YYYY') + ' - ' + moment().format('DD/MM/YYYY'));

    $('#logDateRange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        startDate = picker.startDate.format('YYYY-MM-DD');
        endDate = picker.endDate.format('YYYY-MM-DD');
        districtTable.ajax.reload();
        if(detailsTableInstance) { detailsTableInstance.ajax.reload(); }
    });

    $('#searchTypeFilter').on('change', function() {
        searchType = $(this).val();
        $('#dynamicDistrictHeader').text(searchType.toUpperCase() + ' DISTRICT');
        districtTable.ajax.reload();
    });

    let districtTable = $('#districtReportTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 10,
        ajax: {
            url: window.location.origin + "/ajax/service/district_report_services.php", 
            type: "POST",
            data: function(d) {
                d.method = 'get_district_reports';
                d.startDate = startDate;
                d.endDate = endDate;
                d.searchType = searchType; 
            }
        },
        columns: [
            { data: "sno", className: "text-center fw-bold text-muted", orderable: false },
            { data: "district_name", className: "fw-bold text-dark", orderable: false },
            { data: "search_count", className: "text-center", orderable: false },
            { data: "action", className: "text-center", orderable: false }
        ],
        language: { searchPlaceholder: "Search districts..." }
    });

    // View Customers Button Click
    $('#districtReportTable tbody').on('click', '.view-details-btn', function() {
        let currentDistrict = $(this).data('district');
        let currentSearchType = $(this).data('searchtype'); 
        
        $('#modalDynamicTitle').html(`Detailed Customer Logs <span class="badge bg-secondary ms-2">${currentSearchType} District: ${currentDistrict}</span>`);

        if (detailsTableInstance) {
            detailsTableInstance.destroy();
        }

        detailsTableInstance = $('#detailsTable').DataTable({
            processing: true,
            serverSide: true, 
            pageLength: 10,
            ajax: {
                url: window.location.origin + "/ajax/service/district_report_services.php",
                type: "POST",
                data: function(d) {
                    d.method = 'get_district_report_details';
                    d.district_name = currentDistrict;
                    d.search_type = currentSearchType; 
                    d.startDate = startDate;
                    d.endDate = endDate;
                }
            },
            columns: [
                { data: "sno", className: "text-center", orderable: false },
                { data: "name", orderable: false },
                { data: "mobile", orderable: false },
                { data: "from_loc", orderable: false },
                { data: "to_loc", orderable: false },
                { data: "date", orderable: false }
            ],
            language: { searchPlaceholder: " Search..." }
        });

        var myModal = new bootstrap.Modal(document.getElementById('customerDetailsModal'));
        myModal.show();
    });
});
</script>