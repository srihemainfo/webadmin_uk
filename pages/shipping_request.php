<style>
    textarea {
        height: 100px;
        padding: 12px 2px;
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
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Shipping Request</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Shipping Request</li>
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
                                                <span>Select Date</span> &nbsp; <span style="color:red;"></span>
                                                <div >
                                                    <!--<input type="text" name="datefilter" value="" />-->
                                                    <input class="form-control" type="text" id="datefilter" name="datefilter" value="" placeholder="YYYY-MM-DD" autocomplete="off" maxlength="10" oninput="this.value = this.value.replace(/[^0-9-]/g, '');" />
                                                </div>
                                                <!--<span>Select Date</span> &nbsp; <span style="color:red;">*</span>-->
                                                <!--<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">-->
                                                <!--    <i class="fa fa-calendar"></i>&nbsp;-->
                                                <!--    <span></span> <i class="fa fa-caret-down"></i>-->
                                                <!--</div>-->
                                            </div>

                                            <div class="col-lg-3 col-sm-6 mb-2">
                                                <span>Search Secret Code</span>
                                                <!--<input class="form-control" type="text" oninput="searchTicket($(this).val())" id="searchTxt" placeholder="Name / Email / Mobile No">-->
                                                <input class="form-control" type="text"  id="searchTxt" placeholder=" Secret Code">
                                            </div>

                                            
                                            <div class="col-lg-3 col-sm-6 mb-2">
                                                <span>Select Purchase Type</span> &nbsp; <span style="color:red;"></span>
                                                <select id="userType" class="form-control">
                                                    <option value="" selected>Select Option</option>
                                                    <option value="deliveryToMe">Delivery </option>
                                                    <option value="pickUpToStore">Pick up to store</option>
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
                                <h3 class="card-title"><strong>Shipping</strong></h3>
                            </div>
                            <div class="col-lg-8">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="sms_report1" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p border-bottom-0">Order ID</th>
                                            <th class="wd-15p border-bottom-0">Full Name </th>
                                            <th class="wd-15p border-bottom-0">Email</th>
                                            <th class="wd-15p border-bottom-0">Mobile</th>
                                            <th class="wd-15p border-bottom-0">Requested Date</th>
                                            
                                            <th class="wd-15p border-bottom-0">Product cost</th>

                                            <th class="wd-15p border-bottom-0">Packing cost</th>
                                            <th class="wd-15p border-bottom-0">Handling cost</th>
                                            <th class="wd-15p border-bottom-0">Delivery cost</th>
                                           
                                            <th class="wd-20p border-bottom-0">Subgrand Total</th>
                                            <th class="wd-20p border-bottom-0">paid So Far</th>
                                            <th class="wd-15p border-bottom-0">Grand total</th>
                                            
                                            <!--<th class="wd-15p border-bottom-0">Delivery Type </th>-->
                                            <th class="wd-15p border-bottom-0">Delivery Status</th>
                                            <th class="wd-15p border-bottom-0">Reason</th>
                                            <th class="wd-15p border-bottom-0">Action</th>
                                           
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

                                            <th></th>
                                            <th></th>
                                            <th style="text-align:right">Total:</th>
                                        
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

<div class="modal  fade" id="withdrawrejcet" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content" style="width: 400px;">
            <div class="modal-header">
                <h5 class="modal-title">Deleted Reason</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="rejectre">
                <textarea id="rejectreasontext" class="form-control" placeholder="Reason"></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" onclick="refreshdata('withdrawrejcet')">Close</button>
                <div id="rejectebtn">
                </div>
            </div>
        </div>
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
            <textarea id="deletemessage" cols="30" rows="10" placeholder="Reason"></textarea>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <div id="trbtn"></div>
         </div>
      </div>
   </div>
</div>

<div class="modal  fade" data-bs-backdrop="static"data-keyboard="false" id="SecretCode" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Secret Code</h5>
            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div id="erter"></div>
            <span>Enter the Secret Code</span>
            <input type="text" id="pointrequestid">
            <div class="row" id="pointdiv">
            </div>
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-secondary" onclick="closeSecretCodeModal()">Close</button>

            <div id="tbtn">
            </div>
         </div>
      </div>
   </div>
</div>

<div class="modal  fade" id="outofmessage" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Tracking URL</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div id="erterNre1"></div>
            <textarea id="TrackingURL" cols="30" rows="10" placeholder="Enter the Tracking URL"></textarea>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <div id="trbtn1"></div>
         </div>
      </div>
   </div>
</div>





<script>
    var origin = window.location.origin;



    // var url = origin + "/ajax/service/datatable_services.php";
    var url = origin + "/ajax/service/shipping_services.php";
    var e_url = origin + "/ajax/service/NDemail_services.php";



    $(function() {
       
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
        
        if(newdate != ''){
                var formdate = moment($('#datefilter').data('daterangepicker').startDate).format("YYYY-MM-DD HH:mm:ss");
                var todate = moment($('#datefilter').data('daterangepicker').endDate).format("YYYY-MM-DD HH:mm:ss");
            } else{
                var formdate = '';
                var todate ='';
            }
            //  alert('king11');
        // var formdate = moment($('#reportrange').data('daterangepicker').startDate.toDate()).format("YYYY-MM-DD HH:mm:ss");
        // var todate = moment($('#reportrange').data('daterangepicker').endDate.toDate()).format("YYYY-MM-DD HH:mm:ss");
        var userType = $('#userType').val();
        let search_id = $('#searchTxt').val();
       
        var title = 'Shipping Reports : (' + formdate + '  to  ' + todate + ') ' + (userType != '' ? userType : "");

        var table = $("#sms_report1").DataTable({
            destroy: true,
            pageLength: 15,
            order: [
                ['desc']
            ],
            // columnDefs: [{
            //     type: 'date',
            //     targets: [7]
            // }],
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
                    data: function(row) {
                        return '#'+row.id;
                    }
                },
                {
                    data: 'fullname'
                },
                {
                    data: 'email'
                },
                {
                    data: 'mobile'
                },
                 {
                    data: null,
                    render: function(data, type, row, meta) {
                        return moment(data.collectRequestedDate).format("DD MMM YYYY");
                    }
                },
                // { data: 'product_cost'},
                // { data: 'packing_cost'},
                // { data: 'handling_cost'},
                // { data: 'delivery_cost'},
                // { data: 'subgrand_total'},
                // { data: 'paidSoFor'},
                // { data: 'grand_total'},
                 {
                    data: function(row) {
                        return row.product_cost + ' (AED)';
                    }
                },
                {
                    data: function(row) {
                        return row.packing_cost + ' (AED)';
                    }
                },
               
                {
                    data: function(row) {
                        return row.handling_cost + ' (AED)';
                    }
                },
                {
                    data: function(row) {
                        return row.delivery_cost + ' (AED)';
                    }
                },
                {
                    data: function(row) {
                        return row.subgrand_total + ' (AED)';
                    }
                },
                {
                    data: function(row) {
                        return row.paidSoFor + ' (AED)';
                    }
                },
                {
                    data: function(row) {
                        return row.grand_total;
                    }
                },
               
           
                {
                    data: "delivery_status",
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
                
                      if (row.deliveryType === 'deliveryToMe') {
                            return `
                                <select style="font-size:18px; height:45px; width:200px; color: ${textColorClass}" class="form-select form-select-sm ${textColorClass}" aria-label=".form-select-sm example" onchange="updateDeliveryStatus(this, ${row.id}, '${row.deliveryType}', ${row.mobile},'${row.fullname}','${row.collectRequestedDate}','${row.delivery_status}','${row.ticketReferenceID}' )">
                                
                                
                                    ${data !== 'delivered' ? `
                                        ${data !== 'out_for_delivery' ? `
                                            <option value="requested" class="text-primary" ${data === 'requested' ? 'selected' : ''}>Requested</option>
                                            <option value="confirmed" class="text-success" ${data === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                                        ` : ''}
                                         
                                        <option value="out_for_delivery" class="text-info" ${data === 'out_for_delivery' ? 'selected' : ''}>Out for Delivery</option>
                                    
                                     ` : ''}
                                    <option value="delivered" class="text-success" ${data === 'delivered' ? 'selected' : ''}>Delivered</option>
                                    ${data !== 'delivered' ? `
                                    <option value="cancelled" class="text-danger" ${data === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                                    ` : ''}
                                </select>
                            `;
                        } 
                        else if(row.deliveryType === 'pickUpToStore'){
                            
                            return `
                                <select style="font-size:18px; height:45px; width:200px; color: ${textColorClass}" class="form-select form-select-sm ${textColorClass}" aria-label=".form-select-sm example" onchange="updateDeliveryStatus(this, ${row.id},'${row.deliveryType}', ${row.mobile},'${row.fullname}','${row.collectRequestedDate}','${row.delivery_status}','${row.ticketReferenceID}')">
                                  
                                   ${data !== 'collected' ? `
                                       <option value="requested" class="text-primary" ${data === 'requested' ? 'selected' : ''}>Requested</option>
                                       <option value="confirmed" class="text-success" ${data === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                                   ` : ''}
                                    <option value="collected" class="text-warning" ${data === 'collected' ? 'selected' : ''}>Collected</option>
                                    
                                    ${data !== 'collected' ? `
                                    
                                    <option value="cancelled" class="text-danger" ${data === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                                     ` : ''}
                                </select>
                            `;
                            
                        } else {
                            return '';
                        }

                    }
                 },
                 {
                    data: 'reason'
                },




            {
                data: null,
                render: function(data, type, row, meta) {
                //   return '';
                  
                    if (row.deliveryType === 'deliveryToMe') {
                        
                        const cart = JSON.parse(row.cart); // Parse the JSON string
                        // alert(cart.name);
                        // const shippingAddress = cart.shipingAddress;
                        // alert(row.id);
                        return `
                        
                         <a target="_blank" href="<?=$baseurl;?>invoice/${data.payment_transaction_id}" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt">
                                <span style="font-size: 18px;" class="fa fa-file-text-o"></span>
                            </a>
                            
            
                        
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#deliveryDetailsModal${row.id}">
                                Delivery Details
                            </button>
                            <div class="modal fade" id="deliveryDetailsModal${row.id}" tabindex="-1" aria-labelledby="deliveryDetailsModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deliveryDetailsModalLabel">Delivery Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h4>Shipping Address:</h4>
                                            <table class="table" style="width:100%;">
                                                <tbody>
                                                    <tr><td>Name</td><td>${cart.name}</td></tr>
                                                    <tr><td>Mobile</td><td>${cart.mobile}</td></tr>
                                                    <tr><td>Address</td><td class="word-wrap">${cart.doorno}, ${cart.street},<br> ${cart.city},<br> ${cart.state}, ${cart.country}, <br>${cart.postal_code}</td></tr>
                                                    <tr><td>Landmark</td><td>${cart.landmark}</td></tr>
                                                    <tr><td>Address Book ID</td><td>${cart.addressBookID}</td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                            
                        `;
                    } else {
                        return `
                            <a target="_blank" href="<?=$baseurl;?>invoice/${data.payment_transaction_id}" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt">
                                <span style="font-size: 18px;" class="fa fa-file-text-o"></span>
                            </a>
                        `;
                        
                    }
                }
            },
                
            ],


            footerCallback: function(row, data, start, end, display) {
                var api = this.api();
                // Remove the formatting to get integer data for summation
                var intVal = function(i) {
                    return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                };
                // Total over all pages
                total = api
                    .column(11)
                    .data()
                    .reduce(function(a, b) {
                        let x = intVal(a) + intVal(b);
                        return x.toFixed(2);
                    }, 0);
                // Total over this page
                pageTotal = api
                    .column(11, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                // Update footer
                $(api.column(11).footer()).html('' + pageTotal + ' ( ' + total + ' total)');

            }

        });
    }
    
    
function updateDeliveryStatus(selectElement, rowId,deliveryType,mobile,fullname,collectRequestedDate,delivery_status,ticketReferenceID) {
    var newStatus = selectElement.value;
    
    // alert(ticketReferenceID);
    
    
    if (newStatus == 'cancelled') {
        $('#trbtn').html(`<button class="btn btn-primary" onclick="submitReason('${rowId}')">Submit</button>`);
        $("#pointreject").modal('show');
        
    } else if (deliveryType == 'pickUpToStore' && newStatus == 'collected') {
        if(delivery_status !='cancelled'){
         $('#tbtn').html(`<button class="btn btn-primary" onclick="submitsecretcode('${rowId}', '${url}', '${newStatus}',${mobile},'${fullname}')">Submit</button>`);
         $("#SecretCode").modal('show');
        }
    }  else if(deliveryType == 'deliveryToMe' && newStatus == 'delivered' ){
        
        // alert('kumar');
        
        // Create a FormData object
        var formData = new FormData();
        
        // Add data to the FormData object
        formData.append("senderName", "DRAW");
        formData.append("mobileNo", mobile);
        formData.append("templateName", "shipping_order_delivered");
        formData.append("language", "en");
        formData.append("templateBodyParam[]", fullname);
        formData.append("templateBodyParam[]", "#" + rowId);

        
        // Make the AJAX request
        $.ajax({
            url: 'https://testmapi.nationaldrawuae.com/api/dtSendTemplate',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    // Nested AJAX call
                    $.ajax({
                        url: url, // Replace with your actual update URL
                        method: 'POST',
                        data: { 
                            method: 'update_delivery_status',
                            id: rowId,
                            status: newStatus
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
                    // alert('Welcome'); // This alert will be displayed if the first AJAX call is successful
                } else {
                   toast('error', 'Kindly Fill the Any Fields!');
                }
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr, status, error);
            }
        });

        
    } else if(deliveryType == 'deliveryToMe' && newStatus == 'out_for_delivery' ){
         if(delivery_status !='out_for_delivery'){
        $('#trbtn1').html(`<button class="btn btn-primary" onclick="submitTrackingULR('${rowId}', '${url}', '${newStatus}',${mobile},'${fullname}','${collectRequestedDate}','${ticketReferenceID}')">Submit</button>`);
        $("#outofmessage").modal('show');
         }
        
        
    } else if(deliveryType != 'out_for_delivery' && deliveryType != 'delivered' ){
            $.ajax({
                url: url,
                method: 'POST',
                data: { 
                    method: 'update_delivery_status',
                    id: rowId,
                    status: newStatus
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
    }
    // Close the modal with the given id
function modalclose(modalId) {
    $('#' + modalId).modal('hide');
}

function submitsecretcode(rowId, url, newStatus,mobile,fullname) {
    
    // alert(mobile);
    var secretCode = $('#pointrequestid').val().trim();
    if (secretCode == '') {
        $('#erter').html(`<div class="alert alert-warning" role="alert">Please Enter the SecretCode</div>`);
        return false;
    }

    // $.ajax({
    //     url: url, // Replace with your actual check URL
    //     method: 'POST',
    //     data: {
    //         method: 'Search_Secret_code',
    //         id: rowId,
    //         status: newStatus,
    //         secretCode: secretCode
    //     },
    //     success: function (response) {
    //         response = JSON.parse(response);
    //          if (response.type == '1') {
    //             //  toast('success', response.message);
    //              $("#SecretCode").modal('hide');
                 
    //                 var templateBodyParam = [];
    //                 var formData = new FormData();
    //                 formData.append("senderName", "DRAW");
    //                 formData.append("mobileNo", mobile);
    //                 formData.append("templateName", "pick_up_at_the_store_collected_v2");
    //                 formData.append("templateBodyParam[]", ""); 
    //                 formData.append("language", "en");
                    
                
    //                 $.ajax({
    //                     url: 'https://testmapi.nationaldrawuae.com/api/dtSendTemplate',
    //                     method: 'POST',
    //                     data: formData,
    //                     processData: false,
    //                     contentType: false,
    //                     success: function(response) {
    //                         if (response.status === 'success') {
    //                             // Nested AJAX call
    //                             $.ajax({
    //                                 url: url, // Replace with your actual update URL
    //                                 method: 'POST',
    //                                 data: { 
    //                                     method: 'update_delivery_status',
    //                                     id: rowId,
    //                                     status: newStatus
    //                                 },
    //                                 success: function(response) {
    //                                     response = JSON.parse(response);
    //                                     if (response.type == '1') {
    //                                         toast('success', response.message);
    //                                     } else {
    //                                         toast('error', 'Kindly Fill the Any Fields!');
    //                                     }
    //                                     searchTicket();
    //                                 },
    //                                 error: function(xhr, status, error) {
    //                                     console.error('Error updating delivery status:', error);
    //                                     swal("Error!", "Failed to update delivery status", "error");
    //                                 }
    //                             });
    //                             // alert('Welcome'); // This alert will be displayed if the first AJAX call is successful
    //                         } else {
    //                           toast('error', 'Kindly Fill the Any Fields!');
    //                         }
    //                         console.log(response);
    //                     },
    //                     error: function(xhr, status, error) {
    //                         console.error(xhr, status, error);
    //                     }
    //                 });
                             
    //          } else {
    //              $('#erter').html(`<div class="alert alert-warning" role="alert">The Secret code not matched </div>`);
    //              toast('error', 'Kindly Fill the Any Fields!');
                 
    //          }
    //     },
    //     error: function (xhr, status, error) {
    //         console.error('Error checking secret code:', error);
    //         swal("Error!", "Failed to check secret code", "error");
    //     }
    // });
    $.ajax({
    url: url, // Replace with your actual check URL
    method: 'POST',
    data: {
        method: 'Search_Secret_code',
        id: rowId,
        status: newStatus,
        secretCode: secretCode
    },
    success: function (response) {
        response = JSON.parse(response);
        if (response.type == '1') {
            $("#SecretCode").modal('hide');
          

            var templateBodyParam = [];
            var formData = new FormData();
            //   alert('king11');
            formData.append("senderName", "DRAW");
            formData.append("mobileNo", mobile);
            formData.append("templateName", "pick_up_at_the_store_collected_v2");
            formData.append("templateBodyParam[]", ""); 
            formData.append("language", "en");

            $.ajax({
                url: 'https://testmapi.nationaldrawuae.com/api/dtSendTemplate',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        toast('success', response.message);
                        $.ajax({
                            url: url, // Replace with your actual update URL
                            method: 'POST',
                            data: { 
                                method: 'update_delivery_status',
                                id: rowId,
                                status: newStatus
                            },
                            success: function(response) {
                                response = JSON.parse(response);
                                if (response.type == '1') {
                                    toast('success', response.message);
                                } else {
                                    toast('error', 'Status update Faild!');
                                }
                                searchTicket();
                            },
                            error: function(xhr, status, error) {
                                console.error('Error updating delivery status:', error);
                                swal("Error!", "Failed to update delivery status", "error");
                            }
                        });
                    } else {
                        toast('error', 'WhatsApp prosses Failed!');
                    }
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr, status, error);
                }
            });
        } else {
            $('#erter').html(`<div class="alert alert-warning" role="alert">The Secret code not matched </div>`);
            // toast('error', 'Kindly Fill the Any Fields!');
        }
    },
    error: function (xhr, status, error) {
        console.error('Error checking secret code:', error);
        swal("Error!", "Failed to check secret code", "error");
    }
});

}


// Close the SecretCode modal
function closeSecretCodeModal() {
    $('#SecretCode').modal('hide');
}

function submitTrackingULR(rowId, url, newStatus,mobile,fullname,collectRequestedDate,ticketReferenceID) {
    
    // alert(collectRequestedDate);
    let Tracking_URL = document.getElementById('TrackingURL').value;
    
    let parts = collectRequestedDate.split('-');
    let formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
    
    
    if (Tracking_URL == '') {
        $('#erterNre1').html(`<div class="alert alert-warning" role="alert">Please Enter the Message</div>`);
        return false;
    }
   
       
       let formData = new FormData();
        formData.append("senderName", "DRAW");
        formData.append("mobileNo", mobile);
        formData.append("templateName", "out_of_delivery_tracking_v2");
        formData.append("language", "en");
        formData.append("templateBodyParam[]", "#" + rowId);
        formData.append("templateBodyParam[]", formattedDate);
        formData.append("templateBodyParam[]", Tracking_URL);
        formData.append("buttons[0][type]", "URL");
        formData.append("buttons[0][parameter]", ticketReferenceID);

        
         $.ajax({
            url: 'https://testmapi.nationaldrawuae.com/api/dtSendTemplate',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    
                    // alert('Welcome');
                    $("#outofmessage").modal('hide');
                    $.ajax({
                        url: url, // Replace with your actual update URL
                        method: 'POST',
                        data: { 
                            method: 'update_delivery_status',
                            id: rowId,
                            Tracking_URL: Tracking_URL,
                            status: newStatus
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
                     
                } else {
                   toast('error', 'The WhatsApp message not send!');
                }
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr, status, error);
            }
        });
        
    
        
        
        
}

function submitReason(rowId) {
    let delete_reason = document.getElementById('deletemessage').value;
    
    if (delete_reason == '') {
        $('#erterNre').html(`<div class="alert alert-warning" role="alert">Please Enter the Reasons</div>`);
        return false;
    }
    // alert(delete_reason);
        $.ajax({
            url: url,
            method: 'POST',
            data: { 
                method: 'update_delivery_status',
                id: rowId,
                status: 'cancelled',
                Tracking_URL: delete_reason
            },
            success: function(response) {
                response = JSON.parse(response);
                if (response.type == '1') {
                    toast('success', response.message);
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