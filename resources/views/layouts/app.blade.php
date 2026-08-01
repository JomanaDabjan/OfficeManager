<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Head Section -->
    @include('includes.head')

    <!-- Include CSS Styles -->
    @include('includes.Style')
    @stack('Style')
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar Navigation -->
        @include('includes.Sidebar')

        <!-- Main Panel Container -->
        <div class="main-panel" id="main-panel">
            <!-- Top Header Navbar -->
            @if(!request()->routeIs('admin.project.index') && !request()->routeIs('admin.project.create') &&
            !request()->routeIs('admin.project.show') && !request()->routeIs('admin.project.edit') &&
            !request()->routeIs('admin.task.index') && !request()->routeIs('admin.task.create'))
            @include('includes.Header')
            @endif

            <!-- Dynamic Page Content -->
            @yield('Main_Content')

            <!-- Page Footer -->
            @if(!request()->routeIs('admin.project.index') && !request()->routeIs('admin.project.create') &&
            !request()->routeIs('admin.project.show') && !request()->routeIs('admin.project.edit') &&
            !request()->routeIs('admin.task.index') && !request()->routeIs('admin.task.create'))
            @include('includes.Footer')
            @endif
        </div>
    </div>

    <!-- JavaScript Files and Scripts -->
    @include('includes.Script')
    @stack('Script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
