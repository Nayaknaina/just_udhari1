<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\CommonScope;

class DailyTransaction extends Model
{
    use HasFactory;
    protected $fillable = ["object","type","karet","purity","net","fine","value","status","source","action","action_on","shop_id","branch_id","entry_by","role_id","created_at","updated_at"];

    protected static function booted()
    {
        static::addGlobalScope(new CommonScope());
    }
    
}
