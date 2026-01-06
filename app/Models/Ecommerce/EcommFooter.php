<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcommFooter extends Model
{
    use HasFactory;
    protected $table = "ecomm_footer";
    protected $primaryKey = 'foot_id';
    protected $fillable = ["foot_id","foot_unique","shop_id","branch_id" ,"foot_content","foot_status","created_at"];

    public function content($vendor_id = null){

        return $this->where('shop_id',$vendor_id)->first() ;

    }

    public function activecontent($branch_id = null){

        return $this->where('branch_id',$branch_id)->first();

    }

}
