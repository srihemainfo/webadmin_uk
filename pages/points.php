<!--
   1.modifications unknown
   
   
   Date      Developer_name      Modifications  Enddate
   -->
<style>
   input,
   button {
   height: 35px;
   margin: 0;
   padding: 6px 10px;
   border-radius: 2px;
   font-family: inherit;
   font-size: 100%;
   color: inherit;
   }
   textarea {
   height: 100px;
   width: 100%;
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
   span.fa.fa-money {
   color: blue;
   font-size: 16px;
   font-weight: bolder;
   }
   span.fa.fa-ban {
   font-size: 16px;
   font-weight: bolder;
   color: red;
   }
   button {
   color: #FFF;
   background-color: #428BCA;
   border: 1px solid #357EBD;
   }
   .tabs-menu1 ul li a {
   display: block;
   color: #282f53;
   padding: 5px !important;
   margin-top: -7px !important;
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
   .fs-37 {
    font-size: 40px !important;
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
      <input type="hidden" id="tabid" value="pointtable">
      <!-- CONTAINER -->
      <div class="main-container container-fluid">
         <!-- PAGE-HEADER -->
         <div class="page-header">
            <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Points</h1>
            <div>
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Points</li>
               </ol>
            </div>
         </div>
         <!-- PAGE-HEADER END -->
         <!-- ROW-1 -->
         <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
               <div class="row">
                  <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
                     <div class="card bg-primary img-card box-primary-shadow">
                        <div class="card-body">
                           <div class="d-flex">
                              <div class="text-white">
                                 <h2 class="mb-0 number-font" id="total_point"></h2>
                                 <p class="text-white mb-0">Total Points </p>
                              </div>
                              <div class="ms-auto"> <i class="fa fa-university text-white fs-37 me-2 mt-2"></i> </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <?php if ($_SESSION['memid'] != 1 ) { ?>
                  <!--<div class="col-sm-6 col-md-6 col-lg-6 col-xl-8" style="float: right;">-->
                  <!--   <div class="r-point" style="float: right;">-->
                  <!--      <button type="button" onclick="getleaderdata()" data-bs-toggle="modal" data-bs-target="#pointreq" class="btn btn-primary btn-sm d-block">Request Points</button>-->
                  <!--   </div>-->
                  <!--</div>-->
                  <?php } ?>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-12 col-sm-12">
               <div class="card">
                  <div class="card-header">
                     <div class="col-lg-4 point-agn">
                        <h3 class="card-title"><strong id="tabtitle2">Point Transaction History</strong></h3>
                     </div>
                     <div class="row">
                     <div class="col-lg-3">
                         <span>Select Date</span> &nbsp; <span style="color:red;">*</span>
                         <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                         </div>
                       
                     </div>
                     <div class="col-lg-4">
                         <br>
                       
                        <button onclick="viewtable()">GO</button>
                     </div>
                     </div>
                  </div>
                  <div class="card-body pt-4">
                     <div class="grid-margin">
                        <div class="">
                           <div class="panel panel-primary">
                              <div class="tab-menu-heading border-0 p-0">
                                 <div class="tabs-menu1">
                                    <!-- Tabs -->
                                    <ul class="nav panel-tabs product-sale">
                                       <li><a href="#tab7" class="active" data-bs-toggle="tab" onclick="settabid_new('pointtable', 'Point Transaction History')">Point Transaction History</a></li>
                                       <li><a href="#tab8" data-bs-toggle="tab" class="text-dark" onclick="settabid_new('requesttable', 'Request History')">Request</a></li>
                                       <li><a href="#tab9" data-bs-toggle="tab" class="text-dark" onclick="settabid_new('rejecttable', 'Reject History')">Reject</a></li>
                                    </ul>
                                 </div>
                              </div>
                              <div class="panel-body tabs-menu-body border-0 pt-0">
                                 <div class="tab-content">
                                    <div class="tab-pane active" id="tab7">
                                       <div class="table-responsive">
                                          <table class="table table-bordered text-nowrap border-bottom" id="pointtable" style="width:100%;">
                                             <thead>
                                                <tr>
                                                   <th class="wd-15p border-bottom-0">Date & Time</th>
                                                   <th class="wd-15p border-bottom-0">Seller Name</th>
                                                   <th class="wd-15p border-bottom-0">To Name</th>
                                                   <th class="wd-15p border-bottom-0">Points Transaction</th>
                                                   <th class="wd-15p border-bottom-0">Payment Type</th>
                                                   <th class="wd-15p border-bottom-0">Order Type</th>
                                                   <th class="wd-15p border-bottom-0">Transaction ID</th>
                                                   <th class="wd-20p border-bottom-0">Status</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                    <div class="tab-pane" id="tab8">
                                       <div class="table-responsive">
                                          <table id="requesttable" class="table table-bordered text-nowrap mb-0">
                                             <thead class="border-top">
                                                <tr>
                                                   <th class="bg-transparent border-bottom-0">Requested Date</th>
                                                   <th class="bg-transparent border-bottom-0">Agent Name</th>
                                                   <th class="bg-transparent border-bottom-0">Points</th>
                                                   <th class="bg-transparent border-bottom-0">Request Id</th>
                                                   <th class="bg-transparent border-bottom-0">Status</th>
                                                   <th class="bg-transparent border-bottom-0" style="width: 5%;">ACTION</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                    <div class="tab-pane" id="tab9">
                                       <div class="table-responsive">
                                          <table id="rejecttable" class="table table-bordered text-nowrap mb-0">
                                             <thead class="border-top">
                                                <tr>
                                                   <th class="bg-transparent border-bottom-0">Requested Date</th>
                                                   <th class="bg-transparent border-bottom-0">Rejected BY</th>
                                                   <th class="bg-transparent border-bottom-0">Rejected To</th>
                                                   <th class="bg-transparent border-bottom-0">Points</th>
                                                   <th class="bg-transparent border-bottom-0">Request Id</th>
                                                   <th class="bg-transparent border-bottom-0">Reason</th>
                                                   <th class="bg-transparent border-bottom-0">Status</th>
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
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal  fade" id="withdraw" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Withdrawal Request</h5>
            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-sm-12">
                  <form class="login100-form validate-form">
                     <div class="wrap-input100 validate-input input-group">
                        <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                        <i class="side-menu__icon fa fa-money"></i>
                        </a>
                        <input class="input100 border-start-0 ms-0 form-control" type="text" placeholder="Enter Amount">
                     </div>
                     <div class="wrap-input100 validate-input input-group">
                        <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                        <i class="fa fa-key" aria-hidden="true"></i>
                        </a>
                        <input class="input100 border-start-0 ms-0 form-control" type="password" placeholder="Password">
                     </div>
                  </form>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-secondary">Request</button>
         </div>
      </div>
   </div>
</div>
<div class="modal  fade" id="pointreq" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Point Request</h5>
            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div id="errrequest"></div>
            <input type="hidden" id="leaderreid">
            <div id="pointrediv"></div>
         </div>
         <div class="modal-footer">
            <button class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            <div id="pointdivbtn">
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal  fade" id="pointtransfer" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Point Transfer</h5>
            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div id="erter"></div>
            <input type="hidden" id="pointrequestid">
            <div class="row" id="pointdiv">
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="modalclose('pointtransfer')">Close</button>
            <div id="tbtn">
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal  fade" id="pointreject" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Point Request Reject</h5>
            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div id="erterNre"></div>
            <textarea id="deletemessage" cols="30" rows="10" placeholder="Reason"></textarea>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="modalclose('pointreject')">Close</button>
            <div id="trbtn">
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   var origin = window.location.origin;
   
   var url = origin + "/ajax/service/transaction_services.php";
   
   
   
   $(function() {
       
       createDatePricket('reportrange');
       viewtable();
       total_earning();
   
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
        timePicker: true,
        timePicker24Hour: true,
        timePickerSeconds: true,
        maxSpan: {
            days: 365
        },
        autoUpdateInput: true,
        maxDate: moment().add(1, 'days').toDate(),
        ranges: {
            
            'Today': [moment().startOf('day'), moment().endOf('day')],
            'Yesterday': [moment().subtract(1, 'day').startOf('day'), moment().subtract(1, 'day').endOf('day')],
            'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
            'This Month': [moment().startOf('month').startOf('day'), moment().endOf('month').endOf('day')],
            'Last Month': [moment().subtract(1, 'month').startOf('month').startOf('day'), moment().subtract(1, 'month').endOf('month').endOf('day')],
            'This Year': [moment().startOf('year').startOf('day'), moment().endOf('year').endOf('day')],
            'Last Year': [moment().subtract(1, 'year').startOf('year').startOf('day'), moment().subtract(1, 'year').endOf('year').endOf('day')]
        }
    }, cb);

    cb(start, end);
    // AgentList1();
}
   
   
   
   function modalclose(id) {
   
       $('#' + id + '').modal('hide');
   
       location.reload();
   
   }
   
   function settabid_new(name, tabname) {
   
   
   
       if (name == 'pointtable') {
   
           var table = $('#pointtable').DataTable();
   
           table.destroy();
   
           document.getElementById('tabid').value = name;
   
           document.getElementById('tabtitle2').innerText = tabname;
   
       } else if (name == 'requesttable') {
   
           var table = $('#requesttable').DataTable();
   
           table.destroy();
   
           document.getElementById('tabid').value = name;
   
           document.getElementById('tabtitle2').innerText = tabname;
   
       } else {
   
           var table = $('#rejecttable').DataTable();
   
           table.destroy();
   
           document.getElementById('tabid').value = name;
   
           document.getElementById('tabtitle2').innerText = tabname;
   
       }
       viewtable();
   
   }
   
   function pointTransfer(requestid) {
   
//   alert(requestid);
   
       document.getElementById('pointrequestid').value = requestid;
   
       $("#pointtransfer").modal('show');
   
   
   
       var formdata = [];
   
       formdata.push({
   
           name: 'method',
   
           value: "pointTransferDetails"
   
       });
   
       formdata.push({
   
           name: 'pointrequestid',
   
           value: requestid
   
       });
   
   
   
   
   
       var post_data = formdata;
   
       var onsuccess = function(data) {
   
   
   
           var response = JSON.parse(data);
   
           if (response != "") {
   
               if (response.type == 1) {
   
                   document.getElementById('pointdiv').innerHTML = response.result;
   
                   document.getElementById('tbtn').innerHTML = response.btn;
   
               } else {
   
                   document.getElementById('pointdiv').innerHTML = response.result;
   
               }
   
   
   
           }
   
       }
   
       do_ajax_call(post_data, onsuccess, url);
   
   }
   
   function pointReject(requestid) {
   
   
       $('#trbtn').html(`<button class="btn btn-primary" onclick="rejecttopoint('${requestid}')">Submit</button>`);
   
   
       $("#pointreject").modal('show');
   
   }
   function transfertopoint(id) {
       
    //   alert(id);
   
   
   
       document.getElementById('erter').innerHTML = '';
   
       let topoint = document.getElementById('topoint').value;
   
       if (topoint < 1) {
           $('#erter').html('<div class="alert alert-warning" role="alert">Minimum amount should be 1 AED.</div>');
           return false;
       }
   
   
       if (topoint != '') {
           document.getElementById('tbtn').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';
   
           var formdata = [];
   
           formdata.push({
   
               name: 'method',
               value: "transfertopoint"
   
           });
           formdata.push({
               name: 'pointrequestid',
               value: id
           });
           formdata.push({
               name: 'topoint',
               value: topoint
           });
   
           var post_data = formdata;
           var onsuccess = function(data) {
   
               var response = JSON.parse(data);
               if (response != "") {
                   if (response.type == 1) {
                       document.getElementById('tbtn').innerHTML = '';
                       document.getElementById('pointdiv').innerHTML = response.result;
                   } else {
   
                       document.getElementById('tbtn').innerHTML = response.tbtn;
   
                       document.getElementById('erter').innerHTML = response.result;
   
                   }
   
   
   
               }
   
           }
   
           do_ajax_call(post_data, onsuccess, url);
   
       } else {
   
           document.getElementById('erter').innerHTML = '<div class="alert alert-warning" role="alert">Please Enter the Point</div>';
   
       }
   
   
   
   }
   
   function total_earning() {
   
       var formdata = [];
   
       formdata.push({
   
           name: 'method',
   
           value: "total_points"
   
       });
   
       var post_data = formdata;
   
       var onsuccess = function(data) {
   
   
   
           var response = JSON.parse(data);
   
           if (response != "") {
   
               if (response.type == 1) {
   
                   document.getElementById('total_point').innerText = 'AED ' + response.result;
   
               } else {
   
                   document.getElementById('total_point').innerText = response.result;
   
               }
   
   
   
           }
   
       }
   
       do_ajax_call(post_data, onsuccess, url);
   
   }
   
   function viewtable() {
       
       var tablename = document.getElementById('tabid').value;
       
    //   alert(tablename);
       var table = $('#' + tablename + '').DataTable();
   
       table.destroy();
       var formdata = [];
       formdata.push({
   
           name: 'method',
           value: "list_points_trans"
   
       });
   
       formdata.push({
           name: 'tablename',
           value: tablename
       });
       
       var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
       var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");
   
    //   let formdate = $('#formdate').val();
    //   let todate = $('#todate').val();
   
   
   
       formdata.push({
   
           name: 'formdate',
           value: formdate
   
       }, {
   
           name: 'todate',
           value: todate
   
       });
   
       formdata.push();
       var post_data = formdata;
       var onsuccess = function(data) {
           var response = JSON.parse(data);
           if (response != "") {
               if (response.type == 1) {
                   if (tablename == 'pointtable') {
                       $('#pointtable').DataTable({
                           order: [
   
                               [0, 'desc']
   
                           ],
   
                           dom: 'Bfrtip',
   
                           buttons: [
   
                               'pageLength',
                               'copy',
   
                              
                               {
                                   extend: 'excelHtml5',
                                   title: 'Points Report : (' + formdate + ' to ' + todate + ')'
                               },
                               
                           ],
                           "data": response.result,
                           "columns": [
                               {
                                   'data': 'date'
                               },
                               {
                                   'data': 'cusname'
                               },
                               {
                                   'data': 'toname'
                               },
                               {
                                   'data': 'points'
                               },
                               {
                                   'data': 'paymenttype'
                               },
                               {
                                   'data': 'ordertype'
                               },
                               {
                                   'data': 'transid'
                               },
                               {
                                   'data': 'status'
                               }
                           ],
                       });
                   } else if (tablename == 'requesttable') {
   
                       $('#requesttable').DataTable({
                           order: [
                               [0, 'desc']
                           ],
                           dom: 'Bfrtip',
   
                           buttons: [
   
                               'copy',  'excel', 
   
                           ],
   
                           "data": response.result,
   
                           "columns": [
                               {
                                   'data': 'date'
                               },
                               {
                                   'data': 'name'
                               },
                               {
                                   'data': 'points'
                               },
                               {
                                   'data': 'requestid'
                               },
                               {
                                   'data': 'status'
                               },
                               {
                                   'data': 'action'
                               }
                           ],
                       });
                   } else {
   
                       $('#rejecttable').DataTable({
                           order: [
                               [0, 'desc']
   
                           ],
   
                           dom: 'Bfrtip',
                           buttons: [
   
                               'copy',  'excel',
   
                           ],
   
                           "data": response.result,
   
                           "columns": [
                               {
                                   'data': 'date'
                               },
                               {
                                   'data': 'aname'
                               },
                               {
                                   'data': 'name'
                               },
                               {
                                   'data': 'points'
                               },
                               {
                                   'data': 'requestid'
                               },
                               {
                                   data: null,
                                   render: function(data, type, row, meta) {
                                       return `<textarea readonly="">${data.reason}</textarea>`
                                   }
                               },
                               //   {
                               //                                     'data': 'reason'
                               //                                 },
                               {
                                   'data': 'status'
                               }
                           ],
                       });
                   }
               } else {
   
                   var table = $('#' + tablename + '').DataTable();
                   table.clear().draw();
               }
           }
       }
   
       do_ajax_call(post_data, onsuccess, url);
   
   }
   
   function getleaderdata() {
   
       var formdata = [];
   
       formdata.push({
   
           name: 'method',
   
           value: "getleader_data"
   
       });
   
       var post_data = formdata;
   
       var onsuccess = function(data) {
   
   
   
           var response = JSON.parse(data);
   
           if (response != "") {
   
               if (response.type == 1) {
   
                   document.getElementById('pointrediv').innerHTML = response.reoutput;
   
                   document.getElementById('pointdivbtn').innerHTML = response.pointbtn;
   
                   // document.getElementById('leaderreid').value = response.id;
   
               } else {
   
                   document.getElementById('leanername').innerText = response.result;
   
               }
   
   
   
           }
   
       }
   
       do_ajax_call(post_data, onsuccess, url);
   
   }
   
   function pointrequest() {
   
       document.getElementById('errrequest').innerHTML = '';
   
       let point = document.getElementById('totpoint').value;
   
       if (point != '') {
   
           var formdata = [];
   
           formdata.push({
   
               name: 'method',
   
               value: "point_request"
   
           });
   
           formdata.push({
   
               name: 'point',
   
               value: point
   
           });
   
           var post_data = formdata;
   
           var onsuccess = function(data) {
   
   
   
               var response = JSON.parse(data);
   
               if (response != "") {
   
                   if (response.type == 1) {
   
                       document.getElementById('pointdivbtn').innerHTML = '';
   
                       document.getElementById('pointrediv').innerHTML = '<div class="alert alert-success" role="alert">' + response.result + '</div>';
   
                   } else {
   
                       document.getElementById('errrequest').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';
   
                   }
   
   
   
               }
   
           }
   
           do_ajax_call(post_data, onsuccess, url);
   
       } else {
   
           document.getElementById('errrequest').innerHTML = '<div class="alert alert-danger" role="alert">Please Enter Points!</div>';
   
       }
   
   }
   
   function rejecttopoint(id) {
   
   
   
       $('#erterNre').html('');
   
       let topoint = document.getElementById('deletemessage').value;
   
   
   
       if (topoint == '') {
           $('#erterNre').html(`<div class="alert alert-warning" role="alert">Please Enter the Reasons</div>`);
           return false;
       }
   
       var formdata = [];
   
   
   
       formdata.push({
   
           name: 'method',
   
           value: "rejectToPoint"
   
       }, {
   
           name: 'pointrequestid',
   
           value: id
   
       }, {
   
           name: 'topoint',
   
           value: topoint
   
       });
   
       var btn = $('#trbtn').html();
       $('#trbtn').html(`<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>`);
   
   
       var post_data = formdata;
   
       var onsuccess = function(data) {
   
   
   
           var response = JSON.parse(data);
   
           if (response != "") {
   
               if (response.type == 1) {
   
                   $('#trbtn').html(btn);
                   $("#pointreject").modal('hide');
                   toast('success', response.result);
   
                   location.reload();
               } else {
   
   
                   $('#trbtn').html(btn);
                   $('#erterNre').html(response.result);
               }
   
   
   
           }
   
       }
   
       do_ajax_call(post_data, onsuccess, url);
   
   
   }
   
   function toast(e, t) {
   
       let r = Swal.mixin({
   
           toast: !0,
   
           position: "top-end",
   
           showConfirmButton: !1,
   
           timer: 5e3,
   
           timerProgressBar: !0,
   
           didOpen(e) {
   
               e.addEventListener("mouseenter", Swal.stopTimer), e.addEventListener("mouseleave", Swal.resumeTimer)
   
           }
   
       });
   
       r.fire({
   
           icon: e,
   
           title: t
   
       })
   
   }
</script>