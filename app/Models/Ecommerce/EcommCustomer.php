<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcommCustomer extends Model {

    use HasFactory;
    protected $table = "customers";
    protected $primaryKey = 'custo_id';
    protected $fillable = ["custo_id","custo_unique","vndr_id","custo_first_name","custo_last_name","custo_mail","custo_fone","area_name","pin_code","custo_address"];

    public function content($vendor_id = null){

        return $this->where('shop_id',$vendor_id)->first();

    }

    public function activecontent($vendor_id = null) {

        return $this->where('branch_id',$vendor_id)->first() ;

    }

}
