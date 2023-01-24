<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BookController2;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Foundation\Application;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'books' => Book::query()->orderByDesc('publication_year')->take(10)->get(),
        'totalBooks' => Book::query()->count(),
        'totalBooksLoaned' => Loan::query()->whereNull('loan_end_date')->count(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('books', BookController::class)->middleware(['auth', 'verified']);
Route::resource('books.loans', LoanController::class)->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
