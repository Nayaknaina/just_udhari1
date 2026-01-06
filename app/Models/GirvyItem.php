<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GirvyItem extends Model
{
    use HasFactory;
    protected $table = "girvi_items";
    protected $fillable = ['receipt','girvi_custo_id','girvi_batch_id','category','image','detail','property','rate','value','issue_diff_perc','issue','interest_rate','interest_type','interest','principal','entry_date','action','action_on','remark','flip','status','entry_by','role_id','shop_id','branch_id','created_at','updated_at'];


    public function customer(){
        return $this->belongsTo(GirvyCustomer::class,'girvi_custo_id');
    }

    public function batch(){
        return $this->belongsTo(GirvyBatch::class,'girvi_batch_id');
    }

    public function txns(){
        return $this->hasMany(GirvyTxn::class,'girvi_item_id');
    }

    public static function maxreceipt(){
        return self::where(['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id])->max('receipt');
    }

    public function activeflip(){
        return $this->hasOne(GirviFlip::class,'item_id')->where('status',1);
    }

    public function flips(){
        return $this->hasMany(GirviFlip::class,'item_id');
    }

    public function parentitem(){
        return $this->hasOne(Self::class,'action_on');
    }
}
