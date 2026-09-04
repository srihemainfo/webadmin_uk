<style>
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



	input,

	select {

		border: 1px solid #CCC;

	}



	button {

		color: #FFF;

		background-color: #428BCA;

		border: 1px solid #357EBD;

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



		<div class="main-container container-fluid">



			<div class="page-header">

				<h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Generate Points</h1>

				<div>

					<ol class="breadcrumb">

						<li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

						<li class="breadcrumb-item active" aria-current="page">Generate Points</li>

					</ol>

				</div>

			</div>







			<div class="row">

				<div class="col-md-6">

					<div class="card">

						<div class="card-header">

							<h3 class="card-title">Generate Points</h3>

						</div>

						<div class="card-body">

							<div id="ldberr"></div>

							<div class="form-group ">

								<input type="text" class="form-control" id="totalPoint" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Enter Points">

							</div>







							<div class="form-footer mt-2">

								<button class="btn btn-primary" onclick="generatePoint($('#totalPoint').val())">Generate Points</button>

								<!-- <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#otp" class="btn btn-primary">Generate Points</a> -->

							</div>

						</div>

					</div>

				</div>





				<div class="col-sm-6 ">

					<div class="card">

						<div class="card-body text-center">

							<h6 class=""><span class="text-primary"></span><b>LD BANK Total Points<b></h6>

							<h3 class="text-dark counter mt-0 mb-3 number-font" id="bank_balance"></h3>



							<!-- <div class="chat-details mb-1 p-3">

								<h4 class="mb-0">

									<span class="h5 fw-normal">Shared Points</span>

									<span class="float-end p-1  btn btn-sm text-default">

										<b>25</b>%</span>

								</h4>

								<div class="progress progress-md">

									<div class="progress-bar bg-info-gradient" style="width: 25%;"></div>

								</div>

							</div>

							<div class="row mt-4">

								<div class="col text-center"> <span class="text-muted">Draw Name</span>

									<h4 class="fw-normal mt-2 mb-0 number-font1">D12</h4>

								</div>

								<div class="col text-center"> <span class="text-muted">Draw Date</span>

									<h4 class="fw-normal mt-2 mb-0 number-font2">30/5/2022</h4>

								</div>

								<div class="col text-center"> <span class="text-muted">Remaining Days</span>

									<h4 class="fw-normal mt-2 mb-0 number-font3">13</h4>

								</div>

							</div> -->

						</div>

					</div>

				</div>

			</div>





			<div class="row row-sm">

				<div class="col-lg-12">

					<div class="card">

						<div class="card-header">

							<div class="col-lg-4">

								<h3 class="card-title"><strong id="tabtitle2">Transaction History</strong></h3>

							</div>

							<div class="col-lg-8">

								<input type="date" name="formdate" id="formdate" value="<?php echo date("Y-m-d"); ?>">

								<input type="date" name="todate" id="todate" value="<?php echo date("Y-m-d"); ?>">

								<button onclick="viewtable()">GO</button>

							</div>

						</div>

						<div class="card-body">

							<div class="table-responsive">

								<table class="table table-bordered text-nowrap border-bottom" id="ldpointtable" style="width:100%;">

									<thead>

										<tr>

											<th class="wd-15p border-bottom-0">Date & Time</th>

											<th class="wd-15p border-bottom-0">Seller Name</th>

											<th class="wd-15p border-bottom-0">Points Transaction</th>

											<th class="wd-15p border-bottom-0">Payment Type</th>

											<th class="wd-15p border-bottom-0">Order Type</th>

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

		</div>

	</div>

</div>

</div>







<div class="modal  fade" id="generatepointmodal" tabindex="-1" role="dialog">

	<div class="modal-dialog modal-sm" role="document">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title">Point Transfer</h5>

				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">×</span>

				</button>

			</div>

			<div class="modal-body">

				<div id="ldberr2"></div>

			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-secondary" onclick="lbmodalclose()">Close</button>

			</div>

		</div>



	</div>

</div>









<script>
	var origin = window.location.origin;

	var url = origin + "/ajax/service/transaction_services.php";



	$(function() {

		ldbank_balance();

		viewtable();

	});



	function viewtable() {



		var table = $('#ldpointtable').DataTable();

		table.destroy();



		var formdata = [];

		formdata.push({

			name: 'method',

			value: "list_ld_points"

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

		var post_data = formdata;

		var onsuccess = function(data) {

			var response = JSON.parse(data);

			if (response != "") {

				if (response.type == 1) {





					$('#ldpointtable').DataTable({

						order: [

							[0, 'desc']

						],

						dom: 'Bfrtip',

						buttons: [

							'copy', 'csv', 'excel', 'pdf', 'print'

						],

						"data": response.result,

						"columns": [{

								'data': 'date'

							},

							{

								'data': 'cusname'

							},

							{

								'data': 'points'

							},

							{

								'data': 'paymenttype'

							},

							{

								'data': 'ordertype'

							},



							{

								'data': 'status'

							},

						],

					});





				} else {

					var table = $('#ldpointtable').DataTable();

					table.clear().draw();

				}



			}

		}

		do_ajax_call(post_data, onsuccess, url);

	}



	function ldbank_balance() {

		var formdata = [];

		formdata.push({

			name: 'method',

			value: "ldbank_balance"

		});

		var post_data = formdata;

		var onsuccess = function(data) {



			var response = JSON.parse(data);

			if (response != "") {

				if (response.type == 1) {

					document.getElementById('bank_balance').innerText = 'AED ' + response.result;

				} else {

					document.getElementById('bank_balance').innerText = response.result;

				}



			}

		}

		do_ajax_call(post_data, onsuccess, url);

	}





	function generatePoint(totalPoint) {

		document.getElementById('ldberr').innerHTML = '';

		if (totalPoint != '') {

			var formdata = [];

			formdata.push({

				name: 'method',

				value: "transfer_point_to_admin"

			});

			formdata.push({

				name: 'totalpoint',

				value: totalPoint

			});

			var post_data = formdata;

			var onsuccess = function(data) {



				var response = JSON.parse(data);

				if (response != "") {

					if (response.type == 1) {

						document.getElementById('ldberr2').innerHTML = response.result;

						$('#generatepointmodal').modal('show');

					} else {

						document.getElementById('ldberr').innerHTML = response.result;

					}



				}

			}

			do_ajax_call(post_data, onsuccess, url);

		} else {

			document.getElementById('ldberr').innerHTML = '<div class="alert alert-warning" role="alert">Please Fill the Points.</div>';

		}

	}







	function lbmodalclose() {

		location.reload();

	}
</script>