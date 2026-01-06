<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {

        Schema::create('subscription_module_permission', function (Blueprint $table) {

            $table->id() ;
            $table->unsignedBigInteger('product_id') ;
            $table->unsignedBigInteger('module_id') ;
            $table->timestamps() ;

            // $table->foreign('module_id')->references('id')->on('subscription_modules')->onDelete('cascade') ;
            // $table->foreign('product_id')->references('id')->on('software_products')->onDelete('cascade') ;

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_module_permission');
    }
};
