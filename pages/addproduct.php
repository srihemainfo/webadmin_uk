<!--
1.modifications unknown


   Date      Developer_name      Modifications  End Date


-->
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


<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>



<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>



<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<script>

var page_origin = window.location.origin;

let anchor =  document.getElementById("anchor");

anchor.href = page_origin;

</script>


<div class="main-content app-content mt-0">

	<div class="side-app">

		

		<div class="main-container container-fluid">

			

			<div class="page-header">

				<h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Add &amp Edit Products</h1>

				<div>

					<ol class="breadcrumb">

						<li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

						<li class="breadcrumb-item active" aria-current="page">Add &amp Edit Products</li>

					</ol>

				</div>

			</div>

			

			

			<div class="row row-sm">

				<div class="col-lg-12">

					<div class="card">

						<div class="card-header">

							<h3 class="card-title">Edit Products</h3>

							<div class="col-sm">

								<button type="button" id="smallmodal" data-bs-effect="effect-scale" data-bs-toggle="modal" data-bs-target="#addproducts" style="float: right;" class="btn btn-info"><i class="fa fa-user-plus me-2"></i>Add Product</button>

							</div>

						</div>

						<div class="card-body">

							<table class="table table-bordered text-nowrap border-bottom" id="example" >

								<thead>

									<tr>

										

										<th class="wd-15p border-bottom-0">Product ID</th>

										<th class="wd-15p border-bottom-0">Name</th>

										<th class="wd-20p border-bottom-0">Amount</th>

										<th class="wd-15p border-bottom-0">Description</th>

										<th class="wd-25p border-bottom-0">Action</th>

									</tr>

									<tr>

									<td>1</td>

									<td>National Draw Mai Blue bottled water</td>

									<td>AED 10</td>

									<td> 250ml </td>

									<td>Edit Delete</td>

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



<div class="modal fade" id="addproducts" tabindex="-1" role="dialog">

	<div class="modal-dialog modal-lg" role="document">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title">Edit Product</h5>

				<button class="btn-close me-1" data-bs-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">×</span>

				</button>

			</div>

			<div class="modal-body">

				<div class="col-sm-6">

					<div class="form-group">

						<label class="form-label">Product Name</label>

						<input class="form-control  mb-4" placeholder="Name" type="text">

					</div>

				</div>

				<div class="col-sm-6">

					<div class="form-group">

						<label class="form-label">Rate</label>

						<input class="form-control  mb-4" placeholder="Rate" type="text">

					</div>

				</div>

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



