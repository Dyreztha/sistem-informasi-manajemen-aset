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
        Schema::table('stock_opnames', function (Blueprint $table) {
            $table->date('opname_date')->nullable()->after('title');
            $table->timestamp('completed_at')->nullable()->after('end_date');
            $table->integer('total_expected')->default(0)->after('status');
            $table->integer('found_count')->default(0)->after('found_assets');
            $table->integer('missing_count')->default(0)->after('not_found_assets');
            $table->foreignId('conducted_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_opnames', function (Blueprint $table) {
            $table->dropForeign(['conducted_by']);
            $table->dropColumn(['opname_date', 'completed_at', 'total_expected', 'found_count', 'missing_count', 'conducted_by']);
        });
    }
};