<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'address', 'avatar', 'role', 'last_seen_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_seen_at' => 'datetime',
    ];

    // =============================================
    // HELPER METHODS
    // =============================================

    /** Cek apakah user adalah admin */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /** Cek apakah user adalah customer */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /** Cek apakah user sedang online (aktif dalam 5 menit terakhir) */
    public function isOnline(): bool
    {
        if (!$this->last_seen_at) return false;
        return $this->last_seen_at->gt(now()->subMinutes(5));
    }

    // =============================================
    // RELATIONSHIPS
    // =============================================

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    // Pesan chat yang dikirim user ini
    public function sentMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    /** URL Avatar */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && \Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }
        $initial = strtoupper(substr($this->name, 0, 1));
        return 'https://ui-avatars.com/api/?name=' . urlencode($initial) . '&background=primary&color=fff&length=1';
    }
}
