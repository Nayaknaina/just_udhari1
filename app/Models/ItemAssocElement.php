<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemAssocElement extends Model
{
    use HasFactory;
    protected $table = 'item_assoc_element';
    protected $fillable = ["purchase_id","product_id","name","caret","quant","cost"];

    public function item(){
        return $this->belongsTo(PurchaseItem::class,'product_id');
    }
}
