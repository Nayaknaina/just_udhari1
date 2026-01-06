<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */

    public const HOME = 'vendors/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */

    protected $namespace = 'App\Http\Controllers';

    public function boot() {
		
        $this->configureRateLimiting() ;

        $this->routes(function () {
            
            if (app('launch_ecommerce')) {
              
                Route::middleware('api')
                            ->prefix('api')
                            ->namespace('App\Http\Controllers\Ecommerce')
                            ->group(base_path('routes/ecommerce/api.php'));

                Route::middleware('web')
                        // ->prefix('ecommerce')
                        ->namespace('App\Http\Controllers\Ecommerce')
                        ->group(base_path('routes/ecommerce/web.php'));
				
				Route::middleware(['web','custo'])
                        //->prefix('ecommerce')
                        ->namespace('App\Http\Controllers\Ecommerce')
                        ->group(base_path('routes/ecommerce/customer.php'));

            }else{
				
                Route::prefix('vendors')
                        ->middleware('web')
                        ->namespace('App\Http\Controllers\Vendor')
                        ->group(base_path('routes/vendor.php')) ;

				Route::prefix('vendors/global')
                        ->middleware('web')
                        ->namespace('App\Http\Controllers')
                        ->group(base_path('routes/shared.php')) ;
				
                Route::prefix('vendors/ecommerce')
                        ->middleware('web')
                        ->namespace('App\Http\Controllers\Vendor\Ecommerce')
                        ->group(base_path('routes/vendor/ecommerce.php')) ;

                Route::prefix('vendors/settings')
                        ->middleware('web')
                        ->namespace('App\Http\Controllers\Vendor\Settings')
                        ->group(base_path('routes/vendor/setting.php')) ;

                Route::prefix('ss_manager')
                        ->middleware('web')
                        ->namespace('App\Http\Controllers\Admin')
                        ->group(base_path('routes/admin.php')) ;

                Route::prefix('api')
                    ->middleware('api')
                    ->namespace($this->namespace)
                    ->group(base_path('routes/api.php')) ;

                Route::middleware('web')
                    ->namespace($this->namespace)
                    ->group(base_path('routes/web.php')) ;

                
            }

        }) ;

    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
