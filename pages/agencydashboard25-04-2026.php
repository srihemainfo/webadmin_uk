<?php
// error_reporting(E_ALL); 
// ini_set('display_errors', 1);
$today = date('Y-m-d');
?>
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/viewerjs/dist/viewer.min.css">
<script src="https://unpkg.com/viewerjs/dist/viewer.min.js"></script>
<script src="https://app.digio.in/sdk/v11/digio.js"></script>
<style>
    .leaflet-routing-container {
        display: none !important;
    }
    .document-img,
    .vehicle-photo,
    .driver-photo {
        position: relative;
        background-color: #f8f9fa;
        color: transparent;
    }
    .document-img::after,
    .vehicle-photo::after,
    .driver-photo::after {
        content: "No Image Available";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: #6c757d;
        font-weight: 500;
        text-align: center;
        z-index: 2;
    }
    .has-local-spinner {
        position: relative !important;
        min-height: 100px;
    }
    .has-local-spinner::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -12px;
        margin-left: -12px;
        width: 24px;
        height: 24px;
        border: 3px solid rgba(0, 0, 0, 0.1);
        border-top-color: #0d6efd;
        border-radius: 50%;
        animation: local-spinner-spin 0.8s linear infinite;
        z-index: 10;
    }
    @keyframes local-spinner-spin {
        to {
            transform: rotate(360deg);
        }
    }
    .clear-img-btn {
        background-color: rgba(220, 53, 69, 0.9) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .2931.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") !important;
        border-radius: 4px !important;
        padding: 0.4rem !important;
        opacity: 1 !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2) !important;
    }
    .clear-img-btn:hover {
        background-color: rgba(200, 35, 51, 1) !important;
    }
    #vehicleImagesModal .modal-dialog {
        margin-left: 20px;
        margin-right: auto;
    }
    #unassigned,
    #assigned,
    #websiteBookings {
        max-height: 650px;
        overflow-y: auto;
        transition: all 0.3s ease;
        padding-right: 5px;
    }
    #unassigned::after,
    #assigned::after,
    #websiteBookings::after {
        /*content: "↓ Scroll for more";*/
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
    #unassigned:hover::after,
    #assigned:hover::after,
    #websiteBookings:hover::after,
    #unassigned:focus-within::after,
    #assigned:focus-within::after,
    #websiteBookings:focus-within::after {
        opacity: 0;
    }
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
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 16px;
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
        background: #ffffff;
    }
    .ranges li:hover .daterangepicker {
        border-radius: 10px !important;
        border: 1px solid #f0f0f5;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        font-size: 13px;
    }
    .daterangepicker .ranges li {
        border-radius: 6px;
        padding: 8px 12px;
        margin-bottom: 4px;
        color: #5a5f7d;
        font-weight: 500;
    }
    .daterangepicker .ranges li:hover {
        background: #f0f0f5;
        color: #382f2f;
        border: 1px solid #f0f0f5;
    }
    .daterangepicker .ranges li.active {
        background: #ffc45b;
        color: #382f2f;
        border: 1px solid #f0f0f5;
    }
    .daterangepicker .calendar-table th {
        color: #5a5f7d;
        font-weight: 600;
    }
    .daterangepicker td.available {
        border-radius: 6px;
    }
    .daterangepicker td.available:hover {
        background: #eef1ff;
        color: #5b6cff;
    }
    .daterangepicker td.in-range {
        background: #eef1ff;
        color: #4a4f6a;
    }
    .daterangepicker td.active,
    .daterangepicker td.active:hover {
        background: #5b6cff;
        color: #ffffff;
    }
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
        color: #198754;
    }
    .profile-medium {
        border-color: #ffc107;
        color: #856404;
    }
    .profile-low {
        border-color: #dc3545;
        color: #dc3545;
    }
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
    #profileProgressBar {
        transition: width 0.6s ease;
    }
    .card-open {
        background-color: #fee43130;
    }
    .document-box {
        position: relative;
        width: 100%;
        height: 100px;
        border: 2px dashed #dee2e6;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.2s;
        background: #f8f9fa;
    }
    .document-box-small {
        width: 100%;
        height: 110px;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #e5e8ef;
        background: #f8f9fb;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .document-box:hover,
    .document-box-small:hover {
        border: 1px dashed #0104cd;
        background: #e7f1ff;
        cursor: pointer;
    }
    .document-box.has-image,
    .document-box-small.has-image {
        border: 2px solid #28a745;
    }
    .document-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .upload-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s;
        z-index: 5;
    }
    .upload-overlay-small {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 30px;
        height: 30px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }
    .document-box:hover .upload-overlay,
    .document-box-small:hover .upload-overlay {
        opacity: 1;
    }
    .upload-overlay i {
        color: white;
        font-size: 20px;
    }
    .upload-overlay-small i {
        color: #0d6efd;
        font-size: 16px;
    }
    .file-input-hidden {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 20;
    }
    .gallery-count {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        z-index: 5;
    }
    .schedule-item,
    .activity-item {
        transition: background 0.2s;
    }
    .schedule-item:hover,
    .activity-item:hover {
        background: #f8f9fa;
    }
    .call-icon,
    .whatsapp-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        text-decoration: none;
        transition: all 0.2s;
    }
    .call-icon {
        background: #e3f2fd;
        color: #1976d2;
    }
    .whatsapp-icon {
        background: #e8f5e9;
        color: #25d366;
    }
    .call-icon:hover,
    .whatsapp-icon:hover {
        transform: scale(1.1);
    }
    .cursor-pointer {
        cursor: pointer !important;
    }
    .edit-section-trigger {
        padding: 4px 8px;
        border-radius: 4px;
        transition: all 0.2s;
    }
    .form-control-sm,
    .form-select-sm {
        font-size: 13px;
        padding: 6px 10px;
    }
    .form-label {
        font-size: 12px;
        margin-bottom: 2px;
    }
    .table-sm td,
    .table-sm th {
        padding: 0.5rem;
        font-size: 13px;
    }
    .remarks-content {
        font-size: 14px;
        line-height: 1.5;
    }
    #driverPhotoModal .modal-body,
    #carPhotoModal .modal-body {
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #000;
    }
    #driverPhotoModal .modal-body img,
    #carPhotoModal .modal-body img {
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
    }
    .modal-body img {
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
    }
    .image-wrapper {
        width: 100%;
        height: 100px;
        overflow: hidden;
        position: relative;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
        cursor: pointer;
    }
    .gallery-count {
        position: absolute;
        bottom: 8px;
        right: 8px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
    }
    .offcanvas-body {
        font-size: 14px;
    }
    .card-heading {
        font-size: 12px;
        font-weight: 600;
        background: #adb1b64d;
        padding: 6px 10px;
        border-radius: 6px;
    }
    .verified-star {
        width: 20px;
        height: 20px;
        background: #149604;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-left: 6px;
        clip-path: polygon(50% 0%, 61% 10%, 75% 6%, 82% 18%, 95% 22%,
                90% 35%, 100% 50%, 90% 65%, 95% 78%, 82% 82%,
                75% 94%, 61% 90%, 50% 100%, 39% 90%, 25% 94%,
                18% 82%, 5% 78%, 10% 65%, 0% 50%, 10% 35%,
                5% 22%, 18% 18%, 25% 6%, 39% 10%);
    }
    .verified-star i {
        color: white;
        font-size: 10px;
    }
    .jobs-scroll {
        max-height: 290px;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 4px;
    }
    .jobs-scroll::-webkit-scrollbar {
        width: 5px;
    }
    .jobs-scroll::-webkit-scrollbar-thumb {
        background: #cfd6e4;
        border-radius: 10px;
    }
    @media (max-width: 768px) {
        .document-box-small {
            width: calc(50% - 5px);
        }
    }
    .upload-loading {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }
    .upload-loading i {
        font-size: 22px;
        color: #fff;
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
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#groupDriversTab">
                        Group Drivers
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
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#expiredJobs">
                        Expired Jobs
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#completedJobs">
                        Completed Jobs
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#carpooling">
                        Go Car Pooling
                    </button>
                </li>
            </ul>
            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="jobsTab">
                    <div class="row g-3">
                        <div class="col-lg-4 p-0">
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
                                    <div class="row align-items-center mb-2">
                                        <div class="col-2">
                                            <div class="dropdown w-100">
                                                <button class="btn btn-sm btn-light border w-100 py-1 d-flex align-items-center justify-content-center gap-1" type="button" id="jobFilterBtn" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-filter text-warning"></i></button>
                                                <ul class="dropdown-menu dropdown-menu-start shadow-sm w-100">
                                                    <li><a class="dropdown-item active" href="#" data-filter="all"><i class="fa fa-list me-2 text-secondary"></i>Select Filter Type</a></li>
                                                    <li><a class="dropdown-item" href="#" data-filter="booked"><i class="fa fa-check-circle me-2 text-success"></i>Booked (Datewise)</a></li>
                                                    <li><a class="dropdown-item" href="#" data-filter="pickup"><i class="fa fa-map-marker-alt me-2 text-primary"></i>Pickup (Datewise)</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-2 px-1">
                                            <select id="appSourceFilter_current" class="form-select form-select-sm" style="font-size:12px;">
                                                <option value="all">All</option>
                                                <option value="Driver App">Driver App</option>
                                                <option value="Customer App">Customer App</option>
                                                <option value="Scheduled">Scheduled</option>
                                                <option value="Website Job">Website</option>
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" id="jobDateRange" class="form-control form-control-sm text-center py-0 px-1" placeholder="Select Date" readonly style="cursor:pointer;">
                                        </div>
                                        <div class="col-4 p-0">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-light border-end-0"><i class="fa fa-search text-dark"></i></span>
                                                <input type="text" class="form-control border-start-0 p-0" id="jobSearch" placeholder="Search jobs...">
                                            </div>
                                        </div>
                                        <div class="col-1 text-end d-flex justify-content-end gap-2">
                                            <button class="btn btn-sm btn-success createJobBtn"><i class="fa fa-plus me-1"></i></button>
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
                                <div class="col-lg-8 d-none" id="leftPanel">
                                    <!-- INCOMING BIDS -->
                                    <div id="incomingBidsPanel" class="d-none">
                                        <div class="bid-panel">
                                            <div class="bid-header d-flex align-items-center justify-content-between">
                                                <a href="javascript:void(0)"
                                                    class="backToMap text-decoration-none text-dark small" style="white-space: nowrap; flex-shrink: 0;">
                                                    <i class="fa fa-arrow-left me-1"></i> Back
                                                </a>
                                                <span class="fw-semibold mx-2 text-center" id="incomingBidsTitle" style="white-space: nowrap; flex-shrink: 0;">Incoming Bids</span>
                                                <span class="text-info small best_price" style="white-space: nowrap; flex-shrink: 0;">
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
                                                <span class="fw-semibold" id="availableDriversTitle">Available Drivers</span>
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
                                                <div style="position: absolute; top: 15px; left: 55px; z-index: 1000; display: flex; gap: 10px; align-items: start;">
                                                    <div style="position: relative; width: 300px; flex-shrink: 0;">
                                                        <div class="input-group bg-white" style="border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.15); overflow: hidden; height: 38px;">
                                                            <span class="input-group-text bg-white border-0 text-secondary" style="padding-left: 12px; padding-right: 5px;">
                                                                <i class="fa fa-search"></i>
                                                            </span>
                                                            <input type="text" id="mapDriverSearch" class="form-control border-0 shadow-none h-100" placeholder="Search Driver by Name or Phone" autocomplete="off" style="font-size: 13px; padding-left: 5px; outline: none; box-shadow: none;">
                                                        </div>
                                                        <div id="mapDriverSearchResults" class="list-group position-absolute w-100 d-none" style="max-height: 350px; overflow-y: auto; top: 100%; left: 0; background: white; border-radius: 6px; margin-top: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); border: 1px solid #dee2e6;"></div>
                                                    </div>
                                                    <div id="historyControls" class="bg-white shadow-sm" style="border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.15); overflow: hidden; display: flex; align-items: center;">
                                                        <div class="input-group" style="height: 38px;">
                                                            <span class="input-group-text bg-white border-0 text-primary">
                                                                <i class="fa fa-calendar-alt"></i>
                                                            </span>
                                                            <input type="text" id="historyDateRange" class="form-control border-0 shadow-none fw-semibold h-100" placeholder="Select Date & Time Range" style="font-size: 12px; width: 250px; cursor: pointer; background: #fff; padding-left: 0; outline: none; box-shadow: none;" readonly>
                                                        </div>
                                                    </div>
                                                </div>
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
                                            <div class="card mb-2 d-none" id="ownerDetailsCard">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <strong><i class="fa fa-id-card me-1 text-danger"></i> Passenger
                                                            Details</strong>
                                                        <a href="javascript:void(0)"
                                                            class="backToMap text-decoration-none text-dark small">
                                                            <i class="fa fa-arrow-left me-1"></i> Back
                                                        </a>
                                                    </div>
                                                    <div class="d-flex align-items-center mt-3">
                                                        <img src="/assets/images/driver.png" alt="Customer Photo"
                                                            class="rounded-circle me-3 cursor-pointer"
                                                            style="width:60px;height:60px;object-fit:cover;"
                                                            data-bs-toggle="modal" data-bs-target="#driverPhotoModal">
                                                        <div class="w-100">
                                                            <div
                                                                class="d-flex justify-content-between align-items-center">
                                                                <div class="d-flex align-items-center gap-2"
                                                                    style="font-size:14px">
                                                                    <span>Venkatesan</span>
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
                                                                <span class="d-none"><i
                                                                        class="fa fa-check-circle text-success me-1"></i>
                                                                    KYC Verified</span>
                                                                <span class="c_mobile"><i class="fa fa-phone"></i>
                                                                    91987654321</span>
                                                                <span class="c_address"><i class="fa fa-map-marker-alt text-danger me-1"></i>Chennai, Tamil Nadu</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-1 small text-muted c_created_at">
                                                        <i class="fa fa-user-clock me-1"></i>Account Created: <strong>15
                                                            Mar 2022</strong>
                                                    </div>
                                                    <hr class="my-3">
                                                    <div class="mt-3 p-3 rounded d-none">
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
                                        </div>
                                    </div>
                                </div>
                                <div class="card d-none" id="jobDetailsPanel">
                                    <div class="card-header d-flex justify-content-between align-items-center py-3">
                                        <div class="fw-semibold">
                                            <i class="fa fa-edit me-1 text-warning"></i>
                                            Job Details
                                        </div>
                                        <a href="javascript:void(0)"
                                            class="backToMap text-decoration-none text-dark small">
                                            <i class="fa fa-arrow-left me-1"></i> Back
                                        </a>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="jobDetailsContent"></div>
                                    </div>
                                    <div class="card-footer bg-white border-0 d-flex justify-content-end gap-3 pt-3 pb-4 pe-4">
                                        <button type="button" class="btn btn-sm text-white fw-medium px-4 backToMap" style="background-color: #38bdf8; border-radius: 4px; box-shadow: none;">
                                            Cancel
                                        </button>
                                        <button type="button" class="btn btn-sm text-white fw-medium px-4" id="confirmBookingBtn" style="background-color: #14b8a6; border-radius: 4px; box-shadow: none;">
                                            Create Job
                                        </button>
                                        <button type="button" class="btn btn-sm btn-info text-white fw-medium px-4 d-none" id="updateJobBtn">
                                            <i class="fa fa-save me-1"></i> Update Job
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="carpooling">
                    <ul class="nav nav-tabs mb-3 border-bottom-0 gap-2" id="carpoolStatusTabs" style="border: none;">
    <li class="nav-item">
        <button class="nav-link active fw-bold carpool-status-tab px-4 shadow-sm border-0 text-primary" data-tab="current" style="border-radius: 8px 8px 0 0; background-color: #fff;">
            <i class="fa fa-car-side me-2"></i>Current Go Car Pool Jobs
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold text-muted carpool-status-tab px-4 shadow-sm border-0" data-tab="completed" style="border-radius: 8px 8px 0 0; background-color: #f8f9fa;">
            <i class="fa fa-check-circle me-2"></i>Completed Go Car Pool Jobs
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold text-muted carpool-status-tab px-4 shadow-sm border-0" data-tab="cancelled" style="border-radius: 8px 8px 0 0; background-color: #f8f9fa;">
            <i class="fa fa-times-circle me-2"></i>Cancelled Go Car Pool Jobs
        </button>
    </li>
</ul>
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header bg-white py-3">
                            
                            <div class="row g-3 align-items-center">
                                
                                <div class="col-md-3">
                                    <h5 class="mb-0 fw-bold text-dark"><i class="fa fa-car-side text-primary me-2"></i>Go Carpool Jobs</h5>
                                </div>
                                <div class="col-md-9">
                                    <div class="row g-2 justify-content-end">
                                        <div class="col-md-3">
                                            <select id="carpoolFilterType" class="form-select form-select-sm border-1 shadow-none fw-semibold text-secondary">
                                                <option value="all" selected>All Pools</option>
                                                <option value="public">Public</option>
                                                <option value="private">Private</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select id="carpoolDateFilterType" class="form-select form-select-sm border-1 shadow-none fw-semibold">
                                                <option value="created_at" selected>Created Date</option>
                                                <option value="pickup">Pickup Date</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-light border-end-0"><i class="fa fa-calendar-alt text-muted"></i></span>
                                                <input type="text" id="carpoolDateRange" class="form-control border-start-0 text-center fw-bold shadow-none" placeholder="Select Date" readonly style="cursor:pointer; background-color: #fff;">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-light border-end-0"><i class="fa fa-search text-muted"></i></span>
                                                <input type="text" id="searchCarpool" class="form-control border-start-0 shadow-none" placeholder="Search job, name, city...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex gap-2">
                                    <span class="badge bg-success bg-opacity-10  border border-success px-3 py-2 rounded-pill" id="publicPoolCount">Public Pools</span>
                                    <span class="badge bg-secondary bg-opacity-10  border border-secondary px-3 py-2 rounded-pill" id="privatePoolCount">Private Pools</span>
                                </div>
                                <div class="border border-2 border-dark text-dark fw-bold px-4 py-1" style="font-size: 14px; border-radius: 4px;" id="totalPoolCount">
                                    Total Jobs: 0
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="carpoolLoader" class="text-center py-5" style="display:none">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
                        <div class="mt-3 small text-muted fw-bold">Fetching Carpool Jobs...</div>
                    </div>
                    <div id="carpoolEmpty" class="text-center py-5 d-none bg-white  border border-light">
                        <i class="fa fa-folder-open text-muted opacity-25 mb-3" style="font-size: 4rem;"></i>
                        <h6 class="text-secondary fw-semibold">No Carpool Jobs Found</h6>
                        <p class="text-muted small">Try adjusting your search or date filters.</p>
                    </div>
                    <div class="row g-3" id="carpoolJobsList">
                    </div>
                </div>
                <div class="modal fade" id="carpoolRequestsModal" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-light py-3 border-bottom-0">
                                <h6 class="modal-title fw-bold text-dark"><i class="fa fa-envelope-open-text text-primary me-2"></i>Invitations & Requests (<span id="reqModalJobNo"></span>)</h6>
                                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-0 bg-light" style="max-height: 500px; overflow-y: auto;">
                                <div class="list-group list-group-flush" id="carpoolRequestsList">
                                </div>
                            </div>
                            <div class="modal-footer py-2 bg-white border-top-0">
                                <button class="btn btn-secondary btn-sm px-4 fw-semibold shadow-sm" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="driversTab">
                    <div class="row g-3">
                        <div class="col-lg-3">
                            <div class="card h-100">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong>
                                            Drivers
                                            <span class="badge bg-secondary ms-1" id="driverCount">0</span>
                                        </strong>
                                        <button type="button" class="btn btn-sm text-info border-info px-3 py-1" id="copyDriversListBtn" style="border-radius: 4px; box-shadow: none; background: transparent;">
                                            Copy
                                        </button>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <select id="profileFieldFilter" multiple placeholder="Select profile fields...">
                                            <option value="complete_verified">Complete Verified (Doc + Vehicle)</option>
                                            <option value="state">State</option>
                                            <option value="districts_id">District</option>
                                            <!--<option value="images_count">Above 4 Images</option>-->
                                            <option value="dl_expiry">Driving Licence Expiry</option>
                                            <option value="vehicle_type">Vehicle Type</option>
                                            <option value="fuel_type">Fuel Type</option>
                                            <option value="exp">Driver Experience</option>
                                            <option value="seaters">Seat Capacity</option>
                                            <option value="per_km">Price Per KM</option>
                                            <option value="extra_per_km">Extra Price Per KM</option>
                                            <option value="per_hour">Price Per Hour</option>
                                            <option value="per_day">Price Per Day</option>
                                            <option value="upiID">UPI ID</option>
                                            <option value="reviews">Reviews</option>
                                        </select>
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
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9">
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
                    </div>
                </div>
                <div class="tab-pane fade" id="groupDriversTab">
                    <div class="row g-3">
                        <div class="col-lg-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header bg-white pb-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0 fw-bold text-secondary">Group Drivers <span class="badge bg-secondary ms-1" id="gdCount">0</span></h6>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-12">
                                            <select class="form-select form-select-sm shadow-none" id="gdFilterDistrict">
                                                <option value="">All Districts</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select class="form-select form-select-sm shadow-none" id="gdFilterCab">
                                                <option value="">Select</option>
                                                <?php
                                                $cab_query = mysqli_query($con, "SELECT id, name FROM cab_types ORDER BY name ASC");
                                                if ($cab_query) {
                                                    while ($cab = mysqli_fetch_assoc($cab_query)) {
                                                        $cabName = htmlspecialchars($cab['name']);
                                                        echo "<option value='{$cabName}'>{$cabName}</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <input type="text" id="gdDateRange" class="form-control form-control-sm shadow-none text-center" placeholder="Last Updated Location" readonly style="cursor:pointer; background-color: #fff;">
                                        </div>
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-muted"></i></span>
                                        <input type="text" id="gdSearch" class="form-control border-start-0 shadow-none" placeholder="Search driver name or phone...">
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-1">
                                        <div class="form-check mb-0 d-flex align-items-center">
                                            <input class="form-check-input cursor-pointer shadow-none m-0 me-2" type="checkbox" id="gdSelectAll" style="width: 1.2em; height: 1.2em;">
                                            <label class="form-check-label small fw-bold cursor-pointer text-dark" for="gdSelectAll" style="padding-top: 2px; margin-left: 23px;">Select All</label>
                                        </div>
                                        <button class="btn btn-sm btn-success px-3 py-1 shadow-sm" id="gdSendPushBtn" disabled>
                                            <i class="fa fa-paper-plane"></i> Send Push (<span id="gdSelectedCount">0</span>)
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div id="gdLoader" class="text-center py-4 d-none">
                                        <div class="spinner-border text-primary" role="status"></div>
                                        <div class="mt-2 small text-muted fw-semibold">Loading Drivers...</div>
                                    </div>
                                    <div class="list-group list-group-flush" id="gdDriverList" style="max-height: 600px; overflow-y: auto; overflow-x: hidden;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body p-0 position-relative">
                                    <div id="groupDriversMap" style="height: 100%; min-height: 750px; width: 100%; border-radius: 6px; z-index: 1;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="scheduled">
                    <div class="card shadow-sm border-0">
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
                    <div class="card shadow-sm border-0 mb-2">
                        <div class="card-header bg-light py-2">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-3">
                                    <select id="appSourceFilter_cancelled" class="form-select form-select-sm border-0 shadow-none bg-transparent fw-semibold text-primary">
                                        <option value="all" selected>All Source</option>
                                        <option value="Driver App">Driver App</option>
                                        <option value="Customer App">Customer App</option>
                                        <option value="Scheduled">Scheduled</option>
                                        <option value="Website Job">Website</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select id="cancelledFilterType" class="form-select form-select-sm border-0 shadow-none bg-transparent fw-semibold">
                                        <option value="created" selected>Created Date</option>
                                        <option value="pickup">Pickup Date</option>
                                    </select>
                                </div>
                                <div class="col-md-6 d-flex align-items-center gap-2">
                                    <div class="input-group input-group-sm w-100">
                                        <span class="input-group-text bg-white border-end-0"><i class="fa fa-calendar-alt"></i></span>
                                        <input type="text" id="cancelledDateRange" class="form-control border-start-0 text-center fw-bold" readonly style="cursor:pointer; background-color: #fff;">
                                    </div>
                                    <span class="badge bg-danger shadow-sm py-2 px-3" style="font-size: 13px; white-space: nowrap;" id="cancelledCountBadge">0 Jobs</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="cancelledLoader" class="text-center py-4" style="display:none">
                        <div class="spinner-border text-danger" role="status"></div>
                    </div>
                    <div id="cancelledJobsList"></div>
                </div>
                <div class="tab-pane fade" id="expiredJobs">
                    <div class="card shadow-sm border-0 mb-2">
                        <div class="card-header bg-light py-2">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-3">
                                    <select id="appSourceFilter_expired" class="form-select form-select-sm border-0 shadow-none bg-transparent fw-semibold text-primary">
                                        <option value="all" selected>All Source</option>
                                        <option value="Driver App">Driver App</option>
                                        <option value="Customer App">Customer App</option>
                                        <option value="Scheduled">Scheduled</option>
                                        <option value="Website Job">Website</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select id="expiredFilterType" class="form-select form-select-sm border-0 shadow-none bg-transparent fw-semibold">
                                        <option value="created" selected>Created Date</option>
                                        <option value="pickup">Pickup Date</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white border-end-0"><i class="fa fa-calendar-alt"></i></span>
                                        <input type="text" id="expiredDateRange" class="form-control border-start-0 text-center fw-bold" readonly style="cursor:pointer; background-color: #fff;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="expiredLoader" class="text-center py-4" style="display:none">
                        <div class="spinner-border text-secondary" role="status"></div>
                    </div>
                    <div id="expiredJobsList"></div>
                </div>
                <div class="tab-pane fade" id="completedJobs">
                    <div class="card shadow-sm border-0 mb-2">
                        <div class="card-header bg-light py-2">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-3">
                                    <select id="appSourceFilter_completed" class="form-select form-select-sm border-0 shadow-none bg-transparent fw-semibold text-primary">
                                        <option value="all" selected>All Source</option>
                                        <option value="Driver App">Driver App</option>
                                        <option value="Customer App">Customer App</option>
                                        <option value="Scheduled">Scheduled</option>
                                        <option value="Website Job">Website</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select id="completedFilterType" class="form-select form-select-sm border-0 shadow-none bg-transparent fw-semibold">
                                        <option value="created" selected>Created Date</option>
                                        <option value="pickup">Pickup Date</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white border-end-0"><i class="fa fa-calendar-alt"></i></span>
                                        <input type="text" id="completedDateRange" class="form-control border-start-0 text-center fw-bold" readonly style="cursor:pointer; background-color: #fff;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="completedLoader" class="text-center py-4" style="display:none">
                        <div class="spinner-border text-success" role="status"></div>
                    </div>
                    <div id="completedJobsList"></div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="autoScheduleModal" tabindex="-1" style="z-index: 106000 !important;" aria-labelledby="autoScheduleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" style="z-index: 106001 !important;">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-light">
                        <h6 class="modal-title fw-bold" id="autoScheduleModalLabel">
                            <i class="fa fa-calendar-plus text-primary me-2"></i>Re-Schedule (Last 5 Routes)
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0 align-middle">
                                <thead class="table-light text-muted" style="font-size: 12px;">
                                    <tr>
                                        <th>From Place</th>
                                        <th>To Place</th>
                                        <th>New Pickup Date & Time</th>
                                        <th width="120">Price (₹)</th>
                                        <th width="50" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="autoScheduleTbody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 bg-light py-2">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-sm btn-success" id="confirmAutoScheduleBtn">
                            <i class="fa fa-save me-1"></i> Save Schedules
                        </button>
                    </div>
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
            <div class="modal-header py-2 bg-light">
                <h6 class="modal-title d-flex align-items-center gap-2">
                    <i class="fa fa-flag text-danger"></i>
                    Report Details
                </h6>
                <button type="button" class="border-0 bg-transparent text-dark" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body small">
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
            <div class="modal-header py-1 px-2">
                <h6 class="modal-title">Driver Photo</h6>
                <button type="button" class="btn p-0" data-bs-dismiss="modal">
                    <i class="fa fa-times text-dark"></i>
                </button>
            </div>
            <div class="modal-body p-2 text-center">
                <img src="/assets/images/driver.png" class="img-fluid " style="max-height:250px;" alt="Driver Zoom">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="carPhotoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:300px;">
        <div class="modal-content">
            <div class="modal-header py-1 px-2">
                <h6 class="modal-title">Car Photos</h6>
                <button type="button" class="btn p-0" data-bs-dismiss="modal">
                    <i class="fa fa-times text-dark"></i>
                </button>
            </div>
            <div class="modal-body p-2">
                <div id="carImageCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-touch="true">
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
<div class="modal fade" id="removeDriverModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2 align-items-center">
                <h6 class="modal-title d-flex align-items-center gap-2">
                    <i class="fa fa-user-times text-danger"></i>
                    Remove Driver
                </h6>
                <button type="button" class="border-0 bg-transparent text-dark" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body text-center small">
                <p class="mb-0">
                    Are you sure you want to
                    <strong class="text-danger">remove this driver</strong> from the job?
                </p>
            </div>
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
<div class="offcanvas offcanvas-end" tabindex="-1" id="driverEditDrawer">
    <div class="offcanvas-header border-bottom d-flex justify-content-between p-3 bg-white">
        <div class="d-flex align-items-center gap-3">
            <h5 class="mb-0 fw-bold">
                <i class="fa fa-id-card me-2 text-danger"></i> Edit Driver
            </h5>
            <span class="badge bg-success rounded-pill px-3 py-2" id="profileScoreBadge">
                <i class="fa fa-check-circle me-1"></i>85% Complete
            </span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="position-relative">
                <div class="d-flex align-items-center bg-white border rounded-pill px-3 py-1 shadow-sm" style="width: 280px; border-color: #e2e6f0 !important;">
                    <i class="fa fa-search text-muted me-2" style="font-size: 13px;"></i>
                    <input type="text" id="globalDriverSearch" class="form-control form-control-sm border-0 bg-transparent shadow-none px-0" placeholder="Search drivers" autocomplete="new-password" style="font-size: 13px;">
                </div>
                <div id="globalDriverSearchResults" class="list-group position-absolute w-100 d-none shadow-lg border bg-white" style="top: 100%; left: 0; max-height: 350px; overflow-y: auto; margin-top: 8px; z-index: 99999; border-radius: 8px;">
                </div>
            </div>
            <button class="btn btn-light btn-sm border" type="button" id="closeDrawerBtn" title="Close">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>
    <div class="offcanvas-body p-3 bg-light">
        <div id="driverEditLoader" class="calendar-overlay d-none">
            <div class="calendar-spinner"></div>
        </div>
        <div class="row g-4">
            <!-- ================= LEFT COLUMN - DRIVER PROFILE ================= -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">
                            <div class="d-flex align-items-center">
                                <h6 class="mb-0 fw-bold card-heading" style="font-size: 14px;">
                                    <i class="fa fa-user-circle text-danger me-2"></i>Driver Profile
                                    <span id="status-driver-profile" class="ms-2"></span>
                                </h6>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-info btn-sm" onclick="loadEditLogs()">
                                    <i class="fa fa-history"></i>
                                </button>
                                <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger" data-section="driver-profile" title="Edit"></i>
                            </div>
                        </div>
                        <div class="view-mode" id="view-driver-profile">
                            <div class="d-flex align-items-center">
                                <div id="driverzoom" class="position-relative flex-shrink-0 me-3" style="width: 70px;">
                                    <div class="image-wrapper rounded-circle shadow-sm" style="width: 70px; height: 70px; overflow: hidden; border: 2px solid #fff;">
                                        <img src="assets/images/driver.png" class="driver-photo viewable-image" style="width:100%; height:100%; object-fit:cover;">
                                    </div>
                                    <div class="" data-target="driver-photo" style="bottom: 0px; right: 0px; width: 25px; height: 25px; cursor: pointer;">
                                    </div>
                                </div>
                                <div class="flex-grow-1" style="min-width: 0;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-truncate" style="font-size: 14px;">
                                            <i class="fa fa-user text-primary me-1"></i>
                                            <span class="display-field fw-bold" data-field="name">Venkatesan</span>
                                        </div>
                                        <div class="d-flex gap-2 flex-shrink-0">
                                            <a href="tel:9876543210" class="call-icon text-decoration-none" title="Call">
                                                <i class="fa fa-phone d-flex align-items-center justify-content-center"></i>
                                            </a>
                                            <a href="https://wa.me/919876543210" class="whatsapp-icon text-decoration-none" title="WhatsApp">
                                                <i class="fab fa-whatsapp d-flex align-items-center justify-content-center"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="text-truncate mb-1" style="font-size: 12px;">
                                        <i class="fa fa-envelope text-danger me-1 text-center" style="width: 14px;"></i>
                                        <span class="display-field" data-field="email" title="driver@mail.com">driver@mail.com</span>
                                    </div>
                                    <div class="row g-0" style="font-size: 12px; margin-left:-12px;">
                                        <div class="col-7 text-truncate">
                                            <i class="fa fa-phone text-success me-1 text-center" style="width: 14px;"></i>
                                            <span class="display-field" data-field="mobile">9876543210</span>
                                        </div>
                                        <div class="col-5 text-truncate">
                                            <i class="fa fa-briefcase text-dark me-1 text-center" style="width: 14px;"></i>
                                            <span id="display_experience">5 Yrs</span>
                                        </div>
                                        <div class="col-12 text-truncate d-none">
                                            <i class="fa fa-calendar text-secondary me-1 text-center" style="width: 14px;"></i>
                                            <span class="display-field" data-field="age">35</span> Yrs
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-1" style="font-size: 12px;">
                                <div class="col-12 text-truncate" title="Address">
                                    <i class="fa fa-address-card text-danger me-1 text-center" style="width: 14px;"></i>
                                    <span class="display-field" data-field="address">123, Anna Salai, T. Nagar, Chennai - 600017</span>
                                </div>
                                <div class="col-7 text-truncate">
                                    <i class="fa fa-globe text-info me-1 text-center" style="width: 14px;"></i>
                                    <span class="display-field" data-field="state">Tamil Nadu</span>
                                </div>
                                <div class="col-5 text-truncate">
                                    <i class="fa fa-map-marker text-info me-1 text-center" style="width: 14px;"></i>
                                    <span class="display-field" data-field="city">Chennai</span>
                                </div>
                                <div class="col-12 text-truncate mt-1">
                                    <i class="fa fa-language text-dark me-1 text-center" style="width: 14px;"></i>
                                    <strong>Languages:</strong>
                                    <span class="display-field" data-field="languages">Tamil, English, Telugu</span>
                                </div>
                            </div>
                        </div>
                        <div class="edit-mode" id="edit-driver-profile" style="display: none;">
                            <div class="row g-1 small">
                                <div class="col-7">
                                    <label class="form-label fw-semibold mb-0" style="font-size: 11px;">Name</label>
                                    <input type="text" class="form-control form-control-sm edit-field py-1" oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g,'').slice(0,65)" id="edit_name" data-field="name" value="Venkatesan">
                                </div>
                                <div class="col-5">
                                    <label class="form-label fw-semibold mb-0" style="font-size: 11px;">Mobile</label>
                                    <input type="text" class="form-control form-control-sm edit-field py-1" id="edit_mobile" oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,12)" data-field="mobile" value="9876543210">
                                </div>
                                <div class="col-7">
                                    <label class="form-label fw-semibold mb-0" style="font-size: 11px;">Email</label>
                                    <input type="email" class="form-control form-control-sm edit-field py-1" id="edit_email" oninput="this.value = this.value.replace(/[^a-zA-Z0-9@._-]/g,'').slice(0,75)" value="">
                                </div>
                                <div class="col-5 d-none">
                                    <label class="form-label fw-semibold mb-0" style="font-size: 11px;">Age</label>
                                    <input type="text" class="form-control form-control-sm edit-field py-1" oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,3)" id="edit_age" data-field="age" value="35">
                                </div>
                                <div class="col-5">
                                    <label class="form-label fw-semibold mb-0" style="font-size: 11px;">Exp (Yrs)*</label>
                                    <input type="number" class="form-control form-control-sm py-1" id="edit_experience" oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,3)" value="5">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold mb-0" style="font-size: 11px;">Languages</label>
                                    <div class="" style="background: #fff;">
                                        <select name="language[]" class="form-control form-select-sm edit-field py-1" id="edit_languages" multiple>
                                            <?php
                                            $languages = ["Tamil", "English", "Hindi", "Telugu", "Malayalam", "Kannada", "Marathi"];
                                            $selectedLangs = (isset($selectedLanguages) && is_array($selectedLanguages)) ? $selectedLanguages : [];
                                            foreach ($languages as $lang) {
                                                $selected = in_array($lang, $selectedLangs) ? 'selected' : '';
                                                echo "<option value='" . htmlspecialchars($lang) . "' $selected>" . htmlspecialchars($lang) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-0" style="font-size: 11px;">State</label>
                                    <select class="form-control form-control-sm py-1" id="edit_state" data-field="state" placeholder="Select State">
                                        <option value="">Select State</option>
                                        <?php
                                        $query = mysqli_query($con, "SELECT id, name FROM states WHERE country_code = 'IN'");
                                        while ($rows = mysqli_fetch_assoc($query)) {
                                            echo "<option value='" . htmlspecialchars($rows['name']) . "'>" . htmlspecialchars($rows['name']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-0" style="font-size: 11px;">District</label>
                                    <select class="form-control form-select form-select-sm py-1 edit-field" id="edit_districts_id" data-field="districts_id">
                                        <option value="">Select District</option>
                                        <?php
                                        $dist_query = mysqli_query($con, "SELECT id, district_name FROM districts");
                                        while ($dist = mysqli_fetch_assoc($dist_query)) {
                                            echo "<option value='" . htmlspecialchars($dist['id']) . "'>" . htmlspecialchars($dist['district_name']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold mb-0" style="font-size: 11px;">Address</label>
                                    <input type="text" class="form-control form-control-sm edit-field py-1" id="edit_address" oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s,.-]/g,'').slice(0,150)" data-field="address" value="123, Anna Salai, T. Nagar, Chennai - 600017">
                                </div>
                                <div class="col-12 mt-2 text-end">
                                    <button class="btn btn-sm btn-secondary cancel-section py-1 px-2" style="font-size: 11px;" data-section="driver-profile"><i class="fa fa-times me-1"></i>Cancel</button>
                                    <button class="btn btn-sm btn-success save-section py-1 px-2" style="font-size: 11px;" data-section="driver-profile"><i class="fa fa-save me-1"></i>Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0 fw-bold card-heading"><i class="fa fa-id-card text-danger me-2"></i>Aadhaar Card
                                <span id="status-aadhaar"></span>
                            </h6>
                            <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger" data-section="aadhaar"
                                title="Edit"></i>
                        </div>
                        <div class="view-mode" id="view-aadhaar">
                            <div class="row text-center g-3">
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">Aadhaar Front</h6>
                                    <div class="document-box-small viewable-image" id="aadhaarFront">
                                        <img src="assets/images/adharfront.jpeg" class="document-img"
                                            alt="Aadhaar Front">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">Aadhaar Back</h6>
                                    <div class="document-box-small viewable-image" id="aadhaarBack">
                                        <img src="assets/images/adharback.jpeg" class="document-img" alt="Aadhaar Back">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="edit-mode" id="edit-aadhaar" style="display: none;">
                            <div class="row g-2">
                                <div class="text-center mb-2 text-muted">
                                    Upload Aadhaar Images
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1">Front Side</label>
                                    <div class="document-box-small" id="edit-aadhaarFront" data-target="aadhaarFront">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRqkqkqkqkqkqkqkqkqkqkqkqkqkqkqkqk&s"
                                            class="document-img" alt="Aadhaar Front">
                                        <div class="upload-overlay">
                                            <i class="fa fa-camera text-white"></i>
                                            <input type="file" class="file-input-hidden" accept="image/*"
                                                data-target="aadhaarFront">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1">Back Side</label>
                                    <div class="document-box-small" id="edit-aadhaarBack" data-target="aadhaarBack">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSpSpSpSpSpSpSpSpSpSpSpSpSpSpSpSpS&s"
                                            class="document-img" alt="Aadhaar Back">
                                        <div class="upload-overlay">
                                            <i class="fa fa-camera text-white"></i>
                                            <input type="file" class="file-input-hidden" accept="image/*"
                                                data-target="aadhaarBack">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-2 text-end">
                                    <button class="btn btn-sm btn-success me-2 save-section-img" data-section="aadhaar"><i
                                            class="fa fa-save me-1"></i>Save</button>
                                    <button class="btn btn-sm btn-secondary cancel-section" data-section="aadhaar"><i
                                            class="fa fa-times me-1"></i>Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0 fw-bold card-heading">
                                <i class="fa fa-id-card text-danger me-2"></i>Driving License
                                <span id="status-license"></span>
                            </h6>
                            <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger" data-section="license"
                                title="Edit"></i>
                        </div>
                        <div class="view-mode" id="view-license">
                            <div class="row text-center g-3">
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">License Front</h6>
                                    <div class="document-box-small viewable-image" id="licenseFront">
                                        <img src="assets/images/lic-front.png" class="document-img" alt="License Front">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">License Back</h6>
                                    <div class="document-box-small viewable-image" id="licenseBack">
                                        <img src="assets/images/lic-back.png" class="document-img" alt="License Back">
                                    </div>
                                </div>
                                <div class="col-6 mt-1 text-start">
                                    <i class="fa fa-id-badge text-info me-1"></i>
                                    <strong>No:</strong>
                                    <span id="display_license_no">Licence No</span>
                                </div>
                                <div class="col-6 mt-1 text-start">
                                    <i class="fa fa-id-badge text-info me-1"></i>
                                    <strong>Type:</strong>
                                    <span id="display_license_type">LMV</span>
                                </div>
                                <div class="col-6 mt-1 text-start">
                                    <i class="fa fa-calendar text-warning me-1"></i>
                                    <strong>Expiry:</strong>
                                    <span id="display_license_expiry">12/2028</span>
                                </div>
                            </div>
                        </div>
                        <div class="edit-mode" id="edit-license" style="display: none;">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1">Front Side</label>
                                    <div class="document-box-small" id="edit-licenseFront" data-target="licenseFront">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTtTtTtTtTtTtTtTtTtTtTtTtTtTtTtTtT&s"
                                            class="document-img" alt="License Front">
                                        <div class="upload-overlay">
                                            <i class="fa fa-camera text-white"></i>
                                            <input type="file" class="file-input-hidden" accept="image/*"
                                                data-target="licenseFront">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1">Back Side</label>
                                    <div class="document-box-small" id="edit-licenseBack" data-target="licenseBack">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcUuUuUuUuUuUuUuUuUuUuUuUuUuUuUuUuU&s"
                                            class="document-img" alt="License Back">
                                        <div class="upload-overlay">
                                            <i class="fa fa-camera text-white"></i>
                                            <input type="file" class="file-input-hidden" accept="image/*"
                                                data-target="licenseBack">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1">License No</label>
                                    <input type="text" class="form-control form-control-sm edit-field"
                                        id="edit_license_no">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1">Date of Birth</label>
                                    <input type="date" class="form-control form-control-sm" id="edit_license_dob" max="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1">License Type</label>
                                    <select class="form-select form-select-sm" id="edit_license_type">
                                        <option value="LMV" selected>LMV</option>
                                        <option value="HMV">HMV</option>
                                        <option value="Transport">Transport</option>
                                        <option value="MCWG">MCWG</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1">Expiry Date</label>
                                    <input type="date" class="form-control form-control-sm edit-field" id="edit_license_expiry" min="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-12 mt-2 text-end">
                                    <button class="btn btn-sm btn-success me-2 save-section-img" data-section="license"><i
                                            class="fa fa-save me-1"></i>Save</button>
                                    <button class="btn btn-sm btn-secondary cancel-section" data-section="license"><i
                                            class="fa fa-times me-1"></i>Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ================= CENTER COLUMN - VEHICLE DETAILS ================= -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 fw-bold card-heading">
                                <i class="fa fa-car text-danger me-2"></i>Vehicle Details
                                <span id="status-vehicle"></span>
                            </h6>
                            <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger" data-section="vehicle" title="Edit"></i>
                        </div>
                        <div class="view-mode" id="view-vehicle">
                            <div class="row gx-2 align-items-center gap-3">
                                <div class="col-auto p-0">
                                    <div class="position-relative" style="width: 150px;">
                                        <div id="vehicle-gallery-container" class="shadow-sm" style="width: 100%; height: 80px; overflow: hidden; background: #f8f9fa; border-radius: 6px;">
                                            <img src="assets/images/carimage3.png" alt="Front View" id="main_vehicle_preview" class="cursor-pointer vehicle-photo" style="width: 100%; height: 100%; object-fit: cover;">
                                            <div style="width: 0; height: 0; overflow: hidden; opacity: 0;">
                                                <img src="" alt="Boot" id="gallery_boot_image">
                                                <img src="" alt="Extra 1" id="gallery_extra_image_1">
                                                <img src="" alt="Top View" id="gallery_car_top_view_image">
                                                <img src="" alt="Interior" id="gallery_interior_front_image">
                                                <img src="" alt="Special" id="gallery_special_features_image">
                                            </div>
                                        </div>
                                        <div class="upload-overlay-small" data-bs-toggle="modal" data-bs-target="#vehicleImagesModal" style="cursor: pointer; position: absolute; bottom: -6px; right: -6px; width: 26px; height: 26px; background: white; border-radius: 50%; box-shadow: 0 2px 5px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; z-index: 10;">
                                            <i class="fa fa-camera text-primary" style="font-size: 12px;"></i>
                                        </div>
                                        <input type="hidden" id="val_front_view_image_url" value="">
                                        <input type="hidden" id="val_boot_image_url" value="">
                                        <input type="hidden" id="val_extra_image_1_url" value="">
                                        <input type="hidden" id="val_car_top_view_image_url" value="">
                                        <input type="hidden" id="val_interior_front_image_url" value="">
                                        <input type="hidden" id="val_special_features_image_url" value="">
                                    </div>
                                </div>
                                <div class="col" style="min-width: 0;">
                                    <div class="mb-1 d-flex justify-content-start align-items-center text-truncate" style="line-height: 1.2;">
                                        <strong class="text-dark" style="font-size: 13px;" id="display_vehicle_type">Go Sedan</strong>
                                        <span class="text-muted" style="font-size: 11px;">(<span id="display_maker_model">Hyundai Aura 1.2 MT CNG</span>)</span>
                                        <span class="verified-star"><i class="fa fa-check"></i></span>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2 mb-2" style="font-size: 12px; line-height: 1;">
                                        <div class="text-nowrap"><i class="fa fa-users text-primary me-1"></i><span id="display_seating">4+1</span></div>
                                        <div class="text-nowrap"><i class="fa fa-gas-pump text-warning me-1"></i><span id="display_fuel">Petrol</span></div>
                                        <div class="text-nowrap"><i class="fa fa-suitcase text-secondary me-1"></i><span id="display_luggage">3</span></div>
                                    </div>
                                    <div class="d-flex flex-column gap-1" style="font-size: 11.5px; line-height: 1.2;">
                                        <div class="text-nowrap">
                                            <i class="fa fa-file text-info me-1" style="width: 12px; text-align: center;"></i>RC: <span id="display_rc" class="text-dark fw-medium">30 Nov 2026</span>
                                        </div>
                                        <div class="text-nowrap">
                                            <i class="fa fa-file text-danger me-1" style="width: 12px; text-align: center;"></i>IN: <span id="display_insurance" class="text-dark fw-medium">15 Aug 2027</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 g-1" id="simpleVehicleImages">
                                <div class="col-4 col-md-2 p-1">
                                    <div class="position-relative">
                                        <img src="assets/images/placeholder.png" class="img-fluid cursor-pointer vehicle-thumb" data-index="0" style="height:70px; width:100%; object-fit:cover; border:1px solid #dee2e6;">
                                        <span class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 text-white text-center small py-1" style="font-size:9px; border-radius:0 0 4px 4px;">Front</span>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2 p-1">
                                    <div class="position-relative">
                                        <img src="assets/images/placeholder.png" class="img-fluid cursor-pointer vehicle-thumb" data-index="1" style="height:70px; width:100%; object-fit:cover; border:1px solid #dee2e6;">
                                        <span class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 text-white text-center small py-1" style="font-size:9px; border-radius:0 0 4px 4px;">Boot</span>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2 p-1">
                                    <div class="position-relative">
                                        <img src="assets/images/placeholder.png" class="img-fluid cursor-pointer vehicle-thumb" data-index="2" style="height:70px; width:100%; object-fit:cover; border:1px solid #dee2e6;">
                                        <span class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 text-white text-center small py-1" style="font-size:9px; border-radius:0 0 4px 4px;">Extra 1</span>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2 p-1">
                                    <div class="position-relative">
                                        <img src="assets/images/placeholder.png" class="img-fluid cursor-pointer vehicle-thumb" data-index="3" style="height:70px; width:100%; object-fit:cover; border:1px solid #dee2e6;">
                                        <span class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 text-white text-center small py-1" style="font-size:9px; border-radius:0 0 4px 4px;">Top</span>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2 p-1">
                                    <div class="position-relative">
                                        <img src="assets/images/placeholder.png" class="img-fluid cursor-pointer vehicle-thumb" data-index="4" style="height:70px; width:100%; object-fit:cover; border:1px solid #dee2e6;">
                                        <span class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 text-white text-center small py-1" style="font-size:9px; border-radius:0 0 4px 4px;">Interior</span>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2 p-1">
                                    <div class="position-relative">
                                        <img src="assets/images/placeholder.png" class="img-fluid cursor-pointer vehicle-thumb" data-index="5" style="height:70px; width:100%; object-fit:cover; border:1px solid #dee2e6;">
                                        <span class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 text-white text-center small py-1" style="font-size:9px; border-radius:0 0 4px 4px;">Special</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="edit-mode mt-3" id="edit-vehicle" style="display:none;">
                            <div class="row g-2 small">
                                <div class="col-5">
                                    <label class="form-label fw-semibold mb-0 text-nowrap" style="font-size: 11px;">Vehicle Type</label>
                                    <select class="form-select form-select-sm py-1" id="edit_vehicle_type">
                                        <option value="">Select</option>
                                        <?php
                                        $cab_query = mysqli_query($con, "SELECT id, name FROM cab_types ORDER BY name ASC");
                                        if ($cab_query) {
                                            while ($cab = mysqli_fetch_assoc($cab_query)) {
                                                $cabName = htmlspecialchars($cab['name']);
                                                echo "<option value='{$cabName}'>{$cabName}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-7">
                                    <label class="form-label fw-semibold mb-0 text-nowrap" style="font-size: 11px;">Maker Model</label>
                                    <input type="text" class="form-control form-control-sm py-1" oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s]/g,'').slice(0,75)" id="edit_maker_model" value="Hyundai Aura 1.2">
                                </div>
                                <div class="col-5">
                                    <label class="form-label fw-semibold mb-0 text-nowrap" style="font-size: 11px;">Fuel Type</label>
                                    <select class="form-select form-select-sm py-1" id="edit_fuel">
                                        <option value="">Select</option>
                                        <option value="PETROL">PETROL</option>
                                        <option value="DIESEL">DIESEL</option>
                                        <option value="CNG">CNG</option>
                                        <option value="PETROL/CNG">PETROL/CNG</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label class="form-label fw-semibold mb-0 text-nowrap" style="font-size: 11px;">Seating</label>
                                    <input type="text" class="form-control form-control-sm py-1 px-1 text-center" oninput="this.value = this.value.replace(/[^0-9+]/g,'').slice(0,3)" id="edit_seating" value="4+1" readonly>
                                </div>
                                <div class="col-4">
                                    <label class="form-label fw-semibold mb-0 text-nowrap" style="font-size: 11px;">Luggage</label>
                                    <input type="text" class="form-control form-control-sm py-1 px-1 text-center" oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,3)" id="edit_luggage" value="2">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-0 text-nowrap" style="font-size: 11px;">RC Valid Until</label>
                                    <input type="date" class="form-control form-control-sm py-1 px-1" id="edit_rc" min="<?php echo $today; ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-0 text-nowrap" style="font-size: 11px;">Insurance Valid</label>
                                    <input type="date" class="form-control form-control-sm py-1 px-1" id="edit_insurance" min="<?php echo $today; ?>">
                                </div>
                                <div class="col-12 mt-2 text-end">
                                    <button class="btn btn-sm btn-secondary cancel-section py-1 px-2" style="font-size: 11px;" data-section="vehicle"><i class="fa fa-times me-1"></i>Cancel</button>
                                    <button class="btn btn-sm btn-success save-section py-1 px-2" style="font-size: 11px;" data-section="vehicle"><i class="fa fa-save me-1"></i>Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0 fw-bold card-heading">
                                <i class="fa fa-file text-danger me-2"></i>Vehicle Documents
                                <span id="status-documents"></span>
                            </h6>
                            <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger" data-section="documents"
                                title="Edit"></i>
                        </div>
                        <div class="view-mode" id="view-documents">
                            <div class="row text-center g-3">
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">RC Front</h6>
                                    <div class="document-box-small viewable-image" id="rcDocumentFront">
                                        <img src="assets/images/lic-front.png" class="document-img" alt="RC">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">RC Back</h6>
                                    <div class="document-box-small viewable-image" id="rcDocumentBack">
                                        <img src="assets/images/lic-back.png" class="document-img" alt="RC">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">PUC</h6>
                                    <div class="document-box-small viewable-image" id="pucDocument">
                                        <img src="assets/images/adharfront.jpeg" class="document-img" alt="PUC">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">Insurance</h6>
                                    <div class="document-box-small viewable-image" id="insuranceDocument">
                                        <img src="assets/images/adharfront.jpeg" class="document-img" alt="Insurance">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="edit-mode" id="edit-documents" style="display: none;">
                            <div class="row text-center g-3">
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">RC Front</h6>
                                    <div class="document-box-small" id="edit-rcDocumentFront" data-target="rcDocumentFront">
                                        <img class="document-img" alt="RC Front">
                                        <div class="upload-overlay">
                                            <i class="fa fa-camera text-white"></i>
                                            <input type="file" class="file-input-hidden" accept="image/*" data-target="rcDocumentFront">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">RC Back</h6>
                                    <div class="document-box-small" id="edit-rcDocumentBack" data-target="rcDocumentBack">
                                        <img class="document-img" alt="RC Back">
                                        <div class="upload-overlay">
                                            <i class="fa fa-camera text-white"></i>
                                            <input type="file" class="file-input-hidden" accept="image/*" data-target="rcDocumentBack">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">RC Number</label>
                                    <input type="text" class="form-control form-control-sm" id="edit_rc_number">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">RC Expiry</label>
                                    <input type="date" class="form-control form-control-sm" id="edit_rc_expiry" min="<?php echo $today; ?>">
                                </div>
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">PUC</h6>
                                    <div class="document-box-small" id="edit-pucDocument" data-target="pucDocument">
                                        <img class="document-img" alt="PUC">
                                        <div class="upload-overlay">
                                            <i class="fa fa-camera text-white"></i>
                                            <input type="file" class="file-input-hidden" accept="image/*" data-target="pucDocument">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">PUC Expiry</label>
                                    <input type="date" class="form-control form-control-sm" id="edit_puc_expiry" min="<?php echo $today; ?>">
                                </div>
                                <div class="col-6">
                                    <h6 class="fw-semibold mb-2">Insurance</h6>
                                    <div class="document-box-small" id="edit-insuranceDocument" data-target="insuranceDocument">
                                        <img class="document-img" alt="Insurance">
                                        <div class="upload-overlay">
                                            <i class="fa fa-camera text-white"></i>
                                            <input type="file" class="file-input-hidden" accept="image/*" data-target="insuranceDocument">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Insurance Expiry</label>
                                    <input type="date" class="form-control form-control-sm" id="edit_insurance_expiry" min="<?php echo $today; ?>">
                                </div>
                                <div class="col-12 text-end mt-3">
                                    <button class="btn btn-sm btn-success me-2 save-section-img" data-section="documents">
                                        <i class="fa fa-save me-1"></i>Save
                                    </button>
                                    <button class="btn btn-sm btn-secondary cancel-section" data-section="documents">
                                        <i class="fa fa-times me-1"></i>Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5 d-none">
                        <div class="card shadow-sm border-0 mb-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold card-heading"><i class="fa fa-images text-danger me-2"></i>Vehicle
                                        Gallery</h6>
                                    <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger"
                                        data-section="gallery" title="Edit Gallery"></i>
                                </div>
                                <div class="view-mode" id="view-gallery">
                                    <div class="position-relative image-wrapper">
                                        <img id="mainGalleryImage" src="assets/images/carimage.png"
                                            class="img-fluid cursor-pointer viewable-image"
                                            style="width:100%;height:120px;object-fit:cover;">
                                        <div class="gallery-count" id="galleryCount">0 Photos</div>
                                    </div>
                                </div>
                                <input type="file" id="photoUpload" multiple accept="image/*" class="form-control mt-2">
                                <div id="hiddenGalleryImages" style="display:none;"></div>
                                <div class="edit-mode" id="edit-gallery" style="display: none;">
                                    <div class="position-relative mb-2">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcYyYyYyYyYyYyYyYyYyYyYyYyYyYyYyYyY&s"
                                            alt="Car" class="img-fluid rounded" id="editMainCarImage"
                                            style="width: 100%; height: 100px; object-fit: cover;">
                                        <div class="upload-overlay-small" style="bottom: 5px; right: 5px;">
                                            <i class="fa fa-camera text-primary"></i>
                                            <input type="file" class="file-input-hidden" accept="image/*"
                                                id="galleryUpload">
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button class="btn btn-sm btn-success me-2 save-section"
                                            data-section="gallery"><i class="fa fa-save me-1"></i>Save</button>
                                        <button class="btn btn-sm btn-secondary cancel-section"
                                            data-section="gallery"><i class="fa fa-times me-1"></i>Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ================= RIGHT COLUMN - JOBS & ACTIVITY ================= -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <h6 class="mb-0 fw-bold card-heading">
                                    <i class="fa fa-calendar-alt text-danger me-2"></i>Scheduled Jobs
                                    <span id="status-jobs"></span>
                                </h6>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3" id="openAutoScheduleModalBtn">
                                    <i class="fa fa-magic me-1"></i> Re‑Schedule
                                </button>
                                <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger" data-section="jobs" title="Edit Jobs"></i>
                            </div>
                        </div>
                        <div class="view-mode" id="view-jobs">
                            <div class="schedule-list jobs-scroll" id="scheduledJobsList">
                                <div
                                    class="schedule-item p-2 border-bottom d-flex justify-content-between align-items-center">
                                    <div><i class="fa fa-map-marker-alt text-success me-2"></i> Chennai → Salem</div>
                                    <span class="text-muted small">Today 10:30 AM</span>
                                </div>
                                <div
                                    class="schedule-item p-2 border-bottom d-flex justify-content-between align-items-center">
                                    <div><i class="fa fa-map-marker-alt text-success me-2"></i> Salem → Coimbatore</div>
                                    <span class="text-muted small">Tomorrow 2:00 PM</span>
                                </div>
                            </div>
                        </div>
                        <div class="edit-mode" id="edit-jobs" style="display: none;">
                            <div id="dynamicScheduleWrapper" style="max-height: 280px; overflow-y: auto; overflow-x: hidden;">
                            </div>
                            <div class="row g-2 mt-2 mb-3">
                                <div class="col-6">
                                    <button type="button" class="btn btn-sm btn-info w-100" id="addScheduleRowBtn">
                                        <i class="fa fa-plus me-1"></i> Add Route
                                    </button>
                                </div>
                            </div>
                            <div class="text-end mt-2">
                                <button type="button" class="btn btn-sm btn-success me-2" id="saveScheduleBtn">
                                    <i class="fa fa-save me-1"></i>Save
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary cancel-section" data-section="jobs">
                                    <i class="fa fa-times me-1"></i>Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row align-items-stretch mb-3">
                    <div class="col-6">
                        <div class="card shadow-sm border-0 mb-3 h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold card-heading" style="padding: 7px 2px;">
                                        <i class="fa fa-credit-card text-danger me-1"></i>Payment Methods
                                        <span id="status-payment"></span>
                                    </h6>
                                    <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger" data-section="payment"
                                        title="Edit"></i>
                                </div>
                                <div class="view-mode" id="view-payment">
                                    <div class="mt-5">
                                        <span class="badge bg-light text-dark p-2">PRIMARY UPI</span>
                                        <span class="display-field ms-2" data-field="upi"
                                            id="display_upi">venkatesan@okaxis</span>
                                    </div>
                                </div>
                                <div class="edit-mode" id="edit-payment" style="display: none;">
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold mb-1">UPI ID</label>
                                        <input type="text" class="form-control form-control-sm edit-field" id="edit_upi" data-field="upi" value="venkatesan@okaxis" oninput="this.value = this.value.replace(/\s/g, '').slice(0, 30)">
                                    </div>
                                    <div class="text-end mt-2">
                                        <button class="btn btn-sm btn-success me-2 save-section" data-section="payment"><i
                                                class="fa fa-save me-1"></i>Save</button>
                                        <button class="btn btn-sm btn-secondary cancel-section" data-section="payment"><i
                                                class="fa fa-times me-1"></i>Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold card-heading"><i class="fa fa-comment text-danger me-2"></i>Admin Remarks
                                        <span id="status-remarks"></span>
                                    </h6>
                                    <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger" data-section="remarks"
                                        title="Edit Remarks"></i>
                                </div>
                                <div class="view-mode" id="view-remarks">
                                    <div class="remarks-content p-3 bg-light ">
                                        <p class="mb-2" id="display_remarks">"Reliable driver for long distance trips. Excellent
                                            fuel management on previous Salem route. Recommended for VIP clients."</p>
                                        <small class="text-muted" id="display_review">— John Doe, Operations
                                            Manager</small>
                                    </div>
                                </div>
                                <div class="edit-mode" id="edit-remarks" style="display: none;">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Remarks</label>
                                        <textarea class="form-control form-control-sm" id="edit_remarks"
                                            rows="3">Reliable driver for long distance trips. Excellent fuel management on previous Salem route. Recommended for VIP clients.</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Review</label>
                                        <select class="form-control form-select" id="edit_review">
                                            <option value="">Select</option>
                                            <option value="Good">Good</option>
                                            <option value="Bad">Bad</option>
                                            <option value="Excellent">Excellent</option>
                                        </select>
                                    </div>
                                    <div class="text-end mt-2">
                                        <button class="btn btn-sm btn-success me-2 save-section" data-section="remarks"><i
                                                class="fa fa-save me-1"></i>Save</button>
                                        <button class="btn btn-sm btn-secondary cancel-section" data-section="remarks"><i
                                                class="fa fa-times me-1"></i>Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0 fw-bold card-heading">
                                <i class="fa fa-rupee text-danger me-2"></i>Fare Rates
                                <span id="status-fare"></span>
                            </h6>
                            <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger"
                                data-section="fare" title="Edit"></i>
                        </div>
                        <div class="view-mode" id="view-fare">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>PER KM</th>
                                            <th>EXTRA KM</th>
                                            <th>EXTRA HOUR</th>
                                            <th>EXTRA DAY</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>₹<span class="display-field" data-field="per_km"
                                                    id="display_per_km">14</span></td>
                                            <td>₹<span class="display-field" data-field="extra_km"
                                                    id="display_extra_km">15</span></td>
                                            <td>₹<span class="display-field" data-field="extra_hour"
                                                    id="display_extra_hour">100</span></td>
                                            <td>₹<span class="display-field" data-field="extra_day"
                                                    id="display_extra_day">1200</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="edit-mode" id="edit-fare" style="display: none;">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1">Per KM (₹)</label>
                                    <input type="number" class="form-control form-control-sm edit-field"
                                        id="edit_per_km" data-field="per_km" value="14" min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5)">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1">Extra KM (₹)</label>
                                    <input type="number" class="form-control form-control-sm edit-field"
                                        id="edit_extra_km" data-field="extra_km" value="15" min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5)">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1">Extra Hour (₹)</label>
                                    <input type="number" class="form-control form-control-sm edit-field"
                                        id="edit_extra_hour" data-field="extra_hour" value="100" min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5)">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold mb-1">Extra Day (₹)</label>
                                    <input type="number" class="form-control form-control-sm edit-field"
                                        id="edit_extra_day" data-field="extra_day" value="1200" min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5)">
                                </div>
                                <div class="col-12 mt-2 text-end">
                                    <button class="btn btn-sm btn-success me-2 save-section"
                                        data-section="fare"><i class="fa fa-save me-1"></i>Save</button>
                                    <button class="btn btn-sm btn-secondary cancel-section"
                                        data-section="fare"><i class="fa fa-times me-1"></i>Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm border-0 mb-3 d-none">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0 fw-bold"><i class="fa fa-clock text-danger me-2"></i>Recent Activity</h6>
                            <i class="fa fa-edit text-dark cursor-pointer edit-section-trigger" data-section="activity"
                                title="Edit Activity"></i>
                        </div>
                        <div class="view-mode d-none" id="view-activity">
                            <div class="activity-list" id="recentActivityList">
                                <div class="activity-item p-2 border-bottom">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <i class="fa fa-check-circle text-success me-2"></i>
                                            <strong>Trip Completed</strong>
                                        </div>
                                        <span class="text-muted small">2 hours ago</span>
                                    </div>
                                    <div class="ms-4 small">Bangalore to Chennai - 280 KM</div>
                                </div>
                                <div class="activity-item p-2 border-bottom">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <i class="fa fa-exclamation-triangle text-warning me-2"></i>
                                            <strong>PUC Renewal Pending</strong>
                                        </div>
                                        <span class="text-muted small">Yesterday</span>
                                    </div>
                                    <div class="ms-4 small">Document expires on 25th Oct</div>
                                </div>
                                <div class="activity-item p-2">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <i class="fa fa-times-circle text-danger me-2"></i>
                                            <strong>Job Cancelled</strong>
                                        </div>
                                        <span class="text-muted small">2 days ago</span>
                                    </div>
                                    <div class="ms-4 small">Customer no-show - ID #4421</div>
                                </div>
                            </div>
                        </div>
                        <div class="edit-mode" id="edit-activity" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Activity 1</label>
                                <input type="text" class="form-control form-control-sm mb-1" id="edit_activity1"
                                    value="Trip Completed - Bangalore to Chennai - 280 KM">
                                <input type="text" class="form-control form-control-sm" id="edit_activity1_time"
                                    value="2 hours ago">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Activity 2</label>
                                <input type="text" class="form-control form-control-sm mb-1" id="edit_activity2"
                                    value="PUC Renewal Pending - Document expires on 25th Oct">
                                <input type="text" class="form-control form-control-sm" id="edit_activity2_time"
                                    value="Yesterday">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Activity 3</label>
                                <input type="text" class="form-control form-control-sm mb-1" id="edit_activity3"
                                    value="Job Cancelled - Customer no-show - ID #4421">
                                <input type="text" class="form-control form-control-sm" id="edit_activity3_time"
                                    value="2 days ago">
                            </div>
                            <div class="text-end mt-2">
                                <button class="btn btn-sm btn-success me-2 save-section" data-section="activity"><i
                                        class="fa fa-save me-1"></i>Save</button>
                                <button class="btn btn-sm btn-secondary cancel-section" data-section="activity"><i
                                        class="fa fa-times me-1"></i>Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="jobEditLogsModal" tabindex="-1" aria-labelledby="jobEditLogsModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jobEditLogsModalLabel">
                    <i class="fa fa-history text-secondary me-2"></i>Edit History - <span id="logJobNo" class="text-danger"></span>
                </h5>
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                    <i class="fa fa-times text-dark"></i>
                </button>
            </div>
            <div class="modal-body" style="max-height: 65vh; overflow-y: auto;">
                <div id="jobLogsLoader" class="text-center py-4 d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 small text-muted">Loading logs...</p>
                </div>
                <div id="jobLogsContent"></div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
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
                <input type="hidden" id="cancelJobStatus">
                <p class="mb-2 fw-semibold">
                    Are you sure you want to cancel this job?
                </p>
                <div class="text-start mb-2">
                    <label class="form-label small fw-bold text-muted mb-1">Reason for Cancellation <span class="text-danger">*</span></label>
                    <textarea id="cancelJobReason" class="form-control text-start" rows="3" placeholder="Enter cancellation reason here..."></textarea>
                </div>
                <small class="text-muted">
                    This action cannot be undone.
                </small>
                <div id="cancelJobError" class="text-danger mt-2 d-none">
                </div>
            </div>
            <div class="modal-footer justify-content-center border-0 bg-light">
                <button class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">
                    Keep Job
                </button>
                <button class="btn btn-danger btn-sm px-4" id="confirmCancelJobBtn">
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
<div class="offcanvas offcanvas-end" tabindex="1" id="driverPreviewCanvas">
    <div class="offcanvas-header border-bottom py-2">
        <h6 class="mb-0 card-heading">
            <strong>
                <i class="fa fa-id-card me-1 text-danger"></i> Driver Details
            </strong>
        </h6>
        <button class="btn-close" data-bs-dismiss="offcanvas">
            <i class="fa fa-close"></i>
        </button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="card mb-2 border-0" id="driverDetailsCard">
            <div class="card-body p-3 position-relative">
                <div id="driverProfileLoader" class="calendar-overlay d-none">
                    <div class="calendar-spinner"></div>
                </div>
                <input type="hidden" id="preview_driver_id" value="">
                <div class="d-flex align-items-center gap-3 mt-3">
                    <div style="width:100px;height:80px;">
                        <img src="/assets/images/driver.png" alt="Driver Photo" id="preview_driver_photo"
                            class="rounded-circle cursor-pointer"
                            style="height: 100%;width: 100%; object-fit: cover;"
                            data-bs-toggle="modal" data-bs-target="#driverPhotoModal">
                    </div>
                    <div class="w-100">
                        <div class="d-flex justify-content-start gap-3 align-items-center">
                            <div class="d-flex align-items-center gap-2" style="font-size:14px">
                                <strong id="preview_driver_name">Loading...</strong>
                                <a href="javascript:void(0);" class="text-warning" title="Edit Driver" id="driver_profile_edit">
                                    <i class="fa fa-edit text-info"></i>
                                </a>
                                <div class="d-flex align-items-center">
                                    <i id="sidebar_remarks_icon" class="fa-solid fa-message cursor-pointer" style="color: #6c757d;" title="Add/View Remarks"></i>
                                </div>
                            </div>
                            <div class="d-inline-flex align-items-center gap-2">
                                <a href="#" class="call-con" title="Call Owner" id="preview_call_btn">
                                    <i class="fa fa-phone"></i>
                                </a>
                                <a href="#" target="_blank" class="whatsapp-icon" title="WhatsApp Owner" id="preview_wa_btn">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                        <div class="small text-muted mt-1" style="display: flex;flex-direction: column;">
                            <span id="preview_driver_no" class="text-muted">
                                <i class="fa fa-phone text-success me-1"></i>
                            </span>
                            <span id="preview_kyc_status">
                                <i class="fa fa-times-circle text-danger me-1"></i> Checking...
                            </span>
                            <span id="preview_location">
                                <i class="fa fa-map-marker-alt text-danger me-1"></i> N/A
                            </span>
                            <span id="preview_experience">
                                <i class="fa fa-briefcase text-info me-1"></i> N/A
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-3 small">
                    <div class="row">
                        <div class="col-6" id="preview_license">
                            <i class="fa fa-id-badge text-primary me-1"></i> <strong>N/A</strong> | <strong>N/A</strong>
                        </div>
                        <div class="col-6 p-0" id="preview_languages">
                            <i class="fa fa-language text-dark me-1"></i> <strong>N/A</strong>
                        </div>
                    </div>
                </div>
                <hr class="my-3">
                <strong class="card-heading"><i class="fa fa-car me-1 text-danger"></i> Vehicle Details</strong>
                <div class="d-flex align-items-center mt-3 gap-3">
                    <img src="/assets/images/carimage.png" alt="Car Image" id="preview_vehicle_photo"
                        class="rounded me-3 cursor-pointer"
                        style="width:80px;height:75px;object-fit:cover;"
                        data-bs-toggle="modal" data-bs-target="#carPhotoModal">
                    <div class="w-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex justify-content-center align-items-center" style="font-size:13px">
                                <strong id="preview_vehicle_name">N/A</strong>
                                (<span id="preview_vehicle_model">N/A</span>)
                                <span class="verified-star"><i class="fa fa-check"></i></span>
                            </div>
                        </div>
                        <div class="mt-2 small text-muted">
                            <div class="d-flex align-items-center gap-4 mb-1">
                                <div class="d-flex align-items-center" title="Seats">
                                    <i class="fa fa-users text-info me-1"></i><strong id="preview_seats">N/A</strong>
                                </div>
                                <div class="d-flex align-items-center" title="Fuel Type">
                                    <i class="fa fa-gas-pump text-warning me-1"></i><strong id="preview_fuel">N/A</strong>
                                </div>
                                <div class="d-flex align-items-center" title="Luggage">
                                    <i class="fa fa-suitcase-rolling text-secondary me-1"></i><strong id="preview_luggage">N/A</strong>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-4">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-file-contract text-success me-1"></i>RC: <strong class="ms-1" id="preview_rc">N/A</strong>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-file-alt text-danger me-1"></i>IN: <strong class="ms-1" id="preview_in">N/A</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 p-3 bg-light  small">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-road text-primary me-1"></i>Per Km: <strong class="ms-1 text-dark" id="preview_per_km">N/A</strong>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-plus-circle text-warning me-1"></i>Extra Km: <strong class="ms-1 text-dark" id="preview_extra_km">N/A</strong>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-clock text-info me-1"></i>Add. Hour: <strong class="ms-1 text-dark" id="preview_extra_hour">N/A</strong>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-calendar-plus text-success me-1"></i>Extra Day: <strong class="ms-1 text-dark" id="preview_extra_day">N/A</strong>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center small mb-1">
                        <strong>
                            <i class="fa fa-chart-line text-primary me-1"></i> Profile Completion
                        </strong>
                        <span id="preview_profile_percent_text" class="fw-bold">0%</span>
                    </div>
                    <div class="progress" style="height:8px;">
                        <div id="preview_profile_progress" class="progress-bar" role="progressbar" style="width:0%" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="remarksModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="margin-left: 3%; margin-right: auto;">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light text-dark">
                <h5 class="modal-title fw-bold">Remarks for <span id="rmk_user_name" class="text-primary"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="rmk_user_id">
                <input type="hidden" id="rmk_mem_id" value="<?= $_SESSION['memid'] ?? '' ?>">
                <p class="fw-bold mb-2">Previous Remarks</p>
                <div class="table-responsive mb-4" style="max-height: 250px; overflow-y: auto;">
                    <table class="table table-hover table-bordered table-sm text-start" style="text-align: left !important;">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th scope="col" width="10%">SNO</th>
                                <th scope="col" width="30%">Contact Person</th>
                                <th scope="col" width="40%">Remarks</th>
                                <th scope="col" width="20%">Date & Time</th>
                            </tr>
                        </thead>
                        <tbody id="remarks_table_body">
                        </tbody>
                    </table>
                </div>
                <hr>
                <p class="fw-bold mb-2">Add Remark</p>
                <div class="row">
                    <div class="col-12">
                        <textarea id="new_remark_text" class="form-control" rows="3" placeholder="Type your remarks here..." style="text-align: left !important; resize: vertical; border: 1px solid #ccc;"></textarea>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-primary px-4" id="btn_save_remark" onclick="saveRemark()">Submit Remark</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="customerFeedbackModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light py-2">
                <h6 class="modal-title mb-0 fw-bold text-dark"><i class="fa fa-star text-warning me-2"></i>Customer Feedback</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3 text-center">
                <h6 class="fw-bold text-dark mb-2 text-start" style="font-size: 13px;">Rating Given:</h6>
                <div id="feedbackStars" class="text-warning text-start mb-3 fs-5">
                </div>
                <h6 class="fw-bold text-dark mb-2 text-start" style="font-size: 13px;">Customer Review:</h6>
                <p id="feedbackReviewText" class="text-dark fw-semibold mb-0" style="font-size: 14px; text-align: left; background: #f8f9fa; padding: 10px; border-left: 3px solid #ffc107; border-radius: 4px;"></p>
            </div>
            <div class="modal-footer py-2 border-0 bg-light justify-content-center">
                <button class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="scheduledRequestsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light py-2">
                <h6 class="modal-title fw-bold text-dark"><i class="fa fa-users text-primary me-2"></i>Requested Drivers Status</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="max-height: 400px; overflow-y: auto;">
                <div class="list-group list-group-flush" id="scheduledRequestsList">
                </div>
            </div>
            <div class="modal-footer py-2 bg-light border-0">
                <button class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cancelledBidsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light py-2">
                <h6 class="modal-title fw-bold text-dark"><i class="fa fa-gavel text-primary me-2"></i>Bids History</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="max-height: 400px; overflow-y: auto;">
                <div class="list-group list-group-flush" id="cancelledBidsList">
                </div>
            </div>
            <div class="modal-footer py-2 bg-light border-0">
                <button class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cancelReasonModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title mb-0"><i class="fa fa-exclamation-triangle me-2"></i>Cancellation Details</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3 text-center">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge bg-danger" id="cancelledByBadge" style="font-size: 12px; font-weight: 600;">
                        Cancelled by: User
                    </span>
                    <span id="cancelledTimeText" class="text-muted small fw-bold"></span>
                </div>
                <h6 class="fw-bold text-dark mb-2 text-start">Reason for Cancellation</h6>
                <p id="cancelReasonText" class="text-dark fw-semibold mb-3" style="font-size: 14px; text-align: left; background: #f8f9fa; padding: 10px; border-left: 3px solid #dc3545; border-radius: 4px;"></p>
                <h6 class="text-muted small fw-bold mb-2 text-start">Attachment / Proof</h6>
                <div class="border p-2 bg-light d-flex justify-content-center align-items-center" style="min-height: 150px; border-radius: 4px;">
                    <img id="cancelReasonDoc" src="" class="img-fluid shadow-sm d-none viewable-image cursor-pointer" style="max-height: 200px; width: auto; object-fit: contain; border: 1px solid #dee2e6;">
                    <div id="cancelReasonNoDoc" class="text-muted small">
                        <i class="fa fa-image fs-3 mb-2 d-block text-secondary opacity-50"></i>
                        No Image Uploaded
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 border-0 bg-light justify-content-center">
                <button class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="pushNotificationModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title mb-0"><i class="fa fa-bell me-2"></i>Send Push</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <input type="hidden" id="push_user_id">
                <div class="mb-2">
                    <label class="form-label fw-semibold small mb-1">Recipient</label>
                    <input type="text" class="form-control form-control-sm bg-light fw-bold" id="push_user_name" readonly>
                </div>
                <div class="mb-2">
                    <label class="form-label fw-semibold small mb-1">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" id="push_title" placeholder="Notification title">
                </div>
                <div class="mb-2">
                    <label class="form-label fw-semibold small mb-1">Message <span class="text-danger">*</span></label>
                    <textarea class="form-control form-control-sm" id="push_body" rows="3" placeholder="Notification message..."></textarea>
                    <small class="text-muted" style="font-size: 10px;">Use <b>{{name}}</b> to insert the user's name dynamically.</small>
                </div>
            </div>
            <div class="modal-footer py-2 border-0 bg-light justify-content-center">
                <button class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary btn-sm px-4" id="sendPushBtn">
                    <i class="fa fa-paper-plane me-1"></i> Send
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="dayScheduleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-bottom-0 pb-0">
                <h6 class="modal-title fw-bold text-dark d-flex align-items-center">
                    <i class="fa fa-calendar-day text-primary me-2 fs-5"></i>
                    <span id="dayScheduleDateText">Date</span>
                </h6>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-light pt-2">
                <input type="hidden" id="dayScheduleTargetDate">
                <div id="dayScheduleList" style="max-height: 350px; overflow-y: auto; padding-right: 5px;">
                </div>
            </div>
            <div class="modal-footer border-top-0 bg-white d-flex justify-content-between">
                <button class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<style>
    .fc-theme-standard .fc-scrollgrid {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
    }
    .fc .fc-col-header-cell-cushion {
        padding: 8px 4px;
        font-weight: 600;
        color: #4b5563;
        text-transform: uppercase;
        font-size: 12px;
    }
    .fc-theme-standard th {
        background-color: #f9fafb;
        border-color: #e5e7eb;
    }
    .fc-theme-standard td {
        border-color: #e5e7eb;
    }
    .fc-daygrid-day-number {
        color: #1f2937;
        font-weight: 500;
        padding: 6px !important;
        text-decoration: none !important;
    }
    .fc-daygrid-day-number:hover {
        text-decoration: underline !important;
        color: #2563eb;
    }
    .fc-day-today {
        background-color: #eff6ff !important;
    }
    .fc-event {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: transform 0.1s;
    }
    .fc-event:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        z-index: 5;
    }
    .fc .fc-button-primary {
        background-color: #ffffff;
        border-color: #d1d5db;
        color: #374151;
        font-size: 13px;
        font-weight: 500;
        text-transform: capitalize;
        padding: 6px 12px;
    }
    .fc .fc-button-primary:not(:disabled):active,
    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background-color: #f3f4f6;
        border-color: #d1d5db;
        color: #111827;
    }
    .fc .fc-toolbar-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #111827;
    }
    .fc-daygrid-more-link {
        font-size: 11px;
        font-weight: 600;
        color: #2563eb !important;
        text-decoration: none !important;
    }
    .swal2-container {
        z-index: 1060000 !important;
    }
    #jpsm_customTabs {
        gap: 10px;
        border-bottom: 0;
    }
    #jpsm_customTabs .custom-tab-btn {
        background-color: #ffffff !important;
        color: #495057 !important;
        border: 1px solid #ced4da !important;
        border-radius: 4px !important;
        transition: none;
        opacity: 1 !important;
        cursor: pointer;
        font-size: 14px;
        padding: 8px 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    #jpsm_customTabs .custom-tab-btn.active {
        background-color: #0d6efd !important;
        color: #ffffff !important;
        border-color: #0d6efd !important;
    }
    .jpsm-modal-user-table {
        max-height: 350px;
        overflow-y: auto;
    }
    .jpsm-modal-user-table thead th {
        position: sticky;
        top: 0;
        background-color: #f8f9fa !important;
        z-index: 10;
        border-bottom: 2px solid #dee2e6 !important;
        font-size: 12px;
        text-transform: uppercase;
        color: #6c757d;
        letter-spacing: 0.5px;
    }
    .jpsm-modal-user-table td {
        font-size: 14px;
        vertical-align: middle;
    }
    .modal-close-btn {
        background: none;
        border: none;
        font-size: 24px;
        font-weight: bold;
        color: #666;
        cursor: pointer;
        padding: 0;
        margin-left: auto;
    }
    .jpsm-toolbar {
        padding: 12px 16px;
        background: #fff;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
<div class="modal fade" id="jobPushStatusModal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border: 1px solid #bbb; border-radius: 6px;">
            <div class="modal-header p-3 d-flex align-items-center" style="border-bottom: 1px solid #eee;">
                <h6 class="modal-title fw-bold m-0" style="color: #333;">
                    <i class="fa fa-list-alt text-primary me-2"></i> Notification Details
                </h6>
                <button type="button" class="modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                    &times;
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="p-3 mb-4" style="border: 1px solid #dee2e6; border-radius: 4px;">
                    <div class="mb-3">
                        <span style="color: #6c757d; font-weight: bold; font-size: 11px; text-transform: uppercase;">TITLE:</span><br>
                        <span id="jpsm_modal-title" style="color: #212529; font-size: 15px; font-weight: bold;"></span>
                    </div>
                    <div>
                        <span style="color: #6c757d; font-weight: bold; font-size: 11px; text-transform: uppercase;">MESSAGE:</span><br>
                        <span id="jpsm_modal-body" style="color: #212529; font-size: 14px; font-weight: normal; word-wrap: break-word; white-space: normal;"></span>
                    </div>
                </div>
                <ul class="nav nav-tabs mb-4 border-0" id="jpsm_customTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link custom-tab-btn active fw-bold" id="tab-btn-delivered" data-bs-toggle="tab" data-bs-target="#jpsm_delivered-tab" type="button" role="tab">
                            Delivered <span id="jpsm_delivered-count" class="badge bg-light text-dark border ms-1">0</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link custom-tab-btn text-danger fw-bold" id="tab-btn-failed" data-bs-toggle="tab" data-bs-target="#jpsm_not-delivered-tab" type="button" role="tab">
                            Not Delivered <span id="jpsm_not-delivered-count" class="badge bg-light text-danger border ms-1">0</span>
                        </button>
                    </li>
                </ul>
                <div class="tab-content" style="border: 1px solid #dee2e6; border-radius: 4px; overflow: hidden;">
                    <div class="tab-pane fade show active p-0" id="jpsm_delivered-tab" role="tabpanel">
                        <div class="jpsm-toolbar">
                            <input type="text" class="form-control form-control-sm" id="jpsm_filterDelivered" placeholder="Search User Details..." style="max-width: 300px;">
                            <div class="d-flex align-items-center">
                                <input class="shadow-none cursor-pointer m-0" type="checkbox" id="jpsm_selectAllDelivered" style="width:16px; height:16px;">
                                <label class="form-check-label text-dark ms-2 fw-bold" for="jpsm_selectAllDelivered" style="cursor:pointer; font-size: 13px;">Select All</label>
                            </div>
                        </div>
                        <div class="jpsm-modal-user-table bg-white">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;" class="text-center">S.NO</th>
                                        <th style="width: 10%;" class="text-center">SELECT</th>
                                        <th style="width: 50%;">USER DETAILS</th>
                                        <th style="width: 30%;" class="text-center">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody id="jpsm_delivered-list"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade p-0" id="jpsm_not-delivered-tab" role="tabpanel">
                        <div class="jpsm-toolbar">
                            <input type="text" class="form-control form-control-sm" id="jpsm_filterNotDelivered" placeholder="Search User Details..." style="max-width: 300px;">
                            <div class="d-flex align-items-center">
                                <input class="shadow-none cursor-pointer m-0" type="checkbox" id="jpsm_selectAllNotDelivered" style="width:16px; height:16px;">
                                <label class="form-check-label text-dark ms-2 fw-bold" for="jpsm_selectAllNotDelivered" style="cursor:pointer; font-size: 13px;">Select All</label>
                            </div>
                        </div>
                        <div class="jpsm-modal-user-table bg-white">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;" class="text-center">S.NO</th>
                                        <th style="width: 10%;" class="text-center">SELECT</th>
                                        <th style="width: 50%;">USER DETAILS</th>
                                        <th style="width: 30%;" class="text-center">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody id="jpsm_not-delivered-list"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-3 d-flex justify-content-end" style="border-top: 1px solid #eee; background: #f8f9fa;">
                <button type="button" class="btn btn-secondary px-4 py-2 fw-bold" id="jpsm_resendFromModalBtn" style="font-size: 13px; background: #6c757d; border:none;">
                    <i class="fa fa-refresh me-1"></i> Resend to Selected
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editLogModal" tabindex="-1" aria-labelledby="editLogModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLogModalLabel">Edit Logs</h5>
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal"><i class="fa fa-times text-dark"></i></button>
            </div>
            <div class="modal-body">
                <div id="edit_log_content">
                    <p class="text-center text-muted">Loading logs...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="vehicleImagesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title"><i class="fa fa-camera text-primary me-2"></i>Update Vehicle Images</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3" id="sixImagesContainer">
                    <div class="col-md-4 text-center">
                        <label class="form-label fw-semibold">Front View</label>
                        <div class="position-relative d-inline-block w-100">
                            <img src="assets/images/placeholder.png" id="prev_front_view" class="img-thumbnail mb-2" style="height:100px; width:100%; object-fit:cover;">
                        </div>
                        <input type="file" class="form-control form-control-sm local-image-select" data-key="front_view_image_url" data-target="front_view" accept="image/jpeg, image/png, image/jpg">
                    </div>
                    <div class="col-md-4 text-center">
                        <label class="form-label fw-semibold">Boot Image</label>
                        <div class="position-relative d-inline-block w-100">
                            <img src="assets/images/placeholder.png" id="prev_boot_image" class="img-thumbnail mb-2" style="height:100px; width:100%; object-fit:cover;">
                        </div>
                        <input type="file" class="form-control form-control-sm local-image-select" data-key="boot_image_url" data-target="boot_image" accept="image/jpeg, image/png, image/jpg">
                    </div>
                    <div class="col-md-4 text-center">
                        <label class="form-label fw-semibold">Extra Image 1</label>
                        <div class="position-relative d-inline-block w-100">
                            <img src="assets/images/placeholder.png" id="prev_extra_image_1" class="img-thumbnail mb-2" style="height:100px; width:100%; object-fit:cover;">
                        </div>
                        <input type="file" class="form-control form-control-sm local-image-select" data-key="extra_image_1_url" data-target="extra_image_1" accept="image/jpeg, image/png, image/jpg">
                    </div>
                    <div class="col-md-4 text-center">
                        <label class="form-label fw-semibold">Top View</label>
                        <div class="position-relative d-inline-block w-100">
                            <img src="assets/images/placeholder.png" id="prev_car_top_view" class="img-thumbnail mb-2" style="height:100px; width:100%; object-fit:cover;">
                        </div>
                        <input type="file" class="form-control form-control-sm local-image-select" data-key="car_top_view_image_url" data-target="car_top_view" accept="image/jpeg, image/png, image/jpg">
                    </div>
                    <div class="col-md-4 text-center">
                        <label class="form-label fw-semibold">Interior Front</label>
                        <div class="position-relative d-inline-block w-100">
                            <img src="assets/images/placeholder.png" id="prev_interior_front" class="img-thumbnail mb-2" style="height:100px; width:100%; object-fit:cover;">
                        </div>
                        <input type="file" class="form-control form-control-sm local-image-select" data-key="interior_front_image_url" data-target="interior_front" accept="image/jpeg, image/png, image/jpg">
                    </div>
                    <div class="col-md-4 text-center">
                        <label class="form-label fw-semibold">Special Features</label>
                        <div class="position-relative d-inline-block w-100">
                            <img src="assets/images/placeholder.png" id="prev_special_features" class="img-thumbnail mb-2" style="height:100px; width:100%; object-fit:cover;">
                        </div>
                        <input type="file" class="form-control form-control-sm local-image-select" data-key="special_features_image_url" data-target="special_features" accept="image/jpeg, image/png, image/jpg">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-success" id="saveVehicleImagesBtn">
                    <i class="fa fa-upload me-1"></i>Upload & Save
                </button>
            </div>
        </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/nouislider@15.7.0/dist/nouislider.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/nouislider@15.7.0/dist/nouislider.min.js"></script>
<script src="js/group-drivers.js?v=<?= time() ?>"></script>
<script src="js/job_push_status.js?v=<?= time() ?>"></script>
<script src="js/carpool_jobs.js?v=<?= time() ?>"></script>
<script>
    const API_DOMAIN_2 = "<?= API_DOMAIN_2 ?>";
</script>
<script>
    let currentRouteControl = null;
    let animatedCarMarker = null;
    let carAnimationTimer = null;
    let routePins = [];
    let animatedLineObj = null;
    let historyPolyline = null;
    let historyMarkers = [];
    function clearMapOverlays(fullClear = false) {
        if (typeof driversMap === 'undefined' || !driversMap) return;
        if (currentRouteControl) {
            try {
                driversMap.removeControl(currentRouteControl);
            } catch (err) {}
            currentRouteControl = null;
        }
        if (animatedCarMarker) {
            driversMap.removeLayer(animatedCarMarker);
            animatedCarMarker = null;
        }
        if (carAnimationTimer) {
            clearInterval(carAnimationTimer);
            carAnimationTimer = null;
        }
        if (animatedLineObj) {
            driversMap.removeLayer(animatedLineObj);
            animatedLineObj = null;
        }
        if (typeof tempDriverMarker !== 'undefined' && tempDriverMarker) {
            driversMap.removeLayer(tempDriverMarker);
            tempDriverMarker = null;
        }
        if (routePins && routePins.length > 0) {
            routePins.forEach(pin => driversMap.removeLayer(pin));
            routePins = [];
        }
        if (historyPolyline) {
            driversMap.removeLayer(historyPolyline);
            historyPolyline = null;
        }
        if (historyMarkers && historyMarkers.length > 0) {
            historyMarkers.forEach(m => driversMap.removeLayer(m));
            historyMarkers = [];
        }
        if (fullClear) {
            window.currentDriverId = null;
        }
        resetDriverIcons();
    }
    $(document).on('click', '.nav-link', function() {
        clearMapOverlays();
    });
    $(document).on('click', '.nav-link', function() {
        clearMapOverlays();
    });
    function resetDriverIcons() {
        if (typeof driverMarkers === 'undefined') return;
        const defaultIcon = L.divIcon({
            className: '',
            html: `<div class="pulse-marker"></div>`,
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });
        Object.values(driverMarkers).forEach(marker => {
            if (marker) {
                marker.setIcon(defaultIcon);
                marker.setZIndexOffset(0);
                let content = marker.getPopup().getContent();
                if (typeof content === 'string') {
                    let $content = $('<div>').html(content);
                    let $name = $content.find('.driver-name-head').first();
                    if ($name.length) {
                        $name.css({
                            color: '#333',
                            fontWeight: 'bold'
                        });
                        $name.find('.bid-text-node').remove();
                        let cleanText = $name.text().replace(/\s*-\s*Bid:\s*₹\d+/g, '').trim();
                        $name.text(cleanText);
                    }
                    marker.setPopupContent($content.html());
                }
            }
        });
    }
    function highlightBiddersOnMap(jobNo, skipZoom = false) {
        if (!jobNo || jobNo === 'Unknown Job') return;
        if (typeof db === 'undefined' || !db) return;
        if ($('#blinking-pin-style').length === 0) {
            $('head').append(`
            <style id="blinking-pin-style">
                @keyframes map-pin-blink {
                    0% { opacity: 1; transform: scale(1); box-shadow: 0 0 15px rgba(0,0,0,1); }
                    50% { opacity: 0.3; transform: scale(1.3); box-shadow: 0 0 2px rgba(0,0,0,0.5); }
                    100% { opacity: 1; transform: scale(1); box-shadow: 0 0 15px rgba(0,0,0,1); }
                }
                .blinking-black-pin { width: 24px; height: 24px; background: #000; border-radius: 50%; border: 3px solid #ffc107; animation: map-pin-blink 0.8s infinite ease-in-out; }
            </style>
        `);
        }
        db.collection('<?= rtrim(FIREBASE_COLLECTION, '/') ?>').doc(jobNo).get().then(doc => {
            if (doc.exists) {
                const bids = doc.data().bids_details || {};
                let bounds = L.latLngBounds();
                let hasBidders = false;
                Object.entries(bids).forEach(([bidderId, bid]) => {
                    let targetMarker = null;
                    Object.values(driverMarkers).forEach(m => {
                        if (String(m.driver_user_id) === String(bidderId) || String(m.driver_table_id) === String(bidderId)) {
                            targetMarker = m;
                        }
                    });
                    if (targetMarker) {
                        hasBidders = true;
                        bounds.extend(targetMarker.getLatLng());
                        const highlightedIcon = L.divIcon({
                            className: '',
                            html: `<div class="blinking-black-pin"></div>`,
                            iconSize: [24, 24],
                            iconAnchor: [12, 12]
                        });
                        targetMarker.setIcon(highlightedIcon);
                        targetMarker.setZIndexOffset(1000);
                        let popupContent = targetMarker.getPopup().getContent();
                        if (typeof popupContent === 'string') {
                            let $content = $('<div>').html(popupContent);
                            let $name = $content.find('.driver-name-head').first();
                            if ($name.length) {
                                $name.find('.bid-text-node').remove();
                                let cleanText = $name.text().replace(/\s*-\s*Bid:\s*₹\d+/g, '').trim();
                                $name.text(cleanText);
                                $name.css({
                                        color: '#000',
                                        fontWeight: '900'
                                    })
                                    .append(` <span class="bid-text-node" style="color: #198754;">- Bid: ₹${bid.amount}</span>`);
                            }
                            targetMarker.setPopupContent($content.html());
                        }
                    }
                });
                if (!skipZoom && hasBidders && typeof driversMap !== 'undefined' && bounds.isValid()) {
                    setTimeout(() => {
                        driversMap.fitBounds(bounds, {
                            padding: [50, 50],
                            maxZoom: 13
                        });
                    }, 500);
                }
            }
        }).catch(err => console.error("Error fetching map bids:", err));
    }
    function showRichDriverPopup(marker, driverId, fallbackName, lastUpdated, fallbackMobile = '') {
        marker.bindPopup(`<div class="text-center p-3" style="min-width: 150px;">
        <i class="fa fa-spinner fa-spin text-primary fs-4"></i><br>
        <small class="text-muted mt-2 d-block">Fetching Driver details...</small>
    </div>`).openPopup();
        $.ajax({
            url: window.location.origin + "/ajax/service/driverServices.php",
            type: "POST",
            dataType: "json",
            data: {
                method: 'get_driver_details',
                driver_id: driverId
            },
            success: function(res) {
                let data = null;
                if (res.status && res.data && res.data.driver) {
                    data = res.data.driver;
                } else {
                    if (typeof window.driversAll !== 'undefined') {
                        let foundKey = Object.keys(window.driversAll).find(k => String(window.driversAll[k].user_id) === String(driverId) || String(window.driversAll[k].id) === String(driverId));
                        if (foundKey) data = window.driversAll[foundKey].fullData;
                    }
                    if (!data && typeof allMapDriversCache !== 'undefined' && allMapDriversCache) {
                        let foundSearch = allMapDriversCache.find(d => String(d.user_id) === String(driverId) || String(d.id) === String(driverId));
                        if (foundSearch) data = foundSearch;
                    }
                }
                if (!data) data = {
                    name: fallbackName,
                    mobile: fallbackMobile
                };
                let vModel = data.maker_model || 'N/A';
                let vSeats = data.seat || data.seaters || 'N/A';
                let vFuel = data.fuel_type || data.fuel_types || 'N/A';
                let cabType = data.cab_type || data.vehicle_type || 'Driver';
                if (data.vehicle_details) {
                    try {
                        const vData = typeof data.vehicle_details === 'string' ? JSON.parse(data.vehicle_details) : data.vehicle_details;
                        vModel = vData?.rc_details?.response?.vehicle_details?.maker_model || vModel;
                        vFuel = vData?.rc_details?.response?.vehicle_details?.fuel_type || vFuel;
                        let seatCap = vData?.rc_details?.response?.vehicle_details?.seat_capacity;
                        if (seatCap) {
                            vSeats = (parseInt(seatCap) > 1) ? (parseInt(seatCap) - 1) + "+1" : seatCap;
                        }
                    } catch (e) {
                        console.error("Popup Vehicle Parse Error", e);
                    }
                }
                let cleanMobile = data.mobile ? String(data.mobile).replace(/\D/g, '') : (fallbackMobile ? String(fallbackMobile).replace(/\D/g, '') : '');
                let waMobile = cleanMobile.length === 10 ? '91' + cleanMobile : cleanMobile;
                let lastSeenHtml = lastUpdated ? `<div class="text-center text-muted mt-2" style="font-size: 10px;">Last seen: ${lastUpdated}</div>` : '';
                let html = `
            <div style="min-width:210px; font-family: sans-serif;">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <strong style="font-size: 14px; color: #333;" class="driver-name-head">${data.name || fallbackName || 'Unknown'}</strong>
                    ${cabType !== 'Driver' ? `<span class="badge bg-success" style="font-size:10px;">${cabType}</span>` : ''}
                </div>
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="small text-muted"><i class="fa fa-phone text-primary me-1"></i>${data.mobile || fallbackMobile || 'N/A'}</span>
                    ${cleanMobile ? `
                    <div class="d-flex gap-2">
                        <a href="tel:${cleanMobile}" class="text-decoration-none shadow-sm" title="Call" style="background:#e7f1ff; color:#0d6efd; width:26px; height:26px; display:flex; align-items:center; justify-content:center; border-radius:50%;"><i class="fa fa-phone" style="font-size:11px;"></i></a>
                        <a href="https://wa.me/${waMobile}" target="_blank" class="text-decoration-none shadow-sm" title="WhatsApp" style="background:#e9f7ef; color:#25d366; width:26px; height:26px; display:flex; align-items:center; justify-content:center; border-radius:50%;"><i class="fab fa-whatsapp" style="font-size:14px;"></i></a>
                    </div>
                    ` : ''}
                </div>
                <div class="border-top pt-2 mt-1" style="font-size: 11px; line-height: 1.5; background: #f8f9fa; padding: 8px; border-radius: 6px;">
                    <div class="text-dark mb-1"><i class="fa fa-car text-danger me-2"></i><strong>${vModel}</strong></div>
                    <div class="d-flex justify-content-between mt-1 text-muted">
                        <span><i class="fa fa-users text-info me-1"></i>${vSeats}</span>
                        <span><i class="fa fa-gas-pump text-warning me-1"></i>${vFuel}</span>
                    </div>
                </div>
                ${lastSeenHtml}
                <a href="javascript:void(0)" class="btn btn-primary btn-sm w-100 mt-2 py-1 driver-link" data-driver="${data.user_id || driverId}" style="font-size:12px; font-weight: 500;">View Profile</a>
            </div>`;
                marker.setPopupContent(html);
            },
            error: function() {
                marker.setPopupContent(`<div class="text-danger p-2 text-center small"><i class="fa fa-exclamation-triangle"></i> Failed to load rich details.</div>`);
            }
        });
    }
    function pinDriverOnMap(driverId, driverName) {
        clearMapOverlays();
        $.ajax({
            url: window.location.origin + "/ajax/service/driverServices.php",
            type: "POST",
            dataType: "json",
            data: {
                method: 'get_driver_current_location',
                driver_id: driverId
            },
            success: function(res) {
                if (res.status && res.data) {
                    const lat = parseFloat(res.data.lat);
                    const lng = parseFloat(res.data.lng);
                    const lastUpdated = res.data.updated_at || 'Just now';
                    if (lat && lng && typeof driversMap !== 'undefined') {
                        setTimeout(() => {
                            driversMap.invalidateSize();
                            driversMap.flyTo([lat, lng], 16, {
                                animate: true,
                                duration: 1.5
                            });
                            const redPin = L.divIcon({
                                className: '',
                                html: `<div style="background-color: #dc3545; width: 18px; height: 18px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 8px rgba(0,0,0,0.6);"></div>`,
                                iconSize: [18, 18],
                                iconAnchor: [9, 9]
                            });
                            tempDriverMarker = L.marker([lat, lng], {
                                icon: redPin
                            }).addTo(driversMap);
                            showRichDriverPopup(tempDriverMarker, driverId, driverName, lastUpdated, res.data.mobile);
                        }, 100);
                    }
                } else {
                    toast('error', 'Location not found for this driver.');
                }
            }
        });
    }
    $(document).on('click', '.viewRouteBtn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        let fromLat = parseFloat($(this).data('fromlat'));
        let fromLng = parseFloat($(this).data('fromlng'));
        let toLat = parseFloat($(this).data('tolat'));
        let toLng = parseFloat($(this).data('tolng'));
        const jobNo = $(this).data('jobno') || 'Unknown Job';
        const fromPlaceId = $(this).data('fromplaceid');
        const toPlaceId = $(this).data('toplaceid');
        const fromName = $(this).data('fromname');
        const toName = $(this).data('toname');
        $('.backToMap').trigger('click');
        setTimeout(() => {
            if (typeof driversMap === 'undefined' || !driversMap) return;
            driversMap.invalidateSize();
            clearMapOverlays();
            const executeRouteDrawing = (fLat, fLng, tLat, tLng) => {
                $('#mapLoader .mt-2').text('Finding Route...');
                $('#mapLoader').removeClass('hide');
                const createRoutePins = () => {
                    const createPin = (lat, lng, color, label) => {
                        const icon = L.divIcon({
                            className: '',
                            html: `<div style="background-color: ${color}; width: 18px; height: 18px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 8px rgba(0,0,0,0.6);"></div>`,
                            iconSize: [18, 18],
                            iconAnchor: [9, 9]
                        });
                        let pin = L.marker([lat, lng], {
                            icon: icon
                        }).addTo(driversMap).bindPopup(`
                        <div style="font-family:sans-serif; text-align:center; min-width: 140px;">
                            <span class="badge mb-1 shadow-sm" style="font-size:11px; padding: 5px 8px; background-color: #6c757d !important; color: white;">${jobNo}</span><br>
                            <span style="font-size: 13px; font-weight: 600; color: #333;">${label}</span>
                        </div>
                    `, {
                            autoClose: false,
                            closeOnClick: false
                        }).openPopup();
                        routePins.push(pin);
                    };
                    createPin(fLat, fLng, '#198754', 'Pickup Location');
                    createPin(tLat, tLng, '#dc3545', 'Drop Location');
                };
                if (typeof L.Routing === 'undefined') {
                    let fallbackBounds = L.latLngBounds([
                        [fLat, fLng],
                        [tLat, tLng]
                    ]);
                    driversMap.fitBounds(fallbackBounds, {
                        padding: [50, 50]
                    });
                    createRoutePins();
                    highlightBiddersOnMap(jobNo, true);
                    $('#mapLoader').addClass('hide');
                    $('#mapLoader .mt-2').text('Loading Live Drivers...');
                    animateCar([{
                        lat: fLat,
                        lng: fLng
                    }, {
                        lat: tLat,
                        lng: tLng
                    }]);
                    return;
                }
                currentRouteControl = L.Routing.control({
                    waypoints: [L.latLng(fLat, fLng), L.latLng(tLat, tLng)],
                    routeWhileDragging: false,
                    addWaypoints: false,
                    fitSelectedRoutes: true,
                    show: false,
                    lineOptions: {
                        styles: [{
                            color: 'transparent',
                            opacity: 0,
                            weight: 0
                        }]
                    },
                    createMarker: function() {
                        return null;
                    }
                }).addTo(driversMap);
                createRoutePins();
                currentRouteControl.on('routesfound', function(e) {
                    $('#mapLoader').addClass('hide');
                    $('#mapLoader .mt-2').text('Loading Live Drivers...');
                    highlightBiddersOnMap(jobNo, true);
                    animateCar(e.routes[0].coordinates);
                });
                currentRouteControl.on('routingerror', function() {
                    try {
                        driversMap.removeControl(currentRouteControl);
                    } catch (err) {}
                    currentRouteControl = null;
                    let fallbackBounds = L.latLngBounds([
                        [fLat, fLng],
                        [tLat, tLng]
                    ]);
                    driversMap.fitBounds(fallbackBounds, {
                        padding: [50, 50]
                    });
                    highlightBiddersOnMap(jobNo, true);
                    $('#mapLoader').addClass('hide');
                    $('#mapLoader .mt-2').text('Loading Live Drivers...');
                    animateCar([{
                        lat: fLat,
                        lng: fLng
                    }, {
                        lat: tLat,
                        lng: tLng
                    }]);
                });
            };
            const fallbackToOSMGeocoding = async () => {
                $('#mapLoader .mt-2').text('Searching Internet for City Locations...');
                $('#mapLoader').removeClass('hide');
                try {
                    let resFrom = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(fromName)}`);
                    let dataFrom = await resFrom.json();
                    let resTo = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(toName)}`);
                    let dataTo = await resTo.json();
                    if (dataFrom.length > 0 && dataTo.length > 0) {
                        executeRouteDrawing(parseFloat(dataFrom[0].lat), parseFloat(dataFrom[0].lon), parseFloat(dataTo[0].lat), parseFloat(dataTo[0].lon));
                    } else {
                        $('#mapLoader').addClass('hide');
                        toast('error', 'Could not locate these cities on the map.');
                    }
                } catch (e) {
                    $('#mapLoader').addClass('hide');
                    toast('error', 'Map server search failed.');
                }
            };
            if (!fromLat || !fromLng || !toLat || !toLng || isNaN(fromLat) || isNaN(toLat)) {
                if (fromPlaceId && toPlaceId && fromPlaceId !== 'undefined' && toPlaceId !== 'undefined') {
                    $('#mapLoader .mt-2').text('Checking Database for Coordinates...');
                    $('#mapLoader').removeClass('hide');
                    $.ajax({
                        url: window.location.origin + "/ajax/service/driverServices.php",
                        type: "POST",
                        dataType: "json",
                        data: {
                            method: 'get_coords_by_place_id',
                            from_place_id: fromPlaceId,
                            to_place_id: toPlaceId
                        },
                        success: function(res) {
                            if (res.status && res.data && res.data.from_lat && res.data.to_lat) {
                                executeRouteDrawing(parseFloat(res.data.from_lat), parseFloat(res.data.from_lng), parseFloat(res.data.to_lat), parseFloat(res.data.to_lng));
                            } else {
                                fallbackToOSMGeocoding();
                            }
                        },
                        error: function() {
                            fallbackToOSMGeocoding();
                        }
                    });
                } else {
                    fallbackToOSMGeocoding();
                }
            } else {
                executeRouteDrawing(fromLat, fromLng, toLat, toLng);
            }
        }, 300);
    });
    function animateCar(coords) {
        if (!coords || coords.length === 0) return;
        if (animatedCarMarker) driversMap.removeLayer(animatedCarMarker);
        if (carAnimationTimer) clearInterval(carAnimationTimer);
        if (animatedLineObj) driversMap.removeLayer(animatedLineObj);
        animatedLineObj = L.polyline([], {
            color: '#0d6efd',
            weight: 5,
            opacity: 0.8
        }).addTo(driversMap);
        const carIcon = L.divIcon({
            className: '',
            html: `<div style="background: white; border-radius: 50%; padding: 5px; box-shadow: 0 2px 6px rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center;"><i class="fa fa-car text-primary" style="font-size: 14px;"></i></div>`,
            iconSize: [28, 28],
            iconAnchor: [14, 14]
        });
        animatedCarMarker = L.marker([coords[0].lat, coords[0].lng], {
            icon: carIcon,
            zIndexOffset: 1000
        }).addTo(driversMap);
        let i = 0;
        const speed = Math.max(1, Math.floor(coords.length / 80));
        carAnimationTimer = setInterval(() => {
            if (i < coords.length) {
                animatedCarMarker.setLatLng([coords[i].lat, coords[i].lng]);
                animatedLineObj.setLatLngs(coords.slice(0, i + 1));
                i += speed;
            } else {
                animatedCarMarker.setLatLng([coords[coords.length - 1].lat, coords[coords.length - 1].lng]);
                animatedLineObj.setLatLngs(coords);
                clearInterval(carAnimationTimer);
            }
        }, 30);
    }
    function safeRefreshScheduleUI(driverId) {
        $('#scheduledJobsList').html('<div class="text-center p-3 text-muted"><i class="fa fa-spinner fa-spin me-2"></i>Refreshing schedule...</div>');
        $('#dynamicScheduleWrapper').empty();
        $.ajax({
            url: "ajax/service/driverServices.php",
            type: "POST",
            dataType: "json",
            data: {
                method: 'get_driver_schedule',
                driver_id: driverId
            },
            success: function(res) {
                let jobCount = (res.status && res.data) ? res.data.length : 0;
                let jobScore = Math.min(jobCount, 5);
                let badgeClass = jobScore === 0 ? 'bg-danger text-white' : (jobScore < 5 ? 'bg-warning text-dark' : 'bg-success text-white');
                $('#status-jobs').html(`<span class="badge ${badgeClass} ms-2" style="font-size: 11px; padding: 3px 6px;">${jobScore}/5</span>`);
                if (jobCount > 0) {
                    let viewHtml = '';
                    res.data.forEach(item => {
                        let d = new Date(String(item.date).replace(' ', 'T'));
                        if (isNaN(d.getTime())) return;
                        let formattedDate = d.toLocaleString('en-GB', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: true
                        }).toUpperCase();
                        viewHtml += `
                    <div class="schedule-item p-1 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fa fa-map-marker-alt text-success me-2"></i> 
                            <strong>${item.from}</strong> <i class="fa fa-arrow-right text-muted" style="font-size: 10px;"></i> <strong>${item.to}</strong>
                        </div>
                        <div class="text-end">
                            <span class="d-block fw-bold text-info">₹${item.price}</span>
                            <span class="text-muted small"><i class="fa fa-calendar-alt text-danger me-1"></i>${formattedDate}</span>
                        </div>
                    </div>`;
                    });
                    $('#scheduledJobsList').html(viewHtml);
                } else {
                    $('#scheduledJobsList').html('<div class="text-center p-3 text-muted">No upcoming scheduled jobs found.</div>');
                }
            }
        });
        $.ajax({
            url: API_DOMAIN_2 + "admin-driver-profile-update",
            type: "POST",
            headers: {
                "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
            },
            data: {
                driver_id: driverId
            }
        });
    }
    $(document).off('click', '#openAutoScheduleModalBtn').on('click', '#openAutoScheduleModalBtn', function(e) {
        e.preventDefault();
        let driverId = window.currentDriverId;
        if (!driverId) {
            toast('error', 'Please select a driver first');
            return;
        }
        let $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
        $.ajax({
            url: "ajax/service/driverServices.php",
            type: "POST",
            dataType: "json",
            data: {
                method: 'get_last_5_schedules',
                driver_id: driverId
            },
            success: function(res) {
                if (res.status && res.data.length > 0) {
                    let tbodyHtml = '';
                    let baseDate = new Date();
                    baseDate.setDate(baseDate.getDate() + 1);
                    res.data.forEach((job, index) => {
                        let nextDate = new Date(baseDate);
                        nextDate.setDate(nextDate.getDate() + index);
                        let yyyy = nextDate.getFullYear();
                        let mm = String(nextDate.getMonth() + 1).padStart(2, '0');
                        let dd = String(nextDate.getDate()).padStart(2, '0');
                        let formattedDate = `${yyyy}-${mm}-${dd}T10:00`;
                        tbodyHtml += `
                        <tr class="auto-sch-row">
                            <td><input type="text" class="form-control form-control-sm auto-from fw-bold" value="${job.from_place}"></td>
                            <td><input type="text" class="form-control form-control-sm auto-to fw-bold" value="${job.to_place}"></td>
                            <td><input type="datetime-local" class="form-control form-control-sm auto-date" value="${formattedDate}"></td>
                            <td><input type="number" class="form-control form-control-sm auto-price text-success fw-bold" value="${job.price || 0}" min="1"></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-danger py-1 px-2" onclick="$(this).closest('tr').remove()"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>`;
                    });
                    $('#autoScheduleTbody').html(tbodyHtml);
                    $('#autoScheduleModal').modal('show');
                } else {
                    toast('error', 'No recent schedules found to copy.');
                }
            },
            error: function() {
                toast('error', 'Server error while fetching schedules.');
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fa fa-magic me-1"></i> Re-Schedule');
            }
        });
    });
    $(document).off('click', '#confirmAutoScheduleBtn').on('click', '#confirmAutoScheduleBtn', function(e) {
        e.preventDefault();
        let driverId = window.currentDriverId;
        let routes = [];
        let isValid = true;
        $('.auto-sch-row').each(function() {
            let from = $(this).find('.auto-from').val().trim();
            let to = $(this).find('.auto-to').val().trim();
            let date = $(this).find('.auto-date').val();
            let price = $(this).find('.auto-price').val();
            if (!from || !to || !date || !price) {
                $(this).addClass('table-danger');
                isValid = false;
            } else {
                $(this).removeClass('table-danger');
                let formattedDateTime = date.replace('T', ' ') + ":00";
                routes.push({
                    from,
                    to,
                    dates: {
                        [formattedDateTime]: parseInt(price, 10)
                    }
                });
            }
        });
        if (!isValid) {
            toast('error', 'Please fill all fields or remove empty rows.');
            return;
        }
        if (routes.length === 0) {
            toast('error', 'No schedules to save.');
            return;
        }
        let $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Saving...');
        $.ajax({
            url: "ajax/service/driverServices.php",
            type: "POST",
            dataType: "json",
            data: {
                method: 'auto_save_bulk_schedule',
                driver_id: driverId,
                routes: JSON.stringify(routes)
            },
            success: function(res) {
                if (res.status) {
                    toast('success', res.message);
                    $('#autoScheduleModal').modal('hide');
                    window.loadDriverSchedule(driverId);
                    window.recalculateDriverScore(driverId);
                } else {
                    toast('error', res.message);
                }
            },
            error: function() {
                toast('error', 'Server error while saving schedules');
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> Save Schedules');
            }
        });
    });
    $(document).off('click', '#saveScheduleBtn').on('click', '#saveScheduleBtn', function(e) {
        e.preventDefault();
        let driverId = window.currentDriverId;
        let routes = [];
        let isValid = true;
        let errorMessage = "Please fill all required fields.";
        let uniqueEntries = new Set();
        if ($('.schedule-route-box').length === 0) {
            toast('error', "Schedule is empty! Add a route or close the editor.");
            return;
        }
        $('.schedule-route-box, .schedule-date-row').css('background-color', 'transparent');
        $('.schedule-route-box').each(function() {
            let from = $(this).find('.sch-from').val();
            let to = $(this).find('.sch-to').val();
            let hasData = false;
            $(this).find('.sch-datetime, .sch-price').each(function() {
                if ($(this).val() !== '') hasData = true;
            });
            if (from || to || hasData) {
                if (!from || !to) {
                    isValid = false;
                    $(this).css('background-color', '#ffe6e6');
                    errorMessage = "Please select both From and To cities for the route.";
                    return false;
                }
                let newRoute = {
                    from: from,
                    to: to,
                    dates: {}
                };
                let hasValidDate = false;
                $(this).find('.schedule-date-row').each(function() {
                    let rawDate = $(this).find('.sch-datetime').val();
                    let rawPrice = $(this).find('.sch-price').val();
                    if (rawDate || rawPrice) {
                        let priceInt = parseInt(rawPrice, 10);
                        if (!rawDate || !rawPrice || isNaN(priceInt) || priceInt <= 0) {
                            isValid = false;
                            $(this).css('background-color', '#ffe6e6');
                            errorMessage = "Please provide valid Date and Price (Price > 0).";
                            return false;
                        }
                        let dateOnly = rawDate.split('T')[0];
                        let uniqueKey = `${from}_${to}_${dateOnly}`;
                        if (uniqueEntries.has(uniqueKey)) {
                            isValid = false;
                            $(this).css('background-color', '#ffe6e6');
                            errorMessage = `Duplicate trip found: ${from} to ${to} on the same date.`;
                            return false;
                        }
                        uniqueEntries.add(uniqueKey);
                        let formattedDateTime = rawDate.replace('T', ' ') + ":00";
                        newRoute.dates[formattedDateTime] = priceInt;
                        hasValidDate = true;
                    }
                });
                if (hasValidDate) {
                    routes.push(newRoute);
                } else if (!hasValidDate && isValid) {
                    isValid = false;
                    $(this).css('background-color', '#ffe6e6');
                    errorMessage = "Please add at least one valid Date and Price for the route.";
                    return false;
                }
            }
        });
        if (!isValid) {
            toast('error', errorMessage);
            return;
        }
        if (routes.length === 0) {
            toast('error', "Schedule cannot be empty.");
            return;
        }
        let $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $.ajax({
            url: "ajax/service/driverServices.php",
            type: "POST",
            dataType: "json",
            data: {
                method: 'save_driver_schedule',
                driver_id: driverId,
                routes: JSON.stringify(routes)
            },
            success: function(res) {
                if (res.status) {
                    toast('success', res.message);
                    $('#edit-jobs').hide();
                    $('#view-jobs').show();
                    $('.edit-section-trigger[data-section="jobs"]').show();
                    safeRefreshScheduleUI(driverId);
                } else {
                    toast('error', res.message);
                }
            },
            error: function() {
                toast('error', 'Server error while saving schedules');
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i>Save');
            }
        });
    });
    let mapSearchTimeout;
    let mapSearchTargetMarker = null;
    let allMapDriversCache = null;
    function renderMapDriverResults(drivers, searchVal, resultsBox) {
        let filtered = drivers;
        if (searchVal) {
            const lowerSearch = searchVal.toLowerCase();
            filtered = drivers.filter(d =>
                (d.name && String(d.name).toLowerCase().includes(lowerSearch)) ||
                (d.mobile && String(d.mobile).toLowerCase().includes(lowerSearch))
            );
        }
        filtered = filtered.slice(0, 20);
        if (filtered.length > 0) {
            let html = '';
            filtered.forEach(d => {
                let driverId = d.user_id || d.id;
                html += `
            <a href="#" class="list-group-item list-group-item-action py-2 map-driver-item border-bottom" data-id="${driverId}" data-name="${d.name}" data-mobile="${d.mobile}">
                <div class="d-flex align-items-center">
                    <i class="fa fa-user-circle text-muted fs-4 me-3"></i>
                    <div style="font-size: 13px; line-height: 1.3;">
                        <strong class="text-dark">${d.name || 'Unknown'}</strong><br>
                        <small class="text-muted"><i class="fa fa-phone me-1"></i>${d.mobile || 'N/A'}</small>
                    </div>
                </div>
            </a>`;
            });
            resultsBox.html(html);
        } else {
            resultsBox.html('<div class="list-group-item py-3 text-muted text-center" style="font-size:13px">No drivers found</div>');
        }
    }
    function fetchMapDrivers(searchVal) {
        const resultsBox = $('#mapDriverSearchResults');
        resultsBox.html('<div class="list-group-item text-center text-muted py-3" style="font-size:13px"><i class="fa fa-spinner fa-spin me-2"></i>Loading drivers...</div>').removeClass('d-none');
        if (allMapDriversCache) {
            renderMapDriverResults(allMapDriversCache, searchVal, resultsBox);
        } else {
            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-search-map-drivers",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                data: {
                    search: searchVal
                },
                success: function(res) {
                    if (res.status && res.data) {
                        allMapDriversCache = res.data;
                        renderMapDriverResults(allMapDriversCache, searchVal, resultsBox);
                    } else {
                        resultsBox.html('<div class="list-group-item py-3 text-muted text-center" style="font-size:13px">No active drivers found</div>');
                    }
                },
                error: function(xhr) {
                    console.error("API Error:", xhr.responseText);
                    resultsBox.html('<div class="list-group-item py-2 text-danger text-center" style="font-size:13px">Error loading drivers.</div>');
                }
            });
        }
    }
    $(document).on('click focus', '#mapDriverSearch', function(e) {
        e.stopPropagation();
        $('#mapDriverSearchResults').removeClass('d-none');
        if ($(this).val().trim() === '') {
            fetchMapDrivers('');
        }
    });
    $(document).on('keyup', '#mapDriverSearch', function() {
        clearTimeout(mapSearchTimeout);
        const searchVal = $(this).val().trim();
        mapSearchTimeout = setTimeout(() => {
            fetchMapDrivers(searchVal);
        }, 350);
    });
    $(document).on('click', '.map-driver-item', function(e) {
        e.preventDefault();
        const driverId = $(this).data('id');
        const driverName = $(this).data('name');
        $('#mapDriverSearchResults').addClass('d-none');
        $('#mapDriverSearch').val(driverName);
        $.ajax({
            url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-get-driver-location",
            type: "POST",
            dataType: "json",
            headers: {
                "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
            },
            data: {
                driver_id: driverId
            },
            success: function(res) {
                if (res.status && res.data) {
                    const lat = parseFloat(res.data.lat);
                    const lng = parseFloat(res.data.lng);
                    const lastUpdated = res.data.updated_at || 'Just now';
                    let driver = (typeof driversAll !== 'undefined' && driversAll[driverId]) ? driversAll[driverId].fullData : {
                        name: driverName,
                        mobile: res.data.mobile || ''
                    };
                    let vModel = 'N/A',
                        vSeats = 'N/A',
                        vFuel = 'N/A';
                    let cabType = driver.cab_type || driver.vehicle_type || 'Driver';
                    if (driver.vehicle_details) {
                        try {
                            const vData = typeof driver.vehicle_details === 'string' ? JSON.parse(driver.vehicle_details) : driver.vehicle_details;
                            vModel = vData?.rc_details?.response?.vehicle_details?.maker_model || driver.maker_model || 'N/A';
                            vFuel = vData?.rc_details?.response?.vehicle_details?.fuel_type || driver.fuel_type || 'N/A';
                            let seatCap = vData?.rc_details?.response?.vehicle_details?.seat_capacity || driver.seat;
                            vSeats = (seatCap > 1) ? (seatCap - 1) + "+1" : (seatCap || 'N/A');
                        } catch (e) {}
                    }
                    let cleanMobile = driver.mobile ? String(driver.mobile).replace(/\D/g, '') : '';
                    let waMobile = cleanMobile.length === 10 ? '91' + cleanMobile : cleanMobile;
                    if (lat && lng && typeof driversMap !== 'undefined' && driversMap) {
                        driversMap.invalidateSize();
                        setTimeout(() => {
                            driversMap.flyTo([lat, lng], 16, {
                                duration: 1.5
                            });
                            if (mapSearchTargetMarker) {
                                driversMap.removeLayer(mapSearchTargetMarker);
                            }
                            const redPin = L.divIcon({
                                className: '',
                                html: `<div style="background-color: #dc3545; width: 18px; height: 18px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 8px rgba(0,0,0,0.6);"></div>`,
                                iconSize: [18, 18],
                                iconAnchor: [9, 9]
                            });
                            mapSearchTargetMarker = L.marker([lat, lng], {
                                icon: redPin
                            }).addTo(driversMap);
                            showRichDriverPopup(mapSearchTargetMarker, driverId, driverName, lastUpdated, res.data.mobile);
                        }, 300);
                    }
                } else {
                    toast('error', 'Location not found or GPS is turned off for this driver.');
                }
            },
            error: function(xhr) {
                toast('error', 'Failed to fetch driver location.');
            }
        });
    });
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#mapDriverSearch, #mapDriverSearchResults').length) {
            $('#mapDriverSearchResults').addClass('d-none');
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        if (!window.editLanguagesTomSelect) {
            window.editLanguagesTomSelect = new TomSelect("#edit_languages", {
                plugins: ['remove_button'],
                placeholder: "Select Languages",
                maxItems: null,
            });
        }
        if (!window.editStateTomSelect) {
            window.editStateTomSelect = new TomSelect("#edit_state", {
                placeholder: "Select State",
                maxItems: 1,
                allowEmptyOption: true
            });
        }
    });
    $(document).on('change', '.local-image-select', function(e) {
        let file = this.files[0];
        let target = $(this).data('target');
        let $previewImg = $('#prev_' + target);
        let $clearBtn = $('.clear-img-btn[data-target="' + target + '"]');
        if (!file) {
            $clearBtn.addClass('d-none');
            return;
        }
        let validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            toast('error', 'Only JPG, JPEG, and PNG formats are allowed.');
            $(this).val('');
            $clearBtn.addClass('d-none');
            return;
        }
        let maxSize = 2 * 1024 * 1024;
        if (file.size > maxSize) {
            toast('error', 'Image size must be less than 2MB.');
            $(this).val('');
            $clearBtn.addClass('d-none');
            return;
        }
        let reader = new FileReader();
        reader.onload = function(e) {
            $previewImg.attr('src', e.target.result);
            $clearBtn.removeClass('d-none');
        };
        reader.readAsDataURL(file);
    });
    $(document).on('click', '.clear-img-btn', function() {
        let target = $(this).data('target');
        let $input = $('.local-image-select[data-target="' + target + '"]');
        let $previewImg = $('#prev_' + target);
        $input.val('');
        $previewImg.attr('src', 'assets/images/placeholder.png');
        $(this).addClass('d-none');
    });
    $(document).on('click', '#saveVehicleImagesBtn', async function() {
        let $btn = $(this);
        let inputsWithFiles = $('.local-image-select').filter(function() {
            return this.files.length > 0;
        });
        if (inputsWithFiles.length === 0) {
            toast('info', 'No new images selected to upload.');
            $('#vehicleImagesModal').modal('hide');
            return;
        }
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Uploading...');
        let uploadPromises = [];
        inputsWithFiles.each(function() {
            let fileInput = this;
            let file = fileInput.files[0];
            let imageKey = $(this).data('key');
            let formData = new FormData();
            formData.append('image', file);
            formData.append('img_type', 'vehicle_' + imageKey);
            formData.append('name', 'driver_vehicle_upload');
            formData.append('auth_key', 'asdfghjklpoiuytrewqzxcvbnm!@$%^&*()');
            let uploadPromise = $.ajax({
                url: 'https://www.goride.run/api/v1-cus/s3-upload-image',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false
            }).then(response => {
                if (response.status) {
                    let s3Url = response.data.url;
                    $('#val_' + imageKey).val(s3Url);
                    if (imageKey === 'front_view_image_url') {
                        $('#main_vehicle_preview').attr('src', s3Url);
                        $('#mainGalleryImage').attr('src', s3Url);
                    } else {
                        $('#gallery_' + imageKey.replace('_url', '')).attr('src', s3Url);
                    }
                } else {
                    throw new Error(response.message || `Upload failed for ${imageKey}`);
                }
            });
            uploadPromises.push(uploadPromise);
        });
        try {
            await Promise.all(uploadPromises);
            $btn.html('<i class="fa fa-spinner fa-spin me-1"></i> Saving to DB...');
            refreshVehicleGallery();
            let $dbSaveBtn = $('.save-section[data-section="vehicle"]');
            if ($dbSaveBtn.length) {
                $dbSaveBtn.trigger('click');
            }
            toast('success', 'Images uploaded and saved successfully!');
            setTimeout(() => {
                $btn.prop('disabled', false).html('<i class="fa fa-upload me-1"></i>Upload & Save');
                $('#vehicleImagesModal').modal('hide');
                $('.local-image-select').val('');
                $('.clear-img-btn').addClass('d-none');
            }, 1200);
        } catch (error) {
            toast('error', error.message || 'Some images failed to upload. Please try again.');
            $btn.prop('disabled', false).html('<i class="fa fa-upload me-1"></i>Upload & Save');
        }
    });
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
        1: {
            name: 'Driver A',
            color: '#0d6efd',
            status: 'online',
            profilePercent: 90
        },
        2: {
            name: 'Driver B',
            color: '#198754',
            status: 'busy',
            profilePercent: 60
        },
        3: {
            name: 'Driver C',
            color: '#dc3545',
            status: 'offline',
            profilePercent: 45
        },
        4: {
            name: 'Driver D',
            color: '#6f42c1',
            status: 'online',
            profilePercent: 78
        },
        5: {
            name: 'Driver E',
            color: '#fd7e14',
            status: 'busy',
            profilePercent: 100
        },
        6: {
            name: 'Driver F',
            color: '#20c997',
            status: 'online',
            profilePercent: 27
        },
        7: {
            name: 'Driver G',
            color: '#0dcaf0',
            status: 'offline',
            profilePercent: 82
        }
    };
    $(document).ready(function() {
        const jobs = {
            1021: {
                title: 'Job #1021',
                date: '2026-01-15',
                pickup: 'Chennai',
                drop: 'Salem'
            },
            1022: {
                title: 'Job #1022',
                date: '2026-01-16',
                pickup: 'Trichy',
                drop: 'Madurai'
            },
            1023: {
                title: 'Job #1023',
                date: '2026-01-16',
                pickup: 'Chennai',
                drop: 'Coimbatore'
            },
            1024: {
                title: 'Job #1024',
                date: '2026-01-20',
                pickup: 'Salem',
                drop: 'Trichy'
            },
            1025: {
                title: 'Job #1025',
                date: '2026-01-21',
                pickup: 'Erode',
                drop: 'Chennai'
            },
            1026: {
                title: 'Job #1026',
                date: '2026-01-23',
                pickup: 'Madurai',
                drop: 'Tirunelveli'
            },
            1027: {
                title: 'Job #1027',
                date: '2026-01-25',
                pickup: 'Vellore',
                drop: 'Salem'
            },
            1028: {
                title: 'Job #1028',
                date: '2026-01-26',
                pickup: 'Chennai',
                drop: 'Trichy'
            },
            1029: {
                title: 'Job #1029',
                date: '2026-01-28',
                pickup: 'Coimbatore',
                drop: 'Erode'
            },
            1030: {
                title: 'Job #1030',
                date: '2026-01-30',
                pickup: 'Salem',
                drop: 'Chennai'
            }
        };
        const scheduledTrips = [{
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
        const requestedJobs = [{
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
                        <div class="fw-semibold text-dark">
                            <i class="fa fa-user text-primary me-2"></i>
                            ${driverName}
                        </div>
                        <div class="text-dark">
                            <i class="fa fa-map-marker-alt text-success me-2"></i>
                            ${trip.from}
                        </div>
                        <div class="text-dark">
                            <i class="fa fa-map-marker-alt text-danger me-2"></i>
                            ${trip.to}
                        </div>
                        <div class="text-dark">
                            <i class="fa fa-calendar-alt text-warning me-2"></i>
                            ${trip.date}
                        </div>
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
                const matchCount = getMatchingJobCount(req);
                html += `
        <div class="card mb-2 shadow-sm border-0 driver-request cursor-pointer"
             data-request="${req.id}">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fw-semibold mb-1">
                            <i class="fa fa-user text-primary me-2"></i>
                            ${req.driverName}
                        </div>
                        <div class="small">
                            <i class="fa fa-map-marker-alt text-success me-1"></i>
                            <strong>${req.from}</strong>
                            <i class="fa fa-arrow-right mx-2 text-muted"></i>
                            <i class="fa fa-map-marker-alt text-danger me-1"></i>
                            <strong>${req.to}</strong>
                        </div>
                        <div class="text-muted small mt-1">
                            <i class="fa fa-calendar-alt text-warning me-1"></i>
                            ${req.date}
                        </div>
                    </div>
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
            $('.select2').select2({
                width: '100%',
                placeholder: 'Select',
                allowClear: true
            });
        }
        $('#searchScheduledJobs').on('click', function() {
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
            const options = {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            };
            return date.toLocaleDateString('en-GB', options);
        }
        function renderJobLists() {
            $('#unassignedJobs').empty();
            $('#scheduledJobs').empty();
            $('#assignedJobs').empty();
            Object.entries(jobs).forEach(([jobId, job]) => {
                const assignedDriver = assignments[jobId];
                const jobHtml = `
                    <div class="card mb-2 shadow-sm ddddd border-0">
                        <div class="card-body p-3" >
                          <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="d-flex align-items-center gap-2">
                    <h6 class="mb-0 fw-bold">${job.title}</h6>
                    <a href="#"
                       class="text-warning jobEdit"
                       title="Edit Job"
                       data-job="${jobId}">
                        <i class="fa fa-edit text-info"></i>
                    </a>
                    <a href="https://www.goride.run/booking-information/3d58d95584b7651a83185e3941471aac7d12eb1f7385e7a90dd70f7f978b4f00"
                       class="text-primary jobView"
                       title="View Job"
                       target="_blank">
                        <i class="fa fa-eye text-secondary"></i>
                    </a>
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
                    ${!assignedDriver ? `
                   <button class="btn btn-sm btn-warning bidBtn text-dark px-2 ms-2 position-relative"
                        data-job="${jobId}">
                    <i class="fa fa-gavel text-dark"></i>
                    Bids
                    <span class="position-absolute top-0 start-100 translate-middle
                                 badge rounded-pill bg-danger">
                        4
                    </span>
                </button>
                ` : ''}
                </div>
                       <div class="mt-2 row small text-muted">
                    <div class="col-6">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fa fa-user-tie text-dark " style="width:16px;"></i>
                <span class="d-inline-flex align-items-center gap-2">
                    <a href="#" class="owner-link fw-bold" data-owner="101">
                        Ramesh
                    </a>
                    <a href="tel:9876543210"
                       class="call-con"
                       title="Call Owner">
                        <i class="fa fa-phone"></i>
                    </a>
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
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fa fa-map-marker-alt text-success " style="width:16px;"></i>
                            <span> <strong>Chennai</strong></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fa fa-map-marker-alt text-danger " style="width:16px;"></i>
                            <span><strong>Salem</strong></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between gap-4 mb-1">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-exchange-alt text-info " style="width:16px;"></i>
                                <span><strong>One Way</strong></span>
                            </div>
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
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between pt-1 ">
                           <div class="text-end">
                    <div class="small text-muted">Base Fare: ₹4525</div>
                    <div class="small text-muted">Toll: ₹495</div>
                    <div class="fw-bold text-dark" style=""font-size:14px;>₹5020</div>
                </div>
                           <span class="text-muted cursor-pointer cancelJobBtn"
      style="font-size:14px"
      data-job="${jobId}"
      data-job-no="${job.job_no}"
      data-jobtype="${job.global_type}"
      data-user-id="${job.user_id}"
      data-jobstatus="${job.job_status}">
    <i class="fa fa-times-circle text-danger"></i> Cancel
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
        populateDriverDropdown();
        renderScheduledJobs();
        initScheduledLocationFilters();
        renderRequestedJobs();
        $(document).on('click', '.assignBtn', function() {
            const jobId = $(this).data('job');
            const job = jobs[jobId];
            $('#job_id').val(jobId);
            $('#driver_id').val('');
            $('#notify_whatsapp, #notify_sms').prop('checked', true);
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
        $('#assignBtn').on('click', function() {
            const jobId = $('#job_id').val();
            const driverId = $('#driver_id').val();
            const notifyWhatsapp = $('#notify_whatsapp').is(':checked');
            const notifySms = $('#notify_sms').is(':checked');
            if (!driverId) {
                alert('Please select a driver');
                return;
            }
            assignments[jobId] = driverId;
            console.log('Assignment created:', {
                jobId,
                driverId,
                notifyWhatsapp,
                notifySms,
                assignments
            });
            let notifyMsg = [];
            if (notifyWhatsapp) notifyMsg.push('WhatsApp');
            if (notifySms) notifyMsg.push('SMS');
            if (notifyMsg.length > 0) {
                console.log(`Notifications sent via: ${notifyMsg.join(' & ')}`);
            }
            renderJobLists();
            $('#assignDriverModal').modal('hide');
            const driverTabBtn = document.querySelector('button[data-bs-target="#driversTab"]');
            const tab = new bootstrap.Tab(driverTabBtn);
            $(driverTabBtn).one('shown.bs.tab', function() {
                selectedDriver = null;
                $('.driver-item').removeClass('active');
                setTimeout(() => {
                    refreshCalendar();
                    calendar.updateSize();
                }, 150);
            });
            tab.show();
        });
        $('#jobSearch').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('#unassignedJobs .card, #scheduledJobs .card').each(function() {
                const jobText = $(this).text().toLowerCase();
                $(this).toggle(jobText.includes(searchTerm));
            });
            const unassignedVisible = $('#unassignedJobs .card:visible').length;
            if (searchTerm && unassignedVisible === 0) {
                if ($('#unassignedJobs .no-results').length === 0) {
                    // $('#unassignedJobs').append('<div class="alert alert-warning text-center mb-0 no-results">No jobs found</div>');
                }
            } else {
                $('#unassignedJobs .no-results').remove();
            }
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
        $('#driverStatus').on('change', function() {
            const status = $(this).val().toLowerCase();
            $('.driver-item').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(!status || text.includes(status));
            });
        });
    });
    let driversMap = null;
    let markerClusterGroup = null;
    let driverMarkers = {};
    let tempDriverMarker = null;
    $(document).ready(function() {
        const firebaseConfig = {
            apiKey: "AIzaSyCiRKGU2xZyZNx5-ZwweLd5cPokxJjxKzw",
            authDomain: "goride-947ed.firebaseapp.com",
            projectId: "goride-947ed",
        };
        firebase.initializeApp(firebaseConfig);
        db = firebase.firestore();
        let unassignedJobs = {};
        let websiteJobs = {};
        let assignedJobs = {};
        let cancelledJobs = {};
        let rejectData = {};
        let selectedDriver = null;
        let calendarData = [];
        let driversAll = {};
        let currentJobId = null;
        let currentExistingBidders = new Set();
        let selectedFilterType = null;
        let selectedStartDate = null;
        let selectedEndDate = null;
        let isLoadingDrivers = false;
        function toggleSection(section, isEdit = true) {
            const viewEl = $('#view-' + section);
            const editEl = $('#edit-' + section);
            const editST = $(`.edit-section-trigger[data-section="${section}"]`);
            if (isEdit) {
                viewEl.hide();
                editST.hide();
                editEl.show();
            } else {
                editEl.hide();
                editST.show();
                viewEl.show();
            }
        }
        function initDriversMap() {
            $('#mapLoader').removeClass('hide');
            driversMap = L.map('driversMap', {
                zoomControl: true
            }).setView([13.0827, 80.2707], 10);
            L.tileLayer(
                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors',
                    maxZoom: 19
                }
            ).addTo(driversMap);
            markerClusterGroup = L.markerClusterGroup();
            driversMap.addLayer(markerClusterGroup);
            window.loadDriversOnMap();
        }
        let tempDriverMarker = null;
        $(document).on('click', '.view-driver-location', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const driverId = $(this).data('driver');
            const driverName = $(this).data('name');
            $('.backToMap').trigger('click');
            setTimeout(() => {
                if (driversMap) {
                    driversMap.invalidateSize();
                }
                $.ajax({
                    url: window.location.origin + "/ajax/service/driverServices.php",
                    type: "POST",
                    dataType: "json",
                    data: {
                        method: 'get_driver_current_location',
                        driver_id: driverId
                    },
                    success: function(res) {
                        if (res.status && res.data) {
                            const lat = parseFloat(res.data.lat);
                            const lng = parseFloat(res.data.lng);
                            const lastUpdated = res.data.updated_at;
                            driversMap.setView([lat, lng], 16, {
                                animate: true
                            });
                            if (tempDriverMarker) {
                                driversMap.removeLayer(tempDriverMarker);
                            }
                            const redPin = L.divIcon({
                                className: '',
                                html: `<div style="background-color: #dc3545; width: 18px; height: 18px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 8px rgba(0,0,0,0.6);"></div>`,
                                iconSize: [18, 18],
                                iconAnchor: [9, 9]
                            });
                            tempDriverMarker = L.marker([lat, lng], {
                                icon: redPin
                            }).addTo(driversMap);
                            tempDriverMarker.bindPopup(getDriverRichPopupHTML(driverId, driverName, lastUpdated, res.data.mobile)).openPopup();
                        } else {
                            toast('error', 'Location not found for this driver.');
                        }
                    },
                    error: function() {
                        toast('error', 'Failed to fetch driver location from server.');
                    }
                });
            }, 300);
        });
        window.loadDriversOnMap = function() {
            if (window.isLoadingDrivers) return;
            window.isLoadingDrivers = true;
            $('#mapLoader').removeClass('hide');
            $.ajax({
                url: API_DOMAIN_2 + "admin-get-driver-list",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                success: function(res) {
                    if (!res.status || !res.data) {
                        $('#mapLoader').addClass('hide');
                        window.isLoadingDrivers = false;
                        return;
                    }
                    if (typeof window.driversAll === 'undefined') window.driversAll = {};
                    res.data.forEach(driver => {
                        let status = driver.rm_status == 1 ? 'online' : (driver.rm_status == 2 ? 'busy' : 'offline');
                        window.driversAll[driver.id] = {
                            id: driver.id,
                            user_id: driver.user_id,
                            name: driver.name,
                            mobile: driver.mobile,
                            status: status,
                            profilePercent: driver.profile_percentage,
                            fullData: driver
                        };
                        if (!driver.s_lat || !driver.s_lang) return;
                        const lat = parseFloat(driver.s_lat);
                        const lng = parseFloat(driver.s_lang);
                        let vModel = 'N/A',
                            vSeats = 'N/A',
                            vFuel = 'N/A';
                        let cabType = driver.cab_type || driver.vehicle_type || 'Driver';
                        if (driver.vehicle_details) {
                            try {
                                const vData = typeof driver.vehicle_details === 'string' ? JSON.parse(driver.vehicle_details) : driver.vehicle_details;
                                vModel = vData?.rc_details?.response?.vehicle_details?.maker_model || driver.maker_model || 'N/A';
                                vFuel = vData?.rc_details?.response?.vehicle_details?.fuel_type || driver.fuel_type || 'N/A';
                                let seatCap = vData?.rc_details?.response?.vehicle_details?.seat_capacity || driver.seat;
                                vSeats = (seatCap > 1) ? (seatCap - 1) + "+1" : (seatCap || 'N/A');
                            } catch (e) {
                                console.error("Vehicle Parse Error", e);
                            }
                        }
                        let cleanMobile = driver.mobile ? String(driver.mobile).replace(/\D/g, '') : '';
                        let waMobile = cleanMobile.length === 10 ? '91' + cleanMobile : cleanMobile;
                        let lastSeenTime = driver.updated_at || driver.s_time || '';
                        let lastSeenHtml = lastSeenTime ? `<div class="text-center text-muted mt-2 fw-semibold" style="font-size: 10px;"><i class="fa fa-clock text-secondary me-1"></i>Last seen: ${lastSeenTime}</div>` : '';
                        let popupContent = `
                <div style="min-width:210px; font-family: sans-serif;">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <strong style="font-size: 14px; color: #333;" class="driver-name-head">${driver.name || 'Unknown'}</strong>
                        ${cabType !== 'Driver' ? `<span class="badge bg-success" style="font-size:10px;">${cabType}</span>` : ''}
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small text-muted"><i class="fa fa-phone text-primary me-1"></i>${driver.mobile || 'N/A'}</span>
                        ${cleanMobile ? `
                        <div class="d-flex gap-2">
                            <a href="tel:${cleanMobile}" class="text-decoration-none shadow-sm" title="Call" style="background:#e7f1ff; color:#0d6efd; width:26px; height:26px; display:flex; align-items:center; justify-content:center; border-radius:50%;"><i class="fa fa-phone" style="font-size:11px;"></i></a>
                            <a href="https://wa.me/${waMobile}" target="_blank" class="text-decoration-none shadow-sm" title="WhatsApp" style="background:#e9f7ef; color:#25d366; width:26px; height:26px; display:flex; align-items:center; justify-content:center; border-radius:50%;"><i class="fab fa-whatsapp" style="font-size:14px;"></i></a>
                        </div>
                        ` : ''}
                    </div>
                    <div class="border-top pt-2 mt-1" style="font-size: 11px; line-height: 1.5; background: #f8f9fa; padding: 8px; border-radius: 6px;">
                        <div class="text-dark mb-1"><i class="fa fa-car text-danger me-2"></i><strong>${vModel}</strong></div>
                        <div class="d-flex justify-content-between mt-1 text-muted">
                            <span><i class="fa fa-users text-info me-1"></i>${vSeats}</span>
                            <span><i class="fa fa-gas-pump text-warning me-1"></i>${vFuel}</span>
                        </div>
                    </div>
                    ${lastSeenHtml}
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm w-100 mt-2 py-1 driver-link" data-driver="${driver.user_id || driver.id}" style="font-size:12px; font-weight: 500;">View Profile</a>
                </div>`;
                        if (typeof driverMarkers !== 'undefined' && typeof markerClusterGroup !== 'undefined') {
                            if (!driverMarkers[driver.id]) {
                                const pulseIcon = L.divIcon({
                                    className: '',
                                    html: `<div class="pulse-marker"></div>`,
                                    iconSize: [20, 20],
                                    iconAnchor: [10, 10]
                                });
                                const marker = L.marker([lat, lng], {
                                    icon: pulseIcon
                                });
                                marker.bindPopup(popupContent);
                                marker.driver_table_id = driver.id;
                                marker.driver_user_id = driver.user_id;
                                markerClusterGroup.addLayer(marker);
                                driverMarkers[driver.id] = marker;
                            } else {
                                driverMarkers[driver.id].setLatLng([lat, lng]);
                                let currentHtml = driverMarkers[driver.id].getPopup().getContent();
                                if (typeof currentHtml === 'string' && !currentHtml.includes('Bid: ₹')) {
                                    driverMarkers[driver.id].setPopupContent(popupContent);
                                }
                            }
                        }
                    });
                    $('#mapLoader').addClass('hide');
                    window.isLoadingDrivers = false;
                },
                error: function() {
                    setTimeout(function() {
                        window.isLoadingDrivers = false;
                        window.window.loadDriversOnMap();
                    }, 3000);
                }
            });
        };
        initDriversMap();
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
            if (!dateStr || String(dateStr).trim() === '' || String(dateStr) === 'null' || String(dateStr) === '0000-00-00') {
                return 'N/A';
            }
            const d = new Date(dateStr);
            if (isNaN(d.getTime())) {
                return 'N/A';
            }
            return d.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }
        function normalizeJob(job, source) {
            let userDetails = {};
            let appSourceText = "Customer App";
            let isUserEmpty = !job.user_details || job.user_details === 'null' || job.user_details === '[]' || job.user_details === '{}' || String(job.user_details).trim() === '';
            if (typeof job.user_details === 'object' && job.user_details !== null && Object.keys(job.user_details).length === 0) {
                isUserEmpty = true;
            }
            if (job.global_type === 'schedule' || job.job_status === 'schedule') {
                appSourceText = "Scheduled";
            } else if (job.job_no && String(job.job_no).startsWith('GRD-')) {
                appSourceText = "Driver App";
            } else if (!isUserEmpty) {
                appSourceText = "Website Job";
            } else {
                appSourceText = "Customer App";
            }
            if (job.user_details) {
                if (typeof job.user_details === 'object') {
                    userDetails = job.user_details;
                } else if (typeof job.user_details === 'string') {
                    try {
                        userDetails = JSON.parse(job.user_details);
                    } catch (e) {
                        userDetails = {};
                    }
                }
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
            let cre_date = formatPickupDate(job.created_at);
            let coords = null;
            if (job.from_to_co) {
                try {
                    coords = typeof job.from_to_co === 'string' ? JSON.parse(job.from_to_co) : job.from_to_co;
                } catch (e) {
                    console.error("Coords parse error", e);
                }
            }
            return {
                id: job.id,
                title: job.job_no,
                job_no: job.job_no,
                job_type: job.job_type,
                from_place: job.from_place,
                to_place: job.to_place,
                from_place_id: job.from_place_id || '',
                to_place_id: job.to_place_id || '',
                pickup_date: job.pickup_date,
                dropoff_date: job.dropoff_date || null,
                day: job.day || null,
                distance: job.distance || null,
                duration: job.duration || null,
                base_fare: job.base_fare,
                com: job.com,
                discount: job.discount,
                isDiscount: job.isDiscount,
                toll_fare: job.toll_fare,
                fare: job.fare,
                pass_count: (job.pass_count && job.pass_count !== 'null' && job.pass_count !== '') ? job.pass_count : (userDetails.pass_count ?? 1),
                car_type: userDetails.cab_type ?? '',
                luggage: userDetails.lugg_count ?? 0,
                mobile: (userDetails.mobile && String(userDetails.mobile).trim() !== '') ?
                    String(userDetails.mobile).replace(/\D/g, '') : (job.mobile && String(job.mobile).trim() !== '') ?
                    String(job.mobile).replace(/\D/g, '') : (job.u_mobile && String(job.u_mobile).trim() !== '') ?
                    String(job.u_mobile).replace(/\D/g, '') : '',
                name: (userDetails.name && userDetails.name.trim() !== '') ?
                    userDetails.name : (job.poster_name && job.poster_name.trim() !== '') ?
                    job.poster_name : '-',
                email: (userDetails.email && userDetails.email.trim() !== '') ?
                    userDetails.email : (job.email && job.email.trim() !== '') ?
                    job.email : '',
                user_id: job.user_id || 0,
                global_type: job.global_type || '',
                bid_count: job.bid_count || 0,
                job_status: job.job_status,
                confirm_status: job.confirm_status || 0,
                preview_hash: job.preview_hash,
                created_date: cre_date,
                assigned_driver: assignedDriver,
                source: source,
                coords: coords,
                cancel_reason: job.cancel_reason || 'No specific reason provided',
                cancelled_by_display: job.cancelled_by_display || 'User',
                cancelled_at: job.cancelled_at || 'Unknown Time',
                cancel_doc: job.cancel_doc || null,
                tax: job.tax || 0,
                feedback_rating: job.feedback_rating !== undefined ? job.feedback_rating : 0,
                feedback_review: job.feedback_review || 'No text review provided.',
                app_source: appSourceText,
                push_count: job.count || 0,
                bids_details: job.bids_details,
                sch_status_data: (() => {
                    if (job.sch_status_data && job.sch_status_data.length > 0) return job.sch_status_data;
                    let schArr = [];
                    if (job.sch_status && typeof job.sch_status === 'string' && job.sch_status !== 'null') {
                        try {
                            let parsed = JSON.parse(job.sch_status);
                            Object.keys(parsed).forEach(date => {
                                Object.keys(parsed[date]).forEach(driverId => {
                                    schArr.push({
                                        date: date,
                                        driver_id: driverId,
                                        status: parsed[date][driverId].status || 'pending',
                                        driver_name: "Driver ID: " + driverId
                                    });
                                });
                            });
                        } catch (e) {
                            console.error("Error parsing sch_status", e);
                        }
                    }
                    return schArr;
                })()
            };
        }
        function getUnassignedActionButton(job) {
            // 1. Post Job button for website leads
            if (job.source === 'website' && job.job_status === 'created') {
                return `<button class="btn btn-sm btn-primary ms-2 d-flex justify-content-center align-items-center postJobBtn px-2" style="font-size:11px;" data-job='${JSON.stringify(job).replace(/'/g, "&apos;")}'> <i class="fa fa-paper-plane me-1"></i> Post Job </button>`;
            }
            // 2. Logic for Posted/Active Jobs
            if (job.source === 'posted') {
                let buttons = '';
                // NEW RESTRICTION: Hide Bids button if status OR global_type is 'schedule'
                const isSchedule = (job.job_status === 'schedule' || job.global_type === 'schedule');
                if (!isSchedule && ['created', 'bidding'].includes(job.job_status)) {
                    buttons += `<button class="btn btn-sm btn-warning d-flex justify-content-center align-items-center bidBtn text-dark ms-2 px-2 position-relative" data-job="${job.id}"> 
                            <i class="fa fa-gavel me-1 text-dark"></i> Bids 
                            ${job.bid_count > 0 ? `<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">${job.bid_count}</span>` : ''} 
                        </button>`;
                }
                // Show Requests button if there is schedule data
                if (job.sch_status_data && job.sch_status_data.length > 0) {
                    buttons += `
                <button class="btn btn-sm btn-info viewScheduledRequestsBtn d-flex justify-content-center align-items-center ms-2 text-white px-2 py-0 position-relative" 
                        style="font-size: 11px; height: 24px;"
                        data-requests='${JSON.stringify(job.sch_status_data).replace(/'/g, "&apos;")}'> 
                    <i class="fa fa-users me-1 text-white"></i> Requests 
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 9px; padding: 3px 5px;">
                        ${job.sch_status_data.length}
                    </span> 
                </button>`;
                }
                return buttons;
            }
            return '';
        }
        function renderJobCard(job, jobId, isExpired = false, showEdit = false) {
            const domain = "<?php echo APP_DOMAIN; ?>";
            let parsedBids = {};
            if (job.bids_details && job.bids_details !== 'null') {
                try {
                    if (typeof job.bids_details === 'string') {
                        let tempBids = JSON.parse(job.bids_details);
                        if (tempBids !== null && typeof tempBids === 'object') {
                            parsedBids = tempBids;
                        }
                    } else if (typeof job.bids_details === 'object') {
                        parsedBids = job.bids_details;
                    }
                } catch (e) {
                    console.error(`[Job ${job.job_no}] Error parsing bids:`, e);
                }
            }
            let bidsCount = Object.keys(parsedBids).length;
            console.log(`[Job ${job.job_no}] Total Bids Count:`, bidsCount);
            // --- UPDATED PASS_COUNT LOGIC ---
            let formattedPassCount = job.pass_count || 'N/A';
            if (job.pass_count) {
                let passStr = String(job.pass_count).trim().toLowerCase();
                // Check if the value is "mini"
                if (passStr === 'mini') {
                    formattedPassCount = '4+1(Mini)';
                }
                // Check if the string is purely numbers (prevents breaking if it already says "6+1")
                else if (/^\d+$/.test(passStr) && parseInt(passStr) > 0) {
                    formattedPassCount = (parseInt(passStr));
                }
                // Fallback for any other strings that are already formatted correctly
                else {
                    formattedPassCount = job.pass_count;
                }
            }
            // --------------------------------
            const isInactive = isExpired === true || ['cancelled', 'expired', 'deleted'].includes((job.job_status || '').toLowerCase());
            const safeJobData = encodeURIComponent(JSON.stringify(job));
            const safeBidsData = encodeURIComponent(JSON.stringify(parsedBids));
            let sColor = 'bg-success text-white';
            if (job.app_source === 'Driver App') sColor = 'bg-info text-dark';
            else if (job.app_source === 'Scheduled') sColor = 'bg-primary text-white';
            else if (job.app_source === 'Website Job') sColor = 'bg-warning text-dark';
            let sourceBadge = `<span class="badge ${sColor}" style="font-size: 10px;">${job.app_source}</span>`;
            return `
<div class="card mb-2 shadow-sm border-0">
    <div class="card-body p-3 ${job.global_type == 'open' && !isInactive ? 'card-open' : ''}">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                ${!isInactive ? `<span class="text-muted cursor-pointer cancelJobBtn" style="font-size:14px" data-jobtype="${job.global_type}" data-user-id="${job.user_id}" data-job="${job.id}" data-job-no="${job.job_no}" data-jobstatus="${job.job_status}"><i class="fa fa-times-circle text-danger"></i></span>` : `<span class="text-danger"><i class="fa fa-times-circle"></i></span>`}
                <span class="text-muted">|</span>
                <h6 class="mb-0 fw-bold d-flex align-items-center gap-1">
                    ${job.global_type === 'schedule' ? `<span class="text-dark" style="font-size:13px;">${job.title}</span>` : `<a href="https://console.goride.run/job-bidding?job_id=${job.id}" class="text-dark text-decoration-none" style="font-size:13px;" target="_blank">${job.title}</a>`}
                    ${sourceBadge}
                    ${job.job_no && String(job.job_no).startsWith('GRD') && !isInactive ? `${job.confirm_status == 0 || !job.confirm_status ? `<button class="btn btn-sm btn-info confirmGrdBtn" data-jobid="${job.id}" data-jobno="${job.job_no}" data-userid="${job.user_id}" data-jobtype="${job.global_type}" style="font-size: 11px; padding: 2px 6px;"><i class=""></i> Confirm</button>` : ''}${job.confirm_status == 1 ? `<span class="badge bg-success" style="font-size: 10px;">Confirmed</span>` : ''}` : ''}
                </h6>
                <a href="${domain}/booking-information/${job.preview_hash}" class="text-primary jobView ${(job.job_no && job.job_no.startsWith('GRD')) ? 'd-none' : ''}" target="_blank"><i class="fa fa-eye text-secondary"></i></a>
                <a href="javascript:void(0)" class="text-success viewRouteBtn" title="View Route on Map" data-jobno="${job.job_no || job.title}" data-fromlat="${job.coords?.from_lat || ''}" data-fromlng="${job.coords?.from_lng || ''}" data-tolat="${job.coords?.to_lat || ''}" data-tolng="${job.coords?.to_lng || ''}" data-fromplaceid="${job.from_place_id}" data-toplaceid="${job.to_place_id}" data-fromname="${job.from_place}" data-toname="${job.to_place}"><i class="fa fa-map-marked-alt text-primary" style="font-size: 16px;"></i></a>
                ${(job.global_type !== 'schedule' && job.app_source !== 'Website Job' && job.source !== 'website') ? `
                <a href="javascript:void(0)" class="text-info viewPushStatusBtn position-relative" title="View Push Delivery Status" data-jobid="${job.id}" data-jobno="${job.job_no || job.title}">
                    <i class="fa fa-bell text-warning" style="font-size: 14px;"></i>
                    ${job.push_count > 0 ? `<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 9px; padding: 2px 4px;">${job.push_count}</span>` : ''}
                </a>` : ''}
                ${(['cancelled', 'completed', 'expired', 'accept', 'accepted'].includes((job.job_status || '').toLowerCase()) || isExpired === true) && bidsCount > 0 ? `
                <button type="button" class="btn btn-sm btn-warning text-dark border-0 py-0 px-2 ms-3 position-relative view-cancelled-bids-btn fw-bold" data-jobno="${job.job_no}" data-bids="${safeBidsData}" style="font-size: 12px; height: 24px; box-shadow: none;">
                    <i class="fa fa-gavel text-dark me-1"></i> Bids
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 9px; padding: 3px 5px;">
                        ${bidsCount}
                    </span>
                </button>` : ''}
            </div>
            ${(!isInactive && !job.assigned_driver) ? getUnassignedActionButton(job) : ''}
        </div>
        <div class="mt-2 row small text-muted">
            <div class="col-6">
                <div class="d-flex align-items-center mb-1">
    <i class="fa fa-user-tie text-dark" style="width:16px;"></i>
    <a href="javascript:void(0)" class="${job.source != 'website' ? 'owner-link' : ''} fw-bold text-dark text-decoration-none me-2" data-owner="${job.user_id}" data-jobtype="${job.global_type}">${job.name}</a>
    
    ${(!isInactive && job.app_source !== 'Website Job' && job.source !== 'website') ? `<i class="fa fa-comment-dots text-primary cursor-pointer send-push-icon me-2" title="Send Push Notification" data-userid="${job.user_id}" data-name="${job.name}" style="font-size: 13px;"></i>` : ''}
    
    ${(showEdit && !isInactive && job.global_type !== 'schedule') ? `<i class="fa fa-pencil-alt text-warning cursor-pointer jobEdit" data-job="${jobId}" data-fulljob="${safeJobData}" title="Edit Job Details" style="font-size: 13px;"></i><i class="fa fa-history text-info cursor-pointer ms-2 viewJobLogs" data-jobid="${jobId}" data-jobno="${job.job_no}" title="View Edit History" style="font-size: 13px;"></i>` : ''}
</div>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <span class=" fw-bold" style="font-size: 13px;">${job.mobile || 'No Number'}</span>
                    ${!isInactive ? `<a href="tel:${job.mobile}" class="call-con" title="Call Owner" style="width: 25px; height: 25px; font-size: 12px;"><i class="fa fa-phone"></i></a><a href="https://wa.me/${job.mobile}" target="_blank" class="whatsapp-icon" title="WhatsApp Owner" style="width: 25px; height: 25px; font-size: 14px;"><i class="fab fa-whatsapp"></i></a>` : `<span class="call-con bg-light text-secondary border" title="Call Disabled" style="width: 25px; height: 25px; font-size: 12px; cursor: not-allowed; opacity: 0.6;"><i class="fa fa-phone"></i></span><span class="whatsapp-icon bg-light text-secondary border" title="WhatsApp Disabled" style="width: 25px; height: 25px; font-size: 14px; cursor: not-allowed; opacity: 0.6;"><i class="fab fa-whatsapp"></i></span>`}
                </div>
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
                        ${job.job_type === 'roundtrip' && (job.day || job.dropoff_date) ? `<i class="fa fa-calendar-days text-secondary" title="Date/Day"></i><span><strong>${job.day ? (job.day == 1 ? 'Upto 24 hours' : (String(job.day).match(/day|hour/i) ? job.day : job.day + ' Days')) : (job.dropoff_date == 1 ? 'Upto 24 hours' : (String(job.dropoff_date).match(/day|hour/i) ? job.dropoff_date : job.dropoff_date + ' Days'))}</strong></span>` : ''}
                        <i class="fa fa-road text-info" title="distance"></i>
                        <span><strong>${job.distance}</strong></span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="d-flex align-items-center"><i class="fa fa-car text-secondary me-1" style="width:16px;"></i><strong>${job.car_type}</strong></div>
                        <div class="d-flex align-items-center"><i class="fa fa-users text-success me-1" style="width:16px;"></i><strong>${formattedPassCount}</strong></div>
                        <div class="d-flex align-items-center"><i class="fa fa-suitcase text-warning me-1" style="width:16px;"></i><strong>${job.luggage}</strong></div>
                    </div>
                </div>
            </div>
            ${job.assigned_driver ? `<div class="col-12">
                <div class="d-flex align-items-center mb-1"><i class="fa fa-user text-primary" style="width:16px;"></i><span class="d-inline-flex align-items-center gap-2"><a href="#" class="driver-link fw-bold text-dark text-decoration-none" data-driver="${job.assigned_driver.driver_id}">${job.assigned_driver.name}</a>${!isInactive ? `<i class="fa fa-comment-dots text-primary cursor-pointer send-push-icon" title="Send Push Notification" data-userid="${job.assigned_driver.driver_id}" data-name="${job.assigned_driver.name}" style="font-size: 13px;"></i><i class="fa fa-times-circle text-danger cursor-pointer remove-driver d-none" title="Remove Driver" data-driver="${job.assigned_driver.driver_id}" data-job="${jobId}"></i>` : ''}</span></div>
            </div>` : ''}
            ${(!isInactive && !job.assigned_driver && job.source && job.source.toLowerCase() === 'posted' && ['created', 'bidding'].includes(job.job_status) && job.global_type !== 'schedule') ? `<div class="col-12"><span class="badge bg-secondary cursor-pointer unassigned-driver-btn" data-job="${job.id}" data-jobno="${job.job_no}">Make a Bid</span></div>` : ''}
            <div class="col-6">
                <p class="mb-0"> <i class="fa fa-calendar text-danger me-2"></i>${job.created_date}</p>
                <p class="mb-0"><a href="javascript:void(0)" class="${job.source != 'website' ? 'owner-link' : ''} fw-bold" data-owner="${job.user_id}" data-jobtype="${job.global_type}"><i class="fa fa-user-tie text-info"></i> Booked By ${job.name}</a></p>
            </div>
            ${(job.job_status || '').toLowerCase() === 'cancelled' ? `
            <div class="col-12 mt-2 text-start d-flex flex-wrap gap-2 align-items-center">
                <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 view-cancel-reason-btn" 
                        data-reason="${String(job.cancel_reason || 'No reason provided.').replace(/"/g, '&quot;')}" 
                        data-doc="${job.cancel_doc ? job.cancel_doc : ''}"
                        data-cancelled-by="${String(job.cancelled_by_display || 'User').replace(/"/g, '&quot;')}"
                        data-cancelled-at="${job.cancelled_at || 'Unknown Time'}"> 
                    <i class="fa fa-info-circle me-1"></i> View Reason
                </button>
                <span class="text-danger small fw-semibold ms-2" style="font-size: 12px;">
                    <i class="fa fa-calendar-times me-1"></i> Cancelled At: ${job.cancelled_at || 'Unknown Time'}
                </span>
            </div>` : ''}
            ${(job.feedback_rating || (isExpired === true && (job.job_status === 'accept' || job.job_status === 'accepted'))) ? `
            <div class="col-12 mt-2 text-start">
                <button type="button" class="btn btn-sm btn-outline-warning py-1 px-2 view-feedback-btn text-dark fw-semibold" data-rating="${job.feedback_rating || 0}" data-review="${String(job.feedback_review || 'No text review provided.').replace(/"/g, '&quot;')}">
                    <i class="fa fa-star text-warning me-1"></i> View Feedback
                </button>
            </div>` : ''}
            <div class="col-6">
                <div class="d-flex align-items-center justify-content-end">
                    <div class="text-end d-flex gap-2 align-items-center">
                        ${(!job.base_fare || Number(job.base_fare) === 0) ? '' : `<div class="text-muted">Base: ${job.base_fare === 'Included' ? 'Included' : '₹' + job.base_fare}</div>`}
                        ${(!job.toll_fare || Number(job.toll_fare) === 0) ? '' : `<div class="text-muted">Toll: ${job.toll_fare === 'Included' ? 'Included' : '₹' + job.toll_fare}</div>`}
                        ${(!job.tax || Number(job.tax) === 0) ? '' : `<div class="text-muted">Tax: ${job.tax === 'Included' ? 'Included' : '₹' + job.tax}</div>`}
                        <div class="fw-bold text-dark" style="font-size:14px;">₹${job.fare || 0}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>`;
        }
        function calculateDaysFromDates(pickup, dropoff) {
            if (!pickup || !dropoff) return null;
            const pickupDate = new Date(pickup.replace(' ', 'T'));
            const dropoffDate = new Date(dropoff.replace(' ', 'T'));
            if (isNaN(pickupDate) || isNaN(dropoffDate)) return null;
            const diffMs = dropoffDate - pickupDate;
            const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
            return diffDays >= 0 ? diffDays : null;
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
                success: function(res) {
                    if (!res.result) return;
                    res.result.forEach(j => {
                        const job = normalizeJob(j, 'website');
                        websiteJobs[job.id] = job;
                    });
                    websiteJobs = Object.values(websiteJobs)
                        .sort((a, b) => b.id - a.id);
                    render();
                },
                complete: function() {
                    $('#websiteLoader').hide();
                }
            });
            function render() {
                $('#websiteJobs').empty();
                const sourceFilter = $('#appSourceFilter_current').val() || 'all';
                const jobsArray = Object.values(websiteJobs).filter(job => sourceFilter === 'all' || job.app_source === sourceFilter);
                if (jobsArray.length === 0) {
                    $('#websiteJobs').html(`<div class="alert alert-info text-center mt-3"><i class="fa fa-info-circle me-2"></i>No Jobs Found</div>`);
                } else {
                    jobsArray.forEach(job => {
                        $('#websiteJobs').append(renderJobCard(job, job.id, false, true));
                    });
                }
                $('#websiteCount').text(jobsArray.length);
            }
        }
        function loadUnassignedJobsFinal() {
            $('#unassignedJobs').empty();
            $('#unassignedLoader').show();
            unassignedJobs = {};
            let req1 = $.ajax({
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
                }
            });
            let req2 = $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-scheduled-job-list",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                data: {
                    startDate: selectedStartDate,
                    endDate: selectedEndDate,
                    dateFilter: selectedFilterType ? 1 : '',
                    filterType: selectedFilterType
                }
            });
            $.when(req1, req2).done(function(res1, res2) {
                let data1 = res1[0];
                let data2 = res2[0];
                let combinedJobs = [];
                if (data1 && data1.result) {
                    combinedJobs = combinedJobs.concat(data1.result);
                }
                if (data2 && data2.result) {
                    combinedJobs = combinedJobs.concat(data2.result);
                }
                combinedJobs.forEach(j => {
                    try {
                        const job = normalizeJob(j, 'posted');
                        unassignedJobs[job.id] = job;
                    } catch (err) {
                        console.error('Error normalizing unassigned job:', j, err);
                    }
                });
                render();
            }).fail(function(xhr, status, error) {
                console.error("AJAX Error fetching jobs:", error);
            }).always(function() {
                $('#unassignedLoader').hide();
            });
            function render() {
                $('#unassignedJobs').empty();
                const sourceFilter = $('#appSourceFilter_current').val() || 'all';
                const sortedJobs = Object.entries(unassignedJobs).filter(([id, job]) => sourceFilter === 'all' || job.app_source === sourceFilter).sort(([idA], [idB]) => Number(idB) - Number(idA));
                if (sortedJobs.length === 0) {
                    $('#unassignedJobs').html(`<div class="alert alert-info text-center mt-3"><i class="fa fa-info-circle me-2"></i>No Jobs Found</div>`);
                } else {
                    sortedJobs.forEach(([id, job]) => {
                        $('#unassignedJobs').append(renderJobCard(job, id, false, true));
                    });
                }
                $('#unassignedCount').text(sortedJobs.length);
            }
        }
        function loadAssignedJobsFinal() {
            $('#assignedJobs').empty();
            $('#assignedLoader').show();
            assignedJobs = {};
            var service_url = window.location.origin + "/ajax/service/dashBoard_Services.php";
            $.ajax({
                url: service_url,
                type: "POST",
                dataType: "json",
                data: {
                    method: 'jobList_New',
                    jobStatus: 'accepted',
                    startDate: selectedStartDate,
                    endDate: selectedEndDate,
                    dateFilter: selectedFilterType ? 1 : '',
                    filterType: selectedFilterType
                },
                success: function(res) {
                    if (res && res.type == 1 && res.result) {
                        res.result.forEach(j => {
                            const job = normalizeJob(j, 'posted');
                            assignedJobs[job.id] = job;
                        });
                    }
                    assignedJobs = Object.values(assignedJobs).sort((a, b) => b.id - a.id);
                    render();
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error Loading Assigned Jobs:", error);
                },
                complete: function() {
                    $('#assignedLoader').hide();
                }
            });
            function render() {
                $('#assignedJobs').empty();
                const sourceFilter = $('#appSourceFilter_current').val() || 'all';
                const jobsArray = Object.values(assignedJobs).filter(job => sourceFilter === 'all' || job.app_source === sourceFilter);
                if (jobsArray.length === 0) {
                    $('#assignedJobs').html(`
                <div class="alert alert-info text-center mt-3">
                    <i class="fa fa-info-circle me-2"></i>No Jobs Found
                </div>
            `);
                } else {
                    jobsArray.forEach(job => {
                        $('#assignedJobs').append(renderJobCard(job, job.id));
                    });
                }
                $('#assignedCount').text(jobsArray.length);
            }
        }
        let cancelledStartDate = moment().format('YYYY-MM-DD');
        let cancelledEndDate = moment().format('YYYY-MM-DD');
        $(document).ready(function() {
            $('#cancelledDateRange').daterangepicker({
                startDate: moment(),
                endDate: moment(),
                opens: 'left',
                locale: {
                    format: 'DD/MM/YYYY'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'All Time': [moment('2020-01-01'), moment().add(10, 'years')]
                }
            }, function(start, end, label) {
                cancelledStartDate = start.format('YYYY-MM-DD');
                cancelledEndDate = end.format('YYYY-MM-DD');
                loadCancelledJobsFinal();
            });
            $('#cancelledFilterType').on('change', function() {
                loadCancelledJobsFinal();
            });
        });
        function loadCancelledJobsFinal() {
            $('#cancelledJobsList').empty();
            $('#cancelledLoader').show();
            cancelledJobs = {};
            $.ajax({
                url: window.location.origin + "/ajax/service/Websitebook_service.php",
                type: "POST",
                dataType: "json",
                data: {
                    method: 'get_cancelled_jobs',
                    jobStatus: 'cancelled',
                    startDate: cancelledStartDate,
                    endDate: cancelledEndDate,
                    filterType: $('#cancelledFilterType').val() || 'created'
                },
                success: function(res) {
                    let dataArray = res.result || res.data;
                    if (dataArray && Array.isArray(dataArray)) {
                        dataArray.forEach(j => {
                            const job = normalizeJob(j, 'posted');
                            cancelledJobs[job.id] = job;
                        });
                    }
                    cancelledJobs = Object.values(cancelledJobs).sort((a, b) => b.id - a.id);
                    render();
                },
                error: function() {
                    render();
                },
                complete: function() {
                    $('#cancelledLoader').hide();
                    if ($('#cancelledJobsList').is(':empty')) {
                        render();
                    }
                }
            });
            function render() {
                $('#cancelledJobsList').empty();
                const sourceFilter = $('#appSourceFilter_cancelled').val() || 'all';
                const jobsArray = Object.values(cancelledJobs).filter(job => sourceFilter === 'all' || job.app_source === sourceFilter);
                // Update the count badge here
                $('#cancelledCountBadge').text(jobsArray.length + ' Jobs');
                if (jobsArray.length === 0) {
                    $('#cancelledJobsList').html(`
                <div class="alert alert-info text-center mt-3">
                    <i class="fa fa-info-circle me-2"></i>No cancelled jobs available for selected date
                </div>
            `);
                } else {
                    jobsArray.forEach(job => {
                        $('#cancelledJobsList').append(renderJobCard(job, job.id));
                    });
                }
            }
        }
        let expiredStartDate = moment().format('YYYY-MM-DD');
        let expiredEndDate = moment().format('YYYY-MM-DD');
        let completedStartDate = moment().format('YYYY-MM-DD');
        let completedEndDate = moment().format('YYYY-MM-DD');
        $(document).ready(function() {
            $('#expiredDateRange').daterangepicker({
                startDate: moment(),
                endDate: moment(),
                opens: 'left',
                locale: {
                    format: 'DD/MM/YYYY'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'All Time': [moment('2020-01-01'), moment().add(10, 'years')]
                }
            }, function(start, end, label) {
                expiredStartDate = start.format('YYYY-MM-DD');
                expiredEndDate = end.format('YYYY-MM-DD');
                loadExpiredJobsFinal();
            });
            $('#expiredFilterType').on('change', function() {
                loadExpiredJobsFinal();
            });
            $('#completedDateRange').daterangepicker({
                startDate: moment(),
                endDate: moment(),
                opens: 'left',
                locale: {
                    format: 'DD/MM/YYYY'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'All Time': [moment('2020-01-01'), moment().add(10, 'years')]
                }
            }, function(start, end, label) {
                completedStartDate = start.format('YYYY-MM-DD');
                completedEndDate = end.format('YYYY-MM-DD');
                loadCompletedJobsFinal();
            });
            $('#completedFilterType').on('change', function() {
                loadCompletedJobsFinal();
            });
            $(document).on('change', '#appSourceFilter_current', reloadJobsWithFilter);
            $(document).on('change', '#appSourceFilter_cancelled', loadCancelledJobsFinal);
            $(document).on('change', '#appSourceFilter_expired', loadExpiredJobsFinal);
            $(document).on('change', '#appSourceFilter_completed', loadCompletedJobsFinal);
        });
        function loadExpiredJobsFinal() {
            $('#expiredJobsList').empty();
            $('#expiredLoader').show();
            let expiredJobsObj = {};
            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-expired-jobs",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                data: {
                    startDate: expiredStartDate,
                    endDate: expiredEndDate,
                    filterType: $('#expiredFilterType').val() || 'created'
                },
                success: function(res) {
                    if (res.result && Array.isArray(res.result)) {
                        res.result.forEach(j => {
                            const job = normalizeJob(j, 'posted');
                            expiredJobsObj[job.id] = job;
                        });
                    }
                    const sourceFilter = $('#appSourceFilter_expired').val() || 'all';
                    const jobsArray = Object.values(expiredJobsObj).filter(job => sourceFilter === 'all' || job.app_source === sourceFilter).sort((a, b) => b.id - a.id);
                    if (jobsArray.length === 0) {
                        $('#expiredJobsList').html(`<div class="alert alert text-center mt-3"><i class="fa fa-info-circle me-2"></i>No expired jobs available for selected filter</div>`);
                    } else {
                        $('#expiredJobsList').empty();
                        jobsArray.forEach(job => {
                            $('#expiredJobsList').append(renderJobCard(job, job.id, true));
                        });
                    }
                },
                error: function() {
                    $('#expiredJobsList').html('<div class="alert alert-danger text-center mt-3">Error loading expired jobs</div>');
                },
                complete: function() {
                    $('#expiredLoader').hide();
                }
            });
        }
        $('button[data-bs-target="#expiredJobs"]').on('shown.bs.tab', function() {
            loadExpiredJobsFinal();
        });
        function loadCompletedJobsFinal() {
            $('#completedJobsList').empty();
            $('#completedLoader').show();
            let completedJobsObj = {};
            $.ajax({
                url: window.location.origin + "/ajax/service/dashBoard_Services.php",
                type: "POST",
                dataType: "json",
                data: {
                    method: 'jobList_New',
                    jobStatus: 'completed',
                    startDate: completedStartDate,
                    endDate: completedEndDate,
                    dateFilter: 1,
                    filterType: $('#completedFilterType').val() || 'created'
                },
                success: function(res) {
                    if (res.result && Array.isArray(res.result)) {
                        res.result.forEach(j => {
                            const job = normalizeJob(j, 'posted');
                            completedJobsObj[job.id] = job;
                        });
                    }
                    const sourceFilter = $('#appSourceFilter_completed').val() || 'all';
                    const jobsArray = Object.values(completedJobsObj).filter(job => sourceFilter === 'all' || job.app_source === sourceFilter).sort((a, b) => b.id - a.id);
                    if (jobsArray.length === 0) {
                        $('#completedJobsList').html(`<div class="alert alert-success text-center mt-3"><i class="fa fa-info-circle me-2"></i>No completed jobs available for the selected filters</div>`);
                    } else {
                        $('#completedJobsList').empty();
                        jobsArray.forEach(job => {
                            $('#completedJobsList').append(renderJobCard(job, job.id, true));
                        });
                    }
                },
                error: function() {
                    $('#completedJobsList').html('<div class="alert alert-danger text-center mt-3">Error loading completed jobs</div>');
                },
                complete: function() {
                    $('#completedLoader').hide();
                }
            });
        }
        $('button[data-bs-target="#completedJobs"]').on('shown.bs.tab', function() {
            loadCompletedJobsFinal();
        });
        $('button[data-bs-target="#unassigned"]').on('shown.bs.tab', function() {
            loadUnassignedJobsFinal();
        });
        $('button[data-bs-target="#assigned"]').on('shown.bs.tab', function() {
            loadAssignedJobsFinal();
        });
        $('button[data-bs-target="#websiteBookings"]').on('shown.bs.tab', function() {
            loadWebsiteBookingsFinal();
        });
        $('button[data-bs-target="#cancelledJobs"]').on('shown.bs.tab', function() {
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
                success: function(res) {
                    if (res.status == 'success') {
                        $('#otpSection').removeClass('d-none');
                        $('#otpMsg').text('OTP sent successfully').addClass('text-success');
                        window.enc = res.data.enc;
                        toast('success', res.message);
                    } else {
                        toast('error', res.message);
                        $('#otpMsg').text(res.message || 'Failed to send OTP')
                            .addClass('text-danger');
                    }
                },
                error: function() {
                    toast('error', res.message);
                },
                complete: function() {
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
                beforeSend: function() {
                    Swal.fire({
                        title: 'Posting Job...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(res) {
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
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Server error'
                    });
                }
            });
        }
        function openPostJobPreview(data) {
            // FIX: Hide all other panels including the Update/Create buttons from the main card wrapper
            $('#mapCard, #leftPanel, #incomingBidsPanel, #ownerDetailsCard, #driverListPanel').addClass('d-none');
            $('#jobDetailsPanel').removeClass('d-none');
            $('#updateJobBtn, #confirmBookingBtn').addClass('d-none');
            $('#jobDetailsContent').html(`
    <div class="container-fluid">
      <div class="border p-3 mb-3">
        <h6 class="text-muted mb-3">Customer Details</h6>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Name</label>
            <input class="form-control" id="pv_name" value="${data.name ?? ''}" readonly>
          </div>
          <div class="col-md-4 d-none">
            <label class="form-label">Email</label>
            <input class="form-control" id="pv_email" value="${data.email ?? ''}" readonly>
          </div>
          <div class="col-md-4">
            <label class="form-label">Mobile</label>
            <input class="form-control" id="pv_mobile" value="${data.mobile ?? ''}" readonly>
          </div>
        </div>
      </div>
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
      <div class="border p-3 mb-3">
        <h6 class="text-muted mb-3">Schedule</h6>
        <input class="form-control" id="pv_pickup_time"
               value="${ formatPickupDate(data.pickup_date)}" readonly>
      </div>
      <div class="border p-3 mb-3">
        <h6 class="text-muted mb-3">Fare Breakdown</h6>
        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label">Base Fare</label>
            <input class="form-control" id="pv_base_fare" value="${data.base_fare}" readonly>
          </div>
          <div class="col-md-3">
            <label class="form-label">Tax (GST)</label>
            <input class="form-control" id="pv_tax_fare" value="${data.tax || 0}" readonly>
          </div>
          <div class="col-md-3">
            <label class="form-label">Toll Fare</label>
            <input class="form-control" id="pv_toll_fare" value="${data.toll_fare}" readonly>
          </div>
          <div class="col-md-3 d-flex align-items-end">
            <h6 class="text-success fw-bold mb-2">
              Total: ₹<span id="pv_total_fare">${data.fare}</span>
            </h6>
          </div>
        </div>
      </div>
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
        $(document).on('click', '#verifyOtpBtn', function() {
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
                success: function(res) {
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
                            if (result.isDismissed) {
                                $('#verifyOtpBtn').prop('disabled', false);
                                $('#otpInput, #verifyOtpBtn').prop('disabled', false);
                            }
                        });
                    } else {
                        $('#otpMsg')
                            .text(res.message || 'Invalid OTP')
                            .addClass('text-danger');
                    }
                },
                error: function() {
                    $('#otpMsg')
                        .text('Verification failed')
                        .addClass('text-danger');
                }
            });
        });
        $(document).on('click', '.postJobBtn', function() {
            const job = $(this).data('job');
            openPostJobPreview(job);
        });
        $(document).on('click', '.sendOtpBtn', function() {
            const jobId = $(this).data('job-id');
            const mobile = $(this).data('mobile');
            sendOtp(jobId, mobile, this);
        });
        function openIncomingBids(job) {
            $('#mapCard, #jobDetailsPanel, #driverListPanel').addClass('d-none');
            $('#leftPanel').removeClass('d-none');
            $('#incomingBidsPanel').removeClass('d-none');
            $('#ownerDetailsCard').addClass('d-none');
            // console.log(job);
        
            const jobNo = job.job_no;
            
            // 🔥 FIX: Take the exact toll amount from the database. Do NOT halve it.
            let tollAmount = Number(job.toll_fare) || Number(job.toll) || 0;
            let base_fare = Number(job.base_fare) || 0;
            let com = Number(job.com) || 0;
            let tax = Number(job.tax) || 0;
            let discount = Number(job.discount) || 0;
            let isDiscount = job.isDiscount || 'no';
        
            $('#incomingBidsTitle').html(`Incoming Bids <span class="text-primary ms-1">(${jobNo})</span>`);
            const biddersWrap = $('#incomingBidsPanel .bidders-wrap');
            const bestPriceEl = $('.best_price');
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
                        biddersWrap.html('<p class="text-muted" style="padding-left: 161px;">No bids yet</p>');
                        bestPriceEl.hide();
                        return;
                    }
        
                    let lowestPrice = null;
        
                    const calculateTotal = (bidAmount) => {
                    
                        let bidBaseFare = Number(bidAmount) || 0;
                        
                        bidBaseFare = bidBaseFare - tollAmount;
                    
                        // base calculation (use outer values safely)
                        let calAmt = (base_fare - com);
                    
                        let commission = com; // don't mutate outer `com`
                        let taxAmount = tax;  // don't mutate outer `tax`
                            
                        // // console.log(calAmt, bidBaseFare)
                                    
                        if (calAmt != bidBaseFare) {
                    
                            commission = Math.round((bidBaseFare + tollAmount) * 0.05);
                    
                            let taxableAmount = 0;
                    
                            if (isDiscount == 'yes') {
                                taxableAmount = (bidBaseFare + commission) - discount;
                            } else {
                                taxableAmount = bidBaseFare + commission;
                            }
                            
                            // console.log(bidBaseFare, commission, taxableAmount, isDiscount, discount)
                    
                            taxAmount = Math.round(taxableAmount * 0.05);
                            // console.log(bidBaseFare, commission, taxableAmount, taxAmount, tollAmount, isDiscount, discount)
                            return (bidBaseFare + commission + taxAmount + tollAmount) - (isDiscount == 'yes' ? discount : 0);
                        }else{
                            return base_fare + tax + tollAmount - (isDiscount == 'yes' ? discount : 0);
                        }
                    
                    };
        
                    Object.values(bids).forEach(b => {
                        const amt = Number(b.amount);
                        if (!isNaN(amt)) {
                            const totalAmt = calculateTotal(amt);
                            lowestPrice = lowestPrice === null ? totalAmt : Math.min(lowestPrice, totalAmt);
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
        
                    Object.entries(bids).reverse().forEach(([bidderId, bid]) => {
                        if (isJobAccepted && bidderId !== acceptedBidderId) return;
                        
                        const amount = Number(bid.amount);
                        
                        const totalAmount = calculateTotal(amount);
                        const status = bid.status || 'pending';
                        const isBest = totalAmount === lowestPrice;
                        
                        // Parse and format the bidded time to "Date Month Year, Time"
                        let formattedTime = '';
                        if (bid.created_at) {
                            let dateObj;
                            if (typeof bid.created_at.toDate === 'function') {
                                dateObj = bid.created_at.toDate();
                            } else if (typeof bid.created_at === 'number') {
                                dateObj = new Date(bid.created_at);
                            } else {
                                dateObj = new Date(bid.created_at);
                            }

                            if (dateObj && !isNaN(dateObj)) {
                                const day = dateObj.getDate();
                                const month = dateObj.toLocaleString('default', { month: 'short' }); // e.g., Jan, Feb
                                const year = dateObj.getFullYear();
                                
                                let hours = dateObj.getHours();
                                const minutes = dateObj.getMinutes().toString().padStart(2, '0');
                                const ampm = hours >= 12 ? 'PM' : 'AM';
                                
                                hours = hours % 12;
                                hours = hours ? hours : 12; // the hour '0' should be '12'
                                
                                formattedTime = `${day} ${month} ${year}, ${hours}:${minutes} ${ampm}`;
                            } else {
                                formattedTime = bid.created_at;
                            }
                        }

                        const remarkClass = status === 'accept' ? 'remark-success' : (isBest ? 'remark-success' : 'remark-warning');
                        
                        biddersWrap.append(`
                        <div class="bid-item">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <a href="#"
                                       class="fw-bold text-dark text-decoration-none driver-link"
                                       data-driver="${bidderId}">
                                        ${bid.b_name || 'Driver'}
                                    </a>
                                    <i class="fa fa-check-circle text-success" title="Verified"></i>
                                    <i class="fa fa-map-marker-alt text-danger cursor-pointer view-driver-location"
                                       data-driver="${bidderId}" 
                                       data-name="${bid.b_name || 'Driver'}"
                                       title="View Location"></i>
                                    ${formattedTime ? `<span class="text-muted ms-2" style="font-size: 11px;"><i class="far fa-clock me-1"></i>${formattedTime}</span>` : ''}
                                </div>
                                <div class="d-flex align-items-center text-dark fw-semibold gap-3" style="font-size: 13px;">
                                    <span class="text-dark">For Driver: ₹${amount}</span>
                                    <span class="text-dark" style="font-size: 14px;">Customer Pays: ₹${totalAmount}</span>
                                </div>
                            </div>
                            <div class="bid-remark ${remarkClass} d-flex justify-content-between align-items-center mt-2 p-1 px-2 rounded">
                                <span class="text-truncate">
                                    <i class="fa fa-comment-dots me-1"></i>
                                    <span>${bid.remark || '—'}</span>
                                </span>
                                <i class="fa fa-pencil-alt text-secondary cursor-pointer manageBidBtn ms-2" 
                                   title="Edit Bid/Remark" 
                                   data-job="${job.id}" 
                                   data-driver="${bidderId}" 
                                   style="font-size:11px;"></i>
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
        $(document).on('click', '.bidBtn', function() {
            const jobId = $(this).data('job');
            const job = unassignedJobs[jobId];
            openIncomingBids(job);
        });
        $(document).on('click', '.bid-accept', function() {
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
                        Swal.close();
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
        $(document).on('click', '.bid-reject', function() {
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
            const modal = new bootstrap.Modal(
                document.getElementById('confirmActionModal'), {
                    backdrop: 'static',
                    keyboard: false
                }
            );
            modal.show();
        });
        $('#confirmYesBtn').on('click', function() {
            if (!rejectData.jobId) return;
            const {
                bidderId,
                jobId,
                button
            } = rejectData;
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
                beforeSend: function() {
                    $confirmBtn
                        .prop('disabled', true)
                        .html('<i class="fa fa-spinner fa-spin me-1"></i> Processing');
                },
                success: function(res) {
                    if (res.status) {
                        toast('success', 'Bid rejected successfully');
                        const $actionWrap = button.closest('.d-flex.justify-content-end');
                        $actionWrap.html(`
                            <span class="text-danger fw-semibold">
                                <i class="fa fa-times-circle me-1"></i> Rejected
                            </span>
                        `);
                        bootstrap.Modal.getInstance(
                            document.getElementById('confirmActionModal')
                        ).hide();
                    } else {
                        toast('error', res.message || 'Reject failed');
                    }
                },
                error: function() {
                    toast('error', 'Server error');
                },
                complete: function() {
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
                data: {
                    passenger_id: id,
                    jobtype: jobtype
                },
                success: function(res) {
                    if (!res.status || !res.data) return;
                    const d = res.data;
                    if (d.name) {
                        $('#ownerDetailsCard .d-flex.align-items-center.gap-2 span')
                            .first()
                            .text(d.name);
                    }
                    if (d.mobile) {
                        const cleanMobile = String(d.mobile).replace(/\D/g, '');
                        $('#ownerDetailsCard .call-con')
                            .attr('href', `tel:${cleanMobile}`);
                        $('#ownerDetailsCard .whatsapp-icon')
                            .attr('href', `https://wa.me/${cleanMobile}`);
                    }
                    $('#ownerDetailsCard img')
                        .attr('src', d.profile_img_url || '../assets/images/users/spl_profile_image.png');
                    $('.c_mobile').html(`<i class="fa fa-phone"></i> ${d.mobile}`);
                    $('.c_address').html(`<i class="fa fa-map-marker-alt text-danger me-1"></i> ${d.address ?? '-'}`);
                    $('.c_created_at').html(`<i class="fa fa-user-clock me-1"></i>Account Created: <strong>${formatPickupDate(d.created_at)}</strong>`);
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
        $(document).on('click', '.owner-link', function(e) {
            e.preventDefault();
            const passengerId = $(this).data('owner');
            const jobtype = $(this).data('jobtype');
            $('#mapCard, #jobDetailsPanel').addClass('d-none');
            $('#leftPanel').removeClass('d-none');
            $('#incomingBidsPanel').addClass('d-none');
            $('#ownerDetailsCard').removeClass('d-none');
            getPassengerDetails(passengerId, jobtype);
        });
        function getDriverDetails(id) {
            $.ajax({
                url: "ajax/service/driverServices.php",
                type: "POST",
                dataType: "json",
                data: {
                    method: 'get_driver_details',
                    driver_id: id
                },
                success: function(res) {
                    if (!res.status || !res.data) {
                        $('#driverDetailsCard .card-body > *:not(#driverProfileLoader)').css('opacity', 1);
                        $('#driverProfileLoader').addClass('d-none');
                        return;
                    }
                    const d = res.data.driver;
                    if (d.user_id) {
                        $('#driver_profile_edit').off('click').on('click', function() {
                            openDriverEditDrawer(d.user_id, d.id);
                        });
                        $('#sidebar_remarks_icon').off('click').on('click', function() {
                            openRemarksModal(d.user_id, d.name || 'Driver');
                        });
                    }
                    $('#preview_driver_name').text(d.name || 'N/A');
                    if (d.mobile) {
                        const cleanMobile = String(d.mobile).replace(/\D/g, '');
                        $('#preview_driver_no').html(`<i class="fa fa-phone text-success me-1"></i> (${cleanMobile})`);
                        $('#preview_call_btn').attr('href', `tel:${cleanMobile}`);
                        $('#preview_wa_btn').attr('href', `https://wa.me/${cleanMobile}`);
                    }
                    const profileImg = d.selfie_url || d.profile_img_url || '/assets/images/driver.png';
                    $('#preview_driver_photo').attr('src', profileImg);
                    $('#preview_driver_photo').off('click').on('click', function() {
                        $('#driverPhotoModal .modal-body img').attr('src', profileImg);
                    });
                    if (d.doc_verify == 1) {
                        $('#preview_kyc_status').html('<i class="fa fa-check-circle text-success me-1"></i> KYC Verified');
                    } else {
                        $('#preview_kyc_status').html('<i class="fa fa-times-circle text-danger me-1"></i> KYC Pending');
                    }
                    const locationTxt = [d.address, d.state].filter(Boolean).join(', ');
                    $('#preview_location').html(`<i class="fa fa-map-marker-alt text-danger me-1"></i> ${locationTxt || 'N/A'}`);
                    $('#preview_experience').html(`<i class="fa fa-briefcase text-info me-1"></i> ${d.exp ? d.exp + ' Years' : 'N/A'}`);
                    const dlExpiryStr = d.dl_expiry ? formatDateSimple(d.dl_expiry) : 'N/A';
                    const dlTypeHtml = d.license_type ? ` | <strong class="text-secondary">${d.license_type}</strong>` : '';
                    $('#preview_license').html(`<i class="fa fa-id-badge text-primary me-1"></i> <strong>${d.dl_no || 'N/A'}</strong>${dlTypeHtml} | <strong>${dlExpiryStr}</strong>`);
                    let vName = 'N/A',
                        vModel = 'N/A',
                        vFuel = 'N/A',
                        vSeats = 'N/A',
                        vLuggage = 'N/A';
                    let rcExpiry = 'N/A',
                        inExpiry = 'N/A',
                        vImage = '/assets/images/carimage.png';
                    let languages = 'N/A';
                    let vehicleData = {};
                    if (d.vehicle_details) {
                        try {
                            vehicleData = typeof d.vehicle_details === 'string' ? JSON.parse(d.vehicle_details) : d.vehicle_details;
                            vName = vehicleData.rc_details?.response?.vehicle_details?.body_type || d.cab_type || 'N/A';
                            vModel = vehicleData.rc_details?.response?.vehicle_details?.maker_model || 'N/A';
                            let rawFuel = (vehicleData.rc_details?.response?.vehicle_details?.fuel_type ||
                                vehicleData.vehicle_questions?.fuel_type || d.fuel_type || 'N/A').toString().trim().toUpperCase();
                            if (rawFuel.includes('PETROL/CNG') || rawFuel.includes('PETROL / CNG')) {
                                vFuel = 'PETROL/CNG';
                            } else {
                                vFuel = rawFuel;
                            }
                            let seatCap = vehicleData.rc_details?.response?.vehicle_details?.seat_capacity;
                            if (seatCap) vSeats = (seatCap > 1) ? (seatCap - 1) + "+1" : seatCap;
                            vLuggage = vehicleData.user_info?.luggage || d.luggage || vehicleData.luggage || vehicleData.vehicle_questions?.luggage_capacity || 'N/A';
                            if (vehicleData.rc_expiry_date) rcExpiry = formatDateSimple(vehicleData.rc_expiry_date);
                            if (vehicleData.insurance_details?.insurance_exp_date) inExpiry = formatDateSimple(vehicleData.insurance_details.insurance_exp_date);
                            if (vehicleData.vehicle?.front_view_image_url) vImage = vehicleData.vehicle.front_view_image_url;
                            if (vehicleData.user_info?.language) languages = vehicleData.user_info.language;
                            const images = [
                                vehicleData.vehicle?.front_view_image_url,
                                vehicleData.vehicle?.back_view_image_url,
                                vehicleData.vehicle?.side_view_image_url,
                                vehicleData.vehicle?.car_top_view_image_url,
                                vehicleData.vehicle?.interior_front_image_url,
                                vehicleData.vehicle?.interior_rear_image_url,
                                vehicleData.vehicle?.boot_image_url,
                                vehicleData.vehicle?.extra_image_1_url,
                                vehicleData.vehicle?.special_features_image_url
                            ].filter(Boolean);
                            const carouselInner = $('#carImageCarousel .carousel-inner');
                            carouselInner.empty();
                            if (images.length === 0) {
                                carouselInner.html(`<div class="carousel-item active"><img src="/assets/images/carimage.png" class="img-fluid" style="max-height:250px;"></div>`);
                            } else {
                                images.forEach((img, index) => {
                                    carouselInner.append(`<div class="carousel-item ${index === 0 ? 'active' : ''}"><img src="${img}" class="img-fluid" style="max-height:250px; object-fit:cover; width:100%;"></div>`);
                                });
                            }
                        } catch (e) {
                            console.log('Error parsing vehicle details JSON for preview');
                        }
                    }
                    $('#preview_vehicle_photo').attr('src', vImage);
                    $('#preview_vehicle_name').text(vName);
                    $('#preview_vehicle_model').text(vModel);
                    $('#preview_seats').text(vSeats);
                    $('#preview_fuel').text(vFuel);
                    $('#preview_luggage').text(vLuggage);
                    $('#preview_rc').text(rcExpiry);
                    $('#preview_in').text(inExpiry);
                    $('#preview_languages').html(`<i class="fa fa-language text-dark me-1"></i> <strong>${languages.replace(/,/g, ', ')}</strong>`);
                    $('#preview_per_km').text(d.per_km ? `₹${d.per_km}` : 'N/A');
                    $('#preview_extra_km').text(d.extra_per_km ? `₹${d.extra_per_km}` : 'N/A');
                    $('#preview_extra_hour').text(d.per_hour ? `₹${d.per_hour}` : 'N/A');
                    $('#preview_extra_day').text(d.per_day ? `₹${d.per_day}` : 'N/A');
                    $('#preview_driver_id').val(d.user_id);
                    let percent = parseInt(d.profile_percentage) || 0;
                    percent = Math.max(0, Math.min(100, percent));
                    $('#preview_profile_percent_text').text(percent + '%');
                    const $progress = $('#preview_profile_progress');
                    $progress.css('width', percent + '%').attr('aria-valuenow', percent).removeClass('bg-success bg-warning bg-danger');
                    if (percent < 40) $progress.addClass('bg-danger');
                    else if (percent < 75) $progress.addClass('bg-warning');
                    else $progress.addClass('bg-success');
                    $('#driverEditDrawer .offcanvas-body > *:not(#driverEditLoader)').css('opacity', 1);
                    $('#driverEditLoader').addClass('d-none');
                    $('#driverDetailsCard .card-body > *:not(#driverProfileLoader)').css('opacity', 1);
                    $('#driverProfileLoader').addClass('d-none');
                },
                error: function() {
                    $('#driverDetailsCard .card-body > *:not(#driverProfileLoader)').css('opacity', 1);
                    $('#driverProfileLoader').addClass('d-none');
                }
            });
        }
        let isFetchingGlobalDrivers = false;
        function fetchDriversForSearch(callback = null) {
            if (Object.keys(driversAll).length > 0) {
                if (callback) callback();
                return;
            }
            if (isFetchingGlobalDrivers) return;
            isFetchingGlobalDrivers = true;
            $('#globalDriverSearchResults').html(`<div class="list-group-item text-muted text-center py-3" style="font-size:13px;"><i class="fa fa-spinner fa-spin me-2"></i>Loading drivers...</div>`).removeClass('d-none');
            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-get-driver-list",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                success: function(res) {
                    if (res.status && res.data) {
                        driversAll = {};
                        res.data.forEach(driver => {
                            let status = 'offline';
                            if (driver.rm_status == 1) status = 'online';
                            if (driver.rm_status == 2) status = 'busy';
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
                        if (callback) callback();
                    }
                },
                complete: function() {
                    isFetchingGlobalDrivers = false;
                }
            });
        }
        function renderGlobalSearchDropdown(searchTerm = '') {
            const resultsBox = $('#globalDriverSearchResults');
            let driversArray = Object.values(driversAll);
            if (searchTerm) {
                driversArray = driversArray.filter(driver => {
                    const nameMatch = driver.name && String(driver.name).toLowerCase().includes(searchTerm);
                    const mobileMatch = driver.mobile && String(driver.mobile).includes(searchTerm);
                    return nameMatch || mobileMatch;
                });
            }
            if (driversArray.length === 0) {
                resultsBox.html(`<div class="list-group-item text-muted text-center py-3" style="font-size:13px;"><i class="fa fa-user-slash me-2"></i>No drivers found</div>`).removeClass('d-none');
                return;
            }
            let html = '';
            driversArray.forEach(driver => {
                let statusColor = driver.status === 'online' ? 'success' : (driver.status === 'busy' ? 'warning' : 'danger');
                let profilePercent = driver.profilePercent || 0;
                let profileClass = profilePercent >= 80 ? 'profile-good' : (profilePercent >= 50 ? 'profile-medium' : 'profile-low');
                let profileImg = '/assets/images/driver.png';
                if (driver.fullData) {
                    profileImg = driver.fullData.selfie_url || driver.fullData.profile_img_url || profileImg;
                }
                html += `
                <a href="javascript:void(0)" class="list-group-item list-group-item-action global-driver-search-item py-2 px-3" data-id="${driver.user_id || driver.id}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <img src="${profileImg}" class="rounded-circle shadow-sm" style="width:35px;height:35px;object-fit:cover;">
                            <div style="font-size: 13px; line-height: 1.2;">
                                <strong class="text-dark">${driver.name}</strong><br>
                                <small class="text-muted"><i class="fa fa-phone me-1"></i>${driver.mobile || 'N/A'}</small>
                            </div>
                        </div>
                        <div class="profile-circle ${profileClass}" style="width: 35px; height: 35px; font-size: 10px; border-width: 3px; display:flex; align-items:center; justify-content:center; border-radius:50%; border-style:solid; background: #fff;">
                            ${profilePercent}%
                        </div>
                    </div>
                </a>`;
            });
            resultsBox.html(html).removeClass('d-none');
        }
        $(document).off('click', '#globalDriverSearch').on('click', '#globalDriverSearch', function(e) {
            e.stopPropagation();
            $('#globalDriverSearchResults').removeClass('d-none');
            const searchTerm = $(this).val().toLowerCase().trim();
            const $icon = $(this).prev('i');
            if ($icon.length) {
                $icon.removeClass('fa-search text-muted').addClass('fa-spinner fa-spin text-primary');
            }
            fetchDriversForSearch(function() {
                renderGlobalSearchDropdown(searchTerm);
                if ($icon.length) {
                    $icon.removeClass('fa-spinner fa-spin text-primary').addClass('fa-search text-muted');
                }
            });
        });
        $(document).on('show.bs.modal', '.modal', function() {
            $('#globalDriverSearch').blur();
            $('#globalDriverSearchResults').addClass('d-none');
        });
        document.addEventListener('show', function() {
            $('#globalDriverSearch').blur();
            $('#globalDriverSearchResults').addClass('d-none');
        });
        $('#vehicleImagesModal').on('show.bs.modal', function() {
            $('#globalDriverSearch').blur();
            $('#globalDriverSearchResults').addClass('d-none');
        });
        $(document).on('keyup', '#globalDriverSearch', function() {
            $('#globalDriverSearchResults').removeClass('d-none');
            const searchTerm = $(this).val().toLowerCase().trim();
            fetchDriversForSearch(function() {
                renderGlobalSearchDropdown(searchTerm);
            });
        });
        $(document).on('click', '.global-driver-search-item', function(e) {
            e.preventDefault();
            const driverId = $(this).data('id');
            $('#globalDriverSearchResults').addClass('d-none');
            $('#globalDriverSearch').val('');
            if (driverId) {
                openDriverEditDrawer(driverId);
            }
        });
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#globalDriverSearch, #globalDriverSearchResults').length) {
                $('#globalDriverSearchResults').addClass('d-none');
            }
        });
        function openDriverEditDrawer(id) {
            $('#driverPreviewCanvas').offcanvas('hide');
            window.currentDriverId = id;
            const NO_IMAGE_SVG = "data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100%25' height='100%25'%3E%3Crect width='100%25' height='100%25' fill='%23f8f9fa'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='12px' fill='%236c757d'%3ENo Image Available%3C/text%3E%3C/svg%3E";
            $('#globalDriverSearchResults').addClass('d-none');
            $('#globalDriverSearch').blur();
            $('#driverEditLoader').removeClass('d-none').css({
                'background': '#ffffff',
                'z-index': '9999',
                'opacity': '1'
            });
            $('.display-field').text('-');
            $('.edit-field').val('');
            $('#main_vehicle_preview, .vehicle-photo').attr('src', NO_IMAGE_SVG);
            $('.driver-photo').attr('src', NO_IMAGE_SVG);
            $('.document-img').attr('src', NO_IMAGE_SVG);
            $('#carImageCarousel .carousel-inner').empty();
            $('#dynamicScheduleWrapper, #scheduledJobsList').empty();
            $('#status-driver-profile, #status-aadhaar, #status-license, #status-vehicle, #status-documents, #status-fare, #status-remarks').empty();
            let drawerEl = document.getElementById('driverEditDrawer');
            let bsOffcanvas = bootstrap.Offcanvas.getInstance(drawerEl);
            if (!bsOffcanvas) {
                bsOffcanvas = new bootstrap.Offcanvas(drawerEl);
            }
            bsOffcanvas.show();
            if (typeof fetchDriversForSearch === 'function') {
                fetchDriversForSearch();
            }
            $.ajax({
                url: "ajax/service/driverServices.php",
                type: "POST",
                dataType: "json",
                data: {
                    method: 'get_driver_details',
                    driver_id: id
                },
                success: function(res) {
                    if (!res.status || !res.data) {
                        $('#driverEditLoader').addClass('d-none');
                        return;
                    }
                    const d = res.data.driver || {};
                    let vehicleData = {};
                    try {
                        vehicleData = typeof d.vehicle_details === 'string' ? JSON.parse(d.vehicle_details || '{}') : (d.vehicle_details || {});
                    } catch (e) {
                        vehicleData = {};
                    }
                    const langs = vehicleData.user_info?.language;
                    if (langs) {
                        const langArr = langs.split(',').map(l => l.trim());
                        window.editLanguagesTomSelect.clear();
                        langArr.forEach(l => window.editLanguagesTomSelect.addItem(l));
                    } else {
                        window.editLanguagesTomSelect.clear();
                    }
                    function setText(selector, value, fallback = '-') {
                        $(selector).text(value ? value : fallback);
                    }
                    function setVal(selector, value) {
                        $(selector).val(value ? value : '');
                    }
                    function setLocalizedImage(selector, url, fallbackUrl = NO_IMAGE_SVG) {
                        let $img = $(selector);
                        let $container = $img.parent();
                        $img.off('load.local error.local');
                        if (url && url !== '' && url !== 'null') {
                            $container.addClass('has-local-spinner');
                            $img.hide();
                            $img.on('load.local', function() {
                                $(this).show();
                                $container.removeClass('has-local-spinner');
                            });
                            $img.on('error.local', function() {
                                $(this).off('error.local');
                                $(this).attr('src', fallbackUrl).show();
                                $container.removeClass('has-local-spinner');
                            });
                            $img.attr('src', url);
                        } else {
                            $img.attr('src', fallbackUrl).show();
                            $container.removeClass('has-local-spinner');
                        }
                    }
                    setText('[data-field="name"]', d.name);
                    setVal('#edit_name', d.name);
                    setText('[data-field="mobile"]', d.mobile);
                    setVal('#edit_mobile', d.mobile);
                    if (d.mobile) {
                        let cleanMobile = String(d.mobile).replace(/\D/g, '');
                        let waMobile = cleanMobile.length === 10 ? '91' + cleanMobile : cleanMobile;
                        $('#view-driver-profile .call-icon').attr('href', `tel:${cleanMobile}`);
                        $('#view-driver-profile .whatsapp-icon').attr('href', `https://wa.me/${waMobile}`);
                    }
                    setText('[data-field="age"]', d.age);
                    setVal('#edit_age', d.age);
                    setText('[data-field="email"]', d.email);
                    setVal('#edit_email', d.email);
                    setText('[data-field="address"]', d.address);
                    setVal('#edit_address', d.address);
                    if (d.districts_id) {
                        $('#edit_districts_id').val(d.districts_id);
                        let distName = $('#edit_districts_id option:selected').text();
                        setText('[data-field="city"]', distName !== 'Select District' ? distName : '-');
                    } else {
                        $('#edit_districts_id').val('');
                        setText('[data-field="city"]', '-');
                    }
                    if (d.state && d.state.trim() !== '') {
                        let stateName = d.state.trim();
                        if (window.editStateTomSelect) {
                            window.editStateTomSelect.setValue(stateName);
                        }
                        setText('[data-field="state"]', stateName);
                    } else {
                        if (window.editStateTomSelect) {
                            window.editStateTomSelect.clear();
                        }
                        setText('[data-field="state"]', '-');
                    }
                    setText('#display_experience', d.exp ? d.exp + " Yrs" : "0 Years");
                    setVal('#edit_experience', d.exp);
                    setText('#display_upi', d.upiID);
                    setVal('#edit_upi', d.upiID);
                    $('#profileScoreBadge').html(`<i class="fa fa-check-circle me-1"></i>${d.profile_percentage || 0}% Complete`);
                    if (d.profile_img_url) {
                        setLocalizedImage('.driver-photo', d.profile_img_url, NO_IMAGE_SVG);
                    } else if (d.selfie_url) {
                        setLocalizedImage('.driver-photo', d.selfie_url, NO_IMAGE_SVG);
                    } else {
                        setLocalizedImage('.driver-photo', null, NO_IMAGE_SVG);
                    }
                    if (d.proof_type === 'AADHAR_DIGILOCKER' && d.proof_status === 'approved') {
                        setLocalizedImage('#aadhaarFront img', '/assets/images/digiverify.png', NO_IMAGE_SVG);
                        setLocalizedImage('#aadhaarBack img', '/assets/images/digiverify.png', NO_IMAGE_SVG);
                        setLocalizedImage('#edit-aadhaarFront img', '/assets/images/digiverify.png', NO_IMAGE_SVG);
                        setLocalizedImage('#edit-aadhaarBack img', '/assets/images/digiverify.png', NO_IMAGE_SVG);
                    } else {
                        setLocalizedImage('#aadhaarFront img', d.aadhar_image_front, NO_IMAGE_SVG);
                        setLocalizedImage('#aadhaarBack img', d.aadhar_image_back, NO_IMAGE_SVG);
                        setLocalizedImage('#edit-aadhaarFront img', d.aadhar_image_front, NO_IMAGE_SVG);
                        setLocalizedImage('#edit-aadhaarBack img', d.aadhar_image_back, NO_IMAGE_SVG);
                        aadhaarFrontUrl = d.aadhar_image_front || null;
                        aadhaarBackUrl = d.aadhar_image_back || null;
                    }
                    setLocalizedImage('#licenseFront img', d.license_front_image, NO_IMAGE_SVG);
                    setLocalizedImage('#licenseBack img', d.license_back_image, NO_IMAGE_SVG);
                    setLocalizedImage('#edit-licenseFront img', d.license_front_image, NO_IMAGE_SVG);
                    setLocalizedImage('#edit-licenseBack img', d.license_back_image, NO_IMAGE_SVG);
                    dlFrontUrl = d.license_front_image || null;
                    dlBackUrl = d.license_back_image || null;
                    setText('#display_license_no', d.dl_no);
                    setText('#display_license_expiry', d.dl_expiry);
                    let licType = d.license_type || 'LMV';
                    setText('#display_license_type', licType);
                    setVal('#edit_license_type', licType);
                    setVal('#edit_license_no', d.dl_no);
                    setVal('#edit_license_expiry', d.dl_expiry);
                    setVal('#edit_license_dob', d.dob);
                    let vTypeVal = vehicleData.rc_details?.response?.vehicle_details?.body_type || d.cab_type || '';
                    setText('#display_vehicle_type', vTypeVal);
                    if (window.editVehicleTypeTomSelect) {
                        window.editVehicleTypeTomSelect.setValue(vTypeVal);
                    } else {
                        setVal('#edit_vehicle_type', vTypeVal);
                    }
                    let rawFuel = (vehicleData.rc_details?.response?.vehicle_details?.fuel_type || vehicleData.vehicle_questions?.fuel_type || d.fuel_type || '').toString().trim().toUpperCase();
                    let mappedFuel = '';
                    if (rawFuel.includes('PETROL/CNG') || rawFuel.includes('PETROL / CNG')) mappedFuel = 'PETROL/CNG';
                    else if (rawFuel === 'PETROL') mappedFuel = 'PETROL';
                    else if (rawFuel === 'DIESEL') mappedFuel = 'DIESEL';
                    else if (rawFuel === 'CNG') mappedFuel = 'CNG';
                    setText('#display_fuel', mappedFuel || rawFuel || 'N/A');
                    setVal('#edit_fuel', mappedFuel);
                    const model = vehicleData.rc_details?.response?.vehicle_details?.maker_model;
                    setText('#display_maker_model', model);
                    setVal('#edit_maker_model', model);
                    const color = vehicleData.rc_details?.response?.vehicle_details?.color;
                    setText('#display_colour', color);
                    setVal('#edit_colour', color);
                    const seatCap = vehicleData.rc_details?.response?.vehicle_details?.seat_capacity || d.seat || d.seaters;
                    var disSeat = (seatCap > 1) ? (seatCap - 1) + "+1" : (seatCap || 'N/A');
                    setText('#display_seating', disSeat);
                    setVal('#edit_seating', seatCap);
                    const extLuggage = vehicleData.user_info?.luggage || d.luggage || vehicleData.luggage || vehicleData.vehicle_questions?.luggage_capacity || 'N/A';
                    setText('#display_luggage', extLuggage);
                    setVal('#edit_luggage', extLuggage);
                    if (vehicleData.rc_expiry_date) {
                        setText('#display_rc', formatDateSimple(vehicleData.rc_expiry_date));
                        const expDate = vehicleData.insurance_details?.insurance_exp_date;
                        let displayText = 'N/A';
                        if (expDate) {
                            const formatted = formatDateSimple(expDate);
                            if (formatted && formatted !== 'Invalid Date') {
                                displayText = formatted;
                            }
                        }
                        setText('#display_insurance', displayText);
                        setVal('#edit_rc', vehicleData.rc_expiry_date);
                        setVal('#edit_insurance', vehicleData.insurance_details?.insurance_exp_date);
                    } else {
                        setText('#display_rc', null);
                        setText('#display_insurance', null);
                        setVal('#edit_rc', null);
                        setVal('#edit_insurance', null);
                    }
                    const vImages = vehicleData.vehicle || {};
                    setLocalizedImage('#main_vehicle_preview', vImages.front_view_image_url, NO_IMAGE_SVG);
                    setLocalizedImage('.vehicle-photo', vImages.front_view_image_url, NO_IMAGE_SVG);
                    const imgMapping = [{
                            preview: '#prev_front_view',
                            input: '#val_front_view_image_url',
                            url: vImages.front_view_image_url,
                            target: 'front_view'
                        },
                        {
                            preview: '#prev_boot_image',
                            input: '#val_boot_image_url',
                            url: vImages.boot_image_url,
                            target: 'boot_image'
                        },
                        {
                            preview: '#prev_extra_image_1',
                            input: '#val_extra_image_1_url',
                            url: vImages.extra_image_1_url,
                            target: 'extra_image_1'
                        },
                        {
                            preview: '#prev_car_top_view',
                            input: '#val_car_top_view_image_url',
                            url: vImages.car_top_view_image_url,
                            target: 'car_top_view'
                        },
                        {
                            preview: '#prev_interior_front',
                            input: '#val_interior_front_image_url',
                            url: vImages.interior_front_image_url,
                            target: 'interior_front'
                        },
                        {
                            preview: '#prev_special_features',
                            input: '#val_special_features_image_url',
                            url: vImages.special_features_image_url,
                            target: 'special_features'
                        }
                    ];
                    imgMapping.forEach(item => {
                        $(item.input).val(item.url || '');
                        setLocalizedImage(item.preview, item.url, '/assets/images/placeholder.png');
                        if (item.url && item.url !== '' && item.url !== 'null') {
                            $(`.clear-img-btn[data-target="${item.target}"]`).removeClass('d-none');
                        } else {
                            $(`.clear-img-btn[data-target="${item.target}"]`).addClass('d-none');
                        }
                    });
                    updateSimpleImages();
                    const $galleryDiv = $('#hiddenGalleryImages');
                    $galleryDiv.empty();
                    const allGalleryImages = [
                        vImages.front_view_image_url,
                        vImages.boot_image_url,
                        vImages.extra_image_1_url,
                        vImages.car_top_view_image_url,
                        vImages.interior_front_image_url,
                        vImages.special_features_image_url
                    ];
                    allGalleryImages.forEach(imgUrl => {
                        if (imgUrl && imgUrl !== '' && imgUrl !== 'null') {
                            $galleryDiv.append(`<img src="${imgUrl}" alt="Vehicle View">`);
                        }
                    });
                    refreshVehicleGallery();
                    setLocalizedImage('#rcDocumentFront img', vehicleData.rc_front_image_url, NO_IMAGE_SVG);
                    setLocalizedImage('#rcDocumentBack img', vehicleData.rc_back_image_url, NO_IMAGE_SVG);
                    setLocalizedImage('#pucDocument img', vehicleData.puc_details?.puc_image_url, NO_IMAGE_SVG);
                    setLocalizedImage('#insuranceDocument img', vehicleData.insurance_details?.insurance_image_url, NO_IMAGE_SVG);
                    setLocalizedImage('#edit-rcDocumentFront img', vehicleData.rc_front_image_url, NO_IMAGE_SVG);
                    setLocalizedImage('#edit-rcDocumentBack img', vehicleData.rc_back_image_url, NO_IMAGE_SVG);
                    setLocalizedImage('#edit-pucDocument img', vehicleData.puc_details?.puc_image_url, NO_IMAGE_SVG);
                    setLocalizedImage('#edit-insuranceDocument img', vehicleData.insurance_details?.insurance_image_url, NO_IMAGE_SVG);
                    rcFrontUrl = vehicleData.rc_front_image_url || null;
                    rcBackUrl = vehicleData.rc_back_image_url || null;
                    pucUrl = vehicleData.puc_details?.puc_image_url || null;
                    insuranceUrl = vehicleData.insurance_details?.insurance_image_url || null;
                    $('#edit_rc_number').val(vehicleData.rc_number || '');
                    $('#edit_rc_expiry').val(vehicleData.rc_expiry_date || '');
                    $('#edit_puc_expiry').val(vehicleData.puc_details?.puc_exp_date || '');
                    $('#edit_insurance_expiry').val(vehicleData.insurance_details?.insurance_exp_date || '');
                    setText('#display_per_km', d.per_km);
                    setText('#display_extra_km', d.extra_per_km);
                    setText('#display_extra_hour', d.per_hour);
                    setText('#display_extra_day', d.per_day);
                    setVal('#edit_per_km', d.per_km);
                    setVal('#edit_extra_km', d.extra_per_km);
                    setVal('#edit_extra_hour', d.per_hour);
                    setVal('#edit_extra_day', d.per_day);
                    setText('[data-field="languages"]', vehicleData.user_info?.language);
                    setText('#display_review', d.reviews);
                    setVal('#edit_review', d.reviews);
                    setText('#display_remarks', d.remarks);
                    setVal('#edit_remarks', d.remarks);
                    updateSectionIcons(d, vehicleData);
                    loadDriverSchedule(id);
                    $('#driverEditDrawer .offcanvas-body > *:not(#driverEditLoader)').css('opacity', 1);
                    $('#driverEditLoader').addClass('d-none');
                },
                error: function() {
                    $('#driverEditDrawer .offcanvas-body > *:not(#driverEditLoader)').css('opacity', 1);
                    $('#driverEditLoader').addClass('d-none');
                }
            });
        }
        $(document).on('click', '.driver-edit-icon', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var driverId = $(this).data('driver');
            if (!driverId) {
                showNotification('Driver ID not found', 'error');
                return;
            }
            $('#driverPreviewCanvas').offcanvas('hide');
            openDriverEditDrawer(driverId);
        });
        $(document).on('click', '.driver-link', function(e) {
            e.preventDefault();
            const driverId = $(this).data('driver');
            const driverCanvas = new bootstrap.Offcanvas('#driverPreviewCanvas');
            driverCanvas.show();
            $('#driverProfileLoader').removeClass('d-none');
            $('#driverDetailsCard .card-body > *:not(#driverProfileLoader)')
                .css('opacity', 0.3);
            $('#profileProgressBar').css('width', '0%');
            getDriverDetails(driverId);
        });
        $(document).on('click', '#driverDetailsCard img:first', function() {
            const imgSrc = $(this).attr('src');
            $('#driverPhotoModal .modal-body img')
                .attr('src', imgSrc);
        });
        function initLocationAutocomplete(selector) {
            const $input = $(selector);
            $input.autocomplete({
                minLength: 3,
                source: function(request, response) {
                    response([{
                        label: 'Loading locations...',
                        value: ''
                    }]);
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
                        success: function(res) {
                            if (!res.status || !res.data || !res.data.length) {
                                response([{
                                    label: 'No locations found',
                                    value: ''
                                }]);
                                return;
                            }
                            response(res.data.map(function(item) {
                                return {
                                    id: item.place_id,
                                    label: item.name,
                                    value: item.name,
                                    latitude: item.latitude,
                                    longitude: item.longitude
                                };
                            }));
                        },
                        error: function() {
                            response([{
                                label: 'Error loading locations',
                                value: ''
                            }]);
                        }
                    });
                },
                select: function(event, ui) {
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
                <div class="border p-3 mb-3">
                    <h6 class="text-muted mb-3">Customer Details</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" id="custName" class="form-control" placeholder="Customer Name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mobile <span class="text-danger">*</span></label>
                            <input type="text" id="custMobile" class="form-control" placeholder="Mobile Number">
                        </div>
                    </div>
                </div>
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
                            <option value="mini" selected>Go Mini</option>
                            <option value="four_seater">Go 4Seaters</option>
                            <option value="six_seater">Go 6Seaters</option>
                            <option value="seven_seater">Go 7Seaters</option>
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
                            <label class="form-label">Passengers <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="passengers" value="4" min="1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Luggage <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="luggage" value="2" min="0">
                        </div>
                    </div>
                </div>
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
        <div class="col-md-2">
            <label class="form-label">Base Fare <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="baseFare" placeholder="Base Fare">
        </div>
        <div class="col-md-2">
            <label class="form-label">Tax (GST)</label>
            <input type="number" id="taxFare" class="form-control" placeholder="Tax" value="0">
        </div>
        <div class="col-md-2">
            <label class="form-label">Toll Fare</label>
            <input type="number" class="form-control" id="tollFare" placeholder="Toll">
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
        $(document).on('click', '.createJobBtn', function() {
            $('#mapCard, #leftPanel, #incomingBidsPanel, #ownerDetailsCard, #driverListPanel').addClass('d-none');
            $('#jobDetailsPanel').removeClass('d-none');
            $('#updateJobBtn').addClass('d-none');
            $('#confirmBookingBtn').removeClass('d-none');
            $('#jobDetailsContent').html(getJobFormHTML());
            initLocationAutocomplete('#pickupLocation');
            initLocationAutocomplete('#dropLocation');
        });
        $(document).on('click', '#getDistanceBtn', function() {
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
            if (carType === 'seven_seater' || carType === 'six_seater') {
                apiCarKey = 'seven_seater';
            } else if (carType === 'mini') {
                apiCarKey = 'mini';
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
                beforeSend: function() {
                    $btn.prop('disabled', true)
                        .html('<i class="fa fa-spinner fa-spin me-1"></i> Calculating...');
                    $('#distance, #duration, #baseFare, #tollFare, #taxFare').val('');
                    $('#totalFare').text('₹0');
                },
                success: function(response) {
                    if (response.status && response.data) {
                        let selectedCarKey = $('#carType').val();
                        let cab = response.data[selectedCarKey];
                        if (!cab) {
                            const availableKeys = Object.keys(response.data);
                            cab = response.data[availableKeys[0]];
                        }
                        $('#distance').val(cab.distance);
                        $('#duration').val(cab.duration);
                        $('#baseFare').val(cab.fare);
                        $('#tollFare').val(cab.toll_fare);
                        let taxValue = cab.tax_fare || 0;
                        $('#taxFare').val(taxValue);
                        const base = parseInt(cab.fare) || 0;
                        const toll = parseInt(cab.toll_fare) || 0;
                        const tax = parseInt(taxValue) || 0;
                        const total = base + toll + tax;
                        $('#totalFare').text('₹' + total.toLocaleString('en-IN'));
                        $('#editTotalFare').val(total);
                        toast('success', 'Fare calculated with GST');
                    }
                },
                error: function() {
                    toast('error', 'Something went wrong. Please check your connection and try again.');
                },
                complete: function() {
                    $btn.prop('disabled', false)
                        .html('<i class="fa fa-route me-1"></i> Get Distance');
                }
            });
        });
        $(document).on('change', '#jobType', function() {
            const jobType = $(this).val();
            const $wrapper = $('#returnDaysWrapper');
            const $select = $('#returnDays');
            if (jobType === 'round_trip') {
                $wrapper.removeClass('d-none');
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
        $(document).off('click', '#confirmBookingBtn').on('click', '#confirmBookingBtn', function() {
            const $btn = $(this);
            const cName = $('#custName').val() ? $('#custName').val().trim() : '';
            const cEmail = $('#custEmail').val() ? $('#custEmail').val().trim() : '';
            const cMobile = $('#custMobile').val() ? $('#custMobile').val().trim() : '';
            let jobType = $('#jobType').val() || '';
            let carType = $('#carType').val() || '';
            let returnDays = $('#returnDays').val() || '';
            const pickupDateTime = $('#pickupDateTime').val() || '';
            const fromPlace = $('#pickupLocation').val() || '';
            const toPlace = $('#dropLocation').val() || '';
            const fromPlaceId = $('#pickupLocation').data("place-id") || '';
            const toPlaceId = $('#dropLocation').data("place-id") || '';
            const distance = $('#distance').val() || 0;
            const duration = $('#duration').val() || '';
            const baseFare = parseFloat($('#baseFare').val()) || 0;
            const tollFare = parseFloat($('#tollFare').val()) || 0;
            const taxFare = parseFloat($('#taxFare').val()) || 0;
            let totalFare = parseFloat($('#editTotalFare').val()) || 0;
            if (totalFare <= 0) {
                totalFare = baseFare + tollFare + taxFare;
            }
            const passengers = $('#passengers').val() || 4;
            const luggage = $('#luggage').val() || 2;
            $('.form-control, .form-select').removeClass('is-invalid border-danger');
            let hasError = false;
            let missingFields = [];
            function markError(selector, fieldName) {
                $(selector).addClass('is-invalid border-danger');
                if (!missingFields.includes(fieldName)) missingFields.push(fieldName);
                hasError = true;
            }
            if (!cName) markError('#custName', 'Name');
            if (!cMobile) markError('#custMobile', 'Mobile');
            if (!jobType) markError('#jobType', 'Job Type');
            if (!carType) markError('#carType', 'Car Type');
            if (jobType === 'round_trip' && !returnDays) markError('#returnDays', 'Return Days');
            if (!fromPlace || !fromPlaceId) markError('#pickupLocation', 'Pickup Location');
            if (!toPlace || !toPlaceId) markError('#dropLocation', 'Drop Location');
            if (!pickupDateTime) markError('#pickupDateTime', 'Pickup Date & Time');
            if (hasError) {
                toast('error', `${missingFields.join(', ')} is required.`);
                $('html, body').animate({
                    scrollTop: $('.is-invalid').first().offset().top - 100
                }, 300);
                return;
            }
            if (totalFare <= 0) {
                toast('error', 'Please calculate distance and fare first.');
                return;
            }
            jobType = jobType === 'one_way' ? 'oneway' : 'roundtrip';
            let dropoffDate = "";
            let dayText = "";
            if (jobType === 'roundtrip') {
                dropoffDate = returnDays;
                dayText = returnDays + " Days";
            }
            const formattedPickup = pickupDateTime.replace('T', ' ') + ':00';
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
                        distance: distance,
                        duration: duration,
                        day: dayText,
                        cab_type: carType,
                        base_fare: baseFare,
                        toll_fare: tollFare,
                        tax: taxFare,
                        fare: totalFare,
                        add_fare_details: {
                            bata: "Excluded",
                            parking: "Excluded",
                            toll: "Excluded"
                        },
                        type: "customer",
                        c_name: cName,
                        c_email: cEmail,
                        c_mobile: cMobile,
                        pick_address: "",
                        drop_address: "",
                        isDriver: "no"
                    },
                    beforeSend: function() {
                        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Creating...');
                    },
                    success: function(res) {
                        if (!res.status) {
                            toast('error', res.message || 'Booking failed');
                            return;
                        }
                        const jobNo = res.data || null;
                        $.ajax({
                            url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/web-send-bookinfo",
                            type: "POST",
                            dataType: "json",
                            headers: {
                                'X-CSRF-TOKEN': 'gRZlvH9xMVbgCRLQbmV8co6bmAjYpWuvuAY64Mnw'
                            },
                            data: {
                                job_no: jobNo,
                                mob: cMobile,
                                c_name: cName,
                                c_email: cEmail,
                                from_place: fromPlace,
                                to_place: toPlace,
                                pickup_date: formattedPickup,
                                fare: totalFare,
                                cab_type: carType
                            },
                            success: function(waRes) {
                                Swal.fire({
                                    icon: waRes.status ? 'success' : 'warning',
                                    title: waRes.status ? 'Job Created & WhatsApp Sent' : 'Job Created',
                                    allowOutsideClick: false,
                                    text: waRes.status ? `Job No: ${jobNo}` : 'WhatsApp sending failed'
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Job Created',
                                    allowOutsideClick: false,
                                    text: 'WhatsApp sending failed'
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        });
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON ? (xhr.responseJSON.message || 'Validation Error') : 'Server error. Try again.';
                        toast('error', msg);
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html('<i class="fa fa-check-circle me-1"></i> Confirm Booking');
                    }
                });
            });
        });
        $(document).on('input change', '.is-invalid', function() {
            $(this).removeClass('is-invalid border-danger');
        });
        $(document).on('input change', '.is-invalid', function() {
            $(this).removeClass('is-invalid border-danger');
        });
        function renderDriversList() {
            $('#driversList').empty();
            window.filteredDriversToCopy = [];
            let searchText = $('#driverSearch').val()?.toLowerCase().trim() || '';
            let selectedFields = $('#profileFieldFilter').val() || [];
            let filterType = $('#profileFilterType').val();
            let visibleCount = 0;
            let pMin = typeof percentageMin !== 'undefined' ? percentageMin : 0;
            let pMax = typeof percentageMax !== 'undefined' ? percentageMax : 100;
            Object.entries(driversAll).forEach(([id, driver]) => {
                let data = driver.fullData || {};
                if (searchText !== '') {
                    let nameMatch = driver.name && String(driver.name).toLowerCase().includes(searchText);
                    let phoneMatch = driver.mobile && String(driver.mobile).includes(searchText);
                    if (!nameMatch && !phoneMatch) return;
                }
                const profilePercent = driver.profilePercent ?? 0;
                if (profilePercent < pMin || profilePercent > pMax) {
                    return;
                }
                if (selectedFields.length > 0 && filterType) {
                    let match = selectedFields.every(field => {
                        let value = null;
                        let vd = {};
                        if (data.vehicle_details) {
                            try {
                                vd = typeof data.vehicle_details === 'string' ? JSON.parse(data.vehicle_details) : data.vehicle_details;
                            } catch (e) {}
                        }
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
                                let rawCab = vd?.rc_details?.response?.vehicle_details?.body_type || data.cab_type || data.vehicle_type;
                                let isValidCab = false;
                                if (rawCab) {
                                    let rawCabLower = String(rawCab).toLowerCase().trim();
                                    $('#gdFilterCab option').each(function() {
                                        if ($(this).val() && $(this).val().toLowerCase().trim() === rawCabLower) {
                                            isValidCab = true;
                                        }
                                    });
                                }
                                value = isValidCab ? rawCab : "";
                                break;
                            case 'fuel_type':
                                value = vd?.rc_details?.response?.vehicle_details?.fuel_type || vd?.vehicle_questions?.fuel_type || data.fuel_type || data.fuel_types;
                                break;
                            case 'seaters':
                                value = vd?.rc_details?.response?.vehicle_details?.seat_capacity || data.seat || data.seaters;
                                break;
                            case 'upiID':
                                value = data.upiID || data.upi_id;
                                break;
                            case 'districts_id':
                                value = data.districts_id || data.district_id;
                                break;
                            default:
                                value = data[field];
                        }
                        // 🔥 FIX: Updated logic to treat "0" and 0 as "Not Filled"
                        const isFilled = (val) => {
                            if (val === null || val === undefined) return false;
                            if (typeof val === "number" && val === 0) return false;
                            if (typeof val === "string") {
                                val = val.trim();
                                // Added check for "0"
                                if (val === "" || val.toLowerCase() === "null" || val === "n/a" || val === "-" || val === "0") {
                                    return false;
                                }
                            }
                            return true;
                        };
                        if (filterType === 'filled') {
                            return isFilled(value);
                        } else if (filterType === 'not_filled') {
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
                let safeName = driver.name || 'Unknown';
                let safeMobile = driver.mobile || 'N/A';
                window.filteredDriversToCopy.push(`${safeName} - ${safeMobile}`);
                $('#driversList').append(`
            <div class="list-group-item d-flex justify-content-between align-items-center driver-item"
                 data-driver="${driver.user_id}">
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small fw-bold">${visibleCount}.</span>
                        <strong class="driver-link cursor-pointer" data-driver="${driver.user_id}">${driver.name || 'Unknown'}</strong>
                        <i class="fa fa-edit text-info cursor-pointer driver-edit-icon" 
                           data-driver="${driver.user_id}" 
                           title="Edit Driver"></i>
                        <i class="fa fa-map-marker-alt text-danger cursor-pointer view-driver-location-tab ms-1" 
                           data-driver="${driver.user_id}" 
                           data-name="${driver.name}" 
                           title="View on Map"></i>
                    </div>
                    <small class="text-muted d-block" style="font-size: 11px;">
                        <i class="fa fa-phone me-1"></i>${driver.mobile || 'N/A'}
                    </small>
                    <small class="text-${statusColor}">
                        ● ${driver.status}
                    </small>
                </div>
                <div class="profile-circle ${profileClass}">
                    ${profilePercent}%
                </div>
            </div>
        `);
            });
            $('#driverCount').text(visibleCount);
            if (visibleCount === 0) {
                $('#driversList').html(`
            <div class="list-group-item text-center text-muted py-3">
                <i class="fa fa-filter me-2"></i>No drivers found matching filters
            </div>
        `);
            }
        }
        function loadDriversList() {
            $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-get-driver-list",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                success: function(res) {
                    if (!res.status || !res.data) {
                        toast('error', 'Failed to load drivers');
                        return;
                    }
                    driversAll = {};
                    res.data.forEach(driver => {
                        let status = 'online';
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
                error: function() {
                    toast('error', 'Driver list error');
                }
            });
        }
        function renderDriverList(jobId, searchText = '', existingBidders = new Set()) {
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
                const hasBid = existingBidders.has(String(driver.user_id));
                const buttonClass = hasBid ? 'manageBidBtn btn-success' : 'makeBidBtn btn-warning';
                const buttonText = hasBid ? 'Manage Bid' : 'Make a Bid';
                const buttonIcon = hasBid ? 'fa-edit' : 'fa-gavel';
                html += `
<div class="bid-item mb-2 p-2">
    <div class="d-flex align-items-center gap-2">
        <img src="${driver.profileImage}"
             class="rounded-circle"
             style="width:36px;height:36px;object-fit:cover;">
        <strong>${driver.name || 'Unknown Driver'}</strong>
        <span class="badge bg-${statusClass}">
            ${driver.status}
        </span>
        <button class="btn btn-sm ms-auto ${buttonClass}"
                data-driver="${driver.user_id}"
                data-job="${jobId}">
            <i class="fa ${buttonIcon} me-1"></i> ${buttonText}
        </button>
    </div>
    </div>
            </div>
            <div class="d-flex gap-3 mt-2 small text-muted">
                <img src="${driver.vehicleImage}"
                     class="rounded"
                     style="width:90px;height:60px;object-fit:cover;">
                <div class="flex-grow-1">
                    <div class="fw-semibold text-dark">
                        <i class="fa fa-car text-danger me-1"></i>
                        ${driver.car}
                    </div>
                    <div class="d-flex gap-3 mt-1">
                        <span><i class="fa fa-users text-info"></i> ${driver.seats}</span>
                        <span><i class="fa fa-gas-pump text-warning"></i> ${driver.fuel}</span>
                        <span><i class="fa fa-suitcase text-secondary"></i> ${driver.luggage}</span>
                    </div>
                    <div class="d-flex align-items-center gap-3 mt-2">
                        <span>
                            <i class="fa fa-star text-warning"></i>
                            <strong>${driver.rating}</strong>
                        </span>
                        <span class="text-muted">
                            (${driver.ratingsCount} Ratings)
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
        function loadAvailableDrivers(jobId, jobNo) {
            currentJobId = jobId;
            $('#driverListContainer').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
                    <div class="mt-3 text-muted fw-semibold">Loading drivers<div>
                </div>
            `);
            const fetchDrivers = $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-get-driver-list",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                }
            });
            const fetchBids = db.collection('<?= rtrim(FIREBASE_COLLECTION, '/') ?>').doc(jobNo).get();
            Promise.all([fetchDrivers, fetchBids])
                .then(([res, doc]) => {
                    if (!res.status || !res.data) {
                        $('#driverListContainer').html('<div class="alert alert-danger m-3 text-center">Failed to load drivers</div>');
                        return;
                    }
                    availableDrivers = {};
                    res.data.forEach(driver => {
                        let status = 'offline';
                        if (driver.rm_status == 1) status = 'online';
                        if (driver.rm_status == 2) status = 'busy';
                        let cabImg = '/assets/images/carimage.png';
                        if (driver.vehicle_details) {
                            try {
                                const parsedDetails = JSON.parse(driver.vehicle_details);
                                if (parsedDetails?.vehicle?.front_view_image_url) {
                                    cabImg = parsedDetails.vehicle.front_view_image_url;
                                }
                            } catch (e) {
                                console.log('Could not parse vehicle details JSON');
                            }
                        }
                        availableDrivers[driver.id] = {
                            id: driver.id,
                            user_id: driver.user_id,
                            name: driver.name,
                            profileImage: driver.selfie_url || '/assets/images/driver.png',
                            status: status,
                            rating: driver.ratings || 0,
                            ratingsCount: 0,
                            car: driver.cab_type || 'N/A',
                            seats: '4+1',
                            fuel: driver.vehicle_type || 'Petrol',
                            luggage: 2,
                            vehicleImage: cabImg
                        };
                    });
                    currentExistingBidders = new Set();
                    if (doc.exists) {
                        const bids = doc.data().bids_details || {};
                        currentExistingBidders = new Set(Object.keys(bids));
                    }
                    renderDriverList(jobId, '', currentExistingBidders);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    $('#driverListContainer').html('<div class="alert alert-danger m-3 text-center">Error loading driver list</div>');
                });
        }
        $(document).on('click', '.manageBidBtn', function() {
            const $btn = $(this);
            const driverId = $btn.data('driver');
            const jobId = $btn.data('job');
            let jobNo = $('#availableDriversTitle span.text-primary').text().replace(/[()]/g, '').trim();
            if (!jobNo) {
                jobNo = $('#incomingBidsTitle span.text-primary').text().replace(/[()]/g, '').trim();
            }
            $('#bidDriverId').val(driverId);
            $('#bidJobId').val(jobId);
            const isPencil = $btn.hasClass('fa-pencil-alt');
            const originalHtml = $btn.html();
            if (isPencil) {
                $btn.removeClass('fa-pencil-alt').addClass('fa-spinner fa-spin');
            } else {
                $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            }
            db.collection('<?= rtrim(FIREBASE_COLLECTION, '/') ?>')
                .doc(jobNo)
                .get()
                .then(doc => {
                    if (doc.exists) {
                        const bids = doc.data().bids_details || {};
                        const bid = bids[driverId];
                        if (bid) {
                            $('#bidAmount').val(bid.amount);
                            $('#bidRemark').val(bid.remark || '');
                        } else {
                            $('#bidAmount').val('');
                            $('#bidRemark').val('');
                        }
                    } else {
                        $('#bidAmount').val('');
                        $('#bidRemark').val('');
                    }
                    $('#bidErrorMsg').addClass('d-none');
                    $('#createBidModal .modal-title').html('<i class="fa fa-edit me-2 text-warning"></i> Edit Bid');
                    $('.bid-btn-text').html('<i class="fa fa-save me-1"></i> Update Bid');
                    $('#createBidModal').modal('show');
                })
                .catch(error => {
                    console.error('Error fetching bid:', error);
                    toast('error', 'Could not load bid details');
                    $('#bidAmount').val('');
                    $('#bidRemark').val('');
                    $('#createBidModal').modal('show');
                })
                .finally(() => {
                    if (isPencil) {
                        $btn.removeClass('fa-spinner fa-spin').addClass('fa-pencil-alt');
                    } else {
                        $btn.prop('disabled', false).html(originalHtml);
                    }
                });
        });
        $(document).on('keyup', '#driverSearchInput', function() {
            renderDriverList(currentJobId, $(this).val(), currentExistingBidders);
        });
        $(document).on('click', '.unassigned-driver-btn', function() {
            const jobId = $(this).data('job');
            const jobNo = $(this).data('jobno');
            $('#availableDriversTitle').html(`Available Drivers <span class="text-primary ms-1">(${jobNo})</span>`);
            $('#mapCard, #incomingBidsPanel').addClass('d-none');
            $('#leftPanel').removeClass('d-none');
            $('#driverListPanel').removeClass('d-none');
            loadAvailableDrivers(jobId, jobNo);
        });
        $(document).on('click', '.makeBidBtn', function() {
            const driverId = $(this).data('driver');
            const jobId = $(this).data('job');
            $('#bidDriverId').val(driverId);
            $('#bidJobId').val(jobId);
            $('#bidAmount').val('');
            $('#bidRemark').val('');
            $('#bidErrorMsg').addClass('d-none');
            $('#bidAmount').closest('.mb-3').removeClass('d-none');
            $('#createBidModal .modal-title').html('<i class="fa fa-gavel  text-warning"></i> Create Bid');
            $('.bid-btn-text').html('<i class="fa fa-paper-plane"></i> Bid');
            $('#createBidModal').modal('show');
        });
        $(document).on('click', '.manageBidBtn', function() {
            const $btn = $(this);
            const driverId = $btn.data('driver');
            const jobId = $btn.data('job');
            const isPencilIcon = $btn.is('i');
            let jobNo = $('#availableDriversTitle span.text-primary').text().replace(/[()]/g, '').trim();
            if (!jobNo) {
                jobNo = $('#incomingBidsTitle span.text-primary').text().replace(/[()]/g, '').trim();
            }
            $('#bidDriverId').val(driverId);
            $('#bidJobId').val(jobId);
            if (isPencilIcon) {
                $btn.removeClass('fa-pencil-alt').addClass('fa-spinner fa-spin');
            } else {
                $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            }
            db.collection('<?= rtrim(FIREBASE_COLLECTION, '/') ?>')
                .doc(jobNo)
                .get()
                .then(doc => {
                    if (doc.exists) {
                        const bids = doc.data().bids_details || {};
                        const bid = bids[driverId];
                        if (bid) {
                            $('#bidAmount').val(bid.amount);
                            $('#bidRemark').val(bid.remark || '');
                        } else {
                            $('#bidAmount').val('');
                            $('#bidRemark').val('');
                        }
                    } else {
                        $('#bidAmount').val('');
                        $('#bidRemark').val('');
                    }
                    $('#bidErrorMsg').addClass('d-none');
                    if (isPencilIcon) {
                        $('#bidAmount').closest('.mb-3').addClass('d-none');
                        $('#createBidModal .modal-title').html('<i class="fa fa-comment-dots me-2 text-warning"></i> Edit Remark');
                        $('.bid-btn-text').html('<i class="fa fa-save me-1"></i> Update Remark');
                    } else {
                        $('#bidAmount').closest('.mb-3').removeClass('d-none');
                        $('#createBidModal .modal-title').html('<i class="fa fa-edit me-2 text-warning"></i> Manage Bid');
                        $('.bid-btn-text').html('<i class="fa fa-save me-1"></i> Update Bid');
                    }
                    if (isPencilIcon) {
                        $btn.removeClass('fa-spinner fa-spin').addClass('fa-pencil-alt');
                    } else {
                        $btn.prop('disabled', false).html('<i class="fa fa-edit me-1"></i> Manage Bid');
                    }
                    $('#createBidModal').modal('show');
                })
                .catch(error => {
                    console.error('Error fetching bid:', error);
                    toast('error', 'Could not load bid details');
                    if (isPencilIcon) {
                        $btn.removeClass('fa-spinner fa-spin').addClass('fa-pencil-alt');
                    } else {
                        $btn.prop('disabled', false).html('<i class="fa fa-edit me-1"></i> Manage Bid');
                    }
                });
        });
        $(document).on('click', '#submitBidBtn', function() {
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
                success: function(res) {
                    if (res.status) {
                        $('#createBidModal').modal('hide');
                        const isUpdate = $('.modal-title:contains("Edit"), .manageBidBtn:focus, .fa-pencil-alt:focus').length > 0;
                        toast('success', isUpdate ? 'Bid updated successfully' : 'Bid created successfully');
                        const $btnToUpdate = $(`.makeBidBtn[data-driver="${driverId}"][data-job="${jobId}"]`);
                        if ($btnToUpdate.length) {
                            $btnToUpdate
                                .removeClass('makeBidBtn btn-warning')
                                .addClass('manageBidBtn btn-success')
                                .html('<i class="fa fa-edit me-1"></i> Manage Bid');
                        }
                    } else {
                        $('#bidErrorMsg').text(res.message || 'Bid failed').removeClass('d-none');
                    }
                },
                error: function() {
                    $('#bidErrorMsg')
                        .text('Server error. Try again.')
                        .removeClass('d-none');
                },
                complete: function() {
                    $btn.prop('disabled', false);
                    $('.bid-btn-text').removeClass('d-none');
                    $('.bid-btn-loader').addClass('d-none');
                }
            });
        });
        $(document).on('click', '#confirmCancelJobBtn', function() {
            const jobId = $('#cancelJobId').val();
            const jobNo = $('#cancelJobNo').val();
            const jobType = $('#cancelJobType').val();
            const userId = $('#cancelJobUserId').val();
            const jobStatus = $('#cancelJobStatus').val();
            console.log(jobStatus);
            const cancelReason = $('#cancelJobReason').val().trim();
            const cancelledBy = "<?= $_SESSION['memid'] ?? 1 ?>";
            const $btn = $(this);
            if (!jobId) return;
            if (!cancelReason) {
                $('#cancelJobError').text('Please enter a cancellation reason.').removeClass('d-none');
                return;
            }
            $('#cancelJobError').addClass('d-none');
            $btn.prop('disabled', true);
            $('.cancel-btn-text').addClass('d-none');
            $('.cancel-btn-loader').removeClass('d-none');
            if (jobNo && String(jobNo).startsWith('GRD-')) {
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
                        job_type: jobType,
                        cancel_reason: cancelReason,
                        cancelled_by: cancelledBy
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Cancelling...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(res) {
                        if (res.status) {
                            Swal.fire('Cancelled!', 'Job was cancelled successfully.', 'success').then(() => {
                                $('#cancelJobModal').modal('hide');
                                reloadJobsWithFilter();
                            });
                        } else {
                            Swal.fire('Error', res.message || 'Cancel failed', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Server error during cancellation.', 'error');
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                        $('.cancel-btn-text').removeClass('d-none');
                        $('.cancel-btn-loader').addClass('d-none');
                    }
                });
            } else if (jobStatus === 'accept') {
                $.ajax({
                    url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-cancel-job-assigned",
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
                        job_type: jobType,
                        cancel_reason: cancelReason,
                        cancelled_by: cancelledBy
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Cancelling...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(res) {
                        if (res.status) {
                            Swal.fire('Cancelled!', '', 'success').then(() => {
                                $('#cancelJobModal').modal('hide');
                                reloadJobsWithFilter();
                            });
                        } else {
                            Swal.fire('Error', res.message || 'Cancel failed', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Server error during cancellation.', 'error');
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                        $('.cancel-btn-text').removeClass('d-none');
                        $('.cancel-btn-loader').addClass('d-none');
                    }
                });
            } else if (jobType === 'schedule' || jobType === 'scheduled') {
                $.ajax({
                    url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-cancel-scheduled-job",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                    },
                    data: {
                        job_id: jobId,
                        job_no: jobNo,
                        cancel_reason: cancelReason,
                        cancelled_by: cancelledBy
                    },
                    success: function(response) {
                        if (response.type === 1 || response.status === true) {
                            $('#cancelJobModal').modal('hide');
                            toast('success', response.message || 'Scheduled job cancelled successfully');
                            $(`.cancelJobBtn[data-job="${jobId}"]`)
                                .closest('.card')
                                .fadeOut(300, function() {
                                    $(this).remove();
                                });
                        } else {
                            $('#cancelJobError').text(response.message || 'Cancel failed').removeClass('d-none');
                        }
                    },
                    error: function(xhr) {
                        $('#cancelJobError').text('Server error. Try again.').removeClass('d-none');
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                        $('.cancel-btn-text').removeClass('d-none');
                        $('.cancel-btn-loader').addClass('d-none');
                    }
                });
            }
            // --- EXISTING LOGIC FOR WEBSITE JOBS ---
            else if (userId == 0 || !userId) {
                $.ajax({
                    url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-cancel-website-job",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                    },
                    data: {
                        job_id: jobId,
                        job_no: jobNo,
                        cancel_reason: cancelReason,
                        cancelled_by: cancelledBy
                    },
                    success: function(response) {
                        if (response.type === 1 || response.status === true) {
                            $('#cancelJobModal').modal('hide');
                            toast('success', response.message || response.result || 'Job cancelled successfully');
                            $(`.cancelJobBtn[data-job="${jobId}"]`)
                                .closest('.card')
                                .fadeOut(300, function() {
                                    $(this).remove();
                                });
                        } else {
                            $('#cancelJobError')
                                .text(response.message || response.result || 'Cancel failed')
                                .removeClass('d-none');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Server error. Try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        $('#cancelJobError')
                            .text(errorMsg)
                            .removeClass('d-none');
                    },
                    complete: function() {
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
                        job_type: jobType,
                        cancel_reason: cancelReason,
                        cancelled_by: cancelledBy
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Cancelling...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(res) {
                        if (res.status) {
                            Swal.fire('Cancelled!', '', 'success').then(() => {
                                $('#cancelJobModal').modal('hide');
                                reloadJobsWithFilter();
                            });
                        } else {
                            Swal.fire('Error', res.message || 'Cancel failed', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Server error during cancellation.', 'error');
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                        $('.cancel-btn-text').removeClass('d-none');
                        $('.cancel-btn-loader').addClass('d-none');
                    }
                });
            }
        });
        $(document).on('click', '.cancelJobBtn', function() {
            const jobId = $(this).data('job');
            const jobNo = $(this).data('job-no');
            const jobType = $(this).data('jobtype');
            const userId = $(this).data('user-id');
            const jobStatus = $(this).data('jobstatus');
            $('#cancelJobId').val(jobId);
            $('#cancelJobNo').val(jobNo);
            $('#cancelJobType').val(jobType);
            $('#cancelJobUserId').val(userId);
            $('#cancelJobStatus').val(jobStatus);
            $('#cancelJobReason').val('');
            $('#cancelJobError').addClass('d-none');
            $('#cancelJobModal').modal('show');
        });
        $(document).on('click', '[data-filter]', function(e) {
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
        $(function() {
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
            $('#jobDateRange').on('apply.daterangepicker', function(ev, picker) {
                selectedStartDate = picker.startDate.format('YYYY-MM-DD');
                selectedEndDate = picker.endDate.format('YYYY-MM-DD');
                $(this).val(
                    picker.startDate.format('DD/MM/YYYY') +
                    ' - ' +
                    picker.endDate.format('DD/MM/YYYY')
                );
                reloadJobsWithFilter();
            });
            $('#jobDateRange').on('cancel.daterangepicker', function() {
                $(this).val('');
                selectedStartDate = null;
                selectedEndDate = null;
                reloadJobsWithFilter();
            });
        });
        function reloadJobsWithFilter() {
            loadWebsiteBookingsFinal();
            loadUnassignedJobsFinal();
            loadAssignedJobsFinal();
            loadCancelledJobsFinal();
            loadExpiredJobsFinal();
            loadCompletedJobsFinal();
        }
        function loadCalendarAssignedJobs(filterDriverId = null) {
            $('#calendarLoader').removeClass('d-none');
            calendar.removeAllEvents();
            let reqs = 0;
            const finalize = () => {
                reqs++;
                if (reqs >= 2) {
                    setTimeout(() => {
                        $('#calendarLoader').addClass('d-none');
                    }, 200);
                }
            };
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
                success: function(res) {
                    if (!res.result || !res.result.length) return;
                    res.result.forEach(job => {
                        if (job.job_status !== 'accept' && job.job_status !== 'accepted') return;
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
                complete: finalize
            });
            let scheduleData = filterDriverId ? {
                method: 'get_driver_schedule',
                driver_id: filterDriverId
            } : {
                method: 'get_all_driver_schedules'
            };
            $.ajax({
                url: "ajax/service/driverServices.php",
                type: "POST",
                dataType: "json",
                data: scheduleData,
                success: function(res) {
                    if (res.status && res.data && Array.isArray(res.data)) {
                        let driverName = filterDriverId ? ($('.driver-item.active strong').text() || 'Driver') : 'Driver';
                        res.data.forEach(item => {
                            if (!item.date) return;
                            const eventDate = item.date.replace(' ', 'T');
                            let eventTitle = filterDriverId ?
                                `${driverName} (${item.from} - ${item.to})` :
                                `${item.driver_name || driverName} (${item.from} - ${item.to})`;
                            calendar.addEvent({
                                title: eventTitle,
                                start: eventDate,
                                backgroundColor: '#0d6efd',
                                borderColor: '#0d6efd',
                                extendedProps: {
                                    driverId: item.driver_id || filterDriverId,
                                    isCustomSchedule: true
                                }
                            });
                        });
                    }
                },
                error: function() {},
                complete: finalize
            });
        }
        const calendar = new FullCalendar.Calendar(
            document.getElementById('driverCalendar'), {
                initialView: 'dayGridMonth',
                height: '100%',
                fixedWeekCount: false,
                dayMaxEvents: 2,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                eventTimeFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    meridiem: 'short'
                },
                dateClick: function(info) {
                    openDayScheduleModal(info.dateStr);
                },
                eventClick: function(info) {
                    let dateStr = info.event.startStr.split('T')[0];
                    openDayScheduleModal(dateStr);
                },
                eventDidMount(info) {
                    info.el.style.fontSize = '11px';
                    info.el.style.borderRadius = '4px';
                    info.el.style.border = 'none';
                    info.el.style.padding = '3px 5px';
                    info.el.style.cursor = 'pointer';
                    info.el.style.fontWeight = '500';
                }
            }
        );
        function openDayScheduleModal(dateStr) {
            let events = calendar.getEvents().filter(e => e.startStr.split('T')[0] === dateStr);
            let html = '';
            if (events.length === 0) {
                html = '<div class="alert alert-warning border-0 shadow-sm text-center py-4"><i class="fa fa-calendar-times fs-2 text-warning mb-2 d-block"></i> <span class="fw-semibold text-dark">No trips scheduled for this date.</span></div>';
            } else {
                events.forEach(ev => {
                    let timeStr = ev.start ? moment(ev.start).format('hh:mm A') : 'All Day';
                    let isCustom = ev.extendedProps.isCustomSchedule;
                    let typeBadge = isCustom ? '<span class=""></span>' : '<span class="">Assigned</span>';
                    html += `
                    <div class="card shadow-sm border-0 mb-2 rounded-3" style="border-left: 4px solid ${ev.backgroundColor || '#0d6efd'} !important;">
                        <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold mb-1" style="font-size:14px; color:#1f2937;">${ev.title}</div>
                                <div class="text-muted fw-semibold" style="font-size:12px;"><i class="fa fa-clock me-1 text-secondary"></i>${timeStr}</div>
                            </div>
                            <div>${typeBadge}</div>
                        </div>
                    </div>`;
                });
            }
            $('#dayScheduleDateText').text(moment(dateStr).format('DD MMMM YYYY'));
            $('#dayScheduleTargetDate').val(dateStr);
            $('#dayScheduleList').html(html);
            $('#dayScheduleModal').modal('show');
        }
        $(document).off('click', '#dayScheduleAddTripBtn').on('click', '#dayScheduleAddTripBtn', function() {
            $('#dayScheduleModal').modal('hide');
            let targetDate = $('#dayScheduleTargetDate').val();
            $('.edit-section-trigger[data-section="jobs"]').trigger('click');
            setTimeout(() => {
                $('#addScheduleRowBtn').trigger('click');
                let $newRow = $('.schedule-route-box').last();
                if ($newRow.length > 0 && targetDate) {
                    $newRow.find('.sch-datetime').val(targetDate + "T10:00");
                }
            }, 300);
        });
        $('[data-view]').on('click', function() {
            $('[data-view]').removeClass('active');
            $(this).addClass('active');
            const view = $(this).data('view');
            if (view === 'month') {
                calendar.changeView('dayGridMonth');
            } else {
                calendar.changeView('timeGridWeek');
            }
        });
        $('button[data-bs-target="#driversTab"]').on('shown.bs.tab', function() {
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
        $('button[data-view]').on('click', function() {
            $('button[data-view]').removeClass('active');
            $(this).addClass('active');
            const view = $(this).data('view');
            calendar.changeView(view === 'week' ? 'timeGridWeek' : 'dayGridMonth');
        });
        $(document).on('click', '.driver-item', function(e) {
            e.preventDefault();
            $('.driver-item').removeClass('active');
            $(this).addClass('active');
            selectedDriver = $(this).data('driver');
            refreshCalendar(selectedDriver);
        });
        $('#showAllDrivers').on('click', function() {
            selectedDriver = null;
            $('.driver-item').removeClass('active');
            refreshCalendar();
        });
        $('#driverSearch').on('keyup', function() {
            renderDriversList();
        });
        $('#profileFilterType').on('change', function() {
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
        rangeSlider.noUiSlider.on('update', function(values) {
            percentageMin = parseInt(values[0]);
            percentageMax = parseInt(values[1]);
            $('#rangeMin').text(percentageMin + '%');
            $('#rangeMax').text(percentageMax + '%');
        });
        rangeSlider.noUiSlider.on('change', function() {
            renderDriversList();
        });
        function getSectionData(section, driverId) {
            let data = {
                driver_id: driverId
            };
            switch (section) {
                case 'driver-profile':
                    let name = $('#edit_name').val();
                    let mobile = $('#edit_mobile').val();
                    let email = $('#edit_email').val();
                    let districts_id = $('#edit_districts_id').val();
                    let state = $('#edit_state').val();
                    if (!state || state.trim() === '') {
                        state = $('.display-field[data-field="state"]').text().trim();
                    }
                    let address = $('#edit_address').val();
                    let exp = $('#edit_experience').val();
                    let selectedLangs = window.editLanguagesTomSelect.getValue();
                    let langsString = '';
                    if (selectedLangs) {
                        langsString = selectedLangs.join(',');
                    }
                    console.log("Languages:", langsString);
                    console.log("Extracted Languages:", langsString);
                    if (!name) {
                        showNotification('Driver name is required', 'error');
                        return false;
                    }
                    if (!mobile || mobile.length < 10) {
                        showNotification('Valid mobile number is required', 'error');
                        return false;
                    }
                    if (!email) {
                        showNotification('Email is required', 'error');
                        return false;
                    }
                    if (!address) {
                        showNotification('Address is required', 'error');
                        return false;
                    }
                    if (!exp) {
                        showNotification('Driver experience is required', 'error');
                        return false;
                    }
                    data = {
                        driver_id: driverId,
                        method: 'update_driver_profile',
                        name: name,
                        email: email,
                        mobile: mobile,
                        districts_id: districts_id,
                        state: state || '',
                        address: address,
                        exp: exp,
                        languages: langsString
                    };
                    break;
                case 'remarks':
                    data = {
                        ...data,
                        method: 'update_driver_remarks',
                        remarks: $('#edit_remarks').val(),
                        review: $('#edit_review').val()
                    };
                    break;
                case 'fare':
                    let perKm = $('#edit_per_km').val().trim();
                    let extraKm = $('#edit_extra_km').val().trim();
                    let extraHour = $('#edit_extra_hour').val().trim();
                    let extraDay = $('#edit_extra_day').val().trim();
                    if (perKm !== '' && (perKm < 0 || perKm.length > 5)) {
                        toast1("Please enter a valid Per KM amount (Max 5 digits)", "error");
                        return false;
                    }
                    if (extraKm !== '' && (extraKm < 0 || extraKm.length > 5)) {
                        toast1("Please enter a valid Extra KM amount (Max 5 digits)", "error");
                        return false;
                    }
                    if (extraHour !== '' && (extraHour < 0 || extraHour.length > 5)) {
                        toast1("Please enter a valid Extra Hour amount (Max 5 digits)", "error");
                        return false;
                    }
                    if (extraDay !== '' && (extraDay < 0 || extraDay.length > 5)) {
                        toast1("Please enter a valid Extra Day amount (Max 5 digits)", "error");
                        return false;
                    }
                    data = {
                        ...data,
                        method: 'update_driver_fare',
                        per_km: perKm,
                        extra_per_km: extraKm,
                        per_hour: extraHour,
                        per_day: extraDay
                    };
                    break;
                case 'payment':
                    let upiId = $('#edit_upi').val().trim();
                    if (!upiId) {
                        toast1("UPI ID is required", "error");
                        return false;
                    }
                    if (/\s/.test(upiId)) {
                        toast1("Spaces are not allowed in UPI ID", "error");
                        return false;
                    }
                    if (upiId.length > 30) {
                        toast1("UPI ID cannot exceed 30 characters", "error");
                        return false;
                    }
                    data = {
                        ...data,
                        method: 'update_driver_payment',
                        upiID: upiId
                    };
                    break;
                case 'vehicle':
                    let vType = $('#edit_vehicle_type').val();
                    let vMaker = $('#edit_maker_model').val().trim();
                    let vFuel = $('#edit_fuel').val();
                    let vSeating = $('#edit_seating').val().trim();
                    let vLuggage = $('#edit_luggage').val().trim();
                    let vRC = $('#edit_rc').val();
                    let vInsurance = $('#edit_insurance').val();
                    if (!vType) {
                        toast1("Vehicle Type is required", "error");
                        return false;
                    }
                    if (!vMaker) {
                        toast1("Maker Model is required", "error");
                        return false;
                    }
                    if (vMaker.length > 75) {
                        toast1("Maker Model must be 25 characters or less", "error");
                        return false;
                    }
                    if (!vFuel) {
                        toast1("Fuel Type is required", "error");
                        return false;
                    }
                    if (!vSeating) {
                        toast1("Seating is required", "error");
                        return false;
                    }
                    if (!/^\d{1,3}$/.test(vSeating)) {
                        toast1("Seating must be numbers only (Max 3 digits)", "error");
                        return false;
                    }
                    if (!vLuggage) {
                        toast1("Luggage is required", "error");
                        return false;
                    }
                    if (!/^\d{1,3}$/.test(vLuggage)) {
                        toast1("Luggage must be numbers only (Max 3 digits)", "error");
                        return false;
                    }
                    // if (!vRC) {
                    //     toast1("RC Validity date is required", "error");
                    //     return false;
                    // }
                    // if (!vInsurance) {
                    //     toast1("Insurance Validity date is required", "error");
                    //     return false;
                    // }
                    let todayDate = new Date();
                    todayDate.setHours(0, 0, 0, 0);
                    let selRCDate = new Date(vRC);
                    if (selRCDate < todayDate) {
                        toast1("RC Validity cannot be a past date", "error");
                        return false;
                    }
                    if (vInsurance) {
                        let selInsDate = new Date(vInsurance);
                        if (selInsDate < todayDate) {
                            toast1("Insurance Validity cannot be a past date", "error");
                            return false;
                        }
                    }
                    data = {
                        driver_id: driverId,
                        method: 'update_driver_vehicle',
                        vehicle_type: vType,
                        maker_model: vMaker,
                        fuel: vFuel,
                        seating: vSeating,
                        luggage: vLuggage,
                        rc_expiry: vRC,
                        insurance_expiry: vInsurance,
                        front_view_image_url: $('#val_front_view_image_url').val(),
                        boot_image_url: $('#val_boot_image_url').val(),
                        extra_image_1_url: $('#val_extra_image_1_url').val(),
                        car_top_view_image_url: $('#val_car_top_view_image_url').val(),
                        interior_front_image_url: $('#val_interior_front_image_url').val(),
                        special_features_image_url: $('#val_special_features_image_url').val()
                    };
                    break;
            }
            return data;
        }
        function updateSectionDisplay(section) {
            switch (section) {
                case 'driver-profile':
                    $('.display-field[data-field="name"]').text($('#edit_name').val());
                    let newMobile = $('#edit_mobile').val();
                    $('.display-field[data-field="mobile"]').text(newMobile);
                    let cleanNewMobile = '';
                    if (newMobile) {
                        cleanNewMobile = String(newMobile).replace(/\D/g, '');
                        let waNewMobile = cleanNewMobile.length === 10 ? '91' + cleanNewMobile : cleanNewMobile;
                        $('#view-driver-profile .call-icon').attr('href', `tel:${cleanNewMobile}`);
                        $('#view-driver-profile .whatsapp-icon').attr('href', `https://wa.me/${waNewMobile}`);
                    }
                    $('.display-field[data-field="address"]').text($('#edit_address').val());
                    let selectedDistName = $('#edit_districts_id option:selected').text();
                    $('.display-field[data-field="city"]').text(selectedDistName !== 'Select District' ? selectedDistName : '-');
                    let updatedState = $('#edit_state').val();
                    if (!updatedState || updatedState.trim() === '') {
                        updatedState = $('.display-field[data-field="state"]').text().trim();
                    }
                    $('.display-field[data-field="state"]').text(updatedState);
                    $('.display-field[data-field="email"]').text($('#edit_email').val());
                    $('#display_experience').text($('#edit_experience').val() + " Years");
                    const langs = ($('#edit_languages').val() || []).join(' | ');
                    $('.display-field[data-field="languages"]').text(langs);
                    $('#preview_driver_name').text($('#edit_name').val() || 'N/A');
                    if (cleanNewMobile) {
                        $('#preview_driver_no').html(`<i class="fa fa-phone text-success me-1"></i> (${cleanNewMobile})`);
                        $('#preview_call_btn').attr('href', `tel:${cleanNewMobile}`);
                        let wa = cleanNewMobile.length === 10 ? '91' + cleanNewMobile : cleanNewMobile;
                        $('#preview_wa_btn').attr('href', `https://wa.me/${wa}`);
                    }
                    let locTxt = [$('#edit_address').val(), updatedState].filter(Boolean).join(', ');
                    $('#preview_location').html(`<i class="fa fa-map-marker-alt text-danger me-1"></i> ${locTxt || 'N/A'}`);
                    $('#preview_experience').html(`<i class="fa fa-briefcase text-info me-1"></i> ${$('#edit_experience').val() ? $('#edit_experience').val() + ' Years' : 'N/A'}`);
                    $('#preview_languages').html(`<i class="fa fa-language text-dark me-1"></i> <strong>${langs || 'N/A'}</strong>`);
                    break;
                case 'remarks':
                    $('#display_remarks').text($('#edit_remarks').val());
                    $('#display_review').text($('#edit_review').val());
                    break;
                case 'fare':
                    $('#display_per_km').text($('#edit_per_km').val());
                    $('#display_extra_km').text($('#edit_extra_km').val());
                    $('#display_extra_hour').text($('#edit_extra_hour').val());
                    $('#display_extra_day').text($('#edit_extra_day').val());
                    $('#preview_per_km').text($('#edit_per_km').val() ? `₹${$('#edit_per_km').val()}` : 'N/A');
                    $('#preview_extra_km').text($('#edit_extra_km').val() ? `₹${$('#edit_extra_km').val()}` : 'N/A');
                    $('#preview_extra_hour').text($('#edit_extra_hour').val() ? `₹${$('#edit_extra_hour').val()}` : 'N/A');
                    $('#preview_extra_day').text($('#edit_extra_day').val() ? `₹${$('#edit_extra_day').val()}` : 'N/A');
                    break;
                case 'payment':
                    $('#display_upi').text($('#edit_upi').val());
                    break;
                case 'vehicle':
                    let rcDateStr = $('#edit_rc').val();
                    let inDateStr = $('#edit_insurance').val();
                    let rcFormatted = rcDateStr ? formatDateSimple(rcDateStr) : 'N/A';
                    let inFormatted = inDateStr ? formatDateSimple(inDateStr) : 'N/A';
                    $('#display_vehicle_type').text($('#edit_vehicle_type').val());
                    $('#display_maker_model').text($('#edit_maker_model').val());
                    $('#display_fuel').text($('#edit_fuel').val());
                    $('#display_seating').text($('#edit_seating').val());
                    $('#display_luggage').text($('#edit_luggage').val());
                    $('#display_rc').text(rcFormatted);
                    $('#display_insurance').text(inFormatted);
                    $('#preview_vehicle_name').text($('#edit_vehicle_type').val() || 'N/A');
                    $('#preview_vehicle_model').text($('#edit_maker_model').val() || 'N/A');
                    $('#preview_fuel').text($('#edit_fuel').val() || 'N/A');
                    $('#preview_seats').text($('#edit_seating').val() || 'N/A');
                    $('#preview_luggage').text($('#edit_luggage').val() || 'N/A');
                    $('#preview_rc').text(rcFormatted);
                    $('#preview_in').text(inFormatted);
                    break;
            }
        }
        function createRouteBlock(from = '', to = '', dates = []) {
            const getSelectOptions = (selectedValue, placeholder) => {
                let options = `<option value="">${placeholder}</option>` + districtOptionsHTML;
                if (!selectedValue) return options;
                let searchStr = `value='${selectedValue}'`;
                if (options.indexOf(searchStr) !== -1) {
                    return options.replace(searchStr, `${searchStr} selected`);
                } else {
                    return `<option value="${selectedValue}" selected>${selectedValue}</option>` + options;
                }
            };
            let datesHtml = '';
            if (dates.length === 0) {
                datesHtml = createDateRow('', '');
            } else {
                dates.forEach(d => {
                    datesHtml += createDateRow(d.datetime, d.price);
                });
            }
            return `
    <div class="schedule-route-box border  p-2 mb-3 bg-white shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
            <strong class="text-primary"><i class="fa fa-route text-danger me-1"></i> Route Selection</strong>
            <button type="button" class="btn btn-sm btn-outline-danger remove-route-box py-0 px-2" title="Remove Entire Route"><i class="fa fa-trash"></i></button>
        </div>
        <div class="row g-2 mb-3">
            <div class="col-6">
                <label class="form-label mb-0 text-muted" style="font-size:11px;">From City</label>
                <select class="form-select form-select-sm sch-from">
                    ${getSelectOptions(from, 'Select From')}
                </select>
            </div>
            <div class="col-6">
                <label class="form-label mb-0 text-muted" style="font-size:11px;">To City</label>
                <select class="form-select form-select-sm sch-to">
                    ${getSelectOptions(to, 'Select To')}
                </select>
            </div>
        </div>
        <div class="dates-wrapper p-2 bg-light  border">
            <label class="form-label mb-2 fw-bold" style="font-size:12px;"><i class="fa fa-calendar-alt text-info me-1"></i>Dates & Prices</label>
            ${datesHtml}
        </div>
        <button type="button" class="btn btn-sm btn-primary mt-2 w-100 add-date-row-btn" style="border-style: dashed;">
            <i class="fa fa-plus me-1"></i> Add Another Date for this Route
        </button>
    </div>`;
        }
        function getCurrentDateTimeLocal() {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            return now.toISOString().slice(0, 16);
        }
        function createDateRow(datetime = '', price = '') {
            let minAttr = `min="${getCurrentDateTimeLocal()}"`;
            return `
    <div class="row g-2 mb-2 align-items-center schedule-date-row">
        <div class="col-6">
            <input type="datetime-local" 
                   class="form-control form-control-sm sch-datetime border-primary" 
                   value="${datetime}" 
                   ${minAttr}
                   style="cursor: pointer;"
                   onkeydown="return false;" 
                   onpaste="return false;"
                   onclick="this.showPicker ? this.showPicker() : null">
        </div>
        <div class="col-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-primary border-end-0 text-dark">₹</span>
                <input type="number" 
                       class="form-control border-primary border-start-0 px-1 sch-price" 
                       placeholder="Price" 
                       value="${price}" 
                       min="1" 
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)">
            </div>
        </div>
        <div class="col-2 text-end">
            <button type="button" class="btn btn-sm btn-danger remove-date-row py-1 px-2" title="Remove Date"><i class="fa fa-trash"></i></button>
        </div>
    </div>`;
        }
        $(document).on('change', '.sch-datetime', function() {
            let selected = $(this).val();
            let current = getCurrentDateTimeLocal();
            if (selected && selected < current) {
                toast1('Please select a future date and time.', 'error');
                $(this).val('');
                $(this).addClass('is-invalid border-danger');
            } else {
                $(this).removeClass('is-invalid border-danger');
            }
        });
        window.loadDriverSchedule = function(driverId) {
            $('#scheduledJobsList').html('<div class="text-center p-3 text-muted"><i class="fa fa-spinner fa-spin me-2"></i>Loading schedule...</div>');
            $('#dynamicScheduleWrapper').empty();
            $.ajax({
                url: "ajax/service/driverServices.php",
                type: "POST",
                dataType: "json",
                data: {
                    method: 'get_driver_schedule',
                    driver_id: driverId
                },
                success: function(res) {
                    let viewHtml = '';
                    let editHtml = '';
                    let jobCount = (res.status && res.data) ? res.data.length : 0;
                    let jobScore = Math.min(jobCount, 5);
                    let badgeClass = 'bg-success';
                    let textClass = 'text-white';
                    if (jobScore === 0) {
                        badgeClass = 'bg-danger';
                    } else if (jobScore < 5) {
                        badgeClass = 'bg-warning';
                        textClass = 'text-dark';
                    }
                    $('#status-jobs').html(`<span class="badge ${badgeClass} ${textClass} ms-2" style="font-size: 11px; padding: 3px 6px;">${jobScore}/5</span>`);
                    if (jobCount > 0) {
                        let groupedRoutes = {};
                        res.data.forEach(item => {
                            let safeDateStr = String(item.date).replace(' ', 'T');
                            let d = new Date(safeDateStr);
                            if (isNaN(d.getTime())) return;
                            let formattedDate = d.toLocaleString('en-GB', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: true
                            }).toUpperCase();
                            viewHtml += `
                        <div class="schedule-item p-1 border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fa fa-map-marker-alt text-success me-2"></i> 
                                <strong>${item.from}</strong> <i class="fa fa-arrow-right text-muted" style="font-size: 10px;"></i> <strong>${item.to}</strong>
                            </div>
                            <div class="text-end">
                                <span class="d-block fw-bold text-info">₹${item.price}</span>
                                <span class="text-muted small"><i class="fa fa-calendar-alt text-danger me-1"></i>${formattedDate}</span>
                            </div>
                        </div>
                    `;
                            let routeKey = `${item.from}_${item.to}`;
                            if (!groupedRoutes[routeKey]) {
                                groupedRoutes[routeKey] = {
                                    from: item.from,
                                    to: item.to,
                                    dates: []
                                };
                            }
                            let year = d.getFullYear();
                            let month = String(d.getMonth() + 1).padStart(2, '0');
                            let day = String(d.getDate()).padStart(2, '0');
                            let hours = String(d.getHours()).padStart(2, '0');
                            let minutes = String(d.getMinutes()).padStart(2, '0');
                            let inputValidDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
                            groupedRoutes[routeKey].dates.push({
                                datetime: inputValidDateTime,
                                price: item.price
                            });
                        });
                        Object.values(groupedRoutes).forEach(routeObj => {
                            editHtml += createRouteBlock(routeObj.from, routeObj.to, routeObj.dates);
                        });
                        $('#scheduledJobsList').html(viewHtml);
                        $('#dynamicScheduleWrapper').html(editHtml);
                    } else {
                        $('#scheduledJobsList').html('<div class="text-center p-3 text-muted">No upcoming scheduled jobs found.</div>');
                    }
                },
                error: function() {
                    $('#status-jobs').html('<span class="badge bg-danger text-white ms-2" style="font-size: 11px; padding: 3px 6px;">0/5</span>');
                    $('#scheduledJobsList').html('<div class="text-center p-3 text-danger">Failed to load schedules.</div>');
                }
            });
        }
        $(document).on('change', '.sch-from, .sch-to', function() {
            let $row = $(this).closest('.schedule-edit-row');
            let fromCity = $row.find('.sch-from').val();
            let toCity = $row.find('.sch-to').val();
            if (fromCity && toCity && fromCity === toCity) {
                showNotification("From City and To City cannot be the same.", "error");
                $(this).val("");
            }
        });
        $(document).on('click', '#addScheduleRowBtn', function() {
            $('#dynamicScheduleWrapper').append(createScheduleRow());
            let wrapper = document.getElementById('dynamicScheduleWrapper');
            wrapper.scrollTop = wrapper.scrollHeight;
        });
        $(document).on('click', '.remove-sch-row', function() {
            $(this).closest('.schedule-edit-row').remove();
        });
        $(document).off('click', '#addScheduleRowBtn').on('click', '#addScheduleRowBtn', function() {
            $('#dynamicScheduleWrapper').append(createRouteBlock());
            let wrapper = document.getElementById('dynamicScheduleWrapper');
            wrapper.scrollTop = wrapper.scrollHeight;
        });
        $(document).off('click', '.add-date-row-btn').on('click', '.add-date-row-btn', function() {
            $(this).siblings('.dates-wrapper').append(createDateRow());
        });
        $(document).off('click', '.remove-route-box').on('click', '.remove-route-box', function() {
            let $box = $(this).closest('.schedule-route-box');
            Swal.fire({
                title: 'Remove Route?',
                text: "Are you sure you want to remove this entire route and its dates?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa fa-trash"></i> Yes, remove it'
            }).then((result) => {
                if (result.isConfirmed) {
                    $box.remove();
                    toast1('Route removed successfully.', 'success');
                    autoSaveSchedulesSilently();
                }
            });
        });
        $(document).off('click', '.remove-date-row').on('click', '.remove-date-row', function() {
            let $row = $(this).closest('.schedule-date-row');
            let $wrapper = $(this).closest('.dates-wrapper');
            let $routeBox = $(this).closest('.schedule-route-box');
            Swal.fire({
                title: 'Remove Date?',
                text: "Are you sure you want to delete this scheduled date?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa fa-trash"></i> Yes, delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $row.remove();
                    if ($wrapper.find('.schedule-date-row').length === 0) {
                        $routeBox.remove();
                    }
                    toast1('Scheduled date removed.', 'success');
                    autoSaveSchedulesSilently();
                }
            });
        });
        $(document).on('change', '.sch-from, .sch-to', function() {
            let $box = $(this).closest('.schedule-route-box');
            let fromCity = $box.find('.sch-from').val();
            let toCity = $box.find('.sch-to').val();
            if (fromCity && toCity && fromCity === toCity) {
                showNotification("From City and To City cannot be the same.", "error");
                $(this).val("");
            }
        });
        $(document).on('click', '.save-section', function() {
            const $btn = $(this);
            const section = $btn.data('section');
            const driverId = window.currentDriverId;
            if ($btn.prop('disabled')) return;
            const requestData = getSectionData(section, driverId);
            if (!requestData) {
                return;
            }
            $.ajax({
                url: 'ajax/service/driverServices.php',
                type: 'POST',
                dataType: 'json',
                data: requestData,
                beforeSend: function() {
                    $btn.prop('disabled', true);
                    $btn.data('original-text', $btn.html());
                    $btn.html('<i class="fa fa-spinner fa-spin"></i> Saving...');
                },
                complete: function() {
                    $btn.prop('disabled', false);
                    $btn.html($btn.data('original-text'));
                },
                success: function(res) {
                    if (res.status) {
                        updateSectionDisplay(section);
                        showNotification('Updated successfully', 'success');
                        recalculateDriverScore(driverId);
                        toggleSection(section, false);
                    } else {
                        showNotification(res.message, 'danger');
                    }
                }
            });
        });
        $(document).off('click', '#addScheduleRowBtn').on('click', '#addScheduleRowBtn', function() {
            $('#dynamicScheduleWrapper').append(createRouteBlock());
            let wrapper = document.getElementById('dynamicScheduleWrapper');
            wrapper.scrollTop = wrapper.scrollHeight;
        });
        $(document).off('click', '.add-date-row-btn').on('click', '.add-date-row-btn', function() {
            $(this).siblings('.dates-wrapper').append(createDateRow());
        });
        function autoSaveSchedulesSilently() {
            let driverId = window.currentDriverId;
            let routes = [];
            $('.schedule-route-box').each(function() {
                let from = $(this).find('.sch-from').val();
                let to = $(this).find('.sch-to').val();
                if (from && to) {
                    let newRoute = {
                        from: from,
                        to: to,
                        dates: {}
                    };
                    let hasValidDate = false;
                    $(this).find('.schedule-date-row').each(function() {
                        let rawDate = $(this).find('.sch-datetime').val();
                        let rawPrice = $(this).find('.sch-price').val();
                        if (rawDate && rawPrice) {
                            let formattedDateTime = rawDate.replace('T', ' ') + ":00";
                            newRoute.dates[formattedDateTime] = parseInt(rawPrice, 10);
                            hasValidDate = true;
                        }
                    });
                    if (hasValidDate) {
                        routes.push(newRoute);
                    }
                }
            });
            $.ajax({
                url: "ajax/service/driverServices.php",
                type: "POST",
                dataType: "json",
                data: {
                    method: 'save_driver_schedule',
                    driver_id: driverId,
                    routes: JSON.stringify(routes)
                },
                success: function(res) {
                    if (res.status) {
                        if (typeof window.recalculateDriverScore === 'function') {
                            window.recalculateDriverScore(driverId);
                        }
                    }
                }
            });
        }
        $(document).off('click', '.remove-route-box').on('click', '.remove-route-box', function() {
            $(this).closest('.schedule-route-box').remove();
            showNotification('Job removed successfully.', 'info');
            autoSaveSchedulesSilently();
        });
        $(document).off('click', '.remove-date-row').on('click', '.remove-date-row', function() {
            let wrapper = $(this).closest('.dates-wrapper');
            $(this).closest('.schedule-date-row').remove();
            if (wrapper.find('.schedule-date-row').length === 0) {
                wrapper.closest('.schedule-route-box').remove();
            }
            showNotification('Scheduled date removed.', 'info');
            autoSaveSchedulesSilently();
        });
        $(document).on('change', '.sch-from, .sch-to', function() {
            let $box = $(this).closest('.schedule-route-box');
            let fromCity = $box.find('.sch-from').val();
            let toCity = $box.find('.sch-to').val();
            if (fromCity && toCity && fromCity === toCity) {
                showNotification("From City and To City cannot be the same.", "error");
                $(this).val("");
            }
        });
        $(document).off('click', '#saveScheduleBtn').on('click', '#saveScheduleBtn', function(e) {
            e.preventDefault();
            let driverId = window.currentDriverId;
            let routes = [];
            let isValid = true;
            let errorMessage = "Please fill all required fields.";
            let uniqueEntries = new Set();
            if ($('.schedule-route-box').length === 0) {
                toast1("Schedule is empty! Add a route or close the editor.", "error");
                return;
            }
            $('.schedule-route-box, .schedule-date-row').css('background-color', 'transparent');
            $('.schedule-route-box').each(function() {
                let from = $(this).find('.sch-from').val();
                let to = $(this).find('.sch-to').val();
                let hasData = false;
                $(this).find('.sch-datetime, .sch-price').each(function() {
                    if ($(this).val() !== '') hasData = true;
                });
                if (from || to || hasData) {
                    if (!from || !to) {
                        isValid = false;
                        $(this).css('background-color', '#ffe6e6');
                        errorMessage = "Please select both From and To cities for the route.";
                        return false;
                    }
                    let newRoute = {
                        from: from,
                        to: to,
                        dates: {}
                    };
                    let hasValidDate = false;
                    $(this).find('.schedule-date-row').each(function() {
                        let rawDate = $(this).find('.sch-datetime').val();
                        let rawPrice = $(this).find('.sch-price').val();
                        if (rawDate || rawPrice) {
                            let priceInt = parseInt(rawPrice, 10);
                            if (!rawDate || !rawPrice) {
                                isValid = false;
                                $(this).css('background-color', '#ffe6e6');
                                errorMessage = "Please provide both Date and Price.";
                                return false;
                            }
                            if (isNaN(priceInt) || priceInt <= 0) {
                                isValid = false;
                                $(this).css('background-color', '#ffe6e6');
                                errorMessage = "Price must be greater than 0.";
                                return false;
                            }
                            let dateOnly = rawDate.split('T')[0];
                            let uniqueKey = `${from}_${to}_${dateOnly}`;
                            if (uniqueEntries.has(uniqueKey)) {
                                isValid = false;
                                $(this).css('background-color', '#ffe6e6');
                                errorMessage = `Duplicate trip found: ${from} to ${to} on the same date (${dateOnly}).`;
                                return false;
                            }
                            uniqueEntries.add(uniqueKey);
                            let formattedDateTime = rawDate.replace('T', ' ') + ":00";
                            newRoute.dates[formattedDateTime] = priceInt;
                            hasValidDate = true;
                        }
                    });
                    if (hasValidDate) {
                        routes.push(newRoute);
                    } else if (!hasValidDate && isValid) {
                        isValid = false;
                        $(this).css('background-color', '#ffe6e6');
                        errorMessage = "Please add at least one valid Date and Price for the route.";
                        return false;
                    }
                }
            });
            if (!isValid) {
                showNotification(errorMessage, 'error');
                return;
            }
            if (routes.length === 0) {
                toast1("Schedule cannot be empty. Please add at least one valid route.", "error");
                return;
            }
            let $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            $.ajax({
                url: "ajax/service/driverServices.php",
                type: "POST",
                dataType: "json",
                data: {
                    method: 'save_driver_schedule',
                    driver_id: driverId,
                    routes: JSON.stringify(routes)
                },
                success: function(res) {
                    if (res.status) {
                        showNotification(res.message, 'success');
                        loadDriverSchedule(driverId);
                        toggleSection('jobs', false);
                        recalculateDriverScore(driverId);
                    } else {
                        showNotification(res.message, 'error');
                    }
                },
                error: function() {
                    showNotification('Server error while saving schedules', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i>Save');
                }
            });
        });
        function showUploadLoader(container) {
            $(container).css('position', 'relative');
            $(container).append(`
                <div class="upload-loading">
                    <i class="fa fa-spinner fa-spin"></i>
                </div>
            `);
        }
        function hideUploadLoader(container) {
            $(container).find('.upload-loading').remove();
        }
        function getS3PresignedUrl(file) {
            return $.ajax({
                url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-s3/presigned-url",
                type: "POST",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                data: {
                    file_name: file.name,
                    file_type: file.type
                }
            });
        }
        async function uploadFileToS3(uploadUrl, file) {
            const response = await fetch(uploadUrl, {
                method: "PUT",
                headers: {
                    "Content-Type": file.type
                },
                body: file
            });
            if (!response.ok) {
                const text = await response.text();
                console.error("S3 Upload Failed:", text);
                throw new Error("S3 upload failed");
            }
            return true;
        }
        async function uploadFileViaPresignedUrl(file) {
            try {
                const presignedRes = await getS3PresignedUrl(file);
                if (!presignedRes.status) {
                    throw new Error("Failed to get upload URL");
                }
                const uploadUrl = presignedRes.upload_url;
                const finalFileUrl = presignedRes.file_url;
                await uploadFileToS3(uploadUrl, file);
                return {
                    status: true,
                    file_url: finalFileUrl
                };
            } catch (error) {
                console.error("S3 Upload Error:", error);
                return {
                    status: false,
                    message: error.message || "Upload failed"
                };
            }
        }
        $(document).on('change', '#edit-aadhaarFront input[type="file"]', async function() {
            const file = this.files[0];
            if (!file) return;
            showUploadLoader('#edit-aadhaarFront');
            const result = await uploadFileViaPresignedUrl(file);
            hideUploadLoader('#edit-aadhaarFront');
            if (result.status) {
                aadhaarFrontUrl = result.file_url;
                $('#edit-aadhaarFront img').attr('src', aadhaarFrontUrl);
            } else {
                alert("Front upload failed: " + result.message);
            }
        });
        $(document).on('change', '#edit-aadhaarBack input[type="file"]', async function() {
            const file = this.files[0];
            if (!file) return;
            showUploadLoader('#edit-aadhaarBack');
            const result = await uploadFileViaPresignedUrl(file);
            hideUploadLoader('#edit-aadhaarBack');
            if (result.status) {
                aadhaarBackUrl = result.file_url;
                $('#edit-aadhaarBack img').attr('src', aadhaarBackUrl);
            } else {
                alert("Back upload failed: " + result.message);
            }
        });
        $(document).on('click', '.save-section-img[data-section="aadhaar"]', async function() {
            const $btn = $(this);
            const driverId = window.currentDriverId;
            if (!aadhaarFrontUrl || !aadhaarBackUrl) {
                toast1("Both Aadhaar Front and Back images are mandatory.", "error");
                return;
            }
            const originalHtml = $btn.html();
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Saving...');
            try {
                const response = await $.ajax({
                    url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-kyc/ocr",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                    },
                    data: {
                        user_id: driverId,
                        front_image: aadhaarFrontUrl,
                        back_image: aadhaarBackUrl,
                        type: "AADHAAR"
                    }
                });
                if (response.status) {
                    $('#aadhaarFront img').attr('src', aadhaarFrontUrl);
                    $('#aadhaarBack img').attr('src', aadhaarBackUrl);
                    showNotification('Aadhaar OCR Update Completed', 'success');
                    recalculateDriverScore(driverId);
                    toggleSection('aadhaar', false);
                    aadhaarFrontUrl = null;
                    aadhaarBackUrl = null;
                } else {
                    showNotification(response.message || "OCR failed", 'danger');
                }
            } catch (error) {
                if (error.status === 422) {
                    showNotification('Validation failed.', 'danger');
                } else {
                    showNotification('Server error occurred.', 'danger');
                }
            } finally {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
        $(document).on('change', '#edit-licenseFront input[type="file"]', async function() {
            const file = this.files[0];
            if (!file) return;
            showUploadLoader('#edit-licenseFront');
            const result = await uploadFileViaPresignedUrl(file);
            hideUploadLoader('#edit-licenseFront');
            if (result.status) {
                dlFrontUrl = result.file_url;
                $('#edit-licenseFront img').attr('src', dlFrontUrl);
            } else {
                showNotification(result.message || "Front upload failed", 'danger');
            }
        });
        $(document).on('change', '#edit-licenseBack input[type="file"]', async function() {
            const file = this.files[0];
            if (!file) return;
            showUploadLoader('#edit-licenseBack');
            const result = await uploadFileViaPresignedUrl(file);
            hideUploadLoader('#edit-licenseBack');
            if (result.status) {
                dlBackUrl = result.file_url;
                $('#edit-licenseBack img').attr('src', dlBackUrl);
            } else {
                showNotification(result.message || "Back upload failed", 'danger');
            }
        });
        $(document).on('click', '.save-section-img[data-section="license"]', async function() {
            const driverId = window.currentDriverId;
            const dlNo = $('#edit_license_no').val().trim();
            const dob = $('#edit_license_dob').val();
            const expiry = $('#edit_license_expiry').val();
            const getValidUrl = (selector) => {
                let src = $(selector).attr('src');
                if (src && !src.includes('assets/images/') && !src.includes('placeholder') && !src.includes('encrypted-tbn0')) {
                    return src;
                }
                return null;
            };
            const finalDlFront = dlFrontUrl || getValidUrl('#edit-licenseFront img');
            const finalDlBack = dlBackUrl || getValidUrl('#edit-licenseBack img');
            if (!dlNo || !dob || !expiry || !finalDlFront || !finalDlBack) {
                showNotification("DL No, DOB, Expiry, Front and Back images are mandatory", 'danger');
                return;
            }
            const formattedDob = dob.split('-').reverse().join('-');
            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
            try {
                const response = await $.ajax({
                    url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-kyc/ocr/dl",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                    },
                    data: {
                        user_id: driverId,
                        dl_no: dlNo,
                        dob: formattedDob,
                        expiry: expiry,
                        front_url: finalDlFront,
                        back_url: finalDlBack,
                        type: "DRIVING_LICENSE"
                    }
                });
                if (response.status) {
                    $('#display_license_no').text(dlNo);
                    $('#display_license_expiry').text(formatDateSimple(expiry));
                    $('#display_license_type').text($('#edit_license_type').val());
                    if (finalDlFront) $('#licenseFront img').attr('src', finalDlFront);
                    if (finalDlBack) $('#licenseBack img').attr('src', finalDlBack);
                    showNotification(response.message || "Driving License Updated", 'success');
                    toggleSection('license', false);
                    dlFrontUrl = null;
                    dlBackUrl = null;
                    recalculateDriverScore(driverId);
                } else {
                    showNotification(response.message || "OCR failed", 'danger');
                }
            } catch (error) {
                if (error.status === 422) {
                    let errorMsg = "Validation failed";
                    if (error.responseJSON && error.responseJSON.message) {
                        errorMsg = error.responseJSON.message;
                    }
                    showNotification(errorMsg, 'danger');
                } else {
                    showNotification("Server error occurred", 'danger');
                }
            } finally {
                btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i>Save');
            }
        });
        $(document).on('change', '#edit-rcDocumentFront input[type="file"]', async function() {
            const file = this.files[0];
            if (!file) return;
            showUploadLoader('#edit-rcDocumentFront');
            const result = await uploadFileViaPresignedUrl(file);
            hideUploadLoader('#edit-rcDocumentFront');
            if (result.status) {
                rcFrontUrl = result.file_url;
                $('#edit-rcDocumentFront img').attr('src', rcFrontUrl).show();
            } else {
                showNotification(result.message, 'danger');
            }
        });
        $(document).on('change', '#edit-rcDocumentBack input[type="file"]', async function() {
            const file = this.files[0];
            if (!file) return;
            showUploadLoader('#edit-rcDocumentBack');
            const result = await uploadFileViaPresignedUrl(file);
            hideUploadLoader('#edit-rcDocumentBack');
            if (result.status) {
                rcBackUrl = result.file_url;
                $('#edit-rcDocumentBack img').attr('src', rcBackUrl).show();
            } else {
                showNotification(result.message, 'danger');
            }
        });
        $(document).on('change', '#edit-pucDocument input[type="file"]', async function() {
            const file = this.files[0];
            if (!file) return;
            showUploadLoader('#edit-pucDocument');
            const result = await uploadFileViaPresignedUrl(file);
            hideUploadLoader('#edit-pucDocument');
            if (result.status) {
                pucUrl = result.file_url;
                $('#edit-pucDocument img').attr('src', pucUrl).show();
            } else {
                showNotification(result.message, 'danger');
            }
        });
        $(document).on('change', '#edit-insuranceDocument input[type="file"]', async function() {
            const file = this.files[0];
            if (!file) return;
            showUploadLoader('#edit-insuranceDocument');
            const result = await uploadFileViaPresignedUrl(file);
            hideUploadLoader('#edit-insuranceDocument');
            if (result.status) {
                insuranceUrl = result.file_url;
                $('#edit-insuranceDocument img').attr('src', insuranceUrl).show();
            } else {
                showNotification(result.message, 'danger');
            }
        });
        $(document).on('keydown paste', '#edit_rc_expiry, #edit_puc_expiry, #edit_insurance_expiry', function(e) {
            e.preventDefault();
            return false;
        });
        $(document).on('click', '.save-section-img[data-section="documents"]', async function() {
            const driverId = window.currentDriverId;
            const rcNumber = $('#edit_rc_number').val().trim();
            const rcExpiry = $('#edit_rc_expiry').val();
            const pucExpiry = $('#edit_puc_expiry').val();
            const insuranceExpiry = $('#edit_insurance_expiry').val();
            const getValidUrl = (selector) => {
                let src = $(selector).attr('src');
                if (!src || typeof src !== 'string') return "";
                let s = src.trim();
                if (s === '' || s === '0' || s === 'null' || s === 'N/A' || s === 'undefined') return "";
                if (s.includes('assets/images/') ||
                    s.includes('placeholder') ||
                    s.includes('encrypted-tbn0') ||
                    s.includes('data:image/svg')) {
                    return "";
                }
                return s;
            };
            const finalRcFront = getValidUrl('#edit-rcDocumentFront img');
            const finalRcBack = getValidUrl('#edit-rcDocumentBack img');
            const finalPuc = getValidUrl('#edit-pucDocument img');
            const finalInsurance = getValidUrl('#edit-insuranceDocument img');
            if (!rcNumber) {
                toast1("RC Number is required", "error");
                return;
            }
            if (!finalRcFront) {
                toast1("RC Front image is required", "error");
                return;
            }
            if (!finalRcBack) {
                toast1("RC Back image is required", "error");
                return;
            }
            if (!finalInsurance) {
                toast1("Insurance image is required", "error");
                return;
            }
            // if (!rcExpiry) {
            //     toast1("RC Expiry date is required", "error");
            //     return;
            // }
            // if (!insuranceExpiry) {
            //     toast1("Insurance Expiry date is required", "error");
            //     return;
            // }
            let todayDate = new Date();
            todayDate.setHours(0, 0, 0, 0);
            if (new Date(rcExpiry) < todayDate) {
                toast1("RC Expiry cannot be a past date", "error");
                return;
            }
            if (pucExpiry && new Date(pucExpiry) < todayDate) {
                toast1("PUC Expiry cannot be a past date", "error");
                return;
            }
            if (new Date(insuranceExpiry) < todayDate) {
                toast1("Insurance Expiry cannot be a past date", "error");
                return;
            }
            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Verifying...');
            try {
                const rcResponse = await $.ajax({
                    url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-kyc/rc",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                    },
                    data: {
                        user_id: driverId,
                        type: "RC",
                        front_url: finalRcFront,
                        back_url: finalRcBack,
                        expiry: rcExpiry,
                        rc_no: rcNumber
                    }
                });
                if (!rcResponse.status) {
                    showNotification(rcResponse.message || "RC verification failed", 'danger');
                    return;
                }
                await $.ajax({
                    url: 'ajax/service/driverServices.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        method: 'update_vehicle_documents',
                        driver_id: driverId,
                        rc_number: rcNumber || "",
                        rc_expiry: rcExpiry || "",
                        rc_front: finalRcFront || "",
                        rc_back: finalRcBack || "",
                        puc_image: finalPuc || "",
                        puc_expiry: pucExpiry || "",
                        insurance_image: finalInsurance || "",
                        insurance_expiry: insuranceExpiry || "",
                        rc_details_full: JSON.stringify(rcResponse.data || {})
                    }
                });
                $('#display_rc').text(formatDateSimple(rcExpiry));
                $('#display_insurance').text(formatDateSimple(insuranceExpiry));
                const NO_IMAGE_SVG = "data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100%25' height='100%25'%3E%3Crect width='100%25' height='100%25' fill='%23f8f9fa'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='12px' fill='%236c757d'%3ENo Image Available%3C/text%3E%3C/svg%3E";
                $('#rcDocumentFront img').attr('src', finalRcFront || NO_IMAGE_SVG);
                $('#rcDocumentBack img').attr('src', finalRcBack || NO_IMAGE_SVG);
                $('#pucDocument img').attr('src', finalPuc || NO_IMAGE_SVG);
                $('#insuranceDocument img').attr('src', finalInsurance || NO_IMAGE_SVG);
                showNotification("Vehicle Documents Updated Successfully", 'success');
                toggleSection('documents', false);
                rcFrontUrl = rcBackUrl = pucUrl = insuranceUrl = null;
                recalculateDriverScore(driverId);
            } catch (err) {
                showNotification("Server error occurred while saving.", 'danger');
                console.error(err);
            } finally {
                btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i>Save');
            }
        });
        $(document).on('click', '#verifyAadhaarDigilocker', async function() {
            const driverId = window.currentDriverId;
            const btn = $(this);
            btn.prop('disabled', true)
                .html('<i class="fa fa-spinner fa-spin"></i> Initiating...');
            try {
                const response = await $.ajax({
                    url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-kyc/request",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                    },
                    data: {
                        user_id: driverId
                    }
                });
                if (!response.status) {
                    showNotification(response.message || "Digilocker request failed", 'danger');
                    return;
                }
                const digioData = response.data;
                const requestId = digioData.id;
                const identifier = digioData.customer_identifier;
                const tokenId = digioData.access_token?.id;
                if (!requestId || !identifier || !tokenId) {
                    showNotification("Digilocker token missing", "danger");
                    return;
                }
                const options = {
                    environment: "production",
                    callback: function(response) {
                        if (response.hasOwnProperty("error_code")) {
                            showNotification("Digio verification failed", "danger");
                            console.log(response);
                            return;
                        }
                        showNotification("Aadhaar verified successfully via DigiLocker", "success");
                        toggleSection('aadhaar', false);
                        console.log("Digio Success:", response);
                    },
                    logo: "https://www.goride.run/goride/img/logo-light.png",
                    theme: {
                        primaryColor: "#0d6efd",
                        secondaryColor: "#000000"
                    }
                };
                const digio = new Digio(options);
                digio.init();
                digio.submit(requestId, identifier, tokenId);
            } catch (err) {
                showNotification("Failed to initiate DigiLocker verification", "danger");
            } finally {
                btn.prop('disabled', false)
                    .html('<i class="fa fa-link me-1"></i> Verify via DigiLocker');
            }
        });
    });
    $(document).ready(function() {
        $('#mapCard').removeClass('d-none');
        $('#leftPanel').addClass('d-none');
        $('#incomingBidsPanel').addClass('d-none');
        $('#ownerDetailsCard').addClass('d-none');
        $('#jobDetailsPanel').addClass('d-none');
    });
    $(document).off('click', '.jobEdit').on('click', '.jobEdit', function(e) {
        e.preventDefault();
        const encodedJob = $(this).data('fulljob');
        if (!encodedJob) {
            toast('error', 'Job data not found!');
            return;
        }
        const job = JSON.parse(decodeURIComponent(encodedJob));
        $('#mapCard, #leftPanel, #incomingBidsPanel, #ownerDetailsCard, #driverListPanel').addClass('d-none');
        $('#jobDetailsPanel').removeClass('d-none');
        $('#confirmBookingBtn').addClass('d-none');
        $('#updateJobBtn').removeClass('d-none').data('jobid', job.id).attr('data-jobid', job.id);
        let formattedPickup = "";
        if (job.pickup_date) {
            let dateStr = String(job.pickup_date).trim();
            if (/^\d{2}-\d{2}-\d{4}/.test(dateStr)) {
                let parts = dateStr.split(/[- :\T]/);
                let day = parts[0];
                let month = parts[1];
                let year = parts[2];
                let hours = parts[3] || "00";
                let minutes = parts[4] || "00";
                if (dateStr.toLowerCase().includes('pm') && parseInt(hours) < 12) hours = (parseInt(hours) + 12).toString();
                else if (dateStr.toLowerCase().includes('am') && parseInt(hours) === 12) hours = "00";
                formattedPickup = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}T${hours.padStart(2, '0')}:${minutes.padStart(2, '0')}`;
            } else {
                formattedPickup = dateStr.replace(' ', 'T').substring(0, 16);
            }
        }
        let isRound = job.job_type === 'roundtrip' || job.job_type === 'round_trip';
        let storedDays = job.day ? job.day : job.dropoff_date;
        let parsedDays = parseInt(storedDays, 10);
        let daysOptions = '<option value="">Select Days</option>';
        for (let i = 1; i <= 10; i++) {
            let isSelected = (parsedDays === i) ? 'selected' : '';
            daysOptions += `<option value="${i}" ${isSelected}>${i} Day${i > 1 ? 's' : ''}</option>`;
        }
        const isHidden = (val) => val === null || val === undefined || val == 0 || String(val).trim() === '' || val === 'Included';
        // STRICTLY ALLOWED CABS
        const allowedCabTypes = ['Go Mini', 'Go 4Seater', 'Go 6Seater', 'Go 7Seater'];
        let cabTypeOptions = '<option value="">Select Car</option>';
        let currentCarType = String(job.car_type || job.cab_type || '').toLowerCase().trim();
        let pCount = parseInt(job.pass_count) || 5;
        // Smart Mapping to force string into one of the 4 allowed options
        let mappedType = "";
        if (currentCarType.includes('mini')) mappedType = 'go mini';
        else if (currentCarType.includes('four') || currentCarType.includes('sedan') || currentCarType.includes('4seater')) mappedType = 'go 4seater';
        else if (currentCarType.includes('6')) mappedType = 'go 6seater';
        else if (currentCarType.includes('7')) mappedType = 'go 7seater';
        // SMART PASSENGER FALLBACK: If string mapping fails, decide via pass_count
        if (!mappedType) {
            if (pCount === 6 || pCount === 7) mappedType = 'go 6seater';
            else if (pCount >= 8) mappedType = 'go 7seater';
            else mappedType = 'go 4seater'; // Defaults 5 or below to 4Seater
        }
        allowedCabTypes.forEach(function(cabName) {
            let isSelected = (cabName.toLowerCase() === mappedType) ? 'selected' : '';
            cabTypeOptions += `<option value="${cabName}" ${isSelected}>${cabName}</option>`;
        });
        const initialToll = Math.ceil(parseFloat(job.toll_fare) || 0);
        $('#jobDetailsContent').html(`
        <div class="container-fluid pt-2">
            <div class="border p-3 mb-3">
                <h6 class="text-muted mb-3"><i class="fa fa-user me-2"></i>Customer Details</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Name *</label>
                        <input type="text" id="custName" class="form-control" value="${job.name || ''}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Mobile *</label>
                        <input type="text" id="custMobile" class="form-control bg-light" value="${job.mobile || ''}" readonly>
                    </div>
                </div>
            </div>
            <div class="border p-3 mb-3">
                <h6 class="text-muted mb-3"><i class="fa fa-briefcase me-2"></i>Job Details</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Job No</label>
                        <input type="text" class="form-control bg-light" id="editJobNo" value="${job.job_no}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Job Type *</label>
                        <select class="form-select" id="jobType">
                            <option value="one_way" ${!isRound ? 'selected' : ''}>One Way</option>
                            <option value="round_trip" ${isRound ? 'selected' : ''}>Round Trip</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Car Type *</label>
                        <select class="form-select" id="carType">
                            ${cabTypeOptions}
                        </select>
                    </div>
                    <div class="col-md-4 ${!isRound ? 'd-none' : ''}" id="returnDaysWrapper">
                        <label class="form-label text-muted small fw-bold">Return in Days *</label>
                        <select class="form-select" id="returnDays">
                            ${daysOptions}
                        </select>
                    </div>
                </div>
            </div>
            <div class="border p-3 mb-3">
                <h6 class="text-muted mb-3"><i class="fa fa-map-marker-alt me-2"></i>Route & Schedule</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">From *</label>
                        <input type="text" class="form-control bg-light" id="pickupLocation" value="${job.from_place || ''}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">To *</label>
                        <input type="text" class="form-control bg-light" id="dropLocation" value="${job.to_place || ''}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Pickup Date & Time *</label>
                        <input type="datetime-local" class="form-control bg-light" id="pickupDateTime" value="${formattedPickup}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Passengers *</label>
                        <input type="text" id="passengers" class="form-control bg-light" value="${pCount}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Luggage</label>
                        <input type="number" id="luggage" class="form-control" value="${job.luggage || 0}" readonly>
                    </div>
                </div>
            </div>
            <div class="border p-3 mb-3" id="fareSectionWrapper" data-original-toll="${initialToll}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-muted mb-0"><i class="fa fa-rupee-sign me-2"></i>Fare & Distance</h6>
                </div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Distance (kms) *</label>
                        <input type="text" id="distance" class="form-control bg-light" value="${job.distance || ''}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Duration</label>
                        <input type="text" id="editDuration" class="form-control bg-light" value="${job.duration || ''}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" style="color: #14b8a6; font-weight: 600;">Total Fare *</label>
                        <input type="text" id="editTotalFare" class="form-control fw-bold bg-light" style="color: #14b8a6;" value="${job.fare || ''}" readonly>
                    </div>
                    <div class="col-md-2 ${isHidden(job.base_fare) ? 'd-none' : ''}">
                        <label class="form-label text-muted small fw-bold">Base Fare *</label>
                        <input type="text" id="baseFare" class="form-control bg-light" value="${job.base_fare && job.base_fare !== 'Included' ? job.base_fare : ''}" readonly>
                    </div>
                    <div class="col-md-2 ${isHidden(job.tax) ? 'd-none' : ''}">
                        <label class="form-label text-muted small fw-bold">Tax (GST)</label>
                        <input type="text" id="taxFare" class="form-control bg-light" value="${job.tax || 0}" readonly>
                    </div>
                    <div class="col-md-2 ${isHidden(job.toll_fare) ? 'd-none' : ''}">
                        <label class="form-label text-muted small fw-bold">Toll Fare</label>
                        <input type="text" id="tollFare" class="form-control bg-light" value="${job.toll_fare && job.toll_fare !== 'Included' ? job.toll_fare : ''}" readonly>
                    </div>
                </div>
            </div>
        </div>
    `);
        if (typeof initLocationAutocomplete === 'function') {
            initLocationAutocomplete('#pickupLocation');
            initLocationAutocomplete('#dropLocation');
        }
    });
    window.fetchAndUpdateFares = function() {
        let pickupDateTime = $('#pickupDateTime').val();
        let jobType = $('#jobType').val();
        let carType = $('#carType').val();
        let currentDistance = parseFloat($('#distance').val()) || 0;
        let currentToll = Math.ceil(parseFloat($('#fareSectionWrapper').attr('data-original-toll')) || 0);
        if (!pickupDateTime || !jobType || !carType || currentDistance <= 0) return;
        jobType = jobType === 'one_way' ? 'oneway' : 'roundtrip';
        let dropoffDate = null;
        if (jobType === 'roundtrip') {
            const returnDays = $('#returnDays').val();
            if (!returnDays) return;
            dropoffDate = parseInt(returnDays);
        }
        pickupDateTime = pickupDateTime.replace('T', ' ') + ':00';
        let carTypeStr = String(carType).toLowerCase();
        let apiCarKey = 'four_seater';
        if (carTypeStr.includes('6') || carTypeStr.includes('7') || carTypeStr.includes('six') || carTypeStr.includes('seven') || carTypeStr.includes('suv')) {
            apiCarKey = 'seven_seater';
        }
        function setFieldsToNA() {
            $('#baseFare, #taxFare, #editTotalFare').val('N/A').parent().removeClass('d-none');
            $('#tollFare').val(currentToll).parent().removeClass('d-none');
        }
        $.ajax({
            url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-get-fare",
            type: "POST",
            dataType: "json",
            headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
            data: {
                distance: currentDistance,
                pickup_date: pickupDateTime,
                dropoff_date: dropoffDate,
                way_type: jobType
            },
            beforeSend: function() {
                $('#baseFare, #taxFare, #editTotalFare').val('...');
            },
            success: function(response) {
                if (response.status && response.data) {
                    let cab = response.data[apiCarKey];
                    if (!cab) {
                        const availableKeys = Object.keys(response.data);
                        if (availableKeys.length > 0) cab = response.data[availableKeys[0]];
                    }
                    if (cab && parseFloat(cab.fare) > 0) {
                        const base = Math.ceil(parseFloat(cab.fare) || 0);
                        let tax = parseFloat(cab.tax_fare);
                        if (isNaN(tax) || tax <= 0) {
                            tax = Math.ceil(base * 0.05);
                        } else {
                            tax = Math.ceil(tax);
                        }
                        const total = base + currentToll + tax;
                        $('#baseFare').val(base > 0 ? base : 'N/A').parent().removeClass('d-none');
                        $('#tollFare').val(currentToll).parent().removeClass('d-none');
                        $('#taxFare').val(tax > 0 ? tax : '0').parent().removeClass('d-none');
                        $('#editTotalFare').val(total > 0 ? total : 'N/A');
                    } else {
                        setFieldsToNA();
                        toast('warning', `Fare is not applicable for ${carType}.`);
                    }
                } else {
                    setFieldsToNA();
                    toast('warning', 'Fare calculation unavailable.');
                }
            },
            error: function() {
                setFieldsToNA();
                toast('error', 'Error fetching fare.');
            }
        });
    };
    $(document).off('change', '#carType, #jobType, #returnDays').on('change', '#carType, #jobType, #returnDays', function() {
        // DYNAMIC PASSENGER UPDATE BASED ON SELECTED CAB
        if ($(this).attr('id') === 'carType') {
            let carTypeStr = String($(this).val()).toLowerCase();
            let passCount = 5; // Default for Mini/4Seater
            if (carTypeStr.includes('6')) {
                passCount = 7;
            } else if (carTypeStr.includes('7')) {
                passCount = 8;
            }
            $('#passengers').val(passCount);
        }
        window.fetchAndUpdateFares();
    });
    $(document).off('click', '#updateJobBtn').on('click', '#updateJobBtn', function() {
        const $btn = $(this);
        $('.form-control, .form-select').removeClass('is-invalid border-danger');
        let hasError = false;
        function markErr(sel) {
            $(sel).addClass('is-invalid border-danger');
            hasError = true;
        }
        if (!$('#custName').val().trim()) markErr('#custName');
        if (!$('#custMobile').val().trim()) markErr('#custMobile');
        if (!$('#pickupLocation').val().trim()) markErr('#pickupLocation');
        if (!$('#dropLocation').val().trim()) markErr('#dropLocation');
        if (!$('#pickupDateTime').val().trim()) markErr('#pickupDateTime');
        let jobType = $('#jobType').val();
        let returnDays = $('#returnDays').val();
        if (jobType === 'round_trip' && !returnDays) {
            markErr('#returnDays');
            toast('error', 'Please select Return Days');
            return;
        }
        let editTotalFareVal = $('#editTotalFare').val();
        if (editTotalFareVal === 'N/A' || editTotalFareVal === '...' || editTotalFareVal === '' || parseFloat(editTotalFareVal) <= 0 || isNaN(parseFloat(editTotalFareVal))) {
            toast('error', 'Fare is not available. Job cannot be updated.');
            return;
        }
        const baseFare = Math.ceil(parseFloat($('#baseFare').val()) || 0);
        const tollFare = Math.ceil(parseFloat($('#tollFare').val()) || 0);
        const taxFare = Math.ceil(parseFloat($('#taxFare').val()) || 0);
        let totalFare = Math.ceil(parseFloat(editTotalFareVal) || 0);
        let distanceValue = parseFloat($('#distance').val()) || 0;
        if (hasError) {
            toast('error', 'Please fill the required fields.');
            return;
        }
        let targetJobId = $btn.attr('data-jobid') || $btn.data('jobid');
        const payload = {
            job_id: targetJobId,
            job_no: $('#editJobNo').val() || '',
            job_type: jobType,
            day: jobType === 'round_trip' ? returnDays : '',
            dropoff_date: jobType === 'round_trip' ? returnDays : '',
            c_name: $('#custName').val() || '',
            c_email: $('#custEmail').val() || '',
            c_mobile: $('#custMobile').val() || '',
            from_place: $('#pickupLocation').val() || '',
            to_place: $('#dropLocation').val() || '',
            pickup_date: $('#pickupDateTime').val().replace('T', ' ') + ':00',
            pass_count: $('#passengers').val() || 1,
            lugg_count: $('#luggage').val() || 0,
            cab_type: $('#carType').val() || '',
            distance: distanceValue,
            duration: $('#editDuration').val() || $('#duration').val() || '',
            base_fare: baseFare,
            toll_fare: tollFare,
            tax: taxFare,
            fare: totalFare,
            admin_id: "<?= $_SESSION['memid'] ?? 1 ?>"
        };
        $.ajax({
            url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-update-job",
            type: "POST",
            dataType: "json",
            headers: {
                "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
            },
            data: payload,
            beforeSend: function() {
                $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');
            },
            success: function(res) {
                if (res.status) {
                    toast('success', res.message || 'Job updated successfully!');
                    $('.backToMap').trigger('click');
                    setTimeout(() => {
                        $('#jobsTab .nav-tabs .nav-link.active').trigger('shown.bs.tab');
                    }, 300);
                } else {
                    toast('error', res.message || 'Failed to update job');
                }
            },
            error: function(xhr) {
                toast('error', xhr.responseJSON?.message || 'Server error during update.');
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> Update Job');
            }
        });
    });
    $(document).on('click', '.viewJobLogs', function(e) {
        e.preventDefault();
        const jobId = $(this).data('jobid');
        const jobNo = $(this).data('jobno');
        $('#logJobNo').text(jobNo);
        $('#jobLogsContent').empty();
        $('#jobLogsLoader').removeClass('d-none');
        $('#jobEditLogsModal').modal('show');
        $.ajax({
            url: "ajax/service/driverServices.php",
            type: "POST",
            dataType: "json",
            data: {
                method: 'fetch_job_logs',
                job_id: jobId
            },
            success: function(res) {
                $('#jobLogsLoader').addClass('d-none');
                if (res.status && res.data && res.data.length > 0) {
                    let html = '';
                    res.data.forEach(log => {
                        let detailsHtml = '<div class="table-responsive"><table class="table table-bordered mt-2" style="font-size: 13px;">';
                        detailsHtml += '<thead class="bg-light"><tr><th width="30%">FIELD</th><th width="35%">OLD VALUE</th><th width="35%">NEW VALUE</th></tr></thead><tbody>';
                        let details = log.edit_details;
                        for (const [field, changes] of Object.entries(details)) {
                            let friendlyField = field.replace('customer_', 'Customer ').replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                            let oldVal = changes.old || '-';
                            let newVal = changes.new || '-';
                            detailsHtml += `<tr>
                            <td class="fw-semibold text-dark">${friendlyField}</td>
                            <td class="text-danger">${oldVal}</td>
                            <td class="text-info fw-semibold">${newVal}</td>
                         </tr>`;
                        }
                        detailsHtml += '</tbody></table></div>';
                        html += `
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-info text-white px-3 py-1 rounded-pill"><i class="fa fa-user me-1"></i> ${log.editor_name || 'Administrator'}</span>
                            <span class="text-muted small"><i class="fa fa-clock me-1"></i> ${log.formatted_date}</span>
                        </div>
                        ${detailsHtml}
                    </div>`;
                    });
                    $('#jobLogsContent').html(html);
                } else {
                    $('#jobLogsContent').html('<p class="text-center text-muted py-4">No modification history found for this job.</p>');
                }
            },
            error: function() {
                $('#jobLogsLoader').addClass('d-none');
                $('#jobLogsContent').html('<div class="alert alert-danger text-center">Error fetching history logs.</div>');
            }
        });
    });
    $(document).off('input', '#baseFare, #tollFare, #taxFare').on('input', '#baseFare, #tollFare, #taxFare', function() {
        let base = parseInt($('#baseFare').val()) || 0;
        let toll = parseInt($('#tollFare').val()) || 0;
        let tax = parseInt($('#taxFare').val()) || 0;
        let total = base + toll + tax;
        $('#editTotalFare').val(total);
        $('#totalFare').text('₹' + total.toLocaleString('en-IN'));
    });
    $(document).on('input', '#baseFare, #tollFare', function() {
        let base = parseInt($('#baseFare').val()) || 0;
        let toll = parseInt($('#tollFare').val()) || 0;
        if (base > 0 || toll > 0) {
            $('#editTotalFare').val(base + toll);
        }
    });
    $(document).on('click', '.backToMap', function() {
        $('#leftPanel').addClass('d-none');
        $('#incomingBidsPanel, #ownerDetailsCard, #jobDetailsPanel')
            .addClass('d-none');
        $('#driverListPanel').addClass('d-none');
        $('#mapCard').removeClass('d-none');
    });
    let currentAction = null;
    let currentBidId = null;
    let cancelJobId = null;
    let cancelJobNo = null;
    let cancelJobType = null;
    let cancelJobUserId = null;
    let cancelJobStatus = null;
    $(document).on('click', '.dropdown-menu .dropdown-item', function(e) {
        e.preventDefault();
        let text = $(this).text().trim();
        text = text.replace(/\(.*?\)/g, '').trim();
        $('#jobFilterBtn span').text(text);
        $('.dropdown-menu .dropdown-item').removeClass('active');
        $(this).addClass('active');
    });
    let driverToRemove = null;
    let jobToRemoveDriverFrom = null;
    $(document).on('click', '.remove-driver', function(e) {
        e.preventDefault();
        e.stopPropagation();
        driverToRemove = $(this).data('driver');
        jobToRemoveDriverFrom = $(this).data('job');
        $('#removeDriverModal').modal('show');
    });
    $('#confirmRemoveDriverBtn').on('click', function() {
        if (driverToRemove && jobToRemoveDriverFrom) {
            console.log('Removing driver:', driverToRemove, 'from job:', jobToRemoveDriverFrom);
            if (assignments[jobToRemoveDriverFrom]) {
                delete assignments[jobToRemoveDriverFrom];
                delete jobStatuses[jobToRemoveDriverFrom];
                renderJobLists();
                refreshCalendar();
                showToast('Driver removed successfully!', 'success');
            }
        }
        $('#removeDriverModal').modal('hide');
        driverToRemove = null;
        jobToRemoveDriverFrom = null;
    });
    $(document).on('click', '#newCustomerBtn', function() {
        $('#jobFormWrapper').removeClass('d-none');
        clearJobForm();
        $('#pastJobsSlider').addClass('d-none');
        $('#customerSearchResults').addClass('d-none');
        $('#custName').focus();
    });
    $(document).on('keyup', '#customerSearch', function() {
        const searchTerm = $(this).val().trim();
        const results = $('#customerSearchResults');
        if (!searchTerm) {
            results.addClass('d-none');
            return;
        }
        const mockCustomers = [{
                id: 1,
                name: 'Ramesh Kumar',
                mobile: '9876543210',
                email: 'ramesh@gmail.com',
                pastJobs: 5
            },
            {
                id: 2,
                name: 'Suresh Patel',
                mobile: '9876543211',
                email: 'suresh@gmail.com',
                pastJobs: 3
            },
            {
                id: 3,
                name: 'Rajesh Sharma',
                mobile: '9876543212',
                email: 'rajesh@gmail.com',
                pastJobs: 7
            },
            {
                id: 4,
                name: 'Mahesh Reddy',
                mobile: '9876543213',
                email: 'mahesh@gmail.com',
                pastJobs: 2
            },
            {
                id: 5,
                name: 'Ganesh Iyer',
                mobile: '9876543214',
                email: 'ganesh@gmail.com',
                pastJobs: 4
            }
        ];
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
    $(document).on('click', '.selectCustomer', function(e) {
        e.preventDefault();
        const customerId = $(this).data('customer-id');
        const customerName = $(this).data('name');
        const customerEmail = $(this).data('email');
        const customerMobile = $(this).data('mobile');
        const pastJobsCount = $(this).data('past-jobs');
        $('#jobFormWrapper').removeClass('d-none');
        $('#custName').val(customerName);
        $('#custEmail').val(customerEmail);
        $('#custMobile').val(customerMobile);
        $('#customerSearchResults').addClass('d-none');
        loadPastJobs(customerId, customerName);
    });
    function loadPastJobs(customerId, customerName) {
        const mockPastJobs = [{
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
        $('#customerHistoryControls').removeClass('d-none');
    }
    $(document).on('click', '.copyPastJob', function() {
        const from = $(this).data('from');
        const to = $(this).data('to');
        const carType = $(this).data('car-type');
        const passengers = $(this).data('passengers');
        const luggage = $(this).data('luggage');
        const baseFare = $(this).data('base-fare');
        const toll = $(this).data('toll');
        $('#pickupLocation').val(from);
        $('#dropLocation').val(to);
        $('#carType').val(carType.toLowerCase());
        $('#passengers').val(passengers);
        $('#luggage').val(luggage);
        $('#baseFare').val(baseFare);
        $('#tollFare').val(toll);
        calculateTotal();
        showToast('Job details copied! Adjust as needed.');
        setTimeout(() => {
            $('#pastJobsSlider').addClass('d-none');
        }, 500);
    });
    $(document).on('click', '.closePastJobs', function() {
        $('#pastJobsSlider').addClass('d-none');
    });
    function calculateTotal() {
        const baseFare = parseFloat($('#baseFare').val()) || 0;
        const tollFare = parseFloat($('#tollFare').val()) || 0;
        const total = baseFare + tollFare;
        $('#totalFare').text('₹' + total.toLocaleString());
    }
    $(document).on('input', '#baseFare, #tollFare', calculateTotal);
    function clearJobForm() {
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
    function showToast(message, type = 'success') {
        const toastClass = type === 'success' ? 'bg-success' : 'bg-danger';
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
        $('body').append(toastHtml);
        setTimeout(() => {
            $('.toast').remove();
        }, 3000);
    }
    $(document).on('click', '#customerHistoryControls button', function() {
        const filter = $(this).data('filter');
        $('#pastJobsSlider').removeClass('d-none');
        $('#pastJobsList .past-job').each(function() {
            const status = $(this).data('status');
            if (filter === 'all' || status === filter) {
                $(this).slideDown(150);
            } else {
                $(this).slideUp(150);
            }
        });
    });
    $('#removeDriverNoBtn').on('click', function() {
        if (jobToRemoveDriverFrom) {
            delete assignments[jobToRemoveDriverFrom];
            delete jobStatuses[jobToRemoveDriverFrom];
            renderJobLists();
            refreshCalendar();
        }
        $('#removeDriverModal').modal('hide');
        driverToRemove = null;
        jobToRemoveDriverFrom = null;
    });
    $(document).on('click', '.assignDriverFromList', function() {
        const driverId = $(this).data('driver');
        const jobId = $(this).data('job');
        assignments[jobId] = driverId;
        jobStatuses[jobId] = 'assigned';
        renderJobLists();
        refreshCalendar();
        $('.backToMap').trigger('click');
        showToast('Driver assigned successfully!', 'success');
    });
    $(document).on('click', '.driver-from-list', function(e) {
        e.preventDefault();
        const driverId = $(this).data('driver');
        $('#mapCard').addClass('d-none');
        $('#leftPanel').removeClass('d-none');
        $('#driverListPanel').removeClass('d-none');
        $('#ownerDetailsCard').addClass('d-none');
        $('#driverDetailsCard strong span').text(
            drivers[driverId]?.name || 'Driver'
        );
    });
    function getJobStatusUI(status) {
        const map = {
            assigned: {
                text: 'Assigned',
                class: 'primary',
                icon: 'fa-user'
            },
            dispatched: {
                text: 'Dispatched',
                class: 'info',
                icon: 'fa-car'
            },
            reached: {
                text: 'Reached',
                class: 'warning',
                icon: 'fa-map-marker-alt'
            },
            onboard: {
                text: 'On Board',
                class: 'success',
                icon: 'fa-users'
            },
            completed: {
                text: 'Completed',
                class: 'secondary',
                icon: 'fa-check'
            },
            cancelled: {
                text: 'Cancelled',
                class: 'danger',
                icon: 'fa-times'
            }
        };
        return map[status] || map.assigned;
    }
    $(document).on('click', '.driver-request', function() {
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
    $(document).ready(function() {
        var today = new Date();
        var futureDate = new Date();
        futureDate.setDate(today.getDate() + 12);
        $('#edit_puc').val(futureDate.toISOString().split('T')[0]);
        $('.edit-mode').hide();
        $('.edit-section-trigger').on('click', function(e) {
            e.preventDefault();
            const section = $(this).data('section');
            $(`#view-${section}`).hide();
            $(`#edit-${section}`).show();
            $(this).hide();
        });
        $('.cancel-section').on('click', function(e) {
            e.preventDefault();
            const section = $(this).data('section');
            $(`#edit-${section}`).hide();
            $(`#view-${section}`).show();
            $(`.edit-section-trigger[data-section="${section}"]`).show();
        });
        $('#galleryUpload').on('change', function(e) {
            if (e.target.files.length > 0) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    $('#mainCarImage, #editMainCarImage').attr('src', event.target.result);
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
        $(document).on('click', '.viewable-image, .driver-photo, .vehicle-photo, .document-img, .gallery-main-image', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const $img = $(this).is('img') ? $(this) : $(this).find('img');
            const src = $img.attr('src');
            if (!src) {
                showNotification('No image to view', 'info');
                return;
            }
        });
        $('#notificationBtn').on('click', function() {
            showNotification('You have 3 new notifications', 'info');
        });
        $('#closeDrawerBtn').on('click', function() {
            const offcanvas = bootstrap.Offcanvas.getInstance('#driverEditDrawer');
            if (offcanvas) {
                offcanvas.hide();
            }
        });
        $('#driverQuickSearch').on('keyup', function(e) {
            if (e.key === 'Enter') {
                showNotification('Searching for: ' + $(this).val(), 'info');
            }
        });
    });
    const aadhaarViewer = new Viewer(
        document.getElementById('view-aadhaar'), {
            toolbar: true,
            navbar: false,
            fullscreen: true,
            zIndex: 9999
        }
    );
    const driverzoomViewer = new Viewer(
        document.getElementById('driverzoom'), {
            toolbar: true,
            navbar: false,
            fullscreen: true,
            zIndex: 9999
        }
    );
    const licsenseViewer = new Viewer(
        document.getElementById('view-license'), {
            toolbar: true,
            navbar: false,
            fullscreen: true,
            zIndex: 9999
        }
    );
    function refreshVehicleGallery() {
        let container = document.getElementById('hiddenGalleryImages');
        if (!container) return;
        if (window.vehicleViewer) {
            window.vehicleViewer.destroy();
        }
        window.vehicleViewer = new Viewer(container, {
            toolbar: true,
            navbar: true,
            fullscreen: true,
            zIndex: 9999,
            filter(image) {
                return image.src &&
                    !image.src.includes('placeholder') &&
                    !image.src.includes('data:image/svg+xml') &&
                    image.src !== window.location.href;
            }
        });
        $("#main_vehicle_preview").off("click").on("click", function() {
            if (window.vehicleViewer) {
                window.vehicleViewer.show();
            }
        });
    }
    function updateSimpleImages() {
        const urls = [
            $('#val_front_view_image_url').val(),
            $('#val_boot_image_url').val(),
            $('#val_extra_image_1_url').val(),
            $('#val_car_top_view_image_url').val(),
            $('#val_interior_front_image_url').val(),
            $('#val_special_features_image_url').val()
        ];
        $('#simpleVehicleImages img').each(function(index) {
            if (urls[index] && urls[index].trim() !== '' && !urls[index].includes('placeholder')) {
                $(this).attr('src', urls[index]);
            } else {
                $(this).attr('src', 'assets/images/placeholder.png');
            }
        });
    }
    $(document).on('click', '.vehicle-thumb', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var index = $(this).data('index');
        var urls = [
            $('#val_front_view_image_url').val(),
            $('#val_boot_image_url').val(),
            $('#val_extra_image_1_url').val(),
            $('#val_car_top_view_image_url').val(),
            $('#val_interior_front_image_url').val(),
            $('#val_special_features_image_url').val()
        ];
        if (!urls[index] || urls[index].trim() === '' || urls[index].includes('placeholder')) {
            toast('error', 'No image available for this view');
            return;
        }
        $('#gallery_boot_image').attr('src', urls[1] || '');
        $('#gallery_extra_image_1').attr('src', urls[2] || '');
        $('#gallery_car_top_view_image').attr('src', urls[3] || '');
        $('#gallery_interior_front_image').attr('src', urls[4] || '');
        $('#gallery_special_features_image').attr('src', urls[5] || '');
        $('#main_vehicle_preview').attr('src', urls[index]);
        if (window.vehicleViewer) {
            window.vehicleViewer.destroy();
            window.vehicleViewer = null;
        }
        setTimeout(function() {
            let container = document.getElementById('vehicle-gallery-container');
            if (container) {
                window.vehicleViewer = new Viewer(container, {
                    toolbar: true,
                    navbar: true,
                    fullscreen: true,
                    zIndex: 9999,
                    initialViewIndex: index,
                    filter: function(image) {
                        return image.src &&
                            image.src.indexOf('placeholder') === -1 &&
                            image.src !== window.location.href;
                    }
                });
                window.vehicleViewer.show();
            }
        }, 100);
    });
    $(document).ready(function() {
        refreshVehicleGallery();
    });
    const docViewer = new Viewer(
        document.getElementById('view-documents'), {
            toolbar: true,
            navbar: false,
            fullscreen: true,
            zIndex: 9999
        }
    );
    let uploadedImages = [];
    $("#photoUpload").on("change", function(e) {
        const files = e.target.files;
        uploadedImages = [];
        $("#hiddenGalleryImages").html("");
        $.each(files, function(index, file) {
            if (!file.type.startsWith("image/")) return;
            const reader = new FileReader();
            reader.onload = function(event) {
                uploadedImages.push(event.target.result);
                if (uploadedImages.length === 1) {
                    $("#mainGalleryImage").attr("src", event.target.result);
                }
                $("#hiddenGalleryImages").append(
                    `<img src="${event.target.result}">`
                );
                $("#galleryCount").text(uploadedImages.length + " Photos");
                initViewer();
            };
            reader.readAsDataURL(file);
        });
    });
    function initViewer() {
        if (window.galleryViewer) {
            window.galleryViewer.destroy();
        }
        window.galleryViewer = new Viewer(
            document.getElementById("hiddenGalleryImages"), {
                toolbar: true,
                navbar: true,
                fullscreen: true,
                zIndex: 9999,
                title: false
            }
        );
        $("#mainGalleryImage").off("click").on("click", function() {
            window.galleryViewer.show();
        });
    }
</script>
<script>
    function showNotification(message, type = 'info') {
        const toastId = 'toast_' + Date.now();
        const bgColor = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';
        const toast = `
            <div id="${toastId}" class="toast align-items-center text-white ${bgColor} border-0 position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 9999;">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        $('body').append(toast);
        const bsToast = new bootstrap.Toast($('#' + toastId));
        bsToast.show();
        setTimeout(function() {
            $('#' + toastId).remove();
        }, 3000);
    }
    function getMinDateTime() {
        const now = new Date();
        now.setHours(now.getHours() + 2);
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        return now.toISOString().slice(0, 16);
    }
    function showDateToast(message) {
        $('.date-validation-toast').remove();
        const toastHtml = `
            <div class="date-validation-toast" style="position: fixed; bottom: 20px; right: 20px; background-color: #dc3545; color: white; padding: 12px 20px; border-radius: 6px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 99999; font-family: inherit; transition: opacity 0.3s ease-in-out;">
                <i class="fa fa-clock me-2"></i> ${message}
            </div>
        `;
        $('body').append(toastHtml);
        setTimeout(() => {
            $('.date-validation-toast').css('opacity', '0');
            setTimeout(() => $('.date-validation-toast').remove(), 300);
        }, 3500);
    }
    $(document).on('click', '.createJobBtn, .jobEdit', function() {
        setTimeout(() => {
            $('#pickupDateTime').attr('min', getMinDateTime());
        }, 100);
    });
    setInterval(() => {
        if ($('#pickupDateTime').length) {
            $('#pickupDateTime').attr('min', getMinDateTime());
        }
    }, 60000);
    $(document).on('change', '#pickupDateTime', function() {
        const selectedTime = $(this).val();
        if (selectedTime && selectedTime < getMinDateTime()) {
            $(this).val("");
            showDateToast("Pickup time must be at least 2 hours from now.");
            $(this).addClass('is-invalid border-danger');
        } else {
            $(this).removeClass('is-invalid border-danger');
        }
    });
    function showDateToast(message) {
        $('.date-validation-toast').remove();
        const toastHtml = `
        <div class="date-validation-toast" style="position: fixed; top: 20px; right: 20px; background-color: #dc3545; color: white; padding: 12px 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 99999; font-family: inherit; font-size: 14px; transition: opacity 0.3s ease-in-out, transform 0.2s ease; opacity: 1; transform: translateY(0);">
            ${message}
        </div>
    `;
        $('body').append(toastHtml);
        setTimeout(() => {
            $('.date-validation-toast').css('opacity', '0');
            setTimeout(() => $('.date-validation-toast').remove(), 300);
        }, 3000);
    }
    $(document).on('click', '.createJobBtn, .jobEdit', function() {
        setTimeout(() => {
            $('#pickupDateTime').attr('min', getMinDateTime());
        }, 100);
    });
    setInterval(() => {
        if ($('#pickupDateTime').length) {
            $('#pickupDateTime').attr('min', getMinDateTime());
        }
    }, 60000);
    $(document).on('change', '#pickupDateTime', function() {
        const selectedTime = $(this).val();
        if (selectedTime && selectedTime < getMinDateTime()) {
            $(this).val("");
            showDateToast("Invalid selection! Please choose a future date and time.");
        }
    });
</script>
<script>
    function showValidationToast(message) {
        $('.custom-validation-toast').remove();
        const toastHtml = `
            <div class="custom-validation-toast" style="position: fixed; top: 20px; right: 20px; background-color: #dc3545; color: white; padding: 12px 20px; border-radius: 6px; box-shadow: 0 4px 6px rgba(0,0,0,0.2); z-index: 99999; font-family: inherit; font-size: 14px; transition: opacity 0.3s;">
                <i class="fa fa-exclamation-circle me-2"></i> ${message}
            </div>
        `;
        $('body').append(toastHtml);
        setTimeout(() => {
            $('.custom-validation-toast').fadeOut(300, function() {
                $(this).remove();
            });
        }, 2500);
    }
    $(document).on('keypress', '#custName', function(e) {
        const char = String.fromCharCode(e.which);
        if (!/^[a-zA-Z\s]+$/.test(char)) {
            e.preventDefault();
            showValidationToast('Name: Only letters and spaces allowed');
            return false;
        }
        if (this.value.length >= 50) {
            e.preventDefault();
            showValidationToast('Name: Maximum 50 characters allowed');
            return false;
        }
    });
    $(document).on('input', '#custName', function() {
        let val = this.value.replace(/[^a-zA-Z\s]/g, '');
        if (val.length > 50) val = val.substring(0, 50);
        if (this.value !== val) this.value = val;
    });
    $(document).on('keypress', '#custMobile', function(e) {
        if (e.which < 48 || e.which > 57) {
            e.preventDefault();
            showValidationToast('Mobile: Only numbers allowed');
            return false;
        }
        if (this.value.length >= 10) {
            e.preventDefault();
            showValidationToast('Mobile: Maximum 10 digits allowed');
            return false;
        }
    });
    $(document).on('input', '#custMobile', function() {
        let val = this.value.replace(/[^0-9]/g, '');
        if (val.length > 10) val = val.substring(0, 10);
        if (this.value !== val) this.value = val;
    });
    $(document).on('keypress', '#custEmail', function(e) {
        if (this.value.length >= 100) {
            e.preventDefault();
            showValidationToast('Email: Maximum 100 characters allowed');
            return false;
        }
    });
    $(document).on('input', '#custEmail', function() {
        if (this.value.length > 100) this.value = this.value.substring(0, 100);
    });
    $(document).on('blur', '#custEmail', function() {
        let val = this.value.trim();
        if (val.length > 0 && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
            showValidationToast('Please enter a valid email address');
        }
    });
    $(document).off('change', '#carType').on('change', '#carType', function() {
        const cabType = $(this).val();
        const passInput = $('#passengers');
        const luggInput = $('#luggage');
        if (!cabType) {
            passInput.val('');
            return;
        }
        $.ajax({
            url: "ajax/service/driverServices.php",
            type: "POST",
            dataType: "json",
            data: {
                method: 'get_cab_seat_capacity',
                cab_type: cabType
            },
            beforeSend: function() {
                passInput.val('...').addClass('border-warning');
            },
            success: function(res) {
                if (res.status && res.data && res.data.seat_capacity) {
                    let seats = parseInt(res.data.seat_capacity, 10);
                    passInput.val(seats).attr('data-max', seats);
                } else {
                    passInput.val(4).attr('data-max', 4);
                }
                setTimeout(() => {
                    passInput.removeClass('border-warning');
                    luggInput.removeClass('border-warning');
                }, 1000);
            },
            error: function() {
                console.error("Failed to fetch seat capacity.");
                passInput.val(4).attr('data-max', 4);
                setTimeout(() => passInput.removeClass('border-warning'), 1000);
            }
        });
    });
    $(document).off('input', '#passengers').on('input', '#passengers', function() {
        let val = this.value.replace(/[^0-9]/g, '');
        let maxPass = parseInt($(this).attr('data-max')) || 15;
        if (val !== '') {
            let num = parseInt(val, 10);
            if (num === 0) val = '';
            else if (num > maxPass) val = maxPass.toString();
            else val = num.toString();
        }
        if (this.value !== val) this.value = val;
    });
    $(document).on('click', '.confirmGrdBtn', function(e) {
        e.preventDefault();
        const jobId = $(this).data('jobid');
        const jobNo = $(this).data('jobno');
        const userId = $(this).data('userid');
        const jobType = $(this).data('jobtype');
        const $card = $(this).closest('.card');
        const $confirmBtn = $(this);
        Swal.fire({
            title: 'Job Action',
            text: `Do you want to confirm or cancel job ${jobNo}?`,
            icon: 'warning',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: '<i class="fa fa-check"></i> Confirm',
            denyButtonText: '<i class="fa fa-trash"></i> Cancel Job',
            cancelButtonText: 'Close',
            confirmButtonColor: '#198754',
            denyButtonColor: '#dc3545',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-confirm-grd-job",
                    type: "POST",
                    dataType: "json",
                    data: {
                        job_id: jobId,
                        job_no: jobNo
                    },
                    headers: {
                        "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Confirming...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(res) {
                        if (res.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Confirmed!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            $confirmBtn.replaceWith('<span class="badge bg-success" style="font-size: 10px;">Confirmed</span>');
                            reloadJobsWithFilter();
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Server error. Try again.', 'error');
                    }
                });
            } else if (result.isDenied) {
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
                        job_type: jobType
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Cancelling...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(res) {
                        Swal.close();
                        if (res.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cancelled!',
                                text: 'Job was cancelled successfully.',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                $card.fadeOut(300, function() {
                                    $(this).remove();
                                });
                            });
                        } else {
                            Swal.fire('Error', res.message || 'Cancel failed', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Server error. Try again.', 'error');
                    }
                });
            }
        });
    });
    function updateConfirmStatus(jobId, jobNo, status, onSuccessCallback = null) {
        $.ajax({
            url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-confirm-grd-job",
            type: "POST",
            dataType: "json",
            data: {
                job_id: jobId,
                job_no: jobNo,
                confirm_status: status
            },
            headers: {
                "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Updating...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(res) {
                if (res.status) {
                    if (onSuccessCallback) {
                        onSuccessCallback();
                    } else {
                        Swal.fire('Confirmed!', res.message, 'success').then(() => {
                            reloadJobsWithFilter();
                        });
                    }
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Server error occurred.', 'error');
            }
        });
    }
    function cancelJobApi(jobId, jobNo, userId, jobType) {
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
                job_type: jobType
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Deleting from Firebase...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(res) {
                if (res.status) {
                    Swal.fire('Cancelled!', 'Job was cancelled and removed from Firebase.', 'success').then(() => {
                        reloadJobsWithFilter();
                    });
                } else {
                    Swal.fire('Error', res.message || 'Cancel failed', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Server error during cancellation.', 'error');
            }
        });
    }
    function openSidebarRemarks() {
        let driver_name = $('#driver_name').text();
        let driver_id = $('#sidebar_remarks_icon').attr('data-driver-id');
        if (!driver_id || driver_id === 'undefined' || driver_id === '') {
            showNotification('Could not find Driver ID.', 'error');
            return;
        }
        openRemarksModal(driver_id, driver_name);
    }
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
    function openRemarksModal(user_id, user_name) {
        $('#rmk_user_id').val(user_id);
        $('#rmk_user_name').text(user_name);
        $('#new_remark_text').val('');
        $('#remarks_table_body').html('<tr><td colspan="4" class="text-center"><span class="spinner-border spinner-border-sm"></span> Loading...</td></tr>');
        $('#remarksModal').modal('show');
        fetchRemarks(user_id);
    }
    function fetchRemarks(user_id) {
        $.ajax({
            url: origin + "/ajax/service/datatable_services.php",
            method: "POST",
            dataType: "json",
            data: {
                method: 'get_user_remarks',
                user_id: user_id
            },
            success: function(response) {
                let tbody = '';
                if (response.type == 1 && response.data && response.data.length > 0) {
                    response.data.forEach((item, index) => {
                        tbody += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.contacted_person}</td>
                            <td>${item.remarks}</td>
                            <td>${item.created_at}</td>
                        </tr>
                    `;
                    });
                } else {
                    tbody = '<tr><td colspan="4" class="text-center text-muted">No remarks found.</td></tr>';
                }
                $('#remarks_table_body').html(tbody);
            },
            error: function() {
                $('#remarks_table_body').html('<tr><td colspan="4" class="text-center text-danger">Failed to load remarks.</td></tr>');
            }
        });
    }
    function saveRemark() {
        let user_id = $('#rmk_user_id').val();
        let memid = "<?= $_SESSION['memid'] ?? '1' ?>";
        let remarks = $('#new_remark_text').val();
        let btn = $('#btn_save_remark');
        if (remarks.trim() === '') {
            toast('error', 'Please enter a remark.');
            return;
        }
        btn.html('<span class="spinner-border spinner-border-sm"></span> Saving...').prop('disabled', true);
        $.ajax({
            url: window.location.origin + "/ajax/service/datatable_services.php",
            method: "POST",
            dataType: "json",
            data: {
                method: 'save_user_remark',
                user_id: user_id,
                memid: memid,
                remarks: remarks
            },
            success: function(response) {
                if (response.type == 1) {
                    toast('success', 'Remark added successfully!');
                    $('#new_remark_text').val('');
                    fetchRemarks(user_id);
                } else {
                    toast('error', response.message || 'Failed to add remark.');
                }
            },
            error: function() {
                toast('error', 'Something went wrong while saving.');
            },
            complete: function() {
                btn.html('Submit Remark').prop('disabled', false);
            }
        });
    }
    function loadEditLogs() {
        let driverId = window.currentDriverId;
        if (!driverId) {
            driverId = $('#preview_driver_id').val();
        }
        if (!driverId) {
            showNotification('Driver ID not found', 'error');
            return;
        }
        $('#edit_log_content').html('<p class="text-center text-muted"><i class="fa fa-spinner fa-spin"></i> Loading logs...</p>');
        $('#editLogModal').modal('show');
        $.ajax({
            url: "ajax/service/driverServices.php",
            type: 'POST',
            dataType: 'json',
            data: {
                method: 'fetch_edit_logs',
                user_id: driverId
            },
            success: function(response) {
                $('#edit_log_content').html(response.html);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                $('#edit_log_content').html('<p class="text-center text-danger">Failed to load logs. See console.</p>');
            }
        });
    }
    function toast1(message, type = 'error') {
        const bgColor = type === 'error' ? '#ff3333' : '#28a745';
        const toastHtml = `
        <div id="customToast1" style="position: fixed; top: 20px; right: 20px; z-index: 999999; 
             background: ${bgColor}; color: white; padding: 12px 20px; border-radius: 8px; 
             box-shadow: 0 4px 12px rgba(0,0,0,0.3); font-family: sans-serif; min-width: 250px;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" style="background:none; border:none; color:white; font-weight:bold; cursor:pointer; margin-left:15px;">✕</button>
            </div>
        </div>`;
        $('#customToast1').remove();
        $('body').append(toastHtml);
        setTimeout(() => {
            $('#customToast1').fadeOut(500, function() {
                $(this).remove();
            });
        }, 3000);
    }
    function setSectionScore(elementId, score, max) {
        let badgeClass = 'bg-success';
        let textClass = 'text-white';
        if (score === 0) {
            badgeClass = 'bg-danger';
        } else if (score < max) {
            badgeClass = 'bg-warning';
            textClass = 'text-dark';
        }
        $(elementId).html(`<span class="badge ${badgeClass} ${textClass} ms-2" style="font-size: 11px; padding: 3px 6px;">${score}/${max}</span>`);
    }
    function updateSectionIcons(d, vehicleData) {
        const isSet = (val) => {
            if (!val) return false;
            let str = String(val).trim();
            return str !== '' && str !== 'N/A' && str !== '-' && str !== 'null' && str !== '0' &&
                !str.includes('data:image/svg+xml') && !str.includes('assets/images/');
        };
        let profileScore = 0;
        if (isSet(d.state)) profileScore += 2;
        if (isSet(d.districts_id)) profileScore += 2;
        if (parseFloat(d.exp || 0) > 0) profileScore += 2;
        let hasLangs = isSet(vehicleData?.user_info?.language) || isSet(d.languages) || isSet(d.language);
        if (hasLangs) profileScore += 2;
        setSectionScore('#status-driver-profile', profileScore, 8);
        let aadhaarScore = 0;
        if ((isSet(d.aadhar_image_front) && isSet(d.aadhar_image_back)) ||
            (d.proof_type === 'AADHAR_DIGILOCKER' && d.proof_status === 'approved')) {
            aadhaarScore = 1;
        }
        setSectionScore('#status-aadhaar', aadhaarScore, 1);
        let licenseScore = 0;
        if (isSet(d.dl_expiry)) licenseScore = 3;
        setSectionScore('#status-license', licenseScore, 3);
        let vType = vehicleData?.rc_details?.response?.vehicle_details?.body_type || d.cab_type;
        let vMaker = vehicleData?.rc_details?.response?.vehicle_details?.maker_model || d.maker_model;
        let vFuel = vehicleData?.rc_details?.response?.vehicle_details?.fuel_type || vehicleData?.vehicle_questions?.fuel_type || d.fuel_type || d.fuel_types;
        let vSeats = parseFloat(vehicleData?.rc_details?.response?.vehicle_details?.seat_capacity || d.seat || d.seaters || 0);
        let vLuggage = vehicleData?.user_info?.luggage || vehicleData?.luggage || d.luggage || d.Luggage || '';
        let vehicleScore = 0;
        if (isSet(vMaker)) vehicleScore += 4;
        if (isSet(vType)) vehicleScore += 2;
        if (isSet(vFuel)) vehicleScore += 2;
        if (vSeats > 0) vehicleScore += 3;
        if (isSet(vLuggage)) vehicleScore += 3;
        setSectionScore('#status-vehicle', vehicleScore, 14);
        let docScore = 0;
        let rcExpiry = vehicleData?.rc_expiry_date || vehicleData?.rc_details?.response?.vehicle_details?.fit_up_to || d.rc_upto;
        let insExpiry = vehicleData?.insurance_details?.insurance_exp_date || vehicleData?.insurance_exp_date || d.insurance_upto;
        let pucExpiry = vehicleData?.puc_details?.puc_exp_date || vehicleData?.puc_exp_date || d.puc_upto;
        let rcFront = vehicleData?.rc_front_image_url;
        let rcBack = vehicleData?.rc_back_image_url;
        let insImage = vehicleData?.insurance_details?.insurance_image_url;
        let pucImage = vehicleData?.puc_details?.puc_image_url;
        if (isSet(rcExpiry) && isSet(rcFront) && isSet(rcBack)) {
            docScore += 3;
        }
        if (isSet(insExpiry) && isSet(insImage)) {
            docScore += 3;
        }
        if (isSet(pucExpiry) && isSet(pucImage)) {
            docScore += 3;
        }
        setSectionScore('#status-documents', docScore, 9);
        let fareScore = 0;
        if (parseFloat(d.per_km || 0) > 0) fareScore += 2;
        if (parseFloat(d.extra_per_km || d.extra_price_per_km || 0) > 0) fareScore += 3;
        if (parseFloat(d.per_hour || d.price_per_hour || 0) > 0) fareScore += 3;
        if (parseFloat(d.per_day || d.price_per_day || 0) > 0) fareScore += 2;
        setSectionScore('#status-fare', fareScore, 10);
        let paymentScore = 0;
        if (isSet(d.upiID) || isSet(d.upi_id)) paymentScore = 2;
        setSectionScore('#status-payment', paymentScore, 2);
        let remarksScore = 0;
        if (isSet(d.remarks)) remarksScore += 2;
        if (isSet(d.reviews)) remarksScore += 2;
        setSectionScore('#status-remarks', remarksScore, 4);
    }
    window.recalculateDriverScore = function(driverId) {
        $.ajax({
            url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-driver-profile-update",
            type: "POST",
            dataType: "json",
            headers: {
                "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
            },
            data: {
                driver_id: driverId
            },
            success: function() {
                $.ajax({
                    url: "ajax/service/driverServices.php",
                    type: "POST",
                    dataType: "json",
                    data: {
                        method: 'get_driver_details',
                        driver_id: driverId
                    },
                    success: function(res) {
                        if (res.status && res.data && res.data.driver) {
                            const d = res.data.driver;
                            let vehicleData = {};
                            try {
                                vehicleData = typeof d.vehicle_details === 'string' ? JSON.parse(d.vehicle_details) : (d.vehicle_details || {});
                            } catch (e) {
                                vehicleData = {};
                            }
                            let percent = parseInt(d.profile_percentage) || 0;
                            $('#profileScoreBadge').html(`<i class="fa fa-check-circle me-1"></i>${percent}% Complete`);
                            updateSectionIcons(d, vehicleData);
                            $('#preview_profile_percent_text').text(percent + '%');
                            const $progress = $('#preview_profile_progress');
                            $progress.css('width', percent + '%').attr('aria-valuenow', percent);
                            let $driverCircle = $(`.driver-item[data-driver="${driverId}"] .profile-circle`);
                            if ($driverCircle.length) {
                                $driverCircle.text(percent + '%');
                            }
                        }
                    }
                });
            }
        });
    }
</script>
<script>
    let districtOptionsHTML = '';
    <?php
    $dist_query = mysqli_query($con, "SELECT id, district_name FROM districts ORDER BY district_name ASC");
    if ($dist_query) {
        while ($dist = mysqli_fetch_assoc($dist_query)) {
            $id = htmlspecialchars($dist['id']);
            $city = htmlspecialchars($dist['district_name']);
            echo "districtOptionsHTML += `<option value='{$id}'>{$city}</option>`;\n";
        }
    }
    ?>
</script>
<script>
    $(document).ready(function() {
        $('.dropdown-menu a').click(function(e) {
            let href = $(this).attr('href');
            if (href && href !== '#' && !href.startsWith('javascript')) {
                window.location.href = href;
            }
        });
    });
</script>
<script>
    $(document).on('click', '.view-driver-location-tab', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const driverId = $(this).data('driver');
        const driverName = $(this).data('name');
        $('button[data-bs-target="#jobsTab"]').tab('show');
        $('.backToMap').trigger('click');
        pinDriverOnMap(driverId, driverName);
    });
    function pinDriverOnMap(driverId, driverName) {
        $.ajax({
            url: window.location.origin + "/ajax/service/driverServices.php",
            type: "POST",
            dataType: "json",
            data: {
                method: 'get_driver_current_location',
                driver_id: driverId
            },
            success: function(res) {
                if (res.status && res.data) {
                    const lat = parseFloat(res.data.lat);
                    const lng = parseFloat(res.data.lng);
                    const lastUpdated = res.data.updated_at || 'Just now';
                    if (lat && lng && driversMap && typeof driversMap.invalidateSize === 'function') {
                        setTimeout(() => {
                            driversMap.invalidateSize();
                            driversMap.flyTo([lat, lng], 16, {
                                animate: true,
                                duration: 1.5
                            });
                            if (tempDriverMarker) {
                                driversMap.removeLayer(tempDriverMarker);
                            }
                            const redPin = L.divIcon({
                                className: '',
                                html: `<div style="background-color: #dc3545; width: 18px; height: 18px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 8px rgba(0,0,0,0.6);"></div>`,
                                iconSize: [18, 18],
                                iconAnchor: [9, 9]
                            });
                            tempDriverMarker = L.marker([lat, lng], {
                                icon: redPin
                            }).addTo(driversMap);
                            showRichDriverPopup(tempDriverMarker, driverId, driverName, lastUpdated, res.data.mobile);
                        }, 100);
                    } else {
                        console.error("Map object is not correctly initialized.");
                    }
                } else {
                    toast('error', 'Location not found for this driver.');
                }
            },
            error: function() {
                toast('error', 'Failed to fetch driver location.');
            }
        });
    }
    $(document).ready(function() {
        function activateTabFromHash() {
            let hash = window.location.hash;
            if (hash) {
                const tabMap = {
                    '#unassigned': {
                        mainTab: '#jobsTab',
                        subTab: '#unassigned'
                    },
                    '#assigned': {
                        mainTab: '#jobsTab',
                        subTab: '#assigned'
                    },
                    '#websiteBookings': {
                        mainTab: '#jobsTab',
                        subTab: '#websiteBookings'
                    },
                    '#cancelledJobs': {
                        mainTab: '#cancelledJobs',
                        subTab: null
                    },
                    '#expiredJobs': {
                        mainTab: '#expiredJobs',
                        subTab: null
                    }
                };
                let target = tabMap[hash];
                if (target) {
                    $('button[data-bs-target="' + target.mainTab + '"]').trigger('click');
                    if (target.subTab) {
                        setTimeout(() => {
                            $('button[data-bs-target="' + target.subTab + '"]').trigger('click');
                        }, 100);
                    }
                }
            }
        }
        activateTabFromHash();
        $(window).on('hashchange', function() {
            activateTabFromHash();
        });
    });
</script>
<script>
    let historyStartDateTime = null;
    let historyEndDateTime = null;
    $(document).ready(function() {
        historyStartDateTime = null;
        historyEndDateTime = null;
        $('#historyDateRange').val('');
        if ($('#clearHistoryBtn').length === 0) {
            $('#historyDateRange').after('<span class="input-group-text bg-white border-0 text-danger cursor-pointer d-none" id="clearHistoryBtn" style="cursor: pointer;" title="Clear Filter and Reload Map"><i class="fa fa-times"></i></span>');
        }
        if ($.fn.daterangepicker) {
            $('#historyDateRange').daterangepicker({
                timePicker: true,
                timePicker24Hour: false,
                timePickerIncrement: 15,
                autoUpdateInput: false,
                alwaysShowCalendars: false,
                maxDate: moment(),
                locale: {
                    format: 'DD/MM/YY hh:mm A',
                    cancelLabel: 'Clear'
                },
                ranges: {
                    'Today': [moment().startOf('day'), moment()],
                    'Yesterday': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
                    'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days').startOf('day'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });
            $('#historyDateRange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YY hh:mm A') + ' - ' + picker.endDate.format('DD/MM/YY hh:mm A'));
                $('#clearHistoryBtn').removeClass('d-none');
                historyStartDateTime = picker.startDate.format('YYYY-MM-DD HH:mm:ss');
                historyEndDateTime = picker.endDate.format('YYYY-MM-DD HH:mm:ss');
                fetchDriverHistoryAutomatically();
            });
            $('#historyDateRange').on('cancel.daterangepicker', function(ev, picker) {
                resetHistoryMap();
            });
        }
        $(document).on('click', '#clearHistoryBtn', function(e) {
            e.stopPropagation();
            resetHistoryMap();
        });
        function resetHistoryMap() {
            $('#historyDateRange').val('');
            $('#clearHistoryBtn').addClass('d-none');
            historyStartDateTime = null;
            historyEndDateTime = null;
            clearMapOverlays(true);
            window.currentDriverId = null;
            $('#mapDriverSearch').val('');
            let $spinIcon = $('#historyControls .fa-spinner');
            if ($spinIcon.length) {
                $spinIcon.removeClass('fa-spinner fa-spin').addClass('fa-calendar-alt');
            }
            window.loadDriversOnMap();
        }
        $(document).on('click', '.global-driver-search-item, .view-driver-location-tab, .view-driver-location, .leaflet-marker-icon', function() {
            $('#historyControls').removeClass('d-none');
        });
        let mapCheckTimer = setInterval(() => {
            if (typeof driversMap !== 'undefined' && driversMap !== null) {
                driversMap.on('popupopen', function() {
                    $('#historyControls').removeClass('d-none');
                });
                clearInterval(mapCheckTimer);
            }
        }, 1000);
    });
    function fetchDriverHistoryAutomatically() {
        let driverId = window.currentDriverId || (tempDriverMarker ? tempDriverMarker.driver_user_id : null);
        if (!driverId) driverId = $('.driver-item.active').data('driver');
        if (!driverId) {
            let popupBtn = $('.leaflet-popup-content .driver-link');
            if (popupBtn.length) driverId = popupBtn.data('driver');
        }
        if (!driverId) {
            let searchInput = $('#mapDriverSearch').val();
            if (searchInput && searchInput.trim() !== '') {
                let sVal = searchInput.toLowerCase().trim();
                if (typeof driversAll !== 'undefined') {
                    let found = Object.values(driversAll).find(d =>
                        (d.name && d.name.toLowerCase().includes(sVal)) ||
                        (d.mobile && String(d.mobile).includes(sVal))
                    );
                    if (found) {
                        driverId = found.user_id || found.id;
                        window.currentDriverId = driverId;
                    }
                }
            }
        }
        let fallbackName = $('.leaflet-popup-content .driver-name-head').text() || $('#mapDriverSearch').val() || 'Unknown Driver';
        let $icon = $('#historyControls .fa-calendar-alt');
        if ($icon.length) {
            $icon.removeClass('fa-calendar-alt').addClass('fa-spinner fa-spin');
        }
        $.ajax({
            url: window.location.origin + "/ajax/service/driverServices.php",
            type: "POST",
            dataType: "json",
            data: {
                method: "fetch_driver_history",
                driver_id: driverId || '',
                start_datetime: historyStartDateTime,
                end_datetime: historyEndDateTime
            },
            success: function(res) {
                if (res.status && res.data && res.data.length > 0) {
                    if (res.type === 'all') {
                        plotAllDriversHistoryOnMap(res.data);
                        toast('success', `Loaded locations for ${res.data.length} drivers.`);
                    } else {
                        $.ajax({
                            url: window.location.origin + "/ajax/service/driverServices.php",
                            type: "POST",
                            dataType: "json",
                            data: {
                                method: 'get_driver_details',
                                driver_id: driverId
                            },
                            success: function(driverRes) {
                                let driverData = null;
                                if (driverRes.status && driverRes.data && driverRes.data.driver) {
                                    driverData = driverRes.data.driver;
                                } else {
                                    driverData = {
                                        name: fallbackName,
                                        user_id: driverId,
                                        mobile: ''
                                    };
                                }
                                plotDriverHistoryOnMap(res.data, driverData);
                                let msg = res.data.length > 4 ? `Loaded ${res.data.length} points (Showing 4 main stops).` : `Loaded ${res.data.length} location points.`;
                                toast('success', msg);
                            },
                            error: function() {
                                plotDriverHistoryOnMap(res.data, {
                                    name: fallbackName,
                                    user_id: driverId,
                                    mobile: ''
                                });
                            }
                        });
                    }
                } else {
                    toast('error', res.message || 'No Location Updated For this range or date.');
                    clearMapOverlays();
                    if (driverId) {
                        pinDriverOnMap(driverId, fallbackName);
                    } else {
                        window.loadDriversOnMap();
                    }
                }
            },
            error: function() {
                toast('error', 'Server error while fetching history.');
            },
            complete: function() {
                let $spinIcon = $('#historyControls .fa-spinner');
                if ($spinIcon.length) {
                    $spinIcon.removeClass('fa-spinner fa-spin').addClass('fa-calendar-alt');
                }
            }
        });
    }
    function plotAllDriversHistoryOnMap(locations) {
        if (typeof driversMap === 'undefined' || !driversMap) return;
        clearMapOverlays();
        if (markerClusterGroup) {
            markerClusterGroup.clearLayers();
            driverMarkers = {};
        }
        let bounds = L.latLngBounds();
        locations.forEach(loc => {
            const lat = parseFloat(loc.lat);
            const lng = parseFloat(loc.lng);
            if (!lat || !lng) return;
            bounds.extend([lat, lng]);
            const pulseIcon = L.divIcon({
                className: '',
                html: `<div class="pulse-marker"></div>`,
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });
            const marker = L.marker([lat, lng], {
                icon: pulseIcon
            });
            showRichDriverPopup(marker, loc.user_id, 'Driver ' + loc.user_id, loc.formatted_time);
            marker.driver_table_id = loc.user_id;
            marker.driver_user_id = loc.user_id;
            if (markerClusterGroup) {
                markerClusterGroup.addLayer(marker);
            } else {
                marker.addTo(driversMap);
            }
            driverMarkers[loc.user_id] = marker;
        });
        if (bounds.isValid()) {
            driversMap.fitBounds(bounds, {
                padding: [50, 50],
                maxZoom: 14
            });
        }
    }
    function plotDriverHistoryOnMap(locations, data) {
        if (typeof driversMap === 'undefined' || !driversMap) return;
        clearMapOverlays();
        let latLngs = [];
        historyMarkers = [];
        let sampled = [];
        if (locations.length > 0) {
            sampled.push(locations[0]);
            if (locations.length > 3) {
                sampled.push(locations[Math.floor(locations.length / 3)]);
                sampled.push(locations[Math.floor((2 * locations.length) / 3)]);
            }
            if (locations.length > 1) {
                sampled.push(locations[locations.length - 1]);
            }
        }
        let driverName = 'Unknown Driver';
        let driverIdToView = '';
        let fallbackMobile = '';
        let d = (data && data.fullData) ? data.fullData : data;
        if (d) {
            driverName = d.name || 'Unknown Driver';
            driverIdToView = d.user_id || d.id || '';
            fallbackMobile = d.mobile || '';
        }
        let usedCoords = [];
        sampled.forEach((loc, index) => {
            let lat = parseFloat(loc.lat);
            let lng = parseFloat(loc.lng);
            let overlapCount = 0;
            usedCoords.forEach(c => {
                if (Math.abs(c.lat - lat) < 0.00015 && Math.abs(c.lng - lng) < 0.00015) {
                    overlapCount++;
                }
            });
            if (overlapCount > 0) {
                lat += (0.0004 * overlapCount);
                lng += (0.0004 * overlapCount);
            }
            usedCoords.push({
                lat,
                lng
            });
            latLngs.push([lat, lng]);
            let pointNum = index + 1;
            let isStart = (index === 0);
            let isEnd = (index === sampled.length - 1 && sampled.length > 1);
            let bg = isEnd ? '#dc3545' : (isStart ? '#198754' : '#0d6efd');
            const numIcon = L.divIcon({
                className: '',
                html: `<div style="background-color: ${bg}; color: white; width: 24px; height: 24px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.4); display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:bold;">${pointNum}</div>`,
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });
            let marker = L.marker([lat, lng], {
                icon: numIcon,
                zIndexOffset: 1000 + index
            });
            showRichDriverPopup(marker, driverIdToView, driverName, loc.formatted_time, fallbackMobile);
            marker.addTo(driversMap);
            historyMarkers.push(marker);
            if (isEnd || sampled.length === 1) {
                setTimeout(() => marker.openPopup(), 500);
            }
        });
        if (latLngs.length > 0) {
            let bounds = L.latLngBounds(latLngs);
            driversMap.fitBounds(bounds, {
                padding: [50, 50],
                maxZoom: 14
            });
            $('#historyControls').removeClass('d-none');
        }
    }
    function plotDriverHistoryOnMap(locations, data) {
        if (typeof driversMap === 'undefined' || !driversMap) return;
        clearMapOverlays();
        let latLngs = [];
        historyMarkers = [];
        let sampled = [];
        if (locations.length > 0) {
            sampled.push(locations[0]);
            if (locations.length > 3) {
                sampled.push(locations[Math.floor(locations.length / 3)]);
                sampled.push(locations[Math.floor((2 * locations.length) / 3)]);
            }
            if (locations.length > 1) {
                sampled.push(locations[locations.length - 1]);
            }
        }
        let driverName = 'Unknown Driver';
        let contactHtml = '';
        let cleanMobile = '';
        let waMobile = '';
        let carModel = 'N/A';
        let carSeats = 'N/A';
        let carFuel = 'N/A';
        let driverIdToView = '';
        let d = (data && data.fullData) ? data.fullData : data;
        if (d) {
            driverName = d.name || 'Unknown Driver';
            driverIdToView = d.user_id || d.id || '';
            let mobile = d.mobile || '';
            cleanMobile = mobile ? String(mobile).replace(/\D/g, '') : '';
            waMobile = cleanMobile.length === 10 ? '91' + cleanMobile : cleanMobile;
            if (cleanMobile) {
                contactHtml = `
                    <a href="tel:${cleanMobile}" class="text-decoration-none rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="background:#e7f1ff; color:#0d6efd; width:28px; height:28px;"><i class="fa fa-phone" style="font-size:12px;"></i></a>
                    <a href="https://wa.me/${waMobile}" target="_blank" class="text-decoration-none rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="background:#e9f7ef; color:#25d366; width:28px; height:28px;"><i class="fab fa-whatsapp" style="font-size:14px;"></i></a>
                `;
            }
            carModel = d.maker_model || 'N/A';
            carFuel = d.fuel_type || 'N/A';
            carSeats = d.seat ? (parseInt(d.seat) > 1 ? (parseInt(d.seat) - 1) + "+1" : d.seat) : 'N/A';
            if (d.vehicle_details) {
                try {
                    let vd = typeof d.vehicle_details === 'string' ? JSON.parse(d.vehicle_details) : d.vehicle_details;
                    carModel = vd?.rc_details?.response?.vehicle_details?.maker_model || carModel;
                    carFuel = vd?.rc_details?.response?.vehicle_details?.fuel_type || carFuel;
                    let sc = vd?.rc_details?.response?.vehicle_details?.seat_capacity;
                    if (sc) carSeats = parseInt(sc) > 1 ? (parseInt(sc) - 1) + "+1" : sc;
                } catch (e) {
                    console.error("Vehicle Parse Error", e);
                }
            }
        }
        let usedCoords = [];
        sampled.forEach((loc, index) => {
            let lat = parseFloat(loc.lat);
            let lng = parseFloat(loc.lng);
            let overlapCount = 0;
            usedCoords.forEach(c => {
                if (Math.abs(c.lat - lat) < 0.00015 && Math.abs(c.lng - lng) < 0.00015) {
                    overlapCount++;
                }
            });
            if (overlapCount > 0) {
                lat += (0.0004 * overlapCount);
                lng += (0.0004 * overlapCount);
            }
            usedCoords.push({
                lat,
                lng
            });
            latLngs.push([lat, lng]);
            let pointNum = index + 1;
            let isStart = (index === 0);
            let isEnd = (index === sampled.length - 1 && sampled.length > 1);
            let bg = isEnd ? '#dc3545' : (isStart ? '#198754' : '#0d6efd');
            const numIcon = L.divIcon({
                className: '',
                html: `<div style="background-color: ${bg}; color: white; width: 24px; height: 24px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.4); display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:bold;">${pointNum}</div>`,
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });
            let marker = L.marker([lat, lng], {
                icon: numIcon,
                zIndexOffset: 1000 + index
            });
            let popupHtml = `
                <div style="min-width: 210px; font-family: sans-serif; padding: 2px;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong style="font-size: 15px; color: #333;">${driverName}</strong>
                        <div class="d-flex gap-2">
                            ${contactHtml}
                        </div>
                    </div>
                    <div class="bg-light p-2 rounded mb-3" style="font-size: 12px; border: 1px solid #f0f0f0;">
                        <div class="text-dark mb-1"><i class="fa fa-car text-danger me-2"></i><strong>${carModel}</strong></div>
                        <div class="d-flex gap-3 text-muted mt-1">
                            <span><i class="fa fa-users text-info me-1"></i>${carSeats}</span>
                            <span><i class="fa fa-gas-pump text-warning me-1"></i>${carFuel}</span>
                        </div>
                    </div>
                    <div class="text-center mb-3 text-muted fw-semibold" style="font-size: 12px;">
                        <i class="fa fa-clock text-secondary me-1"></i> ${loc.formatted_time}
                    </div>
                    <a href="javascript:void(0)" class="btn w-100 py-2 driver-link" data-driver="${driverIdToView}" style="font-size:13px; font-weight: 600; background: #6c757d; color: white; border: none; border-radius: 4px;">View Profile</a>
                </div>
            `;
            marker.bindPopup(popupHtml);
            marker.addTo(driversMap);
            historyMarkers.push(marker);
            if (isEnd || sampled.length === 1) {
                setTimeout(() => marker.openPopup(), 500);
            }
        });
        if (latLngs.length > 0) {
            let bounds = L.latLngBounds(latLngs);
            driversMap.fitBounds(bounds, {
                padding: [50, 50],
                maxZoom: 14
            });
            $('#historyControls').removeClass('d-none');
        }
    }
    $(document).on('click', '.send-push-icon', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const userId = $(this).data('userid');
        const userName = $(this).data('name');
        if (!userId) {
            toast1('User ID is missing.', 'error');
            return;
        }
        $('#push_user_id').val(userId);
        $('#push_user_name').val(userName || 'Unknown User');
        $('#push_title').val('');
        $('#push_body').val('');
        $('#pushNotificationModal').modal('show');
    });
    $(document).on('click', '#sendPushBtn', function() {
        const userId = $('#push_user_id').val();
        const title = $('#push_title').val().trim();
        const body = $('#push_body').val().trim();
        const $btn = $(this);
        if (!title) {
            toast1('Please enter a title', 'error');
            return;
        }
        if (!body) {
            toast1('Please enter a message', 'error');
            return;
        }
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Sending...');
        $.ajax({
            url: "<?= rtrim(TEST_API_DOMAIN_2, '/') ?>/admin-send-specific-push-job",
            type: "POST",
            dataType: "json",
            headers: {
                "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
            },
            data: {
                "user_ids": userId.includes(',') ? userId.split(',') : [userId],
                "title": title,
                "body": body,
                "admin_id": "<?= $_SESSION['memid'] ?? 1 ?>"
            },
            success: function(res) {
                if (res.status || res.success) {
                    toast('success', res.message || 'Notification sent successfully');
                    $('#pushNotificationModal').modal('hide');
                } else {
                    toast('error', res.message || 'Failed to send notification');
                }
            },
            error: function(xhr) {
                let msg = 'Server error. Try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                toast('error', msg);
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fa fa-paper-plane"></i> Send');
            }
        });
    });
    let cancelDocViewer = null;
    $(document).on('click', '.view-cancel-reason-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const reason = $(this).attr('data-reason') || 'No reason provided.';
        const docUrl = $(this).attr('data-doc') || '';
        const cancelledBy = $(this).attr('data-cancelled-by') || 'User';
        const cancelledAt = $(this).attr('data-cancelled-at') || ''; // 🔥 GRAB TIMESTAMP
        $('#cancelReasonText').text(reason);
        $('#cancelledByBadge').text('Cancelled by: ' + cancelledBy);
        $('#cancelledTimeText').text(cancelledAt); // 🔥 SET TIMESTAMP
        const $img = $('#cancelReasonDoc');
        const $noDoc = $('#cancelReasonNoDoc');
        $img.off('error.cancel');
        if (docUrl && docUrl.trim() !== '' && docUrl !== 'null' && docUrl !== 'undefined') {
            $noDoc.addClass('d-none');
            $img.attr('src', docUrl).removeClass('d-none');
            $img.on('error.cancel', function() {
                $(this).off('error.cancel');
                $(this).attr('src', NO_DOC_SVG);
                if (cancelDocViewer) {
                    cancelDocViewer.destroy();
                    cancelDocViewer = null;
                }
            });
            if (cancelDocViewer) {
                cancelDocViewer.destroy();
            }
            cancelDocViewer = new Viewer(document.getElementById('cancelReasonDoc'), {
                toolbar: true,
                navbar: false,
                fullscreen: true,
                zIndex: 99999
            });
        } else {
            $img.addClass('d-none').attr('src', '');
            $noDoc.removeClass('d-none');
            if (cancelDocViewer) {
                cancelDocViewer.destroy();
                cancelDocViewer = null;
            }
        }
        $('#cancelReasonModal').modal('show');
    });
    $(document).on('click', '.view-feedback-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const rating = parseInt($(this).data('rating')) || 0;
        const review = $(this).data('review') || 'No review provided.';
        let starsHtml = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                starsHtml += '<i class="fa fa-star text-warning me-1"></i>';
            } else {
                starsHtml += '<i class="far fa-star text-warning me-1"></i>';
            }
        }
        $('#feedbackStars').html(starsHtml);
        $('#feedbackReviewText').text(review);
        $('#customerFeedbackModal').modal('show');
    });
    $(document).ready(function() {
        function autoUpdateEditSeating() {
            var cabType = $('#edit_vehicle_type').val();
            var seatInput = $('#edit_seating');
            if (seatInput.length === 0) return;
            if (!cabType) {
                seatInput.val('');
                seatInput.removeClass('is-invalid');
                return;
            }
            $.ajax({
                url: "ajax/service/driverServices.php",
                type: "POST",
                dataType: "json",
                data: {
                    method: 'get_cab_seat_capacity',
                    cab_type: cabType
                },
                beforeSend: function() {
                    seatInput.val('...');
                },
                success: function(res) {
                    if (res.status && res.data && res.data.seat_capacity) {
                        seatInput.val(res.data.seat_capacity);
                    } else {
                        seatInput.val('');
                    }
                    seatInput.removeClass('is-invalid');
                },
                error: function() {
                    console.error("Failed to fetch seat capacity.");
                    seatInput.val('');
                }
            });
        }
        if (!window.editVehicleTypeTomSelect && $('#edit_vehicle_type').length) {
            window.editVehicleTypeTomSelect = new TomSelect("#edit_vehicle_type", {
                create: false,
                placeholder: "Select Vehicle Type",
                onChange: function(value) {
                    autoUpdateEditSeating();
                }
            });
        }
        $('#edit_vehicle_type').on('change', autoUpdateEditSeating);
    });
    $(document).ready(function() {
        $(document).on('click', '.viewScheduledRequestsBtn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const requests = $(this).data('requests');
            let html = '';
            if (requests && requests.length > 0) {
                requests.forEach(req => {
                    let badgeClass = 'bg-secondary';
                    let statusText = req.status ? req.status.toLowerCase() : 'pending';
                    if (statusText === 'accept' || statusText === 'available') badgeClass = 'bg-success';
                    else if (statusText === 'busy' || statusText === 'reject') badgeClass = 'bg-danger';
                    else if (statusText === 'pending') badgeClass = 'bg-warning text-dark';
                    html += `
            <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                <div>
                    <div class="fw-bold text-dark mb-0" style="font-size: 14px;">
                        <i class="fa fa-user-circle text-muted me-2"></i>${req.driver_name}
                    </div>
                    <div class="mb-1">
                        <a href="tel:${req.driver_mobile}" class="text-primary text-decoration-none small fw-semibold">
                            <i class="fa fa-phone me-1"></i>${req.driver_mobile || 'No Mobile'}
                        </a>
                    </div>
                    <div class="small text-muted" style="font-size: 11px;">
                        <i class="fa fa-calendar-alt me-1 text-danger"></i>Requested for: ${req.date}
                    </div>
                </div>
                <span class="badge ${badgeClass} text-uppercase" style="font-size: 10px; padding: 5px 8px;">${req.status}</span>
            </div>`;
                });
            } else {
                html = '<div class="p-4 text-center text-muted"><i class="fa fa-info-circle me-1"></i>No driver requests found.</div>';
            }
            $('#scheduledRequestsList').html(html);
            $('#scheduledRequestsModal').modal('show');
        });
    });
    $(document).on('click', '#copyDriversListBtn', function(e) {
        e.preventDefault();
        let list = window.filteredDriversToCopy || [];
        if (list.length === 0) {
            showNotification('No drivers in the list to copy.', 'error');
            return;
        }
        let copyText = list.join('\n');
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(copyText).then(() => {
                showNotification(`${list.length} drivers copied to clipboard!`, 'success');
            }).catch(err => {
                console.error('Clipboard API failed, using fallback.', err);
                fallbackCopyTextToClipboard(copyText, list.length);
            });
        } else {
            fallbackCopyTextToClipboard(copyText, list.length);
        }
    });
    function fallbackCopyTextToClipboard(text, count) {
        let $temp = $("<textarea>");
        $("body").append($temp);
        $temp.val(text).select();
        try {
            document.execCommand("copy");
            showNotification(`${count} drivers copied to clipboard!`, 'success');
        } catch (err) {
            showNotification('Failed to copy to clipboard.', 'error');
        }
        $temp.remove();
    }
    $(document).on('click', '.view-cancelled-bids-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const jobNo = $(this).data('jobno');
        const rawBids = $(this).attr('data-bids'); // Use attr to retrieve safely
        let bids = {};
        if (rawBids) {
            try {
                bids = JSON.parse(decodeURIComponent(rawBids));
            } catch (err) {
                console.error('Error parsing bids data:', err);
            }
        }
        $('#cancelledBidsModal .modal-title').html(`<i class="fa fa-gavel text-primary me-2"></i>Bids History (${jobNo})`);
        let html = '';
        if (bids && Object.keys(bids).length > 0) {
            Object.entries(bids).forEach(([driverId, bidData]) => {
                let fallbackName = bidData.b_name || '<i class="fa fa-spinner fa-spin"></i>';
                
                // Extract updated_at strictly from the JSON bidData object
                let bidTimestamp = 'N/A';
                
                if (bidData.updated_at) {
                    let bDate = new Date(bidData.updated_at);
                    if (!isNaN(bDate.getTime())) {
                        bidTimestamp = bDate.toLocaleString('en-GB', { 
                            day: '2-digit', month: 'short', year: 'numeric', 
                            hour: '2-digit', minute: '2-digit', hour12: true 
                        });
                    }
                } else if (bidData.created_at) { 
                    // Fallback to created_at if updated_at doesn't exist in the JSON yet
                    let cDate = typeof bidData.created_at === 'number' ? new Date(bidData.created_at) : new Date(bidData.created_at);
                    if (!isNaN(cDate.getTime())) {
                        bidTimestamp = cDate.toLocaleString('en-GB', { 
                            day: '2-digit', month: 'short', year: 'numeric', 
                            hour: '2-digit', minute: '2-digit', hour12: true 
                        });
                    }
                }

                html += `
                <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                    <div>
                        <div class="fw-bold text-dark mb-0" style="font-size: 14px;">
                            <i class="fa fa-user-circle text-muted me-2"></i>
                            <span id="bids_modal_name_${driverId}">${fallbackName}</span>
                        </div>
                        <div class="mb-1">
                            <a href="#" id="bids_modal_mobile_link_${driverId}" class="text-primary text-decoration-none small fw-semibold">
                                <i class="fa fa-phone me-1"></i><span id="bids_modal_mobile_${driverId}"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
                            </a>
                        </div>
                        ${bidData.remark ? `<div class="small text-muted" style="font-size: 11px;"><i class="fa fa-comment-dots me-1 text-warning"></i>${bidData.remark}</div>` : ''}
                    </div>
                    <div class="text-end">
                        <span class="d-block fw-bold text-success fs-6">₹${bidData.amount}</span>
                        <span class="badge bg-secondary text-uppercase mt-1" style="font-size: 9px;">${bidData.status || 'pending'}</span>
                        <div class="text-muted mt-1 fw-semibold" style="font-size: 10px;">
                            <i class="fa fa-clock me-1"></i>${bidTimestamp}
                        </div>
                    </div>
                </div>`;
            });
            $('#cancelledBidsList').html(html);
            $('#cancelledBidsModal').modal('show');
            Object.entries(bids).forEach(([driverId, bidData]) => {
                if (typeof window.driversAll !== 'undefined' && window.driversAll[driverId]) {
                    let dName = window.driversAll[driverId].name || bidData.b_name || 'Unknown Driver';
                    let dMobile = window.driversAll[driverId].mobile || 'N/A';
                    $(`#bids_modal_name_${driverId}`).text(dName);
                    $(`#bids_modal_mobile_${driverId}`).text(dMobile);
                    $(`#bids_modal_mobile_link_${driverId}`).attr('href', `tel:${dMobile}`);
                } else {
                    $.ajax({
                        url: window.location.origin + "/ajax/service/driverServices.php",
                        type: "POST",
                        dataType: "json",
                        data: {
                            method: 'get_driver_details',
                            driver_id: driverId
                        },
                        success: function(res) {
                            let data = null;
                            if (res.status && res.data && res.data.driver) {
                                data = res.data.driver;
                            } else if (typeof allMapDriversCache !== 'undefined' && allMapDriversCache) {
                                let foundSearch = allMapDriversCache.find(d => String(d.user_id) === String(driverId) || String(d.id) === String(driverId));
                                if (foundSearch) data = foundSearch;
                            }
                            let dName = (data && data.name) ? data.name : (bidData.b_name || 'Unknown Driver');
                            let dMobile = (data && data.mobile) ? data.mobile : 'N/A';
                            $(`#bids_modal_name_${driverId}`).text(dName);
                            $(`#bids_modal_mobile_${driverId}`).text(dMobile);
                            $(`#bids_modal_mobile_link_${driverId}`).attr('href', `tel:${dMobile}`);
                        },
                        error: function() {
                            $(`#bids_modal_name_${driverId}`).text(bidData.b_name || 'Unknown Driver');
                            $(`#bids_modal_mobile_${driverId}`).text('N/A');
                        }
                    });
                }
            });
        } else {
            html = '<div class="p-4 text-center text-muted"><i class="fa fa-info-circle me-1"></i>No bids found.</div>';
            $('#cancelledBidsList').html(html);
            $('#cancelledBidsModal').modal('show');
        }
    });
</script>