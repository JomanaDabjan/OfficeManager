@extends('layouts.app')

@section('Main_Content')



<!-- ========================================================================= -->
<!-- START: WELCOME MODAL SECTION                                              -->
<!-- ========================================================================= -->
@php
// Get the currently authenticated user instance
$user = auth()->user();

// Get the user name or fallback to 'User' if null
$userName = $user->name ?? 'User';

// Check if the flash session for showing the welcome modal exists
$shouldShowModal = session()->has('show_welcome_modal');
@endphp

@if($shouldShowModal)
<!-- Custom Center Screen Welcome Popup Backdrop -->
<div id="custom-welcome-modal" class="modal-backdrop-custom" style="display: flex;">
    <!-- Welcome Card Container -->
    <div class="welcome-card shadow-lg">

        <!-- Close Button to manually hide the modal -->
        <button type="button" class="close-welcome-btn" onclick="dismissWelcomeModal()">&times;</button>

        <!-- Circular Icon Container with Pulse Animation -->
        <div class="welcome-icon-container pulse-animation">
            <!-- Now UI code icon representing development -->
            <i class="now-ui-icons tech_laptop text-white"></i>
        </div>

        <!-- Text Content Section -->
        <div class="welcome-content-area">
            <!-- Dynamic Title -->
            <h4 class="welcome-title">
                Hello, {{ $userName }}!
            </h4>

            <!-- Additional Motivational / Status Message with modern pill design & top bulb icon -->
            <div class="motivational-pill">
                <i class="now-ui-icons business_bulb-63 motivational-bulb-icon"></i>
                <span>Ready to write clean code and build amazing things today?</span>
            </div>
        </div>

        <!-- Action Button for Manual Dismissal -->
        <div class="welcome-action-area">
            <button type="button" class="btn-letser-go" onclick="dismissWelcomeModal()">
                Let's Get Started
            </button>
        </div>

        <!-- Animated Progress Bar Track for Auto-dismissal -->
        <div class="welcome-progress-track">
            <div id="welcome-progress-bar" class="welcome-progress-fill"></div>
        </div>
    </div>
</div>
@endif
<!-- ========================================================================= -->
<!-- END: WELCOME MODAL SECTION                                                -->
<!-- ========================================================================= -->


<!-- Top spacing panel header for Now UI template -->
<div class="panel-header panel-header-sm"></div>

<!-- Main dashboard wrapper container -->
<div class="content">
    <!-- Row element containing statistic cards -->
    <div class="row">

        <!-- ========================================================================= -->
        <!-- CARD 1: Total Projects Metric Card                                        -->
        <!-- ========================================================================= -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats"
                style="border: none; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); background: #ffffff; border-right: 5px solid #f96332;">
                <div class="card-body text-center" style="padding: 25px;">
                    <!-- Icon wrapper with light orange background tint -->
                    <div
                        style="background: rgba(249, 99, 50, 0.1); width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                        <span style="font-size: 24px; color: #f96332; font-weight: bold;">📁</span>
                    </div>
                    <!-- Card label category -->
                    <p class="card-category"
                        style="font-size: 12px; font-weight: 700; color: #9a9a9a; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">
                        Total Projects
                    </p>
                    <!-- Dynamic variable output for total projects -->
                    <h3 class="card-title" style="font-size: 28px; font-weight: 800; color: #2c2c2c; margin: 0;">
                        {{ $totalProjects }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- CARD 2: Total Tasks Metric Card                                           -->
        <!-- ========================================================================= -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats"
                style="border: none; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); background: #ffffff; border-right: 5px solid #51cbce;">
                <div class="card-body text-center" style="padding: 25px;">
                    <!-- Icon wrapper with light cyan background tint -->
                    <div
                        style="background: rgba(81, 203, 206, 0.1); width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                        <span style="font-size: 24px; color: #51cbce; font-weight: bold;">📋</span>
                    </div>
                    <!-- Card label category -->
                    <p class="card-category"
                        style="font-size: 12px; font-weight: 700; color: #9a9a9a; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">
                        Total Tasks
                    </p>
                    <!-- Dynamic variable output for total tasks -->
                    <h3 class="card-title" style="font-size: 28px; font-weight: 800; color: #2c2c2c; margin: 0;">
                        {{ $totalTasks }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- CARD 3: Total Employees Metric Card                                       -->
        <!-- ========================================================================= -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats"
                style="border: none; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); background: #ffffff; border-right: 5px solid #ef8157;">
                <div class="card-body text-center" style="padding: 25px;">
                    <!-- Icon wrapper with light coral background tint -->
                    <div
                        style="background: rgba(239, 129, 87, 0.1); width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                        <span style="font-size: 24px; color: #ef8157; font-weight: bold;">👥</span>
                    </div>
                    <!-- Card label category -->
                    <p class="card-category"
                        style="font-size: 12px; font-weight: 700; color: #9a9a9a; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">
                        Total Employees
                    </p>
                    <!-- Dynamic variable output for total employees -->
                    <h3 class="card-title" style="font-size: 28px; font-weight: 800; color: #2c2c2c; margin: 0;">
                        {{ $totalEmployees }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- CARD 4: Total Project Managers Metric Card                                -->
        <!-- ========================================================================= -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats"
                style="border: none; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); background: #ffffff; border-right: 5px solid #fbc658;">
                <div class="card-body text-center" style="padding: 25px;">
                    <!-- Icon wrapper with light yellow background tint -->
                    <div
                        style="background: rgba(251, 198, 88, 0.1); width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                        <span style="font-size: 24px; color: #fbc658; font-weight: bold;">👔</span>
                    </div>
                    <!-- Card label category -->
                    <p class="card-category"
                        style="font-size: 12px; font-weight: 700; color: #9a9a9a; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">
                        Project Managers
                    </p>
                    <!-- Dynamic variable output for total managers -->
                    <h3 class="card-title" style="font-size: 28px; font-weight: 800; color: #2c2c2c; margin: 0;">
                        {{ $totalManagers }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- START: Task Status Analytics Chart Section                                -->
    <!-- ========================================================================= -->
    <div class="row">
        <div class="col-md-12">
            <div class="card"
                style="border: none; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); background: #ffffff;">

                <!-- Card Header Title -->
                <div class="card-header" style="border-bottom: 1px solid #eee; padding: 20px 25px;">
                    <h4 class="card-title" style="font-size: 18px; font-weight: 700; color: #2c2c2c; margin: 0;">
                        Task Status Analytics
                    </h4>
                </div>

                <!-- Card Body containing the chart canvas element -->
                <div class="card-body" style="height: 350px; position: relative; padding: 20px;">
                    <canvas id="tasksChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- ========================================================================= -->
    <!-- END: Task Status Analytics Chart Section                                  -->
    <!-- ========================================================================= -->

</div>
@endsection
