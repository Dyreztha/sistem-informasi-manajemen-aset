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
        // Add missing fields to maintenances table
        Schema::table('maintenances', function (Blueprint $table) {
            if (!Schema::hasColumn('maintenances', 'title')) {
                $table->string('title')->nullable()->after('type');
            }
            if (!Schema::hasColumn('maintenances', 'estimated_cost')) {
                $table->decimal('estimated_cost', 15, 2)->default(0)->after('cost');
            }
            if (!Schema::hasColumn('maintenances', 'actual_cost')) {
                $table->decimal('actual_cost', 15, 2)->default(0)->after('estimated_cost');
            }
            if (!Schema::hasColumn('maintenances', 'start_date')) {
                $table->date('start_date')->nullable()->after('scheduled_date');
            }
            if (!Schema::hasColumn('maintenances', 'technician_notes')) {
                $table->text('technician_notes')->nullable()->after('technician_name');
            }
        });
        
        // Add missing fields to asset_movements table
        Schema::table('asset_movements', function (Blueprint $table) {
            if (!Schema::hasColumn('asset_movements', 'expected_return_date')) {
                $table->date('expected_return_date')->nullable()->after('movement_date');
            }
            if (!Schema::hasColumn('asset_movements', 'actual_return_date')) {
                $table->date('actual_return_date')->nullable()->after('expected_return_date');
            }
        });
        
        // Add useful_life_years to categories if not exists
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'useful_life_years')) {
                $table->integer('useful_life_years')->default(5)->after('depreciation_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $columns = ['title', 'estimated_cost', 'actual_cost', 'start_date', 'technician_notes'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('maintenances', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
        
        Schema::table('asset_movements', function (Blueprint $table) {
            $columns = ['expected_return_date', 'actual_return_date'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('asset_movements', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
        
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'useful_life_years')) {
                $table->dropColumn('useful_life_years');
            }
        });
    }
};
