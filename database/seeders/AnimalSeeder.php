<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\AnimalType;
use App\Models\AnimalBreed;
use App\Models\AnimalStatus;
use App\Models\AnimalPicture;
use App\Models\AnimalCoatColor;

use App\Models\PetAgency;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // For all Animal Type create 10 animals.
        // Images must be placeholders for now.

        $petAgencies = PetAgency::all();
        $animalTypes = AnimalType::all();
        $animalCoatColors = AnimalCoatColor::all();

        // Faker for images

        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Mmo\Faker\FakeimgProvider($faker));

        // Create Animals for Each Animal Breed

        foreach ($animalTypes as $animalType) {

            $animalBreeds = AnimalBreed::where('animal_type_id', $animalType->id)->get();

            // Create 1 Animals for each Animal Breed

            foreach ($animalBreeds as $animalBreed) {

                for ($i = 0; $i < 1; $i++) { 
                    
                    // Get Random Values

                    $randomSex = $faker->numberBetween(0, 1);
                    $randomCoatLength = $faker->numberBetween(0, 2);
                    $randomBirthDate = $faker->dateTimeBetween('5 years ago', 'today');
                    $randomCoatColor = $animalCoatColors->random();
                    $randomPetAgencyUser = $petAgencies->random();

                    // Get Name

                    $name = $randomSex === 0 ?
                            $faker->firstName('male') :
                            $faker->firstName('female');

                    // Create Animal

                    $animal = Animal::create([
                        'sex' => $randomSex,
                        'name' => $name,
                        'description' => $faker->text(),
                        'coat_length' => $randomCoatLength,
                        'birthdate' => $randomBirthDate,
                        'pet_agency_id' => $randomPetAgencyUser->id,
                        'animal_type_id' => $animalType->id,
                        'animal_breed_id' => $animalBreed->id,
                        'animal_coat_color_id' => $randomCoatColor->id,
                        'status_id' => 1
                    ]);

                    // Create Fake Images

                    for ($j = 0; $j < 2; $j ++) {

                        /*

                        $pictureFolder = 'public/storage/' . strtolower($animalType->name);

                        if (!File::exists($pictureFolder)) {
                            File::makeDirectory($pictureFolder, 0755, true);
                        }

                        $picture = $faker->fakeImg($dir = $pictureFolder, $width = 1000, $height = 1000);
                        
                        AnimalPicture::create([
                            'animal_id' => $animal->id,
                            'path' => str_replace('public/', '', $picture),
                        ]);

                        */

                        /*

                        $pictureURL = $faker->fakeImgUrl($width = 1000, $height = 1000);
                        $pictureData = file_get_contents($picture);
                        $pictureBase64 = base64_encode($pictureData);

                        AnimalPicture::create([
                            'animal_id' => $animal->id,
                            'path' => $pictureBase64,
                        ]);

                        */

                        $imageURL = $faker->fakeImgUrl($width = 1000, $height = 1000);
                        $image = file_get_contents($imageURL);
                        $imageBase64 = 'data:image/png;base64,' . base64_encode($image);

                        AnimalPicture::create([
                            'animal_id' => $animal->id,
                            'path' => $imageBase64,
                        ]);

                    }

                }

            }

        }

    }

}
