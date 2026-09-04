<?php

//1.modifications unknown//

//Date      Developer_name      Modifications//
// 10-04-23 Prashant Changed the max length of CCode from 15 to 20

$pageTitle = "Coupon Code";

?>



<style>
    .add-cr {

        background-image: -webkit-linear-gradient(-45deg, #0e336d 0%, #ce2629 100%) !important;

        border: none;

        color: #ffff;

        border-radius: 56px;

    }

    a.btn.text-danger.btn-sm {
        font-size: 16px;
    }

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

    i.fa.fa-refresh {
        font-size: 20px !important;
        color: lime;
        cursor: pointer;
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



    table.dataTable th.selectall-checkbox,

    table.dataTable td.selectall-checkbox {

        cursor: pointer;

        outline: none;

        text-align: center;

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
                                    <div class="">
                                        <div class="mt-2">
                                            <div class="row">

                                                <div class="col-lg-3 col-sm-6 mb-2">
                                                    <label for="searchtxt"><strong>Coupon Name</strong></label> <br>
                                                    <input class="form-control" type="text" name="cname" id="cname" oninput="this.value = this.value.replace(/[^A-Za-z0-9 ]/g, '');">
                                                </div>

                                                <div class="col-lg-3 col-sm-6 mb-2">
                                                    <label for="searchtxt"><strong>Coupon Code</strong></label> <br>
                                                    <div class="d-flex align-items-center">
                                                        <input class="form-control" type="text" name="ccode" id="ccode" oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '');" minlength="10" maxlength="20" style="text-transform: uppercase">&nbsp;<i onclick="get_new_coupon()" class="fa fa-refresh" aria-hidden="true"></i>
                                                    </div>
                                                </div>


                                                <div class="col-lg-3 col-sm-6 mb-2">
                                                    <label for="searchtxt"><strong>Coupon Used By</strong></label> <br>
                                                    <select id="cusedby" class="form-select">
                                                        <option value="Both" selected>Both</option>
                                                        <option value="Non-Purchased">Non-Purchased</option>
                                                        <option value="Purchased">Purchased</option>
                                                    </select>
                                                </div>

                                                <div class="col-lg-3 col-sm-6 mb-2">
                                                    <label for="searchtxt"><strong>Coupon Limitations</strong></label> <br>
                                                    <input class="form-control" type="text" name="climit" id="climit" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="10">
                                                </div>

                                                <div class="col-lg-3 col-sm-6 mb-2">
                                                    <label for="searchtxt"><strong>Coupon Value</strong></label> <br>
                                                    <select id="camt" class="form-select">
                                                        <?php
                                                        $product = select_query($con, "product", "", "`deletes`='0' ORDER BY `id` ASC", "", "");
                                                        foreach ($product['result'] as $key => $value) {
                                                        ?>
                                                            <option value="<?= $value['id']; ?>"><?= 'AED&nbsp;' . intval($value['rate']); ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="col-lg-5 col-sm-6 mb-2">
                                                    <label><strong>Coupon Start & End Date Time</strong></label>
                                                    <div id="csetime" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"><i class="fa fa-calendar"></i>&nbsp;<span></span> <i class="fa fa-caret-down"></i></div>
                                                </div>








                                                <div class="col-3" id="gcBtn">

                                                    <label></label> <br>

                                                    <button class="btn btn-info" onclick="generate_Coupon()">Generate</button>

                                                </div>



                                            </div>

                                            <br>






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


                            <div class="row">
                                <div class="col-12 mb-2">
                                    <h3 class="card-title"><strong>Coupon History</strong></h3>
                                </div>

                                <div class="col-lg-3 col-md-7  mb-2">
                                    <span>Select Date</span> &nbsp; <span style="color:red;">*</span>
                                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        <span></span> <i class="fa fa-caret-down"></i>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <br>
                                    <button type="submit" id="smslogsearch" class="btn btn-info" onclick="viewtable()">Go</button>
                                </div>

                            </div>
                        </div>



                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered display" id="onlineearntable" style="width:100%;">

                                    <thead>

                                        <tr>

                                            <th class="wd-15p border-bottom-0">Name</th>
                                            <th class="wd-15p border-bottom-0">Code</th>
                                            <th class="wd-15p border-bottom-0">Used By</th>
                                            <th class="wd-20p border-bottom-0">Limit</th>
                                            <th class="wd-20p border-bottom-0">Value (AED)</th>
                                            <th class="wd-20p border-bottom-0">Start Date</th>
                                            <th class="wd-20p border-bottom-0">End Date</th>
                                            <th class="wd-20p border-bottom-0">Created By</th>
                                            <th class="wd-20p border-bottom-0">Ticket Count</th>
                                            <th class="wd-20p border-bottom-0">Status</th>
                                            <th class="wd-20p border-bottom-0">Created At</th>
                                            <th class="wd-20p border-bottom-0">Action</th>
                                            <th class="wd-25p border-bottom-0"></th>
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



<div class="modal fade" id="chooseModal">

    <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content modal-content-demo">

            <div class="modal-header">

                <h6 class="modal-title">Edit Coupon Detail</h6>

                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">×</span>

                </button>

            </div>

            <div class="modal-body">

                <div class="container">
                    <div class="form-group" id="customerpreference">

                        <div class="col-md-12 p-0">
                            <label for="searchtxt"><strong>Coupon Code</strong></label> <br>
                            <div class="d-flex align-items-center">
                                <input class="form-control" type="text" name="ccodeed" id="ccodeed" oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '');" minlength="10" maxlength="15" readonly>
                            </div>
                        </div>


                        <div class="col-md-12 p-0">
                            <label for="searchtxt"><strong>Coupon Used By</strong></label> <br>
                            <select id="cusedbyed" class="form-select">
                                <option value="Both" selected>Both</option>
                                <option value="Non-Purchased">Non-Purchased</option>
                                <option value="Purchased">Purchased</option>
                            </select>
                        </div>

                        <div class="row ">
                            <div class="col-md-12 ">
                                <label for="searchtxt"><strong>Coupon Limitations</strong></label> <br>
                                <input class="form-control" type="text" name="climited" id="climited" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="10">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer" id="confrimbttn">

            </div>

        </div>

    </div>

</div>


<form action="coupon-ticket" id="target" target="_blank" method="post">
    <input type="hidden" name="couponid" id="couponid" value="">
</form>



<script>
    var origin = window.location.origin;
    var url = origin + "/ajax/service/addcredit_services.php";

    $(document).ready(function() {
        get_new_coupon();
        createDatePricket('csetime');
        createDatePricket_old('reportrange');
        viewtable();
    });

    function paymentSuccess(icon, titlestr) {

        Swal.fire({

            title: titlestr,

            icon: icon,

            confirmButtonColor: '#3085d6',

            confirmButtonText: 'OKAY',

            allowOutsideClick: false

        }).then((result) => {

            if (result.isConfirmed) {

                location.reload();

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

    function createDatePricket(id) {

        var start = moment();

        var end = moment();



        function cb(start, end) {
            $('#' + id + ' span').html(start.format("YYYY-MM-DD HH:mm:ss") + ' - ' + end.format("YYYY-MM-DD HH:mm:ss"));
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
            minDate: moment().format("MM/DD/YYYY"),
            timePicker: true,
            timePicker24Hour: true,
            timePickerSeconds: true,
            maxSpan: {
                days: 89
            },
            autoUpdateInput: true,
            // showDropdowns: true,
            // minYear:  moment().format("YYYY"),
            // maxYear: moment().add(1, 'years').format("YYYY"),
            // ranges: {

            //     'Today': [moment(), moment()],

            //     'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],

            //     'Last 7 Days': [moment().subtract(6, 'days'), moment()],

            //     'Last 30 Days': [moment().subtract(29, 'days'), moment()],

            //     'This Month': [moment().startOf('month'), moment().endOf('month')],

            //     'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]

            // }

        }, cb);

        cb(start, end);



    }

    function generate_Coupon() {
        let cname = $('#cname').val();
        let ccode = $('#ccode').val();
        let cusedby = $('#cusedby').val();
        let climit = $('#climit').val();
        let camt = $('#camt').val();
        var formdate = moment($('#csetime').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
        var todate = moment($('#csetime').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");

        if ($('#cname').val().trim().length < 1 || $('#cname').val().trim() == '') {
            toast('warning', 'Kindly Enter the Coupon Name');
            return false;
        }

        if ($('#ccode').val().trim().length < 1 || $('#ccode').val().trim() == '') {
            toast('warning', 'Kindly Enter the Coupon Code');
            return false;
        }

        if ($('#ccode').val().trim().length < 10) {
            toast('warning', 'Coupon Code - Minimum 10 characters');
            return false;
        }

        if ($('#cusedby').val().trim().length < 1 || $('#cusedby').val().trim() == '') {
            toast('warning', 'Kindly Select the Coupon Used By');
            return false;
        }

        if ($('#climit').val().trim().length < 1 || $('#climit').val().trim() == '') {
            toast('warning', 'Kindly Enter the Coupon Limitations');
            return false;
        }

        if ($('#climit').val().trim() < 1) {
            toast('warning', 'Minimum Limit is 1');
            return false;
        }

        if ($('#camt').val().trim().length < 1 || $('#camt').val().trim() == '') {
            toast('warning', 'Kindly Select the Coupon Value');
            return false;
        }

        if (formdate == '' || todate == '') {
            toast('warning', 'Kindly Select the Coupon Start & End Date Time');
            return false;
        }


        var formdata = [];

        formdata.push({

            name: 'method',

            value: "generatedcopoun"

        });

        formdata.push({

            name: 'cname',

            value: cname

        });

        formdata.push({

            name: 'ccode',

            value: ccode.toUpperCase()

        });

        formdata.push({

            name: 'cusedby',

            value: cusedby

        });

        formdata.push({

            name: 'climit',

            value: climit

        });


        formdata.push({

            name: 'camt',

            value: camt

        });


        formdata.push({

            name: 'formdate',

            value: formdate

        });

        formdata.push({

            name: 'todate',

            value: todate

        });

        var post_data = formdata;
        var btn = document.getElementById('gcBtn').innerHTML;
        document.getElementById('gcBtn').innerHTML = '<button class="btn btn-info" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';
        var onsuccess = function(data) {

            var response = JSON.parse(data);

            if (response != "") {

                if (response.type == 1) {
                    document.getElementById('gcBtn').innerHTML = btn;
                    paymentSuccess('success', response.result);

                } else {
                    document.getElementById('gcBtn').innerHTML = btn;
                    toast('error', response.result);

                }

            }

        }

        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/coupon_services.php");

    }

    function get_new_coupon() {
        var formdata = [];
        formdata.push({
            name: 'method',
            value: "get_new_coupon"
        });
        var post_data = formdata;
        var onsuccess = function(data) {
            var response = JSON.parse(data);
            if (response != "") {
                if (response.type == 1) {
                    document.getElementById('ccode').value = response.result;
                }
            }
        }
        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/coupon_services.php");
    }

    function createDatePricket_old(id) {
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

    function viewtable() {

        var table = $('#onlineearntable').DataTable();

        table.destroy();


        var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
        var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");

        var title = 'Coupon History : (' + formdate + '  to  ' + todate + ')';

        table = $("#onlineearntable").DataTable({

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
            paging: true,
            searching: true,
            info: true,
            ajax: {
                url: window.location.origin + "/ajax/service/coupon_services.php",
                method: "POST",
                dataSrc: "",
                data: {
                    method: 'coupon_report',
                    agdate: formdate,
                    todate: todate,
                }
            },
            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                // {
                //     extend: 'copyHtml5',
                //     title: title
                // },
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
                {
                    extend: 'print',
                    title: title
                },
            ],
            columns: [{
                    data: "name"
                },
                {
                    data: "code"
                },

                {
                    data: "usedby"
                },
                {
                    data: "limit"
                },
                {
                    data: "value"
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return '<input class="form-control" type="text" value="' + data.startdate + '" readonly>'
                    }
                    // data: "startdate"
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return '<input class="form-control" type="text" value="' + data.enddate + '" readonly>'
                    }
                    // data: "enddate"
                },
                {
                    data: "generateby"
                },
                {
                    data: "ticketCount"
                },
                {
                    data: "status"
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return '<input class="form-control" type="text" value="' + data.created_at + '" readonly>'
                    }

                    // data: "created_at"
                },
                {
                    data: "action"
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return ''
                    }
                }
            ],

        });

    }

    function couponDeactive(str) {

        swal.fire({

            title: 'Deactivate Reason',

            input: 'textarea',

            showCancelButton: true,

            allowOutsideClick: false

        }).then(function(result) {

            if (result.isConfirmed) {

                if (result.value != '') {

                    var formdata = [];

                    formdata.push({

                        name: 'method',

                        value: "deactivate_coupon"

                    });

                    formdata.push({

                        name: 'couponid',

                        value: str

                    });

                    formdata.push({

                        name: 'reason',

                        value: result.value

                    });

                    var post_data = formdata;

                    var onsuccess = function(data) {
                        var response = JSON.parse(data);

                        if (response != "") {
                            if (response.type == 1) {
                                paymentSuccess('success', response.result);
                            } else {
                                toast('error', response.result);
                            }
                        }

                    }

                    do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/coupon_services.php");



                } else {

                    toast('error', 'Please Fill the Reason');

                }

            }

        })

    }

    function editCoupon(id) {
        if (id == '') {
            toast('error', 'Coupon Code Missing Kindly Refresh and try again!');
            return false;
        }

        var formdata = [];
        formdata.push({
            name: 'method',
            value: "edit_get_details"
        });
        formdata.push({
            name: 'id',
            value: id
        });
        var post_data = formdata;
        var onsuccess = function(data) {
            var response = JSON.parse(data);
            if (response != "") {
                if (response.type == 1) {
                    $('#ccodeed').val(response.c_code);

                    $('#cusedbyed').val(response.c_used_by);

                    $('#climited').val(response.c_limit);
                    $('#confrimbttn').html('<button class="add-cr" onclick="saveChanges(' + response.coupon + ')">Save</button>');
                    $('#chooseModal').modal('show');
                } else {
                    toast('error', response.result);
                }
            }
        }
        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/coupon_services.php");



    }

    function saveChanges(id) {

        var cusedby = $('#cusedbyed').val();
        let climit = $('#climited').val();
        if (id == '') {
            toast('error', 'Coupon Code Missing Kindly Refresh and try again!');
            return false;
        }

        if ($('#cusedbyed').val().trim().length < 1 || $('#cusedbyed').val().trim() == '') {
            toast('warning', 'Kindly Select the Coupon Used By');
            return false;
        }

        if ($('#climited').val().trim().length < 1 || $('#climited').val().trim() == '') {
            toast('warning', 'Kindly Enter the Coupon Limitations');
            return false;
        }

        if ($('#climited').val().trim() < 1) {
            toast('warning', 'Minimum Limit is 1');
            return false;
        }

        var formdata = [];
        formdata.push({
            name: 'method',
            value: "saveChanges"
        });
        formdata.push({
            name: 'id',
            value: id
        });
        formdata.push({

            name: 'cusedby',

            value: cusedby

        });

        formdata.push({

            name: 'climit',

            value: climit

        });

        var btn = $('#confrimbttn').html();
        $('#confrimbttn').html(`<div class="spinner-grow text-danger" role="status"><span class="sr-only">Loading...</span></div><div class="spinner-grow text-warning" role="status"><span class="sr-only">Loading...</span></div><div class="spinner-grow text-dark" role="status"><span class="sr-only">Loading...</span></div>`);
        var post_data = formdata;
        var onsuccess = function(data) {
            var response = JSON.parse(data);
            if (response != "") {
                if (response.type == 1) {
                    $('#confrimbttn').html(btn);
                    $('#chooseModal').modal('hide');
                    toast('success', response.result);
                    viewtable();
                } else {
                    $('#confrimbttn').html(btn);
                    toast('error', response.result);
                }
            }
        }
        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/coupon_services.php");

    }

    function couponActive(str) {

        swal.fire({

            title: 'Activate Reason',

            input: 'textarea',

            showCancelButton: true,

            allowOutsideClick: false

        }).then(function(result) {

            if (result.isConfirmed) {

                if (result.value != '') {

                    var formdata = [];

                    formdata.push({

                        name: 'method',

                        value: "activate_coupon"

                    });

                    formdata.push({

                        name: 'couponid',

                        value: str

                    });

                    formdata.push({

                        name: 'reason',

                        value: result.value

                    });

                    var post_data = formdata;

                    var onsuccess = function(data) {
                        var response = JSON.parse(data);

                        if (response != "") {
                            if (response.type == 1) {
                                paymentSuccess('success', response.result);
                            } else {
                                toast('error', response.result);
                            }
                        }

                    }

                    do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/coupon_services.php");
                } else {

                    toast('error', 'Please Fill the Reason');

                }

            }

        })

    }

    function viewCouponHistory(id, tcount) {
        if (tcount > 0) {
            $('#couponid').val(id);
            $('#target').submit();
            console.log(id);
        }
    }
</script>