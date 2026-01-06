<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory ;

    protected $table = "purchaseitems";
    protected $fillable = [

        'counter_id',
        'box_no',
        'mfg_date',
        'category_id',
        'name',
        'rate',
        'quantity',
        'carat',
        'gross_weight',
        'net_weight',
        'purity',
        'wastage',
        'fine_purity',
        'fine_weight',
        'labour_charge',
        'amount',
        'bis',
		'barcode',
		'qrcode',
        'rfid',
        'huid',
        'product_code' ,
        'ecom_product' ,
        'supplier_id' ,
        'purchase_id' ,
        'branch_id',
        'shop_id',
        'item_type',

    ];

	public function categories() {

        return $this->belongsToMany(Category::class, 'stock_categories', 'stock_id', 'category_id')->where('source','p')->orderBy('categories.category_level');

    }
/*
    public function categories() {

        return $this->belongsToMany(Category::class, 'stock_categories', 'stock_id', 'category_id') ;

    }
*/
    public function purchase(){

        return $this->belongsTo(Purchase::class);

    }

    public function category(){

        return $this->belongsTo(Category::class);

    }

    public function elements(){
        return $this->hasMany(ItemAssocElement::class,'product_id');
    }
	
    public function counter(){
        return $this->hasMany(Counter::class,'stock_id');
    }
}
