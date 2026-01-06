<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UdharConversion extends Model
{
    use HasFactory;
    protected $table = "udhar_conversion";
    protected $fillable = [
        "udhar_id",
        "custo_id",
        "txn_id",
        "curr_from",
        "curr_to",
        "from",
        "to",
        "from_rate",
        "from_rate_unit",
        "to_rate",
        "to_rate_unit",
        "form_quant",
        "from_value",
        "to_quant",
        "shop_id",
        "branch_id",
    ];

    public function account(){
        return $this->belongsto(UdharAccount::class,'udhar_id');
    }
	
    public function txns(){
        return $this->belongsto(UdharTransaction::class,'txn_id');
    }
}
