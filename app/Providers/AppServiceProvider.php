<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Http\Controllers\Interfaces\UserManagementInterfaces::class, \App\Http\Controllers\Repositories\UserManagementRepository::class);
        $this->app->bind(\App\Http\Controllers\Interfaces\PermissionInterfaces::class, \App\Http\Controllers\Repositories\PermissionRepository::class);
        $this->app->bind(\App\Http\Controllers\Interfaces\RoleInterfaces::class, \App\Http\Controllers\Repositories\RoleRepository::class);
        $this->app->bind(\App\Http\Controllers\Interfaces\ReportsInterfaces::class, \App\Http\Controllers\Repositories\ReportsRepository::class);
        $this->app->bind(\App\Http\Controllers\Interfaces\ProfileInterfaces::class, \App\Http\Controllers\Repositories\ProfileRepository::class);
        $this->app->bind(\App\Http\Controllers\Interfaces\ConfirmReportsInterfaces::class, \App\Http\Controllers\Repositories\ConfirmReportsRepository::class);
        $this->app->bind(\App\Http\Controllers\Interfaces\NewsInterfaces::class, \App\Http\Controllers\Repositories\NewsRepository::class);
        $this->app->bind(\App\Http\Controllers\Interfaces\ConsultationInterfaces::class, \App\Http\Controllers\Repositories\ConsultationRepository::class);
        $this->app->bind(\App\Http\Controllers\Interfaces\DisasterCategoryInterfaces::class, \App\Http\Controllers\Repositories\DisasterCategoryRepository::class);
        $this->app->bind(\App\Interfaces\AccountInterface::class, \App\Repositories\AccountRepository::class);
        $this->app->bind(\App\Http\Controllers\Interfaces\DisasterImpactsInterfaces::class, \App\Http\Controllers\Repositories\DisasterImpactsRepository::class);
        $this->app->bind(\App\Interfaces\InfografisInterface::class, \App\Repositories\InfografisRepository::class);
        $this->app->bind(\App\Http\Controllers\Interfaces\DisasterVictimsInterfaces::class, \App\Http\Controllers\Repositories\DisasterVictimsRepository::class);
        $this->app->bind(\App\Http\Controllers\Interfaces\DisasterReportDocumentationsInterfaces::class, \App\Http\Controllers\Repositories\DisasterReportDocumentationsRepository::class);
        $this->app->bind(\App\Http\Controllers\Interfaces\DashboardInterfaces::class, \App\Http\Controllers\Repositories\DashboardRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::addNamespace('errors', resource_path('views/errors'));
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
    }
}
