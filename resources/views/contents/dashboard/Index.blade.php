@extends('layouts.app')

@section('Main_Content')

<!-- ========================================================================= -->
<!-- PANEL HEADER (Now UI Dashboard Style Spacing & Alignment)                 -->
<!-- ========================================================================= -->
<div class="panel-header panel-header-sm"></div>

<!-- ========================================================================= -->
<!-- START: WELCOME MODAL SECTION                                             -->
<!-- ========================================================================= -->
@php
$user = auth()->user();
$userName = $user->name ?? 'User';
$shouldShowModal = session()->has('show_welcome_modal');
@endphp

@if($shouldShowModal)
<!-- Fullscreen Dark & Warm Modal Overlay -->
<div id="custom-welcome-modal"
    style="position: fixed !important; top: 0 !important; left: 0 !important; width: 100vw !important; height: 100vh !important; background: rgba(10, 10, 13, 0.88) !important; backdrop-filter: blur(8px) !important; z-index: 999999 !important; display: flex !important; align-items: center !important; justify-content: center !important; animation: fadeInModal 0.4s cubic-bezier(0.16, 1, 0.3, 1);">

    <!-- Modal Card Container -->
    <div
        style="width: 100% !important; max-width: 440px !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; border-radius: 20px !important; box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6), 0 0 40px rgba(249, 99, 50, 0.2) !important; background: #1e1e24 !important; color: #ffffff !important; overflow: hidden !important; margin: 20px !important; position: relative !important;">

        <!-- Warm Glowing Ambient Light Effect -->
        <div
            style="position: absolute; top: -40px; left: 50%; transform: translateX(-50%); width: 160px; height: 160px; background: rgba(249, 99, 50, 0.25); filter: blur(45px); border-radius: 50%; z-index: 0; pointer-events: none;">
        </div>

        <!-- Progress Bar at Top -->
        <div style="height: 3px; background: rgba(255, 255, 255, 0.1); width: 100%; position: relative; z-index: 1;">
            <div id="welcome-progress-bar"
                style="height: 100%; background: linear-gradient(90deg, #f96332, #ff8c42); width: 0%;"></div>
        </div>

        <div style="padding: 40px 35px 35px !important; text-align: center !important; position: relative; z-index: 1;">

            <!-- Professional Animated Code SVG Icon -->
            <div style="margin-bottom: 22px; display: flex; justify-content: center;">
                <div
                    style="background: linear-gradient(135deg, rgba(249, 99, 50, 0.25), rgba(249, 99, 50, 0.05)); width: 85px; height: 85px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(249, 99, 50, 0.4); animation: floatLogo 3s ease-in-out infinite; box-shadow: 0 10px 25px rgba(249, 99, 50, 0.25);">
                    <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="#f96332" stroke-width="2.2"
                        stroke-linecap="round" stroke-linejoin="round"
                        style="filter: drop-shadow(0 2px 4px rgba(249,99,50,0.3));">
                        <polyline points="16 18 22 12 16 6"></polyline>
                        <polyline points="8 6 2 12 8 18"></polyline>
                    </svg>
                </div>
            </div>

            <!-- Welcome Greeting & Name -->
            <h3
                style="font-size: 26px !important; font-weight: 800 !important; color: #ffffff !important; margin-bottom: 12px !important; letter-spacing: 0.5px !important; line-height: 1.3 !important;">
                Welcome, <span
                    style="background: linear-gradient(90deg, #f96332, #ffb199); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{
                    $userName }}</span>!
            </h3>

            <!-- Short Software Company System Description -->
            <p
                style="color: #b0b0bc !important; font-size: 14px !important; line-height: 1.6 !important; margin-bottom: 30px !important;">
                Start your day with energy, streamline your tasks, and drive code excellence.
            </p>

            <!-- Interactive Action Button -->
            <button type="button" onclick="dismissWelcomeModal()"
                style="background: linear-gradient(135deg, #f96332, #e05322) !important; border: none !important; border-radius: 30px !important; padding: 13px 30px !important; font-weight: 700 !important; font-size: 14px !important; letter-spacing: 0.8px !important; box-shadow: 0 8px 25px rgba(249, 99, 50, 0.4) !important; transition: all 0.3s ease !important; width: 100% !important; color: #fff !important; cursor: pointer !important;">
                Let's Get Started 🚀
            </button>

        </div>
    </div>
</div>
@endif



@can('viewDashboard')
<div class="content" style="margin-top: -15px;">
    <div class="row">
        <!-- CARD 1: Total Projects -->
        <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
            <div class="card card-stats h-100"
                style="border: none; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); background: #ffffff; border-left: 5px solid #f96332;">
                <div class="card-body text-center" style="padding: 25px;">
                    <div
                        style="background: rgba(249, 99, 50, 0.1); width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                        <span style="font-size: 24px; color: #f96332; font-weight: bold;">📁</span>
                    </div>
                    <p class="card-category"
                        style="font-size: 12px; font-weight: 700; color: #9a9a9a; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">
                        Total Projects
                    </p>
                    <h3 class="card-title" style="font-size: 28px; font-weight: 800; color: #2c2c2c; margin: 0;">
                        {{ $totalProjects }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- CARD 2: Total Tasks -->
        <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
            <div class="card card-stats h-100"
                style="border: none; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); background: #ffffff; border-left: 5px solid #f96332;">
                <div class="card-body text-center" style="padding: 25px;">
                    <div
                        style="background: rgba(249, 99, 50, 0.1); width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                        <span style="font-size: 24px; color: #f96332; font-weight: bold;">📋</span>
                    </div>
                    <p class="card-category"
                        style="font-size: 12px; font-weight: 700; color: #9a9a9a; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">
                        Total Tasks
                    </p>
                    <h3 class="card-title" style="font-size: 28px; font-weight: 800; color: #2c2c2c; margin: 0;">
                        {{ $totalTasks }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- CARD 3: Total Employees -->
        <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
            <div class="card card-stats h-100"
                style="border: none; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); background: #ffffff; border-left: 5px solid #f96332;">
                <div class="card-body text-center" style="padding: 25px;">
                    <div
                        style="background: rgba(249, 99, 50, 0.1); width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                        <span style="font-size: 24px; color: #f96332; font-weight: bold;">👥</span>
                    </div>
                    <p class="card-category"
                        style="font-size: 12px; font-weight: 700; color: #9a9a9a; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">
                        Total Employees
                    </p>
                    <h3 class="card-title" style="font-size: 28px; font-weight: 800; color: #2c2c2c; margin: 0;">
                        {{ $totalEmployees }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- CARD 4: Project Managers -->
        <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
            <div class="card card-stats h-100"
                style="border: none; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); background: #ffffff; border-left: 5px solid #f96332;">
                <div class="card-body text-center" style="padding: 25px;">
                    <div
                        style="background: rgba(249, 99, 50, 0.1); width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                        <span style="font-size: 24px; color: #f96332; font-weight: bold;">👔</span>
                    </div>
                    <p class="card-category"
                        style="font-size: 12px; font-weight: 700; color: #9a9a9a; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">
                        Project Managers
                    </p>
                    <h3 class="card-title" style="font-size: 28px; font-weight: 800; color: #2c2c2c; margin: 0;">
                        {{ $totalManagers }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- CARD 5: Total Teams -->
        <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
            <div class="card card-stats h-100"
                style="border: none; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); background: #ffffff; border-left: 5px solid #f96332;">
                <div class="card-body text-center" style="padding: 25px;">
                    <div
                        style="background: rgba(249, 99, 50, 0.1); width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                        <span style="font-size: 24px; color: #f96332; font-weight: bold;">🛡️</span>
                    </div>
                    <p class="card-category"
                        style="font-size: 12px; font-weight: 700; color: #9a9a9a; text-space: uppercase; letter-spacing: 1px; margin-bottom: 5px;">
                        Total Teams
                    </p>
                    <h3 class="card-title" style="font-size: 28px; font-weight: 800; color: #2c2c2c; margin: 0;">
                        {{ $totalTeams ?? 0 }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- CARD 6: Team Leaders -->
        <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
            <div class="card card-stats h-100"
                style="border: none; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); background: #ffffff; border-left: 5px solid #f96332;">
                <div class="card-body text-center" style="padding: 25px;">
                    <div
                        style="background: rgba(249, 99, 50, 0.1); width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                        <span style="font-size: 24px; color: #f96332; font-weight: bold;">⭐</span>
                    </div>
                    <p class="card-category"
                        style="font-size: 12px; font-weight: 700; color: #9a9a9a; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">
                        Team Leaders
                    </p>
                    <h3 class="card-title" style="font-size: 28px; font-weight: 800; color: #2c2c2c; margin: 0;">
                        {{ $totalTeamLeaders ?? 0 }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Status Analytics Chart Section -->
    <div class="row">
        <div class="col-md-12">
            <div class="card"
                style="border: none; border-radius: 12px; box-shadow: 10px 20px rgba(0,0,0,0.05); background: #ffffff;">
                <div class="card-header" style="border-bottom: 1px solid #eee; padding: 20px 25px;">
                    <h4 class="card-title" style="font-size: 18px; font-weight: 700; color: #2c2c2c; margin: 0;">
                        Task Status Analytics
                    </h4>
                </div>
                <div class="card-body" style="height: 350px; position: relative; padding: 20px;">
                    <canvas id="tasksChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection