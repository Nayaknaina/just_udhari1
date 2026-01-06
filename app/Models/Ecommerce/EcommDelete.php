<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcommDelete extends Model
{
    use HasFactory;
    protected $table = "ecomm_deletion";
    protected $primaryKey = 'policy_id';
    protected $fillable = ["policy_id","policy_unique","policy_content","policy_status","branch_id","shop_id","created_at"];

    public function content($vendor_id = null){

        return $this->where('shop_id',$vendor_id)->first() ;

    }

    public function activecontent($branch_id = null){

        return $this->where('policy_status', '1')->where('branch_id',$branch_id)->first();

    }


}
