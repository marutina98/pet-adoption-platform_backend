<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PetAgency;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PetAgencyController extends Controller
{
    
    public function index() {
        return PetAgency::with('user.reviewsGiven', 'user.reviewsReceived')->get();
    }

    public function show(PetAgency $agency) {

        return $agency->load('user.reviewsGiven', 'user.reviewsReceived', 'animals.animalPictures',
                      'receivedApplications.animal.animalPictures');

    }

    public function store() {

        // Validate Received Data

        $data = request()->validate([
            'name' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'regex:/^[- +()0-9]+$/'],
            'website' => ['nullable', 'url'],
            'address' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        // Sanitize Inputs

        $data['name'] = strip_tags($data['name']);
        $data['address'] = strip_tags($data['address']);
        $data['website'] = strip_tags($data['website']);
        $data['description'] = strip_tags($data['description']);

        // Updated Picture if Received

        if (request('picture')) {
        
            /*

            $data['picture'] = request('picture')->store('agencies', 'public');
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
                $data['picture'] = $imageBase64;

            } 

        }

        // Update Pet Agency

        auth()->user()->load('petAgency');
        auth()->user()->petAgency->update($data);

        return response()->json([
            'message' => 'Pet Agency: Success!',
            'object' => auth()->user(),
        ], 200);

    }

    public function storeAdministrator(PetAgency $agency) {

        // Validate Received Data

        $data = request()->validate([
            'name' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'regex:/^[- +()0-9]+$/'],
            'website' => ['nullable', 'url'],
            'address' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        // Sanitize Inputs

        $data['name'] = strip_tags($data['name']);
        $data['address'] = strip_tags($data['address']);
        $data['website'] = strip_tags($data['website']);
        $data['description'] = strip_tags($data['description']);

        // Updated Picture if Received

        if (request('picture')) {
        
            /*

            $data['picture'] = request('picture')->store('agencies', 'public');
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
                $data['picture'] = $imageBase64;

            } 

        }

        // Update Pet Agency

        $agency->update($data);

        return response()->json([
            'message' => 'Pet Agency: Success!',
            'object' => $agency,
        ], 200);

    }

    public function update(User $user) {

        $data = request()->validate([
            'name' => ['nullable', 'string'],
            'phone' => ['nullable', 'phone:INTERNATIONAL'],
            'website' => ['nullable', 'url'],
            'address' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        // Sanitize Inputs

        $data['name'] = strip_tags($data['name']);
        $data['address'] = strip_tags($data['address']);
        $data['website'] = strip_tags($data['website']);
        $data['description'] = strip_tags($data['description']);

        $user->load('petAgency');
        $user->petAgency->update($data);

        return response()->json([
            'message' => 'Pet Agency: Success!',
            'object' => $user,
        ], 200);

    }

    public function delete(User $user) {

        $agency = PetAgency::where('user_id', $user->id)->first();
        $agency->delete();

        return response()->json([
            'message' => 'Pet Agency: Deletion Successful.',
        ], 200);

    }

}
