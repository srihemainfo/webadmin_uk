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

<?php
$NDticketquery = mysqli_query($con, "SELECT id, rate FROM product WHERE type = 'product'");

// Output data as JSON
$output = [];
if (mysqli_num_rows($NDticketquery) > 0) {
   while ($row = mysqli_fetch_assoc($NDticketquery)) {
      $output[] = $row;
   }
}
// echo json_encode($output);

?>

<div class="main-content app-content mt-0">
   <div class="side-app">
      <input type="hidden" id="tabID" value="agents">
      <!-- CONTAINER -->
      <div class="main-container container-fluid">
         <!-- PAGE-HEADER -->
         <div class="page-header">
            <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>ND Invoice</h1>
            <div>
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">ND Invoice</li>
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
                                    <span>Search Invoice</span>
                                    <input class="form-control" type="text" oninput="searchTicket($(this).val())" id="searchTxt" placeholder="Name / Email / Mobile No">
                                    <!--<input class="form-control" type="text"  id="searchTxt" placeholder="Name / Email / Mobile No">-->
                                 </div>
                                 <div class="col-lg-3 col-sm-6 mb-2">
                                    <span>Select Date</span> &nbsp; <span style="color:red;"></span>
                                    <div>
                                       <!--<input type="text" name="datefilter" value="" />-->
                                       <input class="form-control" type="text" id="datefilter" name="datefilter" value="" placeholder="YYYY-MM-DD" autocomplete="off" maxlength="10" oninput="this.value = this.value.replace(/[^0-9-]/g, '');" />
                                    </div>

                                 </div>
                                 <div class="col-lg-3 col-sm-6 mb-2">
                                    <span>Select Purchase Type</span> &nbsp; <span style="color:red;"></span>
                                    <select id="userType" class="form-control">
                                       <option value="" selected>Select Option</option>
                                       <option value="New">New</option>
                                       <option value="Renewal">Renewal</option>
                                       <option value="Agent">Agent</option>
                                       <option value="Wallet">Wallet</option>
                                       <option value="Discount_code">Discount code</option>
                                       <option value="flatAgent">Agent Flat Discount</option>
                                    </select>
                                 </div>
                                 <div class="col-lg-3 col-sm-6 mb-2">
                                    <span>Select Category</span> &nbsp; <span style="color:red;"></span>
                                    <select id="category" class="form-control">
                                       <option value="" selected>Select Category</option>
                                       <?php foreach ($output as $item) : ?>
                                          <option value="<?php echo $item['id']; ?>">AED <?php echo $item['rate']; ?></option>
                                       <?php endforeach; ?>
                                    </select>
                                 </div>
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
                     <h3 class="card-title"><strong>ND Invoice</strong></h3>
                  </div>
                  <div class="col-lg-8">
                  </div>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-bordered text-nowrap border-bottom" id="sms_report1" style="width:100%;">
                        <thead>
                           <tr>
                              <th class="wd-15p border-bottom-0">Transaction ID</th>
                              <th class="wd-15p border-bottom-0">Customer Name</th>
                              <th class="wd-15p border-bottom-0">Mobile Number</th>
                              <th class="wd-15p border-bottom-0">Email ID</th>
                              <th class="wd-15p border-bottom-0">Purchase Date & Time</th>
                              <!--<th class="wd-20p border-bottom-0">Raffle ID</th>-->
                              <th class="wd-20p border-bottom-0">Product Category</th>
                              <th class="wd-20p border-bottom-0">Quantity</th>
                              <th class="wd-15p border-bottom-0">Offer Type</th>
                              <th class="wd-15p border-bottom-0">Discount AED</th>
                              <th class="wd-20p border-bottom-0">Grand Total</th>
                              <th class="wd-20p border-bottom-0">payment Type</th>
                              <th class="wd-15p border-bottom-0">Purchase Type</th>
                              <th class="wd-15p border-bottom-0">Agent Name</th>
                              <th class="wd-15p border-bottom-0">Action</th>
                              <!--<th></th>-->
                           </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                           <tr>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th style="text-align:right">Total:</th>
                              <th></th>

                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                           </tr>
                        </tfoot>
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
<!--</div>-->
<div class="modal" id="myModal">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Remaining Data</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body" id="modalContent">
            <!-- Remaining data will be displayed here -->
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
<script>
   var origin = window.location.origin;



   var url = origin + "/ajax/service/datatable_services.php";
   var e_url = origin + "/ajax/service/NDemail_services.php";



   $(function() {
      // createDatePricket('reportrange');
      searchTicket();

   });

   $(function() {

      $('input[name="datefilter"]').daterangepicker({
         autoUpdateInput: false,
         locale: {
            cancelLabel: 'Clear'
         },
         ranges: {
            'Today': [moment().startOf('day'), moment().endOf('day')],
            'Yesterday': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
            'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'This Year': [moment().startOf('year'), moment().endOf('year')],
            'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
         }
      });

      $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
         $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
      });

      $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
         $(this).val('');
      });

   });




   function searchTicket() {

      var newdate = $('#datefilter').val();
      // alert(deva);
      if (newdate != '') {
         var formdate = moment($('#datefilter').data('daterangepicker').startDate).format("YYYY-MM-DD HH:mm:ss");
         var todate = moment($('#datefilter').data('daterangepicker').endDate).format("YYYY-MM-DD HH:mm:ss");
      } else {
         var formdate = '';
         var todate = '';
      }

      var userType = $('#userType').val();
      var selectedProductId = $('#category').val();
      let search_id = $('#searchTxt').val();
      var title = 'ND Invoice Reports : (' + formdate + '  to  ' + todate + ') ' + (userType != '' ? userType : "");

      var table = $("#sms_report1").DataTable({
         destroy: true,
         pageLength: 10,
         // order: [
         //     ['desc']
         // ],
         order: [
            ['asc']
         ],
         columnDefs: [{
            type: 'date',
            targets: [7]
         }],
         paging: true,
         searching: true,
         info: true,
         ajax: {
            url: url,
            method: "POST",
            dataSrc: "",
            data: {
               method: 'ND_invoice',
               agdate: formdate,
               todate: todate,
               search_id: search_id,
               selectedProductId: selectedProductId,
               userType: userType,

            },
            error: function(xhr, error, thrown) {
               console.error("AJAX error:", error);
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
            //   {
            //       extend: 'csvHtml5',
            //       title: title
            //   },

         ],
         error: function(xhr, error, thrown) {
            console.error("DataTables error:", error);
         },
         columns: [

            {
               data: null,
               render: function(data, type, row, meta) {
                  return data.payment_transaction_id;
               }
            },
            {
               data: null,
               render: function(data, type, row, meta) {
                  return data.firstname + ' ' + data.lastname;
               }
            },
            {
               data: 'mobile'
            },
            {
               data: 'emailid'
            },
            {
               data: null,
               render: function(data, type, row, meta) {
                  return moment(data.createdon).format("DD MMM YYYY hh:mm a");
               }
            },
            {
               data: 'Product_rate'
            },

            {
               data: 'cart',
               render: function(data, type, row, meta) {
                  let cartData = JSON.parse(data);
                  let quantity = cartData?.quantity ?? '';
                  return quantity;
               }
            },
            // {
            //    data: 'grandtotal'
            // },
            {

               data: null,
               render: function(data, type, row, meta) {
                  if (data.discountType === 'general' || data.discountType === 'coupon') {
                     return 'Discount Code'
                  } else if (data.discountType === 'flatAgent') {
                     return 'Agent Flat Discount'
                  } else {
                     return 'NA';
                  }
               }
               // data: 'copupon_type'

            },
            {
               data: 'discount'
            },
            {
               data: 'grandtotal'
            },
            {
               data: 'paymentType'
            },

            {
               data: 'renewalStatus'
            },
            {
               data: 'agentName'
               // data: null,
               // render: function(data, type, row, meta) {
               //     if (!data.agentName) {
               //         return 'Online Ticket';
               //     } else {
               //         return data.agentName;
               //     }
               // }
            },

            {
               data: null,
               render: function(data, type, row, meta) {
                  var Turl = origin + "/ticketHistoryPay/list/" + data.id;
                  var ticketview = origin + "/pages/view-ticket.php?ticketNo=" + data.ticketNo;
                  return `
                       <a target="_blank" href="<?= $baseurl; ?>ticket-view/${data.ticketReferenceID}" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span style="font-size: 18px;" class="fa fa-files-o"></span></a>
                       <a target="_blank" href="<?= $baseurl; ?>invoice/${data.ticketReferenceID}" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span style="font-size: 18px;" class="fa fa-file-text-o"></span></a>
                   `;
               }
            }
         ],

         footerCallback: function(row, data, start, end, display) {
            var api = this.api();

            // Function to convert formatted values to integers
            var intVal = function(i) {
               return typeof i === 'string' ?
                  i.replace(/[\$,]/g, '') * 1 :
                  typeof i === 'number' ?
                  i : 0;
            };

            // Calculate total for column 7 over all pages
            var totalCol7 = api.column(8)
               .data()
               .reduce(function(a, b) {
                  return intVal(a) + intVal(b);
               }, 0);

            // Calculate total for column 9 over all pages
            var totalCol9 = api.column(9)
               .data()
               .reduce(function(a, b) {
                  return intVal(a) + intVal(b);
               }, 0);

            // Calculate page totals for columns 7 and 9
            var pageTotalCol7 = api.column(8, {
                  page: 'current'
               })
               .data()
               .reduce(function(a, b) {
                  return intVal(a) + intVal(b);
               }, 0);

            var pageTotalCol9 = api.column(9, {
                  page: 'current'
               })
               .data()
               .reduce(function(a, b) {
                  return intVal(a) + intVal(b);
               }, 0);

            // Update footer with page totals and overall totals
            $(api.column(8).footer()).html('' + pageTotalCol7 + ' ( ' + totalCol7 + ' total)');
            $(api.column(9).footer()).html('' + pageTotalCol9 + ' ( ' + totalCol9 + ' total)');
         }


      });
   }
</script>