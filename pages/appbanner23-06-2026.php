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
    .action-btns button { margin-right: 5px; }
</style>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.3/viewer.min.css">

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            
            <div class="page-header">
                <h1 class="page-title">
                    <a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>
                    Manage Go App Banners
                </h1>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title" id="formCardTitle">Add New Banner</h3>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="edit_id" value="-1">
                            <input type="hidden" id="existing_image_id" value="">
                            
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Title <span style="color:red;">*</span></label>
                                    <input type="text" id="banner_title" class="form-control" placeholder="e.g. 30% OFF">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Subtitle</label>
                                    <input type="text" id="banner_subtitle" class="form-control" placeholder="e.g. On your first Intercity ride">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Route <span style="color:red;">*</span></label>
                                    <input type="text" id="banner_route" class="form-control" placeholder="e.g. /booking" value="">
                                </div>
                                <div class="col-md-1 mb-3">
                                    <label class="form-label">Order</label>
                                    <input type="number" id="banner_order_num" class="form-control" value="0">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Is Active</label>
                                    <select id="banner_active" class="form-control">
                                        <option value="true">Yes</option>
                                        <option value="false">No</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Display State</label>
                                    <select id="display_state" class="form-control">
                                        <option value="">All States</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Banner Image <span style="color:red;" id="img_req_asterisk">*</span></label>
                                    <input type="file" id="banner_image" class="form-control" accept="image/*">
                                    <small class="text-muted" id="img_help_text">Select an image to upload to S3.</small>
                                </div>
                            </div>
                            
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <button type="button" id="saveBtn" class="btn btn-success">Save Banner</button>
                                    <button type="button" id="cancelEditBtn" class="btn btn-secondary" style="display:none;" onclick="resetForm()">Cancel Edit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Existing Home Banners</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="bannersTable" class="table table-bordered table-hover text-nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Order</th>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Subtitle</th>
                                            <th>Route</th>
                                            <th>Display State</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bannersBody">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.3/viewer.min.js"></script>

<script>
    const API_URL = 'ajax/service/manage_banners_api.php'; 

    // --- UTILITY FUNCTIONS ---
    function toast(icon, message) {
        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 4000 }).fire({ icon: icon, title: message });
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function loadStates() {
        $.ajax({
            url: API_URL,
            type: 'POST',
            dataType: 'json',
            data: { method: 'get_states' },
            success: function(response) {
                if (response.status) {
                    let html = '<option value="">All States</option>';
                    response.data.forEach(function(state) {
                        html += `<option value="${state}">${state}</option>`;
                    });
                    $('#display_state').html(html);
                }
            }
        });
    }

    // Initialize Select2 after document is ready
    $(document).ready(function() {
        $('#display_state').select2({
            placeholder: 'Select State',
            width: '100%',
            allowClear: true
        });

        loadStates();
        loadBanners();

        // --- 2. VIEW IMAGE (LAZY LOAD USING VIEWER.JS) ---
        $(document).on('click', '.view-img-btn', function() {
            let imgUrl = $(this).attr('data-url');
            if (!imgUrl) return toast('error', 'Image URL not found!');

            let $btn = $(this);
            let originalText = $btn.html();
            
            $btn.html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);

            let image = new Image();
            image.onload = function() {
                $btn.html(originalText).prop('disabled', false); 
                let viewer = new Viewer(image, {
                    hidden: function () { viewer.destroy(); },
                    toolbar: { zoomIn: 1, zoomOut: 1, oneToOne: 1, reset: 1, rotateLeft: 1, rotateRight: 1, flipHorizontal: 1, flipVertical: 1 }
                });
                viewer.show();
            };
            image.onerror = function() {
                $btn.html(originalText).prop('disabled', false);
                toast('error', 'Failed to load image.');
            };
            image.src = imgUrl; 
        });

        // --- 3. SAVE / UPLOAD LOGIC ---
        $('#saveBtn').on('click', function() {
            let title = $('#banner_title').val().trim();
            let subtitle = $('#banner_subtitle').val().trim();
            let route = $('#banner_route').val().trim();
            let order_num = parseInt($('#banner_order_num').val()) || 0;
            let is_active = ($('#banner_active').val() === 'true');
            let fileInput = $('#banner_image')[0];
            let display_state = $('#display_state').val() || ''; // Capture the state clearly
            
            let editId = parseInt($('#edit_id').val());
            let existingImageId = $('#existing_image_id').val();

            if (!title) return toast('warning', 'Title is required');
            if (!route) return toast('warning', 'Route is required');
            if (editId === -1 && fileInput.files.length === 0) {
                return toast('warning', 'Image is required for new banners');
            }

            let $btn = $(this);
            let originalBtnText = $btn.html();
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i>Processing...');

            let saveToDatabase = function(finalImageId) {
                $btn.html('<i class="fa fa-spinner fa-spin me-2"></i>Saving to Database...');
                
                $.ajax({
                    url: API_URL,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        method: 'save_banner',
                        id: editId,
                        title: title,
                        subtitle: subtitle,
                        route: route,
                        order_num: order_num,
                        is_active: is_active,
                        image_id: finalImageId,
                        display_state: display_state
                    },
                    success: function(response) {
                        $btn.prop('disabled', false).html(originalBtnText);
                        if(response.status) {
                            toast('success', response.message);
                            resetForm();
                            loadBanners(); 
                        } else {
                            toast('error', response.message);
                        }
                    },
                    error: function() {
                        $btn.prop('disabled', false).html(originalBtnText);
                        toast('error', 'Database save failed.');
                    }
                });
            };

            // Pre-check the order_num BEFORE uploading to S3 (Now including display_state)
            $.ajax({
                url: API_URL,
                type: 'POST',
                dataType: 'json',
                data: {
                    method: 'check_order_num',
                    id: editId,
                    order_num: order_num,
                    display_state: display_state // Added state payload to scope the validation
                },
                success: function(res) {
                    if (res.status === false) {
                        $btn.prop('disabled', false).html(originalBtnText);
                        return toast('error', res.message);
                    }

                    // Proceed with S3 upload if file exists
                    if (fileInput.files.length > 0) {
                        $btn.html('<i class="fa fa-spinner fa-spin me-2"></i>Uploading Image...');
                        let formData = new FormData();
                        formData.append('image', fileInput.files[0]);
                        formData.append('name', title.replace(/\s+/g, '_') + '_' + Date.now());
                        formData.append('img_type', 'home_banner');
                        formData.append('auth_key', 'asdfghjklpoiuytrewqzxcvbnm!@$%^&*()');

                        $.ajax({
                            url: 'https://www.goride.run/api/v1-cus/s3-upload-image',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                if (response.status === true) {
                                    let newImageId = response.data.id; 
                                    saveToDatabase(newImageId);
                                } else {
                                    $btn.prop('disabled', false).html(originalBtnText);
                                    toast('error', response.message || 'S3 Upload failed.');
                                }
                            },
                            error: function() {
                                $btn.prop('disabled', false).html(originalBtnText);
                                toast('error', 'S3 API Upload Error.');
                            }
                        });
                    } else {
                        saveToDatabase(existingImageId);
                    }
                },
                error: function() {
                    $btn.prop('disabled', false).html(originalBtnText);
                    toast('error', 'Failed to validate order number.');
                }
            });
        });
    });

    // --- 1. FETCH & RENDER BANNERS ---
    function loadBanners() {
        $.ajax({
            url: API_URL,
            type: 'POST',
            dataType: 'json',
            data: { method: 'get_banners' },
            success: function(response) {
                if (response.status === true) {
                    let html = '';
                    
                    response.data.forEach((banner) => {
                        let title = escapeHtml(banner.title);
                        let subtitle = escapeHtml(banner.subtitle);
                        let route = escapeHtml(banner.route);
                        let displayState = banner.display_state ? escapeHtml(banner.display_state) : 'All States';
                        let order = banner.order_num || 0;
                        let image_id = banner.image_id || '';
                        let imgUrl = escapeHtml(banner.image_url);
                        
                        let jsTitle = encodeURIComponent(banner.title || '').replace(/'/g, "%27");
                        let jsSubtitle = encodeURIComponent(banner.subtitle || '').replace(/'/g, "%27");
                        let jsRoute = encodeURIComponent(banner.route || '').replace(/'/g, "%27");
                        let jsDisplayState = encodeURIComponent(banner.display_state || '').replace(/'/g, "%27");

                        let isActive = banner.is_active == 1;
                        let isActiveBadge = isActive 
                            ? '<span class="badge bg-success">Active</span>' 
                            : '<span class="badge bg-danger">Inactive</span>';
                            
                        let imageBtn = imgUrl 
                            ? `<button class="btn btn-sm btn-info view-img-btn" data-url="${imgUrl}"><i class="fa fa-eye"></i> View</button>` 
                            : '<span class="text-muted">No Image</span>';

                        html += `<tr>
                            <td>${order}</td>
                            <td>${imageBtn}</td>
                            <td>${title}</td>
                            <td>${subtitle}</td>
                            <td>${route}</td>
                            <td>${displayState}</td>
                            <td>${isActiveBadge}</td>
                            <td class="action-btns">
                                <button class="btn btn-sm btn-primary" onclick="editBanner(${banner.id}, '${jsTitle}', '${jsSubtitle}', '${jsRoute}', ${order}, ${isActive}, '${image_id}', '${jsDisplayState}')" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteBanner(${banner.id})" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>`;
                    });

                    if ($.fn.DataTable.isDataTable('#bannersTable')) {
                        $('#bannersTable').DataTable().destroy();
                    }
                    $('#bannersBody').html(html);
                    $('#bannersTable').DataTable({
                        "order": [[ 5, "asc" ], [0, "asc"]], // Ordered by state first, then order num
                        "pageLength": 10
                    });
                } else {
                    toast('info', 'No banners found.');
                }
            },
            error: function() {
                toast('error', 'Failed to fetch data from Database.');
            }
        });
    }

    // --- 4. POPULATE EDIT FORM ---
    function editBanner(id, encTitle, encSubtitle, encRoute, order, is_active, image_id, encDisplayState) {
        let title = decodeURIComponent(encTitle);
        let subtitle = decodeURIComponent(encSubtitle);
        let route = decodeURIComponent(encRoute);
        let displayState = decodeURIComponent(encDisplayState);

        $('#formCardTitle').text('Edit Banner');
        $('#saveBtn').text('Update Banner');
        $('#cancelEditBtn').show();
        $('#img_req_asterisk').hide();
        $('#img_help_text').text('Leave empty to keep existing image.');
        
        $('#edit_id').val(id);
        $('#existing_image_id').val(image_id);
        
        $('#banner_title').val(title);
        $('#banner_subtitle').val(subtitle);
        $('#banner_route').val(route);
        $('#banner_order_num').val(order);
        $('#banner_active').val(is_active ? 'true' : 'false');
        
        // Update Select2 dropdown
        $('#display_state').val(displayState).trigger('change');
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // --- 5. DELETE LOGIC ---
    function deleteBanner(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will remove the banner permanently from the database!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: API_URL,
                    type: 'POST',
                    dataType: 'json',
                    data: { method: 'delete_banner', id: id },
                    success: function(response) {
                        if(response.status) {
                            toast('success', 'Banner deleted.');
                            loadBanners(); 
                        } else {
                            toast('error', response.message);
                        }
                    },
                    error: function() {
                        toast('error', 'Failed to delete banner.');
                    }
                });
            }
        });
    }

    // --- 6. RESET FORM UTILITY ---
    function resetForm() {
        $('#formCardTitle').text('Add New Banner');
        $('#saveBtn').text('Save Banner');
        $('#cancelEditBtn').hide();
        $('#img_req_asterisk').show();
        $('#img_help_text').text('Select an image to upload to S3.');
        
        $('#edit_id').val('-1');
        $('#existing_image_id').val('');
        $('#banner_title').val('');
        $('#banner_subtitle').val('');
        $('#banner_route').val('/booking');
        $('#banner_order_num').val('0');
        $('#banner_active').val('true');
        $('#banner_image').val('');
        $('#display_state').val('').trigger('change');
    }
</script>