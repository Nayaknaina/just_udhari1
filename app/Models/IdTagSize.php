<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdTagSize extends Model
{
    use HasFactory;
    protected $fillable = ['name','machine','tag','image','info','one','two','status','shop_id','branch_id'];
    protected $table = "id_tag_size";

    public static function allsizes(){
        $cond = ['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id];
        return Self::where($cond)->get();
    }
    public static function size($name=false){
        $cond = ['shop_id'=>auth()->user()->shop_id,'branch_id'=>auth()->user()->branch_id];
        return Self::where($cond)->where('name',$name)->first();
    }

}
