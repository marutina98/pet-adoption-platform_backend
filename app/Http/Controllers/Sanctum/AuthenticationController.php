<?php

namespace App\Http\Controllers\Sanctum;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function __invoke() {

        // Validate Request and Find User

        $data = request()->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'min:8']
        ]);

        $user = User::with(['petAgency', 'petAdopter',
                            'petAgency.receivedApplications.animal.animalPictures',
                            'petAdopter.sentApplications.animal.animalPictures',
                            'reviewsGiven', 'reviewsReceived'
                           ])->where('email', $data['email'])->first();

        // If the user *doesn't* exist and the password is *incorrect*
        // return error.

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Authentication: Invalid Credentials.',
            ], 401);
        }

        // Otherwise return token.

        $token = $user->createToken($user->username . '-Authentication-Token')->plainTextToken;

        return response()->json([
            'message' => 'Authentication: Success!',
            'token' => $token,
            'user' => $user,
        ], 200);

    }
}
