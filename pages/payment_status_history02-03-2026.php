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
        //  alert(anchor.href);
    }
</script>
<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <br>
            <div class="page-header">
                <!--<h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Payments Status History</h1>-->
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)"></i></a>Payments Status History</h1>
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

                                <div class="col-lg-6 col-md-6">
                                    <span>Select Date</span> &nbsp; <span style="color:red;">*</span>
                                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        <span></span> <i class="fa fa-caret-down"></i>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <span>Select Payment Gateway</span>
                                    <select id="gatewayname" class="form-select">
                                        <option value="">Select Gateway</option>
                                        <!--<option value="network">Network</option>-->
                                        <option value="ccavenue" selected>CCAvenue</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <span>Select Payment Status</span>
                                    <select id="paymentStatus" class="form-select">
                                        <option value="" selected>Select Status</option>
                                        <?php
                                        $payStatus = mysqli_query($con, "SELECT `paymentStatus` FROM `payment_history` WHERE `gateway` NOT IN ('ldwallet', 'COUPON') AND `paymentStatus` NOT IN ('COUPON', 'WALLET', 'CASH', 'BONUS', '') GROUP BY `paymentStatus` ORDER BY paymentStatus ASC");
                                        while ($row = mysqli_fetch_assoc($payStatus)) {
                                        ?>
                                            <option value="<?= $row['paymentStatus']; ?>"><?= ucwords(strtolower($row['paymentStatus'])); ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-6 mt-5" id="paySearchBtn">
                                    <button type="submit" id="smslogsearch" class="btn btn-info" onclick="viewtable()">Go</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="onlineearntable" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p border-bottom-0">Name</th>
                                            <th class="wd-20p border-bottom-0">Mobile</th>
                                            <th class="wd-20p border-bottom-0">Email ID</th>

                                            <th class="wd-15p border-bottom-0">Transaction ID</th>
                                            <th class="wd-15p border-bottom-0">Ticket No</th>
                                            <th class="wd-15p border-bottom-0">Gateway</th>
                                            <th class="wd-20p border-bottom-0">Amount (AED)</th>
                                            <th class="wd-25p border-bottom-0">Date & Timing</th>

                                            <th class="wd-15p border-bottom-0">Last Login</th>
                                            <th class="wd-25p border-bottom-0">Status</th>
                                            <th class="wd-25p border-bottom-0">Error Note</th>
                                            <th class="wd-25p border-bottom-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         <tfoot>
                                           <tr>
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
                                              <th></th>
                                              <th></th>
                                           </tr>
                                        </tfoot>
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

<!--Code Start By Prashant At 08-06-2023-->
<script>
    var url = window.location.origin + "/ajax/service/transaction_services.php";
    var ccAvenue = '<?= $_POST['ccAvenue']; ?>';


    $(function() {


        createDatePricket('reportrange');
        if (ccAvenue != '') {
            viewtable();
        }
    });

    function createDatePricket(id) {
        var start = moment();
        var end = moment();

        function cb(start, end) {
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

            timePicker: true,
            timePicker24Hour: true,
            timePickerSeconds: true,

            maxSpan: {
                days: 365
            },

            autoUpdateInput: true,

            // minYear: moment().format("YYYY"),
            // maxYear: moment().add(1, 'years').format("YYYY"),
            maxDate: moment().add(1, 'days').toDate(),
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



                //   'Yesterday': [moment().subtract(1, 'days').set({

                //       hour: 0,
                //       minute: 0,
                //       second: 0,
                //       millisecond: 0

                //   }), moment().subtract(1, 'days').set({

                //       hour: 23,
                //       minute: 59,
                //       second: 59,
                //       millisecond: 59

                //   })],



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



                //   'Last 30 Days': [moment().subtract(29, 'days').set({

                //       hour: 0,
                //       minute: 0,
                //       second: 0,
                //       millisecond: 0

                //   }), moment().set({

                //       hour: 23,
                //       minute: 59,
                //       second: 59,
                //       millisecond: 59

                //   })],



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

                })],



                'This Year': [moment().startOf('year').set({

                    hour: 0,
                    minute: 0,
                    second: 0,
                    millisecond: 0

                }), moment().endOf('year').set({

                    hour: 23,
                    minute: 59,
                    second: 59,
                    millisecond: 59

                })],



                'Last Year': [moment().subtract(1, 'year').startOf('year').set({

                    hour: 0,
                    minute: 0,
                    second: 0,
                    millisecond: 0

                }), moment().subtract(1, 'year').endOf('year').set({

                    hour: 23,
                    minute: 59,
                    second: 59,
                    millisecond: 59

                })]
            }
        }, cb);

        cb(start, end);

    }

    function closerequeryStatus(id) {

        $('#' + id).modal('hide');

        viewtable();

    }



    // function viewtable() {
    //     var ccAvenue = ''; // Reset ccAvenue at the beginning

    //     //   if (ccAvenue === '') {
    //     //       if ($('#draw_new_id').val() === '') {
    //     //           toast('error', 'Kindly Select The Draw');
    //     //           return false;
    //     //       }
    //     //   }
    //     var gatewayname = $('#gatewayname').val()
    //     var paymentStatus = $('#paymentStatus').val()
    //     var draw_new_id = $('#draw_new_id').val()
    //     var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:-mm:-ss");
    //     var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:-mm:-ss");

    //     var title = 'Payment Status History: (' + formdate + ' to ' + todate + ' ' + paymentStatus + ' ' + draw_new_id + ' ' + gatewayname + ' ' + ccAvenue + ')';
    //     var btn = $('#paySearchBtn').html();
    //     //   $('#paySearchBtn').html(`<button class="btn btn-primary" type="button" disabled>
    //     //       <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
    //     //       <span class="sr-only">Loading...</span>
    //     //       </button>`);

    //     var table = $("#onlineearntable").DataTable({
    //         destroy: true,
    //         pageLength: 10,
    //         order: [
    //             [5, 'desc']
    //         ],
    //         columnDefs: [{
    //             type: 'date',
    //             targets: [10]
    //         }],
    //         paging: true,
    //         searching: true,
    //         info: true,
    //         ajax: {
    //             url: url,
    //             method: "POST",
    //             dataSrc: "",
    //             data: {
    //                 method: 'payment_status_history',
    //                 formdate: formdate,
    //                 todate: todate,
    //                 gatewayname: gatewayname,
    //                 paymentStatus: paymentStatus,
    //                 draw_new_id: draw_new_id,
    //                 ccAvenue: ccAvenue
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
    //                 data: 'name'
    //             },
    //             {
    //                 data: 'mobile'
    //             },
    //             {
    //                 data: 'email'
    //             },

    //             {
    //                 data: 'transid'
    //             },
    //             {
    //                 data: 'ticketno'
    //             },
    //             {
    //                 data: 'gateway'
    //             },
    //             //   {
    //             //       data: null,
    //             //       render: function(data, type, row, meta) {
    //             //           if (data.gateway == '' || data.gateway == 'network') {
    //             //               return '<span style="color: red; font-weight: bolder;">Network</span>';
    //             //           } else {
    //             //               return '<span style="color: blue; font-weight: bolder;">CCAvenue</span>';
    //             //           }
    //             //       }
    //             //   },


    //             {
    //                 data: 'amt'
    //             },


    //             {
    //                 data: null,
    //                 render: function(data, type, row, meta) {
    //                     return moment(data.date).format("DD MMM YYYY hh:mm a");
    //                 }
    //             },



    //             {
    //                 data: null,
    //                 render: function(data, type, row, meta) {
    //                     return moment(data.lostlogin).format("DD MMM YYYY hh:mm a");
    //                 }
    //             },

    //             {
    //                 data: 'stauts'
    //             },

    //             {
    //                 data: null,
    //                 render: function(data, type, row, meta) {
    //                     return `<textarea readonly="">${(data.errorMessage != null && data.errorMessage != 'null' ? data.errorMessage : '')}</textarea>`;
    //                 }
    //             },
    //             {
    //                 data: 'action'
    //             },

    //         ],
            
    //          footerCallback: function(row, data, start, end, display) {
    //         var api = this.api();
    //         // Remove the formatting to get integer data for summation
    //         var intVal = function(i) {
    //           return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
    //         };
    //         // Total over all pages
    //         total = api
    //           .column(6)
    //           .data()
    //           .reduce(function(a, b) {
    //               let x = intVal(a) + intVal(b);
    //               return x.toFixed(2);
    //           }, 0);
    //         // Total over this page
    //         pageTotal = api
    //           .column(6, {
    //               page: 'current'
    //           })
    //           .data()
    //           .reduce(function(a, b) {
    //               return intVal(a) + intVal(b);
    //           }, 0);
    //         // Update footer
    //         $(api.column(6).footer()).html('' + pageTotal + ' ( ' + total + ' total)');

    //      }

    //     });


    // }
    function viewtable() {
    var ccAvenue = ''; // Reset ccAvenue at the beginning

    var gatewayname = $('#gatewayname').val();
    var paymentStatus = $('#paymentStatus').val();
    var draw_new_id = $('#draw_new_id').val();
    var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
    var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");

    // var title = 'Payment Status History: (' + formdate + ' to ' + todate + ' ' + paymentStatus + ' ' + draw_new_id + ' ' + gatewayname + ' ' + ccAvenue + ')';
    var title = 'Payment Status History: ';

    var table = $("#onlineearntable").DataTable({
        destroy: true,
        pageLength: 10,
        order: [[5, 'desc']],
        columnDefs: [{
            type: 'date',
            targets: [10]
        }],
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
                gatewayname: gatewayname,
                paymentStatus: paymentStatus,
                draw_new_id: draw_new_id,
                ccAvenue: ccAvenue
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
            { data: 'name' },
            { data: 'mobile' },
            { data: 'email' },
            { data: 'transid' },
            { data: 'ticketno' },
            { data: 'gateway' },
            { data: 'amt' },
            {
                data: null,
                render: function(data, type, row, meta) {
                    return moment(data.date).format("DD MMM YYYY hh:mm a");
                }
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    return moment(data.lostlogin).format("DD MMM YYYY hh:mm a");
                }
            },
            { data: 'stauts' },
            {
                data: null,
                render: function(data, type, row, meta) {
                    return `<textarea readonly="">${(data.errorMessage != null && data.errorMessage != 'null' ? data.errorMessage : '')}</textarea>`;
                }
            },
            { data: 'action' }
        ],
        footerCallback: function(row, data, start, end, display) {
            var api = this.api();

            var intVal = function(i) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };

            var total = api
                .column(6)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            var pageTotal = api
                .column(6, { page: 'current' })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            $(api.column(6).footer()).html(pageTotal.toFixed(2) + ' ( ' + total.toFixed(2) + ' total)');
        }
    });
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

    function changeDrawRange(id) {

        if (id == '') {
            toast('error', 'Kindly Select The Draw');
            return false;
        }


        var formdata = [];

        formdata.push({

            name: 'method',

            value: "get_draw_date"

        }, {

            name: 'drawid',

            value: id

        });


        var post_data = formdata;

        var onsuccess = function(data) {



            var response = JSON.parse(data);

            if (response != "") {

                if (response.type == 1) {

                    createDatePricket('reportrange', moment(response.start).set({
                        hour: 0,
                        minute: 0,
                        second: 0,
                        millisecond: 0
                    }), moment(response.end).set({
                        hour: 23,
                        minute: 59,
                        second: 59,
                        millisecond: 59
                    }))

                } else {

                    toast('error', response.result);
                }



            }

        }



        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/transaction_services.php");




    }
</script>
<?php
unset($_POST['ccAvenue']);
?>