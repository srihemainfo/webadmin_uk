<?php 


include('../../include/shi-config.php');
include('../../include/functions.php');
session_start();
$method = isset($_REQUEST["method"])?$_REQUEST["method"]:"";

if($method == "sent_otp"){
	$tablename = "accounts";
$mobile = isset($_REQUEST["phone"])?$_REQUEST["phone"]:"";

/* $companyname =  select_top_name($con,$tablename, "name", "`mobile`='$mobile'", "name",""); */
$userid =  select_top_name($con,$tablename, "id", "`mobile`='$mobile'", "id","");
/* $companyemail =  select_top_name($con,$tablename, "email", "`mobile`='$mobile'", "email",""); */
$messages = rand(1000,9999);
$response = array();
$Arr = array("otp"=>$messages,"mobile"=>$mobile);


$tmpId = "1707161519842108473";
$message = "Dear User, Your skyfleetexpress Verification Code is $messages . Please use this code and complete the verification - Sri Hema Infotech";
if($userid!=""){
$ins = update($con,$tablename,"`id`='$userid'",$Arr,"","","");
$status =  select_top_name($con,$tablename, "sms_verify", "`id`='$userid'", "sms_verify","");

/* if($status =="verified"){ 
	
	$response["type"]="Active User";
	}else { */
	$sms = sendsms($con,$mobile,$message,$tmpId);
	if($sms){
		$response["type"]="Otp Sent Successfully";
	}
	/* } */
}else{
	$ins = insert($con,$tablename,"",$Arr,"","","");
	$sms =  sendsms($con,$mobile,$message,$tmpId);
	if($sms){
		$response["type"]="Otp Sent Successfully";
	}
}
echo json_encode($response);
}else if($method == "verify_otp"){
	$tablename = "accounts";
$mobile = isset($_REQUEST["phone"])?$_REQUEST["phone"]:"";
$otp = isset($_REQUEST["otp"])?$_REQUEST["otp"]:"";
$sotp =  select_top_name($con,$tablename, "otp", "`mobile`='$mobile'", "otp","");
$userid =  select_top_name($con,$tablename, "id", "`mobile`='$mobile'", "id","");
	
$response=array();
	
if($otp!=""){ 
	
if($otp==$sotp){ 
	
	$Arr = array("sms_verify"=>"verified");
$ins = update($con,$tablename,"`id`='$userid'",$Arr,"","",""); 
$_SESSION[user_id] = $userid;
		$response["type"]="verified";
}else{
		$response["type"]="Otp Miss Match";
}
}
echo json_encode($response);
	
}
?>
