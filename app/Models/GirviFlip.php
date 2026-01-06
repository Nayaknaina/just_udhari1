<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GirviFlip extends Model
{
    use HasFactory;
    protected $fillable = ['batch_id','item_id','now_value','pre_p','pre_i','txn_id','post_p','post_i','op_on','status','remark'];

    public function batch(){
        return $this->belongsTo(GirvyBatch::class,'batch_id');
    } 
    public function item(){
        return $this->belongsTo(GirvyItem::class,'item_id');
        
    } 
    public function txn(){
        return $this->belongsTo(GirvyTxn::class,'txn_id');
        
    } 
}
