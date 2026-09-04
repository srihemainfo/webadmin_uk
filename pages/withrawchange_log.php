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

window.onload = function(){

var page_origin = window.location.origin;

let anchor =  document.getElementById("anchor");

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

                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Bank Details Report</h1>

                <div>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page">Bank Details Report</li>

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















                                                <div class="col-10">

                                                    <span>Date</span>

                                                    <input type="date" name="formdate" id="formdate" value="<?php echo date("Y-m-d"); ?>">

                                                    <input type="date" name="todate" id="todate" value="<?php echo date("Y-m-d"); ?>">





                                                </div>

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

                                <h3 class="card-title"><strong>Bank Details Report</strong></h3>

                            </div>

                            <div class="col-lg-8">















                            </div>



                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="bankchange_report1" style="width:100%;">

                                    <thead>

                                        <tr>



                                            <th class="wd-15p border-bottom-0">User ID</th>

                                            <th class="wd-15p border-bottom-0">Update Type</th>

                                            <th class="wd-15p border-bottom-0">Bank Name </th>

                                            <th class="wd-15p border-bottom-0">Account Type</th>

                                            <th class="wd-15p border-bottom-0">Account Number</th>

                                            <th class="wd-15p border-bottom-0">IBAN Code</th>

                                            <th class="wd-15p border-bottom-0">SWIFT Code</th>

                                            <th class="wd-15p border-bottom-0">Currency Code</th>

                                            <th class="wd-15p border-bottom-0">EmiratesID / PassportID</th>

                                            <th class="wd-15p border-bottom-0">Updated Date & Time</th>







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

    var url = origin + "/ajax/service/datatable_services.php";





    $(function() {

        searchTicket();

    });



    function searchTicket() {



        var table = $('#bankchange_report1').DataTable();

        table.destroy();

        var agdate = $('#formdate').val();

        var todate = $('#todate').val();



        table = $("#bankchange_report1").DataTable({





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

                    method: 'bankchange_report',

                    agdate: agdate,

                    todate: todate

                }

            },

            dom: 'Bfrtip',

            buttons: [

                'pageLength',

                {

                    extend: 'copyHtml5',

                    title: 'Bank Details : (' + agdate + '  to  ' + todate + ')'

                },

                {

                    extend: 'csvHtml5',

                    title: 'Bank Details : (' + agdate + '  to ' + todate + ')'

                },

                {

                    extend: 'excelHtml5',

                    title: 'Bank Details : (' + agdate + '  to ' + todate + ')'

                },

                {

                    extend: 'pdfHtml5',

                    orientation: 'landscape',

                    pageSize: 'LEGAL',

                    title: 'Bank Details : (' + agdate + '  to  ' + todate + ')'

                },

                {

                    extend: 'print',

                    title: 'Bank Details: (' + agdate + '  to ' + todate + ')'



                },



            ],

            order: [[9, 'desc']],

            columns: [{

                    data: "userid"

                },

                {

                    data: "updatetype"

                },

                {

                    data: "bankname"

                },

                {

                    data: "account_type"

                },

                {

                    data: "accountno"

                },

                {

                    data: "iban"

                },

                {

                    data: "swift"

                },

                {

                    data: "currency"

                },

                {

                    data: "passport"

                },

                {

                    data: "datetime"

                },

            ],



        });

    }

</script>