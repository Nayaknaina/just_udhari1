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
        Schema::create('text_messages', function (Blueprint $table) {
            $table->id();
            $table->string('section',20)->comment('Which module of System tryied to send message');
            $table->string('sub_section',200)->comment('Which Operation of module tryied to send message');
            $table->string('msg_header')->comment("System's Message Identifier !");
            $table->enum('msg_route',['d','q'])->default('d')->comment("Which route use to send message");
			$table->text('variable_string');
            $table->unsignedBigInteger('custo_id')->comment("Id of Customer Table")->nullable();
            $table->enum('custo_type',['c','s'])->default('c')->comment("Type of Customer Supplier or Customer")->nullable();
            $table->string('custo_name')->comment("Customer Name !")->nullable();
            $table->string('custo_contact',15)->comment("Customer Contact Number !");
            $table->text('msg_content')->comment("The Actual message Tryied to Send !");
            $table->enum('status',[1,0])->comment('1=Sent,0=Failed');
            $table->text('remark');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->unsignedBigInteger('entry_by')->nullable();
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
        Schema::dropIfExists('text_messages');
    }
};
