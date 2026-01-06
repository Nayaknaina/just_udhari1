<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnjumanSchemeTxns extends Model
{
    use HasFactory;

    protected $table = "anjuman_scheme_txns";
    protected $fillable = ['scheme_id','enroll_id','txn_quant','emi_num','txn_status','txn_action','target_id','txn_date','remark','shop_id','branch_id','entry_by','role_id'];

    public function enroll(){
        return $this->belongsTo(AnjumanSchemeEnroll::class,'enroll_id');
    }

    public function scheme(){
        return $this->belongsTo(AnjumanScheme::class,'scheme_id');
    }
	
	
    public function txns(){
        return $this->hasMany(AnjumanSchemeTxns::class,'scheme_id');
    }

    public function custodeposite(){
        return $this->hasMany(AnjumanSchemeTxns::class,'scheme_id')->where('txn_status',1)->whereIN('txn_action',['A','U']);
    }

    public function custowithdraw(){
        return $this->hasMany(AnjumanSchemeTxns::class,'scheme_id')->where('txn_status',0)->whereIN('txn_action',['A','U']);
    }

    /*public static function maxemipaid($enroll_id){
        $maxEmiNum = self::where(['enroll_id'=>$enroll_id,'txn_status'=>1])->whereIn('txn_action',['A','U'])->max('emi_num');
        $maxEmiNum = $maxEmiNum??0;
        $sum =  Self::where(['enroll_id'=>$enroll_id,'emi_num'=>$maxEmiNum,'txn_status'=>1])->whereIn('txn_action',['A','U'])->sum('txn_quant');
        return (object)['num'=>$maxEmiNum,'paid'=>$sum];
    }*/
	
	public static function maxemipaid($enroll_id,$emi_num=false){
        $maxEmiNum = ($emi_num)?$emi_num:self::where(['enroll_id'=>$enroll_id,'txn_status'=>1])->whereIn('txn_action',['A','U'])->max('emi_num');
        $maxEmiNum = $maxEmiNum??0;
        $sum =  Self::where(['enroll_id'=>$enroll_id,'emi_num'=>$maxEmiNum,'txn_status'=>1])->whereIn('txn_action',['A','U'])->sum('txn_quant');
        return (object)['num'=>$maxEmiNum,'paid'=>$sum];
    }
}
