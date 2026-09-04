<?php
    $url  = "http://smsresell.cwd.co.in/api/checkbalance.php?user=senthil123&pass=srihema567";
    $urls = "http://promodnd.cwd.co.in/api/creditapi?key=5cadcbbd9c385e6a16526b8226abff1e&route=2";

    $response = @file_get_contents($url);
    $result   = json_decode(@file_get_contents($urls));
?>



<style>

    .switch input:checked+.slider-btn {
        background: #07a131 !important;
    }




    

    
    textarea {
        height: 100px;
        padding: 12px 20px;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
        background-color: #f8f8f8;
        font-size: 16px;
        resize: none;
    }

    input,

    select {

        border: 1px solid #CCC;



    }



    input,

    button {

        height: 35px;

        margin: 0;

        padding: 6px 12px;

        border-radius: 2px;

        font-family: inherit;

        font-size: 100%;

        color: inherit;

    }



    #mytext {

        width: 361px;

        height: 63px;

        border: 0px;

    }

    .back-arrow-btn i {
        background: #ffffff;
        font-size: 16px;
        padding: 2px 3px;
        border-radius: 50px;
        border: 2px solid #6c6e70;
        color: #6c6e70;
        margin-right: 15px;
        width: 24px;
        height: 24px;
    }
</style>

<script>
    window.onload = function () {

        var page_origin = window.location.origin;

        let anchor = document.getElementById("anchor");

        anchor.href = page_origin;

    }
</script>


<div class="main-content app-content mt-0">



    <div class="side-app">

        <input type="hidden" id="tabID" value="agents">

        <!-- CONTAINER -->

        <div class="main-container container-fluid">

            <div class="page-header">

                
                <h1 class="page-title"><a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left"
                            onclick="history.go(-1)" aria-hidden="true"></i></a>Whtsapp / SMS</h1>

                <div>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page">WhatsApp Report</li>

                    </ol>

                </div>

            </div>

            <!-- PAGE-HEADER END -->



            <!-- ROW-1 -->

            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">

                   <div class="row">

                    <div class="col-lg-12">

                        <!-- ===== Notification Settings ===== -->
                        <h3 class="mb-3">OTP Notification</h3>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card shadow-sm mb-3">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1">WhatsApp</h5>
                                            <small class="text-muted">
                                                Enable WhatsApp alerts and OTP
                                            </small>
                                        </div>

                                        <label class="switch">
                                            <input type="checkbox" id="whatsapp_toggle">
                                            <span class="slider slider-btn round" ></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card shadow-sm mb-3">
                                    <div class="card-body">
                            
                                        <!-- Main SMS Setting -->
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <h5 class="mb-1">SMS</h5>
                                                <small class="text-muted">Enable SMS alerts and OTP</small>
                                            </div>
                                        </div>
                            
                                        <hr>
                            
                                        <!-- SMS Options -->
                                        <div class="row g-3">
                            
                                            <!-- SMSRESELL -->
                                            <div class="col-12 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">Send via SMSRESELL</h6><strong>Credit: <?php echo htmlspecialchars($response ?? 'N/A'); ?></strong>
                                                    <!--<small class="text-muted">Enable SMS alerts and OTP</small>-->
                                                </div>
                                                <label class="switch mb-0">
                                                    <input type="checkbox" id="sms_smsresell">
                                                    <span class="slider slider-btn round"></span>
                                                </label>
                                            </div>
                            
                                            <!-- PROMO DND -->
                                            <div class="col-12 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">Send via PROMO DND</h6><strong>Credit: <?php echo $result->Credits ?? 'N/A'; ?></strong>
                                                    <!--<small class="text-muted">Enable SMS alerts and OTP</small>-->
                                                </div>
                                                <label class="switch mb-0">
                                                    <input type="checkbox" id="sms_promo_dnd">
                                                    <span class="slider slider-btn round"></span>
                                                </label>
                                            </div>
                            
                                        </div>
                            
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- ===== OTP Settings ===== -->
                        <h3 class="mt-4 mb-3">KYC Notification</h3>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card shadow-sm mb-3">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1">WhatsApp</h5>
                                            <small class="text-muted">
                                                Enable WhatsApp Notification
                                            </small>
                                        </div>

                                        <label class="switch">
                                            <input type="checkbox" id="whatsapp_kyc">
                                            <span class="slider slider-btn round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card shadow-sm mb-3">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1">SMS</h5>
                                            <small class="text-muted">
                                                Enable SMS Notification
                                            </small>
                                        </div>

                                        <label class="switch">
                                            <input type="checkbox" id="sms_kyc">
                                            <span class="slider slider-btn round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card shadow-sm mb-3">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1">Push Notification</h5>
                                            <small class="text-muted">
                                                Enable SMS Notification
                                            </small>
                                        </div>

                                        <label class="switch">
                                            <input type="checkbox" id="push_kyc">
                                            <span class="slider  slider-btn round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                  </div>

                </div>

            </div>


            <!-- ROW-4 END -->

        </div>

        <!-- CONTAINER END -->

    </div>

</div>

<script>
$(document).ready(function () {

    /** ============================
     *  Toggle → Backend mapping
     *  ============================ */
    const toggleMap = {
        whatsapp_toggle: 'whatsapp_message',
        sms_smsresell: 'sms_smsresell',
        sms_promo_dnd: 'sms_promo_dnd',
        whatsapp_kyc: 'whatsapp_kyc',
        sms_kyc: 'sms_kyc',
        push_kyc: 'push_kyc'
    };

    /** ============================
     *  Generic toggle handler
     *  ============================ */
    $('input[type="checkbox"]').on('change', function () {
        const id = this.id;

        if (!toggleMap[id]) return;

        const status = this.checked ? 1 : 0;

        sendToggleRequest(toggleMap[id], status);
    });

    /** ============================
     *  AJAX sender
     *  ============================ */
    function sendToggleRequest(method, status) {
        $.ajax({
            url: '/ajax/service/message_type_services.php',
            type: 'POST',
            dataType: 'json',
            data: {
                method: method,
                status: status
            },
            success: function (res) {
                if (res.type === '1') {
                    toast('success', res.result);
                }
                fetchStatus();
            },
            error: function (xhr, status, error) {
                console.error(method + ' failed:', error);
            }
        });
    }

    /** ============================
     *  Fetch current status
     *  ============================ */
    function fetchStatus() {
        $.ajax({
            url: '/ajax/service/message_type_services.php',
            type: 'POST',
            dataType: 'json',
            data: { method: 'fetch_status' },
            success: function (res) {
                resetToggles();

                if (res.type !== '1') return;

                // OTP Notification
                if (res.result.mess_type === 'whatsapp') {
                    $('#whatsapp_toggle').prop('checked', true);
                }

                if (res.result.mess_type === 'sms') {
                    if (res.result.mess_config === 'smsresell') {
                        $('#sms_smsresell').prop('checked', true);
                    }
                    if (res.result.mess_config === 'promodnd') {
                        $('#sms_promo_dnd').prop('checked', true);
                    }
                }

                // KYC Notification
                if (res.result.template_type === 'sms') {
                    $('#sms_kyc').prop('checked', true);
                }

                if (res.result.template_type === 'whatsapp') {
                    $('#whatsapp_kyc').prop('checked', true);
                }

                if (res.result.template_type === 'push_notification') {
                    $('#push_kyc').prop('checked', true);
                }
            },
            error: function (xhr, status, error) {
                console.error('Fetch status failed:', error);
            }
        });
    }

    /** ============================
     *  Reset all toggles
     *  ============================ */
    function resetToggles() {
        Object.keys(toggleMap).forEach(id => {
            $('#' + id).prop('checked', false);
        });
    }

    /** ============================
     *  Toast helper
     *  ============================ */
    function toast(icon, message) {
        Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true
        }).fire({
            icon: icon,
            title: message
        });
    }

    /** Initial load */
    fetchStatus();
});
</script>