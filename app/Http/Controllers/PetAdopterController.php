<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PetAdopter;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PetAdopterController extends Controller
{
    
    public function index() {
        return PetAdopter::with('user')->get();
    }

    public function show(PetAdopter $adopter) {
        return $adopter->load(['user.reviewsGiven', 'user.reviewsReceived', 'sentApplications.animal.animalPictures']);
    }

    public function store() {

        // Validate Received Data

        $data = request()->validate([
            'name' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'regex:/^[- +()0-9]+$/'],
            'address' => ['nullable', 'string'],
        ]);

        // Sanitize Inputs

        $data['name'] = strip_tags($data['name']);
        $data['address'] = strip_tags($data['address']);

        // Updated Picture if Received

        if (request('picture')) {
        
            /*

            $data['picture'] = request('picture')->store('adopters', 'public');
            $picturePath = 'storage/' . $data['picture'];
            $picture = Image::make(public_path($picturePath));
            $picture->resize(1000, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $picture->save();

            $data['picture'] = $picturePath;
            
            */
            
            $picture = $data['picture'];
            
            if ($picture->isValid()) {

                $image = file_get_contents($picture->getRealPath());
                $imageBase64 = 'data:image/png;base64,' . base64_encode($image);
                $data['picture' = $imageBase64;

            }            

        }

        // Update Pet Adopter

        auth()->user()->load('petAdopter');
        auth()->user()->petAdopter->update($data);

        return response()->json([
            'message' => 'Pet Adopter: Success!',
            'object' => auth()->user(),
        ], 200);

    }

    public function storeAdministrator(PetAdopter $adopter) {

        // Validate Received Data

        $data = request()->validate([
            'name' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'regex:/^[- +()0-9]+$/'],
            'address' => ['nullable', 'string'],
        ]);

        // Sanitize Inputs

        $data['name'] = strip_tags($data['name']);
        $data['address'] = strip_tags($data['address']);

        // Updated Picture if Received

        if (request('picture')) {
        
            /*

            $data['picture'] = request('picture')->store('adopters', 'public');
            $picturePath = 'storage/' . $data['picture'];
            $picture = Image::make(public_path($picturePath));
            $picture->resize(1000, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $picture->save();

            $data['picture'] = $picturePath;
            
            */
            
            $picture = $data['picture'];
            
            if ($picture->isValid()) {

                $image = file_get_contents($picture->getRealPath());
                $imageBase64 = 'data:image/png;base64,' . base64_encode($image);
                $data['picture' = $imageBase64;

            } 

        }

        // Update Pet Adopter

        $adopter->update($data);

        return response()->json([
            'message' => 'Pet Adopter: Success!',
            'object' => $adopter,
        ], 200);

    }

    public function update(User $user) {

        $data = request()->validate([
            'name' => ['nullable', 'string'],
            'phone' => ['nullable', 'phone:INTERNATIONAL'],
            'address' => ['nullable', 'string'],
        ]);

        // Sanitize Inputs

        $data['name'] = strip_tags($data['name']);
        $data['address'] = strip_tags($data['address']);

        // Update

        $user->load('petAdopter');
        $user->petAdopter->update($data);

        return response()->json([
            'message' => 'Pet Adopter: Success!',
            'object' => $user,
        ], 200);

    }

    public function delete(User $user) {

        $adopter = PetAdopter::where('user_id', $user->id)->first();
        $adopter->delete();

        return response()->json([
            'message' => 'Pet Adopter: Deletion Successful.',
        ], 200);

    }

}
