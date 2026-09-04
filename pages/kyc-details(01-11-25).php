<?php
$pageTitle = "Verification Center";
if (isset($subid3)) {
    $userID_data = $subid3;
    $userID_kyc_id = $subid4;
    $userID_txt = $subid5;
}
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
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
}

.image-box img {
    position: absolute;
    top: 0;
    left: 0;
    cursor: grab;
    transform-origin: center center;
    user-select: none;
    transition: transform 0.15s ease-out;
}

.image-box img:active {
    cursor: grabbing;
}

.image-carousel img {
    max-width: 100%;
    height: 100%;
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
</style>
<script>
window.onload = function() {
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
        </div>
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-4">
                        <div class="grid-margin">
                            <!-- Nav Tabs -->
                            <ul class="nav nav-tabs mb-3 d-flex gap-3" id="verificationTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active d-flex align-items-center" id="user-tab"
                                        data-bs-toggle="tab" data-bs-target="#user" type="button" role="tab"
                                        aria-controls="user" aria-selected="true">
                                        <i class="fa-solid fa-user-check me-2"></i>
                                        <span>User Verification</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link d-flex align-items-center" id="vehicle-tab"
                                        data-bs-toggle="tab" data-bs-target="#vehicle" type="button" role="tab"
                                        aria-controls="vehicle" aria-selected="false">
                                        <i class="fa-solid fa-car-side me-2"></i>
                                        <span>Vehicle Verification</span>
                                    </button>
                                </li>
                            </ul>
                            <!-- Tab Content -->
                            <div class="tab-content" id="verificationTabContent">
                                <!-- USER TAB -->
                                <div class="tab-pane fade show active" id="user" role="tabpanel"
                                    aria-labelledby="user-tab">
                                    <div class="row g-3 mt-3 border p-2">
                                        <!-- LEFT COLUMN -->
                                        <div class="col-md-4 d-flex flex-column align-items-center">
                                            <div class="card mb-3 p-3 text-center" style="width: 300px; height: 300px;">
                                                <img id="selfieImage" src="" class="img-fluid"
                                                    style="width: 100%; height: 100%; object-fit: cover;"
                                                    alt="User Photo" />
                                            </div>
                                            <!-- User Info Summary -->
                                            <div class="card p-3" style="width: 100%; max-width: 400px;">
                                                <h6 class="card-title mb-3 text-center">
                                                    <i class="fa-solid fa-list text-primary"></i> <span
                                                        id="card-title-text">User Adhar Info</span>
                                                </h6>
                                                <table class="table table-bordered mb-0 align-middle">
                                                    <tbody id="info-body">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- RIGHT COLUMN -->
                                        <div class="col-md-8">
                                            <ul class="nav nav-pills mb-3" role="tablist">
                                                <li class="nav-item flex-grow-1 me-2" role="presentation">
                                                    <button
                                                        class="nav-link active d-flex align-items-center justify-content-center"
                                                        id="adhar-tab" data-bs-toggle="pill" data-bs-target="#adhar"
                                                        type="button" role="tab">
                                                        <i class="fa-solid fa-id-card me-2"></i> Aadhar Card
                                                    </button>
                                                </li>
                                                <li class="nav-item flex-grow-1" role="presentation">
                                                    <button
                                                        class="nav-link d-flex align-items-center justify-content-center"
                                                        id="dl-tab" data-bs-toggle="pill" data-bs-target="#dl"
                                                        type="button" role="tab">
                                                        <i class="fa-solid fa-address-card me-2"></i> DL Card
                                                    </button>
                                                </li>
                                            </ul>
                                            <!-- Inner Tabs -->
                                            <div class="tab-content">
                                                <!-- Aadhar Tab -->
                                                <div class="tab-pane fade show active" id="adhar" role="tabpanel"
                                                    aria-labelledby="adhar-tab">
                                                    <div class="card p-3 mb-3">
                                                        <h5 class="card-title">
                                                            <i class="fa-solid fa-id-card me-2"></i>Aadhar Card (Front &
                                                            Back)
                                                        </h5>
                                                        <div class="image-carousel text-center">
                                                            <div class="image-box">
                                                                <img id="aadharFront"
                                                                    src="https://via.placeholder.com/400x250?text=Aadhar+Front"
                                                                    class="img-fluid draggable-img" alt="Aadhar Front">
                                                            </div>
                                                            <div class="image-box d-none">
                                                                <img id="aadharBack"
                                                                    src="https://via.placeholder.com/400x250?text=Aadhar+Back"
                                                                    class="img-fluid draggable-img" alt="Aadhar Back">
                                                            </div>
                                                            <!-- Buttons in a new row -->
                                                            <div class="d-flex justify-content-end gap-2 mt-3">
                                                                <button
                                                                    class="btn btn-secondary prev-btn">Previous</button>
                                                                <button class="btn btn-primary next-btn">Next</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- DL Tab -->
                                                <div class="tab-pane fade" id="dl" role="tabpanel"
                                                    aria-labelledby="dl-tab">
                                                    <div class="card p-3 mb-3">
                                                        <h5 class="card-title">
                                                            <i class="fa-solid fa-address-card me-2"></i>Driving License
                                                            (Front & Back)
                                                        </h5>
                                                        <div class="image-carousel text-center">
                                                            <div class="image-box">
                                                                <img id="dlFront"
                                                                    src="https://via.placeholder.com/400x250?text=DL+Front"
                                                                    class="img-fluid draggable-img" alt="DL Front">
                                                            </div>
                                                            <div class="image-box d-none">
                                                                <img id="dlBack"
                                                                    src="https://via.placeholder.com/400x250?text=DL+Back"
                                                                    class="img-fluid draggable-img" alt="DL Back">
                                                            </div>
                                                            <!-- Buttons in a new row -->
                                                            <div class="d-flex justify-content-end gap-2 mt-3">
                                                                <button
                                                                    class="btn btn-secondary prev-btn">Previous</button>
                                                                <button class="btn btn-primary next-btn">Next</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ✅ Verification Complete Button -->
                                    <div class="text-end mt-3">
                                        <button type="button" class="btn btn-success px-4 py-2">
                                            <i class="fa-solid fa-check-circle me-2"></i> User Verify Complete
                                        </button>
                                        <button type="button" class="btn btn-danger px-4 py-2 ms-2" id="rejectBtn">
                                            <i class="fa-solid fa-times-circle me-2"></i> User Verify Reject
                                        </button>
                                    </div>
                                </div>
                                <!-- VEHICLE TAB -->
                                <div class="tab-pane fade" id="vehicle" role="tabpanel" aria-labelledby="vehicle-tab">
                                    <div class="row g-3 mt-3 border p-2">
                                        <!-- LEFT COLUMN -->
                                        <div class="col-md-4 d-flex flex-column align-items-center">
                                            <div class="card mb-3 p-3 text-center" style="width: 300px; height: 300px;">
                                                <img src="" class="img-fluid"
                                                    style="width: 100%; height: 100%; object-fit: cover;"
                                                    alt="Car Photo" />
                                            </div>
                                            <div class="card shadow-sm p-3" style="width: 100%; max-width: 400px;">
                                                <h6 class="card-title mb-3 text-center">
                                                    <i class="fa-solid fa-car text-primary"></i>
                                                    <span id="infoTitle">Car Info Summary</span>
                                                </h6>
                                                <div id="loadingSpinner" class="text-center my-2" style="display:none;">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                                <table class="table table-bordered mb-0 align-middle" id="carInfoTable">
                                                    <tbody>
                                                        <!-- Dynamic content will go here -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- RIGHT COLUMN -->
                                        <div class="col-md-8">
                                            <ul class="nav nav-pills mb-3 d-flex flex-wrap justify-content-between"
                                                id="carTabs" role="tablist">
                                                <li class="nav-item flex-fill me-2" role="presentation">
                                                    <button class="nav-link active" data-bs-toggle="pill"
                                                        data-bs-target="#car-verification" data-tab="car-verification"
                                                        id="car-verification-tab" type="button" role="tab">
                                                        <i class="fa-solid fa-check-circle me-2"></i> Car Verification
                                                    </button>
                                                </li>
                                                <li class="nav-item flex-fill me-2" role="presentation">
                                                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#puc"
                                                        data-tab="puc" id="puc-tab" type="button" role="tab">
                                                        <i class="fa-solid fa-gas-pump me-2"></i> PUC
                                                    </button>
                                                </li>
                                                <li class="nav-item flex-fill me-2" role="presentation">
                                                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#rc"
                                                        data-tab="rc" id="rc-tab" type="button" role="tab">
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
                                            </ul>
                                           <div class="tab-content">
                                                <!-- Car Verification -->
                                                <div class="tab-pane fade show active" id="car-verification" role="tabpanel">
                                                    <div class="card p-3 mb-3">
                                                        <h5><i class="fa-solid fa-check-circle me-2"></i> Car Photo (Front & Back)</h5>
                                                        <div class="image-carousel text-center">
                                                            <div class="image-box">
                                                                <img id="carFrontImg" src="" class="img-fluid" alt="Car Front"
                                                                    style="width:100%; height:300px; object-fit:contain;">
                                                            </div>
                                                            <div class="image-box d-none">
                                                                <img id="carBackImg" src="" class="img-fluid" alt="Car Back"
                                                                    style="width:100%; height:300px; object-fit:contain;">
                                                            </div>
                                                            <div class="d-flex justify-content-end gap-2 mt-3">
                                                                <button class="btn btn-secondary prev-btn">Previous</button>
                                                                <button class="btn btn-primary next-btn">Next</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                                <!-- PUC -->
                                                <div class="tab-pane fade" id="puc" role="tabpanel">
                                                    <div class="card p-3 mb-3">
                                                        <h5><i class="fa-solid fa-gas-pump me-2"></i> PUC Certificate</h5>
                                                        <div class="image-box text-center">
                                                            <img id="pucImg" src="" class="img-fluid" alt="PUC"
                                                                style="width:100%; height:300px; object-fit:contain;">
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                                <!-- RC -->
                                                <div class="tab-pane fade" id="rc" role="tabpanel">
                                                    <div class="card p-3 mb-3">
                                                        <h5><i class="fa-solid fa-id-card me-2"></i> RC Book</h5>
                                                        <div class="image-box text-center">
                                                            <img id="rcImg" src="" class="img-fluid" alt="RC"
                                                                style="width:100%; height:300px; object-fit:contain;">
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                                <!-- Insurance -->
                                                <div class="tab-pane fade" id="insurance" role="tabpanel">
                                                    <div class="card p-3 mb-3">
                                                        <h5><i class="fa-solid fa-file-invoice me-2"></i> Insurance</h5>
                                                        <div class="image-box text-center">
                                                            <img id="insuranceImg" src="" class="img-fluid" alt="Insurance"
                                                                style="width:100%; height:300px; object-fit:contain;">
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
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://kit.fontawesome.com/a2e0e6ad5f.js" crossorigin="anonymous"></script>
<!--rejection swal-->
<script>
document.getElementById('rejectBtn').addEventListener('click', function() {
    Swal.fire({
        title: 'Reject Verification',
        input: 'textarea',
        inputLabel: 'Enter rejection reason',
        inputPlaceholder: 'Type your reason here...',
        inputAttributes: {
            'aria-label': 'Type your reason here'
        },
        showCancelButton: true,
        confirmButtonText: 'Submit',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        preConfirm: (reason) => {
            if (!reason) {
                Swal.showValidationMessage('Please enter a reason before submitting');
            }
            return reason;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Show Toast
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Rejection submitted successfully',
                text: 'Reason: ' + result.value,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
    });
});
</script>
<!--drag images funtion-->
<script>
document.querySelectorAll('.image-carousel').forEach(carousel => {
    const boxes = carousel.querySelectorAll('.image-box');
    const nextBtn = carousel.querySelector('.next-btn');
    const prevBtn = carousel.querySelector('.prev-btn');
    let current = 0;

    function showImage(index) {
        boxes.forEach((box, i) => box.classList.toggle('d-none', i !== index));
    }

    showImage(current);

    if (nextBtn) {
        nextBtn.addEventListener('click', e => {
            e.stopPropagation();
            current = (current + 1) % boxes.length;
            showImage(current);
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', e => {
            e.stopPropagation();
            current = (current - 1 + boxes.length) % boxes.length;
            showImage(current);
        });
    }
});

document.querySelectorAll('.image-box img').forEach(img => {
    let scale = 1, posX = 0, posY = 0;
    let startX = 0, startY = 0;
    let isDragging = false;
    const box = img.closest('.image-box');

    box.addEventListener('wheel', e => {
        e.preventDefault();
        const delta = e.deltaY < 0 ? 0.1 : -0.1;
        scale = Math.min(Math.max(1, scale + delta), 3);
        constrain();
        updateTransform();
    });

    img.addEventListener('mousedown', e => {
        e.preventDefault();
        isDragging = true;
        startX = e.clientX - posX;
        startY = e.clientY - posY;
        img.style.cursor = 'grabbing';
    });
    document.addEventListener('mousemove', e => {
        if (!isDragging) return;
        posX = e.clientX - startX;
        posY = e.clientY - startY;
        constrain();
        updateTransform();
    });
    document.addEventListener('mouseup', () => {
        isDragging = false;
        img.style.cursor = 'grab';
    });

    img.addEventListener('dblclick', () => {
        scale = 1;
        posX = 0;
        posY = 0;
        updateTransform();
    });

    function constrain() {
        const boxRect = box.getBoundingClientRect();
        const imgWidth = img.naturalWidth * scale;
        const imgHeight = img.naturalHeight * scale;
        const maxX = Math.max(0, (imgWidth - boxRect.width) / 2);
        const maxY = Math.max(0, (imgHeight - boxRect.height) / 2);
        posX = Math.min(Math.max(posX, -maxX), maxX);
        posY = Math.min(Math.max(posY, -maxY), maxY);
    }

    function updateTransform() {
        img.style.transform = `translate(${posX}px, ${posY}px) scale(${scale})`;
        img.style.transition = 'transform 0.05s ease-out';
        img.style.cursor = 'grab';
    }
});
</script>
<!--user verify-->
<script>
$(document).ready(function() {
    const origin = window.location.origin;
    let userId = <?= json_encode($userID_data ?? null) ?>;
    let userID_kyc_id = <?= json_encode($userID_kyc_id ?? null) ?>;
    let userID_txt = <?= json_encode($userID_txt ?? null) ?>;
    if (!userId) {
        const parts = window.location.pathname.split('/');
        userId = parts[parts.length - 3] || null;
        userID_kyc_id = parts[parts.length - 2] || null;
        userID_txt = parts[parts.length - 1] || null;
    }
    if (!userId) {
        $("#info-body").html(
            "<tr><td colspan='3' class='text-center text-danger'>No User ID provided!</td></tr>");
        return;
    }

    function loadKycData(selectedType = "adhar") {
        $("#info-body").html(
            "<tr><td colspan='3' class='text-center text-primary'>Loading KYC data...</td></tr>");
        $.ajax({
            url: origin + "/ajax/service/datatable_services.php",
            type: "POST",
            dataType: "json",
            data: {
                method: 'kycUserDetails',
                user_id: userId,
                id: userID_kyc_id,
                txt: userID_txt
            },
            success: function(response) {
                if (!response || !response.result) {
                    $("#info-body").html(
                        "<tr><td colspan='3' class='text-center text-danger'>No KYC data found!</td></tr>"
                    );
                    return;
                }
                const user = response.result;
                const placeholder = "https://via.placeholder.com/400x250?text=No+Image";
                const selfieUrl = user.selfie_url?.trim() ||
                    "https://via.placeholder.com/300?text=No+Selfie";
                $("#selfieImage").attr("src", selfieUrl);
                const aadharInfo = `
          <tr><td><i class="fas fa-user me-2 text-primary"></i><strong>Full Name</strong></td><td>:</td><td>${user.name || '-'}</td></tr>
          <tr><td><i class="fas fa-mobile-alt me-2 text-success"></i><strong>Mobile</strong></td><td>:</td><td>${user.mobile || '-'}</td></tr>
          <tr><td><i class="fas fa-id-card me-2 text-info"></i><strong>Proof Type</strong></td><td>:</td><td>${user.proof_type || '-'}</td></tr>
          <tr><td><i class="fas fa-check-circle me-2 text-warning"></i><strong>Proof Status</strong></td><td>:</td><td>${user.proof_status || '-'}</td></tr>
        `;
                let dlDetails = {};
                try {
                    if (user.req_response) dlDetails = JSON.parse(user.req_response);
                } catch (err) {
                    console.warn("⚠️ Invalid req_response JSON:", err);
                }
                const dlInfo = `
          <tr><td><i class="fas fa-user me-2 text-primary"></i><strong>Holder's Name</strong></td><td>:</td><td>${dlDetails["Holder's Name"] || user.name || '-'}</td></tr>
          <tr><td><i class="fas fa-id-badge me-2 text-info"></i><strong>DL Number</strong></td><td>:</td><td>${dlDetails["id_no"] || user.dl_no || '-'}</td></tr>
          <tr><td><i class="fas fa-calendar-times me-2 text-danger"></i><strong>DL Expiry</strong></td><td>:</td><td>${dlDetails["date_of_expiry"] || user.dl_expiry || '-'}</td></tr>
          <tr><td><i class="fas fa-calendar-plus me-2 text-success"></i><strong>Issue Date</strong></td><td>:</td><td>${dlDetails["issued_date"] || user.issue_date || '-'}</td></tr>
          <tr><td><i class="fas fa-info-circle me-2 text-warning"></i><strong>Current Status</strong></td><td>:</td><td>${dlDetails["Current Status"] || user.dl_status || '-'}</td></tr>
          <tr><td><i class="fas fa-mobile-alt me-2 text-success"></i><strong>Mobile</strong></td><td>:</td><td>${user.mobile || '-'}</td></tr>
        `;
                const aadharFront = user.front_image?.trim() || placeholder;
                const aadharBack = user.back_image?.trim() || placeholder;
                const dlFront = user.front_image?.trim() || placeholder;
                const dlBack = user.back_image?.trim() || placeholder;
                if (selectedType === "adhar") {
                    $("#card-title-text").text("User Aadhar Info");
                    $("#info-body").html(aadharInfo);
                    $("#aadharFront").attr("src", aadharFront);
                    $("#aadharBack").attr("src", aadharBack);
                } else if (selectedType === "dl") {
                    $("#card-title-text").text("User Driving License Info");
                    $("#info-body").html(dlInfo);
                    $("#dlFront").attr("src", dlFront);
                    $("#dlBack").attr("src", dlBack);
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
    loadKycData("adhar");
    $("#adhar-tab").on("click", function() {
        loadKycData("adhar");
    });
    $("#dl-tab").on("click", function() {
        loadKycData("dl");
    });
});
</script>
<!-- vehicle verify -->
<script>
$(document).ready(function() {
    const origin = window.location.origin;
    let userId = <?= json_encode($userID_data ?? null) ?>;
    let userID_kyc_id = <?= json_encode($userID_kyc_id ?? null) ?>;
    let userID_txt = <?= json_encode($userID_txt ?? null) ?>;
    if (!userId) {
        const parts = window.location.pathname.split('/');
        userId = parts[parts.length - 3] || null;
        userID_kyc_id = parts[parts.length - 2] || null;
        userID_txt = parts[parts.length - 1] || null;
    }
    if (!userId) {
        $("#infoTitle").text("Missing User ID");
        $("#carInfoTable tbody").html(`
      <tr><td colspan='3' class='text-center text-danger'>No User ID provided!</td></tr>
    `);
        toastr.error("Missing User ID. Cannot load KYC details.");
        return;
    }
    loadVehicleVerification(userId, userID_kyc_id);

    function loadVehicleVerification(userId, id) {
        $.ajax({
            url: origin + "/ajax/service/datatable_services.php",
            type: "POST",
            data: {
                method: "kycUserDetails",
                user_id: userId,
                id: id,
                txt: "VEHICLEVERIFY"
            },
            dataType: "json",
            beforeSend: function() {
                $("#loadingSpinner").show();
            },
            success: function(response) {
                $("#loadingSpinner").hide();
                const res = response.data;
                if (res && res.vehicle) {
                    const v = res.vehicle || {};
                    const p = res.puc || {};
                    const i = res.insurance || {};
                    const f = res.fc || {};
                    v.car_front_image ? $("#carFrontImg").attr("src", v.car_front_image).show() : $(
                        "#carFrontImg").hide();
                    v.car_back_image ? $("#carBackImg").attr("src", v.car_back_image).show() : $(
                        "#carBackImg").hide();
                    v.rc_image ? $("#rcImg").attr("src", v.rc_image).show() : $("#rcImg").hide();
                    p.puc_image_url ? $("#pucImg").attr("src", p.puc_image_url).show() : $(
                        "#pucImg").hide();
                    i.insurance_image_url ? $("#insuranceImg").attr("src", i.insurance_image_url)
                        .show() : $("#insuranceImg").hide();
                    const carData = {
                        "car-verification": {
                            title: "Vehicle Verification Details",
                            data: [{
                                    icon: "fa-car text-success",
                                    label: "Vehicle Model",
                                    value: v.maker_model || "—"
                                },
                                {
                                    icon: "fa-id-card text-danger",
                                    label: "RC Number",
                                    value: v.rc_number || "—"
                                },
                                {
                                    icon: "fa-user text-info",
                                    label: "Owner Name",
                                    value: v.owner_name || "—"
                                },
                                {
                                    icon: "fa-gas-pump text-warning",
                                    label: "Fuel Type",
                                    value: v.fuel_type || "—"
                                },
                                {
                                    icon: "fa-calendar-alt text-primary",
                                    label: "Registration Date",
                                    value: v.registration_date || "—"
                                },
                                {
                                    icon: "fa-palette text-secondary",
                                    label: "Color",
                                    value: v.color || "—"
                                }
                            ]
                        },
                        "rc": {
                            title: "RC Certificate Details",
                            data: [{
                                    icon: "fa-id-card text-success",
                                    label: "RC Status",
                                    value: v.rc_status || "Active ✅"
                                },
                                {
                                    icon: "fa-car text-danger",
                                    label: "Chassis No",
                                    value: v.vehicle_chassis_number || "—"
                                },
                                {
                                    icon: "fa-key text-warning",
                                    label: "Engine No",
                                    value: v.vehicle_engine_number || "—"
                                },
                                {
                                    icon: "fa-calendar-check text-info",
                                    label: "RC Expiry",
                                    value: v.rc_expiry_date || "—"
                                },
                            ]
                        },
                   "puc": {
                            title: "PUC Certificate Details",
                            data: [
                                {
                                    icon: "fa-check-circle text-success",
                                    label: "PUC Status",
                                    value: p.puc_status ? "Active ✅" : "Inactive ❌"
                                },
                                // {
                                //     icon: "fa-id-card text-primary",
                                //     label: "PUC Number",
                                //     value: p.puc_number || "—"
                                // },
                                {
                                    icon: "fa-calendar-alt text-danger",
                                    label: "PUC Expiry",
                                    value: p.puc_exp_date || "—"
                                },
                            ]
                        },

                        "insurance": {
                            title: "Insurance Details",
                            data: [{
                                    icon: "fa-file-invoice text-success",
                                    label: "Insurance Status",
                                    value: i.insurance_status ? "Active ✅" : "Inactive ❌"
                                },
                                {
                                    icon: "fa-calendar-alt text-danger",
                                    label: "Insurance Expiry",
                                    value: i.insurance_exp_date || "—"
                                },
                            ]
                        },
                        "fc": {
                            title: "Fitness Certificate Details",
                            data: [{
                                    icon: "fa-certificate text-success",
                                    label: "FC Status",
                                    value: f.fc_status ? "Active ✅" : "Inactive ❌"
                                },
                                {
                                    icon: "fa-calendar-alt text-danger",
                                    label: "FC Expiry",
                                    value: f.fc_exp_date || "—"
                                },
                            ]
                        }
                    };
                    window.carData = carData;
                    updateCarInfo("car-verification");
                    toastr.success("✅ Vehicle verification data loaded successfully!");
                } else {
                    toastr.warning("No vehicle details found for this user.");
                }
            },
            error: function(xhr, status, error) {
                $("#loadingSpinner").hide();
                toastr.error("Server error while fetching vehicle data.");
            }
        });
    }

    function updateCarInfo(tabKey) {
        const section = window.carData?. [tabKey];
        if (!section) return;
        $("#infoTitle").text(section.title);
        const rows = section.data.map(item => `
      <tr>
        <td class="text-center" style="width: 40px;"><i class="fa ${item.icon} fa-lg"></i></td>
        <td>${item.label}</td>
        <td>${item.value}</td>
      </tr>
    `).join("");
        $("#carInfoTable tbody").fadeOut(150, function() {
            $(this).html(rows).fadeIn(150);
        });
    }
    $(document).on("click", "#carTabs .nav-link", function() {
        const tabKey = $(this).data("tab");
        updateCarInfo(tabKey);
    });
    let currentIndex = 0;
    const boxes = $(".image-carousel .image-box");
    $(".next-btn").click(function() {
        boxes.eq(currentIndex).addClass("d-none");
        currentIndex = (currentIndex + 1) % boxes.length;
        boxes.eq(currentIndex).removeClass("d-none");
    });
    $(".prev-btn").click(function() {
        boxes.eq(currentIndex).addClass("d-none");
        currentIndex = (currentIndex - 1 + boxes.length) % boxes.length;
        boxes.eq(currentIndex).removeClass("d-none");
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const activeTab = localStorage.getItem("activeTab");
    const userTabBtn = document.querySelector('#user-tab');
    const vehicleTabBtn = document.querySelector('#vehicle-tab');
    const userTabContent = document.querySelector('#user');
    const vehicleTabContent = document.querySelector('#vehicle');
    let tabToActivate = activeTab || "USER";
    if (tabToActivate === "USER") {
        if (vehicleTabBtn) vehicleTabBtn.closest('li').style.display = "none";
        if (vehicleTabContent) vehicleTabContent.style.display = "none";
        if (userTabBtn) {
            const tab = new bootstrap.Tab(userTabBtn);
            tab.show();
        }
    } else if (tabToActivate === "VEHICLE") {
        if (userTabBtn) userTabBtn.closest('li').style.display = "none";
        if (userTabContent) userTabContent.style.display = "none";
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
</script>