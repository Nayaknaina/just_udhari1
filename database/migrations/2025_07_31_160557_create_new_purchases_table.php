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
        Schema::create('new_purchases', function (Blueprint $table) {
            $table->id();
            $table->enum('bill_type',['e','i'])->comment('e=Estimate,i=Invoice or GST');
            $table->enum('entry_type',['m'])->comment('the entry type Manual, Diamong , By Tag etc.');
            $table->string('bill_no');
            $table->enum('party_type',['c','s','w'])->comment("C= Customer,S=Supplier,W=Wholesaler");
            $table->unsignedBigInteger('party_id')->comment("The id of the prev column");
            $table->double('ttl_gold');
            $table->double('ttl_silver');
            $table->double('bill_amount');
            $table->double('bill_remain');
            $table->date('entry_date');
            $table->date('due_date');
            $table->unsignedBigInteger('payment_id');
            $table->enum('bill_status',[1,0])->default(1);
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
        Schema::dropIfExists('new_purchases');
    }
};
