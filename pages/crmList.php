<?php
$pageTitle = "CRM List";


?> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>  
    
    .card-header.d-lg-flex.d-block.justify-content-between {
        border-bottom: none;
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

                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <span>Search (CRM / Name / Mobile / Email)</span>

                                            <input class="form-control" type="text" id="searchTxt" name="searchTxt"
                                                placeholder="CRM / Name / Mobile / Email" value=""
                                                oninput="this.value = this.value.replace(/[^A-Za-z. ]/g, ''); if (this.value.length > 3) { unsubscribeList(); }"
                                                maxlength="70" />

                                        </div>


                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <span>Select Date (Created At)</span>

                                            <input class="form-control" type="text" id="datefilterLogin"
                                                name="datefilterLogin" placeholder="Select Date" value="" readonly />
                                        </div>

                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <span>Status</span>
                                            <select id="statusVal" class="selectpicker" data-live-search="true">
                                                <option value="">Select Status</option>
                                                <?php
                                                foreach ($payStatusArray as $key => $value) {
                                                    ?>
                                                    <option value="<?= $key; ?>"><?= $value; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>


                                        <div class="col-sm-12 col-md-6 col-lg-3">
                                            <span>Plan</span>
                                            <select id="statusPlan" class="selectpicker" data-live-search="true">
                                                <option value="">Select Plan</option>
                                                <?php
                                                $seP = mysqli_query($con, "SELECT JSON_UNQUOTE(JSON_EXTRACT(checkout_response, '$.productDetails.name')) AS productName FROM `subscriptions` GROUP BY productName;");
                                                if (mysqli_num_rows($seP) > 0) {
                                                    while ($row = mysqli_fetch_assoc($seP)) {
                                                        ?>
                                                        <option value="<?= $row['productName']; ?>"><?= $row['productName']; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        
                                        <div class="col-sm-12 col-md-6 col-lg-3">
                                            <span>Campaign Source</span> &nbsp; <span style="color:red;"></span>
                                            <select id="utm_campaign_Source" class="selectpicker" >
                                                <option value="" selected>Select UTM Source</option>

                                                <?php

                                                $utm_campaign = mysqli_query($con, "SELECT utm_source FROM `subscriptions` WHERE utm_source IS NOT null GROUP BY utm_source;");
                                                if (mysqli_num_rows($utm_campaign) > 0) {
                                                    while ($row = mysqli_fetch_assoc($utm_campaign)) {
                                                ?>
                                                        <option value="<?= $row['utm_source']; ?>">
                                                            <?= $row['utm_source']; ?>
                                                        </option>
                                                <?php
                                                    }
                                                }
                                                ?>

                                            </select>
                                        </div>

                                        <div class="col-sm-12 col-md-6 col-lg-3">
                                            <span>UTM Campaign</span> &nbsp; <span style="color:red;"></span>
                                            <select id="utm_campaign" class="selectpicker">
                                                <option value="" selected="">Select UTM Campaign</option>

                                                <?php

                                                $utm_campaign = mysqli_query($con, "SELECT utm_campaign FROM `subscriptions` WHERE utm_campaign IS NOT null GROUP BY utm_campaign;");
                                                if (mysqli_num_rows($utm_campaign) > 0) {
                                                    while ($row = mysqli_fetch_assoc($utm_campaign)) {
                                                ?>
                                                        <option value="<?= $row['utm_campaign']; ?>">
                                                            <?= $row['utm_campaign']; ?>
                                                        </option>
                                                <?php
                                                    }
                                                }
                                                ?>

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
                                                            <th class="column_sort sorting sorting_asc">Createdon</th>
                                                            <th class="column_sort sorting sorting_asc">Subscription ID
                                                            </th>
                                                            <th class="column_sort sorting sorting_asc">Plan Name</th>
                                                            <th class="column_sort sorting sorting_asc">CRM</th>
                                                            <th class="column_sort sorting sorting_asc">Status</th>
                                                            <th class="column_sort sorting sorting_asc">Crm Setup</th>
                                                            <th class="column_sort sorting sorting_asc">Website Created</th>
                                                            <th class="column_sort sorting sorting_asc">Expired At</th>
                                                            <th class="column_sort sorting sorting_asc">Plan Type</th>
                                                            <th class="column_sort sorting sorting_asc">Name</th>
                                                            <th class="column_sort sorting sorting_asc">Mobile</th>
                                                            <th class="column_sort sorting sorting_asc">Email</th>
                                                            <th class="column_sort sorting sorting_asc">Action</th>
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
                        method: 'CRMLsitReprot',
                        dateFilter: dateFilter,
                        startDate: startDate,
                        endDate: endDate,
                        searchTxt: $(`#searchTxt`).val(),
                        statusVal: $(`#statusVal`).val(),
                        statusPlan: $(`#statusPlan`).val(),
                        utm_source: $(`#utm_campaign_Source`).val(),
                        utm_campaign: $(`#utm_campaign`).val()
                        
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
                //     type: 'num',
                //     targets: [2]
                // }],
                dom: 'Bfrtip',
                buttons: [
                    'pageLength',
                    'copy',
                    {
                        extend: 'excelHtml5',
                        title: 'CRM List'
                    },
                ],
                columns: [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            let dateTi = data?.createdon || '';
                            return dateTi != '' ? moment(dateTi).format("DD MMM YYYY hh:mm A") : '';
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return data?.subScriptionID || ''
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            let cart = data.checkout_response ? JSON.parse(data.checkout_response) : [];
                            return cart?.productDetails?.name || '';
                        }
                    },
                    // {
                    //     data: 'grandtotal',
                    //     render: function (data, type, row, meta) {
                    //         return parseFloat(data)
                    //     }
                    // },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            let dom = data?.subDomainName || '';
                            return dom ? `<a target="_blank" style="color: green;font-weight: 900;" href="https://${dom + '.' + mainDomain}">${dom}</a>` : '';

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
                            return _.capitalize(data?.subStatus || '')
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row) {
                            const bookingIcon = row.bookingsetting_count > 0 
                                ? '<i class="fas fa-check-square" style="color: green;"></i>' 
                                : '<i class="fas fa-times-circle" style="color: red;"></i>';

                            const emailIcon = row.emailsetting_count > 0 
                                ? '<i class="fas fa-check-square" style="color: green;"></i>' 
                                : '<i class="fas fa-times-circle" style="color: red;"></i>';

                            const vehicleIcon = row.vehicle_count > 0 
                                ? '<i class="fas fa-check-square" style="color: green;"></i>' 
                                : '<i class="fas fa-times-circle" style="color: red;"></i>';

                            return `
                                <div>
                                    ${bookingIcon} Booking Setting<br>
                                    ${emailIcon} Email Setting<br>
                                    ${vehicleIcon} Vehicle Creation
                                </div>
                            `;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return row.gentral_setting_count > 0 ? 'Created' : 'Pending';
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            let dateTi = data?.expiryDate || '';
                            return dateTi != '' ? moment(dateTi).format("DD MMM YYYY") : '';
                        }
                    },
                    // {
                    //     data: null,
                    //     render: function (data, type, row, meta) {
                    //         return _.capitalize(data?.gateway || '')
                    //     }
                    // },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return _.capitalize(data?.planType === 'TRIAL' ? 'TRAIL' : data?.planType || '');
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {


                            return data?.name ? `<a target="_blank" style="color: #525704; font-weight: 900;" href="profile/edit/${data?.userID}">${data?.name}</a>` : '';
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return data?.mobile ? `<a target="_blank" style="color: #525704; font-weight: 900;" href="tel:${data?.mobile}">${data?.mobile}</a>` : '';

                        }
                    },

                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return data?.email ? `<a target="_blank" style="color: #525704; font-weight: 900;" href="mailto:${data?.email}">${data?.email}</a>` : '';
                        }
                    },

                    
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            let checked = row.manual_sub_access == 1 ? 'checked' : '';
                            let bgColor = row.manual_sub_access == 1 ? '#49117cc9' : '#ccc';
                            let translate = row.manual_sub_access == 1 ? 'translateX(22px)' : 'translateX(0)';
                    
                            return `
                                <label style="position: relative; display: inline-block; width: 45px; height: 22px;" title="Manual Subscription Access">
                                    <input type="checkbox" class="toggle-profile" data-id="${row.id}" data-userid="${row.userID}" ${checked}
                                        style="opacity: 0; width: 0; height: 0;">
                                    <span style="
                                        position: absolute;
                                        cursor: pointer;
                                        top: 0; left: 0; right: 0; bottom: 0;
                                        background-color: ${bgColor};
                                        transition: 0.4s;
                                        border-radius: 22px;
                                    ">
                                        <span style="
                                            content: '';
                                            position: absolute;
                                            height: 16px;
                                            width: 16px;
                                            left: 4px;
                                            bottom: 3px;
                                            background-color: white;
                                            border-radius: 50%;
                                            transition: 0.4s;
                                            transform: ${translate};
                                        " class="slider-inner"></span>
                                    </span>
                                </label>
                            `;
                        }
                    }

                    // {
                    //     data: 'TotalSpending',
                    //     render: function (data, type, row, meta) {
                    //         return parseFloat(data)
                    //     }
                    // },
                    // {
                    //     data: 'TotalTapWinning',
                    //     render: function (data, type, row, meta) {
                    //         return parseFloat(data)
                    //     }
                    // },
                    // {
                    //     data: 'TotalWinning',
                    //     render: function (data, type, row, meta) {
                    //         return parseFloat(data)
                    //     }
                    // },
                    // {
                    //     data: 'TotalWinningCombined',
                    //     render: function (data, type, row, meta) {
                    //         return parseFloat(data)
                    //     }
                    // },
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
    const drawIDchange = () => {
        try {
            $('#datefilterLogin').trigger('cancel.daterangepicker');
        } catch (e) {
            console.log(`Error: ${e.message}`);
        }
    }
    var url01 = origin + "/ajax/service/datatable_services.php";
    $(function () {
        
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