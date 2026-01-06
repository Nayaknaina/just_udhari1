<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillAccount extends Model
{
    use HasFactory;
    protected $table = "bill_account";
    protected $fillable = [
                "person_id",
                "person_type",
                "bill_id",
                "bill_number",
                "bill_type",
                "amount",
                "remark",
                "category",
                "branch_id",
                "shop_id",
                "created_at",
                "updated_at",
            ];
    
    public function  customer(){
        return $this->belongsTo(Customer::class,'person_id');
    }
    public function  supplier(){
        return $this->belongsTo(Supplier::class,'person_id');
    }

    public function  purchasebill(){
        return $this->belongsTo(Sell::class,'bill_id');
    }

    public function  sellebill(){
        return $this->belongsTo(Sell::class,'bill_id');
    }
}
