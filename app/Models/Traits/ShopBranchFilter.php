<?php 
namespace App\Models\Traits;

trait ShopBranchFilter
{
    protected static function filterShop($query)
    {
        return $query->where('shop_id', auth()->user()->shop_id);
    }

    protected static function filterShopBranch($query)
    {
        return $query
            ->where('shop_id', auth()->user()->shop_id)
            ->where('branch_id', auth()->user()->branch_id);
    }
}
