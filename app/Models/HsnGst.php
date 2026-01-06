<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\CommonScope;

class HsnGst extends Model
{
    use HasFactory;
    public static  $category = false;
    public static $type = false;
    protected $fillable = ["category","type","hsn","gst","status","active","shop_id","branch_id","entry_by","role_id"];

    protected static function booted()
    {
        static::addGlobalScope(new CommonScope());
    }

    public static function setFilter($category = null, $type = null)
    {
        if ($category) static::$category = $category;
        if ($type)     static::$type     = $type;
    }
    // 🔥 GST data getter
     public static function modelConditions()
    {
		$cond_array = [];
        if(self::$category){
            $cond_array['category'] = self::$category;
        }
        if(self::$type){
            $cond_array['type'] = self::$type;
        }
        /*return [
            'category' => self::$category,
            'type'     => self::$type,
        ];*/
		return(!empty($cond_array))?$cond_array:null;
    }

}
