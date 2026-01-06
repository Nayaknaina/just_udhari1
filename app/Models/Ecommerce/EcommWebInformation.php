<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcommWebInformation extends Model {

    use HasFactory ;

    protected $fillable = ['web_title' , 'logo' , 'mobile_no' , 'mobile_no_2' , 'email','email_2','address','map','unique_id', 'meta_title' , 'meta_description' , 'branch_id' ,'shop_id'] ;

    public function content($vendor_id = null){

        return $this->where('shop_id',$vendor_id)->first();

    }

    public function activecontent($vendor_id = null) {

        return $this->where('branch_id',$vendor_id)->first() ;

    }


}
