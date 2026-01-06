<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Ecommerce\CommonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Ecommerce\EcommSlider;
use App\Models\Ecommerce\EcommHome;
use App\Models\Ecommerce\EcommAbout;
use App\Models\Ecommerce\EcommTerm;
use App\Models\Ecommerce\EcommPrivacy;
use App\Models\Ecommerce\EcommRefund;
use App\Models\Ecommerce\EcommDesclaimer;
use App\Models\Ecommerce\EcommShiping;
use App\Models\Ecommerce\EcommDelete;
use App\Models\Ecommerce\EcommProduct;
use App\Models\Ecommerce\ShoppingList;
use App\Models\Ecommerce\EcommEnquiries;
use App\Models\ShopScheme;
use App\Models\Customer;
use App\Models\StockCategory;
use App\Models\District;
use App\Models\Category;
use App\Models\Cataloge;
use App\Models\CatalogeCategory;
use App\Models\SchemeEnquiry;
use App\Models\Rate;
use App\Notifications\SchemeEnquiryNotification;
use App\Models\User;



class SiteController extends CommonController {

    private $vendor = null ;
    public $social_link = null ;
    public $footer_content = [] ;
    private $item = null ;

    public function __construct(Request $request, $item = null) {

        //--The Below IF is for Skip this code while running artisan command------//
        if (!app()->runningInConsole()) {
            parent::__construct($request);
            //$this->shop = app('shop');
        }
    }

    /*-----P-Code --------------------
    public function index() {

        $active_menu = 'index';
        $sliderobj = new EcommSlider;
        $sliders = $sliderobj->activeslider($this->shop->id);
        $homeobj = new EcommHome;
        $aboutobj = new EcommAbout;
        $content = $homeobj->activecontent($this->shop->id) ;
        $about_content = $aboutobj->activecontent($this->shop->id) ;
        return view('ecomm.pages.home', ['index' => true, 'activemenu' => $active_menu,'sliders'=>$sliders,'content'=>$content,'about_content'=>$about_content]) ;

    }
    */
    //-------------------------96-Code --------------------//

    public function index_()
    {
        $active_menu = 'index';
        $sliderobj = new EcommSlider;
        $sliders = $sliderobj->activeslider($this->shop->id);
        $homeobj = new EcommHome;
        $content = $homeobj->activecontent($this->shop->id);
        $aboutobj = new EcommAbout;
        $about_content = $aboutobj->activecontent($this->shop->id) ;
        $cat_id_arr = $homeobj->categories($this->shop->id);
        //dd($cat_id_arr);
        $cat_data = [];
        foreach($cat_id_arr as $idk=>$idv){
            $cat_data[$idk] = DB::table(DB::raw("ecomm_products,categories"))->select('ecomm_products.thumbnail_image','categories.*')->where(["ecomm_products.stock_id"=>$idv->id,"categories.id"=>$idv->category_id])->first();
        }
        $cat_id_arr_b = $homeobj->stockcategories($cat_id_arr);
        //dd($cat_id_arr_b);
        $coll_data = [];
        foreach($cat_id_arr_b as $idbk=>$idbv){
            $coll_data[$idbk] = DB::table(DB::raw("ecomm_products,categories"))->select('ecomm_products.thumbnail_image','categories.*')->where(["ecomm_products.stock_id"=>$idbv->id,"categories.id"=>$idbv->category_id,"categories.category_level"=>'2'])->first();
        }

        $schemes = ShopScheme::where('shop_id',$this->shop->shop_id)->where('launch_date','<=',date("Y-m-d",strtotime('now')))->get();
        
        // dd($schemes);

        return view('ecomm.pages.home', ['index' => true, 'activemenu' => $active_menu,'sliders'=>$sliders,'content'=>$content,'about_content'=>$about_content,"category_product"=>$cat_data,"collection_product"=>$coll_data,'schemes'=>$schemes]) ;

    }
	
	 public function index()
    {
        $active_menu = 'index';
        $sliderobj = new EcommSlider;
        $sliders = $sliderobj->activeslider($this->shop->id);
        $homeobj = new EcommHome;
        $content = $homeobj->activecontent($this->shop->id);
        $aboutobj = new EcommAbout;
        $about_content = $aboutobj->activecontent($this->shop->id) ;
        $cat_id_arr = $homeobj->categories($this->shop->id);
        
        $cat_data = $homeobj->categorywisestockid($this->shop->id,3);
        
        $coll_data = $homeobj->categorywisestockid($this->shop->id,2);
		
		$schemes = ShopScheme::where('shop_id',$this->shop->shop_id)->where('launch_date','<=',date("Y-m-d",strtotime('now')))->get();
		
		$catalog = Cataloge::where('shop_id',$this->shop->shop_id)->get();
		
        return view('ecomm.pages.home', ['index' => true, 'activemenu' => $active_menu,'sliders'=>$sliders,'content'=>$content,'about_content'=>$about_content,"category_product"=>$cat_data,"collection_product"=>$coll_data,'schemes'=>$schemes,'catalog'=>$catalog]);
    }

    //-------------------------END : 96-Code --------------------//

    public function about(){

        $activemenu = 'about';
        $aboutobj = new EcommAbout;
        $content = $aboutobj->activecontent($this->shop->id);

        return view('ecomm.pages.about', compact('activemenu','content')) ;

    }

    public function contact(){

        $active_menu = 'contact';
        return view('ecomm.pages.contact', ['activemenu' => $active_menu]) ;

    }

	 public function sendenquiry(Request $request){
        //print_r($request->toArray());
        $validator = Validator::make(
            $request->all(),
            [
                "name" => "required|string",
                "mobile" => "required|numeric|digits:10",
                "subject" => "required|string",
                'message'=>"required|string",
            ],
            [
                "name.required"=>"Enter Your Name !",
                "mobile.required"=>"Enter Your Mobile Number !",
                "mobile.numeric"=>"Mobile Number should be numeric !",
                "mobile.digits"=>"Mobile Number Must have 10 Digits !",
                "subject.required"=>"Enter Subject of Message !",
                'subject.string'=>"Enter a Valid Subject !",
                "message.required"=>"Enter Your Message !",
                'message.string'=>"Enter a Valid Message !",
            ]
        );
        if($validator->fails()){
            return response()->json(["valid"=>false, 'errors' => $validator->errors()]);
        }else{
            $custo  =Auth::guard('custo')->user();
            $is_custo = ($custo)?1:0;
            $data = [
                "enq_custo"=>($custo)?'1':'0',
                "enq_custo_id"=>($custo)?$custo->id:null,
                "enq_name"=>$request->name,
                "enq_email"=>$request->email,
                "enq_mob"=>$request->mobile,
                "enq_subject"=>$request->subject,
                "enq_msg"=>$request->message,
                "enq_ip"=>$request->ip(),
                "enq_shop_id"=>$this->shop->shop_id,
                "enq_branch_id"=>$this->shop->id,
            ];
        }
        if(EcommEnquiries::create($data)){
            return response()->json(["valid"=>true,'status'=>true, 'msg' => "Enquiries Succesfully Send , We Contact you ASAP !"]);
        }else{
            return response()->json(["valid"=>true,'status'=>false, 'msg' => "Failed to Send Enquiry !"]);
        }
    }
	
    /*public function sendenquiry(Request $request){
        //print_r($request->toArray());
        $validator = Validator::make(
            $request->all(),
            [
                "name" => "required|string",
                "mobile" => "required|numeric|digits:10",
                "subject" => "required|string",
                'message'=>"required|string",
            ],
            [
                "name.required"=>"Enter Your Name !",
                "mobile.required"=>"Enter Your Mobile Number !",
                "mobile.numeric"=>"Mobile Number should be numeric !",
                "mobile.digits"=>"Mobile Number Must have 10 Digits !",
                "subject.required"=>"Enter Subject of Message !",
                'subject.string'=>"Enter a Valid Subject !",
                "message.required"=>"Enter Your Message !",
                'message.string'=>"Enter a Valid Message !",
            ]
        );
        if($validator->fails()){
            return response()->json(["valid"=>false, 'errors' => $validator->errors()]);
        }else{
            $custo  =Auth::guard('custo')->user();
            $is_custo = ($custo)?1:0;
            $data = [
                "enq_custo"=>($custo)?'1':'0',
                "enq_custo_id"=>($custo)?$custo->id:null,
                "enq_name"=>$request->name,
                "enq_email"=>$request->email,
                "enq_mob"=>$request->mobile,
                "enq_subject"=>$request->subject,
                "enq_msg"=>$request->message,
                "enq_ip"=>$request->ip(),
                "enq_shop_id"=>$this->shop->shop_id,
                "enq_branch_id"=>$this->shop->id,
            ];
        }
        if(EcommEnquiries::create($data)){
            return response()->json(["valid"=>true,'status'=>true, 'msg' => "Enquiries Succesfully Send , We Contact you ASAP !"]);
        }else{
            return response()->json(["valid"=>true,'status'=>false, 'msg' => "Failed to Send Enquiry !"]);
        }
    }*/

    public function shop($item = null) {

        $active_menu = 'shop';
        return view('ecomm.pages.shop', ['activemenu' => $active_menu, 'item' => $item]) ;

    }

    public function shoppage_(Request $request)
    {
        $products_query = EcommProduct::select('ecomm_products.*');
        $slug_arr = json_decode($request->data);
        $term_arr = json_decode($request->term);
        if(!empty($slug_arr)){
            $category_id_arr = Category::whereIn('slug',$slug_arr)->get()->pluck('id')->toArray();
            $stock_id_arr = StockCategory::whereIn('category_id',$category_id_arr)->get()->pluck('stock_id')->toArray();
            $products_query->where("ecomm_products.branch_id",$this->shop->id)->wherein("ecomm_products.stock_id",$stock_id_arr);
        }
        $products_query->where("ecomm_products.branch_id",$this->shop->id);
        
        (!empty($term_arr) && $term_arr[0]!="")?$products_query->where("ecomm_products.name","like",'%'.$term_arr[0].'%'):null;

        (!empty($term_arr) && $term_arr[1]!="")?$products_query->orderby("ecomm_products.rate", "{$term_arr[1]}"):null;

        $products= $products_query->paginate(15);

        $shopcontent =  view("ecomm.pages.shopcontent",compact('products'))->render();
        return response()->json( array('success' => true, 'html'=>$shopcontent));
    }

	/*public function shoppage(Request $request)
    {
        //$products_query = EcommProduct::select('ecomm_products.*');
		//echo $this->shop->id;
        $products_query = EcommProduct::where("shop_id",$this->shop->shop_id);
		
		
        $slug_arr = json_decode($request->data);
        $term_arr = json_decode($request->term);
        if(!empty($slug_arr)){
            $category_id_arr = Category::whereIn('slug',$slug_arr)->pluck('id')->toArray();
            $stock_id_arr = StockCategory::whereIn('category_id',$category_id_arr)->pluck('stock_id')->toArray();
            $products_query->wherein("stock_id",$stock_id_arr);
        }
		(!empty($term_arr) && $term_arr[0]!="")?$products_query->where("name","like",'%'.$term_arr[0].'%'):null;

        (!empty($term_arr) && $term_arr[1]!="")?$products_query->orderby("rate", $term_arr[1]):null;
		
        //echo $products_query->toSQl();
        $products= $products_query->paginate(15);

        $shopcontent =  view("ecomm.pages.shopcontent",compact('products'))->render();
        return response()->json( array('success' => true, 'html'=>$shopcontent));
    }*/

	/*public function shoppage(Request $request)
    {
        $slug_arr = json_decode($request->data);
        $term_arr = json_decode($request->term);

        $products_query = EcommProduct::join('stocks','ecomm_products.stock_id','stocks.id')->where('ecomm_products.shop_id',$this->shop->shop_id)->whereNot('stocks.item_type','loose');
        
        if(!empty($slug_arr)){
            $category_id_arr = Category::whereIn('slug',$slug_arr)->pluck('id')->toArray();

            $products_query->select('stock_categories.stock_id')->distinct()->join('stock_categories','stock_categories.stock_id','stocks.id')->whereIn('stock_categories.category_id',$category_id_arr)->orWhereIn('stocks.category_id',$category_id_arr);
            
        }

        (!empty($term_arr) && $term_arr[0]!="")?$products_query->where("ecomm_products.name","like",'%'.$term_arr[0].'%'):null;

        (!empty($term_arr) && $term_arr[1]!="")?$products_query->orderByRaw("CASE  WHEN stocks.item_type = ? THEN stocks.available ELSE ecomm_products.rate END {$term_arr[1]}",['genuine']):null;


        $products= $products_query->paginate(15);

        $shopcontent =  view("ecomm.pages.shopcontent",compact('products'))->render();
        return response()->json( array('success' => true, 'html'=>$shopcontent));
    }*/
	
	//---Product in Hirerchy-----------------------------//
	/*public function shoppage(Request $request)
    {
        $slug_arr = json_decode($request->data,true)??[];
        $term_arr = json_decode($request->term,true)??[];
        $products_query = EcommProduct::join('stocks','ecomm_products.stock_id','stocks.id')->where('ecomm_products.shop_id',$this->shop->shop_id);
		
        if(!empty($term_arr) && $term_arr[1]!=""){
            if(isset($term_arr[0]) && $term_arr[0]!=""){
                $products_query->where("ecomm_products.name","like",'%'.$term_arr[0].'%');
            }
            if(isset($term_arr[1]) && $term_arr[1]!=""){
                $products_query->orderByRaw("CASE  WHEN stocks.item_type = ? THEN stocks.available*caret ELSE ecomm_products.rate END {$term_arr[1]}",['genuine']);
            }
        }
        if(!empty($slug_arr)){
            $all_cats = array_values($slug_arr);
            $categoryies = Category::whereIn('slug',$all_cats)->get();
            if(isset($slug_arr['type']) && $slug_arr['type']!=""){
                if(!in_array($slug_arr['type'],['artificial','loose'])){
                    $type_id = $categoryies->where('category_level',1)->pluck('id')[0];
                    $products_query->where('stocks.category_id',$type_id);
                    $type = $categoryies->where('category_level',1)->pluck('name')[0];
                }else{
                    $products_query->where('stocks.item_type','artificial');
                }
            }

            if(isset($slug_arr['coll']) || isset($slug_arr['cat'])){
                $other_cat = $categoryies->whereIn('category_level',[2,3])->pluck('id')->toArray();
                $products_query->join('stock_categories','stock_categories.stock_id','stocks.id');
                if(isset($slug_arr['coll']) && $slug_arr['coll']!=""){
                    $products_query->where('stock_categories.category_id',$categoryies->where('category_level',2)->pluck('id')[0]);
                }
                if(isset($slug_arr['cat']) && $slug_arr['cat']!=""){
                    $products_query->whereIn('stock_categories.category_id',[$categoryies->where('category_level',3)->pluck('id')[0]]);
                }
                $products_query->orderBy("stock_categories.id",'DESC');
            }
            
        }

        $products= $products_query->paginate(15);

        $shopcontent =  view("ecomm.pages.shopcontent",compact('products','slug_arr','term_arr'))->render();
        return response()->json( array('success' => true, 'html'=>$shopcontent));
    }*/
	
	public function shoppage(Request $request)
    {
        $slug_arr = json_decode($request->data,true)??[];
        $term_arr = json_decode($request->term,true)??[];
		//print_r($term_arr);
        $products_query = EcommProduct::where('ecomm_products.shop_id',$this->shop->shop_id);
        if(!empty($slug_arr)){
            $all_cats = array_values($slug_arr);
            $categoryies = Category::whereIn('slug',$all_cats)->pluck('id');
            $products_query->whereHas('categories',function($cat_query) use ($categoryies){
                $cat_query->whereIn('category_id',$categoryies)->groupBy('stock_id')->havingRaw('COUNT(DISTINCT stock_categories.category_id) = ?', [count($categoryies)]) ;
            });
        }
        if(!empty($term_arr)){
            if(isset($term_arr[0]) && $term_arr[0]!=""){
                //$products_query->where("ecomm_products.name","like",'%'.$term_arr[0].'%');
				$products_query->where("name","like",'%'.$term_arr[0].'%');
            }
            if(isset($term_arr[1]) && $term_arr[1]!=""){
				$products_query->join('stocks','ecomm_products.stock_id','stocks.id')->orderByRaw("CASE  WHEN stocks.item_type = ? THEN stocks.available*stocks.caret ELSE ecomm_products.rate END {$term_arr[1]}",['genuine']);
            }
        }
        $products= $products_query->paginate(15);

        $shopcontent =  view("ecomm.pages.shopcontent",compact('products','slug_arr','term_arr'))->render();
        return response()->json( array('success' => true, 'html'=>$shopcontent));
    }

	public function categols(Request $request){
        if($request->ajax()){
            $catalog_query = Cataloge::where("branch_id",$this->shop->id)->orderby('short_order','ASC');

            $slug_arr = json_decode($request->data);
            $term_arr = json_decode($request->term);
    
            if(!empty($slug_arr)){
                $category_id_arr = Category::whereIn('slug',$slug_arr)->pluck('id')->toArray();
                $callog_id_arr = CatalogeCategory::whereIn('category_id',$category_id_arr)->pluck('cataloge_id')->toArray();
                $catalog_query->wherein("id",$callog_id_arr);
            }
            
            (!empty($term_arr) && $term_arr[0]!="")?$catalog_query->where("name","like",'%'.$term_arr[0].'%'):null;
    
            //(!empty($term_arr) && $term_arr[1]!="")?$catalog_query->orderby("rate", "{$term_arr[1]}"):null;
    
            $catalogs= $catalog_query->paginate(15);
    
            $catalogpage =  view("ecomm.pages.catelogcontent",compact('catalogs'))->render();
            return response()->json(['success' => true, 'html'=>$catalogpage]);
        }else{
            $active_menu = 'cateloge';
            return view('ecomm.pages.catelog', ['activemenu' => $active_menu]);
        }
    }
	
	public function cataloggallery($unique=null){
        $galls =  Cataloge::with('catalogeimages')->where(["branch_id"=>$this->shop->id,'unique'=>$unique])->get();
        if($galls->count()>0){
            return view('ecomm.pages.cataloggallery',compact('galls'))->render();
        }else{
            echo '<div class="alert alert-warning text-center ">No gallery !</div>';
        }
    }

    public function getproducts() {

        $active_menu = shop() ;

    }

    // public function product($unique=null){

    //     $active_menu = 'detail';
    //     return view('ecomm.pages.productdetail', ['activemenu' => $active_menu,'dir'=>$unique]);

    // }

    public function product($url=null){

        /*$product = EcommProduct::with('galleryimages','stock')->where('url',$url)->first();
        $active_menu = 'detail';
        return view('ecomm.pages.productdetail', ['activemenu' => $active_menu,'product'=>$product]);*/
		$product = EcommProduct::with('galleryimages','stock')->where('url',$url)->first();
		
        if($product && $product->stock->available==0){
            return redirect("{$this->view_route}shop")->with('error', "Invalid Action !");
        }else{
            $active_menu = 'detail';
            return view('ecomm.pages.productdetail', ['activemenu' => $active_menu,'product'=>$product]);
        }

    }

    public function productdetail(){

    }

    public function wishlist(){

        $active_menu = '';
        return view('ecomm.pages.wishlist', ['activemenu' => $active_menu]);

    }

    public function cart(){

        $active_menu = 'cart';
        return view('ecomm.pages.cart', ['activemenu' => $active_menu]) ;

    }

    // public function checkout() {

    //     $active_menu = 'checkout';
    //     return view('ecomm.pages.checkout', ['activemenu' => $active_menu]);

    // }

    public function scheme() {
        $active_menu = 'scheme';
        //echo $this->shop->shop_id;
        $schemes = ShopScheme::where('shop_id',$this->shop->shop_id)->where('launch_date','<=',date("Y-m-d",strtotime('now')))->get();
       // dd($schemes);
        return view('ecomm.pages.scheme', ['schemes'=>$schemes,'activemenu' => $active_menu]);

    }

    public function scheme_details($url_part) {
        $scheme = ShopScheme::where('url_part',$url_part)->first();
        $active_menu = 'scheme';
        return view('ecomm.pages.scheme-details', ['scheme'=>$scheme,'activemenu' => $active_menu,'url'=>$url_part]);

    }

	public function scheme_rules($url_part){
        //echo $url_part;
        $scheme = ShopScheme::where('url_part',$url_part)->first();
        //dd($scheme);
        echo "<h4 class='text-info'>$scheme->scheme_head</h4>";
        echo $scheme->scheme_rules;
    }

    // <====> Login & Registration Class Start <====>

    public function custoreg() {

        $active_menu = 'register';
        return view('ecomm.pages.register', ['activemenu' => $active_menu]);

    }

    public function custologin() {

        $active_menu = 'login';
        return view('ecomm.pages.login', ['activemenu' => $active_menu]);

    }

	
    public function custoforget(Request $request,$event=false){
        if($request->ajax()){
            if($event=='sendotp'){
                $users = Customer::where(['custo_fone'=>$request->mobile,'shop_id'=>$this->shop->shop_id])->pluck('id');
                if($users->count()==1){
                    $otp = rand(100000, 999999) ;
                    $data = ['OTP'=>$otp] ;
                    $mobile = $request->mobile;
                    //$sendOtp = true;
                    $this->txtsmssrvc = app('App\Services\TextMsgService');
                    $this->txtsmssrvc->shop_id = $this->shop->shop_id;
                    $this->txtsmssrvc->branch_id = $this->shop->id;
                    $this->txtsmssrvc->smssection=['main'=>'E_Comm','sub'=>'Customer Regitration !'];
                    $smssendresponse = $this->txtsmssrvc->sendtextmsg('OTP_VERIFICATION',"{$mobile}",["{$otp}"]);
                    $txt_msg_response = json_decode($smssendresponse['response'],true);
                    //dd($smssendresponse);
                    //$sendOtp = sendOtp('Registration',$mobile,$data);
                    session(['custo_forgot_otp' => $otp, 'custo_forgot_otp_mobile' => $mobile, 'custo_forgot_otp_expires_at' => now()->addMinutes(1)]) ;
                    //session(['otp' => $otp, 'otp_mobile' => $mobile, 'otp_expires_at' => now()->addSeconds(10)]) ;
                    $msg = "Trying...!";
                    //if($sendOtp){
                    if($txt_msg_response['return']){
                        return response()->json(['success'=>"OTP Send to Entered Mobile Number !"]);
                    }else{
                        return response()->json(['errors'=>'OTP Sending Failed !']);
                    }
                }elseif($users->count()>1){
                    return response()->json(['errors'=>"Unable To Proceed, Contact Provider !"]);
                }else{
                    return response()->json(['errors'=>["username"=>["Mobile number not Register !"]]]);
                }
            }
        }else{
            $active_menu = 'login';
            return view('ecomm.pages.forgot', ['activemenu' => $active_menu]);
        }
    }

    public function policy() {
 
        $activemenu = '';
        $obj = new EcommPrivacy ;

        $content = $obj->activecontent($this->shop->id);

        return view('ecomm.pages.privacy', compact('activemenu','content')) ;

    }

    public function terms() {
		//echo $this->shop->id;
		//exist();
        $activemenu = '';
        $obj = new EcommTerm ;

        $content = $obj->activecontent($this->shop->id);
		//dd($content);
        return view('ecomm.pages.tnc', compact('activemenu' , 'content')) ;

    }

	public function refundpolicy(){
        $activemenu = '';
        $obj = new EcommRefund ;

        $content = $obj->activecontent($this->shop->id);

        return view('ecomm.pages.refundpolicy', compact('activemenu' , 'content')) ;
    }

    public function desclaimer() {

        $activemenu = '';
        $obj = new EcommDesclaimer ;
        $content = $obj->activecontent($this->shop->id);
        return view('ecomm.pages.desclaimer', compact('activemenu' , 'content'));

    }


    public function shipingpolicy() {

        $activemenu = '';
        $obj = new EcommShiping ;
        $content = $obj->activecontent($this->shop->id);
        return view('ecomm.pages.shipingpolicy', compact('activemenu' , 'content'));

    }

	
    public function acdeletepolicy() {

        $activemenu = '';
        $obj = new EcommDelete ;
        $content = $obj->activecontent($this->shop->id);
        return view('ecomm.pages.acdeletepolicy', compact('activemenu' , 'content'));

    }

    public function location() {
        
        $active_menu = 'shop-location';
        return view('ecomm.pages.location', ['activemenu' => $active_menu]) ;

    }

	
    public function schemeenquiry($url_part){
		$shopscheme = ShopScheme::where('url_part',$url_part)->first();
        if(Auth::guard("custo")->check()){
           echo  view('ecomm.customer.partials.scheme_enquity',compact('shopscheme')) ;
        }else{
            echo  view('ecomm.customer.partials.scheme_enquity_login',compact('shopscheme')) ;
        }
    }

    public function sendschemeenquiry(Request $request){
        //print_r($request->all());
        $validator = Validator::make($request->all(), [
            "message" => 'required',
            'enroll_amount' => 'nullable|in:paid',
            'enq_utr' => 'exclude_unless:enroll_amount,paid|required_with:enq_amnt|string',
            'enq_amnt' => 'exclude_unless:enroll_amount,paid|required_with:enq_utr|numeric',
        ], [
            'message.required' => "Please Enter the Message !",
            'enroll_amount.in'=>'Invalid Selection Value',
            'enq_utr.required_with'=>'Enter the UTR code ! ',
            'enq_utr.string'=>'Invalid UTR ',
            'enq_amnt.required_with'=>'Enter the paid Amount ! ',
            'enq_amnt.numeric'=>'Invalid Amount ! ',
        ]);
        if ($validator->fails()) {
            return response()->json(['valid'=>false,'errors' => $validator->errors()]);
        }else{
            if(Auth::guard("custo")->check()){
				$scheme = ShopScheme::where('url_part',$request->scheme)->first();
                $input_arr = [
                    "custo_id"=>Auth::guard("custo")->user()->id,
                    "shop_id"=>$this->shop->shop_id,
                    "branch_id"=>$this->shop->id,
                    "scheme_id"=>$scheme->id,
                    "message"=>$request->message,
                ];
                if($request->enroll_amount && $request->enroll_amount=='paid'){
                    $input_arr['utr'] = $request->enq_utr;
                    $input_arr['amount'] = $request->enq_amnt;
                }
                $scheme_enquiry = SchemeEnquiry::create($input_arr);
                if($scheme_enquiry){

                        $customer = Auth::guard("custo")->user();

                        $adminUsers = User::where('shop_id', $this->shop->shop_id)->get();

                        $data = [
                            'customer_name'  => $customer->custo_full_name,
                            'customer_phone' => $customer->custo_fone,

                            'scheme_name' => $scheme->scheme_name,
                            'message'     => $request->message,

                            // paid enquiry (optional)
                            'utr'    => $request->enroll_amount === 'paid' ? $request->enq_utr : null,
                            'amount' => $request->enroll_amount === 'paid' ? $request->enq_amnt : null,

                            'link' => "https://justudhari.com/vendors/shopschemes/schemeenquiry"
                        ];

                        foreach ($adminUsers as $user) {
                            $user->notify(new \App\Notifications\SchemeEnquiryNotification($data));
                        }



                    return response()->json(["valid"=>true,'status'=>true,"msg"=>"Enquiry Send !"]);
                }else{
                    return response()->json(["valid"=>true,'status'=>false,"msg"=>"Enquiry Failed !"]);
                }
            }else{
                return response()->json(["valid"=>true,'status'=>false,"msg"=>"Please login First !"]);
            }
        }
    }

    public function customerregister(Request $request){

        $exist = Customer::where(['shop_id'=>$this->shop->shop_id,'custo_fone'=>$request->mobile])->first() ;

        if(@$exist->fone_varify == 1) {

            return response()->json(['success' => '','errors'=>'Already Registered with this Mobile No'] ) ;

        } 

            $rules = [
                "name" => "required|string",
                "mobile" => "required|numeric|digits:10",
                "address" => "required|string",
            ] ;

            if ($request->progress_step != 1) {

                $rules['password'] = 'required|string|min:4' ;
                $rules['otp'] = 'required|string|min:6' ;

            }

            // Custom error messages
            $messages = [
                "name.required"=>"Name required !",
                "mobile.required"=>"Mobile Number Required !",
                "mobile.numeric"=>"Mobile Number should be numeric !",
                "mobile.digits"=>"Mobile Number Must have 10 Digits !",
                "address.required"=>"Address is Required !",
                'password.required'=>"Password Can't Be Left Blank !",
                'password.string'=>"Password should be Valid String !",
                'otp.required'=>"OTP Required !",
                'otp.numeric'=>"OTP muse be Numeric !",
                'otp.digits_between'=>"OTP can be maximum 6 Digit Long !"
            ];

            $validator = Validator::make($request->all(), $rules, $messages) ;

            if($validator->fails()){

                return response()->json(['errors' => $validator->errors()], 422);

            }

            if ($request->progress_step == 1) {

                $otp = rand(100000, 999999) ;
                $data = ['OTP'=>$otp] ;

                $this->txtsmssrvc = app('App\Services\TextMsgService');
                $this->txtsmssrvc->shop_id = $this->shop->shop_id;
                $this->txtsmssrvc->branch_id = $this->shop->id;
                $this->txtsmssrvc->smssection=['main'=>'Customer E-Comm','sub'=>'Customer Registration !'];
                $smssendresponse = $this->txtsmssrvc->sendtextmsg('OTP_VERIFICATION',"{$request->mobile}",["{$otp}"]);
                $txt_msg_response = json_decode($smssendresponse['response'],true);

                //$sendOtp = sendOtp('Registration',$request->mobile,$data) ;
                //if(!$sendOtp) {

                if(!$txt_msg_response['return']) {
                    return response()->json(['success' => '', 'errors'=>'OTP Send Failed']) ;
                }else{

                    session(['otp' => $otp, 'otp_mobile' => $request->mobile_no, 'otp_expires_at' => now()->addMinutes(3)]) ;
                    return response()->json(['success' => 'OTP Send Successfully', 'errors'=>'']) ;

                }
            }
            $savedOTP = session('otp'); // Assuming you saved the OTP in the session as 'otp'
            $otpCreationTime = session('otp_expires_at'); // Assuming you saved the OTP in the session as 'otp'
            $enteredOTP = $request->otp ;

                $expiryTime = now() ;

                if ($otpCreationTime->lt($expiryTime)) {

                    $validator->errors()->add('otp', 'OTP has expired') ;
                    return response()->json(['errors' => $validator->errors()], 422) ;

                }

                if ($savedOTP != $enteredOTP) {

                    $validator->errors()->add('otp', 'Invalid OTP') ;
                    return response()->json(['errors' => $validator->errors()], 422) ;

                }
					$max_num = Customer::where('shop_id',$this->shop->shop_id)->max('custo_num')??1000;
                    $input['custo_unique'] = uniqid() . time();
                    $input['shop_id'] = $this->shop->shop_id;
                    $input['branch_id'] = $this->shop->id;
                    $input["custo_full_name"]= $request->name;
					$input['custo_num']=$max_num+1;
                    $input["custo_fone"]= $request->mobile;
                    $input["custo_address"]= $request->address;
                    $input["password"] = Hash::make($request->password);
                    $input['fone_varify'] = '1' ;

                    $is_save = (!empty($exist) && $exist->fone_verify==0)?$exist->update($input):Customer::create($input);

                    if($is_save){
						 $credencial_array = [
                            "custo_fone" => $request->mobile,
                            "password" => $request->password,
                            "shop_id"=>$this->shop->shop_id
                        ] ;
                        Auth::guard('custo')->attempt($credencial_array);
                        return response()->json(['success' => 'Register Succesfull Now You Can Login !','errors'=>''] ) ;
                    }else{
                        return response()->json(['errors' => 'Registration Failed !'], 422) ;
                    }

    }

    public function sendotp(Request $request){

        $otp = rand(100000, 999999) ;
        $data = ['OTP'=>$otp] ;
        $this->txtsmssrvc = app('App\Services\TextMsgService');
        $this->txtsmssrvc->shop_id = $this->shop->shop_id;
        $this->txtsmssrvc->branch_id = $this->shop->id;
        

        $this->txtsmssrvc->smssection=['main'=>'Customer E_Comm','sub'=>'Customer Regitration OTP Resend !'];
        $smssendresponse = $this->txtsmssrvc->sendtextmsg('OTP_VERIFICATION',"{$request->mobile}",["{$otp}"]);
        $txt_msg_response = json_decode($smssendresponse['response'],true);

        //$sendOtp = sendOtp('Registration',$request->mobile,$data) ;
        //if(!$sendOtp) {
        if(!$smssendresponse['return']) {

            return response()->json(['success' => '', 'errors'=>'OTP Send Failed']) ;

        }else{

            session(['otp' => $otp, 'otp_mobile' => $request->mobile, 'otp_expires_at' => now()->addMinutes(3)]) ;

            return response()->json(['success' => 'OTP Send Successfully', 'errors'=>'']) ;

        }

    }

    public function attemptlogin(Request $request){
        // echo Hash::make("allow");
        // echo "<br>";
        // echo "<br>";
        // echo Hash::make($request->password);
        // exit();
		//print_r($this->shop);
		//exit();
        $validator = Validator::make($request->all(), [
            "username" => "required|numeric|digits:10",
            "password" => 'required'
        ], [
            'username.required' => "Username Is Required !",
           'username.numeric' => "Username Should Be A valid Mobile Number !",
            'username.digits' => "Mobile Number Must Have 10 Digits !",
            'password.required' => "Passowrd Is Required !",
        ]);

        if ($validator->fails()) {
            return response()->json(['valid'=>false,'errors' => $validator->errors()]);
        }
        
        // echo Hash::make($request->password);
        // exit();

        $credencial_array = [
            "custo_fone" => $request->username,
            "password" => $request->password,
            "shop_id"=>$this->shop->shop_id
        ] ;
		//print_r($credencial_array);
		//exit();
	
        if (Auth::guard('custo')->attempt($credencial_array)) {
            if (Auth::guard('custo')->check()) {
                $request->session()->flash("success","Succesfully Loggedin !");
                return response()->json(['valid'=>true,"status"=>true,'msg' => 'Succesfully Loggedin !']);
            }else{
                return response()->json(['valid'=>true,"status"=>false,'msg' => 'FAiled to Login !']);
            }
        }else{
            return response()->json(['valid'=>true,"status"=>false,'msg' => 'Invalid Credencial !']);
        }
    }

	
    public function proceedforget(Request $request){
        //print_r($request->all());
        $validator = Validator::make($request->all(), [
            'username' => 'required|digits:10',
            'otp'=>'required|digits:6',
            'create' => 'required|string|min:8',
            'confirm' => 'required|min:8|same:create',
        ],[
           'username.required'=>'Mobile number Required !', 
           'username.digit'=>'Please Enter 10 Digit valid Number !', 
           'otp.required'=>'Please Enter The OTP !', 
           'otp.digit'=>'OTP must be 6 digit Long !', 
           'create.required'=>'New password Required !', 
           'create.string'=>'Enter a  Valid New password', 
           'create.min'=>'Password should be atleast 8 character long !',  
           'confirm.min'=>'Confirm Password Must be Same as New Password', 
           'confirm.same'=>'Confirm Password Must be Same as New Password', 
        ]) ;
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }else{
            $custo = Customer::where(['custo_fone'=>$request->username,'shop_id'=>$this->shop->shop_id])->pluck('id');
            if($custo->count()==1){
                $savedOTP = session('custo_forgot_otp'); 
                $otpCreationTime = session('custo_forgot_otp_expires_at');
                $expiryTime = now() ;
                if (!isset($otpCreationTime) || $otpCreationTime->lt($expiryTime)) {
                    session()->forget('custo_forgot_otp');
                    session()->forget('custo_forgot_otp_expires_at');
                    return response()->json(['errors' =>['otp'=>["OTP Expired !"]],'expire'=>true ]) ;
                }elseif($savedOTP == $request->otp){
                    session()->forget('custo_forgot_otp');
                    session()->forget('custo_forgot_otp_expires_at');
                    $new_pass = bcrypt($request->confirm);
                    $custo_now = Customer::find($custo[0]);
                    if($custo_now->update(['password'=>$new_pass])){
                        return response()->json(['success' =>"Password Succesfully Updated !"]) ;
                    }else{
                        return response()->json(['errors' =>"Password Updation Failed !"]) ;
                    }
                }
            }elseif($custo->count()>1){
                return response()->json(['errors' =>"Unable To Proceed, Contact Provider !"]) ;
            }else{
                return response()->json(['errors' =>"Mobile Number Not Registered !"]) ;
            }
        }
    }

    public function logout(){
        $response =  redirect()->intended("{$this->view_route}login");
        if (Auth::guard("custo")->check()) {
            Auth::guard("custo")->logout();
            if (Auth::guard("custo")->check()) {
                $response =  redirect()->back()->with('error', "Unable to procedue your REQUEST !");
                echo "logout done";
            }
        }
        return $response;
    }

    // <====> Login & Registration Class End <====>

    public function addwishlist(Request $request,$url=null){
        return $this->addtoshoppinglist($url,0,$request);
    }
    public function removefromwishlist($id=null){
        return $this->removefromshoppinglist($id,0); 
    }
    public function addkart(Request $request,$url=null)
    {
        return $this->addtoshoppinglist($url,1,$request); 
    }
    public function removefromkart($id=null){
        return $this->removefromshoppinglist($id,1);
    }
    private function addtoshoppinglist($url,$type,Request $request){
        $bool = false;
        $count = 0;
        $msg = "Trying..!";
        $list = ["Wishlist","Cart"];
		$redirect = true;
        if(Auth::guard('custo')->check()){
            //$product = EcommProduct::select('id','rate')->where('url',$url)->first();
			$product = EcommProduct::where(['url'=>$url,"shop_id"=>auth('custo')->user()->shop_id])->first();
            if(!empty($product)){
				$user = Auth::guard('custo')->user()->id;
				$count = ShoppingList::where(['shop_id'=>$this->shop->shop_id,'custo_id'=>$user,'list_type'=>"$type","product_id"=>$product->id,"product_url"=>$url])->count();
				
				if($count==0){
					$stock = $product->stock;
					if($stock->item_type=='genuine'){
						$cat_name = strtolower($stock->category->name);
						$ini_rate = app("{$cat_name}_rate")/24;
						$rate = $stock->caret*$ini_rate;
						$quant = $stock->available;
					}elseif($stock->item_type=='artificial'){
						$rate = $product->rate;
						$quant = $request->quant??1;
					}
					$data_arr = [
						"product_id"=>$product->id,
						"product_url"=>$url,
						"shop_id"=>$this->shop->shop_id,
						"branch_id"=>$this->shop->id,
						"curr_cost"=>$rate,
						"quantity"=>$quant,
						"custo_id"=>$user,
						"list_type"=>"$type",
					];              
					if(ShoppingList::create($data_arr)){
						$bool = true;
						$count = ShoppingList::where(['shop_id'=>$this->shop->shop_id,'custo_id'=>$user,'list_type'=>"$type"])->count();
						$msg = "Succesfully Added to {$list[$type]}";
					}else{
						$msg = "Failed to add to {$list[$type]}";
					}
				}else{
					$msg = "Already Added to {$list[$type]}!";
				}
            }else{
                $msg = "Product Not Found !";
            }
			$redirect = false;
        }else{
            $msg = "Unable to add to {$list[$type]}, You need to Login !";
			//session()->flash('error', 'Please login First !');
        }
        return response()->json(['status'=>$bool,'msg'=>$msg,'data'=>"{$type}#{$count}","redirect"=>$redirect]);
    }
    
    public function shiftwishlisttokart($id=null){
        $bool = false;
        $count = 0;
        $msg = "Trying..!";
        if(Auth::guard('custo')->check()){
            $product = ShoppingList::find($id);
            if(!empty($product)){    
                if($product->update(["list_type"=>'1'])){
					$user = Auth::guard('custo')->user()->id;
					$count = ShoppingList::where(['shop_id'=>$this->shop->shop_id,'custo_id'=>$user,'list_type'=>0])->count();
                    $bool = true;
                    $msg = "Product Move to Cart Succesfully !";
                }else{
                    $msg = "Failed to Move Product to Cart!";
                }
            }else{
                $msg = "Product Not Found !";
            }
        }else{
            $msg = "Unable to Move to Cart !";
        }
        return response()->json(['status'=>$bool,'msg'=>$msg,'data'=>"0#{$count}"]);
    }

    private function removefromshoppinglist($id,$type){
        $bool = false;
        $count = 0;
        $msg = "Trying..!";
        $list = ["Wishlist","Cart"];
        if(Auth::guard('custo')->check()){
            $product = ShoppingList::find($id);
            if(!empty($product)){       
                $user = Auth::guard('custo')->user()->id;      
                if($product->delete()){
                    $bool = true;
                    $count = ShoppingList::where(['shop_id'=>$this->shop->shop_id,'custo_id'=>$user,'list_type'=>"$type"])->count();
                    $msg = "Succesfully Removed From {$list[$type]}";
                }else{
                    $msg = "Failed to Remove from {$list[$type]}";
                }

            }else{
                $msg = "Product Not Found !";
            }
        }else{
            $msg = "Unable to Remove from {$list[$type]}, You need to Login !";
        }
        return response()->json(['status'=>$bool,'msg'=>$msg,'data'=>"{$type}#{$count}"]);
    }
    
    public function getshoppinglistcount(){
		$wish = $kart = 0;
		if(Auth::guard('custo')->check()){
			$user_id = Auth::guard('custo')->user()->id;
			$wish = ShoppingList::where(['custo_id'=>$user_id,'shop_id'=>$this->shop->shop_id,'list_type'=>'0'])->count();
			$kart = ShoppingList::where(['custo_id'=>$user_id,'shop_id'=>$this->shop->shop_id,'list_type'=>'1'])->count();
			
		}
		return response()->json(["wish_list"=>$wish,"kart_list"=>$kart]) ;
    }

    public function get_districts(Request $request) {
        $state = $request->input('state') ;
        $districts = District::where('state_code',$state)->select('name','code')->get() ;
        return response()->json($districts) ;
    }

	public function getcurrentrate(){
        $rate = Rate::where(["shop_id"=>$this->shop->shop_id,'active'=>'1'])->first();
        $gold_rate = $rate->gold_rate;
        $one_k = $gold_rate/24;
        $k_18 = 18 * $one_k;
        $k_20 = 20 * $one_k;
        $k_22 = 22 * $one_k;
        $silver_rate = $rate->silver_rate;
        $silver_1g = $silver_rate/1000;
        $silver_10g = $silver_1g * 10;
        $rates_arr = [
            'gold'=>[
                '24k'=>$gold_rate,
                '22k'=>$k_22,
                '20k'=>$k_20,
                '18k'=>$k_18
                ],
            'silver'=>[
				'1kg'=>$silver_rate,
                '10g'=>$silver_10g,
                '1g'=>$silver_1g,
                ],
			'date'=>date('d-m-Y',strtotime($rate->updated_at))
            ];
        return response()->json(compact('rates_arr'));
    }
}
