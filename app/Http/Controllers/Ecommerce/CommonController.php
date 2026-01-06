<?php
namespace App\Http\Controllers\Ecommerce;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\PaymentGatewayService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Ecommerce\EcommProduct;
use App\Models\Ecommerce\EcommProductImage;
use App\Models\Category;

class CommonController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $view_route = null;
    protected $shop = null;
	protected $paymentGatewayService;
  
    protected function __construct($request){
		$this->shop = app('shop');
        $this->view_route = app('ecommbaseurl');
        $matter_menu =Category::where("category_level",'1')->get();
        $collection = Category::where("category_level",'2')->get();
        $main_menu = Category::where("category_level",'3')->get();
        View::share(['matter_menu'=>$matter_menu,'main_menu'=>$main_menu,'collection'=>$collection]);
		
		$gateway_id = isset($request->gateway)?$request->gateway:false;
        $this->paymentGatewayService = new PaymentGatewayService($gateway_id);
		app()->instance('gold_rate', "8600");
        app()->instance('silver_rate', "100");
    }
}