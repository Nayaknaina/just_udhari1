<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;
    protected $fillable = ['gold_karet','gold_rate','silver_unit','silver_rate','active','shop_id','branch_id','entry_by','role_id'];

}
