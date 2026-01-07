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
        Schema::table('contact_enquiries', function (Blueprint $table) {
             $table->enum('agree', ['yes', 'no'])->default('no')->after('message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_enquiries', function (Blueprint $table) {
             $table->dropColumn('agree');
        });
    }
};

