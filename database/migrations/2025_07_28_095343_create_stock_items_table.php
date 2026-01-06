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
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->unsignedBigInteger('group_id');
            $table->string('hsn_code')->nullable();
            $table->char('tag_prefix',5)->nullable();
            $table->unsignedBigInteger('tag_digit')->nullable()->comment('Length of Digit of Tag Suffix');
            $table->unsignedBigInteger('curr_max_tag')->default(0)->comment('store the max num of tag suffix');
            $table->unsignedBigInteger('labour_value')->nullable();
            $table->unsignedBigInteger('labour_unit')->nullable();
            $table->unsignedBigInteger('tax_value')->nullable();
            $table->unsignedBigInteger('tax_unit')->nullable();
            $table->tinyinteger("karet")->nullable();
            $table->double("tounch")->nullable();
            $table->double("wastage")->nullable();
            $table->enum("stock_method",['loose','tag'])->nullable();
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
        Schema::dropIfExists('stock_items');
    }
};
