<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreListingRequest;
use App\Http\Resources\ListingResource;
use App\Models\Listing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ListingController extends Controller
{
    public function index()
    {
        $listings = Listing::with('user')->paginate(21);

        return Inertia::render('listings/index', [
            'listings' => fn () => ListingResource::collection($listings),
        ]);
    }

    public function create()
    {
        return Inertia::render('listings/create');
    }

    public function store(StoreListingRequest $request)
    {
        $request->validated();

        $listing = Listing::create([
            ...$request->validated(),
            'type' => 'game',
            'user_id' => Auth::id() ?? null,
        ]);

        return Redirect::to('/');
        // return Redirect::to($listing->showRoute());
    }

    public function show(Listing $listing)
    {
        return Inertia::render('listings/show', [
            'listing' => fn () => ListingResource::make($listing),
        ]);
    }
}
