<style>
    :root {
        --theme-bg: #f4f5f7;
        --theme-card: #ffffff;
        --theme-border: #e2e5e9;
        --theme-text: #333333;
        --theme-muted: #6c757d;
        --theme-dark: #212529;
    }

    #social-publisher-wrapper {
        background-color: var(--theme-bg);
        color: var(--theme-text);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
        padding-bottom: 30px;
    }

    #social-publisher-wrapper .card {
        border: 1px solid var(--theme-border);
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        background: var(--theme-card);
    }

    #social-publisher-wrapper .card-header {
        background: var(--theme-card);
        border-bottom: 1px solid var(--theme-border);
        padding: 12px 15px;
    }

    #social-publisher-wrapper .card-title {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: var(--theme-dark);
    }

    #social-publisher-wrapper .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--theme-dark);
    }

    #social-publisher-wrapper .form-control {
        border: 1px solid var(--theme-border);
        border-radius: 4px;
        font-size: 0.85rem;
    }

    #social-publisher-wrapper .form-control:focus {
        border-color: var(--theme-dark);
        box-shadow: none;
    }

    #social-publisher-wrapper .back-arrow-btn {
        color: var(--theme-dark);
        text-decoration: none;
        margin-right: 10px;
        font-size: 1.1rem;
    }

    #social-publisher-wrapper .form-check-input:checked {
        background-color: var(--theme-dark);
        border-color: var(--theme-dark);
    }

    #social-publisher-wrapper .preview-container {
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
    
    #social-publisher-wrapper .preview-container img { 
        max-height: 138px; 
        width: auto; 
        object-fit: contain; 
    }

    #social-publisher-wrapper .table-sm th, 
    #social-publisher-wrapper .table-sm td {
        padding: 8px 10px;
        vertical-align: middle;
        font-size: 0.85rem;
    }
    
    #social-publisher-wrapper .table-light {
        background-color: #f8f9fa !important;
        color: var(--theme-dark);
    }
    
    #social-publisher-wrapper .tbl-thumbnail {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid var(--theme-border);
        background: #fff;
    }

    #social-publisher-wrapper .badge-custom {
        font-size: 0.7rem;
        padding: 0.35em 0.6em;
        border-radius: 3px;
        font-weight: 500;
        display: inline-block;
        min-width: 65px;
        text-align: center;
    }
    
    #social-publisher-wrapper .b-published { background: var(--theme-dark); color: #fff; }
    #social-publisher-wrapper .b-pending { background: #e9ecef; color: var(--theme-dark); border: 1px solid #ced4da; }
    #social-publisher-wrapper .b-failed { background: #6c757d; color: #fff; }
    #social-publisher-wrapper .b-na { background: transparent; color: #adb5bd; border: 1px dashed #e2e5e9; }

    #social-publisher-wrapper .scrollable-caption {
        max-height: 60px;
        max-width: 250px;
        overflow-y: auto;
        white-space: pre-wrap;
        font-size: 0.8rem;
        word-break: break-word;
        scrollbar-width: none;
    }

    #social-publisher-wrapper .btn { border-radius: 4px; font-size: 0.8rem; font-weight: 500; }
    #social-publisher-wrapper .btn-dark { background-color: var(--theme-dark); border-color: var(--theme-dark); color: #fff; }
    #social-publisher-wrapper .btn-dark:hover { background-color: #000; border-color: #000; }
    #social-publisher-wrapper .btn-outline-dark:hover { background-color: #e9ecef; color: var(--theme-dark); }
    
    #social-publisher-wrapper .social-mockup {
        background: #fff; border-radius: 6px; border: 1px solid var(--theme-border);
        overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.05); margin-bottom: 10px;
    }
    
    #social-publisher-wrapper .social-mockup-header { display: flex; align-items: center; padding: 10px; border-bottom: 1px solid #f0f0f0; }
    #social-publisher-wrapper .social-mockup-avatar { width: 32px; height: 32px; border-radius: 50%; background: var(--theme-border); margin-right: 10px; display: flex; align-items: center; justify-content: center; color: var(--theme-muted); font-size: 0.8rem;}
    #social-publisher-wrapper .mockup-name { font-weight: 600; font-size: 0.85rem; color: var(--theme-dark); margin:0; line-height: 1.1; }
    #social-publisher-wrapper .mockup-time { font-size: 0.7rem; color: var(--theme-muted); margin:0; }
    #social-publisher-wrapper .mockup-caption { padding: 10px; font-size: 0.85rem; color: var(--theme-text); white-space: pre-wrap; word-break: break-word;}
    #social-publisher-wrapper .social-mockup-img img { width: 100%; max-height: 250px; object-fit: contain; background: #fafafa; border-bottom: 1px solid #f0f0f0; }
    #social-publisher-wrapper .mockup-actions { padding: 8px 10px; color: var(--theme-muted); display: flex; gap: 15px; font-size: 1rem; }
</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<div id="social-publisher-wrapper">
    <div class="main-content app-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid pt-3">
                
                <div class="d-flex align-items-center mb-3">
                    <a href="javascript:void(0)" class="back-arrow-btn" onclick="history.go(-1)">
                        <i class="fa fa-chevron-left"></i>
                    </a>
                    <h4 class="mb-0 fw-bold" style="color: var(--theme-dark);">Social Media Publisher</h4>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="card" id="composeCard">
                            <div class="card-header d-flex align-items-center">
                                <h3 class="card-title" id="formTitle"><i class="fa fa-pen me-2"></i> Compose Post</h3>
                            </div>
                            <div class="card-body p-3">
                                <form id="socialPostForm">
                                    <input type="hidden" id="edit_post_id" value="">
                                    <input type="hidden" id="edit_existing_image_url" value="">

                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Target Platforms</label>
                                            <div class="d-flex flex-column gap-2 p-2" style="background: #fafafa;">
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox" id="postToFB">
                                                    <label class="form-check-label" for="postToFB"><i class="fab fa-facebook fa-fw"></i> Facebook</label>
                                                </div>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox" id="postToIG">
                                                    <label class="form-check-label" for="postToIG"><i class="fab fa-instagram fa-fw"></i> Instagram</label>
                                                </div>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox" id="postToPin">
                                                    <label class="form-check-label" for="postToPin"><i class="fab fa-pinterest fa-fw"></i> Pinterest</label>
                                                </div>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox" id="postToLI">
                                                    <label class="form-check-label" for="postToLI"><i class="fab fa-linkedin fa-fw"></i> LinkedIn</label>
                                                </div>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox" id="postToGoogle">
                                                    <label class="form-check-label" for="postToGoogle"><i class="fab fa-google fa-fw"></i> Google</label>
                                                </div>
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input" type="checkbox" id="postToDevto">
                                                    <label class="form-check-label" for="postToDevto"><i class="fab fa-dev fa-fw"></i> Dev.to</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label" id="imageLabel">Media <span class="text-danger">*</span></label>
                                            <input type="file" id="media_file" class="form-control form-control-sm" accept="image/jpeg, image/png">
                                            <div class="preview-container" id="imagePreviewContainer">
                                                <img id="imagePreview" src="" alt="Preview">
                                            </div>
                                        </div>

                                        <div class="col-md-5 d-flex flex-column">
                                            <label class="form-label">Caption <span class="text-danger">*</span></label>
                                            <textarea id="post_message" class="form-control flex-grow-1 mb-2" style="min-height: 80px;" placeholder="Write a caption..." required></textarea>
                                            <div class="d-flex gap-2 mt-auto">
                                                <button type="submit" id="saveBtn" class="btn btn-dark w-100">
                                                    <i class="fa fa-save me-1"></i> Save Draft
                                                </button>
                                                <button type="button" id="cancelEditBtn" class="btn btn-outline-dark" style="display: none;">Cancel</button>
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
                                <h3 class="card-title"><i class="fa fa-list me-2"></i> Post Manager</h3>
                            </div>
                            <div class="card-body p-3">
                                <div id="tableLoader" class="text-center py-4">
                                    <div class="spinner-border text-dark spinner-border-sm" role="status"></div>
                                    <div class="mt-2 text-muted small">Loading Posts...</div>
                                </div>
                                <div class="table-responsive" id="tableContainer" style="display: none;">
                                    <table id="postsTable" class="table table-sm table-bordered table-hover text-nowrap align-middle w-100">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 60px;">Image</th>
                                                <th>Caption</th>
                                                <th>Status Logs</th>
                                                <th>Publishing Action</th>
                                                <th style="width: 130px;">Updated At</th>
                                                <th style="width: 120px;">Manage</th>
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
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold" style="color: var(--theme-dark); font-size: 1.1rem;">Post Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="background: #f8f9fa;">
                    <div class="row g-3 justify-content-center">
                        <div class="col-lg-4 col-md-6">
                            <div class="text-center fw-bold mb-2 small"><i class="fab fa-facebook"></i> Facebook</div>
                            <div class="social-mockup">
                                <div class="social-mockup-header">
                                    <div class="social-mockup-avatar"><i class="fa fa-car"></i></div>
                                    <div>
                                        <p class="mockup-name">GoRide</p>
                                        <p class="mockup-time">Just now ¡¤ <i class="fa fa-globe"></i></p>
                                    </div>
                                </div>
                                <div class="mockup-caption" id="fb-preview-caption"></div>
                                <div class="social-mockup-img">
                                    <img id="fb-preview-img" src="" alt="FB Image">
                                </div>
                                <div class="mockup-actions justify-content-around" style="font-size: 0.85rem; border-top: 1px solid #f0f0f0;">
                                    <span><i class="far fa-thumbs-up me-1"></i> Like</span>
                                    <span><i class="far fa-comment me-1"></i> Comment</span>
                                    <span><i class="fas fa-share me-1"></i> Share</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6">
                            <div class="text-center fw-bold mb-2 small"><i class="fab fa-instagram"></i> Instagram</div>
                            <div class="social-mockup">
                                <div class="social-mockup-header">
                                    <div class="social-mockup-avatar"><i class="fa fa-car"></i></div>
                                    <p class="mockup-name">goride.run</p>
                                </div>
                                <div class="social-mockup-img">
                                    <img id="ig-preview-img" src="" alt="IG Image">
                                </div>
                                <div class="mockup-actions pb-0">
                                    <i class="far fa-heart"></i>
                                    <i class="far fa-comment"></i>
                                    <i class="far fa-paper-plane"></i>
                                </div>
                                <div class="mockup-caption pt-1">
                                    <span class="fw-bold me-1">goride.run</span><span id="ig-preview-caption-text"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6">
                            <div class="text-center fw-bold mb-2 small"><i class="fab fa-linkedin"></i> LinkedIn</div>
                            <div class="social-mockup">
                                <div class="social-mockup-header">
                                    <div class="social-mockup-avatar"><i class="fa fa-car"></i></div>
                                    <div>
                                        <p class="mockup-name">GoRide</p>
                                        <p class="mockup-time">Just now ¡¤ <i class="fa fa-globe"></i></p>
                                    </div>
                                </div>
                                <div class="mockup-caption" id="li-preview-caption"></div>
                                <div class="social-mockup-img">
                                    <img id="li-preview-img" src="" alt="LI Image">
                                </div>
                                <div class="mockup-actions justify-content-around" style="font-size: 0.85rem; border-top: 1px solid #f0f0f0;">
                                    <span><i class="far fa-thumbs-up me-1"></i> Like</span>
                                    <span><i class="far fa-comment me-1"></i> Comment</span>
                                    <span><i class="fas fa-share me-1"></i> Repost</span>
                                    <span><i class="fas fa-paper-plane me-1"></i> Send</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6">
                            <div class="text-center fw-bold mb-2 small"><i class="fab fa-google"></i> Google</div>
                            <div class="social-mockup">
                                <div class="social-mockup-header">
                                    <div class="social-mockup-avatar"><i class="fa fa-car"></i></div>
                                    <div>
                                        <p class="mockup-name">GoRide</p>
                                        <p class="mockup-time">Just now</p>
                                    </div>
                                </div>
                                <div class="mockup-caption" id="google-preview-caption"></div>
                                <div class="social-mockup-img">
                                    <img id="google-preview-img" src="" alt="Google Image">
                                </div>
                                <div class="mockup-actions justify-content-around" style="font-size: 0.85rem; border-top: 1px solid #f0f0f0;">
                                    <span><i class="fas fa-share-alt me-1"></i> Share</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6">
                            <div class="text-center fw-bold mb-2 small"><i class="fab fa-dev"></i> Dev.to</div>
                            <div class="social-mockup">
                                <div class="social-mockup-header">
                                    <div class="social-mockup-avatar"><i class="fa fa-car"></i></div>
                                    <div>
                                        <p class="mockup-name">GoRide</p>
                                        <p class="mockup-time">Just now</p>
                                    </div>
                                </div>
                                <div class="social-mockup-img">
                                    <img id="devto-preview-img" src="" alt="Dev.to Image">
                                </div>
                                <div class="mockup-caption fw-bold" style="font-size: 1rem; border-top: 1px solid #f0f0f0;" id="devto-preview-title"></div>
                                <div class="mockup-actions justify-content-around" style="font-size: 0.85rem; border-top: 1px solid #f0f0f0;">
                                    <span><i class="far fa-heart me-1"></i> Like</span>
                                    <span><i class="far fa-comment me-1"></i> Comment</span>
                                    <span><i class="far fa-bookmark me-1"></i> Save</span>
                                </div>
                            </div>
                        </div>

                    </div>
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

<script>
$(document).ready(function() {
    
    const S3_UPLOAD_API = 'https://mapi.goride.run/api/v1-cus/s3-upload-image';
    const LARAVEL_API_BASE = 'https://mapi.goride.run/api/social-posts'; 

    const authHeaders = { 
        'Accept': 'application/json',
        'Authorization': 'Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()'
    };

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
                        let safeCaption = post.caption ? post.caption : '';
                        let encodedCaption = encodeURIComponent(safeCaption);

                        let imagePreviewHtml = `<img src="${post.image_url}" class="tbl-thumbnail" alt="Thumb">`;

                        let statusesHtml = `
                            <div class="d-flex flex-column gap-1" style="font-size: 0.8rem;">
                                <div class="d-flex justify-content-between align-items-center" style="width: 140px;"><span><i class="fab fa-facebook fa-fw"></i> FB</span> ${getBadge(post.fb_status)}</div>
                                <div class="d-flex justify-content-between align-items-center" style="width: 140px;"><span><i class="fab fa-instagram fa-fw"></i> IG</span> ${getBadge(post.ig_status)}</div>
                                <div class="d-flex justify-content-between align-items-center" style="width: 140px;"><span><i class="fab fa-pinterest fa-fw"></i> PIN</span> ${getBadge(post.pin_status)}</div>
                                <div class="d-flex justify-content-between align-items-center" style="width: 140px;"><span><i class="fab fa-linkedin fa-fw"></i> LI</span> ${getBadge(post.li_status)}</div>
                                <div class="d-flex justify-content-between align-items-center" style="width: 140px;"><span><i class="fab fa-google fa-fw"></i> GL</span> ${getBadge(post.google_status)}</div>
                                <div class="d-flex justify-content-between align-items-center" style="width: 140px;"><span><i class="fab fa-dev fa-fw"></i> DEV</span> ${getBadge(post.devto_status)}</div>
                            </div>
                        `;

                        let publishHtml = `<div class="d-flex flex-wrap gap-1 align-items-center" style="max-width: 160px;">`;
                        let pendingPlatforms = [];

                        if (post.post_to_fb && (post.fb_status === 'pending' || post.fb_status === 'failed')) {
                            publishHtml += `<button class="btn btn-sm btn-outline-dark publish-single-btn px-2 py-1" data-platform="facebook" data-id="${post.id}" title="Post to Facebook"><i class="fab fa-facebook"></i></button>`;
                            pendingPlatforms.push('facebook');
                        }
                        if (post.post_to_ig && (post.ig_status === 'pending' || post.ig_status === 'failed')) {
                            publishHtml += `<button class="btn btn-sm btn-outline-dark publish-single-btn px-2 py-1" data-platform="instagram" data-id="${post.id}" title="Post to Instagram"><i class="fab fa-instagram"></i></button>`;
                            pendingPlatforms.push('instagram');
                        }
                        if (post.post_to_pin && (post.pin_status === 'pending' || post.pin_status === 'failed')) {
                            publishHtml += `<button class="btn btn-sm btn-outline-dark publish-single-btn px-2 py-1" data-platform="pinterest" data-id="${post.id}" title="Post to Pinterest"><i class="fab fa-pinterest"></i></button>`;
                            pendingPlatforms.push('pinterest');
                        }
                        if (post.post_to_li && (post.li_status === 'pending' || post.li_status === 'failed')) {
                            publishHtml += `<button class="btn btn-sm btn-outline-dark publish-single-btn px-2 py-1" data-platform="linkedin" data-id="${post.id}" title="Post to LinkedIn"><i class="fab fa-linkedin"></i></button>`;
                            pendingPlatforms.push('linkedin');
                        }
                        if (post.post_to_google && (post.google_status === 'pending' || post.google_status === 'failed')) {
                            publishHtml += `<button class="btn btn-sm btn-outline-dark publish-single-btn px-2 py-1" data-platform="google" data-id="${post.id}" title="Post to Google"><i class="fab fa-google"></i></button>`;
                            pendingPlatforms.push('google');
                        }
                        if (post.post_to_devto && (post.devto_status === 'pending' || post.devto_status === 'failed')) {
                            publishHtml += `<button class="btn btn-sm btn-outline-dark publish-single-btn px-2 py-1" data-platform="devto" data-id="${post.id}" title="Post to Dev.to"><i class="fab fa-dev"></i></button>`;
                            pendingPlatforms.push('devto');
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
                                <td><div class="scrollable-caption">${safeCaption ? safeCaption : '<i class="text-muted">No caption</i>'}</div></td>
                                <td>${statusesHtml}</td>
                                <td>${publishHtml}</td>
                                <td><span class="text-muted small"><i class="far fa-clock me-1"></i>${post.updated_date ? post.updated_date : 'N/A'}</span></td>
                                <td>
                                    <div class="d-flex gap-2 shadow-sm rounded">
                                        <button class="btn btn-sm btn-light border preview-post-btn" data-img="${post.image_url}" data-caption="${encodedCaption}" title="Preview Mockup"><i class="fa fa-eye"></i></button>
                                        <button class="btn btn-sm btn-light border edit-btn" data-id="${post.id}" data-img="${post.image_url}" data-caption="${encodedCaption}" data-fb="${post.post_to_fb}" data-ig="${post.post_to_ig}" data-pin="${post.post_to_pin}" data-li="${post.post_to_li}" data-google="${post.post_to_google}" data-devto="${post.post_to_devto}" title="Edit Data"><i class="fa fa-edit"></i></button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });

                    $('#postsTableBody').html(html);
                    $('#tableLoader').hide();
                    $('#tableContainer').fadeIn();
                    $('#postsTable').DataTable({ 
                        "order": [[ 4, "desc" ]], 
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
        $('#socialPostForm')[0].reset();
        $('#edit_post_id').val('');
        $('#edit_existing_image_url').val('');
        $('#imagePreviewContainer').slideUp();
        $('#imagePreview').attr('src', '');
        
        $('#formTitle').html('<i class="fa fa-pen me-2"></i> Compose Post');
        $('#imageLabel').html('Media <span class="text-danger">*</span>');
        $('#saveBtn').html('<i class="fa fa-save me-1"></i> Save Draft');
        $('#cancelEditBtn').hide();
    }

    $('#cancelEditBtn').on('click', resetForm);

    $(document).on('click', '.edit-btn', function() {
        let id = $(this).data('id');
        let img = $(this).data('img');
        let caption = decodeURIComponent($(this).data('caption'));
        let fb = $(this).data('fb') == 1;
        let ig = $(this).data('ig') == 1;
        let pin = $(this).data('pin') == 1;
        let li = $(this).data('li') == 1;
        let google = $(this).data('google') == 1;
        let devto = $(this).data('devto') == 1;

        $('#edit_post_id').val(id);
        $('#edit_existing_image_url').val(img);
        
        $('#post_message').val(caption);
        $('#postToFB').prop('checked', fb);
        $('#postToIG').prop('checked', ig);
        $('#postToPin').prop('checked', pin);
        $('#postToLI').prop('checked', li);
        $('#postToGoogle').prop('checked', google);
        $('#postToDevto').prop('checked', devto);
        $('#media_file').val(''); 

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
        let captionText = decodeURIComponent($(this).data('caption'));
        let titleText = captionText.substring(0, 50) + (captionText.length > 50 ? '...' : '');
        if(!titleText.trim()) titleText = "New Update";

        $('#fb-preview-img, #ig-preview-img, #li-preview-img, #google-preview-img, #devto-preview-img').attr('src', imgUrl);
        $('#fb-preview-caption').text(captionText);
        $('#ig-preview-caption-text').text(captionText);
        $('#li-preview-caption').text(captionText);
        $('#google-preview-caption').text(captionText);
        $('#devto-preview-title').text(titleText);

        let myModal = new bootstrap.Modal(document.getElementById('previewModal'));
        myModal.show();
    });

    $('#media_file').on('change', function(e) {
        let file = e.target.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result);
                $('#imagePreviewContainer').slideDown();
            }
            reader.readAsDataURL(file);
        } else if (!$('#edit_post_id').val()) {
            $('#imagePreviewContainer').slideUp();
        } else {
            $('#imagePreview').attr('src', $('#edit_existing_image_url').val());
        }
    });

    $('#socialPostForm').on('submit', function(e) {
        e.preventDefault();
        
        let postId = $('#edit_post_id').val();
        let isEditMode = (postId !== '');
        
        let fileInput = $('#media_file')[0];
        let existingImgUrl = $('#edit_existing_image_url').val();
        let caption = $('#post_message').val().trim();
        let postFB = $('#postToFB').is(':checked');
        let postIG = $('#postToIG').is(':checked');
        let postPin = $('#postToPin').is(':checked');
        let postLI = $('#postToLI').is(':checked');
        let postGoogle = $('#postToGoogle').is(':checked');
        let postDevto = $('#postToDevto').is(':checked');

        if (!postFB && !postIG && !postPin && !postLI && !postGoogle && !postDevto) return toast('warning', 'Select at least one platform.');
        if (!isEditMode && fileInput.files.length === 0) return toast('warning', 'Media file required.');
        if (!caption) return toast('warning', 'Caption is required.');

        let $btn = $('#saveBtn');
        let originalText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Processing...');

        if (fileInput.files.length > 0) {
            let s3FormData = new FormData();
            s3FormData.append('image', fileInput.files[0]);
            s3FormData.append('name', 'social_' + Date.now());
            s3FormData.append('img_type', 'social_post');
            s3FormData.append('auth_key', 'asdfghjklpoiuytrewqzxcvbnm!@$%^&*()');

            $.ajax({
                url: S3_UPLOAD_API,
                type: 'POST',
                data: s3FormData,
                processData: false, contentType: false,
                success: function(s3Response) {
                    if (s3Response.status === true) {
                        submitLaravelData(postId, s3Response.data.url, caption, postFB, postIG, postPin, postLI, postGoogle, postDevto, $btn, originalText);
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
            submitLaravelData(postId, existingImgUrl, caption, postFB, postIG, postPin, postLI, postGoogle, postDevto, $btn, originalText);
        }
    });

    function submitLaravelData(id, imgUrl, caption, postFB, postIG, postPin, postLI, postGoogle, postDevto, $btn, originalText) {
        let isEdit = (id !== null && id !== '');
        let endpoint = isEdit ? `${LARAVEL_API_BASE}/update/${id}` : `${LARAVEL_API_BASE}/create`;
        
        $.ajax({
            url: endpoint,
            type: 'POST',
            headers: authHeaders,
            data: {
                image_url: imgUrl,
                caption: caption,
                post_to_fb: postFB ? 1 : 0,
                post_to_ig: postIG ? 1 : 0,
                post_to_pin: postPin ? 1 : 0,
                post_to_li: postLI ? 1 : 0,
                post_to_google: postGoogle ? 1 : 0,
                post_to_devto: postDevto ? 1 : 0
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

    $(document).on('click', '.delete-btn', function() {
        let postId = $(this).data('id');
        
        Swal.fire({
            title: 'Delete Draft?',
            text: "This removes the local draft record.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#212529',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                $.ajax({
                    url: `${LARAVEL_API_BASE}/delete/${postId}`,
                    type: 'DELETE',
                    headers: authHeaders,
                    success: function(response) {
                        if(response.status) {
                            Swal.fire('Deleted', response.message, 'success');
                            if($('#edit_post_id').val() == postId) resetForm();
                            loadPosts();
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', parseError(xhr), 'error');
                    }
                });
            }
        });
    });
});
</script>