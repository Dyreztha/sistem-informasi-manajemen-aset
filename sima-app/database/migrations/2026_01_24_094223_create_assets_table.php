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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('AST-2026-001');
            $table->string('name');
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->onDelete('set null');
            
            // Detail Aset
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->text('description')->nullable();
            
            // Keuangan
            $table->decimal('purchase_price', 15, 2)->default(0);
            $table->date('purchase_date')->nullable();
            $table->decimal('current_value', 15, 2)->default(0);
            $table->decimal('depreciation_value', 15, 2)->default(0);
            
            // Status & Kondisi
            $table->enum('status', ['tersedia', 'digunakan', 'maintenance', 'disposal'])->default('tersedia');
            $table->enum('condition', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->default('baik');
            
            // Assignment
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->date('assigned_date')->nullable();
            
            // QR Code
            $table->string('qr_code')->nullable()->unique();
            
            // Warranty
            $table->date('warranty_end_date')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
