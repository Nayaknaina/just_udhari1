<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextMessage extends Model
{
    use HasFactory;
    protected $fillable = ['section','sub_section','msg_header','msg_route','variable_string','custo_id','custo_type','custo_name','custo_contact','msg_content','status','remark','role_id','entry_by','shop_id','branch_id'];
    
    
}
