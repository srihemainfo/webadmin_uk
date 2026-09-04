<style>
    .chart-container {



        position: relative;



        height: 100vh;



        overflow: hidden;



    }



    .aed-agent {

        text-align: center;



    }
</style>



<!--app-content open-->
<div class="main-content app-content mt-0">
    <div class="side-app">
        <!-- <input type="hidden" id="tabID" value="agents"> -->
        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1 class="page-title">Weekly Comparison (Ticket Sales)</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Weekly Comparison (Ticket Sales)</li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->
            <!-- ROW-1 -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                    <div class="row" id="collectdiv">
                    </div>
                </div>
            </div>
            <!-- ROW-1 END -->



        </div>

        <div class="row">
            <div class="col-12 mt-5 ">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 ">
                                <span>Select Date (Week 1)</span> &nbsp; <span style="color:red;">*</span>
                                <div id="firstWeek" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>
                            </div>

                            <div class="col-6">
                                <span>Select Date (Week 2)</span> &nbsp; <span style="color:red;">*</span>
                                <div id="nextWeek" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 mt-4">
                            <button class="btn btn-info" onclick="loadDetails()" id="goButton">Go</button>
                            <!-- <div id="loader" class="mt-5" style="display: none;">Loading...</div> -->

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">

                                <div id="weeklyReportChart" class="chart-container"></div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>




<script>
    $(function() {

        createDatePricket('firstWeek');
        createDatePricket('nextWeek');
        loadDetails();
    });

    function createDatePricket(id) {
        try {
            var start = moment();

            var end = moment();



            function cb(start, end) {

                $('#' + id + ' span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));

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

                timePicker: true,
                timePicker24Hour: true,
                timePickerSeconds: true,
                maxSpan: {
                    days: 366
                },
                autoUpdateInput: true,

                // minYear: moment().format("YYYY"),
                // maxYear: moment().add(1, 'years').format("YYYY"),
                maxDate: moment().add(1, 'days').toDate(),

                ranges: {

                    'Today': [moment().set({
                        hour: 0,
                        minute: 0,
                        second: 0,
                        millisecond: 0
                    }), moment().set({
                        hour: 23,
                        minute: 59,
                        second: 59,
                        millisecond: 59
                    })],

                    'Yesterday': [moment().subtract(1, 'days').set({
                        hour: 0,
                        minute: 0,
                        second: 0,
                        millisecond: 0
                    }), moment().subtract(1, 'days').set({
                        hour: 23,
                        minute: 59,
                        second: 59,
                        millisecond: 59
                    })],

                    'Last 7 Days': [moment().subtract(6, 'days').set({
                        hour: 0,
                        minute: 0,
                        second: 0,
                        millisecond: 0
                    }), moment().set({
                        hour: 23,
                        minute: 59,
                        second: 59,
                        millisecond: 59
                    })],

                    'Last 30 Days': [moment().subtract(29, 'days').set({
                        hour: 0,
                        minute: 0,
                        second: 0,
                        millisecond: 0
                    }), moment().set({
                        hour: 23,
                        minute: 59,
                        second: 59,
                        millisecond: 59
                    })],

                    'This Month': [moment().startOf('month').set({
                        hour: 0,
                        minute: 0,
                        second: 0,
                        millisecond: 0
                    }), moment().endOf('month').set({
                        hour: 23,
                        minute: 59,
                        second: 59,
                        millisecond: 59
                    })],

                    'Last Month': [moment().subtract(1, 'month').startOf('month').set({
                        hour: 0,
                        minute: 0,
                        second: 0,
                        millisecond: 0
                    }), moment().subtract(1, 'month').endOf('month').set({
                        hour: 23,
                        minute: 59,
                        second: 59,
                        millisecond: 59
                    })],

                    'This Year': [moment().startOf('year').set({
                        hour: 0,
                        minute: 0,
                        second: 0,
                        millisecond: 0
                    }), moment().endOf('year').set({
                        hour: 23,
                        minute: 59,
                        second: 59,
                        millisecond: 59
                    })],

                    'Last Year': [moment().subtract(1, 'year').startOf('year').set({
                        hour: 0,
                        minute: 0,
                        second: 0,
                        millisecond: 0
                    }), moment().subtract(1, 'year').endOf('year').set({
                        hour: 23,
                        minute: 59,
                        second: 59,
                        millisecond: 59
                    })]

                }

            }, cb);

            cb(start, end);
        } catch (e) {
            console.log('Error: ' + e.message);
        }
    }

    function toast(icon, message) {
        try {
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
        } catch (e) {
            console.log('Error: ' + e.message);
        }
    }

    function loadDetails() {
        try {
            var dom = document.getElementById('weeklyReportChart');
            var myChart = echarts.init(dom, null, {
                renderer: 'canvas',
                useDirtyRect: false
            });

            var firstWeekStart = moment($('#firstWeek').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
            var firstWeekEnd = moment($('#firstWeek').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");

            var nextWeekStart = moment($('#nextWeek').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
            var nextWeekEnd = moment($('#nextWeek').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");

            if (firstWeekStart == '' || firstWeekEnd == '') {
                toast('error', 'Kindly select the date');
                return false;
            }

            if (nextWeekStart == '' || nextWeekEnd == '') {
                toast('error', 'Kindly select the date');
                return false;
            }

            var h = [];
            h.push({
                name: 'method',
                value: "loadDetailsWeeklyData"
            }, {
                name: 'firstWeekStart',
                value: firstWeekStart
            }, {
                name: 'firstWeekEnd',
                value: firstWeekEnd
            }, {
                name: 'nextWeekStart',
                value: nextWeekStart
            }, {
                name: 'nextWeekEnd',
                value: nextWeekEnd
            });


            var post_data = h;
            var onsuccess = function(data) {
                var response = JSON.parse(data);
                if (response != "") {



                    if (response.type == 1) {



                        var firstJson = Object.entries(response.firstWeekData).map(function([key, value]) {
                            return {
                                value: parseInt(value),
                                name: key
                            };
                        });

                        var nextJson = Object.entries(response.nextWeekData).map(function([key, value]) {
                            return {
                                value: parseInt(value),
                                name: key
                            };
                        });


                        var option = {
                            title: {
                                text: 'Weekly Comparison Report',
                                subtext: 'Ticket Sales',
                                left: 'center'
                            },
                            tooltip: {
                                trigger: 'item',
                                formatter: '{a} <br/>{b} : {c} ({d}%)'
                            },
                            legend: {
                                left: 'center',
                                top: 'bottom',

                                data: response.productList
                            },
                            toolbox: {
                                show: true,
                                feature: {
                                    mark: {
                                        show: true
                                    },
                                    dataView: {
                                        show: true,
                                        readOnly: false
                                    },
                                    restore: {
                                        show: true
                                    },
                                    saveAsImage: {
                                        show: true
                                    }
                                }
                            },
                            series: [{
                                    name: 'Week 1',
                                    // name: 'Area Mode',
                                    type: 'pie',
                                    radius: [20, 140],
                                    center: ['25%', '50%'],
                                    roseType: 'radius',
                                    // roseType: 'area',
                                    itemStyle: {
                                        borderRadius: 5
                                    },
                                    label: {
                                        show: true
                                    },
                                    emphasis: {
                                        label: {
                                            show: true
                                        }
                                    },


                                    data: firstJson
                                },
                                {
                                    name: 'Week 2',
                                    type: 'pie',
                                    radius: [20, 140],
                                    center: ['75%', '50%'],
                                    roseType: 'area',
                                    itemStyle: {
                                        borderRadius: 5
                                    },

                                    data: nextJson
                                }
                            ]
                        };
                        if (option && typeof option === 'object') {
                            myChart.setOption(option);
                        }
                    } else {
                        toast('error', response.result);
                    }




                }
            }
            do_ajax_call(post_data, onsuccess, origin + "/ajax/service/graph_services.php");
        } catch (e) {
            console.log('Error: ' + e.message);
        }
    }
</script>