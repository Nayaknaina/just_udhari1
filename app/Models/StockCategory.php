<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockCategory extends Model
{
    use HasFactory ;

    protected $fillable = [

        'stock_id' , 
        'category_id' , 
        'source' , 
        'shop_id' , 
        'branch_id' , 

    ] ;

	public function stocks(){
        /*return $this->hasMany(Stock::class,'stock_id');*/
        return $this->hasMany(Stock::class,'id','stock_id');
    }

    public function category(){
        /*return $this->belongsToOne(Category::class);*/
        return $this->belongsTo(Category::class);
    }
}
