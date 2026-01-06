<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopRight extends Model {

    use HasFactory ;

    protected $fillable = ['product_id','permission_id','shop_id'] ;

    public function permission() {

        return $this->belongsTo(Permission::class , 'permission_id' , 'id' ) ;

    }

}
