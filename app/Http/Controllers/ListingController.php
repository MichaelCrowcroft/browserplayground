<?php

namespace App\Http\Controllers;

use App\Http\Requests\Listings\ListingRequest;
use App\Http\Resources\ListingResource;
use App\Models\Listing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ListingController extends Controller
{
    public function index()
    {
        $listings = Listing::withCount('comments')->paginate(100);

        return Inertia::render('listings/Index', [
            'listings' => fn () => ListingResource::collection($listings),
        ]);
    }

    public function create()
    {
        return Inertia::render('listings/Create');
    }

    public function store(ListingRequest $request)
    {
        $data = $request->validated();

        if($request->hasFile('image')) {
            $path = $request->file('image')->store('cover-images');
            $data['image'] = $path;
        }

        $listing = Listing::create([
            ...$data,
            'type' => 'game',
            'user_id' => Auth::id() ?? null,
        ]);

        return Redirect::to($listing->showRoute());
    }

    public function show(Listing $listing)
    {
        $listing->load('comments.user');

        return Inertia::render('listings/Show', [
            'listing' => fn () => ListingResource::make($listing),
        ]);
    }

    public function edit(Listing $listing)
    {
        return Inertia::render('listings/Edit', [
            'listing' => fn () => ListingResource::make($listing),
        ]);
    }

    public function update(ListingRequest $request, Listing $listing)
    {
        $data = $request->validated();

        if($request->hasFile('image')) {
            if($listing->image) {
                Storage::delete($listing->image);
            }

            $path = $request->file('image')->store('cover-images');
            $data['image'] = $path;
        }

        $listing->update($data);

        return Redirect::to($listing->showRoute());
    }
}
