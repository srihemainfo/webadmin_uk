<?php
session_start();
// include 'transaction_services111111.php';
$dates = [];
$signups = [];
$logins = [];

// Fetch dates for the last 7 days
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dates[] = $date;
    $signups[$date] = 0; // Initialize signups for each day to 0
    $logins[$date] = 0;  // Initialize logins for each day to 0
    $loginsOTP[$date] = 0;  // Initialize logins for each day to 0
}

// Fetch data for signups for the last 7 days
$signupQuery = "SELECT DATE(created_at) AS date, COUNT(*) AS count FROM user_register WHERE created_at >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY date";
$signupResult = mysqli_query($con, $signupQuery);

// Fetch data for logins for the last 7 days
// $loginQuery = "SELECT DATE(lastlogin) AS date, COUNT(*) AS count FROM user_register WHERE lastlogin >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY date";


// $loginQuery = "SELECT date, COUNT(*) AS count FROM ( SELECT userid, COUNT(*) AS count, DATE(createdon) AS date FROM login_logs WHERE createdon >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY userid, DATE(createdon) ) AS subquery GROUP BY date;";
// $loginResult = mysqli_query($con, $loginQuery);

$loginQuery = "SELECT date, COUNT(*) AS count FROM ( SELECT userid, COUNT(*) AS count, DATE(createdon) AS date FROM login_logs WHERE method = 'loginWithPassword' AND createdon >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY userid, DATE(createdon) ) AS subquery GROUP BY date;";
$loginResult = mysqli_query($con, $loginQuery);




$loginQueryOtp = "SELECT date, COUNT(*) AS count FROM ( SELECT userid, COUNT(*) AS count, DATE(createdon) AS date FROM login_logs WHERE method = 'loginOTPverify' AND createdon >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY userid, DATE(createdon) ) AS subquery GROUP BY date;";
$loginResultOTP = mysqli_query($con, $loginQueryOtp);




// Merge data from signups query
while ($row = mysqli_fetch_assoc($signupResult)) {
    $date = $row['date'];
    $signups[$date] = $row['count'];
}

// Merge data from logins query
while ($row = mysqli_fetch_assoc($loginResult)) {
    $date = $row['date'];
    $logins[$date] = $row['count'];
}


while ($row = mysqli_fetch_assoc($loginResultOTP)) {
    $date = $row['date'];
    $loginsOTP[$date] = $row['count'];
}



// var_dump($data); // For debugging
// Return the data array to be used in Chart.js
// echo json_encode($data);






$dates = [];
$grandTotalSales = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dates[] = $date;
    $grandTotalSales[$date] = 0;
}

$query = "SELECT DATE(createdon) AS date, SUM(grandtotal) AS total_sales FROM invoice WHERE createdon >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY date";
$result = mysqli_query($con, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['date'];
    $grandTotalSales[$date] = $row['total_sales'];
}


$hours = [];
$hourlySales = [];
$query = "SELECT 
    hours.hour,
    COALESCE(SUM(i.grandtotal), 0) AS total_amount
FROM (
    SELECT 0 AS hour UNION ALL
    SELECT 1 UNION ALL
    SELECT 2 UNION ALL
    SELECT 3 UNION ALL
    SELECT 4 UNION ALL
    SELECT 5 UNION ALL
    SELECT 6 UNION ALL
    SELECT 7 UNION ALL
    SELECT 8 UNION ALL
    SELECT 9 UNION ALL
    SELECT 10 UNION ALL
    SELECT 11 UNION ALL
    SELECT 12 UNION ALL
    SELECT 13 UNION ALL
    SELECT 14 UNION ALL
    SELECT 15 UNION ALL
    SELECT 16 UNION ALL
    SELECT 17 UNION ALL
    SELECT 18 UNION ALL
    SELECT 19 UNION ALL
    SELECT 20 UNION ALL
    SELECT 21 UNION ALL
    SELECT 22 UNION ALL
    SELECT 23
) AS hours
LEFT JOIN (
    SELECT HOUR(createdon) AS hour, SUM(grandtotal) AS grandtotal
    FROM invoice
    WHERE DATE(createdon) = CURDATE()
    GROUP BY HOUR(createdon)
) AS i ON hours.hour = i.hour
GROUP BY hours.hour;
";
$result = mysqli_query($con, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $hours[] = $row['hour'];
    $hourlySales[] = $row['total_amount'];
}






?>

<style>
    .chart-container {
        position: relative;
        height: 200px;
        overflow: hidden
    }

    .aed-agent {
        text-align: center
    }

    .card {
        margin-bottom: 20px;
        /* Add margin to create space between cards */
    }
</style>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <input type="hidden" id="tabID" value="agents">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            <?php if ($roll_id != '11') { ?>
                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <h1 class="page-title"><?= ucwords($roll_name); ?> Dashboard</h1>
                    <div>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page"><?= ucwords($roll_name); ?> Dashboard</li>
                        </ol>
                    </div>
                </div>
            <?php } ?>
            <!-- PAGE-HEADER END -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                    <div class="row" id="collectdiv">
                        <!-- Content fetched dynamically will be placed here -->
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xl-6">

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Sign-up & Sign-in (Last 7 Days)</h4>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="signupLoginChart"></canvas>
                                Canvas for the bar graph
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 col-xl-6">

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Weekly Sales</h4>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="saleschart"></canvas>
                                Canvas for the bar graph
                            </div>
                        </div>
                    </div>
                </div>

                <!--  =================================tesfsd=====================-->


                <div class="col-lg-6 col-md-6 col-sm-12 col-xl-6">
                    <div class="card">
                        <div class="card-header  justify-content-between align-items-center">
                            <div class="row">
                                <div class="col-md-12 d-flex align-items-center justify-content-between">
                                    <h4 class="card-title">Hourly Sales</h4>
                                    <div class="card-title1 mt-22 fs-5">Today Sales(24 Hours)</div>
                                </div>

                                <div class="col-lg-6 col-sm-6 col-md-6 mb-2">
                                    <span>Select Domain</span> &nbsp; <span style="color:red;"></span>
                                    <select id="domainName" onclick="fetchHourlySales()" class="form-select">
                                        <option value="" selected="">All Domain</option>
                                        <option value="nationaldraw.me">Nationaldraw.me (Only CCAvenue)</option>
                                        <option value="nationaldrawuae.com">Nationaldrawuae.com (Only CCAvenue)</option>
                                        <option value="nd360raffle.com">Nd360raffle.com (Only CCAvenue)</option>
                                    </select>
                                </div>


                                <!--<div>-->
                                <!--<span>Select Date</span> &nbsp; <span style="color:red;"></span>-->
                                <div class="col-lg-6 col-sm-6 col-md-6 mb-2">
                                    <!--<input type="date" name="datefrom" id="datefilter" value="<?php echo date('Y-m-d'); ?>" class="form-select" max="<?php echo date("Y-m-d"); ?>">-->
<br>

                                    <input type="date" name="datefrom" id="datefilter" onchange="fetchHourlySales()" value="<?php echo date('Y-m-d'); ?>" class="form-control" min="2024-05-05" max="<?php echo date("Y-m-d"); ?>">

                                </div>
                            </div>
                            <!--</div>-->
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="salescharthour"></canvas>
                            </div>
                        </div>
                    </div>
                </div>




                <!-- ROW-1 END -->


            </div>
            <!-- CONTAINER END -->
        </div>
    </div>
</div>


<!-- <form action="paymentstatushistory" id="ccGet" method="post">
            <input type="hidden" name="ccAvenue" value="notGenerated">
        </form> -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0"></script> -->

<script>
    var origin = window.location.origin;
    var myChartHourlySales;

    var url = origin + "/ajax/service/dashBoard_Services.php";

    function fetchHourlySales() {
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                selectedDate: $('#datefilter').val(),
                method: 'hour_chart',
                domainName: $(`#domainName`).val()
            },
            success: function(response) {
                var data = JSON.parse(response);
                updateChart(data);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching hourly sales data:', error);
            }
        });
    }

    function updateChart(data) {
        var labelsWithTime = data.hours.map(function(label) {
            var hour = parseInt(label);
            if (hour === 0) {
                return "12 am";
            } else if (hour < 12) {
                return hour + " am";
            } else if (hour === 12) {
                return "12 pm";
            } else {
                return (hour - 12) + " pm";
            }
        });

        myChartHourlySales.data.labels = labelsWithTime;
        myChartHourlySales.data.datasets[0].data = data.hourlySales;
        myChartHourlySales.update();

        $('.card-title1').text(' AED ' + data.totalAED);
    }


    var ctx = document.getElementById('signupLoginChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_values($dates)); ?>,
            datasets: [{
                    label: 'Sign-up',
                    data: <?php echo json_encode(array_values($signups)); ?>,
                    backgroundColor: '#F7418F',
                    borderColor: '#F7418F',
                    borderWidth: 1
                },
                {
                    label: 'Sign-in (With Password)',
                    data: <?php echo json_encode(array_values($logins)); ?>,
                    backgroundColor: '#41C9E2',
                    borderColor: '#41C9E2',
                    borderWidth: 1
                },
                {
                    label: 'Sign-in (With OTP)',
                    data: <?php echo json_encode(array_values($loginsOTP)); ?>,
                    backgroundColor: '#42e351',
                    borderColor: '#42e351',
                    borderWidth: 1
                }
            ]
        },
        options: {
            plugins: {
                legend: {
                    display: true
                }
            },

            scales: {
                x: {
                    offset: true,
                    grid: {
                        display: false
                    }
                },
                y: {
                    display: true
                }
            },
            title: {
                display: true,
                text: 'Gradient Donut with custom Start-angle'
            },
            responsive: true,
            maintainAspectRatio: false,
        }
    });



    var ctx = document.getElementById('saleschart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_values($dates)); ?>,
            datasets: [{
                label: '<?php echo $dates[0] ?> Sales', // Assuming $dates[0] is the desired label
                data: <?php echo json_encode(array_values($grandTotalSales)); ?>,
                backgroundColor: '#21a549',
                borderColor: '#21a549',
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    offset: true,
                    grid: {
                        display: false
                    }
                },
                y: {
                    display: true
                }
            },
            title: {
                display: true,
                text: 'Gradient Donut with custom Start-angle'
            },
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    // ===========================================



    // ========**********************************************************************

    $(document).ready(function() {
        var ctxHourlySales = document.getElementById('salescharthour').getContext('2d');
      
        createChart();

        // $('#datefilter').val(new Date().toISOString().slice(0, 10));

        // $('#datefilter').change();

        // fetchHourlySales(selectedDate);
        // var today = new Date().toISOString().slice(0, 10);
        // $('#datefilter').val(today);

        // Fetch hourly sales data for today
        fetchHourlySales();

        // $('#datefilter').change(function() {
        //     var selectedDate = $(this).val();
        //     fetchHourlySales();
        // });

        function createChart() {
            myChartHourlySales = new Chart(ctxHourlySales, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Sales',
                        data: [],
                        backgroundColor: '#21a549',
                        borderColor: '#2431d8',
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false,
                        }
                    },
                    scales: {
                        x: {
                            offset: true,
                            grid: {
                                display: true
                            }
                        },
                        y: {
                            display: true
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        text: 'Hourly Sales (Last 24 Hours)'
                    }
                }
            });
        }


    });







    $(function() {
        collect_earning();
        <?php if ($roll_id == 1 || $roll_id == 2 || $roll_id == 3) { ?>
            setInterval(function() {
                collect_earning();
            }, runtime);
        <?php } ?>
    });



    function collect_earning() {

        var formdata = [];

        formdata.push({
            name: 'method',
            value: "collect_earning"

        });

        var post_data = formdata;

        var onsuccess = function(data) {

            var response = JSON.parse(data);

            if (response != "") {

                if (response.type == 1) {

                    document.getElementById('collectdiv').innerHTML = response.output;

                } else {

                    document.getElementById('collectdiv').innerHTML = response.result;

                }

            }

        }

        do_ajax_call(post_data, onsuccess, origin + "/ajax/service/dashBoard_Services.php");

    }
</script>