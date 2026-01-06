<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewBillPayment extends Model
{
    use HasFactory;
    protected $fillable = ["bill_type","bill_id","pay_source","pay_root","pay_method","pay_quantity","pay_rate","pay_value","pay_holder","pay_effect",'pay_remark',"shop_id","branch_id","entry_by","role_id"];

    public function bill($type){
        return $this->belongsto(Bill::class,'bill_id')->where('bill_type',$type);
    }
}
