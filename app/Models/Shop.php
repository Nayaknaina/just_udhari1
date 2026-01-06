<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory ;

    protected $fillable = ['shop_name', 'name', 'mobile_no', 'whatsapp_no', 'status','role_suffix'];

    public function branches() {

        return $this->hasMany(ShopBranch::class) ;

    }

    public function mainBranch() {
        return $this->belongsTo(ShopBranch::class, 'shop_id', 'id')
                    ->where('branch_type', 0) ;
    }

    public function users() {

        return $this->hasMany(User::class) ;

    }

    public function AdminUser() {

        return $this->belongsTo(User::class, 'shop_id', 'id')
                    ->where('user_type', 0) ;

    }

    public function products(){

        return $this->hasMany(ShopProduct::class);

    }
	
	 public function schemes(){
        return $this->hasMany(ShopScheme::class, 'shop_id', 'shop_id') ;
    } 

}
