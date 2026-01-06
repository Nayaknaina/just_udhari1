<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ecommerce\EcommSlider;
use App\Models\Ecommerce\EcommHome;
use App\Models\Ecommerce\EcommAbout;
use App\Models\Ecommerce\EcommTerm;
use App\Models\Ecommerce\EcommPrivacy;
use App\Models\Ecommerce\EcommDesclaimer;

class SiteController extends Controller
{
    private $vendor = null;
    public $social_link = null;
    public $footer_content = [];
    private $item = null;
    public function __construct(Request $request, $item = null)
    {
        //--The Below IF is for Skip this code while running artisan command------//
        if (!app()->runningInConsole()) {
            $this->shop = app('shop');
        }
    }

    public function index()
    {
        $active_menu = 'index';
        $sliderobj = new EcommSlider;
        $sliders = $sliderobj->activeslider($this->shop->id);
        $homeobj = new EcommHome;
        $content = $homeobj->activecontent($this->shop->id);
        return view('ecomm.pages.home', ['index' => true, 'activemenu' => $active_menu,'sliders'=>$sliders,'content'=>$content]);
    }

    public function about()
    {
        $active_menu = 'about';
        $aboutobj = new EcommAbout;
        $content = $aboutobj->activecontent($this->shop->id);
        return view('ecomm.pages.about', ['activemenu' => $active_menu]);
    }
    public function contact()
    {
        $active_menu = 'contact';
        return view('ecomm.pages.contact', ['activemenu' => $active_menu]);
    }

    public function shop($item = null)
    {
        $active_menu = 'shop';
        return view('ecomm.pages.shop', ['activemenu' => $active_menu, 'item' => $item]);
    }

    public function getproducts()
    {
        $active_menu = shop();
    }
    public function product($unique=null)
    {
        $active_menu = 'detail';
        return view('ecomm.pages.detail', ['activemenu' => $active_menu,'dir'=>$unique]);
    }

    public function productdetail()
    {

    }

    public function wishlist(){
        $active_menu = '';
        return view('ecomm.pages.wishlist', ['activemenu' => $active_menu]);
    }
    public function cart()
    {
        $active_menu = 'cart';
        return view('ecomm.pages.cart', ['activemenu' => $active_menu]);
    }
    public function checkout()
    {
        $active_menu = 'checkout';
        return view('ecomm.pages.checkout', ['activemenu' => $active_menu]);
    }
    public function scheme()
    {
        $active_menu = 'scheme';
        return view('ecomm.pages.scheme', ['activemenu' => $active_menu]);
    }
    public function scheme_details($id) {

        $active_menu = 'scheme';
        return view('ecomm.pages.scheme-details', ['activemenu' => $active_menu,'url'=>$id]);

    }

    public function custoreg()
    {
        $active_menu = 'register';
        return view('ecomm.pages.register', ['activemenu' => $active_menu]);
    }
    public function custologin()
    {
        $active_menu = 'login';
        return view('ecomm.pages.login', ['activemenu' => $active_menu]);
    }
    public function policy()
    {
        $active_menu = '';
        return view('ecomm.pages.privacy', ['activemenu' => $active_menu]);
    }
    public function terms()
    {
        $active_menu = '';
        return view('ecomm.pages.tnc', ['activemenu' => $active_menu]);
    }
    public function desclaimer()
    {
        $active_menu = '';
        return view('ecomm.pages.desclaimer', ['activemenu' => $active_menu]);
    }
    public function location()
    {
        $active_menu = '';
        return view('ecomm.pages.location', ['activemenu' => $active_menu]);
    }

    //---Registration and LogIn---------//
    public function register(Request $request){
        print_r($request->toArray());
    }
    //---END Registration and LogIn---------//
}
