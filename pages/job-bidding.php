<?php
$pageTitle = "Job-Bidding";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$job_id = 0;

$uri = $_SERVER['REQUEST_URI'] ?? '';

// Parse query string safely
$parts = parse_url($uri);

if (!empty($parts['query'])) {
    parse_str($parts['query'], $query);
    $job_id = (int) ($query['job_id'] ?? 0);
}


$stmt = $con->prepare("SELECT *, 
    user_details->>'$.name' AS name, 
    user_details->>'$.email' AS email, 
    user_details->>'$.cab_type' AS car_type, 
    user_details->>'$.mobile' AS mobile,
    user_id -- Assuming this column exists to link the tables
    FROM cus_job_temp WHERE id = ?");

$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if ($job) {
    // 1. Determine which table to look at
    $tableName = ($job['global_type'] === 'customer') ? 'customer_register' : 'user_register';
    
    if($job['user_details'] == null){
        // 2. Prepare a second query to get the actual name/mobile from the registration table
        $stmt2 = $con->prepare("SELECT name, mobile FROM $tableName WHERE id = ?");
        $stmt2->bind_param("i", $job['user_id']);
        $stmt2->execute();
        $res2 = $stmt2->get_result();
        $userData = $res2->fetch_assoc();
    
        // 3. Update the $job array if a record was found
        if ($userData) {
            $job['name'] = $userData['name'];
            $job['mobile'] = $userData['mobile'];
        }
        
    }
}

// var_dump($job); die;

if (!$job) {
    die('Job not found');
}



$job_no = $job['job_no'];


// $job_no = 'GRC-015';


?> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>

/* ===============================
   CARD CONTAINER
================================ */
.card {
    background: #ffffff;
    border-radius: 16px;
    padding: 22px;
    box-shadow: 0 14px 40px rgba(0, 0, 0, 0.06);
    margin-bottom: 24px;
    border: 1px solid #f1f1f1;
}

/* ===============================
   HEADER
================================ */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.badge {
    background: #dcfce7;
    color: #166534;
    padding: 6px 16px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}

/* ===============================
   BEST PRICE BOX
================================ */
.best-price-box {
    background: linear-gradient(135deg, #e8f7ff, #f3fbff);
    padding: 10px 15px;
    margin-bottom: 15px;
    border-radius: 8px;
    text-align: right;
    font-weight: 600;
    color: #0d6efd;
    border: 1px solid #d6ecff;
}

/* ===============================
   BID LIST
================================ */
.bid-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

/* ===============================
   BID ROW
================================ */
.bid-row {
    display: flex;
    justify-content: space-between;
    padding: 16px;
    border-radius: 12px;
    border: 1px solid #f0f0f0;
    background: #ffffff;
    transition: all 0.2s ease;
}

.bid-row:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
    transform: translateY(-2px);
}

/* BEST BID HIGHLIGHT */
.best-bid {
    background: #f6fff6;
    border: 1px solid #d4edda;
    border-left: 4px solid #28a745;
}

/* ===============================
   LEFT SECTION
================================ */
.bid-left {
    display: flex;
    align-items: flex-start;
    gap: 14px;
}

.bid-avatar {
    flex-shrink: 0;
}

.driver-avatar {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e5e5e5;
}

.placeholder-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #0d6efd;
    color: #fff;
    font-weight: bold;
    font-size: 20px;
}

.bid-info {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.bidder-name {
    font-weight: 600;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.bid-icons a {
    margin-left: 6px;
    text-decoration: none;
    font-size: 15px;
}

.bid-details {
    font-size: 13px;
    color: #555;
    line-height: 1.6;
}

.bid-remark {
    font-size: 13px;
    color: #444;
    background: #f9f9f9;
    padding: 6px 10px;
    border-radius: 6px;
}

/* ===============================
   RIGHT SECTION
================================ */
.bid-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    justify-content: center;
    gap: 8px;
}

.bid-amount {
    font-size: 18px;
    font-weight: 700;
    color: #212529;
}

/* ===============================
   BUTTONS
================================ */
.bid-actions {
    display: flex;
    gap: 8px;
}

/* ACCEPT BUTTON */
.accept-btn {
    background: #28a745;
    color: #ffffff;
    border: none;
    padding: 7px 14px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.accept-btn:hover {
    background: #218838;
    transform: translateY(-1px);
}

.accept-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* REJECT BUTTON (SOFTER OUTLINE STYLE) */
.reject-btn {
    background: transparent;
    color: #dc3545;
    border: 1px solid #dc3545;
    padding: 7px 14px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.reject-btn:hover {
    background: #dc3545;
    color: #ffffff;
    transform: translateY(-1px);
}

.reject-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* ACCEPTED BADGE */
.accepted-badge {
    background: #e6f9ed;
    color: #28a745;
    font-weight: 600;
    font-size: 13px;
    padding: 5px 12px;
    border-radius: 999px;
}

/* ===============================
   KYC LINK
================================ */
.kyc-link {
    color: #0d6efd;
    font-weight: 600;
    text-decoration: none;
}

.kyc-link:hover {
    text-decoration: underline;
}

.accepted-row {
    background: #f0fff4;
    border: 1px solid #c6f6d5;
    border-left: 4px solid #28a745;
}

.rejected-row {
    background: #fff5f5;
    border: 1px solid #f5c6cb;
    opacity: 0.75;
}

.rejected-badge {
    background: #ffe6e6;
    color: #dc3545;
    font-weight: 600;
    font-size: 13px;
    padding: 5px 12px;
    border-radius: 999px;
}


/* ===============================
   RESPONSIVE
================================ */
@media (max-width: 768px) {

    .bid-row {
        flex-direction: column;
        gap: 12px;
    }

    .bid-right {
        align-items: flex-start;
    }

    .bid-actions {
        width: 100%;
        display: flex;
        justify-content: flex-start;
    }
}

</style>

<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>
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
            <div class="card">
                <h3 class="mb-3">Job Details</h3>
            
                <?php
                    $user_details_decode = [];
                    if (!empty($job['user_details'])) {
                        $user_details_decode = json_decode($job['user_details'], true) ?? [];
                    }
                ?>
            
                <div class="row g-3">
            
                    <!-- ROW 1 -->
                    <div class="col-md-3">
                        <small class="text-muted">Job No</small>
                        <div class="fw-bold d-flex align-items-center gap-2">
                            <?= htmlspecialchars($job_no) ?>
            
                            <?php if (!empty($job['preview_hash'])) : ?>
                                <a href="https://goride.run/booking-information/<?= urlencode($job['preview_hash']) ?>" 
                                   target="_blank"
                                   class="text-decoration-none text-dark"
                                   title="View Booking Information">
                                    <i class="bi bi-eye" style="font-size:16px;"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
            
                    <div class="col-md-3">
                        <small class="text-muted">Job Type</small>
                        <div class="fw-bold text-capitalize">
                            <?= htmlspecialchars($job['job_type']) ?>
                        </div>
                    </div>
            
                    <div class="col-md-3">
                        <small class="text-muted">Cab Type</small>
                        <div class="fw-bold text-capitalize">
                            <?= htmlspecialchars($user_details_decode['cab_type'] ?? '0') ?>
                        </div>
                    </div>
            
                    <div class="col-md-3">
                        <small class="text-muted">Passengers</small>
                        <div class="fw-bold">
                            <?= htmlspecialchars($user_details_decode['pass_count'] ?? '0') ?>
                        </div>
                    </div>
            
                    <!-- ROW 2 -->
                    <div class="col-md-3">
                        <small class="text-muted">Luggage</small>
                        <div class="fw-bold">
                            <?= htmlspecialchars($user_details_decode['lugg_count'] ?? '0') ?>
                        </div>
                    </div>
            
                    <div class="col-md-3">
                        <small class="text-muted">Pickup Date</small>
                        <div class="fw-bold">
                            <?= htmlspecialchars($job['pickup_date']) ?>
                        </div>
                    </div>
            
                    <?php if (!empty($job['dropoff_date'])) : ?>
                    <div class="col-md-3">
                        <small class="text-muted">Dropoff Date</small>
                        <div class="fw-bold">
                            <?= htmlspecialchars($job['dropoff_date']) ?>
                        </div>
                    </div>
                    <?php endif; ?>
            
                    <div class="col-md-3">
                        <small class="text-muted">Customer Name</small>
                        <div class="fw-bold">
                            <?= htmlspecialchars($job['name']) ?>
                        </div>
                    </div>
            
                    <!-- ROW 3 (Locations Full Width) -->
                    <div class="col-md-6">
                        <small class="text-muted">Pickup Location</small>
                        <div class="fw-bold clickable-place">
                            <?= htmlspecialchars($job['from_place']) ?>
                        </div>
                    </div>
            
                    <div class="col-md-6">
                        <small class="text-muted">Drop Location</small>
                        <div class="fw-bold clickable-place">
                            <?= htmlspecialchars($job['to_place']) ?>
                        </div>
                    </div>
            
                    <!-- ROW 4 (Fare Section Aligned) -->
                    <div class="col-md-3">
                        <small class="text-muted">Mobile</small>
                        <div class="fw-bold">
                            <?= htmlspecialchars($job['mobile']) ?>
                        </div>
                    </div>
            
                    <div class="col-md-3">
                        <small class="text-muted">Base Fare</small>
                        <div class="fw-bold">
                            <?= htmlspecialchars($job['base_fare']) ?>
                        </div>
                    </div>
            
                    <div class="col-md-3">
                        <small class="text-muted">Toll Fare</small>
                        <div class="fw-bold">
                            <?= htmlspecialchars($job['toll_fare']) ?>
                        </div>
                    </div>
            
                    <div class="col-md-3">
                        <small class="text-muted">Total Fare</small>
                        <div class="fw-bold">
                            <?= htmlspecialchars($job['fare']) ?>
                        </div>
                    </div>
            
                </div>
            </div>
        
            <!-- LIVE BIDS -->
            <div class="card">
                <h3>Incoming Bids</h3>
            
                <!-- Best price badge -->
                <div id="bestPriceBox" class="best-price-box" style="display:none">
                    💰 Best Price: <strong id="bestPriceValue"></strong>
                </div>
            
                <div id="bidsContainer">
                    <p>Waiting for bids…</p>
                </div>
            </div>

        </div>
    </div>
    <!-- CONTAINER END -->
</div>

<script>
const firebaseConfig = {
  apiKey: "AIzaSyCiRKGU2xZyZNx5-ZwweLd5cPokxJjxKzw",
  authDomain: "goride-947ed.firebaseapp.com",
  projectId: "goride-947ed",
};

firebase.initializeApp(firebaseConfig);
const db = firebase.firestore();
</script>
<script>
    const API_DOMAIN_2 = "<?= API_DOMAIN_2 ?>";
</script>


<script>
const jobNo = "<?= $job_no ?>";

db.collection('jobs')
  .doc(jobNo)
  .onSnapshot(doc => {

    const container      = document.getElementById('bidsContainer');
    const bestPriceBox   = document.getElementById('bestPriceBox');
    const bestPriceValue = document.getElementById('bestPriceValue');

    container.classList.add('bid-list');
    container.innerHTML = '';

    if (!doc.exists) {
        container.innerHTML = '<p class="text-danger">Job not found</p>';
        return;
    }

    const data = doc.data();
    const bids = data.bids_details || {};

    if (Object.keys(bids).length === 0) {
        container.innerHTML = '<p class="text-muted">No bids yet</p>';
        if (bestPriceBox) bestPriceBox.style.display = 'none';
        return;
    }

    /* =========================
       FIND LOWEST PRICE
    ========================= */
    let lowestPrice = null;

    Object.values(bids).forEach(bid => {
        const amount = Number(bid.amount);
        if (!isNaN(amount)) {
            lowestPrice = lowestPrice === null
                ? amount
                : Math.min(lowestPrice, amount);
        }
    });

    if (lowestPrice !== null && bestPriceBox && bestPriceValue) {
        bestPriceValue.textContent = `₹${lowestPrice}`;
        bestPriceBox.style.display = 'block';
    }

    /* =========================
       CHECK IF JOB ACCEPTED
    ========================= */
    const isJobAccepted = data.job_status === 'accept';

    /* =========================
       RENDER BIDS
    ========================= */
    Object.entries(bids).reverse().forEach(([bidderId, bid]) => {

        const bidderName = bid.b_name || `Bidder #${bidderId}`;
        const amount     = bid.amount;
        const status     = bid.status || 'pending';

        const isAccepted = status === 'accept' || status === 'accepted';
        const isRejected = status === 'reject' || status === 'rejected';
        const isBest     = Number(amount) === lowestPrice;

        const phone = bid.b_mobile ? bid.b_mobile.replace(/\D/g, '') : '';

        const seater   = bid.b_seater || 'NA';
        const cabType  = bid.b_cab || 'NA';
        const language = bid.b_language || 'NA';
        const luggage  = bid.b_luggage || 'NA';
        const remark   = bid.remark || 'NA';
        const kyc_id   = bid.kyc_id || null;
        const b_image  = bid.b_image || null;

        /* =========================
           BUTTON LOGIC
        ========================= */

        // If job accepted → hide ALL buttons
        // If bid rejected → hide its buttons
        let showButtons = !isJobAccepted && !isRejected;

        /* =========================
           ROW CLASS
        ========================= */

        let rowClass = '';

        if (isAccepted) {
            rowClass = 'accepted-row';
        } 
        else if (isRejected) {
            rowClass = 'rejected-row';
        } 
        else if (isBest) {
            rowClass = 'best-bid';
        }

        container.innerHTML += `
            <div class="bid-row ${rowClass}" data-bidder-id="${bidderId}">

                <div class="bid-left d-flex">

                    <div class="bid-avatar">
                        ${
                            b_image
                            ? `<img src="${b_image}" alt="${bidderName}" class="driver-avatar">`
                            : `<div class="driver-avatar placeholder-avatar">
                                    ${bidderName.charAt(0).toUpperCase()}
                               </div>`
                        }
                    </div>

                    <div class="bid-info flex-grow-1 ms-3">

                        <div class="bidder-name">
                            ${
                                kyc_id
                                ? `<a href="/kyc-verify/verify/${bidderId}/${kyc_id}" 
                                     target="_blank" 
                                     class="kyc-link">${bidderName}</a>`
                                : `<span>${bidderName}</span>`
                            }

                            <span class="bid-icons">
                                ${phone ? `<a href="https://wa.me/${phone}" target="_blank">📱</a>` : ''}
                                ${phone ? `<a href="tel:${phone}">📞</a>` : ''}
                            </span>
                        </div>

                        <div class="bid-details">
                            <div><strong>Mobile:</strong> ${phone || '-'}</div>
                            <div><strong>Seater:</strong> ${seater}</div>
                            <div><strong>Cab Type:</strong> ${cabType}</div>
                            <div><strong>Language:</strong> ${language}</div>
                            <div><strong>Luggage:</strong> ${luggage}</div>
                        </div>

                        <div class="bid-remark">
                            <strong>Remark:</strong> ${remark}
                        </div>

                    </div>
                </div>

                <div class="bid-right">
                    <div class="bid-amount">₹${amount}</div>

                    ${
                        isAccepted
                            ? `<span class="accepted-badge">Accepted</span>`
                            : isRejected
                                ? `<span class="rejected-badge">Rejected</span>`
                                : showButtons
                                    ? `
                                        <div class="bid-actions">
                                            <button type="button"
                                                class="accept-btn"
                                                data-bidder="${bidderId}"
                                                data-amount="${amount}"
                                                data-userid="${data.user_id}"
                                                data-jobid="${data.id}">
                                                Accept
                                            </button>

                                            <button type="button"
                                                class="reject-btn"
                                                data-bidder="${bidderId}"
                                                data-userid="${data.user_id}"
                                                data-jobid="${data.id}">
                                                Reject
                                            </button>
                                        </div>
                                      `
                                    : ''
                    }
                </div>
            </div>
        `;
    });

});


  
    
    $(document).on('click', '.reject-btn', function () {

        const bidderId = $(this).data('bidder');
        const jobId    = $(this).data('jobid');
        const userId   = $(this).data('userid');
    
        Swal.fire({
            title: 'Reject Bid?',
            text: 'Are you sure you want to reject this bid?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Reject',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showLoaderOnConfirm: false,
    
            preConfirm: () => {
    
                // Change button text manually
                Swal.getConfirmButton().innerHTML = 'Cancelling...';
                Swal.getConfirmButton().disabled = true;
    
                return new Promise((resolve, reject) => {
    
                    $.ajax({
                        url: API_DOMAIN_2 + 'admin-reject-bid',
                        type: 'POST',
                        headers: {
                          'Content-Type': 'application/json',
                          'Accept': 'application/json',
                          'Authorization': 'Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()'
                        },
                        data: JSON.stringify({
                            uid: userId,
                            job_id: jobId,
                            bidder_id: bidderId
                        })
                    })
                    .done(function (res) {
    
                        if (res.status) {
    
                            resolve(res);
    
                        } else {
                            reject(res.message || 'Reject failed');
                        }
                    })
                    .fail(function (xhr) {
    
                        let msg = 'Server error';
    
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
    
                        reject(msg);
                    });
    
                }).catch(error => {
    
                    Swal.showValidationMessage(error);
    
                    // Reset button state
                    Swal.getConfirmButton().innerHTML = 'Yes, Reject';
                    Swal.getConfirmButton().disabled = false;
    
                });
            }
    
        }).then((result) => {
    
            if (result.isConfirmed) {
    
                Swal.fire({
                    icon: 'success',
                    title: 'Rejected',
                    text: 'Bid rejected successfully.'
                });
    
            }
    
        });
    
    });



/* =========================
   ACCEPT BID (API CALL)
========================= */
document.addEventListener('click', function (e) {

  const btn = e.target.closest('.accept-btn');
  if (!btn) return;

  const bidderId = btn.dataset.bidder;
  const jobId = btn.dataset.jobid;
  const userId = btn.dataset.userid;
  const amount = btn.dataset.amount;

  if (!confirm(`Accept bid ₹${amount}?`)) return;

  btn.disabled = true;
  btn.textContent = 'Accepting…';

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
    if (res.status) {
      toast('success', res.message);
      location.reload();
    } else {
      toast('error', res.message);
      btn.disabled = false;
      btn.textContent = 'Accept';
    }
  })
  .catch(() => {
    toast('error', 'Network error');
    btn.disabled = false;
    btn.textContent = 'Accept';
  });

});
</script>

<script>

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