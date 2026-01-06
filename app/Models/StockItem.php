<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    use HasFactory;
    protected $table = "stock_items";
    protected $fillable = ["item_name","group_id","hsn_code","tag_prefix","tag_digit","curr_max_tag","labour_value","labour_unit","tax_value","tax_unit","karet","tounch","wastage","stock_method","shop_id","branch_id","entry_by","role_id"];

    public function itemgroup(){
        return $this->belongsTo(StockItemGroup::class,'group_id');
    }

    public function inventorystock(){
        return $this->hasMany(InventoryStock::class,'item_id');
    }

    public function matterial(){
        
    }

    public function category(){
        
    }

}
