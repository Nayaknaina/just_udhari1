<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class GlobalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {

            $user = Auth::user();
            $branch_type = ($user) ? $user->shopbranch->branch_type : null;
            $view->with(['userd' => $user, 'branch_type' => $branch_type]);

        });

        $this->app->singleton('userd', function () {
            return Auth::user();
        });

        $this->app->singleton('branch_type', function () {
            $user = Auth::user();
            return ($user) ? $user->shopbranch->branch_type : null;
        });
    }
}
