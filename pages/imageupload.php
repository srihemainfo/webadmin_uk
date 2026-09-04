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
</style>

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<!-- Added Viewer.js CSS for the image modal -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.3/viewer.min.css">

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            
            <div class="page-header">
                <h1 class="page-title">
                    <a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>
                    Upload Image to S3
                </h1>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">S3 Upload Details</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Select Image (image) <span style="color:red;">*</span></label>
                                    <input type="file" id="payload_image" class="form-control" accept="image/*">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Enter Name (name) <span style="color:red;">*</span></label>
                                    <input type="text" id="payload_name" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Enter Image Type (img_type) <span style="color:red;">*</span></label>
                                    <input type="text" id="payload_img_type" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <button type="button" id="uploadBtn" class="btn btn-info">Upload</button>
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
                            <h3 class="card-title">Uploaded S3 Images</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="s3ImagesTable" class="table table-bordered table-hover text-nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Preview</th>
                                            <th>Name</th>
                                            <th>Image Type</th>
                                            <th>S3 Path</th>
                                            <th>Date Uploaded</th>
                                        </tr>
                                    </thead>
                                    <tbody id="s3ImagesBody">
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
<!-- Added Viewer.js script for the image modal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.3/viewer.min.js"></script>

<script>
    // Toast helper
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

    // Function to load/reload DataTable
    function loadS3Images() {
        $.ajax({
            url: 'ajax/service/services.php', // Change this to the actual PHP file handling the GET request
            type: 'POST', // or GET depending on your backend setup
            data: { method: 'get_s3_images' },
            dataType: 'json',
            success: function(response) {
                if (response.type === "1") {
                    // Destroy existing DataTable instance if it exists
                    if ($.fn.DataTable.isDataTable('#s3ImagesTable')) {
                        $('#s3ImagesTable').DataTable().destroy();
                    }
                    
                    // Inject the new HTML rows into the tbody
                    $('#s3ImagesBody').html(response.outputscreen);
                    
                    // Re-initialize DataTable with default pagination
                    $('#s3ImagesTable').DataTable({
                        "order": [[ 0, "desc" ]], // Order by ID descending
                        "pageLength": 10 // Default pagination limit
                    });
                } else {
                    toast('error', 'Failed to load images from database.');
                }
            },
            error: function() {
                toast('error', 'Server error while fetching images.');
            }
        });
    }

    $(document).ready(function() {
        // Load table data on initial page load
        loadS3Images();

        // ---------------------------------------------------------
        // NEW: Event delegation for viewing the image in a modal
        // ---------------------------------------------------------
        $(document).on('click', '.view-img-btn', function() {
            let imgUrl = $(this).attr('data-url');
            
            if (!imgUrl) {
                toast('error', 'Image URL not found!');
                return;
            }

            // Create a temporary image element for Viewer.js
            let image = new Image();
            image.src = imgUrl;
            
            // Initialize Viewer.js with exactly the controls shown in your screenshot
            let viewer = new Viewer(image, {
                hidden: function () {
                    viewer.destroy(); // Clean up memory when closed
                },
                toolbar: {
                    zoomIn: 1,
                    zoomOut: 1,
                    oneToOne: 1,
                    reset: 1,
                    prev: 0,
                    play: 0,
                    next: 0,
                    rotateLeft: 1,
                    rotateRight: 1,
                    flipHorizontal: 1,
                    flipVertical: 1,
                }
            });
            
            // Show the image viewer
            viewer.show();
        });
        // ---------------------------------------------------------

        $('#uploadBtn').on('click', function() {
            let fileInput = $('#payload_image')[0];
            let nameVal = $('#payload_name').val().trim();
            let imgTypeVal = $('#payload_img_type').val().trim();
            
            // Validation
            if (fileInput.files.length === 0) {
                toast('warning', 'Please select an image file to upload.');
                return;
            }
            if (!nameVal) {
                toast('warning', 'Please enter a value for the name.');
                $('#payload_name').focus();
                return;
            }
            if (!imgTypeVal) {
                toast('warning', 'Please enter a value for the image type.');
                $('#payload_img_type').focus();
                return;
            }

            let file = fileInput.files[0];
            let $btn = $(this);
            let originalBtnText = $btn.html();

            // Disable button
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i>Uploading...');

            // Prepare Payload
            let formData = new FormData();
            formData.append('image', file);
            formData.append('name', nameVal);
            formData.append('img_type', imgTypeVal);
            formData.append('auth_key', 'asdfghjklpoiuytrewqzxcvbnm!@$%^&*()');

            // AJAX Request for Upload
            $.ajax({
                url: 'https://mapi.goride.run/api/v1-cus/s3-upload-image',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $btn.prop('disabled', false).html(originalBtnText);

                    if (response.status === true) {
                        let uploadedUrl = response.data.url;
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Upload Successful!',
                            text: 'The image was uploaded to S3.',
                            html: `
                                <div class="mt-3">
                                    <a href="${uploadedUrl}" target="_blank" class="btn btn-outline-primary btn-sm">View Uploaded Image</a>
                                </div>
                            `
                        });
                        
                        // Clear the form
                        $('#payload_image').val('');
                        $('#payload_name').val('');
                        $('#payload_img_type').val('');

                        // RELOAD THE DATATABLE DYNAMICALLY
                        loadS3Images();

                    } else {
                        toast('error', response.message || 'The server rejected the upload.');
                    }
                },
                error: function(xhr, status, error) {
                    $btn.prop('disabled', false).html(originalBtnText);
                    toast('error', 'Server error. Check the console for details.');
                    console.error("AJAX Error:", error, xhr.responseText);
                }
            });
        });
    });
</script>