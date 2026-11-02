<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/', fn () => redirect()->route('dashboard'));

    // Dashboard
    Route::get('/dashboard', [CourseController::class, 'index'])
        ->name('dashboard');

    // Courses CRUD
    Route::post('/courses', [CourseController::class, 'store'])
        ->name('courses.store');

    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])
        ->whereNumber('course')
        ->name('courses.edit');

    Route::get('/courses/{course}', [CourseController::class, 'show'])
        ->whereNumber('course')
        ->name('courses.show');

    Route::put('/courses/{course}', [CourseController::class, 'update'])
        ->whereNumber('course')
        ->name('courses.update');

    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])
        ->whereNumber('course')
        ->name('courses.destroy');

    // Books CRUD
    Route::get('/books', [BookController::class, 'index'])
        ->name('books.index');

    Route::post('/books', [BookController::class, 'store'])
        ->name('books.store');

    Route::get('/books/{book}/edit', [BookController::class, 'edit'])
        ->whereNumber('book')
        ->name('books.edit');

    Route::put('/books/{book}', [BookController::class, 'update'])
        ->whereNumber('book')
        ->name('books.update');

    Route::delete('/books/{book}', [BookController::class, 'destroy'])
        ->whereNumber('book')
        ->name('books.destroy');
});
