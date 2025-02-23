<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalPicture;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class AnimalPictureController extends Controller
{
    
    public function index() {
        return AnimalPicture::all();
    }

    public function show(Animal $animal) {
        return AnimalPicture::where('animal_id', $animal->id)->get();
    }

    public function delete(AnimalPicture $picture) {

        if (!Gate::allows('delete-picture', $picture)) {
            return response()->json([
                'message' => 'You cannot delete this picture. You are not the author nor an administrator.',
            ], 403);
        }

        // Delete Picture

        Storage::delete('public/' . $picture->path);
        $picture->delete();

        return response()->json([
            'message' => 'Animal Picture Delete: Success!',
        ], 200);

    }

}
