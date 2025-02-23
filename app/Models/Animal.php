<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Animal extends Model
{

    protected $fillable = [
        'sex',
        'name',
        'description',
        'birthdate',
        'coat_length',
        'pet_agency_id',
        'pet_adopter_id',
        'animal_type_id',
        'animal_breed_id',
        'animal_coat_color_id',
        'status_id',
    ];

    use HasFactory;

    public function petAgency(): HasOne {
        return $this->hasOne(PetAgency::class);
    }

    public function petAdopter(): HasOne {
        return $this->hasOne(PetAdopter::class);
    }

    public function animalTypes(): HasOne {
        return $this->hasOne(AnimalType::class);
    }

    public function animalCoatColor(): HasOne {
        return $this->hasOne(AnimalCoatColor::class);
    }

    public function animalBreeds(): HasOne {
        return $this->hasOne(AnimalBreed::class);
    }

    public function animalPictures(): HasMany {
        return $this->hasMany(AnimalPicture::class);
    }

    public function applications(): HasMany {
        return $this->hasMany(Application::class);
    }

}
