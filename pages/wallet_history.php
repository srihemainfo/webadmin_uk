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
        //  alert(anchor.href);
    }
</script>
<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <br>
            <div class="page-header">
                <!--<h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Payments Status History</h1>-->
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)"></i></a>Wallet History</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Wallet</li>
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
                                <br>

                                <div class="col-lg-4 col-md-6">
                                    <span>Select Date</span> &nbsp; 
                                    <!-- <span style="color:red;">*</span> -->
                                    <input class="form-control" type="text" id="datefilter" name="datefilter" value="" placeholder="YYYY-MM-DD" autocomplete="off" maxlength="10" oninput="this.value = this.value.replace(/[^0-9-]/g, '');" />
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <span>Search User</span>
                                    <input class="form-control" type="text" oninput="viewtable($(this).val())" id="searchTxt" placeholder="Name / Email / Mobile No">
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <span>Select Transaction</span>
                                    <select id="gatewayname" class="form-select">
                                        <option value="" selected>Select Transaction</option>
                                        <option value="PURCHASE">New-Purchase Using Wallet</option>
                                        <option value="RENEWAL">Renewal</option>
                                        <option value="WALLETTRANSFER">Transfer Wallet</option>
                                        <option value="ADWALLETTRANSFER">Adjustment With Wallet</option>
                                        <option value="WalletDeposit">Wallet Top Up</option>
                                    </select>
                                </div>

                                <div class="col-lg-4 col-md-6 mt-5" id="paySearchBtn">
                                    <button type="submit" id="smslogsearch" class="btn btn-info" onclick="viewtable()">Go</button>
                                </div>
                            </div>
                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="onlineearntable" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p border-bottom-0">Name</th>
                                            <th class="wd-20p border-bottom-0">Mobile</th>
                                            <th class="wd-20p border-bottom-0">Email ID</th>
                                            <th class="wd-15p border-bottom-0">Transaction Type</th>
                                            <th class="wd-15p border-bottom-0">Wallet Opening Balance</th>
                                            <th class="wd-15p border-bottom-0">Transaction Amount</th>
                                            <th class="wd-15p border-bottom-0">Total Balance</th>
                                            <th class="wd-15p border-bottom-0">Reward Type</th>
                                            <th class="wd-20p border-bottom-0">Transaction Date & time</th>

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
<!--Code Start By Prashant At 08-06-2023-->
<script>
    var url = window.location.origin + "/ajax/service/transaction_services.php";
    var ccAvenue = '<?= $_POST['ccAvenue']; ?>';

    $(function() {
        // Initialize the date range picker
        // createDatePricker('reportrange');

        // Call the viewtable function
        viewtable();
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








    function closerequeryStatus(id) {

        $('#' + id).modal('hide');

        viewtable();

    }





    function viewtable() {
        // alert('hello');
        var gatewayname = $('#gatewayname').val();
        var searchTxt = $('#searchTxt').val();

        var newdate = $('#datefilter').val();
        // alert(deva);
        if (newdate != '') {
            var formdate = moment($('#datefilter').data('daterangepicker').startDate).format("YYYY-MM-DD HH:mm:ss");
            var todate = moment($('#datefilter').data('daterangepicker').endDate).format("YYYY-MM-DD HH:mm:ss");
        } else {
            var formdate = '';
            var todate = '';
        }
        // var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
        // var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");
        // var title = 'Wallet History: (' + formdate + ' to ' + todate + ' ' + gatewayname + ')';

        // let filename = 'Participation List ' + $('#draw_new_id option:selected').text();
        let filename = 'Wallet History:';

        // alert('k');

        table = $('#onlineearntable').DataTable({


            destroy: true,
            pageLength: 10,
            order: [],
            paging: true,
            searching: true,
            info: true,
            ajax: {
                url: origin + "/ajax/service/transaction_services.php",
                method: "POST",
                dataSrc: "",
                data: {
                    method: 'Wallet_history',
                    formdate: formdate,
                    todate: todate,
                    searchTxt: searchTxt,
                    gatewayname: gatewayname,

                }
            },
            order: [
                [0, 'desc']
            ],
            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                'copy',


                {

                    extend: 'excelHtml5',
                    title: filename

                },


            ],


            columns: [{
                    data: "uname"
                },
                {
                    data: 'umobile'
                },
                {
                    data: 'uemail'
                },
                {
                    data: 'transaction_type'
                },
                {
                    data: 'opening_balance'
                },
                {
                    data: 'total'
                },
                {
                    data: 'closeing_balance'
                }, // Correct the spelling to 'closing_balance'
                {
                    data: 'reward_type',
                    render: function(data, type, row, meta) {
                        switch (data) {
                            case 'EXCHANGEWITHDRAWAL':
                                return 'EXCHANGE WITHDRAWAL';
                            case 'WALLETTRANSFER':
                                return 'WALLET TRANSFER';
                            case 'ADWALLETTRANSFER':
                                return 'AD. WALLET TRANSFER';
                            case 'BANKWITHDRAWAL':
                                return 'BANK WITHDRAWAL';
                            case 'AGENTTOPUP':
                                return 'AGENT TOPUP';
                            case 'BANKWITHDRAWAL':
                                return 'BANK WITH DRAWAL';
                            case 'RE_BANKWITHDRAWAL':
                                return 'REJECT WITH DRAWAL';
                            case 'EXCHANGEWITHDRAWAL':
                                return 'EXCHANGE WITH DRAWAL';
                            case 'RE_EXCHANGEWITHDRAWAL':
                                return 'REJECT EXCHANGE WITH DRAWAL';
                            case 'ADWALLETTRANSFER':
                                return 'AJUSTMENT WITH WALLET';
                            default:
                                return data;
                        }
                    }
                },
                {
                    data: 'createdon',
                    render: function(data, type, row, meta) {
                        // Assuming 'data' is in the format '2024-05-02 13:12:20'
                        return moment(data, "YYYY-MM-DD HH:mm:ss").format("DD MMM YYYY hh:mm a");
                    }
                },
            ],




        });

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