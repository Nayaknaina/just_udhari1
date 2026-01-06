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
        Schema::create('just_bills', function (Blueprint $table) {
            $table->id();
            $table->string('custo_name',50);
            $table->string('custo_mobile',15);
            $table->text('custo_addr');
            $table->string('custo_gst')->nullable();
            $table->unsignedBiginteger('custo_state');
            $table->string('bill_no');
            $table->enum('custom',[1,0])->default(0)->comment('1=Cstom Bill No,0=Auto Bill Num');
            $table->date('bill_date');
            $table->string('bill_gst');
            $table->string('bill_hsn');
            $table->unsignedBiginteger('bill_state');
            $table->double('count');
            $table->double('sub');
            $table->double('discount');
            $table->enum('gst_type',['in','ex'])->nullable()->comment('in=In the Cost Inclusive,ex= Out of Cost Exclusive');
            $table->double('gst');
            $table->double('sgst');
            $table->double('cgst');
            $table->double('igst');
            $table->decimal('roundoff',['10,2']);
            $table->double('total');
            $table->text('in_word')->nullable();
            $table->text('payment');
            $table->text('banking')->nullable()->comment("Vendor Bank Detail !");
            $table->double('remains');
            $table->string('remark');
            $table->unsignedBiginteger('shop_id');
            $table->unsignedBiginteger('branch_id');
            $table->unsignedBiginteger('entry_by')->nullable();
            $table->unsignedBiginteger('role_id')->nullable();
            $table->enum('status',[0,1])->default(1)->comment('1=>Active not Deleted,0=Deactive or Deleted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('just_bills');
    }
};
