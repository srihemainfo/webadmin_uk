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
            <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Agent Settlement</h1>
            <div>
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Agent Settlement</li>
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
                                        <select class="form-control" id="datefilter" name="datefilter">
                                            <option value="">Select a month</option>
                                        </select>
                                    </div>
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
                     <h3 class="card-title"><strong>Agent Settlement</strong></h3>
                  </div>
                  <div class="col-lg-8">
                  </div>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-bordered text-nowrap border-bottom" id="sms_report1" style="width:100%;">
                        <thead>
                           <tr>
                               
                               
                              <th class="wd-20p border-bottom-0">Generate Date</th>
                              <th class="wd-20p border-bottom-0">Settled By</th>
                              <th class="wd-15p border-bottom-0">Agent Name</th>
                              <th class="wd-15p border-bottom-0">Mobile Number</th>
                              <th class="wd-15p border-bottom-0">Email ID</th>
                              <th class="wd-15p border-bottom-0">Agent total sales</th>
                              <th class="wd-20p border-bottom-0">Commission %</th>
                              <th class="wd-20p border-bottom-0">Commission points</th>
                              <th class="wd-20p border-bottom-0">Status</th>
                              <th class="wd-20p border-bottom-0">Payment method</th>
                              <th class="wd-20p border-bottom-0">Settled Date</th>
                              <th class="wd-20p border-bottom-0">Action</th>
                              <!--<th class="wd-20p border-bottom-0">Action</th>-->

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

<div class="modal  fade" id="pointreject" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Deleted Reason</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div id="erterNre"></div>
            <textarea id="deletemessage" class="w-100" cols="33" rows="10" placeholder="Reason"></textarea>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <div id="trbtn"></div>
         </div>
      </div>
   </div>
</div>
<div class="modal  fade" id="withdrawtransfer" tabindex="-1" role="dialog">
    <div class="modal-dialog " role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title">Agent Commission Transfer</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="wterr"></div>
                <input type="hidden" id="withdrawreqtid">
                <div class="row" id="wdtdiv">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" onclick="refreshdata('withdrawtransfer')">Close</button>
                <div id="withtransbtn">
                </div>
            </div>
        </div>
    </div>
</div>
<script>
   var origin = window.location.origin;



   var url = origin + "/ajax/service/agentND_services.php";




   $(function() {
      // createDatePricket('reportrange');
      searchTicket();

   });
   
   $(function() {
    // Generate options for each month from April 2024 to April 2025
    let startMonth = moment('2024-04-01');
    let endMonth = moment('2025-04-30');
    
    while (startMonth.isBefore(endMonth)) {
        let monthName = startMonth.format('MMMM YYYY');
        let rangeStart = startMonth.clone().startOf('month').format('YYYY-MM-DD');
        let rangeEnd = startMonth.clone().endOf('month').format('YYYY-MM-DD');
        let optionValue = `${rangeStart} - ${rangeEnd}`;
        $('#datefilter').append(new Option(monthName, optionValue));
        startMonth.add(1, 'month');
    }
});




   function searchTicket() {

    //   var newdate = $('#datefilter').val();
    //   if (newdate != '') {
    //      var formdate = moment($('#datefilter').data('daterangepicker').startDate).format("YYYY-MM-DD HH:mm:ss");
    //      var todate = moment($('#datefilter').data('daterangepicker').endDate).format("YYYY-MM-DD HH:mm:ss");
    //   } else {
    //      var formdate = '';
    //      var todate = '';
    //   }
    
    var newdate = $('#datefilter').val();
    let formdate = '';
    let todate = '';
    
    if (newdate != '') {
        [formdate, todate] = newdate.split(' - ');
    }

      var userType = $('#userType').val();
      var selectedProductId = $('#category').val();
      let search_id = $('#searchTxt').val();
      var title = 'Agent Settlement : (' + formdate + '  to  ' + todate + ')';

      var table = $("#sms_report1").DataTable({
         destroy: true,
         pageLength: 13,
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
               data: 'generate_date'
            },
            {
               data: 'processedBy_name'
            },
            {
               data: 'uname'
            },
            {
               data: 'umobile'
            },
            {
               data: 'uemail'
            },
            
            {
               data: 'totalSales'

            },
            {
               data: 'commissionPercentage'
            },
            {
               data: 'totalEarnings'
            },
            {
                data: 'status',
                render: function(data, type, row, meta) {
                    switch(data) {
                        case '0':
                            return '<span style="color: red;">Pending</span>';
                        case '1':
                            return '<span style="color: #0cf45a;">Settled</span>';
                        case '2':
                            return 'Rejected'; // Keep default color for Rejected
                        default:
                            return 'Unknown';
                    }
                }
            },
         {
            data: 'method_type',
            render: function(data, type, row, meta) {
                var options = '<option value=""></option>';
                options += '<option value="Point Transaction">point Transaction</option>';
                options += '<option value="Paid Cash">cash pay</option>';
                
                var selectHtml = '<select class="form-select" onchange="myFunction(this, ' + row.id + ')">';
                if (data === 'Point Transaction') {
                    var inputHtml = '<input type="text" class="form-control" value="' + data + '" readonly>';
                    
                } else if (data === 'Paid Cash') {
                    var inputHtml = '<input type="text" class="form-control" value="' + data + '" readonly>';
                } else {
                    var inputHtml = '<input type="text" class="form-control" value="' +'' + '" readonly>';
                }
                selectHtml += '</select>';
                

                
                return inputHtml;
            }
        },


            
            {
               data: null,
               render: function(data, type, row, meta) {
                   if(data.updatedon !=''){
                       return moment(data.updatedon).format("DD MMM YYYY hh:mm a");
                   }else{
                       return '';
                   }
                  
               }
            },
           {
                data: null,
                render: function(data, type, row, meta) {
                    if (row.status != '1') {
                        return '<a class="btn text-danger btn-sm transfer" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fa fa-money" onclick="withdrawtransfer(' + row.id + ')"> Transfer</span></a>';
                        // return '<button class="btn btn-primary" onclick="submitFunction(' + row.id + ')">Submit</button>';
                    } else {
                        return ' ';
                    }
                }
            }

           
         ],

         
      });
   }
   
   
   function withdrawtransfer(str) {

            $('#withdrawtransfer').modal('show');
            var formdata = [];
            formdata.push({

                name: 'method',
                value: "withdraw_transfer_verify"

            });
            formdata.push({

                name: 'request',
                value: str
            });

            var post_data = formdata;
            var onsuccess = function(data) {

                var response = JSON.parse(data);
                if (response != "") {
                    if (response.type == 1) {
                        document.getElementById('wdtdiv').innerHTML = response.result;
                        document.getElementById('withtransbtn').innerHTML = response.withdraw;
                    } else {
                        document.getElementById('wterr').innerHTML = response.result;

                    }

                }

            }

            do_ajax_call(post_data, onsuccess, url);
        }
        
   function transferwdamount(request) {
            document.getElementById('wterr').innerHTML = '';
            let mode = document.getElementById('transactionmode').value;
            let reasontext = document.getElementById('reasontext').value;
            
            
            
            var btn = $('#withtransbtn').html();
            if (mode != '') {
                
                if (mode === 'Paid Cash' && reasontext === '') {
                    $('#erterNre1').html(`<div class="alert alert-warning" role="alert">Please Enter the Reasons</div>`);
                } else {
                    $('#erterNre1').html('');
                      var formData = new FormData();
                            
                            formData.append('rowId', request);
                            formData.append('mode', mode);
                            formData.append('method', 'submitFunction');
                            formData.append('reasontext', reasontext);
                            var post_data = formData;

                            $.ajax({
                                url: url,
                                type: 'post',

                                data: post_data,

                                success: function(response) {

                                      var response = JSON.parse(response);

                                    if (response != "") {
                                        if (response.type == 1) {
                                            $('#withdrawtransfer').modal('hide');
                                            toast('success', response.message)
                                            location.reload();
                                            // document.getElementById('withtransbtn').innerHTML = '';
                                            // document.getElementById('wdtdiv').innerHTML = response.result;

                                        } else {
                                            toast('error', response.message);
                                            document.getElementById('withtransbtn').innerHTML = btn;

                                            // document.getElementById('wterr').innerHTML = response.result;

                                        }
                                    }
                                },

                                processData: false,
                                contentType: false

                            });
                }
               
                   

            } else {

                toast('error', 'Please Select mode');
                document.getElementById('wterr').innerHTML = '<div class="alert alert-danger" role="alert">' + 'Please Select the Transaction mode' + '</div>';
            }

        }
        
   function refreshdata(modalId) {
        $('#' + modalId).modal('hide');
        location.reload();
    }   
    
   function submitFunction( rowId) {
        
        
            
            $.ajax({
            
                url: url,
                method: "POST",
                dataSrc: "",
                data: {
                   method: 'submitFunction',
                   rowId: rowId,
                   
    
                },
               
               success: function(response) {
                    response = JSON.parse(response);
                    if (response.type == '1') {
                        toast('success', response.message);
                        
                    } else {
                        toast('error', response.message);
                    }
                    searchTicket();
                },
                error: function(xhr, status, error) {
                    console.error('Error updating delivery status:', error);
                    swal("Error!", "Failed to update delivery status", "error");
                }
            });


         
        
        
    }
   
   function myFunction(selectElement, rowId) {
        
        
        var selectedValue = selectElement.value;
         
        
        if (selectedValue == 'Paid Cash') {
            $('#trbtn').html(`<button class="btn btn-primary" onclick="submitReason('${selectedValue}','${rowId}')">Submit</button>`);
            $("#pointreject").modal('show');
            
        }else{
            
            $.ajax({
            
                url: url,
                method: "POST",
                dataSrc: "",
                data: {
                   method: 'method_type',
                   rowId: rowId,
                   selectedValue: selectedValue
    
                },
               
                success: function(response) {
                    // Handle success response
                },
                error: function(xhr, status, error) {
                    // Handle error
                }
            });


            
        // alert(selectedValue);
        
             
        }
        
        
    }
    
   function submitReason(selectedValue,rowId) {
       
    let delete_reason = document.getElementById('deletemessage').value;
    
    
    
    if (delete_reason == '') {
        $('#erterNre').html(`<div class="alert alert-warning" role="alert">Please Enter the Reasons</div>`);
        return false;
    }else{
        
       
        $.ajax({
            
            url: url,
            method: "POST",
            dataSrc: "",
            data: {
               method: 'method_type',
               rowId: rowId,
               selectedValue: selectedValue,
               delete_reason: delete_reason

            },
           
           success: function(response) {
                response = JSON.parse(response);
                if (response.type == '1') {
                    // toast('success', response.message);
                    $("#pointreject").modal('hide'); 
                    document.getElementById('deletemessage').value = '';
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