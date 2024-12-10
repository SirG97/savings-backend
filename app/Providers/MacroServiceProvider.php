<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Auditor;
use App\Models\Marketer;
use App\Models\SuperAdmin;
use Illuminate\Auth\RequestGuard;
use Illuminate\Auth\SessionGuard;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

        //Auth::marketer() for web
        SessionGuard::macro('marketer', function(int|string|null $id = null): Marketer|null {
            return Marketer::find($id ?? Auth::user()->id);
        });

        //Auth::marketer() for api
        RequestGuard::macro('marketer', function(int|string|null $id = null): Marketer|null {
            return Marketer::find($id ?? Auth::user()->id);
        });

        //Auth::auditor() for web
        SessionGuard::macro('auditor', function(int|string|null $id = null): Auditor|null {
            return Auditor::find($id ?? Auth::user()->id);
        });

        //Auth::updater() for api
        RequestGuard::macro('auditor', function(int|string|null $id = null): Auditor|null {
            return Auditor::find($id ?? Auth::user()->id);
        });

        //Auth::admin() for web
        SessionGuard::macro('admin', function(int|string|null $id = null): Admin|null {
            return Admin::find($id ?? Auth::user()->id);
        });

        //Auth::admin() for api
        RequestGuard::macro('admin', function(int|string|null $id = null): Admin|null {
            return Admin::find($id ?? Auth::user()->id);
        });

        //Auth::superAdmin() for web
        SessionGuard::macro('superAdmin', function(int|string|null $id = null): SuperAdmin|null {
            return SuperAdmin::find($id ?? Auth::user()->id);
        });

        //Auth::superAdmin() for api
        RequestGuard::macro('superAdmin', function(int|string|null $id = null): SuperAdmin|null {
            return SuperAdmin::find($id ?? Auth::user()->id);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
