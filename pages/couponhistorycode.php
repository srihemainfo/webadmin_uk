<?php
/**
 *      Date            Developer_name      Modifications
 *      12/08/2023      Divya            coupon histroy report with filters
 *      16/09/2023      divya             page title and excel download name changes
 *      08/09/2023      devanathan        THE WORD UNDEFINED IS OCCURING title of excel download name
 * 
 * */

$pageTitle = "Coupon History Report ";

$get_affiliate_user = select_query($con, "user_register", "", "`deletes` = '0' AND `roll_id` = '7'", "", "");

$get_coupon = select_query($con, "couponcode", "", "`deletes` = '0' AND  c_createdfor IS NOT NULL  Group by c_name ", "", "");

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

    .swal2-styled,#gcBtn{
        padding: 5px 18px 18px 18px;
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



                        <li class="breadcrumb-item active" aria-current="page">Coupon History Report</li>



                    </ol>



                </div>



            </div>



            <!-- PAGE-HEADER END -->


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

                                <div class="col-lg-4 col-md-8  mb-2">
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
                                 <div class="col-lg-4 col-md-8  mb-2">
                                            <span>Search Coupon </span>
                                            <input class="form-control" type="text" oninput="viewtable($(this).val())" id="searchTxt" placeholder="Coupon name /Coupon Code ">
                                        </div>
                                        
                                         
                 
                    <div class="col-lg-4 col-md-8 mb-2">
                        <label for="searchtxt"><strong>Select Coupon Type</strong></label><span style="color: red;"></span>
                        <select oninput="viewtable($(this).val())" id="cpname" class="form-select" >
                            <option value="">Select Coupon Name</option>
                            
                            <?php if ($get_coupon['nr'] > 0) { foreach ($get_coupon ['result'] as $key => $value) { ?>
                            <option value="<?= $value['c_name'] ?>"><?= $value['c_name']?></option>
                            <?php } } ?>
                        </select>
                    </div>

                            </div>
                        </div>



                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered display" id="onlineearntable" style="width:100%;">

                                    <thead>

                                        <tr>

                                            <th class="wd-15p border-bottom-0">Coupon Name</th>
                                            <th class="wd-15p border-bottom-0">Coupon Code</th>
                                            <th class="wd-15p border-bottom-0">Coupon Type</th>
                                            <th class="wd-20p border-bottom-0">Limit</th>
                                            <th class="wd-20p border-bottom-0">Value (AED)</th>
                                            <th class="wd-20p border-bottom-0">Coupon Start Date</th>
                                            <th class="wd-20p border-bottom-0">Coupon validitity</th>
                                            <th class="wd-20p border-bottom-0">Ticket Count</th>
                                            <th class="wd-20p border-bottom-0">Status</th>
                                            <th class="wd-20p border-bottom-0">User Mobile Number</th>
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

<div class="modal fade" id="exampleModal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Download Coupon </h6>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="container">
                 <form method="POST" action="#">
                    <div class="col-lg-12 col-sm-12 mb-2">
                        <label for="searchtxt"><strong>Select Affiliate User</strong></label><span style="color: red;">*</span>
                        <select id="affiliate_user_id" class="form-select" required>
                            <option value="">Select User</option>
                            <?php if ($get_affiliate_user['nr'] > 0) { foreach ($get_affiliate_user['result'] as $key => $value) { ?>
                            <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                            <?php } } ?>
                        </select>
                    </div>
                    <div class="col-lg-12 col-sm-12 mb-5">
                        <label for="searchtxt"><strong>Date (Coupon Created Date)</strong></label>
                        <input type="date" name="date" id="affiliate_date" class="form-control">
                    </div>
                    <div class="col-lg-12 col-sm-12 mb-2">
                        <button type="submit" class="btn btn-success" id="downloadCouponCode">Download</button>
                    </div>
                    </form>   
                </div>
            </div>
        </div>
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
        
        $('.exampleModal').click(function () {
            $('#exampleModal').modal('show');
        });

        //get_new_coupon();
        createDatePricket('csetime');
        createDatePricket_old('reportrange');
        viewtable();

        $('body').on('click','#downloadCouponCode',function(e){
            e.preventDefault();

            if ($('#affiliate_c_name').val().trim() == '') {
                toast('warning', 'Please Select User');
                return false;
            }

            var formdata = [];
            formdata.push({
                name: 'method',
                value: "downloadaffiliatecopoun"
            });
            formdata.push({
                name: 'date',
                value: $('#affiliate_date').val()
            });
            
            formdata.push({
                name: 'c_name',
                value: $('#affiliate_c_name').val()
            });

            var post_data = formdata;
            var btn = document.getElementById('downloadCouponCode').innerHTML;
            document.getElementById('downloadCouponCode').innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...';

            var onsuccess = function(data) {
                var response = JSON.parse(data);
                console.log(response)
                var $a = $("<a>");
                $a.attr("href",response.file);
                // $a.attr('target', '_blank');
                $("body").append($a);
                $a.attr("download",response.filename);
                $a[0].click();
                $a.remove();
                /*var url = response.file;
                window.open(url, '_blank');*/
                location.reload();
            }

            do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/coupon_serviceshis.php");

        });
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

    function generate_Coupon() {
        let cname = $('#cname').val();

        let ccode = $('#totcoupon').val();
        let cusedby = $('#cusedby').val();
        let climit = $('#climit').val();
        let camt = $('#camt').val();
        let user_id = $('#user_id').val();
        var formdate = moment($('#csetime').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
        var todate = moment($('#csetime').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");

        if ($('#cname').val().trim().length < 1 || $('#cname').val().trim() == '') {
            toast('warning', 'Kindly Enter the Coupon Name');
            return false;
        }
        
       
        if ($('#user_id').val().trim() == '') {
            toast('warning', 'Please Select User');
            return false;
        }
        
        if ($('#totcoupon').val().trim() == '') {
            toast('warning', 'Kindly Enter the Number of coupon');
            return false;
        }
        
        if ($('#totcoupon').val() > 100) {
            toast('warning', "Maximum 100 coupon at once.");
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

        var start  = new Date(moment(formdate).format("YYYY-MM-DD"));
        var end  = new Date(moment(todate).format("YYYY-MM-DD"));
        var diff = new Date(end - start);
        var days = diff / 1000 / 60 / 60 / 24;
        
        if (days == 0) {
            var title = 'Coupon is valid for today only.Are you sure you want to create coupon?';
        } else {
            var title = 'Coupon is valid for '+days+' days only.Are you sure you want to create coupon?';
        }

        Swal.fire({
            title: title,
            icon: 'warning',
            showDenyButton: true,
            confirmButtonText: 'Yes',
            denyButtonText: 'No'
        }).then((result) => {
            if (result['isConfirmed']){
                var formdata = [];

                formdata.push({

                    name: 'method',

                    value: "generateaffiliatecopoun"

                });

                formdata.push({

                    name: 'cname',

                    value: cname

                });
          

                formdata.push({

                    name: 'totalcoupon',

                    value: ccode

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
                
                formdata.push({

                    name: 'user_id',

                    value: user_id

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

                do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/coupon_serviceshis.php");
            }
        })

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
        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/coupon_serviceshis.php");
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

    function viewtable(cpname,searchTxt = '') {

        var table = $('#onlineearntable').DataTable();

        table.destroy();
var cpname;
var compor= $('#cpname').val();
// if (cpname !== '') {
//     cpname = cpname;
// } else {
//     cpname = $('#cpname').val();
// }

        var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
        var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");

        if(compor==''){

        var title = 'Coupon History Reports : (' + formdate + '  to  ' + todate + ' )';
        }
        else{
            cpname = $('#cpname').val();
            var title = 'Coupon History Reports : (' + formdate + '  to  ' + todate + '      '+ cpname +' )';

        }

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
                url: window.location.origin + "/ajax/service/coupon_serviceshis.php",
                method: "POST",
                dataSrc: "",
                data: {
                    method: 'coupon_report',
                    agdate: formdate,
                    todate: todate,
                    coupontype: 'affiliate',
                searchTxt: (searchTxt != '') ? searchTxt : $('#searchTxt').val(),
          cpname: cpname,
          
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
                        return ' ' + data.startdate + ' '
                    }
                    // data: "startdate"
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return '' + data.enddate + ''
                        
                    }
                    // data: "enddate"
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
                        return '' + data.created_at + ''
                    }

                    // data: "created_at"
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

                    do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/coupon_serviceshis.php");



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
        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/coupon_serviceshis.php");



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
        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/coupon_serviceshis.php");

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

                    do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/coupon_serviceshis.php");
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