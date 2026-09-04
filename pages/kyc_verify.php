<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

$pageTitle = "Verification Center";

if (isset($subid3)) {
    $userID = $subid3;
    $kyc_id = $subid4;
}

?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>

.is-iframe .app-header,
.is-iframe .app-sidebar,
.is-iframe .footer {
    display: none !important;
}

.is-iframe .jumps-prevent {
    padding-top: 30px !important;
}

.is-iframe .app-content {
    margin-left: 0 !important;
}

/* Main wrapper */
/* Main wrapper */
.rc-viewer {
    display: flex;
    gap: 12px;
    align-items: center;
}

/* Main image container (car-gallery style) */
#rcCarousel {
    position: relative;
    flex: 1;
    height: 450px;
    border: 1px dashed #c8a98a;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

/* Stack front/back images */
#rcCarousel .image-box {
    position: absolute;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
}

#rcCarousel .image-box.active {
    display: flex;
}

/* Image sizing */
#rcCarousel img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

/* Right-side thumbnails */
.rc-thumbs-vertical {
    display: flex;
    flex-direction: column;
    gap: 8px;
    width: 90px;
    justify-content: center;
}

/* Thumbnails */
.rc-thumb {
    width: 100%;
    height: 65px;
    object-fit: cover;
    cursor: pointer;
    opacity: 0.6;
    border-radius: 6px;
    border: 2px solid transparent;
}

.rc-thumb.active {
    opacity: 1;
    border-color: #0d6efd;
}












.user-info-card {

    margin-top: 10px;

    display: flex;

    align-items: center;

    gap: 28px;

    flex-wrap: wrap;

    /* responsive */

    background: linear-gradient(135deg, #0b0f16, #0f172a);

    padding: 18px 22px;

    border-radius: 14px;

    box-shadow: 0 0 18px rgba(0, 255, 200, 0.35);

}


@media (max-width: 768px) {

    .user-info-card {

        flex-direction: column;

        /* stack items */

        align-items: stretch;

        gap: 10px;

        padding: 12px 14px;

        border-radius: 10px;

    }

    .user-info-card .info-item {

        display: flex;

        justify-content: space-between;

        align-items: flex-start;

        gap: 8px;

    }

    .user-info-card .label {

        font-size: 12px;

        font-weight: 600;

        color: #9ca3af;

        min-width: 90px;

    }

    .user-info-card .value {

        font-size: 13px;

        text-align: right;

        flex: 1;

        word-break: break-word;

        color: #e5e7eb;

    }

    /* Address stacked for readability */

    .user-info-card .info-item.address {

        flex-direction: column;

        gap: 2px;

    }

    .user-info-card .info-item.address .value {

        text-align: left;

    }

}

/* Each item */

.info-item {

    display: flex;

    align-items: center;

    gap: 6px;

    white-space: nowrap;

}

.accessory-item {

    position: relative;

    display: inline-block;

    cursor: pointer;

    font-size: 20px;

}

/* Tooltip text */

.accessory-item::after {

    content: attr(data-title);

    position: absolute;

    bottom: 130%;

    left: 50%;

    transform: translateX(-50%);

    background: #000;

    color: #fff;

    padding: 6px 10px;

    border-radius: 6px;

    font-size: 12px;

    white-space: nowrap;

    opacity: 0;

    pointer-events: none;

    transition: opacity 0.2s ease-in-out;

}

/* Tooltip arrow */

.accessory-item::before {

    content: '';

    position: absolute;

    bottom: 115%;

    left: 50%;

    transform: translateX(-50%);

    border: 6px solid transparent;

    border-top-color: #000;

    opacity: 0;

    transition: opacity 0.2s ease-in-out;

}

/* Show on hover */

.accessory-item:hover::after,

.accessory-item:hover::before {

    opacity: 1;

}

/* Address takes remaining space */

.info-item.address {

    flex: 1;

    white-space: normal;

}

/* Labels */

.label {

    color: #00ffd5;

    font-weight: 600;

    margin: 0 !important;

}

/* Values */

.value {

    color: #ffffff;

    font-weight: 500;

    word-break: break-word;

}

/* 📱 Mobile tweaks */

@media (max-width: 576px) {

    .user-info-card {

        gap: 14px;

    }

    .info-item {

        width: 100%;

    }

}

#waHistoryWrapper {

    max-height: 300px;

    overflow-y: auto;

    display: block;

}

.label_name {

    float: right;

    font-weight: bold;

}

#waHistoryWrapper table {

    width: 100%;

    border-collapse: collapse;

}

#waHistoryWrapper thead th {

    position: sticky;

    top: 0;

    background: #f8f9fa;

    z-index: 10;

}

.draggable-img {

    width: 100%;

    height: 450px;

    object-fit: contain;

}

#warnPopup {

    display: none;

    display: flex;

}

#warnPopupBody td i {

    margin-right: 6px;

}

.icon-trigger {

    font-size: 20px;

    cursor: pointer;

    padding: 10px;

    transition: 0.2s;

}

.icon-trigger:hover {

    transform: scale(1.2);

}
</style>

<style>
.swal2-container {

    z-index: 999999 !important;

}

/* Container for the list */

.forms-list {

    margin-top: 10px;

    display: grid;

    gap: 12px;

    max-height: 52vh;

    overflow-y: auto;

    padding-right: 6px;

    /* for scrollbar breathing room */

}

/* Single row */

.form-row {

    display: flex;

    align-items: center;

    gap: 16px;

    padding: 12px 14px;

    /*border-radius: 10px;*/

    /*background: #ffffff;*/

    /*box-shadow: 0 6px 20px rgba(37, 62, 92, 0.06);*/

    border-left: 4px solid rgba(74, 108, 247, 0.10);

}

/* Left column (title + small meta if needed) */

.form-meta {

    flex: 1;

    min-width: 0;

}

.form-title {

    display: block;

    font-size: 15px;

    font-weight: 600;

    color: #173144;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;

}

.form-sub {

    display: block;

    font-size: 12px;

    color: #657786;

    margin-top: 4px;

}

/* Buttons group */

.form-actions {

    display: flex;

    gap: 8px;

    align-items: center;

}

/* Premium buttons */

.btn-premium {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    padding: 8px 12px;

    border-radius: 8px;

    font-weight: 600;

    font-size: 13px;

    border: 0;

    box-shadow: none;

    transition: transform .08s ease, box-shadow .12s ease;

    cursor: pointer;

}

/* Generate button (primary) */

.btn-generate {

    background: linear-gradient(180deg, #4a6cf7, #3a57c1);

    color: #fff;

}

.btn-generate:focus,

.btn-generate:hover {

    transform: translateY(-2px);

    box-shadow: 0 6px 18px rgba(58, 87, 193, 0.18);

}

/* WhatsApp button (accent) */

.btn-whatsapp {

    background: linear-gradient(180deg, #2ed069, #23b55a);

    color: #fff;

}

.btn-whatsapp:focus,

.btn-whatsapp:hover {

    transform: translateY(-2px);

    box-shadow: 0 6px 18px rgba(36, 176, 90, 0.18);

}

/* Icon sizing */

.btn-premium svg {

    width: 14px;

    height: 14px;

}

/* Responsive */

@media (max-width: 480px) {

    /*.premium-modal .modal-dialog { margin: 12px; }*/

    .form-row {

        padding: 10px;

        gap: 10px;

    }

    .form-actions {

        gap: 6px;

    }

    .btn-premium {

        padding: 7px 10px;

        font-size: 12px;

    }

}

.nav-tabs .nav-link.active {

    color: #000;

    background: #fff9a2;

    /* Light yellow background */

    border-bottom: 2px solid #FFD700;

    margin-right: 5px;

    border-radius: 5px 5px 0 0;

    font-weight: 500;

}

.nav-tabs .nav-link {

    background-color: #fff6db;

    color: #000;

    font-weight: 600;

}

.nav-tabs .nav-link:hover:not(.disabled),

.nav-tabs .nav-link.active {

    background: #fffcd1;

    color: black;

}

.nav-pills {

    flex-wrap: nowrap !important;

    /* Prevent wrapping */

    overflow-x: auto;

    /* Allow horizontal scroll */

    background-color: #fff;

    /* White background */

    border-bottom: 2px solid #ccc;

    /* Light bottom border for the whole nav */

}

.nav-pills .nav-item {

    flex: 1 1 auto;

}

.nav-pills .nav-link {

    font-size: 14px;

    /* Smaller text */

    padding: 6px 10px;

    /* Reduce button height and width */

    white-space: nowrap;

    /* Prevent text wrap */

    color: #000;

    /* Black text */

    background-color: transparent;

    /* Transparent background for buttons */

    border-radius: 0;

    /* Remove pill rounding */

    border-bottom: 2px solid transparent;

    /* No underline for inactive tabs */

}

.nav-pills .nav-link.active {

    border-bottom: 2px solid #ffffff;

    color: #ffffff;

}

.nav-pills .nav-link.active,

.nav-pills .show>.nav-link {

    background-color: #00651f;

}

#verificationTab {

    border: none;

}

.image-box {

    width: 100%;

    max-width: 100%;

    height: 450px;

    border: 3px dashed #7e7361;

    border-radius: 10px;

    overflow: hidden;

    position: relative;

    background: #f8f9fa;

    touch-action: none;

}
/* Rotate button overlay */
.image-box .rotate-btn {
    position: absolute;
    bottom: 12px;
    left: 12px;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: none;
    background: rgba(0, 0, 0, 0.65);
    color: #fff;
    font-size: 18px;
    cursor: pointer;
    z-index: 20;
    display: flex;
    align-items: center;
    justify-content: center;
}

.image-box .rotate-btn:hover {
    background: rgba(0, 0, 0, 0.85);
}

#approveBtn,
#rejectBtn {
    font-size: 16px;
    gap: 8px;
}


@media (max-width: 576px) {

    #approveBtn,
    #rejectBtn {
        font-size: 14px;
    }

    .kyc-action-buttons {
        gap: 10px;
    }
}

.image-box img {

    position: absolute;

    top: 0;

    left: 0;

    cursor: grab;

    -webkit-user-drag: none;

    transform-origin: center center;

    user-select: none;

    transition: transform 0.15s ease-out;

}

.image-box img:active {

    cursor: grabbing;

}

.image-carousel img {

    max-width: 100%;

    /*height: 100%;*/

    border-radius: 10px;

    transition: transform 0.3s ease;

}

.image-box {

    overflow: hidden;

    position: relative;

    display: inline-block;

}

.image-box img {

    transition: transform 0.1s ease-out;

    cursor: grab;

    user-select: none;

}

.table-container {

    max-height: 280px;

    /* adjust as needed */

    overflow-y: auto;

    border: 1px solid #ddd;

    border-radius: 6px;

    padding: 5px;

    background: #fff;

}

#InfoTable th {

    position: sticky;

    top: 0;

    background: #f8f9fa;

    z-index: 1;

}

.table-responsive::-webkit-scrollbar {

    width: 6px;

}

.table-responsive::-webkit-scrollbar-thumb {

    background-color: rgba(0, 0, 0, 0.3);

    border-radius: 10px;

}

.table-responsive {

    scrollbar-width: thin;

    scrollbar-color: rgba(0, 0, 0, 0.3) transparent;

}

.badge-gradient {

    background: linear-gradient(45deg, #ffcc33, #ff9966);

    color: #212529;

    font-weight: 600;

    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);

    border: none;

}

.badge-gradient i {

    animation: pulse 1.2s infinite;

}

@keyframes pulse {

    0%,

    100% {

        opacity: 1;

        transform: scale(1);

    }

    50% {

        opacity: 0.6;

        transform: scale(1.1);

    }

}

#accessoriesContainer {

    display: flex;

    flex-wrap: wrap;

    gap: 12px;

    justify-content: flex-start;

    /* or center */

    align-items: center;

    padding: 8px 0;

}

#accessoriesContainer .accessory-item {

    display: flex;

    align-items: center;

    background: #f8f9fa;

    border: 1px solid #dee2e6;

    border-radius: 50px;

    padding: 6px 12px;

    font-weight: 500;

    color: #333;

    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);

    transition: 0.2s;

}

#accessoriesContainer .accessory-item i {

    margin-right: 6px;

    color: #0d6efd;

}

#accessoriesContainer .accessory-item:hover {

    background: #e9ecef;

    transform: translateY(-2px);

}

.role-toggle small {

    transform: scale(0.85);

}

.role-toggle-container {

    display: flex;

    justify-content: center;

    margin-top: 10px;

}

.role-toggle {

    position: relative;

    width: 240px;

    height: 45px;

}

.role-toggle input {

    display: none;

}

.toggle-slider {

    cursor: pointer;

    background: #e9ecef;

    border-radius: 30px;

    height: 100%;

    width: 100%;

    position: relative;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 0 12px;

    font-weight: 600;

    color: #6c757d;

    transition: all 0.3s ease;

}

.toggle-option {

    width: 50%;

    text-align: center;

    z-index: 2;

    font-size: 14px;

    display: flex;

    align-items: center;

    gap: 4px;

    justify-content: center;

}

.toggle-slider::before {

    content: "";

    position: absolute;

    height: 40px;

    width: 120px;

    background: #ffffff;

    border-radius: 25px;

    top: 2.5px;

    left: 2.5px;

    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.18);

    transition: transform 0.35s ease, background 0.35s ease, box-shadow 0.35s ease;

}

/* Active glowing effect */

#roleSwitch:checked+.toggle-slider::before {

    transform: translateX(118px);

    background: #ffffff;

    box-shadow: 0 0 12px rgba(0, 123, 255, 0.6);

}

#roleSwitch:not(:checked)+.toggle-slider::before {

    box-shadow: 0 0 12px rgba(40, 167, 69, 0.5);

}

/* Text color change based on active side */

#roleSwitch:checked+.toggle-slider .left {

    color: #adb5bd;

}

#roleSwitch:not(:checked)+.toggle-slider .right {

    color: #adb5bd;

}

#roleSwitch:checked+.toggle-slider .right {

    color: #0d6efd;

    font-weight: 700;

    text-shadow: 0 0 6px rgba(13, 110, 253, 0.6);

}

#roleSwitch:not(:checked)+.toggle-slider .left {

    color: #28a745;

    font-weight: 700;

    text-shadow: 0 0 6px rgba(40, 167, 69, 0.5);

}

.role-note {

    margin-top: 12px;

    font-size: 13px;

    color: #5a5a5a;

    display: flex;

    align-items: center;

    gap: 6px;

    background: #f8f9fa;

    padding: 8px 12px;

    border-radius: 6px;

    border-left: 3px solid #0d6efd;

}
</style>

<style>
/* Container for the image carousel (kept for positioning) */

.image-carousel {

    position: relative;

}

/* 🔄 NEW Vertical Thumbnail Strip Container */

.thumbnail-strip-inside {

    /* Position the strip vertically on the right side of the main image box */

    position: absolute;

    top: 9px;

    right: 9px;

    /* Adjust this value based on your overall layout/padding */

    width: 100px;

    /* Width of the entire vertical strip */

    height: 427px;

    /* Match the height of the main image-box for alignment */

    /* Display settings for vertical scroll */

    display: flex;

    /* Keep flex for item alignment */

    flex-direction: column;

    /* Stack items vertically */

    gap: 10px;

    padding: 6px 10px;

    border-radius: 8px;

    /*background: #f8f9fa;*/

    /* Light background for the strip */

    border: 1px solid #ccc;

    /* z-index: 10; */

    /* Enable vertical scrolling */

    overflow-y: auto;

    overflow-x: hidden;

}

#carInfoTable,

#carInfoTable th,

#carInfoTable td {

    border: none !important;

}

tbody,

td,

tfoot,

th,

thead,

tr {

    border-style: none !important;

}

/* Thumbnail images */

.thumbnail-strip-inside .thumb-img {

    width: 100px;

    /* Fixed width */

    height: 70px;

    /* Fixed height */

    flex-shrink: 0;

    /* Important: Prevents images from shrinking */

    object-fit: cover;

    border-radius: 6px;

    border: 2px solid transparent;

    cursor: pointer;

    opacity: 0.85;

    transition: 0.3s;

}

.thumbnail-strip-inside .thumb-img.active {

    opacity: 1;

    border-color: #00651f;

    /* Highlight active thumbnail in green */

    box-shadow: 0 0 8px rgba(0, 101, 31, 0.4);

}

/* Scrollbar styling for the vertical strip (Optional, but nice) */

.thumbnail-strip-inside::-webkit-scrollbar {

    width: 4px;

}

.thumbnail-strip-inside::-webkit-scrollbar-thumb {

    background-color: rgba(0, 0, 0, 0.2);

    border-radius: 10px;

}

.user-info-neon {

    display: flex;

    align-items: center;

    gap: 40px;

    padding: 12px 20px;

    background: #0d0f15;

    border: 1px solid rgba(0, 255, 200, 0.3);

    border-radius: 8px;

    box-shadow: 0 0 10px rgba(0, 255, 200, 0.3),

        inset 0 0 10px rgba(0, 255, 200, 0.2);

    font-family: "Poppins", sans-serif;

    font-size: 14px;

    color: #eafaff;

    letter-spacing: 0.3px;

}

.user-info-neon .item {

    white-space: nowrap;

    padding: 5px 12px;

    border-radius: 6px;

    transition: 0.3s ease;

}

.user-info-neon .item:hover {

    background: rgba(0, 255, 200, 0.07);

    box-shadow: 0 0 8px rgba(0, 255, 200, 0.4);

}

.user-info-neon strong {

    color: #00ffc8;

    /* Neon turquoise */

    font-weight: 600;

    text-shadow: 0 0 5px rgba(0, 255, 200, 0.6);

}

.user-info-neon span {

    color: #ffffff;

    margin-left: 3px;

}

@media (max-width: 768px) {

    .thumbnail-strip-inside {

        position: relative;

        /* remove absolute stacking */

        top: auto;

        right: auto;

        width: 100%;

        height: auto;

        flex-direction: row;

        /* horizontal thumbnails */

        gap: 10px;

        padding: 8px;

        margin-top: 10px;

        overflow-x: auto;

        /* horizontal scroll */

        overflow-y: hidden;

        white-space: nowrap;

    }

    .thumbnail-strip-inside img {

        flex: 0 0 auto;

        /* prevent shrinking */

        width: 80px;

        /* adjust as needed */

        height: auto;

    }

}

@media (max-width: 768px) {

    /* Force single-row tabs */

    #verificationTab {

        flex-wrap: nowrap;

        justify-content: space-between;

        gap: 6px !important;

    }

    /* Reduce tab button size */

    #verificationTab .nav-link {

        padding: 6px 10px;

        font-size: 13px;

        white-space: nowrap;

    }

    /* Reduce icon size */

    #verificationTab .nav-link i {

        font-size: 14px;

        margin-right: 6px !important;

    }

}

    .user-select-wrapper {
        width: 260px;
    }
    
    .user-select {
        height: 40px;
        font-size: 14px;
        border-radius: 4px;
        border: 1px solid #ced4da;
        background-color: #ffffff;
    }
    
    .user-select:focus {
        border-color: #0d6efd; /* Bootstrap primary */
        box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.15);
    }


</style>

<div class="main-content app-content mt-0">

    <div class="side-app">

        <input type="hidden" id="tabID" value="agents">

        <div class="main-container container-fluid p-3">

        </div>
        <div class="col-12 d-flex justify-content-end">
            <div class="user-select-wrapper mb-2">
                <select id="users_id" class="form-select user-select">
                </select>
            </div>
        </div>



        <div class="row row-sm">

            <div class="col-lg-12">

                <div class="card">

                    <div class="card-body pt-4">

                        <div class="grid-margin">

                            <div class="row align-items-center">

                                <div class="row align-items-start">

                                    <!-- LEFT SIDE: TABS -->

                                    <div class="col-12 col-md-6">
                                        

                                        <ul class="nav nav-tabs flex-row gap-3" id="verificationTab" role="tablist">

                                            <li class="nav-item" role="presentation">

                                                <button class="nav-link active d-flex align-items-center" id="user-tab"
                                                    data-bs-toggle="tab" data-bs-target="#user" type="button"
                                                    role="tab">

                                                    <i class="fa-solid fa-user-check me-2"></i>

                                                    <span>User Verification</span>

                                                </button>

                                            </li>

                                            <li class="nav-item" role="presentation">

                                                <button class="nav-link d-flex align-items-center" id="vehicle-tab"
                                                    data-bs-toggle="tab" data-bs-target="#vehicle" type="button"
                                                    role="tab">

                                                    <i class="fa-solid fa-car-side me-2"></i>

                                                    <span>Vehicle Verification</span>

                                                </button>

                                            </li>

                                        </ul>

                                    </div>

                                    <div class="col-12 col-md-6 d-flex justify-content-md-center align-items-center text-nowrap mt-4"
                                        style="font-size: 15px;">

                                        <span>

                                            <strong>Date :</strong>

                                            <span id="createdDate">15 Aug 2025</span>

                                        </span>

                                        <span class="ms-4">

                                            <strong>Reason :</strong>

                                            <span id="reasonVeh">Pending document verification</span>

                                        </span>

                                    </div>

                                    <!-- RIGHT SIDE: USER INFO CARD -->

                                    <div class="col-12">

                                        <div class="user-info-card">

                                            <div class="info-item">

                                                <span class="label">Name</span>

                                                <span class="value" id="us_name"></span>

                                            </div>

                                            <div class="info-item">

                                                <span class="label">Mobile</span>

                                                <span class="value" id="us_mobile"></span>

                                            </div>

                                            <div class="info-item address">

                                                <span class="label">Address</span>

                                                <span class="value" id="us_address"></span>

                                            </div>

                                            <div class="info-item">

                                                <span class="label">User Type</span>

                                                <span class="value" id="us_type"></span>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="tab-content" id="verificationTabContent">

                                    <div class="tab-pane fade show active" id="user" role="tabpanel"
                                        aria-labelledby="user-tab">

                                        <div class="row g-3 mt-3 border p-2">

                                            <div class="col-md-4 d-flex flex-column align-items-center">

                                                <div class="card mb-3 p-3 text-center"
                                                    style="width: 300px; height: 300px;">

                                                    <img id="selfieImage" src="" class="img-fluid"
                                                        style="width: 100%; height: 100%; object-fit: cover;"
                                                        alt="User Photo" />

                                                    <i id="openLocationModal" data-id="<?php echo $userID; ?>"
                                                        class="bi bi-geo-alt-fill text-primary"
                                                        style="cursor:pointer; font-size: 2rem; position:absolute; top:10px; right:10px;"
                                                        title="View Location">

                                                    </i>

                                                </div>

                                                <div class="card p-3 table-container"
                                                    style="width: 100%; max-width: 400px;">

                                                    <h6 class="card-title mb-3 text-center">

                                                        <i class="fa-solid fa-list text-primary"></i> <span
                                                            id="card-title-text">User Aadhar Info</span>

                                                    </h6>

                                                    <table class="table table-bordered mb-0 align-middle"
                                                        id="InfoTable">

                                                        <tbody id="info-body">

                                                        </tbody>

                                                    </table>

                                                </div>

                                            </div>

                                            <div class="col-md-7">

                                                <ul class="nav nav-pills mb-3" role="tablist">

                                                    <li class="nav-item flex-grow-1 me-2" role="presentation">

                                                        <button
                                                            class="nav-link active d-flex align-items-center justify-content-center"
                                                            id="aadhar-tab" data-bs-toggle="pill"
                                                            data-bs-target="#aadhar" type="button" role="tab"
                                                            aria-controls="aadhar" aria-selected="true">

                                                            <i class="fa-solid fa-id-card me-2"></i> Aadhar Card

                                                        </button>

                                                    </li>

                                                    <li class="nav-item flex-grow-1 dl_tab" role="presentation">

                                                        <button
                                                            class="nav-link d-flex align-items-center justify-content-center"
                                                            id="dl-tab" data-bs-toggle="pill" data-bs-target="#dl"
                                                            type="button" role="tab" aria-controls="dl"
                                                            aria-selected="false">

                                                            <i class="fa-solid fa-address-card me-2"></i> DL Card

                                                        </button>

                                                    </li>

                                                    <li class="nav-item flex-grow-1 gst_tab" role="presentation">

                                                        <button
                                                            class="nav-link d-flex align-items-center justify-content-center"
                                                            id="gst-tab" data-bs-toggle="pill" data-bs-target="#gst"
                                                            type="button" role="tab" aria-controls="gst"
                                                            aria-selected="false">

                                                            <i class="fa-solid fa-address-card me-2"></i> Name Board /

                                                            GST

                                                        </button>

                                                    </li>

                                                </ul>

                                                <div class="tab-content w-100">

                                                    <div class="tab-pane fade show active" id="aadhar" role="tabpanel"
                                                        aria-labelledby="aadhar-tab">

                                                        <div class="card p-3 mb-3">

                                                            <h5 class="card-title">

                                                                <i class="fa-solid fa-id-card me-2"></i>Aadhar Card

                                                                (Front & Back)

                                                            </h5>

                                                            <div class="image-carousel text-center" id="aadharCarousel">

                                                                <div class="image-box">

                                                                    <img id="aadharFront" src=""
                                                                        class="img-fluid draggable-img"
                                                                        alt="Aadhar Front" data-img-alt="Aadhar Front">

                                                                </div>

                                                                <div class="image-box d-none">

                                                                    <img id="aadharBack" src=""
                                                                        class="img-fluid draggable-img"
                                                                        alt="Aadhar Back" data-img-alt="Aadhar Back">

                                                                </div>

                                                                <div class="thumbnail-strip-inside"
                                                                    id="aadhar-thumbnail-strip">

                                                                    <img src="" class="thumb-img aadhar-thumb active"
                                                                        data-target="aadharFront"
                                                                        id="aadhar-thumb-front">

                                                                    <img src="" class="thumb-img aadhar-thumb"
                                                                        data-target="aadharBack" id="aadhar-thumb-back">

                                                                </div>

                                                           

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="tab-pane fade" id="dl" role="tabpanel"
                                                        aria-labelledby="dl-tab">

                                                        <div class="card p-3 mb-3">

                                                            <h5 class="card-title">

                                                                <i class="fa-solid fa-address-card me-2"></i>Driving

                                                                License (Front & Back)

                                                            </h5>

                                                            <div class="image-carousel text-center" id="dlCarousel">

                                                                <div class="image-box">

                                                                    <img id="dlFront" src=""
                                                                        class="img-fluid draggable-img" alt="DL Front"
                                                                        data-img-alt="DL Front"
                                                                        style="width: 100%; height: 100%; object-fit: contain;">

                                                                </div>

                                                                <div class="image-box d-none">

                                                                    <img id="dlBack" src=""
                                                                        class="img-fluid draggable-img" alt="DL Back"
                                                                        data-img-alt="DL Back"
                                                                        style="width: 100%; height: 100%; object-fit: contain;">

                                                                </div>

                                                                <div class="thumbnail-strip-inside"
                                                                    id="dl-thumbnail-strip">

                                                                    <img src="" class="thumb-img dl-thumb active"
                                                                        data-target="dlFront" id="dl-thumb-front">

                                                                    <img src="" class="thumb-img dl-thumb"
                                                                        data-target="dlBack" id="dl-thumb-back">

                                                                </div>
                                                                <!--<button type="button"-->
                                                                <!--    style="position:absolute;z-index:9999;bottom:7px;left:12px;background-color:#fff;font-size:20px;"-->
                                                                <!--    class="btn btn-sm mt-2 dl-rotate">-->
                                                                <!--    🔄-->
                                                                <!--</button>-->

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="tab-pane fade" id="gst" role="tabpanel"
                                                        aria-labelledby="gst-tab">

                                                        <div class="card p-3 mb-3">

                                                            <h5 class="card-title mb-3" id="d_n_type">

                                                                <i class="fa-solid fa-address-card me-2"></i>Name Board

                                                                Image

                                                            </h5>

                                                            <div class="image-carousel text-center nameboard_div mb-3"
                                                                id="gstCarousel">

                                                                <div class="image-box">

                                                                    <img id="boardFront" src=""
                                                                        class="img-fluid draggable-img"
                                                                        alt="Name Board Front"
                                                                        data-img-alt="Name Board">

                                                                </div>

                                                            </div>

                                                            <div class="gst_div text-center">

                                                                <input type="text"
                                                                    class="form-control d-inline-block text-center mb-2"
                                                                    id="gst_no" placeholder="Enter GST Number"
                                                                    autocomplete="off" style="width: 300px;">

                                                                <br>

                                                                <button class="btn btn-primary" id="searchBtn">

                                                                    Fetch Details

                                                                </button>

                                                            </div>

                                                            <div class="gst_div mt-3" id="gst_details">

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="row align-items-center">

                                                    <div class="col-6 text-end">

                                                        <div id="doc_status">

                                                            <div class="flex-grow-1 text-end">

                                                                <span id="verifyStatusLabel"
                                                                    class="badge bg-success text-white fs-6 px-3 py-2 rounded-pill shadow-sm">

                                                                    <i class="fa-solid fa-hourglass-half me-2"></i>

                                                                    Pending

                                                                </span>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="col-md-6 text-end">

                                                    </div>

                                                    <!--<div class="col-6 text-end">-->

                                                    <!--    <div class="pagination-btn-new">-->

                                                    <!--        <button class="btn btn-secondary prev-btn"><i class="fa-solid fa-arrow-left me-1"></i> Previous</button>-->

                                                    <!--        <button class="btn btn-primary next-btn">Next <i class="fa-solid fa-arrow-right ms-1"></i></button>-->

                                                    <!--    </div>-->

                                                    <!--</div>-->

                                                </div>

                                            </div>

                                            <div class="col-12 col-md-1 d-flex flex-row flex-md-column gap-1">

                                                <!--<i id="warnBtn"-->

                                                <!--    class="bi bi-exclamation-circle-fill icon-trigger text-danger position-relative"-->

                                                <!--    style="cursor:pointer; font-size:1.25rem;" title="View reason"-->

                                                <!--    data-bs-toggle="modal" data-bs-target="#modalReason">-->

                                                <!--</i>-->

                                                <i class="bi bi-upload icon-trigger text-danger position-relative"
                                                    style="cursor:pointer; font-size:1.25rem;" title="Upload Docs"
                                                    data-bs-toggle="modal" data-bs-target="#modalUpload">

                                                </i>

                                                <i class="bi bi-whatsapp icon-trigger text-success" id="wh_icon"
                                                    data-bs-toggle="modal" data-bs-target="#modalWhatsapp"></i>

                                                <i class="bi bi-ui-checks icon-trigger text-primary"
                                                    data-bs-toggle="modal" data-bs-target="#modalForm"></i>

                                                <i class="bi bi-arrow-left-right icon-trigger text-warning"
                                                    data-bs-toggle="modal" data-bs-target="#modalSwitchRole"></i>

                                                <i class="bi bi-pencil-square icon-trigger text-info"
                                                    onclick="window.open('/profile/edit/<?php echo $userID; ?>', '_blank');">

                                                </i>
                                                
                                                 <a  target="_blank">
                                                    <i class="bi bi-person-badge text-primary ms-2"
                                                    style="font-size:22px; cursor:pointer;"
                                                    title="schedule_driver" id="scheduleBtn"></i>
                                                </a>
                                                


                                                <i class="bi bi-exclamation-triangle icon-trigger text-danger d-none"
                                                    data-bs-toggle="modal" data-bs-target="#modalAlert"></i>

                                            </div>

                                        </div>

                                        <div
                                            class="mt-3 d-flex justify-content-between justify-content-md-end kyc-action-buttons">
                                            <button type="button"
                                                class="btn btn-success px-4 py-2 d-flex align-items-center"
                                                id="approveBtn">
                                                <i class="fa-solid fa-check-circle"></i>
                                                <span class="ms-2">Overall KYC Complete</span>
                                            </button>

                                            <button type="button"
                                                class="btn btn-danger px-4 py-2 d-flex align-items-center"
                                                id="rejectBtn">
                                                <i class="fa-solid fa-times-circle"></i>
                                                <span class="ms-2">Overall KYC Reject</span>
                                            </button>
                                        </div>


                                    </div>

                                    <div class="tab-pane fade" id="vehicle" role="tabpanel"
                                        aria-labelledby="vehicle-tab">

                                        <div class="row g-3 mt-3 border p-2">

                                            <div class="col-md-4 d-flex flex-column align-items-center">

                                                <div class="card mb-3 p-3 text-center"
                                                    style="width: 300px; height: 300px;">

                                                    <img src="" class="img-fluid" id="v_front_img"
                                                        style="width: 100%; height: 100%; object-fit: cover;"
                                                        alt="Car Photo" />

                                                </div>

                                                <div id="accessoriesContainer" class="mb-3 p-3">

                                                </div>

                                                <div class="card shadow-sm p-3"
                                                    style="width: 100%; max-width: 400px; height: 234px;">

                                                    <h6 class="card-title mb-3 text-center">

                                                        <i class="fa-solid fa-car text-primary"></i>

                                                        <span id="infoTitle">Vehicle Info Summary</span>

                                                    </h6>

                                                    <div id="loadingSpinner" class="text-center my-2"
                                                        style="display:none;">

                                                        <div class="spinner-border text-primary" role="status">

                                                            <span class="visually-hidden">Loading...</span>

                                                        </div>

                                                    </div>

                                                    <div class="table-responsive"
                                                        style="max-height: 400px; overflow-y: auto;">

                                                        <table class="table table-bordered mb-0 align-middle"
                                                            id="carInfoTable">

                                                            <tbody id="carInfoTable-body">

                                                            </tbody>

                                                        </table>

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="col-md-7">

                                                <ul class="nav nav-pills mb-3 d-flex flex-wrap justify-content-between"
                                                    id="carTabs" role="tablist">

                                                    <li class="nav-item flex-fill me-2" role="presentation">

                                                        <button class="nav-link active" data-bs-toggle="pill"
                                                            data-bs-target="#car-verification"
                                                            data-tab="car-verification" id="car-verification-tab"
                                                            type="button" role="tab">

                                                            <i class="fa-solid fa-check-circle me-2"></i> Vehicle Images

                                                        </button>

                                                    </li>

                                                    <li class="nav-item flex-fill me-2" role="presentation">

                                                        <button class="nav-link" data-bs-toggle="pill"
                                                            data-bs-target="#puc" data-tab="puc" id="puc-tab"
                                                            type="button" role="tab">

                                                            <i class="fa-solid fa-gas-pump me-2"></i> PUC

                                                        </button>

                                                    </li>

                                                    <li class="nav-item flex-fill me-2" role="presentation">

                                                        <button class="nav-link" data-bs-toggle="pill"
                                                            data-bs-target="#rc" data-tab="rc" id="rc-tab" type="button"
                                                            role="tab">

                                                            <i class="fa-solid fa-id-card me-2"></i> RC

                                                        </button>

                                                    </li>

                                                    <li class="nav-item flex-fill" role="presentation">

                                                        <button class="nav-link" data-bs-toggle="pill"
                                                            data-bs-target="#insurance" data-tab="insurance"
                                                            id="insurance-tab" type="button" role="tab">

                                                            <i class="fa-solid fa-file-invoice me-2"></i> Insurance

                                                        </button>

                                                    </li>

                                                    <li class="nav-item flex-fill" role="presentation">

                                                        <button class="nav-link" data-bs-toggle="pill"
                                                            data-bs-target="#fc" data-tab="fc" id="fc-tab" type="button"
                                                            role="tab">

                                                            <i class="fa-solid fa-file-invoice me-2"></i> FC

                                                        </button>

                                                    </li>

                                                    <!-- <i id="warn_Btn"

                                                class="bi bi-exclamation-circle-fill text-danger position-absolute"

                                                style="cursor:pointer; font-size:1.25rem; top:0px; right:10px;"

                                                title="View reason"

                                                data-bs-toggle="modal"

                                                data-bs-target="#modalVehReason">

                                                </i> -->

                                                </ul>

                                                <div class="tab-content w-100">

                                                    <div class="tab-pane fade show active" id="car-verification"
                                                        role="tabpanel">

                                                        <div class="card p-3 mb-3">

                                                            <h5><i class="fa-solid fa-check-circle me-2"></i> Vehicle

                                                                Images</h5>

                                                            <div id="img_arr">

                                                                <p class="text-center text-muted mt-3">Loading vehicle

                                                                    images...</p>

                                                            </div>

                                                            <h5 class="text-center fw-bold" id="data-img-alt"></h5>

                                                            <div class="text-center">

                                                                <span
                                                                    class="bg-warning text-dark fs-6 px-3 py-2 fw-bold rounded-pill shadow-sm"
                                                                    id="data-img-st"></span>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="tab-pane fade" id="puc" role="tabpanel">

                                                        <div class="card p-3 mb-3">

                                                            <h5><i class="fa-solid fa-gas-pump me-2"></i> PUC

                                                                Certificate</h5>

                                                            <div class="image-carousel text-center" id="pucCarousel">

                                                                <div class="image-box">

                                                                    <img id="pucImg" src=""
                                                                        class="img-fluid draggable-img" alt="PUC"
                                                                        data-img-alt="PUC Certificate"
                                                                        style="width:100%; height: 450px; object-fit:contain;">

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="tab-pane fade" id="rc" role="tabpanel">

                                                        <div class="card p-3 mb-3">

                                                            <h5><i class="fa-solid fa-id-card me-2"></i> RC Book</h5>

                                                         <div class="rc-viewer">

                                                            <!-- MAIN IMAGE -->
                                                            <div id="rcCarousel">
                                                                <div class="image-box active">
                                                                    <img id="rcImg" class="img-fluid draggable-img">
                                                                </div>
                                                        
                                                                <div class="image-box">
                                                                    <img id="rcBackImg" class="img-fluid draggable-img">
                                                                </div>
                                                            </div>
                                                        
                                                            <!-- RIGHT SIDE THUMBNAILS -->
                                                            <div class="rc-thumbs-vertical">
                                                                <img id="rcThumbFront" class="rc-thumb active" draggable="false">
                                                                <img id="rcThumbBack" class="rc-thumb" draggable="false">
                                                            </div>
                                                        
                                                        </div>

                                                        </div>

                                                    </div>

                                                    <div class="tab-pane fade" id="insurance" role="tabpanel">

                                                        <div class="card p-3 mb-3">

                                                            <h5><i class="fa-solid fa-file-invoice me-2"></i> Insurance

                                                            </h5>

                                                            <div class="image-carousel text-center"
                                                                id="insuranceCarousel">

                                                                <div class="image-box">

                                                                    <img id="insuranceImg" src=""
                                                                        class="img-fluid draggable-img" alt="Insurance"
                                                                        data-img-alt="Insurance Certificate"
                                                                        style="width:100%; height: 450px; object-fit:contain;">

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="tab-pane fade" id="fc" role="tabpanel">

                                                        <div class="card p-3 mb-3">

                                                            <h5><i class="fa-solid fa-id-card me-2"></i> FC Book</h5>

                                                            <div class="image-carousel text-center" id="fcCarousel">

                                                                <div class="image-box">

                                                                    <img id="fcImg" src=""
                                                                        class="img-fluid draggable-img" alt="FC"
                                                                        data-img-alt="Fitness Certificate"
                                                                        style="width:100%; height: 450px; object-fit:contain;">

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="row align-items-center">

                                                    <div class="col-md-6 text-end">

                                                        <div id="doc_status"
                                                            class="d-inline-flex align-items-end gap-2">

                                                            <span id="verifyStatusLabel_v"
                                                                class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill shadow-sm">

                                                                <i class="fa-solid fa-hourglass-half me-2"></i> Pending

                                                            </span>

                                                            <span id="expiryLabel"
                                                                class="badge badge-gradient fs-6 px-3 py-2 rounded-pill">

                                                                <i class="fa-solid fa-hourglass-end me-2"></i>

                                                                <strong>Expiry:</strong> <span id="exp_txt">-</span>

                                                            </span>

                                                        </div>

                                                    </div>

                                                    <!--<div class="col-md-6 text-end">-->

                                                    <!--    <div class="pagination-btn-new">-->

                                                    <!--        <button class="btn btn-secondary prev-btn">-->

                                                    <!--            <i class="fa-solid fa-arrow-left me-1"></i> Previous-->

                                                    <!--        </button>-->

                                                    <!--        <button class="btn btn-primary next-btn">-->

                                                    <!--            Next <i class="fa-solid fa-arrow-right ms-1"></i>-->

                                                    <!--        </button>-->

                                                    <!--    </div>-->

                                                    <!--</div>-->

                                                </div>

                                            </div>

                                            <div class="col-12 col-md-1 d-flex flex-row flex-md-column gap-1">

                                                <!--<i id="warnBtn"-->
                                                <!--    class="bi bi-exclamation-circle-fill icon-trigger text-danger position-relative"-->
                                                <!--    style="cursor:pointer; font-size:1.25rem;" title="View reason"-->
                                                <!--    data-bs-toggle="modal" data-bs-target="#modalReason">-->

                                                <!--</i>-->
                                                
                                                <i class="bi bi-upload icon-trigger text-danger position-relative"
                                                    style="cursor:pointer; font-size:1.25rem;" title="Upload Docs"
                                                    data-bs-toggle="modal" data-bs-target="#modalUpload">

                                                </i>

                                                <i class="bi bi-whatsapp icon-trigger text-success" id="wh_icon"
                                                    data-bs-toggle="modal" data-bs-target="#modalWhatsapp"></i>

                                                <i class="bi bi-ui-checks icon-trigger text-primary"
                                                    data-bs-toggle="modal" data-bs-target="#modalForm"></i>

                                                <i class="bi bi-arrow-left-right icon-trigger text-warning"
                                                    data-bs-toggle="modal" data-bs-target="#modalSwitchRole"></i>

                                                <i class="bi bi-pencil-square icon-trigger text-info"
                                                    onclick="window.open('/profile/edit/<?php echo $userID; ?>', '_blank');"></i>

                                                <a  target="_blank">
                                                    <i class="bi bi-person-badge text-primary ms-2"
                                                    style="font-size:22px; cursor:pointer;"
                                                    title="schedule_driver" id="schedulerBtn"></i>
                                                </a>

                                                <i class="bi bi-exclamation-triangle icon-trigger text-danger d-none"
                                                    data-bs-toggle="modal" data-bs-target="#modalAlert"></i>

                                            </div>

                                        </div>

                                        <div class="text-end mt-3">

                                            <button type="button" class="btn btn-success px-4 py-2"
                                                id="approveVehicleBtn">

                                                <i class="fa-solid fa-check-circle me-2"></i> Overall Vehicle Complete

                                            </button>

                                            <button type="button" class="btn btn-danger px-4 py-2 ms-2"
                                                id="rejectVehicleBtn">

                                                <i class="fa-solid fa-times-circle me-2"></i> Overall Vehicle Reject

                                            </button>

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

    <div class="modal fade" id="KycApproveModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow">

                <div class="modal-header text-dark">

                    <h5 class="modal-title">KYC Approve Details</h5>

                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"
                        aria-label="Close"></button>

                </div>

                <div class="modal-body">

                    <div class="form-check mb-3">

                        <input class="form-check-input" type="checkbox" id="selfieStatus">

                        <label class="form-check-label fw-bold" for="selfieStatus">

                            Selfie Status

                        </label>

                    </div>

                    <div class="form-check mb-3">

                        <input class="form-check-input" type="checkbox" id="aadharStatus">

                        <label class="form-check-label fw-bold" for="aadharStatus">

                            Aadhar Status

                        </label>

                    </div>

                    <div class="form-check mb-3 driver-div">

                        <input class="form-check-input" type="checkbox" id="dlStatus">

                        <label class="form-check-label fw-bold" for="dlStatus">

                            DL Status

                        </label>

                    </div>

                    <div class="form-check mb-3 owner-div">

                        <input class="form-check-input" type="checkbox" id="gstStatus">

                        <label class="form-check-label fw-bold" for="gstStatus" id="gstTxt">

                            GST/Name Board Status

                        </label>

                    </div>

                    <div class="mb-3 rej-remark">

                        <label for="kycRemarks" class="form-label fw-bold">Remarks / Notes</label>

                        <textarea class="form-control" id="kycRemarks" rows="3"
                            placeholder="Enter remarks or reason for KYC approval/rejection..."></textarea>

                    </div>

                    <div class="form-check mb-2">

                        <input class="form-check-input" type="checkbox" id="sendWhatsapp">

                        <label class="form-check-label fw-bold text-success" for="sendWhatsapp">

                            Send WhatsApp Message to User

                        </label>

                        <p class="text-muted small mb-0 ms-4">

                            A confirmation message will be sent to the user once KYC approval is saved.

                        </p>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-success saveKycBtn" id="saveKycStatus">Save</button>

                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade" id="VehicleApproveModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow">

                <div class="modal-header text-dark">

                    <h5 class="modal-title" id="v_apr_tit">Vehicle Approve Details</h5>

                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"
                        aria-label="Close"></button>

                </div>

                <div class="modal-body" id="vehicleStatusContainer">

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-success saveKycBtn" id="saveVehicleStatus">Save</button>

                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade" id="modalReason" tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">Reject Reason</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <table class="table">

                        <thead>

                            <tr>

                                <th scope="col">Date</th>

                                <th scope="col">Reason</th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr id="us_nn">

                                <td id="createdDate"></td>

                                <td id="reasonVeh"></td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade" id="modalUpload" tabindex="-1">

        <div class="modal-dialog modal-md">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">Upload Document</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>



                <form id="docUploadForm" enctype="multipart/form-data">

                    <div class="modal-body">



                        <!-- Document Type -->

                        <div class="mb-3">

                            <label class="form-label">Document Type</label>

                            <select name="doc_type" id="docType" class="form-select" required>

                                <option value="">Select document</option>

                                <option value="selfie">Selfie</option>

                                <option value="aadhaar">Aadhaar</option>

                                <option value="dl">Driving License</option>

                                <!--<option value="car_front">Car Front View</option>-->

                                <!--<option value="rc">RC</option>-->

                                <!--<option value="fc">FC</option>-->

                                <!--<option value="puc">PUC</option>-->

                                <!--<option value="insurance">Insurance</option>-->

                            </select>

                        </div>



                        <!-- Document No -->

                        <div class="mb-3 doc_no">

                            <label class="form-label">Document No.</label>

                            <input type="text" class="form-control" name="doc_no">

                        </div>
                        
                        <div class="mb-3 doc_date">

                            <label class="form-label doc_date">DOB.</label>

                            <input type="date" class="form-control" name="doc_date">

                        </div>



                        <!-- FILE 1 -->

                        <div class="mb-3">

                            <label class="form-label">Primary File</label>

                            <div class="border p-3 text-center file-drop" data-input="file1" style="cursor:pointer;">

                                <span class="text-muted">Drag & drop or click to select</span>

                            </div>

                            <input type="file" name="document1" id="file1" hidden accept=".jpg,.jpeg,.png,.pdf">



                            <!-- Preview -->

                            <div class="mt-2 preview" id="preview-file1"></div>

                        </div>



                        <!-- FILE 2 -->

                        <div class="mb-3">

                            <label class="form-label">Secondary File (Optional)</label>

                            <div class="border p-3 text-center file-drop" data-input="file2" style="cursor:pointer;">

                                <span class="text-muted">Drag & drop or click to select</span>

                            </div>

                            <input type="file" name="document2" id="file2" hidden accept=".jpg,.jpeg,.png,.pdf">



                            <!-- Preview -->

                            <div class="mt-2 preview" id="preview-file2"></div>

                        </div>



                        <input type="hidden" name="user_id" value="<?= $userID ?? '' ?>">



                    </div>



                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary w-100">

                            Upload Document

                        </button>

                    </div>

                </form>



            </div>

        </div>

    </div>

    <div class="modal fade" id="modalVehReason" tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">Reject Reason</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <table class="table">

                        <thead>

                            <tr>

                                <th scope="col">Date</th>

                                <th scope="col">Reason</th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr id="us_veh">

                                <td id="createdVehDate"></td>

                                <td id="reasonVehicle"></td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade" id="locationModal" tabindex="-1">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">User Location</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <p><strong>Latitude:</strong> <span id="latValue">--</span></p>

                    <p><strong>Longitude:</strong> <span id="longValue">--</span></p>

                    <div id="map" style="height:350px;width:100%;"></div>

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade" id="modalWhatsapp" tabindex="-1">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">Send WhatsApp Message</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="label_name right-end">

                        <label class="user-name-label">Name:</label>

                        <span id="user_name"></span>

                    </div>

                    <label class="form-label">To Number:</label>

                    <input type="text" id="waNumber" class="form-control" placeholder="+91XXXXXXXXXX" disabled>

                    <select id="sample_name" class="form-control mt-3">

                        <option value="">Select Template</option>

                        <?php

                        $query = mysqli_query($con, "SELECT DISTINCT id, name FROM wamail_templates ORDER BY id ASC");

                        while ($row = mysqli_fetch_assoc($query)) {

                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";

                        }

                        ?>

                    </select>

                    <label class="form-label mt-3">Message:</label>

                    <textarea id="waMessage" class="form-control" rows="4"
                        placeholder="Type message... 😊🔥👍"></textarea>

                    <button id="sendWhatsapp_m" class="btn btn-success mt-3 w-100">

                        <i class="bi bi-send-fill"></i> Send Message

                    </button>

                    <div class="table-responsive mt-4" id="waHistoryWrapper">

                        <table class="table table-bordered">

                            <thead class="table-light">

                                <tr>

                                    <th>Created At</th>

                                    <th>WhatsApp Campaign</th>

                                    <th>To Number</th>

                                    <th>Message</th>

                                </tr>

                            </thead>

                            <tbody id="waHistoryBody">

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade" id="modalForm" tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">Available Forms</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">X</button>

                </div>

                <div class="modal-body">

                    <div class="forms-list" id="formsList">

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade" id="modalSwitchRole" tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5>Switch Role</h5><button type="button" class="btn-close" data-bs-dismiss="modal">X</button>

                </div>

                <div class="modal-body">

                    <div class="role-toggle-container" id="modalSwitchRole">

                        <div class="role-toggle small">

                            <input type="checkbox" id="roleSwitch">

                            <label for="roleSwitch" class="toggle-slider">

                                <span class="toggle-option left">

                                    <i class="bi bi-person-fill"></i> Individual

                                </span>

                                <span class="toggle-option right">

                                    <i class="bi bi-building-fill"></i> Company

                                </span>

                            </label>

                        </div>

                    </div>

                    <div class="role-note">

                        <i class="bi bi-info-circle text-primary"></i>

                        <span>Kindly verify whether this user has any active or pending jobs before changing their

                            role.</span>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade" id="modalInfo" tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5>Information</h5>

                </div>

                <div class="modal-body">Info…</div>

            </div>

        </div>

    </div>

    <div class="modal fade" id="modalAlert" tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5>Alert</h5>

                </div>

                <div class="modal-body">Alert message…</div>

            </div>

        </div>

    </div>

    <div class="modal fade" id="pdfPreviewModal" tabindex="-1">

        <div class="modal-dialog modal-xl" style="max-width:90%">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">PDF Preview</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">X</button>

                </div>

                <div class="modal-body p-0" style="height: 85vh;">

                    <iframe id="pdfFrame" width="100%" height="100%" style="border:0; min-height:80vh;"></iframe>

                </div>

            </div>

        </div>

        <!-- custom popup (hidden by default) -->

        <div id="warnPopup" style="display:none; position:fixed; inset:0; z-index:1050; justify-content:center; align-items:center;

     background: rgba(0,0,0,0.35);">

            <div
                style="background:#fff; padding:18px; border-radius:8px; width:360px; box-shadow:0 6px 20px rgba(0,0,0,0.25); position:relative;">

                <button id="warnClose"
                    style="position:absolute; right:10px; top:6px; border:none; background:transparent; font-size:18px; cursor:pointer;">&times;</button>

                <table style="width:100%;">

                    <tbody id="warnPopupBody"></tbody>

                </table>

            </div>

        </div>

    </div>

</div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
       

    <script>
    
    if (window.self !== window.top) {
        document.body.classList.add('is-iframe');
    }
    // let mobile_user = null;

    const carouselIndices = new WeakMap();

    let statusMap = {

        pending: {

            class: 'bg-warning text-dark',

            icon: 'fa-hourglass-half',

            text: 'Pending'

        },

        approved: {

            class: 'bg-success text-white',

            icon: 'fa-circle-check',

            text: 'Approved'

        },

        rejected: {

            class: 'bg-danger text-white',

            icon: 'fa-circle-xmark',

            text: 'Rejected'

        },

        default: {

            class: 'bg-secondary text-white',

            icon: 'fa-circle-question',

            text: 'Unknown'

        }

    };

    /**

     * Shows the image at the specified index in the given carousel container

     * and updates the alt text/status displayed below it.

     * @param {JQuery<HTMLElement>} $carousel - The jQuery object for the .image-carousel container.

     * @param {number} index - The index of the image to show.

     */

    function showImage($carousel, index) {

        const $boxes = $carousel.find('.image-box');

        if (!$boxes.length) return;

        $boxes.each((i, el) => $(el).toggleClass('d-none', i !== index));

        carouselIndices.set($carousel[0], index);

        // Update thumbnail active state

        const $thumbnailStrip = $carousel.find('.thumbnail-strip-inside');

        if ($thumbnailStrip.length) {

            $thumbnailStrip.find('.thumb-img').removeClass('active').eq(index).addClass('active');

        }

        const $activeBox = $boxes.eq(index);

        // Get alt text and status from the image's data attributes

        const altText = $activeBox.find('img').attr('data-img-alt') || 'No Description Available';

        const stText = $activeBox.find('img').attr('data-img-st') ||

            'pending'; // Default to pending if attribute is missing

        // Find the closest display elements in the parent tab structure

        const $displayElement = $carousel.closest('.tab-pane').find('#data-img-alt').first();

        const $stElement = $carousel.closest('.tab-pane').find('#data-img-st').first();

        // 1. Update Alt Text

        if ($displayElement.length) {

            $displayElement.text(altText);

        }

        // 2. Update Status Badge

        if ($stElement.length) {

            let carStatus = stText.toLowerCase() || 'default';

            var {

                class: badgeClass,

                icon,

                text

            } = statusMap[carStatus] || statusMap.default;

            // Note: Using a more generic class attribute structure as defined in statusMap

            $stElement.attr('class', `badge fs-6 px-3 py-2 fw-bold rounded-pill shadow-sm ${badgeClass}`).html(

                `<i class="fa-solid ${icon} me-2"></i> ${text}`);

        }

    }

    $('.image-carousel').each(function() {

        showImage($(this), 0);

    });

    function getActiveCarousel() {

        // Check if we are in the User Verification tab

        const $userTab = $('#user.tab-pane.show.active');

        if ($userTab.length) {

            // Look for the active pill tab content within the user tab

            const $activePillTabContent = $userTab.find('.tab-content > .tab-pane.show.active');

            if ($activePillTabContent.length) {

                return $activePillTabContent.find('.image-carousel').first();

            }

        }

        // Check if we are in the Vehicle Verification tab

        const $vehicleTab = $('#vehicle.tab-pane.show.active');

        if ($vehicleTab.length) {

            // Look for the active pill tab content within the vehicle tab

            const $activePillTabContent = $vehicleTab.find('.tab-content > .tab-pane.show.active');

            if ($activePillTabContent.length) {

                return $activePillTabContent.find('.image-carousel').first();

            }

        }

        // Default to the first carousel found if context is unclear

        return $('.image-carousel').first();

    }

    // Global buttons

    $('.pagination-btn-new .next-btn').on('click', function() {

        const $activeCarousel = getActiveCarousel();

        if (!$activeCarousel || !$activeCarousel.length) return;

        const $boxes = $activeCarousel.find('.image-box');

        if ($boxes.length <= 1) return;

        let idx = carouselIndices.get($activeCarousel[0]) ?? 0;

        idx = (idx + 1) % $boxes.length;

        showImage($activeCarousel, idx);

    });

    $('.pagination-btn-new .prev-btn').on('click', function() {

        const $activeCarousel = getActiveCarousel();

        if (!$activeCarousel || !$activeCarousel.length) return;

        const $boxes = $activeCarousel.find('.image-box');

        if ($boxes.length <= 1) return;

        let idx = carouselIndices.get($activeCarousel[0]) ?? 0;

        idx = (idx - 1 + $boxes.length) % $boxes.length;

        // Calling showImage updates the image AND the alt text

        showImage($activeCarousel, idx);

    });

    // Handle tab switch (Bootstrap event)

    $('[data-bs-toggle="pill"]').on('shown.bs.tab', function() {

        const $activeCarousel = getActiveCarousel();

        if ($activeCarousel && $activeCarousel.length) {

            // Calling showImage updates the image AND the alt text on tab switch

            showImage($activeCarousel, 0);

        }

    });

    // Generic Thumbnail Click Handler

    function handleThumbnailClick(event) {

        const thumb = event.currentTarget;

        const targetId = thumb.dataset.target;

        const carousel = thumb.closest('.image-carousel');

        if (!carousel) return;

        // 1. Hide all main images in this carousel

        $(carousel).find(".image-box").addClass("d-none");

        // 2. Show selected image's parent box

        const targetImage = $(carousel).find(`#${targetId}`);

        if (targetImage.length) {

            targetImage.closest(".image-box").removeClass("d-none");

        }

        // 3. Update active thumbnail style in this strip

        $(carousel).find(".thumb-img").removeClass("active");

        $(thumb).addClass("active");

        // 4. Update index and call showImage to refresh the details (alt text, status)

        const $activeBox = $(carousel).find('.image-box').not('.d-none');

        const index = $activeBox.index();

        if (index !== -1) {

            showImage($(carousel), index);

        }

    }

    function setupThumbnailListeners() {

        document.querySelectorAll(".thumb-img").forEach(thumb => {

            // Remove existing listener to prevent duplicates on repeated calls

            thumb.removeEventListener("click", handleThumbnailClick);

            thumb.addEventListener("click", handleThumbnailClick);

        });

    }


    $(document).ready(function() {

        setupThumbnailListeners();

    });
    </script>

    <script>
          function setupImageZoom(img) {
                if (img.dataset.zoomInit) return;
                img.dataset.zoomInit = "true";
            
                const box = img.closest('.image-box');
                if (!box) return;
            
                let scale = 1;
                let posX = 0;
                let posY = 0;
                let startX = 0;
                let startY = 0;
                let isDragging = false;
            
                let rotation = parseInt(img.dataset.rotation) || 0;
            
                const pointers = new Map();
                let startDistance = 0;
                let startScale = 1;
            
                img.style.touchAction = 'none';
            
                function updateTransform() {
                    img.style.transform =
                        `translate(${posX}px, ${posY}px) scale(${scale}) rotate(${rotation}deg)`;
                }
            
                function softLimit() {
                    const limitX = (img.width * scale) / 2;
                    const limitY = (img.height * scale) / 2;
                    posX = Math.min(Math.max(posX, -limitX), limitX);
                    posY = Math.min(Math.max(posY, -limitY), limitY);
                }

            
                img.addEventListener('pointerdown', e => {
                    img.setPointerCapture(e.pointerId);
                    pointers.set(e.pointerId, e);
            
                    if (pointers.size === 1 && scale > 1) {
                        isDragging = true;
                        startX = e.clientX - posX;
                        startY = e.clientY - posY;
                    }
            
                    if (pointers.size === 2) {
                        const [p1, p2] = [...pointers.values()];
                        startDistance = Math.hypot(
                            p1.clientX - p2.clientX,
                            p1.clientY - p2.clientY
                        );
                        startScale = scale;
                    }
                });
            
                img.addEventListener('pointermove', e => {
                    if (!pointers.has(e.pointerId)) return;
                    pointers.set(e.pointerId, e);
            
                    if (pointers.size === 1 && isDragging) {
                        posX = e.clientX - startX;
                        posY = e.clientY - startY;
                        softLimit();
                    }
            
                    if (pointers.size === 2) {
                        const [p1, p2] = [...pointers.values()];
                        const distance = Math.hypot(
                            p1.clientX - p2.clientX,
                            p1.clientY - p2.clientY
                        );
                        scale = Math.min(Math.max(1, startScale * (distance / startDistance)), 6);
                    }
            
                    updateTransform();
                });
            
                const endPointer = e => {
                    pointers.delete(e.pointerId);
                    if (pointers.size === 0) isDragging = false;
                };
            
                img.addEventListener('pointerup', endPointer);
                img.addEventListener('pointercancel', endPointer);
                img.addEventListener('pointerleave', endPointer);
            
                img.addEventListener('wheel', e => {
                    e.preventDefault();
                    scale = Math.min(Math.max(1, scale + (e.deltaY < 0 ? 0.15 : -0.15)), 6);
                    updateTransform();
                }, { passive: false });
            

            
                let lastTap = 0;
                img.addEventListener('pointerdown', () => {
                    const now = Date.now();
                    if (now - lastTap < 300) {
                        scale = 1;
                        posX = 0;
                        posY = 0;
                        updateTransform();
                    }
                    lastTap = now;
                });

            
                let rotateBtn = box.querySelector('.rotate-btn');
                if (!rotateBtn) {
                    rotateBtn = document.createElement('button');
                    rotateBtn.className = 'rotate-btn';
                    rotateBtn.innerHTML = '⟳';
                    box.appendChild(rotateBtn);
                }
            
                rotateBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
            
                    rotation = (rotation + 90) % 360;
                    img.dataset.rotation = rotation;
            
                    updateTransform();
                });
            
                updateTransform();
            }
            document.querySelectorAll('.image-box img').forEach(img => {
                setupImageZoom(img);
            });

            document.querySelectorAll('.image-box img').forEach(setupImageZoom);
        
            const observer = new MutationObserver(mutations => {
                mutations.forEach(mutation => {
                    mutation.addedNodes.forEach(node => {
                        if (node.nodeType === 1) {
                            if (node.matches('.image-box img')) {
                                setupImageZoom(node);
                            } else {
                                node.querySelectorAll?.('.image-box img').forEach(setupImageZoom);
                            }
                        }
                    });
                });
            });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    </script>


    <script>
    $(document).ready(function() {

        // let schedule_mobile = mobile_user;
        // console.log(schedule_mobile);

        const origin = window.location.origin;

        const userID = <?= json_encode($userID) ?>;

        const kycID = <?= json_encode($kyc_id) ?>;

        // const placeholder = "/assets/images/loading_img.png";
        const placeholder = "/assets/images/placeholderimage.jpg";
        const pendingholder = "/assets/images/digilockerpending.png"

        usersSelect("#users_id");

        if (!$('#docType').hasClass('select2-hidden-accessible')) {

            $('#docType').select2({

                dropdownParent: $('#modalUpload'),

                width: '100%'

            });

        }
        
        $('#docType').on('change', function(){
            
            if($('#docType').val() == 'aadhar'){
                $('.doc_no').hide();
                $('.doc_date').hide();
                
            }else if($('#docType').val() == 'dl'){
                
                $('.doc_no').show();
                $('.doc_date').show();
            }else{
                $('.doc_no').hide();
                $('.doc_date').hide();
            }
            
        })



        document.querySelectorAll('.file-drop').forEach(dropZone => {



            const inputId = dropZone.dataset.input;

            const fileInput = document.getElementById(inputId);

            const previewBox = document.getElementById('preview-' + inputId);



            // Click → file picker

            dropZone.addEventListener('pointerdown', function(e) {

                e.preventDefault();

                e.stopPropagation();

                fileInput.click();

            });



            // Drag over

            dropZone.addEventListener('dragover', function(e) {

                e.preventDefault();

                dropZone.classList.add('bg-light');

            });



            dropZone.addEventListener('dragleave', function() {

                dropZone.classList.remove('bg-light');

            });



            // Drop

            dropZone.addEventListener('drop', function(e) {

                e.preventDefault();

                dropZone.classList.remove('bg-light');



                if (e.dataTransfer.files.length) {

                    fileInput.files = e.dataTransfer.files;

                    renderPreview(fileInput.files[0], previewBox);

                }

            });



            // Change (file picker)

            fileInput.addEventListener('change', function() {

                if (this.files.length) {

                    renderPreview(this.files[0], previewBox);

                }

            });

        });



        /* ---------------------------

           PREVIEW HANDLER

        ---------------------------- */

        function renderPreview(file, previewBox) {



            previewBox.innerHTML = '';



            if (file.type.startsWith('image/')) {

                const img = document.createElement('img');

                img.src = URL.createObjectURL(file);

                img.className = 'img-thumbnail';

                img.style.maxHeight = '150px';

                previewBox.appendChild(img);

            } else if (file.type === 'application/pdf') {

                previewBox.innerHTML = `

                        <div class="d-flex align-items-center gap-2">

                            <i class="bi bi-file-earmark-pdf text-danger fs-3"></i>

                            <span>${file.name}</span>

                        </div>

                    `;

            } else {

                previewBox.innerHTML = `<span>${file.name}</span>`;

            }

        }



        /* ---------------------------

           RESET ON MODAL CLOSE

        ---------------------------- */

        document.getElementById('modalUpload').addEventListener('hidden.bs.modal', function() {

            document.querySelectorAll('.preview').forEach(p => p.innerHTML = '');

            document.querySelectorAll('input[type=file]').forEach(f => f.value = '');

        });



        $('#docUploadForm').on('submit', function(e) {

            e.preventDefault();



            const formData = new FormData(this);

            formData.append('method', 'kyc_manualUpload');

            $.ajax({

                url: origin + "/ajax/service/kycAddtionalServer.php",

                method: 'POST',

                data: formData,

                processData: false,

                contentType: false,

                beforeSend: function() {

                    $('#docUploadForm button[type=submit]')

                        .prop('disabled', true)

                        .text('Uploading...');

                },

                success: function(res) {

                    let response = typeof res == 'string' ? JSON.parse(res) : res;
                    
                    if (response.type == '1') {

                        toast('success', 'Document uploaded successfully');

                        $('#modalUpload').modal('hide');
                        
                        loadKycData();

                    } else {

                        toast('error', response.message || 'Upload failed');

                    }

                },

                error: function() {

                    toast('success', 'Upload failed. Please try again.');

                },

                complete: function() {

                    $('#docUploadForm button[type=submit]')

                        .prop('disabled', false)

                        .text('Upload Document');

                }

            });

        });



        $('#modalUpload').on('hidden.bs.modal', function() {

            fileLabel.textContent = 'Drag & drop file here or click to select';

            fileLabel.classList.add('text-muted');

            fileInput.value = '';

        });





        $(document).on("click", "#viewSelfieBtn", function() {

            const tableRows = `

                        <tr>

                            <td><i class="fas fa-check-circle me-2 text-warning"></i><strong>Date</strong></td>

                            <td>:</td>

                            <td>${createdDate}</td>

                        </tr>

                        <tr>

                            <td><i class="fas fa-check-circle me-2 text-warning"></i><strong>Reason</strong></td>

                            <td>:</td>

                            <td>${reason}</td>

                        </tr>

                    `;

            $("#popupContent").html(tableRows);

            $("#customPopup").css("display", "flex");

            $("#eyeNotifyDot").hide();

        });

        $("#closePopup").on("click", function() {

            $("#customPopup").hide();

        });

      
        function loadKycData(selectedType = "AADHAR") {

            $("#info-body").html(

                "<tr><td colspan='3' class='text-center text-primary'>Loading KYC data...</td></tr>");

            $.ajax({

                url: origin + "/ajax/service/datatable_services.php",

                type: "POST",

                dataType: "json",

                data: {

                    method: 'kycUserDetails',

                    user_id: userID,

                    id: kycID,

                    txt: selectedType

                },

                success: function(response) {

                    if (!response || !response.result) {

                        $("#info-body").html(

                            "<tr><td colspan='3' class='text-center text-danger'>No KYC data found!</td></tr>"

                        );

                        return;

                    }

                    const user = response.result;
                    let mobile_user = user.mobile;
                    
                    $('#scheduleBtn').attr(
                        'onclick',
                        `setCookie('schedule_d_mobile', '${mobile_user}'); window.open('/schedule-driver', '_blank');`
                    );

                    let aadharB, aadharF = placeholder;

                    const selfieUrl = user.selfie_url?.trim() || placeholder;

                    $("#selfieImage").attr("src", selfieUrl);

                    $('#waNumber').val(user.mobile);

                    $('#user_name').text(user.name);

                    $('#us_name').text(user.name);

                    $('#us_email').text(user.email);

                    $('#us_address').text(user.address);

                    $('#us_mobile').text(user.mobile);

                    $('#us_type').text(user.type);

                    // USER ROLE LOGIC (Simplified)

                    if (user.type == 'Driver') {

                        $('.gst_div').hide()

                        $('.nameboard_div').hide();

                        $('.gst_tab').hide();

                        $('.dl_tab').show();

                        $('#vehicle-tab').removeClass('d-none').addClass('d-flex');

                        $('#approveBtn').attr('onclick', "approveKYC('" + user.type + "','" + user

                            .selfie_status + "','" + user.proof_status + "','" + user

                            .dl_status + "','" + user.id + "')");

                        $('#rejectBtn').attr('onclick', "rejectKYC('" + user.type + "','" + user

                            .selfie_status + "','" + user.proof_status + "','" + user

                            .dl_status + "','" + user.id + "')");

                        // Load vehicle data to ensure the tabs are ready

                        // loadVehicleData("VEHICLE"); // Not called here to avoid double loading on initial page load

                    } else if (user.type == 'Owner') {

                        $('#vehicle-tab').removeClass('d-flex').addClass('d-none');

                        $('.gst_tab').show();

                        $('.dl_tab').hide();

                        if (user.o_proof_type == 'gst') {

                            $('.gst_div').show()

                            $('.nameboard_div').hide()

                        } else {

                            $('.nameboard_div').show()

                            $('.gst_div').hide()

                        }

                        $('#approveBtn').attr('onclick', "approveKYC('" + user.type + "','" + user

                            .selfie_status + "','" + user.proof_status + "','" + user

                            .o_proof_status + "','" + user.id + "')");

                        $('#rejectBtn').attr('onclick', "rejectKYC('" + user.type + "','" + user

                            .selfie_status + "','" + user.proof_status + "','" + user

                            .o_proof_status + "','" + user.id + "')");

                    }

                    // if (selectedType == "AADHAR" || selectedType == 'AADHAR_DIGILOCKER') {

                    //     if (user.proof_type == 'AADHAR_DIGILOCKER' && user.proof_status == 'approved') {

                    //         aadharF = '/assets/images/digiverify.png';

                    //         aadharB = '/assets/images/digiverify.png';

                    //         // Hide Aadhar back image thumbnail if it's digilocker

                    //         $('#aadhar-thumb-back').addClass('d-none');

                    //     }
                    //     else if(user.proof_type === 'AADHAR_DIGILOCKER' &&
                        
                    //         ['approval_pending', 'requested'].includes(user.proof_status)){
                                
                    //             aadharF = pendingholder;
                    //             aadharB = pendingholder;
                            
                    //             $('#aadhar-thumb-back').addClass('d-none');
                            
                    //     } else {

                    //         aadharF = user.front_image?.trim() || placeholder;

                    //         aadharB = user.back_image?.trim() || placeholder;

                    //         $('#aadhar-thumb-back').removeClass('d-none');

                    //     }

                    //     const aadharInfo = `

                    //     <tr><td><i class="fas fa-user me-2 text-primary"></i><strong>Full Name</strong></td><td>:</td><td>${user.name || '-'}</td></tr>

                    //     <tr><td><i class="fas fa-mobile-alt me-2 text-success"></i><strong>Mobile</strong></td><td>:</td><td>${user.mobile || '-'}</td></tr>

                    //     <tr><td><i class="fas fa-id-card me-2 text-info"></i><strong>Proof Type</strong></td><td>:</td><td>${user.proof_type || '-'}</td></tr>

                    //     <tr><td><i class="fas fa-check-circle me-2 text-warning"></i><strong>Proof Status</strong></td><td>:</td><td>${user.proof_status || '-'}</td></tr>

                    // `;

                    //     const create_date = user.created_at ?? '-';

                    //     document.getElementById("createdDate").textContent = create_date;

                    //     const vehicle_reason = user.reject_reason ?? '-';

                    //     document.getElementById("reasonVeh").textContent = vehicle_reason;

                    //     $("#card-title-text").text("User Aadhar Info");

                    //     $("#info-body").html(aadharInfo);

                    //     $("#aadharFront").attr("src", aadharF).attr("data-img-st", user

                    //         .proof_status)

                    //     $("#aadhar-thumb-front").attr("src", aadharF)

                    //     $("#aadharBack").attr("src", aadharB).attr("data-img-st", user.proof_status)

                    //     $("#aadhar-thumb-back").attr("src", aadharB)

                    //     // Call showImage to reset carousel index and update status/alt

                    //     showImage($('#aadharCarousel'), 0);

                    //     setupThumbnailListeners();

                    //     if (user.proof_status === 'approval_pending' || user.proof_status ===

                    //         'approved') {

                    //         $('#verifyToggle').prop('checked', true);

                    //         $('#verifyStatusLabel').html(

                    //             '<i class="fa-solid fa-circle-check me-2 text-white"></i> Verified'

                    //         );

                    //     } else {

                    //         $('#verifyToggle').prop('checked', false);

                    //         $('#verifyStatusLabel').html(

                    //             '<i class="fa-solid fa-hourglass-half me-2 text-warning"></i> Not Verified'

                    //         );

                    //     }

                    // } 
                    if (selectedType == "AADHAR" || selectedType == 'AADHAR_DIGILOCKER') {

    if (user.proof_type == 'AADHAR_DIGILOCKER' && ['approved', 'approval_pending'].includes(user.proof_status)) {
        aadharF = '/assets/images/digiverify.png';
        aadharB = '/assets/images/digiverify.png';
        $('#aadhar-thumb-back').addClass('d-none');
    }
    else if(user.proof_type === 'AADHAR_DIGILOCKER' && user.proof_status === 'requested'){
        aadharF = pendingholder;
        aadharB = pendingholder;
        $('#aadhar-thumb-back').addClass('d-none');
    } else {
        aadharF = user.front_image?.trim() || placeholder;
        aadharB = user.back_image?.trim() || placeholder;
        $('#aadhar-thumb-back').removeClass('d-none');
    }

    const aadharInfo = `
    <tr><td><i class="fas fa-user me-2 text-primary"></i><strong>Full Name</strong></td><td>:</td><td>${user.name || '-'}</td></tr>
    <tr><td><i class="fas fa-mobile-alt me-2 text-success"></i><strong>Mobile</strong></td><td>:</td><td>${user.mobile || '-'}</td></tr>
    <tr><td><i class="fas fa-id-card me-2 text-info"></i><strong>Proof Type</strong></td><td>:</td><td>${user.proof_type || '-'}</td></tr>
    <tr><td><i class="fas fa-check-circle me-2 text-warning"></i><strong>Proof Status</strong></td><td>:</td><td>${user.proof_status || '-'}</td></tr>
    `;

    const create_date = user.created_at ?? '-';
    document.getElementById("createdDate").textContent = create_date;

    const vehicle_reason = user.reject_reason ?? '-';
    document.getElementById("reasonVeh").textContent = vehicle_reason;

    $("#card-title-text").text("User Aadhar Info");
    $("#info-body").html(aadharInfo);

    $("#aadharFront").attr("src", aadharF).attr("data-img-st", user.proof_status)
    $("#aadhar-thumb-front").attr("src", aadharF)
    $("#aadharBack").attr("src", aadharB).attr("data-img-st", user.proof_status)
    $("#aadhar-thumb-back").attr("src", aadharB)

    showImage($('#aadharCarousel'), 0);
    setupThumbnailListeners();

    if (user.proof_status === 'approval_pending' || user.proof_status === 'approved') {
        $('#verifyToggle').prop('checked', true);
        $('#verifyStatusLabel').html(
            '<i class="fa-solid fa-circle-check me-2 text-white"></i> Verified'
        );
    } else {
        $('#verifyToggle').prop('checked', false);
        $('#verifyStatusLabel').html(
            '<i class="fa-solid fa-hourglass-half me-2 text-warning"></i> Not Verified'
        );
    }
}
                    else if (selectedType == "DRIVING_LICENSE") {

                        const dlFront = user.front?.trim() || placeholder;

                        const dlBack = user.back?.trim() || placeholder;

                        $("#card-title-text").text("Driving Licence Info");

                        let dlDetails = {};

                        try {

                            if (user.req_response) dlDetails = JSON.parse(user.req_response);

                        } catch (err) {

                            console.warn("⚠️ Invalid req_response JSON for DL:", err);

                        }

                        let dlInfo = "";

                        Object.entries(dlDetails).forEach(([key, value]) => {

                            if (key != 'Status') {

                                dlInfo += `

                          <tr>

                            <td><strong>${key.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())}</strong></td>

                            <td>:</td>

                            <td>${value || '-'}</td>

                          </tr>

                        `;

                            }

                        });


                        $("#card-title-text").text("User Driving License Info")

                        $("#info-body").html(dlInfo)

                        // Populate DL Carousel

                        $("#dlFront").attr("src", dlFront).attr("data-img-st", user.dl_status);

                        $("#dl-thumb-front").attr("src", dlFront);

                        $("#dlBack").attr("src", dlBack).attr("data-img-st", user.dl_status);

                        $("#dl-thumb-back").attr("src", dlBack);

                        // Call showImage to reset carousel index and update status/alt

                        showImage($('#dlCarousel'), 0);

                        setupThumbnailListeners();

                        if (user.dl_status === 'approval_pending' || user.dl_status ===

                            'approved') {

                            $('#verifyToggle').prop('checked', true);

                            $('#verifyStatusLabel').html(

                                '<i class="fa-solid fa-circle-check me-2 text-white"></i> Verified'

                            );

                        } else {

                            $('#verifyToggle').prop('checked', false);

                            $('#verifyStatusLabel').html(

                                '<i class="fa-solid fa-hourglass-half me-2 text-warning"></i> Not Verified'

                            );

                        }

                    } else if (selectedType == "GST") {

                        if (user.o_proof_type == 'gst') {

                            // GST TEXT VIEW

                            $("#card-title-text").text("GST Info");

                            $('.nameboard_div').hide();

                            $('.gst_div').show();

                            let us_Info = `

                            <tr><td><i class="fas fa-user me-2 text-primary"></i><strong>Full Name</strong></td><td>:</td><td>${user.name || '-'}</td></tr>

                            <tr><td><i class="fas fa-mobile-alt me-2 text-success"></i><strong>Mobile</strong></td><td>:</td><td>${user.mobile || '-'}</td></tr>

                            <tr><td><i class="fas fa-id-card me-2 text-info"></i><strong>Proof Type</strong></td><td>:</td><td>${user.o_proof_type || '-'}</td></tr>

                            <tr><td><i class="fas fa-check-circle me-2 text-warning"></i><strong>Proof Status</strong></td><td>:</td><td>${user.o_proof_status || '-'}</td></tr>

                        `;

                            $("#info-body").html(us_Info)

                            if (user.gst_details) {

                                let htmlll = '';

                                htmlll += `

                                <div class="row mb-2">

                                    <h4 class="col-12 text-start fw-bold pe-2">GST Details :</h4>

                                </div>

                                <hr>

                            `;

                                let gstDetails = JSON.parse(user.gst_details);

                                for (let key in gstDetails) {

                                    if (gstDetails.hasOwnProperty(key)) {

                                        htmlll += `

                                        <div class="row mb-2">

                                            <div class="col-5 text-start fw-bold pe-2">${key}</div>

                                            <div class="col-1 text-center">:</div>

                                            <div class="col-6 text-end">${gstDetails[key]}</div>

                                        </div>

                                    `;

                                    }

                                }

                                $('#gst_details').html(htmlll);

                                $('#gst_no').prop('disabled', true)

                                $('#searchBtn').prop('disabled', true).html('Fetched');

                            } else {

                                $('#gst_details').empty();

                                $('#gst_no').prop('disabled', false)

                                $('#searchBtn').prop('disabled', false).html('Fetch Details');

                            }

                            $('#d_n_type').html('<i class="fa-solid fa-address-card me-2"></i>GST');

                            $('#searchBtn').attr('onclick', 'checkGST("' + user.id + '")');

                            $('#gst_no').val(user.o_proof_no);

                        } else {

                            // NAME BOARD IMAGE VIEW

                            $("#card-title-text").text("Name Board Info");

                            $('.gst_div').hide();

                            $('.nameboard_div').show();


                            const gstInfo = `

                            <tr><td><i class="fas fa-user me-2 text-primary"></i><strong>Full Name</strong></td><td>:</td><td>${user.name || '-'}</td></tr>

                            <tr><td><i class="fas fa-mobile-alt me-2 text-success"></i><strong>Mobile</strong></td><td>:</td><td>${user.mobile || '-'}</td></tr>

                            <tr><td><i class="fas fa-user me-2 text-primary"></i><strong>Status</strong></td><td>:</td><td>${user.o_proof_status || '-'}</td></tr>

                            <tr><td><i class="fas fa-mobile-alt me-2 text-success"></i><strong>Reason</strong></td><td>:</td><td>${user.o_proof_reason || '-'}</td></tr>

                            <tr><td><i class="fas fa-building me-2 text-success"></i><strong>Company Name</strong></td><td>:</td><td>${user.company_name || '-'}</td></tr>

                            
                            



                        `;

                            $('#d_n_type').html(

                                '<i class="fa-solid fa-address-card me-2"></i>Name Board Image');

                            $("#boardFront").attr("src", user.o_proof?.trim() || placeholder).attr(

                                "data-img-st", user.o_proof_status);

                            $("#info-body").html(gstInfo);

                            // Call showImage to reset carousel index and update status/alt

                            showImage($('#gstCarousel'), 0);

                        }

                        let statusElement = $('#verifyStatusLabel');

                        let currentStatus = user.o_proof_status?.toLowerCase() || 'default';

                        let {

                            class: badgeClass,

                            icon,

                            text

                        } = statusMap[currentStatus] || statusMap.default;

                        console.log(badgeClass, 'hiiiiiiiiiiii')

                        statusElement.attr('class',

                            `badge fs-6 px-3 py-2 rounded-pill shadow-sm ${badgeClass}`).html(

                            `<i class="fa-solid ${icon} me-2"></i> ${text}`);

                    }

                },

                error: function(xhr, status, error) {

                    console.error("❌ Error loading data:", {

                        status,

                        error,

                        responseText: xhr.responseText

                    });

                    $("#info-body").html(

                        "<tr><td colspan='3' class='text-center text-danger'>Error loading KYC data!</td></tr>"

                    );

                }

            });

        }



        function loadVehicleData(txt, selectedType = 'VEHICLE') {

            $("#carInfoTable-body").html(

                "<tr><td colspan='3' class='text-center text-primary'>Loading vehicle data...</td></tr>");

            // Hide accessories for docs other than VEHICLE

            if (txt !== 'VEHICLE') {

                $('#accessoriesContainer').empty().hide();

            } else {

                $('#accessoriesContainer').show();

            }

            $.ajax({

                url: origin + "/ajax/service/datatable_services.php",

                type: "POST",

                dataType: "json",

                data: {

                    method: 'kycUserDetails',

                    user_id: userID,

                    id: kycID,

                    txt: selectedType

                },



                success: function(response) {

                    if (!response || !response.result) {

                        $("#carInfoTable-body").html(

                            "<tr><td colspan='3' class='text-center text-danger'>No vehicle data found!</td></tr>"

                        );

                        return;

                    }

                   const schedule_driver = response.result;
                   let schedule_mobile = schedule_driver.mobile;

                     $('#schedulerBtn').attr(
                        'onclick',
                        `setCookie('schedule_d_mobile', '${schedule_mobile}'); window.open('/schedule-driver', '_blank');`
                    );


                    $(document).on('click', '#warnBtn', function(e) {

                        e.preventDefault();

                        const createdDate = $(this).attr('data-created') || "-";

                        const reason = $(this).attr('data-reason') || "-";

                        const rows = `

                    <tr><td><i class="fas fa-check-circle me-2 text-warning"></i><strong>Date</strong></td><td>:</td><td>${date_created ?? '-'}</td></tr>

                    <tr><td><i class="fas fa-check-circle me-2 text-warning"></i><strong>Reason</strong></td><td>:</td><td>${reason}</td></tr>

                    `;

                        $('#warnPopupBody').html(rows);

                        $('#warnPopup').fadeIn(100);

                    });

                    $(document).on('click', '#warnClose', function() {

                        $('#warnPopup').fadeOut(100);

                    });

                    $(document).on('click', '#warnPopup', function(e) {

                        if (e.target.id === 'warnPopup') $('#warnPopup').fadeOut(100);

                    });

                    const vehicle_reason = user.reject_reason ?? '-';

                    document.getElementById("reasonVeh").textContent = vehicle_reason;

                    let data = response.result;

                    let date_created = data.v_updated_at ?? null;

                    document.getElementById("createdVehDate").textContent = date_created;

                    // let veh_reason = vehicle_details.vehicle_review_message ?? null;

                    // console.log(veh_reason);

                    // document.getElementById("reasonVehicle").textContent = veh_reason;

                    // console.log(date); 2025-12-10 

                    let info_body = $('#carInfoTable-body').empty();

                    if (data.vehicle_details) {

                        let vehicle_details = JSON.parse(data.vehicle_details);

                        let veh = vehicle_details.vehicle ?? null;

                        let veh_reason = vehicle_details.vehicle_review_message ?? null;

                        document.getElementById("reasonVehicle").textContent = veh_reason;

                        let rc = vehicle_details?.rc_details?.response ?? {};

                        let v_details = rc.vehicle_details ?? {};

                        let user_details = rc.user_details ?? {};

                        let permit_details = rc.permit_details ?? {};

                        let finance_details = rc.finance_details ?? {};

                        let fc_details = vehicle_details.fc_details ?? {};

                        let vehicle_questions = vehicle_details?.vehicle_questions ?? {};

                        $('#v_front_img').attr('src', veh?.front_view_image_url ?? placeholder);

                        // --- CAROUSEL AND THUMBNAIL LOGIC FOR VEHICLE IMAGES TAB ---

                        if (txt == 'VEHICLE' && veh) {

                            $('#verifyStatusLabel_v')

                                .hide(); // Vehicle Images tab shows status per image

                            $('#expiryLabel').hide();

                            let v_img_map = {

                                'front_view_image_url': 'front_view_admin_status',

                                'side_view_image_url': 'side_view_admin_status',

                                'back_view_image_url': 'back_view_admin_status',

                                'boot_image_url': 'boot_admin_status',

                                'extra_image_1_url': 'extra_image_1_admin_status',

                                'extra_image_2_url': 'extra_image_2_admin_status',

                                'car_top_view_image_url': 'car_top_view_admin_status',

                                'interior_rear_image_url': 'interior_rear_admin_status',

                                'interior_front_image_url': 'interior_front_admin_status',

                                'special_features_image_url': 'special_features_admin_status'

                            };

                            let img_html = '';

                            let thumbnail_html = '';

                            let firstIndex = 0;

                            Object.entries(v_img_map).forEach(([urlKey, statusKey]) => {

                                let clean_text = urlKey.replace(/_/g, ' ').replace(/url/gi,

                                    '').trim().replace(/\b\w/g, char => char

                                    .toUpperCase());

                                let img_sts = veh[statusKey] || 'pending';

                                let img_url = veh[urlKey] || placeholder;

                                let img_id = urlKey.replace(/_url/i,

                                    ''); // e.g., 'front_view_image'

                                let activeClass = firstIndex === 0 ? 'active' : '';

                                // Only generate if image exists or we have a placeholder

                                if (veh[urlKey]) {

                                    // Image Box HTML

                                    img_html += `

                                    <div class="image-box ${firstIndex !== 0 ? 'd-none' : ''}">

                                    <img id="${img_id}" 

                                        src="${img_url}" 

                                        class="img-fluid draggable-img vehicle_img" 

                                        alt="${clean_text}"

                                        style="width:100%; height: 450px; object-fit:contain;"

                                        data-img-alt="${clean_text}" 

                                        data-img-st="${img_sts}"

                                        data-rotate="0">

                                      <button type="button"
                                            style="position:absolute;z-index:9999;bottom:7px;left:12px;background-color:#fff;font-size:20px;"
                                            class="btn btn-sm mt-2 rotate-btn" 
                                            data-img-id="${img_id}">
                                        🔄
                                    </button>

                                </div>

                                `;

                                    // Thumbnail HTML

                                    thumbnail_html += `

                                    <img src="${img_url}" 

                                         class="thumb-img car-thumb ${activeClass}" 

                                         data-target="${img_id}">`;

                                    firstIndex++;

                                }

                            });

                            // Construct the full carousel HTML

                            let carousel_html = `<div class="image-carousel text-center" id="img_arr_carousel">${img_html}

                            <div class="thumbnail-strip-inside" id="car-thumbnail-strip">${thumbnail_html}</div>

                        </div>`;

                            // Inject the new carousel structure (or just the image box if only one image exists)

                            $('#car-verification #img_arr').replaceWith(carousel_html);

                            // Set up the listeners for the newly created thumbnails

                            // Note: setupThumbnailListeners must be called whenever dynamic thumbnails are added

                            setupThumbnailListeners();

                            // Manually trigger the initial showImage for the dynamic carousel

                            showImage($('#img_arr_carousel'), 0);

                            // Populate Accessories

                            let $container = $('#accessoriesContainer').empty();

                            let accessories = vehicle_questions?.accessories ?? [];

                            let icons = {

                                'Music System': 'fa-music',

                                'GPS Navigation': 'fa-map-marked-alt',

                                'Bluetooth': 'fab fa-bluetooth-b',

                                'Mobile Charging Port': 'fa-plug',

                                'Power Windows': 'fa-car-side',

                                'Wi-Fi': 'fa-wifi'

                            };

                            accessories.forEach(item => {

                                let iconClass = icons[item] || 'fa-circle';

                                $container.append(`

                                <div class="accessory-item" data-title="${item}">

                                    <i class="fa-solid ${iconClass}"></i>

                                </div>

                            `);

                            });

                            // Populate Info Table

                            info_body.append(`

                            <tr>

                                <td><i class="fas fa-car me-2 text-primary"></i><strong>Number</strong></td>

                                <td>:</td>

                                <td>${vehicle_details?.rc_number ?? '-'}</td>

                            </tr>

                             <tr>

                                <td>

                                    <i class="fa-solid fa-cog me-2 text-primary"></i>

                                    <strong>Model</strong>

                                </td>

                                <td>:</td>

                                <td>

                                    ${v_details?.maker_model ?? '-'} -  (${vehicle_questions?.ac_availability ?? '-'})

                                </td>

                            </tr>

                            <tr>

                                <td><i class="fas fa-palette me-2 text-primary"></i><strong>Color</strong></td>

                                <td>:</td>

                                <td>${v_details.color ?? '-'}</td>

                            </tr>

                            <tr>

                                <td><i class="fas fa-chair me-2 text-primary"></i><strong>Seats</strong></td>

                                <td>:</td>

                                <td>${v_details.seat_capacity ?? '-'}</td>

                            </tr>

                        `);

                        } else if (txt == 'PUC') {

                            // PUC TAB LOGIC

                            $('#infoTitle').text('PUC Summary');

                            $('#verifyStatusLabel_v').show();

                            $('#expiryLabel').show();

                            $('#pucImg').attr('src', vehicle_details?.puc_details?.puc_image_url ??

                                placeholder).attr('data-img-alt', 'PUC Certificate').attr(

                                'data-img-st', vehicle_details?.puc_details?.puc_admin_status);

                            showImage($('#pucCarousel'), 0);

                            info_body.append(`

                            <tr>

                                <td><i class="fas fa-id-card me-2 text-primary"></i><strong>Owner Name</strong></td>

                                <td>:</td>

                                <td>${user_details.owner_name ?? '-'}</td>

                            </tr>

                            <tr>

                                <td><i class="fas fa-car me-2 text-primary"></i><strong>Vehicle No.</strong></td>

                                <td>:</td>

                                <td>${vehicle_details?.rc_number ?? '-'}</td>

                            </tr>

                            <tr>

                                <td><i class="fas fa-certificate me-2 text-primary"></i><strong>PUCC No.</strong></td>

                                <td>:</td>

                                <td>${permit_details.pucc_number ?? '-'}</td>

                            </tr>

                            <tr>

                                <td><i class="fas fa-calendar-check me-2 text-primary"></i><strong>PUCC Valid Upto</strong></td>

                                <td>:</td>

                                <td>${permit_details.pucc_upto ?? '-'}</td>

                            </tr>

                            <tr>

                                <td><i class="fas fa-gas-pump me-2 text-primary"></i><strong>Fuel Type</strong></td>

                                <td>:</td>

                                <td>${v_details.fuel_type ?? '-'}</td>

                            </tr>

                            <tr>

                                <td><i class="fas fa-hashtag me-2 text-primary"></i><strong>Chassis No.</strong></td>

                                <td>:</td>

                                <td>${v_details.vehicle_chassis_number ?? '-'}</td>

                            </tr>

                            <tr>

                                <td><i class="fas fa-cogs me-2 text-primary"></i><strong>Engine No.</strong></td>

                                <td>:</td>

                                <td>${v_details.vehicle_engine_number ?? '-'}</td>

                            </tr>

                        `);

                            let pucStatus = vehicle_details?.puc_details?.puc_admin_status ||

                                'default';

                            var {

                                class: badgeClass,

                                icon,

                                text

                            } = statusMap[pucStatus] || statusMap.default;

                            $('#verifyStatusLabel_v')

                                .attr('class',

                                    `badge fs-6 px-3 py-2 rounded-pill shadow-sm ${badgeClass}`)

                                .html(`<i class="fa-solid ${icon} me-2"></i> ${text}`);

                            $('#exp_txt').text(formatExpiryDate(vehicle_details?.puc_details

                                ?.puc_exp_date));

                        } else if (txt == 'RC') {

                            // RC TAB LOGIC

                            $('#infoTitle').text('RC Details Summary');

                            $('#verifyStatusLabel_v').show();

                            $('#expiryLabel').show();


                            // Main images
                           var rcFront = vehicle_details?.rc_front_image_url || placeholder;
                            var rcBack  = vehicle_details?.rc_back_image_url || placeholder;
                            
                            // Set images
                            $('#rcImg').attr('src', rcFront);
                            $('#rcBackImg').attr('src', rcBack);
                            
                            // Set thumbnails
                            $('#rcThumbFront').attr('src', rcFront);
                            $('#rcThumbBack').attr('src', rcBack);
                            
                            let currentRCIndex = 0;
                            
                            showRCImage(0);
                            
                             $('#rcCarousel').on('click', 'img', function () {
                                    showRCImage(currentRCIndex === 0 ? 1 : 0);
                                });


                            
                            function showRCImage(index) {
                                
                                const boxes = $('#rcCarousel .image-box');
                            
                                boxes.removeClass('active');
                                boxes.eq(index).addClass('active');
                            
                                $('.rc-thumb').removeClass('active');
                                $('.rc-thumb').eq(index).addClass('active');
                            
                                currentRCIndex = index;
                            }

                            // Thumbnail clicks
                            $('#rcThumbFront').on('click', function (e) {
                                e.preventDefault();
                                showRCImage(0);
                            });
                            
                            $('#rcThumbBack').on('click', function (e) {
                                e.preventDefault();
                                showRCImage(1);
                            });
                            
                            
                            
                            $('a[data-bs-toggle="tab"][href="#rc"]').on('shown.bs.tab', function () {
                                showRCImage(0);
                            });



                            var rcData = vehicle_details?.rc_details?.response?.vehicle_details ??
                                {};

                            let rcHtml = '';

                            Object.entries(rcData).forEach(([key, value]) => {

                                if (value === null || value === undefined || value === '')

                                    return;

                                const formattedKey = key

                                    .replace(/_/g, ' ')

                                    .split(' ')

                                    .map(word => word.charAt(0).toUpperCase() + word.slice(

                                        1))

                                    .join(' ');

                                rcHtml += `

                                 <tr>

                                     <td><i class="fas fa-info-circle me-2 text-primary"></i><strong>${formattedKey}</strong></td>

                                     <td>:</td>

                                     <td>${value}</td>

                                 </tr>`;

                            });

                            info_body.append(rcHtml);

                            let rcStatus = vehicle_details?.rc_front_admin_status || 'default';

                            var {

                                class: badgeClass,

                                icon,

                                text

                            } = statusMap[rcStatus] || statusMap.default;

                            $('#verifyStatusLabel_v')

                                .attr('class',

                                    `badge fs-6 px-3 py-2 rounded-pill shadow-sm ${badgeClass}`)

                                .html(`<i class="fa-solid ${icon} me-2"></i> ${text}`);

                            $('#exp_txt').text(formatExpiryDate(vehicle_details?.rc_expiry_date));

                        } else if (txt == 'INSURANCE') {

                            // INSURANCE TAB LOGIC

                            $('#infoTitle').text('Insurance Summary');

                            $('#verifyStatusLabel_v').show();

                            $('#expiryLabel').show();

                            $('#insuranceImg').attr('src', vehicle_details?.insurance_details

                                ?.insurance_image_url ?? placeholder).attr('data-img-alt',

                                'Insurance').attr('data-img-st', vehicle_details

                                ?.insurance_details?.insurance_admin_status);

                            showImage($('#insuranceCarousel'), 0);

                            var in_Data = vehicle_details?.rc_details?.response?.finance_details ??
                            {};

                            let inHtml = '';

                            inHtml += `

                            <tr>

                                <td><i class="fas fa-info-circle me-2 text-primary"></i><strong>Vehicle No.</strong></td>

                                <td>:</td>

                                <td>${vehicle_details?.rc_number}</td>

                            </tr>

                        `;

                            Object.entries(in_Data).forEach(([key, value]) => {

                                if (value === null || value === undefined || value === '')

                                    return;

                                const formattedKey = key

                                    .replace(/_/g, ' ')

                                    .split(' ')

                                    .map(word => word.charAt(0).toUpperCase() + word.slice(

                                        1))

                                    .join(' ');

                                inHtml += `

                                 <tr>

                                     <td><i class="fas fa-info-circle me-2 text-primary"></i><strong>${formattedKey}</strong></td>

                                     <td>:</td>

                                     <td>${value}</td>

                                 </tr>`;

                            });

                            info_body.append(inHtml);

                            let insStatus = vehicle_details?.insurance_details

                                ?.insurance_admin_status || 'default';

                            var {

                                class: badgeClass,

                                icon,

                                text

                            } = statusMap[insStatus] || statusMap.default;

                            $('#verifyStatusLabel_v')

                                .attr('class',

                                    `badge fs-6 px-3 py-2 rounded-pill shadow-sm ${badgeClass}`)

                                .html(`<i class="fa-solid ${icon} me-2"></i> ${text}`);

                            $('#exp_txt').text(formatExpiryDate(vehicle_details?.insurance_details

                                ?.insurance_exp_date));

                        } else if (txt == 'FC') {

                            // FC TAB LOGIC

                            $('#infoTitle').text('FC Details Summary');

                            $('#verifyStatusLabel_v').show();

                            $('#expiryLabel').show();

                            $('#fcImg').attr('src', fc_details?.fc_image_url ?? placeholder).attr(

                                'data-img-alt', 'FC Book').attr('data-img-st', fc_details

                                ?.fc_admin_status);

                            showImage($('#fcCarousel'), 0);

                            var fcData = vehicle_details?.rc_details?.response?.vehicle_details ??
                                {};

                            let fcHtml = '';

                            Object.entries(fcData).forEach(([key, value]) => {

                                if (value === null || value === undefined || value === '')

                                    return;

                                const formattedKey = key

                                    .replace(/_/g, ' ')

                                    .split(' ')

                                    .map(word => word.charAt(0).toUpperCase() + word.slice(

                                        1))

                                    .join(' ');

                                fcHtml += `

                                 <tr>

                                     <td><i class="fas fa-info-circle me-2 text-primary"></i><strong>${formattedKey}</strong></td>

                                     <td>:</td>

                                     <td>${value}</td>

                                 </tr>`;

                            });

                            info_body.append(fcHtml);

                            let fcStatus = fc_details?.fc_admin_status || 'default';

                            var {

                                class: badgeClass,

                                icon,

                                text

                            } = statusMap[fcStatus] || statusMap.default;

                            $('#verifyStatusLabel_v')

                                .attr('class',

                                    `badge fs-6 px-3 py-2 rounded-pill shadow-sm ${badgeClass}`)

                                .html(`<i class="fa-solid ${icon} me-2"></i> ${text}`);

                            $('#exp_txt').text(formatExpiryDate(fc_details?.fc_exp_date));

                        }

                        // Vehicle Approval Logic Setup

                        // let adminStatuses = {

                        //     back_view_admin_status: veh?.back_view_admin_status,

                        //     boot_admin_status: veh?.boot_admin_status,

                        //     car_top_view_admin_status: veh?.car_top_view_admin_status,

                        //     extra_image_1_admin_status: veh?.extra_image_1_admin_status,

                        //     extra_image_2_admin_status: veh?.extra_image_2_admin_status,

                        //     front_view_admin_status: veh?.front_view_admin_status,

                        //     interior_front_admin_status: veh?.interior_front_admin_status,

                        //     interior_rear_admin_status: veh?.interior_rear_admin_status,

                        //     side_view_admin_status: veh?.side_view_admin_status,

                        //     special_features_admin_status: veh?.special_features_admin_status,

                        //     fc_admin_status: fc_details?.fc_admin_status,

                        //     insurance_admin_status: vehicle_details?.insurance_details

                        //         ?.insurance_admin_status,

                        //     puc_admin_status: vehicle_details?.puc_details?.puc_admin_status,

                        //     rc_front_admin_status: vehicle_details?.rc_front_admin_status,

                        //     rc_back_admin_status: vehicle_details?.rc_back_admin_status

                        // };
                        
                        let adminStatuses = {

                            back_view_admin_status: '',

                            boot_admin_status: '',

                            car_top_view_admin_status: '',

                            extra_image_1_admin_status: '',

                            extra_image_2_admin_status: '',

                            front_view_admin_status: '',

                            interior_front_admin_status: '',

                            interior_rear_admin_status: '',

                            side_view_admin_status: '',

                            special_features_admin_status: '',

                            fc_admin_status: '',

                            insurance_admin_status: '',

                            puc_admin_status: '',

                            rc_front_admin_status: '',

                            rc_back_admin_status: ''

                        };

                        let adminStatusesJson = encodeURIComponent(JSON.stringify(adminStatuses));

                        $('#approveVehicleBtn').attr(

                            'onclick',

                            `approveVehicle('${data.id}', '${adminStatusesJson}')`

                        );

                        $('#rejectVehicleBtn').attr(

                            'onclick',

                            `rejectVehicle('${data.id}', '${adminStatusesJson}')`

                        );

                    } else {

                        toast('error', 'Vehicle Details Not Uploaded');

                    }

                },

                error: function(xhr, status, error) {

                    console.error("❌ Error loading data:", {

                        status,

                        error,

                        responseText: xhr.responseText

                    });

                    $("#carInfoTable-body").html(

                        "<tr><td colspan='3' class='text-center text-danger'>Error loading Vehicle data!</td></tr>"

                    );

                }

            });

        }

        // --- Tab Click Handlers ---

        loadKycData("AADHAR");

        loadFormData(userID);

        loadVehicleData("VEHICLE");

        $("#aadhar-tab").on("click", function() {

            loadKycData("AADHAR");

        });

        $("#dl-tab").on("click", function() {

            loadKycData("DRIVING_LICENSE");

        });

        $("#gst-tab").on("click", function() {

            loadKycData("GST");

        });

        // Vehicle Tabs now call loadVehicleData with the appropriate document type

        $("#car-verification-tab").on("click", function() {

            loadVehicleData("VEHICLE");

        });

        $("#puc-tab").on("click", function() {

            loadVehicleData("PUC");

        });

        $("#rc-tab").on("click", function() {

            loadVehicleData("RC");

        });

        $("#insurance-tab").on("click", function() {

            loadVehicleData("INSURANCE");

        });

        $("#fc-tab").on("click", function() {

            loadVehicleData("FC");

        });

        let map, marker, currentLat, currentLng;

        $(document).on("click", "#openLocationModal", function() {

            let id = $(this).data('id');

            $.ajax({

                url: origin + "/ajax/service/kycAddtionalServer.php",

                type: "POST",

                data: {

                    method: 'get_kyc_location',

                    id: id

                },

                dataType: "json",

                success: function(res) {

                    if (res.type === 1) {

                        currentLat = parseFloat(res.result.s_lat);

                        currentLng = parseFloat(res.result.s_lang);

                        $('#latValue').text(currentLat);

                        $('#longValue').text(currentLng);

                        $('#locationModal').modal('show');

                    }

                }

            });

        });

        $('#locationModal').on('shown.bs.modal', function() {

            if (!currentLat || !currentLng) return;

            let location = [currentLat, currentLng];

            if (!map) {

                map = L.map('map').setView(location, 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {

                    attribution: '&copy; OpenStreetMap contributors'

                }).addTo(map);

                marker = L.marker(location).addTo(map);

            } else {

                map.setView(location, 15);

                marker.setLatLng(location);

            }

            setTimeout(() => {

                map.invalidateSize();

            }, 200);

        });

        $("#sample_name").on("change", function() {

            var template = $('#sample_name').val();

            $.ajax({

                url: origin + "/ajax/service/kycAddtionalServer.php",

                type: "POST",

                data: {

                    method: 'get_temp_content',

                    template: template

                },

                dataType: "json",

                success: function(res) {

                    $('#waMessage').val(res.result.body);

                },

                complete: function() {

                    $("#sendWhatsapp").prop("disabled", false).html(

                        '<i class="bi bi-send-fill"></i> Send Message');

                    // // console.log("Request completed.");

                }

            });

        });

        $("#wh_icon").on("click", function() {

            let number = $("#waNumber").val().trim();

            $.ajax({

                url: origin + "/ajax/service/kycAddtionalServer.php",

                type: "POST",

                data: {

                    method: 'get_whats_content',

                    number: number

                },

                dataType: "json",

                beforeSend: function() {

                    $("#sendWhatsapp").prop("disabled", true).html("Sending...");

                    // // console.log("Sending request...");

                },

                success: function(res) {

                    $('#user_name').text(user.name);

                    $("#waHistoryBody").empty();

                    if (res.type == 1 && Array.isArray(res.result)) {

                        let rows = "";

                        res.result.forEach(function(row) {

                            rows += `

                        <tr>

                            <td>${row.created_at}</td>

                            <td>${row.whatapp_campain ?? '-'}</td>

                            <td>${row.to_whatsapp}</td>

                            <td>${row.details}</td>

                        </tr>

                    `;

                        });

                        $("#waHistoryBody").html(rows);

                    } else {

                        $("#waHistoryBody").html(`

                    <tr>

                        <td colspan="4" class="text-center text-danger">${res.result}</td>

                    </tr>

                `);

                    }

                },

                complete: function() {

                    $("#sendWhatsapp").prop("disabled", false).html(

                        '<i class="bi bi-send-fill"></i> Send Message');

                    // // console.log("Request completed.");

                }

            });

        });

        $("#sendWhatsapp_m").on("click", function() {

            // // console.log('hiiiiiiiiiii')

            let number = $("#waNumber").val().trim();

            let message = $("#waMessage").val().trim();

            if (number == "" || message == "") {

                toast('error', "Please fill all fields");

                return;

            }

            $.ajax({

                url: origin + "/ajax/service/kycAddtionalServer.php",

                type: "POST",

                data: {

                    method: 'send_whats_content',

                    number: number,

                    message: message

                },

                dataType: "json",

                beforeSend: function() {

                    $("#sendWhatsapp").prop("disabled", true).html("Sending...");

                    // // console.log("Sending request...");

                },

                success: function(res) {

                    if (res.type == '1') {

                        toast('success', res.result);

                    } else {

                        toast('error', res.result);

                    }

                },

                complete: function() {

                    $("#sendWhatsapp").prop("disabled", false).html(

                        '<i class="bi bi-send-fill"></i> Send Message');

                    // // console.log("Request completed.");

                }

            });

        });

    });

    // function loadFormData(uId) {

    //     $.ajax({

    //         url: origin + "/ajax/service/kycAddtionalServer.php",

    //         type: "POST",

    //         data: { method: "getFormsList", user_id: uId },

    //         dataType: "json",

    //         success: function(res) {

    //             let html = "";

    //             // If no data

    //             if (res.type !== 1) {

    //                 html += `<div class="form-row">No forms available</div>`;

    //                 $("#formsList").html(html);

    //                 $("#modalForm").modal("show");

    //                 return;

    //             }

    //             // Loop forms easily

    //             res.result.forEach(form => {

    //                 html += `

    //                     <div class="form-row">

    //                         <div class="form-meta">

    //                             <div class="form-title">${form.name}</div>

    //                         </div>

    //                         <div class="form-actions">

    //                             <button class="btn-premium btn-generate" data-id="${form.id}" data-userid="${uId}">

    //                                 Generate

    //                             </button>

    //                             <button class="btn-premium btn-whatsapp" data-id="${form.id}" data-userid="${uId}">

    //                                 WhatsApp

    //                             </button>

    //                         </div>

    //                     </div>

    //                 `;

    //             });

    //             // Insert all rows

    //             $("#formsList").html(html);

    //             // Show modal

    //             // $("#modalForm").modal("show");

    //         },

    //         error: function() {

    //             alert("Something went wrong.");

    //         }

    //     });

    // }

    $(document).on("click", ".btn-preview", function() {

        let url = $(this).data("url");

        // $("#pdfFrame").attr("src", url);

        $("#pdfFrame").attr("srcdoc", `

        <html>

        <body style="margin:0; padding:0; overflow:hidden;">

            <object 

                data="${url}" 

                type="application/pdf"

                style="width:100%; height:100vh; border:0;">

            </object>

        </body>

        </html>

    `);

        $("#pdfPreviewModal").modal("show");

    });

    $('#pdfPreviewModal').on('hidden.bs.modal', function() {

        // $("#pdfFrame").attr("src", "about:blank");

        $("#pdfFrame").attr("srcdoc", "");

    });

    function loadFormData(uId) {

        $.ajax({

            url: origin + "/ajax/service/kycAddtionalServer.php",

            type: "POST",

            data: {

                method: "getFormsList",

                user_id: uId

            },

            dataType: "json",

            success: function(res) {

                // console.log(res);

                let html = "";

                if (res.type !== 1) {

                    html += `<div class="form-row">No forms available</div>`;

                    $("#formsList").html(html);

                    return;

                }

                res.result.forEach(form => {

                    // If empty_url is NOT null → Show Preview button

                    let actionButton = "";

                    if (form.empty_url && form.empty_url !== "" && form.empty_url !== null) {

                        actionButton = `

                        <button class="btn-premium btn-preview" data-url="${form.empty_url}">

                            Preview

                        </button>

                        <button class="btn-premium btn-regenerate" data-id="${form.id}" data-userid="${uId}" data-authid="${res.auth_id}">

                            <i class="bi bi-arrow-clockwise"></i>

                        </button>

                    `;

                    } else {

                        actionButton = `

                        <button class="btn-premium btn-generate" data-id="${form.id}" data-userid="${uId}" data-authid="${res.auth_id}">

                            Generate

                        </button>

                    `;

                    }

                    html += `

                    <div class="form-row">

                        <div class="form-meta">

                            <div class="form-title">${form.name}</div>

                        </div>

                        <div class="form-actions">

                            ${actionButton}

                            <button class="btn-premium btn-whatsapp" data-id="${form.id}" data-userid="${uId}">

                                WhatsApp

                            </button>

                        </div>

                    </div>

                `;

                });

                if (res.user_role === 'Owner') {

                    $('#roleSwitch').prop('checked', true);

                } else {

                    $('#roleSwitch').prop('checked', false);

                }

                // Update UI Highlight (Optional but Recommended)

                setTimeout(() => {

                    $("#roleSwitch").trigger("change");

                }, 50);

                $("#formsList").html(html);

            },

            error: function() {

                alert("Something went wrong.");

            }

        });

    }

    document.addEventListener('click', function(e) {

        const genBtn = e.target.closest('.btn-generate');

        if (genBtn) {

            var id = genBtn.dataset.id;

            var userid = genBtn.dataset.userid;

            var auth_id = genBtn.dataset.authid;

            // console.log("Generate clicked for Form ID:", id);

            if (id == "1") {

                $.ajax({

                    url: '<?= API_DOMAIN_2 ?>consent/create',

                    type: "POST",

                    data: {

                        user_id: userid,

                        auth_id: auth_id,

                        auth_key: 'ASDFGHJKLqwertyuiopMNBVCXZ!@#$%^&*()0987612345'

                    },

                    dataType: "json",

                    beforeSend: function() {

                        $(genBtn)

                            .prop("disabled", true)

                            .html("Generating...");

                    },

                    success: function(res) {

                        if (res.type == '1') {

                            toast('success', res.result);

                        } else {

                            toast('error', res.result);

                        }

                        loadFormData(userid)

                    },

                    complete: function() {

                        $(genBtn)

                            .prop("disabled", false).html("Generate");

                    }

                });

            } else {

                // For other form IDs

                // console.log("Generate action for other Form:", id);

            }

            return;

        }

        const regenBtn = e.target.closest('.btn-regenerate');

        if (regenBtn) {

            var id = regenBtn.dataset.id;

            var userid = regenBtn.dataset.userid;

            var auth_id = regenBtn.dataset.authid;

            // // console.log("Generate clicked for Form ID:", id);

            if (id == "1") {

                $.ajax({

                    url: '<?= API_DOMAIN_2 ?>consent/create',

                    type: "POST",

                    data: {

                        user_id: userid,

                        auth_id: auth_id,

                        auth_key: 'ASDFGHJKLqwertyuiopMNBVCXZ!@#$%^&*()0987612345'

                    },

                    dataType: "json",

                    beforeSend: function() {

                        $(regenBtn)

                            .prop("disabled", true)

                            .html("Generating...");

                    },

                    success: function(res) {

                        if (res.type == '1') {

                            toast('success', res.result);

                        } else {

                            toast('error', res.result);

                        }

                        loadFormData(userid)

                    },

                    complete: function() {

                        $(regenBtn)

                            .prop("disabled", false).html(`<i class="bi bi-arrow-clockwise"></i>`);

                    }

                });

            } else {

                // For other form IDs

                // console.log("Generate action for other Form:", id);

            }

            return;

        }

        const waBtn = e.target.closest('.btn-whatsapp');

        if (waBtn) {

            var id = waBtn.dataset.id;

            var userid = waBtn.dataset.userid;

            // console.log("WhatsApp clicked for Form ID:", id);

            Swal.fire({

                title: "Are you sure?",

                text: "Do you want to send this PDF to the user?",

                icon: "warning",

                showCancelButton: true,

                confirmButtonText: "Yes, Send",

                cancelButtonText: "Cancel",

            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({

                        url: origin + "/ajax/service/kycAddtionalServer.php",

                        type: "POST",

                        data: {

                            id: id,

                            user_id: userid,

                            method: 'sent_forms'

                        },

                        dataType: "json",

                        beforeSend: function() {

                            $(waBtn)

                                .prop("disabled", true)

                                .html("Sending...");

                        },

                        success: function(res) {

                            if (res.type == '1') {

                                toast('success', res.result);

                            } else {

                                toast('error', res.result);

                            }

                            // $('#modalForm').hide();

                        },

                        complete: function() {

                            $(waBtn)

                                .prop("disabled", false)

                                .html('Whatsapp');

                        }

                    });

                } else {

                    // User clicked CANCEL → do nothing

                    toast('info', 'Sending cancelled.');

                }

            });

            return;

        }

    });

    document.getElementById("roleSwitch").addEventListener("change", function(use) {

        let role = this.checked ? "Owner" : "Driver";

        // // console.log("Selected Role:", role);

        $.ajax({

            url: origin + "/ajax/service/kycAddtionalServer.php",

            type: "POST",

            data: {

                method: 'switch_role',

                role: role,

                user_id: <?= json_encode($userID) ?>

            },

            dataType: "json",

            success: function(res) {

                if (res.type == '1') {

                    toast('success', res.result);

                } else {

                    toast('error', res.result);

                }

                location.reload();

            },

            error: function(err) {

                toast('error', 'Something went wrong!');

            }

        });

    });

    function formatExpiryDate(dateString) {

        if (!dateString) return '-';

        const date = new Date(dateString);

        if (isNaN(date)) return '-'; // handle invalid dates safely

        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",

            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"

        ];

        return `${date.getDate().toString().padStart(2, '0')} ${months[date.getMonth()]} ${date.getFullYear()}`;

    }

    function approveVehicle(id, stsJson) {

        const sts = JSON.parse(decodeURIComponent(stsJson));
        
        // console.log(sts);
        
        $('#v_apr_tit').text('Vehicle Approve Details');

        const $container = $('#vehicleStatusContainer');

        $container.empty();
        
        $container.append(`
            <div class="form-check mb-3 border-bottom pb-2">
                <input class="form-check-input" type="checkbox" id="selectAllVehicles">
                <label class="form-check-label fw-bold text-primary" for="selectAllVehicles">
                    Select All
                </label>
            </div>
        `);

        Object.keys(sts).forEach(key => {

            // Clean label text (replace underscores, remove 'admin_status', capitalize words)

            const label = key

                .replace(/_admin_status/g, '')

                .replace(/_/g, ' ')

                .replace(/\b\w/g, c => c.toUpperCase());

            const checked = sts[key] === 'approved' ? 'checked' : '';

            $container.append(`

            <div class="form-check mb-2 vehicle-checkbox">

                <input class="form-check-input vehicle-status" type="checkbox" id="${key}" ${checked}>

                <label class="form-check-label fw-bold" for="${key}">

                    ${label}

                </label>

            </div>

        `);

        });

        // Append WhatsApp checkbox last

        $container.append(`

        <div class="form-check mt-3">

            <input class="form-check-input" type="checkbox" id="sendWhatsapp_v">

            <label class="form-check-label fw-bold text-success" for="sendWhatsapp_v">

                Send WhatsApp Message to User

            </label>

            <p class="text-muted small mb-0 ms-4">

                A confirmation message will be sent to the user once KYC approval is saved.

            </p>

        </div>

    `);
    
        bindSelectAllVehicleCheckbox();

        $('#saveVehicleStatus').attr('onclick', 'saveVehicle(' + id + ')');

        $('#VehicleApproveModal').modal('show');

    }

    function rejectVehicle(id, stsJson) {

        const sts = JSON.parse(decodeURIComponent(stsJson));
        
        $('#v_apr_tit').text('Vehicle Reject Details');

        const $container = $('#vehicleStatusContainer');

        $container.empty();
        
        $container.append(`
            <div class="form-check mb-3 border-bottom pb-2">
                <input class="form-check-input" type="checkbox" id="selectAllVehicles">
                <label class="form-check-label fw-bold text-primary" for="selectAllVehicles">
                    Select All
                </label>
            </div>
        `);

        Object.keys(sts).forEach(key => {

            const label = key

                .replace(/_admin_status/g, '')

                .replace(/_/g, ' ')

                .replace(/\b\w/g, c => c.toUpperCase());

            const checked = '';

            $container.append(`

            <div class="form-check mb-2 vehicle-checkbox">

                <input class="form-check-input vehicle-status" type="checkbox" id="${key}" ${checked}>

                <label class="form-check-label fw-bold" for="${key}">

                    ${label}

                </label>

            </div>

        `);

        });

        $container.append(`<div class="mb-3 rej-remark">

        <label for="v_Remarks" class="form-label fw-bold">Remarks / Notes</label>

        <textarea class="form-control" id="v_Remarks" rows="3" placeholder="Enter remarks briefly or reason for Vehicle rejection..."></textarea>

    </div>`);

        $container.append(`

        <div class="form-check mt-3">

            <input class="form-check-input" type="checkbox" id="sendWhatsapp_v">

            <label class="form-check-label fw-bold text-success" for="sendWhatsapp_v">

                Send WhatsApp Message to User

            </label>

            <p class="text-muted small mb-0 ms-4">

                A confirmation message will be sent to the user once KYC approval is saved.

            </p>

        </div>

    `);
    
    bindSelectAllVehicleCheckbox();

        $('#saveVehicleStatus').attr('onclick', 'rejVehicle(' + id + ')');

        $('#VehicleApproveModal').modal('show');

    }
    
    function bindSelectAllVehicleCheckbox() {
    
        // When Select All is toggled
        $('#selectAllVehicles').on('change', function () {
            const isChecked = $(this).is(':checked');
            $('.vehicle-status').prop('checked', isChecked);
        });
    
        // When any individual checkbox is changed
        $(document).on('change', '.vehicle-status', function () {
    
            const total = $('.vehicle-status').length;
            const checked = $('.vehicle-status:checked').length;
    
            $('#selectAllVehicles').prop('checked', total === checked);
        });
    }


    function saveVehicle(id) {

        const $checkboxes = $('#VehicleApproveModal .form-check-input').not('#sendWhatsapp_v');

        const allChecked = $checkboxes.length > 0 && $checkboxes.toArray().every(cb => cb.checked);

        if (!allChecked) {

            toast('error', 'Please check all required vehicle fields before proceeding.');

            return;

        }

        const data = {};

        $checkboxes.each(function() {

            data[$(this).attr('id')] = this.checked ? 'approved' : 'pending';

        });

        data.user_id = id;

        data.method = 'vehicleApprove';

        data.isWhatsapp = $('#sendWhatsapp_v').is(':checked') ? 'yes' : 'no';

        $.ajax({

            url: origin + "/ajax/service/datatable_services.php",

            method: 'POST',

            data,

            success: function(res) {

                let data = JSON.parse(res);

                if (data.type == '1') {

                    toast('success', data.result);

                    location.reload()

                } else {

                    toast('error', data.result);

                }

                $('#KycApproveModal').modal('hide');

                // loadKycData()

            },

            error: function(err) {

                console.error(err);

                alert('Something went wrong!');

            }

        });

    }

    function rejVehicle(id) {

        const $checkboxes = $('#VehicleApproveModal .form-check-input').not('#sendWhatsapp_v, #v_Remarks');

        const atLeastOneChecked = $checkboxes.toArray().some(cb => cb.checked);

        if (!atLeastOneChecked) {

            toast('error', 'Please select at least one vehicle field and remark required before proceeding.');

            return;

        }

        if ($('#v_Remarks').val() == '' || $('#v_Remarks').val() == undefined || $('#v_Remarks').val() == null) {

            toast('error', 'Remark field required before proceeding.');

            return;

        }

        const data = {};

        $checkboxes.each(function() {

            data[$(this).attr('id')] = this.checked ? 'rejected' : '';

        });

        data.user_id = id;

        data.method = 'vehicleReject';

        data.isWhatsapp = $('#sendWhatsapp_v').is(':checked') ? 'yes' : 'no';

        data.remark = $('#v_Remarks').val();

        $.ajax({

            url: origin + "/ajax/service/datatable_services.php",

            method: 'POST',

            data,

            success: function(res) {

                let data = JSON.parse(res);

                if (data.type == '1') {

                    toast('success', data.result);

                    location.reload()

                } else {

                    toast('error', data.result);

                }

                $('#KycApproveModal').modal('hide');

                // loadKycData()

            },

            error: function(err) {

                console.error(err);

                alert('Something went wrong!');

            }

        });

    }

    function saveKyc(id, txt) {

        const data = {

            method: 'kycApprove',

            kyc_id: id,

            selfie_status: $('#selfieStatus').is(':checked') ? 'approved' : 'Inreview',

            aadhar_status: $('#aadharStatus').is(':checked') ? 'approved' : 'Inreview',

            dl_status: $('#dlStatus').is(':checked') ? 'approved' : 'Inreview',

            send_whatsapp: $('#sendWhatsapp').is(':checked') ? 'yes' : 'no',

            gst_status: $('#gstStatus').is(':checked') ? 'approved' : 'Inreview'

        };

        // // console.log(txt)

        // let isValid = 

        //   $('#selfieStatus, #aadharStatus').filter(':checked').length === 2 && 

        //   (

        //     (txt === 'Driver' && $('#dlStatus').is(':checked')) || 

        //     (txt === 'Owner' && $('#gstStatus').is(':checked'))

        //   );

        if (true) {

            $.ajax({

                url: origin + "/ajax/service/datatable_services.php",

                method: 'POST',

                data: {

                    ...data,

                },

                success: function(res) {

                    let data = JSON.parse(res);

                    if (data.type == '1') {

                        toast('success', data.result);

                        location.reload()

                    } else {

                        toast('error', data.result);

                    }

                    $('#KycApproveModal').modal('hide');

                    // loadKycData()

                },

                error: function(err) {

                    console.error(err);

                    alert('Something went wrong!');

                }

            });

        } else {

            toast('error', 'Please check all KYC fields before proceeding.');

        }

    }

    function checkGST(id) {

        if (id != '') {

            let $btn = $('#searchBtn');

            $.ajax({

                url: origin + "/ajax/service/GSTBotService.php",

                type: 'POST',

                dataType: "json",

                data: {

                    method: "gst_details",

                    id: id

                },

                beforeSend: function() {

                    $btn.prop('disabled', true).html('Searching...');

                },

                complete: function() {

                    // Re-enable button and restore text

                    $btn.prop('disabled', false).html('Search');

                },

                success: function(response) {

                    let data = response.result ?? null;

                    let data2 = response.data ?? null;

                    let html = '';

                    if (response.type == 1) {

                        // Use data directly, no JSON.parse

                        let details = JSON.parse(data) || null;

                        if (details) {

                            html += `

                                <div class="row mb-2">

                                    <h4 class="col-12 text-start fw-bold pe-2">GST Details :</h4>

                                </div>

                                <hr>

                            `;

                            for (let key in details) {

                                if (details.hasOwnProperty(key)) {

                                    html += `

                                        <div class="row mb-2">

                                            <div class="col-5 text-start fw-bold pe-2">${key}</div>

                                            <div class="col-1 text-center">:</div>

                                            <div class="col-6 text-end">${details[key]}</div>

                                        </div>

                                    `;

                                }

                            }

                            // Status toggle logic (optional)

                            // let isVerify = false;

                            // if (data2.status && (data2.status === 'approved' || data2.status === 'Verified')) {

                            //     isVerify = true;

                            //     $('#confirmBlockModal .modal-title').html(

                            //         'Kyc Details <span class="badge bg-success">Verified</span>');

                            // } else {

                            //     $('#confirmBlockModal .modal-title').html(

                            //         'Kyc Details <span class="badge bg-secondary">Not Verified</span>');

                            // }

                        }

                    } else {

                        // Handle other types

                        toast('error', data)

                    }

                    // $('#gst_search').remove();

                    $('#gst_details').html(html);

                },

                error: function(xhr, status, error) {

                    console.error('Error:', error);

                }

            });

        }

    }

    function rejKyc(id, txt) {

        const data = {

            method: 'kycReject',

            kyc_id: id,

            remark: $('#kycRemarks').val(),

            selfie_status: $('#selfieStatus').is(':checked') ? 'rejected' : '',

            aadhar_status: $('#aadharStatus').is(':checked') ? 'rejected' : '',

            dl_status: $('#dlStatus').is(':checked') ? 'rejected' : '',

            send_whatsapp: $('#sendWhatsapp').is(':checked') ? 'yes' : 'no',

            gst_status: $('#gstStatus').is(':checked') ? 'rejected' : ''

        };

        let isValid = (txt === 'Driver' && $('#selfieStatus, #aadharStatus, #dlStatus').filter(':checked').length >

                0) ||

            (txt === 'Owner' && $('#selfieStatus, #aadharStatus, #dlStatus, #gstStatus').filter(':checked').length > 0);

        if (isValid) {

            if ($('#kycRemarks').val() != '') {

                $.ajax({

                    url: origin + "/ajax/service/datatable_services.php",

                    method: 'POST',

                    data: {

                        ...data,

                    },

                    success: function(res) {

                        let data = JSON.parse(res);

                        if (data.type == '1') {

                            toast('success', data.result);

                            location.reload()

                        } else {

                            toast('error', data.result);

                        }

                        $('#KycApproveModal').modal('hide');

                        // loadKycData()

                    },

                    error: function(err) {

                        console.error(err);

                        alert('Something went wrong!');

                    }

                });

            } else {

                toast('error', 'Remark field required.');

            }

        } else {

            toast('error', 'Please check at least one KYC field before proceeding.');

        }

    }

    function approveKYC(txt, sel = null, aadh = null, dl = null, id) {

        if (txt == 'Driver') {

            if (sel == 'approved' || sel == 'approval_pending') {

                $('#selfieStatus').prop('checked', true)

            } else {

                $('#selfieStatus').prop('checked', false)

            }

            if (aadh == 'approved' || aadh == 'approval_pending') {

                $('#aadharStatus').prop('checked', true)

            } else {

                $('#aadharStatus').prop('checked', false)

            }

            if (dl == 'approved' || dl == 'approval_pending') {

                $('#dlStatus').prop('checked', true)

            } else {

                $('#dlStatus').prop('checked', false)

            }

            $('.owner-div').hide()

            $('.driver-div').show()

        } else {

            if (sel == 'approved' || sel == 'approval_pending') {

                $('#selfieStatus').prop('checked', true)

            } else {

                $('#selfieStatus').prop('checked', false)

            }

            if (aadh == 'approved' || aadh == 'approval_pending') {

                $('#aadharStatus').prop('checked', true)

            } else {

                $('#aadharStatus').prop('checked', false)

            }

            if (dl == 'approved' || dl == 'approval_pending') {

                $('#gstStatus').prop('checked', true)

            } else {

                $('#gstStatus').prop('checked', false)

            }

            $('.driver-div').hide()

            $('.owner-div').show()

        }

        $('.rej-remark').hide();

        $('#saveKycStatus').attr('onclick', 'saveKyc("' + id + '", "' + txt + '")');

        $('#KycApproveModal').modal('show');

    }

    function rejectKYC(txt, sel = null, aadh = null, dl = null, id) {

        if (txt == 'Driver') {

            $('.owner-div').hide()

            $('.driver-div').show()

        } else {

            $('.driver-div').hide()

            $('.owner-div').show()

        }

        $('#selfieStatus').prop('checked', false)

        $('#aadharStatus').prop('checked', false)

        $('#dlStatus').prop('checked', false)

        $('#gstStatus').prop('checked', false)

        $('.rej-remark').show();

        $('#saveKycStatus').attr('onclick', 'rejKyc("' + id + '", "' + txt + '")');

        $('#KycApproveModal').modal('show');

    }
    </script>

    <!-- vehicle verify -->

    <script>
    document.addEventListener("DOMContentLoaded", function() {

        const activeTab = localStorage.getItem("activeTab");

        const userTabBtn = document.querySelector('#user-tab');

        const vehicleTabBtn = document.querySelector('#vehicle-tab');

        const userTabContent = document.querySelector('#user');

        const vehicleTabContent = document.querySelector('#vehicle');

        let tabToActivate = activeTab || "USER";

        if (tabToActivate === "USER") {

            // if (vehicleTabBtn) vehicleTabBtn.closest('li').style.display = "none";

            // if (vehicleTabContent) vehicleTabContent.style.display = "none";

            if (userTabBtn) {

                const tab = new bootstrap.Tab(userTabBtn);

                tab.show();

            }

        } else if (tabToActivate === "VEHICLE") {

            // if (userTabBtn) userTabBtn.closest('li').style.display = "none";

            // if (userTabContent) userTabContent.style.display = "none";

            if (vehicleTabBtn) {

                const tab = new bootstrap.Tab(vehicleTabBtn);

                tab.show();

            }

        }

        const tabTriggers = document.querySelectorAll('[data-bs-toggle="tab"]');

        tabTriggers.forEach(tab => {

            tab.addEventListener('shown.bs.tab', function(event) {

                const selectedTab = event.target.getAttribute('id');

                if (selectedTab === 'user-tab') {

                    localStorage.setItem("activeTab", "USER");

                } else if (selectedTab === 'vehicle-tab') {

                    localStorage.setItem("activeTab", "VEHICLE");

                }

            });

        });

    });
    
    var url01 = origin + "/ajax/service/schedule_job.php";
    
    $('#users_id').on('select2:select', function (e) {
        const data = e.params.data;

        if (data.id && data.kyc_id) {
            const redirectUrl =
                `https://console.goride.run/kyc-verify/verify/${data.id}/${data.kyc_id}`;

            window.open(redirectUrl, '_blank');
        }else{
            toast('error', 'User didn\'t initiate KYC');
        }
    });
    
    function usersSelect(selector) {
        $(selector).select2({
            placeholder: "Choose user...",
            minimumInputLength: 2,
            width: "100%",
            ajax: {
                url: url01,
                type: "POST",
                data: function (params) {
                    return {
                        method: "get_users",
                        search: params.term
                    };
                },
                processResults: function (data) {
                    // console.log(data)
                    data = JSON.parse(data);
                    return {
                        results: data.data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.name + ' - ' + item.mobile,
                                kyc_id: item.kyc_id
                            };
                        })
                    };
                },
                error: function(xhr){
                    console.log("Error:", xhr.responseText);
                }
            }
        });
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
    </script>
    
    



    <!-- Additional Features  -->