<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider ;
use Illuminate\Support\Facades\Auth ;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use App\Models\Shop;
use App\Models\ShopBranch;
use App\Models\Ecommerce\EcommSocial;
use App\Models\Ecommerce\EcommFooter;
use App\Models\Ecommerce\EcommContact;
use App\Models\Ecommerce\EcommWebInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
    public function boot(Request $request): void {

        // DB::listen(function ($query) {
        //     Log::info($query->sql);
        //     Log::info($query->bindings);
        //     Log::info($query->time);
        // });
        app()->instance('branch', "");
        app()->instance('launch_ecommerce',false) ;

        //---The Line wrote by 96-----------------//

        if (!app()->runningInConsole()) {

            $url = $request->getHttpHost();
            $host = str_replace('www.','',$url);

            $url = $request->getRequestUri();
			
        //    $main_sys = config('app.main_system');
        //    $main_sys = '3.7.100.13';
                $main_sys = 'justudhari.com';
        //    echo $main_sys."<br>";
            $url_arr = explode("/", trim($url, '/'));

            $ecomm_segment = (!empty($url_arr) && $url[0]!="")?(($url_arr[0]!="api")?$url_arr[0]:$url_arr[1]??""):"";

            //echo $ecomm_segment;

            // $ecommurl = trim($host."/".$ecomm_segment,"/");
            $ecommurl = $main_sys ;

            // echo  $ecommurl."<br>";

            if($ecommurl != $main_sys){

                app()->instance('launch_ecommerce',true);

               $ecomm_flag = (!empty($url_arr) && isset($url_arr[1]) && $url[1]!="")?(($url_arr[1]!="api")?$url_arr[1]:$url_arr[2]??""):"";

                // $shop = ShopBranch::where('domain_name',$ecomm_domain)->first()??Shop::where('domain_name',$ecomm_path)->first();

                // echo $ecommurl ;

                $shop = ShopBranch::where('domain_name',$ecommurl)->first()??ShopBranch::where('domain_name',$ecommurl)->first();
				//dd($shop);
                // echo $ecommurl;
                // dd($shop) ;
                
                // $ecomm_domain = rtrim($ecomm_path."/".$ecomm_flag,"/");

                app()->instance('shop', $shop) ;
				
                View::composer("*",function($view) use ($shop){

                    $ecomm_social = new EcommSocial ;
                    $ecomm_info = new EcommWebInformation ;
                    $common_content['socials'] = $ecomm_social->activecontent($shop->id) ;
                    $common_content['info'] = $ecomm_info->activecontent($shop->id) ;
                    View::share('common_content',$common_content) ;

                });

                $branch_shop = (!empty($shop) && $shop->branch_type!=0) ? "{$ecomm_flag}/" : '' ;
				
                app()->instance('branch', $branch_shop) ;

                // app()->instance('branch', '') ;

                $head_branch = @$shop->shop_id;

                $branches = ShopBranch::where(['shop_id'=>$head_branch,'branch_type'=>1])->get();
				
                // View::share("ecommbaseurl", $ecomm_segment."/".$branch_shop);
                // app()->instance('ecommbaseurl', $ecomm_segment."/".$branch_shop);
                View::share("ecommbaseurl", "/".$branch_shop);
                app()->instance('ecommbaseurl', "/".$branch_shop);
                View::share("branches", $branches);
                View::share("ecommurl", $ecommurl);

                //---END The Line wrote by 96-------------//

            }else{
				
                if(Auth::user()) {

                    $apuser =  Auth::user() ;

                    $apshop = Shop::where('id',$apuser->shop_id)->first() ;

                    View::composer('*', function ($view) {

                            $view->with([
                                'userd' => $apuser ,
                                // 'main_branch' => $main_branch ,
                                // 'apshop' => $apshop ,
                            ]) ;

                        }) ;

                }

                $this->app->singleton('userd', function () {
                    return  Auth::user() ;
                });

                Validator::extend('max_decimal_places', function ($attribute, $value, $parameters, $validator) {
                    $maxDecimalPlaces = $parameters[0];
                    $valueString = (string) $value;
                    $decimalPosition = strpos($valueString, '.');
                    if ($decimalPosition !== false) {
                        $decimalPlaces = strlen($valueString) - $decimalPosition - 1;
                        return $decimalPlaces <= $maxDecimalPlaces;
                    }
                    return true; // No decimal places
                });

                Validator::replacer('max_decimal_places', function ($message, $attribute, $rule, $parameters) {
                    return str_replace(':max_decimal_places', $parameters[0], $message);
                });
            }

        }
        // exit();
    }
}
