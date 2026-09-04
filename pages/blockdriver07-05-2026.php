<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Input and Button Defaults */
    input, button, select {
        height: 35px; margin: 0; padding: 6px 12px; border-radius: 4px;
        font-family: inherit; font-size: 14px; color: inherit; border: 1px solid #CCC;
    }
    button.btn-primary { color: #FFF; background-color: #428BCA; border: 1px solid #357EBD; }
    button.btn-primary:hover { background-color: #3276b1; }
    
    .back-arrow-btn i {
        background: #ffffff; font-size: 16px; padding: 2px 3px; border-radius: 50px;
        border: 2px solid #6c6e70; color: #6c6e70; margin-right: 15px;
        width: 24px; height: 24px; text-align: center; line-height: 18px;
    }
    
    .tab-content {
        border: 1px solid #ddd; padding: 25px 20px; background: #fff;
        border-radius: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .table thead th { text-transform: uppercase; font-size: 12px; font-weight: 600; color: #555; }
    .action-btns { display: flex; gap: 5px; justify-content: center; }
</style>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <br>
            <div class="page-header align-items-center mb-4">
                <h1 class="page-title m-0">
                    <a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)"></i></a> 
                    Block Drivers
                </h1>
            </div>

            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="tab-content">
                        <div class="row w-100 align-items-end mb-4">
                            <div class="col-lg-4 col-md-5 mb-3">
                                <span class="form-label mb-1" style="font-size: 13px; font-weight:600;">Search</span>
                                <input type="text" id="searchKeyword" class="form-control" placeholder="Search Name, Email, Mobile..." style="height: 35px; width: 100%;">
                            </div>
                            <div class="col-lg-2 col-md-3 mb-3">
                                <button type="button" class="btn btn-primary w-100 fw-bold" onclick="viewDriverTable()">Search</button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped border-bottom" id="driverTable" style="width:100%;">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th> 
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

<!-- History Modal with Close (X) Mark -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title fw-bold m-0">Driver Action History</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: none; font-size: 1.5rem; line-height: 1; cursor: pointer; color: #000; opacity: 0.5;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="historyTable">
                        <thead class="bg-light">
                            <tr>
                                <th>Date & Time</th>
                                <th>Action</th>
                                <th>Reason</th>
                                <th>Action By</th>
                            </tr>
                        </thead>
                        <tbody id="historyTbody">
                            <!-- Populated via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var datatableUrl = window.location.origin + "/ajax/service/datatable_services.php";
    var driverTableInstance;
    
    // Inject PHP logged-in admin memid
    var currentMemId = "<?= isset($_SESSION['memid']) ? $_SESSION['memid'] : '1' ?>"; 

    $(document).ready(function() {
        viewDriverTable();
        $('#searchKeyword').on('keypress', function(e) {
            if(e.which == 13) { viewDriverTable(); }
        });
    });

    function viewDriverTable() {
        var searchKeyword = $('#searchKeyword').val(); 

        driverTableInstance = $("#driverTable").DataTable({
            destroy: true,
            autoWidth: false, 
            pageLength: 10,
            order: [[0, 'desc']],
            language: { emptyTable: "No driver data available" },
            ajax: {
                url: datatableUrl,
                method: "POST",
                dataSrc: "", 
                data: { method: 'admin_get_driver_list', searchKeyword: searchKeyword }
            },
            columns: [
                { data: 'id' },
                { data: 'name', defaultContent: '-' },
                { data: 'email', defaultContent: '-' },
                { data: 'mobile', defaultContent: '-' },
                { 
                    data: 'status', // Explicitly targeting `status`
                    render: function(data) {
                        // 1 = Blocked, 0 = Active
                        return data == 1 ? '<span class="badge bg-danger">Blocked</span>' : '<span class="badge bg-success">Active</span>';
                    }
                },
                { 
                    data: null,
                    orderable: false,
                    className: "text-center",
                    render: function(data, type, row) {
                        var isBlocked = (row.status == 1); 
                        var newStatus = isBlocked ? 0 : 1;
                        var btnClass = isBlocked ? 'btn-success' : 'btn-danger';
                        var btnText = isBlocked ? 'Unblock' : 'Block';

                        return `
                            <div class="action-btns">
                                <button class="btn btn-sm ${btnClass} fw-bold" onclick="toggleDriverStatus(${row.id}, ${newStatus})">
                                    ${btnText}
                                </button>
                                <button class="btn btn-sm btn-info fw-bold text-white" onclick="viewHistory(${row.id})">
                                    <i class="fa fa-history"></i> History
                                </button>
                            </div>`;
                    }
                }
            ]
        });
    }

    function viewHistory(driverId) {
        $('#historyTbody').html('<tr><td colspan="4" class="text-center py-4"><div class="spinner-border text-primary spinner-border-sm" role="status"></div> Loading history...</td></tr>');
        $('#historyModal').modal('show');

        $.ajax({
            url: datatableUrl,
            type: "POST",
            dataType: "json",
            data: {
                method: 'admin_get_driver_block_history',
                driver_id: driverId
            },
            success: function(res) {
                var tbody = $('#historyTbody');
                tbody.empty();

                if (res.length === 0) {
                    tbody.append('<tr><td colspan="4" class="text-center text-muted py-3">No history records found for this driver.</td></tr>');
                } else {
                    res.forEach(function(log) {
                        var badge = log.action_status == 1 ? '<span class="badge bg-danger">Blocked</span>' : '<span class="badge bg-success">Unblocked</span>';
                        
                        tbody.append(`
                            <tr>
                                <td class="align-middle">${log.formatted_date}</td>
                                <td class="align-middle">${badge}</td>
                                <td class="align-middle">${log.reason}</td>
                                <td class="align-middle"><span class="fw-bold">${log.rejected_by_name}</span> <span class="text-muted" style="font-size:12px;">(ID: ${log.rejected_by})</span></td>
                            </tr>
                        `);
                    });
                }
            },
            error: function() {
                $('#historyTbody').html('<tr><td colspan="4" class="text-center text-danger py-3">Failed to load history.</td></tr>');
            }
        });
    }

    function toggleDriverStatus(driverId, targetStatus) {
        if (targetStatus === 1) { 
            Swal.fire({
                title: 'Block Driver',
                text: "Please provide a reason for blocking this driver:",
                input: 'textarea',
                inputPlaceholder: 'e.g., Customer complaints, rule violation...',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Block Driver',
                preConfirm: (reason) => {
                    if (!reason || reason.trim() === "") {
                        Swal.showValidationMessage('A reason is required to block a driver!');
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    processStatusChange(driverId, targetStatus, result.value);
                }
            });
        } else { 
            Swal.fire({
                title: 'Unblock Driver',
                text: "Are you sure you want to reactivate this driver?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Unblock'
            }).then((result) => {
                if (result.isConfirmed) {
                    processStatusChange(driverId, targetStatus, 'Admin restored access');
                }
            });
        }
    }

    function processStatusChange(driverId, targetStatus, reasonText) {
        $.ajax({
            url: datatableUrl,
            type: "POST",
            dataType: "json",
            data: {
                method: 'admin_change_driver_status',
                driver_id: driverId,
                status: targetStatus,
                reason: reasonText,
                memid: currentMemId
            },
            success: function(res) {
                if (res.status) {
                    Swal.fire('Success!', res.message, 'success');
                    driverTableInstance.ajax.reload(null, false); 
                } else {
                    Swal.fire('Error!', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error!', 'Server communication failed.', 'error');
            }
        });
    }
</script>