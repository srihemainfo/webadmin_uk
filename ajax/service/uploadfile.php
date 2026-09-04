<?php 



include('../../include/shi-config.php');
include('../../include/functions.php');
session_start();
 $method = isset($_REQUEST["method"])?$_REQUEST["method"]:"";

  if($method=="profileupload"){ 
  $tablename = "accounts";
	$editid = isset($_SESSION[user_id])?$_SESSION[user_id]:"0";
if($editid!="0" && $editid!=""){
 if(isset($_FILES['file']['name'])){

   /* Getting file name */
   $filename = $editid.$_FILES['file']['name'];

  $folder = $_REQUEST[upfold];
   /* Location */
   mkdir("../../../".$folder,0755);
   $location = "../../../".$folder."/".$filename;
   $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
   $imageFileType = strtolower($imageFileType);
   
   /* Valid extensions */
   $valid_extensions = array("jpg","jpeg","png");

   $response = 0;
   /* Check file extension */
   if(in_array(strtolower($imageFileType), $valid_extensions)){
   if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
         $response = $folder."/".$filename;
		 $Arr =array("image"=>$response);
	$update =update($con,$tablename, "id='$editid'",$Arr, "", "","","");
      }
   }

   echo $response;
   exit;
  }
  }
  } 
?>
