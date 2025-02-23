<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PetAgency;
use App\Models\PetAdopter;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Add Image Provider

        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Mmo\Faker\FakeimgProvider($faker));

        // Create 10 PetAdopters and 10 PetAgencies

        for ($i = 0; $i < 10; $i++) {
            
            // PetAdopter

            $adopter = User::create([
                'email' => $faker->email(),
                'password' => Hash::make('password'),
                'is_pet_adopter' => true,
            ]);

            // PetAdopter Picture
            
            /*

            $adopterPictureFolder = 'public/storage/adopters';

            if (!File::exists($adopterPictureFolder)) {
                File::makeDirectory($adopterPictureFolder, 0755, true);
            }

            $adopterPicture = $faker->fakeImg($dir = $adopterPictureFolder, $width = 1000, $height = 1000);
            
            */
            
            $adopterImageURL = $faker->fakeImgUrl($width = 1000, $height = 1000);
            $adopterImage = file_get_contents($adopterImageURL);
            $adopterImageBase64 = 'data:image/png;base64,' . base64_encode($adopterImage);

            PetAdopter::create([
                'user_id' => $adopter->id,
                'name' => $faker->name(),
                'phone' => $faker->phoneNumber(),
                'address' => $faker->address(),
                /* 'picture' => str_replace('public/', '', $adopterPicture), */
                'picture' => $adopterImageBase64,
            ]);

            // PetAgency

            $agency = User::create([
                'email' => $faker->email(),
                'password' => Hash::make('password'),
                'is_pet_agency' => true,
            ]);

            // Pet Agency Picture
            
            /*

            $agencyPictureFolder = 'public/storage/agencies';

            if (!File::exists($agencyPictureFolder)) {
                File::makeDirectory($agencyPictureFolder, 0755, true);
            }

            $agencyPicture = $faker->fakeImg($dir = $agencyPictureFolder, $width = 1000, $height = 1000);
            
            */
            
            $agencyImageURL = $faker->fakeImgUrl($width = 1000, $height = 1000);
            $agencyImage = file_get_contents($agencyImageURL);
            $agencyImageBase64 = 'data:image/png;base64,' . base64_encode($agencyImage);

            PetAgency::create([
                'user_id' => $agency->id,
                'name' => $faker->company(),
                'phone' => $faker->phoneNumber(),
                'address' => $faker->address(),
                'website' => $faker->url(),
                'description' => $faker->realTextBetween($minNbChars = 200, $maxNbChars = 1000, $indexSize = 2),
                /* 'picture' => str_replace('public/', '', $agencyPicture), */
                'picture' => $agencyImageBase64,
            ]);

        }

    }
}
