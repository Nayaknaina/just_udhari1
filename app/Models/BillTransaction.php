<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillTransaction extends Model
{
    use HasFactory;

    protected $table  = "bill_transactions";
    protected $fillable = [
                "bill_id",
                "bill_no",
                "source",
                "mode",
                "medium",
                "amount",
                "remains",
                'action_taken',
                'action_on',
                'amnt_holder',
                'stock_status',
                "shop_id",
                "branch_id",
            ];

    public function sellbill(){
        return $this->belongsto(Sell::class,'sell_id')->where('source','s');
    }

    public function purchasebill(){
        return $this->belongsto(Purchase::class,'sell_id')->where('source','p');
    }

}
