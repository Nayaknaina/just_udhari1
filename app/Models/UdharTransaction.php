<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UdharTransaction extends Model
{
    use HasFactory;
    protected $table = "udhar_transaction";
    protected $fillable = [
                        "udhar_id",
                        "custo_type",
                        "custo_id",
                        "source",
                        "source_id",
                        "amount_udhar",
                        "amount_udhar_holder",
                        "amount_udhar_status",
                        "gold_udhar",
                        "gold_udhar_status",
                        "silver_udhar",
                        "silver_udhar_status",
                        "action",
                        "target",
						"date",
                        "custom_remark",
                        "remark",
                        "entry_by",
                        "role_id",
                        "shop_id",
                        "branch_id",
						"created_at",
						"updated_at"
                    ];
    
    public function account(){
        return $this->belongsto(UdharAccount::class,'udhar_id');
    }
    public function custo(){
        return $this->hasone(Customer::class,'id','custo_id');
    }
    public function conversion(){
        return $this->hasone(UdharConversion::class,'txn_id');
    }
	
	public function pretxn(){
        $previous = self::selectRaw('
                IF(amount_udhar_status = 1, amount_udhar, -amount_udhar) AS amount,
                IF(gold_udhar_status = 1, gold_udhar, -gold_udhar) AS gold,
                IF(silver_udhar_status = 1, silver_udhar, -silver_udhar) AS silver
            ')->where('udhar_id', $this->udhar_id)
                ->where('id', '<', $this->id)
                ->orderBy('created_at', 'desc')
                ->first();
        return $previous ?? (object) [ 'amount' => 0, 'gold'   => 0, 'silver' => 0, ];
    }
}
