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
       Sidebar Navigation Styling & Smooth Collapse/Expand Transitions
       ========================================================================== */
    /* Base sidebar positioning and transition properties */
    .sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 1030;
        width: 260px;
        transition: width 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55), transform 0.3s ease;
    }

    /* Mini sidebar state configuration when collapsed */
    .sidebar-mini .sidebar {
        width: 80px !important;
    }

    /* Hide text labels, normal logos, and carets when sidebar is minimized */
    .sidebar-mini .sidebar .logo .logo-normal,
    .sidebar-mini .sidebar .nav p,
    .sidebar-mini .sidebar .caret {
        display: none !important;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s ease;
    }

    /* Show mini logo icon when sidebar is minimized */
    .sidebar-mini .sidebar .logo .logo-mini {
        display: block !important;
        opacity: 1;
        visibility: visible;
    }

    /* Adjust link padding and icon alignment in mini state */
    .sidebar-mini .sidebar .nav li>a {
        padding-left: 25px !important;
    }

    .sidebar-mini .sidebar .nav li>a i {
        font-size: 20px;
        margin-right: 0;
    }

    /* Smoothly adjust main panel width when sidebar expands or collapses */
    .main-panel {
        position: relative !important;
        float: right !important;
        width: calc(100% - 260px) !important;
        transition: width 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .sidebar-mini .main-panel {
        width: calc(100% - 80px) !important;
    }

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
       Scrollbar for Dropdown Menus
       ========================================================================== */
    .dropdown-menu {
        max-height: 250px !important;
        /* You can adjust the height as needed */
        overflow-y: auto !important;
        overflow-x: hidden !important;
    }

    /* Customize the scrollbar appearance inside dropdown menus to appear on hover only */
    .dropdown-menu {
        scrollbar-width: thin !important;
        scrollbar-color: transparent transparent !important;
        transition: scrollbar-color 0.3s ease;
    }

    .dropdown-menu:hover {
        scrollbar-color: rgba(0, 0, 0, 0.2) transparent !important;
    }

    .dropdown-menu::-webkit-scrollbar {
        width: 5px !important;
    }

    .dropdown-menu::-webkit-scrollbar-thumb {
        background-color: transparent !important;
        border-radius: 10px;
    }

    .dropdown-menu:hover::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.25) !important;
    }

    /* ==========================================================================
       Button Hover Effects
       ========================================================================== */

    /* Hover effect for the Login button */
    .navbar-nav .btn-neutral:hover {
        background-color: #ff6b00 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(255, 107, 0, 0.4);
        transform: translateY(-1px);
    }

    /* Hover effect specifically for the Logout button */
    .navbar-nav .logout-btn:hover {
        background-color: #ef4444 !important;
        /* Modern red warning color on hover */
        border-color: #ef4444 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
        transform: translateY(-1px);
    }


    /* ==========================================================================
         Welcome Modal Styling (Persistent via LocalStorage)
       ========================================================================== */
    /* Fullscreen dark overlay with blur effect */
    .modal-backdrop-custom {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 17, 23, 0.75);
        backdrop-filter: blur(5px);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeInModal 0.3s ease-out;
    }

    /* Modern card layout with centered content alignment */
    .welcome-card {
        position: relative;
        width: 370px;
        background: linear-gradient(135deg, #1e1e24 0%, #2d2b38 100%);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 16px;
        padding: 24px 20px 18px 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    /* Circular gradient icon wrapper with Pulse Animation */
    .welcome-icon-container {
        width: 54px;
        height: 54px;
        margin-bottom: 12px;
        background: linear-gradient(135deg, #ff8c00 0%, #ff5e62 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        box-shadow: 0 6px 15px rgba(255, 94, 98, 0.4);
    }

    /* Pulse keyframes for icon attention */
    .pulse-animation {
        animation: iconPulse 2s infinite;
    }

    @keyframes iconPulse {
        0% {
            box-shadow: 0 0 0 0 rgba(255, 140, 0, 0.6);
        }

        70% {
            box-shadow: 0 0 0 12px rgba(255, 140, 0, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(255, 140, 0, 0);
        }
    }

    /* Close button styling positioned top-right */
    .close-welcome-btn {
        position: absolute;
        top: 12px;
        right: 15px;
        background: transparent;
        border: none;
        color: rgba(255, 255, 255, 0.5);
        font-size: 20px;
        cursor: pointer;
        transition: color 0.2s;
    }

    .close-welcome-btn:hover {
        color: #fff;
    }

    /* Content Area Container */
    .welcome-content-area {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .welcome-title {
        color: #ffffff;
        font-weight: 700;
        font-size: 20px;
        margin-bottom: 12px;
        margin-top: 0;
    }

    /* Motivational message pill styling */
    .motivational-pill {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: rgba(255, 140, 0, 0.12);
        border: 1px solid rgba(255, 140, 0, 0.35);
        color: #ffb347;
        font-size: 12px;
        font-weight: 600;
        padding: 12px 14px;
        border-radius: 14px;
        letter-spacing: 0.2px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        margin-bottom: 12px;
        line-height: 1.4;
        text-align: center;
        width: 100%;
    }

    .motivational-bulb-icon {
        font-size: 18px;
        margin-bottom: 6px;
        color: #ffb347;
        display: block;
    }

    /* Action button styling */
    .welcome-action-area {
        width: 100%;
        margin-bottom: 10px;
    }

    .btn-letser-go {
        width: 100%;
        background: linear-gradient(135deg, #ff8c00 0%, #ff5e62 100%);
        border: none;
        border-radius: 20px;
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        padding: 9px 0;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(255, 94, 98, 0.4);
        transition: all 0.2s ease-in-out;
    }

    .btn-letser-go:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(255, 94, 98, 0.6);
    }

    /* Progress bar track container */
    .welcome-progress-track {
        width: 100%;
        height: 3px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
        margin-top: 6px;
        overflow: hidden;
    }

    .welcome-progress-fill {
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, #ff8c00, #ff5e62);
        transition: width linear;
    }

    @keyframes fadeInModal {
        from {
            opacity: 0;
            transform: scale(0.92);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>
</style>
@endpush
