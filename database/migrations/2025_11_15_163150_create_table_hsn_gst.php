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
        Schema::create('table_hsn_gst', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->enum('type',['Jewellery','Bullion','Loose','Precious','Semi Precious','common']);
            $table->string('hsn')->nullable();
            $table->string('gst');
            $table->enum('active',[1,0])->default(1)->comment('1=In Use(not Delete),0=not in use(Deleted)');
            $table->enum('status',['on','off'])->default('off')->comment('on=Applicable,off=Not Applicable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_hsn_gst');
    }
};
