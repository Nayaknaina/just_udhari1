<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnjumanScheme extends Model
{
    use HasFactory;

    protected $table = "anjuman_scheme";
    protected $fillable = ['type','title','detail','validity','fix_emi','emi_quant','start','status','remark','shop_id','branch_id','entry_by','role_id'];

    public function enrolls(){
        return $this->hasMany(AnjumanSchemeEnroll::class,'scheme_id');
    }

    public function txns(){
        return $this->hasMany(AnjumanSchemeTxns::class,'scheme_id');
    }
}
