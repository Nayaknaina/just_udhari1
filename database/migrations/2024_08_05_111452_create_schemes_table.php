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
        Schema::create('schemes', function (Blueprint $table) {
            $table->id();
            $table->string('scheme_unique');
            $table->string('scheme_head');
            $table->string('scheme_sub_head');
            $table->string('scheme_detail_one');
            $table->string('scheme_table_one');
            $table->string('scheme_detail_two');
            $table->string('scheme_table_two');
            $table->date('scheme_validity');
            $table->string('scheme_validity_scale');
            $table->boolean('scheme_emi');
            $table->decimal('scheme_interest', 8, 2);
            $table->decimal('scheme_interest_value', 8, 2);
            $table->string('scheme_interest_scale');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schemes');
    }
};
