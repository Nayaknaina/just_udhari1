<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission {

    protected $fillable = [

        'name' , 'parent_id'  , 'guard_name'

     ] ;

    public function parent() {

        return $this->belongsTo(Permission::class, 'parent_id');

    }

    public function children(){

        // return $this->hasMany(Permission::class, 'parent_id', 'id');
        return $this->hasMany(Permission::class, 'parent_id');

    }

    public function shopRights() {

        return $this->hasMany(ShopRight::class) ;

    }

}
