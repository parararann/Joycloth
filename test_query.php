<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Design;

$designs = Design::all();
foreach ($designs as $d) {
    echo "ID: " . $d->id . " | Title: " . $d->title . " | Active: " . ($d->is_active ? 'Yes' : 'No') . " | Sort: " . $d->sort_order . " | Image: " . $d->image . "\n";
}
