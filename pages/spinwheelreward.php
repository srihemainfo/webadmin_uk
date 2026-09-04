<head>
    <!-- Dependencies -->
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<style>
    .back-arrow-btn i {
        background: #ffffff; font-size: 16px; padding: 2px 3px;
        border-radius: 50px; border: 2px solid #6c6e70; color: #6c6e70;
        margin-right: 15px; width: 24px; height: 24px;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .tab-content {
        border: 1px solid #e0e0e0; padding: 25px 20px;
        background: #fff; border-radius: 0; 
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        margin-bottom: 20px;
    }
    .table thead th { text-transform: uppercase; font-size: 12px; font-weight: 600; color: #333; background-color: #f8f9fa; }
</style>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <br>
            <div class="page-header align-items-center mb-4 d-flex justify-content-between">
                <h1 class="page-title m-0 d-flex align-items-center">
                    <a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)"></i></a> 
                    Spin Wheel Rewards Management
                </h1>
                <div class="ms-auto">
                    <button class="btn btn-primary shadow-sm me-2" onclick="openRewardModal()">
                        <i class="fa fa-plus me-2"></i> Add Daily Rewards
                    </button>
                    <button class="btn btn-light border text-dark shadow-sm" onclick="dataTable.ajax.reload(null, false);">
                        <i class="fa fa-refresh me-2"></i> Refresh Table
                    </button>
                </div>
            </div>

            <div class="tab-content">
                <div class="table-responsive">
                    <table class="table table-bordered border-bottom" id="rewardsTable" style="width:100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Reward Date</th>
                                <th>Rewards Configuration (12 Slots)</th>
                                <th class="text-center">Status</th> 
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

<!-- Add / Edit Rewards Modal -->
<div class="modal fade" id="addRewardsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Configure 12 Spin Rewards</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <form id="rewardForm">
                <input type="hidden" name="reward_id" id="reward_id" value="">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label" id="startDateLabel">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="start_date" id="start_date" required>
                        </div>
                        <div class="col-md-4" id="endDateContainer">
                            <label class="form-label">End Date (Optional for bulk add)</label>
                            <input type="date" class="form-control" name="end_date" id="end_date">
                        </div>
                    </div>
                    <hr>
                    <h6 class="mb-3">Reward Slots (Must fill exactly 12)</h6>
                    <div id="rewardsContainer">
                        <!-- JS will populate 12 rows here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save Configuration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var apiUrl = window.location.origin + "/ajax/service/spin_rewards_service.php"; 
    var dataTable;

    const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
    });
    function showToast(type, message) { Toast.fire({ icon: type, title: message }); }

    $(document).ready(function() { 
        // Set minimum date to today for the date pickers
        let today = new Date().toISOString().split('T')[0];
        $('#start_date').attr('min', today);
        $('#end_date').attr('min', today);

        viewRewardsTable(); 
    });

    // --- DATATABLE LOGIC ---
    function viewRewardsTable() {
        dataTable = $("#rewardsTable").DataTable({
            destroy: true, autoWidth: false, pageLength: 10, order: [[1, 'desc']],
            language: { emptyTable: "No spin rewards found" },
            ajax: { 
                url: apiUrl, 
                type: "POST", 
                dataSrc: "data", 
                data: { method: 'fetch_rewards' } 
            },
            columns: [
                { data: 'id', className: "align-middle text-center", width: "5%" },
                { 
                    data: 'reward_date', 
                    className: "align-middle", width: "15%",
                    render: function(data) {
                        return moment(data).format('DD MMM YYYY');
                    }
                },
                { 
                    data: 'rewards_data', 
                    className: "align-middle",
                    render: function(data) {
                        try {
                            let rewards = JSON.parse(data);
                            let textList = rewards.map(r => {
                                let label = r.type.replace('_', ' ').toUpperCase();
                                let valText = r.value ? ` (${r.value})` : '';
                                let limitText = ` - Limit: ${r.limit} People`;
                                return `Slot ${r.slot}: ${label}${valText}${limitText}`;
                            }).join('<br>'); 
                            return textList;
                        } catch(e) {
                            return 'Invalid Data';
                        }
                    }
                },
                { 
                    data: 'status', 
                    className: "text-center align-middle", width: "10%",
                    render: function(data, type, row) {
                        return `
                            <select class="form-select form-select-sm" style="width: 100px; display: inline-block;" onchange="updateRewardStatus(${row.id}, this.value)">
                                <option value="active" ${data === 'active' ? 'selected' : ''}>Active</option>
                                <option value="inactive" ${data === 'inactive' ? 'selected' : ''}>Inactive</option>
                            </select>
                        `;
                    }
                },
                { 
                    data: null, 
                    className: "text-center align-middle", width: "10%",
                    render: function(data, type, row) {
                        return `<button class="btn btn-sm btn-dark edit-btn">Edit</button>`;
                    }
                }
            ]
        });
    }

    // --- FORM LOGIC ---
    function openRewardModal() {
        $('#rewardForm')[0].reset();
        $('#reward_id').val(''); 
        $('#start_date').prop('readonly', false); 
        $('#endDateContainer').show(); // Show end date option when adding
        $('#startDateLabel').html('Start Date <span class="text-danger">*</span>');
        $('#modalTitle').text('Configure 12 Spin Rewards');
        generateRewardSlots(null);
        $('#addRewardsModal').modal('show');
    }

    $('#rewardsTable tbody').on('click', '.edit-btn', function() {
        let tr = $(this).closest('tr');
        let row = dataTable.row(tr).data();
        let rewards = JSON.parse(row.rewards_data);

        $('#rewardForm')[0].reset();
        $('#reward_id').val(row.id);
        $('#start_date').val(row.reward_date);
        $('#start_date').prop('readonly', true); 
        $('#endDateContainer').hide(); // Hide end date option when editing a single row
        $('#startDateLabel').html('Reward Date <span class="text-danger">*</span>');
        $('#modalTitle').text('Edit Spin Rewards Configuration');
        
        generateRewardSlots(rewards);
        $('#addRewardsModal').modal('show');
    });

    // Generate exactly 12 slots with Type, Amount, and Number of People
    function generateRewardSlots(existingData) {
        let html = '';
        for(let i=1; i<=12; i++) {
            let type = 'cashback';
            let value = '';
            let limit = '';

            if(existingData && existingData[i-1]) {
                type = existingData[i-1].type;
                value = existingData[i-1].value || '';
                limit = existingData[i-1].limit || '';
            }

            let showVal = (type === 'cashback') ? '' : 'style="display:none;"';
            let reqVal = (type === 'cashback') ? 'required' : '';

            html += `
            <div class="row mb-3 align-items-center bg-light p-2 border rounded mx-1">
                <div class="col-md-2 text-center">Slot ${i}</div>
                <div class="col-md-4">
                    <select class="form-select reward-type" name="reward_type[]" onchange="toggleValueField(this)">
                        <option value="cashback" ${type === 'cashback' ? 'selected' : ''}>Cashback</option>
                        <option value="better_luck" ${type === 'better_luck' ? 'selected' : ''}>Better Luck Next Time</option>
                        <option value="free_spin" ${type === 'free_spin' ? 'selected' : ''}>Free Spin</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control reward-value" name="reward_value[]" placeholder="Enter Amount" 
                        value="${value}" ${reqVal} ${showVal} 
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);"> 
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="reward_limit[]" placeholder="No. of People" 
                        value="${limit}" required 
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');"> 
                </div>
            </div>`;
        }
        $('#rewardsContainer').html(html);
    }

    function toggleValueField(selectElement) {
        let row = $(selectElement).closest('.row');
        let valueInput = row.find('.reward-value');
        
        if ($(selectElement).val() === 'cashback') {
            valueInput.show().prop('required', true);
        } else {
            valueInput.hide().prop('required', false).val('');
        }
    }

    // --- SUBMIT REWARDS ---
    $('#rewardForm').submit(function(e) {
        e.preventDefault();
        let method = $('#reward_id').val() ? 'edit_rewards' : 'add_rewards';
        let formData = $(this).serialize() + "&method=" + method;
        
        $('#saveBtn').prop('disabled', true).text('Saving...');

        $.ajax({
            url: apiUrl,
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                $('#saveBtn').prop('disabled', false).text('Save Configuration');
                if (response.status === "success") {
                    showToast('success', response.message);
                    $('#addRewardsModal').modal('hide');
                    dataTable.ajax.reload(null, false);
                } else { 
                    showToast('error', response.message); 
                }
            },
            error: function() { 
                $('#saveBtn').prop('disabled', false).text('Save Configuration');
                showToast('error', 'Failed to communicate with the server.'); 
            }
        });
    });

    // --- UPDATE STATUS ---
    function updateRewardStatus(id, newStatus) {
        if(!newStatus) return;
        $.ajax({
            url: apiUrl, type: "POST", dataType: "json",
            data: { method: 'update_status', id: id, status: newStatus },
            success: function(response) {
                if (response.status === "success") {
                    showToast('success', 'Status updated successfully.');
                    dataTable.ajax.reload(null, false);
                } else { 
                    showToast('error', response.message); 
                    dataTable.ajax.reload(null, false); 
                }
            }
        });
    }
</script>