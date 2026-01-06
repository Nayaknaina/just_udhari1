<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcommProductImage extends Model
{
    use HasFactory ;

    protected $fillable = [

        'images' ,
        'product_id' ,

    ] ;
	public function ecommproduct(){
        return $this->belongsto(EcommProduct::class,'product_id');
    }
}
