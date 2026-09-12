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
       SIDEBAR BASE
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
        transition:
            width 0.35s cubic-bezier(0.4, 0, 0.2, 1),
            transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        box-sizing: border-box !important;

        /* إخفاء شريط التمرير (Scrollbar) لمتصفحات فايرفوكس */
        scrollbar-width: none;
        /* إخفاء شريط التمرير لمتصفحات إنترنت إكسبلورر وإيدج القديم */
        -ms-overflow-style: none;
    }

    /* إخفاء شريط التمرير لمتصفحات كروم وسفاري وبريف */
    .sidebar::-webkit-scrollbar {
        display: none;
        width: 0;
        height: 0;
    }

    .sidebar:not(.sidebar-collapsed) {
        width: 260px !important;
    }

    .sidebar.sidebar-collapsed {
        width: 80px !important;
    }

    /* ===================================================================== */
    /* BUBBLE LOGO DESIGN (TECH COMPANY STYLE)                               */
    /* ===================================================================== */

    .sidebar .logo {
        position: relative;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        height: 70px !important;
        min-height: 70px !important;
        padding: 0 15px !important;
        margin: 0 !important;
        text-align: center !important;
        transition: all 0.35s ease;
    }

    /* تصميم الفقاعة (Bubble / Pill) الحديث المناسب لشركات البرمجة */
    .sidebar .logo-bubble {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0.08) 100%);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #ffffff !important;
        font-weight: 700;
        letter-spacing: 1px;
        border-radius: 50px;
        /* شكل فقاعة دائرية الأطراف */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(5px);
        transition: all 0.3s ease;
    }

    /* قياسات فقاعة الشعار الكامل */
    .sidebar .logo-normal .logo-bubble {
        padding: 8px 22px;
        font-size: 16px;
    }

    /* قياسات فقاعة الشعار المصغر (CT) */
    .sidebar .logo-mini .logo-bubble {
        width: 42px;
        height: 42px;
        font-size: 15px;
        border-radius: 50%;
        /* دائرة كاملة للحرفين المختصرين */
        padding: 0;
    }

    /* تأثير خفيف عند المرور بالفأرة */
    .sidebar .logo:hover .logo-bubble {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.28) 0%, rgba(255, 255, 255, 0.15) 100%);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        transform: translateY(-1px);
    }

    .sidebar .logo-normal {
        display: flex;
        align-items: center;
        justify-content: center;
        transition:
            opacity 0.2s ease,
            transform 0.3s ease;
    }

    .sidebar .logo-mini {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 100% !important;
        height: 100% !important;
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        opacity: 0 !important;
        visibility: hidden !important;
        transition:
            opacity 0.2s ease,
            transform 0.3s ease;
    }

    /* Collapsed Logo Adjustments */
    .sidebar.sidebar-collapsed .logo-normal {
        display: none !important;
        opacity: 0 !important;
        visibility: hidden !important;
    }

    .sidebar.sidebar-collapsed .logo-mini {
        opacity: 1 !important;
        visibility: visible !important;
    }

    /* ===================================================================== */
    /* SIDEBAR NAVIGATION                                                    */
    /* ===================================================================== */

    .sidebar .nav {
        padding-top: 25px !important;
    }

    .sidebar .nav p {
        font-weight: 500;
        font-size: 14px;
        transition:
            opacity 0.2s ease,
            width 0.3s ease,
            margin 0.3s ease;
        white-space: nowrap;
    }

    .sidebar .nav li a {
        transition:
            padding 0.35s ease,
            margin 0.35s ease,
            justify-content 0.35s ease;
    }

    .sidebar .nav li a i {
        transition:
            margin 0.35s ease,
            transform 0.35s ease;
    }

    /* ===================================================================== */
    /* COLLAPSED SIDEBAR WRAPPER & NAV                                       */
    /* ===================================================================== */

    .sidebar.sidebar-collapsed .sidebar-wrapper {
        width: 80px !important;
        margin-top: 0 !important;
        padding-top: 0 !important;
    }

    .sidebar.sidebar-collapsed .sidebar-wrapper .nav {
        margin-top: 0 !important;
        padding-top: 25px !important;
    }

    .sidebar.sidebar-collapsed .nav li a p {
        display: none !important;
        opacity: 0 !important;
        visibility: hidden !important;
        width: 0 !important;
        max-width: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden !important;
    }

    .sidebar.sidebar-collapsed .nav li>a {
        width: 64px !important;
        min-width: 64px !important;
        height: 46px !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        margin-left: auto !important;
        margin-right: auto !important;
        margin-bottom: 8px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-sizing: border-box !important;
        position: relative !important;
    }

    .sidebar.sidebar-collapsed .nav li>a i {
        margin-left: 0 !important;
        margin-right: 0 !important;
        font-size: 20px !important;
        transform: none !important;
    }

    /* ===================================================================== */
    /* COLLAPSE BUTTON                                                       */
    /* ===================================================================== */

    .sidebar-collapse-btn {
        position: absolute;
        top: 35px;
        right: -13px;
        transform: translateY(-50%);
        width: 28px;
        height: 28px;
        border: none;
        border-radius: 50%;
        background: #ffffff;
        color: #f96332;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        margin: 0;
        cursor: pointer;
        z-index: 1001;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.18);
        transition:
            background 0.25s ease,
            color 0.25s ease,
            transform 0.35s cubic-bezier(0.4, 0, 0.2, 1),
            box-shadow 0.25s ease;
    }

    .sidebar-collapse-btn:hover {
        background: #f96332;
        color: #ffffff;
        box-shadow: 0 5px 15px rgba(249, 99, 50, 0.35);
        transform: translateY(-50%) scale(1.08);
    }

    .sidebar-collapse-btn:focus {
        outline: none !important;
        box-shadow: 0 5px 15px rgba(249, 99, 50, 0.35);
    }

    .sidebar-collapse-btn i {
        font-size: 15px;
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sidebar.sidebar-collapsed .sidebar-collapse-btn {
        right: -13px;
    }

    .sidebar.sidebar-collapsed .sidebar-collapse-btn i {
        transform: rotate(180deg);
    }

    /* ===================================================================== */
    /* MAIN PANEL (FIXED RESPONSIVE LAYOUT)                                  */
    /* ===================================================================== */

    .main-panel {
        position: relative !important;
        float: right !important;
        width: calc(100% - 260px) !important;
        max-width: calc(100% - 260px) !important;
        min-height: 100vh !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        box-sizing: border-box !important;
        transition: width 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: visible !important;
    }

    .sidebar.sidebar-collapsed~.main-panel,
    .main-panel.sidebar-collapsed-panel {
        width: calc(100% - 80px) !important;
        max-width: calc(100% - 80px) !important;
    }

    @media (min-width: 992px) {
        .main-panel.sidebar-collapsed-panel {
            width: calc(100% - 80px) !important;
            max-width: calc(100% - 80px) !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
    }

    /* ===================================================================== */
    /* STYLES FOR NAV ITEMS, HOVER & ACTIVE STATES                          */
    /* ===================================================================== */

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

    .sidebar.sidebar-collapsed .nav li.active>a {
        width: 64px !important;
        min-width: 64px !important;
        height: 46px !important;
        margin-left: auto !important;
        margin-right: auto !important;
        margin-bottom: 8px !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 0.35rem !important;
        box-sizing: border-box !important;
    }

    .sidebar.sidebar-collapsed .nav li.active>a i {
        margin-left: 0 !important;
        margin-right: 0 !important;
        font-size: 20px !important;
        transform: none !important;
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

    /* ===================================================================== */
    /* TOOLTIP FOR ICONS IN COLLAPSED MODE                                  */
    /* ===================================================================== */

    .sidebar.sidebar-collapsed .nav li a {
        position: relative;
    }

    .sidebar.sidebar-collapsed .nav li a::after {
        content: attr(data-title);
        position: absolute;
        left: 70px;
        top: 50%;
        transform: translateY(-50%) translateX(-8px);
        background: #333333;
        color: #ffffff;
        padding: 7px 11px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.2s ease, transform 0.2s ease;
        z-index: 99999;
    }

    .sidebar.sidebar-collapsed .nav li a:hover::after {
        opacity: 1;
        visibility: visible;
        transform: translateY(-50%) translateX(0);
    }

    /* ===================================================================== */
    /* MOBILE BEHAVIOR                                                       */
    /* ===================================================================== */

    @media (max-width: 991px) {
        .sidebar-collapse-btn {
            display: none;
        }

        .sidebar,
        .sidebar.sidebar-collapsed {
            width: 260px !important;
        }

        .sidebar.sidebar-collapsed .logo {
            width: 260px !important;
            height: 70px !important;
            min-height: 70px !important;
            padding: 0 15px !important;
            display: flex !important;
            text-align: left !important;
        }

        .sidebar.sidebar-collapsed .logo-normal {
            display: flex !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        .sidebar.sidebar-collapsed .logo-mini {
            display: none !important;
        }

        .sidebar.sidebar-collapsed .nav li a p {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            width: auto !important;
            max-width: none !important;
            margin: initial !important;
            padding: initial !important;
            overflow: visible !important;
        }

        .sidebar.sidebar-collapsed .nav li a {
            width: auto !important;
            min-width: 0 !important;
            height: auto !important;
            justify-content: initial !important;
            padding-left: initial !important;
            padding-right: initial !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .sidebar.sidebar-collapsed .nav li a i {
            margin-right: initial !important;
            margin-left: initial !important;
            transform: none !important;
            font-size: initial !important;
        }

        .main-panel,
        .main-panel.sidebar-collapsed-panel,
        .sidebar.sidebar-collapsed~.main-panel {
            width: 100% !important;
            max-width: 100% !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
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

    /*
     * IMPORTANT:
     *
     * These rules are for MANAGEMENT tables.
     * The Project Report rules are scoped separately below.
     *
     * Therefore the management Project/Task tables can remain
     * inside the available screen width.
     */

    #projectsTable,
    #tasksTable {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;

        table-layout: fixed !important;

        border-collapse: collapse !important;

        border: 2px solid #e3e3e3 !important;

        background-color: #ffffff !important;

        box-shadow:
            0 4px 6px rgba(0, 0, 0, 0.04);

        margin-bottom: 20px;

        box-sizing: border-box !important;
    }


    /* ==========================================================================
       TABLE HEADER - ONE CONTINUOUS GRADIENT
       ========================================================================== */

    #projectsTable thead,
    #tasksTable thead,
    #teamsTable thead,
    .custom-table-header {
        background:
            linear-gradient(135deg,
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

        border:
            1px solid rgba(255, 255, 255, 0.18) !important;

        white-space: normal !important;

        vertical-align: middle !important;

        word-break: break-word !important;

        overflow-wrap: anywhere !important;

        min-width: 0 !important;

        box-sizing: border-box !important;
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
        border:
            1px solid #e9ecef !important;

        padding: 12px 8px !important;

        font-size: 13px !important;

        vertical-align: middle !important;

        color: #3c4858 !important;

        text-align: center !important;

        white-space: normal !important;

        word-break: break-word !important;

        overflow-wrap: anywhere !important;

        min-width: 0 !important;

        box-sizing: border-box !important;
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
        max-width: 100%;
        white-space: normal;
        word-break: break-word;
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
        box-shadow:
            0 0 8px rgba(249, 99, 50, 0.4) !important;
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

        box-shadow:
            0 10px 30px 0px rgba(0, 0, 0, 0.08) !important;

        border:
            1px solid #eaeaea !important;

        overflow: hidden;

        background-color: #ffffff !important;

        margin-top: 10px;

        margin-bottom: 30px;
    }

    .custom-card-header {
        background:
            linear-gradient(135deg,
                #ff8a65 0%,
                #ff7043 100%) !important;

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

        box-shadow:
            0 0 8px rgba(249, 99, 50, 0.25) !important;
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

        box-shadow:
            0 4px 15px rgba(255, 107, 0, 0.4);

        transform: translateY(-1px);
    }

    .navbar-nav .logout-btn:hover {
        background-color: #ef4444 !important;
        border-color: #ef4444 !important;
        color: #ffffff !important;

        box-shadow:
            0 4px 15px rgba(239, 68, 68, 0.4);

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

        box-shadow:
            0 12px 30px rgba(249, 99, 50, 0.6) !important;
    }


    /* ========================================================================== */
    /* PROJECT REPORT TABLE SCROLL SYSTEM                                        */
    /* ========================================================================== */

    /*
     * IMPORTANT:
     *
     * Everything inside this section belongs ONLY to Project Report.
     *
     * The selector starts with #printable-report so these rules
     * cannot affect Project Management.
     */


    /* ==========================================================================
       PROJECT REPORT CONTAINER
       ========================================================================== */

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


    /* ==========================================================================
       TOP HORIZONTAL SCROLLBAR
       ========================================================================== */

    #printable-report .project-table-scroll-top {
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

    #printable-report .project-table-scroll-top-inner {
        height: 1px;
        min-width: 1600px;
    }


    /* ==========================================================================
       BOTTOM HORIZONTAL SCROLLBAR
       ========================================================================== */

    #printable-report .project-table-scroll-bottom {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        margin: 0;
        padding: 0;
    }


    /* ==========================================================================
       ACTUAL PROJECT REPORT TABLE CONTAINER
       ========================================================================== */

    #printable-report .project-table-scroll {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        margin: 0;
        padding: 0;

        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
    }


    /* ==========================================================================
       HIDE THE NATIVE TABLE SCROLLBAR
       ========================================================================== */

    #printable-report .project-table-scroll::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }


    /* ==========================================================================
       HIDE ANY INNER NATIVE SCROLLBAR
       ========================================================================== */

    #printable-report .project-table-scroll * {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    #printable-report .project-table-scroll *::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }


    /* ==========================================================================
       DataTables SCROLL CONTAINERS
       ========================================================================== */

    #printable-report .project-table-scroll .dataTables_wrapper,
    #printable-report .project-table-scroll .dataTables_scroll,
    #printable-report .project-table-scroll .dataTables_scrollBody {
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
    }

    #printable-report .project-table-scroll .dataTables_wrapper::-webkit-scrollbar,
    #printable-report .project-table-scroll .dataTables_scroll::-webkit-scrollbar,
    #printable-report .project-table-scroll .dataTables_scrollBody::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }


    /* ==========================================================================
       PROJECT REPORT TABLE WIDTH
       ========================================================================== */

    /*
     * IMPORTANT FIX
     *
     * 1600px is now applied ONLY to the Project Report.
     *
     * Project Management also uses #projectsTable,
     * therefore we must scope this rule to #printable-report.
     */

    #printable-report #projectsTable {
        width: 1600px !important;
        min-width: 1600px !important;
        max-width: none !important;

        table-layout: auto !important;

        margin-bottom: 0 !important;
    }


    /* ==========================================================================
       PROJECT REPORT TABLE CELLS
       ========================================================================== */

    #printable-report #projectsTable th,
    #printable-report #projectsTable td {
        vertical-align: middle;
    }


    /* ==========================================================================
       PROJECT REPORT COLUMN 1
       ========================================================================== */

    #printable-report #projectsTable th:nth-child(1),
    #printable-report #projectsTable td:nth-child(1) {
        min-width: 160px;
        width: 160px;
    }


    /* ==========================================================================
       PROJECT REPORT COLUMN 2
       ========================================================================== */

    #printable-report #projectsTable th:nth-child(2),
    #printable-report #projectsTable td:nth-child(2) {
        min-width: 170px;
        width: 170px;
    }


    /* ==========================================================================
       PROJECT REPORT COLUMN 3
       ========================================================================== */

    #printable-report #projectsTable th:nth-child(3),
    #printable-report #projectsTable td:nth-child(3) {
        min-width: 150px;
        width: 150px;
    }


    /* ==========================================================================
       PROJECT REPORT COLUMN 4
       ========================================================================== */

    #printable-report #projectsTable th:nth-child(4),
    #printable-report #projectsTable td:nth-child(4) {
        min-width: 100px;
        width: 100px;
    }


    /* ==========================================================================
       PROJECT REPORT COLUMN 5
       ========================================================================== */

    #printable-report #projectsTable th:nth-child(5),
    #printable-report #projectsTable td:nth-child(5) {
        min-width: 100px;
        width: 100px;
    }


    /* ==========================================================================
       PROJECT REPORT COLUMN 6
       ========================================================================== */

    #printable-report #projectsTable th:nth-child(6),
    #printable-report #projectsTable td:nth-child(6) {
        min-width: 120px;
        width: 120px;
    }


    /* ==========================================================================
       PROJECT REPORT COLUMN 7
       ========================================================================== */

    #printable-report #projectsTable th:nth-child(7),
    #printable-report #projectsTable td:nth-child(7) {
        min-width: 130px;
        width: 130px;
    }


    /* ==========================================================================
       PROJECT REPORT COLUMN 8
       ========================================================================== */

    #printable-report #projectsTable th:nth-child(8),
    #printable-report #projectsTable td:nth-child(8) {
        min-width: 300px;
        width: 300px;
    }


    /* ==========================================================================
       PROJECT REPORT COLUMN 9
       ========================================================================== */

    #printable-report #projectsTable th:nth-child(9),
    #printable-report #projectsTable td:nth-child(9) {
        min-width: 150px;
        width: 150px;
    }


    /* ==========================================================================
       Keep Project Report scrollbars visible and clean
       ========================================================================== */

    #printable-report .project-table-scroll-top::-webkit-scrollbar,
    #printable-report .project-table-scroll-bottom::-webkit-scrollbar {
        height: 10px;
    }

    #printable-report .project-table-scroll-top::-webkit-scrollbar-track,
    #printable-report .project-table-scroll-bottom::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    #printable-report .project-table-scroll-top::-webkit-scrollbar-thumb,
    #printable-report .project-table-scroll-bottom::-webkit-scrollbar-thumb {
        background: #ff9a7a;
        border-radius: 10px;
    }

    #printable-report .project-table-scroll-top::-webkit-scrollbar-thumb:hover,
    #printable-report .project-table-scroll-bottom::-webkit-scrollbar-thumb:hover {
        background: #f96332;
    }


    /* ==========================================================================
       Firefox
       ========================================================================== */

    #printable-report .project-table-scroll-top,
    #printable-report .project-table-scroll-bottom {
        scrollbar-width: auto;
    }


    /* ==========================================================================
       Mobile
       ========================================================================== */

    @media (max-width: 768px) {

        #printable-report #projectsTable {
            width: 1600px !important;
            min-width: 1600px !important;
        }

        #printable-report .project-table-scroll-top {
            height: 16px;
        }

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

            font-family:
                'Inter',
                'Montserrat',
                Arial,
                sans-serif !important;

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

            background: #ff7043 !important;

            color: #ffffff !important;

            border:
                1px solid #e05a35 !important;

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

            border:
                1px solid #d5dbe0 !important;

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
            border:
                1px solid #d5dbe0 !important;

            border-collapse: collapse !important;
        }


        /* Header border color */

        #temporaryPrintContainer table thead th {
            border:
                1px solid #e05a35 !important;
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

            border:
                1px solid #1565c0 !important;

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
    | Project Report
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | The selectors below are scoped to #printable-report.
    |
    | Therefore they affect ONLY the Project Report table and
    | cannot force Project Management to become 1600px wide.
    |
    |--------------------------------------------------------------------------
    */


    /* ---------------------------------------------------------------------- */
    /* PROJECT REPORT CONTAINER                                               */
    /* ---------------------------------------------------------------------- */

    #printable-report {
        width: 100% !important;
        max-width: 100% !important;
        overflow: hidden !important;
        box-sizing: border-box !important;
    }

    #printable-report .card-body {
        width: 100% !important;
        max-width: 100% !important;
        overflow: hidden !important;
        box-sizing: border-box !important;
    }


    /* ---------------------------------------------------------------------- */
    /* TOP HORIZONTAL SCROLLBAR                                               */
    /* ---------------------------------------------------------------------- */

    #printable-report .project-table-scroll-top {
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

    #printable-report .project-table-scroll-top-inner {
        height: 1px;
        min-width: 1600px;
    }


    /* ---------------------------------------------------------------------- */
    /* BOTTOM HORIZONTAL SCROLLBAR                                            */
    /* ---------------------------------------------------------------------- */

    #printable-report .project-table-scroll-bottom {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        margin: 0;
        padding: 0;
    }


    /* ---------------------------------------------------------------------- */
    /* ACTUAL TABLE CONTAINER                                                 */
    /* ---------------------------------------------------------------------- */

    #printable-report .project-table-scroll {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        margin: 0;
        padding: 0;
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
    }


    /* ---------------------------------------------------------------------- */
    /* HIDE NATIVE TABLE SCROLLBAR                                            */
    /* ---------------------------------------------------------------------- */

    #printable-report .project-table-scroll::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }


    /* ---------------------------------------------------------------------- */
    /* HIDE INNER NATIVE SCROLLBARS                                           */
    /* ---------------------------------------------------------------------- */

    #printable-report .project-table-scroll * {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    #printable-report .project-table-scroll *::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }


    /* ---------------------------------------------------------------------- */
    /* DATATABLES SCROLL CONTAINERS                                           */
    /* ---------------------------------------------------------------------- */

    #printable-report .project-table-scroll .dataTables_wrapper,
    #printable-report .project-table-scroll .dataTables_scroll,
    #printable-report .project-table-scroll .dataTables_scrollBody {
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
    }

    #printable-report .project-table-scroll .dataTables_wrapper::-webkit-scrollbar,
    #printable-report .project-table-scroll .dataTables_scroll::-webkit-scrollbar,
    #printable-report .project-table-scroll .dataTables_scrollBody::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }


    /* ---------------------------------------------------------------------- */
    /* PROJECT REPORT TABLE WIDTH                                             */
    /* ---------------------------------------------------------------------- */

    #printable-report #projectsTable {
        width: 1600px !important;
        min-width: 1600px !important;
        max-width: none !important;
        table-layout: auto !important;
        margin-bottom: 0 !important;
    }


    /* ---------------------------------------------------------------------- */
    /* PROJECT REPORT TABLE CELLS                                             */
    /* ---------------------------------------------------------------------- */

    #printable-report #projectsTable th,
    #printable-report #projectsTable td {
        vertical-align: middle;
    }


    /* ---------------------------------------------------------------------- */
    /* PROJECT REPORT COLUMNS                                                 */
    /* ---------------------------------------------------------------------- */

    #printable-report #projectsTable th:nth-child(1),
    #printable-report #projectsTable td:nth-child(1) {
        min-width: 160px;
        width: 160px;
    }

    #printable-report #projectsTable th:nth-child(2),
    #printable-report #projectsTable td:nth-child(2) {
        min-width: 170px;
        width: 170px;
    }

    #printable-report #projectsTable th:nth-child(3),
    #printable-report #projectsTable td:nth-child(3) {
        min-width: 150px;
        width: 150px;
    }

    #printable-report #projectsTable th:nth-child(4),
    #printable-report #projectsTable td:nth-child(4) {
        min-width: 100px;
        width: 100px;
    }

    #printable-report #projectsTable th:nth-child(5),
    #printable-report #projectsTable td:nth-child(5) {
        min-width: 100px;
        width: 100px;
    }

    #printable-report #projectsTable th:nth-child(6),
    #printable-report #projectsTable td:nth-child(6) {
        min-width: 120px;
        width: 120px;
    }

    #printable-report #projectsTable th:nth-child(7),
    #printable-report #projectsTable td:nth-child(7) {
        min-width: 130px;
        width: 130px;
    }

    #printable-report #projectsTable th:nth-child(8),
    #printable-report #projectsTable td:nth-child(8) {
        min-width: 300px;
        width: 300px;
    }

    #printable-report #projectsTable th:nth-child(9),
    #printable-report #projectsTable td:nth-child(9) {
        min-width: 150px;
        width: 150px;
    }


    /* ---------------------------------------------------------------------- */
    /* KEEP REPORT SCROLLBAR VISIBLE                                         */
    /* ---------------------------------------------------------------------- */

    #printable-report .project-table-scroll-top::-webkit-scrollbar,
    #printable-report .project-table-scroll-bottom::-webkit-scrollbar {
        height: 10px;
    }

    #printable-report .project-table-scroll-top::-webkit-scrollbar-track,
    #printable-report .project-table-scroll-bottom::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    #printable-report .project-table-scroll-top::-webkit-scrollbar-thumb,
    #printable-report .project-table-scroll-bottom::-webkit-scrollbar-thumb {
        background: #ff9a7a;
        border-radius: 10px;
    }

    #printable-report .project-table-scroll-top::-webkit-scrollbar-thumb:hover,
    #printable-report .project-table-scroll-bottom::-webkit-scrollbar-thumb:hover {
        background: #f96332;
    }


    /* ---------------------------------------------------------------------- */
    /* FIREFOX                                                               */
    /* ---------------------------------------------------------------------- */

    #printable-report .project-table-scroll-top,
    #printable-report .project-table-scroll-bottom {
        scrollbar-width: auto;
    }


    /* ---------------------------------------------------------------------- */
    /* MOBILE                                                                */
    /* ---------------------------------------------------------------------- */

    @media (max-width: 768px) {

        #printable-report #projectsTable {
            width: 1600px !important;
            min-width: 1600px !important;
        }

        #printable-report .project-table-scroll-top {
            height: 16px;
        }

    }
</style>


<style>
    /* ===================================================================== */
    /* DEADLINE NOTIFICATION DROPDOWN                                        */
    /* ===================================================================== */

    .deadline-notification-wrapper {
        position: relative;
    }

    .deadline-notification-button {
        width: 46px;
        height: 46px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 50%;
        color: #ffffff;
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        cursor: pointer;
        outline: none !important;
    }

    .deadline-notification-button:hover,
    .deadline-notification-button:focus {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff;
        outline: none !important;
        box-shadow: none !important;
    }

    .deadline-notification-button i {
        font-size: 20px;
    }

    .deadline-notification-badge {
        position: absolute;
        top: -3px;
        right: -3px;
        min-width: 20px;
        height: 20px;
        padding: 0 5px;
        background: #f96332;
        color: #ffffff;
        border: 2px solid #ffffff;
        border-radius: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 700;
        line-height: 1;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.18);
    }


    /* ===================================================================== */
    /* NOTIFICATION MENU                                                     */
    /* ===================================================================== */

    .deadline-notification-menu {
        position: fixed !important;
        top: 78px !important;
        right: 30px !important;
        left: auto !important;
        width: 380px;
        max-width: calc(100vw - 30px);
        padding: 0;
        margin: 0 !important;
        border: none;
        border-radius: 14px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.18);
        z-index: 999999 !important;

        /*
     * Hidden by default.
     * JavaScript adds .show when the bell is clicked.
     */
        display: none !important;
        visibility: hidden;
        opacity: 0;
        pointer-events: none;

        transition:
            opacity 0.2s ease,
            visibility 0.2s ease;
    }


    /* ===================================================================== */
    /* OPEN NOTIFICATION MENU                                                */
    /* ===================================================================== */

    .deadline-notification-menu.show {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        pointer-events: auto !important;
    }


    /* ===================================================================== */
    /* NOTIFICATION HEADER                                                   */
    /* ===================================================================== */

    .deadline-notification-header {
        padding: 18px 20px;
        background: linear-gradient(135deg, #f96332 0%, #ff8a5b 100%);
        color: #ffffff;
    }

    .deadline-notification-header-title {
        font-size: 16px;
        font-weight: 700;
        line-height: 1.3;
    }

    .deadline-notification-header-subtitle {
        font-size: 12px;
        opacity: 0.9;
        margin-top: 3px;
    }

    .deadline-notification-header-count {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 50px;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 700;
    }


    /* ===================================================================== */
    /* NOTIFICATION ITEMS                                                    */
    /* ===================================================================== */

    .deadline-notification-items {
        max-height: 420px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .deadline-notification-item {
        display: block;
        padding: 14px 18px;
        border-bottom: 1px solid #f1f1f1;
        white-space: normal;
        text-decoration: none !important;
        transition: background-color 0.2s ease;
        background: #ffffff;
    }

    .deadline-notification-item:hover {
        background: #fafafa;
    }

    .deadline-notification-item:last-child {
        border-bottom: none;
    }


    /* ===================================================================== */
    /* NOTIFICATION ICON                                                     */
    /* ===================================================================== */

    .deadline-notification-icon {
        min-width: 40px;
        width: 40px;
        height: 40px;
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.12);
    }


    /* ===================================================================== */
    /* NOTIFICATION CONTENT                                                  */
    /* ===================================================================== */

    .deadline-notification-content {
        min-width: 0;
        flex: 1;
    }

    .deadline-notification-title {
        font-size: 13px;
        font-weight: 700;
        color: #333333;
        margin-bottom: 3px;
    }

    .deadline-notification-name {
        font-size: 13px;
        color: #555555;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .deadline-notification-time {
        font-size: 11px;
        margin-top: 4px;
        font-weight: 600;
    }

    .deadline-notification-arrow {
        margin-left: 8px;
        color: #aaaaaa;
        padding-top: 10px;
    }


    /* ===================================================================== */
    /* EMPTY NOTIFICATIONS                                                   */
    /* ===================================================================== */

    .deadline-notification-empty {
        padding: 38px 20px;
        text-align: center;
    }

    .deadline-notification-empty-icon {
        width: 58px;
        height: 58px;
        margin: 0 auto 14px;
        background: rgba(249, 99, 50, 0.1);
        color: #f96332;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .deadline-notification-empty-title {
        font-size: 14px;
        font-weight: 700;
        color: #333333;
        margin-bottom: 5px;
    }

    .deadline-notification-empty-text {
        font-size: 12px;
        color: #999999;
    }


    /* ===================================================================== */
    /* NOTIFICATION FOOTER                                                   */
    /* ===================================================================== */

    .deadline-notification-footer {
        padding: 10px 15px;
        background: #fafafa;
        border-top: 1px solid #eeeeee;
        text-align: center;
    }

    .deadline-notification-footer-text {
        font-size: 11px;
        color: #999999;
    }


    /* ===================================================================== */
    /* PREVENT HORIZONTAL OVERFLOW                                           */
    /* ===================================================================== */

    html,
    body {
        overflow-x: hidden !important;
    }


    /* ===================================================================== */
    /* MOBILE                                                               */
    /* ===================================================================== */

    @media (max-width: 767px) {

        .deadline-notification-menu {
            top: 70px !important;
            right: 15px !important;
            width: calc(100vw - 30px);
            max-width: none;
        }
    }
</style>


@endpush
