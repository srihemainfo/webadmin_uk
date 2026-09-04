<?php
   /**
    * 
    *      Date            Developer     Changes
    *      
    *      05-03-2024      Devanathan   Winner List page creat
    * 
    * */
   $pageTitle = "winner list";
   
   ?>
<style>
   .card-header.d-lg-flex.d-block.justify-content-between {
   border-bottom: none;
   }
   input,
   select {
   border: 1px solid #CCC;
   /* width: 250px; */
   }
   .nav.product-sale {
   position: unset;
   top: -3rem;
   right: 5px;
   margin: 12px 0;
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
   .swal-modal {
   border: 3px solid white;
   color: #fff;
   }
   .swal-button {
   background-color: #07f3a2 !important;
   }
   .swal-text {
   font-weight: 600 !important;
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
   
   
   
       var page_origin = window.location.origin;
   
   
   
       let anchor = document.getElementById("anchor");
   
   
   
       anchor.href = page_origin;
   
   
   
   }
</script>
<!--app-content open-->
<div class="main-content app-content mt-0">
   <div class="side-app">
      <input type="hidden" id="tabID" value="agents">
      <!-- CONTAINER -->
      <div class="main-container container-fluid mt-5 pt-5">
         <!-- PAGE-HEADER -->
         <div class="page-header pt-5">
            <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Tickets</h1>
            <div>
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Winner List</li>
               </ol>
            </div>
         </div>
         <!-- PAGE-HEADER END -->
         <!-- ROW-1 -->
         <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
               <div class="card overflow-hidden">
                  <div class="card-body">
                     <div class="d-flex1">
                        <div class="mt-2">
                           <div class="row">
                              <div class="col-sm-12">
                                 <span>Search Tickets</span>
                                 <input class="form-control" type="text" oninput="winnerList($(this).val())" id="searchTxt" placeholder="Ticket ID">
                              </div>
                              <?php
                                 // Fetch all draws satisfying the condition
                                 $drawCondition = "`dailyThirllStatus` = 'Completed' and `deletes`='0' ORDER BY `id` DESC";
                                 $draw = select_query($con, "draw", "", $drawCondition, "", "");
                                 
                                 if ($draw['nr'] > 0) {
                                     foreach ($draw['result'] as $key => $value) {
                                         $naeme = explode("#", $value['resultDate']);
                                         $drawDate = date("D", strtotime($value['resultDate']));
                                         echo '<option value="' . $value['dailyDrawNo'] . '">Draw No #' . str_pad($value['dailyDrawNo'], 3, "0", STR_PAD_LEFT) . ' - ' . str_replace("Draw", "", $naeme[0]) . ' (' . $drawDate . ')</option>';
                                     }
                                 }
                                 ?>
                              <!--<div class="col-sm-12 col-md-6 col-lg-4">-->
                              <!--    <span>Select Ticket</span>-->
                              <!--<select id="ticket_name" onchange="winnerList()" class="form-select">-->
                              <!--        <select id="ticket_name" class="form-select">-->
                              <!--        <option value="">Select Ticket</option>-->
                              <!--        <option value="OT">Online Ticket</option>-->
                              <!--        <option value="AT">Agent Ticket</option>-->
                              <!--        <option value="CT">Coupon Ticket</option>-->
                              <!--    </select>-->
                              <!--</div>-->
                              <div class="col-sm-12 col-md-6 col-lg-4">
                              </div>
                              <div class="col-sm-12 col-md-6 col-lg-4">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-4" id="searcherr">
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
               <div class="card-body pt-4">
                  <div class="grid-margin">
                     <div class="panel panel-primary">
                        <div class="tab-menu-heading border-0 p-0">
                           <div class="tabs-menu1">
                              <!-- Tabs -->
                              <ul class="nav panel-tabs product-sale">
                                 <li><a href="#tab1" id="Thirll_Draw" class="text-dark active" data-bs-toggle="tab" onclick="winnerList('Thirll_Draw')">Daily Thirll Draw</a></li>
                                 <li><a href="#tab2" id="Consolation_Draw" class="text-dark" data-bs-toggle="tab" onclick="winnerList('Consolation_Draw')">Daily Consolation Draw</a></li>
                                 <li><a href="#tab3" id="Bumper_Draw" class="text-dark" data-bs-toggle="tab" onclick="winnerList('Bumper_Draw')">Monthly Bumper Draw</a></li>
                              </ul>
                           </div>
                        </div>
                        <div class="panel-body tabs-menu-body border-0 pt-0">
                           <div class="tab-content">
                              <div class="tab-pane active" id="tab1">
                                 <div class="card-header d-lg-flex d-block justify-content-between">
                                    <div class="mb-2 text-center" id="ldfullre">
                                    </div>
                                    <div class="mb-2 text-center" id="repcpdfbtn">
                                    </div>
                                 </div>
                                 <div class="table-responsive">
                                    <table class="table table-bordered text-nowrap border-bottom" id="Thirll_Draw_winner_list_report" style="width:100%;">
                                       <thead>
                                          <tr>
                                             <th class="column_sort sorting sorting_asc">Name</th>
                                             <th class="column_sort sorting sorting_asc">Email</th>
                                             <th class="column_sort sorting sorting_asc">Mobile</th>
                                             <th class="column_sort sorting sorting_asc">Raffle Number</th>
                                             <th class="column_sort sorting sorting_asc">Ticket ID</th>
                                             <th class="column_sort sorting sorting_asc">Gold Prize(g)</th>
                                             <th class="column_sort sorting sorting_asc">Gold Prize Amount</th>
                                             <th class="column_sort sorting sorting_asc">Sold By</th>
                                             <th class="column_sort sorting sorting_asc">Action</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                       </tbody>
                                       <tfoot>
                                          <tr>
                                             <th colspan="4"></th>
                                             <th style="text-align:right">Total:</th>
                                             <th></th>
                                             <th colspan="2"></th>
                                          </tr>
                                       </tfoot>
                                    </table>
                                 </div>
                              </div>
                              <div class="tab-pane" id="tab2">
                                 <div class="card-header d-lg-flex d-block justify-content-between">
                                    <div class="mb-2 text-center" id="ldfullre1">
                                    </div>
                                    <div class="mb-2 text-center" id="repcpdfbtn1">
                                    </div>
                                 </div>
                                 <div class="table-responsive">
                                    <table class="table table-bordered text-nowrap border-bottom" id="Consolation_Draw_winner_list_report" style="width:100%;">
                                       <thead>
                                          <tr>
                                             <th class="column_sort sorting sorting_asc">Name</th>
                                             <th class="column_sort sorting sorting_asc">Email</th>
                                             <th class="column_sort sorting sorting_asc">Mobile</th>
                                             <th class="column_sort sorting sorting_asc">Raffle Number</th>
                                             <th class="column_sort sorting sorting_asc">Ticket ID</th>
                                             <th class="column_sort sorting sorting_asc">Gold Prize(g)</th>
                                             <th class="column_sort sorting sorting_asc">Gold Prize Amount</th>
                                             <th class="column_sort sorting sorting_asc">Sold By</th>
                                             <th class="column_sort sorting sorting_asc">Action</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                       </tbody>
                                       <tfoot>
                                          <tr>
                                             <th colspan="4"></th>
                                             <th style="text-align:right">Total:</th>
                                             <th></th>
                                             <th colspan="1"></th>
                                             <th></th>
                                          </tr>
                                       </tfoot>
                                    </table>
                                 </div>
                              </div>
                              <div class="tab-pane" id="tab3">
                                 <div class="card-header d-lg-flex d-block justify-content-between">
                                    <div class="mb-2 text-center" id="ldfullre1">
                                    </div>
                                    <div class="mb-2 text-center" id="repcpdfbtn1">
                                    </div>
                                 </div>
                                 <div class="table-responsive">
                                    <table class="table table-bordered text-nowrap border-bottom" id="Bumper_Draw_winner_list_report" style="width:100%;">
                                       <thead>
                                          <tr>
                                             <th class="column_sort sorting sorting_asc">Name</th>
                                             <th class="column_sort sorting sorting_asc">Email</th>
                                             <th class="column_sort sorting sorting_asc">Mobile</th>
                                             <th class="column_sort sorting sorting_asc">Raffle Number</th>
                                             <th class="column_sort sorting sorting_asc">Ticket ID</th>
                                             <th class="column_sort sorting sorting_asc">Gold Prize(g)</th>
                                             <th class="column_sort sorting sorting_asc">Gold Prize Amount</th>
                                             <th class="column_sort sorting sorting_asc">Sold By</th>
                                             <th class="column_sort sorting sorting_asc">Action</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                       </tbody>
                                       <tfoot>
                                          <tr>
                                             <th colspan="4"></th>
                                             <th style="text-align:right">Total:</th>
                                             <th></th>
                                             <th colspan="2"></th>
                                          </tr>
                                       </tfoot>
                                    </table>
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
      <!-- ROW-4 END -->
   </div>
   <!-- CONTAINER END -->
</div>
<div class="modal fade" id="uploadimg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Upload Image</h5>
         </div>
         <div class="modal-body">
            <div id="resuld"></div>
         </div>
         <!-- <div class="modal-footer">
            </div> -->
      </div>
   </div>
</div>
<div class="modal fade" id="deleteinfo" tabindex="-1" role="dialog" aria-labelledby="customModalLabel" aria-modal="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="customModalLabel">Delete</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
         </div>
         <div class="modal-body">
            <div class="text-center" id="dele">
            </div>
         </div>
         <div class="modal-footer custom">
            <div class="right-side">
               <button aria-label="Close" class="btn btn-danger pd-x-25 success" onclick="refersh()" data-bs-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>
</div>
<!--app-content close-->
<script>
   var origin = window.location.origin;
   
   
   
   var url = origin + "/ajax/service/report_services.php";
   
   
   
   var ajax_url = origin + "/ajax/service/result_services.php";
   
   var drawType = $(".tabs-menu1 ul>li>a.active").attr('id');
   
   
   
   
   
   $(function() {
   
   
   
       // winnerList();
   
   
   
   })();
   
   
   
   
   
   
   
   
   
   function winnerList(drawType, searchTxt = '') {
   document.getElementById('searcherr').innerHTML = '';
   
   let url, method;
   if (drawType === "Thirll_Draw") {
       url = origin + "/ajax/service/report_services.php"; // Replace with actual URL for Thirll Draw report
       method = 'dailyticket';
   } else if (drawType === "Consolation_Draw") {
       url = origin + "/ajax/service/report_services.php";// Replace with actual URL for Consolation Draw report
       method = 'Consolationticket';
   } else if (drawType === "Bumper_Draw") {
       url = origin + "/ajax/service/report_services.php"; // Replace with actual URL for Bumper Draw report
       method = 'Bumperticket';
   }
   
   let filename = 'Winner List ' + $('#draw_new_id option:selected').text() + $('#ticket_name option:selected').text() + $('#search_id option:selected').text();
   
   // Initialize DataTable
   let table = $('#' + drawType + '_winner_list_report').DataTable({
       destroy: true,
       pageLength: 10,
       order: [],
       paging: true,
       searching: true,
       info: true,
       ajax: {
           url: url,
           method: "POST",
           dataSrc: function (json) {
               // Return data from the JSON response
               return json.data;
           },
           data: function (d) {
               // Customize AJAX data based on drawType and searchTxt
               return $.extend({}, d, {
                   method: method,
                   draw_id: $('#draw_new_id').val(),
                   searchTxt: (searchTxt != '') ? searchTxt : $('#searchTxt').val()
               });
           }
       },
       order: [
           [4, 'asc']
       ],
       dom: 'Bfrtip',
       buttons: [
           'pageLength',
           {
               extend: 'copyHtml5',
               title: filename
           },
           {
               extend: 'csvHtml5',
               title: filename
           },
           {
               extend: 'excelHtml5',
               title: filename
           },
           {
               extend: 'pdfHtml5',
               orientation: 'landscape',
               pageSize: 'LEGAL',
               title: filename
           }, 'print',
       ],
       columns: [{
               data: "name"
           },
           {
               data: "email"
           },
           {
               data: "mobile"
           },
           {
               data: "RaffleNO"
           },
           {
               data: "TicketID"
           },
           {
               data: "prize"
           },
           {
               data: "prizeamt"
           },
           {
               data: "soldby"
           },
           {
               data: "action"
           },
       ],
       footerCallback: function (row, data, start, end, display) {
           var api = this.api();
           var intVal = function (i) {
               return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
           };
           total = api
               .column(6)
               .data()
               .reduce(function (a, b) {
                   let x = intVal(a) + intVal(b);
                   return x.toFixed(2);
               }, 0);
           pageTotal = api
               .column(6, {
                   page: 'current'
               })
               .data()
               .reduce(function (a, b) {
                   return intVal(a) + intVal(b);
               }, 0);
           $(api.column(6).footer()).html('' + pageTotal + ' ( ' + total + ' total)');
       }
   });
   }
   
   
   
   // function winnerList2(searchTxt = '') {
   
   
   
   //     var drawType = $(".tabs-menu1 ul>li>a.active").attr('id');
       
   //     alert('kkkkkkk');
   
   
   
   
   //     // var table = $('#' + drawType + '_winner_list_report').DataTable();
   //     // table.destroy();
   //     document.getElementById('searcherr').innerHTML = '';
   //     let draw_new_id = $('#draw_new_id').val();
   //     let ticket_name = $('#ticket_name').val();
   //     let search_id = $('#search_id').val();
   //     // if (draw_new_id != '') {
   
   //     $('#ldfullre, #repcpdfbtn').html('');
   //     $('#ldfullre1, #repcpdfbtn1').html('');
   
   //     let filename = 'Winner List ' + $('#draw_new_id option:selected').text() + $('#ticket_name option:selected').text() + $('#search_id option:selected').text();
       
   //     table = $('#' + drawType + '_winner_list_report').DataTable({
   
   
   //         destroy: true,
   //         pageLength: 10,
   //         order: [],
   //         paging: true,
   //         searching: true,
   //         info: true,
   //         ajax: {
   //             url: url,
   //             method: "POST",
   //             dataSrc: "",
   //             data: {
   //                 method: 'winnerList',
   //                 draw_id: draw_new_id,
   //                 draw_type: drawType,
   //                 ticket_name: ticket_name,
   
   //                 search_id: search_id,
   //                 searchTxt: (searchTxt != '') ? searchTxt : $('#searchTxt').val()
   //             }
   //         },
   //         order: [
   //             [4, 'asc']
   //         ],
   //         dom: 'Bfrtip',
   //         buttons: [
   //             'pageLength',
   //             {
   //                 extend: 'copyHtml5',
   //                 title: filename
   //             },
   //             {
   //                 extend: 'csvHtml5',
   //                 title: filename
   //             },
   //             {
   //                 extend: 'excelHtml5',
   //                 title: filename
   //             },
   //             {
   //                 extend: 'pdfHtml5',
   //                 orientation: 'landscape',
   //                 pageSize: 'LEGAL',
   //                 title: filename
   //             }, 'print',
   //         ],
   
   
   
   //         columns: [{
   
   //                 data: "name"
   //             },
   //             {
   //                 data: "email"
   //             },
   //             {
   //                 data: "mobile"
   //             },
   
   //             {
   //                 data: "My3number"
   //             },
   //             {
   //                 data: "prize"
   //             },
   //             {
   //                 data: "prizeamt"
   //             },
   //             {
   //                 data: "soldby"
   //             },
   //             {
   //                 data: "action"
   //             },
   //         ],
   
   
   
   //         footerCallback: function(row, data, start, end, display) {
   //             var api = this.api();
   
   //             // Remove the formatting to get integer data for summation
   
   //             var intVal = function(i) {
   //                 return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
   
   //             };
   
   //             // Total over all pages
   
   //             total = api
   
   //                 .column(5)
   //                 .data()
   //                 .reduce(function(a, b) {
   //                     let x = intVal(a) + intVal(b);
   //                     return x.toFixed(2);
   //                 }, 0);
   //             // Total over this page
   //             pageTotal = api
   
   //                 .column(5, {
   
   //                     page: 'current'
   
   //                 })
   
   //                 .data()
   //                 .reduce(function(a, b) {
   //                     return intVal(a) + intVal(b);
   //                 }, 0);
   //             // Update footer
   //             $(api.column(5).footer()).html('' + pageTotal + ' ( ' + total + ' total)');
   
   //         }
   //     });
       
   // }
   
   // var origin = window.location.origin;
   
   
   function uploadimg(id, draw = '') {
   
       var formdata = [];
   
       formdata.push({
           name: 'method',
           value: "result_uplaod2"
       }, {
           name: 'drawName',
           value: draw
       }, {
   
           name: 'id',
           value: id
   
       });
       var post_data = formdata;
       var onsuccess = function(data) {
           var response = JSON.parse(data);
           if (response != "") {
               if (response.type == 1) {
   
                   document.getElementById('resuld').innerHTML = response.result;
                   $('#uploadimg').modal('show');
               } else {
                   document.getElementById('resuld').innerHTML = response.result;
   
                   $('#uploadimg').modal('show');
   
               }
           }
       }
       do_ajax_call(post_data, onsuccess, ajax_url);
   }
   
   function uploadimg1(id, draw = '') {
   
       var formdata = [];
   
       formdata.push({
           name: 'method',
           value: "result_uplaod3"
       }, {
           name: 'drawName',
           value: draw
       }, {
   
           name: 'id',
           value: id
   
       });
       var post_data = formdata;
       var onsuccess = function(data) {
           var response = JSON.parse(data);
           if (response != "") {
               if (response.type == 1) {
   
                   document.getElementById('resuld').innerHTML = response.result;
                   $('#uploadimg').modal('show');
               } else {
                   document.getElementById('resuld').innerHTML = response.result;
   
                   $('#uploadimg').modal('show');
   
               }
           }
       }
       do_ajax_call(post_data, onsuccess, ajax_url);
   }
   
   function move_new_file1(id, draw = '') {
   
       let file = document.getElementById('photos').value;
       var formData = new FormData();
       formData.append('method', 'move_file1');
   
       formData.append('drawName', draw);
   
       formData.append('id', id);
   
       formData.append('Country', $('#Country').val());
   
       formData.append('residingCountry', $('#residingCountry').val());
   
       formData.append('custname', $('#custname').val());
   
       formData.append('mfile', photos.files[0]);
   
       // console.log(formData);
       $.ajax({
   
           url: ajax_url,
           type: 'post',
           data: formData,
           success: function(response) {
               // var response = JSON.parse(data);
               if (response != "") {
                   if (response.type == '1') {
                       $('#uploadimg').modal('hide');
                       toast('success', response.result);
   
                   } else {
                       // toast('error', response.result);
                       toast('error', 'Kindly Fill the Fields!');
   
                   }
               }
           },
           processData: false,
   
           contentType: false
       });
   }
   
   
   
   
   function move_new_file(id, draw = '') {
   
       let file = document.getElementById('photos').value;
       var formData = new FormData();
       formData.append('method', 'move_file');
   
       formData.append('drawName', draw);
   
       formData.append('id', id);
   
       formData.append('Country', $('#Country').val());
   
       formData.append('residingCountry', $('#residingCountry').val());
   
       formData.append('custname', $('#custname').val());
   
       formData.append('mfile', photos.files[0]);
   
       // console.log(formData);
       $.ajax({
   
           url: ajax_url,
           type: 'post',
           data: formData,
           success: function(response) {
               // var response = JSON.parse(data);
               if (response != "") {
                   if (response.type == '1') {
                       $('#uploadimg').modal('hide');
                       toast('success', response.result);
   
                   } else {
                       // toast('error', response.result);
                       toast('error', 'Kindly Fill the Fields!');
   
                   }
               }
           },
           processData: false,
   
           contentType: false
       });
   }
   
   function deleteinfo(id, draw = '') {
       var formdata = [];
       formdata.push({
           name: 'method',
           value: "delete_image_now"
       }, {
           name: 'drawName',
           value: draw
       }, {
           name: 'id',
           value: id
       });
   
   
   
       var post_data = formdata;
   
       var onsuccess = function(data) {
           var response = JSON.parse(data);
           if (response != "") {
               document.getElementById('dele').innerHTML = response.result;
               $('#deleteinfo').modal('show');
           } else {
               document.getElementById('dele').innerHTML = response.result;
   
               $('#deleteinfo').modal('show');
           }
       }
   
       do_ajax_call(post_data, onsuccess, ajax_url);
   
   }
   
   function toast(icon, message) {
       const Toast = Swal.mixin({
           toast: true,
           position: 'top-end',
           showConfirmButton: false,
           timer: 5000,
           timerProgressBar: true,
           didOpen: (toast) => {
               toast.addEventListener('mouseenter', Swal.stopTimer)
               toast.addEventListener('mouseleave', Swal.resumeTimer)
   
           }
       })
   
       Toast.fire({
           icon: icon,
           title: message
   
       })
   }
   
   
   // function refersh() {
   //     location.reload();
   
   // }
   
   function preview(event) {
       var frame = document.getElementById("frame");
   
       if (event.target.files && event.target.files[0]) {
           frame.src = URL.createObjectURL(event.target.files[0]);
           $(`#frame`).show();
       }
   }
   
   
   
</script>