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


span.fa.fa-eye.fs-14 {
    font-weight: 900;
    color: blue;
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
    
    .scroll-cell {
        max-height: 150px;         /* Vertical scroll area height */
        max-width: 500px;          /* Optional width control */
        overflow-y: auto;
        overflow-x: auto;
        white-space: pre-wrap;     /* Preserve formatting */
        word-break: break-word;
        background: #fafafa;
        border: 1px solid #ddd;
        padding: 5px;
        border-radius: 4px;
    }

</style>







<div class="main-content app-content mt-0">



    <div class="side-app">

        <input type="hidden" id="tabID" value="agents">

        <!-- CONTAINER -->

        <div class="main-container container-fluid">



            <!-- PAGE-HEADER -->

            <div class="page-header">

                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Error Report</h1>

                <div>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page">Error Report</li>

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

                                <h3 class="card-title"><strong>Error Report</strong></h3>

                            </div>

                            <div class="col-lg-8">















                            </div>



                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="email_report1" style="width:100%;">

                                    <thead>

                                        <tr>
                                            <th class="wd-15p border-bottom-0">Status</th>
                                            <th class="wd-15p border-bottom-0">Creater At</th>
                                            <th class="wd-15p border-bottom-0">URL</th>
                                            <th class="wd-20p border-bottom-0">Method</th>
                                            <th class="wd-15p border-bottom-0">Payload</th>
                                            <th class="wd-15p border-bottom-0">Message</th>
                                            <!--<th class="wd-15p border-bottom-0">Log</th>-->
                                            <th class="wd-15p border-bottom-0">Ip</th>
                                            <th class="wd-15p border-bottom-0">Files</th>
                                            <th class="wd-15p border-bottom-0">Line</th>
                                            <th class="wd-15p border-bottom-0">User Agent</th>
                                            <th class="wd-15p border-bottom-0">Headers</th>
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
    var url = origin + "/ajax/service/errorLogServices.php";
        var base_url = '<?= $baseurl; ?>';
    $(function() {
        createDatePricket('reportrange213');
        searchEmail();
    });

    function previewm(html) {
        console.log(html);

    }
    

   function markAsSolved(id){

    $.ajax({
        url: "/ajax/service/errorLogServices.php",
        type: 'POST',
        data:{
            id:id,
            method:'status_change'
        },
        success: function (response) {
            searchEmail();
        },
    
    })
   }



    // function searchEmail() {
    //     var table = $('#email_report1').DataTable();
    //     table.destroy();
    //     var formdate = moment($('#reportrange213').data('daterangepicker').startDate._d).format("MMM Do YY");
    //     var todate = moment($('#reportrange213').data('daterangepicker').endDate._d).format("MMM Do YY");

    //     if (formdate != '' && todate != '') {
    //         var title = 'Error Reports : (' + formdate + '  to  ' + todate + ')';
    //         table = $("#email_report1").DataTable({

    //             pageLength: 10,

    //             order: [5, 'desc'],

    //             paging: true,

    //             searching: true,

    //             info: true,

    //             ajax: {

    //                 url: url,

    //                 method: "POST",

    //                 dataSrc: "",

    //                 data: {

    //                     method: 'error_report',

    //                     agdate: formdate,

    //                     todate: todate

    //                 }

    //             },

    //             dom: 'Bfrtip',

    //             buttons: [

    //                 'pageLength',
    //                 'copy',

                   

    //                 {

    //                     extend: 'excelHtml5',

    //                     title: title

    //                 },




    //             ],

    //             columns: [
    //                 {

    //                     data: "created_at"

    //                 },
    //                 {

    //                     data: "url"

    //                 },
    //                 {

    //                     data: "method"

    //                 },

    //                 {

    //                     data: "payload"

    //                 },

    //                 {

    //                     data: "message"

    //                 },

    //                 {
    //                     data: "trace"
    //                 },
    //                 {

    //                     data: "ip"

    //                 },
    //                 {

    //                     data: "user_agent"

    //                 },
    //                 {

    //                     data: "headers"

    //                 }

    //             ]



    //         });
    //     } else {
    //         toast('error', 'Please Select Date');
    //     }
    // }

    
    function searchEmail() {
        var table = $('#email_report1').DataTable();
        table.destroy();
    
        var formdate = moment($('#reportrange213').data('daterangepicker').startDate._d).format("MMM Do YY");
        var todate = moment($('#reportrange213').data('daterangepicker').endDate._d).format("MMM Do YY");
    
        if (formdate != '' && todate != '') {
    
            var title = 'Error Reports : (' + formdate + '  to  ' + todate + ')';
    
            table = $("#email_report1").DataTable({
    
                pageLength: 10,
                order: [0, 'desc'],
                paging: true,
                searching: true,
                info: true,
    
                ajax: {
                    url: url,
                    method: "POST",
                    dataSrc: "",
                    data: {
                        method: 'error_report',
                        agdate: formdate,
                        todate: todate
                    }
                },
    
                dom: 'Bfrtip',
                buttons: [
                    'pageLength',
                    'copy',
                    {
                        extend: 'excelHtml5',
                        title: title
                    }
                ],
    
                columns: [
                       {
                        data:"status",
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                        if (data == 0) {
                            return `
                                <button 
                                    class="btn btn-sm btn-warning solve-btn" 
                                    onclick="markAsSolved(${row.id})">Mark As Solved
                                </button>
                            `;
                            }else if (data == 1) {
                                return `<span class="badge bg-success">Solved</span>`;
                            }
                            return '-';
                        }

                    },
                    { data: "created_at" },
                    { data: "url"},
                    { data: "method"},
    
                    { 
                        data: "payload", 
                        render: scrollCell
                    },
    
                    { 
                        data: "message",
                        render: scrollCell
                    },
    
                    // { 
                    //     data: "trace", 
                    //     width: "25%",
                    //     render: scrollCell
                    // },
    
                    { data: "ip"},

                    {data: "file"},

                    {data: "line"},
    
                    { data: "user_agent" },
    
                    { 
                        data: "headers",
                        render: scrollCell
                    }
                ]
    
            });
    
        } else {
            toast('error', 'Please Select Date');
        }
    }
    
    function scrollCell(data, type, row) {
        if (!data) return "";
    
        return `
            <div class="scroll-cell">
                ${data}
            </div>
        `;
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