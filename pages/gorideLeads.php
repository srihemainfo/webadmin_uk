<?php
$pageTitle = "Go Ride Leads";
// error_reporting(E_ALL);
// ini_set('display_errors', 1);


?> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>  
    
    .card-header.d-lg-flex.d-block.justify-content-between {
        border-bottom: none;
    }
    .cursor-pointer {
        cursor: pointer;
        font-size: 1.2rem; /* Adjust size as needed */
    }

    input,
    select {
        border: 1px solid #CCC;
        /* width: 250px; */
    }

    .nav.product-sale {
        position: unset;
        top: -3rem;
        right: 5px;
        margin: 12px 0;
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

    .swal-modal {
        border: 3px solid white;
        color: #fff;
    }

    .swal-button {
        background-color: #07f3a2 !important;
    }

    .swal-text {
        font-weight: 600 !important;
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

    .required-indicator {
        color: red;
        /* Change to the desired color */
    }

    .dt-column-order {
        display: none;
    }

    table.dataTable th.dt-type-numeric,
    table.dataTable th.dt-type-date,
    table.dataTable td.dt-type-numeric,
    table.dataTable td.dt-type-date {
        text-align: left;
    }

    .form-control:disabled,
    .form-control[readonly] {
        background-color: rgb(255 255 255 / 10%);
        /* opacity: 1; */
    }

    input,
    select {
        border: 1px solid #CCC;
        /* width: 250px; */
    }

    .text {
        float: unset !important;
    }

    .dropdown-item {
        display: block;
        width: 100%;
        padding: 0.25rem 1rem;
        clear: both;
        font-weight: 400;
        color: #121212 !important;
        text-align: inherit;
        text-decoration: none;
        white-space: nowrap;
        background-color: white;
        border: 0;
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

    .btn-light {
        /* color: #495057; */
        background-color: #ffffff !important;
    }

    .dropdown.bootstrap-select {
        display: block !important;
        width: 100% !important;
    }
    
div:where(.swal2-container) {
    z-index: 9999 !important;
}

div:where(.swal2-container) h2:where(.swal2-title) {
    color: rgb(85 85 85) !important;
}
    
</style>
<script>
    window.onload = function () {
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
        <div class="main-container container-fluid mt-5 p-0">
            <!-- PAGE-HEADER -->
            <div class="page-header pt-5">
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left"
                            onclick="history.go(-1)" aria-hidden="true"></i></a><?= $pageTitle; ?></h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= $pageTitle; ?></li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->
            <!-- ROW-1 -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                    <div class="card ">
                        <div class="card-body">
                            <div class="d-flex1">
                                <div class="mt-2">
                                    <div class="row">

                                        <div class="col-sm-12 col-md-3 col-lg-3">
                                            <span>Search (CRM / Name / Mobile / Email)</span>

                                            <input class="form-control" type="text" id="searchTxt" name="searchTxt"
                                                placeholder="CRM / Name / Mobile / Email" value=""
                                                oninput="this.value = this.value.replace(/[^A-Za-z. ]/g, ''); if (this.value.length > 3) { unsubscribeList(); }"
                                                maxlength="70" />

                                        </div>


                                        <div class="col-sm-12 col-md-3 col-lg-3">
                                            <span>Select Date (Created At)</span>

                                            <input class="form-control" type="text" id="datefilterLogin"
                                                name="datefilterLogin" placeholder="Select Date" value="" readonly />
                                        </div>

                                        


                                        
                                        
                                        <div class="col-sm-12 col-md-3 col-lg-3">
                                            <span>Campaign Name</span> &nbsp; <span style="color:red;"></span>
                                            <select id="utm_campaign" class="selectpicker">
                                                <option value="" selected="">Select Campaign Name</option>

                                                <?php

                                                $utm_campaign = mysqli_query($con, "SELECT campaign_name FROM `goride_ad_leads` WHERE campaign_name IS NOT null GROUP BY campaign_name;");
                                                if (mysqli_num_rows($utm_campaign) > 0) {
                                                    while ($row = mysqli_fetch_assoc($utm_campaign)) {
                                                ?>
                                                        <option value="<?= $row['campaign_name']; ?>">
                                                            <?= $row['campaign_name']; ?>
                                                        </option>
                                                <?php
                                                    }
                                                }
                                                ?>

                                            </select>
                                        </div>

                                        <div class="col-sm-12 col-md-3 col-lg-3">
                                            <span>Registered Status</span> &nbsp; <span style="color:red;"></span>
                                            <select id="registered_status" class="selectpicker">
                                                <option value="" selected="">Select Status</option>
                                                <option value="1">Registered</option>
                                                <option value="0">Not Registered</option>
                                            </select>
                                        </div>



                                        <div class="col-sm-12 col-md-6 col-lg-4 mt-4">
                                            <button type="button" class="btn btn-primary" id="searchBTN"
                                                onclick="unsubscribeList();">GO</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4" id="searcherr">
                                    </div>
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
                    <div class="card-body pt-4">
                        <div class="grid-margin">
                            <div class="panel panel-primary">
                                <div class="tab-menu-heading border-0 p-0">
                                    <div class="tabs-menu1">
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body border-0 pt-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab1">
                                            <div class="card-header d-lg-flex d-block justify-content-between">
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered text-nowrap border-bottom"
                                                    id="Participation_List" style="width:100%;">
                                                    <thead>
                                                        <tr>
                                                            <th class="column_sort sorting sorting_asc">platform</th>
                                                            <th class="column_sort sorting sorting_asc">Full Name</th>
                                                            <th class="column_sort sorting sorting_asc">Phone</th>
                                                            <th class="column_sort sorting sorting_asc">Reg. Status</th>
                                                            <th class="column_sort sorting sorting_asc">Temp Status</th>
                                                            <th class="column_sort sorting sorting_asc">WA Temp Status</th>
                                                            <th class="column_sort sorting sorting_asc">Welcome Templates</th>
                                                            <th class="column_sort sorting sorting_asc">Comments</th>
                                                            <th class="column_sort sorting sorting_asc">Action</th>
                                                            <th class="column_sort sorting sorting_asc">adset_name</th>
                                                            <th class="column_sort sorting sorting_asc">campaign_name</th>
                                                            <th class="column_sort sorting sorting_asc">Created At</th>
                                                            <th class="column_sort sorting sorting_asc">Email</th>
                                                            <th class="column_sort sorting sorting_asc">City</th>
                                                            <!--<th class="column_sort sorting sorting_asc">Email</th>-->
                                                            <!--<th class="column_sort sorting sorting_asc">Createdon</th>-->
                                                            <!--<th class="column_sort sorting sorting_asc">Action</th>-->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                    <!-- <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </tfoot> -->
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
        <!-- ROW-4 END -->
    </div>
    <!-- CONTAINER END -->
</div>

<div class="modal fade" id="comment_form">
   <div class="modal-dialog modal-lg">
      <div class="modal-content modal-content-demo">
         <div id="otperror"></div>
         <div class="modal-header">
            <h6 class="modal-title">Lead Details</h6>
            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="container">
               <form action="#" class="comment_form">
                  <div class="form-group">
                     <div class="model-text">
                        <!--<h3 class="text-center"><b id="title"></b>Lead Details</h3>-->
                        <input type="hidden" id="lead_id" class="form-control" value=""/>
                        <!--<p class="text-center">Enter the code we just send on your <b id="title"></b> <b id="mno"></b></p>-->
                        
                        <!--<br>-->
                     </div>
                     <div class="row ">
                        <p class="fw-bold">Comment Logs</p>
                        <table class="table table-hover" >
                            <thead>
                                <tr>
                                    <th scope="col">SNO</th>
                                    <th scope="col">Contact Person</th>
                                    <th scope="col">Customer Comment</th>
                                    <th scope="col">Next FollowUp</th>
                                    <th scope="col">Date</th>
                                </tr>
                            </thead>
                            <tbody id="l_tbl">
                            </tbody>
                        </table>
                     </div>
                     <div class="row mt-3">
                        <p class="fw-bold">Add Comment</p>
                        <!-- Column 1: Select -->
                        <div class="col-3">
                            <label for="selectOption">Customer Comment</label>
                            <select id="com_comment" class="form-control">
                                <option value="">Choose</option>
                                <option value="Interested">Interested</option>
                                <option value="Not Interested">Not Interested</option>
                                <option value="Call Later">Call Later</option>
                            </select>
                        </div>
                    
                        <!-- Column 2: Date -->
                        <div class="col-3">
                            <label for="dateInput">Next FollowUp</label>
                            <input type="date" id="com_dateInput" class="form-control" />
                        </div>
                    
                        <!-- Column 3: Textarea -->
                        <div class="col-6">
                            <label for="message">Message</label>
                            <textarea id="com_message" class="form-control" rows="3" placeholder="Enter message"></textarea>
                        </div>
                    
                    </div>
                     <div class="row justify-content-center mt-5">
                        <!-- Column: Centered Button -->
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" id="comment_submit">Submit</button>
                        </div>
                    </div>


                  </div>
               </form>
               <br>
            </div>
         </div>
         <div class="modal-footer" id="otpbtn">
            <!-- <button class="btn ripple btn-success" onclick="saveformNew()" type="button">Submit</button>
               <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button> -->
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="whatsapp_form">
   <div class="modal-dialog modal-md">
      <div class="modal-content modal-content-demo">
         <div id="otperror"></div>
         <div class="modal-header">
            <h6 class="modal-title">Send Whatsapp Message</h6>
            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="container">
               <form action="#" class="comment_form">
                  <div class="form-group">
                     <div class="model-text d-none">
                        <!--<h3 class="text-center"><b id="title"></b>Lead Details</h3>-->
                        <input type="hidden" id="leads_id" class="form-control" value=""/>
                        <input type="hidden" id="lead_phone" class="form-control" value=""/>
                        <!--<p class="text-center">Enter the code we just send on your <b id="title"></b> <b id="mno"></b></p>-->
                        
                        <br>
                     </div>
                    <!-- <div class="row justify-content-center mt-5">-->
                    <!--    <div class="col-12">-->
                    <!--        <label for="message">Message</label>-->
                    <!--        <textarea id="whatsapp_message" class="form-control" rows="3" placeholder="Your message"></textarea>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <!--<div class="row justify-content-center mt-5">-->
                    <!--  <div class="col-12">-->
                    <!--    <label for="message">Message</label>-->
                    <!--    <div id="whatsapp_message" style="height: 200px;"></div>-->
                    <!--  </div>-->
                    <!--</div>-->
                    
                    <div>
                        <label class="form-label">Template</label>
                        <select id="waTemplateID" class="selectpicker">
                            <option value="" selected="">Select Template</option>
                            <?php
                            $utm_campaign = mysqli_query($con, "SELECT id, name FROM `wamail_templates` WHERE is_active = 1 ORDER BY id;");
                            if (mysqli_num_rows($utm_campaign) > 0) {
                                while ($row = mysqli_fetch_assoc($utm_campaign)) {
                            ?>
                                <option value="<?= $row['id']; ?>">
                                    <?= $row['name']; ?>
                                </option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                     <div class="row justify-content-center mt-5">
                        <!-- Column: Centered Button -->
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" id="whatsapp_submit">Send</button>
                        </div>
                    </div>


                  </div>
               </form>
               <br>
            </div>
         </div>
         <div class="modal-footer" id="otpbtn">
            <!-- <button class="btn ripple btn-success" onclick="saveformNew()" type="button">Submit</button>
               <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Close</button> -->
         </div>
      </div>
   </div>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
  const quill = new Quill('#whatsapp_message', {
    theme: 'snow'
  });
</script>

// <script>
//     tinymce.init({
//         selector: '#whatsapp_message',
//         menubar: false,
//         plugins: 'lists link',
//         toolbar: 'undo redo | bold italic underline | bullist numlist | link',
//         height: 200
//     });
// </script>
<script>
    let mainDomain = window.location.hostname.split('.').slice(-2).join('.');

    const createDatePicker = (id) => {
        try {
            const selector = `#${id}`;
            const today = moment();
            const sevenDaysAgo = moment().subtract(0, 'days');
            $(selector).daterangepicker({
                autoUpdateInput: true,
                locale: {
                    cancelLabel: 'Clear'
                },
                // minDate: sevenDaysAgo, 
                maxDate: today,
                opens: 'left',
                startDate: sevenDaysAgo,
                endDate: today,
                // maxSpan: {
                //     days: 30
                // },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
                }
            });
            $(selector).on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                // $(`#drawID`).val('');
                // $(`#drawID`).val('').selectpicker('refresh');
            });
            $(selector).on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
        } catch (e) {
            console.log(`Error: ${e.message}`);
        }
    }
    const unsubscribeList = () => {
        try {
            let startDate = null;
            let endDate = null;
            var btn = $(`#searchBTN`);
            var dateFilter = $('#datefilterLogin').val() ?? null;
            if (dateFilter != '' && dateFilter != null && dateFilter != undefined) {
                startDate = moment($('#datefilterLogin').data('daterangepicker').startDate).format("YYYY-MM-DD");
                endDate = moment($('#datefilterLogin').data('daterangepicker').endDate).format("YYYY-MM-DD");
            }
            var table = $('#Participation_List').DataTable({
                destroy: true,
                pageLength: 10,
                order: [],
                paging: true,
                searching: true,
                info: true,
                ajax: {
                    url: origin + "/ajax/service/topSpendersServices.php",
                    method: "POST",
                    dataSrc: "result",
                    data: {
                        method: 'GoRideLeadsReprot',
                        dateFilter: dateFilter,
                        startDate: startDate,
                        endDate: endDate,
                        searchTxt: $(`#searchTxt`).val(),
                        statusVal: $(`#statusVal`).val(),
                        statusPlan: $(`#statusPlan`).val(),
                        utm_source: $(`#utm_campaign_Source`).val(),
                        utm_campaign: $(`#utm_campaign`).val(),
                        registered_status: $(`#registered_status`).val()
                        
                        // currencyID: $(`#currencyID`).val()
                    },
                    beforeSend: function () {
                        btn.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Loading...`).prop('disabled', true);
                    },
                },
                // order: [
                //     [9, 'desc']
                // ],
                // columnDefs: [{
                //     data
                // }],
                dom: 'Bfrtip',
                buttons: [
                    'pageLength',
                    'copy',
                    {
                        extend: 'excelHtml5',
                        title: 'Go Ride Leads'
                    },
                ],
                columns: [
                    // {
                    //     data: 'grandtotal',
                    //     render: function (data, type, row, meta) {
                    //         return parseFloat(data)
                    //     }
                    // },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            let dom = data?.platform || '';
                            return dom;

                        }
                    },
                    // {
                    //     data: null,
                    //     render: function (data, type, row, meta) {
                    //         return data.Mobile
                    //     }
                    // },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return data?.full_name || '';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return data?.phone || '';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            if (data?.register_status == 1) {
                                return 'Registered'; 
                            } else if (data?.register_status == 0) {
                                return 'Not Registered';
                            } else {
                                return 'Unknown';
                            }
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            const statusMap = {
                                0: 'NA',
                                1: 'Signup Template',
                                2: 'CRM Purchase Template',
                                3: 'CRM SetUp Template',
                                4: 'CRM Inside SetUp Template',
                                5: 'CRM Website Template'
                            };
                    
                            return statusMap[data.temp_status] || 'Unknown';
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            const st_m = {
                                0: 'No',
                                1: 'Yes'
                            };
                    
                            return st_m[data.welcome_temp];
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            const st_m = {
                                0: 'Yet to Send',
                                11: 'Yet to Send',
                                10: '-',
                                1: 'SignUp'
                            };
                    
                            return st_m[data.welcome_temp_status];
                        }
                    },
                    
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                    
                            return data.comments_status??'NA';
                        }
                    },
                    // {
                    //     data: null,
                    //     render: function (data, type, row, meta) {
                    //         return `
                    //             <button class="btn btn-sm btn-primary me-1 add-comment-btn" data-id="${row.id}" onclick="comment_form('${row.id}', this)">
                    //                 <i class="fas fa-comment-alt"></i>
                    //             </button>
                    //             <button class="btn btn-sm btn-success" onclick="sendWhatsApp('${row.id}', this)">
                    //                 <i class="fab fa-whatsapp"></i>
                    //             </button>
                    //         `;
                    //     }
                    // }
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return `
                                <i class="fas fa-comment-alt text-primary fa-2x me-2 cursor-pointer" title="Add Comment" onclick="comment_form('${row.id}', this)"></i>
                                <i class="fab fa-whatsapp text-success fa-2x cursor-pointer" title="Send WhatsApp" onclick="openWhatsApp('${row.id}', this, '${row.phone}')"></i>
                            `;
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return data?.ad_name || ''
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return data?.campaign_name || '';
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                    
                            return data?.created_at || '';
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return data?.email || '';
                            
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return data?.city || '';
                        }
                    }
                ],
                initComplete: function (settings, json) {
                    btn.html(`GO`).prop('disabled', false);
                },
                // footerCallback: function (row, data, start, end, display) {
                //     var api = this.api();
                //     var intVal = function (i) {
                //         if (typeof i === 'string') {
                //             const trimmed = i.trim();
                //             if (trimmed === 'NA' || trimmed === '') {
                //                 return 0; // Treat 'NA' as 0
                //             }
                //             return parseFloat(trimmed.replace(/[\$,]/g, '')) || 0;
                //         }
                //         return typeof i === 'number' ? i : 0;
                //     };
                //     var calculateTotals = function (columnIndex) {
                //         let s = {
                //             total: 0,
                //             pageTotal: 0
                //         };
                //         // Total for all pages
                //         s.total = api.column(columnIndex).data().reduce((a, b) => {
                //             const value = intVal(b);
                //             console.log(`Column ${columnIndex} Total Value:`, value); // Log total value for each item
                //             return a + value;
                //         }, 0);
                //         // Total for current page
                //         s.pageTotal = api.column(columnIndex, {
                //             page: 'current'
                //         }).data().reduce((a, b) => {
                //             const value = intVal(b);
                //             console.log(`Column ${columnIndex} Page Value:`, value); // Log page value for each item
                //             return a + value;
                //         }, 0);
                //         return s;
                //     };
                //     [2].forEach(function (i) {
                //         api.column(i).data().each(function (value, index) {
                //             console.log(`Row ${index} Column ${i} Value:`, value);
                //         });
                //         var totals = calculateTotals(i);
                //         $(api.column(i).footer()).html(totals.pageTotal.toFixed(2) + ' ( ' + totals.total.toFixed(2) + ' total)');
                //     });
                // }
            });
        } catch (e) {
            console.log(`Error: ${e.message}`);
        }
    }
    
    function comment_form(id, btn){
        $('#com_comment').val('');
        $('#com_message').val('');
        $('#com_dateInput').val('');
        $('#lead_id').val(id);
        if(id != ''){
            $(btn).prop('disabled', true);
              
            $.ajax({
                url: origin + "/ajax/service/topSpendersServices.php",
                method: "POST",
                // contentType: "application/json",
                dataType: "json",
                data: {
                    method: 'lead_details',
                    id: id,
                },
                success: function(response) {
                    if (response.type === 1 && Array.isArray(response.result)) {
                        let rows = '';
                        response.result.forEach((item, index) => {
                            rows += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.person}</td>
                                    <td>${item.comment}<br><small>${item.message}</small></td>
                                    <td>${item.next_follow}</td>
                                    <td>${item.timestamp}</td>
                                </tr>
                            `;
                        });
                        $('#l_tbl').html(rows);
                    } else {
                        $('#l_tbl').html('<tr><td colspan="5" class="text-center">No data found</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    // response = JSON.parse(response);
                    // toast('success', response.result);
                    // $('#comment_form').modal('hide');
                    // unsubscribeList()
                    // Optional: show error message
                },
                complete: function() {
                    $(btn).prop('disabled', false);
                }
            });
        }
        $('#comment_form').modal('show');
    }
    
    function openWhatsApp(id, btn, ph){
        // quill.setText("");
        $('#what_id').val(id);
        $('#lead_phone').val(ph);
        $('#whatsapp_form').modal('show');
    }
    
    function sendWhatsApp(btn){
        let what_id = $('#what_id').val();
        let ph = $('#lead_phone').val();
        let template_id = $('#waTemplateID').val();
        
        if(what_id != '' && ph != '' && template_id != ''){
            $(btn).html('Sending...').prop('disabled', true);
            let ins = "<?= WHATSAPP_API_INS ?>";
            let api_key = "<?= WHATSAPP_API_KEY ?>";
            let whats_url = "<?= WHATSAPP_SERVER ?>";
    
            // First fetch template body from backend
            $.ajax({
                url: origin + "/ajax/service/salse_marketing_services.php",
                method: "POST",
                data: { method: 'get_single_template',id: template_id },
                success: function(res){
                    try {
                        let data = JSON.parse(res);
                        if(data.success){
                            let message = data.data.body;
                            // console.log(message);
                            // return message;
                            $.ajax({
                                url: `${whats_url}client/sendMessage/${ins}`,
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "x-api-key": `${api_key}`
                                },
                                data: JSON.stringify({
                                    chatId: `${ph}@c.us`,
                                    contentType: "string",
                                    content: message,
                                }),
                                success: function (response) {
                                    if(response.success){
                                        toast('success', response.message);
                                    }else{
                                        toast('error', response.message);
                                    }
                                },
                                error: function (xhr) {
                                    toast('error', "Something went wrong!");
                                },
                                complete: function() {
                                    $(btn).html('Send').prop('disabled', false);
                                }
                            });
                        } else {
                            toast('error', data.message);
                            $(btn).html('Send').prop('disabled', false);
                        }
                    } catch (e) {
                        toast('error', "Invalid response");
                        $(btn).html('Send').prop('disabled', false);
                    }
                },
                error: function(){
                    toast('error', "Unable to fetch template body");
                    $(btn).html('Send').prop('disabled', false);
                }
            });
        } else {
            toast('error', "Please select a template");
        }
        $('#whatsapp_form').modal('hide');
    }
        
        
    const comment_submit = (btn) => {
        const com_comment = $('#com_comment').val();
        const com_message = $('#com_message').val();
        const com_dateInput = $('#com_dateInput').val();
        const lead_id = $('#lead_id').val();
        
        $(btn).html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Loading...`)
              .prop('disabled', true);
        
        $.ajax({
            url: origin + "/ajax/service/topSpendersServices.php",
            method: "POST",
            // contentType: "application/json",
            dataType: "json",
            data: {
                method: 'lead_comment',
                com_comment: com_comment,
                com_message: com_message,
                com_dateInput: com_dateInput,
                lead_id: lead_id
            },
            success: function(response) {
                // console.log(response.result)
                // response = JSON.parse(response);
                toast('success', response.result);
                $('#comment_form').modal('hide');
                unsubscribeList()
            },
            error: function(xhr, status, error) {
                // response = JSON.parse(response);
                toast('success', response.result);
                $('#comment_form').modal('hide');
                unsubscribeList()
                // Optional: show error message
            },
            complete: function() {
                $(btn).html('Submit').prop('disabled', false);
            }
        });
    };

    
    const drawIDchange = () => {
        try {
            $('#datefilterLogin').trigger('cancel.daterangepicker');
        } catch (e) {
            console.log(`Error: ${e.message}`);
        }
    }
    var url01 = origin + "/ajax/service/datatable_services.php";
    $(function () {
        
        $('#comment_submit').on('click', function () {
            comment_submit(this);
        });
        $('#whatsapp_submit').on('click', function () {
            sendWhatsApp(this);
        });
        
         $(document).on('change', '.toggle-profile', function () {
            let checkbox = $(this);
            let isChecked = checkbox.is(':checked');
            let slider = checkbox.siblings('span');
            let innerKnob = slider.find('.slider-inner');
        
            slider.css('background-color', isChecked ? '#49117cc9' : '#ccc');
            innerKnob.css('transform', isChecked ? 'translateX(22px)' : 'translateX(0)');
        
            let crmId = checkbox.data('id');
            let userId = checkbox.data('userid');
            let status = isChecked ? 1 : 0;
            $.ajax({
                url: url01,
                method: 'POST',
                data: {
                    method: 'change_sub_status',
                    crmId: crmId,
                    userId: userId,
                    status: status
                },
                success: function (data) {
                    var response = JSON.parse(data);
                    if (response.type == 1) {
                        toast('success', response.result);
                    } else {
                        toast('error', response.result);
        
                        checkbox.prop('checked', !isChecked);
                        slider.css('background-color', !isChecked ? '#49117cc9' : '#ccc');
                        innerKnob.css('transform', !isChecked ? 'translateX(22px)' : 'translateX(0)');
                    }
                },
                 error: function () {
                    toast('error', 'Something went wrong');
        
                    checkbox.prop('checked', !isChecked);
                    slider.css('background-color', !isChecked ? '#49117cc9' : '#ccc');
                    innerKnob.css('transform', !isChecked ? 'translateX(22px)' : 'translateX(0)');
                }
            });
        });
        
        try {
            createDatePicker('datefilterLogin');

            $('#datefilterLogin').trigger('cancel.daterangepicker');
            unsubscribeList();
        } catch (e) {
            console.log(`Error: ${e.message}`);
        }
    });
    
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