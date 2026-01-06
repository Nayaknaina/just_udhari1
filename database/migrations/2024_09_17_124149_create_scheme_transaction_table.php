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
        Schema::create('scheme_transaction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enroll_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('scheme_id');
            $table->tinyInteger('emi_num',2)->nullable();
            $table->double('emi_amnt')->default(0);
            $table->date('emi_date')->default(date("Y-m-d H:i:s"));
            $table->double('bonus_amnt')->default(0);
            $table->enum('bonus_type',['T','B'])->default('B')->comment('T=Token Amount,B= Bonus( Interest )') ;
            $table->enum('pay_mode',['SYS','ECOMM'])->default('SYS');
            $table->string('pay_medium')->default();
            $table->enum('stock_status',[0,1])->comment("1 = When Customer Pay,0 = When Vendor(Draw) pay")->nullable();
            $table->string('remark')->default('Paid') ;
            $table->timestamps();
            $table->foreign('enroll_id')->references('id')->on('enroll_customers');
            $table->foreign('branch_id')->references('id')->on('shop_branches');
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->foreign('scheme_id')->references('id')->on('shop_schemes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheme_transaction');
    }
};
