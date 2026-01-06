<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogeImage extends Model
{
    use HasFactory ;

    protected $fillable = ['cataloge_id', 'images'];

    public function catalog() {

        return $this->belongsTo(Catalog::class) ;

    }

}
