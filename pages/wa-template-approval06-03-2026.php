<?php
   //    Date        Developer_name      Modifications
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
  
  /* Helper class to restrict the name column's width */
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
                                    <a href="#" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#templateModal">
                                        <i class="fa fa-plus me-2"></i> Add Template</a>
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
          <input type="hidden" name="whatsappCon" value="static">
          <input type="hidden" name="whatsapp_content_type" value="message">
          <input type="hidden" name="whatsappVar" value="0">

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
            <label class="form-label">Message Body <span class="text-danger">*</span></label>
            <div id="editor" style="height: 150px; background-color: #fff;"></div>
            <textarea style="display:none;" name="body" id="body"></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save Template</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill-emoji@0.1.7/dist/quill-emoji.js"></script>

<script>
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
</script>

<script>
var origin = window.location.origin;
const baseUrl = origin + "/ajax/service/salse_marketing_services.php";
var api_domain = "<?= rtrim(TEST_API_DOMAIN_2 ?? '', '/') ?>";

const facebookApiUrl = api_domain + "/whatsapp/request-approval"; 
const facebookSyncUrl = api_domain + "/whatsapp/sync-approval"; 
const facebookDeleteUrl = api_domain + "/whatsapp/delete-template"; 

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

// DATATABLE INIT
$(document).ready(function () {
    $('#name').on('input', function() {
        let val = $(this).val();
        val = val.toLowerCase(); 
        val = val.replace(/\s+/g, '_'); 
        val = val.replace(/[^a-z0-9_]/g, ''); 
        $(this).val(val);
    });

    $('#templateTable').DataTable({
      scrollX: true, 
      order: [[0, 'desc']], 
      ajax: {
        url: baseUrl,
        type: 'POST',
        data: function (d) { d.method = 'get_all_templates'; }
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
          render: function (data) {
              if(!data) return '-';
              return `<div class="table-body-scroll">${data}</div>`;
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
            // FIX: Added data-fbid to read the facebook ID inside the delete function
            let fbid = row.whatsapp_template_id ? row.whatsapp_template_id : '';
            
            let actionBtns = `<div style="display: flex; gap: 5px; align-items: center;">
                              <button class="btn btn-sm btn-danger btn-delete" data-id="${row.id}" data-fbid="${fbid}">Delete</button>`;
            
            if (!row.whatsapp_template_id) {
                actionBtns += `<button class="btn btn-sm btn-success btn-approve" data-id="${row.id}" data-name="${row.name}" title="Request Facebook Approval">Req FB</button>`;
            } else if (row.approval_status !== 'approved') {
                actionBtns += `<button class="btn btn-sm btn-info btn-sync text-white" data-id="${row.id}" title="Sync Status"><i class="fa fa-refresh"></i></button>`;
            }
            
            actionBtns += `</div>`;
            return actionBtns;
          }
        }
      ]
    });
});

$(document).ready(function () {

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

      let method = 'add_whatsapp_template2';
      const formData = $(this).serialize() + `&method=${method}`;

      $.post(baseUrl, formData, function (response) {
        $('#templateModal').modal('hide');
        $('#templateForm')[0].reset();
        quill.root.innerHTML = ''; 
        $('#body').val('');
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
        } else { toast('success', 'Template saved.'); }

      }, 'json').fail(function () { toast('error', 'Save failed.'); });
    });

    // SMART DELETE LOGIC (Fixes the Server Error for N/A templates)
    $('#templateTable').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const fbid = $(this).data('fbid'); // Read Facebook ID
        
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
                    // Use standard local PHP file to avoid Laravel errors
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
                    // It exists on Facebook (In Review, Approved, etc)
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
    });

});
</script>