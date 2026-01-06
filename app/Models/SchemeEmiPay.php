<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeEmiPay extends Model
{
    use HasFactory;

    protected $table = "scheme_transaction";

    protected $fillable = ['enroll_id', 'branch_id', 'shop_id', 'group_id', 'emi_num', 'scheme_id', 'emi_amnt', 'emi_date', 'bonus_amnt', 'bonus_type', 'pay_mode', 'pay_medium','pay_receiver','amnt_holder', 'stock_status','action_taken','action_on', 'remark'];

	public function enroll()
	{
		return $this->belongsTo(EnrollCustomer::class,'enroll_id','id');
	}
	
	//-----------------------New Code For Day Book----------------------//
	
    public function scheme()
	{
        return $this->belongsTo(ShopScheme::class, 'scheme_id', 'id');
    }
    public function group()
	{
        return $this->belongsTo(SchemeGroup::class, 'group_id', 'id');
    }
    public function custo()
    {
        return $this->belongsTo(EnrollCustomer::class,Customer::class,'enroll_id','customer_id');
    }
	public function customer()
    {
        return $this->hasOneThrough(Customer::class, EnrollCustomer::class, 'id', 'id', 'enroll_id', 'customer_id');
    }
}
