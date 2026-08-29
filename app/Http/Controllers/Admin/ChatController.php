<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Admin ChatController - Panel chat admin untuk membalas pesan user
 */
class ChatController extends Controller
{
    /**
     * Daftar percakapan (semua user yang pernah chat)
     */
    public function index()
    {
        // Ambil user unik yang pernah kirim pesan, dengan pesan terakhir
        $conversations = ChatMessage::select('user_id')
            ->with(['user'])
            ->groupBy('user_id')
            ->orderByDesc(\DB::raw('MAX(created_at)'))
            ->get()
            ->map(function ($chat) {
                $lastMessage = ChatMessage::forUser($chat->user_id)
                    ->orderByDesc('created_at')
                    ->first();
                $unreadCount = ChatMessage::forUser($chat->user_id)
                    ->where('sender_type', 'user')
                    ->unread()
                    ->count();

                return [
                    'user'        => $chat->user,
                    'lastMessage' => $lastMessage,
                    'unreadCount' => $unreadCount,
                ];
            });

        // Total pesan user yang belum dibaca
        $totalUnread = ChatMessage::where('sender_type', 'user')->unread()->count();

        return view('admin.chat.index', compact('conversations', 'totalUnread'));
    }

    /**
     * Buka percakapan dengan user tertentu
     */
    public function conversation(int $userId)
    {
        $chatUser = User::findOrFail($userId);

        $messages = ChatMessage::forUser($userId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Tandai semua pesan user sebagai sudah dibaca
        ChatMessage::forUser($userId)
            ->where('sender_type', 'user')
            ->unread()
            ->update(['is_read' => true, 'read_at' => now()]);

        return view('admin.chat.conversation', compact('chatUser', 'messages'));
    }

    /**
     * Admin membalas pesan user
     */
    public function reply(Request $request, int $userId)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $message = ChatMessage::create([
            'user_id'     => $userId,
            'message'     => $request->message,
            'sender_type' => 'admin',
            'sender_id'   => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id'          => $message->id,
                'message'     => $message->message,
                'sender_type' => 'admin',
                'time'        => $message->time,
                'sender_name' => auth()->user()->name,
            ],
        ]);
    }

    /**
     * Polling pesan baru dari user (untuk admin)
     */
    public function poll(Request $request, int $userId)
    {
        $lastId   = $request->get('last_id', 0);
        $messages = ChatMessage::forUser($userId)
            ->where('id', '>', $lastId)
            ->where('sender_type', 'user')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($m) => [
                'id'          => $m->id,
                'message'     => $m->message,
                'sender_type' => $m->sender_type,
                'time'        => $m->time,
            ]);

        if ($messages->isNotEmpty()) {
            ChatMessage::forUser($userId)
                ->where('sender_type', 'user')
                ->unread()
                ->update(['is_read' => true, 'read_at' => now()]);
        }

        return response()->json(['messages' => $messages]);
    }
    /**
     * Ambil total pesan yang belum dibaca dari semua user
     */
    public function unreadCount()
    {
        $count = ChatMessage::where('sender_type', 'user')->unread()->count();
        return response()->json(['count' => $count]);
    }
}
