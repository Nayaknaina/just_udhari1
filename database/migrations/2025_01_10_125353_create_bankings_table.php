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
        Schema::create('bankings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('branch');
            $table->string('account');
            $table->string('ifsc');
            $table->enum('for',['jb','b','bjb','sys','all'])->default('all')->comment('jb=just bill,b=stock bill,bjb=Stock & just bill,sys=internal system,all=for all');
            $table->enum('status',[1,0])->default(1)->comment('1=Active,0=Deactive');
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
        Schema::dropIfExists('bankings');
    }
};
