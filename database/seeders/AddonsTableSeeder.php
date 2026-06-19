<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Addon;

class AddonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('addons')->truncate();

        // Uncomment and run this seeder if you need dummy data for testing.
        // As per the request, this is left empty to clear the table.

        /*
        $addons = [
            [
                'name' => 'AI Question Generator',
                'slug' => 'ai-question-generator',
                'version' => '1.5.0',
                'description' => 'Generate quiz questions automatically using AI.',
                'image' => 'images/addons/ai-generator-icon.png',
                'is_active' => true,
                'route_name' => 'admin.extra.addons.index',
                'icon' => 'fa-solid fa-robot',
                'menu_location' => 'exam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($addons as $addon) {
            Addon::create($addon);
        }
        */
    }
}