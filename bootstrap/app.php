<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
// Import necessary exception classes to handle missing models and HTTP 404 errors safely
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register custom route middleware aliases here
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        /**
         * =====================================================================
         * GLOBAL EXCEPTION HANDLING CONFIGURATION
         * =====================================================================
         * This block intercepts unhandled exceptions globally across the entire
         * application before they break and show a generic system error page.
         */
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {

            // Check if the incoming request belongs to the admin panel routes
            // or if it expects a standard web HTML page response rather than an API JSON.
            if ($request->is('admin/*')) {

                // Check specifically if it's a task route to redirect back to tasks index
                if ($request->is('admin/tasks*')) {
                    return redirect()->route('admin.task.index')
                        ->with('error', 'The task you are looking for no longer exists or has been deleted.');
                }

                // Default fallback for other admin routes (like projects)
                return redirect()->route('admin.project.index')
                    ->with('error', 'The record you are looking for no longer exists or has been deleted.');
            }
        });
    })->create();