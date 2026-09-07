<!-- ========================================================================= -->
<!-- NAVIGATION BAR (HEADER CONTAINER)                                         -->
<!-- ========================================================================= -->
<!-- This is the top header navigation bar fixed across the dashboard layout -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute bg-primary fixed-top">
    <div class="container-fluid">

        <!-- LEFT SECTION: DISPLAY LOGGED-IN USER NAME -->
        <div class="navbar-wrapper d-flex align-items-center">
            @auth
            <div class="user-greeting-pill d-flex align-items-center px-3 py-1"
                style="background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 50px; backdrop-filter: blur(5px);">
                <div
                    style="width: 32px; height: 32px; background: #f96332; color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; margin-right: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <span style="color: #ffffff; font-weight: 600; font-size: 14px; letter-spacing: 0.5px;">
                    {{ Auth::user()->name }}
                </span>
            </div>
            @endauth
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
                @can('viewDashboard')
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
                @endcan
                @endguest

            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->