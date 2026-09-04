<?php
$roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'", "roll_id", "");
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
        if (anchor) anchor.href = page_origin;
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
                                            <option value="upi">UPI</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <span>Select Date</span> &nbsp; <span style="color:red;"></span>
                                        <div>
                                            <input class="form-control" type="text" id="datefilter" name="datefilter" value="" placeholder="YYYY-MM-DD" autocomplete="off" maxlength="23" oninput="this.value = this.value.replace(/[^0-9- \/]/g, '');" />
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
                                                <th class="wd-15p border-bottom-0">Account Number / IBAN / UPI ID</th>
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
                                                <th colspan="3" style="text-align:right">Total:</th>
                                                <th></th>
                                                <th colspan="11"></th>
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
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title" id="bakdeatilsmoLabel">Bank Details</h5>
                <button type="button" class="btn-close" onclick="closemodal('bakdeatilsmo')" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body" id="bankdetails">
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-info px-4" onclick="closemodal('bakdeatilsmo')">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    // API URLS
    var API_BASE_URL = "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>";
    var legacy_url = window.location.origin + "/ajax/service/withdraw_services.php";

    // GLOBAL AJAX SETUP TO PASS THE BEARER TOKEN TO ALL REQUESTS
    $.ajaxSetup({
        headers: {
            "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
        }
    });

    // Helper to safely parse AJAX responses
    function safeParse(data) {
        if (typeof data === 'object') return data;
        try {
            return JSON.parse(data);
        } catch (e) {
            return { type: 0, result: "Unexpected server response" };
        }
    }

    $(function() {
        $('[data-toggle="tooltip"]').tooltip();
        
        $('input[name="datefilter"]').daterangepicker({
            autoUpdateInput: true,
            startDate: moment().startOf('day'),
            endDate: moment().endOf('day'),
            locale: { cancelLabel: 'Clear', format: 'MM/DD/YYYY' },
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

        viewtable();
    });

    function closemodal(id) {
        $('#' + id).modal('hide');
    }

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
        let current_roll_id = "<?= isset($roll_id) ? $roll_id : '' ?>";
        let isAdminOrStaff = (current_roll_id == '1' || current_roll_id == '2' || current_roll_id == '4');
        let dt_columns = [];
        let dt_footer = null;

        if (isAdminOrStaff) {
            dt_columns = [
                { data: "date" }, { data: "fromname" }, { data: "type" }, { data: "amt" }, { data: "wbalance" },
                { data: "Request_Type" }, { data: "status" },
                {
                    data: "reason",
                    render: function(data, type, row) {
                        let statusText = row.status ? row.status.toString().toLowerCase() : '';
                        if ((statusText.includes("reject") || statusText.includes("success")) && data) {
                            return `<textarea class="form-control light-textarea" style="width:180px; height:90px;" readonly>${data}</textarea>`;
                        }
                        return '';
                    }
                },
                { data: "trans_mode" }, { data: "request_id" }, { data: "trans_date" }, { data: "trans_id" }, { data: "mobile" },
                {
    data: null,
    render: function(data, type, row, meta) {
        if ((data.Request_Type && data.Request_Type.toLowerCase() === 'upi') || (data.trans_mode && data.trans_mode.toLowerCase() === 'upi')) {
            return data.upiId ? "UPI: " + data.upiId : "-";
        }
        
        let text = "";
        if (data.acount_num) text += "\u200B" + data.acount_num.toString();
        if (data.iban) text += (text ? " / IBAN: " : "IBAN: ") + data.iban;
        
        // Fallback: If no account number or IBAN exists, but a upiId is available, show it
        if (!text && data.upiId) {
            return "UPI: " + data.upiId;
        }
        
        return text || "-";
    }
},
                {
                    data: "action",
                    render: function(data, type, row) {
                        if (!data) return '';
                        try {
                            let tempDiv = document.createElement('div');
                            tempDiv.innerHTML = data;
                            let links = tempDiv.querySelectorAll('a, button, span, div.dropdown-item');
                            links.forEach(link => {
                                let text = (link.innerText || link.innerHTML).toUpperCase();
                                if (text.includes('ID PROOF') || (link.getAttribute('onclick') && link.getAttribute('onclick').includes('user_id_image'))) {
                                    link.remove();
                                }
                                if (text.includes('BANK DETAILS') || (link.getAttribute('onclick') && link.getAttribute('onclick').includes('Bankdeatils'))) {
                                    let upi = row.upiId ? row.upiId : 'Not Available';
                                    link.removeAttribute('onclick');
                                    link.removeAttribute('href');
                                    link.setAttribute('onclick', `showUpiDetails('${upi}')`);
                                    link.classList.add('cursor-pointer');
                                }
                                if (text.includes('TRANSFER') || (link.getAttribute('onclick') && link.getAttribute('onclick').includes('withdrawtransfer'))) {
                                    let match = link.getAttribute('onclick') ? link.getAttribute('onclick').match(/'([^']+)'/) : null;
                                    if (match) {
                                        let reqId = match[1];
                                        let upi = row.upiId ? row.upiId : 'Not Available';
                                        let amt = row.amt ? row.amt : '0.00';
                                        link.removeAttribute('onclick');
                                        link.removeAttribute('href');
                                        link.setAttribute('onclick', `withdrawtransfer('${reqId}', '${amt}', '${upi}')`);
                                        link.classList.add('cursor-pointer');
                                    }
                                }
                            });
                            return tempDiv.innerHTML;
                        } catch (e) {
                            return data;
                        }
                    }
                }
            ];
            dt_footer = function(row, data, start, end, display) {
                var api = this.api();
                var intVal = function(i) { return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0; };
                var total = api.column(3).data().reduce(function(a, b) { return intVal(a) + intVal(b); }, 0);
                var pageTotal = api.column(3, { page: 'current' }).data().reduce(function(a, b) { return intVal(a) + intVal(b); }, 0);
                $(api.column(3).footer()).html(pageTotal + ' (' + total + ' Total)');
            };
        } else {
            dt_columns = [
                { data: "date" }, { data: "toname" }, { data: "amt" }, { data: "Request_Type" }, { data: "status" },
                {
                    data: "reason",
                    render: function(data, type, row) {
                        if (row.status === "Reject" && data) return `<textarea class="form-control light-textarea" style="width:180px; height:90px;" readonly>${data}</textarea>`;
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
                url: API_BASE_URL + "/admin-withdraw-list",
                type: "POST",
                dataType: "json",
                dataSrc: function(json) {
                    if (!json) return [];
                    if (Array.isArray(json)) return json;
                    if (json.result && Array.isArray(json.result)) return json.result;
                    if (json.data && Array.isArray(json.data)) return json.data;
                    return [];
                },
                data: {
                    formdate: formdate,
                    todate: todate,
                    status: status,
                    trans_mode: trans_mode
                },
                error: function(xhr, error, thrown) {
                    toast('error', 'Error loading data from server');
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

    function showUpiDetails(upiId) {
        let content = `
            <div class="d-flex justify-content-between align-items-center px-2 py-3" style="border-bottom: 1px solid #eee;">
                <span class="fw-bold text-dark" style="font-size: 15px;">UPI ID</span>
                <span class="text-muted">${upiId}</span>
            </div>
        `;
        document.getElementById('bankdetails').innerHTML = content;
        $('#bakdeatilsmo').modal('show');
    }

    function withdrawtransfer(reqId, amount = '0.00', upi = 'Not Available') {
        document.getElementById('wterr').innerHTML = '';
        
        let htmlContent = `
            <div class="col-md-12">
                <table class="table table-bordered mb-0">
                    <tbody>
                        <tr>
                            <th style="width: 40%; vertical-align: middle; background-color: #f8f9fa;">Amount</th>
                            <td style="vertical-align: middle;"><span class="fw-bold text-dark">INR ${amount}</span></td>
                        </tr>
                        <tr>
                            <th style="vertical-align: middle; background-color: #f8f9fa;">UPI ID</th>
                            <td style="vertical-align: middle;"><span class="fw-bold text-dark">${upi}</span></td>
                        </tr>
                        <tr>
                            <th style="vertical-align: middle; background-color: #f8f9fa;">Transaction Mode</th>
                            <td>
                                <select id="transactionmode" class="form-select">
                                    <option value="">Select Mode</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Bank">Bank</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="UPI" selected>UPI</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th style="vertical-align: middle; background-color: #f8f9fa;">Transaction ID</th>
                            <td><input type="text" id="withdrawid" class="form-control" placeholder="Enter Transaction ID"></td>
                        </tr>
                        <tr>
                            <th style="vertical-align: middle; background-color: #f8f9fa;">Transaction Date</th>
                            <td><input type="date" id="withdrawdate" class="form-control" value="${new Date().toISOString().split('T')[0]}"></td>
                        </tr>
                        <tr>
                            <th style="vertical-align: middle; background-color: #f8f9fa;">Transaction File</th>
                            <td>
                                <input type="file" id="withdrawfile" class="form-control" accept=".jpg,.png,.webp,.pdf">
                                <small class="text-muted d-block mt-1" style="font-size: 11px;">Upload File size less than 2MB<br>Upload File Format (jpg,png,webp,pdf)</small>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="background-color: #f8f9fa;">
                                <textarea id="reasontext" class="form-control" placeholder="Reason" rows="3" style="height: 80px;"></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;

        let btnContent = `<button class="btn btn-success" onclick="transferwdamount('${reqId}')"><i class="fa fa-money"></i> TRANSFER</button>`;
        document.getElementById('wdtdiv').innerHTML = htmlContent;
        document.getElementById('withtransbtn').innerHTML = btnContent;
        $('#withdrawtransfer').modal('show');
    }

    $(document).on('click', '.view-reason', function(e) {
        e.preventDefault();
        var reason = $(this).data('reason');
        $(this).closest('td').html(`Reject <br>${reason}`);
    });

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
                        formData.append('request', request);
                        formData.append('mode', mode);
                        formData.append('transid', transid);
                        formData.append('date', date);
                        let withdrawfile = document.getElementById('withdrawfile');
                        if (withdrawfile && withdrawfile.files.length > 0) {
                            formData.append('filesent', withdrawfile.files[0]);
                        }
                        formData.append('reasontext', reasontext);

                        $.ajax({
                            url: API_BASE_URL + "/admin-withdraw-transfer",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(data) {
                                var response = safeParse(data);
                                if (response != "") {
                                    if (response.type == '1') {
                                        $('#withdrawtransfer').modal('hide');
                                        paymentSuccess('success', response.result || 'Transfer successful');
                                    } else {
                                        toast('error', response.result || 'Transfer failed');
                                        document.getElementById('withtransbtn').innerHTML = btn;
                                    }
                                }
                            },
                            error: function() {
                                toast('error', 'Server communication failed');
                                document.getElementById('withtransbtn').innerHTML = btn;
                            }
                        });
                    } else { toast('error', 'Please Fill Reason'); }
                } else { toast('error', 'Please select date'); }
            } else { toast('error', 'Please enter Transaction id'); }
        } else { toast('error', 'Please Select mode'); }
    }

    function withdrawDecline(request, mobile, from_name, createdon) {
        if (request != '') {
            var formdata = {
                request: request, mobile: mobile, from_name: from_name, createdon: createdon
            };

            $.ajax({
                url: API_BASE_URL + "/admin-withdraw-decline-form",
                type: "POST",
                data: formdata,
                success: function(data) {
                    var response = safeParse(data);
                    if (response != "") {
                        if (response.type == 1) {
                            $('#withdrawrejcet').modal('show');
                            document.getElementById('rejectebtn').innerHTML = response.rejectbtn;
                            document.getElementById('rejectre').innerHTML = response.result;
                        } else {
                            toast('error', response.result || 'Failed to fetch details');
                            viewtable();
                        }
                    }
                }
            });
        } else { toast('error', 'Request ID Not Found!'); }
    }

    function reject_request(request, mobile, from_name, createdon) {
        let rejectreasontext = $('#rejectreasontext').val();
        if (rejectreasontext != '') {
            document.getElementById('rejectebtn').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';
            var formdata = { request: request, reason: rejectreasontext };

            $.ajax({
                url: API_BASE_URL + "/admin-withdraw-reject",
                type: "POST",
                data: formdata,
                success: function(data) {
                    var response = safeParse(data);
                    if (response != "") {
                        if (response.type == 1) {
                            toast('success', response.result || 'Rejected successfully');
                            viewtable();
                            $('#withdrawrejcet').modal('hide');
                        } else {
                            document.getElementById('rejectebtn').innerHTML = response.rejectbtn;
                            toast('error', response.result || 'Failed to reject');
                            viewtable();
                        }
                    }
                }
            });
        } else { toast('error', 'Please Fill The Reason!'); }
    }


    /* -------------------------------------------------------------
       EDIT & SAVE FUNCTIONS (POINTING STRICTLY TO PHP SCRIPT)
    ------------------------------------------------------------- */
    function editWithDrawDetails(request) {
        if (request != '') {
            $.ajax({
                url: legacy_url, // points to origin + "/ajax/service/withdraw_services.php"
                type: "POST",
                data: {
                    method: 'update_withdraw',
                    request: request
                },
                success: function(data) {
                    var response = safeParse(data);
                    if (response != "") {
                        if (response.type == 1) {
                            document.getElementById('editform').innerHTML = response.output;
                            if (document.getElementById('transreason')) {
                                document.getElementById('transreason').value = response.reason;
                            }
                            document.getElementById('savechange').innerHTML = response.savechange;
                            $('#withdrawedit').modal('show');
                        } else {
                            toast('error', response.result || 'Could not load details');
                        }
                    }
                },
                error: function() {
                    toast('error', 'Server error while loading edit details.');
                }
            });
        }
    }

    function update_withdraw(request) {
        if (request != '') {
            let btnHTML = document.getElementById('savechange').innerHTML;
            document.getElementById('savechange').innerHTML = '<button class="btn btn-success" disabled><span class="spinner-border spinner-border-sm"></span> Saving...</button>';

            $.ajax({
                url: legacy_url, // points to origin + "/ajax/service/withdraw_services.php"
                type: "POST",
                data: {
                    method: 'save_withdraw',
                    request: request,
                    transdate: $('#transdate').val(),
                    reasontxt: $('#transreason').val()
                },
                success: function(data) {
                    var response = safeParse(data);
                    if (response != "") {
                        if (response.type == 1) {
                            $('#withdrawedit').modal('hide');
                            paymentSuccess('success', response.result || 'Updated successfully');
                        } else {
                            toast('error', response.result || 'Update failed');
                            document.getElementById('savechange').innerHTML = btnHTML;
                        }
                    }
                },
                error: function() {
                    toast('error', 'Server error');
                    document.getElementById('savechange').innerHTML = btnHTML;
                }
            });
        }
    }
    /* ------------------------------------------------------------- */


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
        Toast.fire({ icon: icon, title: message });
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
        });
    }
</script>