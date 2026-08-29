<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration: Tabel detail item dalam satu pesanan
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name');         // Snapshot nama produk
            $table->decimal('unit_price', 12, 2);   // Harga per pcs saat order
            $table->integer('quantity');             // Jumlah
            $table->string('size')->nullable();      // Ukuran (S/M/L/XL/XXL)
            $table->string('color')->nullable();     // Warna (Merah/Hitam/Putih/dll)
            $table->string('sablon_type')->nullable(); // Jenis sablon
            $table->string('custom_design')->nullable(); // Path file desain upload
            $table->text('notes')->nullable();        // Catatan khusus item
            $table->decimal('subtotal', 12, 2);      // unit_price x quantity
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
