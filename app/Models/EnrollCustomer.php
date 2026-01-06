<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollCustomer extends Model
{
    use HasFactory ;

    protected $fillable = ['customer_name', 'customer_id', 'token_amt','pay_mode','pay_medium','amnt_holder','pay_receiver','stock_status','token_remain','emi_amnt', 'group_id', 'scheme_id', 'emi_amnt', 'shop_id', 'assign_id', 'branch_id','open','balance_collect','balance_remains','is_winner','is_withdraw','entry_at','created_at','updated_at'];

    public function schemes() {
        return $this->belongsTo(ShopScheme::class , 'scheme_id','id') ;
    }
    
    public function info(){
        return $this->belongsTo(Customer::class , 'customer_id','id') ;
    }
	
    // public function groups() {
    //     return $this->belongsTo(SchemeGroup::class,'group_id','id') ;
    // }
	
    public function groups() {
        return $this->hasOne(SchemeGroup::class,'id','group_id') ;
    }

    public function emipaid(){
        return $this->hasMany(SchemeEmiPay::class,'enroll_id','id');
    }
	
	/*public function emipaidsummery(){
        return $this->hasMany(SchemeEmiPay::class ,'enroll_id','id');
    }*/
	 public function emipaidsummery()
    {
        return $this->hasMany(SchemeEmiPay::class, 'enroll_id', 'shop_schemes','scheme_id');
    }
	////--------------NEW CODE LINE 4 E-Comm Scheme----------------//

    public function schemetxn_sum(){

        return $this->hasMany(SchemeEmiPay::class , 'enroll_id', 'id' );

    }
	public function custo()
    {
        return $this->hasOne(Customer::class,'id', 'customer_id');
    }
}
