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
        Schema::create('new_bill_payments', function (Blueprint $table) {
            $table->id();
            $table->enum('bill_type',['s','p'])->comment('S=Sell,P=Purchase');
            $table->enum('bill_id',['s','p'])->comment('S=Sell,P=Purchase');
            $table->string('pay_method')->comment('Cash, Check, gold, silver etc');
            $table->string('pay_quantity')->comment('Cash, Check, gold, silver IN case of Gold/Silver the json og gross,net.fine')->nullable();
            $table->string('pay_rate')->comment('In case of gold/silver gold rate')->nullable();
            $table->string('pay_value')->comment('In value in Rupees Default Pay or Gold/Silver Valuation !');
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('entry_by')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_bill_payments');
    }
};
