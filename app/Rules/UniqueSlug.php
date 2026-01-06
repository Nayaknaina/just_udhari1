<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueSlug implements Rule
{
    protected $tableName;
    protected $shopId;

    public function __construct($tableName, $shopId)
    {
        $this->tableName = $tableName;
        $this->shopId = $shopId;
    }

    public function passes($attribute, $value)
    {
        $query = DB::table($this->tableName)->where('slug', $value);

        if ($this->shopId === null) {

            $query->whereNull('shop_id');

        } else {

            $query->where('shop_id', $this->shopId);

        }
 
        return $query->doesntExist();
    }

    public function message() {

        return 'The :attribute has already been taken.';

    }
}
