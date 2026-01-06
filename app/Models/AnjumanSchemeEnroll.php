<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnjumanSchemeEnroll extends Model
{
    use HasFactory;

    protected $table = "anjuman_scheme_enroll";
    protected $fillable = ['scheme_id','custo_id','custo_name','enroll_date','status','remark','shop_id','branch_id','entry_by','role_id'];

    public function customer(){
        return $this->belongsTo(Customer::class,'custo_id');
    }

    public function scheme(){
        return $this->belongsTo(AnjumanScheme::class,'scheme_id');
    }

	public function activetxns(){
        return $this->txns()->whereIn('txn_action',['A','U']);
    }
	
    public function txns(){
        return $this->hasMany(AnjumanSchemeTxns::class,'enroll_id');
    }
    
    public function custodeposite(){
        return $this->activetxns()->where('txn_status',1);
        //return $this->hasMany(AnjumanSchemeTxns::class,'enroll_id')->where('txn_status',1)->whereIN('txn_action',['A','U']);
    }

    public function custowithdraw(){
        //return $this->hasMany(AnjumanSchemeTxns::class,'enroll_id')->where('txn_status',0)->whereIN('txn_action',['A','U']);
        return $this->activetxns()->where('txn_status',0);
    }

    /*public function txns(){
        return $this->hasMany(AnjumanSchemeTxns::class,'enroll_id');
    }
	
    public function custodeposite(){
        return $this->hasMany(AnjumanSchemeTxns::class,'enroll_id')->where('txn_status',1)->whereIN('txn_action',['A','U']);
    }

    public function custowithdraw(){
        return $this->hasMany(AnjumanSchemeTxns::class,'enroll_id')->where('txn_status',0)->whereIN('txn_action',['A','U']);
    }*/
}
