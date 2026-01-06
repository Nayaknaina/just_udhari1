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
        Schema::create('suppliers', function (Blueprint $table) {

            $table->id() ;
            $table->string('name')->nullable() ;
			$table->unsignedBigInteger("supplier_num")->nullable();
            $table->string('supplier_name')->nullable() ; 
            $table->string('mobile_no')->nullable() ; 
            $table->string('gst_num')->nullable() ; 
            $table->string('unique_id')->nullable() ; 
            $table->longText('address')->nullable() ; 
            $table->unsignedBigInteger('state')->nullable() ;
            $table->unsignedBigInteger('district')->nullable() ;
            $table->unsignedBigInteger('shop_id')->nullable() ;
            $table->unsignedBigInteger('branch_id')->nullable() ;
            $table->timestamps() ;

            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade') ;
            $table->foreign('branch_id')->references('id')->on('shop_branches')->onDelete('cascade') ; 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
