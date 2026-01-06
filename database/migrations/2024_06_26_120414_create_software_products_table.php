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
        Schema::create('software_products', function (Blueprint $table) {

            $table->id() ;
            $table->string('title')->nullable() ;
            $table->string('url')->nullable() ;
            $table->string('price')->nullable() ;
            $table->string('image')->nullable() ;
            $table->string('banner_image')->nullable() ;
            $table->longText('description')->nullable() ;
            $table->tinyInteger('status')->default(0)->comment('0 For Active 1 For Deactive') ;
            $table->tinyInteger('type')->default(0)->comment('0 Main 1 Sub ') ;
            $table->text('meta_title')->nullable() ;
            $table->longText('meta_description')->nullable() ;
            $table->unsignedBigInteger('role_id')->nullable() ;
            $table->timestamps() ;

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('software_products');
    }
};
