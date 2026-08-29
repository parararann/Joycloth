<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // 1. BUAT AKUN ADMIN
        // ============================================
        $admin = User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@joycloth.id',
            'password' => Hash::make('admin123'),
            'phone'    => '081234567890',
            'address'  => 'Jl. Admin No. 1',
            'role'     => 'admin',
            'email_verified_at' => now(),
        ]);

        // ============================================
        // 2. BUAT AKUN USER DEMO
        // ============================================
        $user = User::create([
            'name'     => 'Demo Customer',
            'email'    => 'user@joycloth.id',
            'password' => Hash::make('user123'),
            'phone'    => '089876543210',
            'address'  => 'Jl. Pelanggan No. 5, Jakarta',
            'role'     => 'user',
            'email_verified_at' => now(),
        ]);

        // ============================================
        // 3. BUAT KATEGORI
        // ============================================
        $categories = [
            ['name' => 'T-Shirt',     'slug' => 't-shirt',    'description' => 'Various types of custom screen printed t-shirts'],
            ['name' => 'Jacket',      'slug' => 'jacket',     'description' => 'Custom jackets and hoodies'],
            ['name' => 'Jersey',      'slug' => 'jersey',     'description' => 'Sports and futsal jerseys'],
            ['name' => 'Totebag',     'slug' => 'totebag',    'description' => 'Canvas and non-woven totebags'],
        ];

        foreach ($categories as $cat) {
            Category::create(array_merge($cat, ['is_active' => true]));
        }

        // ============================================
        // 4. BUAT PRODUK SAMPEL
        // ============================================
        $products = [
            [
                'category_slug' => 't-shirt',
                'name'          => 'Custom Cotton Combed 30s T-Shirt',
                'description'   => 'Custom t-shirt made of soft and comfortable Cotton Combed 30s. Perfect for events, communities, or merchandise.',
                'material'      => 'Cotton Combed 30s, 180 gsm',
                'price'         => 45000,
                'min_order'     => 12,
                'image'         => 'products/FPshLsT4wfZuOOlcdp54WBUXr1Mb3wSHNbTmVZLJ.jpg',
            ],
            [
                'category_slug' => 't-shirt',
                'name'          => 'Premium Oversize T-Shirt',
                'description'   => 'Oversize t-shirt with premium cotton combed 40s material. Modern and trendy design for youth.',
                'material'      => 'Cotton Combed 40s, 160 gsm',
                'price'         => 65000,
                'min_order'     => 12,
                'image'         => 'products/M4M2QopEmxUd9fHH0aGOD9PA8MUJX7LwUQDNXBEY.jpg',
            ],
            [
                'category_slug' => 'jacket',
                'name'          => 'Custom Bomber Jacket',
                'description'   => 'Custom bomber jacket with comfortable taslan and lining. Available in various colors.',
                'material'      => 'Taslan Milky + Lining',
                'price'         => 145000,
                'min_order'     => 6,
                'image'         => 'products/9CABbp9IAeMlcPQzxJE62atcLAj6K0tm1bxiKDPv.webp',
            ],
            [
                'category_slug' => 'jacket',
                'name'          => 'Custom Fleece Hoodie',
                'description'   => 'Thick fleece hoodie for cold weather. Can be customized with designs as desired.',
                'material'      => 'Fleece Cotton, 280 gsm',
                'price'         => 120000,
                'min_order'     => 6,
                'image'         => 'products/VLEDB9YIEHugaREgi1vD1C8TimHdBj93c8olBy5y.webp',
            ],
            [
                'category_slug' => 'jersey',
                'name'          => 'Sublimation Futsal Jersey',
                'description'   => 'Full printing sublimation futsal jersey with lightweight and sweat-absorbent drifit material.',
                'material'      => 'Drifit 100% Polyester',
                'price'         => 75000,
                'min_order'     => 11,
                'image'         => 'products/irW2xvINsnrWYZhq64ux43NOkS58MKoOgNhqRZvG.jpg',
            ],
            [
                'category_slug' => 'totebag',
                'name'          => 'Custom Canvas Totebag',
                'description'   => 'Thick canvas totebag with custom screen printing or embroidery. Perfect for merchandise and giveaways.',
                'material'      => 'Canvas 12oz',
                'price'         => 35000,
                'min_order'     => 24,
                'image'         => 'products/5Wml2wTX0sekyisyBzmUvjw3bo666gU0Y4Sma4Gk.jpg',
            ],
        ];

        foreach ($products as $p) {
            $category = Category::where('slug', $p['category_slug'])->first();
            Product::create([
                'category_id'  => $category->id,
                'name'         => $p['name'],
                'slug'         => Str::slug($p['name']),
                'description'  => $p['description'],
                'material'     => $p['material'],
                'price'        => $p['price'],
                'min_order'    => $p['min_order'],
                'image'        => $p['image'] ?? null,
                'is_active'    => true,
                'sizes'        => ['S', 'M', 'L', 'XL', 'XXL'],
                'colors'       => ['Black', 'White', 'Navy', 'Maroon', 'Grey'],
                'sablon_types' => ['Manual Screen Print', 'DTF Print', 'Plastisol Print', 'Embroidery'],
            ]);
        }

        // ============================================
        // 5. BUAT PESANAN DEMO
        // ============================================
        $product = Product::first();
        $order = Order::create([
            'user_id'          => $user->id,
            'status'           => 'confirmed',
            'subtotal'         => $product->price * 12,
            'shipping_cost'    => 0,
            'total_amount'     => $product->price * 12,
            'recipient_name'   => $user->name,
            'recipient_phone'  => $user->phone,
            'shipping_address' => 'Jl. Contoh No. 10',
            'city'             => 'Jakarta',
            'postal_code'      => '12345',
            'confirmed_at'     => now(),
        ]);

        OrderDetail::create([
            'order_id'     => $order->id,
            'product_id'   => $product->id,
            'product_name' => $product->name,
            'unit_price'   => $product->price,
            'quantity'     => 12,
            'size'         => 'M',
            'sablon_type'  => 'DTF Print',
            'subtotal'     => $product->price * 12,
        ]);

        $this->command->info('✅ Seeder selesai!');
        $this->command->info('👤 Admin: admin@joycloth.id / admin123');
        $this->command->info('👤 User : user@joycloth.id / user123');
    }
}
