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
        Schema::create('cataloge_categories', function (Blueprint $table) {

            $table->id() ;
            $table->unsignedBigInteger('cataloge_id') ;
            $table->unsignedBigInteger('category_id') ;
            $table->timestamps() ;

            $table->foreign('cataloge_id')->references('id')->on('catalogues')->onDelete('cascade') ;
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade') ; 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cataloge_categories');
    }
};
