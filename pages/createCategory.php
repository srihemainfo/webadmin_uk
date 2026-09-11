<?php
$pageTitle = "Create Category";
$category_id = $_GET['category_id'] ?? null;
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

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
        <div class="main-container container-fluid mt-5 p-0">
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
            <div class="row">
                <div class="col-12">

                    <div class="card shadow-sm">
                        <div class="card-body">

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Category Name</label>
                                    <input type="text" id="category_name" class="form-control"
                                        placeholder="Enter category name">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Category Page URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text">/blog/</span>
                                        <input type="text" id="category_url" class="form-control"
                                            placeholder="my-awesome-category">
                                    </div>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label fw-semibold">SEO Title</label>
                                    <input type="text" id="seo_title" class="form-control"
                                        placeholder="Enter SEO Title">
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label fw-semibold">Meta Keywords</label>
                                    <input type="text" id="meta_keywords" class="form-control"
                                        placeholder="Enter keywords (comma separated)">
                                </div>

                                <div class="col-md-12 mt-3">
                                    <label class="form-label fw-semibold">SEO Description</label>
                                    <textarea id="seo_description" class="form-control" rows="3"
                                        placeholder="Enter SEO Description"></textarea>
                                </div>

                            </div>

                            <div class="mt-4">
                                <button class="btn btn-primary me-2" id="saveCategoryBtn">
                                    Save Category
                                </button>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="col-12">

                    <div class="card shadow-sm">
                        <div class="card-body">

                            <h6 class="mb-3 fw-semibold">Category Report</h6>

                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:15%">Category Name</th>
                                            <th style="width:15%">Page URL</th>
                                            <th style="width:15%">SEO Title</th>
                                            <th style="width:20%">SEO Description</th>
                                            <th style="width:15%">Meta Keywords</th>
                                            <th style="width:10%">Status</th>
                                            <th style="width:10%">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody id="categoryTableBody"></tbody>

                                </table>
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
        createCategory();

        // SEO Field Validation Logic
        function applyLimitValidation(selector, limit, fieldName) {
            let toastShown = false;
            $(selector).attr('maxlength', limit); // Enforces limit natively
            $(selector).on('input', function () {
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

    document.getElementById('category_name').addEventListener('input', function () {
        let value = this.value;

        let slug = value
            .trim()
            .toLowerCase()
            .replace(/[^a-z0-9\s-_]/g, '') // remove unsafe special chars
            .replace(/\s+/g, '-')          // spaces → hyphen
            .replace(/-+/g, '-');          // remove double hyphens

        document.getElementById('category_url').value = slug;
    });


    $("#saveCategoryBtn").on("click", function () {

        let cat_name = $("#category_name").val().trim();
        let slug = $("#category_url").val().trim();

        // Get SEO data
        let seo_title = $("#seo_title").val().trim();
        let seo_description = $("#seo_description").val().trim();
        let meta_keywords = $("#meta_keywords").val().trim();

        if (cat_name === "") {
            Swal.fire({
                icon: "warning",
                title: "Missing field",
                text: "Please enter Category Name"
            });
            $("#category_name").focus();
            return;
        }

        let cat_url = "/blog/" + slug;

        $.ajax({
            url: "/ajax/service/createCategory_services.php",
            type: "POST",
            dataType: "json",
            data: {
                cat_name: cat_name,
                cat_url: cat_url,
                seo_title: seo_title,             // Passed to backend
                seo_description: seo_description, // Passed to backend
                meta_keywords: meta_keywords,     // Passed to backend
                method: "create_category"
            },
            success: function (res) {
                if (res.type === 1) {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title: "Created successfully",
                        showConfirmButton: false,
                        timer: 2000
                    });

                    // Clear fields after saving
                    $("#category_name").val('');
                    $("#category_url").val('');
                    $("#seo_title").val('');
                    $("#seo_description").val('');
                    $("#meta_keywords").val('');

                    createCategory();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: res.message || res.result
                    });
                }
            }
        });

    });


    function editCategory(id) {
        if (!id) {
            alert("Invalid category ID");
            return;
        }
        window.open(`/category-edit/edit/${id}`, "_blank");
    }


    function updateStatus(el) {
        const id = el.dataset.id;
        const status = el.checked ? 1 : 0;

        $.ajax({
            url: "/ajax/service/createCategory_services.php",
            type: "POST",
            dataType: "json",
            data: {
                id: id,
                status: status,
                method: "update-status"
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
                        timerProgressBar: true
                    });

                    createCategory();
                } else {
                    alert(res.message);
                }
            }
        });
    }


    function deleteCategory(id) {
        let delete_id = id;

        $.ajax({
            url: "/ajax/service/createCategory_services.php",
            type: "POST",
            dataType: "json",
            data: {
                id: delete_id,
                method: "delete_category"
            },
            success: function (res) {
                if (res.type === 1) {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title: "Deleted successfully",
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });

                    createCategory();
                } else {
                    alert(res.message);
                }
            }
        });
    }

    const createCategory = () => {

        $.ajax({
            url: "/ajax/service/createCategory_services.php",
            type: "POST",
            dataType: "json",
            data: {
                method: 'create_index'
            },
            success: function (res) {

                let tbody = $("#categoryTableBody");
                tbody.empty(); // clear old rows

                if (res.type == '1' && res.result.length > 0) {

                    $.each(res.result, function (index, row) {

                        let tr = `
                        <tr>
                            <td>${row.category_name}</td>
                            <td class="text-muted">${row.category_url}</td>
                            <td>${row.seo_title ? row.seo_title : '<span class="text-muted">-</span>'}</td>
                            <td>${row.seo_description ? row.seo_description : '<span class="text-muted">-</span>'}</td>
                            <td>${row.meta_keywords ? row.meta_keywords : '<span class="text-muted">-</span>'}</td>
                            <td class="text-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           ${row.status == 1 ? 'checked' : ''}
                                           id="toggle_${row.id}"
                                           data-id="${row.id}"
                                           onchange="updateStatus(this)">
                                </div>
                            </td>
                            <td id="edit_con">
                                <i class="fa fa-pen text-primary me-3"
                                   style="cursor:pointer;"
                                   onclick="editCategory(${row.id})"></i>
        
                                <i class="fa fa-trash text-muted"
                                   style="cursor:pointer;"
                                   onclick="deleteCategory(${row.id})"></i>
                            </td>
                        </tr>
                    `;

                        tbody.append(tr);
                    });

                } else {

                    tbody.append(`
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fa fa-folder-open me-2"></i>
                            No category found
                        </td>
                    </tr>
                `);
                }
            }

        });
    };
</script>