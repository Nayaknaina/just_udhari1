<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    use HasFactory;
    protected $fillable = ["bill_type","op_type","bill_id","stock_id","item_name","tag","caret","piece","gross","less","net","tunch","wastage","fine","element","rate","labour","labour_unit","other","discount","discount_unit","total","stock_type","entry_mode","shop_id","branch_id","entry_by","role_id"];

    public function bill(){
        return $this->belongsTo(Bill::class,'bill_id');
    }
	
	public function stock(){
        return $this->belongsTo(InventoryStock::class,'stock_id');
    }
}


