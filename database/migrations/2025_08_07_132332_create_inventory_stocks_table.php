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
        Schema::create('inventory_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('item_id');
            $table->text('image')->nullable();
            $table->string('huid')->nullable();
            $table->unsignedBigInteger('group_id');
            $table->string('tag',)->nullable();
            $table->string('rfid')->nullable();
			$table->string('color')->nullable();
            $table->string('cleariry')->nullable();
            $table->string('crt')->nullable();
            $table->string('caret')->nullable();
            $table->string('tunch')->nullable();
            $table->unsignedInteger('count')->default('1');
            $table->double('gross');
            $table->double('less')->nullable();
            $table->double('net');
            $table->double('wastage')->nullable();
            $table->double('fine')->nullable();
            $table->enum('have_element',[1,0])->comment('0=Dont have stone,1 = Have Stone')->nullable();
            $table->double('element_charge')->nullable();
            $table->double('rate')->nullable();
            $table->double('charge')->nullable();
            $table->double('labour')->nullable();
            $table->enum('labour_unit',['p','w','r'])->nullable()->comment('p=Percentage,w=weight.r=rupees');
            $table->double('discount')->nullable();
            $table->enum('discount_unit',['p','r'])->nullable()->comment('p=Percentage,w=weight.r=rupees');
            $table->double('tax')->nullable();
            $table->enum('tax_unit',['p','w','r'])->nullable()->comment('p=Percentage,w=weight.r=rupees');
            $table->double('total')->nullable();
            $table->char('entry_num');
            $table->date('entry_date');
            $table->char('entry_mode');
            $table->char('stock_type');
			$table->enum('source',['ins','imp'])->default('ins')->('ins=>Created Stock,imp=>imported stock');
			$table->text('remark')->nullable();
            $table->tinyInteger('avail_count',5);
            $table->double('avail_gross');
            $table->double('avail_net');
            $table->double('avail_fine');
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
        Schema::dropIfExists('inventory_stocks');
    }
};
