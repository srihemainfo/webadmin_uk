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
            <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Gold Request</h1>
            <div>
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Gold Request</li>
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
                                    <span>Search Customer</span>
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
                                    <span>Select Status</span>
                                    <select id="requet_type" class="form-select">
                                       <option value="" selected>Select Status</option>
                                       <option value="requested">Requested</option>
                                       <option value="confirmed">Confirmed</option>
                                       <option value="collected">Collected</option>
                                       <option value="cancelled">Cancelled</option>

                                    </select>
                                 </div>


                                 <div class="col-lg-3 col-sm-6 mb-2">
                                    <br>
                                    <button type="submit" id="smslogsearch" class="btn btn-info" onclick="searchTicket()">Go</button>
                                 </div>
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
                     <h3 class="card-title"><strong>Gold Request</strong></h3>
                  </div>
                  <div class="col-lg-8">
                  </div>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-bordered text-nowrap border-bottom" id="sms_report1" style="width:100%;">
                        <thead>
                           <tr>

                              <th class="wd-15p border-bottom-0">Customer Name</th>
                              <th class="wd-15p border-bottom-0">Mobile Number</th>
                              <th class="wd-15p border-bottom-0">Email ID</th>
                              <th class="wd-15p border-bottom-0">Gold Request on Date</th>
                              <th class="wd-20p border-bottom-0">Grams</th>
                              <th class="wd-20p border-bottom-0">Gold Values</th>
                              <th class="wd-20p border-bottom-0">Reason</th>
                              <th class="wd-20p border-bottom-0">Status</th>

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



   var url = origin + "/ajax/service/shipping_services.php";




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
      var request_type = $('#requet_type').val();
      let search_id = $('#searchTxt').val();
      var title = 'Gold Request : (' + formdate + '  to  ' + todate + ')';

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
            targets: [6]
         }],
         paging: true,
         searching: true,
         info: true,
         ajax: {
            url: url,
            method: "POST",
            dataSrc: "",
            data: {
               method: 'Gold_Request',
               agdate: formdate,
               todate: todate,
               request_type: request_type,
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


         ],
         error: function(xhr, error, thrown) {
            console.error("DataTables error:", error);
         },
         columns: [


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
               data: 'grams'

            },
            {
               data: 'goldValue'
            },
            {
               data: null,
               render: function(data, type, row, meta) {
                  return (data.reason != undefined && data.reason != null) ? `<textarea readonly="">${data.reason}</textarea>` : '';
               }
            },
            {
               data: "delivery_statu",
               render: function(data, type, row, meta) {
                  let textColorClass = '';
                  switch (data) {
                     case 'requested':
                        textColorClass = 'text-primary';
                        break;
                     case 'confirmed':
                        textColorClass = 'text-success';
                        break;
                     case 'out_for_delivery':
                        textColorClass = 'text-info';
                        break;
                     case 'delivered':
                        textColorClass = 'text-success';
                        break;
                     case 'collected':
                        textColorClass = 'text-warning';
                        break;
                     case 'cancelled':
                        textColorClass = 'text-danger';
                        break;
                     default:
                        textColorClass = '';
                        break;
                  }

                  return `
                                <select style="font-size:18px; height:45px; width:200px; color: ${textColorClass}" class="form-select form-select-sm ${textColorClass}" aria-label=".form-select-sm example" onchange="updateDeliveryStatus(this, ${row.id})">
                                  
                                   ${data !== 'collected' && data !== 'cancelled' ? 
                                   `${data !== 'confirmed' && data !== 'cancelled' ? `<option value="requested" class="text-primary" ${data === 'requested' ? 'selected' : ''}>Requested</option>` : ''}
                                     <option value="confirmed" class="text-success" ${data === 'confirmed' ? 'selected' : ''}>Confirmed</option>` : ''}


                               ${data !== 'cancelled' ?     `<option value="collected" class="text-warning" ${data === 'collected' ? 'selected' : ''}>Collected</option>` : ''}
                                    
                                    ${data !== 'collected'  ? `<option value="cancelled" class="text-danger" ${data === 'cancelled' ? 'selected' : ''}>Cancelled</option>` : ''}


                                </select>
                            `;
               }
            },
            // {
            //   data: 'order_closed_By_name'
            // },

            // {
            //   data: 'Deleted_BY_name'
            // },

         ],

         //  footerCallback: function(row, data, start, end, display) {
         //     var api = this.api();
         //     // Remove the formatting to get integer data for summation
         //     var intVal = function(i) {
         //       return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
         //     };
         //     // Total over all pages
         //     total = api
         //       .column(7)
         //       .data()
         //       .reduce(function(a, b) {
         //           let x = intVal(a) + intVal(b);
         //           return x.toFixed(2);
         //       }, 0);
         //     // Total over this page
         //     pageTotal = api
         //       .column(7, {
         //           page: 'current'
         //       })
         //       .data()
         //       .reduce(function(a, b) {
         //           return intVal(a) + intVal(b);
         //       }, 0);
         //     // Update footer
         //     $(api.column(7).footer()).html('' + pageTotal + ' ( ' + total + ' total)');

         //  }

      });
   }

   function updateDeliveryStatus(selectElement, rowId) {
      var newStatus = selectElement.value;



      if (newStatus === 'cancelled' && rowId != undefined && rowId != null) {


         swal.fire({

            title: 'Cancel Reason',

            input: 'textarea',

            showCancelButton: true,

            allowOutsideClick: false

         }).then(function(result) {

            if (result.isConfirmed) {

               if (result.value != '') {

                  var formdata = [];

                  formdata.push({

                     name: 'method',

                     value: "Gold_delivery_status"

                  });

                  formdata.push({

                     name: 'id',

                     value: rowId

                  }, {

                     name: 'status',

                     value: newStatus

                  }, {

                     name: 'reason',

                     value: result.value

                  });




                  var post_data = formdata;

                  var onsuccess = function(data) {

                     var response = JSON.parse(data);

                     if (response != "") {

                        if (response.type == 1) {

                           toast('success', response.result);

                           searchTicket();

                        } else {

                           toast('error', response.result);

                        }

                     }

                  }

                  do_ajax_call(post_data, onsuccess, url);

                  // console.log(result.value);

                  // paymentSuccess('success', 's');

               } else {

                  toast('error', 'Please Fill the Reason');
                  searchTicket();
               }

            }

         })


         // alert(rowId);

         // return false;
      } else {

         $.ajax({
            url: url,
            method: 'POST',
            data: {
               method: 'Gold_delivery_status',
               id: rowId,
               status: newStatus
            },
            success: function(response) {
               response = JSON.parse(response);
               if (response.type == '1') {
                  toast('success', response.result);
               } else {
                  toast('error', response.result);
               }
               searchTicket();
            },
            error: function(xhr, status, error) {
               console.error('Error updating delivery status:', error);
               swal("Error!", "Failed to update delivery status", "error");
            }
         });
      }
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
</script>