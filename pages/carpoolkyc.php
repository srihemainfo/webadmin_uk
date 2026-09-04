<?php
$pageTitle = "Customer KYC Dashboard";
?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.js"></script>
<div id="customer-kyc-wrapper">
    <style>
        #customer-kyc-wrapper {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            color: #334155;
        }
        .viewer-container {
            z-index: 9999999 !important;
        }
        .br-12 {
            border-radius: 12px !important;
        }
        .br-8 {
            border-radius: 8px !important;
        }
        .daterangepicker .ranges ul {
            min-width: 160px !important;
        }
        .kyc-toast-container {
            z-index: 999999999 !important;
        }
        .custom-scroll {
            max-height: 140px;
            overflow-y: auto;
            padding-right: 5px;
            font-size: 13px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 6px;
            padding: 10px;
            line-height: 1.6;
        }
        .custom-scroll::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
        }
        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background-color: #94a3b8;
        }
        .form-select-custom {
            border: 1px solid #cbd5e1;
            color: #1e293b;
            background-color: #f8fafc;
            font-size: 14px;
            font-weight: 700;
            padding: 8px 30px 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            box-shadow: none;
            transition: all 0.2s;
        }
        .form-select-custom:focus {
            outline: none;
            border-color: #475569;
            box-shadow: 0 0 0 3px rgba(71, 85, 105, 0.15);
        }
        .nav-tabs {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            padding: 0;
            background: transparent;
            border: none;
        }
        .nav-tabs .nav-link {
            color: #475569;
            font-weight: 700;
            border: 1px solid #cbd5e1;
            padding: 10px 24px;
            text-transform: capitalize;
            letter-spacing: 0.5px;
            border-radius: 8px;
            margin: 0;
            background: #fff;
            transition: 0.2s;
        }
        .nav-tabs .nav-link.active {
            color: #ffffff;
            border-color: #64748b;
            background: #64748b;
        }
        .sub-tabs {
            gap: 10px;
        }
        .sub-tabs .nav-link {
            color: #64748b;
            background: #e2e8f0;
            font-weight: 700;
            border-radius: 20px;
            padding: 6px 20px;
            transition: all 0.2s;
            border: none;
        }
        .sub-tabs .nav-link.active {
            background: #64748b;
            color: #fff;
        }
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 4px;
            border: 1px solid #cbd5e1;
            padding: 6px 12px;
        }
        table.dataTable {
            border-collapse: collapse !important;
            width: 100% !important;
            border: 1px solid #e2e8f0;
            font-size: 14px;
        }
        table.dataTable>thead {
            background-color: #f8fafc;
            border-bottom: 2px solid #cbd5e1;
        }
        table.dataTable>thead>tr>th {
            color: #334155;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
        }
        table.dataTable>tbody>tr>td {
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
            color: #0f172a;
        }
        table.dataTable>tbody>tr:nth-of-type(odd) {
            background-color: #fcfcfc;
        }
        table.dataTable>tbody>tr:hover {
            background-color: #f1f5f9;
        }
        .view-image-static-btn {
            background-color: #8c98a4 !important;
            color: #ffffff !important;
            border: none !important;
            padding: 5px 12px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
            transition: none !important;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            outline: none;
        }
        .view-image-static-btn:hover,
        .view-image-static-btn:focus,
        .view-image-static-btn:active {
            background-color: #8c98a4 !important;
            box-shadow: none !important;
        }
        /* Fix the modal image oval bug */
        #modalDlImages img {
            border-radius: 4px !important;
            clip-path: none !important;
            cursor: zoom-in;
        }
    </style>
    <div class="main-content app-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid mt-4 p-0">
                <ul class="nav nav-tabs" id="kycTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="selfie-tab" data-bs-toggle="tab" data-bs-target="#kyc-pane" type="button" role="tab">
                            <i class="fa fa-user me-2"></i> SELFIE VERIFICATION
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="dl-tab" data-bs-toggle="tab" data-bs-target="#kyc-pane" type="button" role="tab">
                            <i class="fa fa-id-card me-2"></i> DL VERIFICATION
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="vehicle-tab" data-bs-toggle="tab" data-bs-target="#kyc-pane" type="button" role="tab">
                            <i class="fa fa-car me-2"></i> VEHICLE VERIFICATION
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="kycTabsContent">
                    <div class="tab-pane fade show active" id="kyc-pane" role="tabpanel">
                        <div class="d-flex flex-wrap gap-3 mb-3 align-items-center bg-white p-3 br-12 border shadow-sm">
                            <div style="min-width: 220px; flex-grow: 1;">
                                <label class="fw-bold small text-muted text-uppercase mb-1">Search User</label>
                                <input type="text" id="searchTxt" class="form-control br-8" placeholder="Name, Mobile, Email">
                            </div>
                            <div style="min-width: 200px;">
                                <label class="fw-bold small text-muted text-uppercase mb-1">Filter By State</label>
                                <select id="stateFilter" class="form-select br-8" onchange="fetchData()">
                                    <option value="">All States</option>
                                </select>
                            </div>
                            <div style="min-width: 200px;">
                                <label class="fw-bold small text-muted text-uppercase mb-1">Date Range</label>
                                <input type="text" id="dateRange" class="form-control br-8" placeholder="Select Dates">
                            </div>
                            <div class="ms-auto pt-4">
                                <button class="btn btn-light border br-8 fw-bold text-muted" onclick="resetFilters()">
                                    <i class="fa fa-sync-alt me-1"></i> RESET
                                </button>
                            </div>
                        </div>
                        <ul class="nav nav-pills mb-3 sub-tabs">
                            <li class="nav-item">
                                <button class="nav-link active d-flex align-items-center" data-status="pending">
                                    Pending <span class="badge bg-secondary ms-2 rounded-pill" id="count-pending">0</span>
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link d-flex align-items-center" data-status="approved">
                                    Approved <span class="badge bg-secondary ms-2 rounded-pill" id="count-approved">0</span>
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link d-flex align-items-center" data-status="rejected">
                                    Rejected <span class="badge bg-secondary ms-2 rounded-pill" id="count-rejected">0</span>
                                </button>
                            </li>
                        </ul>
                        <div class="bg-white br-12 border shadow-sm p-3">
                            <table id="kycDt" class="table align-middle w-100">
                                <thead id="kyc-thead">
                                </thead>
                                <tbody id="kyc-tbody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editDlModal" tabindex="-1" aria-labelledby="editDlModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content br-12">
            <div class="modal-header bg-light border-bottom">
                <h5 class="modal-title fw-bold" id="editDlModalLabel"><i class="fa fa-edit me-2"></i>Edit DL Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 border-end">
                        <h6 class="fw-bold mb-3 text-uppercase text-muted">DL Images (Click to Zoom)</h6>
                        <div id="modalDlImages" class="d-flex flex-column gap-3 text-center">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3 text-uppercase text-muted">DL Information</h6>
                        <form id="editDlForm">
                            <input type="hidden" id="editDlUserId">
                            <div class="mb-3">
                                <label for="editDlNumber" class="form-label fw-bold">DL Number</label>
                                <input type="text" class="form-control br-8" id="editDlNumber" placeholder="Enter valid DL number" required>
                                <small class="text-muted mt-1 d-block">Make sure to enter the exact DL Number without extra spaces.</small>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="button" class="btn btn-primary fw-bold br-8 py-2" id="saveDlBtn" onclick="saveDlDetails()">
                                    <i class="fa fa-save me-1"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const API_URL = origin + "/ajax/service/carpool_services.php";
    let currentData = [];
    let currentViewer = null;
    let editModalViewer = null;
    let dtTable = null;
    let activeMainTab = 'selfie';
    let activeSubTab = 'pending';
    $(document).ready(function() {
        // Load Unique States
        loadStates();
        let todayStr = moment().format('DD/MM/YYYY') + ' - ' + moment().format('DD/MM/YYYY');
        $('#dateRange').val(todayStr);
        let dateOptions = {
            autoUpdateInput: false,
            startDate: moment(),
            endDate: moment(),
            locale: {
                format: 'DD/MM/YYYY',
                cancelLabel: 'Clear',
                customRangeLabel: 'Custom Range'
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')]
            }
        };
        $('#dateRange').daterangepicker(dateOptions).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            fetchData();
        }).on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            fetchData();
        });
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            let id = e.target.id;
            if (id === 'selfie-tab') activeMainTab = 'selfie';
            else if (id === 'dl-tab') activeMainTab = 'dl';
            else if (id === 'vehicle-tab') activeMainTab = 'vehicle';
            activeSubTab = 'pending';
            $('.sub-tabs .nav-link').removeClass('active');
            $('.sub-tabs .nav-link[data-status="pending"]').addClass('active');
            fetchData();
        });
        $('.sub-tabs .nav-link').on('click', function() {
            $('.sub-tabs .nav-link').removeClass('active');
            $(this).addClass('active');
            activeSubTab = $(this).attr('data-status');
            renderTable();
        });
        let typingTimer;
        $('#searchTxt').on('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => fetchData(), 500);
        });
        $(document).on('click', '.view-selfie-btn', function() {
            let btn = $(this);
            let userId = btn.data('id');
            btn.html('<i class="fa-spin"></i> Loading...').prop('disabled', true);
            $.ajax({
                url: API_URL,
                type: 'POST',
                dataType: 'json',
                data: {
                    method: 'fetch_document_image',
                    id: userId,
                    tab: activeMainTab
                },
                success: function(res) {
                    if (res && res.status && res.url && !res.url.includes('placehold.co')) {
                        let imgLoad = new Image();
                        imgLoad.onload = function() {
                            btn.html('<i class=""></i> View Selfie').prop('disabled', false);
                            if (currentViewer) {
                                currentViewer.destroy();
                            }
                            $('#temp-viewer-gallery').remove();
                            let $img = $('<img id="temp-viewer-gallery" src="' + res.url + '" style="display:none;">');
                            $('body').append($img);
                            currentViewer = new Viewer($img[0], {
                                button: true,
                                navbar: false,
                                title: false,
                                toolbar: {
                                    zoomIn: 1,
                                    zoomOut: 1,
                                    oneToOne: 1,
                                    reset: 1,
                                    rotateLeft: 1,
                                    rotateRight: 1
                                },
                                hidden: function() {
                                    if (currentViewer) currentViewer.destroy();
                                    $('#temp-viewer-gallery').remove();
                                }
                            });
                            currentViewer.show();
                        };
                        imgLoad.onerror = function() {
                            btn.html('<i class=""></i> View Selfie').prop('disabled', false);
                            toast('error', 'Failed to load image file.');
                        };
                        imgLoad.src = res.url;
                    } else {
                        btn.html('<i class=""></i> View Selfie').prop('disabled', false);
                        toast('info', 'No image available for this user.');
                    }
                },
                error: function() {
                    btn.html('<i class=""></i> View Selfie').prop('disabled', false);
                    toast('error', 'Failed to fetch image from server');
                }
            });
        });
        // Combined click handler for DL and Vehicle direct images
        $(document).on('click', '.view-dl-img-btn, .view-veh-img-btn', function() {
            let btn = $(this);
            let url = btn.data('url');
            let origHtml = btn.html();
            btn.html('<i class="fa-spin"></i> Loading...').prop('disabled', true);
            let imgLoad = new Image();
            imgLoad.onload = function() {
                btn.html(origHtml).prop('disabled', false);
                if (currentViewer) {
                    currentViewer.destroy();
                }
                $('#temp-viewer-gallery').remove();
                let $img = $('<img id="temp-viewer-gallery" src="' + url + '" style="display:none;">');
                $('body').append($img);
                currentViewer = new Viewer($img[0], {
                    button: true,
                    navbar: false,
                    title: false,
                    toolbar: {
                        zoomIn: 1,
                        zoomOut: 1,
                        oneToOne: 1,
                        reset: 1,
                        rotateLeft: 1,
                        rotateRight: 1
                    },
                    hidden: function() {
                        if (currentViewer) currentViewer.destroy();
                        $('#temp-viewer-gallery').remove();
                    }
                });
                currentViewer.show();
            };
            imgLoad.onerror = function() {
                btn.html(origHtml).prop('disabled', false);
                toast('error', 'Failed to load image file.');
            };
            imgLoad.src = url;
        });
        fetchData();
    });
    function loadStates() {
        $.ajax({
            url: API_URL,
            type: 'POST',
            dataType: 'json',
            data: {
                method: 'fetch_states'
            },
            success: function(res) {
                if (res && res.type == 1 && res.result) {
                    let opts = '<option value="">All States</option>';
                    res.result.forEach(function(state) {
                        opts += `<option value="${state}">${state}</option>`;
                    });
                    $('#stateFilter').html(opts);
                }
            }
        });
    }
    function resetFilters() {
        $('#searchTxt').val('');
        $('#stateFilter').val('');
        let todayStr = moment().format('DD/MM/YYYY') + ' - ' + moment().format('DD/MM/YYYY');
        $('#dateRange').val(todayStr);
        $('#dateRange').data('daterangepicker').setStartDate(moment());
        $('#dateRange').data('daterangepicker').setEndDate(moment());
        fetchData();
    }
    function generateLoaderHtml() {
        return `
        <div class="col-12 text-center py-5 my-4">
            <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;"></div>
            <h5 class="fw-bold text-secondary tracking-widest text-uppercase">LOADING DATA...</h5>
        </div>`;
    }
    function getRecordStatus(item) {
        let raw = '';
        if (activeMainTab === 'selfie') {
            raw = String(item.selfie_status || 'pending').toLowerCase();
        } else if (activeMainTab === 'dl') {
            let vd = {};
            try {
                vd = JSON.parse(item.vehicle_details || '{}');
            } catch (e) {}
            if (vd.dl_status) {
                raw = String(vd.dl_status).toLowerCase();
            } else {
                raw = String(item.dl_status || item.kyc_dl_status || 'pending').toLowerCase();
                if (item.dl_response) {
                    try {
                        let r = JSON.parse(item.dl_response);
                        if (r.code || !r.id_no) raw = 'rejected';
                    } catch (e) {}
                }
            }
        } else if (activeMainTab === 'vehicle') {
            let vd = {};
            try {
                vd = JSON.parse(item.vehicle_details || '{}');
            } catch (e) {}
            raw = String(vd.car_type_status || 'pending').toLowerCase();
        }
        if (raw === 'approved' || raw === '1') return 'approved';
        if (raw === 'rejected' || raw === 'failed' || raw === '0') return 'rejected';
        return 'pending';
    }
    function updateCounts() {
        let p = 0,
            a = 0,
            r = 0;
        currentData.forEach(item => {
            let s = getRecordStatus(item);
            if (s === 'approved') a++;
            else if (s === 'rejected') r++;
            else p++;
        });
        $('#count-pending').text(p);
        $('#count-approved').text(a);
        $('#count-rejected').text(r);
    }
    function fetchData() {
        if ($.fn.DataTable.isDataTable('#kycDt')) {
            $('#kycDt').DataTable().clear().destroy();
        }
        $('#kyc-tbody').html(`<tr><td colspan="10">${generateLoaderHtml()}</td></tr>`);
        let targetMethod = 'fetch_selfie_data';
        if (activeMainTab === 'dl') targetMethod = 'fetch_dl_data';
        else if (activeMainTab === 'vehicle') targetMethod = 'fetch_vehicle_data';
        let reqData = {
            method: targetMethod,
            searchTxt: $('#searchTxt').val(),
            stateFilter: $('#stateFilter').val(),
            dateRange: $('#dateRange').val()
        };
        $.ajax({
            url: API_URL,
            type: 'POST',
            dataType: 'json',
            data: reqData,
            success: function(res) {
                if (res && res.type == 1 && res.result) {
                    currentData = res.result;
                    renderTable();
                } else {
                    currentData = [];
                    renderTable();
                }
            },
            error: function() {
                $('#kyc-tbody').html('<tr><td colspan="10" class="text-center text-danger py-4 fw-bold">Network Error.</td></tr>');
            }
        });
    }
    function renderTable(preservePage = false) {
        updateCounts();
        let filteredData = currentData.filter(item => getRecordStatus(item) === activeSubTab);
        let theadHtml = `
            <tr>
                <th style="width: 50px;">S.NO</th>
                <th>DATE & TIME</th>
                <th>USER DETAILS</th>
                <th>STATE & DISTRICT</th>
        `;
        if (activeMainTab === 'selfie') {
            theadHtml += `<th>SELFIE IMAGE</th>`;
        } else if (activeMainTab === 'dl') {
            theadHtml += `<th style="width:250px;">DL DETAILS</th>`;
        } else if (activeMainTab === 'vehicle') {
            theadHtml += `<th>LIC TYPE</th><th>SELECTED VEHICLE</th>`;
        }
        theadHtml += `
                <th>STATUS</th>
                <th class="text-center" style="width:140px;">ACTION</th>
            </tr>
        `;
        $('#kyc-thead').html(theadHtml);
        let tbodyHtml = '';
        filteredData.forEach((item, index) => {
            
            // ==========================================
            // DYNAMIC DATE FIELD SELECTION FIX
            // ==========================================
            let dateField = null;
            if (activeMainTab === 'selfie') {
                dateField = item.kyc_updated_at || item.v_updated_at;
            } else if (activeMainTab === 'dl') {
                dateField = item.kyc_updated_at || item.v_updated_at;
            } else if (activeMainTab === 'vehicle') {
                dateField = item.v_updated_at;
            }
            
            let displayDate = dateField ? new Date(dateField) : new Date();
            let dateFormatted = moment(displayDate).format('MMM Do YYYY, hh:mm A');
            let stateHtml = `
                <span class="d-block fw-bold">${item.current_state || 'N/A'}</span>
                <span class="text-muted small">${item.current_district || 'N/A'}</span>
            `;
            let statusStr = getRecordStatus(item);
            let badgeHtml = '';
            let reasonStr = '';
            let rowContent = '';
            let actionBtns = '';
            if (activeMainTab === 'selfie') {
                rowContent = `<td><button class="view-image-static-btn view-selfie-btn" data-id="${item.user_id}"><i class=""></i> View Selfie</button></td>`;
                reasonStr = item.selfie_reason || '';
                actionBtns = `
                    <button class="btn btn-sm btn-success px-3 br-8 me-1" onclick="processAction(${item.user_id}, 'verify_selfie', 'approve')" title="Approve"><i class="fa fa-check"></i></button>
                    <button class="btn btn-sm btn-danger px-3 br-8" onclick="processAction(${item.user_id}, 'verify_selfie', 'reject')" title="Reject"><i class="fa fa-times"></i></button>
                `;
            } else if (activeMainTab === 'dl') {
                let dlHtml = `<div class="custom-scroll">`;
                // Determine the currently displayed DL number for the modal edit parameter
                let activeDlNoForModal = item.dl_no || '';
                if (item.dl_response && item.dl_response.trim() !== '' && item.dl_response !== 'null') {
                    try {
                        let parsed = JSON.parse(item.dl_response);
                        if (parsed.code || !parsed.id_no) {
                            dlHtml += `<span class="text-danger fw-bold">API Error:</span> ${parsed.message || parsed.details || 'Not Found'}<br><span class="text-dark"><b>Entered DL:</b></span> ${item.dl_no || 'N/A'}`;
                        } else {
                            activeDlNoForModal = parsed.id_no; // Capture the real DL number displayed to users
                            let licTypeArr = [];
                            if (parsed["NT"]) licTypeArr.push(parsed["NT"]);
                            if (parsed["TR"]) licTypeArr.push(parsed["TR"]);
                            if (parsed["COV"]) licTypeArr.push(parsed["COV"]);
                            if (parsed["Class Of Vehicle"]) licTypeArr.push(parsed["Class Of Vehicle"]);
                            let licTypeStr = licTypeArr.length > 0 ? licTypeArr.join(", ") : "MCWG";
                            dlHtml += `
                                <span class="text-dark"><b>Name:</b> ${parsed["Holder's Name"] || 'N/A'}</span><br>
                                <span class="text-dark"><b>DL No:</b> ${parsed["id_no"] || 'N/A'}</span><br>
                                <span class="text-dark"><b>Status:</b> <span class="${parsed["Current Status"] === 'ACTIVE' ? 'text-success' : 'text-danger'} fw-bold">${parsed["Current Status"] || 'N/A'}</span></span><br>
                                <span class="text-dark"><b>Type:</b> ${licTypeStr}</span><br>
                                <span class="text-dark"><b>Expiry:</b> ${parsed["date_of_expiry"] || 'N/A'}</span>
                            `;
                        }
                    } catch (e) {
                        dlHtml += `<span class="text-dark"><b>Entered DL:</b></span> ${item.dl_no || 'N/A'}`;
                    }
                } else {
                    dlHtml += `<span class="text-dark"><b>Entered DL:</b></span> ${item.dl_no || 'N/A'}`;
                }
                dlHtml += `</div>`;
                let vd = {};
                try {
                    vd = JSON.parse(item.vehicle_details || '{}');
                } catch (e) {}
                reasonStr = vd.dl_reason || '';
                let dlFrontUrl = item.dl_front && item.dl_front !== 'null' ? item.dl_front : '';
                let dlBackUrl = item.dl_back && item.dl_back !== 'null' ? item.dl_back : '';
                let dlButtons = `<div class="mt-2 d-flex flex-wrap gap-2">`;
                if (!dlFrontUrl && !dlBackUrl) {
                    dlButtons += `<span class="badge bg-light text-danger border mt-1" style="font-size: 11px;">NO IMAGE AVAILABLE</span>`;
                } else {
                    if (dlFrontUrl) dlButtons += `<button class="view-image-static-btn view-dl-img-btn" data-url="${dlFrontUrl}"><i class=""></i> View Front</button>`;
                    if (dlBackUrl) dlButtons += `<button class="view-image-static-btn view-dl-img-btn" data-url="${dlBackUrl}"><i class=""></i> View Back</button>`;
                }
                dlButtons += `</div>`;
                rowContent = `<td>${dlHtml}${dlButtons}</td>`;
                // ADDING EDIT BUTTON FOR DL VERIFICATION
                let cleanDlNo = activeDlNoForModal.replace(/'/g, "\\'");
                actionBtns = `
                    <button class="btn btn-sm btn-info px-3 br-8 mb-1 text-white" onclick="openEditDlModal(${item.user_id}, '${cleanDlNo}', '${dlFrontUrl}', '${dlBackUrl}')" title="Edit DL Number"><i class="fa fa-edit"></i></button>
                    <button class="btn btn-sm btn-success px-3 br-8 mb-1" onclick="processAction(${item.user_id}, 'verify_dl', 'approve')" title="Approve"><i class="fa fa-check"></i></button>
                    <button class="btn btn-sm btn-danger px-3 br-8 mb-1" onclick="processAction(${item.user_id}, 'verify_dl', 'reject')" title="Reject"><i class="fa fa-times"></i></button>
                `;
            } else if (activeMainTab === 'vehicle') {
                let licTypeStr = 'N/A';
                if (item.dl_response && item.dl_response.trim() !== '' && item.dl_response !== 'null') {
                    try {
                        let parsed = JSON.parse(item.dl_response);
                        if (!parsed.code && parsed.id_no) {
                            let arr = [];
                            if (parsed["NT"]) arr.push(parsed["NT"]);
                            if (parsed["TR"]) arr.push(parsed["TR"]);
                            if (parsed["COV"]) arr.push(parsed["COV"]);
                            if (parsed["Class Of Vehicle"]) arr.push(parsed["Class Of Vehicle"]);
                            if (arr.length > 0) licTypeStr = arr.join(", ");
                        }
                    } catch (e) {}
                }
                let dlFrontUrl = item.dl_front && item.dl_front !== 'null' ? item.dl_front : '';
                let dlBackUrl = item.dl_back && item.dl_back !== 'null' ? item.dl_back : '';
                let licTypeButtons = `<div class="mt-2 d-flex flex-column gap-1">`;
                if (!dlFrontUrl && !dlBackUrl) {
                    licTypeButtons += `<span class="badge bg-light text-danger border mt-1" style="font-size: 10px;">NO DL IMAGES</span>`;
                } else {
                    if (dlFrontUrl) licTypeButtons += `<button class="view-image-static-btn view-dl-img-btn p-1 px-2" style="font-size: 11px;" data-url="${dlFrontUrl}"><i class=""></i> View Front</button>`;
                    if (dlBackUrl) licTypeButtons += `<button class="view-image-static-btn view-dl-img-btn p-1 px-2" style="font-size: 11px;" data-url="${dlBackUrl}"><i class=""></i> View Back</button>`;
                }
                licTypeButtons += `</div>`;
                let vd = {};
                try {
                    vd = JSON.parse(item.vehicle_details || '{}');
                } catch (e) {}
                let currentType = String(vd.choosed_vehicle || '').toUpperCase().trim();
                if (currentType === 'MOTORCYCLE' || currentType === 'TWO WHEELER') currentType = 'BIKE';
                let sCar = currentType === 'CAR' ? 'selected' : '';
                let sBike = currentType === 'BIKE' ? 'selected' : '';
                let vHtml = `
                    <select class="form-select-custom" id="veh_type_${item.user_id}" style="width: 140px;">
                        <option value="" disabled ${!currentType ? 'selected':''}>Select...</option>
                        <option value="CAR" ${sCar}>CAR</option>
                        <option value="BIKE" ${sBike}>BIKE</option>
                    </select>
                `;
                let vehImgHtml = '';
                if (item.front_view_image_url && item.front_view_image_url !== 'null') {
                    vehImgHtml = `<div class="mt-2"><button class="view-image-static-btn view-veh-img-btn" data-url="${item.front_view_image_url}"><i class=""></i> View Front</button></div>`;
                } else {
                    vehImgHtml = `<div class="mt-2"><span class="badge bg-light text-danger border mt-1" style="font-size: 11px;">NO IMAGE AVAILABLE</span></div>`;
                }
                reasonStr = vd.car_type_reason || '';
                rowContent = `
                    <td>
                        <span class="badge bg-light text-dark border p-2 mb-1" style="font-size: 12px; white-space: normal;">${licTypeStr}</span>
                        ${licTypeButtons}
                    </td>
                    <td>${vHtml}${vehImgHtml}</td>
                `;
                actionBtns = `
                    <button class="btn btn-sm btn-success px-3 br-8 me-1" onclick="processAction(${item.user_id}, 'verify_vehicle', 'approve')" title="Approve"><i class="fa fa-check"></i></button>
                    <button class="btn btn-sm btn-danger px-3 br-8" onclick="processAction(${item.user_id}, 'verify_vehicle', 'reject')" title="Reject"><i class="fa fa-times"></i></button>
                `;
            }
            if (statusStr === 'approved') {
                badgeHtml = `<span class="badge bg-success"><i class="fa fa-check"></i> Approved</span>`;
            } else if (statusStr === 'rejected') {
                let rText = reasonStr ? `<div class="text-danger fw-bold mt-1" style="font-size: 11px;">Reason: ${reasonStr}</div>` : '';
                badgeHtml = `<span class="badge bg-danger"><i class="fa fa-times"></i> Rejected</span>${rText}`;
            } else {
                badgeHtml = `<span class="badge bg-warning text-dark"><i class="fa fa-clock"></i> Pending</span>`;
            }
            tbodyHtml += `
            <tr>
                <td class="text-center">${index + 1}</td>
                <td><span style="white-space:nowrap">${dateFormatted}</span></td>
                <td>
                    <b>${item.name || 'N/A'}</b><br>
                    <span class="text-muted"><i class="fa fa-phone"></i> ${item.mobile || 'N/A'}</span>
                </td>
                <td>${stateHtml}</td>
                ${rowContent}
                <td>${badgeHtml}</td>
                <td class="text-center" style="white-space:nowrap;">${actionBtns}</td>
            </tr>`;
        });

        // ----------------------------------------------------
        // LOGIC FIX: CAPTURE AND PRESERVE PAGINATION HERE
        // ----------------------------------------------------
        let currentPage = 0;
        if ($.fn.DataTable.isDataTable('#kycDt')) {
            if (preservePage) {
                currentPage = $('#kycDt').DataTable().page();
            }
            $('#kycDt').DataTable().clear().destroy();
        }

        $('#kyc-tbody').html(tbodyHtml);
        dtTable = $('#kycDt').DataTable({
            "destroy": true,
            "pageLength": 10,
            "order": [],
            "dom": '<"d-flex justify-content-between align-items-center mb-3"lf>rt<"d-flex justify-content-between mt-3"ip>',
            "language": {
                "search": "",
                "searchPlaceholder": "Search Table Data...",
                "emptyTable": "No data available in table"
            }
        });

        if (preservePage) {
            dtTable.page(currentPage).draw(false);
        }
    }
    // FUNCTION TO OPEN EDIT DL MODAL
    function openEditDlModal(userId, dlNo, frontUrl, backUrl) {
        $('#editDlUserId').val(userId);
        $('#editDlNumber').val(dlNo);
        let imgHtml = '';
        if (frontUrl && frontUrl !== 'null') {
            imgHtml += `<div><span class="d-block mb-1 fw-bold text-dark">Front View:</span><img src="${frontUrl}" class="img-fluid border shadow-sm" style="max-height: 200px; object-fit: contain;"></div>`;
        }
        if (backUrl && backUrl !== 'null') {
            imgHtml += `<div class="mt-3"><span class="d-block mb-1 fw-bold text-dark">Back View:</span><img src="${backUrl}" class="img-fluid border shadow-sm" style="max-height: 200px; object-fit: contain;"></div>`;
        }
        if (!imgHtml) {
            imgHtml = '<div class="alert alert-warning">No DL Images Available.</div>';
        }
        $('#modalDlImages').html(imgHtml);
        // Reset and Mount Viewer Directly to the Modal Container images
        if (editModalViewer) {
            editModalViewer.destroy();
        }
        editModalViewer = new Viewer(document.getElementById('modalDlImages'), {
            button: true,
            navbar: false,
            title: false,
            toolbar: {
                zoomIn: 1,
                zoomOut: 1,
                oneToOne: 1,
                reset: 1,
                rotateLeft: 1,
                rotateRight: 1
            }
        });
        let editModal = new bootstrap.Modal(document.getElementById('editDlModal'));
        editModal.show();
    }
    // FUNCTION TO SAVE UPDATED DL NUMBER VIA AJAX
    function saveDlDetails() {
        let userId = $('#editDlUserId').val();
        let newDlNo = $('#editDlNumber').val().trim();
        if (!newDlNo) {
            toast('error', 'DL Number cannot be empty');
            return;
        }
        let saveBtn = $('#saveDlBtn');
        let origText = saveBtn.html();
        saveBtn.html('<i class="fa fa-spinner fa-spin me-1"></i> Saving...').prop('disabled', true);
        $.ajax({
            url: API_URL,
            type: 'POST',
            dataType: 'json',
            data: {
                method: 'update_dl_number',
                user_id: userId,
                dl_no: newDlNo
            },
            success: function(res) {
                saveBtn.html(origText).prop('disabled', false);
                if (res && res.type == 1) {
                    toast('success', 'DL Number updated successfully');
                    // Hide Modal properly
                    let modalElement = document.getElementById('editDlModal');
                    let modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                    // Deep update the local data array so changes reflect precisely in the DL Details column without reload
                    let userIndex = currentData.findIndex(u => u.user_id == userId);
                    if (userIndex > -1) {
                        currentData[userIndex].dl_no = newDlNo;
                        // If the UI is rendering from dl_response JSON, we need to update that too
                        if (currentData[userIndex].dl_response && currentData[userIndex].dl_response !== 'null') {
                            try {
                                let parsed = JSON.parse(currentData[userIndex].dl_response);
                                if (parsed.id_no || !parsed.code) {
                                    parsed.id_no = newDlNo;
                                    currentData[userIndex].dl_response = JSON.stringify(parsed);
                                }
                            } catch (e) {}
                        }
                    }
                    // Instantly re-render the datatable UI locally to reflect the update and preserve pagination
                    renderTable(true);
                } else {
                    toast('error', res.result || 'Update failed');
                }
            },
            error: function() {
                saveBtn.html(origText).prop('disabled', false);
                toast('error', 'Network error while saving changes');
            }
        });
    }
    function processAction(userId, apiMethod, actionType) {
        let payload = {
            method: apiMethod,
            id: userId,
            action: actionType,
            reason: ''
        };
        if (apiMethod === 'verify_vehicle' && actionType === 'approve') {
            let vType = $(`#veh_type_${userId}`).val();
            if (!vType) {
                toast('error', 'Please select a Vehicle Type (CAR or BIKE) from the dropdown first.');
                return;
            }
            payload.vehicle_type = vType;
        }
        if (actionType === 'reject') {
            Swal.fire({
                title: 'Reason for rejection',
                input: 'text',
                inputPlaceholder: 'Enter reason for rejection...',
                showCancelButton: true,
                confirmButtonText: 'Reject',
                confirmButtonColor: '#dc3545',
                preConfirm: (reason) => {
                    if (!reason || reason.trim() === '') {
                        Swal.showValidationMessage('Reason required');
                        return false;
                    }
                    return reason.trim();
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    payload.reason = result.value;
                    executeApiCall(payload, userId);
                }
            });
        } else {
            executeApiCall(payload, userId);
        }
    }
    function executeApiCall(payload, userId) {
        let userIndex = currentData.findIndex(u => u.user_id == userId);
        let oldRecordStr = userIndex > -1 ? JSON.stringify(currentData[userIndex]) : null;
        if (userIndex > -1) {
            let sVal = payload.action === 'approve' ? '1' : '0';
            let rVal = payload.reason || '';
            if (payload.method === 'verify_selfie') {
                currentData[userIndex].selfie_status = sVal;
                currentData[userIndex].selfie_reason = rVal;
            } else {
                let vd = {};
                try {
                    vd = JSON.parse(currentData[userIndex].vehicle_details || '{}');
                } catch (e) {}
                if (payload.method === 'verify_dl') {
                    vd.dl_status = sVal === '1' ? 'approved' : 'rejected';
                    vd.dl_reason = rVal;
                } else if (payload.method === 'verify_vehicle') {
                    vd.car_type_status = sVal === '1' ? 'approved' : 'rejected';
                    vd.car_type_reason = rVal;
                    if (sVal === '1') vd.choosed_vehicle = payload.vehicle_type;
                }
                currentData[userIndex].vehicle_details = JSON.stringify(vd);
            }
            // Pass true to keep the user on the current page during the optimistic UI redraw
            renderTable(true);
        }
        $.ajax({
            url: API_URL,
            type: 'POST',
            dataType: 'json',
            data: payload,
            success: function(res) {
                if (res && res.type == 1) {
                    toast('success', res.result);
                } else {
                    toast('error', res.result || 'Update Failed');
                    if (oldRecordStr && userIndex > -1) {
                        currentData[userIndex] = JSON.parse(oldRecordStr);
                        renderTable(true);
                    }
                }
            },
            error: function() {
                toast('error', 'Network Error - Changes reverted');
                if (oldRecordStr && userIndex > -1) {
                    currentData[userIndex] = JSON.parse(oldRecordStr);
                    renderTable(true);
                }
            }
        });
    }
    function toast(icon, message) {
        Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2500,
            customClass: {
                container: 'kyc-toast-container'
            }
        }).fire({
            icon: icon,
            title: message
        });
    }
</script>