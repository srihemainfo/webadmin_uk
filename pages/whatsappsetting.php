<?php
//    Date       Developer_name      Modifications    

//    14-2-2023   Prakash            SMS Switch Option Development Finished
//    12-4-2023   Prakash            Data slice has been added
$pageTitle = 'Customer Site Notification';

//    $settings1 = mysqli_query($con, "SELECT * FROM `sms_switch` WHERE `site` = 'adminsite' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1");
//    $adminsite = mysqli_fetch_assoc($settings1);

//    $settings2 = mysqli_query($con, "SELECT * FROM `whatsapp_switch` WHERE `site` = 'customersite' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1");
//    $customersite = mysqli_fetch_assoc($settings2);

$customerSiteNotfication = json_decode($settings1['customerSiteNotfication'], true);




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

                <div class="col-xl-6">
                    <form id="editprofileform">
                        <div class="card ">
                            <div class="card-header">
                                <h3 class="card-title">Customer Site</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?php

                                            foreach ($customerSiteNotfication as $location => $services) {
                                            ?>
                                                <h3><?= ($location === 'RESENDUAE' ? 'UAE - Resend' : ($location === 'RESENDNONUAE' ? 'NONUAE - Resend' : $location)); ?></h3>
                                                <?php
                                                foreach ($services as $service => $value) {
                                                ?>
                                                    <label><input type='radio' name='<?= $location; ?>' value='<?= $service; ?>' " <?= ($value ? " checked" : ""); ?> "><?= ucwords($service); ?></label><br>
                                                <?php
                                                }
                                                ?>
                                                <br>
                                            <?php
                                            }
                                            ?>



                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=" card-footer text-end">
                                <button type="button" onclick="changeSmsGateWay()" class="btn btn-success bg-success-gradient my-1">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-4">
                                    <h3 class="card-title"><?= ucwords('WhatsAPP History'); ?></h3>
                                </div>
                                <div class="col-7">
                                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"><i class="fa fa-calendar"></i>&nbsp;<span></span> <i class="fa fa-caret-down"></i>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <button class="btn btn-info" onclick="viewtable()">Go</button>
                                </div>
                            </div>
                        </div>
                        <div class=" card-body">
                            <table class=" table table-bordered text-nowrap border-bottom" id="drawtable" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th class="wd-16p border-bottom-0">History ID</th>
                                        <th class="wd-16p border-bottom-0">Site</th>
                                        <th class="wd-16p border-bottom-0">Gateway</th>
                                        <th class="wd-16p border-bottom-0">Changed BY</th>
                                        <th class="wd-15p border-bottom-0">Created AT</th>
                                        <th class="wd-15p border-bottom-0">Deleted At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
    <script>
        $(function() {

            // createDatePricket('reportrange');

            // viewtable();



        });



        // function viewtable() {





        //     var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");

        //     var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");



        //     var table = $('#drawtable').DataTable();

        //     table.destroy();



        //     var title = 'SMS History (' + formdate + '  to  ' + todate + ' )';

        //     table = $("#drawtable").DataTable({

        //         pageLength: 10,

        //         order: [

        //             [0, 'desc']

        //         ],

        //         paging: true,

        //         searching: true,

        //         info: true,

        //         ajax: {

        //             url: window.location.origin + "/ajax/service/winners_email.php",

        //             method: "POST",

        //             dataSrc: "",

        //             data: {

        //                 method: 'whatsappswitchhistory',

        //                 formdate: formdate,

        //                 todate: todate

        //             }

        //         },

        //         dom: 'Bfrtip',

        //         buttons: [

        //             'pageLength',

        //             'copy',

        //             {
        //                 extend: 'csvHtml5',
        //                 title: title
        //             },
        //             {
        //                 extend: 'excelHtml5',
        //                 title: title
        //             },
        //             {
        //                 extend: 'pdfHtml5',
        //                 orientation: 'portrait',
        //                 pageSize: 'A4',
        //                 title: title
        //             }, 'print',

        //         ],

        //         columns: [{

        //                 data: 'id'
        //             },
        //             {
        //                 data: 'Site'
        //             },
        //             {
        //                 data: 'Gateway'
        //             },
        //             {
        //                 data: 'changedby'
        //             },
        //             {
        //                 data: 'createdon'
        //             },
        //             {
        //                 data: 'deletedon'
        //             }
        //         ],


        //     });

        // }



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



        function changeSmsGateWay() {

            // let smsgatway = $("input[type='radio'][name='" + id + "']:checked").val();

            // let $customerSiteNotification = [];

            // $('input[name="UAE"], input[name="NONUAE"]').each(function() {
            //     let location = $(this).attr('name');
            //     let service = $(this).val();
            //     let isChecked = $(this).is(':checked');

            //     let existingLocation = $customerSiteNotification.find(item => item.location === location);
            //     if (existingLocation) {
            //         existingLocation.services[service] = isChecked;
            //     } else {
            //         let newLocation = {
            //             location: location,
            //             services: {
            //                 [service]: isChecked
            //             }
            //         };
            //         $customerSiteNotification.push(newLocation);
            //     }
            // });

            let $customerSiteNotification = {
                'UAE': {},
                'NONUAE': {},
                'RESENDUAE': {},
                'RESENDNONUAE': {}
            };

            $('input[name="UAE"], input[name="NONUAE"], input[name="RESENDUAE"], input[name="RESENDNONUAE"]').each(function() {
                let location = $(this).attr('name');
                let service = $(this).val();
                let isChecked = $(this).is(':checked');

                $customerSiteNotification[location][service] = isChecked;
            });


            // Output for testing
            // console.log(JSON.stringify($customerSiteNotification, null, 2));

            var formdata = [];

            formdata.push({
                name: 'method',
                value: "updateSettings"
            });

            formdata.push({
                name: 'customerSettings',
                value: JSON.stringify($customerSiteNotification)
            });

            var post_data = formdata;




            var onsuccess = function(data) {

                var response = JSON.parse(data);

                if (response != "") {

                    if (response.type == '1') {

                        // viewtable();

                        toast('success', response.result);
                        location.reload();
                    } else {

                        toast('error', response.result);

                    }



                }

            }



            do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/settingUpdate.php");

            // } else {

            //     toast('error', 'Kidnly Select SMS GateWay!');

            // }

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
    </script>