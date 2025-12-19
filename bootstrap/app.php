<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
// use App\Console\Commands\DeleteRecentUsers;
use App\Console\Commands\UpdateNewsStatus;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware Spatie Permission
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        // Middleware groups
        $middleware->group('web', [
            // \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            // \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->group('api', [
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Optional: Global middleware
        $middleware->append([
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            // \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle Spatie permission exceptions
        $exceptions->render(function (Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'You do not have the required authorization.',
                ], 403);
            }

            return response()->view('errors.403', [
                'exception' => $e,
            ], 403);
        });
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Halaman tidak ditemukan.',
            ], 404);
        }

        return response()->view('errors.404', [
            'exception' => $e,
        ], 404);
    });
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('news:update-status')->everySecond();
        $schedule->command('app:update-status-banned')->everySecond();
    })
    ->create();
