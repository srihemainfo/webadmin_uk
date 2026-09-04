<?php

/**
 * 
 *      Date            Developer     Changes
 *      
 *      22-03-2024      Devanathan    Agent List page creat
 * 
 * */
$pageTitle = "Agent List";

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
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Agent List</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Agent List</li>
                    </ol>
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
                                                            <th class="column_sort sorting sorting_asc">Agent ID</th>
                                                            <th class="column_sort sorting sorting_asc">Full Name</th>
                                                            <th class="column_sort sorting sorting_asc">Email</th>
                                                            <th class="column_sort sorting sorting_asc">Mobile</th>
                                                            <th class="column_sort sorting sorting_asc">Total Topup</th>
                                                            <th class="column_sort sorting sorting_asc">Point used</th>
                                                            <th class="column_sort sorting sorting_asc">Commission Point</th>
                                                            <th class="column_sort sorting sorting_asc">wallet Balance</th>
                                                            <th class="column_sort sorting sorting_asc">Created NO</th>
                                                            <th class="column_sort sorting sorting_asc">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                     <tfoot>
                                <!--<tr>-->
                                               
                                                
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align:right">Total(Liability):</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                    
                                 
                                <!--</tr>-->
                                                </tfoot>
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

   




    $(function() {


   
    //   createDatePricket('reportrange');
       AgentList1();
  

    })();









    function AgentList1(searchTxt = '') {
    let search_id1 = $('#searchTxt').val();
    let agentstatus = $('#agentstatus').val();

    let filename = 'Agent List ' + $('#draw_new_id option:selected').text();

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
                method: 'ListofAgent',
                searchTxt: search_id1,
                // formdate: formdate,
                // todate: todate,
                // status: agentstatus,
            }
        },
        order: [
            [5, 'desc']
        ],
        dom: 'Bfrtip',
        buttons: [
            'pageLength',
            'copy',
            {
                extend: 'excelHtml5',
                title: filename
            },
        ],
        columns: [
            {
                targets: 0,
                render: function(data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: "id" },
            { data: "name" },
            { data: "email" },
            { data: "mobile" },
            { data: "topup" },
            { data: "purchase" },
            { data: "commision_point" },
            { data: "points" },
            { data: "created_at" },
            {
                data: null,
                render: function(data, type, row, meta) {
                    var Turl = origin + "/customerList/list/" + data.id;

                    return `
                        <a target="_blank" href="customerList/list/${data.id}" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets">
                            <span style="font-size: 22px;" class="fa fa-users"></span>
                        </a>
                        <a target="_blank" href="Agentticketlist/list/${data.id}" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets">
                            <span style="font-size: 25px; color: #07eb07;" class="fa fa-ticket"></span>
                        </a>
                    `;
                }
            },
        ],
        footerCallback: function(row, data, start, end, display) { 
            var api = this.api();
            // Remove the formatting to get integer data for summation
            var intVal = function(i) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };
            // Total over all pages
            total = api
                .column(8)
                .data()
                .reduce(function(a, b) {
                    let x = intVal(a) + intVal(b);
                    return x.toFixed(2);
                }, 0);
            // Total over this page
            pageTotal = api
                .column(8, {
                    page: 'current'
                })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            // Update footer
            $(api.column(8).footer()).html('' + pageTotal.toFixed(2) + ' ( ' + total + ' total)');
        }
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