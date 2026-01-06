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
        Schema::create('ecomm_web_information', function (Blueprint $table) {

            $table->id() ;
            $table->string('logo')->nullable() ;
            $table->string('web_title')->nullable() ;
            $table->string('mobile_no')->nullable() ;
            $table->string('mobile_no_2')->nullable() ;
            $table->string('email')->nullable() ;
            $table->string('email_2')->nullable() ;
            $table->string('unique_id')->nullable() ;
            $table->text('address')->nullable() ;
            $table->text('map')->nullable() ;
            $table->string('meta_title',500)->nullable() ;
            $table->text('meta_description')->nullable() ;
            $table->unsignedBigInteger('branch_id') ;
            $table->unsignedBigInteger('shop_id') ;
            $table->timestamps() ;

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecomm_web_information');
    }
};
