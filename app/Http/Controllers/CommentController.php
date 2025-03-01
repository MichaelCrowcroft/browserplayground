<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comments\StoreCommentRequest;
use App\Models\Listing;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Listing $listing)
    {
        $data = $request->validated();

        $listing->comments()->create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);
    }

    public function update(UpdateReviewRequest $request, Review $review)
    {
        $data = $request->validated();

        $review->update($data);

        return Redirect::to($review->product->showRoute(['page' => $request->query('page')]))
            ->banner('Review Updated');
    }

    public function delete(Request $request, Review $review)
    {
        Gate::authorize('delete', $review);

        $review->delete();

        return Redirect::to($review->product->showRoute(['page' => $request->query('page')]))
            ->bannerDanger('Review Deleted');
    }
}