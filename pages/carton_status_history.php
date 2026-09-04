<?php 
/**
 * Date      Developer       Modifications
 * 19-4-2023  Prakash             Carton Status History Development completed.
 */

?>



<style>
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

   input,

    select {

        border: 1px solid #CCC;

        /* width: 250px; */

    }



    .text {

        float: unset !important;

    }
    button {
        color: #fff;
        background-color: #428bca;
        border: 1px solid #357ebd
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
    
    .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
    width: 100%;
}
</style>

<script>
    window.onload = function() {
        var n = window.location.origin;
        document.getElementById("anchor").href = n
    };
</script>

<div class="main-content app-content mt-0">

    <div class="side-app">





        <div class="main-container container-fluid">





            <div class="page-header">

                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Carton Status History</h1>

                <div>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page">Carton</li>

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
                                    <span>Select Date</span>
                                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        <span></span> <i class="fa fa-caret-down"></i>
                                    </div>
                                </div>



                                <div class="col-lg-6 col-md-6">
                                    <span>Select Payment Gateway</span>
                                    <select id="gatewayname" class="form-select">
                                        <option value="" selected>Select Gateway</option>
                                        <option value="network">Network</option>
                                        <option value="ccavenue">CCAvenue</option>
                                    </select>
                                </div>

                                <div class="col-lg-6 col-md-6">
                                    <label>Select Payment Status</label><br>
                                    <select id="paymentStatus"  class="selectpicker" multiple aria-label="Default select example" data-live-search="true">
                                        <option value="" disabled>Select Status</option>
                                        <?php
                                        $payStatus = mysqli_query($con, "SELECT `pay_re_status` FROM `payment_history` WHERE `gateway` NOT IN ('ldwallet', 'COUPON') AND `pay_re_status` NOT IN ('WALLET' ,'') GROUP BY `pay_re_status` ORDER BY pay_re_status ASC");
                                        while ($row = mysqli_fetch_assoc($payStatus)) {
                                        ?>
                                            <option value="<?= $row['pay_re_status']; ?>"><?= ucwords(strtolower($row['pay_re_status'])); ?></option>
                                        <?php
                                        }
                                        ?>


                                    </select>
                                </div>

                                <div class="col-lg-6 col-md-6 mt-5">
                                    <button type="submit" id="smslogsearch" class="btn btn-info" onclick="viewtable()">Go</button>
                                </div>
                            </div>
                        </div>



                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="onlineearntable" style="width:100%;">

                                    <thead>

                                        <tr>

                                            <th class="wd-15p border-bottom-0">Transaction ID</th>
                                            <!--<th class="wd-15p border-bottom-0">Ticket No</th>-->
                                            <th class="wd-15p border-bottom-0">Gateway</th>
                                            <!--<th class="wd-15p border-bottom-0">Draw Name</th>-->
                                            <th class="wd-15p border-bottom-0">Name</th>
                                            <th class="wd-20p border-bottom-0">Mobile</th>
                                            <th class="wd-20p border-bottom-0">Cartons</th>
                                            <th class="wd-20p border-bottom-0">Bottle</th>
                                            <th class="wd-15p border-bottom-0">Total Points</th>
                                            <th class="wd-20p border-bottom-0">Cash Points</th>
                                            <th class="wd-20p border-bottom-0">Bonus Points</th>

                                            <!--<th class="wd-20p border-bottom-0">Nationality</th>-->
                                            <!--<th class="wd-20p border-bottom-0">Live in</th>-->
                                            <th class="wd-20p border-bottom-0">Email ID</th>
                                            <th class="wd-20p border-bottom-0">Amount (AED)</th>
                                            <!--<th class="wd-20p border-bottom-0">Bank info</th>-->
                                            <!--<th class="wd-20p border-bottom-0">Card info</th>-->
                                            <th class="wd-25p border-bottom-0">Date & Timing</th>
                                            <!--<th class="wd-15p border-bottom-0">Reason</th>-->
                                            <th class="wd-15p border-bottom-0">ip</th>
                                            <th class="wd-15p border-bottom-0">User Created On</th>
                                            <!--<th class="wd-15p border-bottom-0">Last Login</th>-->
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
    var url = window.location.origin + "/ajax/service/transaction_services.php";
    // var ccAvenue = '<?= $_POST['ccAvenue']; ?>';


    $(function() {


        createDatePricket('reportrange', '', '');
        // if (ccAvenue != '') {
        //     viewtable();
        // }
    });




    function viewtable() {
        var table = $('#onlineearntable').DataTable();
        table.destroy();
        // if (ccAvenue == '') {
        // if ($('#draw_new_id').val() == '') {
        //     toast('error', 'Kindly Select The Draw');
        //     return false;
        // }

        // }


        var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:-mm:-ss");
        var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:-mm:-ss");

        var title = 'Carton Status History : (' + formdate + '  to  ' + todate + ')';
        table = $("#onlineearntable").DataTable({
            pageLength: 10,
            order: [
                [
                    11, 'desc'
                ]
            ],
            columnDefs: [{
                type: 'date',
                targets: [11]
            }],
            paging: true,
            searching: true,
            info: true,
            ajax: {
                url: window.location.origin + "/ajax/service/transaction_services.php",
                method: "POST",
                dataSrc: "",
                data: {
                    method: 'carton_status_history',
                    formdate: formdate,
                    todate: todate,
                    gatewayname: $('#gatewayname').val(),
                    paymentStatus: $('#paymentStatus').val(),
                    // draw_new_id: $('#draw_new_id').val(),
                    // ccAvenue: ccAvenue

                }
            },
            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                'colvis',
                // 'copy',


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
                },
                // 'print',

            ],
            columns: [{

                    data: 'transid'

                },
                // {

                //     data: 'ticketno'

                // },
                {
                    data: 'gateway'
                },
                // {
                //     data: 'drawname'
                // },
                {

                    data: 'name'

                },


                {

                    data: 'mobile'

                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        if ((data.carton_ids != null && data.carton_ids != '')) {
                            return `<textarea readonly="">${(data.carton_ids != null && data.carton_ids != '') ? data.carton_ids : ''}</textarea>`;
                        } else {
                            return '';
                        }

                    }
                },

                // {
                //     data: 'cartons'
                // },
                {
                    data: 'bottle'
                },


                {
                    data: 'total_point'
                },
                {
                    data: 'total_cash'
                },
                {
                    data: 'total_bonus'
                },














                // {

                //     data: 'nation'

                // },
                // {

                //     data: 'livein'

                // },
                {

                    data: 'email'

                },

                {

                    data: 'amt'

                },
                // {

                //     data: 'bankinfo'

                // },
                // {

                //     data: 'cardinfo'

                // },
                {

                    data: 'date'

                },

                // {

                //     data: 'reason'

                // },
                {

                    data: 'ip'

                },
                // {

                //     data: 'creadedon'

                // },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return moment(data.creadedon).format("DD MMM YYYY hh:mm a")
                    }
                },
                // {

                //     data: 'lostlogin'

                // },

                {

                    data: 'stauts'

                },
                {

                    data: 'action'

                }


            ]
            // [{
            //     data: "ticketno"
            // },
            // {
            //     data: "cusname"
            // },
            // {
            //     data: "mobile"
            // },

            // {
            //     data: "email"
            // },
            // {
            //     data: "My3Numbers"
            // },
            // {
            //     data: "transaction_id"
            // },
            // {
            //     data: "RaffleID"
            // },

            // {
            //     data: "proamt"
            // },


            // {
            //     data: 'agentname'
            // },

            // {
            //     data: "purdate"
            // }
            // ],
            // footerCallback: function (row, data, start, end, display) {
            //     var api = this.api();

            //     // Remove the formatting to get integer data for summation
            //     var intVal = function (i) {
            //         return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            //     };

            //     // Total over all pages
            //     total = api
            //         .column(7)
            //         .data()
            //         .reduce(function (a, b) {
            //             return intVal(a) + intVal(b);
            //         }, 0);

            //     // Total over this page
            //     pageTotal = api
            //         .column(7, {
            //             page: 'current'
            //         })
            //         .data()
            //         .reduce(function (a, b) {
            //             return intVal(a) + intVal(b);
            //         }, 0);

            //     // Update footer
            //     $(api.column(7).footer()).html('' + pageTotal + ' ( ' + total + ' total)');
            // },
        });
        // } else {
        //     // document.getElementById('searcherr').innerHTML = '<div class="alert alert-danger" role="alert">Please Select Draw</div>';
        //     toast('error', 'Please Select Draw');
        // }
        // ccAvenue = '';

    }















    function createDatePricket(id, startd, endd) {


        var start = moment().set({
            hour: 0,
            minute: 0,
            second: 0,
            millisecond: 0
        });
        if (startd != '') {
            start = startd;
        }


        var end = moment().set({
            hour: 23,
            minute: 59,
            second: 59,
            millisecond: 59
        });
        if (endd != '') {
            end = endd;
        }


        function cb(start, end) {
            $('#' + id + ' span').html(start.format("YYYY-MM-DD") + ' - ' + end.format("YYYY-MM-DD"));
        }

        $('#' + id).daterangepicker({
            startDate: start,
            endDate: end,
            // minDate: moment().format("MM/DD/YYYY"),
            minDate: '01/01/2020',
            maxDate: moment().endOf('year').format("MM/DD/YYYY"),
            timePicker: false,
            timePicker24Hour: false,
            timePickerSeconds: false,
            maxSpan: {
                days: 89
            },
            autoUpdateInput: true,
        }, cb);

        cb(start, end);

    }

    function closerequeryStatus(id) {

        $('#' + id).modal('hide');

        viewtable();

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



        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/transaction_services.php");



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

        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/transaction_services.php");

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

        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/transaction_services.php");

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