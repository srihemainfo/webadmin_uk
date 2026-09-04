<?php
//1.modifications unknown//



//      Date        Developer_name      Modifications//

//     23-02-2023    sathiya            Added nationality and live in
//     07-10-2023    sathiya            added bank details of user in withdraw page



$roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'", "roll_id", "");

//   echo($roll_id);exit;

?>
<style>
    .pri-img img {
        text-align: center;
        margin: 0 auto 10px;
        border-radius: 9px;
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

    input,
    select {
        border: 1px solid #CCC;
    }

    button {
        color: #FFF;
        background-color: #428BCA;
        border: 1px solid #357EBD;
    }

    .mdtext {
        font-weight: 600;
    }

    button.swal2-confirm.swal2-styled.swal2-default-outline {
        font-size: 14px;
    }

    .input101 {
        width: 100%;
    }

    textarea {
        width: 100%;
        height: 150px;
        padding: 12px 20px;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
        background-color: #f8f8f8;
        font-size: 16px;
        resize: none;
    }

    .transfer {
        color: #00db25 !important;
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
        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Withdraw</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Earnings</li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->
            <!-- ROW-1 -->
            <?php if ($roll_id != 1) { ?>
                <!--<div class="row">-->
                <!--   <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">-->
                <!--      <div class="row">-->
                <!--         <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">-->
                <!--            <div class="card bg-primary img-card box-primary-shadow">-->
                <!--               <div class="card-body">-->
                <!--                  <div class="d-flex">-->
                <!--                     <div class="text-white">-->
                <!--                        <h2 class="mb-0 number-font" id="total_earn"></h2>-->
                <!--                        <p class="text-white mb-0">Total Earnings</p>-->
                <!--                     </div>-->
                <!--                     <div class="ms-auto"> <i class="fa fa-user-o text-white fs-30 me-2 mt-2"></i> </div>-->
                <!--                  </div>-->
                <!--               </div>-->
                <!--            </div>-->
                <!--         </div>-->
                <!--         <div class="col-sm-6 col-md-6 col-lg-6 col-xl-8" style="float: right;">-->
                <!--            <div class="r-point" style="float: right;">-->
                <!--               <button type="button" onclick="getadmindata()" data-bs-toggle="modal" data-bs-target="#withdrawreq" class="btn btn-primary btn-sm d-block">Request Amount</button>-->
                <!--            </div>-->
                <!--         </div>-->
                <!--      </div>-->
                <!--   </div>-->
                <!--</div>-->
            <?php } ?>
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="col-lg-12">
                                <h3 class="card-title"><strong>Withdraw History</strong></h3>
                            </div>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <span>Select Status</span>
                                        <select id="status" class="form-select">
                                            <option value="" selected>Select Status</option>
                                            <option value="all" >All</option>
                                            <option value="1">Success</option>
                                            <option value="0">Process</option>
                                            <option value="2">Reject</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <span>Select Transaction Mode</span>
                                        <select id="trans_mode" class="form-select">
                                            <option value="" selected>Select Status</option>
                                            <option value="all" >All</option>
                                            <option value="Bank">Bank</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Cheque">Cheque</option>
                                        </select>
                                    </div>
                                    <!-- <div class="col-md-3">


                                        <span>Select Date</span> &nbsp; <span style="color:red;">*</span>
                                        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                            <i class="fa fa-calendar"></i>&nbsp;
                                            <span></span> <i class="fa fa-caret-down"></i>
                                        </div>
                                    </div> -->
                                    <div class="col-md-3">
                                    <span>Select Date</span> &nbsp; <span style="color:red;"></span>
                                                <div >
                                                    <!--<input type="text" name="datefilter" value="" />-->
                                                    <input class="form-control" type="text" id="datefilter" name="datefilter" value="" placeholder="YYYY-MM-DD" autocomplete="off" maxlength="10" oninput="this.value = this.value.replace(/[^0-9-]/g, '');" />
                                                </div>
                                    </div>

                                    
                                    <!--<div class="col-md-3">-->
                                    <!--   <span>From Date</span>-->
                                    <!--   <input type="date" name="formdate" id="formdate" value="" class="form-select">-->
                                    <!-- <input type="date" name="formdate" id="formdate" value="<?php echo date("Y-m-d"); ?>"> -->
                                    <!--</div>-->
                                    <!--<div class="col-md-3">-->
                                    <!--   <span>To Date</span>-->
                                    <!--   <input type="date" class="form-select" name="todate" id="todate" value="" max="<?php echo date("Y-m-d"); ?>">-->
                                    <!-- <input type="date" name="todate" id="todate" value="<?php echo date("Y-m-d"); ?>"> -->
                                    <!--</div>-->
                                    <div class="col-md-3">
                                        <button style="margin-top: 22px;" onclick="viewtable()">GO</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="withdrawtable" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p border-bottom-0">Date</th>
                                            <?php if ($roll_id == 1 || $roll_id == 2 || $roll_id == 4) { ?>
                                                <th class="wd-15p border-bottom-0">Name</th>
                                                <th class="wd-15p border-bottom-0">Type</th>
                                            <?php } ?>
                                            <?php if ($roll_id != 1 && $roll_id != 2 && $roll_id != 4) { ?>
                                                <th class="wd-15p border-bottom-0">To</th>
                                            <?php } ?>
                                            <th class="wd-15p border-bottom-0">Amount</th>
                                            <?php if ($roll_id == 1 || $roll_id == 2 || $roll_id == 4) { ?>
                                                <th class="wd-15p border-bottom-0">Wallet Balance</th>
                                            <?php } ?>
                                            <th class="wd-15p border-bottom-0">Request Type</th>
                                            <th class="wd-15p border-bottom-0">Status</th>
                                            <th class="wd-15p border-bottom-0">Reason</th>
                                            <?php if ($roll_id == 1 || $roll_id == 2 || $roll_id == 4) { ?>
                                                <th class="wd-15p border-bottom-0">Transaction Mode</th>
                                            <?php } ?>
                                             
                                            <th class="wd-15p border-bottom-0">Request id</th>
                                            <?php if ($roll_id == 1 || $roll_id == 2 || $roll_id == 4) { ?>
                                                <th class="wd-15p border-bottom-0">Transaction Date</th>
                                                <th class="wd-15p border-bottom-0">Transaction id</th>
                                                <th class="wd-15p border-bottom-0">Mobile</th>
                                                <th class="wd-15p border-bottom-0">Bank Name</th>
                                                <th class="wd-15p border-bottom-0">Account Number / IBAN</th>
                                                <th class="wd-15p border-bottom-0">IFSC</th>
                                                <th class="wd-15p border-bottom-0">Swift</th>
                                                <!--<th class="wd-15p border-bottom-0">Address Line</th>-->
                                                <!--<th class="wd-15p border-bottom-0">Region</th>-->
                                                <th class="wd-15p border-bottom-0">Nationality</th>
                                                <th class="wd-15p border-bottom-0">Live in</th>
                                                <th class="wd-15p border-bottom-0">Action</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <?php if ($roll_id == 1 || $roll_id == 2 || $roll_id == 4) { ?>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2"></th>
                                                <th style="text-align:right">Total:</th>
                                                <th></th>
                                                <th colspan="16"></th>
                                            </tr>
                                        </tfoot>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal  fade" id="withdrawreq" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Withdrawal Request</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="withdrawerr">
                        </div>
                        <div id="withdrawform">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" onclick="refreshdata('withdrawreq')">Close</button>
                <div id="withdrawbtn"> </div>
            </div>
        </div>
    </div>
</div>
<div class="modal  fade" id="withdrawtransfer" tabindex="-1" role="dialog">
    <div class="modal-dialog " role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title">Withdrawal Transfer</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="wterr"></div>
                <input type="hidden" id="withdrawreqtid">
                <div class="row" id="wdtdiv">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" onclick="refreshdata('withdrawtransfer')">Close</button>
                <div id="withtransbtn">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal  fade" id="withdrawrejcet" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content" style="width: 400px;">
            <div class="modal-header">
                <h5 class="modal-title">Reject Form</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="rejectre">
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" onclick="refreshdata('withdrawrejcet')">Close</button>
                <div id="rejectebtn">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="withdrawedit" tabindex="-1" role="dialog" aria-labelledby="withdraweditLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="withdraweditLabel">Edit Reason</h5>
                <button type="button" class="close" onclick="closemodal('withdrawedit')" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="editform">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closemodal('withdrawedit')">Close</button>
                <div id="savechange">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="bakdeatilsmo" tabindex="-1" role="dialog" aria-labelledby="bakdeatilsmoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bakdeatilsmoLabel">Bank Details</h5>
                <button type="button" class="close" onclick="closemodal('bakdeatilsmo')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="bankdetails">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closemodal('bakdeatilsmo')">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="bakdeatilsmo1" tabindex="-1" role="dialog" aria-labelledby="bakdeatilsmoLabel1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bakdeatilsmoLabel">ID Proof</h5>
                <button type="button" class="close" onclick="closemodal('bakdeatilsmo1')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Images will be inserted here by JavaScript -->
                <div class="modal-body1 row pri-img text-center m-auto" id="bankdetails1">

                    <div class="modal-body2 row pri-img text-center m-auto" id="bankdetails1">


                        <!-- Images will be inserted here by JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closemodal('bakdeatilsmo1')">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var origin = window.location.origin;



        //   var url = origin + "/ajax/service/transaction_services.php";
        var url = origin + "/ajax/service/withdraw_services.php";







        $(function() {



            $('[data-toggle="tooltip"]').tooltip()

            // createDatePricket('reportrange');



            viewtable();


            <?php if ($roll_id != 1 or $roll_id != 4) { ?>
                total_earning();

            <?php } ?>

        });

        $(function() {
    
    $('input[name="datefilter"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        },
        ranges: {
                  'Today': [moment().startOf('day'), moment().endOf('day')],
                  'Yesterday': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
                  'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
                  'This Month': [moment().startOf('month'), moment().endOf('month')],
                  'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                  'This Year': [moment().startOf('year'), moment().endOf('year')],
                  'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
              }
    });
  
    $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });
  
    $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
  
  });

        // function createDatePricket(id) {


        //     var start = moment();
        //     var end = moment();

        //     function cb(start, end) {
        //         $('#' + id + ' span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));
        //     }

        //     $('#' + id).daterangepicker({
        //         startDate: start,
        //         endDate: end,
        //         timePicker: true,
        //         timePicker24Hour: true,
        //         timePickerSeconds: true,
        //         maxSpan: {
        //             days: 365
        //         },
        //         autoUpdateInput: true,
        //         maxDate: moment().add(1, 'days').toDate(),
        //         ranges: {

        //             'Today': [moment().startOf('day'), moment().endOf('day')],
        //             'Yesterday': [moment().subtract(1, 'day').startOf('day'), moment().subtract(1, 'day').endOf('day')],
        //             'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
        //             'This Month': [moment().startOf('month').startOf('day'), moment().endOf('month').endOf('day')],
        //             'Last Month': [moment().subtract(1, 'month').startOf('month').startOf('day'), moment().subtract(1, 'month').endOf('month').endOf('day')],
        //             'This Year': [moment().startOf('year').startOf('day'), moment().endOf('year').endOf('day')],
        //             'Last Year': [moment().subtract(1, 'year').startOf('year').startOf('day'), moment().subtract(1, 'year').endOf('year').endOf('day')]
        //         }
        //     }, cb);

        //     cb(start, end);
        //     // AgentList1();
        // }


        function closemodal(id) {

            $('#' + id).modal('hide');
        }

        // function total_earning() {
        //     var formdata = [];
        //     formdata.push({

        //         name: 'method',
        //         value: "total_earnings"

        //     });

        //     var post_data = formdata;
        //     var onsuccess = function(data) {

        //         var response = JSON.parse(data);

        //         if (response != "") {

        //             if (response.type == 1) {

        //                 document.getElementById('total_earn').innerText = 'AED ' + response.result;

        //             } else {

        //                 document.getElementById('total_earn').innerText = response.result;

        //             }
        //         }
        //     }

        //     do_ajax_call(post_data, onsuccess, url);

        // }

        function refreshdata(str) {

            var table = $('#withdrawtable').DataTable();

            table.destroy();
            viewtable();
            $('#' + str + '').modal('hide');
        }

        function viewtable() {

            var table = $('#withdrawtable').DataTable();
            // table.destroy();
            var newdate = $('#datefilter').val();
        
        if(newdate != ''){
                var formdate = moment($('#datefilter').data('daterangepicker').startDate).format("YYYY-MM-DD HH:mm:ss");
                var todate = moment($('#datefilter').data('daterangepicker').endDate).format("YYYY-MM-DD HH:mm:ss");
            } else{
                var formdate = '';
                var todate ='';
            }
            // let formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
            // let todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");
            //   let formdate = $('#formdate').val();
            //   let todate = $('#todate').val();
            let status = $('#status').val();
            let trans_mode = $('#trans_mode').val();
            let filename = 'Withdrawal History';

            if (<?= $roll_id ?> == 1 || <?= $roll_id ?> == 4) {

                table = $("#withdrawtable").DataTable({
                    destroy: true,
                    pageLength: 11,
                    order: [],
                    paging: true,
                    searching: true,
                    info: true,
                    ajax: {
                        url: url,
                        method: "POST",
                        dataSrc: "",
                        data: {
                            method: 'list_with_draw_request',
                            formdate: formdate,
                            todate: todate,
                            status: status,
                            trans_mode: trans_mode
                        }
                    },

                    dom: 'Bfrtip',

                    buttons: [
                        'pageLength',
                        'copy',
                        //   {
                        //       extend: 'copyHtml5',
                        //       title: filename
                        //   },

                        //   {
                        //       extend: 'csvHtml5',
                        //       title: filename
                        //   },

                        {
                            extend: 'excelHtml5',
                            title: filename
                        },

                        //   {
                        //       extend: 'pdfHtml5',
                        //       orientation: 'landscape',
                        //       pageSize: 'LEGAL',
                        //       title: filename
                        //   },

                        //   'print',
                    ],



                    columns: [

                        {
                            data: "date"
                        },
                        {
                            data: "fromname"

                        },
                        {
                            data: "type"
                        },
                        {
                            data: "amt"
                        },
                        {
                            data: "wbalance"
                        },
                        {
                            data: "Request_Type"
                        },
                        {
                            data: "status"
                        },
                    
                        // },  {
                        //     data: "status",
                        //     render: function (data, type, row) {
                        //         if (data === "Reject") {
                        //             return `${data} <br><a href="#" class="btn text-primary btn-sm view-reason" data-reason="${row.reason}" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span class="fa fa-eye fs-14">&nbsp;</span></a>`;
                        //         } else {
                        //             return data;
                        //         }
                        //     }
                       {
                            data: "reason",
                            render: function (data, type, row) {
                                if (row.status === "Reject") {
                                    return `<textarea class="form-control light-textarea" style="width:180px; height:90px;" readonly>${data}</textarea>`;
                                    // return `<textarea readonly> ${data}</textarea>`;
                                } else {
                                    return ''; // Return an empty string if the status is not "Reject"
                                }
                            }
                        },

                        {
                            data: "trans_mode"
                        },
                        {
                            data: "request_id"
                        },
                        {
                            data: "trans_date"
                        },
                        {
                            data: "trans_id"
                        },
                        {
                            data: "mobile"
                        },
                        {
                            data: "bank"
                        },
                        {
                            data: null,
                            render: function(data, type, row, meta) {
                                return "\u200B" + data.acount_num.toString()
                            }
                        },
                        {
                            data: "iban"
                        },
                        {
                            data: "swift"
                        },
                        //   {
                        //       data: "address"
                        //   },
                        //   {
                        //       data: "region"
                        //   },
                        {
                            data: "nation"
                        },
                        {
                            data: "location"
                        },
                        {
                            data: "action"
                        }
                    ],

                    footerCallback: function(row, data, start, end, display) {

                        var api = this.api();
                        // Remove the formatting to get integer data for summation

                        var intVal = function(i) {

                            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;

                        };

                        // Total over all pages

                        total = api
                            .column(3)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Total over this page
                        pageTotal = api
                            .column(3, {
                                page: 'current'

                            })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);

                            }, 0);

                        // Update footer
                        $(api.column(3).footer()).html('' + pageTotal + ' ( ' + total + ' total)');
                    },
                });
            } else if (<?= $roll_id ?> == 6) {
                table = $("#withdrawtable").DataTable({
                    destroy: true,

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

                            method: 'list_with_draw_request',
                            formdate: formdate,
                            todate: todate,
                            status: status,
                            trans_mode: trans_mode

                        }
                    },

                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength',


                        {
                            extend: 'excelHtml5',
                            title: 'Winner Report : (' + formdate + ' ' + todate + ' ' + filename + ')'
                        },
                        {

                            extend: 'pdfHtml5',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: 'Winner Report : (' + formdate + ' ' + todate + ' ' + filename + ')'

                        },
                        //'print',
                        'copy'
                    ],
                    columns: [{
                            data: "date"
                        },
                        {
                            data: "fromname"
                        },
                        {
                            data: "type"
                        },
                        {
                            data: "amt"
                        },
                        {
                            data: "wbalance"
                        },
                        {
                            data: "status"
                        },
                        {
                            data: "trans_mode"
                        },
                        {
                            data: "request_id"
                        },
                        {
                            data: "trans_date"
                        },
                        {
                            data: "trans_id"
                        },
                        {
                            data: "mobile"
                        },
                        {
                            data: "bank"
                        },
                        {
                            data: null,
                            render: function(data, type, row, meta) {
                                return "\u200B" + data.acount_num.toString()

                            }
                        },
                        {
                            data: "iban"
                        },
                        {
                            data: "swift"
                        },
                        {
                            data: "address"
                        },
                        {
                            data: "region"
                        },
                        {
                            data: "nation"
                        },
                        {
                            data: "location"
                        },
                        {

                            data: null,

                            render: function(data, type, row, meta) {

                                return ''

                            }
                        }
                    ],

                    footerCallback: function(row, data, start, end, display) {

                        var api = this.api();

                        // Remove the formatting to get integer data for summation
                        var intVal = function(i) {

                            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                        };

                        // Total over all pages
                        total = api
                            .column(3)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);

                            }, 0);
                        // Total over this page
                        pageTotal = api

                            .column(3, {
                                page: 'current'

                            })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        // Update footer
                        $(api.column(3).footer()).html('' + pageTotal + ' ( ' + total + ' total)');
                    },
                });
            } else {

                table = $("#withdrawtable").DataTable({
                    destroy: true,
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

                            method: 'list_with_draw_request',

                            formdate: formdate,
                            todate: todate,
                            status: status,
                            trans_mode: trans_mode
                        }
                    },

                    dom: 'Bfrtip',
                    buttons: [

                        'copy',
                        'csv', 'excel',
                        {

                            extend: 'pdfHtml5',
                            orientation: 'landscape',
                            pageSize: 'LEGAL'
                        }, 'print',
                    ],

                    columns: [{
                            data: "date"
                        },
                        {
                            data: "toname"
                        },
                        {
                            data: "amt"
                        },
                        {
                            data: "status"
                        },
                        {
                            data: "request_id"
                        }
                    ]
                });
            }
        }
        
                // Ensure this code runs after the table is rendered
        $(document).on('click', '.view-reason', function (e) {
            e.preventDefault(); // Prevent the default action of the link
            var reason = $(this).data('reason'); // Get the reason from the data attribute
            $(this).closest('td').html(`Reject <br>${reason}`); // Replace the content with the reason
        });


        function user_id_image(request_id, path1, path2) {
            // Get the modal
            // alert(path1);
            var modal = document.getElementById('bakdeatilsmo1');

            // Get the modal body
            var modalBody = modal.querySelector('.modal-body1');

            var modalBody1 = modal.querySelector('.modal-body2');

            // Create img elements for the images
            var img1 = document.createElement('img');
            img1.src = path1;
            img1.alt = 'Image 1';
            img1.style.width = '90%';
            // img1.style.height = '200px';

            var img2 = document.createElement('img');
            img2.src = path2;
            img2.alt = 'Image 2';
            img2.style.width = '90%';
            // img2.style.height = '200px';

            // Clear any previous content in the modal body
            modalBody.innerHTML = '';

            // Append the images to the modal body
            var textNode = document.createTextNode('ID proof Front');
            modalBody.appendChild(textNode);
            modalBody.appendChild(img1);
            var textNode1 = document.createTextNode('ID proof Back');
            modalBody.appendChild(textNode1);
            modalBody.appendChild(img2);

            // Show the modal
            $(modal).modal('show');
        }

        function closemodal(modalId) {
            $('#' + modalId).modal('hide');
        }


        function reqwithdraw() {
            document.getElementById('withdrawerr').innerHTML = '';
            let amt = document.getElementById('withdrawamt').value;
            if (parseFloat(amt) > 0) {

                var formdata = [];
                formdata.push({
                    name: 'method',
                    value: "req_with_draw"
                });
                formdata.push({

                    name: 'amount',
                    value: amt

                });

                var post_data = formdata;
                var onsuccess = function(data) {

                    var response = JSON.parse(data);
                    if (response != "") {

                        if (response.type == 1) {
                            document.getElementById('withdrawform').innerHTML = response.result;
                            document.getElementById('withdrawbtn').innerHTML = '';

                        } else {
                            document.getElementById('withdrawerr').innerHTML = response.result;
                        }
                    }
                }

                do_ajax_call(post_data, onsuccess, url);

            } else {
                document.getElementById('withdrawerr').innerHTML = '<div class="alert alert-danger" role="alert">' + 'Please enter the amount' + '</div>';
            }
        }

        function getadmindata() {

            var formdata = [];

            formdata.push({
                name: 'method',
                value: "getadmin_data"

            });
            var post_data = formdata;
            var onsuccess = function(data) {

                var response = JSON.parse(data);
                if (response != "") {
                    if (response.type == 1) {
                        document.getElementById('withdrawform').innerHTML = response.result;
                        document.getElementById('withdrawbtn').innerHTML = response.withdraw;
                    } else {
                        document.getElementById('withdrawerr').innerHTML = response.result;
                    }
                }
            }
            do_ajax_call(post_data, onsuccess, url);

        }

        function withdrawtransfer(str) {

            $('#withdrawtransfer').modal('show');
            var formdata = [];
            formdata.push({

                name: 'method',
                value: "withdraw_transfer_verify"

            });
            formdata.push({

                name: 'request',
                value: str
            });

            var post_data = formdata;
            var onsuccess = function(data) {

                var response = JSON.parse(data);
                if (response != "") {
                    if (response.type == 1) {
                        document.getElementById('wdtdiv').innerHTML = response.result;
                        document.getElementById('withtransbtn').innerHTML = response.withdraw;
                    } else {
                        document.getElementById('wterr').innerHTML = response.result;

                    }

                }

            }

            do_ajax_call(post_data, onsuccess, url);
        }

        function transferwdamount(request) {
            document.getElementById('wterr').innerHTML = '';
            let mode = document.getElementById('transactionmode').value;
            let transid = document.getElementById('withdrawid').value;
            let date = document.getElementById('withdrawdate').value;
            // let filesent =$('withdrawfile'.files[0]);

            // var filesent = $("#withdrawfile")[0].files;
            let reasontext = document.getElementById('reasontext').value;
            var btn = $('#withtransbtn').html();
            if (mode != '') {
                if (transid != '') {
                    if (date != '') {
                        if (reasontext != '') {
                              document.getElementById('withtransbtn').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';

                            var formData = new FormData();
                            formData.append('method', 'transfer_wd');
                            formData.append('request', request);
                            formData.append('mode', mode);
                            formData.append('transid', transid);
                            formData.append('date', date);

                            formData.append('filesent', withdrawfile.files[0]);

                            formData.append('reasontext', reasontext);
                            var post_data = formData;

                            $.ajax({
                                url: url,
                                type: 'post',

                                data: post_data,

                                success: function(response) {

                                    //   var response = JSON.parse(response);
                                      
                                    // alert(response.type);
                                    if (response != "") {
                                        if (response.type == '1') {
                                            $('#withdrawtransfer').modal('hide');
                                            paymentSuccess('success', response.result)
                                            // document.getElementById('withtransbtn').innerHTML = '';
                                            // document.getElementById('wdtdiv').innerHTML = response.result;

                                        } else {
                                            var response = JSON.parse(response);
                                            toast('error', response.result);
                                            document.getElementById('withtransbtn').innerHTML = btn;

                                            // document.getElementById('wterr').innerHTML = response.result;

                                        }
                                    }
                                },

                                processData: false,
                                contentType: false

                            });
                        } else {
                            toast('error', 'Please Fill Reason');
                            // document.getElementById('wterr').innerHTML = '<div class="alert alert-danger" role="alert">' + 'Please Fill Reason' + '</div>';
                        }
                    } else {

                        toast('error', 'Please select date');

                        // document.getElementById('wterr').innerHTML = '<div class="alert alert-danger" role="alert">' + 'Please select date' + '</div>';

                    }

                } else {

                    toast('error', 'Please enter Transaction id');

                    // document.getElementById('wterr').innerHTML = '<div class="alert alert-danger" role="alert">' + 'Please enter Transaction id' + '</div>';

                }

            } else {

                toast('error', 'Please Select mode');
                // document.getElementById('wterr').innerHTML = '<div class="alert alert-danger" role="alert">' + 'Please Select mode' + '</div>';
            }

        }

        function withdrawDecline(request,mobile,from_name,createdon) {
            // alert(request);
            // alert(mobile);
            // alert(from_name);
            // alert(createdon);
            
            if (request != '') {
                var formdata = [];
                formdata.push({

                    name: 'method',
                    value: "withdrawDecline"

                });

                formdata.push({

                    name: 'request',
                    value: request
                });
                 formdata.push({

                    name: 'mobile',
                    value: mobile
                });
                 formdata.push({

                    name: 'from_name',
                    value: from_name
                });
                 formdata.push({

                    name: 'createdon',
                    value: createdon
                });


                var post_data = formdata;
                var onsuccess = function(data) {

                    var response = JSON.parse(data);
                    if (response != "") {
                        if (response.type == 1) {
                            $('#withdrawrejcet').modal('show');
                            document.getElementById('rejectebtn').innerHTML = response.rejectbtn;
                            document.getElementById('rejectre').innerHTML = response.result;

                        } else {

                            toast('error', response.result);
                            viewtable();

                        }
                    }

                }

                do_ajax_call(post_data, onsuccess, url);

            } else {
                toast('error', 'Request ID Not Found!');

            }
        }

        function reject_request(request,mobile,from_name,createdon) {
            
           
            let rejectreasontext = $('#rejectreasontext').val();
            let parts = createdon.split('-');
            let formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];

            if (rejectreasontext != '') {
                document.getElementById('rejectebtn').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';
                
                    
                    
                    
                              var formdata = [];
                                formdata.push({
                                    name: 'method',
                                    value: "withdrawDeclineReject"
                                });
                                formdata.push({
                                    name: 'request',
                
                                    value: request
                
                                });
                
                                formdata.push({
                
                                    name: 'reason',
                                    value: rejectreasontext
                                });
                
                                var post_data = formdata;
                                var onsuccess = function(data) {
                                    var response = JSON.parse(data);
                                    if (response != "") {
                
                                        if (response.type == 1) {
                                            
                                            //  var formData = new FormData();
                                                // alert(mobile);
        
                   
                                                // formData.append("senderName", "DRAW");
                                                // formData.append("mobileNo", mobile);
                                                // formData.append("templateName", "rejected_withdrwal_template_v3");
                                                // formData.append("language", "en");
                                                // formData.append("templateBodyParam[]", formattedDate);
                                                // formData.append("templateBodyParam[]",from_name);
                                                // formData.append("templateBodyParam[]", request);
                                                // formData.append("templateBodyParam[]", rejectreasontext);
                                            
                                            
                                            //  $.ajax({
                                            //     url: '<?= API_DOMAIN;?>dtSendTemplate',
                                            //     method: 'POST',
                                            //     data: formData,
                                            //     processData: false,
                                            //     contentType: false,
                                            //     success: function(response) {
                                            //         // if (response.status === 'success') {
                                            //         if (response.type === 1) {
                                                        
                                            //             alert(request);
                                                        
                                                        
                                            //             // alert('deva successfully');
                                            //         } else {
                                            //           toast('error', response.result);
                                            //         }
                                            //         console.log(response);
                                            //     },
                                            //     error: function(xhr, status, error) {
                                            //         console.error(xhr, status, error);
                                            //     }
                                            // });
                                            
                                            toast('success', response.result);
                                             viewtable();
                
                                            $('#withdrawrejcet').modal('hide');
                                            paymentSuccess('error', response.result);
                                        } else {
                                            document.getElementById('rejectebtn').innerHTML = response.rejectbtn;
                                            toast('error', response.result);
                                            viewtable();
                
                                        }
                                    }
                                }
                
                                do_ajax_call(post_data, onsuccess, url);
                    

               

            } else {
                toast('error', 'Please Fill The Reason!');
            }

        }

        function editWithDrawDetails(request) {
            if (request != '') {
                var formdata = [];

                formdata.push({
                    name: 'method',
                    value: "update_withdraw"

                });

                formdata.push({
                    name: 'request',

                    value: request

                });
                var post_data = formdata;
                var onsuccess = function(data) {
                    var response = JSON.parse(data);
                    if (response != "") {
                        if (response.type == 1) {
                            document.getElementById('editform').innerHTML = response.output;
                            document.getElementById('transreason').innerHTML = response.reason;
                            document.getElementById('savechange').innerHTML = response.savechange;
                            $('#withdrawedit').modal('show');

                        } else {
                            toast('error', response.result);
                        }
                    }
                }

                do_ajax_call(post_data, onsuccess, url);
            }

        }

        function Bankdeatils(request) {
            if (request != '') {
                var formdata = [];
                formdata.push({
                    name: 'method',
                    value: "Bank_deatils"

                });
                formdata.push({
                    name: 'request',
                    value: request
                });
                var post_data = formdata;
                var onsuccess = function(data) {
                    var response = JSON.parse(data);
                    if (response != "") {
                        if (response.type == 1) {
                            document.getElementById('bankdetails').innerHTML = response.result;
                            $('#bakdeatilsmo').modal('show');

                        } else {
                            toast('error', response.result);


                        }
                    }
                }
                do_ajax_call(post_data, onsuccess, url);

            }
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

        function paymentSuccess(icon, titlestr) {

            Swal.fire({
                title: titlestr,
                icon: icon,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OKAY'

            }).then((result) => {

                if (result.isConfirmed) {

                    viewtable();

                }
            })
        }

        function update_withdraw(request) {

            if (request != '') {
                var formdata = [];
                formdata.push({
                    name: 'method',
                    value: "save_withdraw"

                });

                formdata.push({
                    name: 'request',
                    value: request

                });

                formdata.push({
                    name: 'transdate',
                    value: $('#transdate').val()

                });

                formdata.push({
                    name: 'reasontxt',
                    value: $('#transreason').val()

                });
                var post_data = formdata;
                var onsuccess = function(data) {

                    var response = JSON.parse(data);

                    if (response != "") {
                        if (response.type == 1) {
                            $('#withdrawedit').modal('hide');

                            paymentSuccess('success', response.result);

                        } else {

                            toast('error', response.result);
                        }
                    }
                }
                do_ajax_call(post_data, onsuccess, url);
            }
        }
    </script>