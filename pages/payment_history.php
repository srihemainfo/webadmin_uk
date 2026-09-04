
<!--
1.modifications unknown
2.CCavanue Payment run ticket generation work  on this page working ajax file name:ajax/service/transaction_services.php



Date      Developer_name      Modifications                       Enddate
7-01-2023          Prakash             Run ticket generation      07-01-2023 
-->

<style>
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



    input,

    select {

        border: 1px solid #CCC;

    }



    button {

        color: #FFF;

        background-color: #428BCA;

        border: 1px solid #357EBD;

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

<div class="main-content app-content mt-0">

    <div class="side-app">





        <div class="main-container container-fluid">





            <div class="page-header">

                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Payments History</h1>

                <div>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page">Payments</li>

                    </ol>

                </div>

            </div>



            <div class="row row-sm">

                <div class="col-lg-12">

                    <div class="card">

                        <div class="card-header">

                            <div class="row">
                                <div class="col-12 mb-2">
                                    <h3 class="card-title"><strong>Transaction History</strong></h3>
                                </div>

                                <div class="col-12 mb-2">
                                    <span>Select Date</span> &nbsp; <span style="color:red;">*</span>
                                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        <span></span> <i class="fa fa-caret-down"></i>
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <span id="generate"></span>
                                </div>
                                <div class="col-6 mb-2">
                                    <button onclick="viewtable()">GO</button>
                                </div>
                            </div>
                            <!-- <div class="col-lg-4">

                                <h3 class="card-title"><strong>Transaction History</strong></h3>

                            </div>

                            <div class="col-lg-8">

                                <input type="date" name="formdate" id="formdate" value="<?php echo date("Y-m-d"); ?>">

                                <input type="date" name="todate" id="todate" value="<?php echo date("Y-m-d"); ?>">
                                <div id="loadingbtn">
                                    <button onclick="viewtable()">GO</button>
                                </div>
                                <br>

                                <span id="generate"></span>

                            </div> -->

                        </div>



                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="onlineearntable" style="width:100%;">

                                    <thead>

                                        <tr>
                                            <th class="wd-15p border-bottom-0">Transaction ID</th>
                                            <th class="wd-15p border-bottom-0">Gateway</th>
                                            <th class="wd-15p border-bottom-0">Ticket No</th>
                                            <th class="wd-15p border-bottom-0">Mobile Number</th>
                                            <th class="wd-20p border-bottom-0">Email ID</th>
                                            <th class="wd-20p border-bottom-0">Amount (AED)</th>
                                            <th class="wd-25p border-bottom-0">Date & Timing</th>
                                            <th class="wd-25p border-bottom-0">Status</th>
                                            <th class="wd-25p border-bottom-0">Action</th>
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





<div class="modal  fade" id="pointreq" tabindex="-1" role="dialog">

    <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Point Request</h5>

                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">×</span>

                </button>

            </div>

            <div class="modal-body">



                <div class="row">



                    <div class="col-sm-12">

                        <form class="login100-form validate-form">



                            <div class="wrap-input100 validate-input input-group">

                                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">

                                    <i class="side-menu__icon fa fa-money"></i>

                                </a>

                                <input class="input100 border-start-0 ms-0 form-control" type="text" placeholder="Enter Points">

                            </div>



                            <div class="row">

                                <div class="col-6">



                                    <label class="custom-control custom-radio">

                                        <input type="radio" class="custom-control-input" name="example-radios" value="option1" checked="">

                                        <span class="custom-control-label">Prepaid</span>

                                    </label>





                                </div>

                                <div class="col-6">

                                    <label class="custom-control custom-radio">

                                        <input type="radio" class="custom-control-input" name="example-radios" value="option2">

                                        <span class="custom-control-label">Credit</span>

                                    </label>





                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-8">

                                    <div class="form-group">

                                        <div class="form-label">Transaction Method</div>

                                        <label class="custom-switch form-switch me-5">

                                            <input type="radio" name="custom-switch-radio" class="custom-switch-input">

                                            <span class="custom-switch-indicator"></span>

                                            <span class="custom-switch-description">Offline</span>

                                        </label>

                                    </div>

                                    <div class="form-group">

                                        <label class="custom-switch form-switch">

                                            <input type="radio" name="custom-switch-radio" class="custom-switch-input" checked="">

                                            <span class="custom-switch-indicator"></span>

                                            <span class="custom-switch-description">Online</span>

                                        </label>

                                    </div>



                                </div>



                            </div>

                    </div>

                    <div class="row">

                        <div class="col-12">

                            <div class="form-label">Leader Name : <strong>Alex</strong></div>

                            <div class="form-label">ID : <strong>123456</strong></div>

                        </div>

                    </div>

                    </form>

                </div>

            </div>



        </div>

        <div class="modal-footer">



            <button class="btn btn-secondary">Request</button>

        </div>

    </div>

</div>








<div class="modal fade" id="requeryStatus" tabindex="-1" role="dialog">

    <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Payment Status</h5>

                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">×</span>

                </button>

            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-sm-12">

                        <div id="PaymentErr"></div>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" onclick="closerequeryStatus('requeryStatus')" data-dismiss="modal">Close</button>

                <div id="previewBTN">



                </div>

            </div>

        </div>

    </div>

</div>





<div class="modal fade" id="otpreviewModal">

    <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content modal-content-demo" style="width: 400px !important;">

            <div class="modal-header">

                <h6 class="modal-title">Ticket Preview</h6>

                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">×</span>

                </button>

            </div>

            <div class="modal-body">

                <div class="row">



                    <div class="col-sm-12">

                        <div id="otticketview">



                        </div>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Cancel</button>

                <div id="otbtn">



                </div>

            </div>

        </div>

    </div>

</div>







<script>
    var origin = window.location.origin;
    var url = origin + "/ajax/service/transaction_services.php";



    $(function() {
        var start = moment();
        var end = moment();

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));
        }
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);
        cb(start, end);
        viewtable();
    });




    function closerequeryStatus(id) {
        $('#' + id).modal('hide');
        viewtable();
    }

    // function viewtable() {
    //     get_Generate_count();
    //     var table = $('#onlineearntable').DataTable();
    //     table.destroy();

    //     var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
    //     var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");

    //     var title = 'Payment History Report : (' + formdate + ' to ' + todate + ' )';
    //     table = $("#onlineearntable").DataTable({
    //         pageLength: 10,
    //         order: [],
    //         paging: true,
    //         searching: true,
    //         info: true,
    //         ajax: {
    //             url: url,
    //             method: "POST",
    //             dataSrc: "",
    //             data: {
    //                 method: 'payment_history',
    //                 formdate: formdate,
    //                 todate: todate,

    //             }
    //         },
    //         dom: 'Bfrtip',
    //         buttons: [
    //             'pageLength',
    //             'copy',


               

    //             {
    //                 extend: 'excelHtml5',
    //                 title: title
    //             },
                

    //         ],
    //         columns: [{

    //                 'data': 'transid'

    //             },
    //             {

    //                 'data': 'gateway'

    //             },
    //             {

    //                 'data': 'ticketno'

    //             },

    //             {

    //                 'data': 'mobile'

    //             },

    //             {

    //                 'data': 'email'

    //             },

    //             {

    //                 'data': 'amt'

    //             },

    //             {

    //                 'data': 'date'

    //             },

    //             {

    //                 'data': 'stauts'

    //             },

    //             {

    //                 'data': 'action'

    //             }

    //         ]
    //     });
    // }


    function viewtable() {
    get_Generate_count();
    
    var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
    var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");

    var title = 'Payment History Report : (' + formdate + ' to ' + todate + ' )';

    // Destroy existing DataTable instance
    if ($.fn.DataTable.isDataTable('#onlineearntable')) {
        $('#onlineearntable').DataTable().destroy();
    }

    // Initialize DataTable
    $('#onlineearntable').DataTable({
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
                method: 'payment_history',
                formdate: formdate,
                todate: todate
            }
        },
        dom: 'Bfrtip',
        buttons: [
            'pageLength',
            'copy',
            {
                extend: 'excelHtml5',
                title: title
            }
        ],
        columns: [
            { 'data': 'transid' },
            { 'data': 'gateway' },
            { 'data': 'ticketno' },
            { 'data': 'mobile' },
            { 'data': 'email' },
            { 'data': 'amt' },
            { 'data': 'date' },
            { 'data': 'stauts' },
            { 'data': 'action' }
        ],
        destroy: true // Ensure DataTable is reinitialized correctly
    });
}



    function get_Generate_count() {

        var formdata = [];

        formdata.push({
            name: 'method',
            value: "get_Generate_count"
        });

        var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
        var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");

        formdata.push({
            name: 'formdate',
            value: formdate
        });

        formdata.push({
            name: 'todate',
            value: todate
        });

        var post_data = formdata;
        var onsuccess = function(data) {
            var response = JSON.parse(data);
            if (response != "") {
                if (response.type == 1) {
                    document.getElementById('generate').innerHTML = response.generate;
                }
            }
        }

        do_ajax_call(post_data, onsuccess, url);
    }


    function requery(transid) {

        var formdata = [];

        formdata.push({

            name: 'method',

            value: "Check_Payemnt_Status"

        });



        formdata.push({

            name: 'transid',

            value: transid

        });



        var post_data = formdata;

        var onsuccess = function(data) {



            var response = JSON.parse(data);

            if (response != "") {

                if (response.type == 1) {

                    document.getElementById('PaymentErr').innerHTML = '<div class="alert alert-success" role="alert">' + response.result + '</div>';

                    document.getElementById('previewBTN').innerHTML = response.output;

                    $('#requeryStatus').modal('show');

                } else {

                    document.getElementById('PaymentErr').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';

                    $('#requeryStatus').modal('show');

                }



            }

        }



        do_ajax_call(post_data, onsuccess, url);



    }

    function otpreview(transid) {

        $('#requeryStatus').modal('hide');

        $('#otpreviewModal').modal('show');

        document.getElementById('otticketview').innerHTML = '<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>';

        var formdata = [];

        formdata.push({

            name: 'method',

            value: "otpreview"

        });



        formdata.push({

            name: 'transid',

            value: transid

        });



        var post_data = formdata;

        var onsuccess = function(data) {

            var response = JSON.parse(data);

            if (response != "") {

                if (response.type == 1) {

                    document.getElementById('otticketview').innerHTML = response.output;

                    document.getElementById('otbtn').innerHTML = response.otreigiterbtn;

                } else {

                    document.getElementById('otticketview').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';

                }

            }

        }

        do_ajax_call(post_data, onsuccess, url);

    }

    function otregisteroffline(transid, drawid) {

        document.getElementById('otbtn').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';

        var formdata = [];

        formdata.push({

            name: 'method',

            value: "otregisteroffline"

        });



        formdata.push({

            name: 'transid',

            value: transid

        });



        formdata.push({

            name: 'drawid',

            value: drawid

        });



        var post_data = formdata;

        var onsuccess = function(data) {

            var response = JSON.parse(data);

            if (response != "") {

                if (response.type == 1) {

                    document.getElementById('otbtn').innerHTML = '<button class="btn ripple btn-danger" onclick="closerequeryStatus(' + "'otpreviewModal'" + ')" data-bs-dismiss="modal" type="button">Close</button>';

                    document.getElementById('otticketview').innerHTML = '<div class="alert alert-success" role="alert">' + response.result + '</div>';

                } else {



                    document.getElementById('otticketview').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';

                    document.getElementById('otbtn').innerHTML = response.otreigiterbtn;

                }

            }

        }

        do_ajax_call(post_data, onsuccess, url);

    }
</script>