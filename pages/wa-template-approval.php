<?php
   //    Date        Developer_name     Modifications
   //    24.06.2025   Suriya M            Development - WaMail Templates
   $pageTitle = 'WhatsApp Templates';
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
           $col = 2;
           foreach ($user_register['result'] as $key => $value) {
               $objPHPExcel->getActiveSheet()->setCellValue('A' . $col, $value['mobile']);
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
  }
  .swal2-container {
      z-index: 999999 !important;
  }
  input, select {
      border: 1px solid #CCC;
  }
  .custom-chk {
      width: 18px;
      height: 18px;
      cursor: pointer;
      accent-color: #3b82f6;
      border: 2px solid #cbd5e1;
      border-radius: 4px;
      vertical-align: middle;
      margin: 0;
      display: inline-block;
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

  span.fa.fa-trophy.fs-14 { color: #9d8711; font-size: 19px !important; font-weight: 900; }
  div.dt-container div.dt-length select{ color: black; border: 1px solid; }
  .modal-content{ color:black; }
  
  .table-body-scroll {
      max-width: 250px; 
      max-height: 100px; 
      overflow-y: auto; 
      white-space: pre-wrap; 
      background-color: #f8f9fa; 
      padding: 8px; 
      border: 1px solid #ddd; 
      border-radius: 4px;
      font-size: 13px;
  }
  
  .minimized-name {
      max-width: 120px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      display: inline-block;
      vertical-align: bottom;
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
                        <div class="card-header">
                            <div class="col-lg-4">
                                <h3 class="card-title"><strong>WhatsApp Templates</strong></h3>
                            </div>
                            <div class="col-lg-8"></div>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-end gy-3">
                                <div class="col-lg-12 d-flex justify-content-lg-end justify-content-md-start">
                                <button class="btn btn-warning text-dark fw-bold me-2" id="btnFetchSync">
                                    <i class="fa fa-refresh me-2"></i> Sync FB Templates
                                </button>
                                <a href="#" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#templateModal">
                                    <i class="fa fa-plus me-2"></i> Add Template
                                </a>
                            </div>
                            </div>

                            <div class="table-responsive mt-3">
                                <table class="table table-bordered text-nowrap border-bottom" id="templateTable" style="width:100%;">
                                    <thead>
                                        <tr>
                                          <th>ID</th>
                                          <th>Name</th>
                                          <th>Type</th>
                                          <th>Body</th>
                                          <th>Date</th> 
                                          <th>Status</th> 
                                          <th>Action</th>
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
    
  <div class="modal fade" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="templateForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add WhatsApp Template</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="whatsappCon" id="whatsappCon" value="static">
          <input type="hidden" name="whatsapp_content_type" value="message">
          <input type="hidden" name="whatsappVar" id="whatsappVar" value="0">
          
          <input type="hidden" name="variables_json" id="variables_json" value="">

          <div class="mb-3">
            <label for="name" class="form-label">Template Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="name" id="name" placeholder="e.g., trip_confirmation" required>
            <small class="text-muted" style="font-size:11px;">Name must be lowercase, numbers, and underscores only. No spaces.</small>
          </div>
        
          <div class="mb-3">
            <label for="channel" class="form-label">Template Type <span class="text-danger">*</span></label>
            <select name="channel" id="channel" class="form-select" required>
              <option value="">-- Select Type --</option>
              <option value="UTILITY">Utility</option>
              <option value="MARKETING">Marketing</option>
              <option value="AUTHENTICATION">Authentication</option>
            </select>
          </div>
          <div class="mb-3">
  <label for="header_image_file" class="form-label">Header Image (Optional)</label>
  <input type="file" class="form-control" id="header_image_file" accept="image/jpeg, image/png, image/webp">
  <input type="hidden" name="header_image" id="header_image_val" value="">
  <small class="text-muted" style="font-size:11px;">Upload a high-quality JPG/PNG. It will be sent as the template header.</small>
</div>
          <div class="mb-3">
            <label class="form-label">Message Body <span class="text-danger">*</span></label>
            <div id="editor" style="height: 150px; background-color: #fff;"></div>
            <textarea style="display:none;" name="body" id="body"></textarea>
          </div>
          
          <div class="mb-3 border p-3 bg-light ">
            <label for="button_type" class="form-label fw-bold">Interactive Buttons</label>
            <select id="button_type" class="form-select mb-2">
              <option value="none">None</option>
              <option value="QUICK_REPLY">Quick Reply (Up to 3 text buttons)</option>
              <option value="CALL_TO_ACTION">Call to Action (1 URL & 1 Phone Number)</option>
            </select>
            
            <div id="button_inputs_container" class="mt-2"></div>
          </div>
          
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save Template</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="editTemplateModal" tabindex="-1" aria-labelledby="editTemplateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="editTemplateForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Approved Template</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="edit_id" id="edit_id">
          <input type="hidden" name="whatsappCon" id="edit_whatsappCon" value="static">
          <input type="hidden" name="whatsappVar" id="edit_whatsappVar" value="0">
          <input type="hidden" name="variables_json" id="edit_variables_json" value="">

          <div class="alert alert-warning" style="font-size: 13px;">
            <i class="fa fa-info-circle"></i> <strong>Note:</strong> Editing an approved template will reset its status to <strong>In Review</strong> on Meta. The template name cannot be changed.
          </div>

          <div class="mb-3">
            <label class="form-label">Template Name <small class="text-danger">(Cannot be edited)</small></label>
            <input type="text" class="form-control bg-light" name="name" id="edit_name" readonly>
          </div>
        
          <div class="mb-3">
            <label for="edit_channel" class="form-label">Template Type <span class="text-danger">*</span></label>
            <select name="channel" id="edit_channel" class="form-select" required>
              <option value="UTILITY">Utility</option>
              <option value="MARKETING">Marketing</option>
              <option value="AUTHENTICATION">Authentication</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="edit_header_image_file" class="form-label">Update Header Image (Optional)</label>
            <input type="file" class="form-control" name="header_image_file" id="edit_header_image_file" accept="image/jpeg, image/png, image/webp">
            <small class="text-muted" style="font-size:11px;">Leave blank to keep your existing image, or upload a new one to replace it.</small>
          </div>

          <div class="mb-3">
            <label class="form-label">Message Body <span class="text-danger">*</span></label>
            <div id="edit_editor" style="height: 150px; background-color: #fff;"></div>
            <textarea style="display:none;" name="body" id="edit_body"></textarea>
          </div>
        
          <div class="mb-3 border p-3 bg-light">
            <label for="edit_button_type" class="form-label fw-bold">Interactive Buttons</label>
            <select id="edit_button_type" class="form-select mb-2">
              <option value="none">None</option>
              <option value="QUICK_REPLY">Quick Reply (Up to 3 text buttons)</option>
              <option value="CALL_TO_ACTION">Call to Action (1 URL & 1 Phone Number)</option>
            </select>
            <div id="edit_button_inputs_container" class="mt-2"></div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-warning text-dark fw-bold">Update & Sync to Meta</button>
        </div>
      </div>
    </form>
  </div>
</div>

 <div class="modal fade" id="syncModal" tabindex="-1" aria-labelledby="syncModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow">
      <div class="modal-header" style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
        <h5 class="modal-title text-dark fw-bold" style="font-size: 16px;">
            <i class="fa fa-cloud-download text-muted me-2"></i> Sync Missing Templates from Meta
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 14px;">
                <thead style="background-color: #f1f5f9;">
                    <tr>
                        <th style="width: 60px; text-align: center; border-bottom: 2px solid #e2e8f0;">
                            <input type="checkbox" id="selectAllSync" class="custom-chk" title="Select All">
                        </th>
                        <th style="border-bottom: 2px solid #e2e8f0; color: #475569; font-weight: 700;">TEMPLATE NAME</th>
                        <th style="border-bottom: 2px solid #e2e8f0; color: #475569; font-weight: 700;">CATEGORY</th>
                        <th style="border-bottom: 2px solid #e2e8f0; color: #475569; font-weight: 700;">STATUS</th>
                    </tr>
                </thead>
                <tbody id="syncTableBody">
                    </tbody>
            </table>
        </div>
      </div>
      
      <div class="modal-footer" style="background-color: #f8fafc; border-top: 1px solid #e2e8f0;">
        <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary fw-bold px-4" id="btnSaveSync">
            <i class="fa fa-refresh me-2"></i> Sync Selected
        </button>
      </div>
    </div>
  </div>
</div>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill-emoji@0.1.7/dist/quill-emoji.js"></script>

<script>
  // QUILL EDITOR INITIALIZATION
  const quill = new Quill('#editor', {
    theme: 'snow',
    placeholder: 'Type your message here...',
    modules: {
      toolbar: {
        container: [
          ['bold', 'italic', 'underline'],
          [{ list: 'ordered' }, { list: 'bullet' }],
          ['emoji'], 
          ['clean']
        ]
      },
      'emoji-toolbar': true,
      'emoji-textarea': false,
      'emoji-shortname': true
    }
  });

  quill.on('text-change', function() {
      document.getElementById('body').value = quill.root.innerHTML;
  });

  // NEW: QUILL EDIT EDITOR INITIALIZATION
  const editQuill = new Quill('#edit_editor', {
    theme: 'snow',
    placeholder: 'Type your message here...',
    modules: {
        toolbar: {
            container: [
                ['bold', 'italic', 'underline'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['emoji'], ['clean']
            ]
        },
        'emoji-toolbar': true,
        'emoji-textarea': false,
        'emoji-shortname': true
    }
  });

  editQuill.on('text-change', function() {
    document.getElementById('edit_body').value = editQuill.root.innerHTML;
  });
</script>

<script>
// --- GLOBAL VARIABLES (MUST BE DECLARED FIRST) ---
var origin = window.location.origin;
const baseUrl = origin + "/ajax/service/salse_marketing_services.php";
var api_domain = "<?= rtrim(TEST_API_DOMAIN_2 ?? '', '/') ?>";

const facebookApiUrl = api_domain + "/whatsapp/request-approval"; 
const facebookSyncUrl = api_domain + "/whatsapp/sync-approval"; 
const facebookDeleteUrl = api_domain + "/whatsapp/delete-template"; 
const facebookFetchMissingUrl = api_domain + "/whatsapp/fetch-missing-templates";
const facebookSyncSelectedUrl = api_domain + "/whatsapp/sync-selected-templates";
const facebookEditUrl = api_domain + "/whatsapp/edit-template"; // NEW URL

let missingTemplatesCache = []; // Store the FB data temporarily

function toast(icon, message) {
    const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false,
        timer: 5000, timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });
    Toast.fire({ icon: icon, title: message });
}

// FB SUBMIT FUNCTION
function sendToFacebookForApproval(id) {
    Swal.fire({
        title: 'Submitting...', text: 'Sending template to Facebook.',
        allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }
    });

    $.ajax({
        url: facebookApiUrl, 
        type: "POST",
        dataType: "json",
        headers: { "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()" },
        data: { template_id: id },
        success: function(response) {
            if(response.status === true || response.status === 'success') {
                let msg = response.message.replace(/PENDING/gi, 'IN REVIEW');
                Swal.fire('Success', msg, 'success');
                $('#templateTable').DataTable().ajax.reload(null, false);
            } else {
                Swal.fire('Facebook Error', response.message, 'error');
            }
        },
        error: function(xhr) {
            let msg = "Server Error";
            if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
            Swal.fire('Failed', msg, 'error');
        }
    });
}

// FB SYNC FUNCTION
function syncFacebookStatus(id) {
    Swal.fire({
        title: 'Syncing...', text: 'Checking latest status from Facebook.',
        allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }
    });

    $.ajax({
        url: facebookSyncUrl, 
        type: "POST",
        dataType: "json",
        headers: { "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()" },
        data: { template_id: id },
        success: function(response) {
            if(response.status === true || response.status === 'success') {
                let iconType = 'info';
                if (response.approval_status === 'approved') iconType = 'success';
                if (response.approval_status === 'rejected') iconType = 'error';
                
                let msg = response.message.replace(/PENDING/gi, 'IN REVIEW');
                Swal.fire('Updated', msg, iconType);
                $('#templateTable').DataTable().ajax.reload(null, false);
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function(xhr) {
            let msg = "Server Error";
            if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
            Swal.fire('Failed', msg, 'error');
        }
    });
}

// --- DOCUMENT READY WRAPPER (EXECUTES WHEN PAGE LOADS) ---
$(document).ready(function () {
    
    // ----------------------------------------------------
    // FACEBOOK SYNC MODAL LOGIC
    // ----------------------------------------------------
    $('#btnFetchSync').click(function () {
        Swal.fire({ title: 'Checking Meta...', text: 'Fetching templates not in your DB.', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        $.ajax({
            url: facebookFetchMissingUrl,
            type: "GET",
            dataType: "json",
            headers: { "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()" }, 
            success: function(response) {
                Swal.close();
                if (response.status === true && response.templates.length > 0) {
                    missingTemplatesCache = response.templates;
                    let html = '';
                    
                    response.templates.forEach((tpl, index) => {
                        let statusClass = tpl.status === 'APPROVED' ? 'bg-success' : (tpl.status === 'REJECTED' ? 'bg-danger' : 'bg-warning text-dark');
                        html += `
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="custom-chk sync-chk" value="${index}">
                                </td>
                                <td class="fw-bold text-dark">${tpl.name}</td>
                                <td><span class="badge bg-light text-dark border">${tpl.category}</span></td>
                                <td><span class="badge ${statusClass}">${tpl.status}</span></td>
                            </tr>
                        `;
                    });

                    $('#syncTableBody').html(html);
                    $('#selectAllSync').prop('checked', false); 
                    $('#syncModal').modal('show');
                } else if (response.status === true && response.templates.length === 0) {
                    Swal.fire('Up to date!', 'Your database already has all the templates from Meta.', 'info');
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Failed', 'Server Error fetching from Meta.', 'error');
            }
        });
    });

    $('#selectAllSync').change(function() {
        $('.sync-chk').prop('checked', $(this).prop('checked'));
    });

    $('#btnSaveSync').click(function() {
        let selectedIndexes = [];
        $('.sync-chk:checked').each(function() {
            selectedIndexes.push($(this).val());
        });

        if (selectedIndexes.length === 0) {
            toast('error', 'Please select at least one template to sync.');
            return;
        }

        let selectedTemplates = selectedIndexes.map(index => missingTemplatesCache[index]);

        Swal.fire({ title: 'Syncing to DB...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        $.ajax({
            url: facebookSyncSelectedUrl,
            type: "POST",
            dataType: "json",
            contentType: "application/json",
            headers: { "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()" },
            data: JSON.stringify({ templates: selectedTemplates }),
            success: function(response) {
                if (response.status === true) {
                    Swal.fire('Success!', response.message, 'success');
                    $('#syncModal').modal('hide');
                    $('#templateTable').DataTable().ajax.reload(null, false);
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Failed', 'Server Error while syncing to database.', 'error');
            }
        });
    });
    // ----------------------------------------------------

    $('#name').on('input', function() {
        let val = $(this).val();
        val = val.toLowerCase(); 
        val = val.replace(/\s+/g, '_'); 
        val = val.replace(/[^a-z0-9_]/g, ''); 
        $(this).val(val);
    });

    // Handle Dynamic Button Inputs Generation (For Add Form)
    $('#button_type').change(function() {
        let type = $(this).val();
        let html = '';

        if (type === 'QUICK_REPLY') {
            html = `
                <small class="text-muted d-block mb-1">Enter up to 3 quick reply options.</small>
                <input type="text" class="form-control mb-2 qr-input" placeholder="Button 1 (e.g., Confirm)">
                <input type="text" class="form-control mb-2 qr-input" placeholder="Button 2 (e.g., Cancel)">
                <input type="text" class="form-control qr-input" placeholder="Button 3 (Optional)">
            `;
        } else if (type === 'CALL_TO_ACTION') {
            html = `
                <small class="text-muted d-block mb-1">URL Button</small>
                <div class="row mb-3">
                    <div class="col-5"><input type="text" class="form-control cta-url-text" placeholder="Text (e.g., Track Ride)"></div>
                    <div class="col-7"><input type="url" class="form-control cta-url-link" placeholder="https://example.com/track/{{1}}"></div>
                </div>
                <small class="text-muted d-block mb-1">Phone Number Button</small>
                <div class="row">
                    <div class="col-5"><input type="text" class="form-control cta-phone-text" placeholder="Text (e.g., Call Driver)"></div>
                    <div class="col-7"><input type="text" class="form-control cta-phone-num" placeholder="+919876543210 (Include + and code)"></div>
                </div>
            `;
        }
        
        $('#button_inputs_container').html(html);
    });

    $('#templateTable').DataTable({
      scrollX: true, 
      order: [[0, 'desc']], 
      ajax: {
        url: baseUrl,
        type: 'POST',
        data: function (d) { d.method = 'get_all_templates2'; }
      },
      columns: [
        { data: 'id', width: '5%' },
        { 
          data: 'name', 
          width: '15%',
          render: function(data) {
              if(!data) return '-';
              return `<span class="minimized-name" title="${data}">${data}</span>`;
          }
        },
        { 
          data: 'channel',
          width: '10%',
          render: function (data) { return data ? data.toUpperCase() : '-'; } 
        }, 
        { 
          data: 'body',
          width: '30%',
          render: function (data, type, row) {
              let html = `<div class="table-body-scroll" style="margin-bottom: 5px;">${data ? data : '-'}</div>`;

              if (row.variables_json) {
                  try {
                      let parsedJson = JSON.parse(row.variables_json);
                      if (parsedJson.type && parsedJson.type !== 'none' && parsedJson.buttons && parsedJson.buttons.length > 0) {
                          html += `<div class="mt-2" style="display: flex; gap: 5px; flex-wrap: wrap;">`;
                          
                          parsedJson.buttons.forEach(btn => {
                              let icon = '';
                              let btnClass = 'bg-primary'; 
                              
                              if (btn.type === 'QUICK_REPLY') {
                                  icon = 'fa-reply';
                              } else if (btn.type === 'URL') {
                                  icon = 'fa-external-link';
                                  btnClass = 'bg-info text-dark';
                              } else if (btn.type === 'PHONE_NUMBER') {
                                  icon = 'fa-phone';
                                  btnClass = 'bg-success';
                              }

                              html += `<span class="badge ${btnClass}" style="padding: 6px 10px; font-weight: normal; border-radius: 4px;">
                                          <i class="fa ${icon} me-1"></i> ${btn.text}
                                       </span>`;
                          });
                          
                          html += `</div>`;
                      }
                  } catch (e) {
                      console.error("Could not parse buttons JSON for row ID: " + row.id);
                  }
              }

              return html;
          }
        },
        { 
          data: 'created_at',
          width: '10%',
          render: function(data) {
              if (!data) return '-';
              return data.split(' ')[0]; 
          }
        },
        {
          data: 'approval_status', 
          width: '10%',
          render: function (data, type, row) {
              if (!row.whatsapp_template_id) {
                  return `<span class="badge bg-secondary">N/A</span>`;
              }

              let status = data ? data.toUpperCase() : 'IN REVIEW';
              if (status === 'PENDING') status = 'IN REVIEW';
              
              let badgeClass = 'bg-secondary';
              if(status === 'APPROVED') badgeClass = 'bg-success';
              else if(status === 'IN REVIEW') badgeClass = 'bg-warning text-dark';
              else if(status === 'REJECTED') badgeClass = 'bg-danger';
              else if(status === 'PAUSED' || status === 'DISABLED') badgeClass = 'bg-dark';

              let html = `<span class="badge ${badgeClass}">${status}</span>`;
              if (status === 'REJECTED' && row.rejection_reason) {
                  html += `<br><small class="text-danger mt-1 d-block" style="max-width:150px; white-space:normal;">${row.rejection_reason}</small>`;
              }
              return html;
          }
        },
        {
          data: null,
          width: '20%',
          render: function (data, type, row) {
            let fbid = row.whatsapp_template_id ? row.whatsapp_template_id : '';
            let status = row.approval_status ? row.approval_status.toUpperCase() : '';
            
            let actionBtns = `<div style="display: flex; gap: 5px; align-items: center;">
                              <button class="btn btn-sm btn-danger btn-delete" data-id="${row.id}" data-fbid="${fbid}">Delete</button>`;
            
            if (!row.whatsapp_template_id) {
                actionBtns += `<button class="btn btn-sm btn-success btn-approve" data-id="${row.id}" data-name="${row.name}" title="Request Facebook Approval">Req FB</button>`;
            } else if (status !== 'APPROVED') {
                actionBtns += `<button class="btn btn-sm btn-info btn-sync text-white" data-id="${row.id}" title="Sync Status"><i class="fa fa-refresh"></i></button>`;
            }
            
            // Only show Edit button if it is Approved
            if (status === 'APPROVED') {
                actionBtns += `<button class="btn btn-sm btn-warning btn-edit text-dark" title="Edit Template"><i class="fa fa-pencil"></i> Edit</button>`;
            }
            
            actionBtns += `</div>`;
            return actionBtns;
          }
        }
      ]
    });

    $('#templateTable').on('click', '.btn-approve', function () {
        sendToFacebookForApproval($(this).data('id'));
    });

    $('#templateTable').on('click', '.btn-sync', function () {
        syncFacebookStatus($(this).data('id'));
    });

    $('#templateForm').submit(function (e) {
      e.preventDefault();
      document.getElementById('body').value = quill.root.innerHTML;

      const templateName = $('#name').val();
      if (!/^[a-z0-9_]+$/.test(templateName)) {
          Swal.fire('Invalid Name', 'Template name can only contain lowercase letters, numbers, and underscores.', 'error');
          return;
      }

      // --- COMPILE BUTTONS INTO JSON AND EXTRACT VARIABLES ---
      let btnType = $('#button_type').val();
      let buttonsData = { type: btnType, buttons: [] };
      let maxVar = 0; // Track the highest {{number}}

      if (btnType === 'QUICK_REPLY') {
          $('.qr-input').each(function() {
              if ($(this).val().trim() !== '') {
                  buttonsData.buttons.push({ type: 'QUICK_REPLY', text: $(this).val().trim() });
              }
          });
      } else if (btnType === 'CALL_TO_ACTION') {
          let urlText = $('.cta-url-text').val();
          let urlLink = $('.cta-url-link').val();
          if (urlText && urlLink) {
              buttonsData.buttons.push({ type: 'URL', text: urlText, url: urlLink });
              
              // Extract variables from Dynamic URL (e.g., {{1}})
              let urlMatches = urlLink.match(/\{\{(\d+)\}\}/g);
              if (urlMatches) {
                  urlMatches.forEach(v => {
                      let num = parseInt(v.replace(/\D/g, ''));
                      if (num > maxVar) maxVar = num;
                  });
              }
          }

          let phoneText = $('.cta-phone-text').val();
          let phoneNum = $('.cta-phone-num').val();
          if (phoneText && phoneNum) {
              buttonsData.buttons.push({ type: 'PHONE_NUMBER', text: phoneText, phone_number: phoneNum });
          }
      }

      $('#variables_json').val(JSON.stringify(buttonsData));

      // Extract variables from Body text
      let bodyText = $('#body').val();
      let bodyMatches = bodyText.match(/\{\{(\d+)\}\}/g);
      if (bodyMatches) {
          bodyMatches.forEach(v => {
              let num = parseInt(v.replace(/\D/g, ''));
              if (num > maxVar) maxVar = num;
          });
      }

      // Automatically update the hidden inputs before submitting
      if (maxVar > 0) {
          $('#whatsappCon').val('dynamic');
          $('#whatsappVar').val(maxVar);
      } else {
          $('#whatsappCon').val('static');
          $('#whatsappVar').val('0');
      }
      // -------------------------------------------------------

      Swal.fire({ title: 'Saving Template & Uploading Image...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

      // Use FormData to package the text inputs AND the file together
      let formData = new FormData(this);
      formData.append('method', 'add_whatsapp_template2');

      let fileInput = document.getElementById('header_image_file');
      if (fileInput && fileInput.files.length > 0) {
          formData.append('header_image_file', fileInput.files[0]);
      }

      // Single AJAX call to your core PHP file
      $.ajax({
          url: baseUrl, 
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          dataType: 'json',
          success: function(response) {
              if (response.success || response.status) {
                  $('#templateModal').modal('hide');
                  $('#templateForm')[0].reset();
                  quill.root.innerHTML = ''; 
                  $('#body').val('');
                  $('#button_inputs_container').empty(); 
                  $('#templateTable').DataTable().ajax.reload(null, false);

                  let newId = response.id; 
                  
                  if(newId) {
                      Swal.fire({
                          title: 'Template Saved!',
                          text: "Do you want to send this template to Facebook for approval right now?",
                          icon: 'question',
                          showCancelButton: true,
                          confirmButtonColor: '#28a745',
                          cancelButtonColor: '#6c757d',
                          confirmButtonText: 'Yes, Send for Approval',
                          cancelButtonText: 'No, Later'
                      }).then((result) => {
                          if (result.isConfirmed) {
                              sendToFacebookForApproval(newId);
                          } else {
                              toast('success', 'Saved locally.');
                          }
                      });
                  } else { 
                      toast('success', 'Template saved.'); 
                  }
              } else {
                  Swal.fire('Error', response.error || 'Failed to save template', 'error');
              }
          },
          error: function() {
              Swal.fire('Error', 'Server error while saving to DB.', 'error');
          }
      });
    });

    // ----------------------------------------------------
    // EDIT TEMPLATE LOGIC
    // ----------------------------------------------------

    // Dynamic inputs for Edit Modal
    $('#edit_button_type').change(function() {
        let type = $(this).val();
        let html = '';
        if (type === 'QUICK_REPLY') {
            html = `
                <small class="text-muted d-block mb-1">Enter up to 3 quick reply options.</small>
                <input type="text" class="form-control mb-2 edit-qr-input" placeholder="Button 1">
                <input type="text" class="form-control mb-2 edit-qr-input" placeholder="Button 2">
                <input type="text" class="form-control edit-qr-input" placeholder="Button 3">
            `;
        } else if (type === 'CALL_TO_ACTION') {
            html = `
                <div class="row mb-3">
                    <div class="col-5"><input type="text" class="form-control edit-cta-url-text" placeholder="URL Text"></div>
                    <div class="col-7"><input type="url" class="form-control edit-cta-url-link" placeholder="https://example.com/..."></div>
                </div>
                <div class="row">
                    <div class="col-5"><input type="text" class="form-control edit-cta-phone-text" placeholder="Phone Text"></div>
                    <div class="col-7"><input type="text" class="form-control edit-cta-phone-num" placeholder="+91..."></div>
                </div>
            `;
        }
        $('#edit_button_inputs_container').html(html);
    });

    // Populate Modal on click
    $('#templateTable').on('click', '.btn-edit', function () {
        let tr = $(this).closest('tr');
        if (tr.hasClass('child')) tr = tr.prev(); // Handle responsive DT
        let row = $('#templateTable').DataTable().row(tr).data();

        $('#edit_id').val(row.id);
        $('#edit_name').val(row.name);
        $('#edit_channel').val(row.channel.toUpperCase());
        
        editQuill.root.innerHTML = row.body;
        $('#edit_body').val(row.body);

        $('#edit_button_inputs_container').empty();
        $('#edit_header_image_file').val(''); // Clear file input

        if (row.variables_json) {
            let parsed = JSON.parse(row.variables_json);
            $('#edit_button_type').val(parsed.type).trigger('change');
            
            setTimeout(() => {
                if (parsed.type === 'QUICK_REPLY') {
                    let inputs = $('.edit-qr-input');
                    parsed.buttons.forEach((btn, idx) => {
                        if (inputs[idx]) $(inputs[idx]).val(btn.text);
                    });
                } else if (parsed.type === 'CALL_TO_ACTION') {
                    parsed.buttons.forEach((btn) => {
                        if (btn.type === 'URL') {
                            $('.edit-cta-url-text').val(btn.text);
                            $('.edit-cta-url-link').val(btn.url);
                        } else if (btn.type === 'PHONE_NUMBER') {
                            $('.edit-cta-phone-text').val(btn.text);
                            $('.edit-cta-phone-num').val(btn.phone_number);
                        }
                    });
                }
            }, 50);
        } else {
            $('#edit_button_type').val('none').trigger('change');
        }

        $('#editTemplateModal').modal('show');
    });

    // Submit Edit Form
    $('#editTemplateForm').submit(function (e) {
        e.preventDefault();
        document.getElementById('edit_body').value = editQuill.root.innerHTML;

        let btnType = $('#edit_button_type').val();
        let buttonsData = { type: btnType, buttons: [] };
        let maxVar = 0;

        if (btnType === 'QUICK_REPLY') {
            $('.edit-qr-input').each(function() {
                if ($(this).val().trim() !== '') buttonsData.buttons.push({ type: 'QUICK_REPLY', text: $(this).val().trim() });
            });
        } else if (btnType === 'CALL_TO_ACTION') {
            let urlText = $('.edit-cta-url-text').val();
            let urlLink = $('.edit-cta-url-link').val();
            if (urlText && urlLink) {
                buttonsData.buttons.push({ type: 'URL', text: urlText, url: urlLink });
                let urlMatches = urlLink.match(/\{\{(\d+)\}\}/g);
                if (urlMatches) urlMatches.forEach(v => { let num = parseInt(v.replace(/\D/g, '')); if (num > maxVar) maxVar = num; });
            }
            let phoneText = $('.edit-cta-phone-text').val();
            let phoneNum = $('.edit-cta-phone-num').val();
            if (phoneText && phoneNum) buttonsData.buttons.push({ type: 'PHONE_NUMBER', text: phoneText, phone_number: phoneNum });
        }
        $('#edit_variables_json').val(JSON.stringify(buttonsData));

        let bodyText = $('#edit_body').val();
        let bodyMatches = bodyText.match(/\{\{(\d+)\}\}/g);
        if (bodyMatches) bodyMatches.forEach(v => { let num = parseInt(v.replace(/\D/g, '')); if (num > maxVar) maxVar = num; });

        $('#edit_whatsappCon').val(maxVar > 0 ? 'dynamic' : 'static');
        $('#edit_whatsappVar').val(maxVar);

        Swal.fire({ title: 'Updating...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        let formData = new FormData(this);
        formData.append('method', 'edit_whatsapp_template2');

        // 1. Save locally via Core PHP
        $.ajax({
            url: baseUrl, 
            type: 'POST',
            data: formData,
            contentType: false, processData: false, dataType: 'json',
            success: function(response) {
                if (response.success || response.status) {
                    $('#editTemplateModal').modal('hide');
                    $('#templateTable').DataTable().ajax.reload(null, false);
                    
                    // 2. Trigger Meta Update automatically
                    $.ajax({
                        url: facebookEditUrl, 
                        type: "POST", dataType: "json",
                        headers: { "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()" },
                        data: { template_id: $('#edit_id').val() },
                        success: function(fbRes) {
                            if(fbRes.status === true || fbRes.status === 'success') {
                                Swal.fire('Updated!', 'Template updated locally and sent to Meta for re-approval. Status is now IN REVIEW.', 'success');
                                $('#templateTable').DataTable().ajax.reload(null, false);
                            } else {
                                Swal.fire('Meta Sync Error', fbRes.message, 'warning');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Failed', xhr.responseJSON ? xhr.responseJSON.message : "Meta API Error", 'error');
                        }
                    });
                } else {
                    Swal.fire('Error', response.error || 'Failed to update local template', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Server error while updating DB.', 'error');
            }
        });
    });

    // SMART DELETE LOGIC 
    $('#templateTable').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const fbid = $(this).data('fbid'); 
        
        let alertText = fbid 
            ? "This will permanently delete the template from your system AND from Whatsapp!" 
            : "";

        Swal.fire({
            title: 'Are you sure?',
            text: alertText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Removing template...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                if (!fbid) {
                    // It was NEVER sent to Facebook (N/A)
                    $.post(baseUrl, { method: 'delete_template', id: id }, function (response) {
                        if (response.success || response.status) { 
                            Swal.fire('Deleted!', 'Template deleted successfully.', 'success'); 
                            $('#templateTable').DataTable().ajax.reload(null, false); 
                        } else { 
                            Swal.fire('Error', 'Delete failed.', 'error'); 
                        }
                    }, 'json').fail(function() {
                        Swal.fire('Failed', 'Server error during deletion.', 'error');
                    });

                } else {
                    // Call the Laravel Endpoint to delete from FB, then DB
                    $.ajax({
                        url: facebookDeleteUrl, 
                        type: "POST",
                        dataType: "json",
                        headers: { "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()" },
                        data: { template_id: id },
                        success: function(response) {
                            if(response.status === true || response.status === 'success') {
                                Swal.fire('Deleted!', response.message, 'success');
                                $('#templateTable').DataTable().ajax.reload(null, false);
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            let msg = "Server Error";
                            if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                            Swal.fire('Failed', msg, 'error');
                        }
                    });
                }
            }
        });
    });
      
    $('#templateModal').on('hidden.bs.modal', function () {
      $('#templateForm')[0].reset(); 
      quill.root.innerHTML = '';
      $('#body').val('');
      $('#button_inputs_container').empty(); // Clear dynamic inputs when closed
    });

    $('#editTemplateModal').on('hidden.bs.modal', function () {
      $('#editTemplateForm')[0].reset(); 
      editQuill.root.innerHTML = '';
      $('#edit_body').val('');
      $('#edit_button_inputs_container').empty();
    });

});
</script>