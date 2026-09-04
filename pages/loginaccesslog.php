
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

        <input type="hidden" id="tabID" value="agents">

        <!-- CONTAINER -->

        <div class="main-container container-fluid">



            <!-- PAGE-HEADER -->

            <div class="page-header">

                <h1 class="page-title"> <a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Login Access Report</h1>

                <div>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page">Login Access Report</li>

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

                                    <div class="d-flex">

                                        <div class="mt-2">

                                            <div class="row">









                                                <div class="col-12">
                                                    <span>Select Date</span>&nbsp;<span style="color: red;">*</span>
                                                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                        <i class="fa fa-calendar"></i>&nbsp;
                                                        <span></span> <i class="fa fa-caret-down"></i>
                                                    </div>
                                                </div>










                                            </div>


                                            <div class="row">
                                                <div class="col-2">

                                                    <button type="submit" id="smslogsearch" class="btn btn-info" onclick="searchTicket()">Go</button>



                                                </div>
                                            </div>


                                            <br>

                                            <!-- <div class="row">

                                               

                                                <div class="col-4" id="searcherr">



                                                </div>

                                            </div> -->

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

                                <h3 class="card-title"><strong>Login Access Report</strong></h3>

                            </div>

                            <div class="col-lg-8">















                            </div>



                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="bankchange_report1" style="width:100%;">

                                    <thead>

                                        <tr>


                                            <!-- <th class="wd-15p border-bottom-0">S. No</th> -->
                                            <th class="wd-15p border-bottom-0">Execution Time</th>
                                            <th class="wd-15p border-bottom-0">ip</th>
                                            <th class="wd-15p border-bottom-0">Type</th>

                                            <th class="wd-15p border-bottom-0">User Details</th>

                                            <th class="wd-15p border-bottom-0">Reason</th>

                                            <th class="wd-15p border-bottom-0">System Try</th>



                                            <th class="wd-15p border-bottom-0">Forgot check</th>

                                            <th class="wd-15p border-bottom-0">Forgot Reason</th>

                                            <th class="wd-15p border-bottom-0">Forgot Time</th>

                                            <th class="wd-15p border-bottom-0">Auth Success</th>

                                            <th class="wd-15p border-bottom-0">Auth Time</th>

                                            <th class="wd-15p border-bottom-0">status</th>





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







<script>
    var origin = window.location.origin;

    var url = origin + "/ajax/service/login_access_log.php";




    $(function() {


        // var start = moment().subtract(29, 'days');
        var start = moment();
        var end = moment();

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));
        }

        $('#reportrange').daterangepicker({
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

        searchTicket();
    });


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



    function searchTicket() {



        var table = $('#bankchange_report1').DataTable();

        table.destroy();

        var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
        var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");
        if (formdate != '' && todate != '') {


            table = $("#bankchange_report1").DataTable({

                pageLength: 10,

                order: [
                    [0, 'desc']
                ],

                paging: true,

                searching: true,

                info: true,

                ajax: {

                    url: url,

                    method: "POST",

                    dataSrc: "",

                    data: {

                        method: 'access_log',

                        agdate: formdate,

                        todate: todate

                    }

                },

                dom: 'Bfrtip',

                buttons: [

                    'pageLength',
                    {

                        extend: 'excelHtml5',

                        title: 'Login access Report(' + formdate + ' to ' + todate + ')'

                    },




                ],




                columns: [
                    // {
                    //     data: "sno"
                    // },
                    {

                        data: "execution_time"

                    },
                    {

                        data: "ip"

                    },
                    {

                        data: "type"

                    },

                    {

                        data: "userdetails"

                    },

                    {

                        data: "reason"

                    },

                    {

                        data: "system_try"

                    },



                    {

                        data: "forgot_check"

                    },

                    {

                        data: "forgot_reason"

                    },

                    {

                        data: "forgot_time"

                    },

                    {

                        data: "auth_success"

                    },

                    {

                        data: "auth_time"

                    },

                    {

                        data: "status"

                    },

                ],



            });
        } else {
            toast('error', 'Please Select Date');
        }

    }
</script>