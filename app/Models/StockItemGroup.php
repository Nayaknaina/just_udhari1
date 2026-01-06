<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItemGroup extends Model
{
    use HasFactory;
    protected $table =  "stock_item_group";
    protected $fillable = ["cat_id","cat_name","coll_id","coll_name","item_group_name","shop_id","branch_id","entry_by","role_id","created_at","updated_at"];

    public function matterial(){
        return $this->belongsTo(Category::class,'cat_id');
    }

     public function jewellery(){
        return $this->belongsTo(Category::class,'coll_id');
    }

    public function stocks(){
        return $this->hasMany(InventoryStock::class,'group_id');
    }

    public function stockitems(){
        return $this->hasMany(StockItem::class,'group_id');
    }
}
