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

    input, select {
        border: 1px solid #CCC;
    }

    input, button {
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

    /* Fixed scrollable cell for DataTables to prevent stretching */
    .scrollable-cell {
        max-height: 80px;
        max-width: 250px; 
        overflow-y: auto;
        white-space: pre-wrap;
        background-color: #f8f9fa;
        padding: 8px;
        border-radius: 4px;
        border: 1px solid #ddd;
        font-size: 12px;
        line-height: 1.4;
    }
    
    /* Custom styles for the WhatsApp preview */
    .whatsapp-container {
        background-color: #f0f2f5;
        border-radius: 8px;
        padding: 15px;
        font-family: Arial, sans-serif;
        border: 1px solid #ddd;
        min-height: 200px;
    }
    .whatsapp-bubble {
        max-width: 90%;
        background-color: #dcf8c6;
        border-radius: 8px 8px 0 8px; 
        padding: 8px 10px;
        margin-left: auto; 
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
        <div class="main-container container-fluid">
            <div class="page-header">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>FB Whatsapp Message</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">FB Whatsapp Message</li>
                    </ol>
                </div>
            </div>
            <div class="col-12">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="row">

                        <div class="col-lg-6">
                            <div class="mb-3">
    <label for="whatsapp_number">
        WhatsApp Number(s) <span style="color:red;">*</span>
    </label>
    <textarea name="whatsapp_number" id="whatsapp_number" class="form-control" placeholder="e.g., 919876543210, 919876543211, 919876543212" style="height: 70px;"></textarea>
    <small class="text-muted" style="font-size:11px;">Enter multiple numbers separated by commas. Include country codes.</small>
</div>

                            <div class="mb-3">
                                <label for="template_select">
                                    Select WhatsApp Template <span style="color:red;">*</span>
                                </label>
                                <select class="form-select" name="template_select" id="template_select">
                                    <option value="" disabled selected>Loading Templates...</option>
                                </select>
                            </div>
                            
                            <div id="dynamicFieldsContainer"></div>

                            <div class="mb-3 mt-4">
                                <button type="button" id="sendBtn" class="btn btn-success w-100">
                                    Send WhatsApp Message
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mt-2 temp_pre">
                                <h5 class="text-secondary">Template Preview</h5>
                                <div id="templatePreviewWrapper" class="whatsapp-container d-flex align-items-center justify-content-center">
                                    <div class="text-center text-muted">Select a template to see the preview.</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div><div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                            <div class="card overflow-hidden">
                                <div class="card-body">
                                    <div class="mt-2">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6 mb-2">
                                                <span>Select Date</span> &nbsp; <span style="color:red;">*</span>
                                                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 8px 10px; border: 1px solid #ccc; width: 100%">
                                                    <i class="fa fa-calendar"></i>&nbsp;
                                                    <span></span> <i class="fa fa-caret-down"></i>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-sm-6 mb-2">
                                                <span>Select Status</span>
                                                <select id="statusget" class="form-select">
                                                    <option value="All" selected>All</option>
                                                    <option value="Unchecked">Unchecked</option>
                                                    <?php
                                                    $selectQuery = mysqli_query($con, "SELECT smsstatus FROM `smslog` WHERE `smsstatus`  != '' AND  `gateway` IN ('shiwhatsapp') GROUP BY `smsstatus` ORDER BY `smslog`.`smsstatus` ASC");
                                                    if (mysqli_num_rows($selectQuery) > 0) {
                                                        while ($row = mysqli_fetch_assoc($selectQuery)) {
                                                    ?>
                                                    <option value="<?= $row['smsstatus']; ?>">
                                                        <?= ucfirst(strtolower($row['smsstatus'])); ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="col-lg-3 col-sm-6 mb-2">
                                                <br>
                                                <button type="submit" id="smslogsearch" class="btn btn-info" onclick="searchTicket()">Go</button>
                                            </div>
                                        </div>
                                        <br>
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
                                <h3 class="card-title"><strong>WhatsApp Report</strong></h3>
                            </div>
                            <div class="col-lg-8">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="WhatsApp_report1" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Mobile Number</th>
                                            <th>Gateway Name</th>
                                            <th>Details</th>
                                            <th>Whatsapp</th>
                                            <th>IP Address</th>
                                            <th>Reference ID</th>
                                            <th>Date & Time</th>
                                            <th>Status</th>
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

<script>
    var origin = window.location.origin;
    var api_domain = "<?= rtrim(TEST_API_DOMAIN_2 ?? '', '/') ?>";
    
    var fetchTemplateUrl = origin + "/ajax/service/salse_marketing_services.php"; 
    var reportUrl = origin + "/ajax/service/datatable_services.php"; 

    $(function () {
        fetchLocalTemplates();
        createDatePricket('reportrange');
        searchTicket();
    });

    function fetchLocalTemplates() {
        var dropdown = $('#template_select');
        dropdown.html('<option value="" disabled selected>Loading Templates...</option>');

        $.ajax({
            url: fetchTemplateUrl,
            method: 'POST',
            dataType: 'json',
            data: { method: "get_all_templates" }, 
            success: function(response) {
                dropdown.empty();
                dropdown.append('<option value="" disabled selected>Select a Template</option>');
                
                let hasApproved = false;
                let templates = response.data || response; 

                if (templates && templates.length > 0) {
                    $.each(templates, function(index, template) {
                        if(template.approval_status && template.approval_status.toLowerCase() === 'approved') {
                            hasApproved = true;
                            dropdown.append($('<option>', {
                                value: template.id,
                                'data-name': template.name,
                                'data-body': template.body, 
                                'data-header-image': template.header_image || '', // NEW: Fetch Image
                                'data-buttons': template.variables_json || '',    // NEW: Fetch Buttons
                                text: template.name
                            }));
                        }
                    });
                } 

                if (!hasApproved) {
                    dropdown.html('<option value="" disabled selected>No Approved Templates Available</option>');
                }
            }
        });
    }

    $('#template_select').on('change', function() {
        var selected = $(this).find(":selected");
        var bodyText = selected.data('body') || "";
        var headerImage = selected.data('header-image') || "";
        var buttonsJson = selected.data('buttons') || "";
        
        const templatePreviewWrapper = $('#templatePreviewWrapper');
        const dynamicFieldsContainer = $('#dynamicFieldsContainer');
        
        $('#template_select').data('raw-body', bodyText);
        
        // Handle Dynamic Variables Inputs
        let matches = bodyText.match(/\{\{(\d+)\}\}/g);
        let varCount = matches ? matches.length : 0;

        if (varCount > 0) {
            let fieldsHtml = `<div class="alert alert-info py-2"><small><strong>Dynamic Template:</strong> Please fill in the variables below.</small></div>`;
            for (let i = 1; i <= varCount; i++) {
                fieldsHtml += `
                    <div class="mb-3">
                        <label class="form-label text-muted">Value for {{${i}}} <span style="color:red;">*</span></label>
                        <input type="text" class="form-control dynamic-var-input" data-var="${i}" placeholder="Enter value..." required>
                    </div>
                `;
            }
            dynamicFieldsContainer.html(fieldsHtml).hide().slideDown(200);
        } else {
            dynamicFieldsContainer.empty();
        }

        // Format Text Body
        let formattedBody = bodyText.replace(/\n/g, '<br>')
            .replace(/\{\{(\d+)\}\}/g, '<span class="placeholder">{{$1}}</span>')
            .replace(/\*(.*?)\*/g, "<b>$1</b>")
            .replace(/_(.*?)_/g, "<i>$1</i>")
            .replace(/```(.*?)```/g, "<code>$1</code>");
            
        // Build Image Header HTML
        let imageHtml = '';
        if (headerImage) {
            // Display the image cleanly inside the top of the bubble
            imageHtml = `
                <div style="margin-bottom: 8px; text-align: center;">
                    <img src="${headerImage}" style="max-width: 100%; max-height: 150px; border-radius: 6px; object-fit: cover; display: inline-block;">
                </div>
            `;
        }

        // Build WhatsApp Buttons HTML
        let buttonsHtml = '';
        if (buttonsJson) {
            try {
                let parsedJson = typeof buttonsJson === 'string' ? JSON.parse(buttonsJson) : buttonsJson;
                if (parsedJson.type && parsedJson.type !== 'none' && parsedJson.buttons && parsedJson.buttons.length > 0) {
                    
                    buttonsHtml += `<div style="display: flex; flex-direction: column; gap: 4px; margin-top: 4px; width: 100%;">`;
                    
                    parsedJson.buttons.forEach(btn => {
                        let icon = '';
                        if (btn.type === 'QUICK_REPLY') icon = '<i class="fa fa-reply" style="color:#00a884;"></i>';
                        else if (btn.type === 'URL') icon = '<i class="fa fa-external-link" style="color:#00a884;"></i>';
                        else if (btn.type === 'PHONE_NUMBER') icon = '<i class="fa fa-phone" style="color:#00a884;"></i>';

                        // Mimic WhatsApp's native button style (white bubble below main message)
                        buttonsHtml += `
                            <div style="background-color: #fff; color: #00a884; text-align: center; padding: 10px; border-radius: 8px; font-size: 14px; font-weight: 500; box-shadow: 0 1px 0.5px rgba(0,0,0,0.13); border: 1px solid #ddd;">
                                ${icon} <span style="margin-left: 5px;">${btn.text}</span>
                            </div>
                        `;
                    });
                    
                    buttonsHtml += `</div>`;
                }
            } catch (e) {
                console.error("Could not parse buttons JSON for preview.", e);
            }
        }

        // Construct Final Preview UI
        let previewHtml = `
            <div style="display: flex; flex-direction: column; align-items: flex-end; width: 100%;">
                <div class="whatsapp-bubble" style="width: 100%;">
                    ${imageHtml}
                    <div class="whatsapp-content">${formattedBody}</div>
                    <div class="whatsapp-timestamp">${new Date().toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit'})} ✓✓</div>
                </div>
                ${buttonsHtml}
            </div>
        `;
        
        templatePreviewWrapper.html(previewHtml).removeClass('justify-content-center align-items-center');
    });
// --- WHATSAPP NUMBER VALIDATION ---
    $('#whatsapp_number').on('input', function() {
        let originalValue = $(this).val();
        // Regex: Match anything that is NOT a digit (0-9), comma (,), or space (\s)
        let sanitizedValue = originalValue.replace(/[^0-9,\s]/g, '');

        if (originalValue !== sanitizedValue) {
            $(this).val(sanitizedValue); // Instantly remove the invalid character
            
            // Show toast notification
            Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            }).fire({
                icon: 'error',
                title: 'Only numbers, commas, and spaces are allowed.'
            });
        }
    });
    // ----------------------------------

    $("#sendBtn").click(function(){
        // ... (your existing sendBtn code continues here)
        var numberString = $("#whatsapp_number").val().trim();
        var selectedOption = $('#template_select').find(":selected");
        
        var templateName = selectedOption.data('name'); 
        var templateLanguage = 'en_US'; 
        var rawBody = $('#template_select').data('raw-body'); 

        if(!numberString) { Swal.fire({ icon: 'error', title: 'Check', text: 'Please enter at least one WhatsApp Number.' }); return; }
        if(!templateName) { Swal.fire({ icon: 'error', title: 'Check', text: 'Please select a WhatsApp Template.' }); return; }

        var finalBody = rawBody;
        var allVarsValid = true;
        var parametersArray = [];
        
        $('.dynamic-var-input').each(function() {
            var val = $(this).val().trim();
            var varIndex = $(this).data('var');
            
            if(!val) {
                $(this).addClass('is-invalid');
                allVarsValid = false;
            } else {
                $(this).removeClass('is-invalid');
                parametersArray.push(val); 
                finalBody = finalBody.replace('{{' + varIndex + '}}', val); 
            }
        });

        if(!allVarsValid) {
            Swal.fire({ icon: 'error', title: 'Missing Data', text: 'Please fill out all dynamic variables for this template.' });
            return;
        }

        var btn = $(this);
        var originalText = btn.text();
        btn.text('Sending Bulk Messages...').prop('disabled', true);
        
        $.ajax({
            url: api_domain + "/whatsapp/message-send", 
            type: "POST",
            dataType: "json",
            headers: { 
                "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
            },
            data: {
                mobile: numberString, // Now sending the whole comma-separated string
                template_name: templateName,
                template_language: templateLanguage,
                message_body: finalBody, 
                parameters: parametersArray 
            },
            success: function(response){
                btn.text(originalText).prop('disabled', false);

                if(response.status === true || response.status === 'success') {
                    // Check if there were partial failures in the bulk send
                    if (response.errors && response.errors.length > 0) {
                        Swal.fire({ icon: 'warning', title: 'Partial Success', text: response.message, confirmButtonColor: '#f39c12' });
                    } else {
                        Swal.fire({ icon: 'success', title: 'Success', text: response.message, confirmButtonColor: '#28a745' });
                    }
                    $("#whatsapp_number").val('');
                    $('.dynamic-var-input').val('');
                    searchTicket(); 
                } else {
                    Swal.fire({ icon: 'error', title: 'Failed', text: response.message, confirmButtonColor: '#dc3545' });
                }
            },
            error: function(xhr) {
                btn.text(originalText).prop('disabled', false);
                let errorMsg = "Network or server error.";
                if(xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.status === 500) {
                    errorMsg = "Database or server crashed. Check Network Preview.";
                }
                Swal.fire({ icon: 'error', title: 'Message Rejected', text: errorMsg, confirmButtonColor: '#dc3545' });
            }
        });
    });

    function createDatePricket(id) {
        var start = moment();
        var end = moment();

        function cb(start, end) {
            $('#' + id + ' span').html(start.format('MMM Do YY') + ' - ' + end.format('MMM Do YY'));
        }
        $('#' + id).daterangepicker({
            startDate: start,
            endDate: end,
            maxSpan: { days: 15 },
            minDate: moment().subtract(14, "days").format("MM/DD/YYYY"),
            maxDate: moment().format("MM/DD/YYYY"),
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 15 Days': [moment().subtract(14, 'days'), moment()],
            }
        }, cb);
        cb(start, end);
    }
    
    function searchTicket() {
        var startMoment = $('#reportrange').data('daterangepicker').startDate;
        var endMoment = $('#reportrange').data('daterangepicker').endDate;
        
        var formdate = startMoment.format("YYYY-MM-DD");
        var todate = endMoment.format("YYYY-MM-DD");
        var title = 'WhatsApp Reports : (' + startMoment.format("MMM Do YY") + '  to  ' + endMoment.format("MMM Do YY") + ')';

        var gatewayVal = $('#gatewayname').length ? $('#gatewayname').val() : '';

        if ($.fn.DataTable.isDataTable('#WhatsApp_report1')) {
            $('#WhatsApp_report1').DataTable().destroy();
        }

        var table = $("#WhatsApp_report1").DataTable({
            pageLength: 10,
            scrollX: true,         // FIX: Enable horizontal scrolling
            autoWidth: false,      // FIX: Prevent automatic width stretching
            order: [[6, 'desc']], 
            columnDefs: [{ type: 'date', targets: [6] }], 
            paging: true, 
            searching: true, 
            info: true,
            ajax: {
                url: reportUrl, 
                method: "POST", 
                dataSrc: function (json) { return json || []; },
                data: { 
                    method: 'WhatsAppsent_report', 
                    agdate: formdate, 
                    todate: todate, 
                    gatewayname: gatewayVal, 
                    statusget: $('#statusget').val() 
                }
            },
            dom: 'Bfrtip',
            buttons: ['pageLength', 'copy', { extend: 'excelHtml5', title: title }],
            columns: [
                { data: null, width: "10%", render: function (data) { return data && data.mobile ? data.mobile : '-'; }},
                { data: null, width: "10%", render: function (data) { return data && data.gateway ? data.gateway.charAt(0).toUpperCase() + data.gateway.slice(1) : '-'; }},
                { data: null, width: "25%", render: function (data) { 
                    // FIX: Replaced textarea with a neat, scrolling div cell
                    return data && data.details ? `<div class="scrollable-cell">${data.details}</div>` : '-'; 
                }},
                { data: null, width: "10%", render: function (data) {
                    if (!data || !data.mobile) return '-';
                    const waUrl = `https://web.whatsapp.com/send?phone=${data.mobile}&text=${encodeURIComponent(data.details || '')}`;
                    return `<div class="d-flex gap-2 align-items-start"><a href="${waUrl}" target="_blank" title="Open WhatsApp"><i class="bi bi-whatsapp text-success" style="font-size:22px;"></i></a></div>`;
                }},
                { data: null, width: "10%", render: function (data) { return data && data.ip ? data.ip : '-'; }},
                { data: null, width: "15%", render: function (data) { return data && data.reference_id ? data.reference_id : '-'; }},
                { data: null, width: "10%", render: function (data) { return data && data.datetime ? moment(data.datetime).format("DD MMM YYYY hh:mm a") : '-'; }},
                { data: null, width: "10%", render: function (data) {
                    if (!data) return '-';
                    let status = data.smsstatus ? data.smsstatus.toUpperCase() : 'UNCHECKED';
                    let color = 'green';
                    if (status === 'UNCHECKED') color = 'blueviolet';
                    if (status === 'FAILED') color = '#dc3545';
                    return `<span style="color: ${color}; font-weight: bolder;">${status}</span>`; 
                }},
            ],
        });
    }
    function changestatus(val) { $("#statusget").val('All').change(); }
</script>