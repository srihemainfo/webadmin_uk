<style>
    /* Clean standard table overrides */
    .standard-table thead th {
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #555;
        background-color: #f4f6f9;
        border-bottom: 2px solid #e9ecef;
    }
    .standard-table tbody td {
        font-size: 14px;
        color: #333;
        vertical-align: middle;
    }
    
    /* Solid Modal Table Fixes */
    .solid-modal-table thead th {
        background-color: #f8f9fa !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.06); 
    }
</style>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <br>
            <div class="page-header align-items-center mb-4">
                <h1 class="page-title m-0" style="color: #333;">
                    <a href="javascript:void(0)" class="back-arrow-btn" onclick="history.go(-1)"><i class="fa fa-chevron-left"></i></a> 
                    Synced Contacts
                </h1>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover standard-table" id="syncedUsersTable" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th width="10%" class="text-center">S.No</th>
                                            <th width="30%">User Name</th>
                                            <th width="25%">Mobile Number</th>
                                            <th width="20%" class="text-center">Total Contacts</th>
                                            <th width="15%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="syncedUsersTableBody">
                                        <!-- Data injected via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ROBUST SOLID MODAL -->
<div class="modal fade" id="viewContactsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <!-- Using modal-dialog-scrollable makes the modal body naturally scrollable without hacking heights -->
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable"> 
        <div class="modal-content shadow-lg border-0"> 
            
            <div class="modal-header bg-light border-bottom d-flex justify-content-between align-items-center py-2 px-3">
                <h5 class="modal-title fw-bold text-dark m-0" style="font-size: 16px;">
                    <i class="fa fa-address-book text-primary me-2"></i>Contacts: <span id="modalUserName" class="text-primary"></span>
                </h5>
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-dark btn-sm fw-bold shadow-sm" id="copyAllContactsBtn">
                        <i class="fa fa-copy me-1"></i> Copy All
                    </button>
                    <button type="button" class="btn-close shadow-none m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            
            <div class="modal-body p-0">
                <table class="table table-hover table-striped mb-0 standard-table solid-modal-table">
                    <!-- sticky-top locks the header flawlessly to the top of the scrolling modal-body -->
                    <thead class="sticky-top" style="top: 0; z-index: 5;">
                        <tr>
                            <th width="10%" class="text-center border-0">#</th>
                            <th width="45%" class="border-0">Contact Name</th>
                            <th width="45%" class="border-0">Mobile Number</th>
                        </tr>
                    </thead>
                    <tbody id="userContactsDetailsBody">
                        <tr><td colspan="3" class="text-center py-5 text-muted"><i class="fa fa-spinner fa-spin me-2"></i>Loading...</td></tr>
                    </tbody>
                </table>
            </div>
            
            <div class="modal-footer bg-light border-top py-2 px-3">
                <button type="button" class="btn btn-secondary btn-sm px-4 fw-bold" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden textarea for copy fallback -->
<textarea id="hiddenPhoneContainer" style="position: absolute; left: -9999px; opacity: 0;"></textarea>
<script>
    let preloadedContactsCache = {}; // Holds backend background data
    let currentCommaSeparatedPhones = "";

    $(document).ready(function() {
        loadSyncedUsersList();
    });

    // 1. Load Main Users Table
    function loadSyncedUsersList() {
        $.ajax({
            url: window.location.origin + "/ajax/service/datatable_services.php",
            type: "POST",
            dataType: "json",
            data: { method: 'get_synced_users_list' },
            beforeSend: function() {
                $('#syncedUsersTableBody').html('<tr><td colspan="5" class="text-center py-4"><i class="fa fa-spinner fa-spin me-2"></i>Loading Dashboard...</td></tr>');
            },
            success: function(res) {
                if (res.type == 1) {
                    let html = '';
                    if (res.data.length > 0) {
                        res.data.forEach(function(user, index) {
                            let safeName = (user.name || 'Unknown').replace(/'/g, "\\'"); 
                            html += `
                                <tr>
                                    <td class="text-center fw-bold text-muted">${index + 1}</td>
                                    <td class="fw-bold">${user.name || 'Unknown'}</td>
                                    <td>${user.mobile || 'N/A'}</td>
                                    <td class="text-center"><span class="badge bg-light text-dark border">${user.contact_count}</span></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary fw-bold px-3 py-1" onclick="viewUserContacts(${user.id}, '${safeName}')">
                                            View Contacts
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        html = '<tr><td colspan="5" class="text-center py-4">No users with synced contacts found.</td></tr>';
                    }
                    
                    $('#syncedUsersTableBody').html(html);
                    
                    // Initialize DataTable
                    if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#syncedUsersTable')) {
                        $('#syncedUsersTable').DataTable({
                            pageLength: 25,
                            ordering: true,
                            language: { search: "Quick Search:" }
                        });
                    }

                    // 👉 INIT BACKGROUND PRELOADING HERE
                    preloadAllContactDetailsInBg();

                } else {
                    $('#syncedUsersTableBody').html(`<tr><td colspan="5" class="text-center text-danger">${res.message}</td></tr>`);
                }
            }
        });
    }

    // 2. Preload ALL contacts silently in the background
    function preloadAllContactDetailsInBg() {
        $.post(window.location.origin + "/ajax/service/datatable_services.php", { method: 'get_all_synced_contacts' }, function(res) {
            if (res.type == 1 && res.data) {
                preloadedContactsCache = res.data;
            }
        }, 'json');
    }

    // 3. Open Modal & Render specific contacts
    window.viewUserContacts = function(userId, userName) {
        $('#modalUserName').text(userName);
        currentCommaSeparatedPhones = ""; 
        
        var myModal = new bootstrap.Modal(document.getElementById('viewContactsModal'));
        myModal.show();

        // If the background load already finished, render instantly!
        if (preloadedContactsCache[userId]) {
            renderModalTable(preloadedContactsCache[userId].contacts, preloadedContactsCache[userId].all_phones_str);
        } else {
            // Fallback: If preloading isn't done, fetch via ajax
            $('#userContactsDetailsBody').html('<tr><td colspan="3" class="text-center py-5 text-muted"><i class="fa fa-spinner fa-spin me-2"></i>Fetching contacts...</td></tr>');
            $.post(window.location.origin + "/ajax/service/datatable_services.php", { method: 'get_user_contacts_details', user_id: userId }, function(res) {
                if (res.type == 1) {
                    renderModalTable(res.data, res.all_phones_str);
                } else {
                    $('#userContactsDetailsBody').html(`<tr><td colspan="3" class="text-center text-danger">${res.message}</td></tr>`);
                }
            }, 'json');
        }
    };

    // Helper function to build modal HTML
    function renderModalTable(contactsArray, allPhonesStr) {
        currentCommaSeparatedPhones = allPhonesStr;
        let html = '';
        if (contactsArray && contactsArray.length > 0) {
            contactsArray.forEach(function(contact, index) {
                html += `
                    <tr>
                        <td class="text-center fw-bold text-muted">${index + 1}</td>
                        <td class="fw-semibold">${contact.contact_name || '-'}</td>
                        <td>${contact.phone || '-'}</td>
                    </tr>
                `;
            });
        } else {
            html = '<tr><td colspan="3" class="text-center py-4">No contact details found.</td></tr>';
        }
        $('#userContactsDetailsBody').html(html);
    }

    // 4. Handle Copy Functionality
    $('#copyAllContactsBtn').on('click', function() {
        const $btn = $(this);
        const originalText = $btn.html();

        if (!currentCommaSeparatedPhones || currentCommaSeparatedPhones.trim() === '') {
            if (typeof toast === 'function') toast('warning', 'No mobile numbers available to copy!');
            else alert('No mobile numbers available to copy!');
            return;
        }

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(currentCommaSeparatedPhones).then(() => {
                showCopySuccessState($btn, originalText);
            }).catch(err => {
                fallbackCopyMethod(currentCommaSeparatedPhones, $btn, originalText);
            });
        } else {
            fallbackCopyMethod(currentCommaSeparatedPhones, $btn, originalText);
        }
    });

    function fallbackCopyMethod(text, $btn, originalText) {
        let $temp = $('#hiddenPhoneContainer');
        $temp.val(text).select();
        try {
            document.execCommand("copy");
            showCopySuccessState($btn, originalText);
        } catch (err) {
            if (typeof toast === 'function') toast('error', 'Failed to copy to clipboard.');
            else alert('Failed to copy to clipboard.');
        }
        $temp.val(''); 
    }

    function showCopySuccessState($btn, originalText) {
        $btn.html('<i class="fa fa-check me-1"></i> Copied!');
        $btn.removeClass('btn-dark').addClass('btn-success');
        
        if (typeof toast === 'function') toast('success', 'Mobile numbers copied to clipboard!');

        setTimeout(() => {
            $btn.html(originalText);
            $btn.removeClass('btn-success').addClass('btn-dark');
        }, 2000);
    }
</script>