<?php
$pageTitle = ucwords(strtolower('Active Ticket Report'));
?>


<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.css' rel='stylesheet' />
<!-- <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script> -->
<!-- <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js'></script> -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js'></script>


<style>
    #calendar {
        /* max-width: 900px; */
        margin: 40px auto;
        width: 100%;
    }

    .fc-toolbar .fc-right {
        float: right;
        visibility: hidden;
    }

    .fc-content {
        color: #fff;
    }

    .fc-icon {
        height: 2em;
    }

    .fc-content {
        font-size: 15px;
        /*background: #129045;*/
        text-align: center;
        font-weight: 600;
    }

    .fc-unthemed td.fc-backgroundColor {
        background: #b6b6c9;
    }

    .fc-unthemed td.fc-today {
        background: #b6b6c9;
    }

    .orange-event {
        color: orange;
    }

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

        //   alert(n);
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

        </div>
    </div>
</div>
<div id="loading" style="display:none;">
    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...
</div>






<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendar = $('#calendar');

        var today = moment().format('YYYY-MM-DD');

        calendar.fullCalendar({
            // themeSystem: 'bootstrap',
            // initialView: 'listWeek',
            // aspectRatio: 1.5,
            height: 'auto',
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listWeek'
            },
            defaultDate: today,
            editable: false,
            eventLimit: false,
            eventOrder: false,
            events: [], // Initialize events as an empty array
            // handleWindowResize: true,
            // aspectRatio: 1.5,
            // initialView: 'dayGridMonth',
            // windowResize: function(view) {
            //     if (window.innerWidth < 768) {
            //         calendar.changeView('timeGridDay');
            //     } else {
            //         calendar.changeView('timeGridWeek');
            //     }
            // }
        });



        // Function to handle window resize
        // function handleWindowResize() {
        //     if (window.innerWidth < 768) {
        //         calendar.changeView('timeGridDay');
        //     } else {
        //         calendar.changeView('timeGridWeek');
        //     }
        // }

        // // Initial call to set the view based on window width
        // handleWindowResize();

        // // Listen for window resize event
        // window.addEventListener('resize', handleWindowResize);


        var h = new FormData();
        h.append('method', 'seles_report');
        h.append('getAll', true);

        // var loading = $('#loading');

        //  $('#loading').show();

        //  var spinner = $('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Loading...');
        // $('body').append(spinner);
        $.ajax({
            type: 'POST',
            url: origin + '/ajax/service/salesreportServices.php',
            data: h,
            beforeSend: function() {
                loading.show();
            },
            beforeSend: function() {

                // Button Loading

                //  btn.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Loading...`).prop('disabled', true);

            },
            success: function(response) {
                var response = JSON.parse(response);
                if (response && response.result && Array.isArray(response.result)) {
                    var events = [];

                    response.result.forEach(element => {
                        var date = moment(element.resultDate).format('YYYY-MM-DD');

                        let goldprice = 0;

                        if (element.dailyThirllStatus !== 'Pending') {
                            let daily = element.dailyGold;
                            goldprice += daily;
                        }

                        if (element.dailyThirllStatus !== 'Pending') {
                            var weeklyBgEvent = {
                                id: element.id,

                                title: 'Sale : ' + element.dailyactiveCount,
                                start: date + 'T00:00:00',
                                end: date + 'T23:59:59',
                                allDay: true,
                                backgroundColor: '#11c3b5'

                            };
                            events.push(weeklyBgEvent);
                        }

                        if (element.dailyThirllStatus !== 'Pending') {
                            var price1 = {
                                id: element.id,

                                title: 'Gold Grams : ' + element.Total_price,
                                start: date + 'T00:00:00',
                                end: date + 'T23:59:59',
                                allDay: true,
                                backgroundColor: '#ffaa00'
                            };
                            events.push(price1);
                        }
                        if (element.dailyThirllStatus !== 'Pending') {
                            var price2 = {
                                id: element.id,

                                title: 'Gold Prize : ' + element.Total_rete,
                                start: date + 'T00:00:00',
                                end: date + 'T23:59:59',
                                allDay: true,
                                backgroundColor: '#003366'
                            };
                            events.push(price2);
                        }
                        if (element.dailyThirllStatus !== 'Pending') {
                            //   alert('king hellow');

                            if (element.value > 0) {
                                //  alert('king');
                                backgroundColor1 = '#006600'; // Change color to orange if value is 'lose'
                            } else {
                                backgroundColor1 = '#fb1606';
                            }
                            var price3 = {
                                id: element.id,

                                title: 'Amount :  ' + element.value,
                                start: date + 'T00:00:00',
                                end: date + 'T23:59:59',
                                allDay: true,
                                backgroundColor: backgroundColor1
                            };
                            events.push(price3);
                        }




                    });

                    console.log('Events:', events);
                    calendar.fullCalendar('addEventSource', events);
                } else {
                    console.error('Invalid response format:', response);
                }
            },

            complete: function() {
                // Remove loading spinner
                // $('#loading').hide();
                // spinner.remove();
            },
            processData: false,
            contentType: false,
            error: function(error) {
                console.error('Error fetching events:', error);
                // $('#loading').hide();
            }
        });

    });
</script>