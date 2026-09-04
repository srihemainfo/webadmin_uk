<?php


$pageTitle = "Past Participation List";

?>



<style>
    .card-header.d-lg-flex.d-block.justify-content-between {
        border-bottom: none;
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

    .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
        width: 100%;
    }
</style>


<script>
    window.onload = function() {



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
        <div class="main-container container-fluid mt-5 pt-0">
            <!-- PAGE-HEADER -->
            <div class="page-header pt-5">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a><?= $pageTitle; ?></h1>
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
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex1">
                                <div class="mt-2">
                                    <div class="row">



                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <span>Select Draw Type</span><span class="required-indicator">*</span>
                                            <select id="drawtype" class="form-select">
                                                <option value="">Select Draw Type</option>
                                                <option value="Daily_Thrill">Thrill Draw</option>
                                                <option value="Weekly_Raffle">Booster Draw</option>
                                                <option value="Month_Bumper">Bumper Draw</option>
                                            </select>

                                        </div>


                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <span>Select Draw</span><span class="required-indicator">*</span><br>
                                            <select id="draw_new_id" onchange="winnerList()" class="selectpicker" data-live-search="true">
                                                <option value="">Select Draw</option>
                                            </select>
                                        </div>


                                        <!-- <div class="col-sm-12 col-md-6 col-lg-4">
                                            <span>Search Participation</span>
                                            <input class="form-control" type="text" oninput="winnerList()" id="searchTxt" placeholder="Name / Email / Mobile No">
                                        </div> -->

                                        <div class="col-sm-12 col-md-6 col-lg-4"></div>
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

        <!-- ROW-1 END -->
        <!-- ROW-4 -->
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <!-- <div class="grid-margin">
                            <div class="panel panel-primary">
                                <div class="tab-menu-heading border-0 p-0">
                                    <div class="tabs-menu1">

                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body border-0 pt-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab1">
                                          
                                         
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap border-bottom" id="Participation_List" style="width:100%;">
                                <thead>
                                    <tr>

                                        <th class="wd-15p border-bottom-0">Full Name</th>
                                        <th class="wd-15p border-bottom-0">Email</th>
                                        <th class="wd-15p border-bottom-0">Mobile</th>
                                        <th class="wd-15p border-bottom-0">Raffle ID</th>
                                        <th class="wd-15p border-bottom-0">Ticket ID</th>
                                        <th class="wd-15p border-bottom-0">Grand Total</th>
                                        <th class="wd-15p border-bottom-0">Purchase Date and Time</th>
                                        <th class="wd-15p border-bottom-0">End Date</th>

                                        <th class="wd-15p border-bottom-0">Action</th>
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
        <!-- ROW-4 END -->
    </div>
    <!-- CONTAINER END -->
</div>

<script>
    var origin = window.location.origin;



    //   var url = origin + "/ajax/service/report_services.php";



    // var ajax_url = origin + "/ajax/service/result_services.php";

    // var drawType = $(".tabs-menu1 ul>li>a.active").attr('id');

    $(document).ready(function() {
        $('#drawtype').change(function() {
            var drawType = $(this).val();
            // alert('devanathan');
            $.ajax({
                url: origin + "/ajax/service/report_services.php",
                type: 'POST',
                data: {
                    method: 'participation_list_search',
                    drawType: drawType


                },
                success: function(data) {
                    $('#draw_new_id').empty();

                    var response = JSON.parse(data);
                    if (response != "") {
                        if (response.type == '1') {

                            $('#draw_new_id').append(`<option value="">Select Draw</option>`);

                            response.drawList.forEach(function(value, index) {
                                let drawName = value[(drawType === 'Daily_Thrill' ? 'dailyThrillName' : (drawType === 'Weekly_Raffle' ? 'weeklyBoosterName' : (drawType === 'Month_Bumper' ? 'monthlyBumperName' : '')))] || '';
                                let drawNo = value[(drawType === 'Daily_Thrill' ? 'dailyDrawNo' : (drawType === 'Weekly_Raffle' ? 'weeklyDrawNo' : (drawType === 'Month_Bumper' ? 'bumperDrawNo' : '')))] || '';

                                $('#draw_new_id').append('<option value="' + value.id + '">Draw No #' + String(drawNo).padStart(3, '0') + ' - ' + drawName.replace("Draw", "") + ' (' + moment(value.resultDate).format('DD MMMM YYYY') + ')</option>');
                            });


                            $('#draw_new_id').selectpicker('refresh');


                        } else {

                            toast('error', response.result);

                        }
                    }
                }
            });
        });
    });


    function winnerList() {
      

        let draw_new_id = $('#draw_new_id').val();
        let Monthly_draw_new_id = $('#Monthly_draw_new_id').val();
        let drawtype = $('#drawtype').val();

        let search_id1 = '';


        let filename = 'Participation List ' + $('#draw_new_id option:selected').text();







        var table = $('#Participation_List').DataTable({
            destroy: true,
            pageLength: 10,
            paging: true,
            searching: true,
            info: true,
            ajax: {
                url: origin + "/ajax/service/report_services.php",
                method: "POST",
                dataSrc: "userList",
                data: {
                    method: 'participation_list',
                    draw_id: draw_new_id, // Make sure draw_new_id is defined
                    drawtype: drawtype, // Make sure drawtype is defined
                    searchTxt: search_id1,
                }
            },
            order: [
                [7, 'desc'] // Assuming your date column is at index 6
            ],
            columnDefs: [{
                type: 'date',
                targets: [6] // Assuming the date column is at index 6
            }, {
                type: 'date',
                targets: [7] // Assuming the date column is at index 6
            }],
            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                {
                    extend: 'excelHtml5',
                    title: filename
                },
                'copy'

            ],
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.fullName;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.email;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.mobile;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.raffleid;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.ticketNo;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.grandtotal;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return moment(data.purchaseDatetime).format("DD MMM YYYY hh:mm a");
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return moment(data.endDate).format("DD MMM YYYY");

                    }
                },

                {
                    data: null,
                    render: function(data, type, row, meta) {
                        // return '';
                        return `<a target="_blank" href="<?= $baseurl; ?>ticket-view/${data.ticketReferenceID}" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span style="font-size: 18px;" class="fa fa-files-o"></span></a>&nbsp;<a target="_blank" href="<?= $baseurl; ?>invoice/${data.ticketReferenceID}" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span style="font-size: 18px;" class="fa fa-file-text-o"></span></a>`;
                    }
                },
            ],
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