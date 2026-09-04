<?php
/**
 *      Date            Developer_name      Modifications
 *      26-05-2023      Prashant            Payment Enable/Disable Options
 * */


$pageTitle = 'Payment Settings';

$settings1 = mysqli_query($con, "SELECT * FROM `sms_switch` WHERE `site` = 'adminsite' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1");
$adminsite = mysqli_fetch_assoc($settings1);

$getPayment = select_query($con, "payment_switch", "",  "`deletes` = '0'", "", "");
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
                <div class="col-xl-8">
                    <form id="editprofileform">
                        <div class="card ">
                            <div class="card-header">
                                <h3 class="card-title">Payment Enable/Disable Options</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php if($getPayment['nr'] > 0){ foreach($getPayment['result'] as $key => $gatewaylist){ ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="<?= $gatewaylist['gateway'] ?>"><?= $gatewaylist['gateway'] ?></label>
                                            <input type="checkbox" class="payment_status_change" <?php if($gatewaylist['status'] == '0'){ echo "checked"; } ?> data-id="<?= $gatewaylist['id'] ?>" data-toggle="toggle" data-on="Enabled" data-off="Disabled" data-onstyle="success" data-offstyle="danger">
                                        </div>
                                    </div>
                                    <?php } } ?>
                                </div>
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

                                <div class="col-4">
                                    <h3 class="card-title"><?= ucwords('Payment Enable/Disable History'); ?></h3>
                                </div>

                                <div class="col-4">
                                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"><i class="fa fa-calendar"></i>&nbsp;<span></span> <i class="fa fa-caret-down"></i>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <button class="btn btn-info" onclick="viewtable()">Go</button>
                                </div>
                            </div>
                        </div>

                        <div class=" card-body">
                            <table class=" table-responsive table table-bordered text-nowrap border-bottom" id="paymentSwitchtable">
                                <thead>
                                    <tr>
                                        <th class="wd-15p border-bottom-0">Sr No</th>
                                        <th class="wd-15p border-bottom-0">Gateway</th>
                                        <th class="wd-15p border-bottom-0">Changed BY</th>
                                        <th class="wd-15p border-bottom-0">Status</th>
                                        <th class="wd-15p border-bottom-0">Updated At</th>
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
    <script>
        $(function() {
            createDatePricket('reportrange');
            viewtable();
            
            $('.payment_status_change').change(function(e){
                var status = $(this).is(':checked');
                var id = $(this).data('id');
               
                if(status == false){
                    var statustext =  'Disable';
                    var typetext =  'red';
                    var type = 1;
                } else{
                    var statustext =  'Enable';
                    var typetext =  'green';
                    var type = 0;
                }
                
                $.confirm({
                    title: 'Confirmation',
                    content: 'Are you sure? you want to '+statustext+' the payment gateway ?',
                    type: typetext,
                    typeAnimated: true,
                    buttons: {
                        confirm: {
                            text: 'Yes',
                            action: function(){
                                changePaymentGateWay(type,id)
                            }
                        },
                        No: function () {
                            text: 'No',
                            location.reload();
                        }
                    }
                });
            })
        });
        
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
        
        function changePaymentGateWay(status, id) {
                var formdata = [];

                formdata.push({

                    name: 'method',
                    value: "changePaymentGateWay"

                });

                formdata.push({
                    name: 'status',
                    value: status
                });

                formdata.push({
                    name: 'id',
                    value: id

                });

                var post_data = formdata;
                
                var onsuccess = function(data) {
                    var response = JSON.parse(data);
                    if (response != "") {
                        if (response.type == '1') {
                            toast('success', response.result);
                            location.reload()
                        } else {
                            toast('error', response.result);
                            location.reload()
                        }
                    }
                }
                do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/payment_switch.php");
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
        
        function viewtable() {
            var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
            var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");

            var table = $('#paymentSwitchtable').DataTable();
            table.destroy();
            
            var title = 'Payment Enable/Disable History';
            
            table = $("#paymentSwitchtable").DataTable({
                pageLength: 10,
                paging: true,
                searching: true,
                autoWidth: false,
                info: true,
                ajax: {

                    url: window.location.origin + "/ajax/service/payment_switch.php",
                    method: "POST",
                    dataSrc: "",
                    data: {
                        method: 'paymentswitchhistory',
                        formdate: formdate,
                        todate: todate
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
                        data: 'srno'
                    },
                    {
                        data: 'gateway'
                    },
                    {
                        data: 'changedby'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'createdon'
                    }
                ],
            });
        }
        
    </script>