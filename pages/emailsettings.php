<?php
//    Date       Developer_name      Modifications
//    30-3-2023   Prakash            Email Add Option Development Finished



$pageTitle = 'Email Settings';

?>

<div class="main-content app-content mt-0">
    <div class="side-app">


        <div class="main-container container-fluid">

            <div class="page-header">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a><?= ucwords($pageTitle); ?></h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= ucwords($pageTitle); ?></li>
                    </ol>
                </div>
            </div>

            <div class="row">



                <div class="col-xl-12">
                    <form id="editprofileform">

                        <div class="card">


                            <div class="card-header">
                                <h3 class="card-title" id="th1titlte">Add Email (PHPMailer Config Only)</h3>
                            </div>

                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="host">HOST</label><sup style="color: red;"><strong>*</strong></sup>
                                            <input type="text" id="host" class="form-control" oninput="this.value = this.value.replace(/[^A-Za-z0-9!@#$%^&*.]/g, '');" placeholder="eg. name@example.com" maxlength="70">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="smtpauth">SMTP AUTH</label><sup style="color: red;"><strong>*</strong></sup>
                                            <select class="form-select" id="smtpauth">
                                                <option value="1">TRUE</option>
                                                <option value="0">FALSE</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="username">User Name</label><sup style="color: red;"><strong>*</strong></sup>
                                            <input type="text" class="form-control" id="username" maxlength="70" placeholder="eg. name@example.com">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Password</label><sup style="color: red;"><strong>*</strong></sup>
                                            <input type="password" class="form-control" id="password" maxlength="255" placeholder="eg. test123">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="smtpsecure">SMTP Secure</label><sup style="color: red;"><strong>*</strong></sup>
                                            <select class="form-select" id="smtpsecure">
                                                <option value="tls">TLS</option>
                                                <option value="ssl">SSL</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="port">PORT</label><sup style="color: red;"><strong>*</strong></sup>
                                            <select class="form-select" id="port">
                                                <option value="587">587</option>
                                                <option value="465">465</option>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fromemail">From Email</label><sup style="color: red;"><strong>*</strong></sup>
                                            <input type="email" class="form-control" id="fromemail" placeholder="eg. name@example.com">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fromname">From Name</label><sup style="color: red;"><strong>*</strong></sup>
                                            <input type="text" class="form-control" id="fromname" placeholder="eg. NATIONAL DRAW">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="replyto">Reply To Email</label><sup style="color: red;"><strong>*</strong></sup>
                                            <input type="email" class="form-control" id="replyto" placeholder="eg. name@example.com">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cahrset">Character Set</label>
                                            <input type="text" class="form-control" id="cahrset" placeholder="eg. UTF-8">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="encoding">Encoding</label>
                                            <input type="text" class="form-control" id="encoding" placeholder="eg. base64">
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="card-footer text-end" id="saveEmailBtn">
                                <button type="button" onclick="saveEmail(0)" class="btn btn-success bg-success-gradient my-1">Save</button>
                            </div>
                        </div>
                    </form>
                </div>




            </div>

            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <h3 class="card-title"><?= ucwords('Email List'); ?></h3>
                                </div>

                                <div class="col-lg-6 col-sm-10">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-select" id="status">
                                            <option value="">All</option>
                                            <option value="0">Active</option>
                                            <option value="1">Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-2 mt-5">
                                    <button class="btn btn-info" onclick="viewtable()">Go</button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom dataTable" id="emailListtable" style="width:100%;">

                                    <thead>
                                        <tr>
                                            <th class="wd-15p border-bottom-0">S. NO</th>
                                            <th class="wd-15p border-bottom-0">Host</th>
                                            <th class="wd-15p border-bottom-0">SMTP Auth</th>
                                            <th class="wd-15p border-bottom-0">Username</th>
                                            <th class="wd-15p border-bottom-0">Password</th>
                                            <th class="wd-15p border-bottom-0">SMTP Secure</th>
                                            <th class="wd-15p border-bottom-0">Port</th>
                                            <th class="wd-15p border-bottom-0">From Email</th>
                                            <th class="wd-15p border-bottom-0">From Name</th>
                                            <th class="wd-15p border-bottom-0">Reply To Mail</th>
                                            <th class="wd-15p border-bottom-0">Character Set</th>
                                            <th class="wd-15p border-bottom-0">Encoding</th>
                                            <th class="wd-15p border-bottom-0">status</th>
                                            <th class="wd-15p border-bottom-0">Assigned To</th>
                                            <th class="wd-15p border-bottom-0">Action</th>
                                            <!-- <th class="wd-15p border-bottom-0"></th> -->
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




            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <h3 class="card-title"><?= ucwords('Email Logs'); ?></h3>
                                </div>
                                <div class="col-lg-4  col-sm-10 ">
                                    <span>Select Date</span> &nbsp; <span style="color:red;">*</span>
                                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        <span></span> <i class="fa fa-caret-down"></i>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-2 mt-5">
                                    <button class="btn btn-info" onclick="emailZlog()">Go</button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom dataTable" id="emailLog" style="width:100%;">

                                    <thead>
                                        <tr>
                                            <th class="wd-15p border-bottom-0">ID</th>
                                            <th class="wd-15p border-bottom-0">Email ID</th>
                                            <th class="wd-15p border-bottom-0">Name</th>
                                            <th class="wd-15p border-bottom-0">Reason</th>
                                            <th class="wd-15p border-bottom-0">Message</th>
                                            <th class="wd-15p border-bottom-0">Createdon</th>
                                            <th class="wd-15p border-bottom-0"></th>
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



<!-- Modal -->
<div class="modal fade" id="testmail">

    <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content modal-content-demo">

            <div class="modal-header">
                <h6 class="modal-title">Enter Email</h6>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>

            <div class="modal-body">

                <div class="container">
                    <div class="form-group" id="customerpreference">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="toemial">To Email</label>
                                    <input type="email" class="form-control" id="toemial" placeholder="eg. name@example.com">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer" id="sntbtn">

            </div>

        </div>

    </div>

</div>




<script>
    $(function() {
        createDatePricket('reportrange');
        viewtable();
        emailZlog();
    });

    ///////  New ///////
    function toast(icon, message) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        Toast.fire({
            icon: icon,
            title: message
        });

    }

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

    function validateEmail(e) {
        return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(e)
    }


    function required(inputtx) {
        if (inputtx.length == 0) {
            return false;
        }
        return true;
    }

    function viewtable() {

        var table = $('#emailListtable').DataTable();
        table.destroy();

        var title = 'Blocked My3Number History';
        table = $("#emailListtable").DataTable({
            pageLength: 10,
            // responsive: {
            //     details: {
            //         type: 'column',
            //         target: -1,
            //     }
            // },
            columnDefs: [{
                targets: -1,
                orderable: false,
                searchable: true,
                className: 'control',
            }, {
                targets: 0,
                orderable: false,
                searchable: true,
                className: 'selectall-checkbox',
            }],
            select: {
                style: 'multi',
                selector: 'td:first-child',
            },
            order: [
                [0, 'desc']
            ],
            paging: true,
            searching: true,
            info: true,
            ajax: {
                url: window.location.origin + "/ajax/service/email_config_services.php",
                method: "POST",
                dataSrc: "",
                data: {
                    method: 'emailswitchhistory',
                    status: $('#status').val()
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
                    orientation: 'portrait',
                    pageSize: 'A4',
                    title: title

                }, 'print',
            ],
            columns: [{
                    data: 'id'
                },
                {
                    data: 'host'
                },
                {
                    data: 'smtpauth'
                },
                {
                    data: 'username'
                },
                {
                    data: 'password'
                },
                {
                    data: 'smtpsecure'
                },
                {
                    data: 'port'
                },
                {
                    data: 'fromemail'
                },
                {
                    data: 'fromname'
                },
                {
                    data: 'replyto'
                },
                {
                    data: 'charset'
                },
                {
                    data: 'encode'
                },
                {
                    data: 'status'
                },
                {
                    data: 'dropown'
                },
                {
                    data: 'action'
                },
                // {
                //     data: null,
                //     render: function(data, type, row, meta) {
                //         return ''
                //     }
                // }

            ],
        });
    }

    function saveEmail(id) {
        if (!required($('#host').val())) {
            toast('error', 'Kindly Enter the Host!');
            return false;
        }

        if (!required($('#smtpauth').val())) {
            toast('error', 'Kindly select SMTP Auth!');
            return false;
        }

        if (!required($('#username').val())) {
            toast('error', 'Kindly Enter the Username!');
            return false;
        }

        if (!required($('#password').val())) {
            toast('error', 'Kindly Enter the Password!');
            return false;
        }

        if (!required($('#smtpsecure').val())) {
            toast('error', 'Kindly select SMTP Secure!');
            return false;
        }

        if (!required($('#port').val())) {
            toast('error', 'Kindly Select the Port!');
            return false;
        }

        if (!required($('#fromemail').val())) {
            toast('error', 'Kindly Enter the From Email!');
            return false;
        }

        if (!validateEmail($("#fromemail").val())) {
            toast('error', 'Invaild From Email!');
            return false;
        }

        if (!required($('#fromname').val())) {
            toast('error', 'Kindly Enter the From Name!');
            return false;
        }

        if (!required($('#replyto').val())) {
            toast('error', 'Kindly Enter the Reply To Email!');
            return false;
        }

        if (!validateEmail($("#replyto").val())) {
            toast('error', 'Invaild Reply to Email!');
            return false;
        }

        var formdata = [];
        formdata.push({
            name: 'method',
            value: "add_new_email"
        }, {
            name: 'host',
            value: $('#host').val()
        }, {
            name: 'smtpauth',
            value: $('#smtpauth').val()
        }, {
            name: 'username',
            value: $('#username').val()
        }, {
            name: 'password',
            value: $('#password').val()
        }, {
            name: 'smtpsecure',
            value: $('#smtpsecure').val()
        }, {
            name: 'port',
            value: $('#port').val()
        }, {
            name: 'fromemail',
            value: $('#fromemail').val()
        }, {
            name: 'fromname',
            value: $('#fromname').val()
        }, {
            name: 'replyto',
            value: $('#replyto').val()
        }, {
            name: 'cahrset',
            value: $('#cahrset').val()
        }, {
            name: 'encoding',
            value: $('#encoding').val()
        }, {
            name: 'id',
            value: id
        });


        var post_data = formdata;

        var btn = $('#saveEmailBtn').html();
        $('#saveEmailBtn').html(`<div class="spinner-grow text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-secondary" role="status">
                                        <span class="sr-only">Loading...</span>
                                        </div>
                                        <div class="spinner-grow text-success" role="status">
                                        <span class="sr-only">Loading...</span>
                                        </div>`);
        var onsuccess = function(data) {
            var response = JSON.parse(data);
            if (response != "") {
                if (response.type == '1') {

                    viewtable();
                    toast('success', response.result);
                    location.reload();
                    $('#saveEmailBtn').html(btn);
                } else {
                    toast('error', response.result);
                    $('#saveEmailBtn').html(btn);
                }

            }
        }

        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/email_config_services.php");

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
                location.reload();
            }
        })
    }

    function emailDeactive(str) {

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
                        value: "deactivate_email"
                    }, {
                        name: 'emailID',
                        value: str
                    }, {
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

                    do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/email_config_services.php");
                } else {
                    toast('error', 'Please Fill the Reason');
                }
            }
        });
    }


    function emailActive(str) {

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
                        value: "activate_email"
                    }, {
                        name: 'emailID',
                        value: str
                    }, {
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

                    do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/email_config_services.php");
                } else {

                    toast('error', 'Please Fill the Reason');

                }

            }

        })

    }


    function testEmail(id) {
        
        // alert(id);

        if (id == '') {
            toast('error', 'Kindly Refresh and Try again!');
            return false;
        }
        $('#sntbtn').html(`<button type="button" onclick="sentTestMail(${id})" class="btn btn-success bg-success-gradient my-1">Sent</button>`);
        $('#testmail').modal('show');
    }


    function sentTestMail(id) {
        if (id == '') {
            toast('error', 'Kindly Refresh and Try again!');
            return false;
        }

        if (!required($('#toemial').val())) {
            toast('error', 'Kindly Enter the From Email!');
            return false;
        }

        if (!validateEmail($("#toemial").val())) {
            toast('error', 'Invaild From Email!');
            return false;
        }

        var formdata = [];

        formdata.push({
            name: 'method',
            value: "sentTestMail"
        }, {
            name: 'toemail',
            value: $('#toemial').val()
        }, {
            name: 'id',
            value: id
        });


        var post_data = formdata;
        var btn = $('#sntbtn').html();
        $('#sntbtn').html(`<div class="spinner-grow text-danger" role="status">
                            <span class="sr-only">Loading...</span>
                            </div><div class="spinner-grow text-warning" role="status">
                            <span class="sr-only">Loading...</span>
                            </div><div class="spinner-grow text-success" role="status">
                            <span class="sr-only">Loading...</span>
                            </div>`);
        var onsuccess = function(data) {
            var response = JSON.parse(data);

            if (response != "") {
                if (response.type == 1) {
                    $('#testmail').modal('hide');
                    $('#sntbtn').html(btn);
                    toast('success', response.result);
                } else {
                    $('#testmail').modal('hide');
                    $('#sntbtn').html(btn);
                    toast('error', response.result);
                }
            }

        }

        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/email_config_services.php");

    }


    function EditEmail(id, Host, SMTPAuth, Username, Password, SMTPSecure, Port, setFrom, fromname, AddReplyTo, char_set, Encoding) {
        $('#host').val(Host);
        $('#smtpauth').val(SMTPAuth);
        $('#username').val(Username);
        $('#password').val(Password);
        $('#smtpsecure').val(SMTPSecure);
        $('#port').val(Port);
        $('#fromemail').val(setFrom);
        $('#fromname').val(fromname);
        $('#replyto').val(AddReplyTo);
        $('#cahrset').val(char_set);
        $('#encoding').val(Encoding);
        $('#th1titlte').text('Edit Email');
        $('#saveEmailBtn').html(`<button type="button" onclick="saveEmail(${id})" class="btn btn-success bg-success-gradient my-1">Update</button>`);
        window.scrollTo(0, 0);
    }

    function changeMap(selected, id) {
        if (id == '') {
            toast('error', 'Kindly Refresh and Try Again!');
            return false;
        }


        var formdata = [];
        formdata.push({
            name: 'method',
            value: "updateEmail"
        }, {
            name: 'selected',
            value: selected
        }, {
            name: 'id',
            value: id
        });


        var post_data = formdata;
        var onsuccess = function(data) {
            var response = JSON.parse(data);
            if (response != "") {
                if (response.type == 1) {
                    viewtable();
                    toast('success', response.result);
                } else {
                    viewtable();
                    toast('error', response.result);
                }
            }
        }

        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/email_config_services.php");

    }


    function emailZlog() {

        var table = $('#emailLog').DataTable();
        table.destroy();

        var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
        var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");


        var title = 'Email Reports : (' + formdate + '  to  ' + todate + ')';
        table = $("#emailLog").DataTable({

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
            order: [0, 'desc'],

            paging: true,

            searching: true,

            info: true,

            ajax: {

                url: window.location.origin + "/ajax/service/email_config_services.php",

                method: "POST",

                dataSrc: "",

                data: {

                    method: 'list_email_config_log',

                    formdate: formdate,

                    todate: todate

                }

            },

            dom: 'Bfrtip',

            buttons: [

                'pageLength',

                {

                    extend: 'copyHtml5',

                    title: title

                },

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

                {

                    extend: 'print',

                    title: title



                },



            ],

            columns: [



                {

                    data: 'sno'

                },


                {

                    data: 'email'

                },

                {

                    data: 'name'

                },



                {

                    data: 'reason'

                },
                {

                    data: 'message'

                },
                {

                    data: 'createdon'

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
</script>