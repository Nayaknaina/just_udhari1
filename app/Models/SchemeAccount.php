<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeAccount extends Model
{
    use HasFactory;
    protected $table = "scheme_account";
    protected $fillable = ["custo_id","shop_id","branch_id","collect_balance","remains_balance","entry_by","role_id"];

    function info(){
        return $this->belongTo(Customer::class,'custo_id');
    }
}
