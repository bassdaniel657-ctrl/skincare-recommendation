<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EvaluateController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\HistoryController as UserHistoryController;
use App\Services\UpdateTfidfVectorService as UpdateVector;

// Landing page
Route::get('/', function () {
    return view('index');
});

// =========================
// AUTH ROUTES
// =========================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =========================
// RECOMMENDATION FORM (GET publik) - tampilkan form ke siapa saja
// =========================
Route::get('/recommend', [ProductController::class, 'showForm'])->name('recommend.form');

// =========================
// PROSES REKOMENDASI (POST) - harus login
// =========================
// Kita arahkan POST ke route di bawah yang memiliki middleware auth.
// Hal ini mencegah penyimpanan user_query tanpa user_id.
Route::post('/user/recommend', [ProductController::class, 'generate'])
    ->middleware('auth')
    ->name('user.recommend');

// =========================
// ADMIN ROUTES
// =========================
Route::prefix('admin')->middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dasboard/chart', [DashboardController::class, 'chart'])->name('admin.chart');
    Route::patch('/update-vector', [DashboardController::class, 'updateVector'])->middleware(['no.timeout'])->name('admin.update.vector');

    Route::resource('product', AdminProductController::class);

    Route::get('history', [HistoryController::class, 'index'])->name('admin.history');
    Route::get('history/{id}', [HistoryController::class, 'show'])->name('admin.history.show');

    Route::get('evaluate/hitrate', [EvaluateController::class, 'hitrate'])->name('admin.evaluate.hitrate');
    Route::patch('evaluate/update', [EvaluateController::class, 'update'])->name('admin.evaluate.update');

});

// =========================
// USER ROUTES
// =========================
Route::prefix('user')->middleware(['auth'])->group(function () {

    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

    // (Catatan) POST /user/recommend sudah didefinisikan di atas dengan middleware auth
    // supaya user yang submit pasti login.

    // HISTORY
    Route::get('history', [UserHistoryController::class, 'index'])->name('user.history');
    Route::get('history/{id}', [UserHistoryController::class, 'show'])->name('user.history.show');

    // FEEDBACK
    // Route::post('/feedback', [ProductController::class, 'storeFeedback'])->name('user.feedback');
    Route::patch('/feedback', [ProductController::class, 'updateFeedback'])->name('user.feedback');

    // METRICS
    Route::get('/metrics/{userQueryId}', [ProductController::class, 'getEvaluation'])->name('user.metrics');
});
