@push('Style')
<style>
    /* ==========================================================================
        Clean Layout & Single Scrollbar Reset
        ========================================================================== */
    html {
        height: 100% !important;
        overflow-x: hidden !important;
        overflow-y: auto !important;
        scroll-behavior: smooth !important;
    }

    body {
        height: 100% !important;
        overflow-x: hidden !important;
        overflow-y: visible !important;
        margin: 0 !important;
        padding: 0 !important;
        font-family: 'Inter', 'Montserrat', sans-serif !important;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* ==========================================================================
        Global Browser Scrollbar Customization
        ========================================================================== */
    * {
        scrollbar-width: thin !important;
        scrollbar-color: rgba(255, 112, 67, 0.6) #f1f1f1 !important;
    }

    ::-webkit-scrollbar {
        width: 8px !important;
        height: 0px !important;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1 !important;
    }

    ::-webkit-scrollbar-thumb {
        background: rgba(255, 112, 67, 0.7) !important;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 112, 67, 1) !important;
    }

    /* ==========================================================================
        Sidebar & Main Panel Fixes (Preventing Inner Scrollbars)
        ========================================================================== */
    .sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 1030;
        width: 260px;
        overflow-y: auto;
        overflow-x: hidden;
        transition: width 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55), transform 0.3s ease;
    }

    .sidebar-mini .sidebar {
        width: 80px !important;
    }

    .sidebar-mini .sidebar .logo .logo-normal,
    .sidebar-mini .sidebar .nav p,
    .sidebar-mini .sidebar .caret {
        display: none !important;
        opacity: 0;
        visibility: hidden;
    }

    .sidebar-mini .sidebar .logo .logo-mini {
        display: block !important;
        opacity: 1;
        visibility: visible;
    }

    .sidebar-mini .sidebar .nav li>a {
        padding-left: 25px !important;
    }

    .sidebar-mini .sidebar .nav li>a i {
        font-size: 20px;
        margin-right: 0;
    }

    .main-panel {
        position: relative !important;
        float: right !important;
        width: calc(100% - 260px) !important;
        max-width: calc(100% - 260px) !important;
        min-height: 100vh !important;
        transition: width 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        overflow: visible !important;
    }

    .sidebar-mini .main-panel {
        width: calc(100% - 80px) !important;
        max-width: calc(100% - 80px) !important;
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
        padding-left: 15px !important;
        padding-right: 15px !important;
        width: 100% !important;
        max-width: 100% !important;
        overflow-x: hidden !important;
        box-sizing: border-box !important;
    }

    .row {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    /* ==========================================================================
        Now UI Professional Data Table Styling
        ========================================================================== */
    #projectsTable,
    #tasksTable {
        width: 100% !important;
        max-width: 100% !important;
        table-layout: auto;
        border-collapse: collapse !important;
        border: 2px solid #e3e3e3 !important;
        background-color: #ffffff !important;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
        margin-bottom: 20px;
        box-sizing: border-box !important;
    }

    #projectsTable thead,
    #tasksTable thead,
    .custom-table-header {
        background: linear-gradient(135deg, #ff8a65 0%, #ff7043 100%) !important;
        color: #ffffff !important;
    }

    #projectsTable th,
    #tasksTable th,
    .custom-table-header th {
        font-size: 11px !important;
        font-weight: 600 !important;
        letter-spacing: 0.5px;
        padding: 12px 8px !important;
        text-transform: uppercase;
        color: #ffffff !important;
        border: 1px solid #ff7043 !important;
        text-align: center !important;
    }

    #projectsTable tbody,
    #tasksTable tbody {
        background-color: #fcfcfc !important;
    }

    #projectsTable tbody tr:nth-child(even),
    #tasksTable tbody tr:nth-child(even) {
        background-color: #f7f9fa !important;
    }

    #projectsTable tbody tr:hover,
    #tasksTable tbody tr:hover {
        background-color: #f1f3f5 !important;
    }

    #projectsTable td,
    #tasksTable td {
        border: 1px solid #e9ecef !important;
        padding: 12px 8px !important;
        font-size: 13px !important;
        vertical-align: middle !important;
        color: #3c4858 !important;
        text-align: center !important;
    }

    .project-title,
    .task-title {
        font-weight: 600;
        color: #2c3e50 !important;
    }

    .project-desc,
    .task-desc {
        color: #7f8c8d !important;
        font-size: 12px !important;
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
        Form Cards Styling
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
        overflow-y: auto !important;
        overflow-x: hidden !important;
    }

    /* ==========================================================================
        Button Hover Effects
        ========================================================================== */
    .navbar-nav .btn-neutral:hover {
        background-color: #ff6b00 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(255, 107, 0, 0.4);
        transform: translateY(-1px);
    }

    .navbar-nav .logout-btn:hover {
        background-color: #ef4444 !important;
        border-color: #ef4444 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
        transform: translateY(-1px);
    }

    /* ==========================================================================
        Welcome Modal Styling
        ========================================================================== */
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
@endpush
