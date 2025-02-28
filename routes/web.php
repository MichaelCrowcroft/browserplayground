<?php

use App\Http\Controllers\ListingController;
use Illuminate\Support\Facades\Route;

Route::get('/home', function () {
    return redirect('/');
})->name('home');



// Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return redirect('/');
        // return Inertia::render('dashboard');
    })->name('dashboard');
// });

Route::get('/', [ListingController::class, 'index'])->name('listings.index');
Route::get('/listings', function () {
    return redirect('/');
});
Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');
Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create');
Route::get('/listings/{listing}', [ListingController::class, 'show'])->name('listings.show');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
