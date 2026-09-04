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
    .action-btns { display: flex; gap: 5px; justify-content: center; flex-wrap: wrap; }

    /* Custom Toggle Switch CSS */
    .toggle-switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px; }
    .toggle-slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
    .toggle-switch input:checked + .toggle-slider { background-color: #28a745; }
    .toggle-switch input:focus + .toggle-slider { box-shadow: 0 0 1px #28a745; }
    .toggle-switch input:checked + .toggle-slider:before { transform: translateX(20px); }
    .perm-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #eee; }
    .perm-row:last-child { border-bottom: none; }
    .perm-label { font-weight: 600; color: #333; margin: 0; font-size: 14px; }
    .perm-desc { font-size: 12px; color: #777; margin-top: 2px; }
</style>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <br>
            <div class="page-header align-items-center mb-4">
                <h1 class="page-title m-0">
                    <a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)"></i></a> 
                    Manage Drivers Access
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

<div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 900px;">
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
                                <th>Block Date</th>
                                <th>Unblocked At</th> 
                                <th>Action</th>
                                <th>Reason & Expiry</th>
                                <th>Action By</th>
                            </tr>
                        </thead>
                        <tbody id="historyTbody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="permissionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title fw-bold m-0">Driver Permissions</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: none; font-size: 1.5rem; line-height: 1; cursor: pointer; color: #000; opacity: 0.5;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-4">
                <input type="hidden" id="perm_driver_id">
                
                <div class="perm-row">
                    <div>
                        <p class="perm-label">Job Bidding</p>
                        <p class="perm-desc">Driver can bid for jobs</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="perm_isBidding">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="perm-row">
                    <div>
                        <p class="perm-label">Open Jobs</p>
                        <p class="perm-desc">Driver can access job listings</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="perm_isOpenjob">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="perm-row">
                    <div>
                        <p class="perm-label">Post Jobs</p>
                        <p class="perm-desc">Driver can post new jobs</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="perm_isPostJob">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="perm-row">
                    <div>
                        <p class="perm-label">Scheduled Jobs</p>
                        <p class="perm-desc">Driver can post scheduled jobs</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="perm_isPostSchJob">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary w-100 fw-bold" onclick="savePermissions()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    var datatableUrl = window.location.origin + "/ajax/service/datatable_services.php";
    var driverTableInstance;
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
                    data: 'status',
                    render: function(data) {
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
                                <button class="btn btn-sm btn-warning fw-bold text-dark" style="background-color: #ffc107; border-color: #ffc107;" onclick="openPermissionModal(${row.id}, ${row.isBidding}, ${row.isOpenjob}, ${row.isPostJob}, ${row.isPostSchJob})">
                                    <i class="fa fa-sliders"></i> Permissions
                                </button>
                                <button class="btn btn-sm ${btnClass} fw-bold" onclick="toggleDriverStatus(${row.id}, ${newStatus})">
                                    ${btnText}
                                </button>
                                <button class="btn btn-sm btn-info fw-bold text-white" onclick="viewHistory(${row.id})">
                                    <i class="fa fa-history"></i> Block History
                                </button>
                            </div>`;
                    }
                }
            ]
        });
    }

    function openPermissionModal(id, bid, openjob, post, postsch) {
        $('#perm_driver_id').val(id);
        
        // Handle potentially null database values (treat 1 as true, anything else as false)
        $('#perm_isBidding').prop('checked', bid == 1);
        $('#perm_isOpenjob').prop('checked', openjob == 1);
        $('#perm_isPostJob').prop('checked', post == 1);
        $('#perm_isPostSchJob').prop('checked', postsch == 1);
        
        $('#permissionsModal').modal('show');
    }

    function savePermissions() {
        var driverId = $('#perm_driver_id').val();
        var isBidding = $('#perm_isBidding').is(':checked') ? 1 : 0;
        var isOpenjob = $('#perm_isOpenjob').is(':checked') ? 1 : 0;
        var isPostJob = $('#perm_isPostJob').is(':checked') ? 1 : 0;
        var isPostSchJob = $('#perm_isPostSchJob').is(':checked') ? 1 : 0;

        $.ajax({
            url: datatableUrl,
            type: "POST",
            dataType: "json",
            data: {
                method: 'admin_update_driver_permissions',
                driver_id: driverId,
                isBidding: isBidding,
                isOpenjob: isOpenjob,
                isPostJob: isPostJob,
                isPostSchJob: isPostSchJob
            },
            beforeSend: function() {
                Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
            },
            success: function(res) {
                if (res.status) {
                    $('#permissionsModal').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Success', text: res.message, timer: 1500, showConfirmButton: false });
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

    // --- Original History and Status Logic below ---

    function viewHistory(driverId) {
        $('#historyTbody').html('<tr><td colspan="5" class="text-center py-4"><div class="spinner-border text-primary spinner-border-sm" role="status"></div> Loading history...</td></tr>');
        $('#historyModal').modal('show');

        $.ajax({
            url: datatableUrl,
            type: "POST",
            dataType: "json",
            data: { method: 'admin_get_driver_block_history', driver_id: driverId },
            success: function(res) {
                var tbody = $('#historyTbody');
                tbody.empty();
                if (res.length === 0) {
                    tbody.append('<tr><td colspan="5" class="text-center text-muted py-3">No history records found for this driver.</td></tr>');
                } else {
                    res.forEach(function(log) {
                        var badge = log.action_status == 1 ? '<span class="badge bg-danger">Blocked</span>' : '<span class="badge bg-success">Unblocked / Expired</span>';
                        tbody.append(`
                            <tr>
                                <td class="align-middle" style="white-space: nowrap;">${log.formatted_date}</td>
                                <td class="align-middle" style="white-space: nowrap;">${log.formatted_unblock_date}</td>
                                <td class="align-middle">${badge}</td>
                                <td class="align-middle">${log.enhanced_reason}</td>
                                <td class="align-middle"><span class="fw-bold">${log.rejected_by_name}</span> <br><span class="text-muted" style="font-size:12px;">(ID: ${log.reported_by})</span></td>
                            </tr>
                        `);
                    });
                }
            },
            error: function() {
                $('#historyTbody').html('<tr><td colspan="5" class="text-center text-danger py-3">Failed to load history.</td></tr>');
            }
        });
    }

    function toggleDriverStatus(driverId, targetStatus) {
        if (targetStatus === 1) { 
            // Calculate TOMORROW'S date
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const minDate = tomorrow.toISOString().split('T')[0];

            Swal.fire({
                title: 'Block Driver',
                html: `
                    <div style="text-align: left;">
                        <label style="font-weight: bold; font-size: 14px; margin-bottom: 5px;">Reason for blocking:</label>
                        <textarea id="block-reason" class="swal2-textarea" style="margin: 0 0 15px 0; width: 100%; padding: 10px; box-sizing: border-box; height: 80px;" placeholder="e.g., Customer complaints..."></textarea>
                        
                        <label style="font-weight: bold; font-size: 14px; margin-bottom: 5px;">Block Expiry Date:</label>
                        <input type="date" id="block-expiry" class="swal2-input" min="${minDate}" style="margin: 0; width: 100%; box-sizing: border-box; height: 40px; font-size: 14px;">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Block Driver',
                preConfirm: () => {
                    const reason = document.getElementById('block-reason').value;
                    const expiryDate = document.getElementById('block-expiry').value;
                    if (!reason || reason.trim() === "") { Swal.showValidationMessage('A reason is required to block a driver!'); return false; }
                    if (!expiryDate) { Swal.showValidationMessage('An expiry date is required!'); return false; }
                    return { reason: reason.trim(), expiry: expiryDate };
                }
            }).then((result) => {
                if (result.isConfirmed) { processStatusChange(driverId, targetStatus, result.value.reason, result.value.expiry); }
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
                if (result.isConfirmed) { processStatusChange(driverId, targetStatus, 'Admin restored access', null); }
            });
        }
    }

    function processStatusChange(driverId, targetStatus, reasonText, expiryDate) {
        $.ajax({
            url: datatableUrl,
            type: "POST",
            dataType: "json",
            data: { method: 'admin_change_driver_status', driver_id: driverId, status: targetStatus, reason: reasonText, expiry_date: expiryDate, memid: currentMemId },
            success: function(res) {
                if (res.status) {
                    Swal.fire('Success!', res.message, 'success');
                    driverTableInstance.ajax.reload(null, false); 
                } else { Swal.fire('Error!', res.message, 'error'); }
            },
            error: function() { Swal.fire('Error!', 'Server communication failed.', 'error'); }
        });
    }
</script>