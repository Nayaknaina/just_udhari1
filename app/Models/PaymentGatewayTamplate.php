<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGatewayTamplate extends Model
{
    use HasFactory;
    protected $fillable = ["icon","name","prod_url","test_url","parameter_list"];
}
