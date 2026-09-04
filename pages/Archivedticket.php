<?php
   /**
    *      Date            Developer_name      Modifications
    *      13/03/2024      Devanathan          Archived Ticket
    * 
    * */
   
 
   /*$get_coupon = select_query($con, "couponcode", "", "`deletes` = '0' AND c_createdfor IS NOT NULL ORDER BY id DESC", "", "");*/
   ?>
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
            <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Archived Ticket</h1>
            <div>
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Archived Ticket</li>
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
                                 <!--<div class="col-lg-3 col-sm-6 mb-2">-->
                                 <!--   <span>Select User</span> &nbsp; <span style="color:red;">*</span>-->
                                 <!--   <select id="userType" class="form-control">-->
                                 <!--      <option value="customer">Customer</option>-->
                                 <!--      <option value="agent">Agent</option>-->
                                 <!--   </select>-->
                                 <!--</div>-->
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
                        <h3 class="card-title"><strong>Archived Ticket</strong></h3>
                     </div>
                     <div class="col-lg-8">
                     </div>
                  </div>
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="sms_report1" style="width:100%;">
                           <thead>
                              <tr>
                                 <th class="wd-15p border-bottom-0">Name</th>
                                 <th class="wd-15p border-bottom-0">Mobile Number</th>
                                 <th class="wd-15p border-bottom-0">Email ID</th>
                                 <th class="wd-15p border-bottom-0">Ticket No</th>
                                 <th class="wd-20p border-bottom-0">Raffle ID</th>
                                 <th class="wd-20p border-bottom-0">Product Details</th>
                                 <th class="wd-15p border-bottom-0">Transaction ID</th>
                                 <th class="wd-15p border-bottom-0">Amount</th>
                                 <th class="wd-15p border-bottom-0">Purchase Date & Time</th>
                                 <th class="wd-15p border-bottom-0">Expery Date</th>
                                 <th class="wd-15p border-bottom-0">States </th>
                                 <!--<th></th>-->
                              </tr>
                           </thead>
                           <tbody>
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <!--<div class="card-body">-->
                  <!--    <div class="table-responsive">-->
                  <!--        <table class="table table-bordered text-nowrap border-bottom" id="sms_report1" style="width:100%;">-->
                  <!--            <thead>-->
                  <!--                <tr>-->
                  <!--                    <th class="wd-15p border-bottom-0">Name</th>-->
                  <!--                    <th class="wd-15p border-bottom-0">Mobile Number</th>-->
                  <!--                    <th class="wd-15p border-bottom-0">Email ID</th>-->
                  <!--                    <th class="wd-15p border-bottom-0">Ticket No</th>-->
                  <!--                    <th class="wd-20p border-bottom-0">Raffle ID</th>-->
                  <!--                    <th class="wd-20p border-bottom-0">Product Details</th>-->
                  <!--                    <th class="wd-15p border-bottom-0">Purchase Date & Time</th>-->
                  <!--                    <th class="wd-15p border-bottom-0">Action</th>-->
                  <!--<th></th>-->
                  <!--                </tr>-->
                  <!--            </thead>-->
                  <!--            <tbody>-->
                  <!--            </tbody>-->
                  <!--        </table>-->
                  <!--    </div>-->
                  <!--</div>-->
               </div>
            </div>
         </div>
         <!-- ROW-4 END -->
      </div>
      <!-- CONTAINER END -->
   </div>
</div>
<div class="modal" id="myModal">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">Archived Ticket</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
         </div>
         <!-- Modal Body -->
         <div class="modal-body">
            <p id="dataIdDisplay"></p>
         </div>
         <!-- Modal Footer -->
         <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
         </div>
      </div>
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
                      startDate: start.set({
                          
                       hour: 0,
                       minute: 0,
                       second: 0,
                       millisecond: 0
                       
                   }),
   
                   endDate: end.set({
   
                       hour: 23,
                       minute: 59,
                       second: 59,
                       millisecond: 59
   
                   }),
   
   
   
                   timePicker: true,
                   timePicker24Hour: true,
                   timePickerSeconds: true,
   
                   maxSpan: {
   
                       days: 365
   
                   },
   
                   autoUpdateInput: true,
   
                   // minYear: moment().format("YYYY"),
                   // maxYear: moment().add(1, 'years').format("YYYY"),
   
                   maxDate: moment().add(1, 'days').toDate(),
                   ranges: {
   
                       'Today': [moment().set({
   
                           hour: 0,
                           minute: 0,
                           second: 0,
                           millisecond: 0
   
                       }), moment().set({
   
                           hour: 23,
                           minute: 59,
                           second: 59,
                           millisecond: 59
   
                       })],
   
   
   
                       'Yesterday': [moment().subtract(1, 'days').set({
   
                           hour: 0,
                           minute: 0,
                           second: 0,
                           millisecond: 0
   
                       }), moment().subtract(1, 'days').set({
   
                           hour: 23,
                           minute: 59,
                           second: 59,
                           millisecond: 59
   
                       })],
   
   
   
                       'Last 7 Days': [moment().subtract(6, 'days').set({
   
                           hour: 0,
                           minute: 0,
                           second: 0,
                           millisecond: 0
   
                       }), moment().set({
   
                           hour: 23,
                           minute: 59,
                           second: 59,
                           millisecond: 59
   
                       })],
   
   
   
                       'Last 30 Days': [moment().subtract(29, 'days').set({
   
                           hour: 0,
                           minute: 0,
                           second: 0,
                           millisecond: 0
   
                       }), moment().set({
   
                           hour: 23,
                           minute: 59,
                           second: 59,
                           millisecond: 59
   
                       })],
   
   
   
                       'This Month': [moment().startOf('month').set({
   
                           hour: 0,
                           minute: 0,
                           second: 0,
                           millisecond: 0
   
                       }), moment().endOf('month').set({
   
                           hour: 23,
                           minute: 59,
                           second: 59,
                           millisecond: 59
   
                       })],
   
   
   
                       'Last Month': [moment().subtract(1, 'month').startOf('month').set({
   
                           hour: 0,
                           minute: 0,
                           second: 0,
                           millisecond: 0
   
                       }), moment().subtract(1, 'month').endOf('month').set({
   
                           hour: 23,
                           minute: 59,
                           second: 59,
                           millisecond: 59
   
                       })],
   
   
   
                       'This Year': [moment().startOf('year').set({
   
                           hour: 0,
                           minute: 0,
                           second: 0,
                           millisecond: 0
   
                       }), moment().endOf('year').set({
   
                           hour: 23,
                           minute: 59,
                           second: 59,
                           millisecond: 59
   
                       })],
   
   
   
                       'Last Year': [moment().subtract(1, 'year').startOf('year').set({
   
                           hour: 0,
                           minute: 0,
                           second: 0,
                           millisecond: 0
   
                       }), moment().subtract(1, 'year').endOf('year').set({
   
                           hour: 23,
                           minute: 59,
                           second: 59,
                           millisecond: 59
   
                       })]
                   }
   
               }, cb);
   
               cb(start, end); 
   
           }
   
   function searchTicket() {
       
       var table = $('#sms_report1').DataTable();
       table.destroy();
   
       var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
       var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");
       var userType = $('#userType').val();
       var title = 'SMS Reports : (' + formdate + '  to  ' + todate + ')';
       table = $("#sms_report1").DataTable({
   
           pageLength: 10,
   
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
           method: 'archived_ticket',
       fromdate: formdate,
       todate: todate,
       userType: userType,
   
               }
           },
   
       dom: 'Bfrtip',
       buttons: [
       'pageLength',
               // {
   
           //     extend: 'copyHtml5',
   
           //     title: title
   
           // },
   
           {
   
               extend: 'csvHtml5',
               title: title
   
           },
           {
   
               extend: 'excelHtml5',
               title: title
   
           },
           {
   
               extend: 'print',
               title: title
   
           },
   
           ],
   
       columns:
       [
          {
   
           data: null,
   
           render: function(data, type, row, meta) {
               return data.name + ' ' + data.lname;
   
           }
   
       },
       {
   
           data: null,
            render: function(data, type, row, meta) {
   
                           return data.mobile
   
                       }
   
                   },
   
       {
   
           data: null,
       render: function(data, type, row, meta) {
                           return data.email
   
                       }
                   },
   
       {
   
           data: null,
       render: function(data, type, row, meta) {
                           return data.ticketNo
   
                       }
   
                   },
   
      {
   
           data: null,
           render: function(data, type, row, meta) {
               return JSON.parse(data.raffleIds).join(", ");
           }
       },
       {
           data: null,
       render: function(data, type, row, meta) {
                           return data.sale_from
                       }
   
                   },
                   {
   
                     data: null,
                     render: function(data, type, row, meta) {
                         return JSON.parse(data.transactionIds).join(", ");
   
                            }
   
                    },
   
                    {
   
                        data: null,
   
                        render: function(data, type, row, meta) {
   
                          return data.totalAmt
   
                         }
   
                    },          
   
                    {
   
                        data: null,
   
                        render: function(data, type, row, meta) {
   
                           return moment(data.purchaseDatetime).format("DD MMM YYYY hh:mm a")
   
                       }
   
                   },
   
                   {
   
                        data: null,
   
                        render: function(data, type, row, meta) {
   
                             return data.endDate
   
                        }
   
                     },   
   
                   // {
               
                   //     data: null,
               
                   //     render: function(data, type, row, meta) {
               
                   //         return '<button onclick="openInvoicePage(\'' + data.ticketNo + '\')">Invoice</button>';
               
                   //     }
               
                   // }
   
                        {
                   
                           data: null,
                   
                                       render: function(data, type, row, meta) {
                   
                                           var Turl = origin + "/ticketHistoryPay/list/" + data.id;
                   
                                           //   var Turl = origin + "/invoicelist.php?ticketNo=" + data.ticketNo;
                   
                                           return   `
                                           <a class="btn text-danger btn-sm" style="cursor: pointer;" data-bs-original-title="Revoke">
                   <span class="fa fa-undo" style="color: red; font-size: 18px;" onclick="revokeTicket(${data.id})"></span>
                   </a>
                   
                                           `;
                   
                                       }
                                   },
   
       ],
   
       });
   }
   
   
   
   
   
   $('#myModal').on('show.bs.modal', function (event) {
   
       var button = $(event.relatedTarget); // Button that triggered the modal
   
       var dataId = button.data('id'); // Extract data-id attribute from the button
   
       var modal = $(this);
   
       
   
        $.ajax({
   
           url: url,
   
           method: "POST",
   
           data: { 
   
               method: 'list_oticket_Ticket_History',
   
               id: dataId
   
           }, 
   
           success: function(response) {
   
   // Parse the JSON response
   
   var data = JSON.parse(response);
   
   // Update the modal body with the data
   
   if (data.length > 0) {
   
       var invoice = data[0]; // Assuming the first item in the data array contains customer information
   
       var html = '<table class="table">';
   
       html += '<tr><td>Name</td><td>' + invoice.user_id + '</td></tr>';
   
       html += '<tr><td>totalAmt</td><td>' + invoice.totalAmt + '</td></tr>';
   
       html += '<tr><td>taxValue</td><td>' + invoice.taxValue + '</td></tr>';
   
       html += '<tr><td>Total</td><td>' + invoice.netTotal + '</td></tr>';
   
       html += '</table>';
   
       modal.find('.modal-body').html(html);
   
   } else {
   
       modal.find('.modal-body').html('No data found');
   
   }
   
   },
   
   
   
           error: function(xhr, status, error) {
   
               console.error(error);
   
               // Handle error
   
           }
   
       });
   
       
   
   });
   
   
   
   function revokeTicket(id) {
   
   
   
   swal.fire({
   
   
   
   title: 'Revoke Reason',
   
   input: 'textarea',
   
   showCancelButton: true,
   
   allowOutsideClick: false,
   
   confirmButtonText: 'Revoke Ticket',
   
   cancelButtonText: 'Close',
   
   
   
   }).then(function(result) {
   
   if (result.isConfirmed) {
   
   if (result.value != '') {
   
   	var formdata = [];
   
   	formdata.push({
   
   		name: 'method',
   
   		value: "delete_NDticket"
   
   	});
   
   
   
   	formdata.push({
   
   		name: 'transid',
   
   		value: id
   
   	});
   
   	formdata.push({
   
   		name: 'message',
   
   		value: result.value
   
   	});
   
   	var post_data = formdata;
   
   	var onsuccess = function(data) {
   
   		var response = JSON.parse(data);
   
   		if (response != "") {
   
   
   
   			if (response.type == 1) {
   
   				toast('success', response.result);
   
   				viewtable();
   
   
   
   			} else {
   
   				toast('error', response.result);
   
   			}
   
   		}
   
   	}
   
   	do_ajax_call(post_data, onsuccess, url);
   
   
   
   } else {
   
   	toast('error', 'Please Fill the Reason');
   
   
   
   }
   
   }
   
   
   
   });
   
   
   
   }
   
   searchTicket();
   
   
   
</script>