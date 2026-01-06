<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JustBillItem extends Model
{
    use HasFactory;
    protected $fillable = [
                        "bill_id",
                        "bill_no",
                        "name",
                        "quant",
                        "rate",
                        "unit",
                        "charge",
                        "sum",
                        "shop_id",
                        "branch_id",
                        "created_at",
                        "updated_at",
                        ];

    public function bill(){
        return $this->belongsto(JustBill::class,'bill_id');
    }
}
