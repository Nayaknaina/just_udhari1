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
        Schema::create('purchases', function (Blueprint $table) {
           
            $table->id() ;
            $table->string('bill_no')->nullable() ;
            $table->date('bill_date')->nullable() ;
            $table->string('batch_no')->nullable() ;
            $table->unsignedBigInteger('supplier_id')->nullable() ;
            $table->decimal('total_quantity',10, 0)->default(0) ;
            $table->decimal('total_weight', 10, 3)->default(0) ;
            $table->decimal('total_fine_weight', 10, 3)->default(0) ;
            $table->decimal('total_amount', 10, 0)->default(0) ;
            $table->decimal('pay_amount', 10, 0)->default(0) ;
            $table->unsignedBigInteger('shop_id')->nullable() ;
            $table->unsignedBigInteger('branch_id')->nullable() ;
			$table->unsignedBigInteger('entry_by')->nullable() ;
            $table->unsignedBigInteger('role_id')->nullable() ;
            $table->timestamps() ;

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade') ;
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade') ;
            $table->foreign('branch_id')->references('id')->on('shop_branches')->onDelete('cascade') ; 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
