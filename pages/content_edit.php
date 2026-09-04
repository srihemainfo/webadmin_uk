<?php

$pageTitle = "Edit Category";

$categoryID = $subid3;

$sql = "SELECT *  FROM `blogs_content`  WHERE `id` = '$categoryID'";
   $run = mysqli_query($con, $sql);
   if (mysqli_num_rows($run) > 0) {
      $row = $run->fetch_assoc();
   }
   
   
?>

<?php

$faqList = [];

if (!empty($row['faq'])) {
    $faqList = json_decode($row['faq'], true);
}
// var_dump($faqList);die;
?>

<?php

$labelList = [];

if (!empty($row['content'])) {
    $labelList = json_decode($row['content'], true);
}
// var_dump($faqList);die;
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


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

/* Toolbar button active state */
.note-btn {
    border: 1px solid #ced4da !important;
    background: #fff !important;
}

.note-btn.active,
.note-btn:active {
    background-color: #e9ecef !important;
    box-shadow: inset 0 3px 5px rgba(0,0,0,.125) !important;
}

/* Editor area */
.note-editor.note-frame {
    border: 1px solid #ced4da !important;
}

/* Make bold REALLY bold */
.note-editable b,
.note-editable strong {
    font-weight: 700 !important;
}

/* Cursor & selection fix */
.note-editable {
    outline: none !important;
    font-family: inherit !important;
}

/* Dropdown + tooltip position fix (prevents 'top' error) */
.note-popover,
.note-tooltip {
    position: absolute !important;
}

</style>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid mt-5 p-0">

            <!-- PAGE HEADER -->
            <div class="page-header pt-5">
                <h1 class="page-title">
                    <a href="javascript:void(0)" class="back-arrow-btn">
                        <i class="fa fa-chevron-left" onclick="history.go(-1)"></i>
                    </a>
                    <?= $pageTitle ?>
                </h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active"><?= $pageTitle ?></li>
                </ol>
            </div>

            <div class="row">
                 <div class="col-12">

                       <form id="blogForm"  method="POST"  enctype="multipart/form-data">
                           
                           <input type="hidden" id="blog_id" value="<?= $categoryID ?>" name="blog_id">
                           
                            <div class="card shadow-sm">
                            <div class="card-body">
                    
                                <!-- Card Title -->
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fa fa-info-circle text-primary me-2"></i>
                                    <h6 class="mb-0 fw-semibold">Blog Info</h6>
                                </div>
                                
                                <div class="row g-3">
                        
                                    <div class="col-md-3">
                                        <label class="form-label">Thumbnail Upload</label>
                                          <?php
                                    $thumbPath = !empty($row['thumbnail'])
                                        ? $row['thumbnail']
                                        : '';
                                    ?>
                                    
                                    <label for="thumbnailInput"
                                           class="border  d-flex flex-column
                                                  justify-content-center align-items-center
                                                  text-center p-3"
                                           style="height:170px; border-style:dashed;
                                                  cursor:pointer; background:#fafafa;">
                                    
                                        <img id="thumbnailPreview"
                                             src="<?= htmlspecialchars($thumbPath) ?>"
                                             alt="Thumbnail"
                                             style="
                                                display: <?= $thumbPath ? 'block' : 'none' ?>;
                                                max-width:100%;
                                                max-height:120px;
                                                object-fit:cover;
                                                border-radius:6px;
                                                margin-bottom:6px;
                                             ">
                                    
                                        <i id="uploadIcon"
                                           class="fa fa-image text-muted mb-2 fs-4"
                                           style="<?= $thumbPath ? 'display:none;' : '' ?>"></i>
                                    
                                        <small id="uploadText" class="text-muted"
                                               style="<?= $thumbPath ? 'display:none;' : '' ?>">
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
                                            
                                                <select class="form-select" id="category_id" name="category_id" >
                                                    <option value="">Select Category</option>
                                                    
                                                   <?php
                                                        $selectedCategoryId = $row['category_id'] ?? '';
                                                        
                                                    
                                                        $query = mysqli_query($con, "SELECT id, cat_name FROM categories WHERE deleted_at = '0' ORDER BY id ASC");
                                                        
                                                        if (!$query) {
                                                            echo '<option value="">Query Error</option>';
                                                        } elseif (mysqli_num_rows($query) > 0) {
                                                        
                                                            while ($catRow = mysqli_fetch_assoc($query)) {
                                                        
                                                                
                                                                $selected = ($catRow['id'] == $selectedCategoryId) ? 'selected' : '';
                                                        
                                                                echo '<option value="'.$catRow['id'].'" '.$selected.'>'
                                                                     . htmlspecialchars($catRow['cat_name']) .
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
                                                <input type="date" class="form-control" name="published_date" value ="<?=$row['published_date']?>">
                                            </div>
                                            
                                             <div class="col-md-4">
                                                <label class="form-label">Sub Title</label>
                                                <input type="text" class="form-control" name="sub_title"
                                                      placeholder="e.g. Driver Safety" value ="<?=$row['sub_title']?>">
                                            </div>
                                    
                                            <!-- BLOG TITLE -->
                                            <div class="col-md-4">
                                                <label class="form-label">Blog Title</label>
                                                <input type="text" class="form-control" name="blog_title"
                                                       placeholder="e.g. The Future of Electric Commuting" value ="<?=$row['blog_title']?>">
                                            </div>
                                    
                                            <!-- SLUG -->
                                            <div class="col-md-4">
                                                <label class="form-label">Slug</label>
                                                <input type="text" class="form-control" name="slug"
                                                       placeholder="future-of-electric-commuting" id ='slug' value ="<?=$row['slug']?>">
                                            </div>
                                    
                                            <!-- READ MINUTES -->
                                            <div class="col-md-4">
                                                <label class="form-label">Read Minutes</label>
                                                <input type="number"
                                                       class="form-control"
                                                       name="read_minutes"
                                                       placeholder="5"
                                                       min="1"
                                                       oninput="this.value = this.value < 1 ? '' : this.value"
                                                       value ="<?=$row['read_minutes']?>" >
                                            </div>
                                    
                                        </div>
                                    </div>
                
                                </div>
                
                                <!-- SEO Section -->
                                <hr class="my-4">
                                
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <input type="text" class="form-control" name="descripe"
                                           placeholder="Enter Description" value ="<?=$row['description']?>">
                                </div>
                
                                <div class="mb-3">
                                    <label class="form-label">SEO Page Title</label>
                                    <input type="text" class="form-control" name="seo_title"
                                           placeholder="Enter SEO optimized title" value ="<?=$row['seo_title']?>">
                                </div>
                
                                 <div class="mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea class="form-control"
                                              rows="3"
                                              name="meta_description"
                                              placeholder="Enter meta description for search results..."><?= htmlspecialchars($row['meta_description'] ?? '') ?></textarea>
                                </div>
                
                                <div class="mb-3">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" class="form-control" name="meta_keywords" value ="<?=$row['meta_keywords']?>"
                                           placeholder="Electric, Future, EV">
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch pt-2">
                                        <input class="form-check-input" type="checkbox" name="sitemap_needed" value="1" id="sitemap_needed" checked style="cursor: pointer; width: 2.2em; height: 1.2em;">
                                        <label class="form-check-label fw-semibold text-dark ms-2" for="sitemap_needed" style="cursor: pointer;">
                                            <i class="fa fa-sitemap text-primary me-1"></i> Add this blog to sitemap (sitemap-blog.xml)
                                        </label>
                                    </div>
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
                                        
                                    <?php
                                    $heroPath = !empty($row['hero_image'])
                                        ? $row['hero_image']
                                        : '';
                                    ?>
                                    <label class="form-label">
                                        Image Upload for Top of Content (Hero Image)
                                    </label>
                            
                                    <!-- Upload Box -->
                                    <label for="heroImageInput"
                                           class="border d-flex flex-column
                                                  justify-content-center align-items-center
                                                  text-center p-4"
                                           style="height:200px; border-style:dashed;
                                                  cursor:pointer; background:#fafafa;">
                            
                                        <img id="heroImagePreview"
                                             src="<?= htmlspecialchars($heroPath) ?>"
                                             alt="Hero Image"
                                             style="
                                                display: <?= $heroPath ? 'block' : 'none' ?>;
                                                max-width:100%;
                                                max-height:150px;
                                                object-fit:cover;
                                                border-radius:6px;
                                                margin-bottom:8px;
                                             ">
                            
                                        <i id="heroUploadIcon"
                                           class="fa fa-cloud-upload-alt text-muted fs-2 mb-2"
                                           style="<?= $heroPath ? 'display:none;' : '' ?>"></i>
                            
                                        <small id="heroUploadText"
                                               class="text-muted"
                                               style="<?= $heroPath ? 'display:none;' : '' ?>">
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

                                    <?php if (!empty($faqList)) : ?>
                                        <?php foreach ($faqList as $faq) : ?>
                                            <div class="faq-row border p-3 mb-3 position-relative">
                                    
                                                <button type="button"
                                                        class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                                        onclick="removeFaqRow(this)">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                    
                                                <div class="mb-2">
                                                    <label class="form-label">Question</label>
                                                    <input type="text"
                                                           class="form-control"
                                                           name="faq_question[]"
                                                           value="<?= htmlspecialchars($faq['question']) ?>">
                                                </div>
                                    
                                                <div>
                                                    <label class="form-label">Answer</label>
                                                    <textarea class="form-control"
                                                              rows="3"
                                                              name="faq_answer[]"><?= htmlspecialchars($faq['answer']) ?></textarea>
                                                </div>
                                    
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                    
                                        <!-- Empty row for new content -->
                                        <div class="faq-row border p-3 mb-3 position-relative">
                                            <div class="mb-2">
                                                <label class="form-label">Question</label>
                                                <input type="text" class="form-control" name="faq_question[]">
                                            </div>
                                            <div>
                                                <label class="form-label">Answer</label>
                                                <textarea class="form-control" rows="3" name="faq_answer[]"></textarea>
                                            </div>
                                        </div>
                                    
                                    <?php endif; ?>
                                    
                                    </div>
                        
                            </div>
                        </div>
                        
                            <div class="d-flex gap-3 mt-4">
                                <button type="submit" class="btn btn-success px-4" id="updateCategoryBtn">
                                    <i class="fa fa-upload me-2"></i> Update Content
                                </button>
                            
                            </div>
                            

                    </form>
            
                </div>
                 
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Bootstrap CSS -->
<!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">-->
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>


<script>

$(document).ready(function () {
    loadCategory();
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
    
$(document).ready(function () {
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
    
    var existingContent = <?= json_encode($row['content'] ?? '') ?>;
    $('#editor').summernote('code', existingContent);
});


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

$(document).ready(function () {

    
    $("#blogForm").on("submit", function (e) {
        e.preventDefault();
    
        $("#contentInput").val($('#editor').summernote('code'));
    
        let formData = new FormData();
    
        $('#blogForm').find('input, select, textarea').each(function () {
    
            if (this.type === "file") return;
    
            if (this.name) {
                if (this.type === "checkbox") {
                    formData.append(this.name, this.checked ? "1" : "0");
                } else {
                    formData.append(this.name, $(this).val());
                }
            }
        });
    
        // append files only if user selected
        const thumbInput = document.getElementById('thumbnailInput');
        if (thumbInput.files.length > 0) {
            formData.append('thumb_nail', thumbInput.files[0]);
        }
    
        const heroInput = document.getElementById('heroImageInput');
        if (heroInput.files.length > 0) {
            formData.append('hero_image', heroInput.files[0]);
        }
    
        let id = $("#blog_id").val();
        formData.append("blog_id", id);
        formData.append("action", "edit_blog");
        formData.append("method", "edit_content");
    
        $.ajax({
            url: "/ajax/service/manageBlog_services.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend: function () {
                $('#updateCategoryBtn').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Updating...`)
                   .prop('disabled', true);
            },
    
            success: function (res) {
                if (res.type == 1) {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title: "Updated successfully",
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        didClose: () => {
                            location.reload();
                        }
                    });
                    
                    $('#updateCategoryBtn').html(`<i class="fa fa-upload me-2"></i> Update Content`);
                    
                } else {
                    $('#updateCategoryBtn').html(`<i class="fa fa-upload me-2"></i> Update Content`).prop('disabled', false);
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "error",
                        title: res.result || "Update failed",
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        });
    });


});

/* RESET */
$("#resetBtn").on("click", function () {
    loadCategory();
});
</script>