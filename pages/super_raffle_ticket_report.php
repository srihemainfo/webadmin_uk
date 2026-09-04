<?php

/**
 *      Date        Developer_name      Modifications
 *      23-06-2023  Prashant            Just3 Raffle draw Ticket report for DED
 *      23-06-2023  Prashant            Just3 Raffle draw Ticket report for LD
 *      18-08-2023  Divya               dropdown for kt ticket
 * 
 * */

$pageTitle = "Super Raffle Ticket Reports";
?>
<style>
    input,
    select {
        border: 1px solid #ccc
    }

    button,
    input {
        height: 35px;
        margin: 0;
        padding: 6px 12px;
        border-radius: 2px;
        font-family: inherit;
        font-size: 100%;
        color: inherit
    }

    .back-arrow-btn i {
        background: #fff;
        font-size: 16px;
        padding: 2px 3px;
        border-radius: 50px;
        border: 2px solid #6c6e70;
        color: #6c6e70;
        margin-right: 15px;
        width: 24px;
        height: 24px
    }
</style>

<script>
    window.onload = function() {
        var n = window.location.origin;
        document.getElementById("anchor").href = n
    };
</script>

<!--app-content open-->
<div class="main-content app-content mt-0">
    <div class="side-app">
        <input type="hidden" id="tabID" value="agents">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a><?= ucwords($pageTitle); ?>
                </h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= ucwords($pageTitle); ?>
                        </li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- ROW-1 -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                            <div class="card overflow-hidden">
                                <div class="card-body">
                                    <div class="">
                                        <div class="mt-2">
                                            <div class="row">
                                                <div class="col-lg-3 col-sm-6 mb-2">
                                                    <span>Select Draw</span>&nbsp;<span style="color: red;">*</span>
                                                    <select id="draw_new_id" class="form-select">
                                                        <option value="">Select Draw</option>
                                                        <?php
                                                        // $draw = select_query($con, "draw", "", "`deletes` = 0 AND (`raffle_status` = 'Active' OR `raffle_status` = 'Completed')ORDER BY `id` DESC", "", "");
                                                        $draw = select_query($con, "superraffledraw", "", "`deletes` = 0 AND (`status` = 'Active' OR `status` = 'Completed')ORDER BY `id` DESC", "", "");

                                                        if ($draw['nr'] > 0) {
                                                            foreach ($draw['result'] as $key => $value) {
                                                        ?>
                                                                <option value="<?= $value['id']; ?>">
                                                                    <?= $value['name']; ?>
                                                                </option>
                                                        <?php

                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="col-lg-3 col-sm-6 mb-2">
                                                    <span>Select Ticket</span>
                                                    <select id="ticket_name" class="form-select">
                                                        <option value="">Select Ticket</option>
                                                        <option value="OT">Online Ticket</option>
                                                        <option value="AT">Agent Ticket</option>
                                                        <option value="WT">Wallet Ticket</option>
                                                        <option value="FT">Free Ticket</option>
                                                        <option value="CT">Coupon Ticket</option>

                                                        <option value="CP">Cash Ticket</option>
                                                        <option value="BP">Bonus Ticket</option>

                                                        <option value="MT">Manual Ticket</option>
                                                        <option value="KT">Kiosk Ticket</option>
                                                    </select>
                                                </div>

                                                <div class="col-lg-3 col-sm-6 mb-2">
                                                    <span>Date</span>
                                                    <input type="date" id="agdate" class="form-select" max="<?php echo date("Y-m-d"); ?>">
                                                </div>

                                                <!-- <div class="col-lg-4 col-sm-6 mb-2">
                                                    <span>Select Date</span>&nbsp;<span style="color: red;">*</span>
                                                    <div id="csetime" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"><i class="fa fa-calendar"></i>&nbsp;<span></span> <i class="fa fa-caret-down"></i></div>
                                                </div> -->





                                                <div class="col-lg-3 col-sm-5 mb-2 mt-5">
                                                    <button class="btn btn-info" onclick="searchTicket()">Go</button>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>




                        </div>
                    </div>
                </div>
                <!-- ROW-1 END -->





                <!-- ROW-4 -->
                <div class="row row-sm">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header ">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <h3 class="card-title"><strong>Ticket Report</strong></h3>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                Report for DED
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item reportdwn" data-report="0" data-type="excel" onclick="methodFunction(this)">Excel</a></li>
                                                <li><a class="dropdown-item reportdwn" data-report="0" data-type="pdf" onclick="methodFunction(this)">Pdf</a></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                Report for LD
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item reportdwn" data-report="G" data-type="excel" onclick="methodFunction(this)">Excel</a></li>
                                                <li><a class="dropdown-item reportdwn" data-report="G" data-type="pdf" onclick="methodFunction(this)">Pdf</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered display" id="ticket_report" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th class="wd-15p border-bottom-0">Ticket ID</th>
                                                <th class="wd-15p border-bottom-0">Customer Name</th>
                                                <th class="wd-15p border-bottom-0">Mobile Number</th>
                                                <th class="wd-20p border-bottom-0">Email ID</th>
                                                <!-- <th class="wd-25p border-bottom-0">My3Numbers</th> -->
                                                <th class="wd-20p border-bottom-0">Raffle ID</th>
                                                <th class="wd-20p border-bottom-0">Product Amount (AED)</th>
                                                <th class="wd-25p border-bottom-0">Transaction ID</th>

                                                <th class="wd-25p border-bottom-0">Agent Name</th>
                                                <th class="wd-25p border-bottom-0">Purchase Date & Timing</th>
                                                <th class="wd-25p border-bottom-0"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <!-- <th></th> -->
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
                <!-- ROW-4 END -->
            </div>
            <!-- CONTAINER END -->
        </div>
    </div>

    <!--app-content close-->
    <script>
        var origin = window.location.origin;
        var url = origin + "/ajax/service/report_services.php";


        $(function() {
            createDatePricket('csetime');
        })();

        function createDatePricket(id) {

            var start = moment();
            var end = moment();

            function cb(start, end) {
                $('#' + id + ' span').html(start.format("YYYY-MM-DD HH:mm:ss") + ' - ' + end.format("YYYY-MM-DD HH:mm:ss"));
            }

            $('#' + id).daterangepicker({
                startDate: start.set({
                    hour: 0,
                    minute: 0,
                    second: 0,
                    millisecond: 0
                }),
                endDate: end.set({
                    hour: 23,
                    minute: 59,
                    second: 59,
                    millisecond: 59
                }),
                minDate: moment('01/01/2021').format("MM/DD/YYYY"),
                timePicker: true,
                timePicker24Hour: true,
                timePickerSeconds: true,
                maxSpan: {
                    days: 89
                },
                autoUpdateInput: true,


            }, cb);

            cb(start, end);



        }

        function methodFunction(element) {
            var reportFor = element.getAttribute('data-report');
            var reportType = element.getAttribute('data-type');

            console.log(reportType); // Use console.log() for debugging

            var draw_new_id = $('#draw_new_id').val();
            var ticket_name = $('#ticket_name').val();
            var agdate = $('#agdate').val();
            var origin = window.location.origin;

            // Do something with the data, for example, open a new window
            window.open(origin + '/rafflededreport2.php?drawid=' + draw_new_id + '&proid=' + reportFor + '&reportType=' + reportType + '&ticket_name=' + ticket_name + '&agdate=' + agdate);
        }



        $('.reportdwn').click(function(e) {
            e.preventDefault();
            var reportFor = $(this).data('report');
            var reportType = $(this).data('type');
            var draw_new_id = $('#draw_new_id').val();
            var ticket_name = $('#ticket_name').val();
            var agdate = $('#agdate').val();
            window.open(origin + '/rafflededreport2.php?drawid=' + draw_new_id + '&proid=' + reportFor + '&reportType=' + reportType + '&ticket_name=' + ticket_name + '&agdate=' + agdate);
        })

        function searchTicket() {
            var table = $('#ticket_report').DataTable();
            table.destroy();


            let draw_new_id = $('#draw_new_id').val();
            let ticket_name = $('#ticket_name').val();
            let agdate = $('#agdate').val();
            if (draw_new_id != '') {

                var title = 'Ticket Reports - ( ' + draw_new_id + '  ' + ticket_name + '  ' + agdate + ' )';
                table = $("#ticket_report").DataTable({
                    pageLength: 10,
                    order: [],
                    responsive: {
                        details: {
                            type: 'column',
                            target: -1,
                        }
                    },
                    columnDefs: [{
                        targets: -1,
                        orderable: false,
                        searchable: false,
                        className: 'control',
                    }, {
                        targets: 0,
                        orderable: false,
                        searchable: false,
                        className: 'selectall-checkbox',
                    }],
                    select: {
                        style: 'multi',
                        selector: 'td:first-child',
                    },
                    paging: true,
                    searching: true,
                    info: true,
                    ajax: {
                        url: url,
                        method: "POST",
                        dataSrc: "",
                        data: {
                            method: 'searchTicket1',
                            ticket_name: ticket_name,
                            draw_id: draw_new_id,
                            agdate: agdate
                        }
                    },
                    /*dom: 'Bfrtip',
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
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: title
                        }, 'print',
                    ],*/
                    columns: [{
                            data: "ticketno"
                        },
                        {
                            data: "cusname"
                        },
                        {
                            data: "mobile"
                        },
                        {
                            data: "email"
                        },
                        /*{
                            data: "My3Numbers"
                        },*/
                        {
                            data: "RaffleID"
                        },
                        {
                            data: "proamt"
                        },
                        {
                            data: "transaction_id"
                        },

                        {
                            data: 'agentname'
                        },
                        {
                            data: "purdate"
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
                            .column(5)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Total over this page
                        pageTotal = api
                            .column(5, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Update footer
                        $(api.column(5).footer()).html('' + pageTotal + ' ( ' + total + ' total)');
                    },
                });
            } else {
                toast('error', 'Please Select Draw');
            }

        }


        function paymentSuccess(icon, titlestr) {
            Swal.fire({
                title: titlestr,
                icon: icon,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OKAY',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    agentEarning();
                }
            })
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
    </script>