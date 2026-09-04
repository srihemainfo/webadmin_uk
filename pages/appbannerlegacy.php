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
                            <input type="hidden" id="edit_index" value="-1">
                            <input type="hidden" id="existing_image_url" value="">
                            
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
                                    <input type="number" id="banner_order" class="form-control" value="0">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Is Active</label>
                                    <select id="banner_active" class="form-control">
                                        <option value="true">Yes</option>
                                        <option value="false">No</option>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.3/viewer.min.js"></script>

<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>

<script>
    // --- 1. FIREBASE INITIALIZATION ---
    const firebaseConfig = {
      apiKey: "AIzaSyBLWKuZGTE4C0LvFob800avF-jgIYxnsyw",
      authDomain: "goride-947ed.firebaseapp.com",
      projectId: "goride-947ed",
      storageBucket: "goride-947ed.firebasestorage.app",
      messagingSenderId: "1068992532063",
      appId: "1:1068992532063:web:a4bfecdf589c73b5ff55ea",
      measurementId: "G-HS1ZXQDYSS"
    };
    
    if (!firebase.apps.length) {
        firebase.initializeApp(firebaseConfig);
    }
    const db = firebase.firestore();
    const docRef = db.collection('banners').doc('home_banners');

    // --- 2. UTILITY FUNCTIONS ---
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

    // --- 3. FETCH & RENDER BANNERS ---
    function loadBanners() {
        docRef.get().then((doc) => {
            if (doc.exists) {
                let bannersData = doc.data().banners || [];
                let html = '';
                
                bannersData.forEach((banner, index) => {
                    let title = escapeHtml(banner.title);
                    let subtitle = escapeHtml(banner.subtitle);
                    let route = escapeHtml(banner.route);
                    let imgUrl = escapeHtml(banner.image_url);
                    let order = banner.order || 0;
                    
                    let jsTitle = encodeURIComponent(banner.title || '').replace(/'/g, "%27");
                    let jsSubtitle = encodeURIComponent(banner.subtitle || '').replace(/'/g, "%27");
                    let jsRoute = encodeURIComponent(banner.route || '').replace(/'/g, "%27");
                    let jsImgUrl = encodeURIComponent(banner.image_url || '').replace(/'/g, "%27");

                    let isActiveBadge = banner.is_active 
                        ? '<span class="badge bg-success">Active</span>' 
                        : '<span class="badge bg-danger">Inactive</span>';

                    html += `<tr>
                        <td>${order}</td>
                        <td><button class="btn btn-sm btn-info view-img-btn" data-url="${imgUrl}"><i class="fa fa-eye"></i> View Image</button></td>
                        <td>${title}</td>
                        <td>${subtitle}</td>
                        <td>${route}</td>
                        <td>${isActiveBadge}</td>
                        <td class="action-btns">
                            <button class="btn btn-sm btn-primary" onclick="editBanner(${index}, '${jsTitle}', '${jsSubtitle}', '${jsRoute}', ${order}, ${banner.is_active}, '${jsImgUrl}')" title="Edit">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteBanner(${index})" title="Delete">
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
                    "order": [[ 0, "asc" ]],
                    "pageLength": 10
                });
            } else {
                toast('info', 'No banners document found in Firebase.');
            }
        }).catch((error) => {
            console.error("Error getting document:", error);
            toast('error', 'Failed to fetch data from Firebase.');
        });
    }

    $(document).ready(function() {
        loadBanners();

        // --- 4. VIEW IMAGE (LAZY LOAD) ---
        $(document).on('click', '.view-img-btn', function() {
            let imgUrl = $(this).attr('data-url');
            if (!imgUrl) return toast('error', 'Image URL not found!');

            let $btn = $(this);
            let originalText = $btn.html();
            
            $btn.html('<i class="fa fa-spinner fa-spin"></i> Loading...').prop('disabled', true);

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

        // --- 5. SAVE / UPLOAD LOGIC ---
        $('#saveBtn').on('click', function() {
            let title = $('#banner_title').val().trim();
            let subtitle = $('#banner_subtitle').val().trim();
            let route = $('#banner_route').val().trim();
            let order = parseInt($('#banner_order').val()) || 0;
            let is_active = ($('#banner_active').val() === 'true');
            let fileInput = $('#banner_image')[0];
            
            let editIndex = parseInt($('#edit_index').val());
            let existingImgUrl = $('#existing_image_url').val();

            if (!title) return toast('warning', 'Title is required');
            if (!route) return toast('warning', 'Route is required');
            if (editIndex === -1 && fileInput.files.length === 0) {
                return toast('warning', 'Image is required for new banners');
            }

            let $btn = $(this);
            let originalBtnText = $btn.html();
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i>Processing...');

            let saveToFirebase = function(finalImageUrl) {
                $btn.html('<i class="fa fa-spinner fa-spin me-2"></i>Saving to Database...');
                
                let newBannerObj = {
                    action_type: 'none',
                    image_url: finalImageUrl,
                    is_active: is_active,
                    order: order,
                    route: route,
                    subtitle: subtitle,
                    title: title
                };

                docRef.get().then((doc) => {
                    let currentBanners = doc.exists ? (doc.data().banners || []) : [];

                    // --- DUPLICATE ORDER VALIDATION ---
                    let isDuplicate = currentBanners.some((banner, index) => {
                        return banner.order === order && index !== editIndex;
                    });

                    if (isDuplicate) {
                        toast('error', `Order number ${order} is already in use.`);
                        throw new Error("Duplicate Order"); 
                    }

                    if (editIndex >= 0) {
                        currentBanners[editIndex] = newBannerObj; 
                    } else {
                        currentBanners.push(newBannerObj); 
                    }

                    return docRef.set({ banners: currentBanners }, { merge: true });

                }).then(() => {
                    $btn.prop('disabled', false).html(originalBtnText);
                    toast('success', 'Banner saved successfully!');
                    resetForm();
                    loadBanners(); 
                }).catch((error) => {
                    $btn.prop('disabled', false).html(originalBtnText);
                    if (error.message !== "Duplicate Order") {
                        console.error("Error writing document: ", error);
                        toast('error', 'Failed to save to Firebase.');
                    }
                });
            };

            // If a new image is selected, upload to S3 API first
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
                            saveToFirebase(response.data.url);
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
                saveToFirebase(existingImgUrl);
            }
        });
    });

    // --- 6. POPULATE EDIT FORM ---
    function editBanner(index, encTitle, encSubtitle, encRoute, order, is_active, encImgUrl) {
        // Decode the safely encoded variables
        let title = decodeURIComponent(encTitle);
        let subtitle = decodeURIComponent(encSubtitle);
        let route = decodeURIComponent(encRoute);
        let img_url = decodeURIComponent(encImgUrl);

        $('#formCardTitle').text('Edit Banner');
        $('#saveBtn').text('Update Banner');
        $('#cancelEditBtn').show();
        $('#img_req_asterisk').hide();
        $('#img_help_text').text('Leave empty to keep existing image.');
        
        $('#edit_index').val(index);
        $('#existing_image_url').val(img_url);
        
        $('#banner_title').val(title);
        $('#banner_subtitle').val(subtitle);
        $('#banner_route').val(route);
        $('#banner_order').val(order);
        $('#banner_active').val(is_active ? 'true' : 'false');
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // --- 7. DELETE LOGIC ---
    function deleteBanner(index) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will remove the banner permanently!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                docRef.get().then((doc) => {
                    if (doc.exists) {
                        let currentBanners = doc.data().banners || [];
                        currentBanners.splice(index, 1); 
                        return docRef.set({ banners: currentBanners }, { merge: true });
                    }
                }).then(() => {
                    toast('success', 'Banner deleted.');
                    loadBanners(); 
                }).catch((error) => {
                    console.error("Error deleting document: ", error);
                    toast('error', 'Failed to delete banner.');
                });
            }
        });
    }

    // --- 8. RESET FORM UTILITY ---
    function resetForm() {
        $('#formCardTitle').text('Add New Banner');
        $('#saveBtn').text('Save Banner');
        $('#cancelEditBtn').hide();
        $('#img_req_asterisk').show();
        $('#img_help_text').text('Select an image to upload to S3.');
        
        $('#edit_index').val('-1');
        $('#existing_image_url').val('');
        $('#banner_title').val('');
        $('#banner_subtitle').val('');
        $('#banner_route').val('/booking');
        $('#banner_order').val('0');
        $('#banner_active').val('true');
        $('#banner_image').val('');
    }
</script>