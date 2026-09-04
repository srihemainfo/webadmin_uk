<style>
    /* Input and Button Defaults */
    input, button, select {
        height: 35px;
        margin: 0;
        padding: 6px 12px;
        border-radius: 4px;
        font-family: inherit;
        font-size: 14px;
        color: inherit;
        border: 1px solid #CCC;
    }

    button.btn-primary {
        color: #FFF;
        background-color: #428BCA;
        border: 1px solid #357EBD;
    }
    button.btn-primary:hover {
        background-color: #3276b1;
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
        text-align: center;
        line-height: 18px;
    }
    
    /* Force text wrapping for amount */
    #onlineearntable td:nth-child(8), 
    #onlineearntable th:nth-child(8) {
        white-space: normal !important;
        text-align: right;
    }

    /* ========================================= */
    /* UI FIX: SEPARATED SOLID TABS */
    /* ========================================= */
    .nav-tabs {
        border-bottom: none; /* Removed the blue connecting line */
        margin-bottom: 20px; /* Separated from the table container */
        gap: 10px; /* Space between the tabs */
    }
    .nav-tabs .nav-item {
        margin-bottom: 0; 
    }
    .nav-tabs .nav-link {
        font-weight: 600;
        color: #555;
        background-color: #f4f6f9;
        border: 1px solid #ddd;
        border-radius: 5px; /* Fully rounded corners for standalone look */
        padding: 10px 20px;
        margin-right: 0;
        transition: all 0.3s ease;
    }
    .nav-tabs .nav-link:hover:not(.active) {
        background-color: #e9ecef;
        border-color: #ccc;
    }
    .nav-tabs .nav-link.active {
        color: #fff !important;
        background-color: #428BCA !important;
        border-color: #428BCA !important;
        box-shadow: 0 4px 6px rgba(66, 139, 202, 0.3); /* Solid and robust elevation */
    }
    
    /* The container holding the tables */
    .tab-content {
        border: 1px solid #ddd; /* Full border around the container */
        padding: 25px 20px;
        background: #fff;
        border-radius: 5px; /* Rounded all corners */
        box-shadow: 0 4px 6px rgba(0,0,0,0.05); /* Slight elevation */
    }
</style>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <br>
            <div class="page-header align-items-center mb-4">
                <h1 class="page-title m-0">
                    <a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)"></i></a> 
                    Payment Reports
                </h1>
                <div class="ms-auto">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reports</li>
                    </ol>
                </div>
            </div>

            <div class="row row-sm">
                <div class="col-lg-12">
                    <ul class="nav nav-tabs" id="reportTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="transaction-tab" data-bs-toggle="tab" data-bs-target="#tab-transaction" type="button" role="tab">Transaction History</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="wallet-tab" data-bs-toggle="tab" data-bs-target="#tab-wallet" type="button" role="tab">Wallet History</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="reportTabsContent">
                        
                        <div class="tab-pane fade show active" id="tab-transaction" role="tabpanel">
                            <div class="row w-100 align-items-end mb-4">
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <span class="form-label mb-1" style="font-size: 13px; font-weight:600;">Select Date <span style="color:red;">*</span></span>
                                    <div id="reportrange-trans" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; border-radius: 4px; height:35px; display:flex; align-items:center; justify-content:space-between;">
                                        <div><i class="fa fa-calendar text-muted"></i>&nbsp; <span></span></div>
                                        <i class="fa fa-caret-down text-muted"></i>
                                    </div>
                                </div>
                                
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <span class="form-label mb-1" style="font-size: 13px; font-weight:600;">Payment Gateway</span>
                                    <select id="gatewayname" class="form-select w-100">
                                        <option value="">Select Gateway</option>
                                        <?php
                                        // PHP dropdown population
                                        $gwQuery = mysqli_query($con, "SELECT DISTINCT `gateway` FROM `payment_history` WHERE `gateway` IS NOT NULL AND `gateway` != '' ORDER BY `gateway` ASC");
                                        while ($row = mysqli_fetch_assoc($gwQuery)) {
                                            echo '<option value="' . $row['gateway'] . '">' . strtoupper(str_replace('_', ' ', $row['gateway'])) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="col-lg-2 col-md-4 mb-3">
                                    <span class="form-label mb-1" style="font-size: 13px; font-weight:600;">Payment Status</span>
                                    <select id="paymentStatus" class="form-select w-100">
                                        <option value="" selected>Select Status</option>
                                        <?php
                                        // PHP dropdown population
                                        $payStatus = mysqli_query($con, "SELECT `paymentStatus` FROM `payment_history` WHERE `gateway` NOT IN ('ldwallet', 'COUPON') AND `paymentStatus` NOT IN ('COUPON', 'WALLET', 'CASH', 'BONUS', '') GROUP BY `paymentStatus` ORDER BY paymentStatus ASC");
                                        while ($row = mysqli_fetch_assoc($payStatus)) {
                                            echo '<option value="' . $row['paymentStatus'] . '">' . ucwords(strtolower($row['paymentStatus'])) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="col-lg-2 col-md-4 mb-3">
                                    <span class="form-label mb-1" style="font-size: 13px; font-weight:600;">Category</span>
                                    <select id="categoryFilter" class="form-select w-100">
                                        <option value="" selected>All Categories</option>
                                        <?php
                                        // PHP dropdown population
                                        $catQuery = mysqli_query($con, "SELECT DISTINCT `category` FROM `payment_history` WHERE `category` IS NOT NULL AND `category` != '' ORDER BY `category` ASC");
                                        while ($row = mysqli_fetch_assoc($catQuery)) {
                                            echo '<option value="' . $row['category'] . '">' . $row['category'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="col-lg-2 col-md-4 mb-3">
                                    <button type="button" class="btn btn-primary w-100 fw-bold" onclick="viewTransactionTable()">Search</button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped border-bottom" id="onlineearntable" style="width:100%;">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Email ID</th>
                                            <th>Job ID</th>
                                            <th>Transaction ID</th>
                                            <th>Gateway</th>
                                            <th>Category</th>
                                            <th>Amount (INR)</th>
                                            <th>Date & Timing</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th><th></th><th></th><th></th><th></th><th></th>
                                            <th style="text-align:right">Total:</th>
                                            <th></th><th></th><th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-wallet" role="tabpanel">
                            <div class="row w-100 align-items-end mb-4">
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <span class="form-label mb-1" style="font-size: 13px; font-weight:600;">Select Date <span style="color:red;">*</span></span>
                                    <div id="reportrange-wallet" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; border-radius: 4px; height:35px; display:flex; align-items:center; justify-content:space-between;">
                                        <div><i class="fa fa-calendar text-muted"></i>&nbsp; <span></span></div>
                                        <i class="fa fa-caret-down text-muted"></i>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 mb-3">
                                    <span class="form-label mb-1" style="font-size: 13px; font-weight:600;">Transaction Type</span>
                                    <select id="walletTransType" class="form-select w-100">
                                        <option value="">All Types</option>
                                        <option value="REFUND">REFUND</option>
                                        <option value="DEBIT">DEBIT</option>
                                        <option value="CREDIT">CREDIT</option>
                                    </select>
                                </div>

                                <div class="col-lg-3 col-md-6 mb-3">
                                    <span class="form-label mb-1" style="font-size: 13px; font-weight:600;">Status</span>
                                    <select id="walletStatus" class="form-select w-100">
                                        <option value="">All Statuses</option>
                                        <option value="SUCCESS">SUCCESS</option>
                                        <option value="PENDING">PENDING</option>
                                        <option value="FAILED">FAILED</option>
                                        <option value="-">- (CRON)</option>
                                    </select>
                                </div>

                                <div class="col-lg-3 col-md-4 mb-3">
                                    <button type="button" class="btn btn-primary w-100 fw-bold" onclick="viewWalletTable()">Search</button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped border-bottom" id="walletHistoryTable" style="width:100%;">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>        <th>Mobile</th>      <th>Email ID</th>    <th>Job No</th>
                                            <th>Transaction Type</th>
                                            <th>Opening Balance</th>
                                            <th>Credit/Debit (Total)</th>
                                            <th>Closing Balance</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                    </div> </div>
            </div>
        </div>
    </div>
</div>

<script>
    var url = window.location.origin + "/ajax/service/transaction_services.php";
    var ccAvenue = '<?= isset($_POST['ccAvenue']) ? $_POST['ccAvenue'] : ''; ?>';

    $(document).ready(function() {
        createDatePricket('reportrange-trans');
        createDatePricket('reportrange-wallet');
        
        // Initial load for active tab
        viewTransactionTable();

        // Load wallet table only when its tab is clicked
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("data-bs-target");
            if (target === "#tab-wallet" && !$.fn.DataTable.isDataTable('#walletHistoryTable')) {
                viewWalletTable();
            }
        });
    });

    function createDatePricket(id) {
        var start = moment();
        var end = moment();

        function cb(start, end) {
            $('#' + id + ' span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));
        }

        $('#' + id).daterangepicker({
            startDate: start.set({ hour: 0, minute: 0, second: 0, millisecond: 0 }),
            endDate: end.set({ hour: 23, minute: 59, second: 59, millisecond: 59 }),
            timePicker: true,
            timePicker24Hour: true,
            timePickerSeconds: true,
            maxSpan: { days: 365 },
            autoUpdateInput: true,
            maxDate: moment().add(1, 'days').toDate(),
            ranges: {
                'Today': [moment().set({hour: 0, minute: 0, second: 0, millisecond: 0}), moment().set({hour: 23, minute: 59, second: 59, millisecond: 59})],
                'Last 7 Days': [moment().subtract(6, 'days').set({hour: 0, minute: 0, second: 0, millisecond: 0}), moment().set({hour: 23, minute: 59, second: 59, millisecond: 59})],
                'This Month': [moment().startOf('month').set({hour: 0, minute: 0, second: 0, millisecond: 0}), moment().endOf('month').set({hour: 23, minute: 59, second: 59, millisecond: 59})],
                'Last Month': [moment().subtract(1, 'month').startOf('month').set({hour: 0, minute: 0, second: 0, millisecond: 0}), moment().subtract(1, 'month').endOf('month').set({hour: 23, minute: 59, second: 59, millisecond: 59})]
            }
        }, cb);

        cb(start, end);
    }

    // --- TAB 1: TRANSACTION AJAX ---
    function viewTransactionTable() {
        var gatewayname = $('#gatewayname').val();
        var paymentStatus = $('#paymentStatus').val();
        var categoryFilter = $('#categoryFilter').val(); 
        var formdate = moment($('#reportrange-trans').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
        var todate = moment($('#reportrange-trans').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");

        $("#onlineearntable").DataTable({
            destroy: true,
            autoWidth: false, 
            pageLength: 10,
            order: [[, 'desc']], 
            language: {
                emptyTable: "No data available for the selected filters",
                zeroRecords: "No data available for the selected filters"
            },
            columnDefs: [
                { type: 'date', targets: [8] },
                { width: '70px', targets: 7 } 
            ],
            ajax: {
                url: url,
                method: "POST",
                dataSrc: "",
                data: {
                    method: 'payment_status_history',
                    formdate: formdate,
                    todate: todate,
                    gatewayname: gatewayname,
                    paymentStatus: paymentStatus,
                    categoryFilter: categoryFilter,
                    ccAvenue: ccAvenue
                }
            },
            dom: 'Bfrtip',
            buttons: ['pageLength', 'copy', { extend: 'excelHtml5', title: 'Payment Status History' }],
            columns: [
                { data: 'name', defaultContent: '' },
                { data: 'mobile', defaultContent: '' },
                { data: 'email', defaultContent: '' },
                { data: 'job_no', defaultContent: 'N/A' }, 
                { data: 'transid', defaultContent: 'N/A' }, 
                { data: 'gateway', defaultContent: '' },
                { data: 'category', defaultContent: '' },
                { data: 'amt', defaultContent: '0' },
                {
                    data: 'date',
                    render: function(data) { return data ? moment(data).format("DD MMM YYYY hh:mm a") : ''; }
                },
                { data: 'stauts', defaultContent: '' }
            ],
            footerCallback: function(row, data, start, end, display) {
                var api = this.api();
                var intVal = function(i) { return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0; };
                
                var total = api.column(7).data().reduce(function(a, b) { return intVal(a) + intVal(b); }, 0);
                var pageTotal = api.column(7, { page: 'current' }).data().reduce(function(a, b) { return intVal(a) + intVal(b); }, 0);

                $(api.column(7).footer()).html(pageTotal.toFixed(2) + ' <br>( ' + total.toFixed(2) + ' total)');
            }
        });
    }

    // --- TAB 2: WALLET AJAX ---
    function viewWalletTable() {
        var formdate = moment($('#reportrange-wallet').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
        var todate = moment($('#reportrange-wallet').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");
        
        // Grab the new filter values
        var transType = $('#walletTransType').val();
        var status = $('#walletStatus').val();

        $("#walletHistoryTable").DataTable({
            destroy: true,
            autoWidth: false, 
            pageLength: 10,
            order: [[10, 'desc']], 
            language: {
                emptyTable: "No data available for the selected filters",
                zeroRecords: "No data available for the selected filters"
            },
            ajax: {
                url: url,
                method: "POST",
                dataSrc: "",
                data: {
                    method: 'wallet_history_table',
                    formdate: formdate,
                    todate: todate,
                    transType: transType, // Send Trans Type to backend
                    status: status        // Send Status to backend
                }
            },
            dom: 'Bfrtip',
            buttons: ['pageLength', 'copy', { extend: 'excelHtml5', title: 'Wallet History Report' }],
            columns: [
                { data: 'ID', defaultContent: '' },
                { data: 'name', defaultContent: '-' },    // <-- NEW
                { data: 'mobile', defaultContent: '-' },  // <-- NEW
                { data: 'email', defaultContent: '-' },   // <-- NEW
                { data: 'job_no', defaultContent: '-' },
                { data: 'transaction_type', defaultContent: '' },
                { data: 'opening_balance', defaultContent: '0' },
                { data: 'total', defaultContent: '0' },
                { data: 'closeing_balance', defaultContent: '0' },
                { data: 'paymentStatus', defaultContent: '' },
                {
                    data: 'date',
                    render: function(data) { return data ? moment(data).format("DD MMM YYYY hh:mm a") : ''; }
                }
            ]
        });
    }
</script>