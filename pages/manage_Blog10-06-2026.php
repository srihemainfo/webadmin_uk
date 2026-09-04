<?php
$pageTitle = "Manage Posts";
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
                
                <div class="card shadow-sm">
                    <div class="card-body border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <small class="text-muted">
                                    Review and manage all your published blog content
                                </small>
                            </div>
                           <button class="btn btn-warning btn-sm fw-semibold"
                                   onclick="window.location.href='/create-category/';">
                                <i class="fa fa-plus me-1"></i> Create New Post
                            </button>
                        </div>
                
                        <div class="d-flex gap-2 mt-3 flex-wrap">
                            <div class="input-group input-group-sm" style="max-width:260px;">
                                <span class="input-group-text bg-light">
                                    <i class="fa fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control" id="postSearch" placeholder="Search posts...">
                            </div>
                
                            <select class="form-select" style="max-width:160px;" id="category_filter">
                                <option value="">All Categories</option>
                                 <?php
                                    $query = mysqli_query($con, "SELECT id, cat_name  FROM categories WHERE deleted_at = '0' ORDER BY id ASC");
                            
                                    if (!$query) {
                                        echo '<option value="">Query Error</option>';
                                    } else if (mysqli_num_rows($query) > 0) {
                                        while ($row = mysqli_fetch_assoc($query)) {
                                            echo '<option value="'.$row['id'].'">' . htmlspecialchars($row['cat_name']) . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">No categories found</option>';
                                    }
                                    ?>
                            </select>
                
                            <select class="form-select" style="max-width:120px;" id="status_filter">
                                <option value="">All Status</option>
                                <option value="1">Live</option>
                                <option value="0">Draft</option>
                            </select>
                        </div>
                    </div>
                
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="mb-3 fw-semibold">Category Report</h6>
                                <div class="table-responsive">
                                    <table class="table align-middle" id="categoryTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:25%">POST TITLE</th>
                                                <th style="width:20%">CATEGORY</th>
                                                <th style="width:20%">DATE</th>
                                                <th style="width:20%">STATUS</th>
                                                <th style="width:20%">ACTIONS</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body border-top d-flex justify-content-between align-items-center">
                        <small class="text-muted">Showing 1–3 of 24 posts</small>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled"><a class="page-link">‹</a></li>
                                <li class="page-item active"><a class="page-link">1</a></li>
                                <li class="page-item"><a class="page-link">2</a></li>
                                <li class="page-item"><a class="page-link">›</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    contentCategory();
});

$('#postSearch').on('keyup', function () {
    if (postTable) {
        postTable.search(this.value).draw();
    }
});

function updateStatus(el) {
    const id = el.dataset.id;
    const status = el.checked ? 1 : 0;

    $.ajax({
        url: "/ajax/service/manageBlog_services.php",
        type: "POST",
        dataType: "json",
        data: {
            id: id,
            status: status,
            method: "update_status"
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
                location.reload(); 
            } else {
                alert(res.message);
            }
        }
    });
}

function editCategory(id){
     if (!id) {
        alert("Invalid category ID");
        return;
    }
    window.open(`/content-edit/edit/${id}`, "_blank");
}

function deleteCategory(id){
     $.ajax({
        url: "/ajax/service/manageBlog_services.php",
        type: "POST",
        dataType: "json",
        data: {
            id: id,
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
                location.reload(); 
            } else {
                alert(res.message);
            }
        }
    });
}

$('#category_filter, #status_filter').on('change', function () {
    contentCategory();
});

let postTable;

const contentCategory = () => {
    const categoryId = $('#category_filter').val();
    const statusVal = $('#status_filter').val();

    $.ajax({
        url: "/ajax/service/manageBlog_services.php",
        type: "POST",
        dataType: "json",
        data: {
            method: 'create_index',
            category_id: categoryId,
            status: statusVal
        },
        success: function (res) {
            postTable = $('#categoryTable').DataTable({
                destroy: true,
                data: res.result || [],
                language: {
                    emptyTable: "No blogs found"
                },
                columns: [
                    {
                        data: null,
                        render: function (data, type, row) {
                            return `
                                <div>
                                    <strong>${row.blog_title}</strong><br>
                                    <small class="text-muted">${row.slug}</small>
                                </div>
                            `;
                        }
                    },
                    { data: 'cat_name' },
                    { data: 'published_date' },
                    {
                        data: 'status',
                        className: 'text-center',
                        render: function (data, type, row) {
                            return `
                                <div class="form-check form-switch">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           data-id="${row.id}"
                                           onchange="updateStatus(this)"
                                           ${row.status == 1 ? 'checked' : ''}>
                                </div>
                            `;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function (data, type, row) {
                            return `
                                <i class="fa fa-pen text-primary me-3"
                                   style="cursor:pointer"
                                   onclick="editCategory(${row.id})"></i>

                                <i class="fa fa-trash text-muted"
                                   style="cursor:pointer"
                                   onclick="deleteCategory(${row.id})"></i>
                            `;
                        }
                    }
                ]
            });
        }
    });
};
</script>