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
    
        /* Custom styles for the WhatsApp preview */
    .whatsapp-container {
        background-color: #f0f2f5;
        border-radius: 8px;
        padding: 15px;
        font-family: Arial, sans-serif;
        border: 1px solid #ddd;
    }
    .whatsapp-bubble {
        max-width: 90%;
        background-color: #dcf8c6; /* WhatsApp sent message color */
        border-radius: 8px 8px 0 8px; /* Rounded corners */
        padding: 8px 10px;
        margin-left: auto; /* Aligns to the right (sent message) */
        box-shadow: 0 1px 0.5px rgba(0, 0, 0, 0.13);
        word-wrap: break-word;
    }
    .whatsapp-content {
        font-size: 14px;
        line-height: 1.4;
        color: #111b21;
    }
    .whatsapp-content span.placeholder {
        background-color: #a4e46d;
        padding: 1px 3px;
        border-radius: 3px;
        font-weight: 500;
    }
    .whatsapp-timestamp {
        font-size: 10px;
        color: #667781;
        text-align: right;
        margin-top: 2px;
        line-height: 1;
    }
    .whatsapp-header {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 5px;
        color: #0c825a;
    }
    
    /* Overlay */
    .wa-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.6);
        z-index: 99999;
        padding: 20px;
        overflow-y: auto;
    }
    
    /* Centered WhatsApp-box */
    .wa-dialog {
        max-width: 420px;
        width: 100%;
        margin: auto;
        background: #f0f0f0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 25px rgba(0,0,0,0.25);
        animation: waPop 0.25s ease;
    }
    
    /* Top Header */
    .wa-header {
        background: #075e54;
        color: white;
        padding: 14px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .wa-title {
        font-size: 15px;
        font-weight: 600;
    }
    
    /* Close Button */
    .wa-close {
        cursor: pointer;
        font-size: 22px;
        font-weight: bold;
    }
    
    /* Chat Area */
    .wa-body {
        padding: 0;
        max-height: 70vh;
        overflow-y: auto;
        background: #e5ddd5;
    }
    
    .wa-chat {
        padding: 20px;
    }
    
    /* WhatsApp bubble */
    .wa-bubble {
        background: #dcf8c6;
        padding: 10px 14px;
        border-radius: 10px 10px 0 10px;
        max-width: 85%;
        margin-bottom: 15px;
        font-size: 14px;
        line-height: 1.5;
        word-wrap: break-word;
        position: relative;
    }
    
    .wa-time {
        font-size: 11px;
        color: #5a5a5a;
        text-align: right;
        margin-top: 5px;
    }
    
    /* Smooth animation */
    @keyframes waPop {
        from { transform: scale(0.8); opacity: 0; }
        to   { transform: scale(1); opacity: 1; }
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
                                        
                                        <div class="mb-3" id="templateTypeWrapper">
                                            <label class="form-label d-block">Template Type <span style="color:red;">*</span></label>
                                            <div class="form-check form-check-inline">
                                                <!-- Input for Template -->
                                                <input class="form-check-input" type="radio" name="template_type" id="typeTemplate" value="template" checked>
                                                <label class="form-check-label" for="typeTemplate">
                                                    Template
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <!-- Input for Manual -->
                                                <input class="form-check-input" type="radio" name="template_type" id="typeManual" value="manual">
                                                <label class="form-check-label" for="typeManual">
                                                    Manual
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3" id="templateDropdownWrapper">
                                            <label for="template_select" class="form-label">Templates <span style="color:red;">*</span></label>
                                            <select class="form-select" name="template_select" id="template_select">
                                                <option value="" disabled selected>Loading Templates...</option>
                                                
                                            </select>
                                        </div>
                                        
                                        <div id="dynamicFieldsContainer" class="mt-4">
                                            <!-- Input boxes for {{ONE}}, {{TWO}}, etc., will be inserted here -->
                                        </div>
                                        
                                        <!-- NEW: Template Preview Area -->
                                        

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
                                        <div class="mb-3" id="manual_temp" style="display:none;">
                                            <label for="whatsapp_templates">WhatsApp Message Template <span style="color:red;">*</span></label>
                                            <textarea name="whatsapp_templates" id="whatsapp_templates" class="form-control"
                                                rows="10" maxlength="1000" placeholder="Enter WhatsApp template..."></textarea>
                                        </div>
                                        
                                        <div class="mt-5 temp_pre">
                                            <h5 class="text-secondary">Template Preview</h5>
                                            <div id="templatePreviewWrapper" class="whatsapp-container">
                                                <div class="text-center text-muted p-5">Select a template to see the preview.</div>
                                            </div>
                                        </div>

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

<div id="waModal" class="wa-modal">
    <div class="wa-dialog">
        <div class="wa-header">
            <span class="wa-title">WhatsApp Message Preview</span>
            <span class="wa-close">&times;</span>
        </div>
        <div class="wa-body">
            <div class="wa-chat">
                <div id="waMessageContent"></div>
            </div>
        </div>
    </div>
</div>



<script>

    var origin = window.location.origin;
    var url = origin + "/ajax/service/whatsapp_bulk_message.php";

    $(function () {
    
        showdata();
        fetchTemplates();
    });
    
    var dropdownWrapper = $('#templateDropdownWrapper');
    var dropdown = $('#template_select');
    var dynamicFieldsContainer = $('#dynamicFieldsContainer');
    var templatePreviewWrapper = $('#templatePreviewWrapper'); 
    var EXCEL_VAR_MARKER = '[EXCEL_VAR_';
    let templatesLoaded = false;
    
    $('input[name="template_type"]').on('change', handleTemplateChange);
    
    function formatWhatsAppMessage(msg) {
        return msg
            .replace(/\\r\\n/g, "<br>")          
            .replace(/\\n/g, "<br>")             
            .replace(/\\r/g, "<br>")             
            .replace(/\*(.*?)\*/g, "<b>$1</b>")
            .replace(/_(.*?)_/g, "<i>$1</i>")
            .replace(/```(.*?)```/g, "<code>$1</code>");
    }

    $(document).on("click", ".whatsapp-preview-trigger", function () {
        let msg = $(this).data("msg");
    
        let formatted = formatWhatsAppMessage(msg);
    
        $("#waMessageContent").html(`
            <div class="wa-bubble">
                ${formatted}
                <div class="wa-time">
                    ${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                </div>
            </div>
        `);
    
        $("#waModal").fadeIn(150);
    });

    
    // Close popup
    $(document).on("click", ".wa-close, #waModal", function (e) {
        if (e.target !== this) return;
        $("#waModal").fadeOut(150);
    });

    
    // close modal
    $(".wa-close").on("click", function () {
        $("#whatsappPreviewModal").fadeOut();
    });


    function handleTemplateChange() {
        const selectedType = $('input[name="template_type"]:checked').val();
        
        templatePreviewWrapper.html('<div class="text-center text-muted p-5">Select a template to see the preview.</div>');

        if (selectedType === 'template') {
            dropdownWrapper.slideDown(200); // Smoothly show the dropdown field
            $('#dynamicFieldsContainer').slideDown(200); // Smoothly show the dropdown field
            $('.temp_pre').slideDown(200); // Smoothly show the dropdown field
            $('#manual_temp').slideUp(200);
            // dropdownWrapper.slideDown(200); // Smoothly show the dropdown field
            fetchTemplates(); // Fetch or re-fetch templates
        } else {
            dropdownWrapper.slideUp(200);
            $('#dynamicFieldsContainer').slideUp(200);
            $('#manual_temp').slideDown(200);
            $('.temp_pre').slideUp(200);
        }
    }
    
    dropdown.on('change', function() {
        fetchTemplateDetails($(this).val());
    });
    
    function fetchTemplates() {
        if (templatesLoaded) {
            console.log("Templates already loaded, skipping AJAX.");
            return;
        }

        dropdown.html('<option value="" disabled selected>Loading Templates...</option>');

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                method: "fetch_wa_temp"
            },
            dataType: 'json',
            success: function(response) {
                // Assuming the response is an array of objects: 
                // [{id: 't1', name: 'Order Confirmation'}, ...]
                
                // Clear the dropdown and add the default option
                dropdown.empty();
                dropdown.append($('<option>', {
                    value: '',
                    text: 'Select a Template',
                    disabled: true,
                    selected: true
                }));

                // Append new options based on API data
                if (response && response.length > 0) {
                    $.each(response, function(index, template) {
                        dropdown.append($('<option>', {
                            value: template.id,
                            text: template.name
                        }));
                    });
                    templatesLoaded = true;
                    console.log("Templates loaded successfully.");
                } else {
                    dropdown.append($('<option>', { value: '', text: 'No Templates Available', disabled: true }));
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching templates:", status, error);
                dropdown.html('<option value="" disabled selected>Error loading templates.</option>');
            }
        });
    }
    
    function generateVariableInputs(count, body) {
            dynamicFieldsContainer.empty(); // Clear existing fields

            if (count > 0) {
                let fieldsHtml = '<h6>Dynamic Variables Required:</h6>';
                let excelValue = "Name";
                let index = 1;

                // Add a note about Excel columns
                fieldsHtml += `
                    <div class="alert alert-danger py-2 small mb-4">
                        <p class="mb-0"><strong>Excel Column Mapping:</strong></p>
                        <ul>
                            <li><strong>${excelValue}</strong> will be replaced with <strong>{{VAR${index}}}</strong>.</li>
                        </ul>
                    </div>
                `;


                for (let i = 1; i <= count; i++) {
                    const varName = `{{VAR${i}}}`; 
                    const inputId = `var_value_${i}`;
                    const inputName = `var_value_${i}`; 

                    let labelText = `${varName} (Excel Column Index)`;
                    let placeholderText = `Enter Index Value`;
                    let isExcelMapped = true;
                    let isRequired = '';
                    
                    if (isExcelMapped) {
                        fieldsHtml += `
                            <div class="mb-3">
                                <label class="form-label text-muted">${labelText}</label>
                                <input type="text" class="form-control var-input" id="${inputId}" name="${inputName}" placeholder="${placeholderText}" value="">
                            </div>
                        `;
                    }
                }
                dynamicFieldsContainer.html(fieldsHtml);
                dynamicFieldsContainer.slideDown(300); 

            } else if (count === 0) {
                // Static template case
                dynamicFieldsContainer.html('<div class="alert alert-success py-2 mt-2">This is a static template. No dynamic variables required.</div>');
                dynamicFieldsContainer.slideDown(300);
            } else {
                 dynamicFieldsContainer.slideUp(200).empty();
            }
        }
    
    function updateTemplatePreview(body) {
        if (!body) {
            templatePreviewWrapper.html('<div class="text-center text-muted p-5">Template body content not available.</div>');
            return;
        }

        const styledBody = body;

        const previewHtml = `
            <div class="whatsapp-bubble">
                <div class="whatsapp-content">
                    ${styledBody.replace(/\n/g, '<br>')}
                </div>
                <div class="whatsapp-timestamp">${new Date().toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit'})} ✓✓</div>
            </div>
        `;

        templatePreviewWrapper.html(previewHtml);
    }
    
    function fetchTemplateDetails(templateId) {
        if (!templateId) return;

        // Reset state
        dynamicFieldsContainer.html('<div class="text-center text-muted p-3">Loading template details...</div>').slideDown(300);
        templatePreviewWrapper.html('<div class="text-center text-muted p-5">Loading template preview...</div>');
        
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                method: "fetch_temp_details",
                id: templateId
            },
            dataType: 'json',
            success: function(data) {
                // Handle dynamic fields
                console.log(data)
                data = data.result;
                
                if (data.m_type == 'dynamic' && parseInt(data.var_count) > 0) {
                    generateVariableInputs(parseInt(data.var_count), data.body);
                } else if (data.m_type === 'static' || parseInt(data.var_count) == 0) {
                    generateVariableInputs(0);
                } else {
                    dynamicFieldsContainer.html('<div class="alert alert-warning py-2 mt-2">Template requires no variables.</div>').slideUp(300);
                }
                
                updateTemplatePreview(data.body);
            },
            error: function() {
                dynamicFieldsContainer.html('<div class="alert alert-danger py-2 mt-2">Error fetching template details.</div>').slideDown(300);
                templatePreviewWrapper.html('<div class="text-center text-muted p-5">Error loading template preview.</div>');
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
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        Toast.fire({
            icon: icon,
            title: message
        });
    }

    function uploadsheet2() {
        var file = $('#sheetupload_file')[0].files[0];
        var fileInput = $('#sheetupload_file');
        var whatapp_campain = $('#whatapp_campain').val();
        var campainInput = $('#whatapp_campain');
        const selectedType = $('input[name="template_type"]:checked').val();
        var templateId = $('#template_select').val();
        var message = $('#whatsapp_templates').val();
        var messageInput = $('#whatsapp_templates');

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

        if (!message ) {
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
    
    function uploadsheet() {
        var file = $('#sheetupload_file')[0].files[0];
        var fileInput = $('#sheetupload_file');
        var whatapp_campain = $('#whatapp_campain').val();
        var campainInput = $('#whatapp_campain');
        const selectedType = $('input[name="template_type"]:checked').val();
        var templateId = $('#template_select').val();
        var message = $('#whatsapp_templates').val();
        var messageInput = $('#whatsapp_templates');

        if (!file) {
            toast('error',"Please select an Excel file.");
            fileInput.focus().addClass('is-invalid');
            return;
        } else if (file.size > 20 * 1024 * 1024) { 
            toast('error',"File size must be 20MB or less.");
            fileInput.focus().addClass('is-invalid');
            return;
        } else {
            fileInput.removeClass('is-invalid');
        }

        // Campaign Name Validation
        if (!whatapp_campain) {
            toast('error',"Please provide a Whatsapp Campaign Name.");
            campainInput.focus().addClass('is-invalid');
            return;
        } else {
            campainInput.removeClass('is-invalid');
        }

        if (!message && selectedType == 'manual') {
            alert('error',"Please select a WhatsApp Template Message");
            messageInput.focus();
            messageInput.attr('style', 'border: 1px solid red; box-shadow: 0 0 3px red;');
            return;
        } else {
            messageInput.removeAttr('style');
        }

        if (!whatapp_campain) {
            alert('error',"Please Give a Whatsapp Campain Name");
            campainInput.focus();
            campainInput.attr('style', 'border: 1px solid red; box-shadow: 0 0 3px red;');
            return;
        } else {
            campainInput.removeAttr('style');
        }
        
        let templateData = {};
        
        if (selectedType === 'template') {
            // Template Mode Validation
            if (!templateId) {
                toast('error',"Please select a WhatsApp Template from the dropdown.");
                $('#template_select').focus().addClass('is-invalid');
                return;
            } else {
                $('#template_select').removeClass('is-invalid');
            }

            let allVarsValid = true;
            $('.var-input').each(function() {
                const varKey = $(this).attr('name');
                const varValue = $(this).val().trim();

                if (!varValue) {
                    toast('error',`Please enter a value for ${$(this).prev('label').text().replace(' *', '').trim()}.`);
                    $(this).focus().addClass('is-invalid');
                    allVarsValid = false;
                    return false;
                } else {
                    $(this).removeClass('is-invalid');
                }
                templateData[varKey] = varValue;
            });

            if (!allVarsValid) return;

            // Set message variable to the template ID for backend (as the reference)
            message = templateId; 

        } else {
            // Manual Mode Validation
            if (!message) {
                toast('error',"Please enter a WhatsApp Message Template.");
                messageInput.focus().addClass('is-invalid');
                return;
            } else {
                messageInput.removeClass('is-invalid');
            }
            // In manual mode, message is the raw text, and templateData is empty
        }
        
        var formData = new FormData();
        formData.append("method", "upload_sheet");
        formData.append("sheetupload_file", file);
        formData.append("whatapp_campain", whatapp_campain);
        formData.append("message_mode", selectedType); // Send the mode flag
        
        // This field sends either the Template ID (Template Mode) or the raw text (Manual Mode)
        formData.append("whatsapp_temp", message); 
        
        // This field sends the collected dynamic variables as a JSON string (empty if static/manual)
        formData.append("dynamic_vars_json", JSON.stringify(templateData)); 
        
        console.log(formData);
        
        $.ajax({
            url: origin + "/ajax/service/whatsapp_bulk_message.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // Assuming response is a JSON object with status and message
                toast('success', response.message || "Operation completed successfully."); 

                if (response.status == 'success') {
                    // Use a slight delay before reload to ensure the alert is seen
                    setTimeout(() => location.reload(), 500); 
                }
            },
            error: function () {
                toast('error',"Error uploading file. Please check your network connection.");
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
                    return `<div class="scrollable-cell whatsapp-preview-trigger" data-msg='${JSON.stringify(data)}' style="cursor:pointer;">${data}</div>`;
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