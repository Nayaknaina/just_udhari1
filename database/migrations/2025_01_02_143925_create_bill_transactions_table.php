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
        Schema::create('bill_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bill_id');
            $table->string('bill_no');
            $table->enum('source',['s','p'])->nullable()->comment('s=sell bill,p=purchase bill');
            $table->enum('mode',['on','off'])->nullable();
            $table->enum('medium',['PayTm','GPay','PhonPay','BharatPay','BHIM','WhatsAppPay','Scheme','Cash','Check'])->nullable();
            $table->double('amount')->default(0);
            $table->double('remains')->default(0);
            $table->enum('action_taken',['A','E','U','D'])->default("A");
            $table->unsignedBigInteger('action_on')->nullable();
            $table->enum('amnt_holder',['S','B']);
            $table->enum('stock_status',[0,1,'N'])->default('1');
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('branch_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sells_payments');
    }
};
