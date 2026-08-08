<?php

namespace App\Providers;


use App\Models\Task;
use App\Policies\TaskPolicy;
//use Illuminate\Support\Facades\Gate;
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
        // This is a good place to register policies and it is optional to use the Gate facade to define policies for your models.
        //Gate::policy(Task::class, TaskPolicy::class);
    }
}