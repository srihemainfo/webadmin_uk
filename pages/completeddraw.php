<?php
   $now = date("Y-m-d h:i:s");
   // $draw = select_query($con, "draw", "", "`status` = 'Active' AND `result_datetime` > '$now' AND `deletes` = '0' ORDER BY `result_datetime` ASC LIMIT 1", "", "");
   // $resultDate = date_format(date_create($draw['result'][0]['result_datetime']), "Y-m-d h:i:s a");
   
   
   ?>
<style>
   .back-arrow-btn i {
   background: #fff;
   font-size: 16px;
   padding: 2px 3px;
   border-radius: 50px;
   border: 2px solid #6c6e70;
   color: #6c6e70;
   margin-right: 15px;
   width: 24px;
   height: 24px
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
      <div class="main-container container-fluid">
         <div class="page-header">
            <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Completed Draws</h1>
            <div>
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Completed Draws</li>
               </ol>
            </div>
         </div>
         <div class="row row-sm">
            <div class="col-lg-12">
               <div class="card">
                  <div class="card-header">
                     <h3 class="card-title">Draw List</h3>
                     <div class="col-sm">
                     </div>
                  </div>
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="drawtable" style="width:100%;">
                           <thead>
                              <tr>
                                 <th class="wd-15p border-bottom-0">Draw No</th>
                                 <th class="wd-15p border-bottom-0">Draw Name</th>
                                 <th class="wd-20p border-bottom-0">Result Date</th>
                                 <th class="wd-15p border-bottom-0">Gold Reat</th>
                                 <th class="wd-10p border-bottom-0">Action</th>
                              </tr>
                           </thead>
                           <tbody>
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
<script>
   $(function() {
   
   	viewtable();
   
   
   
   });
   
  function viewtable() {
    $("#drawtable").DataTable({
        order: [
            [0, "desc"]
        ],
        ajax: {
            url: window.location.origin + "/ajax/service/completed_draw_services.php",
            method: "POST",
            dataSrc: "",
            data: {
                method: 'list_draw'
            }
        },
        columns: [
            { "data": "dailyDrawNo" },
            { "data": "name" },
            {
                "data": null,
                "render": function(data, type, row, meta) {
                    return moment(data.resultDate).format("DD MMM YYYY");
                }
            },
            { "data": "todayGoldPrize" },
            // {
            //     "data": "output",
            //     "render": function(data, type, row) {
            //         return data ? data : 'N/A';
            //     }
            // },
            { "data": "action" }
        ]
    });
}

</script>