<?php

declare(strict_types=1);

use App\Http\Controllers\IdeaController;
use App\Http\Controllers\IdeaImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StepController;
use Illuminate\Support\Facades\Route;
use Pest\Plugins\Profile;

Route::redirect('/', '/ideas');

Route::get('/ideas', [IdeaController::class, 'index'])
    ->name('idea.index')
    ->middleware('auth');

Route::get('/ideas/{idea}', [IdeaController::class, 'show'])
    ->name('idea.show')
    ->middleware('auth');


Route::patch('/ideas/{idea}', [IdeaController::class, 'update'])
    ->name('idea.update')
    ->middleware('auth');


Route::patch('/step/{step}', [StepController::class, 'update'])
    ->name('step.update')
    ->middleware('auth');

Route::post('/ideas', [IdeaController::class, 'store'])
    ->name('idea.store')
    ->middleware('auth');

Route::delete('/ideas/{idea}', [IdeaController::class, 'destroy'])
    ->name('idea.destroy')
    ->middleware('auth');

Route::delete('/ideas/{idea}/image', [IdeaImageController::class, 'destroy'])
    ->name('image.destroy')
    ->middleware('auth');

Route::get('/register', [RegisterUserController::class, 'create'])
    ->name('register.create')
    ->middleware('guest');

Route::post('/register', [RegisterUserController::class, 'store'])
    ->name('register.store')
    ->middleware('guest');

Route::get('/login', [SessionController::class, 'create'])
    ->name('login.create')
    ->middleware('guest');

Route::post('/login', [SessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('/logout', [SessionController::class, 'destroy'])
    ->name('logout.destroy')
    ->middleware('auth');

Route::get('/profile' , [ProfileController::class , 'edit'])
->name('profile.edit')
->middleware('auth');

Route::patch('/profile' , [ProfileController:: class , 'update'])
->name('profile.update')
->middleware('auth');
