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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('custo_unique');
            $table->unsignedBiginteger('custo_num');
            $table->unsignedBiginteger('shop_id');
            $table->unsignedBiginteger('branch_id');
            $table->string('custo_user')->nullable();
            $table->string('password')->nullable();
            $table->string('custo_img')->nullable();
            $table->string('custo_full_name');
            $table->string('custo_first_name')->nullable();
            $table->string('custo_last_name')->nullable();
            $table->string('custo_mail')->nullable();
            $table->string('custo_fone');
            $table->enum('fone_varify',[1,0])->default(0)->comment('1=Varified,0=Not Varified ');
            $table->insignedBiginteger('state_id')->nullable();
            $table->string('state_name')->nullable();
            $table->insignedBiginteger('dist_id')->nullable();
            $table->string('dist_name')->nullable();
            $table->insignedBiginteger('teh_id')->nullable();
            $table->string('teh_name')->nullable();
            $table->insignedBiginteger('area_id')->nullable();
            $table->string('area_name')->nullable();
            $table->insignedBiginteger('pin_id')->nullable();
            $table->string('pin_code',10)->nullable();
            $table->string('custo_address');
            $table->text('custo_addr_one')->nullable();
            $table->text('custo_addr_two')->nullable();
            $table->double('custo_balance')->default(0);
            $table->enum('custo_status',[1,0])->default(1)->comment("1=Active Profile,0=Deactive Profile");
            $table->enum('cust_type',[1,2,3,4])->default('1')->comment("'1 Self','2 by Vendor', 3=Through Bill,4=Through Udhar Section");
            $table->text("custo_status_msg")->nullable();
            $table->text("custo_remark")->nullable();
            $table->unsignedBiginteger("active_operator_id")->nullable()->comment("This will store the id of active operate who perform action of profile like Activate/Deactiveate the profile ");
            $table->unsignedBiginteger("active_operator_role")->nullable()->comment("This will store the role of active operate who perform action on profile like Activate/Deactiveate the profile ");
            $table->string("remember_token")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
