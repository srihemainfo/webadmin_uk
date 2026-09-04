<!--
1.modifications unknown


Date      Developer_name      Modifications



-->

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

window.onload = function(){

var page_origin = window.location.origin;

let anchor =  document.getElementById("anchor");

anchor.href = page_origin;

}
</script>







<div class="main-content app-content mt-0">

    <div class="side-app">





        <div class="main-container container-fluid">





            <div class="page-header">

                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Payments Log</h1>

                <div>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page">Payments</li>

                        Log

                    </ol>

                </div>

            </div>



            <div class="row row-sm">

                <div class="col-lg-12">

                    <div class="card">

                        <div class="card-header">

                            <div class="col-lg-12">

                                <h3 class="card-title"><strong>Transaction History</strong></h3>

                            </div>

                            <div class="col-lg-4">

                                <input type="date" name="formdate" id="formdate" value="<?php echo date("Y-m-d"); ?>">

                                <input type="date" name="todate" id="todate" value="<?php echo date("Y-m-d"); ?>">

                                <button onclick="viewtable()">GO</button>

                            </div>

                            <div class="col-lg-4">





                                <?php

                                $fd = date("Y-m-d");

                                echo "REQUIRED GENERATE ( " . $fd . " ) - ";

                                $contype = "`createdon` BETWEEN '$fd 00:00:00' AND '$fd 23:59:59' AND";



                                $Ticket_lines = select_query($con, "orders", "transaction_id,event", "`amount`!='0.00' and $contype `id` != ''", "", "");

                                // $Ticket_lines = select_query($con, "payment_history", "", "$contype `pay_re_status` != ''", "", "");

                                $f = "0";

                                foreach ($Ticket_lines[result] as $key => $value) {

                                    $transaction_id = $value[transaction_id];

                                    $ticket_no =  select_top_name($con, "ticket", "ticket_no", "`transaction_id`='$transaction_id' and `deletes`='0'", "ticket_no", "");

                                    if ($value[event] == "CAPTURED" && $ticket_no == "") {

                                        $f++;

                                    }

                                }



                                echo $f;

                                ?>

                            </div>

                        </div>



                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="onlineearntable" style="width:100%;">

                                    <thead>

                                        <tr>

                                            <th class="wd-15p border-bottom-0">Transaction ID</th>

                                            <th class="wd-15p border-bottom-0">Ticket No</th>

                                            <th class="wd-15p border-bottom-0">Mobile Number</th>

                                            <th class="wd-20p border-bottom-0">Email ID</th>

                                            <th class="wd-20p border-bottom-0">Amount (AED)</th>

                                            <th class="wd-25p border-bottom-0">Date & Timing</th>

                                            <th class="wd-25p border-bottom-0">Status</th>



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

    var origin = window.location.origin;

    var url = origin + "/ajax/service/transaction_log.php";



    $(function() {

        viewtable();

    });



    function closerequeryStatus(id) {

        $('#' + id).modal('hide');

        viewtable();

    }



    function viewtable() {

        var table = $('#onlineearntable').DataTable();

        table.destroy();

        var formdata = [];

        formdata.push({

            name: 'method',

            value: "payment_log"

        });



        let formdate = $('#formdate').val();

        let todate = $('#todate').val();



        formdata.push({

            name: 'formdate',

            value: formdate

        });

        formdata.push({

            name: 'todate',

            value: todate

        });



        var post_data = formdata;

        var onsuccess = function(data) {



            var response = JSON.parse(data);

            if (response != "") {

                if (response.type == 1) {

                    $('#onlineearntable').DataTable({

                        order: [

                            [0, 'desc']

                        ],

                        dom: 'Bfrtip',

                        buttons: [

                            'copy', 'csv', 'excel', 'pdf', 'print'

                        ],

                        "data": response.result,

                        "columns": [{

                                'data': 'transid'

                            },

                            {

                                'data': 'ticketno'

                            },

                            {

                                'data': 'mobile'

                            },

                            {

                                'data': 'email'

                            },

                            {

                                'data': 'amt'

                            },

                            {

                                'data': 'date'

                            },

                            {

                                'data': 'stauts'

                            }

                        ]



                    });

                } else {

                    var table = $('#onlineearntable').DataTable();

                    table.clear().draw();

                }



            }

        }



        do_ajax_call(post_data, onsuccess, url);



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

        do_ajax_call(post_data, onsuccess, url);

    }

</script>