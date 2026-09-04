<?php
// error_reporting(E_ALL); 
// ini_set('display_errors', 1);
?>

<style>
    /* Hide all cards beyond the 3rd one in each tab */
    #unassignedJobs .card:nth-child(n+4),
    #assignedJobs .card:nth-child(n+4),
    #websiteJobs .card:nth-child(n+4) {
        display: none;
    }

    /* When the parent is hovered or scrolled, show all cards */
    #unassigned:hover .card,
    #assigned:hover .card,
    #websiteBookings:hover .card,
    #unassigned:focus-within .card,
    #assigned:focus-within .card,
    #websiteBookings:focus-within .card {
        display: block !important;
    }

    /* Style the scroll containers */
    #unassigned,
    #assigned,
    #websiteBookings {
        max-height: 650px;
        overflow-y: auto;
        transition: all 0.3s ease;
        padding-right: 5px;
    }

    /* Simple scroll indicator */
    #unassigned::after,
    #assigned::after,
    #websiteBookings::after {
        content: "↓ Scroll for more";
        display: block;
        text-align: center;
        color: #6c757d;
        font-size: 12px;
        padding: 8px;
        background: linear-gradient(to bottom, transparent, #f8f9fa);
        position: sticky;
        bottom: 0;
        width: 100%;
        pointer-events: none;
    }

    /* Hide indicator when hovering */
    #unassigned:hover::after,
    #assigned:hover::after,
    #websiteBookings:hover::after,
    #unassigned:focus-within::after,
    #assigned:focus-within::after,
    #websiteBookings:focus-within::after {
        opacity: 0;
    }

    /* Add scrollbar styling */
    #unassigned::-webkit-scrollbar,
    #assigned::-webkit-scrollbar,
    #websiteBookings::-webkit-scrollbar {
        width: 6px;
    }

    #unassigned::-webkit-scrollbar-track,
    #assigned::-webkit-scrollbar-track,
    #websiteBookings::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    #unassigned::-webkit-scrollbar-thumb,
    #assigned::-webkit-scrollbar-thumb,
    #websiteBookings::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    #unassigned::-webkit-scrollbar-thumb:hover,
    #assigned::-webkit-scrollbar-thumb:hover,
    #websiteBookings::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Fix map height */
    #driversMapWrapper {
        position: relative;
        height: 600px !important;
        width: 100%;
    }

    #driversMap {
        height: 100% !important;
        width: 100%;
    }

    .leaflet-container {
        height: 100% !important;
        width: 100% !important;
    }

    #jobsTab .nav-tabs .nav-link {
        padding: 11px 15px;
        display: flex;
        justify-content: center;
    }

    .nav-tabs {
        margin: 0;
    }

    .fc-event-main {
        padding: 2px 4px;
        cursor: pointer;
    }

    #assignDriverModal .form-check {
        padding-left: 3.25rem;
    }

    .cursor-pointer {
        cursor: pointer !important;
    }

    #unassigned {
        max-height: 503px;
        overflow: auto;
    }

    #assigned {
        max-height: 503px;
        overflow: auto;
    }

    #driverListPanel,
    #incomingBidsPanel {
        max-height: 620px;
        overflow: auto;
    }

    #driversTab {
        max-height: 620px;
        overflow: auto;
    }

    #cancelledJobs {
        max-height: 620px;
        overflow: auto;
    }

    .bid-panel {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, .075);
        height: 100%;
    }

    .bid-header {
        background: #eaf6ff;
        padding: 10px 15px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .bidders-wrap {
        height: 100%;
        padding: 10px;
    }

    .bid-item {
        padding: 12px 9px;
        background: #e9e9f175;
        margin-bottom: 10px;
        font-size: 13px;
    }

    .bid-amount {
        font-weight: 700;
    }

    .bid-status {
        font-size: 13px;
        font-weight: 600;
    }

    .status-accepted {
        color: #198754;
    }

    .status-cancelled {
        color: #dc3545;
    }

    .status-progress {
        color: #fd7e14;
    }

    .call-con,
    .whatsapp-icon {
        width: 26px;
        height: 26px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.2s ease-in-out;
    }

    .call-con {
        background-color: #e7f1ff;
        color: #0d6efd;
    }

    .whatsapp-icon {
        background-color: #e9f7ef;
        color: #25d366;
    }

    #carPhotoModal .carousel-control-prev-icon,
    #carPhotoModal .carousel-control-next-icon {
        filter: invert(1);
    }

    #carPhotoModal .carousel-control-prev-icon,
    #carPhotoModal .carousel-control-next-icon {
        background-color: #000;
        border-radius: 50%;
        background-size: 60%;
        width: 28px;
        height: 28px;
    }

    /* ================================
   DATE RANGE INPUT
================================ */
    #jobDateRange {
        background: #f8f9fc;
        border: 1px solid #e2e6f0;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        color: #4a4f6a;
        height: 32px;
        transition: all 0.2s ease-in-out;
    }

    #jobDateRange::placeholder {
        color: #9aa0b4;
        font-weight: 500;
    }

    #jobDateRange:focus {
        border-color: #ffc45b;
        /* box-shadow: 0 0 0 0.15rem rgb(240 240 245); */
        background: #ffffff;
    }

    .ranges li:hover

    /* ================================
   DATERANGEPICKER PANEL
================================ */
    .daterangepicker {
        border-radius: 10px !important;
        border: 1px solid #f0f0f5;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        font-size: 13px;
    }

    /* LEFT RANGE LIST */
    .daterangepicker .ranges li {
        border-radius: 6px;
        padding: 8px 12px;
        margin-bottom: 4px;
        color: #5a5f7d;
        font-weight: 500;
    }

    /* HOVER */
    .daterangepicker .ranges li:hover {
        background: #f0f0f5;
        color: #382f2f;
        border: 1px solid #f0f0f5;
    }

    /* ACTIVE RANGE */
    .daterangepicker .ranges li.active {
        background: #ffc45b;
        color: #382f2f;
        border: 1px solid #f0f0f5;
    }

    /* CALENDAR HEADER */
    .daterangepicker .calendar-table th {
        color: #5a5f7d;
        font-weight: 600;
    }

    /* DATE CELLS */
    .daterangepicker td.available {
        border-radius: 6px;
    }

    .daterangepicker td.available:hover {
        background: #eef1ff;
        color: #5b6cff;
    }

    /* SELECTED RANGE */
    .daterangepicker td.in-range {
        background: #eef1ff;
        color: #4a4f6a;
    }

    .daterangepicker td.active,
    .daterangepicker td.active:hover {
        background: #5b6cff;
        color: #ffffff;
    }

    /* ================================
   APPLY / CANCEL BUTTONS
================================ */
    .daterangepicker .drp-buttons {
        border: 1px solid #f0f0f5;
        padding: 10px;
    }

    .daterangepicker .applyBtn {
        background: #5b6cff;
        border-color: #5b6cff;
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 6px;
    }

    .daterangepicker .applyBtn:hover {
        background: #4959e8;
    }

    .daterangepicker .cancelBtn {
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 6px;
        color: #6c757d;
    }

    .past-jobs-slider {
        position: fixed;
        right: 0;
        top: 70px;
        width: 300px;
        height: calc(100% - 70px);
        background: #fff;
        box-shadow: -4px 0 10px rgba(0, 0, 0, .1);
        padding: 12px;
        animation: slideIn .3s ease;
        z-index: 1050;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
        }

        to {
            transform: translateX(0);
        }
    }

    .past-jobs-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .past-job {
        padding: 8px;
        border-bottom: 1px solid #eee;
    }

    /* Add to your existing CSS */
    .past-jobs-slider {
        position: fixed;
        right: 0;
        top: 70px;
        width: 350px;
        height: calc(100vh - 70px);
        background: white;
        box-shadow: -4px 0 15px rgba(0, 0, 0, 0.1);
        padding: 15px;
        z-index: 1050;
        overflow-y: auto;
        transition: transform 0.3s ease;
    }

    .past-jobs-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 10px;
        margin-bottom: 15px;
        border-bottom: 1px solid #dee2e6;
    }

    .past-job {
        padding: 12px;
        margin-bottom: 10px;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        background: #f8f9fa;
        transition: all 0.2s;
    }

    .past-job:hover {
        background: #e9ecef;
        border-color: #dee2e6;
    }

    .copyPastJob {
        margin-top: 8px;
        font-size: 12px;
    }

    /* ================================
   BID REMARKS
================================ */
    .bid-remark {
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 6px;
        margin-top: 4px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        line-height: 1.4;
    }

    /* Remark Types */
    .remark-success {
        background: #e9f7ef;
        color: #198754;
    }

    .remark-warning {
        background: #fff3cd;
        color: #856404;
    }

    .remark-danger {
        background: #fdecea;
        color: #dc3545;
    }

    .profile-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: 4px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
    }

    .profile-good {
        border-color: #198754;
        /* green */
        color: #198754;
    }

    .profile-medium {
        border-color: #ffc107;
        /* yellow */
        color: #856404;
    }

    .profile-low {
        border-color: #dc3545;
        /* red */
        color: #dc3545;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .past-jobs-slider {
            width: 100%;
            top: 0;
            height: 100vh;
        }
    }

    .offcanvas.offcanvas-end#driverEditDrawer {
        width: 100%;
    }

    .cursor-pointer {
        cursor: pointer !important;
    }

    .pulse-marker {
        width: 14px;
        height: 14px;
        background: #0d6efd;
        border-radius: 50%;
        position: relative;
    }

    .pulse-marker::after {
        content: '';
        position: absolute;
        width: 14px;
        height: 14px;
        background: rgba(13, 110, 253, 0.5);
        border-radius: 50%;
        animation: pulse 1.5s infinite;
        top: 0;
        left: 0;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }

        100% {
            transform: scale(3);
            opacity: 0;
        }
    }

    #mapLoader {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        transition: opacity 0.3s ease;
    }

    #mapLoader.hide {
        opacity: 0;
        pointer-events: none;
    }

    .map-spinner {
        width: 45px;
        height: 45px;
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-top: 4px solid #0d6efd;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        100% {
            transform: rotate(360deg);
        }
    }

    .map-loader-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .fc-v-event .fc-event-main-frame {
        color: #fff;
    }

    .calendar-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(2px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 20;
        transition: opacity 0.3s ease;
    }

    .calendar-spinner {
        width: 36px;
        height: 36px;
        border: 4px solid #dee2e6;
        border-top: 4px solid #0d6efd;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    
    .ts-control {
        min-height: 34px;
        font-size: 13px;
    }
    .ts-dropdown {
        font-size: 13px;
    }
</style>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <input type="hidden" id="tabID" value="agents">
        <div class="main-container container-fluid">

            <ul class="nav nav-tabs mt-3 gap-3 align-items-center" id="dashboardTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#jobsTab">
                        Current Jobs
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#driversTab">
                        Drivers
                    </button>
                </li>
                <li class="nav-item d-none">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#scheduled" aria-selected="false"
                        tabindex="-1" role="tab">
                        Scheduled Jobs
                    </button>
                </li>
                <li class="nav-item d-none">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#requested" aria-selected="false"
                        tabindex="-1" role="tab">
                        Requested Jobs
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cancelledJobs">
                        Cancelled Jobs
                    </button>
                </li>
            </ul>


            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="jobsTab">
                    <div class="row g-3">
                        <div class="col-lg-4">

                            <div class="card h-100">
                                <div class="card-header p-0">
                                    <ul class="nav nav-tabs nav-justified" role="tablist">
                                        <li class="nav-item">
                                            <button class="nav-link active" data-bs-toggle="tab"
                                                data-bs-target="#unassigned">
                                                Unassigned
                                                <span class="badge bg-secondary ms-1" id="unassignedCount">0</span>
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#assigned">
                                                Assigned
                                                <span class="badge bg-success ms-1" id="assignedCount">0</span>
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab"
                                                data-bs-target="#websiteBookings">
                                                Website
                                                <span class="badge bg-warning ms-1" id="websiteCount">0</span>
                                            </button>
                                        </li>
                                    </ul>
                                </div>

                                <div class="card-body p-2">
                                    <div class="row  align-items-center mb-2">

                                        <div class="col-2">
                                            <div class="dropdown w-100">
                                                <button
                                                    class="btn btn-sm btn-light border w-100  py-1 d-flex align-items-center justify-content-center gap-1"
                                                    type="button" id="jobFilterBtn" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="fa fa-filter text-warning"></i>

                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-start shadow-sm w-100">
                                                    <li>
                                                        <a class="dropdown-item active" href="#" data-filter="all">
                                                            <i class="fa fa-list me-2 text-secondary"></i>Select Filter
                                                            Type
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" data-filter="booked">
                                                            <i class="fa fa-check-circle me-2 text-success"></i>Booked
                                                            (Datewise)
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" data-filter="pickup">
                                                            <i class="fa fa-map-marker-alt me-2 text-primary"></i>Pickup
                                                            (Datewise)
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>


                                        <div class="col-4">
                                            <input type="text" id="jobDateRange"
                                                class="form-control form-control-sm text-center py-0 px-1"
                                                placeholder="Select Date" readonly style="cursor:pointer;">
                                        </div>


                                        <div class="col-4 p-0">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fa fa-search text-dark"></i>
                                                </span>
                                                <input type="text" class="form-control border-start-0 p-0"
                                                    id="jobSearch" placeholder="Search jobs...">
                                            </div>
                                        </div>

                                        <div class="col-2 text-end d-flex justify-content-end gap-2">
                                            <button class="btn btn-sm btn-success  createJobBtn">
                                                <i class="fa fa-plus me-1"></i>
                                            </button>

                                        </div>


                                    </div>

                                    <div class="tab-content" style="max-height: 650px;overflow: auto;">
                                        <div class="tab-pane fade show active" id="unassigned">
                                            <div id="unassignedLoader" class="text-center py-4" style="display:none">
                                                <div class="spinner-border" role="status"></div>
                                            </div>
                                            <div id="unassignedJobs"></div>
                                        </div>
                                        <div class="tab-pane fade" id="assigned">
                                            <div id="assignedLoader" class="text-center py-4" style="display:none">
                                                <div class="spinner-border" role="status"></div>
                                            </div>
                                            <div id="assignedJobs"></div>
                                        </div>
                                        <div class="tab-pane fade" id="websiteBookings">
                                            <div id="websiteLoader" class="text-center py-4" style="display:none">
                                                <div class="spinner-border text-warning" role="status"></div>
                                            </div>
                                            <div id="websiteJobs"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="col-lg-8">
                            <div class="row">

                                <!-- ================= LEFT PANEL ================= -->
                                <div class="col-lg-6 d-none" id="leftPanel">

                                    <!-- INCOMING BIDS -->
                                    <div id="incomingBidsPanel" class="d-none">
                                        <div class="bid-panel">

                                            <div class="bid-header d-flex align-items-center justify-content-between">
                                                <a href="javascript:void(0)"
                                                    class="backToMap text-decoration-none text-dark small">
                                                    <i class="fa fa-arrow-left me-1"></i> Back
                                                </a>

                                                <span class="fw-semibold">Incoming Bids</span>

                                                <span class="text-info small">
                                                    <i class="fa fa-coins me-1"></i> Best Price: ₹4246
                                                </span>
                                            </div>

                                            <div class="bidders-wrap">

                                                <div class="bid-item">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <a href="#"
                                                                class="fw-bold text-dark text-decoration-none driver-link"
                                                                data-driver="1">
                                                                Elavarasan
                                                            </a>

                                                            <i class="fa fa-check-circle text-success"
                                                                title="Verified"></i>
                                                            <i class="fa fa-map-marker-alt text-danger cursor-pointer"
                                                                title="View Location"></i>
                                                        </div>

                                                        <span class="bid-amount">₹4246</span>
                                                    </div>

                                                    <!-- DRIVER REMARK -->
                                                    <div class="bid-remark remark-success">
                                                        <i class="fa fa-comment-dots"></i>
                                                        Available immediately · Preferred driver
                                                    </div>

                                                    <div class="d-flex justify-content-end gap-3 mt-2">
                                                        <span class="text-success fw-semibold cursor-pointer bid-accept"
                                                            data-bid="1">
                                                            <i class="fa fa-check-circle me-1"></i> Accept
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="bid-item">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <a href="#"
                                                                class="fw-bold text-dark text-decoration-none driver-link"
                                                                data-driver="2">
                                                                Elavarasan
                                                            </a>

                                                            <i class="fa fa-check-circle text-success"></i>
                                                            <i
                                                                class="fa fa-map-marker-alt text-danger cursor-pointer"></i>
                                                        </div>

                                                        <span class="bid-amount">₹4350</span>
                                                    </div>

                                                    <div class="bid-remark remark-warning">
                                                        <i class="fa fa-comment-dots"></i>
                                                        Traffic near toll gate · 10 mins delay
                                                    </div>

                                                    <div class="d-flex justify-content-end gap-3 mt-2">
                                                        <span class="text-success fw-semibold cursor-pointer bid-accept"
                                                            data-bid="2">
                                                            <i class="fa fa-check-circle me-1"></i> Accept
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="bid-item">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <a href="#"
                                                                class="fw-bold text-dark text-decoration-none driver-link"
                                                                data-driver="3">
                                                                Elavarasan
                                                            </a>

                                                            <i class="fa fa-check-circle text-success"></i>
                                                            <i
                                                                class="fa fa-map-marker-alt text-danger cursor-pointer"></i>
                                                        </div>

                                                        <span class="bid-amount">₹4600</span>
                                                    </div>

                                                    <div class="bid-remark remark-danger">
                                                        <i class="fa fa-comment-dots"></i>
                                                        Long route · Extra toll charges expected
                                                    </div>

                                                    <div class="d-flex justify-content-end gap-3 mt-2">
                                                        <span class="text-danger fw-semibold cursor-pointer bid-reject"
                                                            data-bid="3">
                                                            <i class="fa fa-times-circle me-1"></i> Reject
                                                        </span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div id="driverListPanel" class="d-none">
                                        <div class="bid-panel">

                                            <div class="bid-header d-flex align-items-center justify-content-between">
                                                <a href="javascript:void(0)"
                                                    class="backToMap text-decoration-none text-dark small">
                                                    <i class="fa fa-arrow-left me-1"></i> Back
                                                </a>

                                                <span class="fw-semibold">Available Drivers</span>
                                            </div>

                                            <div class="p-2">
                                                <input type="text" id="driverSearchInput"
                                                    class="form-control form-control-sm"
                                                    placeholder="Search driver by name...">
                                            </div>

                                            <div class="bidders-wrap" id="driverListContainer">
                                                <div id="driverContainerLoader" class="text-center py-4"
                                                    style="display:none">
                                                    <div class="spinner-border" role="status"></div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!-- ================= RIGHT PANEL ================= -->
                                <div class="col-12" id="rightPanel">

                                    <div class="card h-100" id="mapCard">
                                        <div class="card-body p-0">
                                            <div id="driversMapWrapper" style="position:relative; height:600px;">
                                                <div id="mapLoader">
                                                    <div class="map-loader-content">
                                                        <div class="map-spinner"></div>
                                                        <div class="mt-2">Loading Live Drivers...</div>
                                                    </div>
                                                </div>
                                                <div id="driversMap" style="height:100%;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">

                                        <div class="col-lg-6">
                                            <div class="card mb-2 driverDetailsCard d-none">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <strong>
                                                            <i class="fa fa-id-card me-1 text-danger"></i> Driver
                                                            Details
                                                        </strong>
                                                        <a href="javascript:void(0)"
                                                            class="backToMap text-decoration-none text-dark small">
                                                            <i class="fa fa-arrow-left me-1"></i> Back
                                                        </a>
                                                    </div>
                                                    
                                                    <div class="d-flex align-items-center mt-3">
                                                        <img src="/assets/images/driver.png" alt="Driver Photo"
                                                            class="rounded-circle me-3 cursor-pointer"
                                                            style="width:60px;height:60px;object-fit:cover;"
                                                            data-bs-toggle="modal" data-bs-target="#driverPhotoModal">

                                                        <div class="w-100">
                                                            <div
                                                                class="d-flex justify-content-between align-items-center">
                                                                <div class="d-flex align-items-center gap-2"
                                                                    style="font-size:14px">
                                                                    <span class="driver_name">Venkatesan</span>
                                                                    <a href="#" class="text-warning driver_profile_edit" title="Edit Driver">
                                                                        <i class="fa fa-edit text-info"></i>
                                                                    </a>
                                                                </div>
                                                                <div class="d-inline-flex align-items-center gap-2">
                                                                    <a href="tel:9876543210" class="call-con"
                                                                        title="Call Owner">
                                                                        <i class="fa fa-phone"></i>
                                                                    </a>
                                                                    <a href="https://wa.me/919876543210" target="_blank"
                                                                        class="whatsapp-icon" title="WhatsApp Owner">
                                                                        <i class="fab fa-whatsapp"></i>
                                                                    </a>
                                                                </div>
                                                            </div>

                                                            <div class="small text-muted mt-1"
                                                                style="display: flex;flex-direction: column;">
                                                                <span><i
                                                                        class="fa fa-check-circle text-success me-1"></i>
                                                                    KYC Verified</span>
                                                                <span><i
                                                                        class="fa fa-map-marker-alt text-danger me-1"></i>
                                                                    Chennai, Tamil Nadu</span>
                                                                <span><i class="fa fa-briefcase text-info"></i> 8
                                                                    Years</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3 small">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <i class="fa fa-id-badge text-primary"></i>
                                                                <strong>LMV-TR</strong> <span>|<strong>12 Jan
                                                                        2028</strong></span>
                                                            </div>
                                                            <div class="col-6 p-0">
                                                                <i class="fa fa-language text-dark"></i>
                                                                <strong>Tamil, English</strong>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr class="my-3">

                                                    <strong><i class="fa fa-car me-1 text-danger"></i> Vehicle
                                                        Details</strong>

                                                    <div class="d-flex align-items-center mt-3">
                                                        <img src="/assets/images/carimage.png" alt="Car Image"
                                                            class="rounded-circle me-3 cursor-pointer"
                                                            style="width:80px;height:60px;object-fit:cover;"
                                                            data-bs-toggle="modal" data-bs-target="#carPhotoModal">

                                                        <div class="w-100">
                                                            <div
                                                                class="d-flex justify-content-between align-items-center">
                                                                <div style="font-size:13px">
                                                                    <strong>Go Sedan</strong> (Hyundai Aura 1.2 MT CNG)
                                                                    <span><i
                                                                            class="fa fa-check-circle text-success me-1"></i></span>
                                                                </div>
                                                            </div>

                                                            <div class="mt-2 small text-muted">
                                                                <div class="d-flex align-items-center gap-4 mb-1">
                                                                    <div class="d-flex align-items-center">
                                                                        <i
                                                                            class="fa fa-users text-info me-1"></i><strong>4+1</strong>
                                                                    </div>
                                                                    <div class="d-flex align-items-center">
                                                                        <i
                                                                            class="fa fa-gas-pump text-warning me-1"></i><strong>Petrol</strong>
                                                                    </div>
                                                                    <div class="d-flex align-items-center">
                                                                        <i
                                                                            class="fa fa-suitcase-rolling text-secondary me-1"></i><strong>2</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center gap-4">
                                                                    <div class="d-flex align-items-center">
                                                                        <i
                                                                            class="fa fa-file-contract text-success me-1"></i>RC:
                                                                        <strong>30 Nov 2026</strong>
                                                                    </div>
                                                                    <div class="d-flex align-items-center">
                                                                        <i
                                                                            class="fa fa-file-alt text-danger me-1"></i>IN:
                                                                        <strong>15 Aug 2027</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3 p-3 rounded d-none">
                                                        <!-- Ratings section (hidden) -->
                                                    </div>

                                                    <div class="mt-3 p-3 bg-light small">
                                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fa fa-road text-primary me-2"></i>Per Km:
                                                                <strong class="ms-1">₹14</strong>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fa fa-plus-circle text-warning me-2"></i>Extra
                                                                Km: <strong class="ms-1">₹15</strong>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fa fa-clock text-info me-2"></i>Additional
                                                                Hour: <strong class="ms-1">₹100</strong>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <i
                                                                    class="fa fa-calendar-plus text-success me-2"></i>Extra
                                                                Day: <strong class="ms-1">₹1200</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Owner Details Card - takes half width -->
                                        <div class="col-lg-6"> <!-- ADDED: column wrapper -->
                                            <div class="card mb-2 d-none" id="ownerDetailsCard">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <!-- LEFT -->
                                                        <strong><i class="fa fa-id-card me-1 text-danger"></i> Customer
                                                            Details</strong>
                                                        <!-- RIGHT -->
                                                        <a href="javascript:void(0)"
                                                            class="backToMap text-decoration-none text-dark small">
                                                            <i class="fa fa-arrow-left me-1"></i> Back
                                                        </a>
                                                    </div>

                                                    <!-- Customer Photo -->
                                                    <div class="d-flex align-items-center mt-3">
                                                        <img src="/assets/images/driver.png" alt="Customer Photo"
                                                            class="rounded-circle me-3 cursor-pointer"
                                                            style="width:60px;height:60px;object-fit:cover;"
                                                            data-bs-toggle="modal" data-bs-target="#driverPhotoModal">

                                                        <div class="w-100">
                                                            <!-- ROW 1 : NAME (LEFT) | MOBILE (RIGHT) -->
                                                            <div
                                                                class="d-flex justify-content-between align-items-center">
                                                                <!-- LEFT : NAME + EDIT -->
                                                                <div class="d-flex align-items-center gap-2"
                                                                    style="font-size:14px">
                                                                    <span>Venkatesan</span>
                                                                    <a href="#" class="text-warning"
                                                                        title="Edit Driver">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a>
                                                                </div>
                                                                <!-- RIGHT : CALL + WHATSAPP -->
                                                                <div class="d-inline-flex align-items-center gap-2">
                                                                    <a href="tel:9876543210" class="call-con"
                                                                        title="Call Owner">
                                                                        <i class="fa fa-phone"></i>
                                                                    </a>
                                                                    <a href="https://wa.me/919876543210" target="_blank"
                                                                        class="whatsapp-icon" title="WhatsApp Owner">
                                                                        <i class="fab fa-whatsapp"></i>
                                                                    </a>
                                                                </div>
                                                            </div>

                                                            <div class="small text-muted mt-1"
                                                                style="display: flex;flex-direction: column;">
                                                                <span><i
                                                                        class="fa fa-check-circle text-success me-1"></i>
                                                                    KYC Verified</span>
                                                                <span><i
                                                                        class="fa fa-map-marker-alt text-danger me-1"></i>
                                                                    Chennai, Tamil Nadu</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-1 small text-muted">
                                                        <i class="fa fa-user-clock me-1"></i>Account Created: <strong>15
                                                            Mar 2022</strong>
                                                    </div>

                                                    <hr class="my-3">

                                                    <div class="mt-3 p-3 rounded">
                                                        <!-- ROW : RATING + TOTAL RATINGS -->
                                                        <div class="d-flex align-items-center gap-3 mb-2">
                                                            <div class="d-flex align-items-center">
                                                                <i
                                                                    class="fa fa-star text-warning me-1"></i><strong>4.2</strong>
                                                            </div>
                                                            <div class="d-flex align-items-center text-muted"
                                                                style="font-size:12px;">
                                                                <i class="fa fa-user-check me-1"></i><span>(112
                                                                    Ratings)</span>
                                                            </div>
                                                        </div>

                                                        <!-- ROW 2 : JOB STATS -->
                                                        <div class="d-flex align-items-center gap-4 small mb-2">
                                                            <div class="d-flex align-items-center">
                                                                <i
                                                                    class="fa fa-check-circle text-success me-1"></i>Accepted:
                                                                <strong class="ms-1">40</strong>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <i
                                                                    class="fa fa-flag-checkered text-primary me-1"></i>Completed:
                                                                <strong class="ms-1">32</strong>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <i
                                                                    class="fa fa-times-circle text-danger me-1"></i>Rejected:
                                                                <strong class="ms-1">05</strong>
                                                            </div>
                                                        </div>

                                                        <div class="d-flex align-items-center gap-3 small mb-2">
                                                            <div class="px-2 py-1 rounded bg-light d-flex align-items-center cursor-pointer"
                                                                data-bs-toggle="modal" data-bs-target="#reportModal">
                                                                <i class="fa fa-flag text-danger me-1"></i>Report:
                                                                <strong class="ms-1">2</strong>
                                                            </div>
                                                            <button class="btn btn-sm btn-danger px-2 py-1">
                                                                <i class="fa fa-ban me-1"></i> Block
                                                            </button>
                                                        </div>

                                                        <div class="small text-muted mb-2">
                                                            <i class="fa fa-comment-dots me-1 text-warning"></i>Remarks:
                                                            <strong>Good</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- END: Owner Details column -->

                                    </div> <!-- END: Row for detail cards -->

                                </div> <!-- END: Right Panel -->

                                <!-- ================= EDIT / VIEW JOB DETAILS ================= -->
                                <div class="card d-none" id="jobDetailsPanel">

                                    <!-- HEADER -->
                                    <div class="card-header d-flex justify-content-between align-items-center py-3">
                                        <div class="fw-semibold">
                                            <i class="fa fa-edit me-1 text-warning"></i>
                                            Job Details
                                        </div>

                                        <!-- BACK -->
                                        <a href="javascript:void(0)"
                                            class="backToMap text-decoration-none text-dark small">
                                            <i class="fa fa-arrow-left me-1"></i> Back
                                        </a>
                                    </div>

                                    <!-- BODY -->
                                    <div class="card-body p-0">
                                        <!-- JS WILL INJECT CONTENT HERE -->
                                        <div id="jobDetailsContent"></div>
                                    </div>

                                    <!-- FOOTER (OPTIONAL) -->
                                    <div class="card-footer d-flex justify-content-end gap-2">
                                        <button class="btn btn-sm btn-secondary backToMap">
                                            Cancel
                                        </button>

                                        <button class="btn btn-sm btn-success" id="confirmBookingBtn">
                                            <i class="fa fa-save me-1"></i>
                                        </button>
                                    </div>

                                </div>


                            </div>
                        </div>

                    </div>
                </div>
                <div class="tab-pane fade" id="driversTab">

                    <div class="row g-3">

                        <!-- LEFT : DRIVERS LIST -->
                        <div class="col-lg-3">
                            <div class="card h-100">

                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong>
                                            Drivers 
                                            <span class="badge bg-primary ms-1" id="driverCount">0</span>
                                        </strong>

                                        <div class="d-flex align-items-center gap-2">

                                            <!-- FILTER ICON -->
                                            <span class="driver-filter-icon" title="Filter by status">
                                                <i class="fa fa-filter text-warning"></i>
                                            </span>

                                            <!-- STATUS FILTER -->
                                            <select class="form-select form-select-sm driver-status-filter"
                                                id="driverStatus">
                                                <option value="">All</option>
                                                <option value="online">🟢 Online</option>
                                                <option value="busy">🟡 Busy</option>
                                                <option value="offline">🔴 Offline</option>
                                            </select>

                                        </div>
                                        
                                    </div>
                                    
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                            
                                        <select id="profileFieldFilter" multiple placeholder="Select profile fields...">
                                        
                                            <!-- 40 Marks -->
                                            <option value="complete_verified">Complete Verified (Doc + Vehicle)</option>
                                        
                                            <!-- Basic Info -->
                                            <option value="state">State</option>
                                            <option value="districts_id">District</option>
                                        
                                            <!-- Images -->
                                            <option value="images_count">Above 4 Images</option>
                                        
                                            <!-- Expiry -->
                                            <option value="dl_expiry">Driving Licence Expiry</option>
                                            <option value="passport_expiry">Passport Expiry</option>
                                        
                                            <!-- Vehicle & Driver -->
                                            <option value="vehicle_type">Vehicle Type</option>
                                            <option value="fuel_type">Fuel Type</option>
                                            <option value="exp">Driver Experience</option>
                                            <option value="seaters">Seat Capacity</option>
                                        
                                            <!-- Pricing -->
                                            <option value="per_km">Price Per KM</option>
                                            <option value="extra_per_km">Extra Price Per KM</option>
                                            <option value="per_hour">Price Per Hour</option>
                                            <option value="per_day">Price Per Day</option>
                                        
                                            <!-- Others -->
                                            <option value="upiID">UPI ID</option>
                                            <option value="reviews">Reviews</option>
                                        
                                        </select>
                                    
                                        <!-- FILTER TYPE -->
                                        <select class="form-select form-select-sm" id="profileFilterType">
                                            <option value="">-- Select Type --</option>
                                            <option value="filled">Filled</option>
                                            <option value="not_filled">Not Filled</option>
                                        </select>
    
                                    </div>
                                    
                                    <div class="mt-2">
                                        <label class="small text-muted">Profile Percentage</label>
                                        <div id="percentageRange"></div>
                                        <div class="d-flex justify-content-between small mt-1">
                                            <span id="rangeMin">0%</span>
                                            <span id="rangeMax">100%</span>
                                        </div>
                                    </div>

                                    <input type="text" class="form-control form-control-sm" id="driverSearch"
                                        placeholder="Search drivers...">
                                </div>


                                <div class="list-group list-group-flush" id="driversList"
                                    style="max-height:650px;overflow:auto">
                                    <!-- Drivers injected by JS -->
                                </div>

                            </div>
                        </div>

                        <!-- RIGHT : DRIVER CALENDAR -->
                        <div class="col-lg-9 d-none" id="driversTabCalenderWrapper">
                            <div class="card h-100">

                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <strong>Driver Schedule</strong>
                                        <button class="btn btn-sm btn-outline-secondary" id="showAllDrivers">
                                            All Drivers
                                        </button>
                                    </div>

                                    <div>
                                        <button class="btn btn-sm btn-outline-primary active" data-view="month">
                                            Month
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary" data-view="week">
                                            Week
                                        </button>
                                    </div>
                                </div>

                                <div class="card-body position-relative" style="height:550px;">
                                    <div id="calendarLoader" class="calendar-overlay d-none">
                                        <div class="calendar-spinner"></div>
                                        <div class="mt-2 small text-muted">Loading drivers schedule...</div>
                                    </div>

                                    <div id="driverCalendar"></div>
                                </div>

                            </div>
                        </div>
                        
                        <div class="col-lg-6 d-none" id="driversTabCardWrapper">
                            <div class="card mb-2 driverDetailsCard d-none">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong>
                                            <i class="fa fa-id-card me-1 text-danger"></i> Driver
                                            Details
                                        </strong>
                                        <a href="javascript:void(0)"
                                            class="backToMap text-decoration-none text-dark small">
                                            <i class="fa fa-arrow-left me-1"></i> Back
                                        </a>
                                    </div>
                                    
                                    <div class="d-flex align-items-center mt-3">
                                        <img src="/assets/images/driver.png" alt="Driver Photo"
                                            class="rounded-circle me-3 cursor-pointer"
                                            style="width:60px;height:60px;object-fit:cover;"
                                            data-bs-toggle="modal" data-bs-target="#driverPhotoModal">

                                        <div class="w-100">
                                            <div
                                                class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center gap-2"
                                                    style="font-size:14px">
                                                    <span class="driver_name">Venkatesan</span>
                                                    <a href="#" class="text-warning driver_profile_edit" title="Edit Driver">
                                                        <i class="fa fa-edit text-info"></i>
                                                    </a>
                                                </div>
                                                <div class="d-inline-flex align-items-center gap-2">
                                                    <a href="tel:9876543210" class="call-con"
                                                        title="Call Owner">
                                                        <i class="fa fa-phone"></i>
                                                    </a>
                                                    <a href="https://wa.me/919876543210" target="_blank"
                                                        class="whatsapp-icon" title="WhatsApp Owner">
                                                        <i class="fab fa-whatsapp"></i>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="small text-muted mt-1"
                                                style="display: flex;flex-direction: column;">
                                                <span><i
                                                        class="fa fa-check-circle text-success me-1"></i>
                                                    KYC Verified</span>
                                                <span><i
                                                        class="fa fa-map-marker-alt text-danger me-1"></i>
                                                    Chennai, Tamil Nadu</span>
                                                <span><i class="fa fa-briefcase text-info"></i> 8
                                                    Years</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3 small">
                                        <div class="row">
                                            <div class="col-6">
                                                <i class="fa fa-id-badge text-primary"></i>
                                                <strong>LMV-TR</strong> <span>|<strong>12 Jan
                                                        2028</strong></span>
                                            </div>
                                            <div class="col-6 p-0">
                                                <i class="fa fa-language text-dark"></i>
                                                <strong>Tamil, English</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-3">

                                    <strong><i class="fa fa-car me-1 text-danger"></i> Vehicle
                                        Details</strong>

                                    <div class="d-flex align-items-center mt-3">
                                        <img src="/assets/images/carimage.png" alt="Car Image"
                                            class="rounded-circle me-3 cursor-pointer"
                                            style="width:80px;height:60px;object-fit:cover;"
                                            data-bs-toggle="modal" data-bs-target="#carPhotoModal">

                                        <div class="w-100">
                                            <div
                                                class="d-flex justify-content-between align-items-center">
                                                <div style="font-size:13px">
                                                    <strong>Go Sedan</strong> (Hyundai Aura 1.2 MT CNG)
                                                    <span><i
                                                            class="fa fa-check-circle text-success me-1"></i></span>
                                                </div>
                                            </div>

                                            <div class="mt-2 small text-muted">
                                                <div class="d-flex align-items-center gap-4 mb-1">
                                                    <div class="d-flex align-items-center">
                                                        <i
                                                            class="fa fa-users text-info me-1"></i><strong>4+1</strong>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <i
                                                            class="fa fa-gas-pump text-warning me-1"></i><strong>Petrol</strong>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <i
                                                            class="fa fa-suitcase-rolling text-secondary me-1"></i><strong>2</strong>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center gap-4">
                                                    <div class="d-flex align-items-center">
                                                        <i
                                                            class="fa fa-file-contract text-success me-1"></i>RC:
                                                        <strong>30 Nov 2026</strong>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <i
                                                            class="fa fa-file-alt text-danger me-1"></i>IN:
                                                        <strong>15 Aug 2027</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3 p-3 rounded d-none">
                                        <!-- Ratings section (hidden) -->
                                    </div>

                                    <div class="mt-3 p-3 bg-light small">
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <div class="d-flex align-items-center">
                                                <i class="fa fa-road text-primary me-2"></i>Per Km:
                                                <strong class="ms-1">₹14</strong>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="fa fa-plus-circle text-warning me-2"></i>Extra
                                                Km: <strong class="ms-1">₹15</strong>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="fa fa-clock text-info me-2"></i>Additional
                                                Hour: <strong class="ms-1">₹100</strong>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i
                                                    class="fa fa-calendar-plus text-success me-2"></i>Extra
                                                Day: <strong class="ms-1">₹1200</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="tab-pane fade" id="scheduled">

                    <div class="card shadow-sm border-0">

                        <!-- HEADER / FILTER -->
                        <div class="card-header bg-light">
                            <div class="row g-2 align-items-end">

                                <div class="col-md-4 col-6">
                                    <label class="form-label small fw-semibold text-muted mb-1">
                                        <i class="fa fa-map-marker-alt text-success me-1"></i> From
                                    </label>
                                    <select id="scheduledFrom" class="form-select form-select-sm select2">
                                        <option value="">Select From</option>
                                    </select>
                                </div>

                                <div class="col-md-4 col-6">
                                    <label class="form-label small fw-semibold text-muted mb-1">
                                        <i class="fa fa-map-marker-alt text-danger me-1"></i> To
                                    </label>
                                    <select id="scheduledTo" class="form-select form-select-sm select2">
                                        <option value="">Select To</option>
                                    </select>
                                </div>

                                <div class="col-md-4 col-12">
                                    <button class="btn btn-sm btn-primary w-100" id="searchScheduledJobs">
                                        <i class="fa fa-search me-1"></i> Search
                                    </button>
                                </div>

                            </div>
                        </div>

                        <!-- BODY -->
                        <div class="card-body p-2">
                            <div id="scheduledJobs"></div>
                        </div>

                    </div>

                </div>
                <div class="tab-pane fade" id="requested">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light fw-semibold">
                            Requested
                        </div>
                        <div class="card-body p-2">
                            <div id="requestedJobs"></div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="cancelledJobs">
                    <div id="cancelledLoader" class="text-center py-4" style="display:none">
                        <div class="spinner-border text-danger" role="status"></div>
                    </div>
                    <div id="cancelledJobsList"></div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="assignDriverModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">

                    <div class="modal-header bg-primary text-white border-0">
                        <h5 class="modal-title">
                            <i class="fa fa-user-plus me-2"></i>Assign Driver to Job
                        </h5>
                        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-4">
                        <input type="hidden" id="job_id">

                        <div class="mb-3" id="jobDetailsSection"></div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fa fa-user me-2 text-primary"></i>Select Driver
                            </label>
                            <select class="form-select" id="driver_id">
                                <option value="">-- Choose Driver --</option>
                            </select>
                        </div>

                        <div class="border-top pt-3 mt-3">
                            <label class="form-label fw-bold mb-3">
                                <i class="fa fa-bell me-2 text-primary"></i>Notifications
                            </label>

                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="notify_whatsapp" checked>
                                <label class="form-check-label cursor-pointer" for="notify_whatsapp">
                                    <i class="fab fa-whatsapp text-success me-2"></i>Notify via WhatsApp
                                </label>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notify_sms" checked>
                                <label class="form-check-label cursor-pointer" for="notify_sms">
                                    <i class="fa fa-sms text-info me-2"></i>Notify via SMS
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 bg-light">
                        <button class="btn btn-secondary px-4" data-bs-dismiss="modal">
                            <i class="fa fa-times me-2"></i>Cancel
                        </button>
                        <button class="btn btn-success px-4" id="assignBtn">
                            <i class="fa fa-check me-2"></i>Assign Driver
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header py-2 bg-light">
                <h6 class="modal-title d-flex align-items-center gap-2">
                    <i class="fa fa-flag text-danger"></i>
                    Report Details
                </h6>

                <button type="button" class="border-0 bg-transparent text-dark" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>

            </div>

            <!-- BODY -->
            <div class="modal-body small">

                <!-- BASIC INFO -->
                <div class="mb-3">
                    <div class="fw-semibold text-dark mb-1">
                        <i class="fa fa-user me-1 text-primary"></i>
                        Name:
                        <span class="fw-normal">Elavarasan</span>
                    </div>

                    <div class="fw-semibold text-dark mb-1">
                        <i class="fa fa-id-badge me-1 text-secondary"></i>
                        Type:
                        <span class="badge bg-warning text-dark ms-1">
                            Driver
                        </span>
                    </div>

                    <div class="fw-semibold text-dark">
                        <i class="fa fa-flag me-1 text-danger"></i>
                        Total Reports:
                        <span class="badge bg-danger ms-1">2</span>
                    </div>
                </div>

                <hr>

                <!-- REPORT LIST -->
                <div class="mb-2 fw-semibold text-muted">
                    Report History
                </div>

                <div class="list-group list-group-flush">

                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between">
                            <span>
                                <i class="fa fa-exclamation-circle text-danger me-1"></i>
                                Abusive Behaviour
                            </span>
                            <small class="text-muted">12 Jan 2026</small>
                        </div>
                        <div class="text-muted small mt-1">
                            Driver used inappropriate language during pickup.
                        </div>
                    </div>

                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between">
                            <span>
                                <i class="fa fa-exclamation-circle text-warning me-1"></i>
                                Late Arrival
                            </span>
                            <small class="text-muted">05 Jan 2026</small>
                        </div>
                        <div class="text-muted small mt-1">
                            Reached pickup location 45 minutes late.
                        </div>
                    </div>

                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer py-2">
                <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>

                <button class="btn btn-sm btn-danger">
                    <i class="fa fa-ban me-1"></i>
                    Take Action
                </button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="driverPhotoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px;height:100%;">
        <div class="modal-content">

            <!-- Close Button -->
            <div class="modal-header py-1 px-2">
                <h6 class="modal-title">Driver Photo</h6>
                <button type="button" class="btn p-0" data-bs-dismiss="modal">
                    <i class="fa fa-times text-dark"></i>
                </button>

            </div>

            <!-- Image -->
            <div class="modal-body p-2 text-center">
                <img src="/assets/images/driver.png" class="img-fluid " style="max-height:250px;" alt="Driver Zoom">
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="carPhotoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:300px;">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header py-1 px-2">
                <h6 class="modal-title">Car Photos</h6>
                <button type="button" class="btn p-0" data-bs-dismiss="modal">
                    <i class="fa fa-times text-dark"></i>
                </button>
            </div>

            <!-- BODY -->
            <div class="modal-body p-2">

                <div id="carImageCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-touch="true">

                    <!-- IMAGES -->
                    <div class="carousel-inner text-center">

                        <div class="carousel-item active">
                            <img src="/assets/images/carimage.png" class="img-fluid " style="max-height:250px;"
                                alt="Car Image 1">
                        </div>

                        <div class="carousel-item">
                            <img src="/assets/images/cariamge2.jpg" class="img-fluid " style="max-height:250px;"
                                alt="Car Image 2">
                        </div>

                        <div class="carousel-item">
                            <img src="/assets/images/carimage3.png" class="img-fluid " style="max-height:250px;"
                                alt="Car Image 3">
                        </div>

                    </div>

                    <!-- CONTROLS -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#carImageCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>

                    <button class="carousel-control-next" type="button" data-bs-target="#carImageCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>

                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmActionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">

            <div class="modal-header py-2 align-items-center">
                <h6 class="modal-title d-flex align-items-center gap-2" id="confirmTitle">
                    <i id="confirmIcon" class="fa"></i>
                    <span id="confirmTitleText">Confirm Action</span>
                </h6>
                <button type="button" class="border-0 bg-transparent text-dark" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <div class="modal-body small text-center">
                <p id="confirmMessage" class="mb-0"></p>
            </div>

            <div class="modal-footer py-2 justify-content-center">
                <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>

                <button class="btn btn-sm btn-dark" id="confirmYesBtn">
                    Yes
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Add this modal AFTER the cancelJobModal -->
<div class="modal fade" id="removeDriverModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header py-2 align-items-center">
                <h6 class="modal-title d-flex align-items-center gap-2">
                    <i class="fa fa-user-times text-danger"></i>
                    Remove Driver
                </h6>
                <button type="button" class="border-0 bg-transparent text-dark" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <!-- BODY -->
            <div class="modal-body text-center small">
                <p class="mb-0">
                    Are you sure you want to
                    <strong class="text-danger">remove this driver</strong> from the job?
                </p>
            </div>

            <!-- FOOTER -->
            <div class="modal-footer py-2 justify-content-center">
                <button class="btn btn-sm btn-secondary" id="removeDriverNoBtn">
                    Cancel
                </button>

                <button class="btn btn-sm btn-danger" id="confirmRemoveDriverBtn">
                    <i class="fa fa-user-times me-1"></i> Yes, Remove
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Driver Edit Modal -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="driverEditDrawer">
    <div class="offcanvas-header">
        <h5>
            <i class="fa fa-user-edit me-2"></i> Edit Driver
        </h5>
        <button class="btn-close" data-bs-dismiss="offcanvas">
            <i class="fa fa-close"></i>
        </button>
    </div>

    <div class="offcanvas-body p-0">
        <iframe id="driverEditIframe" src="" style="width:100%; height:100%; border:0;">
        </iframe>
    </div>
</div>

<div class="modal fade" id="createBidModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title">
                    <i class="fa fa-gavel me-2 text-warning"></i>
                    Create Bid
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="bidJobId">
                <input type="hidden" id="bidDriverId">

                <div class="mb-3">
                    <label class="form-label">Bid Amount</label>
                    <input type="number" id="bidAmount" class="form-control" placeholder="Enter amount">
                </div>

                <div class="mb-2">
                    <label class="form-label">Remark (optional)</label>
                    <input type="text" id="bidRemark" class="form-control" placeholder="Remark">
                </div>

                <small id="bidErrorMsg" class="text-danger d-none"></small>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    Cancel
                </button>

                <button class="btn btn-success btn-sm" id="submitBidBtn">
                    <span class="bid-btn-text">
                        <i class="fa fa-paper-plane me-1"></i> Bid
                    </span>
                    <span class="bid-btn-loader d-none">
                        <i class="fa fa-spinner fa-spin me-1"></i> Bidding...
                    </span>
                </button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="cancelJobModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title text-danger">
                    <i class="fa fa-times-circle me-2"></i>
                    Cancel Job
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">

                <input type="hidden" id="cancelJobId">
                <input type="hidden" id="cancelJobNo">
                <input type="hidden" id="cancelJobType">
                <input type="hidden" id="cancelJobUserId">

                <p class="mb-2">
                    Are you sure you want to cancel this job?
                </p>

                <small class="text-muted">
                    This action cannot be undone.
                </small>

                <div id="cancelJobError" class="text-danger mt-2 d-none">
                </div>

            </div>

            <div class="modal-footer justify-content-center">

                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    Keep Job
                </button>

                <button class="btn btn-danger btn-sm" id="confirmCancelJobBtn">
                    <span class="cancel-btn-text">
                        <i class="fa fa-times me-1"></i> Yes, Cancel
                    </span>
                    <span class="cancel-btn-loader d-none">
                        <i class="fa fa-spinner fa-spin me-1"></i> Cancelling...
                    </span>
                </button>

            </div>

        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>

<!--Map-->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Marker Cluster -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<!-- Tom Select -->
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<!-- noUiSlider -->
<link href="https://cdn.jsdelivr.net/npm/nouislider@15.7.0/dist/nouislider.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/nouislider@15.7.0/dist/nouislider.min.js"></script>

<script>
    const API_DOMAIN_2 = "<?= API_DOMAIN_2 ?>";
</script>

<script>

    let selectedDriverRequest = null;
    const jobStatuses = {
        1021: 'dispatched',
        1022: 'assigned',
        1023: 'reached',
        1024: 'onboard'
    };

    var assignments = {
        1021: 1,
        1022: 2,
        1023: 3,
        1024: 4
    };

    const drivers = {
        1: { name: 'Driver A', color: '#0d6efd', status: 'online', profilePercent: 90 },
        2: { name: 'Driver B', color: '#198754', status: 'busy', profilePercent: 60 },
        3: { name: 'Driver C', color: '#dc3545', status: 'offline', profilePercent: 45 },
        4: { name: 'Driver D', color: '#6f42c1', status: 'online', profilePercent: 78 },
        5: { name: 'Driver E', color: '#fd7e14', status: 'busy', profilePercent: 100 },
        6: { name: 'Driver F', color: '#20c997', status: 'online', profilePercent: 27 },
        7: { name: 'Driver G', color: '#0dcaf0', status: 'offline', profilePercent: 82 }
    };

    $(document).ready(function () {

        const jobs = {
            1021: { title: 'Job #1021', date: '2026-01-15', pickup: 'Chennai', drop: 'Salem' },
            1022: { title: 'Job #1022', date: '2026-01-16', pickup: 'Trichy', drop: 'Madurai' },
            1023: { title: 'Job #1023', date: '2026-01-16', pickup: 'Chennai', drop: 'Coimbatore' },
            1024: { title: 'Job #1024', date: '2026-01-20', pickup: 'Salem', drop: 'Trichy' },
            1025: { title: 'Job #1025', date: '2026-01-21', pickup: 'Erode', drop: 'Chennai' },
            1026: { title: 'Job #1026', date: '2026-01-23', pickup: 'Madurai', drop: 'Tirunelveli' },
            1027: { title: 'Job #1027', date: '2026-01-25', pickup: 'Vellore', drop: 'Salem' },
            1028: { title: 'Job #1028', date: '2026-01-26', pickup: 'Chennai', drop: 'Trichy' },
            1029: { title: 'Job #1029', date: '2026-01-28', pickup: 'Coimbatore', drop: 'Erode' },
            1030: { title: 'Job #1030', date: '2026-01-30', pickup: 'Salem', drop: 'Chennai' }
        };

        const scheduledTrips = [
            {
                id: 1,
                driverId: 1,
                from: 'Trichy',
                to: 'Chennai',
                date: '2026-01-20',
                amount: 4500
            },
            {
                id: 2,
                driverId: 2,
                from: 'Madurai',
                to: 'Salem',
                date: '2026-01-22',
                amount: 5200
            }
        ];

        const requestedJobs = [
            {
                id: 9001,
                driverId: 1,
                driverName: 'Driver A',
                from: 'Salem',
                to: 'Chennai',
                date: '2026-01-18'
            },
            {
                id: 9002,
                driverId: 4,
                driverName: 'Driver D',
                from: 'Trichy',
                to: 'Chennai',
                date: '2026-01-20'
            }
        ];


        function renderScheduledJobs(list = scheduledTrips) {

            $('#scheduledJobs').empty();

            if (!scheduledTrips || scheduledTrips.length === 0) {
                $('#scheduledJobs').html(
                    '<div class="alert alert-info text-center mb-0">No scheduled jobs</div>'
                );
                return;
            }

            let html = '';

            list.forEach(trip => {


                const driverName = drivers[trip.driverId]?.name || 'Driver';

                html += `
      <div class="container">
    <div class="row">
        <div class="col-12">

            <div class="card mb-2 shadow-sm border-0">
                <div class="card-body py-3 px-3">
                    <div class="d-flex align-items-center justify-content-between">

                        <!-- DRIVER -->
                        <div class="fw-semibold text-dark">
                            <i class="fa fa-user text-primary me-2"></i>
                            ${driverName}
                        </div>

                        <!-- FROM -->
                        <div class="text-dark">
                            <i class="fa fa-map-marker-alt text-success me-2"></i>
                            ${trip.from}
                        </div>

                        <!-- TO -->
                        <div class="text-dark">
                            <i class="fa fa-map-marker-alt text-danger me-2"></i>
                            ${trip.to}
                        </div>

                        <!-- DATE -->
                        <div class="text-dark">
                            <i class="fa fa-calendar-alt text-warning me-2"></i>
                            ${trip.date}
                        </div>

                        <!-- AMOUNT -->
                        <div class="fw-bold text-dark">
                            <i class="fa fa-rupee-sign text-info me-1"></i>
                            ${trip.amount}
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
`;
            });

            $('#scheduledJobs').html(html);
        }


        function getMatchingJobCount(driverReq) {

            return Object.values(jobs).filter(job => {

                const sameRoute =
                    job.pickup === driverReq.from &&
                    job.drop === driverReq.to;

                // OPTIONAL: date match (same day only)
                const sameDate = job.date === driverReq.date;

                return sameRoute && sameDate;

            }).length;
        }


        function renderRequestedJobs() {

            $('#requestedJobs').empty();

            if (!requestedJobs.length) {
                $('#requestedJobs').html(
                    '<div class="alert alert-info text-center mb-0">No driver requests</div>'
                );
                return;
            }

            let html = '';

            requestedJobs.forEach(req => {

                // 🔥 AUTO MATCH COUNT
                const matchCount = getMatchingJobCount(req);

                html += `
        <div class="card mb-2 shadow-sm border-0 driver-request cursor-pointer"
             data-request="${req.id}">

            <div class="card-body p-3">

                <!-- TOP ROW -->
                <div class="d-flex justify-content-between align-items-start">

                    <div>
                        <!-- DRIVER -->
                        <div class="fw-semibold mb-1">
                            <i class="fa fa-user text-primary me-2"></i>
                            ${req.driverName}
                        </div>

                        <!-- ROUTE -->
                        <div class="small">
                            <i class="fa fa-map-marker-alt text-success me-1"></i>
                            <strong>${req.from}</strong>

                            <i class="fa fa-arrow-right mx-2 text-muted"></i>

                            <i class="fa fa-map-marker-alt text-danger me-1"></i>
                            <strong>${req.to}</strong>
                        </div>

                        <!-- DATE -->
                        <div class="text-muted small mt-1">
                            <i class="fa fa-calendar-alt text-warning me-1"></i>
                            ${req.date}
                        </div>
                    </div>

                    <!-- 🔥 AUTO MATCH BADGE -->
                    <div>
                        ${matchCount > 0
                        ? `<span class="badge bg-success">
                                       ${matchCount} Matching Job${matchCount > 1 ? 's' : ''}
                                   </span>`
                        : `<span class="badge bg-secondary">
                                       No Match
                                   </span>`
                    }
                    </div>

                </div>

            </div>
        </div>`;
            });

            $('#requestedJobs').html(html);
        }




        function initScheduledLocationFilters() {

            const fromSet = new Set();
            const toSet = new Set();

            scheduledTrips.forEach(trip => {
                fromSet.add(trip.from);
                toSet.add(trip.to);
            });

            fromSet.forEach(loc => {
                $('#scheduledFrom').append(
                    `<option value="${loc}">${loc}</option>`
                );
            });

            toSet.forEach(loc => {
                $('#scheduledTo').append(
                    `<option value="${loc}">${loc}</option>`
                );
            });

            // Init select2
            $('.select2').select2({
                width: '100%',
                placeholder: 'Select',
                allowClear: true
            });
        }
        $('#searchScheduledJobs').on('click', function () {

            const fromVal = $('#scheduledFrom').val();
            const toVal = $('#scheduledTo').val();


            if (!fromVal && !toVal) {
                renderScheduledJobs();
                return;
            }

            const filtered = scheduledTrips.filter(trip => {

                if (fromVal && trip.from !== fromVal) return false;
                if (toVal && trip.to !== toVal) return false;

                return true;
            });

            renderScheduledJobs(filtered);
        });



        let selectedDriver = null;

        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            return date.toLocaleDateString('en-GB', options);
        }


        function renderJobLists() {
            $('#unassignedJobs').empty();
            $('#scheduledJobs').empty();
            $('#assignedJobs').empty();

            Object.entries(jobs).forEach(([jobId, job]) => {

                const assignedDriver = assignments[jobId];

                const jobHtml = `
                    <div class="card mb-2 shadow-sm border-0">
                        <div class="card-body p-3">
                
                          <div class="d-flex justify-content-between align-items-start mb-2">
                    
                    <!-- LEFT: JOB TITLE + ICONS -->
                <div class="d-flex align-items-center gap-2">
                
                    <!-- JOB ID -->
                    <h6 class="mb-0 fw-bold">${job.title}</h6>
                
                    <!-- EDIT -->
                    <a href="#"
                       class="text-warning jobEdit"
                       title="Edit Job"
                       data-job="${jobId}">
                        <i class="fa fa-edit text-info"></i>
                    </a>
                
                    <!-- VIEW -->
                    <a href="https://www.goride.run/booking-information/3d58d95584b7651a83185e3941471aac7d12eb1f7385e7a90dd70f7f978b4f00"
                       class="text-primary jobView"
                       title="View Job"
                       target="_blank">
                        <i class="fa fa-eye text-secondary"></i>
                    </a>
                
                    <!-- ✅ STATUS (LAST) -->
                 
                
                
                </div>
                 ${assignedDriver ? (() => {
                        const s = getJobStatusUI(jobStatuses[jobId] || 'assigned');
                        return `
                        <span class="badge bg-${s.class}"
                              style="font-size:11px;padding:3px 6px;">
                            ${s.text}
                        </span>
                    `;
                    })() : ''}
                
                    <!-- RIGHT: BIDS BUTTON (UNCHANGED) -->
                    ${!assignedDriver ? `
                   <button class="btn btn-sm btn-warning bidBtn text-dark px-2 position-relative"
                        data-job="${jobId}">
                    <i class="fa fa-gavel me-1 text-dark"></i>
                    Bids
                
                    <!-- COUNT BADGE -->
                    <span class="position-absolute top-0 start-100 translate-middle
                                 badge rounded-pill bg-danger">
                        4
                    </span>
                </button>
                ` : ''}
                
                </div>
                
                
                       <div class="mt-2 row small text-muted">
                
                    <!-- ROW 1: OWNER | DATE -->
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fa fa-user-tie text-dark " style="width:16px;"></i>
                <span class="d-inline-flex align-items-center gap-2">
                
                    <a href="#" class="owner-link fw-bold" data-owner="101">
                        Ramesh
                    </a>
                
                    <!-- Call icon -->
                    <a href="tel:9876543210"
                       class="call-con"
                       title="Call Owner">
                        <i class="fa fa-phone"></i>
                    </a>
                
                    <!-- WhatsApp icon -->
                    <a href="https://wa.me/919876543210"
                       target="_blank"
                       class="whatsapp-icon"
                       title="WhatsApp Owner">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                
                </span>
                
                        </div>
                    </div>
                
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-1 justify-content-end">
                            <i class="fa fa-calendar text-danger " style="width:16px;"></i>
                            <span>15 Jan 2026 · 10:30 AM</span>
                        </div>
                    </div>
                
                    <!-- ROW 2: FROM -->
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fa fa-map-marker-alt text-success " style="width:16px;"></i>
                            <span> <strong>Chennai</strong></span>
                        </div>
                    </div>
                
                    <!-- ROW 3: TO -->
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fa fa-map-marker-alt text-danger " style="width:16px;"></i>
                            <span><strong>Salem</strong></span>
                        </div>
                    </div>
                
                    <!-- ROW 4: TRIP | CAR + PASSENGERS + LUGGAGE -->
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between gap-4 mb-1">
                
                            <!-- LEFT : TRIP -->
                            <div class="d-flex align-items-center">
                                <i class="fa fa-exchange-alt text-info " style="width:16px;"></i>
                                <span><strong>One Way</strong></span>
                            </div>
                
                            <!-- RIGHT : CAR | PASSENGERS | LUGGAGE -->
                            <div class="d-flex align-items-center gap-2">
                
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-car text-secondary me-1" style="width:16px;"></i>
                                    <strong>Sedan</strong>
                                </div>
                
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-users text-success me-1" style="width:16px;"></i>
                                    <strong>4</strong>
                                </div>
                
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-suitcase text-warning me-1" style="width:16px;"></i>
                                    <strong>2</strong>
                                </div>
                
                            </div>
                        </div>
                    </div>
                
                    <!-- ROW 5: DRIVER -->
                ${assignedDriver ? `
                <div class="col-6">
                    <div class="d-flex align-items-center mb-1">
                        <i class="fa fa-user text-primary" style="width:16px;"></i>
                        <span class="d-inline-flex align-items-center gap-1">
                            <a href="#"
                               class="driver-link fw-bold"
                               data-driver="${assignedDriver}">
                                ${drivers[assignedDriver].name}
                            </a>
                            <i class="fa fa-times-circle text-danger cursor-pointer remove-driver d-none"
                               title="Remove Driver"
                               data-driver="${assignedDriver}"
                               data-job="${jobId}">
                            </i>
                        </span>
                    </div>
                </div>
                ` : `
                <div class="col-6">
                    <span class="badge bg-secondary cursor-pointer unassigned-driver-btn"
                          data-job="${jobId}">
                        Unassigned
                    </span>
                </div>
                
                `}
                
                    <div class="col-6">
                        <div class="d-flex align-items-center justify-content-end mb-1">
                            <i class="fa fa-clock me-1 text-danger" ></i>
                            <span >
                                  15 Jan 2026 · 10:30 AM
                                
                            </span>
                        </div>
                    </div>
                
                    <!-- ROW 6: CALL | WHATSAPP | BOOKED TIME -->
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between pt-1 ">
                
                           <div class="text-end">
                    <div class="small text-muted">Base Fare: ₹4525</div>
                    <div class="small text-muted">Toll: ₹495</div>
                 
                    <div class="fw-bold text-dark" style=""font-size:14px;>₹5020</div>
                </div>
                
                
                           <span class="text-muted cursor-pointer cancelJobBtn"
                      style="font-size:14px"
                      data-job="1021">
                    <i class="fa fa-times-circle text-danger"></i>
                    Cancel
                </span>
                
                
                        </div>
                    </div>
                
                </div>
                
                        </div>
                    </div>
                    `;

                if (assignedDriver) {

                    $('#assignedJobs').append(jobHtml);
                } else {

                    $('#unassignedJobs').append(jobHtml);
                }

            })


            if ($('#scheduledJobs').is(':empty')) {
                $('#scheduledJobs').html('<div class="alert alert-info text-center mb-0">No scheduled jobs</div>');
            }
            if ($('#unassignedJobs').is(':empty')) {
                $('#unassignedJobs').html('<div class="alert alert-success text-center mb-0">All jobs assigned 2!</div>');
            }

            $('#assignedCount').text($('#assignedJobs .card').length);
            $('#unassignedCount').text($('#unassignedJobs .card').length);

        }

        function populateDriverDropdown() {
            const select = $('#driver_id');
            select.empty();
            select.append('<option value="">-- Select Driver --</option>');

            Object.entries(drivers).forEach(([id, driver]) => {
                select.append(`
                <option value="${id}">
                    ${driver.name} (${driver.status})
                </option>
            `);
            });
        }

        // const calendar = new FullCalendar.Calendar(
        //     document.getElementById('driverCalendar'),
        //     {
        //         initialView: 'dayGridMonth',
        //         initialDate: '2026-01-23',
        //         height: '100%',
        //         fixedWeekCount: false,
        //         headerToolbar: {
        //             left: 'prev,next today',
        //             center: 'title',
        //             right: ''
        //         },
        //         events: [],
        //         eventDidMount(info) {
        //             info.el.style.fontSize = '12px';
        //             info.el.style.borderRadius = '6px';
        //         }
        //     }
        // );

        /* ===============================
           REFRESH CALENDAR
        =============================== */
        // function refreshCalendar(filterDriverId = null) {
        //     calendar.removeAllEvents();

        //     Object.entries(assignments).forEach(([jobId, driverId]) => {
        //         if (!jobs[jobId] || !drivers[driverId]) {
        //             console.warn(`Missing data for job ${jobId} or driver ${driverId}`);
        //             return;
        //         }

        //         if (filterDriverId && driverId != filterDriverId) return;

        //         calendar.addEvent({
        //             title: `${drivers[driverId].name} – ${jobs[jobId].title}`,
        //             start: jobs[jobId].date,
        //             backgroundColor: drivers[driverId].color,
        //             borderColor: drivers[driverId].color,
        //             extendedProps: {
        //                 jobId: jobId,
        //                 driverId: driverId
        //             }
        //         });
        //     });
        // }

        /* ===============================
           INITIAL RENDER
        =============================== */
        // calendar.render();
        // renderDriversList();
        populateDriverDropdown();
        // renderJobLists();
        renderScheduledJobs();
        initScheduledLocationFilters();
        renderRequestedJobs();

        /* ===============================
           TAB SHOW -> UPDATE CALENDAR SIZE
        =============================== */

        /* ===============================
           WEEK / MONTH VIEW BUTTONS
        =============================== */
        // $('button[data-view]').on('click', function () {
        //     $('button[data-view]').removeClass('active');
        //     $(this).addClass('active');

        //     const view = $(this).data('view');
        //     calendar.changeView(view === 'week' ? 'timeGridWeek' : 'dayGridMonth');
        // });

        /* ===============================
           DRIVER CLICK -> FILTER CALENDAR
        =============================== */
        // $(document).on('click', '.driver-item', function (e) {
        //     e.preventDefault();

        //     $('.driver-item').removeClass('active');
        //     $(this).addClass('active');

        //     selectedDriver = $(this).data('driver');
        //     refreshCalendar(selectedDriver);
        // });

        /* ===============================
           SHOW ALL DRIVERS
        =============================== */
        // $('#showAllDrivers').on('click', function () {
        //     selectedDriver = null;
        //     $('.driver-item').removeClass('active');
        //     refreshCalendar();
        // });

        /* ===============================
           ASSIGN BUTTON CLICK
        =============================== */
        $(document).on('click', '.assignBtn', function () {
            const jobId = $(this).data('job');
            const job = jobs[jobId];

            // Set job ID
            $('#job_id').val(jobId);
            $('#driver_id').val('');

            // Reset checkboxes
            $('#notify_whatsapp, #notify_sms').prop('checked', true);

            // Show job details
            $('#jobDetailsSection').html(`
            <div class="alert alert-light border-start border-4 border-primary">
                <h6 class="fw-bold mb-2">${job.title}</h6>
                <small class="text-muted">
                    <i class="fa fa-calendar me-1"></i>${formatDate(job.date)}<br>
                    <i class="fa fa-map-marker-alt text-success me-1"></i>From: <strong>${job.pickup}</strong><br>
                    <i class="fa fa-map-marker-alt text-danger me-1"></i>To: <strong>${job.drop}</strong>
                </small>
            </div>
        `);

            $('#assignDriverModal').modal('show');
        });


        /* ===============================
           ASSIGN DRIVER ACTION
        =============================== */
        $('#assignBtn').on('click', function () {
            const jobId = $('#job_id').val();
            const driverId = $('#driver_id').val();
            const notifyWhatsapp = $('#notify_whatsapp').is(':checked');
            const notifySms = $('#notify_sms').is(':checked');

            if (!driverId) {
                alert('Please select a driver');
                return;
            }

            // Store the assignment
            assignments[jobId] = driverId;

            console.log('Assignment created:', {
                jobId,
                driverId,
                notifyWhatsapp,
                notifySms,
                assignments
            });

            // Show notification
            let notifyMsg = [];
            if (notifyWhatsapp) notifyMsg.push('WhatsApp');
            if (notifySms) notifyMsg.push('SMS');

            if (notifyMsg.length > 0) {
                console.log(`Notifications sent via: ${notifyMsg.join(' & ')}`);
                // You can add actual notification logic here
            }

            // Update job lists
            renderJobLists();

            // Close modal
            $('#assignDriverModal').modal('hide');

            // Switch to Drivers tab and update calendar
            const driverTabBtn = document.querySelector('button[data-bs-target="#driversTab"]');
            const tab = new bootstrap.Tab(driverTabBtn);

            // Listen for tab shown event
            $(driverTabBtn).one('shown.bs.tab', function () {
                // Reset filter
                selectedDriver = null;
                $('.driver-item').removeClass('active');

                // Small delay to ensure tab is fully rendered
                setTimeout(() => {
                    refreshCalendar();
                    calendar.updateSize();
                }, 150);
            });

            // Show the tab
            tab.show();
        });

        /* ===============================
           JOB SEARCH FUNCTIONALITY
        =============================== */
        $('#jobSearch').on('keyup', function () {
            const searchTerm = $(this).val().toLowerCase();

            // Search in both tabs
            $('#unassignedJobs .card, #scheduledJobs .card').each(function () {
                const jobText = $(this).text().toLowerCase();
                $(this).toggle(jobText.includes(searchTerm));
            });

            // Show "No results" message for unassigned jobs
            const unassignedVisible = $('#unassignedJobs .card:visible').length;
            if (searchTerm && unassignedVisible === 0) {
                if ($('#unassignedJobs .no-results').length === 0) {
                    $('#unassignedJobs').append('<div class="alert alert-warning text-center mb-0 no-results">No jobs found</div>');
                }
            } else {
                $('#unassignedJobs .no-results').remove();
            }

            // Show "No results" message for scheduled jobs
            const scheduledVisible = $('#scheduledJobs .card:visible').length;
            const scheduledCards = $('#scheduledJobs .card').length;

            if (searchTerm && scheduledVisible === 0 && scheduledCards > 0) {
                if ($('#scheduledJobs .no-results').length === 0) {
                    $('#scheduledJobs .alert-info').hide();
                    $('#scheduledJobs').append('<div class="alert alert-warning text-center mb-0 no-results">No jobs found</div>');
                }
            } else {
                $('#scheduledJobs .no-results').remove();
                if (scheduledCards === 0) {
                    $('#scheduledJobs .alert-info').show();
                }
            }
        });

        /* ===============================
           DRIVER SEARCH FUNCTIONALITY
        =============================== */
        // $('#driverSearch').on('keyup', function () {
        //     const searchTerm = $(this).val().toLowerCase();

        //     $('.driver-item').each(function () {
        //         const driverText = $(this).text().toLowerCase();
        //         $(this).toggle(driverText.includes(searchTerm));
        //     });
        // });

        /* ===============================
           DRIVER STATUS FILTER
        =============================== */
        $('#driverStatus').on('change', function () {
            const status = $(this).val().toLowerCase();

            $('.driver-item').each(function () {
                const text = $(this).text().toLowerCase();
                $(this).toggle(!status || text.includes(status));
            });
        });

    });

    // #Suriya

    $(document).ready(function () {

        const firebaseConfig = {
            apiKey: "AIzaSyCiRKGU2xZyZNx5-ZwweLd5cPokxJjxKzw",
            authDomain: "goride-947ed.firebaseapp.com",
            projectId: "goride-947ed",
        };

        firebase.initializeApp(firebaseConfig);
        const db = firebase.firestore();

        let unassignedJobs = {};
        let websiteJobs = {};
        let assignedJobs = {};
        let cancelledJobs = {};

        let rejectData = {};

        let selectedDriver = null;
        let calendarData = [];

        let driversAll = {};
        let currentJobId = null;
        let selectedFilterType = null;
        let selectedStartDate = null;
        let selectedEndDate = null;

        let driversMap;
        let markerClusterGroup;
        let driverMarkers = {};
        let isLoadingDrivers = false;
        
        let percentageMin = 0;
        let percentageMax = 100;

        function initDriversMap() {

            $('#mapLoader').removeClass('hide');

            driversMap = L.map('driversMap', {
                zoomControl: true
            }).setView([13.0827, 80.2707], 10);

            L.tileLayer(
                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                {
                    attribution: '&copy; OpenStreetMap contributors',
                    maxZoom: 19
                }
            ).addTo(driversMap);

            markerClusterGroup = L.markerClusterGroup();
            driversMap.addLayer(markerClusterGroup);

            // 🔥 Directly load drivers (no map load event)
            loadDriversOnMap();
        }

        initDriversMap();

        function loadDriversOnMap() {

            if (isLoadingDrivers) return; // prevent double call
            isLoadingDrivers = true;

            $('#mapLoader').removeClass('hide');

            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-get-driver-list",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                success: function (res) {

                    if (!res.status || !res.data) {
                        $('#mapLoader').addClass('hide');
                        isLoadingDrivers = false;
                        return;
                    }

                    res.data.forEach(driver => {

                        if (!driver.s_lat || !driver.s_lang) return;

                        const lat = parseFloat(driver.s_lat);
                        const lng = parseFloat(driver.s_lang);

                        if (!driverMarkers[driver.id]) {

                            // Create pulsing icon
                            const pulseIcon = L.divIcon({
                                className: '',
                                html: `<div class="pulse-marker"></div>`,
                                iconSize: [20, 20],
                                iconAnchor: [10, 10]
                            });

                            const marker = L.marker([lat, lng], { icon: pulseIcon });

                            marker.bindPopup(`
                                <div style="min-width:200px">
                                    <strong>${driver.name}</strong><br>
                                    <small>${driver.mobile ?? ''}</small><br>
                                    <span class="badge bg-success mt-1">
                                        ${driver.vehicle_type ?? 'Driver'}
                                    </span>
                                </div>
                            `);

                            markerClusterGroup.addLayer(marker);
                            driverMarkers[driver.id] = marker;

                        } else {
                            // Update position smoothly
                            driverMarkers[driver.id].setLatLng([lat, lng]);
                        }

                    });

                    $('#mapLoader').addClass('hide');
                    isLoadingDrivers = false;
                },

                error: function () {

                    console.log('Driver fetch failed. Retrying...');

                    // Retry automatically after error
                    setTimeout(function () {
                        isLoadingDrivers = false;
                        loadDriversOnMap();
                    }, 3000);
                }
            });
        }

        function toast(icon, message) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'center',
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

        function formatPickupDate(dateStr) {
            const date = new Date(dateStr.replace(' ', 'T'));

            return new Intl.DateTimeFormat('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            }).format(date).replace(',', ' ·');
        }

        function formatDateSimple(dateStr) {
            const d = new Date(dateStr);
            return d.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }

        function normalizeJob(job, source) {

            let userDetails = {};
            try {
                userDetails = job.user_details ? JSON.parse(job.user_details) : {};
            } catch (e) {
                userDetails = {};
            }

            let assignedDriver = null;

            if (job.job_status === 'accept' && job.bids_details) {
                Object.entries(job.bids_details).forEach(([driverId, bid]) => {
                    if (bid.status === 'accept') {
                        assignedDriver = {
                            driver_id: Number(driverId),
                            name: bid.b_name || '-',
                            amount: bid.amount || 0,
                            rating: bid.b_rating || ''
                        };
                    }
                });
            }

            return {
                id: job.id,
                title: job.job_no,
                job_no: job.job_no,
                job_type: job.job_type,
                from_place: job.from_place,
                to_place: job.to_place,
                pickup_date: job.pickup_date,
                day: job.day || null,
                distance: job.distance || null,

                base_fare: job.base_fare,
                toll_fare: job.toll_fare,
                fare: job.fare,

                pass_count: userDetails.pass_count || job.pass_count || 1,
                car_type: userDetails.cab_type || '',
                luggage: userDetails.lugg_count || 0,

                mobile: job.mobile,
                name: userDetails.name || job.name || '-',
                email: userDetails.email || job.email || '',
                user_id: job.user_id || 0,

                bid_count: job.bid_count || 0,
                job_status: job.job_status,
                preview_hash: job.preview_hash,

                assigned_driver: assignedDriver,

                source: source
            };
        }

        function getUnassignedActionButton(job) {

            // WEBSITE BOOKING → POST JOB
            if (job.source === 'website' && job.job_status === 'created') {
                return `
                <button class="btn btn-sm btn-primary postJobBtn px-2"
                        data-job='${JSON.stringify(job)}'>
                    <i class="fa fa-paper-plane me-1"></i>
                    Post Job
                </button>`;
            }

            // POSTED JOB → BIDS
            if (job.source === 'posted' && ['created', 'bidding'].includes(job.job_status)) {
                return `
                <button class="btn btn-sm btn-warning bidBtn text-dark px-2 position-relative"
                        data-job="${job.id}">
                    <i class="fa fa-gavel me-1 text-dark"></i>
                    Bids
                    ${job.bid_count > 0 ? `
                    <span class="position-absolute top-0 start-100 translate-middle
                                 badge rounded-pill bg-danger">
                        ${job.bid_count}
                    </span>` : ''}
                </button>`;
            }

            return '';
        }

        function renderJobCard(job, jobId) {

            const domain = "<?php echo APP_DOMAIN; ?>";

            return `
    <div class="card mb-2 shadow-sm border-0">
      <div class="card-body p-3">
    
        <div class="d-flex justify-content-between align-items-start mb-2">
    
          <div class="d-flex align-items-center gap-2">
          
          
           <!-- CANCEL BUTTON - MOVED NEXT TO JOB ID -->
            ${!['cancelled', 'expired', 'deleted'].includes(job.job_status) ? `
                <span class="text-muted cursor-pointer cancelJobBtn" 
                      style="font-size:14px" 
                      data-jobtype="${job.global_type}" 
                      data-user-id="${job.user_id}" 
                      data-job="${job.id}" 
                      data-job-no="${job.job_no}">
                    <i class="fa fa-times-circle text-danger"></i>
                </span>
            ` : ''}
                <span class="text-muted mx-1">|</span>
            <h6 class="mb-0 fw-bold">${job.title}</h6>
    
            <a href="#" class="text-warning jobEdit d-none" data-job="${jobId}">
              <i class="fa fa-edit text-info"></i>
            </a>
    
            <a href="${domain}/booking-information/${job.preview_hash}" class="text-primary jobView" target="_blank">
              <i class="fa fa-eye text-secondary"></i>
            </a>
            
           
    
          </div>
    
          ${getUnassignedActionButton(job)}
    
        </div>
    
        <div class="mt-2 row small text-muted">
    
          <div class="col-6">
            <i class="fa fa-user-tie text-dark"></i>
            <span class="d-inline-flex align-items-center gap-2">
                <a href="javascript:void(0)"  fw-bold" data-owner="${job.user_id}" data-jobtype="${job.global_type}">
                    ${job.name}
                </a>
                <a href="tel:${job.mobile}" class="call-con" title="Call Owner">
                    <i class="fa fa-phone"></i>
                </a>
                <a href="https://wa.me/${job.mobile}" target="_blank" class="whatsapp-icon" title="WhatsApp Owner">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </span>
          </div>
          
           <div class="col-6">
                <div class="d-flex align-items-center mb-1 justify-content-end">
                    <i class="fa fa-calendar text-danger " style="width:16px;"></i>
                    <span>${formatPickupDate(job.pickup_date)}</span>
                </div>
            </div>
          
            <div class="col-12">
                <div class="d-flex align-items-center mb-1">
                    <i class="fa fa-map-marker-alt text-success " style="width:16px;"></i>
                    <span> <strong>${job.from_place}</strong></span>
                </div>
            </div>
            
            <div class="col-12">
                <div class="d-flex align-items-center mb-1">
                    <i class="fa fa-map-marker-alt text-danger " style="width:16px;"></i>
                    <span><strong>${job.to_place}</strong></span>
                </div>
            </div>
            
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between gap-4 mb-1">
        
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa fa-exchange-alt text-info" title="Job Type"></i>
                        <span><strong>${job.job_type}</strong></span>
                        ${job.job_type === 'roundtrip' ? `
                            <i class="fa fa-calendar-days text-secondary" title="Day"></i>
                            <span><strong>${job.day}</strong></span>
                        ` : ''}
                        <i class="fa fa-road text-info" title="distance"></i>
                        <span><strong>${job.distance}</strong></span>
                    </div>
        
                    <div class="d-flex align-items-center gap-2">
        
                        <div class="d-flex align-items-center">
                            <i class="fa fa-car text-secondary me-1" style="width:16px;"></i>
                            <strong>${job.car_type}</strong>
                        </div>
        
                        <div class="d-flex align-items-center">
                            <i class="fa fa-users text-success me-1" style="width:16px;"></i>
                            <strong>${job.pass_count}</strong>
                        </div>
        
                        <div class="d-flex align-items-center">
                            <i class="fa fa-suitcase text-warning me-1" style="width:16px;"></i>
                            <strong>${job.luggage}</strong>
                        </div>
        
                    </div>
                </div>
            </div>
            
            ${job.assigned_driver ? `
                <div class="col-12">
                    <div class="d-flex align-items-center mb-1">
                        <i class="fa fa-user text-primary" style="width:16px;"></i>
                        <span class="d-inline-flex align-items-center gap-1">
                            <a href="#" class="driver-link fw-bold" data-driver="${job.assigned_driver.driver_id}">
                                ${job.assigned_driver.name}
                            </a>
                            <i class="fa fa-times-circle text-danger cursor-pointer remove-driver d-none" title="Remove Driver" data-driver="1" data-job="1021">
                            </i>
                        </span>
                    </div>
                </div>
                ` : ''}
            
            ${(
                    job.source &&
                    job.source.toLowerCase() === 'posted' &&
                    ['created', 'bidding'].includes(job.job_status)
                ) ? `
                <div class="col-12">
                    <span class="badge bg-secondary cursor-pointer unassigned-driver-btn" data-job="${job.id}">
                        Make a Bid
                    </span>
                </div>
            ` : ''}
                
               <div class="col-6">
    <p class="mb-0"> <i class="fa fa-calendar text-danger me-2"></i>20 Feb 2026 10.29 p.m</p>
    <p class="mb-0">
       <a href="javascript:void(0)" class="${job.source != 'website' ? 'owner-link' : ''} fw-bold" data-owner="${job.user_id}" data-jobtype="${job.global_type}">
                  <i class="fa fa-user-tie text-info"></i> Booked By ${job.name}
                </a>
    </p>
</div>
            <div class="col-6">
                <div class="d-flex align-items-center justify-content-between">
                   <div class="text-end d-flex gap-2 align-items-center">
                        <div class=" text-muted">Base ₹${job.base_fare}</div>
                        <div class=" text-muted">Toll: ₹${job.toll_fare}</div>
                     
                        <div class="fw-bold text-dark" style="font-size:14px;" >₹${job.fare}</div>
                    </div>
                    <!-- REMOVED CANCEL BUTTON FROM HERE -->
                </div>
            </div>
           
    
        </div>
      </div>
    </div>`;
        }

        function loadWebsiteBookingsFinal() {

            $('#websiteJobs').empty();
            $('#websiteLoader').show();

            websiteJobs = {};

            $.ajax({
                url: origin + "/ajax/service/leadService.php",
                type: "POST",
                dataType: "json",
                data: {
                    method: 'websiteBookingList',
                    startDate: selectedStartDate,
                    endDate: selectedEndDate,
                    dateFilter: selectedFilterType === 'booked' ? 1 : '',
                    filterType: selectedFilterType
                },
                success: function (res) {

                    if (!res.result) return;

                    res.result.forEach(j => {
                        const job = normalizeJob(j, 'website');
                        websiteJobs[job.id] = job;
                    });

                    render();
                },
                complete: function () {
                    $('#websiteLoader').hide();
                }
            });

            function render() {

                $('#websiteJobs').empty();

                Object.entries(websiteJobs).forEach(([id, job]) => {
                    $('#websiteJobs').append(renderJobCard(job, id));
                });

                $('#websiteCount').text($('#websiteJobs .card').length);
            }
        }

        function loadUnassignedJobsFinal() {

            $('#unassignedJobs').empty();
            $('#unassignedLoader').show();

            unassignedJobs = {};

            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-job-list",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                data: {
                    method: 'jobList_New',
                    jobStatus: 'not_complete',
                    startDate: selectedStartDate,
                    endDate: selectedEndDate,
                    dateFilter: selectedFilterType ? 1 : '',
                    filterType: selectedFilterType
                },
                success: function (res) {

                    if (!res.result) return;

                    res.result.forEach(j => {
                        const job = normalizeJob(j, 'posted');
                        unassignedJobs[job.id] = job;
                    });

                    render();
                },
                complete: function () {
                    $('#unassignedLoader').hide();
                }
            });

            function render() {
                $('#unassignedJobs').empty();

                Object.entries(unassignedJobs).forEach(([id, job]) => {
                    $('#unassignedJobs').append(renderJobCard(job, id));
                });

                $('#unassignedCount').text($('#unassignedJobs .card').length);
            }
        }

        function loadAssignedJobsFinal() {
            $('#assignedJobs').empty();
            $('#assignedLoader').show();

            assignedJobs = {};

            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-job-list",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                data: {
                    method: 'jobList_New',
                    jobStatus: 'accepted',
                    startDate: selectedStartDate,
                    endDate: selectedEndDate,
                    dateFilter: selectedFilterType ? 1 : '',
                    filterType: selectedFilterType
                },
                success: function (res) {
                    res.result.forEach(j => {
                        const job = normalizeJob(j, 'posted');
                        assignedJobs[job.id] = job;
                    });
                    render();
                },
                complete: function () {
                    $('#assignedLoader').hide();
                }
            });

            function render() {
                $('#assignedJobs').empty();
                Object.entries(assignedJobs).forEach(([id, job]) => {
                    $('#assignedJobs').append(renderJobCard(job, id));
                });
                $('#assignedCount').text($('#assignedJobs .card').length);
            }
        }

        function loadCancelledJobsFinal() {

            $('#cancelledJobsList').empty();
            $('#cancelledLoader').show();

            cancelledJobs = {};

            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-job-list",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                data: {
                    method: 'jobList_New',
                    jobStatus: 'cancelled',
                    startDate: selectedStartDate,
                    endDate: selectedEndDate,
                    dateFilter: selectedFilterType ? 1 : '',
                    filterType: selectedFilterType
                },
                success: function (res) {

                    if (!res.result) return;

                    res.result.forEach(j => {
                        const job = normalizeJob(j, 'posted');
                        cancelledJobs[job.id] = job;
                    });

                    render();
                },
                complete: function () {
                    $('#cancelledLoader').hide();
                }
            });

            function render() {

                $('#cancelledJobsList').empty();

                Object.entries(cancelledJobs).forEach(([id, job]) => {
                    $('#cancelledJobsList').append(renderJobCard(job, id));
                });
            }
        }

        $('button[data-bs-target="#unassigned"]').on('shown.bs.tab', function () {
            loadUnassignedJobsFinal();
        });

        $('button[data-bs-target="#assigned"]').on('shown.bs.tab', function () {
            loadAssignedJobsFinal();
        });

        $('button[data-bs-target="#websiteBookings"]').on('shown.bs.tab', function () {
            loadWebsiteBookingsFinal();
        });

        $('button[data-bs-target="#cancelledJobs"]').on('shown.bs.tab', function () {
            loadCancelledJobsFinal();
        });

        function sendOtp(jobId, mobile, btn) {

            const $btn = $(btn);

            $btn
                .prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin me-1"></i> Sending...');

            $.ajax({
                url: API_DOMAIN_2 + 'v1-cus/loginOTP',
                type: 'POST',
                dataType: 'json',
                data: {
                    mobile: '91' + mobile,
                    dialCode: '+91',
                    deviceType: 'MOBILE',
                    isResend: false
                },
                success: function (res) {

                    if (res.status == 'success') {
                        $('#otpSection').removeClass('d-none');
                        $('#otpMsg').text('OTP sent successfully').addClass('text-success');

                        window.enc = res.data.enc;

                        toast('success', res.message);

                    } else {
                        $('#otpMsg').text(res.message || 'Failed to send OTP')
                            .addClass('text-danger');
                    }
                },
                error: function () {
                    toast('error', res.message);
                    // alert('OTP service unavailable');
                },
                complete: function () {
                    $btn
                        .prop('disabled', false)
                        .html('<i class="fas fa-key me-1"></i> Send OTP');
                }
            });
        }

        function postJob(userId, jobId) {

            $.ajax({
                url: API_DOMAIN_2 + 'v1-cus/postJob',
                type: 'POST',
                dataType: 'json',
                data: {
                    user_id: userId,
                    job_id: jobId
                },
                beforeSend: function () {
                    Swal.fire({
                        title: 'Posting Job...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (res) {

                    if (res.status === true) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Job Posted',
                            text: `Job ${res.data} posted successfully`
                        }).then(() => {
                            location.reload();
                        });

                    } else {

                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: res.message || 'Job posting failed'
                        });
                    }
                },
                error: function (xhr) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Server error'
                    });
                }
            });
        }

        function openPostJobPreview(data) {
            // Hide other panels
            $('#mapCard, #leftPanel, #incomingBidsPanel, .driverDetailsCard, #ownerDetailsCard')
                .addClass('d-none');

            // Show job details panel
            $('#jobDetailsPanel').removeClass('d-none');

            $('#jobDetailsContent').html(`
            <div class="container-fluid">
            
              <!-- CUSTOMER -->
              <div class="border p-3 mb-3">
                <h6 class="text-muted mb-3">Customer Details</h6>
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">Name</label>
                    <input class="form-control" id="pv_name" value="${data.name ?? ''}" readonly>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input class="form-control" id="pv_email" value="${data.email ?? ''}" readonly>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Mobile</label>
                    <input class="form-control" id="pv_mobile" value="${data.mobile ?? ''}" readonly>
                  </div>
                </div>
              </div>
            
              <!-- JOB -->
              <div class="border p-3 mb-3">
                <h6 class="text-muted mb-3">Job Details</h6>
                <input type="hidden" id="pv_job_id" value="${data.id}" readonly>
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">Job No</label>
                    <input class="form-control" id="pv_job_no" value="${data.job_no}" readonly>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Job Type</label>
                    <input class="form-control" id="pv_job_type" value="${data.job_type}" readonly>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Car Type</label>
                    <input class="form-control" id="pv_car_type" value="${data.car_type}" readonly>
                  </div>
                </div>
              </div>
            
              <!-- ROUTE -->
              <div class="border p-3 mb-3">
                <h6 class="text-muted mb-3">Route Details</h6>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">From</label>
                    <input class="form-control" id="pv_from" value="${data.from_place}" readonly>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">To</label>
                    <input class="form-control" id="pv_to" value="${data.to_place}" readonly>
                  </div>
                </div>
              </div>
            
              <!-- SCHEDULE -->
              <div class="border p-3 mb-3">
                <h6 class="text-muted mb-3">Schedule</h6>
                <input class="form-control" id="pv_pickup_time"
                       value="${data.pickup_date}" readonly>
              </div>
            
              <!-- FARE -->
              <div class="border p-3 mb-3">
                <h6 class="text-muted mb-3">Fare Breakdown</h6>
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">Base Fare</label>
                    <input class="form-control" id="pv_base_fare" value="${data.base_fare}" readonly>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Toll Fare</label>
                    <input class="form-control" id="pv_toll_fare" value="${data.toll_fare}" readonly>
                  </div>
                  <div class="col-md-4 d-flex align-items-end">
                    <h6 class="text-success fw-bold">
                      Total: ₹<span id="pv_total_fare">${data.fare}</span>
                    </h6>
                  </div>
                </div>
              </div>
            
              <!-- OTP -->
              <div class="border p-3 mb-3 d-none" id="otpSection">
                <h6 class="text-muted mb-3">OTP Verification</h6>
                <div class="row g-2">
                  <div class="col-md-6">
                    <input class="form-control" id="otpInput" maxlength="6"
                           placeholder="Enter OTP">
                  </div>
                  <div class="col-md-6">
                    <button class="btn btn-success w-100" id="verifyOtpBtn">
                      Verify OTP
                    </button>
                  </div>
                </div>
                <small id="otpMsg" class="text-muted"></small>
              </div>
            
              <!-- ACTIONS -->
              <div class="text-end">
                <button class="btn btn-outline-primary sendOtpBtn"
                        data-job-id="${data.id}"
                        data-mobile="${data.mobile}">
                  Send OTP
                </button>
              </div>
            
            </div>
            `);
        }

        $(document).on('click', '#verifyOtpBtn', function () {

            const otp = $('#otpInput').val().trim();

            if (!otp || otp.length !== 6) {
                $('#otpMsg')
                    .text('Please enter a valid 6-digit OTP')
                    .removeClass('text-success')
                    .addClass('text-danger');
                return;
            }

            $.ajax({
                url: API_DOMAIN_2 + 'v1-cus/verify-loginOTP-web',
                type: 'POST',
                dataType: 'json',
                data: {
                    enc: window.enc,
                    otp: otp,
                    name: $('#pv_name').val(),
                    email: $('#pv_email').val(),
                    mobile: $('#pv_mobile').val(),
                    platform_type: 'android',
                    fcm_token: null
                },
                success: function (res) {

                    if (res.status === 'success') {

                        $('#otpMsg')
                            .text('OTP verified successfully')
                            .removeClass('text-danger')
                            .addClass('text-success');

                        $('#otpInput, #verifyOtpBtn').prop('disabled', true);

                        const jobNo = $('#pv_job_no').val().trim();
                        const jobId = $('#pv_job_id').val();
                        const userId = res.data.user_id;

                        Swal.fire({
                            title: 'Post Job Confirmation',
                            text: `Are you sure you want to post this job (${jobNo})?`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, Post Job',
                            cancelButtonText: 'Cancel',
                            reverseButtons: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                postJob(userId, jobId);
                            }
                        });

                    } else {
                        $('#otpMsg')
                            .text(res.message || 'Invalid OTP')
                            .addClass('text-danger');
                    }
                },
                error: function () {
                    $('#otpMsg')
                        .text('Verification failed')
                        .addClass('text-danger');
                }
            });
        });

        $(document).on('click', '.postJobBtn', function () {
            const job = $(this).data('job');

            openPostJobPreview(job);
        });

        $(document).on('click', '.sendOtpBtn', function () {
            const jobId = $(this).data('job-id');
            const mobile = $(this).data('mobile');
            sendOtp(jobId, mobile, this);
        });

        function openIncomingBids(job) {

            // Panels visibility (UNCHANGED LOGIC)
            $('#mapCard, #jobDetailsPanel, #driverListPanel').addClass('d-none');
            $('#leftPanel').removeClass('d-none');
            $('#incomingBidsPanel').removeClass('d-none');
            $('.driverDetailsCard, #ownerDetailsCard').addClass('d-none');

            const jobNo = job.job_no;

            const biddersWrap = $('#incomingBidsPanel .bidders-wrap');
            const bestPriceEl = $('#incomingBidsPanel .bid-header .text-info');

            biddersWrap.html('<p class="text-muted small">Waiting for bids…</p>');
            bestPriceEl.hide();

            db.collection('<?= rtrim(FIREBASE_COLLECTION, '/') ?>')
                .doc(jobNo)
                .onSnapshot(doc => {

                    biddersWrap.empty();

                    if (!doc.exists) {
                        biddersWrap.html('<p class="text-danger">Job not found</p>');
                        return;
                    }

                    const data = doc.data();
                    const bids = data.bids_details || {};

                    if (Object.keys(bids).length === 0) {
                        biddersWrap.html('<p class="text-muted">No bids yet</p>');
                        bestPriceEl.hide();
                        return;
                    }

                    /* ======================
                       FIND BEST PRICE
                    ====================== */
                    let lowestPrice = null;
                    Object.values(bids).forEach(b => {
                        const amt = Number(b.amount);
                        if (!isNaN(amt)) {
                            lowestPrice = lowestPrice === null ? amt : Math.min(lowestPrice, amt);
                        }
                    });

                    if (lowestPrice !== null) {
                        bestPriceEl.html(
                            `<i class="fa fa-coins me-1"></i> Best Price: ₹${lowestPrice}`
                        ).show();
                    }

                    const isJobAccepted = data.job_status === 'accept';

                    let acceptedBidderId = null;
                    if (isJobAccepted) {
                        Object.entries(bids).forEach(([bidderId, bid]) => {
                            if (bid.status === 'accept' || bid.status === 'accepted') {
                                acceptedBidderId = bidderId;
                            }
                        });
                    }

                    /* ======================
                       RENDER BIDS (NEW UI)
                    ====================== */
                    Object.entries(bids).reverse().forEach(([bidderId, bid]) => {

                        if (isJobAccepted && bidderId !== acceptedBidderId) return;

                        const amount = bid.amount;
                        const status = bid.status || 'pending';
                        const isBest = Number(amount) === lowestPrice;

                        const remarkClass =
                            status === 'accept'
                                ? 'remark-success'
                                : isBest
                                    ? 'remark-success'
                                    : 'remark-warning';

                        biddersWrap.append(`
                        <div class="bid-item">
                        
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <a href="#"
                                       class="fw-bold text-dark text-decoration-none driver-link"
                                       data-driver="${bidderId}">
                                        ${bid.b_name || 'Driver'}
                                    </a>
                        
                                    <i class="fa fa-check-circle text-success" title="Verified"></i>
                                    <i class="fa fa-map-marker-alt text-danger cursor-pointer"
                                       title="View Location"></i>
                                </div>
                        
                                <span class="bid-amount">₹${amount}</span>
                            </div>
                        
                            <div class="bid-remark ${remarkClass}">
                                <i class="fa fa-comment-dots"></i>
                                ${bid.remark || '—'}
                            </div>
                            
                            <div class="d-flex justify-content-end gap-3 mt-2">
                                ${status === 'accept' || status === 'accepted'
                                ? `<span class="text-success fw-semibold">
                                                <i class="fa fa-check-circle me-1"></i> Accepted
                                           </span>`

                                : status === 'reject'
                                    ? `<span class="text-danger fw-semibold">
                                                <i class="fa fa-times-circle me-1"></i> Rejected
                                           </span>`

                                    : (!isJobAccepted
                                        ? `
                                            <span class="text-success fw-semibold cursor-pointer bid-accept"
                                                  data-bidder="${bidderId}"
                                                  data-jobid="${data.id}"
                                                  data-jobno="${jobNo}"
                                                  data-userid="${data.user_id}"
                                                  data-amount="${amount}">
                                                <i class="fa fa-check-circle me-1"></i> Accept
                                            </span>
                            
                                            <span class="text-danger fw-semibold cursor-pointer bid-reject"
                                                  data-bidder="${bidderId}"
                                                  data-jobid="${data.id}">
                                                <i class="fa fa-times-circle me-1"></i> Reject
                                            </span>
                                          `
                                        : ''
                                    )
                            }
                            </div>
                        
                        </div>
                        `);
                    });
                });
        }

        $(document).on('click', '.bidBtn', function () {
            const jobId = $(this).data('job');
            const job = unassignedJobs[jobId];
            openIncomingBids(job);
        });

        $(document).on('click', '.bid-accept', function () {

            const bidderId = $(this).data('bidder');
            const jobId = $(this).data('jobid');
            const jobNo = $(this).data('jobno');
            const userId = $(this).data('userid');
            const amount = $(this).data('amount');

            Swal.fire({
                title: `Accept bid ₹${amount}?`,
                icon: 'question',
                showCancelButton: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: 'Accept',
                cancelButtonText: 'Cancel'
            }).then(result => {
                if (!result.isConfirmed) return;

                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we confirm the bid.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(API_DOMAIN_2 + 'admin-accept-bidder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()'
                    },
                    body: JSON.stringify({
                        job_id: jobId,
                        job_no: jobNo,
                        u_id: userId,
                        user_id: bidderId
                    })
                })
                    .then(res => res.json())
                    .then(res => {
                        Swal.close(); // Close loader

                        if (res.status) {
                            toast('success', res.message);
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            toast('error', res.message);
                        }
                    })
                    .catch(() => {
                        Swal.close();
                        toast('error', 'Network error');
                    });
            });
        });

        $(document).on('click', '.bid-reject', function () {

            const bidderId = $(this).data('bidder');
            const jobId = $(this).data('jobid');
            const $btn = $(this);

            rejectData = {
                bidderId,
                jobId,
                button: $btn
            };

            $('#confirmIcon')
                .removeClass()
                .addClass('fa fa-times-circle text-danger');

            $('#confirmTitleText').text('Reject Bid');
            $('#confirmMessage').html(
                'Are you sure you want to reject this bid?'
            );

            // Show modal
            const modal = new bootstrap.Modal(
                document.getElementById('confirmActionModal'),
                {
                    backdrop: 'static',
                    keyboard: false
                }
            );
            modal.show();
        });

        $('#confirmYesBtn').on('click', function () {

            if (!rejectData.jobId) return;

            const { bidderId, jobId, button } = rejectData;
            const $confirmBtn = $(this);

            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-reject-bid",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                data: {
                    uid: <?= $_SESSION['memid'] ?>,
                    job_id: jobId,
                    bidder_id: bidderId
                },
                beforeSend: function () {

                    $confirmBtn
                        .prop('disabled', true)
                        .html('<i class="fa fa-spinner fa-spin me-1"></i> Processing');

                },
                success: function (res) {

                    if (res.status) {

                        toast('success', 'Bid rejected successfully');

                        // Replace action buttons with Rejected badge
                        const $actionWrap = button.closest('.d-flex.justify-content-end');

                        $actionWrap.html(`
                            <span class="text-danger fw-semibold">
                                <i class="fa fa-times-circle me-1"></i> Rejected
                            </span>
                        `);

                        // Hide modal
                        bootstrap.Modal.getInstance(
                            document.getElementById('confirmActionModal')
                        ).hide();

                    } else {
                        toast('error', res.message || 'Reject failed');
                    }
                },
                error: function () {
                    toast('error', 'Server error');
                },
                complete: function () {

                    $confirmBtn
                        .prop('disabled', false)
                        .html('Yes');

                    rejectData = {};
                }
            });

        });

        loadUnassignedJobsFinal();

        function getPassengerDetails(id, jobtype) {

            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-get-passenger-details",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                data: { passenger_id: id, jobtype: jobtype },
                success: function (res) {

                    if (!res.status || !res.data) return;

                    const d = res.data;

                    // Name
                    if (d.name) {
                        $('#ownerDetailsCard .d-flex.align-items-center.gap-2 span')
                            .first()
                            .text(d.name);
                    }

                    // Mobile
                    if (d.mobile) {
                        const cleanMobile = String(d.mobile).replace(/\D/g, '');

                        $('#ownerDetailsCard .call-con')
                            .attr('href', `tel:${cleanMobile}`);

                        $('#ownerDetailsCard .whatsapp-icon')
                            .attr('href', `https://wa.me/${cleanMobile}`);
                    }

                    // Profile image
                    if (d.profile_img_url) {
                        $('#ownerDetailsCard img')
                            .attr('src', d.profile_img_url);
                    }

                    if (d.city || d.state) {
                        $('#ownerDetailsCard .fa-map-marker-alt')
                            .parent()
                            .html(`
                                <i class="fa fa-map-marker-alt text-danger me-1"></i>
                                ${d.city || ''} ${d.state ? ', ' + d.state : ''}
                            `);
                    }

                    if (d.created_at) {
                        const date = new Date(d.created_at);
                        const formatted = date.toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });

                        $('#ownerDetailsCard .fa-user-clock')
                            .parent()
                            .html(`
                                <i class="fa fa-user-clock me-1"></i>
                                Account Created: <strong>${formatted}</strong>
                            `);
                    }

                }
            });
        }

        $(document).on('click', '.owner-link', function (e) {
            e.preventDefault();

            const passengerId = $(this).data('owner');
            const jobtype = $(this).data('jobtype');

            $('#mapCard, #jobDetailsPanel').addClass('d-none');
            $('#leftPanel').removeClass('d-none');
            $('#incomingBidsPanel').addClass('d-none');

            $('#ownerDetailsCard').removeClass('d-none');
            $('.driverDetailsCard').addClass('d-none');

            getPassengerDetails(passengerId, jobtype);
        });

        function getDriverDetails(id) {

            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-get-driver-details",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                data: { driver_id: id },
                success: function (res) {

                    if (!res.status || !res.data) return;

                    const d = res.data.driver;
                    const cb = res.data.cab_types;

                    /* ======================
                       BASIC INFO
                    ====================== */

                    if (d.user_id) {
                        $('.driverDetailsCard .driver_profile_edit')
                            .attr('href', 'javascript:void(0)')
                            .off('click')
                            .on('click', function () {

                                const url = `/kyc-verify/verify/${d.user_id}/${d.id}`;

                                $('#driverEditIframe').attr('src', url);

                                const canvas = new bootstrap.Offcanvas('#driverEditDrawer');
                                canvas.show();
                            });

                    }

                    // Name
                    if (d.name) {
                        $('.driverDetailsCard .driver_name').text(d.name);
                    }

                    // Profile image (prefer selfie → profile → fallback)
                    if (d.selfie_url) {
                        $('.driverDetailsCard img')
                            .first()
                            .attr('src', d.selfie_url);
                    } else if (d.profile_img_url) {
                        $('.driverDetailsCard img')
                            .first()
                            .attr('src', d.profile_img_url);
                    }

                    // Mobile
                    if (d.mobile) {
                        const cleanMobile = String(d.mobile).replace(/\D/g, '');

                        $('.driverDetailsCard .call-con')
                            .attr('href', `tel:${cleanMobile}`);

                        $('.driverDetailsCard .whatsapp-icon')
                            .attr('href', `https://wa.me/${cleanMobile}`);
                    }

                    /* ======================
                       KYC STATUS
                    ====================== */

                    if (d.doc_verify == 1) {
                        $('.driverDetailsCard .fa-check-circle')
                            .first()
                            .removeClass('text-danger')
                            .addClass('text-success')
                            .parent()
                            .html('<i class="fa fa-check-circle text-success me-1"></i> KYC Verified');
                    }

                    /* ======================
                       LOCATION
                    ====================== */

                    if (d.address || d.state) {
                        const location = `${d.address || ''} ${d.state || ''}`;
                        $('.driverDetailsCard .fa-map-marker-alt')
                            .first()
                            .parent()
                            .html(`
                                <i class="fa fa-map-marker-alt text-danger me-1"></i>
                                ${location}
                            `);
                    }

                    /* ======================
                       EXPERIENCE
                    ====================== */

                    if (d.exp) {
                        $('.driverDetailsCard .fa-briefcase')
                            .parent()
                            .html(`
                                <i class="fa fa-briefcase text-info"></i>
                                ${d.exp} Years
                            `);
                    }

                    /* ======================
                       LICENSE
                    ====================== */

                    if (d.dl_no && d.dl_expiry) {
                        $('.driverDetailsCard .fa-id-badge')
                            .parent()
                            .html(`
                                <i class="fa fa-id-badge text-primary"></i>
                                <strong>${d.dl_no}</strong> |
                                <strong>${formatDateSimple(d.dl_expiry)}</strong>
                            `);
                    }

                    /* ======================
                       VEHICLE DETAILS
                    ====================== */

                    if (d.vehicle_details) {
                        try {
                            const vehicle = JSON.parse(d.vehicle_details);
                            const v = vehicle.vehicle || {};

                            const images = [
                                v.front_view_image_url,
                                v.back_view_image_url,
                                v.side_view_image_url,
                                v.car_top_view_image_url,
                                v.interior_front_image_url,
                                v.interior_rear_image_url,
                                v.extra_image_1_url,
                                v.extra_image_2_url
                            ].filter(Boolean);

                            // Update small preview image
                            if (images.length > 0) {
                                $('.driverDetailsCard img')
                                    .eq(1)
                                    .attr('src', images[0]);
                            }

                            // Build carousel dynamically
                            const carouselInner = $('#carImageCarousel .carousel-inner');
                            carouselInner.empty();

                            if (images.length === 0) {
                                carouselInner.html(`
                                    <div class="carousel-item active">
                                        <img src="/assets/images/carimage.png"
                                             class="img-fluid"
                                             style="max-height:250px;">
                                    </div>
                                `);
                            } else {
                                images.forEach((img, index) => {
                                    carouselInner.append(`
                                        <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                            <img src="${img}"
                                                 class="img-fluid"
                                                 style="max-height:250px;">
                                        </div>
                                    `);
                                });
                            }

                        } catch (e) {
                            console.log('Vehicle parse error');
                        }
                    }

                    /* ======================
                       ACCOUNT CREATED
                    ====================== */

                    if (d.created_at) {
                        const date = new Date(d.created_at);
                        const formatted = date.toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });

                        $('.driverDetailsCard .fa-user-clock')
                            .parent()
                            .html(`
                                <i class="fa fa-user-clock me-1"></i>
                                Account Created: <strong>${formatted}</strong>
                            `);
                    }

                    /* ======================
                       RATING (IF EXISTS)
                    ====================== */

                    if (d.ratings) {
                        $('.driverDetailsCard .fa-star')
                            .parent()
                            .find('strong')
                            .text(d.ratings);
                    }

                    /* ======================
                       PER KM / PER DAY
                    ====================== */

                    if (d.per_km) {
                        $('.driverDetailsCard .fa-road')
                            .parent()
                            .find('strong')
                            .text(`₹${d.per_km}`);
                    }

                    if (d.per_day) {
                        $('.driverDetailsCard .fa-calendar-plus')
                            .parent()
                            .find('strong')
                            .text(`₹${d.per_day}`);
                    }

                }
            });
        }

        $(document).on('click', '.driver-link', function (e) {
            e.preventDefault();

            const driverId = $(this).data('driver');

            $('#mapCard, #jobDetailsPanel, #driversTabCalenderWrapper').addClass('d-none');

            $('.driverDetailsCard, #driversTabCardWrapper').removeClass('d-none');
            $('#ownerDetailsCard').addClass('d-none');

            getDriverDetails(driverId);
        });

        $(document).on('click', '.driverDetailsCard img:first', function () {

            const imgSrc = $(this).attr('src');

            $('#driverPhotoModal .modal-body img')
                .attr('src', imgSrc);
        });

        function initLocationAutocomplete(selector) {

            const $input = $(selector);

            $input.autocomplete({

                minLength: 3,

                source: function (request, response) {

                    response([{ label: 'Loading locations...', value: '' }]);

                    $.ajax({
                        url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-web-getlocation",
                        type: "POST",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': 'gRZlvH9xMVbgCRLQbmV8co6bmAjYpWuvuAY64Mnw'
                        },
                        data: {
                            search: request.term
                        },

                        success: function (res) {

                            if (!res.status || !res.data || !res.data.length) {
                                response([{ label: 'No locations found', value: '' }]);
                                return;
                            }

                            response(res.data.map(function (item) {
                                return {
                                    id: item.place_id,
                                    label: item.name,
                                    value: item.name,
                                    latitude: item.latitude,
                                    longitude: item.longitude
                                };
                            }));
                        },

                        error: function () {
                            response([{ label: 'Error loading locations', value: '' }]);
                        }
                    });
                },

                select: function (event, ui) {

                    if (!ui.item.id) {
                        event.preventDefault();
                        return false;
                    }

                    $input.data("latitude", ui.item.latitude);
                    $input.data("longitude", ui.item.longitude);
                    $input.data("place-id", ui.item.id);
                }

            });
        }

        function getJobFormHTML() {
            return `
            <div class="container-fluid">
                <!-- ================= CUSTOMER DETAILS ================= -->
                <div class="border p-3 mb-3">
                    <h6 class="text-muted mb-3">Customer Details</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" id="custName" class="form-control" placeholder="Customer Name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email</label>
                            <input type="email" id="custEmail" class="form-control" placeholder="Email Address">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mobile <span class="text-danger">*</span></label>
                            <input type="text" id="custMobile" class="form-control" placeholder="Mobile Number">
                        </div>
                    </div>
                </div>
        
                <!-- ================= JOB DETAILS ================= -->
                <div class="border p-3 mb-3">
                    <h6 class="text-muted mb-3">Job Details</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Job No</label>
                            <input type="text" class="form-control" value="Auto Generated" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Job Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="jobType">
                                <option value="">Select Type</option>
                                <option value="one_way">One Way</option>
                                <option value="round_trip">Round Trip</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Car Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="carType">
                                <option value="">Select Car</option>
                                <option value="sedan">Go Sedan (4 Seater)</option>
                                <option value="suv">Go SUV (7 Seater)</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-none" id="returnDaysWrapper">
                            <label class="form-label">
                                Return in Days <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="returnDays">
                                <option value="">Select Days</option>
                            </select>
                        </div>
                    </div>
                </div>
        
                <!-- ================= ROUTE DETAILS ================= -->
                <div class="border p-3 mb-3">
                    <h6 class="text-muted mb-3">Route Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">From <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pickupLocation" placeholder="Pickup Location">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">To <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="dropLocation" placeholder="Drop Location">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Passengers</label>
                            <input type="number" class="form-control" id="passengers" value="4" min="1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Luggage</label>
                            <input type="number" class="form-control" id="luggage" value="2" min="0">
                        </div>
                    </div>
                </div>
        
                <!-- ================= SCHEDULE ================= -->
                <div class="border p-3 mb-3">
                    <h6 class="text-muted mb-3">Schedule</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Pickup Date & Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="pickupDateTime">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12 mb-3 text-end">
                    <button type="button" class="btn btn-primary" id="getDistanceBtn">
                        <i class="fa fa-route me-1"></i> Get Fare & Distance
                    </button>
                </div>
        
                <!-- ================= FARE BREAKDOWN ================= -->
                <div class="border p-3">
                    <h6 class="text-muted mb-3">Fare Breakdown</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Distance (kms)</label>
                            <input type="number" class="form-control" id="distance" placeholder="Distance" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Duration</label>
                            <input type="text" class="form-control" id="duration" placeholder="Duration" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Base Fare <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="baseFare" placeholder="Base Fare" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Toll Fare</label>
                            <input type="number" class="form-control" id="tollFare" placeholder="Toll Fare" readonly>
                        </div>
                        <div class="col-md-12 d-flex align-items-end">
                            <h6 class="fw-bold text-success mb-1">
                                Total: <span id="totalFare">₹0</span>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
            `;
        }

        $(document).on('click', '.createJobBtn', function () {
            // Hide map & other panels
            $('#mapCard, #leftPanel, #incomingBidsPanel, .driverDetailsCard, #ownerDetailsCard')
                .addClass('d-none');

            // Show job panel
            $('#jobDetailsPanel').removeClass('d-none');

            // Inject CREATE JOB layout
            $('#jobDetailsContent').html(`
                <div class="container-fluid">
                    <!-- CUSTOMER SEARCH -->
                    <div class="border p-3 mb-3 bg-light d-none">
                        <h6 class="text-muted mb-3">Select Customer</h6>
            
                        <div class="row g-2 align-items-end">
                            <div class="col-md-8 position-relative">
                                <label class="form-label">Search Existing Customer</label>
                                <input type="text" id="customerSearch"
                                       class="form-control"
                                       placeholder="Search by name or mobile...">
                                
                                <div id="customerSearchResults"
                                     class="list-group position-absolute w-100 d-none"
                                     style="z-index:1000; max-height: 200px; overflow-y: auto;"></div>
                            </div>
            
                           <div class="col-md-4 text-end d-flex justify-content-end gap-2">
                           
                       <div class="btn-group d-none" id="customerHistoryControls">
                        <button class="btn btn-sm btn-primary active fw-bold" data-filter="all">
                            All
                        </button>
                       
                    </div>
                    
                    
                        <button class="btn btn-sm btn-success" id="newCustomerBtn">
                            <i class="fa fa-user-plus me-1"></i> 
                        </button>
                        </div>
        
                        </div>
                    </div>
        
                    <div id="jobFormWrapper" class="d-none">
                        ${getJobFormHTML()}
                    </div>
            
                    <div id="pastJobsSlider" class="past-jobs-slider d-none">
                        <div class="past-jobs-header d-flex justify-content-between align-items-center">
                            <strong id="pastJobsTitle" class="text-primary">
                                All Jobs
                            </strong>
                            <i class="fa fa-times closePastJobs cursor-pointer"></i>
                        </div>
                        <div id="pastJobsList" style="max-height: calc(100% - 50px); overflow-y: auto;">
                        </div>
                    </div>
                </div>
            `);

            $('#jobFormWrapper').removeClass('d-none');

            initLocationAutocomplete('#pickupLocation');
            initLocationAutocomplete('#dropLocation');
        });

        $(document).on('click', '#getDistanceBtn', function () {

            const $btn = $(this);

            const pickupPlaceId = $('#pickupLocation').data("place-id");
            const dropPlaceId = $('#dropLocation').data("place-id");

            let pickupDateTime = $('#pickupDateTime').val();
            let jobType = $('#jobType').val();
            let carType = $('#carType').val();

            if (!pickupPlaceId || !dropPlaceId) {
                toast('error', 'Please select both Pickup and Drop location');
                return;
            }

            if (!pickupDateTime) {
                toast('error', 'Please select pickup date & time');
                return;
            }

            if (!jobType) {
                toast('error', 'Please select job type');
                return;
            }

            if (!carType) {
                toast('error', 'Please select car type');
                return;
            }

            jobType = jobType === 'one_way' ? 'oneway' : 'roundtrip';

            let dropoffDate = null;

            if (jobType === 'roundtrip') {
                const returnDays = $('#returnDays').val();

                if (!returnDays) {
                    toast('error', 'Please select return days');
                    return;
                }

                dropoffDate = parseInt(returnDays);
            }

            pickupDateTime = pickupDateTime.replace('T', ' ') + ':00';

            let apiCarKey = 'four_seater';
            if (carType === 'suv' || carType === 'innova') {
                apiCarKey = 'seven_seater';
            }

            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-web-getdistance",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': 'gRZlvH9xMVbgCRLQbmV8co6bmAjYpWuvuAY64Mnw'
                },
                data: {
                    from_place_id: pickupPlaceId,
                    to_place_id: dropPlaceId,
                    pickup_date: pickupDateTime,
                    dropoff_date: dropoffDate,
                    way_type: jobType
                },
                beforeSend: function () {
                    $btn
                        .prop('disabled', true)
                        .html('<i class="fa fa-spinner fa-spin me-1"></i> Calculating...');
                },
                success: function (response) {

                    if (!response || !response.status || !response.data) {
                        toast('error', response.message || 'Distance fetch failed');
                        return;
                    }

                    const cab = response.data[apiCarKey];

                    if (!cab) {
                        toast('error', 'Route data not found');
                        return;
                    }

                    $('#distance').val(cab.distance);
                    $('#duration').val(cab.duration);
                    $('#baseFare').val(cab.fare);
                    $('#tollFare').val(cab.toll_fare);

                    const total = parseInt(cab.fare);
                    $('#totalFare').text('₹' + total.toLocaleString('en-IN'));

                    $('#pickupLocation').data("latitude", cab.from_lat);
                    $('#pickupLocation').data("longitude", cab.from_lng);

                    $('#dropLocation').data("latitude", cab.to_lat);
                    $('#dropLocation').data("longitude", cab.to_lng);
                },
                error: function () {
                    toast('error', 'Something went wrong. Try again.');
                },
                complete: function () {
                    $btn
                        .prop('disabled', false)
                        .html('<i class="fa fa-route me-1"></i> Get Distance');
                }
            });

        });

        $(document).on('change', '#jobType', function () {
            const jobType = $(this).val();
            const $wrapper = $('#returnDaysWrapper');
            const $select = $('#returnDays');

            if (jobType === 'round_trip') {
                $wrapper.removeClass('d-none');

                // Populate days only once
                if ($select.children('option').length === 1) {
                    for (let i = 1; i <= 10; i++) {
                        $select.append(
                            `<option value="${i}">${i} Day${i > 1 ? 's' : ''}</option>`
                        );
                    }
                }
            } else {
                $wrapper.addClass('d-none');
                $select.val('');
            }
        });

        $(document).on('click', '#confirmBookingBtn', function () {

            const $btn = $(this);

            // ================= CUSTOMER =================
            const cName = $('#custName').val().trim();
            const cEmail = $('#custEmail').val().trim();
            const cMobile = $('#custMobile').val().trim();

            // ================= JOB =================
            let jobType = $('#jobType').val();
            let carType = $('#carType').val();
            let returnDays = $('#returnDays').val();

            const pickupDateTime = $('#pickupDateTime').val();

            // ================= LOCATION =================
            const fromPlace = $('#pickupLocation').val();
            const toPlace = $('#dropLocation').val();

            const fromPlaceId = $('#pickupLocation').data("place-id");
            const toPlaceId = $('#dropLocation').data("place-id");

            // ================= FARE =================
            const distance = $('#distance').val();
            const duration = $('#duration').val();
            const fare = $('#baseFare').val();
            const tollFare = $('#tollFare').val();
            const passengers = $('#passengers').val();
            const luggage = $('#luggage').val();

            // ================= VALIDATION =================
            if (!cName || !cMobile) {
                toast('error', 'Customer name and mobile required');
                return;
            }

            if (!jobType || !carType) {
                toast('error', 'Select job type and car type');
                return;
            }

            if (!fromPlaceId || !toPlaceId) {
                toast('error', 'Select valid locations');
                return;
            }

            if (!pickupDateTime) {
                toast('error', 'Select pickup date');
                return;
            }

            if (!fare || !distance) {
                toast('error', 'Please calculate distance first');
                return;
            }

            // ================= FORMAT VALUES =================

            jobType = jobType === 'one_way' ? 'oneway' : 'roundtrip';

            let dropoffDate = null;
            let dayText = null;

            if (jobType === 'roundtrip') {

                if (!returnDays) {
                    toast('error', 'Select return days');
                    return;
                }

                dropoffDate = parseInt(returnDays);
                dayText = returnDays + " Days";
            }

            const formattedPickup = pickupDateTime.replace('T', ' ') + ':00';

            let apiCabType = 'four_seater';
            if (carType === 'suv') {
                apiCabType = 'seven_seater';
            }

            // ================= CONFIRMATION POPUP =================

            Swal.fire({
                title: 'Confirm Booking?',
                text: `Create job from ${fromPlace} to ${toPlace}?`,
                icon: 'question',
                showCancelButton: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: 'Yes, Create Job'
            }).then((result) => {

                if (!result.isConfirmed) return;

                // ================= API CALL =================

                $.ajax({
                    url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-web-book-journey",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': 'gRZlvH9xMVbgCRLQbmV8co6bmAjYpWuvuAY64Mnw'
                    },
                    data: {
                        job_type: jobType,
                        from_place: fromPlace,
                        to_place: toPlace,
                        from_place_id: fromPlaceId,
                        to_place_id: toPlaceId,
                        pickup_date: formattedPickup,
                        dropoff_date: dropoffDate,
                        pass_count: passengers,
                        lugg_count: luggage,
                        fare: fare,
                        distance: distance,
                        duration: duration,
                        day: dayText,
                        toll: tollFare,
                        cab_type: apiCabType,
                        "add_fare_details[bata]": "Excluded",
                        "add_fare_details[parking]": "Excluded",
                        "add_fare_details[toll]": "Excluded",
                        type: "customer",
                        c_name: cName,
                        c_email: cEmail,
                        c_mobile: cMobile,
                        pick_address: "",
                        drop_address: "",
                        isDriver: "no"
                    },

                    beforeSend: function () {
                        $btn
                            .prop('disabled', true)
                            .html('<i class="fa fa-spinner fa-spin me-1"></i> Creating...');
                    },

                    success: function (res) {

                        if (!res.status) {
                            toast('error', res.message || 'Booking failed');
                            return;
                        }

                        const jobNo = res.data || null;

                        if (!jobNo) {
                            toast('error', 'Job created but job number missing');
                            return;
                        }

                        // ================= SEND WHATSAPP =================
                        $.ajax({
                            url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/web-send-bookinfo",
                            type: "POST",
                            dataType: "json",
                            headers: {
                                'X-CSRF-TOKEN': 'gRZlvH9xMVbgCRLQbmV8co6bmAjYpWuvuAY64Mnw'
                            },
                            data: {
                                job_no: jobNo,
                                mob: cMobile
                            },
                            success: function (waRes) {

                                if (waRes.status) {

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Job Created & WhatsApp Sent',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        text: `Job No: ${jobNo}`
                                    }).then(() => {
                                        location.reload();
                                    });

                                } else {

                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Job Created',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        text: 'WhatsApp sending failed'
                                    }).then(() => {
                                        location.reload();
                                    });

                                }
                            },
                            error: function () {

                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Job Created',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    text: 'WhatsApp sending failed'
                                }).then(() => {
                                    location.reload();
                                });

                            },
                            complete: function () {
                                location.reload();
                            }
                        });

                    },

                    error: function () {
                        toast('error', 'Server error. Try again.');
                    },

                    complete: function () {
                        $btn
                            .prop('disabled', false)
                            .html('<i class="fa fa-check-circle me-1"></i> Confirm Booking');
                    }
                });

            });

        });

        // Drivers TAB

        function renderDriversList() {

            $('#driversList').empty();
            let searchText = $('#driverSearch').val()?.toLowerCase();
            let selectedFields = $('#profileFieldFilter').val();
            let filterType = $('#profileFilterType').val();
            
            let visibleCount = 0;

            Object.entries(driversAll).forEach(([id, driver]) => {
                
                let data = driver.fullData;

                if (searchText && !driver.name.toLowerCase().includes(searchText)) {
                    return;
                }
                
                const profilePercent = driver.profilePercent ?? 0;
                
                if (profilePercent < percentageMin || profilePercent > percentageMax) {
                    return;
                }
                
                if (selectedFields && selectedFields.length && filterType) {

                    let match = selectedFields.every(field => {
                
                        let value = null;
                
                        switch (field) {
                
                            case 'complete_verified':
                                value = (data.doc_verify == 1 && data.vehicle_verify == 2);
                                return filterType === 'filled' ? value : !value;
                
                            case 'images_count':
                                let count = 0;
                                if (data.profile_img_url) count++;
                                if (data.licence_image) count++;
                                if (data.aadhar_image_front) count++;
                                if (data.aadhar_image_back) count++;
                                value = count >= 4;
                                return filterType === 'filled' ? value : !value;
                
                            case 'vehicle_type':
                            case 'exp':
                            case 'dl_expiry':
                                value = data[field];
                                break;
                
                            default:
                                value = data[field];
                        }
                        
                        const isFilled = (val) => {
                            if (val === null || val === undefined) return false;
                        
                            if (typeof val === "string") {
                                val = val.trim();
                                if (val === "" || val.toLowerCase() === "null") return false;
                            }
                        
                            if (val === 0) return false;
                        
                            return true;
                        };
                        
                        if (filterType === 'filled') {
                            return isFilled(value);
                        }
                        
                        if (filterType === 'not_filled') {
                            return !isFilled(value);
                        }
                
                        return true;
                    });
                
                    if (!match) return;
                }

                let profileClass = 'profile-low';
                if (profilePercent >= 80) profileClass = 'profile-good';
                else if (profilePercent >= 50) profileClass = 'profile-medium';

                let statusColor = 'secondary';
                if (driver.status === 'online') statusColor = 'success';
                if (driver.status === 'busy') statusColor = 'warning';
                if (driver.status === 'offline') statusColor = 'danger';
                
                visibleCount++;

                $('#driversList').append(`
                    <div class="list-group-item d-flex justify-content-between align-items-center driver-item"
                         data-driver="${driver.user_id}">
                        <div>
                            <strong class="driver-link cursor-pointer" data-driver="${driver.user_id}">${driver.name}</strong><br>
                            <small class="text-${statusColor}">
                                ● ${driver.status}
                            </small>
                        </div>
                        <div class="profile-circle ${profileClass}">
                            ${profilePercent}%
                        </div>
                    </div>
                `);
                
                $('#driverCount').text(visibleCount);
            });
        }

        function loadDriversList() {

            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-get-driver-list",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                success: function (res) {

                    if (!res.status || !res.data) {
                        toast('error', 'Failed to load drivers');
                        return;
                    }

                    driversAll = {};

                    res.data.forEach(driver => {

                        // ===== STATUS LOGIC =====
                        let status = 'online';

                        // if (driver.rm_status == 1) status = 'online';
                        // if (driver.rm_status == 2) status = 'busy';
                        // if (driver.status == 0) status = 'offline';

                        driversAll[driver.id] = {
                            id: driver.id,
                            user_id: driver.user_id,
                            name: driver.name,
                            mobile: driver.mobile,
                            status: status,
                            profilePercent: driver.profile_percentage,
                            fullData: driver
                        };
                    });

                    renderDriversList();
                },
                error: function () {
                    toast('error', 'Driver list error');
                }
            });
        }

        function renderDriverList(jobId, searchText = '') {

            let html = '';

            searchText = searchText.toLowerCase().trim();

            Object.values(availableDrivers).forEach(driver => {

                if (searchText !== '' && !driver.name.toLowerCase().includes(searchText)) {
                    return;
                }

                let statusClass = 'secondary';
                if (driver.status === 'online') statusClass = 'success';
                if (driver.status === 'busy') statusClass = 'warning';
                if (driver.status === 'offline') statusClass = 'danger';

                html += `
                <div class="bid-item mb-2 p-2">
        
                    <div class="d-flex align-items-center gap-2">
        
                        <img src="${driver.profileImage || '/assets/images/driver.png'}"
                             class="rounded-circle"
                             style="width:36px;height:36px;object-fit:cover;">
        
                        <strong>${driver.name || 'Unknown Driver'}</strong>
        
                        <span class="badge bg-${statusClass}">
                            ${driver.status}
                        </span>
        
                        <button class="btn btn-sm btn-warning ms-auto makeBidBtn"
                                data-driver="${driver.user_id}"
                                data-job="${jobId}">
                            <i class="fa fa-gavel me-1"></i> Make Bid
                        </button>
        
                    </div>
        
                    <div class="d-flex gap-3 mt-2 small text-muted">
        
                        <img src="${driver.vehicleImage || '/assets/images/carimage.png'}"
                             class="rounded"
                             style="width:90px;height:60px;object-fit:cover;">
        
                        <div class="flex-grow-1">
        
                            <div class="fw-semibold text-dark">
                                <i class="fa fa-car text-danger me-1"></i>
                                ${driver.car || 'N/A'}
                            </div>
        
                            <div class="d-flex gap-3 mt-1">
                                <span><i class="fa fa-users text-info"></i> ${driver.seats || '-'}</span>
                                <span><i class="fa fa-gas-pump text-warning"></i> ${driver.fuel || '-'}</span>
                                <span><i class="fa fa-suitcase text-secondary"></i> ${driver.luggage || '-'}</span>
                            </div>
        
                            <div class="d-flex align-items-center gap-3 mt-2">
                                <span>
                                    <i class="fa fa-star text-warning"></i>
                                    <strong>${driver.rating || 0}</strong>
                                </span>
                                <span class="text-muted">
                                    (${driver.ratingsCount || 0} Ratings)
                                </span>
                            </div>
        
                        </div>
                    </div>
        
                </div>`;
            });

            if (html === '') {
                html = `
                    <div class="text-center text-muted py-3">
                        No drivers found
                    </div>
                `;
            }

            $('#driverListContainer').html(html);
        }

        function loadAvailableDrivers(jobId) {
            currentJobId = jobId;

            $('#driverContainerLoader').show();

            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-get-driver-list",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                success: function (res) {

                    if (!res.status || !res.data) {
                        toast('error', 'Failed to load drivers');
                        return;
                    }

                    availableDrivers = {};

                    res.data.forEach(driver => {

                        let status = 'offline';
                        if (driver.rm_status == 1) status = 'online';
                        if (driver.rm_status == 2) status = 'busy';

                        availableDrivers[driver.id] = {
                            id: driver.id,
                            user_id: driver.user_id,
                            name: driver.name,
                            profileImage: driver.selfie_url || '/assets/images/driver.png',
                            status: status,
                            rating: driver.ratings || 0,
                            ratingsCount: 0,
                            accepted: 0,
                            completed: driver.complete_jobs || 0,
                            cancelled: 0,
                            car: driver.cab_type || 'N/A',
                            seats: '4+1',
                            fuel: driver.vehicle_type || 'Petrol',
                            luggage: 2,
                            rc: 'N/A',
                            insurance: 'N/A',
                            vehicleImage: '/assets/images/carimage.png'
                        };
                    });

                    $('#driverContainerLoader').hide();

                    renderDriverList(jobId);
                },
                error: function () {
                    $('#driverContainerLoader').show();
                    toast('error', 'Driver list error');
                }
            });
        }

        $(document).on('keyup', '#driverSearchInput', function () {
            renderDriverList(currentJobId, $(this).val());
        });

        $(document).on('click', '.unassigned-driver-btn', function () {
            const jobId = $(this).data('job');

            $('#mapCard, #incomingBidsPanel').addClass('d-none');
            $('#leftPanel').removeClass('d-none');
            $('#driverListPanel').removeClass('d-none');

            loadAvailableDrivers(jobId);
        });

        $(document).on('click', '.makeBidBtn', function () {

            const driverId = $(this).data('driver');
            const jobId = $(this).data('job');

            $('#bidDriverId').val(driverId);
            $('#bidJobId').val(jobId);

            $('#bidAmount').val('');
            $('#bidRemark').val('');
            $('#bidErrorMsg').addClass('d-none');

            $('#createBidModal').modal('show');
        });

        $(document).on('click', '#submitBidBtn', function () {

            const jobId = $('#bidJobId').val();
            const driverId = $('#bidDriverId').val();
            const amount = $('#bidAmount').val();
            const remark = $('#bidRemark').val();

            if (!amount || parseFloat(amount) <= 0) {
                $('#bidErrorMsg')
                    .text('Please enter valid bid amount')
                    .removeClass('d-none');
                return;
            }

            $('#bidErrorMsg').addClass('d-none');

            const $btn = $(this);

            // Loading UI
            $btn.prop('disabled', true);
            $('.bid-btn-text').addClass('d-none');
            $('.bid-btn-loader').removeClass('d-none');

            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-create-bid",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                data: {
                    job_id: jobId,
                    assignedBy_id: <?php echo $_SESSION['memid'] ?>,
                    driver_id: driverId,
                    amount: amount,
                    remark: remark
                },
                success: function (res) {

                    if (res.status) {

                        $('#createBidModal').modal('hide');

                        toast('success', 'Bid created successfully');

                        const currentBidJob = unassignedJobs[jobId];
                        openIncomingBids(currentBidJob);

                    } else {

                        $('#bidErrorMsg')
                            .text(res.message || 'Bid failed')
                            .removeClass('d-none');
                    }
                },
                error: function () {

                    $('#bidErrorMsg')
                        .text('Server error. Try again.')
                        .removeClass('d-none');
                },
                complete: function () {

                    $btn.prop('disabled', false);
                    $('.bid-btn-text').removeClass('d-none');
                    $('.bid-btn-loader').addClass('d-none');
                }
            });
        });

        $(document).on('click', '#confirmCancelJobBtn', function () {

            const jobId = $('#cancelJobId').val();
            const jobNo = $('#cancelJobNo').val();
            const jobType = $('#cancelJobType').val();
            const userId = $('#cancelJobUserId').val();
            const $btn = $(this);

            if (!jobId) return;

            // Loading UI
            $btn.prop('disabled', true);
            $('.cancel-btn-text').addClass('d-none');
            $('.cancel-btn-loader').removeClass('d-none');

            if (userId == 0) {

                $.ajax({
                    url: origin + "/ajax/service/Websitebook_service.php",
                    method: "POST",
                    dataType: "json",
                    data: {
                        method: 'cancel_job',
                        job_no: jobNo,
                    },
                    success: function (response) {
                        if (response.type) {

                            toast('success', 'Job cancelled successfully');

                            $(`.cancelJobBtn[data-job="${jobId}"]`)
                                .closest('.card')
                                .fadeOut(300, function () {
                                    $(this).remove();
                                });

                        } else {

                            $('#cancelJobError')
                                .text(reresponse.results.message || 'Cancel failed')
                                .removeClass('d-none');
                        }
                    },
                    error: function () {

                        $('#cancelJobError')
                            .text('Server error. Try again.')
                            .removeClass('d-none');
                    },
                    complete: function () {

                        $btn.prop('disabled', false);
                        $('.cancel-btn-text').removeClass('d-none');
                        $('.cancel-btn-loader').addClass('d-none');
                    }
                });

            } else {

                $.ajax({
                    url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-cancel-job",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                    },
                    data: {
                        job_id: jobId,
                        job_no: jobNo,
                        user_id: userId,
                        auth_key: 'ASDFGHJKLqwertyuiopMNBVCXZ!@#$%^&*()0987612345',
                        job_type: 'customer'
                    },
                    success: function (res) {

                        if (res.status) {

                            $('#cancelJobModal').modal('hide');

                            toast('success', 'Job cancelled successfully');

                            // OPTIONAL: Remove card from UI
                            $(`.cancelJobBtn[data-job="${jobId}"]`)
                                .closest('.card')
                                .fadeOut(300, function () {
                                    $(this).remove();
                                });

                        } else {

                            $('#cancelJobError')
                                .text(res.message || 'Cancel failed')
                                .removeClass('d-none');
                        }
                    },
                    error: function () {

                        $('#cancelJobError')
                            .text('Server error. Try again.')
                            .removeClass('d-none');
                    },
                    complete: function () {

                        $btn.prop('disabled', false);
                        $('.cancel-btn-text').removeClass('d-none');
                        $('.cancel-btn-loader').addClass('d-none');
                    }
                });
            }

        });

        $(document).on('click', '.cancelJobBtn', function () {
            const jobId = $(this).data('job');
            const jobNo = $(this).data('job-no');
            const jobType = $(this).data('jobtype');
            const userId = $(this).data('user-id');
            $('#cancelJobId').val(jobId);
            $('#cancelJobNo').val(jobNo);
            $('#cancelJobType').val(jobType);
            $('#cancelJobUserId').val(userId);
            $('#cancelJobError').addClass('d-none');
            $('#cancelJobModal').modal('show');
        });

        $(document).on('click', '[data-filter]', function (e) {

            e.preventDefault();

            const filter = $(this).data('filter');

            $('.dropdown-item').removeClass('active');
            $(this).addClass('active');

            if (filter === 'all') {

                selectedFilterType = null;
                selectedStartDate = null;
                selectedEndDate = null;

                $('#jobDateRange').val('');

                const picker = $('#jobDateRange').data('daterangepicker');
                if (picker) {
                    picker.setStartDate(moment());
                    picker.setEndDate(moment());
                }

                reloadJobsWithFilter();
                return;
            }

            selectedFilterType = filter;

            reloadJobsWithFilter();
        });

        $(function () {

            $('#jobDateRange').daterangepicker({
                autoUpdateInput: false,
                opens: 'left',
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [
                        moment().subtract(1, 'month').startOf('month'),
                        moment().subtract(1, 'month').endOf('month')
                    ],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [
                        moment().subtract(1, 'year').startOf('year'),
                        moment().subtract(1, 'year').endOf('year')
                    ]
                }
            });

            $('#jobDateRange').on('apply.daterangepicker', function (ev, picker) {

                selectedStartDate = picker.startDate.format('YYYY-MM-DD');
                selectedEndDate = picker.endDate.format('YYYY-MM-DD');

                $(this).val(
                    picker.startDate.format('DD/MM/YYYY') +
                    ' - ' +
                    picker.endDate.format('DD/MM/YYYY')
                );

                reloadJobsWithFilter();
            });

            $('#jobDateRange').on('cancel.daterangepicker', function () {
                $(this).val('');
                selectedStartDate = null;
                selectedEndDate = null;
                reloadJobsWithFilter();
            });

            function reloadJobsWithFilter() {

                loadUnassignedJobsFinal();
                loadAssignedJobsFinal();
            }


        });

        function loadCalendarAssignedJobs(filterDriverId = null) {

            $('#calendarLoader').removeClass('d-none');

            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-job-list",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                data: {
                    method: 'jobList_New',
                    jobStatus: 'accepted'
                },
                success: function (res) {

                    calendar.removeAllEvents();

                    if (!res.result || !res.result.length) return;

                    res.result.forEach(job => {

                        if (job.job_status !== 'accept') return;
                        if (!job.pickup_date) return;
                        if (!job.bids_details) return;

                        let acceptedDriverId = null;
                        let acceptedDriverName = null;

                        Object.entries(job.bids_details).forEach(([bidderId, bid]) => {
                            if (bid.status === 'accept' || bid.status === 'accepted') {
                                acceptedDriverId = bidderId;
                                acceptedDriverName = bid.b_name;
                            }
                        });

                        if (!acceptedDriverId) return;

                        if (filterDriverId && String(acceptedDriverId) !== String(filterDriverId)) return;

                        const eventDate = job.pickup_date.replace(' ', 'T');

                        calendar.addEvent({
                            title: `${acceptedDriverName} – ${job.job_no}`,
                            start: eventDate,
                            backgroundColor: '#198754',
                            borderColor: '#198754',
                            extendedProps: {
                                jobId: job.id,
                                driverId: acceptedDriverId
                            }
                        });
                    });
                },
                complete: function () {
                    setTimeout(() => {
                        $('#calendarLoader').addClass('d-none');
                    }, 200);
                }
            });
        }

        const calendar = new FullCalendar.Calendar(
            document.getElementById('driverCalendar'),
            {
                initialView: 'dayGridMonth',
                height: '100%',
                fixedWeekCount: false,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },

                eventDidMount(info) {
                    info.el.style.fontSize = '12px';
                    info.el.style.borderRadius = '6px';
                },

                eventClick(info) {
                    const jobId = info.event.extendedProps.jobId;
                }
            }
        );

        $('[data-view]').on('click', function () {

            $('[data-view]').removeClass('active');
            $(this).addClass('active');

            const view = $(this).data('view');

            if (view === 'month') {
                calendar.changeView('dayGridMonth');
            } else {
                calendar.changeView('timeGridWeek');
            }
        });

        $('button[data-bs-target="#driversTab"]').on('shown.bs.tab', function () {
            $('#calendarLoader').removeClass('d-none');
            setTimeout(() => {
                calendar.updateSize();
                calendar.render();
                loadCalendarAssignedJobs();
                loadDriversList();
            }, 100);
        });

        function refreshCalendar(driverId = null) {
            loadCalendarAssignedJobs(driverId);
        }

        $('button[data-view]').on('click', function () {

            $('button[data-view]').removeClass('active');
            $(this).addClass('active');

            const view = $(this).data('view');
            calendar.changeView(view === 'week' ? 'timeGridWeek' : 'dayGridMonth');
        });

        $(document).on('click', '.driver-item', function (e) {
            e.preventDefault();
            $('.driver-item').removeClass('active');
            $(this).addClass('active');
            selectedDriver = $(this).data('driver');
            refreshCalendar(selectedDriver);
        });

        $('#showAllDrivers').on('click', function () {
            selectedDriver = null;
            $('.driver-item').removeClass('active');
            refreshCalendar();
        });

        $('#driverSearch').on('keyup', function () {
            renderDriversList();
        });
        
        $('#profileFilterType').on('change', function () {
            renderDriversList();
        });
        
        const profileSelect = new TomSelect("#profileFieldFilter", {
            plugins: ['remove_button'],
            maxItems: null,
            hideSelected: true,
            closeAfterSelect: false,
            placeholder: "Select profile fields...",
            onChange: function() {
                renderDriversList();
            }
        });
        
        const rangeSlider = document.getElementById('percentageRange');
        
        noUiSlider.create(rangeSlider, {
            start: [0, 100],
            connect: true,
            range: {
                'min': 0,
                'max': 100
            },
            step: 1
        });
        
        rangeSlider.noUiSlider.on('update', function (values) {
            percentageMin = parseInt(values[0]);
            percentageMax = parseInt(values[1]);
        
            $('#rangeMin').text(percentageMin + '%');
            $('#rangeMax').text(percentageMax + '%');
        });
        
        rangeSlider.noUiSlider.on('change', function () {
            renderDriversList();
        });

    });

    // #Hastar

    $(document).ready(function () {

        // Show map by default
        $('#mapCard').removeClass('d-none');

        // Hide everything else
        $('#leftPanel').addClass('d-none');
        $('#incomingBidsPanel').addClass('d-none');
        $('.driverDetailsCard').addClass('d-none');
        $('#ownerDetailsCard').addClass('d-none');
        $('#jobDetailsPanel').addClass('d-none');

    });


    /* =====================================================
       BIDS BUTTON CLICK
    ===================================================== */
    // $(document).on('click', '.bidBtn', function () {
    //     $('#mapCard, #jobDetailsPanel').addClass('d-none');
    //     $('#leftPanel').removeClass('d-none');
    //     $('#incomingBidsPanel').removeClass('d-none');
    //     $('.driverDetailsCard, #ownerDetailsCard').addClass('d-none');
    // });

    /* =====================================================
       DRIVER DETAILS CLICK
    ===================================================== */
    // $(document).on('click', '.driver-link', function (e) {
    //     e.preventDefault();

    //     $('#mapCard, #jobDetailsPanel').addClass('d-none');

    //     $('.driverDetailsCard').removeClass('d-none');
    //     $('#ownerDetailsCard').addClass('d-none');
    // });

    /* =====================================================
       OWNER DETAILS CLICK
    ===================================================== */
    // $(document).on('click', '.owner-link', function (e) {
    //     e.preventDefault();

    //     $('#mapCard, #jobDetailsPanel').addClass('d-none');

    //     $('#leftPanel').removeClass('d-none');
    //     $('#incomingBidsPanel').removeClass('d-none');

    //     $('#ownerDetailsCard').removeClass('d-none');
    //     $('.driverDetailsCard').addClass('d-none');
    // });


    /* =====================================================
       EDIT JOB CLICK  ⭐ IMPORTANT ⭐
    ===================================================== */
    $(document).on('click', '.jobEdit', function (e) {
        e.preventDefault();

        const jobId = $(this).data('job');

        // Hide map & all panels
        $('#mapCard').addClass('d-none');
        $('#leftPanel, #incomingBidsPanel, .driverDetailsCard, #ownerDetailsCard')
            .addClass('d-none');

        // Show job details (FULL COL-8)
        $('#jobDetailsPanel').removeClass('d-none');

        // Inject job preview
        $('#jobDetailsContent').html(`
       <div class="container-fluid">

  <!-- ================= CUSTOMER DETAILS ================= -->
  <div class="border  p-3 mt-2">
    <h6 class="text-muted mb-3">Customer Details</h6>

    <div class="row g-3">
      <div class="col-md-4 mt-0">
        <label class="form-label">Name</label>
        <input type="text" class="form-control" value="Jobby">
      </div>

      <div class="col-md-4 mt-0">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" value="devavasu2002@gmail.com">
      </div>

      <div class="col-md-4 mt-0">
        <label class="form-label">Mobile</label>
        <input type="text" class="form-control" value="9176333791">
      </div>
    </div>
  </div>

  <!-- ================= JOB DETAILS ================= -->
  <div class="border  p-3 ">
    <h6 class="text-muted mb-3">Job Details</h6>

    <div class="row g-3">
      <div class="col-md-4 mt-0">
        <label class="form-label">Job No</label>
        <input type="text" class="form-control" value="GRC-015">
      </div>

      <div class="col-md-4 mt-0">
        <label class="form-label">Job Type</label>
        <select class="form-select">
          <option selected>Round Trip</option>
          <option>One Way</option>
        </select>
      </div>

      <!-- ✅ CAR TYPE (YOU SAID THIS WAS MISSING) -->
      <div class="col-md-4 mt-0">
        <label class="form-label">Car Type</label>
        <select class="form-select">
          <option selected>Go Sedan</option>
          <option>SUV</option>
        </select>
      </div>
    </div>
  </div>

  <!-- ================= ROUTE DETAILS ================= -->
  <div class="border p-3 mb-4">
    <h6 class="text-muted mb-3">Route Details</h6>

    <div class="row g-3">
      <div class="col-md-6 mt-0">
        <label class="form-label">From</label>
        <input type="text" class="form-control"
               value="Ooty Market, Ooty, Tamil Nadu, India">
      </div>

      <div class="col-md-6 mt-0">
        <label class="form-label">To</label>
        <input type="text" class="form-control"
               value="Kodaikanal Road, Poombarai, Tamil Nadu, India">
      </div>

      <div class="col-md-3 mt-0">
        <label class="form-label">Distance (kms)</label>
        <input type="number" class="form-control" value="550">
      </div>

      <div class="col-md-3 mt-0">
        <label class="form-label">Duration</label>
        <input type="text" class="form-control" value="10 Days">
      </div>

      <div class="col-md-3 mt-0">
        <label class="form-label">Passengers</label>
        <input type="number" class="form-control" value="4">
      </div>

      <div class="col-md-3 mt-0">
        <label class="form-label">Luggage</label>
        <input type="number" class="form-control" value="3">
      </div>
    </div>
  </div>

  <!-- ================= SCHEDULE ================= -->
  <div class="border  p-3 ">
    <h6 class="text-muted mb-3">Schedule</h6>

    <div class="row g-3">
      <div class="col-md-6 mt-0">
        <label class="form-label">Pickup Date & Time</label>
        <input type="datetime-local" class="form-control"
               value="2026-02-10T20:30">
      </div>
    </div>
  </div>

  <!-- ================= FARE BREAKDOWN ================= -->
  <div class="border  p-3 ">
    <h6 class="text-muted mb-3">Fare Breakdown</h6>

    <div class="row g-3">
      <div class="col-md-4 mt-0">
        <label class="form-label">Base Fare</label>
        <input type="number" class="form-control" value="6760">
      </div>

      <div class="col-md-4 mt-0">
        <label class="form-label">Toll Fare</label>
        <input type="number" class="form-control" value="120">
      </div>

      <div class="col-md-4 mt-0 d-flex align-items-end">
        <h6 class="text-success fw-bold mb-1">
          Total: ₹6880
        </h6>
      </div>
    </div>
  </div>

</div>

    `);
    });


    /* =====================================================
       BACK TO MAP (GLOBAL)
    ===================================================== */
    $(document).on('click', '.backToMap', function () {

        $('#leftPanel').addClass('d-none');
        $('#incomingBidsPanel, .driverDetailsCard, #ownerDetailsCard, #jobDetailsPanel')
            .addClass('d-none');

        // 🔥 ADD THIS
        $('#driverListPanel').addClass('d-none');

        $('#mapCard').removeClass('d-none');
    });



    /* =====================================================
       BID ACCEPT / REJECT MODAL
    ===================================================== */
    let currentAction = null;
    let currentBidId = null;
    let cancelJobId = null;
    let cancelJobNo = null;
    let cancelJobType = null;
    let cancelJobUserId = null;

    $(document).on('click', '.dropdown-menu .dropdown-item', function (e) {
        e.preventDefault();

        let text = $(this).text().trim();

        // Remove text inside brackets
        text = text.replace(/\(.*?\)/g, '').trim();

        $('#jobFilterBtn span').text(text);

        $('.dropdown-menu .dropdown-item').removeClass('active');
        $(this).addClass('active');
    });


    /* =====================================================
       REMOVE DRIVER WITH CONFIRMATION MODAL
    ===================================================== */
    let driverToRemove = null;
    let jobToRemoveDriverFrom = null;

    $(document).on('click', '.remove-driver', function (e) {
        e.preventDefault();
        e.stopPropagation(); // Prevent triggering driver-link click

        driverToRemove = $(this).data('driver');
        jobToRemoveDriverFrom = $(this).data('job');

        // Show confirmation modal
        $('#removeDriverModal').modal('show');
    });

    $('#confirmRemoveDriverBtn').on('click', function () {
        if (driverToRemove && jobToRemoveDriverFrom) {
            console.log('Removing driver:', driverToRemove, 'from job:', jobToRemoveDriverFrom);

            // Here you would make an API call to remove the driver
            // For example: removeDriverFromJob(jobToRemoveDriverFrom, driverToRemove);

            // Remove the driver from the assignments object
            if (assignments[jobToRemoveDriverFrom]) {

                // ❌ Remove driver
                delete assignments[jobToRemoveDriverFrom];

                // ❌ REMOVE STATUS TOO (THIS IS THE FIX)
                delete jobStatuses[jobToRemoveDriverFrom];

                // 🔄 Refresh UI
                renderJobLists();
                refreshCalendar();

                showToast('Driver removed successfully!', 'success');
            }

        }

        // Close the modal
        $('#removeDriverModal').modal('hide');

        // Reset variables
        driverToRemove = null;
        jobToRemoveDriverFrom = null;
    });

    /* =====================================================
       NEW CUSTOMER BUTTON CLICK
    ===================================================== */
    $(document).on('click', '#newCustomerBtn', function () {
        // Show empty form
        $('#jobFormWrapper').removeClass('d-none');

        // Clear all fields
        clearJobForm();

        // Hide past jobs panel
        $('#pastJobsSlider').addClass('d-none');
        $('#customerSearchResults').addClass('d-none');

        // Focus on customer name
        $('#custName').focus();
    });

    /* =====================================================
       CUSTOMER SEARCH FUNCTIONALITY
    ===================================================== */
    $(document).on('keyup', '#customerSearch', function () {
        const searchTerm = $(this).val().trim();
        const results = $('#customerSearchResults');

        if (!searchTerm) {
            results.addClass('d-none');
            return;
        }

        // Mock data for testing
        const mockCustomers = [
            { id: 1, name: 'Ramesh Kumar', mobile: '9876543210', email: 'ramesh@gmail.com', pastJobs: 5 },
            { id: 2, name: 'Suresh Patel', mobile: '9876543211', email: 'suresh@gmail.com', pastJobs: 3 },
            { id: 3, name: 'Rajesh Sharma', mobile: '9876543212', email: 'rajesh@gmail.com', pastJobs: 7 },
            { id: 4, name: 'Mahesh Reddy', mobile: '9876543213', email: 'mahesh@gmail.com', pastJobs: 2 },
            { id: 5, name: 'Ganesh Iyer', mobile: '9876543214', email: 'ganesh@gmail.com', pastJobs: 4 }
        ];

        // Filter customers
        const filtered = mockCustomers.filter(customer =>
            customer.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            customer.mobile.includes(searchTerm)
        );

        if (filtered.length === 0) {
            results.html(`
            <div class="list-group-item text-muted">
                No customers found
            </div>
        `).removeClass('d-none');
            return;
        }

        // Build results HTML
        let html = '';
        filtered.forEach(customer => {
            html += `
            <a href="#" class="list-group-item selectCustomer" 
               data-customer-id="${customer.id}"
               data-name="${customer.name}"
               data-email="${customer.email}"
               data-mobile="${customer.mobile}"
               data-past-jobs="${customer.pastJobs}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${customer.name}</strong><br>
                        <small class="text-muted">${customer.mobile}</small>
                    </div>
                    <span class="badge bg-secondary">${customer.pastJobs} past jobs</span>
                </div>
            </a>
        `;
        });

        results.html(html).removeClass('d-none');
    });

    /* =====================================================
       SELECT EXISTING CUSTOMER
    ===================================================== */
    $(document).on('click', '.selectCustomer', function (e) {
        e.preventDefault();

        const customerId = $(this).data('customer-id');
        const customerName = $(this).data('name');
        const customerEmail = $(this).data('email');
        const customerMobile = $(this).data('mobile');
        const pastJobsCount = $(this).data('past-jobs');

        // Show form (EMPTY initially)
        $('#jobFormWrapper').removeClass('d-none');

        // Fill ONLY customer details (keep job fields empty)
        $('#custName').val(customerName);
        $('#custEmail').val(customerEmail);
        $('#custMobile').val(customerMobile);

        // Hide search results
        $('#customerSearchResults').addClass('d-none');

        // Load and show past jobs on the side
        loadPastJobs(customerId, customerName);
    });

    /* =====================================================
       LOAD PAST JOBS FOR CUSTOMER
    ===================================================== */
    function loadPastJobs(customerId, customerName) {
        // Mock past jobs data
        const mockPastJobs = [
            {
                id: 1,
                from: 'Chennai',
                to: 'Salem',
                date: '15 Jan 2026',
                carType: 'Sedan',
                passengers: 4,
                luggage: 2,
                baseFare: 4525,
                toll: 495,
                total: 5020,
                status: 'completed'
            },
            {
                id: 2,
                from: 'Chennai',
                to: 'Coimbatore',
                date: '20 Jan 2026',
                carType: 'SUV',
                passengers: 6,
                luggage: 4,
                baseFare: 6520,
                toll: 750,
                total: 7270,
                status: 'cancelled'
            },
            {
                id: 3,
                from: 'Chennai',
                to: 'Bangalore',
                date: '25 Jan 2026',
                carType: 'Sedan',
                passengers: 3,
                luggage: 3,
                baseFare: 7850,
                toll: 850,
                total: 8700,
                status: 'completed'
            }
        ];


        // Build past jobs HTML
        let html = '';
        mockPastJobs.forEach(job => {
            html += `
           <div class="past-job p-3 border-bottom" data-status="${job.status}">

              <div class="mb-2 d-flex align-items-center justify-content-between">
    <strong>${job.from} → ${job.to}</strong>

    <span class="d-flex align-items-center gap-1">
        <i class="fa fa-calendar text-danger"></i>
        <span class="badge bg-light text-dark">${job.date}</span>
    </span>
</div>

                <div class="small text-muted mb-2">
                    <i class="fa fa-car text-secondary me-1"></i>${job.carType} | 
                    <i class="fa fa-users text-success me-1"></i>${job.passengers} | 
                    <i class="fa fa-suitcase text-warning me-1"></i>${job.luggage}
                </div>
                <div class="small text-muted mb-2">
                    Base: ₹${job.baseFare} | Toll: ₹${job.toll} | Total: <strong>₹${job.total}</strong>
                </div>
                <button class="btn btn-sm btn-info w-100 copyPastJob"
                        data-from="${job.from}"
                        data-to="${job.to}"
                        data-car-type="${job.carType}"
                        data-passengers="${job.passengers}"
                        data-luggage="${job.luggage}"
                        data-base-fare="${job.baseFare}"
                        data-toll="${job.toll}">
                    <i class="fa fa-copy me-1"></i> Copy This Job
                </button>
            </div>
        `;
        });

        $('#pastJobsList').html(html);
        $('#pastJobsSlider').removeClass('d-none');

        // Show history buttons
        $('#customerHistoryControls').removeClass('d-none');





    }

    /* =====================================================
       COPY PAST JOB TO FORM
    ===================================================== */
    $(document).on('click', '.copyPastJob', function () {
        const from = $(this).data('from');
        const to = $(this).data('to');
        const carType = $(this).data('car-type');
        const passengers = $(this).data('passengers');
        const luggage = $(this).data('luggage');
        const baseFare = $(this).data('base-fare');
        const toll = $(this).data('toll');

        // Auto-fill form fields (except customer details)
        $('#pickupLocation').val(from);
        $('#dropLocation').val(to);
        $('#carType').val(carType.toLowerCase());
        $('#passengers').val(passengers);
        $('#luggage').val(luggage);
        $('#baseFare').val(baseFare);
        $('#tollFare').val(toll);

        // Calculate total
        calculateTotal();

        // Show success message
        showToast('Job details copied! Adjust as needed.');

        // Close past jobs panel after a delay
        setTimeout(() => {
            $('#pastJobsSlider').addClass('d-none');
        }, 500);
    });

    /* =====================================================
       CLOSE PAST JOBS PANEL
    ===================================================== */
    $(document).on('click', '.closePastJobs', function () {
        $('#pastJobsSlider').addClass('d-none');
    });

    /* =====================================================
       CALCULATE TOTAL FARE
    ===================================================== */
    function calculateTotal() {
        const baseFare = parseFloat($('#baseFare').val()) || 0;
        const tollFare = parseFloat($('#tollFare').val()) || 0;
        const total = baseFare + tollFare;
        $('#totalFare').text('₹' + total.toLocaleString());
    }

    // Add event listeners for fare calculation
    $(document).on('input', '#baseFare, #tollFare', calculateTotal);

    /* =====================================================
       CLEAR JOB FORM
    ===================================================== */
    function clearJobForm() {
        // Clear all form fields
        $('#custName').val('');
        $('#custEmail').val('');
        $('#custMobile').val('');
        $('#jobType').val('');
        $('#carType').val('');
        $('#pickupLocation').val('');
        $('#dropLocation').val('');
        $('#distance').val('');
        $('#duration').val('');
        $('#passengers').val(4);
        $('#luggage').val(2);
        $('#pickupDateTime').val('');
        $('#baseFare').val('');
        $('#tollFare').val('');
        $('#totalFare').text('₹0');
    }

    /* =====================================================
       SHOW TOAST NOTIFICATION
    ===================================================== */
    function showToast(message, type = 'success') {
        const toastClass = type === 'success' ? 'bg-success' : 'bg-danger';

        // Create toast HTML
        const toastHtml = `
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
            <div class="toast show" role="alert">
                <div class="toast-header ${toastClass} text-white">
                    <strong class="me-auto">Notification</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        </div>
    `;

        // Add to body
        $('body').append(toastHtml);

        // Auto remove after 3 seconds
        setTimeout(() => {
            $('.toast').remove();
        }, 3000);
    }

    /* =====================================================
       SAVE JOB FUNCTIONALITY
    ===================================================== */
    // $(document).on('click', '#jobDetailsPanel .btn-success', function () {
    //     const requiredFields = ['#custName', '#custMobile', '#jobType', '#carType', '#pickupLocation', '#dropLocation', '#pickupDateTime', '#baseFare'];
    //     let isValid = true;
    //     let missingFields = [];

    //     requiredFields.forEach(field => {
    //         const value = $(field).val();
    //         if (!value || value.trim() === '') {
    //             isValid = false;
    //             const fieldName = $(field).attr('id').replace('cust', '').replace('job', '').replace('pickup', '');
    //             missingFields.push(fieldName);
    //         }
    //     });

    //     if (!isValid) {
    //         showToast(`Please fill required fields: ${missingFields.join(', ')}`, 'danger');
    //         return;
    //     }

    //     const jobData = {
    //         customer: {
    //             name: $('#custName').val(),
    //             email: $('#custEmail').val(),
    //             mobile: $('#custMobile').val()
    //         },
    //         job: {
    //             type: $('#jobType').val(),
    //             carType: $('#carType').val(),
    //             from: $('#pickupLocation').val(),
    //             to: $('#dropLocation').val(),
    //             distance: $('#distance').val(),
    //             duration: $('#duration').val(),
    //             passengers: $('#passengers').val(),
    //             luggage: $('#luggage').val(),
    //             pickupDateTime: $('#pickupDateTime').val(),
    //             baseFare: $('#baseFare').val(),
    //             tollFare: $('#tollFare').val(),
    //             total: $('#totalFare').text().replace('₹', '')
    //         }
    //     };

    //     console.log('Job data to save:', jobData);

    //     showToast('Job created successfully!', 'success');

    //     setTimeout(() => {
    //         $('.backToMap').trigger('click');
    //     }, 1500);
    // });

    $(document).on('click', '#customerHistoryControls button', function () {

        const filter = $(this).data('filter');


        /* ===============================
           APPLY ACTIVE BUTTON + TITLE
        =============================== */
        // if (filter === 'completed') {
        //     $(this)
        //         .removeClass('btn-outline-secondary')
        //         .addClass('btn-success active fw-bold text-white');

        //     $('#pastJobsTitle')
        //         .text('Completed Jobs')
        //         .removeClass('text-primary text-danger')
        //         .addClass('text-success');
        // }
        // else if (filter === 'cancelled') {
        //     $(this)
        //         .removeClass('btn-outline-secondary')
        //         .addClass('btn-danger active fw-bold text-white');

        //     $('#pastJobsTitle')
        //         .text('Cancelled Jobs')
        //         .removeClass('text-primary text-success')
        //         .addClass('text-danger');
        // }
        // else {
        //     $(this)
        //         .removeClass('btn-outline-secondary')
        //         .addClass('btn-primary active fw-bold text-white');

        //     $('#pastJobsTitle')
        //         .text('All Jobs')
        //         .removeClass('text-success text-danger')
        //         .addClass('text-primary');
        // }

        /* ===============================
           OPEN SLIDER
        =============================== */
        $('#pastJobsSlider').removeClass('d-none');

        /* ===============================
           FILTER JOBS
        =============================== */
        $('#pastJobsList .past-job').each(function () {
            const status = $(this).data('status');

            if (filter === 'all' || status === filter) {
                $(this).slideDown(150);
            } else {
                $(this).slideUp(150);
            }
        });

    });

    $('#removeDriverNoBtn').on('click', function () {

        // Remove driver even on NO
        if (jobToRemoveDriverFrom) {

            delete assignments[jobToRemoveDriverFrom];

            // ❌ ALSO REMOVE STATUS
            delete jobStatuses[jobToRemoveDriverFrom];

            renderJobLists();
            refreshCalendar();
        }


        // Close modal
        $('#removeDriverModal').modal('hide');

        // Reset values
        driverToRemove = null;
        jobToRemoveDriverFrom = null;

    });

    $(document).on('click', '.assignDriverFromList', function () {

        const driverId = $(this).data('driver');
        const jobId = $(this).data('job');

        assignments[jobId] = driverId;

        // ✅ ADD THIS LINE
        jobStatuses[jobId] = 'assigned';

        renderJobLists();
        refreshCalendar();

        $('.backToMap').trigger('click');
        showToast('Driver assigned successfully!', 'success');
    });

    /* =====================================================
       DRIVER CLICK FROM UNASSIGNED LIST → SHOW DETAILS
    ===================================================== */

    $(document).on('click', '.driver-from-list', function (e) {
        e.preventDefault();

        const driverId = $(this).data('driver');

        // Hide map
        $('#mapCard').addClass('d-none');

        // Show left panel
        $('#leftPanel').removeClass('d-none');

        // ✅ KEEP DRIVER LIST VISIBLE
        $('#driverListPanel').removeClass('d-none');

        // Show driver details on RIGHT
        $('.driverDetailsCard').removeClass('d-none');

        // (Optional) hide owner details
        $('#ownerDetailsCard').addClass('d-none');

        // Inject name dynamically (demo)
        $('.driverDetailsCard strong span').text(
            drivers[driverId]?.name || 'Driver'
        );
    });

    function getJobStatusUI(status) {
        const map = {
            assigned: { text: 'Assigned', class: 'primary', icon: 'fa-user' },
            dispatched: { text: 'Dispatched', class: 'info', icon: 'fa-car' },
            reached: { text: 'Reached', class: 'warning', icon: 'fa-map-marker-alt' },
            onboard: { text: 'On Board', class: 'success', icon: 'fa-users' },
            completed: { text: 'Completed', class: 'secondary', icon: 'fa-check' },
            cancelled: { text: 'Cancelled', class: 'danger', icon: 'fa-times' }
        };
        return map[status] || map.assigned;
    }

    $(document).on('click', '.driver-request', function () {

        const requestId = $(this).data('request');
        selectedDriverRequest = requestedJobs.find(r => r.id == requestId);

        $('.driver-request').removeClass('border-primary');
        $(this).addClass('border border-primary');

        console.log(
            'Driver wants:',
            selectedDriverRequest.from,
            '→',
            selectedDriverRequest.to
        );

    });

</script>