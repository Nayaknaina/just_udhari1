<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Stock;
use App\Models\Category;
use App\Models\StockCategory;
use Illuminate\Support\Facades\DB;

class EcommHome extends Model {

    use HasFactory ;
    use HasFactory ;

    protected $table = "ecomm_home" ;
    protected $primaryKey = 'home_id' ;

    protected $fillable = ["home_id","home_unique","home_content","home_status","branch_id","shop_id"] ;

    public function content($vendor_id = null){

        return $this->where('shop_id',$vendor_id)->first() ;

    }

    public function activecontent($branch_id = null){

        return $this->where('branch_id',$branch_id)->first();

    }
	
	public function categories($branch = null){
        $cat_ids = Stock::selectRaw("MIN(id) as id , category_id")->where(["ecom_product"=>'0',"branch_id"=>$branch])->groupby('category_id')->get();
        return $cat_ids;
    }
    public function stockcategories($stk_cat_arr){
        $stk_arr = $stk_cat_arr->pluck('id');
        $cat_ids = StockCategory::select("stock_id as id","category_id")->join("categories","stock_categories.category_id","=","categories.id")->where("categories.category_level",2)->wherein('stock_categories.stock_id',$stk_arr)->get();
        return $cat_ids;
    }
	
	public function categorywisestockid_($branch_id = null,$level=null){
        $level_cats_id = Category::where(['category_level'=>$level,'status'=>0])->pluck('id');
        $stock_cat_ids =  StockCategory::where(['branch_id' => $branch_id, 'source' => 's'])->whereIn('category_id', $level_cats_id)->selectRaw('MAX(stock_id) as stock_id, category_id')->groupBy('category_id')->get();
        $products_with_category = [];
        foreach($stock_cat_ids as $sck=>$scv){
            $data = DB::table(DB::raw("ecomm_products,categories"))->select('ecomm_products.thumbnail_image','categories.*')->where(["ecomm_products.stock_id"=>$scv->stock_id,"categories.id"=>$scv->category_id])->first();
            if(!empty($data)){
                $products_with_category[] = $data;
            }
        }
        return $products_with_category;
    }
	
	public function categorywisestockid($branch_id = null,$level=null){
        $level_cats_id = Category::where(['category_level'=>$level,'status'=>0])->pluck('id');
        $stock_cat_ids =  StockCategory::where(['branch_id' => $branch_id, 'source' => 's'])->whereIn('category_id', $level_cats_id)->selectRaw('MAX(stock_id) as stock_id, category_id')->groupBy('category_id')->get();
        $products_with_category = [];
        foreach($stock_cat_ids as $sck=>$scv){
            $data = DB::table(DB::raw("ecomm_products,categories"))->select('ecomm_products.thumbnail_image','categories.*')->where(["ecomm_products.stock_id"=>$scv->stock_id,"categories.id"=>$scv->category_id])->first();
            if(!empty($data)){
                $products_with_category[] = $data;
            }
        }
        return $products_with_category;
    }
}
