<style>
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
   
       var page_origin = window.location.origin;
   
       let anchor = document.getElementById("anchor");
   
       anchor.href = page_origin;
   
   }
</script>
<div class="main-content app-content mt-0">
   <div class="side-app">
      <input type="hidden" id="tabID" value="agents">
      <!-- CONTAINER -->
      <div class="main-container container-fluid">
         <!-- PAGE-HEADER -->
         <div class="page-header">
            <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>SMS Report</h1>
            <div>
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">SMS Report</li>
               </ol>
            </div>
         </div>
         <!-- PAGE-HEADER END -->
         <!-- ROW-1 -->
         <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
               <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                     <div class="card overflow-hidden">
                        <div class="card-body">
                           <div class="mt-2">
                              <div class="row">
                                 <div class="col-lg-3 col-sm-6 mb-2">
                                    <span>Select Date</span> &nbsp; <span style="color:red;">*</span>
                                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                       <i class="fa fa-calendar"></i>&nbsp;
                                       <span></span> <i class="fa fa-caret-down"></i>
                                    </div>
                                 </div>
                                 <div class="col-lg-3 col-sm-6 mb-2">
                                    <span>Select SMS Gateway</span>
                                    <select id="gatewayname" onchange="changestatus($(this).val())" class="form-select">
                                        <option value="" selected>All</option>
                                        <!--<option value="cequens">Cequens</option>-->
                                        <option value="brandmaster">Brandmaster</option>
                                       
                                       <!--<option value="cequens">Cequens</option>-->
                                       <!--<option value="expresso">Expresso</option>-->
                                       <!--<option value="dataslice">Dataslice</option>-->
                                       <!--<option value="precise">Precise</option>-->
                                       <option value="twilio">Twilio</option>
                                       
                                    </select>
                                 </div>
                                 <div class="col-lg-3 col-sm-6 mb-2">
                                    <span>Select Status</span>
                                    <select id="statusget" class="form-select">
                                       <option value="All" selected>All</option>
                                       <option value="Unchecked">Unchecked</option>
                                       <?php
                                          $selectQuery = mysqli_query($con, "SELECT smsstatus FROM `smslog` WHERE smsstatus  != '' AND  `gateway` IN ('cequens', 'dataslice', 'expresso', 'precise', 'firebase') GROUP BY smsstatus ORDER BY `smslog`.`smsstatus` ASC");
                                          if (mysqli_num_rows($selectQuery) > 0) {
                                          
                                              while ($row = mysqli_fetch_assoc($selectQuery)) {
                                          ?>
                                       <option value="<?= $row['smsstatus']; ?>"><?= $row['smsstatus']; ?></option>
                                       <?php
                                          }
                                          }
                                          
                                          ?>
                                    </select>
                                 </div>
                                 <div class="col-lg-3 col-sm-6 mb-2">
                                    <br>
                                    <button type="submit" id="smslogsearch" class="btn btn-info" onclick="searchTicket()">Go</button>
                                 </div>
                              </div>
                              <br>
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
                     <div class="col-lg-4">
                        <h3 class="card-title"><strong>SMS Report</strong></h3>
                     </div>
                     <div class="col-lg-8">
                     </div>
                  </div>
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="sms_report1" style="width:100%;">
                           <thead>
                              <tr>
                                 <th class="wd-15p border-bottom-0">Mobile Number</th>
                                 <th class="wd-15p border-bottom-0">Gateway Name</th>
                                 <th class="wd-20p border-bottom-0">Details</th>
                                 <th class="wd-20p border-bottom-0">IP Address</th>
                                 <th class="wd-15p border-bottom-0">Reference ID</th>
                                 <th class="wd-15p border-bottom-0">Date & Time</th>
                                 <th class="wd-15p border-bottom-0">Status</th>
                                 <!--<th></th>-->
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
<script>
   var origin = window.location.origin;
   
   var url = origin + "/ajax/service/datatable_services.php";
   
   
   
   
   
   $(function() {
   
   
   
       createDatePricket('reportrange');
       searchTicket();
   
   });
   
   
   function createDatePricket(id) {
       var start = moment();
       var end = moment();
   
       function cb(start, end) {
           $('#' + id + ' span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));
       }
       $('#' + id).daterangepicker({
           startDate: start,
           endDate: end,
           maxSpan: {
               days: 15
           },
           minDate: moment().subtract(30, "days").format("MM/DD/YYYY"),
           maxDate: moment().format("MM/DD/YYYY"),
           ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 15 Days': [moment().subtract(14, 'days'), moment()],
               // 'This Month': [moment().startOf('month'), moment().endOf('month')],
               // 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
           }
       }, cb);
       cb(start, end);
   }
   
   function searchTicket() {
   
   
   
       var table = $('#sms_report1').DataTable();
   
       table.destroy();
   
   
       var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
       var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");
   
       var title = 'SMS Reports : (' + formdate + '  to  ' + todate + ')';
   
       table = $("#sms_report1").DataTable({
   
           pageLength: 10,
           // responsive: {
           //     details: {
           //         type: 'column',
           //         target: -1,
           //     }
           // },
           // columnDefs: [{
           //     targets: -1,
           //     orderable: false,
           //     searchable: true,
           //     className: 'control',
           // }, {
           //     targets: 0,
           //     orderable: false,
           //     searchable: true,
           //     className: 'selectall-checkbox',
           // }],
           // select: {
           //     style: 'multi',
           //     selector: 'td:first-child',
           // },
           order: [
               [5, 'desc']
           ],
           columnDefs: [{
                   type: 'date',
                   targets: [5]
               } // Assuming the date column is at index 2
           ],
           paging: true,
           searching: true,
           info: true,
           ajax: {
               url: url,
               method: "POST",
               dataSrc: "",
               data: {
                   method: 'sms_report',
                   agdate: formdate,
                   todate: todate,
                   gatewayname: $('#gatewayname').val(),
                   statusget: $('#statusget').val()
               }
           },
           dom: 'Bfrtip',
           buttons: [
               'pageLength',
               'copy',
              
               {
                   extend: 'excelHtml5',
                   title: title
               },
             
           ],
           columns:
   
               [
   
                   {
                       data: null,
                       render: function(data, type, row, meta) {
                           return data.mobile
                       }
                   },
   
                   {
                       data: null,
                       render: function(data, type, row, meta) {
                           return data.gateway.charAt(0).toUpperCase() + data.gateway.slice(1)
                       }
                   },
   
                   {
                       data: null,
                       render: function(data, type, row, meta) {
                           return `<textarea readonly>${data.details}</textarea>`
                       }
                   },
   
                   {
                       data: null,
                       render: function(data, type, row, meta) {
                           return data.ip
                       }
                   },
                   {
                       data: null,
                       render: function(data, type, row, meta) {
                           return data.reference_id
                       }
                   },
   
                   {
                       data: null,
                       render: function(data, type, row, meta) {
                           return moment(data.datetime).format("DD MMM YYYY hh:mm a")
                       }
                   },
   
                   {
                       data: null,
                       render: function(data, type, row, meta) {
                           let status = data.smsstatus == '' ? 'UNCHECKED' : data.smsstatus.toUpperCase();
                           return `<span style="color: ${(status == 'UNCHECKED' ?  'blueviolet' : 'green')}; font-weight: bolder;">${status}</span>`
                       }
                   },
   
               ],
   
       });
   
   }
   
   function changestatus(val) {
       $("#statusget").val('All').change();
       // if (val == 'cequens') {
   
       // } else if (val == 'expresso') {
   
       // } else {
       //     $("#statusget").val('All').change();
   
       // }
       // console.log(val);
   }
</script>