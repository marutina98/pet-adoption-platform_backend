<?php

namespace Database\Seeders;

use App\Models\Status;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Status::create([
            'name' => 'Available',
            'description' => 'The pet is currently ready and open for adoption.'
        ]);

        Status::create([
            'name' => 'Adopted',
            'description' => 'The pet has been successfully adopted and is no longer available for adoption.'
        ]);

    }
}
