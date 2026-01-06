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

            $table->id() ;
            $table->string('name')->nullable() ;
            $table->decimal('quantity', 10, 0)->default(0) ;
            $table->decimal('carat', 10, 0)->default(0) ;
            $table->decimal('gross_weight', 10, 3)->default(0) ;
            $table->decimal('net_weight', 10, 3)->default(0) ;
            $table->decimal('purity', 10, 2)->default(0) ;
            $table->decimal('wastage', 10, 3)->default(0) ;
            $table->decimal('fine_purity', 10, 2)->default(0) ;
            $table->decimal('fine_weight', 10, 3)->default(0) ;
            $table->decimal('labour_charge', 10, 0)->default(0) ;
            $table->decimal('rate', 10, 0)->default(0) ;
            $table->decimal('amount', 10, 0)->default(0) ;
            $table->tinyInteger('bis')->default(1)->comment('0 Yes 1 No') ;
            $table->string('huid')->nullable() ;
            $table->string('barcode')->nullable() ;
            $table->string('qrcode')->nullable() ;
            $table->string('rfid')->nullable() ;
            $table->unsignedBigInteger('counter_id')->nullable() ;
            $table->string('box_no')->nullable() ;
            $table->date('mfg_date')->nullable() ;
            $table->unsignedBigInteger('category_id')->nullable() ;
            $table->unsignedBigInteger('supplier_id')->nullable() ;
            $table->string('product_code')->nullable() ;
            $table->bigInteger('type')->default(1)->comment('1 Udhari Stock 2 Ecommerce ') ;      
            $table->tinyInteger('ecom_product')->default(1)->comment('0 Yes 1 No') ;
            $table->unsignedBigInteger('purchase_id')->nullable() ;
            $table->unsignedBigInteger('shop_id')->nullable() ;
            $table->unsignedBigInteger('branch_id')->nullable() ;
            $table->timestamps() ;

            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade') ;
            $table->foreign('counter_id')->references('id')->on('counters')->onDelete('cascade') ;
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade') ;
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade') ;
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade') ;
            $table->foreign('branch_id')->references('id')->on('shop_branches')->onDelete('cascade') ; 

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
