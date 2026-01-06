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
        Schema::create('udhar_account', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('custo_id');
            $table->string('custo_name',100);
            $table->string('custo_num',100);
            $table->string('custo_mobile',15);
            $table->double('custo_amount')->nullable();
            $table->enum('custo_amount_status',[1,0])->nullable()->comment('1=Positive balance(Vedor will Pay),0=Negative Balance(Customer Will Pay)');
            $table->double('custo_gold')->nullable();
            $table->enum('custo_gold_status',[1,0])->nullable()->comment('1=Positive(Vedor will Pay),0=Negative(Customer Will Pay)');
            $table->double('custo_silver')->nullable();
            $table->enum('custo_silver_status',[1,0])->nullable()->comment('1=Positive(Vedor will Pay),0=Negative (Customer Will Pay)');
			$table->string('udhar_note')->nullable();
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
        Schema::dropIfExists('udhar_account');
    }
};
