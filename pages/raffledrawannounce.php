<?php

/**
 *      Date                Developer_name      Modifications
 * 
 *      20-05-2023          Prashant            Raffle Draw Winner User Selection 
 * */

$pageTitle = "Raffle Draw";

$getFaqData = select_query($con,"faq","","id=$subid2","","");

$getOrderNo = select_query($con, "faq", "", "`deletes` = '0' ORDER BY `orderno` DESC Limit 1", "", "");

if($getFaqData['nr'] == 0){
    $orderNo = 1;
    if($getOrderNo['nr'] > 0){
        $orderNo = $getOrderNo['result'][0]['orderno']+1;
    }
}
?>
<style>
    
    .faq_tbody{
        cursor: move;
    }
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
    .text-danger1 {
    color: #4d26e8 !important;
}
.swal2-styled,#gcBtn{
    padding: 5px 18px 18px 18px;
}
#ticket_report tbody{
    cursor: all-scroll;
}
.announce{
    display: none;
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
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a><?=ucwords($pageTitle);?>
                </h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?=ucwords($pageTitle);?>
                        </li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- ROW-1 -->
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row mb-3">
                                <div class="col-lg-3 col-sm-6 mb-2">
                                    <span>Select Draw</span>&nbsp;<span style="color: red;">*</span>
                                    <select id="draw_id" class="form-select mt-3">
                                        <!-- <option value="">Select Draw</option> -->
                                        <?php
                                           $draw = select_query($con, "draw", "", "(`deletes` = 0 AND `status` = 'Completed') OR (`deletes` = 0 AND `status` = 'Active' AND `id` IN ( SELECT `id` FROM ( SELECT `id` FROM `draw` WHERE `deletes` = 0 AND `status` = 'Active' ORDER BY `id` ASC LIMIT 2 ) t )) ORDER BY `id` DESC", "", "");
                                       
                                        if ($draw['nr'] > 0) {
                                            foreach ($draw['result'] as $key => $value) {
                                        ?>
                                                <option value="<?= $value['id']; ?>">
                                                    <?= $value['name']; ?>
                                                </option>
                                        <?php

                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-sm-6 mb-2">
                                    <span>Raffle Number</span>&nbsp;<span style="color: red;">*</span>
                                    <input type="text" name="raffle_id" id="raffle_id" class="form-control mt-3">
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
                                <div class="col">
                                    <h3 class="card-title"><strong>Winner List</strong></h3>
                                </div>
                            </div>
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table table-bordered display" id="ticket_report" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th class="wd-15p border-bottom-0">Rank</th>
                                                <th class="wd-15p border-bottom-0">Customer Name</th>
                                                <th class="wd-15p border-bottom-0">Mobile Number</th>
                                                <th class="wd-20p border-bottom-0">Email ID</th>
                                                <th class="wd-20p border-bottom-0">Product Amount (AED)</th>
                                                <th class="wd-20p border-bottom-0">Raffle ID</th>
                                                <th class="wd-25p border-bottom-0">Purchase Date & Timing</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <button type="button" data-bs-effect="effect-scale"  class="btn btn-info announce"><i class="fa fa-bullhorn me-2"></i>Announce Preview</button>
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

<!-- Delete Modal -->

<div class="modal fade" id="deleteinfo" tabindex="-1" role="dialog" aria-labelledby="customModalLabel" aria-modal="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customModalLabel">Delete</h5> <button type="button" onclick="refersh()" class="close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
            </div>
            <div class="modal-body" id="dele">
            </div>
            <div class="modal-footer custom">
                <div class="divider"></div>
                <div class="right-side">
                    <button aria-label="Close" class="btn btn-danger pd-x-25 success" onclick="refersh()" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var url = window.location.origin + "/ajax/service/raffle_services.php";
    var count = 1;
    $(document).ready(function(argument) {
        
        $( "#ticket_report tbody" ).sortable({
            delay: 150,
            stop: function() {
                 /*var selectedData = new Array();;
                $('#ticket_report tbody>tr').each(function() {
                    selectedData.push($(this).data("id"));
                });*/
                // updateOrder(selectedData);
            }
        });
        $( "#raffle_id" ).autocomplete({
            minLength: 3,
             source: function( request, response ) {
                  // Fetch data
                  $.ajax({
                       url: url,
                       type: 'GET',
                       dataType: "json",
                       data: {
                            method: 'searchTicket',
                            term: request.term,
                            draw_id:$('#draw_id').val()
                       },
                       success: function( data ) {
                            response( data );
                       }
                  });
             },
             select: function (event, ui) {
                    searchTicket(ui.item.id)
                  return false;
             },
        });
    })

    function searchTicket(ticket_line_id) {
        var table = $('#ticket_report').DataTable();
        table.destroy();

        if (count == 3) {
            $('.announce').show();
        } else{
            $('.announce').hide();
        }
        if (count <= 3) {

            var title = 'Raffle Winner List';
            table = $("#ticket_report").DataTable({
                pageLength: 3,
                order: [],
                paging: true,
                searching: true,
                info: true,
            })

            $.ajax({
                url: url,
                method: "POST",
                dataSrc: "",
                data: {method: 'ticketLineDetails', ticket_line_id: ticket_line_id, }, 
                success: function (response) {
                    var jsonObject = JSON.parse(response);
                    var result = [];
                    result[0] = count++;
                    var j = 1;
                    $.each(jsonObject, function (i,v){
                        result[j++] = v;
                    });
                    
                    table.row.add(result); // add to DataTable instance
                    table.draw();
                }
              });
        } else {
            toast('error', "Can't add more then three winner");
        }
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