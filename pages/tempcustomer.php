<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            
            <div class="page-header d-flex justify-content-between align-items-center">
                <h1 class="page-title">Temporary Users</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Temporary Customers & Drivers</li>
                    </ol>
                </div>
            </div>
            
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="form-label fw-bold text-muted mb-1">Select Date</label>
                            <div id="dateFilter" class="form-control d-flex justify-content-between align-items-center cursor-pointer bg-light">
                                <div><i class="fa fa-calendar text-primary me-2"></i><span class="date-text text-dark fw-semibold"></span></div>
                                <i class="fa fa-caret-down text-muted"></i>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="form-label fw-bold text-muted mb-1">User Type</label>
                            <select id="userTypeFilter" class="form-select bg-light text-dark fw-semibold" onchange="loadTempCustomers()">
                                <option value="">All Users</option>
                                <option value="driver">Driver App</option>
                                <option value="customer">Customer App</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label fw-bold text-muted mb-1">Search</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa fa-search text-muted"></i></span>
                                <input type="text" id="searchKeyword" class="form-control border-start-0 bg-light" placeholder="Phone / Name / Email">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button id="applyFiltersBtn" class="btn btn-primary w-100 fw-bold shadow-sm" onclick="loadTempCustomers()">
                                Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h3 class="card-title fw-bold text-dark mb-0">Users List</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tempCustomersTable" class="table table-bordered table-hover text-nowrap border-bottom w-100">
                            <thead class="bg-light">
                                <tr>
                                    <th class="wd-15p border-bottom-0 text-muted fw-bold">CREATED ON</th>
                                    <th class="wd-15p border-bottom-0 text-muted fw-bold">TYPE</th>
                                    <th class="wd-20p border-bottom-0 text-muted fw-bold">FULL NAME</th>
                                    <th class="wd-15p border-bottom-0 text-muted fw-bold">PHONE NO.</th>
                                    <th class="wd-25p border-bottom-0 text-muted fw-bold">E-MAIL</th>
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

<script>
    $(document).ready(function() {
        initDateRangePicker('dateFilter');
        loadTempCustomers(); // Load data initially

        // Trigger search on "Enter" key in search box
        $('#searchKeyword').on('keypress', function(e) {
            if (e.which == 13) {
                loadTempCustomers();
            }
        });
    });

    // Initialize Date Range Picker
    function initDateRangePicker(id) {
        if ($('#' + id).length === 0) return;
        
        var start = moment().startOf('month');
        var end = moment().endOf('month');
        
        function cb(start, end) {
            $('#' + id + ' .date-text').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
        }
        
        $('#' + id).daterangepicker({
            startDate: start,
            endDate: end,
            timePicker: false,
            maxSpan: { days: 365 },
            autoUpdateInput: true,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);
        
        cb(start, end);
    }

    // Load Data into DataTable
    function loadTempCustomers() {
        let formdate = '';
        let todate = '';
        let search = $('#searchKeyword').val().trim();
        let userType = $('#userTypeFilter').val(); // Get new filter value
        
        if ($('#dateFilter').length > 0) {
            let drp = $('#dateFilter').data('daterangepicker');
            if (drp && drp.startDate) {
                formdate = drp.startDate.format("YYYY-MM-DD");
                todate = drp.endDate.format("YYYY-MM-DD");
            }
        }

        var table = $('#tempCustomersTable').DataTable();
        table.destroy();
        
        $('#tempCustomersTable').DataTable({
            pageLength: 10,
            order: [[ 0, 'desc' ]], // Sort by Created Date descending
            paging: true,
            searching: false, // We use custom search bar
            info: true,
            ajax: {
                url: window.location.origin + "/ajax/service/temp_customer_services.php", // Adjust path if needed
                method: "POST",
                data: {
                    method: 'get_temp_customers',
                    startDate: formdate,
                    endDate: todate,
                    search: search,
                    userType: userType // Pass to backend
                }
            },
            dom: '<"d-flex justify-content-between align-items-center mb-3"l<"d-flex gap-2"B>>rt<"d-flex justify-content-between align-items-center mt-3"ip>',
            buttons: [
                { extend: 'copy', className: 'btn btn-outline-secondary btn-sm fw-bold' },
                { extend: 'excelHtml5', className: 'btn btn-outline-success btn-sm fw-bold', title: 'Temporary Users Report' }
            ],
            columns: [
                { 
                    data: "created_at",
                    render: function(data) { return `<span class="text-muted fw-semibold">${data}</span>`; }
                },
                { 
                    data: "user_type",
                    render: function(data) { 
                        let badgeClass = data === 'Driver' ? 'bg-success' : 'bg-info';
                        return `<span class="badge ${badgeClass} fs-12 px-2 py-1">${data}</span>`; 
                    }
                },
                { 
                    data: "full_name",
                    render: function(data) { return `<span class="text-dark fw-bold">${data}</span>`; }
                },
                { 
                    data: "mobile",
                    render: function(data) { return `<span class="text-primary fw-semibold">${data}</span>`; }
                },
                { 
                    data: "email"
                }
            ]
        });
    }
</script>