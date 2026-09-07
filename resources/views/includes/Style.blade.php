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

    /* ========================================================================== */
    /* TABLE HEADER - ONE CONTINUOUS GRADIENT                                    */
    /* ========================================================================== */

    #projectsTable thead,
    #tasksTable thead,
    #teamsTable thead,
    .custom-table-header {
        background: linear-gradient(135deg,
                #ff6338 0%,
                #ff8c42 100%) !important;

        color: #ffffff !important;
    }

    /* جميع أعمدة الـthead تكون شفافة حتى يظهر نفس التدرج المستمر */
    #projectsTable thead th,
    #tasksTable thead th,
    #teamsTable thead th,
    .custom-table-header th {
        background: transparent !important;

        color: #ffffff !important;

        font-size: 13px !important;
        font-weight: 700 !important;

        text-align: center !important;

        padding: 12px 8px !important;

        border: 1px solid rgba(255, 255, 255, 0.18) !important;

        white-space: nowrap;

        vertical-align: middle !important;
    }

    /* تأثير السحب */
    #projectsTable thead th.draggable-th,
    #tasksTable thead th.draggable-th,
    #teamsTable thead th.draggable-th {
        background: transparent !important;
        color: #ffffff !important;
        cursor: grab;
    }

    /* عند الضغط والسحب */
    #projectsTable thead th.draggable-th:active,
    #tasksTable thead th.draggable-th:active,
    #teamsTable thead th.draggable-th:active {
        cursor: grabbing;
    }

    /* ==========================================================================
        TABLE BODY
        ========================================================================== */

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
    @keyframes floatLogo {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-8px);
        }

        100% {
            transform: translateY(0px);
        }
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

    #custom-welcome-modal button:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(249, 99, 50, 0.6) !important;
    }

    /* ========================================================================== */
    /* PRINT PREVIEW - TABLE ONLY                                                 */
    /* ========================================================================== */

    @media print {

        /* ---------------------------------------------------------------------- */
        /* PAGE                                                                   */
        /* ---------------------------------------------------------------------- */

        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        /* ---------------------------------------------------------------------- */
        /* HIDE COMPLETE NORMAL PAGE                                              */
        /* ---------------------------------------------------------------------- */

        body>* {
            display: none !important;
        }

        /* ---------------------------------------------------------------------- */
        /* SHOW ONLY TEMPORARY PRINT CONTAINER                                    */
        /* ---------------------------------------------------------------------- */

        body>#temporaryPrintContainer {
            display: block !important;
            visibility: visible !important;

            position: static !important;

            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;

            margin: 0 !important;
            padding: 0 !important;

            background: #ffffff !important;
        }

        #temporaryPrintContainer,
        #temporaryPrintContainer * {
            visibility: visible !important;
        }

        /* ---------------------------------------------------------------------- */
        /* PAGE RESET                                                             */
        /* ---------------------------------------------------------------------- */

        html,
        body {
            width: 100% !important;
            height: auto !important;

            min-width: 0 !important;

            overflow: visible !important;

            margin: 0 !important;
            padding: 0 !important;

            background: #ffffff !important;

            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* ---------------------------------------------------------------------- */
        /* PRINT CONTAINER                                                        */
        /* ---------------------------------------------------------------------- */

        #temporaryPrintContainer {
            width: 100% !important;
            max-width: 100% !important;

            margin: 0 auto !important;
            padding: 0 !important;

            background: #ffffff !important;
        }

        /* ---------------------------------------------------------------------- */
        /* ALL TABLES                                                             */
        /* ---------------------------------------------------------------------- */

        #temporaryPrintContainer table {
            display: table !important;

            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;

            margin: 0 !important;
            padding: 0 !important;

            border-collapse: collapse !important;
            border-spacing: 0 !important;

            table-layout: fixed !important;

            background: #ffffff !important;

            font-family: 'Inter', 'Montserrat', Arial, sans-serif !important;

            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* ---------------------------------------------------------------------- */
        /* TABLE HEADER                                                           */
        /* ---------------------------------------------------------------------- */

        #temporaryPrintContainer table thead {
            display: table-header-group !important;

            background: #ff7043 !important;

            color: #ffffff !important;

            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        #temporaryPrintContainer table thead tr {
            display: table-row !important;
        }

        #temporaryPrintContainer table thead th {
            display: table-cell !important;

            /* ORANGE ONLY - NO GRADIENT */
            background: #ff7043 !important;

            color: #ffffff !important;

            /* CELL BORDERS */
            border: 1px solid #e05a35 !important;

            font-size: 11px !important;

            font-weight: 700 !important;

            padding: 9px 7px !important;

            text-align: center !important;

            vertical-align: middle !important;

            white-space: normal !important;

            line-height: 1.3 !important;

            height: auto !important;

            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* ---------------------------------------------------------------------- */
        /* TABLE BODY                                                             */
        /* ---------------------------------------------------------------------- */

        #temporaryPrintContainer table tbody {
            display: table-row-group !important;

            background: #ffffff !important;
        }

        #temporaryPrintContainer table tbody tr {
            display: table-row !important;

            background: #ffffff !important;

            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        /* ---------------------------------------------------------------------- */
        /* ALTERNATE ROWS                                                         */
        /* ---------------------------------------------------------------------- */

        #temporaryPrintContainer table tbody tr:nth-child(even) {
            background: #f7f9fa !important;
        }

        #temporaryPrintContainer table tbody tr:nth-child(odd) {
            background: #ffffff !important;
        }

        /* ---------------------------------------------------------------------- */
        /* ALL TABLE CELLS                                                        */
        /* ---------------------------------------------------------------------- */

        #temporaryPrintContainer table tbody td {
            display: table-cell !important;

            /* CELL BORDERS */
            border: 1px solid #d5dbe0 !important;

            font-size: 10px !important;

            padding: 8px 7px !important;

            color: #3c4858 !important;

            background: transparent !important;

            text-align: center !important;

            vertical-align: middle !important;

            line-height: 1.35 !important;

            word-break: break-word !important;

            overflow-wrap: break-word !important;

            white-space: normal !important;
        }

        /* ---------------------------------------------------------------------- */
        /* FORCE BORDERS ON EVERY TABLE CELL                                      */
        /* ---------------------------------------------------------------------- */

        #temporaryPrintContainer table th,
        #temporaryPrintContainer table td {
            border: 1px solid #d5dbe0 !important;
            border-collapse: collapse !important;
        }

        /* Header border color */
        #temporaryPrintContainer table thead th {
            border: 1px solid #e05a35 !important;
        }

    }

    /* ---------------------------------------------------------------------- */
    /* FIRST COLUMN                                                           */
    /* ---------------------------------------------------------------------- */

    #temporaryPrintContainer table tbody td:first-child {
        font-weight: 700 !important;

        color: #2c3e50 !important;

        text-align: center !important;
    }

    /* ---------------------------------------------------------------------- */
    /* LINKS                                                                  */
    /* ---------------------------------------------------------------------- */

    #temporaryPrintContainer table a {
        color: inherit !important;

        text-decoration: none !important;
    }

    /* ---------------------------------------------------------------------- */
    /* BLUE BADGES                                                            */
    /* ---------------------------------------------------------------------- */

    #temporaryPrintContainer table .badge,
    #temporaryPrintContainer table .badge-primary,
    #temporaryPrintContainer table .badge-info,
    #temporaryPrintContainer table .members-badge {
        display: inline-block !important;

        background: #1976d2 !important;

        color: #ffffff !important;

        border: 1px solid #1565c0 !important;

        border-radius: 999px !important;

        padding: 5px 12px !important;

        min-width: 65px !important;

        font-size: 9px !important;

        font-weight: 700 !important;

        line-height: 1.2 !important;

        text-align: center !important;

        white-space: nowrap !important;

        box-shadow: none !important;

        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* ---------------------------------------------------------------------- */
    /* AVATARS                                                                */
    /* ---------------------------------------------------------------------- */

    #temporaryPrintContainer table img,
    #temporaryPrintContainer table .avatar,
    #temporaryPrintContainer table .avatar-sm {
        width: 24px !important;

        height: 24px !important;

        min-width: 24px !important;

        display: inline-flex !important;

        align-items: center !important;

        justify-content: center !important;

        vertical-align: middle !important;
    }

    /* ---------------------------------------------------------------------- */
    /* REMOVE HOVER EFFECTS                                                   */
    /* ---------------------------------------------------------------------- */

    #temporaryPrintContainer table tbody tr:hover {
        background: inherit !important;
    }

    /* ---------------------------------------------------------------------- */
    /* HIDE INTERACTIVE ELEMENTS                                              */
    /* ---------------------------------------------------------------------- */

    #temporaryPrintContainer .d-print-none,
    #temporaryPrintContainer button,
    #temporaryPrintContainer .btn,
    #temporaryPrintContainer .dropdown,
    #temporaryPrintContainer .modal,
    #temporaryPrintContainer .dataTables_filter,
    #temporaryPrintContainer .dataTables_info,
    #temporaryPrintContainer .dataTables_paginate,
    #temporaryPrintContainer .dt-buttons {
        display: none !important;
    }

    /* ---------------------------------------------------------------------- */
    /* PREVENT DUPLICATED PRINT TEXT                                          */
    /* ---------------------------------------------------------------------- */

    #temporaryPrintContainer .d-print-block {
        display: none !important;
    }

    #temporaryPrintContainer table tbody td>.d-print-block {
        display: none !important;
    }

    /* ---------------------------------------------------------------------- */
    /* HIDE DUPLICATE PRINT ELEMENTS                                          */
    /* ---------------------------------------------------------------------- */

    #temporaryPrintContainer .print-only,
    #temporaryPrintContainer .print-full-description,
    #temporaryPrintContainer .print-description {
        display: none !important;
    }

    /* ---------------------------------------------------------------------- */
    /* PREVENT CONTENT FROM BEING CUT                                         */
    /* ---------------------------------------------------------------------- */

    #temporaryPrintContainer table th,
    #temporaryPrintContainer table td {
        page-break-inside: avoid !important;

        break-inside: avoid !important;
    }

    /* ---------------------------------------------------------------------- */
    /* GENERAL COLUMN WIDTH                                                   */
    /* ---------------------------------------------------------------------- */

    #temporaryPrintContainer table th,
    #temporaryPrintContainer table td {
        width: auto !important;
    }

    /* ---------------------------------------------------------------------- */
    /* PRINT TITLE                                                            */
    /* ---------------------------------------------------------------------- */

    #temporaryPrintContainer h1,
    #temporaryPrintContainer h2,
    #temporaryPrintContainer h3 {
        color: #2c3e50 !important;

        text-align: center !important;

        margin-top: 0 !important;

        margin-bottom: 12px !important;
    }

    /* ---------------------------------------------------------------------- */
    /* PRINT COLOR SUPPORT                                                    */
    /* ---------------------------------------------------------------------- */

    #temporaryPrintContainer table thead,
    #temporaryPrintContainer table thead tr,
    #temporaryPrintContainer table thead th,
    #temporaryPrintContainer table .badge,
    #temporaryPrintContainer table .badge-primary,
    #temporaryPrintContainer table .badge-info,
    #temporaryPrintContainer table .members-badge {

        -webkit-print-color-adjust: exact !important;

        print-color-adjust: exact !important;
    }
    }
</style>

<!-- ========================================== -->
<!-- TABLE SCROLL FIX STYLES                   -->
<!-- ========================================== -->
<style>
    /*
    |--------------------------------------------------------------------------
    | Project Report Table
    |--------------------------------------------------------------------------
    | The table is intentionally wider than the available screen.
    | This prevents columns from being compressed.
    |--------------------------------------------------------------------------
    */

    #printable-report {
        overflow: hidden !important;
        width: 100%;
        max-width: 100%;
    }

    #printable-report .card-body {
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }

    /*
    |--------------------------------------------------------------------------
    | TOP HORIZONTAL SCROLLBAR
    |--------------------------------------------------------------------------
    */

    .project-table-scroll-top {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        height: 18px;
        margin: 0;
        padding: 0;
        background: #ffffff;
        border-bottom: 1px solid #f1f1f1;
    }

    .project-table-scroll-top-inner {
        height: 1px;
        min-width: 1600px;
    }

    /*
    |--------------------------------------------------------------------------
    | BOTTOM HORIZONTAL SCROLLBAR
    |--------------------------------------------------------------------------
    */

    .project-table-scroll-bottom {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        margin: 0;
        padding: 0;
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUAL TABLE CONTAINER
    |--------------------------------------------------------------------------
    */

    .project-table-scroll {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        margin: 0;
        padding: 0;
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
    }

    /*
    |--------------------------------------------------------------------------
    | HIDE THE NATIVE TABLE SCROLLBAR
    |--------------------------------------------------------------------------
    |
    | The actual table container must remain horizontally scrollable,
    | because the TOP and BOTTOM custom scrollbars are synchronized with it.
    |
    | We hide ONLY the native scrollbar.
    | The scrolling functionality itself remains active.
    |
    */

    .project-table-scroll::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }

    /*
    |--------------------------------------------------------------------------
    | HIDE ANY INNER NATIVE SCROLLBAR
    |--------------------------------------------------------------------------
    |
    | If DataTables or another plugin creates an internal scrollable
    | element, its native scrollbar must also be hidden.
    |
    */

    .project-table-scroll * {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .project-table-scroll *::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }

    /*
    |--------------------------------------------------------------------------
    | DataTables SCROLL CONTAINERS
    |--------------------------------------------------------------------------
    */

    .project-table-scroll .dataTables_wrapper,
    .project-table-scroll .dataTables_scroll,
    .project-table-scroll .dataTables_scrollBody {
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
    }

    .project-table-scroll .dataTables_wrapper::-webkit-scrollbar,
    .project-table-scroll .dataTables_scroll::-webkit-scrollbar,
    .project-table-scroll .dataTables_scrollBody::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }

    /*
    |--------------------------------------------------------------------------
    | TABLE WIDTH
    |--------------------------------------------------------------------------
    |
    | The table must be wider than the screen so the browser does not
    | squeeze all nine columns into the available width.
    |--------------------------------------------------------------------------
    */

    #projectsTable {
        width: 1600px !important;
        min-width: 1600px !important;
        max-width: none !important;
        table-layout: auto !important;
        margin-bottom: 0 !important;
    }

    /*
    |--------------------------------------------------------------------------
    | Prevent important columns from becoming too narrow
    |--------------------------------------------------------------------------
    */

    #projectsTable th,
    #projectsTable td {
        vertical-align: middle;
    }

    #projectsTable th:nth-child(1),
    #projectsTable td:nth-child(1) {
        min-width: 160px;
        width: 160px;
    }

    #projectsTable th:nth-child(2),
    #projectsTable td:nth-child(2) {
        min-width: 170px;
        width: 170px;
    }

    #projectsTable th:nth-child(3),
    #projectsTable td:nth-child(3) {
        min-width: 150px;
        width: 150px;
    }

    #projectsTable th:nth-child(4),
    #projectsTable td:nth-child(4) {
        min-width: 100px;
        width: 100px;
    }

    #projectsTable th:nth-child(5),
    #projectsTable td:nth-child(5) {
        min-width: 100px;
        width: 100px;
    }

    #projectsTable th:nth-child(6),
    #projectsTable td:nth-child(6) {
        min-width: 120px;
        width: 120px;
    }

    #projectsTable th:nth-child(7),
    #projectsTable td:nth-child(7) {
        min-width: 130px;
        width: 130px;
    }

    #projectsTable th:nth-child(8),
    #projectsTable td:nth-child(8) {
        min-width: 300px;
        width: 300px;
    }

    #projectsTable th:nth-child(9),
    #projectsTable td:nth-child(9) {
        min-width: 150px;
        width: 150px;
    }

    /*
    |--------------------------------------------------------------------------
    | Keep scrollbar visible and clean
    |--------------------------------------------------------------------------
    */

    .project-table-scroll-top::-webkit-scrollbar,
    .project-table-scroll-bottom::-webkit-scrollbar {
        height: 10px;
    }

    .project-table-scroll-top::-webkit-scrollbar-track,
    .project-table-scroll-bottom::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .project-table-scroll-top::-webkit-scrollbar-thumb,
    .project-table-scroll-bottom::-webkit-scrollbar-thumb {
        background: #ff9a7a;
        border-radius: 10px;
    }

    .project-table-scroll-top::-webkit-scrollbar-thumb:hover,
    .project-table-scroll-bottom::-webkit-scrollbar-thumb:hover {
        background: #f96332;
    }

    /*
    |--------------------------------------------------------------------------
    | Firefox
    |--------------------------------------------------------------------------
    */

    .project-table-scroll-top,
    .project-table-scroll-bottom {
        scrollbar-width: auto;
    }

    /*
    |--------------------------------------------------------------------------
    | Mobile
    |--------------------------------------------------------------------------
    */

    @media (max-width: 768px) {

        #projectsTable {
            width: 1600px !important;
            min-width: 1600px !important;
        }

        .project-table-scroll-top {
            height: 16px;
        }
    }
</style>
@endpush
