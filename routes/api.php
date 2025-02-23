<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Events\ChatEvent;
use App\Events\ExampleEvent;

use App\Models\User;
use App\Models\Animal;
use App\Models\AnimalType;

use App\Http\Controllers\Sanctum\LogoutController;
use App\Http\Controllers\Sanctum\RegistrationController;
use App\Http\Controllers\Sanctum\AuthenticationController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ApplicationController;

use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\PetAgencyController;
use App\Http\Controllers\PetAdopterController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AnimalTypeController;
use App\Http\Controllers\AnimalBreedController;
use App\Http\Controllers\AnimalStatusController;
use App\Http\Controllers\AnimalPictureController;
use App\Http\Controllers\AnimalCoatColorController;

use App\Http\Middleware\EnsureAdministrator;
use App\Http\Middleware\EnsurePetAdopter;
use App\Http\Middleware\EnsurePetAgency;
use App\Http\Middleware\EnsurePetAgencyOrPetAdopter;

// Authentication

Route::post('/authentication', AuthenticationController::class);
Route::post('/registration/agency', [RegistrationController::class, 'registrateAgency']);
Route::post('/registration/adopter', [RegistrationController::class, 'registrateAdopter']);
Route::middleware('auth:sanctum')->get('/logout', LogoutController::class);

// Authenticated Users AND Guests

Route::get('/type', [AnimalTypeController::class, 'index']);
Route::get('/type/{type}/animals', [AnimalController::class, 'indexAnimalsByType']);
Route::get('/type/{type}/breeds', [AnimalBreedController::class, 'indexAnimalBreedsByType']);

Route::get('/user', [UserController::class, 'index']);
Route::get('/user/{user}', [UserController::class, 'show']);

Route::get('/review', [ReviewController::class, 'index']);
Route::get('/review/received/{user}', [ReviewController::class, 'showReceivedReviews']);
Route::get('/review/given/{user}', [ReviewController::class, 'showGivenReviews']);

Route::get('/agency', [PetAgencyController::class, 'index']);
Route::get('/agency/{agency}', [PetAgencyController::class, 'show']);

Route::get('/adopter', [PetAdopterController::class, 'index']);
Route::get('/adopter/{adopter}', [PetAdopterController::class, 'show']);

Route::get('/animal', [AnimalController::class, 'index']);
Route::get('/animal/agency/{agency}', [AnimalController::class, 'indexAnimalsByPetAgency']);
Route::get('/animal/id/{animal}', [AnimalController::class, 'show']);

Route::get('/picture', [AnimalPictureController::class, 'index']);
Route::get('/picture/{animal}', [AnimalPictureController::class, 'show']);

Route::get('/type', [AnimalTypeController::class, 'index']);
Route::get('/type/{type}', [AnimalTypeController::class, 'show']);

Route::get('/breed', [AnimalBreedController::class, 'index']);
Route::get('/breed/{breed}', [AnimalBreedController::class, 'show']);

Route::get('/coat', [AnimalCoatColorController::class, 'index']);
Route::get('/coat/{coat}', [AnimalCoatColorController::class, 'show']);

Route::get('/status', [StatusController::class, 'index']);
Route::get('/status/{status}', [StatusController::class, 'show']);

// Administrator

Route::middleware(['auth:sanctum', EnsureAdministrator::class])->group(function() {

  // CRUD User

  Route::post('/user', [UserController::class, 'store']);
  Route::put('/user/{user}', [UserController::class, 'update']);
  Route::delete('/user/{user}', [UserController::class, 'delete']);

  // CRUD Pet Agencies

  Route::post('/agency/admin/{agency}', [PetAgencyController::class, 'storeAdministrator']);
  Route::delete('/agency/{agency}', [PetAgencyController::class, 'delete']);

  // CRUD Pet Adopters

  Route::post('/adopter/admin/{adopter}', [PetAdopterController::class, 'storeAdministrator']);
  Route::delete('/adopter/{adopter}', [PetAgencyController::class, 'delete']);

  // CRUD Animal Types

  Route::post('/type', [AnimalTypeController::class, 'store']);
  Route::put('/type/{type}', [AnimalTypeController::class, 'update']);
  Route::delete('/type/{type}', [AnimalTypeController::class, 'delete']);

  // CRUD Animal Breeds

  Route::post('/breed', [AnimalBreedController::class, 'store']);
  Route::put('/breed/{breed}', [AnimalBreedController::class, 'update']);
  Route::delete('/breed/{breed}', [AnimalBreedController::class, 'delete']);

  // CRUD Animal Coat Colors

  Route::post('/coat', [AnimalCoatColorController::class, 'store']);
  Route::put('/coat/{coat}', [AnimalCoatColorController::class, 'update']);
  Route::delete('/coat/{coat}', [AnimalCoatColorController::class, 'delete']);

  // CRUD Statuses

  Route::post('/status', [StatusController::class, 'store']);
  Route::put('/status/{status}', [StatusController::class, 'update']);
  Route::delete('/status/{status}', [StatusController::class, 'delete']);

  // CRUD: Animal

  Route::put('/animal/admin/{animal}', [AnimalController::class, 'update']);
  Route::delete('/animal/admin/{animal}', [AnimalController::class, 'delete']);

  // CRUD: Animal Picture

  Route::delete('/picture/admin/{picture}', [AnimalPictureController::class, 'delete']);

});

// Authenticated User

Route::middleware('auth:sanctum')->get('/authenticated-user', [UserController::class, 'showAuthenticatedUser']);

// Pet Adopters and Pet Agencies

Route::middleware(['auth:sanctum', EnsurePetAgencyOrPetAdopter::class])->group(function() {
  Route::get('/application', [ApplicationController::class, 'index']);
  Route::get('/application/{application}', [ApplicationController::class, 'show']);
  Route::get('/review/pending', [ReviewController::class, 'showPendingReviews']);
  Route::post('/review', [ReviewController::class, 'store']);
});

// Pet Adopters

Route::middleware(['auth:sanctum', EnsurePetAdopter::class])->group(function() {
  Route::post('/adopter', [PetAdopterController::class, 'store']);
  Route::post('/application/{animal}', [ApplicationController::class, 'store']);
});

// Pet Agencies

Route::middleware(['auth:sanctum', EnsurePetAgency::class])->group(function() {
  Route::post('/agency', [PetAgencyController::class, 'store']);
  Route::post('/animal', [AnimalController::class, 'store']);
  Route::put('/animal/{animal}', [AnimalController::class, 'update']);
  Route::delete('/animal/{animal}', [AnimalController::class, 'delete']);
  Route::delete('/picture/{picture}', [AnimalPictureController::class, 'delete']);
  Route::put('/application/{application}', [ApplicationController::class, 'update']);
});
