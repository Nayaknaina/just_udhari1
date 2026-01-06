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
        Schema::create('shop_schemes', function (Blueprint $table) {
            $table->id();
            $table->string('scheme_img');
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('scheme_id');
            $table->string('scheme_head');
            $table->string('scheme_sub_head');
            $table->text('scheme_detail_one');
            $table->text('scheme_detail_two');
            $table->boolean('ss_status');
            $table->timestamps();

            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
            $table->foreign('scheme_id')->references('id')->on('schemes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_schemes');
    }
};
