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
        Schema::create('anjuman_scheme_txns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scheme_id');
            $table->unsignedBigInteger('enroll_id');
            $table->double('txn_quant');
			$table->unsignedBigInteger('emi_num')->nullable();
            $table->enum('txn_status',[1,0,'N'])->default(1)->comment('1=Depositi,0=Withdraw,N=No Effect');
            $table->enum('txn_action',['A','U','E','D'])->default('A')->comment('A=Add,E=Edit,U=Update,D=Delete');
			$table->unsignedBigInteger('target_id')->nullable()->comment('The id of self row where the edit perform !');
            $table->date('txn_date');
            $table->text('remark');
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('enty_by')->nullable()->comment("Opertaor Id");
            $table->unsignedBigInteger('role_id')->nullable()->comment('Role Id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anjuman_scheme_txns');
    }
};
