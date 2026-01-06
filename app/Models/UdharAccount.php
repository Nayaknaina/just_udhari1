<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UdharAccount extends Model
{
    use HasFactory;
    protected $table = "udhar_account";
    protected $fillable = [
                        'custo_type',
                        'custo_id',
                        'custo_name',
                        'custo_num',
                        'custo_mobile',
                        'custo_amount',
                        'custo_amount_status',
                        'custo_gold',
                        'custo_gold_status',
                        'custo_silver',
                        'custo_silver_status',
						'udhar_note',
                        'entry_by',
                        'role_id',
                        'shop_id',
                        'branch_id',
                    ];

    
    public function activetxn($duration=false){
        $query = $this->hasmany(UdharTransaction::class,'udhar_id')->whereIn('action',['A','U','C','D']);
        if($duration){
            $query->whereDate('created_at', "{$duration}");
        }
        return $query->get();
    }
       
    public function txns(){
        return $this->hasmany(UdharTransaction::class,'udhar_id');
    }

    public function lasttxn(){
        return $this->hasone(UdharTransaction::class,'udhar_id')->max('id');
    } 

    public function conversions(){
        return $this->hasmany(UdharConversion::class,'udhar_id');
    }

    public function lastconversion(){
        return $this->hasone(UdharConversion::class,'udhar_id')->max('id');
    }

    public function custo(){
        return $this->hasone(Customer::class,'id','custo_id');
    }

    public function pretxn($current_date_time){
        $query = $this->hasmany(UdharTransaction::class,'udhar_id')->whereIn('action',['A','U','C','D']);
        $query->selectRaw('
            sum(if(amount_udhar_status=1,+amount_udhar,-amount_udhar)) as amount,
            sum(if(gold_udhar_status=1,+gold_udhar,-gold_udhar)) as gold,
            sum(if(silver_udhar_status=1,+silver_udhar,-silver_udhar)) as silver'
        )->where('created_at','<',$current_date_time);
        return $query->first();
    }

    public function userpretxn($current_date_time,$txn_id){
        // echo $current_date_time."<br>";
        // echo $txn_id."<br>";
        // echo $this->id."<br>";
        $first_data = UdharTransaction::where('udhar_id',$this->id)->whereIn('action',['A','U'])->selectRaw('amount_curr as amount,gold_curr as gold,silver_curr as silver')->orderBy('id','ASC')->first();
        
        //print_r($first_data->toArray());

        $sum_data = UdharTransaction::where('udhar_id',$this->id)->where('id','!=',$txn_id)->where('created_at','<=',$current_date_time)->whereIn('action',['A','U'])->selectRaw('
            sum(if(amount_udhar_status=1,+amount_udhar,-amount_udhar)) as amount,
            sum(if(gold_udhar_status=1,+gold_udhar,-gold_udhar)) as gold,
            sum(if(silver_udhar_status=1,+silver_udhar,-silver_udhar)) as silver'
        )->first();
        
        //print_r($sum_data->toArray());

        return (object)[
            "amount" => ($first_data->amount??0) + ($sum_data->amount??0),
            "gold" => ($first_data->gold??0) + ($sum_data->gold??0),
            "silver" => ($first_data->silver??0) + ($sum_data->silver??0),
        ];
    }
}
