<?php

/**

 *      Date            Developer_name      Modifications

 *      30/05/2023      Prashant            Affiliate Coupon Code

 *

 * */

$pageTitle = "Add Kiosk Machines";
$get_affiliate_user = select_query($con, "user_register", "", "`deletes` = '0' AND `roll_id` = '7'", "", "");

?>







<style>
    .add-cr {
        background-image: -webkit-linear-gradient(-45deg, #0e336d 0, #ce2629 100%) !important;
        border: none;
        color: #ffff;
        border-radius: 56px
    }

    .btn-outline-primary:hover {
        color: #fff;
        background: #2f6de5;
        border-color: #165ce3
    }

    a.btn.text-danger.btn-sm {
        font-size: 16px
    }

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

    i.fa.fa-refresh {
        font-size: 20px !important;
        color: #0f0;
        cursor: pointer
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

    table.dataTable td.selectall-checkbox,
    table.dataTable th.selectall-checkbox {
        cursor: pointer;
        outline: 0;
        text-align: center
    }

    textarea {
        height: 100px;
        padding: 12px 20px;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
        background-color: #f8f8f8;
        font-size: 16px;
        resize: none
    }

    #gcBtn,
    .swal2-styled {
        padding: 5px 18px 18px
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
                                                    <label for="searchtxt"><strong>Kiosk ID</strong><i style="color: red;">*</i></label>
                                                    <div class="pt-1">
                                                        <input class="form-control" type="text" name="cname" id="cname" oninput="this.value = this.value.replace(/[^A-Za-z0-9!@#$%^&*()]/g, '');" maxlength="30">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-sm-6 mb-2">
                                                    <label for="searchtxt"><strong>Kiosk Location</strong><i style="color: red;">*</i></label>
                                                    <div class="pt-1">
                                                        <input class="form-control" type="text" name="totcoupon" id="totcoupon" oninput="this.value = this.value.replace(/[^A-Za-z0-9!@#$%^&*() ]/g, '');" maxlength="70">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-sm-6 mb-2">
                                                    <label for="searchtxt"><strong>Area</strong><i style="color: red;">*</i></label>
                                                    <div class="pt-1">
                                                        <input class="form-control" type="text" name="toarea" id="toarea" oninput="this.value = this.value.replace(/[^A-Za-z0-9!@#$%^&*() ]/g, '');" maxlength="70">
                                                    </div>
                                                </div>


                                                <div class="col-lg-3 col-sm-6 mb-2">
                                                    <label for="searchtxt"><strong>Country</strong><i style="color: red;">*</i></label>
                                                    <div class="pt-1">
                                                        <input class="form-control" type="text" name="tocountry" id="tocountry" oninput="this.value = this.value.replace(/[^A-Za-z0-9!@#$%^&*() ]/g, '');" maxlength="70">
                                                    </div>
                                                </div>

                                                <div class="col-3 mt-2" id="gcBtn">
                                                    <label></label> <br>
                                                    <button class="btn btn-info" onclick="addKiosk()">Add</button>
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



            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">

                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">

                            <div class="card overflow-hidden">

                                <div class="card-body">

                                    <div class="">

                                        <div class="mt-2">

                                            <div class="row">
                                                <div class="col-12 mb-2">
                                                    <h3 class="card-title"><strong>Upload Kiosk Details</strong></h3>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <label for="kioskFile" class="form-label">Choose Kiosk File</label>
                                                    <input class="form-control" type="file" id="kioskFile" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                                </div>
                                                <div class="col-lg-6 col-md-6 mt-6" id="upbtn">
                                                    <button type="submit" id="smslogsearch" class="btn btn-info" onclick="UploadReport()">Upload</button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12">
                                                    <label>Uploading file format</label>
                                                    <a href="xlsx/kiosk-format.xlsx" download="">
                                                        <button class="btn"><i class="fa fa-download"></i> Download</button>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>







                                    </div>







                                </div>







                            </div>







                        </div>







                    </div>







                </div>







            </div>




            <!-- ROW-4 -->







            <div class="row row-sm">



                <div class="col-lg-12">



                    <div class="card">



                        <div class="card-header">





                            <div class="row">

                                <div class="col-12 mb-2">
                                    <h3 class="card-title"><strong>Kiosk Machines List</strong></h3>
                                </div>










                            </div>

                        </div>







                        <div class="card-body">



                            <div class="table-responsive">



                                <table class="table table-bordered display" id="onlineearntable" style="width:100%;">



                                    <thead>



                                        <tr>

                                            <th class="wd-15p border-bottom-0">Kiosk ID</th>
                                            <th class="wd-15p border-bottom-0">Location</th>
                                            <th class="wd-15p border-bottom-0">Area</th>
                                            <th class="wd-15p border-bottom-0">Country</th>
                                            <th class="wd-15p border-bottom-0">Status</th>
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
<!-- Modal -->








<div class="modal fade" id="KioskEditModal">



    <div class="modal-dialog modal-sm" role="document">



        <div class="modal-content modal-content-demo">



            <div class="modal-header">



                <h6 class="modal-title">Edit Kiosk Details</h6>



                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">



                    <span aria-hidden="true">×</span>



                </button>



            </div>



            <div class="modal-body">



                <div class="container">

                    <div class="form-group" id="customerpreference">

                        <div class="col-12">
                            <label for="searchtxt"><strong>Kiosk Location</strong><i style="color: red;">*</i></label>
                            <div class="">
                                <input class="form-control" type="text" name="Edittotcoupon" id="Edittotcoupon" oninput="this.value = this.value.replace(/[^A-Za-z0-9!@#$%^&*() ]/g, '');" maxlength="70">
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="searchtxt"><strong>Area</strong><i style="color: red;">*</i></label>
                            <div class="">
                                <input class="form-control" type="text" name="Edittoarea" id="Edittoarea" oninput="this.value = this.value.replace(/[^A-Za-z0-9!@#$%^&*() ]/g, '');" maxlength="70">
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="searchtxt"><strong>Country</strong><i style="color: red;">*</i></label>
                            <div class="">
                                <input class="form-control" type="text" name="Edittocountry" id="Edittocountry" oninput="this.value = this.value.replace(/[^A-Za-z0-9!@#$%^&*() ]/g, '');" maxlength="70">
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













<script>
    var origin = window.location.origin;

    var url = origin + "/ajax/service/addcredit_services.php";

    $(function() {
        viewtable();
    });



    function UpdateKiosk(id) {
        try {
            if (id == null || id == undefined) {
                toast('error', 'The Kiosk ID Is Missing!');
                return false;
            }

            if ($('#Edittotcoupon').val() == undefined || $('#Edittotcoupon').val() == null || $('#Edittotcoupon').val() == '' || $('#Edittotcoupon').val().trim().length < 1 || $('#Edittotcoupon').val().trim() == '') {
                toast('error', 'Kindly Enter The Kiosk Location!');
                return false;
            }

            if ($('#Edittoarea').val() == undefined || $('#Edittoarea').val() == null || $('#Edittoarea').val() == '' || $('#Edittoarea').val().trim().length < 1 || $('#Edittoarea').val().trim() == '') {
                toast('error', 'Kindly Enter The Kiosk Area!');
                return false;
            }

            if ($('#Edittocountry').val() == undefined || $('#Edittocountry').val() == null || $('#Edittocountry').val() == '' || $('#Edittocountry').val().trim().length < 1 || $('#Edittocountry').val().trim() == '') {
                toast('error', 'Kindly Enter The Kiosk Country!');
                return false;
            }

            var formdata = [];

            formdata.push({
                name: 'method',
                value: "UpdateKiosk"
            }, {
                name: 'id',
                value: id
            }, {
                name: 'location',
                value: $('#Edittotcoupon').val()
            }, {
                name: 'area',
                value: $('#Edittoarea').val()
            }, {
                name: 'country',
                value: $('#Edittocountry').val()
            });

            var post_data = formdata;

            var btn = $('#confrimbttn').html();
            $('#confrimbttn').html(`<button class="btn btn-info" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>`);

            var onsuccess = function(data) {
                var response = JSON.parse(data);
                if (response != "") {
                    if (response.type == 1) {
                        $('#confrimbttn').html(btn);
                        $('#KioskEditModal').modal('hide');
                        toast('success', response.result);
                        viewtable();
                    } else {
                        $('#confrimbttn').html(btn);
                        toast('error', response.result);
                    }
                }
            }

            do_ajax_call(post_data, onsuccess, origin + "/ajax/service/addKiosk_service.php");
        } catch (error) {
            console.log('Error: ' + error.message);
        }
    }





    function paymentSuccess(o, t) {
        Swal.fire({
            title: t,
            icon: o,
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OKAY",
            allowOutsideClick: !1
        }).then(o => {
            o.isConfirmed && location.reload()
        })
    }



    function toast(e, t) {
        let i = Swal.mixin({
            toast: !0,
            position: "top-end",
            showConfirmButton: !1,
            timer: 5e3,
            timerProgressBar: !0,
            didOpen(e) {
                e.addEventListener("mouseenter", Swal.stopTimer), e.addEventListener("mouseleave", Swal.resumeTimer)
            }
        });
        i.fire({
            icon: e,
            title: t
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

                days: 364

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









        var title = 'Kiosk Machines List';



        table = $("#onlineearntable").DataTable({

            destroy: true,

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

                searchable: true,

                className: 'selectall-checkbox',

            }],

            select: {

                style: 'multi',

                selector: 'td:first-child',

            },

            paging: true,

            searching: true,

            info: true,
            order: [
                [
                    5, 'desc'
                ]
            ],


            ajax: {

                url: origin + "/ajax/service/addKiosk_service.php",

                method: "POST",

                dataSrc: "",

                data: {

                    method: 'kioskMachineList',


                }

            },

            dom: 'Bfrtip',

            buttons: [

                'pageLength',


                {

                    extend: 'excelHtml5',

                    title: title

                },


            ],

            columns: [


                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.kiosk_id
                    }
                },

                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.location
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return (data.area != null && data.area != undefined && data.area != '') ? data.area : ''
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return (data.country != null && data.country != undefined && data.country != '') ? data.country : ''
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        let statusMsg = (data.is_active === '0') ? 'Active' : 'Deactive';

                        let html = `<div class="dropdown">

                              <button class="${((data.is_active === '0') ? 'btn btn-success' : 'btn btn-danger')} dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                 <strong>${statusMsg}</strong>
                              </button>

                              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="">
                            ${((data.is_active === '0') ? `<li><a class="dropdown-item" href="javascript:void(0);" onclick="changeStatusKiosk(1, ${data.id})"><strong>Deactive</strong></a></li>` : `<li><a class="dropdown-item" href="javascript:void(0);" onclick="changeStatusKiosk(0, ${data.id})"><strong>Active</strong></a></li>`)}

                              </ul>

                            </div>`;

                        return html
                    }
                },

                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return moment(data.createdon).format("DD MMM YYYY hh:mm a")
                    }
                },



                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return `<a href="javascript:void(0);" onclick="editForm(${data.id}, '${data.location}', '${data.area}', '${data.country}')" style="cursor: pointer;"><span class="fe fe-edit" style="color: #576025db; font-size: 16px;font-weight: bold;">&nbsp;Edit</span></a>`
                    }
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

    function editForm(id, location, area, country) {

        if (location != undefined && location != null) {
            $('#Edittotcoupon').val((location != 'null') ? location : '');
        }

        if (area != undefined && area != null) {
            $('#Edittoarea').val((area != 'null') ? area : '');
        }

        if (country != undefined && country != null) {
            $('#Edittocountry').val((country != 'null') ? country : '');
        }

        if (id != undefined && id != null && id != '') {
            $('#confrimbttn').html(`<button type="button" onclick="UpdateKiosk(${id})" class="btn btn-outline-primary">Save</button>`);
            $('#KioskEditModal').modal('show');
        } else {
            toast('error', 'The ID has been missing. So Couldn`t update this Kiosk Details');
            return false;
        }

    }






    function addKiosk() {


        if ($('#cname').val() == undefined || $('#cname').val() == null || $('#cname').val() == '' || $('#cname').val().trim().length < 1 || $('#cname').val().trim() == '') {
            toast('error', 'Kindly Enter The Kiosk id!');
            return false;
        }

        if ($('#totcoupon').val() == undefined || $('#totcoupon').val() == null || $('#totcoupon').val() == '' || $('#totcoupon').val().trim().length < 1 || $('#totcoupon').val().trim() == '') {
            toast('error', 'Kindly Enter The Kiosk location!');
            return false;
        }

        if ($('#toarea').val() == undefined || $('#toarea').val() == null || $('#toarea').val() == '' || $('#toarea').val().trim().length < 1 || $('#toarea').val().trim() == '') {
            toast('error', 'Kindly Enter The Kiosk Area!');
            return false;
        }

        if ($('#tocountry').val() == undefined || $('#tocountry').val() == null || $('#tocountry').val() == '' || $('#tocountry').val().trim().length < 1 || $('#tocountry').val().trim() == '') {
            toast('error', 'Kindly Enter The Kiosk Country!');
            return false;
        }

        var formdata = [];

        formdata.push({
            name: 'method',
            value: "addKiosk"
        }, {
            name: 'kioskID',
            value: $('#cname').val()
        }, {
            name: 'location',
            value: $('#totcoupon').val()
        }, {
            name: 'area',
            value: $('#toarea').val()
        }, {
            name: 'country',
            value: $('#tocountry').val()
        });


        var post_data = formdata;

        var btn = document.getElementById('gcBtn').innerHTML;

        document.getElementById('gcBtn').innerHTML = '<button class="btn btn-info" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';

        var onsuccess = function(data) {



            var response = JSON.parse(data);



            if (response != "") {

                if (response.type == 1) {
                    document.getElementById('gcBtn').innerHTML = btn;
                    toast('success', response.result);
                    viewtable();
                } else {
                    document.getElementById('gcBtn').innerHTML = btn;
                    toast('error', response.result);
                }

            }



        }



        do_ajax_call(post_data, onsuccess, origin + "/ajax/service/addKiosk_service.php");


    }


    function changeStatusKiosk(status, ID) {

        if (status == null || status == undefined) {
            toast('error', 'The Kiosk Status Is Missing!');
            return false;
        }



        if (ID == null || ID == undefined) {
            toast('error', 'The Kiosk ID Is Missing!');
            return false;
        }

        var formdata = [];

        formdata.push({
            name: 'method',
            value: "changeStatusKiosk"
        }, {
            name: 'status',
            value: status
        }, {
            name: 'KioskID',
            value: ID
        });


        var post_data = formdata;


        var onsuccess = function(data) {



            var response = JSON.parse(data);



            if (response != "") {

                if (response.type == 1) {

                    toast('success', response.result);
                    viewtable();
                } else {

                    toast('error', response.result);
                }

            }



        }



        do_ajax_call(post_data, onsuccess, origin + "/ajax/service/addKiosk_service.php");


    }

























    function UploadReport() {


        let file = $('#kioskFile').val();

        if (file == '') {
            toast('error', 'Kindly choose excel file!');
            return false;
        }

        if (kioskFile.files[0].name == '') {
            toast('error', 'Kindly choose excel file!');
            return false;
        }

        var formData = new FormData();
        formData.append('method', 'kiosk_upload');
        formData.append('kioskFile', kioskFile.files[0]);
        // formData.append('memid', <?= $_SESSION['memid']; ?>);
        
        var btn = $('#upbtn').html();
        $('#upbtn').html(`<button class="btn btn-primary" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span class="sr-only">Loading...</span>
                            </button>`);

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

            url: origin + '/xlsx/KioskUpload.php',
            type: 'post',
            data: formData,
            success: function(data) {

                var response = JSON.parse(data);
                if (response != "") {

                    if (response.type == 1) {
                        clearInterval(t);
                        $('#upbtn').html(btn);
                        toast('success', response.result);
                        viewtable();
                    } else {
                        clearInterval(t);
                        $('#upbtn').html(btn);
                        toast('error', response.result);
                    }
                }
            },

            processData: false,
            contentType: false
        });
    }
</script>