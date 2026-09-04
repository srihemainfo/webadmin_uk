<?php 
	
	include('../../include/shi-config.php');
	include('../../include/functions.php'); 
	
	$editid = $_REQUEST["editid"];
	$tablename = "user_register";
	$datas=select_query($con,"$tablename","", "`id`='$editid'","","");
	foreach($datas[result] as $data){}
?>

<div class="row gutters">
	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
		<form class="staffform" enctype="multipart/form-data">
			
			<div class="form-group">
				<label for="name">Emp Name </label>
				<input name="name" type="text"  class="form-control" id="name" placeholder=""
				value="<?=$data[name];?>" required>
			</div>
		</div>
		
		
		<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
			<div class="form-group">
				<label for="staff_id">Emp Id </label>
				<input name="staff_id" type="text"  class="form-control" id="staff_id" placeholder=""
				value="<?=$data[staff_id];?>" required>
				
			</div>
		</div>
		
		<!--<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
			<div class="form-group">
			<label for="email">Email *</label>
			<input name="email" type="email"  class="form-control" id="email" placeholder="" value="<?=$data[email];?>" required>
			</div>
		</div>-->
		
		
		<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
			<div class="form-group">
				<label for="mobile">Mobile no *</label>
				<input name="mobile" type="number"  class="form-control" id="mobile" placeholder="" value="<?=$data[mobile];?>" required>
				
			</div>
		</div>
		
		<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
			<div class="form-group">Email ID 
				<label for="email"> *</label>
				<input name="email" type="text"  class="form-control" id="email" placeholder="" value="<?=$data[email];?>" required>
			</div>
		</div>
		<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
			<div class="form-group">Address
				<label for="address"> *</label>
				<input name="address" type="text"  class="form-control" id="address" placeholder="" value="<?=$data[address];?>" required>
			</div>
		</div>
		<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
			<div class="form-group">
				<label for="user">Username *</label>
				<input name="user" type="text"  class="form-control" id="user" placeholder="" value="<?=$data[user];?>" required>
			</div>
		</div> 
		
		<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
			<div class="form-group">
				<label for="pass">Password *</label>
				<input name="pass" type="text"  class="form-control" id="pass" placeholder="" value="<?=$data[pass];?>" required>
			</div>
		</div> 
		
		<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
			<div class="form-group">
				<label for="type">Type *</label>
				
				<select class="form-control " name="type">                                
					<option value="">Select Type</option>
					<option value="admin">Admin</option>
					<option value="staff">staff</option>
				</select>
			</div>
		</div> 
		<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
			<div class="form-group">
				<label for="country">Status *</label>
				<select class="form-control " name="status">
					<?php foreach ($customstatus as $key=>$cstatus){ 
						$selected="";
					if($key==$data[status]){ $selected="selected"; } ?>
					<option value="<?=$key;?>" <?=$selected;?> ><?=$cstatus?></option>
					<?php } ?>
					
				</select>
			</div>
		</div>
		
		
		
		<input type="hidden" name="edit_id" value="<?=$data[id];?>">
		<div class="modal-footer">
				<button type="button" class="btn btn-success mt-2" onclick="saveform()"><?php echo $editid=="0" ? "Create" : "Update";?></button>
				<button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button>
			</div>
		
		
		
		
	</div>
</form>		
