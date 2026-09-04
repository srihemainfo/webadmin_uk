<?


/**
 *
 * 		Date         	Developer     Changes
 * 		21-06-2023		Prashant	  Raffle Draw Announment
 * */

if ($subid3 != '') {



	$draw = select_query($con, "draw", "", "`id`= '$subid3' AND `deletes` = '0' ", "", "");



	if ($draw['nr'] > 0) {

		$status = $draw['result'][0]['raffle_status'];

		$draw_id  = $draw['result'][0]['id'];
	}
} else {

	$now = date("Y-m-d h:i:s");

	$draw = select_query($con, "draw", "", "`raffle_status` = 'Active' AND `result_datetime` > '$now' AND `deletes` = '0' ORDER BY `result_datetime` ASC LIMIT 1", "", "");
	$resultDate = date_format(date_create($draw['result'][0]['result_datetime']), "Y-m-d h:i:s a");
	// $status = $draw['result'][0]['raffle_status'];
	$subid3 = $draw['result'][0]['id'];
	$subid2 = 'edit';
}



$pagecontent = '';

if ($subid2 == 'edit') {

	$pagecontent = 'list_draw_winner';
} else {

	$pagecontent = 'list_draw_winner_final';
}



?>



<style>

.less-card {
    height: 113px;
    overflow: auto;
}
.more-card {
    height: 500px;
    overflow: scroll;
}
	.bg-light {
    position: relative;
    margin-left: 20px;
    margin-bottom: 10px;
    padding: 10px;
    font: 400 0.9em 'Open Sans', sans-serif;
    border: 1px solid #97C6E3;
    border-radius: 10px;
    width: 96%;
    background-color: #f6f6fb !important;
}
.bg-light:before {
    content: '';
    position: absolute;
    width: 0;
    height: 0;
    border-top: 17px solid #f6f6fb;
    border-left: 16px solid transparent;
    border-right: 16px solid transparent;
    top: -1px;
    left: -17px;
}
.bg-light:after {
    content: '';
    position: absolute;
    width: 0;
    height: 0;
    border-top: 15px solid #f6f6fb;
    border-left: 15px solid transparent;
    border-right: 15px solid transparent;
    top: 0;
    left: -15px;
}
	.bg-light1 {
		position: relative;
		width: 96%;
		margin-bottom: 10px;
		/* margin-left: calc(100% - 240px); */
		padding: 10px;
		background-color: #dfdff5;
		font: 400 0.9em 'Open Sans', sans-serif;
		border: 1px solid #dfd087;
		border-radius: 10px;
	}

	.bg-light1:before {
		content: '';
		position: absolute;
		width: 0;
		height: 0;
		border-top: 17px solid #dfdff5;
		border-left: 16px solid transparent;
		border-right: 16px solid transparent;
		top: 0px;
		right: -15px;
		
	}

	.bg-light1:after {
		content: '';
		position: absolute;
		width: 0;
		height: 0;
		border-top: 15px solid #dfdff5;
		border-left: 15px solid transparent;
		border-right: 15px solid transparent;
		top: 0;
		right: -15px;
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
</style>


<script>
	window.onload = function() {

		var page_origin = window.location.origin;

		let anchor = document.getElementById("anchor");

		anchor.href = page_origin;

	}
</script>



<div class="main-content app-content mt-0">

	<input type="hidden" value="<?= $subid3; ?>" id="winnerid">

	<input type="hidden" id="redirecturl">

	<div class="side-app">



		<input type="hidden" value="<?= $pagecontent; ?>" id="tabid">



		<div class="main-container container-fluid">







			<div class="page-header">



				<h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Just3 Raffle - Announce</h1>



				<div>



					<ol class="breadcrumb">



						<li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>



						<li class="breadcrumb-item active" aria-current="page">Just3 Raffle - Announce</li>



					</ol>



				</div>



			</div>


			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">



					<div class="card bg-secondary-gradient img-card box-primary-shadow">



						<div class="card-body">



							<div class="d-flex">



								<div class="text-white">






									<h2 class="mb-0 number-font"><?= $draw['result'][0]['name']; ?></h2>



									<h4 class="text-white mb-0"><?= date_format(date_create($draw['result'][0]['result_datetime']), "Y-m-d h:i:s a"); ?></h4>






								</div>



								<div class="ms-auto"> <i class="fa fa-money text-white fs-30 me-2 mt-2"></i> </div>



							</div>



						</div>



					</div>



				</div>
			</div>


			<div class="row row-sm">
				<div class="col-lg-12">
					<div class="card">

						<div class="card-header d-flex justify-content-between">



							<h3 class="card-title">Draw Notifications</h3>



							<div class="text-end">

								<p class="btn btn-light" onclick="notificationHistory()"> <i class="fa fa-refresh"></i></p>


							</div>


						</div>

						<div class="card-body ">

							<div class="less-card" id="htmlNotify">


							</div>

							<div class="row">
								<div class="col-11 text-end">

									<p class="pt-3" onclick="changeClassTonot()" id="BTNtextsm" style="cursor:pointer;">View More</p>
								</div>
							</div>


						</div>
					</div>
				</div>
			</div>

			<div class="row">

				<div class="col-md-4">

					<div class="card text-center bg-success-gradient">

						<div class="card-body">

							<h1 class="card-title" style="color: white;">1<sup>st</sup>Prize RaffleID</h1>

							<h3 class="card-text" style="color: white;font-size: 40px;"> <?= $draw['result'][0]['raffleprizefirst']; ?></h3>

						</div>

					</div>

				</div>



				<div class="col-md-4">

					<div class="card text-center bg-info-gradient">

						<div class="card-body">

							<h1 class="card-title" style="color: white;">2<sup>nd</sup> Prize RaffleID</h1>

							<h3 class="card-text" style="color: white; font-size: 40px;"> <?= $draw['result'][0]['raffleprizesecond']; ?></h3>

						</div>

					</div>

				</div>

				<div class="col-md-4">

					<div class="card text-center bg-warning-gradient">

						<div class="card-body">

							<h1 class="card-title" style="color: white;">3<sup>rd</sup>Prize RaffleID</h1>

							<h4 class="card-text" style="color: white; font-size: 35px;"> <?= $draw['result'][0]['raffleprizethird']; ?></h4>



						</div>

					</div>

				</div>

			</div>

			<div class="row row-sm">

				<div class="col-lg-12">



					<div class="card">

						<div class="row" style="align-items: center;">

							<div class="card-header d-block">

								<div style="float: left;">

									<h3 class="card-title">Raffle Winning Announcement List</h3>

								</div>

								<?php if ($subid2 != 'edit') { ?>

									<div id="bulkemail" style="float: right;">

										<!--<button class="btn btn-primary" onclick="bulkemail()">Bulk Email Send</button>-->

									</div>

								<?php } ?>

							</div>

						</div>



						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered text-nowrap border-bottom" id="winnersdatble">

									<thead>

										<tr>

											<th class="wd-15p border-bottom-0">Name</th>

											<th class="wd-15p border-bottom-0">Phone No.</th>

											<th class="wd-25p border-bottom-0">Product</th>

											<th class="wd-25p border-bottom-0">Prize</th>

											<th class="wd-25p border-bottom-0">Amount</th>

											<th class="wd-25p border-bottom-0">Message</th>



											<?php if ($pagecontent == 'list_draw_winner_final') { ?>

												<th class="wd-25p border-bottom-0">Email</th>

												<th class="wd-25p border-bottom-0">sms</th>

											<?php } ?>

										</tr>

									</thead>

									<tbody>



									</tbody>





								</table>
							</div>



							<div class="col-sm col-lg-12 mt-3 d-flex justify-content-between" id="actionBTN">
								<?php
								if ($subid2 != 'list') {
									if ($status == 'Active' && $draw['result'][0]['just3_raffle_review'] == 'Submitted') { ?>
										<div id="drawBTNreject">
											<button type="button" style=" background-color: red !important;  color: #fff!important;" onclick="rejectBTN(<?= $draw['result'][0]['id']; ?>)" class="btn btn-info1"><i class="fa fa-bullhorn me-2"></i>Reject</button>
										</div>
									<?php } ?>
									<?php if ($status == 'Active' && $draw['result'][0]['just3_raffle_review'] == 'Submitted') { ?>
										<div id="drawBTNannounce">
											<button type="button" data-bs-effect="effect-scale" data-bs-toggle="modal" data-bs-target="#confirmModel" style="" class="btn btn-info"><i class="fa fa-bullhorn me-2"></i>Announce</button>
										</div>
								<?php }
								} ?>
							</div>


							<!-- <?php if ($status == 'Active') { ?>

								<div class="col-sm col-lg-6" id="drawBTNannounce">

									<button type="button" data-bs-effect="effect-scale" data-bs-toggle="modal" data-bs-target="#confirmModel" style="float: right;" class="btn btn-info"><i class="fa fa-bullhorn me-2"></i>Announce</button>

								</div>

							<?php } ?> -->

						</div>

					</div>

				</div>



			</div>





		</div>







	</div>



</div>





<div class="modal fade" id="confirmModel">

	<div class="modal-dialog" role="document">

		<div class="modal-content modal-content-demo">

			<div class="modal-header">

				<h6 class="modal-title">Verified</h6>

				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">×</span>

				</button>

			</div>

			<div class="modal-body">

				<div class="container">

					<form id="winnerdata">

						<input type="hidden" name="deleteid" id="deleteid">

						<div class="form-group">

							<div class="model-text">

								<h3 class="text-center">Verified Successfully

								</h3> <br>

							</div>

							<div id="AnnounceMessage"></div>

						</div>

					</form>

					<br>

				</div>

			</div>

			<div class="modal-footer">

				<div id="announcebtn">

					<button class="btn btn-primary" onclick="announce()" type="button">Submit</button>

				</div>

			</div>

		</div>

	</div>

</div>

</div>





<div class="modal fade" id="viewprofile">

	<div class="modal-dialog modal-xl" role="document">

		<div class="modal-content modal-content-demo">

			<div class="modal-header">

				<h6 class="modal-title">View Winners</h6>

				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">×</span>

				</button>

			</div>

			<div class="modal-body">



				<div class="card">



					<div class="card-body">

						<div class="row">

							<div class="col-xl-4">

								<div class="card">

									<div class="card-header">

										<div class="card-title">Winner Profile</div>

									</div>

									<div class="card-body">

										<div class="text-center chat-image mb-5">

											<div class="avatar avatar-xxl chat-profile mb-3 brround">

												<a class="" href="agentview"><img alt="avatar" src="assets/images/users/2.jpg" class="brround"></a>

											</div>

											<div class="main-chat-msg-name">

												<a href="agentview">

													<h5 class="mb-1 text-dark fw-semibold">National Draw</h5>

												</a>

												<p class="text-muted mt-0 mb-0 pt-0 fs-13">ID - 54321</p>

											</div>

										</div>



										<ul class="list-group no-margin">

											<li class="list-group-item d-flex ps-3">

												<div class="social social-profile-buttons me-2">

													<a class="social-icon text-primary" href=""><i class="fe fe-mail"></i></a>

												</div>

												<a href="javascript:void(0)" class="my-auto">mymail@gmail.com</a>

											</li>

											<li class="list-group-item d-flex ps-3">

												<div class="social social-profile-buttons me-2">

													<a class="social-icon text-primary" href=""><i class="fa fa-address-card-o"></i></a>

												</div>

												<a href="javascript:void(0)" class="my-auto">784-1979-1234567-1</a>

											</li>

											<li class="list-group-item d-flex ps-3">

												<div class="social social-profile-buttons me-2">

													<a class="social-icon text-primary" href=""><i class="fe fe-phone"></i></a>

												</div>

												<a href="javascript:void(0)" class="my-auto">+971 9876 5432</a>

											</li>

										</ul>





									</div>



								</div>



							</div>

							<div class="col-xl-8">

								<div class="card-header">

									<h3 class="card-title">Bank Details</h3>

								</div>



								<div class="card-body">

									<div class="row">

										<div class="col-lg-6 col-md-12">

											<div class="form-group">

												<label for="exampleInputname">Account Name</label>

												<input type="text" class="form-control" id="exampleInputname" placeholder="National Draw">

											</div>

										</div>

										<div class="col-lg-6 col-md-12">

											<div class="form-group">

												<label for="exampleInputEmail1">AC. No.</label>

												<input type="text" class="form-control" id="exampleInputEmail1" placeholder="5463 2415 2564 2145">

											</div>

										</div>

									</div>

									<div class="row">



										<div class="col-lg-6 col-md-12">

											<div class="form-group">

												<label for="exampleInputnumber">Bank Name</label>

												<input type="text" class="form-control" id="exampleInputnumber" placeholder="ARAB BANK PLC.">

											</div>

										</div>

										<div class="col-lg-6 col-md-12">

											<div class="form-group">

												<label for="exampleInputnumber">Bank Branch & Address</label>

												<input type="text" class="form-control" id="exampleInputnumber" placeholder="DUBAI">

											</div>

										</div>



										<div class="col-lg-6 col-md-12">

											<div class="form-group">

												<label for="exampleInputnumber">Swift code</label>

												<input type="text" class="form-control" id="exampleInputnumber" placeholder="ARABAEADDER">

											</div>

										</div>

									</div>



								</div>



							</div>

						</div>

					</div>

					<div class="card-footer text-end">

						<a href="javascript:void(0)" class="btn btn-primary">Winner Announce</a>

					</div>

				</div>

			</div>

		</div>

	</div>

</div>

<div class="modal fade" id="announce">

	<div class="modal-dialog modal-lg" role="document">

		<div class="modal-content modal-content-demo">

			<div class="modal-header">

				<h6 class="modal-title">View Totel Winners</h6>

				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">×</span>

				</button>

			</div>

			<div class="modal-body">



				<div class="row row-sm">

					<div class="col-lg-12">

						<div class="card">

							<div class="card-header">

								<h3 class="card-title">Totel Winners</h3>

								<div class="col-sm">

									<button type="button" id="smallmodal" data-bs-effect="effect-scale" style="float: right;" class="btn btn-info"><i class="fa fa-user-plus me-2"></i>Announce All</button>

								</div>

							</div>

							<div class="card-body">

								<table class="table table-bordered text-nowrap border-bottom" id="example">

									<thead>

										<tr>

											<th class="wd-15p border-bottom-0">Name</th>

											<th class="wd-15p border-bottom-0">Phone No.</th>

											<th class="wd-25p border-bottom-0">E-mail</th>

											<th class="wd-25p border-bottom-0">Product</th>

											<th class="wd-25p border-bottom-0">Place</th>

										</tr>



										<tr>



											<td>Rafiq</td>

											<td>7894561234</td>

											<td>rafiq@gmail.com</td>

											<td>10 AED</td>

											<td>1<sup>st</sup>Price</td>

										</tr>

									</thead>



								</table>

							</div>

						</div>

					</div>



				</div>

			</div>

		</div>

	</div>

</div>







<div class="modal fade" id="sendemailwinner">

	<div class="modal-dialog" role="document">

		<div class="modal-content modal-content-demo">

			<div class="modal-header">

				<h6 class="modal-title">Send Mail</h6>

				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">×</span>

				</button>

			</div>

			<div class="modal-body">

				<div class="container">

					<form id="winnerdata">

						<input type="hidden" name="winnerlistid" id="winnerlistid">

						<input type="hidden" name="draw_id" id="draw_id">

						<div class="form-group">

							<div class="model-text">

								<h3 class="text-center">Are you sure you want to send email?

								</h3> <br>

							</div>

							<div id="ErrMessage"></div>





						</div>



					</form>

					<br>

				</div>

			</div>

			<div class="modal-footer">

				<div id="emailbtn">

					<button class="btn btn-primary" onclick="sendmail_winner()" type="button">Submit</button>

				</div>

			</div>

		</div>

	</div>

</div>













<div class="modal fade" id="sendsmswinner">

	<div class="modal-dialog" role="document">

		<div class="modal-content modal-content-demo">

			<div class="modal-header">

				<h6 class="modal-title">Send Mail</h6>

				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">×</span>

				</button>

			</div>

			<div class="modal-body">

				<div class="container">

					<form id="winnerdata">

						<input type="hidden" name="winnerlistid" id="winnerlistid">

						<input type="hidden" name="draw_id" id="draw_id">

						<div class="form-group">

							<div class="model-text">

								<h3 class="text-center">Are you sure you want to send SMS?

								</h3><br>

							</div>

							<div id="smsErrMessage"></div>





						</div>



					</form>

					<br>

				</div>

			</div>

			<div class="modal-footer">

				<div id="smsbtn">

					<button class="btn btn-primary" onclick="sendsms_winner()" type="button">Submit</button>

				</div>

			</div>

		</div>

	</div>

</div>



<div class="modal fade" id="delete">

	<div class="modal-dialog modal-dialog-centered text-center" role="document">

		<div class="modal-content tx-size-sm">

			<div class="modal-body text-center p-4 pb-5">

				<button aria-label="Close" class="btn-close position-absolute" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>

				<i class="icon icon-close fs-70 text-danger lh-1 my-5 d-inline-block"></i>

				<h4 class="text-danger">Are you sure you want to delete page</h4>

				<button aria-label="Close" class="btn  pd-x-25" data-bs-dismiss="modal">Yes</button>

				<button aria-label="Close" class="btn btn-primary pd-x-25" data-bs-dismiss="modal">No</button>

			</div>

		</div>

	</div>

</div>


<div class="modal fade" id="RejectModal">

	<div class="modal-dialog modal-sm" role="document">

		<div class="modal-content modal-content-demo">

			<div class="modal-header">

				<h6 class="modal-title">Reject Reason</h6>

				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">×</span>

				</button>

			</div>

			<div class="modal-body">



				<div class="row row-sm">
					<div class="col-lg-12" id="rejectERR">
					</div>
					<div class="col-lg-12">
						<textarea id="deletemessage" cols="25" rows="10" oninput="this.value = this.value.replace(/[^0-9A-za-z .-()]/g, '');" placeholder="Reason"></textarea>


					</div>



				</div>

			</div>
			<div class="modal-footer">

				<!-- <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button> -->

				<div id="reject_btn">



				</div>

			</div>

		</div>

	</div>

</div>

<script>
	var origin = window.location.origin;
	var mailurl = origin + "/ajax/service/just3raffle_winners_email.php";

	$(function() {

		// viewform();

		viewtable();
		notificationHistory();
		$('#confirmModel').modal({
			backdrop: 'static',
			keyboard: false
		})

	});



	// function viewform(str = 0) {

	// 	var post_data = {

	// 		editid: str

	// 	};

	// 	$("#showsuccessalert", "#showerroralert").hide();

	// 	var divId = ".cls_form_div";

	// 	var oncontinue = function(event) {}

	// 	viewfile("agentform", post_data, divId, oncontinue);

	// }



	function viewtable() {

		var formdata = [];

		let winnerid = $('#winnerid').val();

		let tabid = document.getElementById('tabid').value;

		formdata.push({

			name: 'method',
			value: tabid

		}, {
			name: 'winnerid',
			value: winnerid

		});

		formdata.push();

		var post_data = formdata;



		var onsuccess = function(data) {

			var response = JSON.parse(data);



			if (response != "") {





				if (tabid == 'list_draw_winner') {





					$('#winnersdatble').DataTable({

						"data": response,
						order: [
							[3, 'asc']
						],
						"columns": [{

								'data': 'name'

							},

							{

								'data': 'mobile'

							},

							{

								'data': 'product'

							},

							{

								'data': 'prize'

							},

							{

								'data': 'amt'

							},

							{

								'data': 'message'

							},



						],

					});



				} else {

					$('#winnersdatble').DataTable({



						"data": response,

						dom: 'Bfrtip',

						buttons: [

							'copy', 'csv', 'excel', {

								extend: 'pdfHtml5',

								orientation: 'landscape',

								pageSize: 'LEGAL'

							}, 'print',



						],

						order: [

							[3, 'asc']

						],

						"columns": [{

								'data': 'name'

							},

							{

								'data': 'mobile'

							},

							{

								'data': 'product'

							},

							{

								'data': 'prize'

							},

							{

								'data': 'amt'

							},

							{

								'data': 'message'

							},

							{

								'data': 'emailme'

							},

							{

								'data': 'smsme'

							},

						],

					});

				}



			} else {
				// if (response.length < 1) {
				// 	$('#drawBTNannounce').hide();
				// }
			}





		}



		do_ajax_call(post_data, onsuccess, mailurl);



	}



	function bulkemail() {

		document.getElementById('bulkemail').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';

		var formdata = [];

		var success = [];

		let winnerid = $('#winnerid').val();

		formdata.push({

			name: 'method',

			value: "bulkemail"

		});

		formdata.push({

			name: 'winnerid',

			value: winnerid

		});



		var post_data = formdata;



		var onsuccess = function(data) {

			var response = JSON.parse(data);

			if (response != "") {





				if (response.type == 1) {



					let len = response.result.length;

					for (let i = 0; i < response.result.length; i++) {

						var formdata = [];

						formdata.push({

							name: 'method',

							value: "send_email_winner"

						});

						formdata.push({

							name: 'winnerlistid',

							value: response.result[i]['id']

						});





						var post_data = formdata;



						var onsuccess = function(data) {

							var response = JSON.parse(data);

							if (response != "") {

								if (response.type == 1) {

									success.push(response.type);

								} else {

									success.push(response.type);

								}







								if (success.length == len) {

									document.getElementById('bulkemail').innerHTML = '<button class="btn btn-primary" onclick="bulkemail()">Bulk Email Send</button>';

								}

							}

						}







						do_ajax_call(post_data, onsuccess, mailurl);

					}

				} else {

					document.getElementById('bulkemail').innerHTML = '<button class="btn btn-primary" onclick="bulkemail()">Bulk Email Send</button>';

				}



			}

		}



		do_ajax_call(post_data, onsuccess, mailurl);



	}







	function saveform() {





		var formdata = $('.cls_booking_form').serializeArray();



		formdata.push({

			name: 'method',

			value: "add_agent"

		});

		var post_data = formdata;



		alert(post_data);



		var onsuccess = function(data) {

			var response = JSON.parse(data);

			if (response != "") {





				if (response.type == 0) {

					$("#showsuccessalert").hide();

					$("#showerroralert").html(response.result);



				} else {

					$("#showerroralert").hide();

					$("#showsuccessalert").html(response.result);

				}



			}

		}



		do_ajax_call(post_data, onsuccess);



	}





	function announce() {

		document.getElementById('announcebtn').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';

		var success = [];

		let winnerid = $('#winnerid').val();

		var formdata = [];



		formdata.push({

			name: 'method',

			value: "announce"

		});



		formdata.push({

			name: 'winnerid',

			value: winnerid

		});

		var post_data = formdata;





		var onsuccess = function(data) {
			var response = JSON.parse(data);
			if (response != "") {

				if (response.type == 1) {

					document.getElementById('AnnounceMessage').innerHTML = '<div class="alert alert-success" role="alert">Announced Successfully!</div>';

					document.getElementById('announcebtn').innerHTML = '<button aria-label="Close" class="btn btn-success pd-x-25" onclick="colse_model()">Transfer to Wallet</button>';

					document.getElementById('redirecturl').value = response.url;

				} else {

					// document.getElementById('AnnounceMessage').innerHTML = '<div class="alert alert-success" role="alert">Announced Successfully!</div>';

					// document.getElementById('announcebtn').innerHTML = '<button aria-label="Close" class="btn btn-success pd-x-25" onclick="colse_model()">Close</button>';



				}



			}

		}



		do_ajax_call(post_data, onsuccess, mailurl);

	}







	function colse_model() {

		let url = document.getElementById('redirecturl').value;

		window.location.href = url;



		// $('#successModal').modal('hide');

		// location.reload();

	}





	function sendmailwinner(id) {

		document.getElementById('ErrMessage').innerHTML = '';

		document.getElementById('winnerlistid').value = id;

		// document.getElementById('draw_id').value = draw_id;

		$('#sendemailwinner').modal('show');

	}



	function sendmail_winner() {

		let ticket_id = document.getElementById('winnerlistid').value;

		// let draw_id = document.getElementById('draw_id').value;

		document.getElementById('emailbtn').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';



		var formdata = [];

		formdata.push({

			name: 'method',

			value: "send_email_winner"

		});

		formdata.push({

			name: 'winnerlistid',

			value: ticket_id

		});







		var post_data = formdata;



		var onsuccess = function(data) {

			var response = JSON.parse(data);

			if (response != "") {

				if (response.type == 1) {

					document.getElementById('ErrMessage').innerHTML = '<div class="alert alert-success" role="alert">' + response.result + '</div>';

					document.getElementById('emailbtn').innerHTML = '<button class="btn btn-primary" onclick="Semail_close()" type="button">Close</button>';





				} else {

					document.getElementById('ErrMessage').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';

					document.getElementById('emailbtn').innerHTML = '<button class="btn btn-primary" onclick="sendmail_winner()" type="button">Submit</button>';

				}

			}

		}



		do_ajax_call(post_data, onsuccess, mailurl);



	}





	function Semail_close() {

		$('#sendemailwinner').modal('hide');

		location.reload();

		// var table = $('#winnersdatble').DataTable();

		// table.destroy();

		// viewtable();



	}





	// sms



	function sendsmswinner(id) {

		document.getElementById('smsErrMessage').innerHTML = '';

		document.getElementById('winnerlistid').value = id;

		// document.getElementById('draw_id').value = draw_id;

		$('#sendsmswinner').modal('show');

	}



	function sendsms_winner() {

		let ticket_id = document.getElementById('winnerlistid').value;

		// let draw_id = document.getElementById('draw_id').value;

		document.getElementById('smsbtn').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';



		var formdata = [];

		formdata.push({

			name: 'method',

			value: "send_sms_winner"

		});

		formdata.push({

			name: 'winnerlistid',

			value: ticket_id

		});

		// formdata.push({

		// 	name: 'draw_id',

		// 	value: draw_id

		// });





		var post_data = formdata;



		var onsuccess = function(data) {

			var response = JSON.parse(data);

			if (response != "") {

				if (response.type == 1) {

					document.getElementById('smsErrMessage').innerHTML = '<div class="alert alert-success" role="alert">' + response.result + '</div>';

					document.getElementById('smsbtn').innerHTML = '<button class="btn btn-primary" onclick="Sesms_close()" type="button">Close</button>';



					// location.reload();

				} else {

					document.getElementById('smsErrMessage').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';

					document.getElementById('smsbtn').innerHTML = '<button class="btn btn-primary" onclick="sendsms_winner()" type="button">Submit</button>';

				}

			}

		}



		do_ajax_call(post_data, onsuccess, mailurl);



	}





	function Sesms_close() {

		$('#sendsmswinner').modal('hide');

		location.reload();

		// var table = $('#winnersdatble').DataTable();

		// table.destroy();

		// viewtable();



	}


	function changeClassTonot() {
		if ($('#htmlNotify').hasClass('less-card')) {
			$('#htmlNotify').removeClass('less-card');
			$('#htmlNotify').addClass('more-card');
			$('#BTNtextsm').text('View Less');
		} else {
			$('#htmlNotify').addClass('less-card');
			$('#htmlNotify').removeClass('more-card');
			$('#BTNtextsm').text('View More');
		}
	}



	function notificationHistory() {



		let id = <?= $draw['result'][0]['id']; ?>;
		if (id == '' || id == null || id == undefined || id == 'null') {
			toast('error', 'The Draw ID has been missing!');
			return false;
		}


		// document.getElementById('deletebtn').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';



		// alert(id);



		var formdata = [];



		formdata.push({



			name: 'method',



			value: "notificationHistory"



		}, {



			name: 'drawID',



			value: id



		});









		var post_data = formdata;







		var onsuccess = function(data) {



			var response = JSON.parse(data);



			if (response != "") {



				if (response.type == 1) {

					if (response.result.length > 0) {

						let html = '';
						for (let i = 0; i < response.result.length; i++) {
							// console.log(response.result[i]);

							html += `<div class="card ${ (response.result[i].draw_status == 'Rejected') ? 'bg-light1': 'bg-light'}  p-3">
					<div class="row  align-items-center">
					<div class="col-12">
			<h4>${response.result[i].name}</h4>
					<small>${moment(response.result[i].createdon).format('Do MMM YYYY, h:mm:ss a')}</small>
					<p class="pt-2">${response.result[i].message}</p>
					</div></div></div>`;
						}

						$('#htmlNotify').html(html);

					}

					// document.getElementById('deleteMessage').innerHTML = '<div class="alert alert-success" role="alert">' + response.result + '</div>';



					// document.getElementById('deletebtn').innerHTML = '<button class="btn btn-primary" onclick="deletedraw_model()" type="button">Close</button>';







					// location.reload();



				} else {

					toast('error', response.result);

					// document.getElementById('deleteMessage').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + 'r</div>';



					// document.getElementById('deletebtn').innerHTML = '<button class="btn btn-primary" onclick="delete_now()" type="button">Submit</button>';



				}



			}



		}







		do_ajax_call(post_data, onsuccess, origin + '/ajax/service/just3_raffle_services.php');



	}


	function rejectBTN(drawID) {
		try {
			if (drawID == null || drawID == '') {
				toast('error', 'The Draw ID has been missing!');
				return false;
			}

			$('#reject_btn').html(`<button class="btn ripple btn-danger" onclick="rejecthteDRAW(${drawID})" type="button">Submit</button>`);
			$('#RejectModal').modal('show');
		} catch (error) {
			console.log('An error: '.error.message);
		}
	}


	function rejecthteDRAW(drawID) {
		try {
			$('#rejectERR').html('');
			if (drawID == null || drawID == '') {
				toast('error', 'The Draw ID has been missing!');
				return false;
			}

			if ($('#deletemessage').val() == null || $('#deletemessage').val().trim() == '') {
				$('#rejectERR').html(`<div class="alert alert-danger mb-2" role="alert">
										Kindly Enter the text message!
										</div>`);
				return false;
			}



			var formdata = [];

			formdata.push({

				name: 'method',

				value: "rejectTheDraw"

			}, {

				name: 'RejectReason',

				value: $('#deletemessage').val()

			}, {

				name: 'drawID',

				value: drawID

			});


			var post_data = formdata;

			var btn = $('#reject_btn').html();

			$('#reject_btn').html(`<button class="btn btn-primary" type="button" disabled>
									<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
									Loading...
									</button>`);
			var onsuccess = function(data) {

				var response = JSON.parse(data);

				if (response != "") {

					if (response.type == 1) {
						$('#RejectModal').modal('hide');
						toast('success', `${response.result}`);
						$('#reject_btn').html(btn);
						$('#actionBTN').hide();
						$('#drawBTNannounce').hide();
						setTimeout("location.reload(true);", 5000);

					} else {
						$('#rejectERR').html(`<div class="alert alert-danger mb-2" role="alert">${response.result}</div>`);
						$('#reject_btn').html(btn);
					}

				}

			}



			do_ajax_call(post_data, onsuccess, mailurl);
		} catch (error) {
			console.log('An error: '.error.message);
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