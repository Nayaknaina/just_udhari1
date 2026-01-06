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
        Schema::create('id_tag_size', function (Blueprint $table) {
            $table->id();
            $table->string('name',20);
            $table->string('machine');
            $table->string('tag');
            $table->string('image');
            $table->string('info');
            $table->string('one');
            $table->string('two');
            $table->enum('status',[1,0])->comment('1=Default Size,0=Optional');
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
        Schema::dropIfExists('id_tag_size');
    }
};
