<?php
session_start();
include 'transaction_services111111.php';
   $loginUserID = $_SESSION['memid'];
   $getUSer = mysqli_query($con, "SELECT u.*, r.name AS 'roleName' FROM `user_register` AS u 
   LEFT JOIN role AS r ON r.id = u.roll_id
   WHERE u.`id`='$loginUserID' and u.`deletes`='0'  ORDER BY u.`id` ASC LIMIT 1;");
   if (mysqli_num_rows($getUSer) > 0) {
      $rows = mysqli_fetch_all($getUSer, MYSQLI_ASSOC);
      $roll_name = $rows[0]['roleName'];
  }
  
  // Fetch data for signups for the last 7 days
  $signupQuery = "SELECT DATE(created_at) AS date, COUNT(*) AS count FROM user_register WHERE created_at >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY date";
  $signupResult = mysqli_query($con, $signupQuery);
  
  // Fetch data for logins for the last 7 days
  $loginQuery = "SELECT DATE(lastlogin) AS date, COUNT(*) AS count FROM user_register WHERE lastlogin >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY date";
  $loginResult = mysqli_query($con, $loginQuery);
  
  // Initialize arrays to store data for Chart.js
  $dates = [];
  $signups = [];
  $logins = [];
  
  // Merge data from both queries
  while ($row = mysqli_fetch_assoc($signupResult)) {
      $dates[$row['date']] = $row['date']; // Store dates as keys to ensure uniqueness
      $signups[$row['date']] = $row['count'];
  }
  
  while ($row = mysqli_fetch_assoc($loginResult)) {
      $dates[$row['date']] = $row['date']; // Store dates as keys to ensure uniqueness
      $logins[$row['date']] = $row['count'];
  }
  ?>
  
  <!DOCTYPE html>
  <html lang="en">
  
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Dashboard</title>
      <style>
   .chart-container {
   position: relative;
   height: 400px;
   overflow: hidden
   }
   .aed-agent {
   text-align: center
   }
   </style>
</head>

<body>
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
         <!-- ROW-1 -->
         <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
               <div class="row" id="collectdiv">
               </div>
            </div>
         </div>
         <!-- ROW-1 END -->
         <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                        <!-- Bar Graph Section -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Signup & Sign-in (Last 7 Days)</h4>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="signupLoginChart"></canvas> <!-- Canvas for the bar graph -->
                                </div>
                            </div>
                        </div>
                    </div>
         </div>
      </div>
      <!-- CONTAINER END -->
   </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0"></script>
    <script>
        // JavaScript to render the bar graph using Chart.js
        var ctx = document.getElementById('signupLoginChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_values($dates)); ?>, // Dates for X-axis
                datasets: [{
                        label: 'Signup',
                        data: <?php echo json_encode(array_values($signups)); ?>, // Signups count for Y-axis
                        backgroundColor: '#067938', // Color for signups
                        borderColor: '#067938', // Border color for signups
                        borderWidth: 1
                    },
                    {
                        label: 'Sign-in',
                        data: <?php echo json_encode(array_values($logins)); ?>, // Logins count for Y-axis
                        backgroundColor: '#162254', // Color for logins
                        borderColor: '#162254', // Border color for logins
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true // Start Y-axis from zero
                    }
                }
            }
        });
    </script>
<form action="paymentstatushistory" id="ccGet" method="post">
   <input type="hidden" name="ccAvenue" value="notGenerated">
   </form>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0"></script> 
</body>
 <script>
       
   $(function() {
       collect_earning();
       <?php if ($roll_id == 1 || $roll_id == 2) { ?>
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
   
       do_ajax_call(post_data, onsuccess, origin + "/ajax/service/transaction_services111111.php");
   
   }


</script>
</html>