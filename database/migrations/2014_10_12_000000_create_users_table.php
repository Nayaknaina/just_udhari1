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
        Schema::create('users', function (Blueprint $table) {

            $table->id() ;
            $table->string('name') ;
            $table->string('email')->nullable() ;
            $table->string('mobile_no')->nullable() ;
            $table->string('user_name')->nullable() ;
            $table->timestamp('email_verified_at')->nullable() ;
            $table->string('password')->nullable() ;
            $table->string('mpin')->nullable() ;
            $table->tinyInteger('status')->default(0)->comment('0 For Active 1 For Deactive') ;
            $table->tinyInteger('user_type')->default(1)->comment('0 Admin ,1 Users') ;
            $table->unsignedBigInteger('branch_id')->nullable() ;
            $table->unsignedBigInteger('shop_id')->nullable() ;
            $table->rememberToken() ;
            $table->timestamps() ;

            // $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade') ;
            // $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade') ;

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
