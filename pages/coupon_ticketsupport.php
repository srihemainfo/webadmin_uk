<!--
1.modifications unknown


    Date         Developer_name      Modifications
//  25-09-2023   Divya             table for customer support

-->
<?php
if (isset($_POST['couponid'])) {
	$couponid = $_POST['couponid'];
	unset($_POST['couponid']);
} else {
	$couponid = '';
}


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

				<h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Coupon Tickets</h1>

				<div>

					<ol class="breadcrumb">

						<li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

						<li class="breadcrumb-item active" aria-current="page">Coupon Tickets</li>

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
								<h3 class="card-title"><strong>Ticket List</strong></h3>
							</div>

							<div class="col-md-10   col-lg-4  mb-2">
								<span>Select Date</span> &nbsp; <span style="color:red;">*</span>
								<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
									<i class="fa fa-calendar"></i>&nbsp;
									<span></span> <i class="fa fa-caret-down"></i>
								</div>
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

										<th class="wd-15p border-bottom-0">Ticket ID</th>
										<th class="wd-15p border-bottom-0">Customer Name</th>
										<th class="wd-15p border-bottom-0">Mobile Number</th>
										<th class="wd-20p border-bottom-0">Email ID</th>
										<th class="wd-25p border-bottom-0">My3Numbers</th>
										<th class="wd-20p border-bottom-0">Raffle ID</th>
										<th class="wd-20p border-bottom-0">Product Amount (AED)</th>
										<th class="wd-20p border-bottom-0">Coupon Code</th>
										<th class="wd-25p border-bottom-0">Purchase Date & Timing</th>
										<th class="wd-15p border-bottom-0">Action</th>
										<!-- <th class="wd-15p border-bottom-0"></th> -->
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
										<!-- <th></th> -->
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
    (function()  {
		createDatePricket('reportrange');
// 		viewtable();
	})();

// 	function viewtable() {

// 		var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
// 		var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");


// 		var title = 'Coupon Ticket Reports : (' + formdate + '  to  ' + todate + ')';
//       var table = $("#wtickettable").DataTable();
//       table.destory();
// 	  table = $("#wtickettable").DataTable({
            
// 			pageLength: 10,

			
// 			columnDefs: [{
// 				targets: -1,
// 				orderable: false,
// 				searchable: true,
// 				className: 'control',
// 			}, {
// 				targets: 0,
// 				orderable: false,
// 				searchable: true,
// 				className: 'selectall-checkbox',
// 			}],
// 			select: {
// 				style: 'multi',
// 				selector: 'td:first-child',
// 			},
// 	        	order: [
//                         [
//                             8, 'desc'
//                         ]
//                     ],
//                     columnDefs: [{
//                         type: 'date',
//                         targets: [8]
//                     }],

// 			paging: true,

// 			searching: true,

// 			info: true,

// 			ajax: {

// 				url: origin + "/ajax/service/coupon_services.php",

// 				method: "POST",

// 				dataSrc: "",

// 				data: {

// 					method: 'list_couponticket',

// 					formdate: formdate,

// 					todate: todate,
// 					couponid: couponid

// 				}

// 			},

// 			dom: 'Bfrtip',

// 			buttons: [

// 				'pageLength',

// 				{

// 					extend: 'copyHtml5',

// 					title: title

// 				},

// 				{

// 					extend: 'csvHtml5',

// 					title: title

// 				},

// 				{

// 					extend: 'excelHtml5',

// 					title: title

// 				},

// 				{

// 					extend: 'pdfHtml5',

// 					orientation: 'landscape',

// 					pageSize: 'LEGAL',

// 					title: title

// 				},

// 				{

// 					extend: 'print',

// 					title: title



// 				},



// 			],

// 			columns: [



// 				{

// 					data: 'ticketno'

// 				},


// 				{

// 					data: 'cusname'

// 				},

// 				{

// 					data: 'mobile'

// 				},

// 				{

//                   data: 'email'
                   
//                 },

// 				{

// 					data: 'My3Numbers'

// 				},

// 				{

//                     data: 'RaffleID'
                
//                 },
// 				{

// 					data: 'proamt'

// 				},
				
// 				{

// 					data: 'codeC'

// 				},
//                     {
//                             data: null,
//                             render: function(data, type, row, meta) {
//                                 return moment(data.purdate).format("DD MMM YYYY hh:mm a")
//                             }
//                         },
// 				{
// 					data: 'action'
// 				}

// 			],

// 			footerCallback: function(row, data, start, end, display) {
// 				var api = this.api();
// 				// Remove the formatting to get integer data for summation
// 				var intVal = function(i) {
// 					return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
// 				};

// 				// Total over all pages
// 				total = api
// 					.column(6)
// 					.data()
// 					.reduce(function(a, b) {
// 						let x = intVal(a) + intVal(b);
// 						return x.toFixed(2);
// 					}, 0);

// 				// Total over this page
// 				pageTotal = api
// 					.column(6, {
// 						page: 'current'
// 					})
// 					.data()
// 					.reduce(function(a, b) {
// 						return intVal(a) + intVal(b);
// 					}, 0);

// 				// Update footer
// 				$(api.column(6).footer()).html('' + pageTotal + ' ( ' + total + ' total)');

// 			}

// 		});
// 		couponid = '';
// 	}


function viewtable() {
  var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
  var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");
  var title = 'Coupon Ticket Reports : (' + formdate + '  to  ' + todate + ')';

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
        method: 'list_couponticket',
        formdate: formdate,
        todate: todate,
        couponid: couponid,
      },
    },
    // dom: 'Bfrtip',
    // buttons: [{
    //     extend: 'copyHtml5',
    //     title: title,
    //     titleAttr: 'Copy to Clipboard',
    //   },
    //   {
    //     extend: 'csvHtml5',
    //     title: title,
    //     titleAttr: 'Export as CSV',
    //   },
    //   {
    //     extend: 'excelHtml5',
    //     title: title,
    //     titleAttr: 'Export as Excel',
    //     exportOptions: {
    //         columns: 'th:not(:last-child)'
    //      }
    //   },
    //   {
    //     extend: 'pdfHtml5',
    //     orientation: 'landscape',
    //     pageSize: 'LEGAL',
    //     title: title,
    //     titleAttr: 'Export as PDF',
    //   },
    //   {
    //     extend: 'print',
    //     title: title,
    //     titleAttr: 'Print',
    //   },
    // ],
    columns: [{
        data: 'ticketno'
      },
      {
        data: 'cusname'
      },
      {
        data: 'mobile'
      },
      {
        data: 'email'
      },
      {
        data: 'My3Numbers'
      },
      {
        data: 'RaffleID'
      },
      {
        data: 'proamt'
      },
      {
        data: 'codeC'
      },
      {
        data: null,
        render: function(data, type, row, meta) {
          return moment(data.purdate).format("DD MMM YYYY hh:mm a");
        },
      },
      {
        data: 'action'
      },
    ],
    footerCallback: function(row, data, start, end, display) {
      var api = this.api();
      var intVal = function(i) {
        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
      };
      var total = api
        .column(6)
        .data()
        .reduce(function(a, b) {
          let x = intVal(a) + intVal(b);
          return x.toFixed(2);
        }, 0);
      var pageTotal = api
        .column(6, {
          page: 'current'
        })
        .data()
        .reduce(function(a, b) {
          return intVal(a) + intVal(b);
        }, 0);
      $(api.column(6).footer()).html('' + pageTotal + ' ( ' + total + ' total)');
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

	function createDatePricket(id) {
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
</script>