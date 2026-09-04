<?php
 /* Date               Developer                   Modification
//  25-09-2023   Divya             table for customer support
 */
$AEDcount = 12;

$tabID = $subid1;



if ($tabID == 'customer' || $tabID == 'offline-ticket' || $tabID == 'online-ticket') {

	$title = 'My Customer';

	$idname = 'Customer ID';

	$addName = 'Customer';
} else if ($tabID == 'agents') {

	$title = 'Agents List';

	$idname = 'Agent ID';

	$addName = 'Agent';
} else {

	$title = 'My Staffs';

	$idname = 'Staff ID';

	$addName = 'Staff';
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
</style>

<script>
	window.onload = function() {

		var page_origin = window.location.origin;

		let anchor = document.getElementById("anchor");

		anchor.href = page_origin;

	}
</script>

<input type="hidden" id="tabID" value="<?= $tabID; ?>">

<div class="main-content app-content mt-0">



	<div class="side-app">



		<div class="main-container container-fluid">

			<div class="page-header">

				<h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Online Tickets</h1>

				<div>

					<ol class="breadcrumb">

						<li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

						<li class="breadcrumb-item active" aria-current="page">Online Tickets</li>

					</ol>

				</div>

			</div>

		</div>





		<div class="row row-sm">

			<div class="col-lg-12">

				<div class="card">

					<div class="card-header">



						<div class="col-lg-4">

							<h3 class="card-title"><strong>Ticket List</strong></h3>

						</div>

						<div class="col-lg-8">



							<input type="date" name="formdate" id="formdate" value="<?php echo date("Y-m-d"); ?>">

							<input type="date" name="todate" id="todate" value="<?php echo date("Y-m-d"); ?>">
							<input type="hidden" name="fieldname" id="fieldname" value="" class="form-control">
							<button onclick="viewtable()">GO</button>







							<?php if ($_SESSION['memid'] == 1) { ?>

								<!-- <button type="button" id="smallmodal" onclick="downloadxlsx()" class="btn btn-info"><i class="fa fa-download" aria-hidden="true"></i>Downloads</button> -->

							<?php } ?>

							<div class="modal fade" id="otdownload">

								<div class="modal-dialog modal-sm" role="document">

									<div class="modal-content modal-content-demo">

										<div class="modal-header">

											<h6 class="modal-title">Download Report</h6>

											<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

												<span aria-hidden="true">×</span>

											</button>

										</div>

										<div class="modal-body">

											<div class="row">



												<div class="col-sm-12">

													<form class="login100-form validate-form">



														<div class="wrap-input100 validate-input input-group">

															<form>

																<div class="form-check form-check-inline">

																	<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">

																	<label class="form-check-label" for="inlineRadio1">Excel</label>

																</div>

																<div class="form-check form-check-inline">

																	<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">

																	<label class="form-check-label" for="inlineRadio2">Pdf</label>

																</div>

															</form>

														</div>

												</div>

											</div>

										</div>

										<div class="modal-footer">

											<button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button>

											<button class="btn ripple btn-success" id="smallmodal" data-bs-toggle="modal" data-bs-target="#" type="button">Ok</button>

										</div>

									</div>

								</div>

							</div>

						</div>

					</div>

					<div class="card-body">

						<div class="table-responsive">

							<table class="table table-bordered text-nowrap border-bottom" id="otickettable" style="width:100%;">

								<thead>

									<tr>

										<th class="wd-15p border-bottom-0">Ticket ID</th>

										<th class="wd-15p border-bottom-0">Customer Name</th>

										<th class="wd-15p border-bottom-0">Mobile Number</th>

										<th class="wd-20p border-bottom-0">Email ID</th>

										<th class="wd-25p border-bottom-0">My3Numbers</th>

										<th class="wd-20p border-bottom-0">Raffle ID</th>

										<th class="wd-20p border-bottom-0">Product Amount (AED)</th>

										<th class="wd-25p border-bottom-0">Purchase Date & Timing</th>

										<th class="wd-15p border-bottom-0">Action</th>

									</tr>

								</thead>

								<tbody>

								</tbody>
								<tfoot>
									<tr>
										<th colspan="5"></th>
										<th>Total</th>
										<th></th>
										<th colspan="2"></th>
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

					<input type="hidden" id="transid">

					<textarea id="deletemessage" cols="30" rows="10" placeholder="Reason"></textarea>

				</div>

			</div>

			<div class="modal-footer">

				<button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button>

				<div id="delete_btn">

					<button class="btn ripple btn-danger" onclick="confirmoDelete($('#transid').val(), $('#deletemessage').val())" type="button">Submit</button>

				</div>

			</div>

		</div>

	</div>

</div>







<div class="modal fade" id="sussessmodalot">

	<div class="modal-dialog modal-sm" role="document">

		<div class="modal-content modal-content-demo">

			<div class="modal-header">

				<h6 class="modal-title">Success</h6>

				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">×</span>

				</button>

			</div>

			<div class="modal-body">

				<div id="successerrorot">



				</div>

			</div>

			<div class="modal-footer">

				<button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button>

			</div>

		</div>

	</div>

</div>







<!-- Modal -->

<div class="modal fade" id="sccessModalData" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

	<div class="modal-dialog" role="document">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title" id="exampleModalLabel">Result</h5>

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">&times;</span>

				</button>

			</div>

			<div class="modal-body">

				<div id="emailerror"></div>

			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-secondary" onclick="closeModal('sccessModalData')">Close</button>

				<!-- <button type="button" class="btn btn-primary">Save changes</button> -->

			</div>

		</div>

	</div>

</div>









<script>
	var origin = window.location.origin;

	var url = origin + "/ajax/service/ot_services.php";

	var datatableurl = origin + "/ajax/service/datatable_services.php";

	$(function() {


		var searchValue = $(location).attr('href').split('?')[1];
        if (searchValue != '') {
            $('#fieldname').val(unescape(searchValue));
            viewtable();
        }else{
			viewtable();
        }



		showResult('');

		$(".tno").prop('disabled', true);

		$(".packagenoc").prop('disabled', true);

		$("#buybtn").hide();

		$("#previewticket").hide();

		$("#enterticketlines").hide();

		$("#currentdrawID").hide();

		$(".ticketidhide").hide();

		$(".increment").hide();



	});



	function viewtable() {

		var table = $('#otickettable').DataTable();

		table.destroy();



		let formdate = $('#formdate').val();

		let todate = $('#todate').val();
		let fieldname = $('#fieldname').val();


		table = $("#otickettable").DataTable({

			pageLength: 10,

			order: [],

			paging: true,

			searching: true,

			info: true,

			ajax: {

				url: datatableurl,

				method: "POST",

				dataSrc: "",

				data: {

					method: 'list_oticket',

					formdate: formdate,
					fieldname: fieldname,

					todate: todate

				}

			},

// 			dom: 'Bfrtip',

// 			buttons: [

// 				'pageLength',

// 				{

// 					extend: 'copyHtml5',

// 					title: 'Online Ticket Reports : (' + formdate + '  to  ' + todate + ')'

// 				},

// 				{

// 					extend: 'csvHtml5',

// 					title: 'Online Ticket Reports : (' + formdate + '  to ' + todate + ')'

// 				},

// 				{

// 					extend: 'excelHtml5',

// 					title: 'Online Ticket Reports : (' + formdate + '  to ' + todate + ')'

// 				},

// 				{

// 					extend: 'pdfHtml5',

// 					orientation: 'landscape',

// 					pageSize: 'LEGAL',

// 					title: 'Online Ticket Reports : (' + formdate + '  to  ' + todate + ')'

// 				},

// 				{

// 					extend: 'print',

// 					title: 'Online Ticket Reports: (' + formdate + '  to ' + todate + ')'



// 				},



// 			],

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

					data: 'purdate'

				},

				{

					data: 'action'

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



	function deleteoticket(trans_id) {



		document.getElementById('transid').value = trans_id;

		$('#deleteoModal').modal('show');



	}



	function confirmoDelete(id, Dmessage) {

		document.getElementById('deleteError').innerHTML = '';

		if (Dmessage != '') {

			document.getElementById('delete_btn').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';

			let formdata = [];

			formdata.push({

				name: 'method',

				value: "deleteo_Ticket"

			});

			formdata.push({

				name: 'transid',

				value: id

			});

			formdata.push({

				name: 'message',

				value: Dmessage

			});

			var post_data = formdata;



			var onsuccess = function(data) {

				var response = JSON.parse(data);

				if (response != "") {

					if (response.type == 1) {

						document.getElementById('delete_btn').innerHTML = '<button class="btn ripple btn-danger" onclick="confirmDelete($(' + "'#transid'" + ').val(), $(' + "'#deletemessage'" + ').val())" type="button">Submit</button>';



						var table = $('#otickettable').DataTable();

						table.destroy();

						viewtable();

						$('#deleteoModal').modal('hide');

						document.getElementById('successerrorot').innerHTML = '<div class="alert alert-success" role="alert">' + response.result + '</div>';

						$('#sussessmodalot').modal('show');

					} else {

						document.getElementById('successerrorot').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';

						$('#sussessmodalot').modal('show');

					}

				}

			}

			do_ajax_call(post_data, onsuccess, url);

		} else {

			document.getElementById('deleteError').innerHTML = '<div class="alert alert-danger" role="alert">Please Fill The Reason.</div>';

		}



	}



	function downloadxlsx() {



		var url = origin + "/xlsx/file.php";

		var table = $('#mtickettable').DataTable();

		table.destroy();

		var formdata = [];

		formdata.push({

			name: 'method',

			value: "file_download"

		});





		let formdate = $('#formdate').val();

		let todate = $('#todate').val();





		formdata.push({

			name: 'formdate',

			value: formdate

		});

		formdata.push({

			name: 'todate',

			value: todate

		});



		formdata.push({

			name: 'tablename',

			value: 'ticket'

		});



		var post_data = formdata;



		var onsuccess = function(data) {



			var response = JSON.parse(data);

			if (response != "") {

				if (response.type == 1) {

					download(response.url, response.filename)



				} else {



				}



			}

		}



		do_ajax_call(post_data, onsuccess, url);











	}



	function download(url, filename) {

		fetch(url)

			.then(response => response.blob())

			.then(blob => {

				const link = document.createElement("a");

				link.href = URL.createObjectURL(blob);

				link.download = filename;

				link.click();

			})

			.catch(console.error);

	}



	function sendemailtopurchase(transid) {



		swal.fire({

			title: 'Do you want to resent email?',

			showCancelButton: true,

			allowOutsideClick: false,

			confirmButtonText: 'Send Email',

		}).then(function(result) {

			if (result.isConfirmed) {

				var formdata = [];

				formdata.push({

					name: 'method',

					value: "sendemailtopurchase"

				});

				formdata.push({

					name: 'transid',

					value: transid

				});

				formdata.push({

					name: 'ottype',

					value: 'OT'

				});

				formdata.push({

					name: 'tablename',

					value: 'ticket'

				});

				var post_data = formdata;

				var onsuccess = function(data) {

					var response = JSON.parse(data);

					if (response != "") {

						if (response.type == 1) {

							paymentSuccess('success', response.result);

							// viewtable();

						} else {

							paymentSuccess('error', response.result);

						}

					}

				}

				do_ajax_call(post_data, onsuccess, url);





			}

		});

	}



	function sendsmstopurchase(transid) {



		swal.fire({

			title: 'Do you want to resent SMS?',

			showCancelButton: true,

			allowOutsideClick: false,

			confirmButtonText: 'Send SMS',

		}).then(function(result) {

			if (result.isConfirmed) {

				var formdata = [];

				formdata.push({

					name: 'method',

					value: "sendsmstopurchase"

				});

				formdata.push({

					name: 'transid',

					value: transid

				});

				formdata.push({

					name: 'ottype',

					value: 'OT'

				});

				formdata.push({

					name: 'tablename',

					value: 'ticket'

				});

				var post_data = formdata;

				var onsuccess = function(data) {

					var response = JSON.parse(data);

					if (response != "") {

						if (response.type == 1) {

							paymentSuccess('success', response.result);

						} else {

							paymentSuccess('error', response.result);

						}

					}

				}

				do_ajax_call(post_data, onsuccess, url);





			}

		});

	}



	function closeModal(id) {

		$('#' + id).modal('hide');

	}



	function bothemailsms(transid) {



		swal.fire({

			title: 'Do you want to resent SMS & Email?',

			showCancelButton: true,

			allowOutsideClick: false,

			confirmButtonText: 'Send',

		}).then(function(result) {

			if (result.isConfirmed) {

				var formdata = [];

				formdata.push({

					name: 'method',

					value: "bothemailsms"

				});

				formdata.push({

					name: 'transid',

					value: transid

				});

				formdata.push({

					name: 'ottype',

					value: 'OT'

				});

				formdata.push({

					name: 'tablename',

					value: 'ticket'

				});

				var post_data = formdata;

				var onsuccess = function(data) {

					var response = JSON.parse(data);

					if (response != "") {



						for (let i = 0; i < response.length; i++) {

							if (response[i]['type'] == 1) {

								paymentSuccess('success', response[i]['result']);

							} else {

								paymentSuccess('error', response[i]['result']);

							}

						}



					}

				}

				do_ajax_call(post_data, onsuccess, url);





			}

		});

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





<style>
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