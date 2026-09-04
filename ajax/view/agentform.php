<form class="login100-form validate-form cls_booking_form">

	<div class="modal-body">
		<div class="row">
			<div class="col-sm-12">
				<div id="createformerror"></div>
				<div class="alert alert-danger" role="alert" id="showerroralert">
				</div>
				<div class="alert alert-success" role="alert" id="showsuccessalert">
				</div>
			</div>
		</div>

		<div class="row">

			<div class="col-sm-12">


				<div class="wrap-input100 validate-input input-group">
					<a href="javascript:void(0)" class="input-group-text bg-white text-muted">
						<i class="mdi mdi-account" aria-hidden="true"></i>
					</a>
					<input class="input100 border-start-0 ms-0 form-control req-empty" name="name" type="text" placeholder="Name" required>
				</div>
				<div class="wrap-input100 validate-input input-group">
					<a href="javascript:void(0)" class="input-group-text bg-white text-muted">
						<i class="zmdi zmdi-email" aria-hidden="true"></i>
					</a>
					<input class="input100 border-start-0 ms-0 form-control req-empty" name="email" type="email" placeholder="Email" required>
				</div>
				<div class="wrap-input100 validate-input input-group">
					<a href="javascript:void(0)" class="input-group-text bg-white text-muted">
						<i class="zmdi zmdi-email" aria-hidden="true"></i>
					</a>
					<input class="input100 border-start-0 ms-0 form-control req-empty" name="mobile" type="tel" placeholder="Mobile" required>
				</div>
				<div class="wrap-input100 validate-input input-group">
					<a href="javascript:void(0)" class="input-group-text bg-white text-muted">
						<i class="zmdi zmdi-email" aria-hidden="true"></i>
					</a>
					<input class="input100 border-start-0 ms-0 form-control req-empty" name="passport" type="text" placeholder="Emirate / Passport ID">
				</div>
				<div class="wrap-input100 validate-input input-group">
					<a href="javascript:void(0)" class="input-group-text bg-white text-muted">
						<i class="zmdi zmdi-email" aria-hidden="true"></i>
					</a>
					<input class="input100 border-start-0 ms-0 form-control" name="passport_expiry" type="date" placeholder="Emirate / Passport ID Expiry">
				</div>


			</div>
		</div>
	</div>
	<div class="modal-footer">
		<!-- <button type="button" id="smallmodal" data-bs-effect="effect-scale" onclick="sendotp()"  style="float: right;" class="btn btn-info">Create Agent</button> -->

		<!-- <button type="button" id="smallmodal" data-bs-effect="effect-scale" onclick="sendotp()" data-bs-toggle="modal" data-bs-target="#otp" style="float: right;" class="btn btn-info">Create Agent</button> -->
		<button class="btn ripple btn-success" id="smallmodal" type="button" onclick="sendotp()">Create <?= $addName; ?></button>
		<!-- <button class="btn ripple btn-success" id="smallmodal" type="button" onclick="saveform()">Create Agent</button> -->
	</div>

</form>