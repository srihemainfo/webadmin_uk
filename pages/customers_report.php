<?php

/*

1.modifications unknown


Date      Developer_name      Modifications


*/




$pageTitle = "Draw Comparison Report";



?>





<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

<style>
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

        <div class="main-container container-fluid">



            <!-- PAGE-HEADER -->

            <div class="page-header">

                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a><?= ucwords($pageTitle); ?></h1>

                <div>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page"><?= ucwords($pageTitle); ?></li>

                    </ol>

                </div>

            </div>

            <!-- PAGE-HEADER END -->



            <!-- ROW-1 -->

            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">

                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">

                            <div class="card">

                                <div class="card-body">

                                    <div class="d-flex">

                                        <div class="mt-2">

                                            <div class="row">



                                                <div class="col-md-4">

                                                    <span>Select Draw</span>&nbsp;<span style="color: red;">*</span>

                                                    <select id="draw_new_id" onchange="draw_not_id()" class="selectpicker" multiple aria-label="Default select example" data-live-search="true">

                                                        <option value="" disabled>Select Draw</option>

                                                        <?php

                                                        $draw = select_query($con, "draw", "", "`status` = 'Completed' and `deletes`='0' ORDER BY `id` DESC", "", "");

                                                        if ($draw['nr'] > 0) {

                                                            foreach ($draw['result'] as $key => $value) {

                                                        ?>

                                                                <option value="<?= $value['id']; ?>"><?= $value['name']; ?></option>

                                                        <?php



                                                            }
                                                        }

                                                        ?>

                                                    </select>

                                                </div>



                                                <div class="col-md-4">

                                                    <span>Select Draw</span>&nbsp;<span style="color: red;">*</span>

                                                    <select id="draw_not_id" class="selectpicker" multiple aria-label="Default select example" data-live-search="true">

                                                        <option value="" disabled>Select Draw</option>

                                                    </select>

                                                </div>



                                                <div class="col-md-4">

                                                    <span>Select Ticket</span>&nbsp;<span style="color: red;">*</span>

                                                    <select id="ticket_name" class="form-select">

                                                        <option value="">Select Ticket</option>

                                                        <option value="MT">Manual Ticket</option>

                                                        <option value="OT">Online Ticket</option>

                                                        <option value="AT">Agent Ticket</option>

                                                    </select>

                                                </div>

                                            </div>





                                            <br>

                                            <div class="row">

                                                <div class="col-4">

                                                    <button class="btn btn-info" onclick="customerreport()">Go</button>

                                                </div>

                                                <div class="col-4" id="searcherr">



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

            <!-- ROW-1 END -->











            <!-- ROW-4 -->

            <div class="row row-sm">

                <div class="col-lg-12">

                    <div class="card">

                        <div class="card-header">



                            <div class="col-lg-4">

                                <h3 class="card-title"><strong>Customer Report</strong></h3>

                            </div>

                            <div class="col-lg-8">















                            </div>



                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="customer_sales_report" style="width:100%;">

                                    <thead>

                                        <tr>

                                            <th class="wd-15p border-bottom-0">ID</th>

                                            <th class="wd-15p border-bottom-0">Name</th>

                                            <th class="wd-15p border-bottom-0">Mobile</th>

                                            <th class="wd-20p border-bottom-0">Email</th>

                                            <!-- <th class="wd-25p border-bottom-0">Points</th>

                                            <th class="wd-20p border-bottom-0">Earning</th> -->

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

</div>

<!-- <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'></script> -->





<!--app-content close-->

<script>
    var origin = window.location.origin;

    var url = origin + "/ajax/service/report_services.php";







    function customerreport() {

        var table = $('#customer_sales_report').DataTable();

        table.destroy();



        let ticket_name = $('#ticket_name').val();

        let draw_new_id = $('#draw_new_id').val();

        let draw_not_id = $('#draw_not_id').val();

        if (ticket_name != '' && draw_new_id != '' && draw_not_id != '') {

            table = $("#customer_sales_report").DataTable({

                pageLength: 10,

                order: [],

                paging: true,

                searching: true,

                info: true,

                ajax: {

                    url: url,

                    method: "POST",

                    dataSrc: "",

                    data: {

                        method: 'customerreport',

                        ticket_name: ticket_name,

                        draw_new_id: draw_new_id,

                        draw_not_id: draw_not_id

                    }

                },

                dom: 'Bfrtip',

                buttons: [

                    'copy',

                    'csv',

                    {

                        extend: 'excelHtml5',

                        title: 'Draw Comparison Report (' + ticket_name + ' ' + draw_new_id + ' ' + draw_not_id + ')'

                    },

                    {

                        extend: 'pdfHtml5',

                        orientation: 'landscape',

                        pageSize: 'LEGAL'

                    }, 'print',



                ],

                columns: [{

                        data: "id"

                    },

                    {

                        data: "cusname"

                    },

                    {

                        data: "mobile"

                    },



                    {

                        data: "email"

                    },

                    // {

                    //     data: "point"

                    // },

                    // {

                    //     data: "earning"

                    // }

                ]

            });



        } else {

            toast('error', 'please select all fields');

        }

    }



    function draw_not_id() {



        var formdata = [];

        formdata.push({

            name: 'method',

            value: "draw_not_id"

        });

        formdata.push({

            name: 'draw_new_id',

            value: $('#draw_new_id').val()

        });



        var post_data = formdata;

        var onsuccess = function(data) {

            var response = JSON.parse(data);

            if (response != "") {

                if (response.type == 1) {

                    var len = response.result.length;

                    $("#draw_not_id").empty();

                    $("#draw_not_id").append("<option disabled value='" + "" + "'>Select Draw</option>");

                    for (var i = 0; i < len; i++) {

                        optValue = response.result[i]['id'];

                        optText = response.result[i]['name'];

                        $("#draw_not_id").append("<option value='" + optValue + "'>" + optText + "</option>");

                    }

                    $('#draw_not_id').selectpicker('refresh');

                } else {

                    document.getElementById('draw_not_id').innerHTML = response.result;

                }

            }

        }

        do_ajax_call(post_data, onsuccess, url);

    }



    function paymentSuccess(icon, titlestr) {

        Swal.fire({

            title: titlestr,

            icon: icon,

            confirmButtonColor: '#3085d6',

            confirmButtonText: 'OKAY',

            allowOutsideClick: false

        }).then((result) => {

            if (result.isConfirmed) {

                agentEarning();

            }

        })

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