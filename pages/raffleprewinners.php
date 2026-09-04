<?php

/**

 * 		Date        	Developer     Changes

 * 		22-06-2023		Prashant	  Just3 Raffle Draw Winners 		

 * */



$pageTitle = ' Preliminary Winner Check';

$now = date("Y-m-d h:i:s");

$draw = select_query($con, "draw", "", "`raffle_status` = 'Active' AND `result_datetime` > '$now' AND `deletes` = '0' ORDER BY `result_datetime` ASC LIMIT 1", "", "");

$resultDate = date_format(date_create($draw['result'][0]['result_datetime']), "Y-m-d h:i:s a");



?>



<style>
	input.rafflePrize {

		text-transform: uppercase;

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



	.table-dark th,

	.table-dark td,

	.table-dark thead th {

		color: #f6f6fb;

		border-bottom-color: #fff !important;

	}



	table#rebreport a {

		color: #20ff05;

		font-weight: 700;

		text-decoration: underline;

	}



	table,

	th,

	td {



		border: 1px solid #fff;

		padding: 3px;

	}



	table {

		width: 100%;

	}





	.card-header i {

		font-size: 15px;

		background: #1170e4;

		color: #fff;

		padding: 11px 16px;

		border-radius: 5px;

		margin: 0 10px;

	}

	.badge-danger {

		color: #dc3545;

		font-size: 21px;

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







				<h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a><?= ucwords($pageTitle); ?></h1>







				<div>







					<ol class="breadcrumb">







						<li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>







						<li class="breadcrumb-item active" aria-current="page"><?= ucwords($pageTitle); ?></li>







					</ol>







				</div>







			</div>







			<div class="row">











				<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">



					<div class="card bg-secondary-gradient img-card box-primary-shadow">



						<div class="card-body">



							<div class="d-flex">



								<div class="text-white">



									<?php if ($draw['nr'] > 0) { ?>



										<h2 class="mb-0 number-font"><?= $draw['result'][0]['name']; ?></h2>

										<h4 class="text-white mb-0"><?= $resultDate; ?></h4>



									<?php } else { ?>

										<h3>Active draws not found!</h3>

									<?php } ?>



								</div>



								<div class="ms-auto"> <i class="fa fa-money text-white fs-30 me-2 mt-2"></i> </div>



							</div>



						</div>



					</div>



				</div>





				<?php if ($draw['result'][0]['id'] != '') { ?>

					<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center mb-2">

						<button class="btn btn-success bg-success-gradient my-1 p-2" data-bs-toggle="modal" data-bs-target="#winningno">Check Winners</button>

					</div>



				<?php } ?>



			</div>



			<div class="row row-sm">



				<div class="col-lg-12">



					<div class="card">



						<div class="row card-header ">

							<div class=" d-flex justify-content-between my-2">





								<h3 class="card-title">Winners Details Report</h3>



								<button class="btn btn-info " onclick="ExportToExcel('xlsx')" id="exportbtn">Export</button>



							</div>









							<div class="row justify-content-center">

								<div class="col-lg-8 col-md-12 ">

									<label for="dedpermitno">DED Raffle Permit Number</label>

								</div>

								<div class="col-lg-8 col-md-12 d-flex">



									<input type="text" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="form-control" id="dedpermitno" name="dedpermitno" value="<?= $draw['result'][0]['permitno']; ?>" placeholder="DED Raffle Permit Number"><i class="fa fa-paper-plane f-14" style="cursor: pointer;" aria-hidden="true" onclick="dedPermitnum($('#dedpermitno').val(), <?= $draw['result'][0]['id']; ?>)"></i>

								</div>

							</div>









						</div>



						<div class="card-body table-responsive" id="GetTable">





						</div>



					</div>



				</div>



			</div>









			<div class="row row-sm">



				<div class="col-lg-12">



					<div class="card">



						<div class="card-header d-lg-flex d-block justify-content-between">



							<h3 class="col-md-6 mb-2 card-title"><?= ucwords($pageTitle); ?></h3>

							<div class="mb-2 text-center" id="ldbtnnew"></div>

							<div class="mb-2 text-center" id="ldfullre"></div>

							<div class="mb-2 text-center" id="repcpdfbtn"></div>

						</div>



						<div class=" card-body">



							<table class="table table-bordered display" id="drawtable" style="width:100%;">



								<thead>



									<tr>

										<th class="wd-15p border-bottom-0">Ticket ID</th>

										<th class="wd-15p border-bottom-0">Name</th>

										<th class="wd-15p border-bottom-0">Mobile</th>

										<th class="wd-15p border-bottom-0">Email</th>

										<th class="wd-15p border-bottom-0">My3Numbers</th>

										<th class="wd-15p border-bottom-0">Raffle ID</th>

										<th class="wd-25p border-bottom-0">Product</th>

										<th class="wd-25p border-bottom-0">Prize</th>

										<th class="wd-25p border-bottom-0">Amount</th>

										<th class="wd-25p border-bottom-0">Purchase Date & Timing</th>

										<th class="wd-25p border-bottom-0"></th>

									</tr>



								</thead>



								<tbody>







								</tbody>



								<tfoot>

									<tr>

										<td></td>

										<td></td>

										<td></td>

										<td></td>

										<td></td>

										<td></td>

										<td></td>

										<td></td>

										<td></td>

										<td></td>

										<td></td>

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



<div class="modal fade" id="winningno">



	<div class="modal-dialog modal-xl" role="document">



		<div class="modal-content modal-content-demo">

			<div class="modal-header">

				<h6 class="modal-title">Winning Raffle Number</h6>

				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">×</span>

				</button>

			</div>



			<div class="modal-body">

				<div class="container">

					<form id="winnerdata">

						<input type="hidden" name="drawid" id="drawid" value="<?= $draw[result][0][draw_no]; ?>">



						<div class="row">

							<div class="col-md-4">

								<div class="text-center">

									<h3 class="">Enter 1st Prize RaffleID</h3>

								</div>

								<input type="text" oninput="checkwinner()" id="rafflePrizeOne" name="rafflePrizeOne" class="form-control bg-info-gradient rafflePrize" value="" name style="font-size: 48px;font-weight: 900;width: 100%; margin:auto; text-align: center;padding-bottom: 38px;color: white;text-shadow: 0px 1px 1px #919191, 1px 2px 1px #919191, 1px 3px 1px #919191, 1px 4px 1px #919191, 1px 5px 1px #919191, 1px 6px 1px #919191, 1px 7px 1px #919191, 1px 8px 1px #919191, 1px 9px 1px #919191, 1px 10px 1px #919191, 1px 18px 6px rgb(16 16 16 / 40%), 1px 22px 10px rgb(16 16 16 / 20%), 1px 25px 35px rgb(16 16 16 / 20%), 1px 30px 60px rgb(255 255 255 / 40%);">

								<span class="badge badge-danger error"></span>

							</div>

							<div class="col-md-4">

								<div class="text-center">

									<h3 class="">Enter 2nd Prize RaffleID</h3>

								</div>

								<input type="text" oninput="checkwinner()" id="rafflePrizeTwo" name="rafflePrizeTwo" class="form-control bg-info-gradient rafflePrize" value="" name style="font-size: 48px;font-weight: 900;width: 100%; margin:auto; text-align: center;padding-bottom: 38px;color: white;text-shadow: 0px 1px 1px #919191, 1px 2px 1px #919191, 1px 3px 1px #919191, 1px 4px 1px #919191, 1px 5px 1px #919191, 1px 6px 1px #919191, 1px 7px 1px #919191, 1px 8px 1px #919191, 1px 9px 1px #919191, 1px 10px 1px #919191, 1px 18px 6px rgb(16 16 16 / 40%), 1px 22px 10px rgb(16 16 16 / 20%), 1px 25px 35px rgb(16 16 16 / 20%), 1px 30px 60px rgb(255 255 255 / 40%);">

								<span class="badge badge-danger error"></span>

							</div>

							<div class="col-md-4">

								<div class="text-center">

									<h3 class="">Enter 3rd Prize RaffleID</h3>

								</div>

								<input type="text" oninput="checkwinner()" id="rafflePrizeThree" name="rafflePrizeThree" class="form-control bg-info-gradient rafflePrize" value="" name style="font-size: 48px;font-weight: 900;width: 100%; margin:auto; text-align: center;padding-bottom: 38px;color: white;text-shadow: 0px 1px 1px #919191, 1px 2px 1px #919191, 1px 3px 1px #919191, 1px 4px 1px #919191, 1px 5px 1px #919191, 1px 6px 1px #919191, 1px 7px 1px #919191, 1px 8px 1px #919191, 1px 9px 1px #919191, 1px 10px 1px #919191, 1px 18px 6px rgb(16 16 16 / 40%), 1px 22px 10px rgb(16 16 16 / 20%), 1px 25px 35px rgb(16 16 16 / 20%), 1px 30px 60px rgb(255 255 255 / 40%);">

								<span class="badge badge-danger error"></span>

							</div>

						</div>

					</form>

				</div>

			</div>







			<div class="modal-footer" id="winnerBtn">

				<div class="col-sm-12 text-center">

					<button class="btn btn-primary showLoading" onclick="winner()" type="button">Submit</button>

				</div>

			</div>



		</div>

	</div>

</div>



<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>



<script>
	var url = window.location.origin + "/ajax/service/raffle_services.php";



	(function() {

		// $('body').on('keyup','.rafflePrize',function(e) {

		// 	$(this).removeClass('bg-info-gradient');

		// 	$(this).addClass('bg-danger-gradient');

		// 	checkwinner();



		// })



		$('#exportbtn').hide();

	})();



	function checkwinner() {


		$('#rafflePrizeOne').val($('#rafflePrizeOne').val().replace(/[^A-Za-z0-9]/g, ''));

		$('#rafflePrizeTwo').val($('#rafflePrizeTwo').val().replace(/[^A-Za-z0-9]/g, ''));

		$('#rafflePrizeThree').val($('#rafflePrizeThree').val().replace(/[^A-Za-z0-9]/g, ''));

		$('.showLoading').prop('disabled', true);

		$('.error').html('');





		var formdata = $('#winnerdata').serializeArray();



		formdata.push({

			name: 'method',

			value: "check_winner_list"

		});



		var post_data = formdata;



		var onsuccess = function(data) {



			var response = JSON.parse(data);



			if (response != "") {

				if (response.type == 1) {

					$('.showLoading').prop('disabled', false);

					$('.rafflePrize ').addClass('bg-info-gradient');

					$('.rafflePrize ').removeClass('bg-danger-gradient');

				} else {

					var id = response.input;
					$('#' + id).removeClass('bg-info-gradient');
					$('#' + id).addClass('bg-danger-gradient');
					$('#' + id).next().html(response.result);

				}

			}

		}



		do_ajax_call(post_data, onsuccess, window.location.origin + '/ajax/service/raffle_services.php');



	}



	function winner() {

		$('.error').html('');

		var error = 0;

		if ($('#rafflePrizeOne').val() == '') {

			error++;

			$('#rafflePrizeOne').next().html('Please Enter Raffle Number');

		}

		if ($('#rafflePrizeTwo').val() == '') {

			error++;

			$('#rafflePrizeTwo').next().html('Please Enter Raffle Number');

		}

		if ($('#rafflePrizeThree').val() == '') {

			error++;

			$('#rafflePrizeThree').next().html('Please Enter Raffle Number');

		}



		if (error > 0) {

			return false;

		}



		var formdata = $('#winnerdata').serializeArray();



		formdata.push({

			name: 'method',

			value: "winner_list"

		});



		var post_data = formdata;

		var btn = document.getElementById('winnerBtn').innerHTML;

		document.getElementById('winnerBtn').innerHTML = '<div class="col-sm-12 text-center"><button class="btn btn-primary py-2" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Loading...</button></div>';

		var onsuccess = function(data) {

			var response = JSON.parse(data);

			if (response != "") {

				if (response.type == 1) {

					$('#winningno').modal('hide');

					document.getElementById('winnerBtn').innerHTML = btn;

					// viewtable(response.id);

					getTabel(response.id);

				} else {

					document.getElementById('winnerBtn').innerHTML = btn;

					var id = response.input;
					$('#' + id).removeClass('bg-info-gradient');
					$('#' + id).addClass('bg-danger-gradient');
					$('#' + id).next().html(response.result);

				}

			}

		}



		do_ajax_call(post_data, onsuccess, window.location.origin + '/ajax/service/raffle_services.php');



	}



	function getTabel(id) {

		document.getElementById('repcpdfbtn').innerHTML = '';

		$('#ldfullre').html();

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

					document.getElementById('repcpdfbtn').innerHTML = response.ded;

					document.getElementById('ldfullre').innerHTML = response.ldfullre;

				} else {

					toast('error', response.result);

				}

			}

		}

		do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/raffle_winners_email.php");



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



	function getWinnerList(pid, drawid, title, liveurl) {

		document.getElementById('ldbtnnew').innerHTML = '';

		if (pid != 0) {

			var buildUrl = liveurl + 'rbddedreport.php?drawid=' + drawid + '&proid=' + pid;

			// 			document.getElementById('ldbtnnew').innerHTML = '<a href="' + buildUrl + '" class="btn btn-info" target="_blank" rel="noopener noreferrer">LD ' + title + ' Report</a>';

		}



		var table = $('#drawtable').DataTable();

		table.destroy();



		var title = title;

		table = $("#drawtable").DataTable({

			pageLength: 10,







			paging: true,

			searching: true,

			info: true,

			ajax: {

				url: window.location.origin + "/ajax/service/raffle_winners_email.php",

				method: "POST",

				dataSrc: "",

				data: {

					method: 'list_draw_winner_new',

					pid: pid,

					drawid: drawid

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



					data: 'ticketid'



				},

				{



					data: 'name'



				},



				{



					data: 'mobile'



				},

				{



					data: 'email'



				},

				{



					data: 'my3num'



				},

				{



					data: 'raffle'



				},

				{



					data: 'product'



				},



				{



					data: 'prize'



				},



				{



					data: 'amt'



				},



				{



					data: 'date'



				},

				{

					data: 'empty'

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



					.column(8)



					.data()



					.reduce(function(a, b) {



						let x = intVal(a) + intVal(b);



						return x.toFixed(2);



					}, 0);







				// Total over this page



				pageTotal = api



					.column(8, {



						page: 'current'



					})



					.data()



					.reduce(function(a, b) {



						return intVal(a) + intVal(b);



					}, 0);







				// Update footer



				$(api.column(8).footer()).html('' + pageTotal + ' ( ' + total + ' total)');



			},

			order: [],

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

			do_ajax_call(post_data, onsuccess, window.location.origin + "/ajax/service/raffle_winners_email.php");

		} else {

			toast('error', 'Kindly enter the DED Permit Number');

		}



	}
</script>