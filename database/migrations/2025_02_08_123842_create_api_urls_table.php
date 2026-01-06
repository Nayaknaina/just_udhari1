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
        Schema::create('api_urls', function (Blueprint $table) {
            $table->id();
            $table->string('for',50);
            $table->text('url');
			$table->text('api_key');
			$table->enum('route',['q','dlt'])->comment('q=Quick message,dlt=SLT Route');
			$table->string('sender_id',20);
            $table->enum('status',[1,0])->default(0);
            $table->unsignedbiginteger('shop_id');
            $table->unsignedbiginteger('branch_id');
            $table->unsignedbiginteger('entry_by');
            $table->unsignedbiginteger('role_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_urls');
    }
};
