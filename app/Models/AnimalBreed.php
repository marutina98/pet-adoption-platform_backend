<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnimalBreed extends Model
{

    protected $fillable = [
        'name',
        'description',
        'animal_type_id',
    ];

    use HasFactory;

    public function animals(): HasMany {
        return $this->HasMany(Animal::class);
    }

    public function animalTypes(): HasOne {
        return $this->hasOne(AnimalType::class);
    }

}
