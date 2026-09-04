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
            <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>ND Bank History</h1>
            <div>
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">ND Bank</li>
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
                  <?php if ($_SESSION['memid'] != 1) { ?>
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
                           <span>Search User</span>
                           <input class="form-control" type="text" oninput="viewtable($(this).val())" id="searchTxt" placeholder="Name / Email / Mobile No">
                           <!--<input class="form-control" type="text"  id="searchTxt" placeholder="Name / Email / Mobile No">-->
                        </div>
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
                                                   <th class="wd-15p border-bottom-0">Name</th>
                                                   <th class="wd-15p border-bottom-0">Mobile</th>
                                                   <th class="wd-15p border-bottom-0">Email</th>
                                                   <th class="wd-15p border-bottom-0">ND Bank Balance</th>
                                                   <th class="wd-15p border-bottom-0">Opening Balance</th>
                                                   <th class="wd-15p border-bottom-0">Points Transfered</th>
                                                   <th class="wd-15p border-bottom-0">Closing Balance</th>
                                                   <th class="wd-20p border-bottom-0">Status</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                             </tbody>
                                              <tfoot>
                                <!--<tr>-->
                                                <!--<th></th>-->
                                                <!--<th></th>-->
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align:right">Total:</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                    
                                 
                                <!--</tr>-->
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
         </div>
      </div>
   </div>
</div>
<script>
   var origin = window.location.origin;
   
   var url = origin + "/ajax/service/transaction_services.php";
   var url_1 = origin + "/ajax/service/transaction_services111111.php";
   
   
   
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
       
   
    
     let search_id1 = $('#searchTxt').val();
       var table = $('#' + tablename + '').DataTable();
   
    //   alert(search_id1);
    
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
       
        formdata.push({
           name: 'search_id1',
           value: search_id1
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
   
                              ['desc']
   
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
                                   'data': 'name'
                               },
                               {
                                   'data': 'moble'
                               },
                               {
                                   'data': 'email'
                               },
                               {
                                   'data': 'ND_Bank'
                               },
                               {
                                   'data': 'opening_balance'
                               },
                               {
                                   'data': 're_point'
                               },
                               {
                                   'data': 'wallet_balance'
                               },
                               {
                                   'data': 'status'
                               }
                           ],
                           footerCallback: function(row, data, start, end, display) { 
                                var api = this.api();
                                // Remove the formatting to get integer data for summation
                                var intVal = function(i) {
                                    return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                                };
                                // Total over all pages
                                total = api
                                    .column(6)
                                    .data()
                                    .reduce(function(a, b) {
                                        let x = intVal(a) + intVal(b);
                                        return x.toFixed(2);
                                    }, 0);
                                // Total over this page
                                pageTotal = api
                                    .column(6, {
                                        page: 'current'
                                    })
                                    .data()
                                    .reduce(function(a, b) {
                                        return intVal(a) + intVal(b);
                                    }, 0);
                                // Update footer
                                $(api.column(6).footer()).html('' + pageTotal + ' ( ' + total + ' total)');
                
                            }
                            
                            
                       });
                   } 
               } else {
   
                   var table = $('#' + tablename + '').DataTable();
                   table.clear().draw();
               }
           }
       }
   
       do_ajax_call(post_data, onsuccess, url_1);
   
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