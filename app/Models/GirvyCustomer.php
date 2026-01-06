<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GirvyCustomer extends Model
{
    use HasFactory;
    protected $table = 'girvi_customers';
    protected $fillable = ['girvi_id','custo_name','custo_mobile','custo_id','custo_type','remark','balance_principal','balance_interest','status','entry_by','role_id','shop_id','branch_id','created_at','updated_at'];

    public function batchs(){
        return $this->hasMany(GirvyBatch::class,'girvi_custo_id');
    }

    public function items(){
        return $this->hasMany(GirvyItem::class,'girvi_custo_id');
    }
    
    public function txns(){
        return $this->hasMany(GirvyTxn::class,'girvi_custo_id');
    }

    public function maxbatchreceipt(){
        return $this->batchs()->max('receipt');
    }

    public function maxitemreceipt(){
        return $this->items()->max('receipt');
    }
}
