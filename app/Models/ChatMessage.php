<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'message', 'sender_type', 'sender_id', 'is_read', 'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // =============================================
    // RELATIONSHIPS
    // =============================================

    /** User yang terlibat dalam percakapan */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Pengirim pesan (bisa user atau admin) */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // =============================================
    // SCOPES
    // =============================================

    /** Pesan yang belum dibaca */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /** Pesan untuk user tertentu */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // =============================================
    // ACCESSORS
    // =============================================

    /** Waktu relatif pesan */
    public function getTimeAttribute(): string
    {
        return $this->created_at->format('H:i');
    }

    /** Apakah pesan dari admin */
    public function isFromAdmin(): bool
    {
        return $this->sender_type === 'admin';
    }
}
