 <!--20-10-2023    Divya       con changed to rcon-->
<?php

$pageTitle = "Draw Reports";

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
                                                <form method="POST">
                                                    <div class="col-12">

                                                        <span>Select Draw</span>&nbsp;<span style="color: red;">*</span>

                                                        <select name="draw_new_id" class="form-select" required>

                                                            <option value="">Select Draw</option>

                                                            <?php

                                                            // $draw = select_query($con, "draw", "", "`status` != 'Pending' and `deletes`='0' ORDER BY `id` DESC", "", "");
                                                            // $draw = select_query($con, "draw", "", "`status` = 'Completed' and `deletes`='0' OR id in (select id from draw where  `ticket_start_datetime` < '$dubaidate_time' and `ticket_end_datetime` > '$dubaidate_time'  AND `status` = 'Active' and `deletes`='0' order by `result_datetime` ASC) ORDER BY `id` DESC", "", "");
                                                            $draw = select_query($rcon, "draw", "", "(`deletes` = 0 AND `status` = 'Completed') OR (`deletes` = 0 AND `status` = 'Active' AND `id` IN ( SELECT `id` FROM ( SELECT `id` FROM `draw` WHERE `deletes` = 0 AND `status` = 'Active' ORDER BY `id` ASC LIMIT 2 ) t )) ORDER BY `id` DESC", "", "");
                                                            if ($draw['nr'] > 0) {

                                                                foreach ($draw['result'] as $key => $value) {
                                                                 $naeme = explode("#",$value['name']);
                                                            ?>

                                                                     <!--<option value="<?= $value['id']; ?>"><?= $value['name']; ?></option>-->
                                                                     <option value="<?= $value['id']; ?>"><?= 'Draw No #'  .str_pad($value['draw_no'], 3,"0", STR_PAD_LEFT) . ' - '. date("dmY", strtotime($value['result_datetime'])) . ' ' . str_replace("Draw","",$naeme[0] ) . ' (' .  date("D", strtotime($value['result_datetime'])) . ')'  ; ?></option>

                                                            <?php

                                                                }
                                                            }

                                                            ?>

                                                        </select>

                                                    </div>


                                                    <br>
                                                    <div class="col-12">
                                                        <input type="submit" class="btn btn-info" value="Get Report">
                                                    </div>
                                                </form>
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










            <?php if ($_POST['draw_new_id'] != '' && isset($_POST['draw_new_id'])) {
                $draw_id = BlockSQLInjection($_POST["draw_new_id"]);

                // $draw_id = (int) $_POST['draw_new_id'];
                $draw_name = select_top_name($rcon, "draw", "name", "`id` = '$draw_id' and `status` != 'Pending' and `deletes`='0' ORDER BY `id` DESC", "name", "");

                $oticket = mysqli_query($rcon, "SELECT sum(net_total) FROM ticket INNER JOIN invoice ON invoice.id = ticket.invoice_no WHERE ticket.draw_id = '$draw_id' AND invoice.response != 'wallet' AND ticket.deletes = '0'");
                $row1 = mysqli_fetch_array($oticket);
                $oticket = (int) $row1['sum(net_total)'];

                $wticket = mysqli_query($rcon, "SELECT sum(net_total) FROM ticket INNER JOIN invoice ON invoice.id = ticket.invoice_no WHERE ticket.draw_id = '$draw_id' AND invoice.response = 'wallet' AND ticket.deletes = '0'");
                $row2 = mysqli_fetch_array($wticket);
                $wticket = (int) $row2['sum(net_total)'];

                $product = select_query($rcon, "product", "", "`deletes`='0'", "", "");

                $oticket_all = select_query($rcon, "ticket", "", "`draw_id` = '$draw_id' and `deletes`='0' order by `id` DESC", "", "");
                $wticket_all = mysqli_query($rcon, "SELECT count(*) As 'nr' FROM ticket INNER JOIN invoice ON invoice.id = ticket.invoice_no WHERE ticket.draw_id = '$draw_id' AND invoice.response = 'wallet' AND ticket.deletes = '0'");
                $wticket_all = mysqli_fetch_array($wticket_all);

                $aticket = select_query_sum($rcon, "aticket", "net_total", "`draw_id` = '$draw_id' AND `deletes`='0' order by `id` DESC", "", "");
                $aticket_all = select_query($rcon, "aticket", "", "`draw_id` = '$draw_id' and `deletes`='0' order by `id` DESC", "", "");

                $mticket = select_query_sum($rcon, "mticket", "net_total", "`draw_id` = '$draw_id' and `deletes`='0' order by `id` DESC", "", "");
                $mticket_all = select_query($rcon, "mticket", "", "`draw_id` = '$draw_id' and `deletes`='0' order by `id` DESC", "", "");

                $winner_amt = select_query_sum($rcon, "winnerlist", "prize_amt", "`draw_id` = '$draw_id' AND  `userid` != '' ORDER BY `id` DESC", "", "");
                $winnerlist = select_query($rcon, "winnerlist", "", "`draw_id` = '$draw_id' AND  `userid` != '' ORDER BY `id` DESC", "", "");

            ?>


                <div class="row row-sm">

                    <div class="col-lg-12">

                        <div class="card">
                            <div class="card-header">
                                <div class="col-lg-4">
                                    <h3 class="card-title"><strong><?= $draw_name . ' Report'; ?></strong></h3>
                                </div>

                                <div class="col-lg-8">
                                    <button onclick="ExportToExcel('xlsx')" class="btn btn-info">Excel</button>
                                </div>
                            </div>

                            <div class="card-body">

                                <div class="table-responsive">
                                    <?php if ($oticket > 0) { ?>
                                        <div class="col-12">

                                            <table id="tbl_exporttable_to_xls" class="table table-bordered text-nowrap border-bottom">
                                                <thead>
                                                    <th>Name</th>
                                                    <th>Count</th>
                                                    <th>Total</th>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="3" style="text-align: center;"> Online Ticket</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Total Ticket</td>
                                                        <td><?= $oticket_all["nr"] . ' Ticket'; ?></td>
                                                        <td><?= $oticket; ?></td>
                                                    </tr>

                                                    <?php
                                                    if ($product['nr'] > 0) {

                                                        foreach ($product['result'] as $key => $value) {
                                                            $sql = mysqli_query($rcon, "SELECT  COUNT(*) AS `nr` FROM `ticket_lines` INNER JOIN `invoice` ON invoice.id = ticket_lines.invoice_no WHERE ticket_lines.type = 'OT' and ticket_lines.product_id = '$value[id]' and ticket_lines.draw_id = '$draw_id' and ticket_lines.deletes='0' and invoice.response != 'wallet'");
                                                            $row1 = mysqli_fetch_array($sql);

                                                            // $sql_1 = mysqli_query($con, "SELECT COUNT(*) AS `nr`, SUM(prize_amt) AS 'total_amt' FROM `winnerlist` INNER JOIN ticket_lines ON winnerlist.ticket_lines_id = ticket_lines.id WHERE winnerlist.draw_id = '$draw_id' AND ticket_lines.product_id = '$value[id]' AND ticket_lines.type = 'OT'");
                                                            // $row_2 = mysqli_fetch_array($sql_1);

                                                            if (intval($row1['nr']) > 0) {
                                                    ?>
                                                                <tr>
                                                                    <td><?= 'AED ' . intval($value['rate']); ?></td>
                                                                    <td><?= intval($row1['nr']) . ' Lines'; ?></td>
                                                                    <td><?= intval($row1['nr']) * intval($value['rate']); ?></td>
                                                                </tr>

                                                    <?php
                                                            }
                                                        }
                                                    } ?>
                                                </tbody>



                                            <?php }

                                        if ($wticket > 0) {

                                            ?>


                                                <tr>
                                                    <td colspan="3" style="text-align: center;">Wallet Ticket</td>
                                                </tr>

                                                <tr>
                                                    <td>Total Ticket</td>
                                                    <td><?= $wticket_all["nr"] . ' Ticket'; ?></td>
                                                    <td><?= $wticket; ?></td>
                                                </tr>



                                                <?php
                                                if ($product['nr'] > 0) {

                                                    foreach ($product['result'] as $key => $value) {
                                                        $sql = mysqli_query($rcon, "SELECT  COUNT(*) AS `nr` FROM `ticket_lines` INNER JOIN `invoice` ON invoice.id = ticket_lines.invoice_no WHERE ticket_lines.type = 'OT' and ticket_lines.product_id = '$value[id]' and ticket_lines.draw_id = '$draw_id' and ticket_lines.deletes='0' and invoice.response = 'wallet'");
                                                        $row1 = mysqli_fetch_array($sql);

                                                        if (intval($row1['nr']) > 0) {
                                                ?>
                                                            <tr>
                                                                <td><?= 'AED ' . intval($value['rate']); ?></td>
                                                                <td><?= intval($row1['nr']) . ' Lines'; ?></td>
                                                                <td><?= intval($row1['nr']) * intval($value['rate']); ?></td>
                                                            </tr>

                                                <?php
                                                        }
                                                    }
                                                } ?>


                                            <?php }
                                        if (intval($aticket) > 0) {

                                            ?>

                                                <tr>
                                                    <td colspan="3" style="text-align: center;">Agent Ticket</td>
                                                </tr>

                                                <tr>
                                                    <td>Total Ticket</td>
                                                    <td><?= $aticket_all["nr"] . ' Ticket'; ?></td>
                                                    <td><?= intval($aticket); ?></td>
                                                </tr>




                                                <?php
                                                if ($product['nr'] > 0) {

                                                    foreach ($product['result'] as $key => $value) {
                                                        $product1 = select_query($rcon, "ticket_lines", "", "`type` = 'AT' and `product_id` = '$value[id]' and `draw_id` = '$draw_id' and `deletes`='0'", "", "");

                                                        if (intval($product1['nr']) > 0) {
                                                ?>
                                                            <tr>
                                                                <td><?= 'AED ' . intval($value['rate']); ?></td>
                                                                <td><?= intval($product1['nr']) . ' Lines'; ?></td>
                                                                <td><?= intval($product1['nr']) * intval($value['rate']); ?></td>
                                                            </tr>

                                                <?php
                                                        }
                                                    }
                                                } ?>



                                            <?php }
                                        if (intval($mticket) > 0) { ?>



                                                <tr>
                                                    <td colspan="3" style="text-align: center;">Manual Ticket</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Ticket</td>
                                                    <td><?= $mticket_all["nr"] . ' Ticket'; ?></td>
                                                    <td><?= intval($mticket); ?></td>
                                                </tr>




                                                <?php
                                                if ($product['nr'] > 0) {

                                                    foreach ($product['result'] as $key => $value) {
                                                        $product1 = select_query($rcon, "ticket_lines", "", "`type` = 'MT' and `product_id` = '$value[id]' and `draw_id` = '$draw_id' and `deletes`='0'", "", "");

                                                        if (intval($product1['nr']) > 0) {
                                                ?>
                                                            <tr>
                                                                <td><?= 'AED ' . intval($value['rate']); ?></td>
                                                                <td><?= intval($product1['nr']) . ' Lines'; ?></td>
                                                                <td><?= intval($product1['nr']) * intval($value['rate']); ?></td>
                                                            </tr>

                                                <?php
                                                        }
                                                    }
                                                } ?>


                                            <?php }
                                        if ($winnerlist['nr'] > 0) {
                                            ?>

                                                <tr>
                                                    <td colspan="3" style="text-align: center;">Winner Report</td>
                                                </tr>


                                                <tr>
                                                    <td>Total Winner</td>
                                                    <td><?= $winnerlist["nr"] . ' Winners'; ?></td>
                                                    <td><?= intval($winner_amt); ?></td>
                                                </tr>



                                                <?php
                                                if ($product['nr'] > 0) {

                                                    foreach ($product['result'] as $key => $value) {

                                                        $product1 = mysqli_query($rcon, "SELECT COUNT(*) AS total_row, SUM(winnerlist.prize_amt) AS total_amt  FROM `winnerlist` INNER JOIN `ticket_lines` ON winnerlist.ticket_lines_id = ticket_lines.id WHERE ticket_lines.product_id = '$value[id]' AND winnerlist.draw_id = '$draw_id'");
                                                        $row2 = mysqli_fetch_array($product1);
                                                        $total_row = (int) $row2['total_row'];
                                                        $total_amt = (int) $row2['total_amt'];
                                                        if ($total_row > 0) {
                                                ?>
                                                            <tr>
                                                                <td><?= 'AED ' . intval($value['rate']); ?></td>
                                                                <td><?= $total_row . ' Winners'; ?></td>
                                                                <td><?= $total_amt; ?></td>
                                                            </tr>

                                                <?php
                                                        }
                                                    }
                                                } ?>


                                                </tbody>
                                            </table>
                                        </div>



                                    <?php } ?>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

        </div>

    <?php

            }

    ?>

    </div>



</div>

</div>

<script>
    var origin = window.location.origin;

    var url = origin + "/ajax/service/draw_report_services.php";


    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('tbl_exporttable_to_xls');
        var wb = XLSX.utils.table_to_book(elt, {
            sheet: "sheet1"
        });
        return dl ?
            XLSX.write(wb, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            }) :
            XLSX.writeFile(wb, fn || ('<?= $draw_name . ' Report'; ?>.' + (type || 'xlsx')));
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