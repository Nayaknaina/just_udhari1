<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ApiUrl extends Model
{
    use HasFactory;
    protected $fillable = ['for','url','api_key','route','sender_id','status','shop_id','branch_id','entry_by','role_id'];
    protected $table = "api_urls";
}
