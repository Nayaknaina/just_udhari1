<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcommRefund extends Model
{
    use HasFactory;
    protected $table = "ecomm_refund_policy";
    protected $primaryKey = 'refp_id';
    protected $fillable = ["refp_id","refp_unique","refp_content","refp_status","branch_id","shop_id","created_at"];

    public function content($vendor_id = null){

        return $this->where('shop_id',$vendor_id)->first();

    }

    public function activecontent($vendor_id = null) {

        return $this->where('refp_status', '1')->where('branch_id',$vendor_id)->first() ;

    }

}
