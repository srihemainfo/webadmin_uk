<?php
// Date        Developer     Changes
// 1-2-2023     Prakash       New Format Report Development
// 9-2-2023     Prakash       My3Number Block List Create.
// 17-2-2023    Prakash       Site Selection Option Development
// 3-3-2023     Prakash       Block My3Number Allow Concept Developed
// 03-05-2023   Prashant      Update the Order of Lucy3Number Formula

$pageTitle = 'Block My3Numbers';
$now = date("Y-m-d h:i:s");
$draw = select_query($con, "draw", "", "`status` = 'Active' AND `result_datetime` > '$now' AND `deletes` = '0' ORDER BY `result_datetime` ASC LIMIT 1", "", "");
$resultDate = date_format(date_create($draw['result'][0]['result_datetime']), "Y-m-d h:i:s a");

?>

<style>
	.iti.iti--allow-dropdown.iti--separate-dial-code,
	table {
		width: 100%
	}

	div#reportrange i,
	div#reportrange213 i {
		background: 0 0;
		color: unset;
		padding: 0;
		border-radius: 5px;
		margin: 0
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

	.table-dark td,
	.table-dark th,
	.table-dark thead th {
		color: #f6f6fb;
		border-bottom-color: #fff !important
	}

	table#rebreport a {
		color: #20ff05;
		font-weight: 700;
		text-decoration: underline
	}

	table,
	td,
	th {
		border: 1px solid #fff;
		padding: 3px
	}

	.card-header i {
		font-size: 15px;
		background: #1170e4;
		color: #fff;
		padding: 11px 16px;
		border-radius: 5px;
		margin: 0 10px
	}

	textarea {
		height: 100px;
		padding: 5px;
		box-sizing: border-box;
		border: 2px solid #ccc;
		border-radius: 4px;
		background-color: #f8f8f8;
		font-size: 16px;
		resize: none
	}

	.form {
		background-color: #eee;
		border-radius: 6px;
		padding: 15px;
		align-items: center
	}

	.add,
	.input,
	.tasks .task {
		padding: 10px
	}

	.input {
		border: 1px solid #ddd;
		border-radius: 6px;
		flex: 1
	}

	.add:focus,
	.input:focus {
		outline: 0
	}

	.add {
		border: none;
		background-color: #f44336;
		color: #fff;
		border-radius: 6px;
		margin-left: 10px;
		cursor: pointer
	}

	.tasks {
		background-color: #eee;
		margin-top: 20px;
		border-radius: 6px;
		padding: 20px;
		height: 195px;
		overflow-y: auto
	}

	.tasks .task {
		background-color: #fff;
		border-radius: 6px;
		display: flex;
		justify-content: space-between;
		align-items: center;
		transition: .3s;
		cursor: pointer;
		border: 1px solid #ccc
	}

	.delete-all,
	.tasks .task span {
		color: #fff;
		cursor: pointer;
		border-radius: 4px
	}

	.tasks .task:not(:last-child) {
		margin-bottom: 15px
	}

	.tasks .task:hover {
		background-color: #f7f7f7
	}

	.tasks .task.done {
		opacity: .5;
		position: relative
	}

	.task.done::after {
		position: absolute;
		content: ""
	}

	.tasks .task span {
		font-weight: 700;
		font-size: 10px;
		background-color: red;
		padding: 2px 6px
	}

	.delete-all {
		width: calc(100% - 25px);
		margin: 20px auto auto;
		padding: 12px;
		text-align: center;
		font-size: 14px;
		background-color: #f44336
	}
</style>

<script>
	window.onload = function() {
		var o = window.location.origin;
		document.getElementById("anchor").href = o
	}, window.console = window.console || function(o) {};
</script>

<div class="main-content app-content mt-0">
	<div class="side-app">
		<div class="main-container container-fluid">







			<div class="page-header">
				<h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a><?= ucwords($pageTitle); ?></h1>

				<ol class="breadcrumb">

					<li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

					<li class="breadcrumb-item active" aria-current="page"><?= ucwords($pageTitle); ?></li>

				</ol>
			</div>





			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
							<div class="card overflow-hidden">
								<div class="card-body">
									<div class="mt-2">
										<div class="row justify-content-end">
											<div class="col-lg-3 col-sm-6 mb-2 d-flex justify-content-between">
												<p class="mt-2">Notify to: </p>
												<button class="btn btn-info" onclick="notifyEmail('Email')">Email</button>

												<button class="btn btn-info" onclick="notifyEmail('Mobile')">Mobile</button>

											</div>

										</div>

										<div class="row">
											<div class="col-lg-6 col-sm-6 mb-2">
												<span>Select Draw</span>&nbsp;<span style="color: red;">*</span>
												<select id="draw_new_id" class="form-select">
													<option value="">Select Draw</option>
													<?php
													$draw = select_query($con, "draw", "", "(`deletes` = 0 AND `status` = 'Completed') OR (`deletes` = 0 AND `status` = 'Active' AND `id` IN ( SELECT `id` FROM ( SELECT `id` FROM `draw` WHERE `deletes` = 0 AND `status` = 'Active' ORDER BY `id` ASC LIMIT 2 ) t )) ORDER BY `id` DESC", "", "");
													// $draw = select_query($con, "draw", "", "`status` != 'Pending' and `deletes`='0' ORDER BY `id` DESC", "", "");
													if ($draw['nr'] > 0) {
														foreach ($draw['result'] as $key => $value) {
													?>
															<option value="<?= $value['id']; ?>">
																<?= $value['name']; ?>
															</option>
													<?php
														}
													}
													?>
												</select>
											</div>

											<div class="col-lg-6 col-sm-6 mb-2 mt-5">
												<button class="btn btn-info" onclick="checkBlockNumber($('#draw_new_id').val())">Go</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="row row-sm" id="blockContnent">
				<div class="col-lg-12">
					<div class="card">
						<div class="row card-header ">
							<div class=" d-flex justify-content-between my-2">
								<h3 class="card-title"><?= ucwords($pageTitle); ?></h3>
							</div>
							<div class="row align-items-center" id="DrawBlockedList"></div>
						</div>
						<div class="card-body table-responsive" id="GetTable"></div>
					</div>
				</div>
			</div>




			<div class="row row-sm">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-header">
							<div class="row align-items-center">
								<div class="col-lg-3  col-sm-6 mb-2">
									<h3 class="card-title"><?= ucwords('Block My3Numbers History'); ?></h3>
								</div>
								<div class="col-sm-2 mb-2"></div>
								<div class="col-lg-3  col-sm-6 mb-2">

									<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"><i class="fa fa-calendar"></i>&nbsp;<span></span> <i class="fa fa-caret-down"></i>
									</div>
								</div>
								<div class="col-sm-4 mb-2">
									<button class="btn btn-info" onclick="viewtable()">Go</button>
								</div>
							</div>
						</div>

						<div class=" card-body">
							<div class="table-responsive">

								<table class="table table-bordered display" id="drawtable" style="width:100%;">

									<thead>

										<tr>
											<th class="wd-15p border-bottom-0">BlockList ID</th>
											<th class="wd-15p border-bottom-0">Draw Name</th>
											<th class="wd-15p border-bottom-0">Blocked By</th>
											<th class="wd-15p border-bottom-0">Site Name</th>
											<th class="wd-15p border-bottom-0">Straight</th>
											<th class="wd-15p border-bottom-0">Reverse</th>
											<th class="wd-15p border-bottom-0">Mixed 1</th>
											<th class="wd-15p border-bottom-0">Mixed 2</th>
											<th class="wd-25p border-bottom-0">Mixed 3</th>
											<th class="wd-25p border-bottom-0">Mixed 4</th>
											<th class="wd-25p border-bottom-0">Started At</th>
											<th class="wd-25p border-bottom-0">Ended At</th>
											<th class="wd-25p border-bottom-0"></th>
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


			<div class="row row-sm">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-header">
							<div class="row align-items-center">
								<div class="col-lg-3  col-sm-6 mb-2">
									<h3 class="card-title"><?= ucwords('Customer List'); ?></h3>
								</div>
								<div class="col-2">
								</div>
								<div class="col-lg-3  col-sm-7 mb-2 ">

									<div id="reportrange213" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
										<i class="fa fa-calendar"></i>&nbsp;
										<span></span> <i class="fa fa-caret-down"></i>
									</div>
								</div>
								<div class="col-sm-4 mb-2">
									<button class="btn btn-info" onclick="customerList()">Go</button>
								</div>
							</div>
						</div>

						<div class=" card-body">
							<div class="table-responsive">
								<table class="table table-bordered display" id="customerList" style="width:100%;">

									<thead>

										<tr>
											<th class="wd-15p border-bottom-0">ID</th>
											<th class="wd-15p border-bottom-0">Draw Name</th>
											<th class="wd-15p border-bottom-0">Customer Name</th>
											<th class="wd-15p border-bottom-0">Mobile No</th>
											<th class="wd-15p border-bottom-0">My3Number</th>
											<th class="wd-15p border-bottom-0">Product Amount (AED)</th>
											<th class="wd-15p border-bottom-0">No Of Lines</th>
											<th class="wd-15p border-bottom-0">Ticket Amount</th>
											<th class="wd-15p border-bottom-0">Agent Name</th>
											<th class="wd-15p border-bottom-0">No of Hits</th>
											<th class="wd-25p border-bottom-0">Tried At</th>
											<th class="wd-25p border-bottom-0">Action</th>
											<th class="wd-25p border-bottom-0"></th>
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

<div class="modal fade" id="winningno">

	<div class="modal-dialog modal-sm" role="document">

		<div class="modal-content modal-content-demo">

			<div class="modal-header">

				<h6 class="modal-title">Winning Number</h6>

				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">×</span>

				</button>

			</div>

			<div class="modal-body">

				<div class="container">

					<form id="winnerdata">

						<input type="hidden" name="drawid" id="drawid" value="<?= $draw['result'][0]['id']; ?>">

						<div class="form-group">

							<div class="model-text">

								<h3 class="text-center fs-20">Enter Winning Number

								</h3> <br>

							</div>

							<div id="errmessage"></div>

							<div class="row ">
								<div class="col-12">
									<input type="text" maxlength="3" id="drawlucky" name="drawlucky" class="form-control bg-info-gradient" value="" name style="font-size: 120px;font-weight: 900;margin:auto; text-align: center;padding-bottom: 38px;height: 150px;color: white;text-shadow: 0px 1px 1px #919191, 1px 2px 1px #919191, 1px 3px 1px #919191, 1px 4px 1px #919191, 1px 5px 1px #919191, 1px 6px 1px #919191, 1px 7px 1px #919191, 1px 8px 1px #919191, 1px 9px 1px #919191, 1px 10px 1px #919191, 1px 18px 6px rgb(16 16 16 / 40%), 1px 22px 10px rgb(16 16 16 / 20%), 1px 25px 35px rgb(16 16 16 / 20%), 1px 30px 60px rgb(255 255 255 / 40%);">
								</div>
							</div>



						</div>



					</form>

					<br>

				</div>

			</div>

			<div class="modal-footer" id="winnerBtn">

				<button class="btn btn-primary py-2" onclick="winner()" type="button">Submit</button>

			</div>

		</div>

	</div>

</div>


<div class="modal fade" id="allowcustomermodal">

	<div class="modal-dialog modal-sm" role="document">

		<div class="modal-content modal-content-demo">

			<div class="modal-header">

				<h6 class="modal-title">Customer Preference</h6>

				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">×</span>

				</button>

			</div>

			<div class="modal-body">

				<div class="container">

					<form id="winnerdata">



						<div class="form-group" id="customerpreference">








						</div>



					</form>

					<br>

				</div>

			</div>

			<div class="modal-footer" id="confrimbttn">



			</div>

		</div>

	</div>

</div>



<div class="modal fade" id="notifymodel">

	<div class="modal-dialog modal-md" role="document">

		<div class="modal-content modal-content-demo">

			<div class="modal-header">

				<h6 class="modal-title" id="modTitle"></h6>

				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">×</span>

				</button>

			</div>

			<div class="modal-body">

				<div class="container p-0">
					<div class="form" id="EmailBox">
						<input type="email" class="form-control" id="n_email" name="email" oninput="email_validation($(this).val())" onfocusout="email_validation($(this).val())" placeholder="Email id" maxlength="70">
						<p style="font-weight:bold; font-size:12px;" id="errorinfo"></p>
						<input type="submit" class="add" id="nbtnbut" value="Add Email" onclick="updateNotifiy($('#n_email').val())">
					</div>
					<div class="form" id="MobileBox">
						<input type="tel" class="form-control" style="width:100%;" id="mobile_code" oninput="mobile_number_validation($(this).val())">
						<p style="font-weight:bold; font-size:12px;" id="merrorinfo"></p>
						<input type="submit" class="add" id="nbtnbut" value="Add Mobile" onclick="updateNotifiy(getphoneNumber())">
					</div>

					<div class="tasks" id="tasks">

					</div>
				</div>

			</div>



		</div>

	</div>

</div>

<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

<script>
	var url = window.location.origin + "/ajax/service/services.php",
		input = document.querySelector("#mobile_code"),
		usersid = '<?= $subid3; ?>';

	$(document).ready(function() {
		$('#blockContnent').hide();

		createDatePricket('reportrange');
		createDatePricket('reportrange213');
		viewtable();
		customerList();
	});



	var iti = window.intlTelInput(input, {
		separateDialCode: !0,
		preferredCountries: ["ae", "in", "sa", "qa", "om", "bh", "kw", "ma"],
		excludeCountries: ["AF", "CU", "KP", "IR", "LR", "LY", "MM", "SO", "SD", "SY", "UA"],
		initialCountry: "auto",
		geoIpLookup: function(e, t) {
			$.get("https://iplist.cc/api", function(t) {
				e("" != t.countrycode ? t.countrycode : "us")
			})
		},
		customPlaceholder: function() {
			return ""
		},
		utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js"
	});
	// Pick the Three Number Combination Start


	function getphoneNumber() {
		return iti.getNumber().replace("+", "").trim()
	}

	function getTabel(id) {
		var formdata = [];
		formdata.push({
			name: 'method',
			value: "getTabel"
		});
		formdata.push({
			name: 'drawID',
			value: id
		});

		var post_data = formdata;
		var onsuccess = function(data) {
			var response = JSON.parse(data);
			if (response != "") {
				if (response.type == 1) {
					$('#exportbtn').show();
					document.getElementById('GetTable').innerHTML = response.result;
				} else {
					toast('error', response.result);
				}
			}
		}
		do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/winners_email.php");

	}

	// Pick the Three Number Combination End
	function toast(icon, message) {

		const Toast = Swal.mixin({

			toast: true,

			position: 'top-end',

			showConfirmButton: false,

			timer: 5000,

			timerProgressBar: true,

			didOpen: (toast) => {

				toast.addEventListener('mouseenter', Swal.stopTimer);

				toast.addEventListener('mouseleave', Swal.resumeTimer);

			}

		});



		Toast.fire({

			icon: icon,

			title: message

		});

	}

	function viewtable() {


		var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
		var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");

		var table = $('#drawtable').DataTable();
		table.destroy();

		var title = 'Blocked My3Number History (' + formdate + ' to ' + todate + ')';
		table = $("#drawtable").DataTable({
			pageLength: 10,
			order: [
				[0, 'desc']
			],
			responsive: {
				details: {
					type: 'column',
					target: -1,
				}
			},
			columnDefs: [{
				targets: -1,
				orderable: false,
				searchable: false,
				className: 'control',
			}, {
				targets: 0,
				orderable: false,
				searchable: false,
				className: 'selectall-checkbox',
			}],
			select: {
				style: 'multi',
				selector: 'td:first-child',
			},
			paging: true,
			searching: true,
			info: true,
			ajax: {
				url: window.location.origin + "/ajax/service/winners_email.php",
				method: "POST",
				dataSrc: "",
				data: {
					method: 'blocklisthistory',
					formdate: formdate,
					todate: todate
				}
			},
			dom: 'Bfrtip',
			buttons: [
				'pageLength',
				'copy',
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
					orientation: 'portrait',
					pageSize: 'A4',
					title: title

				}, 'print',
			],
			columns: [{
					data: 'id'
				},
				{

					data: 'drawname'

				},
				{

					data: 'createdby'

				},
				{

					data: 'sitename'

				},
				{

					data: 'first'

				},
				{

					data: 'second'

				},
				{

					data: 'third_one'

				},
				{

					data: 'third_two'

				},
				{

					data: 'third_three'

				},

				{

					data: 'third_four'

				},

				{

					data: 'createdon'

				},

				{

					data: 'updatedon'

				}, {
					data: null,
					render: function(data, type, row, meta) {
						return ''
					}
				}

			],




		});
	}

	function ExportToExcel(type, fn, dl) {
		var elt = document.getElementById('rebreport');
		var wb = XLSX.utils.table_to_book(elt, {
			sheet: "sheet1"
		});
		return dl ?
			XLSX.write(wb, {
				bookType: type,
				bookSST: true,
				type: 'base64'
			}) :
			XLSX.writeFile(wb, fn || ('RBDreport.' + (type || 'xlsx')));
	}

	function dedPermitnum(permit, drawid) {

		if (permit.length > 1) {
			var formdata = $('#winnerdata').serializeArray();
			formdata.push({
				name: 'method',
				value: "dedUpdate"
			});
			formdata.push({
				name: 'drawid',
				value: drawid
			});
			formdata.push({
				name: 'permit',
				value: permit
			});

			var post_data = formdata;

			var onsuccess = function(data) {
				var response = JSON.parse(data);
				if (response != "") {
					if (response.type == 1) {
						toast('success', response.result);
					} else {
						toast('error', response.result);
					}
				}
			}
			do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/winners_email.php");
		} else {
			toast('error', 'Kindly enter the DED Permit Number');
		}

	}

	function checkBlockNumber(drawID) {

		if (drawID == '') {
			toast('error', 'Kindly Select the Draw')
			return false;
		}

		var formdata = [];
		formdata.push({
			name: 'method',
			value: "checkBlockNumber"
		});
		formdata.push({
			name: 'drawID',
			value: drawID
		});

		var post_data = formdata;
		var onsuccess = function(data) {
			var response = JSON.parse(data);
			if (response != "") {
				if (response.type == 1) {
					document.getElementById('DrawBlockedList').innerHTML = response.result;
					$('#blockContnent').show();

				} else {
					toast('error', response.result);
				}
			}
		}
		do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/winners_email.php");

	}


	function updateBlockList(drawID) {

		if (drawID == '') {
			toast('error', 'Kindly Refresh The Page and Try Again!');
			return false;
		}

		let first = $('#first').val();
		if (first != '') {
			if (first.length != 3) {
				toast('error', 'Kindly Enter Three Digit!');
				return false;
			}
		}

		let second = $('#second').val();
		if (second != '') {
			if (second.length != 3) {
				toast('error', 'Kindly Enter Three Digit!');
				return false;
			}
		}

		let third_one = $('#third_one').val();
		if (third_one != '') {
			if (third_one.length != 3) {
				toast('error', 'Kindly Enter Three Digit!');
				return false;
			}
		}

		let third_two = $('#third_two').val();
		if (third_two != '') {
			if (third_two.length != 3) {
				toast('error', 'Kindly Enter Three Digit!');
				return false;
			}
		}

		let third_three = $('#third_three').val();
		if (third_three != '') {
			if (third_three.length != 3) {
				toast('error', 'Kindly Enter Three Digit!');
				return false;
			}
		}

		let third_four = $('#third_four').val();
		if (third_four != '') {
			if (third_four.length != 3) {
				toast('error', 'Kindly Enter Three Digit!');
				return false;
			}
		}


		let sitename = $('#sitename').val();
		if (sitename == '') {
			toast('error', 'Kindly Select Site Name!');
			return false;
		}


		var formdata = [];
		formdata.push({
			name: 'method',
			value: "updateBlockList"
		});
		formdata.push({
			name: 'drawID',
			value: drawID
		});

		formdata.push({
			name: 'first',
			value: first
		});
		formdata.push({
			name: 'second',
			value: second
		});
		formdata.push({
			name: 'third_one',
			value: third_one
		});
		formdata.push({
			name: 'third_two',
			value: third_two
		});
		formdata.push({
			name: 'third_three',
			value: third_three
		});
		formdata.push({
			name: 'third_four',
			value: third_four
		});
		formdata.push({
			name: 'sitename',
			value: sitename
		});

		var post_data = formdata;
		var onsuccess = function(data) {
			var response = JSON.parse(data);
			if (response != "") {
				if (response.type == 1) {
					toast('success', response.result);
					$('#blockContnent').hide();
					viewtable();
				} else {
					toast('error', response.result);
				}
			}
		}

		do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/winners_email.php");
	}

	function getAllNumber(straight) {
		if (straight != '' && straight.length == 3) {
			var arr = [straight];
			let first = straight.substring(0, 1);
			let second = straight.substring(1, 2);
			let third = straight.substring(2, 3);

			let reverse = third + second + first;
			if (!arr.includes(reverse)) {
				arr.push(reverse);
				document.getElementById('second').value = reverse;
			} else {
				document.getElementById('second').value = '';
			}

            //let mix3_1 = third + first + second;

            //Start changes done by prashant on 03-05-23
			let mix3_1 = '';
			if($('#second').val() == ''){
			    mix3_1 = second + third + first;
			} else {
			    mix3_1 = third + first + second;
			}
            //End changes done by prashant on 03-05-23
            
			if (!arr.includes(mix3_1)) {
				arr.push(mix3_1);
				document.getElementById('third_one').value = mix3_1;
			} else {
				document.getElementById('third_one').value = '';
			}

			let mix3_2 = second + third + first;
			if (!arr.includes(mix3_2)) {
				arr.push(mix3_2);
				document.getElementById('third_two').value = mix3_2;
			} else {
				document.getElementById('third_two').value = '';
			}

			let mix3_3 = second + first + third;
			if (!arr.includes(mix3_3)) {
				arr.push(mix3_3);
				document.getElementById('third_three').value = mix3_3;
			} else {
				document.getElementById('third_three').value = '';
			}

			let mix3_4 = first + third + second;
			if (!arr.includes(mix3_4)) {
				arr.push(mix3_4);
				document.getElementById('third_four').value = mix3_4;
			} else {
				document.getElementById('third_four').value = '';
			}

		}
	}


	function customerList() {
		var formdate = moment($('#reportrange213').data('daterangepicker').startDate._d).format("MMM Do YY");
		var todate = moment($('#reportrange213').data('daterangepicker').endDate._d).format("MMM Do YY");

		var table = $('#customerList').DataTable();
		table.destroy();
		var title = 'Customer List (' + formdate + ' to ' + todate + ')';
		table = $("#customerList").DataTable({
			pageLength: 10,
			order: [
				[0, 'desc']
			],
			responsive: {
				details: {
					type: 'column',
					target: -1,
				}
			},
			columnDefs: [{
				targets: -1,
				orderable: false,
				searchable: false,
				className: 'control',
			}, {
				targets: 0,
				orderable: false,
				searchable: false,
				className: 'selectall-checkbox',
			}],
			select: {
				style: 'multi',
				selector: 'td:first-child',
			},
			paging: true,
			searching: true,
			info: true,
			ajax: {
				url: window.location.origin + "/ajax/service/winners_email.php",
				method: "POST",
				dataSrc: "",
				data: {
					method: 'customerList',
					formdate: formdate,
					todate: todate,
					userid: usersid
				}
			},
			dom: 'Bfrtip',
			buttons: [
				'pageLength',
				'copy',
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
					orientation: 'portrait',
					pageSize: 'A4',
					title: title

				}, 'print',
			],
			columns: [{
					data: 'id'
				}, {
					data: 'drawname'
				},
				{

					data: 'cusname'

				},
				{

					data: 'mobileno'

				},

				{

					data: 'my3number'

				},

				{

					data: 'proamt'

				},
				{

					data: 'nolines'

				},
				{

					data: 'ticketamot'

				},
				{

					data: 'agentname'

				},
				{

					data: 'nohit'

				},
				{

					data: 'tried'

				},
				{

					data: 'action'

				},
				{
					data: null,
					render: function(data, type, row, meta) {
						return ''
					}
				}
			]
		});
		usersid = '';
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


	function allow_to_purchase(block_list_id) {
		if (block_list_id == '') {
			toast('error', 'Block List ID Missing!')
			return false;
		}

		var formdata = [];
		formdata.push({
			name: 'method',
			value: "check_allow_to_purchase"
		});
		formdata.push({
			name: 'block_list_id',
			value: block_list_id
		});

		var post_data = formdata;
		var onsuccess = function(data) {
			var response = JSON.parse(data);
			if (response != "") {
				if (response.type == 1) {
					document.getElementById('customerpreference').innerHTML = response.customerpreference;
					document.getElementById('confrimbttn').innerHTML = response.confrimbttn;
					$('#allowcustomermodal').modal('show');
				} else {
					toast('error', response.result);
				}
			}
		}
		do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/winners_email.php");
	}

	function reconfirm(block_list_id) {

		if (block_list_id != '') {

			swal.fire({

				// title: '<strong style="color:red">Duplicate Ticket Alert!</strong>',

				text: 'Already This Customer Allowed. Are you sure you want to allow again?',

				confirmButtonText: 'Yes',

				cancelButtonText: 'No',

				showCancelButton: true,

				allowOutsideClick: false,

				customClass: {

					confirmButton: 'btn btn-success',

					cancelButton: 'btn btn-danger'

				}

			}).then(function(result) {
				if (result.isConfirmed) {
					var reconfirmS = result.isConfirmed;
					confrim_to_Purchase(block_list_id, 1);
				} else {
					toast('error', 'Cancelled Successfully');
					setTimeout(reFresh, 5000);
				}
			});
		} else {
			reFresh();
		}
	}


	function revoke_the_Permission(block_list_id) {
		if (block_list_id != '') {

			swal.fire({

				// title: '<strong style="color:red">Duplicate Ticket Alert!</strong>',

				text: 'Are you sure you want Revoke?',
				confirmButtonText: 'Yes',
				cancelButtonText: 'No',
				showCancelButton: true,
				allowOutsideClick: false,
				customClass: {
					confirmButton: 'btn btn-success',
					cancelButton: 'btn btn-danger'
				}

			}).then(function(result) {
				if (result.isConfirmed) {
					var reconfirmS = result.isConfirmed;

					var formdata = [];
					formdata.push({
						name: 'method',
						value: "revoke_permission"
					});

					formdata.push({
						name: 'block_list_id',
						value: block_list_id
					});

					var post_data = formdata;
					var onsuccess = function(data) {
						var response = JSON.parse(data);
						if (response != "") {
							if (response.type == 1) {
								toast('success', response.result);
								customerList();
							} else {
								toast('error', response.result);
							}
						}
					}

					do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/winners_email.php");
				} else {
					toast('error', 'Cancelled Successfully');
					setTimeout(reFresh, 5000);
				}
			});
		} else {
			reFresh();
		}
	}

	function confrim_to_Purchase(block_list_id, reVerfy) {
		let customermy3 = $('#customermy3').val();
		let product = $('#product').val();
		if (block_list_id == '') {
			toast('error', 'Block List ID Missing!')
			return false;
		}

		if (customermy3 == '') {
			toast('error', 'Kindly Enter Three Digit Number!');
			return false;
		}

		if (customermy3 != '') {
			if (customermy3.length != 3) {
				toast('error', 'Kindly Enter Three Digit!');
				return false;
			}
		}

		if (product == '') {
			toast('error', 'Kindly Select Product Amount!')
			return false;
		}

		var formdata = [];
		formdata.push({
			name: 'method',
			value: "confrim_to_Purchase"
		});

		formdata.push({
			name: 'block_list_id',
			value: block_list_id
		});

		formdata.push({
			name: 'customermy3',
			value: customermy3
		});

		formdata.push({
			name: 'product',
			value: product
		});

		formdata.push({
			name: 'reVerfy',
			value: reVerfy
		});

		var post_data = formdata;
		var onsuccess = function(data) {
			var response = JSON.parse(data);
			if (response != "") {
				if (response.type == 1) {
					$('#allowcustomermodal').modal('hide');
					toast('success', response.result);
					customerList();

				} else if (response.type == 2) {
					$('#allowcustomermodal').modal('hide');
					reconfirm(response.block_list_id);
				} else {
					toast('error', response.result);
				}
			}
		}
		do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/winners_email.php");
	}

	function reFresh() {
		location.reload();
	}

	function notifyEmail(type) {
		if (type == '') {
			toast('error', 'Type Not Found kindly Refresh and try Again.');
			return false;
		}

		var formdata = [];
		formdata.push({
			name: 'method',
			value: "get_notify"
		});

		if (type == 'Email') {
			$('#MobileBox').hide();
			$('#EmailBox').show();

			$('#modTitle').text('Add Email');
			$('#nbtnbut').val('Add Email');

			formdata.push({
				name: 'type',
				value: type
			});
		}
		if (type == 'Mobile') {
			$('#EmailBox').hide();
			$('#MobileBox').show();

			$('#modTitle').text('Add Mobile');
			$('#nbtnbut').val('Add Mobile');

			formdata.push({
				name: 'type',
				value: type
			});
		}

		window.localStorage.setItem("type", type);
		var post_data = formdata;
		var onsuccess = function(data) {
			var response = JSON.parse(data);
			if (response != "") {
				if (response.type == 1) {
					$('#tasks').html('');
					var emailArr = response.result;
					for (const key of emailArr) {
						addEmail(key);
					}
					$('#notifymodel').modal('show');
				} else {
					toast('error', response.result);
				}
			}
		}
		do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/winners_email.php");
	}

	function validateEmail(e) {
		return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(e)
	}

	function updateNotifiy(arr) {


		var formdata = [];
		formdata.push({
			name: 'method',
			value: "update_notify"
		});
		formdata.push({
			name: 'type',
			value: window.localStorage.getItem('type')
		});

		$('#errorinfo').html('');
		$('#merrorinfo').html('');
		if (window.localStorage.getItem('type') == 'Email') {
			if (!validateEmail($("#n_email").val())) return errorThrow("errorinfo", "Invaild Email ID", "n_email"), !1;
			if (!email_validation($("#n_email").val())) return !1;
		}
		if (window.localStorage.getItem('type') == 'Mobile') {
			if ("" == arr) return errorThrow("merrorinfo", "Field Required - Enter Mobile", "mobile_code"), !1;
			if (0 == $("#mobile_code").val().charAt(0)) return errorThrow("merrorinfo", 'Remove "Zero" at beginning',
				"mobile_code"), !1;
			var u = country_Mobile_count(parseInt(iti.getSelectedCountryData().dialCode));
			let c = $("#mobile_code").val();
			if ("" != u && parseInt(c.length) < u) return errorThrow("merrorinfo", "Number is Wrong or Your Number not " + iti
				.getSelectedCountryData().name + " number", "mobile_code"), !1;
			if (!iti.isValidNumber()) return errorThrow("merrorinfo", "Incorrect Mobile Number", "mobile_code"), !1;
		}

		formdata.push({
			name: 'notify',
			value: arr
		});

		var post_data = formdata;
		var onsuccess = function(data) {
			var response = JSON.parse(data);
			if (response != "") {
				if (response.type == 1) {
					$('#tasks').html('');
					if (response.result != '') {

						var emailArr = response.result;
						for (const key of emailArr) {
							addEmail(key);
						}
						if (window.localStorage.getItem('type') == 'Email') {
							$('#n_email').val('');
						}
						if (window.localStorage.getItem('type') == 'Mobile') {
							$('#mobile_code').val('');
							$("#mobile_code").removeAttr("maxlength");
						}
					}
				} else {
					toast('error', response.result);
				}
			}
		}
		do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/winners_email.php");
	}


	function addEmail(item) {
		$('#tasks').append(`<div class="task">${item}<span class="del" onclick="deletearray('${item}')">Delete</span></div>`);
	}


	function deletearray(item) {
		var formdata = [];
		formdata.push({
			name: 'method',
			value: "delete_notify"
		});
		formdata.push({
			name: 'type',
			value: window.localStorage.getItem('type')
		});

		formdata.push({
			name: 'item',
			value: item
		});

		var post_data = formdata;
		var onsuccess = function(data) {
			var response = JSON.parse(data);
			if (response != "") {
				if (response.type == 1) {
					$('#tasks').html('');
					if (response.result != '') {

						var emailArr = response.result;
						for (const key of emailArr) {
							addEmail(key);
						}
					}
				} else {
					toast('error', response.result);
				}
			}
		}
		do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/winners_email.php");
	}

	function errorThrow(e, t, r) {
		color = "#eb1404", $("#" + e).html(t), $("#" + e).css("color", color), $("#" + r).focus()
	}

	function country_Mobile_count(e) {
		var t = "";
		return 971 == e || 61 == e || 966 == e || 33 == e || 61 == e || 31 == e ? 9 : 91 == e || 63 == e || 1 == e || 44 ==
			e || 49 == e || 81 == e || 60 == e ? 10 : 973 == e || 65 == e || 852 == e || 965 == e || 974 == e || 968 == e ||
			45 == e ? 8 : ""
	}

	input.addEventListener("countrychange", function() {
		document.getElementById("mobile_code").value = "", $("#mobile_code").removeAttr("maxlength")
	});

	function mobile_number_validation(e) {
		var t = /[^0-9]/g;
		if ($("#merrorinfo").html(""), "" != e) {
			if (0 != parseInt(e.charAt(0))) {
				if (t.test(e)) e.charAt(e.length - 1), document.getElementById("mobile_code").value = e.replace(t, ""),
					errorThrow("merrorinfo", "The Characters are Not Allowed ", "mobile_code");
				else {
					var r = country_Mobile_count(parseInt(iti.getSelectedCountryData().dialCode));
					"" != r ? e.length < r || (parseInt(e.length), $("#mobile_code").attr("maxlength", r), $("#merrorinfo")
						.html("")) : $("#mobile_code").removeAttr("maxlength")
				}
			} else document.getElementById("mobile_code").value = "", errorThrow("merrorinfo",
				"Mobile Number Should Not Start With Zero", "mobile_code")
		} else errorThrow("merrorinfo", "Field Required - Enter Mobile", "mobile_code")
	}

	function email_validation(e) {
		let t = e.charAt(0);
		if (/[^A-Za-z0-9]/g.test(t)) return errorThrow("errorinfo", "The Email Could not Start with Special Characters " +
			t, "n_email"), !1;
		$("#errorinfo").html("");
		var r = /[^A-Za-z0-9@._]/g;
		if ("" != e) {
			if (!r.test(e)) return document.getElementById("n_email").value = e.replace(/  +/g, " ").trim(), $("#errorinfo")
				.html(""), !0;
			var o = e.charAt(e.length - 1);
			return document.getElementById("n_email").value = e.replace(r, ""), errorThrow("errorinfo",
				"The Special Characters are Not Allowed " + o, "n_email"), !1
		}
	}
</script>