@extends('layouts.app')

@section('Main_Content')
<div class="panel-header panel-header-sm"></div>
<div class="content">
    <div class="row">
        <!-- Total Projects Card -->
        <div class="col-lg-6 col-md-6">
            <div class="card card-stats" style="border: none; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); background: #ffffff; border-right: 5px solid #f96332;">
                <div class="card-body text-center" style="padding: 25px;">
                    <div style="background: rgba(249, 99, 50, 0.1); width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                        <span style="font-size: 24px; color: #f96332; font-weight: bold;">📁</span>
                    </div>
                    <p class="card-category" style="font-size: 12px; font-weight: 700; color: #9a9a9a; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">Total Projects</p>
                    <h3 class="card-title" style="font-size: 28px; font-weight: 800; color: #2c2c2c; margin: 0;">{{ $totalProjects }}</h3>
                </div>
            </div>
        </div>

        <!-- Total Tasks Card -->
        <div class="col-lg-6 col-md-6">
            <div class="card card-stats" style="border: none; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); background: #ffffff; border-right: 5px solid #51cbce;">
                <div class="card-body text-center" style="padding: 25px;">
                    <div style="background: rgba(81, 203, 206, 0.1); width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                        <span style="font-size: 24px; color: #51cbce; font-weight: bold;">📋</span>
                    </div>
                    <p class="card-category" style="font-size: 12px; font-weight: 700; color: #9a9a9a; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">Total Tasks</p>
                    <h3 class="card-title" style="font-size: 28px; font-weight: 800; color: #2c2c2c; margin: 0;">{{ $totalTasks }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Status Analytics Section -->
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="border: none; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); background: #ffffff;">
                <div class="card-header" style="border-bottom: 1px solid #eee; padding: 20px 25px;">
                    <h4 class="card-title" style="font-size: 18px; font-weight: 700; color: #2c2c2c; margin: 0;">Task Status Analytics</h4>
                </div>
                <div class="card-body" style="height: 350px; position: relative; padding: 20px;">
                    <canvas id="tasksChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
