<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InOutController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisposalController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StockEntryController;
use App\Http\Controllers\TransactionLogController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\UsageLogController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ── Auth Routes (guest only) ──
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Protected Routes ──
Route::middleware('auth')->group(function () {
    // Admin-only Routes
    Route::middleware(['admin'])->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('/users/{user}/activity', [UserController::class, 'showActivity'])->name('users.activity');

        // Admin Record Management
        Route::get('/admin/records', [AdminController::class, 'index'])->name('admin.records.index');

        // Stock Entries
        Route::get('/admin/stock-entries/{stockEntry}/edit', [AdminController::class, 'editStockEntry'])->name('admin.stock-entries.edit');
        Route::patch('/admin/stock-entries/{stockEntry}', [AdminController::class, 'updateStockEntry'])->name('admin.stock-entries.update');
        Route::delete('/admin/stock-entries/{stockEntry}', [AdminController::class, 'destroyStockEntry'])->name('admin.stock-entries.destroy');

        // Usage Logs
        Route::get('/admin/usage-logs/{usageLog}/edit', [AdminController::class, 'editUsageLog'])->name('admin.usage-logs.edit');
        Route::patch('/admin/usage-logs/{usageLog}', [AdminController::class, 'updateUsageLog'])->name('admin.usage-logs.update');
        Route::delete('/admin/usage-logs/{usageLog}', [AdminController::class, 'destroyUsageLog'])->name('admin.usage-logs.destroy');

        // Borrows
        Route::get('/admin/borrows/{borrow}/edit', [AdminController::class, 'editBorrow'])->name('admin.borrows.edit');
        Route::patch('/admin/borrows/{borrow}', [AdminController::class, 'updateBorrow'])->name('admin.borrows.update');
        Route::delete('/admin/borrows/{borrow}', [AdminController::class, 'destroyBorrow'])->name('admin.borrows.destroy');

        // Transfers
        Route::get('/admin/transfers/{transfer}/edit', [AdminController::class, 'editTransfer'])->name('admin.transfers.edit');
        Route::patch('/admin/transfers/{transfer}', [AdminController::class, 'updateTransfer'])->name('admin.transfers.update');
        Route::delete('/admin/transfers/{transfer}', [AdminController::class, 'destroyTransfer'])->name('admin.transfers.destroy');

        // Disposals
        Route::get('/admin/disposals/{disposal}/edit', [AdminController::class, 'editDisposal'])->name('admin.disposals.edit');
        Route::patch('/admin/disposals/{disposal}', [AdminController::class, 'updateDisposal'])->name('admin.disposals.update');
        Route::delete('/admin/disposals/{disposal}', [AdminController::class, 'destroyDisposal'])->name('admin.disposals.destroy');
    });

    // Redirect root to dashboard
    Route::get('/', fn () => redirect()->route('dashboard'));

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Settings
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Resource routes
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('units', UnitController::class)->except(['show']);
    Route::resource('locations', LocationController::class)->except(['show']);
    Route::resource('items', ItemController::class);
    Route::resource('staff', StaffController::class)->except(['show']);

    // Usage Routes
    Route::get('/usage/create', [UsageLogController::class, 'create'])->name('usage.create');
    Route::post('/usage', [UsageLogController::class, 'store'])->name('usage.store');

    // Transaction Logs
    Route::get('/logs', [TransactionLogController::class, 'index'])->name('logs.index');

    // Stock entries (nested under items)
    Route::get('/items/{item}/stock/create', [StockEntryController::class, 'create'])->name('stock.create');
    Route::post('/items/{item}/stock', [StockEntryController::class, 'store'])->name('stock.store');

    // Combined In and Out page
    Route::get('/in-out', [InOutController::class, 'index'])->name('in-out.index');

    // Movement Routes (Transfer, Borrow, Return) — keep create/store/edit/update routes
    Route::resource('transfers', TransferController::class)->only(['index', 'create', 'store']);
    Route::resource('borrows', BorrowController::class)->only(['index', 'create', 'store']);
    Route::resource('returns', ReturnController::class)->only(['index', 'edit', 'update'])->parameters([
        'returns' => 'borrow',
    ]);

    // Disposal Routes
    Route::get('/disposals/create', [DisposalController::class, 'create'])->name('disposals.create');
    Route::post('/disposals', [DisposalController::class, 'store'])->name('disposals.store');
});
