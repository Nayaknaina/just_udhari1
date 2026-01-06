<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftwareProductSubscription extends Model {

    use HasFactory;

    protected $fillable = [

        'shop_id',
        'product_id',
        'expiry_date',

    ] ;

    // protected $casts = [
    //     'expiry_date' => 'date',
    // ] ;

    // Relationships

    public function shop() {

        return $this->belongsTo(Shop::class) ;

    }

    public function product() {

        return $this->belongsTo(SoftwareProduct::class) ;

    }

}
