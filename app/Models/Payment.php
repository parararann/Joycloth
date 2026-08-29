<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'amount', 'bank_name', 'bank_account', 'account_holder',
        'sender_bank', 'sender_name', 'payment_proof', 'status',
        'rejection_reason', 'paid_at', 'verified_at', 'verified_by',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'paid_at'     => 'datetime',
        'verified_at' => 'datetime',
    ];

    const STATUSES = [
        'pending'  => 'Pending Payment',
        'uploaded' => 'Proof Uploaded',
        'verified' => 'Verified',
        'rejected' => 'Rejected',
    ];

    const STATUS_COLORS = [
        'pending'  => 'warning',
        'uploaded' => 'info',
        'verified' => 'success',
        'rejected' => 'danger',
    ];

    // =============================================
    // RELATIONSHIPS
    // =============================================

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // =============================================
    // ACCESSORS
    // =============================================

    /** URL bukti transfer */
    public function getProofUrlAttribute(): ?string
    {
        if ($this->payment_proof) {
            return asset('storage/' . $this->payment_proof);
        }
        return null;
    }

    /** Label status */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    /** Warna badge */
    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'dark';
    }

    /** Format amount */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, '.', ',');
    }
}
