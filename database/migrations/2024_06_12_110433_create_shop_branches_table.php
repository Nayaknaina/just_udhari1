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
        Schema::create('shop_branches', function (Blueprint $table) {

            $table->id() ;
            $table->string('branch_name')->nullable() ;
            $table->string('name')->nullable() ;
            $table->string('email')->nullable() ;
            $table->string('mobile_no')->nullable() ;
            $table->string('gst_num')->nullable() ;
            $table->longText('address')->nullable() ;
            $table->string('domain_name')->nullable() ;
            $table->unsignedBigInteger('state')->nullable() ;
            $table->unsignedBigInteger('district')->nullable() ;
            $table->tinyInteger('branch_typ')->default(0)->comment('0 For Main Branch 1 For DSub Branch') ;
            $table->tinyInteger('status')->default(0)->comment('0 For Active 1 For Deactive') ;
            $table->unsignedBigInteger('shop_id')->nullable() ;
            $table->timestamps() ;

            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade') ;

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_branches');
    }
};
