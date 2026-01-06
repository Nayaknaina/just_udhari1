<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model {

    use HasFactory ;

    protected $fillable = [
        'name',
        'box_name',
        'stock_id',
        'stock_name',
        'stock_quantity',
        'stock_avail',
        'stock_type',
        'status',
        'shop_id',
        'branch_id',		
    ];

    public function branch() {

        return $this->belongsTo(ShopBranch::class) ;

    }
	
	public function stock(){
        return $this->belongsTo(Stock::class,'stock_id') ;
    } 

}
