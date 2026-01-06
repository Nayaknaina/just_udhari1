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
        Schema::create('girvi_txns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('girvi_custo_id');
            $table->unsignedBigInteger('girvi_batch_id');
            $table->unsignedBigInteger('girvi_item_id')->nullable();
            $table->enum('pay_mode',['on','off'])->default('off');
            $table->char('pay_medium')->nullable();
            $table->double('pay_principal')->default(0);
            $table->double('pay_interest')->default(0);
            $table->date('pay_date');
            $table->enum('operation',['GG','GI','GX','GE'])->comment('GG=Girvi Grant,GI=Girvi Interest,GX=Girvi Intem Exchage,GE=Girvi Extra Monet Taken');
            $table->enum('action',['A','E','U','D'])->default('A')->comment("A=Record Addedd,E=Editred,U=Updated,D=Delete");
            $table->unsignedBigInteger('action_on')->nullable()->default(0)->comment("Id of self Record on which Action Performed !");
            $table->enum('amnt_holder',['S','B'])->default('S')->comment('S=In Shop,B=In Bank');
            $table->enum('txn_status',[1,0])->comment('1=Amount In,0=Amount Out');
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
        Schema::dropIfExists('girvi_txns');
    }
};
