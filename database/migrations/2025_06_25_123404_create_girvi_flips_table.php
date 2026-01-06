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
        Schema::create('girvi_flips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('item_id');
            $table->double('now_value')->nullable();
            $table->double('pre_p');
            $table->double('pre_i');
            $table->double('txn_id');
            $table->double('post_p');
            $table->double('post_i');
            $table->enum('op_on',['B','I'])->default('I')->comment('B= Operation on Batch,E=Operation on Item');
            $table->enum('status',[1,0])->default(1)->comment('1=Active Post Principal & Interest,0=Deactive Post Principal & Interest !');
            $table->text('remark');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('girvi_flips');
    }
};
