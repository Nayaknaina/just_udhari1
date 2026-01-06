<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\CommonScope;

class DailyStockTransaction extends Model
{
    use HasFactory;
    protected $fillable = ["object","type","karet","purity","net","fine","value","count","holder","holder_id","status","source","balance_value","balance_status","reference","action","action_on","shop_id","branch_id","entry_by","role_id","created_at","updated_at"];

    protected static function booted()
    {
        static::addGlobalScope(new CommonScope());
    }

    


    /*public function section() {
        return match ($this->source) {
            "imp", "ins" => $this->belongsTo(InventoryStock::class, 'reference', 'entry_num'),

            "sll" => $this->belongsTo(Bill::class, 'reference', 'bill_number'),

            'prc' => $this->belongsTo(NewPurchase::class, 'reference', 'bill_no'),

            'udh' => $this->belongsTo(UdharAccount::class, 'reference', 'id'),

            default => $this->belongsTo(InventoryStock::class, 'reference', 'entry_num')->whereRaw('1=0'),
        };
    }*/

    public function section(){
        switch($this->source){
            case 'imp':
            case 'ins':
                return $this->belongsTo(InventoryStock::class,'reference','entry_num');
                break;
            case 'sll':
                return $this->belongsTo(Bill::class,'reference','bill_number');
                break;
            case 'prc':
                return $this->belongsTo(NewPurchase::class,'reference','bill_no');
                break;
            case 'udh':
            case 'cut':
                return $this->belongsTo(UdharAccount::class,'reference','id');
                break;
            case 'sch':
                return $this->belongsTo(EnrollCustomer::class,'reference','id');
                break;
            default:
                //return null;
                return [];
                break;
        }
    }

    public function getCustomerAttribute(){
        $relation = $this->section;

        if (! $relation) return null;

        // Bill Case
        if ($relation instanceof \App\Models\Bill) {
            return $relation->party_type === 'c'
                ? Customer::find($relation->party_id)
                : Supplier::find($relation->party_id);
        }

        // Udhar Account Case
        if ($relation instanceof \App\Models\UdharAccount) {
            return $relation->custo_type === 'c'
                ? Customer::find($relation->custo_id)
                : Supplier::find($relation->custo_id);
        }

        // Scheme Enrollment  Case
        if ($relation instanceof \App\Models\EnrollCustomer) {
            /*return $relation->custo_type === 'c'
                ? Customer::find($relation->custo_id)
                : Supplier::find($relation->custo_id);*/
            return Customer::find($relation->customer_id);
        }

        return null;
    }

    /*public function customer(){
        switch($this->source){
            case 'sll':
            case 'prc':
                return $this->hasOneThrough(Customer::class,
                                            Bill::class,
                                            'bill_number',
                                            'id',
                                            'reference',
                                            'party_id'
                                            );
                break;
            case 'udh':
            case 'cut':
                return $this->hasOneThrough(
                    Customer::class,
                    UdharAccount::class,
                    'id',
                    'id',
                    'reference',
                    'custo_id'
                );
                break;
            default:
                //return null;
                return [];
                break;
        }
    }*/
}
