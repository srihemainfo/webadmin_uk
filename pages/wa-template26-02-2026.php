<?php
   //    Date       Developer_name      Modifications
   //    24.06.2025   Suriya M           Development - WaMail Templates
   $pageTitle = 'WaMail Templates';
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

  .custom-input {
    height: 100px;
    padding-top: 1.5rem; 
    vertical-align: top;
  }


  .custom-input::placeholder {
    vertical-align: top;
    text-align: left;
    position:relative;
    bottom:40px;
  }
 

span.fa.fa-trophy.fs-14 {
    color: #9d8711;
    font-size: 19px !important;
    font-weight: 900;
}
div.dt-container div.dt-length select{
    color: black;
    border: 1px solid;
}
.modal-content{
    color:black;
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
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left"
                            onclick="history.go(-1)" aria-hidden="true"></i></a><?= ucwords($pageTitle); ?></h1>
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
                                <h3 class="card-title"><strong>WaMail Templates</strong></h3>
                            </div>
                            <div class="col-lg-8">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-end gy-3">
                             
                                <!-- Add Template Button -->
                                <div class="col-lg-12 d-flex justify-content-lg-end justify-content-md-start">
                                    <a href="#" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#templateModal">
                                        <i class="fa fa-user-plus me-2"></i>Add Template</a>
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
                                          <th>Date & Time</th>
                                          <th>E Subject</th>
                                          <th>Photo</th>
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
    
    <!-- Modal -->
  <!-- Your existing modal, now modified internally -->
   <div class="modal fade" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="templateForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Template</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <!-- Template Name -->
          <div class="mb-3">
            <label for="name" class="form-label">Template Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="name" id="name" required>
          </div>
        
                   <!-- Template Type -->
          <div class="mb-3">
            <label for="channel" class="form-label">Template Type <span class="text-danger">*</span></label>
            <select name="channel" id="channel" class="form-select" required>
              <option value="">-- Select --</option>
              <option value="whatsapp">WhatsApp</option>
              <option value="email">Email</option>
            </select>
          </div>
        
                  <!-- Email Subject -->
        
          <div class="mb-3" id="emailFields" style="display:none;">
            <label for="subject" class="form-label">Email Subject</label>
            <input type="text" class="form-control" name="subject" id="subject">
          </div>
          
          <div class="mb-3" id="whatsappConDiv" style="display:none;">
            <label for="whatsappCon" class="form-label">Type<span class="text-danger">*</span></label>
            <select name="whatsappCon" id="whatsappCon" class="form-select">
              <option value="">-- Select --</option>
              <option value="static">Static</option>
              <option value="dynamic">Dynamic</option>
            </select>
          </div>
        
          <!-- WhatsApp Content Type -->
          <div class="mb-3" id="whatsappOption" style="display:none;">
            <label for="whatsapp_content_type" class="form-label">WhatsApp Content Type<span class="text-danger">*</span></label>
            <select name="whatsapp_content_type" id="whatsapp_content_type" class="form-select">
              <option value="">-- Select --</option>
              <option value="message">Message Body</option>
              <option value="photo">Photo Upload</option>
            </select>
          </div>
          
          
          
          <div class="mb-3" id="whatsappVarDiv" style="display:none;">
            <label for="whatsappVar" class="form-label">Variable Count<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="whatsappVar" id="whatsappVar">
          </div>
        
        <div class="mb-3" id="messageBodyWrapper" style="display:none;">
            <div class="border-start border-3 border-primary ps-3 py-2 mt-2 mb-2 bg-light rounded-1">
                <p class="mb-0 small text-dark">
                    <span class="fw-bold text-danger">Note:</span> The placeholders, e.g., <span class="fw-bold">**{{VAR1}} {{VAR2}}**</span>, will automatically populate with relevant data.
                </p>
            </div>
            
            <textarea class="form-control" name="body" id="body" rows="5" placeholder="Enter your message here... Use variables like: {{VAR1}}, {{VAR2}}" disabled></textarea>
        
            
        </div>
                
          <!-- Photo Upload -->
          <div class="mb-3" id="photoUploadWrapper" style="display:none;">
            <label for="photo" class="form-label">Upload Photo <span class="text-danger">*</span></label>
            <input type="file" class="form-control" name="photo" id="photo" accept="image/*" disabled>
            <div id="photo_error" class="text-danger"></div>
            <img id="photo_preview" src="#" alt="Preview" style="display:none; max-height:150px; margin-top:10px;" />
            <input type="text" id="photobase64" name="photobase64" style="display: none;">
            <button type="button" id="clearPhotoBtn" class="btn btn-sm btn-outline-danger mt-2">Clear Photo</button>
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


  document.getElementById('templateForm').addEventListener('submit', function (e) {
    e.preventDefault();
    document.getElementById('body').value = quill.root.innerHTML;

    const formData = new FormData(this);
    for (let [key, value] of formData.entries()) {
      console.log(`${key}:`, value);
    //   return false;
    }
  });
</script>


<script>

var origin = window.location.origin;
const baseUrl = origin + "/ajax/service/salse_marketing_services.php";



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

$(document).ready(function () {
    
const $emailFields = $('#emailFields');
const $subjectInput = $('#subject');
const $whatsappOption = $('#whatsappOption');
const $whatsappConDiv = $('#whatsappConDiv');
const $whatsappVarDiv = $('#whatsappVarDiv');
const $whatsappContentType = $('#whatsapp_content_type'); // correctly declared
const $messageBody = $('#messageBodyWrapper');
const $photoUpload = $('#photoUploadWrapper');
const $body = $('#body');
const $photo = $('#photo');

$('#channel').on('change', function () {
  const type = $(this).val();

     if (type === 'email') {
        $emailFields.show();
        $subjectInput.prop('required', true);
    
        $whatsappOption.hide();
        $whatsappVarDiv.hide();
        $whatsappConDiv.hide();
        $whatsappContentType.val(''); 
        $whatsappContentType.prop('required', false);
    
        $messageBody.show();
        $body.prop('required', true).prop('disabled', false);
    
        $photoUpload.hide();
        $photo.prop('required', false).prop('disabled', true);
    
      } 
    else if (type === 'whatsapp') {
        $emailFields.hide();
        $subjectInput.prop('required', false);
    
        $whatsappOption.show();
        // $whatsappVarDiv.show();
        $whatsappConDiv.show();
        $whatsappContentType.prop('required', true);
    
        $messageBody.hide();
        $body.prop('required', false).prop('disabled', true);
    
        $photoUpload.hide();
        $photo.prop('required', false).prop('disabled', true);
    
      } else {
        $emailFields.hide();
        $subjectInput.prop('required', false);
    
        $whatsappOption.hide();
        $whatsappContentType.prop('required', false);
    
        $messageBody.hide();
        $body.prop('required', false).prop('disabled', true);
    
        $photoUpload.hide();
        $photo.prop('required', false).prop('disabled', true);
      }
    });
    
$('#whatsappCon').on('change', function () {
    const type = $(this).val();

    if (type === 'static') {
     
        $whatsappVarDiv.hide();
    
    } 
    else if (type === 'dynamic') {
    
        $whatsappVarDiv.show();
    
    } 
});


    $whatsappContentType.on('change', function () {
      const contentType = $(this).val();
    
      if (contentType === 'message') {
        $messageBody.show();
        $photoUpload.hide();
    
        $body.prop('required', true).prop('disabled', false);
        $photo.prop('required', false).prop('disabled', true);
      } else if (contentType === 'photo') {
        $messageBody.hide();
        $photoUpload.show();
    
        $body.prop('required', false).prop('disabled', true);
        $photo.prop('required', false).prop('disabled', false); // editable photo
      } else {
        $messageBody.hide();
        $photoUpload.hide();
    
        $body.prop('required', false).prop('disabled', true);
        $photo.prop('required', false).prop('disabled', true);
      }
    });

  

    
  $('#photo').on('change', function () {
    const file = this.files[0];

    if (file) {
      // Validate image type
      if (!file.type.startsWith('image/')) {
        $('#photo_error').text('Please select a valid image file.');
        $('#photo_preview').hide();
        $('#photobase64').val(''); 
        return;
      }

      // Clear error and show preview
      $('#photo_error').text('');
      const reader = new FileReader();

    reader.onload = function (e) {
      const base64Full = e.target.result;
      const base64Only = base64Full.split(',')[1]; 
    
      $('#photo_preview').attr('src', base64Full).show();
      $('#photobase64').val(base64Only);
    //   console.log("Base64 String:", $('#photobase64').val());
    };

      reader.readAsDataURL(file);
    } else {
      $('#photo_preview').hide();
      $('#photo_error').text('');
      $('#photobase64').val('');
    }
  });
 $('#clearPhotoBtn').on('click', function () {
  $('#photo').val('');
  $('#photo_preview').hide().attr('src', '#');
  $('#photobase64').val('');
  $('#photo_error').text('');
});
});


function createDatePricket(id) {
  const start = moment();
  const end = moment();

  function cb(start, end) {
    $('#' + id + ' span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
    $('#' + id).data('startDate', start.startOf('day'));
    $('#' + id).data('endDate', end.endOf('day'));
    $('#templateTable').DataTable().ajax.reload();
  }

  $('#' + id).daterangepicker({
    startDate: start,
    endDate: end,
    maxDate: moment(),
    ranges: {
      'Today': [moment(), moment()],
      'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 Days': [moment().subtract(6, 'days'), moment()],
      'This Month': [moment().startOf('month'), moment()],
      'All Time': [moment('2000-01-01'), moment()]
    },
    locale: {
      format: 'DD/MM/YYYY'
    }
  }, cb);

  cb(start, end);
}



$(document).ready(function () {
    
    
   
            $('#templateTable').DataTable({
          ajax: {
            url: baseUrl,
            type: 'POST',
            data: function (d) {
              d.method = 'get_all_templates';
            //   d.start_date = $('#reportrange213').data('startDate')?.format('YYYY-MM-DD');
            //   d.end_date = $('#reportrange213').data('endDate')?.format('YYYY-MM-DD');
            }
          },
          columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'channel' },
            { data: 'body' },
            { data: 'created_at' },
            { data: 'subject' },
            {
              data: 'media_url',
              render: function (data, type, row) {
                if (data) {
                  const src = 'data:image/jpeg;base64,' + data;
                  return `<img src="${src}" alt="Image" style="max-height: 150px;">`;
                } else {
                  return 'No Image';
                }
              }
            },
            {
              data: null,
              render: function (data, type, row) {
                return `
                  <button class="btn btn-sm btn-warning btn-edit" data-id="${row.id}">Edit</button>
                  <button class="btn btn-sm btn-danger btn-delete" data-id="${row.id}">Delete</button>
                `;
              }
            }
          ]
        });

    // createDatePricket('reportrange213');
  });

 


$(document).ready(function () {



$('#templateTable').on('click', '.btn-edit', function () {
  const id = $(this).data('id');

  $.post(baseUrl, { method: 'get_single_template', id }, function (response) {
    if (response.success) {
      const data = response.data;
      $('#templateModal').modal('show');

      $('#templateForm input[name="name"]').val(data.name);
      $('#templateForm select[name="channel"]').val(data.channel).trigger('change');
      $('#templateForm input[name="subject"]').val(data.subject);
      $('#templateForm textarea[name="body"]').val(data.body);

      // Determine WhatsApp content type
      if (data.channel === 'whatsapp') {
        if (data.body) {
          $('#whatsapp_content_type').val('message').trigger('change');
        } else if (data.media_url) {
          $('#whatsapp_content_type').val('photo').trigger('change');
        } else {
          $('#whatsapp_content_type').val('').trigger('change');
        }
      }

      // Handle image preview
      if (data.media_url) {
        const base64Image = data.media_url.startsWith('data:image')
          ? data.media_url
          : `data:image/jpeg;base64,${data.media_url}`;

        $('#photo_preview').attr('src', base64Image).show();
        $('#photobase64').val(base64Image);
      } else {
        $('#photo_preview').attr('src', '#').hide();
        $('#photobase64').val('');
      }

      // Ensure file input is enabled if photo was selected
      if ($('#whatsapp_content_type').val() === 'photo') {
        $('#photo').prop('disabled', false);
      }

      // Clean old template_id input and set new one
      $('#templateForm input[name="template_id"]').remove();
      $('#templateForm').append(`<input type="hidden" name="template_id" value="${data.id}">`);
    } else {
      toast('error', 'Failed to load template data.');
    }
  }, 'json');
});


 $('#templateForm').submit(function (e) {
  e.preventDefault();

  const selectedChannel = $('#channel').val();
  const templateId = $('input[name="template_id"]').val();
  let method = '';

  if (templateId) {
    method = 'update_template';
  } else if (selectedChannel === 'whatsapp') {
    method = 'add_whatsapp_template2';
  } else if (selectedChannel === 'email') {
    method = 'add_email_template';
  } else {
    toast('error', 'Please select a template type.');
    return;
  }
  
  if($('#whatsappCon').val() != '' && $('#whatsappCon').val() == 'dynamic'){
      if($('#whatsappVar').val() == ''){
        toast('error', 'Please select a variable count.');
        return;
      }
  }

  // Strip base64 prefix if present
  const photoVal = $('#photobase64').val();
  if (photoVal.startsWith('data:image')) {
    const base64Only = photoVal.split(',')[1];
    $('#photobase64').val(base64Only);
  }

  const formData = $(this).serialize() + `&method=${method}`;
  console.log(formData);
//   return false;

  $.post(baseUrl, formData, function (response) {
    $('#templateModal').modal('hide');
    $('#templateForm')[0].reset();
    $('input[name="template_id"]').remove();
    toast('success', 'Template saved successfully.');
    $('#templateTable').DataTable().ajax.reload();
  }).fail(function () {
    toast('error', 'Save failed.');
  });
});

  // Soft delete
  $('#templateTable').on('click', '.btn-delete', function () {
    const id = $(this).data('id');
    if (confirm('Are you sure you want to delete this template?')) {
      $.post(baseUrl, { method: 'delete_template', id }, function (response) {
        if (response.success) {
          toast('success', 'Template deleted.');
          $('#templateTable').DataTable().ajax.reload();
        } else {
          toast('error', 'Delete failed.');
        }
      }, 'json');
    }
  });
  
  $('#templateModal').on('hidden.bs.modal', function () {
  $('#templateForm')[0].reset(); // Reset form fields

  // Manually reset hidden inputs and state-based elements
  $('#photobase64').val('');
  $('#photo_preview').attr('src', '#').hide();
  $('#photo_error').text('');
  $('#photo').val('').prop('disabled', true);
  $('#body').prop('disabled', true).val('');
  $('#subject').prop('required', false);
  $('#whatsapp_content_type').val('').trigger('change');
  $('#emailFields, #whatsappOption, #messageBodyWrapper, #photoUploadWrapper').hide();

  // Remove template_id hidden field
  $('#templateForm input[name="template_id"]').remove();
});

});

</script>