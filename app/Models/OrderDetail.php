<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'product_id', 'product_name', 'unit_price',
        'quantity', 'size', 'color', 'sleeve_type', 'sablon_type', 'custom_design', 'design_id', 'notes', 'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal'   => 'decimal:2',
    ];

    // =============================================
    // RELATIONSHIPS
    // =============================================

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function design()
    {
        return $this->belongsTo(Design::class);
    }

    // =============================================
    // ACCESSORS
    // =============================================

    /** URL file desain yang diupload customer */
    public function getDesignUrlAttribute(): ?string
    {
        if ($this->custom_design) {
            return asset('storage/' . $this->custom_design);
        }
        return null;
    }

    /** Format subtotal */
    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    /** Format unit price */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->unit_price, 0, ',', '.');
    }
}
