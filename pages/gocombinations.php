<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

<style>
    .select2-container .select2-selection--single {
        height: 38px !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
    .select2-container--open { z-index: 99999 !important; }
    .select2-dropdown { z-index: 99999 !important; }

    .clean-checkbox-col {
        position: relative !important;
        background: transparent !important;
        text-align: center;
        vertical-align: middle;
    }
    .clean-checkbox-col::before, .clean-checkbox-col::after { display: none !important; }
    .large-checkbox { width: 18px; height: 18px; cursor: pointer; margin: 0; }
    
    #comboTable td { vertical-align: middle; color: #333; font-weight: 500; }
    #comboTable thead th { background-color: #f8f9fa; color: #333; vertical-align: middle; }

    .dataTables_processing {
        background: rgba(255, 255, 255, 0.9) !important;
        border: none !important;
        box-shadow: none !important;
        color: transparent !important;
    }
    .dataTables_processing::after {
        content: ''; display: inline-block; width: 3rem; height: 3rem;
        vertical-align: text-bottom; background-color: transparent;
        border: 0.25em solid #0d6efd; border-right-color: transparent;
        border-radius: 50%; animation: spinner-border .75s linear infinite;
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
    }
    @keyframes spinner-border { to { transform: translate(-50%, -50%) rotate(360deg); } }

    .scrollable-table { max-height: 350px; overflow-y: auto; border-bottom: 1px solid #dee2e6; }
</style>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <div class="page-header">
                <h1 class="page-title">Go Location Combinations</h1>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-header"><h3 class="card-title">1. Select Starting Location</h3></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">From Location <span style="color:red;">*</span></label>
                                    <select class="form-select select2-search" id="from_location_select">
                                        <option value="" disabled selected>Loading Locations...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="pendingCombinationsRow" style="display:none;">
                <div class="col-lg-12">
                    <div class="card border-primary shadow-sm">
                        <div class="card-header bg-primary-transparent">
                            <h3 class="card-title">2. Review & Process Destinations</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 border-end">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="fw-bold text-dark mb-0" id="pendingCountTitle">Pending Generation (0)</h5>
                                        <input type="text" id="searchPending" class="form-control form-control-sm w-50" placeholder="Search new...">
                                    </div>
                                    <div class="scrollable-table">
                                        <table class="table table-bordered table-hover text-nowrap mb-0" id="tablePending">
                                            <thead class="bg-light sticky-top">
                                                <tr>
                                                    <th class="clean-checkbox-col" style="width:40px;">
                                                        <input type="checkbox" id="selectAllPending" class="large-checkbox">
                                                    </th>
                                                    <th>To Location</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="bodyPending"></tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="fw-bold text-primary mb-0" id="existingCountTitle">Already Exists (0)</h5>
                                        <input type="text" id="searchExisting" class="form-control form-control-sm w-50" placeholder="Search existing...">
                                    </div>
                                    <div class="scrollable-table">
                                        <table class="table table-bordered table-hover text-nowrap mb-0" id="tableExisting">
                                            <thead class="bg-light sticky-top">
                                                <tr>
                                                    <th class="clean-checkbox-col" style="width:40px;">
                                                        <input type="checkbox" id="selectAllExisting" class="large-checkbox" disabled>
                                                    </th>
                                                    <th>To Location</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="bodyExisting"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-center">
                                <button id="generateBtn" class="btn btn-success btn-lg px-5" onclick="startGeneration()" disabled>
                                    <i class="fa fa-bolt me-2"></i> Instant Process Selected Items
                                </button>
                            </div>

                            <div id="progressWrapper" class="mt-4" style="display: none;">
                                <h6 id="progressText" class="text-primary fw-bold">Processing instantly...</h6>
                                <div class="progress" style="height: 25px;">
                                    <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 0%;">0%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-header border-bottom-0">
                            <h3 class="card-title">All Generated Combinations</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="comboTable" style="width:100%;">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-center align-middle" style="width:10%">S.No</th>
                                            <th class="align-middle" style="width:40%">From Location</th>
                                            <th class="align-middle" style="width:40%">To Location</th>
                                            <th class="text-center align-middle" style="width:10%">Action</th>
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
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    var origin = window.location.origin;
    var ajaxUrl = origin + "/ajax/service/combination_services.php";
    var table;
    var isGenerating = false;
    var pendingPairsData = [];

    // Helper to format names nicely for the UI (Replaces hyphens with spaces)
    function formatDisplay(str) {
        return str ? str.replace(/-/g, ' ') : '';
    }

    $(document).ready(function() {
        $('#from_location_select').select2({ width: '100%' });
        loadFromLocations();
        initGeneratedTable();

        $('#from_location_select').on('change', function() {
            let locId = $(this).val();
            if (locId) {
                $('#searchPending').val('');
                $('#searchExisting').val('');
                loadCombinationsList(locId);
            }
        });

        $("#searchPending").on("keyup", function() {
            let val = $(this).val().toLowerCase();
            $("#bodyPending tr").filter(function() { $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1); });
            $('#selectAllPending').prop('checked', false);
            toggleGenerateButton();
        });

        $("#searchExisting").on("keyup", function() {
            let val = $(this).val().toLowerCase();
            $("#bodyExisting tr").filter(function() { $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1); });
        });

        $('#selectAllPending').on('change', function() {
            $('.chk-pending:visible:not(:disabled)').prop('checked', $(this).prop('checked'));
            toggleGenerateButton();
        });
    });

    function toggleGenerateButton() {
        let count = $('.chk-pending:visible:checked').length;
        $('#generateBtn').prop('disabled', count === 0);
        if(count > 0) {
            $('#generateBtn').html(`<i class="fa fa-bolt me-2"></i> Instant Process ${count} Selected Items`);
        } else {
            $('#generateBtn').html(`<i class="fa fa-bolt me-2"></i> Instant Process Selected Items`);
        }
    }

    function loadFromLocations() {
        $('#from_location_select').html('<option value="" disabled selected>Loading...</option>').trigger('change.select2');
        $.ajax({
            url: ajaxUrl, type: "POST", data: { method: "get_all_locations" }, dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    let html = '<option value="" disabled selected>Search & Select From Location</option>';
                    response.data.forEach(function(loc) {
                        let type = loc.main == 0 ? ' (District)' : ' (Spot)';
                        html += `<option value="${loc.id}">${formatDisplay(loc.district_name)}${type}</option>`;
                    });
                    $('#from_location_select').html(html).trigger('change.select2');
                }
            }
        });
    }

    function loadCombinationsList(fromId) {
        $('#pendingCombinationsRow').fadeIn();
        $('#bodyPending, #bodyExisting').html('<tr><td colspan="3" class="text-center">Loading...</td></tr>');
        $('#generateBtn').prop('disabled', true);
        $('#selectAllPending').prop('checked', false);

        $.ajax({
            url: ajaxUrl, type: "POST", data: { method: "get_pending_destinations", from_id: fromId }, dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    pendingPairsData = response.data;
                    let pHtml = '', eHtml = '';
                    let pCount = 0, eCount = 0;

                    pendingPairsData.forEach((pair, index) => {
                        let typeBadge = pair.to_main == 0 ? '<span class="badge bg-secondary ms-1">District</span>' : '<span class="badge bg-dark ms-1">Spot</span>';
                        let isEx = (pair.is_existing == 1);

                        // Format name nicely for UI
                        let displayName = formatDisplay(pair.to_name).toUpperCase();

                        if (isEx) {
                            eCount++;
                            eHtml += `<tr>
                                <td class="clean-checkbox-col"><input type="checkbox" class="form-check-input chk-existing large-checkbox" disabled></td>
                                <td><strong>${displayName}</strong> ${typeBadge}</td>
                                <td id="status_cell_${index}"><span class="badge bg-light border text-success"><i class="fa fa-check-circle me-1"></i> Exists</span></td>
                            </tr>`;
                        } else {
                            pCount++;
                            pHtml += `<tr>
                                <td class="clean-checkbox-col"><input type="checkbox" class="form-check-input chk-pending large-checkbox" value="${index}" onchange="toggleGenerateButton()"></td>
                                <td><strong>${displayName}</strong> ${typeBadge}</td>
                                <td id="status_cell_${index}"><span class="text-muted small">Ready</span></td>
                            </tr>`;
                        }
                    });

                    if(pCount === 0) pHtml = '<tr><td colspan="3" class="text-center text-muted">No new routes.</td></tr>';
                    if(eCount === 0) eHtml = '<tr><td colspan="3" class="text-center text-muted">No existing routes.</td></tr>';

                    // Disable select all pending if there's nothing pending
                    $('#selectAllPending').prop('disabled', pCount === 0);

                    $('#bodyPending').html(pHtml);
                    $('#bodyExisting').html(eHtml);
                    $('#pendingCountTitle').text(`Pending Generation (${pCount})`);
                    $('#existingCountTitle').text(`Already Exists (${eCount})`);
                }
            }
        });
    }

    async function startGeneration() {
        if (isGenerating) return;

        let selectedElements = $('.chk-pending:visible:checked');
        if (selectedElements.length === 0) return;

        isGenerating = true;
        
        selectedElements.addClass('processing-item');
        $('.chk-pending, #selectAllPending, input[type="text"]').prop('disabled', true);
        $('#generateBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Processing...');
        $('#progressWrapper').fadeIn();
        $('#progressBar').css('width', '0%').text('0%');

        let total = selectedElements.length;

        try {
            for (let i = 0; i < total; i++) {
                let el = $(selectedElements[i]);
                let pairIndex = el.val();
                let pair = pendingPairsData[pairIndex];

                $(`#status_cell_${pairIndex}`).html('<span class="text-info small"><i class="fa fa-spinner fa-spin me-1"></i> Saving...</span>');

                // Instant Database Save (No KM, No Fare overwrites, No Delay)
                await $.ajax({
                    url: ajaxUrl,
                    type: "POST",
                    dataType: "json",
                    data: {
                        method: "save_combination",
                        from_loc: pair.from_name, 
                        to_loc: pair.to_name
                    }
                });

                let distHtml = `<span class="badge bg-light border text-success"><i class="fa fa-check me-1"></i> Saved</span>`;
                $(`#status_cell_${pairIndex}`).html(distHtml);
                el.prop('checked', false).prop('disabled', true);

                let percent = Math.round(((i + 1) / total) * 100);
                $('#progressBar').css('width', percent + '%').text(percent + '%');
            }

            table.ajax.reload(null, false);
            Swal.fire("Success", "Combinations created instantly.", "success");
            
            let currentLocId = $('#from_location_select').val();
            if (currentLocId) {
                loadCombinationsList(currentLocId);
            }

        } catch (error) {
            Swal.fire("Error", "Process failed.", "error");
        }

        isGenerating = false;
        $('.chk-pending:not(.processing-item)').prop('disabled', false);
        $('#selectAllPending, input[type="text"]').prop('disabled', false);
        selectedElements.removeClass('processing-item');
        $('#selectAllPending').prop('checked', false);

        $('#generateBtn').html(`<i class="fa fa-bolt me-2"></i> Instant Process Selected Items`);
        setTimeout(() => { $('#progressWrapper').fadeOut(); }, 3000);
    }

    function deleteCombination(id) {
        Swal.fire({
            title: 'Delete Combination?',
            text: "This will only remove the combination, not the actual locations.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: ajaxUrl, type: "POST", data: { method: "delete_combination", id: id }, dataType: "json",
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 }).fire({ icon: 'success', title: response.message });
                            table.ajax.reload(null, false);
                            let locId = $('#from_location_select').val();
                            if(locId) loadCombinationsList(locId);
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    }
                });
            }
        });
    }

    function initGeneratedTable() {
        table = $('#comboTable').DataTable({
            processing: true, serverSide: false, destroy: true,
            language: { processing: "Loading..." },
            ajax: { url: ajaxUrl, type: "POST", data: { method: "fetch_all_combinations" } },
            columns: [
                { data: null, className: "text-center align-middle", render: function(d, t, r, m) { return m.row + 1; } },
                { data: "from_loc", className: "align-middle", render: function(d) { return `<strong>${formatDisplay(d)}</strong>`; } },
                { data: "to_loc", className: "align-middle", render: function(d) { return `<strong>${formatDisplay(d)}</strong>`; } },
                { 
                    data: "id", 
                    className: "text-center align-middle",
                    render: function(data) { 
                        return `<button class="btn btn-sm btn-danger py-0 px-2" onclick="deleteCombination(${data})" title="Delete Combination"><i class="fa fa-trash" style="font-size: 12px;"></i></button>`;
                    } 
                }
            ],
            order: []
        });
    }
</script>