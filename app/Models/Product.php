<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'material',
        'price', 'stock', 'min_order', 'image', 'images', 'sizes', 'colors',
        'sablon_types', 'sleeve_types', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'price'         => 'decimal:2',
        'is_active'     => 'boolean',
        'images'        => 'array',
        'sizes'         => 'array',
        'colors'        => 'array',
        'sablon_types'  => 'array',
        'sleeve_types'  => 'array',
    ];

    // Auto-generate slug dari name
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // =============================================
    // RELATIONSHIPS
    // =============================================

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    // =============================================
    // SCOPES
    // =============================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // =============================================
    // ACCESSORS
    // =============================================

    /** URL gambar utama produk */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-product.jpg');
    }

    /** Format harga dengan Rupiah */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, '.', ',');
    }

    /** Daftar ukuran sebagai array (default jika null) */
    public function getSizesListAttribute(): array
    {
        return $this->sizes ?? ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
    }

    /** Daftar jenis sablon sebagai array (default jika null) */
    public function getSablonTypesListAttribute(): array
    {
        return $this->sablon_types ?? [
            'No Printing (Plain)', 'Manual Screen Print', 'DTF Print', 'Plastisol Print',
            'Rubber Print', 'Discharge Print', 'Embroidery',
        ];
    }

    /** Daftar warna sebagai array (default jika null) */
    public function getColorsListAttribute(): array
    {
        return $this->colors ?? ['Black', 'White', 'Navy', 'Maroon', 'Grey', 'Army Green', 'Yellow', 'Red'];
    }

    /** Daftar jenis lengan sebagai array (default jika null) */
    public function getSleeveTypesListAttribute(): array
    {
        return $this->sleeve_types ?? ['Short Sleeve', 'Long Sleeve', '3/4 Sleeve'];
    }
}
