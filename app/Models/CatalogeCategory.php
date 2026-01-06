<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogeCategory extends Model
{
    use HasFactory ;

    protected $fillable = [
        'cataloge_id' , 
        'category_id' ,
        'shop_id' ,
        'branch_id' ,
        'created_at' , 
        'updated_at' , 
    ] ;

}
