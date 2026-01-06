<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class ShoppingList extends Model
{
    use HasFactory;

    protected $table = "shopping_list";
    protected $fillable = ['product_id', 'product_url', 'quantity','shop_id','branch_id', 'curr_cost', 'custo_id', 'list_type', 'entry_medium'] ;
    
    public function shop() {

        return $this->hasMany(Shop::class,'shop_id');

    }
    public function branch() {

        return $this->hasMany(ShopBranch::class,'branch_id');

    }
    public function product() {

        return $this->hasOne(EcommProduct::class,'id','product_id');

    }
    public function customer() {

        return $this->hasMany(Customer::class,'custo_id');

    }
    public function owncustomer() {

        return $this->belongsto(Customer::class,'custo_id');

    }
}
