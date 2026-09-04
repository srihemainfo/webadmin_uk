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
                <h1 class="page-title m-0 fw-bold">Manage Districts</h1>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header p-3">
                            <h6 class="mb-0 fw-bold text-dark">Add New District</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold text-muted small">District Name <span class="text-danger">*</span></label>
                                <input type="text" id="districtName" class="form-control" placeholder="Enter district name">
                            </div>
                            <button type="button" id="saveDistrictBtn" class="btn btn-dark w-100 fw-bold">
                                Save District
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header p-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">Districts List</h6>
                            <span class="text-muted small">Total: <strong id="totalDistricts">0</strong></span>
                        </div>
                        <div class="card-body p-0 pt-3">
                            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width: 80px;" class="text-center">S.NO</th>
                                            <th>DISTRICT NAME</th>
                                        </tr>
                                    </thead>
                                    <tbody id="districtsListBody">
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-4">
                                                <i class="fa fa-spinner fa-spin me-2"></i>Loading districts...
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

    // Function to Fetch and Display Districts
    function loadDistricts() {
        $.ajax({
            url: window.location.origin + "/ajax/service/district_services.php",
            type: "POST",
            dataType: "json",
            data: { method: 'get_districts' },
            success: function(res) {
                if (res.status && res.data) {
                    let html = '';
                    if (res.data.length === 0) {
                        html = `<tr><td colspan="2" class="text-center text-muted py-4">No districts found.</td></tr>`;
                    } else {
                        res.data.forEach(function(district) {
                            html += `
                                <tr>
                                    <td class="text-center text-muted">${district.sno}</td>
                                    <td class="fw-bold text-dark">${district.name}</td>
                                </tr>
                            `;
                        });
                    }
                    $('#districtsListBody').html(html);
                    $('#totalDistricts').text(res.data.length);
                } else {
                    $('#districtsListBody').html(`<tr><td colspan="2" class="text-center text-danger py-4">Failed to load data.</td></tr>`);
                }
            },
            error: function() {
                $('#districtsListBody').html(`<tr><td colspan="2" class="text-center text-danger py-4">Server error occurred.</td></tr>`);
            }
        });
    }

    // Initial Load
    loadDistricts();

    // Handle Save Button Click
    $('#saveDistrictBtn').on('click', function() {
        const districtName = $('#districtName').val().trim();
        const $btn = $(this);

        if (!districtName) {
            showToast('Please enter a district name.', 'error');
            $('#districtName').focus();
            return;
        }

        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i>Saving...');

        $.ajax({
            url: window.location.origin + "/ajax/service/district_services.php",
            type: "POST",
            dataType: "json",
            data: {
                method: 'add_district',
                district_name: districtName
            },
            success: function(res) {
                if (res.status) {
                    showToast(res.message, 'success');
                    $('#districtName').val(''); // Clear input
                    loadDistricts(); // Reload table
                } else {
                    showToast(res.message, 'error');
                }
            },
            error: function() {
                showToast('Server error. Please try again.', 'error');
            },
            complete: function() {
                $btn.prop('disabled', false).html('Save District');
            }
        });
    });

    // Optional: Allow pressing "Enter" in the input field to submit
    $('#districtName').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#saveDistrictBtn').click();
        }
    });

});
</script>