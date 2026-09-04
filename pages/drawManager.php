<?php
   $pageTitle = ucwords(strtolower('Draw Manager'));
   
   ?>
<style>
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
                              <div class="col-lg-3 col-md-3 col-sm-6">
                                 <label for="reportrange">Result Date</label>
                                 <input class="form-control" type="text" id="reportrange" value="" placeholder="YYYY-MM-DD" autocomplete="off" maxlength="10" oninput="this.value = this.value.replace(/[^0-9-]/g, '');" />
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-6 mt-5">
                                 <button type="submit" id="smslogsearch" class="btn btn-info" onclick="searchTicket()">Go</button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- ROW-1 END -->
         <!-- ROW-4 -->
         <div class="row row-sm">
            <div class="col-lg-12">
               <div class="card">
                  <div class="card-header">
                     <div class="col-lg-12 col-md-12 col-sm-12">
                        <h3 class="card-title"><strong>Draw List</strong></h3>
                     </div>
                  </div>
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="sms_report1" style="width:100%;">
                           <thead>
                              <tr>
                                 <th class="wd-15p border-bottom-0">Sale Date</th>
                                 <th class="wd-15p border-bottom-0">Result Date</th>
                                 <th class="wd-20p border-bottom-0">Thrill Prize GOLD (KG)</th>
                                 <th class="wd-20p border-bottom-0">Weekly Booster Prize GOLD (KG)</th>
                                 <th class="wd-20p border-bottom-0">Monthly Bumper Prize GOLD (KG)</th>
                                 <th class="wd-20p border-bottom-0">Today GOLD Rate (AED)</th>
                                 <th class="wd-20p border-bottom-0">Announcement</th>
                                 <!-- <th class="wd-20p border-bottom-0">Details</th>
                                    <th class="wd-20p border-bottom-0">IP Address</th>
                                    <th class="wd-15p border-bottom-0">Reference ID</th>
                                    <th class="wd-15p border-bottom-0">Date & Time</th>
                                    <th class="wd-15p border-bottom-0">Status</th> -->
                                 <!-- <th></th> -->
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
         <!-- ROW-4 END -->
      </div>
      <!-- CONTAINER END -->
   </div>
</div>
<!-- Modal -->
<div class="modal fade" id="WinnerDailyAnnounce" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="winnerModalTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content bo">
         <div class="modal-header">
            <h5 class="modal-title text-white" id="winnerModalTitle"></h5>
            <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
            <button class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
            </button>
         </div>
         <div class="modal-body">
            <form class="row d-flex justify-content-center" id="raffleIDColumn">
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-secondary" id="saveRaffleID">Submit</button>
         </div>
      </div>
   </div>
</div>
<script>
   let drawStatus = ['Active', 'Completed'];
   
   $(function() {
       singleDatePicker('reportrange');
       searchTicket();
   });
   
   const singleDatePicker = (id) => {
       try {
           var start = moment().set({
               hour: 0,
               minute: 0,
               second: 0,
               millisecond: 0
           });
   
           function cb(start) {
               $('#' + id + ' span').html(start.format("YYYY-MM-DD"));
           }
   
           $('#' + id).daterangepicker({
               // startDate: start, // Set start date to null to prevent automatic selection
               singleDatePicker: true,
               // autoApply: true,
               timePicker: false,
               timePicker24Hour: false,
               timePickerSeconds: false,
               autoUpdateInput: false,
               locale: {
                   cancelLabel: 'Clear'
               }
           }, cb);
   
           cb(start);
   
           $('#' + id).on('apply.daterangepicker', function(ev, picker) {
               $(this).val(picker.startDate.format("YYYY-MM-DD"));
           });
   
           $('#' + id).on('cancel.daterangepicker', function(ev, picker) {
               $(this).val('');
           });
       } catch (e) {
           console.log('Error: ' + e.message);
       }
   }
   
   function updateGlodRate(id, todayGoldPrize) {
       try {
           if (id == '' || id == null) {
               showToast('error', 'The Image ID has been missing!', 5000);
               return false;
           }
   
           if (todayGoldPrize == '' || todayGoldPrize == null) {
               showToast('error', 'The Order has been missing!', 5000);
               return false;
           }
   
   
           if (todayGoldPrize < 1) {
               showToast('error', 'The gold prize requires more than 1 AED!', 5000);
               return false;
           }
   
   
   
           Swal.fire({
   
               title: "Are you sure?",
               text: "Do you want to update the order!",
               icon: "warning",
               showCancelButton: true,
               confirmButtonColor: "#3085d6",
               cancelButtonColor: "#d33",
               confirmButtonText: "Yes, update it!",
               allowOutsideClick: false
   
           }).then((result) => {
   
               if (result.isConfirmed) {
   
   
                   var h = new FormData();
                   h.append('method', 'updateGlodRate');
                   h.append('drawID', id);
                   h.append('todayGoldPrize', todayGoldPrize);
                   // h.append('deviceType', deviceType);
   
                   $.ajax({
                       type: 'POST',
                       url: origin + "/ajax/service/drawManagerServices.php", // Replace with the actual server-side script
                       data: h,
                       success: function(response) {
   
   
                           var response = JSON.parse(response);
                           searchTicket();
   
                           if (response != "") {
   
                               if (parseInt(response.type) == 1) {
                                   showToast('success', response.result, 5000);
                               } else if (parseInt(response.type) == 0) {
                                   showToast('error', response.result, 5000);
                               }
   
                           }
   
   
   
   
                       },
                       processData: false,
                       contentType: false,
                       error: function(error) {
   
                           console.error('Error deleting record:', error);
                       }
                   });
   
               }
   
           });
       } catch (e) {
           console.log('Error: ' + e.message);
       }
   
   }
   
   const searchTicket = () => {
   
       try {
           var title = 'Draw List';
           let searchBTN = $(`#smslogsearch`);
   
           var table = $("#sms_report1").DataTable({
               destroy: true,
               pageLength: 10,
               responsive: false,
               // layout: {
               //     topStart: {
               //         buttons: ['colvis']
               //     }
               // },
               order: [
                   [0, 'asc']
               ],
               columnDefs: [{
                   type: 'date',
                   targets: [0, 1] // Assuming the first two columns are date columns
               }],
               paging: true,
               searching: true,
               info: true,
               ajax: {
                   url: origin + '/ajax/service/drawManagerServices.php',
                   method: "POST",
                   dataSrc: "result",
                   data: function(d) {
   
                       // Button Loading
                       searchBTN.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="sr-only">Loading...</span>`).prop('disabled', true);
   
                       //   Request 
                       d.method = 'getAllDraw';
                       d.resultDate = $('#reportrange').val();
                   }
               },
               dom: 'Bfrtip',
               buttons: [
                   'pageLength',
                   'copy',
                   'colvis',
                   // {
                   //     extend: 'copyHtml5',
                   //     title: title
                   // },
                   // {
                   //     extend: 'csvHtml5',
                   //     title: title
                   // },
                   {
                       extend: 'excelHtml5',
                       title: title
                   },
                   // {
                   //     extend: 'pdfHtml5',
                   //     orientation: 'landscape',
                   //     pageSize: 'LEGAL',
                   //     title: title
                   // },
                   // {
                   //     extend: 'print',
                   //     title: title
                   // },
               ],
               columns:
   
                   [
   
                       {
                           data: null,
                           render: function(data, type, row, meta) {
                               return moment(data.saleDate).format("DD MMM YYYY")
                           }
                       },
   
                       {
                           data: null,
                           render: function(data, type, row, meta) {
                               return moment(data.resultDate).format("DD MMM YYYY")
                           }
                       },
   
                       {
                           data: null,
                           render: function(data, type, row, meta) {
                               return drawStatus.includes(data.dailyThirllStatus) ? data.dailyThirllPrice : 'NA'
                           }
                       },
   
                       {
                           data: null,
                           render: function(data, type, row, meta) {
                               return drawStatus.includes(data.weeklyBoosterStatus) ? data.weeklyBoosterPrice : 'NA'
                           }
                       },
   
                       {
                           data: null,
                           render: function(data, type, row, meta) {
                               return data.monthlyBumperPrice != '' && parseInt(data.monthlyBumperPrice) != 0 && drawStatus.includes(data.monthlyBumperStatus) ? data.monthlyBumperPrice : 'NA'
                           }
                       },
   
                       {
                           data: null,
                           render: function(data, type, row, meta) {
                               const todayGoldPrize = Intl.NumberFormat('en-US').format(parseFloat(data.todayGoldPrize));
                               return `<input type="text" style="pointer-events: none;" class="form-control" value="${todayGoldPrize}" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" maxlength="10" ${(data.dailyThirllStatus && data.dailyThirllStatus === 'Active' ? `onfocusout="updateGlodRate(${data.id}, $(this).val())"` : '')}  readonly>`;
                               // return new Intl.NumberFormat('en-US').format(parseFloat(data.todayGoldPrize));
                           }
                       },
   
                       {
                           data: null,
                           render: function(data, type, row, meta) {
                               var btn = '';
   
                               const resultDateString = data.resultDate;
                               const resultDate = moment(resultDateString, 'YYYY-MM-DD');
                               const currentDate = moment();
   
                               // console.log(resultDate.isSame(currentDate, 'day'));
                               if ((resultDate.isSame(currentDate, 'day') || resultDate.isBefore(currentDate, 'day'))) {
                                   if (data.dailyThirllStatus && data.dailyThirllStatus === 'Active') {
                                       btn += `<a style="cursor: pointer;" onclick="WinnerDailyAnnounce(${data.id}, 'Daily Thrill Draw Announcement', 1, 'dailyThrill', '${data.winThirllRaffleIds}')"><span class="fa fa-bullhorn" style="color: #576025db;font-size: 16px;font-weight: bold;">&nbsp;Daily Thrill</span></a>&nbsp;&nbsp;`;
                                   }
   
                                   if (data.weeklyBoosterStatus && data.weeklyBoosterStatus === 'Active') {
                                       // const jsonArray = JSON.parse(data.winConRaffleIds);
                                       btn += `<a style="cursor: pointer;" onclick="WinnerDailyAnnounce(${data.id}, 'Weekly Booster Draw Announcement', 1, 'weeklyBooster', '${data.winweeklyBoosterIds}')"><span class="fa fa-bullhorn" style="color: #28413cdb;font-size: 16px;font-weight: bold;">&nbsp;Weekly Booster</span></a>&nbsp;&nbsp;`;
                                   }
   
                                   if (data.monthlyBumperStatus && data.monthlyBumperStatus === 'Active') {
                                       btn += `<a style="cursor: pointer;" onclick="WinnerDailyAnnounce(${data.id}, 'Monthly Bumper Draw Announcement', 1, 'monthlyBumber', '${data.winBumperRaffleIds}')"><span class="fa fa-bullhorn" style="color: #592560db;font-size: 16px;font-weight: bold;">&nbsp;Monthly Bumper</span></a>&nbsp;&nbsp;`;
                                   }
                                   
                                    if (data.monthlyBumperStatus === 'Completed') {
                                      
                                       btn += `<a href="<?= $adminurl; ?>cron/salesReport1.php?drawid=${data.id}&download=open&sales=yes" target="_blank" ><button class="btn" style="color: blue;"><i class="fa fa-download"></i> Sales Report</button></a>`;
                                       btn += `<a href="<?= $adminurl; ?>cron/salesReport1.php?drawid=${data.id}&download=open&winner=yes" target="_blank"><button class="btn" style="color: #3f3f0d;"><i class="fa fa-download"></i> Winner Report</button></a>`;
                                   }
                               }
   
                               return btn;
                           }
                       },
   
   
   
                   ],
               initComplete: function(settings, json) {
   
                   // Loading Off
                   searchBTN.html(`GO`).prop('disabled', false);
   
                   if (json.type === '0') {
                       showToast("warning", "No data available in table", 5000);
                   }
   
                   // showToast("warning", "No data available in table", 5000);
               }
   
           });
       } catch (e) {
           console.log('Error: ' + e.message);
       }
   }
   
   
   const WinnerDailyAnnounce = (id, title, noRaffleID, drawKeyWord, RaffleIDs) => {
       try {
   
           if (title == '' || title == undefined || title == null) {
               showToast("error", "The Title Missing. Kindly Refresh and Try Again!", 5000);
               return false;
           }
   
           if (id == '' || id == undefined || id == null) {
               showToast("error", "The Title Missing. Kindly Refresh and Try Again!", 5000);
               return false;
           }
   
           if (noRaffleID == '' || noRaffleID == undefined || noRaffleID == null) {
               showToast("error", "The no of raffle id Missing. Kindly Refresh and Try Again!", 5000);
               return false;
           }
   
           if (drawKeyWord == '' || drawKeyWord == undefined || drawKeyWord == null) {
               showToast("error", "The Draw Key Word Missing. Kindly Refresh and Try Again!", 5000);
               return false;
           }
   
           let formData = '';
   
   
   
           var j = 0;
           for (let i = 1; i <= noRaffleID; i++) {
               let RaffleNo = '';
               if (RaffleIDs != undefined && RaffleIDs != null && RaffleIDs != '' && RaffleIDs != 'null') {
                   if (drawKeyWord === 'dailyThrill' || drawKeyWord === 'monthlyBumber') {
                       RaffleNo = RaffleIDs.trim().toUpperCase();
                   } else {
                       const raffle = RaffleIDs.trim().split(',');
                       RaffleNo = raffle[j].trim().toUpperCase();
                   }
               }
   
               formData += `<div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                       <label for="winnerRaffleID${i}" class="col-form-label text-white">Raffle ID:</label>
                       <input type="text" class="form-control raffle-in" autocomplete="off" maxlength="20" oninput="this.value = this.value.replace(/[^0-9A-Za-z]/g, '');" id="winnerRaffleID${i}" name="winnerRaffleID${i}" value="${RaffleNo}">
                       <p style="font-weight: 900;color: red;display:none;" class="raffle-err" id="winnerRaffleID${i}err"></p>
                   </div>`;
               j++;
           }
   
   
           $(`#raffleIDColumn`).html(formData);
           $(`#winnerModalTitle`).text(title);
           $(`#WinnerDailyAnnounce`).modal('show');
           $(`#saveRaffleID`).attr('onclick', `saveRaffleID(${id}, '${drawKeyWord}', ${noRaffleID})`);
   
       } catch (e) {
           console.log('Error: ' + e.message);
       }
   }
   
   const saveRaffleID = (id, drawKeyWord, noRaffleID) => {
       try {
           let saveRaffleIDBTN = $(`#saveRaffleID`);
           $(`.raffle-in`).css('border', 'none');
           $(`.raffle-err`).hide();
   
           if (id == '' || id == undefined || id == null) {
               showToast("error", "The Title Missing. Kindly Refresh and Try Again!", 5000);
               return false;
           }
   
           if (drawKeyWord == '' || drawKeyWord == undefined || drawKeyWord == null) {
               showToast("error", "The Draw Key Word Missing. Kindly Refresh and Try Again!", 5000);
               return false;
           }
   
           if (noRaffleID == '' || noRaffleID == undefined || noRaffleID == null) {
               showToast("error", "The no of raffle id Missing. Kindly Refresh and Try Again!", 5000);
               return false;
           }
   
           var h = [];
           let formData = $(`#raffleIDColumn`).serializeArray();
           let err = 0;
           for (let i = 0; i < noRaffleID; i++) {
               if (formData[i].value == '' || formData[i].value == undefined || formData[i].value == null) {
                   $(`#${formData[i].name}`).css('border', '3px solid red');
                   $(`#${formData[i].name}err`).text('Please Enter the valid Raffle ID!').show();
                   err++;
               }
   
               h.push({
                   name: formData[i].name,
                   value: formData[i].value
               });
   
           }
   
           if (err > 0) {
               return false;
           }
   
           h.push({
               name: 'method',
               value: "WinnerUpdate"
           }, {
               name: 'noRaffleID',
               value: noRaffleID
           }, {
               name: 'drawKeyWord',
               value: drawKeyWord
           }, {
               name: 'drawID',
               value: id
           });
   
   
           $.ajax({
               url: origin + '/ajax/service/drawManagerServices.php',
               type: 'POST',
               data: h,
               beforeSend: function() {
                   // Button Loading
                   saveRaffleIDBTN.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Loading...`).prop('disabled', true);
               },
               success: function(data) {
                   // console.log('Request successful');
   
                   var response = JSON.parse(data);
   
                   if (response != "") {
                       if (response.type == '1') {
                           $(`#WinnerDailyAnnounce`).modal('hide');
                           if (response.result != '' && response.result != null && response.result != undefined) {
                               showToast("success", response.result, 5000, searchTicket);
                           }
                       } else {
                           if (response.result != '' && response.result != null && response.result != undefined) {
                               showToast("error", response.result, 5000);
                           }
   
   
                           if (response.errorRaffleID && response.errorRaffleID.length > 0) {
                               response.errorRaffleID.forEach(function(element) {
                                   $(`#${element.id}`).css('border', '3px solid red');
                                   $(`#${element.id}err`).text(element.error).show();
                               });
                           }
   
                       }
                   } else {
                       showToast("error", "Request failed", 5000);
                       // console.error('Request failed');
   
                   }
                   // Loading Off 
                   saveRaffleIDBTN.html(`Submit`).prop('disabled', false);
               },
               error: function(xhr, status, error) {
                   showToast("error", "Request failed", 5000);
                   saveRaffleIDBTN.html(`Submit`).prop('disabled', false);
                   console.error('Request failed');
                   console.error(xhr, status, error);
               }
           });
           // console.log(formData);
   
       } catch (e) {
           console.log('Error: ' + e.message);
       }
   }
</script>