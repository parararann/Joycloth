<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration: Tabel orders (header pesanan)
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();   // Nomor unik pesanan (ORD-YYYYMMDD-XXXX)
            $table->enum('status', [
                'pending',      // Menunggu konfirmasi admin
                'confirmed',    // Dikonfirmasi admin
                'processing',   // Sedang diproses/diproduksi
                'shipped',      // Dikirim
                'completed',    // Selesai
                'cancelled',    // Dibatalkan
            ])->default('pending');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('recipient_name');        // Nama penerima
            $table->string('recipient_phone', 20);  // No HP penerima
            $table->text('shipping_address');         // Alamat pengiriman
            $table->string('city');                   // Kota
            $table->string('postal_code', 10)->nullable();
            $table->text('notes')->nullable();         // Catatan pesanan
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
