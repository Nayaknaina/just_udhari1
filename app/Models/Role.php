<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole {

    use HasFactory ;

    protected $fillable = ['name', 'guard_name' , 'branch_id','shop_id'] ; 

        public function product() {

            return $this->belongsTo(SoftwareProduct::class , 'id' , 'role_id');

        }

        
}
