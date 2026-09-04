<?php
$pageTitle = ucwords(strtolower('Gold Rate'));
?>

<style>
    .nd-resend {
        position: relative;

        padding: 0.5em 1.5em;
        background-color: #2a1b52;

        border-radius: 28px 7px;
        color: white !important;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        transition: all 0.5s ease;
        cursor: pointer;
    }


    .nd-resend:hover {
        border-radius: 7px 28px;

        background: #089247;
        letter-spacing: 0.2em;

        color: #fff;
    }

    .bg-mon {
        background-color: #F1F500;
    }


    .bg-thrill {
        background-color: cyan;
    }

    .swal2-container {
        z-index: 9999;
    }

    .thirll-bg {
        border-radius: 12px;
    }

    input,
    button {
        height: auto !important;
    }

    .raffle-in {
        height: 60px;
        font-size: 2vw;
        background: #E3C7AF;
        /* border: 0; */
        text-align: center;
    }

    .modal-content {
        border: 0;
        border-radius: 12px;
        background-image: radial-gradient(circle at 29% 55%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 4%, transparent 4%, transparent 44%, transparent 44%, transparent 100%), radial-gradient(circle at 85% 89%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 51%, transparent 51%, transparent 52%, transparent 52%, transparent 100%), radial-gradient(circle at 6% 90%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 53%, transparent 53%, transparent 64%, transparent 64%, transparent 100%), radial-gradient(circle at 35% 75%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 6%, transparent 6%, transparent 98%, transparent 98%, transparent 100%), radial-gradient(circle at 56% 75%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 16%, transparent 16%, transparent 23%, transparent 23%, transparent 100%), radial-gradient(circle at 42% 0%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 3%, transparent 3%, transparent 26%, transparent 26%, transparent 100%), radial-gradient(circle at 29% 28%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 51%, transparent 51%, transparent 75%, transparent 75%, transparent 100%), radial-gradient(circle at 77% 21%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 35%, transparent 35%, transparent 55%, transparent 55%, transparent 100%), radial-gradient(circle at 65% 91%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 46%, transparent 46%, transparent 76%, transparent 76%, transparent 100%), linear-gradient(45deg, rgb(34 9 100), rgb(31 20 58));
        /* background-image: radial-gradient(circle at 29% 55%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 4%, transparent 4%, transparent 44%, transparent 44%, transparent 100%), radial-gradient(circle at 85% 89%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 51%, transparent 51%, transparent 52%, transparent 52%, transparent 100%), radial-gradient(circle at 6% 90%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 53%, transparent 53%, transparent 64%, transparent 64%, transparent 100%), radial-gradient(circle at 35% 75%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 6%, transparent 6%, transparent 98%, transparent 98%, transparent 100%), radial-gradient(circle at 56% 75%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 16%, transparent 16%, transparent 23%, transparent 23%, transparent 100%), radial-gradient(circle at 42% 0%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 3%, transparent 3%, transparent 26%, transparent 26%, transparent 100%), radial-gradient(circle at 29% 28%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 51%, transparent 51%, transparent 75%, transparent 75%, transparent 100%), radial-gradient(circle at 77% 21%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 35%, transparent 35%, transparent 55%, transparent 55%, transparent 100%), radial-gradient(circle at 65% 91%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 46%, transparent 46%, transparent 76%, transparent 76%, transparent 100%), linear-gradient(45deg, rgb(6 119 55), rgb(6 119 55)); */
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

    #mytext {
        width: 361px;
        height: 63px;
        border: 0px;
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
        var n = window.location.origin;
        document.getElementById("anchor").href = n
    };
</script>


<div class="main-content app-content mt-0">



    <div class="side-app">



        <!-- CONTAINER -->

        <div class="main-container container-fluid">



            <!-- PAGE-HEADER -->

            <div class="page-header">

                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a><?= $pageTitle; ?></h1>



                <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                    <li class="breadcrumb-item active" aria-current="page"><?= $pageTitle; ?></li>

                </ol>



            </div>

            <!-- PAGE-HEADER END -->



            <!-- ROW-1 -->

            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">

                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">

                            <div class="card overflow-hidden">

                                <div class="card-body mt-2">





                                    <!-- <div class="row">

                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                            <label for="reportrange">Result Date</label>

                                            <input class="form-control" type="text" id="reportrange" value="" placeholder="YYYY-MM-DD" autocomplete="off" maxlength="10" oninput="this.value = this.value.replace(/[^0-9-]/g, '');" />
                                        </div>









                                        <div class="col-lg-3 col-md-3 col-sm-6 mt-5">
                                            <button type="submit" id="smslogsearch" class="btn btn-info" onclick="searchTicket()">Go</button>
                                        </div>

                                    </div> -->





                                    <div class="row">
                                        <div class="col-12">
                                            <div id="calendar"></div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>









                    </div>

                </div>

            </div>












            <!-- <div class="row row-sm">

                <div class="col-lg-12">

                    <div class="card">

                        <div class="card-header">



                            <div class="col-lg-12 col-md-12 col-sm-12">

                                <h3 class="card-title"><strong>Draw List</strong></h3>

                            </div>





                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="sms_report1" style="width:100%;">

                                    <thead>

                                        <tr>

                                           
                                            <th class="wd-15p border-bottom-0">Result Date</th>

                                            <th class="wd-20p border-bottom-0">Today GOLD Rate (AED)</th>
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

            </div> -->



        </div>



    </div>

</div>







<div class="modal fade" id="WinnerPreview" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="winnerModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bo">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="winnerModalTitle">Winner Preview</h5>

                <button class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">×</span>

                </button>

            </div>
            <div class="modal-body">
                <form class="row d-flex justify-content-center" id="raffleIDColumn">
                    <div class="my-2" id="thrillDrawPre">


                    </div>
                    <div class="my-2" id="conDrawPre">


                    </div>
                    <div class=" my-2" id="monDrawPre">


                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-secondary" style="display: none;" id="announceWinnerBtn">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
    let drawStatus = ['Active', 'Completed'];
    const calendar = $('#calendar');
    let isCalendarInitialized = false;
    $().ready(function() {

        createCalendar(calendar);
        setEventsTo(calendar);
    });


    // $().ready(function() {
    //     calendar.evoCalendar({
    //         // settingName: settingValue
    //         theme: 'Orange Coral',
    //         language: 'en',
    //         todayHighlight: true,
    //         eventDisplay: true,
    //         // sidebarDisplay: false
    //     });

    //     // Side Bar
    //     calendar.evoCalendar('toggleSidebar', false);

    //     setEventsTo();
    // });


    const createCalendar = () => {
        try {
            if (isCalendarInitialized) {
                calendar.evoCalendar('destroy');
                isCalendarInitialized = false;
            }
            calendar.evoCalendar({
                theme: 'Orange Coral',
                language: 'en',
                todayHighlight: true,
                eventDisplay: true,
            });

            // Hide the sidebar
            calendar.evoCalendar('toggleSidebar', false);
            isCalendarInitialized = true;

        } catch (e) {
            console.log('Error: ' + e.message);
            isCalendarInitialized = false;
        }
    }

    calendar.on('selectDate', function() {
        const selectedDate = calendar.evoCalendar('getActiveDate', 'date');
        // alert(selectedDate);

        // calendar.evoCalendar('addCalendarEvent', [{
        //     name: "<div><h1>NEW EVENT</h1></div>",
        //     date: moment(selectedDate, "MM/DD/YYYY").format("MMMM/DD/YYYY"),
        //     type: "Draw",
        //     everyYear: false,
        //     // html: '<div><ul><li>Event 1</li><li>Event 2</li><li>Event 3</li></ul></div>'
        // }]);


        // Event List
        calendar.evoCalendar('toggleEventList', true);
    });


    const setEventsTo = () => {

        try {
            createCalendar(calendar);


            var h = new FormData();
            h.append('method', 'getAllDraw');
            h.append('getAll', true);
            // h.append('todayGoldPrize', todayGoldPrize);
            // h.append('deviceType', deviceType);

            $.ajax({
                type: 'POST',
                url: origin + '/ajax/service/drawManagerServices.php', // Replace with the actual server-side script
                data: h,
                success: function(response) {


                    var response = JSON.parse(response);
                    // searchTicket();

                    if (response != "") {
                        var eventList = [];
                        response.result.forEach(element => {
                            let html = `<div>`;


                            if (drawStatus.includes(element.dailyThirllStatus)) {
                                html += `<h6>${element.dailyThrillName + ' #'+ String(element.dailyDrawNo).padStart(3, '0')}</h6>`;
                            }

                            if (drawStatus.includes(element.weeklyBoosterStatus)) {
                                html += `<h6>${element.weeklyBoosterName + ' #'+ String(element.weeklyDrawNo).padStart(3, '0')}</h6>`;
                            }

                            if (drawStatus.includes(element.monthlyBumperStatus)) {
                                html += `<h6>${element.monthlyBumperName + ' #'+ String(element.bumperDrawNo).padStart(3, '0')}</h6>`;
                            }


                            const todayGoldPrize = Intl.NumberFormat('en-US').format(parseFloat(element.todayGoldPrize));
                            html += `<br><label>Gold Rate Per Gram (AED)</label><input type="text" id="todayGoldPrize${element.id}" class="form-control" value="${todayGoldPrize}" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" maxlength="10">`;


                            if (element.dailyThirllStatus === 'Active' || element.weeklyBoosterStatus === 'Active' || element.monthlyBumperStatus === 'Active') {
                                html += `<button id="updateRate${element.id}" class="btn btn-info mt-2" onclick="updateGlodRate(${element.id})">Update</button>`;
                            }




                            html += `</div>`;





                            eventList.push({
                                // id:"required-id-2",

                                name: 'Draw',
                                id: `drawevent` + element.id,
                                date: moment(element.resultDate).format("MMMM/DD/YYYY"),
                                type: "Draw",
                                everyYear: false,
                                description: html
                            });



                        });

                        calendar.evoCalendar('addCalendarEvent', eventList);

                        // console.log(eventList);
                        // if (parseInt(response.type) == 1) {
                        //     showToast('success', response.result, 5000);
                        // } else if (parseInt(response.type) == 0) {
                        //     showToast('error', response.result, 5000);
                        // }

                    }




                },
                processData: false,
                contentType: false,
                error: function(error) {

                    console.error('Error deleting record:', error);
                }
            });



        } catch (e) {
            console.log('Error: ' + e.message);
        }
    }



    const singleDatePicker = (id) => {
        try {
            var start = moment().set({
                hour: 0,
                minute: 0,
                second: 0,
                millisecond: 0
            });

            function cb(start) {
                $('#' + id + ' span').html(start.format("YYYY-MM-DD"));
            }

            $('#' + id).daterangepicker({
                // startDate: start, // Set start date to null to prevent automatic selection
                singleDatePicker: true,
                // autoApply: true,
                timePicker: false,
                timePicker24Hour: false,
                timePickerSeconds: false,
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            }, cb);

            cb(start);

            $('#' + id).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format("YYYY-MM-DD"));
            });

            $('#' + id).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        } catch (e) {
            console.log('Error: ' + e.message);
        }
    }

    // const searchTicket = () => {

    //     try {
    //         var title = 'Draw List';
    //         let searchBTN = $(`#smslogsearch`);

    //         var table = $("#sms_report1").DataTable({
    //             destroy: true,
    //             pageLength: 10,
    //             responsive: false,
    //             // layout: {
    //             //     topStart: {
    //             //         buttons: ['colvis']
    //             //     }
    //             // },
    //             order: [
    //                 [0, 'asc']
    //             ],
    //             columnDefs: [{
    //                 type: 'date',
    //                 targets: [0, 1] // Assuming the first two columns are date columns
    //             }],
    //             paging: true,
    //             searching: true,
    //             info: true,
    //             ajax: {
    //                 url: origin + '/ajax/service/drawManagerServices.php',
    //                 method: "POST",
    //                 dataSrc: "result",
    //                 data: function(d) {

    //                     // Button Loading
    //                     searchBTN.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="sr-only">Loading...</span>`).prop('disabled', true);

    //                     //   Request 
    //                     d.method = 'getAllDraw';
    //                     d.resultDate = $('#reportrange').val();
    //                 }
    //             },
    //             dom: 'Bfrtip',
    //             buttons: [
    //                 'pageLength',
    //                 'colvis',
    //                 // {
    //                 //     extend: 'copyHtml5',
    //                 //     title: title
    //                 // },
    //                 // {
    //                 //     extend: 'csvHtml5',
    //                 //     title: title
    //                 // },
    //                 {
    //                     extend: 'excelHtml5',
    //                     title: title
    //                 },
    //                 // {
    //                 //     extend: 'pdfHtml5',
    //                 //     orientation: 'landscape',
    //                 //     pageSize: 'LEGAL',
    //                 //     title: title
    //                 // },
    //                 // {
    //                 //     extend: 'print',
    //                 //     title: title
    //                 // },
    //             ],
    //             columns:

    //                 [

    //                     // {
    //                     //     data: null,
    //                     //     render: function(data, type, row, meta) {
    //                     //         return moment(data.saleDate).format("DD MMM YYYY")
    //                     //     }
    //                     // },

    //                     {
    //                         data: null,
    //                         render: function(data, type, row, meta) {
    //                             return moment(data.resultDate).format("DD MMM YYYY")
    //                         }
    //                     },
    //                     {
    //                         data: null,
    //                         render: function(data, type, row, meta) {
    //                             return Intl.NumberFormat('en-US').format(parseFloat(data.todayGoldPrize));
    //                         }
    //                     },
    //                     // {
    //                     //     data: null,
    //                     //     render: function(data, type, row, meta) {
    //                     //         return drawStatus.includes(data.dailyThirllStatus) ? data.dailyThirllPrice : 'NA'
    //                     //     }
    //                     // },

    //                     // {
    //                     //     data: null,
    //                     //     render: function(data, type, row, meta) {
    //                     //         return drawStatus.includes(data.weeklyBoosterStatus) ? data.dailyConsolationPrice : 'NA'
    //                     //     }
    //                     // },

    //                     // {
    //                     //     data: null,
    //                     //     render: function(data, type, row, meta) {
    //                     //         return data.monthlyBumperPrice != '' && parseInt(data.monthlyBumperPrice) != 0 && drawStatus.includes(data.monthlyBumperStatus) ? data.monthlyBumperPrice : 'NA'
    //                     //     }
    //                     // },


    //                     {
    //                         data: null,
    //                         render: function(data, type, row, meta) {
    //                             var btn = '';

    //                             const resultDateString = data.resultDate;
    //                             const resultDate = moment(resultDateString, 'YYYY-MM-DD');
    //                             const currentDate = moment();

    //                             if ((resultDate.isSame(currentDate, 'day') || resultDate.isBefore(currentDate, 'day'))) {
    //                                 // if (data.dailyThirllStatus && data.dailyThirllStatus === 'Active' && data.winThirllRaffleIds != null && data.winThirllRaffleIds != undefined && data.winThirllRaffleIds != '' && data.winThirllRaffleIds != 'NULL') {
    //                                 // btn += `<a style="cursor: pointer;" onclick="WinnerDailyAnnounce(${data.id}, 'Daily Thirll Draw Announcement', 1, 'dailyThrill', '${data.winThirllRaffleIds}')"><span class="fa fa-bullhorn" style="color: #576025db;font-size: 16px;font-weight: bold;">&nbsp;Daily Thirll Announcement</span></a>&nbsp;&nbsp;`;
    //                                 // }

    //                                 // if (data.weeklyBoosterStatus && data.weeklyBoosterStatus === 'Active' && data.winweeklyBoosterIds != null && data.winweeklyBoosterIds != undefined && data.winweeklyBoosterIds != '' && data.winweeklyBoosterIds != 'NULL') {
    //                                 // const jsonArray = JSON.parse(data.winweeklyBoosterIds);
    //                                 // btn += `<a style="cursor: pointer;" onclick="WinnerDailyAnnounce(${data.id}, 'Daily Consolation Draw Announcement', 10, 'dailyConsolation', '${jsonArray}')"><span class="fa fa-bullhorn" style="color: #28413cdb;font-size: 16px;font-weight: bold;">&nbsp;Daily Consolation Announcement</span></a>&nbsp;&nbsp;`;
    //                                 // }

    //                                 // if (data.monthlyBumperStatus && data.monthlyBumperStatus === 'Active' && data.winBumperRaffleIds != null && data.winBumperRaffleIds != undefined && data.winBumperRaffleIds != '' && data.winBumperRaffleIds != 'NULL') {
    //                                 // btn += `<a style="cursor: pointer;" onclick="WinnerDailyAnnounce(${data.id}, 'Monthly Bumper Draw Announcement', 1, 'monthlyBumber', '${data.winBumperRaffleIds}')"><span class="fa fa-bullhorn" style="color: #592560db;font-size: 16px;font-weight: bold;">&nbsp;Monthly Bumper Announcement</span></a>&nbsp;&nbsp;`;
    //                                 // }

    //                                 if ((data.dailyThirllStatus && data.dailyThirllStatus === 'Active' && data.winThirllRaffleIds != null && data.winThirllRaffleIds != undefined && data.winThirllRaffleIds != '' && data.winThirllRaffleIds != 'NULL') ||
    //                                     (data.weeklyBoosterStatus && data.weeklyBoosterStatus === 'Active' && data.winweeklyBoosterIds != null && data.winweeklyBoosterIds != undefined && data.winweeklyBoosterIds != '' && data.winweeklyBoosterIds != 'NULL') ||
    //                                     (data.monthlyBumperStatus && data.monthlyBumperStatus === 'Active' && data.winBumperRaffleIds != null && data.winBumperRaffleIds != undefined && data.winBumperRaffleIds != '' && data.winBumperRaffleIds != 'NULL') ||
    //                                     (data.dailyThirllStatus === 'Completed' || data.weeklyBoosterStatus === 'Completed' || data.monthlyBumperStatus === 'Completed')) {
    //                                     // const jsonArray = JSON.parse(data.winweeklyBoosterIds);
    //                                     btn += `<a style="cursor: pointer;" onclick="winnerPreview(${data.id}, '${(data.dailyThirllStatus != 'Active' && data.weeklyBoosterStatus != 'Active' && data.monthlyBumperStatus != 'Active') ? 'WinnerList' : 'WinnerPreview'}')"><span class="fa fa-eye" style="color: #576025db;font-size: 16px;font-weight: bold;">&nbsp;${(data.dailyThirllStatus != 'Active' && data.weeklyBoosterStatus != 'Active' && data.monthlyBumperStatus != 'Active') ? 'Winner List' : 'Winner Preview'}</span></a>&nbsp;&nbsp;`;
    //                                 }

    //                             }

    //                             return btn;
    //                         }
    //                     },

    //                 ],
    //             initComplete: function(settings, json) {

    //                 // Loading Off
    //                 searchBTN.html(`GO`).prop('disabled', false);

    //                 if (json.type === '0') {
    //                     showToast("warning", "No data available in table", 5000);
    //                 }

    //                 // showToast("warning", "No data available in table", 5000);
    //             }

    //         });
    //     } catch (e) {
    //         console.log('Error: ' + e.message);
    //     }
    // }

    function updateGlodRate(id) {
        try {
            var todayGoldPrize = $(`#todayGoldPrize${id}`).val();
            var btn = $(`#updateRate${id}`);

            if (id == '' || id == null) {
                showToast('error', 'The Image ID has been missing!', 5000);
                return false;
            }

            if (todayGoldPrize == '' || todayGoldPrize == null) {
                showToast('error', 'The Order has been missing!', 5000);
                return false;
            }

            if (parseFloat(todayGoldPrize) < 1) {
                showToast('error', 'The gold prize requires more than 1 AED!', 5000);
                return false;
            }



            Swal.fire({

                title: "Are you sure?",
                text: "Do you want to update the order!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, update it!",
                allowOutsideClick: false

            }).then((result) => {

                if (result.isConfirmed) {



                    var h = new FormData();
                    h.append('method', 'updateGlodRate');
                    h.append('drawID', id);
                    h.append('todayGoldPrize', todayGoldPrize);
                    // h.append('deviceType', deviceType);

                    $.ajax({
                        type: 'POST',
                        url: origin + "/ajax/service/drawManagerServices.php", // Replace with the actual server-side script
                        data: h,
                        beforeSend: function() {
                            // Button Loading
                            btn.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Loading...`).prop('disabled', true);
                        },
                        success: function(response) {


                            var response = JSON.parse(response);
                            // searchTicket();

                            if (response != "") {

                                if (parseInt(response.type) == 1) {
                                    showToast('success', response.result, 5000);
                                } else if (parseInt(response.type) == 0) {
                                    showToast('error', response.result, 5000);
                                }

                            }


                            btn.html(`Update`).prop('disabled', false);

                        },
                        processData: false,
                        contentType: false,
                        error: function(error) {
                            btn.html(`Update`).prop('disabled', false);
                            console.error('Error deleting record:', error);
                        }
                    });

                } else {
                    btn.html(`Update`).prop('disabled', false);
                }

            });
        } catch (e) {
            console.log('Error: ' + e.message);
        }

    }


    const winnerPreview = (id, method = 'WinnerPreview') => {
        try {

            $(`#thrillDrawPre,#monDrawPre,#conDrawPre`).html('');

            if (id == '' || id == undefined || id == null) {
                showToast("error", "The Title Missing. Kindly Refresh and Try Again!", 5000);
                return false;
            }


            const requestData = {
                method: method,
                drawID: id
            };


            $.ajax({
                url: origin + '/ajax/service/drawManagerServices.php',
                type: 'POST',
                data: requestData,
                beforeSend: function() {
                    // Button Loading
                    // saveRaffleIDBTN.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Loading...`).prop('disabled', true);
                },
                success: function(data) {
                    // console.log('Request successful');

                    var response = JSON.parse(data);

                    if (response != "") {
                        if (response.type == '1') {
                            // $(`#WinnerDailyAnnounce`).modal('hide');
                            $(`#winnerModalTitle`).text((data.dailyThirllStatus != 'Active' && data.weeklyBoosterStatus != 'Active' && data.monthlyBumperStatus != 'Active') ? 'Winner List' : 'Winner Preview');

                            if (response.result != '' && response.result != null && response.result != undefined) {
                                showToast("success", response.result, 5000);
                            }

                            if (response.winnerData?.winThirllRaffleIds?.RaffleID != null) {

                                var thrillDrawPre = '';
                                thrillDrawPre += `<h4 class="text-white text-center">Thirll Draw Prize GOLD (KG)</h4>`;
                                thrillDrawPre += `<div class="row justify-content-center">
                                                        <div class="col-md-6">
                                                            <div class="bg-thrill thirll-bg p-2">
                                                                <div class="row">
                                                                    <div class="col-4"><b>Name </b></div>
                                                                    <div class="col-8">: ${response.winnerData?.winThirllRaffleIds?.Name}</div>
                                                                    <div class="col-4"><b>Mobile number</b></div>
                                                                    <div class="col-8">: ${response.winnerData?.winThirllRaffleIds?.Mobile}</div>
                                                                    <div class="col-4"><b>E-mail</b></div>
                                                                    <div class="col-8">: ${response.winnerData?.winThirllRaffleIds?.Email}</div>
                                                                    <div class="col-4"><b>Prize (GOLD)</b></div>
                                                                    <div class="col-8">: ${response.winnerData?.winThirllRaffleIds?.prize} Gram</div>
                                                                    <div class="col-4"><b>Prize Amount</b></div>
                                                                    <div class="col-8">: ${Intl.NumberFormat('en-US').format(parseFloat(response.winnerData?.winThirllRaffleIds?.prizeAmt))} AED</div>
                                                                    <div class="col-4"><b>Raffle ID</b></div>
                                                                    <div class="col-8">: ${response.winnerData?.winThirllRaffleIds?.RaffleID}</div>
                                                                </div>${response.dailyThirllStatus === 'Completed' ? `<div class="text-center my-3">
                                                                    <a id="resend${response.winnerData?.winThirllRaffleIds?.RaffleID}" onclick="resendWinner(${id}, '${response.winnerData?.winThirllRaffleIds?.RaffleID}', '${response.winnerData?.winThirllRaffleIds?.ticketReferenceID}', '${response.winnerData?.winThirllRaffleIds?.ticketID}', $(this).attr('id'))" class="nd-resend">Resend</a>
                                                                </div>`:''}
                                                               
                                                            </div>
                                                        </div>
                                                    </div> `;

                                $(`#thrillDrawPre`).html(thrillDrawPre);
                            }

                            if (response.winnerData?.winBumperRaffleIds?.RaffleID != null) {

                                var monDrawPre = '';
                                monDrawPre += `<h4 class="text-white text-center">Monthly Bumper Draw Prize GOLD (KG)</h4>`;
                                monDrawPre += `<div class="row justify-content-center">
                                                    <div class="col-md-6">
                                                        <div class="bg-mon thirll-bg p-2">
                                                            <div class="row">
                                                                <div class="col-4"><b>Name </b></div>
                                                                <div class="col-8">: ${response.winnerData?.winBumperRaffleIds?.Name}</div>
                                                                <div class="col-4"><b>Mobile number</b></div>
                                                                <div class="col-8">: ${response.winnerData?.winBumperRaffleIds?.Mobile}</div>
                                                                <div class="col-4"><b>E-mail</b></div>
                                                                <div class="col-8">: ${response.winnerData?.winBumperRaffleIds?.Email}</div>
                                                                <div class="col-4"><b>Prize (GOLD)</b></div>
                                                                <div class="col-8">: ${response.winnerData?.winBumperRaffleIds?.prize} Gram</div>
                                                                <div class="col-4"><b>Prize Amount</b></div>
                                                                <div class="col-8">: ${Intl.NumberFormat('en-US').format(parseFloat(response.winnerData?.winBumperRaffleIds?.prizeAmt))} AED</div>
                                                                <div class="col-4"><b>Raffle ID</b></div>
                                                                <div class="col-8">: ${response.winnerData?.winBumperRaffleIds?.RaffleID}</div>
                                                            </div>${response.monthlyBumperStatus === 'Completed' ? `<div class="text-center my-3"> 
                                                                <a id="resend${response.winnerData?.winBumperRaffleIds?.RaffleID}" onclick="resendWinner(${id}, '${response.winnerData?.winBumperRaffleIds?.RaffleID}', '${response.winnerData?.winBumperRaffleIds?.ticketReferenceID}', '${response.winnerData?.winBumperRaffleIds?.ticketID}', $(this).attr('id'))" class="nd-resend">Resend</a>
                                                            </div>` :''}
                                                            
                                                        </div>
                                                    </div>
                                                </div>`;

                                $(`#monDrawPre`).html(monDrawPre);
                            }

                            if (response.winnerData?.winweeklyBoosterIds?.RaffleID != null) {

                                var conDrawPre = '';
                                conDrawPre += `<h4 class="text-white text-center">Weekly Booster Draw Prize GOLD (KG)</h4>`;
                                conDrawPre += `<div class="row justify-content-center">`;
                                //  response.winnerData?.winweeklyBoosterIds.forEach(function(element) {
                                conDrawPre += `<div class="col-md-6 mb-3">
                                                            <div class="bg-white thirll-bg p-2">
                                                                <div class="row">
                                                                    <div class="col-4"><b>Name </b></div>
                                                                    <div class="col-8">: ${response.winnerData?.winweeklyBoosterIds?.Name}</div>
                                                                    <div class="col-4"><b>Mobile number</b></div>
                                                                    <div class="col-8">: ${response.winnerData?.winweeklyBoosterIds?.Mobile}</div>
                                                                    <div class="col-4"><b>E-mail</b></div>
                                                                    <div class="col-8">: ${response.winnerData?.winweeklyBoosterIds?.Email}</div>
                                                                    <div class="col-4"><b>Prize (GOLD)</b></div>
                                                                    <div class="col-8">: ${response.winnerData?.winweeklyBoosterIds?.prize} Gram</div>
                                                                    <div class="col-4"><b>Prize Amount</b></div>
                                                                    <div class="col-8">: ${Intl.NumberFormat('en-US').format(parseFloat(response.winnerData?.winweeklyBoosterIds?.prizeAmt))} AED</div>
                                                                    <div class="col-4"><b>Raffle ID</b></div>
                                                                    <div class="col-8">: ${response.winnerData?.winweeklyBoosterIds?.RaffleID}</div>
                                                                </div>${response.weeklyBoosterStatus === 'Completed' ? `<div class="text-center my-3">
                                                                    <a id="resend${response.winnerData?.winweeklyBoosterIds?.RaffleID}" onclick="resendWinner(${id}, '${response.winnerData?.winweeklyBoosterIds?.RaffleID}', '${response.winnerData?.winweeklyBoosterIds?.ticketReferenceID}', '${response.winnerData?.winweeklyBoosterIds?.ticketID}', $(this).attr('id'))" class="nd-resend">Resend</a>
                                                                </div>` : ''}
                                                                
                                                            </div>
                                                        </div>`;
                                //   });
                                conDrawPre += `</div>`;

                                $(`#conDrawPre`).html(conDrawPre);
                            }

                            $(`#WinnerPreview`).modal('show');


                            if (response.dailyThirllStatus === 'Active' || response.weeklyBoosterStatus === 'Active' || response.monthlyBumperStatus === 'Active') {
                                $(`#announceWinnerBtn`).attr('onclick', `winnerAnnonuce(${id})`).text('Announce').show();
                            }


                        } else {
                            if (response.result != '' && response.result != null && response.result != undefined) {
                                showToast("error", response.result, 5000);
                            }


                            // if (response.errorRaffleID && response.errorRaffleID.length > 0) {
                            //     response.errorRaffleID.forEach(function(element) {
                            //         $(`#${element.id}`).css('border', '3px solid red');
                            //         $(`#${element.id}err`).text(element.error).show();
                            //     });
                            // }

                        }
                    } else {
                        showToast("error", "Request failed", 5000);
                        // console.error('Request failed');

                    }
                    // Loading Off 
                    // saveRaffleIDBTN.html(`Submit`).prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    showToast("error", "Request failed", 5000);
                    // saveRaffleIDBTN.html(`Submit`).prop('disabled', false);
                    console.error('Request failed');
                    console.error(xhr, status, error);
                }
            });



        } catch (e) {
            console.log('Error: ' + e.message);
        }
    }



    const winnerAnnonuce = (id) => {
        try {



            if (id == '' || id == undefined || id == null) {
                showToast("error", "The Title Missing. Kindly Refresh and Try Again!", 5000);
                return false;
            }

            let announceWinnerBtn = $(`#announceWinnerBtn`);
            var h = [];
            h.push({
                name: 'method',
                value: "winnerAnnonuce"
            }, {
                name: 'drawID',
                value: id
            });

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, announce it!",
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {

                    // Swal.fire({
                    //     title: "Deleted!",
                    //     text: "Your file has been deleted.",
                    //     icon: "success"
                    // });

                    $.ajax({
                        url: origin + '/ajax/service/drawManagerServices.php',
                        type: 'POST',
                        data: h,
                        beforeSend: function() {
                            // Button Loading
                            announceWinnerBtn.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Loading...`).prop('disabled', true);
                        },
                        success: function(data) {
                            // console.log('Request successful');

                            var response = JSON.parse(data);

                            if (response != "") {
                                if (response.type == '1') {
                                    $(`#WinnerPreview`).modal('hide');
                                    if (response.result != '' && response.result != null && response.result != undefined) {
                                        showToast("success", response.result, 5000, searchTicket);
                                    }
                                    // Loading Off 
                                    announceWinnerBtn.html(`Announce`).prop('disabled', true).hide();
                                } else {
                                    if (response.result != '' && response.result != null && response.result != undefined) {
                                        showToast("error", response.result, 5000);
                                    }

                                }
                            } else {
                                showToast("error", "Request failed", 5000);

                            }
                            // Loading Off 
                            announceWinnerBtn.html(`Announce`).prop('disabled', false);
                        },
                        error: function(xhr, status, error) {
                            showToast("error", "Request failed", 5000);
                            announceWinnerBtn.html(`Announce`).prop('disabled', false);
                            console.error('Request failed');
                            console.error(xhr, status, error);
                        }
                    });

                } else {
                    $(`#WinnerPreview`).modal('hide');
                }
            });




        } catch (e) {
            console.log('Error: ' + e.message);
        }
    }


    const resendWinner = (id, RaffleID, ticketReferenceID, ticketID, btnID) => {
        try {

            if (id == '' || id == undefined || id == null) {
                showToast("error", "The Title Missing. Kindly Refresh and Try Again!", 5000);
                return false;
            }

            if (RaffleID == '' || RaffleID == undefined || RaffleID == null) {
                showToast("error", "The Raffle ID Missing. Kindly Refresh and Try Again!", 5000);
                return false;
            }

            if (ticketReferenceID == '' || ticketReferenceID == undefined || ticketReferenceID == null) {
                showToast("error", "The Ticket Reference ID Missing. Kindly Refresh and Try Again!", 5000);
                return false;
            }

            if (ticketID == '' || ticketID == undefined || ticketID == null) {
                showToast("error", "The Ticket ID Missing. Kindly Refresh and Try Again!", 5000);
                return false;
            }

            let announceWinnerBtn = $(`#${btnID}`);
            const requestData = {
                method: 'resendWinner',
                drawID: id,
                RaffleID: RaffleID,
                ticketReferenceID: ticketReferenceID,
                ticketID: ticketID
            };

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, send it!",
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {

                    // Swal.fire({
                    //     title: "Deleted!",
                    //     text: "Your file has been deleted.",
                    //     icon: "success"
                    // });

                    $.ajax({
                        url: origin + '/ajax/service/drawManagerServices.php',
                        type: 'POST',
                        data: requestData,
                        beforeSend: function() {
                            // Button Loading
                            announceWinnerBtn.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Loading...`).prop('disabled', true);
                        },
                        success: function(data) {
                            // console.log('Request successful');

                            var response = JSON.parse(data);

                            if (response != "") {
                                if (response.type == '1') {
                                    // $(`#WinnerPreview`).modal('hide');
                                    if (response.result != '' && response.result != null && response.result != undefined) {
                                        showToast("success", response.result, 5000, searchTicket);
                                    }
                                    // Loading Off 
                                    // announceWinnerBtn.html(`Resend`).prop('disabled', true).hide();
                                } else {
                                    if (response.result != '' && response.result != null && response.result != undefined) {
                                        showToast("error", response.result, 5000);
                                    }

                                }
                            } else {
                                showToast("error", "Request failed", 5000);

                            }
                            // Loading Off 
                            announceWinnerBtn.html(`Resend`).prop('disabled', false);
                        },
                        error: function(xhr, status, error) {
                            showToast("error", "Request failed", 5000);
                            announceWinnerBtn.html(`Resend`).prop('disabled', false);
                            console.error('Request failed');
                            console.error(xhr, status, error);
                        }
                    });

                } else {
                    // $(`#WinnerPreview`).modal('hide');
                }
            });




        } catch (e) {
            console.log('Error: ' + e.message);
        }
    }
</script>