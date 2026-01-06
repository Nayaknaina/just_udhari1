<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcommContact extends Model
{
    use HasFactory;
    protected $table = "ecomm_contact";
    protected $primaryKey = 'contact_id';
    protected $fillable = ["contact_id","contact_unique", "contact_greet","contact_addr","contact_email_one","contact_email_two","contact_email_vis","contact_fone_one","contact_fone_two","contact_fone_vis","contact_status" , 'branch_id','shop_id', "created_at"];

    
    public function content($vendor_id = null){

        return $this->where('shop_id',$vendor_id)->first();

    }

    public function activecontent($vendor_id = null) {

        return $this->where('branch_id',$vendor_id)->first() ;

    }

}
