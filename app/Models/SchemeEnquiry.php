<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeEnquiry extends Model
{
    use HasFactory;
    protected $table = "scheme_enquiry";
    protected $fillable = ['custo_id','shop_id','branch_id','scheme_id','name','mail','fone','message','status'];

    function scheme(){
        return $this->belongsTo(ShopScheme::class,'scheme_id','id');
    }
    function customer(){
        return $this->belongsTo(Customer::class,'custo_id','id');
    }
}
