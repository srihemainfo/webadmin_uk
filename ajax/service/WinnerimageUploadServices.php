<?php
include '../../include/shi-config.php';
include '../../include/functions.php';

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// var_dump('kimnh');die;
$method = $_REQUEST['method'];
$drawID = $_REQUEST['drawId'];
// var_dump($method,$drawID);die;

if ($method == "WinnerPreview") {
        $drawID = $_REQUEST['drawId'];
        
       
        
        $raffleCheckQuery = "SELECT * FROM `winnerlist` WHERE draw_id = '$drawID' AND deletes !=1 ORDER BY `prize` DESC ";
        $runQuery = mysqli_query($con, $raffleCheckQuery);
        $data = [];
        
        if ($runQuery && mysqli_num_rows($runQuery) > 0) {
            while ($row = mysqli_fetch_assoc($runQuery)) {
                $data[] = [
                    "drawType" =>  $row['drawType'],
                    "id" =>  $row['id'],
                    "userID" =>  $row['userid'],
                    "Name" =>  $row['fullName'],
                    "Email" => $row['email'],
                    "Mobile" => $row['mobile'],
                    "RaffleID" => $row['draw_id'],
                    "prize" => $row['prize'],
                    'winRaffleId' => $row['winRaffleId'],
                    'image_url' => $row['image_url'],
                    'ticketID' => $row['ticketID']
                ];
            }
        }
        
    
    resultFI:
    echo json_encode($data);

    
    
} 
 else if ($method == "site_banner") {
     
     if (isset($_FILES['image']['name'])) {
         
          $allowed = array('jpg', 'jpeg', 'tif', 'tiff', 'png', 'webp');
            $file_name = $_FILES['image']['name'];
            $file_type = $_FILES['image']['type'];
            $file_size = $_FILES['image']['size'];
            $file_temp_loc = $_FILES['image']['tmp_name'];
            
            $ext = pathinfo($file_name, PATHINFO_EXTENSION);
             $file_store = "";
            $path = "";
            $loginID = 1;
            if (!in_array($ext, $allowed)) {
                $result['type'] = '0';
                $result['result'] = 'format not supported!';
                // goto resultGVI1;
            }
            
             $userID = $_REQUEST['userID'];
             
            //  var_dump($userID);die;
            
             $filePath = new CURLFile($file_temp_loc, $file_type, $file_name);
        $postData = array('image' => $filePath, 'id' => $loginID);
        
         $fileUpload = json_decode(json_decode(fileMoveS3($postData), true), true);
         
        //  var_dump($fileUpload);die;
         
         if ($fileUpload['status'] = 'success') {

            $wd_arr= $fileUpload['data']['digitalURL'];
         }
         
        

        
        // var_dump($wd_arr);die;
            
           
            echo json_encode(['Image_URL' => $wd_arr,'userID' =>$userID]);
            exit;
        } else {
            // var_dump('this error');die;
            // Send an error response back to the client
            echo json_encode(['status' => 'error', 'message' => 'No image uploaded']);
            exit;
        }



    

     
 } else if ($method == "image_upload_insert") {
     
     $response = $_REQUEST['response'];
     
    //  $imageUrl = $response['Image_URL'];
    $imageUrl = "https://nationalasset.blr1.digitaloceanspaces.com/{$response['Image_URL']}";

$userId = $response['userID'];

$updateQuery = "UPDATE `winnerlist` SET `image_url` = '$imageUrl' WHERE `id` = $userId";

// Execute the update query
if(mysqli_query($con, $updateQuery)) {
     echo json_encode(['status' => 'success', 'message' => 'image upload succes']);
} else {
      echo json_encode(['status' => 'error', 'message' => 'No image uploaded']);
}

     
    
     
   
    
} else if ($method == "deleteImagemethod") {
     
     $userId = $_REQUEST['id'];
     
     $imageUrl = "";

    // $userId = $response['userID'];
    
    $updateQuery = "UPDATE `winnerlist` SET `image_url` = '$imageUrl' WHERE `id` = $userId";
    
    // Execute the update query
    if(mysqli_query($con, $updateQuery)) {
         echo json_encode(['status' => 'success', 'message' => 'image deleted succes']);
    } else {
          echo json_encode(['status' => 'error', 'message' => 'No image uploaded']);
    }
     
    //  var_dump($imageid);die;
     
    
    
}





