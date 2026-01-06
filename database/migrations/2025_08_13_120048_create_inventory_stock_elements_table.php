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
        Schema::create('inventory_stock_elements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_stock_id');
            $table->string('element');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->string('caret')->nullable();
            $table->string('part')->nullable();
            $table->string('colour')->nullable();
            $table->integer('piece')->nullable();
            $table->decimal('clarity')->nullable();
            $table->decimal('gross')->nullable();
            $table->decimal('less')->nullable();
            $table->decimal('net')->nullable();
            $table->decimal('wastage')->nullable();
            $table->decimal('fine')->nullable();
            $table->decimal('tunch')->nullable();
            $table->double('rate')->nullable();
            $table->double('cost')->nullable();
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('entry_by')->nullable();
            $table->unsignedBigInteger('role_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_stock_elements');
    }
};
