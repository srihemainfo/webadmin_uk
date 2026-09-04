<?php
   $loginUserID = $_SESSION['memid'];
   $getUSer = mysqli_query($con, "SELECT u.*, r.name AS 'roleName' FROM `user_register` AS u 
   LEFT JOIN role AS r ON r.id = u.roll_id
   WHERE u.`id`='$loginUserID' and u.`deletes`='0'  ORDER BY u.`id` ASC LIMIT 1;");
   if (mysqli_num_rows($getUSer) > 0) {
       $rows = mysqli_fetch_all($getUSer, MYSQLI_ASSOC);
       $roll_name = $rows[0]['roleName'];
   }
   
// Fetch sign-in/sign-up data for the last 7 days
$start_date = date('Y-m-d', strtotime('-6 days')); // Get the date 7 days ago
$end_date = date('Y-m-d'); // Today's date
$query = "SELECT DATE(created_at) AS sign_up_date, DATE(lastlogin) AS sign_in_date,
            COUNT(*) AS sign_up_count, 
            SUM(CASE WHEN lastlogin IS NOT NULL THEN 1 ELSE 0 END) AS sign_in_count
          FROM user_register
          WHERE DATE(created_at) BETWEEN '$start_date' AND '$end_date'
          GROUP BY DATE(created_at)";
$result = mysqli_query($con, $query);

// Initialize arrays to store dates and counts
$dates = array();
$sign_up_counts = array();
$sign_in_counts = array();

// Fetch the data and populate the arrays
while ($row = mysqli_fetch_assoc($result)) {
    // Use sign_up_date as the key to ensure all dates are included, even if there are no sign-ups on a specific day
    $dates[$row['sign_up_date']] = $row['sign_up_date'];
    $sign_up_counts[$row['sign_up_date']] = $row['sign_up_count'];
    $sign_in_counts[$row['sign_up_date']] = $row['sign_in_count'];
}

// Fill in missing dates with zero counts
$current_date = $start_date;
while ($current_date <= $end_date) {
    if (!isset($dates[$current_date])) {
        $dates[$current_date] = $current_date;
        $sign_up_counts[$current_date] = 0;
        $sign_in_counts[$current_date] = 0;
    }
    $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
}

// Sort dates in ascending order
ksort($dates);

// Convert arrays to JSON for JavaScript
$dates_json = json_encode(array_values($dates));
$sign_up_counts_json = json_encode(array_values($sign_up_counts));
$sign_in_counts_json = json_encode(array_values($sign_in_counts));
   ?>
   <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ucwords($roll_name); ?> Dashboard</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
   .chart-container {
   position: relative;
   height: 100vh;
   overflow: hidden
   }
   .aed-agent {
   text-align: center
   }
   </style>
</head>
<body>
    <!--app-content open-->
    <div class="main-content app-content mt-0">
        <div class="side-app">
            <input type="hidden" id="tabID" value="agents">
            <!-- CONTAINER -->
            <div class="main-container container-fluid">
                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <h1 class="page-title"><?= ucwords($roll_name); ?> Dashboard</h1>
                    <div>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page"><?= ucwords($roll_name); ?> Dashboard</li>
                        </ol>
                    </div>
                </div>
                <!-- PAGE-HEADER END -->
                <!-- ROW-1 -->
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                        <div class="row" id="collectdiv">
                            <!-- Chart container -->
                            <div class="chart-container">
                                <canvas id="signChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ROW-1 END -->
            </div>
            <!-- CONTAINER END -->
        </div>
    </div>
    <!--app-content close-->
    <script>
        // Data for the sign-in/sign-up chart
        var dates = <?= $dates_json; ?>;
        var sign_up_counts = <?= $sign_up_counts_json; ?>;
        var sign_in_counts = <?= $sign_in_counts_json; ?>;

        // Create the chart
        var ctx = document.getElementById('signChart').getContext('2d');
        var signChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Sign-ups',
                    data: sign_up_counts,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)', // Red color for sign-ups
                    borderColor: 'rgba(255, 99, 132, 1)', // Red color for sign-ups
                    borderWidth: 1
                }, {
                    label: 'Sign-ins',
                    data: sign_in_counts,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', // Blue color for sign-ins
                    borderColor: 'rgba(54, 162, 235, 1)', // Blue color for sign-ins
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
    <form action="paymentstatushistory" id="ccGet" method="post">
   <input type="hidden" name="ccAvenue" value="notGenerated">
   <!-- <input type="submit"> -->
</form>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
<!--app-content close-->
<script>
   $(function() {
       collect_earning();
       <?php if ($roll_id == 1 || $roll_id == 2) { ?>
           setInterval(function() {
               collect_earning();
           }, runtime);
       <?php } ?>
   });
   
   // Update the function to render the chart
// Define a function to render the chart
function renderChart() {
    var ctx = document.getElementById('signChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'],
            datasets: [{
                label: 'Sign-ins',
                data: [12, 19, 3, 5, 2, 3, 11],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Call the renderChart function whenever needed
renderChart();


   
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
   
       do_ajax_call(post_data, onsuccess, origin + "/ajax/service/transaction_services111111.php");
   
   }


</script>
