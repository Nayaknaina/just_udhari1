<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('purchase_id');
            $table->string('bill_num');
            $table->string('product_code');
            $table->string('rfid');
            $table->string('bis');
            $table->string('huid');
            $table->string('barcode');
            $table->string('product_name');
            $table->double('caret')->nullable();
            $table->double('gross')->nullable();
            $table->double('quantity');
            $table->double('fine')->nullable();
            $table->double('available');
            $table->double('counter');
            $table->enum('unit',['grms','count']);
            $table->text('property')->nullable();
            $table->double('rate');
            $table->double('labour_charge');
            $table->double('amount');
            $table->unsignedBiginteger('category_id');
            $table->enum('ecom_product',[1,0])->default(0)->comment("0=No Ecomm Product,1=Ecomm Product");
            $table->enum('item_type',['genuine','loose','artificial']);
            $table->enum('assoc_element',[1,0])->default(0)->comment("1=Assoc Element Present,0=Not Present,Assoc Element=Ruby,Dismond etc with jewellery");
            $table->text('element_name')->nullable();
            $table->text('element_caret')->nullable();
            $table->text('element_quant')->nullable();
            $table->text('element_cost')->nullable();
            $table->unsignedBiginteger('shop_id');
            $table->unsignedBiginteger('branch_id');
            $table->unsignedBiginteger('entry_by');
            $table->unsignedBiginteger('role_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
