<?php
$memid = $_SESSION['memid'] ?? 0;
$isAdmin = ($memid == 1);
?>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"/>

<style>
    .card.border-0.shadow-sm {
        border-radius: 12px;
        overflow: hidden;
    }

    .card-header {
        border-bottom: 1px solid #edf2f9;
        border-top-left-radius: 12px !important;
        border-top-right-radius: 12px !important;
    }

    #logDateRange {
        border-radius: 6px;
        border: 1px solid #ced4da;
    }

    .table-responsive {
        padding: 0 15px 15px 15px;
    }

    table.dataTable {
        border-collapse: separate !important;
        border-spacing: 0;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
    }

    table.dataTable thead th {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6 !important;
        border-right: 1px solid #dee2e6 !important;
        color: #495057;
        font-weight: 600;
        padding: 12px 10px;
        font-size: 13px;
    }

    table.dataTable tbody td {
        border-bottom: 1px solid #dee2e6 !important;
        border-right: 1px solid #dee2e6 !important;
        padding: 12px 10px;
        color: #333;
        vertical-align: middle;
        font-size: 13px;
    }

    table.dataTable thead th:last-child,
    table.dataTable tbody td:last-child {
        border-right: none !important;
    }

    table.dataTable tbody tr:last-child td {
        border-bottom: none !important;
    }

    .dataTables_wrapper .dataTables_filter input {
        border-radius: 6px;
        border: 1px solid #ced4da;
        padding: 4px 10px;
        margin-left: 8px;
    }

    .dataTables_wrapper .dataTables_length select {
        border-radius: 6px;
        border: 1px solid #ced4da;
        padding: 4px 30px 4px 10px;
    }
</style>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <div class="page-header d-flex justify-content-between align-items-center mt-3 mb-4">
                <h1 class="page-title m-0 fw-bold">Job Search Report</h1>
            </div>

            <div class="card border-0 shadow-sm">

                <?php if ($isAdmin): ?>

                    <!-- DATE FILTER ONLY FOR MEMID 1 -->
                    <div class="card-header bg-light p-4">
                        <div class="row w-100 align-items-end">
                            <div class="col-md-4">

                                <label class="mb-2 text-muted"
                                       style="font-weight: 600; font-size: 13px;">
                                    Filter Searches by Date
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text bg-white border-end-0 text-muted">
                                        <i class="fa fa-calendar"></i>
                                    </span>

                                    <input type="text"
                                           id="logDateRange"
                                           class="form-control border-start-0"
                                           placeholder="Select Date Range"
                                           readonly
                                           style="cursor: pointer; background: #fff;">

                                </div>

                            </div>
                        </div>
                    </div>

                <?php endif; ?>


                <div class="card-body p-0 pt-3">

                    <div class="table-responsive">

                        <table id="jobSearchTable"
                               class="table table-hover align-middle mb-0"
                               style="width:100%;">

                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 50px;">S.NO</th>
                                    <th>CUSTOMER NAME</th>
                                    <th>MOBILE</th>
                                    <th style="width: 25%;">FROM PLACE</th>
                                    <th style="width: 25%;">TO PLACE</th>
                                    <th>DATE & TIME</th>
                                </tr>
                            </thead>

                            <tbody></tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>


<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


<script>

$(document).ready(function() {

    // PHP memid value
    const memid = <?= (int)$memid ?>;

    // Default date = TODAY
    let startDate = moment().format('YYYY-MM-DD');
    let endDate   = moment().format('YYYY-MM-DD');


    <?php if ($isAdmin): ?>

    // ==========================================
    // DATE RANGE PICKER - ADMIN ONLY
    // ==========================================

    $('#logDateRange').daterangepicker({

        startDate: moment(),
        endDate: moment(),

        autoUpdateInput: true,

        opens: 'right',

        ranges: {

            'Today': [
                moment(),
                moment()
            ],

            'Yesterday': [
                moment().subtract(1, 'days'),
                moment().subtract(1, 'days')
            ],

            'Last 7 Days': [
                moment().subtract(6, 'days'),
                moment()
            ],

            'Last 30 Days': [
                moment().subtract(29, 'days'),
                moment()
            ],

            'This Month': [
                moment().startOf('month'),
                moment().endOf('month')
            ]

        },

        locale: {
            format: 'DD/MM/YYYY'
        }

    });


    $('#logDateRange').val(
        moment().format('DD/MM/YYYY') +
        ' - ' +
        moment().format('DD/MM/YYYY')
    );


    $('#logDateRange').on('apply.daterangepicker', function(ev, picker) {

        $(this).val(
            picker.startDate.format('DD/MM/YYYY') +
            ' - ' +
            picker.endDate.format('DD/MM/YYYY')
        );

        startDate = picker.startDate.format('YYYY-MM-DD');

        endDate = picker.endDate.format('YYYY-MM-DD');

        jobTable.ajax.reload();

    });

    <?php endif; ?>


    // ==========================================
    // DATATABLE
    // ==========================================

    let jobTable = $('#jobSearchTable').DataTable({

        processing: true,

        serverSide: true,

        pageLength: 10,

        searchDelay: 1000,

        search: {
            return: true
        },

        ajax: {

            url: window.location.origin +
                 "/ajax/service/job_search_report_services.php",

            type: "POST",

            data: function(d) {

                d.startDate = startDate;

                d.endDate = endDate;

                // Send memid also if needed in PHP service
                d.memid = memid;

            }

        },

        columns: [

            {
                data: "sno",
                className: "text-center fw-bold text-muted",
                orderable: false
            },

            {
                data: "name",
                orderable: false
            },

            {
                data: "mobile",
                orderable: false
            },

            {
                data: "from_loc",
                orderable: false
            },

            {
                data: "to_loc",
                orderable: false
            },

            {
                data: "date",
                orderable: false
            }

        ],

        language: {

            searchPlaceholder: "Type & Press Enter...",

            processing:
                '<i class="fa fa-spinner fa-spin fa-2x text-primary"></i>'

        }

    });

});

</script>