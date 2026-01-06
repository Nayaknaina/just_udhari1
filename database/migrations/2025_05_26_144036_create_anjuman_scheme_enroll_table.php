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
        Schema::create('anjuman_scheme_enroll', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scheme_id');
            $table->string('custo_id');
            $table->string('custo_name');
            $table->date('enroll_date');
            $table->enum('status',[1,0])->default(1);
            $table->text('remark')->default('New Enrollment !');
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
        Schema::dropIfExists('anjuman_scheme_enroll');
    }
};
