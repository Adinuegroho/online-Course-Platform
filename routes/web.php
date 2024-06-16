<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', [
    \App\Http\Controllers\FrontController::class, 'index'
])->name('front.index');

Route::get('/details/{course:slug}', [
    \App\Http\Controllers\FrontController::class, 'details'
])->name('front.details');

Route::get('/category/{category:slug}', [
    \App\Http\Controllers\FrontController::class, 'category'
])->name('front.category');

Route::get('/pricing', [
    \App\Http\Controllers\FrontController::class, 'pricing'
])->name('front.pricing');

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Harus login sebelum membuat transaksi
    Route::get('/checkout', [
        \App\Http\Controllers\FrontController::class, 'checkout'
    ])->name('front.checkout')->middleware('role:student');

    Route::post('/checkout/store', [
        \App\Http\Controllers\FrontController::class, 'checkout_store'
    ])->name('front.checkout.store')->middleware('role:student');

    // domain.com/learning/100.5 = belajar install js pada mac os
    Route::get('/learning/{course}/{courseVideoId}', [
        \App\Http\Controllers\FrontController::class, 'learning'
    ])->name('front.learning')->middleware('role:student|teacher|owner');


    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('categories', \App\Http\Controllers\CategoryController::class)
            ->middleware('role:owner');

        Route::resource('teachers', \App\Http\Controllers\TeacherController::class)
            ->middleware('role:owner');

        Route::resource('courses', \App\Http\Controllers\CourseController::class)
            ->middleware('role:owner|teacher');

        Route::resource('subscribe_transactions', \App\Http\Controllers\SubscribeTransactionController::class)
            ->middleware('role:owner');

        Route::get('/add/video/{course:id}', [\App\Http\Controllers\CourseVideoController::class, 'create'])
            ->middleware('role:teacher|owner')
            ->name('course.add_video');

        Route::post('/add/video/save/{course:id}', [\App\Http\Controllers\CourseVideoController::class, 'store'])
            ->middleware('role:teacher|owner')
            ->name('course.add_video.save');

        Route::resource('course_videos', \App\Http\Controllers\CourseVideoController::class)
            ->middleware('role:owner|teacher');
    });

    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
