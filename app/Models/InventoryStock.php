<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rate;

class InventoryStock extends Model
{
    use HasFactory;
    protected $fillable = ["name","item_id","image","huid","group_id","tag","rfid","color","clearity","crt","caret","tunch",'count','gross','less','net','wastage','fine','have_element','element_charge','rate','charge','labour','labour_unit','discount','discount_unit','tax','tax_unit','total','entry_num','entry_date','entry_mode','stock_type','source',"remark",'entry_at','avail_count','avail_gross','avail_net','avail_fine','shop_id','branch_id','entry_by','role_id'];

    // public function itemelements(){
    //     return $this->hasMany(StockItemElements::class,'inventory_stocks_id');
    // }
	protected $appends = ['current_rate'];
    protected static $latestRate = null;
    // public function itemelements(){
    //     return $this->hasMany(StockItemElements::class,'inventory_stocks_id');
    // }

    public function getCurrentRateAttribute(){
        // Load latest active rate only once
        $material = strtolower($this->stock_type);
        if(in_array($material,['gold','silver'])){
            if(!self::$latestRate) {
                self::$latestRate = Rate::where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id,'active'=>'1'])->latest()->first();
            }
			$column = "{$material}_rate";
			if(self::$latestRate){
				$baseRate = self::$latestRate->{$column} ?? null;
				//return $baseRate;
				if($baseRate){
					return round(($material=='gold')?($this->caret * ($baseRate/24)):($baseRate/1000));
				}
			}
            return self::$latestRate->{$column} ?? null;
        }
        // If no active rate row exists, return null
        if (!self::$latestRate) {
            return null;
        }
    }
	
	/*public function getCurrentRateAttribute(){
        // Load latest active rate only once
        if (!self::$latestRate) {
            self::$latestRate = Rate::where('active', '1')->latest()->first();
        }

        // If no active rate row exists, return null
        if (!self::$latestRate) {
            return null;
        }

        // Map stock_type to corresponding rate column
        $material = strtolower($this->stock_type);
        $column = "{$material}_rate";

        // Return the value if column exists, otherwise null
        return self::$latestRate->{$column} ?? null;
    }*/

    public function itemgroup(){
        return $this->belongsTo(StockItemGroup::class,'group_id');
    }

    public function item(){
        return $this->belongsTo(StockItem::class,'item_id');
    }

    public function elements(){
        return $this->hasMany(InventoryStockElement::class);
    }
}
