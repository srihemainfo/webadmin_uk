<?php



include 'include/shi-config.php';
include 'include/functions.php';

$id = $_REQUEST['id'];
if($id != ''){
      $Emaillog = select_query($con, "emaillog", "details", "`id` = '$id' ORDER BY `id` DESC LIMIT 1", "", "");
      if($Emaillog['nr'] > 0){
              echo $Emaillog['result'][0]['details'];
      }
} else {
    echo 'Kindly provide correct information!';
}
