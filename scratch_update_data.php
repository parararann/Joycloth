<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Design;
use Illuminate\Support\Str;

// Load Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Updating Categories...\n";
$catMapping = [
    'kaos' => ['name' => 'T-Shirt', 'slug' => 't-shirt', 'description' => 'Various types of custom screen printed t-shirts'],
    'jaket' => ['name' => 'Jacket', 'slug' => 'jacket', 'description' => 'Custom jackets and hoodies'],
    'jersey' => ['name' => 'Jersey', 'slug' => 'jersey', 'description' => 'Sports and futsal jerseys'],
    'totebag' => ['name' => 'Totebag', 'slug' => 'totebag', 'description' => 'Canvas and non-woven totebags'],
];

foreach ($catMapping as $oldSlug => $new) {
    Category::where('slug', $oldSlug)->update($new);
}

echo "Updating Products...\n";
$prodMapping = [
    'Kaos Cotton Combed 30s Custom' => [
        'name' => 'Custom Cotton Combed 30s T-Shirt',
        'description' => 'Custom t-shirt made of soft and comfortable Cotton Combed 30s. Perfect for events, communities, or merchandise.',
        'material' => 'Cotton Combed 30s, 180 gsm'
    ],
    'Kaos Oversize Premium' => [
        'name' => 'Premium Oversize T-Shirt',
        'description' => 'Oversize t-shirt with premium cotton combed 40s material. Modern and trendy design for youth.',
        'material' => 'Cotton Combed 40s, 160 gsm'
    ],
    'Jaket Bomber Custom' => [
        'name' => 'Custom Bomber Jacket',
        'description' => 'Custom bomber jacket with comfortable taslan and lining. Available in various colors.',
        'material' => 'Taslan Milky + Lining'
    ],
    'Hoodie Fleece Custom' => [
        'name' => 'Custom Fleece Hoodie',
        'description' => 'Thick fleece hoodie for cold weather. Can be customized with designs as desired.',
        'material' => 'Fleece Cotton, 280 gsm'
    ],
    'Jersey Futsal Sublimation' => [
        'name' => 'Sublimation Futsal Jersey',
        'description' => 'Full printing sublimation futsal jersey with lightweight and sweat-absorbent drifit material.',
        'material' => 'Drifit 100% Polyester'
    ],
    'Totebag Kanvas Custom' => [
        'name' => 'Custom Canvas Totebag',
        'description' => 'Thick canvas totebag with custom screen printing or embroidery. Perfect for merchandise and giveaways.',
        'material' => 'Canvas 12oz'
    ],
];

foreach ($prodMapping as $oldName => $new) {
    $p = Product::where('name', $oldName)->first();
    if ($p) {
        $p->update([
            'name' => $new['name'],
            'slug' => Str::slug($new['name']),
            'description' => $new['description'],
            'material' => $new['material'],
            'colors' => ['Black', 'White', 'Navy', 'Maroon', 'Grey'],
            'sablon_types' => ['Manual Screen Print', 'DTF Print', 'Plastisol Print', 'Embroidery'],
        ]);
    }
}

echo "Updating Designs...\n";
$designMapping = [
    'Desain tengkorak streetwear bold dengan api neon hijau.' => 'Bold streetwear skull design with green neon flames.',
    'Tipografi distorsi warna hitam dan pink gaya Neobrutalism.' => 'Black and pink distorted typography in Neobrutalism style.',
    'Grafis kepala robot retro-futuristik dengan efek glitch cyan/magenta.' => 'Retro-futuristic robot head graphic with cyan/magenta glitch effect.',
    'Ilustrasi macan kumbang menerkam pagar kawat dengan warna flat tebal.' => 'Panther illustration pouncing on a wire fence with thick flat colors.',
    'Bentuk geometris abstrak dengan tipografi agresif kuning dan hitam.' => 'Abstract geometric shapes with aggressive yellow and black typography.',
    'Desain smiley meleleh warna psikedelik yang trendi.' => 'Trendy psychedelic melting smiley design.',
];

foreach ($designMapping as $oldDesc => $newDesc) {
    Design::where('description', $oldDesc)->update(['description' => $newDesc]);
}

echo "Done!\n";
