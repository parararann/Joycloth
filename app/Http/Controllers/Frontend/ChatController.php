<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

/**
 * ChatController - Live chat antara user dan admin
 * Menggunakan polling HTTP (tanpa WebSocket)
 */
class ChatController extends Controller
{
    /**
     * Tampilkan halaman live chat user
     */
    public function index()
    {
        $userId   = auth()->id();
        $messages = ChatMessage::forUser($userId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Tandai semua pesan admin sebagai sudah dibaca
        ChatMessage::forUser($userId)
            ->where('sender_type', 'admin')
            ->unread()
            ->update(['is_read' => true, 'read_at' => now()]);

        // Cek apakah ada admin yang sedang online
        $isAdminOnline = \App\Models\User::where('role', 'admin')
            ->where('last_seen_at', '>=', now()->subMinutes(5))
            ->exists();

        return view('chat.index', compact('messages', 'isAdminOnline'));
    }

    /**
     * Kirim pesan dari user
     */
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = ChatMessage::create([
            'user_id'     => auth()->id(),
            'message'     => $request->message,
            'sender_type' => 'user',
            'sender_id'   => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id'          => $message->id,
                'message'     => $message->message,
                'sender_type' => 'user',
                'time'        => $message->time,
            ],
        ]);
    }

    /**
     * Polling: ambil pesan baru sejak last_id
     */
    public function poll(Request $request)
    {
        $lastId   = $request->get('last_id', 0);
        $userId   = auth()->id();

        $messages = ChatMessage::forUser($userId)
            ->where('id', '>', $lastId)
            ->where('sender_type', 'admin') // hanya ambil balasan dari admin
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($m) => [
                'id'          => $m->id,
                'message'     => $m->message,
                'sender_type' => $m->sender_type,
                'time'        => $m->time,
            ]);

        // Tandai sudah dibaca
        if ($messages->isNotEmpty()) {
            ChatMessage::forUser($userId)
                ->where('sender_type', 'admin')
                ->unread()
                ->update(['is_read' => true, 'read_at' => now()]);
        }

        return response()->json(['messages' => $messages]);
    }
}
