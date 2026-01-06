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
        Schema::create('gst_infos', function (Blueprint $table) {
            $table->id();
            $table->string('hsf');
            $table->text('desc')->nullable();
            $table->float('gst');
            $table->enum('status',[1,0])->default(0)->comment('1=Active,0=Deactive');
            $table->unsignedBiginteger('shop_id');
            $table->unsignedBiginteger('branch_id');
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
        Schema::dropIfExists('gst_infos');
    }
};
