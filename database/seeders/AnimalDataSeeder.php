<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AnimalType;
use App\Models\AnimalBreed;

class AnimalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Types

        $dog = AnimalType::create([
            'name' => 'Dogs',
            'description' => 'Loyal and playful pet known for its companionship and varied breeds.',
        ]);

        $cat = AnimalType::create([
            'name' => 'Cats',
            'description' => 'Independent and affectionate pet known for its agility and curiosity.',
        ]);

        $rabbit = AnimalType::create([
            'name' => 'Rabbits',
            'description' => 'Gentle and social pet known for its soft fur and friendly nature.',
        ]);

        // Breeds: Dogs

        $generalDogBreed = AnimalBreed::create([
            'animal_type_id' => $dog->id,
            'name' => 'Mixed Breed Dog',
            'description' => 'Category for dogs of mixed or unidentified breed.',
        ]);

        $labradorRetriever = AnimalBreed::create([
            'animal_type_id' => $dog->id,
            'name' => 'Labrador Retriever',
            'description' => 'Friendly and outgoing with a sturdy build and a short, dense coat.',
        ]);

        $germanShepherd = AnimalBreed::create([
            'animal_type_id' => $dog->id,
            'name' => 'German Shepherd',
            'description' => 'Loyal and intelligent, with a muscular build and a dense, medium-length coat.',
        ]);

        $goldenRetriever = AnimalBreed::create([
            'animal_type_id' => $dog->id,
            'name' => 'Golden Retriever',
            'description' => 'Friendly and tolerant, with a dense, water-repellant golden coat and a sturdy build.',
        ]);

        $bulldog = AnimalBreed::create([
            'animal_type_id' => $dog->id,
            'name' => 'Bulldog',
            'description' => 'Calm and courageous, with a wrinkled face and a distinctive pushed-in nose.',
        ]);

        $beagle = AnimalBreed::create([
            'animal_type_id' => $dog->id,
            'name' => 'Beagle',
            'description' => 'Curious and merry, with a compact build and short, easy-to-care-for coat.',
        ]);

        $pug = AnimalBreed::create([
            'animal_type_id' => $dog->id,
            'name' => 'Pug',
            'description' => 'Charming and playful, with a distinctive wrinkled face and curly tail.',
        ]);

        $chihuahua = AnimalBreed::create([
            'animal_type_id' => $dog->id,
            'name' => 'Chihuahua',
            'description' => 'Alert and lively, with a small size and big personality.',
        ]);

        $boxer = AnimalBreed::create([
            'animal_type_id' => $dog->id,
            'name' => 'Boxer',
            'description' => 'Energetic and fun-loving, with a strong, muscular build.',
        ]);

        $poodle = AnimalBreed::create([
            'animal_type_id' => $dog->id,
            'name' => 'Poodle',
            'description' => 'Intelligent and active, known for their curly coat and elegance.',
        ]);

        $rottweiler = AnimalBreed::create([
            'animal_type_id' => $dog->id,
            'name' => 'Rottweiler',
            'description' => 'Confident and protective, with a robust build and short, black-and-tan coat.',
        ]);

        $siberianHusky = AnimalBreed::create([
            'animal_type_id' => $dog->id,
            'name' => 'Siberian Husky',
            'description' => 'Friendly and energetic, with a thick double coat and striking blue or multicolored eyes.',
        ]);

        $dachshund = AnimalBreed::create([
            'animal_type_id' => $dog->id,
            'name' => 'Dachshund',
            'description' => 'Clever and lively, with a long body and short legs.',
        ]);

        $shihTzu = AnimalBreed::create([
            'animal_type_id' => $dog->id,
            'name' => 'Shih Tzu',
            'description' => 'Affectionate and outgoing, with a long, flowing coat.',
        ]);

        // Breeds: Cats

        $generalCatBreed = AnimalBreed::create([
            'animal_type_id' => $cat->id,
            'name' => 'Mixed Breed Cat',
            'description' => 'Category for cats of mixed or unidentified breed.',
        ]);

        $siamese = AnimalBreed::create([
            'animal_type_id' => $cat->id,
            'name' => 'Siamese',
            'description' => 'Sociable and vocal, with a sleek, slender body and striking blue almond-shaped eyes.',
        ]);

        $persian = AnimalBreed::create([
            'animal_type_id' => $cat->id,
            'name' => 'Persian',
            'description' => 'Calm and affectionate, with a long, luxurious coat and a flat, round face.',
        ]);

        $maineCoon = AnimalBreed::create([
            'animal_type_id' => $cat->id,
            'name' => 'Maine Coon',
            'description' => 'Friendly and intelligent, with a large, sturdy build and a long, shaggy coat.',
        ]);

        $bengal = AnimalBreed::create([
            'animal_type_id' => $cat->id,
            'name' => 'Bengal',
            'description' => 'Active and playful, with a sleek, muscular body and a distinctive spotted or marbled coat.',
        ]);

        $sphynx = AnimalBreed::create([
            'animal_type_id' => $cat->id,
            'name' => 'Sphynx',
            'description' => 'Energetic and affectionate, with a hairless body and large, bat-like ears.',
        ]);

        // Breeds: Rabbits

        $generalRabbitBreed = AnimalBreed::create([
            'animal_type_id' => $rabbit->id,
            'name' => 'Mixed Breed Rabbit',
            'description' => 'Category for rabbits of mixed or unidentified breed.',
        ]);

        $hollandLop = AnimalBreed::create([
            'animal_type_id' => $rabbit->id,
            'name' => 'Holland Lop',
            'description' => 'Friendly and energetic, with floppy ears and a compact, muscular build.',
        ]);

        $netherlandDwarf = AnimalBreed::create([
            'animal_type_id' => $rabbit->id,
            'name' => 'Netherland Dwarf',
            'description' => 'Lively and curious, with a small, compact body and short, upright ears.',
        ]);

        $miniRex = AnimalBreed::create([
            'animal_type_id' => $rabbit->id,
            'name' => 'Mini Rex',
            'description' => 'Calm and friendly, with a velvety, plush coat and a compact, rounded body.',
        ]);

        $lionhead = AnimalBreed::create([
            'animal_type_id' => $rabbit->id,
            'name' => 'Lionhead',
            'description' => 'Playful and gentle, with a distinctive mane of long fur around their head and neck.',
        ]);

        $flemishGiant = AnimalBreed::create([
            'animal_type_id' => $rabbit->id,
            'name' => 'Flemish Giant',
            'description' => 'Calm and docile, with a large, sturdy build and a dense, glossy coat.',
        ]);

    }
}
