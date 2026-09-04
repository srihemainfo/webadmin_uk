<?php
//    Date       Developer_name      Modifications
//    30-3-2023   Prakash            Email Add Option Development Finished



$pageTitle = 'Upcoming Draws';

?>
<style>
    tr.drawYES {
        background: lightgreen !important;
    }

    .form-control:disabled,
    .form-control[readonly] {
        background-color: #f6f6fb !important;
        opacity: 1;
    }
</style>
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

            <!-- <div class="row">



                <div class="col-xl-12">
                   

                    <div class="card">


                        <div class="card-header">
                            <h3 class="card-title" id="th1titlte">Draw Frequency</h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-12">

                                    <div class="form-group">
                                        <label for="drawfreq">Draw Frequency</label><sup style="color: red;"><strong>*</strong></sup>
                                        <select class="form-select" id="drawfreq" onchange="getDrawStart($(this).val())">
                                            <option value="">Select Frequency</option>
                                            <?php
                                            $draw_frequency = select_query($con, "draw_frequency", "", "`deletes` = '0' ORDER BY `id` ASC", "", "");
                                            if ($draw_frequency['nr'] > 0) {
                                                foreach ($draw_frequency['result'] as $key => $value) {
                                            ?>
                                                    <option value="<?= $value['id']; ?>"><?= $value['name']; ?></option>
                                            <?php

                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-4 col-12">
                                    <span>Last Draw Date</span>
                                    <div id="startDate" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        <span></span> <i class="fa fa-caret-down"></i>
                                    </div>
                                </div>


                                <div class="col-md-4 col-sm-4 col-12">
                                    <span>End Date</span>
                                    <div id="endDate" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        <span></span> <i class="fa fa-caret-down"></i>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-4 col-12">

                                    <div class="form-group">
                                        <label for="drawfreq">Draw Prefix</label><sup style="color: red;"><strong>*</strong></sup>
                                        <input type="text" class="form-control" id="drawprefix" placeholder="eg: Weekly Draw">
                                    </div>
                                </div>

                            </div>










                        </div>




                        <div class="card-footer text-end" id="saveEmailBtn">
                            <button type="button" onclick="generrateDrawList()" class="btn btn-success bg-success-gradient my-1">Gate Draw List</button>
                        </div>
                    </div>
                    
                </div>




            </div> -->

            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <h3 class="card-title"><?= ucwords('Draw List'); ?></h3>
                                </div>


                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="drawList" >

                                </div>
                            </div>
                        </div>

                        <!-- <div class="card-footer text-end">
                            <div id="saveDrawbtn">
                                <button type="button" onclick="SaveAllDraws()" class="btn btn-success bg-success-gradient my-1">Submit</button>
                            </div>

                        </div> -->
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>



<!-- Modal -->
<div class="modal fade" id="testmail">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content modal-content-demo border-0" style="
    background-image: -webkit-linear-gradient(-45deg,#0e3b69 0,#0270bf 100%);
    border: none;
    color: #fff;
">

            <div class="modal-header border-0">
                <h4 class="modal-title">Delete Confirmation</h4>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>

            <div class="modal-body text-center">

                <div class="container" id="body-content">
                    <!-- <div class="form-group" id="customerpreference">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="toemial">To Email</label>
                                    <input type="email" class="form-control" id="toemial" placeholder="eg. name@example.com">
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>

            </div>

            <div class="modal-footer border-0" id="sntbtn">

            </div>

        </div>

    </div>

</div>


<div class="modal fade" id="editTime">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content modal-content-demo" style="
    background-image: -webkit-linear-gradient(-45deg,#0e3b69 0,#0270bf 100%);
    border: none;
    color: #fff;
">

            <div class="modal-header border-0">
                <h4 class="modal-title">Edit Confirmation</h4>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>

            <div class="modal-body text-center">

                <div class="container">

                    <h2 id="titleTas"></h2>
                    <div id="bodyasd"></div>
                </div>

            </div>

            <div class="modal-footer border-0" id="saveBtmEnd">

            </div>

        </div>

    </div>

</div>



<script>
    $(function() {

        // startDate('startDate', '');
        // endDate('endDate', '');
        // $('#saveDrawbtn').hide();
        UpcomingDrawList();

    });

    function UpcomingDrawList() {
        var formdata = [];
        formdata.push({
            name: 'method',
            value: "getUpcommingDraw"
        });

        var post_data = formdata;

        var onsuccess = function(data) {



            var response = JSON.parse(data);

            if (response != "") {

                if (response.type == 1) {

                    // console.log(response.drawlist);
                    generateTable(response.drawlist);
                    // $('#saveEmailBtn').html(btn);
                } else {

                    // toast('error', response.result);
                    $('#drawList').html('<div class="alert alert-danger" role="alert" style="text-align: center;">' + response.result + '</div>');
                    $('#saveEmailBtn').html(btn);
                }



            }

        }



        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/drawcreate_services.php");
    }





    ///////  New ///////
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

    function startDate(id, startd) {


        var start = moment().set({
            hour: 0,
            minute: 0,
            second: 0,
            millisecond: 0
        });
        if (startd != '') {
            start = startd;
        }

        function cb(start) {
            $('#' + id + ' span').html(start.format("YYYY-MM-DD"));
        }

        $('#' + id).daterangepicker({
            startDate: start,
            // endDate: end,
            // minDate: moment().format("MM/DD/YYYY"),
            singleDatePicker: true,
            autoApply: true,
            minDate: start,
            maxDate: moment().add(365, 'day').format("MM/DD/YYYY"),
            timePicker: false,
            timePicker24Hour: false,
            timePickerSeconds: false,
            maxSpan: {
                days: 89
            },
            autoUpdateInput: true,
        }, cb);

        cb(start);

    }

    function endDate(id, startd) {


        var start = moment().set({
            hour: 0,
            minute: 0,
            second: 0,
            millisecond: 0
        });
        if (startd != '') {
            start = startd;
        }

        function cb(start) {
            $('#' + id + ' span').html(start.format("YYYY-MM-DD"));
        }

        $('#' + id).daterangepicker({
            startDate: start,
            // endDate: end,
            // minDate: moment(start).add(1, 'days').format("MM/DD/YYYY"),
            singleDatePicker: true,
            autoApply: true,
            minDate: start,
            maxDate: moment().add(365, 'day').format("MM/DD/YYYY"),
            timePicker: false,
            timePicker24Hour: false,
            timePickerSeconds: false,
            maxSpan: {
                days: 89
            },
            autoUpdateInput: true,
        }, cb);

        cb(start);

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
                location.reload();
            }
        })
    }

    // function getDrawStart(frq) {
    //     if (frq == '') {
    //         toast('error', 'Kindly Select the Draw Frequency');
    //         return false;
    //     }

    //     var formdata = [];

    //     formdata.push({

    //         name: 'method',

    //         value: "get_draw_date"

    //     }, {

    //         name: 'frq',

    //         value: frq

    //     });


    //     var post_data = formdata;

    //     var onsuccess = function(data) {



    //         var response = JSON.parse(data);

    //         if (response != "") {

    //             if (response.type == 1) {

    //                 startDate('startDate', moment(response.start).set({
    //                     hour: 0,
    //                     minute: 0,
    //                     second: 0,
    //                     millisecond: 0
    //                 }));
    //                 endDate('endDate', moment(response.start).add(1, 'days').set({
    //                     hour: 0,
    //                     minute: 0,
    //                     second: 0,
    //                     millisecond: 0
    //                 }));
    //             } else {

    //                 toast('error', response.result);
    //             }



    //         }

    //     }



    //     do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/drawcreate_services.php");





    // }


    // function generrateDrawList() {
    //     var formdata = [];
    //     $('#drawList').html('');

    //     formdata.push({

    //             name: 'method',

    //             value: "generateDraw"

    //         }, {

    //             name: 'frq',
    //             value: $('#drawfreq').val()

    //         }, {

    //             name: 'startDate',
    //             value: moment($('#startDate').data('daterangepicker').startDate._d).format("YYYY-MM-DD")

    //         }, {

    //             name: 'endDate',
    //             value: moment($('#endDate').data('daterangepicker').endDate._d).format("YYYY-MM-DD")

    //         }, {
    //             name: 'drawprefix',
    //             value: $('#drawprefix').val()
    //         }

    //     );

    //     var btn = $('#saveEmailBtn').html();
    //     loading('saveEmailBtn');
    //     var post_data = formdata;

    //     var onsuccess = function(data) {



    //         var response = JSON.parse(data);

    //         if (response != "") {

    //             if (response.type == 1) {


    //                 generateTable(response.drawlist);
    //                 $('#saveEmailBtn').html(btn);
    //             } else {

    //                 // toast('error', response.result);
    //                 $('#drawList').html('<div class="alert alert-danger" role="alert" style="text-align: center;">' + response.result + '</div>');
    //                 $('#saveEmailBtn').html(btn);
    //             }



    //         }

    //     }



    //     do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/drawcreate_services.php");

    // }



    function generateTable(drawlist) {
        $('#drawList').html('');
        var tableHTML = `<table class="table" id="drawlistTable" style="width:100%">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Draw Day</th>
                        <th scope="col">Draw Name</th>
                        <th scope="col">Ticket Selling Start Date</th>
                        <th scope="col">Ticket Selling End Date</th>
                        <th scope="col">Draw Date & Time</th>
                        <th scope="col">Popup Start <br>Time to Auto <br>Close in 1 Hr</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead><tbody></tbody></table>`;


        $('#drawList').append(tableHTML);
        for (i in drawlist) {
            // let type = `<input type="text" class="form-control" name="datefilter" id="${'type'+i}" value="${drawlist[i].type}" readonly />`;
            let day = `<input type="text" class="form-control" name="datefilter" id="${'day'+drawlist[i].id}" value="${drawlist[i].day}" readonly />`;
            let drawname = `<input type="text" class="form-control" name="datefilter" id="${'drawname'+drawlist[i].id}" value="${drawlist[i].drawname}" readonly />`;
            let salestart = `<input type="text" class="form-control" name="datefilter" id="${'salestart'+drawlist[i].id}" value="${moment(drawlist[i].salestart).format("DD MMM YYYY hh:mm a")}" readonly />`;
            let saleend = `<input type="text" class="form-control" name="datefilter" id="${'saleend'+drawlist[i].id}" value="${drawlist[i].saleend}" />`;
            let result_time = `<input type="text" class="form-control" name="resultdatefilter" id="${'result_time'+drawlist[i].id}" value="${drawlist[i].result_time}" />`;
            let popup = `<input type="text" class="form-control" name="datefilter" id="${'popup'+drawlist[i].id}" value="${moment(drawlist[i].popup).format("DD MMM YYYY hh:mm a")}" readonly />`;
            let action = '';
            if (drawlist[i].deletes) {
                action = '<button class="btn" style="background:none;font-size:20px !important;color: red;" onclick="deletePreview(' + drawlist[i].id + ')"><i class="fa fa-trash"></i></button>';
            }
            if (drawlist[i].generate) {
                action = '<a href="' + window.location.origin + '/cron/consolidatedReport.php?id=' + drawlist[i].id + '"><button class="btn"><i class="fa fa-download"></i> Generate</button></a>';
            }
            var tableHTMLrow = `<tr class="${(drawlist[i].deletes) ? '' : 'drawYES'}">
                        <td>${day}</td>
                        <td>${drawname}</td>
                        <td>${salestart}</td>
                        <td>${saleend}</td>
                        <td>${result_time}</td>
                        <td>${popup}</td>
                        <td>${action}</td>
                    </tr>`;
            $('#drawlistTable tbody').append(tableHTMLrow);

            // generateDrawPicker('salestart' + i, moment(drawlist[i].salestart).format("YYYY-MM-DD HH:mm:ss"))
            generateDrawPicker('saleend' + drawlist[i].id, moment(drawlist[i].saleend).format("YYYY-MM-DD HH:mm:ss"), moment(drawlist[i].saleend).format("YYYY-MM-DD"), moment(drawlist[i].maxDay).format("YYYY-MM-DD"));
            // generateDrawPicker('saleend' + drawlist[i].id, moment(drawlist[i].minDay).format("YYYY-MM-DD HH:mm:ss"), moment(drawlist[i].maxDay).format("YYYY-MM-DD"));

            generateDrawPicker('result_time' + drawlist[i].id, moment(drawlist[i].result_time).format("YYYY-MM-DD HH:mm:ss"), moment(drawlist[i].saleend).format("YYYY-MM-DD"), moment(drawlist[i].maxDay).format("YYYY-MM-DD"));
        }



        $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
            var id = $(this).attr('id');
            var newTime = moment($('#' + id).data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
            if (id == '') {
                toast('error', 'Kindly Refresh and Try Again!');
                return false;
            }
            if (newTime == '') {
                toast('error', 'Kindly Refresh and Try Again!');
                return false;
            }

            var formdata = [];
            formdata.push({
                name: 'method',
                value: "getPreviewsContent"
            }, {
                name: 'id',
                value: id
            }, {
                name: 'newTime',
                value: newTime
            });


            var post_data = formdata;
            var onsuccess = function(data) {
                var response = JSON.parse(data);
                if (response != "") {
                    if (response.type == 1) {
                        var txt = id.trim();
                        $('#saveBtmEnd').html(`<button onclick="editEndTime('${txt}')" type="button" class="btn btn-primary">Save</button>`);
                        $('#titleTas').html(response.title);
                        $('#bodyasd').html(response.result);
                        $('#editTime').modal('show');
                    } else {
                        toast('error', response.result);
                        $('#editTime').modal('hide');

                    }
                }
            }
            do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/drawcreate_services.php");
        });


        $('input[name="resultdatefilter"]').on('apply.daterangepicker', function(ev, picker) {
            var id = $(this).attr('id');
            var newTime = moment($('#' + id).data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
            if (id == '') {
                toast('error', 'Kindly Refresh and Try Again!');
                return false;
            }
            if (newTime == '') {
                toast('error', 'Kindly Refresh and Try Again!');
                return false;
            }

            var formdata = [];
            formdata.push({
                name: 'method',
                value: "resultPreviews"
            }, {
                name: 'id',
                value: id
            }, {
                name: 'newTime',
                value: newTime
            });


            var post_data = formdata;
            var onsuccess = function(data) {
                var response = JSON.parse(data);
                if (response != "") {
                    if (response.type == 1) {
                        var txt = id.trim();
                        $('#saveBtmEnd').html(`<button onclick="editResultTime('${txt}')" type="button" class="btn btn-primary">Save</button>`);
                        $('#titleTas').html(response.title);
                        $('#bodyasd').html(response.result);
                        $('#editTime').modal('show');
                    } else {
                        toast('error', response.result);
                        $('#editTime').modal('hide');

                    }
                }
            }
            do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/drawcreate_services.php");
        });

        $('#saveDrawbtn').show();
    }

    function deletePreview(id) {
        if (id == '') {
            toast('error', 'Kindly Refresh and Try Again!');
            return false;
        }

        var formdata = [];
        formdata.push({
            name: 'method',
            value: "deleteDraw_preview"
        }, {
            name: 'id',
            value: id
        });
        var post_data = formdata;
        var onsuccess = function(data) {
            var response = JSON.parse(data);
            if (response != "") {
                if (response.type == 1) {
                    $('#sntbtn').html('<button class="btn btn-primary" style="font-size:14px !important;color: red;" onclick="deleteDraw(' + id + ')">Submit</button>');
                    if (response.result == 'LAST') {

                        $('#body-content').html(response.message);

                    } else {
                        $('#body-content').html(response.message);
                    }

                    $('#testmail').modal('show');

                } else {
                    toast('error', response.result);
                }
            }

        }
        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/drawcreate_services.php");

    }


    function deleteDraw(id) {
        if (id == '') {
            toast('error', 'Kindly Refresh and Try Again!');
            return false;
        }

        var formdata = [];
        formdata.push({
            name: 'method',
            value: "deleteDraw_new"
        }, {
            name: 'id',
            value: id
        });
        var btn = $('#sntbtn').html();
        $('#sntbtn').html(`<button class="btn btn-primary" type="button" disabled>
                           <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                           Loading...
                           </button>`);
        var post_data = formdata;
        var onsuccess = function(data) {
            var response = JSON.parse(data);
            if (response != "") {
                if (response.type == 1) {
                    $('#sntbtn').html(btn);
                    $('#testmail').modal('hide');
                    toast('success', response.result);
                    UpcomingDrawList();
                } else {
                    toast('error', response.result);
                }
            }

        }
        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/drawcreate_services.php");
    }

    function generateDrawPicker(id, startd, minDay, maxDate) {
        var start = moment(startd);
        var mindate = moment(minDay);

        function cb(start) {
            // $('#' + id).val(start.format("YYYY-MM-DD HH:mm:ss"));
            $('#' + id).val(start.format("DD MMM YYYY hh:mm a"));

        }

        $('#' + id).daterangepicker({
            startDate: start,
            // endDate: end,
            // minDate: moment().format("MM/DD/YYYY"),
            singleDatePicker: true,
            autoApply: true,
            minDate: moment(mindate).format("MM/DD/YYYY"),
            maxDate: moment(maxDate).format("MM/DD/YYYY"),
            timePicker: true,
            timePicker24Hour: true,
            timePickerSeconds: true,
            maxSpan: {
                days: 89
            },
            autoUpdateInput: false,
        }, cb);

        cb(start);
    }

    function editEndTime(id) {
        var newTime = moment($('#' + id).data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
        if (id == '') {
            toast('error', 'Kindly Refresh and Try Again!');
            return false;
        }
        if (newTime == '') {
            toast('error', 'Kindly Refresh and Try Again!');
            return false;
        }
        var formdata = [];
        formdata.push({
            name: 'method',
            value: "editDraw_new"
        }, {
            name: 'id',
            value: id
        }, {
            name: 'newTime',
            value: newTime
        });

        var post_data = formdata;
        var onsuccess = function(data) {
            var response = JSON.parse(data);
            if (response != "") {
                if (response.type == 1) {
                    // generateTable(response.drawlist);
                    toast('success', response.result);
                    UpcomingDrawList();
                    $('#editTime').modal('hide');
                } else {
                    toast('error', response.result);
                    $('#editTime').modal('hide');
                    //     $('#drawList').html('<div class="alert alert-danger" role="alert" style="text-align: center;">' + response.result + '</div>');
                    // $('#saveDrawbtn').hide();
                }
            }
        }
        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/drawcreate_services.php");
    }


    function editResultTime(id) {
        var newTime = moment($('#' + id).data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
        if (id == '') {
            toast('error', 'Kindly Refresh and Try Again!');
            return false;
        }
        if (newTime == '') {
            toast('error', 'Kindly Refresh and Try Again!');
            return false;
        }
        var formdata = [];
        formdata.push({
            name: 'method',
            value: "UpdateDrawReusltTime"
        }, {
            name: 'id',
            value: id
        }, {
            name: 'newTime',
            value: newTime
        });

        var post_data = formdata;
        var onsuccess = function(data) {
            var response = JSON.parse(data);
            if (response != "") {
                if (response.type == 1) {
                    // generateTable(response.drawlist);
                    toast('success', response.result);
                    UpcomingDrawList();
                    $('#editTime').modal('hide');
                } else {
                    toast('error', response.result);
                    $('#editTime').modal('hide');
                    //     $('#drawList').html('<div class="alert alert-danger" role="alert" style="text-align: center;">' + response.result + '</div>');
                    // $('#saveDrawbtn').hide();
                }
            }
        }
        do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/drawcreate_services.php");
    }
    // function SaveAllDraws() {
    //     var formdata = [];
    //     if ($('#drawfreq').val() == '') {
    //         toast('error', 'Kindly Select the Draw Frequency');
    //         return false;
    //     }

    //     formdata.push({
    //         name: 'method',
    //         value: "SaveAllDraws"
    //     }, {

    //         name: 'frq',
    //         value: $('#drawfreq').val()
    //     }, {
    //         name: 'startDate',
    //         value: moment($('#startDate').data('daterangepicker').startDate._d).format("YYYY-MM-DD")
    //     }, {
    //         name: 'endDate',
    //         value: moment($('#endDate').data('daterangepicker').endDate._d).format("YYYY-MM-DD")
    //     });
    //     var btn = $('#saveDrawbtn').html();
    //     loading('saveDrawbtn');
    //     var post_data = formdata;
    //     var onsuccess = function(data) {
    //         var response = JSON.parse(data);
    //         if (response != "") {
    //             if (response.type == 1) {
    //                 paymentSuccess('success', response.result);
    //                 $('#saveDrawbtn').html(btn);
    //                 // generateTable(response.drawlist);
    //             } else {
    //                 $('#drawList').html('<div class="alert alert-danger" role="alert" style="text-align: center;">' + response.result + '</div>');
    //                 // $('#saveDrawbtn').hide();
    //                 $('#saveDrawbtn').html(btn);
    //             }
    //         }

    //     }

    //     do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/drawcreate_services.php");
    // }


    // function loading(id) {
    //     $('#' + id).html(`<div class="spinner-grow text-primary" role="status">
    //                         <span class="sr-only">Loading...</span>
    //                         </div>
    //                         <div class="spinner-grow text-secondary" role="status">
    //                         <span class="sr-only">Loading...</span>
    //                         </div>
    //                         <div class="spinner-grow text-success" role="status">
    //                         <span class="sr-only">Loading...</span>
    //                         </div>`);
    // }
</script>