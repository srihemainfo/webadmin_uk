<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"/>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* Greyish Theme & Centered Styling */
    body, .main-content { background-color: #f4f5f7; }
    .card.border-0.shadow-sm { border-radius: 12px; overflow: hidden; background: #ffffff; border: 1px solid #e2e8f0 !important; }
    .card-header { background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; border-top-left-radius: 12px !important; border-top-right-radius: 12px !important; }
    
    /* Top Stats Widget & Scroll Optimization */
    .top-stats-scroll {
        max-height: 280px;
        overflow-y: auto;
        overflow-x: hidden;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f8fafc;
        padding-right: 5px;
    }
    .top-stats-scroll::-webkit-scrollbar { width: 6px; }
    .top-stats-scroll::-webkit-scrollbar-track { background: #f8fafc; border-radius: 8px; }
    .top-stats-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }

    .stat-box { background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px 15px; text-align: center; transition: 0.3s; }
    .stat-box:hover { background: #e2e8f0; border-color: #cbd5e1; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
    .stat-title { font-size: 13px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.4; }
    .stat-count { font-size: 28px; color: #2563eb; font-weight: 700; margin-top: 8px; }

    /* Inputs & Table */
    .form-control, .form-select, .select2-container .select2-selection--single { border-radius: 6px; border: 1px solid #cbd5e1; background-color: #f8fafc; height: 38px; }
    .table-responsive { padding: 0 15px 15px 15px; }
    table.dataTable { border-collapse: separate !important; border-spacing: 0; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; width: 100% !important; }
    table.dataTable thead th { background-color: #f1f5f9; border-bottom: 1px solid #e2e8f0 !important; border-right: 1px solid #e2e8f0 !important; color: #475569; font-weight: 600; padding: 12px 10px; text-transform: uppercase; font-size: 12px; text-align: center; }
    table.dataTable tbody td { border-bottom: 1px solid #e2e8f0 !important; border-right: 1px solid #e2e8f0 !important; padding: 12px 10px; color: #334155; vertical-align: middle; text-align: center; font-size: 13px; }
    table.dataTable thead th:last-child, table.dataTable tbody td:last-child { border-right: none !important; }
    table.dataTable tbody tr:last-child td { border-bottom: none !important; }
    
    /* Clean Loader Override */
    div.dataTables_wrapper div.dataTables_processing { background: rgba(255,255,255,0.9) !important; border: none !important; box-shadow: none !important; z-index: 10; margin-top: -20px; }
    
    /* Buttons */
    .btn-action { background-color: #64748b; color: white; border: none; border-radius: 6px; padding: 6px 12px; font-size: 12px; font-weight: 500; transition: 0.2s; text-align: center; display: inline-flex; justify-content: center; align-items: center; gap: 5px; width: 100%; }
    .btn-action:hover { background-color: #475569; color: white; }
    
    .btn-search { background-color: #2563eb; color: white; border: none; border-radius: 6px; height: 38px; font-weight: 600; width: 100%; transition: 0.3s;}
    .btn-search:hover { background-color: #1d4ed8; color: white; }

    /* Modal Specific Styles */
    #detailsTable th { background-color: #e2e8f0; font-size: 11px; }
    #detailsTable td { font-size: 12px; }
</style>

<div class="main-content app-content mt-0">
<div class="side-app" style="
    margin-top: 50px;
">
        <div class="main-container container-fluid">
            
            <div class="row top-stats-scroll" id="topStatsContainer">
            </div>

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header p-4">
                    <div class="row w-100 g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="mb-1 text-muted" style="font-weight: 600; font-size: 12px;">Filter by State</label>
                            <select id="filterState" class="form-select select2-init">
                                <option value="">Select State</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="mb-1 text-muted" style="font-weight: 600; font-size: 12px;">Filter by District</label>
                            <select id="filterDistrict" class="form-select select2-init">
                                <option value="">Select District</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="mb-1 text-muted" style="font-weight: 600; font-size: 12px;">Location Status</label>
                            <select id="filterStatus" class="form-select">
                                <option value="ALL">All Customers</option>
                                <option value="HAS_STATE">Has State/District</option>
                                <option value="NO_STATE">Missing Location Info</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="mb-1 text-muted" style="font-weight: 600; font-size: 12px;">Registration Date</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="fa fa-calendar"></i></span>
                                <input type="text" id="logDateRange" class="form-control" placeholder="Select Date" readonly style="cursor: pointer; background: #fff;">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button id="searchBtn" class="btn btn-search"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0 pt-3">
                    <div class="table-responsive">
                        <table id="customerTargetTable" class="table table-hover align-middle mb-0" style="width:100%;">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">S.NO</th>
                                    <th>CUSTOMER NAME</th>
                                    <th>MOBILE / DETAILS</th>
                                    <th>STATE</th>
                                    <th>DISTRICT</th>
                                    <th>TOTAL ACTIVITIES</th>
                                    <th style="width: 140px;">ACTION</th>
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

<div class="modal fade" id="activityLogsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background-color: #f1f5f9; border-bottom: 1px solid #e2e8f0;">
                <h5 class="modal-title fw-bold text-secondary" id="modalDynamicTitle">Customer Activity Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-white">
                <div class="table-responsive p-0">
                    <table class="table table-bordered table-hover align-middle" id="detailsTable">
                        <thead>
                            <tr>
                                <th style="width: 50px;">S.NO</th>
                                <th>MODULE</th>
                                <th>ACTION / EVENT</th>
                                <th>META DETAILS (FROM -> TO)</th>
                                <th>IP ADDRESS</th>
                                <th>DATE & TIME</th>
                            </tr>
                        </thead>
                        <tbody id="activityModalBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    
    $('.select2-init').select2({ width: '100%' });
    
    let startDate = '';
    let endDate = '';
    let isSearchClicked = false; // Add this flag to track if search was clicked

    $('#logDateRange').daterangepicker({
        autoUpdateInput: false,
        opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
        },
        locale: { format: 'DD/MM/YYYY', cancelLabel: 'Clear' }
    });

    $('#logDateRange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        startDate = picker.startDate.format('YYYY-MM-DD');
        endDate = picker.endDate.format('YYYY-MM-DD');
    });

    $('#logDateRange').on('cancel.daterangepicker', function() {
        $(this).val('');
        startDate = ''; endDate = '';
    });

    // 1. OPTIMIZED BATCH LOADING TOP STATS (INFINITE SCROLL)
    let statsOffset = 0;
    let statsLimit = 12; 
    let isStatsLoading = false;
    let hasMoreStats = true;

    function loadTopStats(append = false) {
        if (isStatsLoading || !hasMoreStats) return;
        isStatsLoading = true;

        let loaderHtml = `<div class="col-12 text-center py-3" id="statsLoader">
                            <i class="fa fa-circle-o-notch fa-spin fa-2x text-primary"></i>
                          </div>`;

        if (!append) {
            $('#topStatsContainer').html(loaderHtml);
            statsOffset = 0;
            hasMoreStats = true;
        } else {
            $('#topStatsContainer').append(loaderHtml);
        }

        $.post(window.location.origin + "/ajax/service/customer_target_services.php", { 
            method: 'get_top_counts',
            offset: statsOffset,
            limit: statsLimit
        }, function(res) {
            $('#statsLoader').remove();
            
            if(res.status && res.data.length > 0) {
                let html = '';
                res.data.forEach(item => {
                    html += `
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                        <div class="stat-box shadow-sm h-100 d-flex flex-column justify-content-center">
                            <div class="stat-title">${item.district}, ${item.state}</div>
                            <div class="stat-count">${item.total}</div>
                        </div>
                    </div>`;
                });

                if (!append) {
                    $('#topStatsContainer').html(html);
                } else {
                    $('#topStatsContainer').append(html);
                }

                statsOffset += statsLimit;
                if (res.data.length < statsLimit) {
                    hasMoreStats = false; 
                }
            } else {
                if (!append) $('#topStatsContainer').html('<div class="col-12 text-center text-muted">No location data available.</div>');
                hasMoreStats = false;
            }
            isStatsLoading = false;
        }, 'json').fail(function() {
            $('#statsLoader').remove();
            isStatsLoading = false;
        });
    }

    $('#topStatsContainer').on('scroll', function() {
        if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight - 10) {
            loadTopStats(true);
        }
    });

    // 2. Fetch Filter Options
    function loadFilters() {
        $.post(window.location.origin + "/ajax/service/customer_target_services.php", { method: 'get_filter_options' }, function(res) {
            if(res.status) {
                res.states.forEach(state => { $('#filterState').append(new Option(state, state)); });
                res.districts.forEach(district => { $('#filterDistrict').append(new Option(district, district)); });
            }
        }, 'json');
    }

    loadTopStats();
    loadFilters();

    // 3. Initialize Main DataTable (Empty until searched)
    let targetTable = $('#customerTargetTable').DataTable({
        processing: true,
        serverSide: true,
        deferLoading: 0, // Prevents initial AJAX call
        ajax: {
            url: window.location.origin + "/ajax/service/customer_target_services.php",
            type: "POST",
            data: function(d) {
                // Return early if search hasn't been clicked yet
                if (!isSearchClicked) {
                    return false;
                }

                d.method = 'get_targeted_customers';
                d.state = $('#filterState').val();
                d.district = $('#filterDistrict').val();
                d.loc_status = $('#filterStatus').val();
                d.startDate = startDate;
                d.endDate = endDate;
            }
        },
        columns: [
            { data: "sno", orderable: false, searchable: false },
            { data: "name", className: "text-start fw-bold text-dark" },
            { data: "mobile", className: "text-start" },
            { data: "state" },
            { data: "district" },
            { data: "log_count", searchable: false },
            { data: "action", orderable: false, searchable: false }
        ],
        order: [[5, 'desc']], 
        pageLength: 25,
        language: { 
            searchPlaceholder: "Search Name, Mobile...", 
            processing: '<i class=""></i>', // Changed to spinner
            emptyTable: "Please select filters and Search to load targets."
        }
    });

    // Trigger explicit search on button click
    $('#searchBtn').on('click', function() {
        isSearchClicked = true; // Set flag to true so AJAX request proceeds
        targetTable.ajax.reload(); // Reload the table data
    });

    // 4. View Activity Modal Logic
    let activityTable = null;

    $('#customerTargetTable tbody').on('click', '.view-activity-btn', function() {
        let userId = $(this).data('userid');
        let userName = $(this).data('username');
        let btn = $(this);
        let originalText = btn.html();
        
        btn.html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);
        $('#modalDynamicTitle').html(`Activity Logs for <span class="text-primary">${userName}</span>`);

        $.ajax({
            url: window.location.origin + "/ajax/service/customer_target_services.php",
            type: "POST",
            data: { method: 'get_customer_activity', user_id: userId },
            success: function(response) {
                btn.html(originalText).prop('disabled', false); 
                
                if (activityTable !== null) {
                    activityTable.destroy();
                }

                let tbody = $('#activityModalBody');
                tbody.empty();

                if(response.status && response.data.length > 0) {
                    $.each(response.data, function(index, row) {
                        let tr = `<tr>
                            <td>${row.sno}</td>
                            <td><span class="badge bg-secondary">${row.module}</span></td>
                            <td class="fw-bold">${row.action}</td>
                            <td class="text-start" style="font-size: 11px;">
                                ${row.from_loc ? `<div class="mb-1"><b>From:</b> ${row.from_loc}</div>` : ''}
                                ${row.to_loc ? `<div><b>To:</b> ${row.to_loc}</div>` : ''}
                                ${(!row.from_loc && !row.to_loc) ? '<span class="text-muted">No Meta Data</span>' : ''}
                            </td>
                            <td><small>${row.ip}</small></td>
                            <td class="text-nowrap">${row.date}</td>
                        </tr>`;
                        tbody.append(tr);
                    });
                }

                activityTable = $('#detailsTable').DataTable({
                    pageLength: 10,
                    lengthMenu: [10, 25, 50],
                    order: [],
                    language: { emptyTable: "No activity logged for this user." }
                });

                var myModal = new bootstrap.Modal(document.getElementById('activityLogsModal'));
                myModal.show();
            },
            error: function() {
                alert('Something went wrong.');
                btn.html(originalText).prop('disabled', false);
            }
        });
    });
});
</script>
