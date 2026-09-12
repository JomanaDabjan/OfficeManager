<!-- ========================================================================= -->
<!-- NAVIGATION BAR (HEADER CONTAINER)                                         -->
<!-- ========================================================================= -->


<!-- ======================================================================== -->
<!-- NAVIGATION BAR                                                           -->
<!-- ========================================================================= -->

<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute bg-primary fixed-top">

    <div class="container-fluid">

        <!-- ================================================================= -->
        <!-- LEFT SECTION: DISPLAY LOGGED-IN USER NAME                         -->
        <!-- ================================================================= -->

        <div class="navbar-wrapper d-flex align-items-center">

            @auth

            <div class="user-greeting-pill d-flex align-items-center px-3 py-1" style="background: rgba(255, 255, 255, 0.15);
                       border: 1px solid rgba(255, 255, 255, 0.3);
                       border-radius: 50px;
                       backdrop-filter: blur(5px);">

                <div style="width: 32px;
                           height: 32px;
                           background: #f96332;
                           color: #ffffff;
                           border-radius: 50%;
                           display: flex;
                           align-items: center;
                           justify-content: center;
                           font-weight: 700;
                           font-size: 14px;
                           margin-right: 10px;
                           box-shadow: 0 2px 5px rgba(0,0,0,0.1);">

                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                </div>

                <span style="color: #ffffff;
                           font-weight: 600;
                           font-size: 14px;
                           letter-spacing: 0.5px;">

                    {{ Auth::user()->name }}

                </span>

            </div>

            @endauth

        </div>


        <!-- ================================================================= -->
        <!-- TOGGLE BUTTON FOR MOBILE                                          -->
        <!-- ================================================================= -->

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
            aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">

            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>

        </button>


        <!-- ================================================================= -->
        <!-- RIGHT SECTION: NAVIGATION LINKS & AUTH BUTTONS                    -->
        <!-- ================================================================= -->

        <div class="collapse navbar-collapse justify-content-end" id="navigation">

            <ul class="navbar-nav align-items-center">

                @guest

                <!-- ========================================================= -->
                <!-- LOGIN BUTTON                                              -->
                <!-- ========================================================= -->

                <li class="nav-item ml-2">

                    <a href="{{ route('login') }}"
                        class="btn btn-neutral btn-round text-orange-500 font-weight-bold px-4 py-2 shadow-sm transition-all duration-300"
                        style="background-color: #ffffff;
                               color: #ff6b00;
                               border-radius: 50px;
                               transition: all 0.3s ease;">

                        <i class="now-ui-icons users_single-02 mr-1"></i>

                        Login

                    </a>

                </li>

                @else


                <!-- ========================================================= -->
                <!-- DEADLINE NOTIFICATIONS                                    -->
                <!-- ========================================================= -->

                <li class="nav-item ml-2 deadline-notification-wrapper">

                    @php

                    $notificationCount = isset($deadlineNotifications)
                    ? $deadlineNotifications->count()
                    : 0;

                    @endphp


                    <!-- ===================================================== -->
                    <!-- NOTIFICATION BELL                                     -->
                    <!-- ===================================================== -->

                    <button type="button"
                        class="deadline-notification-button"
                        id="deadlineNotificationButton"
                        aria-label="Notifications"
                        aria-expanded="false">

                        <i class="now-ui-icons ui-1_bell-53"></i>


                        @if($notificationCount > 0)

                        <span class="deadline-notification-badge"
                            id="deadlineNotificationBadge">

                            {{ $notificationCount > 99 ? '99+' : $notificationCount }}

                        </span>

                        @endif

                    </button>


                    <!-- ===================================================== -->
                    <!-- NOTIFICATION DROPDOWN                                 -->
                    <!-- ===================================================== -->

                    <div class="deadline-notification-menu"
                        id="deadlineNotificationMenu">


                        <!-- ================================================= -->
                        <!-- NOTIFICATION HEADER                               -->
                        <!-- ================================================= -->

                        <div class="deadline-notification-header">

                            <div class="d-flex align-items-center justify-content-between">

                                <div>

                                    <div class="deadline-notification-header-title">

                                        Notifications

                                    </div>

                                    <div class="deadline-notification-header-subtitle">

                                        Projects and tasks approaching their deadline

                                    </div>

                                </div>


                                @if($notificationCount > 0)

                                <span class="deadline-notification-header-count"
                                    id="deadlineNotificationHeaderCount">

                                    {{ $notificationCount }}

                                </span>

                                @endif

                            </div>

                        </div>


                        <!-- ================================================= -->
                        <!-- NOTIFICATION ITEMS                                 -->
                        <!-- ================================================= -->

                        <div class="deadline-notification-items">

                            @if(isset($deadlineNotifications) && $deadlineNotifications->count() > 0)

                            @foreach($deadlineNotifications as $notification)

                            @php

                            $type = $notification->type ?? 'project';

                            $title = $notification->title ?? 'Deadline approaching';

                            $name = $notification->name ?? '';

                            $daysRemaining = $notification->days_remaining ?? null;

                            $url = $notification->url ?? '#';

                            $notificationKey = $type . '-' . $notification->id;


                            if ($daysRemaining === 0) {

                            $timeText = 'Due today';

                            $icon = 'now-ui-icons ui-1_time-alarm';

                            $iconBg = '#f96332';

                            } elseif ($daysRemaining === 1) {

                            $timeText = 'Due tomorrow';

                            $icon = 'now-ui-icons ui-1_time-alarm';

                            $iconBg = '#f96332';

                            } elseif ($daysRemaining > 1) {

                            $timeText = $daysRemaining . ' days remaining';

                            $icon = 'now-ui-icons ui-1_calendar-60';

                            $iconBg = '#2CA8FF';

                            } else {

                            $timeText = 'Deadline passed';

                            $icon = 'now-ui-icons ui-1_simple-remove';

                            $iconBg = '#f96332';

                            }

                            @endphp


                            <!-- ========================================= -->
                            <!-- NOTIFICATION ITEM                         -->
                            <!-- ========================================= -->

                            <div class="deadline-notification-item"
                                data-notification-key="{{ $notificationKey }}">

                                <div class="d-flex align-items-start">


                                    <!-- ================================= -->
                                    <!-- NOTIFICATION ICON                 -->
                                    <!-- ================================= -->

                                    <div class="deadline-notification-icon"
                                        style="background: {{ $iconBg }};">

                                        <i class="{{ $icon }}"
                                            style="font-size: 17px;"></i>

                                    </div>


                                    <!-- ================================= -->
                                    <!-- NOTIFICATION CONTENT              -->
                                    <!-- ================================= -->

                                    <a href="{{ $url }}"
                                        class="deadline-notification-content"
                                        style="text-decoration: none;">

                                        <div class="deadline-notification-title">

                                            {{ $title }}

                                        </div>


                                        <div class="deadline-notification-name">

                                            {{ $name }}

                                        </div>


                                        <div class="deadline-notification-time"
                                            style="color: {{ $daysRemaining !== null && $daysRemaining <= 1 ? '#f96332' : '#888888' }};">

                                            {{ $timeText }}

                                        </div>

                                    </a>


                                    <!-- ================================= -->
                                    <!-- DELETE BUTTON                     -->
                                    <!-- ================================= -->

                                    <button type="button"
                                        class="deadline-notification-delete btn btn-danger btn-icon btn-round"
                                        data-notification-key="{{ $notificationKey }}"
                                        aria-label="Delete notification"
                                        title="Delete notification"
                                        style="width: 30px;
                                               height: 30px;
                                               min-width: 30px;
                                               padding: 0;
                                               margin: 2px 4px 0 6px;
                                               display: flex;
                                               align-items: center;
                                               justify-content: center;
                                               border-radius: 50%;
                                               box-shadow: 0 3px 8px rgba(0, 0, 0, 0.12);
                                               transition: all 0.2s ease;">

                                        <i class="now-ui-icons ui-1_simple-remove"
                                            style="font-size: 11px;
                                                   font-weight: 600;
                                                   margin: 0;"></i>

                                    </button>


                                    <!-- ================================= -->
                                    <!-- ARROW                             -->
                                    <!-- ================================= -->

                                    <div class="deadline-notification-arrow">

                                        <i class="now-ui-icons arrows-1_minimal-right"></i>

                                    </div>

                                </div>

                            </div>

                            @endforeach


                            @else


                            <!-- ============================================= -->
                            <!-- EMPTY NOTIFICATIONS                          -->
                            <!-- ============================================= -->

                            <div class="deadline-notification-empty"
                                style="display: flex !important;
                                       flex-direction: column !important;
                                       align-items: center !important;
                                       justify-content: center !important;
                                       width: 100% !important;
                                       min-height: 190px !important;
                                       padding: 35px 25px !important;
                                       margin: 0 !important;
                                       text-align: center !important;
                                       visibility: visible !important;
                                       opacity: 1 !important;
                                       box-sizing: border-box !important;">


                                <!-- ========================================= -->
                                <!-- EMPTY ICON                                -->
                                <!-- ========================================= -->

                                <div class="deadline-notification-empty-icon"
                                    style="width: 60px;
                                           height: 60px;
                                           border-radius: 50%;
                                           background: rgba(249, 99, 50, 0.10);
                                           color: #f96332;
                                           display: flex !important;
                                           align-items: center;
                                           justify-content: center;
                                           margin-bottom: 15px;
                                           box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);">

                                    <i class="now-ui-icons ui-1_bell-53"
                                        style="font-size: 25px;
                                               margin: 0;
                                               color: #f96332;"></i>

                                </div>


                                <!-- ========================================= -->
                                <!-- EMPTY TITLE                               -->
                                <!-- ========================================= -->

                                <div class="deadline-notification-empty-title"
                                    style="display: block !important;
                                           font-size: 15px;
                                           font-weight: 600;
                                           color: #344675;
                                           margin-bottom: 7px;
                                           visibility: visible !important;
                                           opacity: 1 !important;">

                                    No Notifications

                                </div>


                                <!-- ========================================= -->
                                <!-- EMPTY TEXT                                -->
                                <!-- ========================================= -->

                                <div class="deadline-notification-empty-text"
                                    style="display: block !important;
                                           font-size: 12px;
                                           line-height: 1.6;
                                           color: #9a9a9a;
                                           max-width: 270px;
                                           visibility: visible !important;
                                           opacity: 1 !important;">

                                    You have no projects or tasks approaching their deadlines.

                                </div>

                            </div>

                            @endif

                        </div>


                        <!-- ================================================= -->
                        <!-- NOTIFICATION FOOTER                               -->
                        <!-- ================================================= -->

                        @if($notificationCount > 0)

                        <div class="deadline-notification-footer">

                            <span class="deadline-notification-footer-text">

                                Deadline alerts are shown automatically

                            </span>

                        </div>

                        @endif

                    </div>

                </li>


                <!-- ========================================================= -->
                <!-- LOGOUT BUTTON                                             -->
                <!-- ========================================================= -->

                @can('viewDashboard')

                <li class="nav-item ml-2">

                    <form method="POST"
                        action="{{ route('logout') }}"
                        class="m-0">

                        @csrf

                        <button type="submit"
                            class="btn btn-neutral btn-round font-weight-bold px-4 py-2 shadow-sm transition-all duration-300 logout-btn"
                            style="background-color: rgba(255, 255, 255, 0.15);
                                   color: #ffffff;
                                   border: 1px solid rgba(255, 255, 255, 0.4);
                                   border-radius: 50px;
                                   transition: all 0.3s ease;">

                            <i class="now-ui-icons media-1_button-power mr-1"></i>

                            Logout

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
