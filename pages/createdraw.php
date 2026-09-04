<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$now = date("Y-m-d h:i:s");

// OLD
$draw = select_query($con, "draw", "", "`status` = 'Active' AND `result_datetime` > '$now' AND `deletes` = '0' ORDER BY `result_datetime` ASC LIMIT 1", "", "");


$resultDate = date_format(date_create($draw['result'][0]['result_datetime']), "Y-m-d h:i:s a");



?>




<style>
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







				<h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Just3 Ball Draw Winner Announcement</h1>







				<div>







					<ol class="breadcrumb">







						<li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>







						<li class="breadcrumb-item active" aria-current="page">Create Draw</li>







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



					<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center mb-5">



						<button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#winningno">Winner Announcement</button>



					</div>



				<?php } ?>



			</div>











			<div class="row row-sm">



				<div class="col-lg-12">



					<div class="card">



						<div class="card-header">



							<h3 class="card-title">Draw List</h3>



							<div class="col-sm">





							</div>



						</div>



						<div class="card-body">

							<div class="table-responsive">
								<table class="table table-bordered text-nowrap border-bottom" id="drawtable" style="width:100%;">



									<thead>



										<tr>

											<th class="wd-15p border-bottom-0">Draw No</th>

											<th class="wd-15p border-bottom-0">Draw Name</th>



											<th class="wd-20p border-bottom-0">Ticket Selling Start Date</th>



											<th class="wd-15p border-bottom-0">Ticket Selling End Date</th>



											<th class="wd-10p border-bottom-0">Draw Date & Time</th>



											<th class="wd-10p border-bottom-0">Status</th>



											<th class="wd-10p border-bottom-0">Action</th>



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











<div class="modal fade" id="addagent">



	<div class="modal-dialog modal-lg" role="document">



		<div class="modal-content modal-content-demo">



			<div class="modal-header">



				<h6 class="modal-title">Add Draw</h6>



				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">



					<span aria-hidden="true">×</span>



				</button>



			</div>



			<div class="modal-body">



				<div id="drawerror"></div>



				<div class="col-md-12">



					<div class="card">



						<div class="card-header">



							<h4 class="card-title">Create Draw</h4>



						</div>







						<div class="card-body">



							<form id="drawform">



								<div class="form-group">



									<label for="exampleInputEmail1" class="form-label">Enter Draw Name</label>



									<input type="text" name="drawname" class="form-control" placeholder="Enter Draw Name">



								</div>



								<div class="row">



									<div class="form-group col-sm-6">



										<label for="exampleInputEmail1" class="form-label"> Ticket Selling Start Date</label>



										<input name="TicketStartDate" type="date" class="form-control">



									</div>



									<div class="form-group col-sm-6">



										<label for="exampleInputEmail1" class="form-label"> Ticket Selling End Date</label>



										<input name="TicketEndDate" type="datetime-local" class="form-control">



									</div>



								</div>



								<div class="row">



									<div class="form-group col-sm-6">



										<label for="exampleInputEmail1" class="form-label">Draw Date & Time</label>



										<input name="DrawDate" type="datetime-local" class="form-control">



									</div>



								</div>



								<div class="row">



									<div class="form-group col-sm-12">



										<div id="summernote"></div>



										<script>
											$('#summernote').summernote({



												placeholder: 'Events details',



												tabsize: 2,



												height: 100



											});
										</script>



									</div>



								</div>



								<button type="button" class="btn btn-primary mt-4 mb-0" onclick="addDraw()">Create Draw</button>



							</form>



						</div>







					</div>



				</div>



			</div>



		</div>



	</div>



</div>







<div class="modal fade" id="winningno">



	<div class="modal-dialog" role="document">



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



								<h3 class="text-center">Enter Winning Number



								</h3> <br>



							</div>



							<div id="errmessage"></div>



							<div class="row ">



								<div class="col-lg-2"></div>

								<!-- <input type="text" maxlength="3" id="drawlucky" name="drawlucky" class="form-control bg-info-gradient" value="" name style="font-size: 150px;font-weight: 900;width: 300px; text-align: center;padding-bottom: 38px;height: 150px;color: white;text-shadow: 0px 1px 1px #919191, 1px 2px 1px #919191, 1px 3px 1px #919191, 1px 4px 1px #919191, 1px 5px 1px #919191, 1px 6px 1px #919191, 1px 7px 1px #919191, 1px 8px 1px #919191, 1px 9px 1px #919191, 1px 10px 1px #919191, 1px 18px 6px rgb(16 16 16 / 40%), 1px 22px 10px rgb(16 16 16 / 20%), 1px 25px 35px rgb(16 16 16 / 20%), 1px 30px 60px rgb(255 255 255 / 40%);"> -->



								<input type="text" maxlength="3" id="drawlucky" oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="drawlucky" class="form-control bg-info-gradient" value="" name style="font-size: 150px;font-weight: 900;width: 400px; margin:auto; text-align: center;padding-bottom: 38px;color: white;text-shadow: 0px 1px 1px #919191, 1px 2px 1px #919191, 1px 3px 1px #919191, 1px 4px 1px #919191, 1px 5px 1px #919191, 1px 6px 1px #919191, 1px 7px 1px #919191, 1px 8px 1px #919191, 1px 9px 1px #919191, 1px 10px 1px #919191, 1px 18px 6px rgb(16 16 16 / 40%), 1px 22px 10px rgb(16 16 16 / 20%), 1px 25px 35px rgb(16 16 16 / 20%), 1px 30px 60px rgb(255 255 255 / 40%);">



							</div>







						</div>







					</form>



					<br>



				</div>



			</div>



			<div class="modal-footer">



				<button class="btn btn-primary" onclick="winner()" type="button">Submit</button>









			</div>



		</div>



	</div>



</div>















<div class="modal fade" id="activenow">



	<div class="modal-dialog" role="document">



		<div class="modal-content modal-content-demo">



			<div class="modal-header">



				<h6 class="modal-title">Active Now</h6>



				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">



					<span aria-hidden="true">×</span>



				</button>



			</div>



			<div class="modal-body">



				<div class="container">



					<form id="winnerdata">



						<input type="hidden" name="activedid" id="activedid">



						<div class="form-group">



							<div class="model-text">



								<h3 class="text-center">Are you sure you want to Activate?



								</h3> <br>



							</div>



							<div id="ActiveMessage"></div>











						</div>







					</form>



					<br>



				</div>



			</div>



			<div class="modal-footer">



				<div id="acbtn">



					<button class="btn btn-primary" onclick="active_now()" type="button">Submit</button>



				</div>



			</div>



		</div>



	</div>



</div>































<div class="modal fade" id="deletenow">



	<div class="modal-dialog" role="document">



		<div class="modal-content modal-content-demo">



			<div class="modal-header">



				<h6 class="modal-title">Delete Now</h6>



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



								<h3 class="text-center">Are you sure you want to Delete?



								</h3> <br>



							</div>



							<div id="deleteMessage"></div>











						</div>







					</form>



					<br>



				</div>



			</div>



			<div class="modal-footer">



				<div id="deletebtn">



					<button class="btn btn-primary" onclick="delete_now()" type="button">Submit</button>



				</div>



			</div>



		</div>



	</div>



</div>







<div class="modal fade" id="otp">



	<div class="modal-dialog modal-sm" role="document">



		<div class="modal-content modal-content-demo">



			<div class="modal-header">



				<h6 class="modal-title">OTP</h6>



				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">



					<span aria-hidden="true">×</span>



				</button>



			</div>



			<div class="modal-body">



				<div class="container">



					<form action="#">



						<div class="form-group">



							<div class="model-text">



								<h3 class="text-center">Mobile phone verification



								</h3>



								<p class="text-center">Enter the code we just send on your mobile phone +1 9876543210</p><br>



							</div>







							<div class="row">



								<div class="col-md-3">



									<input type="text" class="form-control" id="" name="">



								</div>



								<div class="col-md-3">



									<input type="text" class="form-control" id="" name="">



								</div>



								<div class="col-md-3">



									<input type="text" class="form-control" id="" name="">



								</div>



								<div class="col-md-3">



									<input type="text" class="form-control" id="" name="">



								</div>



							</div>







						</div>







					</form>



					<br>



				</div>



			</div>



			<div class="modal-footer">



				<button class="btn ripple btn-success" type="button">Submit</button>



				<button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button>



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



				<button aria-label="Close" class="btn btn-primary pd-x-25" data-bs-dismiss="modal">Yes</button>



				<button aria-label="Close" class="btn btn-danger pd-x-25" data-bs-dismiss="modal">No</button>



			</div>



		</div>



	</div>



</div>







<script>
	$(function() {



		viewtable();



	});







	function viewform(str = 0) {



		var post_data = {



			editid: str



		};



		$("#showsuccessalert", "#showerroralert").hide();







		var divId = ".cls_form_div";



		var oncontinue = function(event) {}







		viewfile("staffform", post_data, divId, oncontinue);



	}







	function viewtable() {



		var formdata = [];



		formdata.push({



			name: 'method',



			value: "list_draw"



		});



		var post_data = formdata;



		var onsuccess = function(data) {



			var response = JSON.parse(data);







			if (response != "") {



				$('#drawtable').DataTable({



					order: [



						[0, 'desc']



					],



					data: response,



					columns: [{



							'data': 'drawno'



						},

						{



							'data': 'name'



						},



						{



							'data': 'ticket_start_datetime'



						},



						{



							'data': 'ticket_end_datetime'



						},



						{



							'data': 'result_datetime'



						},



						{



							'data': 'status'



						},



						{



							'data': 'output'



						}



					],



				});







			}



		}







		do_ajax_call(post_data, onsuccess);







	}







	function saveform() {







		var formdata = $('.cls_booking_form').serializeArray();



		//data = $('.cls_faretype_form').serialize(); 



		formdata.push({



			name: 'method',



			value: "add_agent"



		});



		var post_data = formdata;



		//var post_data =data;



		alert(post_data);







		var onsuccess = function(data) {



			var response = JSON.parse(data);



			if (response != "") {



				//alert(response.result);







				if (response.type == 0) {



					$("#showsuccessalert").hide();



					$("#showerroralert").html(response.result);







				} else {



					$("#showerroralert").hide();



					$("#showsuccessalert").html(response.result);



				}



				//viewform();



				//viewtable();



			}



		}







		do_ajax_call(post_data, onsuccess);



		//	}



	}







	function addDraw() {



		var formdata = $('#drawform').serializeArray();



		// var messageData = $('#summernote').summernote('code');



		formdata.push({



			name: 'method',



			value: "add_draw"



		});



		// formdata.push({



		// 	name: 'message',



		// 	value: messageData



		// });











		var post_data = formdata;







		var onsuccess = function(data) {



			var response = JSON.parse(data);



			if (response != "") {



				if (response.type == 1) {







					// var table = $('#drawtable').DataTable();



					var table = $('#drawtable').DataTable();



					table.destroy();



					viewtable();



					// table.row.add({



					// 	"name": response.name,



					// 	"ticket_start_datetime": response.ticket_start_datetime,



					// 	"ticket_end_datetime": response.ticket_end_datetime,



					// 	"result_datetime": response.result_datetime,



					// 	"": '<div class="g-2"><a class="btn text-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addevent" data-bs-original-title="Edit"><span class="fa fa-calendar-check-o fs-14"></span></a><a class="btn text-success btn-sm" data-bs-toggle="modal" data-bs-target="#viewevent" data-bs-original-title="View Event"><span class="fa fa-eye fs-14"></span></a><a class="btn text-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete" data-bs-original-title="Delete"><span class="fe fe-trash-2 fs-14"></span></a></div>'



					// }).draw();



					$('#drawid').val(response.id)



					$('#addagent').modal('hide');



					document.getElementById('drawerror').innerHTML = '<div class="alert alert-success" role="alert">' + response.result + '</div>';



				} else {



					document.getElementById('drawerror').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + '</div>';



				}



			}



		}







		do_ajax_call(post_data, onsuccess);



	}























	function winner() {



		// alert();



		document.getElementById('errmessage').innerHTML = '';



		let drawlucky = $('#drawlucky').val();



		if (drawlucky.length == 3) {







			var formdata = $('#winnerdata').serializeArray();



			formdata.push({



				name: 'method',



				value: "winner_list"



			});



			var post_data = formdata;







			var onsuccess = function(data) {



				var response = JSON.parse(data);



				if (response != "") {



					if (response.type == 1) {



						let origin = window.location.origin;



						let url = origin + '/winners/edit/' + response.id;



						// console.log(url);



						window.location.href = url;



					} else {



						document.getElementById('errmessage').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + 'r</div>';







					}



				}



			}







			do_ajax_call(post_data, onsuccess);



		} else {



			document.getElementById('errmessage').innerHTML = '<div class="alert alert-danger" role="alert">Enter 3 Digit Number</div>';



		}



	}











	function activenow(id) {



		document.getElementById('activedid').value = id;



	}







	function active_now() {



		let id = document.getElementById('activedid').value;



		document.getElementById('acbtn').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';



		// alert(id);



		var formdata = [];



		formdata.push({



			name: 'method',



			value: "activate_now"



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



					document.getElementById('ActiveMessage').innerHTML = '<div class="alert alert-success" role="alert">' + response.result + '</div>';



					document.getElementById('acbtn').innerHTML = '<button class="btn btn-primary" onclick="close_model()" type="button">Close</button>';







					// location.reload();



				} else {



					document.getElementById('ActiveMessage').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + 'r</div>';



					document.getElementById('acbtn').innerHTML = '<button class="btn btn-primary" onclick="active_now()" type="button">Submit</button>';



				}



			}



		}







		do_ajax_call(post_data, onsuccess);



	}







	function close_model() {



		$('#activenow').modal('hide');



		location.reload();



	}



















	function deletedraw(id) {



		document.getElementById('deleteid').value = id;



	}







	function delete_now() {



		let id = document.getElementById('deleteid').value;



		document.getElementById('deletebtn').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';



		// alert(id);



		var formdata = [];



		formdata.push({



			name: 'method',



			value: "delete_draw_now"



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



					document.getElementById('deleteMessage').innerHTML = '<div class="alert alert-success" role="alert">' + response.result + '</div>';



					document.getElementById('deletebtn').innerHTML = '<button class="btn btn-primary" onclick="deletedraw_model()" type="button">Close</button>';







					// location.reload();



				} else {



					document.getElementById('deleteMessage').innerHTML = '<div class="alert alert-danger" role="alert">' + response.result + 'r</div>';



					document.getElementById('deletebtn').innerHTML = '<button class="btn btn-primary" onclick="delete_now()" type="button">Submit</button>';



				}



			}



		}







		do_ajax_call(post_data, onsuccess);



	}







	function deletedraw_model() {



		$('#deletenow').modal('hide');



		location.reload();



	}
</script>