<?php

//1.modifications unknown//


//Date      Developer_name      Modifications//

use LDAP\Result;



$pageTitle = "Wallet Balance";



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



    .mdtext {

        font-weight: 600;

    }



    .input101 {

        width: 100%;

    }



    textarea {

        width: 100%;

        height: 150px;

        padding: 12px 20px;

        box-sizing: border-box;

        border: 2px solid #ccc;

        border-radius: 4px;

        background-color: #f8f8f8;

        font-size: 16px;

        resize: none;

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

                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a><?=ucwords($pageTitle);?></h1>

                <div>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page"><?=ucwords($pageTitle);?></li>

                    </ol>

                </div>

            </div>

            <!-- PAGE-HEADER END -->





            <!-- ROW-4 -->

            <div class="row row-sm">

                <div class="col-lg-12">

                    <div class="card">

                        <div class="card-header">



                            <div class="col-lg-4">

                                <h3 class="card-title"><strong><?=ucwords($pageTitle);?></strong></h3>

                            </div>

                            <div class="col-lg-8">















                            </div>



                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="wallet_list_table" style="width:100%;">

                                    <thead>

                                        <tr>

                                            <th>Name</th>

                                            <th>Type</th>

                                            <th>Wallet Balance</th>

                                            <th>Mobile</th>

                                            <th>Email</th>

                                            <th>Action</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                    </tbody>

                                    <tfoot>

                                    <tr>

                                    <th></th>



                                            <th style="text-align:right">Total:</th>

                                            <th></th>

                                            <!-- <th colspan="3"></th> -->

                                            <!-- <th></th> -->

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







<div class="modal  fade" id="withdrawtransfer" tabindex="-1" role="dialog">

    <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content" style="width: 400px;">

            <div class="modal-header">

                <h5 class="modal-title">Withdrawal Transfer</h5>

                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">×</span>

                </button>

            </div>

            <div class="modal-body">

                <div id="wterr"></div>

                <input type="hidden" id="withdrawreqtid">

                <div class="row" id="wdtdiv">



                </div>

            </div>

            <div class="modal-footer">

                <button class="btn btn-danger" onclick="refreshdata('withdrawtransfer')">Close</button>

                <div id="withtransbtn">



                </div>

            </div>

        </div>



    </div>

</div>







<!--app-content close-->

<script>

    var origin = window.location.origin;

    var url = origin + "/ajax/service/report_services.php";



    $(function() {

        walletlist();

    });



    function walletlist() {

        var table = $('#wallet_list_table').DataTable();

        table.destroy();



        table = $("#wallet_list_table").DataTable({

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

                    method: 'walletlist'

                }

            },

            dom: 'Bfrtip',

            buttons: [

                'pageLength',

                'copy',

                'csv', {

                    extend: 'excelHtml5',

                    title: 'Wallet Balance'

                },

                {

                    extend: 'pdfHtml5',

                    orientation: 'landscape',

                    pageSize: 'LEGAL'

                }, 'print',



            ],

            columns: [{

                    data: "name"

                },

                {

                    data: "position"

                },

                {

                    data: "walbalance"

                },

                {

                    data: "mobile"

                },



                {

                    data: "email"

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

                        .column(2)

                        .data()

                        .reduce(function(a, b) {

                            let x =  intVal(a) + intVal(b);

                            return x.toFixed(2);

                        }, 0);



                    // Total over this page

                    pageTotal = api

                        .column(2, {

                            page: 'current'

                        })

                        .data()

                        .reduce(function(a, b) {

                            return intVal(a) + intVal(b);

                        }, 0);



                    // Update footer

                    $(api.column(2).footer()).html('' + pageTotal + ' ( ' + total + ' total)');

                }

        });

    }







    function settlement(id) {

        $('#withdrawtransfer').modal('show');

        var formdata = [];

        formdata.push({

            name: 'method',

            value: "withdraw_transfer_verify"

        });

        formdata.push({

            name: 'request',

            value: id

        });

        var post_data = formdata;

        var onsuccess = function(data) {



            var response = JSON.parse(data);

            if (response != "") {

                if (response.type == 1) {

                    document.getElementById('wdtdiv').innerHTML = response.result;

                    document.getElementById('withtransbtn').innerHTML = response.withdraw;

                } else {

                    document.getElementById('wterr').innerHTML = response.result;

                }



            }

        }

        do_ajax_call(post_data, onsuccess, url);

    }





    function transferwdamount(id) {

        document.getElementById('wterr').innerHTML = '';

        let mode = document.getElementById('transactionmode').value;

        let transid = document.getElementById('withdrawid').value;

        let date = document.getElementById('withdrawdate').value;

        let reasontext = document.getElementById('reasontext').value;

        let withdrawamt = document.getElementById('withdrawamt').value;



        if (withdrawamt != '') {

            if (mode != '') {

                if (transid != '') {

                    if (date != '') {

                        if (reasontext != '') {

                            document.getElementById('withtransbtn').innerHTML = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...</button>';

                            var formdata = [];

                            formdata.push({

                                name: 'method',

                                value: "transfer_wd"

                            });

                            formdata.push({

                                name: 'request',

                                value: id

                            });

                            formdata.push({

                                name: 'mode',

                                value: mode

                            });

                            formdata.push({

                                name: 'transid',

                                value: transid

                            });

                            formdata.push({

                                name: 'date',

                                value: date

                            });

                            formdata.push({

                                name: 'reasontext',

                                value: reasontext

                            });

                            formdata.push({

                                name: 'withdrawamt',

                                value: withdrawamt

                            });

                            var post_data = formdata;

                            var onsuccess = function(data) {



                                var response = JSON.parse(data);

                                if (response != "") {

                                    if (response.type == 1) {

                                        document.getElementById('withtransbtn').innerHTML = '';

                                        document.getElementById('wdtdiv').innerHTML = response.result;

                                    } else {

                                        document.getElementById('wterr').innerHTML = response.result;

                                        document.getElementById('withtransbtn').innerHTML = response.transbtn;

                                    }



                                }

                            }

                            do_ajax_call(post_data, onsuccess, url);

                        } else {

                            document.getElementById('wterr').innerHTML = '<div class="alert alert-danger" role="alert">' + 'Please Fill Reason' + '</div>';

                        }

                    } else {

                        document.getElementById('wterr').innerHTML = '<div class="alert alert-danger" role="alert">' + 'Please select date' + '</div>';

                    }

                } else {

                    document.getElementById('wterr').innerHTML = '<div class="alert alert-danger" role="alert">' + 'Please enter Transaction id' + '</div>';

                }

            } else {

                document.getElementById('wterr').innerHTML = '<div class="alert alert-danger" role="alert">' + 'Please Select mode' + '</div>';

            }

        } else {

            document.getElementById('wterr').innerHTML = '<div class="alert alert-danger" role="alert">' + 'Please Enter the Withdraw amount' + '</div>';

        }



    }









    function refreshdata(str) {

        var table = $('#wallet_list_table').DataTable();

        table.destroy();

        walletlist();



        $('#' + str + '').modal('hide');

    }

</script>