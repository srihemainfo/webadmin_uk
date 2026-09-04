<?php
// echo "<pre>";
// print_r($_GET);
// echo "</pre>";
$pageTitle = "Edit Category";
/* Get category ID from URL
   Example: /category-edit/edit/5
*/
$categoryID = $subid3;
// var_dump($categoryID);
$sql = "SELECT * FROM `categories`  WHERE `id` = '$categoryID'";
   $run = mysqli_query($con, $sql);
   if (mysqli_num_rows($run) > 0) {
      $row = $run->fetch_assoc();
   }
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
</style>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid mt-5 p-0">

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

                    <div class="card shadow-sm">
                        <div class="card-body">

                            <input type="hidden" id="category_id" value="<?= $categoryID ?>">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Category Name</label>
                                    <input type="text" value="<?=$row['cat_name']?>"
                                           id="category_name"
                                           class="form-control"
                                           placeholder="Enter category name">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Category Page URL</label>
                                    <div class="input-group">
                                        <input type="text"
                                               id="category_url"
                                               class="form-control"
                                               value="<?=$row['cat_url']?>"
                                               placeholder="my-awesome-category">
                                    </div>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label fw-semibold">SEO Title</label>
                                    <input type="text" id="seo_title" class="form-control" value="<?= htmlspecialchars($row['seo_title'] ?? '', ENT_QUOTES) ?>" placeholder="Enter SEO Title">
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label fw-semibold">Meta Keywords</label>
                                    <input type="text" id="meta_keywords" class="form-control" value="<?= htmlspecialchars($row['meta_keywords'] ?? '', ENT_QUOTES) ?>" placeholder="Enter keywords (comma separated)">
                                </div>

                                <div class="col-md-12 mt-3">
                                    <label class="form-label fw-semibold">SEO Description</label>
                                    <textarea id="seo_description" class="form-control" rows="3" placeholder="Enter SEO Description"><?= htmlspecialchars($row['seo_description'] ?? '', ENT_QUOTES) ?></textarea>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button class="btn btn-primary me-2" id="updateCategoryBtn">
                                    Update Category
                                </button>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    // loadCategory(); // Uncomment if you decide to load data via AJAX later

    // SEO Field Validation Logic
    function applyLimitValidation(selector, limit, fieldName) {
        let toastShown = false;
        $(selector).attr('maxlength', limit); // Enforces limit natively
        $(selector).on('input', function() {
            if ($(this).val().length >= limit) {
                if (!toastShown) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'warning',
                        title: `${fieldName} limit reached (${limit} characters)`,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    toastShown = true;
                }
            } else {
                toastShown = false;
            }
        });
    }

    applyLimitValidation('#seo_title', 65, 'SEO Title');
    applyLimitValidation('#seo_description', 165, 'SEO Description');
    applyLimitValidation('#meta_keywords', 255, 'Meta Keywords');
});

/* UPDATE CATEGORY */
$("#updateCategoryBtn").on("click", function () {

    let id = $("#category_id").val();
    let name = $("#category_name").val().trim();
    let slug = $("#category_url").val().trim();
    
    // Capture SEO Data
    let seo_title = $("#seo_title").val().trim();
    let seo_description = $("#seo_description").val().trim();
    let meta_keywords = $("#meta_keywords").val().trim();

    if (!name || !slug) {
        Swal.fire({
            icon: "warning",
            title: "Missing fields",
            text: "Category name and URL are required"
        });
        return;
    }
    
    $.ajax({
        url: "/ajax/service/createCategory_services.php",
        type: "POST",
        dataType: "json",
        data: {
            method: "update_category",
            id: id,
            cat_name: name,
            cat_url: slug,
            seo_title: seo_title,             // Send to backend
            seo_description: seo_description, // Send to backend
            meta_keywords: meta_keywords      // Send to backend
        },
        success: function (res) {
            if (res.type === 1) {
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "success",
                    title: "Updated successfully",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didClose: () => {
                        window.location.href = "/create-category/";
                    }
                });
            } else {
                 Swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "error",
                    title: res.result || "Update failed",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                });
            }
        }
    });
});

/* RESET */
$("#resetBtn").on("click", function () {
    location.reload(); // Quick reset since data is loaded via PHP
});
</script>