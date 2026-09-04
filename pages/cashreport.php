<?php
/**
 * Date       Developer        Modifications
 * 9-6-2023    Prakash          Cash Report Has Been Developed
 */




$pageTitle = "Cash Reports";
?>
<style>
    input,
    select {
        border: 1px solid #CCC;
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
</style>

<script>
    window.onload = function() {
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
            <!--<div class="row">-->
                <!--<div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">-->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                            <div class="card overflow-hidden">
                                <div class="card-body">
                                    <div class="">
                                        <div class="mt-2">
                                            <div class="row">
                                                <div class="col-lg-6 col-sm-6 mb-2">
                                                      <span>Select Date</span>&nbsp;<span style="color: red;">*</span>
                                                    <div id="csetime" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"><i class="fa fa-calendar"></i>&nbsp;<span></span> <i class="fa fa-caret-down"></i></div>
                                                </div>
                                                 <div class="col-lg-6 col-sm-6 mb-2">







                                                    <span>Select Reward Type</span>







                                                    <select id="reward" class="form-select">







                                                        <option value="">Select Reward Type</option>







                                                        <?php




                                                     
                                        $reward = mysqli_query($con, 'SELECT `reward_type` FROM `cb_transactions` GROUP BY `reward_type` ORDER BY `reward_type` ASC;');



                                                            while($row = mysqli_fetch_assoc($reward)) {
                                              


                                                        ?>







                                                                 
                                                                 <option value="<?= $row['reward_type']; ?>"><?= ucfirst(strtolower($row['reward_type'])); ?></option>






                                                        <?php



                                                            }
                                                      

                                                        ?>







                                                    </select>







                                                </div>
                                                <div class="col-lg-6 col-sm-6 mb-2">
                                                    <button class="btn btn-info" onclick="searchTicket()">Go</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>




                        </div>
                    </div>
                <!--</div>-->
                <!-- ROW-1 END -->





                <!-- ROW-4 -->
                <div class="row row-sm">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">

                                <div class="col-lg-4">
                                    <h3 class="card-title"><strong>Cash Report</strong></h3>
                                </div>
                                <div class="col-lg-8">







                                </div>

                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered display" id="ticket_report" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th class="wd-15p border-bottom-0">ID</th>
                                                <th class="wd-15p border-bottom-0">Name</th>
                                                <th class="wd-20p border-bottom-0">Opening Balance (AED)</th>
                                                <th class="wd-25p border-bottom-0">Total Amount (AED)</th>
                                                <th class="wd-20p border-bottom-0">Closeing Balance (AED)</th>
                                                <th class="wd-15p border-bottom-0">Mobile</th>
                                                <th class="wd-20p border-bottom-0">Email</th>
                                                <th class="wd-25p border-bottom-0">Transaction Type</th>
                                                <th class="wd-25p border-bottom-0">Reward Type</th>
                                                <th class="wd-25p border-bottom-0">IP</th>

                                                <th class="wd-25p border-bottom-0">Date & Time</th>
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
            <!--</div>-->
            <!-- CONTAINER END -->
        </div>
    </div>

    <!--app-content close-->
    <script>
        var url = window.location.origin + "/ajax/service/points_services.php";

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

        function searchTicket() {
            var table = $('#ticket_report').DataTable();
            table.destroy();
            var formdate = moment($('#csetime').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:-mm:-ss");
            var todate = moment($('#csetime').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:-mm:-ss");

            var title = 'Cash Reports (' + formdate + ' to ' + todate + ')';
            table = $("#ticket_report").DataTable({
                pageLength: 10,
                order: [
                    [
                        0, 'desc'
                    ]
                ],
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
                    url: origin + "/ajax/service/points_services.php",
                    method: "POST",
                    dataSrc: "",
                    data: {
                        method: 'getCashReport',
                        formdate: formdate,
                        todate: todate,
                        reward: $('#reward').val()
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
                        data: "id"
                    },
                    {
                        data: "name"
                    },
                    {
                        data: "opbal"
                    },
                    {
                        data: "total"
                    },
                    {
                        data: "clobal"
                    },
                    {
                        data: "mobile"
                    },
                    {
                        data: "email"
                    },
                    {
                        data: "transtype"
                    },

                    {
                        data: 'rewardtype'
                    },
                    {
                        data: "ip"
                    },

                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return moment(data.date).format("DD MMM YYYY hh:mm a")
                        }
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
                        .column(3)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Total over this page
                    pageTotal = api
                        .column(3, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Update footer
                    $(api.column(3).footer()).html('' + pageTotal + ' ( ' + total + ' total)');
                },
            });


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