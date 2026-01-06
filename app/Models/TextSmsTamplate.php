<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TextSmsTamplate extends Model
{
    use HasFactory;
    protected $fillable = ['msg_id','head','body','variables','status','shop_id','branch_id','entry_by','role_id'];
    protected $table = "text_sms_tamplate";
    
}
