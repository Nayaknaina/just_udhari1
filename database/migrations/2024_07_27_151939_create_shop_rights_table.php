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
        Schema::create('shop_rights', function (Blueprint $table) {

            $table->id() ;
            $table->unsignedBigInteger('product_id')->nullable() ;
            $table->unsignedBigInteger('permission_id')->nullable() ;
            $table->unsignedBigInteger('shop_id')->nullable() ;
            $table->timestamps() ;

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_rights');
    }
};
