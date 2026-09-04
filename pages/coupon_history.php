<!--
   1.modifications unknown
   
   
       Date         Developer_name      Modifications
   //  22-05-2024   Devanathan          Page creation
   
   -->
<?php
   //   if (isset($_POST['couponid'])) {
   //   	$couponid = $_POST['couponid'];
   //   	unset($_POST['couponid']);
   //   } else {
   //   	$couponid = '';
   //   }
   
      
      ?>
      
      <?php
$Coupon_name = mysqli_query($con, "SELECT c_name FROM couponcode GROUP BY c_name, productid;");

// Output data as JSON
$output = [];
if (mysqli_num_rows($Coupon_name) > 0) {
   while ($row = mysqli_fetch_assoc($Coupon_name)) {
      $output[] = $row;
   }
}
// echo json_encode($output);

?>
<style>
   .dt-buttons.btn-group.flex-wrap {
   position: initial;
   float: left;
   }
   div.container {
   width: 80%;
   }
   #showsuccessalert {
   display: none;
   }
   #showerroralert {
   display: none;
   }
   form.datefilter {
   float: right;
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
   border: 1px solid #CCC;
   }
   button {
   color: #FFF;
   background-color: #428BCA;
   border: 1px solid #357EBD;
   }
   textarea {
   width: 100%;
   height: 150px;
   padding: 12px 20px;
   box-sizing: border-box;
   border: 2px solid #ccc;
   border-radius: 4px;
   background-color: #f8f8f8;
   font-size: 16px;
   resize: none;
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
   .pd-20 {
   padding: 0 37px 0 0;
   }
   button.btn-style {
   background: #1170e4;
   padding: 4px;
   height: 38px;
   border: navajowhite;
   color: #fff;
   border-radius: 7px;
   width: 38px;
   margin: 38px 0 0 5px;
   }
   button.btn-style1 {
   background: #1170e4;
   padding: 2px;
   height: 29px;
   border: navajowhite;
   color: #fff;
   border-radius: 7px;
   width: 42px;
   margin: 5px 5px 0 4px;
   }
   div#enternewticketlines select {
   padding: 0.475rem 0.75rem;
   font-size: 0.875rem;
   border-radius: 7px;
   margin-top: 9px;
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
      <div class="main-container container-fluid">
         <div class="page-header">
            <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Coupon History</h1>
            <div>
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Coupon History</li>
               </ol>
            </div>
         </div>
      </div>
      <div class="row row-sm">
         <div class="col-lg-12">
            <div class="card">
               <div class="card-header">
                  <div class="row">
                     <div class="col-12 mb-2">
                        <h3 class="card-title"><strong>Ticket History</strong></h3>
                     </div>
                     <div class="col-md-10   col-lg-4  mb-2">
                        <span>Select Date</span> &nbsp; <span style="color:red;">*</span>
                        <div>
                           
                           <input class="form-control" type="text" id="datefilter" name="datefilter" value="" placeholder="YYYY-MM-DD" autocomplete="off" maxlength="10" oninput="this.value = this.value.replace(/[^0-9-]/g, '');" />
                        </div>
                     </div>
                     <div class="col-md-10   col-lg-4  mb-2">
                         
                          <span>Select Coupon Name</span> &nbsp; <span style="color:red;"></span>
                                    <select id="searchTxt" class="form-control">
                                       <option value="" selected>Select name</option>
                                       <?php foreach ($output as $item) : ?>
                                          <option value="<?php echo $item['c_name']; ?>"><?php echo $item['c_name']; ?></option>
                                       <?php endforeach; ?>
                                    </select>
                        <!--<span>Search </span>-->
                        <!--<input class="form-control" type="text" oninput="searchTicket($(this).val())" id="searchTxt" placeholder=" Coupon Code name">-->

                     </div>
                     <div class="col-lg-4 col-md-2 mb-2">
                        <br>
                        <button class="btn btn-primary" onclick="viewtable()">GO</button>
                     </div>
                  </div>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-bordered text-nowrap border-bottom" id="wtickettable" style="width:100%;">
                        <thead>
                           <tr>
                              <th class="wd-20p border-bottom-0">Created Date/time</th>
                              <th class="wd-15p border-bottom-0">coupon Code Name</th>
                              <th class="wd-20p border-bottom-0">Product Amount (AED)</th>
                              <th class="wd-20p border-bottom-0">Start Date</th>
                              <th class="wd-15p border-bottom-0">End Date</th>
                              <th class="wd-25p border-bottom-0">NO of Coupon Created</th>
                              <th class="wd-20p border-bottom-0">NO of Coupon used</th>
                              <th class="wd-15p border-bottom-0">NO of coupon not Used</th>
                              <th class="wd-15p border-bottom-0">Status</th>
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
                              <th></th>
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
<script>
   var couponid = '<?= $couponid; ?>';
   //       (function()  {
   //   	createDatePricket('reportrange');
   //     		viewtable();
   //   })();
   
    $(function() {
   viewtable();
      $('input[name="datefilter"]').daterangepicker({
          
        //   alert('king');
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
   
   
   
   
   function viewtable() {
    // var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
    // var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");
     var newdate = $('#datefilter').val();
      // alert(deva);
      if (newdate != '') {
         var formdate = moment($('#datefilter').data('daterangepicker').startDate).format("YYYY-MM-DD HH:mm:ss");
         var todate = moment($('#datefilter').data('daterangepicker').endDate).format("YYYY-MM-DD HH:mm:ss");
      } else {
         var formdate = '';
         var todate = '';
      }
    var title = 'Coupon Ticket History : (' + formdate + '  to  ' + todate + ')';
    let search_id = $('#searchTxt').val();
   
    var table = $("#wtickettable").DataTable({
      destroy: true,
      pageLength: 10,
      columnDefs: [{
          targets: -1,
          orderable: false,
          searchable: true,
          className: 'control',
        },
        {
          targets: 0,
          orderable: false,
          searchable: true,
          className: 'selectall-checkbox',
        },
        {
          type: 'date',
          targets: [8],
        }
      ],
      select: {
        style: 'multi',
        selector: 'td:first-child',
      },
      order: [
        [8, 'desc']
      ],
      paging: true,
      searching: true,
      info: true,
      ajax: {
        url: origin + "/ajax/service/coupon_services.php",
        method: "POST",
        dataSrc: "",
        data: {
          method: 'list_coupon_history',
          formdate: formdate,
          todate: todate,
          couponid: couponid,
          search_id: search_id,
        },
      },
      dom: 'Bfrtip',
      buttons: [
          {
          extend: 'copyHtml5',
          title: title,
          titleAttr: 'Copy to Clipboard',
        },
       
        {
          extend: 'excelHtml5',
          title: title,
          titleAttr: 'Export as Excel',
          exportOptions: {
              columns: 'th:not(:last-child)'
           }
        },
       
      ],
      columns: [
          {
          data: null,
          render: function(data, type, row, meta) {
            return moment(data.createdon).format("DD MMM YYYY hh:mm a");
          },
        },
        {
          data: 'c_name'
        },
        {
            data: 'productid',
            render: function(data, type, row, meta) {
                if (data == '1') { return '1.00'; }
                else if (data == '2') { return '7.00'; }
                else if (data == '3') { return '30.00'; }
                else if (data == '4') { return '360.00'; }
                else { return data; }
            }
        },
        {
          data: null,
          render: function(data, type, row, meta) {
            return moment(data.c_started_at).format("DD MMM YYYY hh:mm a");
          },
        },
        {
          data: null,
          render: function(data, type, row, meta) {
            return moment(data.c_ended_at).format("DD MMM YYYY hh:mm a");
          },
        },
   
        {
          data: 'count'
        },
        {
          data: 'user'
        },
        {
          data: 'not_used'
        },
        
        // {
        //   data: 'null'
        // },
        {
                    data: "status",
                    render: function(data, type, row, meta) {
                        var currentDate = new Date();
                        var endDate = new Date(row.c_ended_at);
                        if (endDate < currentDate) {
                            return "Inactive";
                        }else {
                            return "Active"
                        }
                        return data;
                    }
                },
       
         {
                data: null,
                render: function(data, type, row, meta) {
                    var Turl = origin + "/ticketHistoryPay/list/" + data.id;
                    var ticketview = origin + "/pages/view-ticket.php?ticketNo=" + data.ticketNo;
                    return `
                    
                    <a target="_blank" href="<?= $adminurl; ?>coupon-ticket/list/${data.c_name}" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets">
                            <span style="font-size: 22px;" class="fa fa-users"></span>
                        </a>
                    `;
                    
                    // <a target="_blank" href="<?= $adminurl; ?>coupon-ticket/list/${data.c_name}" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets">
                    //         <span style="font-size: 22px;" class="fa fa-users"></span>
                    //     </a>
                    // <a target="_blank" href="<?=$baseurl;?>ticket-view/${data.referenceID}" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span style="font-size: 18px;" class="fa fa-files-o"></span></a>
                }
            },
      ],
   footerCallback: function(row, data, start, end, display) {
    var api = this.api();
    var intVal = function(i) {
        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
    };
    
    // Calculate total for column 6
    var total7 = api.column(7).data().reduce(function(a, b) {
        return intVal(a) + intVal(b);
    }, 0);

    // Calculate total for column 6
    var total6 = api.column(6).data().reduce(function(a, b) {
        return intVal(a) + intVal(b);
    }, 0);

    // Calculate total for column 5
    var total5 = api.column(5).data().reduce(function(a, b) {
        return intVal(a) + intVal(b);
    }, 0);

    // Append the totals to the footer
    // $(api.column(6).footer()).html('Page Total: ' + total6.toFixed(2) + '<br/>Total: ' + total6.toFixed(2));
    $(api.column(7).footer()).html('Total: ' + total7.toFixed(2));
    $(api.column(6).footer()).html('Total: ' + total6.toFixed(2));
    $(api.column(5).footer()).html('Total: ' + total5.toFixed(2));
},

    });
   
    couponid = '';
   }
   
   
   function paymentSuccess(icon, titlestr) {
   
   	Swal.fire({
   
   		title: titlestr,
   
   		icon: icon,
   
   		confirmButtonColor: '#3085d6',
   
   		confirmButtonText: 'OKAY',
   
   		allowOutsideClick: false
   
   	}).then((result) => {
   
   		if (result.isConfirmed) {
   
   			viewtable();
   
   		}
   
   	})
   
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