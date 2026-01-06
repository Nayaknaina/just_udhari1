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
        Schema::create('girvi_customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('girvi_id')->unique();
            $table->string('custo_name');
            $table->string('custo_mobile');
            $table->unsignedBigInteger('custo_id');
            $table->enum('custo_type',['c','s'])->default('c');
            $table->double('balance_principal')->default(0)->comment("The Remaind to Pay ot the Advance Deposited !");
            $table->double('balance_interest')->default(0)->comment("The Remaind to Pay ot the Advance Deposited !");
            $table->text('remark')->nullable();
            $table->enum('status',[1,0])->default(1);
            $table->unsignedBigInteger('entry_by')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
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
        Schema::dropIfExists('girvi_customers');
    }
};
