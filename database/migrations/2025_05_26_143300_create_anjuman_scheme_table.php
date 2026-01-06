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
        Schema::create('anjuman_scheme', function (Blueprint $table) {
            $table->id();
            $table->enum('type',['gold','cash']);
            $table->string('title');
            $table->text('detail');
            $table->unsignedBigInteger('validity')->nullable();
            $table->enum('fix_emi',[1,0])->comment('1=Fix Emi,0=variable EMi')->default(0);
            $table->douncle('emi_quant')->nullable();
            $table->date('start')->nullable();
            $table->enum('status',[1,0])->default(1);
            $table->text('remark')->default('Created !');
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
        Schema::dropIfExists('anjuman_scheme');
    }
};
