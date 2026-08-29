<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DesignSeeder extends Seeder
{
    public function run(): void
    {
        $designsDir = storage_path('app/public/designs');
        if (!File::exists($designsDir)) {
            File::makeDirectory($designsDir, 0755, true);
        }

        $sourceDir = 'C:\Users\ASUS\.gemini\antigravity\brain\9e23e278-ba8d-443a-aec0-719c8782442f';
        
        // Temukan semua file gambar desain di folder gemini
        $files = File::glob($sourceDir . '\design_*.png');
        
        $designs = [
            'design_skull' => ['title' => 'Neon Flame Skull', 'description' => 'Bold streetwear skull design with green neon flames.'],
            'design_norules' => ['title' => 'No Rules Typography', 'description' => 'Black and pink distorted typography in Neobrutalism style.'],
            'design_robot' => ['title' => 'Glitch Robot', 'description' => 'Retro-futuristic robot head graphic with cyan/magenta glitch effect.'],
            'design_panther' => ['title' => 'Edgy Panther', 'description' => 'Panther illustration pouncing on a wire fence with thick flat colors.'],
            'design_chaos' => ['title' => 'Abstract Chaos', 'description' => 'Abstract geometric shapes with aggressive yellow and black typography.'],
            'design_smiley' => ['title' => 'Melting Smiley', 'description' => 'Trendy psychedelic melting smiley design.'],
        ];

        foreach ($files as $file) {
            $filename = basename($file);
            $prefix = explode('_177', $filename)[0]; // get the prefix like design_skull
            
            if (isset($designs[$prefix])) {
                $newFilename = time() . '_' . $filename;
                File::copy($file, $designsDir . '/' . $newFilename);

                DB::table('designs')->insert([
                    'title' => $designs[$prefix]['title'],
                    'image' => $newFilename,
                    'description' => $designs[$prefix]['description'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
