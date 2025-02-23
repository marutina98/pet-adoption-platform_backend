<?php

namespace App\Models;

use App\Models\User;
use App\Models\Animal;
use App\Models\PetAdopter;
use App\Models\Application;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PetAgency extends Model
{

    protected $fillable = [
        'name',
        'city',
        'email',
        'phone',
        'user_id',
        'country',
        'picture',
        'website',
        'description',
        'postal_code',
        'street_address',
    ];

    use HasFactory;

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function animals(): HasMany {
        return $this->hasMany(Animal::class);
    }

    public function receivedApplications(): HasManyThrough {
        return $this->hasManyThrough(Application::class, Animal::class, 'pet_agency_id', 'animal_id');
    }

    public function pendingReviews($_user = null) {

        // get the user and load given reviews.

        $user = isset($_user) ? $_user : $this->user;

        // $user = $this->user;
        $user->load('reviewsGiven');

        // get adopted animals
        // get pet adopter ids
        // get reviewed pet adopters
        // get non-reviewed pet adopters

        $adoptedAnimals = $this->animals->filter(function ($animal) {
            return !is_null($animal->pet_adopter_id);
        });

        $petAdopterIds = $adoptedAnimals->pluck('pet_adopter_id')->toArray();

        $reviewedPetAdopterUserIds = $user->reviewsGiven->pluck('reviewee_id')->toArray();
        $reviewedPetAdopters = PetAdopter::whereIn('user_id', $reviewedPetAdopterUserIds)->get();
        $reviewedPetAdoptersIds = $reviewedPetAdopters->pluck('id')->toArray();

        $notReviewedPetAdopterIds = array_diff($petAdopterIds, $reviewedPetAdoptersIds);

        return PetAdopter::with('user')->whereIn('id', $notReviewedPetAdopterIds)->get();

    }

}
