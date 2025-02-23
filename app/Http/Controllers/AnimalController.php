<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalType;
use App\Models\AnimalBreed;
use App\Models\AnimalStatus;
use App\Models\AnimalPicture;
use App\Models\PetAgency;
use App\Models\Application;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class AnimalController extends Controller
{

    public function index() {
        return Animal::with('animalPictures')->get();
    }

    public function indexAnimalsByType(AnimalType $type) {
        return Animal::with('animalPictures')->where('animal_type_id', $type->id)->get();
    }

    public function indexAnimalsByPetAgency(PetAgency $agency) {
        return $agency->load('animals.animalPictures')->animals;
    }

    public function show(Animal $animal) {
        return $animal->load('animalPictures');
    }

    public function store() {

        if (!Gate::allows('create-animal')) {
            return response()->json([
                'message' => 'You cannot create an animal. You are not a pet agency.',
            ], 403);
        }

        $user = auth()->user()->load('petAgency');

        $data = request()->validate([
            'sex' => ['required', 'integer', 'min:0', 'max:1'],
            'name' => ['required', 'string'],
            'birthdate' => ['required', 'date'],
            'description' => ['required', 'string'],
            'coat_length' => ['required', 'integer', 'min:0', 'max:2'],
            'animal_type_id' => ['required', 'integer', 'exists:animal_types,id'],
            'animal_breed_id' => ['nullable', 'integer'],
            'animal_coat_color_id' => ['required', 'integer', 'exists:animal_coat_colors,id'],
            'pictures' => ['required', 'array', 'min:1'],
            'pictures.*' => ['image'],
        ]);

        // Sanitize Inputs

        $data['name'] = strip_tags($data['name']);
        $data['description'] = strip_tags($data['description']);

        // If AnimalBreed is present, verify that is a valid breed for
        // the chosen AnimalType.

        if (request('animal_breed_id')) {

            $animalTypeId = request('animal_type_id');
            $animalBreedId = request('animal_breed_id');
            $isValidBreed = AnimalBreed::where('id', $animalBreedId)
                                  ->where('animal_type_id', $animalTypeId)
                                  ->exists();

            if (!$isValidBreed) {
                return response()->json([
                    'message' => 'Animal Creation: The chosen Animal Breed is not available for the chosen Animal Type.',
                ], 422);
            }

        }

        // Start Database Transaction

        DB::beginTransaction();

        try {

            // Add Pet Agency Id and Create Animal
            // Set Status_Id to 1

            $data['pet_agency_id'] = $user->petAgency->id;

            $animal = Animal::create($data);

            // Get the chosen animal type name for the correct image folder path.

            $animalType = AnimalType::find(request('animal_type_id'));
            $animalTypeName = strtolower($animalType->name);

            // Create and Load Animal Pictures

            foreach ($data['pictures'] as $index => $picture) {

                $storedPath = $picture->store($animalTypeName, 'public');
                $picturePath = 'storage/' . $storedPath;

                $image = Image::make(storage_path('app/public/' . $storedPath));
                $image->resize(1000, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $image->save(storage_path('app/public/' . $storedPath));

                $data['pictures'][$index] = $picturePath;

                AnimalPicture::create([
                    'animal_id' => $animal->id,
                    'path' => $picturePath,
                ]);

            }

            // Commit Database Transaction

            DB::commit();

            // Load Pictures and Return Animal

            $animal->load('animalPictures');

            return response()->json([
                'message' => 'Animal Creation: Success!',
                'object' => $animal,
            ], 200);

        } catch (Exception $error) {

            // Rollback and Handle Error

            DB::rollback();

            return response()->json([
                'message' => 'Animal: Creation Failed. Please try again later.',
                'error' => $error->getMessage(),
            ], 500);

        }

    }

    public function update(Animal $animal) {

        if (!Gate::allows('update-animal', $animal)) {
            return response()->json([
                'message' => 'You cannot update this animal. You are not a pet agency, its author or an administrator.',
            ], 403);
        }

        $data = request()->validate([
            'sex' => ['required', 'numeric', 'min:0', 'max:1'],
            'name' => ['required', 'string'],
            'birthdate' => ['required', 'date'],
            'description' => ['required', 'string'],
            'coat_length' => ['required', 'numeric', 'min:0', 'max:2'],
            'animal_type_id' => ['required', 'numeric', 'exists:animal_types,id'],
            'animal_breed_id' => ['nullable', 'numeric'],
            'animal_coat_color_id' => ['required', 'numeric', 'exists:animal_coat_colors,id'],
            'status_id' => ['required', 'numeric', 'exists:statuses,id'],
        ]);

        // sanitize inputs

        $data['name'] = strip_tags($data['name']);
        $data['description'] = strip_tags($data['description']);

        // If AnimalBreed is present, verify that is a valid breed for
        // the chosen AnimalType.

        if ($data['animal_breed_id']) {

            $animalTypeId = $data['animal_type_id'];
            $animalBreedId = $data['animal_breed_id'];
            $isValidBreed = AnimalBreed::where('id', $animalBreedId)
                                  ->where('animal_type_id', $animalTypeId)
                                  ->exists();

            if (!$isValidBreed) {
                return response()->json([
                    'message' => 'Animal Update: The chosen Animal Breed is not available for the chosen Animal Type.',
                ], 422);
            }

        }

        // Update Animal

        $animal->update($data);

        return response()->json([
            'message' => 'Animal Update: Success!',
            'object' => $animal,
        ], 200);

    }

    public function delete(Animal $animal) {

        if (!Gate::allows('delete-animal', $animal)) {
            return response()->json([
                'message' => 'You cannot delete this animal. You are not a pet agency, its author or an administrator.',
            ], 403);
        }

        // Delete Pictures

        $animalPictures = AnimalPicture::where('animal_id', $animal->id)->get();

        foreach ($animalPictures as $picture) {
            Storage::delete('public/' . $picture->path);
            $picture->delete();
        }

        // Delete Applications

        $applications = Application::where('animal_id', $animal->id)->get();

        foreach ($applications as $application) {
            $application->delete();
        }

        // Delete Animal

        $animal->delete();

        return response()->json([
            'message' => 'Animal Delete: Success!',
        ], 200);

    }

}
