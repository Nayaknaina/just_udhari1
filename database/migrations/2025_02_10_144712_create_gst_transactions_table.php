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
        Schema::create('gst_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('source',['s','p','jb'])->comment('s=sell bill,p=purchase bill,jb=just bill')->nullable();
            $table->unsignedbiginteger('source_id')->comment("s=sell bill id,p=purchase bill id,jb=just bill id");;
            $table->string('source_no')->nullable()->comment("s=sell bill num,p=purchase bill num,jb=just bill num");
            $table->enum('person_type',['c','s'])->comment('C=Customer,S=Sipplier')->nullable();
           $table->unsignedbiginteger('person_id')->nullable()->comment('customer/Supplier record id whome you receive/grant the gst');
            $table->string('person_name')->nullable()->comment('customer/Supplier name');
            $table->string('person_contact')->nullable()->comment('customer/Supplier name');
            $table->enum('gst_type',['in','ex'])->default('ex')->comment('In = Inclusive,Ex= Exclusive');
            $table->double('gst');
            $table->double('igst')->default(0);
            $table->double('cgst')->default(0);
            $table->double('sgst')->default(0);
            $table->double('gst_amnt');
            $table->double('base_amnt');
            $table->enum('amnt_status',[1,0,'N'])->comment("1=Gst Received(plus),0=Gst Grand(minus),N=No effenct(neight plus nor minus)");
            $table->enum('action_taken',['A','E','U','D'])->default('A')->comment("Record->A=Added,E=Edited,U=Updated,D=Deleted");
            $table->unsignedbiginteger('action_on')->nullable()->comment('On E/U/D ,the id of the has been modified');
            $table->unsignedbiginteger('entry_by')->nullable()->comment('Id of the user performin the action');
            $table->unsignedbiginteger('role_id')->nullable()->comment("Id of the user's role  performin the action");
            $table->unsignedbiginteger('shop_id');
            $table->unsignedbiginteger('branch_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gst_transactions');
    }
};
