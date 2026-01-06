<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftwareProduct extends Model
{
    use HasFactory ;

    protected $fillable = [ 'title' , 'url' , 'price' , 'image' , 'banner_image' , 'description' , 'status' , 'type' ,
    'role_id'  , 'meta_title' , 'meta_description' ] ;

    public function roles() {

        return $this->belongsTo(Role::class,'role_id' , 'id') ;

    }

    public function shopRights()  {

        return $this->hasMany(ShopRight::class , 'product_id') ;

    }

}
