<?php
/**
 * Date        Developer     Modifications 
 * 7-6-2023    Prakash        Datapicker Updated     
 */

$pageTitle = "Monthly Report (Line Wise)";

?>

<style>
    input,
    select {
        border: 1px solid #ccc
    }

    button,
    input {
        height: 35px;
        margin: 0;
        padding: 6px 12px;
        border-radius: 2px;
        font-family: inherit;
        font-size: 100%;
        color: inherit
    }

    .back-arrow-btn i {
        background: #fff;
        font-size: 16px;
        padding: 2px 3px;
        border-radius: 50px;
        border: 2px solid #6c6e70;
        color: #6c6e70;
        margin-right: 15px;
        width: 24px;
        height: 24px
    }
</style>



<script>
    window.onload = function() {
        var n = window.location.origin;
        document.getElementById("anchor").href = n
    };
</script>



<!--app-content open-->

<div class="main-content app-content mt-0">

    <div class="side-app">

        <input type="hidden" id="tabID" value="agents">

        <!-- CONTAINER -->

        <div class="main-container container-fluid">



            <!-- PAGE-HEADER -->

            <div class="page-header">

                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a><?= ucwords($pageTitle); ?>

                </h1>

                <div>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page">

                            <?= ucwords($pageTitle); ?>

                        </li>

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

                                    <div class="">

                                        <div class="mt-2">

                                            <div class="row">





                                                <div class="col-lg-6 col-md-6 col-sm-6">

                                                    <span>Select Date</span> &nbsp; <span style="color:red;">*</span>

                                                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">

                                                        <i class="fa fa-calendar"></i>&nbsp;

                                                        <span></span> <i class="fa fa-caret-down"></i>

                                                    </div>

                                                </div>





                                                <div class="col-lg-6 col-md-6 col-sm-6 mt-4">

                                                    <button class="btn btn-info" onclick="searchTicket()">Go</button>

                                                </div>



                                            </div>





                                            <br>

                                            <!--<div class="row">-->



                                            <!--<div class="col-4" id="searcherr">-->



                                            <!--</div>-->

                                            <!--</div>-->

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

                                <h3 class="card-title"><strong>Ticket Report</strong></h3>

                            </div>

                            <div class="col-lg-8">















                            </div>



                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="ticket_report" style="width:100%;">

                                    <thead>

                                        <tr>

                                            <th class="wd-15p border-bottom-0">Ticket ID</th>

                                            <th class="wd-15p border-bottom-0">Customer Name</th>

                                            <th class="wd-15p border-bottom-0">Mobile Number</th>

                                            <th class="wd-20p border-bottom-0">Email ID</th>

                                            <th class="wd-25p border-bottom-0">My3Numbers</th>

                                            <th class="wd-25p border-bottom-0">Transaction ID</th>

                                            <th class="wd-25p border-bottom-0">Payment Type</th>

                                            <th class="wd-20p border-bottom-0">Raffle ID</th>

                                            <th class="wd-20p border-bottom-0">Product Amount (AED)</th>

                                            <th class="wd-25p border-bottom-0">Agent Name</th>

                                            <th class="wd-25p border-bottom-0">Draw No</th>

                                            <th class="wd-25p border-bottom-0">Purchase Date & Timing</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                    </tbody>

                                    <tfoot>

                                        <tr>

                                            <th></th>

                                            <th></th>

                                            <th></th>

                                            <th></th>

                                            <th></th>

                                            <th></th>

                                           

                                            <th></th>
 <th style="text-align:right">Total:</th>
                                            <th></th>

                                            <th></th>

                                            <th></th>       
 <th></th>
                                        </tr>

                                    </tfoot>

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
    var url = origin + "/ajax/service/report_services.php";



    (function() {

        // var start = moment();

        // var end = moment();



        // function cb(start, end) {

        //     $('#reportrange span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));

        // }

        // $('#reportrange').daterangepicker({

        //     startDate: start,

        //     endDate: end,

        //     ranges: {

        //         'Today': [moment(), moment()],

        //         'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],

        //         'Last 7 Days': [moment().subtract(6, 'days'), moment()],

        //         'Last 30 Days': [moment().subtract(29, 'days'), moment()],

        //         'This Month': [moment().startOf('month'), moment().endOf('month')],

        //         'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]

        //     }

        // }, cb);

        // cb(start, end);
        createDatePricket('reportrange');
    })();

    function createDatePricket(id) {

        var start = moment();

        var end = moment();



        function cb(start, end) {
            // $('#' + id + ' span').html(start.format("DD MMM YYYY hh:mm a") + ' - ' + end.format("DD MMM YYYY hh:mm a"));
            $('#' + id + ' span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));

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
            // minDate: moment().format("MM/DD/YYYY"),
            timePicker: true,
            timePicker24Hour: true,
            timePickerSeconds: true,
            maxSpan: {
                days: 30
            },
            autoUpdateInput: true,
            // showDropdowns: true,
            minYear: moment().format("YYYY"),
            maxYear: moment().add(1, 'years').format("YYYY"),
            ranges: {

                'Today': [moment().set({
                    hour: 0,
                    minute: 0,
                    second: 0,
                    millisecond: 0
                }), moment().set({
                    hour: 23,
                    minute: 59,
                    second: 59,
                    millisecond: 59
                })],

                'Yesterday': [moment().subtract(1, 'days').set({
                    hour: 0,
                    minute: 0,
                    second: 0,
                    millisecond: 0
                }), moment().subtract(1, 'days').set({
                    hour: 23,
                    minute: 59,
                    second: 59,
                    millisecond: 59
                })],

                'Last 7 Days': [moment().subtract(6, 'days').set({
                    hour: 0,
                    minute: 0,
                    second: 0,
                    millisecond: 0
                }), moment().set({
                    hour: 23,
                    minute: 59,
                    second: 59,
                    millisecond: 59
                })],

                'Last 30 Days': [moment().subtract(29, 'days').set({
                    hour: 0,
                    minute: 0,
                    second: 0,
                    millisecond: 0
                }), moment().set({
                    hour: 23,
                    minute: 59,
                    second: 59,
                    millisecond: 59
                })],

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

                'Last Month': [moment().subtract(1, 'month').startOf('month').set({
                    hour: 0,
                    minute: 0,
                    second: 0,
                    millisecond: 0
                }), moment().subtract(1, 'month').endOf('month').set({
                    hour: 23,
                    minute: 59,
                    second: 59,
                    millisecond: 59
                })]

            }

        }, cb);

        cb(start, end);



    }

    function searchTicket() {

        var table = $('#ticket_report').DataTable();

        table.destroy();



        // document.getElementById('searcherr').innerHTML = '';





        // var fromdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
        // var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");


        var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
        var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");


        if (formdate == '' && todate == '') {


            toast('error', 'Please Select Date');

            return false;
        }

        var title = 'Monthly Line Wise Report (' + moment(formdate).format("DD-MMM-YYYY hh:mm a") +  ' to ' +  moment(todate).format("DD-MMM-YYYY hh:mm a") + ')';

        table = $("#ticket_report").DataTable({

            pageLength: 10,

            // order: [],

            columnDefs: [

                {
                    type: 'date',
                    targets: [11]
                } // Assuming the date column is at index 2

            ],

            order: [

                [11, 'desc']

            ],

            paging: true,

            searching: true,

            info: true,

            ajax: {

                url: url,

                method: "POST",

                dataSrc: "",

                data: {

                    method: 'monthly_report_rw',

                    fromdate: formdate,

                    todate: todate

                }

            },

            dom: 'Bfrtip',

            buttons: [

                'pageLength',

                'copy',

                {

                    extend: 'csvHtml5',

                    title: title

                },

                {

                    extend: 'excelHtml5',

                    title: title

                },

                // {

                //     extend: 'pdfHtml5',

                //     orientation: 'landscape',

                //     pageSize: 'LEGAL',

                //     title: title

                // },
                // 'print',

            ],

            columns: [{

                    data: "ticketno"

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

                {

                    data: "My3Numbers"

                },

                {

                    data: "transaction_id"

                },

                {

                    data: "payment_method"

                },



                {

                    data: "RaffleID"

                },

                {

                    data: "proamt"

                },

                {

                    data: 'agentname'

                },

                {

                    data: 'drawno'

                },

                // {

                //     data: "purdate"

                // }

                {

                    data: null,

                    render: function(data, type, row, meta) {

                        return moment(data.date).format("DD MMM YYYY hh:mm a")

                    }

                },

            ],

            footerCallback: function(row, data, start, end, display) {

                var api = this.api();



                // Remove the formatting to get integer data for summation

                var intVal = function(i) {

                    return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;

                };



                // Total over all pages

                total = api

                    .column(8)

                    .data()

                    .reduce(function(a, b) {

                        return intVal(a) + intVal(b);

                    }, 0);



                // Total over this page

                pageTotal = api

                    .column(8, {

                        page: 'current'

                    })

                    .data()

                    .reduce(function(a, b) {

                        return intVal(a) + intVal(b);

                    }, 0);



                // Update footer

                $(api.column(8).footer()).html('' + pageTotal + ' ( ' + total + ' total)');

            },

        });

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