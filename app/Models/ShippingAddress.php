<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;
    protected $table = "shipping_address";
    protected $fillable = ['ship_unique', 'custo_id', 'state_id', 'state_name', 'dist_id','dist_name','teh_id','teh_name','area_id','area_name','pin_id','pin_code','custo_address'];

    
    public function customer() {

        return $this->belongsTo(Customer::class,'custo_id');

    }

}
