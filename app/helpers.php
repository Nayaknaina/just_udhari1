<?php

use App\Models\State ;
use App\Models\District ;
use App\Models\OtpTemplate ;
use App\Models\Supplier ;
use App\Models\Category ;
use App\Models\Counter ;
use App\Models\ShopRight;
use App\Models\Shop;
use App\Models\ShopBranch;
use App\Models\ShopScheme ;
use App\Models\SchemeEnquiry ;
use App\Models\GstInfo;
use App\Models\Banking;
use App\Models\JustBill;
use App\Models\StockItemGroup;
use Illuminate\Http\Request ;
use Illuminate\Http\UploadedFile ;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

session_start() ;

function isActive($path,$params = []){
   // First check the route name
   $output = false;
    /*if (!request()->routeIs($routes)) {
        return '';
    }

    // If params are passed, check them too
    foreach ($params as $key => $value) {
        if (request($key) != $value) {
            return '';
        }
    }*/

    foreach ((array) $path as $pattern) {
        // 1) Check against route names
        if (request()->routeIs($pattern)) {
            $output =  true;
        }

        // 2) Check against path (includes prefix "vendors/ecommerce")
        if (request()->is($pattern)) {
            $output =  true;
        }
    }
    if($output){
        foreach ($params as $key => $value) {
            if (request($key) != $value) {
                return '';
            }
        }
    }

    return ($output)?'active':'';
}

function domaincheck() {

    $dev = true ;

    $domain = '127.0.0.1:8000' ;

    if($dev) {

        $host = request()->path() ;
        // session(['domain'=>$host]) ;

        if (strpos($host, 'ecom1') !== false) {

            return true ;

        }else {

            return false  ;

        }

    }else{

       $host = request()->getHost() ;

        // session(['domain'=>$host]) ;

        $scheme = request()->getScheme() ;
        $fullUrl = $scheme . '://' . $host ;

        if($domain == $host) {

            return true ;

        }else {

            return false  ;

        }
    }


}

function user_rights_check($request , $permission){

    $user = Auth::user() ;

    $permission_denied = '' ;
    $ecommerce_denied = '' ;
    $subscription_expire = '' ;

    if (!$user->hasPermissionTo($permission)) {

        if($permission == 'Ecommerce Portal') {

            $ecommerce_denied = 'No Ecommerce Portal Subscription' ;

        }else{

            $permission_denied = 'You do not have permission to access this module.' ;

        }

    }else {

            $current_date = new DateTime() ;
            $expiry_date = [] ;
            $arr_permission = [] ;

            // Collecting data
            foreach (@$user->subscriptions as $subscription) {

                $title = $subscription->product->title ;
                $expiry = $subscription->expiry_date ;
                $product_id = $subscription->product->id;

                $shopRights = ShopRight::where('shop_id', $user->shop_id)
                ->where('product_id', $product_id)->with('permission')->get();

                if(!$shopRights) {

                    foreach ($subscription->product->roles->permissions as $gt_permission) {
                        array_push($arr_permission, [
                        'title' => $title,
                        'expiry_date' => $expiry,
                        'permission_name' => $gt_permission->name
                        ]);
                    }

                }else{

                    foreach ($shopRights as $shopRight) {
                        $permissionName = $shopRight->permission->name ;
                        array_push($arr_permission, [
                            'title' => $title, // Assuming $title is defined elsewhere in your code
                            'expiry_date' => $expiry, // Assuming $expiry is defined elsewhere in your code
                            'permission_name' => $permissionName
                        ]);
                    }
                }
            }

            $given_permission_name = $permission ; // Replace with the actual permission name

            $filtered_subscriptions = array_filter($arr_permission, function ($item) use ($given_permission_name) {

                $expiry_date = new DateTime($item['expiry_date']);
                return $item['permission_name'] === $given_permission_name ;

            }) ;

            if(empty($filtered_subscriptions)) {

                $subscription_expire = 'Don`t have a Subscription for this module' ;

            } else{

                $filtered_expiry = array_filter($filtered_subscriptions, function ($item) use ($current_date) {
                    $expiry_date = new DateTime($item['expiry_date']);
                    return $expiry_date >= $current_date ;
                });

                if(empty($filtered_expiry)) {

                    $subscription_expire = 'Your Subscription for this module is Expired'  ;

                }
            }
    }

    return [ 'permission_denied' => $permission_denied , 'subscription_expire' => $subscription_expire,'ecommerce_denied' => $ecommerce_denied] ;

}

// function branch_check(){

//   $branch = ShopBranch::where(['branch_type'=>0 , 'shop_id'=>app('userd')->branch_id])->count() ;

//   return $branch > 0 ? true : false ;

// }

// Under Construction

function module_right_view($permission) {

    $roles = $user->getRoleNames() ; // Returns a collection

    return ($permission =='All Module View' || auth()->user()->can($permission) )  ? true : false ;

}

function convertToSeoUrl($title) {

    // Convert the title to lowercase
    $seoUrl = strtolower($title);
    // Replace non-alphanumeric characters with hyphens
    $seoUrl = preg_replace('/[^a-z0-9]+/i', '-', $seoUrl);
    // Remove leading and trailing hyphens
    $seoUrl = trim($seoUrl, '-');
    return $seoUrl;

}

function states($data='',$row=false){
    if($data) {
        $state_dt = State::where('code',$data)->select('name','code')->first() ;
        $states = $state_dt->name ;
		 if($row){
            return $state_dt;
        }
    }else{
        $states = State::select('name','code')->get() ;
    }

   return $states ;

}

/*function districts($state,$data=''){
    if($data) {
        $district_dt = District::where('state_code',$state)->select('name','code')->first() ;
        $districts = $district_dt->name ;
    }else{
        $districts = District::where('state_code',$state)->select('name','code','id')->get() ;
    }
   return $districts ;

}*/

function districts($state,$data=''){
    if($data) {
        $district_dt = District::where('code',$data)->select('name','code')->first() ;
        $districts = $district_dt->name ;
    }else{
        $districts = District::where('state_code',$state)->select('name','code','id')->get() ;
    }
   return $districts ;

}

function sendOtp( $tempName , $mobile_no , $data = [] ) { 

    $otp_temp = OtpTemplate::where(['title'=>$tempName, 'status'=>0])->select('template_content')->first() ;
	//print_r($data);
	//exit();
    if($otp_temp) {

		$template_content = $otp_temp->template_content ;
		//print_r($data);
		//exit();
		if($data) {

			$r_key = [] ; $r_value = [] ;

			foreach ($data as $key => $placeholder) {
				array_push($r_key , '#'.$key.'#') ;
				array_push($r_value , $placeholder) ;
			}

			$message_content = str_replace($r_key,$r_value,$template_content) ;

			}else{ $message_content  = $template_content ; }

		// $message_content = str_replace('#OTP#', $otp, $template_content) ;
		
		$apiurl = "https://www.fast2sms.com/dev/bulkV2?authorization=tIfH1p8hJyaAiseUWSkq4bGEZlNOT3cFdurQRx0L972KwjmPMCcIY5GKEz3ZCrWwHQstfTV4uoqAyBaR&route=dlt&sender_id=HAMSOL&message=204926&variables_values={$data['OTP']}&flash=0&numbers=".$mobile_no;
		
		//$apiurl = "https://www.fast2sms.com/dev/bulkV2?authorization=tIfH1p8hJyaAiseUWSkq4bGEZlNOT3cFdurQRx0L972KwjmPMCcIY5GKEz3ZCrWwHQstfTV4uoqAyBaR&route=dlt&sender_id=HAMSOL&message=177070&variables_values={$data['OTP']}&flash=0&numbers=".$mobile_no;
		
		//$apiurl="http://msg.websoftvalley.com/V2/http-api.php?apikey=M7SH66CDN49gyWmZ&senderid=HAMSOL&number=".$mobile_no."&message=".urlencode($message_content)."&format=json";
			$sms = false;
			$res = curl_init();
			curl_setopt( $res, CURLOPT_URL, $apiurl);
			curl_setopt( $res, CURLOPT_RETURNTRANSFER, true );
			$result = curl_exec( $res );
			//$data1="[".$result."]";
			//$sms_array = json_decode($data1, true);
			$sms_array = json_decode($result, true);
			//print_r($sms_array);
			//exit();
			
			/*foreach ($sms_array as $sms_data){
				if($sms_data['status']!='no'){
					$count=count($sms_data['data']);
					for($i=0;$i<$count;$i++){
						$sms = $sms_data['data'][$i]['status'];
					}
				 }
			}*/
			
			/*foreach ($sms_array['message'] as $res_message){
				echo $res_message;
			}*/
			$sms = $sms_array['return'];
		if($sms){ return true ; }else{ return false ; }

	}

}

function MyBranchwhere($query,$type=1){

    if($type==2) {

        $query->where('shop_id',app('userd')->shop_id) ;

    }else{

        $query->where('shop_id',app('userd')->shop_id) ;
        $query->where('id',app('userd')->branch_id) ;

    }

    return $query ;

}

function ShopBranchwhere($query,$type=1){

    if($type==2) {

        $query->where('shop_id',app('userd')->shop_id) ;

    }else{

        $query->where('shop_id',app('userd')->shop_id) ;
        $query->where('branch_id',app('userd')->branch_id) ;

    }

    return $query ;

}

function Shopwhere($query){
	//echo app('userd')->shop_id;
    $query->where('shop_id',app('userd')->shop_id) ;

    return $query ;

}

function querybuildmain($query,$cond = [],$type=1){

    // type = 1  means shop id and branch id both in condition defualt
    //  2 for only shop id

    foreach ($cond as $key => $cd) {

        $query->where($cd['field'], $cd['operator'], $cd['value']);

    }

    ShopBranchwhere($query,$type) ;

    return $query ;

}

function category_label($id){

    if($id==1) { $rt = 'Metal' ;  } elseif($id==2) { $rt = 'Collections' ;  }else { $rt = 'Category' ;  }

    return $rt ;

}

function supplier(){

    $query = Supplier::select('id','supplier_name') ;

    ShopBranchwhere($query) ;

    $suppliers = $query->get() ;

    return $suppliers ;

}

function categories($level,$art=false){

    $query = Category::where('category_level',$level)->select('id','name') ;
	if(!$art){
        $query->where('name','!=','artificial');
    }
    $suppliers = $query->get() ;

    return $suppliers ;

}

function stonecategory(){
    $parent_id = Category::where(['name'=>'stone','category_level'=>1])->pluck('id');
    $query = Category::where(['category_level'=>2,'parent_id'=>$parent_id])->select('id','name');
    $categories = $query->get() ;
    return $categories ;
	return []; 
}

function counters(){

    $query = Counter::select('id','name') ;

    $counters = $query->get() ;

    return $counters ;

}

function generateUniqueImageName(UploadedFile $file, $folder , $npath = 1) {

    // npath if 1 file name without path

    // Get the file extension
    $extension = $file->extension() ;

    // Generate the base file name (without extension)
    $baseName = time() . rand() ;

    // Define the initial file name with extension
    $fileName = $baseName . '.' . $extension ;

    // Define the path to the directory
    $path = public_path($folder) ;

    // Initialize a counter
    $counter = 1;

    // Check if the file name already exists in the directory
    if(file_exists($path . '/' . $fileName)) {
        // Generate a new file name by appending the counter
        $fileName = $baseName . '_' . $counter . '.' . $extension;
        // Increment the counter
        $counter++;
    }

    // return ($npath==2) ? $fileName : $folder.$fileName ;

    // Return the unique file name
    return $fileName ;

}

function generateRandomAlphanumeric($length = 10) {

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString ;

}

function getInitials($string) {

    if($string) {

        // Split the string into words
        $words = explode(' ', $string);

        // Get the first letter of each word
        $initials = '';
        foreach ($words as $word) {
            $initials .= strtoupper($word[0]);
        }

        return $initials ;

    }else {

        return null ;

    }

}

function component_array($cm_key , $title , $arr  = [] , $assos = true ) {

    if ($assos) {
        $myDataArray = [
            $cm_key => [
                'title' => $title,
                'sub_title' =>[$arr]
            ]
        ];
    } else {
        $myDataArray = [
            $cm_key => [
                'title' => $arr,
            ]
        ];
    }

    $data = json_encode($myDataArray) ;
    return $data ;

}

function new_component_array($cm_key , $title , $arr  = [] , $assos = true ) {

    if ($assos) {
        $myDataArray = [
            $cm_key => [
                'title' => $title,
                'sub_title' =>$arr
            ]
        ];
    } else {
        $myDataArray = [
            $cm_key => [
                'title' => $arr,
            ]
        ];
    }

    $data = json_encode($myDataArray) ;
    return $data ;

}

function delete_image ($image){

    $imagePath = public_path($image) ;

    if (File::exists($imagePath)) {

        // File::delete($imagePath) ;
        unlink($imagePath) ;

    }

}

function shop_data(){

  $shop = Shop::where('id',app('userd')->shop_id)->first() ;

  return $shop ;

}

function role_prefix(){

    $shop = shop_data() ;
    $role_suffix = $shop->role_suffix.'_' ;

  return $role_suffix ;

}

function role_prefix_remover($role_name){

    $role_suffix = role_prefix() ;

    if (strpos($role_name, $role_suffix) !== false) {
        $cleanedString = str_replace($role_suffix, '', $role_name);
    } else {
        $cleanedString =  $role_name ;
    }

    echo  $cleanedString ;

}


///////////------------96 Written Code--------------------------/////

function schememenu($shop_id){

    return  ShopScheme::with('schemes')->where('shop_id',$shop_id)->get();

}

function date_range_extract($query,$date,$field){

      $dates = explode(' - ', $date);

      if (count($dates) == 2) {

          $fromDate = date('Y-m-d',strtotime($dates[0])) ;
          $toDate = date('Y-m-d',strtotime($dates[1])) ;

         $query->whereBetween($field, [$fromDate, $toDate]) ;

      }

}

function scheme_interest_show($interest , $type , $emi_amt ,$gt_interest_value ){

    $n_interest_value = ($type == 'per' ) ? ($interest ) : $gt_interest_value ;

    $scheme_interest_value = $n_interest_value ;

    return $scheme_interest_value ;

}
function enquirycount(){
    //$count =SchemeEnquiry::where('status','00')->count();
    $count =SchemeEnquiry::where(['status'=>'00','shop_id'=>auth()->user()->shop_id])->count();
    return $count??0;
}

function justbillsequence(){
    //echo str_pad(000000 + 1, 6, '0', STR_PAD_LEFT);
    $sequence = JustBill::where('custom','0')->max('bill_no');
    $max_bill_num = ($sequence)?$sequence:0;
    $bill_num = str_pad($max_bill_num + 1, 6, '0', STR_PAD_LEFT);
    return $bill_num;
}

function justbillhsn($option=false){
    $hsfs = GstInfo::where('status','1')->orderby('id','desc')->get();
    if($option){
        if($hsfs->count()>0){
            foreach($hsfs as $hk=>$hsf){
                echo "<option value='$hsf->hsf' data-target='$hsf->gst'>$hsf->hsf</option>";
            }
        }else{
            echo "<option value='' data-target=''>No Data !</option>";
        }
    }else{
        return $hsf;
    }
}

function justbillbanking($type='all'){
    $bank_query = Banking::where('status','1')->orderby('id','desc')->limit(1);
    $bank_data = $bank_query->where('for',$type)->first();
    return $bank_data;
}
function getgstval(){
    return 3;
}

function todayrate($name,$caret){
    $name = strtolower($name);
    $caret_rate = app("{$name}_rate");
    $one_caret = $caret_rate/24;
    return round($one_caret*$caret,2);
}

function todayratebyid($id,$caret){
    $name = Category::find($id)->pluck('name')->name;
    return $this->todayrateby($name,$caret);
}


/*function generateidcode($model,$column,$name_prefix="",$type=false){
    $prev_arr = explode(" ",shop_data()->shop_name);
    $count = count($prev_arr);
    $first_char = $prev_arr[0][0];
    $second_char = ($count>1)?$prev_arr[$count-1][0]:null;
    $prefix = strtoupper($first_char.$second_char);
    $num_code = false;
    do {
        $num_code = $name_prefix.str_pad(random_int(1, 999999), 6, '0', STR_PAD_LEFT).$prefix;
    } while ($model::where($column,$num_code)->exists());
    return $num_code;
}*/

function generateidcode($model,$column,$name_prefix="",$type=false){
    $prev_arr = explode(" ",shop_data()->shop_name);
    $count = count($prev_arr);
    $first_char = $prev_arr[0][0];
    $second_char = ($count>1)?$prev_arr[$count-1][0]:null;
    $prefix = strtoupper($first_char.$second_char);
    $num_code = false;
    do {
        $num_code = $name_prefix.str_pad(random_int(1, 999999), 6, '0', STR_PAD_LEFT).$prefix;
    } while ($model::where($column,$num_code)->exists());
    return $num_code;
}


function itemgroups(){
    $groups = StockItemGroup::where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->orderBy('item_group_name','ASC')->get();
    return $groups;
}

function numberToWords($num) {
    $ones = [
        0 => "zero", 1 => "one", 2 => "two", 3 => "three", 4 => "four",
        5 => "five", 6 => "six", 7 => "seven", 8 => "eight", 9 => "nine",
        10 => "ten", 11 => "eleven", 12 => "twelve", 13 => "thirteen",
        14 => "fourteen", 15 => "fifteen", 16 => "sixteen", 17 => "seventeen",
        18 => "eighteen", 19 => "nineteen"
    ];

    $tens = [
        20 => "twenty", 30 => "thirty", 40 => "forty", 50 => "fifty",
        60 => "sixty", 70 => "seventy", 80 => "eighty", 90 => "ninety"
    ];

    $scales = ["", "thousand", "million", "billion", "trillion"];

    if ($num == 0) return "zero";

    $numStr = str_pad($num, ceil(strlen($num) / 3) * 3, "0", STR_PAD_LEFT);
    $groups = str_split($numStr, 3);

    $words = [];
    foreach ($groups as $index => $group) {
        $groupNum = intval($group);
        if ($groupNum == 0) continue;

        $groupWords = [];
        $hundreds = intdiv($groupNum, 100);
        $remainder = $groupNum % 100;

        if ($hundreds > 0) {
            $groupWords[] = $ones[$hundreds] . " hundred";
            if ($remainder > 0) $groupWords[] = "and";
        }

        if ($remainder > 0) {
            if ($remainder < 20) {
                $groupWords[] = $ones[$remainder];
            } else {
                $tenVal = intdiv($remainder, 10) * 10;
                $unitVal = $remainder % 10;
                $groupWords[] = $tens[$tenVal];
                if ($unitVal > 0) $groupWords[] = $ones[$unitVal];
            }
        }

        $scaleIndex = count($groups) - $index - 1;
        if ($scaleIndex > 0) $groupWords[] = $scales[$scaleIndex];

        $words[] = implode(" ", $groupWords);
    }

    return implode(" ", $words);
}

function getallbanks($section=false){
    $use_case = ['all'];
    if($section){
        array_push($use_case,$section);
    }
    $bank_query = Banking::where(['status'=>'1','shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->orderby('name','desc');
    $bank_data = $bank_query->whereIn('for',$use_case)->get();
    return $bank_data;
}

function itemtagstream($item_data){
    if(!empty($item_data)){
        $stock_matterial = strtolower($item_data->stock_type);
        $stock_matterial = (($stock_matterial=='franchise-jewellery')?'franchise':$stock_matterial).'tagstream';
       return  $stock_matterial($item_data);
    }else{
        return null;
    }
}

function goldtagstream($item_data){
    $entry_mode = strtolower($item_data->entry_mode);
    if($entry_mode!='loose'){
        $tag_gross = ($item_data->avail_gross)?number_format($item_data->avail_gross,3):0;
        $zpl = "^XA";
        $zpl.="^FO100,15^A0N,20,20^FD".$item_data->name."^FS";
        $tag_net = ($item_data->avail_net)?number_format($item_data->avail_net,3):0;
        $tag_less = number_format(($tag_gross-$tag_net),3);
        $huid = ($item_data->huid!="")?"HUID: {$item_data->huid}":"";
        $tag_stchrg = ($item_data->element_charge!="")?"St.Chrg: {$item_data->element_charge}/-Rs.":"";
        $zpl.="^FO100,45^A0N,18,18^FDGross: ".($tag_gross??'-')."gm^FS";
        $zpl.="^FO100,65^A0N,18,18^FDLess: ".($tag_less??'-')."gm^FS";
        $zpl.="^FO100,85^A0N,18,18^FDNet: ".($tag_net??'-')."gm^FS";
        $zpl.="^FO300,45^A0N,18,18^FDTag: ".($item_data->tag??'xxxxxx')."^FS";
        if($tag_stchrg!=""){
            $zpl.="^FO300,65^A0N,18,18^FD".($tag_stchrg)."^FS";
        }elseif($huid!=""){
            $zpl.="^FO300,65^A0N,18,18^FD".($huid)."^FS";
        }
        $zpl.="^FO300,85^A0N,18,18^FDKarat: ".($stock->caret??'-')."K^FS";
        $zpl.="^FO440,20^BQN,3,3"; 
        $zpl.="^FDLA,".($item_data->tag??'xxxxxx')."^FS";   
        $zpl.="^XZ";
        return $zpl;
    }else{
        return null;
    }
}

function silvertagstream($item_data){
    $entry_mode = strtolower($item_data->entry_mode);
    if($entry_mode!='loose'){
        $tag_gross = ($item_data->avail_gross)?number_format($item_data->avail_gross,3):0;
        $zpl = "^XA";
        $zpl.="^FO100,15^A0N,20,20^FD".$item_data->name."^FS";
        $tag_net = ($item_data->avail_net)?number_format($item_data->avail_net,3):0;
        $tag_less = number_format(($tag_gross-$tag_net),3);
        $huid = ($item_data->huid!="")?"HUID: {$item_data->huid}":"";
        $tag_stchrg = ($item_data->element_charge!="")?"St.Chrg: {$item_data->element_charge}/-Rs.":"";
        $zpl.="^FO100,45^A0N,18,18^FDGross: ".($tag_gross??'-')."gm^FS";
        $zpl.="^FO100,65^A0N,18,18^FDLess: ".($tag_less??'-')."gm^FS";
        $zpl.="^FO100,85^A0N,18,18^FDNet: ".($tag_net??'-')."gm^FS";
        $zpl.="^FO300,45^A0N,18,18^FDTag: ".($item_data->tag??'xxxxxx')."^FS";
        if($tag_stchrg!=""){
            $zpl.="^FO300,65^A0N,18,18^FD".($tag_stchrg)."^FS";
        }elseif($huid!=""){
            $zpl.="^FO300,65^A0N,18,18^FD".($huid)."^FS";
        }
        $zpl.="^FO300,85^A0N,18,18^FDKarat: ".($item_data->caret??'-')."K^FS";
        $zpl.="^FO440,20^BQN,3,3"; 
        $zpl.="^FDLA,".($item_data->tag??'xxxxxx')."^FS";   
        $zpl.="^XZ";
        return $zpl;
    }else{
        return null;
    }
}

function stonetagstream($item_data){
    $entry_mode = strtolower($item_data->entry_mode);
    if($entry_mode!='loose'){
        $tag_gross = ($item_data->avail_gross)?number_format($item_data->avail_gross,3):0;
        $zpl = "^XA";
        $zpl.="^FO100,25^A0N,20,20^FD".$item_data->name."^FS";
        $zpl.="^FO100,55^A0N,18,18^FDWt.: ".(number_format($tag_gross,2)??'-')."CT^FS";
        $zpl.="^FO100,75^A0N,18,18^FDTag: ".($item_data->tag??'xxxxxx')."^FS";
        $zpl.="^FO300,55^A0N,18,18^FDMRP:^FS";
        $zpl.="^FO300,75^A0N,18,18^FD".($item_data->rate??'-')."Rs^FS";
        $zpl.="^FO440,20^BQN,3,3"; 
        $zpl.="^FDLA,".($item_data->tag??'xxxxxx')."^FS";   
        $zpl.="^XZ";
        return $zpl;
    }else{
        return null;
    }
}

function artificialtagstream($item_data){
    $entry_mode = strtolower($item_data->entry_mode);
    return null;
    /*if($entry_mode!='loose'){
        
    }*/
}

function franchisetagstream($item_data){
    $entry_mode = strtolower($item_data->entry_mode);
    return null;
}