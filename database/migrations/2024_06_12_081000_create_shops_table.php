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
        Schema::create('shops', function (Blueprint $table) {

            $table->id() ;
            $table->string('shop_name')->nullable() ;
            $table->string('name')->nullable() ;
            $table->string('mobile_no')->nullable() ;
            $table->string('whatsapp_no')->nullable() ;
            $table->string('email')->nullable() ;
            $table->string('role_suffex')->nullable() ;
            $table->string('domain_name')->nullable() ;
            $table->unsignedBigInteger('subscription_id')->nullable() ;
            $table->date('expiry_date')->nullable() ;
            $table->tinyInteger('status')->default(0)->comment('0 For Active 1 For Deactive') ;
            $table->timestamps() ;

            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade') ;

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
