<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeGroup extends Model {

    use HasFactory ;

    protected $fillable = ['scheme_id' ,'group_name','group_limit' , 'start_date' , 'end_date' ,'branch_id' ,'shop_id'] ;


    public function schemes(){
        return $this->hasOne(ShopScheme::class,'id','scheme_id');
    }

	public function enrollcustomers(){
        return $this->hasMany(EnrollCustomer::class,'group_id','id');
    }
////--------------NEW CODE LINE 4 E-Comm Scheme----------------//  ////--------------NEW CODE LINE 4 E-Comm Scheme----------------//
    public function custoemipays()
    {
        return $this->hasMany(EnrollCustomer::class, 'scheme_transaction', 'enroll_id', 'groups_id');
    }

    public function members(){
        return $this->hasMany(EnrollCustomer::class, 'group_id', 'id');
    }

    public function groupemipaid(){
        return $this->hasMany(SchemeEmiPay::class, 'group_id', 'id');
    }
}
