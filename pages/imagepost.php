<style>
    :root {
        --theme-bg: #f4f5f7;
        --theme-card: #ffffff;
        --theme-border: #e2e5e9;
        --theme-text: #333333;
        --theme-muted: #6c757d;
        --theme-dark: #212529;
    }

    #image-publisher-wrapper {
        background-color: var(--theme-bg);
        color: var(--theme-text);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
        padding-bottom: 30px;
    }

    #image-publisher-wrapper .card {
        border: 1px solid var(--theme-border);
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        background: var(--theme-card);
    }

    #image-publisher-wrapper .card-header {
        background: var(--theme-card);
        border-bottom: 1px solid var(--theme-border);
        padding: 12px 15px;
    }

    #image-publisher-wrapper .card-title {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: var(--theme-dark);
    }

    #image-publisher-wrapper .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--theme-dark);
    }

    #image-publisher-wrapper .form-control {
        border: 1px solid var(--theme-border);
        border-radius: 4px;
        font-size: 0.85rem;
    }

    #image-publisher-wrapper .form-control:focus {
        border-color: var(--theme-dark);
        box-shadow: none;
    }

    #image-publisher-wrapper .back-arrow-btn {
        color: var(--theme-dark);
        text-decoration: none;
        margin-right: 10px;
        font-size: 1.1rem;
    }

    #image-publisher-wrapper .form-check-input:checked {
        background-color: #ea4c89;
        border-color: #ea4c89;
    }

    #image-publisher-wrapper .tumblr-switch:checked {
        background-color: #36465D !important;
        border-color: #36465D !important;
    }

    #image-publisher-wrapper .minds-switch:checked {
        background-color: #fbd214 !important;
        border-color: #fbd214 !important;
    }

    #image-publisher-wrapper .pixelfed-switch:checked {
        background-color: #6366f1 !important;
        border-color: #6366f1 !important;
    }

    #image-publisher-wrapper .preview-container {
        width: 100%;
        max-height: 150px;
        border-radius: 4px;
        display: none;
        margin-top: 10px;
        border: 1px dashed var(--theme-border);
        background: #fafafa;
        text-align: center;
        padding: 5px;
    }
    
    #image-publisher-wrapper .preview-container img { 
        max-height: 138px; 
        width: auto; 
        object-fit: contain; 
    }

    #image-publisher-wrapper .table-sm th, 
    #image-publisher-wrapper .table-sm td {
        padding: 8px 10px;
        vertical-align: middle;
        font-size: 0.85rem;
    }
    
    #image-publisher-wrapper .table-light {
        background-color: #f8f9fa !important;
        color: var(--theme-dark);
    }
    
    #image-publisher-wrapper .tbl-thumbnail {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid var(--theme-border);
        background: #fff;
    }

    #image-publisher-wrapper .badge-custom {
        font-size: 0.7rem;
        padding: 0.35em 0.6em;
        margin-left: 10px;
        border-radius: 3px;
        font-weight: 500;
        display: inline-block;
        min-width: 65px;
        text-align: center;
    }
    
    #image-publisher-wrapper .b-published { background: #198754; color: #fff; }
    #image-publisher-wrapper .b-pending { background: #e9ecef; color: var(--theme-dark); border: 1px solid #ced4da; }
    #image-publisher-wrapper .b-failed { background: #dc3545; color: #fff; }
    #image-publisher-wrapper .b-na { background: transparent; color: #adb5bd; border: 1px dashed #e2e5e9; }

    #image-publisher-wrapper .scrollable-text {
        max-height: 60px;
        max-width: 250px;
        overflow-y: auto;
        white-space: pre-wrap;
        font-size: 0.8rem;
        word-break: break-word;
        scrollbar-width: none;
    }

    #image-publisher-wrapper .btn { border-radius: 4px; font-size: 0.8rem; font-weight: 500; }
    #image-publisher-wrapper .btn-dark { background-color: var(--theme-dark); border-color: var(--theme-dark); color: #fff; }
    #image-publisher-wrapper .btn-dark:hover { background-color: #000; border-color: #000; }
    #image-publisher-wrapper .btn-outline-dark:hover { background-color: #e9ecef; color: var(--theme-dark); }
    
    #image-publisher-wrapper .social-mockup {
        background: #fff; border-radius: 8px; border: 1px solid var(--theme-border);
        overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 10px;
    }
    
    #image-publisher-wrapper .social-mockup-header { display: flex; align-items: center; padding: 12px; border-bottom: 1px solid #f0f0f0; }
    #image-publisher-wrapper .social-mockup-avatar { width: 36px; height: 36px; border-radius: 50%; background: #ea4c89; margin-right: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.9rem;}
    #image-publisher-wrapper .mockup-name { font-weight: 600; font-size: 0.9rem; color: var(--theme-dark); margin:0; line-height: 1.2; }
    #image-publisher-wrapper .mockup-caption { padding: 15px; font-size: 0.85rem; color: var(--theme-text); white-space: pre-wrap; word-break: break-word;}
    #image-publisher-wrapper .social-mockup-img { background: #f4f4f4; padding: 15px; text-align: center; }
    #image-publisher-wrapper .social-mockup-img img { width: 100%; max-height: 300px; object-fit: contain; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    #image-publisher-wrapper .mockup-actions { padding: 10px 15px; color: var(--theme-muted); display: flex; justify-content: flex-end; gap: 15px; font-size: 1rem; border-top: 1px solid #f0f0f0; background: #fafafa;}
    
    .crop-img-container { max-height: 60vh; width: 100%; display: flex; justify-content: center; background-color: #000; }
    .crop-img-container img { max-width: 100%; display: block; }
</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">

<div id="image-publisher-wrapper">
    <div class="main-content app-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid pt-3">
                
                <div class="d-flex align-items-center mb-3">
                    <a href="javascript:void(0)" class="back-arrow-btn" onclick="history.go(-1)">
                        <i class="fa fa-chevron-left"></i>
                    </a>
                    <h4 class="mb-0 fw-bold" style="color: var(--theme-dark);">Image Submission Hub</h4>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="card" id="composeCard">
                            <div class="card-header d-flex align-items-center">
                                <h3 class="card-title" id="formTitle"><i class="fa fa-image me-2"></i> Prepare Image Post</h3>
                            </div>
                            <div class="card-body p-3">
                                <form id="imagePostForm">
                                    <input type="hidden" id="edit_post_id" value="">
                                    <input type="hidden" id="edit_existing_image_url" value="">

                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label">Target Platforms</label>
                                            <div class="d-flex flex-column gap-2 p-3">
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox" id="postToDribbble" checked>
                                                    <label class="form-check-label fw-bold" for="postToDribbble" style="color: #ea4c89;">
                                                        <i class="fab fa-dribbble fa-fw"></i> Dribbble
                                                    </label>
                                                </div>
                                                <div class="form-check form-switch m-0 mt-2">
                                                    <input class="form-check-input tumblr-switch" type="checkbox" id="postToTumblr" checked>
                                                    <label class="form-check-label fw-bold" for="postToTumblr" style="color: #36465D;">
                                                        <i class="fab fa-tumblr fa-fw"></i> Tumblr
                                                    </label>
                                                </div>
                                                <div class="form-check form-switch m-0 mt-2">
                                                    <input class="form-check-input minds-switch" type="checkbox" id="postToMinds" checked>
                                                    <label class="form-check-label fw-bold" for="postToMinds" style="color: #333333;">
                                                        <i class="fas fa-lightbulb fa-fw" style="color: #fbd214;"></i> Minds
                                                    </label>
                                                </div>
                                                <div class="form-check form-switch m-0 mt-2">
                                                    <input class="form-check-input pixelfed-switch" type="checkbox" id="postToPixelfed" checked>
                                                    <label class="form-check-label fw-bold" for="postToPixelfed" style="color: #6366f1;">
                                                        <i class="fas fa-camera-retro fa-fw"></i> Pixelfed
                                                    </label>
                                                </div>
                                            </div>

                                            <label class="form-label mt-3" id="imageLabel">Media Upload <span class="text-danger">*</span></label>
                                            <input type="file" id="media_file" class="form-control form-control-sm" accept="image/jpeg, image/png, image/gif">
                                            <small class="text-danger fw-bold d-block mt-2" style="font-size: 0.75rem;">
                                                <i class="fa fa-exclamation-circle"></i> Strict Requirement: Image must be a 4:3 ratio between 400x300 and 1600x1200 pixels.
                                            </small>
                                            <div class="preview-container" id="imagePreviewContainer">
                                                <img id="imagePreview" src="" alt="Preview">
                                            </div>
                                        </div>

                                        <div class="col-md-9 d-flex flex-column">
                                            <div class="mb-2">
                                                <label class="form-label">Shot Title <span class="text-danger">*</span></label>
                                                <input type="text" id="post_title" class="form-control" placeholder="Enter a catchy title..." required>
                                            </div>
                                            
                                            <label class="form-label">Description <span class="text-danger">*</span></label>
                                            <textarea id="post_description" class="form-control flex-grow-1 mb-3" style="min-height: 120px;" placeholder="Write a detailed description of your image..." required></textarea>
                                            
                                            <div class="d-flex gap-2 mt-auto justify-content-end">
                                                <button type="button" id="cancelEditBtn" class="btn btn-outline-dark px-4" style="display: none;">Cancel</button>
                                                <button type="submit" id="saveBtn" class="btn btn-dark px-5">
                                                    <i class="fa fa-save me-1"></i> Save Draft
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-list-alt me-2"></i> Post Manager</h3>
                            </div>
                            <div class="card-body p-3">
                                <div id="tableLoader" class="text-center py-4">
                                    <div class="spinner-border text-dark spinner-border-sm" role="status"></div>
                                    <div class="mt-2 text-muted small">Loading records...</div>
                                </div>
                                <div class="table-responsive" id="tableContainer" style="display: none;">
                                    <table id="postsTable" class="table table-sm table-bordered table-hover text-nowrap align-middle w-100">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 60px;">Image</th>
                                                <th style="width: 200px;">Title</th>
                                                <th>Description</th>
                                                <th>Status Logs</th>
                                                <th>Publishing Action</th>
                                                <th style="width: 130px;">Updated At</th>
                                                <th style="width: 80px;">Manage</th>
                                            </tr>
                                        </thead>
                                        <tbody id="postsTableBody">
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

    <div class="modal fade" id="previewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold" style="color: var(--theme-dark); font-size: 1.1rem;"><i class="fab fa-dribbble" style="color: #ea4c89;"></i> Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="background: #f8f9fa;">
                    <div class="social-mockup m-0">
                        <div class="social-mockup-header">
                            <div class="social-mockup-avatar"><i class="fa fa-user"></i></div>
                            <div>
                                <p class="mockup-name" id="dribbble-preview-title"></p>
                            </div>
                        </div>
                        <div class="social-mockup-img">
                            <img id="dribbble-preview-img" src="" alt="Dribbble Image">
                        </div>
                        <div class="mockup-caption" id="dribbble-preview-desc"></div>
                        <div class="mockup-actions">
                            <span><i class="fas fa-heart text-danger me-1"></i> Like</span>
                            <span><i class="fas fa-comment me-1"></i> Comment</span>
                            <span><i class="fas fa-bookmark me-1"></i> Save</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cropModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="fa fa-crop-alt me-2"></i> Crop Image (Required 4:3 Ratio)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 bg-dark">
                    <div class="crop-img-container">
                        <img id="imageToCrop" src="">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <span class="text-muted small me-auto"><i class="fa fa-info-circle"></i> Image will be strictly verified for 400x300 minimum before saving.</span>
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-dark" id="cropImageBtn"><i class="fa fa-check me-1"></i> Crop & Apply</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
$(document).ready(function() {
    
    const S3_UPLOAD_API = 'https://mapi.goride.run/api/v1-cus/s3-upload-image';
    const LARAVEL_API_BASE = 'https://mapi.goride.run/api/image-posts'; 

    const authHeaders = { 
        'Accept': 'application/json',
        'Authorization': 'Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()'
    };

    let cropper = null;
    let croppedFileObj = null;

    function toast(icon, message) {
        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 4000 })
            .fire({ icon: icon, title: message });
    }

    function parseError(xhr) {
        if (xhr && xhr.responseJSON) {
            return xhr.responseJSON.message || xhr.responseJSON.error || "An error occurred.";
        }
        return `Server Error (${xhr ? xhr.status : 'Unknown'}).`;
    }

    function getBadge(status) {
        if(status === 'published') return '<span class="badge-custom b-published">Published</span>';
        if(status === 'pending') return '<span class="badge-custom b-pending">Pending</span>';
        if(status === 'failed') return '<span class="badge-custom b-failed">Failed</span>';
        return '<span class="badge-custom b-na">N/A</span>';
    }

    function loadPosts() {
        $('#tableContainer').hide();
        $('#tableLoader').show();

        $.ajax({
            url: `${LARAVEL_API_BASE}/all`,
            type: 'GET',
            headers: authHeaders,
            dataType: 'json',
            success: function(response) {
                if (response.status === true) {
                    if ($.fn.DataTable.isDataTable('#postsTable')) {
                        $('#postsTable').DataTable().destroy();
                    }

                    let html = '';
                    response.data.forEach(post => {
                        let safeTitle = post.title ? post.title : '';
                        let encodedTitle = encodeURIComponent(safeTitle);
                        let safeDesc = post.description ? post.description : '';
                        let encodedDesc = encodeURIComponent(safeDesc);

                        let imagePreviewHtml = `<img src="${post.image_url}" class="tbl-thumbnail" alt="Thumb">`;

                        let statusesHtml = `
                            <div class="d-flex flex-column gap-1" style="font-size: 0.8rem;">
                                <div class="d-flex align-items-center gap-2">
                                    <span style="color: #ea4c89; font-weight:600; width: 75px;">
                                        <i class="fab fa-dribbble fa-fw"></i> Dribbble
                                    </span> 
                                    ${getBadge(post.dribbble_status)}
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span style="color: #36465D; font-weight:600; width: 75px;">
                                        <i class="fab fa-tumblr fa-fw"></i> Tumblr
                                    </span> 
                                    ${getBadge(post.tumblr_status)}
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span style="color: #333; font-weight:600; width: 75px;">
                                        <i class="fas fa-lightbulb fa-fw" style="color: #fbd214;"></i> Minds
                                    </span> 
                                    ${getBadge(post.minds_status)}
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span style="color: #6366f1; font-weight:600; width: 75px;">
                                        <i class="fas fa-camera-retro fa-fw"></i> Pixelfed
                                    </span> 
                                    ${getBadge(post.pixelfed_status)}
                                </div>
                            </div>
                        `;

                        let publishHtml = `<div class="d-flex flex-wrap gap-1 align-items-center" style="max-width: 160px;">`;
                        let pendingPlatforms = [];

                        if (post.post_to_dribbble && (post.dribbble_status === 'pending' || post.dribbble_status === 'failed')) {
                            publishHtml += `<button class="btn btn-sm btn-outline-dark publish-single-btn px-2 py-1" data-platform="dribbble" data-id="${post.id}" title="Post to Dribbble"><i class="fab fa-dribbble me-1" style="color: #ea4c89;"></i> Publish</button>`;
                            pendingPlatforms.push('dribbble');
                        }
                        
                        if (post.post_to_tumblr && (post.tumblr_status === 'pending' || post.tumblr_status === 'failed')) {
                            publishHtml += `<button class="btn btn-sm btn-outline-dark publish-single-btn px-2 py-1" data-platform="tumblr" data-id="${post.id}" title="Post to Tumblr"><i class="fab fa-tumblr me-1" style="color: #36465D;"></i> Publish</button>`;
                            pendingPlatforms.push('tumblr');
                        }

                        if (post.post_to_minds && (post.minds_status === 'pending' || post.minds_status === 'failed')) {
                            publishHtml += `<button class="btn btn-sm btn-outline-dark publish-single-btn px-2 py-1" data-platform="minds" data-id="${post.id}" title="Post to Minds"><i class="fas fa-lightbulb me-1" style="color: #fbd214;"></i> Publish</button>`;
                            pendingPlatforms.push('minds');
                        }

                        if (post.post_to_pixelfed && (post.pixelfed_status === 'pending' || post.pixelfed_status === 'failed')) {
                            publishHtml += `<button class="btn btn-sm btn-outline-dark publish-single-btn px-2 py-1" data-platform="pixelfed" data-id="${post.id}" title="Post to Pixelfed"><i class="fas fa-camera-retro me-1" style="color: #6366f1;"></i> Publish</button>`;
                            pendingPlatforms.push('pixelfed');
                        }

                        if (pendingPlatforms.length > 0) {
                            let plts = pendingPlatforms.join(',');
                            publishHtml += `<button class="btn btn-sm btn-dark w-100 mt-1 publish-all-btn" data-id="${post.id}" data-platforms="${plts}"><i class="fa fa-paper-plane me-1"></i> Post Pending</button>`;
                        } else {
                            publishHtml += `<span class="text-muted small w-100 text-center"><i class="fa fa-check-circle me-1"></i> Up to date</span>`;
                        }
                        publishHtml += `</div>`;

                        html += `
                            <tr>
                                <td class="text-center">${imagePreviewHtml}</td>
                                <td><div class="scrollable-text fw-bold">${safeTitle ? safeTitle : '<i class="text-muted">No title</i>'}</div></td>
                                <td><div class="scrollable-text">${safeDesc ? safeDesc : '<i class="text-muted">No description</i>'}</div></td>
                                <td>${statusesHtml}</td>
                                <td>${publishHtml}</td>
                                <td><span class="text-muted small"><i class="far fa-clock me-1"></i>${post.updated_date ? post.updated_date : 'N/A'}</span></td>
                                <td>
                                    <div class="btn-group shadow-sm">
                                        <button class="btn btn-sm btn-light border preview-post-btn" data-img="${post.image_url}" data-title="${encodedTitle}" data-desc="${encodedDesc}" title="Preview Mockup"><i class="fa fa-eye"></i></button>
                                        <button class="btn btn-sm btn-light border edit-btn" data-id="${post.id}" data-img="${post.image_url}" data-title="${encodedTitle}" data-desc="${encodedDesc}" data-dribbble="${post.post_to_dribbble}" data-tumblr="${post.post_to_tumblr}" data-minds="${post.post_to_minds}" data-pixelfed="${post.post_to_pixelfed}" title="Edit Data"><i class="fa fa-edit"></i></button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });

                    $('#postsTableBody').html(html);
                    $('#tableLoader').hide();
                    $('#tableContainer').fadeIn();
                    $('#postsTable').DataTable({ 
                        "order": [[ 5, "desc" ]], 
                        "pageLength": 10, 
                        "responsive": true,
                        "language": { "search": "", "searchPlaceholder": "Search posts..." }
                    });
                } else {
                    $('#tableLoader').hide();
                    toast('error', response.message || 'Failed to load posts.');
                }
            },
            error: function(xhr) {
                $('#tableLoader').hide();
                toast('error', parseError(xhr));
            }
        });
    }

    loadPosts();

    function resetForm() {
        $('#imagePostForm')[0].reset();
        $('#edit_post_id').val('');
        $('#edit_existing_image_url').val('');
        $('#imagePreviewContainer').slideUp();
        $('#imagePreview').attr('src', '');
        croppedFileObj = null;
        
        $('#formTitle').html('<i class="fa fa-image me-2"></i> Prepare Image Post');
        $('#imageLabel').html('Media Upload <span class="text-danger">*</span>');
        $('#saveBtn').html('<i class="fa fa-save me-1"></i> Save Draft');
        $('#cancelEditBtn').hide();
    }

    $('#cancelEditBtn').on('click', resetForm);

    $(document).on('click', '.edit-btn', function() {
        let id = $(this).data('id');
        let img = $(this).data('img');
        let title = decodeURIComponent($(this).data('title'));
        let desc = decodeURIComponent($(this).data('desc'));
        let dribbble = $(this).data('dribbble') == 1;
        let tumblr = $(this).data('tumblr') == 1;
        let minds = $(this).data('minds') == 1;
        let pixelfed = $(this).data('pixelfed') == 1;

        $('#edit_post_id').val(id);
        $('#edit_existing_image_url').val(img);
        
        $('#post_title').val(title);
        $('#post_description').val(desc);
        $('#postToDribbble').prop('checked', dribbble);
        $('#postToTumblr').prop('checked', tumblr);
        $('#postToMinds').prop('checked', minds);
        $('#postToPixelfed').prop('checked', pixelfed);
        $('#media_file').val(''); 
        croppedFileObj = null;

        $('#imagePreview').attr('src', img);
        $('#imagePreviewContainer').slideDown();

        $('#formTitle').html('<i class="fa fa-edit me-2"></i> Edit Draft');
        $('#imageLabel').html('Change Media (Optional)');
        $('#saveBtn').html('<i class="fa fa-sync me-1"></i> Update Post');
        $('#cancelEditBtn').show();

        document.getElementById('composeCard').scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    $(document).on('click', '.preview-post-btn', function() {
        let imgUrl = $(this).data('img');
        let titleText = decodeURIComponent($(this).data('title'));
        let descText = decodeURIComponent($(this).data('desc'));

        $('#dribbble-preview-img').attr('src', imgUrl);
        $('#dribbble-preview-title').text(titleText);
        $('#dribbble-preview-desc').text(descText);

        let myModal = new bootstrap.Modal(document.getElementById('previewModal'));
        myModal.show();
    });

    $('#media_file').on('change', function(e) {
        let file = e.target.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#imageToCrop').attr('src', e.target.result);
                let myModal = new bootstrap.Modal(document.getElementById('cropModal'));
                myModal.show();
            }
            reader.readAsDataURL(file);
        } else if (!$('#edit_post_id').val()) {
            $('#imagePreviewContainer').slideUp();
            croppedFileObj = null;
        } else {
            $('#imagePreview').attr('src', $('#edit_existing_image_url').val());
            croppedFileObj = null;
        }
    });

    $('#cropModal').on('shown.bs.modal', function () {
        let image = document.getElementById('imageToCrop');
        cropper = new Cropper(image, {
            aspectRatio: 4 / 3,
            viewMode: 1,
            autoCropArea: 1,
        });
    }).on('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        if(!croppedFileObj && !$('#edit_post_id').val()) {
            $('#media_file').val(''); 
        }
    });

    $('#cropImageBtn').on('click', function() {
        if (!cropper) return;
        
        let canvas = cropper.getCroppedCanvas({
            maxWidth: 1600,
            maxHeight: 1200
        });
        
        if (!canvas || canvas.width < 400 || canvas.height < 300) {
            toast('warning', 'Validation failed: The cropped area must be at least 400x300 pixels in size.');
            return;
        }
        
        canvas.toBlob(function(blob) {
            croppedFileObj = new File([blob], "cropped_image.jpg", { type: "image/jpeg" });
            $('#imagePreview').attr('src', canvas.toDataURL('image/jpeg'));
            $('#imagePreviewContainer').slideDown();
            $('#cropModal').modal('hide');
        }, 'image/jpeg', 0.95);
    });

    $('#imagePostForm').on('submit', function(e) {
        e.preventDefault();
        
        let postId = $('#edit_post_id').val();
        let isEditMode = (postId !== '');
        
        let existingImgUrl = $('#edit_existing_image_url').val();
        let title = $('#post_title').val().trim();
        let desc = $('#post_description').val().trim();
        let postDribbble = $('#postToDribbble').is(':checked');
        let postTumblr = $('#postToTumblr').is(':checked');
        let postMinds = $('#postToMinds').is(':checked');
        let postPixelfed = $('#postToPixelfed').is(':checked');

        if (!postDribbble && !postTumblr && !postMinds && !postPixelfed) return toast('warning', 'Select at least one platform.');
        if (!isEditMode && !croppedFileObj) return toast('warning', 'Media file required. Please select and crop an image.');
        if (!title) return toast('warning', 'Title is required.');
        if (!desc) return toast('warning', 'Description is required.');

        let $btn = $('#saveBtn');
        let originalText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Processing...');

        if (croppedFileObj) {
            let s3FormData = new FormData();
            s3FormData.append('image', croppedFileObj);
            s3FormData.append('name', 'image_post_' + Date.now());
            s3FormData.append('img_type', 'image_submission');
            s3FormData.append('auth_key', 'asdfghjklpoiuytrewqzxcvbnm!@$%^&*()');

            $.ajax({
                url: S3_UPLOAD_API,
                type: 'POST',
                data: s3FormData,
                processData: false, contentType: false,
                success: function(s3Response) {
                    if (s3Response.status === true) {
                        submitLaravelData(postId, s3Response.data.url, title, desc, postDribbble, postTumblr, postMinds, postPixelfed, $btn, originalText);
                    } else {
                        $btn.prop('disabled', false).html(originalText);
                        toast('error', s3Response.message || 'S3 upload rejected.');
                    }
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).html(originalText);
                    toast('error', 'S3 Upload Error: ' + parseError(xhr));
                }
            });
        } else if (isEditMode) {
            submitLaravelData(postId, existingImgUrl, title, desc, postDribbble, postTumblr, postMinds, postPixelfed, $btn, originalText);
        }
    });

    function submitLaravelData(id, imgUrl, title, desc, postDribbble, postTumblr, postMinds, postPixelfed, $btn, originalText) {
        let isEdit = (id !== null && id !== '');
        let endpoint = isEdit ? `${LARAVEL_API_BASE}/update/${id}` : `${LARAVEL_API_BASE}/create`;
        
        $.ajax({
            url: endpoint,
            type: 'POST',
            headers: authHeaders,
            data: {
                image_url: imgUrl,
                title: title,
                description: desc,
                post_to_dribbble: postDribbble ? 1 : 0,
                post_to_tumblr: postTumblr ? 1 : 0,
                post_to_minds: postMinds ? 1 : 0,
                post_to_pixelfed: postPixelfed ? 1 : 0
            },
            success: function(dbResponse) {
                $btn.prop('disabled', false).html(originalText);
                if(dbResponse.status) {
                    toast('success', isEdit ? 'Update saved.' : 'Draft saved.');
                    resetForm();
                    loadPosts(); 
                } else {
                    toast('error', dbResponse.message);
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(originalText);
                toast('error', parseError(xhr));
            }
        });
    }

    function triggerPublish(platform, postId) {
        return $.ajax({
            url: `${LARAVEL_API_BASE}/publish/${platform}/${postId}`,
            type: 'POST',
            headers: authHeaders
        });
    }

    $(document).on('click', '.publish-single-btn', function() {
        let postId = $(this).data('id');
        let platform = $(this).data('platform');
        let $btn = $(this);
        let origHtml = $btn.html();

        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        triggerPublish(platform, postId)
            .done(function(res) {
                if(res.status) {
                    toast('success', res.message);
                    loadPosts();
                } else {
                    toast('error', res.message || 'Failed to publish.');
                    $btn.prop('disabled', false).html(origHtml);
                }
            })
            .fail(function(xhr) { 
                toast('error', parseError(xhr)); 
                $btn.prop('disabled', false).html(origHtml);
            });
    });

    $(document).on('click', '.publish-all-btn', async function() {
        let postId = $(this).data('id');
        let platforms = $(this).data('platforms').split(',');
        let $btn = $(this);
        let origText = $btn.html();

        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Posting...');
        
        let successCount = 0;
        let hasError = false;
        let errorMessage = '';
        
        for (let i = 0; i < platforms.length; i++) {
            try {
                let res = await triggerPublish(platforms[i], postId);
                if(res.status) {
                    successCount++;
                } else {
                    hasError = true;
                    errorMessage = res.message || `Failed to post to ${platforms[i]}`;
                }
            } catch (err) {
                hasError = true;
                errorMessage = parseError(err);
            }
        }
        
        if (hasError) {
            toast('error', errorMessage);
            if (successCount > 0) {
                loadPosts(); 
            } else {
                $btn.prop('disabled', false).html(origText);
            }
        } else {
            toast('success', `Posted ${successCount}/${platforms.length} Platforms Successfully.`);
            loadPosts();
        }
    });

});
</script>