<?php
/**
 * Date         Developer_name      Modifications
 * 15-05-2023   Prashant            User Profile Activity Log
 * */
?>
<script>
    window.onload = function() {

        var page_origin = window.location.origin;

        let anchor = document.getElementById("anchor");

        anchor.href = page_origin;

    }
</script>


<style>
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
</style>







<div class="main-content app-content mt-0">



    <div class="side-app">

        <input type="hidden" id="tabID" value="agents">

        <!-- CONTAINER -->

        <div class="main-container container-fluid">



            <!-- PAGE-HEADER -->

            <div class="page-header">

                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>User profile activity</h1>

                <div>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page">User Profile Activity Report</li>

                    </ol>

                </div>

            </div>

            <!-- PAGE-HEADER END -->



            <!-- ROW-1 -->

            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">

                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">

                            <div class="card overflow-hidden">

                                <div class="card-body">

                                    <div class="">

                                        <div class="mt-2">

                                            <div class="row">












                                                <div class="col-sm-5 col-lg-4">
                                                    <label><strong>Select Date</strong></label>
                                                    <div id="reportrange213" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                        <i class="fa fa-calendar"></i>&nbsp;
                                                        <span></span> <i class="fa fa-caret-down"></i>
                                                    </div>
                                                </div>


                                                <!-- <div class="col-10">

                                                    <span>Date</span>

                                                    <input type="date" name="formdate" id="formdate" value="<?php echo date("Y-m-d"); ?>">

                                                    <input type="date" name="todate" id="todate" value="<?php echo date("Y-m-d"); ?>">

                                                </div> -->



                                                <div class="col-4">
                                                    <br>
                                                    <button type="submit" id="emailogsearch" class="btn btn-info" onclick="searchEmail()">Go</button>



                                                </div>

                                            </div>





                                            <br>


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

                        <div class="card-header">



                            <div class="col-lg-4">

                                <h3 class="card-title"><strong>User profile activity</strong></h3>

                            </div>

                            <div class="col-lg-8">















                            </div>



                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="userprofile_report" style="width:100%;">

                                    <thead>

                                        <tr>
                                            <th class="border-bottom-0">Sr.No</th>
                                            <th class="border-bottom-0">Changed By</th>
                                            <th class="border-bottom-0">User Email ID</th>
                                            <th class="border-bottom-0">Changed Data</th>
                                            <th class="border-bottom-0">IP</th>
                                            <th class="border-bottom-0">Date & Time</th>
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

            <!-- ROW-4 END -->

        </div>

        <!-- CONTAINER END -->

    </div>

</div>

<!-- Home button -->





<script>
    var origin = window.location.origin;
    var url = origin + "/ajax/service/datatable_services.php";

    $(function() {
        createDatePricket('reportrange213');
        searchEmail();
    });

    function previewm(html) {
        console.log(html);

    }

    function searchEmail() {
        var table = $('#userprofile_report').DataTable();
        table.destroy();
        var formdate = moment($('#reportrange213').data('daterangepicker').startDate._d).format("MMM Do YY");
        var todate = moment($('#reportrange213').data('daterangepicker').endDate._d).format("MMM Do YY");

        if (formdate != '' && todate != '') {
            var title = 'User profile activity : (' + formdate + '  to  ' + todate + ')';
            table = $("#userprofile_report").DataTable({

                pageLength: 10,
                bAutoWidth: false, 
                aoColumns : [
                    { sWidth: '15%' },
                    { sWidth: '15%' },
                    { sWidth: '15%' },
                    { sWidth: '15%' },
                    { sWidth: '15%' },
                    { sWidth: '15%' }
                ],
                /*responsive: {
                    details: {
                        type: 'column',
                        target: -1,
                    }
                },
                columnDefs: [{
                    targets: -1,
                    orderable: false,
                    searchable: false,
                    className: 'control',
                }, {
                    targets: 0,
                    orderable: false,
                    searchable: false,
                    className: 'selectall-checkbox',
                }],
                select: {
                    style: 'multi',
                    selector: 'td:first-child',
                },*/
                order: [5, 'desc'],

                paging: true,

                searching: true,

                info: true,

                ajax: {

                    url: url,

                    method: "POST",

                    dataSrc: "",

                    data: {

                        method: 'userprofile_report',

                        agdate: formdate,

                        todate: todate

                    }

                },

                dom: 'Bfrtip',

                buttons: [

                    'pageLength',

                    {

                        extend: 'copyHtml5',

                        title: title

                    },

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

                    },

                    {

                        extend: 'print',

                        title: title



                    },



                ],

                columns: [

                    {

                        data: "userId",

                    },
                    {

                        data: "userEmail"

                    },

                    {

                        data: "changedBy"

                    },

                    {
                        data: "changedData",
                        "render": function(data, type, full, meta){
                            var changedData = $.parseJSON(data);
                            var table = '<table class="table">';
                            table += '<tr>';
                            $.each(changedData, function (key, val) {
                                 table += '<th>'+key+'</th>';
                                console.log(key + val);
                            });
                            table += '</tr>';
                            table += '<tr>';
                            $.each(changedData, function (key, val) {
                                 table += '<td>'+val+'</td>';
                                console.log(key + val);
                            });
                            table += '</tr>';
                             table += '</table>';
                            /*var delete_url = '';
                            for(var i=0;i<=5;i++){
                                delete_url += '<a href="#" class="button small alert expand" data-method="delete" >Delete</a>';    
                            }
                            
                            return delete_url;*/
                            return table;
                        }
                    },

                    {
                        data: "ip"
                    },
                    {

                        data: "datetime"

                    }

                ],



            });
        } else {
            toast('error', 'Please Select Date');
        }
    }


    function toast(icon, message) {

        const Toast = Swal.mixin({

            toast: true,

            position: 'top-end',

            showConfirmButton: false,

            timer: 5000,

            timerProgressBar: true,

            didOpen: (toast) => {

                toast.addEventListener('mouseenter', Swal.stopTimer);

                toast.addEventListener('mouseleave', Swal.resumeTimer);

            }

        });



        Toast.fire({

            icon: icon,

            title: message

        });



    }

    function createDatePricket(id) {
        var start = moment();
        var end = moment();

        function cb(start, end) {
            $('#' + id + ' span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));
        }
        $('#' + id).daterangepicker({
            startDate: start,
            endDate: end,
            maxSpan: {
                days: 6
            },
            minDate: moment().subtract(14, "days").format("MM/DD/YYYY"),
            maxDate: moment().format("MM/DD/YYYY"),
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                // 'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                // 'This Month': [moment().startOf('month'), moment().endOf('month')],
                // 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);
        cb(start, end);

    }
</script>