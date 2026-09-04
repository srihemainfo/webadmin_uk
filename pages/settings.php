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

window.onload = function(){

var page_origin = window.location.origin;

let anchor =  document.getElementById("anchor");

anchor.href = page_origin;

}
</script>

<div class="main-content app-content mt-0">

	<div class="side-app">

		

		

		<div class="main-container container-fluid">

			

			<div class="page-header">

				<h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>My Settings</h1>

				<div>

					<ol class="breadcrumb">

						<li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

						<li class="breadcrumb-item active" aria-current="page">My Settings</li>

					</ol>

				</div>

			</div>

			

			

			<div class="row row-sm">

				<div class="col-lg-12">

					<div class="card">

						<div class="card-header">

							<h3 class="card-title">Settings List</h3>

							<div class="col-sm">

								<button type="button" id="smallmodal" data-bs-effect="effect-scale" data-bs-toggle="modal" data-bs-target="#addagent" style="float: right;" class="btn btn-info"><i class="fa fa-cog me-2"></i>Add Settings</button>

							</div>

						</div>

						<div class="card-body">

						     <div class="table-responsive">

							<table class="table table-bordered text-nowrap border-bottom" id="example" >

								<thead>

									<tr>

										

										<th class="wd-15p border-bottom-0">Customer ID</th>

										<th class="wd-15p border-bottom-0">Name</th>

										<th class="wd-20p border-bottom-0">Points</th>

										<th class="wd-15p border-bottom-0">Phone No.</th>

										<th class="wd-10p border-bottom-0">Emirate / Passport ID</th>

										<th class="wd-25p border-bottom-0">E-mail</th>

										<th class="wd-25p border-bottom-0">Action</th>

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

	

	

	

	<div class="modal fade" id="addagent">

	<div class="modal-dialog modal-sm" role="document">

		<div class="modal-content modal-content-demo">

			<div class="modal-header">

				<h6 class="modal-title">Add Customer</h6>

				<button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">×</span>

				</button>

			</div>

			<div class="cls_form_div">

				

				

				

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

								<h3  class="text-center">Mobile phone verification

								</h3> <p  class="text-center">Enter the code we just send on your mobile phone +1 9876543210</p><br></div>

								

								<div class="row">

									<div class="col-md-3">

										<input type="text" class="form-control" id=""  name="">

									</div>

									<div class="col-md-3">

										<input type="text" class="form-control" id=""  name="">

									</div>

									<div class="col-md-3">

										<input type="text" class="form-control" id=""  name="">

									</div>

									<div class="col-md-3">

										<input type="text" class="form-control" id=""  name="">

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

	$(function(){

		viewform();

		viewtable();

		

	});

	

	function viewform(str=0){

		var post_data ={editid:str};

		$("#showsuccessalert","#showerroralert").hide();

		

		var divId = ".cls_form_div";

		var oncontinue = function(event)

		{

		}

		

		viewfile("agentform",post_data,divId,oncontinue);

	} 

	

	function viewtable(){

		var formdata =[];

		

		//data = $('.cls_faretype_form').serialize(); 

		formdata.push({name: 'method', value: "list_agent"});

		var post_data =formdata;

		//alert(post_data);

		

		var onsuccess = function(data){

			var response=JSON.parse(data);

			console.log(response);

			//var data ={response};

			if(response != ""){

				// alert(response);

				$('#example').DataTable({

					// ajax: 'arrays.txt',

					"data":response,

					"columns": [

					{ 'data': 'id' },

					{ 'data': 'name' },

					{ 'data': 't_point' },

					{ 'data': 'mobile' },

					{ 'data': 'passport' },

					{ 'data': 'email' },

					{

						'data': null,

						render: function (data, type, row) {

							return '<a onclick="viewform(1)" style="cursor: pointer;" data-bs-effect="effect-scale" data-bs-toggle="modal" data-bs-target="#addagent"  data-bs-original-title="Edit"><span class="fe fe-edit fs-14"></span></a><a class="btn text-danger btn-sm" data-bs-target="#delete" data-bs-toggle="modal" data-bs-toggle="tooltip" style="cursor: pointer;" data-bs-original-title="Delete"><span class="fe fe-trash-2 fs-14"></span></a>';

						}

					}

					

					],

				});

				

			}

		}

		

		do_ajax_call(post_data,onsuccess);

		

	}

	/*

		function viewtable(){

		var post_data ={};

		var divId = ".cls_table_div";

		var oncontinue = function(event)

		{

		//$('#responsive-datatable').DataTable();

		$('#responsive-datatable').DataTable({

        language: {

		searchPlaceholder: 'Search...',

		scrollX: "100%",

		sSearch: '',

        }

		});

		}

		

		viewfile("agentlist",post_data,divId,oncontinue);

	}*/

	

	function saveform(){

		

		//var is_valid = validateform(".cls_booking_form");

		//if(is_valid){

		// alert(is_valid);

		var formdata = $('.cls_booking_form').serializeArray();

		//data = $('.cls_faretype_form').serialize(); 

		formdata.push({name: 'method', value: "add_agent"});

		var post_data =formdata;

		//var post_data =data;

		alert(post_data);

		

		var onsuccess = function(data){

			var response=JSON.parse(data);

			if(response != ""){

				//alert(response.result);

				

				if(response.type==0){

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

		

		do_ajax_call(post_data,onsuccess);

		//	}

	}

</script>