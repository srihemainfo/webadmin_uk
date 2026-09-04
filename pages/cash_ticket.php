<?php
/**
 * Date           Developer                   Modifications
 * 9-6-2023        Prakash                      Cash Ticket has been displayed
 */






?>





<style>
	.dt-buttons.btn-group.flex-wrap {
		position: initial;
		float: left
	}

	div.container {
		width: 80%
	}

	#showerroralert,
	#showsuccessalert {
		display: none
	}

	form.datefilter {
		float: right
	}

	button,
	input {
		height: 35px;
		margin: 0;
		padding: 6px 12px;
		border-radius: 2px;
		font-family: inherit;
		font-size: 100%;
		color: inherit;
		border: 1px solid #ccc
	}

	button {
		color: #fff;
		background-color: #428bca;
		border: 1px solid #357ebd
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
		resize: none
	}

	.back-arrow-btn i {
		background: #fff;
		font-size: 16px;
		padding: 2px 3px;
		border-radius: 50px;
		border: 2px solid #6c6e70;
		color: #6c6e70;
		margin-right: 15px;
		width: 24px;
		height: 24px
	}

	.pd-20 {
		padding: 0 37px 0 0
	}

	button.btn-style {
		background: #1170e4;
		padding: 4px;
		height: 38px;
		border: #ffdead;
		color: #fff;
		border-radius: 7px;
		width: 38px;
		margin: 38px 0 0 5px
	}

	button.btn-style1 {
		background: #1170e4;
		padding: 2px;
		height: 29px;
		border: #ffdead;
		color: #fff;
		border-radius: 7px;
		width: 42px;
		margin: 5px 5px 0 4px
	}

	div#enternewticketlines select {
		padding: .475rem .75rem;
		font-size: .875rem;
		border-radius: 7px;
		margin-top: 9px
	}



	.deleteBTN {

		color: red;

		border: none;

		background: no-repeat;



	}
</style>



<script>
	window.onload = function() {
		var n = window.location.origin;
		document.getElementById("anchor").href = n
	};
</script>





<div class="main-content app-content mt-0">







	<div class="side-app">







		<div class="main-container container-fluid">



			<div class="page-header">



				<h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Cash Tickets</h1>



				<div>



					<ol class="breadcrumb">



						<li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>



						<li class="breadcrumb-item active" aria-current="page">Cash Tickets</li>



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
								<input type="hidden" id="fieldname">
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
										<!-- <th class="wd-15p border-bottom-0">Last Name</th> -->


										<th class="wd-15p border-bottom-0">Mobile Number</th>

										<th class="wd-20p border-bottom-0">Email ID</th>

										<th class="wd-25p border-bottom-0">My3Numbers</th>

										<th class="wd-20p border-bottom-0">Raffle ID</th>

										<th class="wd-20p border-bottom-0">Product Amount (AED)</th>

										<!--<th class="wd-20p border-bottom-0">Coupon Code</th>-->

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

										<!-- <th></th> -->

										<th></th>

										<th></th>

										<th></th>

										<th></th>

										<!--<th></th>-->

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























<div class="modal fade" id="deleteoModal">



	<div class="modal-dialog modal-sm" role="document">



		<div class="modal-content modal-content-demo">



			<div class="modal-header">



				<h6 class="modal-title">Confirm</h6>



				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">



					<span aria-hidden="true">×</span>



				</button>



			</div>



			<div class="modal-body">



				<div id="deleteError"></div>



				<div id="">







					<textarea id="deletemessage" cols="30" rows="10" placeholder="Reason"></textarea>



				</div>



			</div>



			<div class="modal-footer">



				<button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button>



				<div id="delete_btn">







				</div>



			</div>



		</div>



	</div>



</div>























<script>
	// 	var couponid = '<?= $couponid; ?>';

	$(document).ready(function() {



		createDatePricket('reportrange');

		var searchValue = $(location).attr('href').split('?')[1];
		if (searchValue != '' && searchValue != undefined) {
			$('#fieldname').val(unescape(searchValue));
			$('#overall_search').val(unescape(searchValue));
		}
		viewtable();

	});



	function viewtable() {



		var table = $('#wtickettable').DataTable();

		table.destroy();





		var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");

		var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");





		var title = 'Cash Ticket : (' + formdate + '  to  ' + todate + ')';
		var fieldname = $('#fieldname').val();


		table = $("#wtickettable").DataTable({



			pageLength: 10,



			// responsive: {

			// 	details: {

			// 		type: 'column',

			// 		target: -1,

			// 	}

			// },

			columnDefs: [{

				targets: -1,

				orderable: false,

				searchable: true,

				className: 'control',

			}, {

				targets: 0,

				orderable: false,

				searchable: true,

				className: 'selectall-checkbox',

			}],

			select: {

				style: 'multi',

				selector: 'td:first-child',

			},

			order: [0, 'desc'],



			paging: true,



			searching: true,



			info: true,



			ajax: {



				url: window.location.origin + "/ajax/service/points_services.php",



				method: "POST",



				dataSrc: "",



				data: {



					method: 'list_cash_ticket',



					formdate: formdate,



					todate: todate,
					fieldname: fieldname,

					// 	couponid: couponid



				}



			},



			dom: 'Bfrtip',



			buttons: [



				'pageLength',



				{



					extend: 'copyHtml5',



					title: title



				},



				{



					extend: 'csvHtml5',



					title: title



				},



				{



					extend: 'excelHtml5',



					title: title



				},



				{



					extend: 'pdfHtml5',



					orientation: 'landscape',



					pageSize: 'LEGAL',



					title: title



				},



				{



					extend: 'print',



					title: title







				},







			],



			columns: [







				{



					data: 'ticketno'



				},





				{



					data: 'cusname'



				},
				// {



				// 	data: 'lname'



				// },



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



				// {



				// 	data: 'codeC'



				// },



				{



					data: 'purdate'



				},



				{



					data: 'action'



				}



				// {

				// 	data: null,

				// 	render: function(data, type, row, meta) {

				// 		return ''

				// 	}

				// }







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

		// 		couponid = '';

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









	function deletecpticket(trans_id) {



		if (trans_id != '') {

			// 	document.getElementById('transid').value = trans_id;

			$('#delete_btn').html(`<button class="btn ripple btn-danger" onclick="confirmcpDelete('${trans_id}', $('#deletemessage').val())" type="button">Submit</button>`);

			$('#deleteoModal').modal('show');

		} else {

			toast('error', 'Transaction  id missing!');

		}









	}









	function confirmcpDelete(id, Dmessage) {



		$('#deleteError').html('');



		if (id == '') {

			$('#deleteError').html(`<div class="alert alert-danger" role="alert">Transaction ID Missing. Kindly Refresh and Try Again!</div>`);

			return false;

		}



		if (Dmessage == '') {

			$('#deleteError').html(`<div class="alert alert-danger" role="alert">Kindly Enter the Reason!</div>`);

			return false;

		}















		let formdata = [];



		formdata.push({



			name: 'method',



			value: "deletecp_Ticket"



		}, {



			name: 'transid',



			value: id



		}, {



			name: 'message',



			value: Dmessage



		});





		var post_data = formdata;

		var nbtn = $('#delete_btn').html();



		$('#delete_btn').html(`<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>`);

		var onsuccess = function(data) {



			var response = JSON.parse(data);



			if (response != "") {



				if (response.type == 1) {



					// 		document.getElementById('delete_btn').innerHTML = '<button class="btn ripple btn-danger" onclick="confirmDelete($(' + "'#transid'" + ').val(), $(' + "'#deletemessage'" + ').val())" type="button">Submit</button>';



					$('#delete_btn').html(nbtn);



					var table = $('#otickettable').DataTable();



					table.destroy();



					viewtable();



					$('#deleteoModal').modal('hide');



					// 		document.getElementById('successerrorot').innerHTML = '<div class="alert alert-success" role="alert">' + response.result + '</div>';

					toast('success', response.result);

					// 		$('#sussessmodalot').modal('show');



				} else {



					// 		document.getElementById('successerrorot').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';

					toast('error', response.result);

					// 		$('#sussessmodalot').modal('show');

					$('#delete_btn').html(nbtn);



				}



			}



		}



		do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/points_services.php");







	}
</script>