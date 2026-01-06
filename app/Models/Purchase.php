<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory ;

    
    protected $fillable = [

        'supplier_id',
        'bill_no',
        'bill_date',
        'batch_no',
        'total_quantity',
        'total_weight',
        'total_fine_weight',
        'total_amount',
        'pay_amount',
		'remain',
        'refund',
        'stock_type',
        'branch_id',
        'shop_id',
		'entry_by',
        'role_id'

    ] ;

    
    public function supplier() {

        return $this->belongsTo(Supplier::class) ;

    }

    public function branch() {

        return $this->belongsTo(ShopBranch::class) ;

    }

    public function stocks() {

        return $this->hasMany(PurchaseItem::class);

    }
	
	public function storestock(){
        return $this->hasMany(Stock::class);
    }
	
	public function payments(){
        return $this->hasMany(BillTransaction::class,'bill_id')->where('source','p');
    }
	
	///-------------CODE 96---------------------------///
    protected static function booted()
    {
        static::deleting(function ($bill) {
            // Delete all associated items
            //$bill->stocks()->delete();
        });
    }
    
}
