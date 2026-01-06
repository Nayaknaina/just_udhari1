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
        Schema::create('just_bill_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('bill_id');
            $table->string('bill_no');
            $table->string('name');
            $table->double('quant');
            $table->double('rate');
            $table->enum('unit',['grms','unit'])->default('grms');
            $table->double('charge');
            $table->double('sum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('just_bill_items');
    }
};
