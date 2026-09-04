<style>
    input, button {
        height: 35px;
        margin: 0;
        padding: 6px 12px;
        border-radius: 2px;
        font-family: inherit;
        font-size: 100%;
        color: inherit;
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

    input, select {
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
    
    /* Force text wrapping for amount to keep it narrow */
    #onlineearntable td:nth-child(8), 
    #onlineearntable th:nth-child(8) {
        white-space: normal !important;
        text-align: right;
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
        <div class="main-container container-fluid">
            <br>
            <div class="page-header">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)"></i></a>Payments Status History</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payments</li>
                    </ol>
                </div>
            </div>
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
    <div class="row w-100 align-items-end">
        <div class="col-12 mb-3">
            <h3 class="card-title"><strong>Transaction History</strong></h3>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <span class="form-label mb-1" style="font-size: 13px;">Select Date <span style="color:red;">*</span></span>
            <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; border-radius: 4px;">
                <i class="fa fa-calendar"></i>&nbsp;
                <span></span> <i class="fa fa-caret-down float-end mt-1"></i>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <span class="form-label mb-1" style="font-size: 13px;">Payment Gateway</span>
            <select id="gatewayname" class="form-select">
                <option value="">Select Gateway</option>
                <?php
                $gwQuery = mysqli_query($con, "SELECT DISTINCT `gateway` FROM `payment_history` WHERE `gateway` IS NOT NULL AND `gateway` != '' ORDER BY `gateway` ASC");
                while ($row = mysqli_fetch_assoc($gwQuery)) {
                ?>
                    <option value="<?= $row['gateway']; ?>"><?= strtoupper(str_replace('_', ' ', $row['gateway'])); ?></option>
                <?php
                }
                ?>
            </select>
        </div>
        
        <div class="col-lg-2 col-md-4 mb-3">
            <span class="form-label mb-1" style="font-size: 13px;">Payment Status</span>
            <select id="paymentStatus" class="form-select">
                <option value="" selected>Select Status</option>
                <?php
                $payStatus = mysqli_query($con, "SELECT `paymentStatus` FROM `payment_history` WHERE `gateway` NOT IN ('ldwallet', 'COUPON') AND `paymentStatus` NOT IN ('COUPON', 'WALLET', 'CASH', 'BONUS', '') GROUP BY `paymentStatus` ORDER BY paymentStatus ASC");
                while ($row = mysqli_fetch_assoc($payStatus)) {
                ?>
                    <option value="<?= $row['paymentStatus']; ?>"><?= ucwords(strtolower($row['paymentStatus'])); ?></option>
                <?php
                }
                ?>
            </select>
        </div>
        
        <div class="col-lg-2 col-md-4 mb-3">
            <span class="form-label mb-1" style="font-size: 13px;">Category</span>
            <select id="categoryFilter" class="form-select">
                <option value="" selected>All Categories</option>
                <?php
                $catQuery = mysqli_query($con, "SELECT DISTINCT `category` FROM `payment_history` WHERE `category` IS NOT NULL AND `category` != '' ORDER BY `category` ASC");
                while ($row = mysqli_fetch_assoc($catQuery)) {
                ?>
                    <option value="<?= $row['category']; ?>"><?= $row['category']; ?></option>
                <?php
                }
                ?>
            </select>
        </div>
        
        <div class="col-lg-2 col-md-4 mb-3" id="paySearchBtn">
            <button type="submit" id="smslogsearch" class="btn btn-primary w-100" onclick="viewtable()">Go</button>
        </div>
    </div>
</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered border-bottom" id="onlineearntable" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">Name</th>
                                            <th class="border-bottom-0">Mobile</th>
                                            <th class="border-bottom-0">Email ID</th>
                                            <th class="border-bottom-0">Job ID</th>
                                            <th class="border-bottom-0">Transaction ID</th>
                                            <th class="border-bottom-0">Gateway</th>
                                            <th class="border-bottom-0">Category</th>
                                            <th class="border-bottom-0">Amount (INR)</th>
                                            <th class="border-bottom-0">Date & Timing</th>
                                            <th class="border-bottom-0">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var url = window.location.origin + "/ajax/service/transaction_services.php";
    var ccAvenue = '<?= isset($_POST['ccAvenue']) ? $_POST['ccAvenue'] : ''; ?>';

    $(function() {
        createDatePricket('reportrange');
        
        // REMOVED the if() condition here. 
        // This forces the table to fetch and show data instantly when landing on the page.
        viewtable();
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
                'Last Month': [moment().subtract(1, 'month').startOf('month').set({hour: 0, minute: 0, second: 0, millisecond: 0}), moment().subtract(1, 'month').endOf('month').set({hour: 23, minute: 59, second: 59, millisecond: 59})],
                'This Year': [moment().startOf('year').set({hour: 0, minute: 0, second: 0, millisecond: 0}), moment().endOf('year').set({hour: 23, minute: 59, second: 59, millisecond: 59})],
                'Last Year': [moment().subtract(1, 'year').startOf('year').set({hour: 0, minute: 0, second: 0, millisecond: 0}), moment().subtract(1, 'year').endOf('year').set({hour: 23, minute: 59, second: 59, millisecond: 59})]
            }
        }, cb);

        cb(start, end);
    }

    function viewtable() {
        var ccAvenue = ''; 
        var gatewayname = $('#gatewayname').val();
        var paymentStatus = $('#paymentStatus').val();
        var categoryFilter = $('#categoryFilter').val(); 
        var draw_new_id = $('#draw_new_id').length ? $('#draw_new_id').val() : '';
        var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
        var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");

        var title = 'Payment Status History';

        var table = $("#onlineearntable").DataTable({
            destroy: true,
            autoWidth: false, 
            pageLength: 10,
            order: [[8, 'desc']], 
            
            // ADDED THIS BLOCK: Custom text when no data matches the filters
            language: {
                emptyTable: "No data available for the selected filters",
                zeroRecords: "No data available for the selected filters"
            },

            columnDefs: [
                { type: 'date', targets: [8] },
                { width: '70px', targets: 7 } 
            ],
            paging: true,
            searching: true,
            info: true,
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
                    draw_new_id: draw_new_id,
                    ccAvenue: ccAvenue
                }
            },
            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                'copy',
                {
                    extend: 'excelHtml5',
                    title: title
                }
            ],
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
                    render: function(data, type, row, meta) {
                        return data ? moment(data).format("DD MMM YYYY hh:mm a") : '';
                    }
                },
                { data: 'stauts', defaultContent: '' }
            ],
            footerCallback: function(row, data, start, end, display) {
                var api = this.api();

                var intVal = function(i) {
                    return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                };

                var total = api
                    .column(7)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var pageTotal = api
                    .column(7, { page: 'current' })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                $(api.column(7).footer()).html(pageTotal.toFixed(2) + ' <br>( ' + total.toFixed(2) + ' total)');
            }
        });
    }
</script>