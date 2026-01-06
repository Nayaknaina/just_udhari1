<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcommSocial extends Model {

    use HasFactory;
    protected $table = "ecomm_social";
    protected $primaryKey = 'social_id';
    protected $fillable = ["social_id","social_unique","social_icon_name","social_icon_type","social_icon_src","social_link","social_status","branch_id","shop_id","created_at"] ;

    public function content($vendor_id = null){

        return $this->where('shop_id',$vendor_id)->get();

    }

    public function activecontent($vendor_id = null) {

        return $this->where('branch_id',$vendor_id)->get() ;

    }

}
