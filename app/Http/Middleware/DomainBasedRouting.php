<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

class DomainBasedRouting
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next) {

        // Get the host name

        $host = $request->getHost();

        if (strpos($host, 'http://127.0.0.1') !== false) {

            require base_path('routes/web.php') ;

        } else {

                Route::namespace('App\Http\Controllers\Ecommerce')
                    ->group(base_path('routes/ecommerce/web.php')) ;

        }

        return $next($request);

    }
}
