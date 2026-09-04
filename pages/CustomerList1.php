<?php

/**
 * 
 *      Date            Developer     Changes
 *      
 *      22-03-2024      Devanathan    Agent customer List page creat
 * 
 * */
$pageTitle = "My Agent customer";

?>
<style>
    .card-header.d-lg-flex.d-block.justify-content-between {
        border-bottom: none;
    }

    input,
    select {
        border: 1px solid #CCC;
        /* width: 250px; */
    }

    .nav.product-sale {
        position: unset;
        top: -3rem;
        right: 5px;
        margin: 12px 0;
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

    .swal-modal {
        border: 3px solid white;
        color: #fff;
    }

    .swal-button {
        background-color: #07f3a2 !important;
    }

    .swal-text {
        font-weight: 600 !important;
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
</style>
<script>
    window.onload = function() {



        var page_origin = window.location.origin;



        let anchor = document.getElementById("anchor");



        anchor.href = page_origin;



    }
</script>
<!--app-content open-->
<div class="main-content app-content mt-0">
    <div class="side-app">
        <input type="hidden" id="tabID" value="agents">
        <!-- CONTAINER -->
        <div class="main-container container-fluid mt-5 pt-5">
            <!-- PAGE-HEADER -->
            <div class="page-header pt-5">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>My Agent customer</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Agent  customer</li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->
            <!-- ROW-1 -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex1">
                                <div class="mt-2">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <span>Search Agent</span>
                                            <input class="form-control" type="text" oninput="AgentList1($(this).val())" id="searchTxt" placeholder="Name / Email / Mobile No">
                                            <!--<input class="form-control" type="text"  id="searchTxt" placeholder="Name / Email / Mobile No">-->
                                        </div>
                                        
                                        
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                           <span>Select Date</span> &nbsp; <span style="color:red;">*</span>
                                            <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                               <i class="fa fa-calendar"></i>&nbsp;
                                               <span></span> <i class="fa fa-caret-down"></i>
                                            </div>

                                        </div>
                                        
                                        
                                       <!--<div class="col-sm-12 col-md-6 col-lg-4">-->
                                       <!--     <span>Select type</span>-->
                                       <!--     <select id="agentstatus" class="form-select">-->
                                       <!--         <option value="">select option</option>-->
                                       <!--         <option value="active">Active Agent</option>-->
                                       <!--         <option value="inactive">Inactive Agent</option>-->
                                       <!--     </select>-->
                                       <!-- </div>-->
                                       
                                       <div class="col-sm-12 col-md-6 col-lg-4">
                                            <br>
                                            <button type="submit" id="smslogsearch" class="btn btn-info" onclick="AgentList1()">Go</button>
                                         </div>
                                        
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4" id="searcherr">
                                    </div>
                                </div>
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
                    <div class="card-body pt-4">
                        <div class="grid-margin">
                            <div class="panel panel-primary">
                                <div class="tab-menu-heading border-0 p-0">
                                    <div class="tabs-menu1">
                                        
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body border-0 pt-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab1">
                                            <div class="card-header d-lg-flex d-block justify-content-between">
                                                <div class="mb-2 text-center" id="ldfullre">
                                                </div>
                                                <div class="mb-2 text-center" id="repcpdfbtn">
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered text-nowrap border-bottom" id="Participation_List" style="width:100%;">
                                                    <thead>
                                                        <tr>
                                                            <th class="column_sort sorting sorting_asc">s.No</th>
                                                            <th class="column_sort sorting sorting_asc">Customer ID</th>
                                                            <th class="column_sort sorting sorting_asc">Full Name</th>
                                                            <th class="column_sort sorting sorting_asc">Email</th>
                                                            <th class="column_sort sorting sorting_asc">Mobile</th>
                                                            <!--<th class="column_sort sorting sorting_asc">Password</th>-->
                                                            <!--<th class="column_sort sorting sorting_asc">Points</th>-->
                                                            <th class="column_sort sorting sorting_asc">Created NO</th>
                                                            <!--<th class="column_sort sorting sorting_asc">Action</th>-->
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
            </div>
        </div>
        <!-- ROW-4 END -->
    </div>
    <!-- CONTAINER END -->
</div>

<script>
    var origin = window.location.origin;



    var url = origin + "/ajax/service/agentND_services.php";



    var ajax_url = origin + "/ajax/service/result_services.php";

   

var currentPageUrl = window.location.href;

// Get the user ID from the URL
var userId = currentPageUrl.split('/').pop();


    $(function() {


   
       createDatePricket('reportrange');
       AgentList1();
  

    })();



 function createDatePricket(id) {
    var start = moment();
    var end = moment();

    function cb(start, end) {
        $('#' + id + ' span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));
    }

    $('#' + id).daterangepicker({
        startDate: start,
        endDate: end,
        timePicker: true,
        timePicker24Hour: true,
        timePickerSeconds: true,
        maxSpan: {
            days: 365
        },
        autoUpdateInput: true,
        maxDate: moment().add(1, 'days').toDate(),
        ranges: {
            'Today': [moment().startOf('day'), moment().endOf('day')],
            'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
            'This Month': [moment().startOf('month').startOf('day'), moment().endOf('month').endOf('day')],
            'Last Month': [moment().subtract(1, 'month').startOf('month').startOf('day'), moment().subtract(1, 'month').endOf('month').endOf('day')],
            'This Year': [moment().startOf('year').startOf('day'), moment().endOf('year').endOf('day')],
            'Last Year': [moment().subtract(1, 'year').startOf('year').startOf('day'), moment().subtract(1, 'year').endOf('year').endOf('day')]
        }
    }, cb);

    cb(start, end);
    // AgentList1();
}





    function AgentList1(searchTxt = '') {
        // alert(userId);

        document.getElementById('searcherr').innerHTML = '';
        let search_id1 = $('#searchTxt').val();
        let agentstatus = $('#agentstatus').val();
        var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("YYYY-MM-DD HH:mm:ss");
        var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("YYYY-MM-DD HH:mm:ss");
        
       
        let filename = 'Participation List ' + $('#draw_new_id option:selected').text();
        
        
        
        table = $('#Participation_List').DataTable({


            destroy: true,
            pageLength: 10,
            order: [],
            paging: true,
            searching: true,
            info: true,
            ajax: {
                url: url,
                method: "POST",
                dataSrc: "",
                data: {
                    method: 'Agent_customer',
                    searchTxt: search_id1,
                    formdate: formdate,
                    todate: todate,
                    userId: userId,
                    
                }
            },
             order: [
       [5, 'desc']
   
       ],
           dom: 'Bfrtip',
           buttons: [
           'pageLength',
       
               {
       
                   extend: 'csvHtml5',
                  title: filename
       
               },
               {
       
                   extend: 'excelHtml5',
                  title: filename
       
               },
               {
                   extend: 'print',
                  title: filename
       
               },
       
               ],


            columns: [
                {
                    targets: 0, // Target the first column
                    render: function(data, type, row, meta) {
                        return meta.row + 1; // Add 1 to start from 1 instead of 0
                    }
                },
                {
                    data: "id"
                   
                },
                {
                    data: "name"
                },
                {
                    data: "email"
                },
                 {
                    data: "mobile"
                },
                // {
                //     data: "password"
                // },
                // {
                //     data: "points"
                // },
                 {
                    data: "created_at"
                },
               
               
        //       {
   
        //   data: null,
        //               render: function(data, type, row, meta) {
   
        //                   var Turl = origin + "/ticketHistoryPay/list/" + data.id;
   
        //                   return   `
   

        //                   `;
        //                 //                              <a target="_blank" href="https://admin.nationaldrawuae.com/customerList/${data.id}" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets">
        //                     //     <span style="font-size: 18px;" class="fa fa-users"></span>
        //                     // </a>

   
                           
   
        //                     // <a target="_blank" href="https://www.test.nationaldrawuae.com/invoice/${data.id}" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span style="font-size: 18px;" class="fa fa-file-text-o"></span></a>
   
        //               }
        //           },
            ],


        });
        
    }
    
    

   
    function toast(icon, message) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)

            }
        })

        Toast.fire({
            icon: icon,
            title: message

        })
    }

    


    
</script>