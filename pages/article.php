<!--
1. modifications unknown


Date      Developer_name      Modifications


-->

<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>



<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>



<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

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

				<h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>ARTICLE</h1>

				<div>

					<ol class="breadcrumb">

						<li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

						<li class="breadcrumb-item active" aria-current="page">ARTICLE</li>

					</ol>

				</div>

			</div>

			

			

			<div class="row row-sm">

				<div class="col-lg-12">

					<div class="card">

						<div class="card-header">

							<h3 class="card-title">Edit Article</h3>

						</div>

						<div class="card-body">

							<div class="table-responsive">

								<table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">

									<thead>

										<tr>

											<th class="wd-15p border-bottom-0">Title</th>

											<th class="wd-15p border-bottom-0">Order</th>

											<th class="wd-15p border-bottom-0">Status</th>

											<th class="wd-20p border-bottom-0">Actions</th>

											

											

										</tr>

									</thead>

									<tbody>

										

										<tr>

											<td>About Us</td>

											<td>0</td>

											<td>Active</td>

											<td>

												<div class="g-2">

													<a data-bs-toggle="modal" data-bs-target="#fullscreenmodal" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="Edit"><span class="fe fe-edit fs-14"></span></a>

													<a class="btn text-danger btn-sm" data-bs-target="#modaldemo5" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-original-title="Delete"><span class="fe fe-trash-2 fs-14"></span></a>

												</div>

												

											</td>

										</tr>

										

										<tr>

											<td>Home</td>

											<td>0</td>

											<td>Active</td>

											<td>

												<div class="g-2">

													<a data-bs-toggle="modal" data-bs-target="#fullscreenmodal" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="Edit"><span class="fe fe-edit fs-14"></span></a>

													<a class="btn text-danger btn-sm" data-bs-target="#modaldemo5" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-original-title="Delete"><span class="fe fe-trash-2 fs-14"></span></a>

												</div>

												

											</td>

										</tr>

										

										<tr>

											<td>Contact Us</td>

											<td>0</td>

											<td>Active</td>

											<td>

												<div class="g-2">

													<a data-bs-toggle="modal" data-bs-target="#fullscreenmodal" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="Edit"><span class="fe fe-edit fs-14"></span></a>

													<a class="btn text-danger btn-sm" data-bs-target="#modaldemo5" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-original-title="Delete"><span class="fe fe-trash-2 fs-14"></span></a>

												</div>

												

											</td>

										</tr>

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



<div class="modal fade" id="fullscreenmodal" tabindex="-1" role="dialog">

	<div class="modal-dialog modal-lg" role="document">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title">Edit Artica</h5>

				<button class="btn-close me-1" data-bs-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">×</span>

				</button>

			</div>

			<div class="modal-body">

			<div class="form-label">Parent Menu</div>

				<div class="col-sm-6">

					<div class="form-group">

					

						<select name="country" class="form-control form-select" data-bs-placeholder="Select Page">

							<option value="br">Faq's</option>

							<option value="cz">Contact Us</option>

							<option value="de">About Us</option>

							<option value="pl" selected="">Home</option>

							

						</select>

					</div>

					

				</div>

				

				

				<div class="row">

					<div class="form-label">Menu Link</div>

					<div class="col-sm-3">

						<div class="form-group">

							

							<label class="custom-switch form-switch">

								<input type="radio" name="custom-switch-radio" class="custom-switch-input">

								<span class="custom-switch-indicator"></span>

								<span class="custom-switch-description">Internal</span>

							</label>

						</div>

					</div>

					<div class="col-sm-3">

						<div class="form-group">

							<label class="custom-switch form-switch ">

								<input type="radio" name="custom-switch-radio" class="custom-switch-input" checked="">

								<span class="custom-switch-indicator"></span>

								<span class="custom-switch-description">External</span>

							</label>

						</div>

					</div>

				</div>

				

				<div class="row">

					<div class="form-label">Page Open Type</div>

					<div class="col-sm-3">

						<div class="form-group">

							

							<label class="custom-switch form-switch">

								<input type="radio" name="custom-switch-radio1" class="custom-switch-input">

								<span class="custom-switch-indicator"></span>

								<span class="custom-switch-description">Same Window</span>

							</label>

						</div>

					</div>

					<div class="col-sm-3">

						<div class="form-group">

							<label class="custom-switch form-switch ">

								<input type="radio" name="custom-switch-radio1" class="custom-switch-input" checked="">

								<span class="custom-switch-indicator"></span>

								<span class="custom-switch-description">New Window</span>

							</label>

						</div>

					</div>

				</div>

				<div class="col-sm-6">

				<div class="form-group">

					<label class="form-label">Title</label>

				<input class="form-control  mb-4" placeholder="Title" type="text">

				</div>

				</div>

				<div class="col-sm-6">

				<div class="form-group">

				<label class="form-label">Meta Title</label>

				<input class="form-control  mb-4" placeholder="Meta Title" type="text">

				</div>

				</div>

				<div class="col-sm-6">

				<div class="form-group">

				<label class="form-label">Meta Keyword</label>

				<input class="form-control  mb-4" placeholder="Meta Keyword" type="text">

				</div>

				</div>

				<div class="col-sm-6">

				<div class="form-group">

				<label class="form-label">Meta Description</label>

				<input class="form-control  mb-4" placeholder="Meta Description" type="text">

				</div>

				</div>

				<div class="col-sm-6">

				<div class="form-group">

				<label class="form-label">Url </label>

				<input class="form-control  mb-4" placeholder="Url " type="text">

				</div>

				</div>

				

				<div class="row">

				<div class="form-label">Menu List Area</div>

				<div class="col-sm-4">

				<div class="form-group">

				

				<label class="custom-control custom-checkbox-md">

				

				<input type="checkbox" class="custom-control-input" name="example-checkbox5" value="option5" checked="">

				<span class="custom-control-label" style="position: unset;">Quick links</span>

				</label>

				</div>

				</div>

				<div class="col-sm-4">

				<div class="form-group">

				<label class="custom-control custom-checkbox-md">

				<input type="checkbox" class="custom-control-input" name="example-checkbox5" value="option5" checked="">

				<span class="custom-control-label" style="position: unset;">Company</span>

				</label>

				</div>

				</div>

				</div>

				<div class="col-sm-6">

				<div class="form-group">

				<label class="form-label">Menu Order</label>

				<input class="form-control  mb-4" placeholder="Menu Order" type="text">

				</div></div>

				<div class="col-sm-6">

				<div class="form-group">

				<label class="form-label mt-0">Image</label>

				<input class="form-control" type="file">

				</div>

				</div>

				

				<div class="form-group">

				

				

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

                <div class="modal-footer">

				<button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

				<button class="btn btn-primary">Save changes</button>

                </div>

				</div>

				</div>

				</div>

				

				<div class="modal fade" id="modaldemo5">

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