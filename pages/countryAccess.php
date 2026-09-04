<style>

    .switch {
      position: relative;
      display: inline-block;
      width: 40px;
      height: 20px;
    }
    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0; left: 0;
      right: 0; bottom: 0;
      background-color: #ccc;
      transition: 0.4s;
      border-radius: 20px;
    }
    .slider:before {
      position: absolute;
      content: "";
      height: 14px; width: 14px;
      left: 3px; bottom: 3px;
      background-color: white;
      transition: 0.4s;
      border-radius: 50%;
    }
    input:checked + .slider {
      background-color: #28a745;
    }
    input:checked + .slider:before {
      transform: translateX(20px);
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

				<h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Country Access</h1>

				<div>

					<ol class="breadcrumb">

						<li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

						<li class="breadcrumb-item active" aria-current="page">Country Access</li>

					</ol>

				</div>

			</div>

			

			

			<div class="row row-sm">

				<div class="col-lg-12">

					<div class="card">

						<div class="card-header">

							<h3 class="card-title">Settings List</h3>

							<div class="col-sm">

								<!--<button type="button" id="smallmodal" data-bs-effect="effect-scale" data-bs-toggle="modal" data-bs-target="#addagent" style="float: right;" class="btn btn-info"><i class="fa fa-cog me-2"></i>Add Settings</button>-->

							</div>

						</div>

						<div class="card-body">

						     <div class="table-responsive">

							<table class="table table-bordered text-nowrap border-bottom" id="example" >

								<thead>

									<tr>

										

										<th class="wd-15p border-bottom-0">S.No</th>

										<th class="wd-15p border-bottom-0">Country Name</th>

										<th class="wd-20p border-bottom-0">Dail Code</th>

										<th class="wd-15p border-bottom-0">Country Code</th>

										<th class="wd-25p border-bottom-0">Currency</th>

										<th class="wd-10p border-bottom-0">Currency Symbol</th>

										<th class="wd-10p border-bottom-0">Time Zone</th>
										
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

<script>

    var ajax_url = origin + "/ajax/service/gorideServices.php";

	$(function(){

// 		viewform();

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

		formdata.push({name: 'method', value: "country_access"});

		var post_data =formdata;

		//alert(post_data);

		

		var onsuccess = function(data) {
            var response = JSON.parse(data);
        
            if (response != "") {
                // Destroy existing DataTable instance first if it exists
                if ($.fn.DataTable.isDataTable('#example')) {
                    $('#example').DataTable().clear().destroy();
                }
        
                // Now initialize it cleanly
                $('#example').DataTable({
                    data: response,
                    columns: [
                        { data: 'id' },
                        { data: 'name' },
                        { data: 'dailcode' },
                        { data: 'country_code' },
                        { data: 'currency' },
                        { data: 'currency_symbol' },
                        { data: 'time_zone' },
                        {
                            data: 'active_status',
                            render: function (data, type, row) {
                                const checked = data == "1" ? 'checked' : '';
                                return `
                                    <label class="switch">
                                        <input type="checkbox" class="toggle-status" data-id="${row.id}" ${checked}>
                                        <span class="slider round"></span>
                                    </label>
                                `;
                            }
                        }
                    ]
                });
            }
        }


		

		do_ajax_call(post_data,onsuccess,ajax_url);

		

	}

	
	$(document).on('change', '.toggle-status', function () {
        const checkbox = $(this); // Save reference to the checkbox
        const id = checkbox.data('id');
        const status = checkbox.is(':checked') ? 1 : 0;
        
        $.ajax({
            url: ajax_url, // Replace with your actual route
            method: 'POST',
            data: {
                c_id: id,
                c_status: status,
                method: 'update_access'
            },
            beforeSend: function () {
                checkbox.prop('disabled', true);
            },
            complete: function () {
                checkbox.prop('disabled', false);
            },
            success: function (response) {
                var response = JSON.parse(response);

                if (response != "") {
                    if (response.type == 1) {
                        paymentSuccess('success', response.result);
                    } else {
                        toast('error', response.result);
                    }
                }
                viewtable()
            },
            error: function (xhr) {
                let message = 'An error occurred';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    message = xhr.responseText;
                }
                toast('error', message);
                viewtable()
            }

        });
    });
    
    function paymentSuccess(icon, titlestr) {

        Swal.fire({

            title: titlestr,

            icon: icon,

            confirmButtonColor: '#3085d6',

            confirmButtonText: 'OKAY',

            allowOutsideClick: false

        }).then((result) => {

            if (result.isConfirmed) {

                location.reload();

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