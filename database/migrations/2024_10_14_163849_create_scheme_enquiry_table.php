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
        Schema::create('scheme_enquiry', function (Blueprint $table) {
            $table->id();
            $table->unsignedbiginteger('custo_id');
            $table->unsignedbiginteger('shop_id');
            $table->unsignedbiginteger('branch_id');
            $table->unsignedbiginteger('scheme_id');
            $table->string('name',100)->nullable();
            $table->string('mail',100)->nullable();
            $table->string('fone',100)->nullable();
            $table->string('message',100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheme_enquiry');
    }
};
