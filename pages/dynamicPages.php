<?php
$pageTitle = "Dynamic Pages";
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<style>
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

    .cursor-pointer {
        cursor: pointer;
    }

    .section-block-card {
        transition: all 0.2s ease-in-out;
        border-left: 4px solid #0d6efd !important;
    }

    .section-block-card.type-hero {
        border-left-color: #0d6efd !important;
    }

    .section-block-card.type-overview {
        border-left-color: #198754 !important;
    }

    .section-block-card.type-fleet_pricing {
        border-left-color: #ffc107 !important;
    }

    .section-block-card.type-why_choose {
        border-left-color: #0dcaf0 !important;
    }

    .section-block-card.type-booking_steps {
        border-left-color: #6c757d !important;
    }

    .section-block-card.type-faqs {
        border-left-color: #dc3545 !important;
    }

    .section-block-card.type-cta {
        border-left-color: #6f42c1 !important;
    }

    .section-block-card.type-custom_content {
        border-left-color: #212529 !important;
    }

    .border-dashed {
        border-style: dashed !important;
    }

    .form-check-input:checked {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
    }

    .form-switch .form-check-input {
        cursor: pointer;
    }

    .sec-badge-pill {
        background-color: #e0f2fe !important;
        color: #0369a1 !important;
        border: 1px solid #bae6fd !important;
        font-weight: 600 !important;
        padding: 5px 11px !important;
        border-radius: 6px !important;
        font-size: 12px !important;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .type-badge-pill {
        background-color: #f1f5f9 !important;
        color: #334155 !important;
        border: 1px solid #cbd5e1 !important;
        font-weight: 600 !important;
        padding: 5px 10px !important;
        border-radius: 6px !important;
        font-size: 12px !important;
    }

    /* Button icon enhancement */
    .btn-action-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .btn-action-icon:hover {
        background: #e2e8f0;
    }

    /* Schema Action Buttons */
    .btn-schema-action {
        font-size: 12px;
        font-weight: 600;
        padding: 5px 14px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
        line-height: 1.4;
    }

    .btn-schema-action:active {
        transform: translateY(0) !important;
    }

    .btn-schema-format {
        background-color: #0891b2 !important;
        color: #ffffff !important;
        border: 1px solid #0891b2 !important;
        box-shadow: 0 1px 3px rgba(8, 145, 178, 0.25);
    }

    .btn-schema-format:hover {
        background-color: #0e7490 !important;
        color: #ffffff !important;
        border-color: #0e7490 !important;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(8, 145, 178, 0.35);
    }

    .btn-schema-validate {
        background-color: #0d6efd !important;
        color: #ffffff !important;
        border: 1px solid #0d6efd !important;
        box-shadow: 0 1px 3px rgba(13, 110, 253, 0.25);
    }

    .btn-schema-validate:hover {
        background-color: #0b5ed7 !important;
        color: #ffffff !important;
        border-color: #0a58ca !important;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(13, 110, 253, 0.35);
    }

    /* Sticky Editor Header & Tabs */
    .sticky-editor-header {
        position: -webkit-sticky;
        position: sticky;
        top: 74px;
        z-index: 99;
        background: #ffffff;
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }

    .sticky-editor-header.is-pinned {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
        border-bottom: 2px solid #0d6efd !important;
    }

    /* Drag & Drop Handles and Animation */
    .drag-handle,
    .nested-drag-handle {
        cursor: grab !important;
        color: #94a3b8;
        transition: all 0.15s ease;
        user-select: none;
    }

    .drag-handle:hover,
    .nested-drag-handle:hover {
        color: #0d6efd !important;
        transform: scale(1.15);
    }

    .drag-handle:active,
    .nested-drag-handle:active {
        cursor: grabbing !important;
    }

    .sortable-ghost {
        opacity: 0.35 !important;
        background: #eff6ff !important;
        border: 2px dashed #2563eb !important;
        border-radius: 8px !important;
    }

    .sortable-chosen {
        background: #ffffff !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }

    .sortable-drag {
        opacity: 0.95 !important;
    }

    /* Section Card Enhancements */
    .section-block-card {
        border-radius: 8px !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .section-block-card:hover {
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
    }

    .section-block-card .card-header {
        border-top-left-radius: 8px !important;
        border-top-right-radius: 8px !important;
    }

    .section-badge-pill {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .sec-badge-hero {
        background: #e0f2fe;
        color: #0284c7;
    }

    .sec-badge-overview {
        background: #dcfce7;
        color: #16a34a;
    }

    .sec-badge-fleet_pricing {
        background: #fef3c7;
        color: #d97706;
    }

    .sec-badge-places_showcase {
        background: #ccfbf1;
        color: #0d9488;
    }

    .sec-badge-why_choose {
        background: #e0e7ff;
        color: #4f46e5;
    }

    .sec-badge-booking_steps {
        background: #f1f5f9;
        color: #475569;
    }

    .sec-badge-faqs {
        background: #fee2e2;
        color: #dc2626;
    }

    .sec-badge-cta {
        background: #f3e8ff;
        color: #7e22ce;
    }

    .sec-badge-custom_content {
        background: #f1f5f9;
        color: #0f172a;
    }
</style>

<script>
    window.onload = function () {
        var page_origin = window.location.origin;
        let anchor = document.getElementById("anchor");
        if (anchor) {
            anchor.href = page_origin;
        }
    }
</script>

<div class="main-content app-content mt-0">
    <div class="side-app">
        <input type="hidden" id="tabID" value="agents">
        <div class="main-container container-fluid mt-5 p-0">
            <!-- PAGE HEADER -->
            <div class="page-header pt-5 mb-3">
                <h1 class="page-title">
                    <a href="javascript:void(0)" class="back-arrow-btn">
                        <i class="fa fa-chevron-left" onclick="history.go(-1)" aria-hidden="true"></i>
                    </a>
                    <?= $pageTitle; ?>
                </h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="" id="anchor">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= $pageTitle; ?></li>
                    </ol>
                </div>
            </div>

            <!-- VIEW 1: PAGES TABLE LIST WRAPPER -->
            <div id="pagesListView">
                <!-- ACTION TOP BAR (ONLY VISIBLE ON LIST VIEW) -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 px-1">
                    <div>
                        <p class="text-muted small mb-0">Create, manage, and customize multi-route landing pages, SEO
                            metadata, and dynamic content blocks</p>
                    </div>
                    <div>
                        <button class="btn btn-primary px-4 shadow-sm fw-semibold" id="btnCreateNewPage">
                            <i class="fa fa-plus me-1"></i> Create New Page
                        </button>
                    </div>
                </div>

                <div class="card border-0 shadow-sm ">
                    <div class="card-header bg-white py-3 border-0">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="fa fa-search text-muted"></i></span>
                                    <input type="text" class="form-control bg-light border-start-0" id="searchPageInput"
                                        placeholder="Search by title or slug...">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select bg-light" id="filterPageType">
                                    <option value="">All Page Types</option>
                                    <option value="car-rental">Car Rental / Transfers</option>
                                    <option value="general">General Landing</option>
                                    <option value="tour">Tour & Outstation</option>
                                </select>
                            </div>
                            <div class="col-md-3 text-md-end">
                                <button class="btn btn-light text-secondary border" id="btnRefreshList">
                                    <i class="fa fa-refresh me-1"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="pagesTable">
                                <thead class="table-light text-uppercase fs-7 text-muted">
                                    <tr>
                                        <th style="width: 60px;" class="ps-4">ID</th>
                                        <th>Page Title & URL</th>
                                        <th>Type</th>
                                        <th>Sections</th>
                                        <th>Status</th>
                                        <th>Updated</th>
                                        <th class="text-end pe-4" style="width: 140px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="pagesTableBody">
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <div class="spinner-border spinner-border-sm text-primary me-2"
                                                role="status"></div>
                                            Loading dynamic pages...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VIEW 2: MODULAR PAGE BUILDER (Hidden initially) -->
            <div id="pageEditorView" class="card border-0 shadow-sm d-none">
                <form id="pageBuilderForm">
                    <input type="hidden" id="pageId" name="id" value="0">

                    <!-- STICKY TOP HEADER & TABS (Fixed/sticky on scroll) -->
                    <div class="sticky-editor-header bg-white border-bottom shadow-sm">
                        <div
                            class="py-3 px-4 d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <button type="button" class="btn btn-outline-secondary btn-sm px-3 shadow-none"
                                    id="btnBackToList">
                                    <i class="fa fa-arrow-left me-1"></i> Back to List
                                </button>
                                <h5 class="mb-0 fw-bold text-dark text-truncate" style="max-width: 480px;"
                                    id="editorTitle">Create Dynamic Landing Page</h5>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-outline-info btn-sm px-3 fw-semibold"
                                    id="btnLoadTemplate">
                                    <i class="fa fa-magic me-1"></i> Load Car Rental Template
                                </button>
                                <button type="button" class="btn btn-success px-4 fw-semibold shadow-sm"
                                    id="btnSavePage">
                                    <i class="fa fa-save me-1"></i> <span id="btnSaveText">Save Page</span>
                                </button>
                            </div>
                        </div>

                        <!-- TABS & QUICK CONTROLS -->
                        <div
                            class="px-4 py-2 bg-white d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <ul class="nav nav-pills" id="editorTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active fw-semibold" id="tab-general" data-bs-toggle="pill"
                                        data-bs-target="#pills-general" type="button" role="tab">
                                        <i class="fa fa-sliders me-1"></i> 1. Page Info & Settings
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-semibold" id="tab-seo" data-bs-toggle="pill"
                                        data-bs-target="#pills-seo" type="button" role="tab">
                                        <i class="fa fa-line-chart me-1"></i> 2. SEO & Meta Tags
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-semibold" id="tab-sections" data-bs-toggle="pill"
                                        data-bs-target="#pills-sections" type="button" role="tab">
                                        <i class="fa fa-cubes me-1"></i> 3. Modular Sections (<span
                                            id="sectionCountBadge">0</span>)
                                    </button>
                                </li>
                            </ul>

                            <div class="d-flex align-items-center gap-2" id="quickSectionControls">
                                <button type="button" class="btn btn-sm btn-light border px-2 text-secondary"
                                    onclick="toggleAllSections(false)"
                                    title="Collapse all sections for easy drag & drop">
                                    <i class="fa fa-compress me-1"></i> Collapse All
                                </button>
                                <button type="button" class="btn btn-sm btn-light border px-2 text-secondary"
                                    onclick="toggleAllSections(true)" title="Expand all sections">
                                    <i class="fa fa-expand me-1"></i> Expand All
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="tab-content" id="editorTabContent">
                            <!-- TAB 1: General Info -->
                            <div class="tab-pane fade show active" id="pills-general" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Page Title <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="pageTitle" name="page_title"
                                            placeholder="e.g. Heathrow Airport to Sutton Car Rental" required>
                                        <small class="text-muted">Descriptive main title of this landing route</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">URL Slug <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span
                                                class="input-group-text bg-light text-muted small fw-semibold">goride.run/uk/</span>
                                            <input type="text" class="form-control" id="pageSlug" name="slug"
                                                placeholder="e.g. car-rental or heathrow-to-sutton" required>
                                        </div>
                                        <small class="text-muted">Full URL:
                                            <code>goride.run/uk/<span id="slugHint">car-rental</span></code></small>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Page Category / Type</label>
                                        <select class="form-select" id="pageType" name="page_type">
                                            <option value="car-rental">Car Rental / Transfers</option>
                                            <option value="general">General Landing Page</option>
                                            <option value="tour">Tour & Outstation</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Publish Status</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" id="isPublished"
                                                name="is_published" value="1" checked>
                                            <label class="form-check-label ms-2" for="isPublished">Active & Visible to
                                                Public</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">XML Sitemap</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" id="isSitemap"
                                                name="is_sitemap" value="1" checked>
                                            <label class="form-check-label ms-2" for="isSitemap">Include in XML
                                                Sitemap</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 2: SEO Settings -->
                            <div class="tab-pane fade" id="pills-seo" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-bold">Meta Title</label>
                                        <input type="text" class="form-control" id="seoTitle" name="seo_title"
                                            placeholder="e.g. Heathrow Airport to Sutton Car Rental | GoRide UK">
                                        <small class="text-muted">Recommended length: 50-60 characters</small>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold">Meta Description</label>
                                        <textarea class="form-control" id="metaDescription" name="meta_description"
                                            rows="3"
                                            placeholder="Brief summary for Google search snippets..."></textarea>
                                        <small class="text-muted">Recommended length: 140-160 characters</small>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold">Meta Keywords</label>
                                        <input type="text" class="form-control" id="metaKeywords" name="meta_keywords"
                                            placeholder="e.g. heathrow car rental, taxi sutton, airport transfer london">
                                        <small class="text-muted">Comma separated keywords</small>
                                    </div>

                                    <div class="col-12">
                                        <div
                                            class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                            <label class="form-label fw-bold mb-0 text-dark">
                                                <i class="fa fa-code text-primary me-1"></i> Schema Markup / Structured
                                                Data Scripts (JSON-LD)
                                            </label>
                                            <div class="d-flex align-items-center gap-2">
                                                <button type="button"
                                                    class="btn btn-sm btn-info fw-semibold px-3 py-1 text-white shadow-sm d-inline-flex align-items-center btn-schema-action btn-schema-format"
                                                    id="btnBeautifySchema" title="Format & Beautify JSON-LD scripts">
                                                    <i class="fa-solid fa-wand-magic-sparkles fa fa-magic me-1"></i> Format / Beautify
                                                </button>
                                                <button type="button"
                                                    class="btn btn-sm btn-primary fw-semibold px-3 py-1 text-white shadow-sm d-inline-flex align-items-center btn-schema-action btn-schema-validate"
                                                    id="btnValidateSchemaManual" title="Validate all schema scripts">
                                                    <i class="fa-solid fa-check fa fa-check me-1"></i> Check & Validate
                                                </button>
                                            </div>
                                        </div>
                                        <textarea class="form-control font-monospace" id="schemaMarkup"
                                            name="schema_markup" rows="8"
                                            placeholder="Paste one or more &lt;script type=&quot;application/ld+json&quot;&gt;...&lt;/script&gt; tags or raw JSON schema here..."></textarea>
                                        <div class="d-flex justify-content-between align-items-start mt-1">
                                            <small class="text-muted" style="font-size: 12px;">
                                                <strong>Summary Note:</strong> Paste the schema script(s) for this page.
                                                You can input multiple
                                                <code>&lt;script type="application/ld+json"&gt;...&lt;/script&gt;</code>
                                                blocks or direct JSON schemas (e.g. <code>LocalBusiness</code>,
                                                <code>TaxiService</code>, <code>FAQPage</code>,
                                                <code>BreadcrumbList</code>, <code>Product</code>). The system
                                                automatically validates each script block in real time.
                                            </small>
                                        </div>
                                        <!-- Real-time Schema Validation Result Box -->
                                        <div id="schemaValidationStatus" class="mt-2" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 3: Modular Sections Builder -->
                            <div class="tab-pane fade" id="pills-sections" role="tabpanel">
                                <div
                                    class="d-flex align-items-center justify-content-between p-3 bg-light  mb-4 border">
                                    <div>
                                        <h6 class="fw-bold mb-1"><i class="fa fa-cubes text-primary me-2"></i>Page
                                            Section Blocks</h6>
                                        <p class="text-muted small mb-0">Add, reorder, customize or remove any content
                                            block dynamically.</p>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle shadow-sm" type="button"
                                            id="addSectionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-plus me-1"></i> Add Section Block
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0"
                                            aria-labelledby="addSectionDropdown">
                                            <li><a class="dropdown-item py-2 add-section-item" href="javascript:void(0)"
                                                    data-type="hero"><i class="fa fa-header text-primary me-2"></i> Hero
                                                    Banner & Header</a></li>
                                            <li><a class="dropdown-item py-2 add-section-item" href="javascript:void(0)"
                                                    data-type="overview"><i class="fa fa-list text-success me-2"></i>
                                                    Route Overview & Key Highlights</a></li>
                                            <li><a class="dropdown-item py-2 add-section-item" href="javascript:void(0)"
                                                    data-type="fleet_pricing"><i
                                                        class="fa fa-car text-warning me-2"></i> Fleet Cards &
                                                    Pricing</a></li>
                                            <li><a class="dropdown-item py-2 add-section-item" href="javascript:void(0)"
                                                    data-type="places_showcase"><i
                                                        class="fa fa-map-marker text-success me-2"></i> Popular Places &
                                                    Areas to Visit</a></li>
                                            <li><a class="dropdown-item py-2 add-section-item" href="javascript:void(0)"
                                                    data-type="why_choose"><i class="fa fa-trophy text-info me-2"></i>
                                                    Why Choose Us / Value Props</a></li>
                                            <li><a class="dropdown-item py-2 add-section-item" href="javascript:void(0)"
                                                    data-type="booking_steps"><i
                                                        class="fa fa-clock-o text-secondary me-2"></i> How to Book
                                                    (Step-by-Step)</a></li>
                                            <li><a class="dropdown-item py-2 add-section-item" href="javascript:void(0)"
                                                    data-type="faqs"><i
                                                        class="fa fa-question-circle text-danger me-2"></i> Frequently
                                                    Asked Questions (FAQ)</a></li>
                                            <li><a class="dropdown-item py-2 add-section-item" href="javascript:void(0)"
                                                    data-type="cta"><i class="fa fa-bullhorn text-purple me-2"></i> Call
                                                    to Action (CTA) Banner</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item py-2 add-section-item" href="javascript:void(0)"
                                                    data-type="custom_content"><i class="fa fa-code text-dark me-2"></i>
                                                    Custom HTML / Rich Content</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Container for dynamic section blocks -->
                                <div id="sectionsContainer" class="d-flex flex-column gap-3">
                                    <!-- Section cards rendered here by JS -->
                                </div>

                                <div id="noSectionsNotice" class="text-center py-5 border border-dashed  bg-light">
                                    <i class="fa fa-cubes text-muted fa-3x mb-3"></i>
                                    <h6 class="fw-bold text-dark">No Section Blocks Added Yet</h6>
                                    <p class="text-muted small">Click "Add Section Block" or "Load Car Rental Template"
                                        to build your landing page layout.</p>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    // ==========================================
    // DYNAMIC LANDING PAGES BUILDER SCRIPT
    // ==========================================
    let allPages = [];
    let currentSections = [];

    $(document).ready(function () {
        loadPagesList();

        // Sticky Header pinned effect & offset sync on scroll
        function syncStickyEditorTop() {
            let $appHeader = $('.app-header:visible, .header.sticky:visible, .header:visible');
            let offset = 0;
            if ($appHeader.length) {
                let pos = $appHeader.css('position');
                if (pos === 'fixed' || pos === 'sticky') {
                    offset = $appHeader.outerHeight() || 74;
                }
            }
            $('.sticky-editor-header').css('top', offset + 'px');
        }

        $(window).on('scroll resize', function () {
            syncStickyEditorTop();
            let header = $('.sticky-editor-header');
            if (header.length) {
                if ($(window).scrollTop() > 70) {
                    header.addClass('is-pinned');
                } else {
                    header.removeClass('is-pinned');
                }
            }
        });
        syncStickyEditorTop();


        // Event handlers
        $('#btnCreateNewPage').on('click', function () {
            openPageEditor();
        });

        $('#btnBackToList').on('click', function () {
            $('#pageEditorView').addClass('d-none');
            $('#pagesListView').removeClass('d-none');
        });

        $('#btnRefreshList').on('click', function () {
            loadPagesList();
        });

        // Auto-generate slug from page title if slug is empty
        $('#pageTitle').on('keyup change', function () {
            if ($('#pageId').val() == '0' || $('#pageSlug').val() === '') {
                let slug = $(this).val().toLowerCase()
                    .replace(/[^a-z0-9 -]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
                $('#pageSlug').val(slug);
                $('#slugHint').text(slug || 'car-rental');
            }
        });

        $('#pageSlug').on('keyup change', function () {
            $('#slugHint').text($(this).val().trim() || 'car-rental');
        });

        // Filter and search
        $('#searchPageInput, #filterPageType').on('input change', function () {
            renderFilteredPages();
        });

        // Add Section dropdown
        $(document).on('click', '.add-section-item', function (e) {
            e.preventDefault();
            let sectionType = $(this).data('type');
            addSectionBlock(sectionType);
        });

        // Load Car Rental Template
        $('#btnLoadTemplate').on('click', function () {
            Swal.fire({
                title: 'Load Car Rental Template?',
                text: 'This will populate standard Heathrow to Sutton sections. You can edit them freely!',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                confirmButtonText: 'Yes, load template'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadCarRentalDefaultTemplate();
                }
            });
        });

        // Save Page button
        $('#btnSavePage').on('click', function () {
            saveCurrentPage();
        });

        // Real-time Schema Markup Validation (with debounce)
        let schemaValidationTimer = null;
        $('#schemaMarkup').on('input paste change', function () {
            clearTimeout(schemaValidationTimer);
            schemaValidationTimer = setTimeout(function () {
                renderSchemaValidationFeedback();
            }, 250);
        });

        // Beautify / Format Schema button
        $('#btnBeautifySchema').on('click', function () {
            beautifySchemaMarkup();
        });

        // Manual Validate button
        $('#btnValidateSchemaManual').on('click', function () {
            let res = renderSchemaValidationFeedback(true);
            if (res.isValid && !res.isEmpty) {
                let typesInfo = res.types.length ? '<br>Detected types: <span class="badge bg-success">' + res.types.join('</span> <span class="badge bg-success">') + '</span>' : '';
                let warnsInfo = res.warnings.length ? '<br><br><small class="text-warning text-start d-block"><strong>Notes:</strong><br>' + res.warnings.map(w => '• ' + escapeHtml(w)).join('<br>') + '</small>' : '';
                Swal.fire({
                    icon: 'success',
                    title: 'Schema is Valid!',
                    html: 'Successfully validated <strong>' + res.blocksCount + '</strong> schema block(s).' + typesInfo + warnsInfo,
                    timer: 2500,
                    showConfirmButton: false
                });
            } else if (res.isEmpty) {
                Swal.fire('Info', 'Schema field is currently empty. You can paste your schema script(s) anytime.', 'info');
            }
        });
    });

    // Load pages from AJAX
    function loadPagesList() {
        $('#pagesTableBody').html(`
        <tr>
            <td colspan="7" class="text-center py-5 text-muted">
                <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                Loading dynamic pages...
            </td>
        </tr>
    `);

        $.ajax({
            url: 'ajax/service/dynamicPages_services.php',
            type: 'POST',
            data: { action: 'list' },
            dataType: 'json',
            success: function (res) {
                if ((res.status === 'success' || res.status === true)) {
                    allPages = res.data || [];
                    renderFilteredPages();
                } else {
                    Swal.fire('Error', res.message || 'Failed to fetch pages', 'error');
                }
            },
            error: function (err) {
                console.error(err);
                $('#pagesTableBody').html(`
                <tr>
                    <td colspan="7" class="text-center py-4 text-danger">
                        <i class="fa fa-exclamation-triangle me-1"></i> Failed to connect to server.
                    </td>
                </tr>
            `);
            }
        });
    }

    function renderFilteredPages() {
        let search = ($('#searchPageInput').val() || '').toLowerCase();
        let typeFilter = $('#filterPageType').val();

        let filtered = allPages.filter(p => {
            let matchSearch = !search || (p.page_title && p.page_title.toLowerCase().includes(search)) || (p.slug && p.slug.toLowerCase().includes(search));
            let matchType = !typeFilter || p.page_type === typeFilter;
            return matchSearch && matchType;
        });

        if (filtered.length === 0) {
            $('#pagesTableBody').html(`
            <tr>
                <td colspan="7" class="text-center py-5 text-muted">
                    <i class="fa fa-folder-open fa-2x mb-2 d-block"></i>
                    No landing pages found.
                </td>
            </tr>
        `);
            return;
        }

        let rowsHtml = '';
        filtered.forEach(page => {
            let viewUrl = 'https://www.goride.run/uk/' + page.slug;

            rowsHtml += `
            <tr>
                <td class="ps-4 fw-bold text-muted">#${page.id}</td>
                <td>
                    <div class="fw-bold text-dark mb-1">${escapeHtml(page.page_title)}</div>
                    <div class="small">
                        <a href="${viewUrl}" target="_blank" class="text-primary text-decoration-none fw-semibold">
                            <i class="fa fa-external-link me-1"></i>/uk/${escapeHtml(page.slug)}
                        </a>
                    </div>
                </td>
                <td>
                    <span class="type-badge-pill">${escapeHtml(page.page_type || 'car-rental')}</span>
                </td>
                <td>
                    <span class="sec-badge-pill">
                        <i class="fa fa-cubes"></i>${page.section_count || 0} Sections
                    </span>
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="form-check form-switch m-0" style="min-height: auto;">
                            <input class="form-check-input toggle-status-switch"
                                   type="checkbox"
                                   role="switch"
                                   data-id="${page.id}"
                                   ${page.is_published == 1 ? 'checked' : ''}
                                   style="cursor: pointer; width: 36px; height: 18px;">
                        </div>
                        <span class="badge ${page.is_published == 1 ? 'bg-success' : 'bg-secondary'}" style="font-size: 11px; padding: 4px 7px; border-radius: 4px;">
                            ${page.is_published == 1 ? 'Live' : 'Draft'}
                        </span>
                    </div>
                </td>
                <td class="small text-muted">${page.updated_at ? page.updated_at.split(' ')[0] : '-'}</td>
                <td class="text-end pe-4">
                    <button class="btn btn-sm btn-light border text-primary me-1" onclick="editPage(${page.id})" title="Edit Page & Sections" style="padding: 4px 9px; border-radius: 6px;">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-light border text-danger" onclick="deletePage(${page.id}, '${escapeHtml(page.page_title)}')" title="Delete Page" style="padding: 4px 9px; border-radius: 6px;">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        });

        $('#pagesTableBody').html(rowsHtml);

        // Bind toggle switch
        $('.toggle-status-switch').on('change', function () {
            let pageId = $(this).data('id');
            let newStatus = $(this).is(':checked') ? 1 : 0;
            $.post('ajax/service/dynamicPages_services.php', { action: 'toggle_status', id: pageId, status: newStatus, is_published: newStatus }, function (res) {
                if ((!res.status || res.status === 'error' || res.status === false)) {
                    Swal.fire('Error', res.message || 'Failed to update status', 'error');
                    loadPagesList();
                } else {
                    loadPagesList();
                }
            }, 'json');
        });
    }

    // Open Editor in Create Mode
    function openPageEditor(data = null) {
        $('#pageBuilderForm')[0].reset();
        currentSections = [];

        if (data) {
            $('#editorTitle').text('Edit Landing Page: ' + data.page_title);
            $('#pageId').val(data.id);
            $('#pageTitle').val(data.page_title);
            $('#pageSlug').val(data.slug);
            $('#slugHint').text(data.slug || 'car-rental');
            $('#pageType').val(data.page_type || 'car-rental');
            $('#isPublished').prop('checked', data.is_published == 1);
            $('#isSitemap').prop('checked', data.is_sitemap == 1);
            $('#seoTitle').val(data.seo_title || '');
            $('#metaDescription').val(data.meta_description || '');
            $('#metaKeywords').val(data.meta_keywords || '');
            $('#schemaMarkup').val(data.schema_markup || '');

            if (Array.isArray(data.sections)) {
                currentSections = data.sections;
            } else if (typeof data.sections === 'string') {
                try { currentSections = JSON.parse(data.sections) || []; } catch (e) { currentSections = []; }
            }
            // Strip deprecated hardcoded unsplash car image from overview sections
            currentSections.forEach(s => {
                if (s && s.type === 'overview' && s.image && s.image.indexOf('photo-1549399542-7e3f8b79c341') !== -1) {
                    s.image = '';
                }
            });
        } else {
            $('#editorTitle').text('Create Dynamic Landing Page');
            $('#pageId').val('0');
            $('#pageSlug').val('');
            $('#slugHint').text('car-rental');
            $('#schemaMarkup').val('');
            $('#isPublished').prop('checked', true);
            $('#isSitemap').prop('checked', true);
        }

        renderSchemaValidationFeedback();
        renderSectionsUI();

        // Switch view
        $('#pagesListView').addClass('d-none');
        $('#pageEditorView').removeClass('d-none');
        // Switch to first tab
        $('#tab-general').tab('show');
    }

    // Edit existing page
    function editPage(id) {
        Swal.fire({
            title: 'Loading Page...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.post('ajax/service/dynamicPages_services.php', { action: 'get', id: id }, function (res) {
            Swal.close();
            if ((res.status === 'success' || res.status === true) && res.data) {
                openPageEditor(res.data);
            } else {
                Swal.fire('Error', res.message || 'Could not fetch page details', 'error');
            }
        }, 'json').fail(function () {
            Swal.close();
            Swal.fire('Error', 'Server request failed', 'error');
        });
    }

    // Delete page
    function deletePage(id, title) {
        Swal.fire({
            title: 'Delete Landing Page?',
            text: 'Are you sure you want to delete "' + title + '"? This cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Yes, Delete It'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('ajax/service/dynamicPages_services.php', { action: 'delete', id: id }, function (res) {
                    if ((res.status === 'success' || res.status === true)) {
                        Swal.fire('Deleted!', 'The page has been removed.', 'success');
                        loadPagesList();
                    } else {
                        Swal.fire('Error', res.message || 'Failed to delete page', 'error');
                    }
                }, 'json');
            }
        });
    }

    // -------------------------------------------------------------
    // SECTION BUILDER LOGIC
    // -------------------------------------------------------------
    let collapsedSections = {};
    let sectionsSortableInstance = null;

    function renderSectionsUI() {
        let container = $('#sectionsContainer');
        container.empty();

        $('#sectionCountBadge').text(currentSections.length);

        if (currentSections.length === 0) {
            $('#noSectionsNotice').removeClass('d-none');
            return;
        } else {
            $('#noSectionsNotice').addClass('d-none');
        }

        currentSections.forEach((sec, idx) => {
            let cardHtml = buildSectionCardHtml(sec, idx);
            container.append(cardHtml);
        });

        // Initialize drag and drop
        initSectionsSortable();
        initNestedSortables();
    }

    function initSectionsSortable() {
        let container = document.getElementById('sectionsContainer');
        if (!container || typeof Sortable === 'undefined') return;

        if (sectionsSortableInstance) {
            try { sectionsSortableInstance.destroy(); } catch (e) { }
        }

        sectionsSortableInstance = new Sortable(container, {
            animation: 200,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onEnd: function (evt) {
                let oldIdx = evt.oldIndex;
                let newIdx = evt.newIndex;
                if (oldIdx !== undefined && newIdx !== undefined && oldIdx !== newIdx) {
                    let moved = currentSections.splice(oldIdx, 1)[0];
                    currentSections.splice(newIdx, 0, moved);

                    // Cleanly re-render indices
                    renderSectionsUI();
                }
            }
        });
    }

    function initNestedSortables() {
        if (typeof Sortable === 'undefined') return;
        document.querySelectorAll('[id^="nested-places-"]').forEach(container => {
            let secIdx = parseInt(container.id.replace('nested-places-', ''));
            new Sortable(container, {
                animation: 150,
                handle: '.nested-drag-handle',
                ghostClass: 'sortable-ghost',
                onEnd: function (evt) {
                    if (evt.oldIndex !== evt.newIndex && currentSections[secIdx] && currentSections[secIdx].places) {
                        let moved = currentSections[secIdx].places.splice(evt.oldIndex, 1)[0];
                        currentSections[secIdx].places.splice(evt.newIndex, 0, moved);
                        renderSectionsUI();
                    }
                }
            });
        });
    }

    function toggleSecCollapse(idx) {
        let sec = currentSections[idx];
        if (!sec) return;
        let uid = getSectionUid(sec);
        collapsedSections[uid] = !collapsedSections[uid];
        let body = $(`#sec_body_${idx}`);
        let icon = $(`#btn_collapse_${idx} i`);
        if (collapsedSections[uid]) {
            body.slideUp(180);
            icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
        } else {
            body.slideDown(180);
            icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
        }
    }

    function toggleAllSections(expand) {
        currentSections.forEach(s => {
            let uid = getSectionUid(s);
            collapsedSections[uid] = !expand;
        });
        renderSectionsUI();
    }

    function getSectionUid(sec) {
        if (!sec._uid) {
            sec._uid = 'sec_' + Math.random().toString(36).substr(2, 9);
        }
        return sec._uid;
    }

    function buildSectionCardHtml(sec, idx) {
        let typeTitles = {
            hero: 'Hero Banner & Header',
            overview: 'Route Overview & Highlights',
            fleet_pricing: 'Fleet Cards & Pricing',
            places_showcase: 'Popular Places & Areas to Visit',
            why_choose: 'Why Choose Us / Value Proposition',
            booking_steps: 'How to Book (Step-by-Step)',
            faqs: 'Frequently Asked Questions (FAQ)',
            cta: 'Call to Action (CTA) Banner',
            custom_content: 'Custom HTML / Rich Text'
        };

        let typeIcons = {
            hero: 'fa fa-header',
            overview: 'fa fa-list',
            fleet_pricing: 'fa fa-car',
            places_showcase: 'fa fa-map-marker',
            why_choose: 'fa fa-trophy',
            booking_steps: 'fa fa-clock-o',
            faqs: 'fa fa-question-circle',
            cta: 'fa fa-bullhorn',
            custom_content: 'fa fa-code'
        };

        let titleLabel = typeTitles[sec.type] || ('Section: ' + sec.type);
        let iconClass = typeIcons[sec.type] || 'fa fa-cubes';
        let uid = getSectionUid(sec);
        let isCollapsed = !!collapsedSections[uid];
        let bodyFieldsHtml = buildSectionBodyFields(sec, idx);

        return `
        <div class="card shadow-sm border section-block-card type-${sec.type} mb-3" data-idx="${idx}" data-uid="${uid}">
            <div class="card-header bg-white py-2 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="drag-handle px-2 py-1 text-muted" title="Drag and drop to reorder section">
                        <i class="fa fa-bars fa-lg"></i>
                    </span>
                    <span class="badge bg-dark sec-num-badge">${idx + 1}</span>
                    <span class="section-badge-pill sec-badge-${sec.type}">
                        <i class="${iconClass}"></i> ${titleLabel}
                    </span>
                    ${sec.title ? '<span class="text-dark small fw-semibold ms-1">| ' + escapeHtml(sec.title) + '</span>' : ''}
                </div>
                <div class="d-flex align-items-center gap-1">
                    <button type="button" class="btn btn-sm btn-light border px-2 text-primary" onclick="moveSection(${idx}, -1)" ${idx === 0 ? 'disabled' : ''} title="Move Up">
                        <i class="fa fa-arrow-up"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-light border px-2 text-primary" onclick="moveSection(${idx}, 1)" ${idx === currentSections.length - 1 ? 'disabled' : ''} title="Move Down">
                        <i class="fa fa-arrow-down"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-light border px-2 text-secondary" onclick="toggleSecCollapse(${idx})" title="${isCollapsed ? 'Expand' : 'Collapse'}" id="btn_collapse_${idx}">
                        <i class="fa fa-chevron-${isCollapsed ? 'down' : 'up'}"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-light border px-2 text-danger" onclick="removeSectionBlock(${idx})" title="Remove Section">
                        <i class="fa fa-trash text-danger"></i>
                    </button>
                </div>
            </div>
            <div class="card-body bg-light bg-opacity-25 p-3" id="sec_body_${idx}" style="${isCollapsed ? 'display: none;' : ''}">
                ${bodyFieldsHtml}
            </div>
        </div>
    `;
    }

    function buildSectionBodyFields(sec, idx) {
        let html = '';

        if (sec.type === 'hero') {
            html = `
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Hero Pill / Badge Text</label>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(sec.badge || '')}" onchange="updateSecField(${idx}, 'badge', this.value)" placeholder="e.g. Fixed Price Guarantee">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Hero Heading / Title</label>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(sec.title || '')}" onchange="updateSecField(${idx}, 'title', this.value)" placeholder="e.g. Heathrow Airport to Sutton Car Rental">
                </div>
                <div class="col-md-12">
                    <label class="form-label small fw-bold">Hero Subtitle / Description</label>
                    <textarea class="form-control form-control-sm" rows="2" onchange="updateSecField(${idx}, 'subtitle', this.value)">${escapeHtml(sec.subtitle || '')}</textarea>
                </div>
                <div class="col-md-12">
                    <label class="form-label small fw-bold text-dark">Hero Background / Featured Image</label>
                    <div class="p-2 border bg-white shadow-sm">
                        <div class="d-flex align-items-center gap-3">
                            <div id="preview_hero_${idx}" class="border bg-light d-flex align-items-center justify-content-center position-relative" style="width: 100px; height: 75px; flex-shrink: 0; overflow: hidden;">
                                ${sec.image ? `
                                    <img src="${escapeHtml(sec.image)}" alt="Hero Preview" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="previewImageModal('${escapeHtml(sec.image)}')">
                                    <button type="button" class="btn btn-danger btn-xs position-absolute top-0 end-0 p-0 d-flex align-items-center justify-content-center shadow" style="width: 20px; height: 20px; font-size: 10px; border-radius: 50%; margin: 3px;" onclick="clearHeroImage(${idx})" title="Remove image"><i class="fa fa-times"></i></button>
                                ` : `
                                    <div class="text-center text-muted" style="font-size: 11px;">
                                        <i class="fa fa-image fa-2x d-block mb-1 text-secondary"></i>No image
                                    </div>
                                `}
                            </div>
                            <div class="flex-grow-1">
                                <div class="input-group input-group-sm mb-1">
                                    <input type="text" class="form-control" id="sec_img_${idx}" placeholder="Image URL (e.g. https://...)" value="${escapeHtml(sec.image || '')}" oninput="updateHeroImagePreview(${idx}, this.value)">
                                    <button class="btn btn-primary px-3 fw-semibold" type="button" onclick="triggerImgUpload(${idx})"><i class="fa fa-upload me-1"></i> Upload S3</button>
                                    <button class="btn btn-outline-secondary px-3" type="button" onclick="previewImageModal($('#sec_img_' + ${idx}).val())" title="Preview full image"><i class="fa fa-eye me-1"></i> Preview</button>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted" style="font-size: 11px;">Upload directly to S3 or paste any direct image URL.</small>
                                    ${sec.image ? `<small><a href="${escapeHtml(sec.image)}" target="_blank" class="text-primary text-decoration-none" style="font-size: 11px;"><i class="fa fa-external-link me-1"></i>Open original</a></small>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Stat Badge (e.g. 45-60 Mins)</label>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(sec.stat_badge || '')}" onchange="updateSecField(${idx}, 'stat_badge', this.value)" placeholder="45 - 60 Mins Travel Time">
                </div>
            </div>
        `;
        } else if (sec.type === 'overview') {
            if (sec.image && sec.image.indexOf('photo-1549399542-7e3f8b79c341') !== -1) {
                sec.image = '';
            }
            html = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Overview Section Title</label>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(sec.title || '')}" onchange="updateSecField(${idx}, 'title', this.value)">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Overview Section Subtitle</label>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(sec.subtitle || '')}" onchange="updateSecField(${idx}, 'subtitle', this.value)">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold">Main Description Paragraph</label>
                    <textarea class="form-control form-control-sm" rows="3" onchange="updateSecField(${idx}, 'description', this.value)">${escapeHtml(sec.description || '')}</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label small fw-bold text-dark">Route Overview Featured Image (Displays on right side)</label>
                    <div class="p-2 border bg-white shadow-sm rounded-2">
                        <div class="d-flex align-items-center gap-3">
                            <div id="preview_overview_${idx}" class="border bg-light d-flex align-items-center justify-content-center position-relative shadow-sm" style="width: 100px; height: 75px; flex-shrink: 0; overflow: hidden; border-radius: 6px;">
                                ${sec.image ? `
                                    <img src="${escapeHtml(sec.image)}" alt="Overview Preview" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="previewImageModal('${escapeHtml(sec.image)}')">
                                    <button type="button" class="btn btn-danger btn-xs position-absolute top-0 end-0 p-0 d-flex align-items-center justify-content-center shadow" style="width: 18px; height: 18px; font-size: 10px; border-radius: 50%; margin: 2px;" onclick="clearOverviewImage(${idx})" title="Clear image"><i class="fa fa-times"></i></button>
                                ` : `
                                    <div class="text-center text-muted" style="font-size: 10px;">
                                        <i class="fa fa-image fa-lg d-block mb-1 text-secondary"></i>No image
                                    </div>
                                `}
                            </div>
                            <div class="flex-grow-1">
                                <div class="input-group input-group-sm mb-1">
                                    <input type="text" class="form-control" id="overview_img_${idx}" placeholder="Image URL (https://...)" value="${escapeHtml(sec.image || '')}" oninput="updateOverviewImagePreview(${idx}, this.value)">
                                    <button class="btn btn-primary" type="button" onclick="triggerImgUpload(${idx})" title="Upload image to S3">
                                        <i class="fa fa-upload me-1"></i> Upload
                                    </button>
                                    <button class="btn btn-outline-secondary" type="button" onclick="previewImageModal($('#overview_img_' + ${idx}).val())" title="Preview image">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted" style="font-size: 10px;">Upload image or paste direct image URL to display on the right side of the route overview card</small>
                                    ${sec.image ? `<small><a href="${escapeHtml(sec.image)}" target="_blank" class="text-primary text-decoration-none" style="font-size: 10px;"><i class="fa fa-external-link me-1"></i>Open Full Image</a></small>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border p-2 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="small text-dark"><i class="fa fa-list me-1"></i> Key Route Highlight Items</strong>
                    <button type="button" class="btn btn-sm btn-primary px-2 py-1" onclick="addNestedItem(${idx}, 'items', {title:'', desc:'', icon:'fa-car'})"><i class="fa fa-plus me-1"></i> Add Item</button>
                </div>
                <div class="d-flex flex-column gap-2" id="nested_items_${idx}">
                    ${renderNestedOverviewItems(sec.items || [], idx)}
                </div>
            </div>
        `;
        } else if (sec.type === 'fleet_pricing') {
            html = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Fleet Section Title</label>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(sec.title || '')}" onchange="updateSecField(${idx}, 'title', this.value)">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Fleet Section Subtitle</label>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(sec.subtitle || '')}" onchange="updateSecField(${idx}, 'subtitle', this.value)">
                </div>
            </div>
            <div class="border p-2 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="small text-dark"><i class="fa fa-car me-1"></i> Vehicle Pricing Cards</strong>
                    <button type="button" class="btn btn-sm btn-primary px-2 py-1" onclick="addNestedItem(${idx}, 'vehicles', {name:'Standard Saloon', price:'£48', passengers:'4', luggage:'2', desc:'Ideal for solo travellers, couples, and small luggage.'})"><i class="fa fa-plus me-1"></i> Add Vehicle</button>
                </div>
                <div class="d-flex flex-column gap-2">
                    ${renderNestedVehicles(sec.vehicles || [], idx)}
                </div>
            </div>
        `;
        } else if (sec.type === 'places_showcase') {
            html = `
            <div class="row g-2 mb-3">
                <div class="col-md-12">
                    <label class="form-label small fw-bold">Section Heading / Title</label>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(sec.title || 'Popular Sutton Areas and Places to Visit')}" onfocus="if(!sec.title){ sec.title='Popular Sutton Areas and Places to Visit'; }" onchange="updateSecField(${idx}, 'title', this.value)" placeholder="e.g. Popular Sutton Areas and Places to Visit">
                </div>
                <div class="col-md-12">
                    <label class="form-label small fw-bold">Description / Overview Text</label>
                    <textarea class="form-control form-control-sm" rows="3" onchange="updateSecField(${idx}, 'subtitle', this.value)" placeholder="Sutton is part of South London and offers a mixture of parks, heritage locations, shopping areas and leisure facilities...">${escapeHtml(sec.subtitle || sec.description || '')}</textarea>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-2">
                <label class="form-label small fw-bold mb-0">Place / Area Cards (${(sec.places || []).length})</label>
                <button type="button" class="btn btn-sm btn-outline-success" onclick="addNestedItem(${idx}, 'places', { title: 'New Area', desc: '', image: '' })">
                    <i class="fa fa-plus me-1"></i> Add Place Card
                </button>
            </div>
            <div class="d-flex flex-column gap-2" id="nested-places-${idx}">
                ${renderNestedPlaces(sec.places || [], idx)}
            </div>
        `;
        } else if (sec.type === 'why_choose') {
            html = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Why Choose Us Title</label>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(sec.title || '')}" onchange="updateSecField(${idx}, 'title', this.value)">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Why Choose Us Subtitle</label>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(sec.subtitle || '')}" onchange="updateSecField(${idx}, 'subtitle', this.value)">
                </div>
            </div>
            <div class="border p-2 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="small text-dark"><i class="fa fa-trophy me-1"></i> Feature Benefits</strong>
                    <button type="button" class="btn btn-sm btn-primary px-2 py-1" onclick="addNestedItem(${idx}, 'features', {title:'', desc:'', icon:'fa-shield'})"><i class="fa fa-plus me-1"></i> Add Benefit</button>
                </div>
                <div class="d-flex flex-column gap-2">
                    ${renderNestedWhyChoose(sec.features || [], idx)}
                </div>
            </div>
        `;
        } else if (sec.type === 'booking_steps') {
            html = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Steps Section Title</label>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(sec.title || '')}" onchange="updateSecField(${idx}, 'title', this.value)">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Steps Section Subtitle</label>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(sec.subtitle || '')}" onchange="updateSecField(${idx}, 'subtitle', this.value)">
                </div>
            </div>
            <div class="border p-2 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="small text-dark"><i class="fa fa-clock-o me-1"></i> Booking Step Flow</strong>
                    <button type="button" class="btn btn-sm btn-primary px-2 py-1" onclick="addNestedItem(${idx}, 'steps', {step:'1', title:'', desc:''})"><i class="fa fa-plus me-1"></i> Add Step</button>
                </div>
                <div class="d-flex flex-column gap-2">
                    ${renderNestedSteps(sec.steps || [], idx)}
                </div>
            </div>
        `;
        } else if (sec.type === 'faqs') {
            html = `
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">FAQ Section Title</label>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(sec.title || '')}" onchange="updateSecField(${idx}, 'title', this.value)">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">FAQ Section Subtitle</label>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(sec.subtitle || '')}" onchange="updateSecField(${idx}, 'subtitle', this.value)">
                </div>
            </div>
            <div class="border p-2 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="small text-dark"><i class="fa fa-question-circle me-1"></i> Questions & Answers</strong>
                    <button type="button" class="btn btn-sm btn-primary px-2 py-1" onclick="addNestedItem(${idx}, 'faqs', {q:'', a:''})"><i class="fa fa-plus me-1"></i> Add Question</button>
                </div>
                <div class="d-flex flex-column gap-2">
                    ${renderNestedFaqs(sec.faqs || [], idx)}
                </div>
            </div>
        `;
        } else if (sec.type === 'cta') {
            html = `
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">CTA Title</label>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(sec.title || '')}" onchange="updateSecField(${idx}, 'title', this.value)">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">CTA Subtitle</label>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(sec.subtitle || '')}" onchange="updateSecField(${idx}, 'subtitle', this.value)">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Button Text</label>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(sec.btn_text || 'Book Now')}" onchange="updateSecField(${idx}, 'btn_text', this.value)">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Button URL</label>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(sec.btn_url || '/#booking')}" onchange="updateSecField(${idx}, 'btn_url', this.value)">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Phone Number</label>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(sec.phone || '+44 20 8337 3777')}" onchange="updateSecField(${idx}, 'phone', this.value)">
                </div>
            </div>
        `;
        } else if (sec.type === 'custom_content') {
            html = `
            <div class="row g-2">
                <div class="col-md-12">
                    <label class="form-label small fw-bold">Custom Block Title</label>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(sec.title || '')}" onchange="updateSecField(${idx}, 'title', this.value)">
                </div>
                <div class="col-md-12">
                    <label class="form-label small fw-bold">Custom HTML / Markdown Content</label>
                    <textarea class="form-control form-control-sm font-monospace" rows="6" onchange="updateSecField(${idx}, 'content', this.value)">${escapeHtml(sec.content || '')}</textarea>
                </div>
            </div>
        `;
        }

        return html;
    }

    // Sub-renders for nested items
    function renderNestedOverviewItems(items, secIdx) {
        if (!items || items.length === 0) return '<div class="text-muted small p-2">No highlight items added yet.</div>';
        return items.map((it, itIdx) => `
        <div class="d-flex align-items-center gap-2 border p-2 bg-light">
            <input type="text" class="form-control form-control-sm" style="width: 140px;" placeholder="Icon (fa-car)" value="${escapeHtml(it.icon || 'fa-car')}" onchange="updateNestedItemField(${secIdx}, 'items', ${itIdx}, 'icon', this.value)">
            <input type="text" class="form-control form-control-sm" style="width: 200px;" placeholder="Title" value="${escapeHtml(it.title || '')}" onchange="updateNestedItemField(${secIdx}, 'items', ${itIdx}, 'title', this.value)">
            <input type="text" class="form-control form-control-sm" placeholder="Description" value="${escapeHtml(it.desc || '')}" onchange="updateNestedItemField(${secIdx}, 'items', ${itIdx}, 'desc', this.value)">
            <button type="button" class="btn btn-sm btn-light border text-danger" onclick="removeNestedItem(${secIdx}, 'items', ${itIdx})"><i class="fa fa-times"></i></button>
        </div>
    `).join('');
    }

    const standardVehiclesList = [
        { name: 'Standard Saloon', passengers: '4', luggage: '2', desc: 'Ideal for solo travellers, couples, and small luggage.' },
        { name: 'Executive Chauffeur (Mercedes E-Class)', passengers: '4', luggage: '2', desc: 'Travel in refined luxury with leather seating and complimentary bottled water.' },
        { name: 'Estate', passengers: '4', luggage: '4', desc: 'Extra space for family holidays and bulky suitcases.' },
        { name: 'MPV', passengers: '4', luggage: '8', desc: 'Perfect for large group transfers, teams, and extra sports luggage.' },
        { name: 'MPV 6', passengers: '4', luggage: '2', desc: 'Ideal for small groups' },
        { name: 'MPV 6 Luxury', passengers: '6', luggage: '2', desc: 'Ideal for small groups' },
        { name: 'MPV 7', passengers: '7', luggage: '2', desc: 'Ideal for small groups' },
        { name: 'MPV 8', passengers: '8', luggage: '2', desc: 'Ideal for small groups' },
        { name: 'MPV 8 Luxury', passengers: '8', luggage: '2', desc: 'Ideal for small groups' }
    ];

    function renderNestedVehicles(vehicles, secIdx) {
        if (!vehicles || vehicles.length === 0) return '<div class="text-muted small p-2">No vehicle pricing cards added yet.</div>';
        return vehicles.map((v, itIdx) => {
            const currentName = (v.name || '').trim();
            let hasMatch = false;
            let optionsHtml = standardVehiclesList.map(item => {
                const isSelected = (currentName.toLowerCase() === item.name.toLowerCase());
                if (isSelected) hasMatch = true;
                return `<option value="${escapeHtml(item.name)}" ${isSelected ? 'selected' : ''}>${escapeHtml(item.name)}</option>`;
            }).join('');

            if (currentName && !hasMatch) {
                optionsHtml = `<option value="${escapeHtml(currentName)}" selected>${escapeHtml(currentName)}</option>` + optionsHtml;
            }

            return `
        <div class="row g-2 align-items-center border p-2 bg-light mb-1 vehicle-row">
            <div class="col-md-3">
                <select class="form-select form-select-sm fw-semibold" onchange="onVehicleSelectChange(${secIdx}, ${itIdx}, this)">
                    <option value="" disabled ${!currentName ? 'selected' : ''}>-- Select Cab Type --</option>
                    ${optionsHtml}
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control form-control-sm" placeholder="Price (£48)" value="${escapeHtml(v.price || '')}" onchange="updateNestedItemField(${secIdx}, 'vehicles', ${itIdx}, 'price', this.value)">
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control form-control-sm vehicle-passengers-input" placeholder="Passengers" value="${escapeHtml(v.passengers || '4')}" onchange="updateNestedItemField(${secIdx}, 'vehicles', ${itIdx}, 'passengers', this.value)">
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control form-control-sm vehicle-desc-input" placeholder="Description" value="${escapeHtml(v.desc || '')}" onchange="updateNestedItemField(${secIdx}, 'vehicles', ${itIdx}, 'desc', this.value)">
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-sm btn-light border text-danger" onclick="removeNestedItem(${secIdx}, 'vehicles', ${itIdx})" title="Delete Vehicle"><i class="fa fa-trash"></i></button>
            </div>
        </div>
            `;
        }).join('');
    }

    function onVehicleSelectChange(secIdx, itIdx, selectElem) {
        const selectedVal = selectElem.value;
        updateNestedItemField(secIdx, 'vehicles', itIdx, 'name', selectedVal);

        const preset = standardVehiclesList.find(p => p.name.toLowerCase() === selectedVal.toLowerCase());
        if (preset && currentSections[secIdx] && currentSections[secIdx].vehicles && currentSections[secIdx].vehicles[itIdx]) {
            const v = currentSections[secIdx].vehicles[itIdx];
            if (!v.passengers || v.passengers === '4' || v.passengers === '') {
                v.passengers = preset.passengers;
            }
            if (!v.luggage || v.luggage === '2' || v.luggage === '') {
                v.luggage = preset.luggage;
            }
            if (!v.desc || v.desc.trim() === '' || v.desc === 'Ideal for small groups' || standardVehiclesList.some(p => p.desc === v.desc)) {
                v.desc = preset.desc;
            }

            const row = $(selectElem).closest('.vehicle-row');
            if (row.length) {
                row.find('.vehicle-passengers-input').val(v.passengers);
                row.find('.vehicle-desc-input').val(v.desc);
            }
        }
    }


    function renderNestedPlaces(places, secIdx) {
        if (!places || places.length === 0) return '<div class="text-muted small p-3 border bg-white text-center"><i class="fa fa-map-marker fa-2x text-muted mb-2 d-block"></i>No place cards added yet. Click "+ Add Place Card" above.</div>';
        return places.map((p, itIdx) => `
        <div class="border p-3 bg-white mb-3 shadow-sm rounded-3"><div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom"><div class="d-flex align-items-center gap-2"><span class="nested-drag-handle px-2 py-1 text-muted" title="Drag to reorder place" style="cursor: grab;"><i class="fa fa-bars text-secondary"></i></span><span class="badge bg-secondary">Place #${itIdx + 1}</span><strong class="text-dark small">${escapeHtml(p.title || "Untitled Place")}</strong></div></div>
            <div class="row g-2">
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-dark mb-1">Place Name / Heading</label>
                    <input type="text" class="form-control form-control-sm fw-bold" placeholder="e.g. Sutton Town Centre & High Street" value="${escapeHtml(p.title || '')}" onchange="updateNestedItemField(${secIdx}, 'places', ${itIdx}, 'title', this.value)">
                    
                    <label class="form-label small text-muted mt-2 mb-1">Description</label>
                    <textarea class="form-control form-control-sm" rows="3" placeholder="Brief summary of this location..." onchange="updateNestedItemField(${secIdx}, 'places', ${itIdx}, 'desc', this.value)">${escapeHtml(p.desc || p.description || '')}</textarea>
                </div>
                <div class="col-md-7">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label small fw-bold text-dark mb-0">Place Photo & Image URL</label>
                        <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" onclick="removeNestedItem(${secIdx}, 'places', ${itIdx})" title="Remove Place Card">
                            <i class="fa fa-trash me-1"></i> Remove Card
                        </button>
                    </div>
                    <div class="p-2 border bg-white shadow-sm">
                        <div class="d-flex align-items-center gap-3">
                            <div id="preview_place_${secIdx}_${itIdx}" class="border bg-light d-flex align-items-center justify-content-center position-relative shadow-sm" style="width: 90px; height: 75px; flex-shrink: 0; overflow: hidden;">
                                ${p.image ? `
                                    <img src="${escapeHtml(p.image)}" alt="Place Preview" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="previewImageModal('${escapeHtml(p.image)}')">
                                    <button type="button" class="btn btn-danger btn-xs position-absolute top-0 end-0 p-0 d-flex align-items-center justify-content-center shadow" style="width: 18px; height: 18px; font-size: 10px; border-radius: 50%; margin: 2px;" onclick="clearPlaceImage(${secIdx}, ${itIdx})" title="Clear image"><i class="fa fa-times"></i></button>
                                ` : `
                                    <div class="text-center text-muted" style="font-size: 10px;">
                                        <i class="fa fa-image fa-lg d-block mb-1 text-secondary"></i>No image
                                    </div>
                                `}
                            </div>
                            <div class="flex-grow-1">
                                <div class="input-group input-group-sm mb-1">
                                    <input type="text" class="form-control" id="place_img_${secIdx}_${itIdx}" placeholder="Image URL (https://...)" value="${escapeHtml(p.image || '')}" oninput="updatePlaceImagePreview(${secIdx}, ${itIdx}, this.value)">
                                    <button class="btn btn-primary" type="button" onclick="triggerNestedImgUpload(${secIdx}, 'places', ${itIdx}, 'image')" title="Upload photo to S3">
                                        <i class="fa fa-upload me-1"></i> Upload
                                    </button>
                                    <button class="btn btn-outline-secondary" type="button" onclick="previewImageModal($('#place_img_' + ${secIdx} + '_' + ${itIdx}).val())" title="Preview image">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted" style="font-size: 10px;">Click upload or paste image URL</small>
                                    ${p.image ? `<small><a href="${escapeHtml(p.image)}" target="_blank" class="text-primary text-decoration-none" style="font-size: 10px;"><i class="fa fa-external-link me-1"></i>Open</a></small>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
    }



    function renderNestedWhyChoose(features, secIdx) {
        if (!features || features.length === 0) return '<div class="text-muted small p-2">No benefits added yet.</div>';
        return features.map((f, itIdx) => `
        <div class="d-flex align-items-center gap-2 border p-2 bg-light">
            <input type="text" class="form-control form-control-sm" style="width: 140px;" placeholder="Icon (fa-check)" value="${escapeHtml(f.icon || 'fa-check')}" onchange="updateNestedItemField(${secIdx}, 'features', ${itIdx}, 'icon', this.value)">
            <input type="text" class="form-control form-control-sm" style="width: 200px;" placeholder="Benefit Title" value="${escapeHtml(f.title || '')}" onchange="updateNestedItemField(${secIdx}, 'features', ${itIdx}, 'title', this.value)">
            <input type="text" class="form-control form-control-sm" placeholder="Description" value="${escapeHtml(f.desc || '')}" onchange="updateNestedItemField(${secIdx}, 'features', ${itIdx}, 'desc', this.value)">
            <button type="button" class="btn btn-sm btn-light border text-danger" onclick="removeNestedItem(${secIdx}, 'features', ${itIdx})"><i class="fa fa-times"></i></button>
        </div>
    `).join('');
    }

    function renderNestedSteps(steps, secIdx) {
        if (!steps || steps.length === 0) return '<div class="text-muted small p-2">No steps added yet.</div>';
        return steps.map((s, itIdx) => `
        <div class="d-flex align-items-center gap-2 border p-2 bg-light">
            <input type="text" class="form-control form-control-sm" style="width: 80px;" placeholder="Step #" value="${escapeHtml(s.step || (itIdx + 1))}" onchange="updateNestedItemField(${secIdx}, 'steps', ${itIdx}, 'step', this.value)">
            <input type="text" class="form-control form-control-sm" style="width: 200px;" placeholder="Step Title" value="${escapeHtml(s.title || '')}" onchange="updateNestedItemField(${secIdx}, 'steps', ${itIdx}, 'title', this.value)">
            <input type="text" class="form-control form-control-sm" placeholder="Step Description" value="${escapeHtml(s.desc || '')}" onchange="updateNestedItemField(${secIdx}, 'steps', ${itIdx}, 'desc', this.value)">
            <button type="button" class="btn btn-sm btn-light border text-danger" onclick="removeNestedItem(${secIdx}, 'steps', ${itIdx})"><i class="fa fa-times"></i></button>
        </div>
    `).join('');
    }

    function renderNestedFaqs(faqs, secIdx) {
        if (!faqs || faqs.length === 0) return '<div class="text-muted small p-2">No FAQs added yet.</div>';
        return faqs.map((q, itIdx) => `
        <div class="border p-2 bg-light mb-1">
            <div class="d-flex align-items-center gap-2 mb-1">
                <input type="text" class="form-control form-control-sm fw-bold" placeholder="Question" value="${escapeHtml(q.q || '')}" onchange="updateNestedItemField(${secIdx}, 'faqs', ${itIdx}, 'q', this.value)">
                <button type="button" class="btn btn-sm btn-light border text-danger" onclick="removeNestedItem(${secIdx}, 'faqs', ${itIdx})"><i class="fa fa-times"></i></button>
            </div>
            <textarea class="form-control form-control-sm" rows="2" placeholder="Answer" onchange="updateNestedItemField(${secIdx}, 'faqs', ${itIdx}, 'a', this.value)">${escapeHtml(q.a || '')}</textarea>
        </div>
    `).join('');
    }

    // -------------------------------------------------------------
    // SECTION MANIPULATION HELPERS
    // -------------------------------------------------------------
    function addSectionBlock(type) {
        let newSec = { type: type, title: '' };

        if (type === 'hero') {
            newSec.title = 'Heathrow Airport to Sutton - Taxi, Transfer & Cab Booking';
            newSec.subtitle = 'Planning a journey from Heathrow Airport to Sutton? Whether arriving in London for business or continuing to South London, enjoy a stress-free private hire transfer with fixed prices and meet & greet service.';
            newSec.badge = 'Premium Transfer Service';
            newSec.stat_badge = '45-60 Mins Travel Time';
        } else if (type === 'overview') {
            newSec.title = 'Route Overview & Journey Details';
            newSec.subtitle = 'Everything you need to know about traveling from Heathrow Airport to Sutton';
            newSec.description = 'The journey spans roughly 22 miles via the M25 and A217. Enjoy stress-free airport pickup with live flight tracking.';
            newSec.image = '';
            newSec.items = [
                { icon: 'fa-road', title: 'Route Distance', desc: 'Approx. 22 miles via M25 & A217' },
                { icon: 'fa-clock-o', title: 'Estimated Time', desc: '45 - 60 minutes depending on traffic' },
                { icon: 'fa-plane', title: 'Flight Tracking', desc: 'Free waiting time if flight is delayed' }
            ];
        } else if (type === 'fleet_pricing') {
            newSec.title = 'Transparent Fleet Options & Fixed Pricing';
            newSec.subtitle = 'Select the vehicle tailored to your group size and luggage requirements with no hidden surcharges';
            newSec.vehicles = [
                { name: 'Standard Saloon', price: '£48', passengers: '4', luggage: '2', desc: 'Ideal for solo travellers, couples, and small luggage.' },
                { name: 'Executive Chauffeur (Mercedes E-Class)', price: '£65', passengers: '4', luggage: '2', desc: 'Travel in refined luxury with leather seating and complimentary bottled water.' },
                { name: 'Estate', price: '£55', passengers: '4', luggage: '4', desc: 'Extra space for family holidays and bulky suitcases.' },
                { name: 'MPV', price: '£58', passengers: '4', luggage: '8', desc: 'Perfect for large group transfers, teams, and extra sports luggage.' },
                { name: 'MPV 6', price: '£65', passengers: '4', luggage: '2', desc: 'Ideal for small groups' },
                { name: 'MPV 6 Luxury', price: '£75', passengers: '6', luggage: '2', desc: 'Ideal for small groups' },
                { name: 'MPV 7', price: '£75', passengers: '7', luggage: '2', desc: 'Ideal for small groups' },
                { name: 'MPV 8', price: '£85', passengers: '8', luggage: '2', desc: 'Ideal for small groups' },
                { name: 'MPV 8 Luxury', price: '£95', passengers: '8', luggage: '2', desc: 'Ideal for small groups' }
            ];
        } else if (type === 'why_choose') {
            newSec.title = 'Why Choose GoRide UK for Airport Transfers';
            newSec.subtitle = 'Professional, licensed, and dependable private hire transport across London';
            newSec.features = [
                { icon: 'fa-shield', title: 'Fully Licensed & Insured', desc: 'TfL licensed private hire operators' },
                { icon: 'fa-tag', title: 'No Hidden Fees', desc: 'All tolls and taxes included in fixed quote' },
                { icon: 'fa-user', title: 'Meet & Greet Service', desc: 'Driver waits at arrivals with a name board' },
                { icon: 'fa-phone', title: '24/7 Customer Support', desc: 'Dedicated dispatch team ready at any hour' }
            ];
        } else if (type === 'booking_steps') {
            newSec.title = 'How to Book in 3 Simple Steps';
            newSec.subtitle = 'Quick online reservation with instant confirmation';
            newSec.steps = [
                { step: '1', title: 'Enter Flight & Pickup Details', desc: 'Select Heathrow terminal and drop-off in Sutton' },
                { step: '2', title: 'Select Vehicle & Fixed Fare', desc: 'Choose Saloon, Executive, or Minibus' },
                { step: '3', title: 'Receive Confirmation', desc: 'Instant SMS & email confirmation with driver details' }
            ];
        } else if (type === 'faqs') {
            newSec.title = 'Frequently Asked Questions';
            newSec.subtitle = 'Got queries about Heathrow Airport to Sutton taxi transfers? Find answers here.';
            newSec.faqs = [
                { q: 'How long does a taxi transfer from Heathrow to Sutton take?', a: 'Under normal traffic conditions, the journey takes between 45 and 60 minutes via the M25 or through South West London.' },
                { q: 'What happens if my incoming flight is delayed?', a: 'We monitor all flights live. If your flight is delayed or arrives early, your pickup time will be automatically adjusted at no extra charge.' },
                { q: 'Where do I meet my driver at Heathrow Airport?', a: 'Your driver will be waiting in the designated arrivals hall by the information desk holding a name board with your name.' }
            ];
        } else if (type === 'cta') {
            newSec.title = 'Ready for a Stress-Free Airport Transfer?';
            newSec.subtitle = 'Book your Heathrow to Sutton ride in under 2 minutes with guaranteed fixed prices.';
            newSec.btn_text = 'Book Your Ride Now';
            newSec.btn_url = '/#booking';
            newSec.phone = '+44 20 8337 3777';
        } else if (type === 'custom_content') {
            newSec.title = 'Additional Information';
            newSec.content = '<p>Custom informative content goes here...</p>';
        }

        currentSections.push(newSec);
        renderSectionsUI();

        // Scroll to the new section
        $('html, body').animate({
            scrollTop: $('#sectionsContainer .section-block-card:last-child').offset().top - 100
        }, 300);
    }

    function updateSecField(idx, field, val) {
        if (currentSections[idx]) {
            currentSections[idx][field] = val;
        }
    }

    function updateNestedItemField(secIdx, listKey, itIdx, field, val) {
        if (currentSections[secIdx] && currentSections[secIdx][listKey] && currentSections[secIdx][listKey][itIdx]) {
            currentSections[secIdx][listKey][itIdx][field] = val;
        }
    }

    function addNestedItem(secIdx, listKey, defaultObj) {
        if (currentSections[secIdx]) {
            if (!currentSections[secIdx][listKey]) currentSections[secIdx][listKey] = [];
            currentSections[secIdx][listKey].push(defaultObj);
            renderSectionsUI();
        }
    }

    function removeNestedItem(secIdx, listKey, itIdx) {
        if (currentSections[secIdx] && currentSections[secIdx][listKey]) {
            currentSections[secIdx][listKey].splice(itIdx, 1);
            renderSectionsUI();
        }
    }

    function moveSection(idx, direction) {
        let targetIdx = idx + direction;
        if (targetIdx < 0 || targetIdx >= currentSections.length) return;
        let temp = currentSections[idx];
        currentSections[idx] = currentSections[targetIdx];
        currentSections[targetIdx] = temp;
        renderSectionsUI();
    }

    function removeSectionBlock(idx) {
        currentSections.splice(idx, 1);
        renderSectionsUI();
    }

    // Load default Heathrow to Sutton template
    function loadCarRentalDefaultTemplate() {
        if (!$('#pageTitle').val()) $('#pageTitle').val('Heathrow Airport to Sutton Car Rental');
        if (!$('#pageSlug').val()) $('#pageSlug').val('car-rental');
        $('#slugHint').text('car-rental');
        if (!$('#seoTitle').val()) $('#seoTitle').val('Heathrow Airport to Sutton Car Rental | GoRide UK Private Hire');
        if (!$('#metaDescription').val()) $('#metaDescription').val('Pre-book your private transfer or car rental from London Heathrow Airport to Sutton. Fixed fares, flight tracking, and 24/7 service.');
        if (!$('#metaKeywords').val()) $('#metaKeywords').val('heathrow car rental, heathrow to sutton taxi, london airport transfer, sutton car hire');

        currentSections = [];
        addSectionBlock('hero');
        addSectionBlock('overview');
        addSectionBlock('fleet_pricing');
        addSectionBlock('why_choose');
        addSectionBlock('booking_steps');
        addSectionBlock('faqs');
        addSectionBlock('places_showcase');
        addSectionBlock('cta');

        $('#tab-sections').tab('show');
    }

    // Save current page
    function saveCurrentPage() {
        let title = $('#pageTitle').val().trim();
        let slug = $('#pageSlug').val().trim();

        if (!title) {
            Swal.fire('Required Field', 'Please enter a Page Title.', 'warning');
            $('#tab-general').tab('show');
            $('#pageTitle').focus();
            return;
        }

        if (!slug) {
            Swal.fire('Required Field', 'Please enter a URL Slug.', 'warning');
            $('#tab-general').tab('show');
            $('#pageSlug').focus();
            return;
        }

        // Validate Schema Markup before saving
        let schemaValidation = validateSchemaMarkup($('#schemaMarkup').val());
        if (!schemaValidation.isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Schema Script',
                html: '<p class="mb-2 text-start">The Schema Markup in <strong>2. SEO & Meta Tags</strong> contains syntax errors:</p>' +
                    '<ul class="text-danger text-start small mb-3">' +
                    schemaValidation.errors.map(e => '<li>' + escapeHtml(e) + '</li>').join('') +
                    '</ul>' +
                    '<p class="small text-muted mb-0">Please fix the schema script before saving.</p>',
                confirmButtonColor: '#d33'
            });
            $('#tab-seo').tab('show');
            $('#schemaMarkup').focus();
            return;
        }

        let payload = {
            action: 'save',
            id: $('#pageId').val(),
            page_title: title,
            slug: slug,
            page_type: $('#pageType').val(),
            is_published: $('#isPublished').is(':checked') ? 1 : 0,
            is_sitemap: $('#isSitemap').is(':checked') ? 1 : 0,
            seo_title: $('#seoTitle').val().trim(),
            meta_description: $('#metaDescription').val().trim(),
            meta_keywords: $('#metaKeywords').val().trim(),
            schema_markup: $('#schemaMarkup').val().trim(),
            sections: JSON.stringify(currentSections.map(s => {
                let c = Object.assign({}, s);
                delete c._uid;
                if (c.type === 'overview' && c.image && c.image.indexOf('photo-1549399542-7e3f8b79c341') !== -1) {
                    c.image = '';
                }
                return c;
            }))
        };

        $('#btnSavePage').prop('disabled', true);
        $('#btnSaveText').text('Saving...');

        $.ajax({
            url: 'ajax/service/dynamicPages_services.php',
            type: 'POST',
            data: payload,
            dataType: 'json',
            success: function (res) {
                $('#btnSavePage').prop('disabled', false);
                $('#btnSaveText').text('Save Page');

                if ((res.status === 'success' || res.status === true)) {
                    Swal.fire({
                        title: 'Saved Successfully!',
                        text: 'Landing page has been saved.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // Return to table view & reload
                    $('#pageEditorView').addClass('d-none');
                    $('#pagesListView').removeClass('d-none');
                    loadPagesList();
                } else {
                    Swal.fire('Error', res.message || 'Failed to save page', 'error');
                }
            },
            error: function (err) {
                $('#btnSavePage').prop('disabled', false);
                $('#btnSaveText').text('Save Page');
                Swal.fire('Error', 'Server connection error during save.', 'error');
            }
        });
    }

    // Image upload trigger helper

    // Image upload helper for nested items (e.g. places cards)
    function triggerNestedImgUpload(secIdx, fieldArrayName, itemIdx, fieldName) {
        let input = $('<input type="file" accept="image/*" style="display:none">');
        $('body').append(input);
        input.on('change', function () {
            let file = this.files[0];
            if (!file) return;

            let formData = new FormData();
            formData.append('action', 'upload_image');
            formData.append('image', file);

            Swal.fire({
                title: 'Uploading image to S3...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            $.ajax({
                url: 'ajax/service/dynamicPages_services.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (res) {
                    Swal.close();
                    if ((res.status === 'success' || res.status === true) && res.url) {
                        // Update data model
                        updateNestedItemField(secIdx, fieldArrayName, itemIdx, fieldName, res.url);
                        // Update field directly in DOM
                        $(`#place_img_${secIdx}_${itemIdx}`).val(res.url);
                        // Re-render UI to update preview thumbnail
                        renderSectionsUI();
                        Swal.fire({
                            icon: 'success',
                            title: 'Uploaded!',
                            text: 'Image uploaded successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire('Error', res.message || 'Image upload failed', 'error');
                    }
                },
                error: function () {
                    Swal.close();
                    Swal.fire('Error', 'Failed to upload image', 'error');
                }
            });
            input.remove();
        });
        input.click();
    }

    // Image upload helper for top-level section fields (e.g. Hero banner image)
    function triggerImgUpload(idx) {
        let input = $('<input type="file" accept="image/*" style="display:none">');
        $('body').append(input);
        input.on('change', function () {
            let file = this.files[0];
            if (!file) return;

            let formData = new FormData();
            formData.append('action', 'upload_image');
            formData.append('image', file);

            Swal.fire({
                title: 'Uploading image to S3...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            $.ajax({
                url: 'ajax/service/dynamicPages_services.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (res) {
                    Swal.close();
                    if ((res.status === 'success' || res.status === true) && res.url) {
                        $('#sec_img_' + idx).val(res.url);
                        $('#overview_img_' + idx).val(res.url);
                        updateSecField(idx, 'image', res.url);
                        renderSectionsUI();
                        Swal.fire({
                            icon: 'success',
                            title: 'Uploaded!',
                            text: 'Image uploaded successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire('Error', res.message || 'Image upload failed', 'error');
                    }
                },
                error: function () {
                    Swal.close();
                    Swal.fire('Error', 'Failed to upload image', 'error');
                }
            });
            input.remove();
        });
        input.click();
    }

    // Global Image Preview Modal
    function previewImageModal(url) {
        if (!url || !url.trim()) {
            Swal.fire({
                icon: 'info',
                title: 'No Image',
                text: 'Please upload or paste an image URL first to preview it.'
            });
            return;
        }
        Swal.fire({
            title: 'Image Preview',
            imageUrl: url,
            imageAlt: 'Preview Image',
            imageWidth: 500,
            showCloseButton: true,
            showConfirmButton: true,
            confirmButtonText: '<i class="fa fa-external-link me-1"></i> Open Original',
            showCancelButton: true,
            cancelButtonText: 'Close',
            preConfirm: () => {
                window.open(url, '_blank');
            }
        });
    }

    // Live update & clear helpers for Hero image
    function updateHeroImagePreview(secIdx, val) {
        updateSecField(secIdx, 'image', val);
        let box = $(`#preview_hero_${secIdx}`);
        if (val && val.trim()) {
            box.html(`
            <img src="${escapeHtml(val)}" alt="Hero Preview" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="previewImageModal('${escapeHtml(val)}')">
            <button type="button" class="btn btn-danger btn-xs position-absolute top-0 end-0 p-0 d-flex align-items-center justify-content-center shadow" style="width: 20px; height: 20px; font-size: 10px; border-radius: 50%; margin: 3px;" onclick="clearHeroImage(${secIdx})" title="Remove image"><i class="fa fa-times"></i></button>
        `);
        } else {
            box.html('<div class="text-center text-muted" style="font-size: 11px;"><i class="fa fa-image fa-2x d-block mb-1 text-secondary"></i>No image</div>');
        }
    }


    // Overview Image Preview and Clear helpers
    function updateOverviewImagePreview(secIdx, val) {
        updateSecField(secIdx, 'image', val);
        let box = $(`#preview_overview_${secIdx}`);
        if (val && val.trim()) {
            box.html(`
            <img src="${escapeHtml(val)}" alt="Overview Preview" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="previewImageModal('${escapeHtml(val)}')">
            <button type="button" class="btn btn-danger btn-xs position-absolute top-0 end-0 p-0 d-flex align-items-center justify-content-center shadow" style="width: 18px; height: 18px; font-size: 10px; border-radius: 50%; margin: 2px;" onclick="clearOverviewImage(${secIdx})" title="Clear image"><i class="fa fa-times"></i></button>
            `);
        } else {
            box.html(`
            <div class="text-center text-muted" style="font-size: 10px;">
                <i class="fa fa-image fa-lg d-block mb-1 text-secondary"></i>No image
            </div>
            `);
        }
    }

    function clearOverviewImage(secIdx) {
        $('#overview_img_' + secIdx).val('');
        updateSecField(secIdx, 'image', '');
        updateOverviewImagePreview(secIdx, '');
    }

    function clearHeroImage(secIdx) {
        $('#sec_img_' + secIdx).val('');
        updateSecField(secIdx, 'image', '');
        updateHeroImagePreview(secIdx, '');
    }

    // Live update & clear helpers for Place Card images
    function updatePlaceImagePreview(secIdx, itIdx, val) {
        updateNestedItemField(secIdx, 'places', itIdx, 'image', val);
        let box = $(`#preview_place_${secIdx}_${itIdx}`);
        if (val && val.trim()) {
            box.html(`
            <img src="${escapeHtml(val)}" alt="Place Preview" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="previewImageModal('${escapeHtml(val)}')">
            <button type="button" class="btn btn-danger btn-xs position-absolute top-0 end-0 p-0 d-flex align-items-center justify-content-center shadow" style="width: 18px; height: 18px; font-size: 10px; border-radius: 50%; margin: 2px;" onclick="clearPlaceImage(${secIdx}, ${itIdx})" title="Clear image"><i class="fa fa-times"></i></button>
        `);
        } else {
            box.html('<div class="text-center text-muted" style="font-size: 10px;"><i class="fa fa-image fa-lg d-block mb-1 text-secondary"></i>No image</div>');
        }
    }

    function clearPlaceImage(secIdx, itIdx) {
        $(`#place_img_${secIdx}_${itIdx}`).val('');
        updateNestedItemField(secIdx, 'places', itIdx, 'image', '');
        updatePlaceImagePreview(secIdx, itIdx, '');
    }


    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // ==========================================
    // SCHEMA SCRIPT VALIDATION & FORMATTING
    // ==========================================
    function extractSchemaTypes(obj, typesArray) {
        if (!obj || typeof obj !== 'object') return;
        if (Array.isArray(obj)) {
            obj.forEach(item => extractSchemaTypes(item, typesArray));
            return;
        }
        if (obj['@type']) {
            if (Array.isArray(obj['@type'])) {
                obj['@type'].forEach(t => typesArray.push(t));
            } else {
                typesArray.push(obj['@type']);
            }
        }
        if (obj['@graph'] && Array.isArray(obj['@graph'])) {
            obj['@graph'].forEach(item => extractSchemaTypes(item, typesArray));
        }
    }

    function tryParseConcatenatedJson(str) {
        let trimmed = str.trim();
        let items = [];
        let depth = 0;
        let inString = false;
        let escape = false;
        let startIdx = -1;

        for (let i = 0; i < trimmed.length; i++) {
            let ch = trimmed[i];
            if (escape) {
                escape = false;
                continue;
            }
            if (ch === '\\' && inString) {
                escape = true;
                continue;
            }
            if (ch === '"') {
                inString = !inString;
                continue;
            }
            if (!inString) {
                if (ch === '{') {
                    if (depth === 0) startIdx = i;
                    depth++;
                } else if (ch === '}') {
                    depth--;
                    if (depth === 0 && startIdx !== -1) {
                        let jsonSlice = trimmed.substring(startIdx, i + 1);
                        try {
                            let parsed = JSON.parse(jsonSlice);
                            items.push(parsed);
                        } catch (e) {
                            return { success: false, items: [] };
                        }
                        startIdx = -1;
                    }
                }
            }
        }

        if (depth === 0 && items.length > 0) {
            return { success: true, items: items };
        }
        return { success: false, items: [] };
    }

    function getJsonErrorLocationSnippet(str, errMsg) {
        try {
            let posMatch = errMsg.match(/position\s+(\d+)/i);
            if (posMatch) {
                let pos = parseInt(posMatch[1], 10);
                let start = Math.max(0, pos - 20);
                let end = Math.min(str.length, pos + 20);
                let snippet = str.substring(start, end).replace(/\s+/g, ' ');
                return 'near "...' + snippet + '..."';
            }
            let lineMatch = errMsg.match(/line\s+(\d+)/i);
            if (lineMatch) {
                return 'around line ' + lineMatch[1];
            }
        } catch (e) { }
        return '';
    }

    function validateSchemaMarkup(rawInput) {
        let input = (rawInput || '').trim();
        if (!input) {
            return {
                isValid: true,
                isEmpty: true,
                blocksCount: 0,
                types: [],
                errors: [],
                warnings: []
            };
        }

        let scriptTagRegex = new RegExp('<script\\b([^>]*)>([\\s\\S]*?)<\\/script>', 'gi');
        let matches = [];
        let match;

        while ((match = scriptTagRegex.exec(input)) !== null) {
            matches.push({
                attributes: match[1],
                content: match[2].trim()
            });
        }

        let errors = [];
        let warnings = [];
        let validBlocks = [];
        let detectedTypes = [];

        let openScriptRegex = new RegExp('<script\\b', 'gi');
        let closeScriptRegex = new RegExp('<\\/script>', 'gi');

        if (input.toLowerCase().includes('<script') || matches.length > 0) {
            let openCount = (input.match(openScriptRegex) || []).length;
            let closeCount = (input.match(closeScriptRegex) || []).length;

            if (openCount > closeCount) {
                errors.push('Unclosed <' + 'script> tag detected. You have ' + openCount + ' opening <' + 'script> tag(s) but only ' + closeCount + ' closing </' + 'script> tag(s).');
                return {
                    isValid: false,
                    isEmpty: false,
                    blocksCount: matches.length,
                    types: [],
                    errors: errors,
                    warnings: warnings
                };
            }

            if (matches.length === 0) {
                errors.push('No valid <' + 'script>...</' + 'script> block could be parsed. Please verify your script tags.');
                return {
                    isValid: false,
                    isEmpty: false,
                    blocksCount: 0,
                    types: [],
                    errors: errors,
                    warnings: warnings
                };
            }

            // Check for loose text outside of script tags
            let textWithoutScripts = input.replace(/<!--[\s\S]*?-->/g, '').replace(new RegExp('<script\\b[^>]*>[\\s\\S]*?<\\/script>', 'gi'), '').trim();
            if (textWithoutScripts.length > 0) {
                let preview = textWithoutScripts.length > 50 ? textWithoutScripts.substring(0, 50) + '...' : textWithoutScripts;
                warnings.push('Detected extra text outside <' + 'script> tags: "' + preview + '". Only <' + 'script> tags should be present.');
            }

            // Validate each script block
            matches.forEach((item, idx) => {
                let blockNum = idx + 1;
                let attrs = item.attributes.toLowerCase();

                if (!attrs.includes('application/ld+json')) {
                    warnings.push('Script Block #' + blockNum + ' is missing type="application/ld+json".');
                }

                if (!item.content) {
                    errors.push('Script Block #' + blockNum + ' is empty.');
                    return;
                }

                // Check for JS comments in JSON
                if (/\/\*[\s\S]*?\*\/|\/\/.*/.test(item.content)) {
                    warnings.push('Script Block #' + blockNum + ' appears to contain JavaScript comments (// or /* */). Standard JSON-LD requires pure JSON.');
                }

                try {
                    let cleaned = item.content.replace(/^<!--|-->$/g, '').trim();
                    let parsed = JSON.parse(cleaned);
                    validBlocks.push(parsed);
                    extractSchemaTypes(parsed, detectedTypes);
                } catch (jsonErr) {
                    let errSnippet = getJsonErrorLocationSnippet(item.content, jsonErr.message);
                    errors.push('Script Block #' + blockNum + ' JSON Syntax Error: ' + jsonErr.message + (errSnippet ? ' (' + errSnippet + ')' : ''));
                }
            });

        } else {
            // Raw JSON (no script tags)
            let parsedMulti = tryParseConcatenatedJson(input);
            if (parsedMulti.success) {
                parsedMulti.items.forEach(item => {
                    validBlocks.push(item);
                    extractSchemaTypes(item, detectedTypes);
                });
                warnings.push('Raw JSON provided without <' + 'script type="application/ld+json"> tag. You can click "Format / Beautify" to wrap them automatically.');
            } else {
                try {
                    let parsed = JSON.parse(input);
                    validBlocks.push(parsed);
                    extractSchemaTypes(parsed, detectedTypes);
                    warnings.push('Raw JSON provided without <' + 'script type="application/ld+json"> tag. You can click "Format / Beautify" to wrap them automatically.');
                } catch (jsonErr) {
                    let errSnippet = getJsonErrorLocationSnippet(input, jsonErr.message);
                    errors.push('JSON Syntax Error: ' + jsonErr.message + (errSnippet ? ' (' + errSnippet + ')' : ''));
                }
            }
        }

        return {
            isValid: errors.length === 0,
            isEmpty: false,
            blocksCount: validBlocks.length,
            types: [...new Set(detectedTypes)],
            errors: errors,
            warnings: warnings
        };
    }

    function renderSchemaValidationFeedback(isManual = false) {
        let raw = $('#schemaMarkup').val();
        let res = validateSchemaMarkup(raw);
        let $status = $('#schemaValidationStatus');

        if (res.isEmpty) {
            $status.hide().html('');
            return res;
        }

        $status.show();

        if (!res.isValid) {
            let errHtml = '<div class="alert alert-danger py-2 px-3 mb-0 border-0 shadow-sm" style="font-size: 12.5px; background: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444 !important;">' +
                '<div class="fw-bold mb-1 d-flex align-items-center gap-1">' +
                '<i class="fa fa-times-circle text-danger"></i> Invalid Schema Script (' + res.errors.length + ' error' + (res.errors.length > 1 ? 's' : '') + ')' +
                '</div>' +
                '<ul class="mb-0 ps-3">' +
                res.errors.map(e => '<li>' + escapeHtml(e) + '</li>').join('') +
                '</ul>' +
                '</div>';
            $status.html(errHtml);
        } else {
            let typesBadges = res.types.map(t => '<span class="badge bg-success bg-opacity-75 me-1">' + escapeHtml(t) + '</span>').join('');
            let warnHtml = '';
            if (res.warnings.length > 0) {
                warnHtml = '<div class="mt-1 small text-muted fst-italic ps-1">' +
                    res.warnings.map(w => '<div><i class="fa fa-info-circle text-warning me-1"></i>' + escapeHtml(w) + '</div>').join('') +
                    '</div>';
            }

            let successHtml = '<div class="alert alert-success py-2 px-3 mb-0 border-0 shadow-sm" style="font-size: 12.5px; background: #ecfdf5; color: #065f46; border-left: 4px solid #10b981 !important;">' +
                '<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">' +
                '<div>' +
                '<i class="fa fa-check-circle text-success me-1"></i> ' +
                '<strong>Valid Schema Markup!</strong> ' +
                'Found <strong>' + res.blocksCount + '</strong> valid script block' + (res.blocksCount > 1 ? 's' : '') +
                (typesBadges ? ': ' + typesBadges : '') +
                '</div>' +
                '<span class="badge bg-success text-white px-2 py-1"><i class="fa fa-check me-1"></i> Ready to Save</span>' +
                '</div>' +
                warnHtml +
                '</div>';
            $status.html(successHtml);
        }

        return res;
    }

    function beautifySchemaMarkup() {
        let raw = $('#schemaMarkup').val().trim();
        if (!raw) {
            Swal.fire('Info', 'Nothing to beautify. Please enter a schema script first.', 'info');
            return;
        }

        let validation = validateSchemaMarkup(raw);
        if (!validation.isValid) {
            Swal.fire({
                icon: 'warning',
                title: 'Cannot Format Invalid Schema',
                html: 'Please resolve syntax errors before formatting:<br><br><span class="text-danger small">' +
                    validation.errors.map(e => '• ' + escapeHtml(e)).join('<br>') + '</span>'
            });
            return;
        }

        try {
            let formatted = '';
            let scriptTagRegex = new RegExp('<script\\b([^>]*)>([\\s\\S]*?)<\\/script>', 'gi');
            let matches = [];
            let match;

            while ((match = scriptTagRegex.exec(raw)) !== null) {
                matches.push({
                    attrs: match[1],
                    content: match[2].trim()
                });
            }

            let openTag = '<' + 'script type="application/ld+json">\n';
            let closeTag = '\n</' + 'script>';

            if (matches.length > 0) {
                let formattedBlocks = [];
                matches.forEach(item => {
                    let cleaned = item.content.replace(/^<!--|-->$/g, '').trim();
                    let parsed = JSON.parse(cleaned);
                    let prettyJson = JSON.stringify(parsed, null, 2);
                    formattedBlocks.push(openTag + prettyJson + closeTag);
                });
                formatted = formattedBlocks.join('\n\n');
            } else {
                let parsedMulti = tryParseConcatenatedJson(raw);
                if (parsedMulti.success) {
                    let formattedBlocks = [];
                    parsedMulti.items.forEach(item => {
                        let prettyJson = JSON.stringify(item, null, 2);
                        formattedBlocks.push(openTag + prettyJson + closeTag);
                    });
                    formatted = formattedBlocks.join('\n\n');
                } else {
                    let parsed = JSON.parse(raw);
                    let prettyJson = JSON.stringify(parsed, null, 2);
                    formatted = openTag + prettyJson + closeTag;
                }
            }

            $('#schemaMarkup').val(formatted);
            renderSchemaValidationFeedback();

            Swal.fire({
                icon: 'success',
                title: 'Formatted!',
                text: 'Schema script(s) beautified and formatted successfully.',
                timer: 1500,
                showConfirmButton: false
            });
        } catch (e) {
            Swal.fire('Error', 'Failed to format schema: ' + e.message, 'error');
        }
    }
</script>