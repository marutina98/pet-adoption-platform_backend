<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AnimalCoatColor;

class AnimalCoatColorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $black = AnimalCoatColor::create([
            'name' => 'Black',
            'hex_color' => '#000000'
        ]);

        $white = AnimalCoatColor::create([
            'name' => 'White',
            'hex_color' => '#FFFFFF'
        ]);

        $gray = AnimalCoatColor::create([
            'name' => 'Gray',
            'hex_color' => '#808080'
        ]);

        $brown = AnimalCoatColor::create([
            'name' => 'Brown',
            'hex_color' => '#8B4513'
        ]);

        $golden = AnimalCoatColor::create([
            'name' => 'Golden',
            'hex_color' => '#FFD700'
        ]);

        $ginger = AnimalCoatColor::create([
            'name' => 'Ginger',
            'hex_color' => '#FFA500'
        ]);

        $other = AnimalCoatColor::create([
            'name' => 'Other',
            'hex_color' => null
        ]);

    }
}
