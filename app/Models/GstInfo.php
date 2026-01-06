<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GstInfo extends Model
{
    use HasFactory;
    protected $fillable=["hsf","desc","gst","status",'shop_id','branch_id'];
}
