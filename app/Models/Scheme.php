<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scheme extends Model {

    use HasFactory ;

    protected $fillable = [
        'scheme_unique' , 'scheme_head' , 'scheme_sub_head' , 'scheme_detail_one' , 'scheme_table_one' , 'scheme_detail_two' , 'scheme_table_two' , 'scheme_validity' , 'lucky_draw' , 'scheme_emi' , 'scheme_amount' , 'emi_date' , 'interest_type' , 'scheme_interest_scale' , 'scheme_interest' , 'scheme_interest_value','start_date_fix'  , 'role_id' , 'meta_title', 'meta_description'
        ] ;
    
    

}
