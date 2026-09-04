<?php 

//   Date               Developer name       Description
//   11-04-2023         Prashant             Added the Is Cron columm to check is Purchase ticket by system OR Purchase ticket by cron
?>
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

                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Pay By Link</h1>

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

                                <div class="col-6 mb-2">
                                    <span>Select Date</span> &nbsp; <span style="color:red;">*</span>
                                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        <span></span> <i class="fa fa-caret-down"></i>
                                    </div>
                                </div>


                                <div class="col-3 mb-2">
                                    <span>Select Payment Status</span>
                                    <select id="paymentStatus" class="form-select">
                                        <option value="" selected>All</option>
                                        <option value="initiated">Initiated</option>
                                        <option value="completed">Paid</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <button type="submit" id="smslogsearch" class="btn btn-info" onclick="viewtable()">Go</button>
                                </div>
                            </div>
                        </div>



                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="onlineearntable" style="width:100%;">

                                    <thead>

                                        <tr>
                                            <th class="wd-15p border-bottom-0">User ID</th>
                                            <th class="wd-15p border-bottom-0">Name</th>
                                            <th class="wd-15p border-bottom-0">Mobile No</th>
                                            <th class="wd-15p border-bottom-0">Email ID</th>
                                            <th class="wd-20p border-bottom-0">Link Status</th>
                                            <th class="wd-20p border-bottom-0">Send by SMS/EMAIL</th>
                                            <th class="wd-20p border-bottom-0">Old Transaction ID</th>
                                            <th class="wd-20p border-bottom-0">New Transaction ID</th>
                                            <th class="wd-20p border-bottom-0">Payment AMount</th>
                                            <th class="wd-20p border-bottom-0">created on</th>
                                             <th class="wd-20p border-bottom-0">Cron Update</th> 





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



<script>
    var origin = window.location.origin;

    var url = origin + "/ajax/service/datatable_services.php";



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


    function viewtable() {
        var table = $('#onlineearntable').DataTable();
        table.destroy();

                   var paymentStatus = $('#paymentStatus').val()

        var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
        var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");

        var title = 'Pay by link : (' + formdate + '  to  ' + todate + ' ' + paymentStatus + ')';
        table = $("#onlineearntable").DataTable({
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
                    method: 'payment_status_history',
                    formdate: formdate,
                    todate: todate,
                    paymentStatus: paymentStatus
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
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    title: title
                }, 'print',

            ],
            columns: [{

                    'data': 'userid'

                },


                {

                    'data': 'name'

                },


                {

                    'data': 'mobile'

                },

                {

                    'data': 'email'

                },

                {

                    'data': 'linkstatus'

                },
                {

                    'data': 'type'

                },


                {

                    'data': 'oldtransaction'

                },
                {

                    'data': 'newtransaction'

                },
                {

                    'data': 'amount'

                },
                {

                    'data': 'date'

                },
                {

                    'data': 'is_cron'

                },

                // {

                //     'data': 'action'

                // }, 


            ]
        })
    }
</script>