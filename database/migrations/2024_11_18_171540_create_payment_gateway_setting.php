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
        Schema::create('payment_gateway_setting', function (Blueprint $table) {
            $table->id();
            $table->unsignedbiginteger('shop_id');
            $table->unsignedbiginteger('branch_id');
            $table->unsignedbiginteger('gateway_id');
            $table->string('gateway_name');
            $table->string('custom_name');
            $table->text('paramenter');
            $table->enum('state',['prod','test'])->comment('prod=Live Payment,test=Test Payment')->default('test');
            $table->enum('status',[1,0])->comment('1=Launch At Ecomm Site,0=Not launched')->default(0);
            $table->text('success_url')->nullable();
            $table->text('failures_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateway_setting');
    }
};
