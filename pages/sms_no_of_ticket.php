<?php
//    Date       Developer_name      Modifications
//    01-12-2023   Devanathan  K      Development 



$pageTitle = 'No of Ticket Purchase';
$DIR = dirname(__DIR__);
set_include_path($DIR);
require_once "xlsx/Classes/PHPExcel.php";

// if (isset($_POST['smscampID']) && $_POST['smscampID'] != null) {
//     $smsCamID = $_POST['smscampID'];
//     $smsDownloadBatch = ($_POST['smsDownloadBatch'] != '') ? $_POST['smsDownloadBatch'] : '1';
//     $objPHPExcel = new PHPExcel();
//     $objPHPExcel->setActiveSheetIndex(0);
//     $filename = $_POST['fileName'] . ' Batch ' . $smsDownloadBatch .  '.xlsx';

//     $user_register = select_query($con, "sms_campaign_list", "`mobile`, `link_url`", "`campaign_id` = '$smsCamID' AND `batch` = '$smsDownloadBatch' AND `deletes` = '0' ORDER BY `id` ASC", "", "");

//     if ($user_register['nr'] > 0) {
//         $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Mobile No');
//         $objPHPExcel->getActiveSheet()->setCellValue('B1', 'link');

//         $col = 2;
//         foreach ($user_register['result'] as $key => $value) {
//             $objPHPExcel->getActiveSheet()->setCellValue('A' . $col, $value['mobile']);
//             $objPHPExcel->getActiveSheet()->setCellValue('B' . $col, $value['link_url']);
//             $col++;
//         }
//     }

//     $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

//     $loc = $DIR . '/xlsx/upload/generate/';

//     $objWriter->save($loc . $filename);

//     $xslurl = $adminurl . 'xlsx/upload/generate/' . $filename;
//     divert($xslurl);
// }



?>

<style>
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

    span.fa.fa-trophy.fs-14 {
        color: #9d8711;
        font-size: 19px !important;
        font-weight: 900;
    }

    .preview {
        max-width: 100%;
        max-height: 200px;
        margin-bottom: 10px;
    }
</style>



<div class="main-content app-content mt-0">
    <div class="side-app">


        <div class="main-container container-fluid">

            <div class="page-header">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a><?= ucwords($pageTitle); ?></h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= ucwords($pageTitle); ?></li>
                    </ol>
                </div>
            </div>




            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <!-- <div class="card-header">
                            <h3 class="card-title"><strong>Export Customer List</strong></h3>
                        </div> -->
                        <div class="card-body">
                            <div class="row">





                                <!-- <form method="POST" id="exportDexcel"> -->




                                <!-- <div class="col-lg-2 col-md-2 mt-5" id="upbtn">
                                    <button class="btn btn-info" onclick="CollectTheBatch()" type="submit">Go</button>
                                </div> -->
                                <!-- </form> -->

                            </div>


                            <br>
                            <div class="row">
                                <div class="card-body">

                                    <div class="table-responsive">

                                        <table class="table table-bordered text-nowrap border-bottom" id="sms_report1" style="width:100%;">

                                            <thead>

                                                <tr>
                                                    <!-- <th class="wd-15p border-bottom-0">Customer id</th> -->
                                                    <th class="wd-15p border-bottom-0">Ticket Id</th>
                                                    <th class="wd-20p border-bottom-0">Customer Name</th>
                                                    <th class="wd-20p border-bottom-0">Email</th>
                                                    <th class="wd-20p border-bottom-0">My3Numbers</th>
                                                    <th class="wd-20p border-bottom-0">Mobile</th>
                                                    <th class="wd-20p border-bottom-0">Purchase Date & timing</th>
                                                    <th class="wd-20p border-bottom-0">Transaction Id</th>
                                                    <th class="wd-20p border-bottom-0">t point</th>
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
    </div>
</div>



<script>
    $(function() {
        CollectTheBatch();
    });
    var origin = window.location.origin;
    var url = origin + "/ajax/service/report_services.php";
    var ajax_url = origin + "/ajax/service/sms_campaign_services.php";

    var urlParams = new URLSearchParams(window.location.search);
    var smscampID = <?= $subid3; ?>;
    var smsDownloadBatch = <?= $subid4; ?>;


    function CollectTheBatch() {
        // let smscampID = $(`#smscampID`).val();
        // let smscampID = 1;
        if (smscampID == '') {
            toast('error', 'Kindly select the SMS Campaign Bluk Upload');
            return false;
        }


        var title = 'Campaign List';

        var table = $("#sms_report1").DataTable({
            destroy: true,
            pageLength: 10,

            paging: true,
            searching: true,
            info: true,
            ajax: {
                url: origin + "/ajax/service/sms_campaign_services.php",
                method: "POST",
                dataSrc: "result",
                data: {
                    method: 'noofticket',
                    smscampID: smscampID,
                    smsDownloadBatch: smsDownloadBatch,
                    // smsDownloadBatch: $(`#smsDownloadBatch`).val()
                    // agdate: formdate,
                    // todate: todate,
                    // campID: smtpauth,
                    // statusget: $('#statusget').val()
                }
            },
            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                
                {
                    extend: 'csvHtml5',
                    title: title
                },
                {
                    extend: 'excelHtml5',
                    title: title
                },
                // {
                //     extend: 'pdfHtml5',
                //     orientation: 'landscape',
                //     pageSize: 'LEGAL',
                //     title: title
                // },
                {
                    extend: 'print',
                    title: title
                },
            ],
            columns: [{

                    data: "ticket_no"

                },
                {

                    data: "name"

                },
                {

                    data: "email"

                },
                {

                    data: "draw_id"

                },
                {

                    data: "mobile"

                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return moment(data.purchase_datetime).format("DD MMM YYYY hh:mm a")
                    }

                    // data: "created_at"

                },
                {

                    data: "transaction_id"

                },
                {

                    data: "t_point"

                }





            ],

        });
    }
</script>