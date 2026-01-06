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
        Schema::create('sell_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('sell_id');
            $table->unsignedBiginteger('stock_id');
            $table->string('item_name');
            $table->unsignedBiginteger('item_quantity');
            $table->enum('quantity_unit',['unit','grm'])->default('grm');
            $table->unsignedBiginteger('item_caret')->nullable();
            $table->double('item_rate');
            $table->double('item_cost');
            $table->double('labour_perc')->nullable();
            $table->double('labour_charge')->nullable();
            $table->text('elements')->nullable();
            $table->double('total_amount');
            $table->enum('item_type',['artificial', 'genuine', 'loose'])->nullable();
            $table->unsignedBiginteger('shop_id');
            $table->unsignedBiginteger('branch_id');
            $table->unsignedBiginteger('entry_by')->nullable();
            $table->unsignedBiginteger('role_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sell_items');
    }
};
