<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\FileProcessingController;

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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'dashboardRedirect'])->name('dashboard.redirect');
});

Route::get('/redirect', [DashboardController::class, 'redirect'])->name('redirect');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('profile/plan', [ProfileController::class, 'updatePlan'])->name('profile.updatePlan');
    Route::post('profile/cancelPlan', [ProfileController::class, 'cancelPlan'])->name('profile.cancelPlan');
    Route::post('profile/nonRenewPlan', [ProfileController::class, 'nonRenewPlan'])->name('profile.nonRenewPlan');

    Route::get('/personalized', [ArticleController::class, 'index'])->name('personalized.index');
});

Route::get('/plans', [SubscriptionController::class, 'index'])->name('plans.index');
Route::get('/plans/redirect', [SubscriptionController::class, 'redirect'])->name('plans.redirect');

Route::get('/articles', function () {
    return Inertia::render('Articles/Index');
})->name('articles.index');

Route::webhooks('xsolla/webhook');

require __DIR__.'/auth.php';
