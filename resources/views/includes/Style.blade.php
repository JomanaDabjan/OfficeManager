@push('Style')
<style>
    /* ==========================================================================
       Global Typography and Smoothing Settings
       ========================================================================== */
    body,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    .sidebar,
    .main-panel,
    .navbar {
        font-family: 'Inter', 'Montserrat', sans-serif !important;
        letter-spacing: -0.01em;
    }

    body {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
    }

    /* ==========================================================================
       Sidebar Navigation Styling
       ========================================================================== */
    .sidebar .nav p {
        font-weight: 500;
        font-size: 14px;
    }

    .sidebar .collapse .nav a {
        font-size: 13px;
        padding: 8px 15px 8px 50px;
    }

    .sidebar .nav li.active>a {
        background: linear-gradient(0deg, #ff8a65 0%, #ff7043 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 20px 0px rgba(0, 0, 0, 0.14), 0 7px 10px -5px rgba(255, 112, 67, 0.4);
        border-radius: 0.35rem;
    }

    .sidebar .collapse .nav li.active>a {
        background-color: rgba(255, 255, 255, 0.15) !important;
        color: #ffffff !important;
        font-weight: 600;
        border-radius: 0.25rem;
    }

    .sidebar .nav li>a:hover {
        background-color: rgba(255, 255, 255, 0.08);
        border-radius: 0.35rem;
    }

    /* ==========================================================================
       Layout & Container Spacers
       ========================================================================== */
    .main-panel,
    .content,
    body,
    .card {
        background-color: #ffffff !important;
    }

    .content,
    .container-fluid {
        padding-left: 30px !important;
        padding-right: 30px !important;
    }

    .row.mt-4.mb-4.align-items-center {
        margin-left: 10px !important;
        margin-right: 10px !important;
    }

    /* ==========================================================================
       Data Table Design & Formatting (Projects & Tasks Tables)
       ========================================================================== */
    #projectsTable,
    #projectsTable th,
    #projectsTable td,
    #tasksTable,
    #tasksTable th,
    #tasksTable td {
        border: 1px solid #dee2e6 !important;
        text-align: center !important;
    }

    #projectsTable,
    #tasksTable {
        border-collapse: collapse !important;
    }

    #projectsTable th,
    #tasksTable th,
    .custom-table-header th {
        font-size: 12px !important;
        font-weight: 600 !important;
        letter-spacing: 0.8px;
        padding-top: 12px !important;
        padding-bottom: 12px !important;
        text-transform: uppercase;
        color: #ffffff !important;
        border-top: none !important;
        border-bottom: none !important;
    }

    .custom-table-header {
        background-color: #ff7043 !important;
    }

    #projectsTable td,
    #tasksTable td {
        padding: 14px 12px !important;
        font-size: 14px !important;
        vertical-align: middle !important;
    }

    .project-title,
    .task-title {
        font-weight: 600;
        color: #2c3e50 !important;
    }

    .project-desc,
    .task-desc {
        color: #7f8c8d !important;
        font-size: 13px !important;
    }

    #projectsTable .badge,
    #tasksTable .badge {
        font-size: 11px;
        letter-spacing: 0.3px;
        font-weight: 600;
    }

    /* ==========================================================================
       Interactive Rounded Search Box Styling
       ========================================================================== */
    .search-container {
        position: relative;
        max-width: 320px;
        margin-left: 15px;
    }

    .search-container .form-control {
        background-color: #f9fbfd !important;
        border: 2px solid #ced4da !important;
        border-radius: 30px !important;
        padding-left: 45px !important;
        font-size: 14px;
        height: 40px;
        color: #495057;
        box-shadow: none !important;
        transition: all 0.3s ease;
    }

    .search-container .form-control:focus {
        background-color: #ffffff !important;
        border-color: #f96332 !important;
        box-shadow: 0 0 8px rgba(249, 99, 50, 0.4) !important;
    }

    .search-container .search-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #8898aa;
        font-size: 15px;
        z-index: 10;
    }

    /* ==========================================================================
       Form Cards Styling (Create/Edit Projects & Tasks Forms)
       ========================================================================== */
    .project-form-card,
    .tasks-table-card {
        border-radius: 12px;
        box-shadow: 0 10px 30px 0px rgba(0, 0, 0, 0.08) !important;
        border: 1px solid #eaeaea !important;
        overflow: hidden;
        background-color: #ffffff !important;
        margin-top: 10px;
        margin-bottom: 30px;
    }

    .custom-card-header {
        background: linear-gradient(135deg, #ff8a65 0%, #ff7043 100%) !important;
        border-bottom: none;
    }

    .icon-shape {
        display: inline-flex;
        padding: 10px;
        text-align: center;
        border-radius: 50%;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        flex-shrink: 0;
    }

    .icon-shape i {
        font-size: 16px;
        color: #ff7043 !important;
    }

    .project-form-card .form-control {
        background-color: #f9fbfd !important;
        border: 1.5px solid #ced4da !important;
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 14px;
        color: #495057;
        height: auto;
        transition: all 0.3s ease;
    }

    .project-form-card .form-control:focus {
        background-color: #ffffff !important;
        border-color: #f96332 !important;
        box-shadow: 0 0 8px rgba(249, 99, 50, 0.25) !important;
    }

    .project-form-card .form-control-label {
        font-size: 13px;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #333333 !important;
    }

    .project-form-card .btn {
        font-weight: 600;
        letter-spacing: 0.3px;
        padding: 10px 24px;
    }

    /* ==========================================================================
       This code is for fixing the scrollbar issue
       ========================================================================== */
    /* Maintain proper container flow for the main wrapper */
    .wrapper {
        position: relative;
        top: 0;
        height: auto;
    }

    /* Ensure the main panel respects the sidebar space and scrolls correctly */
    .main-panel {
        position: relative;
        float: right !important;
        /* Forces the panel to stay on the correct side of the sidebar */
        width: calc(100% - 260px) !important;
        /* Matches the exact width accounting for the sidebar */
        min-height: 100vh;
        overflow-y: auto !important;
    }

    /* Fix content spacing inside the panel */
    .main-panel>.content {
        padding: 30px 15px;
        min-height: calc(100vh - 120px);
    }

    /* Responsive fix for smaller screens where sidebar collapses */
    @media screen and (max-width: 991px) {
        .main-panel {
            width: 100% !important;
            float: none !important;
        }
    }
</style>
@endpush
