<?php
   //    Date       Developer_name      Modifications    
   
   //    14-2-2023   Prakash            SMS Switch Option Development Finished
   //    12-4-2023   Prakash            Data slice has been added
   $pageTitle = 'SMS Settings';
   
   $settings1 = mysqli_query($con, "SELECT * FROM `sms_switch` WHERE `site` = 'adminsite' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1");
   $adminsite = mysqli_fetch_assoc($settings1);
   
   $settings2 = mysqli_query($con, "SELECT * FROM `sms_switch` WHERE `site` = 'customersite' AND `deletes` = '0' ORDER BY `id` DESC LIMIT 1");
   $customersite = mysqli_fetch_assoc($settings2);
   
   ?>
<div class="main-content app-content mt-0">
<div class="side-app">
   <div class="main-container container-fluid">
      <div class="page-header">
         <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a><?= ucwords($pageTitle); ?></h1>
         <div>
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
               <li class="breadcrumb-item active" aria-current="page"><?= ucwords($pageTitle); ?></li>
            </ol>
         </div>
      </div>
      <div class="row">
         <div class="col-xl-6">
            <form id="editprofileform">
               <div class="card ">
                  <div class="card-header">
                     <h3 class="card-title">Admin Site</h3>
                  </div>
                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              <!--<input type="radio" id="expresso" name="smsgateway" <?= ($adminsite['gateway'] == 'expresso') ? 'checked' : ''; ?> value="expresso" disabled>-->
                              <!--<label for="expresso">Expresso</label><br>-->
                              <!--<input type="radio" id="cequens" name="smsgateway" <?= ($adminsite['gateway'] == 'cequens') ? 'checked' : ''; ?> value="cequens">-->
                              <!--<label for="cequens">Cequens</label><br>-->
                              <!--<input type="radio" id="dataslice" name="smsgateway" <?= ($adminsite['gateway'] == 'dataslice') ? 'checked' : ''; ?> value="dataslice" disabled>-->
                              <!--<label for="dataslice">Data Slice</label><br>-->
                              <!--<input type="radio" id="precise" name="smsgateway" <?= ($adminsite['gateway'] == 'precise') ? 'checked' : ''; ?> value="precise">-->
                              <!--<label for="precise">Precise</label><br>-->
                              
                              <input type="radio" id="brandmaster" name="smsgateway" <?= ($customersite['gateway'] == 'brandmaster') ? 'checked' : ''; ?> value="brandmaster">
                              <label for="brandmaster">Brandmaster</label><br>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card-footer text-end">
                     <button type="button" onclick="changeSmsGateWay('admin', 'smsgateway')" class="btn btn-success bg-success-gradient my-1">Update</button>
                  </div>
               </div>
            </form>
         </div>
         <div class="col-xl-6">
            <form id="editprofileform">
               <div class="card ">
                  <div class="card-header">
                     <h3 class="card-title">Customer Site</h3>
                  </div>
                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              <!--<input type="radio" id="expressocus" name="smsgatewaycus" <?= ($customersite['gateway'] == 'expresso') ? 'checked' : ''; ?> value="expresso" disabled>-->
                              <!--<label for="expressocus">Expresso</label><br>-->
                              <!--<input type="radio" id="cequenscus" name="smsgatewaycus" <?= ($customersite['gateway'] == 'cequens') ? 'checked' : ''; ?> value="cequens">-->
                              <!--<label for="cequenscus">Cequens</label><br>-->
                              <!--<input type="radio" id="datasliceus" name="smsgatewaycus" <?= ($customersite['gateway'] == 'dataslice') ? 'checked' : ''; ?> value="dataslice" disabled>-->
                              <!--<label for="datasliceus">Data Slice</label><br>-->
                              <!--<input type="radio" id="preciseus" name="smsgatewaycus" <?= ($customersite['gateway'] == 'precise') ? 'checked' : ''; ?> value="precise">-->
                              <!--<label for="preciseus">Precise</label><br>-->
                              
                             <input type="radio" id="brandmaster" name="smsgatewaycus" <?= ($customersite['gateway'] == 'brandmaster') ? 'checked' : ''; ?> value="brandmaster">
                             <label for="brandmaster">Brandmaster</label><br>
                              
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card-footer text-end">
                     <button type="button" onclick="changeSmsGateWay('customer', 'smsgatewaycus')" class="btn btn-success bg-success-gradient my-1">Update</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
      <div class="row row-sm">
         <div class="col-lg-12">
            <div class="card">
               <div class="card-header">
                  <div class="row align-items-center">
                     <div class="col-4">
                        <h3 class="card-title"><?= ucwords('SMS History'); ?></h3>
                     </div>
                     <div class="col-7">
                        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"><i class="fa fa-calendar"></i>&nbsp;<span></span> <i class="fa fa-caret-down"></i>
                        </div>
                     </div>
                     <div class="col-1">
                        <button class="btn btn-info" onclick="viewtable()">Go</button>
                     </div>
                  </div>
               </div>
               <div class=" card-body">
                  <table class=" table-responsive table table-bordered text-nowrap border-bottom" id="drawtable" style="width:100%;">
                     <thead>
                        <tr>
                           <th class="wd-15p border-bottom-0">History ID</th>
                           <th class="wd-15p border-bottom-0">Site</th>
                           <th class="wd-15p border-bottom-0">Gateway</th>
                           <th class="wd-15p border-bottom-0">Changed BY</th>
                           <th class="wd-15p border-bottom-0">Created AT</th>
                           <th class="wd-15p border-bottom-0">Deleted At</th>
                        </tr>
                     </thead>
                     <tbody>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   $(function() {
   
       createDatePricket('reportrange');
   
       viewtable();
   
   
   
   });
   
   
   
   function viewtable() {
   
   
   
   
   
       var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
   
       var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");
   
   
   
       var table = $('#drawtable').DataTable();
   
       table.destroy();
   
   
   
       var title = 'SMS History (' + formdate + '  to  ' + todate + ' )';
   
       table = $("#drawtable").DataTable({
   
           pageLength: 10,
   
           order: [
   
               [0, 'desc']
   
           ],
   
           paging: true,
   
           searching: true,
   
           info: true,
   
           ajax: {
   
               url: window.location.origin + "/ajax/service/winners_email.php",
   
               method: "POST",
   
               dataSrc: "",
   
               data: {
   
                   method: 'smsswitchhistory',
   
                   formdate: formdate,
   
                   todate: todate
   
               }
   
           },
   
           dom: 'Bfrtip',
   
           buttons: [
   
               'pageLength',
   
               'copy',
   
               {
   
                   extend: 'csvHtml5',
   
                   title: title
   
               },
   
               {
   
                   extend: 'excelHtml5',
   
                   title: title
   
               },
   
               {
   
                   extend: 'pdfHtml5',
   
                   orientation: 'portrait',
   
                   pageSize: 'A4',
   
                   title: title
   
   
   
               }, 'print',
   
           ],
   
           columns: [{
   
                   data: 'id'
   
               },
   
               {
   
   
   
                   data: 'Site'
   
   
   
               },
   
               {
   
   
   
                   data: 'Gateway'
   
   
   
               },
   
   
   
               {
   
   
   
                   data: 'changedby'
   
   
   
               },
   
               {
   
   
   
                   data: 'createdon'
   
   
   
               },
   
               {
   
   
   
                   data: 'deletedon'
   
   
   
               }
   
   
   
           ],
   
   
   
   
   
   
   
   
   
       });
   
   }
   
   
   
   function toast(icon, message) {
   
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
   
   
   
   }
   
   
   
   function changeSmsGateWay(site, id) {
   
       let smsgatway = $("input[type='radio'][name='" + id + "']:checked").val();
   
   
   
       if (smsgatway != '' && smsgatway != undefined) {
   
           var formdata = [];
   
           formdata.push({
   
               name: 'method',
   
               value: "changeSmsGateWay"
   
           });
   
           formdata.push({
   
               name: 'site',
   
               value: site
   
           });
   
           formdata.push({
   
               name: 'gateway',
   
               value: smsgatway
   
           });
   
           var post_data = formdata;
   
   
   
           var onsuccess = function(data) {
   
               var response = JSON.parse(data);
   
               if (response != "") {
   
                   if (response.type == '1') {
   
                       viewtable();
   
                       toast('success', response.result);
   
                   } else {
   
                       toast('error', response.result);
   
                   }
   
   
   
               }
   
           }
   
   
   
           do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/winners_email.php");
   
       } else {
   
           toast('error', 'Kidnly Select SMS GateWay!');
   
       }
   
   }
   
   
   
   
   
   function createDatePricket(id) {
   
       var start = moment();
   
       var end = moment();
   
   
   
       function cb(start, end) {
   
           $('#' + id + ' span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));
   
       }
   
       $('#' + id).daterangepicker({
   
           startDate: start,
   
           endDate: end,
   
           ranges: {
   
               'Today': [moment(), moment()],
   
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
   
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
   
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
   
               'This Month': [moment().startOf('month'), moment().endOf('month')],
   
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
   
           }
   
       }, cb);
   
       cb(start, end);
   
   
   
   }
</script>