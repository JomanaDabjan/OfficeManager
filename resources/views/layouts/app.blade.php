<!DOCTYPE html>
<html lang="en">

<!-- Head Section -->
@include('includes.Head')

<!-- Include CSS Styles -->
@include('includes.Style')
@stack('Style')

<body>
    <div class="wrapper">
        <!-- Sidebar Navigation -->
        @include('includes.Sidebar')

        <!-- Main Panel Container -->
        <div class="main-panel" id="main-panel">
            <!-- Top Header Navbar -->
            @include('includes.Header')

            <!-- Dynamic Page Content -->
            @yield('Main_Content')

            <!-- Page Footer -->
            @include('includes.Footer')
        </div>
    </div>

    <!-- JavaScript Files and Scripts -->
    @include('includes.Script')
    @stack('Script')
</body>

</html>
