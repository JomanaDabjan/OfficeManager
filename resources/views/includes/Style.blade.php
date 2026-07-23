@push('Style')

<style>
    /* Apply modern font family across the entire dashboard */
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

    /* Improve text rendering smoothness */
    body {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
    }

    /* Refine sidebar typography for a cleaner look */
    .sidebar .nav p {
        font-weight: 500;
        font-size: 14px;
    }

    /* Refine dropdown sub-menu items typography */
    .sidebar .collapse .nav a {
        font-size: 13px;
        padding: 8px 15px 8px 50px;
    }

    /* Active state styling for main sidebar items */
    .sidebar .nav li.active>a {
        background: linear-gradient(0deg, #ff8a65 0%, #ff7043 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 20px 0px rgba(0, 0, 0, 0.14), 0 7px 10px -5px rgba(255, 112, 67, 0.4);
        border-radius: 0.35rem;
    }

    /* Active state styling for sub-menu (dropdown) items */
    .sidebar .collapse .nav li.active>a {
        background-color: rgba(255, 255, 255, 0.15) !important;
        color: #ffffff !important;
        font-weight: 600;
        border-radius: 0.25rem;
    }

    /* Hover effect for sidebar links for better interactivity */
    .sidebar .nav li>a:hover {
        background-color: rgba(255, 255, 255, 0.08);
        border-radius: 0.35rem;
    }
</style>
@endpush
