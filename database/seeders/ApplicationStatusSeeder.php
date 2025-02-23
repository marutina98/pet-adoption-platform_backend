<?php

namespace Database\Seeders;

use App\Models\ApplicationStatus;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        ApplicationStatus::create([
            'name' => 'Pending Review',
            'description' => 'The application is awaiting decision.'
        ]);
        
        ApplicationStatus::create([
            'name' => 'Accepted',
            'description' => 'The application has been approved.'
        ]);

        ApplicationStatus::create([
            'name' => 'Refused',
            'description' => 'The application has been denied.'
        ]);

    }
}
