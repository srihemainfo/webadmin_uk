<!--
   1.modifications unknown
   
   
   Date      Developer_name      Modifications  Enddate
   -->
<style>
    input,
    button {
        height: 35px;
        margin: 0;
        padding: 6px 10px;
        border-radius: 2px;
        font-family: inherit;
        font-size: 100%;
        color: inherit;
    }

    textarea {
        height: 100px;
        width: 100%;
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

    span.fa.fa-money {
        color: blue;
        font-size: 16px;
        font-weight: bolder;
    }

    span.fa.fa-ban {
        font-size: 16px;
        font-weight: bolder;
        color: red;
    }

    button {
        color: #FFF;
        background-color: #428BCA;
        border: 1px solid #357EBD;
    }

    .tabs-menu1 ul li a {
        display: block;
        color: #282f53;
        padding: 5px !important;
        margin-top: -7px !important;
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

    .fs-37 {
        font-size: 40px !important;
    }
</style>
<script>
    window.onload = function() {

        var page_origin = window.location.origin;

        let anchor = document.getElementById("anchor");

        anchor.href = page_origin;

    }
</script>

<?php
$query = mysqli_query($con, "SELECT ph1.paymentStatus
FROM `payment_history` AS ph1
JOIN (
    SELECT `paymentStatus`, MAX(`id`) AS max_id
    FROM `payment_history`
    GROUP BY `paymentStatus`
) AS ph2
ON ph1.`id` = ph2.`max_id`  
ORDER BY `ph1`.`paymentStatus` ASC;");

// Output data as JSON
$output = [];
if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        $output[] = $row;
    }
}
// echo json_encode($output);

?>
<div class="main-content app-content mt-0">
    <div class="side-app">
        <input type="hidden" id="tabid" value="pointtable">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Daily Report</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Daily Report</li>
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
                                    <div class="mt-2">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6 mb-2">
                                                <span>Select Date</span> &nbsp; <span style="color:red;">*</span>
                                                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                    <i class="fa fa-calendar"></i>&nbsp;
                                                    <span></span> <i class="fa fa-caret-down"></i>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 mb-2">
                                                <span>Select The Customer </span> &nbsp; <span style="color:red;">*</span>
                                                <select id="trans_mode" class="form-select">
                                                    <option value=""selected>Select customer</option>
                                                    <option value="uae_cus">UAE Customers</option>
                                                    <option value="non_uae_cus" >Non UAE Customers</option>

                                                </select>
                                            </div>

                                            <div class="col-lg-3 col-sm-6 mb-2">
                                                <span>Select Category</span> &nbsp; <span style="color:red;">*</span>
                                                <select id="category" class="form-control" onchange="updateColorAndDisableLink(this)">
                                                    <option value="" selected>Select Category</option>
                                                    <option value="logged_in">Logged in-didn't make a purchase</option>
                                                    <option value="Initiated">Selected a product,but didn't make a purchase</option>
                                                    <option value="Aborted" >Aborted</option>
                                                    <option value="Awaited" >Awaited</option>
                                                    <option value="Failure" >Failure</option>
                                                    <option value="Invalid" >Invalid</option>
                                                    <option value="CAPTURED" >Captured</option>
                                                    <option value="Shipped" >Shipped</option>
                                                    <option value="Success" >Success</option>
                                                    <?php //foreach ($output as $item) : ?>
                                                        <!-- <option value="<?php //echo $item['paymentStatus'] ?? 'NotInitiated'; ?>"> -->
                                                            <?php //echo $item['paymentStatus'] ?? 'Not Initiated'; ?>
                                                        <!-- </option> -->
                                                    <?php //endforeach; ?>
                                                </select>
                                            </div>


                                            <div class="col-lg-3 col-sm-6 mb-2">
                                                <br>
                                                <button type="submit" id="smslogsearch" class="btn btn-info" onclick="viewtable()">Go</button>
                                            </div>
                                        </div>

                                    </div>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="col-lg-4 point-agn">
                                <h3 class="card-title"><strong id="tabtitle2">Daily Report</strong></h3>
                            </div>
                        </div>
                        <div class="card-body pt-4">
                            <div class="grid-margin">
                                <div class="">
                                    <div class="panel panel-primary">
                                        <div class="tab-menu-heading border-0 p-0">
                                            <div class="tabs-menu1">
                                                <!-- Tabs -->
                                                <ul class="nav panel-tabs product-sale">
                                                    <!-- <li><a href="#tab7" class="active" data-bs-toggle="tab" onclick="settabid_new('pointtable', 'Customers Detail')">Customers Detaild</a></li> -->
                                                    
                                                    <!-- <li><a id="tab8_link" href="#tab8" data-bs-toggle="tab"  class="text-dark" onclick="settabid_new('requesttable', 'Request History')">Payment Status Report</a></li> -->

                                                </ul>
                                            </div>
                                        </div>
                                        <div class="panel-body tabs-menu-body border-0 pt-0">
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab7">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered text-nowrap border-bottom" id="pointtable" style="width:100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th class="wd-15p border-bottom-0">Customer ID</th>
                                                                    <th class="wd-15p border-bottom-0">Customer Name</th>
                                                                    <th class="wd-15p border-bottom-0">Mobile</th>
                                                                    <th class="wd-15p border-bottom-0">Email</th>
                                                                    <th class="wd-15p border-bottom-0">Product</th>


                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab8">
                                                    <div class="table-responsive">
                                                        <table id="requesttable" class="table table-bordered text-nowrap mb-0">
                                                            <thead class="border-top">
                                                                <tr>
                                                                    <th class="wd-15p border-bottom-0">Customer ID</th>
                                                                    <th class="wd-15p border-bottom-0">Customer Name</th>
                                                                    <th class="wd-15p border-bottom-0">Mobile</th>
                                                                    <th class="wd-15p border-bottom-0">Email</th>
                                                                    <th class="wd-15p border-bottom-0">AED</th>
                                                                    <th class="wd-15p border-bottom-0">Status</th>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var origin = window.location.origin;

    var url = origin + "/ajax/service/DailyReport_service.php";

    function updateColorAndDisableLink(select) {
    var link = document.getElementById('tab8_link');
    var colorIndicator = select.nextElementSibling.querySelector('span');
    
    if (select.value === "") {
        colorIndicator.style.color = "black";
        link.style.pointerEvents = "none";
    } else {
        colorIndicator.style.color = "red";
        link.style.pointerEvents = "auto";
    }
}
 
    $(function() {

        createDatePricket('reportrange');
        // viewtable();
        total_earning();

    });

    function createDatePricket(id) {


        // var start = moment();
        // var end = moment();

        var start = moment().startOf('day').hours(0).minutes(0).seconds(0);
        var end = moment().endOf('day').hours(23).minutes(59).seconds(59);

        function cb(start, end) {
            $('#' + id + ' span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));
        }

        $('#' + id).daterangepicker({
            startDate: start,
            endDate: end,
            timePicker: true,
            timePicker24Hour: true,
            timePickerSeconds: true,
            maxSpan: {
                days: 365
            },
            autoUpdateInput: true,
            maxDate: moment().add(1, 'days').toDate(),
            ranges: {

                'Today': [moment().startOf('day'), moment().endOf('day')],
                'Yesterday': [moment().subtract(1, 'day').startOf('day'), moment().subtract(1, 'day').endOf('day')],
                'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
                'This Month': [moment().startOf('month').startOf('day'), moment().endOf('month').endOf('day')],
                'Last Month': [moment().subtract(1, 'month').startOf('month').startOf('day'), moment().subtract(1, 'month').endOf('month').endOf('day')],
                'This Year': [moment().startOf('year').startOf('day'), moment().endOf('year').endOf('day')],
                'Last Year': [moment().subtract(1, 'year').startOf('year').startOf('day'), moment().subtract(1, 'year').endOf('year').endOf('day')]
            }
        }, cb);

        cb(start, end);
        // AgentList1();
    }

    function settabid_new(name, tabname) {



        if (name == 'pointtable') {

            var table = $('#pointtable').DataTable();

            table.destroy();

            document.getElementById('tabid').value = name;

            document.getElementById('tabtitle2').innerText = tabname;

        } else if (name == 'requesttable') {

            var table = $('#requesttable').DataTable();

            table.destroy();

            document.getElementById('tabid').value = name;

            document.getElementById('tabtitle2').innerText = tabname;

        }
        viewtable();

    }

    function viewtable() {
        var tablename = document.getElementById('tabid').value;
        var customer = document.getElementById('trans_mode').value;
        var payment_category = document.getElementById('category').value;

        if (customer == '' || customer == undefined || customer == null) {
                showToast("error", "Please select the customer!", 5000);
                return false;
            }

            if (payment_category == '' || payment_category == undefined || payment_category == null) {
                showToast("error", "Please select the Category!", 5000);
                return false;
            }



        // var table = $('#' + tablename).DataTable();
        var table = $("#pointtable").DataTable();
        table.destroy();

        var formdata = [{
                name: 'method',
                value: "Daily_Report_customer"
            },
            {
                name: 'tablename',
                value: tablename
            },
            {
                name: 'Customer',
                value: customer
            },
            {
                name: 'payment_category',
                value: payment_category
            },
            {
                name: 'formdate',
                value: moment($('#reportrange').data('daterangepicker').startDate).format("YYYY-MM-DD HH:mm:ss")
            },
            {
                name: 'todate',
                value: moment($('#reportrange').data('daterangepicker').endDate).format("YYYY-MM-DD HH:mm:ss")
            }
        ];
        var title = 'Daily Report : (' + formdata[4].value + ' to ' + formdata[5].value + ') ';

        var onsuccess = function(data) {
            var response = JSON.parse(data);
            if (response.type == 1) {
                var tableConfig = {
                    order: [
                        [0, 'asc']
                    ],
                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength',
                        'copy',
                        {
                        extend: 'excelHtml5',
                        title: title
                        },


                    ],
                    data: response.result,
                    columns: []
                };

                if (tablename == 'pointtable') {
                    // tableConfig.buttons.push({
                    //     extend: 'excelHtml5',
                    //     title: 'Daily Report : (' + formdata[4].value + ' to ' + formdata[5].value + ')'
                    // });
                    tableConfig.columns = [{
                            data: 'CustomerID'
                        },
                        { 
                            data: null,
                            render: function (data, type, row) {
                                return row.FirstName + ' ' + row.LastName; // Combine FirstName and LastName
                            }
                        },
                        {
                            data: 'Mobile'
                        },
                        {
                            data: 'Email'
                        },
                        {
                            data: 'max_finaltotal'
                        },



                    ];
                } else if (tablename == 'requesttable') {
                    tableConfig.columns = [{
                            data: 'CustomerID'
                        },
                        {
                            data: 'FirstName'
                        },
                        {
                            data: 'Mobile'
                        },
                        {
                            data: 'Email'
                        },
                        {
                            data: 'max_finaltotal'
                        },
                        {
                            data: 'PymentStatus'
                        }
                    ];
                }

                $('#' + tablename).DataTable(tableConfig);
            }
            else {

                // var table = $('#' + tablename).DataTable();
                var table = $("#pointtable").DataTable();
                table.clear().draw();
            }
        }

        do_ajax_call(formdata, onsuccess, url);
    }

    //    function viewtable() {

    //        var tablename = document.getElementById('tabid').value;
    //        var customer = document.getElementById('trans_mode').value;
    //        var payment_category = document.getElementById('category').value;

    //     //   alert(tablename);
    //        var table = $('#' + tablename + '').DataTable();

    //        table.destroy();
    //        var formdata = [];
    //        formdata.push({

    //            name: 'method',
    //            value: "Daily_Report_customer"

    //        });

    //        formdata.push({
    //            name: 'tablename',
    //            value: tablename
    //        });

    //        formdata.push({
    //            name: 'Customer',
    //            value: customer
    //        });
    //        formdata.push({
    //            name: 'payment_category',
    //            value: payment_category
    //        });

    //        var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
    //        var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");

    //     //   let formdate = $('#formdate').val();
    //     //   let todate = $('#todate').val();



    //        formdata.push({

    //            name: 'formdate',
    //            value: formdate

    //        }, {

    //            name: 'todate',
    //            value: todate

    //        });

    //        formdata.push();
    //        var post_data = formdata;
    //        var onsuccess = function(data) {
    //            var response = JSON.parse(data);
    //            if (response != "") {
    //                if (response.type == 1) {
    //                 alert('hellow king');
    //                    if (tablename == 'pointtable') {
    //                        $('#pointtable').DataTable({
    //                            order: [

    //                                [0, 'desc']

    //                            ],

    //                            dom: 'Bfrtip',

    //                            buttons: [

    //                                'pageLength',
    //                                'copy',


    //                                {
    //                                    extend: 'excelHtml5',
    //                                    title: 'Points Report : (' + formdate + ' to ' + todate + ')'
    //                                },

    //                            ],

    //                            error: function(xhr, error, thrown) {
    //                                 console.error("DataTables error:", error);
    //                             },
    //                             columns: [


    //                                 {
    //                                 data: 'CustomerID'
    //                                 },
    //                                 {
    //                                 data: 'FirstName'
    //                                 },
    //                                 {
    //                                 data: 'Email'
    //                                 },

    //                                 {
    //                                 data: 'Mobile'
    //                                 },

    //                             ],


    //                        });
    //                    } else if (tablename == 'requesttable') {

    //                        $('#requesttable').DataTable({
    //                            order: [
    //                                [0, 'desc']
    //                            ],
    //                            dom: 'Bfrtip',

    //                            buttons: [

    //                                'copy',  'excel', 

    //                            ],

    //                            "data": response.result,

    //                            "columns": [
    //                                {
    //                                    'data': 'date'
    //                                },
    //                                {
    //                                    'data': 'name'
    //                                },
    //                                {
    //                                    'data': 'points'
    //                                },
    //                                {
    //                                    'data': 'requestid'
    //                                },
    //                                {
    //                                    'data': 'status'
    //                                },
    //                                {
    //                                    'data': 'action'
    //                                }
    //                            ],
    //                        });
    //                    }
    //                } else {

    //                    var table = $('#' + tablename + '').DataTable();
    //                    table.clear().draw();
    //                }
    //            }
    //        }

    //        do_ajax_call(post_data, onsuccess, url);

    //    }


    function toast(e, t) {

        let r = Swal.mixin({

            toast: !0,

            position: "top-end",

            showConfirmButton: !1,

            timer: 5e3,

            timerProgressBar: !0,

            didOpen(e) {

                e.addEventListener("mouseenter", Swal.stopTimer), e.addEventListener("mouseleave", Swal.resumeTimer)

            }

        });

        r.fire({

            icon: e,

            title: t

        })

    }
</script>