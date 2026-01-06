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
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->enum('bill_type',['s','p'])->comment('Which type of bill Sell/Purchase !');
            $table->enum('op_type',['s','p'])->comment('How the item participate (Use to Pay the bill cost or just to list in bill_type)');
            $table->unsignedBigInteger('bill_id');
            $table->unsignedBigInteger('item_id');
            $table->text('item_name');
            $table->string('tag')->nullable();
            $table->tinyInteger('caret')->nullable();
            $table->double('piece')->nullable();
            $table->double('gross')->nullable();
            $table->double('less')->nullable();
            $table->double('net')->nullable();
            $table->double('tunch')->nullable();
            $table->double('wastage')->nullable();
            $table->double('fine')->nullable();
            $table->double('element')->nullable();
            $table->double('rate')->nullable();
            $table->double('labour')->nullable();
            $table->enum('labour_unit',['p','w','r'])->nullable()->comment('p=%,w=gm,r=Rs');
            $table->double('other')->nullable();
            $table->double('discount')->nullable();
            $table->enum('discount_unit',['p','r'])->nullable()->comment('p=%,r=Rs');
            $table->double('total')->nullable();
			$table->enum('stock_type',['gold','silver','artificial','stone']);
			$table->enum('entry_mode',['both','loose','tag']);
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
        Schema::dropIfExists('bill_items');
    }
};
