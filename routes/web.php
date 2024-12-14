<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BeerSpotController as AdminBeerSpotController;
use App\Http\Controllers\Admin\BeerController as AdminBeerController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Public home route
Route::get('/', function () {
    return redirect()->route('home');
})->name('home');

// User routes
Route::middleware('auth')->group(function () {
    // This is the user's home/dashboard
    Route::get('/home', function () {
        return view('dashboard');
    })->name('home');
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('home');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Panel administratora
Route::prefix('admin')->middleware(['auth', AdminMiddleware::class])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Beer Spots bulk actions
    Route::prefix('beer-spots')->group(function () {
        Route::post('/bulk-verify', [AdminBeerSpotController::class, 'bulkVerify'])
            ->name('admin.beer-spots.bulk-verify');
        Route::post('/bulk-unverify', [AdminBeerSpotController::class, 'bulkUnverify'])
            ->name('admin.beer-spots.bulk-unverify');
        Route::delete('/bulk-destroy', [AdminBeerSpotController::class, 'bulkDestroy'])
            ->name('admin.beer-spots.bulk-destroy');
        Route::put('/bulk-status/{action}', [AdminBeerSpotController::class, 'bulkUpdateStatus'])
            ->name('admin.beer-spots.bulk-status');
    });

    // Zarządzanie punktami sprzedaży
    Route::resource('beer-spots', AdminBeerSpotController::class)->names('admin.beer-spots');
    Route::post('beer-spots/{beerSpot}/verify', [AdminBeerSpotController::class, 'verify'])
        ->name('admin.beer-spots.verify');
    Route::post('beer-spots/{beerSpot}/unverify', [AdminBeerSpotController::class, 'unverify'])
        ->name('admin.beer-spots.unverify');
    
    // Zarządzanie piwami
    Route::resource('beers', AdminBeerController::class)->names('admin.beers');
    
    // Reviews bulk actions
    Route::post('reviews/bulk/{action}', [AdminReviewController::class, 'bulk'])
        ->name('admin.reviews.bulk')
        ->where('action', 'approve|reject|delete');

    // Zarządzanie opiniami
    Route::resource('reviews', AdminReviewController::class)->names('admin.reviews');
    Route::post('reviews/{review}/approve', [AdminReviewController::class, 'approve'])
        ->name('admin.reviews.approve');
    Route::post('reviews/{review}/reject', [AdminReviewController::class, 'reject'])
        ->name('admin.reviews.reject');
    
    // Zarządzanie użytkownikami
    Route::resource('users', UserController::class)->names('admin.users');
    
    // Zarządzanie powiadomieniami
    Route::prefix('notifications')->name('admin.notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('{notification}/mark-read', [NotificationController::class, 'markAsRead'])
            ->name('mark-read');
        Route::post('{notification}/mark-unread', [NotificationController::class, 'markAsUnread'])
            ->name('mark-unread');
        Route::post('mark-all-read', [NotificationController::class, 'markAllAsRead'])
            ->name('mark-all-read');
        Route::delete('{notification}', [NotificationController::class, 'destroy'])
            ->name('destroy');
        Route::delete('/', [NotificationController::class, 'destroyAll'])
            ->name('destroy-all');
    });
});

require __DIR__.'/auth.php';