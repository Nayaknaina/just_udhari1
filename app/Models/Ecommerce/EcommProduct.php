<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Stock;
use App\Models\StockCategory;

class EcommProduct extends Model
{
    use HasFactory ;

    protected $fillable = [

        'name' ,
        'rate' ,
        'strike_rate',
        'url' ,
        'thumbnail_image' ,
        'description' ,
        'meta_title' ,
        'meta_description' ,
        'stock_id' ,
        'branch_id' ,
        'shop_id' ,

    ] ;


    public function stock() {

        return $this->belongsTo(Stock::class,'stock_id') ;

    }
	public function categories(){
        return $this->hasMany(StockCategory::class,'stock_id','stock_id');
    }
    public function galleryimages(){
        return $this->hasMany(EcommProductImage::class,'product_id');
    }

    public function branch(){
        return $this->belongsTo(ShopBranch::class,'branch_id');
    }
    
    public function shop(){
        return $this->belongsTo(ShopBranch::class,'shop_id');
    }

}
