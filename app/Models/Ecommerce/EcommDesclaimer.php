<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcommDesclaimer extends Model
{
    use HasFactory;
    protected $table = "ecomm_desclaimer";
    protected $primaryKey = 'desc_id';
    protected $fillable = ["desc_id","desc_unique", "desc_content","desc_status","branch_id","shop_id","created_at"];

    public function content($vendor_id = null){

        return $this->where('shop_id',$vendor_id)->first() ;

    }

    public function activecontent($branch_id = null){

        return $this->where('desc_status', '1')->where('branch_id',$branch_id)->first();

    }


}
