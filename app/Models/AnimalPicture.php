<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AnimalPicture extends Model
{

    protected $fillable = [
        'path',
        'animal_id',
    ];

    use HasFactory;

    public function animal(): HasOne {
        return $this->hasOne(Animal::class);
    }

}
