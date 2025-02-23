<?php

namespace App\Http\Controllers;

use App\Models\AnimalType;
use Illuminate\Http\Request;

class AnimalTypeController extends Controller
{
    
    public function index() {
        return AnimalType::all();
    }

    public function show(AnimalType $type) {
        return $type;
    }

    public function store() {

        $data = request()->validate([
            'name' => ['required', 'string'],
            'description' => ['required', 'string']
        ]);

        // Sanitize Inputs

        $data['name'] = strip_tags($data['name']);
        $data['description'] = strip_tags($data['description']);

        // Create Animal Type

        $type = AnimalType::create($data);

        return response()->json([
            'message' => 'Animal Type: Creation Successful',
            'object' => $type,
        ], 200);

    }

    public function update(AnimalType $type) {

        $data = request()->validate([
            'name' => ['required', 'string'],
            'description' => ['required', 'string']
        ]);

        // Sanitize Inputs

        $data['name'] = strip_tags($data['name']);
        $data['description'] = strip_tags($data['description']);

        // Update Animal Type

        $type->update($data);

        return response()->json([
            'message' => 'Animal Type: Update Successful',
            'object' => $type,
        ], 200);

    }

    public function delete(AnimalType $type) {

        $type->delete();

        return response()->json([
            'message' => 'Animal Type: Deletion Successful',
        ], 200);

    }

}
