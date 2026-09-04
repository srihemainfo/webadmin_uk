<?php
$today = date('Y-m-d');
?>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid mt-4">
            
            <div class="d-flex flex-wrap gap-2 mb-3" id="carpoolStatusTabs">
                <button class="btn bg-white shadow-sm border-0 fw-bold carpool-status-tab active px-4 py-3" data-tab="current" style="color: #0d6efd; border-radius: 6px;">
                    Current Go Car Pool Jobs
                </button>
                <button class="btn bg-white shadow-sm border-0 fw-bold text-muted carpool-status-tab px-4 py-3" data-tab="completed" style="border-radius: 6px;">
                    Completed Go Car Pool Jobs
                </button>
                <button class="btn bg-white shadow-sm border-0 fw-bold text-muted carpool-status-tab px-4 py-3" data-tab="expired" style="border-radius: 6px;">
                    Expired Go Car Pool Jobs
                </button>
                <button class="btn bg-white shadow-sm border-0 fw-bold text-muted carpool-status-tab px-4 py-3" data-tab="cancelled" style="border-radius: 6px;">
                    Cancelled Go Car Pool Jobs
                </button>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-3">
                            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                                <i class="fa fa-car-side text-secondary me-2"></i> Go Carpool Jobs
                            </h5>
                        </div>
                        <div class="col-md-9">
                            <div class="row g-2 justify-content-end align-items-center">
                                <div class="col-md-3">
                                    <select id="carpoolFilterType" class="form-select form-select-sm shadow-none fw-semibold text-primary border-light" style="background-color: #f8f9fa;">
                                        <option value="all" selected>All Pools</option>
                                        <option value="public">Public</option>
                                        <option value="private">Private</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select id="carpoolDateFilterType" class="form-select form-select-sm shadow-none fw-semibold text-secondary border-light" style="background-color: #f8f9fa;">
                                        <option value="created_at" selected>Created Date</option>
                                        <option value="pickup">Pickup Date</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white border-end-0"><i class="fa fa-calendar-alt text-muted"></i></span>
                                        <input type="text" id="carpoolDateRange" class="form-control border-start-0 text-center fw-bold shadow-none bg-white" placeholder="Select Date" readonly style="cursor:pointer;">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-muted"></i></span>
                                        <input type="text" id="searchCarpool" class="form-control border-start-0 shadow-none bg-white" placeholder="Search job, name, city.">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3 border-light">

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            <span class="badge rounded-pill px-4 py-2 text-white shadow-sm" style="background-color: #20c997; font-size: 12px; font-weight: 600;" id="publicPoolCount">0 Public Pools</span>
                            <span class="badge rounded-pill px-4 py-2 text-white shadow-sm" style="background-color: #0dcaf0; font-size: 12px; font-weight: 600;" id="privatePoolCount">0 Private Pools</span>
                        </div>
                        <div class="border border-2 border-dark text-dark fw-bold px-4 py-2" style="font-size: 14px; border-radius: 4px; background: #fff;" id="totalPoolCount">
                            Total Jobs: 0
                        </div>
                    </div>
                </div>
            </div>

            <div id="carpoolLoader" class="text-center py-5" style="display:none">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
                <div class="mt-3 small text-muted fw-bold">Fetching Carpool Jobs...</div>
            </div>

            <div id="carpoolEmpty" class="card shadow-sm border-0 mb-3 d-none">
                <div class="card-body text-center py-5">
                    <i class="fa fa-folder-open text-muted opacity-25 mb-3" style="font-size: 4rem;"></i>
                    <h6 class="text-secondary fw-semibold">No Carpool Jobs Found</h6>
                    <p class="text-muted small mb-0">Try adjusting your search or date filters.</p>
                </div>
            </div>

            <div class="row g-3" id="carpoolJobsList">
            </div>

        </div>
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

<script>
    const API_DOMAIN_2 = "<?= API_DOMAIN_2 ?>";
</script>
<script src="js/carpool_jobs.js?v=<?= time() ?>"></script>

<script>
    $(document).ready(function() {
        let allCarpoolJobs = { public: [], private: [] };
        let carpoolStartDate = '';
        let carpoolEndDate = '';
        let searchTimeout = null;
        let carpoolTabType = 'current'; 

        if ($.fn.daterangepicker) {
            $('#carpoolDateRange').daterangepicker({
                autoUpdateInput: false,
                opens: 'left',
                locale: { format: 'DD/MM/YYYY', cancelLabel: 'Clear' },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')]
                }
            });

            $('#carpoolDateRange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                carpoolStartDate = picker.startDate.format('YYYY-MM-DD');
                carpoolEndDate = picker.endDate.format('YYYY-MM-DD');
                loadCarpoolJobs();
            });

            $('#carpoolDateRange').on('cancel.daterangepicker', function() {
                $(this).val('');
                carpoolStartDate = '';
                carpoolEndDate = '';
                loadCarpoolJobs();
            });
        }

        $('.carpool-status-tab').on('click', function(e) {
            e.preventDefault();
            
            $('.carpool-status-tab')
                .removeClass('active text-primary')
                .addClass('text-muted')
                .css('background-color', '#f8f9fa');
            
            $(this)
                .addClass('active text-primary')
                .removeClass('text-muted')
                .css('background-color', '#fff');
            
            carpoolTabType = $(this).data('tab');
            loadCarpoolJobs(); 
        });

        $('button[data-bs-target="#carpooling"]').on('shown.bs.tab', function() {
            loadCarpoolJobs();
        });

        $('#carpoolFilterType, #carpoolDateFilterType').on('change', function() {
            loadCarpoolJobs();
        });

        $('#searchCarpool').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadCarpoolJobs();
            }, 500); 
        });

        setTimeout(function() {
            var $activeTab = $('.carpool-status-tab[data-tab="current"]');
            if ($activeTab.length > 0) {
                $activeTab.trigger('click');
            }
        }, 500); 

        function loadCarpoolJobs() {
            $('#carpoolJobsList').empty();
            $('#carpoolEmpty').addClass('d-none');
            $('#carpoolLoader').show();

            const searchVal = $('#searchCarpool').val().trim();
            const typeFilter = $('#carpoolFilterType').val(); 
            const dateFilterType = $('#carpoolDateFilterType').val();

            $.ajax({
                url: window.location.origin + "/ajax/service/dashBoard_Services.php",
                type: "POST",
                dataType: "json",
                data: { 
                    method: 'carpoolJobList',
                    search: searchVal,
                    startDate: carpoolStartDate,
                    endDate: carpoolEndDate,
                    filterType: dateFilterType,
                    tabType: carpoolTabType 
                },
                headers: {
                    "Authorization": "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()"
                },
                success: function(res) {
                    if (res.status && res.data) {
                        allCarpoolJobs.public = res.data.public || [];
                        allCarpoolJobs.private = res.data.private || [];
                        
                        $('#publicPoolCount').text(allCarpoolJobs.public.length + ' Public Pools');
                        $('#privatePoolCount').text(allCarpoolJobs.private.length + ' Private Pools');

                        const totalCount = allCarpoolJobs.public.length + allCarpoolJobs.private.length;
                        $('#totalPoolCount').text('Total Jobs: ' + totalCount);

                        renderCarpoolList(typeFilter);
                    } else {
                        $('#carpoolEmpty').removeClass('d-none');
                        $('#publicPoolCount').text('0 Public Pools');
                        $('#privatePoolCount').text('0 Private Pools');
                        $('#totalPoolCount').text('Total Jobs: 0');
                    }
                },
                error: function(xhr, status, error) {
                    $('#carpoolEmpty').removeClass('d-none');
                },
                complete: function() {
                    $('#carpoolLoader').hide();
                }
            });
        }

        function renderCarpoolList(typeFilter) {
            const $list = $('#carpoolJobsList');
            $list.empty();

            let jobsToRender = [];

            if (typeFilter === 'all' || typeFilter === 'public') {
                allCarpoolJobs.public.forEach(job => jobsToRender.push({ ...job, poolType: 'public' }));
            }
            if (typeFilter === 'all' || typeFilter === 'private') {
                allCarpoolJobs.private.forEach(job => jobsToRender.push({ ...job, poolType: 'private' }));
            }

            if (jobsToRender.length === 0) {
                $('#carpoolEmpty').removeClass('d-none');
                return;
            }

            let html = '';
            jobsToRender.forEach(job => {
                html += generateCarpoolCard(job);
            });

            $list.html(html);
        }

        function generateCarpoolCard(job) {
            const isPublic = job.poolType === 'public';
            const badgeClass = isPublic ? 'bg-success' : 'bg-secondary';
            const badgeText = isPublic ? 'Public Pool' : 'Private Pool';
            const jobNo = job.job_no || 'N/A';
            
            const hostName = job.driver_name || job.name || 'Unknown Host';
            const hostMobile = job.driver_mobile || job.mobile || 'N/A';
            const cleanMobile = hostMobile.replace(/\D/g, '');
            const waMobile = cleanMobile.length === 10 ? '91' + cleanMobile : cleanMobile;
            const hostInitial = hostName.charAt(0).toUpperCase();

            const pickupDate = job.pickup_date ? new Date(job.pickup_date.replace(' ', 'T')).toLocaleString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true }).replace(',', ' -') : 'N/A';
            const createdDate = job.created_at ? new Date(job.created_at.replace(' ', 'T')).toLocaleString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : 'N/A';

            const totalSeats = parseInt(job.total_seats) || 4;
            const filledSeats = parseInt(job.filled_seats) || 0;
            const availableSeats = Math.max(0, totalSeats - filledSeats);
            const progressPercent = (filledSeats / totalSeats) * 100;
            const barColor = progressPercent >= 100 ? 'bg-danger' : (progressPercent >= 75 ? 'bg-warning' : 'bg-success');

            const invites = job.invitations || [];
            
            let uniqueStatuses = [...new Set(invites.map(inv => (inv.status || 'pending').toLowerCase()))];
            let filterOptions = `<option value="all">All</option>`;
            uniqueStatuses.forEach(st => {
                let displayStatus = st.charAt(0).toUpperCase() + st.slice(1); 
                filterOptions += `<option value="${st}">${displayStatus}</option>`;
            });

            let filterHtml = invites.length > 0 ? `
                <select class="form-select form-select-sm border border-secondary text-dark fw-bold request-status-filter py-0 shadow-sm" style="width: auto; font-size: 10px; height: 22px; cursor: pointer;">
                    ${filterOptions}
                </select>
            ` : '';

            let requestsHtml = '';
            if (invites.length === 0) {
                requestsHtml = `
                    <div class="h-100 d-flex flex-column justify-content-center align-items-center p-3 text-center rounded-3" style="border: 1px dashed #ccc; background: #fafafa;">
                        <i class="fa fa-inbox text-muted opacity-25 mb-2" style="font-size: 2rem;"></i>
                        <span class="text-secondary fw-semibold" style="font-size: 11px;">No requests</span>
                    </div>
                `;
            } else {
                invites.forEach(inv => {
                    const inviterName = inv.inviter_name || 'Unknown';
                    const inviterPhone = inv.inviter_mobile || 'N/A';
                    const inviteeName = inv.invitee_name || (inv.invitee_phone_hash ? 'Guest User' : 'Unknown');
                    const inviteePhone = inv.invitee_mobile || 'N/A';

                    const status = (inv.status || 'pending').toLowerCase();
                    let statusBadge = 'bg-warning text-dark';
                    let statusIcon = 'fa-clock';
                    
                    if (status === 'accepted' || status === 'accept') {
                        statusBadge = 'bg-success text-white';
                        statusIcon = 'fa-check-circle';
                    } else if (status === 'rejected' || status === 'reject' || status === 'declined') {
                        statusBadge = 'bg-danger text-white';
                        statusIcon = 'fa-times-circle';
                    }

                    const dateStr = inv.created_at ? new Date(inv.created_at).toLocaleString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true }) : 'N/A';

                    requestsHtml += `
                    <div class="border bg-white mb-2 shadow-sm rounded-3 p-2 request-item" data-status="${status}">
                        <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">
                            <span class="text-muted fw-bold" style="font-size: 10px;"><i class="fa fa-calendar-alt me-1"></i> ${dateStr}</span>
                            <span class="badge ${statusBadge} px-1 py-1 shadow-sm" style="font-size: 9px; text-transform: uppercase;">
                                <i class="fa ${statusIcon} me-1"></i> ${status}
                            </span>
                        </div>
                        <div class="row align-items-center text-center g-0 position-relative">
                            <div class="col-5">
                                <div class="text-muted mb-1" style="font-size: 9px; text-transform: uppercase; font-weight: 700;">Sent By</div>
                                <div class="fw-bold text-dark text-truncate" style="font-size: 11px;" title="${inviterName}">${inviterName}</div>
                                <div class="text-primary fw-semibold" style="font-size: 10px;"><i class="fa fa-phone me-1" style="font-size: 8px;"></i>${inviterPhone}</div>
                            </div>
                            <div class="col-2 d-flex justify-content-center">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center border" style="width: 20px; height: 20px;">
                                    <i class="fa fa-arrow-right text-secondary" style="font-size: 8px;"></i>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="text-muted mb-1" style="font-size: 9px; text-transform: uppercase; font-weight: 700;">Sent To</div>
                                <div class="fw-bold text-dark text-truncate" style="font-size: 11px;" title="${inviteeName}">${inviteeName}</div>
                                <div class="text-primary fw-semibold" style="font-size: 10px;"><i class="fa fa-phone me-1" style="font-size: 8px;"></i>${inviteePhone}</div>
                            </div>
                        </div>
                    </div>`;
                });
            }

            return `
            <div class="col-12 mb-3">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="row g-0">
                        
                        <div class="col-md-3 bg-light p-3 border-end d-flex flex-column justify-content-center">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px; font-size: 18px; font-weight: 700;">
                                    ${hostInitial}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark text-truncate" title="${hostName}" style="max-width: 150px;">${hostName}</h6>
                                    <span class="text-muted small">Host</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="fw-bold text-dark" style="font-size: 13px;">${hostMobile}</span>
                                ${cleanMobile ? `
                                <a href="tel:${cleanMobile}" class="text-decoration-none rounded-circle bg-white shadow-sm d-flex justify-content-center align-items-center" style="width: 24px; height: 24px;"><i class="fa fa-phone text-primary" style="font-size: 10px;"></i></a>
                                <a href="https://wa.me/${waMobile}" target="_blank" class="text-decoration-none rounded-circle bg-white shadow-sm d-flex justify-content-center align-items-center" style="width: 24px; height: 24px;"><i class="fab fa-whatsapp text-success" style="font-size: 12px;"></i></a>
                                ` : ''}
                            </div>
                            <div class="text-muted" style="font-size: 11px;">
                                <i class="fa fa-clock me-1"></i> Created: ${createdDate}
                            </div>
                        </div>

                        <div class="col-md-3 p-3 border-end">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge ${badgeClass} shadow-sm px-2 py-1" style="font-size: 11px;">${badgeText}</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                 <span class="fw-bold text-secondary" style="font-size: 12px;">${jobNo}</span>
                            </div>

                            <div class="d-flex align-items-stretch mb-3">
                                <div class="d-flex flex-column align-items-center me-3 mt-1">
                                    <i class="fa fa-circle text-success" style="font-size: 10px;"></i>
                                    <div style="width: 2px; flex-grow: 1; background: #e9ecef; margin: 4px 0;"></div>
                                    <i class="fa fa-map-marker-alt text-danger" style="font-size: 14px;"></i>
                                </div>
                                <div class="d-flex flex-column justify-content-between w-100">
                                    <div class="mb-3">
                                        <div class="text-muted" style="font-size: 10px; text-transform: uppercase; font-weight: 700;">Pickup</div>
                                        <div class="fw-bold text-dark" style="font-size: 14px;" title="${job.from_place}">${job.from_place}</div>
                                    </div>
                                    <div>
                                        <div class="text-muted" style="font-size: 10px; text-transform: uppercase; font-weight: 700;">Dropoff</div>
                                        <div class="fw-bold text-dark" style="font-size: 14px;" title="${job.to_place}">${job.to_place}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-inline-flex align-items-center bg-warning bg-opacity-10 text-dark px-2 py-2 rounded-3 border border-warning shadow-sm w-100">
                                <i class="fa fa-calendar-alt text-warning me-2 fs-5"></i>
                                <div>
                                    <div style="font-size: 9px; font-weight: 700; color: #666; text-transform: uppercase;">Departure Time</div>
                                    <div class="fw-bold" style="font-size: 11px;">${pickupDate}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 p-3 border-end bg-light d-flex flex-column" style="max-height: 280px;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="text-dark fw-bold mb-0" style="font-size: 12px; text-transform: uppercase;">
                                    <i class="fa fa-envelope-open-text me-1 text-muted"></i> Requests 
                                    <span class="badge rounded-pill bg-primary shadow-sm ms-1" style="font-size: 10px;">${invites.length}</span>
                                </h6>
                                ${filterHtml}
                            </div>
                            <div class="requests-list-container flex-grow-1" style="overflow-y: auto; overflow-x: hidden; padding-right: 5px;">
                                ${requestsHtml}
                            </div>
                        </div>

                        <div class="col-md-2 p-3 bg-white d-flex flex-column justify-content-center">
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-end mb-1">
                                    <span class="text-dark fw-bold" style="font-size: 12px;">Seat Occupancy</span>
                                    <span class="fw-bold text-secondary" style="font-size: 12px;">${filledSeats} / ${totalSeats}</span>
                                </div>
                                <div class="progress shadow-sm" style="height: 10px; border-radius: 5px; background-color: #f1f3f5;">
                                    <div class="progress-bar ${barColor} progress-bar-striped" role="progressbar" style="width: ${progressPercent}%;" aria-valuenow="${progressPercent}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="text-end mt-1">
                                    <small class="fw-bold ${availableSeats === 0 ? 'text-danger' : 'text-success'}" style="font-size: 10px;">
                                        ${availableSeats === 0 ? 'Full' : `${availableSeats} Seats Left`}
                                    </small>
                                </div>
                            </div>

                            <div class="text-center">
                                <div class="text-muted fw-bold" style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Fare Per Seat</div>
                                <h2 class="mb-0 fw-bolder text-dark">₹${job.per_seat_fare || 0}</h2>
                            </div>
                        </div>

                    </div>
                </div>
            </div>`;
        }

        $(document).on('change', '.request-status-filter', function() {
            const selectedStatus = $(this).val();
            const $requestsContainer = $(this).closest('.col-md-4').find('.requests-list-container');
            
            if (selectedStatus === 'all') {
                $requestsContainer.find('.request-item').show();
            } else {
                $requestsContainer.find('.request-item').hide();
                $requestsContainer.find(`.request-item[data-status="${selectedStatus}"]`).show();
            }
        });

    });
</script>