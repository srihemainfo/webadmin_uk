<?php

/**
 * Date             Developer                  Modification
 * 
 */
?>

<link href="<?= $adminurl . 'assets/css/style1.css' ?>" rel="stylesheet" />


<?php

/// LIVE
// $con = mysqli_connect("localhost", "nationaldraw_crmad", "dRkvNb*xW6mt", "nationaldraw_crmad");


$roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC ", "roll_id", "");
$roll_name = select_top_name($con, "role", "name", "`id`='$roll_id'", "name", "");
$n_roll_id = intval($roll_id) + 1;
$below_agent_name = select_top_name($con, "role", "name", "`id`='$n_roll_id'", "name", "");

$draw = select_query($con, "draw", "", "`deletes`='0' and `status`='Active' and `ticket_start_datetime`<'$dubaidate_time' and `ticket_end_datetime`>'$dubaidate_time'  ORDER BY `id` ASC LIMIT 1", "", "");
$drawID = $draw['result'][0]['id'];
$wrfd = date('Y-m-01');
$wrtd = date('Y-m-t');

$customer_active_withdraw_request = select_query($con, "withdraw_request", "", "`status` = '0' AND  `createdon` BETWEEN '$wrfd 00:00:00' AND '$wrtd 23:59:59' AND `deletes`='0' ORDER BY `id` DESC", "", "");
$getAgentReq = mysqli_query($con, "SELECT user_register.roll_id FROM `withdraw_request` JOIN user_register ON user_register.id = withdraw_request.from_id WHERE (user_register.roll_id = 3 OR user_register.roll_id = 4 OR user_register.roll_id = 5) AND withdraw_request.status = '0' AND  `createdon` BETWEEN '" . $wrfd . " 00:00:00' AND '" . $wrtd . " 23:59:59'");
$agent_active_withdraw_request = mysqli_num_rows($getAgentReq);


///// Completed ///////
$customer_active_withdraw_request_c = select_query($con, "withdraw_request", "", "`status` = '1' AND  `createdon` BETWEEN '$wrfd 00:00:00' AND '$wrtd 23:59:59' AND `deletes`='0' ORDER BY `id` DESC", "", "");
$getAgentReq_c = mysqli_query($con, "SELECT user_register.roll_id FROM `withdraw_request` JOIN user_register ON user_register.id = withdraw_request.from_id WHERE (user_register.roll_id = 3 OR user_register.roll_id = 4 OR user_register.roll_id = 5) AND withdraw_request.status = '1' AND  `createdon` BETWEEN '" . $wrfd . " 00:00:00' AND '" . $wrtd . " 23:59:59'");
$agent_active_withdraw_request_c = mysqli_num_rows($getAgentReq_c);


$totalRequest = $customer_active_withdraw_request['nr'] + $agent_active_withdraw_request;
$titlename = '';
$titlename1 = '';
if ($_SESSION['memid'] != 1) {
   $titlename = 'My ' . ucwords($below_agent_name);
   $titlename1 = 'My User';
} else {
   $titlename = 'All Agent List';
   $titlename1 = 'All User';
}

?>

<style>
   .widgets-icons-2 {
      width: 56px;
      height: 56px;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #ededed;
      font-size: 27px;
      border-radius: 10px
   }

   .rounded-circle {
      border-radius: 50% !important
   }

   .border-info {
      border-left: 5px solid #0dcaf0 !important
   }

   .border-danger {
      border-left: 5px solid #dc3545 !important
   }

   .border-success {
      border-left: 5px solid #28a745 !important
   }

   .border-warning {
      border-left: 5px solid #ffc107 !important
   }

   .bg-gradient-scooter {
      background: linear-gradient(45deg, #17ead9, #6078ea) !important
   }

   .bg-gradient-bloody {
      background: linear-gradient(45deg, #f54ea2, #ff7676) !important
   }

   .bg-gradient-ohhappiness {
      background: linear-gradient(45deg, #00b09b, #96c93d) !important
   }

   .bg-gradient-blooker {
      background: linear-gradient(45deg, #ffdf40, #ff8359) !important
   }

   .order-card {
      color: #fff;
      height: 90%
   }

   .bg-c-blue {
      background: linear-gradient(45deg, #4099ff, #73b4ff)
   }

   .bg-c-green {
      background: linear-gradient(45deg, #2ed8b6, #59e0c5)
   }

   .bg-c-yellow {
      background: linear-gradient(45deg, #ffb64d, #ffcb80)
   }

   .bg-c-pink {
      background: linear-gradient(45deg, #ff5370, #ff869a)
   }

   .card .card-block {
      padding: 25px
   }

   .order-card i {
      font-size: 26px
   }

   .f-left {
      float: left
   }

   .f-right {
      float: right
   }

   .counter-value {
      font-size: 25px;
      font-weight: 700;
      letter-spacing: 1px;
      margin: 0 0 13px;
      display: block
   }

   .chart-container {
      position: relative;
      height: 100vh;
      overflow: hidden
   }

   .aed-agent {
      text-align: center
   }

   .box-h {
      height: 80%
   }
</style>



<!--app-content open-->



<div class="main-content app-content mt-0">



   <div class="side-app">



      <input type="hidden" id="tabID" value="agents">



      <!-- CONTAINER -->



      <div class="main-container container-fluid mx-3">


         <!-- PAGE-HEADER -->



         <div class="page-header">



            <h1 class="page-title">
               <?= ucwords($roll_name); ?> Dashboard
            </h1>



            <div>



               <ol class="breadcrumb">



                  <li class="breadcrumb-item active" aria-current="page">
                     <?= ucwords($roll_name); ?> Dashboard
                  </li>



               </ol>



            </div>



         </div>



         <!-- PAGE-HEADER END -->







         <!-- ROW-1 -->

         <?php if ($roll_id == 1 || $roll_id == 2) { ?>
            <div class="row row-sm">
               <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <div class="d-flex justify-content-end">
                     <!-- <div class="card-body iconfont text-left"> -->
                     <!-- <div class="d-flex "> -->
                     <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" onclick="autoRefresh()" id="autoRefresh">
                        <label class="form-check-label" for="autoRefresh" data-toggle="tooltip" data-placement="top" title="This page contents are refreshed every 1 minutes.">Auto Refresh</label>
                     </div>

                     <!-- </div> -->
                     <!-- </div> -->
                  </div>
               </div>
            </div>
         <?php } ?>


         <div class="row row-sm">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
               <div class="card box-h">
                  <div class="card-body iconfont text-left">
                     <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-3" id="drawTotalTitle"><?= ucwords(strtolower($draw['result'][0]['name'])); ?></h4>
                        <!-- <i class="mdi mdi-dots-horizontal text-gray"></i> -->
                     </div>
                     <div class="d-flex mb-0">
                        <div class="">
                           <h4 class="mb-1 font-weight-bold" id="drawTotalAmt">
                           </h4>
                           <p class="mb-2 tx-12 text-muted">Current Total</p>
                        </div>
                        <div class="card-chart bg-primary-transparent brround ml-auto mt-0"> <i class="typcn typcn-group-outline text-primary tx-24"></i> </div>
                     </div>
                     <div class="progress progress-sm mt-2">
                        <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="70" id="saleprogress1" class="progress-bar bg-primary wd-70p" role="progressbar"></div>
                     </div> <small class="mb-0  text-muted" id="currentDrawTotalSale"></small>
                  </div>
               </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
               <div class="card box-h">
                  <div class="card-body iconfont text-left">
                     <div class="d-flex justify-content-between">
                        <h6 class="card-title mb-3">Withdraw request</h6>
                     </div>
                     <div class="d-flex mb-0">
                        <div class="">
                           <h4 class="mb-1 font-weight-bold"><?= $totalRequest ?></h4>
                           <p class="mb-2 tx-12 text-muted">Overview of Current month</p>
                        </div>
                        <div class="card-chart bg-pink-transparent brround ml-auto mt-0"> <i class="typcn typcn-chart-line-outline text-pink tx-24"></i> </div>
                     </div>
                     <div class="progress progress-sm mt-2">
                        <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?= $customer_active_withdraw_request['nr'] ?>" class="progress-bar bg-pink wd-50p" role="progressbar" style="width: <?= $customer_active_withdraw_request['nr'] . '%' ?>;"></div>
                     </div> <small class="mb-0  text-muted">Customer (<?= (intval($customer_active_withdraw_request_c['nr'] > 0) ? $customer_active_withdraw_request_c['nr'] : '0') ?>)<span class="float-right text-muted"><?= $customer_active_withdraw_request['nr'] ?></span></small>
                     <!--<div class="progress progress-sm mt-2">-->
                     <!--   <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?= $agent_active_withdraw_request ?>" class="progress-bar bg-primary wd-50p" role="progressbar" style="width: <?= $agent_active_withdraw_request . '%' ?>;"></div>-->
                     <!--</div> <small class="mb-0  text-muted">Agent (<?= (intval($agent_active_withdraw_request_c['nr'] > 0) ? $agent_active_withdraw_request_c['nr'] : '0') ?>)<span class="float-right text-muted"><?= $agent_active_withdraw_request ?></span></small>-->

                  </div>
               </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
               <div class="card box-h">
                  <div class="card-body iconfont text-left">
                     <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-3" id="drawTotalTitle">CCAvenue Payment</h4>
                        <!-- <i class="mdi mdi-dots-horizontal text-gray"></i> -->
                     </div>
                     <div class="d-flex mb-0">
                        <div class="">
                           <h4 class="mb-1 font-weight-bold" id="drawTotalCCPayment"></h4>
                           <p class="mb-2 tx-12 text-muted">Current Draw</p>
                        </div>
                        <div class="card-chart bg-primary-transparent brround ml-auto mt-0"> <i class="typcn typcn-credit-card text-primary tx-24"></i> </div>
                     </div>
                     <div class="progress progress-sm mt-2">
                        <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="70" id="ccsaleprogress" class="progress-bar bg-primary wd-70p" role="progressbar"></div>
                     </div> <small class="mb-0  text-muted" id="currentDrawTotalCCPayment"></small>
                  </div>
               </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
               <div class="card box-h">
                  <div class="card-body iconfont text-left">
                     <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-3" id="drawTotalTitle">Network Payment</h4>
                        <!-- <i class="mdi mdi-dots-horizontal text-gray"></i> -->
                     </div>
                     <div class="d-flex mb-0">
                        <div class="">
                           <h4 class="mb-1 font-weight-bold" id="drawTotalNetPayment">
                           </h4>
                           <p class="mb-2 tx-12 text-muted">Current Draw</p>
                        </div>
                        <div class="card-chart bg-purple-transparent brround ml-auto mt-0"> <i class="typcn typcn-credit-card text-purple tx-24"></i> </div>
                     </div>
                     <div class="progress progress-sm mt-2">
                        <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="70" id="netsaleprogress" class="progress-bar bg-purple wd-70p" role="progressbar"></div>
                     </div> <small class="mb-0  text-muted" id="currentDrawTotalNetPayment"></small>
                  </div>
               </div>
            </div>


         </div>
         <div class="row mb-5">
            <div class="col-md-4 col-xl-3">
               <div class="card radius-10 border-start border-0 border-3 border-info">
                  <div class="card-body">
                     <div class="d-flex align-items-center">
                        <div>
                           <!-- <p class="mb-0 text-secondary">Deleted Ticket Count</p> -->
                           <h4 class="my-1 text-info" id="deletedTicket"><strong>0</strong></h4>
                           <p class="mb-0 ">Deleted Ticket Count</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i class="fa fa-ticket"></i></div>
                     </div>
                  </div>
               </div>

            </div>


            <div class="col-md-4 col-xl-3">
               <div class="card radius-10 border-start border-0 border-3 border-danger">
                  <div class="card-body">
                     <div class="d-flex align-items-center">
                        <div>

                           <h4 class="my-1 text-danger" id="smsError"><strong>0</strong></h4>
                           <p class="mb-0 ">SMS</p>

                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i class="fa fa-comment"></i></div>
                     </div>
                  </div>
               </div>
            </div>



            <div class="col-md-4 col-xl-3">
               <div class="card radius-10 border-start border-0 border-3 border-success">
                  <div class="card-body">
                     <div class="d-flex align-items-center">
                        <div>

                           <h4 class="my-1 text-success" id="ccavenueError"><strong>0</strong></h4>
                           <p class="mb-0 ">CCAvenue </p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class="fa fa-money"></i></div>
                     </div>
                  </div>
               </div>

            </div>



            <div class="col-md-4 col-xl-3">
               <div class="card radius-10 border-start border-0 border-3 border-warning">
                  <div class="card-body">
                     <div class="d-flex align-items-center">
                        <div>

                           <h4 class="my-1 text-warning" id="networkError"><strong>0</strong></h4>
                           <p class="mb-0 ">Network </p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto"><i class="fa fa-credit-card"></i></div>
                     </div>
                  </div>
               </div>

            </div>


         </div>
         <div class="row mb-5">
            <div class="col-md-4 col-xl-3">
               <div class="card bg-c-blue order-card ">
                  <div class="card-block">
                     <h6 class="m-b-20">Past Draw Winners Sent SMS Count</h6>
                     <h2 class="text-end"><i class="fa fa-rocket f-left"></i><span class="counter-value" id="lastDrawsms">0</span></h2>

                  </div>
               </div>
            </div>

            <div class="col-md-4 col-xl-3">
               <div class="card bg-c-green order-card ">
                  <div class="card-block">
                     <h6 class="m-b-20">Past Draw Winners Sent Email Count</h6>
                     <h2 class="text-end"><i class=" fa fa-envelope f-left"></i><span class="counter-value" id="lastDrawemail">0</span></h2>

                  </div>
               </div>
            </div>

            <div class="col-md-4 col-xl-3">
               <div class="card bg-c-yellow order-card ">
                  <div class="card-block">
                     <h6 class="m-b-20">CCAvenue Missing Ticket</h6>
                     <h2 class="text-end"><i class="fa fa-refresh f-left"></i><span id="ccMissingT">0</span></h2>

                  </div>
               </div>
            </div>

            <div class="col-md-4 col-xl-3">
               <div class="card bg-c-pink order-card ">
                  <div class="card-block">
                     <h6 class="m-b-20">Delete Account Request</h6>
                     <h2 class="text-end"><i class="fa fa-trash  f-left"></i><span class="counter-value" id="deleted_req_count">0</span></h2>

                  </div>
               </div>
            </div>
         </div>



         <div class="row row-sm">


            <div class="col-lg-7 col-md-6 col-sm-12">
               <div class="card">
                  <div class="card-body">
                     <div class="d-flex justify-content-between">
                        <h4 class="card-title"> Sign-in & sign-up (Last 7 Days)</h4>

                     </div>
                     <div id="chart">
                        <div id="oneweekuserinout"></div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
               <div class="card">
                  <div class="card-body">
                     <div class="d-flex justify-content-between">
                        <h4 class="card-title"><?= ucwords(strtolower($draw['result'][0]['name'])) . ' Ticket Sales (AED)'; ?> </h4>

                     </div>
                     <div id="chart">
                        <div id="ticket-sales-chart"></div>
                     </div>
                  </div>
               </div>
            </div>




            <div class="col-lg-7 col-md-6 col-sm-12">
               <div class="card">
                  <div class="card-body">

                     <div class="row">
                        <div class="d-flex justify-content-between">
                           <h4 class="card-title">Ticket Analyze (OT)</h4>
                        </div>
                     </div>
                     <!--<div class="row">-->
                     <!--   <div class="col-6">-->
                     <!--      <input type="date" class="form-control" name="dateOF" id="dateOF" value="<?= date('Y-m-d'); ?>" max="<?= date('Y-m-d'); ?>">-->
                     <!--   </div>-->
                     <!--   <div class="col-6">-->
                     <!--      <button class="btn btn-primary" onclick="ticket_analyze_chart($('#dateOF').val())">GO</button>-->
                     <!--   </div>-->



                     <div class="row">
                        <div class="col-6">
                           <span>Select Draw</span>&nbsp;<span style="color: red;">*</span>
                           <select id="draw_new_id" class="form-select">
                              <!--<option value="">Select Draw</option>-->
                              <?php
                              $draw = select_query($con, "draw", "", "(`deletes` = 0 AND `status` = 'Completed') OR (`deletes` = 0 AND `status` = 'Active' AND `id` IN ( SELECT `id` FROM ( SELECT `id` FROM `draw` WHERE `deletes` = 0 AND `status` = 'Active' ORDER BY `id` ASC LIMIT 2 ) t )) ORDER BY `id` DESC", "", "");

                              if ($draw['nr'] > 0) {
                                 foreach ($draw['result'] as $key => $value) {
                                    $naeme = explode("#", $value['name']);
                              ?>
                                    <option value="<?= $value['id']; ?>" <?= ($drawID == $value['id']) ? 'selected' : '' ?>><?= 'Draw No #'  . str_pad($value['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($value['result_datetime'])) . ' ' . str_replace("Draw", "", $naeme[0]) . ' (' .  date("D", strtotime($value['result_datetime'])) . ')'; ?></option>
                              <?php

                                 }
                              }
                              ?>
                           </select>
                        </div>
                        <div class="col-6 mt-5" id="ticketValiBtn">
                           <button class="btn btn-primary" onclick="ticketAnanDetails($('#draw_new_id').val())">GO</button>
                        </div>




                        <!--</div>-->


                        <!--<div id="chart">-->
                        <!--   <div id="ticket-analyze-chart"></div>-->
                        <!--</div>-->


                        <div id="chart">
                           <table class="table">
                              <thead>
                                 <tr>
                                    <th scope="col">Ticket Count</th>
                                    <th scope="col">Invoice Count</th>
                                    <th scope="col">Ticket Amount (AED)</th>
                                    <th scope="col">Ticket Line Wise Amount (AED)</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td id="tccount">0</td>
                                    <td id="invoiceCount">0</td>
                                    <td id="ticketTotal">0</td>
                                    <td id="lineWiseTotal">0</td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>







            </div>
            <div class="col-lg-5 col-md-6 col-sm-12 ">
               <div class="card">
                  <div class="card-body">
                     <div class="d-flex justify-content-between">
                        <h4 class="card-title">Sign-up (Last 5 days) </h4>

                     </div>
                     <div id="chart">
                        <div id="agent-chart"></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>



<!-- ROW-1 END -->















<!-- CONTAINER END -->








<form action="paymentstatushistory" id="ccGet" method="post">
   <input type="hidden" name="ccAvenue" value="notGenerated">
   <!-- <input type="submit"> -->
</form>


<!--app-content close-->



<script>
   var url = origin + "/ajax/service/transaction_services.php";
   var graph_services = origin + "/ajax/service/graph_services.php";
   var chart = '';
   var chartTicket = '';
   var chart1 = '';
   var oneweekuserinout = '';


   $(function() {
      autoSelect();
      getDrawTotal();
      getDrawCCPayment();
      getDrawNetPayment();
      ticket_Sales_Chart();
      last_7_sign_Chart();
      getErrorCount();
      //   ticket_analyze_chart();
      ticketAnanDetails();
      last_7_sign();
      setInterval(function() {
         if ($("#autoRefresh").prop("checked")) {
            getDrawTotal();
            ticket_Sales_Chart();
            last_7_sign_Chart();
            getErrorCount();
            getDrawCCPayment();
            getDrawNetPayment();
            // ticket_analyze_chart();
            ticketAnanDetails();
            last_7_sign();
         }
      }, runtime);

   });

   function setCookie(cname, cvalue, exdays) {
      const d = new Date();
      d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
      let expires = "expires=" + d.toUTCString();
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
   }

   function getCookie(cname) {
      let name = cname + "=";
      let ca = document.cookie.split(';');
      for (let i = 0; i < ca.length; i++) {
         let c = ca[i];
         while (c.charAt(0) == ' ') {
            c = c.substring(1);
         }
         if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
         }
      }
      return "";
   }

   function autoRefresh() {
      let auto = $("#autoRefresh").prop("checked");
      setCookie('autoRefresh', auto, 30);
   }

   function autoSelect() {
      let auto = getCookie('autoRefresh');
      if (auto != '' && auto != undefined && auto != null) {
         $("#autoRefresh").prop("checked", (auto === 'true'));
      }
   }




   //   function ticket_Sales_Chart() {

   //       var formdata = [];

   //       formdata.push({
   //          name: 'method',
   //          value: "ticketsaleschart"

   //       });

   //       var post_data = formdata;

   //       var onsuccess = function(data) {

   //          var response = JSON.parse(data);

   //          if (response != "") {

   //             if (response.type == 1) {

   //               var keys = response.result.map(obj => Object.keys(obj)[0]);
   //               var values = response.result.map(obj => Object.values(obj)[0]);

   //               if (chart != '') {
   //                   if (chart.ohYeahThisChartHasBeenRendered && chart.ohYeahThisChartHasBeenRendered != undefined) {
   //                      chart.destroy();
   //                   }
   //               }

   //               var options = {
   //                   series: values,
   //                   labels: keys,
   //                   chart: {
   //                      type: 'donut',
   //                      width: '100%',
   //                      height: 250
   //                   },
   //                   responsive: []

   //               };

   //               chart = new ApexCharts(document.querySelector("#ticket-sales-chart"), options);
   //               chart.render().then(() => chart.ohYeahThisChartHasBeenRendered = true);

   //             } else {

   //               $('#ticket-sales-chart').html(response.result);



   //             }

   //          }

   //       }

   //       do_ajax_call(post_data, onsuccess, origin + "/ajax/service/overall_services.php");

   //   }
   function ticket_Sales_Chart() {

      var formdata = [];

      formdata.push({
         name: 'method',
         value: "ticketsaleschart"
      });

      var post_data = formdata;

      var onsuccess = function(data) {

         var response = JSON.parse(data);

         if (response != "") {

            if (response.type == 1) {

               var keys = response.result.map(obj => Object.keys(obj)[0]);
               var values = response.result.map(obj => Object.values(obj)[0]);

               if (chart != '') {
                  if (chart.ohYeahThisChartHasBeenRendered && chart.ohYeahThisChartHasBeenRendered != undefined) {
                     chart.destroy();
                  }
               }

               var options = {
                  series: values,
                  labels: keys,
                  chart: {
                     type: 'donut',
                     width: '100%',
                     height: 250
                  },
                  colors: ['#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#CD5C5C', '#00FFFF'], // Add the custom colors here
                  responsive: []
               };

               chart = new ApexCharts(document.querySelector("#ticket-sales-chart"), options);
               chart.render().then(() => chart.ohYeahThisChartHasBeenRendered = true);

            } else {

               $('#ticket-sales-chart').html(response.result);

            }

         }

      }

      do_ajax_call(post_data, onsuccess, origin + "/ajax/service/overall_services.php");

   }

   function ticket_analyze_chart(date = '') {

      var formdata = [];

      formdata.push({
         name: 'method',
         value: "ticketanalyzechart"

      }, {
         name: 'date',
         value: date

      });

      var post_data = formdata;

      var onsuccess = function(data) {
         var response = JSON.parse(data);
         if (response != "") {
            if (response.type == 0) {

               if (chartTicket != '') {
                  if (chartTicket.ohYeahThisChartHasBeenRendered && chartTicket.ohYeahThisChartHasBeenRendered != undefined) {
                     chartTicket.destroy();
                  }
               }

               var options = {
                  series: [{
                        name: "Count",
                        data: [response.ticketCount, response.ticketLinesCount, response.paymentsCount, response.invoiceCount]
                     }

                     // ,
                     // {
                     //    name: "Total Invoice",
                     //    data: [response.invticket, response.ainvticket, response.finvticket, response.minvticket, response.winvticket, response.cinvticket, response.cpinvticket, response.bpinvticket]
                     // }

                  ],
                  chart: {
                     type: 'bar',
                     height: 250
                  },
                  plotOptions: {
                     bar: {
                        horizontal: true,
                        dataLabels: {
                           position: 'top',
                        },
                     }
                  },
                  dataLabels: {
                     enabled: true,
                     offsetX: -6,
                     style: {
                        fontSize: '12px',
                        colors: ['#fff']
                     }
                  },
                  stroke: {
                     show: true,
                     width: 1,
                     colors: ['#fff']
                  },
                  tooltip: {
                     shared: true,
                     intersect: false
                  },
                  xaxis: {
                     categories: ['Online Ticket', 'Ticket Lines', 'Payments', 'Invoice'],
                  },
               };

               chartTicket = new ApexCharts(document.querySelector("#ticket-analyze-chart"), options);
               chartTicket.render().then(() => chartTicket.ohYeahThisChartHasBeenRendered = true);
            }
         }
      }

      do_ajax_call(post_data, onsuccess, origin + "/ajax/service/overall_services.php");

   }

   function ticketAnanDetails(draw = '') {

      var formdata = [];
      let btn = $('#ticketValiBtn').html();
      formdata.push({
         name: 'method',
         value: "ticketAnanDetails"

      }, {
         name: 'draw',
         value: draw

      });

      var post_data = formdata;


      $('#ticketValiBtn').html(`<button class="btn btn-primary" type="button" disabled>
                              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                              <span class="sr-only">Loading...</span>
                            </button>`);
      var onsuccess = function(data) {
         var response = JSON.parse(data);
         if (response != "") {
            if (response.type == 0) {
               $('#tccount').text(response.ticketCount);
               $('#ticketTotal').text(response.ticketTotal);
               $('#lineWiseTotal').text(response.lineWiseTotal);
               $('#invoiceCount').text(response.invoiceCount);
            }

            $('#ticketValiBtn').html(btn);
         }
      }

      do_ajax_call(post_data, onsuccess, origin + "/ajax/service/overall_services.php");

   }

   function last_7_sign_Chart() {

      var formdata = [];

      formdata.push({
         name: 'method',
         value: "lastoneWeekSignUP"

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
                        name: 'Sign-IN',
                        data: response.result.Signin
                     }, {
                        name: 'Sign-UP',
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

   function getErrorCount() {

      var formdata = [{
         name: 'method',
         value: "getErrorCount"

      }];



      var post_data = formdata;
      var onsuccess = function(data) {
         var response = JSON.parse(data);
         if (response != "") {
            if (response.type == 1) {

               $('#deletedTicket').html(`<strong style="font-weight: 600;color: ${(response.result['deletedTicket'] > 0) ? '#af2b03 !important' : '#6eaf19 !important' };" class="counter-value">${response.result['deletedTicket']}</strong>`);
               $('#smsError').html(`<a href="${ origin + '/cron/smssend.php' }" target="_blank"><strong style="font-weight: 600;color: ${(response.result['smslog'] > 0) ? '#af2b03 !important' : '#6eaf19 !important' };" class="counter-value">${response.result['smslog']}</strong></a>`);
               $('#networkError').html(`<a href="${ origin + '/cron/cron1.php' }" target="_blank"><strong style="font-weight: 600;color: ${(response.result['network'] > 0) ? '#af2b03 !important' : '#6eaf19 !important' };" class="counter-value">${response.result['network']}</strong></a>`);
               $('#ccavenueError').html(`<a href="${ origin + '/cron/cron1.php' }" target="_blank"><strong style="font-weight: 600;color: ${(response.result['ccavenue'] > 0) ? '#af2b03 !important' : '#6eaf19 !important' };" class="counter-value">${response.result['ccavenue']}</strong></a>`);
               $('#lastDrawsms').text(response.result['lastDrawsms']);
               $('#lastDrawemail').text(response.result['lastDrawemail']);
               $('#ccMissingT').html(`<strong onclick="getUnTicket()" style="font-weight: 600;" class="counter-value">${response.result['ccMissingT']}</strong>`);
               $('#deleted_req_count').text(response.result['deleted_req_count']);


               __counterRun();
            } else {


            }
         }
      }

      do_ajax_call(post_data, onsuccess, origin + "/ajax/service/overall_services.php");

   }



   function getDrawTotal() {

      var formdata = [];

      formdata.push({
         name: 'method',
         value: "getDrawTotal"

      });

      var post_data = formdata;
      var onsuccess = function(data) {
         var response = JSON.parse(data);
         if (response != "") {
            if (response.type == 1) {
               var currentkeys = response.currentDraw.map(obj => Object.keys(obj)[0]);
               var currentvalues = response.currentDraw.map(obj => Object.values(obj)[0]);

               var currentSum = sumValue(currentvalues);


               var pastkeys = response.pastDraw.map(obj => Object.keys(obj)[0]);
               var pastvalues = response.pastDraw.map(obj => Object.values(obj)[0]);

               var pastSum = sumValue(pastvalues);

               var percentage = ((currentSum - pastSum) / pastSum) * 100;


               $('#drawTotalAmt').html(`${currentSum}<span class="${(currentSum > pastSum) ? 'text-success' : 'text-danger'} tx-13 ml-2">&nbsp;(${(currentSum > pastSum) ? '+' : '-'}${Math.abs(percentage.toFixed(2))}%)</span>`);
               let perSale = 100 - Math.abs(percentage.toFixed(2));
               var newProgressValue = parseInt(perSale);


               $('#saleprogress1').attr('aria-valuenow', newProgressValue);


               $('#saleprogress1').css('width', newProgressValue + '%');


               $('#currentDrawTotalSale').html(`${response.currentDrawName}<span class="float-right text-muted">${parseFloat(perSale).toFixed(2)}%</span>`);

            } else {


            }
         }
      }

      do_ajax_call(post_data, onsuccess, origin + "/ajax/service/overall_services.php");

   }

   function getDrawCCPayment() {

      var formdata = [];

      formdata.push({
         name: 'method',
         value: "getDrawCCPayment"

      });

      var post_data = formdata;
      var onsuccess = function(data) {
         var response = JSON.parse(data);
         if (response != "") {
            if (response.type == 1) {


               var currentSum = response.currentDraw;




               var pastSum = response.pastDraw

               var percentage = ((currentSum - pastSum) / pastSum) * 100;


               $('#drawTotalCCPayment').html(`${currentSum}<span class="${(currentSum > pastSum) ? 'text-success' : 'text-danger'} tx-13 ml-2">&nbsp;(${(currentSum > pastSum) ? '+' : '-'}${Math.abs(percentage.toFixed(2))}%)</span>`);
               let perSale = 100 - Math.abs(percentage.toFixed(2));
               var newProgressValue = parseInt(perSale);


               $('#ccsaleprogress').attr('aria-valuenow', newProgressValue);

               $('#ccsaleprogress').css('width', newProgressValue + '%');


               $('#currentDrawTotalCCPayment').html(`${response.currentDrawName}<span class="float-right text-muted">${parseFloat(perSale).toFixed(2)}%</span>`);

            } else {


            }
         }
      }

      do_ajax_call(post_data, onsuccess, origin + "/ajax/service/overall_services.php");

   }

   function getDrawNetPayment() {

      var formdata = [];

      formdata.push({
         name: 'method',
         value: "getDrawNetPayment"

      });

      var post_data = formdata;
      var onsuccess = function(data) {
         var response = JSON.parse(data);
         if (response != "") {
            if (response.type == 1) {


               var currentSum = response.currentDraw;

               var pastSum = response.pastDraw

               var percentage = ((currentSum - pastSum) / pastSum) * 100;


               $('#drawTotalNetPayment').html(`${currentSum}<span class="${(currentSum > pastSum) ? 'text-success' : 'text-danger'} tx-13 ml-2">&nbsp;(${(currentSum > pastSum) ? '+' : '-'}${Math.abs(percentage.toFixed(2))}%)</span>`);
               let perSale = 100 - Math.abs(percentage.toFixed(2));
               var newProgressValue = parseInt(perSale);


               $('#netsaleprogress').attr('aria-valuenow', newProgressValue);


               $('#netsaleprogress').css('width', newProgressValue + '%');


               $('#currentDrawTotalNetPayment').html(`${response.currentDrawName}<span class="float-right text-muted">${parseFloat(perSale).toFixed(2)}%</span>`);

            } else {


            }
         }
      }

      do_ajax_call(post_data, onsuccess, origin + "/ajax/service/overall_services.php");

   }


   function sumValue(sum) {
      return sum.reduce((accumulator, currentValue) => accumulator + currentValue, 0)
   }












   function getUnTicket() {
      $('#ccGet').submit();
   }





   function __counterRun() {
      $('.counter-value').each(function() {
         $(this).prop('Counter', 0).animate({
            Counter: $(this).text()
         }, {
            duration: 3500,
            easing: 'swing',
            step: function(now) {
               $(this).text(Math.ceil(now));
            }
         });
      });
   }




   function last_7_sign() {
      var formdata = [];

      formdata.push({
         name: 'method',
         value: "lastoneWeekSignUPWithCreatedBy"
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
                     name: 'Agent-signup',
                     data: response.result.Signup
                  }, {
                     name: 'Kiosk-signup',
                     data: response.result.KioskSignup
                  }, {
                     name: 'Customer',
                     data: response.result.CustomerSignup
                  }],
                  chart: {
                     type: 'bar',
                     width: '100%',
                     height: 280,
                     toolbar: {
                        show: true,
                        offsetX: 0,
                        offsetY: 0,
                        tools: {
                           download: false,
                           selection: true,
                           zoom: false,
                           zoomin: true,
                           zoomout: true,
                           pan: false,
                           reset: true
                        }
                     }
                  },
                  plotOptions: {
                     bar: {
                        horizontal: false,
                        columnWidth: '100%',
                        endingShape: 'rounded'
                     },
                  },
                  colors: ['#7E30E1', '#E26EE5', '#FF5733'],
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
                     position: 'bottom',
                     labels: {
                        offsetY: 5,
                        rotate: 0,
                     },
                     max: 5,
                  },
                  yaxis: {
                     title: {
                        // text: 'Counts'
                     }
                  },
                  fill: {
                     opacity: 3
                  },
                  tooltip: {
                     y: {
                        formatter: function(val) {
                           return "# " + val + " Counts";
                        }
                     }
                  },
                  legend: {
                     show: true,
                     position: 'top',
                     horizontalAlign: 'left',
                     markers: {
                        width: 12,
                        height: 12,
                     },
                  },
               };


               oneweekuserinout = new ApexCharts(document.querySelector("#agent-chart"), options);
               oneweekuserinout.render();

            } else {
               $('#agent-chart').html(response.result);
            }
         }
      }

      do_ajax_call(post_data, onsuccess, origin + "/ajax/service/overall_services.php");
   }
</script>