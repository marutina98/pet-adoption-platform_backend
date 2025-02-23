<?php

namespace App\Http\Controllers;

use App\Models\AnimalCoatColor;
use Illuminate\Http\Request;

class AnimalCoatColorController extends Controller
{
    
    public function index() {
        return AnimalCoatColor::all();
    }

    public function show(AnimalCoatColor $coat) {
        return $coat;
    }

    public function store() {

        $data = request()->validate([
            'name' => ['required', 'string'],
            'hex_color' => ['nullable', 'string', 'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i']
        ]);

        $data['name'] = strip_tags($data['name']);

        $type = AnimalCoatColor::create($data);

        return response()->json([
            'message' => 'Animal Coat Color: Creation Successful',
            'object' => $type,
        ], 200);

    }

    public function update(AnimalCoatColor $coat) {

        $data = request()->validate([
            'name' => ['required', 'string'],
            'hex_color' => ['nullable', 'string', 'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'],
        ]);

        $data['name'] = strip_tags($data['name']);

        $coat->update($data);

        return response()->json([
            'message' => 'Animal Coat Color: Update Successful',
            'object' => $coat,
        ], 200);

    }

    public function delete(AnimalCoatColor $coat) {

        $coat->delete();

        return response()->json([
            'message' => 'Animal Coat Color: Deletion Successful',
        ], 200);

    }

}
