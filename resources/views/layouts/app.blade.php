<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ================================================================= -->
    <!-- META & HEAD CONFIGURATION SECTION                               -->
    <!-- ================================================================= -->
    <!-- Include core meta tags, charset, and viewport settings -->
    @include('includes.head')

    <!-- ================================================================= -->
    <!-- DYNAMIC BROWSER WINDOW TITLE                                    -->
    <!-- ================================================================= -->
    <!--
      This allows child pages to pass a custom title using '@section('title')'.
      If no title is provided by the child page, it defaults to 'System Dashboard'.
    -->
    <title>@yield('title', 'CoreTask - System Dashboard')</title>

    <!-- ================================================================= -->
    <!-- STYLESHEETS INCLUSION SECTION                                   -->
    <!-- ================================================================= -->
    <!-- Include global CSS stylesheet files -->
    @include('includes.Style')

    <!-- Allow child views to push page-specific CSS styles -->
    @stack('Style')
</head>

<body>
    <!-- ================================================================= -->
    <!-- MAIN WRAPPER CONTAINER                                          -->
    <!-- ================================================================= -->
    <div class="wrapper">

        <!-- ============================================================= -->
        <!-- SIDEBAR NAVIGATION MENU                                       -->
        <!-- ============================================================= -->
        @include('includes.Sidebar')

        <!-- ============================================================= -->
        <!-- MAIN PANEL CONTENT AREA                                       -->
        <!-- ============================================================= -->
        <div class="main-panel" id="main-panel">

            <!-- ========================================================= -->
            <!-- CONDITIONAL TOP HEADER NAVBAR                             -->
            <!-- ========================================================= -->
            <!--
              Render the header navbar only if the current route
              is NOT part of the excluded project and task routes.
            -->
            @if(!request()->routeIs('admin.project.index') && !request()->routeIs('admin.project.create') &&
            !request()->routeIs('admin.project.show') && !request()->routeIs('admin.project.edit') &&
            !request()->routeIs('admin.task.index') && !request()->routeIs('admin.task.create'))
            @include('includes.Header')
            @endif

            <!-- ========================================================= -->
            <!-- DYNAMIC PAGE CONTENT PLACEHOLDER                          -->
            <!-- ========================================================= -->
            <!-- Child views inject their main HTML content here using 'section' -->
            @yield('Main_Content')

            <!-- ========================================================= -->
            <!-- CONDITIONAL PAGE FOOTER                                   -->
            <!-- ========================================================= -->
            <!-- Render the footer only on permitted pages (matching header condition) -->
            @if(!request()->routeIs('admin.project.index') && !request()->routeIs('admin.project.create') &&
            !request()->routeIs('admin.project.show') && !request()->routeIs('admin.project.edit') &&
            !request()->routeIs('admin.task.index') && !request()->routeIs('admin.task.create'))
            @include('includes.Footer')
            @endif

        </div> <!-- End of main-panel -->

    </div> <!-- End of wrapper -->

    <!-- ================================================================= -->
    <!-- JAVASCRIPT SCRIPTS INCLUSION SECTION                              -->
    <!-- ================================================================= -->
    <!-- Include global application JavaScript files -->
    @include('includes.Script')

    <!-- Allow child views to push page-specific JavaScript scripts -->
    @stack('Script')
</body>

</html>