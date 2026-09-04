<?php
   $pageTitle = ucwords(strtolower('Winner Image Upload'));
   
   ?>
<style>
   .bg-mon {
   background-color: #F1F500;
   }
   .bg-thrill {
   background-color: cyan;
   }
   .swal2-container {
   z-index: 9999;
   }
   .thirll-bg {
   border-radius: 12px;
   }
   input,
   button {
   height: auto !important;
   }
   .raffle-in {
   height: 60px;
   font-size: 2vw;
   background: #E3C7AF;
   /* border: 0; */
   text-align: center;
   }
   .modal-content {
   border: 0;
   border-radius: 12px;
   background-image: radial-gradient(circle at 29% 55%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 4%, transparent 4%, transparent 44%, transparent 44%, transparent 100%), radial-gradient(circle at 85% 89%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 51%, transparent 51%, transparent 52%, transparent 52%, transparent 100%), radial-gradient(circle at 6% 90%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 53%, transparent 53%, transparent 64%, transparent 64%, transparent 100%), radial-gradient(circle at 35% 75%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 6%, transparent 6%, transparent 98%, transparent 98%, transparent 100%), radial-gradient(circle at 56% 75%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 16%, transparent 16%, transparent 23%, transparent 23%, transparent 100%), radial-gradient(circle at 42% 0%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 3%, transparent 3%, transparent 26%, transparent 26%, transparent 100%), radial-gradient(circle at 29% 28%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 51%, transparent 51%, transparent 75%, transparent 75%, transparent 100%), radial-gradient(circle at 77% 21%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 35%, transparent 35%, transparent 55%, transparent 55%, transparent 100%), radial-gradient(circle at 65% 91%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 46%, transparent 46%, transparent 76%, transparent 76%, transparent 100%), linear-gradient(45deg, rgb(34 9 100), rgb(31 20 58));
   /* background-image: radial-gradient(circle at 29% 55%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 4%, transparent 4%, transparent 44%, transparent 44%, transparent 100%), radial-gradient(circle at 85% 89%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 51%, transparent 51%, transparent 52%, transparent 52%, transparent 100%), radial-gradient(circle at 6% 90%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 53%, transparent 53%, transparent 64%, transparent 64%, transparent 100%), radial-gradient(circle at 35% 75%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 6%, transparent 6%, transparent 98%, transparent 98%, transparent 100%), radial-gradient(circle at 56% 75%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 16%, transparent 16%, transparent 23%, transparent 23%, transparent 100%), radial-gradient(circle at 42% 0%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 3%, transparent 3%, transparent 26%, transparent 26%, transparent 100%), radial-gradient(circle at 29% 28%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 51%, transparent 51%, transparent 75%, transparent 75%, transparent 100%), radial-gradient(circle at 77% 21%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 35%, transparent 35%, transparent 55%, transparent 55%, transparent 100%), radial-gradient(circle at 65% 91%, hsla(329, 0%, 99%, 0.05) 0%, hsla(329, 0%, 99%, 0.05) 46%, transparent 46%, transparent 76%, transparent 76%, transparent 100%), linear-gradient(45deg, rgb(6 119 55), rgb(6 119 55)); */
   }
   textarea {
   height: 100px;
   padding: 12px 20px;
   box-sizing: border-box;
   border: 2px solid #ccc;
   border-radius: 4px;
   background-color: #f8f8f8;
   font-size: 16px;
   resize: none;
   }
   input,
   select {
   border: 1px solid #CCC;
   }
   input,
   button {
   height: 35px;
   margin: 0;
   padding: 6px 12px;
   border-radius: 2px;
   font-family: inherit;
   font-size: 100%;
   color: inherit;
   }
   #mytext {
   width: 361px;
   height: 63px;
   border: 0px;
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
   .bg-light123{
   background: aliceblue;
   padding: 23px;
   border-radius: 15px;
   }
   .preview {
   width: 80%;
   height: 210px;
   object-fit: cover;
   }
</style>
<script>
   window.onload = function() {
       var n = window.location.origin;
       document.getElementById("anchor").href = n
   };
</script>
<div class="main-content app-content mt-0">
<div class="side-app">
<!-- CONTAINER -->
<div class="main-container container-fluid">
   <!-- PAGE-HEADER -->
   <div class="page-header">
      <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a><?= $pageTitle; ?></h1>
      <ol class="breadcrumb">
         <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
         <li class="breadcrumb-item active" aria-current="page"><?= $pageTitle; ?></li>
      </ol>
   </div>
   <!-- PAGE-HEADER END -->
   <!-- ROW-1 -->
   <div class="row row-sm">
      <div class="col-lg-12">
         <div class="card">
            <div class="card-header">
               <div class="row align-items-center">
                  <div class="col-sm-12 col-md-6 col-lg-4">
                     <span>Select Draw</span>
                     <select id="draw_new_id" onchange="fetchWinnerList(this.value)" class="form-select">
                        <option value="">Select Draw</option>
                        <?php
                           $draw = select_query($con, "draw", "", "`dailyThirllStatus` = 'Completed' and `deletes`='0'  ORDER BY `id` DESC", "", "");
                           if ($draw['nr'] > 0) {
                               foreach ($draw['result'] as $key => $value) {
                                   $naeme = explode("#", $value['resultDate']);
                                   $drawDate = date("D", strtotime($value['resultDate']));
                                   echo '<option value="' . $value['dailyDrawNo'] . '">Draw No #' . str_pad($value['dailyDrawNo'], 3, "0", STR_PAD_LEFT) . ' - ' . str_replace("Draw", "", $naeme[0]) . ' (' . $drawDate . ')</option>';
                               }
                           }
                           ?>
                     </select>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- ROW-1 END -->
   <!-- ROW-4 -->
   <div class="row row-sm">
      <div class="col-lg-12">
         <div class="card">
            <div class="card-header hidden">
               <div class="row align-items-center">
                  <div class="col-12">
                     <div class="row">
                        <div class="my-2 col-lg-4 col-sm-4 mt-5" id="thrillDrawPre"></div>  
                         <div class=" my-2 col-lg-4 col-sm-4 mt-5" id="conDrawPre"></div>
                   
                     <div class="my-2 col-lg-4 col-sm-4 mt-5" id="monDrawPre"></div>
                     </div>
                     <br>
                     
                    
                     <div class=" mt-4">
                        
                     </div>
                  </div>
               </div>
            </div>
            <div class="card-footer">
               <button type="button" class="btn btn-primary" id="submitImages">Submit All Images</button>
            </div>
         </div>
      </div>
   </div>
   <!-- ROW-4 END -->
</div>
<!-- CONTAINER END --
   </div>
   </div>
   <!-- Modal -->
<script>
// var totalResponses = 0; 




var totalResponses = 0; // Counter for total responses received


$('#submitImages').on('click', function() {
    
    var allResponses = JSON.parse(localStorage.getItem('allResponses'));

  
    if (allResponses && allResponses.length > 0) {
        
        allResponses.forEach(function(response) {
            // Insert the response into the imageupload table using AJAX or other method
            // For example:
            $.ajax({
                url:  origin + '/ajax/service/WinnerimageUploadServices.php',
                type: 'POST',
                data: {
                        response: response,
                        method: 'image_upload_insert'
                    },
                success: function(result) {
                    
                     console.log(data);
                      var data = JSON.parse(result);
                    if (data.status === "success") {
                        
                        
                        swal.fire({
                                    title: 'Success',
                                    text: 'Image Deleted successful',
                                    icon: 'success',
                                    timer: 3000, // 3 seconds
                                    showConfirmButton: false
                                });
                        
                        // $('#imagePreview_' + imageId).remove();
                        $('#imagePreview_' + imageId).attr('src', '');
                   
                    }
                    console.log('Image uploaded and inserted into imageupload table');
                    //  window.location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Error: ' + textStatus + ' - ' + errorThrown);
                }
            });
        });

        // Clear the stored responses from local storage after inserting into the table
        localStorage.removeItem('allResponses');
    } else {
        console.log('No responses to submit');
    }
});

function previewImage(input, previewId, id) {
    console.log('Function called'); // Log a message to indicate the function call
    var user_id = id;
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#' + previewId).attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);

        // Upload the image via AJAX
        var formData = new FormData();
        formData.append('method', 'site_banner');
        formData.append('userID', user_id);
        formData.append('image', input.files[0]);

        $.ajax({
            url: origin + '/ajax/service/WinnerimageUploadServices.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // Get existing responses from local storage
                var allResponses = JSON.parse(localStorage.getItem('allResponses')) || [];

                // Push the new response into the array
                allResponses.push(response);

                
                localStorage.setItem('allResponses', JSON.stringify(allResponses));

                totalResponses++;

               
                if (totalResponses === input.files.length) {
                  
                    console.log(allResponses);

                   
                    totalResponses = 0;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error: ' + textStatus + ' - ' + errorThrown);
            }
        });
    }
}








   
   
   
       function fetchWinnerList(drawId) {
           // Make an AJAX request
           // alert(drawId);
           $.ajax({
               url: origin + '/ajax/service/WinnerimageUploadServices.php', // Replace 'your_endpoint_url_here' with your actual endpoint URL
               type: 'POST',
               data: {
                   drawId: drawId,
                   method: 'WinnerPreview',
               },
               success: function(data) {
   
                   var response = JSON.parse(data);
                   var dailyConsolationPre = ''; // Define the variable outside the loop
                   
                    var dailyConsolationPre = ''; // Define the variable outside the loop
                    var consolationDrawAdded = false;
                   response.forEach(function(row) {
                       
                       if (row.drawType == 'dailyThrill') {
                           var thrillDrawPre = '';
                        //   thrillDrawPre += `<h4 class="text-white text-center">Thirll Draw Prize GOLD (KG)</h4>`;
                           thrillDrawPre += `<div class=" ">
                                <h3><b>Daily Thrill Draw</b></h3>
                               <div class="">
                                  
                                   <div class="form-group bg-light123">
                                    <div>
                                       <label><b style="font-size:18px;">Name :</b>${row.Name}</label><br>
                                       <label><b style="font-size:18px;"> Phone :</b>${row.Mobile}</label>
                                   </div>
                                       <label for="image_${row.id}">Upload Image:</label>
                                       <input type="file" class="form-control-file form-control" id="image_${row.id}" accept="image/*" onchange="previewImage(this, 'imagePreview_${row.id}',${row.id})">

                                        <div class="text-center">
                                                <img class="mt-5" id="imagePreview_${row.id}" alt="" src="${row.image_url}">
                                               
                                           </div>
                                           <div class="text-center">
                                                
                                                <button class="btn btn-danger mt-2" onclick="deleteImage(${row.id})">Delete Image</button>
                                           </div>
                                   </div>
                               </div>
                           </div>`;
               
                           $('#thrillDrawPre').html(thrillDrawPre);
                       }
                       
                       
                       if (row.drawType == 'weeklyRaffle' && !consolationDrawAdded) {
                            dailyConsolationPre += `<div class=""><h3><b>Daily Consolation Draw</b></h3></div>`;
                            consolationDrawAdded = true; 
                        }
                       
                       if (row.drawType == 'weeklyRaffle' && consolationDrawAdded) {
                            // dailyConsolationPre += `<h3><b>Daily Consolation Draw</b></h3>`;
                           dailyConsolationPre += `
                                   
                                   <div class="">
                                   
                                   
                                       <div class="form-group bg-light123">
                                           <div>
                                           <label><b style="font-size:18px;">Name :</b>${row.Name}</label><br>
                                           <label><b style="font-size:18px;">Phone :</b>${row.Mobile}</label>
                                       </div>
                                           <label for="image_${row.id}">Upload Image:</label>
                                           <input type="file" class="form-control-file form-control" id="image_${row.id}" accept="image/*" onchange="previewImage(this, 'imagePreview_${row.id}',${row.id})">
                                           <div class="text-center">
                                                <img class="mt-5" id="imagePreview_${row.id}" alt="" src="${row.image_url}">
                                               
                                           </div>
                                           <div class="text-center">
                                                
                                                <button class="btn btn-danger mt-2" onclick="deleteImage(${row.id})">Delete Image</button>
                                           </div>
                                       </div>
                                   
                               </div>`;
                       }
                       
                       if (row.drawType == 'monthlyBumper') {
                           var monthlyBumperPre = '';
                        //   monthlyBumperPre += `<h4 class="text-white text-center">Thirll Draw Prize GOLD (KG)</h4>`;
                           monthlyBumperPre += `<div class="r">
                               <h3><b>Monthly Bumper Draw</b></h3>
                               <div class="">
                                   
                                   <div class="form-group bg-light123">
                                   <div>
                                       <label><b style="font-size:18px;">Name :</b>${row.Name}</label><br>
                                       <label><b style="font-size:18px;">Phone :</b>${row.Mobile}</label>
                                   </div>
                                       <label for="image_${row.id}">Upload Image:</label>
                                       <input type="file" class="form-control-file form-control" id="image_${row.id}" accept="image/*" onchange="previewImage(this, 'imagePreview_${row.id}',${row.id})">
                                        <div class="text-center">
                                                <img class="mt-5" id="imagePreview_${row.id}" alt="" src="${row.image_url}">
                                               
                                           </div>
                                           <div class="text-center">
                                                
                                                <button class="btn btn-danger mt-2" onclick="deleteImage(${row.id})">Delete Image</button>
                                           </div>
                                   </div>
                               </div>
                           </div>`;
               
                           $('#monDrawPre').html(monthlyBumperPre);
                       }
                       
                   });
   
                   // Update #conDrawPre outside the loop
                   $('#conDrawPre').html(dailyConsolationPre);
               },
   
             
               error: function(xhr, status, error) {
                   // Handle errors here
                   console.error('Error:', error);
               }
          });
      }
function deleteImage(imageId) {
   
    $.ajax({
        url:  origin + '/ajax/service/WinnerimageUploadServices.php',
        type: 'POST',
        data: { 
            id: imageId,
            method: 'deleteImagemethod',
        }, 
       success: function(response) {
            // Parse the JSON response
            var data = JSON.parse(response);
            
            // alert(data.status);
            console.log(data);
            if (data.status === "success") {
                
                
                swal.fire({
                            title: 'Success',
                            text: 'Image Deleted successful',
                            icon: 'success',
                            timer: 3000, // 3 seconds
                            showConfirmButton: false
                        });
                
                // $('#imagePreview_' + imageId).remove();
                $('#imagePreview_' + imageId).attr('src', '');
           
            }
        },
        error: function(xhr, status, error) {
            // Handle the error
            alert('Error deleting image: ' + error);
        }
    });
}

   
      
</script>