<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcommAbout extends Model {

    use HasFactory ;

    protected $table = "ecomm_about" ;
    protected $primaryKey = 'about_id' ;

    protected $fillable = [

                            "about_id","about_unique", "about_image","about_sort","about_desc","about_status", 
                            'meta_title' , 'meta_description' , 'branch_id','shop_id'

                          ] ;

    public function content($vendor_id = null){

        return $this->where('shop_id',$vendor_id)->first();

    }

    public function activecontent($vendor_id = null) {

        return $this->where('branch_id',$vendor_id)->first() ;

    }

}
