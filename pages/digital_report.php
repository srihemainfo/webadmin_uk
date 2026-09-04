<?php

/*
1.modifications unknown

Date      Developer_name      Modifications

 */

$pageTitle = "Digital Market Reports";

?>

<style>
    input,

    select {

        border: 1px solid #CCC;



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

                            <div class="card overflow-hidden">

                                <div class="card-body">



                                    <div class="mt-2">

                                        <div class="row">



                                            <div class="col-lg-3 col-sm-6">
                                                <span>Select Date</span>
                                                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                    <i class="fa fa-calendar"></i>&nbsp;
                                                    <span></span> <i class="fa fa-caret-down"></i>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-sm-3" id="serchbtn">
                                                <br>

                                                <button class="btn btn-info" onclick="searchTicket()">Go</button>

                                            </div>

                                        </div>





                                      

                                        <!-- <div class="row">

                                            <div class="col-4" id="serchbtn">

                                                <button class="btn btn-info" onclick="searchTicket()">Go</button>

                                            </div>

                                        </div> -->

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

                                <h3 class="card-title"><strong>Digital Market Report</strong></h3>

                            </div>

                            <div class="col-lg-8">















                            </div>



                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="dm_report" style="width:100%;">

                                    <thead>

                                        <tr>

                                            <th class="wd-15p border-bottom-0">Page Name</th>

                                            <th class="wd-15p border-bottom-0">Total Views</th>
                                            <th></th>


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







<!--app-content close-->

<script>
    var origin = window.location.origin;

    var url = origin + "/ajax/service/datatable_services.php";







    $(function() {

        createDatePricket('reportrange');
        searchTicket();
    });

    function createDatePricket(id) {
        var start = moment();
        var end = moment();

        function cb(start, end) {
            $('#' + id + ' span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));
        }
        $('#' + id).daterangepicker({
            startDate: start,
            endDate: end,
            maxSpan: {
                days: 6
            },
            minDate: moment().subtract(14, "days").format("MM/DD/YYYY"),
            maxDate: moment().format("MM/DD/YYYY"),
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                // 'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                // 'This Month': [moment().startOf('month'), moment().endOf('month')],
                // 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);
        cb(start, end);
    }

    function searchTicket() {
        var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
        var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");
        if (formdate != '' && todate != '') {
            var btn = document.getElementById('serchbtn').innerHTML;
            document.getElementById('serchbtn').innerHTML = '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>';
            var table = $('#dm_report').DataTable();
            table.destroy();

            table = $("#dm_report").DataTable({

                pageLength: 10,

                responsive: {
                    details: {
                        type: 'column',
                        target: -1,
                    }
                },
                columnDefs: [{
                    targets: -1,
                    orderable: false,
                    searchable: false,
                    className: 'control',
                }, {
                    targets: 0,
                    orderable: false,
                    searchable: false,
                    className: 'selectall-checkbox',
                }],
                select: {
                    style: 'multi',
                    selector: 'td:first-child',
                },
                order: [],

                paging: true,

                searching: true,

                info: true,

                ajax: {

                    url: url,

                    method: "POST",

                    dataSrc: "",

                    data: {

                        method: 'digital_market',
                        formdate: formdate,
                        todate: todate

                    }

                },

                dom: 'Bfrtip',

                buttons: [
                    'pageLength',
                    // 'copy',
                    'csv',
                    {

                        extend: 'excelHtml5',

                        title: 'Digital Market Reports(' + formdate + ' to ' + todate + ')'

                    },
                    // 'pdf',
                    'print'
                ],

                columns: [{
                        data: "pagename"
                    },
                    {
                        data: "totalviews"
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return ''
                        }
                    }
                ],



            });

            document.getElementById('serchbtn').innerHTML = btn;
        } else {
            toast('error', 'Please Select Date');
        }


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