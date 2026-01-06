<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    protected $fillable = ["bill_type","bill_prop","bill_number","bill_date","due_date","party_type","party_id","party_name","party_mobile","items","sub","discount","discount_unit","total","gst","gst_value","sgst","sgst_value","igst","igst_value","cgst","cgst_value","round","final","payment","balance","shop_id","branch_id","entry_by","role_id","status"];

    public function billitems(){
        return $this->hasMany(BillItem::class,'bill_id');
    }

    public function payments(){
        return $this->hasMany(NewBillPayment::class,'bill_id');
    }
	
	public function partydetail(){
        switch ($this->party_type) {
            case 'c':
                return $this->belongsTo(Customer::class, 'party_id');
            case 'w':
                //return $this->belongsTo(WholeSeller::class, 'party_id');
            case 's':
                return $this->belongsTo(Supplier::class, 'party_id');
            default:
                return null; // unknown type
        }
    }
	
	public function gstdata($type,$current = true){
        $source = (strtolower($type) == 'sale')?'s':'p';
        $cond = ['shop_id'=> auth()->user()->shop_id,'branch_id'=> auth()->user()->branch_id,'source'=>$source];
        if($current){
            return $this->hasOne(GstTransaction::class,'source_id')->where($cond)->whereIn('action_taken',['A','U'])->latest('id')->first();
        }else{
            return $this->hasMany(GstTransaction::class,'source_id')->where($cond)->get();
        }
    }
	
	public function udhardata($type,$current=true){
        $source = (strtolower($type) == 'sale')?'S':'P';
        $cond = ['shop_id'=> auth()->user()->shop_id,'branch_id'=> auth()->user()->branch_id,'source'=>$source];
        if($current){
            return $this->hasOne(UdharTransaction::class, 'source_id', 'bill_number')->where($cond)->whereIn('action',['A','U'])->latest('id')->first();
        }else{
            return $this->hasMany(UdharTransaction::class, 'source_id', 'bill_number')->where($cond)->get();
        }
    }
}
