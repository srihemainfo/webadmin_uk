<?php

/**
 * 
 *      Date            Developer     Changes
 *      
 *      11-03-2024      Devanathan    Winner List page creat
 * 
 * */
$pageTitle = "Winner List";

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

    .required-indicator {
        color: red;
        /* Change to the desired color */
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
        <div class="main-container container-fluid mt-5 pt-0">
            <!-- PAGE-HEADER -->
            <div class="page-header pt-5">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>Winner List</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Winner List</li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->
            <!-- ROW-1 -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex1">
                                <div class="mt-2">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <span>Search Winner</span>
                                            <input class="form-control" type="text" oninput="winnerList($(this).val())" id="searchTxt" placeholder="Name / Email / Mobile No">
                                            <!--<input class="form-control" type="text"  id="searchTxt" placeholder="Name / Email / Mobile No">-->
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <span>Select Draw type </span><span class="required-indicator">*</span>
                                            <select id="drawtype" class="form-select">
                                                <option value="">Select Draw Type</option>
                                                <option value="Daily_Thrill">Thrill Draw</option>
                                                <option value="Weekly_Raffle">Booster Draw</option>
                                                <option value="Month_Bumper">Bumper Draw</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <span>Select Draw</span><span class="required-indicator">*</span>
                                            <select id="draw_new_id" oninput="winnerList($(this).val())" class="form-select">
                                                <option value="">Select Draw</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4" id="searcherr">
                                    </div>
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
                                                            <!--<th class="column_sort sorting sorting_asc">s.No</th>-->
                                                            <th class="column_sort sorting sorting_asc">Full Name</th>
                                                            <th class="column_sort sorting sorting_asc">Email</th>
                                                            <th class="column_sort sorting sorting_asc">Mobile</th>
                                                            <th class="column_sort sorting sorting_asc">Draw Name</th>
                                                            <th class="column_sort sorting sorting_asc">Raffle Number</th>
                                                            <th class="column_sort sorting sorting_asc">Ticket NO</th>
                                                            <th class="column_sort sorting sorting_asc">Gold(Grms)</th>
                                                            <th class="column_sort sorting sorting_asc">Gold Amount</th>
                                                            
                                                            <th class="column_sort sorting sorting_asc">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                    <!--<tfoot>-->
                                                    <!--    <tr>-->
                                                    <!--        <th colspan="4"></th>-->
                                                    <!--        <th style="text-align:right">Total:</th>-->
                                                    <!--        <th></th>-->
                                                    <!--        <th colspan="1"></th>-->
                                                    <!--    </tr>-->
                                                    <!--</tfoot>-->
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

    <div class="modal fade" id="uploadimg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload Image</h5>
                </div>
                <div class="modal-body">
                    <div id="resuld"></div>
                </div>
                <!-- <div class="modal-footer">

            </div> -->
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteinfo" tabindex="-1" role="dialog" aria-labelledby="customModalLabel" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customModalLabel">Delete</h5> <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                </div>
                <div class="modal-body">
                    <div class="text-center" id="dele">
                    </div>
                </div>
                <div class="modal-footer custom">
                    <div class="right-side">
                        <button aria-label="Close" class="btn btn-danger pd-x-25 success" onclick="refersh()" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>
<script>
    var origin = window.location.origin;



    var url = origin + "/ajax/service/report_services.php";



    var ajax_url = origin + "/ajax/service/result_services.php";

    // var drawType = $(".tabs-menu1 ul>li>a.active").attr('id');

    $(document).ready(function() {
        $('#drawtype').change(function() {
            var drawType = $(this).val();
            // alert('devanathan');
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    method: 'winner_list_search',
                    drawType: drawType


                },
                success: function(response) {
                    $('#draw_new_id').html(response);
                }
            });
        });
    });



    $(function() {



        winnerList();



    })();









    function winnerList(searchTxt = '') {
        // alert('helloe');

        document.getElementById('searcherr').innerHTML = '';
        let draw_new_id = $('#draw_new_id').val();

        let Monthly_draw_new_id = $('#Monthly_draw_new_id').val();
        let drawtype = $('#drawtype').val();
        //   alert(drawtype);
        let search_id1 = $('#searchTxt').val();
        // if (draw_new_id != '') {

        // $('#ldfullre, #repcpdfbtn').html('');
        // $('#ldfullre1, #repcpdfbtn1').html('');

        let filename = 'Winner List ' + drawtype + ' ' + $('#draw_new_id option:selected').text();

        // alert(Monthly_draw_new_id);

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
                    method: 'winnerList',
                    draw_id: draw_new_id,
                    drawtype: drawtype,
                    searchTxt: search_id1,

                }
            },
            //         order: [
            //   [0, 'desc']

            //   ],
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
                //   {
                //       targets: 0, // Target the first column
                //       render: function(data, type, row, meta) {
                //           return meta.row + 1; // Add 1 to start from 1 instead of 0
                //       }
                //   },
                {
                    data: "name"
                },
                {
                    data: "email"
                },
                {
                    data: "mobile"
                },
                {
                    data: "winningDrawName"
                },
                {

                    data: "RaffleNO",

                },
                {
                    data: "TicketID"
                },
                {
                    data: "prize"
                },
                {
                    data: "prizeamt"
                },

                {
                    data: null,
                    render: function(data, type, row, meta) {
                        
                        var invoiceLink = data.ivoiceNo ? `<a target="_blank" href="<?= $baseurl; ?>invoice/${data.ticketReferenceID}" class="btn text-primary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Receipt"><span style="font-size: 18px;" class="fa fa-file-text-o"></span></a>` : '';

                        return `
                <a target="_blank" href="<?= $baseurl; ?>ticket-view/${data.ticketReferenceID}" class="btn text-secondary btn-sm" data-bs-toggle="tooltip" data-bs-original-title="View Tickets"><span style="font-size: 28px;" class="fa fa-ticket"></span></a>
                ${invoiceLink}
                
                <a class="btn text-danger btn-sm"  data-bs-original-title="Edit"><span style="cursor: pointer; color: #15bf0a !important;font-size:24px; " class="fa fa-edit edit_product" data-id="3490" onclick="uploadimg1(${data.id}, 'SUPER')"></span></a>&nbsp;
                <a class="btn text-danger btn-sm" target="_blank"  href="acknowledgement.php?id=${data.id}&DrawName=${data.drawType}"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a>
                
                
                               `;

                        //   <a class="btn text-danger btn-sm" target="_blank"  href="acknowledgement.php?id=${data.id}&DrawName=${data.drawType}"  style="cursor: pointer;" ><span style="font-size:24px;color:blue;" class="fa fa-print"></span></a>



                    }
                },


            ],




        });

    }

    function formatMatrix(raffleIds) {
        var result = '';
        for (var i = 0; i < raffleIds.length; i += 4) {
            for (var j = i; j < i + 4 && j < raffleIds.length; j++) {
                result += raffleIds[j];
                if (j < raffleIds.length - 1) {
                    result += ', ';
                } else {
                    // result += ' end';
                }
            }
            result += '<br>';
        }
        return result;
    }

    function uploadimg1(id, draw = '') {

        //   alert(draw);

        var formdata = [];

        formdata.push({
            name: 'method',
            value: "result_uplaod3"
        }, {
            name: 'drawName',
            value: draw
        }, {

            name: 'id',
            value: id

        });
        var post_data = formdata;
        var onsuccess = function(data) {
            var response = JSON.parse(data);

            if (response != "") {
                if (response.type == 1) {
                    // alert('THINK');

                    document.getElementById('resuld').innerHTML = response.result;
                    $('#uploadimg').modal('show');
                } else {
                    document.getElementById('resuld').innerHTML = response.result;

                    $('#uploadimg').modal('show');

                }
            }
        }
        do_ajax_call(post_data, onsuccess, ajax_url);
    }

    function move_new_file1(id, draw = '') {

        let file = document.getElementById('photos').value;
        var formData = new FormData();
        formData.append('method', 'move_file1');

        formData.append('drawName', draw);

        formData.append('id', id);

        formData.append('Country', $('#Country').val());

        formData.append('residingCountry', $('#residingCountry').val());

        formData.append('custname', $('#custname').val());

        formData.append('mfile', photos.files[0]);

        // console.log(formData);
        $.ajax({
            url: ajax_url,
            type: 'post',
            data: formData,
            success: function(response) {
                if (response != "") {
                    // alert(response.type);
                    if (response.type == '1') {
                        $('#uploadimg').modal('hide');
                        toast('success', response.result);

                    } else {
                        response = JSON.parse(response);
                        // alert(response);
                        // alert(response.type);
                        // alert('king');
                    }
                    if (response.type == '1') {
                        $('#uploadimg').modal('hide');
                        toast('success', response.result);
                    } else {
                        toast('error', 'Kindly Fill the Any Fields!');
                    }
                }
            },
            processData: false,
            contentType: false
        });

    }


    function deleteinfo(id, draw = '') {

        //   alert(id);
        var formdata = [];
        formdata.push({
            name: 'method',
            value: "delete_image_now"
        }, {
            name: 'drawName',
            value: draw
        }, {
            name: 'id',
            value: id
        });



        var post_data = formdata;

        var onsuccess = function(data) {
            var response = JSON.parse(data);
            if (response != "") {
                if (response.type == '1') {
                    $('#uploadimg').modal('hide');
                    toast('success', response.result);

                } else {
                    // toast('error', response.result);
                    toast('error', 'Kindly Fill the Fields!');

                }
            }
        }

        do_ajax_call(post_data, onsuccess, ajax_url);

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


    // function refersh() {
    //     location.reload();

    // }

    function preview(event) {
        var frame = document.getElementById("frame");

        if (event.target.files && event.target.files[0]) {
            frame.src = URL.createObjectURL(event.target.files[0]);
            $(`#frame`).show();
        }
    }
</script>