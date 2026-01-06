<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory ;

    protected $fillable = [
        'name',
        'supplier_name',
        'mobile_no',
		'gst_num',
        'unique_id',
        'address',
        'state',
        'district',
        'shop_id',
        'branch_id',
    ] ;

    public function shop() {

        return $this->belongsTo(Shop::class) ;

    }

    public function branch() {

        return $this->belongsTo(ShopBranch::class) ;

    }

}
