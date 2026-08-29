<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_number', 'status', 'subtotal', 'shipping_cost',
        'total_amount', 'shipping_method', 'recipient_name', 'recipient_phone', 'shipping_address',
        'city', 'postal_code', 'notes', 'confirmed_at', 'shipped_at', 'completed_at',
    ];

    protected $casts = [
        'subtotal'       => 'decimal:2',
        'shipping_cost'  => 'decimal:2',
        'total_amount'   => 'decimal:2',
        'confirmed_at'   => 'datetime',
        'shipped_at'     => 'datetime',
        'completed_at'   => 'datetime',
    ];

    // Daftar status pesanan dan label-nya
    const STATUSES = [
        'pending'    => 'Waiting for Confirmation',
        'confirmed'  => 'Confirmed',
        'processing' => 'Processing',
        'shipped'    => 'Shipped',
        'completed'  => 'Completed',
        'cancelled'  => 'Cancelled',
    ];

    // Warna badge per status
    const STATUS_COLORS = [
        'pending'    => 'warning',
        'confirmed'  => 'info',
        'processing' => 'primary',
        'shipped'    => 'info',
        'completed'  => 'success',
        'cancelled'  => 'danger',
    ];

    // Auto-generate order number
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }
        });
    }

    // =============================================
    // RELATIONSHIPS
    // =============================================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // =============================================
    // SCOPES
    // =============================================

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRecent($query)
    {
        return $query->orderByDesc('created_at');
    }

    // =============================================
    // ACCESSORS
    // =============================================

    /** Label status dalam Bahasa Indonesia */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    /** Warna badge status */
    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'dark';
    }

    /** Format total amount */
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, '.', ',');
    }

    /** Label metode pengiriman */
    public function getShippingMethodLabelAttribute(): string
    {
        return match($this->shipping_method) {
            'gosend' => 'GoSend (Gojek)',
            'manual' => 'Manual Confirmation',
            default  => 'Manual Confirmation',
        };
    }

    /** Apakah pesanan bisa dibayar */
    public function canPay(): bool
    {
        return in_array($this->status, ['pending', 'confirmed'])
            && (!$this->payment || $this->payment->status === 'rejected');
    }
}
