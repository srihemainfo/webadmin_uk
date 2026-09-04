<?php
$pageTitle = 'Banner Upload';
?>


<style>
    span.fa.fa-trophy.fs-14 {
        color: #9d8711;
        font-size: 19px !important;
        font-weight: 900;
    }

    i.fa.fa-trash-o {
        font-size: 14px;
        font-weight: bolder;
        color: #e31212;
        cursor: pointer;
    }

    i.fa.fa-picture-o {
        font-size: 14px;
        font-weight: 900;
        color: #973d4e;
    }

    i.fa.fa-external-link {
        font-size: 14px;
        font-weight: 900;
        color: #448f66;
    }

    .preview {
        max-width: 100%;
        max-height: 200px;
        margin-bottom: 10px;
    }
</style>

<div class="main-content app-content mt-0">
    <div class="side-app">


        <div class="main-container container-fluid">

            <div class="page-header">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a><?= ucwords($pageTitle); ?></h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= ucwords($pageTitle); ?></li>
                    </ol>
                </div>
            </div>




            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-12">
                                </div>
                                <div class="row">

                                    <div class="col-lg-6 col-sm-6 col-md-6">
                                        <label for="startDate">Start & End Time (UAE):</label><i style="color: red;">*</i>
                                        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                            <i class="fa fa-calendar"></i>&nbsp;
                                            <span></span> <i class="fa fa-caret-down"></i>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-6 col-md-6">
                                        <label for="selectDevice">Device Type:</label><i style="color: red;">*</i>
                                        <select class="form-control" id="selectDevice">
                                            <option value="">Select Device</option>
                                            <option value="DESKTOP">DESKTOP</option>
                                            <option value="MOBILE">MOBILE</option>
                                        </select>
                                    </div>


                                    <!-- <div class="col-lg-4 col-sm-4 mt-5" style="display: none;" id="selectOptionContainer">
                                        <label for="linkInput" id="linkInputLabel1"> PAGE LINK:</label>
                                        <input type="text" class="form-control" id="pagelink">
                                    </div> -->

                                    <div class="col-lg-6 col-sm-6 col-md-6" style="display: none;" id="linkInputContainer">
                                        <div class="form-group">
                                            <label for="linkInput" id="linkInputLabel">Link:</label>
                                            <input type="text" class="form-control" id="linkInput">
                                        </div>
                                    </div>


                                    <div class="col-lg-6 col-sm-6 col-md-6">
                                        <label for="image" id="imageFileChoose">Choose Bannar Image 1MB:</label><i style="color: red;">*</i>
                                        <input type="file" class="form-control-file" id="image" accept="image/*" onchange="previewImage()">

                                        <div class="preview mt-4">
                                            <img id="imagePreview" alt="Image Preview" style="display:none">
                                            <p id="uploadTime"></p>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-6 col-md-6 mt-5">
                                        <button type="button" class="btn btn-success" onclick="move_new_file()" data-bs-dismiss="modal">Submit</button>
                                    </div>



                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- OLD  -->
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">



                        <div class="card-body">
                            <div class="tab-menu-heading border-0 p-0">

                                <div class="tabs-menu1">

                                    <!-- Tabs -->
                                    <ul class="nav panel-tabs ">
                                        <li><a href="#tab1" id="aemailLog" class="text-dark active" data-bs-toggle="tab" onclick=" WorkList()">Web Banner</a></li>
                                        <li><a href="#tab2" id="lemailLog" class="text-dark" data-bs-toggle="tab" onclick=" WorkList()">Mobile Banner</a></li>
                                    </ul>

                                </div>

                            </div>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <div class="table-responsive mt-5">
                                        <table class="table table-bordered text-nowrap border-bottom dataTable" id="aemailLog1" style="width:100%;">

                                            <thead>
                                                <tr>

                                                    <th class="wd-15p border-bottom-0">S No</th>
                                                    <th class="wd-15p border-bottom-0">Image</th>
                                                    <th class="wd-15p border-bottom-0">Status</th>
                                                    <th class="wd-15p border-bottom-0">Device Type</th>
                                                    <th class="wd-15p border-bottom-0">Start Date</th>
                                                    <th class="wd-15p border-bottom-0">End Date</th>
                                                    <th class="wd-15p border-bottom-0">Page Url</th>
                                                    <th class="wd-15p border-bottom-0">Action</th>
                                                </tr>
                                            </thead>


                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab2">
                                    <div class="table-responsive mt-5">
                                        <table class="table table-bordered text-nowrap border-bottom dataTable" id="lemailLog1" style="width:100%;">

                                            <thead>
                                                <tr>
                                                    <th class="wd-15p border-bottom-0">S No</th>
                                                    <th class="wd-15p border-bottom-0">Image</th>
                                                    <th class="wd-15p border-bottom-0">Status</th>
                                                    <th class="wd-15p border-bottom-0">Device Type</th>
                                                    <th class="wd-15p border-bottom-0">Start Date</th>
                                                    <th class="wd-15p border-bottom-0">End Date</th>
                                                    <th class="wd-15p border-bottom-0">Page Url</th>
                                                    <th class="wd-15p border-bottom-0">Action</th>

                                                </tr>
                                            </thead>


                                        </table>
                                    </div>
                                </div>

                            </div>

                            <br> <br>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>


<script>
    var origin = window.location.origin;
    let assetURL = '<?= constant('assetURL') ?>';
    var url = origin + "/ajax/service/report_services.php";
    var ajax_url = origin + "/ajax/service/sitebanner_services.php";
    var drawType = $(".tabs-menu1 ul>li>a.active").attr('id');


    $(document).ready(function() {

        // Event listener for changes in the Device Type dropdown
        $('#selectDevice').on('change', function() {
            // Get the selected value
            var selectedDevice = $(this).val();

            $(`#imageFileChoose`).text((selectedDevice === 'DESKTOP') ? `Choose Banner Image (1635*446) 1MB` : `Choose Banner Image (940*489) 1MB`);

            // Show or hide the Select Option dropdown based on the selected value
            if (selectedDevice === 'DESKTOP' || selectedDevice === 'MOBILE') {
                $('#selectOptionContainer').show();
            } else {
                $('#selectOptionContainer').hide();
            }
        });

        // Event listener for changes in the Select Option dropdown
        $('#selectDevice').on('change', function() {
            // Get the selected value
            var selectDevice = $(this).val();

            // Update the label text based on the selected value
            if (selectDevice === 'DESKTOP') {
                $('#linkInputLabel').text('DESKTOP URL (Redirect):');
            } else if (selectDevice === 'MOBILE') {
                $('#linkInputLabel').text('MOBILE URL (Redirect):');
            }

            // Show or hide the linkInputContainer based on the selected value
            if (selectDevice === 'DESKTOP' || selectDevice === 'MOBILE') {
                $('#linkInputContainer').show();
            } else {
                $('#linkInputContainer').hide();
            }
        });



        WorkList();

        createDatePricket1('reportrange');


        // $(".faq_tbody").sortable({
        //     delay: 150,
        //     stop: function() {
        //         var selectedData = new Array();;
        //         $('.faq_tbody>tr').each(function() {
        //             selectedData.push($(this).data("id"));
        //         });
        //         updateOrder(selectedData);
        //     }
        // });

        // function updateOrder(data) {
        //     $.ajax({
        //         url: origin + "/ajax/service/faq_services.php",
        //         type: 'post',
        //         data: {
        //             position: data,
        //             method: 'update_orderno'
        //         },
        //         success: function() {
        //             paymentSuccess('success', "Order Updated Sucessfully.");
        //             searchTicket();
        //         }
        //     })
        // }









    });

    function openPopup() {
        document.getElementById('addagent1').style.display = 'block';
    }

    function closePopup() {
        document.getElementById('addagent1').style.display = 'none';
        // Clear input fields and image preview on close
        document.getElementById('image').value = '';
        document.getElementById('imagePreview').src = '';
        document.getElementById('startDate').value = '';
        document.getElementById('endDate').value = '';
    }

    function previewImage() {
        var input = document.getElementById('image');
        var preview = document.getElementById('imagePreview');
        var uploadTime = document.getElementById('uploadTime');

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block'; // Show the image preview

                // Display current date and time
                // var currentDate = new Date();
                // var formattedDate = currentDate.toLocaleString();
                // uploadTime.textContent = 'Upload Time: ' + formattedDate;
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.style.display = 'none'; // Hide the image preview
            uploadTime.textContent = '';
        }
    }

    function cleartext() {
        // Reset values
        document.getElementById('image').value = '';
        document.getElementById('imagePreview').src = '';
        document.getElementById('selectDevice').value = '';
        document.getElementById('linkInput').value = '';
        document.getElementById('pagelink').value = '';
        document.getElementById('reportrange').value = '';
        document.getElementById('endDate').value = '';

        // Hide link input containers by default
        var linkInputContainer = document.getElementById('linkInputContainer');
        var selectOptionContainer = document.getElementById('selectOptionContainer');
        linkInputContainer.style.display = 'none';
        selectOptionContainer.style.display = 'none';

        // Optionally, you can add logic to show/hide containers based on selectedDevice
        var selectedDevice = document.getElementById('selectDevice').value;
        if (selectedDevice === 'MOBILE') {
            // Show the containers or perform any other actions based on the selected device
            linkInputContainer.style.display = 'block';
            selectOptionContainer.style.display = 'block';
        }

        // Reset image preview
        var preview = document.getElementById('imagePreview');
        preview.src = '';
        preview.style.display = 'none';
    }

    function move_new_file() {

        // const startDate = document.getElementById('startDate').value;
        var Device = document.getElementById('selectDevice').value;
        var image1 = document.getElementById('image').value;


        if (Device != '') {
            if (image1 != '') {


                var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
                var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");




                let file = document.getElementById('image').value;

                var formData = new FormData();

                formData.append('method', 'site_banner');

                formData.append('linkInput', $('#linkInput').val());

                formData.append('pagelink', $('#pagelink').val());

                formData.append('selectDevice', $('#selectDevice').val());

                formData.append('startDate', formdate);
                formData.append('endDate', todate);

                formData.append('mfile', image.files[0]);

                var maxSize = 1 * 1024 * 1024; // 1MB in bytes
                if (image && image.files[0]['size'] > maxSize) {
                    toast('error', 'File size exceeds 1MB. Please choose a smaller file.');
                    return false;
                }

                $.ajax({

                    url: ajax_url,

                    type: 'post',

                    data: formData,

                    success: function(response) {

                        // var response = JSON.parse(data);

                        if (response != "") {

                            if (response.type == 1) {

                                $('#uploadimg').modal('hide');

                                toast('success', 'Image Uploaded Successfully');


                                WorkList();
                                // cleartext();

                                setTimeout(location.reload(), 5000);



                            } else if (response.type == 0) {

                                toast('error', 'Please Fill The Reason!');

                            }

                        }

                    },

                    processData: false,
                    contentType: false
                });

            } else {

                toast('error', 'Please select the image!');

            }

        } else {

            toast('error', 'Please select the Device!');

        }



        // closePopup();

    }

    function updateStatus(button) {
        var id = $(button).data('id');
        var status = $(button).data('status');
        // var ordeer_by = $(button).data('ordeer_by');
        var h = new FormData();
        h.append('method', 'statusbutton');
        h.append('id', id);
        h.append('status', status);

        // Perform an AJAX request to update the status
        $.ajax({
            type: 'POST',
            url: origin + "/ajax/service/home_banner_service.php", // Replace with the actual server-side script
            data: h,


            success: function(response) {
                // Handle the response from the server, if needed
                // toast('success', 'Activated Successfully');

                // console.log(response);
                // if (status === 'active') {
                //     toast('success', 'Deactivated Successfully');
                // } else {
                //     toast('success', 'Activated Successfully');
                // }
                var response = JSON.parse(response);
                WorkList();
                if (response != "") {

                    if (response.type == 1) {
                        toast('success', response.result);
                    } else if (response.type == 0) {
                        toast('error', response.result);
                    }

                }

                // Assuming you want to toggle the status
                $(button).data('status', (status === 'active' ? 'Inactive' : 'Active'));
            },
            processData: false,
            contentType: false,
            error: function(error) {
                console.error(error);
            }
        });
    }

    function deleteinfo(id) {

        if (id == '' || id == null) {
            toast('error', 'The Image ID has been missing!');
            return false;
        }

        Swal.fire({

            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
            allowOutsideClick: false

        }).then((result) => {

            if (result.isConfirmed) {


                var h = new FormData();
                h.append('method', 'deleteinfo');
                h.append('id', id);


                $.ajax({
                    type: 'POST',
                    url: origin + "/ajax/service/home_banner_service.php", // Replace with the actual server-side script
                    data: h,
                    success: function(response) {

                        // toast('success', 'The Image deleted successfully');
                        var response = JSON.parse(response);
                        WorkList();
                        if (response != "") {

                            if (response.type == 1) {
                                toast('success', response.result);
                            } else if (response.type == 0) {
                                toast('error', response.result);
                            }

                        }


                        // console.log('Record deleted successfully');

                    },
                    processData: false,
                    contentType: false,
                    error: function(error) {

                        console.error('Error deleting record:', error);
                    }
                });

            }

        })

        // if (confirm('Are you sure you want to delete this Image?')) {


        // }
    }



    function updateOrder(id, order, deviceType) {

        if (id == '' || id == null) {
            toast('error', 'The Image ID has been missing!');
            return false;
        }

        if (order == '' || order == null) {
            toast('error', 'The Order has been missing!');
            return false;
        }

        if (deviceType == '' || deviceType == null) {
            toast('error', 'The Device Type has been missing!');
            return false;
        }

        Swal.fire({

            title: "Are you sure?",
            text: "Do you want to update the order!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, update it!",
            allowOutsideClick: false

        }).then((result) => {

            if (result.isConfirmed) {


                var h = new FormData();
                h.append('method', 'updateOrder');
                h.append('id', id);
                h.append('order', order);
                h.append('deviceType', deviceType);

                $.ajax({
                    type: 'POST',
                    url: origin + "/ajax/service/home_banner_service.php", // Replace with the actual server-side script
                    data: h,
                    success: function(response) {


                        var response = JSON.parse(response);
                        WorkList();

                        if (response != "") {

                            if (response.type == 1) {
                                toast('success', response.result);
                            } else if (response.type == 0) {
                                toast('error', response.result);
                            }

                        }




                    },
                    processData: false,
                    contentType: false,
                    error: function(error) {

                        console.error('Error deleting record:', error);
                    }
                });

            }

        })


    }

    function searchByOrdeer(button) {
        var id = $(button).data('id');
        var inputValue = $(button).prev('.ordeer-by-input').val(); // Get the input value

        // Make an AJAX request to update the database using jQuery
        $.ajax({
            url: origin + "/ajax/service/home_banner_service.php",
            method: 'POST',
            data: {
                id: id,
                method: 'seachbutton',
                ordeer_by: inputValue,
            },
            dataType: 'json', // Specify the expected data type
            success: function(data) {
                // Check the response type
                if (data.type === "0") {
                    // Handle error case
                    toast('error', data.result);
                } else if (data.type === "1") {
                    // Handle success case
                    toast('success', data.result);
                    WorkList();
                } else {
                    console.error('Invalid response type');
                }
                console.log(data);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    }

    function toast(icon, message) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        Toast.fire({
            icon: icon,
            title: message
        });

    }

    function createDatePricket1(id) {

        var start = moment();
        var end = moment();

        function cb(start, end) {
            $('#' + id + ' span').html(start.format('MMM Do YY hh:mm a') + ' - ' + end.format('MMM Do YY hh:mm a'));
        }

        $('#' + id).daterangepicker({
            startDate: start.set({
                hour: 0,
                minute: 0,
                second: 0,
                millisecond: 0
            }),
            endDate: end.set({
                hour: 23,
                minute: 59,
                second: 59,
                millisecond: 59
            }),
            minDate: moment().format("MM/DD/YYYY"),
            timePicker: true,
            timePicker24Hour: true,
            timePickerSeconds: true,
            maxSpan: {
                days: 364
            },
            autoUpdateInput: true,
            showDropdowns: true,
            minYear: moment().format("YYYY"),
            maxYear: moment().add(1, 'years').format("YYYY"),
            ranges: {


                'This Month': [moment().startOf('month').set({
                    hour: 0,
                    minute: 0,
                    second: 0,
                    millisecond: 0
                }), moment().endOf('month').set({
                    hour: 23,
                    minute: 59,
                    second: 59,
                    millisecond: 59
                })],
                'No Expiry': [moment().set({
                    hour: 0,
                    minute: 0,
                    second: 0,
                    millisecond: 0
                }), moment().add(7, 'years').set({
                    hour: 23,
                    minute: 59,
                    second: 59,
                    millisecond: 59
                })]


            }

        }, cb);

        cb(start, end);



    }

    // $(function() {


    //     // var start = moment();

    //     // var end = moment();



    //     // function cb(start, end) {
    //     //     $('#reportrange  span').html(start.format("YYYY-MM-DD HH:mm:ss") + ' - ' + end.format("YYYY-MM-DD HH:mm:ss"));
    //     // }

    //     // $('#reportrange').daterangepicker({
    //     //     startDate: start.set({
    //     //         hour: 1,
    //     //         minute: 1,
    //     //         second: 1,
    //     //         millisecond: 0
    //     //     }),
    //     //     endDate: end.set({
    //     //         hour: 23,
    //     //         minute: 59,
    //     //         second: 59,
    //     //         millisecond: 59
    //     //     }),
    //     //     minDate: moment().format("MM/DD/YYYY"),
    //     //     timePicker: true,
    //     //     timePicker24Hour: true,
    //     //     timePickerSeconds: true,
    //     //     maxSpan: {
    //     //         days: 364
    //     //     },
    //     //     autoUpdateInput: true,
    //     //     showDropdowns: true,
    //     //     minYear: moment().format("YYYY"),
    //     //     maxYear: moment().add(1, 'years').format("YYYY"),
    //     //     ranges: {


    //     //         'This Month': [moment().startOf('month'), moment().endOf('month')],
    //     //         'No Expiry': [moment(), moment().add(3, 'years')]


    //     //     }

    //     // }, cb);

    //     // cb(start, end);



    //     // var start = moment().subtract(29, 'days');
    //     // var end = moment();

    //     // function cb(start, end) {
    //     //     $('#reportrange span').html(start.format('YYYY-MM-DD HH:mm:ss') + ' - ' + end.format(
    //     //         'YYYY-MM-DD HH:mm:ss'));
    //     // }

    //     // function createDatePricket(id) {
    //     //     $('#' + id).daterangepicker({
    //     //         startDate: start,
    //     //         endDate: end,
    //     //         autoApply: true,
    //     //         showDropdowns: true, // Show year and month dropdowns
    //     //         ranges: {
    //     //             'This Month': [moment().startOf('month'), moment().endOf('month')],
    //     //             // 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
    //     //             'No Expiry': [moment(), moment().add(3, 'years')]
    //     //         },
    //     //         locale: {
    //     //             format: 'YYYY-MM-DD HH:mm:ss'
    //     //         }
    //     //     }, cb);

    //     //     cb(start, end);


    //     // }

    // });

    function WorkList() {
        try {


            var title = 'Banner List';
            let tableID = $('ul li a.active').attr('id') === 'aemailLog' ? 'aemailLog1' : 'lemailLog1';
            let work_with;



            var table = $('#' + tableID).DataTable({
                destroy: true,
                pageLength: 10,

                order: [
                    [0, 'asc'],
                ],

                // columnDefs: [{
                //     type: 'date',
                //     targets: [0]
                // }],



                paging: true,
                searching: true,
                info: true,

                ajax: {
                    url: origin + "/ajax/service/home_banner_service.php",
                    method: "POST",
                    dataSrc: "",
                    data: {
                        method: 'home_banner',

                        banner: $('ul li a.active').attr('id') === 'aemailLog' ? 'DESKTOP' : 'MOBILE'
                    },
                },


                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            // Use meta.row to get the row index, and add 1 to start from 1
                            return `<input type="text" class="form-control" value="${data.order_by}" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="10" onfocusout="updateOrder(${data.id}, $(this).val(), '${data.device_type}')">`;
                        }
                    },


                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return (data.path != undefined && data.path != null && data.path != '') ? `<a href = "${assetURL + data.path}" target = "_blank" style = "color: #15e815;"><i class="fa fa-picture-o" aria-hidden="true">&nbsp;&nbsp;View Image</i></a>` : ``;
                        }
                    },

                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            const dubaiTime = new Date().toLocaleString('en-US', {
                                timeZone: 'Asia/Dubai'
                            });
                            const startDate = new Date(data.start_date).getTime();
                            const endDate = new Date(data.end_date).getTime();
                            const currentDate = new Date(dubaiTime).getTime();

                            return (data.status == 'Active') ? ((currentDate >= startDate && currentDate <= endDate) ? `<span style="color: green;font-weight: bolder;">Active<\span>` : `<span style="color: red;font-weight: bolder;">Inactive<\span>`) : `<span style="color: red;font-weight: bolder;">${data.status}<\span>`;
                        }
                    },

                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return data.device_type;
                        }
                    },


                    {
                        data: null,
                        render: function(data, type, row, meta) {

                            var inputField = `<input type="text" class="form-control" name="resultdatefilter" id="${'startTime'+data.id}" value="${data.start_date}" />`;
                            generateDrawPicker('startTime' + data.id, moment(data.start_date).format("YYYY-MM-DD HH:mm:ss"));


                            $('input[name="resultdatefilter"]').on('apply.daterangepicker', function(ev, picker) {
                                var id = $(this).attr('id');
                                var newTime = moment($('#' + id).data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");

                                if (id == '') {
                                    toast('error', 'Kindly Refresh and Try Again!');
                                    return false;
                                }

                                if (newTime == '') {
                                    toast('error', 'Kindly Refresh and Try Again!');
                                    return false;
                                }

                                Swal.fire({

                                    title: "Are you sure?",
                                    text: "Do you want to update the time!",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#3085d6",
                                    cancelButtonColor: "#d33",
                                    confirmButtonText: "Yes, update it!",
                                    allowOutsideClick: false

                                }).then((result) => {

                                    if (result.isConfirmed) {
                                        var formdata = [];
                                        formdata.push({
                                            name: 'method',
                                            value: "changeTimeBannar"
                                        }, {
                                            name: 'id',
                                            value: id.toString().replace("startTime", '').trim()
                                        }, {
                                            name: 'startTime',
                                            value: newTime
                                        });


                                        var post_data = formdata;
                                        var onsuccess = function(response) {
                                            var response = JSON.parse(response);
                                            // WorkList();

                                            if (response != "") {

                                                if (response.type == 1) {
                                                    toast('success', response.result);
                                                } else if (response.type == 0) {
                                                    toast('error', response.result);
                                                }

                                            }
                                        }

                                        do_ajax_call(post_data, onsuccess, origin + "/ajax/service/home_banner_service.php");
                                    }
                                });
                            });

                            return inputField;
                            // moment(data.start_date).format("DD MMM YYYY hh:mm a")

                        }
                    },

                    // {
                    //     data: null,
                    //     render: function(data, type, row, meta) {
                    //         return moment(data.end_date).format("DD MMM YYYY hh:mm a")
                    //     }
                    // },

                    {
                        data: null,
                        render: function(data, type, row, meta) {

                            var inputField = `<input type="text" class="form-control" name="enddatefilter" id="${'endTime'+data.id}" value="${data.end_date}" />`;
                            generateDrawPicker('endTime' + data.id, moment(data.end_date).format("YYYY-MM-DD HH:mm:ss"));


                            $('input[name="enddatefilter"]').on('apply.daterangepicker', function(ev, picker) {
                                var id = $(this).attr('id');
                                var newTime = moment($('#' + id).data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");

                                if (id == '') {
                                    toast('error', 'Kindly Refresh and Try Again!');
                                    return false;
                                }

                                if (newTime == '') {
                                    toast('error', 'Kindly Refresh and Try Again!');
                                    return false;
                                }

                                Swal.fire({

                                    title: "Are you sure?",
                                    text: "Do you want to update the time!",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#3085d6",
                                    cancelButtonColor: "#d33",
                                    confirmButtonText: "Yes, update it!",
                                    allowOutsideClick: false

                                }).then((result) => {

                                    if (result.isConfirmed) {
                                        var formdata = [];
                                        formdata.push({
                                            name: 'method',
                                            value: "changeTimeBannar"
                                        }, {
                                            name: 'id',
                                            value: id.toString().replace("endTime", '').trim()
                                        }, {
                                            name: 'endTime',
                                            value: newTime
                                        });


                                        var post_data = formdata;
                                        var onsuccess = function(response) {
                                            var response = JSON.parse(response);
                                            // WorkList();

                                            if (response != "") {

                                                if (response.type == 1) {
                                                    toast('success', response.result);
                                                } else if (response.type == 0) {
                                                    toast('error', response.result);
                                                }

                                            }
                                        }

                                        do_ajax_call(post_data, onsuccess, origin + "/ajax/service/home_banner_service.php");
                                    }
                                });
                            });

                            return inputField;
                            // moment(data.start_date).format("DD MMM YYYY hh:mm a")

                        }
                    },

                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return (data.url_link != undefined && data.url_link != null && data.url_link != '') ? `<a href = "${data.url_link}" target = "_blank" style = "color: #15e815;"><i class="fa fa-external-link" aria-hidden="true">&nbsp;&nbsp;Redirect</i></a>` : ``;
                        }
                    },

                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return `<button class='btn custom-button-style view-button' data-id='${data.id}' data-status='${data.status}' style=' background-color: unset;font-weight: bolder;${(data.status == 'Active') ? 'color: red;' : 'color: green;'}' onclick='updateStatus(this)'>${(data.status == 'Active') ? 'Deactivate' : 'Activate'}</button>&nbsp;&nbsp;<a  target = "_blank" style = "color: #15e815;" onclick='deleteinfo(${data.id})'><i class="fa fa-trash-o" aria-hidden="true"></i></a>`;
                        }
                    }

                ],
            });


        } catch (e) {
            console.log('Error: ' + e.message);
        }
    }

    function generateDrawPicker(id, startd) {
        var start = moment(startd);
        // var mindate = moment(minDay);

        function cb(start) {
            // $('#' + id).val(start.format("YYYY-MM-DD HH:mm:ss"));
            $('#' + id).val(start.format("DD MMM YYYY hh:mm a"));

        }

        $('#' + id).daterangepicker({
            startDate: start,
            // endDate: end,
            // minDate: moment().format("MM/DD/YYYY"),
            singleDatePicker: true,
            autoApply: true,
            minDate: moment().format("MM/DD/YYYY"),
            maxDate: moment().add(7, 'years').format("YYYY"),
            timePicker: true,
            timePicker24Hour: true,
            timePickerSeconds: true,
            // maxSpan: {
            //     days: 89
            // },
            autoUpdateInput: false,
        }, cb);

        cb(start);
    }
</script>