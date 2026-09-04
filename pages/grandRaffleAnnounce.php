<?php
//    Date       Developer_name      Modifications
//    01-12-2023   Devanathan  K      Development 



$pageTitle = 'Grand Raffle';

?>

<style>
    i.fa {
        cursor: pointer;
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
                                <h3 class="card-title"><strong>Grand Raffle List</strong></h3>

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
                                            <th class="wd-20p border-bottom-0">Raffle No</th>
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



        var title = 'Grand Raffle Draw';

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
                [3, 'desc']
            ],
            columnDefs: [{
                type: 'date',
                targets: [3]
            }],
            paging: true,
            searching: true,
            info: true,
            ajax: {
                url: origin + "/ajax/service/grandRaffleService.php",
                method: "POST",
                dataSrc: "result",
                data: {
                    method: 'grandRaffleService',

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
                        // return (data.id == '2' && data.status != 'Completed') ? `<div class="dropdown">
                        //       <button class="${data.execution_strategy == 'DEFAULT' ? 'btn btn-primary' : 'btn btn-danger'} dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">${data.execution_strategy}</button>
                        //       <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        //        <li><a onclick="changeDrawStrategy(${data.id}, 'DEFAULT')" class="dropdown-item reportdwn" data-report="0" data-type="excel">DEFAULT</a></li>
                        //         <li><a  onclick="changeDrawStrategy(${data.id}, 'RANDOM')" class="dropdown-item reportdwn" data-report="0" data-type="pdf">RANDOM</a></li>
                        //       </ul>
                        //     </div>` : ``;

                        return ``;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return (data.id == '2' && data.status != 'Completed') ?
                            `<a class="btn text-primary btn-sm" onclick="announceGrandRaffleWinner(${data.id}, ${data.win_userid})"><span class="fa fa-trophy fs-14">&nbsp;Announce Winner</span></a>` :
                            ``
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
                url: origin + "/ajax/service/grandRaffleService.php",
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

        } catch (error) {
            console.log('Error: ' + error.message);
        }
    }


    function announceGrandRaffleWinner(id, win_userid) {
        try {
            if (id == '') {
                toast('error', "Kindly Refresh the screen and Try again!");
                return false;
            }

            if (win_userid == '' || win_userid == null || win_userid == undefined) {
                toast('error', "Kindly Refresh the screen and Try again!");
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
                        url: origin + "/ajax/service/grandRaffleService.php",
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
</script>