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
        Schema::create('shop_products', function (Blueprint $table) {

            $table->id() ;
            $table->date('expiry_date')->nullable() ;
            $table->unsignedBigInteger('product_id') ;
            $table->unsignedBigInteger('shop_id') ;
            $table->timestamps() ;

            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade') ;
            $table->foreign('product_id')->references('id')->on('software_products')->onDelete('cascade') ; 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_products');
    }
};
