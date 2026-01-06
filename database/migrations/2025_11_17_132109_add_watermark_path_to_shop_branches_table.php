<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('shop_branches', function (Blueprint $table) {
        $table->string('watermark_path')->nullable()->after('signature_path');
    });
}

public function down()
{
    Schema::table('shop_branches', function (Blueprint $table) {
        $table->dropColumn('watermark_path');
    });
}

};
