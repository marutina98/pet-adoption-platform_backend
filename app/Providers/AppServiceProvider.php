<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Animal;
use App\Models\Application;
use App\Models\AnimalPicture;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Animal

        Gate::define('create-animal', function(User $user) {

            // The animal can only be created if the user is
            // a pet agency.

            return $user->is_pet_agency;

        });

        Gate::define('update-animal', function(User $user, Animal $animal) {

            // The animal can only be updated if the user is
            // a pet agency and the author of the animal, or
            // an administrator

            if ($user->is_administrator) {
                return true;
            }

            if ($user->is_pet_agency) {
                $user->load('petAgency');
                return $animal->pet_agency_id === $user->petAgency->id;
            }

            return false;

        });



        Gate::define('delete-animal', function(User $user, Animal $animal) {

            // The animal can only be deleted if the user is
            // a pet agency and the author of the animal, or
            // an administrator

            if ($user->is_administrator) {
                return true;
            }

            if ($user->is_pet_agency) {
                $user->load('petAgency');
                return $animal->pet_agency_id === $user->petAgency->id;
            }

            return false;

        });

        Gate::define('update-animal-status-to-adopted', function(User $user, Animal $animal) {

            // The animal status can be set as adopted only by the petAgency
            // that created the animal, and only if the animal is available (id: 1)

            if ($user->is_pet_agency && $animal->status_id === 1) {
                $user->load('petAgency');
                return $animal->pet_agency_id === $user->petAgency->id;
            }

            return false;

        });

        // Application

        Gate::define('create-application', function(User $user, Animal $animal) {

            // The application can only be created if the user is
            // a pet adopter and the animal is available.

            return $user->is_pet_adopter && $animal->status_id === 1;

        });

        Gate::define('update-application', function(User $user, Application $application) {

            // The application can only by updated by the petAgency
            // user that received it, and only if the application-status is
            // pending review (id: 1).

            // Check that the petAgency created the animal from the application.

            if ($user->is_pet_agency && $application->application_status_id === 1) {
                $user->load('petAgency.animals');
                $animalIds = $user->petAgency->animals->modelKeys();
                return in_array($application->animal_id, $animalIds);
            }

            return false;

        });

        Gate::define('view-application', function(User $user, Application $application) {

            // The application can only be viewed if the user is
            // the pet adopter that sent the application or the pet agency
            // that received it.

            $user->load('petAdopter', 'petAgency.animals');

            // Pet Adopter

            if ($user->is_pet_adopter) {
                return $user->petAdopter->id === $application->pet_adopter_id;
            }

            // Pet Agency

            if ($user->is_pet_agency) {
                $animalIds = $user->petAgency->animals->modelKeys();
                return in_array($application->animal_id, $animalIds);
            }

            // Do not allow otherwise.

            return false;

        });

        // Pictures

        Gate::define('delete-picture', function(User $user, AnimalPicture $picture) {

            // The picture can only be deleted if the user is an administrator
            // or the pet agency that created the animal.

            if ($user->is_administrator) {
                return true;
            }

            if ($user->is_pet_agency) {
                $user->load('petAgency.animals');              
                $animalIds = $user->petAgency->animals->modelKeys();
                return in_array($picture->animal_id, $animalIds);
            }

            return false;

        });

        // Review

        Gate::define('create-review', function(User $user, User $reviewee) {

            // Check first if the user is trying to review themselves.

            if ($user->id === $reviewee->id) {
                return false;
            }

            // Check if a review for said user is needed.

            if ($user->is_pet_adopter) {

                // Load pending reviews

                $user->load('petAdopter');
                $pendingReviews = $user->petAdopter->pendingReviews($user);

                // $user->load('petAdopter.pendingReviews');

                // Get User Ids
                // Verify if $reviewee->id is present in this array

                $userIds = $pendingReviews->pluck('user_id')->toArray();
                return in_array($reviewee->id, $userIds);

            }

            if ($user->is_pet_agency) {

                // Load pending reviews

                $user->load('petAgency');
                $pendingReviews = $user->petAgency->pendingReviews($user);

                // $user->load('petAgency.pendingReviews');

                // Get User Ids
                // Verify if $reviewee->id is present in this array

                $userIds = $pendingReviews->pluck('user_id')->toArray();
                return in_array($reviewee->id, $userIds);

            }

            return false;

        });

    }

}
