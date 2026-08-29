<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration: Tabel pesan live chat antara user dan admin
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User yang terlibat chat
            $table->text('message');                          // Isi pesan
            $table->enum('sender_type', ['user', 'admin']);  // Pengirim pesan
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade'); // ID pengirim
            $table->boolean('is_read')->default(false);       // Status dibaca
            $table->timestamp('read_at')->nullable();          // Waktu dibaca
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
