<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GirvyTxn extends Model
{
    use HasFactory;
    protected $table = "girvi_txns";
    protected $fillable = ['girvi_custo_id','girvi_batch_id','girvi_item_id','pay_mode','pay_medium','pay_principal','pay_interest','operation','amnt_holder','txn_status','action','action_on','pay_date','remark','entry_by','role_id','shop_id','branch_id','created_at','updated_at'];

    public function customer(){
        return $this->belongsTo(GirvyCustomer::class,'girvi_custo_id');
    }

    public function batch(){
        return $this->belongsTo(GirvyBatch::class,'girvi_batch_id');
    }

    public function item(){
        return $this->belongsTo(GirvyItem::class,'girvi_item_id');
    }

    public function flip(){
        return $this->hasOne(GirviFlip::class,'txn_id');
    }
}
