<?php 

// include '../include/shi-config.php' ;

// var_dump('hellow');die;

?>
<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>National-Draw</title>
  <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="16x16">


<style>
* {
  box-sizing: border-box;
}
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

html, body {
  height: 100%;
  margin: 0;
}

body {
 font-family: Poppins, sans-serif !important;
  background-color: #171f4f;
  height: 100%;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #1c1c1c;
  display: flex;
  justify-content: center;
}

.ticket-system {
  max-width: 385px;
}
.ticket-system .top {
  display: flex;
  align-items: center;
  flex-direction: column;
}
.ticket-system .top .title {
  font-weight: normal;
  font-size: 1.6em;
  text-align: left;
  margin-left: 20px;
  margin-bottom: 50px;
  color: #fff;
}
.ticket-system .top .printer {
  width: 90%;
  height: 20px;
  border: 5px solid #fff;
  border-radius: 10px;
  box-shadow: 1px 3px 3px 0px rgba(0, 0, 0, 0.2);
}
.ticket-system .receipts-wrapper {
  overflow: hidden;
  margin-top: -10px;
  padding-bottom: 10px;
}
.ticket-system .receipts {
  width: 100%;
  display: flex;
  align-items: center;
  flex-direction: column;
  transform: translateY(-510px);
  animation-duration: 2.5s;
  animation-delay: 500ms;
  animation-name: print;
  animation-fill-mode: forwards;
}
.ticket-system .receipts .receipt {
  padding: 25px ;
  text-align: left;
  min-height: 200px;
  width: 88%;
  background-color: #fff;
  border-radius: 10px 10px 20px 20px;
  box-shadow: 1px 3px 8px 3px rgba(0, 0, 0, 0.2);
  text-align: center;
}

.ticket-system .receipts .receipt .route {
  /* display: flex; */
  justify-content: space-between;
  align-items: center;
  margin: 20px 0;
  text-align: center;
}

.ticket-system .receipts .receipt .route h2 {
  font-weight: 300;
  font-size: 25px;
  margin: 0;
}
.ticket-system .receipts .receipt .details {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
}
.ticket-system .receipts .receipt .details .item {
  display: flex;
  flex-direction: column;
  min-width: 70px;
}
.ticket-system .receipts .receipt .details .item span {
  font-size: 0.8em;
  color: rgba(28, 28, 28, 0.93);
  font-weight: 500;
}
.pro tr td{
    font-size: 14px;
    line-height: 1.5;
}
.pro  small{
    font-size: 12px!important;
}
.pro {
    width: 100%;
margin: 5px 0;
}
.receipt.qr-code {
    padding: 20px 10px !important;
}
.pro {
    width: 100%;
    border-collapse: collapse;
}


.ticket-system .receipts .receipt .details .item h3 {
    /* margin-top: 10px;
    margin-bottom: 15px; */
    font-size: 15px;
    margin: 0;
}
.ticket-system .receipts .receipt.qr-code {
  /* height: 110px; */
  min-height: unset;
  position: relative;
  border-radius: 20px 20px 10px 10px;
  /* display: flex; */
  align-items: center;
}
.ticket-system .receipts .receipt.qr-code::before {
  content: "";
  background: linear-gradient(to right, #fff 50%, #171f4f 50%);
  background-size: 22px 4px, 100% 4px;
  height: 3px;
  width: 90%;
  display: block;
  left: 0;
  right: 0;
  top: -1px;
  position: absolute;
  margin: auto;
}
.ticket-system .receipts .receipt.qr-code .qr {
  width: 70px;
  height: 70px;
}
.ticket-system .receipts .receipt.qr-code .description {
  margin-left: 20px;
}
.ticket-system .receipts .receipt.qr-code .description h2 {
  margin: 0 0 5px 0;
  font-weight: 500;
}
.ticket-system .receipts .receipt.qr-code .description p {
  margin: 0;
  font-weight: 400;
}
p.total {
    margin: 4px 0;
    padding: 10px 0;
    font-weight: 700;
    border-bottom: 1px dashed #171f4f;
    border-top: 1px dashed #171f4f;
}
.terms-footer strong {
    font-weight: 400;
    font-size: 15px;
}
@keyframes print {
  0% {
    transform: translateY(-510px);
  }
  35% {
    transform: translateY(-395px);
  }
  70% {
    transform: translateY(-140px);
  }
  100% {
    transform: translateY(0);
  }
}
@media (max-width: 768px) {

}
</style>

 
  
  
</head>

<body translate="no">
 
<main class="ticket-system">
   <div class="top">
   <!-- <h1 class="title">Wait a second, your ticket is being printed</h1> -->
   <div class="printer" />
   </div>
   <?php
   
   include '../include/shi-config.php';
   
   if(isset($_GET['ticketid'])) {
        
        $ticketId = $_GET['ticketid'];
    
         echo $ticketId;
    } else {
       
        echo "Ticket ID not found in the URL";
    } 
    
    // $draw = select_query($con, "SELECT * FROM `ndticket` WHERE id='$ticketId' AND deletes = '0';");
     $stmt = $con->prepare("SELECT * FROM `ndticket` WHERE id=? AND deletes = '0'");
     $stmt->bind_param("i", $ticketId);
     $stmt->execute();
     $result = $stmt->get_result();
     
    //  if ($result->num_rows > 0) {
    //     while ($row = $result->fetch_assoc()) {
            
    //          echo "<h2>Ticket Details</h2>";
    //         echo "<h2>" . $row['id'] . "</h2>"; 
            
    //     }
    // }
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $userId = $row['userId'];
            $use = $con->prepare("SELECT * FROM `user_register` WHERE id=? AND deletes = '0'");
            $use->bind_param("i", $userId);
            $use->execute();
            $userResult = $use->get_result();
            
            
            
                // $id = $row['id'];
                // $use = $con->prepare("SELECT * FROM `invoice` WHERE ticketId=? AND deletes = '0'");
                // $use->bind_param("i", $id);
                // $use->execute();
                // $userResult = $use->get_result();
                // while ($userRow = $userResult->fetch_assoc()) {
                    
                // }
            while ($userRow = $userResult->fetch_assoc()) {
                $id = $userRow['id'];
                $userId = $userRow['userId'];
                $username = $userRow['name'];
                $userlname = $userRow['lname'];
                $usermobile = $userRow['mobile'];
                $purchaseDatetime = $row['purchaseDatetime'];
                $endDate = $row['endDate'];
                $username = $userRow['name'];
                $username = $userRow['name'];
                
                $purchaseDate = new DateTime($purchaseDatetime);
                $endDate = new DateTime($endDate);
                $interval = $purchaseDate->diff($endDate);
                $daysDifference = $interval->days;
               
                
            }
        }
    }
    else {
        echo "No ticket found with ID: $ticketId";
    }
   
    
    ?>
   <div class="receipts-wrapper">
      <div class="receipts">
         <div class="receipt">
            <img src="https://admin.nationaldrawuae.com/assets/images/in-logo.png" width="83%" alt="site-logo">
            <div class="route">
              
               <h2>Ticket Details</h2>
               <h2><?php echo"$ticketId"; ?></h2>
            </div>
            <div class="details">
               <div class="item">
                  <span>Customer Name</span>
                  <h3><?php echo $username . ' ' . $userlname; ?></h3>
               </div>
               <div class="item">
                  <span>Mobile No. </span>
                  <h3><?php echo "$usermobile"; ?></h3>
               </div>
               <div class="item" style="margin: 0 auto;">
                  <span>Issued On</span>
                  <h3><?php echo "$purchaseDatetime"; ?></h3>
               </div>
             
            </div>
         </div>
         <div class="receipt qr-code">
           
            <div class="details" style="border-bottom: 1px dashed #171f4f">
         
                <div class="item" style="margin: 5px auto; ;">
                   
                   <h3><?php echo "$usercreated_at"; ?></h3>
                </div>
              
             </div>
            <!-- <br> -->
            <table class="pro">
                <tbody>
                   <tr>
                      <th style="text-align: center;"> Products</th>
                      <th>Valid</th>
                      <th>Raffle ID</th>
                   </tr>
                   <tr>
                      <td>AED 90</td>
                      <td>
                         <?php echo "$daysDifference"; ?><br>
                         <small> <?php echo "$endDate"; ?></small>
                      </td>
                      <td>
                         ND00100001 <br>ND00100002<br>ND00100003
                      </td>
                   </tr>
                </tbody>
             </table>
             <div class="row">
                <div class="row text-center">

                    <p class="total">TOTAL: 90 AED</p>
                </div>

            </div>
<div class="row text-center">

                <div class="terms-footer"><strong>All Other Terms and Conditions Apply</strong>
                </div>
            </div>
         </div>
      </div>
   </div>
</main>
  
  
  
</body>

</html>
