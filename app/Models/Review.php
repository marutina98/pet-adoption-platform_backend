<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{

    protected $fillable = [
        'rating',
        'comment',
        'reviewee_id',
        'reviewer_id',
    ];

    use HasFactory;

    public function reviewee(): BelongsTo {
        return $this->belongsTo(User::class, 'reviewee_id');
    }

    public function reviewer(): BelongsTo {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

}
