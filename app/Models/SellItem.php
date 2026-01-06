<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellItem extends Model
{
    use HasFactory;
    protected $table = "sell_items";
    protected $fillable = [
        'sell_id',
        'stock_id',
        'item_name',
        'item_weight',
        'item_quantity',
        'item_caret',
        'item_rate',
        'item_cost',
        'labour_perc',
        'labour_charge',
        'elements',
        'total_amount',
        'item_type',
        'shop_id',
        'branch_id',
        'entry_by',
        'role_id',
        'created_at',
        'updated_at',
    ] ;
	public function bill(){
        return $this->belongsto(Sell::class,'sell_id');
    }
    public function stock(){
        return $this->belongsTo(Stock::class);
    }
}
