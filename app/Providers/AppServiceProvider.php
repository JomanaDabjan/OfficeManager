<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Team;
use App\Policies\TeamPolicy;
use App\Models\Task;
use App\Policies\TaskPolicy;
use App\Models\Project;
use App\Policies\ProjectPolicy;
use App\Models\Report;
use App\Policies\ReportPolicy;
use App\Policies\DashboardPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Team::class, TeamPolicy::class);
        Gate::policy(Task::class, TaskPolicy::class);
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(Report::class, ReportPolicy::class);
        Gate::define('viewDashboard', [DashboardPolicy::class, 'viewDashboard']);
    }
}