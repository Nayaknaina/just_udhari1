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
        Schema::create('udhar_transaction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('udhar_id');
            $table->enum('custo_type',['s','c'])->comment('s=aupplier,c=customer');
            $table->unsignedBiginteger('custo_id');
            $table->enum('source',['S','P','D'])->comment('S=Sell Bill,P=Purchase Bill,D=Direct(From the Udhar Panel),C=>Coversion(One Form to Another Conversion)');

            $table->double('amount_udhar')->nullable();
            $table->enum('amount_udhar_holder',['B','S'])->nullable()->comment('B=Bank/Online,S=Shop/Offline');
            $table->enum('amount_udhar_status',[1,0])->nullable()->comment('1=Plus(Increase In Stock Udhar return),0=Minus(Udhat taken)');

            $table->double('gold_udhar')->nullable();
            $table->enum('gold_udhar_status',[1,0])->nullable()->comment('1=Plus(Increase In Stock Udhar return),0=Minus(Udhat taken)');
            
            $table->double('sliver_udhar')->nullable();
            $table->enum('sliver_udhar_status',[1,0])->nullable()->comment('1=Plus(Increase In Stock Udhar return),0=Minus(Udhat taken)');

            $table->enum('action',['A','E','U','D','C'])->default('A')->comment('A=Record Addess,E=Record Edited,U=Record Updated,D=record Deleted,C=Conversion');
            $table->unsignedBigInteger('target')->default(0)->comment('The ID of Target Entry In Case E/U/D the id will belongs to Self table,But In Case of Conversion The Id will Belongs to udhar_conversion Table ');
            
            $table->date('date')->nullable();
            $table->text('custom_remark')->nullable();
            $table->text('remark')->nullable();
            $table->unsignedBiginteger('entry_by')->nullable();
            $table->unsignedBiginteger('role_id')->nullable();
            $table->unsignedBiginteger('shop_id');
            $table->unsignedBiginteger('branch_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('udhar_transaction');
    }
};
