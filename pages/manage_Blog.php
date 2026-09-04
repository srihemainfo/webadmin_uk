<?php
$pageTitle = "Manage Posts";
?> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="scheduleModalLabel">Schedule Blog Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="schedule_blog_id">
        <div class="mb-3">
            <label class="form-label">Select Date</label>
            <input type="date" class="form-control" id="schedule_date" min="<?= date('Y-m-d') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Select Time (Hourly base)</label>
            <select class="form-select" id="schedule_time">
                <option value="00:00:00">12:00 AM</option>
                <option value="01:00:00">01:00 AM</option>
                <option value="02:00:00">02:00 AM</option>
                <option value="03:00:00">03:00 AM</option>
                <option value="04:00:00">04:00 AM</option>
                <option value="05:00:00">05:00 AM</option>
                <option value="06:00:00">06:00 AM</option>
                <option value="07:00:00">07:00 AM</option>
                <option value="08:00:00">08:00 AM</option>
                <option value="09:00:00">09:00 AM</option>
                <option value="10:00:00">10:00 AM</option>
                <option value="11:00:00">11:00 AM</option>
                <option value="12:00:00">12:00 PM (Noon)</option>
                <option value="13:00:00">01:00 PM</option>
                <option value="14:00:00">02:00 PM</option>
                <option value="15:00:00">03:00 PM</option>
                <option value="16:00:00">04:00 PM</option>
                <option value="17:00:00">05:00 PM</option>
                <option value="18:00:00">06:00 PM</option>
                <option value="19:00:00">07:00 PM</option>
                <option value="20:00:00">08:00 PM</option>
                <option value="21:00:00">09:00 PM</option>
                <option value="22:00:00">10:00 PM</option>
                <option value="23:00:00">11:00 PM</option>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="saveSchedule()">Save Schedule</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
    contentCategory();

    // Trigger time option filtering when date changes
    $('#schedule_date').on('change', function() {
        updateTimeOptions();
    });
});

$('#postSearch').on('keyup', function () {
    if (postTable) {
        postTable.search(this.value).draw();
    }
});

// Logic to disable past hours for today's date
function updateTimeOptions() {
    let selectedDate = $('#schedule_date').val();
    let today = new Date();
    
    // Format today as YYYY-MM-DD
    let currentYear = today.getFullYear();
    let currentMonth = String(today.getMonth() + 1).padStart(2, '0');
    let currentDay = String(today.getDate()).padStart(2, '0');
    let todayString = `${currentYear}-${currentMonth}-${currentDay}`;
    
    let currentHour = today.getHours();

    $('#schedule_time option').each(function() {
        let optionHour = parseInt($(this).val().split(':')[0], 10);
        
        if (selectedDate === todayString) {
            // If today, disable any hours that have already passed (including current hour)
            if (optionHour <= currentHour) {
                $(this).hide();
                $(this).prop('disabled', true);
            } else {
                $(this).show();
                $(this).prop('disabled', false);
            }
        } else {
            // For future dates, enable all hours
            $(this).show();
            $(this).prop('disabled', false);
        }
    });

    // Auto-select the first available future time if the current selection is disabled
    if ($('#schedule_time option:selected').is(':disabled') || !$('#schedule_time').val()) {
        $('#schedule_time').val($('#schedule_time option:not(:disabled):first').val());
    }
}

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
                    toast: true, position: "top-end", icon: "success", title: "Updated successfully", showConfirmButton: false, timer: 2000, timerProgressBar: true
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
                    toast: true, position: "top-end", icon: "success", title: "Deleted successfully", showConfirmButton: false, timer: 2000, timerProgressBar: true
                });
                location.reload(); 
            } else {
                alert(res.message);
            }
        }
    });
}

// AI Interlink Function
function autoInterlink(id) {
    if (!id) return;

    Swal.fire({
        title: 'Analyzing Content...',
        text: 'Artificial Intelligence is reading blog and adding relevant internal links. This may take up to a minute.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: "/ajax/service/manageBlog_services.php",
        type: "POST",
        dataType: "json",
        data: {
            id: id,
            method: "auto_interlink"
        },
        success: function (res) {
            if (res.type === 1) {
                Swal.fire({ icon: "success", title: "Success!", text: res.message, timer: 2500, showConfirmButton: false });
            } else {
                Swal.fire("Error", res.message, "error");
            }
        },
        error: function (xhr, status, error) {
            Swal.fire("Error", "Server request failed or timed out. Check console for details.", "error");
        }
    });
}

// Open Schedule Modal
function openScheduleModal(id) {
    $('#schedule_blog_id').val(id);
    
    // Set date to today automatically so time constraints immediately apply
    let today = new Date();
    let currentYear = today.getFullYear();
    let currentMonth = String(today.getMonth() + 1).padStart(2, '0');
    let currentDay = String(today.getDate()).padStart(2, '0');
    let todayString = `${currentYear}-${currentMonth}-${currentDay}`;
    
    $('#schedule_date').val(todayString);
    updateTimeOptions(); // Validate times for current date

    $('#scheduleModal').modal('show');
}

// Save Schedule Action
function saveSchedule() {
    let id = $('#schedule_blog_id').val();
    let date = $('#schedule_date').val();
    let time = $('#schedule_time').val();

    if(!date || !time) {
        Swal.fire('Error', 'Please select a valid date and time.', 'error');
        return;
    }

    let scheduled_at = date + ' ' + time;

    $.ajax({
        url: "/ajax/service/manageBlog_services.php",
        type: "POST",
        dataType: "json",
        data: {
            id: id,
            scheduled_at: scheduled_at,
            method: "schedule_blog"
        },
        success: function (res) {
            if (res.type === 1) {
                $('#scheduleModal').modal('hide');
                Swal.fire({
                    toast: true, position: "top-end", icon: "success", title: "Scheduled successfully", showConfirmButton: false, timer: 2000, timerProgressBar: true
                });
                contentCategory(); // Refresh the table
            } else {
                Swal.fire("Error", res.result || res.message, "error");
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
                            let actionBtns = `
                                <i class="fa fa-sync text-info me-3" style="cursor:pointer" title="AI Auto-Interlink" onclick="autoInterlink(${row.id})"></i>
                                <i class="fa fa-pen text-primary me-3" style="cursor:pointer" title="Edit" onclick="editCategory(${row.id})"></i>
                                <i class="fa fa-trash text-muted me-3" style="cursor:pointer" title="Delete" onclick="deleteCategory(${row.id})"></i>
                            `;
                            
                            // If draft mode (status == 0), show schedule options
                            if(row.status == 0) {
                                actionBtns += `<i class="fa fa-calendar-alt text-warning" style="cursor:pointer" title="Schedule Blog" onclick="openScheduleModal(${row.id})"></i>`;
                                
                                if(row.scheduled_at !== null && row.scheduled_at !== '0000-00-00 00:00:00' && row.scheduled_at !== '') {
                                    actionBtns += `<br><small class="text-success fw-bold mt-1 d-block"><i class="fa fa-clock"></i> ${row.scheduled_at}</small>`;
                                }
                            }
                            
                            return actionBtns;
                        }
                    }
                ]
            });
        }
    });
};
</script>