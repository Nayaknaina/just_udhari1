<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CommonScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // Global shop filter for all models
            $builder->where('shop_id', auth()->user()->shop_id);

        // Model-specific conditions (if provided)
        if (method_exists($model, 'modelConditions')) {
			if(!empty($model::modelConditions())){
				foreach ($model::modelConditions() as $col => $val) {
					$builder->where($col, $val);
				}
			}
        }
    }
}
