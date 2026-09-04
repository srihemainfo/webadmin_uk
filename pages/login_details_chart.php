<?php

//1.modifications unknown//


//Date      Developer_name      Modifications//

$page_name = 'Login Via Password';
$page_name1 = 'Login Via OTP';


?>

<style>
    .chart-container {

        position: relative;

        height: 100vh;

        overflow: hidden;

    }

    .aed-agent {
        text-align: center;

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
    span.apexcharts-legend-text {
    font-size: 15px!important;
    /* padding: 0px 0; */
}
.apexcharts-toolbar {
    position: absolute;
    z-index: 0;
}
</style>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="main-content app-content mt-0">

    <div class="side-app">

        <input type="hidden" id="tabID" value="agents">



        <div class="main-container container-fluid">





            <div class="page-header">

                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Customer Login Analysis</h1>

                <div>

                    <ol class="breadcrumb">

                        <!-- <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li> -->

                        <li class="breadcrumb-item active" aria-current="page">Customer Login Analysis</li>

                    </ol>

                </div>

            </div>

            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">

                    <div class="row" id="collectdiv">
                        <div class="col-lg-12 col-md-6 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="card-title"> <?= ucwords($page_name); ?></h4>

                                    </div>
                                    <div id="chart">
                                        <div id="oneweekuserinout"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">

                    <div class="row" id="collectdiv">
                        <div class="col-lg-12 col-md-6 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="card-title"> <?= ucwords($page_name1); ?></h4>

                                    </div>
                                    <div id="chart">
                                        <div id="oneweekuserinout1"></div>
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

<!-- <script src="https://fastly.jsdelivr.net/npm/echarts@5.4.0/dist/echarts.min.js"></script> -->



<script>
    //   var origin = window.location.origin;

    //   var url = origin + "/ajax/service/transaction_services.php";

    //   var graph_services = origin + "/ajax/service/overall_services.php";

    $(function() {
        last_7_sign_Chart();
        last_7_Otp_Chart();

        setInterval(function() {
            if ($("#autoRefresh").prop("checked")) {

                last_7_sign_Chart();
                last_7_Otp_Chart();
            }
        }, runtime);

    });



    function last_7_sign_Chart() {

        var formdata = [];

        formdata.push({
            name: 'method',
            value: "loginDetails"

        });

        var post_data = formdata;

        var onsuccess = function(data) {

            var response = JSON.parse(data);

            if (response != "") {

                if (response.type == 1) {




                    if (oneweekuserinout != '') {
                        if (oneweekuserinout.ohYeahThisChartHasBeenRendered && oneweekuserinout.ohYeahThisChartHasBeenRendered != undefined) {
                            oneweekuserinout.destroy();
                        }
                    }


                    var options = {
                        series: [{
                                name: 'Login Via Mobile Number',
                                data: response.result.Signin
                            }, {
                                name: 'Login Via Email ID',
                                data: response.result.Signup
                            },

                        ],
                        chart: {
                            type: 'bar',
                            width: '100%',
                            height: 190
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '55%',
                                endingShape: 'rounded'
                            },
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ['transparent']
                        },
                        xaxis: {
                            categories: response.result.days,
                        },
                        yaxis: {
                            title: {
                                text: 'Counts'
                            }
                        },
                        fill: {
                            opacity: 1
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return "# " + val + " Counts"
                                }
                            }
                        }
                    };

                    oneweekuserinout = new ApexCharts(document.querySelector("#oneweekuserinout"), options);
                    oneweekuserinout.render();



                } else {

                    $('#oneweekuserinout').html(response.result);



                }

            }

        }

        do_ajax_call(post_data, onsuccess, origin + "/ajax/service/overall_services.php");

    }

    function last_7_Otp_Chart() {

        var formdata = [];

        formdata.push({
            name: 'method',
            value: "loginotpDetails"

        });

        var post_data = formdata;

        var onsuccess = function(data) {

            var response = JSON.parse(data);

            if (response != "") {

                if (response.type == 1) {




                    if (oneweekuserinout != '') {
                        if (oneweekuserinout.ohYeahThisChartHasBeenRendered && oneweekuserinout.ohYeahThisChartHasBeenRendered != undefined) {
                            oneweekuserinout.destroy();
                        }
                    }


                    var options = {
                        series: [{
                                name: 'Login Via SMS OTP',
                                data: response.result.Signin
                            }, {
                                name: 'Login Via Email OTP',
                                data: response.result.Signup
                            },

                        ],
                        chart: {
                            type: 'bar',
                            width: '100%',
                            height: 190
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '55%',
                                endingShape: 'rounded'
                            },
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ['transparent']
                        },
                        xaxis: {
                            categories: response.result.days,
                        },
                        yaxis: {
                            title: {
                                text: 'Counts'
                            }
                        },
                        fill: {
                            opacity: 1
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return "# " + val + " Counts"
                                }
                            }
                        }
                    };

                    oneweekuserinout = new ApexCharts(document.querySelector("#oneweekuserinout1"), options);
                    oneweekuserinout.render();



                } else {

                    $('#oneweekuserinout1').html(response.result);



                }

            }

        }

        do_ajax_call(post_data, onsuccess, origin + "/ajax/service/overall_services.php");

    }
</script>