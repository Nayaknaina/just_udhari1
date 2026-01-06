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
        // Check if the shop_branches table exists
        if (Schema::hasTable('shop_branches')) {
            Schema::table('shop_branches', function (Blueprint $table) {
                // Check if the column doesn't exist before adding
                if (!Schema::hasColumn('shop_branches', 'bill_columns')) {
                    $table->json('bill_columns')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('shop_branches')) {
            Schema::table('shop_branches', function (Blueprint $table) {
                if (Schema::hasColumn('shop_branches', 'bill_columns')) {
                    $table->dropColumn('bill_columns');
                }
            });
        }
    }
};