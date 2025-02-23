<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApplicationStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function applications(): HasMany {
        return $this->hasMany(Application::class);
    }

}
