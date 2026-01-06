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
        Schema::create('Sells', function (Blueprint $table) {
            $table->id();
            $table->enum('sell_to',['R','W'])->nullable()->comment("R=Customer(End User) ,W = Whole Seller");
            $table->unsignedBiginteger('custo_id')->default(0);
            $table->string('custo_name',50);
            $table->string('custo_mobile',15);
            $table->string('custo_gst');
            $table->unsignedBiginteger('custo_state')->nullable();
            $table->text('custo_bank')->nullable();
            $table->string('bill_no');
            $table->date('bill_date');
            $table->string('bill_gst');
            $table->string('bill_hsn');
            $table->unsignedBiginteger('bill_state');
            $table->unsignedBiginteger('count');
            $table->double('sub_total');
            $table->enum('gst_apply',[0,1])->default(1)->comment('1=Gst Apply,0=Gst Not Apply');
            $table->enum('gst_type',['in','ex'])->nullable()->comment('in=Inclusive,ex=exclusive');
            $table->double('gst');
            $table->double('sgst');
            $table->double('cgst');
            $table->double('igst');
            $table->double('discount');
            $table->double('roundoff');
            $table->double('total');
            $table->text('in_word')->nullable();
            $table->double('payment');
            $table->double('remains');
            $table->double('refund')->default(0);
            $table->text('banking')->nullable();
            $table->text('declaration')->nullable();
            $table->string('remark');
            $table->unsignedBiginteger('shop_id');
            $table->unsignedBiginteger('branch_id');
            $table->unsignedBiginteger('entry_by')->nullable();
            $table->unsignedBiginteger('role_id')->nullable();
            $table->enum('delete_status',[0,1])->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Sells');
    }
};
