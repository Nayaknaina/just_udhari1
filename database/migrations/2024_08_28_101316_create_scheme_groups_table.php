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
        Schema::create('scheme_groups', function (Blueprint $table) {

            $table->id();
            $table->string('group_name')->nullable() ;
            $table->tinyInteger('status')->default(0)->comment('0 For Active 1 For Deactive') ;
            $table->unsignedBigInteger('scheme_id')->nullable() ;
            $table->unsignedBigInteger('branch_id')->nullable() ;
            $table->unsignedBigInteger('shop_id')->nullable() ;
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheme_groups');
    }
};
