<!-- ========================================================================= -->
<!-- NAVIGATION BAR (HEADER CONTAINER)                                         -->
<!-- ========================================================================= -->
<!-- This is the top header navigation bar fixed across the dashboard layout -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute bg-primary fixed-top">
    <div class="container-fluid">

        <!-- ================================================================= -->
        <!-- LEFT SECTION: BRAND TITLE & MOBILE TOGGLE                         -->
        <!-- ================================================================= -->
        <div class="navbar-wrapper">
            <!-- Mobile sidebar toggle menu button -->
            <div class="navbar-toggle">
                <button type="button" class="navbar-toggler">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </button>
            </div>
            <!-- Brand page title displayed on the header -->
            <a class="navbar-brand" href="#pablo">Dashboard</a>
        </div>

        <!-- Toggle button for responsive mobile view collapse -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
            aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
        </button>

        <!-- ================================================================= -->
        <!-- RIGHT SECTION: NAVIGATION LINKS & AUTH BUTTONS (LOGIN / LOGOUT)     -->
        <!-- ================================================================= -->
        <div class="collapse navbar-collapse justify-content-end" id="navigation">

            <!-- Note: Search bar has been completely removed as requested -->

            <ul class="navbar-nav align-items-center">

                <!-- System Stats Icon Link -->
                <li class="nav-item">
                    <a class="nav-link" href="#pablo" title="System Stats">
                        <i class="now-ui-icons media-2_sound-wave"></i>
                        <p>
                            <span class="d-lg-none d-md-block">Stats</span>
                        </p>
                    </a>
                </li>
                @guest
                <!-- ===================================================== -->
                <!-- LOGIN BUTTON (Styled with CoreTask Orange Accent)     -->
                <!-- ===================================================== -->
                <li class="nav-item ml-2">
                    <a href="{{ route('login') }}"
                        class="btn btn-neutral btn-round text-orange-500 font-weight-bold px-4 py-2 shadow-sm transition-all duration-300"
                        style="background-color: #ffffff; color: #ff6b00; border-radius: 50px; transition: all 0.3s ease;">
                        <i class="now-ui-icons users_single-02 mr-1"></i> Login
                    </a>
                </li>
                @else
                <!-- ===================================================== -->
                <!-- LOGOUT BUTTON (Secure POST Form with Hover Effect)     -->
                <!-- ===================================================== -->
                <li class="nav-item ml-2">
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit"
                            class="btn btn-neutral btn-round font-weight-bold px-4 py-2 shadow-sm transition-all duration-300 logout-btn"
                            style="background-color: rgba(255, 255, 255, 0.15); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.4); border-radius: 50px; transition: all 0.3s ease;">
                            <i class="now-ui-icons media-1_button-power mr-1"></i> Logout
                        </button>
                    </form>
                </li>
                @endguest

            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->

