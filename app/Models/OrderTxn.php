<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTxn extends Model
{
    use HasFactory;
    protected $table = "order_transactions";
    protected $fillable = ["txn_unique","txn_number","orders_id","order_number","order_amount","txn_mode","txn_medium","txn_by","txn_gatway","txn_res_msg","txn_res_code","txn_for","txn_status","gateway_txn_id","gateway_txn_status"];

    public function order(){
        return $this->belongsTo(Order::class,'orders_id');
    }
	public function schemeorder(){
        return $this->belongsTo(OrderDetail::class,'orders_id');
    }
}
