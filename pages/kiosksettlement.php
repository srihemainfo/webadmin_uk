<?php



//1.modifications unknown//



//      Date        Developer_name      Modifications//

//     23-02-2023    sathiya            Added nationality and live in



$roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'", "roll_id", "");



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
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Kiosk Settlement</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Kiosk Settlement</li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- ROW-1 -->



            <?php if ($roll_id != 1) { ?>



                <!-- <div class="row">



                    <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">



                        <div class="row">



                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">



                                <div class="card bg-primary img-card box-primary-shadow">



                                    <div class="card-body">



                                        <div class="d-flex">



                                            <div class="text-white">



                                                <h2 class="mb-0 number-font" id="total_earn"></h2>



                                                <p class="text-white mb-0">Total Earnings</p>



                                            </div>



                                            <div class="ms-auto"> <i class="fa fa-user-o text-white fs-30 me-2 mt-2"></i> </div>



                                        </div>



                                    </div>



                                </div>



                            </div>



                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-8" style="float: right;">



                                <div class="r-point" style="float: right;">



                                    <button type="button" onclick="getadmindata()" data-bs-toggle="modal" data-bs-target="#withdrawreq" class="btn btn-primary btn-sm d-block">Request Amount</button>



                                </div>



                            </div>



                        </div>



                    </div>



                </div> -->



            <?php } ?>







            <div class="row row-sm">



                <div class="col-lg-12">



                    <div class="card">



                        <div class="card-header">


                            <div class="row">

                                <div class="col-lg-9">


                                    <h3 class="card-title"><strong>Withdraw History</strong></h3>

                                </div>


                                <div class="col-lg-3">
                                    <button class="btn btn-primary" data-bs-target="#settlmentNow" data-bs-toggle="modal">Settlement</button>
                                </div>

                            </div>























                        </div>







                        <div class="card-body">
                            <div class="col-lg-12 mb-3">



                                <div class="row">

                                    <div class="col-md-3">



                                        <span>Select Status</span> <sup style="color:red;">*</sup>



                                        <select id="rewardStatus" class="form-select">

                                            <option value="SALES" selected>Sales</option>
                                            <option value="SETTLEMENT">Settlement</option>
                                            
                                        </select>



                                    </div>

                                    <div class="col-md-3">



                                        <span>Select Merchant</span>



                                        <select id="merchatLIST" class="form-select">







                                            <option value="all" selected>All</option>

                                            <?php



                                            $merlist = select_query($con, "user_register", "id, name", " `roll_id` = '9' AND `deletes` = '0' ORDER BY `id` DESC", "", "");
                                            if ($merlist['nr'] > 0) {
                                                foreach ($merlist['result'] as $key => $value) {
                                            ?>
                                                    <option value="<?= $value['id']; ?>"><?= $value['name']; ?></option>
                                            <?php
                                                }
                                            }
                                            ?>




                                        </select>



                                    </div>


                                    <div class="col-md-3">



                                        <button style="margin-top: 22px;" onclick="searchTicket()">GO</button>



                                    </div>



                                </div>



                            </div>


                            <div class="table-responsive">



                                <table class="table table-bordered text-nowrap border-bottom" id="kioskStatel" style="width:100%;">



                                    <thead>
                                        <tr>
                                            <th class="wd-15p border-bottom-0">Settlement Date</th>
                                            <th class="wd-15p border-bottom-0">Merchant Name</th>
                                            <th class="wd-15p border-bottom-0">Opening (AED)</th>
                                            <th class="wd-15p border-bottom-0">Total (AED)</th>
                                            <th class="wd-15p border-bottom-0">Closing (AED)</th>
                                            <th class="wd-15p border-bottom-0">Transaction Type</th>
                                            <th class="wd-15p border-bottom-0">Status</th>
                                            <th class="wd-15p border-bottom-0">Created On</th>
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







<div class="modal fade" id="settlmentNow" tabindex="-1" role="dialog">



    <div class="modal-dialog modal-sm" role="document">



        <div class="modal-content">



            <div class="modal-header">



                <h5 class="modal-title">Settlement Form</h5>



                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">


                    <span aria-hidden="true">×</span>



                </button>



            </div>



            <div class="modal-body">



                <div class="row">
                    <div class="col-sm-12">
                        <div id="setError"></div>
                    </div>


                    <div class="col-sm-12">







                        <span>Select Merchant</span>



                        <select id="SetllmentLIST" class="form-select" onchange="getUserAmount($(this).val())">

                            <option value="" selected>Select Merchant</option>

                            <?php



                            $merlist = select_query($con, "user_register", "id, name", " `roll_id` = '9' AND `deletes` = '0' AND `f_points` > 0 ORDER BY `id` DESC", "", "");
                            if ($merlist['nr'] > 0) {
                                foreach ($merlist['result'] as $key => $value) {
                            ?>
                                    <option value="<?= $value['id']; ?>"><?= $value['name']; ?></option>
                            <?php
                                }
                            }
                            ?>




                        </select>



                    </div>



                    <div class="col-sm-12">
                        <span>Total Balance : &nbsp;</span> <span id="totalBNP">0</span>
                    </div>

                    <div class="col-sm-12">
                        <span for="setamt">Amount</span>
                        <input type="text" name="setamt" id="setamt" class="form-control bg-wrapper" oninput="this.value = this.value.replace(/[^0-9]/g, );">

                    </div>

                </div>



            </div>



            <div class="modal-footer">


                <div id="steBTYN">
                    <button class="btn btn-primary" onclick="settleNow()">Submit</button>
                </div>

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



                <h5 class="modal-title" id="withdraweditLabel">Modal title</h5>



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







<script>
    var origin = window.location.origin;



    var url = origin + "/ajax/service/transaction_services.php";







    $(function() {



        // $('[data-toggle="tooltip"]').tooltip()



        // viewtable();



        // total_earning();

        searchTicket();

    });




    function searchTicket() {



        // var table = $('#sms_report1').DataTable();

        // table.destroy();


        // var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
        // var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");

        var title = 'Kiosk';

        table = $("#kioskStatel").DataTable({
            destroy: true,
            pageLength: 10,
            order: [
                [7, 'desc']
            ],
            columnDefs: [{
                type: 'date',
                targets: [7]
            }],
            paging: true,
            searching: true,
            info: true,
            ajax: {
                url: origin + "/ajax/service/kiosk_settl_services.php",
                method: "POST",
                dataSrc: "",
                data: {
                    method: 'get_kiosk_settelment',
                    merchatLIST: $('#merchatLIST').val(),
                    rewardStatus: $('#rewardStatus').val()
                }
            },
            dom: 'Bfrtip',
            buttons: [
                'pageLength',

                {
                    extend: 'csvHtml5',
                    title: title
                },
                {
                    extend: 'excelHtml5',
                    title: title
                },

                {
                    extend: 'print',
                    title: title
                },
            ],
            columns: [

                {
                    data: null,
                    render: function(data, type, row, meta) {
                        let date = '';
                        if(data.reward_type == 'SALES'){
                            date=  moment(data.settlement_start).format("DD MMM YYYY") + ' - ' + moment(data.settlement_end).format("DD MMM YYYY");
                        } else {
                            date = moment(data.settlement_start).format("DD MMM YYYY");
                        }
                        return date
                    }
                },

                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.name
                    }
                },

                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.opening_balance
                    }
                },

                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.total
                    }
                },

                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.closeing_balance
                    }
                },

                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.transaction_type
                    }
                },

                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.reward_type
                    }
                },

                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return moment(data.createdon).format("DD MMM YYYY hh:mm a")
                    }
                },


            ],

        });

    }


    // function closemodal(id) {



    //     $('#' + id).modal('hide');



    // }







    function total_earning() {



        var formdata = [];



        formdata.push({



            name: 'method',



            value: "total_earnings"



        });



        var post_data = formdata;



        var onsuccess = function(data) {



            var response = JSON.parse(data);



            if (response != "") {



                if (response.type == 1) {



                    document.getElementById('total_earn').innerText = 'AED ' + response.result;



                } else {



                    document.getElementById('total_earn').innerText = response.result;



                }



            }



        }



        do_ajax_call(post_data, onsuccess, url);



    }







    function refreshdata(str) {



        var table = $('#withdrawtable').DataTable();



        table.destroy();



        viewtable();







        $('#' + str + '').modal('hide');



    }







    function viewtable() {



        var table = $('#withdrawtable').DataTable();



        table.destroy();







        let formdate = $('#formdate').val();



        let todate = $('#todate').val();



        let status = $('#status').val();



        let trans_mode = $('#trans_mode').val();







        let filename = 'Winner List ' + $('#status option:selected').text();







        if (<?= $roll_id ?> == 1 || <?= $roll_id ?> == 2) {



            table = $("#withdrawtable").DataTable({



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



                        extend: 'copyHtml5',



                        title: 'Ticket Report : (' + formdate + ' ' + todate + ' ' + filename + ')'











                    },







                    {



                        extend: 'csvHtml5',



                        title: 'Ticket Report : (' + formdate + ' ' + todate + ' ' + filename + ')'











                    },



                    {



                        extend: 'excelHtml5',



                        title: 'Ticket Report : (' + formdate + ' ' + todate + ' ' + filename + ')'











                    },



                    {



                        extend: 'pdfHtml5',



                        orientation: 'landscape',



                        pageSize: 'LEGAL',



                        title: 'Ticket Report : (' + formdate + ' ' + todate + ' ' + filename + ')'



                    }, 'print',







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



                        extend: 'copyHtml5',



                        title: 'Ticket Report : (' + formdate + ' ' + todate + ' ' + filename + ')'











                    },







                    {



                        extend: 'csvHtml5',



                        title: 'Ticket Report : (' + formdate + ' ' + todate + ' ' + filename + ')'











                    },



                    {



                        extend: 'excelHtml5',



                        title: 'Ticket Report : (' + formdate + ' ' + todate + ' ' + filename + ')'











                    },



                    {



                        extend: 'pdfHtml5',



                        orientation: 'landscape',



                        pageSize: 'LEGAL',



                        title: 'Ticket Report : (' + formdate + ' ' + todate + ' ' + filename + ')'



                    }, 'print',







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







                            success: function(data) {



                                var response = JSON.parse(data);



                                if (response != "") {



                                    if (response.type == 1) {



                                        $('#withdrawtransfer').modal('hide');



                                        paymentSuccess('success', response.result)



                                        // document.getElementById('withtransbtn').innerHTML = '';



                                        // document.getElementById('wdtdiv').innerHTML = response.result;



                                    } else {



                                        document.getElementById('withtransbtn').innerHTML = response.withdraw;



                                        // document.getElementById('wterr').innerHTML = response.result;







                                        toast('error', response.result);







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







    function withdrawDecline(request) {



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



                    }



                }



            }



            do_ajax_call(post_data, onsuccess, url);







        } else {



            toast('error', 'Request ID Not Found!');



        }



    }







    function reject_request(request) {



        let rejectreasontext = $('#rejectreasontext').val();







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



                        $('#withdrawrejcet').modal('hide');



                        paymentSuccess('error', response.result);







                    } else {



                        document.getElementById('rejectebtn').innerHTML = response.rejectbtn;



                        toast('error', response.result);



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






    function getUserAmount(id) {
        $('#setError').html('');

        if (id == '') {
            $('#setError').html(`<div class="alert alert-danger" role="alert">
                                    Kindly Select The Merchant
                                    </div>`);
            return false;
        }

        var formdata = [];



        formdata.push({

            name: 'method',
            value: "getUserAmount"

        }, {

            name: 'userid',
            value: id

        });










        var post_data = formdata;



        var onsuccess = function(data) {



            var response = JSON.parse(data);



            if (response != "") {



                if (response.type == 1) {






                    $('#totalBNP').text(parseInt(response.result));







                } else {




                    $('#setError').html(`<div class="alert alert-danger" role="alert">${response.result}</div>`);


                }



            }



        }



        do_ajax_call(post_data, onsuccess, origin + "/ajax/service/kiosk_settl_services.php");
    }


    function settleNow() {
        $('#setError').html('');
        let id = $('#SetllmentLIST').val();
        let setamt = $('#setamt').val();

        if (id == '') {
            $('#setError').html(`<div class="alert alert-danger" role="alert">
                                    Kindly Select The Merchant
                                    </div>`);
            return false;
        }

        if (setamt == '') {
            $('#setError').html(`<div class="alert alert-danger" role="alert">
                                    Kindly Enter the Amount
                                    </div>`);
            return false;
        }

        if (setamt < 1) {
            $('#setError').html(`<div class="alert alert-danger" role="alert">
                                    Minimum amount 1 AED
                                    </div>`);
            return false;
        }


        var formdata = [];



        formdata.push({

            name: 'method',
            value: "settlenow"

        }, {

            name: 'userid',
            value: id

        }, {

            name: 'setamt',
            value: setamt

        });






        let btn = $('#steBTYN').html();
        $('#steBTYN').html(`<button class="btn btn-primary" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                            </button>`);


        var post_data = formdata;



        var onsuccess = function(data) {



            var response = JSON.parse(data);



            if (response != "") {



                if (response.type == 1) {






                    // $('#totalBNP').text(parseInt(response.result));

                    $('#settlmentNow').modal('hide');
                    $('#steBTYN').html(btn);
                    paymentSuccess('success', response.result);



                } else {




                    $('#setError').html(`<div class="alert alert-danger" role="alert">${response.result}</div>`);
                    $('#steBTYN').html(btn);

                }



            }



        }



        do_ajax_call(post_data, onsuccess, origin + "/ajax/service/kiosk_settl_services.php");
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