<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CardTypeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/es/club', [ClubController::class, 'index'])->name('club.index');
Route::get('/es/club/', [ClubController::class, 'index']);

Route::get('/es/command', [TrainerController::class, 'index'])->name('trainers.index');
Route::get('/es/command/{alias}', [TrainerController::class, 'show'])->name('trainers.show');

Route::get('/es/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/es/news/{alias}', [NewsController::class, 'show'])->name('news.show');

Route::get('/es/events', [EventController::class, 'index'])->name('events.index');
Route::get('/es/events/', [EventController::class, 'index']);

Route::get('/es/job', [JobController::class, 'index'])->name('jobs.index');
Route::get('/es/job/', [JobController::class, 'index']);

Route::get('/card/shares', [ShareController::class, 'index'])->name('shares.index');
Route::get('/card/shares/{alias}', [ShareController::class, 'show'])->name('shares.show');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{alias}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/card/type', [CardTypeController::class, 'index'])->name('card.type');
