<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ==========================================================================
        1. BASIC HTML METADATA & DOCUMENT SETTINGS
        ========================================================================== -->
    <!-- Defines the character encoding for proper text rendering (UTF-8 supports Arabic & English) -->
    <meta charset="UTF-8">

    <!-- Sets a dynamic page title. If a view doesn't specify a title, it defaults to 'Report' -->
    <title>@yield('title', 'Report')</title>
</head>

<body>
    <!-- ==========================================================================
        3. MAIN PRINTABLE CONTAINER & DYNAMIC CONTENT INJECTION
        ========================================================================== -->
    <!-- Wrapper div designated for printable and PDF export scope -->
    <div id="printable-report">

        <!-- Dynamic Report Title: Changes automatically based on which view extends this layout -->
        <h2>@yield('report_title')</h2>

        <!-- Content Injection Area: Child views (like project tables) will inject their code right here -->
        @yield('content')
    </div>
</body>

</html>
