<style>
    /* Flat, Solid styling */
    .card { border-radius: 0; border: 1px solid #dee2e6; }
    .card-header { border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; border-radius: 0 !important; }
    .form-control { border-radius: 0; border: 1px solid #ced4da; }
    .btn { border-radius: 0; }
    
    .table-responsive { padding: 0 15px 15px 15px; }
    table.table { border-collapse: collapse !important; border-spacing: 0; border: 1px solid #dee2e6; width: 100%; margin-bottom: 0; }
    table.table thead th { border-bottom: 1px solid #dee2e6 !important; border-right: 1px solid #dee2e6 !important; color: #495057; font-weight: 600; padding: 12px 10px; background-color: #f8f9fa; text-align: left; }
    table.table tbody td { border-bottom: 1px solid #dee2e6 !important; border-right: 1px solid #dee2e6 !important; padding: 12px 10px; color: #333; }
    table.table thead th:last-child, table.table tbody td:last-child { border-right: none !important; }
</style>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            
            <div class="page-header d-flex justify-content-between align-items-center mt-3 mb-4">
                <h1 class="page-title m-0 fw-bold">Manage Cab Types</h1>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header p-3">
                            <h6 class="mb-0 fw-bold text-dark" id="formTitle">Add New Cab Type</h6>
                        </div>
                        <div class="card-body p-4">
                            <input type="hidden" id="cabId" value="">
                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold text-muted small">Cab Name <span class="text-danger">*</span></label>
                                <input type="text" id="cabName" class="form-control" placeholder="Enter cab type name (e.g. Go 4Seater)">
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold text-muted small">Seat Capacity <span class="text-danger">*</span></label>
                                <input type="number" id="seatCapacity" class="form-control" placeholder="Enter seat capacity (e.g. 5)" min="1">
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" id="saveCabBtn" class="btn btn-dark w-100 fw-bold">
                                    Save Cab Type
                                </button>
                                <button type="button" id="cancelEditBtn" class="btn btn-secondary w-100 fw-bold d-none">
                                    Cancel Edit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header p-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">Cab Types List</h6>
                            <div class="d-flex align-items-center">
                                <input type="text" id="searchCabInput" class="form-control form-control-sm me-3" placeholder="Search Cabs..." style="width: 200px;">
                                <span class="text-muted small text-nowrap">Total: <strong id="totalCabs">0</strong></span>
                            </div>
                        </div>
                        <div class="card-body p-0 pt-3">
                            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width: 80px;" class="text-center">S.NO</th>
                                            <th>CAB TYPE NAME</th>
                                            <th class="text-center" style="width: 150px;">SEAT CAPACITY</th>
                                            <th class="text-center" style="width: 100px;">ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cabsListBody">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fa fa-spinner fa-spin me-2"></i>Loading cab types...
                                            </td>
                                        </tr>
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

<script>
$(document).ready(function() {

    // Helper for Toast Notifications
    function showToast(message, type = 'error') {
        Swal.fire({
            icon: type,
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    }

    // Function to Fetch and Display Cab Types
    function loadCabs(searchTerm = '') {
        $('#cabsListBody').html(`<tr><td colspan="4" class="text-center text-muted py-4"><i class="fa fa-spinner fa-spin me-2"></i>Loading...</td></tr>`);

        $.ajax({
            url: window.location.origin + "/ajax/service/cab_types_services.php",
            type: "POST",
            dataType: "json",
            data: { 
                method: 'get_cab_types',
                search: searchTerm 
            },
            success: function(res) {
                if (res.status && res.data) {
                    let html = '';
                    if (res.data.length === 0) {
                        html = `<tr><td colspan="4" class="text-center text-muted py-4">No cab types found.</td></tr>`;
                    } else {
                        res.data.forEach(function(cab) {
                            html += `
                                <tr>
                                    <td class="text-center text-muted">${cab.sno}</td>
                                    <td class="fw-bold text-dark">${cab.name}</td>
                                    <td class="text-center fw-bold text-secondary">${cab.seat_capacity || '-'}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary edit-cab-btn" 
                                            data-id="${cab.id}" 
                                            data-name="${cab.name}" 
                                            data-capacity="${cab.seat_capacity}">
                                            <i class="fa fa-edit"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    $('#cabsListBody').html(html);
                    $('#totalCabs').text(res.data.length);
                } else {
                    $('#cabsListBody').html(`<tr><td colspan="4" class="text-center text-danger py-4">Failed to load data.</td></tr>`);
                }
            },
            error: function() {
                $('#cabsListBody').html(`<tr><td colspan="4" class="text-center text-danger py-4">Server error occurred.</td></tr>`);
            }
        });
    }

    // Initial Load
    loadCabs();

    // Handle Search with Debounce (Wait 500ms before searching)
    let searchTimer;
    $('#searchCabInput').on('keyup', function() {
        clearTimeout(searchTimer);
        let val = $(this).val().trim();
        searchTimer = setTimeout(function() {
            loadCabs(val);
        }, 500);
    });

    // Reset Form Function
    function resetForm() {
        $('#cabId').val('');
        $('#cabName').val('');
        $('#seatCapacity').val('');
        $('#formTitle').text('Add New Cab Type');
        $('#saveCabBtn').html('Save Cab Type').removeClass('btn-primary').addClass('btn-dark');
        $('#cancelEditBtn').addClass('d-none');
    }

    // Handle Cancel Edit
    $('#cancelEditBtn').on('click', function() {
        resetForm();
    });

    // Handle Edit Button Click
    $(document).on('click', '.edit-cab-btn', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const capacity = $(this).data('capacity');

        $('#cabId').val(id);
        $('#cabName').val(name);
        $('#seatCapacity').val(capacity);
        
        $('#formTitle').text('Edit Cab Type');
        $('#saveCabBtn').html('Update Cab Type').removeClass('btn-dark').addClass('btn-primary');
        $('#cancelEditBtn').removeClass('d-none');
    });

    // Handle Save / Update Button Click
    $('#saveCabBtn').on('click', function() {
        const cabId = $('#cabId').val();
        const cabName = $('#cabName').val().trim();
        const seatCapacity = $('#seatCapacity').val().trim();
        const $btn = $(this);

        if (!cabName) {
            showToast('Please enter a cab type name.', 'error');
            $('#cabName').focus();
            return;
        }
        
        if (!seatCapacity) {
            showToast('Please enter the seat capacity.', 'error');
            $('#seatCapacity').focus();
            return;
        }

        const btnText = cabId ? 'Update Cab Type' : 'Save Cab Type';
        const method = cabId ? 'edit_cab_type' : 'add_cab_type';

        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i>Saving...');

        $.ajax({
            url: window.location.origin + "/ajax/service/cab_types_services.php",
            type: "POST",
            dataType: "json",
            data: {
                method: method,
                cab_id: cabId,
                cab_name: cabName,
                seat_capacity: seatCapacity
            },
            success: function(res) {
                if (res.status) {
                    showToast(res.message, 'success');
                    resetForm();
                    $('#searchCabInput').val(''); // Clear search if any
                    loadCabs(); // Reload table
                } else {
                    showToast(res.message, 'error');
                }
            },
            error: function() {
                showToast('Server error. Please try again.', 'error');
            },
            complete: function() {
                $btn.prop('disabled', false).html(cabId ? 'Update Cab Type' : 'Save Cab Type');
            }
        });
    });

    // Allow pressing "Enter" in the input fields to submit
    $('#cabName, #seatCapacity').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#saveCabBtn').click();
        }
    });

});
</script>