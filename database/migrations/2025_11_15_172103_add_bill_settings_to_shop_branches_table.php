<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillSettingsToShopBranchesTable extends Migration
{
    public function up()
    {
        Schema::table('shop_branches', function (Blueprint $table) {
            $table->string('logo_path')->nullable();
            $table->string('signature_path')->nullable();
            $table->string('bill_format')->default('format1');
            $table->text('invoice_terms')->nullable();
        });
    }

    public function down()
    {
        Schema::table('shop_branches', function (Blueprint $table) {
            $table->dropColumn(['logo_path', 'signature_path', 'bill_format', 'invoice_terms']);
        });
    }
}