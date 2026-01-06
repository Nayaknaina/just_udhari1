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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->enum('bill_type',['s','p'])->comment('s=Sell,p=Purchase');
            $table->enum('bill_prop',['g','e'])->comment('e=Rough Estimate,g=GST');
            $table->string('bill_number');
            $table->date('bill_date');
            $table->date('due_date');
            $table->enum('party_type',['c','w','s'])->comment('c=Customer,w=Wholeseller,s=Supplier');
            $table->unsignedBigInteger('party_id')->comment('I from Customer Record (Customer/Wholeseller/Supplier)');
            $table->string('party_name');
            $table->string('party_mobile');
            $table->tinyInteger('items');
            $table->double('sub');
            $table->double('discount');
            $table->enum('discount_unit',['r','p'])->comment('r=Rs,p=%');
            $table->double('gst');
            $table->double('sgst')->nullable();
            $table->double('igst')->nullable();
            $table->double('cgst')->nullable();
            $table->double('round');
            $table->double('total');
            $table->double('payment');
            $table->double('balance');
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('entry_by')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
			$table->enum('status',['c','d','r'])->default('c')->comment('c=Bill stay,d=bill delete only(Stock retain same),r=>Bill Deleted & Stock return');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
