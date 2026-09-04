<style>
    .back-arrow-btn i {
        background: #ffffff;
        font-size: 16px;
        padding: 2px 3px;
        border-radius: 50px;
        border: 2px solid #6c6e70;
        color: #6c6e70;
        margin-right: 15px;
        width: 24px;
        height: 24px;
        text-align: center;
        line-height: 18px;
    }
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
    .alias-badge {
        background-color: #17a2b8;
        color: white;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 12px;
        margin-right: 4px;
        display: inline-block;
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <div class="page-header">
                <h1 class="page-title">
                    <a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>
                    Go Trip Locations
                </h1>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Add District, Spot or Alias</h3>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="is_new_district" value="0">

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Select State (IN) <span style="color:red;">*</span></label>
                                    <select class="form-select select2-search" id="state_select">
                                        <option value="" disabled selected>Loading States...</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">District <small class="text-muted">(Select or Type New)</small> <span style="color:red;">*</span></label>
                                    <select class="form-select select2-tags" id="district_select">
                                        <option value="" disabled selected>Select State First</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3" id="entry_type_wrapper" style="display:none;">
                                    <label class="form-label d-block">What are you adding? <span style="color:red;">*</span></label>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" name="entry_type" id="typeSpot" value="spot" checked>
                                        <label class="form-check-label" for="typeSpot">Child Spot</label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" name="entry_type" id="typeDistrictAlias" value="district_alias">
                                        <label class="form-check-label" for="typeDistrictAlias">District Alias</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3" id="spot_name_wrapper">
                                    <label class="form-label">Child Spot Names <small class="text-muted">(Comma separated)</small> <span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" id="spot_name" placeholder="">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label" id="aliases_label">Spot Aliases <small class="text-muted">(Comma separated)</small></label>
                                    <input type="text" class="form-control" id="aliases_input" placeholder="">
                                </div>
                                <div class="col-md-4 mb-3 d-flex align-items-end">
                                    <button type="button" class="btn btn-info w-100" onclick="saveLocation()">Save Entry</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-bottom-0">
                            <h3 class="card-title">Trip Locations List</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="locationsTable" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>State</th>
                                            <th>Location Name</th>
                                            <th>Type</th>
                                            <th>Parent District</th>
                                            <th>Aliases</th>
                                            <th>Action</th>
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
    var ajaxUrl = origin + "/ajax/service/location_services.php";
    var table;

    $(document).ready(function() {
        $('#state_select').select2({ width: '100%' });

        $('#district_select').select2({
            width: '100%',
            tags: true,
            createTag: function (params) {
                var term = $.trim(params.term);
                if (term === '') return null;
                return { id: term, text: term + ' (Add New District)', newTag: true }
            }
        });

        $('#state_select').on('change', function() { loadDistricts(); });

        $('#district_select').on('select2:select', function (e) {
            var data = e.params.data;
            $('#entry_type_wrapper').fadeIn();
            
            if (data.newTag) {
                $('#is_new_district').val('1');
            } else {
                $('#is_new_district').val('0');
            }
        });

        $('input[name="entry_type"]').on('change', function() {
            if ($(this).val() === 'spot') {
                $('#spot_name_wrapper').fadeIn();
                $('#aliases_label').html('Spot Aliases <small class="text-muted">(Comma separated)</small>');
            } else {
                $('#spot_name_wrapper').hide();
                $('#aliases_label').html('District Aliases <small class="text-muted">(Comma separated)</small> <span style="color:red;">*</span>');
            }
        });

        loadStates();
        initTable();
    });

    function toast(icon, message) {
        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 4000 }).fire({ icon: icon, title: message });
    }

    function loadStates() {
        $('#state_select').html('<option value="" disabled selected>Loading...</option>').trigger('change.select2');
        $.ajax({
            url: ajaxUrl, type: "POST", data: { method: "fetch_states" }, dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    let html = '<option value="" disabled selected>Search and Select State</option>';
                    response.data.forEach(function(state) { html += `<option value="${state.name}">${state.name}</option>`; });
                    $('#state_select').html(html).trigger('change.select2');
                }
            }
        });
    }

    function loadDistricts() {
        let stateName = $('#state_select').val();
        $('#is_new_district').val('0');
        $('#entry_type_wrapper').hide();
        $('#spot_name').val('');
        $('#aliases_input').val('');
        $('#typeSpot').prop('checked', true).trigger('change');
        
        if (!stateName) return;

        $('#district_select').html('<option value="" disabled selected>Loading...</option>').trigger('change.select2');
        $.ajax({
            url: ajaxUrl, type: "POST", data: { method: "fetch_districts", state: stateName }, dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    let html = '<option value="" disabled selected>Select or Type New District</option>';
                    response.data.forEach(function(district) { html += `<option value="${district.id}">${district.district_name}</option>`; });
                    $('#district_select').html(html).trigger('change.select2');
                }
            }
        });
    }

    function saveLocation() {
        let isNewDistrict = $('#is_new_district').val() === '1';
        let stateName = $('#state_select').val();
        let districtVal = $('#district_select').val();
        let entryType = $('input[name="entry_type"]:checked').val();
        let spotName = $('#spot_name').val().trim();
        let aliasesVal = $('#aliases_input').val().trim();
        
        if (!stateName) { toast('error', 'Please select a state'); return; }
        if (!districtVal) { toast('error', 'Please select or type a district'); return; }

        if (entryType === 'spot' && !spotName && !isNewDistrict) { 
            toast('error', 'Please enter at least one Child Spot Name'); 
            $('#spot_name').focus(); 
            return; 
        }
        if (entryType === 'district_alias' && !aliasesVal && !isNewDistrict) { 
            toast('error', 'Please enter at least one Alias'); 
            $('#aliases_input').focus(); 
            return; 
        }

        let formData = {
            method: "save_location",
            state: stateName,
            is_new: isNewDistrict,
            district_val: districtVal,
            entry_type: entryType,
            spot_name: spotName,
            aliases: aliasesVal
        };

        $.ajax({
            url: ajaxUrl, type: "POST", data: formData, dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    toast('success', response.message);
                    
                    $('#spot_name').val('');
                    $('#aliases_input').val('');
                    table.ajax.reload(null, false);
                    
                    if(isNewDistrict) { loadDistricts(); }
                } else {
                    toast('error', response.message);
                }
            }
        });
    }

    function editLocation(id, encName, encAliases) {
        let name = decodeURIComponent(encName);
        let aliasJsonStr = decodeURIComponent(encAliases);
        let aliasStr = '';

        if (aliasJsonStr && aliasJsonStr !== "null") {
            try {
                let arr = JSON.parse(aliasJsonStr);
                if (Array.isArray(arr)) aliasStr = arr.join(', ');
            } catch(e) {}
        }

        Swal.fire({
            title: 'Edit Location',
            html:
                `<label class="form-label text-start w-100 mt-2">Location Name</label>` +
                `<input id="swal-edit-name" class="form-control mb-3" placeholder="Location Name" value="${name}">` +
                `<label class="form-label text-start w-100">Aliases (Comma separated)</label>` +
                `<input id="swal-edit-aliases" class="form-control" placeholder="" value="${aliasStr}">`,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Save Changes',
            preConfirm: () => {
                const newName = document.getElementById('swal-edit-name').value.trim();
                const newAliases = document.getElementById('swal-edit-aliases').value.trim();
                if (!newName) { Swal.showValidationMessage('Location Name cannot be empty'); }
                return { name: newName, aliases: newAliases }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: ajaxUrl, type: "POST", dataType: "json",
                    data: {
                        method: "update_location",
                        id: id,
                        name: result.value.name,
                        aliases: result.value.aliases
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            toast('success', response.message);
                            table.ajax.reload(null, false);
                            loadDistricts();
                        } else {
                            toast('error', response.message);
                        }
                    }
                });
            }
        });
    }

    function deleteLocation(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will remove the location and any associated aliases.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: ajaxUrl, type: "POST", data: { method: "delete_location", id: id }, dataType: "json",
                    success: function(response) {
                        if (response.status === 'success') {
                            toast('success', response.message);
                            table.ajax.reload(null, false);
                            loadDistricts(); 
                        } else {
                            toast('error', response.message);
                        }
                    }
                });
            }
        });
    }

    function initTable() {
        table = $('#locationsTable').DataTable({
            processing: true, 
            serverSide: false,
            destroy: true,
            ajax: { url: ajaxUrl, type: "POST", data: { method: "fetch_all_locations" } },
            columns: [
                { data: null, render: function(data, type, row, meta) { return meta.row + 1; } },
                { data: "state" },
                { data: "location_name", render: function(data) { return `<strong>${data}</strong>`; } },
                { data: "main", render: function(data) { return data == 0 ? '<span class="badge bg-primary">Main District</span>' : '<span class="badge bg-secondary">Child Spot</span>'; } },
                { data: "parent_name", render: function(data) { return data ? data : '-'; } },
                { 
                    data: "alias_names",
                    render: function(data) {
                        if (!data || data === "null") return '-';
                        try {
                            let aliases = JSON.parse(data);
                            if (!Array.isArray(aliases) || aliases.length === 0) return '-';
                            let badges = '';
                            aliases.forEach(a => badges += `<span class="alias-badge">${a}</span>`);
                            return badges;
                        } catch(e) { return '-'; }
                    }
                },
                { 
                    data: "id", 
                    render: function(data, type, row) {
                        let encName = encodeURIComponent(row.location_name || '');
                        let encAliases = encodeURIComponent(row.alias_names || '');
                        
                        return `
                            <button class="btn btn-sm btn-primary me-1" onclick="editLocation(${data}, '${encName}', '${encAliases}')" title="Edit"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger" onclick="deleteLocation(${data})" title="Delete"><i class="fa fa-trash"></i></button>
                        `;
                    } 
                }
            ],
            order: [] 
        });
    }
</script>