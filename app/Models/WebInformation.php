<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebInformation extends Model {

    use HasFactory ;

    protected $table = 'web_informations' ;

    protected $fillable = ['name' ,'logo' ,'mobile_no' ,'whatsapp_no' ,'email' ,'address' ,'map' ] ;

}
