<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\EmailListController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscribersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    // only to make things easier 😅
    Auth::loginUsingId(2);
    return to_route('dashboard');
});

Route::view('/dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/email-list', [EmailListController::class, 'index'])->name('email-list.index');
    Route::get('/email-list/create', [EmailListController::class, 'create'])->name('email-list.create');
    Route::post('/email-list/create', [EmailListController::class, 'store']);
    Route::get('/email-list/{emailList}/subscribers', [SubscribersController::class, 'index'])->name('subscribers.index');
    Route::get('/email-list/{emailList}/subscribers/create', [SubscribersController::class, 'create'])->name('subscribers.create');
    Route::delete('/email-list/{emailList}/subscribers/{subscriber}', [SubscribersController::class, 'destroy'])->name('subscribers.destroy');
    Route::post('/email-list/{emailList}/subscribers/create', [SubscribersController::class, 'store']);

    Route::resource('email-template', EmailTemplateController::class);
    Route::resource('campaign', CampaignController::class)->only(['index', 'create', 'destroy']);
});

require __DIR__.'/auth.php';
