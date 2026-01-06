<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = "orders";
    protected $fillable = ["cart_id","order_unique","shop_id","branch_id","custo_id","quantity","total","pay_status","ship_id","order_type","gateway_id"];

    public function orderdetail(){
        return $this->hasmany(OrderDetail::class,'order_id');
    }
    public function shop(){
        return $this->belongsTo(Shop::class,'shop_id');
    }
    public function branch(){
        return $this->belongsTo(ShopBranch::class,'branch_id');
    }
    public function customer(){
        return $this->belongsTo(Customer::class,'custo_id');
    }
    public function shippingaddress(){
        return $this->hasOne(ShippingAddress::class, 'id','ship_id');
    }
	public function txns(){
        return $this->hasMany(OrderTxn::class, 'orders_id');
    } 
	
	public function lasttxns(){
        return $this->hasOne(OrderTxn::class, 'orders_id')->orderBy('id','DESC');
    } 
	
    public function gateway(){
        return $this->belongsTo(PaymentGatewaySetting::class,'gateway_id');
    }
}
