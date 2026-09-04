<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    body { background-color: #f4f6f9; }

    /* =========================================
       UI COMPONENT STYLES
    ========================================= */
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
        text-align: center;
        line-height: 18px;
    }

    /* Standard Normal Table Headers with Increased Font Size */
    table.dataTable thead th {
        vertical-align: middle;
        font-size: 14px;
        font-weight: bold;
        color: #333;
        background-color: #f8f9fa !important; 
        border-bottom: 2px solid #dee2e6 !important;
        text-transform: uppercase;
    }
    
    /* Increased Font Size for Table Data */
    table.dataTable tbody td {
        vertical-align: middle;
        font-size: 15px; 
        color: #212529;
        font-weight: 500;
        padding: 12px 8px; /* Slightly larger padding for breathing room */
    }

    /* Single Custom DataTables Loader (Only Bouncing Dots) */
    div.dataTables_wrapper div.dataTables_processing {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 150px;
        margin-left: -75px;
        margin-top: -30px;
        text-align: center;
        padding: 15px 0;
        background: #ffffff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-radius: 6px;
        z-index: 100;
        border: 1px solid #ddd;
    }
    
    /* Bouncing blue dots for the loader */
    .loading-dots span {
        display: inline-block;
        width: 10px;
        height: 10px;
        background-color: #0d6efd;
        border-radius: 50%;
        margin: 0 4px;
        animation: bounce 1.4s infinite ease-in-out both;
    }
    .loading-dots span:nth-child(1) { animation-delay: -0.32s; }
    .loading-dots span:nth-child(2) { animation-delay: -0.16s; }
    @keyframes bounce {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }

    /* DataTables Buttons Styling */
    .dt-buttons .btn {
        font-size: 14px;
        padding: 6px 16px;
        background-color: #f8f9fa;
        color: #333;
        border: 1px solid #ced4da;
    }
    .dt-buttons .btn:hover { background-color: #e9ecef; }
    .dataTables_length select { font-size: 14px; padding: 4px 8px; }

    /* Map Modal & Pin Styles */
    #singleDriverMap {
        height: 500px;
        width: 100%;
        border-radius: 0 0 8px 8px;
        z-index: 1;
    }
    .modal-body.map-body { padding: 0; }
    
    /* Custom Red Border X Button for Modal */
    .btn-close-red {
        background-color: transparent;
        border: 2px solid #dc3545;
        color: #dc3545;
        font-size: 18px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-close-red:hover {
        background-color: #dc3545;
        color: white;
    }

    .pulse-marker {
        width: 18px; 
        height: 18px; 
        background: #0d6efd; 
        border-radius: 50%; 
        border: 3px solid white; 
        box-shadow: 0 0 10px rgba(0,0,0,0.5);
    }
</style>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            
            <div class="page-header mt-4 mb-4">
                <h1 class="page-title fw-bold ">
                    <a href="javascript:void(0)" class="back-arrow-btn"><i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i></a>
                    <i class="fa fa-satellite-dish me-2"></i> Lat Long Log
                </h1>
            </div>

            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header border-bottom-0 pt-4 pb-0">
                            <h3 class="card-title fw-bold">Driver's Locations</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover text-center align-middle w-100" id="driverLocationTable">
                                    <thead>
                                        <tr>
                                            <th width="5%">S.No</th>
                                            <th width="18%">Driver Name</th>
                                            <th width="20%">Email</th>
                                            <th width="12%">Mobile</th>
                                            <th width="10%">Latitude</th>
                                            <th width="10%">Longitude</th>
                                            <th width="15%">Time</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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

<div class="modal fade" id="mapModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                <h5 class="modal-title fw-bold text-dark" id="mapModalLabel">
                    <i class="fa fa-map-marker-alt text-primary me-2"></i> Location Details
                </h5>
                <button type="button" class="btn-close-red" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body map-body">
                <div id="singleDriverMap"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const API_DOMAIN = "<?= rtrim(TEST_API_DOMAIN_2 ?? 'https://www.goride.run/api/v1-cus', '/') ?>";
    const AUTH_TOKEN = "Bearer asdfghjklpoiuytrewqzxcvbnm!@$%^&*()";

    let driverLocationTable = null;
    let singleMap = null;
    let singleMarker = null;

    $(function() {
        searchLogs(); // Initial load
    });

    // Fetch and Load DataTables
    function searchLogs() {
        if ($.fn.DataTable.isDataTable('#driverLocationTable')) {
            $('#driverLocationTable').DataTable().destroy();
        }

        let exportTitle = 'Lat Long Log';

        driverLocationTable = $('#driverLocationTable').DataTable({
            processing: true,
            serverSide: false,
            pageLength: 25,
            lengthMenu: [10, 25, 50, 100],
            dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center gap-3'l B><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                { extend: 'copy', className: 'btn btn-sm' },
                { extend: 'excelHtml5', className: 'btn btn-sm', title: exportTitle }
            ],
            language: {
                search: "Search:",
                emptyTable: "No tracking data found.",
                // Exclusively the bouncing dots as requested
            },
            ajax: {
                url: API_DOMAIN + "/admin-get-driver-list",
                type: "POST",
                headers: { "Authorization": AUTH_TOKEN },
                dataSrc: function (json) {
                    if (!json.status) {
                        toast('error', 'Failed to retrieve driver list.');
                        return [];
                    }
                    return json.data;
                },
                error: function() {
                    toast('error', 'Network error while fetching data.');
                }
            },
            columns: [
                { data: null, render: (data, type, row, meta) => meta.row + 1 },
                { data: "name", render: data => `<strong class="text-dark">${data || '-'}</strong>` },
                { data: "email", render: data => data ? data : '-' },
                { data: "mobile", render: data => data ? data : '-' },
                { 
                    data: "s_lat", 
                    render: function(data) {
                        let lat = parseFloat(data);
                        return (!isNaN(lat) && lat !== 0) ? lat.toFixed(6) : '-';
                    }
                },
                { 
                    data: "s_lang", 
                    render: function(data) {
                        let lng = parseFloat(data);
                        return (!isNaN(lng) && lng !== 0) ? lng.toFixed(6) : '-';
                    }
                },
                { 
                    data: "updated_at", 
                    render: data => data ? data : '-' 
                },
                { 
                    data: null, 
                    orderable: false,
                    render: function(data) {
                        let lat = parseFloat(data.s_lat);
                        let lng = parseFloat(data.s_lang);
                        let hasLoc = (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0);
                        
                        // IF NO LOCATION, REMOVE BUTTON COMPLETELY AND JUST RETURN DASH
                        if (!hasLoc) {
                            return '<span class="text-muted fw-bold">-</span>';
                        }
                        
                        return `
                            <button class="btn btn-sm btn-secondary text-white view-map-btn shadow-sm" 
                                data-lat="${lat}" 
                                data-lng="${lng}" 
                                data-name="${data.name || 'Driver'}"
                                data-mobile="${data.mobile || '-'}"
                                data-time="${data.updated_at || '-'}">
                                <i class="fa fa-map-marker-alt me-1"></i> View on Map
                            </button>
                        `;
                    }
                }
            ]
        });
    }

    // Handle "View on Map" Click
    $(document).on('click', '.view-map-btn', function() {
        let lat = parseFloat($(this).data('lat'));
        let lng = parseFloat($(this).data('lng'));
        let name = $(this).data('name');
        let mobile = $(this).data('mobile');
        let time = $(this).data('time');

        // Update Modal Header
        $('#mapModalLabel').html(`<i class="fa fa-map-marker-alt text-primary me-2"></i> Location Details`);
        
        // Show Modal
        $('#mapModal').modal('show');

        // Initialize Map after modal animation completes
        setTimeout(() => {
            if(!singleMap) {
                singleMap = L.map('singleDriverMap').setView([lat, lng], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(singleMap);
            } else {
                singleMap.invalidateSize(); 
                singleMap.flyTo([lat, lng], 16, { animate: true, duration: 1.2 });
            }

            if(singleMarker) singleMap.removeLayer(singleMarker);

            let customIcon = L.divIcon({
                className: '',
                html: `<div class="pulse-marker"></div>`,
                iconSize: [18, 18],
                iconAnchor: [9, 9]
            });

            singleMarker = L.marker([lat, lng], {icon: customIcon}).addTo(singleMap);
            
            // Popup UI explicitly matching requested format
            singleMarker.bindPopup(`
                <div style="font-family: sans-serif; min-width: 160px; text-align: left; padding: 2px;">
                    <strong class="text-dark fs-6 d-block border-bottom pb-1 mb-2">${name}</strong>
                    <div class="small text-muted mb-1"><i class="fa fa-phone me-1"></i> ${mobile}</div>
                    <div class="small text-dark mt-2">
                        <i class="fa fa-clock text-primary me-1"></i> <strong>Time:</strong><br>
                        <span class="text-secondary ms-3">${time}</span>
                    </div>
                </div>
            `).openPopup();

        }, 300); 
    });

    function toast(icon, message) {
        Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000
        }).fire({ icon: icon, title: message });
    }
</script>