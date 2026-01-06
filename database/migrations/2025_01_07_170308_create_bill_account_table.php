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
        Schema::create('bill_account', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('person_id');
            $table->enum('person_type',['C','S'])->default('C')->comment("C=>Customer,S=>Supplier");
            $table->unsignedBiginteger('bill_id');
            $table->string('bill_number');
            $table->enum('bill_type',['S','P'])->default('S')->comment("S=>Sell Bill,P=>Purchase Bill");
            $table->double('amount');
            $table->enum('category',[1,0])->default('1')->comment("1=>Balance Plus,0=>Balance Minus");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_account');
    }
};
