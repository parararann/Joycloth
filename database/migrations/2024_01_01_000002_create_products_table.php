<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration: Tabel produk sablon & konveksi
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');               // Nama produk
            $table->string('slug')->unique();     // URL-friendly name
            $table->text('description');           // Deskripsi produk
            $table->text('material')->nullable();  // Bahan/material produk
            $table->decimal('price', 12, 2);       // Harga dasar (per pcs)
            $table->integer('min_order')->default(12);  // Minimum order
            $table->string('image')->nullable();   // Gambar utama produk
            $table->json('images')->nullable();    // Multiple gambar (JSON array)
            $table->json('sizes')->nullable();     // Ukuran tersedia (S,M,L,XL,XXL)
            $table->json('colors')->nullable();    // Warna tersedia
            $table->json('sablon_types')->nullable(); // Jenis sablon tersedia
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0); // Urutan tampilan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
