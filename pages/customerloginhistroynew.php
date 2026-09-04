<?php

/**
 * Date            Developer               Modifications
 * 29-09-2023      Divya                customer last login report
 */
 
 
 $get_coutry = select_query($con, "user_register", "", "`deletes` = '0' AND `roll_id` = '0' AND  created_at IS NOT NULL  Group by nationality ", "", "");

?>




<style>
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



    input,

    select {

        border: 1px solid #CCC;

    }



    button {

        color: #FFF;

        background-color: #428BCA;

        border: 1px solid #357EBD;

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

<div class="main-content app-content mt-0">

    <div class="side-app">





        <div class="main-container container-fluid">





            <div class="page-header">

                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Customer Login History</h1>

                <div>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page">Customer Login History</li>

                    </ol>

                </div>

            </div>



            <div class="row row-sm">

                <div class="col-lg-12">

                    <div class="card">

                        <div class="card-header">


                            <div class="row align-items-center">
                                <div class="col-12 mb-2">
                                    <h3 class="card-title"><strong>Customer History</strong></h3>
                                </div>


                              <div class="col-lg-4 col-md-8  mb-2">
                              <label for="searchtxt"><strong>Select Date</strong></label><span style="color: red;"></span>
                                    <!-- <span>Select Date</span> &nbsp; <span style="color:red;">*</span> -->
                                    <div id="reportrange"class="form-select" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        <span></span> <i class="fa fa-caret-down"></i>
                                    </div>
                                     
                                </div>

                                <div class="col-lg-4 col-md-8 mb-2">
    <label for="searchtxt"><strong>Select Country</strong></label><span style="color: red;"></span>
    <select oninput="viewtable($(this).val())" id="cpname" class="form-select" >
        <option value="">Select Country</option>
        
        <?php 
        if ($get_coutry['nr'] > 0) {
            $countries = $get_coutry['result'];
            // Sort the countries alphabetically based on the 'nationality' field
            usort($countries, function($a, $b) {
                return strcmp($a['nationality'], $b['nationality']);
            });

            foreach ($countries as $key => $value) { 
        ?>
        <option value="<?= $value['nationality'] ?>"><?= $value['nationality']?></option>
        <?php } } ?>
    </select>
</div>

                               
                                <div class="col-lg-4 col-md-6 mt-5" id="paySearchBtn">
                                    <button type="submit" id="smslogsearch" class="btn btn-info" onclick="viewtable()">Go</button>
                                </div>
                        
                            </div>
                        </div>



                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="onlineearntable" style="width:100%;">

                                    <thead>

                                        <tr>

                                         
                                            <th class="wd-20p border-bottom-0">Customer id</th>
                                            <th class="wd-15p border-bottom-0">Name</th>
                                            <th class="wd-20p border-bottom-0">Mobile</th>
                                            <th class="wd-20p border-bottom-0">Nationality</th>
                                            <th class="wd-20p border-bottom-0">Email ID</th>
                                            <th class="wd-15p border-bottom-0">User Created On</th>
                                            <th class="wd-15p border-bottom-0">Last Login</th>
                                           


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
    var url = window.location.origin + "/ajax/service/transaction_services.php";
  

    $(function() {


        createDatePricket('reportrange', '', '');
        if (createDatePricket != '') {
            viewtable();
        }
    });

   
    function createDatePricket(id) {
        var start = moment();
        var end = moment();

        function cb(start, end) {
            $('#' + id + ' span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));
        }
        $('#' + id).daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);
        cb(start, end);



    }

    function closerequeryStatus(id) {

        $('#' + id).modal('hide');

        viewtable();

    }


    

    function viewtable(cpname) {
        
       

       var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
        var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");

        var title = 'Customer Login History: (' + formdate + ' to ' + todate + ')';
        

        var table = $("#onlineearntable").DataTable({
            destroy: true,
            pageLength: 10,
            order: [
                [2, 'desc']
            ],
            columnDefs: [{
                type: 'date',
                targets: [2]
            }],
            paging: true,
            searching: true,
            info: true,
            ajax: {
                url: url,
                method: "POST",
                dataSrc: "",
                data: {
                    method: 'customer_login_histroy',
                    formdate: formdate,
                    todate: todate,
                    cpname: (cpname != '') ? cpname : $('#cpname').val(),
                }
            },
            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                'colvis',
                {
                    extend: 'csvHtml5',
                    title: title
                },
                {
                    extend: 'excelHtml5',
                    title: title
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    title: title
                }
            ],
            columns: [
              
             {
                    data: 'livein'
                },
                {
                    data: 'name'
                },
                {
                    data: 'mobile'
                },
                {
                    data: 'nation'
                },
               
                {
                    data: 'email'
                },
                
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return moment(data.creadedon).format("DD MMM YYYY hh:mm a");
                    }
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return moment(data.lostlogin).format("DD MMM YYYY hh:mm a");
                    }
                }
                
            ],
            initComplete: function() {
                // DataTable rendering completed
                // alert("DataTable rendering completed");
                ccAvenue = '';
                $('#paySearchBtn').html(btn);
            }
        });


    }





 

  
</script>

