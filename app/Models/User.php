<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens , HasFactory , Notifiable , HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = ['name', 'user_name', 'email' , 'password' , 'mpin' , 'mobile_no', 'branch_id', 'shop_id', 'status','user_type'] ;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'mpin',
        'remember_token',
    ] ;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ] ;

    public function shop() {

        return $this->belongsTo(Shop::class) ;

    }

    public function shopbranch() {

        return $this->belongsTo(ShopBranch::class, 'branch_id') ;

    }

	public function banking(){
        return $this->hasOne(Banking::class,'shop_id','shop_id')->where('branch_id',$this->branch_id);
    }


    public function subscriptions(){

        return $this->hasMany(SoftwareProductSubscription::class, 'shop_id', 'shop_id') ;

    }

    public function ShopRight(){

        return $this->hasMany(ShopRight::class, 'shop_id', 'shop_id') ;

    }

    // public function permissions() {
    //     return $this->belongsToMany(Permission::class);
    // }

    // public function roles() {
    //     return $this->belongsToMany(Role::class);
    // }

    // public function permissionsThroughRoles() {
    //     return $this->roles()->with('permissions')->get()->pluck('permissions')->flatten()->unique();
    // }
	public function schemes(){
        return $this->hasMany(ShopScheme::class, 'shop_id', 'shop_id') ;
    }

	//---Written at 19-11-2024 by 96---------------------------------//
	public function gateways(){
        return $this->hasMany(PaymentGatewaySetting::class, 'shop_id', 'shop_id') ;
    }
}
