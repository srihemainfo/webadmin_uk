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
            <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Discount Offers</h1>
            <div>
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Discount Offers</li>
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
                                 <div class="col-lg-4 col-sm-6 mb-2">
                                    <label for="searchtxt"><strong>Discount Name</strong><i style="color: red;">*</i></label>
                                    <div class="pt-0">
                                       <input class="form-control" type="text" name="Dis_name" id="Dis_name" placeholder="Name" oninput="this.value = this.value.replace(/[^A-Za-z0-9 ]/g, '');">
                                    </div>
                                 </div>
                                 
                                <div class="col-lg-4 col-sm-6 mb-2">
                                    <label for="searchtxt"><strong>Discount Type</strong><i style="color: red;">*</i></label>
                                    <div class="pt-0">
                                        <select class="form-control" name="Dis_type" id="Dis_type" onchange="toggleCouponInput()">
                                            <!--<option value="">Select Name</option>-->
                                            <option value="general">General</option>
                                            <option value="coupon">Coupon</option>
                                        </select>
                                    </div>
                                </div>
                                
                              
                                
                                <div class="col-lg-4 col-sm-6 mb-2" id="couponInput" style="display: none;">
                                       <label for="searchtxt"><strong>Coupon Code</strong></label> <br>
                                       <div class="d-flex align-items-center">
                                          <input class="form-control" type="text" name="Discount_code" id="Discount_code" oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '');" minlength="10" maxlength="15" style="text-transform: uppercase">&nbsp;<i onclick="get_new_coupon()" class="fa fa-refresh" aria-hidden="true"></i>
                                       </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 mb-2" id="couponuserby1" style="display: none;">
                                       <label for="searchtxt"><strong>Coupon Used By</strong></label> <br>
                                       <select id="used_BY" class="form-select">
                                          <option value="Both" selected>Both</option>
                                          <option value="Non-Purchased">Non-Purchased</option>
                                          <option value="Purchased">Purchased</option>
                                       </select>
                                    </div>
                                
                                  <div class="col-lg-4 col-sm-6 mb-2" id="couponlimit1" style="display: none;">
                                       <label for="searchtxt"><strong>Coupon Limitations</strong><i style="color: red;">*</i></label>
                                       <div class="pt-0">
                                           <input class="form-control" type="text" name="couponLimit" id="couponLimit" oninput="this.value = this.value.replace(/[^0-9 ]/g, '')" maxlength="7">  
                                          
                                       </div>
                                    </div>
                                
                                

                                 
                                 <div class="col-lg-4 col-sm-6 mb-2">
                                    <label for="searchtxt"><strong>Discount for Product</strong><i style="color: red;">*</i></label>
                                    <div class="pt-0">
                                       <select id="Damt" class="form-select">
                                          <option value=" " selected>Select Amount</option>
                                           <!--<option value="1">AED 1</option>-->
                                           <!--<option value="2">AED 7</option>-->
                                           <!--<option value="3">AED 30</option>-->
                                           <option value="4">AED 360</option>
                                          
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-lg-4 col-sm-6 mb-2">
                                    <label for="searchtxt"><strong>Discount Amount of AED</strong><i style="color: red;">*</i></label>
                                    <div class="pt-0">
                                       <input class="form-control" type="text" name="DiscountAED" id="DiscountAED" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="3">  
                                    </div>
                                 </div>
                                 <div class="col-lg-5 col-sm-6 mb-2">
                                    <label><strong>Discount Start & End Date Time</strong><i style="color: red;">*</i></label>
                                    <div id="csetime" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"><i class="fa fa-calendar"></i>&nbsp;<span></span> <i class="fa fa-caret-down"></i></div>
                                 </div>
                                 <div class="col-lg-2 col-sm-6 mb-2">
                                    <br>
                                    <button type="submit" id="smslogsearch" class="btn btn-info mt-lg-1" onclick="New_Discount()">Create</button>
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
                     <h3 class="card-title"><strong>Discount Offers</strong></h3>
                  </div>
                  <br>
                  <div class="row row-sm">
                     <div class="col-lg-3 col-md-8  mb-2">
                         <label for="searchtxt"><strong>Select Date</strong><i style="color: red;"></i></label>
                        <!--<span>Select Date</span> &nbsp; <span style="color:red;"></span>-->
                        <div >
                           <!--<input type="text" name="datefilter" value="" />-->
                           <input class="form-control" type="text" id="datefilter" name="datefilter" value="" placeholder="YYYY-MM-DD" autocomplete="off" maxlength="10" oninput="this.value = this.value.replace(/[^0-9-]/g, '');" />
                        </div>
                     </div>
                      <div class="col-lg-3 col-md-8  mb-2">
                                    <label for="searchtxt"><strong>Discount Type</strong><i style="color: red;"></i></label>
                                    <div >
                                        <select id="select_dis_type" class="form-select">
                                            <option value="">Select Type</option>
                                            <option value="general">General</option>
                                            <option value="coupon">Coupon</option>
                                        </select>
                                    </div>
                                </div>
                     <div class="col-lg-4 col-md-8  mb-2">
                        <br>
                        <button type="submit" id="smslogsearch" class="btn btn-info" onclick="searchTicket()">Go</button>
                     </div>
                  </div>
                  <div class="col-lg-8">
                  </div>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-bordered text-nowrap border-bottom" id="sms_report1" style="width:100%;">
                        <thead>
                           <tr>
                              <th class="wd-15p border-bottom-0">Created Date</th>
                              <th class="wd-15p border-bottom-0">Offer Type</th>
                              <th class="wd-15p border-bottom-0">Coupon Type</th>
                              <th class="wd-15p border-bottom-0">Coupon Code</th>
                              <th class="wd-15p border-bottom-0">Discount Offer Name</th>
                              <th class="wd-15p border-bottom-0">Created BY Name</th>
                              <!--<th class="wd-15p border-bottom-0">Email ID</th>-->
                              <th class="wd-15p border-bottom-0">Start Date</th>
                              <th class="wd-15p border-bottom-0">End Date</th>
                              <th class="wd-20p border-bottom-0">Limitations</th>
                              <th class="wd-20p border-bottom-0">Used</th>
                              <th class="wd-20p border-bottom-0">Product</th>
                              <th class="wd-20p border-bottom-0">Discount Amount</th>
                              <th class="wd-20p border-bottom-0">Action</th>
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
   
   
   
   var url = origin + "/ajax/service/DiscountOffers_services.php";
   
   
   
   $(function() {
      // createDatePricket('reportrange');
      
      get_new_coupon();
      
      createDatePricket('csetime');
      createDatePricket_old('reportrange');
      searchTicket();
     
   
   });
   
   function toggleCouponInput() {
        var select = document.getElementById('Dis_type');
        var couponInput = document.getElementById('couponInput');
        var couponuserby1 = document.getElementById('couponuserby1');
        var couponlimit1 = document.getElementById('couponlimit1');
        
        if (select.value === 'coupon') {
            couponInput.style.display = 'block';
            couponuserby1.style.display = 'block';
            couponlimit1.style.display = 'block';
        } else {
            couponInput.style.display = 'none';
            couponuserby1.style.display = 'none';
            couponlimit1.style.display = 'none';
        }
    }
   
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
   
   function createDatePricket(id) {
   
       var start = moment();
   
       var end = moment();
   
   
   
       function cb(start, end) {
           $('#' + id + ' span').html(start.format("YYYY-MM-DD HH:mm:ss") + ' - ' + end.format("YYYY-MM-DD HH:mm:ss"));
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
           minDate: moment().format("MM/DD/YYYY"),
           timePicker: true,
           timePicker24Hour: true,
           timePickerSeconds: true,
           maxSpan: {
               days: 364
           },
           autoUpdateInput: true,
           
   
       }, cb);
   
       cb(start, end);
   
   
   
   }
   function createDatePricket_old(id) {
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
   
   function get_new_coupon() {
       
    //   alert('test');
       var formdata = [];
       formdata.push({
           name: 'method',
           value: "get_new_coupon"
       });
       var post_data = formdata;
       var onsuccess = function(data) {
           var response = JSON.parse(data);
           if (response != "") {
               if (response.type == 1) {
                   document.getElementById('Discount_code').value = response.result;
               }
           }
       }
       do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/DiscountOffers_services.php");
   }
   
   
   function searchTicket() {
       
    //   var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
    //   var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");
   
      var newdate = $('#datefilter').val();
      // alert(deva);
      if (newdate != '') {
         var formdate = moment($('#datefilter').data('daterangepicker').startDate).format("YYYY-MM-DD HH:mm:ss");
         var todate = moment($('#datefilter').data('daterangepicker').endDate).format("YYYY-MM-DD HH:mm:ss");
      } else {
         var formdate = '';
         var todate = '';
      }
      var select_dis_type = $('#select_dis_type').val();
    //   alert(select_dis_type);
    //   var select_dis_type 
   
   
    
      var title = 'Discount Offers : (' + formdate + '  to  ' + todate + ') '  ;
   
      var table = $("#sms_report1").DataTable({
         destroy: true,
         pageLength: 10,
          order: [
              ['desc']
          ],
      
         columnDefs: [{
            type: 'date',
            targets: [8]
         }],
         paging: true,
         searching: true,
         info: true,
         ajax: {
            url: url,
            method: "POST",
            dataSrc: "",
            data: {
               method: 'Discount_Offers',
              formdate: formdate,
              todate: todate,
              select_dis_type: select_dis_type,
            //   selectedProductId: selectedProductId,
            //   userType: userType,
   
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
               render: function(data, type, row, meta)
               {
                  return moment(data.createdon).format("DD MMM YYYY");
               }
            },
            {
              data: 'type'
            },
            {
                data: 'type',
                render: function(data, type, row) {
                    if (data === 'coupon') {
                        return row.usedBy;
                    } else {
                        return 'NA';
                    }
                }
            },
            {
                data: 'couponCode',
                render: function(data, type, row) {
                    if (data === '') {
                        return 'NA';
                    } else {
                        return data;
                    }
                }
            },

            {
              data: 'discount_name'
            },
            {
               data: 'fullname'
            },
            
            // {
            //   data: 'email'
            // },
            {
               data: null,
               render: function(data, type, row, meta)
               {
                  return moment(data.start_date).format("DD MMM YYYY hh:mm a");
               }
            },
            {
               data: null,
               render: function(data, type, row, meta)
               {
                  return moment(data.end_date).format("DD MMM YYYY hh:mm a");
               }
            },
             {
                data: 'maxPurchaseLimit'
            },
             {
                data: 'reachedLimit'
            },
           
           {
                data: 'rate'
            },
           {
    data: 'discount_amount'
   },
   
    // {
    //                 data: "status",
    //                 render: function(data, type, row, meta) {
    //                     var currentDate = new Date();
    //                     var endDate = new Date(row.end_date);
    //                     if (endDate < currentDate) {
    //                         return "Inactive";
    //                     }else {
    //                       return `
    //                             <a class="btn text-danger btn-sm" data-bs-target="#deleteinfo" data-bs-toggle="modal" data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete" onclick="deletelist(${row.id})">
    //                                 <span class="fe fe-trash-2" style="color: #ff0000;font-size: 16px;font-weight: bold;"></span>
    //                             </a>&nbsp;&nbsp;
                                
                                
    //                         `;
    //                     }
    //                     return data;
    //                 }
    //             },
 {
    data: null,
    render: function(data, type, row, meta) {
        if (row.deletes == 1) {
            return "<span style='color: red;'>Deleted</span>"; // Show "Deleted" in red color
        } else if (row.end_date_status == 'Active') {
            return `
                <a class="btn text-danger btn-sm" data-bs-target="#deleteinfo" data-bs-toggle="modal" data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete" onclick="deletelist(${row.id})">
                    <span class="fe fe-trash-2" style="color: #ff0000;font-size: 16px;font-weight: bold;"></span>
                </a>&nbsp;&nbsp;
            `;
        } else {
            return "<span style='color: blue;'>Inactive</span>"; // Show "Inactive" in orange color
        }
    }
}


   
   
           
         ],
   
        
   
      });
   }
   
   function New_Discount() {
        
        let DiscountAED = $('#DiscountAED').val();
        let Damt = $('#Damt').val();
        let DisName = $('#Dis_name').val();
        let Dis_type = $('#Dis_type').val();
        let Discount_code = $('#Discount_code').val();
        let used_BY = $('#used_BY').val();
        let couponLimit = $('#couponLimit').val();
        
       var formdate = moment($('#csetime').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
       var todate = moment($('#csetime').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");
       
      
       if ($('#Dis_name').val().trim() == '') {
           toast('warning', 'Kindly Enter the Discount Name');
           return false;
       }
       
        if ($('#Dis_type').val().trim() =='coupon' ) {
            
            if ($('#Discount_code').val().trim() == '') {
                   toast('warning', 'Kindly Enter the Coupon code');
                   return false;
             }
               
            
            if ($('#Discount_code').val().trim().length < 5 || $('#Discount_code').val().trim() == '' || Discount_code == 0) {
               toast('warning', 'Minimum 5 characters required');
               return false;
            }
            
            
            if ($('#used_BY').val().trim() == '') {
                   toast('warning', 'Kindly Select the Used B');
                   return false;
            }
            
            if ($('#couponLimit').val().trim() == '' || couponLimit == 0) {
                   toast('warning', 'Kindly Enter the Coupon Limit ');
                   return false;
            }
       
       }
       
       
        if ($('#Damt').val().trim().length < 1 || $('#Damt').val().trim() == '' || Damt == 0) {
           toast('warning', 'Kindly Select Discount Product');
           return false;
       }
       
        
       
        if ($('#DiscountAED').val().trim() == ''|| DiscountAED == 0 ) {
           toast('warning', 'Kindly Enter the Discount Amount');
           return false;
       }
       
    //   if ($('#DiscountAED').val() > 50) {
    //       toast('warning', "Maximum Discount amount 50 AED.");
    //       return false;
    //   }
    
   
            $.ajax({
                url: url,
                method: 'POST',
                data: { 
                    method: 'New_Discount_offers',
                    startdate: formdate,
                    enddate: todate,
                    product: Damt,
                    DisName: DisName,
                    DiscountAED:DiscountAED,
                    Dis_type: Dis_type,
                    Discount_code:Discount_code,
                    used_BY: used_BY,
                    couponLimit: couponLimit,
                    
                },
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.type == '1') {
                        toast('success', response.message);
                        location.reload();
                    } else {
                        toast('error', response.message);
                    }
                    // searchTicket();
                    
                },
                error: function(xhr, status, error) {
                    console.error('Error updating delivery status:', error);
                    swal("Error!", "Failed to update delivery status", "error");
                }
            });
        }
   
   function deletelist( rowId) {
   
    
   
    
    
   
            $.ajax({
                url: url,
                method: 'POST',
                data: { 
                    method: 'update_Delete_status',
                    id: rowId,
                    
                },
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.type == '1') {
                        toast('success', response.message);
                    } else {
                        toast('error', 'Kindly Fill the Any Fields!');
                    }
                    searchTicket();
                },
                error: function(xhr, status, error) {
                    console.error('Error updating delivery status:', error);
                    swal("Error!", "Failed to update delivery status", "error");
                }
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