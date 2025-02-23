<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnimalType extends Model
{

    protected $fillable = [
        'name',
        'description',
    ];

    use HasFactory;

    public function animals(): HasMany {
        return $this->hasMany(Animal::class);
    }

    public function animalBreeds(): HasMany {
        return $this->hasMany(AnimalBreed::class);
    }

}