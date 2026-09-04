<?php



use LDAP\Result;



$pageTitle = "agent earning report";



?>

<style>

    input,

    select {

        border: 1px solid #CCC;

        /* width: 250px; */

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



<!--app-content open-->

<div class="main-content app-content mt-0">

    <div class="side-app">

        <input type="hidden" id="tabID" value="agents">

        <!-- CONTAINER -->

        <div class="main-container container-fluid">



            <!-- PAGE-HEADER -->

            <div class="page-header">

                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a><?= ucwords($pageTitle); ?></h1>

                <div>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page"><?= ucwords($pageTitle); ?></li>

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

                                                <div class="col-4">

                                                    <span>Date</span>

                                                    <input type="date" id="agdate" max="<?= date("Y-m-d"); ?>">

                                                </div>

                                            </div>

                                            <br>

                                            <div class="row">

                                                <div class="col-4">

                                                    <button class="btn btn-info" onclick="agentEarning()">Go</button>

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

            <!-- ROW-1 END -->











            <!-- ROW-4 -->

            <div class="row row-sm">

                <div class="col-lg-12">

                    <div class="card">

                        <div class="card-header">



                            <div class="col-lg-4">

                                <h3 class="card-title"><strong><?= ucwords($pageTitle); ?></strong></h3>

                            </div>

                            <div class="col-lg-8">















                            </div>



                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="agent_earning_report" style="width:100%;">

                                    <thead>

                                        <tr>

                                            <th class="wd-15p border-bottom-0">SI.No</th>

                                            <th class="wd-15p border-bottom-0">Date</th>

                                            <th class="wd-15p border-bottom-0">Agent ID</th>

                                            <th class="wd-15p border-bottom-0">Agent Name</th>

                                            <th class="wd-15p border-bottom-0">Agent Position</th>

                                            <th class="wd-15p border-bottom-0">No. of Tickets</th>

                                            <th class="wd-20p border-bottom-0">Collections Amount</th>

                                            <th class="wd-20p border-bottom-0">Action</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                    </tbody>

                                    <tfoot>

                                        <tr>

                                            <th></th>

                                            <th></th>

                                            <th></th>

                                            <th></th>

                                            <th></th>

                                            <th>Total:</th>

                                            <th></th>

                                        </tr>

                                    </tfoot>

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







<!--app-content close-->

<script>

    var origin = window.location.origin;

    var url = origin + "/ajax/service/report_services.php";





    





    function agentEarning() {

        var table = $('#agent_earning_report').DataTable();

        table.destroy();



        let agdate = $('#agdate').val();

        if (agdate != '') {

            const d = new Date(agdate);

            let filename = 'Agent Earning Report ' + d.getDate() + ' ' + d.toLocaleString('default', {

                month: 'short'

            }) + ' ' + d.getFullYear();

            table = $("#agent_earning_report").DataTable({

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

                        method: 'agentEarning',

                        agdate: agdate

                    }

                },

                dom: 'Bfrtip',

                buttons: [

                    'pageLength',

                    {

                        extend: 'copyHtml5',

                        title: filename

                    },



                    {

                        extend: 'csvHtml5',

                        title: filename

                    },

                    , {

                        extend: 'excelHtml5',

                        title: filename

                    },

                    {

                        extend: 'pdfHtml5',

                        orientation: 'landscape',

                        pageSize: 'LEGAL',

                        title: filename

                    }, 'print',



                ],

                // data: response.result,

                columns: [{

                        data: "sino"

                    },

                    {

                        data: "date"

                    },

                    {

                        data: "agentid"

                    },

                    {

                        data: "name"

                    },

                    {

                        data: "agpos"

                    },

                    {

                        data: "soldticket"

                    },

                    {

                        data: "amt"

                    },

                    {

                        data: "action"

                    }

                ],

                footerCallback: function(row, data, start, end, display) {

                    var api = this.api();



                    // Remove the formatting to get integer data for summation

                    var intVal = function(i) {

                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;

                    };



                    // Total over all pages

                    total = api

                        .column(6)

                        .data()

                        .reduce(function(a, b) {

                            return intVal(a) + intVal(b);

                        }, 0);



                    // Total over this page

                    pageTotal = api

                        .column(6, {

                            page: 'current'

                        })

                        .data()

                        .reduce(function(a, b) {

                            return intVal(a) + intVal(b);

                        }, 0);



                    // Update footer

                    $(api.column(6).footer()).html('' + pageTotal + ' ( ' + total + ' total)');

                },

            });

        } else {

            toast('error', 'Please Choose Date');

        }

    }



    function paymentSuccess(icon, titlestr) {

        Swal.fire({

            title: titlestr,

            icon: icon,

            confirmButtonColor: '#3085d6',

            confirmButtonText: 'OKAY',

            allowOutsideClick: false

        }).then((result) => {

            if (result.isConfirmed) {

                agentEarning();

            }

        })

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



    function viewagenthistory(id) {

        var path = '/agenthistory/list/' + id;

        NewTab(path);

        // console.log(id);

    }





    function NewTab(path) {

        var origin = window.location.origin;

        window.open(

            origin + path, "_blank");

    }

</script>