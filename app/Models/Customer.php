<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ecommerce\ShoppingList;

class Customer extends Model
{
    use HasFactory;
    protected $table = "customers";
    protected $primaryKey = 'id';
    protected $fillable = ["id", "custo_unique","custo_num", "password", "custo_full_name", "custo_img","custo_mail", "custo_fone", "fone_varify","state_id","state_name","dist_id","dist_name","teh_id","teh_name","area_id","area_name","pin_id","pin_code", "custo_address", "custo_status", "custo_status_msg", "active_operator_id", "active_operator_role", "shop_id", "branch_id", 'cust_type', "created_at"];

  //protected $hidden = ["password"];
    public function shop(){
      return $this->belongsTo(ShopBranch::class,'shop_id');
    }
	
    public function branch(){
      return $this->belongsTo(ShopBranch::class,'branch_id');
    }

    public function state(){
      return $this->belongsTo(State::class,'state_id','code');
    }

    public function district(){
      return $this->belongsTo(District::class,'dist_id','code');
    }
	

    public function cart(){
        //return $this->where()->first();
		return $this->hasmany(ShoppingList::class,'custo_id')->where('list_type','1');
    }

    public function wishlist(){
		return $this->hasmany(ShoppingList::class,'custo_id')->where('list_type','0');
    }

    public function orders(){
		return $this->hasmany(Order::class,'custo_id');
    }


	public function ordertxns(){
		return $this->hasManyThrough(OrderTxn::class, Order::class,'custo_id','orders_id');
	}

	
	public function schemetxn(){
		return $this->hasManyThrough(OrderTxn::class, OrderDetail::class,'custo_id','orders_id');
	}

    public function txnhistory(){

    }

    public function orderhistory(){

    }
    public function shippingaddress(){
      return $this->hasMany(ShippingAddress::class,"custo_id");
    }
	
	public function schemes()
  {
    return $this->belongsToMany(ShopScheme::class, 'enroll_customers', 'customer_id', 'scheme_id');
  }

  public function group()
  {
    return $this->hasMany(SchemeGroup::class, 'customer_id', 'id');
  }
  
  public function shiping(){
    return $this->hasOne(ShippingAddress::class, 'custo_id');
  }
  ////--------------NEW CODE LINE 4 E-Comm Scheme----------------//
  public function enroll(){
    return $this->hasMany(EnrollCustomer::class,'customer_id','id');
  }
  
  public function schemebalance(){
    return $this->hasOne(SchemeAccount::class,'custo_id');
  }
  
  public function billaccount(){
    return $this->hasmany(BillAccount::class,'person_id')->where(['person_type'=>'C','bill_type'=>'S']);
  }
  
  public function schemeenquiry(){
    return $this->hasmany(SchemeEnquiry::class,'custo_id');
  }
  
}
