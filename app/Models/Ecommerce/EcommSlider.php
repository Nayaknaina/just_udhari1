<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcommSlider extends Model {

    use HasFactory;

    protected $table = "ecomm_slider";
    protected $primaryKey = 'slider_id';
    protected $fillable = ["slider_id","slider_unique", "slider_image","slider_top_text","slider_bottom_text","slider_order","slider_status", "branch_id","shop_id" , "created_at"];

    public function slider($shop_id = null){

        return $this->where('shop_id',$shop_id)->orderby('slider_order','ASC')->get() ;

    }

    public function activeslider($branch_id = null) {

        return $this->where('slider_status', '1')->where('branch_id',$branch_id)->orderby('slider_order','ASC')->get() ;

    }

}
