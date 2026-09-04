<?php
/**
 * Date               Developer          Modification
 * 6/02/2023          Prakash             Count show from payment check
 * 16-6-2023          Prakash             Status Has been updated
 */



$name = select_top_name($con, "user_register", "name", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC ", "name", "");
$lname = select_top_name($con, "user_register", "lname", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC ", "lname", "");

$roll_id = select_top_name($con, "user_register", "roll_id", "`id`='$_SESSION[memid]' and `deletes`='0'  order by `id` DESC ", "roll_id", "");
$roll_name = select_top_name($con, "role", "name", "`id`='$roll_id'", "name", "");
$time0 = strtotime($dubaidate_time);
$fd = date('Y-m-d H:i:s', strtotime('-1 day', $time0));
$td = date('Y-m-d H:i:s', strtotime('-10 minutes', $time0));
$Network = select_query($con, "payment_history", "", "`gateway` = 'network' AND (`paymentStatus` = 'CAPTURED' OR `paymentStatus` IS null) AND  `cron_check_status` = '0'  AND  `reference` != '' AND `status` = '0'  AND `createdon` BETWEEN '$fd' AND '$td'  ORDER BY `id` ASC", "", "");
$CCAvenue = select_query($con, "payment_history", "", "`gateway` = 'ccavenue' AND `status` = '0'  AND  `reference` != ''  AND  `cron_check_status` = '0' AND `paymentStatus` IN ('Success', 'Successful', 'Shipped')  AND `createdon`  BETWEEN '$fd' AND '$td'  ORDER BY `id` ASC", "", "");
$smslog = select_query($con, "smslog", "", "`smssendstatus` = '0' AND `mobile` != '' AND `details` != '' ORDER BY `id` ASC", "", "");
?>

<style>
    
.switch {
  /* switch */
  --switch-width: 46px;
  --switch-height: 24px;
  --switch-bg: rgb(131, 131, 131);
  --switch-checked-bg: #222;
  --switch-offset: calc((var(--switch-height) - var(--circle-diameter)) / 2);
  --switch-transition: all .2s cubic-bezier(0.27, 0.2, 0.25, 1.51);
  /* circle */
  --circle-diameter: 18px;
  --circle-bg: #fff;
  --circle-shadow: 1px 1px 2px rgba(146, 146, 146, 0.45);
  --circle-checked-shadow: -1px 1px 2px rgba(163, 163, 163, 0.45);
  --circle-transition: var(--switch-transition);
  /* icon */
  --icon-transition: all .2s cubic-bezier(0.27, 0.2, 0.25, 1.51);
  --icon-cross-color: var(--switch-bg);
  --icon-cross-size: 6px;
  --icon-checkmark-color: var(--switch-checked-bg);
  --icon-checkmark-size: 10px;
  /* effect line */
  --effect-width: calc(var(--circle-diameter) / 2);
  --effect-height: calc(var(--effect-width) / 2 - 1px);
  --effect-bg: var(--circle-bg);
  --effect-border-radius: 1px;
  --effect-transition: all .2s ease-in-out;
}

 #notif-box {
        max-height: 300px;
        overflow-y: auto;
    }

.start-75{
    right: 0;
}

.switch input {
  display: none;
}

.switch {
  display: inline-block;
}

.switch svg {
  -webkit-transition: var(--icon-transition);
  -o-transition: var(--icon-transition);
  transition: var(--icon-transition);
  position: absolute;
  height: auto;
}

.switch .checkmark {
  width: var(--icon-checkmark-size);
  color: var(--icon-checkmark-color);
  -webkit-transform: scale(0);
  -ms-transform: scale(0);
  transform: scale(0);
}

.switch .cross {
  width: var(--icon-cross-size);
  color: var(--icon-cross-color);
}

.slider {
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  width: var(--switch-width);
  height: var(--switch-height);
  background: var(--switch-bg);
  border-radius: 999px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  position: relative;
  -webkit-transition: var(--switch-transition);
  -o-transition: var(--switch-transition);
  transition: var(--switch-transition);
  cursor: pointer;
}

.circle {
  width: var(--circle-diameter);
  height: var(--circle-diameter);
  background: var(--circle-bg);
  border-radius: inherit;
  -webkit-box-shadow: var(--circle-shadow);
  box-shadow: var(--circle-shadow);
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  -webkit-transition: var(--circle-transition);
  -o-transition: var(--circle-transition);
  transition: var(--circle-transition);
  z-index: 1;
  position: absolute;
  left: var(--switch-offset);
}

.slider::before {
  content: "";
  position: absolute;
  width: var(--effect-width);
  height: var(--effect-height);
  left: calc(var(--switch-offset) + (var(--effect-width) / 2));
  background: var(--effect-bg);
  border-radius: var(--effect-border-radius);
  -webkit-transition: var(--effect-transition);
  -o-transition: var(--effect-transition);
  transition: var(--effect-transition);
}

/* actions */

.switch input:checked+.slider {
  background: var(--switch-checked-bg);
}

.switch input:checked+.slider .checkmark {
  -webkit-transform: scale(1);
  -ms-transform: scale(1);
  transform: scale(1);
}

.switch input:checked+.slider .cross {
  -webkit-transform: scale(0);
  -ms-transform: scale(0);
  transform: scale(0);
}

.switch input:checked+.slider::before {
  left: calc(100% - var(--effect-width) - (var(--effect-width) / 2) - var(--switch-offset));
}

.switch input:checked+.slider .circle {
  left: calc(100% - var(--circle-diameter) - var(--switch-offset));
  -webkit-box-shadow: var(--circle-checked-shadow);
  box-shadow: var(--circle-checked-shadow);
}

.notification-item {
  cursor: pointer;
  transition: transform 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
}

.notification-item:hover {
  transform: scale(1.02);
  background-color: #f7f7f7; /* subtle highlight */
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.notification-item:active {
  transform: scale(0.98);
}

    
</style>



<div class="app-header header sticky stickyClass fixed-header visible-title" style="margin-bottom: -74px;">

	<div class="container-fluid main-container">

		<div class="d-flex">

			<a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0)"></a>



			<a class="logo-horizontal " href="<?php echo $adminurl; ?>">

				<!--<img src="assets/images/brand/logo.png" class="header-brand-img desktop-logo" alt="logo">-->

				<img src="/assets/images/brand/go_ride_logo.png" style="width: 32%;" class="header-brand-img light-logo1 mx-auto" alt="logo">

			</a>



			<div class="main-header-center ms-3 d-none d-lg-block">
				<?php
if ($roll_id == 1 || $roll_id == 6 || $roll_id == 2) {
    ?>
					<!--<div class="d-flex">-->
					<!--	<p class="px-3 mb-0"><b>Network:</b> &nbsp; <a href="<?=constant('cronURL') . 'cron/cron1.php'?>" target="_blank"><strong style="font-weight: 600;color: <?=($Network['nr'] > 0) ? '#af2b03 !important' : '#6eaf19 !important';?>;"><?=$Network['nr'];?></strong></a></p>-->

					<!--	<p class="px-3 mb-0"> <b>CCAvenue:</b> &nbsp;<a href="<?=constant('cronURL') . 'cron/cron1.php'?>" target="_blank"><strong style="font-weight: 600;color: <?=($CCAvenue['nr'] > 0) ? '#af2b03 !important' : '#6eaf19 !important';?>;"><?=$CCAvenue['nr'];?></strong></a></p>-->

					<!--	<p class="px-3 mb-0"> <b>SMS:</b> &nbsp;<a href="<?=constant('cronURL') . 'cron/smssend.php'?>" target="_blank"><strong style="font-weight: 600;color: <?=($smslog['nr'] > 0) ? '#af2b03 !important' : '#6eaf19 !important';?>;"><?=$smslog['nr'];?></strong></a></p>-->
					<!--</div>-->

				<?php
}
?>





			</div>

			<div class="d-flex order-lg-2 ms-auto header-right-icons">

				<div class="dropdown d-none">

					<a href="javascript:void(0)" class="nav-link icon" data-bs-toggle="dropdown">

						<i class="fe fe-search"></i>

					</a>

					<div class="dropdown-menu header-search dropdown-menu-start">

						<div class="input-group w-100 p-2">

							<input type="text" class="form-control" placeholder="Search....">

							<div class="input-group-text btn btn-primary">

								<i class="fe fe-search" aria-hidden="true"></i>

							</div>

						</div>

					</div>

				</div>



				<button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">

					<span class="navbar-toggler-icon fe fe-more-vertical"></span>

				</button>

				<div class="navbar navbar-collapse responsive-navbar p-0">

					<div class="collapse navbar-collapse" id="navbarSupportedContent-4">

						<div class="d-flex order-lg-2">

							<div class="dropdown d-lg-none d-flex">

								<a href="javascript:void(0)" class="nav-link icon" data-bs-toggle="dropdown">

									<i class="fe fe-search"></i>

								</a>

								<div class="dropdown-menu header-search dropdown-menu-start">

									<div class="input-group w-100 p-2">

										<input type="text" class="form-control" placeholder="Search....">

										<div class="input-group-text btn btn-primary">

											<i class="fa fa-search" aria-hidden="true"></i>

										</div>

									</div>

								</div>

							</div>
							
							<!--<div class="dropdown d-flex" >-->
       <!--                         <a href="javascript:void(0)" onclick="openNotif()" class="nav-link icon position-relative" data-bs-toggle="dropdown">-->
       <!--                             <dotlottie-wc-->
       <!--                               src="https://lottie.host/ed1e6d1a-ca23-4953-ae48-bdaf032d6e9a/ArlJoT7dfU.lottie"-->
       <!--                               style="width: 50px;height: 50px"-->
       <!--                               autoplay-->
       <!--                               loop-->
       <!--                             ></dotlottie-wc>-->
       <!--                             <span class="position-absolute top-0 start-75 translate-middle badge rounded-pill bg-danger" id="notif-count">-->
                                        
       <!--                             </span>-->
       <!--                         </a>-->
       <!--                         <div class="dropdown-menu dropdown-menu-end shadow-lg p-0 border-0" style="min-width: 300px;" >-->
       <!--                             <div class="p-3 border-bottom fw-bold text-dark">-->
       <!--                                 Notifications-->
       <!--                             </div>-->
       <!--                             <ul class="list-group list-group-flush" id="notif-box">-->
                                        
       <!--                             </ul>-->
       <!--                             <div class="p-2 text-center border-top">-->
       <!--                                 <a href="javascript:void(0)" class="text-primary small">View All Notifications</a>-->
       <!--                             </div>-->
       <!--                         </div>-->
       <!--                     </div>-->
							
							<div class="gap-2 d-none">
                                <span>Test</span>
    							<label class="switch m-0">
                                    <input type="checkbox" 
                                       <?php echo ($dbConType === 'Live') ? 'checked' : ''; ?> 
                                       onchange="toggleConnection('<?php echo ($dbConType === 'Live') ? 'Test' : 'Live'; ?> ');">
                                    <div class="slider">
                                        <div class="circle">
                                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="6" height="6" x="0" y="0" viewBox="0 0 365.696 365.696" style="enable-background:new 0 0 512 512" xml:space="preserve" class="cross">
                                                <g>
                                                    <path d="M243.188 182.86 356.32 69.726c12.5-12.5 12.5-32.766 0-45.247L341.238 9.398c-12.504-12.503-32.77-12.503-45.25 0L182.86 122.528 69.727 9.374c-12.5-12.5-32.766-12.5-45.247 0L9.375 24.457c-12.5 12.504-12.5 32.77 0 45.25l113.152 113.152L9.398 295.99c-12.503 12.503-12.503 32.769 0 45.25L24.48 356.32c12.5 12.5 32.766 12.5 45.247 0l113.132-113.132L295.99 356.32c12.503 12.5 32.769 12.5 45.25 0l15.081-15.082c12.5-12.504 12.5-32.77 0-45.25zm0 0" fill="currentColor" data-original="#000000"></path>
                                                </g>
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="10" height="10" x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512" xml:space="preserve" class="checkmark">
                                                <g>
                                                    <path d="M9.707 19.121a.997.997 0 0 1-1.414 0l-5.646-5.647a1.5 1.5 0 0 1 0-2.121l.707-.707a1.5 1.5 0 0 1 2.121 0L9 14.171l9.525-9.525a1.5 1.5 0 0 1 2.121 0l.707.707a1.5 1.5 0 0 1 0 2.121z" fill="currentColor" data-original="#000000" class=""></path>
                                                </g>
                                            </svg>
                                        </div>
                                    </div>
                                </label>
                                <span>Live</span>
                            </div>

							<div class="dropdown d-flex profile-1">

								<a href="javascript:void(0)" data-bs-toggle="dropdown" class="nav-link leading-none d-flex" aria-expanded="false">

									<img src="https://webadmin.goride.run//assets/images/brand/Go-Ride-fav-icon.webp" alt="profile-user" class="avatar  profile-user brround cover-image">

								</a>

								<div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">

									<div class="drop-heading">

										<div class="text-center">

											<h5 class="text-dark mb-0 fs-14 fw-semibold"><?=$name;?> <?=$lname;?></h5>

											<small class="text-muted"> <?=$roll_name;?></small>

										</div>

									</div>

									<div class="dropdown-divider m-0"></div>

									





									<a class="dropdown-item" href="<?php echo $adminurl; ?>logout.php">

										<i class="dropdown-icon fe fe-alert-circle"></i> Sign out

									</a>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

</div>

<script
  src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js"
  type="module"
></script>
<script>
    
function toggleConnection(type) {
    fetch('set_db_active.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'selected_type=' + encodeURIComponent(type)
    })
    .then(response => response.text())
    .then(data => {
        // alert(data);
        location.reload();
    })
    .catch(err => alert('Error: ' + err));
}

const originss = window.location.origin;

/**
 * Toggles the visibility of the notification box and fetches/renders notifications.
 */
(function () {
  "use strict";

  // Prevent multiple intervals from being created accidentally
  let notifInterval = null;

  function startNotifLoop() {
    if (notifInterval !== null) return; // already running

    notifInterval = setInterval(() => {
      try {
        openNotif(); // your function
      } catch (err) {
        console.error("openNotif() failed:", err);
      }
    }, 10_000); // 10 seconds
  }

  function stopNotifLoop() {
    if (notifInterval !== null) {
      clearInterval(notifInterval);
      notifInterval = null;
    }
  }

  // Start the loop
  startNotifLoop();

  // Optional: expose control functions
  window.stopNotifLoop = stopNotifLoop;
  window.startNotifLoop = startNotifLoop;
})();

openNotif();
requestNotificationPermission();

const notifySound = new Audio("/assets/tone/admin_notify.mp3");
notifySound.preload = "auto";

let audioUnlocked = false;

function unlockAudio() {
    if (audioUnlocked) return;

    notifySound.play().then(() => {
        notifySound.pause();
        notifySound.currentTime = 0;
        audioUnlocked = true;
    }).catch(() => {});
}

function requestNotificationPermission() {
    if (!("Notification" in window)) return;

    if (Notification.permission === "default") {
        Notification.requestPermission();
    }
}

function showDesktopNotification(notification) {
    if (Notification.permission !== "granted") return;

    const notif = new Notification("New Notification", {
        body: notification.title,
        icon: "/assets/icons/bell.png", // optional
        silent: false
    });

    notif.onclick = function () {
        if (notification.link) {
            window.open(notification.link, "_blank");
        }
    };
}





function openNotif() {

    const notifBox   = $("#notif-box");
    const notifCount = $("#notif-count");

    $.get(originss + "/ajax/service/get_notifications.php?method=get_notification")
    .done(function (data) {

        if (!data || !Array.isArray(data.list)) {
            notifBox.html('<div class="p-3 text-danger">Invalid notification data</div>');
            return;
        }

        /* ---------- COUNT ---------- */
        const unreadCount = parseInt(data.unread || 0, 10);
        unreadCount > 0 ? notifCount.text(unreadCount).show() : notifCount.hide();

        /* ---------- SOUND (NEW ONLY) ---------- */
        const lastNotifId = parseInt(getCookie("last_notif_id") || "0", 10);

        const newUnreadItems = data.list.filter(n =>
            !n.read_at &&
            parseInt(n.id, 10) > lastNotifId &&
            (
                // n.type === "kyc.updated" ||
                n.type === "job.lead"
                // n.title?.toLowerCase().includes("vehicle") ||
                // n.title?.toLowerCase().includes("enquiry") ||
                // n.title?.toLowerCase().includes("dl")
            )
        );
        
        // console.log(newUnreadItems, 'hiiii', audioUnlocked)

        if (newUnreadItems.length > 0) {
            // Show notification only for the newest one
            showDesktopNotification(newUnreadItems[0]);
        }

        /* ---------- SAVE LATEST ID ---------- */
        if (data.list.length > 0) {
            const maxId = Math.max(...data.list.map(n => parseInt(n.id, 10)));
            setCookie("last_notif_id", maxId);
        }

        /* ---------- UI RENDER ---------- */
        let html = "";

        if (data.list.length === 0) {
            html = `<div class="p-3 text-center text-muted">No notifications</div>`;
        } else {
            data.list.forEach(n => {

                // const icon = n.read_at
                //     ? `<img src="assets/icons/notif_read.gif" width="24">`
                //     : `<img src="assets/icons/notif_unread.gif" width="24">`;

                html += `
                    <li class="list-group-item notification-item d-flex gap-2 align-items-start"
                        data-id="${n.id}"
                        data-link="${n.link}">
                        <div class="flex-grow-1">
                            <div class="fw-semibold">${n.title ?? 'Notification'}</div>
                            <small class="text-muted">${n.created_at ?? ''}</small>
                        </div>
                    </li>
                `;
            });
        }
                        // ${icon}

        notifBox.html(html);

        /* ---------- CLICK ---------- */
        notifBox.find('.notification-item').off('click').on('click', function () {
            goToNotif($(this).data('id'), $(this).data('link'));
        });

    })
    .fail(function () {
        notifBox.html('<div class="p-3 text-danger">Failed to load notifications</div>');
    });
}


function goToNotif(id, url) {
    if (!id || !url) return;

    $.post(originss + "/ajax/service/get_notifications.php", {
        id: id,
        method: 'mark_read'
    }).always(function () {
        window.open(url, '_blank');
        openNotif();
    });
}



    
</script>