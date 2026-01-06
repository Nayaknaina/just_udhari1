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
        Schema::create('text_sms_tamplate', function (Blueprint $table) {
            $table->id();
			$table->string('msg_id',20);
            $table->string('head');
            $table->string('body');
            $table->string('variables');
            $table->text('detail')->nullable();
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
        Schema::dropIfExists('text_sms_tamplate');
    }
};
