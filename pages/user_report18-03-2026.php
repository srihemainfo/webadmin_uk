<?php
$pageTitle = "RM Report";
// error_reporting(E_ALL);
// ini_set('display_errors', 1);


?> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style> 

   .modal-body {
    padding: 0;
}

#carDetailsContent {
    font-family: "Segoe UI", monospace;
    font-size: 14px;
    padding-left: 20px;
}

/* Grid layout for details */
.car-details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px 24px;
}

/* Each detail row */
.car-details-grid div {
    padding: 6px 0;
    border-bottom: 1px dashed #e0e0e0;
}

/* Label style */
.car-details-grid span.label {
    font-weight: 600;
    color: #333;
}

/* Value style */
.car-details-grid span.value {
    color: #555;
}

/* Mobile view */
@media (max-width: 768px) {
    .car-details-grid {
        grid-template-columns: 1fr;
    }
}


.car-key {
    text-transform: capitalize; 
    font-weight: 600;           
}


.image-box {
    width: 100%;
    height: 85vh;                 
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
}

.modal-car-img {
    width: 100%;
    height: 100%;
    object-fit: contain;         
}


    /* CLOSE BUTTON */
    .custom-close-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        z-index: 9999;

        width: 32px;
        height: 32px;

       
        color: #000;
        border: none;

        font-size: 18px;
        line-height: 32px;
        text-align: center;
        cursor: pointer;
    }

      textarea {
        height: 100px;
        padding: 12px 20px;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
        background-color: #f8f8f8;
        font-size: 16px;
        resize: none;
    }
        .custom-modal {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .custom-modal-content {
        background: #fff;
        padding: 20px;
        width: 600px;
        max-height: 80vh;
        overflow: auto;
        border-radius: 6px;
        position: relative;
    }

    .custom-modal-content pre {
        white-space: pre-wrap;
        font-size: 14px;
    }

    .close-car-modal {
        position: absolute;
        right: 15px;
        top: 10px;
        font-size: 22px;
        cursor: pointer;
    }

    
    .card-header.d-lg-flex.d-block.justify-content-between {
        border-bottom: none;
    }
    .cursor-pointer {
        cursor: pointer;
        font-size: 1.2rem; /* Adjust size as needed */
    }

    input,
    select {
        border: 1px solid #CCC;
        /* width: 250px; */
    }

    .nav.product-sale {
        position: unset;
        top: -3rem;
        right: 5px;
        margin: 12px 0;
    }

    input,
    button {
        height: 35px;
        margin: 0;
        padding: 6px 12px;
        border-radius: 2px;
        font-family: inherit;
        font-size: 100%;
        color: inherit;
    }

    .swal-modal {
        border: 3px solid white;
        color: #fff;
    }

    .swal-button {
        background-color: #07f3a2 !important;
    }

    .swal-text {
        font-weight: 600 !important;
    }

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

    .required-indicator {
        color: red;
        /* Change to the desired color */
    }

    .dt-column-order {
        display: none;
    }

    table.dataTable th.dt-type-numeric,
    table.dataTable th.dt-type-date,
    table.dataTable td.dt-type-numeric,
    table.dataTable td.dt-type-date {
        text-align: left;
    }

    .form-control:disabled,
    .form-control[readonly] {
        background-color: rgb(255 255 255 / 10%);
        /* opacity: 1; */
    }

    input,
    select {
        border: 1px solid #CCC;
        /* width: 250px; */
    }

    .text {
        float: unset !important;
    }

    .dropdown-item {
        display: block;
        width: 100%;
        padding: 0.25rem 1rem;
        clear: both;
        font-weight: 400;
        color: #121212 !important;
        text-align: inherit;
        text-decoration: none;
        white-space: nowrap;
        background-color: white;
        border: 0;
    }

    input,
    button {
        height: 35px;
        margin: 0;
        padding: 6px 12px;
        border-radius: 2px;
        font-family: inherit;
        font-size: 100%;
        color: inherit;
    }

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

    .btn-light {
        /* color: #495057; */
        background-color: #ffffff !important;
    }

    .dropdown.bootstrap-select {
        display: block !important;
        width: 100% !important;
    }
    
div:where(.swal2-container) {
    z-index: 9999 !important;
}

div:where(.swal2-container) h2:where(.swal2-title) {
    color: rgb(85 85 85) !important;
}

.clickable-bid-cell {
    cursor: pointer;
    color: #3200fa;
    font-weight: 700;
    text-align: center;
}

.clickable-place {
    cursor: pointer;
}
    
</style>
<script>
    window.onload = function () {
        var page_origin = window.location.origin;
        let anchor = document.getElementById("anchor");
        anchor.href = page_origin;
    }
</script>
<!--app-content open-->
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
            <!--<div class="row">-->
            <!--    <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">-->
            <!--        <div class="card ">-->
            <!--            <div class="card-body">-->
            <!--                <div class="d-flex1">-->
            <!--                    <div class="row mt-1">-->

            <!--                        <div class="col-md-2">-->
            <!--                            <span>Date</span>-->
            <!--                            <input class="form-control" type="text" id="datefilterLogin"-->
            <!--                                   name="datefilterLogin" placeholder="Select Date" value="" readonly />-->
            <!--                        </div>-->
                                
                                    <!-- Search Button -->
            <!--                        <div class="col-md-2">-->
            <!--                            <span class="d-block">&nbsp;</span>-->
            <!--                            <button class="btn btn-primary" onclick="cabList()">Search</button>-->
            <!--                        </div>-->
                                
            <!--                    </div>-->

            <!--                    <div class="row">-->
            <!--                        <div class="col-4" id="searcherr">-->
            <!--                        </div>-->
                                    
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
        </div>
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-4">
                        <div class="row align-items-end">

                        <!-- LEFT SIDE -->
                            <div class="col-12">
                                <div class="row mt-1">
                        
                                   <div class="col-sm-12 col-md-3 col-lg-3">

                                            <span>Search (Name / Mobile / Email)</span>

                                            <input class="form-control" type="text" id="searchTxt" name="searchTxt"

                                                placeholder="Name / Mobile / Email" value=""

                                                maxlength="70" />

                                        </div>
                                    
                                    <div class="col-sm-12 col-md-3 col-lg-3">

                                            <span>Select filter</span>

                                            <select class="form-control" id="search_filter">

                                                <option value="all">All</option>

                                                <option value="Owner">Owner</option>

                                                <option value="Driver">Driver</option>

                                            </select> 
                                            
                                        </div>
                                        
                                    <div class="col-sm-12 col-md-3 col-lg-3">

                                            <span>Select filter</span>

                                            <select class="form-control" id="rmFilter">

                                                <option value="all">All</option>

                                                <option value="1">Enable (Assigned)</option>
                                                
                                                <option value="2">Enable (Un Assigned)</option>

                                                <option value="0">Disable</option>

                                            </select> 
                                            
                                        </div>
                                    
                                    <div class="col-sm-12 col-md-3 col-lg-3">

                                         <label for="agent_id">Agents List</label>
                                        <select class="form-control form-select" id="agent_id" name="agent_id">
                                            <option value="">Select Agent</option>
                                            <?php
                                            // Assuming $assigned_agent_id holds the ID of the agent currently saved to this record
                                            $assigned_agent_id = $row['agent_id'] ?? null; 
                            
                                            $query = mysqli_query($con, "SELECT id, name, lname FROM user_register WHERE roll_id = 3 AND deletes = '0' ");
                                            
                                            if ($query) {
                                                
                                                while ($agentsList = mysqli_fetch_assoc($query)) {
                                                    // Compare the loop's ID with the previously saved agent ID
                                                    $selected = ($agentsList['id'] == $assigned_agent_id) ? 'selected' : '';
                                                    
                                                    echo '<option value="' . htmlspecialchars($agentsList['id']) . '" ' . $selected . '>' 
                                                         . htmlspecialchars($agentsList['name']) . ' ' . htmlspecialchars($agentsList['lname']) 
                                                         . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                            
                                        </div>
                                     
                                
                                    <div class="col-md-3">
                                        <span class="d-block">&nbsp;</span>
                                        <button class="btn btn-primary " onclick="cabList()">Search</button>
                                    </div>
                        
                                </div>
                        
                                <div class="row">
                                    <div class="col-12" id="searcherr"></div>
                                </div>
                            </div>
                        
                            <!-- RIGHT SIDE -->
                            <!--<div class="col-md-4 col-12 text-md-end mt-3 mt-md-0">-->
                            <!--    <button class="btn btn-sm btn-primary"-->
                            <!--            data-bs-toggle="modal"-->
                            <!--            data-bs-target="#createModal">-->
                            <!--        <i class="bi bi-plus-circle"></i> Create-->
                            <!--    </button>-->
                            <!--</div>-->
                        
                        </div>

                        <div class="grid-margin">
                            <div class="panel panel-primary">
                                <div class="tab-menu-heading border-0 p-0">
                                    <div class="tabs-menu1">
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body border-0 pt-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab1">
                                            <div class="card-header d-lg-flex d-block justify-content-between">
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered text-nowrap border-bottom"
                                                    id="Participation_List" style="width:100%;">
                                                    <thead>
                                                        <tr>
                                                            <th class="column_sort sorting sorting_asc">Created At</th>
                                                            <th class="column_sort sorting sorting_asc">Name</th>
                                                            <th class="column_sort sorting sorting_asc">Type</th>
                                                            <th class="column_sort sorting sorting_asc">Mobile No</th>
                                                            <th class="column_sort sorting sorting_asc">Email</th>
                                                            <th class="column_sort sorting sorting_asc">Rm Status</th>
                                                            <th class="column_sort sorting sorting_asc">Agents</th>
                                                            <th class="column_sort sorting sorting_asc">Action</th>
                                                            <!--<th class="column_sort sorting sorting_asc">Action</th>-->
                                                            <!-- <th class="column_sort sorting sorting_asc">Address</th>-->
                                                            <!--<th class="column_sort sorting sorting_asc">Cab Type</th>-->
                                                            <!--<th class="column_sort sorting sorting_asc">Fuel Type</th>-->
                                                            <!-- <th class="column_sort sorting sorting_asc">Area</th>-->
                                                            <!--<th class="column_sort sorting sorting_asc">Districts</th>-->
                                                            <!--<th class="column_sort sorting sorting_asc">States</th>-->
                                                            <!--<th class="column_sort sorting sorting_asc">To</th>-->
                                                            <!--<th class="column_sort sorting sorting_asc">Pickup Date</th>-->
                                                            <!--<th class="column_sort sorting sorting_asc">Dropoff Date</th>-->
                                                            <!--<th class="column_sort sorting sorting_asc">Dis / PC</th>-->
                                                            <!--<th class="column_sort sorting sorting_asc">Fare</th>-->
                                                            <!--<th class="column_sort sorting sorting_asc">Created At</th>-->
                                                            <!--<th class="column_sort sorting sorting_asc">Action</th>-->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
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
            </div>
        </div>
        <!-- ROW-4 END -->
    </div>
    <!-- CONTAINER END -->
</div>



<div class="modal fade" id="locationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title">User Location</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <div id="locationContent" class="location-details-grid">
          Loading...
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Close
        </button>
      </div>

    </div>
  </div>
</div>




<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
  const quill = new Quill('#whatsapp_message', {
    theme: 'snow'
  });
</script>

<script>

$(document).on("click", ".edit-btn", function () {

    let id = $(this).data("id");

    let baseUrl = window.location.origin;

    window.location.href = baseUrl + "/profile/edit/" + id;

});

 const API_DOMAIN_2 = "<?php echo API_DOMAIN_2; ?>";

$(document).on('click', '.location-icon', function () {

    let userId = $(this).data('id');

    // You can load data via AJAX here if needed
    $('#locationContent').html("User ID: " + userId);

    // Open Modal
    let modal = new bootstrap.Modal(document.getElementById('locationModal'));
    modal.show();
});


$(document).on('click', '.location-icon', function () {

    let userId = $(this).data('id').toString();
    let token = "asdfghjklpoiuytrewqzxcvbnm!@$%^&*()";

    let modal = new bootstrap.Modal(document.getElementById('locationModal'));
    modal.show();

    $('#locationContent').html(`
        <div class="text-center py-3">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2 mb-0">Fetching location...</p>
        </div>
    `);

    $.ajax({
        url: API_DOMAIN_2 + "rm-req-list",
        type: "POST",
        contentType: "application/json",
        headers: {
            Authorization: `Bearer ${token}`
        },
        data: JSON.stringify({ id: userId }),

        success: function (response) {

    if (response.status === true && response.data) {

        let districts = response.data.district
            .map(d => `
                <li class="list-group-item list-group-item-light rounded-3 mb-2">
                    ${d}
                </li>
            `)
            .join("");

        let createdDate = new Date(response.data.created_at);
        let formattedDate = createdDate.toLocaleString("en-IN", {
            day: "2-digit",
            month: "short",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit"
        });

        $('#locationContent').html(`
            <div class="mb-3">
                <label class="form-label fw-medium">
                    <i class="bi bi-person-circle text-primary me-2"></i>
                    User ID
                </label>
                <div class="form-control form-control-sm rounded-3 bg-light">
                    ${response.data.user_id}
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">
                    <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                    Districts
                </label>
                <ul class="list-unstyled">
                    ${districts}
                </ul>
            </div>

            <div>
                <label class="form-label fw-medium">
                    <i class="bi bi-clock text-muted me-2"></i>
                    Created At
                </label>
                <div class="form-control form-control-sm rounded-3 bg-light">
                    ${formattedDate}
                </div>
            </div>
        `);

    } else {

        $('#locationContent').html(`
            <div class="alert alert-warning text-center rounded-3">
                ${response.message || "No location data found."}
            </div>
        `);
    }
},


        error: function (xhr) {
            $('#locationContent').html(`
                <div class="alert alert-danger text-center mb-0">
                    No location data found.
                </div>
            `);
        }
    });

});






// function previewEditImage() {
//     const fileInput = document.getElementById('edit_cab_image');
//     const preview = document.getElementById('editImagePreview');

//     if (fileInput.files && fileInput.files[0]) {
//         const reader = new FileReader();
//         reader.onload = function (e) {
//             preview.src = e.target.result;
//             preview.style.display = 'block';
//         };
//         reader.readAsDataURL(fileInput.files[0]);
//     }
// }



// function previewImage() {
//     const fileInput = document.getElementById('cab_image');
//     const preview = document.getElementById('imagePreview');

//     if (fileInput.files && fileInput.files[0]) {
//         const reader = new FileReader();

//         reader.onload = function (e) {
//             preview.src = e.target.result;
//             preview.style.display = 'block';
//         };

//         reader.readAsDataURL(fileInput.files[0]);
//     } else {
//         alert('Please select an image first');
//     }
// }


// $(document).on('click', '#saveCreate', function () {

//     let name = $('#cab_name').val().trim();
//     let seat = $('#cab_seater').val().trim();
//     let file = $('#cab_image')[0].files[0];
    
//   if (name === '') {
//     Swal.fire({
//         toast: true,
//         position: 'top-end',
//         icon: 'warning',
//         title: 'Name is required',
//         showConfirmButton: false,
//         timer: 3000,
//         timerProgressBar: true
//     });
// }


   
//     let formData = new FormData();
//     formData.append('name', name);
//     formData.append('seats', seat);
//     formData.append('image', file); 
//     formData.append('method', 'create_cabs');

//     $.ajax({
//         url: origin + "/ajax/service/cabList_services.php",
//         type: 'POST',
//         dataType: 'json',
//         data: formData,
//         contentType: false,  
//         processData: false, 
//         success: function (res) {
//             console.log(res);

//          if (res.status) {
//             Swal.fire({
//                 toast: true,
//                 position: 'top-end',
//                 icon: 'success',
//                 title: 'Cab created successfully',
//                 showConfirmButton: false,
//                 timer: 3000,
//                 timerProgressBar: true
//             });
//         } else {
//             Swal.fire({
//                 toast: true,
//                 position: 'top-end',
//                 icon: 'error',
//                 title: res.message || 'Something went wrong',
//                 showConfirmButton: false,
//                 timer: 3000,
//                 timerProgressBar: true
//             });
//         }


//         },
//         error: function (err) {
//             console.error(err);
//             alert('Something went wrong');
//         }
//     });
// });



//   $(document).on("click", ".car_details", function () {
//     let value = $(this).val();

//     try {
//         let obj = typeof value === "string" ? JSON.parse(value) : value;

//         // Format keys on left side only
//         let formatted = Object.entries(obj)
//             .map(([k, v]) => {
//                 let key = k.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
//                 return `<span class="car-key">${key}:</span> ${v ?? 'null'}`;
//             })
//             .join('<br>');

//         $("#carDetailsContent").html(formatted);
//     } catch (e) {
//         $("#carDetailsContent").text(value);
//     }

//     // Show modal using Bootstrap
//     var modal = new bootstrap.Modal(document.getElementById('carDetailsModal'));
//     modal.show();
// });


//   $(document).on("click", ".close-car-modal", function () {
//     var modal = bootstrap.Modal.getInstance(document.getElementById('carDetailsModal'));
//     if (modal) {
//         modal.hide(); 
//     }
//     });

//     $(document).on("click", ".close-car-modal", function () {
//     var modal = bootstrap.Modal.getInstance(document.getElementById('imageViewModal'));
//     if (modal) {
//         modal.hide(); 
//     }
//     });
    
//     function editCar(id) {

//     $('#edit_cab_id').val('');
//     $('#edit_cab_name').val('');
//     $('#edit_cab_seater').val('');
//     $('#editImagePreview').hide().attr('src', '');

//     $.ajax({
//         url: origin + "/ajax/service/cabList_services.php",
//         type: 'POST',
//         dataType: 'json',
//         data: {
//             method: 'get_cab',
//             cab_id: id
//         },
//         success: function (res) {

//             if (res.type === 1) {

//                 $('#edit_cab_id').val(res.result.id);
//                 $('#edit_cab_name').val(res.result.name);
//                 $('#edit_cab_seater').val(res.result.seats);

               
//                 if (res.result.image_url) {
//                     $('#editImagePreview')
//                         .attr('src', res.result.image_url)
//                         .show();
//                 }

//                 const modal = new bootstrap.Modal(
//                     document.getElementById('editCabModal')
//                 );
//                 modal.show();

//             } else {
//                 Swal.fire({
//                     icon: 'error',
//                     title: 'Error',
//                     text: res.result || 'Failed to load cab details'
//                 });
//             }
//         }
//     });
// }




//     $(document).on('click', '#updateCab', function () {

//     let formData = new FormData();
//     formData.append('id', $('#edit_cab_id').val());
//     formData.append('name', $('#edit_cab_name').val());
//     formData.append('seats', $('#edit_cab_seater').val());
//     formData.append('method', 'update_cab');

//     const file = $('#edit_cab_image')[0].files[0];
//     if (file) {
//         formData.append('image', file); // ✅ optional
//     }

//     $.ajax({
//         url: origin + "/ajax/service/cabList_services.php",
//         type: 'POST',
//         dataType: 'json',
//         data: formData,
//         contentType: false,
//         processData: false,
//         success: function (res) {

//             if (res.status) {
//                 Swal.fire({
//                     icon: 'success',
//                     title: 'Updated',
//                     text: 'Cab updated successfully'
//                 }).then(() => {
//                     $('#editCabModal').modal('hide');
//                     // reload list here
//                 });
//             } else {
//                 Swal.fire({
//                     icon: 'error',
//                     title: 'Error',
//                     text: res.message
//                 });
//             }
//         }
//     });
// });


// function deleteCar(id) {

//     Swal.fire({
//         title: 'Are you sure?',
//         text: 'This action cannot be undone!',
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#d33',
//         cancelButtonColor: '#6c757d',
//         confirmButtonText: 'Yes',
//         cancelButtonText: 'Cancel'
//     }).then((result) => {

//         if (result.isConfirmed) {

//             $.ajax({
//                 url: origin + "/ajax/service/cabList_services.php",
//                 type: 'POST',
//                 dataType: 'json',
//                 data: {
//                     method: 'delete_cab',
//                     cab_id: id
//                 },
//                 success: function (res) {

//                     if (res.type === 1) {

//                         Swal.fire({
//                             icon: 'success',
//                             title: 'Deleted!',
//                             text: 'Deleted successfully',
//                             timer: 1500,
//                             showConfirmButton: false
//                         });

//                         // Reload DataTable
//                         $('#Participation_List')
//                             .DataTable()
//                             .ajax.reload(null, false);

//                     } else {

//                         Swal.fire({
//                             icon: 'error',
//                             title: 'Failed',
//                             text: res.result || 'Delete failed'
//                         });
//                     }
//                 }
//             });
//         }
//     });
// }


    // $(document).on("click", "#carDetailsModal", function (e) {
    // if (e.target.id === "carDetailsModal") {
    //     $("#carDetailsModal").fadeOut(150);
    // }
    // });
    

    
//   function cabTypes(carType, id) {

//     if (!carType) return;

//     $.ajax({
//         url: origin + "/ajax/service/cabServices.php",
//         type: "POST",
//         data: { 
//             id: id, 
//             car_type: carType,
//             method: "car_types"
//         },
//         success: function (res) {
//             console.log(res);

//             // Success toast
//             Swal.fire({
//                 toast: true,
//                 position: "top-end",
//                 icon: "success",
//                 title: "Car type updated successfully",
//                 showConfirmButton: false,
//                 timer: 2000,
//                 timerProgressBar: true
//             });
//         },
//         error: function () {
//             Swal.fire({
//                 toast: true,
//                 position: "top-end",
//                 icon: "error",
//                 title: "Failed to update car type",
//                 showConfirmButton: false,
//                 timer: 2000
//             });
//         }
//     });
// }

//     function tankTypes(tankType, id) {

//     if (!tankType) return;
//     // const tank = $('#tank_type').val();
//     // console.log(tank);

//     $.ajax({
//         url: origin + "/ajax/service/cabServices.php",
//         type: "POST",
//         data: { 
//             id: id, 
//             tank_type: tankType,
//             method: "tank_types"
//         },
//         success: function (res) {
//             // console.log(res);

//             // Success toast
//             Swal.fire({
//                 toast: true,
//                 position: "top-end",
//                 icon: "success",
//                 title: "updated successfully",
//                 showConfirmButton: false,
//                 timer: 2000,
//                 timerProgressBar: true
//             });
//         },
//         error: function () {
//             Swal.fire({
//                 toast: true,
//                 position: "top-end",
//                 icon: "error",
//                 title: "Failed to update car type",
//                 showConfirmButton: false,
//                 timer: 2000
//             });
//         }
//     });
// }

//      function districtsCabs(districtsId, id) {
         
//         const districts_val = districtsId;
//         // console.log(districts_val);

//     if (!districts_val) return;
//     // const tank = $('#tank_type').val();
//     // console.log(tank);

//     $.ajax({
//         url: origin + "/ajax/service/cabServices.php",
//         type: "POST",
//         data: { 
//             id: id, 
//             districts: districts_val,
//             method: "district_list"
//         },
//         success: function (res) {
//             // console.log(res);

//             // Success toast
//             Swal.fire({
//                 toast: true,
//                 position: "top-end",
//                 icon: "success",
//                 title: "updated successfully",
//                 showConfirmButton: false,
//                 timer: 2000,
//                 timerProgressBar: true
//             });
//         },
//         error: function () {
//             Swal.fire({
//                 toast: true,
//                 position: "top-end",
//                 icon: "error",
//                 title: "Failed to update car type",
//                 showConfirmButton: false,
//                 timer: 2000
//             });
//         }
//     });
// }

    
//     function statesCabs(stateId, id) {
         
//         const state_val = stateId;
//         // console.log(state_val);

//     if (!state_val) return;
   

//     $.ajax({
//         url: origin + "/ajax/service/cabServices.php",
//         type: "POST",
//         data: { 
//             id: id, 
//             state: state_val,
//             method: "state_list"
//         },
//         success: function (res) {
//             // console.log(res);

//             // Success toast
//             Swal.fire({
//                 toast: true,
//                 position: "top-end",
//                 icon: "success",
//                 title: "updated successfully",
//                 showConfirmButton: false,
//                 timer: 2000,
//                 timerProgressBar: true
//             });
//         },
//         error: function () {
//             Swal.fire({
//                 toast: true,
//                 position: "top-end",
//                 icon: "error",
//                 title: "Failed to update car type",
//                 showConfirmButton: false,
//                 timer: 2000
//             });
//         }
//     });
// }

$(document).on("click", ".delete-btn", function () {

    let rowId = $(this).data("id");

    if(confirm("Are you sure you want to Disable the RM Support for this Driver / Owner?")) {

        $.ajax({
            url: origin + "/ajax/service/userReport_service.php",
            type: "POST",
            data: {
                action: "delete",
                id: rowId
            },
            success: function(response) {

                console.log("Delete success:", response);

                // safest reload method
                if ($.fn.DataTable.isDataTable('#Participation_List')) {
                    $('#Participation_List').DataTable().ajax.reload(null, false);
                }

                alert("RM Disabled Successfully");

            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert("Delete failed");
            }
        });

    }

});

$(document).ready(function(){
    cabList();
});

    
    let origin = window.location.origin;

    let mainDomain = window.location.hostname.split('.').slice(-2).join('.');

    const createDatePicker = (id) => {
        try {
            const selector = `#${id}`;
            const today = moment();
            const sevenDaysAgo = moment().subtract(0, 'days');
            $(selector).daterangepicker({
                autoUpdateInput: true,
                locale: {
                    cancelLabel: 'Clear'
                },
                // minDate: sevenDaysAgo, 
                maxDate: today,
                opens: 'left',
                startDate: sevenDaysAgo,
                endDate: today,
                // maxSpan: {
                //     days: 30
                // },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
                }
            });
            $(selector).on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                // $(`#drawID`).val('');
                // $(`#drawID`).val('').selectpicker('refresh');
            });
            $(selector).on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
        } catch (e) {
            console.log(`Error: ${e.message}`);
        }
    }
	
	
	
    $(document).ready(function () {
        cabList();
    });
    
    
    const cabList = () => {
      try {
          
           
            let startDate = null;
            let endDate = null;
            var btn = $(`#searchBTN`);
            var dateFilter = $('#datefilterLogin').val() ?? null;
            if (dateFilter != '' && dateFilter != null && dateFilter != undefined) {
                startDate = moment($('#datefilterLogin').data('daterangepicker').startDate).format("YYYY-MM-DD");
                endDate = moment($('#datefilterLogin').data('daterangepicker').endDate).format("YYYY-MM-DD");
            }
            participationTable = $('#Participation_List').DataTable({
                destroy: true,
                pageLength: 10,
                order: [[5, 'desc']],
                paging: true,
                searching: true,
                info: true,
                ajax: {
                    url: origin + "/ajax/service/userReport_service.php",
                    method: "POST",
                    dataSrc: "result",
                    data: function (d) {
                        const datePicker = $('#datefilterLogin').data('daterangepicker');
                        const hasDate = $('#datefilterLogin').val()?.trim() !== '';
                        const searchTxt = $('#searchTxt').val();
                        const search_filter = $('#search_filter').val();
                        const rmFilter = $('#rmFilter').val();
                        const agentsFilter = $('#agent_id').val();
                        // const fueltypeFilter = $('#tank_typeFilter').val();
                        
                        d.method = "userReport";
                        d.dateFilter = hasDate ? 1 : '';
                        d.searchTxt = searchTxt;
                        d.search_filter = search_filter;
                        d.rmFilter = rmFilter;
                        d.agentsFilter = agentsFilter;
                        
                        if (hasDate && datePicker) {
                            d.startDate = datePicker.startDate.format("YYYY-MM-DD");
                            d.endDate = datePicker.endDate.format("YYYY-MM-DD");
                        }
                    },
                    beforeSend: function () {
                        btn.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Loading...`).prop('disabled', true);
                    },
                },
                dom: 'Bfrtip',
                
                buttons: [
                    'pageLength',
                    'copy',
                    {
                        extend: 'excelHtml5',
                        title: 'Go Ride Leads'
                    },
                    
                ],
                
                columns: [

    {
        data: "created_at",
        defaultContent: ''
    },
    {
        data: "name",
        defaultContent: '-'
    },
    {
        data: "type",
        defaultContent: '-'
    },
    {
        data: "mobile",
        className: "text-center",
        defaultContent: '-'
    },
    {
        data: "email",
        className: "text-center",
        defaultContent: '-'
    },
    {
        data: "rm_status",
        className: "text-center",
        render: function (data, type, row) {

            let isActive = data == 1;
            let badgeClass = isActive ? 'bg-success' : 'bg-danger';
            let text = isActive ? 'Active' : 'Inactive';

            return `
                <span class="badge ${badgeClass}">
                    ${text}
                </span>
            `;
        }
    },
    {
        data: "matched_name",
        className: "text-center",
        defaultContent: '-'
    },
    {
        data: null,
        orderable: false,
        searchable: false,
        className: "text-center",
        render: function (data, type, row) {

            return `
                <button 
                    class="btn btn-sm btn-primary edit-btn" 
                    data-id="${row.id}"
                    data-userid="${row.user_id}"
                    title="Edit">
                    <i class="bi bi-pencil"></i>
                </button>

                ${row.rm_status == 1 ? `
                    <button 
                        class="btn btn-sm btn-danger delete-btn ms-2" 
                        data-id="${row.id}"
                        title="Delete">
                        <i class="bi bi-x-lg"></i>
                    </button>
                ` : ''}
            `;
        }
    }

],
                initComplete: function (settings, json) {
                    btn.html(`GO`).prop('disabled', false);
                },
            });
        } catch (e) {
            console.log(`Error: ${e.message}`);
        }
    }
    // const oldVerifiedList = () => {
    //   try {
    //         let startDate = null;
    //         let endDate = null;
    //         var btn = $(`#searchBTN`);
    //         var dateFilter = $('#datefilterLogin').val() ?? null;
    //         if (dateFilter != '' && dateFilter != null && dateFilter != undefined) {
    //             startDate = moment($('#datefilterLogin').data('daterangepicker').startDate).format("YYYY-MM-DD");
    //             endDate = moment($('#datefilterLogin').data('daterangepicker').endDate).format("YYYY-MM-DD");
    //         }
    //         var table = $('#Participation_List').DataTable({
    //             destroy: true,
    //             pageLength: 10,
    //             order: [[5, 'desc']],
    //             paging: true,
    //             searching: true,
    //             info: true,
    //             ajax: {
    //                 url: origin + "/ajax/service/cabServices.php",
    //                 method: "POST",
    //                 dataSrc: "result",
    //                 data: function (d) {
    //                     const datePicker = $('#datefilterLogin').data('daterangepicker');
    //                     const hasDate = $('#datefilterLogin').val()?.trim() !== '';
                    
    //                     d.method = "oldList";
    //                     d.dateFilter = hasDate ? 1 : '';
                    
    //                     if (hasDate && datePicker) {
    //                         d.startDate = datePicker.startDate.format("YYYY-MM-DD");
    //                         d.endDate = datePicker.endDate.format("YYYY-MM-DD");
    //                     }
    //                 },
    //                 beforeSend: function () {
    //                     btn.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Loading...`).prop('disabled', true);
    //                 },
    //             },
    //             dom: 'Bfrtip',
                
    //             buttons: [
    //                 'pageLength',
    //                 'copy',
    //                 {
    //                     extend: 'excelHtml5',
    //                     title: 'Go Ride Leads'
    //                 },
                    
    //             ],
                
    //             columns: [
    //                  {
    //                     data: null,
    //                     render: function (data, type, row, meta) {
    //                         return data?.created_at || '';
    //                     }
    //                 },
    //                 {
    //                     data: null,
    //                     render: function (data, type, row, meta) {
    //                         return data?.name || '';
    //                     }
    //                 },
    //                 {
    //                     data: null,
    //                     render: function (data, type, row, meta) {
    //                         return data?.car_no || '-';
    //                     }
    //                 },
    //                 {
    //                     data: "car_image",
    //                     render: function (data, type, row, meta) {

    //                         if (!data) {
    //                             return `<span class="text-muted">No Image</span>`;
    //                         }

    //                         return `
    //                             <img 
    //                                 src="${data}"
    //                                 class="img-thumbnail image-modal-trigger"
    //                                 data-userid="${row.id}"
    //                                 data-kdid="${row.kd_id}"
    //                                 data-img="${data}" 
    //                                 style="width:80px;height:60px;object-fit:cover;cursor:pointer;"
    //                             />
                                
                                
    //                         `;
    //                     }
    //                 },

    //                 {
    //                     data: null,
    //                     render: function (data, type, row, meta) {
    //                         return `<textarea readonly class="car_details"  style="cursor:pointer;"
    //                         >${data.car_details}</textarea>`
    //                     }
                       
    //                 },
                    
    //                 {
    //                   data: null,
    //                   render: function (data, type, row, meta) {
				// let selected = (val) => row.vehicle_type === val ? 'selected' : ' ';
    //                           return `
    //                             <select class="form-select form-select-md" style="width:140px" id="car_type"
    //                             onchange="cabTypes(this.value,${row.id})">
    //                                 <option value="">Select Model</option>
    //                                 <option value="Mini" ${selected('Mini')}>Go Mini</option>
    //                                 <option value="Prime_Sedan" ${selected('Prime_Sedan')}>Go Sedan</option>
    //                                 <option value="Prime_SUV" ${selected('Prime_SUV')}>Go SUV</option>
    //                                 <option value="Prime_SUV⁺" ${selected('Prime_SUV⁺')}>Go SUV⁺</option>
    //                                 <option value="Prime_Plus" ${selected('Prime_Plus')}>Go Plus</option>
    //                                 <option value="XL_Intercity" ${selected('XL_Intercity')}>Go XL Intercity</option>
    //                             </select>
    //                         `;
    //                     }
                    
    //                 },
                   
    //             ],
    //             initComplete: function (settings, json) {
    //                 btn.html(`GO`).prop('disabled', false);
    //             },
    //         });
    //     } catch (e) {
    //         console.log(`Error: ${e.message}`);
    //     }
    // }
    // const  newVerifiedList = () => {
    //   try {
    //         let startDate = null;
    //         let endDate = null;
    //         var btn = $(`#searchBTN`);
    //         var dateFilter = $('#datefilterLogin').val() ?? null;
    //         if (dateFilter != '' && dateFilter != null && dateFilter != undefined) {
    //             startDate = moment($('#datefilterLogin').data('daterangepicker').startDate).format("YYYY-MM-DD");
    //             endDate = moment($('#datefilterLogin').data('daterangepicker').endDate).format("YYYY-MM-DD");
    //         }
    //         var table = $('#Participation_List').DataTable({
    //             destroy: true,
    //             pageLength: 10,
    //             order: [[5, 'desc']],
    //             paging: true,
    //             searching: true,
    //             info: true,
    //             ajax: {
    //                 url: origin + "/ajax/service/cabServices.php",
    //                 method: "POST",
    //                 dataSrc: "result",
    //                 data: function (d) {
    //                     const datePicker = $('#datefilterLogin').data('daterangepicker');
    //                     const hasDate = $('#datefilterLogin').val()?.trim() !== '';
    //                     d.method = "cabList";
    //                     d.verifyType = verifyType;
    //                     d.dateFilter = hasDate ? 1 : '';
                    
    //                     if (hasDate && datePicker) {
    //                         d.startDate = datePicker.startDate.format("YYYY-MM-DD");
    //                         d.endDate = datePicker.endDate.format("YYYY-MM-DD");
    //                     }
    //                 },
    //                 beforeSend: function () {
    //                     btn.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Loading...`).prop('disabled', true);
    //                 },
    //             },
    //             dom: 'Bfrtip',
                
    //             buttons: [
    //                 'pageLength',
    //                 'copy',
    //                 {
    //                     extend: 'excelHtml5',
    //                     title: 'Go Ride Leads'
    //                 },
                    
    //             ],
                
    //             columns: [
    //                  {
    //                     data: null,
    //                     render: function (data, type, row, meta) {
    //                         return data?.created_at || '';
    //                     }
    //                 },
    //                 {
    //                     data: null,
    //                     render: function (data, type, row, meta) {
    //                         return data?.name || '';
    //                     }
    //                 },
    //                 {
    //                     data: null,
    //                     render: function (data, type, row, meta) {
    //                         return data?.car_no || '-';
    //                     }
    //                 },
    //                 {
    //                     data: "car_image",
    //                     render: function (data, type, row, meta) {

    //                         if (!data) {
    //                             return `<span class="text-muted">No Image</span>`;
    //                         }

    //                         return `
    //                             <img 
    //                                 src="${data}"
    //                                 class="img-thumbnail image-modal-trigger"
    //                                 data-userid="${row.id}"
    //                                 data-kdid="${row.kd_id}"
    //                                 data-img="${data}" 
    //                                 style="width:80px;height:60px;object-fit:cover;cursor:pointer;"
    //                             />
                                
                                
    //                         `;
    //                     }
    //                 },

    //                 {
    //                     data: null,
    //                     render: function (data, type, row, meta) {
    //                         return `<textarea readonly class="car_details"  style="cursor:pointer;"
    //                         >${data.car_details}</textarea>`
    //                     }
                       
    //                 },
                    
    //                 {
    //                   data: null,
    //                   render: function (data, type, row, meta) {
				// let selected = (val) => row.vehicle_type === val ? 'selected' : ' ';
    //                           return `
    //                             <select class="form-select form-select-md" style="width:140px" id="car_type"
    //                             onchange="cabTypes(this.value,${row.id})">
    //                                 <option value="">Select Model</option>
    //                                 <option value="Mini" ${selected('Mini')}>Go Mini</option>
    //                                 <option value="Prime_Sedan" ${selected('Prime_Sedan')}>Go Sedan</option>
    //                                 <option value="Prime_SUV" ${selected('Prime_SUV')}>Go SUV</option>
    //                                 <option value="Prime_SUV⁺" ${selected('Prime_SUV⁺')}>Go SUV⁺</option>
    //                                 <option value="Prime_Plus" ${selected('Prime_Plus')}>Go Plus</option>
    //                                 <option value="XL_Intercity" ${selected('XL_Intercity')}>Go XL Intercity</option>
    //                             </select>
    //                         `;
    //                     }
                    
    //                 },
                   
    //             ],
    //             initComplete: function (settings, json) {
    //                 btn.html(`GO`).prop('disabled', false);
    //             },
    //         });
    //     } catch (e) {
    //         console.log(`Error: ${e.message}`);
    //     }
    // }
    
    // $('#cab_typesFilter').on('change', function () {
    // console.log('Selected cab type:', $(this).val());
    // $('#Participation_List').DataTable().ajax.reload();
    // });
      
    $("#btnSearch").on("click", function () {
        $("#Participation_List").DataTable().ajax.reload();
    });
    
      $("#searchTxt").on("keypress", function (e) {
        if (e.which === 13) {
            $("#Participation_List").DataTable().ajax.reload();
        }
    });
    
    $('#searchTxt').on('keyup', function () {

    let value = $(this).val();

    if (value.length >= 3 || value.length === 0) {
        table.ajax.reload();
    }

    });


      $(function () {
        try {
            createDatePicker('datefilterLogin');
            $('#datefilterLogin').trigger('cancel.daterangepicker');
               
  		     cabList();
        } catch (e) {
            console.log(`Error: ${e.message}`);
        }
    });
    
  
    
    
  
    
    function toast(icon, message) {
       	const Toast = Swal.mixin({
       		toast: true,
       		position: 'top-end',
       		showConfirmButton: false,
       		timer: 5000,
       		timerProgressBar: true,
       		didOpen: (toast) => {
       			toast.addEventListener('mouseenter', Swal.stopTimer)
       			toast.addEventListener('mouseleave', Swal.resumeTimer)
       		}
       	})
       	Toast.fire({
       		icon: icon,
       		title: message
       	})
    }
    
</script>