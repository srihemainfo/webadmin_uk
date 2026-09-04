<?php
//    Date       Developer_name      Modifications
//    11-12-2024   Surya  G      Development 



$pageTitle = 'Super Raffle Winner';

$now = date("Y-m-d h:i:s");

$draw = select_query($con, "superraffledraw", "", "`status` = 'Active' AND `result_datetime` > '$now' AND `deletes` = '0' ORDER BY `result_datetime` ASC LIMIT 1", "", "");

$resultDate = date_format(date_create($draw['result'][0]['result_datetime']), "Y-m-d h:i:s a");
?>

<style>
    i.fa {
        cursor: pointer;
    }

    .badge-danger.error {
        background-color: #fff;
        /* Red color for error badge */
        color: #f51313;
        /* White text for better visibility */
        font-size: 20px;
    }

    a.dropdown-item.reportdwn {
        cursor: pointer;
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
        width: 100%;
    }

    span.fa.fa-trophy.fs-14 {
        color: #9d8711;
        font-size: 19px !important;
        font-weight: 900;
    }

    .preview {
        max-width: 100%;
        max-height: 200px;
        margin-bottom: 10px;
    }

    button.btn-primary#dropdownMenuButton1 {
        color: #fff !important;
        background: #77bd55 !important;
        border-color: #77bd55 !important;
        font-weight: bolder !important;
    }

    button.btn-danger#dropdownMenuButton1 {
        color: #fff;
        background: #e82646 !important;
        border-color: #e62a45;
        font-weight: bolder !important;
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






            <div class="row row-sm">

                <div class="col-lg-12">

                    <div class="card">

                        <div class="card-header">



                            <div class="col-lg-4">
                                <h3 class="card-title"><strong>Super Raffle List</strong></h3>

                            </div>





                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="sms_report1" style="width:100%;">

                                    <thead>

                                        <tr>
                                            <th class="wd-15p border-bottom-0">Draw Name</th>
                                            <th class="wd-15p border-bottom-0">Start Date & Time</th>
                                            <th class="wd-20p border-bottom-0">End Date & Time</th>
                                            <th class="wd-20p border-bottom-0">Result Date & Time</th>
                                            <!-- <th class="wd-20p border-bottom-0">Draw Status</th> -->
                                            <th class="wd-20p border-bottom-0">Raffle Id</th>
                                            <th class="wd-20p border-bottom-0">Winner Name</th>
                                            <th class="wd-20p border-bottom-0">Prize Category</th>
                                            <th class="wd-20p border-bottom-0">Prize Amount</th>
                                            <th class="wd-20p border-bottom-0">Draw Status</th>
                                            <th class="wd-20p border-bottom-0">Execution Strategy</th>
                                            <th class="wd-20p border-bottom-0">Action</th>
                                        </tr>

                                    </thead>

                                    <tbody>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>






<div class="modal fade" id="winningno">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo ">
            <div class="modal-header">
                <h6 class="modal-title">Winning Raffle Number</h6>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form id="winnerdata">
                        <input type="hidden" name="drawid" id="drawid" value="<?= $draw['result'][0]['id']; ?>">
                        <div class="row">


                            <div class="col-md-12">

                                <div class="text-center">

                                    <h3 class="">Super Raffle Winner</h3>

                                </div>

                                <input type="text" oninput="checkwinner()" id="rafflePrizeTwo" name="rafflePrizeTwo" class="form-control bg-info-gradient rafflePrize" value="" name style="font-size: 48px;font-weight: 900;width: 100%; margin:auto; text-align: center;padding-bottom: 38px;color: white;text-shadow: 0px 1px 1px #919191, 1px 2px 1px #919191, 1px 3px 1px #919191, 1px 4px 1px #919191, 1px 5px 1px #919191, 1px 6px 1px #919191, 1px 7px 1px #919191, 1px 8px 1px #919191, 1px 9px 1px #919191, 1px 10px 1px #919191, 1px 18px 6px rgb(16 16 16 / 40%), 1px 22px 10px rgb(16 16 16 / 20%), 1px 25px 35px rgb(16 16 16 / 20%), 1px 30px 60px rgb(255 255 255 / 40%);">

                                <span class="badge badge-danger error"></span>

                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal-footer">

                <div class="col-sm-12 text-center">

                    <button class="btn btn-primary hideLoading" onclick="winner()" type="button">Submit</button>

                    <button class="btn btn-primary showLoading " style="display:none" disabled type="button"><span class="fa fa-spinner fa-spin"></span>Loading</button>

                </div>

            </div>
        </div>
    </div>
</div>



<script>
    var url = origin + "/ajax/service/report_services.php";
    var ajax_url = origin + "/ajax/service/sms_campaign_services.php";

    $(function() {
        searchTicket();
    })();







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

    function searchTicket() {



        var title = 'Super Raffle Draw';

        var table = $("#sms_report1").DataTable({
            destroy: true,
            pageLength: 10,
            // responsive: {
            //     details: {
            //         type: 'column',
            //         target: -1,
            //     }
            // },
            // columnDefs: [{
            //     targets: -1,
            //     orderable: false,
            //     searchable: true,
            //     className: 'control',
            // }, {
            //     targets: 0,
            //     orderable: false,
            //     searchable: true,
            //     className: 'selectall-checkbox',
            // }],
            // select: {
            //     style: 'multi',
            //     selector: 'td:first-child',
            // },
            order: [
                [3, 'asc']
            ],
            columnDefs: [{
                type: 'date',
                targets: [3]
            }],
            paging: true,
            searching: true,
            info: true,
            ajax: {
                url: origin + "/ajax/service/superRaffleWinnerService.php",
                method: "POST",
                dataSrc: "result",
                data: {
                    method: 'superRaffleService',

                }
            },
            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                // {
                //     extend: 'copyHtml5',
                //     title: title
                // },
                {
                    extend: 'csvHtml5',
                    title: title
                },
                {
                    extend: 'excelHtml5',
                    title: title
                },
                // {
                //     extend: 'pdfHtml5',
                //     orientation: 'landscape',
                //     pageSize: 'LEGAL',
                //     title: title
                // },
                {
                    extend: 'print',
                    title: title
                },
            ],
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.name

                    }
                },


                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return moment(data.ticket_start_datetime).format("DD MMM YYYY hh:mm a")
                    }
                },


                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return moment(data.ticket_end_datetime).format("DD MMM YYYY hh:mm a")
                    }
                },


                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return moment(data.result_datetime).format("DD MMM YYYY hh:mm a")
                    }
                },


                // {
                //     data: null,
                //     render: function (data, type, row, meta) {
                //         return data.status

                //     }
                // },


                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.win_raffle_no ?? ''
                    }
                },

                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return (data.uname ?? '') + ' ' + (data.lname ?? '')
                    }
                },

                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return (data.rate != null && data.rate != undefined && data.rate != '') ?
                            'AED ' + (new Intl.NumberFormat('en-US').format(parseInt(data.rate)) ??
                                '') : ''
                    }
                },

                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return (data.wonprize != null && data.wonprize != undefined && data.wonprize !=
                            '' && data.wonprize > 0) ? 'AED ' + (new Intl.NumberFormat('en-US')
                            .format(parseInt(data.wonprize)) ?? '') : ''
                    }
                },

                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return data.status ?? ''
                    }
                },

                {
                    data: null,
                    render: function(data, type, row, meta) {
                        const currentDatetime = new Date();
                        const year = currentDatetime.getFullYear();
                        const month = String(currentDatetime.getMonth() + 1).padStart(2, '0');
                        const day = String(currentDatetime.getDate()).padStart(2, '0');

                        // Start of the current day (00:00:00)
                        const startOfDay = new Date(`${year}-${month}-${day}T00:00:00Z`);

                        // End of the current day (23:59:59)
                        const endOfDay = new Date(`${year}-${month}-${day}T23:59:59Z`);

                        // Convert data.result_datetime to a Date object for comparison
                        const resultDatetime = new Date(data.result_datetime);
                        return (data.id && data.status != 'Completed' &&
                            resultDatetime >= startOfDay &&
                            resultDatetime <= endOfDay) ? `<div class="dropdown">
                              <button class="${data.execution_strategy == 'DEFAULT' ? 'btn btn-primary' : 'btn btn-danger'} dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">${data.execution_strategy}</button>
                              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                               <li><a onclick="changeDrawStrategy(${data.id}, 'DEFAULT')" class="dropdown-item reportdwn" data-report="0" data-type="excel">DEFAULT</a></li>
                                <li><a  onclick="changeDrawStrategy(${data.id}, 'MANUAL')" class="dropdown-item reportdwn" data-bs-toggle="modal" data-bs-target="#winningno">MANUAL</a></li>
                              </ul>
                            </div>` : ``;

                        //return ``;
                    }
                },

                // {
                //     data: null,
                //     render: function(data, type, row, meta) {
                //         return (data.id && data.status != 'Completed') ? `<div class="dropdown">
                //               <button class="${data.execution_strategy == 'DEFAULT' ? 'btn btn-primary' : 'btn btn-danger'} dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">${data.execution_strategy}</button>
                //               <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                //                <li><a onclick="changeDrawStrategy(${data.id}, 'DEFAULT')" class="dropdown-item reportdwn" data-report="0" data-type="excel">DEFAULT</a></li>
                //                 <li><a  onclick="changeDrawStrategy(${data.id}, 'MANUAL')" class="dropdown-item reportdwn" data-bs-toggle="modal" data-bs-target="#winningno">MANUAL</a></li>
                //               </ul>
                //             </div>` : ``;

                //         //return ``;
                //     }
                // },
                {
                    data: null,
                    // render: function(data, type, row, meta) {
                    //     return (data.id && data.status != 'Completed' && data.result_datetime ==) ?
                    //         `<a class="btn text-primary btn-sm" onclick="announceGrandRaffleWinner(${data.id}, ${data.win_userid})"><span class="fa fa-trophy fs-14">&nbsp;Announce Winner</span></a>` :
                    //         ``
                    // }

                    render: function(data, type, row, meta) {
                        const currentDatetime = new Date();
                        const year = currentDatetime.getFullYear();
                        const month = String(currentDatetime.getMonth() + 1).padStart(2, '0');
                        const day = String(currentDatetime.getDate()).padStart(2, '0');

                        // Start of the current day (00:00:00)
                        const startOfDay = new Date(`${year}-${month}-${day}T00:00:00Z`);

                        // End of the current day (23:59:59)
                        const endOfDay = new Date(`${year}-${month}-${day}T23:59:59Z`);

                        // Convert data.result_datetime to a Date object for comparison
                        const resultDatetime = new Date(data.result_datetime);

                        return (
                                data.id &&
                                data.status !== 'Completed' &&
                                resultDatetime >= startOfDay &&
                                resultDatetime <= endOfDay
                            ) ?
                            `<a class="btn text-primary btn-sm" onclick="announceGrandRaffleWinner(${data.id}, ${data.win_userid})"><span class="fa fa-trophy fs-14">&nbsp;Announce Winner</span></a>` :
                            '';
                    }
                },

            ],

        });

    }




    function changeDrawStrategy(id, stratgy) {
        try {
            if (id == '' || stratgy == '') {
                toast('error', "Kindly Refresh the screen and Try again!");
                return false;
            }

            var formData = new FormData();
            formData.append('method', 'updateGrandRaffleStrategy');
            formData.append('raffleID', id);
            formData.append('stratgy', stratgy);
            $.ajax({
                url: origin + "/ajax/service/superRaffleWinnerService.php",
                type: 'post',
                data: formData,
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response != "") {
                        if (response.type == 0) {
                            // $(`#addagent1`).modal('hide');

                            // Successful response 
                            // if (stratgy == 'DEFAULT') {
                            toast('success', response.result);
                            searchTicket();
                            // }
                            // location.reload();
                        } else {
                            // if (stratgy == 'DEFAULT') {
                            searchTicket();
                            // $(`#addagent1`).modal('hide')
                            toast('error', response.result);
                            // }
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    // toast('error', 'An error occurred during the request');
                },
                processData: false,
                contentType: false
            });

        } catch (error) {
            console.log('Error: ' + error.message);
        }
    }


    function announceGrandRaffleWinner(id, win_userid) {
        try {
            if (id == '') {
                toast('error', "Please Select The Winner And Try Again!");
                return false;
            }

            if (win_userid == '' || win_userid == null || win_userid == undefined) {
                toast('error', "Please Select The Winner And Try Again!");
                return false;
            }

            Swal.fire({
                title: "Are you sure you want to announce this draw?",
                icon: 'warning',
                showDenyButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: 'No'
            }).then((result) => {
                if (result['isConfirmed']) {

                    var formData = new FormData();
                    formData.append('method', 'GrandRaffleAnnounce');
                    formData.append('raffleDrawID', id);
                    formData.append('winnerID', win_userid);

                    $.ajax({
                        url: origin + "/ajax/service/superRaffleWinnerService.php",
                        type: 'post',
                        data: formData,
                        success: function(data) {
                            var response = JSON.parse(data);
                            if (response != "") {
                                if (response.type == 0) {
                                    // $(`#addagent1`).modal('hide');

                                    // Successful response 
                                    toast('success', response.result);
                                    searchTicket();
                                    // location.reload();
                                } else {
                                    searchTicket();
                                    // $(`#addagent1`).modal('hide')
                                    toast('error', response.result);
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error);
                            // toast('error', 'An error occurred during the request');
                        },
                        processData: false,
                        contentType: false
                    });

                }
            });
        } catch (error) {
            console.log('Error: ' + error.message);
        }
    }

    function checkwinner() {




        $('#rafflePrizeTwo').val($('#rafflePrizeTwo').val().replace(/[^A-Za-z0-9]/g, '').toUpperCase());


        $('.showLoading').show();
        $('.hideLoading').hide();
        $('.hideLoading').prop('disabled', true);
        $('.error').html('');





        var formdata = $('#winnerdata').serializeArray();



        formdata.push({

            name: 'method',

            value: "check_winner_list1"

        });

        var post_data = formdata;
        var onsuccess = function(data) {
            var response = JSON.parse(data);
            $('.hideLoading').show();
            $('.showLoading').hide();

            if (response != "") {

                if (response.type == 1) {

                    $('.hideLoading').prop('disabled', false);
                    $('.rafflePrize ').addClass('bg-info-gradient');
                    $('.rafflePrize ').removeClass('bg-danger-gradient');

                } else {
                    var id = response.input;
                    $('#' + id).removeClass('bg-info-gradient');
                    $('#' + id).addClass('bg-danger-gradient');
                    $('#' + id).next().html(response.result);

                }

            }

        }
        do_ajax_call(post_data, onsuccess, origin + '/ajax/service/superRaffleWinnerService.php');
    }

    function winner() {
        $('.showLoading').show();
        $('.hideLoading').hide();
        $('.error').html('');

        var error = 0;

        if ($('#rafflePrizeTwo').val() == '') {
            error++;
            $('#rafflePrizeTwo').next().html('Please Enter Raffle Number');
        }

        if (error > 0) {

            $('.hideLoading').show();
            $('.showLoading').hide();
            return false;
        }

        var formdata = $('#winnerdata').serializeArray();
        formdata.push({
            name: 'method',
            value: "winner_list1"

        });

        var post_data = formdata;
        var onsuccess = function(data) {

            var response = JSON.parse(data);

            $('.hideLoading').show();
            $('.showLoading').hide();

            if (response != "") {
                if (response.type == 1) {
                    let origin = window.location.origin;



                    $('#winningno').modal('hide');
                    // toast('success', 'Winner Announce Successfully');
                    $('#rafflePrizeTwo').val('');

                    // let url = origin + '/superrafflewinners/edit/' + response.id;

                    //console.log(url);

                    //window.location.href = url;
                    toast('success', response.result);
                    searchTicket();

                } else {

                    var id = response.input;
                    $('#' + id).removeClass('bg-info-gradient');
                    $('#' + id).addClass('bg-danger-gradient');
                    $('#' + id).next().html(response.result);

                    searchTicket();
                    // $(`#addagent1`).modal('hide')
                    toast('error', response.result);
                }
            }
        }
        do_ajax_call(post_data, onsuccess, origin + '/ajax/service/superRaffleWinnerService.php');
    }
</script>