<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGatewaySetting extends Model
{
    use HasFactory;
    protected $fillable = ["shop_id","branch_id","gateway_id","gateway_name","custom_name","parameter","state","status"];
	
	public function origin(){
        return $this->belongsTo(PaymentGatewayTamplate::class,'gateway_id');
    }
}
