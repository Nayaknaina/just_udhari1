<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopScheme extends Model{

    use HasFactory ;
    
    protected $fillable = ['url_part','scheme_img','scheme_head' ,'scheme_sub_head' ,'scheme_detail_one' ,'scheme_detail_two' ,'total_amt' ,'scheme_validity','lucky_draw' ,'emi_range_start','emi_range_end' ,'emi_date' ,'emi_validity_month' ,'scheme_interest' ,'interest_type' ,'interest_rate' ,'interest_amt','scheme_date_fix','scheme_date','launch_date' ,'scheme_rules','ss_status' , 'scheme_id' , 'shop_id'] ;

    public function schemes(){
        return $this->hasOne(Scheme::class,'id','scheme_id');
    }
    
    public function groups(){
        return $this->hasMany(SchemeGroup::class,'scheme_id','id');
    }
	////--------------NEW CODE LINE 4 E-Comm Scheme----------------//
    public function mygroups()
    {
        return $this->hasMany(SchemeGroup::class, 'scheme_id', 'id');
    }
    public function members(){
        return $this->hasMany(EnrollCustomer::class, 'scheme_id', 'id');
    }
}
