<?php

namespace App\Http\Controllers;

use App\Models\AnimalBreed;
use App\Models\AnimalType;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnimalBreedController extends Controller
{
    
    public function index() {
        return AnimalBreed::all();
    }

    public function indexAnimalBreedsByType(AnimalType $type) {
        return AnimalBreed::where('animal_type_id', $type->id)->get();
    }

    public function show(AnimalBreed $breed) {
        return $breed;
    }

    public function store() {

        $unique = Rule::unique('animal_breeds')->where(function ($query) {
            return $query->where('animal_type_id', request()->input('animal_type_id'));
        });

        $data = request()->validate([
            'name' => ['required','string', $unique],
            'description' => ['required', 'string'],
            'animal_type_id' => ['required', 'numeric', 'exists:animal_types,id']
        ]);

        $data['name'] = strip_tags($data['name']);
        $data['description'] = strip_tags($data['description']);

        $breed = AnimalBreed::create($data);

        return response()->json([
            'message' => 'Animal Breed: Creation Successful',
            'object' => $breed,
        ], 200);

    }

    public function update(AnimalBreed $breed) {

        $unique = Rule::unique('animal_breeds')->where(function ($query) {
            return $query->where('animal_type_id', request()->input('animal_type_id'));
        });

        $data = request()->validate([
            'name' => ['required','string', $unique],
            'description' => ['required', 'string'],
            'animal_type_id' => ['required', 'numeric', 'exists:animal_types,id']
        ]);

        $data['name'] = strip_tags($data['name']);
        $data['description'] = strip_tags($data['description']);

        $breed->update($data);

        return response()->json([
            'message' => 'Animal Breed: Update Successful',
            'object' => $breed,
        ], 200);

    }

    public function delete(AnimalBreed $breed) {

        $breed->delete();

        return response()->json([
            'message' => 'Animal Breed: Deletion Successful',
        ], 200);

    }

}
