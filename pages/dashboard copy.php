<?php
   $loginUserID = $_SESSION['memid'];
   $getUSer = mysqli_query($con, "SELECT u.*, r.name AS 'roleName' FROM `user_register` AS u 
   LEFT JOIN role AS r ON r.id = u.roll_id
   WHERE u.`id`='$loginUserID' and u.`deletes`='0'  ORDER BY u.`id` ASC LIMIT 1;");
   if (mysqli_num_rows($getUSer) > 0) {
       $rows = mysqli_fetch_all($getUSer, MYSQLI_ASSOC);
       $roll_name = $rows[0]['roleName'];
   }
   
   ?>
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
<!--app-content open-->
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
      </div>
      <!-- CONTAINER END -->
   </div>
</div>
<form action="paymentstatushistory" id="ccGet" method="post">
   <input type="hidden" name="ccAvenue" value="notGenerated">
   <!-- <input type="submit"> -->
</form>
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
