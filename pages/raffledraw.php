<?php



$pageTitle = "Raffle Draw Preliminary Winners List";



?>

<style>
    input,

    select {

        border: 1px solid #CCC;

        /* width: 250px; */

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



            <!-- ROW-1 END -->











            <!-- ROW-4 -->

            <div class="row row-sm">

                <div class="col-lg-12">

                    <div class="card">

                        <div class="card-header">



                            <div class="col-lg-4">

                                <h3 class="card-title"><strong><?= ucwords($pageTitle); ?></strong></h3>

                            </div>

                            <div class="col-lg-8">
                            </div>



                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="winner_list_report" style="width:100%;">

                                    <thead>

                                        <tr>
                                            <th class="wd-15p border-bottom-0">User ID</th>
                                            <th class="wd-15p border-bottom-0">Name</th>

                                            <th class="wd-15p border-bottom-0">Mobile</th>
                                            <!-- <th class="wd-15p border-bottom-0">Name</th> -->

                                            <th class="wd-15p border-bottom-0">Email</th>

                                            <!-- <th class="wd-15p border-bottom-0">Mobile</th> -->
                                            <!-- <th class="wd-15p border-bottom-0">Product Name</th>-->

                                            <!-- <th class="wd-15p border-bottom-0">Prize Amount (AED)</th>  -->

                                            <th class="wd-15p border-bottom-0">Total Spent AED</th>

                                            <th class="wd-15p border-bottom-0">AED 10</th>
                                            <th class="wd-15p border-bottom-0">AED 20</th>
                                            <th class="wd-15p border-bottom-0">AED 50</th>
                                            <th class="wd-15p border-bottom-0">AED 100</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                    </tbody>

                                    <!-- <tfoot>

                                        <tr>
                                            <th colspan="4"></th>
                                            <th style="text-align:right">Total:</th>
                                            <th></th>
                                            <th colspan="2"></th>
                                        </tr>

                                    </tfoot> -->

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





<!-- uplaod moadal -->









<div class="modal fade" id="uploadimg" tabindex="-1" role="dialog" aria-labelledby="Add Permission" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="Add Permission">Upload Image</h5>



            </div>

            <div class="modal-body">

                <div id="resuld"></div>



            </div>

            <div class="modal-footer">



            </div>

        </div>

    </div>

</div>









<!-- delete modal -->





<div class="modal fade" id="deleteinfo" tabindex="-1" role="dialog" aria-labelledby="customModalLabel" aria-modal="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="customModalLabel">Delete</h5> <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>

            </div>

            <div class="modal-body">

                <div class="text-center" id="dele">





                </div>

            </div>

            <div class="modal-footer custom">



                <div class="right-side">



                    <button aria-label="Close" class="btn btn-danger pd-x-25 success" onclick="refersh()" data-bs-dismiss="modal">Close</button>





                </div>

            </div>

        </div>

    </div>

</div>





<!--app-content close-->

<script>
    var origin = window.location.origin;
    var url = origin + "/ajax/service/datatable_services.php";
    // var ajax_url = origin + "/ajax/service/result_services.php";

    $(function() {
        winnerList();
    })();







    function winnerList() {

        let filename = 'Raffle Draw Preliminary Winners List';

        var table = $("#winner_list_report").DataTable({
            destroy: true,
            pageLength: 10,

            order: [
                [4, 'desc']
            ],

            paging: true,
            searching: true,
            info: true,
            ajax: {
                url: origin + "/ajax/service/grandRaffleService.php",
                method: "POST",
                dataSrc: "result",
                data: {
                    method: 'raffledrawPreWinners',
                }
            },

            dom: 'Bfrtip',

            buttons: [

                'pageLength',
                {
                    extend: 'copyHtml5',
                    title: filename
                },

                {
                    extend: 'csvHtml5',
                    title: filename
                },

                {
                    extend: 'excelHtml5',
                    title: filename
                },

                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    title: filename
                },

                'print',
            ],

            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.user_id

                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.name + ' ' + (data.lname ?? '')


                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.mobile


                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.email


                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.total
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data?.AED10 ?? ''
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data?.AED20 ?? ''
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data?.AED50 ?? ""
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data?.AED100 ?? ""
                    }
                },

                // {

                //     data: "raffleid"

                // },
                // {

                //     data: "drawname"

                // },

                // {

                //     data: "name"

                // },

                // {

                //     data: "email"

                // },



                // {

                //     data: "mobile"

                // },
                // {

                //     data: "productname"

                // },

                // {

                //     data: "prizeamt"

                // },

                // {

                //     data: "drawdate"

                // }



            ],

            // footerCallback: function(row, data, start, end, display) {

            //     var api = this.api();



            //     // Remove the formatting to get integer data for summation

            //     var intVal = function(i) {

            //         return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;

            //     };



            //     // Total over all pages

            //     total = api

            //         .column(5)

            //         .data()

            //         .reduce(function(a, b) {

            //             let x = intVal(a) + intVal(b);

            //             return x.toFixed(2);

            //         }, 0);



            //     // Total over this page

            //     pageTotal = api

            //         .column(5, {

            //             page: 'current'

            //         })

            //         .data()

            //         .reduce(function(a, b) {

            //             return intVal(a) + intVal(b);

            //         }, 0);



            //     // Update footer

            //     $(api.column(5).footer()).html('' + pageTotal + ' ( ' + total + ' total)');

            // }



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