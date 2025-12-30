<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\EmailListController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscribersController;
use App\Http\Controllers\TrackingController;
use App\Http\Middleware\CampaignCreateSessionControl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/t/{mail}/o', [TrackingController::class, 'openings'])->name('tracking.openings');
Route::get('/t/{mail}/c', [TrackingController::class, 'clicks'])->name('tracking.clicks');

Route::redirect('/dashboard', '/campaign')->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware([])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/email-list', [EmailListController::class, 'index'])->name('email-list.index');
    Route::get('/email-list/create', [EmailListController::class, 'create'])->name('email-list.create');
    Route::post('/email-list/create', [EmailListController::class, 'store']);
    Route::get('/email-list/{emailList}/subscribers', [SubscribersController::class, 'index'])->name('subscribers.index');
    Route::get('/email-list/{emailList}/subscribers/create', [SubscribersController::class, 'create'])->name('subscribers.create');
    Route::delete('/email-list/{emailList}/subscribers/{subscriber}', [SubscribersController::class, 'destroy'])->name('subscribers.destroy');
    Route::delete('/email-list/{emailList}', [EmailListController::class, 'destroy'])->name('email-list.destroy');
    Route::post('/email-list/{emailList}/subscribers/create', [SubscribersController::class, 'store']);

    Route::resource('email-template', EmailTemplateController::class);
    Route::delete('/campaign/{campaign}', [CampaignController::class, 'destroy'])->name('campaign.destroy');
    Route::get('/campaign', [CampaignController::class, 'index'])->name('campaign.index');
    Route::patch('/campaign/{campaign}/restore', [CampaignController::class, 'restore'])->withTrashed()->name('campaign.restore');

    Route::get('/campaign/create/{tab?}', [CampaignController::class, 'create'])->name('campaign.create')->middleware(CampaignCreateSessionControl::class);
    Route::post('/campaign/create/{tab?}', [CampaignController::class, 'store']);
    Route::get('/campaign/{campaign}/{what?}', [CampaignController::class, 'show'])->name('campaign.show')->withTrashed();
});

require __DIR__.'/auth.php';
