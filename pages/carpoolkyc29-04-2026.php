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
        .viewer-container {
            z-index: 9999999 !important;
        }
        .br-12 { border-radius: 12px !important; }
        .br-8 { border-radius: 8px !important; }
        .br-circle { border-radius: 50% !important; }

        .filter-card { background: #fff; border: 1px solid #e2e8f0; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .user-card { background: #fff; border: 1px solid #e2e8f0; padding: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); transition: all 0.3s ease; }
        .user-card:hover { transform: translateY(-3px); border-color: #cbd5e1; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        
        .badge-status { font-size: 10px; font-weight: 700; padding: 4px 10px; text-transform: uppercase; display: inline-flex; align-items: center; gap: 5px; }
        .badge-pending { color: #d97706; background: #fef3c7; border: 1px solid #fde68a; }
        .badge-verified { color: #059669; background: #d1fae5; border: 1px solid #a7f3d0; }

        .doc-preview { width: 100%; object-fit: cover; cursor: pointer; border: 1px solid #e2e8f0; padding: 4px; background: #fff; border-radius: 8px; transition: 0.2s;}
        .doc-preview:hover { border-color: #3b82f6; opacity: 0.9; transform: scale(1.02); }
        
        .btn-dark-solid { background-color: #1e293b; color: #fff; border: none; font-weight: 600; font-size: 13px; text-transform: uppercase; padding: 10px 16px; transition: 0.2s;}
        .btn-dark-solid:hover { background-color: #0f172a; color: #fff; }
        .btn-outline-blue { background-color: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-weight: 600; font-size: 13px; text-transform: uppercase; padding: 10px 16px; transition: 0.2s;}
        .btn-outline-blue:hover { background-color: #dbeafe; color: #1d4ed8; }

        .modal-content-custom { border: none; background: #f8fafc; }
        .modal-header-nav { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 15px; }
        .modal-main-tab { padding: 12px 28px; font-weight: 700; font-size: 13px; text-transform: uppercase; cursor: pointer; border: none; background: #e2e8f0; color: #64748b; transition: 0.3s;}
        .modal-main-tab.active { background: #3b82f6; color: #fff; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3); }
        .modal-close-btn { background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; font-weight: 600; padding: 10px 20px; font-size: 13px; text-transform: uppercase; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: 0.2s;}
        .modal-close-btn:hover { background: #e2e8f0; color: #0f172a; }

        .modal-header-strip { background-color: #ffffff; color: #334155; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #e2e8f0; }
        .modal-header-strip span.val { color: #0f172a; font-weight: 700; margin-left: 6px;}
        .modal-header-strip .lbl { color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;}

        .gallery-nav-btn { background: rgba(15, 23, 42, 0.7); color: white; border: none; padding: 15px 20px; font-size: 20px; cursor: pointer; transition: 0.2s;}
        .gallery-nav-btn:hover { background: rgba(15, 23, 42, 0.95); }
        
        .daterangepicker .ranges ul { width: 150px !important; }
        .doc-block { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }

        #actionApproveModal, #actionRejectModal {
            z-index: 999999 !important;
            background: rgba(0,0,0,0.6); 
        }
        .modal-backdrop { z-index: 1040 !important; }
        
        .dl-details-card { height: 150px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 15px; font-size: 13px; overflow-y: auto;}

        /* ======================================================= */
        /* AI CHATBOT STYLES (Updated for Compactness) */
        /* ======================================================= */
        .ai-chat-fab {
            position: fixed;
            bottom: 30px;
            margin-bottom: 30px; /* Pushes the button higher up */
            right: 30px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.4);
            cursor: pointer;
            z-index: 1050;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .ai-chat-fab:hover { transform: scale(1.1) rotate(5deg); }
        .ai-chat-window {
            position: fixed;
            bottom: 130px; /* Adjusts window position to match new FAB height */
            right: 30px;
            width: 350px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 12px 28px rgba(0,0,0,0.2);
            z-index: 1050;
            display: none;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .ai-chat-header {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            padding: 14px 20px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            letter-spacing: 0.5px;
        }
        .ai-chat-body {
            height: 250px; /* More compact chat body */
            overflow-y: auto;
            padding: 16px;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .ai-msg { max-width: 85%; padding: 10px 14px; border-radius: 12px; font-size: 13px; line-height: 1.5; font-weight: 500;}
        .msg-system { background: #e0e7ff; color: #1e3a8a; align-self: flex-start; border-bottom-left-radius: 2px;}
        .msg-user { background: #8b5cf6; color: white; align-self: flex-end; border-bottom-right-radius: 2px;}
        .ai-suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 12px;
            background: white;
            border-top: 1px solid #e2e8f0;
            max-height: 130px; /* More compact suggestions area */
            overflow-y: auto;
        }
        .ai-chip {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }
        .ai-chip:hover { background: #e2e8f0; color: #0f172a; border-color: #94a3b8; }
        .typing-indicator { display: none; align-self: flex-start; background: #e0e7ff; padding: 10px 16px; border-radius: 12px; border-bottom-left-radius: 2px; }
        .dot { width: 6px; height: 6px; background: #6366f1; border-radius: 50%; display: inline-block; margin: 0 2px; animation: bounce 1.4s infinite ease-in-out both; }
        .dot:nth-child(1) { animation-delay: -0.32s; }
        .dot:nth-child(2) { animation-delay: -0.16s; }
        @keyframes bounce { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }
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
                                <option value="kyc_pending">KYC PENDING</option>
                                <option value="kyc_verified">KYC VERIFIED</option>
                                <option value="vehicle_pending">VEHICLE PENDING</option>
                                <option value="vehicle_verified">VEHICLE VERIFIED</option>
                                <option value="both_verified">BOTH VERIFIED</option>
                                <option value="both_pending">BOTH NOT VERIFIED</option>
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

    <div class="modal fade" id="verifyModal" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content modal-content-custom br-12 shadow-lg">
                <div class="modal-body p-4" style="max-height: 90vh; overflow-y: auto;">
                    
                    <div class="modal-header-nav">
                        <div class="d-flex gap-3">
                            <button class="modal-main-tab br-12 active" id="tabBtnUser" onclick="switchMainTab('user')"><i class="fa fa-user me-2"></i> USER INFO</button>
                            <button class="modal-main-tab br-12" id="tabBtnVehicle" onclick="switchMainTab('vehicle')"><i class="fa fa-car me-2"></i> VEHICLE INFO</button>
                        </div>
                        <button type="button" class="modal-close-btn br-12" data-bs-dismiss="modal"><i class="fa fa-times"></i> CLOSE</button>
                    </div>

                    <div class="modal-header-strip br-12 mb-4 shadow-sm">
                        <div><span class="lbl">NAME:</span> <span class="val text-uppercase" id="modName"></span></div>
                        <div><span class="lbl">MOBILE:</span> <span class="val" id="modMobile"></span></div>
                        <div><span class="lbl">EMAIL:</span> <span class="val text-uppercase" id="modEmail"></span></div>
                    </div>

                    <div id="section-user">
                        <div class="row g-4">
                            <div class="col-md-5">
                                <div id="selfieBlockContainer"></div>
                            </div>
                            <div class="col-md-7">
                                <div id="aadharBlockContainer"></div>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-end bg-white p-3 br-12 border shadow-sm d-flex justify-content-end gap-3">
                            <button class="btn btn-success br-8 px-4 fw-bold" onclick="openApproveModal('user')"><i class="fa fa-check me-1"></i> APPROVE</button>
                            <button class="btn btn-danger br-8 px-4 fw-bold" onclick="openRejectModal('user')"><i class="fa fa-times me-1"></i> REJECT</button>
                        </div>
                    </div>

                    <div id="section-vehicle" style="display: none;">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div id="dlBlockContainer"></div>
                            </div>
                            <div class="col-md-6">
                                <div id="rcBlockContainer"></div>
                            </div>
                            <div class="col-md-12">
                                <div id="vehicleImagesBlockContainer"></div>
                            </div>
                        </div>

                        <div class="mt-4 text-end bg-white p-3 br-12 border shadow-sm d-flex justify-content-end gap-3">
                            <button class="btn btn-success br-8 px-4 fw-bold" onclick="openApproveModal('vehicle')"><i class="fa fa-check me-1"></i> APPROVE</button>
                            <button class="btn btn-danger br-8 px-4 fw-bold" onclick="openRejectModal('vehicle')"><i class="fa fa-times me-1"></i> REJECT</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true" style="z-index: 99999;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-header border-0 pb-0 justify-content-end">
                    <button type="button" class="btn btn-danger br-8 px-4 py-2 fw-bold" data-bs-dismiss="modal"><i class="fa fa-times me-1"></i> CLOSE</button>
                </div>
                <div class="modal-body text-center p-3 mt-2 position-relative d-flex justify-content-center align-items-center">
                    <button class="gallery-nav-btn br-circle me-3" id="btnPrevImg" onclick="prevImage()"><i class="fa fa-chevron-left"></i></button>
                    <img id="previewImage" src="" class="img-fluid border border-4 border-white br-12 shadow-lg" style="max-height:75vh; object-fit:contain;">
                    <button class="gallery-nav-btn br-circle ms-3" id="btnNextImg" onclick="nextImage()"><i class="fa fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="actionApproveModal" tabindex="-1" data-bs-backdrop="false">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content br-12 border-0 shadow-lg">
                <div class="modal-header bg-success text-white br-12 d-flex justify-content-between align-items-center" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
                    <h6 class="modal-title fw-bold m-0"><i class="fa fa-check-circle me-2"></i>Approve Documents</h6>
                    <button type="button" class="btn btn-sm text-white border-0" data-bs-dismiss="modal" style="background:transparent;"><i class="fa fa-times fa-lg"></i></button>
                </div>
                <div class="modal-body p-4" id="approveModalBody"></div>
                <div class="modal-footer bg-light d-flex justify-content-between">
                    <div class="form-check me-auto">
                        <input class="form-check-input mt-1" type="checkbox" id="sendWaApprove" checked>
                        <label class="form-check-label text-success fw-bold small" for="sendWaApprove"><i class="fab fa-whatsapp"></i> Send WA</label>
                    </div>
                    <div>
                        <button type="button" class="btn btn-light br-8 px-3 fw-bold border" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success br-8 px-4 fw-bold ms-1" onclick="confirmApprove()"><i class="fa fa-check me-1"></i> Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="actionRejectModal" tabindex="-1" data-bs-backdrop="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content br-12 border-0 shadow-lg">
                <div class="modal-header bg-danger text-white br-12 d-flex justify-content-between align-items-center" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
                    <h6 class="modal-title fw-bold m-0"><i class="fa fa-times-circle me-2"></i>Reject Documents</h6>
                    <button type="button" class="btn btn-sm text-white border-0" data-bs-dismiss="modal" style="background:transparent;"><i class="fa fa-times fa-lg"></i></button>
                </div>
                <div class="modal-body p-4" id="rejectModalBody"></div>
                <div class="modal-footer bg-light d-flex justify-content-between">
                    <div class="form-check me-auto">
                        <input class="form-check-input mt-1" type="checkbox" id="sendWaReject" checked>
                        <label class="form-check-label text-success fw-bold small" for="sendWaReject"><i class="fab fa-whatsapp"></i> Send WA</label>
                    </div>
                    <div>
                        <button type="button" class="btn btn-light br-8 px-3 fw-bold border" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger br-8 px-4 fw-bold ms-1" onclick="confirmReject()"><i class="fa fa-times me-1"></i> Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ai-chat-fab" onclick="toggleChat()">
        <i class="fa fa-robot"></i>
    </div>
    
    <div class="ai-chat-window" id="kycChatWindow">
        <div class="ai-chat-header">
            <div><i class="fa fa-robot me-2"></i>GO KYC Assistant</div>
            <button type="button" class="btn-close btn-close-white" style="font-size:12px;" onclick="toggleChat()"></button>
        </div>
        <div class="ai-chat-body" id="chatBody">
            <div class="ai-msg msg-system">Hi! I am your AI admin guide. Select a question below to learn how to manage KYC.</div>
            <div class="typing-indicator" id="typingIndicator">
                <span class="dot"></span><span class="dot"></span><span class="dot"></span>
            </div>
        </div>
        <div class="ai-suggestions" id="chatSuggestions"></div>
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
    let galleryImages = [];
    let currentGalleryIndex = 0;
    let currentActionTab = '';
    let currentViewer = null;
    // =======================================================
    // AI CHATBOT KNOWLEDGE BASE & LOGIC
    // =======================================================
    const kycKnowledgeBase = [
        { q: "How do I verify a user's KYC?", a: "Click 'USER INFO' on a customer's card. Review their Selfie and Aadhaar images. Click 'Approve' or 'Reject' at the bottom right, select the specific documents, add a reason if rejecting, and submit." },
        { q: "How do I verify vehicle details?", a: "Click 'VEHICLE INFO'. Review the Driving License, RC Document, and all Vehicle Photos. Click Approve or Reject, select the relevant items, enter any rejection reasons, and submit." },
        { q: "What does the 'DigiLocker Verified' badge mean?", a: "It means the customer fetched their Aadhaar directly from the government's DigiLocker API. It is already verified electronically, which is why no physical card photos are shown." },
        { q: "What happens when I reject a document?", a: "When rejecting, you MUST type a reason. The system will then automatically send a WhatsApp message to the user detailing exactly which document failed and your reason, so they can fix it." },
        { q: "Why is there no DL image sometimes?", a: "If the Driving License was successfully scanned by OCR, you will see a clean text card showing the Holder's Name, DL Number, Status, and Expiry Date instead of a raw photo." },
        { q: "What do the small icons under the name mean?", a: "They show the granular status of Aadhaar, Selfie, DL, and Vehicle. A green check is Approved, a red cross is Rejected/Failed, and a yellow clock means it is still Pending review." }
    ];

    function toggleChat() {
        $('#kycChatWindow').fadeToggle(200);
    }

    function loadChatSuggestions() {
        let html = '';
        kycKnowledgeBase.forEach((item, index) => {
            html += `<div class="ai-chip" onclick="askAi(${index})">${item.q}</div>`;
        });
        $('#chatSuggestions').html(html);
    }

    function askAi(index) {
        let item = kycKnowledgeBase[index];
        let $chatBody = $('#chatBody');
        let $typing = $('#typingIndicator');
        
        $typing.hide();
        $chatBody.append(`<div class="ai-msg msg-user">${item.q}</div>`);
        $chatBody.append($typing); 
        $typing.show();
        scrollToChatBottom();
        
        setTimeout(() => {
            $typing.hide();
            $chatBody.append(`<div class="ai-msg msg-system">${item.a}</div>`);
            $chatBody.append($typing);
            scrollToChatBottom();
        }, 800); 
    }

    function scrollToChatBottom() {
        let chatBody = document.getElementById('chatBody');
        chatBody.scrollTop = chatBody.scrollHeight;
    }


    // =======================================================
    // MAIN KYC DASHBOARD LOGIC
    // =======================================================
    $(document).ready(function() {
        
        // Initialize AI Chat suggestions immediately
        loadChatSuggestions();

        // 🚀 UPDATE: Set default value to Today
        let todayStr = moment().format('DD/MM/YYYY');
        $('#dateRange').val(todayStr + ' - ' + todayStr);

        $('#dateRange').daterangepicker({ 
            startDate: moment(), // 🚀 Set start date to today
            endDate: moment(),   // 🚀 Set end date to today
            autoUpdateInput: false, 
            locale: { format: 'DD/MM/YYYY', cancelLabel: 'Clear', customRangeLabel: 'Custom Date' },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
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
                let $context = $(this).closest('.modal-content').length ? $(this).closest('.modal-content') : $(this).closest('.user-card');
                
                // Clean up previous viewer if it exists
                if (currentViewer) { currentViewer.destroy(); }
                $('#temp-viewer-gallery').remove(); 
                
                // Create a temporary hidden gallery container
                let $galleryContainer = $('<div id="temp-viewer-gallery" style="display:none;"></div>');
                let clickedIndex = 0;
                let count = 0;
                
                $context.find('.doc-preview:visible').each(function() {
                    let imgUrl = $(this).attr('src');
                    if(imgUrl && !imgUrl.includes('placehold.co') && imgUrl !== 'null' && imgUrl.trim() !== '') { 
                        // Avoid duplicates in the gallery
                        if ($galleryContainer.find(`img[src="${imgUrl}"]`).length === 0) {
                            $galleryContainer.append(`<img src="${imgUrl}">`);
                            if (imgUrl === src) { clickedIndex = count; }
                            count++;
                        }
                    }
                });
                
                $('body').append($galleryContainer);
                
                // Initialize the advanced Viewer.js
                currentViewer = new Viewer(document.getElementById('temp-viewer-gallery'), {
                    initialViewIndex: clickedIndex,
                    button: true,
                    navbar: true,
                    title: false,
                    toolbar: {
                        zoomIn: 1, zoomOut: 1, oneToOne: 1, reset: 1, prev: 1, play: 0, next: 1, rotateLeft: 1, rotateRight: 1, flipHorizontal: 1, flipVertical: 1
                    },
                    hidden: function () {
                        // Destroy when closed to prevent memory leaks
                        currentViewer.destroy();
                        $('#temp-viewer-gallery').remove();
                        currentViewer = null;
                    }
                });
                
                currentViewer.show();
            }
        });
    });

    function updateGalleryModal() {
        $('#previewImage').attr('src', galleryImages[currentGalleryIndex]);
        if(galleryImages.length <= 1) { $('#btnPrevImg, #btnNextImg').hide(); } 
        else { $('#btnPrevImg, #btnNextImg').show(); }
    }
    
    function nextImage() { currentGalleryIndex = (currentGalleryIndex + 1) % galleryImages.length; updateGalleryModal(); }
    function prevImage() { currentGalleryIndex = (currentGalleryIndex - 1 + galleryImages.length) % galleryImages.length; updateGalleryModal(); }

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
                if(res.type == 1 && res.result.length > 0) { 
                    currentUsersData = res.result; 
                    renderCards(); 
                    
                    if (activeUserId && $('#verifyModal').is(':visible')) {
                        let activeTab = $('#tabBtnUser').hasClass('active') ? 'user' : 'vehicle';
                        openVerifyModal(activeUserId, activeTab);
                    }
                } 
                else { 
                    currentUsersData = []; 
                    $('#card-container').html('<div class="col-12 text-center text-muted py-5"><i class="fa fa-folder-open fa-3x mb-3 text-light"></i><br><span class="fw-bold text-uppercase">No records found.</span></div>'); 
                    if ($('#verifyModal').is(':visible')) { $('#verifyModal').modal('hide'); }
                }
            },
            error: function() { $('#card-container').html('<div class="col-12 text-center text-danger py-4 fw-bold">Network Error.</div>'); }
        });
    }

    function renderCards() {
        let html = '';

        const getIconStatus = (statusStr) => {
            if(statusStr === 'approved' || statusStr === '1' || statusStr === 1) return '<i class="fa fa-check-circle text-success bg-white br-circle position-absolute" style="top:-4px; right:-6px; font-size:13px; box-shadow: 0 0 2px rgba(0,0,0,0.3);"></i>';
            if(statusStr === 'rejected' || statusStr === 'failed' || statusStr === '0' || statusStr === 0) return '<i class="fa fa-times-circle text-danger bg-white br-circle position-absolute" style="top:-4px; right:-6px; font-size:13px; box-shadow: 0 0 2px rgba(0,0,0,0.3);"></i>';
            return '<i class="fa fa-clock text-warning bg-white br-circle position-absolute" style="top:-4px; right:-6px; font-size:13px; box-shadow: 0 0 2px rgba(0,0,0,0.3);"></i>';
        };

        currentUsersData.forEach(item => {
            let userBadge = item.doc_verify == 1 ? '<span class="badge-status badge-verified br-8"><i class="fa fa-user-check"></i> KYC VERIFIED</span>' : '<span class="badge-status badge-pending br-8"><i class="fa fa-user-clock"></i> KYC PENDING</span>';
            let vehicleBadge = item.vehicle_verify == 2 ? '<span class="badge-status badge-verified br-8"><i class="fa fa-car"></i> VEHICLE VERIFIED</span>' : '<span class="badge-status badge-pending br-8"><i class="fa fa-car"></i> VEHICLE PENDING</span>';
            let hasSelfie = (item.selfie_url && item.selfie_url.trim() !== '' && item.selfie_url !== 'null');
            
            // --- NEW DATE LOGIC: Find the most recent KYC/OCR submission date ---
            let validDates = [item.kyc_created_at, item.rc_created_at, item.dl_created_at].filter(d => d && d !== 'null' && d.trim() !== '');
            let displayDate = item.created_at; // Fallback to account creation date
            
            if (validDates.length > 0) {
                // Sort dates descending to get the newest submission
                validDates.sort((a, b) => new Date(b) - new Date(a));
                displayDate = validDates[0];
            }
            
            let dateFormatted = moment(displayDate).format('MMM DD, YYYY');
            // --------------------------------------------------------------------

            let profilePic = hasSelfie ? item.selfie_url : 'https://placehold.co/150?text=No+Pic';

            let vd = {};
            if(item.vehicle_details && item.vehicle_details.trim() !== '' && item.vehicle_details !== 'null') {
                try { vd = JSON.parse(item.vehicle_details); } catch(e) {}
            }

            let rawDlStat = item.dl_status || vd.dl_status || item.kyc_dl_status;

            let aadharStatIcon = getIconStatus(item.proof_status);
            let selfieStatIcon = getIconStatus(item.selfie_status);
            let dlStatIcon = getIconStatus(rawDlStat);
            
            let rcStatus = vd.rc_status || item.rc_status;
            let vehPhotosStatus = vd.veh_status; 
            let vehOverallStat = 'pending';
            if (rcStatus === 'rejected' || vehPhotosStatus === 'rejected' || rcStatus === 'failed' || vehPhotosStatus === 'failed') {
                vehOverallStat = 'rejected';
            } else if (rcStatus === 'approved' && vehPhotosStatus === 'approved') {
                vehOverallStat = 'approved';
            }
            let vehStatIcon = getIconStatus(vehOverallStat);

            let docIconsHtml = `
            <div class="d-flex align-items-center gap-4 mt-2 mb-3 pb-3 border-bottom">
                <div class="text-center position-relative">
                    <i class="fa fa-id-card" style="font-size: 22px; color: #3b82f6;"></i>
                    ${aadharStatIcon}
                    <div class="mt-1" style="font-size:10px; font-weight:600; color:#3b82f6;">Aadhar</div>
                </div>
                <div class="text-center position-relative">
                    <i class="fa fa-camera" style="font-size: 22px; color: #10b981;"></i>
                    ${selfieStatIcon}
                    <div class="mt-1" style="font-size:10px; font-weight:600; color:#10b981;">Selfie</div>
                </div>
                <div class="text-center position-relative">
                    <i class="fa fa-id-badge" style="font-size: 22px; color: #f59e0b;"></i>
                    ${dlStatIcon}
                    <div class="mt-1" style="font-size:10px; font-weight:600; color:#f59e0b;">DL</div>
                </div>
                <div class="text-center position-relative">
                    <i class="fa fa-car" style="font-size: 22px; color: #ef4444;"></i>
                    ${vehStatIcon}
                    <div class="mt-1" style="font-size:10px; font-weight:600; color:#ef4444;">Vehicle</div>
                </div>
            </div>`;

            html += `
            <div class="col-xl-4 col-lg-6 col-md-12 mb-4">
                <div class="user-card br-12 d-flex flex-column h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div><h5 class="fw-bold mb-1 text-dark text-uppercase">${item.name || 'Unnamed'}</h5></div>
                        <div class="d-flex flex-column align-items-end gap-1">${userBadge}${vehicleBadge}</div>
                    </div>
                    
                    ${docIconsHtml}

                    <div class="d-flex mb-4 align-items-center">
                        <img src="${profilePic}" class="br-circle me-3 border border-2 p-1 bg-light doc-preview" style="width: 65px; height: 65px; object-fit: cover;">
                        <div class="small">
                            <div class="mb-1 text-dark fw-bold"><i class="fa fa-phone text-muted me-2" style="width:14px;"></i> ${item.mobile || 'N/A'}</div>
                            <div class="text-muted fw-bold"><i class="fa fa-calendar-check me-2 text-primary" style="width:14px;"></i> SUBMITTED: <span class="text-dark">${dateFormatted}</span></div>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mt-auto">
                        <button class="btn-dark-solid br-8 flex-grow-1" onclick="openVerifyModal(${item.user_id}, 'user')"><i class="fa fa-user me-1"></i> USER INFO</button>
                        <button class="btn-outline-blue br-8 flex-grow-1" onclick="openVerifyModal(${item.user_id}, 'vehicle')"><i class="fa fa-car me-1"></i> VEHICLE INFO</button>
                    </div>
                </div>
            </div>`;
        });
        $('#card-container').html(html);
    }

    function createDocBlock(title, contentHtml, type, status, reason) {
        let badge = status == 1 ? '<span class="badge bg-success float-end"><i class="fa fa-check"></i> Approved</span>' : 
                   (status == 0 && reason ? '<span class="badge bg-danger float-end" title="'+reason+'"><i class="fa fa-times"></i> Rejected</span>' : 
                   (status == null ? '<span class="badge bg-info text-white float-end"><i class="fa fa-clock"></i> Pending</span>' : ''));
        return `
        <div class="doc-block position-relative">
            <h6 class="fw-bold text-muted text-uppercase mb-3"><i class="fa fa-file-image me-2"></i>${title} ${badge}</h6>
            <div class="mb-2 text-center">${contentHtml}</div>
            ${status == 0 && reason ? `<div class="alert alert-danger p-2 small m-0 mt-2"><i class="fa fa-info-circle me-1"></i> <strong>Reason:</strong> ${reason}</div>` : ''}
        </div>`;
    }

    function openVerifyModal(userId, defaultTab) {
        activeUserId = userId;
        let user = currentUsersData.find(u => u.user_id == userId);
        if(!user) return;

        $('#modName').text((user.name || 'N/A').toUpperCase());
        $('#modMobile').text(user.mobile || 'N/A');
        $('#modEmail').text((user.email || 'N/A').toUpperCase());

        let vd = {};
        let rcNo = user.rc_no || 'NOT GIVEN';
        let vReviewMsg = '';
        let vAdminVerify = false;
        
        if(user.vehicle_details && user.vehicle_details.trim() !== '' && user.vehicle_details !== 'null') {
            try {
                vd = JSON.parse(user.vehicle_details);
                if(vd.rc_number) rcNo = vd.rc_number;
                vReviewMsg = vd.vehicle_review_message || '';
                vAdminVerify = vd.admin_verify || false;
            } catch(e) {}
        }

        // --- USER INFO TAB ---
        let hasSelfie = (user.selfie_url && user.selfie_url.trim() !== '' && user.selfie_url !== 'null');
        let pic = hasSelfie ? user.selfie_url : 'https://placehold.co/300x400?text=No+Selfie';
        let af = (user.aadhar_front && user.aadhar_front !== 'null') ? user.aadhar_front : 'https://placehold.co/400x250?text=No+Aadhar+Front';
        let ab = (user.aadhar_back && user.aadhar_back !== 'null') ? user.aadhar_back : 'https://placehold.co/400x250?text=No+Aadhar+Back';
        
        let af_html = `<img src="${af}" class="doc-preview w-100" style="height: 180px;">`;
        let ab_html = `<img src="${ab}" class="doc-preview w-100" style="height: 180px;">`;

        if (user.proof_type === 'AADHAR_DIGILOCKER') {
            let digiLockerPill = `
            <div class="d-flex align-items-center justify-content-center w-100 shadow-sm" style="background:#3b1c8f; border-radius: 50px; padding: 10px; height: 180px;">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center position-relative shadow" style="width: 85px; height: 85px; flex-shrink: 0;">
                    <i class="fa fa-file-invoice" style="font-size:38px; color:#3b1c8f;"></i>
                    <i class="fa fa-cloud text-info position-absolute" style="bottom: 22px; right: 22px; font-size:16px;"></i>
                    <i class="fa fa-check-circle text-success position-absolute" style="bottom:-4px; right:-4px; font-size:26px; background:white; border-radius:50%;"></i>
                </div>
                <div class="text-white fw-bold ms-3 text-start" style="font-size: 18px; line-height: 1.2; letter-spacing: 0.5px;">DIGILOCKER<br>VERIFIED</div>
            </div>`;
            af_html = digiLockerPill;
            ab_html = digiLockerPill;
        }

        let s_stat = (user.selfie_status === 'rejected' || user.selfie_status === 'failed') ? 0 : (user.selfie_status === 'approved' ? 1 : null);
        let a_stat = (user.proof_status === 'rejected' || user.proof_status === 'failed') ? 0 : (user.proof_status === 'approved' ? 1 : null);

        $('#selfieBlockContainer').html(
            createDocBlock('Selfie', `<img src="${pic}" class="doc-preview mx-auto" style="height: 250px; width: auto;">`, 'selfie', s_stat, user.selfie_reason)
        );

        $('#aadharBlockContainer').html(
            createDocBlock('Aadhaar Document' + (user.proof_type === 'AADHAR_DIGILOCKER' ? ' <span class="badge bg-warning ms-2">DIGILOCKER</span>' : ''), `
                <div class="row g-2">
                    <div class="col-6">${af_html}<small class="d-block mt-1 text-muted fw-bold">FRONT</small></div>
                    <div class="col-6">${ab_html}<small class="d-block mt-1 text-muted fw-bold">BACK</small></div>
                </div>
            `, 'aadhar', a_stat, user.proof_reason)
        );

        // --- VEHICLE INFO TAB ---
        let dlNoTxt = user.dl_no ? `(NO: ${user.dl_no})` : '';
        let dlContentHtml = '';

        if (user.dl_response && user.dl_response.trim() !== '' && user.dl_response !== 'null') {
            try {
                let dlData = JSON.parse(user.dl_response);
                let name = dlData["Holder's Name"] || "N/A";
                let dlNoStr = dlData["id_no"] || "N/A";
                let exp = dlData["date_of_expiry"] || "N/A";
                let statusStr = dlData["Current Status"] || "N/A";
                let issued = dlData["issued_date"] || "N/A";
                let statusColor = statusStr.toUpperCase() === 'ACTIVE' ? 'text-success' : 'text-danger';

                // Extract Licence Type dynamically from the JSON
                let licTypeArr = [];
                if (dlData["NT"]) licTypeArr.push(dlData["NT"]);
                if (dlData["TR"]) licTypeArr.push(dlData["TR"]);
                if (dlData["COV"]) licTypeArr.push(dlData["COV"]);
                if (dlData["Class Of Vehicle"]) licTypeArr.push(dlData["Class Of Vehicle"]);
                let finalLicType = licTypeArr.length > 0 ? licTypeArr.join(", ") : "N/A";

                dlContentHtml = `
                    <div class="dl-details-card text-start shadow-sm border-0">
                        <div class="row">
                            <div class="col-6 mb-2 text-truncate">
                                <span class="text-muted fw-bold" style="font-size:10px;">HOLDER NAME:</span><br>
                                <span class="fw-bold text-dark" title="${name}">${name}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <span class="text-muted fw-bold" style="font-size:10px;">LICENCE TYPE:</span><br>
                                <span class="fw-bold text-dark">${finalLicType}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <span class="text-muted fw-bold" style="font-size:10px;">DL NO:</span><br>
                                <span class="fw-bold text-dark">${dlNoStr}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <span class="text-muted fw-bold" style="font-size:10px;">STATUS:</span><br>
                                <span class="fw-bold ${statusColor}">${statusStr}</span>
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
            } catch(e) {
                dlContentHtml = `<div class="dl-details-card d-flex align-items-center justify-content-center text-warning fw-bold"><i class="fa fa-exclamation-triangle me-2"></i> Error parsing DL Data</div>`;
            }
        } else {
            let dlf = (user.dl_front && user.dl_front !== 'null') ? user.dl_front : 'https://placehold.co/400x250?text=No+DL+Front';
            let dlb = (user.dl_back && user.dl_back !== 'null') ? user.dl_back : 'https://placehold.co/400x250?text=No+DL+Back';
            dlContentHtml = `
                <div class="row g-2">
                    <div class="col-6"><img src="${dlf}" class="doc-preview w-100" style="height: 150px;"><small class="d-block mt-1 text-muted fw-bold">FRONT</small></div>
                    <div class="col-6"><img src="${dlb}" class="doc-preview w-100" style="height: 150px;"><small class="d-block mt-1 text-muted fw-bold">BACK</small></div>
                </div>`;
        }

        let rcF = (user.rc_front && user.rc_front.trim() !== '' && user.rc_front !== 'null') ? user.rc_front : 'https://placehold.co/400x250?text=No+RC+Front';
        let rcB = (user.rc_back && user.rc_back.trim() !== '' && user.rc_back !== 'null') ? user.rc_back : 'https://placehold.co/400x250?text=No+RC+Back';
        if(vd.rc_front_image_url && vd.rc_front_image_url.trim() !== '' && vd.rc_front_image_url !== 'null') rcF = vd.rc_front_image_url;
        if(vd.rc_back_image_url && vd.rc_back_image_url.trim() !== '' && vd.rc_back_image_url !== 'null') rcB = vd.rc_back_image_url;

        let rawDlStat = user.dl_status || vd.dl_status || user.kyc_dl_stat;
        let dl_stat = null;
        if (rawDlStat === 'approved' || rawDlStat === '1') {
            dl_stat = 1;
        } else if (rawDlStat === 'rejected' || rawDlStat === 'failed' || rawDlStat === '0' || vd.dl_reason) {
            dl_stat = 0;
        }

        let rc_stat = (user.rc_status === 'rejected' || vd.rc_reason) ? 0 : (user.rc_status === 'approved' ? 1 : null);
        
        let veh_stat = 1; 
        if(vAdminVerify === false && vReviewMsg.toLowerCase().includes('reject')) { veh_stat = 0; }
        else if (vAdminVerify === false && !vd.veh_reason) { veh_stat = null; }

        $('#dlBlockContainer').html(createDocBlock(`Driving License ${dlNoTxt}`, dlContentHtml, 'dl', dl_stat, vd.dl_reason));

        $('#rcBlockContainer').html(
            createDocBlock(`RC Document (NO: ${rcNo})`, `
                <div class="row g-2">
                    <div class="col-6"><img src="${rcF}" class="doc-preview w-100" style="height: 150px;"><small class="d-block mt-1 text-muted fw-bold">FRONT</small></div>
                    <div class="col-6"><img src="${rcB}" class="doc-preview w-100" style="height: 150px;"><small class="d-block mt-1 text-muted fw-bold">BACK</small></div>
                </div>
            `, 'rc', rc_stat, vd.rc_reason)
        );

        const imgMap = [
            {key: 'front_view_image_url', label: 'FRONT VIEW'}, {key: 'back_view_image_url', label: 'BACK VIEW'},
            {key: 'side_view_image_url', label: 'SIDE VIEW'}, {key: 'interior_front_image_url', label: 'INTERIOR FRONT'},
            {key: 'interior_rear_image_url', label: 'INTERIOR REAR'}, {key: 'boot_image_url', label: 'BOOT'},
            {key: 'car_top_view_image_url', label: 'TOP VIEW'}, {key: 'special_features_image_url', label: 'SPECIAL FEATURES'}
        ];

        let carPhotosHTML = '<div class="row g-3">';
        imgMap.forEach(img => {
            let formattedLabel = img.label.replace(/ /g, '+');
            let imgUrl = `https://placehold.co/400x250?text=No+${formattedLabel}`;
            if(vd.vehicle && vd.vehicle[img.key] && vd.vehicle[img.key].trim() !== '' && vd.vehicle[img.key] !== 'null') {
                imgUrl = vd.vehicle[img.key];
            }
            carPhotosHTML += `<div class="col-md-3 col-6"><img src="${imgUrl}" class="doc-preview w-100" style="height: 120px;"><strong class="mt-2 d-block small text-center text-muted">${img.label}</strong></div>`;
        });
        carPhotosHTML += '</div>';

        $('#vehicleImagesBlockContainer').html(
            createDocBlock('Vehicle Photos', carPhotosHTML, 'veh', veh_stat, vd.veh_reason)
        );

        switchMainTab(defaultTab);
        $('#verifyModal').modal('show');
    }

    function switchMainTab(tab) {
        if(tab === 'user') {
            $('#section-user').fadeIn(200); $('#section-vehicle').hide();
            $('#tabBtnUser').addClass('active'); $('#tabBtnVehicle').removeClass('active');
        } else {
            $('#section-user').hide(); $('#section-vehicle').fadeIn(200);
            $('#tabBtnVehicle').addClass('active'); $('#tabBtnUser').removeClass('active');
        }
    }

    function openApproveModal(tab) {
        currentActionTab = tab;
        let html = '';
        if (tab === 'user') {
            html += `<div class="form-check mb-3"><input class="form-check-input doc-check" type="checkbox" value="selfie" id="chk_selfie" checked><label class="form-check-label fw-bold cursor-pointer" for="chk_selfie">Selfie</label></div>`;
            html += `<div class="form-check mb-2"><input class="form-check-input doc-check" type="checkbox" value="aadhar" id="chk_aadhar" checked><label class="form-check-label fw-bold cursor-pointer" for="chk_aadhar">Aadhaar Document</label></div>`;
        } else {
            html += `<div class="form-check mb-3"><input class="form-check-input doc-check" type="checkbox" value="dl" id="chk_dl" checked><label class="form-check-label fw-bold cursor-pointer" for="chk_dl">Driving License</label></div>`;
            html += `<div class="form-check mb-3"><input class="form-check-input doc-check" type="checkbox" value="rc" id="chk_rc" checked><label class="form-check-label fw-bold cursor-pointer" for="chk_rc">RC Document</label></div>`;
            html += `<div class="form-check mb-2"><input class="form-check-input doc-check" type="checkbox" value="veh" id="chk_veh" checked><label class="form-check-label fw-bold cursor-pointer" for="chk_veh">Vehicle Photos</label></div>`;
        }
        $('#approveModalBody').html(html);
        $('#actionApproveModal').modal('show');
    }

    function openRejectModal(tab) {
        currentActionTab = tab;
        let html = '';
        if (tab === 'user') {
            html += createRejectCheckbox('selfie', 'Selfie');
            html += createRejectCheckbox('aadhar', 'Aadhaar Document');
        } else {
            html += createRejectCheckbox('dl', 'Driving License');
            html += createRejectCheckbox('rc', 'RC Document');
            html += createRejectCheckbox('veh', 'Vehicle Photos');
        }
        $('#rejectModalBody').html(html);
        $('#actionRejectModal').modal('show');
    }

    function createRejectCheckbox(val, label) {
        return `
        <div class="mb-3 border p-3 br-8 bg-light">
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
        if (docs.length === 0) { toast('error', 'Please select at least one document to approve.'); return; }

        let sendWa = $('#sendWaApprove').is(':checked') ? 'yes' : 'no';
        let method = currentActionTab === 'user' ? 'verify_customer_doc' : 'verify_customer_vehicle';
        processAction(method, 'approve', docs, sendWa, '#actionApproveModal');
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

        if (Object.keys(docs).length === 0) { toast('error', 'Please select at least one document to reject.'); return; }
        if (hasError) { toast('error', 'Please provide a reason for all selected documents.'); return; }

        let sendWa = $('#sendWaReject').is(':checked') ? 'yes' : 'no';
        let method = currentActionTab === 'user' ? 'verify_customer_doc' : 'verify_customer_vehicle';
        processAction(method, 'reject', docs, sendWa, '#actionRejectModal');
    }

    function processAction(method, action, docs, sendWa, modalId) {
        let btnStr = action === 'approve' ? '<i class="fa fa-spinner fa-spin"></i> Approving...' : '<i class="fa fa-spinner fa-spin"></i> Rejecting...';
        $(modalId + ' .btn').prop('disabled', true);
        $(modalId + ' .btn-' + (action==='approve'?'success':'danger')).html(btnStr);

        $.ajax({
            url: API_URL, type: 'POST', dataType: 'json',
            data: { method: method, action: action, id: activeUserId, docs: docs, send_wa: sendWa },
            success: function(res) {
                if (res.type == 1) {
                    toast('success', res.result);
                    $(modalId).modal('hide');
                    fetchCustomerData(); 
                } else { toast('error', res.result); }
            },
            complete: function() {
                $(modalId + ' .btn').prop('disabled', false);
                let origStr = action === 'approve' ? '<i class="fa fa-check me-1"></i> Submit' : '<i class="fa fa-times me-1"></i> Submit';
                $(modalId + ' .btn-' + (action==='approve'?'success':'danger')).html(origStr);
            }
        });
    }

    function toast(icon, message) {
        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true }).fire({ icon: icon, title: message });
    }
</script>