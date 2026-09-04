<?php
//    Date       Developer_name      Modifications
//    01-12-2023   Devanathan  K      Development 



$pageTitle = 'SMS Campaign Bulk Upload';
$DIR = dirname(__DIR__);
set_include_path($DIR);
require_once "xlsx/Classes/PHPExcel.php";

if (isset($_POST['smscampID']) && $_POST['smscampID'] != null) {
    $smsCamID = $_POST['smscampID'];
    $smsDownloadBatch = ($_POST['smsDownloadBatch'] != '') ? $_POST['smsDownloadBatch'] : '1';
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $filename = $_POST['fileName'] . ' Batch ' . $smsDownloadBatch .  '.xlsx';

    $user_register = select_query($con, "sms_campaign_list", "`mobile`, `link_url`", "`campaign_id` = '$smsCamID' AND `batch` = '$smsDownloadBatch' AND `deletes` = '0' ORDER BY `id` ASC", "", "");

    if ($user_register['nr'] > 0) {
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Mobile No');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'link');

        $col = 2;
        foreach ($user_register['result'] as $key => $value) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $col, $value['mobile']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $col, $value['link_url']);
            $col++;
        }
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

    $loc = $DIR . '/xlsx/upload/generate/';

    $objWriter->save($loc . $filename);

    $xslurl = $adminurl . 'xlsx/upload/generate/' . $filename;
    divert($xslurl);
}

if (isset($_POST['fileName1']) && $_POST['smscampID1'] != null) {
    $smsCamID = $_POST['smscampID1'];
    $smsDownloadBatch = ($_POST['smsDownloadBatch1'] != '') ? $_POST['smsDownloadBatch1'] : '1';
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $filename = $_POST['fileName1'] . ' Batch ' . $smsDownloadBatch . 'No of clicks' .  '.xlsx';

    $user_register = select_query($con, "sms_campaign_list", "`mobile`, `link_url`", "`campaign_id` = '$smsCamID' AND `batch` = '$smsDownloadBatch' AND `deletes` = '0' AND `user_click` = '1' ORDER BY `id` ASC", "", "");

    if ($user_register['nr'] > 0) {
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Mobile No');
        // $objPHPExcel->getActiveSheet()->setCellValue('B1', 'link');

        $col = 2;
        foreach ($user_register['result'] as $key => $value) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $col, $value['mobile']);
            // $objPHPExcel->getActiveSheet()->setCellValue('B' . $col, $value['link_url']);
            $col++;
        }
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

    $loc = $DIR . '/xlsx/upload/generate/';

    $objWriter->save($loc . $filename);

    $xslurl = $adminurl . 'xlsx/upload/generate/' . $filename;
    divert($xslurl);
}


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



            <!-- Create SMS Campign  -->
            <!-- <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Create SMS Campaign Bulk Upload</h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="startDate">Campaign Name</label>&nbsp;<sup style="color: red;"><strong>*</strong></sup>
                                        <input type="text" class="form-control" id="c_name" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="endDate">Redirect URL (UTM URL)</label>&nbsp;<sup style="color: red;"><strong>*</strong></sup>
                                        <input type="text" class="form-control" placeholder="UTM URL" id="c_url" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group">
                                        <label for="startDate">SMS Message</label>&nbsp;<sup style="color: red;"><strong>*</strong></sup><br>
                                      
                                        <textarea id="c_message" placeholder="Type your message here..."></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3 mt-5">
                                    <button type="button" class="btn btn-success" onclick="create_sms()">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Upload Customer List  -->
            <div class="row row-sm">

                <div class="col-lg-12">

                    <div class="card">

                        <div class="card-header">
                            <h3 class="card-title"><strong>Upload Customer List</strong></h3>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-lg-5 col-md-5">
                                    <label for="networkreport" class="form-label">Choose Customer List</label>&nbsp;<sup style="color: red;"><strong>*</strong></sup>
                                    <input class="form-control" type="file" id="networkreport" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                </div>

                                <div class="col-lg-5 col-md-5">
                                    <div class="form-group">
                                        <label for="smtpauth">Select SMS Campaign Bulk Upload List</label>&nbsp;<sup style="color: red;"><strong>*</strong></sup>
                                        <select class="form-select" id="smtpauth">
                                            <option value="">Select SMS Campaign Bulk Upload</option>
                                            <?php
                                            $campignList = select_query($con, "sms_campaign", "", "`deletes` = '0' ORDER BY `id` DESC", "", "");
                                            if ($campignList['nr'] > 0) {
                                                foreach ($campignList['result'] as $key => $value) {
                                            ?>
                                                    <option value="<?= $value['id']; ?>"><?= $value['campaign_name']; ?></option>
                                            <?php
                                                }
                                            }

                                            ?>


                                        </select>
                                    </div>
                                </div>


                                <div class="col-lg-5 col-md-5">
                                    <div class="form-group">
                                        <label for="smtpauth">Select Batch</label>&nbsp;<sup style="color: red;"><strong>*</strong></sup>
                                        <select class="form-select" id="smsUploadBatch">
                                            <option value="">Select Upload Batch</option>
                                            <?php
                                            // $campignList = select_query($con, "sms_campaign", "", "`deletes` = '0' ORDER BY `id` DESC", "", "");
                                            // if ($campignList['nr'] > 0) {
                                            for ($i = 1; $i <= 100; $i++) {
                                            ?>
                                                <option value="<?= $i; ?>"><?= 'Batch ' . $i; ?></option>
                                            <?php
                                            }
                                            // }

                                            ?>


                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-2 mt-5" id="upbtn">
                                    <button type="submit" id="smslogsearch" class="btn btn-info" onclick="UploadReport()">Upload</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <label>Uploading file format</label>
                                    <a href="<?= $adminurl; ?>xlsx/customer-list-format.xlsx" download="">
                                        <button class="btn"><i class="fa fa-download"></i> Download</button>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12" id="successinfoMessage">

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <!-- Export the Customer List  -->
            <!-- <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Export Customer List</strong></h3>
                        </div>
                        <div class="card-body">
                            <div class="row">





                                <form method="POST" id="exportDexcel">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label for="smtpauth">SMS Campaign Bulk Upload List</label>&nbsp;<sup style="color: red;"><strong>*</strong></sup>
                                            <select class="form-select" id="smscampID" name="smscampID">
                                                <option value="">Select SMS Campaign Bulk Upload</option>
                                                <?php
                                                $campignList = select_query($con, "sms_campaign", "", "`deletes` = '0' ORDER BY `id` DESC", "", "");
                                                if ($campignList['nr'] > 0) {
                                                    foreach ($campignList['result'] as $key => $value) {
                                                ?>
                                                        <option value="<?= $value['id']; ?>"><?= $value['campaign_name']; ?></option>
                                                <?php
                                                    }
                                                }

                                                ?>


                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label for="smtpauth">Select Batch</label>&nbsp;<sup style="color: red;"><strong>*</strong></sup>
                                            <select class="form-select" id="smsDownloadBatch" name="smsDownloadBatch">
                                                <option value="">Select Download Batch</option>
                                                <?php

                                                for ($i = 1; $i <= 100; $i++) {
                                                ?>
                                                    <option value="<?= $i; ?>"><?= 'Batch ' . $i; ?></option>
                                                <?php
                                                }
                                                ?>


                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-2 mt-5" id="upbtn">
                                        <button class="btn btn-info" type="submit">Export</button>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>

                </div>

            </div> -->


            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Export Customer List</strong></h3>
                        </div>
                        <div class="card-body">
                            <div class="row">





                                <!-- <form method="POST" id="exportDexcel"> -->
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="smtpauth"> Select SMS Campaign Bulk Upload List</label>&nbsp;<sup style="color: red;"><strong>*</strong></sup>
                                        <select class="form-select" id="smscampID" name="smscampID">
                                            <option value="">Select SMS Campaign Bulk Upload</option>
                                            <?php
                                            $campignList = select_query($con, "sms_campaign", "", "`deletes` = '0' ORDER BY `id` DESC", "", "");
                                            if ($campignList['nr'] > 0) {
                                                foreach ($campignList['result'] as $key => $value) {
                                            ?>
                                                    <option value="<?= $value['id']; ?>"><?= $value['campaign_name']; ?></option>
                                            <?php
                                                }
                                            }

                                            ?>


                                        </select>
                                    </div>
                                </div>


                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="smtpauth">Select Batch</label>&nbsp;<sup style="color: red;"><strong>*</strong></sup>
                                        <select class="form-select" id="smsDownloadBatch" name="smsDownloadBatch">
                                            <option value="">Select Download Batch</option>
                                            <?php

                                            for ($i = 1; $i <= 100; $i++) {
                                            ?>
                                                <option value="<?= $i; ?>"><?= 'Batch ' . $i; ?></option>
                                            <?php
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-2 mt-5" id="upbtn">
                                    <button class="btn btn-info" onclick="CollectTheBatch()" type="submit">Go</button>
                                </div>
                                <!-- </form> -->

                            </div>


                            <br>
                            <div class="row">
                                <div class="card-body">

                                    <div class="table-responsive">

                                        <table class="table table-bordered text-nowrap border-bottom" id="sms_report1" style="width:100%;">

                                            <thead>

                                                <tr>
                                                    <th class="wd-15p border-bottom-0">Batch</th>
                                                    <th class="wd-15p border-bottom-0">Count of Records</th>
                                                    <th class="wd-20p border-bottom-0">No Of Clicks</th>
                                                    <th class="wd-20p border-bottom-0">No of Register</th>
                                                    <th class="wd-20p border-bottom-0">No of Ticket</th>
                                                    <!-- <th class="wd-20p border-bottom-0">Action</th> -->
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



<!-- <div class="modal fade" id="addagent1" aria-hidden="true">

    <div class="modal-dialog " role="document">

        <div class="modal-content modal-content-demo">

            <div class="modal-header">

                <h6 class="modal-title">Create Campaign </h6>

                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">×</span>

                </button>

            </div>

            <div class="modal-body">
                <form>
                    <div id="createformerror"></div>
                    <div class="form-group">
                        <label for="startDate">Name :</label>
                        <input type="text" class="form-control" id="c_name" required>
                    </div>

                    <div class="form-group">
                        <label for="startDate">Message :</label>
                        <input type="text" class="form-control" id="c_message" required>
                    </div>

                    <div class="form-group">
                        <label for="endDate">UTM url:</label>
                        <input type="text" class="form-control" id="c_url" required>
                    </div>


                    <button type="button" class="btn btn-success" onclick="create_sms()">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div> -->







<script>
    var origin = window.location.origin;
    var $path = "http://admin.nationaldraw.org/sms_no_of_register";
    var url = origin + "/ajax/service/report_services.php";
    var ajax_url = origin + "/ajax/service/sms_campaign_services.php";







    function generateCodes() {
        // Get the file input
        var fileInput = document.getElementById('fileInput');

        // Create a FormData object to handle the file upload
        var formData = new FormData();
        formData.append('method', 'Generate_Codes');
        formData.append('file', fileInput.files[0]); // Append the actual file data

        // Make an AJAX request using jQuery
        $.ajax({
            url: origin + "/ajax/service/sms_campaign_services.php",
            type: 'POST',
            data: formData,
            processData: false, // Important to prevent jQuery from processing the data
            contentType: false, // Important for FormData
            success: function(data) {
                // Handle the response from the server
                alert(data);
            },
            error: function(error) {
                // Handle any errors that occurred during the AJAX request
                console.error('AJAX Error:', error.responseText);
            }
        });
    }

    // Create SMS Campign 
    function create_sms() {
        var name = $('#c_name').val().trim();
        var mesg = $('#c_message').val().trim();
        var url = $('#c_url').val().trim();

        if (name === '') {
            toast('error', 'Kindly enter the name');
            return false;
        } else if (mesg === '') {
            toast('error', 'Kindly enter the message');
            return false;
        } else if (url === '') {
            toast('error', 'Kindly enter the URL');
            return false;
        } else {
            var formData = new FormData();
            formData.append('method', 'campaign_create');
            formData.append('name', $('#c_name').val());
            formData.append('message', $('#c_message').val());
            formData.append('url', $('#c_url').val());

            $.ajax({
                url: origin + "/ajax/service/sms_campaign_services.php",
                type: 'post',
                data: formData,
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response != "") {
                        if (response.type == 0) {
                            // $(`#addagent1`).modal('hide');

                            // Successful response 
                            toast('success', response.result);

                            location.reload();
                        } else {
                            $(`#addagent1`).modal('hide')
                            toast('error', response.result);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    // toast('error', 'An error occurred during the request');
                },
                processData: false,
                contentType: false
            });
        }
    }

    // Upload the Customers 
    function UploadReport() {

        let file = document.getElementById('networkreport').value;
        let smsCamID = $('#smtpauth').val();
        let smsUploadBatch = $(`#smsUploadBatch`).val();
        if (smsCamID == '') {
            toast('error', 'Kindly select the SMS Campaign Bulk Upload');
            return false;
        }
        if (smsUploadBatch == '') {
            toast('error', 'Kindly select upload batch');
            return false;
        }


        if (file != '') {
            var formData = new FormData();
            formData.append('method', 'sms_campign_link_generate');

            formData.append('mfile', networkreport.files[0]);
            formData.append('smsCamID', smsCamID);
            formData.append('smsUploadBatch', smsUploadBatch);
            var btn = document.getElementById('upbtn').innerHTML;
            document.getElementById('upbtn').innerHTML = `<div class="spinner-grow text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                            </div>
                            <div class="spinner-grow text-secondary" role="status">
                            <span class="sr-only">Loading...</span>
                            </div>
                            <div class="spinner-grow text-success" role="status">
                            <span class="sr-only">Loading...</span>
                            </div>`;

            var i = 1;
            var sec = 180;
            var t = setInterval(function() {
                if (i == sec) {
                    document.getElementById('upbtn').innerHTML = btn;
                    toast('success', 'Kindly Reupload The File!');
                    clearInterval(t);
                }
            });


            $.ajax({

                url: origin + '/xlsx/smsCampignUpload.php',
                type: 'POST',
                data: formData,

                success: function(data) {

                    var response = JSON.parse(data);
                    if (response != "") {

                        if (response.type == 1) {
                            clearInterval(t);
                            document.getElementById('upbtn').innerHTML = btn;
                            toast('success', response.result);
                        } else {
                            clearInterval(t);
                            document.getElementById('upbtn').innerHTML = btn;
                            toast('error', response.result);
                        }
                    }
                },
                processData: false,
                contentType: false
            });
        } else {
            toast('error', 'Select the file.');
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
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        Toast.fire({
            icon: icon,
            title: message
        });
    }

    $('#exportDexcel').submit(function(event) {
        // Prevent the default form submission
        event.preventDefault();
        let smscampID = $(`#smscampID`).val();
        if (smscampID == '') {
            toast('error', 'Kindly select the SMS Campaign Bulk Upload');
            return false;
        }

        let smsDownloadBatch = $(`#smsDownloadBatch`).val();
        if (smsDownloadBatch == '') {
            toast('error', 'Kindly select the download batch');
            return false;
        }
        // Your custom logic goes here
        // For example, you can perform an AJAX request
        // or manipulate the form data before submission

        // Finally, submit the form programmatically
        $(this).unbind('submit').submit();
    });

    function CollectTheBatch() {
        let smscampID = $(`#smscampID`).val();
        let smsUploadBatch = $(`#smsDownloadBatch`).val();
        if (smscampID == '') {
            toast('error', 'Kindly select the SMS Campaign Bulk Upload');
            return false;
        }
        if (smsUploadBatch == '') {
            toast('error', 'Kindly select upload batch');
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
                    method: 'campBathList',
                    smscampID: smscampID,
                    smsDownloadBatch: $(`#smsDownloadBatch`).val()
                    // agdate: formdate,
                    // todate: todate,
                    // campID: smtpauth,
                    // statusget: $('#statusget').val()
                }
            },
            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                // {
                //     extend: 'copyHtml5',
                //     title: title
                // },
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
                    data: null,
                    render: function(data, type, row, meta) {
                        return `Batch ${data.batch}`

                    }
                },
                {


                    data: null,
                    render: function(data, type, row, meta) {
                        return `<form method="POST">
                                <input type="hidden" name="fileName" value="${$('#smscampID option:selected').text()}">
                                <input type="hidden" name="smscampID" value="${smscampID}">
                                <input type="hidden" name="smsDownloadBatch" value="${data.batch}">
                                <button type="submit" class="btn btn-light"><i class="fa fa-download"></i>&nbsp;&nbsp;${data.count}</button>
                                </form>`
                    }
                },
                // {
                //     data: null,
                //     render: function(data, type, row, meta) {
                //         return `${data.totalClick}`

                //     }
                // },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return `<form method="POST">
                                <input type="hidden" name="fileName1" value="${$('#smscampID option:selected').text()}">
                                <input type="hidden" name="smscampID1" value="${smscampID}">
                                <input type="hidden" name="smsDownloadBatch1" value="${data.batch}">
                                <button type="submit" class="btn btn-light"><i class="fa fa-download"></i>&nbsp;&nbsp;${data.totalClick}</button>
                                </form>`
                    }
                },

                {
                    data: null,
                    render: function(data, type, row, meta) {

                        return `                    
                        <a href="/smsnoofregister/list/${smscampID}/${data.batch}"  target="blank"><button class="btn btn-light" >${data.userCount}</button></a>

                        `
                    }
                },
                //  <a href="' . $path . '"  target="blank"><button class="btn"><i  style="color: lime;" class="fa fa-eye"></i> View</button></a>
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return `                    
                        <a href="/smsnoofticket/list/${smscampID}/${data.batch}"  target="blank"><button class="btn btn-light" >${data.ticket_count}</button></a>

                        `
                    }
                }

            ],

        });
    }

    function NewTab(path) {

        // var_dump('ghedfbjhbjehjdhj');

        var origin = window.location.origin;

        window.open(

            origin + path, "_blank");

    }

    function viewagenthistory(id) {

        var path = '/sms_no_of_register/' + id;

        NewTab(path);


    }
</script>