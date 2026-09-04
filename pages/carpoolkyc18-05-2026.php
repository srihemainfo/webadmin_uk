<?php
$pageTitle = "Customer KYC Dashboard";
?>

<!-- DataTables CSS -->
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
        .viewer-container { z-index: 9999999 !important; }
        .br-12 { border-radius: 12px !important; }
        .br-8 { border-radius: 8px !important; }
        
        .daterangepicker .ranges ul { min-width: 160px !important; }
        .kyc-toast-container { z-index: 999999999 !important; }
        
        /* Inline Custom Scrollbar for Row Forms */
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
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }

        /* Modern Dark Styled Dropdown for Vehicles */
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
        
        /* Updated Minimal Tabs */
        .nav-tabs { 
            display: flex; gap: 12px; margin-bottom: 20px; padding: 0; background: transparent; border: none; 
        }
        .nav-tabs .nav-link { 
            color: #475569; font-weight: 700; border: 1px solid #cbd5e1; padding: 10px 24px; 
            text-transform: capitalize; letter-spacing: 0.5px; border-radius: 8px; margin: 0; background: #fff; transition: 0.2s;
        }
        .nav-tabs .nav-link.active { 
            color: #ffffff; border-color: #64748b; background: #64748b; 
        }

        /* Sub Tabs */
        .sub-tabs { gap: 10px; }
        .sub-tabs .nav-link { 
            color: #64748b; background: #e2e8f0; font-weight: 700; border-radius: 20px; 
            padding: 6px 20px; transition: all 0.2s; border: none; 
        }
        .sub-tabs .nav-link.active { background: #64748b; color: #fff; }

        /* DataTable Customizations */
        .dataTables_wrapper .dataTables_filter input { border-radius: 4px; border: 1px solid #cbd5e1; padding: 6px 12px; }
        table.dataTable { border-collapse: collapse !important; width: 100% !important; border: 1px solid #e2e8f0; font-size: 14px; }
        table.dataTable>thead { background-color: #f8fafc; border-bottom: 2px solid #cbd5e1; }
        table.dataTable>thead>tr>th { color: #334155; font-size: 13px; font-weight: 700; text-transform: uppercase; padding: 12px 16px; border: 1px solid #e2e8f0; }
        table.dataTable>tbody>tr>td { padding: 12px 16px; border: 1px solid #e2e8f0; vertical-align: middle; color: #0f172a; }
        table.dataTable>tbody>tr:nth-of-type(odd) { background-color: #fcfcfc; }
        table.dataTable>tbody>tr:hover { background-color: #f1f5f9; }

        /* EXACT STATIC GREY BUTTON - NO HOVER */
        .view-image-static-btn {
            background-color: #8c98a4 !important; /* Solid grey */
            color: #ffffff !important;
            border: none !important;
            padding: 5px 12px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
            transition: none !important; /* Forces no hover effect */
            display: inline-flex;
            align-items: center;
            gap: 5px;
            outline: none;
        }
        .view-image-static-btn:hover,
        .view-image-static-btn:focus,
        .view-image-static-btn:active {
            background-color: #8c98a4 !important; /* No color change on hover/click */
            box-shadow: none !important;
        }
    </style>

    <div class="main-content app-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid mt-4 p-0">
                
                <!-- TABS NAVIGATION -->
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

                <!-- TABS CONTENT -->
                <div class="tab-content" id="kycTabsContent">
                    <div class="tab-pane fade show active" id="kyc-pane" role="tabpanel">
                        
                        <!-- Common Filters -->
                        <div class="d-flex flex-wrap gap-3 mb-3 align-items-center bg-white p-3 br-12 border shadow-sm">
                            <div style="min-width: 220px; flex-grow: 1;">
                                <label class="fw-bold small text-muted text-uppercase mb-1">Search User</label>
                                <input type="text" id="searchTxt" class="form-control br-8" placeholder="Name, Mobile, Email">
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

                        <!-- Sub Tabs -->
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

                        <!-- DataTable Layout -->
                        <div class="bg-white br-12 border shadow-sm p-3">
                            <table id="kycDt" class="table align-middle w-100">
                                <thead id="kyc-thead">
                                    <!-- Populated dynamically based on Main Tab -->
                                </thead>
                                <tbody id="kyc-tbody">
                                    <!-- Populated dynamically based on Main Tab -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const API_URL = origin + "/ajax/service/carpool_services.php"; 
    let currentData = [];
    let currentViewer = null;
    
    const globalLocCache = {}; 
    let dtTable = null; 
    let activeMainTab = 'selfie'; 
    let activeSubTab = 'pending';

    $(document).ready(function() {
        // ALWAYS DEFAULT TO "TODAY"
        let todayStr = moment().format('DD/MM/YYYY') + ' - ' + moment().format('DD/MM/YYYY');
        $('#dateRange').val(todayStr);

        let dateOptions = { 
            autoUpdateInput: false, 
            startDate: moment(),
            endDate: moment(),
            locale: { format: 'DD/MM/YYYY', cancelLabel: 'Clear', customRangeLabel: 'Custom Range' },
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

        // Main Tab Switching
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            let id = e.target.id;
            if(id === 'selfie-tab') activeMainTab = 'selfie';
            else if(id === 'dl-tab') activeMainTab = 'dl';
            else if(id === 'vehicle-tab') activeMainTab = 'vehicle';
            
            activeSubTab = 'pending';
            $('.sub-tabs .nav-link').removeClass('active');
            $('.sub-tabs .nav-link[data-status="pending"]').addClass('active');

            fetchData();
        });

        // Sub Tab Switching
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

        // ==========================================
        // FAST ON-DEMAND IMAGE VIEWER API 
        // ==========================================
        $(document).on('click', '.view-image-static-btn', function() {
            let btn = $(this);
            let userId = btn.data('id');
            
            // Show loading on the button
            btn.html('<i class="fa-spin"></i> Loading...').prop('disabled', true);
            
            $.ajax({
                url: API_URL, type: 'POST', dataType: 'json',
                data: { method: 'fetch_document_image', id: userId, tab: activeMainTab },
                success: function(res) {
                    if(res && res.status && res.url && !res.url.includes('placehold.co')) {
                        // 1. Create a virtual image to preload the file
                        let imgLoad = new Image();
                        
                        // 2. Wait for it to fully download
                        imgLoad.onload = function() {
                            // Reset the button only AFTER download completes
                            btn.html('<i class=""></i> View Selfie').prop('disabled', false);
                            
                            if (currentViewer) { currentViewer.destroy(); }
                            $('#temp-viewer-gallery').remove(); 
                            
                            // Append directly as an image tag, no container needed
                            let $img = $('<img id="temp-viewer-gallery" src="'+res.url+'" style="display:none;">');
                            $('body').append($img);
                            
                            currentViewer = new Viewer($img[0], {
                                button: true, navbar: false, title: false,
                                toolbar: { zoomIn: 1, zoomOut: 1, oneToOne: 1, reset: 1, rotateLeft: 1, rotateRight: 1 },
                                hidden: function () {
                                    // Clean up DOM when closed
                                    if(currentViewer) currentViewer.destroy();
                                    $('#temp-viewer-gallery').remove();
                                }
                            });
                            currentViewer.show();
                        };
                        
                        imgLoad.onerror = function() {
                            btn.html('<i class=""></i> View Selfie').prop('disabled', false);
                            toast('error', 'Failed to load image file.');
                        };
                        
                        // 3. Trigger the download
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

        // Load Initial Data (Today)
        fetchData();
    });

    function resetFilters() {
        $('#searchTxt').val('');
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
        if(activeMainTab === 'selfie') {
            raw = String(item.selfie_status || 'pending').toLowerCase();
        } 
        else if (activeMainTab === 'dl') {
            let vd = {}; try { vd = JSON.parse(item.vehicle_details||'{}'); } catch(e){}
            if (vd.dl_status) {
                raw = String(vd.dl_status).toLowerCase();
            } else {
                raw = String(item.dl_status || item.kyc_dl_status || 'pending').toLowerCase();
                if (item.dl_response) {
                    try { let r=JSON.parse(item.dl_response); if(r.code || !r.id_no) raw = 'rejected'; } catch(e){}
                }
            }
        } 
        else if (activeMainTab === 'vehicle') {
            let vd = {}; try { vd = JSON.parse(item.vehicle_details||'{}'); } catch(e){}
            raw = String(vd.car_type_status || 'pending').toLowerCase();
        }

        if(raw === 'approved' || raw === '1') return 'approved';
        if(raw === 'rejected' || raw === 'failed' || raw === '0') return 'rejected';
        return 'pending';
    }

    function updateCounts() {
        let p = 0, a = 0, r = 0;
        currentData.forEach(item => {
            let s = getRecordStatus(item);
            if(s === 'approved') a++;
            else if(s === 'rejected') r++;
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
        
        // DYNAMIC METHODS: Hit specific APIs to avoid dragging heavy JSON columns
        let targetMethod = 'fetch_selfie_data';
        if (activeMainTab === 'dl') targetMethod = 'fetch_dl_data';
        else if (activeMainTab === 'vehicle') targetMethod = 'fetch_vehicle_data';

        let reqData = {
            method: targetMethod,
            searchTxt: $('#searchTxt').val(),
            dateRange: $('#dateRange').val()
        };
        
        $.ajax({
            url: API_URL, type: 'POST', dataType: 'json',
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

    function renderTable() {
        updateCounts();

        let filteredData = currentData.filter(item => getRecordStatus(item) === activeSubTab);

        let theadHtml = `
            <tr>
                <th style="width: 50px;">S.NO</th>
                <th>DATE & TIME</th>
                <th>USER DETAILS</th>
                <th>LOCATION</th>
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
                <th class="text-center" style="width:120px;">ACTION</th>
            </tr>
        `;
        $('#kyc-thead').html(theadHtml);

        let tbodyHtml = '';
        filteredData.forEach((item, index) => {
            let validDates = [item.kyc_created_at, item.dl_created_at].filter(d => d && d !== 'null' && d.trim() !== '');
            let displayDate = validDates.length > 0 ? validDates.sort((a, b) => new Date(b) - new Date(a))[0] : item.created_at;
            let dateFormatted = moment(displayDate).format('MMM Do YYYY, hh:mm A'); 

            let locHtml = '';
            if (item.s_lat && item.s_lang && item.s_lat !== 'null' && item.s_lang !== 'null') {
                locHtml = `<span class="location-placeholder" data-lat="${item.s_lat}" data-lng="${item.s_lang}"><i class="fa fa-ellipsis-h fa-fade text-primary"></i></span>`;
            } else {
                locHtml = `<span>INDIA</span>`;
            }

            let statusStr = getRecordStatus(item);
            let badgeHtml = '';
            let reasonStr = '';
            let rowContent = '';
            let actionBtns = '';

            if (activeMainTab === 'selfie') {
                // RENDER FAST: Store user ID on button, hit API for Image URL only on click
                rowContent = `<td><button class="view-image-static-btn" data-id="${item.user_id}"><i class=""></i> View Selfie</button></td>`;
                
                reasonStr = item.selfie_reason || '';
                
                actionBtns = `
                    <button class="btn btn-sm btn-success px-3 br-8 me-1" onclick="processAction(${item.user_id}, 'verify_selfie', 'approve')" title="Approve"><i class="fa fa-check"></i></button>
                    <button class="btn btn-sm btn-danger px-3 br-8" onclick="processAction(${item.user_id}, 'verify_selfie', 'reject')" title="Reject"><i class="fa fa-times"></i></button>
                `;
            } 
            else if (activeMainTab === 'dl') {
                let dlHtml = `<div class="custom-scroll">`;
                if(item.dl_response && item.dl_response.trim() !== '' && item.dl_response !== 'null') {
                    try {
                        let parsed = JSON.parse(item.dl_response);
                        if(parsed.code || !parsed.id_no) {
                            dlHtml += `<span class="text-danger fw-bold">API Error:</span> ${parsed.message || parsed.details || 'Not Found'}<br><span class="text-dark"><b>Entered DL:</b></span> ${item.dl_no || 'N/A'}`;
                        } else {
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
                    } catch(e) { dlHtml += `<span class="text-dark"><b>Entered DL:</b></span> ${item.dl_no || 'N/A'}`; }
                } else {
                    dlHtml += `<span class="text-dark"><b>Entered DL:</b></span> ${item.dl_no || 'N/A'}`;
                }
                dlHtml += `</div>`;

                let vd = {}; try{ vd = JSON.parse(item.vehicle_details||'{}'); }catch(e){}
                reasonStr = vd.dl_reason || '';
                rowContent = `<td>${dlHtml}</td>`;

                actionBtns = `
                    <button class="btn btn-sm btn-success px-3 br-8 me-1" onclick="processAction(${item.user_id}, 'verify_dl', 'approve')" title="Approve"><i class="fa fa-check"></i></button>
                    <button class="btn btn-sm btn-danger px-3 br-8" onclick="processAction(${item.user_id}, 'verify_dl', 'reject')" title="Reject"><i class="fa fa-times"></i></button>
                `;
            } 
            else if (activeMainTab === 'vehicle') {
                let licTypeStr = 'N/A';
                if(item.dl_response && item.dl_response.trim() !== '' && item.dl_response !== 'null') {
                    try {
                        let parsed = JSON.parse(item.dl_response);
                        if(!parsed.code && parsed.id_no) {
                            let arr = [];
                            if (parsed["NT"]) arr.push(parsed["NT"]);
                            if (parsed["TR"]) arr.push(parsed["TR"]);
                            if (parsed["COV"]) arr.push(parsed["COV"]);
                            if (parsed["Class Of Vehicle"]) arr.push(parsed["Class Of Vehicle"]);
                            if(arr.length > 0) licTypeStr = arr.join(", ");
                        }
                    } catch(e) {}
                }

                let vd = {}; try{ vd = JSON.parse(item.vehicle_details||'{}'); }catch(e){}
                let currentType = String(vd.choosed_vehicle || '').toUpperCase().trim();
                if(currentType === 'MOTORCYCLE' || currentType === 'TWO WHEELER') currentType = 'BIKE';
                
                let sCar = currentType === 'CAR' ? 'selected' : '';
                let sBike = currentType === 'BIKE' ? 'selected' : '';
                
                let vHtml = `
                    <select class="form-select-custom" id="veh_type_${item.user_id}" style="width: 140px;">
                        <option value="" disabled ${!currentType ? 'selected':''}>Select...</option>
                        <option value="CAR" ${sCar}>CAR</option>
                        <option value="BIKE" ${sBike}>BIKE</option>
                    </select>
                `;
                
                reasonStr = vd.car_type_reason || '';
                rowContent = `
                    <td><span class="badge bg-light text-dark border p-2" style="font-size: 13px;">${licTypeStr}</span></td>
                    <td>${vHtml}</td>
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
                <td>${locHtml}</td>
                ${rowContent}
                <td>${badgeHtml}</td>
                <td class="text-center" style="white-space:nowrap;">${actionBtns}</td>
            </tr>`;
        });
        
        if ($.fn.DataTable.isDataTable('#kycDt')) {
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

        processLocations(); 
    }

    async function processLocations() {
        const elements = document.querySelectorAll('.location-placeholder:not(.processed)');
        for (let i = 0; i < elements.length; i++) {
            let el = elements[i];
            el.classList.add('processed');
            let lat = el.getAttribute('data-lat');
            let lng = el.getAttribute('data-lng');
            
            if(!lat || !lng || lat == 'null' || lng == 'null') {
                el.innerHTML = `<span>INDIA</span>`;
                continue;
            }

            let key = `${lat},${lng}`;

            if (globalLocCache[key]) {
                el.innerHTML = `<span title="${globalLocCache[key]}">${globalLocCache[key]}</span>`;
                continue;
            }

            try {
                await new Promise(r => setTimeout(r, 1000)); 
                
                let response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=en`, {
                    headers: {
                        'User-Agent': 'GoRideAdminPanel/1.0 (admin@goride.run)'
                    }
                });
                if (response.ok) {
                    let data = await response.json();
                    if (data && data.address) {
                        let addr = data.address;
                        let district = addr.state_district || addr.county || addr.city || addr.town || '';
                        district = district.replace(/ District/gi, ''); 
                        let state = addr.state || '';
                        let country = addr.country || 'India';
                        let parts = [district.trim(), state.trim(), country.trim()].filter(Boolean);
                        let locationStr = parts.join(', ').toUpperCase();
                        
                        globalLocCache[key] = locationStr;
                        el.innerHTML = `<span title="${locationStr}">${locationStr}</span>`;
                    } else {
                        globalLocCache[key] = "INDIA";
                        el.innerHTML = `<span>INDIA</span>`;
                    }
                } else {
                    el.innerHTML = `<span>INDIA</span>`;
                }
            } catch(e) {
                el.innerHTML = `<span>INDIA</span>`;
            }
        }
    }

    function processAction(userId, apiMethod, actionType) {
        let payload = { method: apiMethod, id: userId, action: actionType, reason: '' };

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

        if(userIndex > -1) {
            let sVal = payload.action === 'approve' ? '1' : '0';
            let rVal = payload.reason || '';

            if (payload.method === 'verify_selfie') {
                currentData[userIndex].selfie_status = sVal;
                currentData[userIndex].selfie_reason = rVal;
            } else {
                let vd = {}; 
                try { vd = JSON.parse(currentData[userIndex].vehicle_details||'{}'); } catch(e){}
                
                if (payload.method === 'verify_dl') {
                    vd.dl_status = sVal === '1' ? 'approved' : 'rejected';
                    vd.dl_reason = rVal;
                } else if (payload.method === 'verify_vehicle') {
                    vd.car_type_status = sVal === '1' ? 'approved' : 'rejected';
                    vd.car_type_reason = rVal;
                    if(sVal === '1') vd.choosed_vehicle = payload.vehicle_type;
                }
                currentData[userIndex].vehicle_details = JSON.stringify(vd);
            }
            
            renderTable(); 
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
                        renderTable();
                    }
                }
            },
            error: function() {
                toast('error', 'Network Error - Changes reverted');
                if (oldRecordStr && userIndex > -1) {
                    currentData[userIndex] = JSON.parse(oldRecordStr);
                    renderTable();
                }
            }
        });
    }

    function toast(icon, message) {
        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, customClass: { container: 'kyc-toast-container' } }).fire({ icon: icon, title: message });
    }
</script>