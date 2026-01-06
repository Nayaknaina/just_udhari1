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
        Schema::create('girvi_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receipt');
            $table->unsignedBigInteger('girvi_custo_id');
            $table->unsignedBigInteger('item_count');
            $table->double('girvi_value');
            $table->double('girvy_grant');
            $table->decimal('girvi_issue_perc');
            $table->double('interest_rate');
            $table->enum('interest_type',['SI','CI'])->default('SI');
            $table->double('principle');
            $table->double('interest');
            $table->double('old_amount')->default(0);
            $table->date('entry_date');
            $table->tinyInteger('girvy_period',2);
            $table->date('girvy_issue_date');
            $table->date('girvy_return_date');
            $table->text('remark')->nullable();
            $table->enum('flip',[1,0])->default(0)->comment('1=Principle Change so the interest changed too !');
            $table->enum('status',[1,0]);
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
        Schema::dropIfExists('girvi_batches');
    }
};
