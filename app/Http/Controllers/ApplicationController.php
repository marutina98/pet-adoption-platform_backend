<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\PetAdopter;
use App\Models\Application;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ApplicationController extends Controller
{
    
    public function index() {

        // Load Applications

        $user = auth()->user();
        $user->load('petAdopter.sentApplication', 'petAgency.receivedApplications');
        
        // Return Applications

        if ($user->is_pet_agency) {
            return $user->petAgency->receivedApplications;
        }

        if ($user->is_pet_adopter) {
            return $user->petAdopter->sentApplications;
        }

    }

    public function show(Application $application) {

        // Authorize Request

        if (!Gate::allows('view-application', $application)) {
            return response()->json([
                'message' => 'You cannot view this application. You are not the author nor the receiver.',
            ], 403);
        }

        return $application;

    }

    public function store(Animal $animal) {

        // Authorize Request

        if (!Gate::allows('create-application', $animal)) {
            return response()->json([
                'message' => 'You are not a pet adopter or the animal is not available.',
            ], 403);
        }

        // Validate Application

        $data = request()->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'phone' => ['nullable', 'string', 'regex:/^[- +()0-9]+$/'],
            'message' => ['required', 'string'],
        ]);

        // Find Pet Adopter

        $petAdopter = PetAdopter::where('user_id', auth()->user()->id)->first();

        // Add animal_id and pet_adopter_id to $data

        // Create Application and return

        $application = Application::create([
            'name' => strip_tags($data['name']),
            'email' => $data['email'],
            'phone' => $data['phone'],
            'message' => strip_tags($data['message']),
            'animal_id' => $animal->id,
            'pet_adopter_id' => $petAdopter->id,
        ]);

        return response()->json([
            'message' => 'Application Successfully sent.',
            'object' => $application,
        ], 200);

    }

    public function update(Application $application) {

        // 1: Pending Review
        // 2: Accepted
        // 3: Refused
        
        // 1: Available
        // 2: Adopted

        if (!Gate::allows('update-application', $application)) {
            return response()->json([
                'message' => 'You are not a pet agency, or the animal was not created by you.',
            ], 403);
        }

        // Validate application_status_id
        // The new application_status_id cannot be 1 (aka Pending Review)

        $data = request()->validate([
            'application_status_id' => ['required', 'integer', 'exists:application_statuses,id', 'min:2', 'max:3'],
        ]);

        // If the application_status_id is 3 (Refused), just refuse it.
        // update the application with the new status and that's it.

        $applicationStatusId = $data['application_status_id'];

        if ($applicationStatusId === 3) {

            $application->update(['application_status_id' => $applicationStatusId]);

        } else if ($applicationStatusId === 2) {

            // Get the animal

            $animal = Animal::find($application->animal_id);

            // Use a gate to verify if the animal's status id can be set to adopted

            if (!Gate::allows('update-animal-status-to-adopted', $animal)) {
                return response()->json([
                    'message' => 'You are not a pet agency, or the animal is not available.',
                ], 403);
            }

            // Set the application_status_id
            // Refuse everyother application for this animal
            // Set the animal status to adopted

            $application->update(['application_status_id' => $applicationStatusId]);

            $applicationsToRefuse = Application::where('animal_id', $animal->id)
                                    ->where('id', '!=', $application->id)
                                    ->get();

            $applicationsToRefuse->each(function ($a) {
                $a->update(['application_status_id' => 3]);
            });

            $animal->update([
                'status_id' => 2,
                'pet_adopter_id' => $application->pet_adopter_id,
            ]);

        }

        return response()->json([
            'message' => 'Application Successfully updated.',
            'object' => $application,
        ], 200);

    }

}
