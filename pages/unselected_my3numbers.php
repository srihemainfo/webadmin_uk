<?php

// Date        Developer     Changes
// 11-1-2022    Prakash       Unselected My3Numbers Reports development
// 21-2-2022    Prakash       Unselected count show

$pageTitle = "Unselected My3Numbers Reports";
?>

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
        width: 100%;
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

                                                <div class="col-12">

                                                    <span>Select Draw</span>&nbsp;<span style="color: red;">*</span>

                                                    <select id="draw_new_id" class="form-select">

                                                        <option value="">Select Draw</option>

                                                        <?php

                                                        $draw = select_query($con, "draw", "", "`status` != 'Pending' and `deletes`='0' ORDER BY `id` DESC", "", "");

                                                        if ($draw['nr'] > 0) {

                                                            foreach ($draw['result'] as $key => $value) {
                                                                $naeme = explode("#", $value['name']);

                                                        ?>

                                                                <!--<option value="<?= $value['id']; ?>"><?= $value['name']; ?></option>-->
                                                                <option value="<?= $value['id']; ?>"><?= 'Draw No #'  . str_pad($value['draw_no'], 3, "0", STR_PAD_LEFT) . ' - ' . date("dmY", strtotime($value['result_datetime'])) . ' ' . str_replace("Draw", "", $naeme[0]) . ' (' .  date("D", strtotime($value['result_datetime'])) . ')'; ?></option>
                                                        <?php



                                                            }
                                                        }

                                                        ?>

                                                    </select>

                                                </div>





                                            </div>





                                            <br>

                                            <div class="row">

                                                <div class="col-4">

                                                    <button class="btn btn-info" onclick="search_wise_Ticket()">Go</button>

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

                        <div class=" card-header d-lg-flex">



                            <div class="col-lg-4">
                                <h3 class="card-title"><strong>Unselected My3numbers</strong></h3>
                            </div>

                            <div class="col-lg-8">
                                <h3 class="text-lg-end card-title" id="titleColumn"></h3>
                            </div>



                        </div>

                        <div class="card-body">

                            <div class="table-responsive">
                                <textarea readonly="" id="unselectedMy3Number"></textarea>


                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- ROW-4 END -->





            <!-- ROW-4 -->

            <div class="row row-sm">

                <div class="col-lg-12">

                    <div class="card">

                        <div class="card-header">



                            <div class="col-lg-4">
                                <h3 class="card-title"><strong>Selected My3numbers </strong></h3>
                            </div>

                            <div class="col-lg-8">
                            </div>



                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="ticket_wise_report" style="width:100%;">

                                    <thead>

                                        <tr>

                                            <th class="wd-15p border-bottom-0">My3Number</th>

                                            <th class="wd-15p border-bottom-0">Count of My3Numbers</th>

                                            <th class="wd-15p border-bottom-0">Sum of AED</th>

                                            <th class="wd-20p border-bottom-0">TOP TEN AMOUNT</th>


                                        </tr>

                                    </thead>

                                    <tbody>

                                    </tbody>

                                    <tfoot style="display: none;">

                                        <tr>

                                            <th style="text-align: center;">Unselected My3Numbers</th>


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
    var url = window.location.origin + "/ajax/service/report_services.php";

    // Get the unselected my3numbers Start
    function search_wise_Ticket() {

        let draw_new_id = $('#draw_new_id').val();

        if (draw_new_id != '') {
            let formdata = [];
            formdata.push({
                name: 'method',
                value: "unselectedmy3numbers"
            });
            formdata.push({
                name: 'draw_id',
                value: draw_new_id
            });
            formdata.push({
                name: 'type',
                value: "unselected"
            });
            var post_data = formdata;
            var onsuccess = function(data) {
                var response = JSON.parse(data);
                if (response != "") {
                    if (response.type == 1) {
                        document.getElementById('unselectedMy3Number').innerText = response.result;
                        document.getElementById('titleColumn').innerHTML = '<strong>Total: </strong>' + response.unselectedcount;
                        var table = $('#ticket_wise_report').DataTable();
                        table.destroy();

                        var title = 'Selected My3Numbers Reports (' + draw_new_id + ')';
                        table = $("#ticket_wise_report").DataTable({
                            pageLength: 10,
                            order: [
                                [1, 'desc']
                            ],
                            paging: true,
                            searching: true,
                            info: true,
                            ajax: {
                                url: url,
                                method: "POST",
                                dataSrc: "",
                                data: {
                                    method: 'unselectedmy3numbers',
                                    draw_id: draw_new_id,
                                    type: 'selected'
                                }
                            },

                            dom: 'Bfrtip',
                            buttons: [
                                'pageLength',
                                'copy',

                                {
                                    extend: 'csvHtml5',
                                    title: title
                                },

                                {
                                    extend: 'excelHtml5',
                                    title: title,
                                    footer: true,
                                    messageBottom: response.result
                                },

                                {
                                    extend: 'pdfHtml5',
                                    orientation: 'portrait',
                                    pageSize: 'A4',
                                    title: title

                                }, 'print',
                            ],

                            columns: [{
                                    data: "rowlabel"
                                },
                                {
                                    data: "count"
                                },
                                {
                                    data: "amt"
                                },
                                {
                                    data: "topten"
                                },
                            ],
                        });


                    } else {


                    }
                }
            }
            do_ajax_call(post_data, onsuccess, url);
        } else {
            toast('error', 'Please Select Draw');
        }
    }

    // Get the unselected my3numbers End



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