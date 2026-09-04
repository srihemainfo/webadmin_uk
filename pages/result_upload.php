<!--
1.modifications unknown


Date      Developer_name      Modifications
-->


<style>
	.nav-item .nav-link,

	.nav-tabs .nav-link {

		-webkit-transition: all 300ms ease 0s;

		-moz-transition: all 300ms ease 0s;

		-o-transition: all 300ms ease 0s;

		-ms-transition: all 300ms ease 0s;

		transition: all 300ms ease 0s;

	}



	.card-Tab a {

		-webkit-transition: all 150ms ease 0s;

		-moz-transition: all 150ms ease 0s;

		-o-transition: all 150ms ease 0s;

		-ms-transition: all 150ms ease 0s;

		transition: all 150ms ease 0s;

	}



	[data-toggle="collapse"][data-parent="#accordion"] i {

		-webkit-transition: transform 150ms ease 0s;

		-moz-transition: transform 150ms ease 0s;

		-o-transition: transform 150ms ease 0s;

		-ms-transition: all 150ms ease 0s;

		transition: transform 150ms ease 0s;

	}



	[data-toggle="collapse"][data-parent="#accordion"][aria-expanded="true"] i {

		filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2);

		-webkit-transform: rotate(180deg);

		-ms-transform: rotate(180deg);

		transform: rotate(180deg);

	}





	.now-ui-icons {

		display: inline-block;

		font: normal normal normal 14px/1 'Nucleo Outline';

		font-size: inherit;

		speak: none;

		text-transform: none;

		-webkit-font-smoothing: antialiased;

		-moz-osx-font-smoothing: grayscale;

	}



	@-webkit-keyframes nc-icon-spin {

		0% {

			-webkit-transform: rotate(0deg);

		}



		100% {

			-webkit-transform: rotate(360deg);

		}

	}



	@-moz-keyframes nc-icon-spin {

		0% {

			-moz-transform: rotate(0deg);

		}



		100% {

			-moz-transform: rotate(360deg);

		}

	}



	@keyframes nc-icon-spin {

		0% {

			-webkit-transform: rotate(0deg);

			-moz-transform: rotate(0deg);

			-ms-transform: rotate(0deg);

			-o-transform: rotate(0deg);

			transform: rotate(0deg);

		}



		100% {

			-webkit-transform: rotate(360deg);

			-moz-transform: rotate(360deg);

			-ms-transform: rotate(360deg);

			-o-transform: rotate(360deg);

			transform: rotate(360deg);

		}

	}



	.now-ui-icons.objects_umbrella-13:before {

		content: "\ea5f";

	}



	.now-ui-icons.shopping_cart-simple:before {

		content: "\ea1d";

	}



	.now-ui-icons.shopping_shop:before {

		content: "\ea50";

	}



	.now-ui-icons.ui-2_settings-90:before {

		content: "\ea4b";

	}



	.nav-tabs {

		border: 0;

		padding: 15px 0.7rem;

	}



	.nav-tabs:not(.nav-tabs-neutral)>.nav-item>.nav-link.active {

		box-shadow: 0px 0px 11px 0px rgb(0 0 0 / 30%);

	}



	.card .nav-tabs {

		border-top-right-radius: 0.1875rem;

		border-top-left-radius: 0.1875rem;

	}



	.nav-tabs>.nav-item>.nav-link {

		color: #888888;

		margin: 0;

		margin-right: 5px;

		background-color: transparent;

		/* border: 1px solid transparent; */

		border-radius: 30px;

		font-size: 14px;

		padding: 11px 23px;

		line-height: 1.5;

	}



	.nav-tabs>.nav-item>.nav-link:hover {

		background-color: #f0f0f5;

		color: #8692a9;

	}



	.nav-tabs>.nav-item>.nav-link.active {

		background-color: #1f68af !important;

		border-radius: 30px;

		color: #FFFFFF;

	}



	.nav-tabs>.nav-item>.nav-link i.now-ui-icons {

		font-size: 14px;

		position: relative;

		top: 1px;

		margin-right: 3px;

	}



	.nav-tabs.nav-tabs-neutral>.nav-item>.nav-link {

		color: #FFFFFF;

	}



	.nav-tabs.nav-tabs-neutral>.nav-item>.nav-link.active {

		background-color: rgba(255, 255, 255, 0.2);

		color: #FFFFFF;

	}



	.card-Tab {

		border: 0;

		border-radius: 0.1875rem;

		display: inline-block;

		position: relative;

		width: 100%;

		margin-bottom: 30px;



	}



	.card .card-header {

		background-color: transparent;

		border-bottom: 0;

		background-color: transparent;

		border-radius: 12px;

		padding: 0;

	}



	.card-Tab[data-background-color="orange"] {

		background-color: #f96332;

	}



	.card-Tab[data-background-color="red"] {

		background-color: #FF3636;

	}



	.card-Tab[data-background-color="yellow"] {

		background-color: #FFB236;

	}



	.card-Tab[data-background-color="blue"] {

		background-color: #2CA8FF;

	}



	.card-Tab[data-background-color="green"] {

		background-color: #15b60d;

	}



	[data-background-color="orange"] {

		background-color: #e95e38;

	}



	[data-background-color="black"] {

		background-color: #2c2c2c;

	}



	[data-background-color]:not([data-background-color="gray"]) {

		color: #FFFFFF;

	}



	[data-background-color]:not([data-background-color="gray"]) p {

		color: #FFFFFF;

	}



	[data-background-color]:not([data-background-color="gray"]) a:not(.btn):not(.dropdown-item) {

		color: #FFFFFF;

	}



	[data-background-color]:not([data-background-color="gray"]) .nav-tabs>.nav-item>.nav-link i.now-ui-icons {

		color: #FFFFFF;

	}





	/* @font-face {

		font-family: 'Nucleo Outline';

		src: url("https://github.com/creativetimofficial/now-ui-kit/blob/master/assets/fonts/nucleo-outline.eot");

		src: url("https://github.com/creativetimofficial/now-ui-kit/blob/master/assets/fonts/nucleo-outline.eot") format("embedded-opentype");

		src: url("https://raw.githack.com/creativetimofficial/now-ui-kit/master/assets/fonts/nucleo-outline.woff2");

		font-weight: normal;

		font-style: normal;



	} */



	.now-ui-icons {

		display: inline-block;

		font: normal normal normal 14px/1 'Nucleo Outline';

		font-size: inherit;

		speak: none;

		text-transform: none;

		/* Better Font Rendering */

		-webkit-font-smoothing: antialiased;

		-moz-osx-font-smoothing: grayscale;

	}









	@media screen and (max-width: 768px) {



		.nav-tabs {



			text-align: center;

		}



		.nav-tabs .nav-item>.nav-link {

			margin-bottom: 5px;

		}

	}



	.card-Tab-header {

		background: #fff;

		border-bottom: 2px solid #f0f0f5;

		border-radius: 7px 7px 0 0;

	}



	.card-result {

		position: relative;

		display: -ms-flexbox;

		display: flex;

		-ms-flex-direction: column;

		flex-direction: column;

		min-width: 0;

		word-wrap: break-word;

		border: inherit !important;

		border-radius: 0;

		background: #fff;

	}



	h1.page-title {

		color: #555555;

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



	input,

	select {

		border: 1px solid #CCC;

	}



	button {

		color: #FFF;

		background-color: #428BCA;

		border: 1px solid #357EBD;

	}



	.mdtext {

		font-weight: 600;

	}



	.input101 {

		width: 100%;

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

<?php





















if (isset($_POST['winners']) || isset($_POST['resuld_uplaod'])) {



	$userid = $_SESSION['memid'];

	echo $userid;



	$msg = "";



	if (isset($_FILES['photos'])) {





		$allowed = array('jpg', 'jpeg', 'tif', 'tiff', 'png');



		$file_name = $_FILES['photos']['name'];

		$file_type = $_FILES['photos']['type'];

		$file_size = $_FILES['photos']['size'];

		$file_tem_loc = $_FILES['photos']['tmp_name'];



		$ext = pathinfo($file_name, PATHINFO_EXTENSION);

		var_dump($ext);

		$name = "";



		if ("jpg" == $ext) {

			$name .= str_replace(".jpg", "", $file_name) . $userid . date("Ymdhms");
		} else if ("jpeg" == $ext) {

			$name .= str_replace(".jpeg", "", $file_name) . $userid . date("Ymdhms");
		} else if ("tif" == $ext) {

			$name .= str_replace(".tif", "", $file_name) . $userid . date("Ymdhms");
		} else if ("tiff" == $ext) {

			$name .= str_replace(".tiff", "", $file_name) . $userid . date("Ymdhms");
		} else if ("png" == $ext) {

			$name .= str_replace(".png", "", $file_name) . $userid . date("Ymdhms");
		}



		$file_store = "";

		$path = "";



		if (in_array($ext, $allowed)) {

			mkdir("pages/upload/images/" . $userid, 0755);

			mkdir("pages/upload/images/" . $userid . "/img", 0755);



			$path = "pages/upload/images/" . $userid . "/img";



			$file_store = $path . '/' . $name . '.' . $ext;
		}





		$fileNEW = $path . '/' . $name . '.' . $ext;



		// var_dump("stop");die;



		if (move_uploaded_file($file_tem_loc, $file_store)) {

			$formid = $_POST['result_up'];



			$winner_upadte = select_query($con, "winner_past_lsit", "", "`deletes`='0'  AND  `id`= $formid", "", "");

			if ($winner_upadte['nr'] > 0) {



				$winner_upadte = array("draw_id" => $_POST['draw_new_id'], "image_url" =>  $fileNEW, "name" =>  $_POST['customername'], "country" => $_POST['Country'], "my3_numbers" => $_POST['My3number'], "matched_order" => $_POST['Matched'], "participated_categeroy" => $_POST['Participated'], "won_prize" => $_POST['Prize']);





				$aticket = update($con, "winner_past_lsit", "`id`= $formid", $winner_upadte, "", "", "");
			} else {



				$winnerresult = array("draw_id" => $_POST['draw_new_id'], "image_url" =>  $fileNEW, "name" =>  $_POST['customername'], "country" => $_POST['Country'], "my3_numbers" => $_POST['My3number'], "matched_order" => $_POST['Matched'], "participated_categeroy" => $_POST['Participated'], "won_prize" => $_POST['Prize']);

				$aticket = insert($con, "winner_past_lsit", "", $winnerresult, "", "", "");
			}





			divert($adminurl . "resultuplaod/list/" . 'success');
		} else {

			divert($adminurl . "resultuplaod/list/" . 'failed');
		}
	}
}











































?>











<html>







<body>


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

					<h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Image Uplaod</h1>

					<div>

						<ol class="breadcrumb">

							<li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

							<li class="breadcrumb-item active" aria-current="page">Image Upload</li>

						</ol>

					</div>

				</div>

				<div class="row">

					<div class="col-md-12 m-auto">



						<div class="card-Tab">



							<div class="card-Tab-body">

								<!-- Tab panes -->

								<?php

								if ($subid3 == 'success') {

								?>

									<div class="alert alert-success" role="alert">

										Submited successfully

									</div>

								<?php

								}

								?>

								<?php

								if ($subid3 == 'failed') {

								?>

									<div class="alert alert-danger" role="alert">

										FAILED!

									</div>

								<?php

								}

								?>

								<div id="home">

									<div class="card-result">

										<div class="card-body">

											<form action="" method="post" enctype="multipart/form-data">



												<fieldset>

													<div class="row">

														<div class="row">

															<div class="col-6 mb-3">

																<span>Select Draw</span>&nbsp;<span style="color: red;">*</span>

																<select id="draw_new_id" name="draw_new_id" class="form-select">

																	<option value="">Select Draw</option>

																	<?php

																	$draw = select_query($con, "draw", "", "`status` != 'Pending' and `deletes`='0' ", "", "");

																	if ($draw['nr'] > 0) {

																		foreach ($draw['result'] as $key => $value) {

																	?>

																			<option value="<?= $value['id']; ?>"><?= $value['name']; ?></option>

																	<?php



																		}
																	}

																	?>

																</select>



															</div>



															<div class="col-6 mb-3">

																<span>Image:</span>&nbsp;<span style="color: red;">* (Uplaod size : 1020 × 680 px)</span>





																<input type="file" class="form-control" name="photos" required>



															</div>

														</div>





														<div class="row">

															<div class="col-6 mb-3">

																<span>Name:</span>&nbsp;<span style="color: red;">*</span>



																<input type="text" class="form-control" name="customername" required>







															</div>



															<div class="col-6 mb-3">

																<span>Country:</span>&nbsp;<span style="color: red;">*</span>





																<input type="text" class="form-control" name="Country" required>







															</div>

														</div>





														<div class="row">

															<div class="col-6 mb-3">

																<span>My3 Numbers:</span>&nbsp;<span style="color: red;">*</span>



																<input type="text" class="form-control" name="My3number" required>











															</div>



															<div class="col-6 mb-3">

																<span>Matched Order:</span>&nbsp;<span style="color: red;">*</span>

																<select id="Matched" name="Matched" class="form-select" required>

																	<option value="">Select Prize</option>

																	<option value="1">1<span>st</span> Prize</option>

																	<option value="2">2<span>st</span> Prize</option>

																	<option value="3">3<span>st</span> Prize</option>





																</select>









															</div>

														</div>





														<div class="row">

															<div class="col-6 mb-3">

																<span>Participated Category:</span>&nbsp;<span style="color: red;">*</span>

																<select id="Participated" name="Participated" class="form-select">

																	<option value="">Select Category</option>

																	<?php

																	$productlist = select_query($con, "product", "", "`deletes`='0' ", "", "");

																	if ($productlist['nr'] > 0) {

																		foreach ($productlist['result'] as $key => $value) {

																	?>

																			<option value="<?= $value['id']; ?>"><?= $value['rate']; ?></option>

																	<?php



																		}
																	}

																	?>

																</select>











															</div>



															<div class="col-6 mb-3">

																<span> Prize:</span>&nbsp;<span style="color: red;">*</span>

																<input type="text" class="form-control" name="Prize" required>







															</div>

														</div>















														<div class="row">

															<div class="col-12 mb-3">

																<input type="submit" class="btn btn-info" name="winners" style="width:20% ;" value="Submit">

															</div>

														</div>

























													</div>





												</fieldset>



											</form>

										</div>

									</div>

								</div>



							</div>

						</div>



					</div>

				</div>

			</div>

			</di v>

			<div class="row row-sm">

				<div class="col-lg-12">

					<div class="card-result">

						<div class="card-header">

							<div class="col-lg-4">

								<h3 class="card-title"><strong>Result upload</strong></h3>

							</div>





							<div class="col-lg-8">

								<input type="date" name="formdate" id="formdate" value="<?php echo date("Y-m-d"); ?>">

								<button onclick="viewtable()">GO</button>

							</div>





						</div>



						<div class="card-body">

							<div class="table-responsive">

								<table class="table table-bordered text-nowrap border-bottom" id="resultupload" style="width:100%;">

									<thead>

										<tr>

											<th class="wd-15p border-bottom-0">Date&Time</th>

											<th class="wd-15p border-bottom-0">Select Draw</th>

											<th class="wd-15p border-bottom-0">Image</th>

											<th class="wd-15p border-bottom-0">Name</th>

											<th class="wd-15p border-bottom-0">Country</th>

											<th class="wd-15p border-bottom-0">My3 Numbers</th>

											<th class="wd-15p border-bottom-0">Matched Order</th>

											<th class="wd-15p border-bottom-0">Participated Category</th>

											<th class="wd-15p border-bottom-0">Prize</th>



											<th class="wd-15p border-bottom-0">Action</th>



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





		<!-- delete modul -->

		<div class="modal fade" id="deleteinfo" tabindex="-1" role="dialog" aria-labelledby="customModalLabel" aria-modal="true">

			<div class="modal-dialog" role="document">

				<div class="modal-content">

					<div class="modal-header">

						<h5 class="modal-title" id="customModalLabel">Delete</h5> <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>

					</div>

					<div class="modal-body">

						<div class="text-center" id="dele">





						</div>

					</div>

					<div class="modal-footer custom">



						<div class="right-side">



							<button aria-label="Close" class="btn btn-danger pd-x-25 success" onclick="refersh()" data-bs-dismiss="modal">Close</button>





						</div>

					</div>

				</div>

			</div>

		</div>









		<!-- edit modul -->





		<div class="modal fade" id="result_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

			<div class="modal-dialog">

				<div class="modal-content">

					<div class="modal-header">

						<h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>

						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

					</div>

					<div class="modal-body">

						<div class="row">

							<div class="col-md-10 m-auto">



								<div class="card-Tab">



									<div class="card-Tab-body">



										<div id="home">

											<div class="card-result">

												<div class="card-body">

													<div id="resuld"></div>

													<!-- <form action="" method="post" enctype="multipart/form-data">

														<fieldset>

															<div class="row">

																<div class="row">

																	<div class="col-6 mb-3">

																		<span>Select Draw</span>&nbsp;<span style="color: red;">*</span>

																		<select id="draw_new_id" name="draw_new_id" class="form-select">

																			<option value="">Select Draw</option>

																			<?php

																			$draw = select_query($con, "draw", "", "`status` != 'Pending' and `deletes`='0' ", "", "");

																			if ($draw['nr'] > 0) {

																				foreach ($draw['result'] as $key => $value) {

																			?>

																					<option value="<?= $value['id']; ?>"><?= $value['name']; ?></option>

																			<?php



																				}
																			}

																			?>

																		</select>



																	</div>



																	<div class="col-6 mb-3">

																		<span>Image:</span>&nbsp;<span style="color: red;">* (Uplaod size : 1020 × 680 px)</span>





																		<input type="file" class="form-control" name="photos" required>



																	</div>

																</div>





																<div class="row">

																	<div class="col-6 mb-3">

																		<span>Name:</span>&nbsp;<span style="color: red;">*</span>



																		<input type="text" class="form-control" name="customername" required>







																	</div>



																	<div class="col-6 mb-3">

																		<span>Country:</span>&nbsp;<span style="color: red;">*</span>





																		<input type="text" class="form-control" name="Country" required>







																	</div>

																</div>





																<div class="row">

																	<div class="col-6 mb-3">

																		<span>My3 Numbers:</span>&nbsp;<span style="color: red;">*</span>



																		<input type="text" class="form-control" name="My3number" required>











																	</div>



																	<div class="col-6 mb-3">

																		<span>Matched Order:</span>&nbsp;<span style="color: red;">*</span>

																		<select id="Matched" name="Matched" class="form-select" required>

																			<option value="">Select Prize</option>

																			<option value="1">1<span>st</span> Prize</option>

																			<option value="2">2<span>st</span> Prize</option>

																			<option value="3">3<span>st</span> Prize</option>





																		</select>









																	</div>

																</div>





																<div class="row">

																	<div class="col-6 mb-3">

																		<span>Participated Category:</span>&nbsp;<span style="color: red;">*</span>

																		<select id="Participated" name="Participated" class="form-select">

																			<option value="">Select Category</option>

																			<?php

																			$productlist = select_query($con, "product", "", "`deletes`='0' ", "", "");

																			if ($productlist['nr'] > 0) {

																				foreach ($productlist['result'] as $key => $value) {

																			?>

																					<option value="<?= $value['id']; ?>"><?= $value['rate']; ?></option>

																			<?php



																				}
																			}

																			?>

																		</select>











																	</div>



																	<div class="col-6 mb-3">

																		<span> Prize:</span>&nbsp;<span style="color: red;">*</span>

																		<input type="text" class="form-control" name="Prize" required>







																	</div>

																</div>

















































															</div>





														</fieldset>



													</form> -->

												</div>

											</div>

										</div>



									</div>

								</div>



							</div>

						</div>

					</div>



				</div>

			</div>

		</div>









		<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="customModalLabel" aria-modal="true">

			<div class="modal-dialog" role="document">

				<div class="modal-content">

					<div class="modal-header">

						<h5 class="modal-title" id="customModalLabel">Preview</h5> <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>

					</div>

					<div class="modal-body" style="height: 300px">

						<div id="preimg">

							<img src="" id="preimag">

							<!-- <a href="" id="preimag"></a> -->

						</div>

					</div>

					<div class="modal-footer custom">

						<div class="left-side">





						</div>



					</div>

				</div>

			</div>

		</div>







		<script>
			var origin = window.location.origin;

			var ajax_url = origin + "/ajax/service/result_services.php";



			$(function() {

				viewtable();



			});



			function viewtable() {

				var table = $('#resultupload').DataTable();

				table.destroy();

				var formdata = [];

				formdata.push({

					name: 'method',

					value: "winner_list"

				});





				let formdate = $('#formdate').val();





				formdata.push({

					name: 'formdate',

					value: formdate

				});







				var post_data = formdata;

				var onsuccess = function(data) {



					var response = JSON.parse(data);

					if (response != "") {

						if (response.type == 1) {









							$('#resultupload').DataTable({

								order: [

									[0, 'asc']

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

										'data': 'drawid'

									},



									{

										'data': 'url'

									},

									{

										'data': 'name'

									},

									{

										'data': 'country'

									},

									{

										'data': 'my3number'

									},

									{

										'data': 'match'

									},

									{

										'data': 'participated'

									},

									{

										'data': 'prize'

									},



									{

										'data': null,

										render: function(data, type, row) {



											return '<a class="btn text-danger btn-sm" data-bs-target="#result_edit" data-bs-toggle="modal" data-bs-toggle="tooltip"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #002bff !important;" class="fa fa-edit edit_product"  data-id="' + data.id + '" onclick="result_edit(' + data.id + ')"></span></a> <a class="btn text-danger btn-sm"  data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2 fs-14" onclick="deleteinfo(' + data.id + ')"></span></a>'





										}

									}

								],

							});





						} else {

							var table = $('#resultupload').DataTable();

							table.clear().draw();

						}



					}

				}



				do_ajax_call(post_data, onsuccess, ajax_url);



			}











			function result_edit(id) {



				var formdata = [];

				formdata.push({

					name: 'method',

					value: "result_uplaod1"

				});











				formdata.push({

					name: 'id',

					value: id

				});







				var post_data = formdata;

				var onsuccess = function(data) {



					var response = JSON.parse(data);

					if (response != "") {

						if (response.type == 1) {





							document.getElementById('resuld').innerHTML = response.result;





						} else {

							document.getElementById('resuld').innerHTML = response.result;



						}



					}

				}



				do_ajax_call(post_data, onsuccess, ajax_url);



			}











			function deleteinfo(id) {



				var formdata = [];

				formdata.push({

					name: 'method',

					value: "delete_result_now"

				});











				formdata.push({

					name: 'id',

					value: id

				});







				var post_data = formdata;

				var onsuccess = function(data) {



					var response = JSON.parse(data);

					if (response != "") {



						document.getElementById('dele').innerHTML = response.result;

						$('#deleteinfo').modal('show');



					} else {

						document.getElementById('dele').innerHTML = response.result;

						$('#deleteinfo').modal('show');

					}

				}



				do_ajax_call(post_data, onsuccess, ajax_url);



			}





			function refersh() {



				location.reload();

			}







			function preview(url) {

				$('#previewModal').modal('show');

				$("#preimag").attr("src", url);

				$("#preimag").load();

				// document.getElementById('preimg').innerHTML = '<a href="'+url+'"></a>';

			}
		</script>





		<script src='https://code.jquery.com/jquery-3.3.1.slim.min.js'></script>

		<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js'></script>





</body>





</html>