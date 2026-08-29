<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration: Tabel pembayaran (transfer bank + upload bukti)
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);                // Jumlah yang dibayar
            $table->string('bank_name');                     // Nama bank tujuan
            $table->string('bank_account');                  // Nomor rekening tujuan
            $table->string('account_holder');                // Nama pemilik rekening
            $table->string('sender_bank')->nullable();       // Bank pengirim
            $table->string('sender_name')->nullable();       // Nama pengirim
            $table->string('payment_proof')->nullable();     // Path file bukti transfer
            $table->enum('status', [
                'pending',   // Menunggu upload bukti
                'uploaded',  // Bukti sudah diupload, menunggu verifikasi
                'verified',  // Sudah diverifikasi admin
                'rejected',  // Ditolak admin
            ])->default('pending');
            $table->text('rejection_reason')->nullable();    // Alasan penolakan
            $table->timestamp('paid_at')->nullable();        // Waktu pembayaran
            $table->timestamp('verified_at')->nullable();    // Waktu verifikasi
            $table->foreignId('verified_by')->nullable()->constrained('users'); // Admin yang memverifikasi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
