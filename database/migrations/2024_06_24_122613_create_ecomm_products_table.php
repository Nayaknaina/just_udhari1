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
        Schema::create('ecomm_products', function (Blueprint $table) {

            $table->id() ;
            $table->string('name')->nullable() ;
            $table->decimal('rate', 10, 0)->default(0) ;
            $table->string('url')->nullable() ;
            $table->string('thumbnail_image')->nullable() ;
            $table->longText('description')->nullable() ;
            $table->string('meta_title')->nullable() ;
            $table->longText('meta_description')->nullable() ;
            $table->unsignedBigInteger('stock_id')->nullable() ;
            $table->unsignedBigInteger('branch_id')->nullable() ;
            $table->unsignedBigInteger('shop_id')->nullable() ;
            $table->timestamps() ;

            $table->foreign('stock_id')->references('id')->on('stocks')->onDelete('cascade') ;
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade') ;
            $table->foreign('branch_id')->references('id')->on('shop_branches')->onDelete('cascade') ; 

        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {

        Schema::dropIfExists('ecomm_products') ;

    }

} ;
