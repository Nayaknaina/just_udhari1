<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebPage extends Model {

    use HasFactory ;

    protected $table = 'web_pages' ;

    protected $fillable  = [ 'title' , 'description' , 'meta_title' , 'meta_description'] ;

}
