<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    use HasFactory;
    protected $table = "sells";
    protected $fillable = [
		'sell_to',
		'custo_id',
        'custo_name',
        'custo_mobile',
        'custo_gst',
        'custo_state',
        'custo_bank',
        'bill_no',
        'bill_date',
        'bill_gst',
        'bill_hsn',
        'bill_state',
        'count',
        'sub_total',
        'gst_apply',
        'gst_type',
        'gst',
        'sgst',
        'cgst',
        'igst',
        'discount',
        'disc_amnt',
        'roundoff',
        'total',
        'in_word',
        'payment',
        'pay_mode',
        'pay_medium',
        'remains',
		'refund',
        'banking',
        'declaration',
        'remark',
        'shop_id',
        'branch_id',
        'entry_by',
        'role_id',
        'delete_status',
    ] ;
	
	public function customer(){
        return $this->belongsTo(Customer::class,'custo_id');
    }

    public function items(){
        return $this->hasMany(SellItem::class,'sell_id');
    }

    public function payments(){
        return $this->hasMany(BillTransaction::class,'bill_id')->where('source','s');
    }
	public function gsttxn($bill=null){
        return $this->hasone(GstTransaction::class,'source_id')->where(['source'=>'s','source_no'=>$bill])->first();
    }

    public function gsttxns($bill=null){
        return $this->hasone(GstTransaction::class,'source_id')->where(['source'=>'s','source_no'=>$bill])->get();
    }
}
