<!--
1.modifications unknown


Date      Developer_name      Modifications
10-4-2023  Prakash             Merge the Payment Status History and Payment History table
25-5-2023  Prakash             Notification Alert Added
-->

<?php 
    $roll_id =  select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'", "roll_id", "");
    
    if($roll_id == 1){
        $contenTnot = '<div class="alert alert-info" role="alert">Note: <br>Generate consolidated Report in Upcoming Draw (Ticket sale closed) Or Generate consolidated Report in Draw Announcement (Draw Announced) </div>';
    } else {
        $contenTnot = '<div class="alert alert-info" role="alert">Note: <br>Generate consolidated Report in Completed Draw</div>';
    }
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

                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Network History</h1>

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
                                    <h3 class="card-title"><strong>Upload Network History</strong></h3>
                                </div>


                                <div class="col-lg-6 col-md-6">
                                    <label for="networkreport" class="form-label">Choose Network Report</label>
                                    <input class="form-control" type="file" id="networkreport" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                </div>
                                <div class="col-lg-6 col-md-6 mt-6" id="upbtn">
                                    <button type="submit" id="smslogsearch" class="btn btn-info" onclick="UploadReport()">Upload</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <label>Uploading file format</label>
                                    <a href="<?= $adminurl; ?>xlsx/network-report-format.xlsx" download="">

                                        <button class="btn"><i class="fa fa-download"></i> Download</button>

                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12" id="successinfoMessage">
                                  
                                </div>
                            </div>
                        </div>
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <h3 class="card-title"><strong>Network History</strong></h3>
                                </div>

                                <div class="col-lg-6 col-sm-7 col-12">
                                    <span>Select Date</span>
                                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        <span></span> <i class="fa fa-caret-down"></i>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-5 col-12 mt-5" id="upbtn">
                                    <button type="submit" id="smslogsearch" class="btn btn-info" onclick="viewtable()">Go</button>
                                </div>


                            </div>
                        </div>



                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="onlineearntable" style="width:100%;">

                                    <thead>

                                        <tr>

                                            <th class="wd-15p border-bottom-0">Merchant Defined Order Number</th>
                                            <th class="wd-15p border-bottom-0">Order Status</th>
                                            <th class="wd-15p border-bottom-0">Payment Status</th>
                                            <th class="wd-15p border-bottom-0">Date & Time</th>
                                            <th class="wd-15p border-bottom-0">Amount</th>
                                            <th class="wd-20p border-bottom-0">Card Holder Email</th>
                                            <th class="wd-20p border-bottom-0">Captured Date</th>
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
    var ccAvenue = '<?= $_POST['ccAvenue']; ?>';


    $(function() {
        createDatePricket('reportrange');
        viewtable();

        // if (ccAvenue != '') {
        //     viewtable();
        // }
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
    }
    // function closerequeryStatus(id) {

    //     $('#' + id).modal('hide');

    //     viewtable();

    // }


    function viewtable() {
        var table = $('#onlineearntable').DataTable();
        table.destroy();

        var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
        var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");

        var title = 'Network Status History';
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
                    method: 'Network_history',
                    formdate: formdate,
                    todate: todate,
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
                {

                    data: 'status'

                },
                {
                    data: 'paystatus'
                },
                {
                    data: 'date'
                },
                {

                    data: 'amt'

                },


                {

                    data: 'email'

                },
                {

                    data: 'capdate'

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
        ccAvenue = '';

    }







    // function requery(transid) {

    //     var formdata = [];

    //     formdata.push({

    //         name: 'method',

    //         value: "Check_Payemnt_Status"

    //     });



    //     formdata.push({

    //         name: 'transid',

    //         value: transid

    //     });



    //     var post_data = formdata;

    //     var onsuccess = function(data) {



    //         var response = JSON.parse(data);

    //         if (response != "") {

    //             if (response.type == 1) {

    //                 document.getElementById('PaymentErr').innerHTML = '<div class="alert alert-success" role="alert">' + response.result + '</div>';

    //                 document.getElementById('previewBTN').innerHTML = response.output;

    //                 $('#requeryStatus').modal('show');

    //             } else {

    //                 document.getElementById('PaymentErr').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';

    //                 $('#requeryStatus').modal('show');

    //             }



    //         }

    //     }



    //     do_ajax_call(post_data, onsuccess, url);



    // }





    // function otpreview(transid) {

    //     $('#requeryStatus').modal('hide');

    //     $('#otpreviewModal').modal('show');

    //     document.getElementById('otticketview').innerHTML = '<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>';

    //     var formdata = [];

    //     formdata.push({

    //         name: 'method',

    //         value: "otpreview"

    //     });



    //     formdata.push({

    //         name: 'transid',

    //         value: transid

    //     });



    //     var post_data = formdata;

    //     var onsuccess = function(data) {

    //         var response = JSON.parse(data);

    //         if (response != "") {

    //             if (response.type == 1) {

    //                 document.getElementById('otticketview').innerHTML = response.output;

    //                 document.getElementById('otbtn').innerHTML = response.otreigiterbtn;

    //             } else {

    //                 document.getElementById('otticketview').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';

    //             }

    //         }

    //     }

    //     do_ajax_call(post_data, onsuccess, url);

    // }


    function UploadReport() {


        let file = document.getElementById('networkreport').value;

        if (file != '') {





            var formData = new FormData();



            formData.append('method', 'network_report');

            formData.append('mfile', networkreport.files[0]);


            var btn = document.getElementById('upbtn').innerHTML;
            document.getElementById('upbtn').innerHTML = `<div class="spinner-grow text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-secondary" role="status">
                                    <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-success" role="status">
                                    <span class="sr-only">Loading...</span>
                                    </div>`;

            var i = 1;
            var sec = 180;
            var t = setInterval(function() {
                if (i == sec) {
                    document.getElementById('upbtn').innerHTML = btn;
                    toast('success', 'Kindly Reupload The File!');
                    clearInterval(t);
                }
            });

            $.ajax({

                url: window.location.origin + '/xlsx/networkreport.php',

                type: 'post',

                data: formData,



                success: function(data) {

                    var response = JSON.parse(data);

                    if (response != "") {

                        if (response.type == 1) {
                            clearInterval(t);
                            document.getElementById('upbtn').innerHTML = btn;
                            
                            
                            $('#successinfoMessage').html(`<?= $contenTnot; ?>`);
                            
                            
                            toast('success', response.result);
                        } else {
                            clearInterval(t);
                            document.getElementById('upbtn').innerHTML = btn;
                            toast('error', response.result);

                        }

                    }



                },

                processData: false,

                contentType: false

            });

        } else {

            toast('error', 'Select the file.');


        }



    }



    // function otregisteroffline(transid, drawid) {

    //     document.getElementById('otbtn').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';

    //     var formdata = [];

    //     formdata.push({

    //         name: 'method',

    //         value: "otregisteroffline"

    //     });



    //     formdata.push({

    //         name: 'transid',

    //         value: transid

    //     });



    //     formdata.push({

    //         name: 'drawid',

    //         value: drawid

    //     });



    //     var post_data = formdata;

    //     var onsuccess = function(data) {

    //         var response = JSON.parse(data);

    //         if (response != "") {

    //             if (response.type == 1) {

    //                 document.getElementById('otbtn').innerHTML = '<button class="btn ripple btn-danger" onclick="closerequeryStatus(' + "'otpreviewModal'" + ')" data-bs-dismiss="modal" type="button">Close</button>';

    //                 document.getElementById('otticketview').innerHTML = '<div class="alert alert-success" role="alert">' + response.result + '</div>';

    //             } else {



    //                 document.getElementById('otticketview').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';

    //                 document.getElementById('otbtn').innerHTML = response.otreigiterbtn;

    //             }

    //         }

    //     }

    //     do_ajax_call(post_data, onsuccess, url);

    // }

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




    // function changeDrawRange(id) {

    //     if (id == '') {
    //         toast('error', 'Kindly Select The Draw');
    //         return false;
    //     }


    //     var formdata = [];

    //     formdata.push({

    //         name: 'method',

    //         value: "get_draw_date"

    //     }, {

    //         name: 'drawid',

    //         value: id

    //     });


    //     var post_data = formdata;

    //     var onsuccess = function(data) {



    //         var response = JSON.parse(data);

    //         if (response != "") {

    //             if (response.type == 1) {

    //                 createDatePricket('reportrange', moment(response.start).set({
    //                     hour: 0,
    //                     minute: 0,
    //                     second: 0,
    //                     millisecond: 0
    //                 }), moment(response.end).set({
    //                     hour: 23,
    //                     minute: 59,
    //                     second: 59,
    //                     millisecond: 59
    //                 }))

    //             } else {

    //                 toast('error', response.result);
    //             }



    //         }

    //     }



    //     do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/transaction_services.php");




    // }
</script>