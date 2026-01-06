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
        Schema::create('girvi_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('girvi_custo_id');
            $table->unsignedBigInteger('girvi_batch_id');
            $table->string('category');
            $table->text('detail');
            $table->text('property')->nullable();
            $table->double('rate');
            $table->double('value');
            $table->decimal('issue_diff_perc');
            $table->double('issue');
            $table->tinyInteger('interest_rate',2);
            $table->enum('interest_type',['si','ci'])->default('si');
            $table->double('interest');
            $table->double('principal');
            $table->date('entry_date');
            $table->enum('action',['A','E','U','D','X'])->default('A');
            $table->unsignedBigInteger('action_on')->nullable()->default(0);
            $table->text('remark')->nullable();
            $table->enum('flip',[1,0])->default(0)->comment('1=Principle Change so the interest changed too !');
            $table->enum('status',[1,0])->default(1);
            $table->unsignedBigInteger('entry_by')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('branch_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('girvi_items');
    }
};
