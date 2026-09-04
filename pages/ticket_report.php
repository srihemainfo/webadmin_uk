<?php
/**
 * Date                  Developer      Modifications
 * 25-4-2023             Prakash             Draw Filter updated
 * 19-05-2023            Prakash             Cash & Bonus Tickets added to Ticket Line Wise Report
 * 9-6-2023              Prakash             Cash and Bonus Ticket Has been added
 * 18-8-2023             Divya               kiosk ticket has been created
 */ 


$pageTitle = "Ticket Reports (Line Wise)";
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
                                                        $draw = select_query($con, "draw", "", "(`deletes` = 0 AND `status` = 'Completed') OR (`deletes` = 0 AND `status` = 'Active' AND `id` IN ( SELECT `id` FROM ( SELECT `id` FROM `draw` WHERE `deletes` = 0 AND `status` = 'Active' ORDER BY `id` ASC LIMIT 2 ) t )) ORDER BY `id` DESC", "", "");

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
                            <div class="card-header">

                                <div class="col-lg-4">
                                    <h3 class="card-title"><strong>Ticket Report</strong></h3>
                                </div>
                                <div class="col-lg-8">







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
                                                <th class="wd-25p border-bottom-0">My3Numbers</th>
                                                <th class="wd-20p border-bottom-0">Product Amount (AED)</th>
                                                <th class="wd-25p border-bottom-0">Transaction ID</th>
                                                <th class="wd-20p border-bottom-0">Raffle ID</th>

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
                <!-- ROW-4 END -->
            </div>
            <!-- CONTAINER END -->
        </div>
    </div>

    <!--app-content close-->
    <script>
        var origin = window.location.origin;
        var url = origin + "/ajax/service/report_services.php";



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
                            method: 'searchTicket',
                            ticket_name: ticket_name,
                            draw_id: draw_new_id,
                            agdate: agdate
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
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            title: title
                        }, 'print',
                    ],
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
                        {
                            data: "My3Numbers"
                        },
                        {
                            data: "proamt"
                        },
                        {
                            data: "transaction_id"
                        },
                        {
                            data: "RaffleID"
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