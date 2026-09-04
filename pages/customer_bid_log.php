<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>
    /* Make headers center correctly and ensure standard dataTable styling */
    table.dataTable thead th {
        vertical-align: middle;
    }
</style>
<style>
    /* Make headers center correctly and darker */
    table.dataTable thead th {
        vertical-align: middle;
        color: #111827 !important; /* Very dark gray/black */
        font-weight: 700 !important; 
        text-transform: uppercase;
        font-size: 13px;
    }

    /* Make table row text darker and bolder */
    #jobsTable tbody td {
        color: #374151 !important; /* Dark gray */
        font-weight: 600 !important;
        font-size: 14px;
    }

    /* Darken the "text-muted" mobile numbers so they don't wash out */
    #jobsTable .text-muted {
        color: #4b5563 !important; 
        font-weight: 500 !important;
    }

    /* Make filter dropdowns and inputs pop */
    .custom-dark-filter {
        color: #111827 !important;
        font-weight: 600 !important;
        border: 1px solid #9ca3af !important;
    }
    
    .custom-dark-filter:focus {
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    }
</style>
<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            
            <div class="page-header">
                <h1 class="page-title">Customer Bid Log</h1>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-bottom-0 d-flex flex-wrap justify-content-between align-items-center">
                            <h3 class="card-title mb-2 mb-md-0">Customer Bid Log List</h3>
                            
                            <div class="d-flex gap-2 align-items-center">
    <select id="jobStatusFilter" class="form-select form-select-sm custom-dark-filter" style="width: 130px;">
        <option value="">All Status</option>
        <option value="created">Created</option>
        <option value="accept">Accepted</option>
    </select>

    <select id="dateFilterType" class="form-select form-select-sm custom-dark-filter" style="width: 150px;">
        <option value="created_at">Created Date</option>
        <option value="pickup_date">Pickup Date</option>
    </select>
    
    <div class="input-group input-group-sm" style="width: 250px;">
        <span class="input-group-text bg-light custom-dark-filter border-end-0"><i class="fa fa-calendar text-dark"></i></span>
        <input type="text" id="dateRangeFilter" class="form-control custom-dark-filter border-start-0" placeholder="Select Date Range" readonly style="cursor: pointer; background: #fff;">
    </div>
    <button id="clearFilterBtn" class="btn btn-sm btn-danger d-none fw-bold"><i class="fa fa-times"></i></button>
</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="jobsTable" style="width:100%;">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-center align-middle" width="5%">S.No</th>
                                            <th class="align-middle" width="15%">Job No</th>
                                            <th class="align-middle" width="20%">Customer Details</th>
                                            <th class="align-middle" width="25%">Route</th>
                                            <th class="align-middle" width="15%">Pickup Time</th>
                                            <th class="align-middle" width="10%">Status</th>
                                            <th class="text-center align-middle" width="10%">Action</th>
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

<div class="modal fade" id="viewBidsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between align-items-center">
        <h5 class="modal-title fw-bold" id="modalJobNoTitle">Loading...</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.5rem; line-height: 1; color: #000; opacity: 0.5; cursor: pointer;">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-4">
          
          <div id="modalLoader" class="text-center py-5 d-none">
              <p class="fw-bold" style="font-size: 18px;"><i class="fa fa-spinner fa-spin"></i> Fetching Bids...</p>
          </div>

          <div class="table-responsive" id="modalTableContainer">
              <table class="table table-bordered table-hover text-nowrap" id="bidsTable" style="width:100%;">
                  <thead class="bg-light">
                      <tr>
                          <th style="width: 5%;" class="text-center align-middle">#</th>
                          <th style="width: 25%;" class="align-middle">Driver Details</th>
                          <th style="width: 15%;" class="text-center align-middle">Bid Amount</th>
                          <th style="width: 15%;" class="text-center align-middle">Status</th>
                          <th style="width: 40%;" class="align-middle">Remark</th>
                      </tr>
                  </thead>
                  <tbody id="bidsTableBody">
                  </tbody>
              </table>
          </div>
          
      </div>
      <div class="modal-footer bg-light border-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close Window</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>

<script>
    // Initialize Firebase
    const firebaseConfig = {
        apiKey: "AIzaSyCiRKGU2xZyZNx5-ZwweLd5cPokxJjxKzw",
        authDomain: "goride-947ed.firebaseapp.com",
        projectId: "goride-947ed",
    };
    
    if (!firebase.apps.length) {
        firebase.initializeApp(firebaseConfig);
    }
    const db = firebase.firestore();

    var ajaxUrl = "ajax/service/bid_log_services.php"; 

    $(document).ready(function() {
        
        window.selectedStartDate = '';
        window.selectedEndDate = '';

        // Initialize Datepicker
        $('#dateRangeFilter').daterangepicker({
            autoUpdateInput: false,
            locale: { cancelLabel: 'Clear', format: 'YYYY-MM-DD' },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'All Time': [moment('2020-01-01'), moment().add(10, 'years')]
            }
        });

        $('#dateRangeFilter').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
            window.selectedStartDate = picker.startDate.format('YYYY-MM-DD');
            window.selectedEndDate = picker.endDate.format('YYYY-MM-DD');
            $('#clearFilterBtn').removeClass('d-none');
            $('#jobsTable').DataTable().ajax.reload();
        });

        $('#clearFilterBtn, #dateRangeFilter').on('cancel.daterangepicker', function() {
            $('#dateRangeFilter').val('');
            window.selectedStartDate = '';
            window.selectedEndDate = '';
            $('#clearFilterBtn').addClass('d-none');
            $('#jobsTable').DataTable().ajax.reload();
        });

        $('#clearFilterBtn').on('click', function(){
            $('#dateRangeFilter').val('');
            window.selectedStartDate = '';
            window.selectedEndDate = '';
            $(this).addClass('d-none');
            $('#jobsTable').DataTable().ajax.reload();
        });

        // Trigger reload on filter change
        $('#dateFilterType, #jobStatusFilter').on('change', function() {
            $('#jobsTable').DataTable().ajax.reload();
        });

        // 1. Initialize Main DataTable
        var table = $('#jobsTable').DataTable({
            processing: true, 
            serverSide: true,
            ajax: { 
                url: ajaxUrl, 
                type: "POST", 
                data: function(d) {
                    d.method = "fetch_all_jobs";
                    d.startDate = window.selectedStartDate;
                    d.endDate = window.selectedEndDate;
                    d.filterType = $('#dateFilterType').val();
                    d.jobStatusFilter = $('#jobStatusFilter').val(); // New Status Filter
                }
            },
            columns: [
                { data: null, className: "text-center align-middle", render: function(data, type, row, meta) { return meta.row + 1; } },
                { 
                    data: "job_no", 
                    className: "align-middle fw-bold",
                    render: function(data, type, row) {
                        if ((data && data.startsWith('GRD')) || !row.preview_hash) {
                            return data;
                        } else {
                            let domain = window.location.origin;
                            return `<a href="https://www.goride.run/booking-information/${row.preview_hash}" class="text-primary text-decoration-none" target="_blank" title="View Booking Preview">${data}</a>`;
                        }
                    }
                },
                { 
                    data: null, 
                    className: "align-middle",
                    render: function(data) {
                        return `<strong class="text-dark">${data.customer_name}</strong><br>
                                <span class="text-muted" style="font-size:12px;">Mob: ${data.customer_mobile}</span>`;
                    }
                },
                { data: "route", className: "align-middle" },
                { data: "pickup_date", className: "align-middle" },
                { data: "status", className: "align-middle fw-bold" },
                { 
                    data: "id", 
                    className: "text-center align-middle",
                    render: function(data, type, row) {
                        let attr = row.bid_count > 0 ? '' : 'disabled';
                        let btnClass = row.bid_count > 0 ? 'btn-primary' : 'btn-secondary';
                        let safeFare = row.fare_breakdown ? encodeURIComponent(JSON.stringify(row.fare_breakdown)) : '';
                        
                        return `<button class="btn btn-sm ${btnClass} btn-view-bids" data-id="${data}" data-jobno="${row.job_no}" data-status="${row.status}" data-fare="${safeFare}" ${attr}><i class="fa fa-eye"></i> View Bids (<span class="bid-count-badge">${row.bid_count}</span>)</button>`;
                    } 
                }
            ],
            order: [[0, "desc"]] 
        });

        // Firebase COUNT SYNC
        $('#jobsTable').on('draw.dt', function () {
            $('.btn-view-bids').each(function() {
                let status = String($(this).data('status')).toLowerCase();
                if (status === 'created') {
                    let jobNo = $(this).data('jobno');
                    let $btn = $(this);
                    db.collection('<?= rtrim(FIREBASE_COLLECTION, "/") ?>').doc(jobNo).get().then(doc => {
                        let count = 0;
                        if (doc.exists) {
                            let data = doc.data();
                            if(data.bids_details) count = Object.keys(data.bids_details).length;
                        }
                        $btn.find('.bid-count-badge').text(count);
                        if (count > 0) {
                            $btn.prop('disabled', false).removeClass('btn-secondary').addClass('btn-primary');
                        } else {
                            $btn.prop('disabled', true).removeClass('btn-primary').addClass('btn-secondary');
                        }
                    }).catch(e => console.log('Firebase fetch error', e));
                }
            });
        });

        // 2. Open Modal and Fetch Bids (Firebase OR PHP)
        $('#jobsTable tbody').on('click', '.btn-view-bids', function () {
            let jobId = $(this).data('id');
            let jobNo = $(this).data('jobno');
            let jobStatus = String($(this).data('status')).toLowerCase();
            
            let fareDataStr = $(this).data('fare');
            let fareData = {};
            if (fareDataStr) {
                try { fareData = JSON.parse(decodeURIComponent(fareDataStr)); } catch(e){}
            }
            let finalBidderId = fareData.bidder_id ? String(fareData.bidder_id) : null;
            
            $('#modalJobNoTitle').text('Bids For: ' + jobNo);
            $('#bidsTableBody').empty();
            $('#modalTableContainer').addClass('d-none');
            $('#modalLoader').removeClass('d-none');
            
            var myModal = new bootstrap.Modal(document.getElementById('viewBidsModal'));
            myModal.show();

            if (jobStatus === 'created') {
                if (typeof db !== 'undefined') {
                    db.collection('<?= rtrim(FIREBASE_COLLECTION, "/") ?>').doc(jobNo).get().then(doc => {
                        $('#modalLoader').addClass('d-none');
                        $('#modalTableContainer').removeClass('d-none');
                        
                        if (!doc.exists) {
                            $('#bidsTableBody').html('<tr><td colspan="5" class="text-center py-4 fw-bold text-danger">Job not found in live database.</td></tr>');
                            return;
                        }

                        const data = doc.data();
                        const bids = data.bids_details || {};
                        let combinedBids = {};

                        Object.entries(bids).forEach(([dId, bidInfo]) => {
                            combinedBids[String(dId)] = {
                                driver_name: bidInfo.b_name || 'UNKNOWN DRIVER',
                                amount: bidInfo.amount || 0,
                                status: (bidInfo.status || 'PENDING').toUpperCase(),
                                remark: bidInfo.remark ? bidInfo.remark : '-',
                                is_final: false
                            };
                        });

                        if (finalBidderId && combinedBids[finalBidderId]) {
                            combinedBids[finalBidderId].is_final = true;
                        }

                        if (Object.keys(combinedBids).length === 0) {
                            $('#bidsTableBody').html('<tr><td colspan="5" class="text-center py-4 fw-bold text-muted">No bids found for this job.</td></tr>');
                            return;
                        }

                        let html = '';
                        let index = 1;
                        Object.values(combinedBids).forEach(bid => {
                            let bgClass = bid.is_final ? 'table-warning' : '';
                            let finalTag = bid.is_final ? '<br><strong class="text-danger" style="font-size: 11px;">[ FINAL VIEWED BID ]</strong>' : '';

                            html += `
                            <tr class="${bgClass}">
                                <td class="text-center align-middle">${index++}</td>
                                <td class="align-middle">
                                    <strong class="text-dark">${bid.driver_name}</strong>
                                    ${finalTag}
                                </td>
                                <td class="text-center align-middle fw-bold" style="font-size: 15px;">₹${bid.amount}</td>
                                <td class="text-center align-middle fw-bold">${bid.status}</td>
                                <td class="align-middle text-wrap" style="max-width: 250px;">${bid.remark}</td>
                            </tr>`;
                        });

                        $('#bidsTableBody').html(html);

                    }).catch(error => {
                        $('#modalLoader').addClass('d-none');
                        $('#modalTableContainer').removeClass('d-none');
                        $('#bidsTableBody').html('<tr><td colspan="5" class="text-center py-4 fw-bold text-danger">Error fetching bids from Firebase.</td></tr>');
                    });
                } else {
                    $('#modalLoader').addClass('d-none');
                    $('#modalTableContainer').removeClass('d-none');
                    $('#bidsTableBody').html('<tr><td colspan="5" class="text-center py-4 fw-bold text-danger">Firebase is not initialized.</td></tr>');
                }
            } 
            else {
                $.ajax({
                    url: ajaxUrl,
                    type: "POST",
                    dataType: "json",
                    data: { method: "fetch_job_bids", job_id: jobId },
                    success: function(res) {
                        $('#modalLoader').addClass('d-none');
                        $('#modalTableContainer').removeClass('d-none');

                        if (res.status === 'success') {
                            if (res.data.length === 0) {
                                $('#bidsTableBody').html('<tr><td colspan="5" class="text-center py-4 fw-bold text-muted">No bids found for this job.</td></tr>');
                            } else {
                                let html = '';
                                res.data.forEach((bid, index) => {
                                    let bgClass = bid.is_final ? 'table-warning' : '';
                                    let finalTag = bid.is_final ? '<br><strong class="text-danger" style="font-size: 11px;">[ FINAL VIEWED BID ]</strong>' : '';

                                    html += `
                                    <tr class="${bgClass}">
                                        <td class="text-center align-middle">${index + 1}</td>
                                        <td class="align-middle">
                                            <strong class="text-dark">${bid.driver_name}</strong><br>
                                            <span class="text-muted" style="font-size:12px;">Mob: ${bid.driver_mobile}</span>
                                            ${finalTag}
                                        </td>
                                        <td class="text-center align-middle fw-bold" style="font-size: 15px;">₹${bid.amount}</td>
                                        <td class="text-center align-middle fw-bold">${bid.status}</td>
                                        <td class="align-middle text-wrap" style="max-width: 250px;">${bid.remark}</td>
                                    </tr>`;
                                });
                                $('#bidsTableBody').html(html);
                            }
                        } else {
                            $('#bidsTableBody').html(`<tr><td colspan="5" class="text-center py-4 fw-bold text-danger">${res.message}</td></tr>`);
                        }
                    },
                    error: function() {
                        $('#modalLoader').addClass('d-none');
                        $('#modalTableContainer').removeClass('d-none');
                        $('#bidsTableBody').html('<tr><td colspan="5" class="text-center py-4 fw-bold text-danger">Server error while fetching bids.</td></tr>');
                    }
                });
            }
        });

    });
</script>