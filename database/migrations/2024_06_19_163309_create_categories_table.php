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
        Schema::create('categories', function (Blueprint $table) {

            $table->id() ;
            $table->string('name')->nullable() ;   
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('slug')->nullable() ;
            $table->string('category_level')->default(1)->comment('1 Level 2 Level 3 Level 4 Level') ;
            $table->tinyInteger('status')->default(0)->comment('0 For Active 1 For Deactive') ;
            $table->unsignedBigInteger('shop_id')->nullable() ;
            $table->unsignedBigInteger('branch_id')->nullable() ;
            $table->timestamps() ;

            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade') ;
            $table->foreign('branch_id')->references('id')->on('shop_branches')->onDelete('cascade') ;
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade') ;

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
