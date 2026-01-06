<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopBranch extends Model
{
    use HasFactory ;

    protected $fillable = ['branch_name','image', 'name', 'email' , 'mobile_no', 'gst_num', 'address', 'state', 'district', 'branch_type', 'domain_name' ,  'shop_id', 'status'] ;

    public function branches() {

        return $this->hasMany(ShopBranch::class,'shop_id');

    }

	public function banking(){
        return $this->hasOne(Banking::class,'shop_id','shop_id')->where('branch_id',$this->branch_id);
    }

    public function customers(){

        return $this->hasMany(Customer::class,'shop_branches_id');

    }

    public function counters() {

        return $this->hasMany(Counter::class);

    }

    public function headbranch() {

        return $this->belongsTo(Shop::class,'shop_id');

    }

    public function statedata(){
        return $this->belongsTo(State::class,'state','code');
    }

    public function districtdata(){
        return $this->belongsTo(District::class,'district','code');
    }

    public function activeschemes(){
        return $this->hasMany(ShopScheme::class,'shop_id','shop_id')->where('ss_status','1');
        // return $this->hasMany(ShopScheme::class)->where(["shop_id"=>$this->shop_id,'ss_status'=>'1']);
        // return $this->belongsToMany(scheme::class, $this->table, 'id', 'scheme_id');
    }
	//-------------------------------NEW CODE BY 96 05-11-2014----------------------------//
    
}
