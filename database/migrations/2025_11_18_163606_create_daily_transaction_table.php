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
        Schema::create('daily_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('object',['gold','silver','stone','money']);
            $table->enum('type',['usual','old','online','cash','upi'])->nullable()->comment('for gold/silver=usual/old here usual means a sellable prodict/item of shop (jewellery/bullion/loose)');
            $table->character('karet')->nullable();
            $table->double('purity')->nullable();
            $table->double('net')->nullable();
            $table->double('fine')->nullable();
            $table->double('value')->nullable();
            $table->enum('holder',['shop','bank'])->default('shop');
            $table->unsignedBigInteger('holder_id')->nullable();
            $table->enum('status',[1,0,'N'])->comment('1 = Plus,0=Minus,N=noEffect');
            $table->enum('source',['sll','prc','udh','imp','ins'])->comment('sll=Sell Bill,prc=purchase bill,udh=udhar,imp = import,ins=insert(on stock listing)');
            $table->unsignedBigInteger('reference')->nullable()->comment('The ID of Source');
            $table->enum('action',['A','E','U','D'])->default('A')->comment('A=Record Added,E=Record Edited,U=Record Updated,D=Record Deleted');
            $table->unsignedBigInteger('action_on')->nullable()->comment('the id of self table which has been operate for E/U/D');
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
        Schema::dropIfExists('daily_transactions');
    }
};
