<?php

// Category.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'parent_id', 'slug', 'category_level'];

    public function stocks(){

        return $this->belongsToMany(Product::class, 'stock_categories') ; 

    }

    public function parent() {

        return $this->belongsTo(Category::class, 'parent_id') ;

    }

    public function children() {

        return $this->hasMany(Category::class, 'parent_id') ;

    }

    public function cataloges(){

        return $this->belongsToMany(Cataloge::class, 'cataloge_categories', 'category_id', 'cataloge_id') ; 

    }
	//----Stock Relation By 96-------------//
    public function stockexist(){
        return $this->hasMany(Stock::class, 'category_id') ;
    }
	
	public function stonestock(){
        return $this->hasMany(Stock::class, 'category_id') ; 
    }
}
