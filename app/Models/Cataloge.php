<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cataloge extends Model
{
    
    use HasFactory ;

    protected $table = 'catalogues' ;

    protected $fillable = [
		'unique',
        'name' , 
        'images' , 
        'weight' , 
        'short_order' , 
        'status' ,  
        'categoty_id' , 
        'shop_id' , 
        'branch_id' , 

    ] ;

    public function categories() {

        return $this->belongsToMany(Category::class, 'cataloge_categories', 'cataloge_id', 'category_id') ;

    }

    public function category() {

        return $this->belongsTo(Category::class,'category_id' , 'id' ) ;

    }

    public function catalogecategories() {

        return $this->belongsToMany(Category::class, 'cataloge_categories', 'cataloge_id', 'category_id') ;

    }

    public function branch() {

        return $this->belongsTo(ShopBranch::class) ;

    }

    public function catalogeimages() {

        return $this->hasMany(CatalogeImage::class) ;

    }

}
