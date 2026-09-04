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

    #mytext {

        width: 361px;

        height: 63px;

        border: 0px;

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

    .scrollable-cell {
        max-height: 100px;
        max-width: 150px;
        overflow-y: auto;
        white-space: pre-wrap;
        background-color: #f1f1f1;
        /* Light gray background */
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #ddd;
        font-size: 13px;
        line-height: 1.4;
    }
</style>

<script>
    window.onload = function () {

        var page_origin = window.location.origin;

        let anchor = document.getElementById("anchor");

        anchor.href = page_origin;

    }
</script>


<div class="main-content app-content mt-0">

    <div class="side-app">

        <input type="hidden" id="tabID" value="agents">

        <!-- CONTAINER -->

        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->

            <div class="page-header">

                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left"
                            onclick="history.go(-1)" aria-hidden="true"></i></a>Bulk Whatsapp Message</h1>

                <div>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page">Bulk Whatsapp Message</li>

                    </ol>

                </div>

            </div>

            <!-- PAGE-HEADER END -->

          <!-- ROW-1 -->
                <div class="row">
                    <div class="col-12">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Left Side -->
                                    <div class="col-lg-6">
                                        <!-- Upload Excel -->
                                        <div class="mb-3">
                                            <label for="sheetupload_file">Upload Excel Sheet <span style="color:red;">*</span></label>
                                            <input type="file" name="sheetupload_file" id="sheetupload_file"
                                                class="form-control" accept=".xls,.xlsx">
                                        </div>

                                        <!-- Sample Excel Download Button -->
                                        <div class="mb-3">
                                            <a href="assets/images/Example_sheet.xlsx" download class="btn btn-sm btn-success mt-2">
                                                Download Example File
                                            </a>
                                        </div>

                                        <!-- Whatsapp Campaign Name -->
                                        <div class="mb-3">
                                            <label for="whatapp_campain">Whatsapp Campaign Name <span style="color:red;">*</span></label>
                                            <input type="text" name="whatapp_campain" id="whatapp_campain"
                                                class="form-control" placeholder="Enter Whatsapp Campaign Name" maxlength="30">
                                        </div>

                                        <!-- Image Upload under Excel (Optional) -->
                                        <!--
                                        <div class="mb-3">
                                            <label for="image_upload">Image Upload <small class="text-muted">(Optional)</small></label>
                                            <input type="file" name="image_upload" id="image_upload" class="form-control"
                                                accept=".png, .jpeg, .jpg, .gif, .svg, image/png, image/jpeg, image/jpg, image/gif, image/svg+xml">
                                        </div>
                                        -->
                                    </div>

                                    <!-- Right Side -->
                                    <div class="col-lg-6">
                                        <!-- WhatsApp Message Template -->
                                        <div class="mb-3">
                                            <label for="whatsapp_templates">WhatsApp Message Template <span style="color:red;">*</span></label>
                                            <textarea name="whatsapp_templates" id="whatsapp_templates" class="form-control"
                                                rows="10" maxlength="1000" placeholder="Enter WhatsApp template..."></textarea>
                                        </div>

                                        <!-- Visit Now Textbox (Optional) -->
                                        <!--
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="visit_textbox">Visit Now Text <small class="text-muted">(Optional)</small></label>
                                                <input type="text" name="visit_textbox" id="visit_textbox"
                                                    class="form-control" placeholder="Enter text" maxlength="30">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="visit_textbox_url">Visit Now URL <small class="text-muted">(Optional)</small></label>
                                                <input type="text" name="visit_textbox_url" id="visit_textbox_url"
                                                    class="form-control" placeholder="Enter URL" maxlength="30">
                                            </div>
                                        </div>
                                        -->

                                        <!-- Textbox URL (Optional) -->
                                        <!--
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="textbox_url">Text Box <small class="text-muted">(Optional)</small></label>
                                                <input type="text" name="textbox_url" id="textbox_url"
                                                    class="form-control" placeholder="Enter URL" maxlength="30">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="textbox_1">Text Box URL <small class="text-muted">(Optional)</small></label>
                                                <input type="text" name="textbox_1" id="textbox_1"
                                                    class="form-control" placeholder="Enter URL" maxlength="30">
                                            </div>
                                        </div>
                                        -->
                                    </div>

                                    <!-- Upload Button Centered -->
                                    <div class="col-12 d-flex justify-content-center mt-3">
                                        <button type="submit" id="smslogsearch" class="btn btn-info px-5" onclick="uploadsheet()">
                                            Upload
                                        </button>
                                    </div>
                                </div> <!-- row -->
                            </div> <!-- card-body -->
                        </div> <!-- card -->
                    </div> <!-- col-12 -->
                </div> <!-- row -->


            <!-- ROW-1 END -->

            <!-- ROW-4 -->

            <div class="row row-sm">

                <div class="col-lg-12">

                    <div class="card">

                        <div class="card-header">
                            <div class="row w-100">
                                <div class="col-lg-4 d-flex align-items-center">
                                    <h3 class="card-title me-3"><strong>Bulk Whatsapp Message</strong></h3>
                                    <button type="button" id="smslogsearch" class="btn btn-success px-4" onclick="showdata()">
                                        Refresh
                                    </button>
                                </div>
                              <!-- ⬇️ Put the dropdown here -->
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="campaignFilter"><strong>Filter by Campaign</strong></label>
                                        <select id="campaignFilter" class="form-select" style="max-width: 300px;">
                                            <option value="">All Campaigns</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered text-nowrap border-bottom" id="WhatsApp_message"
                                    style="width:100%;">

                                    <thead>

                                        <tr>
                                            <th class="wd-15p border-bottom-0">S.No</th>
                                            <th class="wd-15p border-bottom-0">Instance Id</th>
                                            <th class="wd-20p border-bottom-0">Receiver No.</th>
                                            <th class="wd-20p border-bottom-0">Campaign Name</th>
                                            <th class="wd-20p border-bottom-0">Message</th>
                                            <th class="wd-15p border-bottom-0">Name</th>
                                            <!-- <th class="wd-20p border-bottom-0">IP Address</th> -->
                                            <th class="wd-15p border-bottom-0">Status</th>
                                            <th class="wd-15p border-bottom-0">Date & Time</th>
                                            <!-- <th class="wd-15p border-bottom-0">Action</th>  -->
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

            <!-- ROW-4 END -->

        </div>

        <!-- CONTAINER END -->

    </div>

</div>

<script>

    var origin = window.location.origin;
    var url = origin + "/ajax/service/whatsapp_bulk_message.php";

    $(function () {

        showdata();
    });


    function uploadsheet() {
       var file = $('#sheetupload_file')[0].files[0];
        var fileInput = $('#sheetupload_file');
        var message = $('#whatsapp_templates').val();
        var messageInput = $('#whatsapp_templates');
        var whatapp_campain = $('#whatapp_campain').val();
        var campainInput = $('#whatapp_campain');
        // var imageFile = $('#image_upload')[0].files[0];

        if (!file) {
            alert("Please select a file.");
            fileInput.focus();
            fileInput.attr('style', 'border: 1px solid red; box-shadow: 0 0 3px red;');
            return;
        } else if (file.size > 20 * 1024 * 1024) { // 20MB in bytes
            alert("File size must be 20MB or less.");
            fileInput.focus();
            fileInput.attr('style', 'border: 1px solid red; box-shadow: 0 0 3px red;');
            return;
        } else {
            fileInput.removeAttr('style');
        }

        if (!message) {
            alert("Please select a WhatsApp Template Message");
            messageInput.focus();
            messageInput.attr('style', 'border: 1px solid red; box-shadow: 0 0 3px red;');
            return;
        } else {
            messageInput.removeAttr('style');
        }

        if (!whatapp_campain) {
            alert("Please Give a Whatsapp Campain Name");
            campainInput.focus();
            campainInput.attr('style', 'border: 1px solid red; box-shadow: 0 0 3px red;');
            return;
        } else {
            campainInput.removeAttr('style');
        }


        var formData = new FormData();
        formData.append("method", "upload_sheet");
        formData.append("sheetupload_file", file);
        formData.append("whatsapp_temp", message);
        formData.append("whatapp_campain", whatapp_campain);
        // formData.append("visit_textbox", $('#visit_textbox').val());
        // formData.append("visit_textbox_url", $('#visit_textbox_url').val());
        // formData.append("textbox_url", $('#textbox_url').val());
        // formData.append("textbox_1", $('#textbox_1').val());
        // formData.append("image_upload", imageFile);

        $.ajax({
            url: origin + "/ajax/service/whatsapp_bulk_message.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
           success: function (response) {
                alert(response.message);

                if (response.status == 'success') {
                      location.reload();

                    // Optionally clear the form fields before reload
                    // $('#sheetupload_file').val('');
                    // $('#whatsapp_templates').val('');
                    // $('#whatapp_campain').val('');
                    // $('#visit_textbox').val('');
                    // $('#visit_textbox_url').val('');
                    // $('#textbox_url').val('');
                    // $('#textbox_1').val('');
                    // $('#image_upload').val('');
                }
            },
            error: function () {
                alert("Error uploading file.");
            }
        });
    }
    

   function showdata() {
    if ($.fn.DataTable.isDataTable('#WhatsApp_message')) {
        $('#WhatsApp_message').DataTable().clear().destroy();
    }

    var table = $("#WhatsApp_message").DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: url,
            type: "POST",
            data: {
                method: "WhatsApp_report"
            },
            dataSrc: function (json) {
                populateCampaignFilter(json);
                return json;
            }
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                },
                title: "S.No"
            },
            { data: "from_whatsapp" },
            { data: "to_whatsapp" },
            { data: "whatapp_campain" },
            {
                data: "details",
                render: function (data) {
                    return `<div class="scrollable-cell">${data}</div>`;
                }
            },
            { data: "name" },
            { data: "status" },
            { data: "created_at" },
        ]
    });

    // Filter by campaign
    $('#campaignFilter').on('change', function () {
        table.column(3).search(this.value).draw(); // 3 = whatapp_campain column index
    });
}

// Populate dropdown with distinct campaigns
function populateCampaignFilter(data) {
    let campaigns = new Set();

    data.forEach(row => {
        if (row.whatapp_campain && row.whatapp_campain.trim() !== "") {
            campaigns.add(row.whatapp_campain.trim());
        }
    });

    let select = $('#campaignFilter');
    select.empty();
    select.append(`<option value="">All Campaigns</option>`);

    [...campaigns].sort().forEach(campain => {
        select.append(`<option value="${campain}">${campain}</option>`);
    });
}

</script>