<?php
include '../../include/shi-config.php';
include '../../include/functions.php';

$pageTitle = "Create Content";
// error_reporting(E_ALL);
// ini_set('display_errors', 1);


?> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<!--<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">-->

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
    }
    
</style>
<script>
    window.onload = function () {
        var page_origin = window.location.origin;
        let anchor = document.getElementById("anchor");
        anchor.href = page_origin;
    }
</script>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <input type="hidden" id="tabID" value="agents">
        <!-- CONTAINER -->
        <div class="main-container container-fluid mt-5 p-0">
            <!-- PAGE-HEADER -->
           <div class="page-header pt-5">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left"
                            onclick="history.go(-1)" aria-hidden="true"></i></a><?= $pageTitle; ?></h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= $pageTitle; ?></li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->
            <!-- ROW-1 -->
            <div class="row">
                 <div class="col-12">

        <!-- Page Header -->
                    <!--<div class="mb-3">-->
                    <!--    <h4 class="fw-semibold ">Content Upload</h4>-->
                    <!--    <small class="text-muted">Create and publish a new blog post</small>-->
                    <!--</div>-->
            
                    <!-- Card -->
                       <form id="blogForm"  method="POST"  enctype="multipart/form-data">
                           
                            <div class="card shadow-sm">
                            <div class="card-body">
                    
                                <!-- Card Title -->
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fa fa-info-circle text-primary me-2"></i>
                                    <h6 class="mb-0 fw-semibold">Blog Info</h6>
                                </div>
                                
                                <div class="row g-3">
                
                                    <!-- Thumbnail Upload -->
                                   <div class="col-md-3">
                                        <label class="form-label">Thumbnail Upload</label>
                                    
                                        <!-- Upload Box -->
                                        <label for="thumbnailInput"
                                               class="border d-flex flex-column
                                                      justify-content-center align-items-center
                                                      text-center p-3" 
                                               style="height:170px; border-style:dashed;
                                                      cursor:pointer; background:#fafafa;">
                                    
                                            <img id="thumbnailPreview"
                                                 src=""
                                                 alt=""
                                                 style="display:none; max-width:100%;
                                                        max-height:120px; object-fit:cover;
                                                        border-radius:6px; margin-bottom:6px;">
                                    
                                            <i id="uploadIcon"
                                               class="fa fa-image text-muted mb-2 fs-4"></i>
                                    
                                            <small id="uploadText" class="text-muted">
                                                Click or drag & drop thumbnail (800×600px)
                                            </small>
                                        </label>
                                    
                                        <!-- Hidden File Input -->
                                        <input type="file"
                                               id="thumbnailInput" name="thumb_nail"
                                               accept="image/*"
                                               style="display:none;"
                                               onchange="previewThumbnail(this)">
                                    </div>
    
                
                                    <!-- Right Form -->
                                    <div class="col-md-9">
                                        <div class="row g-3">
                                    
                                            <!-- CATEGORY -->
                                            <div class="col-md-4">
                                                <label class="form-label">Category</label>
                                            
                                                <select class="form-select" id="category_id" name="category_id">
                                                    <option value="">Select Category</option>
                                            
                                                    <?php
                                                    $query = mysqli_query($con, "SELECT id, cat_name  FROM categories WHERE deleted_at = '0' ORDER BY id ASC");
                                            
                                                    if (!$query) {
                                                        echo '<option value="">Query Error</option>';
                                                    } else if (mysqli_num_rows($query) > 0) {
                                                        while ($row = mysqli_fetch_assoc($query)) {
                                                            echo '<option value="'.$row['id'].'">'
                                                                 . htmlspecialchars($row['cat_name']) .
                                                                 '</option>';
                                                        }
                                                    } else {
                                                        echo '<option value="">No categories found</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                    
                                            <!-- PUBLISHED DATE -->
                                           <div class="col-md-4">
                                                <label class="form-label">Published Date</label>
                                                <input type="date"
                                                       class="form-control"
                                                       name="published_date"
                                                       value="<?= date('Y-m-d') ?>">
                                            </div>
                                    
                                            
                                            <div class="col-md-4">
                                                <label class="form-label">Sub Title</label>
                                                <input type="text" class="form-control" name="sub_title"
                                                      placeholder="e.g. Driver Safety" >
                                            </div>
                                            
                                            
                                            <!-- BLOG TITLE -->
                                            <div class="col-md-12">
                                                <label class="form-label">Blog Title</label>
                                                <input type="text" class="form-control" name="blog_title"
                                                       placeholder="e.g. The Future of Electric Commuting" >
                                            </div>
                                    
                                            <!-- SLUG -->
                                            <div class="col-md-8">
                                                <label class="form-label">Slug</label>
                                                <input type="text" class="form-control" name="slug"
                                                       placeholder="future-of-electric-commuting" id ='slug' >
                                            </div>
                                    
                                            <!-- READ MINUTES -->
                                           <div class="col-md-4">
                                                <label class="form-label">Read Minutes</label>
                                                <input type="number"
                                                       class="form-control"
                                                       name="read_minutes"
                                                       placeholder="0"
                                                       min="1"
                                                       oninput="this.value = this.value < 1 ? '' : this.value">
                                            </div>
                                        </div>
                                    </div>
                
                                </div>
                
                                <!-- SEO Section -->
                                <hr class="my-4">
                
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <input type="text" class="form-control" name="descripe"
                                           placeholder="Enter Description">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">SEO Page Title</label>
                                    <input type="text" class="form-control" name="seo_title"
                                           placeholder="Enter SEO optimized title">
                                </div>
                
                                <div class="mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea class="form-control" rows="3" name="meta_description"
                                              placeholder="Enter meta description for search results..."></textarea>
                                </div>
                
                                <div class="mb-3">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" class="form-control" name="meta_keywords"
                                           placeholder="Electric, Future, EV">
                                </div>
                
                            </div>
                        </div>
                        
                            <!-- Hero Section -->
                            <div class="card shadow-sm mb-4">
                            <div class="card-body">
                        
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fa fa-image text-primary me-2"></i>
                                    <h6 class="mb-0 fw-semibold">Hero Section</h6>
                                </div>
                        
                                <label class="form-label">Image Upload for Top of Content (Hero Image)</label>
                        
                                <!-- Upload Box -->
                                <label for="heroImageInput"
                                       class="border  d-flex flex-column
                                              justify-content-center align-items-center
                                              text-center p-4"
                                       style="height:200px; border-style:dashed;
                                              cursor:pointer; background:#fafafa;">
                                    
                        
                                    <img id="heroImagePreview"
                                         style="display:none; max-width:100%;
                                                max-height:150px; object-fit:cover;
                                                border-radius:6px;">    
                        
                                    <i id="heroUploadIcon"
                                       class="fa fa-cloud-upload-alt text-muted fs-2 mb-2"></i>
                        
                                    <small class="text-muted">
                                        Drop your hero image here, or browse<br>
                                        Supports JPG, PNG, WEBP (Max 2MB, 1920×800px)
                                    </small>
                                </label>
                                
                                <!-- Hidden Input -->
                                <input type="file"
                                       id="heroImageInput"
                                       name="hero_image"
                                       accept="image/*"
                                       style="display:none;"
                                       onchange="previewHeroImage(this)">
                            </div>
                        </div>
                        
                            <!-- Content Section -->
                            <!--<div class="card shadow-sm mb-4">-->
                            <!--    <div class="card-body">-->
                            
                            <!--        <div class="d-flex align-items-center mb-3">-->
                            <!--            <i class="fa fa-pen-nib text-primary me-2"></i>-->
                            <!--            <h6 class="mb-0 fw-semibold">Content</h6>-->
                            <!--        </div>-->
                            
                            <!--        <div class="border rounded-top p-2 bg-light">-->
                            <!--            <button type="button" class="btn btn-sm btn-light" onclick="formatText('bold')"><b>B</b></button>-->
                            <!--            <button type="button" class="btn btn-sm btn-light" onclick="formatText('italic')"><i>I</i></button>-->
                            <!--            <button type="button" class="btn btn-sm btn-light" onclick="formatText('underline')"><u>U</u></button>-->
                            
                            
                            <!--        </div>-->
                            
                            <!--        <div id="editor"-->
                            <!--             contenteditable="true"-->
                            <!--             class="border rounded-bottom p-3"-->
                            <!--             style="min-height:200px;"-->
                            <!--             onkeyup="saveSelection()"-->
                            <!--             onmouseup="saveSelection()"-->
                            <!--             onfocus="saveSelection()">-->
                            <!--        </div>-->

                            
                            <!--        <textarea name="content" id="contentInput" hidden></textarea>-->
                            
                            <!--    </div>-->
                            <!--</div>-->
                            
                            <div class="card shadow-sm mb-4">
                                <div class="card-body">
                            
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa fa-pen-nib text-primary me-2"></i>
                                        <h6 class="mb-0 fw-semibold">Content</h6>
                                    </div>
                            
                                    <div class="border rounded-top p-2 bg-light">
                                        <!--<button type="button" class="btn btn-sm btn-light" onclick="formatText('bold')"><b>B</b></button>-->
                                        <!--<button type="button" class="btn btn-sm btn-light" onclick="formatText('italic')"><i>I</i></button>-->
                                        <!--<button type="button" class="btn btn-sm btn-light" onclick="formatText('underline')"><u>U</u></button>-->
                            
                            
                                    </div>
                            
                                    <div id="editor" class="summernote"></div>

                                    <textarea name="content" id="contentInput" hidden></textarea>

                            
                                </div>
                            </div>
                            
                        <!--     <div class="card shadow-sm mb-4">-->
                        <!--        <div class="card-body">-->
                            
                                    <!-- Header -->
                        <!--            <div class="d-flex justify-content-between align-items-center mb-3">-->
                        <!--                <div class="d-flex align-items-center">-->
                        <!--                    <i class="fa fa-question-circle text-success me-2"></i>-->
                        <!--                    <h6 class="mb-0 fw-semibold">Content</h6>-->
                        <!--                </div>-->
                            
                        <!--                <button type="button" class="btn btn-sm btn-warning" onclick="addlabelRow()">-->
                        <!--                    <i class="fa fa-plus"></i> Add Row-->
                        <!--                </button>-->
                        <!--            </div>-->
                            
                                    <!-- FAQ Container -->
                        <!--            <div id="titleContainer">-->
                            
                                        <!-- FAQ Row -->
                        <!--                <div class="faq-row border  p-3 mb-3 position-relative">-->
                            
                        <!--                    <button type="button"-->
                        <!--                            class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"-->
                        <!--                            onclick="removelabelRow(this)">-->
                        <!--                    <i class="fa fa-minus"></i>-->
                        <!--                </button>-->
                        
                        <!--                <div class="mb-2">-->
                        <!--                    <label class="form-label">Title</label>-->
                        <!--                    <input type="text"-->
                        <!--                           class="form-control"-->
                        <!--                           placeholder="e.g. Driver Safe"-->
                        <!--                           name="label_h[]">-->
                        <!--                </div>-->
                        
                        <!--                <div>-->
                        <!--                    <label class="form-label">Content</label>-->
                        <!--                    <textarea class="form-control"-->
                        <!--                              rows="3"-->
                        <!--                              placeholder="Provide a detailed answer..."-->
                        <!--                              name="content_p[]"></textarea>-->
                        <!--                </div>-->
                        
                        <!--            </div>-->
                        
                        <!--        </div>-->
                        
                        <!--    </div>-->
                        <!--</div>-->
                            
                            <!-- FAQ Section -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-body">
                            
                                    <!-- Header -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fa fa-question-circle text-success me-2"></i>
                                            <h6 class="mb-0 fw-semibold">FAQ Section</h6>
                                        </div>
                            
                                        <button type="button" class="btn btn-sm btn-warning" onclick="addFaqRow()">
                                            <i class="fa fa-plus"></i> Add Row
                                        </button>
                                    </div>
                            
                                    <!-- FAQ Container -->
                                    <div id="faqContainer">
                            
                                        <!-- FAQ Row -->
                                        <div class="faq-row border  p-3 mb-3 position-relative">
                            
                                            <button type="button"
                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                                    onclick="removeFaqRow(this)">
                                            <i class="fa fa-minus"></i>
                                        </button>
                        
                                        <div class="mb-2">
                                            <label class="form-label">Question</label>
                                            <input type="text"
                                                   class="form-control"
                                                   placeholder="e.g. How does the battery life compare?"
                                                   name="faq_question[]">
                                        </div>
                        
                                        <div>
                                            <label class="form-label">Answer</label>
                                            <textarea class="form-control"
                                                      rows="3"
                                                      placeholder="Provide a detailed answer..."
                                                      name="faq_answer[]"></textarea>
                                        </div>
                        
                                    </div>
                        
                                </div>
                        
                            </div>
                        </div>
                        
                            <!-- Action Buttons -->
                            <div class="d-flex gap-3 mt-4">
                                <button type="submit" class="btn btn-success px-4" id="submit_content">
                                    <i class="fa fa-upload me-2"></i> Submit Content
                                </button>
                            
    
                            </div>
                            

                    </form>
            
                </div>
                 
            </div>

        </div>
      
        <!-- ROW-4 END -->
    </div>
    <!-- CONTAINER END -->
</div>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>


$(document).ready(function () {

    $("input[name='blog_title']").on("input", function () {

        let title = $(this).val();

        let slug = title
            .toLowerCase()
            .trim()
            .replace(/'/g, '')            // ✅ remove single quotes only
            .replace(/[^a-z0-9]+/g, '-')  // other non-alphanumeric → hyphen
            .replace(/-+/g, '-')          
            .replace(/^-|-$/g, '');

        $("#slug").val(slug);
    });

});

 function addlabelRow() {
        const container = document.getElementById('titleContainer');

        const row = document.createElement('div');
        row.className = 'labelh-row border p-3 mb-3 position-relative';

        row.innerHTML = `
            <button type="button"
                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                    onclick="removelabelRow(this)">
                <i class="fa fa-minus"></i>
            </button>

            <div class="mb-2">
                <label class="form-label">Title</label>
                <input type="text"
                       class="form-control"
                       placeholder="e.g. Driver Safe "
                       name="label_h[]">
            </div>

            <div>
                <label class="form-label">Content</label>
                <textarea class="form-control"
                          rows="3"
                          placeholder="Provide a detailed answer..."
                          name="content_p[]"></textarea>
            </div>
        `;

        container.appendChild(row);
    }

    function removelabelRow(button) {
        const row = button.closest('.labelh-row');
        row.remove();
    }


 function addFaqRow() {
        const container = document.getElementById('faqContainer');

        const row = document.createElement('div');
        row.className = 'faq-row border p-3 mb-3 position-relative';

        row.innerHTML = `
            <button type="button"
                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                    onclick="removeFaqRow(this)">
                <i class="fa fa-minus"></i>
            </button>

            <div class="mb-2">
                <label class="form-label">Question</label>
                <input type="text"
                       class="form-control"
                       placeholder="e.g. How does the battery life compare?"
                       name="faq_question[]">
            </div>

            <div>
                <label class="form-label">Answer</label>
                <textarea class="form-control"
                          rows="3"
                          placeholder="Provide a detailed answer..."
                          name="faq_answer[]"></textarea>
            </div>
        `;

        container.appendChild(row);
    }

    function removeFaqRow(button) {
        const row = button.closest('.faq-row');
        row.remove();
    }

function previewHeroImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Preview
        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById('heroImagePreview');
            preview.src = e.target.result;
            preview.style.display = 'block';

            document.getElementById('heroUploadIcon').style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
}
function previewThumbnail(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Preview
        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById('thumbnailPreview');
            preview.src = e.target.result;
            preview.style.display = 'block';

            document.getElementById('uploadIcon').style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
}

function insertTextAtCursor(text) {
    const selection = window.getSelection();

    if (!selection.rangeCount) return;

    selection.deleteFromDocument();

    const range = selection.getRangeAt(0);
    const textNode = document.createTextNode(text);

    range.insertNode(textNode);

    // move cursor to end of inserted text
    range.setStartAfter(textNode);
    range.setEndAfter(textNode);

    selection.removeAllRanges();
    selection.addRange(range);
}



$(document).ready(function () {
    
    // const editor = document.getElementById("editor");

    // editor.addEventListener("paste", function (e) {
    //     e.preventDefault();
    
    //     // Get only plain text
    //     let text = (e.clipboardData || window.clipboardData).getData("text/plain");
    
    //     insertTextAtCursor(text);
    // });
    
    $('#editor').summernote({
        height: 300,
        placeholder: 'Start writing your story here...',
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview']]
        ],
        callbacks: {
            onPaste: function (e) {
                e.preventDefault();
                var text = (e.originalEvent || e).clipboardData.getData('text/plain');
                document.execCommand("insertText", false, text);
            }
        }
    });


    $("#blogForm").on("submit", function (e) {
        e.preventDefault();

        // // console.log("Form submit intercepted");

        $("#contentInput").val($('#editor').summernote('code'));

        let errors = [];

        if ($("#thumbnailInput").val() === "") {
            errors.push("Thumbnail image is required");
        }

        if ($("#category_id").val() === "") {
            errors.push("Please select a category");
        }

        if ($("input[name='published_date']").val() === "") {
            errors.push("Published date is required");
        }
        
        if ($("input[name='descripe']").val() === "") {
            errors.push("Description is required");
        }

        if ($("input[name='blog_title']").val().trim() === "") {
            errors.push("Blog title is required");
        }

        if ($("#slug").val().trim() === "") {
            errors.push("Slug is required");
        }

        if ($("input[name='read_minutes']").val() === "") {
            errors.push("Read minutes is required");
        }

        if ($("#contentInput").val() === "" || $("#contentInput").val() === "<br>") {
            errors.push("Blog content cannot be empty");
        }

        if (errors.length > 0) {
            alert("Please fix:\n\n• " + errors.join("\n• "));
            return;
        }

        let formData = new FormData(this);

        let slug = $("#slug").val().trim();
        formData.set("slug", slug);

        formData.append("action", "create_blog");
        formData.append("method", "blogs_content");

        // console.log("Sending AJAX request...");

        $.ajax({
            url: "/ajax/service/blogsContent_services.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend: function () {
                $('#submit_content').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Creating...`)
                   .prop('disabled', true);
            },

            success: function (res) {
                // // console.log("AJAX SUCCESS:", res);

                Swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "success",
                    title: "Created successfully",
                    showConfirmButton: false,
                    timer: 2000
                });
                 setTimeout(function () {
                    location.reload();
                }, 2000);
                $('#submit_content').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Submit Content`);
            },
            

            error: function (xhr, status, error) {
                console.error("AJAX ERROR:", status, error);
                // console.log(xhr.responseText);

                Swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "error",
                    title: "Failed to create",
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    });

});


</script>
<script>

let savedSelection = null;

// Save cursor position
function saveSelection() {
    const sel = window.getSelection();
    if (sel.rangeCount > 0) {
        savedSelection = sel.getRangeAt(0);
    }
}

// Restore cursor
function restoreSelection() {
    if (savedSelection) {
        const sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(savedSelection);
    }
}

function formatText(command, value = null) {
    restoreSelection();
    document.execCommand(command, false, value);
    saveSelection();
    document.getElementById('editor').focus();
}

</script>

