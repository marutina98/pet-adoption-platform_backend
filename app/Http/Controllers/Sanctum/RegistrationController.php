<?php

namespace App\Http\Controllers\Sanctum;

use App\Models\User;
use App\Models\PetAgency;
use App\Models\PetAdopter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    
    public function registrateAgency() {

        // Validate Credentials

        $data = request()->validate([
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'min:8'],
        ]);

        // Create User and Pet Agency Profile

        $user = User::create([
            'email' => request('email'),
            'password' => Hash::make(request('password')),
            'is_pet_agency' => true,
        ]);

        $agency = PetAgency::create(['user_id' => $user->id]);

        // Load

        $user->load(['petAgency',  'petAgency.receivedApplications.animal.animalPictures',
                     'reviewsGiven', 'reviewsReceived']);

        // Return Token

        $token = $user->createToken($user->username . '-Authentication-Token')->plainTextToken;

        return response()->json([
            'message' => 'Registration: Success!',
            'token' => $token,
            'user' => $user,
        ], 200);

    }

    public function registrateAdopter() {

        // Validate Credentials

        $data = request()->validate([
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'min:8'],
        ]);

        // Create User and Pet Adopter Profile

        $user = User::create([
            'email' => request('email'),
            'username' => request('username'),
            'password' => Hash::make(request('password')),
            'is_pet_adopter' => true,
        ]);

        $adopter = PetAdopter::create(['user_id' => $user->id]);

        // Load

        $user->load(['petAdopter', 'petAdopter.sentApplications.animal.animalPictures',
                     'reviewsGiven', 'reviewsReceived']);

        // Return Token

        $token = $user->createToken($user->username . '-Authentication-Token')->plainTextToken;

        return response()->json([
            'message' => 'Registration: Success!',
            'token' => $token,
            'user' => $user,
        ], 200);
    }

}
