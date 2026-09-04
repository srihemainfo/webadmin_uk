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

$wrfd = date('Y-m-01');
$wrtd = date('Y-m-t');

$customer_active_withdraw_request = select_query($con, "withdraw_request", "", "`status` = '0' AND  `createdon` BETWEEN '$wrfd 00:00:00' AND '$wrtd 23:59:59' AND `deletes`='0' ORDER BY `id` DESC", "", "");

$getAgentReq = mysqli_query($con, "SELECT user_register.roll_id FROM `withdraw_request` JOIN user_register ON user_register.id = withdraw_request.from_id WHERE (user_register.roll_id = 3 OR user_register.roll_id = 4 OR user_register.roll_id = 5) AND withdraw_request.status = '0' AND  `createdon` BETWEEN '" . $wrfd . " 00:00:00' AND '" . $wrtd . " 23:59:59'");

$agent_active_withdraw_request = mysqli_num_rows($getAgentReq);

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



            <h1 class="page-title"> Ticket Sales Analysis</h1>




            <div>



               <ol class="breadcrumb">



                  <li class="breadcrumb-item active" aria-current="page">Ticket Sales Analysis

                  </li>



               </ol>



            </div>



         </div>



         <!-- PAGE-HEADER END -->







         <!-- ROW-1 -->

         <?php if ($roll_id == 1 || $roll_id == 2) { ?>
            <!--<div class="row row-sm">-->
            <!--   <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">-->
            <!--      <div class="d-flex justify-content-end">-->

            <!--         <div class="form-check form-switch">-->
            <!--            <input class="form-check-input" type="checkbox" role="switch" onclick="autoRefresh()" id="autoRefresh">-->
            <!--            <label class="form-check-label" for="autoRefresh" data-toggle="tooltip" data-placement="top" title="This page contents are refreshed every 1 minutes.">Auto Refresh</label>-->
            <!--         </div>-->

            <!--      </div>-->
            <!--   </div>-->
            <!--</div>-->
         <?php } ?>
         <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
               <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                     <div class="card overflow-hidden">
                        <div class="card-body">
                           <div class="">
                              <div class="mt-2">
                                 <div class="row">
                                    <div class="col-lg-3 col-sm-6 mb-2">
                                       <span>Select Draw</span>&nbsp;<span style="color: red;">*</span>
                                       <select id="draw_new_id" class="form-select">
                                          <option value="">Select Draw</option>
                                          <?php
                                          $draw = select_query($con, "draw", "", " `deletes`='0'  ORDER BY `id` DESC", "", "");
                                          $draw2432432 = select_query($con, "draw", "", "`deletes`='0' and `status`='Active' and `ticket_start_datetime`< '$dubaidate_time' and `ticket_end_datetime`>'$dubaidate_time'   order by `id` ASC", "", "");
                                          if ($draw['nr'] > 0) {
                                             foreach ($draw['result'] as $key => $value) {
                                                $naeme = explode("#", $value['name']);
                                          ?>

                                                <option value="<?= $value['id']; ?>" <?= ($draw2432432['result'][0]['id'] == $value['id']) ? 'selected' : ''; ?>><?= 'Draw No #'  . str_pad($value['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("d-m-Y", strtotime($value['result_datetime'])) . ' ' . str_replace("Draw", "", $naeme[0]) . ' (' .  date("D", strtotime($value['result_datetime'])) . ')'; ?></option>

                                                <?= 'Draw No #'  . str_pad($value['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("d-m-Y", strtotime($value['result_datetime'])) . ' ' . str_replace("Draw", "", $naeme[0]) . ' (' .  date("D", strtotime($value['result_datetime'])) . ')'; ?>
                                                <?= $value['name']; ?>
                                                </option>
                                          <?php

                                             }
                                          }
                                          ?>
                                          <!-- <form class="datefilter" method="POST"> -->

                                          <!--<div class="col-lg-3 col-sm-12 mb-2">-->

                                           



                                          

                                             <!--<input type="hidden" name="fieldname" id="fieldname" value="" class="form-control">-->


                                       </select>

                                    </div>
                                      <div class="col-lg-3 col-sm-6 mb-2">
                                          <lable>From Date</lable>
                                            <input type="date" name="formdate" class="form-control" id="formdate" value="<?php echo date("Y-m-d"); ?>">
                                          </div>
                                          <div class="col-lg-3 col-sm-6 mb-2"> 
                                           <lable>To Date</lable>
                                             <input type="date" name="todate" class="form-control" id="todate" value="<?php echo date("Y-m-d"); ?>">
                                          </div>

                                    <div class="col-lg-3 col-sm-5 mb-2 mt-5">
                                       <button class="btn btn-info" class="form-control" onclick="ticket_Sales_Chart()">Go</button>
                                    </div>



                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                       <div class="card">
                                          <div class="card-body">
                                             <div class="d-flex justify-content-between">
                                                <h4 class="card-title" id="titaltafdas"></h4>

                                             </div>
                                             <div id="chart">
                                                <div id="ticket-sales-chart"></div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>












                                 </div>




                              </div>



                              <!-- ROW-1 END -->











                           </div>



                           <!-- CONTAINER END -->



                        </div>



                     </div>




                     <form action="paymentstatushistory" id="ccGet" method="post">
                        <input type="hidden" name="ccAvenue" value="notGenerated">

                     </form>


                     <!--app-content close-->



                     <script>
                        var origin = window.location.origin;
                        var url = origin + "/ajax/service/transaction_services.php";
                        var graph_services = origin + "/ajax/service/graph_services.php";
                        var chart = '';
                        var chartTicket = '';
                        var chart1 = '';
                        var oneweekuserinout = '';









                        $(function() {
                           // autoSelect();
                           // getDrawTotal();
                           // getDrawCCPayment();
                           // getDrawNetPayment();
                           ticket_Sales_Chart();
                           // last_7_sign_Chart();
                           // getErrorCount();
                           // ticket_analyze_chart();


                           // setInterval(function() {
                           //    if ($("#autoRefresh").prop("checked")) {
                           //       // getDrawTotal();
                           //       ticket_Sales_Chart();
                           //       // last_7_sign_Chart();
                           //       // getErrorCount();
                           //       // getDrawCCPayment();
                           //       // getDrawNetPayment();
                           //       // ticket_analyze_chart();
                           //    }
                           // }, runtime);

                        });

                        function ticket_Sales_Chart() {

                           var formdata = [];


                           formdata.push({
                                 name: 'method',
                                 value: "ticketsaleschart"

                              }, {
                                 name: 'draw_new_id',

                                 value: $('#draw_new_id').val()
                              },

                              {

                                 name: 'formdate',

                                 value: $('#formdate').val()

                              },

                              {

                                 name: 'todate',

                                 value: $('#todate').val()

                              });




                           ticketname = $('#draw_new_id option:selected').val()
                           if (ticketname != "") {
                              $('#titaltafdas').text($('#draw_new_id option:selected').text());
                           } else {

                              fromdate = moment($('#formdate').val()).format('DD-MM-YYYY');
                              todate = moment($('#todate').val()).format('DD-MM-YYYY');

                              $('#titaltafdas').html("Date from " + fromdate + " to " + todate);

                           }

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
                                          type: "pie",
                                          width: '100%',
                                          height: 450
                                       },
                                       responsive: []

                                    };

                                    chart = new ApexCharts(document.querySelector("#ticket-sales-chart"), options);
                                    chart.render().then(() => chart.ohYeahThisChartHasBeenRendered = true);

                                 } else {

                                    $('#ticket-sales-chart').html(response.result);


                                 }

                              }

                           }

                           do_ajax_call(post_data, onsuccess, origin + "/ajax/service/TicketSalesAnalysis_services.php");

                        }

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





                        function ticket_analyze_chart() {

                           var formdata = [];

                           formdata.push({
                              name: 'method',
                              value: "ticketanalyzechart"

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
                                          name: "Total Ticket",
                                          data: [response.ticket, response.aticket, response.fticket, response.mticket, response.wticket, response.cticket, response.cpticket, response.bpticket]
                                       }, {
                                          name: "Total Invoice",
                                          data: [response.invticket, response.ainvticket, response.finvticket, response.minvticket, response.winvticket, response.cinvticket, response.cpinvticket, response.bpinvticket]
                                       }],
                                       chart: {
                                          type: 'bar',
                                          height: 430
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
                                          categories: ['Online', 'Wallet', 'Agent', 'Cash', 'Bonus', 'Coupon', 'Free'],
                                       },
                                    };

                                    chartTicket = new ApexCharts(document.querySelector("#ticket-analyze-chart"), options);
                                    chartTicket.render().then(() => chartTicket.ohYeahThisChartHasBeenRendered = true);
                                 }
                              }
                           }

                           do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/TicketSalesAnalysis_services.php");

                        }

                        // function last_24_sign_Chart() {

                        //    var formdata = [];

                        //    formdata.push({
                        //       name: 'method',
                        //       value: "lastsignsignup"

                        //    });

                        //    var post_data = formdata;

                        //    var onsuccess = function(data) {

                        //       var response = JSON.parse(data);

                        //       if (response != "") {

                        //          if (response.type == 1) {

                        //             var keys = response.result.map(obj => Object.keys(obj)[0]);
                        //             var values = response.result.map(obj => Object.values(obj)[0]);


                        //             if (chart1 != '') {
                        //                if (chart1.ohYeahThisChartHasBeenRendered && chart1.ohYeahThisChartHasBeenRendered != undefined) {
                        //                   chart1.destroy();
                        //                }
                        //             }

                        //             var options = {
                        //                series: values,
                        //                labels: keys,
                        //                chart: {
                        //                   type: 'radialBar',
                        //                   width: '100%',
                        //                   height: 220
                        //                },



                        //                // breakpoint: 480,
                        //                plotOptions: {
                        //                   radialBar: {
                        //                      dataLabels: {
                        //                         name: {
                        //                            fontSize: '22px',
                        //                         },
                        //                         value: {
                        //                            fontSize: '16px',
                        //                            formatter: function(val) {
                        //                               return val;
                        //                            }
                        //                         },
                        //                         total: {
                        //                            show: true,
                        //                            label: 'Total',
                        //                            formatter: function(w) {
                        //                               // Calculate the total of the series
                        //                               var total = values.reduce((acc, value) => acc + parseInt(value), 0);
                        //                               return total;
                        //                            }
                        //                         }
                        //                      }
                        //                   }
                        //                }
                        //             };

                        //             chart1 = new ApexCharts(document.querySelector("#lastonedayChart"), options);
                        //             chart1.render().then(() => chart1.ohYeahThisChartHasBeenRendered = true);;


                        //          } else {

                        //             $('#ticket-sales-chart').html(response.result);

                        //             // document.getElementById('collectdiv').innerHTML = response.result;

                        //          }

                        //       }

                        //    }

                        //    do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/TicketSalesAnalysis_services.php");

                        // }



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

                                    // var keys = response.result.map(obj => Object.keys(obj)[0]);
                                    // var values = response.result.map(obj => Object.values(obj)[0]);


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

                                    // document.getElementById('collectdiv').innerHTML = response.result;

                                 }

                              }

                           }

                           do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/TicketSalesAnalysis_services.php");

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

                                    $('#deletedTicket').html(`<strong style="font-weight: 600;color: ${(response.result['deletedTicket'] > 0) ? '#af2b03 !important' : '#6eaf19 !important' };">${response.result['deletedTicket']}</strong>`);
                                    $('#smsError').html(`<a href="${ window.location.origin + '/cron/smssend.php' }" target="_blank"><strong style="font-weight: 600;color: ${(response.result['smslog'] > 0) ? '#af2b03 !important' : '#6eaf19 !important' };">${response.result['smslog']}</strong></a>`);
                                    $('#networkError').html(`<a href="${ window.location.origin + '/cron/cron1.php' }" target="_blank"><strong style="font-weight: 600;color: ${(response.result['network'] > 0) ? '#af2b03 !important' : '#6eaf19 !important' };">${response.result['network']}</strong></a>`);
                                    $('#ccavenueError').html(`<a href="${ window.location.origin + '/cron/cron1.php' }" target="_blank"><strong style="font-weight: 600;color: ${(response.result['ccavenue'] > 0) ? '#af2b03 !important' : '#6eaf19 !important' };">${response.result['ccavenue']}</strong></a>`);
                                    $('#lastDrawsms').text(response.result['lastDrawsms']);
                                    $('#lastDrawemail').text(response.result['lastDrawemail']);
                                    $('#ccMissingT').html(`<strong onclick="getUnTicket()" style="font-weight: 600;">${response.result['ccMissingT']}</strong>`);
                                    $('#deleted_req_count').text(response.result['deleted_req_count']);

                                    // $('#networkError').html(`<b>Network:</b> &nbsp; <a href="${ window.location.origin + '/cron/cron1.php' }" target="_blank"><strong style="font-weight: 600;color: ${(response.result['network'] > 0) ? '#af2b03 !important' : '#6eaf19 !important' };">${response.result['network']}</strong></a>`);
                                    // $('#ccavenueError').html(`<b>CCAvenue:</b> &nbsp;<a href="${ window.location.origin + '/cron/cron1.php' }" target="_blank"><strong style="font-weight: 600;color: ${(response.result['ccavenue'] > 0) ? '#af2b03 !important' : '#6eaf19 !important' };">${response.result['ccavenue']}</strong></a>`);
                                    // $('#smsError').html(`<b>SMS:</b> &nbsp;<a href="${ window.location.origin + '/cron/smssend.php' }" target="_blank"><strong style="font-weight: 600;color: ${(response.result['smslog'] > 0) ? '#af2b03 !important' : '#6eaf19 !important' };">${response.result['smslog']}</strong></a>`);
                                    // $('#deletedTicket').html(`<b>Deleted Ticket Count:</b> &nbsp;<strong style="font-weight: 600;color: ${(response.result['deletedTicket'] > 0) ? '#af2b03 !important' : '#6eaf19 !important' };">${response.result['deletedTicket']}</strong>`);
                                    // $('#ccMissingT').html(`<b>CCAvenue Missing Ticket:</b> &nbsp;<strong onclick="getUnTicket()" style="font-weight: 600;color: ${(response.result['ccMissingT'] > 0) ? '#af2b03 !important' : '#6eaf19 !important' };">${response.result['ccMissingT']}</strong>`);
                                    // $('#lastDrawemail').html(`<b>Past Draw Winners Sent Email Count:</b> &nbsp;<strong style="font-weight: 600;color: ${(response.result['lastDrawemail'] > 0) ? '#af2b03 !important' : '#6eaf19 !important' };">${response.result['lastDrawemail']}</strong>`);
                                    // $('#lastDrawsms').html(`<b>Past Draw Winners Sent SMS Count:</b> &nbsp;<strong style="font-weight: 600;color: ${(response.result['lastDrawsms'] > 0) ? '#af2b03 !important' : '#6eaf19 !important' };">${response.result['lastDrawsms']}</strong>`);

                                 } else {

                                    // $('#ticket-sales-chart').html(response.result);
                                 }
                              }
                           }

                           do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/TicketSalesAnalysis_services.php");

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

                                    // Update aria-valuenow attribute
                                    $('#saleprogress1').attr('aria-valuenow', newProgressValue);

                                    // Update progress bar width
                                    $('#saleprogress1').css('width', newProgressValue + '%');


                                    $('#currentDrawTotalSale').html(`${response.currentDrawName}<span class="float-right text-muted">${parseFloat(perSale).toFixed(2)}%</span>`);

                                 } else {


                                 }
                              }
                           }

                           do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/TicketSalesAnalysis_services.php");

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
                                    /*var currentkeys = response.currentDraw.map(obj => Object.keys(obj)[0]);
                                    var currentvalues = response.currentDraw.map(obj => Object.values(obj)[0]);*/

                                    var currentSum = response.currentDraw;


                                    /*var pastkeys = response.pastDraw.map(obj => Object.keys(obj)[0]);
                                    var pastvalues = response.pastDraw.map(obj => Object.values(obj)[0]);*/

                                    var pastSum = response.pastDraw

                                    var percentage = ((currentSum - pastSum) / pastSum) * 100;


                                    $('#drawTotalCCPayment').html(`${currentSum}<span class="${(currentSum > pastSum) ? 'text-success' : 'text-danger'} tx-13 ml-2">&nbsp;(${(currentSum > pastSum) ? '+' : '-'}${Math.abs(percentage.toFixed(2))}%)</span>`);
                                    let perSale = 100 - Math.abs(percentage.toFixed(2));
                                    var newProgressValue = parseInt(perSale);

                                    // Update aria-valuenow attribute
                                    $('#ccsaleprogress').attr('aria-valuenow', newProgressValue);

                                    // Update progress bar width
                                    $('#ccsaleprogress').css('width', newProgressValue + '%');


                                    $('#currentDrawTotalCCPayment').html(`${response.currentDrawName}<span class="float-right text-muted">${parseFloat(perSale).toFixed(2)}%</span>`);

                                 } else {


                                 }
                              }
                           }

                           do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/TicketSalesAnalysis_services.php");

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
                                    /*var currentkeys = response.currentDraw.map(obj => Object.keys(obj)[0]);
                                    var currentvalues = response.currentDraw.map(obj => Object.values(obj)[0]);*/

                                    var currentSum = response.currentDraw;


                                    /*var pastkeys = response.pastDraw.map(obj => Object.keys(obj)[0]);
                                    var pastvalues = response.pastDraw.map(obj => Object.values(obj)[0]);*/

                                    var pastSum = response.pastDraw

                                    var percentage = ((currentSum - pastSum) / pastSum) * 100;


                                    $('#drawTotalNetPayment').html(`${currentSum}<span class="${(currentSum > pastSum) ? 'text-success' : 'text-danger'} tx-13 ml-2">&nbsp;(${(currentSum > pastSum) ? '+' : '-'}${Math.abs(percentage.toFixed(2))}%)</span>`);
                                    let perSale = 100 - Math.abs(percentage.toFixed(2));
                                    var newProgressValue = parseInt(perSale);

                                    // Update aria-valuenow attribute
                                    $('#netsaleprogress').attr('aria-valuenow', newProgressValue);

                                    // Update progress bar width
                                    $('#netsaleprogress').css('width', newProgressValue + '%');


                                    $('#currentDrawTotalNetPayment').html(`${response.currentDrawName}<span class="float-right text-muted">${parseFloat(perSale).toFixed(2)}%</span>`);

                                 } else {


                                 }
                              }
                           }

                           do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/TicketSalesAnalysis_services.php");

                        }


                        function sumValue(sum) {
                           return sum.reduce((accumulator, currentValue) => accumulator + currentValue, 0)
                        }













                        //   function collect_earning() {

                        //       var formdata = [];

                        //       formdata.push({
                        //           name: 'method',
                        //           value: "collect_earning"

                        //       });

                        //       var post_data = formdata;

                        //       var onsuccess = function(data) {

                        //           var response = JSON.parse(data);

                        //           if (response != "") {

                        //               if (response.type == 1) {

                        //                   document.getElementById('collectdiv').innerHTML = response.output;

                        //               } else {

                        //                   document.getElementById('collectdiv').innerHTML = response.result;

                        //               }

                        //           }

                        //       }

                        //       do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/transaction_services.php");

                        //   }



                        // var options = {
                        //    series: [60], // Progress percentage
                        //    chart: {
                        //       type: 'bar',
                        //       height: 50,
                        //       sparkline: {
                        //          enabled: true
                        //       }
                        //    },
                        //    plotOptions: {
                        //       bar: {
                        //          horizontal: true,
                        //          barHeight: '100%',
                        //          colors: {
                        //             ranges: [{
                        //                from: 0,
                        //                to: 60,
                        //                color: '#6c757d'
                        //             }]
                        //          }
                        //       }
                        //    },
                        //    dataLabels: {
                        //       enabled: false
                        //    },
                        //    xaxis: {
                        //       categories: ['Progress']
                        //    },
                        //    yaxis: {
                        //       max: 100
                        //    }
                        // };

                        // var chart = new ApexCharts(document.querySelector('#progress-bar'), options);
                        // chart.render();



                        function getUnTicket() {
                           $('#ccGet').submit();
                        }




                        // var options7 = {
                        //    series: [75],
                        //    chart: {
                        //       type: 'radialBar',
                        //       width: 50,
                        //       height: 50,
                        //       sparkline: {
                        //          enabled: true
                        //       }
                        //    },
                        //    dataLabels: {
                        //       enabled: false
                        //    },
                        //    plotOptions: {
                        //       radialBar: {
                        //          hollow: {
                        //             margin: 0,
                        //             size: '50%'
                        //          },
                        //          track: {
                        //             margin: 0
                        //          },
                        //          dataLabels: {
                        //             show: false
                        //          }
                        //       }
                        //    },
                        //    colors: ['#FF0000'] // Set the desired color here
                        // };

                        // var chart7 = new ApexCharts(document.querySelector("#chart-7"), options7);
                        // chart7.render();

                        // var options7 = {
                        //    series: [65],
                        //    chart: {
                        //       type: 'radialBar',
                        //       width: 50,
                        //       height: 50,
                        //       sparkline: {
                        //          enabled: true
                        //       }
                        //    },
                        //    dataLabels: {
                        //       enabled: false
                        //    },
                        //    plotOptions: {
                        //       radialBar: {
                        //          hollow: {
                        //             margin: 0,
                        //             size: '50%'
                        //          },
                        //          track: {
                        //             margin: 0
                        //          },
                        //          dataLabels: {
                        //             show: false
                        //          }
                        //       }
                        //    },
                        //    colors: ['#008ffb'] // Set the desired color here
                        // };

                        // var chart7 = new ApexCharts(document.querySelector("#chart-8"), options7);
                        // chart7.render();
                        // var options7 = {
                        //    series: [85],
                        //    chart: {
                        //       type: 'radialBar',
                        //       width: 50,
                        //       height: 50,
                        //       sparkline: {
                        //          enabled: true
                        //       }
                        //    },
                        //    dataLabels: {
                        //       enabled: false
                        //    },
                        //    plotOptions: {
                        //       radialBar: {
                        //          hollow: {
                        //             margin: 0,
                        //             size: '50%'
                        //          },
                        //          track: {
                        //             margin: 0
                        //          },
                        //          dataLabels: {
                        //             show: false
                        //          }
                        //       }
                        //    },
                        //    colors: ['#feb019'] // Set the desired color here
                        // };

                        // var chart7 = new ApexCharts(document.querySelector("#chart-9"), options7);
                        // chart7.render();

                        $(document).ready(function() {
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
                        });
                     </script>