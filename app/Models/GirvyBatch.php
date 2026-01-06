<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GirvyBatch extends Model
{
    use HasFactory;
    protected $table = "girvi_batches";
    protected $fillable = ['receipt','girvi_custo_id','item_count','girvi_value','girvi_issue','girvi_issue_diff_perc','interest_rate','principle','interest','entry_date','girvy_period','girvy_issue_date','girvy_return_date','remark','flip','status','entry_by','role_id','shop_id','branch_id','created_at','updated_at'];

    public function customer(){
        return $this->belongsTo(GirvyCustomer::class,'girvi_custo_id');
    }

    public function items(){
        return $this->hasMany(GirvyItem::class,'girvi_batch_id');
    }

    public function activeitems(){
        return $this->items()->where('status',1);
    }

    public function txns(){
        return $this->hasMany(GirvyTxn::class,'girvi_batch_id');
    }

   /* public static function oldbatch($girvi = false){
        $cond = ['status'=>1];
        if($girvi){
            $cond['girvi_custo_id'] = $girvi;
            $$cond['shop_id'] = auth()->user()->shop_id;
            $$cond['branch_id'] = auth()->user()->branch_id;
        }
        return self::batchs()->where($cond);
    }
    public static function newbatch($girvi = false){
        $cond = ['status'=>1];
        if($girvi){
            $cond['girvi_custo_id'] = $girvi;
            $$cond['shop_id'] = auth()->user()->shop_id;
            $$cond['branch_id'] = auth()->user()->branch_id;
        }
        return self::batchs()->where($cond);
    }*/

    public static function maxreceipt(){
        return self::where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->max('receipt');
    }

    public function activeflip(){
        return $this->hasone(GirviFlip::class,'batch_id')->where('status',1);
    }
    public function flips(){
        return $this->hasMany(GirviFlip::class,'batch_id');
    }
}
