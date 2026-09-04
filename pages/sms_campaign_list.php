<?php
//    Date       Developer_name      Modifications
//    01-12-2023   Devanathan  K      Development 



$pageTitle = 'SMS Campaign';
$DIR = dirname(__DIR__);
set_include_path($DIR);
require_once "xlsx/Classes/PHPExcel.php";

if (isset($_POST['smscampID']) && $_POST['smscampID'] != null) {
    $smsCamID = $_POST['smscampID'];
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $filename = 'User List.xlsx';

    $user_register = select_query($con, "sms_campaign_list", "`mobile`, `link_url`", "`campaign_id` = '$smsCamID' AND `deletes` = '0' ORDER BY `id` ASC", "", "");

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

?>

<style>
    i.fa {
        cursor: pointer;
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
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title" id="titleSMSTect">Create SMS Campaign</h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="startDate">Campaign Name</label>&nbsp;<sup style="color: red;"><strong>*</strong></sup>
                                        <input type="text" class="form-control" id="c_name" oninput="this.value = this.value.replace(/[^A-Za-z0-9. ]/g, '');" required>
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
                                        <!-- <input type="text" class="form-control" id="c_message" required> -->
                                        <textarea id="c_message" placeholder="Type your message here..." oninput="this.value = this.value.replace(/[^A-Za-z0-9. -]/g, '');"></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3 mt-5">
                                    <div id="smsCAMPBTN">
                                        <button type="button" class="btn btn-success" onclick="create_sms()">Submit</button>
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

                        <div class="card-header">



                            <div class="col-lg-4">
                                <h3 class="card-title"><strong>SMS Campaign List</strong></h3>

                            </div>





                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="sms_report1" style="width:100%;">

                                    <thead>

                                        <tr>
                                            <th class="wd-15p border-bottom-0">Name</th>
                                            <th class="wd-15p border-bottom-0">Redirect URL (UTM URL)</th>
                                            <th class="wd-20p border-bottom-0">SMS Message</th>
                                            <th class="wd-20p border-bottom-0">Created AT</th>
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
            <!-- Upload Customer List  -->
            <!-- <div class="row row-sm">

                <div class="col-lg-12">

                    <div class="card">

                        <div class="card-header">


                            <div class="row">
                                <div class="col-12 mb-2">
                                    <h3 class="card-title"><strong>Upload Customer List</strong></h3>
                                </div>
                                <div class="col-lg-5 col-md-5">
                                    <label for="networkreport" class="form-label">Choose Customer List</label>&nbsp;<sup style="color: red;"><strong>*</strong></sup>
                                    <input class="form-control" type="file" id="networkreport" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                </div>

                                <div class="col-lg-5 col-md-5">
                                    <div class="form-group">
                                        <label for="smtpauth">SMS Campaign List</label>&nbsp;<sup style="color: red;"><strong>*</strong></sup>
                                        <select class="form-select" id="smtpauth">
                                            <option value="">Select SMS Campaign</option>
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

            </div> -->

            <!-- Export the Customer List  -->
            <!-- <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">

                            <div class="row">

                                <div class="col-12 mb-2">
                                    <h3 class="card-title"><strong>Export Customer List</strong></h3>
                                </div>

                                <form method="POST" id="exportDexcel">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="form-group">
                                            <label for="smtpauth">SMS Campaign List</label>&nbsp;<sup style="color: red;"><strong>*</strong></sup>
                                            <select class="form-select" id="smscampID" name="smscampID">
                                                <option value="">Select SMS Campaign</option>
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

                                    <div class="col-lg-2 col-md-2 mt-5" id="upbtn">
                                        <button class="btn btn-info" type="submit">Export</button>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>

                </div>

            </div> -->

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
    var url = origin + "/ajax/service/report_services.php";
    var ajax_url = origin + "/ajax/service/sms_campaign_services.php";

    $(function() {
        searchTicket();
    })();

    // function generateCodes() {
    //     // Get the file input
    //     var fileInput = document.getElementById('fileInput');

    //     // Create a FormData object to handle the file upload
    //     var formData = new FormData();
    //     formData.append('method', 'Generate_Codes');
    //     formData.append('file', fileInput.files[0]); // Append the actual file data

    //     // Make an AJAX request using jQuery
    //     $.ajax({
    //         url: origin + "/ajax/service/sms_campaign_services.php",
    //         type: 'POST',
    //         data: formData,
    //         processData: false, // Important to prevent jQuery from processing the data
    //         contentType: false, // Important for FormData
    //         success: function(data) {
    //             // Handle the response from the server
    //             alert(data);
    //         },
    //         error: function(error) {
    //             // Handle any errors that occurred during the AJAX request
    //             console.error('AJAX Error:', error.responseText);
    //         }
    //     });
    // }

    // Create SMS Campign 
    function create_sms(id = 0) {
        var name = $('#c_name').val().trim();
        var mesg = $(`#c_message`).val().replace(/\s+/g, ' ').trim();
        var url = $('#c_url').val().trim();

        if (name === '') {
            toast('error', 'Kindly enter the name');
            return false;
        } else if (mesg === '') {
            toast('error', 'Kindly enter the message');
            return false;
        } else if (!$(`#c_message`).val().replace(/\s+/g, ' ').includes(' UTMURL ')) {
            toast('error', '"UTMURL" is missing in your message.');
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
            formData.append('id', id);
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
                            // $(`#addagent1`).modal('hide')
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
    // function UploadReport() {

    //     let file = document.getElementById('networkreport').value;
    //     let smsCamID = $('#smtpauth').val();

    //     if (smsCamID == '') {
    //         toast('error', 'Kindly select the SMS Campaign');
    //         return false;
    //     }

    //     if (file != '') {
    //         var formData = new FormData();
    //         formData.append('method', 'sms_campign_link_generate');

    //         formData.append('mfile', networkreport.files[0]);
    //         formData.append('smsCamID', smsCamID);

    //         var btn = document.getElementById('upbtn').innerHTML;
    //         document.getElementById('upbtn').innerHTML = `<div class="spinner-grow text-primary" role="status">
    //                         <span class="sr-only">Loading...</span>
    //                         </div>
    //                         <div class="spinner-grow text-secondary" role="status">
    //                         <span class="sr-only">Loading...</span>
    //                         </div>
    //                         <div class="spinner-grow text-success" role="status">
    //                         <span class="sr-only">Loading...</span>
    //                         </div>`;

    //         var i = 1;
    //         var sec = 180;
    //         var t = setInterval(function() {
    //             if (i == sec) {
    //                 document.getElementById('upbtn').innerHTML = btn;
    //                 toast('success', 'Kindly Reupload The File!');
    //                 clearInterval(t);
    //             }
    //         });

    //         $.ajax({

    //             url: origin + '/xlsx/smsCampignUpload.php',
    //             type: 'POST',
    //             data: formData,

    //             success: function(data) {

    //                 var response = JSON.parse(data);
    //                 if (response != "") {

    //                     if (response.type == 1) {
    //                         clearInterval(t);
    //                         document.getElementById('upbtn').innerHTML = btn;
    //                         toast('success', response.result);
    //                     } else {
    //                         clearInterval(t);
    //                         document.getElementById('upbtn').innerHTML = btn;
    //                         toast('error', response.result);
    //                     }
    //                 }
    //             },
    //             processData: false,
    //             contentType: false
    //         });
    //     } else {
    //         toast('error', 'Select the file.');
    //     }
    // }


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

    function searchTicket() {
        // var table = $('#sms_report1').DataTable();
        // table.destroy();
        // var formdate = moment($('#reportrange').data('daterangepicker').startDate._d).format("MMM Do YY");
        // var todate = moment($('#reportrange').data('daterangepicker').endDate._d).format("MMM Do YY");
        // var smtpauth = $(`#smtpauth`).val();
        // if (smtpauth == '') {
        //     toast('error', 'Kindly select the SMS Campaign');
        //     return false;
        // }


        var title = 'Campaign List';

        var table = $("#sms_report1").DataTable({
            destroy: true,
            pageLength: 10,
            // responsive: {
            //     details: {
            //         type: 'column',
            //         target: -1,
            //     }
            // },
            // columnDefs: [{
            //     targets: -1,
            //     orderable: false,
            //     searchable: true,
            //     className: 'control',
            // }, {
            //     targets: 0,
            //     orderable: false,
            //     searchable: true,
            //     className: 'selectall-checkbox',
            // }],
            // select: {
            //     style: 'multi',
            //     selector: 'td:first-child',
            // },
            order: [
                [3, 'desc']
            ],
            columnDefs: [{
                type: 'date',
                targets: [3]
            }],
            paging: true,
            searching: true,
            info: true,
            ajax: {
                url: origin + "/ajax/service/sms_campaign_services.php",
                method: "POST",
                dataSrc: "result",
                data: {
                    method: 'campList',
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
                    data: "campaign_name"
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return `<a href="${data.utm_url}" target="_blank"><i class="fa fa-external-link" aria-hidden="true" style="color: blue; font-weight: 800;">&nbsp;Link</i></a>`

                    }
                },
                // {
                //     data: "utm_url"
                // },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        let message = data.message;
                        return `<textarea readonly="">${data.message}</textarea>`
                    }
                },
                // {
                //     data: "ip"
                // },
                // {
                //     data: "reference_id"
                // },
                // {
                //     data: "datetime"
                // },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return moment(data.date).format("DD MMM YYYY hh:mm a")
                    }
                },
                // {
                //     data: "status"
                // },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return `<a target="_blank" onclick="EditCamp(${data.id},  '${data.campaign_name}', '${data.utm_url}', '${data.message}')"><i class="fa fa-pencil-square-o" aria-hidden="true" style="color: green; font-weight: 800;">&nbsp;Edit</i></a>&nbsp;<a target="_blank" onclick="deleteCamp(${data.id})"><i class="fa fa-trash" aria-hidden="true" style="color: red; font-weight: 800;">&nbsp;Delete</i></a>`
                    }
                }
            ],

        });

    }

    // $('#exportDexcel').submit(function(event) {
    //     // Prevent the default form submission
    //     event.preventDefault();
    //     let smscampID = $(`#smscampID`).val();
    //     if (smscampID == '') {
    //         toast('error', 'Kindly select the SMS Campaign');
    //         return false;
    //     }
    //     // Your custom logic goes here
    //     // For example, you can perform an AJAX request
    //     // or manipulate the form data before submission

    //     // Finally, submit the form programmatically
    //     $(this).unbind('submit').submit();
    // });

    function EditCamp(id, name, url, message) {
        if (id == '' || url == '' || name == '' || message == '') {
            toast('error', "Kindly Refresh the screen and Try again!");
            return false;
        }
        $('#c_name').val(name);
        $(`#c_message`).val(message);
        $('#c_url').val(url);
        $(`#titleSMSTect`).text(`Edit SMS Campaign`);
        $(`#smsCAMPBTN`).html(`<button type="button" class="btn btn-success" onclick="create_sms(${id})">Update</button>&nbsp;<button type="button" class="btn btn-primary" onclick="location.reload();">Cancel</button>`);
        $('html, body').animate({
            scrollTop: 0
        }, 300);
    }

    function deleteCamp(id) {
        if (id == '') {
            toast('error', "Kindly Refresh the screen and Try again!");
            return false;
        }
        var formData = new FormData();
        formData.append('method', 'campaign_delete');

        formData.append('id', id);
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
                        // $(`#addagent1`).modal('hide')
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
</script>