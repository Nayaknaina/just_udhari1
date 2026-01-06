<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcommTerm extends Model
{
    use HasFactory;
    protected $table = "ecomm_terms";
    protected $primaryKey = 'term_id';
    protected $fillable = ["term_id","term_unique","term_content","term_status","branch_id","shop_id","created_at"];

    public function content($vendor_id = null){

        return $this->where('shop_id',$vendor_id)->first();

    }

    public function activecontent($vendor_id = null) {

        return $this->where('term_status','1')->where('branch_id',$vendor_id)->first() ;

    }

}
