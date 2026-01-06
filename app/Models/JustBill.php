<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JustBill extends Model
{
    use HasFactory;
    protected $fillable = [
                        "custo_name",
                        "custo_mobile",
                        "custo_addr",
                        "custo_gst",
                        "custo_state",
                        "bill_no",
                        "custom",
                        "bill_date",
                        "bill_gst",
                        "bill_hsn",
                        "bill_state",
                        "count",
                        "sub",
                        "discount",
                        "gst_type",
                        "gst",
                        "sgst",
                        "cgst",
                        "igst",
                        'roundoff',
                        "total",
                        "in_word",
                        "payment",
                        "remains",
                        "banking",
                        "remark",
                        "shop_id",
                        "branch_id",
                        "entry_by",
                        "role_id",
                        "status",
                        ];

    public function items(){
        return $this->hasmany(JustBillItem::class,'bill_id');
    }
}
