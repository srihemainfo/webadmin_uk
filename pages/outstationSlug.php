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
    .cursor-pointer {
        cursor: pointer;
    }
</style>

<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <div class="page-header">
                <h1 class="page-title">
                    <a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>
                    OutStation Slugs Management
                </h1>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center">
                            <h3 class="card-title">OutStation Slugs List</h3>
                            <button class="btn btn-primary" id="btnAddSlug">
                                <i class="fa fa-plus"></i> Add New Slug
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="suffixSlugTable" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th width="5%">S.No</th>
                                            <th>Slug Name</th>
                                            <th width="15%" class="text-center">Status</th>
                                            <th width="15%" class="text-center">Action</th>
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

<div class="modal fade" id="slugModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle">Add New OutStation Slug</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="slugForm">
                    <input type="hidden" id="old_slug" name="old_slug" value="">
                    
                    <div class="mb-3">
                        <label for="slugName" class="form-label">Slug Name <span style="color:red;">*</span></label>
                        <input type="text" class="form-control" id="slugName" name="slug" placeholder="e.g. outstation-taxi" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="btnSaveSlug">Save Slug</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    var origin = window.location.origin;
    // Update to point to the suffix slug backend service
    var ajaxUrl = origin + "/ajax/service/outstation_slug_services.php"; 
    var slugTable;

    $(document).ready(function() {
        initTable();
    });

    // Reusable Toast Alert Function
    function toast(icon, message) {
        Swal.mixin({ 
            toast: true, 
            position: 'top-end', 
            showConfirmButton: false, 
            timer: 4000 
        }).fire({ 
            icon: icon, 
            title: message 
        });
    }

    function initTable() {
        slugTable = $('#suffixSlugTable').DataTable({
            processing: true, 
            serverSide: false,
            destroy: true,
            ajax: {
                url: ajaxUrl,
                type: "POST",
                data: { action: 'fetch' }
            },
            columns: [
                { 
                    data: null, 
                    render: function (data, type, row, meta) { 
                        return meta.row + 1; 
                    } 
                },
                { data: "slug", render: function(data) { return `<strong>${data}</strong>`; } },
                { 
                    data: "status",
                    className: "text-center",
                    render: function(data, type, row) {
                        let isActive = (data == 0);
                        let badgeClass = isActive ? 'bg-success' : 'bg-danger';
                        let text = isActive ? 'Active' : 'Inactive';
                        
                        return `
                            <span class="badge ${badgeClass} cursor-pointer toggle-status" 
                                  data-slug="${row.slug}" 
                                  data-status="${data}" 
                                  title="Click to change status">
                                ${text}
                            </span>
                        `;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    className: "text-center",
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-sm btn-primary edit-btn me-1" data-slug="${row.slug}" title="Edit">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-slug="${row.slug}" title="Delete">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        `;
                    }
                }
            ],
            order: [] 
        });
    }

    // Open Modal for ADD
    $('#btnAddSlug').on('click', function() {
        $('#slugForm')[0].reset();
        $('#old_slug').val(''); 
        $('#modalTitle').text('Add New OutStation Slug');
        $('#slugModal').modal('show');
    });

    // Open Modal for EDIT
    $(document).on('click', '.edit-btn', function() {
        let slug = $(this).data('slug');
        $('#slugName').val(slug);
        $('#old_slug').val(slug); 
        $('#modalTitle').text('Edit OutStation Slug');
        $('#slugModal').modal('show');
    });

    // Save/Update Slug
    $('#btnSaveSlug').on('click', function() {
        let slug = $('#slugName').val().trim();
        let old_slug = $('#old_slug').val().trim();
        
        if (slug === '') {
            toast('error', 'Slug name is required');
            return;
        }

        let actionType = (old_slug === '') ? 'create' : 'update';

        $.ajax({
            url: ajaxUrl,
            type: "POST",
            dataType: "json",
            data: {
                action: actionType,
                slug: slug,
                old_slug: old_slug
            },
            success: function(response) {
                if (response.status) {
                    $('#slugModal').modal('hide');
                    toast('success', response.message);
                    slugTable.ajax.reload(null, false);
                } else {
                    toast('error', response.message);
                }
            },
            error: function() {
                toast('error', 'Server error occurred while saving.');
            }
        });
    });

    // Permanent Delete
    $(document).on('click', '.delete-btn', function() {
        let slug = $(this).data('slug');

        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to permanently delete '${slug}'.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: ajaxUrl,
                    type: "POST",
                    dataType: "json",
                    data: { action: 'delete', slug: slug },
                    success: function(response) {
                        if (response.status) {
                            toast('success', response.message);
                            slugTable.ajax.reload(null, false);
                        } else {
                            toast('error', response.message);
                        }
                    },
                    error: function() {
                        toast('error', 'Server error occurred during deletion.');
                    }
                });
            }
        });
    });

    // Toggle Status
    $(document).on('click', '.toggle-status', function() {
        let slug = $(this).data('slug');
        let current_status = $(this).data('status');

        $.ajax({
            url: ajaxUrl,
            type: "POST",
            dataType: "json",
            data: {
                action: 'toggle_status',
                slug: slug,
                current_status: current_status
            },
            success: function(response) {
                if (response.status) {
                    toast('success', response.message);
                    slugTable.ajax.reload(null, false);
                } else {
                    toast('error', response.message);
                }
            },
            error: function() {
                toast('error', 'Failed to update status.');
            }
        });
    });
</script>