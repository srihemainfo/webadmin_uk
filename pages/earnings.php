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

window.onload = function(){

var page_origin = window.location.origin;

let anchor =  document.getElementById("anchor");

anchor.href = page_origin;

}
</script>


<div class="main-content app-content mt-0">

    <div class="side-app">



        <!-- CONTAINER -->

        <div class="main-container container-fluid">



            <!-- PAGE-HEADER -->

            <div class="page-header">

                <h1 class="page-title"> <a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Earnings</h1>

                <div>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page">Earnings</li>

                    </ol>

                </div>

            </div>

            <!-- PAGE-HEADER END -->



            <!-- ROW-1 -->

            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">

                    <div class="row">





                        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">

                            <div class="card bg-primary img-card box-primary-shadow">

                                <div class="card-body">

                                    <div class="d-flex">

                                        <div class="text-white">

                                            <h2 class="mb-0 number-font" id="total_earn"></h2>

                                            <p class="text-white mb-0">Total Earnings </p>

                                        </div>

                                        <div class="ms-auto"> <i class="fa fa-user-o text-white fs-30 me-2 mt-2"></i> </div>

                                    </div>

                                </div>

                            </div>

                        </div>



                    </div>

                </div>

            </div>



            <div class="row row-sm">

                <div class="col-lg-12">

                    <div class="card">

                        <div class="card-header">

                            <div class="col-lg-4">

                                <h3 class="card-title"><strong>Earning Transaction History</strong></h3>

                            </div>





                            <div class="col-lg-8">

                                <input type="date" name="formdate" id="formdate" value="<?php echo date("Y-m-d"); ?>">

                                <input type="date" name="todate" id="todate" value="<?php echo date("Y-m-d"); ?>">

                                <button onclick="viewtable()">GO</button>

                            </div>





                        </div>



                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="earntable" style="width:100%;">

                                    <thead>

                                        <tr>

                                            <th class="wd-15p border-bottom-0">Date & Time</th>

                                            <th class="wd-15p border-bottom-0">Seller Name</th>

                                            <th class="wd-15p border-bottom-0">Payment Type</th>

                                            <th class="wd-15p border-bottom-0">Order Type</th>

                                            <th class="wd-15p border-bottom-0">Ticket Amount (AED)</th>

                                            <th class="wd-15p border-bottom-0">Commission Amount (AED)</th>

                                            <th class="wd-15p border-bottom-0">Transaction ID</th>

                                            <th class="wd-20p border-bottom-0">Status</th>

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



<div class="modal  fade" id="withdraw" tabindex="-1" role="dialog">

    <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Withdrawal Request</h5>

                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">×</span>

                </button>

            </div>

            <div class="modal-body">



                <div class="row">



                    <div class="col-sm-12">

                        <form class="login100-form validate-form">



                            <div class="wrap-input100 validate-input input-group">

                                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">

                                    <i class="side-menu__icon fa fa-money"></i>

                                </a>

                                <input class="input100 border-start-0 ms-0 form-control" type="text" placeholder="Enter Amount">

                            </div>

                            <div class="wrap-input100 validate-input input-group">

                                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">

                                    <i class="fa fa-key" aria-hidden="true"></i>

                                </a>

                                <input class="input100 border-start-0 ms-0 form-control" type="password" placeholder="Password">

                            </div>





                        </form>

                    </div>

                </div>



            </div>

            <div class="modal-footer">

                <button class="btn btn-danger" data-bs-dismiss="modal">Close</button>

                <button class="btn btn-secondary">Request</button>

            </div>

        </div>

    </div>

</div>



<script>
    var origin = window.location.origin;

    var url = origin + "/ajax/service/transaction_services.php";



    $(function() {

        viewtable();

        total_earning();

    });



    function total_earning() {

        var formdata = [];

        formdata.push({

            name: 'method',

            value: "total_earnings"

        });

        var post_data = formdata;

        var onsuccess = function(data) {



            var response = JSON.parse(data);

            if (response != "") {

                if (response.type == 1) {

                    document.getElementById('total_earn').innerText = 'AED ' + response.result;

                } else {

                    document.getElementById('total_earn').innerText = response.result;

                }



            }

        }



        do_ajax_call(post_data, onsuccess, url);



    }



    function viewtable() {
        var table = $('#earntable').DataTable();
        table.destroy();

        let formdate = $('#formdate').val();
        let todate = $('#todate').val();
        table = $("#earntable").DataTable({
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
                    method: 'list_earnings',
                    formdate: formdate,
                    todate: todate
                }
            },
            dom: 'Bfrtip',
            buttons: [

                'pageLength',

                {

                    extend: 'copyHtml5',

                    title: 'Earnings Report : (' + formdate + ' to ' + todate + ')'

                },

                {

                    extend: 'csvHtml5',

                    title: 'Earnings Report : (' + formdate + ' to ' + todate + ')'

                },

                {

                    extend: 'excelHtml5',

                    title: 'Earnings Report : (' + formdate + ' to ' + todate + ')'

                },

                {

                    extend: 'pdfHtml5',

                    orientation: 'landscape',

                    pageSize: 'LEGAL',

                    title: 'Earnings Report : (' + formdate + ' to ' + todate + ')'

                },

                'print'

            ],
            columns: [{

                    'data': 'date'

                },



                {

                    'data': 'sellername'

                },

                {

                    'data': 'type'

                },

                {

                    'data': 'ordertype'

                },

                {

                    'data': 'ticketamount'

                },

                {

                    'data': 'amt'

                },

                {

                    'data': 'transid'

                },

                {

                    'data': 'status'

                },

            ],
            // footerCallback: function(row, data, start, end, display) {
            //     var api = this.api();

            //     // Remove the formatting to get integer data for summation
            //     var intVal = function(i) {
            //         return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            //     };

            //     // Total over all pages
            //     total = api
            //         .column(7)
            //         .data()
            //         .reduce(function(a, b) {
            //             return intVal(a) + intVal(b);
            //         }, 0);

            //     // Total over this page
            //     pageTotal = api
            //         .column(7, {
            //             page: 'current'
            //         })
            //         .data()
            //         .reduce(function(a, b) {
            //             return intVal(a) + intVal(b);
            //         }, 0);

            //     // Update footer
            //     $(api.column(7).footer()).html('' + pageTotal + ' ( ' + total + ' total)');
            // },
        });

        // var table = $('#earntable').DataTable();

        // table.destroy();

        // var formdata = [];

        // formdata.push({

        //     name: 'method',

        //     value: "list_earnings"

        // });



        // let formdate = $('#formdate').val();

        // let todate = $('#todate').val();



        // formdata.push({

        //     name: 'formdate',

        //     value: formdate

        // });

        // formdata.push({

        //     name: 'todate',

        //     value: todate

        // });



        // var post_data = formdata;

        // var onsuccess = function(data) {



        //     var response = JSON.parse(data);

        //     if (response != "") {

        //         if (response.type == 1) {

        //             $('#earntable').DataTable({

        //                 order: [

        //                     [0, 'desc']

        //                 ],

        //                 dom: 'Bfrtip',

        //                 buttons: [

        //                     'pageLength',

        //                     {

        //                         extend: 'copyHtml5',

        //                         title: 'Earnings Report : (' + formdate + '  ' + todate + ')'

        //                     },

        //                     {

        //                         extend: 'csvHtml5',

        //                         title: 'Earnings Report : (' + formdate + '  ' + todate + ')'

        //                     },

        //                     {

        //                         extend: 'excelHtml5',

        //                         title: 'Earnings Report : (' + formdate + '  ' + todate + ')'

        //                     },

        //                     {

        //                         extend: 'pdfHtml5',

        //                         orientation: 'landscape',

        //                         pageSize: 'LEGAL',

        //                         title: 'Earnings Report : (' + formdate + '  ' + todate + ')'

        //                     },

        //                     'print'

        //                 ],

        //                 "data": response.result,

        //                 "columns": [{

        //                         'data': 'date'

        //                     },



        //                     {

        //                         'data': 'sellername'

        //                     },

        //                     {

        //                         'data': 'type'

        //                     },

        //                     {

        //                         'data': 'ordertype'

        //                     },

        //                     {

        //                         'data': 'ticketamount'

        //                     },

        //                     {

        //                         'data': 'amt'

        //                     },

        //                     {

        //                         'data': 'transid'

        //                     },

        //                     {

        //                         'data': 'status'

        //                     },

        //                 ],

        //             });

        //         } else {

        //             var table = $('#earntable').DataTable();

        //             table.clear().draw();

        //         }



        //     }

        // }



        // do_ajax_call(post_data, onsuccess, url);



    }
</script>