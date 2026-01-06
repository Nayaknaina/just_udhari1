<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryStockElement extends Model
{
    use HasFactory;
    protected $fillable = ["inventory_stock_id","element","item_id","group_id","caret","part","colour","piece","clarity","gross","less","net","wastage","fine","tunch","rate","cost","shop_id","branch_id","entry_by","role_id"];

    public function itemstock(){
        return $this->belongTo(InventoryStock::class);
    }

    public function itemgroup(){
        return $this->belongTo(StockItemGroup::class,'group_id');
    }

    public function item(){
        return $this->belongTo(StockItem::class,'item_id');
    }
}
