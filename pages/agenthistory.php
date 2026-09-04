
<!--
Ajax file name:transaction_services.php

1.modifications unknown


Date      Developer_name      Modifications



-->
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

                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>History</h1>

                <div>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page">History</li>

                    </ol>

                </div>

            </div>

            <!-- PAGE-HEADER END -->



            <?php if ($subid2 == '') { ?>

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

                                                        <label>Mobile</label>&nbsp;<span style="color: red;">*</span>

                                                        <input type="text" id="mobile">

                                                    </div>

                                                </div>

                                                <br>

                                                <div class="row">

                                                    <div class="col-4">

                                                        <button class="btn btn-info" onclick="searchagent()">Go</button>

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

            <?php } ?>

            <div class="row row-sm">

                <div class="col-lg-12">

                    <div class="card">

                        <div class="card-header">

                            <div class="col-lg-4">

                                <h3 class="card-title"><strong>Agent History</strong></h3>

                            </div>





                            <div class="col-lg-8">

                                <input type="hidden" id="agentid" value="<?= $subid3; ?>">

                                <input type="date" name="formdate" id="formdate" value="<?php echo date("Y-m-d"); ?>">

                                <input type="date" name="todate" id="todate" value="<?php echo date("Y-m-d"); ?>">

                                <button onclick="viewtable($('#agentid').val())">GO</button>

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

                                            <th class="wd-20p border-bottom-0">Action</th>

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

    var origin = window.location.origin;

    var url = origin + "/ajax/service/transaction_services.php";

    <?php if ($subid3 != '') { ?>

        $(function() {

            let userid = <?= $subid3; ?>;

            viewtable(userid);



        });

    <?php } ?>





    function viewtable(userid) {



        let filename = 'Agent History';

        let formdate = $('#formdate').val();

        let todate = $('#todate').val();

        if (userid != '') {

            var table = $('#earntable').DataTable();

            table.destroy();



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

                        method: 'list_history',

                        userid: userid,

                        formdate: formdate,

                        todate: todate

                    }

                },

                dom: 'Bfrtip',

                buttons: [

                    'copy',

                    'csv', {

                        extend: 'excelHtml5',

                        title: 'Agent History : (' + userid + ' ' + formdate + ' ' + todate + ')'

                    },

                    {

                        extend: 'pdfHtml5',

                        orientation: 'landscape',

                        pageSize: 'LEGAL',

                        title: 'Agent History : (' + userid + ' ' + formdate + ' ' + todate + ')'

                    }, 'print',



                ],

                // data: response.result,

                columns: [{

                        data: 'date'

                    },



                    {

                        data: 'sellername'

                    },

                    {

                        data: 'type'

                    },

                    {

                        data: 'ordertype'

                    },

                    {

                        data: 'ticketamount'

                    },

                    {

                        data: 'amt'

                    },

                    {

                        data: 'transid'

                    },

                    {

                        data: 'status'

                    },

                    {

                        data: 'action'

                    }

                ]

            });

        }



    }



    function searchagent() {

        let mobile = $('#mobile').val();

        if (mobile != '') {

            var formdata = [];

            formdata.push({

                name: 'method',

                value: "searchagent"

            });

            formdata.push({

                name: 'mobile',

                value: mobile

            });





            var post_data = formdata;

            var onsuccess = function(data) {



                var response = JSON.parse(data);

                if (response != "") {

                    if (response.type == 1) {

                        document.getElementById('agentid').value = response.result;

                        viewtable(response.result);

                    } else {

                        toast('error', response.result);

                    }



                }

            }



            do_ajax_call(post_data, onsuccess, url);

        } else {

            toast('error', 'Please Type Mobile No');

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

</script>