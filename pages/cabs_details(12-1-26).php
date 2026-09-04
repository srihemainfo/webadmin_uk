<?php
$pageTitle = "Cabs";
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
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                    <div class="card ">
                        <div class="card-body">
                            <div class="d-flex1">
                                <div class="row mt-1">

                                    <!-- Date -->
                                    <div class="col-md-2">
                                        <span>Date</span>
                                        <input class="form-control" type="text" id="datefilterLogin"
                                               name="datefilterLogin" placeholder="Select Date" value="" readonly />
                                    </div>
                                
                                    <!-- Search Button -->
                                    <div class="col-md-2">
                                        <span class="d-block">&nbsp;</span>
                                        <button class="btn btn-primary" onclick="jobList()">Search</button>
                                    </div>
                                
                                </div>

                                <div class="row">
                                    <div class="col-4" id="searcherr">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-4">
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
                                                            <th class="column_sort sorting sorting_asc">Name</th>
                                                            <th class="column_sort sorting sorting_asc">Car No</th>
                                                            <th class="column_sort sorting sorting_asc">Car Image</th>
                                                            <th class="column_sort sorting sorting_asc">Car Details</th>
                                                            <th class="column_sort sorting sorting_asc">Action</th>
                                                            <!-- <th class="column_sort sorting sorting_asc">Type</th>
                                                            <th class="column_sort sorting sorting_asc">From</th>
                                                            <th class="column_sort sorting sorting_asc">To</th>
                                                            <th class="column_sort sorting sorting_asc">Pickup Date</th>
                                                            <th class="column_sort sorting sorting_asc">Dropoff Date</th>
                                                            <th class="column_sort sorting sorting_asc">Dis / PC</th>
                                                            <th class="column_sort sorting sorting_asc">Fare</th>
                                                            <th class="column_sort sorting sorting_asc">Created At</th>
                                                            <th class="column_sort sorting sorting_asc">Action</th> -->
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


<div class="modal fade" id="imageViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">
                <h5 class="modal-title">Car Image</h5>
                <span class="close-car-modal">&times;</span>
            </div>

            <!-- BODY -->
            <div class="modal-body p-0">
                <div class="image-box">
                    <img id="modalCarImage"
                         src=""
                         class="modal-car-img" />
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="carDetailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title">Car Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <div id="carDetailsContent" class="car-details-grid"></div>
      </div>

      <!-- Footer (optional) -->
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

// <script>
//     tinymce.init({
//         selector: '#whatsapp_message',
//         menubar: false,
//         plugins: 'lists link',
//         toolbar: 'undo redo | bold italic underline | bullist numlist | link',
//         height: 200
//     });
// </script>
<script>

    $(document).on("click", ".image-modal-trigger", function () {
        $("#modalCarImage").attr("src", $(this).data("img"));
        $("#imageViewModal").modal("show");
    });

   $(document).on("click", ".car_details", function () {
    let value = $(this).val();

    try {
        let obj = typeof value === "string" ? JSON.parse(value) : value;

        // Format keys on left side only
        let formatted = Object.entries(obj)
            .map(([k, v]) => {
                let key = k.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
                return `<span class="car-key">${key}:</span> ${v ?? 'null'}`;
            })
            .join('<br>');

        $("#carDetailsContent").html(formatted);
    } catch (e) {
        $("#carDetailsContent").text(value);
    }

    // Show modal using Bootstrap
    var modal = new bootstrap.Modal(document.getElementById('carDetailsModal'));
    modal.show();
});


  $(document).on("click", ".close-car-modal", function () {
    var modal = bootstrap.Modal.getInstance(document.getElementById('carDetailsModal'));
    if (modal) {
        modal.hide(); 
    }
    });

    $(document).on("click", ".close-car-modal", function () {
    var modal = bootstrap.Modal.getInstance(document.getElementById('imageViewModal'));
    if (modal) {
        modal.hide(); 
    }
    });

    // $(document).on("click", "#carDetailsModal", function (e) {
    // if (e.target.id === "carDetailsModal") {
    //     $("#carDetailsModal").fadeOut(150);
    // }
    // });

    function cabTypes(carType, id) {

        if (!carType) return;

        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to change the car type?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No"
        }).then((result) => {

            if (!result.isConfirmed) return;

            $.ajax({
                url: origin + "/ajax/service/cabServices.php",
                type: "POST",
                data: { 
                    id: id, 
                    car_type: carType,
                    method: "car_types"
                },
                success: function (res) {
                    console.log(res);
                }
            });

        });
}



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
            var btn = $('#searchBTN');
            // let startDate = null;
            // let endDate = null;
            // var btn = $(`#searchBTN`);
            // var dateFilter = $('#datefilterLogin').val() ?? null;
            // if (dateFilter != '' && dateFilter != null && dateFilter != undefined) {
            //     startDate = moment($('#datefilterLogin').data('daterangepicker').startDate).format("YYYY-MM-DD");
            //     endDate = moment($('#datefilterLogin').data('daterangepicker').endDate).format("YYYY-MM-DD");
            // }
            var table = $('#Participation_List').DataTable({
                destroy: true,
                pageLength: 10,
                order: [],
                paging: true,
                searching: true,
                info: true,
                ajax: {
                    url: origin + "/ajax/service/cabServices.php",
                    method: "POST",
                    dataSrc: "result",
                    data: function (d) {
                        const datePicker = $('#datefilterLogin').data('daterangepicker');
                        const hasDate = $('#datefilterLogin').val()?.trim() !== '';
                    
                        d.method = "cabList";
                        d.jobType = $("#jobType").val();
                        d.job_no = $("#job_no").val();
                        d.jobStatus = $("#jobStatus").val();
                        d.dateFilter = hasDate ? 1 : '';
                    
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
                        data: null,
                        render: function (data, type, row, meta) {
                            return data?.name || '';
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return data?.car_no || '';
                        }
                    },
                    {
                        data: "car_image",
                        render: function (data) {

                            if (!data) {
                                return `<span class="text-muted">No Image</span>`;
                            }

                            return `
                                <img 
                                    src="${data}"
                                    class="img-thumbnail image-modal-trigger"
                                    data-img="${data}"
                                    style="width:80px;height:60px;object-fit:cover;cursor:pointer;"
                                />
                            `;
                        }
                    },

                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return `<textarea readonly class="car_details"  style="cursor:pointer;"
                            >${data.car_details}</textarea>`
                        }
                       
                    },
                    
                    {
                       data: null,
                       render: function (data, type, row, meta) {
                              return `
                                <select class="form-select form-select-md" style="width:140px" id="car_type"
                                onchange="cabTypes(this.value,${row.id})">
                                    <option value="">Select Model</option>
                                    <option value="Mini">Mini</option>
                                    <option value="Prime_Sedan">Prime Sedan</option>
                                    <option value="Prime_SUV">Prime SUV</option>
                                    <option value="Prime_SUV⁺">Prime SUV⁺</option>
                                    <option value="Prime_Plus">Prime Plus</option>
                                    <option value="XL_Intercity">XL Intercity</option>
                                </select>
                            `;
                        }
                    
                    },
                   
                ],
                initComplete: function (settings, json) {
                    btn.html(`GO`).prop('disabled', false);
                },
            });
        } catch (e) {
            console.log(`Error: ${e.message}`);
        }
    }
    
    // $("#btnSearch").on("click", function () {
    //     $("#Participation_List").DataTable().ajax.reload();
    // });
    
    // $("#searchTxt").on("keypress", function (e) {
    //     if (e.which === 13) {
    //         $("#Participation_List").DataTable().ajax.reload();
    //     }
    // });
    
  
    
    
  
    
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