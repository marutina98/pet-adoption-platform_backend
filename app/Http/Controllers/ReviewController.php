<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Animal;
use App\Models\Review;
use App\Models\PetAgency;
use App\Models\PetAdopter;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReviewController extends Controller
{
    
    public function index() {
        return Review::all();
    }

    public function showGivenReviews(User $user) {
        return Review::where('reviewer_id', $user->id)->get();
    }

    public function showReceivedReviews(User $user) {
        return Review::where('reviewee_id', $user->id)->get();
    }

    public function store() {

        $reviewer = auth()->user();

        $data = request()->validate([
            'reviewee_id' => ['required', 'integer', 'exists:users,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string'],
        ]);

        $data['comment'] = strip_tags($data['comment']);

        $reviewee = User::find($data['reviewee_id']);

        if (!Gate::allows('create-review', $reviewee)) {
            return response()->json([
                'message' => 'You cannot leave this review, you are trying to review yourself or a review is already present.',
            ], 403);
        }

        $data['reviewer_id'] = $reviewer->id;

        $review = Review::create($data);

        return response()->json([
            'message' => 'Review Creation: Success!',
            'object' => $review,
        ], 200);

    }

    public function showPendingReviews() {

        $user = auth()->user();

        if ($user->is_pet_agency) {
            $user->load('petAgency');
            return $user->petAgency->pendingReviews();
        }

        if ($user->is_pet_adopter) {
            $user->load('petAdopter');
            return $user->petAdopter->pendingReviews();
        }

        return [];

    }

}
