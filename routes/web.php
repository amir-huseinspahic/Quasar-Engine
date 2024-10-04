<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\PostCategoriesController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/panel', [DashboardController::class, 'index'])
    ->middleware(['auth', 'userLocale'])
    ->name('dashboard');

Route::group(['middleware' => ['auth', 'permission:create posts', 'userLocale']], function () {
    Route::get('/panel/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/panel/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/panel/posts/create', [PostController::class, 'store'])->name('posts.store');
    Route::get('/panel/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/panel/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::post('/panel/posts/{post}/edit', [PostController::class, 'update'])->name('posts.update');
    Route::post('/panel/posts/{post}/destroy', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/panel/posts/{post}/media/destroy/{id}', [PostController::class, 'destroyMedia'])->name('posts.media.destroy');
    Route::post('/panel/posts/categories/create', [PostCategoriesController::class, 'store'])->name('posts.categories.store');
    Route::post('/panel/posts/categories/{id}/destroy', [PostCategoriesController::class, 'destroy'])->name('posts.categories.destroy');
});

Route::group(['middleware' => ['auth', 'role_or_permission:admin|view users', 'userLocale']], function () {
    Route::get('/panel/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/panel/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/panel/users/create', [UserController::class, 'store'])->name('users.store');
    Route::get('/panel/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/panel/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/panel/users/{user}/edit', [UserController::class, 'update'])->name('users.update');
    Route::post('/panel/users/{user}/destroy', [UserController::class, 'destroy'])->name('users.destroy');
});
Route::post('/panel/updateUserPreferences', [ProfileController::class, 'updatePreferences'])->middleware('auth')->name('updateUserPreferences');

Route::group(['middleware' => ['auth', 'role_or_permission:admin', 'userLocale']], function () {
    Route::get('/panel/gallery', [GalleryController::class, 'index'])->name('gallery.index');
    Route::post('/panel/gallery/add', [GalleryController::class, 'store'])->name('gallery.store');
    Route::post('/panel/gallery/destroy/{id}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
});

Route::group(['middleware' => ['auth', 'userLocale']], function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
});

 // This has to be last!
Route::group(['middleware' => ['guestLocale']], function () {
    Route::get('/', [HomepageController::class, 'index'])->name('home');
    Route::get('/post/{slug}', [HomepageController::class, 'postDetails'])->name('home.postDetails');
});
Route::post('/homepage/handleLocale', [HomepageController::class, 'handleHomepageLocale'])->name('home.handleLocale');









//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});
//
//Route::get('/dashboard', function () {
//    return Inertia::render('Dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
//
//Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});

require __DIR__.'/auth.php';
