<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $table = "order_detail";
    protected $fillable = ["detail_unique","order_id","product_id","product_url","quantity","shop_id","branch_id","mark_cost","curr_cost","custo_id","entry_medium","type","gateway_id"];

    public function order(){
        return $this->belongsTo(Order::class,'order_id');
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
    public function product(){
        return $this->belongsTo(Ecommerce\EcommProduct::class,'product_id');
    }
	
	public function schemetxn(){
        return $this->hasOne(OrderTxn::class,'orders_id','id');
    }
    
    public function enroll(){
        return $this->belongsTo(EnrollCustomer::class,'product_id');
    }

    public function group(){
        return $this->belongsTo(SchemeGroup::class,'order_id');
    }
}
