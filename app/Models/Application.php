<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{

    use HasFactory;

    protected $fillable = [
        'name', 
        'email', 
        'phone', 
        'message', 
        'animal_id', 
        'pet_adopter_id',
        'application_status_id',
    ];

    public function animal(): BelongsTo {
        return $this->belongsTo(Animal::class);
    }

    public function petAdopter(): BelongsTo {
        return $this->belongsTo(PetAdopter::class);
    }

    public function petAgency() {
        return $this->animal->petAgency;
    }

    public function applicationStatus(): BelongsTo {
        return $this->hasOne(ApplicationStatus::class);
    }

}
