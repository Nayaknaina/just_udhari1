<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory ;

    protected $fillable = [

		'purchase_id',
        'bill_num',
        'product_code',
        'bis',
        'rfid',
        'huid',
        'barcode',
        'qrcode',
        'product_name',
        'caret',
        'gross',
        'quantity',
        'fine',
        'available',
        'unit',
        'property',
        'rate',
        'labour_charge',
        'amount',
        'category_id',
        'item_type',
		'assoc_element',
        'element_name',
        'element_caret',
        'element_quant',
        'element_cost',
        'counter',
        'box',
        'branch_id',
        'shop_id',
        'entry_by',
        'role_id'

    ];

    public function categories() {

        return $this->belongsToMany(Category::class, 'stock_categories', 'stock_id', 'category_id')->where('source','s')->orderBy('categories.category_level');

    }

    public function purchase(){

        return $this->belongsTo(Purchase::class);

    }

    public function category(){

        return $this->belongsTo(Category::class);

    }
	 public function owncategory(){

        return $this->hasone(Category::class,'id','category_id');

    }
	
    public function counterplaced(){
        return $this->hasMany(Counter::class,'stock_id');
    }
	
	 public function sellitem(){
        return $this->hasMany(SellItem::class,'stock_id');
    }
	
	public function elements(){
        return $this->hasMany(ItemAssocElement::class,'product_id');
    }
	
	public function allcategories(){
        return $this->hasMany(StockCategory::class,'stock_id');
    }
    public function catofstockcats(){
        return $this->hasOne(StockCategory::class,'stock_id');
    }
}
