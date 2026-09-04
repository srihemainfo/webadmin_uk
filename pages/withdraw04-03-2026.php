<?php
$roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'", "roll_id", "");
?>
<style>
    .pri-img img {
        text-align: center;
        margin: 0 auto 10px;
        border-radius: 9px;
    }
    input, button {
        height: 35px; margin: 0; padding: 6px 12px; border-radius: 2px;
        font-family: inherit; font-size: 100%; color: inherit;
    }
    input, select { border: 1px solid #CCC; }
    button { color: #FFF; background-color: #428BCA; border: 1px solid #357EBD; }
    .mdtext { font-weight: 600; }
    button.swal2-confirm.swal2-styled.swal2-default-outline { font-size: 14px; }
    .input101 { width: 100%; }
    textarea {
        width: 100%; height: 150px; padding: 12px 20px; box-sizing: border-box;
        border: 2px solid #ccc; border-radius: 4px; background-color: #f8f8f8;
        font-size: 16px; resize: none;
    }
    .transfer { color: #00db25 !important; }
    .back-arrow-btn i {
        background: #ffffff; font-size: 16px; padding: 2px 3px; border-radius: 50px;
        border: 2px solid #6c6e70; color: #6c6e70; margin-right: 15px; width: 24px; height: 24px;
    }
</style>
<script>
    window.onload = function() {
        var page_origin = window.location.origin;
        let anchor = document.getElementById("anchor");
        if(anchor) anchor.href = page_origin;
    }
</script>
<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <div class="page-header">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Withdraw</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Earnings</li>
                    </ol>
                </div>
            </div>
            
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
                                            <option value="all" selected>All</option>
                                            <option value="1">Success</option>
                                            <option value="0">Process</option>
                                            <option value="2">Reject</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <span>Select Transaction Mode</span>
                                        <select id="trans_mode" class="form-select">
                                            <option value="all" selected>All</option>
                                            <?php
                                            // DYNAMIC TRANSACTION MODES
                                            $mode_res = mysqli_query($con, "SELECT DISTINCT trans_mode FROM withdraw_request WHERE trans_mode IS NOT NULL AND trans_mode != ''");
                                            if($mode_res && mysqli_num_rows($mode_res) > 0) {
                                                while($mode_row = mysqli_fetch_assoc($mode_res)) {
                                                    echo '<option value="' . $mode_row['trans_mode'] . '">' . ucfirst($mode_row['trans_mode']) . '</option>';
                                                }
                                            } else {
                                                echo '<option value="Bank">Bank</option><option value="Cash">Cash</option><option value="Cheque">Cheque</option><option value="upi">UPI</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <span>Select Date</span> &nbsp; <span style="color:red;"></span>
                                        <div>
                                            <input class="form-control" type="text" id="datefilter" name="datefilter" value="" placeholder="YYYY-MM-DD" autocomplete="off" maxlength="10" oninput="this.value = this.value.replace(/[^0-9- \/]/g, '');" />
                                        </div>
                                    </div>
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
                                                <th class="wd-15p border-bottom-0">User ID</th>
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
                                                <th class="wd-15p border-bottom-0">Request id</th>
                                                <th class="wd-15p border-bottom-0">Transaction Date</th>
                                                <th class="wd-15p border-bottom-0">Transaction id</th>
                                                <th class="wd-15p border-bottom-0">Mobile</th>
                                                <th class="wd-15p border-bottom-0">Bank Name</th>
                                                <th class="wd-15p border-bottom-0">Account Number / IBAN / UPI ID</th>
                                                <th class="wd-15p border-bottom-0">IFSC</th>
                                                <th class="wd-15p border-bottom-0">Swift</th>
                                                <th class="wd-15p border-bottom-0">Nationality</th>
                                                <th class="wd-15p border-bottom-0">Live in</th>
                                                <th class="wd-15p border-bottom-0">Action</th>
                                            <?php } else { ?>
                                                <th class="wd-15p border-bottom-0">Request id</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <?php if ($roll_id == 1 || $roll_id == 2 || $roll_id == 4) { ?>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3"></th> <th style="text-align:right">Total:</th>
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

<div class="modal fade" id="withdrawreq" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Withdrawal Request</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="withdrawerr"></div>
                        <div id="withdrawform"></div>
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

<div class="modal fade" id="withdrawtransfer" tabindex="-1" role="dialog">
    <div class="modal-dialog " role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title">Withdrawal Transfer</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div id="wterr"></div>
                <input type="hidden" id="withdrawreqtid">
                <div class="row" id="wdtdiv"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" onclick="refreshdata('withdrawtransfer')">Close</button>
                <div id="withtransbtn"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="withdrawrejcet" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content" style="width: 400px;">
            <div class="modal-header">
                <h5 class="modal-title">Reject Form</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body" id="rejectre"></div>
            <div class="modal-footer">
                <button class="btn btn-danger" onclick="refreshdata('withdrawrejcet')">Close</button>
                <div id="rejectebtn"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="withdrawedit" tabindex="-1" role="dialog" aria-labelledby="withdraweditLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="withdraweditLabel">Edit Reason</h5>
                <button type="button" class="close" onclick="closemodal('withdrawedit')" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="editform"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closemodal('withdrawedit')">Close</button>
                <div id="savechange"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="bakdeatilsmo" tabindex="-1" role="dialog" aria-labelledby="bakdeatilsmoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bakdeatilsmoLabel">Bank Details</h5>
                <button type="button" class="close" onclick="closemodal('bakdeatilsmo')"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="bankdetails"></div>
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
                <button type="button" class="close" onclick="closemodal('bakdeatilsmo1')"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="modal-body1 row pri-img text-center m-auto" id="bankdetails1">
                    <div class="modal-body2 row pri-img text-center m-auto" id="bankdetails1"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closemodal('bakdeatilsmo1')">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var origin = window.location.origin;
    var url = origin + "/ajax/service/withdraw_services.php";
    
    $(function() {
        $('[data-toggle="tooltip"]').tooltip();
        viewtable();
    });

    $(function() {
        $('input[name="datefilter"]').daterangepicker({
            autoUpdateInput: false,
            locale: { cancelLabel: 'Clear' },
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

    function closemodal(id) { $('#' + id).modal('hide'); }

    function refreshdata(str) {
        var table = $('#withdrawtable').DataTable();
        table.destroy();
        viewtable();
        $('#' + str).modal('hide');
    }

    function viewtable() {
        var newdate = $('#datefilter').val();
        var formdate = '';
        var todate = '';
        
        if (newdate != '') {
            formdate = moment($('#datefilter').data('daterangepicker').startDate).format("YYYY-MM-DD HH:mm:ss");
            todate = moment($('#datefilter').data('daterangepicker').endDate).format("YYYY-MM-DD HH:mm:ss");
        }

        let status = $('#status').val();
        let trans_mode = $('#trans_mode').val();
        let filename = 'Withdrawal History';
        
        // Safely parse the PHP variable into JS so it doesn't break if empty
        let current_roll_id = "<?= isset($roll_id) ? $roll_id : '' ?>";
        let isAdminOrStaff = (current_roll_id == '1' || current_roll_id == '2' || current_roll_id == '4');

        // Dynamically define columns to strictly match HTML <th> tags
        let dt_columns = [];
        let dt_footer = null;
        
        if (isAdminOrStaff) {
            // ADMIN MATCHES EXACTLY 21 HTML COLUMNS
            dt_columns = [
                { data: "date" },
                { data: "user_id" }, 
                { data: "fromname" },
                { data: "type" },
                { data: "amt" },
                { data: "wbalance" },
                { data: "Request_Type" },
                { data: "status" },
                { 
                    data: "reason",
                    render: function(data, type, row) {
                        if (row.status === "Reject" && data) {
                            return `<textarea class="form-control light-textarea" style="width:180px; height:90px;" readonly>${data}</textarea>`;
                        }
                        return '';
                    }
                },
                { data: "trans_mode" },
                { data: "request_id" },
                { data: "trans_date" },
                { data: "trans_id" },
                { data: "mobile" },
                { data: "bank" },
                { 
                    data: null, 
                    render: function(data, type, row, meta) {
                        let text = "";
                        if ((data.Request_Type && data.Request_Type.toLowerCase() === 'upi') || (data.trans_mode && data.trans_mode.toLowerCase() === 'upi')) {
                            return data.upiId ? "UPI: " + data.upiId : "-";
                        }
                        if (data.acount_num) text += "\u200B" + data.acount_num.toString();
                        if (data.iban) text += (text ? " / IBAN: " : "IBAN: ") + data.iban;
                        return text;
                    }
                },
                { data: "iban" },
                { data: "swift" },
                { data: "nation" },
                { data: "location" },
                { data: "action" }
            ];

            dt_footer = function(row, data, start, end, display) {
                var api = this.api();
                var intVal = function(i) { return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0; };
                // Using column 4 because "User ID" shifted Amount to index 4
                var total = api.column(4).data().reduce(function(a, b) { return intVal(a) + intVal(b); }, 0);
                var pageTotal = api.column(4, { page: 'current' }).data().reduce(function(a, b) { return intVal(a) + intVal(b); }, 0);
                $(api.column(4).footer()).html('' + pageTotal + ' ( ' + total + ' total)');
            };

        } else {
            // NON-ADMIN MATCHES EXACTLY 7 HTML COLUMNS
            dt_columns = [
                { data: "date" },
                { data: "toname" },
                { data: "amt" },
                { data: "Request_Type" },
                { data: "status" },
                { 
                    data: "reason",
                    render: function(data, type, row) {
                        if (row.status === "Reject" && data) {
                            return `<textarea class="form-control light-textarea" style="width:180px; height:90px;" readonly>${data}</textarea>`;
                        }
                        return '';
                    }
                },
                { data: "request_id" }
            ];
        }

        $("#withdrawtable").DataTable({
            destroy: true,
            pageLength: 10,
            order: [],
            paging: true,
            searching: true,
            info: true,
            ajax: {
                url: url,
                method: "POST",
                dataType: "json", // Force expecting JSON
                dataSrc: function (json) {
                    return json ? json : []; // Handle empty array gracefully
                },
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
                'pageLength', 'copy',
                { extend: 'excelHtml5', title: filename },
                { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'LEGAL', title: filename },
                'print'
            ],
            columns: dt_columns,
            footerCallback: dt_footer
        });
    }

    $(document).on('click', '.view-reason', function(e) {
        e.preventDefault(); 
        var reason = $(this).data('reason'); 
        $(this).closest('td').html(`Reject <br>${reason}`); 
    });

    function user_id_image(request_id, path1, path2) {
        var modal = document.getElementById('bakdeatilsmo1');
        var modalBody = modal.querySelector('.modal-body1');
        
        var img1 = document.createElement('img');
        img1.src = path1; img1.alt = 'Image 1'; img1.style.width = '90%';
        var img2 = document.createElement('img');
        img2.src = path2; img2.alt = 'Image 2'; img2.style.width = '90%';
        
        modalBody.innerHTML = '';
        modalBody.appendChild(document.createTextNode('ID proof Front'));
        modalBody.appendChild(img1);
        modalBody.appendChild(document.createTextNode('ID proof Back'));
        modalBody.appendChild(img2);
        
        $(modal).modal('show');
    }

    function reqwithdraw() {
        document.getElementById('withdrawerr').innerHTML = '';
        let amt = document.getElementById('withdrawamt').value;
        if (parseFloat(amt) > 0) {
            var formdata = [];
            formdata.push({ name: 'method', value: "req_with_draw" });
            formdata.push({ name: 'amount', value: amt });
            $.ajax({
                url: url, type: 'post', data: formdata,
                success: function(data) {
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
            });
        } else {
            document.getElementById('withdrawerr').innerHTML = '<div class="alert alert-danger" role="alert">Please enter the amount</div>';
        }
    }

    function getadmindata() {
        var formdata = [];
        formdata.push({ name: 'method', value: "getadmin_data" });
        $.ajax({
            url: url, type: 'post', data: formdata,
            success: function(data) {
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
        });
    }

    function withdrawtransfer(str) {
        $('#withdrawtransfer').modal('show');
        var formdata = [];
        formdata.push({ name: 'method', value: "withdraw_transfer_verify" });
        formdata.push({ name: 'request', value: str });
        $.ajax({
            url: url, type: 'post', data: formdata,
            success: function(data) {
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
        });
    }

    function transferwdamount(request) {
        document.getElementById('wterr').innerHTML = '';
        let mode = document.getElementById('transactionmode').value;
        let transid = document.getElementById('withdrawid').value;
        let date = document.getElementById('withdrawdate').value;
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
                        
                        let withdrawfile = document.getElementById('withdrawfile');
                        if(withdrawfile && withdrawfile.files.length > 0){
                            formData.append('filesent', withdrawfile.files[0]);
                        }
                        formData.append('reasontext', reasontext);
                        $.ajax({
                            url: url, type: 'post', data: formData, processData: false, contentType: false,
                            success: function(response) {
                                if (response != "") {
                                    if (response.type == '1') {
                                        $('#withdrawtransfer').modal('hide');
                                        paymentSuccess('success', response.result)
                                    } else {
                                        toast('error', response.result);
                                        document.getElementById('withtransbtn').innerHTML = btn;
                                    }
                                }
                            }
                        });
                    } else { toast('error', 'Please Fill Reason'); }
                } else { toast('error', 'Please select date'); }
            } else { toast('error', 'Please enter Transaction id'); }
        } else { toast('error', 'Please Select mode'); }
    }

    function withdrawDecline(request, mobile, from_name, createdon) {
        if (request != '') {
            var formdata = [];
            formdata.push({ name: 'method', value: "withdrawDecline" });
            formdata.push({ name: 'request', value: request });
            formdata.push({ name: 'mobile', value: mobile });
            formdata.push({ name: 'from_name', value: from_name });
            formdata.push({ name: 'createdon', value: createdon });
            $.ajax({
                url: url, type: 'post', data: formdata,
                success: function(data) {
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
            });
        } else { toast('error', 'Request ID Not Found!'); }
    }

    function reject_request(request, mobile, from_name, createdon) {
        let rejectreasontext = $('#rejectreasontext').val();
        let parts = createdon.split('-');
        let formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
        if (rejectreasontext != '') {
            document.getElementById('rejectebtn').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';
            var formdata = [];
            formdata.push({ name: 'method', value: "withdrawDeclineReject" });
            formdata.push({ name: 'request', value: request });
            formdata.push({ name: 'reason', value: rejectreasontext });
            $.ajax({
                url: url, type: 'post', data: formdata,
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response != "") {
                        if (response.type == 1) {
                            toast('success', response.result);
                            viewtable();
                            $('#withdrawrejcet').modal('hide');
                        } else {
                            document.getElementById('rejectebtn').innerHTML = response.rejectbtn;
                            toast('error', response.result);
                            viewtable();
                        }
                    }
                }
            });
        } else { toast('error', 'Please Fill The Reason!'); }
    }

    function editWithDrawDetails(request) {
        if (request != '') {
            var formdata = [];
            formdata.push({ name: 'method', value: "update_withdraw" });
            formdata.push({ name: 'request', value: request });
            $.ajax({
                url: url, type: 'post', data: formdata,
                success: function(data) {
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
            });
        }
    }

    function Bankdeatils(request) {
        if (request != '') {
            var formdata = [];
            formdata.push({ name: 'method', value: "Bank_deatils" });
            formdata.push({ name: 'request', value: request });
            $.ajax({
                url: url, type: 'post', data: formdata,
                success: function(data) {
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
            });
        }
    }

    function toast(icon, message) {
        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 5000, timerProgressBar: true,
            didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
        });
        Toast.fire({ icon: icon, title: message });
    }

    function paymentSuccess(icon, titlestr) {
        Swal.fire({ title: titlestr, icon: icon, confirmButtonColor: '#3085d6', confirmButtonText: 'OKAY' })
            .then((result) => { if (result.isConfirmed) { viewtable(); } });
    }

    function update_withdraw(request) {
        if (request != '') {
            var formdata = [];
            formdata.push({ name: 'method', value: "save_withdraw" });
            formdata.push({ name: 'request', value: request });
            formdata.push({ name: 'transdate', value: $('#transdate').val() });
            formdata.push({ name: 'reasontxt', value: $('#transreason').val() });
            $.ajax({
                url: url, type: 'post', data: formdata,
                success: function(data) {
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
            });
        }
    }
</script>