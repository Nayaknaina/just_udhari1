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
        Schema::create('scheme_account', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('custo_id');
            $table->unsignedBiginteger('shop_id');
            $table->unsignedBiginteger('branch_id');
            $table->double('collect_balance');
            $table->double('remains_balance');
            $table->unsignedBiginteger('entry_by');
            $table->unsignedBiginteger('role_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheme_account');
    }
};
