<?php

namespace App\Models;

use App\Models\User;
use App\Models\Animal;
use App\Models\PetAgency;
use App\Models\Application;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PetAdopter extends Model
{

    protected $fillable = [
        'name',
        'city',
        'email',
        'phone',
        'user_id',
        'country',
        'picture',
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

    public function sentApplications(): HasMany {
        return $this->hasMany(Application::class);
    }

    public function pendingReviews($_user = null) {

        // get the user and load given reviews.

        $user = isset($_user) ? $_user : $this->user;

        // $user = $this->user;
        $user->load('reviewsGiven');

        // get adopted animals
        // get pet agency ids
        // get reviewed pet agencies
        // get non-reviewed pet agencies

        $adoptedAnimals = $this->animals;

        $petAgencyIds = $adoptedAnimals->pluck('pet_agency_id')->toArray();
        
        $reviewedPetAgencyUserIds = $user->reviewsGiven->pluck('reviewee_id')->toArray();
        $reviewedPetAgencies = PetAgency::whereIn('user_id', $reviewedPetAgencyUserIds)->get();
        $reviewedPetAgencyIds = $reviewedPetAgencies->pluck('id')->toArray();

        $notReviewedPetAgencyIds = array_diff($petAgencyIds, $reviewedPetAgencyIds);

        return PetAgency::with('user')->whereIn('id', $notReviewedPetAgencyIds)->get();

    }

}
