<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PetAdopter;
use App\Models\PetAgency;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function index() {
        return User::with(['petAgency', 'petAdopter'])->get();
    }

    public function show(User $user) {
        return $user->load(['petAgency', 'petAdopter']);
    }
    
    public function showAuthenticatedUser() {
        return auth()->user()->load(['petAgency', 'petAdopter',
                                     'petAgency.receivedApplications.animal.animalPictures',
                                     'petAdopter.sentApplications.animal.animalPictures',
                                     'reviewsGiven', 'reviewsReceived'
                                    ]);
    }

    public function store() {

        // Boolean

        $isAdmin = !request('is_pet_agency') && !request('is_pet_adopter');
        $isPetAgency = !request('is_administrator') && !request('is_pet_adopter');
        $isPetAdopter = !request('is_administrator') && !request('is_pet_agency');

        // Validate Data

        $data = request()->validate([
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'min:8'],
            'is_administrator' => ['required', 'boolean', Rule::requiredIf($isAdmin)],
            'is_pet_agency' => ['required', 'boolean', Rule::requiredIf($isPetAgency)],
            'is_pet_adopter' => ['required', 'boolean', Rule::requiredIf($isPetAdopter)],
        ]);

        // Create User

        $user = User::create($data);

        // Create Correct Profile (Pet Agency/Adopter do work as Profiles after all)

        if (request('is_pet_agency')) {
            PetAgency::create(['user_id' => $user->id]);
            $user->load('petAgency');
        }

        if (request('is_pet_adopter')) {
            PetAdopter::create(['user_id' => $user->id]);
            $user->load('petAdopter');
        }

        // Return User

        return response()->json([
            'message' => 'User Creation: Success!',
            'object' => $user,
        ], 200);

    }

    public function update(User $user) {

        $isAdmin = !request('is_pet_agency') && !request('is_pet_adopter');
        $isPetAgency = !request('is_administrator') && !request('is_pet_adopter');
        $isPetAdopter = !request('is_administrator') && !request('is_pet_agency');

        // Validate

        $data = request()->validate([
            'email' => ['required', 'string', 'email', Rule::unique('users')->ignore($user->id)],
            'is_administrator' => ['required', 'boolean', Rule::requiredIf($isAdmin)],
            'is_pet_agency' => ['required', 'boolean', Rule::requiredIf($isPetAgency)],
            'is_pet_adopter' => ['required', 'boolean', Rule::requiredIf($isPetAdopter)],
        ]);

        // Update User

        $user->update($data);

        // Create/Delete PetAgency/Adopter Profile if needed.

        if ($data['is_administrator']) {

            PetAgency::where('user_id', $user->id)->delete();
            PetAdopter::where('user_id', $user->id)->delete();

        } else {

            if ($user->is_pet_agency && !$data['is_pet_agency']) {
                PetAgency::where('user_id', $user->id)->delete();
            }

            if ($user->is_pet_adopter && !$data['is_pet_adopter']) {
                PetAdopter::where('user_id', $user->id)->delete();
            }

            if ($data['is_pet_agency']) {
                PetAgency::firstOrCreate(['user_id' => $user->id]);
                $user->load('petAgency');
            }

            if ($data['is_pet_adopter']) {
                PetAdopter::firstOrCreate(['user_id' => $user->id]);
                $user->load('petAdopter');
            }

        }

        // Return User

        return response()->json([
            'message' => 'User Creation: Success!',
            'object' => $user,
        ], 200);

    }

    public function delete(User $user) {

        $user->delete();

        return response()->json([
            'message' => 'User: Deletion Successful.',
        ], 200);

    }

}
