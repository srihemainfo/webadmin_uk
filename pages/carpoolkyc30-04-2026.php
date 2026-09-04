<?php
$pageTitle = "Customer KYC Dashboard";
?>

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
        .br-circle { border-radius: 50% !important; }

        .filter-card { background: #fff; border: 1px solid #e2e8f0; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .user-card { background: #fff; border: 1px solid #e2e8f0; padding: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); transition: all 0.3s ease; }
        .user-card:hover { transform: translateY(-3px); border-color: #cbd5e1; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        
        .doc-preview { width: 100%; object-fit: contain; cursor: pointer; background: transparent; transition: 0.2s;}
        .doc-preview:hover { opacity: 0.8; transform: scale(1.02); }
        
        .btn-dark-theme { background-color: #1e293b; color: #fff; border: none; font-weight: 600; font-size: 13px; text-transform: uppercase; padding: 10px 16px; transition: 0.2s;}
        .btn-dark-theme:hover { background-color: #0f172a; color: #fff; }

        .modal-content-custom { border: none; background: #f8fafc; }
        .modal-header-strip { background-color: #ffffff; color: #334155; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #e2e8f0; }
        .modal-header-strip span.val { color: #0f172a; font-weight: 700; margin-left: 6px;}
        .modal-header-strip .lbl { color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;}
        
        .daterangepicker .ranges ul { min-width: 160px !important; }
        
        .doc-block { 
            background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.05); height: 100%; display: flex; flex-direction: column; 
        }

        #actionApproveModal, #actionRejectModal { background: rgba(0,0,0,0.6); }
        .kyc-toast-container { z-index: 999999999 !important; }
    </style>

    <div class="main-content app-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid mt-5 p-0">
                <div class="filter-card br-12 mb-4 mt-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Search Details</label>
                            <input type="text" id="searchTxt" class="form-control br-8" placeholder="Name, Mobile, Email">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Date Range</label>
                            <input type="text" id="dateRange" class="form-control br-8" placeholder="Select Dates">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Verification Status</label>
                            <select id="filterSelect" class="form-select br-8">
                                <option value="all">ALL RECORDS</option>
                                <option disabled>── SELFIE ──</option>
                                <option value="selfie_pending">Selfie Pending</option>
                                <option value="selfie_verified">Selfie Verified</option>
                                <option value="selfie_rejected">Selfie Rejected</option>
                                <option disabled>── DRIVING LICENSE ──</option>
                                <option value="dl_pending">DL Pending</option>
                                <option value="dl_verified">DL Verified</option>
                                <option value="dl_rejected">DL Rejected</option>
                                <option disabled>── VEHICLE TYPE ──</option>
                                <option value="veh_type_pending">Vehicle Type Pending</option>
                                <option value="veh_type_verified">Vehicle Type Verified</option>
                                <option value="veh_type_rejected">Vehicle Type Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button class="btn btn-light border br-8 w-100 fw-bold text-muted" onclick="resetFilters()">
                                <i class="fa fa-sync-alt me-1"></i> RESET FILTERS
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row" id="card-container"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="verifyModal" aria-hidden="true" data-bs-backdrop="static" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 95%;">
            <div class="modal-content modal-content-custom shadow-lg" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header bg-dark text-white d-flex justify-content-between align-items-center" style="border: none; padding: 18px 24px;">
                    <h5 class="m-0 fw-bold"><i class="fa fa-user-shield me-2"></i>KYC INFO</h5>
                    <button type="button" class="btn btn-light btn-sm fw-bold border-0 px-3 br-8" data-bs-dismiss="modal">
                        <i class="fa fa-times text-danger"></i> CLOSE
                    </button>
                </div>
                <div class="modal-body p-4 bg-light">
                    
                    <div class="modal-header-strip br-12 mb-3 shadow-sm">
                        <div><span class="lbl">NAME:</span> <span class="val text-uppercase" id="modName"></span></div>
                        <div><span class="lbl">MOBILE:</span> <span class="val" id="modMobile"></span></div>
                        <div><span class="lbl">EMAIL:</span> <span class="val text-uppercase" id="modEmail"></span></div>
                    </div>

                    <div class="row g-3 align-items-stretch">
                        <div class="col-lg-4"><div id="selfieBlockContainer" class="h-100"></div></div>
                        <div class="col-lg-4"><div id="dlBlockContainer" class="h-100"></div></div>
                        <div class="col-lg-4"><div id="vehicleTypeBlockContainer" class="h-100"></div></div>
                    </div>

                    <div class="mt-3 text-end bg-white p-3 br-12 border shadow-sm d-flex justify-content-end gap-3">
                        <button class="btn btn-success br-8 px-4 fw-bold" onclick="openApproveModal()"><i class="fa fa-check me-1"></i> APPROVE</button>
                        <button class="btn btn-danger br-8 px-4 fw-bold" onclick="openRejectModal()"><i class="fa fa-times me-1"></i> REJECT</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="actionApproveModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content br-12 border-0 shadow-lg">
                <div class="modal-header bg-success text-white br-12 d-flex justify-content-between align-items-center" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
                    <h6 class="modal-title fw-bold m-0"><i class="fa fa-check-circle me-2"></i>Approve</h6>
                    <button type="button" class="btn btn-sm text-white border-0" data-bs-dismiss="modal" style="background:transparent;"><i class="fa fa-times fa-lg"></i></button>
                </div>
                <div class="modal-body p-4" id="approveModalBody"></div>
                <div class="modal-footer bg-light d-flex align-items-center justify-content-between">
                    <div class="form-check m-0">
                        <input class="form-check-input mt-1" type="checkbox" id="sendWaApprove" checked>
                        <label class="form-check-label text-success fw-bold small" for="sendWaApprove"><i class="fab fa-whatsapp"></i> Send WA</label>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light br-8 px-3 fw-bold border" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success br-8 px-4 fw-bold" onclick="confirmApprove()"><i class="fa fa-check me-1"></i> Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="actionRejectModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content br-12 border-0 shadow-lg">
                <div class="modal-header bg-danger text-white br-12 d-flex justify-content-between align-items-center" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
                    <h6 class="modal-title fw-bold m-0"><i class="fa fa-times-circle me-2"></i>Reject</h6>
                    <button type="button" class="btn btn-sm text-white border-0" data-bs-dismiss="modal" style="background:transparent;"><i class="fa fa-times fa-lg"></i></button>
                </div>
                <div class="modal-body p-4" id="rejectModalBody"></div>
                <div class="modal-footer bg-light d-flex align-items-center justify-content-between">
                    <div class="form-check m-0">
                        <input class="form-check-input mt-1" type="checkbox" id="sendWaReject" checked>
                        <label class="form-check-label text-success fw-bold small" for="sendWaReject"><i class="fab fa-whatsapp"></i> Send WA</label>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light br-8 px-3 fw-bold border" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger br-8 px-4 fw-bold" onclick="confirmReject()"><i class="fa fa-times me-1"></i> Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const API_URL = origin + "/ajax/service/carpool_services.php"; 
    let currentUsersData = [];
    let activeUserId = null;
    let currentViewer = null;
    const globalLocCache = {}; // Global cache for locations

    $(document).ready(function() {
        let todayStr = moment().format('DD/MM/YYYY');
        $('#dateRange').val(todayStr + ' - ' + todayStr);

        $('#dateRange').daterangepicker({ 
            startDate: moment(),
            endDate: moment(),
            autoUpdateInput: false, 
            locale: { format: 'DD/MM/YYYY', cancelLabel: 'Clear', customRangeLabel: 'Custom Range' },
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

        $('#dateRange').on('apply.daterangepicker', function(ev, picker) { 
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY')); 
            fetchCustomerData();
        });

        $('#dateRange').on('cancel.daterangepicker', function(ev, picker) { 
            $(this).val(''); 
            fetchCustomerData();
        });

        $('#filterSelect').on('change', fetchCustomerData);

        let typingTimer;
        $('#searchTxt').on('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(fetchCustomerData, 500);
        });

        fetchCustomerData();

        $(document).on('click', '.doc-preview', function() {
            let src = $(this).attr('src');
            if(src && !src.includes('placehold.co')) {
                if (currentViewer) { currentViewer.destroy(); }
                $('#temp-viewer-gallery').remove(); 
                
                let $galleryContainer = $('<div id="temp-viewer-gallery" style="display:none;"><img src="'+src+'"></div>');
                $('body').append($galleryContainer);
                
                currentViewer = new Viewer(document.getElementById('temp-viewer-gallery'), {
                    button: true, navbar: false, title: false,
                    toolbar: { zoomIn: 1, zoomOut: 1, oneToOne: 1, reset: 1, rotateLeft: 1, rotateRight: 1, flipHorizontal: 1, flipVertical: 1 },
                    hidden: function () {
                        currentViewer.destroy();
                        $('#temp-viewer-gallery').remove();
                        currentViewer = null;
                    }
                });
                currentViewer.show();
            }
        });
    });

    function resetFilters() {
        $('#searchTxt').val(''); 
        $('#dateRange').val(''); 
        $('#filterSelect').val('all');
        fetchCustomerData();
    }

    function fetchCustomerData() {
        $('#card-container').html(`<div class="col-12 text-center py-5"><div class="spinner-border text-primary mb-3"></div><h5 class="fw-bold text-secondary text-uppercase">GO KYC..</h5></div>`);
        $.ajax({
            url: API_URL, type: 'POST', dataType: 'json',
            data: { method: 'fetch_customer_ocr', searchTxt: $('#searchTxt').val(), dateRange: $('#dateRange').val(), filter: $('#filterSelect').val() },
            success: function(res) {
                if(res && res.type == 1 && res.result && res.result.length > 0) { 
                    currentUsersData = res.result; 
                    renderCards(); 
                } else { 
                    currentUsersData = []; 
                    $('#card-container').html('<div class="col-12 text-center text-muted py-5"><i class="fa fa-folder-open fa-3x mb-3 text-light"></i><br><span class="fw-bold text-uppercase">No records found.</span></div>'); 
                }
            },
            error: function() { $('#card-container').html('<div class="col-12 text-center text-danger py-4 fw-bold">Network Error.</div>'); }
        });
    }
    
    function silentFetchCustomerData() {
        $.ajax({
            url: API_URL, type: 'POST', dataType: 'json',
            data: { method: 'fetch_customer_ocr', searchTxt: $('#searchTxt').val(), dateRange: $('#dateRange').val(), filter: $('#filterSelect').val() },
            success: function(res) {
                if(res && res.type == 1 && res.result && res.result.length > 0) { currentUsersData = res.result; } 
            }
        });
    }

    function renderCards() {
        let html = '';

        const getIconStatus = (statusStr) => {
            let s = String(statusStr).toLowerCase();
            if(s === 'approved' || s === '1') return '<i class="fa fa-check-circle text-success bg-white br-circle position-absolute" style="top: -5px; right: -8px; font-size:14px; box-shadow: 0 0 2px rgba(0,0,0,0.3);"></i>';
            if(s === 'rejected' || s === 'failed' || s === '0') return '<i class="fa fa-times-circle text-danger bg-white br-circle position-absolute" style="top: -5px; right: -8px; font-size:14px; box-shadow: 0 0 2px rgba(0,0,0,0.3);"></i>';
            return '<i class="fa fa-clock text-warning bg-white br-circle position-absolute" style="top: -5px; right: -8px; font-size:14px; box-shadow: 0 0 2px rgba(0,0,0,0.3);"></i>';
        };

        currentUsersData.forEach(item => {
            let hasSelfie = (item.selfie_url && item.selfie_url.trim() !== '' && item.selfie_url !== 'null');
            
            let validDates = [item.kyc_created_at, item.dl_created_at].filter(d => d && d !== 'null' && d.trim() !== '');
            let displayDate = item.created_at; 
            if (validDates.length > 0) {
                validDates.sort((a, b) => new Date(b) - new Date(a));
                displayDate = validDates[0];
            }
            let dateFormatted = moment(displayDate).format('MMM DD, YYYY');
            let profilePic = hasSelfie ? item.selfie_url : 'https://placehold.co/150?text=No+Pic';

            let vd = {};
            if(item.vehicle_details && item.vehicle_details.trim() !== '' && item.vehicle_details !== 'null') {
                try { vd = JSON.parse(item.vehicle_details); } catch(e) {}
            }

            let rawDlStat = item.dl_status || vd.dl_status || item.kyc_dl_status || 'pending';
            
            // --> OVERRIDE FOR API ERRORS ON CARD ICON <--
            if (item.dl_response && item.dl_response.trim() !== '' && item.dl_response !== 'null') {
                try {
                    let parsedRes = JSON.parse(item.dl_response);
                    if (parsedRes.code || !parsedRes.id_no) {
                        rawDlStat = 'pending'; // Force pending icon visually
                    }
                } catch (e) {}
            }

            let selfieStatIcon = getIconStatus(item.selfie_status);
            let dlStatIcon = getIconStatus(rawDlStat);
            let vehicleTypeStatIcon = getIconStatus(vd.car_type_status); 

            let vTypeStr = String(vd.choosed_vehicle || '').toUpperCase().trim();
            let mainIconClass = 'fa-car'; 
            if (vTypeStr === 'BIKE' || vTypeStr === 'MOTORCYCLE' || vTypeStr === 'TWO WHEELER') {
                mainIconClass = 'fa-motorcycle';
            }

            let docIconsHtml = `
            <div class="d-flex justify-content-around align-items-end mt-3 mb-4">
                <div class="text-center">
                    <div class="position-relative mx-auto" style="width: 35px;">
                        <i class="fa fa-camera" style="font-size: 26px; color: #10b981;"></i>
                        ${selfieStatIcon}
                    </div>
                    <div class="mt-1" style="font-size:11px; font-weight:600; color:#10b981;">Selfie</div>
                </div>
                <div class="text-center">
                    <div class="position-relative mx-auto" style="width: 35px;">
                        <i class="fa fa-id-badge" style="font-size: 26px; color: #f59e0b;"></i>
                        ${dlStatIcon}
                    </div>
                    <div class="mt-1" style="font-size:11px; font-weight:600; color:#f59e0b;">DL</div>
                </div>
                <div class="text-center">
                    <div class="position-relative mx-auto" style="width: 35px;">
                        <i class="fa ${mainIconClass}" style="font-size: 26px; color: #ef4444;"></i>
                        ${vehicleTypeStatIcon}
                    </div>
                    <div class="mt-1" style="font-size:11px; font-weight:600; color:#ef4444;">Vehicle Type</div>
                </div>
            </div>`;

            // Prepare location fetching HTML
            let locHtml = '';
            if (item.s_lat && item.s_lang && item.s_lat !== 'null' && item.s_lang !== 'null') {
                locHtml = `<span class="location-placeholder text-primary" data-lat="${item.s_lat}" data-lng="${item.s_lang}" data-uid="${item.user_id}"><i class="fa fa-ellipsis-h fa-fade me-1"></i></span>`;
            } else {
                locHtml = `INDIA`;
            }

            html += `
            <div class="col-xl-4 col-lg-6 col-md-12 mb-4">
                <div class="user-card br-12 d-flex flex-column h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2 border-bottom pb-3">
                        <h5 class="fw-bold mb-0 text-dark text-uppercase">${item.name || 'Unnamed'}</h5>
                    </div>
                    
                    ${docIconsHtml}

                    <div class="d-flex mb-4 align-items-center bg-light p-2 br-8">
                        <img src="${profilePic}" class="br-8 me-3 doc-preview shadow-sm" style="width: 60px; height: 60px; object-fit: cover; background: #000;">
                        <div class="small w-100">
                            <div class="mb-1 text-dark fw-bold d-flex align-items-center"><i class="fa fa-phone text-muted me-2" style="width:16px; text-align:center;"></i> ${item.mobile || 'N/A'}</div>
                            <div class="mb-1 text-muted fw-bold d-flex align-items-center"><i class="fa fa-calendar-check text-primary me-2" style="width:16px; text-align:center;"></i> <span class="text-dark">${dateFormatted}</span></div>
                            <div class="text-muted fw-bold d-flex align-items-start mt-1 w-100" style="font-size: 10px;">
                                <i class="fa fa-map-marker-alt text-danger me-2" style="width:16px; text-align:center; margin-top: 2px;"></i> 
                                <span style="white-space: normal; line-height: 1.4;" id="loc-${item.user_id}">FROM: ${locHtml}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-auto">
                        <button class="btn-dark-theme w-100 br-8" onclick="openVerifyModal(${item.user_id})"><i class="fa fa-file-signature me-1"></i> KYC INFO</button>
                    </div>
                </div>
            </div>`;
        });
        
        $('#card-container').html(html);
        
        // Trigger Async Location Fetching after cards render
        processLocations();
    }

    // Async Frontend Location Fetching
    async function processLocations() {
        const elements = document.querySelectorAll('.location-placeholder');
        
        for (let i = 0; i < elements.length; i++) {
            let el = elements[i];
            let lat = el.getAttribute('data-lat');
            let lng = el.getAttribute('data-lng');
            let uid = el.getAttribute('data-uid');
            let key = `${lat},${lng}`;
            let locContainer = document.getElementById(`loc-${uid}`);

            if (globalLocCache[key]) {
                if (locContainer) locContainer.innerHTML = `FROM: ${globalLocCache[key]}`;
                continue;
            }

            try {
                // Add a slight delay to respect OSM's strict 1 request/second limit to avoid getting blocked
                await new Promise(r => setTimeout(r, 250)); 
                
                let response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=en`);
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
                        if (locContainer) locContainer.innerHTML = `<span title="${locationStr}">FROM: ${locationStr}</span>`;
                    } else {
                        globalLocCache[key] = "INDIA";
                        if (locContainer) locContainer.innerHTML = `FROM: INDIA`;
                    }
                } else {
                    if (locContainer) locContainer.innerHTML = `FROM: INDIA`;
                }
            } catch(e) {
                // Fallback silently to INDIA on network error
                if (locContainer) locContainer.innerHTML = `FROM: INDIA`;
            }
        }
    }

    function createDocBlock(title, contentHtml, type, status, reason) {
        let badge = '';
        if (status === 1) badge = '<span class="badge bg-success float-end"><i class="fa fa-check"></i> Approved</span>';
        else if (status === 0) badge = `<span class="badge bg-danger float-end" title="${reason}"><i class="fa fa-times"></i> Rejected</span>`;
        else badge = '<span class="badge bg-info text-white float-end"><i class="fa fa-clock"></i> Pending</span>';
        
        return `
        <div class="doc-block position-relative">
            <h6 class="fw-bold text-muted text-uppercase mb-3 border-bottom pb-2"><i class="fa fa-check-square me-2"></i>${title} ${badge}</h6>
            <div class="mb-2 w-100 d-flex flex-column justify-content-center flex-grow-1">${contentHtml}</div>
            ${status === 0 && reason ? `<div class="alert alert-danger p-2 small m-0 mt-3 w-100"><i class="fa fa-info-circle me-1"></i> <strong>Reason:</strong> ${reason}</div>` : ''}
        </div>`;
    }

    function openVerifyModal(userId) {
        activeUserId = userId;
        let user = currentUsersData.find(u => u.user_id == userId);
        if(!user) return;

        $('#modName').text((user.name || 'N/A').toUpperCase());
        $('#modMobile').text(user.mobile || 'N/A');
        $('#modEmail').text((user.email || 'N/A').toUpperCase());

        let vd = {};
        if(user.vehicle_details && user.vehicle_details.trim() !== '' && user.vehicle_details !== 'null') {
            try { vd = JSON.parse(user.vehicle_details); } catch(e) {}
        }

        let hasSelfie = (user.selfie_url && user.selfie_url.trim() !== '' && user.selfie_url !== 'null');
        let pic = hasSelfie ? user.selfie_url : 'https://placehold.co/300x400?text=No+Selfie';
        
        let selfieRaw = String(user.selfie_status).toLowerCase();
        let s_stat = null;
        if(selfieRaw === 'approved' || selfieRaw === '1') s_stat = 1;
        else if(selfieRaw === 'rejected' || selfieRaw === 'failed' || selfieRaw === '0') s_stat = 0;

        $('#selfieBlockContainer').html(
            createDocBlock('Selfie', `<img src="${pic}" class="doc-preview mx-auto br-8" style="max-height: 220px; width: auto; max-width: 100%; object-fit: contain; background: transparent;">`, 'selfie', s_stat, user.selfie_reason)
        );

        let dlContentHtml = '';
        let forcePending = false;
        let enteredDlNo = user.dl_no || "N/A"; // Use the explicitly entered doc_no

        if (user.dl_response && user.dl_response.trim() !== '' && user.dl_response !== 'null') {
            try {
                let dlData = JSON.parse(user.dl_response);
                
                // --> NEW LOGIC: CHECK FOR API ERROR <--
                if (dlData.code || !dlData.id_no) {
                    forcePending = true;
                    let errMsg = dlData.message || dlData.details || "API Error / Entity Not Found";

                    dlContentHtml = `
                    <div class="p-3 bg-light br-8 w-100 h-100 border text-start">
                        <div class="row g-2">
                            <div class="col-6 text-truncate">
                                <span class="text-muted fw-bold" style="font-size:10px;">HOLDER NAME:</span><br>
                                <span class="fw-bold text-dark">N/A</span>
                            </div>
                            <div class="col-6">
                                <span class="text-muted fw-bold" style="font-size:10px;">DL NO:</span><br>
                                <span class="fw-bold text-dark">${enteredDlNo}</span>
                            </div>
                            <div class="col-6">
                                <span class="text-muted fw-bold" style="font-size:10px;">STATUS:</span><br>
                                <span class="fw-bold">API ERROR</span>
                            </div>
                            <div class="col-6">
                                <span class="text-muted fw-bold" style="font-size:10px;">LIC TYPE:</span><br>
                                <span class="fw-bold text-dark">N/A</span>
                            </div>
                            <div class="col-6">
                                <span class="text-muted fw-bold" style="font-size:10px;">ISSUED:</span><br>
                                <span class="fw-bold text-dark">N/A</span>
                            </div>
                            <div class="col-6">
                                <span class="text-muted fw-bold" style="font-size:10px;">EXPIRY:</span><br>
                                <span class="fw-bold text-dark">N/A</span>
                            </div>
                            
                            <div class="col-12 mt-2">
                                <div class="p-2 m-0 text-center fw-bold" style="font-size:11px;">
                                    <i class="fa fa-info-circle me-1"></i> ${errMsg}
                                </div>
                            </div>
                        </div>
                    </div>`;
                } else {
                    // --> SUCCESS PARSING (ORIGINAL LOGIC) <--
                    let name = dlData["Holder's Name"] || "N/A";
                    let dlNoStr = dlData["id_no"] || "N/A";
                    let exp = dlData["date_of_expiry"] || "N/A";
                    let statusStr = dlData["Current Status"] || "N/A";
                    let issued = dlData["issued_date"] || "N/A";
                    let statusColor = statusStr.toUpperCase() === 'ACTIVE' ? 'text-success' : 'text-danger';

                    let licTypeArr = [];
                    if (dlData["NT"]) licTypeArr.push(dlData["NT"]);
                    if (dlData["TR"]) licTypeArr.push(dlData["TR"]);
                    if (dlData["COV"]) licTypeArr.push(dlData["COV"]);
                    if (dlData["Class Of Vehicle"]) licTypeArr.push(dlData["Class Of Vehicle"]);
                    let finalLicType = licTypeArr.length > 0 ? licTypeArr.join(", ") : "N/A";

                    dlContentHtml = `
                        <div class="p-3 bg-light br-8 w-100 h-100 border text-start">
                            <div class="row g-2">
                                <div class="col-6 text-truncate">
                                    <span class="text-muted fw-bold" style="font-size:10px;">HOLDER NAME:</span><br>
                                    <span class="fw-bold text-dark" title="${name}">${name}</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted fw-bold" style="font-size:10px;">DL NO:</span><br>
                                    <span class="fw-bold text-dark">${dlNoStr}</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted fw-bold" style="font-size:10px;">STATUS:</span><br>
                                    <span class="fw-bold ${statusColor}">${statusStr}</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted fw-bold" style="font-size:10px;">LIC TYPE:</span><br>
                                    <span class="fw-bold text-dark">${finalLicType}</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted fw-bold" style="font-size:10px;">ISSUED:</span><br>
                                    <span class="fw-bold text-dark">${issued}</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted fw-bold" style="font-size:10px;">EXPIRY:</span><br>
                                    <span class="fw-bold text-dark">${exp}</span>
                                </div>
                            </div>
                        </div>`;
                }
            } catch(e) {
                dlContentHtml = `<div class="d-flex align-items-center justify-content-center text-warning fw-bold w-100 h-100 bg-light br-8 border"><i class="fa fa-exclamation-triangle me-2"></i> Error parsing Data</div>`;
            }
        } else {
            dlContentHtml = `
                <div class="d-flex flex-column justify-content-center align-items-center text-muted w-100 h-100 bg-light br-8 border py-4">
                    <i class="fa fa-id-badge mb-2" style="font-size:30px;"></i>
                    <div class="fw-bold text-dark">DL NO: ${enteredDlNo}</div>
                    <div class="small">(No text data parsed)</div>
                </div>`;
        }

        let rawDlStat = String(user.dl_status || vd.dl_status || user.kyc_dl_stat || 'pending').toLowerCase();
        let dl_stat = null;

        // --> OVERRIDE DL STATUS IF API ERROR <--
        if (forcePending) {
            dl_stat = null; // Forces 'Pending' visual status in the UI
        } else {
            if(rawDlStat === 'approved' || rawDlStat === '1') dl_stat = 1;
            else if(rawDlStat === 'rejected' || rawDlStat === 'failed' || rawDlStat === '0' || vd.dl_reason) dl_stat = 0;
        }
        
        $('#dlBlockContainer').html(createDocBlock(`Driving License`, dlContentHtml, 'dl', dl_stat, vd.dl_reason));

        let vehicleType = vd.choosed_vehicle || 'Not Selected';
        
        let vTypeNormalized = String(vehicleType).toUpperCase().trim();
        let iconClass = 'fa-car'; 
        if (vTypeNormalized === 'BIKE' || vTypeNormalized === 'MOTORCYCLE' || vTypeNormalized === 'TWO WHEELER') {
            iconClass = 'fa-motorcycle';
        }
        
        let typeRaw = String(vd.car_type_status || 'pending').toLowerCase();
        let vehicleTypeStat = null;
        if(typeRaw === 'approved' || typeRaw === '1') vehicleTypeStat = 1;
        else if(typeRaw === 'rejected' || typeRaw === 'failed' || typeRaw === '0') vehicleTypeStat = 0;
        
        let vTypeHtml = `
            <div class="d-flex flex-column justify-content-center align-items-center w-100 h-100 bg-light br-8 border py-4">
                <i class="fa ${iconClass} text-primary mb-2" style="font-size: 55px;"></i>
                <h3 class="mt-2 text-uppercase fw-bold text-dark m-0">${vehicleType}</h3>
            </div>
        `;
        $('#vehicleTypeBlockContainer').html(
            createDocBlock(`Vehicle Type`, vTypeHtml, 'vehicle_type', vehicleTypeStat, vd.car_type_reason)
        );

        $('#verifyModal').modal('show');
    }

    function openApproveModal() {
        let html = `
            <div class="form-check mb-3"><input class="form-check-input doc-check" type="checkbox" value="selfie" id="chk_selfie" checked><label class="form-check-label fw-bold cursor-pointer" for="chk_selfie">Selfie</label></div>
            <div class="form-check mb-3"><input class="form-check-input doc-check" type="checkbox" value="dl" id="chk_dl" checked><label class="form-check-label fw-bold cursor-pointer" for="chk_dl">Driving License</label></div>
            <div class="form-check mb-2"><input class="form-check-input doc-check" type="checkbox" value="car_type" id="chk_vehicle_type" checked><label class="form-check-label fw-bold cursor-pointer" for="chk_vehicle_type">Vehicle Type</label></div>
        `;
        $('#approveModalBody').html(html);
        $('#actionApproveModal').modal('show');
    }

    function openRejectModal() {
        let html = `
            ${createRejectCheckbox('selfie', 'Selfie')}
            ${createRejectCheckbox('dl', 'Driving License')}
            ${createRejectCheckbox('car_type', 'Vehicle Type')}
        `;
        $('#rejectModalBody').html(html);
        $('#actionRejectModal').modal('show');
    }

    function createRejectCheckbox(val, label) {
        return `
        <div class="mb-3 border p-3 br-8 bg-white">
            <div class="form-check mb-2">
                <input class="form-check-input doc-reject-check mt-1" type="checkbox" value="${val}" id="rej_${val}" onchange="toggleReason('${val}')">
                <label class="form-check-label fw-bold text-danger cursor-pointer" for="rej_${val}">${label}</label>
            </div>
            <input type="text" class="form-control d-none reason-input br-8 border-danger" id="reason_${val}" placeholder="Type reason for rejection...">
        </div>`;
    }

    function toggleReason(val) {
        if ($(`#rej_${val}`).is(':checked')) {
            $(`#reason_${val}`).removeClass('d-none').focus();
        } else {
            $(`#reason_${val}`).addClass('d-none').val('');
        }
    }

    function confirmApprove() {
        let docs = [];
        $('.doc-check:checked').each(function() { docs.push($(this).val()); });
        if (docs.length === 0) { toast('error', 'Please select at least one item to approve.'); return; }

        let sendWa = $('#sendWaApprove').is(':checked') ? 'yes' : 'no';
        processAction('verify_customer_kyc', 'approve', docs, sendWa, '#actionApproveModal');
    }

    function confirmReject() {
        let docs = {};
        let hasError = false;

        $('.doc-reject-check:checked').each(function() {
            let val = $(this).val();
            let reason = $(`#reason_${val}`).val().trim();
            if (!reason) { hasError = true; }
            docs[val] = reason;
        });

        if (Object.keys(docs).length === 0) { toast('error', 'Please select at least one item to reject.'); return; }
        if (hasError) { toast('error', 'Please provide a reason for all selected items.'); return; }

        let sendWa = $('#sendWaReject').is(':checked') ? 'yes' : 'no';
        processAction('verify_customer_kyc', 'reject', docs, sendWa, '#actionRejectModal');
    }

    function updateLocalUserStatus(action, docs) {
        let user = currentUsersData.find(u => u.user_id == activeUserId);
        if (!user) return;
        
        let statVal = action === 'approve' ? 'approved' : 'rejected';

        let vd = {};
        if(user.vehicle_details && user.vehicle_details !== 'null') {
            try { vd = JSON.parse(user.vehicle_details); } catch(e){}
        }

        let processedDocs = Array.isArray(docs) ? docs : Object.keys(docs);
        
        processedDocs.forEach(key => {
            let reason = Array.isArray(docs) ? null : docs[key];
            if (key === 'selfie') { 
                user.selfie_status = statVal; 
                user.selfie_reason = reason; 
            }
            if (key === 'dl') { 
                user.dl_status = statVal; 
                vd.dl_status = statVal; 
                vd.dl_reason = reason; 
            }
            if (key === 'vehicle_type' || key === 'car_type') { 
                vd.car_type_status = statVal; 
                vd.car_type_reason = reason; 
            }
        });

        if (action === 'approve') {
            if (user.selfie_status === 'approved') user.doc_verify = 1;
            if (vd.dl_status === 'approved' && vd.car_type_status === 'approved') user.vehicle_verify = 2;
        } else {
            if (processedDocs.includes('selfie')) user.doc_verify = 0;
            if (processedDocs.includes('dl') || processedDocs.includes('car_type')) user.vehicle_verify = 1;
        }

        user.vehicle_details = JSON.stringify(vd);
    }

    function processAction(method, action, docs, sendWa, modalId) {
        let btnStr = '<i class="fa fa-spinner fa-spin"></i> Processing...';
        $(modalId + ' .btn').prop('disabled', true);
        $(modalId + ' .btn-' + (action==='approve'?'success':'danger')).html(btnStr);

        $.ajax({
            url: API_URL, type: 'POST', dataType: 'json',
            data: { method: method, action: action, id: activeUserId, docs: docs, send_wa: sendWa },
            success: function(res) {
                if (res && res.type == 1) {
                    toast('success', res.result);
                    $(modalId).modal('hide'); 

                    updateLocalUserStatus(action, docs);
                    openVerifyModal(activeUserId); 
                    renderCards();
                    silentFetchCustomerData(); 

                } else { toast('error', res ? res.result : 'Server Error - No Valid Response'); }
            },
            error: function() {
                toast('error', 'Network error or Invalid server response.');
            },
            complete: function() {
                $(modalId + ' .btn').prop('disabled', false);
                let origStr = action === 'approve' ? '<i class="fa fa-check me-1"></i> Submit' : '<i class="fa fa-times me-1"></i> Submit';
                $(modalId + ' .btn-' + (action==='approve'?'success':'danger')).html(origStr);
            }
        });
    }

    function toast(icon, message) {
        Swal.mixin({ 
            toast: true, 
            position: 'top-end', 
            showConfirmButton: false, 
            timer: 3000, 
            timerProgressBar: true,
            customClass: { container: 'kyc-toast-container' } 
        }).fire({ icon: icon, title: message });
    }
</script>