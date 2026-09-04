<?php
$pageTitle = "Subscription List";
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
?>
<style>
    .card-header.d-lg-flex.d-block.justify-content-between {
        border-bottom: none;
    }

    input,
    select {
        border: 1px solid #CCC;
        /* width: 250px; */
    }

    .nav.product-sale {
        position: unset;
        top: -3rem;
        right: 5px;
        margin: 12px 0;
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

    .swal-modal {
        border: 3px solid white;
        color: #fff;
    }

    .swal-button {
        background-color: #07f3a2 !important;
    }

    .swal-text {
        font-weight: 600 !important;
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

    .required-indicator {
        color: red;
        /* Change to the desired color */
    }

    .dt-column-order {
        display: none;
    }

    table.dataTable th.dt-type-numeric,
    table.dataTable th.dt-type-date,
    table.dataTable td.dt-type-numeric,
    table.dataTable td.dt-type-date {
        text-align: left;
    }

    .form-control:disabled,
    .form-control[readonly] {
        background-color: rgb(255 255 255 / 10%);
        /* opacity: 1; */
    }

    input,
    select {
        border: 1px solid #CCC;
        /* width: 250px; */
    }

    .text {
        float: unset !important;
    }

    .dropdown-item {
        display: block;
        width: 100%;
        padding: 0.25rem 1rem;
        clear: both;
        font-weight: 400;
        color: #121212 !important;
        text-align: inherit;
        text-decoration: none;
        white-space: nowrap;
        background-color: white;
        border: 0;
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

    .btn-light {
        /* color: #495057; */
        background-color: #ffffff !important;
    }

    .dropdown.bootstrap-select {
        display: block !important;
        width: 100% !important;
    }
</style>
<script>
    window.onload = function () {
        var page_origin = window.location.origin;
        let anchor = document.getElementById("anchor");
        anchor.href = page_origin;
    }
</script>
<!--app-content open-->
<div class="main-content app-content mt-0">
    <div class="side-app">
        <input type="hidden" id="tabID" value="agents">
        <!-- CONTAINER -->
        <div class="main-container  container-fluid mt-5 p-0">
            <!-- PAGE-HEADER -->
            <div class="page-header pt-5">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left"
                            onclick="history.go(-1)" aria-hidden="true"></i></a><?= $pageTitle; ?></h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= $pageTitle; ?></li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->
            <!-- ROW-1 -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                    <div class="card ">
                        <div class="card-body">
                            <div class="d-flex1">
                                <div class="mt-2">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <span>Select Date</span>
                                            <!-- <span style="color:red;">&nbsp;*</span> -->
                                            <input class="form-control" type="text" id="datefilterLogin"
                                                name="datefilterLogin" placeholder="Select Date" value="" readonly />
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <span>Select Currency</span>

                                            <select id="currencyID" class="selectpicker" data-live-search="true">
                                                <!-- <option value="">Select Draw</option> -->

                                                <option value="INR">
                                                    INR
                                                </option>
                                                <option value="USD">
                                                    USD
                                                </option>

                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-4 mt-4">
                                            <button type="button" class="btn btn-primary" id="searchBTN"
                                                onclick="unsubscribeList()">GO</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4" id="searcherr">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-4">
                        <div class="grid-margin">
                            <div class="panel panel-primary">
                                <div class="tab-menu-heading border-0 p-0">
                                    <div class="tabs-menu1">
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body border-0 pt-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab1">
                                            <div class="card-header d-lg-flex d-block justify-content-between">
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered text-nowrap border-bottom"
                                                    id="Participation_List" style="width:100%;">
                                                    <thead>
                                                        <tr>
                                                            <th class="column_sort sorting sorting_asc">Subscription ID
                                                            </th>
                                                            <th class="column_sort sorting sorting_asc">Plan Name</th>
                                                            <th class="column_sort sorting sorting_asc">Amount</th>
                                                            <th class="column_sort sorting sorting_asc">Status</th>
                                                            <th class="column_sort sorting sorting_asc">Gateway</th>
                                                            <th class="column_sort sorting sorting_asc">Plan Type</th>
                                                            <th class="column_sort sorting sorting_asc">Name</th>
                                                            <th class="column_sort sorting sorting_asc">Mobile</th>
                                                            <th class="column_sort sorting sorting_asc">Email</th>
                                                            <th class="column_sort sorting sorting_asc">Createdon</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
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
            </div>
        </div>
        <!-- ROW-4 END -->
    </div>
    <!-- CONTAINER END -->
</div>
<script>

    const createDatePicker = (id) => {
        try {
            const selector = `#${id}`;
            const today = moment();
            const sevenDaysAgo = moment().subtract(0, 'days');
            $(selector).daterangepicker({
                autoUpdateInput: true,
                locale: {
                    cancelLabel: 'Clear'
                },
                // minDate: sevenDaysAgo, 
                maxDate: today,
                opens: 'left',
                startDate: sevenDaysAgo,
                endDate: today,
                // maxSpan: {
                //     days: 30
                // },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
                }
            });
            $(selector).on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                // $(`#drawID`).val('');
                // $(`#drawID`).val('').selectpicker('refresh');
            });
            $(selector).on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
        } catch (e) {
            console.log(`Error: ${e.message}`);
        }
    }
    const unsubscribeList = () => {
        try {
            let startDate = null;
            let endDate = null;
            var btn = $(`#searchBTN`);
            var dateFilter = $('#datefilterLogin').val();
            if (dateFilter != '' && dateFilter != null && dateFilter != undefined) {
                startDate = moment($('#datefilterLogin').data('daterangepicker').startDate).format("YYYY-MM-DD");
                endDate = moment($('#datefilterLogin').data('daterangepicker').endDate).format("YYYY-MM-DD");
            }
            var table = $('#Participation_List').DataTable({
                destroy: true,
                pageLength: 10,
                order: [],
                paging: true,
                searching: true,
                info: true,
                ajax: {
                    url: origin + "/ajax/service/topSpendersServices.php",
                    method: "POST",
                    dataSrc: "result",
                    data: {
                        method: 'topSpendersList',
                        dateFilter: dateFilter,
                        startDate: startDate,
                        endDate: endDate,
                        currencyID: $(`#currencyID`).val()
                    },
                    beforeSend: function () {
                        btn.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Loading...`).prop('disabled', true);
                    },
                },
                order: [
                    [9, 'desc']
                ],
                columnDefs: [{
                    type: 'num',
                    targets: [2]
                }],
                dom: 'Bfrtip',
                buttons: [
                    'pageLength',
                    'copy',
                    {
                        extend: 'excelHtml5',
                        title: 'Subscrition List'
                    },
                ],
                columns: [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return data?.subscription_id || ''
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            let cart = data.checkout_response ? JSON.parse(data.checkout_response) : [];
                            return cart?.productDetails?.name || '';
                        }
                    },
                    {
                        data: 'grandtotal',
                        render: function (data, type, row, meta) {
                            return parseFloat(data)
                        }
                    },
                    // {
                    //     data: null,
                    //     render: function (data, type, row, meta) {
                    //         return data.Mobile
                    //     }
                    // },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return _.capitalize(data?.paymentStatus || '')
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return _.capitalize(data?.gateway || '')
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return _.capitalize(data?.planType === 'TRIAL' ? 'TRAIL' : data?.planType || '');
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return data?.name ? `<a target="_blank" style="color: #525704; font-weight: 900;" href="profile/edit/${data?.userID}">${data?.name}</a>` : '';
                        }
                    },

                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return data?.mobile ? `<a target="_blank" style="color: #525704; font-weight: 900;" href="tel:${data?.mobile}">${data?.mobile}</a>` : '';

                        }
                    },

                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return data?.email ? `<a target="_blank" style="color: #525704; font-weight: 900;" href="mailto:${data?.email}">${data?.email}</a>` : '';
                        }
                    },

                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            let dateTi = data?.createdon || '';
                            return dateTi != '' ? moment(dateTi).format("DD MMM YYYY hh:mm A") : '';
                        }
                    },
                    // {
                    //     data: 'TotalSpending',
                    //     render: function (data, type, row, meta) {
                    //         return parseFloat(data)
                    //     }
                    // },
                    // {
                    //     data: 'TotalTapWinning',
                    //     render: function (data, type, row, meta) {
                    //         return parseFloat(data)
                    //     }
                    // },
                    // {
                    //     data: 'TotalWinning',
                    //     render: function (data, type, row, meta) {
                    //         return parseFloat(data)
                    //     }
                    // },
                    // {
                    //     data: 'TotalWinningCombined',
                    //     render: function (data, type, row, meta) {
                    //         return parseFloat(data)
                    //     }
                    // },
                ],
                initComplete: function (settings, json) {
                    btn.html(`GO`).prop('disabled', false);
                },
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    var intVal = function (i) {
                        if (typeof i === 'string') {
                            const trimmed = i.trim();
                            if (trimmed === 'NA' || trimmed === '') {
                                return 0; // Treat 'NA' as 0
                            }
                            return parseFloat(trimmed.replace(/[\$,]/g, '')) || 0;
                        }
                        return typeof i === 'number' ? i : 0;
                    };
                    var calculateTotals = function (columnIndex) {
                        let s = {
                            total: 0,
                            pageTotal: 0
                        };
                        // Total for all pages
                        s.total = api.column(columnIndex).data().reduce((a, b) => {
                            const value = intVal(b);
                            console.log(`Column ${columnIndex} Total Value:`, value); // Log total value for each item
                            return a + value;
                        }, 0);
                        // Total for current page
                        s.pageTotal = api.column(columnIndex, {
                            page: 'current'
                        }).data().reduce((a, b) => {
                            const value = intVal(b);
                            console.log(`Column ${columnIndex} Page Value:`, value); // Log page value for each item
                            return a + value;
                        }, 0);
                        return s;
                    };
                    [2].forEach(function (i) {
                        api.column(i).data().each(function (value, index) {
                            console.log(`Row ${index} Column ${i} Value:`, value);
                        });
                        var totals = calculateTotals(i);
                        $(api.column(i).footer()).html(totals.pageTotal.toFixed(2) + ' ( ' + totals.total.toFixed(2) + ' total)');
                    });
                }
            });
        } catch (e) {
            console.log(`Error: ${e.message}`);
        }
    }
    const drawIDchange = () => {
        try {
            $('#datefilterLogin').trigger('cancel.daterangepicker');
        } catch (e) {
            console.log(`Error: ${e.message}`);
        }
    }
    $(function () {
        try {
            createDatePicker('datefilterLogin');

            // $('#datefilterLogin').trigger('cancel.daterangepicker');
            unsubscribeList();
        } catch (e) {
            console.log(`Error: ${e.message}`);
        }
    });
</script>